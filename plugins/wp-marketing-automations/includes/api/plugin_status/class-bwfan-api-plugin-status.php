<?php
// using wpmailsmtp options class to check the configuration status
use WPMailSMTP\Options;

/**
 * Class BWFAN_API_Plugin_Status
 */
class BWFAN_API_Plugin_Status extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/plugin/status';
	}

	/**
	 * Checking the plugin installation and activation status
	 */
	public function process_api_call() {
		// plugin - plugin_folder/main_file
		$plugin     = isset( $this->args['plugin'] ) ? $this->args['plugin'] : '';
		$pro_plugin = isset( $this->args['plugin_pro'] ) ? $this->args['plugin_pro'] : '';

		if ( empty( $plugin ) && empty( $pro_plugin ) ) {
			return $this->error_response( __( 'Plugin name is missing', 'wp-marketing-automations' ) );
		}

		$install_status   = false;
		$active_status    = false;
		$configure_status = false;

		// fetching all the installed plugins from the site
		$all_plugins = get_plugins();

		// return with status false if not installed
		if ( ! array_key_exists( $plugin, $all_plugins ) && ! array_key_exists( $pro_plugin, $all_plugins ) ) {
			return $this->success_response( [
				'installed'  => $install_status,
				'activated'  => $active_status,
				'configured' => $configure_status
			], __( 'Plugin is not installed', 'wp-marketing-automations' ) );
		}

		$install_status = true;
		if ( is_plugin_active( $plugin ) || is_plugin_active( $pro_plugin ) ) {
			$active_status = true;

			// fetching configure data of wp-mail-smtp
			// although class will be available as plugin is activated, but just making sure that no fatal error occurred due to class doesn't exist,
			if ( class_exists( 'WPMailSMTP\Options' ) ) {
				$default_options = wp_json_encode( Options::get_defaults() );
				$current_options = wp_json_encode( Options::init()->get_all() );

				// Check if the current settings are the same as the default settings.
				if ( $current_options !== $default_options ) {
					$configure_status = true;
				}
			}
		}

		$this->response_code = 200;

		return $this->success_response( [ 'installed' => $install_status, 'activated' => $active_status, 'configured' => $configure_status ], __( 'Plugin status', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Plugin_Status' );
