<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224002715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE about_me ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE about_me ALTER updated DROP DEFAULT');
        $this->addSql('ALTER TABLE media ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE project ADD published TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE project ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE project ALTER updated DROP DEFAULT');
        $this->addSql('ALTER TABLE social ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE social ALTER updated DROP DEFAULT');
        $this->addSql('ALTER TABLE technology ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE technology ALTER updated DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER created DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER updated DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER created SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE "user" ALTER updated SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE project DROP published');
        $this->addSql('ALTER TABLE project ALTER created SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE project ALTER updated SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE about_me ALTER created SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE about_me ALTER updated SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE technology ALTER created SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE technology ALTER updated SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE social ALTER created SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE social ALTER updated SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE media ALTER created SET DEFAULT \'now()\'');
    }
}
