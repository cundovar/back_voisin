<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\ObjetRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

}
