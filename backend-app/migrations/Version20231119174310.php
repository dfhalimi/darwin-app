<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119174310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "
            CREATE TABLE users (
                id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)',
                username VARCHAR(180) NOT NULL,
                username_canonical VARCHAR(180) NOT NULL,
                email VARCHAR(180) NOT NULL,
                email_canonical VARCHAR(180) NOT NULL,
                enabled TINYINT(1) NOT NULL,
                salt VARCHAR(255) DEFAULT NULL,
                password VARCHAR(255) NOT NULL,
                last_login DATETIME DEFAULT NULL,
                confirmation_token VARCHAR(180) DEFAULT NULL,
                password_requested_at DATETIME DEFAULT NULL,
                roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)',
                created_at DATETIME NOT NULL,
                UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical),
                UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical),
                UNIQUE INDEX UNIQ_1483A5E9C05FB297 (confirmation_token),
                INDEX created_at_idx (created_at),
                INDEX email_idx (email),
                INDEX username_idx (username),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB;
            "
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE users;");
    }
}
