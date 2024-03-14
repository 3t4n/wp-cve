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
class Better_Testimonial extends Widget_Base {

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
		return 'testimonials';
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
		return __( 'BETTER Testimonials', 'better-el-addons' );
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

		// start of the Content tab section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Testimonial Description
		$this->add_control(
			'better_testimonial_description',
			[
				'label' => esc_html__( 'Description', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'write your profissional text here and you can styling and customize it form style or advanced tabs or check documentation for more details.write your profissional text here and you can styling and customize it form style or advanced tabs or check documentation for more details.' ),
			]
		);

		// Testimonial Image
		$this->add_control(
			'better_testimonial_author_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		// Testimonial Author Name
		$this->add_control(
			'better_testimonial_author_name',
			[
				'label' => esc_html__( 'Author Name', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'John Doe' ),
			]
		);

		// Testimonial Author Designation
		$this->add_control(
			'better_testimonial_author_designation',
			[
				'label' => esc_html__( 'Author Designation', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Web Developer' ),
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

		// Testimonial Icon Options
		$this->add_control(
			'better_testimonial_icon_options',
			[
				'label' => esc_html__( 'Icon', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Testimonial Icon Color
		$this->add_control(
			'better_testimonial_icon_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .single-testimonial i' => 'color: {{VALUE}}',
				],
			]
		);

		// Testimonial Icon Size
		$this->add_control(
			'better_testimonial_icon_size',
			[
				'label' => __( 'Icon Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single-testimonial i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Testimonial Description Options
		$this->add_control(
			'better_testimonial_description_options',
			[
				'label' => esc_html__( 'Description', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Testimonial Description Color
		$this->add_control(
			'better_testimonial_description_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .single-testimonial p' => 'color: {{VALUE}}',
				],
			]
		);

		// Testimonial Description Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_testimonial_description_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .single-testimonial p',
			]
		);

		// Testimonial Author Title Options
		$this->add_control(
			'better_testimonial_author_options',
			[
				'label' => esc_html__( 'Author', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Testimonial Author Title Color
		$this->add_control(
			'better_testimonial_author_title_color',
			[
				'label' => esc_html__( 'Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .authro-info h4' => 'color: {{VALUE}}',
				],
			]
		);

		// Testimonial Author Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_testimonial_author_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .authro-info h4',
			]
		);		

		// Testimonial Author Designation Color
		$this->add_control(
			'better_testimonial_author_des_color',
			[
				'label' => esc_html__( 'Designation Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .authro-info h4 span' => 'color: {{VALUE}}',
				],
			]
		);

		// Testimonial Author Designation Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_testimonial_author_des_typography',
				'label' => esc_html__( 'Designation Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .authro-info h4 span',
			]
		);

		// Testimonial Alignment Options
		$this->add_control(
			'better_testimonial_options',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Testimonial Alignment
		$this->add_responsive_control(
			'better_testimonial_alignment',
			[
				'label' => __( 'Alignment', 'better-el-addons' ),
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
					'{{WRAPPER}} .single-testimonial' => 'text-align: {{VALUE}}',
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
	    ?>
	    <div class="better single-testimonial">
	        <i class="fas fa-quote-left"></i>
	        <p><?php echo esc_html($settings['better_testimonial_description']); ?></p>
	        <div class="authro-info">
	            <h4>
	                <img src="<?php echo esc_url($settings['better_testimonial_author_image']['url']); ?>" alt="">
	                <?php echo esc_html($settings['better_testimonial_author_name']); ?> 
	                <span><?php echo esc_html($settings['better_testimonial_author_designation']); ?></span>
	            </h4>
	        </div>
	    </div>
	    <?php
	}

}