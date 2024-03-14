<?php
/*
 Plugin Name: Select and Multi-Select Field for Contact Form 7
 Description: This plugin uses jQuery plugin called Select2 for select boxes which allows searching and infinite scrolling for the select boxes option.  
 Author: Yash Baldawa
 Author URI: https://github.com/baldawayash15
 Text Domain: yb-sml
 Domain Path: /languages
 Version: 1.1
 License: GPLv2 or later
 License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

//Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die();

class YB_Selct_Multiselct_Plugin{
	
	public function __construct(){
		add_action( 'plugins_loaded', array( $this, 'yb_load_plugin_textdomain' ) );
		if(class_exists('WPCF7')){
			$this->yb_plugin_constants();
			require_once YB_SM_PATH . 'includes/autoload.php';
		}else{
            add_action( 'admin_notices', array( $this, 'wpcf7_selct_multiselct_not_active' ) );

            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
		}	
	}
	
	public function yb_load_plugin_textdomain() {
		load_plugin_textdomain( 'yb-sml', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/*
		register admin notice if contact form 7 is not active.
	*/
	public function wpcf7_selct_multiselct_not_active(){
        ?>
        <div class="error">
            <p>Sorry, but <strong>Contact Form 7 - Select and Multi-Select</strong> requires <strong><a href="//wordpress.org/plugins/contact-form-7/">Contact Form 7</a></strong>.</p>
        </div>
        <?php
	}
	
	/*
		set plugin constants
	*/
	public function yb_plugin_constants(){
		
		if ( ! defined( 'YB_SM_PATH' ) ) {
			define( 'YB_SM_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'YB_SM_URL' ) ) {
			define( 'YB_SM_URL', plugin_dir_url( __FILE__ ) );
        }
        if ( ! defined( 'YB_SM_BASENAME' ) ) {
			define( 'YB_SM_BASENAME', plugin_basename( __FILE__ ) );
        }
	}
}

// Instantiate the plugin class.
$YB_SM_Plugin = new YB_Selct_Multiselct_Plugin();