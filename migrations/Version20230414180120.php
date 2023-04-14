<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414180120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, INDEX IDX_7BA2F5EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_favorite (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_favorite_user (user_favorite_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8D1B622630C8188 (user_favorite_id), INDEX IDX_8D1B6226A76ED395 (user_id), PRIMARY KEY(user_favorite_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_favorite_image (user_favorite_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_1830F02230C8188 (user_favorite_id), INDEX IDX_1830F0223DA5256D (image_id), PRIMARY KEY(user_favorite_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_favorite_user ADD CONSTRAINT FK_8D1B622630C8188 FOREIGN KEY (user_favorite_id) REFERENCES user_favorite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_user ADD CONSTRAINT FK_8D1B6226A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_image ADD CONSTRAINT FK_1830F02230C8188 FOREIGN KEY (user_favorite_id) REFERENCES user_favorite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_image ADD CONSTRAINT FK_1830F0223DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token DROP FOREIGN KEY FK_7BA2F5EBA76ED395');
        $this->addSql('ALTER TABLE user_favorite_user DROP FOREIGN KEY FK_8D1B622630C8188');
        $this->addSql('ALTER TABLE user_favorite_user DROP FOREIGN KEY FK_8D1B6226A76ED395');
        $this->addSql('ALTER TABLE user_favorite_image DROP FOREIGN KEY FK_1830F02230C8188');
        $this->addSql('ALTER TABLE user_favorite_image DROP FOREIGN KEY FK_1830F0223DA5256D');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_favorite');
        $this->addSql('DROP TABLE user_favorite_user');
        $this->addSql('DROP TABLE user_favorite_image');
    }
}
