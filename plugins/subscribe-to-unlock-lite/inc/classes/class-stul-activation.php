<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('STUL_Activation')) {

    class STUL_Activation {

        function __construct() {
            register_activation_hook(STUL_PATH . 'subscribe-to-unlock-lite.php', array($this, 'activation_tasks'));
        }

        function activation_tasks() {
            $this->create_tables();
            $this->update_install_date();
        }

        function create_tables() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            $subscribers_table_name = STUL_SUBSCRIBERS_TABLE;
            $subscribers_table_sql = "CREATE TABLE $subscribers_table_name (
                    subscriber_id mediumint(9) NOT NULL AUTO_INCREMENT,
                    subscriber_name varchar(255) NOT NULL,
                    subscriber_email varchar(255) NOT NULL,
                    subscriber_form_alias varchar(255) NOT NULL,
                    subscriber_unlock_key varchar(255) NOT NULL,
                    subscriber_verification_status mediumint(9) DEFAULT 0 NOT NULL,
                    PRIMARY KEY  (subscriber_id)
                  ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($subscribers_table_sql);
        }

        function update_install_date() {
            if (empty(get_option('stul_plugin_install_date'))) {
                update_option('stul_plugin_install_date', date('Y-m-d'));
            }
        }
    }

    new STUL_Activation();
}
