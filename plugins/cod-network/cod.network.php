<?php
/**
 * @package Cod.Network
 */
/*
Plugin Name: cod.network
Plugin URI: https://cod.network/
Description: COD.network it's a platform where we can promote different offers by offering a remuneration to affiliates and managers in exchange of a sale to an offer.
Stable tag: 1.2.2
Version: 1.2.2
Requires PHP: 7.2
Tested up to: 5.9
Author: COD.NETWORK
License: GPLv2 or later
Text Domain: cod.network
*/

if (!defined('ABSPATH')) {
    exit;
}

define('CODN__PLUGIN_DIR', plugin_dir_path(__FILE__));

require __DIR__ . '/vendor/autoload.php';

use CODNetwork\Controller\CODN_Token_Controller;
use CODNetwork\Jobs\CODN_Clean_Logs_Job;
use CODNetwork\Jobs\CODN_Order_Job;
use CODNetwork\Models\CODN_Settings;
use CODNetwork\Repositories\CodNetworkRepository;
use CODNetwork\Services\CODN_Admin_Menus;
use CODNetwork\Services\CODN_Logger_Service;
use CODNetwork\Services\CODN_Order_Service;

define('CODN_NOTICE_WC_NOT_INSTALLED', 1);
define('CODN_NOTICE_SETUP_PLUGIN', 2);
define('CODN_NOTICE_WC_DISABLED', 3);
define('COD_PLUGIN_VERSION', '1.2.2');

/**
 *  COD.network Administration Pages
 */
function codn_make_front_page_cod_network()
{
    $codNetwork = CODN_Settings::get_instance();
    $connectLink = codn_get_connect_url();

    // TODO: use wordpress view method
    include_once("admin/view/settings.php");
}

/**
 *  Registers a stylesheet.
 */
function codn_load_resources()
{
    wp_register_style('codNetwork.css', sprintf('%s/_inc/codNetwork.css', codn_plugin_dir_path()));
    wp_enqueue_style('codNetwork.css');
}

/**
 * init Plugin
 * @return void
 */
function codn_init_cod_network()
{
    codn_load_resources();
    codn_make_front_page_cod_network();
    codn_register_worker_schedule();
}

/**
 * Make Menu Cod.network
 * @return void
 */
function codn_make_menu_cod_network()
{
    add_menu_page('Cod.network', 'Cod.network', 'manage_options', 'cod.network', 'codn_init_cod_network');
    $makeMenu = CODN_Admin_Menus::get_instance();
    $makeMenu::make_status_menu();
}

/**
 * Add Action onload plugin
 */

add_action('plugins_loaded', 'codn_init');

function codn_init()
{
    codn_register_dispatches();
}

/**
 * Add Administration Menus
 */
add_action('admin_menu', 'codn_make_menu_cod_network');

if (is_admin()) {
    $repository = CodNetworkRepository::get_instance();

    /** create tables */
    register_activation_hook(__FILE__, array($repository, 'create_table_setting'));
    register_activation_hook(__FILE__, array($repository, 'create_status_logs_activity'));
    register_activation_hook(__FILE__, array($repository, 'create_queue_table'));
    register_activation_hook(__FILE__, array($repository, 'create_queue_failures_table'));

    register_activation_hook(__FILE__, 'codn_register_worker_schedule');

    /** delete tables */
    register_deactivation_hook(__FILE__, array($repository, 'delete_table_setting'));
    register_deactivation_hook(__FILE__, array($repository, 'delete_table_queue'));
    register_deactivation_hook(__FILE__, array($repository, 'delete_table_queue_failures'));
}

function codn_register_dispatches()
{
    add_action(
        'woocommerce_new_order',
        function ($orderId) {
            $orderJob = new CODN_Order_Job($orderId);
            codn_custom_wp_queue()->push($orderJob);
        },
        1,
        1
    );
}

function codnetwork_add_every_week($schedules)
{
    $schedules['every_week'] = [
        'interval' => 604800,
        'display' => __('Every week', 'textdomain')
    ];

    return $schedules;
}

add_filter('cron_schedules', 'codnetwork_add_every_week');

if (!wp_next_scheduled('codnetwork_cron_processing_queue_cleaning_logs')) {
    wp_schedule_event(time(), 'every_week', 'codnetwork_cron_processing_queue_cleaning_logs');
}

add_action('codnetwork_cron_processing_queue_cleaning_logs', 'codnetwork_processing_queue_cleaning_logs', 1, 1);

/**
 * Worker queue cleaning logs
 *
 * @return bool
 */
function codnetwork_processing_queue_cleaning_logs()
{
    codn_custom_wp_queue()->push(new CODN_Clean_Logs_Job());
    codn_custom_wp_queue()->worker(1)->process();

    return true;
}

add_action('codnetwork_processing_queue', function () {
    codn_custom_wp_queue()->worker(1)->process();
});

/** Register worker */
function codn_register_worker_schedule()
{
    $timestamp = wp_next_scheduled('codnetwork_processing_queue');
    if ($timestamp == false) {
        wp_schedule_event(time(), 'every_minute', 'codnetwork_processing_queue');
    }
}

add_action('rest_api_init', function () {
    $controller = new CODN_Token_Controller();
    $controller->register_routes();
});

function codn_add_notice_cod_network_to_admin_pages()
{
    global $pagenow;
    $settings = new CODN_Settings();
    $hasToken = $settings->has_token();
    $user = wp_get_current_user();
    $activePluginWc = codn_wc_plugin_is_active();
    $existsPluginWc = codn_wc_plugin_is_loaded();

    if (!in_array('administrator', (array) $user->roles)) {
        return;
    }

    /** show notice download WC when plugin not exist */
    if (!$existsPluginWc) {
        $noticeType = CODN_NOTICE_WC_NOT_INSTALLED;
        include_once("admin/view/notice.php");
    }

    /** show notice Active WC when plugin not active */
    if ($existsPluginWc && !$activePluginWc) {
        $noticeType = CODN_NOTICE_WC_DISABLED;
        include_once("admin/view/notice.php");
    }

    /** show notice setup CodNetwork when token exist */
    if (!$hasToken && $activePluginWc) {
        $noticeType = CODN_NOTICE_SETUP_PLUGIN;
        include_once("admin/view/notice.php");
    }
}

add_action('admin_notices', 'codn_add_notice_cod_network_to_admin_pages');

