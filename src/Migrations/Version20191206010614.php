<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191206010614 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reservation (reservation_id INT AUTO_INCREMENT NOT NULL, user_reservation_id INT DEFAULT NULL, recipe_reservation_id INT DEFAULT NULL, reservation_for_first_name VARCHAR(50) NOT NULL, reservation_for_second_name VARCHAR(50) NOT NULL, message VARCHAR(2000) NOT NULL, INDEX IDX_42C84955D3B748BE (user_reservation_id), INDEX IDX_42C84955CAA50574 (recipe_reservation_id), PRIMARY KEY(reservation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D3B748BE FOREIGN KEY (user_reservation_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CAA50574 FOREIGN KEY (recipe_reservation_id) REFERENCES recipe (recipe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reservation');
    }
}
