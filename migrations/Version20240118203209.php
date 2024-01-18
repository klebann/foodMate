<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118203209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ingredients AS SELECT id, product_id, quantity, unit FROM ingredients');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('CREATE TABLE ingredients (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, recipe_id INTEGER NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, CONSTRAINT FK_4B60114F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4B60114F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ingredients (id, product_id, quantity, unit) SELECT id, product_id, quantity, unit FROM __temp__ingredients');
        $this->addSql('DROP TABLE __temp__ingredients');
        $this->addSql('CREATE INDEX IDX_4B60114F4584665A ON ingredients (product_id)');
        $this->addSql('CREATE INDEX IDX_4B60114F59D8A214 ON ingredients (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ingredients AS SELECT id, product_id, quantity, unit FROM ingredients');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('CREATE TABLE ingredients (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, CONSTRAINT FK_4B60114F4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ingredients (id, product_id, quantity, unit) SELECT id, product_id, quantity, unit FROM __temp__ingredients');
        $this->addSql('DROP TABLE __temp__ingredients');
        $this->addSql('CREATE INDEX IDX_4B60114F4584665A ON ingredients (product_id)');
    }
}
