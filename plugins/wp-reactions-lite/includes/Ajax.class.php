<?php

namespace WP_Reactions\Lite;

use WP_Reactions\Lite\Integrations\Cache;

class Ajax {
	function __construct() {
		$ajax_actions = [
			[
				'name'       => 'wpra_save_options',
				'func'       => [ $this, 'save_options' ],
				'admin_only' => true,
			],
			[
				'name'       => 'wpra_preview',
				'func'       => [ $this, 'preview' ],
				'admin_only' => true,
			],
			[
				'name'       => 'wpra_reset_options',
				'func'       => [ $this, 'reset_option' ],
				'admin_only' => true,
			],
			[
				'name'       => 'wpra_react',
				'func'       => [ $this, 'react' ],
				'admin_only' => false,
			],
			[
				'name'       => 'wpra_submit_feedback',
				'func'       => [ $this, 'submit_feedback' ],
				'admin_only' => true,
			],
			[
				'name'       => 'wpra_get_doc_links',
				'func'       => [ $this, 'get_doc_links' ],
				'admin_only' => false,
			]
		];
		$this->register_ajax_actions( $ajax_actions );
	}

	static function init() {
		return new self();
	}

	function register_ajax_actions( $ajax_actions ) {
		foreach ( $ajax_actions as $action ) {
			$admin_only = isset( $action['admin_only'] ) ? $action['admin_only'] : false;
			$this->register_ajax_action( $action['name'], $action['func'], $admin_only );
		}
	}

	function register_ajax_action( $name, $func, $admin_only = true ) {
		add_action( 'wp_ajax_' . $name, $this->handle($admin_only, $func));

		if ( ! $admin_only ) {
			add_action( 'wp_ajax_nopriv_' . $name, $this->handle($admin_only, $func));
		}
	}

	private function handle($admin_only, $func) {
		return function() use ($admin_only, $func) {
			$checker = $admin_only ? 'wpra-admin-action' : 'wpra-public-action';
			$is_valid_request = check_ajax_referer( $checker, 'checker', false );

			if ( ! $is_valid_request ) {
				echo 'Invalid request';
				wp_die();
			}
	
			if ( $admin_only && !current_user_can( 'manage_options' ) ) {
				echo 'You can not perform this action!';
				wp_die();
			}
	
			call_user_func($func);
	
			wp_die();
		};
	}

	function save_options() {
		$received = json_decode( stripslashes( $_POST['options'] ), true );

		if ( isset( $_POST['single'] ) and $_POST['single'] == 1 ) {
			$save = Config::$current_options;
			foreach ( $received as $opt => $val ) {
				$save[ $opt ] = $val;
			}
		} else {
			$received           = array_replace( Config::$default_options, $received );
			$save               = $received;
			$save['activation'] = Config::$current_options['activation'];
		}

		update_option( WPRA_LITE_OPTIONS, json_encode( $save ) );
	}

	function preview() {
		if ( isset( $_POST['options'] ) ) {
			$options = json_decode( stripslashes( $_POST['options'] ), true );
		} else {
			$options = Config::$default_options;
		}
		$options['post_id'] = 'ajax_preview_lite';
		echo Shortcode::build( $options );
	}

	function reset_option() {
		$defaults               = Config::$default_options;
		$defaults['activation'] = 'true';
		$res                    = update_option( WPRA_LITE_OPTIONS, json_encode( $defaults ) );
		echo $res ? 1 : 0;
	}

	function submit_feedback() {
		$email   = sanitize_email( $_POST['email'] );
		$message = sanitize_text_field( $_POST['message'] );
		$rating  = sanitize_text_field( $_POST['rating'] );

		$resp = wp_remote_post( Config::FEEDBACK_API,
			[
				'method' => 'POST',
				'body'   => [
					'email'   => $email,
					'message' => $message,
					'rating'  => $rating,
					'secure'  => 'daEFZIqbUpouTLibklIVhqyg8XDKHNOW',
				],
			]
		);

		$status_code = wp_remote_retrieve_response_code( $resp );

		if ( is_wp_error( $resp ) and $status_code != 200 ) {
			$result['status']  = 'error';
			$result['message'] = 'Something went wrong';
		} else {
			$result['status']        = 'success';
			$result['message_title'] = __( 'Thank you for your Feedback!', 'wpreactions-lite' );
			$result['message']       = __( 'Your message has been received and we are working on it!', 'wpreactions-lite' );
		}
		echo json_encode( $result );
	}

	function react() {
		global $wpdb;

		$emoji_id           = sanitize_text_field( $_POST['emoji_id'] );
		$post_id            = sanitize_text_field( $_POST['post_id'] );
		$user_id            = get_current_user_id();
		$tbl                = Config::$tbl_reacted_users;
		$react_id           = '';
		$is_already_reacted = 0;

		if ( isset( $_COOKIE['react_id'] ) ) {
			$react_id           = $_COOKIE['react_id'];
			$is_already_reacted = $wpdb->get_var(
				$wpdb->prepare( "SELECT count(*) FROM $tbl WHERE bind_id = %s and react_id = %s", $post_id, $react_id )
			);
		}

		$data = [
			'bind_id'      => $post_id,
			'react_id'     => $react_id,
			'reacted_date' => date( 'Y-m-d H:i:s' ),
			'source'       => 'global',
			'emoji_id'     => $emoji_id,
			'user_id'      => $user_id,
			'sgc_id'       => 0,
		];

		if ( $react_id != '' and $is_already_reacted != 0 ) {
			$response['action'] = 'update';
			$response['status'] = $wpdb->update( Config::$tbl_reacted_users, $data, [ 'react_id' => $react_id, 'bind_id' => $post_id ] ) > 0
				? 'success'
				: 'error';
		} else {
			if ( $react_id == '' ) {
				$data['react_id'] = uniqid();
				setcookie( 'react_id', $data['react_id'], time() + ( 86400 * 365 ), "/" ); // 86400 = 1 day
			}
			$response['action'] = 'insert';
			$response['status'] = $wpdb->insert( Config::$tbl_reacted_users, $data ) > 0
				? 'success'
				: 'error';
		}

		if ( $response['status'] == 'error' ) {
			$response['error_message'] = $wpdb->last_error;
		} else {
			// in case of success reaction clear cache provided by plugins
			Cache::clear( $post_id );
		}

		echo json_encode( $response );
	} // end of react
}
