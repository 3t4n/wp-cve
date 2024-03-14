<?php

/**
 * Uncanny Groups for LearnDash by Uncanny Owl (upto v.4.0.5)
 * Plugin Path: https://www.uncannyowl.com
 */

#[AllowDynamicProperties]

  class WFACP_Uncanny_Groups_For_LearnDash {
	public $instance = null;

	public function __construct() {


		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_ulgm_group_name', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );

		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		/* default classes */
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

		/* Internal css  */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );


	}

	public function is_enable() {
		if ( ! class_exists( 'uncanny_learndash_groups\WoocommerceLicense' ) ) {
			return false;
		}

		return true;
	}

	public function add_field( $fields ) {
		$fields['ulgm_group_name'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_ulgm_group_name' ],
			'id'         => 'ulgm_group_name',
			'field_type' => 'ulgm_group_name',
			'label'      => __( 'ULGM Group Name', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function action() {

		if ( ! $this->is_enable() ) {
			return;
		}

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'uncanny_learndash_groups\WoocommerceLicense', 'create_group_related_fields' );


	}

	public function display_field( $field, $key ) {


		if ( ! $this->is_enable() || empty( $key ) || 'ulgm_group_name' !== $key || ! $this->instance instanceof uncanny_learndash_groups\WoocommerceLicense ) {
			return '';
		}


		?>
        <div class="wfacp_ulgm_group_name" id="wfacp_ulgm_group_name">
			<?php
			$this->instance->create_group_related_fields( WC()->checkout() );
			?>
        </div>
		<?php

	}


	public function add_default_wfacp_styling( $args, $key ) {

		if ( ! $this->is_enable() || 'ulgm_group_name' !== $key ) {
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

		if ( ! $this->is_enable() ) {
			return;

		}
		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";
		$px        = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$px        = "7px";
			$bodyClass = "body #wfacp-e-form ";
		}


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_ulgm_group_name{clear: both;}";
		$cssHtml .= $bodyClass . "#wfacp_ulgm_group_name h3{margin: 0 0 10px;padding:0 $px;font-size: 16px;font-weight: 900;}";

		$cssHtml .= "</style>";
		echo $cssHtml;
	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Uncanny_Groups_For_LearnDash(), 'wfacp-ulgm' );

