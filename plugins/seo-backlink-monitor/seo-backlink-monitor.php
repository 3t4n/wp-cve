<?php
/**
 * The file responsible for starting the SEO Backlink Monitor Plugin
 *
 * SEO Backlink Monitor is a WordPress plugin that lets you track your Link
 * Building campaign. Add your link and check if it is dofollow or nofollow,
 * live or not. Based on Syed Fakhar Abbas's "Backlink Monitoring Manager" v0.1.3
 * https://wordpress.org/plugins/backlink-monitoring-manager/
 *
 * @link              https://www.active-websight.de
 * @since             1.0.0
 * @package           SEO_BLM
 *
 * Plugin Name:       SEO Backlink Monitor
 * Plugin URI:        https://www.active-websight.de
 * Description:       SEO Backlink Monitor checks if the link is live or not. This plugin is based on <a href='https://wordpress.org/plugins/backlink-monitoring-manager/' target='_blank'>Backlink Monitoring Manager</a> (v0.1.3). Thanks to Syed Fakhar Abbas <3.
 * Version:           1.6.0
 * Author:            Active Websight
 * Author URI:        http://www.active-websight.de
 * Text Domain:       seo-backlink-monitor
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SEO_BLM_PLUGIN', 'seo-backlink-monitor' );
define( 'SEO_BLM_PLUGIN_NAME', 'SEO Backlink Monitor' );
if (!defined('SEO_BLM_PLUGIN_VERSION'))
	define( 'SEO_BLM_PLUGIN_VERSION', '1.6.0' );
define( 'SEO_BLM_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'SEO_BLM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SEO_BLM_OPTION_SETTINGS', 'seo_backlink_monitor_settings' );
define( 'SEO_BLM_OPTION_LINKS', 'seo_backlink_monitor_links' );
define( 'SEO_BLM_OPTION_VERSION', 'seo_backlink_monitor_version' );
define( 'SEO_BLM_OPTION_SESSION', 'seo_backlink_monitor_session' );
define( 'SEO_BLM_CRON', 'seo_backlink_monitor_cron' );

require_once SEO_BLM_PLUGIN_PATH . 'includes/class-seo-backlink-monitor.php';

function seo_backlink_monitor_cron_deactivate() {
	$timestamp = wp_next_scheduled( SEO_BLM_CRON );
	wp_unschedule_event( $timestamp, SEO_BLM_CRON );
}
register_deactivation_hook( __FILE__, 'seo_backlink_monitor_cron_deactivate' );

function run_seo_backlink_monitor() {
	$spmm = new SEO_Backlink_Monitor();
	$spmm->run();
}
run_seo_backlink_monitor();
