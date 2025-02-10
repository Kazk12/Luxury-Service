<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210130934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD compagy_name VARCHAR(255) DEFAULT NULL, ADD activity_type VARCHAR(255) DEFAULT NULL, ADD contact_name VARCHAR(255) DEFAULT NULL, ADD workstation VARCHAR(255) DEFAULT NULL, ADD contact_email VARCHAR(255) DEFAULT NULL, ADD contact_number VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP compagy_name, DROP activity_type, DROP contact_name, DROP workstation, DROP contact_email, DROP contact_number');
    }
}
