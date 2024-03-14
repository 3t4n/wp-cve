<?php

/**
 * YITH WooCommerce Delivery Date Premium by YITH (up to  2.1.29 Version)
 * Plugin URL Path: https://yithemes.com/themes/plugins/yith-woocommerce-delivery-date/
 */
#[AllowDynamicProperties]

  class WFACP_Yth_WC_Delivery_Date_Premium {
	public $instance = null;

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wfacp_yth_wc_delivery_date', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );
		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'action' ] );
		/* internal css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function add_field( $fields ) {
		$fields['wfacp_yth_wc_delivery_date'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_yth_wc_delivery_date' ],
			'id'         => 'wfacp_yth_wc_delivery_date',
			'field_type' => 'wfacp_yth_wc_delivery_date',
			'label'      => __( 'YITH Delivery Date', 'funnel-builder' ),
		];

		return $fields;
	}

	public function action() {
		$this->instance = WFACP_Common::remove_actions( 'admin_init', 'YITH_Delivery_Date_Shipping_Manager', 'set_shipping_method' );
		WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'YITH_Delivery_Date_Shipping_Manager', 'print_delivery_from' );
	}

	public function display_field( $field, $key ) {
		if ( ! $this->is_enable() || empty( $key ) || 'wfacp_yth_wc_delivery_date' !== $key ) {
			return '';
		}

		if ( is_null( $this->instance ) || ! $this->instance instanceof YITH_Delivery_Date_Shipping_Manager || ! method_exists( $this->instance, 'print_delivery_from' ) ) {
			return '';
		}


		?>
        <div id="wfacp_yth_wc_delivery_date_wrap">
			<?php echo $this->instance->print_delivery_from(); ?>
        </div>
		<?php


	}

	public function is_enable() {
		if ( class_exists( 'YITH_Delivery_Date_Shipping_Manager' ) ) {
			return true;
		}

		return false;
	}

	public function internal_css() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";
		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}
		$px = $instance->get_template_type_px() . "px";

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_yth_wc_delivery_date_wrap input {padding: 12px 10px;color: #404040;}";
		$cssHtml .= $bodyClass . "#wfacp_yth_wc_delivery_date_wrap {padding-left:$px;padding-right:$px;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Yth_WC_Delivery_Date_Premium(), 'wfacp-yth-wc-delivery-date-premium' );
