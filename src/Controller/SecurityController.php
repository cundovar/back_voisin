<?php 


// src/Controller/SecurityController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


    #[Route(path: '/api/login', name: 'api_loginApi', methods: ['POST'])]
    public function loginApi(Request $request, JWTTokenManagerInterface $jwtManager, LoggerInterface $logger): JsonResponse
    {
        $logger->info("Attempting to log in the user.");
        
        if (!$this->getUser()) {
            $logger->error("No user found, unauthorized access.");
            return new JsonResponse(['error' => 'No user found'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            $logger->error("User is not an instance of UserInterface.");
            return new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        try {
            $logger->info("Generating JWT token for user with ID: " . $user->getId());
            $token = $jwtManager->create($user);
        } catch (\Exception $e) {
            $logger->error("JWT creation failed: " . $e->getMessage());
            return new JsonResponse(['error' => 'JWT creation failed: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        $logger->info("JWT token successfully generated.");
        
        // Réponse avec toutes les informations utilisateur
        return new JsonResponse([
            'id' => $user->getId() || "nill",
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'username' => $user->getUsername(),
            'token' => $token,
        ]);
    }



}

