<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ModuloBox handle ajax requests
 *
 * @class ModuloBox_Async_Request
 * @version	1.0.0
 * @since 1.0.0
 */
class ModuloBox_Async_Request extends ModuloBox_Settings_field {

	/**
	 * Cloning disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __clone() {
	}

	/**
	 * De-serialization disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __wakeup() {
	}

	/**
	 * Initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Init Settings API
		parent::__construct();

		// Handle Ajax requests
		add_action( 'wp_ajax_' . MOBX_NAME . '_ajax_request', array( $this, 'check_ajax_request' ) );

	}

	/**
	 * Check for ajax request type
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function check_ajax_request() {

		$type = isset( $_POST['type'] ) ? $_POST['type'] : null;

		if ( method_exists( $this, $type ) ) {

			$this->$type();

		} else {

			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Sorry, an unknown error occurred.', 'modulobox' ),
			));

		}

	}

	/**
	 * Check refer for admin ajax requests
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int|string $action    Action nonce.
	 * @param string     $query_arg Nonce Key of $_REQUEST
	 */
	public function check_admin_referer( $action = -1, $query_arg = '_wpnonce' ) {

		if ( check_ajax_referer( $action, $query_arg, false ) === false ) {

			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'An error occurred. Please try to refresh the page or logout and login again.', 'modulobox' ),
			));

		}

		if ( ! current_user_can( 'manage_options' ) ) {

			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'You are not allowed to perform this action. Please contact site administrator for further information.', 'modulobox' ),
			));

		}

	}

	/**
	 * Save settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function save_settings() {

		$this->check_admin_referer( MOBX_NAME . '-options', '_wpnonce' );

		$success = false;

		if ( isset( $_POST[ MOBX_NAME ] ) && ! empty( $_POST[ MOBX_NAME ] ) ) {

			// Settings API handles sanitization when updating option (see sanitize_settings method in settings-field.class.php)
			update_option( MOBX_NAME, $_POST[ MOBX_NAME ] );

			$message = __( 'Settings saved!', 'modulobox' );
			$success = true;

		} else {
			$message = __( 'Sorry, an unknown error occurred while saving settings.', 'modulobox' );
		}

		wp_send_json(array(
			'success' => $success,
			'message' => esc_html( $message ),
			'content' => get_option( MOBX_NAME ),
		));

	}

	/**
	 * Preview Lightbox
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function preview_lightbox() {

		$this->check_admin_referer( MOBX_NAME . '-options', '_wpnonce' );

		$success = false;
		$options = null;

		if ( isset( $_POST[ MOBX_NAME ] ) && ! empty( $_POST[ MOBX_NAME ] ) ) {

			// Sanitize settings with WordPress Settings API sanitize callback
			$options   = parent::sanitize_settings( $_POST[ MOBX_NAME ] );

			// Normalize sanitized settings
			$normalize = new ModuloBox_Normalize_Settings( $options );
			$options   = $normalize->get_settings();

			$message = __( 'Opening lightbox preview!', 'modulobox' );
			$success = true;

		} else {
			$message = __( 'Sorry, an unknown error occurred while getting settings.', 'modulobox' );
		}

		wp_send_json(array(
			'success' => $success,
			'message' => esc_html( $message ),
			'content' => $options,
		));

	}
}

new ModuloBox_Async_Request;
