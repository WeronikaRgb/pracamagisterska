<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220824232415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD recipe_id INT NOT NULL, ADD content VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD nick VARCHAR(255) NOT NULL, DROP created_at, DROP text, DROP author_name, DROP author_email');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
        $this->addSql('CREATE INDEX IDX_9474526C59D8A214 ON comment (recipe_id)');
        $this->addSql('ALTER TABLE recipes ADD author_id INT DEFAULT NULL, ADD slug VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B5F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_A369E2B5F675F31B ON recipes (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP slug');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C59D8A214');
        $this->addSql('DROP INDEX IDX_9474526C59D8A214 ON comment');
        $this->addSql('ALTER TABLE comment ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD text VARCHAR(255) NOT NULL, ADD author_name VARCHAR(255) NOT NULL, ADD author_email VARCHAR(255) NOT NULL, DROP recipe_id, DROP content, DROP email, DROP nick');
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B5F675F31B');
        $this->addSql('DROP INDEX IDX_A369E2B5F675F31B ON recipes');
        $this->addSql('ALTER TABLE recipes DROP author_id, DROP slug');
    }
}
