<?php
/**
 * Functions
 */

use Elementor\Widget_Base;


if ( ! function_exists( 'wpsection_do_settings' ) ) {
	function wpsection_do_settings( $widget_base ) {

		global $widget_settings;

		if ( ! $widget_base instanceof Elementor\Widget_Base ) {
			return;
		}

		$widget_settings           = $widget_base->get_settings_for_display();
		$widget_settings['widget'] = $widget_base;
	}
}


if ( ! function_exists( 'wpsection' ) ) {
	function wpsection() {
		global $wpsection;

		if ( ! $wpsection instanceof WPSECTION_Functions ) {
			$wpsection = new WPSECTION_Functions();
		}

		return $wpsection;
	}
}


if ( ! function_exists( 'wpsection_get_template' ) ) {

	function wpsection_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		$located = wpsection_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return new WP_Error( 'invalid_data', __( '%s does not exist.', 'wpsection' ), '<code>' . $located . '</code>' );
		}

		$located = apply_filters( 'wpsection_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'wpsection_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'wpsection_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'wpsection_locate_template' ) ) {

	function wpsection_locate_template( $template_name, $template_path = '', $default_path = '' ) {

		$plugin_dir  = WPSECTION_PLUGIN_DIR;
		$this_widget = wpsection()->get_settings_atts( 'widget' );
		$widget_name = $this_widget ? $this_widget->get_name() : '';

		/**
		 * Template path in Theme
		 */
		if ( ! $template_path ) {
			$template_path = 'wpsection/';
		}

		if ( ! $default_path && $this_widget instanceof Widget_Base ) {
			$default_path = untrailingslashit( $plugin_dir ) . '/widgets/' . $this_widget->get_widget_slug( true ) . '/';
		}

		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);


		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

	
		return apply_filters( 'wpsection_filters_locate_template', $template, $template_name, $template_path, $widget_name );
	}
}


