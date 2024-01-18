<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118215441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe ADD COLUMN description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD COLUMN price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD COLUMN difficulty VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, name, time, servings, instructions, category FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, time INTEGER DEFAULT NULL, servings INTEGER DEFAULT NULL, instructions VARCHAR(500) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO recipe (id, name, time, servings, instructions, category) SELECT id, name, time, servings, instructions, category FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }
}
