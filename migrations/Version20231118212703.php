<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118212703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "
            ALTER TABLE posts
                ADD created_by VARCHAR(255) NOT NULL
            ;
            "
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "
            ALTER TABLE posts
                DROP COLUMN created_by
            ;
            "
        );
    }
}
