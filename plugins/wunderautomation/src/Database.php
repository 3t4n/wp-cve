<?php

namespace WunderAuto;

/**
 * Class Database
 */
class Database
{
    /**
     * @var string
     */
    private $dbVersion;

    /**
     * Constructor
     *
     * @param string $dbVersion
     */
    public function __construct($dbVersion)
    {
        $this->dbVersion = $dbVersion;
    }

    /**
     * Checks and update WunderAuto database tables
     *
     * @return void
     */
    public function databaseVersionCheck()
    {
        $wpdb = wa_get_wpdb();

        $currentDbVersion = get_option('wa_db_version', false);

        if ($currentDbVersion !== $this->dbVersion) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $collate = $wpdb->get_charset_collate();

            $tableName = $wpdb->prefix . 'wa_log';
            $sql       = "CREATE TABLE $tableName (
                id int(11) NOT NULL AUTO_INCREMENT,
                time datetime NOT NULL,
                session varchar(8) NOT NULL,
                level varchar(16),
                message varchar(512),
                context text,
                PRIMARY KEY  (id)
            ) $collate;";
            dbDelta($sql);

            $tableName = $wpdb->prefix . 'wa_queue';
            $sql       = "CREATE TABLE $tableName (
                id int(11) NOT NULL AUTO_INCREMENT,
                workflow_id int(11) NOT NULL,
                created datetime NOT NULL,
                time datetime NOT NULL,
                args varchar(1024),
                failed int(1),
                fail_reason int(3),
                PRIMARY KEY  (id)
            ) $collate;";
            dbDelta($sql);

            $tableName = $wpdb->prefix . 'wa_confirmationlinks';
            $sql       = "CREATE TABLE $tableName (
                id int(11) NOT NULL AUTO_INCREMENT,
                name varchar(128) NOT NULL,
                code varchar(32) NOT NULL,
                created datetime NOT NULL,
                expires datetime NOT NULL,
                clicked int(1),
                click_limit int(11),                
                args varchar(128),
                on_success varchar(256),
                on_expired varchar(256),
                PRIMARY KEY  (id)
            ) $collate;";
            dbDelta($sql);

            update_option('wa_db_version', $this->dbVersion, true);
        }
    }
}
