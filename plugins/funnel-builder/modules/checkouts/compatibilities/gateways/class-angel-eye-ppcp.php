<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Angel_Eye_PPCP {
	public function __construct() {

		add_filter( 'wfacp_smart_buttons', [ $this, 'add_buttons' ], 15 );
		add_action( 'wfacp_smart_button_container_angelleye_ppcp', [ $this, 'add_paypal_buttons' ] );
		add_filter( 'wfacp_enable_hashtag_for_multistep_checkout', [ $this, 'disabled_hashtag_form_multistep_checkout' ] );
		add_filter( 'wfacp_css_js_removal_paths', [ $this, 'remove_some_js' ], 15 );
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'update_aero_field' ], 11, 3 );
		add_filter( 'wfacp_mark_conversion_post_id', [ $this, 'update_conversion_post_id' ], 10, 2 );
		add_action( 'woocommerce_checkout_create_order', [ $this, 'update_checkout_id' ], 10 );

		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}


	public function add_buttons( $buttons ) {

		if ( ! class_exists( 'AngellEYE_PayPal_PPCP_Smart_Button' ) ) {

			return $buttons;
		}

		$settings = AngellEYE_Utility::angelleye_get_pre_option( false, 'woocommerce_angelleye_ppcp_settings' );
		if ( ! is_array( $settings ) || 'yes' != $settings['enabled'] || 'regular' == $settings['checkout_page_display_option'] || 'yes' == $settings['checkout_disable_smart_button'] ) {
			return $buttons;
		}

		//VERSION_PFW
		add_action( 'wfacp_internal_css', function () {
			$instance = AngellEYE_PayPal_PPCP_Smart_Button::instance();
			remove_action( 'woocommerce_before_checkout_form', [ $instance, 'display_paypal_button_top_checkout_page' ], 5 );
			remove_action( 'woocommerce_checkout_before_customer_details', [ $instance, 'display_paypal_button_top_checkout_page' ], 1 );
		} );
		$buttons['angelleye_ppcp'] = [
			'iframe' => true,
			'name'   => $settings['title'],
		];
		add_filter( 'wfacp_smart_container_display_hook', [ $this, 'change_top_position_hook' ] );

		return $buttons;
	}

	/**
	 *
	 * WHen PPCP Button available then we change button position forcefully  to top of the form.
	 *
	 * @param $position_hook
	 *
	 * @return mixed|string
	 */
	public function change_top_position_hook( $position_hook ) {

		$position_hook = 'woocommerce_before_checkout_form';

		return $position_hook;

	}

	public function add_paypal_buttons() {
		echo '<style>.angelleye_ppcp_checkout_message_guide{display: none;}</style>';

		$instance = AngellEYE_PayPal_PPCP_Smart_Button::instance();
		$instance->display_paypal_button_top_checkout_page();
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


	public function print_html() {
		?>
        <p>
            <strong><?php _e( 'Full Name', 'woofunnels-aero-checkout' ); ?></strong> <?php echo esc_html( WFACP_Core()->public->billing_details['first_name'] . ' ' . WFACP_Core()->public->billing_details['last_name'] ); ?>
        </p>
		<?php
	}


	// this function only run when Order created via paypal express
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


	public function update_checkout_id($order) {
		if ( ! class_exists( 'AngellEYE_PayPal_PPCP_Front_Action' ) || ! isset( $_GET['angelleye_ppcp_action'] ) || ! isset( $_GET['wfacp_id'] ) ) {
			return;
		}
		$order->update_meta_data('_wfacp_post_id', $_GET['wfacp_id']);

	}

	public function internal_css() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body";
		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_smart_buttons .wfacp_smart_button_inner #angelleye_ppcp_checkout_top{    height: auto;line-height: 1;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Angel_Eye_PPCP(), 'angel_eye_ppcp' );



