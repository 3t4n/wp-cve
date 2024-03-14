<?php

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

update_option('elastic-email-subscribe-basename', plugin_basename(__FILE__));

function subscribe_deactivation_admin_notice__info()
{
    $class = 'notice notice-info';
    $message = __('Plugin Elastic Email Subscribe Form has just been activated. We\'ve detected the use of our second product - Elastic Email Sender. A plugin that has been activated will replace the deactivated one.', 'elastic-email-sender');

    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
}

if (is_plugin_active(get_option('elastic-email-sender-basename'))) {

    deactivate_plugins(get_option('elastic-email-sender-basename'));
    add_action('admin_notices', 'subscribe_deactivation_admin_notice__info');

} else {

    /*
     * Plugin Name: Elastic Email Subscribe Form
     * Version: 1.2.2
     * Plugin URI: https://wordpress.org/plugins/elastic-email-subscribe-form/
     * Description: This plugin add subscribe widget to your page and integration with Elastic Email account.
     * Author: Elastic Email Inc.
     * Author URI: https://elasticemail.com
     * Network: false
     * Text Domain: elastic-email-subscribe-form
     * Domain Path: /languages
     */

    /**
     * @author    Elastic Email Inc.
     * @copyright Elastic Email, 2021, All Rights Reserved
     * This code is released under the GPL licence version 3 or later, available here
     * https://www.gnu.org/licenses/gpl.txt
     */

    global $wp_version;
    $exit_msg = 'ElasticEmail Sender requires WordPress 4.1 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress"> Please update!</a>';

    if (version_compare($wp_version, "4.1", "<")) {
        exit($exit_msg);
    }

    require_once('defaults/function.reset_pass.php');
    if (!class_exists('eemail')) {
        require_once('class/eesf_mail.php');
        eemail::on_load(__DIR__);
    }

    /* ----------- ADMIN ----------- */

    if (is_admin()) {

        update_option('eesf_plugin_dir_name', plugin_basename(__DIR__));
        update_option('eesf_plugin_path', plugins_url() . '/' . plugin_basename(__DIR__));

        register_activation_hook(__FILE__, 'elasticemailsubscribe_activate');
        register_deactivation_hook(__FILE__, 'elasticemailsubscribe_deactivate');
        register_uninstall_hook(__FILE__, 'elasticemailsubscribe_uninstall');

        require_once 'class/eesf_admin.php';
        $ee_admin = new eeadmin_subscribe_7250232799(__DIR__);

        add_action('wp_ajax_subscribe_send_test', 'eeSubscribeTestMsg');
    }

    function wp_upe_upgrade_completed( $upgrader_object, $options ) {
        $our_plugin = plugin_basename( __FILE__ );
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
         foreach( $options['plugins'] as $plugin ) {
          if( $plugin == $our_plugin ) {
            if (get_option('ee_mimetype') === false ) {
                update_option('ee_mimetype', 'auto');
            }
          }
         }
        }
    }
    
    add_action( 'upgrader_process_complete', 'wp_upe_upgrade_completed', 10, 2 );
    
    function elasticemailsubscribe_activate()
    {
        update_option('ee_actualselectedlist', '00000000-0000-0000-0000-000000000000');
        update_option('ee_mimetype', 'auto');
        create_elasticemail_log_table();
    }

    function elasticemailsubscribe_deactivate()
    {
        update_option('ee_actualselectedlist', '');
        update_option('daterangeselect', 'last-wk');
        update_option('elastic-email-subscribe-status', false);

        require_once 'class/eesf_admin.php';
        $eeadmin_subscribe = new eeadmin_subscribe_7250232799(__DIR__);

        if (class_exists('ElasticEmailClient\\ApiClient')) {
            $eeadmin_subscribe->addToUserList('D');
        }
    }

    function elasticemailsubscribe_uninstall()
    {
        unregister_widget('eeswidgetadmin');

        $optionsList = [
                'eesf-connecting-status',
                'ee_options',
                'ee_selectedlists_html',
                'ee_publicaccountid',
                'ee_enablecontactfeatures',
                'ee-listdata_json',
                'ee-listname',
                'ee-apikey',
                'eesf_plugin_path',
                'ee_actualselectedlist',
                'eesf_plugin_dir_name',
                'daterangeselect',
                'ee_config_override_wooCommerce',
                'ee_accountemail',
                'ee_accountemail_2',
                'ee-list-checkbox',
                'ee_send-email-type',
                'eesf_is_created_channels',
                'ee_mimetype'
            ];

        foreach ($optionsList as $option) {
            delete_option($option);
        }
    }

    function eeSubscribeTestMsg()
    {
        $key = filter_input(INPUT_GET, "hex", FILTER_SANITIZE_STRING);
        if ($key === '422f753b2d746e205b422e2068276f352143') {
            $to = $_POST['to'];
            $subject = 'Elastic Email Subscribe Form send test';
            $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_STRING);

            $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_STRING);
            $send = eemail::send($to, $subject, $message, null, null, true);
            exit($send);
        }
    }

    function create_elasticemail_log_table() 
    {
        global $wpdb;
        $table = $wpdb->prefix . 'elasticemail_log';
        $charset_collate = $wpdb->get_charset_collate();

        $query =  "CREATE TABLE IF NOT EXISTS  ".$table." (
                    id INT(11) AUTO_INCREMENT,
                    date TEXT(120),
                    error TEXT(255),
                    PRIMARY KEY(id)
                    )$charset_collate;";

        $wpdb->query( $query );
    }

    function drop_elasticemail_log_table() 
    {
        global $wpdb;
        $table = $wpdb->prefix . 'elasticemail_log';
        $wpdb->query( "DROP TABLE IF EXISTS ".$table);
    }

    function clean_elasticemail_log_table() 
    {
        global $wpdb;
        $table = $wpdb->prefix . 'elasticemail_log';
        $wpdb->query( "TRUNCATE TABLE ".$table);
    }

    add_action('wp_ajax_clean_error_log', 'eeCleanErrorLog');

    function eeCleanErrorLog()
    {
        $key = filter_input(INPUT_GET, "hex", FILTER_SANITIZE_STRING);
        if ($key === '222h753b5d796e205b422e2068274f351991') {
            clean_elasticemail_log_table();
            wp_send_json(true);
        }
    }

    //widget init
    add_action('widgets_init', 'ees_register_widget');

    function ees_register_widget()
    {
        require_once(dirname(__FILE__) . '/class/eesf_widget.php');
        register_widget('EESW_Widget');
    }

    require_once 'security/eesf_security.php';
}
