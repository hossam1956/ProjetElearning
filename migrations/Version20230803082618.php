<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803082618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE choix_reponse CHANGE question_id question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE exercice_id exercice_id INT DEFAULT NULL, CHANGE question question LONGTEXT DEFAULT NULL, CHANGE reponse reponse SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544D823E37A');
        $this->addSql('DROP INDEX IDX_939F4544D823E37A ON ressource');
        $this->addSql('ALTER TABLE ressource DROP section_id, CHANGE lien lien VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF5200282E');
        $this->addSql('DROP INDEX IDX_2D737AEF5200282E ON section');
        $this->addSql('ALTER TABLE section DROP formation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE choix_reponse CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE question CHANGE exercice_id exercice_id INT NOT NULL, CHANGE question question LONGTEXT NOT NULL, CHANGE reponse reponse SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ressource ADD section_id INT DEFAULT NULL, CHANGE lien lien VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('CREATE INDEX IDX_939F4544D823E37A ON ressource (section_id)');
        $this->addSql('ALTER TABLE section ADD formation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('CREATE INDEX IDX_2D737AEF5200282E ON section (formation_id)');
    }
}
