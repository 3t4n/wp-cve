<?php

/**
 * Creative Mail by Constant Contact ( version 1.3.5)
 * Plugin Path: https://wordpress.org/plugins/creative-mail-by-constant-contact/
 */

#[AllowDynamicProperties]

  class WFACP_Creative_Mail_Constant_Contact {
	public $instance = null;

	public function __construct() {

		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_creative_mail', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );

		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		/* Add Default Styling CSS*/
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

		/* internal css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function is_enable() {

		if ( ! class_exists( 'CreativeMail\Helpers\OptionsHelper' ) || is_null( $this->instance ) || ! $this->instance instanceof CreativeMail\Managers\EmailManager ) {
			return false;
		}

		return true;

	}

	public function add_field( $fields ) {
		$fields['creative_mail'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'creative_mail' ],
			'id'         => 'creative_mail',
			'field_type' => 'creative_mail',
			'label'      => __( 'Creative Mail', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function action() {

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'CreativeMail\Managers\EmailManager', 'add_checkout_field' );

	}

	public function display_field( $field, $key ) {

		if ( ! $this->is_enable() || empty( $key ) || 'creative_mail' !== $key || CreativeMail\Helpers\OptionsHelper::get_checkout_checkbox_enabled() !== '1' ) {

			return '';
		}

		?>
        <div class="wfacp_creative_mail" id="wfacp_creative_mail">
			<?php echo $this->instance->add_checkout_field( WC()->checkout() ); ?>
        </div>

		<?php


	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( ! $this->is_enable() || 'ce4wp_checkout_consent_checkbox' !== $key ) {
			return $args;
		}

		$all_cls          = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
		$args['class']    = $all_cls;
		$args['cssready'] = [ 'wfacp-col-full' ];


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
		$cssHtml .= $bodyClass . "#wfacp_creative_mail {clear:both}";
		$cssHtml .= "</style>";

		echo $cssHtml;


	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Creative_Mail_Constant_Contact(), 'wfacp-creative-mail-constant-contact' );
