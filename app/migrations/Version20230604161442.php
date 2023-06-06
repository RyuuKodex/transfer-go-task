<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230604161442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create notification';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE notification (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            sender BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            receiver BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
            title VARCHAR(255) NOT NULL,
            message LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            status VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )
        DEFAULT CHARACTER SET utf8mb4
        COLLATE `utf8mb4_unicode_ci`
        ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notification');
    }
}
