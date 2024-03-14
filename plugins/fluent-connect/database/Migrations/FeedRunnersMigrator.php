<?php

namespace FluentConnect\Database\Migrations;

class FeedRunnersMigrator
{
    static $tableName = 'fcon_feed_runners';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `feed_id` BIGINT UNSIGNED NULL,
                `trigger_id` BIGINT UNSIGNED NULL,
                `trigger_data` LONGTEXT NULL,
                `status` VARCHAR(100) NULL DEFAULT 'draft',
                `runner_hash` VARCHAR(192) NULL,
                `last_action_serial` INT(10) UNSIGNED NULL,
                `last_action_id` BIGINT UNSIGNED NULL,
                 `scheduled_at` TIMESTAMP NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
