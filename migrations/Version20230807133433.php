<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807133433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription_formation (id INT AUTO_INCREMENT NOT NULL, id_formation_id INT DEFAULT NULL, id_formateur_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, INDEX IDX_E655E3A771C15D5C (id_formation_id), INDEX IDX_E655E3A7369CFA23 (id_formateur_id), INDEX IDX_E655E3A779F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A771C15D5C FOREIGN KEY (id_formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A7369CFA23 FOREIGN KEY (id_formateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inscription_formation ADD CONSTRAINT FK_E655E3A779F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A771C15D5C');
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A7369CFA23');
        $this->addSql('ALTER TABLE inscription_formation DROP FOREIGN KEY FK_E655E3A779F37AE5');
        $this->addSql('DROP TABLE inscription_formation');
    }
}
