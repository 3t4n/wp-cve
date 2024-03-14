<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Softtemplate_Footer_Document extends Softtemplate_Document_Base {

	public function get_name() {
		return 'softtemplate_footer';
	}

	public static function get_title() {
		return __( 'Footer', 'soft-template-core' );
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'softtemplate_template_preview_footer',
			array(
				'label' => __( 'Footer Extra', 'soft-template-core' ),
				'tab' => Elementor\Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			'fixed_footer',
			[
				'label' => __( 'Parallax Footer', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'soft-template-core' ),
				'label_off' => __( 'Hide', 'soft-template-core' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);	

		$this->add_control(
			'z_index',
			[
				'label' => __( 'Z-Index', 'soft-template-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -100,
				'max' => 1000,
				'step' => 0,
				'default' => 0,
				'condition'   => [
					'fixed_footer' => 'yes',
				],
				'selectors' => [
					'footer.stfe-fixed-footer' => 'z-index: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}
}