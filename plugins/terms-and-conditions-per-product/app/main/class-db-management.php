<?php
/**
 * Class for managing the DB.
 *
 * @package TACPP4_DB_Management
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'TACPP4_DB_Management' ) ) {

    /**
     * Class for the plugin's core.
     */
    class TACPP4_DB_Management {

        /**
         * Constructor for class.
         */
        public function __construct() {

            // Register Activation hooks

            register_activation_hook( TACPP4_PLUGIN_FILE, array( 'TACPP4_DB_Management', 'create_accept_log_table' ) );
            register_uninstall_hook( TACPP4_PLUGIN_FILE, array( 'TACPP4_DB_Management', 'delete_accept_log_table' ) );
        }


        /**
         * Create the custom table for logging the product likes
         * Since 1.0.0
         */
        static function create_accept_log_table() {
            global $wpdb;

            $table_name = $wpdb->prefix . TACPP4_ACCEPT_LOG_TABLE_NAME;

            // Check if table exists.
            $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

            if ( $wpdb->get_var( $query ) === $table_name ) {
                return true;
            }

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE `{$table_name}`  (
                        `id` bigint NOT NULL AUTO_INCREMENT,
                        `user_id` bigint NULL,
                        `order_id` bigint NULL,
                        `type` varchar(255) NULL,
                        `terms_url` text NULL,
                        `terms_text` text NULL,
                        `product_id` bigint(20) NULL,
                        `variation_id` bigint(20) NULL,
                        `term_id` bigint(20) NULL,
                        `date_recorded` datetime NULL,
                          PRIMARY KEY (`id`) USING BTREE,
                          INDEX `user_id`(`user_id`) USING BTREE
                        ) ENGINE = InnoDB  {$charset_collate} ROW_FORMAT = COMPACT;
                        ";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $result = dbDelta( $sql );

        }


        // Delete DB tables on Plugin removal
        static function delete_accept_log_table() {
            global $wpdb;

            $tables = array(
                $wpdb->prefix . TACPP4_ACCEPT_LOG_TABLE_NAME,
            );


            foreach ( $tables as $table_name ) {

                $sql = "DROP TABLE IF EXISTS $table_name";
                $wpdb->query( $sql );
            }

        }

    }

    new TACPP4_DB_Management();
}
