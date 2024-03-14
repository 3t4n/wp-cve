<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 0.1.8
 */
class Search extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-search';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Search', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-search xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-themer' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'search', 'bar', 'searching', 'find' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 0.1.8
	 * @access protected
	 */
	protected function register_controls() {

		//Button Primary
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'              => __( 'Layout', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'options'            => array(
					'1' => __( 'Classic', 'xpro-elementor-addons' ),
					'2' => __( 'Minimal', 'xpro-elementor-addons' ),
					'3' => __( 'Creative', 'xpro-elementor-addons' ),
					'4' => __( 'Full Screen', 'xpro-elementor-addons' ),
					'5' => __( 'Half Screen', 'xpro-elementor-addons' ),
				),
				'frontend_available' => true,
			)
		);

		$post_types        = xpro_elementor_get_post_types();
		$post_types['any'] = __( 'Any', 'xpro-elementor-addons' );

		$this->add_control(
			'post_type',
			array(
				'label'   => __( 'Source', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_types,
				'default' => 'post',
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'   => __( 'Placeholder', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search', 'xpro-elementor-addons' ) . '...',
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_heading',
			array(
				'label'     => __( 'Button', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout' => array( '1' ),
				),
			)
		);

		$this->add_control(
			'button_type',
			array(
				'label'     => __( 'Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'icon',
				'options'   => array(
					'icon' => __( 'Icon', 'xpro-elementor-addons' ),
					'text' => __( 'Text', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'layout' => array( '1' ),
				),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Text', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Search', 'xpro-elementor-addons' ),
				'separator' => 'after',
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'button_type' => 'text',
					'layout'      => array( '1' ),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'       => __( 'Icon', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => array(
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'button_type!' => 'text',
				),
			)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
			'section_input_style',
			array(
				'label' => __( 'Input', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_background_color',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( '4', '5' ),
				),
			)
		);

		$this->add_control(
			'close_btn_color',
			array(
				'label'     => __( 'Close Button', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-button-close:before,{{WRAPPER}}  .xpro-elementor-search-button-close:after' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( '4', '5' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group > input',
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 1000,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 400,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper:not(.xpro-elementor-search-layout-3) .xpro-elementor-search-input-group > input,
					{{WRAPPER}} .xpro-elementor-search-layout-3 .xpro-elementor-search-input-group:hover > input,{{WRAPPER}} .xpro-elementor-search-layout-3 .xpro-elementor-search-input-group:focus-within > input' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group > input,{{WRAPPER}} .xpro-elementor-search-button' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-search-button'                                                                                       => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-search-layout-3 .xpro-elementor-search-input-group > input'                                          => 'width: {{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'input_icon_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-search-layout-2 .xpro-elementor-search-input-group i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-search-layout-2 svg' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => '2',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'input_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group input,{{WRAPPER}} .xpro-elementor-search-layout-2 .xpro-elementor-search-input-group i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group input::placeholder'                                                                     => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-elementor-search-button > svg'                                                                                 => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'input_background_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group,{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group > input' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'input_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			array(
				'label' => __( 'Focus', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'input_text_color_focus',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group > input:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'input_background_color_focus',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group:focus-within,{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group > input:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'input_border_color_focus',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'     => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-input-group > input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Button', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout!' => array( '2' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .xpro-elementor-search-button',
				'condition' => array(
					'layout'      => array( '1' ),
					'button_type' => 'text',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-elementor-search-button > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'label'    => esc_html__( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-search-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-button:hover,{{WRAPPER}} .xpro-elementor-search-button:focus' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-elementor-search-button:hover > svg,{{WRAPPER}} .xpro-elementor-search-button:focus > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hbackground',
				'label'    => esc_html__( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-search-button:hover,{{WRAPPER}} .xpro-elementor-search-button:focus',
			)
		);

		$this->add_control(
			'button_text_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-button:hover,{{WRAPPER}} .xpro-elementor-search-button:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-button > i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .xpro-elementor-search-button > svg' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'button_type' => 'icon',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'button_bg_size',
			array(
				'label'     => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-wrapper .xpro-elementor-search-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-search-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-search-button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'xpro-elementor-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'xpro-elementor-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-search-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.1.8
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'search/layout/frontend.php';

	}

}
