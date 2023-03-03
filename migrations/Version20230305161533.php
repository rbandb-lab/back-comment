<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305161533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id UUID NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN author.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE comment (id UUID NOT NULL, parent_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, post_id VARCHAR(255) NOT NULL, comment_content VARCHAR(255) NOT NULL, created_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C727ACA70 ON comment (parent_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('COMMENT ON COLUMN comment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.parent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE comments_ratings (comment_id UUID NOT NULL, rating_id UUID NOT NULL, PRIMARY KEY(comment_id, rating_id))');
        $this->addSql('CREATE INDEX IDX_C37FF405F8697D13 ON comments_ratings (comment_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C37FF405A32EFC6 ON comments_ratings (rating_id)');
        $this->addSql('COMMENT ON COLUMN comments_ratings.comment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comments_ratings.rating_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE rating (id UUID NOT NULL, user_id UUID DEFAULT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8892622A76ED395 ON rating (user_id)');
        $this->addSql('COMMENT ON COLUMN rating.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rating.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments_ratings ADD CONSTRAINT FK_C37FF405F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments_ratings ADD CONSTRAINT FK_C37FF405A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C727ACA70');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comments_ratings DROP CONSTRAINT FK_C37FF405F8697D13');
        $this->addSql('ALTER TABLE comments_ratings DROP CONSTRAINT FK_C37FF405A32EFC6');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D8892622A76ED395');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comments_ratings');
        $this->addSql('DROP TABLE rating');
    }
}
