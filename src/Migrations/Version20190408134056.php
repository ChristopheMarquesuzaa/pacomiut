<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408134056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_5FB6DEC75053569B (formulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC75053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id)');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1135053569B');
        $this->addSql('DROP INDEX IDX_136AC1135053569B ON result');
        $this->addSql('ALTER TABLE result ADD reponse_id INT DEFAULT NULL, DROP formulaire_id');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
        $this->addSql('CREATE INDEX IDX_136AC113CF18BB82 ON result (reponse_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113CF18BB82');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP INDEX IDX_136AC113CF18BB82 ON result');
        $this->addSql('ALTER TABLE result ADD formulaire_id INT NOT NULL, DROP reponse_id');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1135053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id)');
        $this->addSql('CREATE INDEX IDX_136AC1135053569B ON result (formulaire_id)');
    }
}
