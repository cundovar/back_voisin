<?php
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

// Initialisez la configuration avec les clés privées et publiques
$config = Configuration::forAsymmetricSigner(
    new Sha256(),
    InMemory::file('%kernel.project_dir%/config/jwt/private.pem', 'lenine'),
    InMemory::file('%kernel.project_dir%/config/jwt/public.pem')
);

// Génération d'un token
$token = $config->builder()
    ->issuedBy('http://localhost:8000') // Vous pouvez ajuster les valeurs ici
    ->permittedFor('http://localhost:3000')
    ->identifiedBy('4f1g23a12aa')
    ->issuedAt(new DateTimeImmutable())
    ->canOnlyBeUsedAfter(new DateTimeImmutable())
    ->expiresAt((new DateTimeImmutable())->modify('+1 hour'))
    ->withClaim('username', 'cundo')
    ->getToken($config->signer(), $config->signingKey());

echo $token->toString();
