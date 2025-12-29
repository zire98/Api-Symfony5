<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229101929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nacionalidad (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona_nacionalidad (persona_id INT NOT NULL, nacionalidad_id INT NOT NULL, INDEX IDX_A1851F93F5F88DB9 (persona_id), INDEX IDX_A1851F93AB8DC0F8 (nacionalidad_id), PRIMARY KEY(persona_id, nacionalidad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persona_nacionalidad ADD CONSTRAINT FK_A1851F93F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona_nacionalidad ADD CONSTRAINT FK_A1851F93AB8DC0F8 FOREIGN KEY (nacionalidad_id) REFERENCES nacionalidad (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE persona_nacionalidad DROP FOREIGN KEY FK_A1851F93F5F88DB9');
        $this->addSql('ALTER TABLE persona_nacionalidad DROP FOREIGN KEY FK_A1851F93AB8DC0F8');
        $this->addSql('DROP TABLE nacionalidad');
        $this->addSql('DROP TABLE persona_nacionalidad');
    }
}
