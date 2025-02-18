<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218141323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE concert_band (concert_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_ED62962C83C97B2E (concert_id), INDEX IDX_ED62962C49ABEB17 (band_id), PRIMARY KEY(concert_id, band_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, capacite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE concert_band ADD CONSTRAINT FK_ED62962C83C97B2E FOREIGN KEY (concert_id) REFERENCES concert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE concert_band ADD CONSTRAINT FK_ED62962C49ABEB17 FOREIGN KEY (band_id) REFERENCES band (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE concert ADD salle_id INT NOT NULL');
        $this->addSql('ALTER TABLE concert ADD CONSTRAINT FK_D57C02D2DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('CREATE INDEX IDX_D57C02D2DC304035 ON concert (salle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE concert DROP FOREIGN KEY FK_D57C02D2DC304035');
        $this->addSql('ALTER TABLE concert_band DROP FOREIGN KEY FK_ED62962C83C97B2E');
        $this->addSql('ALTER TABLE concert_band DROP FOREIGN KEY FK_ED62962C49ABEB17');
        $this->addSql('DROP TABLE concert_band');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP INDEX IDX_D57C02D2DC304035 ON concert');
        $this->addSql('ALTER TABLE concert DROP salle_id');
    }
}
