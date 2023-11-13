<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113152338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpool (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', from_location VARCHAR(255) NOT NULL, to_location VARCHAR(255) NOT NULL, places INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E95D90CCB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpool_members (id INT AUTO_INCREMENT NOT NULL, carpool_id INT NOT NULL, user_id INT NOT NULL, is_accepted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_66FDA9419A6F0DAE (carpool_id), INDEX IDX_66FDA941A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversations (id INT AUTO_INCREMENT NOT NULL, from_user_id INT NOT NULL, to_user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C2521BF12130303A (from_user_id), INDEX IDX_C2521BF129F6EE60 (to_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, professor_id INT DEFAULT NULL, promo_id INT NOT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', classroom VARCHAR(255) NOT NULL, tags LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A9A55A4C7D2D84D5 (professor_id), INDEX IDX_A9A55A4CD0C07AFF (promo_id), INDEX IDX_A9A55A4CB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5387574AB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, folder_id INT NOT NULL, weight VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6354059162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_ECA209CDB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lbc (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3DFCD291B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lbc_pictures (id INT AUTO_INCREMENT NOT NULL, lbc_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_235009629D950E60 (lbc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DB021E969AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages_images (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_277D14FC537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, image_path VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, is_view TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6000B0D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permissions (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, folder_id INT NOT NULL, is_readable TINYINT(1) NOT NULL, is_editable TINYINT(1) NOT NULL, is_deletable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2DEDCC6FA76ED395 (user_id), INDEX IDX_2DEDCC6FD0C07AFF (promo_id), INDEX IDX_2DEDCC6F162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_885DBAFAB03A8386 (created_by_id), INDEX IDX_885DBAFA12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_categories (id INT AUTO_INCREMENT NOT NULL, category_parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A8C3AA46B51A1840 (category_parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_comments (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, content LONGTEXT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_85514EE3B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_images (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8A1D99C34B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_report (id INT AUTO_INCREMENT NOT NULL, reported_by_id INT NOT NULL, post_id INT NOT NULL, post_comment_id INT DEFAULT NULL, category VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AE2D502D71CE806 (reported_by_id), INDEX IDX_AE2D502D4B89032C (post_id), INDEX IDX_AE2D502DDB1174D2 (post_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, promo_id INT DEFAULT NULL, campus_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649D0C07AFF (promo_id), INDEX IDX_8D93D649AF5D55E1 (campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carpool ADD CONSTRAINT FK_E95D90CCB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE carpool_members ADD CONSTRAINT FK_66FDA9419A6F0DAE FOREIGN KEY (carpool_id) REFERENCES carpool (id)');
        $this->addSql('ALTER TABLE carpool_members ADD CONSTRAINT FK_66FDA941A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF12130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversations ADD CONSTRAINT FK_C2521BF129F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C7D2D84D5 FOREIGN KEY (professor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CDB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lbc ADD CONSTRAINT FK_3DFCD291B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lbc_pictures ADD CONSTRAINT FK_235009629D950E60 FOREIGN KEY (lbc_id) REFERENCES lbc (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969AC0396 FOREIGN KEY (conversation_id) REFERENCES conversations (id)');
        $this->addSql('ALTER TABLE messages_images ADD CONSTRAINT FK_277D14FC537A1329 FOREIGN KEY (message_id) REFERENCES messages (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6F162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA12469DE2 FOREIGN KEY (category_id) REFERENCES posts_categories (id)');
        $this->addSql('ALTER TABLE posts_categories ADD CONSTRAINT FK_A8C3AA46B51A1840 FOREIGN KEY (category_parent_id) REFERENCES posts_categories (id)');
        $this->addSql('ALTER TABLE posts_comments ADD CONSTRAINT FK_85514EE3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posts_images ADD CONSTRAINT FK_8A1D99C34B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE posts_report ADD CONSTRAINT FK_AE2D502D71CE806 FOREIGN KEY (reported_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posts_report ADD CONSTRAINT FK_AE2D502D4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE posts_report ADD CONSTRAINT FK_AE2D502DDB1174D2 FOREIGN KEY (post_comment_id) REFERENCES posts_comments (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carpool DROP FOREIGN KEY FK_E95D90CCB03A8386');
        $this->addSql('ALTER TABLE carpool_members DROP FOREIGN KEY FK_66FDA9419A6F0DAE');
        $this->addSql('ALTER TABLE carpool_members DROP FOREIGN KEY FK_66FDA941A76ED395');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF12130303A');
        $this->addSql('ALTER TABLE conversations DROP FOREIGN KEY FK_C2521BF129F6EE60');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C7D2D84D5');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CD0C07AFF');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CB03A8386');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AB03A8386');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059162CB942');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CDB03A8386');
        $this->addSql('ALTER TABLE lbc DROP FOREIGN KEY FK_3DFCD291B03A8386');
        $this->addSql('ALTER TABLE lbc_pictures DROP FOREIGN KEY FK_235009629D950E60');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969AC0396');
        $this->addSql('ALTER TABLE messages_images DROP FOREIGN KEY FK_277D14FC537A1329');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FA76ED395');
        $this->addSql('ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FD0C07AFF');
        $this->addSql('ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6F162CB942');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAB03A8386');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA12469DE2');
        $this->addSql('ALTER TABLE posts_categories DROP FOREIGN KEY FK_A8C3AA46B51A1840');
        $this->addSql('ALTER TABLE posts_comments DROP FOREIGN KEY FK_85514EE3B03A8386');
        $this->addSql('ALTER TABLE posts_images DROP FOREIGN KEY FK_8A1D99C34B89032C');
        $this->addSql('ALTER TABLE posts_report DROP FOREIGN KEY FK_AE2D502D71CE806');
        $this->addSql('ALTER TABLE posts_report DROP FOREIGN KEY FK_AE2D502D4B89032C');
        $this->addSql('ALTER TABLE posts_report DROP FOREIGN KEY FK_AE2D502DDB1174D2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D0C07AFF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF5D55E1');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE carpool');
        $this->addSql('DROP TABLE carpool_members');
        $this->addSql('DROP TABLE conversations');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE lbc');
        $this->addSql('DROP TABLE lbc_pictures');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE messages_images');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE posts_categories');
        $this->addSql('DROP TABLE posts_comments');
        $this->addSql('DROP TABLE posts_images');
        $this->addSql('DROP TABLE posts_report');
        $this->addSql('DROP TABLE user');
    }
}
