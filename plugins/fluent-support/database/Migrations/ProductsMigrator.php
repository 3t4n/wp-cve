<?php

namespace FluentSupport\Database\Migrations;

class ProductsMigrator
{
    static $tableName = 'fs_products';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `source_uid` BIGINT(20) UNSIGNED NULL,
                `mailbox_id` BIGINT(20) UNSIGNED NULL,
                `title` VARCHAR(192) NULL,
                `description` TEXT NULL,
                `settings` LONGTEXT NULL,
                `source` VARCHAR(100) DEFAULT 'local',
                `created_by` BIGINT(20) UNSIGNED NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }

    }
}
