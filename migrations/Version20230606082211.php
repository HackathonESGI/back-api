<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606082211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE meeting_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE meeting_category (id INT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE meeting ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E13912469DE2 FOREIGN KEY (category_id) REFERENCES meeting_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F515E13912469DE2 ON meeting (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE meeting DROP CONSTRAINT FK_F515E13912469DE2');
        $this->addSql('DROP SEQUENCE meeting_category_id_seq CASCADE');
        $this->addSql('DROP TABLE meeting_category');
        $this->addSql('DROP INDEX IDX_F515E13912469DE2');
        $this->addSql('ALTER TABLE meeting DROP category_id');
    }
}
