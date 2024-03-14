<?php
/**
 * WooCommerce Plugin
 *
 * @package     Splitit_WooCommerce_Plugin
 *
 * Plugin Name: Splitit - WooCommerce plugin
 * Plugin URI: https://github.com/Splitit/Splitit.Plugins.WooCommerce.FF
 * Description: Plugin available to WooCommerce users that would allow adding Splitit as a payment method at checkout.
 * Author: Splitit
 * Author URI: https://www.splitit.com/
 * Version: 4.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // @Exit if accessed directly
}

/*
 * @Global plugin ID
 */
global $plguin_id;
$plguin_id = 'splitit';

global $plugin_version;
$plugin_version = '4.1.4';

global $required_splitit_php_version;
$required_splitit_php_version = '7.0';

global $current_php_version;
$current_php_version = phpversion();

/**
 * Check WC is active
 *
 * @Check that WooCommerce is active
 */
require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	return;
}

add_filter( 'upgrader_pre_install', 'php_version_check_function', 99, 2 );

function php_version_check_function( $return, $plugin ) {
	if ( plugin_basename( __FILE__ ) === $plugin['plugin'] ) {

		global $required_splitit_php_version;
		global $current_php_version;

		if ( version_compare( $current_php_version, $required_splitit_php_version, '<' ) ) {
			$return = new WP_Error( 'php_version', 'The plugin requires a PHP version >= ' . $required_splitit_php_version . ' to update. Your current version ' . $current_php_version . '.' );
		}

		return $return;
	}
}

define( 'URL', get_site_url() );
define( 'DOMAIN', parse_url( URL, PHP_URL_HOST ) );
const SLACK_KEY_1 = 'TGYNZCYCC';
const SLACK_KEY_2 = 'B03BBNA77DZ';
const SLACK_KEY_3 = 'Xw97npf5R1hHI8OLl615Tgwy';

const SL_T_1 = 'xoxb';
const SL_T_2 = '576781440420';
const SL_T_3 = '6542608850293';
const SL_T_4 = 'IkIJduPQnAYsJPgxqgO04IlV';

/**
 * Send notification to slack when plugin is activated
 */
function splitit_activate() {
	global $required_splitit_php_version;
	global $current_php_version;

	if ( version_compare( $current_php_version, $required_splitit_php_version, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'The plugin requires at least PHP version 7.0' );
	}

	// Check if the plugin was previously activated.
	$previous_activation_time = get_option( 'splitit_last_activation_time' );

	if ( $previous_activation_time && ( time() - $previous_activation_time ) < 3600 ) {
		// If the previous activation was less than an 1 hour ago, stop executing the function.
		return;
	}

	if ( 'my-wordpress-blog.local' != DOMAIN && 'localhost' != DOMAIN && '127.0.0.1' != DOMAIN ) {
		send_slack_notification( 'Splitit app has been activated \n Domain: <' . URL . '|' . DOMAIN . '> \n Platform: Woocommerce' );
		send_info( 'activate' );
	}

	// Save the activation time.
	update_option( 'splitit_last_activation_time', time() );
}
register_activation_hook( __FILE__, 'splitit_activate' );

/**
 * Send notification to slack when plugin is deactivated
 */
function splitit_deactivate() {
	// Check if the plugin was previously deactivated.
	$previous_deactivation_time = get_option( 'splitit_last_deactivation_time' );

	if ( $previous_deactivation_time && ( time() - $previous_deactivation_time ) < 3600 ) {
		// If the previous deactivation was less than an 1 hour ago, stop executing the function.
		return;
	}

	if ( 'my-wordpress-blog.local' != DOMAIN && 'localhost' != DOMAIN && '127.0.0.1' != DOMAIN ) {
		send_slack_notification( 'Splitit app has been deactivated \n Domain: <' . URL . '|' . DOMAIN . '> \n Platform: Woocommerce' );
		send_info( 'deactivate' );
	}

	// Save the deactivation time.
	update_option( 'splitit_last_deactivation_time', time() );
}
register_deactivation_hook( __FILE__, 'splitit_deactivate' );

/**
 * Function ot send a notification to Splitit internal Slack channel about activation and deactivation plugin.
 * Note: we do not send and private data, just domain. It's for internal statistic
 *
 * @param string $message Slack message.
 */
function send_slack_notification( $message ) {
	$msg = '{
		"blocks": [{
			"type": "section",
			"text": {
				"type": "mrkdwn",
				"text": "' . $message . '"
			}
		}]
	}';

	$c = curl_init( 'https://hooks.slack.com/services/' . SLACK_KEY_1 . '/' . SLACK_KEY_2 . '/' . SLACK_KEY_3 );
	curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $c, CURLOPT_POST, true );
	curl_setopt( $c, CURLOPT_POSTFIELDS, array( 'payload' => $msg ) );
	curl_exec( $c );
	curl_close( $c );
}

function send_slack_refund_notification( $message ) {
	$token = SL_T_1 . '-' . SL_T_2 . '-' . SL_T_3 . '-' . SL_T_4;
	$channel = '#plugins-refund-notifications-public';
	$apiUrl = 'https://slack.com/api/chat.postMessage';

	$msg = '{
	    "channel": "' . $channel . '",
		"blocks": [{
			"type": "section",
			"text": {
				"type": "mrkdwn",
				"text": "' . $message . '"
			}
		}]
	}';

	$options = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPHEADER => [
			'Content-type: application/json',
			'Authorization: Bearer ' . $token,
		],
		CURLOPT_POSTFIELDS => $msg,
	];

	$ch = curl_init($apiUrl);
	curl_setopt_array($ch, $options);

	curl_exec($ch);

	curl_close($ch);
}

/**
 * Function ot send a notification to internal Splitit system about activation and deactivation plugin.
 * Note: we do not send and private data, just domain. It's for internal statistic
 *
 * @param string $action_type Action type.
 */
function send_info( $action_type ) {
	$msg = array(
		'platform'   => 'Woocommerce',
		'actionType' => $action_type,
		'domain'     => DOMAIN,
	);

	$c = curl_init( 'https://internalapi.production.splitit.com/api/plugins-action-logs/save' );

	curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $c, CURLOPT_POST, true );
	curl_setopt( $c, CURLOPT_POSTFIELDS, http_build_query( $msg ) );
	curl_exec( $c );
	curl_close( $c );
}

add_filter( 'woocommerce_payment_gateways', 'splitit_flexfields_payment_plugin_gateway_class' );
/**
 * This action hook registers our PHP class as a WooCommerce payment gateway
 *
 * @param $gateways
 *
 * @return mixed
 */
function splitit_flexfields_payment_plugin_gateway_class( $gateways ) {
	$gateways[] = 'WC_SplitIt_FlexFields_Payment_Gateway';

	return $gateways;
}


add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'splitit_flexfields_payment_plugin_links' );
/**
 * Configure from plugin page
 *
 * @param $links
 *
 * @return array|string[]
 */
function splitit_flexfields_payment_plugin_links( $links ) {
	global $plguin_id;
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . esc_attr( $plguin_id ) ) . '">' . __( 'Configure', 'wc-splitit' ) . '</a>',
	);

	return array_merge( $plugin_links, $links );
}

/*
 * Include DB files for creating tables in DB
 */
require_once 'db/create-log-table.php';
require_once 'db/create-transactions-tracking-table.php';
require_once 'db/create-order-data-with-ipn.php';

//for async refunds
require_once 'db/create-async-refund-log-table.php';

/*
 * Add DB tables when activating the plugin
 */
if ( function_exists( 'splitit_flexfields_payment_plugin_create_log_table' ) ) {
	register_activation_hook( __FILE__, 'splitit_flexfields_payment_plugin_create_log_table' );
}
if ( function_exists( 'splitit_flexfields_payment_plugin_create_log_create_order_data_with_ipn' ) ) {
	register_activation_hook( __FILE__, 'splitit_flexfields_payment_plugin_create_log_create_order_data_with_ipn' );
}
if ( function_exists( 'splitit_flexfields_payment_plugin_create_transactions_tracking_table' ) ) {
	register_activation_hook( __FILE__, 'splitit_flexfields_payment_plugin_create_transactions_tracking_table' );
}
if ( function_exists( 'splitit_flexfields_payment_plugin_create_async_refund_log_table' ) ) {
	register_activation_hook( __FILE__, 'splitit_flexfields_payment_plugin_create_async_refund_log_table' );
	add_action( 'admin_init', 'splitit_flexfields_payment_plugin_create_async_refund_log_table' );
}

function add_check_refund_file() {
	require_once 'cron/check-refund-status.php';
}

/**
 * Redirect to the settings page after plugin activate
 *
 * @param $plugin
 */
function splitit_flexfields_payment_plugin_cyb_activation_redirect( $plugin ) {
	if ( plugin_basename( __FILE__ ) === $plugin ) {
		global $plguin_id;
		exit( wp_redirect( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . esc_attr( $plguin_id ) ) ) );
	}
}

add_action( 'activated_plugin', 'splitit_flexfields_payment_plugin_cyb_activation_redirect' );

if ( version_compare( $current_php_version, $required_splitit_php_version, '<' ) ) {
	deactivate_plugins( plugin_basename( __FILE__ ) );
	add_action( 'admin_notices', 'plugin_php_version_notice' );
	return;
}

/**
 * Notification message
 */
function plugin_php_version_notice() {
	$message = 'The new version of the plugin requires at least PHP version 8.1';
	echo '<div class="error"><p>' . $message . '</p></div>';
}

add_action( 'plugins_loaded', 'splitit_flexfields_payment_plugin_init_gateway_class' );

/**
 * The class itself, please note that it is inside plugins_loaded action hook
 */
function splitit_flexfields_payment_plugin_init_gateway_class() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	/*
	 * Include additional classes
	 */
	require_once 'classes/class-splitit-flexfields-payment-plugin-log.php';
	require_once 'classes/class-splitit-flexfields-payment-plugin-api.php';
	require_once 'classes/class-splitit-flexfields-payment-plugin-settings.php';
	require_once 'classes/class-splitit-flexfields-payment-plugin-checkout.php';
	require_once 'classes/traits/splitit-flexfields-payment-plugin-upstream-messaging-trait.php';

	/**
	 * Class WC_SplitIt_FlexFields_Payment_Gateway
	 */
	class WC_SplitIt_FlexFields_Payment_Gateway extends WC_Payment_Gateway {

		use SplitIt_FlexFields_Payment_Plugin_UpstreamMessagingTrait;

		/**
		 * Instance
		 *
		 * @var null
		 */
		public static $instance = null;

		/**
		 * DEFAULT_INSTALMENT_PLAN
		 */
		const DEFAULT_INSTALMENT_PLAN = 3;

		/**
		 * Get instance
		 *
		 * @return WC_SplitIt_FlexFields_Payment_Gateway|null
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * WC_SplitIt_FlexFields_Payment_Gateway constructor.
		 */
		public function __construct() {
			global $plguin_id;
			$this->id                 = $plguin_id; // @payment gateway plugin ID
			$this->has_fields         = true; // @in case you need a custom credit card form
			$this->title              = __( 'Monthly credit card payments - no fees', 'splitit_ff_payment' );
			$this->method_title       = __( 'Splitit - WooCommerce Plugin', 'splitit_ff_payment' );
			$this->method_description = '<span class="method-description">' . __( 'Splitit is an installments solution that lets your customers pay monthly with their existing credit cards, so they don’t need to take out a new loan. There are no applications, added interest or fees for the shopper to pay, so the checkout experience is fast and simple.', 'splitit_ff_payment' ) . '</span>'; // @will be displayed on the options page

			$this->pay_button_id     = 'splitit-btn-pay';
			$this->order_button_text = 'Place order';

			// @gateways can support subscriptions, refunds, saved payment methods
			$this->supports = array(
				'products',
				'refunds',
			);

			// @Method with all the options fields
			$this->init_form_fields();

			// @After init_settings() is called, you can get the settings and load them into variables, e.g:
			$this->init_settings();

			// @Turn these settings into variables we can use
			foreach ( $this->settings as $setting_key => $value ) {
				$this->$setting_key = $value;
			}

			// for async refunds
			global $settings_for_check_refund;
			$settings_for_check_refund = $this->settings;
			add_action('init', 'add_check_refund_file');

			// @This action hook changed order status
			add_action( 'woocommerce_thankyou', array( $this, 'woocommerce_payment_change_order_status' ) );

			// @This action hook saves the settings
			add_action(
				'woocommerce_update_options_payment_gateways_' . $this->id,
				array(
					$this,
					'process_admin_options',
				)
			);

			// @TODO Place to add ajax scripts for the front end
			$this->init_ajax_frontend_hook();

			add_action( 'parse_request', array( $this, 'splitit_custom_url_handler' ) );
		}

		/**
		 * Initiate frontend AJAX hooks
		 */
		public function init_ajax_frontend_hook() {
			add_action( 'wc_ajax_splitit_calculate_new_installment_price_cart_page', array( $this, 'calculate_new_installment_price_cart_page' ) );
			add_action( 'wc_ajax_splitit_calculate_new_installment_price_product_page', array( $this, 'calculate_new_installment_price_product_page' ) );
			add_action( 'wc_ajax_splitit_calculate_new_installment_price_checkout_page', array( $this, 'calculate_new_installment_price_checkout_page' ) );
			add_action( 'wc_ajax_splitit_flex_field_initiate_method', array( $this, 'flex_field_initiate_method' ) );
			add_action( 'wc_ajax_splitit_checkout_validate', array( $this, 'checkout_validate' ) );
			add_action( 'wc_ajax_splitit_order_pay_validate', array( $this, 'order_pay_validate' ) );
		}

		/**
		 * Plugin options
		 */
		public function init_form_fields() {
			$this->init_settings();

			$this->form_fields = SplitIt_FlexFields_Payment_Plugin_Settings::get_fields( $this->settings );
		}

		/**
		 * Custom payment form
		 */
		public function payment_fields() {
			if ( ! is_ajax() && ! is_wc_endpoint_url( 'order-pay' ) ) {
				return;
			}

			$sandbox = true;

			if ( 'sandbox' === $this->splitit_environment ) {
				$sandbox = true;
			} elseif ( 'production' === $this->splitit_environment ) {
				$sandbox = false;
			}

			// @I will echo() the form, but you can close PHP tags and print it directly in HTML
			echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';

			// @Add this action hook if you want your custom payment gateway to support it
			do_action( 'woocommerce_splitit_form_start', $this->id );

			global $wp;
			$order_id = $wp->query_vars['order-pay'] ?? null;

			$d_3 = (bool) $this->splitit_settings_3d;

			$flex_fields_form = file_get_contents( __DIR__ . '/template/flex-field-index.php' );

			$tmp    = str_replace( '<order_id>', $order_id, $flex_fields_form );
			$tmp2   = str_replace( '<debug>', $sandbox, $tmp );
			$tmp3   = str_replace( '<3ds>', $d_3, $tmp2 );
			$result = str_replace( '<culture>', str_replace( '_', '-', get_locale() ), $tmp3 );

			echo $result;

			do_action( 'woocommerce_splitit_form_end', $this->id );

			echo '<div class="clear"></div></fieldset>';
		}

		/**
		 * Method that process payment for the order
		 *
		 * @param int $order_id
		 *
		 * @return array|void
		 */
		public function process_payment( $order_id ) {
			global $woocommerce;

			if ( ! is_ssl() ) {
				wc_add_notice( __( 'Please ensure your site supports SSL connection.', 'splitit_ff_payment' ), 'error' );

				return;
			}

			// @we need it to get any order detailes
			$order       = wc_get_order( $order_id );
			$post_fields = stripslashes_deep( $_POST );

			$flex_field_ipn         = wc_clean( $post_fields['flex_field_ipn'] );
			$flex_field_num_of_inst = wc_clean( $post_fields['flex_field_num_of_inst'] );

			if ( isset( $flex_field_ipn ) && isset( $flex_field_num_of_inst ) ) {

				if ( SplitIt_FlexFields_Payment_Plugin_Log::check_exist_order_by_ipn( $flex_field_ipn ) ) {
					wc_add_notice( sprintf( 'Sorry, your session has expired. Order already exist. <a href="%s" class="wc-backward">Return to homepage</a>', home_url() ), 'error' );

					return;
				}

				$data = array(
					'user_id'                 => get_current_user_id(),
					'order_id'                => $order_id,
					'installment_plan_number' => $flex_field_ipn,
					'number_of_installments'  => $flex_field_num_of_inst,
					'processing'              => 'woocommerce',
				);

				// @Add record to transaction table
				SplitIt_FlexFields_Payment_Plugin_Log::transaction_log( $data );

				// @#17.06.2021  Postponed order updates after verifyPayment methods
				$api = new SplitIt_FlexFields_Payment_Plugin_API( $this->settings, self::DEFAULT_INSTALMENT_PLAN );
				// @$api->update($order_id, $_POST['flex_field_ipn']);

				try {
					$verify_data = $api->verify_payment( $flex_field_ipn );

					if ( $verify_data->getIsAuthorized() ) {

						SplitIt_FlexFields_Payment_Plugin_Log::update_transaction_log( array( 'installment_plan_number' => $flex_field_ipn ) );

					} else {
						$order_total_amount = $order->get_total();

						if ( isset( $order ) && ! empty( $order ) ) {
							$order->update_status( 'cancelled' );
						}

						$data = array(
							'user_id' => $order->user_id ?? null,
							'method'  => __( 'process_payment() Splitit', 'splitit_ff_payment' ),
						);

						SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, 'Spltiti->verifyPaymentAPI() Returned an failed in process_payment()', 'error' );

						if ( SplitIt_FlexFields_Payment_Plugin_Log::check_exist_order_by_ipn( $flex_field_ipn ) ) {
							SplitIt_FlexFields_Payment_Plugin_Log::update_transaction_log( array( 'installment_plan_number' => $flex_field_ipn ) );
						}

						wc_add_notice( __( 'Your order has not been paid, please try again.', 'splitit_ff_payment' ), 'error' );

						return;
					}
					$api->update( $order_id, $flex_field_ipn );
				} catch ( Exception $e ) {
					$data = array(
						'user_id' => $order->user_id ?? null,
						'method'  => __( 'process_payment() Splitit', 'splitit_ff_payment' ),
					);
					SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $e->getMessage(), 'error' );

					// if fail then update order status and try to refund.
					$order->update_status( 'failed' );
					$api->refund( $order->get_total(), '', $flex_field_ipn, $order_id, '', 'auto' );
					wc_add_notice( __( 'Something went wrong, please try to place an order later.', 'splitit_ff_payment' ), 'error' );
					return;
				}

				$data = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'process_payment() Splitit', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, 'Customer placed order with Splitit', 'info' );

				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			} else {
				wc_add_notice( __( 'Sorry, there was no payment received! Please try to order again.', 'splitit_ff_payment' ), 'error' );

				return;
			}

			wc_add_notice( __( 'Something went wrong, please try to place an order again.', 'splitit_ff_payment' ), 'error' );

			return;
		}

		/**
		 * Method that process payment refund for the order
		 *
		 * @param int    $order_id
		 * @param null   $amount
		 * @param string $reason
		 *
		 * @return bool|WP_Error
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			try {
				$order = wc_get_order( $order_id );

				if ( $amount <= 0 ) {
					return new WP_Error( 'error', 'Refund process failed. Amount can not be less or equal 0. Amount = ' . $amount );
				}

				if ( 'splitit' === $order->get_payment_method() ) {

					if ( in_array( $order->get_status(), array( 'processing', 'completed' ) ) ) {
						if ( $splitit_info = SplitIt_FlexFields_Payment_Plugin_Log::get_splitit_info_by_order_id( $order_id ) ) {

							if ( 'splitit_programmatically' == $reason ) {
								return true;
							}

							$api = new SplitIt_FlexFields_Payment_Plugin_API( $this->settings, $splitit_info->number_of_installments );
							if ( $api->refund( $amount, $order->get_currency(), $splitit_info->installment_plan_number, $order_id, $reason, 'refund' ) ) {

								$refund_message = 'Splitit accepted the request for a refund by amount = '. $amount .' . Installment Plan Number = '. $splitit_info->installment_plan_number .'. After processing on the Splitit side, you will see additional notification here and the order status will change automatically';

								$order->add_order_note( $refund_message );
								return new WP_Error( 'error', $refund_message );

							}
						} else {
							throw new Exception( __( 'Refund order to Splitit is failed, no order information in db for ship to Splitit', 'splitit_ff_payment' ) );
						}
					} else {
						throw new Exception( __( 'Invalid order status for refund', 'splitit_ff_payment' ) );
					}
				}

				return true;
			} catch ( Exception $e ) {
				$message = $e->getMessage();
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'process_refund() Splitit', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );

				$order = wc_get_order( $order_id );
				SplitIt_FlexFields_Payment_Plugin_Settings::update_order_status_to_old( $order );

				$message_fo_displaying = 'Refund unable to be processed online, consult your Splitit Account to process manually.';

				return new WP_Error( 'error', $message_fo_displaying );
			}
		}

		/**
		 * Method that change order status
		 *
		 * @param $order_id
		 */
		public function woocommerce_payment_change_order_status( $order_id ) {
			if ( ! $order_id ) {
				return;
			}
			$order = wc_get_order( $order_id );
			if ( $order->get_payment_method() == 'splitit' ) {
				if ( ! $this->settings['splitit_auto_capture'] ) {
					$order->update_status( 'pending' );
				} else {
					$order->update_status( 'processing' );
				}
			}
		}

		/**
		 * Initiate admin styles and scripts on the settings page
		 */
		public function init_admin_styles_and_scripts() {
			global $plguin_id;
			add_action( 'woocommerce_order_status_changed', array( $this, 'processing_change_status' ) );
			add_action( 'woocommerce_order_status_cancelled', array( $this, 'process_cancelled' ) );
			add_action('admin_notices', array( $this, 'displaying_custom_admin_notice' ) );

			// @# 18.06.2021 reworked the launch start installation by clicking on the SHIP button
			// @add_action('woocommerce_order_status_completed', [$this, 'process_start_installments']);

			add_action( 'wp_ajax_check_api_credentials', array( $this, 'check_api_credentials' ) );
			add_action( 'wp_ajax_splitit_get_environment', array( $this, 'splitit_get_environment' ) );
			add_action( 'wp_ajax_splitit_set_environment', array( $this, 'splitit_set_environment' ) );
			add_action( 'wp_ajax_splitit_get_plugin_url', array( $this, 'splitit_get_plugin_url' ) );
			add_action( 'wp_ajax_splitit_merchant_logout', array( $this, 'splitit_merchant_logout' ) );
			add_action( 'wp_ajax_set_code_verifier', array( $this, 'set_code_verifier' ) );
			add_action( 'wp_ajax_splitit_remove_merchant_api_key', array( $this, 'remove_merchant_api_key' ) );
			add_action( 'wp_ajax_splitit_remove_logged_user_data', array( $this, 'remove_logged_user_data' ) );
			add_action(
				'woocommerce_order_item_add_action_buttons',
				array(
					$this,
					'add_ship_button_to_admin_order_page',
				)
			);
			add_action(
				'woocommerce_admin_order_data_after_billing_address',
				array(
					$this,
					'splitit_add_installment_plan_number_data',
				)
			);
			add_action( 'wp_ajax_start_installment_method', array( $this, 'start_installment_method' ) );

			SplitIt_FlexFields_Payment_Plugin_Settings::get_admin_scripts_and_styles( $plguin_id );
		}

		/**
		 * Method that getting access_token using auth code
		 *
		 * @param $auth_code
		 *
		 * @return mixed
		 */
		public function get_access_token( $auth_code ) {
			$environment = get_option( 'splitit_environment' ) ? get_option( 'splitit_environment' ) : $this->splitit_environment;

			$token_url     = 'https://id.' . $environment . '.splitit.com/connect/token';
			$client_id     = 'WooCommerceIntegration'; // only for authorization
			$client_secret = 'sandbox' === $environment ? 'rT8XDrC7UbekQvtd1mvkpPV1MPe1itS0uhdo0s6wwHs2esy50r' : 'yX8SGi01dkcna4zPBqM67xrYPnKuoFWzyRI2Vf4h9KCfMji5zS'; // only for authorization
			$callback_uri  = get_site_url() . '/splitit-auth/callback';

			session_start();
			$verifier = isset( $_SESSION['code_verifier'] ) ? $_SESSION['code_verifier'] : '';

			$header  = array( 'Content-Type: application/x-www-form-urlencoded' );
			$content = "client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code&code=$auth_code&redirect_uri=$callback_uri&code_verifier=$verifier";

			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL            => $token_url,
					CURLOPT_HTTPHEADER     => $header,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST           => true,
					CURLOPT_POSTFIELDS     => $content,
				)
			);

			$response = curl_exec( $curl );

			curl_close( $curl );

			if ( false === $response ) {
				echo 'Failed';
				echo curl_error( $curl );
			} elseif ( property_exists( json_decode( $response ), 'error' ) ) {
				echo 'Error:<br />';
				echo $auth_code;
				echo $response;
			}

			$id_token = json_decode( $response )->id_token;
			if ( $id_token ) {
				session_start();
				$_SESSION['id_token'] = $id_token;
			}

			return json_decode( $response )->access_token;
		}

		/**
		 * Method that getting logged user data
		 *
		 * @param $api_url
		 * @param $token
		 * @param null    $merchant_id
		 *
		 * @return bool|mixed|string
		 */
		public function get_user_data( $api_url, $token ) {
			$header = array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $token,
				'touch-point: MerchantAdminPortal',
				'touch-point-sub-version: 2',
				'touch-point-version: 2',
			);

			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL            => $api_url,
					CURLOPT_HTTPHEADER     => $header,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_RETURNTRANSFER => true,
				)
			);

			$response = curl_exec( $curl );
			curl_close( $curl );

			if ( false === $response ) {
				echo 'Failed';
				echo curl_error( $curl );
				echo 'Failed';
			} elseif ( property_exists( json_decode( $response ), 'error' ) ) {
				echo 'Error:<br />';
				echo $response;
			}

			return json_decode( $response )->UserData;
		}

		/**
		 * Method that getting merchant`s settings list
		 *
		 * @param $api_url
		 * @param $token
		 * @param null    $merchant_id
		 *
		 * @return bool|mixed|string
		 */
		public function get_merchant_settings( $api_url, $token, $merchant_id ) {
			$header = array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $token,
				'touch-point: WooCommercePlugin',
				'touch-point-sub-version: 0',
				'touch-point-version: v4',
				'merchant-id: ' . $merchant_id,
			);

			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL            => $api_url,
					CURLOPT_HTTPHEADER     => $header,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_RETURNTRANSFER => true,
				)
			);

			$response = curl_exec( $curl );
			curl_close( $curl );

			if ( false === $response ) {
				echo 'Failed';
				echo curl_error( $curl );
				echo 'Failed';
			} elseif ( property_exists( json_decode( $response ), 'error' ) ) {
				echo 'Error:<br />';
				echo $response;
			}

			return json_decode( $response )->Settings;
		}

		/**
		 * Method that getting list of merchants and list of merchant`s terminals
		 *
		 * @param $api_url
		 * @param $token
		 * @param null    $merchant_id
		 *
		 * @return bool|mixed|string
		 */
		public function get_list( $api_url, $token, $merchant_id = null ) {
			$header = array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $token,
				'touch-point: WooCommercePlugin',
				'touch-point-sub-version: 0',
				'touch-point-version: v4',
			);

			if ( $merchant_id ) {
				$header[] = 'merchant-id: ' . $merchant_id;
			}

			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL            => $api_url,
					CURLOPT_HTTPHEADER     => $header,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_RETURNTRANSFER => true,
				)
			);

			$response = curl_exec( $curl );
			curl_close( $curl );

			if ( false === $response ) {
				echo 'Failed';
				echo curl_error( $curl );
				echo 'Failed';
			} elseif ( json_decode( $response )->error ) {
				echo 'Error:<br />';
				echo $response;
			}

			return $response;
		}

		/**
		 * Get Client Secret
		 *
		 * @param $api_url
		 * @param $token
		 * @param $client_id
		 * @param $merchant_id
		 *
		 * @return mixed
		 */
		public function get_client_secret( $api_url, $token, $client_id, $merchant_id ) {
			$header = array(
				'Accept: text/plain',
				'Content-Type: application/json-patch+json',
				'Authorization: Bearer ' . $token,
				'touch-point: WooCommercePlugin',
				'touch-point-sub-version: 0',
				'touch-point-version: v4',
				'merchant-id: ' . $merchant_id,
			);

			$content = array(
				'ClientId' => $client_id,
			);

			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL            => $api_url,
					CURLOPT_HTTPHEADER     => $header,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST           => true,
					CURLOPT_POSTFIELDS     => json_encode( $content ),
				)
			);

			$response = curl_exec( $curl );
			curl_close( $curl );

			if ( false === $response ) {
				echo 'Failed';
				echo curl_error( $curl );
				echo 'Failed';
			} elseif ( json_decode( $response )->error ) {
				echo 'Error:<br />';
				echo $response;
			}

			return json_decode( $response )->Secret;
		}

		/**
		 * Method that generating drop-down with list of merchants
		 *
		 * @param $list
		 * @param $user_data
		 */
		public function generate_merchants_list_dropdown( $list, $user_data ) {
			$merchants_list = json_decode( $list, true )['Merchants'];

			usort(
				$merchants_list,
				function ( $item1, $item2 ) {
					if ( $item1['Code'] == $item2['Code'] ) {
						return 0;
					}
					return $item1['Code'] < $item2['Code'] ? -1 : 1;
				}
			);

			session_start();
			$_SESSION['merchants_list'] = $merchants_list;
			$id_token                   = isset( $_SESSION['id_token'] ) ? $_SESSION['id_token'] : '';

			?>

			<div id="page_content" style="display: none">
				<title>Login to merchant portal</title>
				<div id="merchantsModal" class="modal">
					<form id="merchantForm" method=POST action="/get-terminals">
						<div class="modal-content">
							<div class="modal-header">
								<span id="close" class="close">&times;</span>
								<div class="img-header">
									<div class="Oval">
										<span class="num-1">1</span>
									</div>
									<div class="Path-2"></div>
									<div class="Oval">
										<span class="num-2">2</span>
									</div>
								</div>
								<div class="text-header">
								<span class="Account-details">
								  Account details
								</span>
									<span class="Connect-mercaht">
								  Connect mercaht
								</span>
								</div>
							</div>
							<div class="modal-body">
								<div class="name-title">
									Hey <?php echo $user_data ? ucwords( $user_data->FirstName ) : ''; ?>!
								</div>
								<div class="question-title">Which merchant would you like to connect?</div>
								<div id="merchant_list_dropdown_select" class="form-group">
									<select name="merchant_id" id="merchants_list_dropdown" class="merchant-select">
										<option value="" disabled selected>Merchant Account</option>
										<?php
										foreach ( $merchants_list as $item ) {
											echo '<option value="' . $item['Id'] . '">' . $item['Code'] . '</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<select name="terminal_id" id="terminals_list_dropdown" class="merchant-select" disabled>
										<option value="">Choose a Terminal</option>
									</select>
								</div>

								<div class="button-block">
									<button class="connect-button" type="submit" id="get_terminal" disabled>
										Connect my merchant
									</button>

									<div class="email-info">
										You’re logging in as <?php echo $user_data ? $user_data->Email : ''; ?>
									</div>

									<div class="logout-link">
										<a id="re_login" href="#">Log in with a different Email</a>
									</div>
								</div>

							</div>
							<div class="modal-footer">
								<div class="decoration-1"></div>
								<div class="decoration-2"></div>
								<div class="decoration-3"></div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<?php $this->get_pop_up_scripts(); ?>

			<script>
				$(function () {
					$("select").select2();

					setTimeout(function () {
						$('#page_content').show()
					}, 500)


					$('#merchants_list_dropdown').change(function () {
						$('#merchantForm').submit()
					})

					let id_token = '<?php echo $id_token; ?>'
					let environment   = localStorage.getItem( 'environment' );

					$( '#re_login' ).click( function (e) {
						let logoutUrl = 'https://id.' + environment + '.splitit.com/connect/endsession?state=FORCE_LOGOUT&id_token_hint=' + id_token + '&post_logout_redirect_uri=' + window.location.origin + '/splitit-auth/callback'
						window.location.href = logoutUrl
					})
				});
			</script>

			<style>
				<?php $this->get_pop_up_styles(); ?>
				.connect-button {
					width: 307px;
					height: 46px;
					border-radius: 23px;
					background-color: #cdcdcd;
					border: none;
					cursor: pointer;
					color: #fff;
				}
				#merchant_list_dropdown_select .select2-container--default .selection .select2-selection--single {
					border-color: #642f6c;
				}
			</style>

			<?php
		}

		/**
		 * Add scripts for pop-up
		 */
		public function get_pop_up_scripts() {
			echo '
				<script src="' . plugin_dir_url( __FILE__ ) . 'assets/js/jquery/jquery-3.6.0.slim.min.js"></script>
				<link href="' . plugin_dir_url( __FILE__ ) . 'assets/js/select2/select2.min.css" rel="stylesheet" />
				<script src="' . plugin_dir_url( __FILE__ ) . 'assets/js/select2/select2.min.js"></script>
			';
		}

		/**
		 * Add styles for pop-up
		 */
		public function get_pop_up_styles() {
			echo '
                body#error-page {
					max-width: none;
					margin: 0;
					padding: 0;
				}
				.merchant-select {
					height: 46px;
					padding: 10px;
					font-size: 16px;
					margin-bottom: 15px;
					width: 392px;
				}
				.modal {
					max-width: 600px;
					width: 100%;
					margin: 15px auto;
				}
				.modal-content {
					margin: auto;
					padding: 20px;
					height: 720px;
					border-radius: 6px;
					box-shadow: 0 3px 30px 0 rgba(0, 0, 0, 0.19);
					background-color: #fff;
					position: relative;
				}
				.modal-header {
					height: 18%;
				}
				.close {
					float: right;
					margin-top: -25px;
					font-size: 25px;
					cursor: pointer;
					display: none;
				}
				.modal-footer {
					height: 120px;
					position: absolute;
					left: 0;
					right: 0;
					bottom: 0;
					overflow: hidden;
				}
				.img-header, .text-header {
					display: flex;
					justify-content: center;
				}
				.img-header {
					margin-top: 20px;
				}
				.modal-body {
					height: 50%;
				}
				#get_terminal {
					margin-top: 15px;
				}
				.form-group {
					margin-bottom: 20px;
					text-align: center;
				}
				.logout-link {
					margin-top: 15px;
				}
				.decoration-1 {
					position: absolute;
					height: 1000px;
					width: 1000px;
					border-radius: 1200px;
					background: rgb(2,188,214);
					background: linear-gradient(90deg, rgba(2,188,214,1) 0%, rgba(154,226,225,0.6) 63%);
					left: 45px;
					top: 20px;
					opacity: 0.8;
				}
				.decoration-2 {
					position: absolute;
					height: 900px;
					width: 900px;
					border-radius: 1200px;
					background: rgb(2,188,214);
					background: linear-gradient(260deg, rgba(2,188,214,1) 0%, rgba(154,226,225,1) 31%, rgba(154,226,225,1) 100%);
					left: -440px;
					opacity: 0.4;
				}
				.decoration-3 {
					position: absolute;
					height: 900px;
					width: 900px;
					border-radius: 1200px;
					background: rgb(2,188,214);
					background: linear-gradient(260deg, rgba(2,188,214,1) 0%, rgba(154,226,225,1) 31%, rgba(154,226,225,1) 100%);
					left: -395px;
					top: 60px;
					opacity: 0.5;
				}
				.Oval {
					width: 28px;
					height: 28px;
					border-radius: 50%;
					box-shadow: 0 2px 4px 0 rgba(27, 133, 120, 0.27);
					background-color: #45c3b4;
					text-align: center;
				}
				.num-1, .num-2 {
					width: 10px;
					height: 22px;
					font-family: Avenir, sans-serif;
					font-size: 16px;
					font-weight: 900;
					color: #efefef;
					line-height: 28px;
				}
				.num-2 {
					color: #fff;
				}
				.Path-2 {
					width: 133px;
					height: 14px;
					border-bottom: dashed 2px #45c3b4;
				}
				.Account-details, .Connect-mercaht {
					height: 19px;
					font-family: Avenir, sans-serif;
					font-size: 14px;
					color: #45c3b4;
					margin: 10px 30px;
				}
				.name-title {
					height: 24px;
					font-family: Avenir, sans-serif;
					font-size: 35px;
					font-weight: 900;
					line-height: 0.69;
					text-align: center;
					color: #642f6c;
				}
				.question-title {
					height: 25px;
					font-family: Avenir, sans-serif;
					font-size: 18px;
					color: #000;
					text-align: center;
					margin: 30px;
				}
				.button-block {
					text-align: center;
				}
				.email-info {
					height: 19px;
					font-family: Avenir, sans-serif;
					font-size: 14px;
					color: #000;
					margin-top: 30px;
				}
				.logout-link {
					height: 19px;
					font-family: Avenir, sans-serif;
					font-size: 14px;
					color: #1152bf;
				}
				.select2-container .select2-selection--single {
					height: 46px;
					text-align: start;
					font-family: Avenir, sans-serif;
					font-size: 14px;
				}
				.select2-container--default .select2-selection--single .select2-selection__rendered {
					line-height: 45px;
				}
				.select2-container--default .select2-selection--single .select2-selection__arrow {
					height: 45px;
				}
            ';
		}

		/**
		 * Method that generating drop-down with list of merchant`s terminals
		 *
		 * @param $terminals_list
		 * @param $credentials_list
		 * @param $merchant_id
		 * @param $user_data
		 * @param $env
		 */
		public function generate_terminals_list_dropdown( $terminals_list, $credentials_list, $merchant_id, $user_data, $env ) {
			session_start();
			$_SESSION['terminals_list']   = $terminals_list;
			$_SESSION['credentials_list'] = $credentials_list;
			$merchants_list               = $_SESSION['merchants_list'];
			$id_token                     = isset( $_SESSION['id_token'] ) ? $_SESSION['id_token'] : '';
			?>

			<div id="page_content" style="display: none">
				<title>Login to merchant portal</title>
				<div id="terminalModal" class="modal">

					<div class="modal-content">
						<div class="modal-header">
							<span id="close" class="close">&times;</span>
							<div class="img-header">
								<div class="Oval">
									<span class="num-1">1</span>
								</div>
								<div class="Path-2"></div>
								<div class="Oval">
									<span class="num-2">2</span>
								</div>
							</div>
							<div class="text-header">
								<span class="Account-details">
								  Account details
								</span>
								<span class="Connect-mercaht">
								  Connect mercaht
								</span>
							</div>
						</div>

						<div class="modal-body">
							<div class="name-title">
								Hey <?php echo $user_data ? ucwords( $user_data->FirstName ) : ''; ?>!
							</div>
							<div class="question-title">Which merchant would you like to connect?</div>
							<div class="form-group">
								<form id="merchantForm" method=POST action="/get-terminals">
									<select name="merchant_id" id="merchants_list_dropdown" class="merchant-select">
										<option value="" disabled selected>Merchant Account</option>
										<?php
										foreach ( $merchants_list as $item ) {
											$selected = $merchant_id == $item['Id'] ? 'selected' : '';
											echo '<option value="' . $item['Id'] . '" ' . $selected . '>' . $item['Code'] . '</option>';
										}
										?>
									</select>
								</form>
							</div>
							<div class="form-group">
								<form id="terminalForm" method=POST action="/terminal">
									<input type="hidden" id="selected_merchant_id" name="merchant_id" value="<?php echo $merchant_id; ?>">
									<input type="hidden" id="old_merchant_id" value="<?php echo get_option( 'splitit_' . $env . '_merchant_id' ); ?>">
									<input type="hidden" id="old_terminal_id" value="<?php echo get_option( 'splitit_' . $env . '_terminal_id' ); ?>">

									<select name="terminal_id" id="terminals_list_dropdown" class="merchant-select">
										<option value="" disabled selected>Choose a Terminal</option>
										<?php
										foreach ( $terminals_list as $item ) {
											$name = $item['Name'] ?? $item['MerchantName'];
											echo '<option value="' . $item['Id'] . '">' . $name . '</option>';
										}
										?>
									</select>

									<div id="credentials_list_block" style="margin-top: 20px; 
									<?php
									if ( ( ! get_option( 'splitit_' . $env . '_client_id' ) || ! get_option( 'splitit_' . $env . '_client_secret' ) ) || ( get_option( 'splitit_' . $env . '_merchant_id' ) != $merchant_id ) ) {
										echo 'display: block';
									} else {
										echo 'display: none'; }
									?>
									">
										<select name="client_id" id="credentials_list_dropdown" class="merchant-select">
											<option value="" disabled selected>Choose a Client ID</option>
										<?php
										foreach ( $credentials_list as $credential ) {
											if ( ! $credential['IsLocked'] ) {
												echo '<option value="' . $credential['ClientId'] . '">' . $credential['ClientId'] . '</option>';
											}
										}
										?>
										</select>
									</div>

								</form>
							</div>


							<?php
							if ( count( $terminals_list ) > 0 ) {
								?>

								<?php if ( get_option( 'splitit_' . $env . '_merchant_id' ) && get_option( 'splitit_' . $env . '_merchant_id' ) != $merchant_id ) : ?>
									<div class="warning-text">
										Warning! <br> You have chosen another merchant! <br> Please pay attention to the fact that you can't work with orders from the previous merchant. <br> For more information, please contact the Splitit Support Team
									</div>
								<?php endif; ?>

								<div id="anotherTerminal" class="warning-text" style="display: none">
									Warning! <br> You have chosen another terminal! <br> Please pay attention to the fact that you can't work with orders from the previous terminal. <br> For more information, please contact the Splitit Support Team
								</div>

								<div class="button-block">
									<button class="connect-button" type="submit" id="show_terminal" disabled>
										Connect my merchant
									</button>

									<div class="email-info">
										You’re logging in as <?php echo $user_data ? $user_data->Email : ''; ?>
									</div>

									<div class="logout-link">
										<a id="re_login" href="#">Log in with a different Email</a>
									</div>
								</div>
								<?php
							} else {
								?>
								<div class="error-text">
									This merchant has no available terminals. Choose another merchant
								</div>
								<div class="button-block">
									<button class="connect-button" type="submit" id="show_terminal" disabled>
										Connect my merchant
									</button>

									<div class="email-info">
										You’re logging in as <?php echo $user_data ? $user_data->Email : ''; ?>
									</div>

									<div class="logout-link">
										<a id="re_login"  href="#">Log in with a different Email</a>
									</div>
								</div>
								<?php
							}
							?>
						</div>
						<div class="modal-footer">
							<div class="decoration-1"></div>
							<div class="decoration-2"></div>
							<div class="decoration-3"></div>
						</div>
					</div>

				</div>
			</div>

			<?php $this->get_pop_up_scripts(); ?>

			<script>
				$(function () {
					$("select").select2();

					setTimeout(function () {
						$('#page_content').show()
					}, 500)

					$('#merchants_list_dropdown').change(function () {
						$('#show_terminal').attr('disabled', true);
						$('#merchantForm').submit()
					})
					$('#terminals_list_dropdown').change(function () {
						let oldMerchantId = ($('#old_merchant_id').val());
						let selectedMerchantId = ($('#selected_merchant_id').val());

						let oldTerminalId = ($('#old_terminal_id').val());
						let selectedTerminalId = ($(this).val());

						if (oldTerminalId == selectedTerminalId) {
							$('#credentials_list_block').attr('style', 'display: none');
							$('#credentials_list_dropdown').val('').prop('selected', true);
							$('#show_terminal').attr('disabled', false);
							$('#anotherTerminal').attr('style', 'display: none');
						} else {
							$('#show_terminal').attr('disabled', true);

							if (oldMerchantId && oldMerchantId == selectedMerchantId) {
								$('#anotherTerminal').attr('style', 'display: block');
							}

							$('#credentials_list_block').attr('style', 'display: block; margin-top: 20px');
							if ( $('#credentials_list_dropdown').val() ) {
								$('#show_terminal').attr('disabled', false);
							}
						}
					})
					$('#credentials_list_dropdown').change(function () {
						if ( $('#terminals_list_dropdown').val() ) {
							$('#show_terminal').attr('disabled', false);
						}
					})
					$('#show_terminal').click(function () {
						$('#terminalForm').submit();
					})

					let id_token = '<?php echo $id_token; ?>'
					let environment   = localStorage.getItem( 'environment' );

					$( '#re_login' ).click( function (e) {
						let logoutUrl = 'https://id.' + environment + '.splitit.com/connect/endsession?state=FORCE_LOGOUT&id_token_hint=' + id_token + '&post_logout_redirect_uri=' + window.location.origin + '/splitit-auth/callback'
						window.location.href = logoutUrl
					})
				});
			</script>

			<style>
				<?php $this->get_pop_up_styles(); ?>
				.connect-button {
					width: 307px;
					height: 46px;
					border-radius: 23px;
					background-color: #45c3b4;
					border: none;
					cursor: pointer;
					color: #fff;
				}
				.connect-button:disabled {
					background-color: #cdcdcd;
				}
				.select2-container--default .select2-selection--single {
					border-color: #642f6c;
				}
				.warning-text {
					background-color: indianred;
					padding: 15px;
					margin: 15px;
					border-radius: 5px;
					color: #000;
					text-align: center;
					font-size: 16px;
				}
			</style>

			<?php

			add_action( 'wp_enqueue_scripts', 'pop_up_enqueue_scripts' );
		}

		/**
		 * URL handler method
		 */
		public function splitit_custom_url_handler() {
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				if ( strstr( $_SERVER['REQUEST_URI'], '?', true ) === '/splitit-auth/callback' ) {
					$data = stripslashes_deep( $_GET );

					if ( isset( $data['state'] ) && $data['state'] == 'FORCE_LOGOUT' ) {
						$this->remove_logged_user_data();
						?>
							<style>
								body#error-page {
									max-width: none;
									margin: 0;
									padding: 0;
								}
							</style>
							<script>
								window.opener.document.getElementById('merchant_login').click()
							</script>
						<?php
					}

					if ( isset( $data['code'] ) && $data['code'] ) {
						$access_token = $this->get_access_token( $data['code'] );
						$env          = get_option( 'splitit_environment' ) ? get_option( 'splitit_environment' ) : $this->splitit_environment;
						if ( $access_token ) {
							session_start();
							$_SESSION['access_token'] = $access_token;

							$user_data = $this->get_user_data( 'https://id.' . $env . '.splitit.com/api/user/profile', $access_token );

							update_option( 'splitit_logged_user_data', $user_data );

							$merchant_ref_list = $this->get_list( 'https://merchantportal-api.' . $env . '.splitit.com/api/v1/merchant/ref-list?forceRefresh=false', $access_token );
							$this->generate_merchants_list_dropdown( $merchant_ref_list, $user_data );
						}
					}
					wp_die();
				}

				if ( '/get-terminals' === $_SERVER['REQUEST_URI'] ) {
					if ( isset( $_POST ) ) {
						$_POST       = stripslashes_deep( $_POST );
						$merchant_id = $_POST['merchant_id'];
						$env         = get_option( 'splitit_environment' ) ? get_option( 'splitit_environment' ) : $this->splitit_environment;

						session_start();
						$access_token      = isset( $_SESSION['access_token'] ) ? $_SESSION['access_token'] : null;
						$user_data         = get_option( 'splitit_logged_user_data' );
						$merchant_settings = $this->get_merchant_settings( 'https://merchantportal-api.' . $env . '.splitit.com/api/v1/merchant/extended-info', $access_token, $merchant_id );
						update_option( 'merchant_settings', $merchant_settings );

						if ( $access_token ) {
							$terminals_list = $this->get_list( 'https://merchantportal-api.' . $env . '.splitit.com/api/v1/terminal/list', $access_token, $merchant_id );

							$credentials_list = $this->get_list( 'https://merchantportal-api.' . $env . '.splitit.com/api/v1/credentials/list', $access_token, $merchant_id );

							$this->generate_terminals_list_dropdown( json_decode( $terminals_list, true )['Terminals'], json_decode( $credentials_list, true )['Merchants'], $merchant_id, $user_data, $env );
						}
					}
					wp_die();
				}

				if ( '/terminal' === $_SERVER['REQUEST_URI'] ) {
					if ( isset( $_POST ) ) {
						$_POST       = stripslashes_deep( $_POST );
						$terminal_id = $_POST['terminal_id'];
						$merchant_id = $_POST['merchant_id'];
						$client_id   = $_POST['client_id'];
						$env         = get_option( 'splitit_environment' ) ? get_option( 'splitit_environment' ) : $this->splitit_environment;

						session_start();
						$merchants_list = isset( $_SESSION['merchants_list'] ) ? $_SESSION['merchants_list'] : null;
						$terminals_list = isset( $_SESSION['terminals_list'] ) ? $_SESSION['terminals_list'] : null;
						$access_token   = isset( $_SESSION['access_token'] ) ? $_SESSION['access_token'] : null;

						$selected_merchant = null;

						if ( $merchants_list && $terminals_list ) {
							foreach ( $merchants_list as $merchant ) {
								if ( $merchant['Id'] == $merchant_id ) {
									update_option( 'merchant_name', $merchant['Code'] );
									$selected_merchant = $merchant['Id'];
								}
							}

							foreach ( $terminals_list as $terminal ) {
								if ( $terminal['Id'] == $terminal_id ) {
									update_option( 'splitit_' . $env . '_new_login', 1 );
									update_option( 'api_key', $terminal['ApiKey'] );
									update_option( 'terminal_name', $terminal['Name'] ?? $terminal['MerchantName'] );

									if ( $client_id ) {

										$client_secret = $this->get_client_secret( 'https://merchantportal-api.' . $env . '.splitit.com/api/v1/credentials/generate', $access_token, $client_id, $merchant_id );

										update_option( 'splitit_' . $env . '_merchant_id', $selected_merchant );

										if ( $client_secret ) {
											update_option( 'splitit_' . $env . '_client_id', $client_id );
											update_option( 'splitit_' . $env . '_client_secret', $client_secret );
										} else {
											return false;
										}
									}

									update_option( 'splitit_' . $env . '_api_key', $terminal['ApiKey'] );
									update_option( 'splitit_' . $env . '_terminal_id', $terminal['Id'] );

									?>
									<script type="text/javascript">
										function closePopUp() {
											window.addEventListener("load", window.close);
										}
										closePopUp();
									</script>
									<?php
								}
							}
						}
					}
					wp_die();
				}

				if ( strstr( $_SERVER['REQUEST_URI'], '?', true ) === '/logout' ) {
					ob_clean();
					ob_start();

					$this->remove_merchant_api_key();
					$this->remove_logged_user_data();

					wp_safe_redirect( get_site_url() . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=splitit' );
					exit();
				}
			}
		}

		/**
		 * Merchant logout method
		 */
		public function splitit_merchant_logout() {
			$this->remove_merchant_api_key();
			$this->remove_logged_user_data();
			return wp_send_json_success();
		}

		/**
		 * Method that setting code_verifier to session
		 */
		public function set_code_verifier() {
			if ( isset( $_POST ) ) {
				$_POST         = stripslashes_deep( $_POST );
				$code_verifier = isset( $_POST['code_verifier'] ) ? wc_clean( $_POST['code_verifier'] ) : null;
				if ( $code_verifier ) {
					session_start();
					$_SESSION['code_verifier'] = $code_verifier;
				}
			}
			wp_die();
		}

		/**
		 * Method that remove merchant_name, terminal_name, api_key from DB
		 */
		public function remove_merchant_api_key() {
			$setting_options = array( 'merchant_name', 'terminal_name', 'api_key', 'merchant_settings' );

			foreach ( $setting_options as $setting_name ) {
				delete_option( $setting_name );
			}
		}

		/**
		 * Method that remove logged user data from DB
		 */
		public function remove_logged_user_data() {
			delete_option( 'splitit_logged_user_data' );
		}

		/**
		 * Method that getting environment
		 */
		public function splitit_get_environment() {
			return wp_send_json_success( $this->splitit_environment );
		}

		/**
		 * Method that setting environment
		 */
		public function splitit_set_environment() {
			$result = false;
			if ( isset( $_POST ) ) {
				$_POST       = stripslashes_deep( $_POST );
				$environment = $_POST['environment'];

				$result = update_option( 'splitit_environment', $environment );
			}

			return wp_send_json_success( $result );
		}

		/**
		 * Method that getting splitit plugin url
		 */
		public function splitit_get_plugin_url() {
			return wp_send_json_success( plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Call start Installment API method
		 */
		public function start_installment_method() {
			if ( isset( $_POST ) ) {
				$_POST    = stripslashes_deep( $_POST );
				$order_id = isset( $_POST['order_id'] ) ? wc_clean( $_POST['order_id'] ) : null;
				if ( isset( $order_id ) ) {
					return wp_send_json_success( $this->process_start_installments( $order_id ) );
				}
			}
		}

		/**
		 * Add SHIP button
		 *
		 * @param $order
		 */
		public function add_ship_button_to_admin_order_page( $order ) {
			if ( $order->has_status( 'completed' ) || $order->has_status( 'processing' ) || $order->has_status( 'refunded' ) ) {
				return;
			}
			$order_id       = $order->get_id();
			$payment_method = $order->get_payment_method();
			global $plguin_id;
			if ( $payment_method == $plguin_id ) {
				echo "<button id='start_installment_button' data-order_id='" . esc_attr( $order_id ) . "' class='button'>SHIP</button>";
			}
		}

		/**
		 * Adds installment_plan_number value to order edit page
		 *
		 * @param $order
		 */
		public function splitit_add_installment_plan_number_data( $order ) {
			$order_info = SplitIt_FlexFields_Payment_Plugin_Log::get_splitit_info_by_order_id( $order->get_id() );
			if ( isset( $order_info ) && ! empty( $order_info ) ) {
				echo '<p><strong>' . __( 'Installment plan number', 'splitit_ff_payment' ) . ':</strong> ' . esc_html( $order_info->installment_plan_number ) . '</p>';
				echo '<p><strong>' . __( 'Number of installments', 'splitit_ff_payment' ) . ':</strong> ' . esc_html( $order_info->number_of_installments ) . '</p>';
			}
		}

		/**
		 * Return IPN and Number of installment for 'Thank you' page
		 *
		 * @param $thank_you_title
		 * @param $order
		 *
		 * @return mixed|string
		 */
		public function splitit_add_installment_plan_number_data_thank_you_title( $thank_you_title, $order ) {
			if ( $order && $order->get_id() ) {
				$order_info = SplitIt_FlexFields_Payment_Plugin_Log::get_splitit_info_by_order_id( $order->get_id() );
			}
			if ( isset( $order_info ) && ! empty( $order_info ) ) {
				$thank_you_title = '<p><strong>' . __( 'Installment plan number', 'splitit_ff_payment' ) . ':</strong> ' . esc_html( $order_info->installment_plan_number ) . '</p> <p><strong>' . __( 'Number of installments', 'splitit_ff_payment' ) . ':</strong> ' . esc_html( $order_info->number_of_installments ) . '</p>';
			}

			echo $thank_you_title;

		}

		/**
		 * Method checks if the hook has arrived and auto_capture is on and changes the order status
		 *
		 * @param $order_id
		 */
		public function processing_change_status( $order_id ) {
			if ( $this->settings['splitit_auto_capture'] && $order_info = SplitIt_FlexFields_Payment_Plugin_Log::get_splitit_info_by_order_id( $order_id ) ) {
				if ( ! $order_info->plan_create_succeed ) {
					$order = wc_get_order( $order_id );
					$order->update_status( 'failed' );
					$message = 'Order = ' . $order_id . ' status changed to failed. Since the hook didn\'t come and auto_capture is on';
					$data    = array(
						'user_id' => get_current_user_id(),
						'method'  => __( 'processing_change_status() Splitit', 'splitit_ff_payment' ),
					);
					SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message );
				}
			}
		}

		/**
		 * Initiate AJAX URL that is used in all AJAX calls
		 */
		public function init_client_styles_and_scripts() {
			add_action( 'wp_footer', array( $this, 'include_footer_script_and_style_front' ) );
		}

		/**
		 * Method that inserts the script in the footer
		 */
		public function include_footer_script_and_style_front() {
			?>
			<script>
				getSplititAjaxURL = function( endpoint ) {
					return '<?php echo WC_AJAX::get_endpoint( '%%endpoint%%' ); ?>'
						.toString()
						.replace( '%%endpoint%%', 'splitit_' + endpoint );
				};
			</script>
			<?php
		}

		/**
		 * Method for check API credentials on the admin settings page
		 */
		public function check_api_credentials() {
			if ( ! $this->settings['splitit_api_key'] || ! $this->settings['splitit_api_username'] || ! $this->settings['splitit_api_password'] ) {
				$message = __( 'Please enter the credentials and save settings', 'splitit_ff_payment' );
			} else {
				$api     = new SplitIt_FlexFields_Payment_Plugin_API( $this->settings, self::DEFAULT_INSTALMENT_PLAN );
				$session = $api->login( true );
				$message = '';
				if ( ! isset( $session['error'] ) ) {
					$message .= __( 'Successfully login! API available!', 'splitit_ff_payment' );
				} else {
					$message .= $session['error']['message'];
				}
			}
			echo esc_html( $message );
			wp_die();
		}

		function displaying_custom_admin_notice()
		{
			session_start();
			if (isset($_SESSION['cancelled_order_message'])) {
				?>
                <div class='notice notice-error is-dismissible'>
                    <p><?php echo $_SESSION['cancelled_order_message'] ?></p>
                </div>
				<?php
				unset($_SESSION['cancelled_order_message']);
			}
		}

		/**
		 * Method that cancels payment for the order
		 *
		 * @param $order_id
		 *
		 * @return bool
		 */
		public function process_cancelled( $order_id ) {
			try {
				$order = wc_get_order( $order_id );
				if ( $order->get_payment_method() == 'splitit' ) {
					if ( $splitit_info = SplitIt_FlexFields_Payment_Plugin_Log::get_splitit_info_by_order_id( $order_id ) ) {

						$refund_info = SplitIt_FlexFields_Payment_Plugin_Log::select_from_refund_log_by_order_id( $order_id );

                        if ( ! $refund_info ) {

	                        $api      = new SplitIt_FlexFields_Payment_Plugin_API( $this->settings, $splitit_info->number_of_installments );
	                        $ipn_info = $api->get_ipn_info( $splitit_info->installment_plan_number );

	                        if ( ! empty( $ipn_info ) ) {

                                if ( $api->refund( $ipn_info->getAmount(), $ipn_info->getCurrency(), $splitit_info->installment_plan_number, $order_id, '', 'cancel' ) ) {

                                    $cancel_message = 'Splitit accepted the request for a cancel by amount = '. $ipn_info->getAmount() .' . Installment Plan Number = '. $splitit_info->installment_plan_number .'. After processing on the Splitit side, you will see additional notification here and the order status will change automatically';

                                    $order->add_order_note( $cancel_message );

                                    SplitIt_FlexFields_Payment_Plugin_Settings::update_order_status_to_old( $order );

                                    session_start();
                                    $_SESSION['cancelled_order_message'] = $cancel_message;

                                } else {
                                    SplitIt_FlexFields_Payment_Plugin_Settings::update_order_status_to_old( $order );
                                    throw new Exception( __( 'Cancel order failed due to the order being processed already', 'splitit_ff_payment' ) );
                                }

	                        } else {
		                        SplitIt_FlexFields_Payment_Plugin_Settings::update_order_status_to_old( $order );
		                        throw new Exception( __( 'Cancel order failed due to the order being processed already', 'splitit_ff_payment' ) );
	                        }

                        }

					} else {
						throw new Exception( __( 'Cancel order to Splitit is failed, no order information in db for ship to Splitit', 'splitit_ff_payment' ) );
					}
				}

				return true;
			} catch ( Exception $e ) {
				$message = $e->getMessage();
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'cancel() Splitit', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );

				$order = wc_get_order( $order_id );
				SplitIt_FlexFields_Payment_Plugin_Settings::update_order_status_to_old( $order );

				setcookie( 'splitit', $message, time() + 30 );

				return false;
			}
		}

		/**
		 * Method that call the StartInstallment for order and ipn
		 *
		 * @param $order_id
		 *
		 * @return string|void
		 */
		public function process_start_installments( $order_id ) {
			try {
				$order = wc_get_order( $order_id );
				if ( $order->get_payment_method() == 'splitit' ) {
					if ( $splitit_info = SplitIt_FlexFields_Payment_Plugin_Log::get_splitit_info_by_order_id( $order_id ) ) {
						$api = new SplitIt_FlexFields_Payment_Plugin_API( $this->settings, $splitit_info->number_of_installments );

						if ( ! $this->settings['splitit_auto_capture'] ) {
							if ( $api->start_installments( $splitit_info->installment_plan_number, $order_id ) ) {
								$order->update_status( 'processing' );

								return __( 'Start installments order to Splitit is success', 'splitit_ff_payment' );
							}
						} else {
							return __( 'Start installments order to Splitit is failed, auto_capture should be off', 'splitit_ff_payment' );
						}
					} else {
						return __( 'Start installments order to Splitit is failed, no order information in db for ship to Splitit', 'splitit_ff_payment' );
					}
				}
			} catch ( Exception $e ) {
				$message = $e->getMessage();
				$data    = array(
					'user_id' => get_current_user_id(),
					'method'  => __( 'process_start_installments() Splitit', 'splitit_ff_payment' ),
				);
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $data, $message, 'error' );

				setcookie( 'splitit', $message, time() + 30 );

				return $message;
			}
		}

		/**
		 * Method for settings form in the admin panel
		 *
		 * @param array $form_fields
		 * @param bool  $echo
		 *
		 * @return string
		 */
		public function generate_settings_html( $form_fields = array(), $echo = true ) {
			if ( empty( $form_fields ) ) {
				$form_fields = $this->get_form_fields();
			}

			$html = '';
			foreach ( $form_fields as $k => $v ) {
				switch ( $k ) {
					case 'splitit_merchant_login':
						$html .= $this->generate_merchant_login_html( $k, $v );
						break;
					case 'general_setting_section':
						$html .= $this->generate_general_setting_section_html( $k, $v );
						break;
					case 'Payment_Method_Settings_section':
						$html .= $this->generate_payment_method_settings_section_html( $k, $v );
						break;
					case 'splitit_product_option':
						$html .= $this->generate_splitit_product_option_settings_section_html( $k, $v );
						break;
					case 'Upstream_Messaging_Settings_section':
						$html .= $this->generate_upstream_messaging_settings_section_html( $k, $v );
						break;
					case 'New_Upstream_Messaging_Settings_section':
						$html .= $this->generate_new_upstream_messaging_settings_section_html( $k, $v );
						break;
					case 'splitit_footer_allowed_card_brands':
						$html .= $this->generate_splitit_footer_allowed_card_brands_settings_section_html( $k, $v );
						break;
					case 'splitit_upstream_messaging_css':
						$html .= $this->generate_splitit_upstream_messaging_css_settings_section_html( $k, $v );
						break;
					case 'splitit_flex_fields_css':
						$html .= $this->generate_splitit_flex_fields_css_settings_section_html( $k, $v );
						break;
					default:
						$type = $this->get_field_type( $v );
						if ( method_exists( $this, 'generate_' . $type . '_html' ) ) {
							$html .= $this->{'generate_' . $type . '_html'}( $k, $v );
						} else {
							$html .= $this->generate_text_html( $k, $v, '', '' );
						}
						break;
				}
			}
			if ( $echo ) {
				echo $html;
			} else {
				return $html;
			}
		}

		/**
		 * Method for custom flex fields css setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_splitit_flex_fields_css_settings_section_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data  = wp_parse_args( $data, $defaults );
			$value = $this->get_option( $key );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>

			<div class="main-section">
				<!--start section header-->
				<div id="ff_css_collapse" class="setting-wrap setting-section-block">
					<div class="section-header-title">
						<span class="setting-title">
							<?php echo wp_kses_post( $data['title'] ); ?>
						</span>
						<div class="mt-3">
							<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
						</div>
					</div>

					<div class="section-header-collapse">
						<span class="section-close" id="ff_css_collapse_arrow"></span>
					</div>
				</div>
				<!--end section header-->

				<!--start section body-->
				<div id="ff_css_collapse_settings_section" class="setting-content-block mt-3 hide">
					<div id="ff_css_advanced_section" class="forminp mt-3">
						<fieldset>
							<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
							<textarea
									rows="3"
									cols="20"
									class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?> setting-content-advanced-block"
									type="<?php echo esc_attr( $data['type'] ); ?>"
									name="<?php echo esc_attr( $field_key ); ?>"
									id="<?php echo esc_attr( $field_key ); ?>"
									style="<?php echo esc_attr( $data['css'] ); ?>"
									placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>"
								<?php disabled( $data['disabled'], true ); ?>
								<?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?>
							><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
						</fieldset>
					</div>
				</div>
				<!--end section body-->
			</div>

			<?php

			return ob_get_clean();

		}

		/**
		 * Method for custom upstream messaging css setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_splitit_upstream_messaging_css_settings_section_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data  = wp_parse_args( $data, $defaults );
			$value = $this->get_option( $key );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>

			<div class="main-section">
				<!--start section header-->
				<div id="um_css_collapse" class="setting-wrap setting-section-block">
					<div class="section-header-title">
						<span class="setting-title">
							<?php echo wp_kses_post( $data['title'] ); ?>
						</span>
						<div class="mt-3">
							<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
						</div>
					</div>

					<div class="section-header-collapse">
						<span class="section-close" id="um_css_collapse_arrow"></span>
					</div>
				</div>
				<!--end section header-->

				<!--start section body-->
				<div id="um_css_collapse_settings_section" class="setting-content-block mt-3 hide">
					<div id="um_css_advanced_section" class="forminp mt-3">
						<fieldset>
							<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
							<textarea
									rows="3"
									cols="20"
									class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?> setting-content-advanced-block"
									type="<?php echo esc_attr( $data['type'] ); ?>"
									name="<?php echo esc_attr( $field_key ); ?>"
									id="<?php echo esc_attr( $field_key ); ?>"
									style="<?php echo esc_attr( $data['css'] ); ?>"
									placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>"
								<?php disabled( $data['disabled'], true ); ?>
								<?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?>
							><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
						</fieldset>
					</div>

					<!--this block for next version-->
					<?php if ( 1 === 0 ) : ?>
					<div class="d-flex">
						<div class="selects-block">
							<div class="mb-3">
								<fieldset>
									<p class="description text-black">Font Type</p>

									<select
											class="select mt-3"
											name="<?php echo esc_attr( $field_key ); ?>_font_type"
											id="<?php echo esc_attr( $field_key ); ?>_font_type"
									>
										<?php foreach ( (array) $data['font_types'] as $option_key => $option_value ) : ?>
											<option
													value="<?php echo esc_attr( $option_key ); ?>"
												<?php selected( (string) $option_key, esc_attr( $value ) ); ?>
											><?php echo esc_html( $option_value ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</fieldset>
							</div>
							<div class="mb-3">
								<fieldset>
									<p class="description text-black">Font Size</p>

									<select
											class="select mt-3"
											name="<?php echo esc_attr( $field_key ); ?>_font_size"
											id="<?php echo esc_attr( $field_key ); ?>_font_size"
									>
										<?php foreach ( (array) $data['font_sizes'] as $option_key => $option_value ) : ?>
											<option
													value="<?php echo esc_attr( $option_key ); ?>"
												<?php selected( (string) $option_key, esc_attr( $value ) ); ?>
											><?php echo esc_html( $option_value ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</fieldset>
							</div>
							<div class="mb-3">
								<fieldset>
									<p class="description text-black">Font Color</p>

									<input
											type="color"
											class="input-color mt-3"
											name="<?php echo esc_attr( $field_key ); ?>_font_color"
											id="<?php echo esc_attr( $field_key ); ?>_font_color"
											value="#652d70"
									>
								</fieldset>
							</div>
							<div class="mb-3">
								<fieldset>
									<p class="description text-black">Fill Color</p>

									<input
											type="color"
											class="input-color mt-3"
											name="<?php echo esc_attr( $field_key ); ?>_fill_color"
											id="<?php echo esc_attr( $field_key ); ?>_fill_color"
											value="#cf9dff"
									>
								</fieldset>
							</div>
						</div>

						<div class="radios-block">
							<div class="choose-style">
								<p class="description text-black">Choose your style</p>
								<div class="d-flex mt-3 mb-3">
									<label class="container css-label description">
										Classic
										<input
												type="radio"
												id="um_css_classic"
												name="um_css_style"
												value="um_css_classic"
												checked
										>
										<span class="checkmark"></span>
									</label>
									<label class="container css-label description">
										Your brand
										<input
												type="radio"
												id="um_css_your_brand"
												name="um_css_style"
												value="um_css_your_brand"
										>
										<span class="checkmark"></span>
									</label>
									<label class="container css-label description">
										Out of the box
										<input
												type="radio"
												id="um_css_out_of_the_box"
												name="um_css_style"
												value="um_css_out_of_the_box"
										>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
							<div class="css-section-title">
								<div class="mb-2">Splitit classic CSS for upstream message.</div>
								<div class="mb-3">Suitable for a variety of store designs</div>
							</div>
							<div class="um-css-ex">
								<div class="um-css-product-img"></div>
								<div class="css-product-info description text-black">
									<div>Product name</div>
									<div class="text-bold">$100</div>
								</div>
								<div class="css-um-ex-wrap">
									<div class="um-ex">
										As low as $20 / month with Splitit
									</div>
								</div>
								<div class="css-add-to-card-button">
									<div class="d-flex">
										<div class="um-css-ex-add-card"></div>
										<div class="um-css-ex-add-to-card">
											Add to Cart
										</div>
									</div>
								</div>
							</div>
							<div class="css-description mb-5">
								For demonstration purposes only
							</div>
							<div id="um_css_advanced_collapse" class="d-flex pointer">
								<span class="advanced-title">Advanced CSS</span>
								<span class="section-close" id="um_css_advanced_collapse_arrow"></span>
							</div>
							<div id="um_css_advanced_section" class="forminp mt-3 hide">
								<fieldset>
									<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
									<textarea
											rows="3"
											cols="20"
											class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?> setting-content-advanced-block"
											type="<?php echo esc_attr( $data['type'] ); ?>"
											name="<?php echo esc_attr( $field_key ); ?>"
											id="<?php echo esc_attr( $field_key ); ?>"
											style="<?php echo esc_attr( $data['css'] ); ?>"
											placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>"
								<?php disabled( $data['disabled'], true ); ?>
										<?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?>
							><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
								</fieldset>
							</div>
						</div>
					</div>

					<div class="mt-5" style="display: none">
						<button id="um_css_save_button" type="button" class="login-button css-block-button">Save Changes</button>
					</div>
					<?php endif; ?>
					<!--  -->
				</div>
				<!--end section body-->
			</div>

			<?php

			return ob_get_clean();

		}

		/**
		 * Method for custom footer allowed card brands setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_splitit_footer_allowed_card_brands_settings_section_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data  = wp_parse_args( $data, $defaults );
			$value = $this->get_option( $key );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>

			<div class="main-section">
				<!--start section header-->
				<div id="footer_allowed_card_brands_collapse" class="setting-wrap setting-section-block">
					<div class="section-header-title">
						<span class="setting-title">
							<?php echo wp_kses_post( $data['title'] ); ?>
						</span>
						<div class="mt-3">
							<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
						</div>
					</div>

					<div class="section-header-collapse">
						<span class="section-close" id="footer_allowed_card_brands_collapse_arrow"></span>
					</div>
				</div>
				<!--end section header-->

				<!--start section body-->
				<div id="footer_allowed_card_brands_collapse_settings_section" class="setting-content-block mt-3 hide">
					<div class="forminp mr-3">
						<fieldset>
							<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span>
							</legend>
							<p class="description">select payment method</p>

							<select
									multiple
									class="select <?php echo esc_attr( $data['class'] ); ?>"
									name="<?php echo esc_attr( $field_key ); ?>[]"
									id="<?php echo esc_attr( $field_key ); ?>"
									style="<?php echo esc_attr( $data['css'] ); ?>"
								<?php disabled( $data['disabled'], true ); ?>
								<?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?>
							>
								<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
									<?php if ( is_array( $option_value ) ) : ?>
										<optgroup label="<?php echo esc_attr( $option_key ); ?>">
											<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
												<option
														value="<?php echo esc_attr( $option_key_inner ); ?>"
													<?php selected( in_array( (string) $option_key_inner, $value ) ); ?>
												><?php echo esc_html( $option_value_inner ); ?>
												</option>
											<?php endforeach; ?>
										</optgroup>
									<?php else : ?>
										<option
												value="<?php echo esc_attr( $option_key ); ?>"
											 <?php selected( in_array( (string) $option_key, $value ) ); ?>
										><?php echo esc_html( $option_value ); ?>
										</option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</fieldset>
					</div>

					<div class="mt-4 mb-3 description">
						Footer ex.
					</div>
					<div class="footer-ex">
						<div class="footer-ex-logo"></div>
						<div id="footer-ex-cards" class="footer-ex-cards">
							<?php foreach ( (array) $value as $value_key => $value_item ) : ?>
								<div class="footer-ex-card <?php echo $value_item; ?>"></div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<!--end section body-->
			</div>

			<?php

			return ob_get_clean();

		}

		/**
		 * Method for custom upstream messaging setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_upstream_messaging_settings_section_html( $key, $data ) {
			$defaults = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			$splitit_upstream_messaging_selection = $this->get_option( 'splitit_upstream_messaging_selection' );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>

			<div class="main-section">
				<!--start section header-->
				<div id="upstream_messaging_collapse" class="setting-wrap setting-section-block">
					<div class="section-header-title">
						<span class="setting-title">
							<?php echo wp_kses_post( $data['title'] ); ?>
						</span>
						<div class="mt-3">
							<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
						</div>
					</div>

					<div class="section-header-collapse">
						<span class="section-close" id="upstream_messaging_collapse_arrow"></span>
					</div>
				</div>
				<!--end section header-->

				<!--start section body-->
				<div id="upstream_messaging_settings_section" class="setting-content-block mt-3 hide">
					<div class="description mb-5">
						Adding on-site messaging is a great way to let shoppers know that you offer installment payments.
						<br>It’s important to inform the customer throughout their shopping journey to help them make decisions before they get to the checkout.
					</div>
					<div class="um-title">
						<div>
							<span class="setting-title">
								Page
							</span>
						</div>
						<div>
							<span class="setting-title">
								Display location
							</span>
						</div>
					</div>
					<?php foreach ( (array) $data['pages'] as $page_key => $page ) : ?>
						<div class="d-flex">
							<div class="mr-3 description um-desc">
								<span class="settings-3d-title"><?php echo wp_kses_post( $page['title'] ); ?></span>
							</div>

							<div>
								<legend class="screen-reader-text"><span><?php echo wp_kses_post( $page['title'] ); ?></span></legend>

								<label class="main-section-switch">
									<input
										<?php disabled( $data['disabled'], true ); ?>
											data-desc="<?php echo $page['checkbox']; ?>"
											class="<?php echo esc_attr( $data['class'] ); ?>"
											type="checkbox"
											name="<?php echo esc_attr( $this->get_field_key( $data['option_name'] ) ); ?>[]"
											id="<?php echo esc_attr( $this->get_field_key( $page['checkbox'] ) ); ?>"
											style="<?php echo esc_attr( $data['css'] ); ?>"
											value="<?php echo wp_kses_post( $page['checkbox'] ); ?>"
										<?php checked( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) ); ?>
										<?php echo $this->get_custom_attribute_html( $page ); // WPCS: XSS ok. ?>
									/>
									<span class="main-section-slider main-section-round"></span>
								</label>
							</div>
							<div id="upstream_messages_desc_<?php echo $page['checkbox']; ?>" class="chbx-status ml-3 description main-section-enabled-description">
								<?php echo in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) ? '<span class="description-green">Enabled</span>' : 'Disabled'; ?>
							</div>

							<div id="um_position_<?php echo $page['checkbox']; ?>" style="margin-top: -10px">
								<select
										class="select um-select <?php echo esc_attr( $data['class'] ); ?>"
										name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>"
										id="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>"
										style="<?php echo esc_attr( $data['css'] ); ?>"
									<?php disabled( ! in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) ); ?>
									<?php echo $this->get_custom_attribute_html( $data['pages'][ $page_key ] ); // WPCS: XSS ok. ?>
								>
									<?php foreach ( (array) $data['pages'][ $page_key ]['options'] as $option_key => $option_value ) : ?>
										<?php if ( is_array( $option_value ) ) : ?>
											<optgroup label="<?php echo esc_attr( $option_key ); ?>">
												<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
													<option
															value="<?php echo esc_attr( $option_key_inner ); ?>"
														<?php selected( (string) $option_key_inner, esc_attr( $this->get_option( $page_key ) ) ); ?>
													><?php echo esc_html( $option_value_inner ); ?>
													</option>
												<?php endforeach; ?>
											</optgroup>
										<?php else : ?>
											<option
													value="<?php echo esc_attr( $option_key ); ?>"
												<?php selected( (string) $option_key, esc_attr( $this->get_option( $page_key ) ) ); ?>
											><?php echo esc_html( $option_value ); ?>
											</option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</div>

							<div style="margin-top: 5px; display: none"> <!--TODO::maybe for the next version-->
								<span
										id="link_preview_<?php echo $page['checkbox']; ?>"
										data-target="<?php echo $page['checkbox']; ?>"
										class="preview-link <?php echo in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) ? '' : 'disabled-preview'; ?>">
									Preview
								</span>
							</div>
						</div>

						<div id="preview_<?php echo $page['checkbox']; ?>" class="modal preview-modal <?php echo $page['checkbox'] == 'cart' ? 'preview-lg' : ''; ?>">
							<div class="modal-content">
								<div class="modal-header">
									<span
											id="close"
											data-target="preview_<?php echo $page['checkbox']; ?>"
											class="close"
									>&times;</span>
									<span class="fl setting-title">
									  Example View
									</span>
								</div>
								<div class="modal-body">
									<div class="demonstration-title">
										For demonstration purposes only
									</div>

									<div class="preview-page-wrap <?php echo $page['checkbox'] == 'cart' ? 'preview-page-wrap-lg' : ''; ?>">
										<div class="preview-page-header">
											<div class="preview-page-header-title"></div>
										</div>
										<div class="preview-page-item-1"></div>
										<div class="preview-page-item-2"></div>

										<?php if ( $page['checkbox'] == 'product' ) : ?>
											<div class="d-flex">
												<div class="preview-page-item-3">
													<div class="preview-img-icon"></div>
												</div>
												<div>
													<div class="demonstration-title" style="margin-top: 0; margin-bottom: 10px">
														Product name
													</div>
													<div class="demonstration-title">
														$ 100
													</div>
													<div class="preview-page-item-4"></div>
													<div id="preview_page_um_<?php echo $page['checkbox']; ?>" class="preview-page-um mr-3 d-flex">
														<div class="um-ex">
															As low as $20 / month with Splitit
														</div>
													</div>
												</div>
											</div>
										<?php elseif ( $page['checkbox'] == 'cart' ) : ?>
											<div class="description" style="margin-left: 22px; color: #000">
												Your Cart
											</div>
											<div class="d-flex">
												<div class="preview-page-item-5">
													<div class="preview-img-icon-sm"></div>
												</div>
												<div class="preview-product-desc" style="width: 370px; margin-top: 5px;">
													Product name
												</div>
												<div style="text-align: end; margin-top: 5px;">
													<div class="preview-product-desc" style="margin-top: 0; margin-bottom: 10px">
														$100
													</div>
													<div class="preview-product-desc">
														Free shipping
													</div>
												</div>
											</div>
											<div class="preview-page-item-2"></div>

											<div style="margin-left: 22px">
												<div class="d-flex mb-3">
													<div class="preview-product-desc" style="width: 100px">Subtotal</div>
													<div class="preview-product-desc">$100</div>
												</div>
												<div class="d-flex mb-3">
													<div class="preview-product-desc" style="width: 100px">Total</div>
													<div class="preview-product-desc">$150</div>
												</div>
											</div>

											<div class="d-flex" style="justify-content: space-between;">
												<div id="preview_page_um_<?php echo $page['checkbox']; ?>" class="preview-page-um mr-3 d-flex ml-3" style="align-items: center; width: 430px;">
													<div class="um-ex">
														As low as $20 / month with Splitit
													</div>
												</div>
												<div class="preview-page-item-4" style="margin: 0 15px 0 0"></div>
											</div>
										<?php else : ?>
											<div style="margin: 200px 15px 0 15px;" id="preview_page_um_<?php echo $page['checkbox']; ?>" class="preview-page-um d-flex">
												<div class="um-ex">
													As low as $20 / month with Splitit
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="modal-footer">

								</div>
							</div>
						</div>
					<?php endforeach; ?>

					<div class="mt-4 mb-3 description" style="display: none"> <!--TODO::maybe for the next version-->
						<span class="settings-3d-title">Upstream message ex.</span>
					</div>
					<div class="um-ex" style="display: none"> <!--TODO::maybe for the next version-->
						As low as $20 / month with Splitit
					</div>
				</div>
				<!--end section body-->
			</div>

			<?php

			return ob_get_clean();

		}

		/**
		 * Method for custom upstream messaging setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_new_upstream_messaging_settings_section_html( $key, $data ) {
			$defaults = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			$splitit_upstream_messaging_selection = $this->get_option( 'splitit_upstream_messaging_selection' );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>

			<div class="main-section new_um">
				<!--start section header-->
				<div id="upstream_messaging_collapse" class="setting-wrap setting-section-block">
					<div class="section-header-title" style="width: 90%">
						<span class="setting-title">
							<?php echo wp_kses_post( $data['title'] ); ?>
						</span>
						<div class="mt-3">
							<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
						</div>
						<div id="um_main_error_box"></div>
					</div>

					<div class="section-header-collapse" style="margin-top: 35px">
						<span class="section-close" id="upstream_messaging_collapse_arrow"></span>
					</div>
				</div>
				<!--end section header-->

				<!--start section body-->
				<div id="upstream_messaging_settings_section" class="setting-content-block mt-3 hide">

					<?php foreach ( (array) $data['pages'] as $page_key => $page ) : ?>

					<div class="main-section">
						<!--start page section header-->
						<div id="upstream_messaging_<?php echo $page['name']; ?>_collapse" class="setting-wrap setting-section-block" style="padding: 0">
							<div class="section-header-title">
								<span class="setting-title page-setting-title">
									<?php echo wp_kses_post( $page['title'] ); ?>
								</span>
								<div id="um_page_error_box_<?php echo $page['name']; ?>"></div>
							</div>

							<div class="section-header-collapse" style="margin-top: 0">
								<span class="section-close" id="upstream_messaging_<?php echo $page['name']; ?>_collapse_arrow"></span>
							</div>
						</div>
						<!--end page section header-->

						<!--start page section body-->
						<div id="upstream_messaging_<?php echo $page['name']; ?>_settings_section" class="setting-content-block mt-3 hide page-section">

							<?php
								$page_config = $this->get_option( $page_key );

								$empty_config = ! in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) ||
											   ! is_array( $page_config ) || ( ! $page_config['strip']['enable_strip'] && ! $page_config['banner']['enable_banner'] && ! $page_config['logo']['enable_logo'] && ! $page_config['one_liner']['enable_one_liner'] );

							?>

							<!--start tabs buttons-->
							<div class="parent_header_block" data-um_page="<?php echo $page['name']; ?>">
								<div class="tabs">
									<div class="
									<?php
									if ( $empty_config || ( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['strip']['enable_strip'] ) ) {
										echo 'active';}
									?>
									">
										<span
												data-type="<?php echo $page['name']; ?>_strip"
												data-page="<?php echo $page['name']; ?>"
										>Strip</span>
									</div>
									<div class="
									<?php
									if ( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['banner']['enable_banner'] ) {
										echo 'active';}
									?>
									">
										<span
												data-type="<?php echo $page['name']; ?>_banner"
												data-page="<?php echo $page['name']; ?>"
										>Banner</span>
									</div>

									<?php if ( $page['name'] != 'home_page_banner' ) : ?>

										<div class="
										<?php
										if ( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['logo']['enable_logo'] ) {
											echo 'active';}
										?>
										">
											<span
													data-type="<?php echo $page['name']; ?>_logo"
													data-page="<?php echo $page['name']; ?>"
											>Logo</span>
										</div>
										<div class="
										<?php
										if ( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['one_liner']['enable_one_liner'] ) {
											echo 'active';}
										?>
										">
											<span
													data-type="<?php echo $page['name']; ?>_one_liner"
													data-page="<?php echo $page['name']; ?>"
											>One liner</span>
										</div>

									<?php endif; ?>

								</div>
							</div>
							<!--end tabs buttons-->

							<!--start tabs content-->

							<!--start strip tab-->
							<div class="<?php echo $page['name']; ?>_strip_section um_block_sections <?php echo $page['name']; ?>_um_block_sections
							<?php
							if ( $empty_config || in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['strip']['enable_strip'] ) {
								echo 'active';}
							?>
							">
								<div class="toogle-with-text">
									<div>
										<legend class="screen-reader-text"><span><?php echo wp_kses_post( $page['title'] ); ?></span></legend>

										<label class="main-section-switch">
											<input
												<?php disabled( $data['disabled'], true ); ?>
													data-desc="<?php echo $page['checkbox'] . '_strip'; ?>"
													data-page="<?php echo $page['name']; ?>"
													data-type="Strip"
													class="<?php echo esc_attr( $data['class'] ); ?> um_checkboxes_<?php echo $page['name']; ?>"
													type="checkbox"
													name="<?php echo esc_attr( $this->get_field_key( $data['option_name'] ) ); ?>[]"
													id="<?php echo esc_attr( $this->get_field_key( $page['checkbox'] ) ); ?>_strip"
													style="<?php echo esc_attr( $data['css'] ); ?>"
													value="<?php echo wp_kses_post( $page['checkbox'] ); ?>"
												<?php checked( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['strip']['enable_strip'] ); ?>
												<?php echo $this->get_custom_attribute_html( $page ); // WPCS: XSS ok. ?>
											/>
											<span class="main-section-slider main-section-round"></span>
										</label>

										<input
												type="hidden"
												name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][enable_strip]"
												id="<?php echo $page['checkbox']; ?>_strip_enabled"
												value="<?php echo is_array( $page_config ) && $page_config['strip']['enable_strip'] ? $page_config['strip']['enable_strip'] : 0; ?>"
										>

										<div id="upstream_messages_desc_<?php echo $page['checkbox']; ?>_strip" class="chbx-status mt-2 description main-section-enabled-description page-chbx-status">
											<?php echo in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['strip']['enable_strip'] ? '<span class="description-enabled">Enabled Strip</span>' : 'Disabled Strip'; ?>
										</div>
									</div>


									<div class="text">
										<p>
											This On-Site gadget is a way for us to let shoppers know that we offer installment payments.
										</p>
										<p>
											This is something that we have found to be very important in the online shopping world.
										</p>
									</div>
								</div>

								<div class="parent-wrap">
									<div class="page-setting-content-block">
										<div class="setting-title mt-0">
											Text
										</div>

										<div class="mt-3">
											<p class="description">Selection Of Texts:</p>
											<select
													class="select um-select <?php echo esc_attr( $data['class'] ); ?> um-text-type"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][um_text_type]"
													id="<?php echo $page['checkbox']; ?>_strip_um_text_type"
													data-page="<?php echo $page['name']; ?>"
													data-type="strip"
													data-name=""
											>
												<option value="">Default</option>
												<option value="custom" <?php echo is_array( $page_config ) && $page_config['strip']['um_text_type'] == 'custom' ? 'selected' : ''; ?>>Other</option>
											</select>
										</div>

										<div class="mt-3">
											<input
													class="<?php echo is_array( $page_config ) && $page_config['strip']['um_text_type'] == 'custom' ? '' : 'hide'; ?> input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][strip_text]"
													id="<?php echo $page['name']; ?>_strip_text"
													value="<?php echo ! is_array( $page_config ) || $page_config['strip']['strip_text'] == '' ? '' : $page_config['strip']['strip_text']; ?>"
													data-name="strip_text"
													data-page="<?php echo $page['name']; ?>"
											>
											<div class="splitit_error"></div>
										</div>

										<div class="mt-3">
											<p class="description">Button Text</p>
											<input
													class="input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][button_text]"
													id="<?php echo $page['name']; ?>_strip_button_text"
													value="<?php echo ! is_array( $page_config ) || $page_config['strip']['button_text'] == '' ? '' : $page_config['strip']['button_text']; ?>"
													data-name="button_text"
											>
										</div>

										<div class="mt-3">
											<p class="description">Font Size</p>
											<input
													class="input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][text_size]"
													id="<?php echo $page['name']; ?>_strip_text_size"
													value="<?php echo ! is_array( $page_config ) || $page_config['strip']['text_size'] == '' ? '' : $page_config['strip']['text_size']; ?>"
													data-size="true"
													data-name="text_size"
													placeholder="Default unit is 'px' but you can enter other '10%', '3em', '2rem'..."
											>
										</div>

										<div class="mt-3">
											<p class="description">Position</p>
											<select
													class="select um-select"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][position]"
													id="<?php echo $page['name']; ?>_strip_position"
													data-name="position"
											>
												<option value="bottom" <?php echo is_array( $page_config ) && $page_config['strip']['position'] && $page_config['strip']['position'] == 'bottom' ? 'selected' : ''; ?>>
													Bottom
												</option>
												<option value="top" <?php echo is_array( $page_config ) && $page_config['strip']['position'] && $page_config['strip']['position'] == 'top' ? 'selected' : ''; ?>>
													Top
												</option>
											</select>
										</div>

										<div class="mt-3">
											<p class="description">Text Alignment</p>
											<select
													class="select um-select"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][text_alignment]"
													id="<?php echo $page['name']; ?>_strip_text_alignment"
													data-name="text_alignment"
											>
												<option value="left" <?php echo is_array( $page_config ) && $page_config['strip']['text_alignment'] && $page_config['strip']['text_alignment'] == 'left' ? 'selected' : ''; ?>>Left</option>
												<option value="right" <?php echo is_array( $page_config ) && $page_config['strip']['text_alignment'] && $page_config['strip']['text_alignment'] == 'right' ? 'selected' : ''; ?>>Right</option>
												<option value="center" <?php echo is_array( $page_config ) && $page_config['strip']['text_alignment'] && $page_config['strip']['text_alignment'] == 'center' ? 'selected' : ''; ?>>Center</option>
											</select>
										</div>

										<div class="mt-3">
											<p class="description">Text Strip Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][text_strip_color]"
													id="<?php echo $page['name']; ?>_strip_text_strip_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['strip']['text_strip_color'] == '' ? '#94d6d0' : $page_config['strip']['text_strip_color']; ?>"
													data-name="text_strip_color"
											>
										</div>

										<div class="mt-3">
											<p class="description">Relative To Parent</p>
											<label class="main-section-switch">
												<input
														class=""
														type="checkbox"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][relative_to_parent]"
														id="<?php echo $page['name']; ?>_strip_relative_to_parent"
														value="1"
													<?php checked( is_array( $page_config ) && isset( $page_config['strip']['relative_to_parent'] ) && $page_config['strip']['relative_to_parent'] ); ?>
														data-name="relative_to_parent"
												/>
												<span class="main-section-slider main-section-round"></span>
											</label>
										</div>

									</div>

									<div class="page-setting-content-block mt-3">

										<div class="setting-title mt-0">
											Buttons
										</div>

										<div class="mt-3">
											<p class="description">Background Button Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][background_button_color]"
													id="<?php echo $page['name']; ?>_strip_background_button_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['strip']['background_button_color'] == '' ? '#94d6d0' : $page_config['strip']['background_button_color']; ?>"
													data-name="background_button_color"
											>
										</div>

										<div class="mt-3">
											<p class="description">Text Button Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][text_button_color]"
													id="<?php echo $page['name']; ?>_strip_text_button_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['strip']['text_button_color'] == '' ? '#642f6c' : $page_config['strip']['text_button_color']; ?>"
													data-name="text_button_color"
											>
										</div>

										<div class="mt-3">
											<p class="description">Hide Learn More</p>
											<label class="main-section-switch">
												<input
														class=""
														type="checkbox"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][hide_learn_more]"
														id="<?php echo $page['name']; ?>_strip_hide_learn_more"
														value="1"
														<?php checked( is_array( $page_config ) && isset( $page_config['strip']['hide_learn_more'] ) && $page_config['strip']['hide_learn_more'] ); ?>
														data-name="hide_learn_more"
												/>
												<span class="main-section-slider main-section-round"></span>
											</label>
										</div>

										<div class="mt-3">
											<p class="description">Button Reverse Icon</p>
											<label class="main-section-switch">
												<input
														class=""
														type="checkbox"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][button_reverse_icon]"
														id="<?php echo $page['name']; ?>_strip_button_reverse_icon"
														value="1"
														<?php checked( is_array( $page_config ) && isset( $page_config['strip']['button_reverse_icon'] ) && $page_config['strip']['button_reverse_icon'] ); ?>
														data-name="button_reverse_icon"
												/>
												<span class="main-section-slider main-section-round"></span>
											</label>
										</div>

									</div>

									<div class="page-setting-content-block mt-3">

										<div class="setting-title mt-0">
											Background
										</div>

										<div class="mt-3">
											<p class="description">Background Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[strip][background_color]"
													id="<?php echo $page['name']; ?>_strip_background_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['strip']['background_color'] == '' ? '#642f6c' : $page_config['strip']['background_color']; ?>"
													data-name="background_color"
											>
										</div>

									</div>

									<div class="um_preview_block">
										<div class="um_block" id="preview_<?php echo $page['name']; ?>_strip" style="position: relative; padding: 15px 0"></div>

										<div class="mt-3 preview_um_button_wrap">
											<button
													type="button"
													class="apply preview_um preview_um_button"
													data-page="<?php echo $page['name']; ?>"
													data-type="strip"
											>
												PREVIEW
											</button>
										</div>
									</div>
								</div>

							</div>
							<!--end strip tab-->

							<!--start banner tab-->
							<div class="<?php echo $page['name']; ?>_banner_section um_block_sections <?php echo $page['name']; ?>_um_block_sections
							<?php
							if ( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['banner']['enable_banner'] ) {
								echo 'active';}
							?>
							">
								<div class="toogle-with-text">
									<div>
										<legend class="screen-reader-text"><span><?php echo wp_kses_post( $page['title'] ); ?></span></legend>

										<label class="main-section-switch">
											<input
												<?php disabled( $data['disabled'], true ); ?>
													data-desc="<?php echo $page['checkbox'] . '_banner'; ?>"
													data-page="<?php echo $page['name']; ?>"
													data-type="Banner"
													class="<?php echo esc_attr( $data['class'] ); ?> um_checkboxes_<?php echo $page['name']; ?>"
													type="checkbox"
													name="<?php echo esc_attr( $this->get_field_key( $data['option_name'] ) ); ?>[]"
													id="<?php echo esc_attr( $this->get_field_key( $page['checkbox'] ) ); ?>_banner"
													style="<?php echo esc_attr( $data['css'] ); ?>"
													value="<?php echo wp_kses_post( $page['checkbox'] ); ?>"
												<?php checked( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['banner']['enable_banner'] ); ?>
												<?php echo $this->get_custom_attribute_html( $page ); // WPCS: XSS ok. ?>
											/>
											<span class="main-section-slider main-section-round"></span>
										</label>

										<input
												type="hidden"
												name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][enable_banner]"
												id="<?php echo $page['checkbox']; ?>_banner_enabled"
												value="<?php echo is_array( $page_config ) && $page_config['banner']['enable_banner'] ? $page_config['banner']['enable_banner'] : 0; ?>"
										>

										<div id="upstream_messages_desc_<?php echo $page['checkbox']; ?>_banner" class="chbx-status mt-2 description main-section-enabled-description page-chbx-status">
											<?php echo in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['banner']['enable_banner'] ? '<span class="description-enabled">Enabled Banner</span>' : 'Disabled Banner'; ?>
										</div>
									</div>


									<div class="text">
										<p>
											This On-Site gadget is a way for us to let shoppers know that we offer installment payments.
										</p>
										<p>
											This is something that we have found to be very important in the online shopping world.
										</p>
									</div>
								</div>

								<div class="parent-wrap">
									<div class="page-setting-content-block">
										<div class="setting-title mt-0">
											Text
										</div>

										<div class="mt-3">
											<p class="description">СSS Selector ( can be left blank to display in place by default )</p>
											<input
													class="input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][regular]"
													id="<?php echo $page['name']; ?>_banner_regular"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['regular'] == '' ? '' : $page_config['banner']['regular']; ?>"
													placeholder="Js selector. Example: '.yourClassName or #yourId'"
													data-name="regular"
											>
										</div>

										<div class="mt-3">
											<p class="description">Text Title</p>
											<input
													class="input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][text_title]"
													id="<?php echo $page['name']; ?>_banner_text_title"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['text_title'] == '' ? '' : $page_config['banner']['text_title']; ?>"
													placeholder="You can add your custom title"
													data-name="text_title"
											>
										</div>

										<div class="mt-3">
											<p class="description">Text Title Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][text_title_color]"
													id="<?php echo $page['name']; ?>_banner_text_title_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['text_title_color'] == '' ? '#000000' : $page_config['banner']['text_title_color']; ?>"
													data-name="text_title_color"
											>
										</div>

										<div class="mt-3">
											<p class="description">Show Title</p>
											<label class="main-section-switch">
												<input
														class=""
														type="checkbox"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][show_title]"
														id="<?php echo $page['name']; ?>_banner_show_title"
														value="1"
													<?php checked( is_array( $page_config ) && isset( $page_config['banner']['show_title'] ) && $page_config['banner']['show_title'] ); ?>
														data-name="show_title"
												/>
												<span class="main-section-slider main-section-round"></span>
											</label>
										</div>

										<div class="mt-3">
											<p class="description">Text Main:</p>
											<select
													class="select um-select <?php echo esc_attr( $data['class'] ); ?> um-text-type"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][um_text_type]"
													id="<?php echo $page['checkbox']; ?>_banner_um_text_type"
													data-page="<?php echo $page['name']; ?>"
													data-type="banner"
													data-name=""
											>
												<option value="">Default</option>
												<option value="custom" <?php echo is_array( $page_config ) && $page_config['banner']['um_text_type'] == 'custom' ? 'selected' : ''; ?>>Other</option>
											</select>
										</div>

										<div class="mt-3">
											<input
													class="<?php echo is_array( $page_config ) && $page_config['banner']['um_text_type'] == 'custom' ? '' : 'hide'; ?> input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][text_main]"
													id="<?php echo $page['name']; ?>_banner_text"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['text_main'] == '' ? '' : $page_config['banner']['text_main']; ?>"
													data-name="text_main"
													data-page="<?php echo $page['name']; ?>"
											>
											<div class="splitit_error"></div>
										</div>

										<div class="mt-3">
											<p class="description">Text Main Size</p>
											<input
													class="input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][text_main_size]"
													id="<?php echo $page['name']; ?>_banner_text_main_size"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['text_main_size'] == '' ? '' : $page_config['banner']['text_main_size']; ?>"
													data-size="true"
													data-name="text_main_size"
													placeholder="Default unit is 'px' but you can enter other '10%', '3em', '2rem'..."
											>
										</div>

										<div class="mt-3">
											<p class="description">Text Main Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][text_main_color]"
													id="<?php echo $page['name']; ?>_banner_text_main_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['text_main_color'] == '' ? '#94d6d0' : $page_config['banner']['text_main_color']; ?>"
													data-name="text_main_color"
											>
										</div>

									</div>

									<div class="page-setting-content-block">
										<div class="setting-title mt-0">
											Size
										</div>

										<div class="mt-3">
											<p class="description">Width:</p>
											<input
													class="input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][banner_width]"
													id="<?php echo $page['name']; ?>_banner_banner_width"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['banner_width'] == '' ? '' : $page_config['banner']['banner_width']; ?>"
													data-size="true"
													data-name="banner_width"
													placeholder="Default unit is 'px' but you can enter other '10%', '3em', '2rem'..."
											>
										</div>

										<div class="mt-3">
											<p class="description">Height:</p>
											<input
													class="input-text regular-input"
													type="text"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][banner_height]"
													id="<?php echo $page['name']; ?>_banner_banner_height"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['banner_height'] == '' ? '' : $page_config['banner']['banner_height']; ?>"
													data-size="true"
													data-name="banner_height"
													placeholder="Default unit is 'px' but you can enter other '10%', '3em', '2rem'..."
											>
										</div>

									</div>

									<div class="page-setting-content-block mt-3">

										<div class="setting-title mt-0">
											Buttons
										</div>

										<div class="mt-3">
											<p class="description">Background Button Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][background_button_color]"
													id="<?php echo $page['name']; ?>_banner_background_button_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['background_button_color'] == '' ? '#94d6d0' : $page_config['banner']['background_button_color']; ?>"
													data-name="background_button_color"
											>
										</div>

										<div class="mt-3">
											<p class="description">Text Button Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][text_button_color]"
													id="<?php echo $page['name']; ?>_banner_text_button_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['text_button_color'] == '' ? '#642f6c' : $page_config['banner']['text_button_color']; ?>"
													data-name="text_button_color"
											>
										</div>

										<div class="mt-3">
											<p class="description">Button Reverse Icon</p>
											<label class="main-section-switch">
												<input
														class=""
														type="checkbox"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][button_reverse_icon]"
														id="<?php echo $page['name']; ?>_banner_button_reverse_icon"
														value="1"
													<?php checked( is_array( $page_config ) && isset( $page_config['banner']['button_reverse_icon'] ) && $page_config['banner']['button_reverse_icon'] ); ?>
														data-name="button_reverse_icon"
												/>
												<span class="main-section-slider main-section-round"></span>
											</label>
										</div>

									</div>

									<div class="page-setting-content-block mt-3">

										<div class="setting-title mt-0">
											Background
										</div>

										<div class="mt-3">
											<p class="description">Banner Background Color</p>
											<input
													class="input-text regular-input"
													type="color"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[banner][banner_bg_color]"
													id="<?php echo $page['name']; ?>_banner_banner_bg_color"
													value="<?php echo ! is_array( $page_config ) || $page_config['banner']['banner_bg_color'] == '' ? '#642f6c' : $page_config['banner']['banner_bg_color']; ?>"
													data-name="banner_bg_color"
											>
										</div>

									</div>

									<div class="um_preview_block" style="margin: 0">
										<div class="um_block" id="preview_<?php echo $page['name']; ?>_banner" style="position: relative; padding: 15px 0"></div>

										<div class="mt-3 preview_um_button_wrap">
											<button
													type="button"
													class="apply preview_um preview_um_button"
													data-page="<?php echo $page['name']; ?>"
													data-type="banner"
											>
												PREVIEW
											</button>
										</div>
									</div>
								</div>

							</div>
							<!--end banner tab-->

							<?php if ( $page['name'] != 'home_page_banner' ) : ?>
							
								<!--start logo tab-->
								<div class="<?php echo $page['name']; ?>_logo_section um_block_sections <?php echo $page['name']; ?>_um_block_sections
								<?php
								if ( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['logo']['enable_logo'] ) {
									echo 'active';}
								?>
								">
									<div class="toogle-with-text">
										<div>
											<legend class="screen-reader-text"><span><?php echo wp_kses_post( $page['title'] ); ?></span></legend>

											<label class="main-section-switch">
												<input
													<?php disabled( $data['disabled'], true ); ?>
														data-desc="<?php echo $page['checkbox'] . '_logo'; ?>"
														data-page="<?php echo $page['name']; ?>"
														data-type="Logo"
														class="<?php echo esc_attr( $data['class'] ); ?> um_checkboxes_<?php echo $page['name']; ?>"
														type="checkbox"
														name="<?php echo esc_attr( $this->get_field_key( $data['option_name'] ) ); ?>[]"
														id="<?php echo esc_attr( $this->get_field_key( $page['checkbox'] ) ); ?>_logo"
														style="<?php echo esc_attr( $data['css'] ); ?>"
														value="<?php echo wp_kses_post( $page['checkbox'] ); ?>"
													<?php checked( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['logo']['enable_logo'] ); ?>
													<?php echo $this->get_custom_attribute_html( $page ); // WPCS: XSS ok. ?>
												/>
												<span class="main-section-slider main-section-round"></span>
											</label>

											<input
													type="hidden"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][enable_logo]"
													id="<?php echo $page['checkbox']; ?>_logo_enabled"
													value="<?php echo is_array( $page_config ) && $page_config['logo']['enable_logo'] ? $page_config['logo']['enable_logo'] : 0; ?>"
											>

											<div id="upstream_messages_desc_<?php echo $page['checkbox']; ?>_logo" class="chbx-status mt-2 description main-section-enabled-description page-chbx-status">
												<?php echo in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['logo']['enable_logo'] ? '<span class="description-enabled">Enabled Logo</span>' : 'Disabled Logo'; ?>
											</div>
										</div>


										<div class="text">
											<p>
												This On-Site gadget is a way for us to let shoppers know that we offer installment payments.
											</p>
											<p>
												This is something that we have found to be very important in the online shopping world.
											</p>
										</div>
									</div>

									<div class="parent-wrap">
										<div class="page-setting-content-block">
											<div class="setting-title mt-0">
												Text
											</div>

											<?php if ( $page['name'] != 'shop' ) : ?>
												<div class="mt-3">
													<p class="description">СSS Selector ( can be left blank to display in place by default )</p>
													<input
															class="input-text regular-input"
															type="text"
															name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][regular]"
															id="<?php echo $page['name']; ?>_logo_regular"
															value="<?php echo ! is_array( $page_config ) || $page_config['logo']['regular'] == '' ? '' : $page_config['logo']['regular']; ?>"
															placeholder="Js selector. Example: '.yourClassName or #yourId'"
															data-name="regular"
													>
												</div>
											<?php endif; ?>

											<div class="mt-3">
												<p class="description">Logo Text:</p>
												<select
														class="select um-select <?php echo esc_attr( $data['class'] ); ?> um-text-type"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][um_text_type]"
														id="<?php echo $page['checkbox']; ?>_logo_um_text_type"
														data-page="<?php echo $page['name']; ?>"
														data-type="logo"
														data-name=""
												>
													<option value="">None</option>
													<option value="custom" <?php echo is_array( $page_config ) && $page_config['logo']['um_text_type'] == 'custom' ? 'selected' : ''; ?>>Other</option>
												</select>
											</div>

											<div class="mt-3">
												<input
														class="<?php echo is_array( $page_config ) && $page_config['logo']['um_text_type'] == 'custom' ? '' : 'hide'; ?> input-text regular-input"
														type="text"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][logo_text]"
														id="<?php echo $page['name']; ?>_logo_text"
														value="<?php echo ! is_array( $page_config ) || $page_config['logo']['logo_text'] == '' ? '' : $page_config['logo']['logo_text']; ?>"
														data-name="logo_text"
														data-page="<?php echo $page['name']; ?>"
												>
												<div class="splitit_error"></div>
											</div>

											<div class="mt-3">
												<p class="description">Hide Tooltip</p>
												<label class="main-section-switch">
													<input
															class=""
															type="checkbox"
															name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][hide_tooltip]"
															id="<?php echo $page['name']; ?>_logo_hide_tooltip"
															value="1"
														<?php checked( is_array( $page_config ) && isset( $page_config['logo']['hide_tooltip'] ) && $page_config['logo']['hide_tooltip'] ); ?>
															data-name="hide_tooltip"
													/>
													<span class="main-section-slider main-section-round"></span>
												</label>
											</div>

											<div class="mt-3">
												<p class="description">Tooltip Text</p>
												<input
														class="input-text regular-input"
														type="text"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][tooltip_text]"
														id="<?php echo $page['name']; ?>_logo_tooltip_text"
														value="<?php echo ! is_array( $page_config ) || $page_config['logo']['tooltip_text'] == '' ? '' : $page_config['logo']['tooltip_text']; ?>"
														data-name="tooltip_text"
												>
											</div>

											<div class="mt-3">
												<p class="description">Tooltip Title</p>
												<input
														class="input-text regular-input"
														type="text"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][tooltip_title]"
														id="<?php echo $page['name']; ?>_logo_tooltip_title"
														value="<?php echo ! is_array( $page_config ) || $page_config['logo']['tooltip_title'] == '' ? '' : $page_config['logo']['tooltip_title']; ?>"
														data-name="tooltip_title"
												>
											</div>

											<div class="mt-3">
												<p class="description">Text Size</p>
												<input
														class="input-text regular-input"
														type="text"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][text_size]"
														id="<?php echo $page['name']; ?>_logo_text_size"
														value="<?php echo ! is_array( $page_config ) || $page_config['logo']['text_size'] == '' ? '' : $page_config['logo']['text_size']; ?>"
														data-size="true"
														data-name="text_size"
														placeholder="Default unit is 'px' but you can enter other '10%', '3em', '2rem'..."
												>
											</div>

											<div class="mt-3">
												<p class="description">Docking</p>
												<select
														class="select um-select"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][docking]"
														id="<?php echo $page['name']; ?>_logo_docking"
														data-name="docking"
												>
													<option value="" <?php echo is_array( $page_config ) && $page_config['logo']['docking'] && $page_config['logo']['docking'] == '' ? 'selected' : ''; ?>>

													</option>
													<option value="top_left" <?php echo is_array( $page_config ) && $page_config['logo']['docking'] && $page_config['logo']['docking'] == 'top_left' ? 'selected' : ''; ?>>
														Top Left
													</option>
													<option value="top_right" <?php echo is_array( $page_config ) && $page_config['logo']['docking'] && $page_config['logo']['docking'] == 'top_right' ? 'selected' : ''; ?>>
														Top Right
													</option>
													<option value="bottom_left" <?php echo is_array( $page_config ) && $page_config['logo']['docking'] && $page_config['logo']['docking'] == 'bottom_left' ? 'selected' : ''; ?>>
														Bottom Left
													</option>
													<option value="bottom_right" <?php echo is_array( $page_config ) && $page_config['logo']['docking'] && $page_config['logo']['docking'] == 'bottom_right' ? 'selected' : ''; ?>>
														Bottom Right
													</option>
												</select>
											</div>

										</div>

										<div class="page-setting-content-block mt-3">

											<div class="setting-title mt-0">
												Buttons
											</div>

											<div class="mt-3">
												<p class="description">Hide Learn More</p>
												<label class="main-section-switch">
													<input
															class=""
															type="checkbox"
															name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][hide_learn_more]"
															id="<?php echo $page['name']; ?>_logo_hide_learn_more"
															value="1"
														<?php checked( is_array( $page_config ) && isset( $page_config['logo']['hide_learn_more'] ) && $page_config['logo']['hide_learn_more'] ); ?>
															data-name="hide_learn_more"
													/>
													<span class="main-section-slider main-section-round"></span>
												</label>
											</div>

										</div>

										<div class="page-setting-content-block mt-3">

											<div class="setting-title mt-0">
												Logo
											</div>

											<div class="mt-3">
												<p class="description">Logo Color</p>
												<input
														class="input-text regular-input"
														type="color"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][logo_color]"
														id="<?php echo $page['name']; ?>_logo_logo_color"
														value="<?php echo ! is_array( $page_config ) || $page_config['logo']['logo_color'] == '' ? '#94d6d0' : $page_config['logo']['logo_color']; ?>"
														data-name="logo_color"
												>
											</div>

										</div>

										<div class="page-setting-content-block mt-3">

											<div class="setting-title mt-0">
												Background
											</div>

											<div class="mt-3">
												<p class="description">Background Color</p>
												<input
														class="input-text regular-input"
														type="color"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[logo][bg_color]"
														id="<?php echo $page['name']; ?>_logo_bg_color"
														value="<?php echo ! is_array( $page_config ) || $page_config['logo']['bg_color'] == '' ? '#642f6c' : $page_config['logo']['bg_color']; ?>"
														data-name="bg_color"
												>
											</div>

										</div>

										<div class="um_preview_block" style="margin: 15px 0;">
											<div class="um_block" id="preview_<?php echo $page['name']; ?>_logo" style="position: relative; padding: 15px 0"></div>

											<div class="mt-3 preview_um_button_wrap">
												<button
														type="button"
														class="apply preview_um preview_um_button"
														data-page="<?php echo $page['name']; ?>"
														data-type="logo"
												>
													PREVIEW
												</button>
											</div>
										</div>
									</div>

								</div>
								<!--end logo tab-->

								<!--start one_liner tab-->
								<div class="<?php echo $page['name']; ?>_one_liner_section um_block_sections <?php echo $page['name']; ?>_um_block_sections
								<?php
								if ( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['one_liner']['enable_one_liner'] ) {
									echo 'active';}
								?>
								">
									<div class="toogle-with-text">
										<div>
											<legend class="screen-reader-text"><span><?php echo wp_kses_post( $page['title'] ); ?></span></legend>

											<label class="main-section-switch">
												<input
													<?php disabled( $data['disabled'], true ); ?>
														data-desc="<?php echo $page['checkbox'] . '_one_liner'; ?>"
														data-page="<?php echo $page['name']; ?>"
														data-type="One Liner"
														class="<?php echo esc_attr( $data['class'] ); ?> um_checkboxes_<?php echo $page['name']; ?>"
														type="checkbox"
														name="<?php echo esc_attr( $this->get_field_key( $data['option_name'] ) ); ?>[]"
														id="<?php echo esc_attr( $this->get_field_key( $page['checkbox'] ) ); ?>_one_liner"
														style="<?php echo esc_attr( $data['css'] ); ?>"
														value="<?php echo wp_kses_post( $page['checkbox'] ); ?>"
													<?php checked( in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['one_liner']['enable_one_liner'] ); ?>
													<?php echo $this->get_custom_attribute_html( $page ); // WPCS: XSS ok. ?>
												/>
												<span class="main-section-slider main-section-round"></span>
											</label>

											<input
													type="hidden"
													name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][enable_one_liner]"
													id="<?php echo $page['checkbox']; ?>_one_liner_enabled"
													value="<?php echo is_array( $page_config ) && $page_config['one_liner']['enable_one_liner'] ? $page_config['one_liner']['enable_one_liner'] : 0; ?>"
											>

											<div id="upstream_messages_desc_<?php echo $page['checkbox']; ?>_one_liner" class="chbx-status mt-2 description main-section-enabled-description page-chbx-status">
												<?php echo in_array( $page['checkbox'], (array) $splitit_upstream_messaging_selection ) && is_array( $page_config ) && $page_config['one_liner']['enable_one_liner'] ? '<span class="description-green">Enabled One Liner</span>' : 'Disabled One Liner'; ?>
											</div>
										</div>


										<div class="text">
											<p>
												This On-Site gadget is a way for us to let shoppers know that we offer installment payments.
											</p>
											<p>
												This is something that we have found to be very important in the online shopping world.
											</p>
										</div>
									</div>

									<div class="parent-wrap">
										<div class="page-setting-content-block">
											<div class="setting-title mt-0">
												Text
											</div>

											<?php if ( $page['name'] != 'shop' ) : ?>
												<div class="mt-3">
													<p class="description">СSS Selector ( can be left blank to display in place by default )</p>
													<input
															class="input-text regular-input"
															type="text"
															name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][regular]"
															id="<?php echo $page['name']; ?>_one_liner_regular"
															value="<?php echo ! is_array( $page_config ) || $page_config['one_liner']['regular'] == '' ? '' : $page_config['one_liner']['regular']; ?>"
															placeholder="Js selector. Example: '.yourClassName or #yourId'"
															data-name="regular"
													>
												</div>
											<?php endif; ?>

											<div class="mt-3">
												<p class="description">Text Option</p>
												<select
														class="select um-select um-text-type"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][text_option]"
														id="<?php echo $page['name']; ?>_one_liner_text_option"
														data-page="<?php echo $page['name']; ?>"
														data-type="one_liner"
														data-name="text_option"
												>
													<option value="payment-a-month" <?php echo is_array( $page_config ) && $page_config['one_liner']['text_option'] && $page_config['one_liner']['text_option'] == 'payment-a-month' ? 'selected' : ''; ?>>
														Payment A Month
													</option>
													<option value="payment-a-month-number-of-payments" <?php echo is_array( $page_config ) && $page_config['one_liner']['text_option'] && $page_config['one_liner']['text_option'] == 'payment-a-month-number-of-payments' ? 'selected' : ''; ?>>
														Payment A Month Number Of Payments
													</option>
													<option value="payment-fourtnightly" <?php echo is_array( $page_config ) && $page_config['one_liner']['text_option'] && $page_config['one_liner']['text_option'] == 'payment-fourtnightly' ? 'selected' : ''; ?>>
														Payment Fortnightly
													</option>
													<option value="payment-fourtnightly-number-of-payments" <?php echo is_array( $page_config ) && $page_config['one_liner']['text_option'] && $page_config['one_liner']['text_option'] == 'payment-fourtnightly-number-of-payments' ? 'selected' : ''; ?>>
														Payment Fortnightly Number Of Payments
													</option>
													<option value="custom" <?php echo is_array( $page_config ) && $page_config['one_liner']['text_option'] && $page_config['one_liner']['text_option'] == 'custom' ? 'selected' : ''; ?>>
														Custom
													</option>
												</select>
											</div>

											<div class="mt-3">
												<input
														class="<?php echo is_array( $page_config ) && $page_config['one_liner']['text_option'] == 'custom' ? '' : 'hide'; ?> input-text regular-input"
														type="text"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][text_custom]"
														id="<?php echo $page['name']; ?>_one_liner_text"
														value="<?php echo ! is_array( $page_config ) || $page_config['one_liner']['text_custom'] == '' ? '' : $page_config['one_liner']['text_custom']; ?>"
														data-name="text_custom"
														data-page="<?php echo $page['name']; ?>"
												>
												<div class="splitit_error"></div>
											</div>

											<div class="mt-3">
												<p class="description">Text Size</p>
												<input
														class="input-text regular-input"
														type="text"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][text_size]"
														id="<?php echo $page['name']; ?>_one_liner_text_size"
														value="<?php echo ! is_array( $page_config ) || $page_config['one_liner']['text_size'] == '' ? '' : $page_config['one_liner']['text_size']; ?>"
														data-size="true"
														data-name="text_size"
														placeholder="Default unit is 'px' but you can enter other '10%', '3em', '2rem'..."
												>
											</div>

										</div>

										<div class="page-setting-content-block mt-3">

											<div class="setting-title mt-0">
												Buttons
											</div>

											<div class="mt-3">
												<p class="description">Learn More Color</p>
												<input
														class="input-text regular-input"
														type="color"
														name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][learn_more_color]"
														id="<?php echo $page['name']; ?>_one_liner_learn_more_color"
														value="<?php echo ! is_array( $page_config ) || $page_config['one_liner']['learn_more_color'] == '' ? '#94d6d0' : $page_config['one_liner']['learn_more_color']; ?>"
														data-name="learn_more_color"
												>
											</div>

											<div class="mt-3">
												<p class="description">Hide Learn More</p>
												<label class="main-section-switch">
													<input
															class=""
															type="checkbox"
															name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][hide_learn_more]"
															id="<?php echo $page['name']; ?>_one_liner_hide_learn_more"
															value="1"
														<?php checked( is_array( $page_config ) && isset( $page_config['one_liner']['hide_learn_more'] ) && $page_config['one_liner']['hide_learn_more'] ); ?>
															data-name="hide_learn_more"
													/>
													<span class="main-section-slider main-section-round"></span>
												</label>
											</div>

											<div class="mt-3">
												<p class="description">Hide Icon</p>
												<label class="main-section-switch">
													<input
															class=""
															type="checkbox"
															name="<?php echo esc_attr( $this->get_field_key( $page_key ) ); ?>[one_liner][hide_icon]"
															id="<?php echo $page['name']; ?>_one_liner_hide_icon"
															value="1"
														<?php checked( is_array( $page_config ) && isset( $page_config['one_liner']['hide_icon'] ) && $page_config['one_liner']['hide_icon'] ); ?>
															data-name="hide_icon"
													/>
													<span class="main-section-slider main-section-round"></span>
												</label>
											</div>

										</div>

										<div class="um_preview_block" style="margin: 15px 0;">
											<div class="um_block" id="preview_<?php echo $page['name']; ?>_one_liner" style="position: relative; padding: 15px 0"></div>

											<div class="mt-3 preview_um_button_wrap">
												<button
														type="button"
														class="apply preview_um preview_um_button"
														data-page="<?php echo $page['name']; ?>"
														data-type="one_liner"
												>
													PREVIEW
												</button>
											</div>
										</div>
									</div>
								</div>
								<!--end one_liner tab-->

							<?php endif; ?>

							<!--end tabs content-->

						</div>
						<!--end page section body-->
					</div>

					<?php endforeach; ?>

				</div>
				<!--end section body-->
			</div>

			<?php

			return ob_get_clean();

		}

		/**
		 * UM preview
		 *
		 * @param $page
		 * @param $type
		 * @param $page_config
		 */
		public function render_um_preview( $page, $type, $page_config ) {

			$um_block = '<div id="preview_' . $page . '_' . $type . '" style="position: relative; padding: 15px 0">';

			if ( ! empty( $page_config ) ) {

				switch ( $type ) {
					case ( 'strip' ):
						$baseTag = 'spt-strip';
						break;
					case ( 'banner' ):
						$baseTag = 'spt-banner';
						break;
					case ( 'logo' ):
						$baseTag = 'spt-floating-logo';
						break;
					case ( 'one_liner' ):
						$baseTag = 'spt-one-liner';
						break;
				}

				$um_block .= '<' . $baseTag;

				foreach ( $page_config as $key => $config ) {
					if ( $key !== $type . '_regular' && $key !== $type . '_sale' && $key !== $type . '_docking' ) {
						$um_block .= ' ' . $key . '=' . json_encode( $config );
					}
				}

				if ( $type === 'logo' || $type === 'one_liner' ) {
					$um_block .= ' amount="1000" installments="4" ';
				}

				if ( $type === 'strip' ) {
					$um_block .= ' relative_to_parent="true" ';
				}

				$um_block .= '></' . $baseTag . '>';
			}

			$um_block .= '</div>';

			echo $um_block;
		}

		/**
		 * Method for custom splitit per product option setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_splitit_product_option_settings_section_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			ob_start();
			?>

			<!--make always disabled Splitit per product-->
			<input
					type="hidden"
					name="<?php echo esc_attr( $field_key ); ?>"
					id="<?php echo esc_attr( $field_key ); ?>"
					value="0"
			/>

			<?php

			return ob_get_clean();

		}

		/**
		 * Method for custom payment method setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_payment_method_settings_section_html( $key, $data ) {
			$defaults = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>

			<div class="main-section payment_method">
				<!--start section header-->
				<div id="payment_method_collapse" class="setting-wrap setting-section-block">
					<div class="section-header-title">
						<span class="setting-title">
							<?php echo wp_kses_post( $data['title'] ); ?>
						</span>
						<div class="mt-3">
							<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
						</div>
						<div id="error-box"></div>
					</div>

					<div class="section-header-collapse">
						<span class="section-close" id="payment_method_collapse_arrow"></span>
					</div>
				</div>
				<!--end section header-->

				<!--start section body-->
				<div id="payment_method_settings_section" class="setting-content-block mt-3 hide">
					<!--start splitit_settings_3d-->
					<div class="d-flex">
						<div class="mr-3 description" style="width: 285px;">
								<span class="settings-3d-title"><?php echo wp_kses_post( $data['splitit_settings_3d']['title'] ); ?></span>

								<span id="splitit_settings_3d_tooltip" class="tooltip-icon"></span>
								<span class="tooltip">
									<span id="splitit_settings_3d_tooltiptext" class="tooltiptext">
										<span class="setting-title mb-3">
											<?php echo $data['splitit_settings_3d']['tip_title']; // @WPCS: XSS ok. ?>
										</span>
										<br>
										<br>
										<span class="description tip-description">
											<?php echo $data['splitit_settings_3d']['tip_description']; // @WPCS: XSS ok. ?>
										</span>
										<button class="tooltip-button" id="splitit_settings_3d_tooltip_close" type="button">Got it!</button>
									</span>
								</span>

						</div>
						<div>
							<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['splitit_settings_3d']['title'] ); ?></span></legend>

							<label class="main-section-switch">
								<input
									<?php disabled( $data['disabled'], true ); ?>
										class="<?php echo esc_attr( $data['class'] ); ?> <?php echo $this->get_option( 'splitit_settings_3d' ) == 1 ? 'on' : 'off'; ?>"
										type="checkbox" name="<?php echo esc_attr( $this->get_field_key( 'splitit_settings_3d' ) ); ?>"
										id="<?php echo esc_attr( $this->get_field_key( 'splitit_settings_3d' ) ); ?>"
										style="<?php echo esc_attr( $data['css'] ); ?>"
										value="1"
									<?php checked( $this->get_option( 'splitit_settings_3d' ), '1' ); ?>
									<?php echo $this->get_custom_attribute_html( $data['splitit_settings_3d'] ); // WPCS: XSS ok. ?>
								/>
								<span class="main-section-slider main-section-round"></span>
							</label>
						</div>
						<div id="splitit_settings_3d_desc" class="ml-3 description main-section-enabled-description">
							<?php echo $this->get_option( 'splitit_settings_3d' ) == '1' ? '<span class="description-green">Enabled</span>' : 'Disabled'; ?>
						</div>
					</div>
					<!--end splitit_settings_3d-->

					<!--start splitit_auto_capture-->
					<div class="d-flex mt-3">
						<div class="mr-3 description" style="width: 285px;">
								<span class="settings-3d-title"><?php echo wp_kses_post( $data['splitit_auto_capture']['title'] ); ?></span>

								<span id="splitit_auto_capture_tooltip" class="tooltip-icon"></span>
								<span class="tooltip">
									<span id="splitit_auto_capture_tooltiptext" class="tooltiptext">
										<span class="setting-title mb-3">
											<?php echo $data['splitit_auto_capture']['tip_title']; // @WPCS: XSS ok. ?>
										</span>
										<br>
										<br>
										<span class="description tip-description">
											<?php echo $data['splitit_auto_capture']['tip_description']; // @WPCS: XSS ok. ?>
										</span>
										<button class="tooltip-button" id="splitit_auto_capture_tooltip_close" type="button">Got it!</button>
									</span>
								</span>
						</div>
						<div>
							<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['splitit_auto_capture']['title'] ); ?></span></legend>

							<label class="main-section-switch">
								<input
									<?php disabled( $data['disabled'], true ); ?>
										class="<?php echo esc_attr( $data['class'] ); ?> <?php echo $this->get_option( 'splitit_auto_capture' ) == 1 ? 'on' : 'off'; ?>"
										type="checkbox" name="<?php echo esc_attr( $this->get_field_key( 'splitit_auto_capture' ) ); ?>"
										id="<?php echo esc_attr( $this->get_field_key( 'splitit_auto_capture' ) ); ?>"
										style="<?php echo esc_attr( $data['css'] ); ?>"
										value="1"
									<?php checked( $this->get_option( 'splitit_auto_capture' ), '1' ); ?>
									<?php echo $this->get_custom_attribute_html( $data['splitit_auto_capture'] ); // WPCS: XSS ok. ?>
								/>
								<span class="main-section-slider main-section-round"></span>
							</label>
						</div>
						<div id="splitit_auto_capture_desc" class="ml-3 description main-section-enabled-description">
							<?php echo $this->get_option( 'splitit_auto_capture' ) == '1' ? '<span class="description-green">Enabled</span>' : 'Disabled'; ?>
						</div>
					</div>
					<!--end splitit_auto_capture-->

					<!--start splitit_inst_conf-->
					<div class="mt-5">
						<?php echo $this->generate_new_instalments_grid( 'splitit_inst_conf', $data['splitit_inst_conf'] ); ?>
					</div>
					<!--end splitit_inst_conf-->

					<!--start splitit_upstream_default_installments-->
						<!--make always empty field Choose the number by which you'd like your customer journey amount to be divided-->
						<input type="hidden"
							   name="<?php echo esc_attr( $this->get_field_key( 'splitit_upstream_default_installments' ) ); ?>"
							   id="<?php echo esc_attr( $this->get_field_key( 'splitit_upstream_default_installments' ) ); ?>"
							   value=""
						>
					<!--end splitit_upstream_default_installments-->
				</div>
				<!--end section body-->
			</div>

			<?php

			return ob_get_clean();

		}

		/**
		 * Method for custom general setting section on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_general_setting_section_html( $key, $data ) {
			$defaults = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>

				<div class="main-section">

					<div class="whole-page-overlay" id="settings_page_loader">
						<div class="center-loader"></div>

<!--                        <img class="center-loader"  style="height:100px;" src="assets/img/loading-buffering.gif"/>-->
					</div>

					<div class="setting-wrap">
						<div class="main-section-merchant d-flex">
							<div class="mr-5">
								<span class="setting-title">
									<?php echo wp_kses_post( $data['merchant']['title'] ); ?>
								</span>
								<br>
								<?php echo $this->get_description_html( $data['merchant'] ); // WPCS: XSS ok. ?>
							</div>
							<div>
								<span class="setting-title">
									<?php echo wp_kses_post( $data['terminal']['title'] ); ?>
								</span>
								<br>
								<?php echo $this->get_description_html( $data['terminal'] ); // WPCS: XSS ok. ?>
							</div>
						</div>

						<div class="main-section-enabled d-flex">
							<div class="mr-3 main-section-enabled-title">
								<span class="setting-title">
									<?php echo wp_kses_post( $data['enabled']['title'] ); ?>
								</span>
							</div>
							<div>
								<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['enabled']['title'] ); ?></span></legend>

								<label class="main-section-switch">
									<input
										<?php disabled( $data['disabled'], true ); ?>
											class="<?php echo esc_attr( $data['class'] ); ?>"
											type="checkbox" name="<?php echo esc_attr( $this->get_field_key( 'enabled' ) ); ?>"
											id="<?php echo esc_attr( $this->get_field_key( 'enabled' ) ); ?>"
											style="<?php echo esc_attr( $data['css'] ); ?>"
											value="1"
										<?php checked( $this->get_option( 'enabled' ), 'yes' ); ?>
										<?php echo $this->get_custom_attribute_html( $data['enabled'] ); // WPCS: XSS ok. ?>
									/>
									<span class="main-section-slider main-section-round"></span>
								</label>
							</div>
							<div id="main-section-enabled-desc" class="ml-3 description main-section-enabled-description">
								<?php echo $this->get_option( 'enabled' ) == 'yes' ? '<span class="description-green">Enabled</span>' : 'Disabled'; ?>
							</div>
						</div>
					</div>

					<div class="setting-wrap" style="padding-bottom: 0">
						<div class="main-section-environment mt-3"
							 style="<?php echo esc_attr( $data['splitit_environment']['setting_block_css'] ); ?>"
						>
							<div class="setting-title">
								<?php echo wp_kses_post( $data['splitit_environment']['title'] ); ?>
							</div>
							<div>
								<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['splitit_environment']['title'] ); ?></span></legend>

								<?php echo $this->get_description_html( $data['splitit_environment'] ); // WPCS: XSS ok. ?>

								<select
										class="env-select select <?php echo esc_attr( $data['class'] ); ?>"
										name="<?php echo esc_attr( $this->get_field_key( 'splitit_environment' ) ); ?>"
										id="<?php echo esc_attr( $this->get_field_key( 'splitit_environment' ) ); ?>"
										style="display: none; <?php echo esc_attr( $data['splitit_environment']['css'] ); ?>"
									<?php disabled( $data['disabled'], true ); ?>
									<?php echo $this->get_custom_attribute_html( $data['splitit_environment'] ); // WPCS: XSS ok. ?>
								>
									<?php foreach ( (array) $data['splitit_environment']['options'] as $option_key => $option_value ) : ?>
										<?php if ( is_array( $option_value ) ) : ?>
											<optgroup label="<?php echo esc_attr( $option_key ); ?>">
												<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
													<option
															value="<?php echo esc_attr( $option_key_inner ); ?>"
														<?php selected( (string) $option_key_inner, esc_attr( $this->get_option( 'splitit_environment' ) ) ); ?>
													>
														<?php echo esc_html( $option_value_inner ); ?>
													</option>
												<?php endforeach; ?>
											</optgroup>
										<?php else : ?>
											<option
													value="<?php echo esc_attr( $option_key ); ?>"
												<?php selected( (string) $option_key, esc_attr( $this->get_option( 'splitit_environment' ) ) ); ?>
											>
												<?php echo esc_html( $option_value ); ?>
											</option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>

					<div class="main-section-logout-button">
						<div class="main-section-logout-button-title">
							<span class="setting-title">
								<?php echo wp_kses_post( $data['logout_button']['title'] ); ?>
							</span>
						</div>
					</div>
				</div>

			<?php

			return ob_get_clean();

		}

		/**
		 * Method for custom checkbox on the settings page
		 *
		 * @param $key
		 * @param $data
		 *
		 * @return false|string
		 */
		public function generate_custom_checkbox_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'label'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			if ( ! $data['label'] ) {
				$data['label'] = $data['title'];
			}

			ob_start();
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?>
						<?php
						echo $this->get_tooltip_html( $data ); // @WPCS: XSS ok.
						?>
					</label>
				</th>
				<td class="forminp">
					<fieldset>
						<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span>
						</legend>
						<label for="<?php echo esc_attr( $field_key ); ?>" class="switch">
							<input <?php disabled( $data['disabled'], true ); ?>
									class="<?php echo esc_attr( $data['class'] ); ?>" type="checkbox"
									name="<?php echo esc_attr( $field_key ); ?>"
									id="<?php echo esc_attr( $field_key ); ?>"
									style="<?php echo esc_attr( $data['css'] ); ?>"
									value="1" <?php checked( $this->get_option( $key ), '1' ); ?> <?php
									echo $this->get_custom_attribute_html( $data ); // @WPCS: XSS ok.
									?>
							/>
							<div class="slider round">
								<span class="on">ON</span>
								<span class="off">OFF</span>
							</div>
						</label><br/>
						<?php
						echo $this->get_description_html( $data ); // @WPCS: XSS ok.
						?>
					</fieldset>
				</td>
			</tr>
			<?php

			return ob_get_clean();
		}

		/**
		 * Method for generate Installment grid subtitle with installments range
		 */
		public function generate_merchant_settings_allow_range_title() {
			if ( get_option( 'merchant_settings' ) ) {
				return '<b>Installment range is: ' . get_option( 'merchant_settings' )->MinInstallments . ' to ' . get_option( 'merchant_settings' )->MaxInstallments . ' </b><br>
                        <b style="display: block; margin-top: 10px">Price range is: <span>' . get_option( 'merchant_settings' )->MinAmount . '</span> to <span>' . get_option( 'merchant_settings' )->MaxAmount . '</span> </b>
                        <input type="hidden" id="merchant_amount_min" value="' . get_option( 'merchant_settings' )->MinAmount . '"><input type="hidden" id="merchant_amount_max" value="' . get_option( 'merchant_settings' )->MaxAmount . '">';
			}
		}

		/**
		 * Method for Installment grid on the settings page
		 *
		 * @param $v
		 * @param $k
		 *
		 * @return string
		 */
		public function generate_new_instalments_grid( $k, $v ) {
			$html = '<div class="" id="main_ic_container">
                            <div class="setting-title mb-3">Number of Installments<span>*</span></div>
                            <div class="description mb-2">Set the installment options to show for each product price range. (For example, you may wish to offer larger numbers of installments for expensive products.)</div>
                            <div class="description mb-2">Note that the price ranges shouldn’t overlap.</div>
                            <div class="description mb-4">Note that asterisks signify that a particular setting is mandatory.</div>
                            <div class="description">' . $this->generate_merchant_settings_allow_range_title() . '</div>
                            <div id="ic_container">
                                <div class="ic_tr mb-3 mt-3"></div>';

			if ( 0 == count( $v ) ) {
				$default_inst_settings = array(
					'ic_from'        => array(
						'title'   => __( 'Starting price*', 'splitit_ff_payment' ),
						'type'    => 'number',
						'class'   => 'from',
						'default' => '0',
					),
					'ic_to'          => array(
						'title'   => __( 'Ending price*', 'splitit_ff_payment' ),
						'type'    => 'number',
						'class'   => 'to',
						'default' => '1000',
					),
					'ic_installment' => array(
						'title'   => __( 'Installments*', 'splitit_ff_payment' ),
						'type'    => 'select',
						'class'   => 'installments',
						'options' => SplitIt_FlexFields_Payment_Plugin_Settings::merchant_installments_range(),
					),
					'ic_action'      => array(
						'title' => '<span class="delete_instalment"><span class="trash-icon-new"></span></span>',
						'css'   => 'display:none;',
					),
				);

				$html .= '<div style="display: flex" class="ic_tr mb-3 mt-3" id="ic_tr_0">';
				$html .= $this->generate_new_custom_text_field_in_grid( 0, 'ic_from', $default_inst_settings['ic_from'] );
				$html .= $this->generate_new_custom_text_field_in_grid( 0, 'ic_to', $default_inst_settings['ic_to'] );
				$html .= $this->generate_new_custom_select_field_in_grid( 0, 'ic_installment', $default_inst_settings['ic_installment'] );
				$html .= $this->generate_new_custom_text_field_in_grid( 0, 'ic_action', $default_inst_settings['ic_action'], true );
				$html .= '</div>';
			} else {
				foreach ( $v as $k1 => $v1 ) {
					$i = 0;

					if ( count( (array) $v1 ) == 4 ) {
						foreach ( (array) $v1 as $k2 => $v2 ) {
							if ( 0 === $i ) {
								$html .= '<div style="display: flex" class="ic_tr mb-3 mt-3" id="ic_tr_' . $k1 . '">';
							}
							if ( 'ic_action' === $k2 ) {
								$html .= $this->generate_new_custom_text_field_in_grid( $k1, $k2, $v2, true );
							} elseif ( 'ic_installment' === $k2 ) {
								$html .= $this->generate_new_custom_select_field_in_grid( $k1, $k2, $v2 );
							} else {
								$html .= $this->generate_new_custom_text_field_in_grid( $k1, $k2, $v2 );
							}

							if ( 3 === $i ) {
								$html .= '</div>';
							}
							$i ++;
						}
					}
				}
			}

			$html .= '<div>
                        <div id="installment-error-message" class="installment-message">Please fill the required fields</div>
                        <div id="installment-remove-error-message" class="installment-message">Installments cannot be empty</div>
                        <div class="d-flex">
                            <span class="plus-icon"></span>
                            <span id="add_instalment" class="installment-button">Add another price range</span>
                        </div>
                      </div>
                    </div>
                </div>
            </div>';

			return $html;
		}

		/**
		 * Method for Installment grid on the settings page
		 *
		 * @param $v
		 * @param $k
		 *
		 * @return string
		 */
		public function generate_instalments_grid( $v, $k ) {
			$html = '<tr valign="top" class="custom_settings" id="main_ic_container">
                            <th class="heading">Number of Installments</th>
                            <td>
                            <table>
                            <p class="help_text_heading help_text_size">You can define several installments per each amount range. Do not overlap amount ranges. See examples:</p>
                            <p class="help_text_bold help_text_size">Bad configuration:</p>
                            <p class="help_text_size">100-500 | 2,3,4</p>
                            <p class="help_text_last help_text_size">300-700 | 4,7,8</p>
                            <p class="help_text_bold help_text_size">Good configuration:</p>
                            <p class="help_text_size">100-500 | 2,3,4</p>
                            <p class="help_text_last help_text_size">501-700 | 5,6,7</p>
                            </table>
                                <table id="ic_container">
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>No. of installments</th>
                                        <th>Action</th>
                                    </tr>
                                ';
			foreach ( $v as $k1 => $v1 ) {
				$i = 0;

				if ( count( (array) $v1 ) == 4 ) {
					foreach ( (array) $v1 as $k2 => $v2 ) {
						if ( 0 === $i ) {
							$html .= '<tr class="ic_tr" id="ic_tr_' . $k1 . '">';
						}

						if ( 'ic_action' === $k2 ) {
							$html .= $this->generate_custom_text_field_in_grid( $k1, $k2, $v2, true );
						} else {
							$html .= $this->generate_custom_text_field_in_grid( $k1, $k2, $v2 );
						}

						if ( 3 === $i ) {
							$html .= '</tr>';
						}
						$i ++;
					}
				}
			}
			$html .= '<tr><td colspan="4"><button class="btn btn-default" type="button" id="add_instalment">Add</button></td></tr>
                        </table>
                            </td>
                            </tr>';

			return $html;
		}

		/**
		 * Generate Text Input HTML.
		 *
		 * @param string $key Field key.
		 * @param array  $data Field data.
		 * @since  1.0.0
		 * @return string
		 */
		public function generate_merchant_login_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'placeholder'       => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			session_start();
			$user_data = get_option( 'splitit_logged_user_data' );

			ob_start();

			?>

			<div class="setting-wrap" style="padding-bottom: 0;">
				<div class="welcome-message">
					<div class="hi">Hey <?php echo $user_data ? ucwords( $user_data->FirstName ) : ''; ?>!</div>
					<div class="hi">Welcome Back :)</div>
				</div>
				<div class="logo-section"></div>
			</div>
			<?php

			return ob_get_clean();
		}

		/**
		 * Method for custom text field on the settings page
		 *
		 * @param $order
		 * @param $key
		 * @param $data
		 * @param false $with_label
		 *
		 * @return false|string
		 */
		public function generate_new_custom_text_field_in_grid( $order, $key, $data, $with_label = false ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'placeholder'       => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			ob_start();
			$text = $this->get_option( $key );

			if ( isset( $this->settings['splitit_inst_conf'][ $key ][ $order ] ) ) {
				$txt_value = $this->settings['splitit_inst_conf'][ $key ][ $order ];
			} elseif ( isset( $text ) && ! empty( $text ) ) {
				$txt_value = $text;
			} else {
				$txt_value = $data['default'] ?? '';
			}

			?>
			<?php if ( $with_label ) : ?>
				<div class="titledesc ic_action">
					<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); // @WPCS: XSS ok. ?></label>
				</div>
			<?php else : ?>
				<div class="forminp mr-3">
					<fieldset>
						<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span>
						</legend>
						<p class="description"><?php echo wp_kses_post( $data['title'] ); ?></p>
						<div style="position: relative">
							<span
									id="<?php echo esc_attr( $field_key ) . '_' . $order; ?>_currency_symbol" 
									style="position: absolute; left: 11px; top: 14px; font-weight: 500; font-size: 16px;"
							>
								<?php echo get_woocommerce_currency_symbol(); ?>
							</span>
							<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>"
								   type="<?php echo esc_attr( $data['type'] ); ?>"
								   name="<?php echo esc_attr( $field_key ); ?>[]"
								   id="<?php echo esc_attr( $field_key ) . '_' . $order; ?>"
								   style="<?php echo esc_attr( $data['css'] ); ?>"
								   value="<?php echo esc_attr( $txt_value ); ?>"
								   placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php
									echo $this->get_custom_attribute_html( $data ); // @WPCS: XSS ok.
									?>
							/>
						</div>
						<?php
						echo $this->get_description_html( $data ); // @WPCS: XSS ok.
						?>
					</fieldset>
				</div>
			<?php endif; ?>
			<?php

			return ob_get_clean();
		}

		/**
		 * Method for custom select field on the settings page
		 *
		 * @param $order
		 * @param $key
		 * @param $data
		 * @param bool  $with_label
		 *
		 * @return false|string
		 */
		public function generate_new_custom_select_field_in_grid( $order, $key, $data, $with_label = false ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'placeholder'       => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			ob_start();
			$text = $this->get_option( $key );

			if ( isset( $this->settings['splitit_inst_conf'][ $key ][ $order ] ) ) {
				$txt_value = $this->settings['splitit_inst_conf'][ $key ][ $order ];
			} elseif ( isset( $text ) && ! empty( $text ) ) {
				$txt_value = $text;
			} else {
				$txt_value = $data['default'] ?? '';
			}

			$txt_value_to_array = explode( ',', $txt_value );

			?>
			<?php if ( $with_label ) : ?>
				<div class="titledesc ic_action">
					<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); // @WPCS: XSS ok. ?></label>
				</div>
			<?php else : ?>
				<div class="forminp mr-3">

					<fieldset>
						<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span>
						</legend>
						<p class="description"><?php echo wp_kses_post( $data['title'] ); ?></p>

						<select
								multiple
								class="is-select select <?php echo esc_attr( $data['class'] ); ?>"
								name="<?php echo esc_attr( $field_key ); ?>[<?php echo esc_attr( $order ); ?>][]"
								id="<?php echo esc_attr( $field_key ) . '_' . $order; ?>"
								style="<?php echo esc_attr( $data['css'] ); ?>"
							<?php disabled( $data['disabled'], true ); ?>
							<?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?>
						>
							<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
								<?php if ( is_array( $option_value ) ) : ?>
									<optgroup label="<?php echo esc_attr( $option_key ); ?>">
										<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
											<option
													value="<?php echo esc_attr( $option_key_inner ); ?>"
												<?php selected( in_array( (string) $option_key_inner, $txt_value_to_array ) ); ?>
											><?php echo esc_html( $option_value_inner ); ?>
											</option>
										<?php endforeach; ?>
									</optgroup>
								<?php else : ?>
									<option
											value="<?php echo esc_attr( $option_key ); ?>"
										<?php selected( in_array( (string) $option_key, $txt_value_to_array ) ); ?>
									><?php echo esc_html( $option_value ); ?>
									</option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
						<?php
						echo $this->get_description_html( $data ); // @WPCS: XSS ok.
						?>
					</fieldset>
				</div>

			<?php endif; ?>
			<?php

			return ob_get_clean();
		}

		/**
		 * Method for custom text field on the settings page
		 *
		 * @param $order
		 * @param $key
		 * @param $data
		 * @param false $with_label
		 *
		 * @return false|string
		 */
		public function generate_custom_text_field_in_grid( $order, $key, $data, $with_label = false ) {
			$field_key = $this->get_field_key( $key );
			$defaults  = array(
				'title'             => '',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'placeholder'       => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => array(),
			);

			$data = wp_parse_args( $data, $defaults );

			ob_start();
			$text = $this->get_option( $key );

			if ( isset( $this->settings['splitit_inst_conf'][ $key ][ $order ] ) ) {
				$txt_value = $this->settings['splitit_inst_conf'][ $key ][ $order ];
			} elseif ( isset( $text ) && ! empty( $text ) ) {
				$txt_value = $text;
			} else {
				$txt_value = $data['default'] ?? '';
			}

			?>
			<?php if ( $with_label ) : ?>
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); // @WPCS: XSS ok. ?></label>
				</th>
			<?php else : ?>
				<td class="forminp">
					<fieldset>
						<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span>
						</legend>
						<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>"
							   type="<?php echo esc_attr( $data['type'] ); ?>"
							   name="<?php echo esc_attr( $field_key ); ?>[]"
							   id="<?php echo esc_attr( $field_key ) . '_' . $order; ?>"
							   style="<?php echo esc_attr( $data['css'] ); ?>"
							   value="<?php echo esc_attr( $txt_value ); ?>"
							   placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php
								echo $this->get_custom_attribute_html( $data ); // @WPCS: XSS ok.
								?>
						/>
						<?php
						echo $this->get_description_html( $data ); // @WPCS: XSS ok.
						?>
					</fieldset>
				</td>
			<?php endif; ?>
			<?php

			return ob_get_clean();
		}

		/**
		 * Method allows changing saving of the Installment grid form
		 *
		 * @return bool
		 */
		public function process_admin_options() {
			$this->init_settings();

			$post_data = $this->get_post_data();

			foreach ( $this->get_form_fields() as $key => $field ) {
				if ( 'title' !== $this->get_field_type( $field ) ) {
					try {
						if ( 'general_setting_section' === $key ) {
							$this->settings['enabled']             = $this->get_field_value( 'enabled', $field['enabled'], $post_data );
							$this->settings['splitit_environment'] = $this->get_field_value( 'splitit_environment', $field['splitit_environment'], $post_data );
						} elseif ( 'Payment_Method_Settings_section' === $key ) {
							$this->settings['splitit_settings_3d']                   = $this->get_field_value( 'splitit_settings_3d', $field['splitit_settings_3d'], $post_data );
							$this->settings['splitit_auto_capture']                  = $this->get_field_value( 'splitit_auto_capture', $field['splitit_auto_capture'], $post_data );
							$this->settings['splitit_upstream_default_installments'] = $this->get_field_value( 'splitit_upstream_default_installments', $field['splitit_upstream_default_installments'], $post_data );

							if ( isset( $post_data['woocommerce_splitit_ic_from'] ) && isset( $post_data['woocommerce_splitit_ic_to'] ) && isset( $post_data['woocommerce_splitit_ic_installment'] ) ) {

								$woocommerce_splitit_ic_installment = array();

								foreach ( $post_data['woocommerce_splitit_ic_installment'] as $post_datum ) {
									$woocommerce_splitit_ic_installment[] = implode( ',', $post_datum );
								}

								$new_arr                             = array(
									'ic_from'        => $post_data['woocommerce_splitit_ic_from'],
									'ic_to'          => $post_data['woocommerce_splitit_ic_to'],
									'ic_installment' => $woocommerce_splitit_ic_installment,
								);
								$this->settings['splitit_inst_conf'] = $new_arr;
							} else {
								$this->settings['splitit_inst_conf'] = array();
							}
						} elseif ( 'Upstream_Messaging_Settings_section' === $key ) {
							$this->settings['splitit_upstream_messaging_selection'] = $this->get_field_value( 'splitit_upstream_messaging_selection', $field['splitit_upstream_messaging_selection'], $post_data );

							foreach ( $field['pages'] as $page_key => $page ) {
								$this->settings[ $page_key ] = $this->get_field_value( $page_key, $page, $post_data );
							}
						} elseif ( 'New_Upstream_Messaging_Settings_section' === $key ) {
							$this->settings['splitit_upstream_messaging_selection'] = $this->get_field_value( 'splitit_upstream_messaging_selection', $field['splitit_upstream_messaging_selection'], $post_data );

							foreach ( $field['pages'] as $page_key => $page ) {
								if ( isset( $post_data[ 'woocommerce_splitit_' . $page_key ] ) ) {
									$this->settings[ $page_key ] = $post_data[ 'woocommerce_splitit_' . $page_key ];
								} else {
									$this->settings[ $page_key ] = array();
								}
							}
						} else {
							$this->settings[ $key ] = $this->get_field_value( $key, $field, $post_data );
						}
					} catch ( Exception $e ) {
						$this->add_error( $e->getMessage() );
					}
				}
			}

			return update_option( $this->get_option_key(), apply_filters( 'woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings ) );
		}

		/**
		 * Method that checks SSL connection
		 */
		public function do_ssl_check() {
			if ( 'yes' === $this->enabled && ! is_ssl() ) {
				echo '<div class="error"><p>' . sprintf( __( '<strong>%1$s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href="%2$s">forcing the checkout pages to be secured.</a>' ), $this->method_title, admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
			}
		}

		/**
		 * Method for initiate flex fields styles and scripts
		 */
		public function init_flex_fields_styles_and_scripts() {
			function add_flex_field_sandbox_scripts() {
				if ( is_checkout() ) {
					wp_register_script( 'flex_field_js', 'https://flex-form.sandbox.splitit.com/flex-form.js', null, null, true );
					wp_enqueue_script( 'flex_field_js' );
				}
			}

			function add_flex_field_production_scripts() {
				if ( is_checkout() ) {
					wp_register_script( 'flex_field_js', 'https://flex-form.production.splitit.com/flex-form.js', null, null, true );
					wp_enqueue_script( 'flex_field_js' );
				}
			}

			if ( $this->splitit_environment == 'sandbox' ) {
				add_action( 'wp_enqueue_scripts', 'add_flex_field_sandbox_scripts' );
			} elseif ( $this->splitit_environment == 'production' ) {
				add_action( 'wp_enqueue_scripts', 'add_flex_field_production_scripts' );
			}
		}

		/**
		 * Method that calls a method to insert custom flex field CSS from the settings page
		 */
		public function init_custom_flex_fields_styles() {
			add_action( 'wp_head', array( $this, 'flex_fields_custom_styles' ), 100 );
		}

		/**
		 * Method for initiate Upstream messaging styles and scripts
		 */
		public function init_upstream_messaging_styles_and_scripts() {
			add_action( 'wp_footer', array( $this, 'upstream_messaging_script' ) );
			add_action( 'wp_head', array( $this, 'upstream_messaging_custom_styles' ), 100 );
		}

		/**
		 * Method for insert in the footer custom flex field css
		 */
		public function flex_fields_custom_styles() {
			if ( is_checkout() ) {
				echo '<style>' . wp_strip_all_tags( $this->settings['splitit_flex_fields_css'] ) . '</style>';
			}
		}

		/**
		 * Method for asynchronous processing Refund
		 */
		public function splitit_refund_result_async() {

			$log_data = array(
				'user_id' => null,
				'method'  => __( 'splitit_refund_result_async() Splitit', 'splitit_ff_payment' ),
			);
			SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, 'Refund Async hook arrived' );

			try {
				$raw_post_data = file_get_contents("php://input");
				SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Hook body: ' . $raw_post_data );

				if (!empty($raw_post_data)) {
					$decoded_data = json_decode($raw_post_data, true);

					if ($decoded_data !== null) {
						$credit_to_shopper_value = $decoded_data['RefundDetails']['CreditToShopper'][0]['Value'] ?? 0;
						$succeed_amount = $decoded_data['RefundSummary']['SucceedAmount'];
						$failed_amount = $decoded_data['RefundSummary']['FailedAmount'];
						$refund_id = $decoded_data['RefundId'];
						$ipn = $decoded_data['InstallmentPlanNumber'];

						if ( SplitIt_FlexFields_Payment_Plugin_Log::check_exist_order_by_ipn_and_refund_id( $ipn, $refund_id ) ) {
							$order_by_refund = SplitIt_FlexFields_Payment_Plugin_Log::select_from_refund_log_by_ipn_and_refund_id( $ipn, $refund_id );

							$order_id              = $order_by_refund->order_id;
							$order_refund_id       = $order_by_refund->refund_id;
							$requested_action_type = $order_by_refund->action_type;

							SplitIt_FlexFields_Payment_Plugin_Log::update_refund_log( $order_by_refund->id, array( 'action_type' => 'done' ) );

							$order = wc_get_order( $order_id );

							if ( $order ) {
								if ( $order_refund_id == $refund_id ) {
									if ( 0 == $failed_amount ) {
										if ( 'refund' == $requested_action_type ) {
											$refunds = $order->get_refunds();

											if ( ( empty( $refunds ) || $order->get_remaining_refund_amount() > 0 ) && in_array( $order->get_status(), array(
													'processing',
													'completed'
												) ) ) {

												// Doc from Splitit said: When the user presses the cancel button, if you don’t sure if it was a cancellation or a refund, than check the webhook response, and in case of success, if you find there “CreditToShopper” object with value greater than zero, in that case it is a Refund. Otherwise, it is a cancellation.
												// BUT: if I send partial refund amount, I get in response an empty CreditToShopper object, but I need to make Refund on WC, not Cancel
												// $amount = $credit_to_shopper_value > 0 ? $succeed_amount : $order->get_total();

												// So I use $succeed_amount
												$amount = $succeed_amount;

												$reason = 'splitit_programmatically';

												if ( $order->get_remaining_refund_amount() >= $amount ) {
													$refund = wc_create_refund(
														array(
															'amount'         => $amount,
															'reason'         => $reason,
															'order_id'       => $order_id,
															'refund_payment' => true,
														)
													);

													if ( is_wp_error( $refund ) ) {
														if ( $refund->get_error_message() == 'Invalid refund amount.' ) {
															$order->add_order_note( 'Refund failed by Splitit: Refund requested amount = ' . $amount . ' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() );
															SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund requested amount = ' . $amount . ' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() . '; Order ID: ' . $order_id . ', ipn = ' . $ipn );
														} else {
															$order->add_order_note( 'Refund failed by Splitit. Amount: ' . $amount . ';Error: ' . $refund->get_error_message() );
															SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund error: ' . $refund->get_error_message() . '; Amount: ' . $amount . '; Order ID: ' . $order_id . ', ipn = ' . $ipn );
														}
													} else {
														$order->add_order_note( 'A refund for the amount = ' . $amount . ' has succeeded on the Splitit side.' );
														SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund success. Amount: ' . $amount . ', Order ID: ' . $order_id . ' Refund ID: ' . $order_refund_id . ', ipn = ' . $ipn );
													}
												} else {
													//$order->add_order_note( 'Refund failed by Splitit: Refund requested amount = '. $amount .' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() );
													$order->add_order_note( 'Splitit made a refund for a different amount = ' . $amount . '; Check this order in the Merchant Portal or contact Splitit support.' );
													//SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund requested amount = ' . $amount . ' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() . 'Order ID: ' . $order_id . ', ipn = ' . $ipn );
													throw new Exception( __( 'Refund requested amount = ' . $amount . ' exceeds remaining order balance of ' . $order->get_remaining_refund_amount() . 'Order ID: ' . $order_id . ', ipn = ' . $ipn . '', 'splitit_ff_payment' ) );
												}
											} else {
												SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund has already been completed for this order. Order ID: ' . $order_id . ', ipn = ' . $ipn );
											}
										} elseif ( 'cancel' == $requested_action_type ) {
											$order->add_order_note( 'Cancel for the amount = ' . $succeed_amount . ' is succeeded on the Splitit side' );
											SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Cancel success. Amount = ' . $succeed_amount . ', Order ID: ' . $order_id . ' Refund ID: ' . $order_refund_id . ', ipn = ' . $ipn );

											$order->update_status( 'cancelled' );
										}
									} else {
										$order->add_order_note( 'Refund failed by Splitit. For more details please contact the Splitit Support Team' );
										SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Refund failed by Splitit. Failed Amount = ' . $failed_amount . ', ipn = ' . $ipn );
									}
								} else {
									$order->add_order_note( 'Refund ID saved in platform = ' . $order_refund_id . ' for this order ID = ' . $order_id . ' different with Refund ID in hook from Splitit = ' . $refund_id . '; IPN = ' . $ipn . '' );
									throw new Exception( __( 'Refund ID saved in platform = ' . $order_refund_id . ' for this order ID = ' . $order_id . ' different with Refund ID in hook from Splitit = ' . $refund_id . '; IPN = ' . $ipn . '', 'splitit_ff_payment' ) );
								}
							} else {
								throw new Exception( __( 'Refund order programmatically is failed, no order information in DB. IPN = ' . $ipn . '', 'splitit_ff_payment' ) );
							}
						} else {
							throw new Exception( __( 'Refund order programmatically is failed, no order IPN information in DB. IPN = ' . $ipn . '', 'splitit_ff_payment' ) );
						}
					} else {
						SplitIt_FlexFields_Payment_Plugin_Log::log_to_file('Error decoding webhook raw data.');
					}
				} else {
					SplitIt_FlexFields_Payment_Plugin_Log::log_to_file('Empty webhook raw data.');
				}
			} catch ( Exception $e ) {
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, $e->getMessage(), 'error' );
				if ( 'my-wordpress-blog.local' != DOMAIN && 'localhost' != DOMAIN && '127.0.0.1' != DOMAIN ) {
					send_slack_refund_notification( 'Refund webhook processing error: \n ' . $e->getMessage() . ' \n Domain: <' . URL . '|' . DOMAIN . '> \n Platform: Woocommerce' );
				}
			}
		}

		/**
		 * Method for asynchronous processing PlanCreatedSucceeded
		 */
		public function splitit_payment_success_async() {
			$log_data = array(
				'user_id' => null,
				'method'  => __( 'splitit_payment_success_async() Splitit', 'splitit_ff_payment' ),
			);
			SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, 'Async hook PlanCreatedSucceeded arrived' );
			SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( json_encode( $_GET ) );

			try {
				$_GET = stripslashes_deep( $_GET );
				$ipn  = isset( $_GET['InstallmentPlanNumber'] ) ? wc_clean( $_GET['InstallmentPlanNumber'] ) : false;
				if ( ! SplitIt_FlexFields_Payment_Plugin_Log::check_exist_order_by_ipn( $ipn ) ) {
					$order_info = SplitIt_FlexFields_Payment_Plugin_Log::get_order_info_by_ipn( $ipn );

					$log_data['user_id'] = ( $order_info && $order_info->user_id ) ? $order_info->user_id : null;

					$api = new SplitIt_FlexFields_Payment_Plugin_API( $this->settings );

					$verify_data = $api->verify_payment( $ipn );

					if ( $verify_data->getIsAuthorized() ) {
						$checkout = new SplitIt_FlexFields_Payment_Plugin_Checkout();
						$order_id = $checkout->create_checkout( $order_info );
						$ipn_info = $api->get_ipn_info( $ipn );

						$order = wc_get_order( $order_id );

						if ( $order->get_payment_method() == 'splitit' ) {
							if ( ! $this->settings['splitit_auto_capture'] ) {
								$order->update_status( 'pending' );
							} else {
								$order->update_status( 'processing' );
							}
						}

						$data = array(
							'user_id'                 => $order_info->user_id,
							'order_id'                => $order_id,
							'installment_plan_number' => $ipn,
							'number_of_installments'  => count( $ipn_info->getInstallments() ),
							'processing'              => 'splitit_hook',
							'plan_create_succeed'     => 1,
						);

						// @Add record to transaction table
						SplitIt_FlexFields_Payment_Plugin_Log::transaction_log( $data );

						SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, 'Async hook placed order with Splitit' );

						$order_id_by_ipn      = SplitIt_FlexFields_Payment_Plugin_Log::get_order_id_by_ipn( $ipn );
						$std                  = new stdClass();
						$std->order_id        = null;
						$order_by_transaction = $order_by_transaction ?? $std;
						$order_id_in_method   = $order_id ?? $order_by_transaction->order_id;
						$api->update( $order_id_by_ipn->order_id ?? $order_id_in_method, $ipn );
					} else {
						$order_total_amount = $this->get_order_total_amount_from_order_info( $order_info, $ipn );



						$order_id_by_ipn      = SplitIt_FlexFields_Payment_Plugin_Log::get_order_id_by_ipn( $ipn );
						$std                  = new stdClass();
						$std->order_id        = null;
						$order_by_transaction = $order_by_transaction ?? $std;
						$order_id_in_method   = $order_id ?? $order_by_transaction->order_id;

						$order_id = $order_id_by_ipn->order_id ?? $order_id_in_method;

						$order                = wc_get_order( $order_id );

						if ( isset( $order ) && ! empty( $order ) ) {
							$order->update_status( 'cancelled' );
						}

						SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, 'Async hook canceled transaction' );

						SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, 'Splitit->verifyPaymentAPI() Returned an failed', 'error' );

						if ( SplitIt_FlexFields_Payment_Plugin_Log::check_exist_order_by_ipn( $ipn ) ) {
							SplitIt_FlexFields_Payment_Plugin_Log::update_transaction_log( array( 'installment_plan_number' => $ipn ) );
						}
					}
				} else {
					SplitIt_FlexFields_Payment_Plugin_Log::update_transaction_log( array( 'installment_plan_number' => $ipn ) );
					SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, 'Order already exists (' . $ipn . ')' );
				}
			} catch ( Exception $e ) {
				SplitIt_FlexFields_Payment_Plugin_Log::save_log_info( $log_data, $e->getMessage(), 'error' );
			}
		}

		/**
		 * Get order total amount
		 *
		 * @param $order_info
		 * @param $ipn
		 * @return float|int
		 */
		private function get_order_total_amount_from_order_info( $order_info, $ipn ) {
			$order_total_amount = 0;

			if ( ! $order_info ) {
				$order_by_transaction = SplitIt_FlexFields_Payment_Plugin_Log::select_from_transaction_log_by_ipn( $ipn );
				if ( $order_by_transaction && $order_by_transaction->order_id ) {
					$order              = wc_get_order( $order_by_transaction->order_id );
					$order_total_amount = $order->get_total() ?? 0;
				}
			} else {
				$order_total_amount = $order_info->set_total ?? 0;
			}

			return $order_total_amount;
		}

		/**
		 * Method for initiate custom WC API hooks
		 */
		public function init_custom_api_hooks() {
			add_action(
				'woocommerce_api_splitit_payment_success_async',
				array(
					$this,
					'splitit_payment_success_async',
				)
			);
			add_action(
				'woocommerce_api_splitit_refund_result_async',
				array(
					$this,
					'splitit_refund_result_async',
				)
			);
		}

		/**
		 * Method for Initiate Flex Fields (get token)
		 */
		public function flex_field_initiate_method() {
			wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );
			$total = $this->get_current_order_total();

			$api = new SplitIt_FlexFields_Payment_Plugin_API( $this->settings, self::DEFAULT_INSTALMENT_PLAN );

			$installments = $this->get_array_of_installments( (float) str_replace( ',', '', $total ) );

			$data = array();

			$post_data = array();
			$_POST     = stripslashes_deep( $_POST );
			if ( isset( $_POST ) ) {
				$post_data['action']               = isset( $_POST['action'] ) ? wc_clean( $_POST['action'] ) : null;
				$post_data['order_id']             = isset( $_POST['order_id'] ) ? wc_clean( $_POST['order_id'] ) : null;
				$post_data['ipn']                  = isset( $_POST['ipn'] ) ? wc_clean( $_POST['ipn'] ) : null;
				$post_data['numberOfInstallments'] = isset( $_POST['numberOfInstallments'] ) ? wc_clean( $_POST['numberOfInstallments'] ) : '';
				$post_data['currency_code']        = isset( $_POST['currency'] ) && ! empty( $_POST['currency'] ) ? wc_clean( $_POST['currency'] ) : get_woocommerce_currency();

				foreach ( $post_data as $key => $value ) {
					if ( isset( $value ) ) {
						$data[ $key ] = $value;
					}
				}
			}

			if ( isset( $data['order_id'] ) && ! empty( $data['order_id'] ) ) {
				$order = wc_get_order( $data['order_id'] );

				$order_data = $order->get_data();

				if ( 0 === (int) $total ) {
					$total = $order->get_total();
				}

				if ( isset( $order_data['billing'] ) ) {
					$data['billingAddress']['AddressLine']  = $order_data['billing']['address_1'];
					$data['billingAddress']['AddressLine2'] = $order_data['billing']['address_2'];
					$data['billingAddress']['City']         = $order_data['billing']['city'];
					$data['billingAddress']['State']        = $order_data['billing']['state'];
					$data['billingAddress']['Country']      = $order_data['billing']['country'];
					$data['billingAddress']['Zip']          = $order_data['billing']['postcode'];

					$data['consumerData']['FullName']    = $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'];
					$data['consumerData']['Email']       = $order_data['billing']['email'];
					$data['consumerData']['PhoneNumber'] = $order_data['billing']['phone'];
					$data['consumerData']['CultureName'] = str_replace( '_', '-', get_locale() );
				}
			}

			$data['amount']       = (float) str_replace( ',', '', $total );
			$data['installments'] = $installments;
			$data['culture']      = str_replace( '_', '-', get_locale() );

			echo $api->initiate( $data ); // @already json_encode, WPCS: XSS ok.
			wp_die();
		}

		/**
		 * Get current order total
		 *
		 * @return float
		 */
		private function get_current_order_total() {
			$_POST    = stripslashes_deep( $_POST );
			$order_id = isset( $_POST['order_id'] ) ? wc_clean( $_POST['order_id'] ) : null;

			$order = empty( $order_id ) ? null : wc_get_order( $order_id );
			WC()->cart->calculate_totals();

			if ( $order ) {
				$total = (float) $order->get_total();
			} else {
				$total = $this->get_order_total();
			}

			return custom_wc_price_value( $total );
		}

		/**
		 * Validation for order pay
		 */
		public function order_pay_validate() {
			if ( isset( $_POST ) ) {
				$_POST      = stripslashes_deep( $_POST );
				$errors     = array();
				$all_fields = isset( $_POST['fields'] ) ? wc_clean( $_POST['fields'] ) : null;
				if ( isset( $all_fields['terms-field'] ) && $all_fields['terms-field'] && ! isset( $all_fields['terms'] ) ) {
					$errors[] = '<li>' . __( 'You must accept our Terms &amp; Conditions.', 'woocommerce' ) . '</li>';
				}

				if ( ! is_ssl() ) {
					$errors[] = '<li>Please ensure your site supports SSL connection.</li>';
				}

				if ( is_array( $errors ) && count( $errors ) ) {
					$errors   = array_unique( $errors );
					$response = array(
						'result'   => 'failure',
						'messages' => implode( '', $errors ),
					);
				} else {
					$response = array(
						'result' => 'success',
					);
				}
			} else {
				$response = array(
					'result'   => 'failure',
					'messages' => 'No data has been sent from form',
				);
			}
			wp_send_json( $response );
		}

		/**
		 * Method for custom checkout validation
		 */
		public function checkout_validate() {
			if ( isset( $_POST ) ) {
				$_POST           = stripslashes_deep( $_POST );
				$errors          = array();
				$countries       = new WC_Countries();
				$billings_fields = $countries->get_address_fields( $countries->get_base_country(), 'billing_' );
				$shipping_fields = $countries->get_address_fields( $countries->get_base_country(), 'shipping_' );

				$wc_fields = array_merge( $billings_fields, $shipping_fields );

				$all_fields = wc_clean( $_POST['fields'] );

				if ( ! is_user_logged_in() && isset( $all_fields['createaccount'] ) && isset( $all_fields['billing_email'] ) && $all_fields['billing_email'] != '' ) {

					if ( email_exists( $all_fields['billing_email'] ) ) {
						$errors[] = '<li>' . __( 'An account is already registered with your email address. Please login.', 'woocommerce' ) . '</li>';
					}
				}

				if ( isset( $all_fields['terms-field'] ) && $all_fields['terms-field'] && ! isset( $all_fields['terms'] ) ) {
					$errors[] = '<li>' . __( 'You must accept our Terms &amp; Conditions.', 'woocommerce' ) . '</li>';
				}

				// @For check shipping
				if ( WC()->cart->needs_shipping() ) {
					$shipping_country = WC()->customer->get_shipping_country();

					if ( empty( $shipping_country ) ) {
						$errors[] = __( 'Please enter an address to continue.', 'woocommerce' );
					} elseif ( ! in_array( WC()->customer->get_shipping_country(), array_keys( WC()->countries->get_shipping_countries() ), true ) ) {
						$errors[] = sprintf( __( 'Unfortunately <strong>we do not ship %s</strong>. Please enter an alternative shipping address.', 'woocommerce' ), WC()->countries->shipping_to_prefix() . ' ' . WC()->customer->get_shipping_country() );
					} else {
						$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
						if ( ! $chosen_shipping_methods[0] ) {
							$errors[] = __( 'No shipping method has been selected. Please double check your address, or contact us if you need any help.', 'woocommerce' );
						}
						foreach ( WC()->shipping()->get_packages() as $i => $package ) {
							if ( ! isset( $chosen_shipping_methods[ $i ], $package['rates'][ $chosen_shipping_methods[ $i ] ] ) ) {
								$errors[] = __( 'No shipping method has been selected. Please double check your address, or contact us if you need any help.', 'woocommerce' );
							}
						}
					}
				}

				$ship_to_different_address = ( isset( $all_fields['ship_to_different_address'] ) && $all_fields['ship_to_different_address'] ) ? true : false;

				if ( ! $ship_to_different_address ) {
					$all_fields['shipping_first_name'] = $all_fields['billing_first_name'];
					$all_fields['shipping_last_name']  = $all_fields['billing_last_name'];
					$all_fields['shipping_company']    = $all_fields['billing_company'];
					$all_fields['shipping_email']      = $all_fields['billing_email'];
					$all_fields['shipping_phone']      = $all_fields['billing_phone'];
					$all_fields['shipping_country']    = $all_fields['billing_country'];
					$all_fields['shipping_address_1']  = $all_fields['billing_address_1'];
					$all_fields['shipping_address_2']  = $all_fields['billing_address_2'];
					$all_fields['shipping_city']       = $all_fields['billing_city'];
					$all_fields['shipping_state']      = $all_fields['billing_state'];
					$all_fields['shipping_postcode']   = $all_fields['billing_postcode'];
				}

				foreach ( $all_fields as $key => $value ) {
					switch ( $key ) {
						case 'billing_postcode':
							if ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] && ! WC_Validation::is_postcode( $value, $all_fields['billing_country'] ) ) {
								$errors[] = '<li><strong>' . __( 'Billing', 'woocommerce' ) . ' ' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'is not valid.', 'woocommerce' ) . '</li>';
							}
							break;

						case 'shipping_postcode':
							if ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] && ! WC_Validation::is_postcode( $value, $all_fields['shipping_country'] ) ) {
								$errors[] = '<li><strong>' . __( 'Shipping', 'woocommerce' ) . ' ' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'is not valid.', 'woocommerce' ) . '</li>';
							}
							break;

						case 'billing_phone':
						case 'shipping_phone':
							if ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] && ! WC_Validation::is_phone( $value ) ) {
								$errors[] = '<li><strong>' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'is not a valid phone number.', 'woocommerce' ) . '</li>';
							}
							if ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] && strlen( $value ) < 5 || strlen( $value ) > 14 ) {
								$errors[] = '<li><strong>' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'should be greater than 5 and less than 14 digits', 'woocommerce' ) . '</li>';
							}
							break;

						case 'billing_email':
						case 'shipping_email':
							if ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] && ! is_email( $value ) ) {
								$errors[] = '<li><strong>' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'is not a valid email address.', 'woocommerce' ) . '</li>';
							}
							break;

						case 'billing_address_1':
						case 'shipping_address_1':
							if ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] && ! isset( $value ) || strlen( trim( $value ) ) <= 0 ) {
								$errors[] = '<li><strong>' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'is a required field.', 'woocommerce' ) . '</li>';
							}
							break;

						case 'billing_state':
							$valid_states = WC()->countries->get_states( WC()->customer->get_billing_country() );
							if ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] ) {
								if ( ! empty( $valid_states ) && is_array( $valid_states ) && count( $valid_states ) > 0 ) {
									if ( ! in_array( $value, array_keys( $valid_states ) ) ) {
										$errors[] = '<li><strong>' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'is not valid. Please enter one of the following:', 'woocommerce' ) . ' ' . implode( ', ', $valid_states ) . '</li>';
									}
								}
							}
							break;

						case 'billing_first_name':
						case 'billing_last_name':
						case 'shipping_first_name':
						case 'shipping_last_name':
						case 'billing_city':
						case 'shipping_city':
						case 'billing_country':
						case 'shipping_country':
							if ( ( isset( $wc_fields[ $key ]['required'] ) && $wc_fields[ $key ]['required'] ) && empty( $value ) || strlen( trim( $value ) ) <= 0 ) {
								$errors[] = '<li><strong>' . $wc_fields[ $key ]['label'] . '</strong> ' . __( 'is a required field.', 'woocommerce' ) . '</li>';
							}
							break;
					}
				}

				if ( isset( $all_fields['billing_email'] ) ) {
					WC()->cart->check_customer_coupons( array( 'billing_email' => $all_fields['billing_email'] ) );
					$notices = wc_get_notices();
					if ( isset( $notices['error'] ) && ! empty( $notices['error'] ) ) {
						foreach ( $notices['error'] as $notice ) {
							$errors[] = '<li>' . __( $notice, 'woocommerce' ) . '</li>';
						}
					}
				}

				if ( ! is_ssl() ) {
					$errors[] = '<li>Please ensure your site supports SSL connection.</li>';
				}

				if ( is_array( $errors ) && count( $errors ) ) {
					$errors   = array_unique( $errors );
					$response = array(
						'result'   => 'failure',
						'messages' => implode( '', $errors ),
					);
				} else {
					$response = array(
						'result' => 'success',
					);
					$this->add_order_data_to_db( $_POST );
				}
			} else {
				$response = array(
					'result'   => 'failure',
					'messages' => 'No data has been sent from form',
				);
			}
			wp_send_json( $response );
		}

		/**
		 * Method for adding full data about the order
		 *
		 * @param $data
		 */
		public function add_order_data_to_db( $data ) {
			if ( isset( $data ) ) {
				global $woocommerce;
				$fetch_session_item   = WC()->session->get( 'chosen_shipping_methods' );
				$shipping_method_cost = WC()->cart->shipping_total;
				if ( ! empty( $fetch_session_item ) ) {
					$explode_items      = explode( ':', $fetch_session_item[0] );
					$shipping_method_id = $explode_items[0];
				} else {
					$shipping_method_id = '';
				}
				$shipping_method_title = '';
				$coupon_code           = '';
				$coupon_amount         = '';
				$applied_coupon_array  = $woocommerce->cart->get_applied_coupons();
				if ( ! empty( $applied_coupon_array ) ) {
					$discount_array = $woocommerce->cart->coupon_discount_amounts;
					foreach ( $discount_array as $key => $value ) {
						$coupon_code   = $key;
						$coupon_amount = wc_format_decimal( number_format( $discount_array[ $key ], 2 ) );
					}
				}

				$set_shipping_total = WC()->cart->shipping_total;
				$set_discount_total = WC()->cart->get_cart_discount_total();
				$set_discount_tax   = WC()->cart->get_cart_discount_tax_total();
				$set_cart_tax       = WC()->cart->tax_total;
				$set_shipping_tax   = WC()->cart->shipping_tax_total;
				$set_total          = WC()->cart->total;
				$wc_cart            = json_encode( WC()->cart );

				$get_packages                 = json_encode( WC()->shipping->get_packages() );
				$chosen_shipping_methods_data = json_encode( WC()->session->get( 'chosen_shipping_methods' ) );

				$total_tax_amount  = '';
				$total_taxes_array = WC()->cart->get_taxes();
				if ( ! empty( $total_taxes_array ) ) {
					$total_tax_amount = array_sum( $total_taxes_array );
					$total_tax_amount = wc_format_decimal( number_format( $total_tax_amount, 2 ) );
				}

				$insert_data = array(
					'ipn'                          => wc_clean( $data['ipn'] ),
					'user_id'                      => get_current_user_id(),
					'cart_items'                   => json_encode( WC()->cart->get_cart() ),
					'shipping_method_cost'         => $shipping_method_cost,
					'shipping_method_title'        => $shipping_method_title,
					'shipping_method_id'           => $shipping_method_id,
					'coupon_amount'                => $coupon_amount,
					'coupon_code'                  => $coupon_code,
					'tax_amount'                   => $total_tax_amount,
					'user_data'                    => wc_clean( $data['fields'] ),
					'set_shipping_total'           => $set_shipping_total,
					'set_discount_total'           => $set_discount_total,
					'set_discount_tax'             => $set_discount_tax,
					'set_cart_tax'                 => $set_cart_tax,
					'set_shipping_tax'             => $set_shipping_tax,
					'set_total'                    => $set_total,
					'wc_cart'                      => $wc_cart,
					'get_packages'                 => $get_packages,
					'chosen_shipping_methods_data' => $chosen_shipping_methods_data,
					'updated_at'                   => gmdate( 'Y-m-d H:i:s' ),
				);

				SplitIt_FlexFields_Payment_Plugin_Log::add_order_data( $insert_data );
			}
		}

		/**
		 * Output of the admin notices
		 */
		public function admin_notices() {
			$_COOKIE = stripslashes_deep( $_COOKIE );
			if ( ! empty( $_COOKIE['splitit'] ) ) {
				$message = wc_clean( $_COOKIE['splitit'] );
				setcookie( 'splitit', '', time() - 30 );
				echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
			}
		}

		/**
		 * Method initiate admin ssl notice
		 */
		public function init_admin_notice() {
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'admin_notices', array( $this, 'do_ssl_check' ) );
		}

		/**
		 * Disable SplitIt Based on Cart Total - WooCommerce
		 */
		public function init_disable_of_the_payment() {
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'disable_splitit' ) );
		}

		/**
		 * Disable splitit
		 *
		 * @param $available_gateways
		 *
		 * @return mixed
		 */
		public function disable_splitit( $available_gateways ) {
			if ( ! is_admin() && ! $this->is_allowed_payment() ) {
				global $plguin_id;
				unset( $available_gateways[ $plguin_id ] );
			}

			return $available_gateways;
		}

		/**
		 * Add IPN and Number of installment to the 'Thank you' page
		 */
		public function init_ipn_to_the_thank_you_page() {
			add_filter(
				'woocommerce_thankyou_order_received_text',
				array(
					$this,
					'splitit_add_installment_plan_number_data_thank_you_title',
				),
				10,
				2
			);
		}

	}

	/**
	 * Singleton
	 *
	 * @return WC_SplitIt_FlexFields_Payment_Gateway|null
	 */
	function splitit_flexfields_payment() {
		return WC_SplitIt_FlexFields_Payment_Gateway::get_instance();
	}

	splitit_flexfields_payment();
	splitit_flexfields_payment()->init_custom_api_hooks();

	if ( is_admin() ) {
		splitit_flexfields_payment()->init_admin_styles_and_scripts();
		splitit_flexfields_payment()->init_admin_notice();
	} else {
		splitit_flexfields_payment()->init_flex_fields_styles_and_scripts();
		splitit_flexfields_payment()->init_upstream_messaging_styles_and_scripts();
		splitit_flexfields_payment()->init_custom_flex_fields_styles();
		splitit_flexfields_payment()->init_footer_credit_cards();
		splitit_flexfields_payment()->init_home_page_banner();
		splitit_flexfields_payment()->init_shop_page();
		splitit_flexfields_payment()->init_product_page();
		splitit_flexfields_payment()->init_cart_page();
		splitit_flexfields_payment()->init_checkout_page_after_cart_totals();
		splitit_flexfields_payment()->init_client_styles_and_scripts();
		splitit_flexfields_payment()->init_disable_of_the_payment();
		splitit_flexfields_payment()->init_ipn_to_the_thank_you_page();
		splitit_flexfields_payment()->init_styles_and_scripts();
	}

	add_filter( 'woocommerce_order_button_html', 'splitit_flexfields_payment_plugin_custom_order_button_html' );

	/**
	 *  Adding custom payment button to the checkout page
	 *
	 * @param string $button Button html.
	 * @return string
	 */
	function splitit_flexfields_payment_plugin_custom_order_button_html( $button ) {
		return '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" onclick="performPayment(this)" value="Place order" data-value="Place order"></button>';
	}

	add_action( 'woocommerce_after_order_notes', 'splitit_flexfields_payment_plugin_add_custom_checkout_hidden_field' );

	/**
	 * Outputting the hidden field in checkout page
	 *
	 * @param $checkout
	 */
	function splitit_flexfields_payment_plugin_add_custom_checkout_hidden_field( $checkout ) {
		// @Output the hidden field
		echo '<div id="flex_field_hidden_checkout_field">
                <input type="hidden" class="input-hidden" name="flex_field_ipn" id="flex_field_ipn" value="">
                <input type="hidden" class="input-hidden" name="flex_field_num_of_inst" id="flex_field_num_of_inst" value="">
            </div>';
	}

	add_action( 'woocommerce_order_status_changed', 'splitit_flexfields_payment_plugin_grab_order_old_status', 10, 4 );

	/**
	 * Added post meta with _old_status
	 *
	 * @param $order_id
	 * @param $status_from
	 * @param $status_to
	 * @param $order
	 */
	function splitit_flexfields_payment_plugin_grab_order_old_status( $order_id, $status_from, $status_to, $order ) {
		if ( $order->get_meta( '_old_status' ) ) {
			// @Grab order status before it's updated
			update_post_meta( $order_id, '_old_status', $status_from );
		} else {
			// @Starting status in Woocommerce (empty history)
			update_post_meta( $order_id, '_old_status', 'processing' );
		}
	}

	add_action( 'wp_head', 'splitit_flexfields_payment_plugin_custom_checkout_script' );

	/**
	 * Include custom script to the checkout page
	 */
	function splitit_flexfields_payment_plugin_custom_checkout_script() {
		if ( is_checkout() == true ) {
			echo '<script>var flexFieldsInstance; localStorage.removeItem("ipn"); </script>';
		}
	}

	/**
	 * Custom WC price value
	 *
	 * @param $price
	 * @param array $args
	 *
	 * @return float
	 */
	function custom_wc_price_value( $price, $args = array() ) {
		if ( is_float( $price ) ) {
			return $price;
		}

		$args = apply_filters(
			'wc_price_args',
			wp_parse_args(
				$args,
				array(
					'ex_tax_label'       => false,
					'currency'           => '',
					'decimal_separator'  => wc_get_price_decimal_separator(),
					'thousand_separator' => wc_get_price_thousand_separator(),
					'decimals'           => wc_get_price_decimals(),
					'price_format'       => get_woocommerce_price_format(),
				)
			)
		);

		$original_price = $price;

		// Convert to float to avoid issues on PHP 8.
		$price = (float) $price;

		$unformatted_price = $price;
		$negative          = $price < 0;

		/**
		 * Filter raw price.
		 *
		 * @param float        $raw_price      Raw price.
		 * @param float|string $original_price Original price as float, or empty string. Since 5.0.0.
		 */
		$price = apply_filters( 'raw_woocommerce_price', $negative ? $price * -1 : $price, $original_price );

		/**
		 * Filter formatted price.
		 *
		 * @param float        $formatted_price    Formatted price.
		 * @param float        $price              Unformatted price.
		 * @param int          $decimals           Number of decimals.
		 * @param string       $decimal_separator  Decimal separator.
		 * @param string       $thousand_separator Thousand separator.
		 * @param float|string $original_price     Original price as float, or empty string. Since 5.0.0.
		 */
		$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'], $original_price );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$return = $price;

		/**
		 * Filters the string of price markup.
		 *
		 * @param string       $return            Price HTML markup.
		 * @param string       $price             Formatted price.
		 * @param array        $args              Pass on the args.
		 * @param float        $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
		 * @param float|string $original_price    Original price as float, or empty string. Since 5.0.0.
		 */
		return apply_filters( 'wc_price', $return, $price, $args, $unformatted_price, $original_price );
	}
}
