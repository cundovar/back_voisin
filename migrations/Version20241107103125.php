<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107103125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voisin_category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voisin_objet (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(100) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_FE48DD0012469DE2 (category_id), INDEX IDX_FE48DD00A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voisin_utilisateur (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_time_interface DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE voisin_objet ADD CONSTRAINT FK_FE48DD0012469DE2 FOREIGN KEY (category_id) REFERENCES voisin_category (id)');
        $this->addSql('ALTER TABLE voisin_objet ADD CONSTRAINT FK_FE48DD00A76ED395 FOREIGN KEY (user_id) REFERENCES voisin_utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voisin_objet DROP FOREIGN KEY FK_FE48DD0012469DE2');
        $this->addSql('ALTER TABLE voisin_objet DROP FOREIGN KEY FK_FE48DD00A76ED395');
        $this->addSql('DROP TABLE voisin_category');
        $this->addSql('DROP TABLE voisin_objet');
        $this->addSql('DROP TABLE voisin_utilisateur');
    }
}
