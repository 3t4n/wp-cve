<?php
/**
 * Mailchimp Api Client
 *
 * @package AbsoluteAddons
 * @author Kudratullah <mhamudul.hk@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */

namespace AbsoluteAddons;

use InvalidArgumentException;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

class MailChimp {

	/**
	 * Client Version.
	 * @var string
	 */
	protected $version = '1.0.0';

	/**
	 * API Host.
	 * @var string
	 */
	protected $host = 'https://server.api.mailchimp.com/3.0';

	/**
	 * Request User Agent.
	 * @var string
	 */
	protected $user_agent;

	/**
	 * @var string
	 */
	private $api_key;

	/**
	 * API Key.
	 *
	 * @var string|bool
	 */
	private $key = false;

	/**
	 * Mailchimp DC Prefix.
	 *
	 * @var string|bool
	 */
	private $prefix = false;

	/**
	 *
	 * @var array
	 */
	private $audience_list;

	/**
	 *
	 * @var string
	 */
	private $active_list = '';

	protected $default_data = [
		'audience_list' => [],
		'default_list'  => '',
		'api_key'       => '',
	];

	public function __construct( $api_key = '', $load = false ) {
		$this->user_agent = 'WordPress/' . get_bloginfo( 'version' ) . ' (Plugin; Client ' . $this->version . ') AbsoluteAddons/' . ABSOLUTE_ADDONS_VERSION;

		if ( absp_has_pro() ) {
			$this->user_agent .= ' Pro/' . ABSOLUTE_ADDONS_PRO_VERSION;
		}

		$this->api_key = $api_key;

		if ( $load ) {
			$this->load();
		}
	}

	public function load( $key = '' ) {

		if ( ! $key ) {
			$key = $this->api_key;
		}

		if ( strlen( $key ) < 36 ) {
			throw new InvalidArgumentException( 'Invalid (Malformed/Missing) API Key', 503 );
		}

		$key = explode( '-', $key );
		if ( 2 !== count( $key ) ) {
			throw new InvalidArgumentException( 'Invalid API Key', 501 );
		}

		if ( 32 !== strlen( $key[0] ) ) {
			throw new InvalidArgumentException( 'Invalid API Key', 501 );
		}

		if ( strlen( $key[1] ) < 2 ) {
			throw new InvalidArgumentException( 'Invalid API Key', 503 );
		}

		$this->key    = $key[0];
		$this->prefix = $key[1];

		$this->host = str_replace( 'server', $this->prefix, $this->host );

		return $this;
	}

	public function has_key() {
		return $this->key && $this->prefix;
	}

	public function set_active_list( $id ) {
		$this->active_list = $id;

		return $this;
	}

	public function get_active_list() {
		return $this->active_list;
	}

	public function set_audience_list( $list = [] ) {
		if ( empty( $list ) ) {
			$list = $this->fetch_audience_list();
		}

		$this->audience_list = $list;

		return $this;
	}

	public function get_audience_list() {
		return $this->audience_list;
	}

	public function subscribe( $email, $first_name = '', $last_name = '' ) {

		if ( ! $this->active_list ) {
			return new WP_Error( 'invalid-list', __( 'Audience List Not Set!', 'absolute-addons' ) );
		}

		if ( ! is_email( $email ) ) {
			return new WP_Error( 'invalid-email', __( 'Invalid Email Address!', 'absolute-addons' ) );
		}

		$lang = get_user_locale();
		$lang = explode( '_', $lang );

		$audience = [
			'email_address'    => 'human.' . $email,
			'status'           => 'subscribed',
			// "subscribed", "unsubscribed", "cleaned", "pending", or "transactional".
			'language'         => $lang[0],
			'ip_signup'        => absp_get_unsafe_client_ip(),
			'timestamp_signup' => gmdate( 'Y-m-d\TH:i:sO', absp_get_wp_time() ),
			'opt_in'           => absp_get_unsafe_client_ip(),
			'timestamp_opt'    => gmdate( 'Y-m-d\TH:i:sO', absp_get_wp_time() ),
		];

		if ( $first_name || $last_name ) {
			if ( $first_name ) {
				$audience['merge_fields']['FNAME'] = $first_name;
				$audience['merge_fields']['LNAME'] = $last_name;
			}
		}

		$data = $this->request( 'lists/' . $this->active_list . '/members', $audience, 'POST' );

		if ( is_wp_error( $data ) ) {
			if ( 'member-exists' === $data->get_error_code() ) {
				return new WP_Error( 'member-exists', __( 'Already Subscribed', 'absolute-addons' ), $data->get_error_data() );
			}

			return $data;
		}

		return new MC_Member( $data );
	}

	public function fetch_members() {

		$this->active_list = '9f837b4f68';

		if ( ! $this->active_list ) {
			return new WP_Error( 'invalid-list', __( 'Audience List Not Set!', 'absolute-addons' ) );
		}

		$data = $this->request( 'lists/' . $this->active_list . '/members' );

		if ( is_wp_error( $data ) ) {
			if ( 'member-exists' === $data->get_error_code() ) {
				return new WP_Error( 'member-exists', __( 'Already Subscribed', 'absolute-addons' ), $data->get_error_data() );
			}

			return $data;
		}

		return $data->members;
	}

	public function fetch_audience_list() {
		$data = $this->request( 'lists', [ 'fields' => 'lists.id,lists.name' ] );

		return is_wp_error( $data ) ? $data : $data->lists;
	}

	public function ping() {
		$data = $this->request( 'ping' );

		if ( ! is_wp_error( $data ) ) {
			return new MC_Ping( $data );
		}

		return $data;
	}

	protected function request( $path, $data = [], $method = 'GET' ) {

		if ( ! $this->has_key() ) {
			return new WP_Error( 'no-api-key', __( 'No/Invalid API Key', 'absolute-addons' ) );
		}

		$path   = rtrim( ltrim( $path, '/' ), '/' );
		$method = strtoupper( $method );

		//'Authorization' => 'Bearer AccessToken',
		$args = [
			'headers'    => [
				'Accept'        => 'application/json,application/problem+json',
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( 'user:' . $this->key ),
			],
			//'timeout'    => 15,
			'blocking'   => true,
			'User-Agent' => $this->user_agent,
			'body'       => 'POST' === $method ? wp_json_encode( $data ) : $data,
		];

		if ( 'GET' === $method ) {
			$response = wp_safe_remote_get( $this->host . '/' . $path, $args );
		} elseif ( 'POST' === $method ) {
			$response = wp_safe_remote_post( $this->host . '/' . $path, $args );
		} else {
			$args['method'] = $method;
			$response       = wp_safe_remote_request( $this->host . '/' . $path, $args );
		}

		if ( is_wp_error( $response ) ) {
			return $response;
		} else {
			$response_code = wp_remote_retrieve_response_code( $response );
			if ( 200 === $response_code ) {
				$data       = json_decode( wp_remote_retrieve_body( $response ) );
				$class_name = 'MC_' . ucfirst( $path );
				if ( class_exists( $class_name ) ) {
					$data = new $class_name( $data );
				}

				return $data;
			} else {
				$headers = wp_remote_retrieve_headers( $response );
				$data    = false;
				if ( false !== strpos( $headers['Content-Type'], 'application/problem+json' ) ) {
					$data = wp_remote_retrieve_body( $response );
					if ( ! empty( $data ) ) {
						$data = json_decode( $data );
					}
				}

				if ( ! $data ) {
					$data = (object) [
						'type'     => 'Unknown Error',
						'title'    => __( 'Something Went Wrong', 'absolute-addons' ),
						'status'   => $response_code,
						'detail'   => __( 'Something Went Wrong. Please Try after sometime.', 'absolute-addons' ),
						'instance' => '',
					];
					switch ( $response_code ) {
						case 429:
						case 403:
							$data->type   = __( 'Too Many Requests', 'absolute-addons' );
							$data->detail = __( 'Too Many Requests. Please Try after sometime.', 'absolute-addons' );
							break;
						case 500:
							$data->type   = __( 'Internal Server Error', 'absolute-addons' );
							$data->detail = __( 'Something Went Wrong. Please Try after sometime.', 'absolute-addons' );
							break;
					}
				}

				return new WP_Error( sanitize_title( $data->title ), $data->detail, $data );
			}
		}
	}
}

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

abstract class MC_Data {

	public function __construct( $data ) {
		foreach ( $data as $k => $v ) {
			$this->{$k} = $v;
		}
	}
}

class MC_Ping extends MC_Data {

	/**
	 * @var string;
	 */
	public $health_status;
}

class MC_List extends MC_Data {

	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	public $name;

	//  "id": "string",
	//  "name": "string",
}

class MC_Member extends MC_Data {

	public $id = '';
	public $name = '';
	public $email_address = '';
	public $unique_email_id = '';
	public $full_name = '';
	public $web_id = '';
	public $email_type = 'html';
	public $status = '';
	public $merge_fields;
//		[
//			'FNAME'    => '',
//			'LNAME'    => '',
//			'ADDRESS'  => '',
//			'PHONE'    => '',
//			'BIRTHDAY' => '',
//		]
	public $stats;
//		[
//			'avg_open_rate'  => 0,
//			'avg_click_rate' => 0,
//		];
	public $ip_signup = '';
	public $timestamp_signup = '';
	public $ip_opt = '';
	public $timestamp_opt = '';
	public $member_rating = 0;
	public $last_changed = '';
	public $language = '';
	public $vip = false;
	public $email_client = '';
	public $location;
//		[
//			'latitude'     => 0,
//			'longitude'    => 0,
//			'gmtoff'       => 0,
//			'dstoff'       => 0,
//			'country_code' => 0,
//			'timezone'     => 0,
//		];
	public $source = '';
	public $tags_count = 0;
	public $tags = [];
	public $list_id = '';
	public $_links = [];
}

// phpcs:enable

// End of file class-mailchimp.php.
