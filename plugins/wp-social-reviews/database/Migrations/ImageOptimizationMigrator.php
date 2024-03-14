<?php

namespace WPSocialReviews\Database\Migrations;

class ImageOptimizationMigrator
{
    static $tableName = 'wpsr_optimize_images';

    public static function migrate()
    {
        global $wpdb;
        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `platform` varchar(255) null,		
                `user_id` varchar(255),
                `user_name` varchar(255),
                `json_data`	LONGTEXT NULL,
                `fields` LONGTEXT NULL,
                `media_id` varchar(1000),
                `sizes`	varchar(1000),
                `aspect_ratio` DECIMAL (4, 2) DEFAULT 0 NOT NULL,
                `images_resized` tinyint(1),
                `last_requested` TIMESTAMP NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $created = dbDelta($sql);
            update_option('wpsr_optimize_images_table_status', true, 'no');
            return $created;
        }
        return false;
    }
}