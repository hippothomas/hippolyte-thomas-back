<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224224542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE api_key_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE api_key (id INT NOT NULL, account_id INT NOT NULL, key VARCHAR(255) NOT NULL, label VARCHAR(255) DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expiration_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_accessed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C912ED9D8A90ABA9 ON api_key (key)');
        $this->addSql('CREATE INDEX IDX_C912ED9D9B6B5FBA ON api_key (account_id)');
        $this->addSql('ALTER TABLE api_key ADD CONSTRAINT FK_C912ED9D9B6B5FBA FOREIGN KEY (account_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE api_key_id_seq CASCADE');
        $this->addSql('ALTER TABLE api_key DROP CONSTRAINT FK_C912ED9D9B6B5FBA');
        $this->addSql('DROP TABLE api_key');
    }
}
