<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130611004513 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE ice_mailer_mailrequest (id INT AUTO_INCREMENT NOT NULL, template_name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, serialized_vars LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ice_mailer_mail (id INT AUTO_INCREMENT NOT NULL, recipient_id INT DEFAULT NULL, request_id INT DEFAULT NULL, compiled_body_plain LONGTEXT DEFAULT NULL, compiled_body_html LONGTEXT DEFAULT NULL, compiled_subject LONGTEXT DEFAULT NULL, compiled DATETIME DEFAULT NULL, INDEX IDX_C9B48515E92F8F78 (recipient_id), INDEX IDX_C9B48515427EB8A5 (request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ice_mailer_mail ADD CONSTRAINT FK_C9B48515E92F8F78 FOREIGN KEY (recipient_id) REFERENCES ice_user (id)");
        $this->addSql("ALTER TABLE ice_mailer_mail ADD CONSTRAINT FK_C9B48515427EB8A5 FOREIGN KEY (request_id) REFERENCES ice_mailer_mailrequest (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE ice_mailer_mail DROP FOREIGN KEY FK_C9B48515427EB8A5");
        $this->addSql("DROP TABLE ice_mailer_mailrequest");
        $this->addSql("DROP TABLE ice_mailer_mail");
    }
}
