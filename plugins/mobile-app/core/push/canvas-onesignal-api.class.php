<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}
/**
 * Canvas API notifications class
 */
class CanvasOnesignalApi {

	/** @var string Onesignal App Id */
	private $app_id;
	/** @var string Onesignal API Key */
	private $secret_key;
	/** @var string Onesignal API endpoint */
	private $endpoint_url;
	/** @var bool Save push requests and API responses to log file */
	protected $log_enabled;

	public function __construct() {
		$this->load_options();
	}

	/**
	 * Load required options
	 */
	protected function load_options() {
		$this->log_enabled  = Canvas::get_option( 'push_log_enable', false );
		$this->app_id       = Canvas::get_option( 'push_app_id' );
		$this->secret_key   = Canvas::get_option( 'push_key' );
		$this->endpoint_url = 'https://onesignal.com/api/v1/';
	}

	/**
	 * Send push notification
	 *
	 * @param array      $data
	 * @param array      $tagNames
	 * @param array|null $user_ids
	 * @return bool|string true if succsess, error messages otherwise
	 */
	public function send_batch_notification( $data, $tagNames = array(), $user_ids = null ) {
		$fields = array(
			'app_id'            => $this->app_id,
			'ios_badgeType'     => 'Increase',
			'ios_badgeCount'    => 1,
			'content_available' => true,
		);
		if ( isset( $data['platform'] ) ) {
			if ( in_array( 0, $data['platform'] ) ) {
				$fields['isIos'] = true;
			}
			if ( in_array( 1, $data['platform'] ) ) {
				$fields['isAndroid'] = true;
			}
		}
		// title
		if ( ! empty( $data['title'] ) ) {
			$fields['headings'] = array( 'en' => html_entity_decode( stripslashes( $data['title'] ) ) );
		}
		// message
		$fields['contents'] = array( 'en' => html_entity_decode( stripslashes( $data['msg'] ) ) );

		$fields['excluded_segments'] = array( 'unsubscribed' );

		if ( ! empty( $data['users'] ) ) { // users
			$fields['target_channel'] = "push";
			$fields['include_aliases'] = array(
				'external_id' => $data['users'],
			);
		} elseif ( ! empty( $data['tags'] ) ) { // tags
			$filters = array();
			foreach ( $data['tags'] as $value ) {
				if ( ! empty( $filters ) ) {
					$filters[] = array( 'operator' => 'OR' );
				}
				$filters[] = array(
					'field'    => 'tag',
					'key'      => strtolower( $value ),
					'relation' => '=',
					'value'    => 'on',
				);
			}
			$fields['filters'] = $filters;
		} else {
			// Segment "All" to use when we send with no tags
			$fields['included_segments'] = array( 'All' );
		}

		// payload
		if ( ! empty( $data['payload'] ) ) {
			$fields['data'] = array();
			if ( isset( $data['payload']['post_id'] ) ) {
				$data['payload']['url'] = get_permalink( intval( $data['payload']['post_id'] ) ); // we want to send url only, but save both, url and post id
			}
			if ( isset( $data['payload']['featured_image'] ) ) {
				$fields['big_picture']         = $data['payload']['featured_image'];
				$fields['ios_attachments']     = array( 'id1' => $data['payload']['featured_image'] );
				$fields['data']['large_image'] = $data['payload']['featured_image'];
			}
			if ( isset( $data['payload']['url'] ) ) {
				$fields['data']['url'] = $data['payload']['url'];
			}
		}

		$json_data = wp_json_encode( $fields, JSON_UNESCAPED_UNICODE );

		$headers = array(
			'Content-Type'   => 'application/json; charset=uft-8',
			'Authorization'  => 'Basic ' . $this->secret_key,
			'Content-Length' => strlen( $json_data ),
		);
		$url     = $this->endpoint_url . 'notifications';

		$request    = new WP_Http();
		$parameters = array(
			'timeout'   => 10,
			'headers'   => $headers,
			'sslverify' => false,
			'body'      => $json_data,
		);
		$result     = $request->post( $url, $parameters );
		if ( $this->log_enabled ) {
			// hide secret key value
			$parameters['headers']['Authorization'] = 'Basic *****';
			$this->save_log( $url, $parameters, $result );
		}
		$error = false;
		if ( ! empty( $result ) && ! is_wp_error( $result ) && isset( $result['body'] ) ) {
			$result = json_decode( $result['body'], true );
			if ( ! empty( $result['id'] ) ) {
				$this->save_as_sent_message( $data, $tagNames, $user_ids );
				return true;
			} elseif ( ! empty( $result['errors'] ) || ! empty( $result['warnings'] ) ) {
				$messages = ! empty( $result['errors'] ) ? $result['errors'] : array();
				if ( ! empty( $result['warnings'] ) ) {
					$messages = array_merge( $messages, $result['warnings'] );
				}
				$messages = array_unique( $messages );
				foreach ( $messages as $key => $text ) {
					if ( 'All included players are not subscribed' == $text ) {
						$messages[ $key ] = "There's no users meeting the criteria used, this includes the categories in any post attached and/or your selection of platforms.";
					}
				}
				return implode( '<br>', $messages );
			}
		}
		return 'There was an error sending this notification';
	}

	/**
	 * Save request & response to log file. This helps to debug any issues with push notifications.
	 *
	 * @param string $url
	 * @param mixed  $parameters
	 * @param mixed  $result
	 */
	public function save_log( $url, $parameters, $result ) {
		if ( $this->log_enabled ) {

			if (is_array($parameters)) {
				// remove extra log data
				if (isset($parameters['headers'])) {
					unset($parameters['headers']);
				}

				if (isset($parameters['timeout'])) {
					unset($parameters['timeout']);
				}

				if (isset($parameters['sslverify'])) {
					unset($parameters['sslverify']);
				}
			}

			$log    = array(
				'timestamp' => current_time( 'timestamp' ),
				'url'       => $url,
				'params'    => $parameters,
			);

			if (isset($result['body'])) {
				$result_body = json_decode( $result['body'], true );
				if (isset($result_body['warnings'])) {
					$result_body['warnings'] = array_unique( $result_body['warnings'] );
					$log['result'] = json_encode( $result_body );
				} else {
					$log['result'] = $result['body'];
				}
			}

			$string = "\r\n" . date( 'Y-m-d H:i:s' ) . "\t" . var_export( $log, true );

			$old_file_content = file_get_contents( CanvasAdmin::get_push_log_name() );
			file_put_contents( CanvasAdmin::get_push_log_name(), $string . $old_file_content );
		}
	}

	/**
	 * Store message as sent
	 *
	 * @param array      $data
	 * @param array      $tagNames
	 * @param array|null $user_ids
	 */
	protected function save_as_sent_message( $data, $tagNames, $user_ids ) {
		$values = array(
			'time'    => current_time( 'timestamp' ),
			'post_id' => isset( $data['payload']['post_id'] ) ? $data['payload']['post_id'] : null,
			'url'     => isset( $data['payload']['url'] ) ? $data['payload']['url'] : null,
			'title'   => isset( $data['title'] ) && is_string( $data['title'] ) ? $data['title'] : '',
			'msg'     => $data['msg'],
			'android' => is_array( $data['platform'] ) && in_array( 1, $data['platform'] ) ? 'Y' : 'N',
			'ios'     => is_array( $data['platform'] ) && in_array( 0, $data['platform'] ) ? 'Y' : 'N',
			'tags'    => count( $tagNames ) > 0 ? implode( ',', $tagNames ) : '',
			'private' => is_array( $user_ids ) && count( $user_ids ),
		);
		CanvasNotifications::save_sent_message( $values );
	}

	/**
	 * Call API for registered devices count
	 */
	protected function registered_devices() {
		$fields    = array(
			'app_id' => $this->app_id,
			'limit'  => 1,
			'offset' => 0,
		);
		$json_data = json_encode( $fields );

		$headers = array(
			'Content-Type'  => 'application/json; charset=uft-8',
			'Authorization' => 'Basic ' . $this->secret_key,
		);
		$url     = $this->endpoint_url . 'players?app_id=' . $this->app_id . '&limit=1&offset=0';

		$request    = new WP_Http();
		$parameters = array(
			'timeout'   => 10,
			'headers'   => $headers,
			'sslverify' => false,
		);
		$result     = $request->get( $url, $parameters );

		$count = null;
		if ( ! empty( $result ) && ! is_wp_error( $result ) && isset( $result['body'] ) ) {
			$result = json_decode( $result['body'], true );
			if ( ! empty( $result['total_count'] ) ) {
				$count = $result['total_count'];
			}
		}
		return $count;
	}

	/**
	 * Return count of devices (all, ios, android).
	 */
	public function registered_devices_count() {
		$total = $this->registered_devices();
		if ( is_null( $total ) ) {
			$total = Canvas::get_option( 'push_count_total', '' );
			if ( '' === $total ) {
				$total = null;
			}
		} else {
			Canvas::set_option( 'push_count_total', $total );
		}
		return array(
			'total'   => $total,
			'ios'     => null,
			'android' => null,
		);
	}
}
