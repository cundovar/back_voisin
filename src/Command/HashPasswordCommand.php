<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:hash-password',
    description: 'Hash a plain password for a specific user.',
)]
class HashPasswordCommand extends Command
{
    private $passwordHasher;
    private $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('plainPassword', InputArgument::REQUIRED, 'The plain password to hash');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('plainPassword');

        $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln("User not found.");
            return Command::FAILURE;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("Password hashed and updated successfully for user $email.");
        return Command::SUCCESS;
    }
}
