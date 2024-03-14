<?php

namespace FluentSupport\Database\Migrations;

class ActivityMigrator
{
    static $tableName = 'fs_activities';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `person_id` BIGINT(20) NULL,
                `person_type` VARCHAR(192) NULL,
                `event_type` VARCHAR(192) NULL,
                `object_id` BIGINT(20) NULL,
                `object_type` VARCHAR(192) NULL,
                `description` MEDIUMTEXT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
