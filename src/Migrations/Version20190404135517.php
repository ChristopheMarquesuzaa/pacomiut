<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404135517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, shortname VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temp_formulaire (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, actif TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6D092684CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formulaire (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, type INT NOT NULL, created_at DATETIME NOT NULL, actif TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, accompagnateur INT DEFAULT NULL, INDEX IDX_5BDD01A8CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_404021BF5053569B (formulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE porte (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_7D4B659C5053569B (formulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visiteur (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT NOT NULL, porte_id INT NOT NULL, accompagnateur INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4EA587B85053569B (formulaire_id), INDEX IDX_4EA587B86BCC8323 (porte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visiteur_formation (visiteur_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_6450C1257F72333D (visiteur_id), INDEX IDX_6450C1255200282E (formation_id), PRIMARY KEY(visiteur_id, formation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, sexe TINYINT(1) NOT NULL, age INT DEFAULT NULL, type VARCHAR(255) NOT NULL, departement VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, ecole VARCHAR(255) DEFAULT NULL, formation VARCHAR(255) DEFAULT NULL, qualite INT DEFAULT NULL, utile TINYINT(1) DEFAULT NULL, extra_utile VARCHAR(255) DEFAULT NULL, candidat TINYINT(1) DEFAULT NULL, extra_candidat VARCHAR(255) DEFAULT NULL, autre VARCHAR(255) DEFAULT NULL, provenances LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', provenances_autre VARCHAR(255) DEFAULT NULL, motifs_autre VARCHAR(255) DEFAULT NULL, motifs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', date DATETIME NOT NULL, formations TINYTEXT NOT NULL COMMENT \'(DC2Type:array)\', dpt VARCHAR(50) NOT NULL, accompagnateur INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT DEFAULT NULL, tempformulaire_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_831B97225053569B (formulaire_id), INDEX IDX_831B97229633DC23 (tempformulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, block_id INT NOT NULL, title LONGTEXT NOT NULL, answer VARCHAR(255) DEFAULT NULL, type INT NOT NULL, INDEX IDX_B6F7494EE9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE temp_formulaire ADD CONSTRAINT FK_6D092684CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE formulaire ADD CONSTRAINT FK_5BDD01A8CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF5053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id)');
        $this->addSql('ALTER TABLE porte ADD CONSTRAINT FK_7D4B659C5053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id)');
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B85053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id)');
        $this->addSql('ALTER TABLE visiteur ADD CONSTRAINT FK_4EA587B86BCC8323 FOREIGN KEY (porte_id) REFERENCES porte (id)');
        $this->addSql('ALTER TABLE visiteur_formation ADD CONSTRAINT FK_6450C1257F72333D FOREIGN KEY (visiteur_id) REFERENCES visiteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visiteur_formation ADD CONSTRAINT FK_6450C1255200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B97225053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id)');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B97229633DC23 FOREIGN KEY (tempformulaire_id) REFERENCES temp_formulaire (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EE9ED820C FOREIGN KEY (block_id) REFERENCES block (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE temp_formulaire DROP FOREIGN KEY FK_6D092684CCF9E01E');
        $this->addSql('ALTER TABLE formulaire DROP FOREIGN KEY FK_5BDD01A8CCF9E01E');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B97229633DC23');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF5053569B');
        $this->addSql('ALTER TABLE porte DROP FOREIGN KEY FK_7D4B659C5053569B');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B85053569B');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B97225053569B');
        $this->addSql('ALTER TABLE visiteur_formation DROP FOREIGN KEY FK_6450C1255200282E');
        $this->addSql('ALTER TABLE visiteur DROP FOREIGN KEY FK_4EA587B86BCC8323');
        $this->addSql('ALTER TABLE visiteur_formation DROP FOREIGN KEY FK_6450C1257F72333D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EE9ED820C');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE temp_formulaire');
        $this->addSql('DROP TABLE formulaire');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE porte');
        $this->addSql('DROP TABLE visiteur');
        $this->addSql('DROP TABLE visiteur_formation');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE block');
        $this->addSql('DROP TABLE question');
    }
}
