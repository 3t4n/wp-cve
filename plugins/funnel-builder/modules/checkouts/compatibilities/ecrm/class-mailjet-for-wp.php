<?php

/**
 * Mailjet for WordPress by Mailjet SAS
 * Plugin Path : https://www.mailjet.com/partners/wordpress/
 */


#[AllowDynamicProperties]

  class Mailjet_For_WP {

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_mailjet_for_wp', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );
		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		/* Add Default Styling  */
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}


	public function add_field( $fields ) {
		$fields['mailjet_for_wp'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_mailjet_for_wp' ],
			'id'         => 'mailjet_for_wp',
			'field_type' => 'mailjet_for_wp',
			'label'      => __( 'Mailjet for WordPress', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function action() {
		$this->instance = MailjetPlugin\Includes\SettingsPages\WooCommerceSettings::getInstance();
	}

	public function display_field( $field, $key ) {
		if ( ! $this->is_enable() || empty( $key ) || 'mailjet_for_wp' !== $key || ! $this->instance instanceof MailjetPlugin\Includes\SettingsPages\WooCommerceSettings ) {
			return '';
		}
		?>
        <div class="mailjet_show_wrap" id="mailjet_show_wrap">
			<?php $this->instance->mailjet_show_extra_woo_fields( WC()->checkout() ); ?>
        </div>
		<?php
	}

	public function is_enable() {
		if ( class_exists( 'MailjetPlugin\Includes\SettingsPages\WooCommerceSettings' ) ) {
			return true;
		}

		return false;

	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( ! $this->is_enable() || empty( $key ) || 'mailjet_woo_subscribe_ok' !== $key ) {
			return $args;
		}

		if ( ! isset( $args['type'] ) || 'checkbox' === $args['type'] ) {
			return $args;
		}

		$args['class']    = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
		$args['cssready'] = [ 'wfacp-col-full' ];


		return $args;
	}

}

WFACP_Plugin_Compatibilities::register( new Mailjet_For_WP(), 'wfacp-mailjet-for-wp' );
