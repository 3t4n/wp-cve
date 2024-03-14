<?php
/**
 * WP Ultimate Exporter.
 *
 * WP Ultimate Exporter plugin file.
 *
 * @package   Smackcoders\SMEXP
 * @copyright Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: WP Ultimate Exporter
 * Version:     2.4.5
 * Plugin URI:  https://www.smackcoders.com/ultimate-exporter.html
 * Description: Backup tool to export all your WordPress data as CSV file. eCommerce data of WooCommerce, eCommerce, Custom Post and Custom field information along with default WordPress modules.
 * Author:      Smackcoders
 * Author URI:  https://www.smackcoders.com/wordpress.html
 * Text Domain: wp-ultimate-exporter
 * Domain Path: /languages
 * License:     GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

define('IMPORTER_VERSION', '7.10.10');
define('EXPORTER_VERSION', '2.4.5');
require_once('Plugin.php');
require_once('SmackExporterInstall.php');
require_once('exportExtensions/ExportExtension.php');
	
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$upload = wp_upload_dir();
$exports_dir = $upload['basedir'] . '/smack_uci_uploads/exports/';
$htaccess_file = $exports_dir . '.htaccess';
if (file_exists($htaccess_file)) {
	unlink($htaccess_file);
}
if (is_plugin_active('wp-ultimate-exporter/wp-ultimate-exporter.php')) {	
	$plugin_pages = ['com.smackcoders.csvimporternew.menu'];
	include __DIR__ . '/wp-exp-hooks.php';
	global $plugin_ajax_hooks;
	
	$request_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	$request_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	if (in_array($request_page, $plugin_pages) || in_array($request_action, $plugin_ajax_hooks)) {
		require_once('exportExtensions/JetEngine.php');
		require_once('exportExtensions/LearnPress.php');
		require_once('exportExtensions/MetaBox.php');
		require_once('exportExtensions/ExportHandler.php');
		require_once('exportExtensions/CustomerReviewsExport.php');
		require_once('exportExtensions/PostExport.php');
		require_once('exportExtensions/WooComExport.php');
		
	}
}

if(! class_exists('Smackcoders\SMEXP\ExportExtension')){	
	ExpInstall::init(plugin_basename( __FILE__ ));	
	add_action( 'admin_notices', 'Smackcoders\\SMEXP\\Notice_msg_exporter_free' );
}
else {
class ExpCSVHandler extends ExportExtension{

	protected static $instance = null,$install,$exp_instance;

	public function __construct(){ 
		$this->plugin = Plugin::getInstance();		
		add_action('wp_ajax_get_plugin_notice', array($this,'check_parent_plugin_version'));
			// add_action('wp_ajax_upgrade_notices_csv_bro',array($this,'dismiss_function'));
			add_action('wp_ajax_upgrade_notices_csv_bro', array($this,'dismiss_function')); 
			add_action('wp_ajax_nopriv_upgrade_notices_csv_bro', array($this,'dismiss_function'));
			add_action('wp_ajax_dismiss_notice',array($this,'dismiss_function_new'));
	}

	public  function dismiss_function_new() {
		update_option('csv_update_option', 0);
		exit();
	}

	public  function dismiss_function() {
		update_option('csv_update_option', 0);
		$response =array();
		$response['value'] = 0;
		echo wp_json_encode($response);
		wp_die();
		// exit();
	}
	function check_parent_plugin_version() {
		$parent_plugin_dir = WP_PLUGIN_DIR . '/wp-ultimate-csv-importer/wp-ultimate-csv-importer.php';
		$parent_plugin_data = get_plugin_data($parent_plugin_dir);
		$parent_version=$parent_plugin_data['Version'];

		$child_plugin_dir = WP_PLUGIN_DIR . '/wp-ultimate-exporter/wp-ultimate-exporter.php';
		$child_plugin_data = get_plugin_data($child_plugin_dir);
		$child_version=$child_plugin_data['Version'];
		// if($parent_version < IMPORTER_VERSION || $child_version < EXPORTER_VERSION ){
		 if(version_compare($parent_version, IMPORTER_VERSION, '<') || version_compare($child_version,EXPORTER_VERSION, '<')){
			$option_name = 'csv_update_option';
			$option_value = 1;
			$autoload = true;
			if (get_option($option_name) === false) {
				add_option($option_name, $option_value, '', $autoload);
			}
			if(get_option('csv_update_option') == 1){
				$response=array();
				$response['message'] ='Important note: Please upgrade latest versions for security updates.';
				$response['link'] = admin_url('plugins.php');
				$response['value'] = 1;
				$response['dismiss'] = 'Dismiss this notice.';
				$response['upgrade'] = 'Upgrade Now';
				echo wp_json_encode($response);
				wp_die();
				?>
				<!-- <div class="notice notice-info is-dismissible">
					<p><?php//_e('Important note: Please upgrade your WP Ultimate CSV Importer to latest versions for security updates.', 'wp-ultimate-exporter'); ?></p>
					<p><a href="<?php //echo admin_url('plugins.php'); ?>" class="button"><?php //_e('Upgrade Now', 'wp-ultimate-exporter'); ?></a></p>
					<button type="submit"class="notice-dismiss" id="upgrade_notices_csv_bro">
						<span class="screen-reader-text">Dismiss this notice.</span>
					</button>
				</div> -->
				<?php	
			}
		}
	}
	// public static function is_upgrade_notice_dismissed() {
	// 	return get_option('csv_update_option', false);
	// }
	public static function getInstance() {
		if (ExpCSVHandler::$instance == null) {
			ExpCSVHandler::$instance = new ExpCSVHandler;	
			ExpCSVHandler::$install = ExpInstall::getInstance();
			ExpCSVHandler::$exp_instance = ExportExtension::getInstance();
			
			return ExpCSVHandler::$instance;
		}
		return ExpCSVHandler::$instance;
	}	
	/**
	 * Init UserSmCSVHandlerPro when WordPress Initialises.
	 */
	public function init() {
		if(is_admin()) {
			// Init action.
			do_action('uci_init');
		}
	}
}
}

add_action( 'plugins_loaded', 'Smackcoders\\SMEXP\\onpluginsload' );


function onpluginsload(){
	$plugin_pages = ['com.smackcoders.csvimporternew.menu'];
	include __DIR__ . '/wp-exp-hooks.php';
	global $plugin_ajax_hooks;

	$request_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	$request_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	if (in_array($request_page, $plugin_pages) || in_array($request_action, $plugin_ajax_hooks)) {
		$plugin = ExpCSVHandler::getInstance();						
	}
	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	// if(!is_dir($upload_dir)){
	// 	return false;
	// }
	// else{
	// 	$exports_dir = $upload['basedir'] . '/smack_uci_uploads/exports/';
	// 	$htaccess_content = "deny from all\n";
	// 	$htaccess_file = $exports_dir . '.htaccess';
	// 	if (!file_exists($htaccess_file)) {
	// 		file_put_contents($htaccess_file, $htaccess_content);
	// 	}
	// }	
}

function Notice_msg_exporter_free() {
	
?>
				<div class="notice notice-warning is-dismissible" >
				<p> WP Ultimate Exporter is an addon of <a href="https://wordpress.org/plugins/wp-ultimate-csv-importer" target="blank" style="cursor: pointer;text-decoration:none">WP Ultimate CSV Importer</a> plugin, kindly install it to continue using WP ultimate exporter. </p>
				</div>
<?php 
	
}?>
