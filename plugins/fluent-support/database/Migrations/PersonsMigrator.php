<?php

namespace FluentSupport\Database\Migrations;

class PersonsMigrator
{
    static $tableName = 'fs_persons';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `first_name` VARCHAR(192) NULL,
                `last_name` VARCHAR(192) NULL,
                `email` VARCHAR(192) NULL,
                `title` VARCHAR(192) NULL,
                `avatar` VARCHAR(192) NULL,
                `person_type` VARCHAR(192) DEFAULT 'customer',
                `status` VARCHAR(192) DEFAULT 'active',
                `ip_address` VARCHAR(20) NULL,
                `last_ip_address` VARCHAR(20) NULL,
                `address_line_1` VARCHAR(192) NULL,
                `address_line_2` VARCHAR(192) NULL,
                `city` VARCHAR(192) NULL,
                `zip` VARCHAR(192) NULL,
                `state` VARCHAR(192) NULL,
                `country` VARCHAR(192) NULL,
                `note` LONGTEXT NULL,
                `hash` VARCHAR(192) NULL,
                `user_id` BIGINT(20) UNSIGNED NULL,
                `description` MEDIUMTEXT NULL,
                `remote_uid` BIGINT(20) UNSIGNED NULL,
                `last_response_at` TIMESTAMP NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        } else {
            // @todo: We will remove this on final release
            // This is only for beta users
            $existing_columns = $wpdb->get_col("DESC {$table}", 0);
            if(!in_array('title', $existing_columns)) {
                $query = 'ALTER TABLE '.$table.' ADD `title` VARCHAR(192) NULL AFTER `email`';
                $wpdb->query($query);
            }

            if(!in_array('description', $existing_columns)) {
                $query = 'ALTER TABLE '.$table.' ADD `description`  MEDIUMTEXT NULL AFTER `user_id`';
                $wpdb->query($query);
            }

        }
    }
}
