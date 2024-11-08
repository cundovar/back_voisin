<?php

// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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
}
