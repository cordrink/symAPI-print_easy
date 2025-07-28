<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250728210130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE customer ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_81398E09A76ED395 ON customer (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649275ED078 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP profil_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD profil_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649275ED078 ON user (profil_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer DROP FOREIGN KEY FK_81398E09A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_81398E09A76ED395 ON customer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer DROP user_id
        SQL);
    }
}
