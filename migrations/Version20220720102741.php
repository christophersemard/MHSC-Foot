<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220720102741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD name VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE post DROP INDEX IDX_5A8A6C8D12469DE2, ADD UNIQUE INDEX UNIQ_5A8A6C8D12469DE2 (category_id)');
        $this->addSql('ALTER TABLE post ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8DF5B7AF75 ON post (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP name');
        $this->addSql('ALTER TABLE post DROP INDEX UNIQ_5A8A6C8D12469DE2, ADD INDEX IDX_5A8A6C8D12469DE2 (category_id)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF5B7AF75');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8DF5B7AF75 ON post');
        $this->addSql('ALTER TABLE post DROP address_id');
    }
}
