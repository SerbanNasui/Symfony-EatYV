<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191211160333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_review (recipe_review_id INT AUTO_INCREMENT NOT NULL, user_review_recipe_id INT DEFAULT NULL, recipe_review_recipe_id INT DEFAULT NULL, comment VARCHAR(2000) NOT NULL, grade INT NOT NULL, INDEX IDX_F45D9FE7B33C7BC8 (user_review_recipe_id), INDEX IDX_F45D9FE78A446033 (recipe_review_recipe_id), PRIMARY KEY(recipe_review_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_review ADD CONSTRAINT FK_F45D9FE7B33C7BC8 FOREIGN KEY (user_review_recipe_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE recipe_review ADD CONSTRAINT FK_F45D9FE78A446033 FOREIGN KEY (recipe_review_recipe_id) REFERENCES recipe (recipe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE recipe_review');
    }
}
