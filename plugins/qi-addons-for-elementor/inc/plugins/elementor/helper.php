<?php

if ( ! function_exists( 'qi_addons_for_elementor_get_elementor_instance' ) ) {
	/**
	 * Function that return page builder module instance
	 */
	function qi_addons_for_elementor_get_elementor_instance() {
		return \Elementor\Plugin::instance();
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_elementor_widgets_manager' ) ) {
	/**
	 * Function that return page builder widget module instance
	 */
	function qi_addons_for_elementor_get_elementor_widgets_manager() {
		return qi_addons_for_elementor_get_elementor_instance()->widgets_manager;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_load_elementor_widgets' ) ) {
	/**
	 * Function that include modules into page builder
	 */
	function qi_addons_for_elementor_load_elementor_widgets() {
		include_once QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/elementor/class-qiaddonsforelementor-elementor-widget-base.php';

		$widgets = array();

		foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/*', GLOB_ONLYDIR ) as $shortcode ) {
			if ( ! qi_addons_for_elementor_is_widget_disabled( $shortcode ) ) {
				foreach ( glob( $shortcode . '/*-elementor.php' ) as $shortcode_load ) {
					$widgets[ basename( $shortcode_load ) ] = $shortcode_load;
				}
			}
		}

		foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/*/shortcodes/*', GLOB_ONLYDIR ) as $shortcode ) {
			if ( ! qi_addons_for_elementor_is_widget_disabled( $shortcode ) ) {
				foreach ( glob( $shortcode . '/*-elementor.php' ) as $shortcode_load ) {
					$widgets[ basename( $shortcode_load ) ] = $shortcode_load;
				}
			}
		}

		foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/*/shortcodes/*', GLOB_ONLYDIR ) as $shortcode ) {
			if ( ! qi_addons_for_elementor_is_widget_disabled( $shortcode ) ) {
				foreach ( glob( $shortcode . '/*-elementor.php' ) as $shortcode_load ) {
					$widgets[ basename( $shortcode_load ) ] = $shortcode_load;
				}
			}
		}

		$additional_widgets = apply_filters( 'qi_addons_for_elementor_filter_additional_widgets_load', array() );

		$widgets = array_merge( $widgets, $additional_widgets );

		if ( ! empty( $widgets ) ) {
			ksort( $widgets );

			foreach ( $widgets as $widget ) {
				include_once $widget;
			}
		}
	}

	if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>' ) ) {
		add_action( 'elementor/widgets/register', 'qi_addons_for_elementor_load_elementor_widgets' );
	} else {
		add_action( 'elementor/widgets/widgets_registered', 'qi_addons_for_elementor_load_elementor_widgets' );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_register_new_elementor_widget' ) ) {
	/**
	 * Function that register a new widget type.
	 *
	 * @param \Elementor\Widget_Base $widget_instance Elementor Widget.
	 */
	function qi_addons_for_elementor_register_new_elementor_widget( $widget_instance ) {

		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>' ) ) {
			qi_addons_for_elementor_get_elementor_widgets_manager()->register( $widget_instance );
		} else {
			qi_addons_for_elementor_get_elementor_widgets_manager()->register_widget_type( $widget_instance );
		}
	}
}
