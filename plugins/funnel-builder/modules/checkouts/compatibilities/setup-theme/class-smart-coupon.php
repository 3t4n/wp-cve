<?php

/**
 * WooCommerce Smart Coupons By Store Apps
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Smart_Coupons
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Smart_Coupons {
	private $process = false;
	private $instance = null;

	public function __construct() {

		/* Register Gift Certificate field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_smart_coupon_gift', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );

		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css_script' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'get_data' ], 5 );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'unset_fragments' ], 900 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
	}

	public function action() {
		add_filter( 'wc_sc_is_filter_content_coupon_message', '__return_false' );

		if ( ! class_exists( 'WC_SC_Coupon_Message' ) ) {
			return;
		}
		$this->instance = WC_SC_Coupon_Message::get_instance();


		remove_action( 'woocommerce_checkout_before_customer_details', array( $this->instance, 'wc_coupon_message_display' ) );
		add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'wc_coupon_message_display' ), 99 );

	}

	public function add_field( $fields ) {
		$fields['smart_coupon_gift'] = [
			'type'  => 'wfacp_html',
			'class' => [ 'wfacp-col-full', 'wfacp-form-control-wrapper' ],
			'id'    => 'smart_coupon_gift',
			'label' => __( 'Smart Coupon Gift', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function display_field( $field, $key ) {
		if ( empty( $key ) || 'smart_coupon_gift' !== $key ) {
			return '';
		}
		if ( ! class_exists( 'WC_SC_Purchase_Credit' ) || ! method_exists( 'WC_SC_Purchase_Credit', 'get_instance' ) || ! method_exists( 'WC_SC_Purchase_Credit', 'gift_certificate_receiver_detail_form' ) ) {
			return '';
		}
		$instance = WC_SC_Purchase_Credit::get_instance();
		remove_action( 'woocommerce_checkout_after_customer_details', [ $instance, 'gift_certificate_receiver_detail_form' ] );

		if ( $instance instanceof WC_SC_Purchase_Credit ) {

			echo "<div id=wfacp_smart_coupon_gift>";
			$instance->gift_certificate_receiver_detail_form();
			echo "</div>";
		}
	}

	public function get_data( $data ) {

		if ( empty( $data ) ) {
			return $data;
		}
		parse_str( $data, $post_data );
		if ( empty( $post_data ) || ! isset( $post_data['wfacp_input_hidden_data'] ) || empty( $post_data['wfacp_input_hidden_data'] ) ) {
			return $data;
		}

		$bump_action_data = json_decode( $post_data['wfacp_input_hidden_data'], true );

		if ( empty( $bump_action_data ) ) {
			return $data;
		}
		if ( isset( $bump_action_data['unset_fragments'] ) ) {
			$this->process = true;
		}
	}

	public function unset_fragments( $fragments ) {
		if ( false == $this->process ) {
			return $fragments;
		}
		foreach ( $fragments as $k => $fragment ) {
			if ( false !== strpos( $k, 'wfacp' ) && true == apply_filters( 'wfacp_unset_our_fragments_by_smart_coupon', true, $k ) ) {
				unset( $fragments[ $k ] );
			}
		}
		unset( $fragments['cart_total'] );


		$fragments['wc_coupon_message_wrap'] = $this->wc_coupon_html();

		return $fragments;
	}

	public function wc_coupon_message_display() {

		if ( ! is_object( WC() ) || ! is_object( WC()->cart ) || WC()->cart->is_empty() ) {
			return;
		}

		echo '<div class="wc_coupon_message_wrap" style="padding: 10px 0 10px;">';
		echo $this->instance->print_coupon_message();
		echo '</div>';


	}

	public function wc_coupon_html() {

		$applied_coupons = WC()->cart->get_applied_coupons();
		$instance        = WC_SC_Coupon_Message::get_instance();

		if ( ! $instance instanceof WC_SC_Coupon_Message ) {
			return '';
		}

		ob_start();
		$instance->print_coupon_message( $applied_coupons );

		$html = ob_get_clean();

		return $html;

	}


	public function wfacp_internal_css_script() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";
		$px        = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
			$px        = "7px";
		}

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_smart_coupon_gift {clear: both;padding: 0 $px;}";
		$cssHtml .= $bodyClass . "#wfacp_smart_coupon_gift ul.show_hide_list {padding: 0;margin: 0;}";
		$cssHtml .= $bodyClass . "#wfacp_smart_coupon_gift input[type='text'] {padding: 12px 10px;margin: 0 0 15px;}";
		$cssHtml .= $bodyClass . "#wfacp_smart_coupon_gift .form_table {margin: 15px 0 0;width: 100%;padding: 15px 0 0;}";
		$cssHtml .= "</style>";

		echo $cssHtml;


		?>

        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    wfacp_frontend.hooks.addFilter('wfacp_before_ajax_data_apply_coupon_field', set_custom_data);
                    wfacp_frontend.hooks.addFilter('wfacp_before_ajax_data_apply_coupon_main', set_custom_data);
                    wfacp_frontend.hooks.addAction('wfacp_ajax_apply_coupon_field', trigger_checkout);
                    wfacp_frontend.hooks.addAction('wfacp_ajax_apply_coupon_main', trigger_checkout);

                    function set_custom_data(data) {
                        data['unset_fragments'] = 'yes';
                        return data;
                    }

                    function trigger_checkout(rsp) {
                        if (rsp.hasOwnProperty('message')) {
                            var message = rsp.message;
                            if (!message.hasOwnProperty('error')) {
                                $(document.body).trigger('update_checkout');
                            }
                        }
                    }
                })(jQuery);
            });
        </script>
		<?php
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Smart_Coupons(), 'wfacp_smart_coupon' );

