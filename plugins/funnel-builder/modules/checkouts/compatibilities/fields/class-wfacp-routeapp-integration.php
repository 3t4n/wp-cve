<?php

/**
 * Route App  by Route v(2.2.3)
 * Plugin URI: https://route.com/
 */

#[AllowDynamicProperties]

  class WFACP_Route_App {
	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'register_field' ], 20 );
		add_filter( 'wfacp_after_checkout_page_found', [ $this, 'actions' ], 20 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function register_field( $field ) {
		if ( ! $this->is_active() ) {
			return $field;
		}
		$field['route_widget_field'] = [
			'type'          => 'hidden',
			'default'       => true,
			'label'         => 'Route Widget',
			'validate'      => [],
			'id'            => 'route_widget_field',
			'required'      => false,
			'wrapper_class' => [],
			'class'         => [ 'route-widget-field' ],
		];

		return $field;
	}

	public function actions() {
		if ( ! $this->is_active() ) {
			return;
		}
		add_filter( 'woocommerce_form_field_args', [ $this, 'register_form_field_args' ], 25, 2 );
	}

	public function register_form_field_args( $args, $key ) {

		if ( ! $this->is_active() ) {
			return $args;
		}
		if ( $key == 'route_widget_field' ) {
			echo do_shortcode( '[route]' );
		}

		return $args;
	}

	public function internal_css() {
		if ( ! $this->is_active() ) {
			return;
		}
		$cssHtml = "<style>";
		$cssHtml .= "#wfacp_checkout_form #RouteWidget .pw-route-protection .pw-container {max-width: 100%;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}

	public function is_active() {
		return class_exists( 'Routeapp_Plugin_Integrations' );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Route_App(), 'wfacp-route-app' );
