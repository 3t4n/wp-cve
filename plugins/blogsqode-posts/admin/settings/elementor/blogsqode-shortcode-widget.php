<?php

/**
 * Used Code :'blogsqode-shortcode-widget.php'.
 * Create Custom Shortcode Elementor.
 */

namespace Elementor;
if(is_plugin_active('elementor/elementor.php')) {
	class Blogsqode_Shortcode_Widget extends Widget_Base {


		public function get_name() {
			return 'blogsqode_shortcode';
		}

		public function get_title() {
			return esc_html__( 'Blogsqode Shortcode', 'blogsqode' );
		}

		protected function register_controls() {

			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Content', 'blogsqode' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'blogsqode_shortcode', [
					'label' => esc_html__( 'Shortcode', 'blogsqode' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html( '[blogsqode_blog_list]' , 'blogsqode' ),
					'label_block' => true,
				]
			);

			$this->end_controls_section();

		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			print_r($settings['blogsqode_shortcode']);
		}

	}
}
?>