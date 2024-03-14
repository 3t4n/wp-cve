<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Scripts Class
 *
 * Html for eclg form
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */
class Eclg_Public {

	public function __construct() {

		add_action( 'wp_ajax_eclg_add_newsletter', array( $this, 'eclg_add_newsletter' ) );
		add_action( 'wp_ajax_nopriv_eclg_add_newsletter', array( $this, 'eclg_add_newsletter' ) );
	}

	/**
	 * Validating and insert
	 *
	 * Validate whole form and insert data into database
	 *
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
	 */
	public function eclg_add_newsletter() {

		$all_options = get_option( 'eclg_options', array() );

		$integration_data  = isset( $all_options['eclg_integration'] ) ? $all_options['eclg_integration'] : array();
		$selected_provider = isset( $integration_data['selectedProvider'] ) ? $integration_data['selectedProvider'] : array();
		$use_own_list      = isset( $integration_data['useOwnList'] ) ? $integration_data['useOwnList'] : 'yes';

		global $wpdb;

		$firstname = isset( $_POST['firstname'] ) ? sanitize_text_field( $_POST['firstname'] ) : '';
		$lastname  = isset( $_POST['lastname'] ) ? sanitize_text_field( $_POST['lastname'] ) : '';
		$email     = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$ip        = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';

		$response = array();

		// Get email id if registerd
		$table_name = $wpdb->prefix . 'eclg_subscribers';
		$myrows     = $wpdb->get_results( 'SELECT email FROM ' . $table_name . " WHERE email = '" . $email . "'" );

		// Check firstname validation
		if ( isset( $_POST['firstname'] ) && empty( $firstname ) ) {
			$response['status'] = '0';
			$response['errmsg'] = __( 'Please enter firstname', 'email-capture-lead-generation' );
		}

		// Check email validation
		if ( empty( $email ) ) {
			$response['status'] = '0';
			$response['errmsg'] = __( 'Please enter email address', 'email-capture-lead-generation' );
		} elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$response['status'] = '0';
			$response['errmsg'] = __( 'Please enter valid email address.', 'email-capture-lead-generation' );
		} elseif ( count( $myrows ) > 0 ) {
			$response['status'] = '0';
			$response['errmsg'] = __( 'You have already subscribed.', 'email-capture-lead-generation' );
		}

		// if error print and exit
		if ( isset( $response['status'] ) && $response['status'] == '0' ) {
			echo json_encode( $response );
			exit;
		}

		/**
		 * If user disables the own list then use newletter providers.
		 */
		if ( 'yes' !== $use_own_list ) {

			if ( 'activecampaign' === $selected_provider ) {
				$response = $this->activecampaign_newsletter( $integration_data, $email, $firstname, $lastname );
			} else {
				$response = $this->mailchimp_newsletter( $integration_data, $email, $firstname, $lastname );
			}

			echo json_encode( $response );
			exit;
		}

		// insert newslertter data
		$wpdb->insert(
			$table_name,
			array(
				'first_name' => $firstname,
				'last_name'  => $lastname,
				'email'      => $email,
				'user_ip'    => $ip,
				'date'       => current_time( 'mysql' ),
			),
			array(
				'%s',
				'%s',
				'%s',
			)
		);

		// Check if data inserted
		if ( ! empty( $wpdb ) && ! is_wp_error( $wpdb ) ) {

			$response['status'] = '1';
			$response['errmsg'] = __( 'You have subscribed successfully!.', 'email-capture-lead-generation' );

		}

		echo json_encode( $response );
		exit;
	}


	/**
	 * Handles the activeCampaign newletter response.
	 */
	public function activecampaign_newsletter( $integration_data, $email, $firstname, $lastname ) {

		$response = array();

		$args     = array();
		$endpoint = '';

		$res           = '';
		$entry_created = '';
		$res_body      = '';
		$contact_data  = '';
		$list_res      = '';

		$response['status'] = '0';
		$response['errmsg'] = __( 'Sorry, please provide a valid email address or try again later.', 'email-capture-lead-generation' );

		$selected_provider = isset( $integration_data['selectedProvider'] ) ? $integration_data['selectedProvider'] : array();
		$api_keys          = isset( $integration_data['apiKeys'] ) ? $integration_data['apiKeys'] : array();
		$list_ids          = isset( $integration_data['listIDs'] ) ? $integration_data['listIDs'] : array();
		$list_id           = isset( $list_ids[ $selected_provider ] ) ? $list_ids[ $selected_provider ] : null;

		$url = ! empty( $api_keys[ $selected_provider ]['url'] ) ? $api_keys[ $selected_provider ]['url'] : '';
		$key = ! empty( $api_keys[ $selected_provider ]['key'] ) ? $api_keys[ $selected_provider ]['key'] : '';

		/**
		 * Data one that will create the contact in activeCampaign.
		 * It will be used later below.
		 */
		$contact_json = array(
			'contact' => array(
				'email'     => $email,
				'firstName' => $firstname,
				'lastName'  => $lastname,
			),
		);

		/**
		 * Lets set the headers for our activeCampaign api.
		 */
		$args     = array(
			'headers'     => array(
				'Content-Type' => 'application/json',
				'Api-Token'    => $key,
			),
			'body'        => wp_json_encode( $contact_json ),
			'method'      => 'POST',
			'data_format' => 'body',
		);
		$endpoint = "{$url}/api/3/contacts";

		/**
		 * Lets create the contact first.
		 * After we successfully created the contact, we then use that contact id to add it to the admin selected list.
		 */
		if ( $endpoint && $args ) {
			$res = wp_remote_post( $endpoint, $args );
		}

		if ( is_array( $res ) && isset( $res['body'] ) && isset( $res['response'] ) ) {
			$entry_created = ! empty( $res['response']['code'] ) && 201 === $res['response']['code'];
			if ( $entry_created ) {
				$res_body = json_decode( $res['body'] );
			}
		}

		/**
		 * If we have created the contact, lets add it to the admin selected list.
		 */
		if ( $entry_created && is_object( $res_body ) ) {
			$contact_data = isset( $res_body->contact ) ? $res_body->contact : '';
			$contact_id   = is_object( $contact_data ) && isset( $contact_data->id ) ? $contact_data->id : '';

			$list_json = array(
				'contactList' => array(
					'list'    => $list_id,
					'contact' => $contact_id,
					'status'  => 1,
				),
			);

			$args['body'] = wp_json_encode( $list_json );
			$endpoint     = "{$url}/api/3/contactLists";

			if ( $endpoint && $args ) {
				$list_res = wp_remote_post( $endpoint, $args );
			}

			if ( is_array( $list_res ) && isset( $list_res['body'] ) && isset( $list_res['response'] ) ) {
				$entry_created = ! empty( $list_res['response']['code'] ) && 201 === $list_res['response']['code'];
				if ( $entry_created ) {
					/**
					 * If we are here, then it means, we have successfully created a new contact and added it to the
					 * admin selected list.
					 */
					$response['status'] = '1';
					$response['errmsg'] = __( 'You have subscribed successfully!.', 'email-capture-lead-generation' );
				}
			}
		}

		return $response;

	}


	/**
	 * Handles the mailchimp newletter response.
	 */
	public function mailchimp_newsletter( $integration_data, $email, $firstname, $lastname ) {

		$response = array();

		$args     = array();
		$endpoint = '';

		$res           = '';
		$entry_created = '';

		$response['status'] = '0';
		$response['errmsg'] = __( 'Sorry, please provide a valid email address or try again later.', 'email-capture-lead-generation' );

		$selected_provider = isset( $integration_data['selectedProvider'] ) ? $integration_data['selectedProvider'] : array();
		$api_keys          = isset( $integration_data['apiKeys'] ) ? $integration_data['apiKeys'] : array();
		$list_ids          = isset( $integration_data['listIDs'] ) ? $integration_data['listIDs'] : array();
		$list_id           = isset( $list_ids[ $selected_provider ] ) ? $list_ids[ $selected_provider ] : null;

		$url = ! empty( $api_keys[ $selected_provider ]['url'] ) ? $api_keys[ $selected_provider ]['url'] : '';
		$key = ! empty( $api_keys[ $selected_provider ]['key'] ) ? $api_keys[ $selected_provider ]['key'] : '';

		$contact_json = array(
			'email_address' => $email,
			'status'        => 'subscribed',
			'merge_fields'  => array(
				'FNAME' => $firstname,
				'LNAME' => $lastname,
			),
		);

		$args     = array(
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( "username:$key" ),
			),
			'body'        => wp_json_encode( $contact_json ),
			'method'      => 'POST',
			'data_format' => 'body',
		);
		$endpoint = "{$url}/3.0/lists/{$list_id}/members";

		$res = wp_remote_post( $endpoint, $args );

		if ( is_array( $res ) && isset( $res['response'] ) && isset( $res['body'] ) ) {
			$entry_created = ! empty( $res['response']['code'] ) && 200 === $res['response']['code'];
		}

		if ( $entry_created ) {
			/**
			 * If we are here, then it means, we have successfully created a new contact and added it to the
			 * admin selected list.
			 */
			$response['status'] = '1';
			$response['errmsg'] = __( 'You have subscribed successfully!.', 'email-capture-lead-generation' );
		}

		return $response;

	}
}

return new Eclg_Public();
