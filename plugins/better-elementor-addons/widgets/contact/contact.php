<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Contact extends Widget_Base {

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
		return 'better-contact-form';
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
		return esc_html__( 'BETTER Contact Form', 'elementor-hello-world' );
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
		return 'eicon-posts-ticker';
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
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'nice-select' ];
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
			'section_shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'genesis-core' ),
			]
		);

		$this->add_control(
			'contact_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
					'3' => __( 'Style 3', 'better-el-addons' ),
					'4' => __( 'Style 4', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Insert your shortcode here', 'genesis-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => 'Place cintact form shortcode here',
				'default' => '[contact-form-7 id="51" title="genesis contact form"]',
			]
		);

		$this->add_control( 
        	'style3_mode2',
            [
                'label' => esc_html__( '4 Column', 'better-el-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'return_value' => 'yes',
				'condition' => [
					'contact_style' => array( '3')
				],
            ]
        );

		$this->add_control(
			'location_link',
			[
				'label' => esc_html__( 'Map location link', 'genesis-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_url( 'http://your-link.com' ),
				'default' => [
					'url' => esc_url('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22864.11283411948!2d-73.96468908098944!3d40.630720240038435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew+York%2C+NY%2C+USA!5e0!3m2!1sen!2sbg!4v1540447494452'),
				],
				'condition' => [
					'contact_style' => array('4')
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_title_section',
			[
				'label' => __( 'Contact For Title', 'better-el-addons' ),
				'condition' => [
					'contact_style' => array('4')
				],
			]
		);

		$this->add_control(
			'title_style5_1',
			[
				'label' => __( 'Title 1','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'GET IN TOUCH', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'title_style5_2',
			[
				'label' => __( 'Title 2','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Contact Us', 'better-el-addons' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'opening_time',
			[
				'label' => __( 'Opening Time', 'better-el-addons' ),
				'condition' => [
					'contact_style' => array('2','4')
				],
			]
		);

		$this->add_control(
			'title_1',
			[
				'label' => __( 'Title 1','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Opening Times', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'title_2',
			[
				'label' => __( 'Title 2','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Check Availability', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'opening_time_list',
			[
				'label' => __( 'Opening Time List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'days' => 'Sunday to Tuesday ',
						'time' => '10:00 - 22:00',
					],
					[
						'days' => 'Friday to Saturday ',
						'time' => '12:00 - 19:00',
					],

				],
				'fields' => [
					[
						'name' => 'days',
						'label' => __( 'Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => __( 'Sunday to Tuesday ', 'better-el-addons' ),
					],
					[
						'name' => 'time',
						'label' => __( 'Sub Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => __( '10:00 - 22:00', 'better-el-addons' ),
					],

				],
				'title_field' => '{{ days }}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_info_section',
			[
				'label' => __( 'Contact Info', 'better-el-addons' ),
				'condition' => [
					'contact_style' => array('2')
				],
			]
		);

		$this->add_control(
			'contact_info_title',
			[
				'label' => __( 'Title','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Call Us Now', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'contact_info',
			[
				'label' => __( 'Info','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( '+1 800 603 6035', 'better-el-addons' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_button_section',
			[
				'label' => __( 'Contact Button', 'better-el-addons' ),
				'condition' => [
					'contact_style' => array('4')
				],
			]
		);

		$this->add_control(
			'btn_contact_title',
			[
				'label' => __( 'Button Title','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Make a Reservation', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'btn_contact_link',
			[
				'label' => __( 'Button Title','better-el-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave Link here',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_location_section',
			[
				'label' => __( 'Contact Location', 'better-el-addons' ),
				'condition' => [
					'contact_style' => array('4')
				],
			]
		);

		$this->add_control(
			'contact_location_title',
			[
				'label' => __( 'Title','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Our Location', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'contact_location_info',
			[
				'label' => __( 'Title','better-el-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'label_block' => true,
				'default' => __( '56 12th Ave, New York, NY 10011', 'better-el-addons' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_contactus_section',
			[
				'label' => __( 'Contact Us', 'better-el-addons' ),
				'condition' => [
					'contact_style' => array('4')
				],
			]
		);

		$this->add_control(
			'contact_contactus_title',
			[
				'label' => __( 'Title','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Contact Us', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'contact_contactus_phone',
			[
				'label' => __( 'Phone','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( '800-603-6035 , 914-309-7030', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'contact_contactus_mail',
			[
				'label' => __( 'Mail','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'mail@companyname.com', 'better-el-addons' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'contact_style' => array('1')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_heading_sub_title_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-contact-shortcode .form input, {{WRAPPER}} .better-contact-shortcode .form textarea, {{WRAPPER}} .better-contact-shortcode .form span',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_settings',
			[
				'label' => __( 'Form Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'contact_style' => array('2','3','4')
				],
			]
		);
		
		$this->add_control(
			'form_placeholder',
			[
				'label' => __( 'Placeholder Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} :-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} :-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .nice-select' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'form_text',
			[
				'label' => __( 'Text Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  input:not(.wpcf7-submit) ' => 'color: {{VALUE}};',
					'{{WRAPPER}} textarea' => 'color: {{VALUE}};',
					'{{WRAPPER}} .nice-select' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'form_bg',
			[
				'label' => __( 'Background Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  input' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} textarea' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'form_border_color',
			[
				'label' => __( 'Border Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  input' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} textarea' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .nice-select' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'form_border_color_active',
			[
				'label' => __( 'Border Color on Focus','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'btn_settings',
			[
				'label' => __( 'Button Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'contact_style' => array('2','3','4')
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'btn_typography',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .wpcf7-submit',
			]
		);
		
		$this->add_responsive_control(
			'btn_margin',
			[
				'label' => __( 'Margin', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => __( 'Padding', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' => __( 'Border Radius', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'btn_color_section',
			[
				'label' => __( 'Button Color Scheme Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'contact_style' => array('2','3','4')
				],
			]
		);
		
		$this->add_control(
			'btn_color',
			[
				'label' => __( 'Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_color_hover',
			[
				'label' => __( 'Color on Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_bg',
			[
				'label' => __( 'Background Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpcf7-submit::before' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_bg_hover',
			[
				'label' => __( 'Background Color on Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpcf7-submit::after' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_border',
			[
				'label' => __( 'Border', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_border_hover',
			[
				'label' => __( 'Border on Hover', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit:hover' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'btn_border_color',
			[
				'label' => __( 'Border Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_border_color_hover',
			[
				'label' => __( 'Border Color on  Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpcf7-submit:hover' => 'border-color: {{VALUE}};',
				],
			]

		);
		
		
		$this->end_controls_section();

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
        $shortcode = $this->get_settings( 'shortcode' );
        $shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
        
		$style = $settings['contact_style'];
       
		require( 'styles/style'.$style.'.php' );

	}
}