<?php
/**
Plugin Name: Contact Form 7 Spam Killer
Description: Prevent unwanted spam mail from your inbox. A permanent solution for from 7 spam emails issue.
Author: WP-EXPERTS.IN Team
Author URI: https://www.wp-experts.in
Version: 1.6
License:GPL2
Conatct Form 7 Spam Killer is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
Conatct Form 7 Spam Killer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Contact Conatct Form 7 Spam Killer.
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( !class_exists( 'CF7_Advance_Security_Admin' ) ) {
    class CF7_Advance_Security_Admin {
        /**
         * Construct the plugin object
         */
        public function __construct() {
			// Installation and uninstallation hooks
			register_activation_hook( __FILE__,  array(&$this, 'cf7as_activation') );
			register_deactivation_hook( __FILE__,  array(&$this, 'cf7as_deactivation') );			
			add_action( 'admin_menu', array( &$this, 'cf7as_admin_menu_init') );
			add_filter( "plugin_action_links_".plugin_basename(__FILE__), array(&$this,'cf7as_add_settings_link') );
			// register actions
			add_action( 'admin_init', array( &$this, 'cf7as_register_settings') );
			// better use get_current_screen(); or the global $current_screen
			$currentPage = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
			if ($currentPage == 'cf7as-settings') {
			   add_action('admin_footer',array( &$this,'init_cf7as_admin_scripts' ) );
			}
			
        } // END public function __construct

		/** register admin menu */
		 public function cf7as_admin_menu_init() {

					add_submenu_page(
					'wpcf7',
					__( 'CF7 Advance Security', 'cfas' ),
					__( 'Advance Security', 'cfas' ),
					'manage_options',
					'cf7as-settings',
					array(&$this, 'cf7as_add_settings_page')
				);
				
			}


      // Add settings link to plugin list page in admin
        public function cf7as_add_settings_link( $links ) {
            $settings_link = array('<a href="admin.php?page=cf7as-settings">' . __( 'Settings', 'cf7as' ) . '</a>');
            return array_merge( $links, $settings_link );;
        }

		/** register settings */
		public function cf7as_register_settings() {
			register_setting( 'cf7as_options', 'cf7as_captcha');
			register_setting( 'cf7as_options', 'cf7as_hidden_captcha'); 
			register_setting( 'cf7as_options', 'cf7as_email_confirmation'); 
			register_setting( 'cf7as_options', 'cf7as-inlinecss'); 
		} 


	/* CF7 Advance Security Settings Page HTML*/
	public function cf7as_add_settings_page() {
	  $inlineCss=get_option('cf7as-inlinecss');
	?>
	<div style="width: 80%; padding: 10px; margin: 10px;"> 
	 <h1>Contact Form 7 Advance Security Settings</h1>
	 <!-- Start Options Form -->
	 <form action="options.php" method="post" id="cf7as-sidebar-admin-form">	
	 <div id="cf7as-tab-menu"><a id="cf7as-general" class="cf7as-tab-links active" >General</a>
	 <a id="cf7as-shortcode" class="cf7as-tab-links" >Shortcodes</a> <a  id="cf7as-support" class="cf7as-tab-links">Support</a> 
	 </div>
	<div class="cf7as-setting">
		<!-- General Setting -->	
		<div class="first cf7as-tab" id="div-cf7as-general">
		<h2>General Settings</h2>
		<p><input type="checkbox" id="cf7as_captcha" name="cf7as_captcha" value='1' <?php if(get_option('cf7as_captcha')!=''){ echo ' checked="checked"'; }?>/><label> Enable Math Captcha</label></p>
		<p><label>Math Captcha CSS </label><br><textarea rows="10" cols="50" id="cf7as-inlinecss" name="cf7as-inlinecss" ><?php echo $inlineCss;?></textarea> </p>
		</div>
		<!-- Shortcode -->	
		<div class="cf7as-tab" id="div-cf7as-shortcode">
		<h2>Shortcodes</h2>
		<p><h3>Math Captcha</h3>[cf7ascaptcha "What is your answer" "enter answer" "invalid answer"] --  Use this shortcode for add to captcha into form 7</p>
		
		<h4><a href="<?php echo plugin_dir_url( __FILE__ ) . 'images/screenshot-2.png'; ?>"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/screenshot-2.png'; ?>" width="100%">
	</a></h4>
		</div>
		<!-- Support -->
		<div class="last author cf7as-tab" id="div-cf7as-support">
		<h2>Plugin Support</h2>
		<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" title="Donate for this plugin"></a></p>
		<p><strong>Plugin Author:</strong><a href="https://www.wp-experts.in" target="_blank">WP Experts Team</a></p>
		<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
		<p><strong>Our Other Plugins:</strong><br>
		<ol>
						<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons With Floating Sidebar</a></li>
						<li><a href="https://wordpress.org/plugins/seo-manager/" target="_blank">SEO Manager</a></li>
						<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
						<li><a href="https://wordpress.org/plugins/wp-sales-notifier/" target="_blank">WP Sales Notifier</a></li>
						<li><a href="https://wordpress.org/plugins/wp-post-notification/" target="_blank">WP Post Notification</a></li>
						<li><a href="https://wordpress.org/plugins/wp-tracking-manager/" target="_blank">WP Tracking Manager</a></li>
						<li><a href="https://wordpress.org/plugins/wp-categories-widget/" target="_blank">WP Categories Widget</a></li>
						<li><a href="https://wordpress.org/plugins/wp-protect-content/" target="_blank">WP Protect Content</a></li>
						<li><a href="https://wordpress.org/plugins/wp-amp-website/" target="_blank">WP AMP Website</a></li>
						<li><a href="https://wordpress.org/plugins/wp-version-remover/" target="_blank">WP Version Remover</a></li>
						<li><a href="https://wordpress.org/plugins/wp-posts-widget/" target="_blank">WP Post Widget</a></li>
						<li><a href="https://wordpress.org/plugins/otp-login/" target="_blank">OTP Login</a></li>
						<li><a href="https://wordpress.org/plugins/wp-importer" target="_blank">WP Importer</a></li>
						<li><a href="https://wordpress.org/plugins/optimizer-wp-website/" target="_blank">Optimize WP Website</a></li>
						<li><a href="https://wordpress.org/plugins/wp-testimonial/" target="_blank">WP Testimonial</a></li>
						<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
						<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
						<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
						<li><a href="https://wordpress.org/plugins/rg-responsive-gallery/" target="_blank">RG Responsive Slider</a></li>
						<li><a href="https://wordpress.org/plugins/cf7-advance-security" target="_blank">Contact Form 7 Advance Security WP-Admin</a></li>
						<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
				 </ol></p>
		</div>
		</div>
			<span class="submit-btn"><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></span>
		<?php settings_fields('cf7as_options'); ?>
		</form>
	<!-- End Options Form -->
	</div>
	<?php
	}
	/** add js into admin footer */
	 public function init_cf7as_admin_scripts() {
		wp_register_style( 'cf7as_admin_style', plugins_url( 'css/cf7as-admin-min.css',__FILE__ ) );
		wp_enqueue_style( 'cf7as_admin_style' );
		// JS files
		wp_register_script('wpc_admin_script', plugins_url('/js/cf7as-admin.js',__FILE__ ), array('jquery'));
		wp_enqueue_script('wpc_admin_script');
		
		}


    /** register_deactivation_hook */
    /** Delete exits options during deactivation the plugins */
	//Delete all options after uninstall the plugin
	public function cf7as_deactivation(){
		delete_option('cf7as_captcha');
		delete_option('cf7as-inlinecss');
	}
	/** register_activation_hook */
	/** Delete exits options during activation the plugins */
	//Disable free version after activate the plugin
	public function cf7as_activation(){
			if ( !is_plugin_active('contact-form-7/wp-contact-form-7.php')){
			// Throw an error in the wordpress admin console
			$error_message = __('This plugin requires <a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a> plugins to be active!', 'wpexpertsin');
			die($error_message);
			}
		delete_option('cf7as_captcha');
		delete_option('cf7as-inlinecss');
	}

    } // END class WP_Protect_Content
} // END if(!class_exists('WP_Protect_Content'))

// init class
if( class_exists( 'CF7_Advance_Security_Admin' ) ) {
    // instantiate the plugin class
    $cf7_init = new CF7_Advance_Security_Admin();
}
/** Include class file **/
require dirname(__FILE__).'/cf7as-class.php';
?>
