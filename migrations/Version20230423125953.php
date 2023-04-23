<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423125953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure CHANGE title title VARCHAR(100) NOT NULL, CHANGE slug slug VARCHAR(190) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37A2B36786B ON figure (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37A989D9B62 ON figure (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2F57B37A2B36786B ON figure');
        $this->addSql('DROP INDEX UNIQ_2F57B37A989D9B62 ON figure');
        $this->addSql('ALTER TABLE figure CHANGE title title VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
    }
}
