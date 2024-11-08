<?php 


// src/Controller/SecurityController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
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
    public function loginApi(Request $request, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        // Ajoutez ce logging temporaire
    if (!$this->getUser()) {
        return new JsonResponse(['error' => 'No user found'], JsonResponse::HTTP_UNAUTHORIZED);
    }
    
    $user = $this->getUser();
    if (!$user instanceof UserInterface) {
        return new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    try {
        $token = $jwtManager->create($user);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'JWT creation failed: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return new JsonResponse([
        'id' => $user->getId(),
        'email' => $user->getEmail(),
        'roles' => $user->getRoles(),
        'token' => $token
    ]);
    }
}

