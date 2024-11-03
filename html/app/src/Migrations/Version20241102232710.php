<?php

declare(strict_types=1);

namespace app\src\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102232710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
        CREATE TABLE IF NOT EXISTS `sessions` (
  `uid` varchar(255) PRIMARY KEY NOT NULL,
  `user_uid` varchar(255) NOT NULL COMMENT 'should be user uid here',
  `token` text NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `user_platform` varchar(255) NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `is_blocked` boolean NOT NULL DEFAULT false,
  `is_blocked_updated_at` varchar(255) NOT NULL DEFAULT '0000-00-00T00:00:00.000000+00:00' COMMENT 'example: 2024-11-01T11:02:54.210540+00:00',
  `is_blocked_updated_by` varchar(255) NOT NULL DEFAULT 'system' COMMENT 'should be user uid here',
  `expires_at` varchar(255) NOT NULL COMMENT 'example: 2024-11-01T11:02:54.210540+00:00',
  `created_at` varchar(255) NOT NULL COMMENT 'example: 2024-11-01T11:02:54.210540+00:00'
);
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE IF EXISTS sessions");
    }
}
