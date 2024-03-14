<?php

/*
  Plugin Name: SwiftCloud E-Sign Connector
  Plugin URL: https://swiftcloud.ai/software/electronic-signature
  Description: Electronic Signature - e-Sign connected to Wordpress. NOTE: This Plugin is NOT required to make it work. 
  See http://youtube.com/watch?v=G9eLFDv8sDg before install. 
  See https://swiftcloud.ai/software/electronic-signature for details. 
  e-Signature happens on SwiftCloud servers, using [shortcodes] similar to Wordpress, and typically returns the user back here to WP. 
  This plugin can help with the thanks, and will soon add tracking and reporting.
  Version: 2.0.2
  Author: SwiftCloud E-Sign
  Author URI: https://swiftcrm.com/software/electronic-signature
  Text Domain: swiftsign
  Domain Path: /swiftsign/
 */

define('SWIFTSIGN_VERSION', '2.0.2');
define('SWIFTSIGN_MINIMUM_WP_VERSION', '4.5');
define('SWIFTSIGN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SWIFTSIGN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SWIFTSIGN_PLUGIN_PREFIX', 'ssing_');

register_activation_hook(__FILE__, 'ssign_install');

function ssign_install() {
    if (version_compare($GLOBALS['wp_version'], SWIFTSIGN_MINIMUM_WP_VERSION, '<')) {
        add_action('admin_notices', create_function('', "
        echo '<div class=\"error\"><p>" . sprintf(esc_html__('Swift Signature %s requires WordPress %s or higher.', 'swiftsign'), SWIFTSIGN_VERSION, SWIFTSIGN_MINIMUM_WP_VERSION) . "</p></div>'; "));

        add_action('admin_init', 'ssign_deactivate_self');

        function ssign_deactivate_self() {
            if (isset($_GET["activate"]))
                unset($_GET["activate"]);
            deactivate_plugins(plugin_basename(__FILE__));
        }

        return;
    }
    update_option('swift_sign_version', SWIFTSIGN_VERSION);

    /**
     *      Add table ssing log
     */
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $tab_ssing_local_capture = $wpdb->prefix . 'ssing_log';
    $ssing_log = "CREATE TABLE IF NOT EXISTS $tab_ssing_local_capture (
		 `ssign_id` mediumint(9) NOT NULL AUTO_INCREMENT,
                 `ssign_capture_name` varchar(255) NOT NULL,
                 `ssign_capture_email` varchar(255) NOT NULL,
		 `ssign_capture_data` LONGTEXT NULL,
                 `ssign_status` TINYINT DEFAULT '0' NOT NULL,
		 `date_time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		 PRIMARY KEY (`ssign_id`)
	) $charset_collate;";

    dbDelta($ssing_log);

    $table_lead_report = $wpdb->prefix . 'ssing_lead_report';
    $create_table_lead_report = "CREATE TABLE IF NOT EXISTS `$table_lead_report` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `lead_date` date NOT NULL DEFAULT '0000-00-00',
                    `lead_pageid` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                  ) $charset_collate ;";
    dbDelta($create_table_lead_report);
}

function ssign_initial_data() {
    /**
     *   Auto generate pages
     */
    $pages_array = array(
        "thankspage" => array("title" => "Thanks (return page after e-signature)", "content" => "<p>Thanks! We have received your doc(s). A signed copy has been sent to you for your records.</p>", "slug" => "signedthanks", "option" => "swiftsignature_thanks_page_id", "template" => "swiftsignature-thanks.php")
    );
    $ssign_pages_id = '';
    foreach ($pages_array as $key => $page) {
        $page_data = array(
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_title' => $page['title'],
            'post_name' => $page['slug'],
            'post_content' => $page['content'],
            'comment_status' => 'closed'
        );
        $page_id = wp_insert_post($page_data);
        $ssign_pages_id .= $page_id . ",";

        if (isset($page['option']) && !empty($page['option'])) {
            update_option($page['option'], sanitize_text_field($page_id));
        }
        if (isset($page['template']) && !empty($page['template'])) {
            update_post_meta($page_id, '_wp_page_template', $page['template']);
        }
    }

    if (!empty($ssign_pages_id)) {
        update_option('swiftsign_pages', rtrim($ssign_pages_id, ","));
    }

    // default set dashboard widget to ON
    update_option('ssing_dashboard_widget_flag', 1);
}

/**
 *  Update checking
 */
function ssign_update_check() {
    if (get_option("swift_sign_version") != SWIFTSIGN_VERSION) {
        ssign_install();
    }
    load_plugin_textdomain('swiftsign', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

add_action('plugins_loaded', 'ssign_update_check');

/**
 *  Update process
 * */
add_action('upgrader_process_complete', 'ssign_update_process');

function ssign_update_process($upgrader_object, $options = '') {
    $current_plugin_path_name = plugin_basename(__FILE__);

    if (isset($options) && !empty($options) && $options['action'] == 'update' && $options['type'] == 'plugin' && $options['bulk'] == false && $options['bulk'] == false) {
        foreach ($options['packages'] as $each_plugin) {
            if ($each_plugin == $current_plugin_path_name) {
                ssign_install();
                ssign_initial_data();
            }
        }
    }
}

/**
 *      Deactive plugin
 *      Remove Tabel sb_email_template
 */
register_deactivation_hook(__FILE__, 'ssign_deactive_plugin');

function ssign_deactive_plugin() {
    
}

/**
 *      Uninstall plugin
 */
register_uninstall_hook(__FILE__, 'swiftsign_uninstall_callback');
if (!function_exists('swiftsign_uninstall_callback')) {

    function swiftsign_uninstall_callback() {
        delete_option("swift_sign_version");
        delete_option("ssign_notice");

        global $wpdb;
        $table_log = $wpdb->prefix . 'ssing_log';
        $wpdb->query("DROP TABLE IF EXISTS $table_log");

        $table_lead_report = $wpdb->prefix . 'ssing_lead_report';
        $wpdb->query("DROP TABLE IF EXISTS $table_lead_report");

        // delete pages
        $pages = get_option('swiftsign_pages');
        if ($pages) {
            $pages = explode(",", $pages);
            foreach ($pages as $pid) {
                wp_delete_post($pid, true);
            }
        }
        delete_option("swiftsign_pages");
    }

}

/**
 *  Frontend css
 */
add_action('wp_enqueue_scripts', 'ss_enqueue_scripts_styles');

function ss_enqueue_scripts_styles() {
    wp_enqueue_style('ss-style', plugins_url('css/swiftsignature-style.css', __FILE__), '', '', '');
    wp_enqueue_script('ssing-script', plugins_url('js/swiftsignature-script.js', __FILE__), array('jquery'), '', true);

    wp_localize_script('ssing-script', 'ssign_ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'ssing_plugin_home_url' => SWIFTSIGN_PLUGIN_URL));

    if (wp_style_is('bootstrap.css', 'enqueued') || wp_style_is('bootstrap.min.css', 'enqueued')) {
        return;
    } else {
        wp_enqueue_style('swift-bs-modal', plugins_url('/css/ssing_bs_modal.min.css', __FILE__), '', '', '');
    }
}

include('swiftsignature-pagetemplater.php');
include('admin/swiftsignature-admin.php');
include('section/swiftsignature-form-shortcode.php');
include('section/swiftsignature-shortcode.php');
