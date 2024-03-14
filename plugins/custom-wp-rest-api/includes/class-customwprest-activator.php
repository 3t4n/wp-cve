<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Fired during plugin activation
 *
 * @link       https://dipankar-team.business.site
 * @since      1.0.0
 *
 * @package    Customwprest
 * @subpackage Customwprest/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Customwprest
 * @subpackage Customwprest/includes
 * @author     Dipankar <dipankarpal212@gmail.com>
 */
class WCRA_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//create root secret
		global $wpdb;
		$wpr__settings = array();
  		$tab = $wpdb->prefix.WCRA_DB.'api_base';
  		$ApiSecret = wcra_api_key_gen();
  		$wpr_set_req_check = 0;
  		$wpr_set_auth_check = 1;
  		$wpr_set_ver = 1;
  		$wpr_set_enb_api = 1;
  		$wpr_set_end_slug = 'wcra';
  		$wpr_set_recent_activity_dur = 3;
  		$_get_settings = wcra_get_settings('wpr_set_end_slug');
	    $wpr__settings['root_secret'] = $ApiSecret;
	    $wpr__settings['wpr_set_req_check'] = $wpr_set_req_check;
	    $wpr__settings['wpr_set_ver'] = $wpr_set_ver;
	    $wpr__settings['wpr_set_end_slug'] = $wpr_set_end_slug;
	    $wpr__settings['wpr_set_auth_check'] = $wpr_set_auth_check;
	    $wpr__settings['wpr_set_enb_api'] = $wpr_set_enb_api;
	    $wpr__settings['wpr_set_recent_activity_dur'] = $wpr_set_recent_activity_dur;
	    if(empty($_get_settings)){
	    	update_option('wcra_settings_meta' , $wpr__settings);
	   		wcra_create_database_tables();
	    }
	    $notification = "Plugin Activated!";
	    wcra_save_default_endpoint();
        wcra_save_recent_activity(array('txt' => $notification ));
    	do_action('wcra_plugin_activated_actiondo');//for extra 
	    add_action( 'activated_plugin', 'wcra_activation_redirect' );
	    //wpr_cron_activation();
	    
	}

}
