<?php

/**
 * WooCommerce Quaderno by Quaderno
 * Plugin URI: https://quaderno.io/integrations/woocommerce/?utm_source=wordpress&utm_campaign=woocommerce
 */
#[AllowDynamicProperties]

  class WFACP_WC_Quaderno {
	public $instance = null;

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_tax_id', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );
		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		/* default classes */
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		/* internal css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function is_enable() {
		return class_exists( 'WC_QD_Tax_Id_Field' );
	}

	public function add_field( $fields ) {
		$fields['tax_id'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_tax_id' ],
			'id'         => 'tax_id',
			'field_type' => 'tax_id',
			'label'      => __( 'Quaderno Tax ID', 'woocommerce-quaderno' ),
		];

		return $fields;
	}

	public function action() {
		if ( ! $this->is_enable() ) {
			return;
		}
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_checkout_billing_form', 'WC_QD_Tax_Id_Field', 'print_field' );
	}

	public function display_field( $field, $key ) {
		if ( ! $this->is_enable() || empty( $key ) || 'tax_id' !== $key || ! $this->instance instanceof WC_QD_Tax_Id_Field ) {
			return '';
		}

		?>
        <div class="wfacp_quaderno_tax_id" id="wfacp_quaderno_tax_id">
			<?php
			$this->instance->print_field();
			?>
        </div>
		<?php
	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( ! $this->is_enable() ) {
			return $args;
		}

		if ( strpos( $key, 'tax_id' ) === false ) {
			return $args;
		}
		if ( isset( $args['type'] ) && 'checkbox' !== $args['type'] ) {
			$args['input_class'] = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$args['label_class'] = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );
			$args['class']       = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full' ], $args['class'] );
			$args['cssready']    = [ 'wfacp-col-full' ];
		} else {
			$args['class']    = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$args['cssready'] = [ 'wfacp-col-full' ];
		}

		return $args;
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
		$cssHtml .= $bodyClass . "#wfacp_quaderno_tax_id {clear:both;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_WC_Quaderno(), 'wfacp-wc-quaderno' );
