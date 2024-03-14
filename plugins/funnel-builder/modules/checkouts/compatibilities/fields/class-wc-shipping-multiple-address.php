<?php

/**
 * Name: WooCommerce Ship to Multiple Addresses by WooCommerce (up to 3.6.39)
 * URL: https://woocommerce.com/products/shipping-multiple-addresses/
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties]

  class WFACP_WC_Ship_To_Multiple_Address {
	public $instance = null;

	public function __construct() {


		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_shipping' ] );
		} else {
			add_action( 'init', [ $this, 'setup_shipping' ], 20 );
		}

		add_filter( 'wfacp_html_fields_shipping_wc_ship_multiple', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 3 );

		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		add_action( 'wfacp_before_wfacp_divider_shipping_field', function () {
			echo "<div class=woocommerce-shipping-fields>";
		} );

		add_action( 'wfacp_after_wfacp_divider_shipping_field', function () {
			echo "</div>";
		} );


		/* internal css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function is_enable() {
		return class_exists( 'WC_Ship_Multiple' );
	}

	public function setup_shipping() {


		new WFACP_Add_Address_Field( 'wc_ship_multiple', array(
			'type'     => 'wfacp_html',
			'label'    => __( 'WC Ship Multiple Address', 'woofunnels-aero-checkout' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => array( 'form-row-third first', 'wfacp-col-full' ),
			'required' => false,
			'priority' => 60,
		), 'shipping' );


	}

	public function action() {

		if ( ! $this->is_enable() || ! isset( $GLOBALS['wcms'] ) || ! $GLOBALS['wcms']->checkout instanceof WC_MS_Checkout ) {
			return;
		}
		$this->instance = $GLOBALS['wcms']->checkout;

		add_filter( 'pre_option_woocommerce_checkout_page_id', function () {
			$post_id = WFACP_Common::get_id();

			return $post_id;
		} );


	}


	public function display_field( $field, $key, $args ) {


		if ( ! $this->is_enable() || empty( $key ) || 'shipping_wc_ship_multiple' !== $key || ! $this->instance instanceof WC_MS_Checkout ) {
			return '';
		}

		echo "<div id=wfacp_wc_ship_multiple>";

		$this->instance->before_shipping_form( WC()->checkout() );
		$this->instance->display_set_addresses_button( WC()->checkout() );
		$this->instance->render_user_addresses_dropdown( WC()->checkout() );

		echo "</div>";


	}


	public function internal_css() {

		if ( ! $this->is_enable() || ! function_exists( 'wfacp_template' ) ) {
			return '';
		}
		?>


        <script>

            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {

                    if ($('#ms_shipping_addresses_field').length > 0) {
                        $('#ms_shipping_addresses_field').addClass('wfacp-col-full wfacp-form-control-wrapper');
                        $('#ms_shipping_addresses_field').find('label').addClass('wfacp-form-control-label');
                        $('#ms_shipping_addresses_field').find('select').addClass('wfacp-form-control');
                    }

                })(jQuery);
            });

        </script>
		<?php

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body";
		$px        = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
			$px        = "7px";

		}


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_wc_ship_multiple{clear: both;}";
		$cssHtml .= $bodyClass . "#wfacp_wc_ship_multiple p{padding:0 $px;}";

		$cssHtml .= "</style>";

		echo $cssHtml;


	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_WC_Ship_To_Multiple_Address(), 'wfacp-wcms' );

