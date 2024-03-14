<?php

namespace FluentSupport\Database\Migrations;

class TagRelationsMigrator
{
    static $tableName = 'fs_tag_pivot';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `tag_id` BIGINT(20) UNSIGNED NOT NULL,
                `source_id` BIGINT(20) UNSIGNED NOT NULL,
                `source_type` VARCHAR(192) NOT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
