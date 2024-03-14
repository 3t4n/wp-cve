<?php
/**
 * Plugin Name: SIBS - WooCommerce
 * Plugin URI:  http://www.sibs.pt/
 * Description: WooCommerce with SIBS payment gateway
 * Author:      SIBS
 * Author URI:  http://www.sibs.pt/
 * Version:     2.2.0
 *
 * @package     Sibs/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

require_once dirname( __FILE__ ) . '/sibs-install.php';
require_once dirname( __FILE__ ) . '/sibs-additional.php';
register_activation_hook( __FILE__, 'sibs_activation_process' );
register_deactivation_hook( __FILE__, 'sibs_uninstallation_process' );
add_action( 'plugins_loaded', 'sibs_init_payment_gateway', 0 );

//ob_start();

define( 'SIBS_VERSION', '2.2.0' );
define( 'SIBS_PLUGIN_FILE', __FILE__ );


function sibs_get_notice_woocommerce_activation() {

	echo '<div id="notice" class="error"><p>';
	echo '<a href="http://www.woothemes.com/woocommerce/" style="text-decoration:none" target="_new">WooCommerce </a>' . esc_attr( __( 'BACKEND_GENERAL_PLUGINREQ', 'wc-sibs' ) ) . '<b> SIBS Payment Gateway for WooCommerce</b>';
	echo '</p></div>';
}

function sibs_add_configuration_links( $links ) {
	$add_links = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=sibs_settings' ) . '">' . __( 'BACKEND_CH_GENERAL', 'wc-sibs' ) . '</a>' );
	return array_merge( $add_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sibs_add_configuration_links' );


function validate_sibs_term() {
	if ( ! get_option( 'sibs_term' ) ) {
		echo '<div id="notice" class="updated"><p>';
		echo esc_attr( Sibs_General_Functions::sibs_translate_sibs_term( 'SIBS_TT_VERSIONTRACKER' ) );
		echo ' <a href="' . esc_attr( admin_url( 'admin.php?page=wc-settings&tab=sibs_settings' ) ) . '">' . esc_attr( Sibs_General_Functions::sibs_translate_sibs_term( 'SIBS_BACKEND_BT_ADMIN' ) ) . '</a>';
		echo '</p></div>';
		add_option( 'sibs_term', true );
	}
}



/**
 * Added for thankyou order text
 */
add_filter( 'woocommerce_thankyou_order_received_text', 'sibs_wpb_thankyou', 10, 2 );

function sibs_wpb_thankyou( $thankyoutext, $order ) {

	$added_text = '';
	$isSibsOrder = false;
	$ID = $order->get_id();

	//getting order Object 
	$orderObject = wc_get_order( $ID ); 

	//Search if this order is a SIBS order
	$transaction_log = Sibs_General_Models::sibs_get_db_transaction_log( $ID );
	$payment_id = $transaction_log['payment_id'];
	if ( $isSibsOrder !== strpos( $payment_id, 'sibs' ) ) {
		$isSibsOrder = true;
	}

	if ( $isSibsOrder ) {

		//get order gateway
		$payment_gateways = $orderObject->get_payment_method();

		if ( $payment_gateways == 'sibs_mbway' ){
			$added_text = __( '<p> Complete the payment in your MB WAY app. </p> <p> You have 4 minutes to complete the payment in your app.</p>', 'wc-sibs' );
		} else if ( $payment_gateways == 'sibs_multibanco' ){
			$added_text = __( '<p> Will be processed after multibanco payment completion. </p>', 'wc-sibs' );
		}
	}

	$finalText = $thankyoutext . $added_text;

	return $finalText ;
}


function sibs_init_payment_gateway() {

	/**
	 * Loads the Sibs language translation strings
	 */
	load_plugin_textdomain( 'wc-sibs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		add_action( 'admin_notices', 'sibs_get_notice_woocommerce_activation' );
		return;
	} else {
		add_action( 'admin_notices', 'validate_sibs_term' );
	}

	include_once dirname( __FILE__ ) . '/includes/class-general-functions.php';
	include_once dirname( __FILE__ ) . '/includes/class-general-models.php';
	include_once dirname( __FILE__ ) . '/includes/admin/class-sibs-general-settings.php';
	include_once dirname( __FILE__ ) . '/includes/admin/class-sibs-backend-settings.php';
	include_once dirname( __FILE__ ) . '/includes/core/class-sibspaymentcore.php';
	include_once dirname( __FILE__ ) . '/includes/core/class-sibserrorhandler.php';
	include_once dirname( __FILE__ ) . '/includes/core/class-sibsversiontracker.php';

	if ( ! class_exists( 'Sibs_Payment_Gateway' ) ) {

		
		class Sibs_Payment_Gateway extends WC_Payment_Gateway {
			
			protected $payment_id;
		
			protected $payment_type;
		
			protected $payment_brand;
			
			protected $language;
			
			protected $payment_group;
			
			protected $payment_display = 'form';
			
			private static $saved_meta_boxes = false;
			
			private static $added_meta_boxes = false;
			
			private static $added_payment_method = false;
			
			private static $updated_meta_boxes = false;
			
			protected $wc_order;

			
			public function __construct() {
				$this->payment_id    = $this->id;
				$payment_gateway     = Sibs_General_Functions::sibs_get_payment_gateway_variable( $this->payment_id );
				$this->payment_type  = $payment_gateway['payment_type'];
				$this->payment_brand = $payment_gateway['payment_brand'];
				$this->payment_group = $payment_gateway['payment_group'];
				$this->language      = $payment_gateway['language'];
				$this->plugins_url   = Sibs_General_Functions::sibs_get_plugin_url();

				$this->form_fields  = Sibs_Backend_Settings::sibs_create_backend_payment_settings( $this->payment_id );
				$this->method_title = Sibs_Backend_Settings::sibs_backend_payment_title( $this->payment_id );
				$this->method_description = Sibs_Backend_Settings::sibs_backend_payment_desc( $this->payment_id );
				
				$this->init_settings();

				// Save admin configuration from woocomerce checkout tab.
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				// Frontend hook.
				add_action( 'woocommerce_receipt_' . $this->payment_id, array( &$this, 'sibs_receipt_page' ) );

				add_action( 'woocommerce_thankyou_' . $this->payment_id, array( &$this, 'thankyou_page' ) );
				// Backend hook.
				add_action( 'woocommerce_process_shop_order_meta', array( &$this, 'sibs_save_order_meta' ) );
				add_action( 'woocommerce_admin_order_data_after_order_details', array( &$this, 'sibs_update_order_status' ) );
				add_action( 'woocommerce_admin_order_data_after_billing_address', array( &$this, 'add_payment_method' ) );
				add_action( 'woocommerce_admin_order_data_after_shipping_address', array( &$this, 'sibs_add_additional_information' ) );

				// Added for Capture
				
				/**
				 * hook to add the ajax callback
				 */
                global $action_addded_my_function_to_add_the_product;
                if (!$action_addded_my_function_to_add_the_product) {
                    add_action( 'wp_ajax_add_my_product_to_order', array( &$this,'sibs_my_function_to_add_the_product') );
                    $action_addded_my_function_to_add_the_product = true;
                }

				//custom email  
				add_action( 'woocommerce_email_order_meta', array( &$this, 'sibs_add_multibanco_data_to_order_email'), 10, 4 );

				// Add a custom metabox only for shop_order post type (order edit pages)
				add_action( 'add_meta_boxes', array( &$this, 'sibs_add_meta_boxesws' ) );
				
				//WebHook
				add_action( 'rest_api_init', function(){
					register_rest_route(
						'sibs-api/v1',
						'/callback_hook',
						array(
							'methods'  => 'POST',
							'callback' => array( &$this,'callback_hook'),
							)
					);
				} );


				// Enable woocommerce refund for {payment gateway}.
				$this->supports = array( 'refunds' );

				if ( isset( WC()->session->sibs_thankyou_page ) ) {
					unset( WC()->session->sibs_thankyou_page );
				}
				if ( isset( WC()->session->pmtRef ) ) {
					unset( WC()->session->pmtRef );
				}

				if ( isset( WC()->session->ptmntEntty ) ) {
					unset( WC()->session->ptmntEntty );
				}

				if ( isset( WC()->session->refIntlDtTm ) ) {
					unset( WC()->session->refIntlDtTm );
				}

				if ( isset( WC()->session->RefLmtDtTm ) ) {
					unset( WC()->session->RefLmtDtTm );
				}

				if ( isset( WC()->session->sibs_receipt_page ) ) {
					unset( WC()->session->sibs_receipt_page );
				}
				if ( isset( WC()->session->sibs_confirmation_page ) ) {
					unset( WC()->session->sibs_confirmation_page );
				}

			}

			/**
			 * Get Payment Logo(s)
			 * ( extended at Sibs payment gateways class )
			 *
			 * @return boolean
			 */
			protected function sibs_get_payment_logo() {

				return false;
			}

			
			public function is_available() {

				$is_available = parent::is_available();

				if ( $is_available ) {
					$recurring = 0;
					switch ( $this->payment_id ) {
						case 'sibs_cc':
						case 'sibs_dd':
						case 'sibs_paypal':
							if ( $recurring && 0 < get_current_user_id() ) {
								$is_available = false;
							}
							break;
						case 'sibs_ccsaved':
						case 'sibs_ddsaved':
						case 'sibs_paypalsaved':
							if ( ! $recurring || 0 === get_current_user_id() ) {
								$is_available = false;
							}
							break;
						default:
							$is_available = true;
							break;
					}
				}
				return $is_available;
			}

			/**
			 * Check if current user login is admin
			 *
			 * @return boolean
			 */
			private function sibs_is_site_admin() {
				return in_array( 'administrator', wp_get_current_user()->roles, true );
			}

			/**
			 * Get wc order property value
			 *
			 * @param  string $property property of class WC_Order.
			 * @return mixed
			 */
			protected function sibs_get_wc_order_property_value( $property ) {
				if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
					$function = 'get_' . $property;
					return $this->wc_order->$function();
				}
				return $this->wc_order->$property;
			}

			/**
			 * Get wc checkout url
			 *
			 * @return string
			 */
			protected function sibs_get_wc_checkout_url() {
				if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
					return wc_get_checkout_url();
				} else {
					return WC()->cart->get_checkout_url();
				}
			}

		
			protected function sibs_get_wc_customer_note($order) {
				if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
					return $order->get_customer_note();
				} else {
					return $order->customer_note;
				}
			}


			protected function sibs_get_wc_billing_bod($order) {
				if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
					$order_data = $order->get_data();
					return $order_data['meta_data'][0]->get_data()['value'];
				} else {
					return $order->billing_bod;
				}
			}

			public function get_title() {
				if ( $this->sibs_is_site_admin() ) {
					return Sibs_General_Functions::sibs_translate_backend_payment( $this->payment_id );
				}
				return Sibs_General_Functions::sibs_translate_backend_payment( $this->payment_id );
			}

			public function get_icon() {
				$title     = Sibs_General_Functions::sibs_translate_frontend_payment( $this->payment_id );
				$icon_html = '<img src="' . $this->sibs_get_payment_logo() . '" alt="' . $title . '" title="' . $title . '" style="height:40px; max-height:40px; margin:5px 10px 5px 0; float: none; vertical-align: middle; display: inline;" />';

				if ( 'sibs_mbway'  === $this->payment_id ) {

                    $payment_setting = get_option('woocommerce_' . $this->payment_id . '_settings');
                    $payment_desc = $payment_setting['payment_desc'];

                    $icon_html.= '<p>'.$payment_desc.'</p>';
                }

                if ( 'sibs_multibanco'  === $this->payment_id ) {

                    $payment_setting = get_option('woocommerce_' . $this->payment_id . '_settings');
                    $payment_desc = $payment_setting['payment_desc'];

                    $icon_html.= '<p>'.$payment_desc.'</p>';
                }

				if ( 'sibs_easycredit' === $this->payment_id ) {
					$total_amount = $this->get_order_total();
					$customer     = WC()->session->get( 'customer' );
					$billing_bod  = $customer['billing_bod'];
					$valid_dob    = Sibs_General_Functions::sibs_validate_dob( $billing_bod );
					$gender       = $customer['billing_gender'];

					$error_message = '';
					if ( ! get_option( 'sibs_general_dob_gender' ) || ! $valid_dob ) {
						$error_message .= '<br />' . __( 'ERROR_EASYCREDIT_PARAMETER_DOB', 'wc-sibs' );
					}
					if ( ! get_option( 'sibs_general_dob_gender' ) || ! $gender ) {
						$error_message .= '<br />' . __( 'ERROR_MESSAGE_EASYCREDIT_PARAMETER_GENDER', 'wc-sibs' );
					}
					if ( $total_amount < 200 || $total_amount > 3000 || 'EUR' !== get_woocommerce_currency() ) {
						$error_message .= '<br />' . __( 'ERROR_MESSAGE_EASYCREDIT_AMOUNT_NOTALLOWED', 'wc-sibs' );
					}
					if ( strtotime( $valid_dob ) >= strtotime( date( 'd-m-Y' ) ) ) {
						$error_message .= '<br />' . __( 'ERROR_EASYCREDIT_FUTURE_DOB', 'wc-sibs' );
					}
					if ( ! Sibs_General_Functions::sibs_is_address_billing_equal_shipping() ) {
						$error_message .= '<br />' . __( 'ERROR_EASYCREDIT_BILLING_NOTEQUAL_SHIPPING', 'wc-sibs' );
					}
					$key = $this->payment_id;
					if ( $error_message ) {
						$icon_html .= $error_message;
						$icon_html .=
							'<script>
							document.getElementById("payment_method_' . $key . '").disabled = true;
							document.getElementById("payment_method_' . $key . '").checked = false;
							var payment_method_easycredit_class = document.getElementsByClassName("payment_method_' . $key . '")[0];
							if (payment_method_easycredit_class.style.opacity !== undefined) {
								payment_method_easycredit_class.style.opacity = "0.7";
							} else {
								payment_method_easycredit_class.style.filter = "alpha(opacity=70)";
							}
							</script>';
					}
				}// End if().

				if ( 'sibs_multibanco' === $this->payment_id ) {
					$error_message = '';
					//enable check billing country
					//if ( ! Sibs_General_Functions::sibs_is_billing_country_portugal() ) {
					//	$error_message .= '<br />' . __( 'ERROR_MULTIBANCO_BILLING_COUNTRY_NOT_PORTUGAL', 'wc-sibs' );
					//}
					$key = $this->payment_id;
					if ( $error_message ) {
						$icon_html .= $error_message;
						$icon_html .=
							'<script>
							document.getElementById("payment_method_' . $key . '").disabled = true;
							document.getElementById("payment_method_' . $key . '").checked = false;
							var payment_method_multibanco_class = document.getElementsByClassName("payment_method_' . $key . '")[0];
							if (payment_method_multibanco_class.style.opacity !== undefined) {
								payment_method_multibanco_class.style.opacity = "0.7";
							} else {
								payment_method_multibanco_class.style.filter = "alpha(opacity=70)";
							}
							</script>';
					}
				}// End if().

				return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
			}

		
			public function sibs_get_multi_icon() {
				$icon_html = '';

				$payment_setting = get_option( 'woocommerce_' . $this->payment_id . '_settings' );
				$cards           = $payment_setting['card_types'];
				if ( isset( $cards ) && '' !== $cards ) {
					foreach ( $cards as $card ) {
						$icon       = $this->plugins_url . '/assets/images/' . strtolower( $card ) . '.png';
						$icon_html .= '<img src="' . $icon . '" alt="' . strtolower( $card ) . '" title="' . strtolower( $card ) . '" style="height:40px; max-height:40px; margin:5px 10px 5px 0; float: none; vertical-align: middle; display: inline;" />';
					}
                    $payment_desc = $payment_setting['payment_desc'];
                    $icon_html .= '<p>'.$payment_desc.'</p>';
				}

				return $icon_html;
			}

			
			public function process_payment( $order_id ) {
				$this->wc_order = new WC_Order( $order_id );
				return array(
					'result'   => 'success',
					'redirect' => $this->wc_order->get_checkout_payment_url( true ),
				);
			}

	
			public function sibs_receipt_page( $order_id ) {
				$paypal_repeated   = Sibs_General_Functions::sibs_get_request_value( 'paypal_repeated' );
				$accept_payment    = Sibs_General_Functions::sibs_get_request_value( 'accept_payment' );
				$confirmation_page = Sibs_General_Functions::sibs_get_request_value( 'confirmation_page' );
				if ( Sibs_General_Functions::sibs_get_request_value( 'id' ) && empty( $paypal_repeated ) && empty( $accept_payment ) &&
					empty( $confirmation_page ) ) {
					$this->sibs_response_page( $order_id );
				} elseif ( ! empty( $paypal_repeated ) ) {
					$this->sibs_debit_paypal_recurring( $order_id );
				} elseif ( ! empty( $accept_payment ) ) {
					$this->sibs_accept_payment( $order_id );
				}

				
				//CHANGE: MULTIBANCO FROM REDIRECT TO SERVER_TO_SERVER
				
				$order = wc_get_order( $order_id ); 
				$payment_gateway_used = $order->get_payment_method();
				if ( $payment_gateway_used == 'sibs_multibanco' ){

					$this->sibs_multibanco_server_to_server_payment( $order_id );
					WC()->session->set( 'sibs_confirmation_page', true );

				}


				if ( Sibs_General_Functions::sibs_get_request_value( 'confirmation_page' ) && ! isset( WC()->session->sibs_confirmation_page ) ) {
					$this->sibs_confirmation_page( $order_id );
					WC()->session->set( 'sibs_confirmation_page', true );
				} elseif ( ! Sibs_General_Functions::sibs_get_request_value( 'confirmation_page' ) && ! isset( WC()->session->sibs_receipt_page ) ) {
					$this->sibs_set_payment_form( $order_id );
					WC()->session->set( 'sibs_receipt_page', true );
				}
			}

			//CHANGE: Multibanco Change to Server to Server Payment
			private function sibs_multibanco_server_to_server_payment( $order_id) {
				global $wp;
				$is_one_click_payments = false;


				if( get_option( 'sibs_general_dob_gender' ) ) {
					$this->wc_order = new WC_Order( $order_id );
					$valid_dob      = Sibs_General_Functions::sibs_validate_dob( $this->sibs_get_wc_billing_bod($this->wc_order) );
					if ( ! get_option( 'sibs_general_dob_gender' ) || ! $valid_dob ) {
						$this->sibs_do_error_payment( $order_id, 'wc-failed', 'ERROR_WRONG_DOB' );
					}
				}

				//PREPARE THE PAYMENT INFORMATION

				$payment_parameters = $this->sibs_set_payment_parameters( $order_id );

				//log
				$log = new WC_Logger();
				$log_entry = print_r( $payment_parameters, true );
				$log->add( 'woocommerce-sibs-log', 'SET PAYMENT PARAMETERS : ' . $log_entry );


				//GET THE SERVER TO SERVER RESPONSE
				
				$payment_response = SibsPaymentCore::sibs_get_server_to_server_response($payment_parameters);

				//log
				$log = new WC_Logger();
				$log_entry = print_r( $payment_response, true );
				$log->add( 'woocommerce-sibs-log', 'GET PAYMENT SERVER TO SERVER RESPONSE: ' . $log_entry );
				
				// CHECK IF VALID RESPONSE

				if ( ! $payment_response['is_valid'] ) {
					$this->sibs_do_error_payment( $order_id, 'wc-canceled', $payment_response['response'] );
				} elseif ( ! isset( $payment_response['response']['id'] ) ) {
					$this->sibs_do_error_payment( $order_id, 'wc-canceled', $payment_response['response'], 'ERROR_GENERAL_REDIRECT' );
				}

				//PARSE THE PAYMENT SUCCESS AND SAVE IN THE DB

				$payment_parameters_parsed = $payment_response['response'];
				$payment_parameters_parsed['payment_status'] = 'wc-pending';
				$this->sibs_save_transactions( $order_id, $payment_parameters_parsed, $payment_parameters_parsed['id'] );

				// REDUCE STOCK LEVELS

				if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
					wc_reduce_stock_levels( $order_id );
				} else {
					$this->wc_order->reduce_order_stock();
				}
				
				$order = wc_get_order($order_id);
				$order->update_status('wc-on-hold', 'order_note' );

				// EMPTY CART, REDIRECT & EXIT

				WC()->cart->empty_cart();
				wp_safe_redirect( $this->get_return_url( $this->wc_order ) );
				exit();

			}

		
			private function sibs_set_payment_form( $order_id ) {
				global $wp;
				$is_one_click_payments = false;

				if ( get_option( 'sibs_general_dob_gender' ) ) {
					$this->wc_order = new WC_Order( $order_id );
					$valid_dob      = Sibs_General_Functions::sibs_validate_dob( $this->sibs_get_wc_billing_bod($this->wc_order) );
					if ( ! get_option( 'sibs_general_dob_gender' ) || ! $valid_dob ) {
						$this->sibs_do_error_payment( $order_id, 'wc-failed', 'ERROR_WRONG_DOB' );
					}
				}

				$payment_parameters = $this->sibs_set_payment_parameters( $order_id );


				/////

				//added for card payment registration
				$order = wc_get_order( $order_id ); 
				$payment_gateway_used = $order->get_payment_method();
				if ( $payment_gateway_used == 'sibs_cc' ){
					$credentials         				 = Sibs_General_Functions::sibs_get_credentials( $this->payment_id );
					$payment_parameters['registrations'] = Sibs_General_Models::sibs_get_db_registered_payment( $this->payment_group, $credentials );
				}

				//////

				//log
				$log = new WC_Logger();
				$log_entry = print_r( $payment_parameters, true );
				$log->add( 'woocommerce-sibs-log', 'SET CHECKOUT PARAMETERS : ' . $log_entry );


				$checkout_result = SibsPaymentCore::sibs_get_checkout_result( $payment_parameters );

				//log
				$log = new WC_Logger();
				$log_entry = print_r( $checkout_result, true );
				$log->add( 'woocommerce-sibs-log', 'GET CHECKOUT RESULT : ' . $log_entry );


				if ( ! $checkout_result['is_valid'] ) {
					$this->sibs_do_error_payment( $order_id, 'wc-canceled', $checkout_result['response'] );
				} elseif ( ! isset( $checkout_result['response']['id'] ) ) {
					$this->sibs_do_error_payment( $order_id, 'wc-canceled', $checkout_result['response'], 'ERROR_GENERAL_REDIRECT' );
				}
				// TODO: Maybe check here if order was successful and CC

				$url_config['payment_widget'] = SibsPaymentCore::sibs_get_payment_widget_url( $payment_parameters, $checkout_result['response']['id'] );

				if ( isset( $wp->request ) ) {
					$url_config['return_url'] = home_url( $wp->request ) . '/?key=' . Sibs_General_Functions::sibs_get_request_value( 'key' );
				} else {
					$url_config['return_url'] = get_page_link() . '&order-pay=' . Sibs_General_Functions::sibs_get_request_value( 'order-pay' ) . '&key=' . Sibs_General_Functions::sibs_get_request_value( 'key' );
				}

				$payment_widget_content = SibsPaymentCore::sibs_get_payment_widget_content( $url_config['payment_widget'], $payment_parameters['server_mode'] );

				$url_config['cancel_url']  = $this->sibs_get_wc_checkout_url();
				$url_config['plugins_url'] = $this->plugins_url;

				if ( ! $payment_widget_content['is_valid'] || strpos( $payment_widget_content['response'], 'errorDetail' ) !== false ) {
					$this->sibs_do_error_payment( $order_id, 'wc-canceled', 'ERROR_GENERAL_REDIRECT' );
				}

				wp_enqueue_script( 'sibs_formpayment_script', $url_config['payment_widget'], array(), null );
				wp_enqueue_style( 'sibs_formpayment_style', $this->plugins_url . '/assets/css/formpayment.css', array(), null );

				if ( ! empty( $payment_parameters['registrations'] ) ) {
					$is_one_click_payments = true;
				}

				$payment_form = Sibs_General_Functions::sibs_get_payment_form( $this->payment_id );
				$args         = array(
					'url_config'            => $url_config,
					'payment_parameters'    => $payment_parameters,
					'plugins_url'           => $this->plugins_url,
					'is_one_click_payments' => $is_one_click_payments,
					'settings'              => $this->settings,

				);

				switch ( $payment_form ) {
					case 'redirect':
						Sibs_General_Functions::sibs_include_template( dirname( __FILE__ ) . '/templates/checkout/template-redirect-payment.php', $args );
						break;
					case 'servertoserver':
						Sibs_General_Functions::sibs_include_template( dirname( __FILE__ ) . '/templates/checkout/template-servertoserver-payment.php', $args );
						break;
					default:
						Sibs_General_Functions::sibs_include_template( dirname( __FILE__ ) . '/templates/checkout/template-payment-form.php', $args );
						break;
				}
			}

			
			private function sibs_response_page( $order_id ) {
				$is_testmode_available = false;
				$status_parameters     = Sibs_General_Functions::sibs_get_credentials( $this->payment_id, $is_testmode_available );
				$status_parameters['payment_brand'] = $this->payment_brand;
                $log = new WC_Logger();
                $log_entry = print_r( $status_parameters, true );
                $log->add( 'woocommerce-sibs-log', 'STATUS_PARAMETERS: ' . $log_entry );

				$id = Sibs_General_Functions::sibs_get_request_value( 'id' );
				$payment_result = SibsPaymentCore::sibs_get_payment_status( $id, $status_parameters );

				$log_entry = print_r( $payment_result, true );
				$log->add( 'woocommerce-sibs-log', 'PAYMENT_RESULT: ' . $log_entry );


				if ( ! $payment_result ) {
					$this->sibs_do_error_payment( $order_id, 'wc-failed', 'ERROR_GENERAL_NORESPONSE' );
				} else {
					if ( isset( $payment_result['amount'] ) ) {
						$payment_amount = $payment_result['amount'];
					}

					$result_status = SibsPaymentCore::sibs_get_transaction_result( $payment_result['result']['code'] );

					if ( 'ACK' === $result_status ) {

						$is_success_review = SibsPaymentCore::sibs_is_success_review( $payment_result['result']['code'] );
						
						//default behaviour
						$log->add('woocommerce-sibs-log', 'default behaviour');

						if ( $is_success_review ) {
							//MBWAY - PA DB
							$payment_result['payment_status'] = 'wc-on-hold';
							$log->add('woocommerce-sibs-log', 'default behaviour sucess review');

						} else {
							$log->add('woocommerce-sibs-log', 'default behaviour not sucess review');

							if ( $this->payment_id == 'sibs_cc' ) {
								if ( get_option( 'sibs_general_custom_status' )) {
									if ($payment_result['paymentType'] = 'DB') {
										$payment_result['payment_status'] = 'wc-completed';
									} else {
										$payment_result['payment_status'] = 'wc-processing';
									}
								} else {
									$payment_result['payment_status'] = 'wc-processing';
								}
							} else if ( $this->payment_id == 'sibs_mbway' ) {
								$payment_result['payment_status'] = 'wc-on-hold';
							} else if ( $this->payment_id == 'sibs_multibanco' ) {
								$payment_result['payment_status'] = 'wc-on-hold';
							}
						}

						$this->sibs_do_success_payment( $order_id, $payment_result );
						
					} else {
						$this->sibs_failed_response( $order_id, $payment_result, $result_status );
					}
				}
			}

			
			private function sibs_confirmation_page( $order_id ) {
				$is_testmode_available = false;
				$status_parameters     = Sibs_General_Functions::sibs_get_credentials( $this->payment_id, $is_testmode_available );

				$id = Sibs_General_Functions::sibs_get_request_value( 'id' );

				$payment_result = SibsPaymentCore::sibs_get_payment_server_to_server_status( $id, $status_parameters );
				$this->wc_order = new WC_Order( $order_id );

				if ( ! $payment_result ) {
					$this->sibs_do_error_payment( $order_id, 'wc-failed', 'ERROR_GENERAL_NORESPONSE' );
				} else {
					$result_status = SibsPaymentCore::sibs_get_transaction_result( $payment_result['result']['code'] );

					if ( 'ACK' === $result_status ) {
						WC()->session->set(
							'easycredit_result', array(
								$id => $payment_result,
							)
						);
						WC()->session->set( 'sum_of_interest', $payment_result['resultDetails']['ratenplan.zinsen.anfallendeZinsen'] );
						WC()->session->set( 'order_total', $payment_result['resultDetails']['ratenplan.gesamtsumme'] );
						WC()->session->set( 'tilgungsplan_text', $payment_result['resultDetails']['tilgungsplanText'] );
						WC()->session->set( 'vorvertragliche_link', $payment_result['resultDetails']['vorvertraglicheInformationen'] );

						add_action( 'woocommerce_thankyou_' . $this->payment_id, array( $this, 'sibs_get_woocommerce_thankyou_text' ) );
						add_filter( 'woocommerce_get_order_item_totals', array( $this, 'sibs_add_sumofinterest_ordertotal' ) );
						add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'sibs_get_woocommerce_thankyou_order_received_text' ) );
						add_action( 'woocommerce_thankyou', array( $this, 'sibs_get_woocommerce_thankyou' ) );
						add_filter( 'woocommerce_pay_order_button_text', array( $this, 'change_thankyou_to_confirmation' ) );

						$this->wc_order->update_status( 'wc-on-hold', 'order_note' );

						wc_get_template(
							'checkout/thankyou.php', array(
								'order' => $order_id,
							)
						);
					} else {
						$this->sibs_failed_response( $order_id, $payment_result, $result_status );
					}// End if().
				}// End if().
			}

			public function sibs_get_woocommerce_thankyou_text() {
				echo '<p>' . esc_attr( WC()->session->tilgungsplan_text ) . '</p>';
				echo "<p>
						<a href='" . esc_attr( WC()->session->vorvertragliche_link ) . "' target='_blank'>" . esc_attr( __( 'FRONTEND_EASYCREDIT_LINK', 'wc-sibs' ) ) . '</a>
					 </p>';
			}

			
			public function sibs_get_woocommerce_thankyou_order_received_text() {
				return __( 'GENERAL_TEXT_ORDER_REVIEW', 'wc-sibs' );
			}

			/**
			 * Get woocommerce thankyou
			 */
			public function sibs_get_woocommerce_thankyou() {
				global $wp;

				if ( isset( $wp->request ) ) {
					$accept_payment_link = home_url( $wp->request ) . '/?key=' . Sibs_General_Functions::sibs_get_request_value( 'key' ) . '&accept_payment=1&id=' .
					Sibs_General_Functions::sibs_get_request_value( 'id' );
				} else {
					$accept_payment_link = get_page_link() . '&order-pay=' .
					Sibs_General_Functions::sibs_get_request_value( 'order-pay' ) . '&key=' .
					Sibs_General_Functions::sibs_get_request_value( 'key' ) . '&accept_payment=1&id=' .
					Sibs_General_Functions::sibs_get_request_value( 'id' );
				}
				// Input hidden is for make the input have the shame padding according to css style.
				echo '<div id="payment" style="padding-top: 10px; border-top: 1px solid #aaaaaa">
						<input type="hidden"/>
						<input type="button" class="button alt" onclick="window.location = \'' . esc_attr( $this->sibs_get_wc_checkout_url() ) . '\'" value="' . esc_attr( __( 'Cancel', 'woocommerce' ) ) . '"/>
						<input type="submit" class="button alt"  id="place_order" onclick="window.location = \'' . esc_attr( $accept_payment_link ) . '\'" value="' . esc_attr( __( 'Place order', 'woocommerce' ) ) . '" /></div>';
				echo '<script>
						var title = document.getElementsByClassName("woocommerce-thankyou-order-received")[0].innerHTML;
						document.getElementsByClassName("entry-title")[0].innerHTML = title;
						var element = document.getElementsByClassName("order_details")[0];
						element.parentNode.removeChild(element);
					 </script>';
			}

			
			public function sibs_add_sumofinterest_ordertotal( $total_rows ) {
				$total_rows['sum_of_interest']        = array(
					'label' => __( 'FRONTEND_EASYCREDIT_INTEREST', 'wc-sibs' ),
					'value' => wc_price(
						WC()->session->sum_of_interest, array(
							'currency' => get_woocommerce_currency(),
						)
					),
				);
				$total_rows['easycredit_order_total'] = array(
					'label' => __( 'FRONTEND_EASYCREDIT_TOTAL', 'wc-sibs' ),
					'value' => wc_price(
						WC()->session->order_total, array(
							'currency' => get_woocommerce_currency(),
						)
					),
				);
				return $total_rows;
			}

			private function sibs_accept_payment( $order_id ) {
				$id = Sibs_General_Functions::sibs_get_request_value( 'id' );
				if ( ! isset( WC()->session->easycredit_result[ $id ] ) ) {
					$this->sibs_do_error_payment( $order_id, 'wc-failed', 'ERROR_GENERAL_CAPTURE_PAYMENT' );
				} else {
					$payment_result = WC()->session->easycredit_result[ $id ];
					unset( WC()->session->easycredit_result );
					$is_testmode_available     = true;
					$is_multichannel_available = true;

					$capture_parameter                 = Sibs_General_Functions::sibs_get_credentials( $this->payment_id, $is_testmode_available, $is_multichannel_available );
					$capture_parameter['amount']       = $payment_result['amount'];
					$capture_parameter['currency']     = $payment_result['currency'];
					$capture_parameter['payment_type'] = 'CP';

					$capture_result = SibsPaymentCore::sibs_back_office_operation( $id, $capture_parameter );
					if ( ! $capture_result ) {
						$this->sibs_do_error_payment( $order_id, 'wc-failed', 'ERROR_GENERAL_CAPTURE_PAYMENT' );
					} else {
						$capture_status = SibsPaymentCore::sibs_get_transaction_result( $capture_result['result']['code'] );
						if ( 'ACK' === $capture_status ) {
							$payment_result['payment_status'] = 'wc-payment-accepted';
							$this->sibs_do_success_payment( $order_id, $payment_result );
						} else {
							$this->sibs_failed_response( $order_id, $payment_result, $capture_status );
						}
					}
				}
			}

			
			private function sibs_do_success_payment( $order_id, $payment_result ) {

				$this->wc_order = new WC_Order( $order_id );

				$merchant_info = $this->sibs_get_merchant_info();

				SibsVersionTracker::sibs_send_version_tracker( $merchant_info );

				$reference_id = $payment_result['id'];
				$payment_brand = $payment_result['paymentBrand'];
				$this->sibs_save_transactions( $order_id, $payment_result, $reference_id );

				// Update Order Status.
                $this->wc_order->update_status( 'wc-pending', 'order_note' );
				$this->wc_order->update_status( $payment_result['payment_status'], 'order_note' );
				$order_awaiting_payment_session = WC()->session->order_awaiting_payment;
				// Empty awaiting payment session.
				if ( ! empty( $order_awaiting_payment_session ) ) {
					unset( WC()->session->order_awaiting_payment );
				}
				$log = new WC_Logger();
				$log_entry = print_r( $payment_brand , true );
				$log->add( 'woocommerce-sibs-log', 'stock Paymentbrand e orderID1 : ' . $log_entry );

				$log = new WC_Logger();
				$log_entry = print_r( $order_id , true);
				$log->add( 'woocommerce-sibs-log', 'stock Paymentbrand e orderID2 : ' . $log_entry );

				if (  $payment_brand != "MBWAY" &&  $payment_brand != "SIBS_MULTIBANCO"  ) {

					$log_entry = print_r( $payment_brand , true );
					$log->add( 'woocommerce-sibs-log', 'stock reduced ' . $log_entry );

					// Reduce stock levels.
					//if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
					//	wc_reduce_stock_levels( $order_id );
					//} else {
					//	$this->wc_order->reduce_order_stock();
					//}
				}

				// Remove cart.
				WC()->cart->empty_cart();
				wp_safe_redirect( $this->get_return_url( $this->wc_order ) );
				exit();
			}

			private function sibs_failed_response( $order_id, $payment_result, $result_status ) {
				if ( 'NOK' === $result_status ) {
					$error_identifier = SibsErrorHandler::sibs_get_error_identifier( $payment_result['result']['code'] );

					$log = new WC_Logger();
					$log_entry = print_r( $payment_result, true );
					$log->add( 'woocommerce-sibs-log', 'FAILED PAYMENT_RESULT: ' . $log_entry );

				} else {
					$error_identifier = 'ERROR_UNKNOWN';
				}

				$payment_result['payment_status'] = 'wc-failed';
				$this->sibs_save_transactions( $order_id, $payment_result, $payment_result['id'] );
				$this->sibs_do_error_payment( $order_id, $payment_result['payment_status'], $error_identifier );
			}

			/**
			 * Error payment action
			 *
			 * @param int    $order_id order id.
			 * @param string $payment_status payment status.
			 * @param string $error_identifier error identifier.
			 */
			private function sibs_do_error_payment( $order_id, $payment_status, $error_identifier ) {

				$this->wc_order = new WC_Order( $order_id );

				$error_translated = Sibs_General_Functions::sibs_translate_error_identifier( $error_identifier );
                $log = new WC_Logger();
                $log_entry = print_r( $error_translated, true );
                $log->add( 'woocommerce-sibs-log', 'PAYMENT ERROR: ' . $log_entry );

				// Cancel the order.
				$this->wc_order->cancel_order( $error_translated );
				$this->wc_order->update_status( $payment_status, 'order_note' );
				// To display failure messages from woocommerce session.
				$woocommerce->session->errors = $error_translated;
				wc_add_notice( $error_translated, 'error' );

				wp_safe_redirect( $this->sibs_get_wc_checkout_url() );
				exit();
			}

			private function sibs_get_debit_paypal_recurring_parameters( $order_id, $reg_id = false ) {
				$parameters                 = $this->sibs_set_payment_parameters( $order_id );
				$parameters['payment_type'] = $this->payment_type;
				unset( $parameters['payment_registration'] );

				if ( $reg_id ) {
					$parameters['payment_recurring'] = 'INITIAL';
				} else {
					$parameters['payment_recurring'] = 'REPEATED';
				}

				return $parameters;
			}

			private function sibs_debit_paypal_recurring( $order_id, $reg_id = false ) {
				$payment_parameters = $this->sibs_get_debit_paypal_recurring_parameters( $order_id, $reg_id );

				if ( $reg_id ) {
					$registration_id = $reg_id;
				} else {
					$registration_id = Sibs_General_Functions::sibs_get_request_value( 'registrationId', '' );
				}

				$paypal_result = SibsPaymentCore::sibs_use_registered_account( $registration_id, $payment_parameters );

				if ( ! $paypal_result ) {
					$this->sibs_do_error_payment( $order_id, 'wc-failed', 'ERROR_GENERAL_NORESPONSE' );
				} else {
					$paypal_status = SibsPaymentCore::sibs_get_transaction_result( $paypal_result['result']['code'] );
					if ( 'ACK' === $paypal_status ) {
						if ( $reg_id ) {
							return $paypal_result;
						} else {
							$paypal_result['payment_status'] = 'wc-payment-accepted';
							$this->sibs_do_success_payment( $order_id, $paypal_result );
						}
					} else {
						$this->sibs_failed_response( $order_id, $paypal_result, $paypal_status );
					}
				}
			}

			public function thankyou_page() {
				if ( ! isset( WC()->session->sibs_thankyou_page ) ) {
					WC()->session->set( 'sibs_thankyou_page', true );
				}
			}

			public function sibs_save_order_meta() {
				if ( ! self::$saved_meta_boxes ) {
					$original_post_status = Sibs_General_Functions::sibs_get_request_value( 'original_post_status', '' );
					$auto_draft           = Sibs_General_Functions::sibs_get_request_value( 'auto_draft', false );

					if ( 'auto-draft' === $original_post_status && '1' === $auto_draft ) {
						$this->sibs_save_backend_order();
					} else {
						$order_id        = Sibs_General_Functions::sibs_get_request_value( 'post_ID', '' );
						$is_sibs_payment = $this->sibs_is_sibs_by_order( $order_id );
						if ( $is_sibs_payment ) {
							$this->sibs_change_payment_status();
						}
					}
					self::$saved_meta_boxes = true;
				}
			}

			private function sibs_change_payment_status() {
				$order_id             = Sibs_General_Functions::sibs_get_request_value( 'post_ID', '' );
				$original_post_status = Sibs_General_Functions::sibs_get_request_value( 'original_post_status', '' );
				$order_post_status    = Sibs_General_Functions::sibs_get_request_value( 'order_status', '' );
				$transaction_log      = Sibs_General_Models::sibs_get_db_transaction_log( $order_id );

				if ( 'wc-in-review' === $original_post_status && 'wc-in-review' === $order_post_status ) {
					$this->sibs_update_payment_status( $order_id, $original_post_status );
				} elseif ( 'wc-in-review' === $original_post_status && ( 'wc-failed' === $order_post_status || 'wc-cancelled' === $order_post_status ) ) {
					$this->sibs_update_payment_status( $order_id, $original_post_status, $order_post_status );
				} elseif ( 'wc-pending' === $original_post_status && 'wc-payment-accepted' === $order_post_status && $transaction_log['payment_id'] !== 'sibs_multibanco' ) {
					$backoffice_config['payment_type']  = 'CP';
					$backoffice_config['order_status']  = $order_post_status;
					$backoffice_config['error_message'] = __( 'ERROR_GENERAL_CAPTURE_PAYMENT', 'wc-sibs' );
					$this->sibs_do_back_office_payment( $order_id, $backoffice_config );
				} elseif ( 'wc-pending' === $original_post_status && 'wc-cancelled' === $order_post_status ) {
					$backoffice_config['payment_type']  = 'RV';
					$backoffice_config['order_status']  = $order_post_status;
					$backoffice_config['error_message'] = __( 'ERROR_GENERAL_CANCEL', 'wc-sibs' );
					$this->sibs_do_back_office_payment( $order_id, $backoffice_config );
				} elseif ( 'wc-payment-accepted' === $original_post_status && 'wc-refunded' === $order_post_status ) {
					$this->wc_order = new WC_Order( $order_id );
					$this->sibs_increase_order_stock();
					$backoffice_config['payment_type']  = 'RF';
					$backoffice_config['order_status']  = $order_post_status;
					$backoffice_config['error_message'] = __( 'ERROR_GENERAL_REFUND_PAYMENT', 'wc-sibs' );
					$this->sibs_do_back_office_payment( $order_id, $backoffice_config );
				} elseif ( 'wc-payment-accepted' !== $original_post_status && 'wc-refunded' === $order_post_status ) {
					$redirect = get_admin_url() . 'post.php?post=' . $order_id . '&action=edit';
					wp_safe_redirect( $redirect );
					exit;
				}

			}

			public function sibs_is_sibs_by_order( $order_id ) {
				$transaction_log = Sibs_General_Models::sibs_get_db_transaction_log( $order_id );
				$payment_id      = $transaction_log['payment_id'];

				return $this->sibs_is_sibs_by_payment_id( $payment_id );
			}

			private function sibs_is_sibs_by_payment_id( $payment_method ) {
				if ( false !== strpos( $payment_method, 'sibs' ) ) {
					return true;
				}
				return false;
			}

			private function sibs_update_payment_status( $order_id, $original_post_status = false, $order_post_status = false ) {
				$transaction_log = Sibs_General_Models::sibs_get_db_transaction_log( $order_id );
						if ( ! $original_post_status ) {
					$original_post_status = $transaction_log['payment_status'];
				}

				$backoffice_parameter = Sibs_General_Functions::sibs_get_credentials( $transaction_log['payment_id'] );

				$backoffice_result = SibsPaymentCore::sibs_update_status( $transaction_log['reference_id'], $backoffice_parameter );
				$backoffice_status = SibsPaymentCore::sibs_get_transaction_result( $backoffice_result['result']['code'] );

				if ( 'ACK' === $backoffice_status && ! SibsPaymentCore::sibs_is_success_review( $backoffice_result['result']['code'] ) ) {
					if ( 'PA' === $backoffice_result['paymentType'] ) {
						$payment_status = 'wc-on-hold';
					} elseif ( 'DB' === $backoffice_result['paymentType'] ) {
						$payment_status = 'wc-payment-accepted';
					}

					Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, $payment_status );
					Sibs_General_Models::sibs_update_db_posts_status( $order_id, $payment_status );
					if ( $original_post_status !== $payment_status ) {
						if ( $order_post_status && ( 'wc-failed' === $order_post_status || 'wc-cancelled' === $order_post_status ) ) {
							$this->wc_order = new WC_Order( $order_id );
							$this->sibs_increase_order_stock();
						}
						$wc_order_status         = wc_get_order_statuses();
						$status_name['original'] = $wc_order_status[ $original_post_status ];
						$status_name['new']      = $wc_order_status[ $payment_status ];
						$this->sibs_add_order_notes( $order_id, $status_name );
					}
					return true;
				} else {
					return false;
				}
			}

			private function sibs_save_backend_order() {
				$order['order_id'] = Sibs_General_Functions::sibs_get_request_value( 'post_ID', '' );
				$reg_id            = Sibs_General_Functions::sibs_get_request_value( '_payment_recurring', '' );
				$payment_method    = Sibs_General_Functions::sibs_get_request_value( '_payment_method', '' );

				$is_sibs_payment = $this->sibs_is_sibs_by_payment_id( $payment_method );

				if ( $is_sibs_payment ) {
					$registered_payment     = Sibs_General_Models::sibs_get_db_registered_payment_by_regid( $reg_id );
					$order['payment_id']    = Sibs_General_Functions::sibs_get_payment_id_by_group( $registered_payment['payment_group'] );
					$order['user_id']       = $registered_payment['cust_id'];
					$order['payment_brand'] = $registered_payment['brand'];

					$order_parameter = $this->sibs_get_backend_order_parameters( $order );
					$order_result    = SibsPaymentCore::sibs_use_registered_account( $reg_id, $order_parameter );
					$order_status    = SibsPaymentCore::sibs_get_transaction_result( $order_result['result']['code'] );

					if ( 'ACK' === $order_status ) {
						$order_result['payment_status'] = $this->sibs_get_success_payment_status( $order['payment_id'], $order_result['result']['code'] );

						$this->sibs_do_success_backend_order( $order, $order_result, $order_parameter );
					} else {
						$order_result['payment_status'] = 'wc-failed';
						Sibs_General_Models::sibs_update_db_posts_status( $order['order_id'], $order_result['payment_status'] );
					}

					$_POST['order_status']    = $order_result['payment_status'];
					$_POST['_payment_method'] = $order['payment_id'];
				}

			}

			private function sibs_get_backend_order_parameters( $order ) {
				$order_parameters = array();
				$order_parameters = Sibs_General_Functions::sibs_get_credentials( $order['payment_id'] );

				$order_detail = Sibs_General_Models::sibs_get_db_order_detail( $order['order_id'] );
				foreach ( $order_detail as $value ) {
					if ( '_order_total' === $value['meta_key'] ) {
						$order_parameters['amount'] = $value['meta_value'];
					} elseif ( '_order_currency' === $value['meta_key'] ) {
						$order_parameters['currency'] = $value['meta_value'];
					}
				}

				$order_parameters['transaction_id']    = $order['order_id'];
				$order_parameters['payment_type']      = Sibs_General_Functions::sibs_get_payment_type( $order['payment_id'] );
				$order_parameters['payment_recurring'] = 'REPEATED';

				return $order_parameters;
			}

			private function sibs_do_success_backend_order( $order, $order_result, $order_parameter ) {
				$this->sibs_add_customer_note( $order['payment_id'], $order['order_id'], $order_parameter['currency'] );

				$order['payment_type']   = Sibs_General_Functions::sibs_get_payment_type( $order['payment_id'] );
				$order['reference_id']   = $order_result['id'];
				$order['payment_status'] = $order_result['payment_status'];
				$order['amount']         = $order_parameter['amount'];
				$order['currency']       = $order_parameter['currency'];

				Sibs_General_Models::sibs_save_db_transaction( $order );
				Sibs_General_Models::sibs_update_db_posts_status( $order['id'], $order_result['payment_status'] );

			}

			/////
		
			public function sibs_my_function_to_add_the_product( $order ) 
			{
				$order_id = intval($_POST['order_id']);

				//$product_id = intval($_POST['product_id']);  product_id:"status"
				
				$amount_id_received = $_POST['amount_id'];
				$amount_id = (float)$amount_id_received;

				//log
				$log = new WC_Logger();
				$log_entry = print_r( $order_id, true );
				$log->add( 'woocommerce-sibs-log', 'STATUS_LOG_ORDER_ID: ' . $log_entry );

				if ($_POST['product_id'] == 'status'){
					// status button 
                    // Executed when admin clicks on the "Check Status" button

					//getting order Object
					$order = wc_get_order($order_id);

					$transaction_log = Sibs_General_Models::sibs_get_db_transaction_log( $order_id );
					$is_testmode_available     = false;
					$is_multichannel_available = true;
					$status_parameter = Sibs_General_Functions::sibs_get_credentials( $transaction_log['payment_id'], $is_testmode_available, $is_multichannel_available );

					//log 
					$log = new WC_Logger();
					$log_entry = print_r( $transaction_log, true );
					$log->add( 'woocommerce-sibs-log', 'STATUS_LOG_TRANSACTION_DB: ' . $log_entry );

					if ( $transaction_log['payment_status'] == 'wc-on-hold' ){

                        // Get all payments for this merchantTransactionId
                        $status_result = SibsPaymentCore::sibs_get_query_merchantTransactionId( $transaction_log['transaction_id'], $status_parameter );
                        
						//log 
						$log = new WC_Logger();
						$log_entry = print_r( $status_result, true );
						$log->add( 'woocommerce-sibs-log', 'STATUS_LOG_REQUEST_RESULT: ' . $log_entry );

						if ( ! $status_result ) {

							$back_data['msg'] = 'Status connection error for order ' . esc_attr($order_id);
							$back_data['error'] = 1;
							wp_send_json( $back_data );

						} else {

							$result_status = SibsPaymentCore::sibs_get_transaction_result_ack_only( $status_result['result']['code'] );

							//log 
							$log = new WC_Logger();
							$log_entry = print_r( $result_status, true );
							$log->add( 'woocommerce-sibs-log', 'STATUS_LOG_REQUEST_RESULT_IS_ACK?: ' . $log_entry );
		
							if ( 'ACK' === $result_status ) {

                                // Get the payment information
                                $paymentInfo = null;
                                foreach ($status_result['payments'] as $key => $payment) {
                                    if ($payment['id'] == $transaction_log['reference_id']) {
                                        $paymentInfo = $status_result['payments'][$key];
                                        break;
                                    }
                                }
                                if (!$paymentInfo) {
                                     $back_data['msg'] = sprintf( __( 'Could not find payment with reference_id %1$s on payments of merchantTransactionId %2$s for order %3$s', 'wc-sibs' ), $transaction_log['reference_id'], $transaction_log['transaction_id'], esc_attr($order_id));
                                    $back_data['error'] = 1;
                                    wp_send_json( $back_data );
                                    
                                } else {
                                
                                    /*
                                     * When Multibanco, check if payment is complete
                                     * 
                                     *  When paymentType is "PA" and paymentBrand is "SIBS_MULTIBANCO" 
                                     *  then check if the payment is complete
                                     *      (look for a payment which the referencedId is equal to this payment id and paymentType is "RC")
                                     */
                                    if ($paymentInfo['paymentType'] == 'PA' && $paymentInfo['paymentBrand'] == 'SIBS_MULTIBANCO') {
                                        $paymentId = $paymentInfo['id'];
                                        $merchantTransactionId = $paymentInfo['merchantTransactionId'];

                                        $paymentComplete = false;
                                        foreach ($status_result['payments'] as $key => $payment) {
                                            $referencedId = $payment['referencedId'];
                                            $paymentType = $payment['paymentType'];
                                            if ($referencedId && $referencedId == $paymentId && $paymentType == 'RC') {
                                                // Multibanco payment complete 
                                                $paymentComplete = true;

                                                Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, 'wc-payment-accepted' );
                                                Sibs_General_Models::sibs_update_db_posts_status( $order_id, 'wc-payment-accepted' );

                                                $log->add( 'woocommerce-sibs-log', 'Status payment SIBS_MULTIBANCO complete for order #' . esc_attr($order_id) );

                                                $back_data['msg'] = 'Order #' . esc_attr($order_id) . ' paid';
                                                $back_data['error'] = 0;

                                                // send message
                                                wp_send_json( $back_data );
                                            }
                                        }
                                        if (!$paymentComplete) {
                                            // Check if the payment reference is expired 

                                            $expire_date = strtotime( $paymentInfo['resultDetails']['refLmtDtTm']);
                                            if ($paymentInfo['resultDetails']['refLmtDtTm'] && $expire_date < time()) {
                                                // Payment reference Expired - Order cancelled 

                                                $back_data['msg'] = 'Multibanco reference expired for order #' . esc_attr($order_id) . '. Order canceled';
                                                $back_data['error'] = 0;

                                                Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, 'wc-failed' );
                                                Sibs_General_Models::sibs_update_db_posts_status( $order_id, 'wc-failed' );

                                                // send message 
                                                wp_send_json( $back_data );
                                            } else {
                                                $back_data['msg'] = sprintf( __( 'No news on status updated for order %1$s', 'wc-sibs' ), esc_attr($order_id));
                                                $back_data['error'] = -1;
                                                wp_send_json( $back_data );
                                            }
                                        }

                                    } else {
										// Not Multibanco - (MBWAY or CC)

										$trnStatus = 'NOK';

										$ack_patterns = array(
											'/^(000\.000\.|000\.100\.1|000\.[36])/',
											'/^(000\.200)/',
										);
					
										$pending_patterns = array(
											'/^(000\.200)/',
											'/^(800\.400\.5|100\.400\.500)/',
										);
												
										foreach ( $ack_patterns as $pattern ) {
											if ( preg_match( $pattern, $paymentInfo['result']['code'] ) ) {
												$trnStatus = 'ACK';
											}
										}
						
										foreach ( $pending_patterns as $pattern ) {
											if ( preg_match( $pattern, $paymentInfo['result']['code'] ) ) {
												$trnStatus = 'PEN';
											}
										}

										// Check Payment
										if ( $trnStatus == 'ACK' ) {

											Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, 'wc-payment-accepted' );
											Sibs_General_Models::sibs_update_db_posts_status( $order_id, 'wc-payment-accepted' );

											$log->add( 'woocommerce-sibs-log', 'Status payment complete (ACK) for order #' . esc_attr($order_id ));

											$back_data['msg'] = sprintf( __( 'Status complete (ACK) for order #%1$s', 'wc-sibs' ), esc_attr($order_id));
											$back_data['error'] = 0;

											// send message
											wp_send_json( $back_data );

										} else if ( $trnStatus == 'NOK' ){

											Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, 'wc-failed' );
											Sibs_General_Models::sibs_update_db_posts_status( $order_id, 'wc-failed' );
	
											$log->add( 'woocommerce-sibs-log', 'Status payment declined (NOK) for order #' . esc_attr($order_id ));
	
											$back_data['msg'] = sprintf( __( 'Status declined (NOK) for order #%1$s', 'wc-sibs' ), esc_attr($order_id));
											$back_data['error'] = 0;
	
											// send message??
											wp_send_json( $back_data );

										} else {

											$log->add( 'woocommerce-sibs-log', 'Status payment pending for order #' . esc_attr($order_id ));
	
											$back_data['msg'] = sprintf( __( 'Status payment pending for order #%1$s', 'wc-sibs' ), esc_attr($order_id));
											$back_data['error'] = 0;

											// send message??
											wp_send_json( $back_data );

										}
                                    }
                                }
								
							} else {

								$back_data['msg'] = sprintf( __( 'Status (NOK) for order #%1$s', 'wc-sibs' ), esc_attr($order_id));
								$back_data['error'] = 1;

								// send message 
								wp_send_json( $back_data );
							}
						}
					} else {
						$back_data['msg'] = sprintf( __( 'Cannot check Status for order %1$s. Current status is different from Pending.', 'wc-sibs' ), esc_attr($order_id));
						$back_data['error'] = 1;
						wp_send_json( $back_data );
					}

				} else {

					//getting order Object 
					$order = wc_get_order($order_id);

					// Default error message
					$back_data['error'] = 1;
					$back_data['msg'] = esc_attr( __( 'Error in Capture API.', 'wc-sibs' ) );
					
					$is_sibs_payment = $this->sibs_is_sibs_by_order( $order_id ); 
	
					if ( $is_sibs_payment ) {
						$woocommerce_refund                 = true;
						$backoffice_config['payment_type']  = 'CP';
						$backoffice_config['order_status']  = 'wc-payment-captured';
						$backoffice_config['error_message'] = __( 'ERROR_GENERAL_CAPTURE_PAYMENT', 'wc-sibs' );
						$backoffice_config['amount1']       = $amount_id;				
						
						$refund_result = $this->sibs_do_back_office_payment( $order_id, $backoffice_config, $woocommerce_refund );
	
						//log
						$log = new WC_Logger();
						$log_entry = print_r( $refund_result, true );
						$log->add( 'woocommerce-sibs-log', 'REFUND_RESULT: ' . $log_entry );
					
						if ( $refund_result ) {
							$this->wc_order = new WC_Order( $order_id );
							$this->wc_order->update_status( 'wc-payment-captured', 'order_note' );
	
							$args = array(
								'post_parent' => $order_id,
								'post_type'   => 'shop_order_refund',
							);
	
							$child_posts = get_children( $args );
	
							if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
								$posts = $this->sibs_search_array_by_postmeta( $child_posts );
							} else {
								$posts = $this->sibs_search_array_by_value( esc_attr( __( 'Order Fully Captured', 'wc-sibs' ) ), $child_posts );
							}
	
							if ( count( $posts ) > 0 ) {
								wp_delete_post( $posts->ID, true );
							}
	
							Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, 'wc-payment-captured' );
							
							$message = sprintf( __( 'Order was Captured for %1$s %2$s', 'wc-sibs' ), $amount_id, $order->get_currency());
							$order->add_order_note( $message );
	
							// Capture message 
							$back_data['msg'] = sprintf( __( 'Capture complete for order #%1$s with %2$s', 'wc-sibs' ), esc_attr($order->get_id()), esc_attr($amount_id) . $order->get_currency());
							$back_data['error'] = 0;
							
						} else {
							// Error message if request fail
							$back_data['error'] = 1;
							$back_data['msg'] = esc_attr( __( 'Error in Capture API. Capture request denied.', 'wc-sibs' ) );
						} 
					} else {
						// Error message if is not a SIBS payment
						$back_data['error'] = 1;
						$back_data['msg'] = esc_attr( __( 'Error in Capture API. Not a SIBS payment.', 'wc-sibs' ) );
					}			
					// send message
					wp_send_json( $back_data );
			   }			  
			}

			/**
			 * [BACKEND] From class WC_Payment_Gateway
			 * Payment refund from button refund.
			 *
			 * @param object   $order
			 */
			public function sibs_custom_metabox_content( $order )
			{

				global $wpdb;
				
				// get order ID
				$order_id = $order->ID;

				//getting order Object (different from $order argument)
				$order_obj = wc_get_order($order_id); 

				//Get Order Amount for auto fill the input text
				$OrderAmount = $order_obj->get_total();

				// order data
				$orderArray = $order_obj->get_data();
				$payment_method = $orderArray['payment_method'];
			
				//Search if this order is a SIBS order
				$sibsOrderData = Sibs_General_Models::sibs_get_db_transaction_log( $order_id );

				$payment_type = $sibsOrderData['payment_type'];

				//status button
				echo '<button id="my_special_button" type="button" style="margin-right: 1%" class="button add-special-item" data-order_id="'. esc_attr($order_id)  .'" data-product_id="status" > <b>' . esc_attr( __( 'Check Status', 'wc-sibs' ) ) . '</b></button>';
			
				// State Machine for capture button
				if ($order->post_status != "wc-refunded" && $order->post_status != "wc-payment-captured" && $order->post_status != "wc-canceled" && $order->post_status != "wc-failed" && $payment_method != 'sibs_multibanco' && $payment_type == 'PA' ){
					echo '<button id="my_special_button"  type="button" class="button add-special-item" data-order_id="'. esc_attr($order_id)  .'" data-product_id="41" > <b>' . esc_attr( __( 'Capture', 'wc-sibs' ) ) . '</b></button> ' . esc_attr( __( 'Capture amount:', 'wc-sibs' ) ) . ' <input type="text" class="refund_amount_capture" name="refund_amount_capture" data-scenario="scenario1" placeholder="' . esc_attr( __( 'Enter Value', 'wc-sibs' ) ) . '" value="' . esc_attr( $OrderAmount ) . '"/>';
				}

				$table_perfixed = $wpdb->prefix . 'comments';
				// Query to get the comments
				$results = $wpdb->get_results("
					SELECT *
					FROM $table_perfixed
					WHERE  `comment_post_ID` = $order_id
					AND  `comment_type` LIKE  'order_note'
				");

				foreach($results as $note){
					$order_note[]  = array(
						'note_id'      => $note->comment_ID,
						'note_date'    => $note->comment_date,
						'note_author'  => $note->comment_author,
						'note_content' => $note->comment_content,
					);
				}

				// for each message 
				foreach($order_note as $note){
					$note_id = $note['note_id'];
					$note_date = $note['note_date'];
					$note_author = $note['note_author'];
					$note_content = $note['note_content'];

					if (strpos($note_content, 'Captured') !== false) {
						// Outputting each note content for the order
						echo sprintf( __( '<p>%1$s at %2$s by %3$s</p>', 'wc-sibs' ), $note_content, $note_date, $note_author);
					}
				}	
			}
			
			/////

			public function process_refund( $order_id, $amount = null, $reason = '' ) {
				$is_sibs_payment = $this->sibs_is_sibs_by_order( $order_id );

				if ( $is_sibs_payment ) {
					$woocommerce_refund                 = true;
					$backoffice_config['payment_type']  = 'RF';
					$backoffice_config['order_status']  = 'wc-refunded';
					$backoffice_config['error_message'] = __( 'ERROR_GENERAL_REFUND_PAYMENT', 'wc-sibs' );

					$refund_result = $this->sibs_do_back_office_payment( $order_id, $backoffice_config, $woocommerce_refund );

					//log
					$log = new WC_Logger();
					$log_entry = print_r( $refund_result, true );
					$log->add( 'woocommerce-sibs-log', 'REFUND RESULT : ' . $log_entry );

					if ( $refund_result ) {
						$this->wc_order = new WC_Order( $order_id );
						$this->wc_order->update_status( 'wc-refunded', 'order_note' );

						$args = array(
							'post_parent' => $order_id,
							'post_type'   => 'shop_order_refund',
						);

						$child_posts = get_children( $args );

						if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
							$posts = $this->sibs_search_array_by_postmeta( $child_posts );
						} else {
							$posts = $this->sibs_search_array_by_value( esc_attr( __( 'Order Fully Refunded', 'wc-sibs' ) ), $child_posts );
						}

						if ( count( $posts ) > 0 ) {
							wp_delete_post( $posts->ID, true );
						}

						Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, 'wc-refunded' );
					}
					return $refund_result;
				}
				return false;
			}

			public function sibs_search_array_by_postmeta( $array ) {
				foreach ( $array as $key => $val ) {
					if ( strpos( get_post_meta( $val->ID, '_refund_reason', true ), 'Bestellung vollst??ndig' ) !== false
						|| strpos( get_post_meta( $val->ID, '_refund_reason', true ), 'Order fully' ) !== false ) {
						return $val;
					}
				}

				return null;
			}

			public function sibs_search_array_by_value( $value, $array ) {
				foreach ( $array as $key => $val ) {
					if ( $val->post_excerpt === $value ) {
						return $val;
					}
				}
				return null;
			}

			protected function sibs_increase_order_stock() {
				$items = $this->wc_order->get_items();
				foreach ( $items as $item ) {
					$wc_product       = $this->wc_order->get_product_from_item( $item );
					$is_managed_stock = $this->sibs_is_managed_stock( $item['product_id'] );
					if ( $is_managed_stock ) {
						$product_stock = $wc_product->get_stock_quantity();
						$wc_product->increase_stock( $item['qty'] );
						$order_note = sprintf( __( 'Item #%1$s stock increased from %2$s to %3$s', 'wc-sibs' ), $item['product_id'], $product_stock, ( $product_stock + $item['qty'] ));
						$this->wc_order->add_order_note( $order_note );
					}
				}
			}


			protected function sibs_is_managed_stock( $product_id ) {
				$is_managed_stock = get_post_meta( $product_id, '_manage_stock', true );
				if ( 'yes' === $is_managed_stock ) {
					return true;
				}
				return false;
			}


			private function sibs_do_back_office_payment( $order_id, $back_office_config, $woocommerce_refund = false ) {
				$transaction_log           = Sibs_General_Models::sibs_get_db_transaction_log( $order_id );
				$is_testmode_available     = true;
				$is_multichannel_available = true;
				$amount                    = Sibs_General_Functions::sibs_get_request_value( 'refund_amount', false );

				$backoffice_parameter = Sibs_General_Functions::sibs_get_credentials( $transaction_log['payment_id'], $is_testmode_available, $is_multichannel_available );
				
				if ( $woocommerce_refund ) {
					
					if ( !$amount ){
						$backoffice_parameter['amount'] = $back_office_config['amount1']; 
					} else {
						$backoffice_parameter['amount'] = $amount;
					}
					
				} else {
					$backoffice_parameter['amount'] = $transaction_log['amount'];
				}
				$backoffice_parameter['currency']     = $transaction_log['currency'];
				$backoffice_parameter['payment_type'] = $back_office_config['payment_type'];

				//log
				$log = new WC_Logger();
				$log_entry = print_r( $backoffice_parameter, true );
				$log->add( 'woocommerce-sibs-log', 'BACK OFFICE PAYMENT PARAMETERS: ' . $log_entry );
				
				$backoffice_result = SibsPaymentCore::sibs_back_office_operation( $transaction_log['reference_id'], $backoffice_parameter );

				//log
				$log = new WC_Logger();
				$log_entry = print_r( $backoffice_result, true );
				$log->add( 'woocommerce-sibs-log', 'BACK OFFICE PAYMENT RESULT: ' . $log_entry );

				if (!$backoffice_result['is_valid']) {
					//die('sibs_payment_1246'); 
					if ( 'ERROR_UNKNOWN' !== $backoffice_result['response'] ) {
						$back_office_config['error_message'] = Sibs_General_Functions::sibs_translate_error_identifier( $backoffice_result['response'] );
					} else {
						$back_office_config['error_message'] = Sibs_General_Functions::sibs_translate_error_identifier( 'ERROR_UNKNOWN' );
					}
					if ( $woocommerce_refund ) {
						return false;
					} else {
						$this->bs_backend_redirect_error( $order_id, $back_office_config['error_message'] );
					}
				} else {
					$backoffice_status = SibsPaymentCore::sibs_get_transaction_result( $backoffice_result['response']['result']['code'] );
					if ( 'ACK' === $backoffice_status ) {
						Sibs_General_Models::sibs_update_db_transaction_log_status( $order_id, $back_office_config['order_status'] );
						if ( $woocommerce_refund ) {
							return true;
						}
					} else {
						if ( $woocommerce_refund ) {
							return false;
						} else {
							$redirect = get_admin_url() . 'post.php?post=' . $order_id . '&action=edit';
							WC_Admin_Meta_Boxes::add_error( $back_office_config['error_message'] );
							wp_safe_redirect( $redirect );
							exit;
						}
					}
				}
			}

			public function sibs_add_additional_information() {
				if ( ! self::$added_meta_boxes ) {
					$order_id = Sibs_General_Functions::sibs_get_request_value( 'post', false );

					$is_sibs_payment = $this->sibs_is_sibs_by_order( $order_id );
					if ( $is_sibs_payment ) {
						$additional_information = '';
						$transaction_log        = Sibs_General_Models::sibs_get_db_transaction_log( $order_id );
						//if ( $transaction_log['additional_information'] ) {
						//	$additional_information = $this->sibs_set_additional_info( $transaction_log['additional_information'] );
						//}
						$args = array(
							'transaction_log'        => $transaction_log,
							'additional_information' => $additional_information,
						);
						Sibs_General_Functions::sibs_include_template( dirname( __FILE__ ) . '/templates/admin/meta-boxes/template-additional-information.php', $args );
					}
					self::$added_meta_boxes = true;
				}
			}

			public function sibs_update_order_status() {
				$post_type = false;
				if ( isset( $_GET['post_type'] ) ) { // input var okay.
					$post_type = wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) ); // input var okay.
				}

				if ( ! self::$updated_meta_boxes && 'shop_order' !== $post_type ) {
					$order_id        = Sibs_General_Functions::sibs_get_request_value( 'post', false );
					$is_sibs_payment = $this->sibs_is_sibs_by_order( $order_id );

					if ( $is_sibs_payment ) {
						$order = wc_get_order( $order_id );
                        if ( 'wc-sibs-processing' === $order->get_status() ) {
							$request_section = Sibs_General_Functions::sibs_get_request_value( 'section', false );

							if ( $order_id && 'update-order' === $request_section ) {
								$this->sibs_update_payment_status( $order_id, 'wc-on-hold' );
								$redirect = get_admin_url() . 'post.php?post=' . $order_id . '&action=edit';
								wp_safe_redirect( $redirect );
								exit;
							}
							$args = array(
								'order_id' => $order_id,
							);
							Sibs_General_Functions::sibs_include_template( dirname( __FILE__ ) . '/templates/admin/meta-boxes/template-update-order.php', $args );
						}
					}
					self::$updated_meta_boxes = true;
				}
			}

			public function add_payment_method() {
				if ( ! self::$added_payment_method ) {
					$action   = Sibs_General_Functions::sibs_get_request_value( 'action', 'false' );
					$order_id = Sibs_General_Models::sibs_get_db_last_order_id();
					if ( $order_id ) {
						$args = array(
							'action' => $action,
						);
						Sibs_General_Functions::sibs_include_template( dirname( __FILE__ ) . '/templates/admin/meta-boxes/template-add-payment-method.php', $args );
					}

					self::$added_payment_method = true;
				}
			}

			public function sibs_add_meta_boxesws()
			{
				add_meta_box( 'custom_order_meta_box', __( 'Capture' ),
					array( &$this, 'sibs_custom_metabox_content' ) , 'shop_order', 'normal', 'default');
			}

			public function callback_hook(){
				global $wpdb;

				$table_prefix = $wpdb->prefix;
				$webhookSecret = get_option( 'sibs_webhook_secret' );

				if ( is_null( $webhookSecret ) ) {
					return array(501, esc_attr( __( 'Webhook Secret not defined', 'wc-sibs' ) ));
				}
				
				//$headers = $_SERVER;
				$iv_from_http_header = $_SERVER['HTTP_X_INITIALIZATION_VECTOR'];
				$auth_tag_from_http_header = $_SERVER['HTTP_X_AUTHENTICATION_TAG'];
			    //$headers = apache_request_headers();
				//$iv_from_http_header = $headers['X-Initialization-Vector'];
				//$auth_tag_from_http_header = $headers['X-Authentication-Tag'];
				
				if (is_null($iv_from_http_header)){
					return new WP_Error(
						'rest_no_event_date', __( 'X-Initialization-Vector not present in header' ),
						array( 'status' => 407 ) );
				}

				if (is_null($auth_tag_from_http_header)){
					return new WP_Error(
						'rest_no_event_date', __( 'X-Authentication-Tag not present in header' ),
						array( 'status' => 408 ) );
				}

				//log 
				$log = new WC_Logger();
				$log_entry = print_r( $auth_tag_from_http_header, true );
				$log->add( 'woocommerce-sibs-log', 'WEBHOOK BODY X-Authentication-Tag : ' . $log_entry );

				//log 
				$log = new WC_Logger();
				$log_entry = print_r( $iv_from_http_header, true );
				$log->add( 'woocommerce-sibs-log', 'WEBHOOK BODY X-Initialization-Vector : ' . $log_entry );
				
				$http_body = file_get_contents( 'php://input' );

				if ( $http_body == '' ) {
					return array(501, esc_attr( __( 'Body is empty', 'wc-sibs' ) ));
				}

				//log 
				$log = new WC_Logger();
				$log_entry = print_r( $http_body, true );
				$log->add( 'woocommerce-sibs-log', 'WEBHOOK BODY ENCODED : ' . $log_entry );

				$key = hex2bin($webhookSecret);
				$iv = hex2bin($iv_from_http_header);
				$cipher_text = hex2bin($http_body . $auth_tag_from_http_header);

				$PHPSodiumAvailable = 0;
				$PHPSodiumCompactAvailable = 0;
		
				if (function_exists('sodium_crypto_aead_aes256gcm_is_available')) {
					if (sodium_crypto_aead_aes256gcm_is_available()) {
						$PHPSodiumAvailable = 1;
					}
				}
		
				If ($PHPSodiumAvailable == 0) {
					$dir = substr(plugin_dir_path( __DIR__ ), 0 , -9)  .DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'sibs-woocommerce'.DIRECTORY_SEPARATOR.'external'.DIRECTORY_SEPARATOR.'sodium_compat-1.7.0'.DIRECTORY_SEPARATOR.'autoload.php';					require_once $dir;
		
					if (function_exists('\Sodium\crypto_aead_aes256gcm_is_available')) {
						if (\Sodium\crypto_aead_aes256gcm_is_available()) {
							$PHPSodiumCompactAvailable = 1;
						}
					}
				}
		
				if($PHPSodiumAvailable == 1){
					$result = sodium_crypto_aead_aes256gcm_decrypt( $cipher_text, "", $iv, $key);
				} else {	        
					if ($PHPSodiumCompactAvailable == 1) {
						$result = \Sodium\crypto_aead_aes256gcm_decrypt($cipher_text, "", $iv, $key);
					} else {
						return array(502,esc_attr( __( 'sodium lib not available', 'wc-sibs' ) ));
					}		
				}				

				if ($result === false) {
					return array(501, esc_attr( __( 'Bad ciphertext', 'wc-sibs' ) ));
				}
						
				$body = json_decode($result , true);

				//log 
				$log = new WC_Logger();
				$log_entry = print_r( $body, true );
				$log->add( 'woocommerce-sibs-log', 'WEBHOOK BODY DECODED: ' . $log_entry );
				
				if (($body['type'] == 'PAYMENT') && array_key_exists('action', $body) && array_key_exists('payload', $body) ) {
					if (is_null($body['action']) && is_null($body['payload'])){
						// logs 
						$log = new WC_Logger();
						$log_entry = print_r( 'PAYMENT', true );
						$log->add( 'woocommerce-sibs-log', 'WEBHOOK TYPE: ' . $log_entry );
					}	
				}

				//test 
				if (($body['type'] == 'test') || ($body['type'] == 'Test') ) {
					
					// logs
					$log = new WC_Logger();
					$log_entry = print_r( 'Received', true );
					$log->add( 'woocommerce-sibs-log', 'WEBHOOK TEST: ' . $log_entry );

					return array(200,esc_attr( __( 'OK', 'wc-sibs' ) ));
				}

				if (isset($body['payload']['referencedId'])) {
					$originalTransactionId = $body['payload']['referencedId'];
				} else {
					$originalTransactionId = $body['payload']['id'];
				}
				
				$sqlString = "SELECT order_no FROM " . $table_prefix . "sibs_transaction where " . 
				"  reference_id = '" . $originalTransactionId . "'" ; 

				$orderNo = $wpdb->get_var($sqlString );

				$webHookStatus = $body['payload']['result']['code'];

				//echo $swebHookStatus; 
				$trnStatus =false; 
				
				$ack_patterns = array(
						'/^(000\.000\.|000\.100\.1|000\.[36])/',
						'/^(000\.200)/',
					);

				$pending_patterns = array(
						'/^(000\.200)/',
						'/^(800\.400\.5|100\.400\.500)/',
					);

				$nok_patterns = array(
						'/^(000\.400\.[1][0-9][1-9]|000\.400\.2)/',
						'/^(800\.[17]00|800\.800\.[123])/',
						'/^(900\.[1234]00)/',
						'/^(800\.5|999\.|600\.1|800\.800\.8)/',
						'/^(100\.39[765])/',
						'/^(100\.400|100\.38|100\.370\.100|100\.370\.11])/',
						'/^(800\.400\.1)/',
						'/^(800\.400\.2|100\.380\.4|100\.390)/',
						'/^(100\.100\.701|800\.[32])/',
						'/^(800\.1[123456]0)/',
						'/^(600\.2|500\.[12]|800\.121)/',
						'/^(100\.[13]50)/',
						'/^(100\.250|100\.360)/',
						'/^(700\.[1345][05]0)/',
						'/^(200\.[123]|100\.[53][07]|800\.900|100\.[69]00\.500)/',
						'/^(100\.800)/',
						'/^(100\.[97]00)/',
						'/^(100\.100|100.2[01])/',
						'/^(100\.55)/',
						'/^(100\.380\.[23]|100\.380\.101)/',
					);
						
				foreach ( $ack_patterns as $pattern ) {
					if ( preg_match( $pattern, $webHookStatus ) ) {
						if ( get_option( 'sibs_general_custom_status' )) {
							if ($body['payload']['paymentType'] == 'DB' || $body['payload']['paymentType'] == 'RC' ) {
								$trnStatus = 'wc-completed';
							} else {
								$trnStatus = 'wc-processing';
							}
						} else {
							$trnStatus = 'wc-processing';
						}
					}
				}

				foreach ( $pending_patterns as $pattern ) {
					if ( preg_match( $pattern, $webHookStatus ) ) {
						$trnStatus = 'wc-on-hold';
					}
				}

				foreach ( $nok_patterns as $pattern ) {
					if ( preg_match( $pattern, $webHookStatus ) ) {
						$trnStatus = 'wc-failed';
					}
				}

				// only do if is a payment -- actualizar multibanco
                if($body['payload']['paymentBrand'] == 'SIBS_MULTIBANCO') {
					if  ($body['payload']['paymentType'] == 'RC') {
                        $log = new WC_Logger();
						$wpdb->query('START TRANSACTION');

						$rows = $wpdb->update($table_prefix . "sibs_transaction" , 
							array('payment_status'=>$trnStatus) ,
							array('reference_id'=>$originalTransactionId) );
		
						if ($rows == 0){
							// logs
							$log_entry = print_r( $originalTransactionId, true );
							$log->add( 'woocommerce-sibs-log', 'WEBHOOK Unknown Order: ' . $log_entry ); 
						} 
							
						if($rows > 1) {
							$wpdb->query('ROLLBACK'); // something went wrong, Rollback 
							return array(400,esc_attr( __( 'erro in update - number of line > 1', 'wc-sibs' ) ));
						
						}

						$wpdb->query('COMMIT'); // if you come here then... well done!

                        if ($rows == 1) {
							$order = wc_get_order($orderNo);
                            if ( get_option( 'sibs_general_custom_status' ))
                            {
                                $order->update_status('wc-completed', 'order_note');
                            }
                            else
                            {
                                $order->update_status('wc-processing', 'order_note');
                            }

							$log_entry = print_r( $orderNo  , true );
							$log->add( 'woocommerce-sibs-log', 'stock webhook sibsmultibanco1 : ' . $log_entry );
							$log_entry = print_r( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) , true );
							$log->add( 'woocommerce-sibs-log', 'stock webhook sibsmultibanco2 : ' . $log_entry );

							// Reduce stock levels.
							if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
								wc_reduce_stock_levels( $orderNo );
							} else {
								$order->reduce_order_stock();
							}
                        }
						return array(200,esc_attr( __( 'OK', 'wc-sibs' ) ));
					} else {
						return array(200,esc_attr( __( 'OK', 'wc-sibs' ) ));
					}
                } elseif ( $body['payload']['paymentType'] == 'PA' || 
					$body['payload']['paymentType'] == 'DB' || 
					$body['payload']['paymentType'] == 'PA.CP' ){
                    $log = new WC_Logger();

					if ($trnStatus == 'wc-on-hold'){
						return array(200,esc_attr( __( 'OK', 'wc-sibs' ) ));
					} else {
						
						$wpdb->query('START TRANSACTION');

						$rows = $wpdb->update($table_prefix . "sibs_transaction" ,
							array('payment_status'=>$trnStatus) ,
							array('reference_id'=>$originalTransactionId) );
							//  'additional_information'=>$trnStatus 

						if ($rows == 0){
							// logs
							$log_entry = print_r( $originalTransactionId, true );
							$log->add( 'woocommerce-sibs-log', 'WEBHOOK Unknown Order: ' . $log_entry ); 
						} 
							
						if($rows > 1) {
							$wpdb->query('ROLLBACK'); // something went wrong, Rollback 
							return array(400,esc_attr( __( 'erro in update - number of line > 1', 'wc-sibs' ) ));
						}

						$wpdb->query('COMMIT'); // if you come here then... well done!

                        if ($rows == 1) {
							$order = wc_get_order($orderNo);

							if ($trnStatus == 'wc-payment-accepted') {
								$order->update_status( 'wc-processing', 'order_note' );

								$log = new WC_Logger();
								$log_entry = print_r( $orderNo  , true );
								$log->add( 'woocommerce-sibs-log', 'stock webhook mbway1 : ' . $log_entry );
								$log_entry = print_r( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) , true );
								$log->add( 'woocommerce-sibs-log', 'stock webhook mbway2 : ' . $log_entry );

								// Reduce stock levels.
								if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
									wc_reduce_stock_levels( $orderNo );
								} else {
									$order->reduce_order_stock();
								}
							} else {
								$this->wc_order = new WC_Order( $orderNo );

								if  ('wc-on-hold' === $this->wc_order->get_status() ) {
									$this->sibs_increase_order_stock();
								}
								$order->update_status(  $trnStatus, 'order_note' );
							}

                        }
						return array(200,esc_attr( __( 'OK', 'wc-sibs' ) ));
					}   
				}
			}
			

			/**
			 * Get success payment status
			 *
			 * @param string $payment_id payment id.
			 * @param string $payment_result_code payment result code.
			 * @return string
			 */
			private function sibs_get_success_payment_status( $payment_id, $payment_result_code ) {
				$is_success_review = SibsPaymentCore::sibs_is_success_review( $payment_result_code );

				if ( $is_success_review ) {
					$payment_status = 'wc-on-hold';
				} else {
					if ( get_option( 'sibs_general_custom_status' )) {
						if ( 'PA' === Sibs_General_Functions::sibs_get_payment_type( $payment_id ) ) {
							if ('SIBS_MULTIBANCO' === $payment_brand) {
								$payment_status = 'wc-on-hold';
							} else {
								$payment_status = 'wc-processing';

							}
						} else {
							$payment_status = 'wc-completed';
						}
					} else {
						if ( 'PA' === Sibs_General_Functions::sibs_get_payment_type( $payment_id ) ) {
							$payment_status = 'wc-on-hold';
						} else {
							$payment_status = 'wc-processing';
						}
					}
				}

				return $payment_status;
			}

			private function sibs_add_customer_note( $payment_id, $order_id, $currency ) {
				$this->wc_order = new WC_Order( $order_id );

				$customer_note = $this->sibs_get_wc_order_property_value( 'customer_note' );

				$new_line             = "\n";
				$transaction_id_title = __( 'BACKEND_TT_TRANSACTION_ID', 'wc-sibs' );

				$payment_comments = Sibs_General_Functions::sibs_translate_frontend_payment( $payment_id ) . $new_line;

				if ( $this->sibs_get_wc_customer_note($this->wc_order) ) {
					$customer_note .= $new_line;
				}

				$customer_note .= html_entity_decode( $payment_comments, ENT_QUOTES, 'UTF-8' );
				$order_notes                    = array(
					'ID'           => $this->sibs_get_wc_order_property_value( 'id' ),
					'post_excerpt' => $this->sibs_get_wc_customer_note($this->wc_order),
				);
				wp_update_post( $order_notes );
			}

			private function sibs_set_cart_items_parameters() {
				global $woocommerce;
				$wc_cart    = $woocommerce->cart;
				$count      = 0;
				$cart_items = array();
				foreach ( $wc_cart->get_cart() as $cart ) {
					$cart_items[ $count ]['merchant_item_id'] = $cart['product_id'];
					if ( 'sibs_easycredit' !== $this->payment_id ) {
						$cart_items[ $count ]['discount'] = Sibs_General_Functions::sibs_get_payment_discount_in_percent( $cart );
					}
					$cart_items[ $count ]['quantity'] = (int) $cart['quantity'];
					if ( Sibs_General_Functions::sibs_is_version_greater_than( '3.0' ) ) {
						$cart_items[ $count ]['name'] = $cart['data']->get_name();
					} else {
						$cart_items[ $count ]['name'] = $cart['data']->get_title();
					}
					$cart_items[ $count ]['price'] = Sibs_General_Functions::sibs_get_payment_price_with_tax_and_discount( $cart );
					$cart_items[ $count ]['tax']   = Sibs_General_Functions::sibs_get_payment_tax_in_percent( $cart );

					$count++;
				}

				return $cart_items;
			}

			private function sibs_set_customer_parameters( $order_id ) {
				$this->wc_order = new WC_Order( $order_id );

				$customer['first_name'] = $this->sibs_get_wc_order_property_value( 'billing_first_name' );
				$customer['last_name']  = $this->sibs_get_wc_order_property_value( 'billing_last_name' );
				$customer['email']      = $this->sibs_get_wc_order_property_value( 'billing_email' );

				if ( 'sibs_klarnains' === $this->payment_id || 'sibs_klarnainv' === $this->payment_id || 'sibs_easycredit' === $this->payment_id || 'sibs_mbway' === $this->payment_id) {
					$customer['phone']     = $this->sibs_get_wc_order_property_value( 'billing_phone' );
				}
				if ( 'sibs_klarnains' === $this->payment_id || 'sibs_klarnainv' === $this->payment_id || 'sibs_easycredit' === $this->payment_id ) {
					
					$customer['birthdate'] = get_option( 'sibs_general_dob_gender' ) ? date( 'Y-m-d', strtotime( $this->wc_order->billing_bod ) ) : '';
					$customer['sex']       = get_option( 'sibs_general_dob_gender' ) ? Sibs_General_Functions::sibs_get_initial_gender( $this->wc_order->billing_gender ) : '';
				}

				return $customer;
			}

			private function sibs_set_billing_parameters( $order_id ) {
				$this->wc_order = new WC_Order( $order_id );

				$billing['street'] = $this->sibs_get_wc_order_property_value( 'billing_address_1' );
				if ( trim( $this->sibs_get_wc_order_property_value( 'billing_address_2' ) ) ) {
					$billing['street'] .= ', ' . $this->sibs_get_wc_order_property_value( 'billing_address_2' );
				}
				$billing['city']         = $this->sibs_get_wc_order_property_value( 'billing_city' );
				$billing['zip']          = $this->sibs_get_wc_order_property_value( 'billing_postcode' ) == "" ? '0000-000' : $this->sibs_get_wc_order_property_value( 'billing_postcode' );
				$billing['country_code'] = $this->sibs_get_wc_order_property_value( 'billing_country' );

                $log = new WC_Logger();
                $log->add( 'woocommerce-sibs-log', 'ZIP: ' . $billing['zip'] );

				return $billing;
			}

			private function sibs_set_shipping_parameters( $order_id ) {
				$this->wc_order = new WC_Order( $order_id );

				$shipping['street'] = $this->sibs_get_wc_order_property_value( 'shipping_address_1' );
				if ( trim( $this->sibs_get_wc_order_property_value( 'shipping_address_2' ) ) ) {
					$shipping['street'] .= ', ' . $this->sibs_get_wc_order_property_value( 'shipping_address_2' );
				}
				$shipping['city']         = $this->sibs_get_wc_order_property_value( 'shipping_city' );
				$shipping['zip']          = $this->sibs_get_wc_order_property_value( 'shipping_postcode' );
				$shipping['country_code'] = $this->sibs_get_wc_order_property_value( 'shipping_country' );

				return $shipping;
			}


			private function sibs_get_paydirekt_parameters() {
				$paydirekt['PAYDIREKT_minimumAge']        = $this->settings['minimum_age'];
				$paydirekt['PAYDIREKT_payment.isPartial'] = $this->settings['payment_is_partial'];

				return $paydirekt;
			}


			private function sibs_get_klarna_parameters() {
				if ( 'sibs_klarnains' === $this->payment_id ) {
					$klarna['KLARNA_PCLASS_FLAG'] = $this->settings['pclass'];
				}

				$klarna['KLARNA_CART_ITEM1_FLAGS'] = '32';

				return $klarna;
			}


			private function sibs_get_easycredit_parameters() {
				$easycredit['RISK_ANZAHLBESTELLUNGEN'] = Sibs_General_Functions::sibs_get_order_count();

				$easycredit['RISK_BESTELLUNGERFOLGTUEBERLOGIN'] = is_user_logged_in() ? 'true' : 'false';

				$easycredit['RISK_KUNDENSTATUS'] = Sibs_General_Functions::sibs_get_risk_kunden_status();

				$easycredit['RISK_KUNDESEIT'] = Sibs_General_Functions::sibs_get_customer_created_date();

				return $easycredit;
			}


			protected function sibs_set_payment_parameters( $order_id ) {

				$this->wc_order = new WC_Order( $order_id );

				$payment_parameters                     = Sibs_General_Functions::sibs_get_credentials( $this->payment_id );
				$payment_parameters['transaction_id']   = $this->wc_order->get_order_number();
				$payment_parameters['amount']           = $this->get_order_total();
				$payment_parameters['currency']         = get_woocommerce_currency();
				$payment_parameters['customer']         = $this->sibs_set_customer_parameters( $order_id );

				//truncate customer name 
				$payment_parameters['customer']['first_name'] = substr($payment_parameters['customer']['first_name'], 0, 10);
				$payment_parameters['customer']['last_name'] = substr($payment_parameters['customer']['last_name'], -10);

				$payment_parameters['billing']          = $this->sibs_set_billing_parameters( $order_id );
				$payment_parameters['payment_type']     = $this->payment_type;
				$payment_parameters['payment_brand']    = $this->payment_brand;
				$payment_parameters['customParameters'] = array();
				
				$payment_parameters['customParameters']['SIBS_ENV']           = get_option( 'sibs_general_environment' );

				if ( 'sibs_paydirekt' === $this->payment_id ) {
					$payment_parameters['customParameters'] = $this->sibs_get_paydirekt_parameters();
				}

				if ( 'sibs_klarnainv' === $this->payment_id || 'sibs_klarnains' === $this->payment_id ) {
					$payment_parameters['cartItems']        = $this->sibs_set_cart_items_parameters();
					$payment_parameters['customParameters'] = $this->sibs_get_klarna_parameters();
				}

				if ( 'sibs_easycredit' === $this->payment_id ) {
					$payment_parameters['cartItems']        = $this->sibs_set_cart_items_parameters();
					$payment_parameters['customParameters'] = $this->sibs_get_easycredit_parameters();
					$payment_parameters['shipping']         = $this->sibs_set_shipping_parameters( $order_id );

					global $wp;
					if ( isset( $wp->request ) ) {
						$payment_parameters['shopperResultUrl'] = home_url( $wp->request ) . '/?key=' . Sibs_General_Functions::sibs_get_request_value( 'key' ) . '&confirmation_page=1';
					} else {
						$payment_parameters['shopperResultUrl'] = get_page_link() . '&order-pay=' . Sibs_General_Functions::sibs_get_request_value( 'order-pay' ) . '&key=' . Sibs_General_Functions::sibs_get_request_value( 'key' ) . '&confirmation_page=1';
					}
				}
				
				$payment_parameters['customer_ip'] = Sibs_General_Functions::sibs_get_customer_ip();

				//for mbway 
				if ('sibs_mbway' === $this->payment_id) {

					$shop = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

					if ( $shop ){
						$payment_parameters['descriptor'] = $shop . '-' . $order_id;
					}
				}


				$log = new WC_Logger();
				$log_entry = print_r( $payment_parameters, true );
				$log->add( 'woocommerce-sibs-log', 'PAYMENT_PARAMETERS: ' . $log_entry );

				return $payment_parameters;
			}


			protected function sibs_set_serialize_add_info() {
				$additional_info = array();
				if ( isset( WC()->session->sum_of_interest ) ) {
					$additional_info[] = 'FRONTEND_EASYCREDIT_INTEREST=>' . WC()->session->sum_of_interest;
				}
				if ( isset( WC()->session->order_total ) ) {
					$additional_info[] = 'FRONTEND_EASYCREDIT_TOTAL=>' . WC()->session->order_total;
				}
				if ( 'sibs_easycredit' === $this->payment_id ) {
					unset( WC()->session->tilgungsplan_text, WC()->session->sum_of_interest, WC()->session->order_total );
				}
				$additional_info_serialize = maybe_serialize( $additional_info );

				unset(
					WC()->session->tilgungsplan_text,
					WC()->session->sum_of_interest,
					WC()->session->order_total
				);

				return $additional_info_serialize;
			}


			public function sibs_set_additional_info( $serialize_info ) {
				$additional_info = false;
				if ( $serialize_info ) {
					$unserialize_info = maybe_unserialize( $serialize_info );
					foreach ( $unserialize_info as $info ) {
						$explode_info                        = explode( '=>', $info );
						$additional_info[ $explode_info[0] ] = $explode_info[1];
					}
				}

				return $additional_info;
			}


			private function sibs_set_registration_parameters() {

				$registration        = array();
				$credentials         = Sibs_General_Functions::sibs_get_credentials( $this->payment_id );
				$registered_payments = Sibs_General_Models::sibs_get_db_registered_payment( $this->payment_group, $credentials );

				foreach ( $registered_payments as $key => $registrations ) {
					$registration[ $key ] = $registrations['reg_id'];
				}

				return $registration;
			}


			private function sibs_get_registered_paypal() {

				$registered_paypal   = array();
				$credentials         = Sibs_General_Functions::sibs_get_credentials( $this->payment_id );
				$registered_payments = Sibs_General_Models::sibs_get_db_registered_payment( $this->payment_group, $credentials );

				foreach ( $registered_payments as $key => $registered_payment ) {
					$registered_paypal[ $key ]['reg_id']          = $registered_payment['reg_id'];
					$registered_paypal[ $key ]['email']           = $registered_payment['email'];
					$registered_paypal[ $key ]['payment_default'] = $registered_payment['payment_default'];
				}

				return $registered_paypal;
			}

			//  $account
			private function sibs_save_recurring_card_payment( $order_id, $payment_result ) {
				$is_registered_payment = Sibs_General_Models::sibs_is_registered_payment_db( $payment_result['registrationId'] );

				if ( ! $is_registered_payment ) {
					$credentials                           = Sibs_General_Functions::sibs_get_credentials( $this->payment_id );
					//$registered_payment                    = $account;
					$registered_payment['payment_group']   = $this->payment_group;
					$registered_payment['payment_brand']   = $payment_result['paymentBrand'];
					$registered_payment['server_mode']     = $credentials['server_mode'];
					$registered_payment['channel_id']      = $credentials['channel_id'];
					$registered_payment['registration_id'] = $payment_result['registrationId'];
					$registered_payment['payment_default'] = Sibs_General_Models::sibs_get_db_payment_default( $this->payment_group, $credentials );

					// Credit Card
					$registered_payment['holder'] =  $payment_result['card']['holder'];
					$registered_payment['email'] = $payment_result['customer']['email'];
					$registered_payment['last_4_digits'] =  $payment_result['card']['last4Digits'];
					$registered_payment['expiry_month'] = $payment_result['card']['expiryMonth'];
					$registered_payment['expiry_year'] = $payment_result['card']['expiryYear'];

					Sibs_General_Models::sibs_save_db_registered_payment( $registered_payment );
				}
			}


			
			protected function sibs_save_transactions( $order_id, $payment_result, $reference_id ) {
				$this->wc_order = new WC_Order( $order_id );
				$transaction = array();
				$this->sibs_add_customer_note( $this->payment_id, $order_id, $payment_result['currency'] );

				if ( empty( $payment_result['paymentBrand'] ) ) {
					$payment_result['paymentBrand'] = $this->payment_brand;
				}

				$additional_info = '';

				//used for email multibanco
				if ( $payment_result['paymentBrand'] == 'SIBS_MULTIBANCO' ){
					$additional_info = $payment_result['resultDetails']['ptmntEntty'] . '|' . $payment_result['resultDetails']['pmtRef'] . '|' . $payment_result['resultDetails']['refLmtDtTm'] . '|4';
				}

				$transaction['order_id']       = $this->wc_order->get_order_number();
				$transaction['payment_type']   = $this->payment_type;
				$transaction['reference_id']   = $reference_id;
				$transaction['payment_brand']  = $payment_result['paymentBrand'];
				$transaction['transaction_id'] = $payment_result['merchantTransactionId'];
				$transaction['payment_id']     = $this->payment_id;
				$transaction['payment_status'] = $payment_result['payment_status'];
				$transaction['amount']         = $this->get_order_total();
				$transaction['currency']       = get_woocommerce_currency();
				$transaction['customer_id']    = ( $this->wc_order->get_user_id() ) ? $this->wc_order->get_user_id() : 0;

				//log 
				$log = new WC_Logger();
				$log_entry = print_r( $transaction, true );
				$log->add( 'woocommerce-sibs-log', 'SAVE TRANSACTION : ' . $log_entry );


				Sibs_General_Models::sibs_save_db_transaction( $transaction, $additional_info );
                Sibs_General_Models::sibs_save_db_postmeta( $transaction, $additional_info );

				// save credit card info
				if ( isset($payment_result['card']) && isset($payment_result['registrationId']) )
				{
					//get cards from user
					$credentials         				 = Sibs_General_Functions::sibs_get_credentials( $this->payment_id );
					$payment_parameters['registrations'] = Sibs_General_Models::sibs_get_db_registered_payment( $this->payment_group, $credentials );
					$cardExist = false;

					//if there is cards for the user
					if ($payment_parameters['registrations'] !== null){
						foreach ($payment_parameters['registrations'] as $key => $value) {
						
							// if the card dont exist 
							if ( $payment_parameters['registrations'][$key]['last4digits'] == $payment_result['card']['last4Digits'] 
								&& $payment_parameters['registrations'][$key]['brand'] == $payment_result['paymentBrand']
								&& $payment_parameters['registrations'][$key]['expiry_month'] == $payment_result['card']['expiryMonth']
								&& $payment_parameters['registrations'][$key]['expiry_year'] == $payment_result['card']['expiryYear']
								&& $payment_parameters['registrations'][$key]['holder'] == $payment_result['card']['holder']
								){
								$cardExist = true;
								break;
							}
						}

						// if the card does not exist, add
						if ($cardExist == false){
							$this->sibs_save_recurring_card_payment( $order_id, $payment_result);
						}
					} else {
						$this->sibs_save_recurring_card_payment( $order_id, $payment_result);
					}
				}	
			}


			// Added for Multibanco Email Payment Data
			public function sibs_add_multibanco_data_to_order_email( $order, $sent_to_admin, $plain_text, $email  ) {
				// Get the current payment gateway
				$payment_id = $this->payment_id;
				// Get the order payment gateway
				$payment_gateways = $order->get_payment_method();
				// Add the data only for multibanco
				if ( $payment_id == $payment_gateways && $payment_id == 'sibs_multibanco' &&  $payment_gateways == 'sibs_multibanco' ){
					
					$payment_data = Sibs_General_Models::sibs_get_db_transaction_log( $order->get_id() );
			
					$payment_data_detail = explode("|", $payment_data['additional_information']);
			
					if ( $payment_data_detail != null ){
						$pay_entity = $payment_data_detail[0];
						$pay_ref = $payment_data_detail[1];
						$pay_date = $payment_data_detail[2];
						$hook_usage = intval( $payment_data_detail[3] );
					}
					
					// Added for counting the number of times that the hook for email is called. 
					// The question here is that this hook will be called one time for each
					// gateway present, times the number of email senders, and so we make following
					// filter: The 1st time this hook is called for the user email, the 2nd time is 
					// for the user email again, the 3rd time is for the admin email and the 4th is 
					// for the admin email again. 
					// So we filter for one time for user email, and another for admin email, leading 
					// to the values showed bellow. 
					
					if ( $hook_usage > 0 && $hook_usage != 2 && $hook_usage != 3 ) {

						//log
						$log = new WC_Logger();
						$log_entry = print_r( $payment_id, true );
						$log->add( 'woocommerce-sibs-log', 'ADD MULTIBANCO EMAIL INFORMATION FOR : ' . $log_entry );

						// CSS style
						$styles = '<style>
						table.salesman-meta{width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;
							color: #737373; border: 1px solid #e4e4e4; margin-bottom:8px;}
						table.salesman-meta th, table.tracking-info td{text-align: left; border-top-width: 4px;
							color: #737373; border: 1px solid #e4e4e4; padding: 12px; width:50%;}
						table.salesman-meta td{text-align: left; border-top-width: 4px; color: #737373; border: 1px solid #e4e4e4; padding: 12px; width:50%;}
						</style>';
				
						// HTML Structure
						$html_output = '<h2>'.__('Payment Details', 'wc-sibs').'</h2>
						<table class="salesman-meta" cellspacing="0" cellpadding="6">';
				
						if( ! empty( $pay_entity ) ){
							$html_output .= '<tr class="sales-rep">
									<th>' . __( 'Payment Entity', 'wc-sibs' ) . '</th>
									<td>' . $pay_entity . '</td>
								</tr>';
						}
				
						if( ! empty( $pay_ref ) ){
							$html_output .= '<tr class="po-num">
									<th>' . __( 'Payment Reference' , 'wc-sibs' ) . '</th>
									<td>' . $pay_ref . '</td>
								</tr>';
						}
				
						if( ! empty( $pay_date ) ){
							$html_output .= '<tr class="po-num">
									<th>' . __( 'Payment Reference Expiration' , 'wc-sibs' ) . '</th>
									<td>' . date( 'Y-m-d H:i', strtotime( $pay_date )) . '</td>
								</tr>';
						}
				
						$html_output .= '</table><br>'; // HTML (end)
				
						// Output styles CSS + HTML
						echo $styles . $html_output;
						
					}
					
					$hook_usage = $hook_usage - 1;
					$additional_info = $pay_entity . '|' . $pay_ref . '|' . $pay_date . '|' . strval( $hook_usage );
					Sibs_General_Models::sibs_update_db_transaction_multibanco_usage( $order->get_id(), $additional_info );
				}
			}



			private function sibs_add_order_notes( $order_id, $status_name ) {

				$user = get_user_by( 'id', get_current_user_id() );
				/**
				 * $timezone = new DateTimeZone( wc_timezone_string() );
				 * date_default_timezone_set( wc_timezone_string() );
				 */

				$comments['order_id'] = $order_id;
				$comments['author']   = $user->display_name;
				$comments['email']    = $user->user_email;
				$comments['content']  = sprintf( __( 'Order status changed from %1$s to %2$s.', 'wc-sibs' ), $status_name['original'], $status_name['new']);

				Sibs_General_Models::sibs_add_db_order_notes( $comments );
			}


			public function sibs_get_merchant_info() {
				$merchant['transaction_mode'] = $this->settings['server_mode'];
				$merchant['ip_address']       = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : ''; // input var okay.
				$merchant['shop_version']     = WC()->version;
				$merchant['plugin_version']   = constant( 'SIBS_VERSION' );
				$merchant['client']           = 'SIBS';
				$merchant['merchant_id']      = get_option( 'sibs_general_merchant_no' );
				$merchant['shop_system']      = 'WooCommerce';
				$merchant['email']            = get_option( 'sibs_general_merchant_email' );
				$merchant['shop_url']         = get_option( 'sibs_general_shop_url' );

				return $merchant;
			}

		} // End of class Sibs_Payment_Gateway
	}// End if().


	function add_sibs_payments( $methods ) {
		$methods[] = 'Gateway_Sibs_CC';
		$methods[] = 'Gateway_Sibs_Mbway';
		$methods[] = 'Gateway_Sibs_Multibanco';

		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_sibs_payments' );
	foreach ( glob( dirname( __FILE__ ) . '/includes/gateways/**.php' ) as $filename ) {
		include_once $filename;
	}
}


add_action( 'wp_ajax_my_action', 'sibs_get_ajax_registered_payment' );


function sibs_get_ajax_registered_payment() {
	global $wpdb;

	$uid    = Sibs_General_Functions::sibs_get_request_value( 'user_id' );
	$payment_id = Sibs_General_Functions::sibs_get_request_value( 'payment_id' );

	if ( 'sibs_ccsaved' === $payment_id ) {
		$payment_group = 'CC';
	} elseif ( 'sibs_ddsaved' === $payment_id ) {
		$payment_group = 'DD';
	} elseif ( 'sibs_paypalsaved' === $payment_id ) {
		$payment_group = 'PAYPAL';
	}

	$registered_payment = $wpdb->get_results( $wpdb->prepare( "SELECT  * FROM {$wpdb->prefix}sibs_payment_information WHERE cust_id = %d AND payment_group = %s", $uid, $payment_group ), ARRAY_A );
	echo wp_json_encode( $registered_payment );

	wp_die();
}


/**
 * hook to add the javascript file
 */
add_action( 'admin_enqueue_scripts', 'sibs_my_function_to_add_the_js_file' );

/**
 * Add javascript for capture button
 */
function sibs_my_function_to_add_the_js_file()
{

	wp_enqueue_script( 'my_button_script', plugin_dir_url(__FILE__) ."assets/js/capture_button.js", array('jquery'), NULL, true );

	// send the admin ajax url to the script
	wp_localize_script( 'my_button_script', 'mb_script_var', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}


/**
 * Override new order email
 */
add_filter( 'woocommerce_locate_template', 'sibs_override_woocommerce_template', 10, 3 );

function sibs_override_woocommerce_template( $template, $template_name, $template_path ) {

	$template_directory = untrailingslashit( dirname( __FILE__ )  );

	$path = $template_directory . "/" . $template_name;

    return file_exists( $path ) ? $path : $template;
}


/**
 * Add new order email header
 */
add_action( 'woocommerce_email_order_details', 'sibs_email_order_details', 10, 4 );

function sibs_email_order_details( $order, $sent_to_admin, $plain_text, $email ) {
    $log = new WC_Logger();
    $log_entry = isset($email->id) ? print_r($email->id, true) : json_encode($email);
    $log->add( 'woocommerce-sibs-log', 'ORDER DETAILS EMAIL: ' . $log_entry);

	//if its not send to admin
	if ( ! $sent_to_admin && isset($email->id) ) {
		if ( $email->id == 'customer_processing_order') {
	
			$isSibsOrder = '';
			$ID = $order->get_id();
	
			//getting order Object 
			$orderObject = wc_get_order( $ID ); 
	
			//Search if this order is a SIBS order 
			$transaction_log = Sibs_General_Models::sibs_get_db_transaction_log( $ID );
			$payment_id = $transaction_log['payment_id'];
			if ( $isSibsOrder !== strpos( $payment_id, 'sibs' ) ) {
				$isSibsOrder = true;
			}
	
			if ( $isSibsOrder ) {
	
				//get order gateway
				$payment_gateways = $orderObject->get_payment_method();

				//log
                $log_entry = print_r( $payment_gateways, true );
                $log->add( 'woocommerce-sibs-log', 'ADD THANK YOU MESSAGE TO ORDER : ' . $log_entry );
	
				//if ( $payment_gateways == 'sibs_mbway' ){
				//	echo '<p> ' . __( 'Your order has been received and will be processed after acceptance in MBWAY app. You only have 4 minutes to accept the payment in your MBWAY app. Your order details are shown below for your reference:', 'wc-sibs' );
				//} else
				// if ( $payment_gateways == 'sibs_multibanco' ){
				//	echo '<p> ' . __( 'Your order has been received and will be processed after multibanco payment completion. Your order details are shown below for your reference:', 'wc-sibs' );
				//}
			}
		}
	}
}

add_action( 'sibs_cancel_onhold_orders', 'sibs_cancel_onhold_orders' );
function sibs_cancel_onhold_orders() {
    global $pagenow, $post_type;

	$log = new WC_Logger();
	$log_entry = print_r( 'start' , true);
	$log->add( 'woocommerce-sibs-log', 'sibs cancel order  inicio : ' . $log_entry );


	$held_duration = get_option( 'woocommerce_hold_stock_minutes' );

	if ( $held_duration < 1 || 'yes' !== get_option( 'woocommerce_manage_stock' ) ) {
		return;
	}


    $seconds_delay    = $held_duration *  60;
    $today      = strtotime( date('Y-m-d H:i:s') );

    // Get unpaid orders
    $unpaid_orders = (array) wc_get_orders( array(
        'limit'        => -1,
        'status'       => 'wc-on-hold',
        'date_created' => '<' . ( $today - $seconds_delay ),
	) );

    if ( sizeof($unpaid_orders) > 0 ) {
        $cancelled_text = __("The order was cancelled due to no payment from customer.", "woocommerce");

        // Loop through orders
        foreach ( $unpaid_orders as $unpaid_order ) {
			$log = new WC_Logger();
			$log_entry = print_r( $unpaid_order->get_id() , true);
			$log->add( 'woocommerce-sibs-log', 'sibs cancel on-hold order id : ' . $log_entry );


			if ( $unpaid_order->payment_method == 'sibs_mbway'  ||  $unpaid_order->payment_method ==  'sibs_multibanco'  ) {
				$unpaid_order->update_status( 'cancelled', $cancelled_text );
			}


		}



    }


}

