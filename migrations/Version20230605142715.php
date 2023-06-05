<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605142715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "group_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE meeting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE meeting_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tour_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, slug VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE meeting (id INT NOT NULL, patient_id INT DEFAULT NULL, tour_id INT DEFAULT NULL, starting_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ending_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, notes TEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F515E1396B899279 ON meeting (patient_id)');
        $this->addSql('CREATE INDEX IDX_F515E13915ED8D43 ON meeting (tour_id)');
        $this->addSql('CREATE TABLE meeting_log (id INT NOT NULL, meeting_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1D359C0467433D9C ON meeting_log (meeting_id)');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, lat DOUBLE PRECISION NOT NULL, long DOUBLE PRECISION NOT NULL, pathologies JSON DEFAULT NULL, notes VARCHAR(255) DEFAULT NULL, ameli_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE patient_provider (patient_id INT NOT NULL, provider_id INT NOT NULL, PRIMARY KEY(patient_id, provider_id))');
        $this->addSql('CREATE INDEX IDX_A5412B396B899279 ON patient_provider (patient_id)');
        $this->addSql('CREATE INDEX IDX_A5412B39A53A8AA ON patient_provider (provider_id)');
        $this->addSql('CREATE TABLE provider (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE provider_group (provider_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(provider_id, group_id))');
        $this->addSql('CREATE INDEX IDX_3496F1B1A53A8AA ON provider_group (provider_id)');
        $this->addSql('CREATE INDEX IDX_3496F1B1FE54D947 ON provider_group (group_id)');
        $this->addSql('CREATE TABLE tour (id INT NOT NULL, provider_id INT DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6AD1F969A53A8AA ON tour (provider_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, mobile_phone VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E1396B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E13915ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE meeting_log ADD CONSTRAINT FK_1D359C0467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBBF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient_provider ADD CONSTRAINT FK_A5412B396B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient_provider ADD CONSTRAINT FK_A5412B39A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE provider ADD CONSTRAINT FK_92C4739CBF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE provider_group ADD CONSTRAINT FK_3496F1B1A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE provider_group ADD CONSTRAINT FK_3496F1B1FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "group_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE meeting_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE meeting_log_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tour_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE meeting DROP CONSTRAINT FK_F515E1396B899279');
        $this->addSql('ALTER TABLE meeting DROP CONSTRAINT FK_F515E13915ED8D43');
        $this->addSql('ALTER TABLE meeting_log DROP CONSTRAINT FK_1D359C0467433D9C');
        $this->addSql('ALTER TABLE patient DROP CONSTRAINT FK_1ADAD7EBBF396750');
        $this->addSql('ALTER TABLE patient_provider DROP CONSTRAINT FK_A5412B396B899279');
        $this->addSql('ALTER TABLE patient_provider DROP CONSTRAINT FK_A5412B39A53A8AA');
        $this->addSql('ALTER TABLE provider DROP CONSTRAINT FK_92C4739CBF396750');
        $this->addSql('ALTER TABLE provider_group DROP CONSTRAINT FK_3496F1B1A53A8AA');
        $this->addSql('ALTER TABLE provider_group DROP CONSTRAINT FK_3496F1B1FE54D947');
        $this->addSql('ALTER TABLE tour DROP CONSTRAINT FK_6AD1F969A53A8AA');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE meeting');
        $this->addSql('DROP TABLE meeting_log');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_provider');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE provider_group');
        $this->addSql('DROP TABLE tour');
        $this->addSql('DROP TABLE "user"');
    }
}
