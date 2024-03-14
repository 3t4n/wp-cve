<?php
/**
 * Class: WPE_Model_Email_Settings
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Forms
 */

if ( ! class_exists( 'WPE_Model_Email_Settings' ) ) :
	
	class WPE_Model_Email_Settings extends FlipperCode_WPE_Model_Base {

		function __construct() {}

		function navigation() {
			return array('wpe_manage_email_settings' => __( 'Email Settings', WPE_TEXT_DOMAIN ));
		}

		function save() {
			$nonce = wp_create_nonce( 'wpgmp-nonce1' );
			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( $_REQUEST['_wpnonce']  ); 
			}
			
			if ( !isset( $nonce ) || ! wp_verify_nonce( $nonce, 'wpgmp-nonce1' ) ) {
				die( 'Cheating...' );
			}
			
			$this->verify( $_POST );
			
			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}
			
			$wpe_value_array = array(
				'prayer_req_admin_email' => sanitize_email($_POST['prayer_req_admin_email'] ),
				'wpe_email_from'	 => sanitize_text_field($_POST['wpe_email_from'] ),
				'wpe_email_cc'		 => sanitize_text_field($_POST['wpe_email_cc'] ),
				'wpe_email_user'	 => sanitize_email($_POST['wpe_email_user'] ),
				'wpe_email_req_subject'  => sanitize_text_field($_POST['wpe_email_req_subject']),
				'wpe_email_req_messages' => wp_filter_post_kses(stripslashes($_POST['wpe_email_req_messages'] )),
				'wpe_email_praise_subject' => sanitize_text_field( $_POST['wpe_email_praise_subject'] ),
				'wpe_email_praise_messages' => wp_filter_post_kses(stripslashes($_POST['wpe_email_praise_messages'])),
				'wpe_email_admin_subject' => sanitize_text_field( $_POST['wpe_email_admin_subject'] ),
				'wpe_email_admin_messages' => wp_filter_post_kses(stripslashes($_POST['wpe_email_admin_messages'] )),
                'wpe_email_prayed_subject'  => sanitize_text_field($_POST['wpe_email_prayed_subject']),
				'wpe_email_prayed_messages' => wp_filter_post_kses(stripslashes($_POST['wpe_email_prayed_messages'] )),
                );

			//$settings = unserialize(get_option('_wpe_prayer_engine_settings'));
			//$wpe_value_array = array_merge($settings, $wpe_value_array);

			update_option( '_wpe_prayer_engine_email_settings', serialize( $wpe_value_array ) );
			$response['success'] = __( 'Email Setting(s) saved successfully.',WPE_TEXT_DOMAIN );
			return $response;
		}
	}

endif;