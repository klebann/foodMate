<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623224702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fridge ADD CONSTRAINT FK_F2E94D89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fridge_product ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE fridge_product ADD CONSTRAINT FK_CD1526EF14A48E59 FOREIGN KEY (fridge_id) REFERENCES fridge (id)');
        $this->addSql('ALTER TABLE fridge_product ADD CONSTRAINT FK_CD1526EF4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_CD1526EF4584665A ON fridge_product (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fridge DROP FOREIGN KEY FK_F2E94D89A76ED395');
        $this->addSql('ALTER TABLE fridge_product DROP FOREIGN KEY FK_CD1526EF14A48E59');
        $this->addSql('ALTER TABLE fridge_product DROP FOREIGN KEY FK_CD1526EF4584665A');
        $this->addSql('DROP INDEX IDX_CD1526EF4584665A ON fridge_product');
        $this->addSql('ALTER TABLE fridge_product DROP product_id');
    }
}
