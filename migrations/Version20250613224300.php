<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250613224300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE favori (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, chambre_id INT NOT NULL, INDEX IDX_EF85A2CCFB88E14F (utilisateur_id), INDEX IDX_EF85A2CC9B177F54 (chambre_id), UNIQUE INDEX UNIQ_EF85A2CCFB88E14F9B177F54 (utilisateur_id, chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CCFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD contenu LONGTEXT NOT NULL, ADD note INT DEFAULT NULL, ADD date_commentaire DATETIME NOT NULL, ADD utilisateur_id INT NOT NULL, ADD chambre_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_67F068BCFB88E14F ON commentaire (utilisateur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_67F068BC9B177F54 ON commentaire (chambre_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD date_debut DATE NOT NULL, ADD date_fin DATE NOT NULL, ADD prix_total DOUBLE PRECISION DEFAULT NULL, ADD utilisateur_id INT NOT NULL, ADD chambre_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C849559B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955FB88E14F ON reservation (utilisateur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C849559B177F54 ON reservation (chambre_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CCFB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC9B177F54
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE favori
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC9B177F54
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_67F068BCFB88E14F ON commentaire
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_67F068BC9B177F54 ON commentaire
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP contenu, DROP note, DROP date_commentaire, DROP utilisateur_id, DROP chambre_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559B177F54
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C84955FB88E14F ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C849559B177F54 ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP date_debut, DROP date_fin, DROP prix_total, DROP utilisateur_id, DROP chambre_id
        SQL);
    }
}
