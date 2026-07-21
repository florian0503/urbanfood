<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260721080346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, tag VARCHAR(100) NOT NULL, position INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE menu_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, featured_description VARCHAR(255) DEFAULT NULL, price VARCHAR(20) NOT NULL, tag VARCHAR(50) DEFAULT NULL, position INTEGER NOT NULL, featured BOOLEAN NOT NULL, featured_position INTEGER DEFAULT NULL, category_id INTEGER NOT NULL, CONSTRAINT FK_D754D55012469DE2 FOREIGN KEY (category_id) REFERENCES menu_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D754D55012469DE2 ON menu_item (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE menu_category');
        $this->addSql('DROP TABLE menu_item');
    }
}
