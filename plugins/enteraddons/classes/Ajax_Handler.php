<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons helper class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !class_exists('Ajax_Handler') ) {

	class Ajax_Handler {

		function __construct() {

			add_action('wp_ajax_mailchimp_action_fire', [__CLASS__, 'mailchimp_ajax_maping'] );
			add_action('wp_ajax_noprev_mailchimp_action_fire', [__CLASS__, 'mailchimp_ajax_maping']);
		}

		public static function mailchimp_ajax_maping() {
			$msg = [];
			$getKey = get_option(ENTERADDONS_OPTION_KEY);

			if(  !isset( $getKey['integration']['mailchimp_token'] ) && empty( $getKey['integration']['mailchimp_token'] ) ) {
				echo json_encode( [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Please set your mailchimp API key', 'enteraddons' ) ] );
				wp_die();
			}

			$listid = $email = '';

			// List Id Check 
			if( !empty( $_POST['list_id'] ) ) {
				$listid = sanitize_text_field( $_POST['list_id'] );
			} else {
				echo json_encode( [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Mailchimp list ID missing.', 'enteraddons' ) ] );
				wp_die();
			}
			// Mail Id Check
	        if( !empty( $_POST['email'] ) && filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ){
	        	$email = sanitize_email( $_POST['email'] );
	        } else {
	        	echo json_encode( [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Please enter valid mail ID.', 'enteraddons' ) ] );
	        	wp_die();
	        }

	        //
			if( !empty( $email ) && !empty( $listid ) ) {

				$MailChimp = new Mail_Chimp( $getKey['integration']['mailchimp_token'] );

		        $result = $MailChimp->post("lists/$listid/members",[
		            'email_address'    => $email,
		            'status'           => 'subscribed',
		        ]);

		        // Response
		        if ( $MailChimp->success() ) {
		            if( !empty( $result['status'] ) && $result['status'] == 'subscribed' ){
		                
		            $msg = [ 'status' => true, 'type' => 'success', 'msg' => esc_html__( 'Thank you, you have been added to our mailing list.', 'enteraddons' ) ];

		            }
		        }elseif( !empty( $result['status'] ) && $result['status'] == '400' ) {
		            $msg = [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'This Email address is already exists.', 'enteraddons' ) ];
		        }else{
		            
		            $msg = [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Sorry something went wrong. Please try again.', 'enteraddons' ) ];
		        }



			}  else {
				$msg = [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Sorry something went wrong. Please try again.', 'enteraddons' ) ];
			}

			echo json_encode($msg);

	        wp_die();


		}

	} // Class End

} // Check condition end
