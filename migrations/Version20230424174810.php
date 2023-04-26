<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230424174810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD product_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCDE18E50B FOREIGN KEY (product_id_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCDE18E50B ON commentaire (product_id_id)');
        $this->addSql('ALTER TABLE produit ADD date_time DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCDE18E50B');
        $this->addSql('DROP INDEX IDX_67F068BCDE18E50B ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP product_id_id');
        $this->addSql('ALTER TABLE produit DROP date_time');
    }
}
