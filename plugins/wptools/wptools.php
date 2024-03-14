<?php
/*
Plugin Name: wptools
Plugin URI:  https://BillMinozzi.com
Description: WP Tools Increase PHP memory limit, time limit, max upload file size limit without editing any files.Show PHP info, PHP and Javascript errors, Server info and more tools. 
Version:     4.29
Author:      Bill Minozzi
Plugin URI:  https://BillMinozzi.com
Domain Path: /language
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
// meta description(?) for page or posts
*/

if (!defined('ABSPATH')) {
	die('We\'re sorry, but you can not directly access this file.');
}
if (!ini_get('error_log') or empty(trim(ini_get('error_log'))));
   @ini_set('error_log', ABSPATH . 'error_log'); // path to server-writable log file

/*
// error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
 error_reporting(E_ERROR | E_WARNING | E_PARSE);
*/

/*
debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

$wptools_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$wptools_plugin_version = $wptools_plugin_data['Version'];
define('WPTOOLSVERSION', $wptools_plugin_version);
define('WPTOOLSHOMEURL', admin_url());
define('WPTOOLSPATH', plugin_dir_path(__file__));
define('WPTOOLSURL', plugin_dir_url(__file__));
define('WPTOOLSIMAGES', plugin_dir_url(__file__) . 'images');
define('WPTOOLSADMURL', admin_url());
require_once WPTOOLSPATH . 'functions/bill-catch-errors.php';

if(is_admin())
  add_action('plugins_loaded', 'wptools_localization_init');
$wptools_request_url = trim(sanitize_text_field($_SERVER['REQUEST_URI']));
$wptools_bypass_wpdebug =  trim(sanitize_text_field(get_option('wptools_bypass_wpdebug', 'no')));
if (!defined('WP_DEBUG'))
	define('WP_DEBUG', false);
if ($wptools_bypass_wpdebug == 'yes' and WP_DEBUG == false) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}
$wptools_radio_server_load =  trim(sanitize_text_field(get_option('wptools_radio_server_load', 'yes')));
$wptools_radio_server_load = strtolower($wptools_radio_server_load);
$wptools_disable_lazy =  trim(sanitize_text_field(get_option('wptools_disable_lazy', 'yes')));
$wptools_disable_emojis =  trim(sanitize_text_field(get_option('wptools_disable_emojis', 'yes')));
$wptools_show_pageload_info =  trim(sanitize_text_field(get_option('wptools_show_pageload_info', 'yes')));
$wptools_classic_widget =  trim(sanitize_text_field(get_option('wptools_classic_widget', 'no')));
$wptools_show_adminbar =  trim(sanitize_text_field(get_option('wptools_show_adminbar', 'no')));
$wptools_vote =  trim(sanitize_text_field(get_option('wptools_vote', '')));
$wptools_checkversion =  trim(sanitize_text_field(get_option('wptools_checkversion','')));
if (!empty($wptools_checkversion))
  add_action('plugins_loaded', 'wptools_update');
$wptools_show_errors =  trim(sanitize_text_field(get_option('wptools_show_errors', 'yes')));
$wptools_logo =  trim(sanitize_text_field(get_option('wptools_logo', '')));
$wptools_erase_readme =  trim(sanitize_text_field(get_option('wptools_erase_readme', '')));
$wptools_remove_icon =  trim(sanitize_text_field(get_option('wptools_remove_icon', '')));
$wptools_logo_width =  trim(sanitize_text_field(get_option('wptools_logo_width', '')));
$wptools_logo_height =  trim(sanitize_text_field(get_option('wptools_logo_height', '')));
$wptools_jquery_version = trim(sanitize_text_field(get_option('wptools_jquery_version', 'default')));
$wptools_disable_selfping  = trim(sanitize_text_field(get_option('wptools_disable_selfping', '')));
$wptools_auto_updates = trim(sanitize_text_field(get_option('wptools_auto_updates', 'yes')));
$wptools_activated_notice =  trim(sanitize_text_field(get_option('wptools_activated_notice', '0')));

$wptools_was_activated =  trim(sanitize_text_field(get_option('wptools_was_activated', '0')));

$wptools_radio_email_weekly_error_notification = trim(sanitize_text_field(get_option('wptools_radio_email_weekly_error_notification', 'yes')));

$wptools_radio_email_weekly_load_notification = trim(sanitize_text_field(get_option('wptools_radio_email_weekly_load_notification', 'yes')));

$wptools_disable_ziparchive = trim(sanitize_text_field(get_option('wptools_disable_ziparchive', 'no')));


// error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATE
// production: E_ALL & ~E_DEPRECATED & ~E_STRICT. 
// This value prevented deprecation notice and strict notices from making it to error logs or screen. 


if (!function_exists('wp_get_current_user')) {
    require_once(ABSPATH . "wp-includes/pluggable.php");
}
if(is_admin()) {
	add_action('admin_menu', 'wptools_menu');
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wptools_add_action_links22');
	function wptools_add_action_links22($links)
	{
		$mylinks = array(
			'<a href="' . admin_url('admin.php?page=wp-tools') . '">Settings</a>',
		);
		return array_merge($links, $mylinks);
	}
}
function wptools_login_logo()
{
	global $wptools_logo;
	global $wptools_logo_width;
	global $wptools_logo_height;
?>
	<style type="text/css">
		#login h1 a,
		.login h1 a {
			background-image: url('<?php echo esc_url($wptools_logo); ?>') !important;
			width: <?php echo esc_attr($wptools_logo_width); ?>px !important;
			height: <?php echo esc_attr($wptools_logo_height); ?>px !important;
			background-repeat: no-repeat;
			background-size: <?php echo esc_attr($wptools_logo_width); ?>px <?php echo esc_attr($wptools_logo_height); ?>px;
			//padding-bottom: 30px;
		}
	</style>
<?php }
if (!empty($wptools_logo) and !empty($wptools_logo_width) and !empty($wptools_logo_height)) {
	add_action('login_enqueue_scripts', 'wptools_login_logo');
}
function wptools_admin_bar_remove_logo()
{
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}
if ($wptools_remove_icon == 'yes' and is_admin())
	add_action('wp_before_admin_bar_render', 'wptools_admin_bar_remove_logo', 0);
if ($wptools_erase_readme == 'yes' and is_admin())
	wptools_remove_readme();
function wptools_remove_readme()
{
	$file = ABSPATH . 'readme.html';
	if (file_exists($file)) {
		if (!unlink($file))
			chmod($file, 755);
		if (file_exists($file)){
			if (!unlink($file)) {
				add_action('admin_notices', 'wptools_alert_notice');
			}
	    }
	}
	$file = ABSPATH . 'license.txt';
	if (file_exists($file)) {
		if (!unlink($file))
			chmod($file, 755);
		if (file_exists($file)){
			if (!unlink($file)) {
				add_action('admin_notices', 'wptools_alert_notice');
			}
	    }
	}
}
function wptools_alert_notice()
{
?>
	<div class="notice-warning">
		<p><?php _e('Unable to remove file read.me and/or license.txt. Talk with your hosting company.', 'wptools'); ?></p>
	</div>
	<?php
}
$wptools_email_to =  trim(sanitize_text_field(get_option('wptools_email_to', '')));
$wptools_radio_email_error_notification =  trim(sanitize_text_field(get_option('wptools_radio_email_error_notification', 'no')));
if ($wptools_radio_email_error_notification == 'yes' or $wptools_bypass_wpdebug == 'yes') {
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	error_reporting(E_ALL);
	$old_error_handler = set_error_handler("wptoolsErrorHandler");
}
// ini_set("memory_limit","128M");
function wptools_general_admin_notice($msg)
{
	if (is_admin() and !empty($msg)) {
		echo '<div class="notice notice-warning is-dismissible">
			 <p>' . esc_attr($msg) . '</p>
		     </div>';
	}
}

require_once(WPTOOLSPATH . "functions/functions.php");


if (!function_exists('ini_set')) {
	function wptools_general_admin_notice1()
	{
		if (is_admin()) {
			echo '<div class="notice notice-warning is-dismissible">
				 <p>'.esc_attr("Your server doesn\'t have a PHP function ini_set.","wptools").'</p>
				 <p>'.esc_attr("Please, talk with your hosting company.","wptools").'</p>
			 </div>';
		}
	}
	add_action('admin_notices', 'wptools_general_admin_notice');
}
if (!function_exists('ini_get')) {
	function wptools_general_admin_notice2()
	{
		if (is_admin()) {
			echo '<div class="notice notice-warning is-dismissible">
				 <p>'.esc_attr("Your server doesn\'t have a PHP function ini_get.","wptools").'</p>
				 <p>'.esc_attr("Please, talk with your hosting company.","wptools").'</p>
			 </div>';
		}
	}
	add_action('admin_notices', 'wptools_general_admin_notice');
}
// memory
$wptools_memory_limit = (int) get_option('wptools_memory_limit', '0');
if ($wptools_memory_limit > 0 and $wptools_memory_limit <= 512) {
	if ($wptools_memory_limit > wptools_get_limit())
		if (!wptools_set_limit($wptools_memory_limit)) {
			wptools_general_admin_notice('wptools: Fail to set new memory limit!');
		}
}
// time
$wptools_time_limit = get_option('wptools_time_limit', '');
if ($wptools_time_limit > 0 and $wptools_time_limit <= 360) {
	if ($wptools_memory_limit > wptools_current_time_limit())
		if (!wptools_set_time_limit($wptools_time_limit)) {
			wptools_general_admin_notice('wptools: Fail to set new time limit!');
		}
}
//Size upload
/*
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
*/
$wptools_max_filesize = (int) get_option('wptools_max_filesize', '');
$wptools_max_filesize = $wptools_max_filesize * (1024 * 1024);
if ($wptools_max_filesize > 0) {
	// and $wptools_max_filesize <= 26214400
	if ($wptools_max_filesize > wptools_current_upload_max_filesize()) {
		// var_dump(wptools_set_upload_max_filesize());
		if (!add_filter('upload_size_limit', 'wptools_set_upload_max_filesize', 20)) {
			wptools_general_admin_notice('wptools: Fail to set new upload file limit!');
		}
	}
}
/////////////////////////////////////////
// require_once(WPTOOLSPATH . "functions/functions.php");
if (is_admin()) {
    require_once(WPTOOLSPATH . 'includes/help/help.php');
	add_action('setup_theme', 'wptools_load_settings');
	function wptools_load_settings() {
		require_once(WPTOOLSPATH . "settings/load-plugin.php");
		require_once(WPTOOLSPATH . "settings/options/plugin_options_tabbed.php");
	}
	$plugin = plugin_basename(__FILE__);
	function wptools_add_action_links($links)
	{
		$mylinks = array(
			'<a href="' . admin_url('admin.php?page=settings-wptools') . '">Settings44</a>',
		);
		return array_merge($links, $mylinks);
	}
}

add_action('wp_head', 'wptools_ajaxurl');
function wptools_ajaxurl()
{
	echo '<script type="text/javascript">
	   var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	 </script>';
}

if (is_admin()) {
	/*
	add_action('wp_head', 'wptools_ajaxurl');
	function wptools_ajaxurl()
	{
		echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
	}
	*/
	add_action('wp_ajax_wptools_get_ajax_data', 'wptools_get_ajax_data');
	add_action('wp_ajax_wptools_get_js_errors', 'wptools_get_js_errors');
	add_action('wp_ajax_wptools_get_speed_info', 'wptools_get_speed_info');

	function wptools_alert_errors()
	{
		global $wp_admin_bar;
		//global $wptools_radio_server_load;
		$site = WPTOOLSHOMEURL . "admin.php?page=wptools_options21";
		$args = array(
			'id' => 'wptools-alert',
			'title' => '<div class="wptools-alert-logo"></div><span id="wptools_alert_errors" class="text">'. esc_attr__("Site Errors","wptools").'</td>',
			'href' => $site,
			'meta' => array(
				'class' => 'wptools-alert',
				'title' => ''
			)
		);
		$wp_admin_bar->add_node($args);
		echo '<style>';
		if (wptools_errors_today(1)) {
			echo '#wpadminbar .wptools-alert  {
			 background: red !important; */
				color: white !important;
				width: 120px;
				}';
		} else {
			echo '#wpadminbar .wptools-alert  {
				/* background: green !important; */
				color: white !important;
				width: 120px;
				}';
		}
		$logourl = WPTOOLSIMAGES . "/bell.png";
		echo '#wpadminbar .wptools-alert-logo  {
			background-image: url("' . esc_url($logourl) . '");
			float: left;
			width: 26px;
			height: 30px;
			background-repeat: no-repeat;
			background-position: 0 6px;
			background-size: 20px;
			}';
		echo '</style>';
	}
	function wptools_custom_toolbar_link($wp_admin_bar)
	{
		global $wp_admin_bar;
		global $wptools_radio_server_load;
		$site = WPTOOLSHOMEURL . "admin.php?page=wp-tools";
		$args = array(
			'id' => 'wptools',
			'title' => '<div class="wptools-logo"></div><span id="wptools_proc_load" class="text">wait</span>',
			'href' => $site,
			'meta' => array(
				'class' => 'wptools',
				'title' => ''
			)
		);
		$wp_admin_bar->add_node($args);
		echo '<style>';
		echo '#wpadminbar .wptools  {
			/* background: red !important; */
			color: black !important;
			width: 80px;
			}';
		$logourl = WPTOOLSIMAGES . "/processorx30x35.png";
		echo '#wpadminbar .wptools-logo  {
			background-image: url("' . esc_url($logourl) . '");
			float: left;
			width: 26px;
			height: 30px;
			background-repeat: no-repeat;
			background-position: 0 6px;
			background-size: 20px;
			}';
		echo '</style>';
	}
	function wptools_get_js_errors()
	{
		if (isset($_REQUEST)) {
			if (!isset($_REQUEST['wptools_js_error_catched']))
				die("empty error");
			if (!wp_verify_nonce($_POST['_wpnonce'], 'jquery-wptools')) {
				status_header(406, 'Invalid nonce');
				die();
			}
			$wptools_js_error_catched = sanitize_text_field($_REQUEST['wptools_js_error_catched']);
			$wptools_js_error_catched = trim($wptools_js_error_catched);
			if (!empty($wptools_js_error_catched)) {
				$txt = 'Javascript ' . $wptools_js_error_catched;
				error_log($txt);
				// send email
				wptools_php_error($txt);
				die('OK!!!');
			}
		}
		die('NOT OK!');
	}
	function wptools_get_ajax_data()
	{
		try {
			// LOAD AVERAGES 
			if (function_exists('sys_getloadavg')) {
				$loadavg = sys_getloadavg();
				if(gettype($loadavg) !== 'array' or count($loadavg) < 3)
				wp_die();
				$load_1 = number_format($loadavg[0], 2);
				$load_5 = number_format($loadavg[1], 2);
				$load_15 = number_format($loadavg[2], 2);
			} else {
				$load_1 = 0;
				$load_5 = 0;
				$load_15 = 0;
			}
		} catch (Exception $e) {
			$load_1 = 0;
			$load_5 = 0;
			$load_15 = 0;
		}


		try {
			// NUMBER OF CORES 
			if(wptools_check_if_obd_permitted() and is_readable("/proc/cpuinfo")) {
					preg_match_all('/^processor/m', @file_get_contents('/proc/cpuinfo'), $cores);
					$cores = count($cores[0]);
	     	}
			else {

                if(wptools_exec_enabled()   ) {
					$cores = @shell_exec('nproc');
				}
				else
				  $cores = 0;
			}

		} catch (Exception $e) {
			$cores = 0;
		}
		try {
			// CPU INFO 
			if(wptools_check_if_obd_permitted() and is_readable('/proc/stat')) {
					$proc_stat = file('/proc/stat'); //read file into array, split by lines
					$proc_stat_cpu = preg_split('/\ +/', $proc_stat[0]); //read 1st line of file, and split into array by spaces. The first line is the aggregate of all cores
					if(gettype($proc_stat_cpu) !== 'array' or count($proc_stat_cpu) < 8){
						
						$proc_stat_cpu['total'] = '';
						$proc_stat_cpu['usage'] = 0;

					}
					$proc_stat_cpu['total'] = $proc_stat_cpu[1] + $proc_stat_cpu[2] + $proc_stat_cpu[3] + $proc_stat_cpu[4] + $proc_stat_cpu[5] + $proc_stat_cpu[6] + $proc_stat_cpu[7]; //100% of the cpu time
					$proc_stat_cpu['usage'] = $proc_stat_cpu[1] + $proc_stat_cpu[2] + $proc_stat_cpu[3] + $proc_stat_cpu[5] + $proc_stat_cpu[6] + $proc_stat_cpu[7]; //usage = total skipping idle
	    	}
			else {

					$proc_stat_cpu['total'] = '';
					$proc_stat_cpu['usage'] = 0;
   
			}
			
		} catch (Exception $e) {
			$proc_stat_cpu['total'] = '';
			$proc_stat_cpu['usage'] = 0;
		}


		$monitor = array('load_1' => $load_1, 'load_5' => $load_5, 'load_15' => $load_15, 'cores' => $cores, 'proc_stat_cpu_total' => $proc_stat_cpu['total'], 'proc_stat_cpu_usage' => $proc_stat_cpu['usage']);
		echo json_encode($monitor); //the output
		wp_die();
	}
	function wptools_add_admstylesheet()
	{
		global $wptools_radio_server_load;
		global $wptools_tab;

		wp_enqueue_script("jquery");
		if (isset($_GET['tab']))  
		$wptools_tab = sanitize_text_field($_GET['tab']);
		else {
			if(bill_check_resources(false))
			   $wptools_tab = 'requirements';
			else
		    	$wptools_tab = 'dashboard';
		}
		if (isset($_GET['page']))  
			$page = sanitize_text_field($_GET['page']);
		else
			$page = '';
		if (($page == 'wptools_options31' or $page == 'wp-tools')  and $wptools_tab == 'dashboard') {
			wp_enqueue_script('wptools-smoothiejs', WPTOOLSURL . 'js/smoothie.min.js', array('jquery'), WPTOOLSVERSION, true);
			wp_enqueue_script('jquery');
			wp_enqueue_script('wptah-flot', WPTOOLSURL .
				'js/jquery.flot.min.js', array('jquery'));
			wp_enqueue_script('wptflotpie', WPTOOLSURL .
				'js/jquery.flot.pie.js', array('jquery'));

			wp_enqueue_script('wptcircle', WPTOOLSURL .
				'js/radialIndicator.js', array('jquery'));

			wp_enqueue_script('wptgauge', WPTOOLSURL .
				'js/speedometer.js', array('jquery'));

			//var_dump(WPTOOLSURL .'js/jquery-gauge.min.js');

			 wp_enqueue_style('wpt-jquery-gauge-css', WPTOOLSURL .
				'css/speedometer.css');



			
		}

		if (isset($_GET['page']))  // {
			$page = sanitize_text_field($_GET['page']);
		else
			$page = '';
		if ($wptools_radio_server_load == 'yes' or $page == 'wptools_options31' or $page == 'wp-tools' ) {
			//$pos = stripos(PHP_OS_FAMILY, 'linux');
			//if ($pos !== false) {
				wp_register_script("wptools-processor", WPTOOLSURL . 'js/processor.js', array('jquery'), WPTOOLSVERSION, true);
				wp_enqueue_script('wptools-processor');
			//}
		}
		if ($wptools_radio_server_load == 'yes') {
			if(defined('PHP_OS_FAMILY'))
			   $pos = stripos(PHP_OS_FAMILY, 'linux');
		    else
			    $pos = false;

			if ($pos !== false) {
				wp_register_script("wptools-processor", WPTOOLSURL . 'js/processor.js', array('jquery'), WPTOOLSVERSION, true);
				wp_enqueue_script('wptools-processor');
			}
		}
		if (wptools_errors_today(1)) {
			wp_register_script("wptools-alert-errors", WPTOOLSURL . 'js/alert_errors.js', array('jquery'), WPTOOLSVERSION, true);
			wp_enqueue_script('wptools-alert-errors');
		}
		wp_enqueue_script(
			'wptools-scripts-js',
			plugin_dir_url(__FILE__) . 'js/wptools_scripts.js'
		);
	}
	add_action('admin_enqueue_scripts', 'wptools_add_admstylesheet', 1000);
	if ($wptools_radio_server_load == 'yes')
		add_action('admin_bar_menu', 'wptools_custom_toolbar_link', 999);
	if ($wptools_show_errors != 'no'){
		add_action('admin_bar_menu', 'wptools_alert_errors', 999);
	}
}
$wptools_disable_sitemap =  trim(sanitize_text_field(get_option('wptools_disable_sitemap', 'no')));
$wptools_disable_sitemap = strtolower($wptools_disable_sitemap);
$wptools_disable_updates_notifications =  trim(sanitize_text_field(get_option('wptools_disable_updates_notifications', 'no')));
$wptools_disable_updates_notifications = strtolower($wptools_disable_updates_notifications);

$wptools_add_google_webmaster =  trim(get_option('wptools_add_google_webmaster', ''));

$wptools_add_bing_webmaster_metaname =  sanitize_text_field(trim(get_option('wptools_add_bing_webmaster_metaname', '')));

$wptools_add_bing_webmaster_content =  sanitize_text_field(trim(get_option('wptools_add_bing_webmaster_content', '')));



	



$wptools_add_analitics =  trim(sanitize_text_field(get_option('wptools_add_analitics', '')));
$wptools_alert_debug =  trim(sanitize_text_field(get_option('wptools_alert_debug', 'no')));
$wptools_alert_debug = strtolower($wptools_alert_debug);
$wptools_hide_admin_bar =  trim(sanitize_text_field(get_option('wptools_hide_admin_bar', 'no')));
$wptools_hide_admin_bar = strtolower($wptools_hide_admin_bar);
if ($wptools_disable_updates_notifications == 'yes') {
	add_filter('auto_core_update_send_email', 'wptools_stop_auto_update_emails', 10, 4);
	add_filter('auto_plugin_update_send_email', '__return_false');
	add_filter('auto_theme_update_send_email', '__return_false');
}
if ($wptools_disable_lazy == 'yes')
	add_filter('wp_lazy_loading_enabled', '__return_false');
if ($wptools_classic_widget == 'yes') {
	// Disables the block editor from managing widgets in the Gutenberg plugin.
	add_filter('gutenberg_use_widgets_block_editor', '__return_false');
	// Disables the block editor from managing widgets.
	add_filter('use_widgets_block_editor', '__return_false');
}
function wptools_stop_auto_update_emails($send, $type, $core_update, $result)
{
	if (!empty($type) && $type == 'success') {
		return false;
	}
	return true;
}
//disable sitemap
function wptools_disable_sitemap_main()
{
	add_filter('wp_sitemaps_enabled', '__return_false');
}
if ($wptools_disable_sitemap == 'yes')
	add_action('init', 'wptools_disable_sitemap_main');
if ($wptools_disable_sitemap == 'users') {
	add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
		return ($name == 'users') ? false : $provider;
	}, 10, 2);
}
function wptools_webmaster_tools()
{
	global $wptools_add_google_webmaster;
	global $wptools_add_bing_webmaster_metaname;
	global $wptools_add_bing_webmaster_content;

	if(!empty($wptools_add_google_webmaster)){
	  $wptools_add_google_webmaster =  str_replace('"', '', $wptools_add_google_webmaster);
	  echo '<meta name="google-site-verification" content="'.esc_attr($wptools_add_google_webmaster).'" />';
	}
	if(!empty($wptools_add_bing_webmaster_metaname))
    	echo '<meta name="'.esc_attr($wptools_add_bing_webmaster_metaname).'" content="'.esc_attr($wptools_add_bing_webmaster_content).'"  />';


}

if (!is_admin()) {
	add_action('wp_head', 'wptools_webmaster_tools');
}


function wptools_add_analytics()
{
	global $wptools_add_analitics;
	// Global Site Tag / gtag.js (new method)
	// https://developers.google.com/analytics/devguides/collection/gtagjs/Ffile
	if( substr($wptools_add_analitics,0,2) == 'G-'){
	echo "<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-HCPLTMPVCT\"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '". $wptools_add_analitics . "');
	</script>" . PHP_EOL;
    }
	else{
	echo "<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		ga('create', '" . $wptools_add_analitics . "', 'auto');
		ga('send', 'pageview');
	</script>" . PHP_EOL;
	}
}
if (!empty($wptools_add_analitics))
	add_action('wp_footer', 'wptools_add_analytics');

if ($wptools_alert_debug == 'yes') {
	// define( 'WP_DEBUG', true );
	if (defined('WP_DEBUG')) {
		if (WP_DEBUG) {
			add_action('admin_bar_menu', 'wptools_custom_toolbar_debug', 999);
		}
	}
}
function wptools_custom_toolbar_debug($wp_admin_bar)
{

	global $wp_admin_bar;
	$site = WPTOOLSHOMEURL . "admin.php?page=settings-wptools&tab=general_settings";
	$args = array(
		'id' => 'wptools_debug_active',
		'title' => '<span id="wptools_debug_active" class="text">WP Debug Active</span>',
		'href' => $site,
		'meta' => array(
			'class' => 'wptools_debug_active',
			'title' => ''
		)
	);
	$wp_admin_bar->add_node($args);
	echo '<style>';
	echo '#wpadminbar .wptools_debug_active  {
		background: red !important; 
		color: black !important;
		width: 119px;
		}';
	echo '</style>';
}
if ($wptools_disable_emojis == 'yes')
	add_action('init', 'wptools_emojis_disable');
function wptools_emojis_disable()
{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'wptools_emojis_disable_tinymce');
	add_filter('wp_resource_hints', 'wptools_emojis_disable_remove_dns_prefetch', 10, 2);
}
function wptools_emojis_disable_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	}
	return array();
}
function wptools_emojis_disable_remove_dns_prefetch($urls, $relation_type)
{
	if ('dns-prefetch' == $relation_type) {
		$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ($urls as $key => $url) {
			if (strpos($url, $emoji_svg_url_bit) !== false) {
				unset($urls[$key]);
			}
		}
	}
	return $urls;
}
//
function wptools_mysql_queries()
{
	// echo '<!-- ';
	$r = __('Page Load Info: ','wptools');
	$r .= ' '.get_num_queries();
	$r .= ' '.__('queries and loaded in','wptools');
	$r .= ' '.timer_stop(0, 1);
	$r .= ' '.__('seconds','wptools');
	// echo ' -->';
	return $r;
}
if (is_admin() and $wptools_show_pageload_info == 'yes')
	add_filter('admin_footer_text', 'wptools_mysql_queries');
function wptools_php_error($txt)
{
	global $wptools_email_to;
	global $wptools_radio_email_error_notification;
	if ($wptools_radio_email_error_notification != 'yes')
		return;
		$wptools_transient_email = get_transient('wptools_transient_email');
		if(!$wptools_transient_email){
		   set_transient ('wptools_transient_email', 1, 60*5);
		   $wptools_transient_email = 1;  
		}
		else
		   set_transient ('wptools_transient_email', $wptools_transient_email+1);
		if($wptools_transient_email > 10)
		  return;
	if (empty($wptools_email_to))
		$wptools_email_to = sanitize_email(get_option('admin_email',''));
	$dt = date("Y-m-d H:i:s");
	$dom = sanitize_text_field($_SERVER['SERVER_NAME']);
	$msg =  __('This email was sent from your website', "wptools");
	$msg .= ': ' . $dom . ' ';
	$msg .=  __('by the wpTools plugin.', "wptools");
	$msg .= '<br> ';
	$msg .= __('Date', "wptools");
	$msg .= ': ' . $dt . '<br>';
	$msg .= '<br> ';
	$msg .= $txt;
	$msg .= '<br> ';
	$msg .= '<br> ';
	$msg .= __('You can stop emails at the Notifications Tab.', "wptools");
	$msg .= '<br>';
	$msg .= __('Dashboard => WP tools=> Settings', "wptools");
	$email_from = 'wordpress@' . $dom;

	/*
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: " . $wptools_email_to . "\r\n" . 'Reply-To: ' . $wptools_email_to . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	*/

	
	$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $wptools_email_to,
			'Reply-To: ' . $wptools_email_to,
		);

	$to = $wptools_email_to;
	$subject = __('Notification about errors and warnings at: ', "wptools") . ' ' . $dom;
	wp_mail($to, $subject, $msg, $headers, '');
	return;
}
function wptoolsErrorHandler($errno, $errstr, $errfile, $errline)
{
	$errstr = htmlspecialchars($errstr);
	$txt = '';
	switch ($errno) {
		case E_USER_ERROR:
			$txt .= "ERROR [$errno] $errstr\n";
			$txt .= "  Fatal error on line $errline in file $errfile";
		case E_USER_WARNING:
			$txt .= "WARNING [$errno] $errstr\n";
			$txt .= "  Warning on line $errline in file $errfile";
			break;
		case E_USER_NOTICE:
			$txt .= "NOTICE [$errno] $errstr\n";
			$txt .= "  Notice on line $errline in file $errfile";
			break;
		case E_NOTICE:
			$txt .= "NOTICE [$errno] $errstr\n";
			$txt .= "  Notice on line $errline in file $errfile";
			break;
		default:
			$txt .= "Unknown error type: [$errno] $errstr\n";
			$txt .= "  On line $errline in file $errfile";
			break;
	}
	// send email
	if ($errno != E_USER_ERROR)
		wptools_php_error($txt);
	if (defined('WP_DEBUG')) {
		return false; // show
	} else {
		error_log($txt);
		return true;  // hide
	}
}
function wptools_add_stylesheet()
{
	wp_enqueue_style('admin-css', WPTOOLSURL .
		'css/admin.css');
	wp_enqueue_style('help-css', WPTOOLSURL .
		'dashboard/css/help.css');

	
	wp_enqueue_style('admin-settings-css', WPTOOLSURL .
		'settings/styles/admin-settings.css');
		

}
if (is_admin())
	add_action('wp_loaded', 'wptools_add_stylesheet', 1000);
if (!is_admin() and $wptools_show_adminbar == 'yes') {
	// Yes == Disable...
	//var_dump($wptools_show_adminbar);
	function wptools_disable_admin_toolbar()
	{
		$wptools_custom_css = '
				.show-admin-bar {
					display: none;
				}';
		wp_add_inline_style('wptools-css2', $wptools_custom_css);
	}
	add_filter('show_admin_bar', '__return_false', 999);
	add_action('wp_enqueue_scripts', 'wptools_disable_admin_toolbar');
}
 if (is_admin())
	add_action('admin_menu', 'wptools_change_menu_label', 99);
function wptools_change_menu_label()
{
	global $menu;
	global $submenu;
	$submenu['wp-tools'][0][0] = esc_attr__('Dashboard', "wptools");
}
add_action('wp_head', function () {
	if (!current_user_can('administrator')) {
	?>
		<script type="text/javascript">
			console.log = function() {};
			console.log('ok2');
		</script>
	<?php
	}
}, 1);
function wptools_plugin_row_meta($links, $file)
{
	global $wptools_checkversion;
	if (strpos($file, 'wptools.php') !== false) {
		if (is_multisite()) 
		    $url = WPTOOLSHOMEURL . "plugin-install.php?s=sminozzi&tab=search&type=author";
     	else {
			if( empty($wptools_checkversion)) 
	    	   $url = "https://wptoolsplugin.com/premium/";
			else
			   $url = WPTOOLSHOMEURL . "admin.php?page=wptools_options39";
		    }
		if( empty($wptools_checkversion)) 
	    	$new_links['Pro'] = '<a href="' . $url . '" target="_blank"><b><font color="#FF6600">Go Pro</font></b></a>';
	    else
	    	$new_links['Pro'] = '<a href="' . $url . '" target="_blank"><b><font color="#FF6600">Click To see more plugins from same author</font></b></a>';
		$links = array_merge($links, $new_links);
	}
	return $links;
}

 add_filter('plugin_row_meta', 'wptools_plugin_row_meta', 10, 2);
 
 /*
if (!class_exists('wptools_catch_errors')) {
	include_once __DIR__ . '/functions/class-wptools-catch-errors.php';
	add_action('plugins_loaded', array('wptools_catch_errors', 'init_actions'));
}
*/

//require_once WPTOOLSPATH . 'functions/bill-catch-errors.php';



if (is_admin()) {

	// Pointer... 
	function wptools_load_pointer() {
		$pointers = get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
		$pointers = ''; // str_replace( 'plugins', '', $pointers );
		update_user_meta(get_current_user_id(), 'dismissed_wp_pointers', $pointers);
	}

	//add_action('wp_enqueue_scripts', function() {
	//	wp_enqueue_style('wpt-pointer', WPTOOLSURL . 'css/bill-wp-pointer.css');
	//});

	
	function wptools_load_pointer_css(){
		wp_enqueue_style('wpt-pointer', WPTOOLSURL . 'css/bill-wp-pointer.css');
	}


	
	
	
	$wptools_activated_pointer =  trim(sanitize_text_field(get_option('wptools_activated_pointer', '0')));
  

	if($wptools_activated_pointer == '1'){
		$BILLCLASS = 'ACTIVATED_WPTOOLS';

		if(isset($_COOKIE[$BILLCLASS])) {
			$bill_tempo_criacao = $_COOKIE[$BILLCLASS];
			$bill_intervalo = time() - $bill_tempo_criacao;
			// wait 30 sec...
			if ($bill_intervalo > 10) {
				//add_action( 'wp_enqueue_scripts', 'wptools_load_pointer_css' );
				add_action('admin_enqueue_scripts', 'wptools_adm_enqueue_scripts2');
				wptools_load_pointer();
				add_action('wp_loaded', 'wptools_load_pointer_css');

				$r = update_option('wptools_activated_pointer', '0');
				if (!$r) {
					add_option('wptools_activated_pointer', '0');
				}
			}
	    } 
    }
	// end pointer


	// Activation...
	register_activation_hook(__FILE__, 'wptools_activated');

	$BILLCLASS = 'ACTIVATED_WPTOOLS';
	if(!isset($_COOKIE[$BILLCLASS]) and $wptools_activated_notice == '1'){
		add_action('wp_loaded', 'wptools_load_feedback3');
		add_action( 'admin_notices', 'wptools_include_file_more_plugins' );
	}

	// 2023
	function wptools_load_feedback3() {
		global $wptools_request_url;
		if (strpos($wptools_request_url, 'plugins.php') !== false) 
		{
			wp_register_style( 'bill-feedback-wptools-css', WPTOOLSURL. 'includes/feedback/feedback-plugin.css' );
			wp_enqueue_style( 'bill-feedback-wptools-css' );

			wp_register_script( 'bill-feedback-wptools-js', WPTOOLSURL.'includes/feedback/activated-manager.js' , array( 'jquery' ), WPTOOLSVERSION, true );
			wp_enqueue_script( 'bill-feedback-wptools-js' );
		}
	}

	function wptools_include_file_more_plugins() {
		global $wptools_request_url;
		if (strpos($wptools_request_url, 'plugins.php') !== false) 
			require_once (WPTOOLSPATH . 'includes/feedback/activated-manager.php');
	}

	function wptools_activated()
	{
		$r = update_option('wptools_was_activated', '1');
		if (!$r) {
			add_option('wptools_was_activated', '1');
		}
		$r = update_option('wptools_activated_notice', '1');
		if (!$r) {
			add_option('wptools_activated_notice', '1');
		}
		$r = update_option('wptools_activated_pointer', '1');
		if (!$r) {
			add_option('wptools_activated_pointer', '1');
		}
	}
	// end activation
}



function wptools_dismissible_notice()
{
	$r = update_option('wptools_dismiss', false);
	if (!$r) {
		$r = add_option('wptools_dismiss', false);
	}
	wp_die($r);
}
add_action('wp_ajax_wptools_dismissible_notice', 'wptools_dismissible_notice');

if (get_option('wptools_dismiss', true) and is_admin())

	add_action('admin_notices', 'wptools_dismiss_admin_notice');
function wptools_dismiss_admin_notice()
{
	if(!bill_check_resources(false))
	   return;
	?>
		<div id="wptools_an1" class="notice-warning notice is-dismissible">
			<p>
			Please, look the WP Tools plugin Dashboard (Server Check & Requirements Tab)&nbsp;
			<a class="button button-primary" href="admin.php?page=wptools_options31&tab=requirements">or click here</a>
		   </p>
		</div>
	<?php
	//endif;
}
if($wptools_disable_selfping == 'yes'){
	$home = esc_url( home_url() );
	add_action( 'pre_ping', function ( &$post_links, &$pung, int $post_ID ) {
		foreach ( $post_links as $key => $link ) {
			if ( 0 === strpos( $link, $home ) ) {
				unset( $post_links[ $key ] );
			}
		}
	}, 10, 3 );
}
add_action('wp_ajax_wptools_bill_go_pro_hide', 'wptools_bill_go_pro_hide');
function wptools_load_feedback()
{
    if (is_admin() or is_super_admin()) {
        if (file_exists(WPTOOLSPATH. 'includes/feedback/feedback-last.php'))
            require_once WPTOOLSPATH. "includes/feedback/feedback-last.php";
    }
}
add_action('wp_loaded', 'wptools_load_feedback');



  function wptools_localization_init()
{
	$path = basename( dirname( __FILE__ ) ) . '/language';
    $loaded = load_plugin_textdomain('wptools', false, $path);
    if (!$loaded and get_locale() <> 'en_US') {
        if (function_exists('wptools_localization_init_fail'))
            add_action('admin_notices', 'wptools_localization_init_fail');
    }
}  
function wptools_localization_init_fail()
{
	if(get_option('wptools_dismiss_language') == '1')
	  return;

    echo '<div id="wptools_an2" class="update notice is-dismissible">
                     <br />
                     WP Tools Plugin not load the localization file (Language file).
                     <br />
                     Please, contact me at our Support Page to translate it on your language.
					 <br />
					 <br />
					 </div>';
}  

function wptools_dismissible_notice2() {
		$r = update_option('wptools_dismiss_language', '1');
		if (!$r) {
			$r = add_option('wptools_dismiss_language', '1');
		}
}
add_action('wp_ajax_wptools_dismissible_notice2', 'wptools_dismissible_notice2');

if(function_exists('wptools_errors_today')){
   if(wptools_errors_today(1))
     add_filter( 'site_status_tests', 'wptools_error_test' );
}

function wptools_add_error_test() {
    $result = array(
        'label'       => __( 'WP Tools found errors on your site' ),
        'status'      => 'critical',
        'badge'       => array(
            'label' => __( 'Critical' ),
            'color' => 'red',
        ),
        'description' => sprintf(
            '<p>%s</p>',
            __( 'Code and hosting errors can destroy your site and the user experience.' )
        ),
        'test'        => 'wptools_plugin',
    );
        $result['actions'] = sprintf(
            '<p><a href="%s">%s</a></p>',
            esc_url( admin_url( 'admin.php?page=wptools_options21' ) ),
            __( 'Visit the WP Tools show errors page','wptools' )
        );
 
    return $result;
}


function wptools_check_if_obd_permitted()
{
   $obd=ini_get('open_basedir');

   if (!$obd) return true;

   if(empty($obd))
     return true;
   else
     return false;

   // if (substr(realpath($file), 0, strlen($obd))==$obd) return true;
}

function wptools_exec_enabled() {
	if(!function_exists('shell_exec'))
	  return false;
    $disabled = explode(',', ini_get('disable_functions'));
    return !in_array('exec', $disabled);
}

if(isset($_GET['wptools_get_speed_info']))
{
	wptools_get_speed_info();	
}


function wptools_get_speed_info() {
		require_once WPTOOLSPATH . "functions/functions_benchmark.php";
		$arr_cfg = array();
		$arr_cfg['db.host'] = DB_HOST;
		$arr_cfg['db.user'] = DB_USER;
		$arr_cfg['db.pw'] = DB_PASSWORD;
		$arr_cfg['db.name'] = DB_NAME;
		$showServerName = true;
        // wptools_performance_share($benchmarkResult);
	$server_performance = wptools_test_benchmark($arr_cfg);
	$benchmarkResult = $server_performance;
	$server_performance = $server_performance['total'];

    $r = get_transient('wptools_performance_share');


	// $x = var_export($r,true);
	//error_log('987 '.$x);


	if (!$r) {
		wptools_performance_share($benchmarkResult);
	    $r = get_transient('wptools_performance_share');
	}
	$r = json_decode($r, true);
	$benchmarkIndustryResult = wptools_industry_benchmark($r);
    $industry_performance = $benchmarkIndustryResult['total'];


	if($industry_performance < 1)
	   $industry_performance = 1;
	if($server_performance >= $industry_performance)
	   wp_die(round(($industry_performance/$server_performance)*100));
	else
	   wp_die('100');

    return;
}

/////// weekly cron job


if ($wptools_radio_email_weekly_error_notification == 'yes') {

    // Check if the cron job is already scheduled
    if (!wp_next_scheduled('wptools_weekly_cron_job')) {
		wp_schedule_event(time(), 'weekly', 'wptools_weekly_cron_job');
    }

    // Add the function that will be executed by the cron job
    add_action('wptools_weekly_cron_job', 'wptools_send_error_summary_email');
} else {

	wptools_deactivate_cron_job();
}

   // Deactivate the cron job when deactivating the plugin (optional)
   register_deactivation_hook(__FILE__, 'wptools_deactivate_cron_job');

   function wptools_deactivate_cron_job()
   {
	   // Remove the cron job
	   wp_clear_scheduled_hook('wptools_weekly_cron_job');

   }

   function wptools_send_error_summary_email() {
	  global $wptools_email_to;
	  global $wpdb;



		$wptools_last_email_sent = get_site_option('wptools_last_email_sent_issues');
		if ($wptools_last_email_sent > time() - (7 * 24 * 60 * 60)) {
			return;
		}
	
	
		





	  $wptools_table_name = $wpdb->prefix . 'wptools_errors'; 
      if ($wpdb->get_var("SHOW TABLES LIKE '$wptools_table_name'") !== $wptools_table_name) 
	     return;

	  $seven_days_ago = date('Y-m-d', strtotime('-7 days'));

	  $query = $wpdb->prepare(
		"SELECT COUNT(*) AS qtotal
		FROM $wptools_table_name
		WHERE error_date >= %s",
		$seven_days_ago
	  );
 
      $error_count = $wpdb->get_var($query);

	  if (!is_numeric($error_count) or $error_count < 1)
	    return;

	if (empty($wptools_email_to))
	  $wptools_email_to = sanitize_email(get_option('admin_email',''));


	  $message = 'Hello,
	  <br>
	  This is a weekly summary of errors, warnings, or notices that occurred on your site assisted by the wpTools plugin:
	  <br>
	  Total number of issues: ' . $error_count . '
	  <br>
	  Please visit your site for more details:
	  Dashboard => wpTools => Dashboard
	  <br>
	  ';

		$dt = date("Y-m-d H:i:s");
		$dom = sanitize_text_field($_SERVER['SERVER_NAME']);
		$msg =  __('This email was sent from your website', "wptools");
		$msg .= ': ' . $dom . ' ';
		$msg .=  __('by the wpTools plugin.', "wptools");
		$msg .= '<br> ';
		$msg .= __('Date', "wptools");
		$msg .= ': ' . $dt . '<br>';
		$msg .= '<br> ';
		$msg .= $message;
		$msg .= '<br> ';
		$msg .= '<br> ';
		$msg .= __('You can stop weekly emails at the Notifications Tab:', "wptools");
		$msg .= '<br>';
		$msg .= __('Dashboard => WP tools=> Settings', "wptools");
		$email_from = 'wordpress@' . $dom;
	
		/*
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: " . $wptools_email_to . "\r\n" . 'Reply-To: ' . $wptools_email_to . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		*/


		$headers = array(
				'Content-Type: text/html; charset=UTF-8',
				'From: ' . $wptools_email_to,
				'Reply-To: ' . $wptools_email_to,
			  );

	
		$to = $wptools_email_to;
		$subject = __('Weekly summary of issues on the site: ', "wptools") . ' ' . $dom;
		wp_mail($to, $subject, $msg, $headers, '');

		update_site_option('wptools_last_email_sent_issues', time());

		return;

}


// Call the function when needed
// wptools_send_error_summary_email();


/////// weekly cron job END


///////////////   wptools_auto_updates

if($wptools_auto_updates !== 'No'){
	if( !wptools_check_autoupdate()){
		wptools_check_autoupdate_activate();
	}
}

function wptools_check_autoupdate(){
	$plugin_slug = 'wptools';
	$auto_update_settings = get_site_option('auto_update_plugins', array());
	if ($auto_update_settings && is_array($auto_update_settings) ) {
		foreach ($auto_update_settings as $plugin_path) {
			if (strpos($plugin_path, $plugin_slug) !== false) 
				return true;
		}
	} 
	return false;
}

function wptools_check_autoupdate_activate(){

	$auto_update_settings = get_site_option('auto_update_plugins', array());
	$target_plugin_slug_details = 'wptools/wptools.php';
	$auto_update_settings[] = $target_plugin_slug_details;
	update_site_option('auto_update_plugins', $auto_update_settings);
	return;
}

///////////////   wptools_auto_updates END

add_filter( 'manage_upload_columns', 'wpse_237131_add_column_file_size' );
add_action( 'manage_media_custom_column', 'wpse_237131_column_file_size', 10, 2 );

function wpse_237131_add_column_file_size( $columns ) { // Create the column
    $columns['filesize'] = 'File Size';
    return $columns;
}
function wpse_237131_column_file_size( $column_name, $media_item ) { // Display the file size
    if ( 'filesize' != $column_name || !wp_attachment_is_image( $media_item ) ) {
      return;
    }
    $filesize = filesize( get_attached_file( $media_item ) );
    $filesize = size_format($filesize, 2);
    echo esc_attr($filesize);
}
require_once WPTOOLSPATH . 'functions/function_time_loading.php';


/////// weekly cron job load
if ($wptools_radio_email_weekly_load_notification == 'yes') {
    // Check if the cron job is already scheduled
    if (!wp_next_scheduled('wptools_weekly_cron_job_loadtime')) {
		wp_schedule_event(time(), 'weekly', 'wptools_weekly_cron_job_loadtime');
    }
    // Add the function that will be executed by the cron job
    add_action('wptools_weekly_cron_job_loadtime', 'wptools_send_load_summary_email');
} else {
	wptools_deactivate_cron_job_loadtime();
}
   // Deactivate the cron job when deactivating the plugin (optional)
   register_deactivation_hook(__FILE__, 'wptools_deactivate_cron_job_loadtime');
   function wptools_deactivate_cron_job_loadtime()
   {
	   // Remove the cron job
	   wp_clear_scheduled_hook('wptools_weekly_cron_job_loadtime');
   }
   function wptools_send_load_summary_email() {
	  global $wptools_email_to;
	  global $wpdb;
	  $wptools_table_name = $wpdb->prefix . 'wptools_page_load_times';
      if ($wpdb->get_var("SHOW TABLES LIKE '$wptools_table_name'") !== $wptools_table_name) 
	     return;
	  $seven_days_ago = date('Y-m-d', strtotime('-7 days'));
	  $query = $wpdb->prepare(
		"SELECT AVG(load_time) AS average_load_time
		FROM $wptools_table_name
		WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
		AND page_url NOT LIKE %s",
		'wp-admin'
		);
		$page_load = $wpdb->get_var($query);
		// die(var_export($page_load ));


	    if (!is_numeric($page_load) or $page_load < 3.1) // 3.1
	       return;


		if (!is_numeric($page_load) or $page_load > 30) 
	       return;



		$page_load_rounded = round($page_load, 1);
		if (empty($wptools_email_to))
		$wptools_email_to = sanitize_email(get_option('admin_email',''));
		$message = 'Hello,
		<br>
		This is a weekly summary of Page Load seconds that occurred on your site assisted by the wpTools plugin:
		<br>
		Weekly Page Load Time: ' . $page_load_rounded . '
		<br>
		Google recommend that the page loading time be less than 3 seconds. A faster-loading website is more likely to rank higher, potentially attracting more visitors.
		<br>
		Please visit your site for more details:
		Dashboard => wpTools => Dashboard
		<br>
		';
		$dt = date("Y-m-d H:i:s");
		$dom = sanitize_text_field($_SERVER['SERVER_NAME']);
		$msg =  __('This email was sent from your website', "wptools");
		$msg .= ': ' . $dom . ' ';
		$msg .=  __('by the wpTools plugin.', "wptools");
		$msg .= '<br> ';
		$msg .= __('Date', "wptools");
		$msg .= ': ' . $dt . '<br>';
		$msg .= '<br> ';
		$msg .= $message;
		$msg .= '<br> ';
		$msg .= '<br> ';
		$msg .= __('You can stop weekly emails at the Notifications Tab:', "wptools");
		$msg .= '<br>';
		$msg .= __('Dashboard => WP tools=> Settings', "wptools");
		$email_from = 'wordpress@' . $dom;
		$headers = array(
				'Content-Type: text/html; charset=UTF-8',
				'From: ' . $wptools_email_to,
				'Reply-To: ' . $wptools_email_to,
			  );
		$to = $wptools_email_to;
		$subject = __('Weekly summary of Page Load on the site: ', "wptools") . ' ' . $dom;
		$r = wp_mail($to, $subject, $msg, $headers, '');
		// die(var_export($r));
		return;
}
/////// weekly cron job load END



if ($wptools_disable_ziparchive == 'yes' and function_exists('_unzip_file_pclzip')) {
	add_filter( 'unzip_file_use_ziparchive', '__return_false' );  
} 




/*
function myplugin_wptools_is_enabled(){ add_filter( 'unzip_file_use_ziparchive', '__return_false' );

	return false;
}
*/
/**********************************
 * DEBUG
 **********************************/
/* function wptools_save_error() {
    update_option( 'wptools_plugin_error',  ob_get_contents() );
}
add_action( 'activated_plugin', 'wptools_save_error' );
echo get_option( 'wptools_plugin_error' ); */
// var_dump( wptools_errors_today());
// XMLHttpRequest must not be sending.
// add_action('admin_notices', 'wptools_dismiss_admin_notice');

// wptools_send_load_summary_email();