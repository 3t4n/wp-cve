<?php

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Dracula_Elementor_Settings {

	private static $instance = null;

	public function __construct() {
		add_action( 'elementor/documents/register_controls', [ $this, 'register_controls' ] );
	}

	public function register_controls( $document ) {

		$document->start_controls_section( '_dracula_settings_section',
			[
				'label' => __( 'Dark Mode Settings', 'dracula-dark-mode' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			] );

		$document->add_control( 'dracula_settings', [
			'label'   => __( 'Settings', 'dracula-dark-mode' ),
			'type'    => Controls_Manager::HIDDEN,
		] );

		//Edit button
		$document->add_control( 'settings_html', [
			'type' => Controls_Manager::RAW_HTML,
			'raw'  => '<div id="dracula-elementor-settings" class="dracula-live-edit-wrap"></div>',
		] );

		$document->end_controls_section();
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

Dracula_Elementor_Settings::instance();