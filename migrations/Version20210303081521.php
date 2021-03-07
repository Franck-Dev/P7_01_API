<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303081521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token ADD user_client_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EB190BE4C5 FOREIGN KEY (user_client_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7BA2F5EB190BE4C5 ON api_token (user_client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token DROP FOREIGN KEY FK_7BA2F5EB190BE4C5');
        $this->addSql('DROP INDEX UNIQ_7BA2F5EB190BE4C5 ON api_token');
        $this->addSql('ALTER TABLE api_token DROP user_client_id, CHANGE client_id client_id INT NOT NULL');
    }
}
