<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602123358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F0FE25271AD5CDBF (cart_id), INDEX IDX_F0FE25274584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, image_size INT NOT NULL, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, product_id INT DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_EEB02E75F624B39D (sender_id), INDEX IDX_EEB02E75CD53EDB6 (receiver_id), INDEX IDX_EEB02E754584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_message ADD CONSTRAINT FK_EEB02E75F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_message ADD CONSTRAINT FK_EEB02E75CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_message ADD CONSTRAINT FK_EEB02E754584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_message DROP FOREIGN KEY FK_EEB02E75F624B39D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_message DROP FOREIGN KEY FK_EEB02E75CD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_message DROP FOREIGN KEY FK_EEB02E754584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact_message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
