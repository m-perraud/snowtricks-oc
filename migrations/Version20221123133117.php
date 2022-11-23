<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123133117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, linked_figure_id INT NOT NULL, image_url VARCHAR(255) NOT NULL, main_image TINYINT(1) NOT NULL, INDEX IDX_E01FBE6A13C2DEBE (linked_figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videos (id INT AUTO_INCREMENT NOT NULL, linked_figure_id INT NOT NULL, video_url VARCHAR(255) NOT NULL, INDEX IDX_29AA643213C2DEBE (linked_figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A13C2DEBE FOREIGN KEY (linked_figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA643213C2DEBE FOREIGN KEY (linked_figure_id) REFERENCES figure (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A13C2DEBE');
        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA643213C2DEBE');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE videos');
    }
}
