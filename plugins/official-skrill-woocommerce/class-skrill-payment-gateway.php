<?php
/**
 * Skrill Payment Gateway
 *
 * Copyright (c) Skrill
 *
 * @class   Skrill_Payment_Gateway
 * @extends WC_Payment_Gateway
 * @package Skrill/Classes
 * @located at  /
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skrill Pyament Gateway
 */
class Skrill_Payment_Gateway extends WC_Payment_Gateway {


	/**
	 * Payment method id
	 *
	 * @var string
	 */
	public $payment_method_id = '';

	/**
	 * Payment method
	 *
	 * @var string
	 */
	public $payment_method = '';

	/**
	 * Payment method logo
	 *
	 * @var string
	 */
	public $payment_method_logo = '';

	/**
	 * Payment method description
	 *
	 * @var string
	 */
	public $payment_method_description = '';

	/**
	 * Language
	 *
	 * @var string
	 */
	public $language;

	/**
	 * Plugin directory
	 *
	 * @var string
	 */
	public $plugin_directory;

	/**
	 * Skrill log
	 *
	 * @var object
	 */
	public $skrill_log;

	/**
	 * Logger handle
	 *
	 * @var string
	 */
	public $logger_handle;

	/**
	 * Woocommerce cancelled status
	 *
	 * @var string
	 */
	protected $cancelled_status = 'cancelled';

	/**
	 * Woocommerce failed status
	 *
	 * @var string
	 */
	protected $failed_status = 'failed';

	/**
	 * Woocommerce processing status
	 *
	 * @var string
	 */
	protected $processing_status = 'processing';

	/**
	 * Woocommerce pending status
	 *
	 * @var string
	 */
	protected $pending_status = 'pending';

	/**
	 * Woocommerce completed status
	 *
	 * @var string
	 */
	protected $completed_status = 'completed';

	/**
	 * Woocommerce on-hold status
	 *
	 * @var string
	 */
	protected $onhold_status = 'on-hold';

	/**
	 * Woocommerce refunded status
	 *
	 * @var string
	 */
	protected $refunded_status = 'refunded';

	/**
	 * Woocommerce wc-cancelled status
	 *
	 * @var string
	 */
	protected $wc_cancelled_status = 'wc-cancelled';

	/**
	 * Woocommerce wc-failed status
	 *
	 * @var string
	 */
	protected $wc_failed_status = 'wc-failed';

	/**
	 * Woocommerce wc-processing status
	 *
	 * @var string
	 */
	protected $wc_processing_status = 'wc-processing';

	/**
	 * Woocommerce wc-pending status
	 *
	 * @var string
	 */
	protected $wc_pending_status = 'wc-pending';

	/**
	 * Woocommerce wc-completed status
	 *
	 * @var string
	 */
	protected $wc_completed_status = 'wc-completed';

	/**
	 * Woocommerce wc-on-hold status
	 *
	 * @var string
	 */
	protected $wc_onhold_status = 'wc-on-hold';

	/**
	 * Woocommerce wc-refunded status
	 *
	 * @var string
	 */
	protected $wc_refunded_status = 'wc-refunded';

	/**
	 * Prepare only
	 *
	 * @var int
	 */
	protected $prepare_only = 1;

	/**
	 * Skrill processed status
	 *
	 * @var string
	 */
	protected $skrill_processed_status = '2';

	/**
	 * Skrill pending status
	 *
	 * @var string
	 */
	protected $skrill_pending_status = '0';

	/**
	 * Skrill failed status
	 *
	 * @var string
	 */
	protected $skrill_failed_status = '-2';

	/**
	 * Skrill refunded status
	 *
	 * @var string
	 */
	protected $skrill_refunded_status = '-4';

	/**
	 * Skrill refund failed status
	 *
	 * @var string
	 */
	protected $skrill_refund_failed_status = '-5';

	/**
	 * Skrill refund pending status
	 *
	 * @var string
	 */
	protected $skrill_refund_pending_status = '-6';

	/**
	 * Skrill not verified status
	 *
	 * @var string
	 */
	protected $skrill_not_verified_status = '-7';

	/**
	 * Skrill fraud status
	 *
	 * @var string
	 */
	protected $skrill_fraud_status = '-8';

	/**
	 * Woocommerce order
	 *
	 * @var object
	 */
	public $wc_order;

	/**
	 * Added meta boxes
	 *
	 * @var boolean
	 */
	private static $added_meta_boxes = false;

	/**
	 * Updated meta boxes
	 *
	 * @var boolean
	 */
	private static $updated_meta_boxes = false;

	/**
	 * Saved meta boxes
	 *
	 * @var boolean
	 */
	private static $saved_meta_boxes = false;

	/**
	 * Payment method that need to display the title and supported logo in the description
	 *
	 * @var array
	 */
	protected $payment_method_show_title_logo = array();

	/**
	 * True when payment method is one of the credit card list
	 *
	 * @var array
	 */
	protected $is_payment_method_in_credit_card_list = false;

	/**
	 * Allowed countries
	 *
	 * @var array
	 */
	protected $allowed_countries = array( 'ALA', 'ALB', 'DZA', 'ASM', 'AND', 'AGO', 'AIA', 'ATA', 'ATG', 'ARG', 'ARM', 'ABW', 'AUS', 'AUT', 'AZE', 'BHS', 'BHR', 'BGD', 'BRB', 'BLR', 'BEL', 'BLZ', 'BEN', 'BMU', 'BTN', 'BOL', 'BIH', 'BWA', 'BVT', 'BRA', 'BRN', 'BGR', 'BFA', 'BDI', 'KHM', 'CMR', 'CAN', 'CPV', 'CYM', 'CAF', 'TCD', 'CHL', 'CHN', 'CXR', 'CCK', 'COL', 'COM', 'COG', 'COD', 'COK', 'CRI', 'CIV', 'HRV', 'CYP', 'CZE', 'DNK', 'DJI', 'DMA', 'DOM', 'ECU', 'EGY', 'SLV', 'GNQ', 'ERI', 'EST', 'ETH', 'FLK', 'FRO', 'FJI', 'FIN', 'FRA', 'GUF', 'PYF', 'ATF', 'GAB', 'GMB', 'GEO', 'DEU', 'GHA', 'GIB', 'GRC', 'GRL', 'GRD', 'GLD', 'GUM', 'GTM', 'GGY', 'HTI', 'HMD', 'VAT', 'GIN', 'GNB', 'GUY', 'HND', 'HKG', 'HUN', 'ISL', 'IND', 'IDN', 'IRL', 'IMN', 'ISR', 'ITA', 'JAM', 'JPN', 'JEY', 'JOR', 'KAZ', 'KEN', 'KIR', 'KOR', 'KWT', 'LAO', 'LVA', 'LBN', 'LSO', 'LBR', 'LIE', 'LTU', 'LUX', 'MAC', 'MKD', 'MDG', 'MWI', 'MYS', 'MDV', 'MLI', 'MLT', 'MHL', 'MTQ', 'MRT', 'MUS', 'MYT', 'MEX', 'FSM', 'MDA', 'MCO', 'MNG', 'MNE', 'MSR', 'MAR', 'MOZ', 'MMR', 'NAM', 'NPL', 'NLD', 'ANT', 'NCL', 'NZL', 'NIC', 'NER', 'NGA', 'NIU', 'NFK', 'MNP', 'NOR', 'OMN', 'PAK', 'PLW', 'PSE', 'PAN', 'PNG', 'PRY', 'PER', 'PHL', 'PCN', 'POL', 'PRT', 'PRI', 'QAT', 'REU', 'ROU', 'RUS', 'RWA', 'SHN', 'KNA', 'LCA', 'MAF', 'SPM', 'VCT', 'WSM', 'SMR', 'STP', 'SAU', 'SEN', 'SRB', 'SYC', 'SLE', 'SGP', 'SVK', 'SVN', 'SLB', 'SOM', 'ZAF', 'SGS', 'ESP', 'LKA', 'SUR', 'SJM', 'SWZ', 'SWE', 'CHE', 'TWN', 'TJK', 'TZA', 'THA', 'TLS', 'TGO', 'TKL', 'TON', 'TTO', 'TUN', 'TUR', 'TKM', 'TCA', 'TUV', 'UGA', 'UKR', 'ARE', 'GBR', 'USA', 'UMI', 'URY', 'UZB', 'VUT', 'VEN', 'VNM', 'VGB', 'VIR', 'WLF', 'ESH', 'YEM', 'ZMB', 'ZWE' );

	/**
	 * Unallowed countries
	 *
	 * @var array
	 */
	protected $unallowed_countries = array( 'AFG', 'CUB', 'ERI', 'IRN', 'IRQ', 'JPN', 'KGZ', 'LBY', 'PRK', 'SDN', 'SSD', 'SYR', 'PLW' );

	/**
	 * Excepted countries
	 *
	 * @var array
	 */
	protected $excepted_countries = array();

	/**
	 * Construct
	 */
	public function __construct() {
		$this->payment_method_id = $this->id;

		$this->language           = Skrill_Configuration::get_language();
		$this->plugin_directory   = plugin_dir_path( __FILE__ );
		$this->form_fields        = Skrill_Payment_Configuration::render_payment_configuration(
			$this->payment_method_id,
			$this->payment_method_description
		);
		$this->method_title       = 'Skrill - ' . $this->get_title();
		$this->method_description = $this->get_payment_method_logo();
		$this->init_settings();

		$this->skrill_log    = new WC_Logger();
		$this->logger_handle = 'skrill-' . gmdate( 'Ym' );

		// save admin configuration from woocomerce checkout tab.
		add_action(
			'woocommerce_update_options_payment_gateways_' . $this->payment_method_id,
			array( $this, 'process_admin_options' )
		);
		// frontend hook.
		add_action( 'woocommerce_receipt_' . $this->payment_method_id, array( &$this, 'set_receipt_page' ) );
		add_action( 'woocommerce_thankyou_' . $this->payment_method_id, array( &$this, 'set_thankyou_page' ) );
		// backend hook.
		add_action(
			'woocommerce_admin_order_data_after_shipping_address',
			array( &$this, 'render_additional_information' )
		);
		add_action( 'woocommerce_admin_order_data_after_order_details', array( &$this, 'update_order' ) );
		add_action( 'woocommerce_process_shop_order_meta', array( &$this, 'save_order_meta' ) );

		// enable woocommerce refund for {payment gateway}.
		$this->supports = array( 'refunds' );

		if ( isset( WC()->session->skrill_thankyou_page ) ) {
			unset( WC()->session->skrill_thankyou_page );
		}
		if ( isset( WC()->session->skrill_receipt_page ) ) {
			unset( WC()->session->skrill_receipt_page );
		}
		if ( isset( WC()->session->skrill_status_url ) ) {
			unset( WC()->session->skrill_status_url );
		}
		if ( isset( WC()->session->skrill_refund_status_url ) ) {
			unset( WC()->session->skrill_refund_status_url );
		}

		if ( isset( WC()->session->skrill_order_id ) ) {
			unset( WC()->session->skrill_order_id );
		}

		$transaction_id    = Skrill_Payment::get_request_value( 'transaction_id' );
		$msid              = Skrill_Payment::get_request_value( 'msid' );
		$status            = Skrill_Payment::get_request_value( 'status' );
		$status_url        = Skrill_Payment::get_request_value( 'status_url' );
		$refund_status_url = Skrill_Payment::get_request_value( 'refund_status_url' );

		if ( ( ! empty( $transaction_id ) && ! empty( $msid ) )
			|| ( ! empty( $status ) && ! empty( $status_url ) )
			|| ( ! empty( $status ) && ! empty( $refund_status_url ) )
		) {
			add_filter(
				'woocommerce_order_needs_payment',
				function () {
					return true;
				}
			);
		}

	}

	/**
	 * To initied subscription support and hooks.
	 *
	 * @return boolean
	 */
	public function maybe_init_subscriptions() {
		if ( ! $this->is_subscriptions_enabled() ) {
			return;
		}

		$this->supports = array_merge(
			$this->supports,
			array(
				'subscriptions',
				'subscription_cancellation',
				'subscription_suspension',
				'subscription_reactivation',
				'multiple_subscriptions',
			)
		);

		add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
	}

			/**
			 * Checks if subscriptions are enabled on the site.
			 *
			 * @since 5.6.0
			 *
			 * @return bool Whether subscriptions is enabled or not.
			 */
	public function is_subscriptions_enabled() {
		return class_exists( 'WC_Subscriptions' ) && version_compare( WC_Subscriptions::$version, '2.2.0', '>=' );
	}

			/**
			 * Is $order_id a subscription?
			 *
			 * @since 5.6.0
			 *
			 * @param  int $order_id woocommerce order id.
			 * @return boolean
			 */
	public function has_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}

	/**
	 * Get payment icon.
	 * Override from class WC_Payment_Gateway
	 *
	 * @return string
	 */
	public function get_icon() {
		if ( in_array( $this->payment_method_id, $this->payment_method_show_title_logo, true ) ) {
			return false;
		}
		$logos_html = $this->generate_logos_html();

		return apply_filters( 'woocommerce_gateway_icon', $logos_html, $this->payment_method_id );
	}

	/**
	 * Get ayment description.
	 * Override from class WC_Payment_Gateway
	 *
	 * @return string
	 */
	public function get_description() {
		if ( ! in_array( $this->payment_method_id, $this->payment_method_show_title_logo, true ) ) {
			return false;
		}
		$description_html = __( 'Supported Banks', 'wc-skrill' ) . ':<br>';
		$logos_html       = $this->add_logos_to_description();

		return apply_filters( 'woocommerce_gateway_description', $description_html . $logos_html, $this->payment_method_id );
	}

	/**
	 * Add logos to payment method description
	 *
	 * @return string
	 */
	public function add_logos_to_description() {
		$logos_html = $this->generate_logos_html( true );
		return $logos_html;
	}

	/**
	 * Generate logos html
	 *
	 * @param  boolean $is_small true if want to generate small image html.
	 * @return string            logos html
	 */
	public function generate_logos_html( $is_small = false ) {
		$title                = $this->get_title();
		$payment_method_logos = explode( ',', $this->payment_method_logo );
		$logos_html           = '';
		foreach ( $payment_method_logos as $logo ) {
			$logo_src = plugins_url( 'assets/images/' . $logo, __FILE__ );
			if ( $is_small ) {
				$logo_style = 'height:28px; margin:10px 5px;';
			} else {
				$logo_style = 'height:50px; max-height:50px; margin:15px 10px;';
			}

			$logos_html .= '<img src="' . $logo_src . '" alt="' . $title . '" title="' . $title . '"
			style="' . $logo_style . ' float: none; vertical-align: middle" />';
		}
		return $logos_html;
	}

	/**
	 * Get payment method settings from backend configuration
	 *
	 * @return array
	 */
	public function get_payment_settings() {
		return get_option( 'woocommerce_' . $this->payment_method_id . '_settings' );
	}

	/**
	 * Check if payment method enabled at backend configuration
	 *
	 * @return bool
	 */
	public function is_enabled() {
		$payment_settings = $this->get_payment_settings();
		if ( 'yes' === $payment_settings['enabled'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if payment method show separately at backend configuration
	 *
	 * @return bool
	 */
	public function is_show_separately() {
		$payment_settings = $this->get_payment_settings();
		if ( 'yes' === $payment_settings['show_separately'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if payment methods allow the country
	 *
	 * @return bool
	 */
	public function is_country_allowed() {
		$country_code = $this->get_wc_customer_property_value( 'country' );
		$country_code = Skrill_Payment::get_country_iso3_by_iso2( $country_code );

		$unallowed_countries = array_merge( $this->unallowed_countries, $this->excepted_countries );

		if ( in_array( $country_code, $unallowed_countries, true ) ) {
			return false;
		} elseif ( in_array( $country_code, $this->allowed_countries, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate if payment methods is available.
	 * Override From class WC_Payment_Gateway
	 *
	 * @return bool
	 */
	public function is_available() {
		$is_available = parent::is_available();

		if ( $is_available ) {
			if ( $this->is_enabled() && $this->is_country_allowed() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get payment method logo
	 *
	 * @return string
	 */
	protected function get_payment_method_logo() {
		$payment_method_title = $this->get_title();
		$country_code         = $this->get_wc_customer_property_value( 'country' );
		$country_code         = Skrill_Payment::get_country_iso3_by_iso2( $country_code );

		// if ( 'ADB' === $this->payment_method || 'AOB' === $this->payment_method || 'ACI' === $this->payment_method ) {
		// if ( array_key_exists( $country_code, $this->logo_per_countries ) ) {
		// foreach ( $this->logo_per_countries[ $country_code ] as $payment_logo ) {
		// $payment_method_logos[] = $payment_logo;
		// }
		// } else {
		// foreach ( $this->logo_per_countries as $payment_countries ) {
		// foreach ( $payment_countries as $payment_logo ) {
		// $payment_method_logos[] = $payment_logo;
		// }
		// }
		// }
		// $this->payment_method_logo = implode( ',', $payment_method_logos );
		// }

		$payment_method_logos = explode( ',', $this->payment_method_logo );

		$icon_html = '';
		foreach ( $payment_method_logos as $logo ) {
			$icon_src   = plugins_url( 'assets/images/' . $logo, __FILE__ );
			$icon_html .= '<img src="' . $icon_src . '" alt="' . $payment_method_title . '" title="' . $payment_method_title . '"
			style="height:40px; margin:15px 10px; float: none; vertical-align: middle" />';
		}

		return $icon_html;
	}

	/**
	 * Checks whether order is part of subscription.
	 *
	 * @since 1.2.0
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return bool Returns true if order is part of subscription
	 */
	public function is_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}

	/**
	 * Process payment and return success result
	 * Override from class WC_Payment_Gateway
	 *
	 * @param  int $order_id - order id.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$this->wc_order = new WC_Order( $order_id );
		return array(
			'result'   => 'success',
			'redirect' => $this->wc_order->get_checkout_payment_url( true ),
		);
	}

	/**
	 * Thankyou Page
	 * from hook "woocommerce_thankyou_{gateway_id}"
	 */
	public function set_thankyou_page() {
		if ( ! isset( WC()->session->skrill_thankyou_page ) ) {
			WC()->session->set( 'skrill_thankyou_page', true );
		}
	}

	/**
	 * Process payment
	 * from hook "woocommerce_receipt_{gateway_id}"
	 *
	 * @param int $order_id - order id.
	 */
	public function set_receipt_page( $order_id ) {
		$this->wc_order = new WC_Order( $order_id );

		if ( Skrill_Payment::get_request_value( 'status' ) && Skrill_Payment::get_request_value( 'status_url' ) ) {
			if ( ! WC()->session->get( 'skrill_status_url', false ) ) {
				$this->add_log( 'Use status_url' );
				$this->process_status_response();
				WC()->session->set( 'skrill_status_url', true );
			}
		} elseif ( Skrill_Payment::get_request_value( 'status' ) && Skrill_Payment::get_request_value( 'refund_status_url' ) ) {
			if ( ! WC()->session->get( 'skrill_refund_status_url', false ) ) {
				$this->add_log( 'Use refund_status_url' );
				$this->process_refund_status_response();
				WC()->session->set( 'skrill_refund_status_url', true );
			}
		} else {
			if ( Skrill_Payment::get_request_value( 'transaction_id' ) ) {
				$this->process_payment_response( $order_id );
			} elseif ( Skrill_Payment::get_request_value( 'cancelled' ) ) {
				$this->add_log( 'Payment cancelled by user' );
				$this->process_error_payment( $this->cancelled_status, 'You cancelled the payment prior to its execution. Please try again.' );
			} elseif ( ! WC()->session->get( 'skrill_receipt_page', false ) ) {
				$this->add_log( 'Start payment process with payment method', $this->payment_method );
				WC()->session->set( 'skrill_receipt_page', true );
				$this->render_payment_form();
			}
		}
	}

	/**
	 * Render Payment Form
	 */
	protected function render_payment_form() {
		$this->add_log( 'Get checkout parameters' );
		$checkout_parameters = $this->get_checkout_parameters();
		$transaction_log     = $this->set_transaction_log( $checkout_parameters );
		$this->add_log( 'Checkout parameters ', $transaction_log );

		if ( $this->wc_order->get_status() === get_option( 'skrill_completed_status', 'processing' ) ) {
			wp_safe_redirect( $this->get_return_url( $this->wc_order ) );
		}

		$this->add_log( 'Get payment widget url' );
		$payment_url = Skrill_Payment::get_payment_url_by_checkout_parameters( $checkout_parameters );
		$this->add_log( 'Payment widget url', $payment_url );

		try {
			$version_controll_parameters                = array();
			$version_controll_parameters['shop_url']    = home_url();
			$version_controll_parameters['ip_address']  = $this->get_the_user_ip();
			$version_controll_parameters['shop_system'] = 'Woocommerce';
			$version_controll_parameters['merchant_id'] = get_option( 'skrill_merchant_id', '' );
			global $woocommerce;
			$version_controll_parameters['shop_version'] = $woocommerce->version;

			$version_controll_parameters = wp_json_encode( $version_controll_parameters );

			Skrill_Payment::send_to_version_controll( $version_controll_parameters );
		} catch ( Exception $e ) {
			$this->add_log( 'version controll already exist for today call' );
		}

		if ( ! $payment_url ) {
			$this->add_log( 'Payment widget url is not generated' );
			$this->process_error_payment( $this->cancelled_status, 'Error before redirect' );
		}

		if ( strpos( explode( 'sid=', $payment_url )[1], 'message' ) !== false ) {
			$this->add_log( 'Payment widget url is invalid' );
			$this->process_error_payment( $this->cancelled_status, 'An error occurred while processing your transaction. Please contact our support.' );
		}

		wp_safe_redirect( $payment_url );

	}

	/**
	 * Is woocommerce version greater than
	 *
	 * @param  string $version woocommerce version.
	 * @return boolean
	 */
	protected static function is_version_greater_than( $version ) {
		if ( class_exists( 'WooCommerce' ) ) {
			global $woocommerce;
			if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get general wc order property value
	 *
	 * @param  string $property property of class WC_Order.
	 * @return mixed
	 */
	protected function get_general_wc_order_property_value( $property ) {
		$function = 'get_' . $property;
		return $this->wc_order->$function();
	}

	/**
	 * Get wc order property value
	 *
	 * @param  string $property property of class WC_Order.
	 * @return mixed
	 */
	protected function get_wc_order_property_value( $property ) {
		if ( $this->is_version_greater_than( '3.0' ) ) {
			if ( 'customer_note' === $property ) {
				$wc_order = new WC_Order();
				return $wc_order->get_customer_note();
			}
			$function = 'get_' . $property;
			return $this->wc_order->$function();
		}
		return $this->wc_order->$property;
	}

	/**
	 * Set wc order property value
	 *
	 * @param  string $property property of class WC_Order.
	 * @param  mixed  $value    value.
	 * @return void
	 */
	protected function set_wc_order_property_value( $property, $value ) {
		if ( $this->is_version_greater_than( '3.0' ) ) {
			$function = 'set_' . $property;
			$this->wc_order->$function( $value );
		} else {
			if ( 'customer_note' === $property ) {
				$this->wc_order->customer_note = $value;
			}
		}
	}

	/**
	 * Get wc customer property value
	 *
	 * @param  string $property property of class WC_Customer.
	 * @return mixed
	 */
	protected function get_wc_customer_property_value( $property ) {
		global $woocommerce;

		if ( $this->is_version_greater_than( '3.0' ) ) {

			if ( 'country' === $property ) {
				$property = 'billing_country';
			}
			$function = 'get_' . $property;

			if ( is_null( $woocommerce->customer ) ) {
				$wc_customer = new WC_Customer();
				return $wc_customer->$function();
			} else {
				return $woocommerce->customer->$function();
			}
		}
		return $woocommerce->customer->$property;
	}

	/**
	 * Skrill order reduce stock
	 */
	protected function skrill_order_reduce_stock() {
		if ( $this->is_version_greater_than( '3.0' ) ) {
			wc_reduce_stock_levels( $this->wc_order->get_order_number() );
		} else {
			$this->wc_order->reduce_order_stock();
		}
	}

	/**
	 * Get checkout parameters
	 *
	 * @return array
	 */
	protected function get_checkout_parameters() {
		$date_time     = Skrill_Payment::get_current_datetime();
		$random_number = Skrill_Payment::get_random_number( 4 );
		$currency      = get_woocommerce_currency();

		$checkout_parameters                          = array();
		$checkout_parameters['pay_to_email']          = get_option( 'skrill_merchant_account', '' );
		$checkout_parameters['recipient_description'] = get_option( 'skrill_recipient_desc', '' );
		$checkout_parameters['transaction_id']        = gmdate( 'ymd' ) . $this->wc_order->get_order_number() . $date_time . $random_number;
		$checkout_parameters['return_url']            = $this->get_receipt_page_url();
		$anti_fraud_hash                              = Skrill_Payment::generate_anti_fraud_hash( $currency, $this->wc_order->get_order_number(), $checkout_parameters['transaction_id'] );

		$checkout_parameters['status_url'] = $this->get_receipt_page_url( 'status_url' ) . '&order_id=' . $this->wc_order->get_order_number() .
		'&secure_payment=' . $anti_fraud_hash . '&payment_method_id=' . $this->payment_method_id;

		$checkout_parameters['rec_status_url'] = $this->get_skrill_rec_status_url() . '&order_id=' . $this->wc_order->get_order_number() .
		'&secure_payment=' . $anti_fraud_hash . '&payment_method_id=' . $this->payment_method_id;

		$skrill_merchant_account = get_option( 'skrill_merchant_account' );
		if ( $skrill_merchant_account ) {
			$checkout_parameters['status_url2'] = 'mailto:' . $skrill_merchant_account;
		}
		$checkout_parameters['cancel_url']     = WC()->cart->get_checkout_url();
		$checkout_parameters['language']       = $this->language;
		$checkout_parameters['logo_url']       = get_option( 'skrill_logo_url', '' );
		$checkout_parameters['prepare_only']   = $this->prepare_only;
		$checkout_parameters['pay_from_email'] = $this->get_wc_order_property_value( 'billing_email' );
		$checkout_parameters['firstname']      = $this->get_wc_order_property_value( 'billing_first_name' );
		$checkout_parameters['lastname']       = $this->get_wc_order_property_value( 'billing_last_name' );
		$checkout_parameters['address']        = $this->get_wc_order_property_value( 'billing_address_1' ) . ', ' .
		$this->get_wc_order_property_value( 'billing_address_2' );
		$checkout_parameters['postal_code']    = $this->get_wc_order_property_value( 'billing_postcode' );
		$checkout_parameters['city']           = $this->get_wc_order_property_value( 'billing_city' );
		$billing_country                       = $this->get_wc_order_property_value( 'billing_country' );
		$checkout_parameters['country']        = Skrill_Payment::get_country_iso3_by_iso2( $billing_country );

		$checkout_parameters['currency']            = $currency;
		$checkout_parameters['detail1_description'] = 'Order';

		$checkout_parameters['detail1_text']        = $this->get_general_wc_order_property_value( 'order_number' );
		$checkout_parameters['detail2_description'] = 'Order Amount';
		$sub_total_include_tax                      = $this->get_general_wc_order_property_value( 'subtotal' ) + $this->get_general_wc_order_property_value( 'cart_tax' );
		$checkout_parameters['detail2_text']        = Skrill_Payment::set_number_format( $sub_total_include_tax ) . ' ' . $currency;
		$checkout_parameters['detail3_description'] = 'Shipping';
		$shipping_include_tax                       = $this->get_general_wc_order_property_value( 'total_shipping' ) + $this->get_general_wc_order_property_value( 'shipping_tax' );
		$checkout_parameters['detail3_text']        = Skrill_Payment::set_number_format( $shipping_include_tax ) . ' ' . $currency;
		$checkout_parameters['merchant_fields']     = 'platform';
		$checkout_parameters['platform']            = '87941747';

		if ( 'skrill_flexible' !== $this->payment_method_id ) {
			$checkout_parameters['payment_methods'] = $this->payment_brand;
		}

		if ( class_exists( 'WC_Subscriptions_Order' ) && WC_Subscriptions_Order::order_contains_subscription( $this->wc_order->get_order_number() ) ) {

			$this->add_log( 'order contains subscriptions product' );

			$order                         = new WC_Order( $this->wc_order->get_order_number() );
			$skrill_subscription_parameter = $this->get_skrill_subscription_parameter( $checkout_parameters, $order );

			$merge_paramters = array_merge( $checkout_parameters, $skrill_subscription_parameter );
			unset( $checkout_parameters );
			$checkout_parameters = array();
			$checkout_parameters = $merge_paramters;
		} else {
				$checkout_parameters['amount'] = $this->wc_order->get_total();
		}

		return $checkout_parameters;
	}


	/**
	 * Generate base url for status_url
	 */
	protected function get_skrill_rec_status_url() {
		global $wp;
		return home_url() . '/wp-json/woocommerce_skrill_api/response_url?';
	}

	/**
	 * Get customer ip address
	 *
	 * @return string
	 */
	private function get_the_user_ip() {
		$ip = '0';

		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = esc_url_raw( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = esc_url_raw( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = esc_url_raw( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		return $ip;
	}

	/**
	 * Get checkout parameters for order that contain subscription product
	 *
	 * @param  string $checkout_parameters checkout parameter.
	 * @param  string $order order parameter.
	 * @return array
	 */
	protected function get_skrill_subscription_parameter( $checkout_parameters, $order ) {
		$subscription_period                   = WC_Subscriptions_Order::get_subscription_period( $order );
		$subscription_parameters               = array();
		$subscription_parameters['rec_period'] = WC_Subscriptions_Order::get_subscription_interval( $order );
		$subscription_parameters['rec_cycle']  = WC_Subscriptions_Order::get_subscription_period( $order );

		$date_now = gmdate( 'm/d/Y' );

		if ( 'day' === $subscription_period ) {
			$subscription_parameters['rec_start_date'] = gmdate( 'd/m/Y', strtotime( $date_now . '+1 days' ) );
		} elseif ( 'week' === $subscription_period ) {
			$subscription_parameters['rec_cycle']      = 'day';
			$subscription_parameters['rec_period']    *= 7;
			$subscription_parameters['rec_start_date'] = gmdate( 'd/m/Y', strtotime( $date_now . '+1 week' ) );
		} elseif ( 'month' === $subscription_period ) {
			$subscription_parameters['rec_start_date'] = gmdate( 'd/m/Y', strtotime( $date_now . '+1 month' ) );
		} else {
			$subscription_parameters['rec_start_date'] = gmdate( 'd/m/Y', strtotime( $date_now . '+1 year' ) );
		}

		$subscription_parameters['amount']     = Skrill_Payment::set_number_format( WC_Subscriptions_Order::get_total_initial_payment( $order ) );
		$subscription_parameters['rec_amount'] = Skrill_Payment::set_number_format( WC_Subscriptions_Order::get_recurring_total( $order ) );
		$unconvert_date                        = WC_Subscriptions_Manager::get_subscription_expiration_date( WC_Subscriptions_Manager::get_subscription_key( $order->id ), $order->customer_user );

		if ( isset( $checkout_parameters['rec_status_url'] ) ) {
			$subscription_parameters['rec_status_url'] = $checkout_parameters['rec_status_url'];
		}

		if ( isset( $checkout_parameters['status_url2'] ) ) {
			$subscription_parameters['rec_status_url2'] = $subscription_parameters['status_url2'];
		}

		if ( gmdate( 'd/m/Y', strtotime( $unconvert_date ) ) === $subscription_parameters['rec_start_date'] ) {

			unset( $subscription_parameters['rec_end_date'] );
			unset( $subscription_parameters['rec_start_date'] );
			unset( $subscription_parameters['rec_cycle'] );
			unset( $subscription_parameters['rec_period'] );
			unset( $subscription_parameters['rec_status_url'] );
			unset( $subscription_parameters['rec_status_url2'] );
			unset( $subscription_parameters['rec_amount'] );

		} elseif ( 0 !== $unconvert_date ) {
			if ( 'day' === $subscription_period ) {
				$subscription_parameters['rec_end_date'] = gmdate( 'd/m/Y', strtotime( $unconvert_date . '-1 days' ) );
			} elseif ( 'week' === $subscription_period ) {
				$subscription_parameters['rec_end_date'] = gmdate( 'd/m/Y', strtotime( $unconvert_date . '-1 week' ) );
			} elseif ( 'month' === $subscription_period ) {
				$subscription_parameters['rec_end_date'] = gmdate( 'd/m/Y', strtotime( $unconvert_date . '-1 month' ) );
			} else {
				$subscription_parameters['rec_end_date'] = gmdate( 'd/m/Y', strtotime( $unconvert_date . '-1 year' ) );
			}
		}
		return $subscription_parameters;
	}

	/**
	 * Get receipt page url
	 *
	 * @param  string $status   - status.
	 * @param  string $order_id - order id.
	 * @return string
	 */
	protected function get_receipt_page_url( $status = false, $order_id = '' ) {
		global $wp;

		$transaction_status = '';
		if ( $status ) {
			$transaction_status = '&' . $status . '=true';
		}
		if ( 'refund_status_url' === $status ) {
			$page_id = get_option( 'woocommerce_checkout_page_id', '' );
			return home_url( '/' ) . '?page_id=' . $page_id . '&order-pay=' . $order_id . '&key=' . $this->get_wc_order_property_value( 'order_key' ) . $transaction_status;
		} else {
			$key = Skrill_Payment::get_request_value( 'key' );
			if ( isset( $wp->request ) ) {
				return home_url( $wp->request ) . '/?key=' . $key . $transaction_status;
			} else {
				$order_pay = Skrill_Payment::get_request_value( 'order-pay' );
				return get_page_link() . '&order-pay=' . $order_pay . '&key=' . $key . $transaction_status;
			}
		}
	}

	/**
	 * Process payment response from 'status_url'
	 */
	public function process_status_response() {
		$wc_order_id = $this->wc_order->get_order_number();

		$this->add_log( 'Get payment response from status_url' );
		$payment_response = Skrill_Payment::get_status_response();
		$this->add_log( 'Payment response from status_url', $payment_response );

		$parent_order_transaction_log = Skrill_Transactions_Model::get_transaction_by_transaction_id(
			$payment_response['transaction_id']
		);

		if ( $parent_order_transaction_log ) {
			$existing_renewal_order = Skrill_Transactions_Model::get_transaction_by_mb_transaction_id(
				$payment_response['mb_transaction_id']
			);

			if ( ! $existing_renewal_order ) {
				$this->handle_renewal_payment_from_gateway( $payment_response );
			} else {
				$this->add_log( 'this renewal order already exist' );
				die( 'this renewal order already exist' );
			}
		} else {
			$skrill_settings['merchant_id'] = get_option( 'skrill_merchant_id', '' );
			$skrill_settings['secret_word'] = get_option( 'skrill_secret_word', '' );

			$generate_md5_sig                                = Skrill_Payment::generate_md5_sig( $skrill_settings, $payment_response );
			$is_payment_signature_equals_generated_signature = Skrill_Payment::is_payment_signature_equals_generated_signature( $payment_response['md5sig'], $generate_md5_sig );
			$payment_response['is_payment_signature_equals_generated_signature'] = $is_payment_signature_equals_generated_signature;
			if ( ! $is_payment_signature_equals_generated_signature ) {
				$payment_response['status'] = $this->skrill_not_verified_status;
				$this->add_log( 'Not verified detection' );
			}

			$is_fraud                     = Skrill_Payment::is_fraud( $payment_response['currency'], $wc_order_id, $payment_response['transaction_id'], $payment_response['secure_payment'] );
			$payment_response['is_fraud'] = $is_fraud;
			if ( $is_fraud ) {
				$payment_response['status'] = $this->skrill_fraud_status;
				$this->add_log( 'Fraud detection' );
			}

			$order_date = gmdate( 'd/m/Y', strtotime( $this->wc_order->order_date ) );

			$this->add_log( 'order_date is ' . $order_date );
			$this->add_log( 'current date ' . gmdate( 'd/m/Y' ) );

			$this->add_customer_note();
			$transaction = Skrill_Transactions_Model::get_transaction_by_order_id( $wc_order_id );

			if ( ! isset( $transaction ) ) {
				$transaction_log = $this->set_transaction_log( $payment_response );

				$this->add_log( 'save transaction log', $transaction_log );
				Skrill_Transactions_Model::save( $transaction_log );
				$this->add_log( 'Transaction log successfully saved' );

				$this->validate_payment_response( $payment_response );
			} elseif ( $transaction['transaction_id'] !== $payment_response['transaction_id'] ) {
				$transaction_log = $this->set_transaction_log( $payment_response );
				$this->add_log( 'update transaction log', $transaction_log );
				Skrill_Transactions_Model::update( $wc_order_id, $transaction_log );
				$this->add_log( 'Transaction log successfully updated' );

				$this->validate_payment_response( $payment_response );
			} else {
				$this->add_log( 'update status url' );
				if ( $transaction['payment_status'] === $this->skrill_pending_status ) {
					if ( $payment_response['status'] === $this->skrill_processed_status ) {
						$this->skrill_order_reduce_stock();
					}
					if ( ! $payment_response['is_payment_signature_equals_generated_signature'] ) {
						$this->update_order_status( $this->skrill_not_verified_status );
					} elseif ( $payment_response['is_fraud'] ) {
						$this->update_order_status( $this->skrill_fraud_status );
					} else {
						$this->update_order_status( $payment_response['status'] );
					}
					Skrill_Transactions_Model::update_payment_response( $wc_order_id, $payment_response );
					$this->add_log( 'Transaction log udpate response saved' );
				}
			}

			if ( WC()->session->get( 'skrill_late_status_url' ) ) {
				$this->add_log( 'redirect' );
				WC()->session->set( 'skrill_late_status_url', false );
				wp_safe_redirect( $this->get_return_url( $this->wc_order ) );
			}
			exit();
		}
	}
	/**
	 * Create renewal order using response from status_url
	 *
	 * @param  array  $payment_response payment response.
	 * @param  string $subscription property of class WC_Subscription.
	 */
	protected function process_subcription_renewal_order( $payment_response, $subscription ) {
		// // Generate a renewal order to record the failed payment
		$transaction_order = wcs_create_renewal_order( $subscription );

		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
		$payment_method     = 'skrill_' . strtolower( $payment_response['payment_type'] );
		$note               = $available_gateways[ $payment_method ]->method_title . ' payment approved (Recurring payment ID:' . $payment_response['rec_payment_id'];
		$transaction_order->add_order_note( $note );
		$transaction_order->set_payment_method( $available_gateways[ $payment_method ] );
		$transaction_order->update_status( get_option( 'skrill_completed_status', 'processing' ), 'order_note' );

		exit();
	}

	/**
	 * Process refund response from 'refund_status_url'
	 */
	protected function process_refund_status_response() {
		$this->add_log( 'Get refund response from refund_status_url' );
		$refund_response = Skrill_Payment::get_status_response( 'refund' );
		$this->add_log( 'Refund response from refund_status_url', $refund_response );

		$wc_order_id = $refund_response['order_id'];
		$transaction = Skrill_Transactions_Model::get_transaction_by_order_id( $wc_order_id );

		if ( isset( $transaction ) ) {
			if ( $transaction['payment_status'] === $this->skrill_refund_pending_status ) {
				if ( $refund_response['status'] === $this->skrill_processed_status ) {
					$payment_status = $this->skrill_refunded_status;
					$this->add_log( 'update payment status to refunded' );
				} else {
					$payment_status = $this->skrill_refund_failed_status;
					$this->add_log( 'update payment status to refund failed' );
				}
				Skrill_Transactions_Model::update_payment_status( $wc_order_id, $payment_status );
			}
		}
		exit();
	}

	/**
	 * Process payment response at 'return_url'
	 *
	 * @param string $order_id order id.
	 */
	protected function process_payment_response( $order_id ) {
		$transaction_id = Skrill_Payment::get_request_value( 'transaction_id' );

		for ( $i = 1; $i <= 10; $i++ ) {
			$this->add_log( 'get transaction log data: ', $i );
			$transaction = Skrill_Transactions_Model::get_transaction_by_transaction_id( $transaction_id );
			if ( $transaction ) {
				break;
			}
			sleep( 1 );
		}

		$this->add_log( 'return url transaction log data', $transaction );

		if ( $transaction ) {
			$payment_response = maybe_unserialize( $transaction['payment_response'] );
			$this->add_log( 'return url payment response', $payment_response );
			if ( $payment_response['status'] === $this->skrill_pending_status
				|| $payment_response['status'] === $this->skrill_processed_status
				|| $payment_response['status'] === $this->skrill_fraud_status
				|| $payment_response['status'] === $this->skrill_not_verified_status
			) {
				$this->add_log( 'Redirect to page success' );
				wp_safe_redirect( $this->get_return_url( $this->wc_order ) );
			} else {
				$error_identifier = 'Failure reason not specified';
				if ( $payment_response['status'] === $this->skrill_failed_status ) {
					$error_identifier = Skrill_Payment::get_skrill_error_mapping( $payment_response['failed_reason_code'] );
				}
				$this->add_log( 'Redirect Payment error', $error_identifier );
				$this->redirect_error_payment( $error_identifier );
			}
		} else {
			$this->add_log( 'status url late' );

			WC()->session->set( 'skrill_order_id', $order_id );
			WC()->session->set( 'skrill_late_status_url', true );

			if ( ! empty( WC()->session->order_awaiting_payment ) ) {
				unset( WC()->session->order_awaiting_payment );
			}

			WC()->cart->empty_cart();
			$this->redirect_error_payment( 'Please come back again after a few minutes and check your order histoy.' );
		}
		exit();
	}


	/**
	 * Validate payment response
	 * check fraud and status response
	 *
	 * @param array $payment_response - payment response.
	 */
	protected function validate_payment_response( $payment_response ) {
		if ( $payment_response['status'] === $this->skrill_processed_status
			|| $payment_response['status'] === $this->skrill_pending_status
			|| $payment_response['status'] === $this->skrill_fraud_status
			|| $payment_response['status'] === $this->skrill_not_verified_status
		) {
			$this->process_success_payment( $payment_response );
		} else {
			$error_identifier = 'Failure reason not specified';
			if ( $payment_response['status'] === $this->skrill_failed_status ) {
				$error_identifier =
				Skrill_Payment::get_skrill_error_mapping( $payment_response['failed_reason_code'] );
			}
			$this->add_log( 'Payment error', $error_identifier );
			$this->process_error_payment(
				$this->failed_status,
				$error_identifier,
				$payment_response['status'],
				false
			);
		}
	}

	/**
	 * Process fraud payment
	 *
	 * @param array $payment_response - payment response.
	 */
	protected function process_fraud_payment( $payment_response ) {
		$order_id                               = $this->wc_order->get_order_number();
		$refund_parameters['email']             = get_option( 'skrill_merchant_account', '' );
		$refund_parameters['password']          = get_option( 'skrill_api_passwd', '' );
		$refund_parameters['mb_transaction_id'] = $payment_response['mb_transaction_id'];
		$refund_parameters['amount']            = $payment_response['amount'];
		$refund_parameters_log                  = $refund_parameters;
		$refund_parameters_log['password']      = '*****';
		$this->add_log( 'Refund fraud parameters', $refund_parameters_log );

		$refund_response = Skrill_Payment::do_refund( $refund_parameters );
		$this->add_log( 'Refund fraud response', $refund_response );

		$refund_status = (string) $refund_response->status;
		if ( $refund_status === $this->skrill_processed_status
			|| $refund_status === $this->skrill_pending_status
		) {
			$payment_status = $this->skrill_refunded_status;
			$this->add_log( 'Payment is fraud and refunded' );
		} else {
			$payment_status = $this->skrill_refund_failed_status;
			$this->add_log( 'Payment is fraud and not refunded' );
		}
		$transaction_log                    = $this->set_transaction_log( $payment_response );
		$transaction_log['refunded_amount'] = $refund_response->mb_amount;
		$transaction_log['payment_status']  = $payment_status;
		Skrill_Transactions_Model::save( $transaction_log );
		$this->add_log( 'Transaction log successfully saved' );

		if ( $order_status === $this->pending_status ) {
			$this->skrill_order_reduce_stock();
		}
	}

	/**
	 * Set transaction log
	 *
	 * @param  array $payment_response - payment response.
	 * @return array
	 */
	protected function set_transaction_log( $payment_response = false ) {
		$transaction_log                      = array();
		$transaction_log['order_id']          = $this->wc_order->get_order_number();
		$transaction_log['payment_method_id'] = $this->payment_method_id;
		$transaction_log['amount']            =
		$payment_response['amount'] ? $payment_response['amount'] : $this->wc_order->get_total();
		$transaction_log['refunded_amount']   = '0';
		$transaction_log['currency']          = get_woocommerce_currency();
		$customer_id                          = $this->get_general_wc_order_property_value( 'user_id' );

		$transaction_log['customer_id'] = ( ! empty( $customer_id ) ) ? $customer_id : 0;

		if ( $payment_response ) {
			if ( isset( $payment_response['transaction_id'] ) ) {
				$transaction_log['transaction_id'] = $payment_response['transaction_id'];
			}
			if ( isset( $payment_response['mb_transaction_id'] ) ) {
				$transaction_log['mb_transaction_id'] = $payment_response['mb_transaction_id'];
			}
			$transaction_log['payment_type'] = $this->get_payment_type( $payment_response );
			if ( isset( $payment_response['status'] ) ) {
				$transaction_log['payment_status'] = $payment_response['status'];
			}
			$transaction_log['additional_information'] = $this->set_additional_information( $payment_response );
			$transaction_log['payment_response']       = maybe_serialize( $payment_response );
		}

		return $transaction_log;
	}

	/**
	 * Set additional information from payment response
	 * add order_origin, order country and skrill_account
	 *
	 * @param  array $payment_response - payment response.
	 * @return array
	 */
	protected function set_additional_information( $payment_response ) {
		$additional_information = '';
		$information            = array();
		if ( isset( $payment_response['ip_country'] ) && isset( $payment_response['payment_instrument_country'] ) ) {
			$information['order_origin']  = $payment_response['ip_country'];
			$information['order_country'] = $payment_response['payment_instrument_country'];
		}
		if ( isset( $payment_response['payment_type'] ) && 'WLT' === $payment_response['payment_type'] ) {
			if ( isset( $payment_response['pay_from_email'] ) ) {
				$information['skrill_account'] = $payment_response['pay_from_email'];
			}
		}
		$additional_information = maybe_serialize( $information );
		return $additional_information;
	}

	/**
	 * Get payment type from payment response
	 *
	 * @param  array $payment_response - payment response.
	 * @return string
	 */
	protected function get_payment_type( $payment_response ) {
		if ( ! empty( $payment_response['payment_type'] ) ) {
			if ( 'NGP' === $payment_response['payment_type'] ) {
				return 'OBT';
			} else {
				return $payment_response['payment_type'];
			}
		}
		return $this->payment_method;
	}

	/**
	 * Add customer notes at order detail
	 * add payment methods title
	 */
	protected function add_customer_note() {
		$new_line = "\n";

		$payment_method_title = __( 'Payment Method', 'wc-skrill' );
		$payment_comments     = $payment_method_title .
		' : ' .
		$this->get_title() .
		$new_line;

		$payment_comments = html_entity_decode( $payment_comments, ENT_QUOTES, 'UTF-8' );
		$customer_note    = $this->get_wc_order_property_value( 'customer_note' );

		if ( ! empty( $customer_note ) && strpos( $customer_note, __( 'Payment Method', 'wc-skrill' ) ) === false ) {
			$customer_note .= $new_line . $payment_comments;
		} else {
			$customer_note = $payment_comments;
		}
		$this->set_wc_order_property_value( 'customer_note', $customer_note );

		$order_notes = array(
			'ID'           => $this->wc_order->get_order_number(),
			'post_excerpt' => $this->get_wc_order_property_value( 'customer_note' ),
		);
		wp_update_post( $order_notes );
	}

	/**
	 * Success payment action
	 * update order status, reduce order stock, clear the cart and redirect to success page
	 *
	 * @param array $payment_response - payment response.
	 */
	protected function process_success_payment( $payment_response ) {
		if ( ! empty( WC()->session->order_awaiting_payment ) ) {
			unset( WC()->session->order_awaiting_payment );
		}
		if ( ! $payment_response['is_payment_signature_equals_generated_signature'] ) {
			$this->wc_order->update_status( $this->onhold_status, 'order_note' );
		} elseif ( $payment_response['is_fraud'] ) {
			$this->wc_order->update_status( $this->onhold_status, 'order_note' );
		} elseif ( $payment_response['status'] === $this->skrill_processed_status ) {
			$this->wc_order->update_status( get_option( 'skrill_completed_status', 'processing' ), 'order_note' );
		}

		$this->skrill_order_reduce_stock();

		$this->add_log( 'Payment has been successfully with status : ' . $payment_response['status'] );

		WC()->cart->empty_cart();
	}

	/**
	 * Error payment action
	 * update order status, cancel the order and redirect to error page
	 *
	 * @param string        $order_status     - order status.
	 * @param string        $error_identifier - error identifier.
	 * @param boolean|array $payment_status   - payment status.
	 * @param boolean       $is_redirect      - is redirect.
	 */
	protected function process_error_payment( $order_status, $error_identifier, $payment_status = false, $is_redirect = true ) {
		$error_translated = Skrill_Configuration::translate_error_identifier( $error_identifier );
		if ( $payment_status ) {
			Skrill_Transactions_Model::update_payment_status( $this->wc_order->get_order_number(), $payment_status );
		} else {
			$transaction_log                   = $this->set_transaction_log();
			$transaction_log['payment_status'] = null;
			if ( $order_status === $this->pending_status ) {
				$transaction_log['payment_status'] = $this->skrill_pending_status;
			} elseif ( $order_status === $this->failed_status ) {
				$transaction_log['payment_status'] = $this->skrill_pending_status;
			}
			Skrill_Transactions_Model::save_error_transaction( $transaction_log );
		}

		$this->wc_order->update_status( $order_status, 'order_note : ' . $error_translated );

		if ( ! empty( WC()->session->order_awaiting_payment ) ) {
			unset( WC()->session->order_awaiting_payment );
		}

		if ( $order_status === $this->pending_status ) {
			$this->skrill_order_reduce_stock();
		}

		WC()->session->errors = $error_translated;
		wc_add_notice( $error_translated, 'error' );

		$this->add_log( 'Payment has not been successfully completed' );

		if ( $is_redirect ) {
			wp_safe_redirect( WC()->cart->get_checkout_url() );
		}
		exit();
	}

	/**
	 * Redirect error payment action
	 * redirect to error page
	 *
	 * @param string $error_identifier - error identifier.
	 */
	protected function redirect_error_payment( $error_identifier ) {
		$error_translated     = Skrill_Configuration::translate_error_identifier( $error_identifier );
		WC()->session->errors = $error_translated;
		wc_add_notice( $error_translated, 'notice' );
		wp_safe_redirect( WC()->cart->get_checkout_url() );
		exit();
	}

	/**
	 * Update order status
	 *
	 * @param string $payment_status - payment status.
	 */
	protected function update_order_status( $payment_status ) {
		$order_status = false;
		if ( $payment_status === $this->skrill_processed_status ) {
			$order_status = get_option( 'skrill_completed_status', 'processing' );
		} elseif ( $payment_status === $this->skrill_pending_status ) {
			$order_status = $this->pending_status;
		} elseif ( $payment_status === $this->skrill_failed_status ) {
			$order_status = $this->failed_status;
		} elseif ( $payment_status === $this->skrill_fraud_status ) {
			$order_status = $this->onhold_status;
		} elseif ( $payment_status === $this->skrill_not_verified_status ) {
			$order_status = $this->onhold_status;
		}
		$this->add_log( 'Update order status : ' . $order_status . ' (' . $payment_status . ')' );
		Skrill_Transactions_Model::update_payment_status( $this->wc_order->get_order_number(), $payment_status );
		$this->wc_order->update_status( $order_status, 'order_note' );
		$this->add_log( 'payment order has been successfully updated' );
	}

	/**
	 * [BACKEND] Render additional information at backend
	 * show payment title, payment status, order_origin, order_country, currency, transaction id & skrill account email
	 * from hook "woocommerce_admin_order_data_after_shipping_address"
	 */
	public function render_additional_information() {
		if ( ! self::$added_meta_boxes ) {
			$order_id = Skrill_Payment::get_request_value( 'post' );

			$order = new WC_Order( $order_id );

			$transaction = Skrill_Transactions_Model::get_transaction_by_order_id( $order_id );

			$is_skrill_payment = Skrill_Configuration::is_skrill_payment( $transaction['payment_method_id'] );
			if ( $is_skrill_payment && str_contains( $order->get_payment_method(), 'skrill' ) ) {
				$additional_information = '';
				if ( isset( $transaction['additional_information'] ) ) {
					$additional_information =
					$this->validate_additional_information( $transaction['additional_information'] );
				}

				$payment_status_identifier =
				Skrill_Payment::get_transaction_status( $transaction['payment_status'] );

				$class_name = 'Gateway_Skrill_' . strtoupper( str_replace( 'skrill_', '', $transaction['payment_method_id'] ) );
				if ( class_exists( $class_name ) ) {
					$payment_method_instance = new $class_name();
					$payment_method_title    = $payment_method_instance->get_title();

					$class_name              = 'Gateway_Skrill_' . strtoupper( str_replace( "skrill_", "", $transaction['payment_method_id'] ) );
					$payment_method_instance = new $class_name();
					$payment_type            = $payment_method_instance->get_title();

					wc_get_template(
						'additional-information.php',
						array(
							'payment_method_title'   => $payment_method_title,
							'payment_type'           => $payment_type,
							'payment_status'         =>
							Skrill_Configuration::translate_transaction_status_identifier( $payment_status_identifier ),
							'transaction'            => $transaction,
							'additional_information' => $additional_information,
						),
						$this->plugin_directory . 'templates/admin/order/',
						$this->plugin_directory . 'templates/admin/order/'
					);
				}
			}
			self::$added_meta_boxes = true;
		}
	}

	/**
	 * Validate additional information
	 *
	 * @param  array $additional_information - additional information.
	 * @return array
	 */
	protected function validate_additional_information( $additional_information ) {
		$additional_information = maybe_unserialize( $additional_information );
		if ( isset( $additional_information['order_origin'] ) ) {
			$additional_information['order_origin'] =
			WC()->countries->countries[ $additional_information['order_origin'] ];
		}
		if ( isset( $additional_information['order_country'] ) ) {
			$origin_iso2                             = Skrill_Payment::get_country_iso2_by_iso3( $additional_information['order_country'] );
			$additional_information['order_country'] =
			WC()->countries->countries[ $origin_iso2 ];
		}

		return $additional_information;
	}

	/**
	 * [BACKEND] Update order from backend
	 * render update order button and process update order from gateway
	 * from hook "woocommerce_admin_order_data_after_order_details"
	 */
	public function update_order() {
		$post_type = Skrill_Payment::get_request_value( 'post_type' );

		if ( ! self::$updated_meta_boxes && 'shop_order' !== $post_type ) {
			$order_id          = Skrill_Payment::get_request_value( 'post' );
			$transaction       = Skrill_Transactions_Model::get_transaction_by_order_id( $order_id );
			$is_skrill_payment = Skrill_Configuration::is_skrill_payment( $transaction['payment_method_id'] );
			if ( $is_skrill_payment ) {

				$this->wc_order = new WC_Order( $order_id );
				$order_status   = $this->wc_order->get_status();
				if ( ( $order_status === $this->pending_status || $order_status === $this->onhold_status )
					&& ( $transaction['payment_status'] === $this->skrill_pending_status
					|| $transaction['payment_status'] === $this->skrill_not_verified_status
					|| $transaction['payment_status'] === $this->skrill_fraud_status )
				) {
					$request_section = Skrill_Payment::get_request_value( 'section' );

					if ( $order_id && 'update-order' === $request_section ) {
						$this->add_log( 'Start update order process' );
						$this->process_updated_order( $transaction, $order_status );
					}

					$update_order_url =
					get_admin_url() . 'post.php?post=' . $order_id . '&action=edit&section=update-order';

					$is_show_warning_message = false;
					$is_show_update_order    = true;
					$warning_message         = '';
					if ( $transaction['payment_status'] === $this->skrill_not_verified_status ) {
						$is_show_warning_message = true;
						$warning_message         = __( 'Skrill - Not Verified. Please check your Skrill settings.' );
					}
					if ( $transaction['payment_status'] === $this->skrill_fraud_status ) {
						$is_show_warning_message = true;
						$warning_message         = __( 'Skrill - Suspected fraud' );
						$is_show_update_order    = false;
					}

					wc_get_template(
						'update-order.php',
						array(
							'update_order_url'        => $update_order_url,
							'is_show_warning_message' => $is_show_warning_message,
							'warning_message'         => $warning_message,
							'is_show_update_order'    => $is_show_update_order,
						),
						$this->plugin_directory . 'templates/admin/order/',
						$this->plugin_directory . 'templates/admin/order/'
					);
				}
			}
			self::$updated_meta_boxes = true;
		}
	}

	/**
	 * [BACKEND] Process Updated Order
	 *
	 * @param array  $transaction  - transaction.
	 * @param string $order_status - order status.
	 */
	protected function process_updated_order( $transaction, $order_status ) {
		$payment_status = $this->get_payment_status( $transaction );

		$skrill_settings['merchant_id'] = get_option( 'skrill_merchant_id', '' );
		$skrill_settings['secret_word'] = get_option( 'skrill_secret_word', '' );

		$generate_md5_sig                                = Skrill_Payment::generate_md5_sig( $skrill_settings, $payment_status );
		$is_payment_signature_equals_generated_signature = Skrill_Payment::is_payment_signature_equals_generated_signature( $payment_status['md5sig'], $generate_md5_sig );

		if ( isset( $payment_status['status'] ) && $is_payment_signature_equals_generated_signature ) {
			if ( $order_status === $this->pending_status && $payment_status['status'] === $this->skrill_processed_status ) {
				$this->wc_order->update_status( get_option( 'skrill_completed_status', 'processing' ), 'order_note' );
			}
			$this->update_order_status( $payment_status['status'] );
			if ( $payment_status['status'] === $this->skrill_failed_status ) {
				$this->increase_order_stock();
			}
			$redirect = get_admin_url() . 'post.php?post=' . $this->wc_order->get_order_number() . '&action=edit';
			wp_safe_redirect( $redirect );
			exit;
		} elseif ( ! $is_payment_signature_equals_generated_signature ) {
			$error_message = __( 'Please check merchant ID or secret word', 'wc-skrill' );
		} else {
			$error_message = __( 'Order status can not be updated', 'wc-skrill' );
		}
		$this->redirect_order_detail( $error_message );
	}

	/**
	 * [BACKEND] Get payment status
	 * get payment status from gateway
	 *
	 * @param  array $transaction - transaction.
	 * @return array | void
	 */
	protected function get_payment_status( $transaction ) {
		$this->add_log( 'Get update order parameters' );
		$parameters['action']   = 'status_trn';
		$parameters['email']    = get_option( 'skrill_merchant_account', '' );
		$parameters['password'] = get_option( 'skrill_api_passwd', '' );
		$parameters['trn_id']   = $transaction['transaction_id'];

		$parameters_log             = $parameters;
		$parameters_log['password'] = '*****';
		$this->add_log( 'Update order parameters', $parameters_log );

		$payment_result = '';
		$this->add_log( 'Get updated order response' );
		$payment_status = Skrill_Payment::get_status_trn( $parameters, $payment_result );
		if ( 'SUCCESS' === $payment_status ) {
			$this->add_log( 'Updated order response', $payment_result );
			return $payment_result;
		}
		$this->add_log( 'No updated order responses from gateway : ' . $payment_status );
		$this->redirect_payment_status_error( $payment_status );
	}

	/**
	 * Redirect payment status error
	 *
	 * @param  string $payment_status - payment status.
	 * @return void
	 */
	protected function redirect_payment_status_error( $payment_status ) {
		$error_message = __( 'Order status can not be updated', 'wc-skrill' );
		if ( 'ACCOUNT_LOCKED' === $payment_status ) {
			$error_message = __( 'Your account is currently locked. Please contact our Merchant Team: merchantservices@skrill.com', 'wc-skrill' );
		} elseif ( 'CANNOT_LOGIN' === $payment_status ) {
			$error_message = __( 'Please check MQI/API password', 'wc-skrill' );
		}
		$this->redirect_order_detail( $error_message );
	}

	/**
	 * Is_refund_status_error
	 *
	 * @param  string $refund_status        - refund status.
	 * @param  bool   $is_use_refund_button - is use refund button.
	 * @return bool | void
	 */
	protected function is_refund_status_error( $refund_status, $is_use_refund_button ) {
		$error_message = false;
		if ( 'GENERAL_ERROR' === $refund_status ) {
			$error_message = __( 'Unfortunately, your attempt to refund the payment failed.', 'wc-skrill' );
		} elseif ( 'ACCOUNT_LOCKED' === $refund_status ) {
			$error_message = __( 'Your account is currently locked. Please contact our Merchant Team: merchantservices@skrill.com', 'wc-skrill' );
		} elseif ( 'CANNOT_LOGIN' === $refund_status ) {
			$error_message = __( 'Please check MQI/API password', 'wc-skrill' );
		}
		if ( $error_message ) {
			if ( $is_use_refund_button ) {
				return true;
			}
			$this->redirect_order_detail( $error_message );
		}
		return false;
	}

	/**
	 * [BACKEND] Process refund from refund button at backend
	 * Override from class WC_Payment_Gateway
	 *
	 * @param  int    $order_id - order id.
	 * @param  float  $amount   - amount.
	 * @param  string $reason   - reason.
	 * @return boolean
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$this->wc_order = new WC_Order( $order_id );

		$transaction       = Skrill_Transactions_Model::get_transaction_by_order_id( $order_id );
		$is_skrill_payment = Skrill_Configuration::is_skrill_payment( $transaction['payment_method_id'] );

		if ( $is_skrill_payment
			&& null !== $amount
			&& ( $transaction['payment_status'] === $this->skrill_processed_status
			|| $transaction['payment_status'] === $this->skrill_refund_failed_status
			|| $transaction['payment_status'] === $this->skrill_refunded_status )
		) {
			$is_refunded_payment = $this->is_refunded_payment( $transaction['mb_transaction_id'], $amount, true );
			return $is_refunded_payment;
		}
		return false;
	}

	/**
	 * [BACKEND] Save Order Meta Boxes when change order status at backend
	 * from hook "woocommerce_process_shop_order_meta"
	 */
	public function save_order_meta() {
		if ( ! self::$saved_meta_boxes ) {

			$original_post_status = Skrill_Payment::get_request_value( 'original_post_status' );

			if ( 'auto-draft' !== $original_post_status ) {
				$order_id          = Skrill_Payment::get_request_value( 'post_ID' );
				$transaction       = Skrill_Transactions_Model::get_transaction_by_order_id( $order_id );
				$is_skrill_payment = Skrill_Configuration::is_skrill_payment( $transaction['payment_method_id'] );

				if ( $is_skrill_payment ) {
					$this->wc_order = new WC_Order( $order_id );
					$this->change_order_status();
				}
			}
			self::$saved_meta_boxes = true;
		}
	}

	/**
	 * [BACKEND] Change Order Status at backend
	 * validate order status and get update status from gateway
	 */
	protected function change_order_status() {
		$order_id             = $this->wc_order->get_order_number();
		$transaction          = Skrill_Transactions_Model::get_transaction_by_order_id( $order_id );
		$original_post_status = Skrill_Payment::get_request_value( 'original_post_status' );
		$order_post_status    = Skrill_Payment::get_request_value( 'order_status' );

		if ( $original_post_status === $this->wc_pending_status
			&& $transaction['payment_status'] === $this->skrill_pending_status
			&& ( $order_post_status === $this->wc_processing_status || $order_post_status === $this->wc_completed_status )
		) {
			$this->update_pending_status( $order_post_status, $transaction );
		} elseif ( ( $original_post_status === $this->wc_processing_status
			|| $original_post_status === $this->wc_completed_status )
			&& $transaction['payment_status'] === $this->skrill_refund_pending_status
			&& $order_post_status === $this->wc_refunded_status
		) {
			$error_message = __( 'You can not refund at this moment because the refund status was pending before. Please try again when the refund status is not pending', 'wc-skrill' );
			$this->redirect_order_detail( $error_message );
		} elseif ( ( $original_post_status === $this->wc_processing_status || $original_post_status === $this->wc_completed_status )
			&& ( $transaction['payment_status'] === $this->skrill_processed_status
			|| $transaction['payment_status'] === $this->skrill_refund_failed_status )
			&& $order_post_status === $this->wc_refunded_status
		) {
			$is_refunded_payment =
			$this->is_refunded_payment( $transaction['mb_transaction_id'], $this->wc_order->get_total() );
			if ( ! $is_refunded_payment ) {
				$error_message = __( 'Unfortunately, your attempt to refund the payment failed.', 'wc-skrill' );
				$this->redirect_order_detail( $error_message );
			}
			$this->increase_order_stock();
		} elseif ( $original_post_status === $this->wc_pending_status
			&& $transaction['payment_status'] === $this->skrill_pending_status
			&& ( $order_post_status === $this->wc_cancelled_status
			|| $order_post_status === $this->wc_failed_status )
		) {
			$this->update_pending_status( $order_post_status, $transaction );
		}
	}

	/**
	 * [BACKEND] Update pending status
	 *
	 * @param string $order_post_status - order post status.
	 * @param array  $transaction       - transaction.
	 */
	protected function update_pending_status( $order_post_status, $transaction ) {
		$payment_status = $this->get_payment_status( $transaction );
		if ( $order_post_status === $this->wc_processing_status || $order_post_status === $this->wc_completed_status ) {
			$transaction_status = $this->skrill_processed_status;
		} else {
			$transaction_status = $this->skrill_failed_status;
		}
		if ( $payment_status && $payment_status['status'] === $transaction_status ) {
			$this->update_order_status( $payment_status['status'] );
			if ( $transaction_status === $this->skrill_failed_status ) {
				$this->increase_order_stock();
			}
		} else {
			$error_message = __( 'Order status can not be updated', 'wc-skrill' );
			$this->redirect_order_detail( $error_message );
		}
	}

	/**
	 * [BACKEND] Refund Payment at backend
	 * refund payment and update status
	 *
	 * @param  string $mb_transaction_id    - mb transaction id.
	 * @param  float  $amount               - amount.
	 * @param  bool   $is_use_refund_button - is use refund_button.
	 * @return bool
	 */
	protected function is_refunded_payment( $mb_transaction_id, $amount, $is_use_refund_button = false ) {
		$order_id = $this->wc_order->get_order_number();
		$this->add_log( 'Start refund payment process' );

		$this->add_log( 'Get refund parameters' );
		$refund_parameters['email']             = get_option( 'skrill_merchant_account', '' );
		$refund_parameters['password']          = get_option( 'skrill_api_passwd', '' );
		$refund_parameters['mb_transaction_id'] = $mb_transaction_id;
		$refund_parameters['amount']            = $amount;
		$refund_parameters['refund_status_url'] = $this->get_receipt_page_url( 'refund_status_url', $order_id ) . '&order_id=' . $order_id;
		$refund_parameters_log                  = $refund_parameters;
		$refund_parameters_log['password']      = '*****';
		$this->add_log( 'Refund parameters', $refund_parameters_log );

		$this->add_log( 'Get refund response' );
		$refund_response = Skrill_Payment::do_refund( $refund_parameters );
		$this->add_log( 'Refund response', $refund_response );

		$is_refund_status_error = $this->is_refund_status_error( $refund_response, $is_use_refund_button );
		if ( $is_refund_status_error ) {
			return false;
		}

		$refund_status = (string) $refund_response->status;
		$this->add_log( 'update payment status' );

		if ( $refund_status === $this->skrill_processed_status ) {
			Skrill_Transactions_Model::update_refunded_status(
				$order_id,
				$this->skrill_refunded_status,
				$refund_response->mb_amount
			);
			$this->add_log( 'payment status has been successfully updated to refund successfull' );
			return true;
		} elseif ( $refund_status === $this->skrill_pending_status ) {
			Skrill_Transactions_Model::update_refunded_status( $order_id, $this->skrill_refund_pending_status );
			$this->add_log( 'payment status has been successfully updated to refund pending' );
			return false;
		} else {
			Skrill_Transactions_Model::update_refunded_status( $order_id, $this->skrill_refund_failed_status );
			$this->add_log( 'payment status has been successfully updated to refund failed' );
			return false;
		}
		return false;
	}

	/**
	 * [BACKEND] Increase Order Stock
	 * increase order stock and add note to order detail
	 *
	 * @return void
	 */
	protected function increase_order_stock() {
		$items = $this->wc_order->get_items();
		foreach ( $items as $item ) {
			$wc_product       = $this->wc_order->get_product_from_item( $item );
			$is_managed_stock = $this->is_managed_stock( $item['product_id'] );
			if ( $is_managed_stock ) {
				$product_stock = $wc_product->get_stock_quantity();
				if ( $this->is_version_greater_than( '3.0' ) ) {
					wc_update_product_stock( $wc_product, $item['qty'], 'increase' );
				} else {
					$wc_product->increase_stock( $item['qty'] );
				}
				$order_note = 'Item #' . $item['product_id'] .
				' stock increased from ' . $product_stock . ' to ' . ( $product_stock + $item['qty'] );
				$this->wc_order->add_order_note( $order_note );
			}
		}
	}

	/**
	 * [BACKEND] Is managed stock
	 *
	 * @param  int $product_id - product id.
	 * @return bool
	 */
	protected function is_managed_stock( $product_id ) {
		$is_managed_stock = get_post_meta( $product_id, '_manage_stock', true );
		if ( 'yes' === $is_managed_stock ) {
			return true;
		}
		return false;
	}

	/**
	 * [BACKEND] Redirect Order Detail
	 * redirect to order detail and show error message if exist
	 *
	 * @param boolean|string $error_message - error message.
	 */
	protected function redirect_order_detail( $error_message = false ) {
		$redirect = get_admin_url() . 'post.php?post=' . $this->wc_order->get_order_number() . '&action=edit';
		if ( $error_message ) {
			WC_Admin_Meta_Boxes::add_error( $error_message );
		}
		wp_safe_redirect( $redirect );
		exit;
	}

	/**
	 * Add log for debugging
	 *
	 * @param string            $message  - message.
	 * @param bool|string|array $data     - data.
	 * @param bool|int          $order_id - order id.
	 */
	public function add_log( $message, $data = false, $order_id = false ) {
		if ( ! $order_id ) {
			$order_id = $this->wc_order->get_order_number();
		}

		$logger_message = '[Order Id : ' . $order_id . '] ' . $message;

		if ( $data ) {
			if ( is_object( $data ) ) {
				$logger_message .= " : Xml ( \r\n";
				foreach ( $data->children() as $child ) {
					$logger_message .= '(' . $child->getName() . ') => ' . $child . "\r\n";
				}
				$logger_message .= ')';
			} elseif ( is_array( $data ) ) {
				$logger_message .= " : Array [ \r\n";
				foreach ( $data as $key => $value ) {
					if ( is_bool( $value ) ) {
						if ( $value ) {
							$value = 'true';
						} else {
							$value = 'false';
						}
					}
					$logger_message .= '[' . $key . '] => ' . $value . "\r\n";
				}
				$logger_message .= ']';
			} else {
				$logger_message .= ' : ' . $data;
			}
		}

		$this->skrill_log->add( $this->logger_handle, $logger_message . "\r\n" );
	}

	/**
	 * Excute renewal order when time come
	 *
	 * @param string $amount_to_charge float The amount to charge.
	 * @param object $renewal_order WC_Order A WC_Order object created to record the renewal payment.
	 */
	public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
		$this->wc_order = $renewal_order;
		if ( 'skrill_vsa' === $this->id || 'skrill_msc' === $this->id ) {
			$this->add_log( 'execute renewal order' );
			$this->add_log( 'amount of renewal order ' . $amount_to_charge );
			$this->process_subscription_payment( $amount_to_charge, $renewal_order, true, false );
		} else {
			$this->add_log( 'this payment method id not support recurring payment ' . $this->payment_id );
		}
	}

	/**
	 * Process_subscription_payment function.
	 *
	 * @param float  $amount renewal amount.
	 * @param mixed  $renewal_order renewal order.
	 * @param bool   $retry Should we retry the process.
	 * @param object $previous_error the previous error for renewal payment.
	 * @throws Exception $e Exception.
	 */
	public function process_subscription_payment( $amount, $renewal_order, $retry = true, $previous_error = false ) {
		try {
			if ( wcs_order_contains_renewal( $renewal_order->id ) ) {

				$this->add_log( 'process subscription' );
				$parent_order_id         = WC_Subscriptions_Renewal_Order::get_parent_order_id( $renewal_order->id );
				$parent_transaction_log  = Skrill_Transactions_Model::get_parent_transaction_by_order_id( $parent_order_id );
				$renewal_transaction_log = Skrill_Transactions_Model::get_transaction_by_parent_order_id_and_customer_id( $parent_order_id );

				if ( $parent_transaction_log && $renewal_transaction_log ) {

					$this->add_log( 'process subscription using viveum payment' );

					if ( '2' === $renewal_transaction_log['payment_status'] ) {
						$this->without_redirect = true;
						$this->skrill_do_success_renewal_payment( $renewal_order->id, $renewal_transaction_log );
					} else {
						$this->add_log( 'cant do renewal order because gateway return status : ' . $renewal_transaction_log['payment_status'] );
						throw new Exception( 'cant do renewal order because gateway return status ' ) . $renewal_transaction_log['payment_status'];
					}
				} else {
					$this->add_log( 'renewal order already exist' );
				}
			} else {
				$this->add_log( 'cant process non subscription order' );
			}
		} catch ( Exception $e ) {
			$this->add_log( 'Error: ' . $e->getMessage() );
			$renewal_order->update_status( 'wc-failed', 'failed renew order' );
			if ( is_callable( array( $renewal_order, 'save' ) ) ) {
				$renewal_order->save();
			}
		}
	}

	/**
	 * Run after payment successful
	 *
	 * @param int   $order_id order id.
	 * @param array $payment_result payment result.
	 */
	public function skrill_do_success_renewal_payment( $order_id, $payment_result ) {

		$order = new WC_Order( $order_id );
		$order->update_status( get_option( 'skrill_completed_status', 'processing' ), 'order_note' );
		$payment_result['customer_id'] = ( $order->get_user_id() ) ? $order->get_user_id() : 0;
		Skrill_Transactions_Model::update_by_transaction_id( $order_id, $payment_result );

		// Reduce stock levels.
		if ( $this->is_version_greater_than( '3.0' ) ) {
			wc_reduce_stock_levels( $order_id );
		} else {
			$order->reduce_order_stock();
		}
	}


	/**
	 * Handle recurring response from skrill gateway
	 *
	 * @param object $payment_response payment response.
	 */
	public function handle_renewal_payment_from_gateway( $payment_response ) {
		$skrill_log    = new WC_Logger();
		$logger_handle = 'skrill-' . gmdate( 'Ym' );

		$skrill_log->add( $logger_handle, 'incoming renewal payment from status url' . "\r\n" );
		$skrill_log->add( $logger_handle, 'Payment response from renewal status_url', $payment_response . "\r\n" );
		$skrill_settings['merchant_id'] = get_option( 'skrill_merchant_id', '' );
		$skrill_settings['secret_word'] = get_option( 'skrill_secret_word', '' );

		$generate_md5_sig                                = Skrill_Payment::generate_md5_sig( $skrill_settings, $payment_response );
		$is_payment_signature_equals_generated_signature = Skrill_Payment::is_payment_signature_equals_generated_signature( $payment_response['md5sig'], $generate_md5_sig );
		$payment_response['is_payment_signature_equals_generated_signature'] = $is_payment_signature_equals_generated_signature;
		if ( ! $is_payment_signature_equals_generated_signature ) {
			$skrill_log->add( $logger_handle, 'Fraud detection' . "\r\n" );
			echo 'Your payment is suspected as fraud, please contact merchant';
			exit();
		}

		$skrill_log->add( $logger_handle, 'current date ' . gmdate( 'd/m/Y' ) . "\r\n" );

		if ( ! empty( $payment_response['rec_payment_type'] ) && ! empty( $payment_response['rec_payment_id'] )
		&& ! empty( $payment_response['transaction_id'] ) ) {
			$skrill_log->add( $logger_handle, 'process add rec payment into transction log' . "\r\n" );

			$transaction_log = Skrill_Transactions_Model::get_transaction_by_transaction_id(
				$payment_response['transaction_id']
			);

			if ( $transaction_log ) {

				$parent_status_url_response = maybe_unserialize( $transaction_log['payment_response'] );

				if ( $parent_status_url_response['rec_payment_id'] !== $payment_response['rec_payment_id'] ) {
					$skrill_log->add( $logger_handle, 'no parent rec id found' . "\r\n" );
					echo 'no parent rec id found';
					exit();
				}

				$date_time                                = Skrill_Payment::get_current_datetime();
				$random_number                            = Skrill_Payment::get_random_number( 4 );
				$transaction_log_params                   = transaction_log_parameters_converter( $payment_response );
				$transaction_log_params['transaction_id'] = gmdate( 'ymd' ) . $payment_response['order_id'] . $date_time . $random_number;
				Skrill_Transactions_Model::save_rec_payment( $transaction_log_params );

				$skrill_log->add( $logger_handle, 'success add rec payment to transaction log' . "\r\n" );

				echo 'success add rec payment to transaction log';
				exit();
			}
			$skrill_log->add( $logger_handle, 'parent transaction is not found' . "\r\n" );
			echo 'parent transaction is not found';
			exit();
		} else {
			$skrill_log->add( $logger_handle, 'required paramter is missing' . "\r\n" );
			echo 'required paramter is missing';
			exit();
		}
	}

	/**
	 * Convert renwewal payment response into transaction log params
	 *
	 * @param  array $payment_response - payment response.
	 * @return array
	 */
	public function transaction_log_parameters_converter( $payment_response = false ) {
		$transaction_log                      = array();
		$transaction_log['order_id']          = $payment_response['order_id'];
		$transaction_log['payment_method_id'] = $payment_response['payment_method_id'];
		$transaction_log['amount']            = $payment_response['amount'];
		$transaction_log['refunded_amount']   = '0';
		$transaction_log['currency']          = $payment_response['currency'];

		$transaction_log['customer_id'] = 0;

		if ( $payment_response ) {
			if ( isset( $payment_response['transaction_id'] ) ) {
				$transaction_log['transaction_id'] = $payment_response['transaction_id'];
			}
			if ( isset( $payment_response['mb_transaction_id'] ) ) {
				$transaction_log['mb_transaction_id'] = $payment_response['mb_transaction_id'];
			}
			$transaction_log['payment_type'] = get_rec_payment_type( $payment_response );
			if ( isset( $payment_response['status'] ) ) {
				$transaction_log['payment_status'] = $payment_response['status'];
			}
			$transaction_log['additional_information'] = set_rec_payment_additional_information( $payment_response );
			$transaction_log['payment_response']       = maybe_serialize( $payment_response );
		}

		return $transaction_log;
	}

	/**
	 * Set additional information for renewal paymen
	 *
	 * @param  array $payment_response - payment response.
	 * @return array
	 */
	public function set_rec_payment_additional_information( $payment_response ) {
		$additional_information = '';
		$information            = array();
		if ( isset( $payment_response['ip_country'] ) && isset( $payment_response['payment_instrument_country'] ) ) {
			$information['order_origin']  = $payment_response['ip_country'];
			$information['order_country'] = $payment_response['payment_instrument_country'];
		}
		if ( isset( $payment_response['payment_type'] ) && 'WLT' === $payment_response['payment_type'] ) {
			if ( isset( $payment_response['pay_from_email'] ) ) {
				$information['skrill_account'] = $payment_response['pay_from_email'];
			}
		}
		$additional_information = maybe_serialize( $information );
		return $additional_information;
	}

	/**
	 * Get payment type of renewal payment
	 *
	 * @param  array $payment_response - payment response.
	 * @return string
	 */
	public function get_rec_payment_type( $payment_response ) {
		if ( ! empty( $payment_response['payment_type'] ) ) {
			if ( 'NGP' === $payment_response['payment_type'] ) {
				return 'OBT';
			} else {
				return $payment_response['payment_type'];
			}
		}
		return $this->payment_method;
	}

}
