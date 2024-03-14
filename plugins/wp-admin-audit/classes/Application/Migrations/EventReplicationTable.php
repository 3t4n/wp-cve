<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Migration_EventReplicationTable extends WADA_Migration_Base {
    public $applicableBeforeVersion = '1.2';
    protected $eventReplicationTableExisting = true;

    public function __construct(){
        parent::__construct();
    }

    public function isMigrationApplicable(){
        $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
        if(version_compare($dbVersion, $this->applicableBeforeVersion, "<")){
            $this->eventReplicationTableExisting = WADA_Database::isTableExisting('wada_event_replications');

            if(!$this->eventReplicationTableExisting){
                WADA_Log::warning('EventReplicationTable migration is applicable (table missing)');
                return true;
            }
        }
        WADA_Log::debug('EventReplicationTable migration is NOT applicable');
        return false;
    }

    public function doMigration(){
        WADA_Log::info('EventReplicationTable doMigration');
        $res = array();
        if(!$this->eventReplicationTableExisting){
            $res[] = 'createEventReplicationTable: '.$this->createEventReplicationTable();
        }
        WADA_Log::info('EventReplicationTable migration results: '.print_r($res, true));
        return true;
    }

    protected function createEventReplicationTable(){
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."wada_event_replications (
                    id INT NOT NULL AUTO_INCREMENT,
                    login_date DATE NOT NULL,
                    login_successful TINYINT(1) NOT NULL,
                    user_login VARCHAR(127) NOT NULL,
                    user_login_existing TINYINT(1) NOT NULL,
                    user_id INT NULL,
                    ip_address VARBINARY(16) NOT NULL,
                    PRIMARY KEY (id)
                ) ".$this->charsetCollate.";";
        return $this->wpdb->query($sql);
    }

}