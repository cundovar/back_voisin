<?php 


// src/Security/CustomAuthenticationSuccessHandler.php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomAuthenticationSuccessHandler
{
    public function __invoke(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        // Vérifiez que l'utilisateur est bien une instance de UserInterface
        if (!$user instanceof UserInterface) {
            return;
        }

        // Ajoutez des informations utilisateur supplémentaires dans la réponse
        $data = [
            'token' => $data['token'],
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            
        ];

        $event->setData($data);
    }
}

