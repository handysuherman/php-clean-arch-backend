<?php

declare(strict_types=1);

namespace app\src\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102232711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
        CREATE INDEX `role_index_0` ON `role` (`role_name`);

CREATE INDEX `user_roles_index_1` ON `user_roles` (`user_uid`);

CREATE INDEX `user_roles_index_2` ON `user_roles` (`role_uid`);

ALTER TABLE `user_roles` ADD CONSTRAINT `ct_user_role` UNIQUE (`user_uid`, `role_uid`);

CREATE UNIQUE INDEX `user_index_4` ON `user` (`email`);

CREATE UNIQUE INDEX `user_credentials_index_5` ON `user_credentials` (`user_uid`);

CREATE INDEX `sessions_index_6` ON `sessions` (`user_uid`);

ALTER TABLE `user_roles` 
ADD CONSTRAINT `fk_user_uid_user_roles` FOREIGN KEY (`user_uid`) REFERENCES `user`(`uid`) ON DELETE CASCADE;

ALTER TABLE `user_roles` 
ADD CONSTRAINT `fk_role_uid_user_roles` FOREIGN KEY (`role_uid`) REFERENCES `role`(`uid`) ON DELETE CASCADE;

ALTER TABLE `user_credentials` 
ADD CONSTRAINT `fk_user_uid_user_credentials` FOREIGN KEY (`user_uid`) REFERENCES `user`(`uid`) ON DELETE CASCADE;

ALTER TABLE `sessions` 
ADD CONSTRAINT `fk_user_uid_sessions` FOREIGN KEY (`user_uid`) REFERENCES `user`(`uid`) ON DELETE CASCADE;
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE user_roles DROP FOREIGN KEY fk_user_uid_user_roles;
            ALTER TABLE user_roles DROP FOREIGN KEY fk_role_uid_user_roles;
            ALTER TABLE user_roles DROP FOREIGN KEY fk_user_uid_user_credentials;
            ALTER TABLE user_roles DROP FOREIGN KEY fk_user_uid_sessions;
        ");
    }
}
