<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412180653 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_71697092BF396750');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_71697092BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE utilisateurs CHANGE image image VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE workshop CHANGE hDebut hDebut TIME DEFAULT NULL, CHANGE hFin hFin TIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_71697092BF396750');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_71697092BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs CHANGE image image VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`');
        $this->addSql('ALTER TABLE workshop CHANGE hDebut hDebut TIME NOT NULL, CHANGE hFin hFin TIME NOT NULL');
    }
}
