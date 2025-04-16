<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416020317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, uploaded_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', printed_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_D8698A769395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD CONSTRAINT FK_D8698A769395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP FOREIGN KEY FK_D8698A769395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document
        SQL);
    }
}
