<?php
/**
 * Plugin Name: Ziina
 * Description: Accept payments via Ziina
 * Author: Ziina
 * Author URI: https://ziina.com/
 * Text Domain: ziina
 * Domain Path: /languages
 * WC requires at least: 4.8
 * WC tested up to: 6.2.1
 * Requires at least: 5.7
 * Requires PHP: 7.2
 * Version: 1.2.1
 *
 * @package ZiinaPayment
 */

namespace ZiinaPayment;

defined( 'ABSPATH' ) || exit();

/**
 * Class Main
 *
 * @package ZiinaPayment
 * @since   1.0.0
 */
class Main {
	/**
	 * Class instance
	 *
	 * @var Main|null
	 */
	private static $instance = null;

	/**
	 * Plugin id
	 *
	 * @var string
	 */
	public $plugin_id = 'ziina';

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Plugin url
	 *
	 * @var string
	 */
	public $plugin_url;

	/**
	 * Plugin path
	 *
	 * @var string
	 */
	public $plugin_path;

	/**
	 * Assets url
	 *
	 * @var string
	 */
	public $assets_url;

	/**
	 * Settings array
	 *
	 * @var array|null
	 */
	private $settings = null;

	/**
	 * Admin class instance
	 *
	 * @var Api\Main|null
	 */
	private $api = null;

	/**
	 * Admin class instance
	 *
	 * @var Ajax\Main|null
	 */
	private $ajax;

	/**
	 * Main constructor.
	 */
	private function __construct() {
		$this->plugin_url  = plugin_dir_url( __FILE__ );
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->assets_url  = $this->plugin_url . '/assets/';

		require_once 'vendor/autoload.php';

		load_plugin_textdomain( 'ziina', false, $this->plugin_path . 'languages/' );

		add_action( 'plugins_loaded', array( $this, 'action_plugins_loaded' ) );

		register_activation_hook( __FILE__, array( $this, 'activation_hook' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation_hook' ) );

		add_filter( 'woocommerce_payment_gateways', array( $this, 'filter_add_payment_gateway' ) );

		$this->ajax = new Ajax\Main();
	}

	/**
	 * Get api class instance.
	 *
	 * @return Api\Main
	 */
	public function api(): Api\Main {
		if ( is_null( $this->api ) ) {
			$this->api = new Api\Main();
		}

		return $this->api;
	}

	/**
	 * Get Ajax class instance
	 *
	 * @return Ajax\Main
	 */
	public function ajax(): Ajax\Main {
		return $this->ajax;
	}

	/**
	 * Disable plugin if Woocommerce not active
	 */
	public function action_plugins_loaded() {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			$this->disable_plugin();
			return;
		}

		new Integrations\Main();
	}

	/**
	 * Add plugin gateway to Woo gateways list
	 *
	 * @param array $gateways Array of gateway classes or class names.
	 *
	 * @return array
	 */
	public function filter_add_payment_gateway( array $gateways ): array {
		$gateways[] = Gateway::class;

		return $gateways;
	}

	/**
	 * Activation hook
	 */
	public function activation_hook() {
	}

	/**
	 * Deactivation hook
	 */
	public function deactivation_hook() {
	}

	/**
	 * Get Gateway setting
	 *
	 * @param string $name Setting key.
	 *
	 * @return mixed
	 */
	public function get_setting( string $name ) {
		if ( is_null( $this->settings ) ) {
			$gateway = $this->gateway();

			if ( empty( $gateway ) ) {
				$this->log( 'Get_settings without gateway' );

				return null;
			}

			$this->settings = array();

			$is_test = $gateway->get_option( 'is_test' ) === 'yes';

			$this->settings['gateway_id']          = $gateway->id;
			$this->settings['enabled']             = $gateway->get_option( 'enabled' ) === 'yes';
			$this->settings['authorization_token'] = $gateway->get_option( 'authorization_token' );
			$this->settings['is_test']             = $is_test;
			$this->settings['logging']             = $is_test || $gateway->get_option( 'logging' ) === 'yes';
		}

		return $this->settings[ $name ] ?? null;
	}

	/**
	 * Get plugin gateway instance.
	 *
	 * @return Gateway|null Gateway instance.
	 */
	public function gateway(): ?Gateway {
		return WC()->payment_gateways()->payment_gateways()[ $this->plugin_id ] ?? null;
	}

	/**
	 * Wrapper of wc_get_template function
	 *
	 * @param string $template Template name.
	 * @param array  $args     Arguments.
	 * @param bool   $return   Return or echo. Echo by default.
	 *
	 * @return bool|string
	 */
	public function include_template( string $template, $args = array(), $return = false ) {
		if ( $return ) {
			ob_start();
		}

		wc_get_template(
			$template,
			$args,
			'',
			$this->plugin_path . 'templates/'
		);

		if ( $return ) {
			return ob_get_clean();
		}

		return true;
	}

	/**
	 * Disable plugin
	 */
	public function disable_plugin() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		deactivate_plugins( $this->plugin_id . '/index.php' );
	}

	/**
	 * Add data to Woo logs
	 *
	 * @param array|string $data        Data to add to logs.
	 * @param string       $code_source Source of log in code.
	 */
	public function log( $data, string $code_source = '' ) {
		if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || $this->settings['logging'] ) {
			if ( empty( $code_source ) ) {
				$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 )[1]; //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace

				$code_source  = isset( $backtrace['class'] ) ? $backtrace['class'] . '::' : '';
				$code_source .= $backtrace['function'] ?? '';
			}

			$data = array(
				'source' => $code_source,
				'data'   => $data,
			);

			wc_get_logger()->debug( print_r( $data, true ), array( 'source' => $this->plugin_id ) );// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}
	}

	/**
	 * Get singleton instance
	 *
	 * @return Main
	 */
	public static function get_instance(): Main {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

require_once 'main-class-shortcut.php';

ziina_payment();
