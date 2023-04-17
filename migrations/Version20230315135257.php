<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315135257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A13C2DEBE');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A13C2DEBE FOREIGN KEY (linked_figure_id) REFERENCES figure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA643213C2DEBE');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA643213C2DEBE FOREIGN KEY (linked_figure_id) REFERENCES figure (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A13C2DEBE');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A13C2DEBE FOREIGN KEY (linked_figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA643213C2DEBE');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA643213C2DEBE FOREIGN KEY (linked_figure_id) REFERENCES figure (id)');
    }
}
