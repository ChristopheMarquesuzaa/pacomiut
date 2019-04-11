<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408112840 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT NOT NULL, question_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_136AC1135053569B (formulaire_id), INDEX IDX_136AC1131E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1135053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1131E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('DROP TABLE response');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, sexe TINYINT(1) NOT NULL, age INT DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, departement VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ville VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ecole VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, formation VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, qualite INT DEFAULT NULL, utile TINYINT(1) DEFAULT NULL, extra_utile VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, candidat TINYINT(1) DEFAULT NULL, extra_candidat VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, autre VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, provenances LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', provenances_autre VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, motifs_autre VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, motifs LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', date DATETIME NOT NULL, formations TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', dpt VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, accompagnateur INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE result');
    }
}
