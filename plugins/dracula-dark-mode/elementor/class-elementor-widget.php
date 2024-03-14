<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit();

class Dracula_Elementor_Widget extends Widget_Base {

	public function get_name() {
		return 'dracula_switch';
	}

	public function get_title() {
		return __( 'Dark Mode Switch', 'dracula-dark-mode' );
	}

	public function get_icon() {
		return 'dracula-switch';
	}


	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [
			'dracula',
			'dark mode',
			'toggle',
			'switch'
		];
	}

	public function register_controls() {

		$this->start_controls_section( '_section',
			[
				'label' => __( 'Switch Style', 'dracula-dark-mode' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

		$this->add_control( 'style', [
			'label'   => __( 'Switch Style', 'dracula-dark-mode' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 1,
		] );

		//Edit button
		$this->add_control( 'toggles', [
			'type' => Controls_Manager::RAW_HTML,
			'raw'  => '<p class="description">Choose the dark mode switch style.</p> <br> <div id="dracula-elementor-toggles"></div>',
		] );

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$style = ! empty( $settings['style'] ) ? $settings['style'] : 1;
		$id    = ! empty( $settings['id'] ) ? $settings['id'] : '';

		echo do_shortcode( "[dracula_toggle style=$style id=$id]" );

	}

}
