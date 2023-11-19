<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118022124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "
            CREATE TABLE posts (
                id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)',
                title VARCHAR(255) NOT NULL,
                image_file_name VARCHAR(255) DEFAULT NULL,
                rating DOUBLE PRECISION DEFAULT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME DEFAULT NULL,
                required_likes INT NOT NULL,
                current_likes INT NOT NULL,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB
            ;
            "
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "
            DROP TABLE posts
            ;
            "
        );
    }
}
