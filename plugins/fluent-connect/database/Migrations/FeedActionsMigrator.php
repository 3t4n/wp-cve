<?php

namespace FluentConnect\Database\Migrations;

class FeedActionsMigrator
{
    static $tableName = 'fcon_feed_actions';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `feed_id` BIGINT UNSIGNED NULL,
                `priority` INT(10) UNSIGNED NULL,
                `title` VARCHAR(194) NULL,
                `action_name` VARCHAR(194) NULL,
                `action_provider` VARCHAR(194) NULL,
                `status` VARCHAR(100) NULL DEFAULT 'draft',
                `settings` LONGTEXT NULL,
                `created_by` BIGINT UNSIGNED NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
