<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210421150800 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, country_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locale (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, shipment_id INT NOT NULL, shipping_address_id INT NOT NULL, billing_address_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, locale_code VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, email VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, items_total INT NOT NULL, state INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, token VARCHAR(255) NOT NULL, payment_intent VARCHAR(255) DEFAULT NULL, total_with_shipment DOUBLE PRECISION NOT NULL, INDEX IDX_F52993987BE036FC (shipment_id), INDEX IDX_F52993984D4CFF2B (shipping_address_id), INDEX IDX_F529939879D0C0E4 (billing_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_number (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payement_method (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payement_method_translation (id INT AUTO_INCREMENT NOT NULL, payement_method_id INT NOT NULL, locale_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_35DFC52A396979B3 (payement_method_id), INDEX IDX_35DFC52AE559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, product_brand_id INT DEFAULT NULL, product_type_id INT NOT NULL, code VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, enabled TINYINT(1) NOT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, promotion INT DEFAULT NULL, INDEX IDX_D34A04AD8F45BA9F (product_brand_id), INDEX IDX_D34A04AD14959723 (product_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_brand (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, link LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_brand_image (id INT AUTO_INCREMENT NOT NULL, product_brand_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_32EB53578F45BA9F (product_brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_brand_translation (id INT AUTO_INCREMENT NOT NULL, product_brand_id INT NOT NULL, locale_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_61BD57C98F45BA9F (product_brand_id), INDEX IDX_61BD57C9E559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category_image (id INT AUTO_INCREMENT NOT NULL, product_category_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_7E67353ABE6903FD (product_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category_translation (id INT AUTO_INCREMENT NOT NULL, product_category_id INT NOT NULL, locale_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1DAAB487BE6903FD (product_category_id), INDEX IDX_1DAAB487E559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_sold (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, order_list_id INT NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_153AFF384584665A (product_id), INDEX IDX_153AFF38CBD4BFC0 (order_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_translation (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, locale_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1846DB704584665A (product_id), INDEX IDX_1846DB70E559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, product_category_id INT NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1367588BE6903FD (product_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type_image (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5856DC8314959723 (product_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type_translation (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, locale_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_71FCF60214959723 (product_type_id), INDEX IDX_71FCF602E559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_translation (id INT AUTO_INCREMENT NOT NULL, shipment_id INT NOT NULL, locale_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_9B57DDFB7BE036FC (shipment_id), INDEX IDX_9B57DDFBE559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993987BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939879D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE payement_method_translation ADD CONSTRAINT FK_35DFC52A396979B3 FOREIGN KEY (payement_method_id) REFERENCES payement_method (id)');
        $this->addSql('ALTER TABLE payement_method_translation ADD CONSTRAINT FK_35DFC52AE559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD8F45BA9F FOREIGN KEY (product_brand_id) REFERENCES product_brand (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_brand_image ADD CONSTRAINT FK_32EB53578F45BA9F FOREIGN KEY (product_brand_id) REFERENCES product_brand (id)');
        $this->addSql('ALTER TABLE product_brand_translation ADD CONSTRAINT FK_61BD57C98F45BA9F FOREIGN KEY (product_brand_id) REFERENCES product_brand (id)');
        $this->addSql('ALTER TABLE product_brand_translation ADD CONSTRAINT FK_61BD57C9E559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE product_category_image ADD CONSTRAINT FK_7E67353ABE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_category_translation ADD CONSTRAINT FK_1DAAB487BE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_category_translation ADD CONSTRAINT FK_1DAAB487E559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_sold ADD CONSTRAINT FK_153AFF384584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_sold ADD CONSTRAINT FK_153AFF38CBD4BFC0 FOREIGN KEY (order_list_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product_translation ADD CONSTRAINT FK_1846DB704584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_translation ADD CONSTRAINT FK_1846DB70E559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE product_type ADD CONSTRAINT FK_1367588BE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_type_image ADD CONSTRAINT FK_5856DC8314959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_type_translation ADD CONSTRAINT FK_71FCF60214959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product_type_translation ADD CONSTRAINT FK_71FCF602E559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
        $this->addSql('ALTER TABLE shipment_translation ADD CONSTRAINT FK_9B57DDFB7BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_translation ADD CONSTRAINT FK_9B57DDFBE559DFD1 FOREIGN KEY (locale_id) REFERENCES locale (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984D4CFF2B');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939879D0C0E4');
        $this->addSql('ALTER TABLE payement_method_translation DROP FOREIGN KEY FK_35DFC52AE559DFD1');
        $this->addSql('ALTER TABLE product_brand_translation DROP FOREIGN KEY FK_61BD57C9E559DFD1');
        $this->addSql('ALTER TABLE product_category_translation DROP FOREIGN KEY FK_1DAAB487E559DFD1');
        $this->addSql('ALTER TABLE product_translation DROP FOREIGN KEY FK_1846DB70E559DFD1');
        $this->addSql('ALTER TABLE product_type_translation DROP FOREIGN KEY FK_71FCF602E559DFD1');
        $this->addSql('ALTER TABLE shipment_translation DROP FOREIGN KEY FK_9B57DDFBE559DFD1');
        $this->addSql('ALTER TABLE product_sold DROP FOREIGN KEY FK_153AFF38CBD4BFC0');
        $this->addSql('ALTER TABLE payement_method_translation DROP FOREIGN KEY FK_35DFC52A396979B3');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_sold DROP FOREIGN KEY FK_153AFF384584665A');
        $this->addSql('ALTER TABLE product_translation DROP FOREIGN KEY FK_1846DB704584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD8F45BA9F');
        $this->addSql('ALTER TABLE product_brand_image DROP FOREIGN KEY FK_32EB53578F45BA9F');
        $this->addSql('ALTER TABLE product_brand_translation DROP FOREIGN KEY FK_61BD57C98F45BA9F');
        $this->addSql('ALTER TABLE product_category_image DROP FOREIGN KEY FK_7E67353ABE6903FD');
        $this->addSql('ALTER TABLE product_category_translation DROP FOREIGN KEY FK_1DAAB487BE6903FD');
        $this->addSql('ALTER TABLE product_type DROP FOREIGN KEY FK_1367588BE6903FD');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD14959723');
        $this->addSql('ALTER TABLE product_type_image DROP FOREIGN KEY FK_5856DC8314959723');
        $this->addSql('ALTER TABLE product_type_translation DROP FOREIGN KEY FK_71FCF60214959723');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993987BE036FC');
        $this->addSql('ALTER TABLE shipment_translation DROP FOREIGN KEY FK_9B57DDFB7BE036FC');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE locale');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_number');
        $this->addSql('DROP TABLE payement_method');
        $this->addSql('DROP TABLE payement_method_translation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_brand');
        $this->addSql('DROP TABLE product_brand_image');
        $this->addSql('DROP TABLE product_brand_translation');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_category_image');
        $this->addSql('DROP TABLE product_category_translation');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE product_sold');
        $this->addSql('DROP TABLE product_translation');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP TABLE product_type_image');
        $this->addSql('DROP TABLE product_type_translation');
        $this->addSql('DROP TABLE shipment');
        $this->addSql('DROP TABLE shipment_translation');
    }
}
