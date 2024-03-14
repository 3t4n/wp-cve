<?php

/**
 * FluentCRM Pro  by Fluent CRM
 * Plugin URI: https://fluentcrm.com
 */

#[AllowDynamicProperties]

  class WFACP_FluentPro {
	public $instance = null;

	public function __construct() {


		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wfacp_fluent_wc_subscription_checkbox', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );

		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		/* default classes */
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 999, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );


	}


	public function is_enable() {

		return class_exists( 'FluentCampaign\App\Services\Integrations\WooCommerce\WooInit' );
	}

	public function add_field( $fields ) {


		$fields['wfacp_fluent_wc_subscription_checkbox'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_fluent_wc_subscription_checkbox' ],
			'id'         => 'wfacp_fluent_wc_subscription_checkbox',
			'field_type' => 'wfacp_fluent_wc_subscription_checkbox',
			'label'      => __( 'FluentCRM', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function action() {

		if ( ! $this->is_enable() ) {
			return;
		}

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_checkout_billing', 'FluentCampaign\App\Services\Integrations\WooCommerce\WooInit', 'addSubscribeBox' );


	}

	public function display_field( $field, $key ) {


		if ( ! $this->is_enable() || empty( $key ) || 'wfacp_fluent_wc_subscription_checkbox' !== $key || ! $this->instance instanceof FluentCampaign\App\Services\Integrations\WooCommerce\WooInit ) {
			return '';
		}


		?>
        <div class="wfacp_fluent_wc_subscription_checkbox" id="wfacp_fluent_wc_subscription_checkbox">
			<?php
			$this->instance->addSubscribeBox();
			?>
        </div>
		<?php

	}


	public function add_default_wfacp_styling( $args, $key ) {


		if ( ! $this->is_enable() || 'wfacp_fluent_wc_subscription_checkbox' !== $key ) {
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

		if ( ! $this->is_enable() || ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";
		$px        = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
			$px        = "7px";
		}

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_fluent_wc_subscription_checkbox {clear:both;}";

		if ( ! empty( $px ) ) {
			$cssHtml .= $bodyClass . "#wfacp_fluent_wc_subscription_checkbox p {padding:0 $px;}";
		}

		$cssHtml .= "</style>";
		echo $cssHtml;


	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_FluentPro(), 'wfacp-fluentcampaign-pro' );

