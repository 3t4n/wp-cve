<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Better_Button extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-button';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Button', 'better-el-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Button Settings', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'button_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
					'3' => __( 'Style 3', 'better-el-addons' ),
					'4' => __( 'Style 4', 'better-el-addons' ),
					'5' => __( 'Style 5', 'better-el-addons' ),
					'6' => __( 'Style 6', 'better-el-addons' ),
					'7' => __( 'Style 7', 'better-el-addons' ),
					'8' => __( 'Style 8', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label' => __( 'Button Text','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => 'Click now',
				'condition' => [
					'button_style!' => '5',
					'button_style!' => '7'
				],
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => __( 'Button Link','better-el-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave Link here',
				'condition' => [
					'button_style!' => '3'
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => __( 'Button Alignment', 'better-el-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'better-el-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'better-el-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'better-el-addons'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .better-button' => 'text-align: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .better-button.style-2' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'button_style!' => '8'
				],
			]
		);

		$this->add_control(
			'button5_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
				],
				'default' => '1',
				'condition' => [
					'button_style' => array('5')
				],
			]
		);
		

		$this->end_controls_section();

		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => __( 'Border', 'better-el-addons' ),
				'selector' => '{{WRAPPER}} .better-button.style-4 a',
				'condition' => [
					'button_style' => array('4')
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-button.style-4 a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_style' => array('4')
				],
			]
		);

		// Price Plan Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_button_text_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-button span, {{WRAPPER}} .better-button.style-1 span, {{WRAPPER}} .better-button.style-2 a, {{WRAPPER}} .better-button.style-3 span, {{WRAPPER}} .better-button.style-4 span',
				'condition' => [
					'button_style!' => '5'
				],
			]
		);

		$this->add_control(
			'better_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-button a' => 'background: {{VALUE}};',
					'{{WRAPPER}} .better-button.style-3 button' => 'background: {{VALUE}};',
					'condition' => [
						'button_style!' => '5'
					],
				],
			]
        );

		$this->add_control(
			'better_button_text_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-button span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-button.style-2 a' => 'color: {{VALUE}};',
					'condition' => [
						'button_style!' => '5'
					],
				],
			]
        );

		$this->add_control(
			'better_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color Hover', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-button a:hover' => 'background: {{VALUE}};',
					'{{WRAPPER}} .better-button.style-3 button:hover' => 'background: {{VALUE}};',
					'condition' => [
						'button_style!' => '5'
					],
				],
			]
        );

		$this->add_control(
			'better_button_text_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-button:hover span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-button:hover a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-button.style-2:hover a' => 'color: {{VALUE}};',
				],
			]
        );

		$this->add_control(
			'better_button_icon_color',
			[
				'label' => esc_html__( 'Color Hover', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-button.style-5 .vid span' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_style' => '5'
				],
			]
        );

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		
        // Styles selections.
		$style = $settings['button_style'];
		include( 'styles/style'.$style.'.php' );
	
		
	 
		}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}

