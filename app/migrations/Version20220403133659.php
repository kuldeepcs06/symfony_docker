<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403133659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_item (id INT AUTO_INCREMENT NOT NULL, author VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publish_date DATE NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, category_name VARCHAR(255) NOT NULL, sku VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, shortdesc LONGTEXT NOT NULL, price VARCHAR(255) DEFAULT NULL, link VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, rating VARCHAR(255) DEFAULT NULL, caffine_type VARCHAR(255) DEFAULT NULL, count VARCHAR(255) DEFAULT NULL, flavored VARCHAR(255) DEFAULT NULL, seasonal VARCHAR(255) DEFAULT NULL, in_stock VARCHAR(255) NOT NULL, facebook INT NOT NULL, isk_cup VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, quote LONGTEXT NOT NULL, historian VARCHAR(25) NOT NULL, year VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE book_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE quote');
    }
}
