<?php

use \Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Group_Control_Image_Size;

class bdfe_Hero_Slider extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'hero_slider';
	}
	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Hero Slider', BDFE_TEXT_DOMAIN );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-sliders';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'blogmaker' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', BDFE_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$select_post_categories = array();
		$select_post_cats = get_terms( 'category' );
		foreach ($select_post_cats as $select_post_cat) :
			$select_post_categories[$select_post_cat->term_id] = $select_post_cat->name;
		endforeach;
		$this->add_control(
			'select_categories',
			[
				'label' => __( 'Select Categories', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $select_post_categories,
				
			]
		);
		$this->add_control(
			'slider_post_count',
			[
				'label' => __( 'Post Count', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 15,
				'step' => 1,
				'default' => 3,
				
			]
		);
		$this->add_control(
			'post_image_show',
			[
				'label'        => __( 'Show Thumbnail ?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
				
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'sliderimage', // Actually its `image_size`.
				'default' => 'large',
				'exclude' => [ 'custom' ],
			]
		);
		$this->add_control(
			'post_slider_title_show',
			[
				'label'        => __( 'Show Title?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'show_slider_post_excerpt',
			[
				'label'        => __( 'Show Excerpt?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
				
			]
		);

		$this->add_control(
			'slider_excerpt_length',
			[
				'label' => __( 'Excerpt Length', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 70,
				'max' => 600,
				'step' => 10,
				'default' => 150,
				'condition' => [
					
					'show_slider_post_excerpt' => 'true',
				],
			]
		);
		$this->add_control(
			'show_slider_post_meta',
			[
				'label'        => __( 'Show Post Meta?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
				
			]
		);
		$this->add_control(
			'slider_post_meta_data',
			[
				'label' => __( 'Post Meta', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'author'	=>	__( 'Author', BDFE_TEXT_DOMAIN ),
					'date'	=>	__( 'Date', BDFE_TEXT_DOMAIN ),
					'comments'	=>	__( 'Comments', BDFE_TEXT_DOMAIN ),
				],
				'condition'	=>	[
					'show_slider_post_meta'	=>	'true',
					
				]
			]
		);
		$this->add_control(
			'show_slider_category',
			[
				'label'        => __( 'Show Category?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
				
			]
		);

		$this->add_control(
			'slider_post_read_button',
			[
				'label'        => __( 'Show Read More Button?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
				
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'        => __( 'Button Text', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::TEXT,
				'default'      => __( 'Read More', BDFE_TEXT_DOMAIN ),
				'condition' => [
					
					'slider_post_read_button' => 'true',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slide_settings',
			[
				'label' => __( 'Slides Settings', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'slide_to_show',
			[
				'label' => __( 'Slide To Show', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 1,
			]
		);
		$this->add_control(
			'margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
			]
		);
		$this->add_control(
			'slider_smart_speed',
			[
				'label' => __( 'Smart Speed', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
			]
		);
		$this->add_control(
			'slider_fluid_speed',
			[
				'label' => __( 'Fluid Speed', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
			]
		);
		$this->add_control(
			'slider_autoplay_speed',
			[
				'label' => __( 'Auto Play Speed', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
			]
		);
		$this->add_control(
			'slider_nav_speed',
			[
				'label' => __( 'Nav Speed', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
			]
		);
		$this->add_control(
			'slider_dost_speed',
			[
				'label' => __( 'Dots Speed', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
			]
		);
		$this->add_control(
			'center_mode_true',
			[
				'label'        => __( 'Center Mode True?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'No', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'autoplay_hover_push',
			[
				'label'        => __( 'Auto Play Hover', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'No', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'nav',
			[
				'label'        => __( 'Navigation Arrow', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'dots',
			[
				'label'        => __( 'Dots', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Auto Play', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'No', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'loop',
			[
				'label'        => __( 'Loop', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'No', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'mouseDrag',
			[
				'label'        => __( 'Mouse Drag', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'No', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'touchDrag',
			[
				'label'        => __( 'Touch Drag', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'No', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'autoplayTimeout',
			[
				'label'     => __( 'Autoplay Timeout', BDFE_TEXT_DOMAIN ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5000',
				'condition' => [
					'autoplay' => 'true',
				],
				'options' => [
					'5000'  => __( '5 Seconds', BDFE_TEXT_DOMAIN ),
					'10000' => __( '10 Seconds', BDFE_TEXT_DOMAIN ),
					'15000' => __( '15 Seconds', BDFE_TEXT_DOMAIN ),
					'20000' => __( '20 Seconds', BDFE_TEXT_DOMAIN ),
					'25000' => __( '25 Seconds', BDFE_TEXT_DOMAIN ),
					'30000' => __( '30 Seconds', BDFE_TEXT_DOMAIN ),
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'responsive_settings',
			[
				'label' => __( 'Responsive Settings', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'mobile_screen',
			[
				'label' => __( 'Mobile Screen for 360px', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 1,
			]
		);
		$this->add_control(
			'mobile_margin',
			[
				'label' => __( 'Mobile Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'label'        => __( 'Label', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_control(
			'tablet_screen',
			[
				'label' => __( 'Tablet Screen for 768px', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 1,
				
			]
		);
		$this->add_control(
			'tablet_margin',
			[
				'label' => __( 'Tablet Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
			]
		);
		$this->add_control(
			'hr2',
			[
				'type' => Controls_Manager::DIVIDER,
				'label'        => __( 'Label', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_control(
			'medium_screen',
			[
				'label' => __( 'Tablet Screen for 992px - 1200', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 1,
				
			]
		);
		$this->add_control(
			'medium_margin',
			[
				'label' => __( 'Tablet Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
			]
		);
		
		$this->end_controls_section();
		/*Style Tab*/
		$this->start_controls_section(
			'slider_settings',
			[
				'label' => __( 'Slider Settings', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'slider_height',
			[
				'label' => __( 'Slider Height', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 650,
				],
				'selectors' => [
					'{{WRAPPER}} .main-slider-area .main-hero-slider__post-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .main-slider-area .main-hero-slider__post-thumbnail img' => 'min-height: {{SIZE}}{{UNIT}};'
				]
			]
		);
		$this->add_control(
			'show_slider_overly',
			[
				'label' => __( 'Show Slider Overlay', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'slider_overlay',
				'label' => __( 'Slider Overlay', BDFE_TEXT_DOMAIN ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__post-thumbnail .slider_overlay',
				'condition' => [
					'show_slider_overly' => 'true'
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'slider_content_background',
				'label' => __( 'Slider Content Background', BDFE_TEXT_DOMAIN ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .main-hero-slider .main-hero-slider__content >.container',
			]
		);
		$this->add_responsive_control(
			'slider_content_box_padding',
			[
				'label' => __( 'Content Box Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .main-hero-slider .main-hero-slider__content >.container, .main-slider-area .main-hero-slider__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'slider_content_box_margin',
			[
				'label' => __( 'Content Box Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .main-hero-slider .main-hero-slider__content >.container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'slider_content_box_radious',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .main-hero-slider .main-hero-slider__content >.container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'slider_content_vr_alignment',
			[
				'label'     => __( 'Content Alignment Verticle', BDFE_TEXT_DOMAIN ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options' => [
					'flex-start'  => __( 'TOP', BDFE_TEXT_DOMAIN ),
					'center' => __( 'Center', BDFE_TEXT_DOMAIN ),
					'flex-end' => __( 'Bottom', BDFE_TEXT_DOMAIN ),
				],
				'selectors' => [
					'{{WRAPPER}} .main-slider-area .main-hero-slider__content' => 'align-items: {{VALUE}}'
				]
			]
		);
		$this->add_control(
			'slider_content_text_alignment',
			[
				'label'     => __( 'Text Alignment', BDFE_TEXT_DOMAIN ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options' => [
					'left'  => __( 'Left', BDFE_TEXT_DOMAIN ),
					'center' => __( 'Center', BDFE_TEXT_DOMAIN ),
					'right' => __( 'Right', BDFE_TEXT_DOMAIN ),
				],
				'selectors' => [
					'{{WRAPPER}} .main-slider-area .main-hero-slider__content' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_thumnail',
			[
				'label' => __( 'Slider Thumbnail', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'slider_image',
				'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__post-thumbnail img',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'slider_thumbnail_background',
				'label' => __( 'Thumbnail Background', BDFE_TEXT_DOMAIN ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__post-thumbnail.backgrouncolor',
			]
		);
		$this->add_control(
			'slider_thumbnail_border_radius',
			[
				'label' => __( 'Border Radius', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .main-slider-area .main-hero-slider__post-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'slider_title_style',
			[
				'label' => __( 'Slider Title', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'slider_title_tabs'
		);
		$this->start_controls_tab(
			'slider_title_style_normal',
			[
				'label' => __( 'Normal', BDFE_TEXT_DOMAIN ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'slider_title_typography',
				'selector' => '{{WRAPPER}} .main-title',
			]
		);
		$this->add_control(
			'slider_heading_color',
			[
				'label' => __( 'Title Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .main-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'slider_heading_text_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .main-title a',
			]
		);
		$this->add_responsive_control(
			'slider_heading_padding',
			[
				'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .main-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'slider_heading_margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .main-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'slider_title_style_hover',
			[
				'label' => __( 'Hover', BDFE_TEXT_DOMAIN ),
			]
		);
		
		$this->add_control(
			'slider_heading_hover_color',
			[
				'label' => __( 'Title Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .main-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'slider_heading_text_shadow_hover',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .main-title a:hover',
			]
		);
	$this->end_controls_tab();
	$this->end_controls_tabs();
	$this->end_controls_section();
	$this->start_controls_section(
		'slider_description_style',
		[
			'label' => __( 'Slider Description', BDFE_TEXT_DOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
			
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'slider_description_typography',
			'selector' => '{{WRAPPER}} .excerpt',
		]
	);
	$this->add_control(
		'slider_description_color',
		[
			'label' => __( 'Description Color', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .excerpt' => 'color: {{VALUE}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Text_Shadow::get_type(),
		[
			'name' => 'slider_description_text_shadow',
			'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
			'selector' => '{{WRAPPER}} .excerpt',
		]
	);
	$this->add_responsive_control(
		'slider_description_padding',
		[
			'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);
	$this->add_responsive_control(
		'slider_description_margin',
		[
			'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);

	$this->end_controls_section();
	$this->start_controls_section(
		'slider_category_style',
		[
			'label' => __( 'Slider category', BDFE_TEXT_DOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition'	=>	[
				'show_slider_category' => 'true',
			]
		]
	);
	$this->start_controls_tabs(
			'slider_category_tabs'
		);
	$this->start_controls_tab(
		'slider_category_normal_tab',
		[
			'label' => __( 'Normal', BDFE_TEXT_DOMAIN ),
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'slider_category_typography',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__categories a',
		]
	);
	$this->add_control(
		'slider_category_color',
		[
			'label' => __( 'Category Color', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories a' => 'color: {{VALUE}};',
			],
		]
	);
	$this->add_control(
		'slider_category_bg_color',
		[
			'label' => __( 'Category Background', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories a' => 'background: {{VALUE}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'slider_category_border',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__categories a',
			'separator' => 'before',
		]
	);

	$this->add_control(
		'slider_category_border_radius',
		[
			'label' => __( 'Border Radius', 'elementor' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Text_Shadow::get_type(),
		[
			'name' => 'slider_category_text_shadow',
			'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__categories a',
		]
	);
	$this->add_responsive_control(
		'slider_category_padding',
		[
			'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);
	$this->add_responsive_control(
		'slider_category_margin',
		[
			'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);
	$this->end_controls_tab();
	$this->start_controls_tab(
		'slider_category_hover_tab',
		[
			'label' => __( 'Hover', BDFE_TEXT_DOMAIN ),
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'slider_category_hover_typography',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__categories a:hover',
		]
	);
	$this->add_control(
		'slider_category_hover_color',
		[
			'label' => __( 'Category Color', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories a:hover' => 'color: {{VALUE}};',
			],
		]
	);
	$this->add_control(
		'slider_category_hover_bg_color',
		[
			'label' => __( 'Category Background', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories a:hover' => 'background: {{VALUE}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'slider_hover_category_border',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__categories a:hover',
			'separator' => 'before',
		]
	);

	$this->add_control(
		'slider_category_hover_border_radius',
		[
			'label' => __( 'Border Radius', 'elementor' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__categories a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$this->end_controls_tab();
	$this->end_controls_tabs();
	$this->end_controls_section();
	$this->start_controls_section(
		'slider_post_meta_style',
		[
			'label' => __( 'Slider Post Meta', BDFE_TEXT_DOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition'	=>	[
				'show_slider_post_meta' => 'true',
			]
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'slider_post_meta_typography',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__blog-meta li a',
		]
	);
	$this->add_control(
		'slider_post_meta_color',
		[
			'label' => __( 'Meta Color', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__blog-meta li a, {{WRAPPER}} .main-slider-area .main-hero-slider__blog-meta li span' => 'color: {{VALUE}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Text_Shadow::get_type(),
		[
			'name' => 'slider_post_meta_text_shadow',
			'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__blog-meta li',
		]
	);
	$this->add_responsive_control(
		'slider_post_meta_padding',
		[
			'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__blog-meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);
	$this->add_responsive_control(
		'slider_post_meta_margin',
		[
			'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__blog-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);

	$this->end_controls_section();

	$this->start_controls_section(
		'slider_button_style',
		[
			'label' => __( 'Slider button', BDFE_TEXT_DOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);
	$this->start_controls_tabs(
			'slider_button_style_tab'
		);
	$this->start_controls_tab(
		'slider_button_normal',
		[
			'label' => __( 'Normal', BDFE_TEXT_DOMAIN ),
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'slider_button_typography',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a',
		]
	);
	$this->add_control(
		'slider_button_color',
		[
			'label' => __( 'Button Color', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a' => 'color: {{VALUE}};',
			],
		]
	);
	$this->add_control(
		'slider_button_bg_color',
		[
			'label' => __( 'Button Background', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a' => 'background: {{VALUE}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'slider_button_border',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a',
			'separator' => 'before',
		]
	);

	$this->add_control(
		'slider_button_border_radius',
		[
			'label' => __( 'Border Radius', 'elementor' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Text_Shadow::get_type(),
		[
			'name' => 'slider_button_text_shadow',
			'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a',
		]
	);
	$this->add_responsive_control(
		'slider_button_padding',
		[
			'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);
	$this->add_responsive_control(
		'slider_button_margin',
		[
			'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator' => 'before',
		]
	);
	$this->end_controls_tab();
	$this->start_controls_tab(
		'slider_button_hover',
		[
			'label' => __( 'Hover', BDFE_TEXT_DOMAIN ),
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'slider_button_hover_typography',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a:hover, {{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a.active_button',
		]
	);
	$this->add_control(
		'slider_button_hover_color',
		[
			'label' => __( 'Button Color', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a:hover, {{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a.active_button' => 'color: {{VALUE}};',
			],
		]
	);
	$this->add_control(
		'slider_button_bg_hover_color',
		[
			'label' => __( 'Button Background', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a:hover, {{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a.active_button' => 'background: {{VALUE}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'slider_button_hover_border',
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a:hover, {{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a.active_button',
			'separator' => 'before',
		]
	);

	$this->add_control(
		'slider_button_border_radius_hover',
		[
			'label' => __( 'Border Radius', 'elementor' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a:hover, {{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a.active_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Text_Shadow::get_type(),
		[
			'name' => 'slider_button_text_shadow_hover',
			'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
			'selector' => '{{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a:hover, {{WRAPPER}} .main-slider-area .main-hero-slider__content .welcome-button a.active_button',
		]
	);
	$this->end_controls_tab();
	$this->end_controls_tabs();
	$this->end_controls_section();
	$this->start_controls_section(
		'slider_navigation_style',
		[
			'label' => __( 'Slider navigation', BDFE_TEXT_DOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_control(
		'slider_navigation_color',
		[
			'label' => __( 'Navigation Color', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-nav button' => 'color: {{VALUE}} !important;',
			],
		]
	);
	$this->add_control(
		'slider_navigation_bg_color',
		[
			'label' => __( 'Navigation Background', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-nav button' => 'background: {{VALUE}} !important;',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'slider_navigation_border',
			'selector' => '{{WRAPPER}} .main-slider-area .owl-nav button',
			'separator' => 'before',
		]
	);

	$this->add_control(
		'slider_navigation_border_radius',
		[
			'label' => __( 'Border Radius', 'elementor' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$this->add_control(
		'slider_navigation_width',
		[
			'label' => __( 'Width', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 40,
			],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-nav button' => 'width: {{SIZE}}{{UNIT}};',
			]
		]
	);
	$this->add_control(
		'slider_navigation_height',
		[
			'label' => __( 'Height', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 100,
					'step' => 1,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 40,
			],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-nav button' => 'height: {{SIZE}}{{UNIT}};',
			]
		]
	);

	$this->end_controls_section();
	$this->start_controls_section(
		'slider_dots_style',
		[
			'label' => __( 'Slider Dots', BDFE_TEXT_DOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_control(
		'slider_dots_bg_color',
		[
			'label' => __( 'Dots Background', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-dots button' => 'background: {{VALUE}} !important;',
			],
		]
	);
	$this->add_control(
		'slider_dots_active_bg_color',
		[
			'label' => __( 'Active Background', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-dots button.active' => 'background: {{VALUE}} !important;',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'slider_dots_border',
			'selector' => '{{WRAPPER}} .main-slider-area .owl-dots button',
			'separator' => 'before',
		]
	);

	$this->add_control(
		'slider_dots_border_radius',
		[
			'label' => __( 'Border Radius', 'elementor' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-dots button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$this->add_control(
		'slider_dots_width',
		[
			'label' => __( 'Width', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 50,
					'step' => 1,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 10,
			],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-dots button' => 'width: {{SIZE}}{{UNIT}};',
			]
		]
	);
	$this->add_control(
		'slider_dots_height',
		[
			'label' => __( 'Height', BDFE_TEXT_DOMAIN ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 50,
					'step' => 1,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 10,
			],
			'selectors' => [
				'{{WRAPPER}} .main-slider-area .owl-dots button' => 'height: {{SIZE}}{{UNIT}};',
			]
		]
	);

	$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$sliderDynamicId 		   = rand(10, 100000);
		$nav             		   = $settings['nav'] ? $settings['nav'] : 'false';
		$dots            		   = $settings['dots'] ? $settings['dots'] : 'false';
		$autoplay        		   = $settings['autoplay'] ? $settings['autoplay'] : 'false';
		$autoplayhoverpush         = $settings['autoplay_hover_push'] ? $settings['autoplay_hover_push'] : 'true';
		$loop            		   = $settings['loop'] ? $settings['loop'] : 'false';
		$mouseDrag       		   = $settings['mouseDrag'] ? $settings['mouseDrag'] : 'false';
		$touchDrag       		   = $settings['touchDrag'] ? $settings['touchDrag'] : 'false';
		$smartspeed       		   = $settings['slider_smart_speed'] ? $settings['slider_smart_speed'] : '250';
		$fluidspeed       		   = $settings['slider_fluid_speed'] ? $settings['slider_fluid_speed'] : 'false';
		$autoplayspeed       	   = $settings['slider_autoplay_speed'] ? $settings['slider_autoplay_speed'] : 'false';
		$navspeed       	       = $settings['slider_nav_speed'] ? $settings['slider_nav_speed'] : 'false';
		$dotspeed       	       = $settings['slider_dost_speed'] ? $settings['slider_dost_speed'] : 'false';
		$center_mode_true       	       = $settings['center_mode_true'] ? $settings['center_mode_true'] : 'false';
		$autoplayTimeout 		   = $settings['autoplayTimeout'] ? $settings['autoplayTimeout'] : '0';
		$slide_to_show   		   = $settings['slide_to_show'];
		$slide_to_show_on_mobile   = $settings['mobile_screen'];
		$slide_to_show_on_tablet   = $settings['tablet_screen'];
		$slide_to_show_on_medium   = $settings['medium_screen'];
		$item_margin     		   = $settings['margin'];
		$item_margin_on_mobile     = $settings['mobile_margin'];
		$item_margin_on_tablet     = $settings['tablet_margin'];
		$item_margin_on_medium     = $settings['medium_margin'];
		$this->add_render_attribute(
            'slider-wrapper',
            [
                'class'                 => 'active-main-slider owl-carousel',
                'id'                    => 'active-mainslider-'.esc_attr($sliderDynamicId),
                'data-items'       		=> $slide_to_show,
                'data-mobile-items'     => $slide_to_show_on_mobile,
                'data-tablet-items'     => $slide_to_show_on_tablet,
                'data-medium-items'     => $slide_to_show_on_medium,
                'data-dots'             => $dots,
                'data-nav'              => $nav,
                'data-loop'             => $loop,
                'data-autoplay'         => $autoplay,
                'data-autoplay-timeout' => $autoplayTimeout,
                'data-mouse-drag'       => $mouseDrag,
                'data-touch-drag'       => $touchDrag,
                'data-margin'           => $item_margin['size'],
                'data-mobile-margin'    => $item_margin_on_mobile['size'],
                'data-tablet-margin'    => $item_margin_on_tablet['size'],
                'data-medium-margin'    => $item_margin_on_medium['size'],
                'data-smart-speed'    => $smartspeed['size'],
                'data-autoplay-speed'    => $autoplayspeed['size'],
                'data-fluid-speed'    => $fluidspeed['size'],
                'data-nav-speed'    => $navspeed['size'],
                'data-dot-speed'    => $dotspeed['size'],
                'data-auto-hover'    => $autoplayhoverpush,
                'data-center-mode'    => $center_mode_true,
            ]
        );
		$bdfe_get_categories = $settings['select_categories'];		
		$slider_post_count = $settings['slider_post_count'];
		$get_post_args = array(
			'post_type' => array('post'),
			'category__in' => $bdfe_get_categories,
			'posts_per_page' => $slider_post_count,
		);
		?>
		<section class="main-slider-area">
			<div <?php echo $this->get_render_attribute_string('slider-wrapper'); ?>>
			<?php
			$get_post_query = new WP_Query($get_post_args);
			while ($get_post_query->have_posts()) :
				$get_post_query->the_post();
			?>
			<div class="main-hero-slider">
				<?php $this->bdfe_render_post_thumbnail(); ?>
			    <div class="main-hero-slider__content">
			         <div class="container">
				         <?php
				        $this->bdfe_render_category();
				    	$this->bdfe_render_title();
				    	$this->bdfe_render_post_meta();
				    	$this->bdfe_render_excerpt();
				    	$this->bdfe_render_post_readmore_button();
				    	?>
			        </div>
			    </div>
			</div>
			<?php
			 endwhile;  wp_reset_postdata();
			 ?>
			</div>
	    </section>
		<?php
	}
	private function bdfe_render_post_thumbnail(){
		$settings = $this->get_settings_for_display();
		$post_image_show  = $settings['post_image_show'];
		$bdfe_image_size = $settings['sliderimage_size'];
		$show_slider_overlay = $settings['show_slider_overly'];
		$nopost_thumbnail_class = 'true' === $post_image_show ? '' : ' backgrouncolor'; ?>
	    <div class="main-hero-slider__post-thumbnail<?php echo esc_attr( $nopost_thumbnail_class );?>">
	    	<?php if ('true' === $show_slider_overlay):?>
	    	<div class="slider_overlay"></div>
	    	<?php endif; ?>
	        <?php
	        if ('true' === $post_image_show) :
	        	if (get_transient( 'bdfe_image_size' )) {
	        		$bdfe_get_image_size = get_transient( 'bdfe_image_size' );
	        	}else{
	        		$bdfe_get_image_size = 'full';
	        	}
	         the_post_thumbnail( $bdfe_get_image_size );
	     	endif;
	         ?>
	    </div>
	   <?php
	}
	private function bdfe_render_title(){
		$settings = $this->get_settings_for_display();
		$post_title_show = $settings['post_slider_title_show'];
		if ('true' === $post_title_show) :
		?>
		<h1 class="main-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h1>
		<?php
		endif;
	}
	private function bdfe_render_category(){
		$settings = $this->get_settings_for_display();
		$show_slider_category = $settings['show_slider_category'];
		if('true' === $show_slider_category) : ?>
         	<div class="main-hero-slider__categories">
				<?php
				$categories_list = get_the_category_list( esc_html__( ' ', BDFE_TEXT_DOMAIN ) );
				if ( $categories_list ) {
					/* translators: 1: list of categories. */
					printf( '<span class="cat-links">' . __( '%1$s', BDFE_TEXT_DOMAIN ) . '</span>', $categories_list ); // WPCS: XSS OK.
				}
				?>
			</div>
		<?php endif;
	}
	private function bdfe_render_post_meta(){
		$settings = $this->get_settings_for_display();
		$show_slider_post_meta = $settings['show_slider_post_meta'];
		$get_slider_post_meta_data = is_array($settings['slider_post_meta_data']) ? implode(',', $settings['slider_post_meta_data']) : array();
		$slider_post_meta_data = (!empty($get_slider_post_meta_data) ? explode(',', $get_slider_post_meta_data) : array());
		
		if ('true' === $show_slider_post_meta) :?>
        <div class="main-hero-slider__blog-meta">
			<ul>
			<?php if(in_array('author', $slider_post_meta_data)) : ?>
			<li class="author-meta"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>"><span class="post-author-image"><?php echo get_avatar( get_the_author_meta('ID'), 30); ?></span> <?php echo esc_html( get_the_author() ); ?></a></li>
			<?php endif;
			if(in_array('date', $slider_post_meta_data)) :
			?>
			<li><a href="#"> <span class="far fa-calendar-alt"></span><?php bdfe_posted_on(); ?></a></li>
			<?php endif;
			if(in_array('comments', $slider_post_meta_data)) :
			?>
			<li><span class="fas fa-comment"></span> <?php bdfe_comment_popuplink(); ?></li>
			<?php endif; ?>
			</ul>
		</div>
		<?php endif;
	}
	private function bdfe_render_excerpt(){
		$settings = $this->get_settings_for_display();
		$slider_excerpt_length 			   = $settings['slider_excerpt_length'];
		$show_slider_post_excerpt = $settings['show_slider_post_excerpt'];
		if ('true' === $show_slider_post_excerpt) :?>
	    	<p class="excerpt"> <?php echo esc_html( bdfe_get_excerpt( $slider_excerpt_length ) ); ?> </p>
		<?php endif; 
	}
	private function bdfe_render_post_readmore_button(){
		$settings = $this->get_settings_for_display();
		$slider_post_read_button = $settings['slider_post_read_button'];
		$button_text  = $settings['button_text'];
		if('true' === $slider_post_read_button): ?>
        <div class="welcome-button">
            <a href="<?php the_permalink();?>" class="btn btn-default button-primary"><?php echo esc_html($button_text); ?></a>
        </div>
    	<?php endif; 
	}

}