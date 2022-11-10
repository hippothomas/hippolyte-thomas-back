<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221110154132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE technology_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, name VARCHAR(255) NOT NULL, introduction VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project_technology (project_id INT NOT NULL, technology_id INT NOT NULL, PRIMARY KEY(project_id, technology_id))');
        $this->addSql('CREATE INDEX IDX_ECC5297F166D1F9C ON project_technology (project_id)');
        $this->addSql('CREATE INDEX IDX_ECC5297F4235D463 ON project_technology (technology_id)');
        $this->addSql('CREATE TABLE technology (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6A2CA10C166D1F9C ON media (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10C166D1F9C');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE technology_id_seq CASCADE');
        $this->addSql('ALTER TABLE project_technology DROP CONSTRAINT FK_ECC5297F166D1F9C');
        $this->addSql('ALTER TABLE project_technology DROP CONSTRAINT FK_ECC5297F4235D463');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_technology');
        $this->addSql('DROP TABLE technology');
        $this->addSql('DROP INDEX IDX_6A2CA10C166D1F9C');
        $this->addSql('ALTER TABLE media DROP project_id');
    }
}
