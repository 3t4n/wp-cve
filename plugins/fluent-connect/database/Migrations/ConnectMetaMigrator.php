<?php

namespace FluentConnect\Database\Migrations;

class ConnectMetaMigrator
{
    static $tableName = 'fcon_meta';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `object_id` BIGINT UNSIGNED NULL,
                `object_type` VARCHAR(194) NULL,
                `meta_key` VARCHAR(194) NULL,
                `meta_value` LONGTEXT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
