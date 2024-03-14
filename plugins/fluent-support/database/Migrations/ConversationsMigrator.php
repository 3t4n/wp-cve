<?php

namespace FluentSupport\Database\Migrations;

class ConversationsMigrator
{
    static $tableName = 'fs_conversations';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `serial` INT(11) UNSIGNED DEFAULT 1,
                `ticket_id` BIGINT(20) UNSIGNED NOT NULL,
                `person_id` BIGINT(20) UNSIGNED NOT NULL,
                `conversation_type` VARCHAR(100) DEFAULT 'response',
                `content` LONGTEXT NULL,
                `source` VARCHAR(100) DEFAULT 'web',
                `content_hash` VARCHAR(192) NULL,
                `message_id` VARCHAR(192) NULL,
                `is_important` ENUM('yes', 'no') DEFAULT 'no',
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
