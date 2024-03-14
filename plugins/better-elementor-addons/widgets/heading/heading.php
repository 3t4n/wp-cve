<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Heading extends Widget_Base {

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
		return 'heading-main';
	}

	//script depend
	public function get_script_depends() { return [ 'grouploop','heading-loop', 'better-el-addons']; }


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
		return esc_html__( 'BETTER Heading', 'elementor-hello-world' );
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
		return 'fa fa-text-height';
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

		// start of the Content tab section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'better_heading_style',
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
					'9' => __( 'Style 9', 'better-el-addons' ),
					'10' => __( 'Style 10', 'better-el-addons' ),
					'11' => __( 'Style 11', 'better-el-addons' ),
					'12' => __( 'Style 12', 'better-el-addons' ),
					'13' => __( 'Style 13', 'better-el-addons' ),
					'14' => __( 'Style 14', 'better-el-addons' ),
					'15' => __( 'Style 15', 'better-el-addons' ),
					'16' => __( 'Style 16', 'better-el-addons' ),
					'17' => __( 'Style 17', 'better-el-addons' ),
					'18' => __( 'Style 18', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'header_style11_text',
			[
				'label' => __( 'Text', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Type your Text here', 'better-el-addons' ),
				'placeholder' => __( 'Type your Text here', 'better-el-addons' ),
				'condition' => [
					'better_heading_style' => array('11')
				],
			]
		);

		$this->add_control(
			'header_style6',
			[
				'label' => __( 'Mode', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
				],
				'default' => '1',
				'condition' => [
					'better_heading_style' => array('6')
				],
			]
		);

		// Heading Sub Title
		$this->add_control(
			'better_heading_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'sub titel' ),
				'condition' => [
					'better_heading_style' => array('1','2','4','16')
				],
			]
		);

		$this->add_control(
			'better_heading3_number',
			[
				'label' => esc_html__( 'Number', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '01' ),
				'condition' => [
					'better_heading_style' => array('3','16')
				],
			]
		);

		// Heading Title
		$this->add_control(
			'better_heading_title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Main title' ),
				'condition' => [
					'better_heading_style!' => '11'
				],
			]
		);

		$this->add_control(
			'better_heading_title_1',
			[
				'label' => __( 'Title part 1','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => 'Title part1',
				'condition'   => array( 'better_heading_style' => array('5','6','7','9','14','17','18') ),
			]
		);
		$this->add_control(
			'better_heading_title_2',
			[
				'label' => __( 'Title part 2','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => 'Title part2',
				'condition'   => array( 'better_heading_style' => array('6') ),
			]
		);

		// Heading Description
		$this->add_control(
			'better_heading_des',
			[
				'label' => esc_html__( 'Description', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'write your profissional text here and you can styling and customize it form style or advanced tabs or check documentation for more details.' ),
				'condition' => [
					'better_heading_style' => array('1','3','5','9','14','15')
				],
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label' => __( 'Button Text','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'placeholder' => 'Insert your button text here..',
				'condition' => [
					'better_heading_style' => array('5','9')
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Button Link','better-el-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave it blank if you don\'t want to use this button',
				'condition' => [
					'better_heading_style' => array( '5','9')
				],
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
				'condition' => [
					'better_heading_style' => array('8')
				],
            ]
        );

		$this->end_controls_section();
		// end of the Content tab section

		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Heading Sub Title Options
		$this->add_control(
			'better_heading_sub_title_options',
			[
				'label' => esc_html__( 'Sub Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'better_heading_style' => array('1','2','4','6','14','17')
				],
			]
		);

		// Heading Sub Title Color
		$this->add_control(
			'better_heading_sub_title_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-heading .tex-bg, {{WRAPPER}} .better-heading.style-1 span, {{WRAPPER}} .better-heading.style-2 h6, {{WRAPPER}} .better-heading.style-4 p,  {{WRAPPER}} .better-heading.style-14 p' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('1','2','4','6','14','17')
				],
			]
		);

		// Heading Sub Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_heading_sub_title_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-heading .tex-bg, {{WRAPPER}} .better-heading.style-1 span, {{WRAPPER}} .better-heading.style-2 h6, {{WRAPPER}} .better-heading.style-4 p',
				'condition' => [
					'better_heading_style' => array('1','2','4','6','17')
				],
			]
		);

		// Heading Title Options
		$this->add_control(
			'better_heading_title_options',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Heading Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'style12_text_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-heading.style-12 h2',
				'condition' => [
					'better_heading_style' => array('12')
				],
			]
		);

		// Name Background Color 
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'style12_text_color',
				'label' => esc_html__( 'Text Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selector' => '{{WRAPPER}} .better-heading.style-12 h2',
				'condition' => [
					'better_heading_style' => array('12')
				],
			]
        );

		// Heading Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'style11_text_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-heading.style-11 p',
				'condition' => [
					'better_heading_style' => array('11')
				],
			]
		);

		// Name Background Color 
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'style11_link_color',
				'label' => esc_html__( 'Link Color', 'better-el-addons' ),
				'types' => ['gradient'],
				'type' => \Elementor\Controls_Manager::COLOR,
				'selector' => '{{WRAPPER}} .better-heading.style-11 a',
				'condition' => [
					'better_heading_style' => array('11')
				],
			]
        );

		// Heading Title Color
		$this->add_control(
			'better_heading13_title_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-heading.style-13 h2' => '-webkit-text-stroke-color: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('13')
				],
			]
		);

		// Heading Title Color
		$this->add_control(
			'better_heading_title_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .better-heading h3, {{WRAPPER}} .better-heading h5, {{WRAPPER}} .better-heading h4, {{WRAPPER}} .better-heading.style-1 h4, {{WRAPPER}} .better-heading.style-2 h3, {{WRAPPER}} .better-heading.style-3 .htit h4, {{WRAPPER}} .better-heading.style-4 .better-extra-title, {{WRAPPER}} .better-heading.style-10 items, {{WRAPPER}} .better-heading h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('1','2','3','4','5','6','7','8','10','14','15','16','17','18')
				],
			]
		);

		// Heading Title Color
		$this->add_control(
			'better_heading_9_title_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-heading.style-9 h3' => 'color: {{VALUE}}; background: none; -webkit-background-clip: unset; -webkit-text-fill-color: unset;',
				],
				'condition' => [
					'better_heading_style' => array('9')
				],
			]
		);

		// Heading icon Color
		$this->add_control(
			'better_heading_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-heading.style-2 .icon i svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('6')
				],
			]
		);

		// Heading Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_heading_title_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-heading h3, {{WRAPPER}} .better-heading h5, {{WRAPPER}} .better-heading h2, {{WRAPPER}} .better-heading.style-1 h4, {{WRAPPER}} .better-heading.style-2 h3, {{WRAPPER}} .better-heading.style-3 .htit h4, {{WRAPPER}} .better-heading.style-4 .better-extra-title, {{WRAPPER}} .better-heading.style-9 h6, {{WRAPPER}} .better-heading.style-10 items, {{WRAPPER}} .better-heading.style-13 h2, {{WRAPPER}} .better-heading.style-14 .capt h2',
				'condition' => [
					'better_heading_style' => array('1','2','3','4','5','6','7','8','9','10','13','14','15','16','17','18')
				],
			]
		);

		// Heading Sub Title Options
		$this->add_control(
			'better_heading_sub_title_1_options',
			[
				'label' => esc_html__( 'Sub Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'better_heading_style' => array('6','9','16','18')
				],
			]
		);

		// Heading Sub Title Color
		$this->add_control(
			'better_heading_sub_title_1_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-heading.style-16 span, , {{WRAPPER}} .better-heading.style-9 h6, {{WRAPPER}} .better-heading h6, {{WRAPPER}} .better-heading.style-2 h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('6','9','16','18')
				],
			]
		);

		// Heading Sub Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_heading_sub_title_1_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-heading.style-16 span, {{WRAPPER}} .better-heading.style-9 h6, {{WRAPPER}} .better-heading h6, {{WRAPPER}} .better-heading.style-2 h2',
				'condition' => [
					'better_heading_style' => array('6','9','16','18')
				],
			]
		);

		// Heading Border 1 Color
		$this->add_control(
			'better_heading__title_border1_color',
			[
				'label' => esc_html__( 'Border 1 Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#2c3e50',
				'selectors' => [
				'{{WRAPPER}} .better-heading.style-1 h4:before' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .better-heading.style-15:after' => 'border-color: {{VALUE}}',
				'{{WRAPPER}} .better-heading.style-16:after' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('1','15','16')
				],
			]
		);

		// Heading Border 2 Color
		$this->add_control(
			'better_heading__title_border2_color',
			[
				'label' => esc_html__( 'Border 2 Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#db3157',
				'selectors' => [
				'{{WRAPPER}} .better-heading.style-1 h4:after' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('1')
				],
			]
		);

		// Heading Description Options
		$this->add_control(
			'better_heading_des_options',
			[
				'label' => esc_html__( 'Description', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'better_heading_style' => array('1','3','9','15')
				],
			]
		);

		// Heading Description Color
		$this->add_control(
			'better_heading_des_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .better-heading p, {{WRAPPER}} .better-heading.style-3 .text p, {{WRAPPER}} .better-heading.style-9 p' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('1','3','9','15')
				],
			]
		);

		// Heading Description Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_heading_des_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-heading p, {{WRAPPER}} .better-heading.style-3 .text p, {{WRAPPER}} .smp-list li, {{WRAPPER}} .better-heading.style-9 p',
				'condition' => [
					'better_heading_style' => array('1','3','9','15')
				],
			]
		);

		// Heading Number Options
		$this->add_control(
			'better_heading_number_options',
			[
				'label' => esc_html__( 'Number', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'better_heading_style' => array('3','16')
				],
			]
		);

		// Heading Title Color
		$this->add_control(
			'better_heading_number_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-heading h4, {{WRAPPER}} .better-heading.style-3 .htit span, {{WRAPPER}} .smp-list li:after' => 'color: {{VALUE}}',
				'{{WRAPPER}} .smp-list li:after' => 'background: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('3','16')
				],
			]
		);

		// Heading Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_heading_number_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-heading h4, {{WRAPPER}} .better-heading.style-3 .htit span',
				'condition' => [
					'better_heading_style' => array('3','16')
				],
			]
		);

		// Heading Alignment Options
		$this->add_control(
			'better_heading_options',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'better_heading_style' => array('1','2','3','17')
				],
			]
		);

		// Header Alignment
		$this->add_responsive_control(
			'better_heading_alignment',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
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
						'title' => __( 'Right', 'better-el-addons' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .better-heading.style-1, {{WRAPPER}} .better-heading.style-2, {{WRAPPER}} .better-heading.style-3, {{WRAPPER}} .better-heading.style-12' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'better_heading_style' => array('1','2','3','12','17')
				],
			]
		);

		$this->end_controls_section();
		// end of the Style tab section
	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		$better_heading_sub_title = $settings['better_heading_sub_title'];
		$better_heading_title = $settings['better_heading_title'];
		$better_heading_des = $settings['better_heading_des'];
		$style = $settings['better_heading_style'];
       
		require( 'styles/style'.$style.'.php' );

	}
}