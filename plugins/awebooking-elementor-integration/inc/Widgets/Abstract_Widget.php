<?php

namespace AweBooking\Elementor\Widgets;

use Elementor\Widget_Base;

/**
 * Class Filmfestival_Elementor_Widget
 */
abstract class Abstract_Widget extends Widget_Base {

	/**
	 * Get widget prefix.
	 *
	 * @access public
	 *
	 * @return string Widget prefix.
	 */
	public function get_prefix() {
		return 'awebooking';
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return $this->get_prefix() . '-' . $this->get_path();
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'awebooking' ];
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$located = $this->locate_template( $this->get_path() . '/render.php' );

		if ( ! file_exists( $located ) ) {
			return;
		}

		if ( ! empty( $located ) && file_exists( $located ) ) {
			@ include apply_filters( 'awebooking_elementor_render_widget_templates', $located, $settings, $this );
		}
	}

	/**
	 * Render widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		$located = $this->locate_template( $this->get_path() . '/preview.php' );

		if ( ! file_exists( $located ) ) {
			return;
		}

		if ( ! empty( $located ) && file_exists( $located ) ) {
			@ include apply_filters( 'awebooking_elementor_preview_widget_templates', $located, $this );
		}
	}

	/**
	 * Locate template.
	 *
	 * @param string $template_name Template name.
	 *
	 * @return string
	 */
	protected function locate_template( $template_name ) {
		// Locate in your {theme}/awebooking-elementor.
		$template = locate_template( [
			trailingslashit( 'awebooking-elementor/' ) . $template_name,
		] );

		// Fallback to default template in the plugin.
		if ( ! $template ) {
			$template = ABRS_ELEMENTOR_PLUGIN_PATH . 'templates/' . $template_name;
		}

		// Return what we found.
		return apply_filters( 'abrs_elementor_locate_template', $template, $template_name );
	}
}
