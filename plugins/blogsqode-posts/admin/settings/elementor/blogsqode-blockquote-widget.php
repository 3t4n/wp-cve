<?php

/**
 * Used Code :'blogsqode-blockquote-widget.php'.
 * Create Custom Blockquote Elementor.
 */

namespace Elementor;
if(is_plugin_active('elementor/elementor.php')) {
	class Blogsqode_Blockquote_Widget extends Widget_Base {


		public function get_name() {
			return 'blogsqode_blockquote';
		}

		public function get_title() {
			return esc_html__( 'Blogsqode Blockquote', 'blogsqode' );
		}

		protected function register_controls() {

			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Quote', 'blogsqode' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'blogsqode_blockquote', [
					'label' => esc_html__( 'Quote', 'blogsqode' ),
					'type' => \Elementor\Controls_Manager::WYSIWYG,
					'default' => esc_html__( 'Your Quote' , 'blogsqode' ),
					'label_block' => true,
				]
			);

			$this->end_controls_section();

		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			echo '<blockquote cite="#">'.esc_html($settings['blogsqode_blockquote']).'</blockquote>';
		}

	}
}
?>