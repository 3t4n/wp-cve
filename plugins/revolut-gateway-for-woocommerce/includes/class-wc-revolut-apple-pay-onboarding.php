<?php
/**
 * Revolut Apple Pay Merchant On-boarding Class.
 *
 * @package    WooCommerce
 * @category   Payment Gateways
 * @author     Revolut
 * @since      3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Revolut_Apple_Pay_OnBoarding class.
 */
class WC_Revolut_Apple_Pay_OnBoarding {

	use WC_Revolut_Logger_Trait;

	/**
	 * Onboarding file name.
	 *
	 * @var string
	 */
	public $domain_onboarding_file_name;

	/**
	 * Onboarding file root directory.
	 *
	 * @var string
	 */
	public $onboarding_file_dir;

	/**
	 * Onboarding file Path.
	 *
	 * @var string
	 */
	public $onboarding_file_path;

	/**
	 * Onboarding file directory.
	 *
	 * @var string
	 */
	public $domain_onboarding_file_directory;

	/**
	 * Onboarding file remote link.
	 *
	 * @var string
	 */
	public $domain_onboarding_file_remote_link;

	/**
	 * Domain name.
	 *
	 * @var string
	 */
	private $domain_name;

	/**
	 * Error message.
	 *
	 * @var string
	 */
	public $error_messages;

	/**
	 * Success message.
	 *
	 * @var string
	 */
	public $success_messages;

	/**
	 * API settings
	 *
	 * @var WC_Revolut_Settings_API
	 */
	public $api_settings;

	/**
	 * Revolut payment request setting.
	 *
	 * @var array
	 */
	public $revolut_payment_request_settings;

	/**
	 * API client
	 *
	 * @var WC_Revolut_API_Client
	 */
	public $api_client;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->api_settings = revolut_wc()->api_settings;
		$this->api_client   = new WC_Revolut_API_Client( $this->api_settings, true );

		add_action( 'admin_init', array( $this, 'maybe_onboard_apple_pay_merchant' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'add_option_woocommerce_revolut_settings', array( $this, 'on_revolut_options_update' ), 10, 2 );
		add_action( 'update_option_woocommerce_revolut_settings', array( $this, 'on_revolut_options_update' ), 10, 2 );

		add_action(
			'add_option_woocommerce_revolut_payment_request_settings',
			array( $this, 'maybe_onboard_apple_pay_merchant' ),
			10,
			2
		);
		add_action(
			'update_option_woocommerce_revolut_payment_request_settings',
			array( $this, 'on_revolut_payment_request_options_update' ),
			10,
			2
		);

		$this->domain_onboarding_file_name        = 'apple-developer-merchantid-domain-association';
		$this->domain_onboarding_file_directory   = '.well-known';
		$this->onboarding_file_dir                = untrailingslashit( ABSPATH ) . '/' . $this->domain_onboarding_file_directory;
		$this->onboarding_file_path               = $this->onboarding_file_dir . '/' . $this->domain_onboarding_file_name;
		$this->domain_onboarding_file_remote_link = 'https://assets.revolut.com/api-docs/merchant-api/files/apple-developer-merchantid-domain-association';

		$this->revolut_payment_request_settings = get_option( 'woocommerce_revolut_payment_request_settings', array() );

		$this->domain_name = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : str_replace(
			array(
				'https://',
				'http://',
			),
			'',
			get_site_url()
		); // phpcs:ignore

		$this->error_messages = array();

		$this->success_messages = array();
	}

	/**
	 * Try to onboard after Revolut API settings updated.
	 *
	 * @param array $old_options old options.
	 * @param array $new_options new options.
	 */
	public function on_revolut_options_update( $old_options, $new_options ) {
		$this->api_settings = revolut_wc()->api_settings;
		$this->api_client   = new WC_Revolut_API_Client( $this->api_settings, true );
		$this->maybe_onboard_apple_pay_merchant();
	}

	/**
	 * Try to onboard after payment request settings updated.
	 *
	 * @param array $old_options old options.
	 * @param array $new_options new options.
	 */
	public function on_revolut_payment_request_options_update( $old_options, $new_options ) {
		$this->revolut_payment_request_settings = $new_options;
		$this->maybe_onboard_apple_pay_merchant();
	}

	/**
	 * Display messages.
	 */
	public function admin_notices() {
		$page    = isset( $_GET['page'] ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore 
		$section = isset( $_GET['section'] ) ? wc_clean( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore 

		if ( ! empty( $page ) && ! empty( $section ) ) {
			$is_revolut_section = 'wc-settings' === $page && in_array( $section, WC_REVOLUT_GATEWAYS, true );
			if ( $is_revolut_section ) {
				if ( ! empty( $this->error_messages ) ) {
					$this->error_messages = array_unique( $this->error_messages );
					foreach ( $this->error_messages as $message ) {
						echo wp_kses_post( '<div class="error revolut-passphrase-message"><p>' . $message . '</p></div>' );
					}
				}

				if ( ! empty( $this->success_messages ) ) {
					foreach ( $this->success_messages as $message ) {
						echo wp_kses_post( '<div style="border-left-color: green" class="error revolut-passphrase-message"><p>' . $message . '</p></div>' );
					}
				}
			}
		}
	}

	/**
	 * Check is shop needs onboarding.
	 */
	public function check_is_shop_needs_onboarding() {
		if ( $this->check_is_already_onboarded() ) {
			return false;
		}

		if ( ! $this->is_revolut_payment_request_enabled() ) {
			return false;
		}

		if ( ! $this->check_is_api_key_configured() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check is api key configured.
	 */
	public function check_is_api_key_configured() {
		return ! empty( $this->api_client->api_key ) && 'sandbox' !== $this->api_settings->get_option( 'mode' );
	}

	/**
	 * Check is already onboarded.
	 */
	public function check_is_already_onboarded() {
		return $this->get_option( 'apple_pay_merchant_onboarded' ) === 'yes'
			&& $this->get_option( 'apple_pay_merchant_onboarded_api_key' ) === $this->api_client->api_key
			&& $this->domain_name === $this->get_option( 'apple_pay_merchant_onboarded_domain' );
	}

	/**
	 * Check is payment request enabled.
	 */
	public function is_revolut_payment_request_enabled() {
		return 'yes' === $this->get_option( 'enabled', 'yes' );
	}

	/**
	 * Get configuration options.
	 *
	 * @param string $option option key.
	 * @param mixed  $default default value.
	 */
	public function get_option( $option, $default = '' ) {
		if ( isset( $this->revolut_payment_request_settings[ $option ] ) && ! empty( $this->revolut_payment_request_settings[ $option ] ) ) {
			return $this->revolut_payment_request_settings[ $option ];
		}

		return $default;
	}

	/**
	 * Onboard Apple Pay merchant if required.
	 */
	public function maybe_onboard_apple_pay_merchant() {
		$action = isset( $_POST['action'] ) ? wc_clean( wp_unslash( $_POST['action'] ) ) : ''; // phpcs:ignore 

		if ( ! empty( $action ) && 'wc_revolut_onboard_applepay_domain' === $action ) {
			return false; // skip for manual on-boarding.
		}

		if ( ! $this->check_is_shop_needs_onboarding() ) {
			return false;
		}

		flush_rewrite_rules();

		if ( ! $this->download_onboarding_file() ) {
			$this->revolut_payment_request_settings['apple_pay_merchant_onboarded_domain'] = '';
			$this->revolut_payment_request_settings['apple_pay_merchant_onboarded']        = 'no';
			update_option(
				'woocommerce_revolut_payment_request_settings',
				$this->revolut_payment_request_settings
			);

			return false;
		}

		if ( ! $this->register_domain() ) {
			$this->add_onboarding_error_message(
				__(
					'Can not on-boarding Apple Pay merchant',
					'revolut-gateway-for-woocommerce'
				)
			);

			$this->revolut_payment_request_settings['apple_pay_merchant_onboarded_domain'] = '';
			$this->revolut_payment_request_settings['apple_pay_merchant_onboarded']        = 'no';
			update_option(
				'woocommerce_revolut_payment_request_settings',
				$this->revolut_payment_request_settings
			);

			return false;
		}

		$this->remove_onboarding_file();

		$this->revolut_payment_request_settings['apple_pay_merchant_onboarded_domain']  = $this->domain_name;
		$this->revolut_payment_request_settings['apple_pay_merchant_onboarded_api_key'] = $this->api_client->api_key;
		$this->revolut_payment_request_settings['apple_pay_merchant_onboarded']         = 'yes';
		update_option( 'woocommerce_revolut_payment_request_settings', $this->revolut_payment_request_settings );

		$this->add_onboarding_success_message(
			__(
				'Apple Pay merchant on-boarded successfully',
				'revolut-gateway-for-woocommerce'
			)
		);
	}

	/**
	 * Register domain.
	 */
	public function register_domain() {
		try {
			$request_body = array(
				'domain' => $this->domain_name,
			);
			$res          = $this->api_client->post( '/apple-pay/domains/register', $request_body );
		} catch ( Exception $e ) {
			$this->log_error( $e->getMessage() );

			return false;
		}

		return true;
	}

	/**
	 * Download onboarding file.
	 */
	public function download_onboarding_file() {
		try {
			if ( ! file_exists( $this->onboarding_file_dir ) && ! mkdir( $this->onboarding_file_dir, 0755 ) ) {
				$this->add_onboarding_error_message(
					__(
						'Can not on-boarding Apple Pay merchant: Can not create directory',
						'woocommerce-gateway-revolut'
					)
				);

				return false;
			}

			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}
			if ( ! file_put_contents( $this->onboarding_file_path, file_get_contents( $this->domain_onboarding_file_remote_link )) ) // phpcs:ignore 
			{
				$this->add_onboarding_error_message(
					__(
						'Can not on-boarding Apple Pay merchant: Can not locate on-boarding file',
						'revolut-gateway-for-woocommerce'
					)
				);

				return false;
			}

			return true;
		} catch ( Exception $e ) {
			$this->log_error( $e->getMessage() );

			return false;
		}
	}

	/**
	 * Remove onboarding file.
	 */
	public function remove_onboarding_file() {
		if ( ! unlink( $this->onboarding_file_path ) ) {
			$this->add_onboarding_error_message(
				__(
					'Can not remove on-boarding file',
					'revolut-gateway-for-woocommerce'
				)
			);
		}
	}

	/**
	 * Add error message.
	 *
	 * @param string $message message.
	 */
	public function add_onboarding_error_message( $message ) {
		$this->error_messages[] = $message;
	}

	/**
	 * Add success message.
	 *
	 * @param string $message message.
	 */
	public function add_onboarding_success_message( $message ) {
		$this->success_messages[] = $message;
	}
}
