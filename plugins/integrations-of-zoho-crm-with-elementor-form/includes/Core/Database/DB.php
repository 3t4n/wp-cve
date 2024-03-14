<?php

/**
 * Class For Database Migration
 *
 * @category Database
 * @author   FormInteg Developer <developer@bitcode.pro>
 */
namespace FormInteg\IZCRMEF\Core\Database;

/**
 * Database Migration
 */
final class DB
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function migrate()
    {
        global $wpdb;
        $collate = '';

        if ($wpdb->has_cap('collation')) {
            if (!empty($wpdb->charset)) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }
            if (!empty($wpdb->collate)) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }
        $table_schema = [
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}izcrmef_log` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `flow_id` bigint(20) DEFAULT NULL,
                `job_id` bigint(20) DEFAULT NULL,
                `api_type` varchar(255) DEFAULT NULL,
                `response_type` varchar(50) DEFAULT NULL,
                `response_obj` LONGTEXT DEFAULT NULL,
                `created_at` DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                KEY `flow_id` (`flow_id`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}izcrmef_flow` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) DEFAULT NULL,
                `triggered_entity` varchar(50)  NOT NULL,
                `triggered_entity_id` varchar(100) DEFAULT NULL, /* form_id = 0 means all/app */
                `flow_details` longtext DEFAULT NULL,
                `status` tinyint(1) DEFAULT 1,/* 0 disabled, 1 enabled,  2 trashed */
                `user_id` bigint(20) unsigned DEFAULT NULL,
                `user_ip` int(11) unsigned DEFAULT NULL,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;"
        ];

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        foreach ($table_schema as $table) {
            dbDelta($table);
        }

        update_option(
            'izcrmef_db_version',
            IZCRMEF_DB_VERSION
        );
    }
}
