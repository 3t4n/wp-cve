<?php
/**
 * Plugin Name: SKT Addons for Elementor
 * Plugin URI: https://www.sktthemes.org/shop/flexible-addons-for-elementor/
 * Description: SKT Addons for Elementor page builder is one of the great Elementor Addons that includes 123 absolutely free Elementor Widgets. These provide you more options to easily add more features and functionality into your existing website.
 * Version: 1.8
 * Author: SKT Themes
 * Author URI: https://www.sktthemes.org/
 * Elementor tested up to: 3.19.4
 * Elementor Pro tested up to: 3.19.4
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: skt-addons-elementor
 * Domain Path: /languages/
 *
 * @package Skt_Addons_Elementor

Copyright 2024 SKT Themes <https://www.sktthemes.org>
*/

defined('ABSPATH') || die();

define('SKT_ADDONS_ELEMENTOR_VERSION', '1.8');
define('SKT_ADDONS_ELEMENTOR__FILE__', __FILE__);
define('SKT_ADDONS_ELEMENTOR_DIR_PATH', plugin_dir_path(SKT_ADDONS_ELEMENTOR__FILE__));
define('SKT_ADDONS_ELEMENTOR_DIR_URL', plugin_dir_url(SKT_ADDONS_ELEMENTOR__FILE__));
define('SKT_ADDONS_ELEMENTOR_ASSETS', trailingslashit(SKT_ADDONS_ELEMENTOR_DIR_URL . 'assets'));
define('SKT_ADDONS_ELEMENTOR_REDIRECTION_FLAG', 'sktaddonselementor_do_activation_direct');

define('SKT_ADDONS_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION', '3.9.0');
define('SKT_ADDONS_ELEMENTOR_MINIMUM_PHP_VERSION', '7.4');

/**
 * The journey of a thousand miles starts here.
 *
 * @return void Some voids are not really void, you have to explore to figure out why not!
 */
function skt_addons_elementor_let_the_journey_begin()
{
    require(SKT_ADDONS_ELEMENTOR_DIR_PATH . 'inc/functions.php');

    // Check for required PHP version
    if (version_compare(PHP_VERSION, SKT_ADDONS_ELEMENTOR_MINIMUM_PHP_VERSION, '<')) {
        add_action('admin_notices', 'skt_addons_elementor_required_php_version_missing_notice');
        return;
    }

    // Check if Elementor installed and activated
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'skt_addons_elementor_missing_notice');
        return;
    }

    // Check for required Elementor version
    if (!version_compare(ELEMENTOR_VERSION, SKT_ADDONS_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION, '>=')) {
        add_action('admin_notices', 'skt_addons_elementor_required_elementor_version_missing_notice');
        return;
    }

    require SKT_ADDONS_ELEMENTOR_DIR_PATH . 'base.php';
    \Skt_Addons_Elementor\Elementor\Base::instance();
}

add_action('plugins_loaded', 'skt_addons_elementor_let_the_journey_begin');

/**
 * Admin notice for required php version
 *
 * @return void
 */
function skt_addons_elementor_required_php_version_missing_notice()
{
    $notice = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
        esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'skt-addons-elementor'),
        '<strong>' . esc_html__('SKT Elementor Addons', 'skt-addons-elementor') . '</strong>',
        '<strong>' . esc_html__('PHP', 'skt-addons-elementor') . '</strong>',
        SKT_ADDONS_ELEMENTOR_MINIMUM_PHP_VERSION
    );

    printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice);
}

/**
 * Admin notice for elementor if missing
 *
 * @return void
 */
function skt_addons_elementor_missing_notice()
{

    if (file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) {
        $notice_title = __('Activate Elementor', 'skt-addons-elementor');
        $notice_url = wp_nonce_url('plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php');
    } else {
        $notice_title = __('Install Elementor', 'skt-addons-elementor');
        $notice_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
    }

    $notice = skt_addons_elementor_kses_intermediate(sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
        __('%1$s requires %2$s to be installed and activated to function properly. %3$s', 'skt-addons-elementor'),
        '<strong>' . __('SKT Elementor Addons', 'skt-addons-elementor') . '</strong>',
        '<strong>' . __('Elementor', 'skt-addons-elementor') . '</strong>',
        '<a href="' . esc_url($notice_url) . '">' . $notice_title . '</a>'
    ));

    printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice);
}

/**
 * Admin notice for required elementor version
 *
 * @return void
 */
function skt_addons_elementor_required_elementor_version_missing_notice()
{
    $notice = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
        esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'skt-addons-elementor'),
        '<strong>' . esc_html__('SKT Elementor Addons', 'skt-addons-elementor') . '</strong>',
        '<strong>' . esc_html__('Elementor', 'skt-addons-elementor') . '</strong>',
        SKT_ADDONS_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION
    );

    printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice);
}

/**
 * Register actions that should run on activation
 *
 * @return void
 */
function skt_addons_elementor_register_activation_hook()
{
    add_option(SKT_ADDONS_ELEMENTOR_REDIRECTION_FLAG, true);

    // add plugin activation time
    $get_activation_time = strtotime("now");
    add_option('skt_addons_elementor_addons_activation_time', $get_activation_time);
}

register_activation_hook(SKT_ADDONS_ELEMENTOR__FILE__, 'skt_addons_elementor_register_activation_hook');