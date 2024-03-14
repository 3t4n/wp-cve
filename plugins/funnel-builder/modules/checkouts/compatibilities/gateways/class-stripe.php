<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WFACP_Stripe_GPAY_AND_APAY' ) ) {
    #[AllowDynamicProperties] 

  class WFACP_Stripe_GPAY_AND_APAY {
        public function __construct() {
            add_filter( 'wfacp_smart_buttons', [ $this, 'add_buttons' ] );
            add_action( 'wfacp_smart_button_container_stripe_gpay_apay', [ $this, 'add_stripe_gpay_apay_buttons' ] );
            add_filter( 'wfacp_mark_conversion_post_id', [ $this, 'update_conversion_post_id' ], 10, 1 );
            add_action( 'wfacp_after_checkout_page_found', [ $this, 'force_add_woocommerce_checkout_shortcode' ], 99 );
            add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );
        }

		public function add_buttons( $buttons ) {

			if ( ! class_exists( 'WC_Stripe_Payment_Request' ) ) {
				return $buttons;
			}
			if ( true == apply_filters( 'wfacp_disabled_google_apple_pay_button_on_desktop', false, $buttons ) ) {


				if ( ! class_exists( 'WFACP_Mobile_Detect' ) ) {
					return $buttons;
				}

				$detect = WFACP_Mobile_Detect::get_instance();
				if ( ! $detect->isMobile() || empty( $detect ) ) {
					return $buttons;
				}
				add_filter( 'wfacp_template_localize_data', [ $this, 'set_local_data' ] );
			}
			$settings = get_option( 'woocommerce_stripe_settings', array() );
			// Checks if Payment Request is enabled.
			if ( ! isset( $settings['payment_request'] ) || 'yes' !== $settings['payment_request'] ) {
				return $buttons;
			}

			if ( defined( 'WC_STRIPE_VERSION' ) && version_compare( WC_STRIPE_VERSION, '5.5.0', '<' ) ) {
				add_filter( 'wc_stripe_show_payment_request_on_checkout', '__return_true' );
			}

			$instance = WC_Stripe_Payment_Request::instance();
			remove_action( 'woocommerce_checkout_before_customer_details', [ $instance, 'display_payment_request_button_html' ], 1 );
			remove_action( 'woocommerce_checkout_before_customer_details', [ $instance, 'display_payment_request_button_separator_html' ], 2 );

			$buttons['stripe_gpay_apay'] = [
				'iframe' => true,
				'name'   => __( 'Stripe Payment Reques', 'woocommerce-gateway-amazon-payments-advanced' ),
			];

			return $buttons;
		}

		public function add_stripe_gpay_apay_buttons() {
			$instance = WC_Stripe_Payment_Request::instance();
			$instance->display_payment_request_button_html();

		}


		public function set_local_data( $data ) {
			$data['stripe_smart_show_on_desktop'] = 'no';

			return $data;
		}

		public function update_conversion_post_id( $post_id ) {

			if ( ! class_exists( 'WC_Stripe_Payment_Request' ) ) {
				return $post_id;
			}
			$wfacp_id             = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
			$payment_request_type = filter_input( INPUT_POST, 'payment_request_type', FILTER_UNSAFE_RAW );
			if ( ! is_null( $wfacp_id ) && ! is_null( $payment_request_type ) && ( 'payment_request_api' == $payment_request_type || 'google_pay' == $payment_request_type || 'apple_pay' == $payment_request_type ) ) {
				$override = filter_input( INPUT_GET, 'wfacp_is_checkout_override', FILTER_UNSAFE_RAW );
				if ( ! is_null( $override ) ) {
					if ( 'yes' == $override ) {
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

		public function force_add_woocommerce_checkout_shortcode() {
			global $post;
			if ( ! is_null( $post ) && ! is_checkout_pay_page() ) {
				$post->post_content .= '[woocommerce_checkout]';
				add_filter( 'pre_do_shortcode_tag', [ $this, 'replace_empty_string' ], 21, 2 );
			}
		}

		public function replace_empty_string( $status, $tag ) {
			if ( 'woocommerce_checkout' == $tag ) {
				$status = '';
			}

			return $status;
		}

		public function add_internal_css() {
			if ( ! defined( 'WC_STRIPE_VERSION' ) ) {
				return;
			}


			$instance = wfacp_template();
			if ( ! $instance instanceof WFACP_Template_Common ) {
				return;
			}
			$bodyClass = "body";
			if ( 'pre_built' !== $instance->get_template_type() ) {
				$bodyClass = "body #wfacp-e-form";
			}
			if ( version_compare( WC_STRIPE_VERSION, '5.6.0', '<' ) ) {
				return;
			}


			echo "<style>";

			echo $bodyClass . " #payment ul.payment_methods li .card-brand-icons img{position: absolute;}";
			echo ".wfacp_smart_button_container #wc-stripe-payment-request-button-separator{display:none !important}";
			echo ".wfacp_smart_button_container #wc-stripe-payment-request-wrapper{margin-top:0 !important}";
			echo "</style>";

		}
	}


	WFACP_Plugin_Compatibilities::register( new WFACP_Stripe_GPAY_AND_APAY(), 'gpay_apay' );


}

