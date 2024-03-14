<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PPEC
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Paypal_Express
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Paypal_Express {
	private $payer_details;

	public function __construct() {
		add_filter( 'wfacp_add_to_cart_init', [ $this, 'check_ppec_checkout_enable' ], 10 );
		add_filter( 'wfacp_changed_default_woocommerce_page', [ $this, 'check_ppec_checkout_enable' ], 10 );
		add_filter( 'wfacp_form_section', [ $this, 'skip_product_switching_section' ] );
		add_action( 'woocommerce_checkout_init', [ $this, 'woocommerce_checkout_init' ] );
		add_filter( 'wfacp_autopopulate_fields', [ $this, 'stop_auto_puluation_fields' ] );
		add_filter( 'wfacp_default_values', [ $this, 'merge_ppec_data' ], 11, 2 );
		add_filter( 'wfacp_form_template', [ $this, 'replace_form_template' ] );
		add_action( 'woocommerce_checkout_process', array( $this, 'copy_change_in_checkout_details' ), 99 );
		add_filter( 'wfacp_checkout_fields', [ $this, 'woocommerce_checkout_fields' ], 99 );

		add_filter( 'wfacp_layout_9_active_progress_bar', [ $this, 'active_progress_bar' ], 10, 2 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'attach_paypal_btn' ], 999 );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'attach_paypal_btn' ], 999 );
		add_action( 'wfacp_internal_css', [ $this, 'paypal_internal_css' ] );
		add_filter( 'wfacp_css_js_removal_paths', [ $this, 'remove_some_js' ], 15 );
		add_filter( 'wfacp_display_quantity_increment', [ $this, 'hide_quantity_switcher' ] );
		add_filter( 'wfacp_mini_cart_enable_delete_item', [ $this, 'hide_delete_icon' ] );


	}

	public function active_progress_bar( $active, $step ) {
		if ( $step != '' && $step != null ) {
			if ( ! empty( $_GET['woo-paypal-return'] ) && ! empty( $_GET['token'] ) && ! empty( $_GET['PayerID'] ) ) {
				$paypalsession = (array) WC()->session->get( 'paypal' );
				if ( in_array( $_GET['woo-paypal-return'], $paypalsession ) && in_array( $_GET['token'], $paypalsession ) && in_array( $_GET['PayerID'], $paypalsession ) ) {
					$active = 'wfacp_bred_active wfacp_bred_visited ppec_express_checkout_m express_paypal_wrap';
				}
			}
		}

		return $active;
	}


	public function woocommerce_checkout_init() {

		if ( WFACP_Common::is_theme_builder() ) {

			return;
		}

		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {
			add_action( 'woocommerce_before_checkout_form', array( wc_gateway_ppec()->checkout, 'paypal_billing_details' ) );
			add_action( 'woocommerce_before_checkout_form', array( wc_gateway_ppec()->checkout, 'paypal_shipping_details' ) );
		}

		if ( WFACP_Common::is_customizer() && function_exists( 'wc_gateway_ppec' ) ) {
			remove_action( 'wp_enqueue_scripts', [ wc_gateway_ppec()->cart, 'enqueue_scripts' ] );
		}
	}


	public function merge_ppec_data( $value, $field_index ) {
		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {
			try {
				$checkout_details = wc_gateway_ppec()->checkout->get_checkout_details();
				if ( 'billing_first_name' === $field_index ) {
					$value = $checkout_details->payer_details->first_name;
				}
				if ( 'billing_last_name' === $field_index ) {
					$value = $checkout_details->payer_details->last_name;
				}
				if ( 'billing_email' === $field_index ) {
					$value = $checkout_details->payer_details->email;
				}
				if ( 'billing_city' === $field_index && isset( $checkout_details->payer_details->city ) ) {
					$value = $checkout_details->payer_details->city;
				}
				if ( 'billing_state' === $field_index && isset( $checkout_details->payer_details->state ) ) {
					$value = $checkout_details->payer_details->state;
				}
				if ( 'billing_country' === $field_index ) {
					$value = $checkout_details->payer_details->country;
				}
			} catch ( PayPal_API_Exception $e ) {

			}
		}

		return $value;
	}

	/**
	 * @param $status  bool
	 * @param $instance WFACP_public
	 */
	public function check_ppec_checkout_enable() {

		if ( is_admin() ) {
			return;
		}

		if ( ! function_exists( 'wc_gateway_ppec' ) ) {
			return;
		}
		if ( is_null( wc_gateway_ppec()->checkout ) ) {
			return;
		}

		if ( ! wc_gateway_ppec()->checkout instanceof WC_Gateway_PPEC_Checkout_Handler ) {
			return;
		}
		if ( ! method_exists( wc_gateway_ppec()->checkout, 'has_active_session' ) ) {
			return;
		}

		if ( WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {
			WFACP_Core()->public->is_checkout_override             = true;
			WFACP_Core()->public->is_paypal_express_active_session = true;

			try {
				$checkout_details                            = wc_gateway_ppec()->checkout->get_checkout_details();
				WFACP_Core()->public->paypal_billing_address = $checkout_details->payer_details->billing_address;
				$this->payer_details                         = (array) $checkout_details->payer_details;
				WFACP_Core()->public->shipping_details       = wc_gateway_ppec()->checkout->get_mapped_shipping_address( $checkout_details );

				$this->merge_billing_details( $checkout_details );
			} catch ( PayPal_API_Exception $e ) {


			}
			add_action( 'wfacp_express_checkout_paypal_billing_address_not_present', [ $this, 'print_html' ] );
		}
	}

	private function merge_billing_details( $checkout_details ) {
		$instance = wfacp_template();
		if ( ! is_null( $instance ) && $instance->have_billing_address() ) {

			$array_keys = array(
				'first_name',
				'last_name',
				'company',
				'address_1',
				'address_2',
				'city',
				'state',
				'postcode',
				'country',
				'phone',
				'email',
			);
			foreach ( $array_keys as $key ) {
				$temp_data = WC()->checkout->get_value( 'billing_' . $key );
				if ( ! empty( $temp_data ) ) {
					WFACP_Core()->public->billing_details[ $key ] = $temp_data;
				} else {
					if ( isset( WFACP_Core()->public->shipping_details[ $key ] ) ) {
						WFACP_Core()->public->billing_details[ $key ] = WFACP_Core()->public->shipping_details[ $key ];
					}

				}
			}
		} else {
			WFACP_Core()->public->billing_details = wc_gateway_ppec()->checkout->get_mapped_billing_address( $checkout_details );
		}
		if ( empty( WFACP_Core()->public->billing_details['email'] ) ) {
			WFACP_Core()->public->billing_details[ $key ] = $checkout_details->payer_details->email;
		}
	}

	public function stop_auto_puluation_fields( $status ) {
		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 ) {
			if ( ! is_admin() && wc_gateway_ppec()->checkout->has_active_session() ) {
				$status = 'no';
			}
		}

		return $status;
	}

	public function skip_product_switching_section( $section ) {
		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 ) {
			if ( wc_gateway_ppec()->checkout->has_active_session() ) {
				foreach ( $section['fields'] as $field_key => $field ) {
					if ( isset( $field['id'] ) && 'product_switching' == $field['id'] ) {
						unset( $section['fields'][ $field_key ] );
						break;
					}
				}
			}
		}

		return $section;
	}


	public function replace_form_template( $template ) {

		if ( ! isset( $_REQUEST['woo-paypal-return'] ) || ! isset( $_REQUEST['token'] ) || ! isset( $_REQUEST['PayerID'] ) ) {
			return $template;
		}

		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 ) {

			if ( wc_gateway_ppec()->checkout->has_active_session() ) {
				$template = WFACP_TEMPLATE_COMMON . '/form-express-checkout.php';
				add_action( 'wfacp_internal_css', [ $this, 'paypal_custom_style' ] );

			}
		}

		return $template;
	}

	public function paypal_custom_style() {

		if ( ! function_exists( 'wc_gateway_ppec' ) ) {
			return;
		}
		if ( is_null( wc_gateway_ppec()->checkout ) ) {
			return;
		}

		if ( ! wc_gateway_ppec()->checkout instanceof WC_Gateway_PPEC_Checkout_Handler ) {
			return;
		}
		if ( ! method_exists( wc_gateway_ppec()->checkout, 'has_active_session' ) ) {
			return;
		}

		if ( function_exists( 'wfacp_template' ) ) {
			$instance = wfacp_template();
		}

		if ( is_null( $instance ) ) {
			return;
		}
		$px = $instance->get_template_type_px();

		if ( ! isset( $px ) || $px == '' ) {
			return;
		}
		?>
        <style>
            .woocommerce-account-fields {
                margin: 0 -<?php echo $px; ?>px;
            }

        </style>
		<?php


	}

	public function copy_change_in_checkout_details() {
		if ( ! isset( $_POST['payment_method'] ) || ( 'ppec_paypal' !== $_POST['payment_method'] ) ) {
			return;
		}
		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {
			$posted_data = WC()->session->get( 'wfacp_posted_data', [] );
			if ( ! empty( $posted_data ) ) {
				if ( isset( $posted_data['shipping_country'] ) ) {
					$posted_data['ship_to_different_address'] = 1;
				}

				foreach ( $posted_data as $key => $value ) {
					if ( isset( $_POST[ $key ] ) ) {
						$_POST[ $key ] = $value;
					}
				}
			}
		}
	}

	public function woocommerce_checkout_fields( $field ) {

		if ( ! isset( $_POST['payment_method'] ) || ( 'ppec_paypal' !== $_POST['payment_method'] ) ) {
			return $field;
		}
		$available_fields = [ 'country', 'city', 'state', 'postcode', 'address_1', 'address_2' ];

		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {

			if ( isset( $field['billing'] ) ) {
				foreach ( $available_fields as $val ) {
					$b_key = 'billing_' . $val;
					if ( isset( $field['billing'][ $b_key ] ) && isset( $field['billing'][ $b_key ]['required'] ) ) {
						unset( $field['billing'][ $b_key ]['required'] );
					}
				}
			}
			if ( isset( $field['shipping'] ) ) {
				foreach ( $available_fields as $val ) {
					$s_key = 'shipping_' . $val;
					if ( isset( $field['shipping'][ $s_key ] ) && isset( $field['shipping'][ $s_key ]['required'] ) ) {
						unset( $field['shipping'][ $s_key ]['required'] );
					}
				}
			}
		}

		return $field;
	}

	public function attach_paypal_btn() {

		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 ) {

			add_action( 'woocommerce_review_order_after_submit', function () {
				wp_enqueue_script( 'wc-gateway-ppec-smart-payment-buttons' );
				echo '<div id="woo_pp_ec_button_checkout"></div>';
			} );
		}
	}

	public function paypal_internal_css( $selected_template_slug ) {

		if ( $selected_template_slug == 'layout_9' ) {
			?>

            <style>

                .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.wfacp_bred_active.wfacp_bred_visited.express_paypal_wrap:nth-last-child(2):before {
                    background: #000;
                }

                .wfacp_custom_breadcrumb .wfacp_steps_sec ul li.wfacp_bred_active.wfacp_bred_visited.express_paypal_wrap:before {
                    background: #fff;
                }


            </style>
			<?php
		}
	}


	public function remove_ppec_when_smart_button_enable( $gateway ) {
		if ( isset( $gateway['ppec_paypal'] ) ) {
			unset( $gateway['ppec_paypal'] );
		}

		return $gateway;
	}

	public function remove_some_js( $paths ) {
		//Remved Woo-postnl JS due Payment Gateway stuck in loop
		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {
			$paths[] = 'js/wcmp-frontend';
		}

		return $paths;
	}

	public function hide_quantity_switcher( $status ) {
		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {
			$status = false;
		}

		return $status;
	}

	public function hide_delete_icon( $status ) {
		if ( function_exists( 'wc_gateway_ppec' ) && WFACP_Common::get_id() > 0 && wc_gateway_ppec()->checkout->has_active_session() ) {
			$status = false;
		}

		return $status;
	}

	public function print_html() {
		?>
        <p>
            <strong><?php _e( 'Full Name', 'woofunnels-aero-checkout' ); ?></strong> <?php echo esc_html( $this->payer_details ['first_name'] . ' ' . $this->payer_details ['last_name'] ); ?>
        </p>
		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Paypal_Express(), 'ppec' );
