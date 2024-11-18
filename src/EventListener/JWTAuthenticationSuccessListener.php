<?php 


// src/EventListener/JWTAuthenticationSuccessListener.php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTAuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        // Vérifiez que l'utilisateur est bien une instance de UserInterface
        if (!$user instanceof UserInterface) {
            return;
        }

        // Ajoutez les informations utilisateur dans la réponse
        $data['email'] = $user->getEmail();
        $data['id'] = $user->getId();
        $data['username'] = $user->getUsername();
        $data['roles'] = $user->getRoles();

        // Définissez les données de réponse mises à jour
        $event->setData($data);
    }
}
