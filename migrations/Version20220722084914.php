<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722084914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_post CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post DROP INDEX UNIQ_5A8A6C8D12469DE2, ADD INDEX IDX_5A8A6C8D12469DE2 (category_id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_post CHANGE post_id post_id INT NOT NULL');
        $this->addSql('ALTER TABLE post DROP INDEX IDX_5A8A6C8D12469DE2, ADD UNIQUE INDEX UNIQ_5A8A6C8D12469DE2 (category_id)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D12469DE2');
    }
}
