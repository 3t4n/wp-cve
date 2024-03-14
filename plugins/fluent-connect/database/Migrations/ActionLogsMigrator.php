<?php

namespace FluentConnect\Database\Migrations;

class ActionLogsMigrator
{
    static $tableName = 'fcon_action_logs';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `feed_id` BIGINT UNSIGNED NULL,
                `action_id` BIGINT UNSIGNED NULL,
                `runner_id` BIGINT UNSIGNED NULL,
                `remote_action_id` BIGINT UNSIGNED NULL,
                `status` VARCHAR(100) NULL DEFAULT 'processed',
                `reference_url` TEXT NULL,
                `description` TEXT NULL,
                `settings` LONGTEXT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
