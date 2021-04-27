<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420012450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, workshop_id INT NOT NULL, author_name VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_9474526C1FDCE57C (workshop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profanities (id INT AUTO_INCREMENT NOT NULL, word VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B8715B4C3F17511 (word), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_for');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC0759D98 FOREIGN KEY (id_formation) REFERENCES formation (Id)');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY fk_cons');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575C0759D98 FOREIGN KEY (id_formation) REFERENCES formation (Id)');
        $this->addSql('ALTER TABLE formateurs DROP FOREIGN KEY fk_idf');
        $this->addSql('ALTER TABLE formateurs CHANGE etat etat TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE formateurs ADD CONSTRAINT FK_FD80E574BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation ADD image_name VARCHAR(255) NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE panier CHANGE prix_total prix_total DOUBLE PRECISION NOT NULL, CHANGE nombre nombre INT NOT NULL');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY fk_id');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_71697092BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_reclamation_message');
        $this->addSql('ALTER TABLE reclamation CHANGE id_message id_message INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046820990F FOREIGN KEY (id_message) REFERENCES message (id_message)');
        $this->addSql('ALTER TABLE utilisateurs ADD image VARCHAR(8) DEFAULT NULL, ADD idp VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE workshop ADD hearts INT DEFAULT NULL, CHANGE hDebut hDebut TIME DEFAULT NULL, CHANGE hFin hFin TIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE profanities');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC0759D98');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_for FOREIGN KEY (id_formation) REFERENCES formation (Id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575C0759D98');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT fk_cons FOREIGN KEY (id_formation) REFERENCES formation (Id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateurs DROP FOREIGN KEY FK_FD80E574BF396750');
        $this->addSql('ALTER TABLE formateurs CHANGE etat etat TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE formateurs ADD CONSTRAINT fk_idf FOREIGN KEY (id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE formation DROP image_name, DROP updated_at');
        $this->addSql('ALTER TABLE panier CHANGE prix_total prix_total DOUBLE PRECISION DEFAULT \'0\' NOT NULL, CHANGE nombre nombre INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_71697092BF396750');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT fk_id FOREIGN KEY (id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE promotion CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046820990F');
        $this->addSql('ALTER TABLE reclamation CHANGE id_message id_message INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_reclamation_message FOREIGN KEY (id_message) REFERENCES message (id_message) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs DROP image, DROP idp');
        $this->addSql('ALTER TABLE workshop DROP hearts, CHANGE hDebut hDebut TIME NOT NULL, CHANGE hFin hFin TIME NOT NULL');
    }
}
