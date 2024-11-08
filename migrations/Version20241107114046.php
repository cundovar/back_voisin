<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107114046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_USERNAME ON voisin_utilisateur');
        $this->addSql('ALTER TABLE voisin_utilisateur ADD email VARCHAR(180) NOT NULL, CHANGE username username VARCHAR(180) DEFAULT NULL, CHANGE date_time_interface created_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON voisin_utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON voisin_utilisateur');
        $this->addSql('ALTER TABLE voisin_utilisateur DROP email, CHANGE username username VARCHAR(180) NOT NULL, CHANGE created_at date_time_interface DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON voisin_utilisateur (username)');
    }
}
