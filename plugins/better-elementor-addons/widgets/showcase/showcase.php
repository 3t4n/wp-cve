<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Better_Showcase extends Widget_Base {

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
		return 'better-showcase';
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
		return __( 'Better Showcase', 'elementor-hello-world' );
	}

    //script depend
	public function get_script_depends() { return ['swiper', 'better-showcase','better-el-addons']; }


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
		return 'eicon-blockquote';
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
				'label' => __( 'Showcase Settings', 'better-el-addons' ),
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title', [
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'better-el-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'subtitle', [
				'label' => esc_html__( 'Sub-Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Content' , 'better-el-addons' ),
				'show_label' => true,
                'label_block' => true,
			]
		);

        $repeater->add_control(
			'link', [
				'label' => esc_html__( 'Link', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'Add your link here..' , 'better-el-addons' ),
				'show_label' => false,
			]
		);

        $repeater->add_control(
			'image', [
				'label' => esc_html__( 'Image', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => esc_html__( 'List Content' , 'better-el-addons' ),
				'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
		);

		$this->add_control(
			'showcase_list',
			[
				'label' => esc_html__( 'Showcase List', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => esc_html__( 'Title', 'better-el-addons' ),
						'subtitle' => esc_html__( 'Sub Title', 'better-el-addons' ),
					],
					[
						'title' => esc_html__( 'Title', 'better-el-addons' ),
						'subtitle' => esc_html__( 'Sub Title', 'better-el-addons' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

        $this->add_control(
			'show_dots',
			[
				'label' => esc_html__( 'Show Dots', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'better-el-addons' ),
				'label_off' => esc_html__( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_control(
			'show_nav_btn',
			[
				'label' => esc_html__( 'Show Nav Buttons', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'better-el-addons' ),
				'label_off' => esc_html__( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'nav_prev',
			[
				'label' => __( 'Previous','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Prev Slide', 'better-el-addons' ),
				'condition' => [
                    'show_nav_btn' => 'yes'
				],
			]
		);

		$this->add_control(
			'nav_next',
			[
				'label' => __( 'Next','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Next Slide', 'better-el-addons' ),
				'condition' => [
                    'show_nav_btn' => 'yes'
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'content_style_section',
			[
				'label' => __( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_title_typography',
				'label' => esc_html__( 'title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-showcase.style-0 .parallax-slider .caption h1 .stroke',
			]
		);

        $this->add_control(
			'better_title_color',
			[
				'label' => esc_html__( 'Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				    '{{WRAPPER}} .better-showcase.style-0 .parallax-slider .caption h1 .stroke' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_subtitle_typography',
				'label' => esc_html__( 'Sub-title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-showcase.style-0 .parallax-slider .caption h1 span',
			]
		);

        $this->add_control(
			'better_subtitle_color',
			[
				'label' => esc_html__( 'Sub-Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				    '{{WRAPPER}} .better-showcase.style-0 .parallax-slider .caption h1  span' => 'color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'nav_style_section',
			[
				'label' => __( 'Navigation Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_arrow_typography',
				'label' => esc_html__( 'arrow Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-prev, {{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-next',
			]
		);

        $this->add_control(
			'better_arrow_color',
			[
				'label' => esc_html__( 'Arrow Text Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				    '{{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-prev, {{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-next' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'better_arrow_icon_color',
			[
				'label' => esc_html__( 'Arrow Icon Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				    '{{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-prev i, {{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-next i' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'better_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-prev i, {{WRAPPER}} .better-showcase.style-0 .txt-botm .swiper-nav-ctrl.swiper-button-next i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'button_style_section',
			[
				'label' => __( 'Button Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_button_typography',
				'label' => esc_html__( 'Button Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-showcase.style-0 .parallax-slider .caption .discover span',
			]
		);

        $this->add_control(
			'better_button_color',
			[
				'label' => esc_html__( 'Button Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				    '{{WRAPPER}} .better-showcase.style-0 .parallax-slider .caption .discover span' => 'color: {{VALUE}}',
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
		include( 'style.php' );
	
		
	 
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


