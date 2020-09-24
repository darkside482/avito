<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200920200137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payments (id UUID NOT NULL, amount DOUBLE PRECISION NOT NULL, purpose VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payments.amount IS \'Amount of payment\'');
        $this->addSql('COMMENT ON COLUMN payments.purpose IS \'Purpose of payment\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payments');
    }
}
