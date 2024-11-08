<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use App\Entity\Utilisateur;

// Mot de passe que vous souhaitez tester
$plainPassword = '01'; // Remplacez '01' par le mot de passe que vous souhaitez tester
$hashedPassword = '$2y$...'; // Remplacez par le hash de votre base de données

// Configuration du hasher
$factory = new PasswordHasherFactory([
    'common' => ['algorithm' => 'bcrypt'],
]);

$passwordHasher = $factory->getPasswordHasher('common');

// Vérifiez si le mot de passe correspond
if ($passwordHasher->verify($hashedPassword, $plainPassword)) {
    echo "Mot de passe correct.";
} else {
    echo "Mot de passe incorrect.";
}
