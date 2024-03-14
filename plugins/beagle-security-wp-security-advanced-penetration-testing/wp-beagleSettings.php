<?php

/**
 * @link              https://beaglesecurity.com/
 * @since             1.0.8
 * @package           Beagle Security
 *
 * @wordpress-plugin
 * Plugin Name:       Beagle Security - WP Security, Advanced Penetration Testing
 * Plugin URI:        https://beaglesecurity.com/wordpress-security-testing
 * Description:       Secure your WordPress website from the latest vulnerabilities with automated in-depth penetration testing.
 * Version:           1.0.8
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Beagle Security 
 * Author URI:        https://beaglesecurity.com/
 * License:           GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       Beagle wordpress scan
 * Domain Path:       /languages
 */

/*
Beagle Security - WP Security, Advanced Penetration Testing, 2021 Beagle Security
This plugin is distributed under the terms of the GNU GPL

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; If not, see {License URI}.
*/

/* Basic Securtiy*/
defined('ABSPATH') or die("No Access");


if (!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__), '/');
}

class Beagle_WordPress_Scan
{

	function __construct()
	{
		add_action('init', array($this, 'Beagle_WP_table_create'));
	}

	// for plugin activate
	function activate_Beagle_plugin()
	{	
		flush_rewrite_rules();
	}

	// for plugin deactivate
	function deactivate_Beagle_plugin()
	{
		global $wpdb;

		$Beagle_WP_scan_table = $wpdb->prefix . 'beagleScanData';

		$sql = "DROP TABLE IF EXISTS $Beagle_WP_scan_table";
		$wpdb->query($sql);

		delete_option("my_plugin_db_version");

		flush_rewrite_rules();
	}

	function Beagle_WP_table_create()
	{
		flush_rewrite_rules();
        global $wpdb;

        $Beagle_WP_scan_table = $wpdb->prefix . "beagleScanData";

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $Beagle_WP_scan_table( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, access_token VARCHAR(50) NOT NULL, application_token VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, result_token VARCHAR(50), verified BOOLEAN NOT NULL,title VARCHAR(20),url VARCHAR(50),runningStatus VARCHAR(10), autoVerify BOOLEAN)DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");
        dbDelta($sql);
	}

}

if (class_exists('Beagle_WordPress_Scan')) {

	$beagleWPscanStart = new Beagle_WordPress_Scan();

	// activation hook
	register_deactivation_hook(__FILE__, array($beagleWPscanStart, 'activate_Beagle_plugin'));

	// deactivation hook
	register_deactivation_hook(__FILE__, array($beagleWPscanStart, 'deactivate_Beagle_plugin'));
}

add_action('admin_menu', 'Beagle_Menu_Page');

/*Adding submenu page of plugin to the main menu*/
function Beagle_Menu_Page()
{
	add_menu_page('Beagle Plugin', 'Beagle Security', 'manage_options', 'Beagle_Plugin', 'Beagle_WP_Page_Content', 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2IiBoZWlnaHQ9IjE4IiB2aWV3Qm94PSIwIDAgMTYgMTgiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE2IDE4IiB4bWw6c3BhY2U9InByZXNlcnZlIj4gIDxpbWFnZSBpZD0iaW1hZ2UwIiB3aWR0aD0iMTYiIGhlaWdodD0iMTgiIHg9IjAiIHk9IjAiIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQkFBQUFBU0NBUUFBQUQ0TXBiaEFBQUFCR2RCVFVFQUFMR1BDL3hoQlFBQUFDQmpTRkpOIEFBQjZKZ0FBZ0lRQUFQb0FBQUNBNkFBQWRUQUFBT3BnQUFBNm1BQUFGM0NjdWxFOEFBQUFBbUpMUjBRQS80ZVB6TDhBQUFBSmNFaFogY3dBQUxFb0FBQ3hLQVhkNmRFMEFBQUFIZEVsTlJRZmxBZ1lMTGhvdnhqT2FBQUFCWlVsRVFWUW96MlhRTVV2VkFSaEc4ZC8vZWlXaiBEREZNc1lLa1JOU2kybHFpV1FLaENPb2pKRG0xRkxRVlJOUVUxQllFUWREUUlFUXVJVVFOaWxRUWtxRmxpU0Jva2c2Smx4THYwMkoyIG83T2U4L0xDVXdSbzB1T1lSdXZLWnJ3elp3TUtSYmJwZEZpelJSL00rcWxGdDA2RkdST1dDa1VHclpqdzJacS9GTnIxNnZhaW1DeXIgOXhoMWVqUnQ2bFZmelpzMzc2akpzaldVRExxaWNUTlk5TWdUMHlwUUJxZGMwN0wxSUpyMStXNkRLS2xxYzZORy8zSlhwNm96dW15aiB5SUQ5cmlwcTdqOXBOK0syYmwwaXR6S1YvNm5rYktRakl5Vzc3VlRMRHd0b2NFNmRXY01scmI3OEU2d2J0b3J0NHBCcVNhOHBIMnVDIFpoMUdWVHkxVjUvbkplLzFHL05hZFNzNVljNjRIYTRiTjExeVI4VUZTNGEyeHE0MzVySmxiN3hGaXB6UHR5U3Y4akFMU1pKbjJaWGUgWE1xZW9BaUYwMjQ2WXRwTHJaYmQxMmFmSVl2Rm4yVWlCM012SzFuSXlSelBRUHJTRUZFelhxUSsvUm5OZzF6TWdWcjVHMzRucUZHTyBSOWpIQUFBQUpYUkZXSFJrWVhSbE9tTnlaV0YwWlFBeU1ESXhMVEF5TFRBMlZERXhPalEyT2pJMkt6QXlPakF3U2pPczNRQUFBQ1YwIFJWaDBaR0YwWlRwdGIyUnBabmtBTWpBeU1TMHdNaTB3TmxReE1UbzBOam95Tmlzd01qb3dNRHR1RkdFQUFBQUFTVVZPUks1Q1lJST0iLz4KPC9zdmc+');
}


/*to declare the apis globally*/
global $apiServerBaseUrl;
$apiServerBaseUrl = "https://api.beaglesecurity.com/v1/";


/*calling the option menu to enter access token and application token and to submit it*/
require plugin_dir_path(__FILE__) . 'Admin/optionPageContent.php';

/*hooks the post method to start the test when the user clicks 'start test' button*/
if (isset($_POST['startBeagleTest'])) {
	$_POST = array();
	add_action('admin_init', 'Beagle_WP_start_Test');
}

/*hooks the post method to stop the test when the user clicks 'stop test' button*/
if (isset($_POST['stopBeagleTest'])) {
	add_action('admin_init', 'Beagle_WP_stop_Test');
}

/*hooks the post method to restart the test when the user clicks 'start test' button after pressing stop button*/
if (isset($_POST['restartBeagleTest'])) {
	add_action('admin_init', 'Beagle_WP_stop_Test');
}

/*erases the data in database when the user wants to delete the test details*/

if (isset($_POST['delete'])) {
	add_action('admin_init', 'Beagle_WP_delete_Test');
}

/*hooks the post method to verify the domain*/
if (isset($_POST['startVerify'])) {
	add_action('admin_init', 'Beagle_WP_addDataTo_DB');
}

if (isset($_POST['verify'])) {
	add_action('admin_init', 'Beagle_WP_verify_Token');
}

/*calling startTest.php file to begin test*/
require plugin_dir_path(__FILE__) . 'Admin/startTest.php';

/*calling stopTest.php file to stop test*/
require plugin_dir_path(__FILE__) . 'Admin/stopTest.php';

/*calling deleteTest.php file to delete the data of test*/
require plugin_dir_path(__FILE__) . 'Admin/deleteTest.php';

/*calling insertInToTable.php file to add data to db*/
require plugin_dir_path(__FILE__) . 'Admin/insertInToTable.php';

/*calling getStatus.php file to get status of test*/
require  plugin_dir_path(__FILE__) . 'Admin/getStatus.php';

/*calling getResultData.php file to get result of test*/
require  plugin_dir_path(__FILE__) . 'Admin/getResultData.php';

/*calling verify.php */
require  plugin_dir_path(__FILE__) . 'Admin/verifyToken.php';

/*calling updateVerify.php */
require  plugin_dir_path(__FILE__) . 'Admin/updateVerify.php';

/*calling updateVerifyFailed.php */
require  plugin_dir_path(__FILE__) . 'Admin/autoVerifyFailed.php';

// for status data
add_action('wp_ajax_t4a_ajax_call', 'Beagle_WP_getStatusOf_CurrentTestData');

// for result data
add_action('wp_ajax_t4a_ajax_call_result', 'Beagle_WP_getResultOf_CurrentTestData');

// for delete application
add_action('wp_ajax_t4a_ajax_call_delete', 'Beagle_WP_delete_Test');

// for verify application
add_action('wp_ajax_t4a_ajax_call_verify', 'Beagle_WP_verify_Token');

// for update verify application
add_action('wp_ajax_t4a_ajax_call_verify_update', 'Beagle_WP_verify_Token_Update');

// for update verify failed condition 
add_action('wp_ajax_t4a_ajax_call_verify_update_failed', 'Beagle_WP_auto_Verify');
