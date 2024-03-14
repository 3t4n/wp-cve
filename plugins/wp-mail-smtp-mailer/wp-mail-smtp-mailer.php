<?php
/*
Plugin Name: WP Mail Smtp - SMTP7
Plugin URI: https://ciphercoin.com/
Description: Make email delivery easy from WordPress. It is easy to configure. 
Text Domain: wp-mail-smtp-mailer
Version: 1.0.8
Author: Arshid
Author URI: https://ciphercoin.com/
*/


require_once plugin_dir_path( __FILE__).'encryption.class.php';

//Plugin activation 
function wpmsm_plugin_activate() {

	  $dir_path   = plugin_dir_path( __FILE__ );
    
    add_option( 'WPMSM_mail_data','' , '', 'yes' );
    add_option( 'wpmsm_mailer_install_date', date('Y-m-d G:i:s'), '', 'yes');

}
register_activation_hook( __FILE__, 'wpmsm_plugin_activate' );

//Plugin deactivation 
function wpmsm_plugin_deactivation() {
	
	delete_option( 'WPMSM_mail_data' );
	delete_option( 'WPMS_mail_flag' );

}
register_deactivation_hook( __FILE__, 'wpmsm_plugin_deactivation' );



// Add settings link on plugin page
function wpmsm_settings_link($links) { 
  $settings_link = "<a href='options-general.php?page=wp-mail-smtp-mailer'>".__('Settings','wp-mail-smtp-mailer')."</a>"; 
  array_unshift($links, $settings_link); 
  return $links; 
}

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wpmsm_settings_link' );

//plugin load
function wpmsm_plugin_load(){

	load_plugin_textdomain( 'wp-mail-smtp-mailer', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
	
	require_once plugin_dir_path(__FILE__).'settings.class.php';
	
	$dir_path  		 = plugin_dir_path( __FILE__ );

	WPMS_settings::get_instance($dir_path);
	
}
add_action('plugins_loaded', 'wpmsm_plugin_load');




add_action( 'phpmailer_init', 'wpms_php_mailer' );
function wpms_php_mailer( $phpmailer ) {

  global $wpms_option;
  $option = $wpms_option;

	// if( empty( $wpms_option ) ) $option = get_option('WPMSM_mail_data','');

	if ($option['encrypt'] == '1'){

		$option['host'] 	  = WPMSM_encryption::decrypt( $option['host'] );
		$option['username'] = WPMSM_encryption::decrypt($option['username'], SECURE_AUTH_KEY);
		$option['password']	= WPMSM_encryption::decrypt($option['password'], SECURE_AUTH_KEY);
	}

    $phpmailer->isSMTP();     
    $phpmailer->Host = $option['host'];
    $phpmailer->SMTPAuth = true;  
    $phpmailer->Port = $option['port'];
    $phpmailer->Username = $option['username'];
    $phpmailer->Password = $option['password'];

    // Additional settings…
    if( $option['SMTPSecure'] != 'none' )
      $phpmailer->SMTPSecure = $option['SMTPSecure']; 
    

    if( $option['From'] != '' )
      $phpmailer->From = $option['From'];
    
    if( $option['FromName'] != '' )
      $phpmailer->FromName = $option['FromName'];

    unset( $wpms_option );
  
}

add_filter('wp_mail_from', 'wpms_mail_form');

function wpms_mail_form( $from ){

  global $wpms_option;
  
  $wpms_option = get_option('WPMSM_mail_data','');

  if( $wpms_option['From'] != '' ) 
    $from = $wpms_option['From'];

  return $from;
}