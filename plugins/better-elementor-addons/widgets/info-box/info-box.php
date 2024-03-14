<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  


		
/**
 * @since 1.0.8
 */
class Better_Info_Box extends Widget_Base { 

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.8
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-infobox';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.8
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Info box', 'better_plg' );
	}

	//script depend
	public function get_script_depends() { return [ 'swiper','wow','isotope','youtubepopup-js','bootstrap-js','splitting','parallaxie','simpleParallax','justifiedgallery','scrollit','jquery.twentytwenty','counterup','better-el-addons']; }

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.8
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slideshow';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.8
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
	 * @since 1.0.8
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Info box Settings', 'better-el-addons' ),
			]
		);
		
		$this->add_control(
			'better_infobox_style',
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
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'letter',
			[
				'label' => __( 'Letter','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Leave it blank if you don\'t want to use this subtitle',
				'default' => 'Letter here',
				'condition' => [
					'better_infobox_style' => array('5')
				],
			]
		);

		$this->add_control(
			'better_infobox_style6_type',
			[
				'label' => __( 'Mode', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Number', 'better-el-addons' ),
					'2' => __( 'Icon', 'better-el-addons' ),
				],
				'default' => '1',
				'condition' => [
					'better_infobox_style' => array('5')
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Number','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Leave it blank if you don\'t want to use this subtitle',
				'default' => 'Number here',
				'condition' => [
					'better_infobox_style6_type' => array('1'),
					'better_infobox_style' => array('5'),
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Insert your title..',
				'default' => 'Title here',

			]
		);
		
		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Leave it blank if you don\'t want to use this subtitle',
				'default' => 'Sub-title here',
				'condition' => [
					'better_infobox_style' => array('1')
				],
			]
		);
		$this->add_control(
			'text',
			[
				'label' => __( 'Text','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Leave it blank if you don\'t want to use this subtitle',
				'default' => 'Sub-title here',
			]
		);

		$this->add_control(
			'better_infobox_pe7_icon',
			[
				'label' => __( 'Icon', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'pe-7s-map-marker' => __( 'map marker', 'better-el-addons' ),
					'pe-7s-mail' => __( 'mail', 'better-el-addons' ),
					'pe-7s-call' => __( 'call', 'better-el-addons' ),
					'pe-7s-refresh' => __( 'refresh', 'better-el-addons' ),
					'pe-7s-refresh-2' => __( 'refresh 2', 'better-el-addons' ),
					'pe-7s-cloud-upload' => __( 'cloud upload', 'better-el-addons' ),
					'pe-7s-close' => __( 'close', 'better-el-addons' ),
					'pe-7s-photo' => __( 'photo', 'better-el-addons' ),
					'pe-7s-users' => __( 'users', 'better-el-addons' ),
					'pe-7s-angle-right' => __( 'angle right', 'better-el-addons' ),
					'pe-7s-angle-left' => __( 'angle left', 'better-el-addons' ),
					'pe-7s-angle-up' => __( 'angle up', 'better-el-addons' ),
					'pe-7s-angle-down' => __( 'angle down', 'better-el-addons' ),
					'pe-7s-paint-bucket' => __( 'paint bucket', 'better-el-addons' ),
					'pe-7s-gleam' => __( 'gleam', 'better-el-addons' ),
					'pe-7s-vector' => __( 'vector', 'better-el-addons' ),
					'pe-7s-drop' => __( 'drop', 'better-el-addons' ),
					'pe-7s-glasses' => __( 'glasses', 'better-el-addons' ),
					'pe-7s-music' => __( 'music', 'better-el-addons' ),
					'pe-7s-way' => __( 'way', 'better-el-addons' ),
					'pe-7s-settings' => __( 'settings', 'better-el-addons' ),
				],
				'default' => 'pe-7s-map-marker',
				'condition' => [
					'better_infobox_style6_type' => '2',
					'better_infobox_style' => array('6','5'),
				],
			]
		);

		$this->add_control(
			'info_icon',
			[
				'label' =>esc_html__( 'Icon', 'better-el-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'better_btn_icon',
				'label_block' => true,
				'default' => [
                    'value' => '',
				],
				'condition'	=> [
					'better_infobox_style'	=> array('4')
				]
			]
		);

		$this->add_control(
            'image',
            [
                'label' => __( 'Image', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
				'url' => Utils::get_placeholder_image_src(),
				],
				'condition'	=> [
					'better_infobox_style'	=> array('3')
				]
            ]
        );

		// Main Color
		$this->add_control(
			'better_infobox_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'selectors' => [
				'{{WRAPPER}} .better-info-box.style-1 .item-sm .numb' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_infobox_style' => array('1')
				],
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'title_settings',
			[
				'label' => __( 'Title Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .icon-title, {{WRAPPER}} .better-info-box h6',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .icon-title' => 'color: {{VALUE}};', 
					'{{WRAPPER}} .better-info-box h6' => 'color: {{VALUE}};', 
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'text_settings',
			[
				'label' => __( 'Text Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'text_typography',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .icon-text, {{WRAPPER}} .better-info-box p',
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .icon-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-info-box p' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'text_margin',
			[
				'label' => __( 'Margin)', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .icon-text, {{WRAPPER}} .better-info-box p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_settings',
			[
				'label' => __( 'Icon Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_infobox_style' => array('5','6')
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-info-box .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Margin)', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-info-box .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-info-box .icon' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'letter_settings',
			[
				'label' => __( 'Icon Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_infobox_style' => array('5')
				],
			]
		);

		$this->add_control(
			'letter_color',
			[
				'label' => __( 'Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-info-box .letr-bg' => 'color: {{VALUE}};',
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
	 * @since 1.0.8
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings(); 
		$style = $settings['better_infobox_style'];	
		require( 'styles/style'.$style.'.php' );
 
		}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.8
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


