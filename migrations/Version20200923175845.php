<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200923175845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('UPDATE payments SET created_at = CURRENT_TIMESTAMP ');
        $this->addSql('ALTER TABLE payments ALTER created_at SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN payments.created_at IS \'Payment create datetime\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payments DROP created_at');
    }
}
