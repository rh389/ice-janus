<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Update Attribute to allow updated and updated_by to be null
 */
class Version20121207121542 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE ice_user_attribute CHANGE updated updated DATETIME DEFAULT NULL, CHANGE updated_by updated_by VARCHAR(255) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE ice_user_attribute CHANGE updated updated DATETIME NOT NULL, CHANGE updated_by updated_by VARCHAR(255) NOT NULL");
    }
}
