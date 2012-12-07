<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Add Attribute entity
 */
class Version20121207110348 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE ice_user_attribute (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, field_name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, updated_by VARCHAR(255) NOT NULL, INDEX IDX_FA9A621FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ice_user_attribute ADD CONSTRAINT FK_FA9A621FA76ED395 FOREIGN KEY (user_id) REFERENCES ice_user (id)");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("DROP TABLE ice_user_attribute");
    }
}
