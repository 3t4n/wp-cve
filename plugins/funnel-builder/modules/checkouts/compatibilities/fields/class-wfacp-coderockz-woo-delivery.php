<?php

/**
 * WooCommerce Delivery Date & Time Pro By CodeRockz
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_WC_Coderockz_Delivery
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WC_Coderockz_Delivery {

	private $coderockz_woo_delivery = null;

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_filter( 'wfacp_html_fields_coderockz_woo_delivery', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 999, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function add_field( $fields ) {
		if ( $this->is_enable() ) {
			$fields['coderockz_woo_delivery'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'coderockz_woo_delivery' ],
				'id'         => 'coderockz_woo_delivery',
				'field_type' => 'coderockz_woo_delivery',
				'label'      => __( 'Coderockz Woo Delivery', 'woofunnels-aero-checkout' ),
			];

		}

		return $fields;
	}

	public function actions() {
		if ( $this->is_enable() ) {
			if ( class_exists( 'Coderockz_Woo_Delivery_Public' ) ) {
				if ( defined( 'CODEROCKZ_WOO_DELIVERY_DIR' ) && defined( 'CODEROCKZ_WOO_DELIVERY_VERSION' ) ) {
					$this->coderockz_woo_delivery = new Coderockz_Woo_Delivery_Public( plugin_basename( CODEROCKZ_WOO_DELIVERY_DIR ), CODEROCKZ_WOO_DELIVERY_VERSION );
				}
			}
		}
	}

	private function is_enable() {
		if ( class_exists( 'Coderockz_Woo_Delivery_Public' ) ) {
			return true;
		}

		return false;
	}


	public function call_fields_hook( $field, $key, $args ) {
		if ( ! empty( $key ) && $this->is_enable() && 'coderockz_woo_delivery' === $key ) {
			if ( $this->coderockz_woo_delivery instanceof Coderockz_Woo_Delivery_Public ) {
				echo "<div class='wfacp_coderockz_woo_delivery'>";
				$this->coderockz_woo_delivery->coderockz_woo_delivery_add_custom_field();
				echo "</div>";
			}
		}
	}

	public function internal_css() {

		if ( ! $this->is_enable() ) {
			return;
		}

		if ( ! function_exists( 'wfacp_template' ) ) {
			return;

		}

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$px = $instance->get_template_type_px();

		echo "<style>";
		if ( $px != '' ) {
			echo ".wfacp_coderockz_woo_delivery{padding:0 $px" . 'px' . "}";

		}
		echo "</style>";


	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Coderockz_Delivery(), 'wc-codrockz-delivery' );
