<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507203945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, _user_id INT NOT NULL, neighborhood_id INT NOT NULL, address_name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_D4E6F81D32632E8 (_user_id), INDEX IDX_D4E6F81803BB24B (neighborhood_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE neighborhood (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sub_district (id INT AUTO_INCREMENT NOT NULL, neighborhood_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8B6EBADE803BB24B (neighborhood_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F81D32632E8 FOREIGN KEY (_user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F81803BB24B FOREIGN KEY (neighborhood_id) REFERENCES neighborhood (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sub_district ADD CONSTRAINT FK_8B6EBADE803BB24B FOREIGN KEY (neighborhood_id) REFERENCES neighborhood (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE profil_id profil_id INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81D32632E8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81803BB24B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sub_district DROP FOREIGN KEY FK_8B6EBADE803BB24B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE neighborhood
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sub_district
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE profil_id profil_id INT DEFAULT NULL
        SQL);
    }
}
