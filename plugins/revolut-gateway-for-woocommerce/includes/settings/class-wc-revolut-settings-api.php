<?php
/**
 * Revolut Api Settings
 *
 * Provides configuration for API settings
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

/**
 * WC_Revolut_Settings_API class.
 */
class WC_Revolut_Settings_API extends WC_Settings_API {


	use WC_Revolut_Settings_Trait;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id        = 'revolut';
		$this->tab_title = __( 'API Settings', 'revolut-gateway-for-woocommerce' );
		$this->init_form_fields();
		$this->init_settings();
		$this->hooks();
	}

	/**
	 * Add required filters
	 */
	public function hooks() {
		add_filter( 'wc_revolut_settings_nav_tabs', array( $this, 'admin_nav_tab' ), 1 );
		add_action( 'woocommerce_settings_checkout', array( $this, 'output_settings_nav' ) );
		add_action( 'woocommerce_settings_checkout', array( $this, 'admin_options' ) );
		add_action( 'admin_notices', array( $this, 'add_revolut_description' ) );
		add_action( 'admin_notices', array( $this, 'check_api_key' ) );
		add_action( 'admin_notices', array( $this, 'maybe_register_webhook' ) );
		add_action( 'admin_notices', array( $this, 'maybe_register_synchronous_webhooks' ) );
		add_action( 'woocommerce_update_options_checkout_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Initialize Settings Form Fields
	 */
	public function init_form_fields() {
		$mode            = $this->get_option( 'mode' );
		$mode            = empty( $mode ) ? 'sandbox' : $mode;
		$api_key_sandbox = $this->get_option( 'api_key_sandbox' );
		$api_key_dev     = $this->get_option( 'api_key_dev' );
		$api_key_live    = $this->get_option( 'api_key' );

		$this->form_fields = array(
			'title'                        => array(
				'type'  => 'title',
				'title' => __( 'Revolut Gateway - API Settings', 'revolut-gateway-for-woocommerce' ),
			),
			'mode'                         => array(
				'title'       => __( 'Select Mode', 'revolut-gateway-for-woocommerce' ),
				'description' => __( 'Select mode between live mode and sandbox.', 'revolut-gateway-for-woocommerce' ),
				'desc_tip'    => true,
				'type'        => 'select',
				'default'     => $mode,
				'options'     => array(
					'sandbox' => __( 'Sandbox', 'revolut-gateway-for-woocommerce' ),
					'live'    => __( 'Live', 'revolut-gateway-for-woocommerce' ),
					// phpcs:ignore
		//  'dev' => __('Dev', 'revolut-gateway-for-woocommerce'),
				),
			),
			'api_key_sandbox'              => array(
				'title'       => __( 'Sandbox API secret key' ),
				'description' => __( 'Sandbox API secret key from your Merchant settings on Revolut.', 'revolut-gateway-for-woocommerce' ),
				'desc_tip'    => true,
				'default'     => $api_key_sandbox,
				'type'        => 'password',
				'class'       => 'enabled-sandbox',
			),
			// phpcs:ignore
			//  'api_key_dev' => array(
			// 'title'       => __( 'API Key Dev' ),
			// 'description' => __( 'API Key from your Merchant settings on Revolut.', 'revolut-gateway-for-woocommerce' ),
			// 'desc_tip'    => true,
			// 'default'     => $api_key_dev,
			// 'type'        => 'password',
			// 'class'       => 'enabled-sandbox',
			// ),
			'api_key'                      => array(
				'title'       => __( 'Production API secret key', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'password',
				'description' => __( 'Production API secret key from your Merchant settings on Revolut.', 'revolut-gateway-for-woocommerce' ),
				'desc_tip'    => true,
				'default'     => $api_key_live,
				'class'       => 'enabled-live',
			),
			'payment_action'               => array(
				'title'       => __( 'Payment Action', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'select',
				'default'     => 'authorize_and_capture',
				'options'     => array(
					'authorize'             => __( 'Authorize Only', 'revolut-gateway-for-woocommerce' ),
					'authorize_and_capture' => __( 'Authorize and Capture', 'revolut-gateway-for-woocommerce' ),
				),
				'description' => __(
					'Select "Authorize Only" mode. This allows the payment to be captured up to 7 days after the user has placed the order (e.g. when the goods are shipped or received). 
                If not selected, Revolut will try to authorize and capture all payments.',
					'revolut-gateway-for-woocommerce'
				),
				'desc_tip'    => true,
			),
			'accept_capture'               => array(
				'title'       => '',
				'label'       => __( 'Automatically capture order in Revolut', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'Automatically try to capture orders when their status is changed.', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'yes',
			),
			'customise_capture_status'     => array(
				'title'       => '',
				'label'       => __( 'Customize status to trigger capture.', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'Default when checkbox not selected: Processing, Completed', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'yes',
			),
			'selected_capture_status_list' => array(
				'title'             => '',
				'type'              => 'multiselect',
				'description'       => __( 'Order Status for triggering the payment capture on Revolut. Default: processing, completed', 'revolut-gateway-for-woocommerce' ),
				'desc_tip'          => true,
				'class'             => 'wc-enhanced-select',
				'options'           => wc_get_order_statuses(),
				'default'           => array(
					'wc-processing' => 'Processing',
					'wc-completed'  => 'Completed',
				),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select status', 'revolut-gateway-for-woocommerce' ),
				),
			),
			'disable_banner'               => array(
				'title'       => 'Banner Visibility',
				'label'       => __( 'Customers can get instructions to signup to Revolut and get rewarded.', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'checkbox',
				'description' => 'This will allow them to pay via Revolut Pay the next time they visit your store and checkout faster.',
				'default'     => 'yes',
			),
		);
	}

	/**
	 * Displays configuration page with tabs
	 */
	public function admin_options() {
		if ( $this->check_is_get_data_submitted( 'page' ) && $this->check_is_get_data_submitted( 'section' ) ) {
			$is_revolut_api_section = 'wc-settings' === $this->get_request_data( 'page' ) && 'revolut' === $this->get_request_data( 'section' );

			if ( $is_revolut_api_section ) {
				echo wp_kses_post( '<table class="form-table">' );
				$this->generate_settings_html( $this->get_form_fields(), true );
				echo wp_kses_post( '</table>' );
			}
		}
	}

	/**
	 * Output Revolut description.
	 *
	 * @since 2.0.0
	 */
	public function add_revolut_description() {
		if ( $this->check_is_get_data_submitted( 'page' ) && $this->check_is_get_data_submitted( 'section' ) ) {
			$is_revolut_section = 'wc-settings' === $this->get_request_data( 'page' ) && in_array( $this->get_request_data( 'section' ), WC_REVOLUT_GATEWAYS, true );

			if ( $is_revolut_section ) {
				if ( isset( $this->settings['api_key'] ) && empty( $this->settings['api_key'] ) ) {
					?>
					<div class="notice notice-info sf-notice-nux is-dismissible" xmlns="" id="revolut_notice">
						<div class="notice-content">
							<p>
								Welcome to the <b>Revolut Gateway for Woocommerce plugin!</b>
							</p>
							<p>
								To start accepting payments from your customers at great rates, you'll need to follow
								three
								simple steps:
							</p>
							<ul style="list-style-type: disc; margin-left: 50px;">
								<li>
									<a href="https://business.revolut.com/signup">Sign up for Revolut Business</a> if
									you
									don't
									have an account already.
								</li><li>
									Once your Revolut Business account has been approved, <a target="_blank" href="https://business.revolut.com/merchant">apply
										for a Merchant
										Account</a>
								</li>
								<li>
									<a target="_blank" href="https://business.revolut.com/settings/merchant-api">Get
										your Production API key</a>
									and
									paste it in the corresponding field below
								</li>
							</ul>
							<p>
								<a target="_blank" href="https://www.revolut.com/business/online-payments">Find out
									more</a> about why
								accepting
								payments through Revolut is the right decision for your business.
							</p>
							<p>
								If you'd like to know more about how to configure this plugin for your needs, <a
										target="_blank"
										href="https://developer.revolut.com/docs/accept-payments/plugins/woocommerce/configuration">check
									out
									our documentation.</a>
							</p>
						</div>
					</div>
					<?php
				} else {
					?>
					<script>
						jQuery(document).ready(function ($) {
							$("#revolut_notice").hide();
						});
					</script>
					<?php
				}
			}
		}
	}

	/**
	 * Add admin notice when set up failed
	 */
	public function check_api_key() {
		if ( $this->check_is_get_data_submitted( 'page' ) && $this->check_is_get_data_submitted( 'section' ) ) {
			$is_revolut_section = 'wc-settings' === $this->get_request_data( 'page' ) && in_array( $this->get_request_data( 'section' ), WC_REVOLUT_GATEWAYS, true );

			if ( $is_revolut_section ) {
				$api_key_sandbox = $this->get_option( 'api_key_sandbox' );
				$api_key_live    = $this->get_option( 'api_key' );

				if ( empty( $api_key_sandbox ) && empty( $api_key_live ) ) {
					$this->add_error_message( __( 'Revolut requires an API Key to work.', 'revolut-gateway-for-woocommerce' ) );
				}
			}
		}
	}

	/**
	 * Setup Revolut webhook if not configured
	 */
	public function maybe_register_webhook() {
		$api_client = new WC_Revolut_API_Client( $this );

		if ( empty( $api_client->api_key ) ) {
			return false;
		}

		if ( ! $this->check_is_shop_needs_webhook_setup() ) {
			return false;
		}

		if ( ! $this->setup_revolut_webhook() ) {
			return false;
		}

		$this->add_success_message( __( 'Webhook url successfully configured', 'revolut-gateway-for-woocommerce' ) );
	}

	/**
	 * Setup Revolut synchronous webhooks if not configured
	 */
	public function maybe_register_synchronous_webhooks() {
		$api_client = new WC_Revolut_API_Client( $this );

		if ( empty( $api_client->api_key ) ) {
			return false;
		}

		if ( ! $this->setup_revolut_synchronous_webhook() ) {
			/* translators:%1s: %$2s: */
			$this->add_error_message( sprintf( __( 'Synchronous Webhook setup unsuccessful. Please make sure you are using the correct %1$sAPI key%2$s. If the problem persists, please reach out to support via our in-app chat.', 'revolut-gateway-for-woocommerce' ), '<a href="https://developer.revolut.com/docs/accept-payments/get-started/generate-the-api-key" target="_blank">', '</a>' ) );
			return false;
		}
	}

	/**
	 * Revolut location setup
	 *
	 * @throws Exception Exception.
	 */
	public function setup_revolut_location() {
		$domain        = get_site_url();
		$location_name = str_replace( array( 'https://', 'http://' ), '', $domain );
		$api_client    = new WC_Revolut_API_Client( $this, true );
		$locations     = $api_client->get( '/locations' );

		if ( ! empty( $locations ) ) {
			foreach ( $locations as $location ) {
				if ( isset( $location['name'] ) && $location['name'] === $domain && ! empty( $location['id'] ) ) {
					return $location['id'];
				}
			}
		}

		$body = array(
			'name'    => $location_name,
			'type'    => 'online',
			'details' => array(
				'domain' => $domain,
			),
		);

		$location = $api_client->post( '/locations', $body );

		if ( ! isset( $location['id'] ) || empty( $location['id'] ) ) {
			throw new Exception( 'Can not create location object.' );
		}

		return $location['id'];
	}

	/**
	 * Check is shop needs webhook setup
	 */
	public function check_is_shop_needs_webhook_setup() {
		try {
			$web_hook_url = get_site_url( null, '/wp-json/wc/v3/revolut', 'https' );

			if ( $this->get_option( 'revolut_webhook_domain' ) === $web_hook_url ) {
				return false;
			}

			$api_client        = new WC_Revolut_API_Client( $this );
			$web_hook_url_list = $api_client->get( '/webhooks' );

			if ( ! empty( $web_hook_url_list ) ) {
				$web_hook_url_list = array_column( $web_hook_url_list, 'url' );

				if ( in_array( $web_hook_url, $web_hook_url_list, true ) ) {
					return false;
				}
			}
		} catch ( Exception $e ) {
			$this->add_error_message( $e->getMessage() );
		}

		return true;
	}

	/**
	 * Revolut webhook setup
	 */
	public function setup_revolut_webhook() {
		try {
			$web_hook_url = get_site_url( null, '/wp-json/wc/v3/revolut', 'https' );
			$body         = array(
				'url'    => $web_hook_url,
				'events' => array(
					'ORDER_COMPLETED',
					'ORDER_AUTHORISED',
				),
			);
			$api_client   = new WC_Revolut_API_Client( $this );
			$response     = $api_client->post( '/webhooks', $body );

			if ( isset( $response['id'] ) && ! empty( $response['id'] ) ) {
				$this->update_option( 'revolut_webhook_domain', $web_hook_url );
				return true;
			}
		} catch ( Exception $e ) { // phpcs:ignore
			// Prevent double logs. Exception logged previously.
		}

		return false;
	}

	/**
	 * Revolut webhook setup
	 */
	public function setup_revolut_synchronous_webhook() {
		try {
			$web_hook_url = get_site_url( null, '/wp-json/wc/v3/revolut', 'https' );
			$location_id  = $this->setup_revolut_location();

			$mode = $this->get_option( 'mode' );
			$mode = empty( $mode ) ? 'sandbox' : $mode;

			if ( $this->get_option( 'revolut_pay_synchronous_webhook_domain_' . $mode . '_' . $location_id ) === $web_hook_url ) {
				$this->update_option( 'revolut_' . $mode . '_location_id', $location_id );
				return true;
			}

			$body = array(
				'url'         => $web_hook_url,
				'event_type'  => 'fast_checkout.validate_address',
				'location_id' => $location_id,
			);

			$api_client = new WC_Revolut_API_Client( $this, true );
			$response   = $api_client->post( '/synchronous-webhooks', $body );

			if ( isset( $response['signing_key'] ) && ! empty( $response['signing_key'] ) ) {
				$this->update_option( 'revolut_' . $mode . '_location_id', $location_id );
				$this->update_option( 'revolut_pay_synchronous_webhook_domain_' . $mode . '_' . $location_id, $web_hook_url );
				$this->update_option( 'revolut_pay_synchronous_webhook_domain_' . $mode . '_signing_key', $response['signing_key'] );
				$this->add_success_message( __( 'Synchronous Webhook url successfully configured', 'revolut-gateway-for-woocommerce' ) );
				return true;
			}

			$this->add_error_message( wp_json_encode( $response ) );
		} catch ( Exception $e ) {
			$this->add_error_message( $e->getMessage() );
		}

		return false;
	}

	/**
	 * Get Revolut Location
	 */
	public function get_revolut_location() {
		$mode = empty( $this->get_option( 'mode' ) ) ? 'sandbox' : $this->get_option( 'mode' );
		return $this->get_option( 'revolut_' . $mode . '_location_id' );
	}

	/**
	 * Display error message
	 *
	 * @param string $message display message.
	 */
	public function add_error_message( $message ) {
		echo wp_kses_post( '<div class="error revolut-passphrase-message"><p>' . $message . '</p></div>' );
	}

	/**
	 * Display success message
	 *
	 * @param string $message display message.
	 */
	public function add_success_message( $message ) {
		echo wp_kses_post( '<div style="border-left-color: green" class="error revolut-passphrase-message"><p>' . $message . '</p></div>' );
	}

	/**
	 * Check is data submitted for GET request.
	 *
	 * @param string $submit request key.
	 */
	public function check_is_get_data_submitted( $submit ) {
		return isset( $_GET[ $submit ] ); // phpcs:ignore 
	}

	/**
	 * Safe get request data
	 *
	 * @param string $get_key request key.
	 */
	public function get_request_data( $get_key ) {
		return isset( $_GET[ $get_key ] ) ? wc_clean( wp_unslash( $_GET[ $get_key ] ) ) : ''; // phpcs:ignore 
	}
}
