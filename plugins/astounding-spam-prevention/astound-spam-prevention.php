<?PHP
/*
Plugin Name: Astounding Spam Prevention
Plugin URI: http://wordpress.org/plugins/stop-spammer-registrations-plugin/
Description: Astounding Spam Prevention blocks spammers from leaving comments. Protects sites from robot registrations and malicious attacks.
Version: 1.19
Author: Keith P. Graham

This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
if (!defined('ABSPATH')) exit;

/*
* define globals.
*/
define('ASTOUND_VERSION', '1.17');
define( 'ASTOUND_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ASTOUND_PLUGIN_FILE', plugin_dir_path( __FILE__ ) );

astound_require('includes/astound-init.php');

add_action('init','astound_init',0); 

function astound_require($file) {
	require_once($file);
}
function astound_file_exists($file) {
	$found=file_exists($file);
	if (!$found) {
		astound_log("can't find $file");
	}
	return $found;
}
function astound_class($file) {
	require_once('modules/'.$file.'.php');
	return new $file;
}
function astound_log($msg="message") {
	// used to aid debugging. Adds to debug file
	$now=date('Y/m/d H:i:s',time() + ( get_option( 'gmt_offset' ) * 3600 ));
	// get the program that is running
	//$sname=$_SERVER["REQUEST_URI"];	
	//if (empty($sname)) {
	//	$sname=$_SERVER["SCRIPT_NAME"];	
	//}
	
	$f='';
	$f=@fopen(ASTOUND_PLUGIN_FILE."/.astound_debug_output.txt",'a');
	if(empty($f)) return false;
	@fwrite($f," $now: $msg\r\n");
	@fclose($f);
}
function astound_set_option($op,$val) {
	$options=astound_get_options();
	$options[$op]=$val;
	update_option('astound_options',$options);

}	
function astound_get_options() {
	/********************************************
	* returns and array of options 
	* checks the options array for the current
	* defined version mumber. If different it
	* reloads the defaults and compares looks
	* for changes.
	********************************************/
	$ansa=get_option('astound_options');
	if (isset($ansa) && is_array($ansa) && array_key_exists('ASTOUND_VERSION',$ansa) ) {
		if (ASTOUND_VERSION==$ansa['ASTOUND_VERSION']) {
			return $ansa;
		}
	}
	/* version not found or different - reload with defaults. */
	astound_require('includes/astound-init-options.php');
	return astound_init_options();
}
function astound_get_post() {
	/* safe post */
	$post=array();
	if (!isset($_POST)) return $post;
	if (empty($_POST)) return $post;
	// sanitize post
	$post=$_POST;
	$keys=array_keys($_POST);
	foreach ($keys as $var) {
		try {
			$val=$_POST[$var];
			if (is_string($val)) {
				if (strpos($var,'email')!==false) {
					// $val2 = sanitize_email($val);
					$val2 = sanitize_text_field($val); // don't fix up the email too much, it may hide spam.
				} else if (strpos($val,"\n")!==false) {
					$val2 = esc_textarea($val);
				} else {
					$val2 = sanitize_text_field($val);
				}
			$post[$var]=$val2;
			}
		} catch (Exception $e) {}
		return $post;
	}
}
function astound_get_ip() {
	$ip=$_SERVER['REMOTE_ADDR'];
	return $ip;
}
function astound_admin_scripts() {
	// js
	wp_register_script('astound-js',ASTOUND_PLUGIN_URL.'/script/astound.js');
	wp_enqueue_script('astound-js');
 	
	// style
	wp_register_style('astound-css',ASTOUND_PLUGIN_URL.'/css/astound.css');
	wp_enqueue_style('astound-css');
	
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__),'astound_add_action_links' );
function astound_add_action_links ( $links ) {
	$mylinks = array('<a href="'.admin_url('options-general.php?page=astound_control').'">Settings</a>');
	$links=array_merge($links,$mylinks);
	return $links;
}
function astound_errorsonoff($old=null) {
	$debug=true;  // change to true to debug, false to stop all debugging.
	if (!$debug) return;
	if (empty($old)) return set_error_handler("astound_ErrorHandler");
	restore_error_handler();
}
function astound_ErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
	// write the answers to the file
	// we are only concerned with the errors and warnings, not the notices
	//if ($errno==E_NOTICE || $errno==E_WARNING) return false;
	//if ($errno==2048) return; // wordpress throws deprecated all over the place.
	$serrno="";
	if (
			(strpos($filename,'kpg')===false)
			&&(strpos($filename,'admin-options')===false)
			&&(strpos($filename,'mu-options')===false)
			&&(strpos($filename,'stop-spam')===false)
			&&(strpos($filename,'sfr_mu')===false)
			&&(strpos($filename,'settings.php')===false)
			&&(strpos($filename,'options-general.php')===false)
			) return false;
	switch ($errno) {
	case E_ERROR: 
		$serrno="Fatal run-time errors. These indicate errors that can not be recovered from, such as a memory allocation problem. Execution of the script is halted. ";
		break;
	case E_WARNING: 
		$serrno="Run-time warnings (non-fatal errors). Execution of the script is not halted. ";
		break;
	case E_NOTICE: 
		$serrno="Run-time notices. Indicate that the script encountered something that could indicate an error, but could also happen in the normal course of running a script. ";
		break;
		default;
		$serrno="Unknown Error type $errno";
	}
	if (strpos($errmsg,'modify header information')) return false;
	$now=date('Y/m/d H:i:s',time() + ( get_option( 'gmt_offset' ) * 3600 ));
	$m1=memory_get_usage(true);
	$m2=memory_get_peak_usage(true);
	$ip=kpg_get_ip();
	$msg="
	Time: $now
	Error number: $errno
	Error type: $serrno
	Error Msg: $errmsg
	IP address: $ip
	File name: $filename
	Line Number: $linenum
	Memory used, peak: $m1, $m2
	---------------------
	";
	// write out the error
	astound_log($msg);
	return false;
}

?>