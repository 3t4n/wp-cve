<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Controls_Manager;


/**
 * Elementor testimonial widget.
 *
 * Elementor widget that displays customer testimonials that show social proof.
 *
 * @since 1.0.0
 */
class mgp_TestimonialCarousel extends \Elementor\Widget_Base
{
	use mpdProHelpLink;

	/**
	 * Get widget name.
	 *
	 * Retrieve testimonial widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'mgpdtestti_carousel';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve testimonial widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('MPD Testimonial Carousel', 'elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve testimonial widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-testimonial-carousel';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Blank widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories()
	{
		return ['mpd-productwoo'];
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
	public function get_keywords()
	{
		return ['testimonial', 'blockquote', 'carousel', 'slider', 'mpd'];
	}

	/**
	 * Retrieve the list of scripts the image comparison widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends()
	{
		return [
			'mpd-swiper',
			'mgproducts-tcarousel'
		];
	}

	/**
	 * Retrieve the list of styles the image comparison widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends()
	{
		return [
			'mpd-swiper',
			'mgproducts-style',
		];
	}


	/**
	 * Register testimonial widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls()
	{
		$this->start_controls_section(
			'section_testimonial',
			[
				'label' => __('Testimonial items', 'elementor'),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'testimonial_content',
			[
				'label' => __('Content', 'elementor'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'rows' => '10',
				'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
			]
		);
		$repeater->add_control(
			'testimonial_name',
			[
				'label' => __('Name', 'elementor'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'John Doe',
			]
		);
		$repeater->add_control(
			'testimonial_job',
			[
				'label' => __('Title', 'elementor'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Designer',
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __('Link', 'elementor'),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __('https://your-link.com', 'elementor'),
			]
		);
		$repeater->add_control(
			'testimonial_image',
			[
				'type' => Controls_Manager::MEDIA,
				'label' => __('Image', 'magical-products-display'),
				'dynamic' => [
					'active' => true,
				]
			]
		);
		$this->add_control(
			'testicar_items',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '<# print(testimonial_name || "Testimonial Item"); #>',
				'default' => [
					[
						'testimonial_name' => __('John Doe', 'magical-products-display'),
						'testimonial_content' => __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio', 'magical-products-display'),
						'testimonial_job' => __('Designer', 'magical-products-display'),
						'link' => '',
					],
					[
						'testimonial_name' => __('John Doe', 'magical-products-display'),
						'testimonial_content' => __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio', 'magical-products-display'),
						'testimonial_job' => __('Designer', 'magical-products-display'),
						'link' => '',
					],


				]
			]
		);
		$this->add_control(
			'testi_image_size',
			[
				'label' => esc_html__('Image Size', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'medium',
				'separator' => 'before',
				'options' => [
					'thumbnail'  => esc_html__('Thumbnail', 'magical-products-display'),
					'medium'   => esc_html__('Medium', 'magical-products-display'),
					'medium_large'   => esc_html__('Medium_large', 'magical-products-display'),
					'large'   => esc_html__('Large', 'magical-products-display'),
					'full'   => esc_html__('Full', 'magical-products-display'),
				],
			]
		);

		$this->add_control(
			'testimonial_image_position',
			[
				'label' => __('Image Position', 'elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'aside',
				'options' => [
					'aside' => __('Aside', 'elementor'),
					'top' => __('Top', 'elementor'),
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'testimonial_alignment',
			[
				'label' => __('Alignment', 'elementor'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left'    => [
						'title' => __('Left', 'elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'elementor'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __('View', 'elementor'),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mgptcar_settings_section',
			[
				'label' => __('Carousel Settings', 'magical-products-display'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'mgptcar_products_number',
			[
				'label' => __('Carousel Items', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 3,
				'description' => __('Enter How many items show at a time in the carousel', 'magical-products-display'),
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'mgptcar_slide_effect',
			[
				'label' => __('Slide Effect', 'magical-products-display'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fade' => __('fade', 'magical-products-display'),
					'slide' => __('Slide', 'magical-products-display'),
				],
				'default' => 'slide',
				'frontend_available' => true,
				'style_transfer' => true,
				'condition' => [
					'mgptcar_products_number' => 1,
				],
			]
		);

		$this->add_control(
			'mgptcar_products_margin',
			[
				'label' => __('Between Margin', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'size' => 30,
				],
				'condition' => [
					'mgptcar_products_number!' => 1,
				],
			]
		);
		/*
        $this->add_control(
            'mgptcar_slide_direction',
            [
                'label' => __( 'Slide Direction', 'magical-products-display' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __( 'Horizontal', 'magical-products-display' ),
                    'vertical' => __( 'Vertical', 'magical-products-display' ),
                ],
                'default' => 'horizontal',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );
*/

		$this->add_control(
			'mgptcar_autoplay',
			[
				'label' => __('Autoplay?', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'magical-products-display'),
				'label_off' => __('No', 'magical-products-display'),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'mgptcar_autoplay_delay',
			[
				'label' => __('Autoplay Delay', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'step' => 1,
				'max' => 50000,
				'default' => 5000,
				'description' => __('Autoplay Delay in milliseconds', 'magical-products-display'),
				'frontend_available' => true,
				'condition' => [
					'mgptcar_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'mgptcar_autoplay_speed',
			[
				'label' => __('Autoplay Speed', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'step' => 100,
				'max' => 10000,
				'default' => 1000,
				'description' => __('Autoplay speed in milliseconds', 'magical-products-display'),
				'frontend_available' => 'true',
			]
		);

		$this->add_control(
			'mgptcar_loop',
			[
				'label' => __('Infinite Loop?', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'magical-products-display'),
				'label_off' => __('No', 'magical-products-display'),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'mgptcar_grab_cursor',
			[
				'label' => __('Grab Cursor?', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'magical-products-display'),
				'label_off' => __('No', 'magical-products-display'),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mgptcar_navdots_section',
			[
				'label' => __('Nav & Dots', 'magical-products-display'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'mgptcar_dots',
			[
				'label' => __('Slider Dots?', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'magical-products-display'),
				'label_off' => __('No', 'magical-products-display'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);
		$this->add_control(
			'mgptcar_navigation',
			[
				'label' => __('Slider Navigation?', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'magical-products-display'),
				'label_off' => __('No', 'magical-products-display'),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		$this->add_control(
			'mgptcar_nav_prev_icon',
			[
				'label' => __('Choose Prev Icon', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-left',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'arrow-alt-circle-left',
						'arrow-circle-left',
						'arrow-left',
						'long-arrow-alt-left',
						'angle-left',
						'chevron-circle-left',
						'fa-chevron-left',
						'angle-double-left',
					],
					'fa-regular' => [
						'hand-point-left',
						'arrow-alt-circle-left',
						'caret-square-left',
					],
				],
				'condition' => [
					'mgptcar_navigation' => 'yes',
				],


			]
		);
		$this->add_control(
			'mgptcar_nav_next_icon',
			[
				'label' => __('Choose Next Icon', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'arrow-alt-circle-right',
						'arrow-circle-right',
						'arrow-right',
						'long-arrow-alt-right',
						'angle-right',
						'chevron-circle-right',
						'fa-chevron-right',
						'angle-double-right',
					],
					'fa-regular' => [
						'hand-point-right',
						'arrow-alt-circle-right',
						'caret-square-right',
					],
				],
				'condition' => [
					'mgptcar_navigation' => 'yes',
				],

			]
		);


		$this->end_controls_section();
		$this->link_pro_added();


		/*
*
*Style section
*
*/
		// Content.
		$this->start_controls_section(
			'section_style_testimonial_content',
			[
				'label' => __('Content', 'elementor'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_content_color',
			[
				'label' => __('Text Color', 'elementor'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .elementor-testimonial-content',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_shadow',
				'selector' => '{{WRAPPER}} .elementor-testimonial-content',
			]
		);

		$this->end_controls_section();

		// Image.
		$this->start_controls_section(
			'section_style_testimonial_image',
			[
				'label' => __('Image', 'elementor'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'testimonial_image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => __('Image Size', 'elementor'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __('Border Radius', 'elementor'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Name.
		$this->start_controls_section(
			'section_style_testimonial_name',
			[
				'label' => __('Name', 'elementor'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_text_color',
			[
				'label' => __('Text Color', 'elementor'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .elementor-testimonial-name',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'name_shadow',
				'selector' => '{{WRAPPER}} .elementor-testimonial-name',
			]
		);

		$this->end_controls_section();

		// Job.
		$this->start_controls_section(
			'section_style_testimonial_job',
			[
				'label' => __('Title', 'elementor'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'job_text_color',
			[
				'label' => __('Text Color', 'elementor'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'selector' => '{{WRAPPER}} .elementor-testimonial-job',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'job_shadow',
				'selector' => '{{WRAPPER}} .elementor-testimonial-job',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'mgtest_section_style_dots',
			[
				'label' => __('Navigation - Dots', 'magical-products-display'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'mgptcar_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'mgtest_dots_position_y',
			[
				'label' => __('Vertical Position', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -30,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items.swiper-container-horizontal>.swiper-pagination-bullets, {{WRAPPER}} .mgptcar-car-items .swiper-pagination-custom, {{WRAPPER}} .mgptcar-car-items .swiper-pagination-fraction' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mgptcar-car-items.swiper-container-vertical>.swiper-pagination-bullets' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mgtest_dots_spacing',
			[
				'label' => __('Spacing', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items.swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .mgptcar-car-items.swiper-container-vertical>.swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
			'mgtest_dots_nav_align',
			[
				'label' => __('Alignment', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __('Left', 'magical-products-display'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __('Center', 'magical-products-display'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __('Right', 'magical-products-display'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items.swiper-container-horizontal>.swiper-pagination-bullets, .swiper-pagination-custom, {{WRAPPER}} .mgptcar-car-items .swiper-pagination-fraction' => 'text-align: {{VALUE}}'
				]
			]
		);
		$this->add_control(
			'mgtest_dots_width',
			[
				'label' => __('Dots Width', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'mgtest_dots_height',
			[
				'label' => __('Dots Height', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'mgtest_dots_border_radius',
			[
				'label' => __('Border Radius', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->start_controls_tabs('mgtest_tabs_dots');
		$this->start_controls_tab(
			'mgtest_tab_dots_normal',
			[
				'label' => __('Normal', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgtest_dots_nav_color',
			[
				'label' => __('Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mgtest_tab_dots_hover',
			[
				'label' => __('Hover', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgtest_dots_nav_hover_color',
			[
				'label' => __('Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mgtest_tab_dots_active',
			[
				'label' => __('Active', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgtest_dots_nav_active_color',
			[
				'label' => __('Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items span.swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'mgtest_section_style_arrow',
			[
				'label' => __('Navigation - Arrow', 'magical-products-display'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'mgptcar_navigation' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'mgtest_arrow_size',
			[
				'label' => __('Icon Size', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],

				],

				'selectors' => [
					'
{{WRAPPER}} .mgpd-testimonial-carousel .swiper-button-prev i,
{{WRAPPER}} .mgptcar-car-items.swiper-container-rtl .swiper-button-next i,
{{WRAPPER}} .mgptcar-car-items.swiper-container-rtl .swiper-button-next i,
{{WRAPPER}} .mgpd-testimonial-carousel .swiper-button-next i,
{{WRAPPER}} .mgptcar-car-items.swiper-container-rtl .swiper-button-prev i,
{{WRAPPER}} .mgptcar-car-items.swiper-container-rtl .swiper-button-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mgpd-testimonial-carousel .swiper-button-next svg,{{WRAPPER}} .mgpd-testimonial-carousel .swiper-button-prev svg,
{{WRAPPER}} .mgptcar-car-items.swiper-container-rtl .swiper-button-prev svg,
{{WRAPPER}} .mgptcar-car-items.swiper-container-rtl .swiper-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mgtest_arrow_position_toggle',
			[
				'label' => __('Position', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __('None', 'magical-products-display'),
				'label_on' => __('Custom', 'magical-products-display'),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'mgtest_arrow_positiony',
			[
				'label' => __('Vertical', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				// 'condition' => [
				//     'arrow_position_toggle' => 'yes'
				// ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],

				],

				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next,{{WRAPPER}} .mgptcar-car-items .swiper-button-prev' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mgtest_arrow_position_x',
			[
				'label' => __('Horizontal', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				// 'condition' => [
				//     'arrow_position_toggle' => 'yes'
				// ],
				'range' => [
					'px' => [
						'min' => -10,
						'max' => 250,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-prev, {{WRAPPER}} .mgptcar-car-items .swiper-container-rtl .swiper-button-next' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next,{{WRAPPER}} .mgptcar-car-items .swiper-container-rtl .swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();
		$this->add_responsive_control(
			'mgtest_arrow_border',
			[
				'label' => __('Padding', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};width:inherit;height:inherit',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'mgtest_arrow_border',
				'selector' => '{{WRAPPER}} .mgptcar-car-items .swiper-button-next, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev',
			]
		);

		$this->add_responsive_control(
			'mgtest_arrow_border_radius',
			[
				'label' => __('Border Radius', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->start_controls_tabs('mgtest_tabs_arrow');

		$this->start_controls_tab(
			'mgtest_tab_arrow_normal',
			[
				'label' => __('Normal', 'magical-products-display'),
			]
		);

		$this->add_responsive_control(
			'mgtest_arrow_color',
			[
				'label' => __('Text Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next i, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next svg, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev svg' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'mgtest_arrow_bg_color',
			[
				'label' => __('Background Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mgtest_tab_arrow_hover',
			[
				'label' => __('Hover', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgtest_arrow_hover_color',
			[
				'label' => __('Text Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .slick-prev:hover, {{WRAPPER}} .mgptcar-car-items .slick-next:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mgtest_arrow_hover_bg_color',
			[
				'label' => __('Background Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next:hover, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mgtest_arrow_hover_border_color',
			[
				'label' => __('Border Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'mgtest_arrow_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .mgptcar-car-items .swiper-button-next:hover, {{WRAPPER}} .mgptcar-car-items .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render testimonial widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$testicar_items = $this->get_settings('testicar_items');
		$mgptcar_slide_effect = $this->get_settings('mgptcar_slide_effect');
		$mgptcar_products_margin = $this->get_settings('mgptcar_products_margin');
		$mgptcar_slide_direction = 'horizontal';

		$mgptcar_slide_effect = $mgptcar_slide_effect ? $mgptcar_slide_effect : 'slide';
		$mgptcar_products_margin = $mgptcar_products_margin['size'] ? $mgptcar_products_margin['size'] : '0';

		$this->add_render_attribute('wrapper', 'class', 'elementor-testimonial-wrapper');

		if ($settings['testimonial_alignment']) {
			$this->add_render_attribute('wrapper', 'class', 'elementor-testimonial-text-align-' . $settings['testimonial_alignment']);
		}

		$this->add_render_attribute('meta', 'class', 'elementor-testimonial-meta');



		if ($settings['testimonial_image_position']) {
			$this->add_render_attribute('meta', 'class', 'elementor-testimonial-image-position-' . $settings['testimonial_image_position']);
		}




?>
		<div class="mgptcar-car-items swiper-container mgpd-testimonial-carousel" data-loop="<?php echo esc_attr($settings['mgptcar_loop']); ?>" data-number="<?php echo esc_attr($settings['mgptcar_products_number']); ?>" data-margin="<?php echo esc_attr($mgptcar_products_margin); ?>" data-direction="<?php echo esc_attr($mgptcar_slide_direction); ?>" data-effect="<?php echo esc_attr($mgptcar_slide_effect); ?>" data-autoplay="<?php echo esc_attr($settings['mgptcar_autoplay']); ?>" data-auto-delay="<?php echo esc_attr($settings['mgptcar_autoplay_delay']); ?>" data-speed="<?php echo esc_attr($settings['mgptcar_autoplay_speed']); ?>" data-grab-cursor="<?php echo esc_attr($settings['mgptcar_grab_cursor']); ?>" data-nav="<?php echo esc_attr($settings['mgptcar_navigation']); ?>" data-dots="<?php echo esc_attr($settings['mgptcar_dots']); ?>">
			<div class="swiper-wrapper">
				<?php
				foreach ($testicar_items as $index => $slide) :
					//$key1 = $this->get_repeater_setting_key('mgs_btn_link','mgs_slides',$index);
					$testimonial_image = wp_get_attachment_image_url($slide['testimonial_image']['id'], $settings['testi_image_size']);
					if ($slide['testimonial_image']['url']) {
						$this->add_render_attribute('meta', 'class', 'elementor-has-image');
					}
					if (!empty($slide['link']['url'])) {
						$this->add_link_attributes('link', $slide['link']);
					}


					$has_content = !!$slide['testimonial_content'];
					$has_image = !!$testimonial_image;
					$has_name = !!$slide['testimonial_name'];
					$has_job = !!$slide['testimonial_job'];
				?>
					<div class="swiper-slide no-load">

						<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
							<?php
							if ($has_content) :
								$key1 = $this->get_repeater_setting_key('testimonial_content', 'testicar_items', $index);
								$this->add_render_attribute($key1, 'class', 'elementor-testimonial-content');
								$this->add_inline_editing_attributes($key1);
								/*
				$this->add_render_attribute( 'testimonial_content', 'class', 'elementor-testimonial-content' );
				$this->add_inline_editing_attributes( 'testimonial_content' );
*/
							?>
								<div <?php echo $this->get_render_attribute_string($key1); ?>><?php echo $slide['testimonial_content']; ?></div>
							<?php endif; ?>

							<?php if ($has_image || $has_name || $has_job) : ?>
								<div <?php echo $this->get_render_attribute_string('meta'); ?>>
									<div class="elementor-testimonial-meta-inner">
										<?php if ($has_image) : ?>
											<div class="elementor-testimonial-image">
												<?php
												$image_html = '<img class="xtest-img" src="' . esc_url($testimonial_image) . '" alt="' . esc_attr($slide['testimonial_name']) . '">';
												if (!empty($slide['link']['url'])) :
													$image_html = '<a ' . $this->get_render_attribute_string('link') . '>' . $image_html . '</a>';
												endif;
												echo $image_html;
												?>
											</div>
										<?php endif; ?>

										<?php if ($has_name || $has_job) : ?>
											<div class="elementor-testimonial-details">
												<?php
												if ($has_name) :
													$key2 = $this->get_repeater_setting_key('testimonial_name', 'testicar_items', $index);
													$this->add_render_attribute($key2, 'class', 'elementor-testimonial-name');
													$this->add_inline_editing_attributes($key2, 'none');
													/*
							$this->add_render_attribute( 'testimonial_name', 'class', 'elementor-testimonial-name' );
							$this->add_inline_editing_attributes( 'testimonial_name', 'none' );
				*/
													$testimonial_name_html = $slide['testimonial_name'];

													if (!empty($slide['link']['url'])) :
												?>
														<a <?php echo $this->get_render_attribute_string($key2) . ' ' . $this->get_render_attribute_string('link'); ?>><?php echo $testimonial_name_html; ?></a>
													<?php
													else :
													?>
														<div <?php echo $this->get_render_attribute_string($key2); ?>><?php echo $testimonial_name_html; ?></div>
												<?php
													endif;
												endif; ?>
												<?php
												if ($has_job) :
													$key3 = $this->get_repeater_setting_key('testimonial_job', 'testicar_items', $index);
													$this->add_render_attribute($key3, 'class', 'elementor-testimonial-job');
													$this->add_inline_editing_attributes($key3, 'none');

													/*
							$this->add_render_attribute( 'testimonial_job', 'class', 'elementor-testimonial-job' );
							$this->add_inline_editing_attributes( 'testimonial_job', 'none' );
*/
													$testimonial_job_html = $slide['testimonial_job'];

													if (!empty($slide['link']['url'])) :
												?>
														<a <?php echo $this->get_render_attribute_string($key3) . ' ' . $this->get_render_attribute_string('link'); ?>><?php echo $testimonial_job_html; ?></a>
													<?php
													else :
													?>
														<div <?php echo $this->get_render_attribute_string($key3); ?>><?php echo $testimonial_job_html; ?></div>
												<?php
													endif;
												endif; ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>


			</div>
			<?php if ($settings['mgptcar_dots']) : ?>
				<div class="swiper-pagination mgptcar-btn"></div>
			<?php endif; ?>

			<?php if ($settings['mgptcar_navigation']) : ?>
				<div class="swiper-button-prev mgptcar-nav">
					<?php \Elementor\Icons_Manager::render_icon($settings['mgptcar_nav_prev_icon']); ?>
				</div>
				<div class="swiper-button-next mgptcar-nav">
					<?php \Elementor\Icons_Manager::render_icon($settings['mgptcar_nav_next_icon']); ?>
				</div>
			<?php endif; ?>
		</div>
<?php
	}

	/**
	 * Render testimonial widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template()
	{
	}
}
