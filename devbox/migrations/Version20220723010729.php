<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220723010729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, client_ip VARCHAR(255) DEFAULT NULL, http_path VARCHAR(255) NOT NULL, http_verb VARCHAR(255) NOT NULL, http_version VARCHAR(255) NOT NULL, response_code SMALLINT NOT NULL, service_name VARCHAR(255) NOT NULL, timestamp DATETIME NOT NULL, user_id VARCHAR(255) DEFAULT NULL, INDEX service_name_idx (service_name), INDEX response_code_idx (response_code), INDEX timestamp_idx (timestamp), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE log');
    }
}
