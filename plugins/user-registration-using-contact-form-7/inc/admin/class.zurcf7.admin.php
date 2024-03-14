<?php
/**
 * ZURCF7_Admin Class
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage Plugin name
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ZURCF7_Admin' ) ) {

	/**
	 * The ZURCF7_Admin Class
	 */
	class ZURCF7_Admin {

		var $action = null,
			$filter = null;

		static $setting_submenu = 'zurcf7_settings';

		function __construct() {
			add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );

			add_action( 'do_meta_boxes', array( $this, 'zcf7ur_remove_submit_meta_boxes' ) );
		}

		/*
		   ###     ######  ######## ####  #######  ##    ##  ######
		  ## ##   ##    ##    ##     ##  ##     ## ###   ## ##    ##
		 ##   ##  ##          ##     ##  ##     ## ####  ## ##
		##     ## ##          ##     ##  ##     ## ## ## ##  ######
		######### ##          ##     ##  ##     ## ##  ####       ##
		##     ## ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##     ##  ######     ##    ####  #######  ##    ##  ######
		*/

		/**
		 * Action: admin_menu
		 *
		 * Used for Add Menu Page
		 *
		 * @method action_admin_menu
		 */
		function action_admin_menu(){
			$author = wp_get_current_user();
			if( current_user_can( 'administrator' ) ){
				add_submenu_page(
					'edit.php?post_type='.ZURCF7_POST_TYPE,
					__( 'Settings', 'zeal-user-reg-cf7' ),
					__( 'Settings', 'zeal-user-reg-cf7' ),
					'manage_options',
					self::$setting_submenu,
					array( $this, 'zurcf7_setting_page' )
				);

			}else{
				remove_menu_page( 'edit.php?post_type=zuserreg_data' );
				if(isset($_GET['post_type']) && ($_GET['post_type'] === ZURCF7_POST_TYPE))
					wp_die( 'Cheatin’ uh?' );
			}
		}

		/*
		######## #### ##       ######## ######## ########   ######
		##        ##  ##          ##    ##       ##     ## ##    ##
		##        ##  ##          ##    ##       ##     ## ##
		######    ##  ##          ##    ######   ########   ######
		##        ##  ##          ##    ##       ##   ##         ##
		##        ##  ##          ##    ##       ##    ##  ##    ##
		##       #### ########    ##    ######## ##     ##  ######
		*/


		/*
		######## ##     ## ##    ##  ######  ######## ####  #######  ##    ##  ######
		##       ##     ## ###   ## ##    ##    ##     ##  ##     ## ###   ## ##    ##
		##       ##     ## ####  ## ##          ##     ##  ##     ## ####  ## ##
		######   ##     ## ## ## ## ##          ##     ##  ##     ## ## ## ##  ######
		##       ##     ## ##  #### ##          ##     ##  ##     ## ##  ####       ##
		##       ##     ## ##   ### ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##        #######  ##    ##  ######     ##    ####  #######  ##    ##  ######
		*/

		/**
		 * Action: admin_menu
		 *
		 * Save/reset all the admin settings
		 *
		 * @method zurcf7_setting_page
		 *
		 */
		function zurcf7_setting_page(){
			wp_enqueue_style( ZURCF7_PREFIX . '-admin-css');
			wp_enqueue_script( ZURCF7_PREFIX . '-admin-js');

			if( isset( $_REQUEST['_zurcf7_settings_nonce'] ) && $_REQUEST['_zurcf7_settings_nonce'] != '' ) {
				if( ! wp_verify_nonce( $_REQUEST['_zurcf7_settings_nonce'], 'zurcf7_settings_nonce' ) ){
					add_action( 'admin_notices', array( $this, 'action__admin_notices_zurcf7_nonce_issue' ) );
					return;
				}
			}

			//save settings in admin
			if(isset($_REQUEST['setting_zurcf7_submit'])){
				if(isset($_POST['zurcf7_formid'])) update_option( 'zurcf7_formid', sanitize_text_field($_POST['zurcf7_formid']));
				isset($_POST['zurcf7_debug_mode_status']) ? update_option( 'zurcf7_debug_mode_status', sanitize_text_field($_POST['zurcf7_debug_mode_status'])) : update_option( 'zurcf7_debug_mode_status', "");
				isset($_POST['zurcf7_skipcf7_email']) ? update_option( 'zurcf7_skipcf7_email', sanitize_text_field($_POST['zurcf7_skipcf7_email'])) : update_option( 'zurcf7_skipcf7_email', "");
				if(isset($_POST['zurcf7_successurl_field'])) update_option( 'zurcf7_successurl_field', sanitize_text_field($_POST['zurcf7_successurl_field']));
				if(isset($_POST['zurcf7_email_field']) && $_POST['zurcf7_email_field'] != '') update_option( 'zurcf7_email_field', sanitize_text_field($_POST['zurcf7_email_field']));
				if(isset($_POST['zurcf7_username_field']) && $_POST['zurcf7_username_field'] != '') update_option( 'zurcf7_username_field', sanitize_text_field($_POST['zurcf7_username_field']));
				if(isset($_POST['zurcf7_userrole_field']) && $_POST['zurcf7_userrole_field'] != '') update_option( 'zurcf7_userrole_field', sanitize_text_field($_POST['zurcf7_userrole_field']));

				//Start Save ACF Fields
				if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
					$returnfieldarr = zurcf7_ACF_filter_array_function();
					if(!empty($returnfieldarr)){
						$count = 0;
						foreach ($returnfieldarr['response'] as $value) { 
							$field_name = $value['field_name'];
							if($count != 3) {
								// Perform blank check before updating option
								$field_value = isset($_POST[$field_name]) ? sanitize_text_field($_POST[$field_name]) : '';
								update_option($field_name, $field_value);
								
							}
						$count++;
						}
					}
				}
				//End Save ACF Fields
				//Start FB Fields
				isset($_POST['zurcf7_fb_signup_app_id']) ? update_option( 'zurcf7_fb_signup_app_id', sanitize_text_field($_POST['zurcf7_fb_signup_app_id'])) : update_option( 'zurcf7_fb_signup_app_id', "");
				isset($_POST['zurcf7_fb_app_secret']) ? update_option( 'zurcf7_fb_app_secret', sanitize_text_field($_POST['zurcf7_fb_app_secret'])) : update_option( 'zurcf7_fb_app_secret', "");
				//End FB Fields
				isset($_POST['zurcf7_enable_sent_login_url']) ? update_option( 'zurcf7_enable_sent_login_url', sanitize_text_field($_POST['zurcf7_enable_sent_login_url'])) : update_option( 'zurcf7_enable_sent_login_url', "");
				
			}

			//reset all the settings.
			if(isset($_REQUEST['setting_reset'])){
				update_option( 'zurcf7_formid', "");
				update_option( 'zurcf7_email_field', "");
				update_option( 'zurcf7_username_field', "");
				update_option( 'zurcf7_userrole_field', "");
				update_option( 'zurcf7_debug_mode_status', "");
				update_option( 'zurcf7_skipcf7_email', "");
				update_option( 'zurcf7_successurl_field', "");

				//Start Save ACF Fields
				if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
					$returnfieldarr = zurcf7_ACF_filter_array_function();
					if(!empty($returnfieldarr)){
						$count = 0;
						foreach ($returnfieldarr['response'] as $value) { 
							$field_name = $value['field_name'];
							if($count != 3) {
								// Perform blank check before updating option
								$field_value = "";
								update_option($field_name, $field_value);
								
							}
						$count++;
						}
					}
				}
				//End Save ACF Fields
				//Start FB Fields
				update_option( 'zurcf7_fb_signup_app_id', "");
				update_option( 'zurcf7_fb_app_secret', "");
				//End FB Fields
				update_option( 'zurcf7_enable_sent_login_url', "");

			}

			//get all the fields value from the database
			$zurcf7_formid = (get_option( 'zurcf7_formid')) ? get_option( 'zurcf7_formid') : "";
			$zurcf7_debug_mode_status = (get_option( 'zurcf7_debug_mode_status')) ? get_option( 'zurcf7_debug_mode_status') : "";
			$zurcf7_skipcf7_email = (get_option( 'zurcf7_skipcf7_email')) ? get_option( 'zurcf7_skipcf7_email') : "";
			$zurcf7_successurl_field = (get_option( 'zurcf7_successurl_field')) ? get_option( 'zurcf7_successurl_field') : "";
			$zurcf7_email_field = (get_option( 'zurcf7_email_field')) ? get_option( 'zurcf7_email_field') : "";
			$zurcf7_username_field = (get_option( 'zurcf7_username_field')) ? get_option( 'zurcf7_username_field') : "";
			$zurcf7_userrole_field = (get_option( 'zurcf7_userrole_field')) ? get_option( 'zurcf7_userrole_field') : "";
			$zurcf7_enable_sent_login_url = (get_option( 'zurcf7_enable_sent_login_url')) ? get_option( 'zurcf7_enable_sent_login_url') : "";
			//get all the CF7 forms
			$cf7forms = $this->get_all_cf7_forms();

			//Registration form tags
			if(!empty($zurcf7_formid)){
				$cf7 = WPCF7_ContactForm::get_instance($zurcf7_formid);
				$tags = $cf7->collect_mail_tags();
			}

			//Login form tags
			if(!empty($zurcf7_login_formid)){
				$cf7 = WPCF7_ContactForm::get_instance($zurcf7_login_formid);
				$logintags = $cf7->collect_mail_tags();
			}

			require_once( ZURCF7_DIR .  '/inc/admin/template/' . ZURCF7_PREFIX . '.settings.template.php' );
		}

		/**
		 * get all the CF7 forms
		 *
		 */
		function get_all_cf7_forms(){
			$cf7forms = get_posts(array(
				'post_type'     => 'wpcf7_contact_form',
				'numberposts'   => -1
			));
			return $cf7forms;
		}

		/**
		 * nonce issue notice
		 */
		function action__admin_notices_zurcf7_nonce_issue(){
			echo '<div class="error">' .
				sprintf(
					__( '<p>Nonce issue.. Please try again.</p>', 'zeal-user-reg-cf7' ),
					'User Registration Using Contact Form 7'
				) .
			'</div>';
		}

		/**
		 * Hide Metaboxes For All Post Types
		 */
		function zcf7ur_remove_submit_meta_boxes() {
			remove_meta_box( 'submitdiv', ZURCF7_POST_TYPE, 'side' );
		}
	}

	add_action( 'plugins_loaded', function() {
		ZURCF7()->admin = new ZURCF7_Admin;
	} );
}
