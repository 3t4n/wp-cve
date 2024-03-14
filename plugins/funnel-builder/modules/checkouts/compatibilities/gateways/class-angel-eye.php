<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Angel_Eye {
	public function __construct() {
		add_action( 'wp', [ $this, 'detect_canceled_url' ], 15 );
		add_filter( 'wfacp_skip_add_to_cart', [ $this, 'check_angel_eye_checkout_enable' ], 10, 2 );
		add_filter( 'wfacp_form_template', [ $this, 'replace_form_template' ] );
		add_filter( 'wfacp_checkout_fields', [ $this, 'woocommerce_checkout_fields' ], 99 );
		add_action( 'wfacp_checkout_preview_form_start', [ $this, 'enable_billing_and_shipping_address' ] );
		add_filter( 'wfacp_smart_buttons', [ $this, 'add_buttons' ], 15 );
		add_action( 'wfacp_smart_button_container_paypal_express', [ $this, 'add_paypal_buttons' ] );
		add_filter( 'angelleye_woocommerce_express_checkout_set_express_checkout_request_args', [ $this, 'add_aero_parameter_in_paypal_request' ] );
		add_filter( 'woocommerce_get_checkout_url', [ $this, 'change_checkout_url' ], 100 );
		add_filter( 'wfacp_do_not_check_for_global_checkout', [ $this, 'redirect_proper_url' ] );
		add_action( 'woocommerce_before_checkout_process', [ $this, 'make_session_empty' ] );
		add_filter( 'wfacp_enable_hashtag_for_multistep_checkout', [ $this, 'disabled_hashtag_form_multistep_checkout' ] );
		add_filter( 'wfacp_css_js_removal_paths', [ $this, 'remove_some_js' ], 15 );
		add_filter( 'wfacp_display_quantity_increment', [ $this, 'hide_quantity_switcher' ] );
		add_filter( 'wfacp_mini_cart_enable_delete_item', [ $this, 'hide_delete_icon' ] );
		add_action( 'wfacp_after_template_found', [ $this, 'v2_action' ] );
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'update_aero_field' ], 11, 3 );
		add_filter( 'wfacp_mark_conversion_post_id', [ $this, 'update_conversion_post_id' ], 10, 2 );

		add_action( 'wfacp_woocommerce_review_order_before_submit', [ $this, 'handle_general_list_express_button' ] );

	}

	public function v2_action() {

		add_filter( 'woocommerce_api_request_url', [ $this, 'attach_aero_parameter' ], 10, 2 );
	}

	/**
	 * @param $status  bool
	 * @param $instance WFACP_public
	 */
	public function check_angel_eye_checkout_enable( $status, $instance ) {
		if ( ! is_admin() ) {

			$paypal_express_checkout = WC()->session->get( 'paypal_express_checkout' );
			if ( isset( $paypal_express_checkout ) && isset( $paypal_express_checkout['ExpresscheckoutDetails'] ) ) {
				$instance->is_checkout_override = true;
				$status                         = true;
			}
		}

		return $status;
	}


	public function replace_form_template( $template ) {
		$paypal_express_checkout = WC()->session->get( 'paypal_express_checkout' );
		if ( isset( $paypal_express_checkout ) && is_array( $paypal_express_checkout ) && isset( $paypal_express_checkout['ExpresscheckoutDetails'] ) ) {
			WFACP_Core()->public->paypal_billing_address           = true;
			WFACP_Core()->public->paypal_shipping_address          = true;
			WFACP_Core()->public->is_paypal_express_active_session = true;

			if ( isset( $paypal_express_checkout['shipping_details'] ) ) {
				WFACP_Core()->public->shipping_details = $paypal_express_checkout['shipping_details'];

				$this->merge_billing_details( $paypal_express_checkout );
			}
			add_action( 'wfacp_express_checkout_paypal_billing_address_not_present', [ $this, 'print_html' ] );
			$template = WFACP_TEMPLATE_COMMON . '/form-express-checkout.php';
		}

		return $template;
	}

	private function merge_billing_details( $paypal_express_checkout ) {
		$instance = wfacp_template();
		if ( ! is_null( $instance ) && $instance->have_billing_address() ) {
			$array_keys = array_keys( $paypal_express_checkout['shipping_details'] );
			foreach ( $array_keys as $key ) {
				$temp_data = WC()->checkout->get_value( 'billing_' . $key );
				if ( ! empty( $temp_data ) ) {
					WFACP_Core()->public->billing_details[ $key ] = $temp_data;
				} else {
					WFACP_Core()->public->billing_details[ $key ] = WFACP_Core()->public->shipping_details[ $key ];
				}
			}
		} else {
			WFACP_Core()->public->billing_details = $paypal_express_checkout['shipping_details'];
		}

	}

	public function enable_billing_and_shipping_address() {

		$paypal_express_checkout = WC()->session->get( 'paypal_express_checkout' );
		if ( isset( $paypal_express_checkout ) && is_array( $paypal_express_checkout ) && isset( $paypal_express_checkout['ExpresscheckoutDetails'] ) ) {
			$instance                        = WFACP_Core()->template_loader->get_template_ins();
			$instance->have_billing_address  = true;
			$instance->have_shipping_address = true;
		}

	}

	public function woocommerce_checkout_fields( $field ) {
		if ( wp_doing_ajax() && did_action( 'woocommerce_checkout_process' ) > 0 && isset( $_POST['payment_method'] ) && 'paypal_express' == $_POST['payment_method'] ) {
			return $field;
		}

		$available_fields = [ 'email', 'first_name', 'last_name', 'country', 'city', 'state', 'postcode', 'address_1', 'address_2' ];

		if ( wp_doing_ajax() && ! is_null( WC()->session ) && WFACP_Common::get_id() > 0 && ! is_null( WC()->session->get( 'paypal_express_checkout', null ) ) ) {

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

	public function add_buttons( $buttons ) {

		if ( ! class_exists( 'Angelleye_PayPal_Express_Checkout_Helper' ) ) {

			return $buttons;
		}
		$settings = AngellEYE_Utility::angelleye_get_pre_option( false, 'woocommerce_paypal_express_settings' );
		if ( ! is_array( $settings ) || 'yes' != $settings['enabled'] || 'regular' == $settings['show_on_checkout'] || 'no' == $settings['show_on_checkout'] ) {
			return $buttons;
		}

		//VERSION_PFW
		add_action( 'wfacp_internal_css', function () {
			$instance = Angelleye_PayPal_Express_Checkout_Helper::instance();
			remove_action( 'woocommerce_before_checkout_form', [ $instance, 'checkout_message' ], 5 );
		} );
		$buttons['paypal_express'] = [
			'iframe' => true,
			'name'   => $settings['title'],
		];
		if ( isset( $buttons['ppec_paypal'] ) ) {
			unset( $buttons['ppec_paypal'] );
		}

		return $buttons;
	}

	public function add_paypal_buttons() {
		?>
        <style>
            .woocommerce_paypal_ec_checkout_message {
                display: none;
            }
            div#wfacp_smart_button_paypal_express #wc-paypal_express-new-payment-method {
                width: auto !important;
            }
        </style>
		<?php
		$instance = Angelleye_PayPal_Express_Checkout_Helper::instance();

		$instance->checkout_message();
	}

	public function change_checkout_url( $url ) {
		if ( ! class_exists( 'Angelleye_PayPal_Express_Checkout_Helper' ) ) {

			return $url;
		}
		$settings = AngellEYE_Utility::angelleye_get_pre_option( false, 'woocommerce_paypal_express_settings' );


		if ( ! is_null( WC()->session ) ) {
			WC()->session->set( 'wfacp_angell_eye_error', '' );
		}

		if ( isset( $_POST['pp_action'] ) && 'set_express_checkout' == $_POST['pp_action'] && isset( $_POST['wfacp_is_checkout_override'] ) ) {
			if ( 'yes' == $_POST['wfacp_is_checkout_override'] ) {
				if ( isset( $_POST['wfacp_embed_form_page_id'] ) && '' !== $_POST['wfacp_embed_form_page_id'] ) {
					$aero_id = absint( $_POST['wfacp_embed_form_page_id'] );
					$url     = get_the_permalink( $aero_id );
				} else {
					$url = wc_get_checkout_url();
				}
			} else {
				$aero_id = $_POST['wfacp_id'];
				if ( isset( $_POST['wfacp_embed_page_id'] ) && '' !== $_POST['wfacp_embed_page_id'] ) {
					$aero_id = absint( $_POST['wfacp_embed_form_page_id'] );
				}
				$url = get_the_permalink( $aero_id );
			}
			//some error triggered in aero checkout
			if ( ! is_null( WC()->session ) && wc_notice_count() > 0 ) {
				WC()->session->set( 'wfacp_angell_eye_error', $url );
			}

		}

		if ( isset( $_REQUEST['pp_action'] ) && 'get_express_checkout_details' == $_REQUEST['pp_action'] && isset( $_REQUEST['wfacp_is_checkout_override'] ) && 'no' == $_REQUEST['wfacp_is_checkout_override'] ) {

			$aero_id = $_REQUEST['wfacp_id'];
			if ( isset( $_REQUEST['wfacp_embed_page_id'] ) && '' !== $_REQUEST['wfacp_embed_page_id'] ) {
				$aero_id = $_REQUEST['wfacp_embed_page_id'];
			}


			$notice_count = wc_notice_count();
			if ( $notice_count > 0 || ( isset( $settings['skip_final_review'] ) && 'no' == $settings['skip_final_review'] ) ) {
				$url = get_the_permalink( $aero_id );
				if ( ! is_null( WC()->session ) ) {
					if ( $notice_count > 0 ) {
						WC()->session->set( 'wfacp_angell_eye_error', $url );
					}
				}
			}
		}

		return $url;
	}

	public function add_aero_parameter_in_paypal_request( $request ) {

		if ( isset( $_REQUEST['wfacp_id'] ) && $_REQUEST['wfacp_id'] > 0 ) {

			$aero_id   = $_REQUEST['wfacp_id'];
			$is_global = $_REQUEST['wfacp_is_checkout_override'];

			$embed_page_id = '';
			if ( ! empty( $_REQUEST['wfacp_embed_form_page_id'] ) ) {
				$embed_page_id = absint( $_REQUEST['wfacp_embed_form_page_id'] );
			}
			$request['SECFields']['cancelurl'] = add_query_arg( [
				'wfacp_canceled'             => true,
				'wfacp_is_checkout_override' => $is_global,
				'wfacp_id'                   => $aero_id,
				'wfacp_embed_page_id'        => $embed_page_id,
			], $request['SECFields']['cancelurl'] );
			$request['SECFields']['returnurl'] = add_query_arg( [
				'wfacp_id'                   => $aero_id,
				'wfacp_is_checkout_override' => $is_global,
				'wfacp_embed_page_id'        => $embed_page_id,
			], $request['SECFields']['returnurl'] );
		}

		return $request;
	}

	public function attach_aero_parameter( $api_request_url, $request ) {

		if ( 'WC_Gateway_PayPal_Express_AngellEYE' == $request ) {

			$arg             = [ 'wfacp_id' => WFACP_Common::get_id(), 'wfacp_is_checkout_override' => WFACP_Core()->public->is_checkout_override() ? 'yes' : 'no', ];
			$api_request_url = add_query_arg( $arg, $api_request_url );

		}

		return $api_request_url;
	}

	public function detect_canceled_url() {
		if ( isset( $_REQUEST['wfacp_canceled'] ) ) {
			if ( ! is_null( WC()->session ) ) {
				WC()->session->set( 'paypal_express_checkout', null );
			}
			// Cancel Url is set
			$is_global = $_REQUEST['wfacp_is_checkout_override'];
			$wfacp_id  = $_REQUEST['wfacp_id'];
			// User Make payment from global checkout page

			if ( 'yes' == $is_global ) {
				wp_redirect( wc_get_checkout_url() );
			} else {
				if ( isset( $_REQUEST['wfacp_embed_page_id'] ) && '' !== $_REQUEST['wfacp_embed_page_id'] ) {
					$wfacp_id = $_REQUEST['wfacp_embed_page_id'];
				}

				wp_redirect( get_the_permalink( $wfacp_id ) );
			}
			exit;
		}
	}


	public function redirect_proper_url( $status ) {

		if ( ! is_null( WC()->session ) ) {

			$url = WC()->session->get( 'wfacp_angell_eye_error', '' );
			if ( '' !== $url ) {
				WC()->session->set( 'wfacp_angell_eye_error', '' );
				wp_redirect( $url );
				exit;
			}
		}


		return $status;
	}

	public function make_session_empty() {
		remove_filter( 'woocommerce_get_checkout_url', [ $this, 'change_checkout_url' ], 100 );
		if ( ! is_null( WC()->session ) ) {
			WC()->session->set( 'wfacp_angell_eye_error', '' );
		}
	}

	public function disabled_hashtag_form_multistep_checkout( $status ) {
		if ( ! defined( 'VERSION_PFW' ) ) {
			return $status;
		}

		if ( version_compare( VERSION_PFW, '2.1.10', '>' ) ) {

			$status = 'no';
		}


		return $status;
	}


	public function remove_some_js( $paths ) {
		//Remved Woo-postnl JS due Payment Gateway stuck in loop
		if ( ! is_null( WC()->session ) && WFACP_Common::get_id() > 0 && ! is_null( WC()->session->get( 'paypal_express_checkout', null ) ) ) {
			$paths[] = 'js/wcmp-frontend';
		}

		return $paths;
	}

	public function hide_quantity_switcher( $status ) {
		if ( ! is_null( WC()->session ) && WFACP_Common::get_id() > 0 && ! is_null( WC()->session->get( 'paypal_express_checkout', null ) ) ) {
			$status = false;
		}

		return $status;
	}

	public function hide_delete_icon( $status ) {
		if ( ! is_null( WC()->session ) && WFACP_Common::get_id() > 0 && ! is_null( WC()->session->get( 'paypal_express_checkout', null ) ) ) {
			$status = false;
		}

		return $status;
	}

	public function print_html() {
		?>
        <p>
            <strong><?php _e( 'Full Name', 'woofunnels-aero-checkout' ); ?></strong> <?php echo esc_html( WFACP_Core()->public->billing_details['first_name'] . ' ' . WFACP_Core()->public->billing_details['last_name'] ); ?>
        </p>
		<?php
	}


	/**
	 * @param $order_id
	 * @param $posted_data
	 * @param $order WC_Order
	 *
	 * @return void
	 */
	public function update_aero_field( $order_id, $posted_data, $order ) {
		if ( ! class_exists( 'AngellEYE_Gateway_Paypal' ) ) {
			return;
		}

		$http_referer = [];

		if ( isset( $posted_data['_wp_http_referer'] ) ) {
			parse_str( $posted_data['_wp_http_referer'], $http_referer );
		}

		$wfacp_id = isset( $posted_data['_wfacp_post_id'] ) ? $posted_data['_wfacp_post_id'] : 0;
		if ( $wfacp_id === 0 ) {
			$wfacp_id = isset( $posted_data['wfacp_post_id'] ) ? $posted_data['wfacp_post_id'] : 0;
		}
		$payment_request_type = isset( $posted_data['payment_method'] ) ? $posted_data['payment_method'] : '';
		$override             = isset( $http_referer['wfacp_is_checkout_override'] ) ? $http_referer['wfacp_is_checkout_override'] : '';

		if ( $wfacp_id === 0 ) {
			$wfacp_id = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
		}
		if ( $override === '' ) {
			$override = filter_input( INPUT_GET, 'wfacp_is_checkout_override', FILTER_UNSAFE_RAW );
		}

		if ( ! is_null( $wfacp_id ) && ! is_null( $payment_request_type ) && ( 'paypal_express' === $payment_request_type ) ) {

			$order->update_meta_data( '_wfacp_post_id', $wfacp_id );

			if ( ! is_null( $override ) ) {
				if ( 'yes' === $override ) {
					$link = wc_get_checkout_url();
				} else {
					$link = get_the_permalink( $wfacp_id );
				}
				if ( ! empty( $link ) ) {
					$order->update_meta_data( '_wfacp_source', $link );
				}
			}
			$order->save();
		}
	}

	// this function only run when Order created via paypal express
	public function update_conversion_post_id( $post_id, $posted_data ) {

		if ( ! class_exists( 'AngellEYE_Gateway_Paypal' ) ) {
			return $post_id;
		}

		$http_referer = [];
		if ( isset( $posted_data['_wp_http_referer'] ) ) {
			parse_str( $posted_data['_wp_http_referer'], $http_referer );
		}

		$wfacp_id = isset( $posted_data['_wfacp_post_id'] ) ? $posted_data['_wfacp_post_id'] : 0;
		if ( $wfacp_id === 0 ) {
			$wfacp_id = isset( $posted_data['wfacp_post_id'] ) ? $posted_data['wfacp_post_id'] : 0;
		}

		$payment_request_type = isset( $posted_data['payment_method'] ) ? $posted_data['payment_method'] : '';
		$override             = isset( $http_referer['wfacp_is_checkout_override'] ) ? $http_referer['wfacp_is_checkout_override'] : '';

		if ( $wfacp_id === 0 ) {
			$wfacp_id = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
		}
		if ( $override === '' ) {
			$override = filter_input( INPUT_GET, 'wfacp_is_checkout_override', FILTER_UNSAFE_RAW );
		}

		if ( ! is_null( $wfacp_id ) && ! is_null( $payment_request_type ) && ( 'paypal_express' === $payment_request_type ) ) {
			if ( ! is_null( $override ) ) {
				if ( 'yes' === $override ) {
					$link = wc_get_checkout_url();
				} else {
					$link = get_the_permalink( $wfacp_id );
				}
				if ( ! empty( $link ) ) {
					return $wfacp_id;
				}
			}
		}

		return $post_id;

	}

	public function handle_general_list_express_button() {
		if ( ! class_exists( 'Angelleye_PayPal_Express_Checkout_Helper' ) ) {
			return;
		}
		$instance = Angelleye_PayPal_Express_Checkout_Helper::instance();
		if ( ! property_exists( $instance, 'checkout_page_disable_smart_button' ) || ! property_exists( $instance, 'enable_in_context_checkout_flow' ) ) {
			return;
		}
		if ( $instance->checkout_page_disable_smart_button == false && $instance->enable_in_context_checkout_flow == 'yes' ) {

			$template = wfacp_template();
			if ( is_null( $template ) ) {
				return;
			}

			if ( $template->get_step_count() > 1 ) {
				remove_action( 'woocommerce_review_order_after_submit', array( $instance, 'angelleye_display_paypal_button_checkout_page' ) );
				add_action( 'wfacp_woocommerce_review_order_before_submit', array( $instance, 'angelleye_display_paypal_button_checkout_page' ), 20 );
			} else {
				remove_action( 'woocommerce_review_order_after_submit', array( $instance, 'angelleye_display_paypal_button_checkout_page' ) );
				add_action( 'wfacp_woocommerce_review_order_after_submit', array( $instance, 'angelleye_display_paypal_button_checkout_page' ) );
			}


		}

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Angel_Eye(), 'angel_eye' );



