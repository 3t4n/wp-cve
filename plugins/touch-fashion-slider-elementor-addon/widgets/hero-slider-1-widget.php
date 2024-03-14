<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;      // Exit if accessed directly.
}
/**
 * Touch Fashion Hero Slider 1
 */
class Tfsel_Hero_Slider_1 extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve tfsel Fashion Slider widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'touch-fashion-slider-hero-slider-1';
	}


	/**
	 * Get widget title.
	 *
	 * Retrieve tfsel Fashion Slider widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Hero Slider 1', 'touch-fashion-slider' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve tfsel Fashion Slider widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slider-push';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'image', 'photo', 'visual', 'carousel', 'slider', 'tfsel' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the tfsel Fashion Slider widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'touch-fashion-slider' );
	}

	/**
	 * Register tfsel Fashion Slider widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Tfsel Hero Slider v1', 'touch-fashion-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'slide_title',
			array(
				'label'       => esc_html__( 'Title', 'touch-fashion-slider' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Add Title Here', 'touch-fashion-slider' ),
				'placeholder' => esc_html__( 'Type your title here', 'touch-fashion-slider' ),
			)
		);
		$repeater->add_control(
			'slide_subtitle',
			array(
				'label'       => esc_html__( 'Sub Title', 'touch-fashion-slider' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Sub Title Here', 'touch-fashion-slider' ),
				'placeholder' => esc_html__( 'Enter SubTitle here', 'touch-fashion-slider' ),
			)
		);

		$repeater->add_control(
			'color_mod',
			array(
				'label'   => esc_html__( 'Color Mod', 'touch-fashion-slider' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'Default' => array(
						'title' => esc_html__( 'Default', 'touch-fashion-slider' ),
						'icon'  => 'fa fa-palette',
					),
					'Custom'  => array(
						'title' => esc_html__( 'Custom', 'touch-fashion-slider' ),
						'icon'  => 'fa fa-eye-dropper',
					),
				),
				'default' => 'Default',
				'toggle'  => true,
			),
		);

		$repeater->add_control(
			'slide_color',
			array(
				'label'     => esc_html__( 'Slide Color', 'touch-fashion-slider' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'Pink',
				'condition' => array(
					'color_mod'  => 'Default',
					'color_mod!' => 'Custom',
				),
				'options'   => array(
					'#dc3545' => esc_html__( 'Red', 'touch-fashion-slider' ),
					'#e83e8c' => esc_html__( 'Pink', 'touch-fashion-slider' ),
					'#8bc34a' => esc_html__( 'Indigo', 'touch-fashion-slider' ),
					'#6f42c1' => esc_html__( 'Purple', 'touch-fashion-slider' ),
					'#311b92' => esc_html__( 'Deep Purple', 'touch-fashion-slider' ),
					'#fd7e14' => esc_html__( 'Orange', 'touch-fashion-slider' ),
					'#bf360c' => esc_html__( 'Deep-Orange', 'touch-fashion-slider' ),
					'#ffc107' => esc_html__( 'Yellow', 'touch-fashion-slider' ),
					'#28a745' => esc_html__( 'Green', 'touch-fashion-slider' ),
					'#20c997' => esc_html__( 'Teal', 'touch-fashion-slider' ),
					'Lime'    => esc_html__( 'Lime', 'touch-fashion-slider' ),
					'#2196f3' => esc_html__( 'Blue', 'touch-fashion-slider' ),
					'#00bcd4' => esc_html__( 'Light-Blue', 'touch-fashion-slider' ),
					'#17a2b8' => esc_html__( 'Cyan', 'touch-fashion-slider' ),
					'#8bc34a' => esc_html__( 'Light-Green', 'touch-fashion-slider' ),
					'#795548' => esc_html__( 'Brown', 'touch-fashion-slider' ),
					'#6c757d' => esc_html__( 'Grey', 'touch-fashion-slider' ),
					'#6c757d' => esc_html__( 'Dark-Grey', 'touch-fashion-slider' ),
					'#343a40' => esc_html__( 'Dark', 'touch-fashion-slider' ),
					'black'   => esc_html__( 'Black', 'touch-fashion-slider' ),
					'#e0e0e0' => esc_html__( 'White', 'touch-fashion-slider' ),
				),
			)
		);

		$repeater->add_control(
			'slide_custom_color',
			array(
				'label'     => esc_html__( 'Slide Color', 'touch-fashion-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Core\Schemes\Color::get_type(),
					'value' => Core\Schemes\Color::COLOR_1,
				),
				'condition' => array(
					'color_mod!' => 'Default',
					'color_mod'  => 'Custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'slide_image',
			array(
				'label'   => esc_html__( 'Add Images', 'touch-fashion-slider' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'slide_btn_text',
			array(
				'label'       => esc_html__( 'Button Text', 'touch-fashion-slider' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Buy', 'touch-fashion-slider' ),
				'placeholder' => esc_html__( 'Button Text Here', 'touch-fashion-slider' ),
			)
		);

		$repeater->add_control(
			'slide_url',
			array(
				'label'         => esc_html__( 'Product URL', 'touch-fashion-slider' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => esc_html__( '#', 'touch-fashion-slider' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => false,
				),
			)
		);

		$repeater->add_control(
			'slide_icon',
			array(
				'label'   => esc_html__( 'Bottom Icon', 'touch-fashion-slider' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => '',
					'library' => 'solid',
				),
			)
		);

		$this->add_control(
			'tfsel_slide',
			array(
				'label'                => esc_html__( 'Slides List', 'touch-fashion-slider' ),
				'type'                 => \Elementor\Controls_Manager::REPEATER,
				'fields'               => $repeater->get_controls(),
				'default'              => array(
					array(
						'slide_title'    => esc_html__( 'Slide #1', 'touch-fashion-slider' ),
						'slide_subtitle' => esc_html__( 'Slide 1 details here', 'touch-fashion-slider' ),
						'slide_color'    => esc_html__( 'Red', 'touch-fashion-slider' ),
						'slide_icon'     => esc_html__( 'fa fa-shopping-bag', 'touch-fashion-slider' ),
						'slide_btn_text' => esc_html__( 'Buy Now', 'touch-fashion-slider' ),
					),
					array(
						'slide_title'    => esc_html__( 'Slide #2', 'touch-fashion-slider' ),
						'slide_subtitle' => esc_html__( 'Slide 2 details here', 'touch-fashion-slider' ),
						'slide_color'    => esc_html__( 'Orange', 'touch-fashion-slider' ),
						'slide_icon'     => esc_html__( 'fa fa-shopping-cart', 'touch-fashion-slider' ),
						'slide_btn_text' => esc_html__( 'Buy Now', 'touch-fashion-slider' ),
					),
				),
				'image_field'          => '{{{ slide_image }}}',
				'title_field'          => '{{{ slide_title }}}',
				'subtitle_field'       => '{{{ slide_subtitle }}}',
				'color_field'          => '{{{ slide_color }}}',
				'url_field'            => '{{{ slide_url }}}',
				'custom_color_field'   => '{{{ custom_color }}}',
				'slide_btn_text_field' => '{{{ slide_btn_text }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings_section',
			array(
				'label' => esc_html__( 'Slider Settings', 'touch-fashion-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'slider_height',
			array(
				'label'       => esc_html__( 'Slider Height', 'touch-fashion-slider' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '100vh',
				'options'     => array(
					'100vh' => esc_html__( '100vh', 'touch-fashion-slider' ),
					'75vh'  => esc_html__( '75vh', 'touch-fashion-slider' ),
					'50vh'  => esc_html__( '50vh', 'touch-fashion-slider' ),
				),
				'placeholder' => esc_html__( 'Select Height', 'touch-fashion-slider' ),

			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'              => esc_html__( 'Navigation', 'touch-fashion-slider' ),
				'type'               => \Elementor\Controls_Manager::SELECT,
				'default'            => 'both',
				'options'            => array(
					'both'   => esc_html__( 'Arrows and Lines', 'touch-fashion-slider' ),
					'arrows' => esc_html__( 'Arrows', 'touch-fashion-slider' ),
					'dots'   => esc_html__( 'Lines', 'touch-fashion-slider' ),
					'none'   => esc_html__( 'None', 'touch-fashion-slider' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => esc_html__( 'Animation Speed', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'default'            => 500,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_section',
			array(
				'label' => esc_html__( 'General', 'touch-fashion-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tfsel_hero_slider_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'touch-fashion-slider' ),
				'selector' => '{{WRAPPER}} .tfsel-slider-container',
			)
		);

		$this->add_responsive_control(
			'tfsel_hero_slider_area_margin',
			array(
				'label'      => esc_html__( 'Margin', 'touch-fashion-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .tfsel-slider-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'tfsel_hero_slider_area_padding',
			array(
				'label'      => esc_html__( 'Padding', 'touch-fashion-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .tfsel-slider-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'tfsel_hero_slider_area_border',
				'label'    => esc_html__( 'Border', 'touch-fashion-slider' ),
				'selector' => '{{WRAPPER}} .tfsel-slider-container',
			)
		);

		$this->add_responsive_control(
			'tfsel_hero_slider_area_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'touch-fashion-slider' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .tfsel-slider-container' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'styles_section',
			array(
				'label' => esc_html__( 'Typography Controls', 'touch-fashion-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Title', 'touch-fashion-slider' ),
				'scheme'   => Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .slide-text h1',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typo',
				'label'    => esc_html__( 'Sub Title', 'touch-fashion-slider' ),
				'scheme'   => Core\Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .slide-text p',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typo',
				'label'    => esc_html__( 'Button', 'touch-fashion-slider' ),
				'scheme'   => Core\Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .slide-text .btn',
			)
		);

		$this->end_controls_section();

	}
	/**
	 * Render listing card widget output on the front-end.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		include TFS_EL_PLUGIN_PATH . 'include/hero-slider/hero-1.php';
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Tfsel_Hero_Slider_1() );
