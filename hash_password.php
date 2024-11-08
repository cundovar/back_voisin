<?php

use App\Entity\Utilisateur;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

// Charger l'autoloader
require 'vendor/autoload.php';

// Initialiser l'application Symfony
$kernel = new \App\Kernel('dev', true);
$kernel->boot();

// Récupérer les services nécessaires
/** @var UserPasswordHasherInterface $passwordHasher */
$passwordHasher = $kernel->getContainer()->get(UserPasswordHasherInterface::class);
/** @var EntityManagerInterface $entityManager */
$entityManager = $kernel->getContainer()->get(EntityManagerInterface::class);

// Définir l'email et le mot de passe en clair
$email = $argv[1] ?? null;
$plainPassword = $argv[2] ?? null;

if (!$email || !$plainPassword) {
    echo "Usage: php hash_password.php <email> <plain_password>\n";
    exit(1);
}

// Rechercher l'utilisateur par email
$user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

if (!$user) {
    echo "Utilisateur non trouvé.\n";
    exit(1);
}

// Hacher le mot de passe
$hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
$user->setPassword($hashedPassword);

// Sauvegarder le mot de passe haché dans la base de données
$entityManager->persist($user);
$entityManager->flush();

echo "Mot de passe haché et mis à jour avec succès pour l'utilisateur $email.\n";
