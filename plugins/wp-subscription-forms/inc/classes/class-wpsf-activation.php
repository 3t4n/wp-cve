<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('WPSF_Activation')) {

    class WPSF_Activation {

        function __construct() {
            register_activation_hook(WPSF_PATH . 'wp-subscription-forms.php', array($this, 'activation_tasks'));
        }

        function activation_tasks() {
            $this->create_tables();
            $this->update_install_date();
        }

        function create_tables() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $form_table_name = WPSF_FORM_TABLE;
            $subscribers_table_name = WPSF_SUBSCRIBERS_TABLE;
            $form_table_sql = "CREATE TABLE $form_table_name (
                    form_id mediumint(9) NOT NULL AUTO_INCREMENT,
                    form_title varchar(255) NOT NULL,
                    form_alias varchar(255) NOT NULL,
                    form_details longtext NOT NULL,
                    form_status mediumint(9) DEFAULT 1 NOT NULL,
                    PRIMARY KEY  (form_id)
                  ) $charset_collate;";
            $subscribers_table_sql = "CREATE TABLE $subscribers_table_name (
                    subscriber_id mediumint(9) NOT NULL AUTO_INCREMENT,
                    subscriber_name varchar(255) NOT NULL,
                    subscriber_email varchar(255) NOT NULL,
                    subscriber_form_alias varchar(255) NOT NULL,
                    PRIMARY KEY  (subscriber_id)
                  ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($form_table_sql);
            dbDelta($subscribers_table_sql);
        }

        function update_install_date() {
            if (empty(get_option('wpsf_plugin_install_date'))) {
                update_option('wpsf_plugin_install_date', date('Y-m-d'));
            }
        }
    }

    new WPSF_Activation();
}
