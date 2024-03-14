<?php

/**
 * Responsible for setting up AJAX functionality
 */
class Eprolo_Pod_Ajax extends Eprolo_Pod_Options_Manager {

	public function textAction( $nonce ) {
		return $nonce;
	}

	public function eprolo_pod_disconnect() {

		if (!isset( $_POST['eprolo_pod_nonce_field'] ) || !wp_verify_nonce( sanitize_text_field($_POST['eprolo_pod_nonce_field']), 'eprolo_pod_form_action' )) {
			wp_send_json_success(array( 'msg'  => 'Please refresh the page', 'code' => '-1' ));
			exit;
		}

		if ( isset( $_POST['eprolo_pod_user_id'] ) && sanitize_key( $_POST['eprolo_pod_user_id'] ) ) {
			$user_id = sanitize_text_field( wp_unslash( $_POST['eprolo_pod_user_id'] ) );
		}

		$this->updateOption( 'eprolo_pod_user_id', '' );
		$this->updateOption( 'eprolo_pod_store_token', '' );
		$this->updateOption( 'eprolo_pod_connected', '0' );
		//Invoking the logout method
		$url    = 'prod-api/woocommerce/eprolo_pod_disconnect.html?user_id=' . $user_id;
		$result = $this->curlPost( $url );
		$arr    = json_decode( $result );
		$code   = $arr->code;
		$msg    = $arr->msg;
		
		if ( '0' == $code ) {
			$this->updateOption( 'eprolo_pod_user_id', '' );
			$this->updateOption( 'eprolo_pod_store_token', '' );
			$this->updateOption( 'eprolo_pod_connected', '0' );
			//delete key delete authorized key information, including wp_woocommerce_api_keys of eprolo
			$status = $arr->data->status;
			global $wpdb;
			if ( '1' == $status || '2' == $status ) {
				$wpdb->query(
					"DELETE
							                FROM {$wpdb->prefix}woocommerce_api_keys
							                WHERE description LIKE '%eprolo_pod%'
							              "
				);
			}
			if ( '1' == $status || '3' == $status ) {
				$wpdb->query(
					"DELETE
							                FROM {$wpdb->prefix}wc_webhooks
							                WHERE name LIKE '%eprolo_pod%'
							              "
				);

			}
		}
		wp_send_json_success(
			array(
				'msg'  => $msg,
				'code' => $code,
			)
		);
	}

	public function eprolo_pod_reflsh() {


		if (!isset( $_POST['eprolo_pod_nonce_field'] ) || !wp_verify_nonce( sanitize_text_field($_POST['eprolo_pod_nonce_field']), 'eprolo_pod_form_action' )) {
			wp_send_json_success(array( 'msg'  => 'Please refresh the page', 'code' => '-1' ));
			exit;
		}

		if ( isset( $_POST['user_id'] ) && sanitize_key( $_POST['user_id'] ) ) {
			$user_id = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
		}
		if ( isset( $_POST['domain'] ) && sanitize_key( $_POST['domain'] ) ) {
			$domain = sanitize_text_field( wp_unslash( $_POST['domain'] ) );
		}
		if ( isset( $_POST['eprolo_pod_store_token'] ) && sanitize_key( $_POST['eprolo_pod_store_token'] ) ) {
			$eprolo_pod_store_token = sanitize_text_field( wp_unslash( $_POST['eprolo_pod_store_token'] ) );
		}
				  //Call server
		$url    = 'prod-api/woocommerce/eprolo_pod_reflsh.html?user_id=' . $user_id . '&domain=' . $domain . '&token=' . $eprolo_pod_store_token;
		$result = $this->curlPost( $url );
		$arr    = json_decode( $result );
		$code   = $arr->code;
		$msg    = $arr->msg;
		if ( '0' == $code ) {
			$token = $arr->data->token;
			 $this->updateOption( 'eprolo_pod_store_token', $token );
			 $this->updateOption( 'eprolo_pod_connected', '1' );
			 $this->updateOption( 'eprolo_pod_shop_url', $domain );
						wp_send_json_success(
							array(
								'msg'  => $msg,
								'code' => $code,
							)
						);
		} else {
				wp_send_json_success(
					array(
						'msg'  => $msg,
						'code' => $code,
					)
				);
		}
	}

	public function eprolo_pod_connect_key() {

		if (!isset( $_POST['eprolo_pod_nonce_field'] ) || !wp_verify_nonce( sanitize_text_field($_POST['eprolo_pod_nonce_field']), 'eprolo_pod_form_action' )) {
			wp_send_json_success(array( 'msg'  => 'Please refresh the page', 'code' => '-1' ));
			exit;
		}

		if ( isset( $_POST['user_id'] ) && sanitize_key( $_POST['user_id'] ) ) {
			$user_id = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
		}
		if ( isset( $_POST['domain'] ) && sanitize_key( $_POST['domain'] ) ) {
			$domain = sanitize_text_field( wp_unslash( $_POST['domain'] ) );
		}
		$url    = 'prod-api/woocommerce/eprolo_pod_connect_key.html?user_id=' . $user_id . '&domain=' . $domain;
		$result = $this->curlPost( $url );
		$arr    = json_decode( $result );
		$code   = $arr->code;
		$msg    = $arr->msg;
		if ( '0' == $code ) {
			$token   = $arr->data->token;
			$user_id = $arr->data->user_id;
			 $this->updateOption( 'eprolo_pod_user_id', $user_id );
			 $this->updateOption( 'eprolo_pod_connected', '1' );
			 $this->updateOption( 'eprolo_pod_shop_url', $domain );
			wp_send_json_success(
				array(
					'msg'  => $msg,
					'code' => $code,
				)
			);

		} else {
			wp_send_json_success(
				array(
					'msg'  => $msg,
					'code' => $code,
				)
			);
		}
	}


}
