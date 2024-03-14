<?php
/**
 * Settings Model.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Model_Settings extends Base_Model {

	/**
	 * Instance
	 *
	 * @var Model_Settings
	 */
	public static $instance = null;

	/**
	 * Class instance
	 *
	 * @return Model_Settings
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function update_theme( \WP_REST_Request $request ) {
		$data = $request->get_params();
		if ( isset( $data['theme'] ) ) {
			update_option( 'stax_editor_theme', $data['theme'] );

			return $this->response( self::STATUS_OK );
		}

		return $this->response( self::STATUS_FAILED );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function update_colors( \WP_REST_Request $request ) {
		$data = $request->get_params();
		if ( isset( $data['colors'] ) ) {
			update_option( 'stax_color_picker', $data['colors'] );

			return $this->response( self::STATUS_OK );
		}

		return $this->response( self::STATUS_FAILED );
	}
}
