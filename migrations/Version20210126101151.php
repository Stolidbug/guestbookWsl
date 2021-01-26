<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126101151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference ADD users_id INT NOT NULL');
        $this->addSql('ALTER TABLE conference ADD content TEXT NOT NULL');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C867B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_911533C867B3B43D ON conference (users_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conference DROP CONSTRAINT FK_911533C867B3B43D');
        $this->addSql('DROP INDEX IDX_911533C867B3B43D');
        $this->addSql('ALTER TABLE conference DROP users_id');
        $this->addSql('ALTER TABLE conference DROP content');
    }
}
