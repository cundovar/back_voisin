<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Entity\Utilisateur;
use App\Repository\ObjetRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('api/me', name: 'api_me', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]  // Assure que l'utilisateur a le rôle requis pour accéder à cette méthode
    public function me(LoggerInterface $logger): JsonResponse
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        if ($user) {
            $logger->info('Utilisateur récupéré dans /auth/me', [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
            ]);
        } else {
            $logger->error("Aucun utilisateur authentifié trouvé dans /auth/me");
        }

        // Vérifiez si un utilisateur est authentifié
        if (!$user) {
            return new JsonResponse(['error' => 'Aucun utilisateur trouve'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/api/user/{id}/objets', name: 'api_user_objets', methods: ['GET'])]
    public function getUserObjets(Utilisateur $user, ObjetRepository $objetRepository): JsonResponse
    {
        $objets = $objetRepository->findByUser($user);

        return $this->json($objets);
    }

    #[Route ('/api/user/{id}/objet',name:'api_user_objet',methods:['DELETE'])]
    public function delateUserObjets(Utilisateur $user,ObjetRepository $repo,EntityManagerInterface $entityManager):JsonResponse
 {
 // Récupérer les objets associés à l'utilisateur
 $objets = $repo->findByUser($user);

 if (empty($objets)) {
     return new JsonResponse(['message' => 'Aucun objet trouvé pour cet utilisateur.'], 404);
 }

 try {
     foreach ($objets as $objet) {
         $entityManager->remove($objet); // Supprime chaque objet
     }

     $entityManager->flush(); // Applique les suppressions en base de données

     return new JsonResponse(['message' => 'Tous les objets de l\'utilisateur ont été supprimés.'], 200);
 } catch (\Exception $e) {
     return new JsonResponse(['error' => 'Erreur lors de la suppression des objets.', 'details' => $e->getMessage()], 500);
 }
}

#[Route('/api/user/{userId}/objet/{objetId}', name: 'api_user_objet', methods: ['DELETE'])]
public function deleteUserObjet(
    int $userId,
    int $objetId,
    UtilisateurRepository $userRepo,
    ObjetRepository $objetRepo,
    EntityManagerInterface $entityManager
): JsonResponse {
    // Récupérer l'utilisateur
    $user = $userRepo->find($userId);

    if (!$user) {
        return new JsonResponse(['message' => 'Utilisateur non trouvé.'], 404);
    }

    // Récupérer l'objet
    $objet = $objetRepo->find($objetId);

    if (!$objet) {
        return new JsonResponse(['message' => 'Objet non trouvé.'], 404);
    }

    // Vérifier que l'objet appartient bien à l'utilisateur
    if ($objet->getUser()->getId() !== $user->getId()) {
        return new JsonResponse(['message' => 'Cet objet n\'appartient pas à cet utilisateur.'], 403);
    }

    try {
        // Supprimer l'objet
        $entityManager->remove($objet);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Objet supprimé avec succès.'], 200);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'Erreur lors de la suppression de l\'objet.', 'details' => $e->getMessage()], 500);
    }
}


#[Route('/api/user/{id}/objet', name: 'api_user_add_objet', methods: ['POST'])]
public function addUserObjet(
    int $id,
    Request $request,
    UtilisateurRepository $userRepo,
    EntityManagerInterface $entityManager
): JsonResponse {
    // Récupérer l'utilisateur
    $user = $userRepo->find($id);

    if (!$user) {
        return new JsonResponse(['message' => 'Utilisateur non trouvé.'], 404);
    }

    // Décoder les données de la requête
    $data = json_decode($request->getContent(), true);

    if (empty($data['name'])) {
        return new JsonResponse(['message' => 'Le champ "name" est requis.'], 400);
    }

    try {
        // Créer un nouvel objet
        $objet = new Objet();
        $objet->setTitle($data['tile']);
        $objet->setUser($user); // Associer l'objet à l'utilisateur

        // Persister l'objet en base
        $entityManager->persist($objet);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Objet ajouté avec succès.', 'objet' => ['id' => $objet->getId(), 'name' => $objet->getTitle()]], 201);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'Erreur lors de l\'ajout de l\'objet.', 'details' => $e->getMessage()], 500);
    }
}

 

    #[Route('/api/user/{id}', name: 'update_user', methods: ['PUT'])]
public function update(Request $request, Utilisateur $user, UtilisateurRepository $userRepository,EntityManagerInterface $entityManager): Response
{
    $data = json_decode($request->getContent(), true);

    if (isset($data['username'])) {
        $user->setUsername($data['username']);
    }

    if (isset($data['email'])) {
        $user->setEmail($data['email']);
    }

    if (isset($data['password'])) {
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
    }

    $entityManager->persist($user);
    $entityManager->flush();


    return $this->json($user, Response::HTTP_OK);
}

}

