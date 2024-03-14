<?php
/**
* Class: WPE_Model_Settings
* @author Flipper Code <hello@flippercode.com>
* @version 1.0.0
* @package Forms
*/
if ( ! class_exists( 'WPE_Model_Settings' ) ) {
	/**
	* Setting model for Plugin Options.
	* @package Forms
	* @author Flipper Code <hello@flippercode.com>
	*/
	class WPE_Model_Settings extends FlipperCode_WPE_Model_Base {
		/**
		* Intialize Backup object.
		*/
		function __construct() {
		}
		/**
		* Admin menu for Settings Operation
		* @return array Admin menu navigation(s).
		*/
		function navigation() {
			return array(
			'wpe_manage_settings' => __( 'Settings', WPE_TEXT_DOMAIN ),
			);
		}
		/**
		* Add or Edit Operation.
		*/
		function save() {
			$nonce = wp_create_nonce( 'wpgmp-nonce2' );
			if ( isset( $_REQUEST['_wpnonce'] ) ) {
			$nonce = sanitize_text_field( $_REQUEST['_wpnonce']  ); }
			if ( !isset( $nonce ) || ! wp_verify_nonce( $nonce, 'wpgmp-nonce2' ) ) {
				die( 'Cheating...' );
			}
			$this->verify( $_POST );
			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}
			$wpe_value_array = array(
      		'wpe_prayer_site_key'                  => sanitize_text_field( $_POST['wpe_prayer_Site_Key'] ),
     	 	'wpe_prayer_secret_key'                => sanitize_text_field( $_POST['wpe_prayer_secret_key'] ),
			'wpe_prayer_btn_color'                 => sanitize_text_field( $_POST['wpe_prayer_btn_color'] ),
			'wpe_prayer_btn_text_color'            => sanitize_text_field( $_POST['wpe_prayer_btn_text_color'] ),
			'wpe_pray_btn_color'                   => sanitize_text_field( $_POST['wpe_pray_btn_color'] ),
			'wpe_pray_text'          		       => sanitize_text_field( $_POST['wpe_pray_text'] ),
			// 'wpe_pray_btn_image'                   => sanitize_text_field( $_POST['wpe_pray_btn_image'] ),
			'wpe_pray_text_color'                  => sanitize_text_field( $_POST['wpe_pray_text_color'] ),
			'wpe_num_prayer_per_page'              => sanitize_text_field( $_POST['wpe_num_prayer_per_page'] ),
			'wpe_terms_and_condition'              => wp_filter_post_kses(stripslashes($_POST['wpe_terms_and_condition'] )),
           	'wpe_num_of_characters_in_message'     => sanitize_text_field( htmlspecialchars($_POST['wpe_num_of_characters_in_message'])),
			'wpe_login_required' 			   	   => (empty($_POST['wpe_login_required'])) ? 'true' : sanitize_text_field( $_POST['wpe_login_required'] ),
			'wpe_send_email'                 	   => (empty($_POST['wpe_send_email'])) ? 'false' : sanitize_text_field( $_POST['wpe_send_email'] ),
			'wpe_send_admin_email'                 => (empty($_POST['wpe_send_admin_email'])) ? 'false' : sanitize_text_field( $_POST['wpe_send_admin_email'] ),
	        'wpe_hide_email'                       => (empty($_POST['wpe_hide_email'])) ? 'false' : sanitize_text_field( $_POST['wpe_hide_email'] ),
	        'wpe_disapprove_prayer_default' 	   => (empty($_POST['wpe_disapprove_prayer_default'])) ? 'false' : sanitize_text_field( $_POST['wpe_disapprove_prayer_default'] ),
			'wpe_hide_prayer'	 		 		   => (empty($_POST['wpe_hide_prayer'])) ? 'false' : sanitize_text_field( $_POST['wpe_hide_prayer'] ),
			'wpe_hide_prayer_count'	 		 => (empty($_POST['wpe_hide_prayer_count'])) ? 'false' : sanitize_text_field( $_POST['wpe_hide_prayer_count'] ),
			'wpe_display_author'	 	 	 => (empty($_POST['wpe_display_author'])) ? 'false' : sanitize_text_field( $_POST['wpe_display_author'] ),
			'wpe_captcha'		 	 	 => (empty($_POST['wpe_captcha'])) ? 'false' : sanitize_text_field( $_POST['wpe_captcha'] ),
			'wpe_country'		 	 	 => (empty($_POST['wpe_country'])) ? 'false' : sanitize_text_field( $_POST['wpe_country'] ),
			'wpe_category'		 	 	 => (empty($_POST['wpe_category'])) ? 'false' : sanitize_text_field( $_POST['wpe_category'] ),
			'wpe_share'		 	 		 => (empty($_POST['wpe_share'])) ? 'false' : sanitize_text_field( $_POST['wpe_share'] ),
			'wpe_date'		 	 		 => (empty($_POST['wpe_date'])) ? 'false' : sanitize_text_field( $_POST['wpe_date'] ),
			'wpe_ago'		 	 		 => (empty($_POST['wpe_ago'])) ? 'false' : sanitize_text_field( $_POST['wpe_ago'] ),			
            'wpe_autoemail'		 	 	 => (empty($_POST['wpe_autoemail'])) ? 'false' : sanitize_text_field( $_POST['wpe_autoemail'] ),    
			'wpe_prayer_time_interval'   => sanitize_text_field( $_POST['wpe_prayer_time_interval'] ),
            'wpe_prayer_comment'		 	 => (empty($_POST['wpe_prayer_comment'])) ? 'false' : sanitize_text_field( $_POST['wpe_prayer_comment'] ),
            'wpe_prayer_comment_status'		 	 => (empty($_POST['wpe_prayer_comment_status'])) ? 'false' : sanitize_text_field( $_POST['wpe_prayer_comment_status'] ),
            'wpe_categorylist'                       => sanitize_text_field( $_POST['wpe_categorylist'] ),
			'wpe_fetch_req_from'			 => sanitize_text_field( $_POST['wpe_fetch_req_from'] ),
            'wpe_thankyou'                         => wp_filter_post_kses(stripslashes($_POST['wpe_thankyou'] )),
			);
			//$settings = unserialize(get_option('_wpe_prayer_engine_settings'));
			//$wpe_value_array = array_merge($settings,$wpe_value_array);
			update_option( '_wpe_prayer_engine_settings', serialize( $wpe_value_array ) );
			$response['success'] = __( 'Setting(s) saved successfully.',WPE_TEXT_DOMAIN );
			return $response;
		}
	}
}
