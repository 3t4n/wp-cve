<?php
/*
Plugin Name: Powies Uptime Robot
Plugin URI: https://powie.de/wordpress/powies-uptime-robot/
Description: Powies Uptime Robot Plugin with Shortcode and Widget
Version: 0.9.7
License: GPLv2
Author: Thomas Ehrhardt
Author URI: https://powie.de
Text Domain: powies-uptime-robot
Domain Path: /languages
*/

//Define some stuff
define( 'PUM_PLUGIN_DIR', dirname( plugin_basename( __FILE__ ) ) );
define( 'PUM_PLUGIN_URL', plugins_url( dirname( plugin_basename( __FILE__ ) ) ) );
load_plugin_textdomain( 'powies-uptime-robot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

//call widgets file
include('status-cloud-widget.php');

//CSS
function pum_scripts() {
	wp_enqueue_style('pum', plugins_url('pum.css',__FILE__), array(), 1); //main css
}
add_action( 'wp_enqueue_scripts', 'pum_scripts' );

//Admin Menu
add_action('admin_menu', 'pum_create_menu');
function pum_create_menu() {
	// or create options menu page
	add_options_page(__('Uptime Robot Setup'),__('Uptime Robot Setup'), 'manage_options', PUM_PLUGIN_DIR.'/pum_settings.php');
}

//create custom plugin settings menu
//add_action('admin_menu', 'pag_create_menu');

//add_action('wp_head', 'plinks_websnapr_header');
//Shortcode
add_shortcode('pum', 'pum_shortcode');
//Hook for Activation
register_activation_hook( __FILE__, 'pum_activate' );
//Hook for Deactivation
register_deactivation_hook( __FILE__, 'pum_deactivate' );

add_action('admin_init', 'pum_register_settings' );
function pum_register_settings() {
	//register settings
	register_setting( 'pum-settings', 'pum-apikey');			//API Key
	register_setting( 'pum-settings', 'pum-cache' );			//Letzte abgefragte Daten
	register_setting( 'pum-settings', 'pum-time' );			    //Timestamp abgefragte Daten
	register_setting( 'pum-settings', 'pum-hidemonitors' );		//Liste zu versteckender Monitore
}

function pum_shortcode( $atts ) {
	$json = pum_get_data();
	//var_dump($json);
	$sc = '<table class="pum"><tr>
			<th>'.__('Status', 'powies-uptime-robot').'</th>
			<th>'.__('Monitor Name', 'powies-uptime-robot').'</th>
			<th>'.__('Uptime', 'powies-uptime-robot').'</th></tr>';
	foreach ($json->monitors as $monitor) {
		if ( isset($atts['monitor'])) {
			if ( $monitor->friendly_name == $atts['monitor'] ) {
					$sc.='<tr><td><span class="pum stat'.$monitor->status.'">
        	              '.pum_status_type($monitor->status).'</span></td>
					      <td>'.$monitor->friendly_name.'</td>
						  <td>'.$monitor->all_time_uptime_ratio.' %</td></tr>';
			}
		}
		elseif ( pum_hidestatus($monitor->friendly_name) ) {
			$sc.='<tr><td><span class="pum stat'.$monitor->status.'">
                      '.pum_status_type($monitor->status).'</span></td>
				  <td>'.$monitor->friendly_name.'</td>
				  <td>'.$monitor->all_time_uptime_ratio.' %</td></tr>';
		}
	}
	$sc.='</table>';
	$sc.=__('Updated at', 'powies-uptime-robot'). ' '.get_date_from_gmt( date('Y-m-d H:i:s' ,get_option( 'pum-time' )), get_option('time_format'));
	//$sc.=__('Updated at', 'pum'). ' '.date_i18n(get_option('time_format'), get_option( 'pum-time' ));
	return $sc;
}

//Activate
function pum_activate() {
	//do not generate any output here
	//add_option('postfield-rows',5);
	//add_option('after-post-msg', __('Thanks for your post. We will review your post befor publication.','pag'));
}

//Deactivate
function pum_deactivate() {
	// do not generate any output here
}

function pum_status_type($status){
	switch ($status) {
		case 0:
			$r = __('paused', 'powies-uptime-robot');
			break;
		case 1:
			$r = __('not checked yet', 'powies-uptime-robot');
			break;
		case 2:
			$r = __('up', 'powies-uptime-robot');
			break;
		case 8:
			$r = __('seems down', 'powies-uptime-robot');
			break;
		case 9:
			$r = __('down', 'powies-uptime-robot');
			break;
		default:
			$r = __('unknown', 'powies-uptime-robot');
	} // switch
	return $r;
}

function pum_get_data() {
	// check for cached copy
	$cache = get_option( 'pum-cache' );

	if ($cache != '' && time() < $cache['timestamp'] + 600) { // cache is < 10 minutes old. use it.
		$json = json_decode($cache['umdata']);
		//$json = $cache['data'];
	}
	else { // cache is stale
		// set up request
		$api_key = get_option( 'pum-apikey' ); // My Settings > API Information > Monitor-specific API keys > Select a Monitor > Click to Create One
		//$url = "https://api.uptimerobot.com/v2/getMonitors?api_key=" . $api_key . "&logs=1&showTimezone=1&format=json&noJsonCallback=1";

		// request via cURL
		$curl = curl_init();
		curl_setopt_array($curl, array(
  			CURLOPT_URL => "https://api.uptimerobot.com/v2/getMonitors",
  			CURLOPT_RETURNTRANSFER => true,
  			CURLOPT_ENCODING => "",
  			CURLOPT_MAXREDIRS => 10,
  			CURLOPT_TIMEOUT => 30,
  			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  			CURLOPT_CUSTOMREQUEST => "POST",
  			CURLOPT_POSTFIELDS => "api_key=".$api_key."&format=json&logs=0&all_time_uptime_ratio=1",
  			CURLOPT_HTTPHEADER => array(
    			"cache-control: no-cache",
    			"content-type: application/x-www-form-urlencoded"
  			),
		));
		$responseJSON = curl_exec($curl);
		$json = json_decode($responseJSON);
		// don't cache if there's a failure
		if ($json !== NULL && $json->stat != 'fail') {
			// save to cached  option
			//update_option('pum-cache', array ( 'data' => $json, 'timestamp' => time()));
			update_option('pum-cache', array ( 'umdata' => $responseJSON, 'timestamp' => time()));
			update_option('pum-time', time() );
		}
		curl_close($curl);
	}
	//var_dump($json);
	return $json;
}

function pum_hidestatus($name) {
	$l = explode(',', get_option('pum-hidemonitors') );
	if ( in_array($name,$l) ) {
		return false;
	} else {
		return true;
	}
}
?>