<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210417001447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite CHANGE idP idP INT NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_for');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC0759D98 FOREIGN KEY (id_formation) REFERENCES formation (Id)');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY fk_cons');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575C0759D98 FOREIGN KEY (id_formation) REFERENCES formation (Id)');
        $this->addSql('ALTER TABLE formateurs DROP FOREIGN KEY fk_idf');
        $this->addSql('ALTER TABLE formateurs CHANGE etat etat TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE formateurs ADD CONSTRAINT FK_FD80E574BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier CHANGE prix_total prix_total DOUBLE PRECISION NOT NULL, CHANGE nombre nombre INT NOT NULL');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY fk_id');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_71697092BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_reclamation_message');
        $this->addSql('ALTER TABLE reclamation CHANGE id_message id_message INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046820990F FOREIGN KEY (id_message) REFERENCES message (id_message)');
        $this->addSql('ALTER TABLE utilisateurs ADD image VARCHAR(8) DEFAULT NULL, ADD idp VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE activite CHANGE idP idP INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC0759D98');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_for FOREIGN KEY (id_formation) REFERENCES formation (Id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575C0759D98');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT fk_cons FOREIGN KEY (id_formation) REFERENCES formation (Id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateurs DROP FOREIGN KEY FK_FD80E574BF396750');
        $this->addSql('ALTER TABLE formateurs CHANGE etat etat TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE formateurs ADD CONSTRAINT fk_idf FOREIGN KEY (id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE panier CHANGE prix_total prix_total DOUBLE PRECISION DEFAULT \'0\' NOT NULL, CHANGE nombre nombre INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_71697092BF396750');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT fk_id FOREIGN KEY (id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046820990F');
        $this->addSql('ALTER TABLE reclamation CHANGE id_message id_message INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_reclamation_message FOREIGN KEY (id_message) REFERENCES message (id_message) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs DROP image, DROP idp');
    }
}
