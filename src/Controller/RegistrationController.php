<?php

// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        // Création d'un nouvel utilisateur
        $user = new Utilisateur();
        
        // Exemple de récupération du mot de passe en texte clair depuis un formulaire
        $plaintextPassword = $request->request->get('password');

        // Hashage du mot de passe
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        // Sauvegarde de l'utilisateur dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Utilisateur enregistré avec succès');
    }
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
public function registerApi(
    Request $request,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager
): JsonResponse {
    try {
        // Décoder les données JSON du corps de la requête
        $data = json_decode($request->getContent(), true);
        
        // Vérifiez que toutes les données nécessaires sont présentes
        $email = $data['email'] ?? null;
        $username = $data['username'] ?? null;
        $plaintextPassword = $data['password'] ?? null;

        if (!$email || !$username || !$plaintextPassword) {
            throw new \InvalidArgumentException("Tous les champs (email, username, password) sont obligatoires.");
        }

        // Créez un nouvel utilisateur
        $user = new Utilisateur();
        $user->setEmail($email);
        $user->setUsername($username);

        // Hashage du mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);

        // Sauvegarder dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        // Réponse réussie
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
        ], JsonResponse::HTTP_CREATED);

    } catch (\Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
    }
}
}
