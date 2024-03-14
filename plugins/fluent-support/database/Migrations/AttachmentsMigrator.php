<?php

namespace FluentSupport\Database\Migrations;

class AttachmentsMigrator
{
    static $tableName = 'fs_attachments';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `ticket_id` BIGINT(20) UNSIGNED NULL,
                `person_id` BIGINT(20) UNSIGNED NULL,
                `conversation_id` BIGINT(20) UNSIGNED NULL,
                `file_type` VARCHAR(100) NULL,
                `file_path` TEXT NULL,
                `full_url` TEXT NULL,
                `settings` TEXT NULL,
                `title` VARCHAR(192) NULL,
                `file_hash` VARCHAR(192) NULL,
                `driver` VARCHAR(100) DEFAULT 'local',
                `status` VARCHAR(100) NULL DEFAULT 'active',
                `file_size` VARCHAR(100) NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        } else {
            // @todo: We will remove this on final release
            // This is only for beta users
            $existing_columns = $wpdb->get_col("DESC {$table}", 0);
            if(!in_array('status', $existing_columns)) {
                $query = "ALTER TABLE {$table} ADD `status` VARCHAR(100) NULL DEFAULT 'active' AFTER `driver`";
                $wpdb->query($query);
            }
        }
    }
}
