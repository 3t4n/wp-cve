<?php

namespace FluentSupport\Database\Migrations;

class MailBoxMigrator
{
    static $tableName = 'fs_mail_boxes';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `name` VARCHAR(192) NOT NULL,
                `slug` VARCHAR(192) NOT NULL,
                `box_type` VARCHAR(50) default 'web',
                `email` VARCHAR(192) NOT NULL,
                `mapped_email` VARCHAR(192) NULL,
                `email_footer` LONGTEXT NULL,
                `settings` LONGTEXT NULL,
                `avatar` VARCHAR(192) NULL,
                `created_by` BIGINT(20) UNSIGNED NULL,
                `is_default` ENUM('yes', 'no') DEFAULT 'no',
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
