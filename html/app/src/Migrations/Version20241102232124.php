<?php

declare(strict_types=1);

namespace app\src\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102232124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("        
        CREATE TABLE IF NOT EXISTS `role` (
  `uid` varchar(255) PRIMARY KEY NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `role_name_slug` text NOT NULL COMMENT 'this should be created and updated, respectively when role_name changes',
  `created_at` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `is_activated` boolean NOT NULL DEFAULT true,
  `is_activated_updated_at` varchar(255) NOT NULL DEFAULT '0000-00-00T00:00:00.000000+00:00' COMMENT 'example: 2024-11-01T11:02:54.210540+00:00',
  `is_activated_updated_by` varchar(255) NOT NULL DEFAULT 'system' COMMENT 'should be user uid here'
);
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE IF EXISTS role");
    }
}
