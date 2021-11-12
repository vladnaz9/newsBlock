<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211109100830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, category_id INT DEFAULT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DD3995012469DE2 ON news (category_id)');
        $this->addSql('CREATE INDEX IDX_1DD39950F675F31B ON news (author_id)');
        $this->addSql('COMMENT ON COLUMN news.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(60) NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD3995012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD3995012469DE2');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950F675F31B');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE "user"');
    }
}
