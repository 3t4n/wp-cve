<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class sakolawp_myaccount_block extends Widget_Base {

	public function get_name() {
		return 'sakolawp-myaccount-block';
	}

	public function get_title() {
		return esc_html__( 'My Account', 'sakolawp' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'sakolawp-general-category' ];
	}

	protected function _register_controls() {
		/*-----------------------------------------------------------------------------------
			POST BLOCK INDEX
			1. POST SETTING
		-----------------------------------------------------------------------------------*/

		/*-----------------------------------------------------------------------------------*/
		/*  1. POST SETTING
		/*-----------------------------------------------------------------------------------*/
		$this->start_controls_section(
			'section_sakolawp_myaccount_block_setting',
			[
				'label' => esc_html__( 'Post Setting', 'sakolawp' ),
			]
		);

		$this->add_responsive_control(
			'wrapper_width',
			[
				'label' => esc_html__( 'Wrapper Width', 'sakolawp' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1200,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 2400,
					],
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skwp-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*-----------------------------------------------------------------------------------
			end of post block post setting
		-----------------------------------------------------------------------------------*/

	}

	protected function render() {

		$instance = $this->get_settings();

		/*-----------------------------------------------------------------------------------*/
		/*  VARIABLES LIST
		/*-----------------------------------------------------------------------------------*/

		/* POST SETTING VARIBALES */


		/* end of variables list */


		/*-----------------------------------------------------------------------------------*/
		/*  THE CONDITIONAL AREA
		/*-----------------------------------------------------------------------------------*/

		include ( plugin_dir_path(__FILE__).'tpl/myaccount-block.php' );

		/*-----------------------------------------------------------------------------------
		  end of conditional end of post block.
		-----------------------------------------------------------------------------------*/

		?>

		<?php

	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new sakolawp_myaccount_block() );