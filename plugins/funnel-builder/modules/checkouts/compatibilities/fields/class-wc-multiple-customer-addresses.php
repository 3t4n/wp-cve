<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Multiple Customer Addresses  by Lagudi Domenico
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_Multiple_Customer_Addresses {
	public $instance = null;

	public function __construct() {

		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_billing' ] );
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_shipping' ] );
		} else {
			add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
			add_action( 'init', [ $this, 'setup_fields_shipping' ], 20 );
		}


		add_filter( 'wfacp_html_fields_shipping_wc_multi_customer_address', '__return_false' );
		add_filter( 'wfacp_html_fields_billing_wc_multi_customer_address', '__return_false' );

		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 10, 3 );


		/* Add remove action */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ], 10 );
		add_action( 'wfacp_checkout_page_found', [ $this, 'actions' ], 10 );


		/* Custom CSS added */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ], 10 );

	}


	public function setup_fields_billing() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'wc_multi_customer_address', array(
			'type'     => 'wfacp_html',
			'label'    => __( 'WC Multi Customer Address', 'woofunnels-aero-checkout' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => array( 'form-row-third first', 'wfacp-col-full' ),
			'required' => false,
			'priority' => 60,
		) );


	}

	public function setup_fields_shipping() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'wc_multi_customer_address', array(
			'type'     => 'wfacp_html',
			'label'    => __( 'WC Multi Customer Address', 'woofunnels-aero-checkout' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => array( 'form-row-third first', 'wfacp-col-full' ),
			'required' => false,
			'priority' => 60,
		), 'shipping' );


	}

	public function is_enabled() {
		global $wcmca_checkout_page_addon;

		if ( ! class_exists( 'WCMCA_CheckoutPage' ) ) {
			return false;
		}

		if ( ! $wcmca_checkout_page_addon instanceof WCMCA_CheckoutPage ) {
			return false;
		}
		$this->instance = $wcmca_checkout_page_addon;

		return true;

	}

	public function actions() {

		if ( ! $this->is_enabled() ) {
			return;
		}


		WFACP_Common::remove_actions( 'woocommerce_before_checkout_billing_form', 'WCMCA_CheckoutPage', 'add_billing_address_select_menu' );
		WFACP_Common::remove_actions( 'woocommerce_before_checkout_shipping_form', 'WCMCA_CheckoutPage', 'add_billing_address_select_menu' );
		WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'WCMCA_CheckoutPage', 'add_popup_html' );

		add_action( 'woocommerce_before_checkout_form', array( $this->instance, 'add_popup_html' ) );


	}

	public function display_field( $field, $key, $args ) {


		if ( ! $this->is_enabled() || empty( $key ) || strpos( $key, '_wc_multi_customer_address' ) === false ) {
			return '';
		}


		echo '<div class=wfacp_address_multi_select_wrap>';
		if ( 'shipping_wc_multi_customer_address' === $key ) {
			echo "<div class='wfacp_shipping_address_multi_select wfacp-col-full wfacp_shipping_fields'>";
			$this->instance->add_shipping_address_select_menu( WC()->checkout() );
			echo "</div>";
		} else {
			echo "<div class='wfacp_billing_address_multi_select wfacp-col-full wfacp_billing_fields'>";
			$this->instance->add_billing_address_select_menu( WC()->checkout() );
			echo "</div>";
		}
		echo "</div>";


	}


	public function add_billing_address_select_menu() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		echo "<div class=wfacp_billing_address_multi_select>";
		$this->instance->add_billing_address_select_menu( WC()->checkout() );
		echo "</div>";

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

		$px = $instance->get_template_type_px() . "px";
		if ( false !== strpos( $instance->get_template_type(), 'elementor' ) ) {
			$px = "7px";
		}

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wcmca_form_popup_container_shipping .form-row.form-row.form-row-last,body #wcmca_form_popup_container_billing .form-row.form-row.form-row-last{clear: none;float: none;margin-left: 0;width: 100%;}";
		$cssHtml .= $bodyClass . "#wcmca_form_popup_container_shipping .form-row-first,body #wcmca_form_popup_container_billing .form-row-first {float: none;position: relative;width: 100%;margin: 0 0 15px;}";
		$cssHtml .= $bodyClass . "#wcmca_address_form_container_billing .select2-selection__rendered, body #wcmca_address_form_container_shipping .select2-selection__rendered {font-size: 14px;line-height: 1.5;width: 100%;background-color: #ffffff;border-radius: 4px;position: relative;color: #404040;display: block;min-height: 52px;padding: 20px 12px 5px;border: 1px solid #bfbfbf;opacity: 1;}";
		$cssHtml .= $bodyClass . "#wcmca_address_form_container_shipping button,body #wcmca_address_form_container_billing button {font-size: 15px;cursor: pointer;background-color: #999999;color: #ffffff;text-decoration: none;font-weight: normal;line-height: 18px;margin-bottom: 0;padding: 10px 20px;border: 1px solid rgba(0, 0, 0, 0.1);border-radius: 4px;}";
		$cssHtml .= $bodyClass . "#wcmca_add_new_address_button_billing {font-size: 100%;margin: 0;line-height: 1;cursor: pointer;position: relative;text-decoration: none;overflow: visible;padding: 10px 15px;font-weight: 700;border-radius: 8px;left: auto;border: 0;display: inline-block;background-image: none;box-shadow: none;margin-top: 12px;background-color: #999999;text-shadow: none;color: #fff;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce input[type=email]{font-size: 14px;line-height: 1.5;width: 100%;background-color: #fff;border-radius: 4px;position: relative;color: #404040;display: block;min-height: 52px;padding: 23px 12px 6px;vertical-align: top;box-shadow: none;border: 1px solid #bfbfbf;margin-bottom: 0 !important;font-weight: 400;height: auto;margin-bottom: 0;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce input[type=number]{font-size: 14px;line-height: 1.5;width: 100%;background-color: #fff;border-radius: 4px;position: relative;color: #404040;display: block;min-height: 52px;padding: 23px 12px 6px;vertical-align: top;box-shadow: none;border: 1px solid #bfbfbf;margin-bottom: 0 !important;font-weight: 400;height: auto;margin-bottom: 0;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce input[type=text]{font-size: 14px;line-height: 1.5;width: 100%;background-color: #fff;border-radius: 4px;position: relative;color: #404040;display: block;min-height: 52px;padding: 23px 12px 6px;vertical-align: top;box-shadow: none;border: 1px solid #bfbfbf;margin-bottom: 0 !important;font-weight: 400;height: auto;margin-bottom: 0;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce input[type=date]{font-size: 14px;line-height: 1.5;width: 100%;background-color: #fff;border-radius: 4px;position: relative;color: #404040;display: block;min-height: 52px;padding: 23px 12px 6px;vertical-align: top;box-shadow: none;border: 1px solid #bfbfbf;margin-bottom: 0 !important;font-weight: 400;height: auto;margin-bottom: 0;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce select{font-size: 14px;line-height: 1.5;width: 100%;background-color: #fff;border-radius: 4px;position: relative;color: #404040;display: block;min-height: 52px;padding: 23px 12px 6px;vertical-align: top;box-shadow: none;border: 1px solid #bfbfbf;margin-bottom: 0 !important;font-weight: 400;height: auto;margin-bottom: 0;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce textarea{font-size: 14px;line-height: 1.5;width: 100%;background-color: #fff;border-radius: 4px;position: relative;color: #404040;display: block;min-height: 52px;padding: 23px 12px 6px;vertical-align: top;box-shadow: none;border: 1px solid #bfbfbf;margin-bottom: 0 !important;font-weight: 400;height: auto;margin-bottom: 0;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce .wfacp_address_multi_select_wrap .select2-container{width:100% !important;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form.woocommerce .wfacp_address_multi_select_wrap{clear:both;padding:0 $px; }";
		$cssHtml .= $bodyClass . ".wfacp_main_form .wfacp_address_multi_select_wrap .select2-container .select2-selection--single .select2-selection__rendered{padding:10px 12px;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form .wfacp_address_multi_select_wrap select{padding:14px 12px;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form .wfacp_address_multi_select_wrap .wcmca_add_new_address_button{font-size: 14px;line-height: 1.5;height: auto;color: #fff !important;font-weight: 400;background-color: #999999;margin-top: 10px;padding: 10px 15px;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form .wfacp_address_multi_select_wrap #wcmca_add_new_address_button_shipping{font-size: 14px;line-height: 1.5;height: auto;color: #fff !important;font-weight: 400;background-color: #999999;margin-top: 10px;padding: 10px 15px;}";
		$cssHtml .= $bodyClass . ".wfacp_main_form .wfacp_address_multi_select_wrap #wcmca_add_new_address_button_billing{font-size: 14px;line-height: 1.5;height: auto;color: #fff !important;font-weight: 400;background-color: #999999;margin-top: 10px;padding: 10px 15px;}";


		$cssHtml .= "</style>";
		echo $cssHtml;


	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Multiple_Customer_Addresses(), 'wfacp-wc-multiple-customer-addresses' );


