<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116155939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE challenge (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, reward INT NOT NULL, created_on DATETIME NOT NULL, difficulty SMALLINT NOT NULL, INDEX IDX_D7098951B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE validation (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, challenge_id INT NOT NULL, validated_on DATETIME NOT NULL, feedback SMALLINT NOT NULL, INDEX IDX_16AC5B6EB03A8386 (created_by_id), INDEX IDX_16AC5B6E98A21AC6 (challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, score INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attempt (id INT AUTO_INCREMENT NOT NULL, attempted_by_id INT NOT NULL, challenge_id INT NOT NULL, attempted_on DATETIME NOT NULL, attempt VARCHAR(255) NOT NULL, INDEX IDX_18EC0266A3C0CBD4 (attempted_by_id), INDEX IDX_18EC026698A21AC6 (challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6E98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE attempt ADD CONSTRAINT FK_18EC0266A3C0CBD4 FOREIGN KEY (attempted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attempt ADD CONSTRAINT FK_18EC026698A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6E98A21AC6');
        $this->addSql('ALTER TABLE attempt DROP FOREIGN KEY FK_18EC026698A21AC6');
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951B03A8386');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EB03A8386');
        $this->addSql('ALTER TABLE attempt DROP FOREIGN KEY FK_18EC0266A3C0CBD4');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('DROP TABLE validation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE attempt');
    }
}
