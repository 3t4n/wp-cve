<?php

/**
 * WC-AC Hook by Matthew Treherne
 * Plugin URI: https://wordpress.org/plugins/wc-ac-hook/
 */

#[AllowDynamicProperties]

  class WFACP_WC_AC_Hook {
	public $instance = null;

	public function __construct() {


		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wc_ac_hook', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );
		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		/* internal css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}


	public function add_field( $fields ) {
		$fields['wc_ac_hook'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wc_ac_hook' ],
			'id'         => 'wc_ac_hook',
			'field_type' => 'wc_ac_hook',
			'label'      => __( 'WC AC Hook', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function action() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'mtreherne\WC_AC_Hook\WC_AC_Hook', 'marketing_checkout_field' );
	}

	public function display_field( $field, $key ) {

		if ( empty( $key ) || 'wc_ac_hook' !== $key || ! $this->instance instanceof mtreherne\WC_AC_Hook\WC_AC_Hook ) {
			return '';
		}

		?>
        <div class="wfacp_wc_ac_hook" id="wfacp_wc_ac_hook">
			<?php
			$this->instance->marketing_checkout_field( WC()->checkout() );
			?>
        </div>
		<?php
	}


	public function internal_css() {

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
		$cssHtml .= $bodyClass . "#wfacp_wc_ac_hook{padding-left:$px;padding-right:$px;clear:both;}";
		$cssHtml .= "</style>";
		echo $cssHtml;

	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_WC_AC_Hook(), 'wfacp-wc-ac-hook' );
