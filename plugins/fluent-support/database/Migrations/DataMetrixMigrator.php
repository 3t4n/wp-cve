<?php

namespace FluentSupport\Database\Migrations;

class DataMetrixMigrator
{
    static $tableName = 'fs_data_metrix';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `stat_date` DATE NOT NULL,
                `data_type` VARCHAR(100) DEFAULT 'agent_stat',
                `agent_id` BIGINT(20) UNSIGNED NULL,
                `replies` INT(11) UNSIGNED NULL DEFAULT 0,  /* replies count in that date */
                `active_tickets` INT(11) UNSIGNED NULL DEFAULT 0,  /* ticket counts without new and closed */
                `resolved_tickets` INT(11) UNSIGNED NULL DEFAULT 0, /* tickets that got closed today */
                `new_tickets` INT(11) UNSIGNED NULL DEFAULT 0, /* all new status ticket count */
                `unassigned_tickets` INT(11) UNSIGNED NULL DEFAULT 0, /* For Global use case only */
                `close_to_average` INT(11) UNSIGNED NULL DEFAULT 0, /* average close time of the tickets */
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
