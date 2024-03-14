<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

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

		// Heading Sub Title
		$this->add_control(
			'better_heading_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'sub titel' ),
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
			]
		);

		// Heading Sub Title Color
		$this->add_control(
			'better_heading_sub_title_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .better-heading span' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .better-heading span',
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
				'{{WRAPPER}} .better-heading h4' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .better-heading h4',
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
				'{{WRAPPER}} .better-heading h4:before' => 'background-color: {{VALUE}}',
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
				'{{WRAPPER}} .better-heading h4:after' => 'background-color: {{VALUE}}',
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
				'{{WRAPPER}} .better-heading p' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .better-heading p',
			]
		);

		// Heading Alignment Options
		$this->add_control(
			'better_heading_options',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
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
					'{{WRAPPER}} .better-heading' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tabs();

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
	    $better_heading_sub_title = esc_html( $settings['better_heading_sub_title'] );
	    $better_heading_title = esc_html( $settings['better_heading_title'] );
	    $better_heading_des = esc_html( $settings['better_heading_des'] );
	    ?>
	    <div class="better-heading style-1">
	        <span><?php echo $better_heading_sub_title; ?></span>
	        <h4><?php echo $better_heading_title; ?></h4>
	        <p><?php echo $better_heading_des; ?></p>
	    </div>
	    <?php
	}

}