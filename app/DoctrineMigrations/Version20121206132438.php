<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Add middle names to User entity and remove non-name field as these will be stored as key-value pairs later.
 */
class Version20121206132438 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE ice_user ADD middle_names VARCHAR(255) DEFAULT NULL, DROP dob, CHANGE last_name last_names VARCHAR(255) NOT NULL");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE ice_user ADD dob DATE DEFAULT NULL, DROP middle_names, CHANGE last_names last_name VARCHAR(255) NOT NULL");
    }
}
