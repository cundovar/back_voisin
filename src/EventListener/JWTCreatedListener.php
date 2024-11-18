<?php

// src/EventListener/JWTCreatedListener.php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        // Vérifiez que l'utilisateur est bien une instance de UserInterface
        if (!$user instanceof UserInterface) {
            return;
        }

        // Ajoutez des informations supplémentaires dans le payload du token
        $payload = $event->getData();
        $payload['username'] = $user->getUsername(); 
     

        // Définissez les données de payload mises à jour
        $event->setData($payload);
    }
}



