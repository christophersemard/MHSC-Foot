<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908121352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_product_size (product_id INT NOT NULL, product_size_id INT NOT NULL, INDEX IDX_F78C24A04584665A (product_id), INDEX IDX_F78C24A09854B397 (product_size_id), PRIMARY KEY(product_id, product_size_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_size (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_product_size ADD CONSTRAINT FK_F78C24A04584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_product_size ADD CONSTRAINT FK_F78C24A09854B397 FOREIGN KEY (product_size_id) REFERENCES product_size (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_product_size DROP FOREIGN KEY FK_F78C24A04584665A');
        $this->addSql('ALTER TABLE product_product_size DROP FOREIGN KEY FK_F78C24A09854B397');
        $this->addSql('DROP TABLE product_product_size');
        $this->addSql('DROP TABLE product_size');
    }
}
