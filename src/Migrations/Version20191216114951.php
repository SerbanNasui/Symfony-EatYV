<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216114951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE food_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (recipe_id INT AUTO_INCREMENT NOT NULL, user_author_id INT DEFAULT NULL, food_category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(2000) NOT NULL, image VARCHAR(255) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, price NUMERIC(10, 0) NOT NULL, max_nr_persons INT NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_DA88B137F6957EFF (user_author_id), INDEX IDX_DA88B137B3F04B2C (food_category_id), PRIMARY KEY(recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_review (recipe_review_id INT AUTO_INCREMENT NOT NULL, user_review_recipe_id INT DEFAULT NULL, recipe_review_recipe_id INT DEFAULT NULL, comment VARCHAR(2000) NOT NULL, grade INT NOT NULL, INDEX IDX_F45D9FE7B33C7BC8 (user_review_recipe_id), INDEX IDX_F45D9FE78A446033 (recipe_review_recipe_id), PRIMARY KEY(recipe_review_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (reservation_id INT AUTO_INCREMENT NOT NULL, user_reservation_id INT DEFAULT NULL, recipe_reservation_id INT DEFAULT NULL, reservation_for_first_name VARCHAR(50) NOT NULL, reservation_for_second_name VARCHAR(50) NOT NULL, message VARCHAR(2000) NOT NULL, persons_participate INT NOT NULL, date_time_coming DATETIME NOT NULL, INDEX IDX_42C84955D3B748BE (user_reservation_id), INDEX IDX_42C84955CAA50574 (recipe_reservation_id), PRIMARY KEY(reservation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(50) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (user_profile_id INT NOT NULL, first_name VARCHAR(50) NOT NULL, second_name VARCHAR(50) NOT NULL, biography VARCHAR(2000) NOT NULL, profile_image VARCHAR(255) NOT NULL, PRIMARY KEY(user_profile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137F6957EFF FOREIGN KEY (user_author_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137B3F04B2C FOREIGN KEY (food_category_id) REFERENCES food_category (id)');
        $this->addSql('ALTER TABLE recipe_review ADD CONSTRAINT FK_F45D9FE7B33C7BC8 FOREIGN KEY (user_review_recipe_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE recipe_review ADD CONSTRAINT FK_F45D9FE78A446033 FOREIGN KEY (recipe_review_recipe_id) REFERENCES recipe (recipe_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D3B748BE FOREIGN KEY (user_reservation_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CAA50574 FOREIGN KEY (recipe_reservation_id) REFERENCES recipe (recipe_id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4056B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137B3F04B2C');
        $this->addSql('ALTER TABLE recipe_review DROP FOREIGN KEY FK_F45D9FE78A446033');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955CAA50574');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137F6957EFF');
        $this->addSql('ALTER TABLE recipe_review DROP FOREIGN KEY FK_F45D9FE7B33C7BC8');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D3B748BE');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4056B9DD454');
        $this->addSql('DROP TABLE food_category');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_review');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_profile');
    }
}
