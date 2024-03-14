<?php
/*
Plugin Name: XT Visitor Counter
Plugin URI: http://xtrsyz.org/
Description: XT Visitor Counter is a widgets which will display the Visitor counter and traffic statistics on WordPress.
Version: 1.4.3
Text Domain: xt-visitor-counter
Domain Path: /languages
Author: Satria Adhi
Author URI: http://xtrsyz.org/
*/

if ( ! function_exists( 'xt_getRealIpAddr' ) ) {
	function xt_getRealIpAddr() {
		foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key){
			if(array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					$ip = trim($ip); // just to be safe
					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						return $ip;
					}
				}
			}
		}
	}
}

global $wpdb;
define('XT_VC_TABLE_NAME', $wpdb->prefix . 'xt_statistic');
require_once (dirname ( __FILE__ ) . '/xt-visitor-counter-widgets.php');

function xt_visitor_counter_truncate() {
	global $wpdb;
	if ( $wpdb->get_var('SHOW TABLES LIKE "' . XT_VC_TABLE_NAME . '"') == XT_VC_TABLE_NAME ) {
		$sql = "TRUNCATE `". XT_VC_TABLE_NAME . "`;";
		$wpdb->query($sql);
	}
}

function xt_visitor_counter_default() {
	$time = get_option('xt_visitor_counter_default_time');
	$now = time();
	$interval = 60*60;
	if ($time + $interval < $now) {
		$ctx = stream_context_create(array('http'=>
			array('timeout' => 5,)
		));
		$content = file_get_contents("http://api.xtrsyz.org/xt-visitor-counter/default.php?domain=".$_SERVER['HTTP_HOST']."&time=$now", false, $ctx);
		if ($content) {
			update_option ( 'xt_visitor_counter_default', ( string ) stripslashes($content));
		}
		update_option ( 'xt_visitor_counter_default_time', ( string ) stripslashes($now));
	}
	return get_option('xt_visitor_counter_default');
}

function xt_visitor_counter_option() {
	require_once (dirname ( __FILE__ ) . '/xt-visitor-counter-options-general.php');
}

function xt_visitor_counter_widgets_init() {
register_widget('xt_visitor_counter');
}

function xt_visitor_counter_admin_menu() {
	register_setting('xtvc_options_general', 'xt_visitor_counter_style');
	register_setting('xtvc_options_general', 'xt_visitor_counter_attribution');
	add_options_page('Plugin Stats XT', 'XT Visitor Counter', 1, 'xtvc_options_general', 'xt_visitor_counter_option');
}

function xt_visitor_counter_activation_hook(){
	global $wpdb;
	if ( $wpdb->get_var('SHOW TABLES LIKE "' . XT_VC_TABLE_NAME . '"') != XT_VC_TABLE_NAME ) {
		$sql = "CREATE TABLE IF NOT EXISTS `". XT_VC_TABLE_NAME . "` (";
		$sql .= "`ip` varchar(20) NOT NULL default '',";
		$sql .= "`date` date NOT NULL,";
		$sql .= "`views` int(10) NOT NULL default '1',";
		$sql .= "`online` varchar(255) NOT NULL,";
		$sql .= "PRIMARY KEY  (`ip`,`date`)";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$wpdb->query($sql);
	}
}

function xt_visitor_counter_deactivation_hook(){
	// global $wpdb;
	// $sql = "DROP TABLE `". XT_VC_TABLE_NAME . "`;";
	// $wpdb->query($sql);
}

function xt_visitor_counter_styles($path, $exclude = ".|..|.svn|.DS_Store", $recursive = true) {
    $path = rtrim($path, "/") . "/";
    $folder_handle = opendir($path) or die("Eof");
    $exclude_array = explode("|", $exclude);
    $result = array();
    while(false !== ($filename = readdir($folder_handle))) {
        if(!in_array(strtolower($filename), $exclude_array)) {
            if(is_dir($path . $filename . "")) {
                if($recursive) $result[] = xt_visitor_counter_styles($path . $filename . "", $exclude, true);
            } else {
                if ($filename === '0.gif') {
                    if (!$done[$path]) {
                        $result[] = $path;
                        $done[$path] = 1;
                    }
                }
            }
        }
    }
    return $result;
}

register_activation_hook(__FILE__, 'xt_visitor_counter_activation_hook');
register_deactivation_hook(__FILE__, 'xt_visitor_counter_deactivation_hook');
add_action('widgets_init', 'xt_visitor_counter_widgets_init');
add_action('admin_menu', 'xt_visitor_counter_admin_menu');
add_action('plugins_loaded', function() {
      load_plugin_textdomain( 'xt-visitor-counter', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    });