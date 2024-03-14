<?php
/**
 * Google Classroom setting Model.
 *
 * @package Masteriyo\GoogleCLassroom
 *
 * @since 1.8.3
 */

namespace Masteriyo\Addons\GoogleClassroomIntegration\Models;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo Setting Model.
 *
 * @class Masteriyo\Setting
 */
class GoogleClassroomSetting {
	/**
	 * Setting option name.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	private $name = 'google-classroom-settings';

		/**
	 * Post type.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $post_type = 'mto-google-classroom-settings';

	/**
	 * Setting User Id.
	 *
	 * @since 1.8.3
	 *
	 * @var integer
	 */
	public $user_id = 0;

	/**
	 * Setting data.
	 *
	 * @since 1.8.3
	 *
	 * @var array
	 */
	private $data = array(
		'client_id'     => '',
		'client_secret' => '',
		'access_code'   => false,
		'refresh_token' => '',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.8.3
	 */
	public function __construct() {
		$this->read();
	}

	/**
	 * Return data.
	 *
	 * @since 1.8.3
	 *
	 * @return array
	 */
	public function get_data() {
			$data = get_user_meta( get_current_user_id(), $this->name, true );
			return $data;
	}

	/**
	 * Store client settings.
	 *
	 * @since 1.8.3
	 *
	 * @param Model         $setting  Object object.
	 * @param WP_REST_Request $request  Request object.
	 * @param bool            $creating If is creating a new object.
	 */
	public function save() {
		update_user_meta( get_current_user_id(), $this->name, $this->data );
	}

	/**
	 * Read the settings from database.
	 *
	 * @since 1.8.3
	 *
	 */
	public function read() {
		$data       = get_user_meta( get_current_user_id(), $this->name, true );
		$this->data = wp_parse_args( $data, $this->data );
	}

	/**
	 * Return setting value.
	 *
	 * @since 1.8.3
	 * @param string $key Setting key.
	 * @param string $default Setting default value.
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		return masteriyo_array_get( $this->get_data(), $key, $default );
	}

	/**
	 * Save setting value.
	 *
	 * @since 1.8.3
	 *
	 * @param string $key Setting key.
	 * @param mixed $value Setting default.
	 */
	public function set( $key, $value ) {
		masteriyo_array_set( $this->data, $key, $value );
	}
}
