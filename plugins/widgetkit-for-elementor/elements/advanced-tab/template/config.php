<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
	exit;
}
// Exit if accessed directly
class wkfe_advanced_tab extends Widget_Base
{

	public function get_name()
	{
		return 'wk-advanced-tabs';
	}

	public function get_title()
	{
		return __('Advanced Tab', 'widgetkit-for-elementor');
	}

	public function get_icon()
	{
		return 'eicon-tabs wk-icon';
	}

	public function get_keywords()
	{
		return [
			'tabs',
			'section',
			'advanced',
			'advanced tab',
			'toggle'
		];
	}
	public function select_elementor_page($type)
	{
		$args  = [
			'tax_query'      => [
				[
					'taxonomy' => 'elementor_library_type',
					'field'    => 'slug',
					'terms'    => $type,
				],
			],
			'post_type'      => 'elementor_library',
			'posts_per_page' => -1,
		];
		$query = new \WP_Query($args);

		$posts = $query->posts;
		foreach ($posts as $post) {
			$items[$post->ID] = $post->post_title;
		}

		if (empty($items)) {
			$items = [];
		}

		return $items;
	}

	public function get_categories()
	{
		return ['widgetkit_elementor'];
	}

	/**
	 * A list of style that the widgets is depended in
	 **/
	public function get_style_depends() {
        return [
            'widgetkit_main',
        ];
    }
	/**
	 * A list of scripts that the widgets is depended in
	 **/
	public function get_script_depends() {
		return [ 
			'widgetkit-main',
		 ];
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'content_heading',
			[
				'label' => __('Heading', 'widgetkit-for-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'enable_tab_heading_switcher' => 'yes'
				]
			]
		);

		$this->add_control(
			'heading_title',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('Title', 'widgetkit-for-elementor'),
				'label_block' => true,
				'default' => __('Tab Heading', 'widgetkit-for-elementor'),
				'placeholder' => __('Type Tab Heading', 'widgetkit-for-elementor'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'heading_desc',
			[
				'type' => Controls_Manager::TEXTAREA,
				'label' => __('Description', 'widgetkit-for-elementor'),
				'default' => __('Tab Description', 'widgetkit-for-elementor'),
				'placeholder' => __('Type Tab Description', 'widgetkit-for-elementor'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => __('Content', 'widgetkit-for-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();
		
		$repeater->add_control(
			'icon',
			[
				'label' => __('Icon', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label' => __('Title', 'widgetkit-for-elementor'),
				'default' => __('Tab Title', 'widgetkit-for-elementor'),
				'placeholder' => __('Type Tab Title', 'widgetkit-for-elementor'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'description',
			[
				'type' => Controls_Manager::TEXTAREA,
				'label' => __('Description', 'widgetkit-for-elementor'),
				'default' => __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.', 'widgetkit-for-elementor'),
				'placeholder' => __('Type Tab Description', 'widgetkit-for-elementor'),
			]
		);

		$repeater->add_control(
			'tabs_content_type',
			[
				'label' => __('Content Type', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'content' => __('Content', 'widgetkit-for-elementor'),
					'image' => __('Image', 'widgetkit-for-elementor'),
					'template' => __('Saved Templates', 'widgetkit-for-elementor'),
				],
				'default' => 'content',
			]
		);

		$repeater->add_control(
            'tabs_tab_content',
            [
                'label' => esc_html__('Tab Content', 'widgetkit-for-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'widgetkit-for-elementor'),
                'dynamic' => ['active' => true],
                'condition' => [
					'tabs_content_type' => 'content',
                ],
            ]
        );

		$repeater->add_control(
			'tab_image',
			[
				'label' => esc_html__('Choose Image', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'tabs_content_type' => 'image',
				],
			]
		);

		$saved_sections = ['0' => __('--- Select Section ---', 'widgetkit-for-elementor')];
		$saved_sections = $saved_sections + $this->select_elementor_page('section');

		$repeater->add_control(
			'primary_templates',
			[
				'label'     => __('Sections', 'widgetkit-for-elementor'),
				'type'      => Controls_Manager::SELECT,
				'options'   => $saved_sections,
				'default'   => '0',
				'condition' => [
					'tabs_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{title}}',
				'default' => [
					[
						'title' => 'Tab 1',
						'description' => '',
					],
					[
						'title' => 'Tab 2',
						'description' => '',
					]
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_options',
			[
				'label' => __('Options', 'widgetkit-for-elementor'),
			]
		);

		$this->add_control(
			'_enable_heading_title',
			[
				'label' => __('Heading', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'enable_tab_heading_switcher',
			[
				'label' => __('Show', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'widgetkit-for-elementor'),
				'label_off' => __('No', 'widgetkit-for-elementor'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'_enable_accordian_title',
			[
				'label' => __('Accordian', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'enable_tab_accordian_switcher',
			[
				'label' => __('Enable', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'widgetkit-for-elementor'),
				'label_off' => __('No', 'widgetkit-for-elementor'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'_heading_tab_title',
			[
				'label' => __('Tab Title', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'nav_position',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __('Position', 'widgetkit-for-elementor'),
				'description' => __('Only applicable for desktop', 'widgetkit-for-elementor'),
				'default' => 'top',
				'toggle' => false,
				'options' => [
					'left' => [
						'title' =>  __('Left', 'widgetkit-for-elementor'),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' =>  __('Top', 'widgetkit-for-elementor'),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' =>  __('Right', 'widgetkit-for-elementor'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'nav_align_y',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __('Content Alignment', 'widgetkit-for-elementor'),
				'default' => 'y-top',
				'toggle' => false,
				'options' => [
					'y-top' => [
						'title' =>  __('Top', 'widgetkit-for-elementor'),
						'icon' => 'eicon-v-align-top',
					],
					'y-center' => [
						'title' =>  __('Center', 'widgetkit-for-elementor'),
						'icon' => 'eicon-v-align-middle',
					],
					'y-bottom' => [
						'title' =>  __('Right', 'widgetkit-for-elementor'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'y-top' => 'flex-start',
					'y-center' => 'center',
					'y-bottom' => 'flex-end',
				],
				'condition' => [
					'nav_position' => ['left', 'right'],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'_heading_tab_icon',
			[
				'label' => __('Tab Icon', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_icon_position',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => __('Position', 'widgetkit-for-elementor'),
				'default' => 'left',
				'toggle' => false,
				'options' => [
					'left' => [
						'title' =>  __('Left', 'widgetkit-for-elementor'),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' =>  __('Top', 'widgetkit-for-elementor'),
						'icon' => 'eicon-v-align-top',
					]
				],
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();

		// Start Style Tab
		$this->start_controls_section(
			'_section_nav_heading',
			[
				'label' => __('Heading', 'widgetkit-for-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_tab_heading_switcher' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'tab_heading_align',
			[
				'label' => esc_html__('Alignment', 'widgetkit-for-elementor'),
				'type'  => Controls_Manager::CHOOSE,
				'default'  => 'center',
				'options'  => [
					'left'    => [
						'title' => esc_html__('Left', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-heading' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'nav_heading_title_color',
			[
				'label' => __('Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-heading h3' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nav_heading_typography',
				'selector' => '{{WRAPPER}} .wk-adv-tab-heading h3',
			]
		);

		$this->add_responsive_control(
			'nav_heading_title_spacing',
			[
				'label' => __('Spacing', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-heading h3' => 'margin-bottom: {{SIZE}}px;'
				],
			]
		);

		$this->add_control(
			'nav_heading_desc_color',
			[
				'label' => __('Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-heading p' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nav_heading_desc_typography',
				'selector' => '{{WRAPPER}} .wk-adv-tab-heading p',
			]
		);

		$this->add_responsive_control(
			'nav_heading_desc_spacing',
			[
				'label' => __('Spacing', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-heading p' => 'margin-bottom: {{SIZE}}px;'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_tab_nav',
			[
				'label' => __('Title & Description', 'widgetkit-for-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_title_desc_global_heading',
			[
				'label' => __('Global', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'tab_nav_align',
			[
				'label' => esc_html__('Alignment', 'widgetkit-for-elementor'),
				'type'  => Controls_Manager::CHOOSE,
				'default'  => 'center',
				'options'  => [
					'left'    => [
						'title' => esc_html__('Left', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tabs-nav .wk-adv-tab-title' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .nav-pos-top.nav-icon-pos-left .wk-adv-tabs-nav li' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .nav-icon-pos-left .wk-adv-tabs-nav .no-nav-desc a' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .nav-icon-pos-top .wk-adv-tabs-nav li' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'nav_tab_width',
			[
				'label' => __('Nav Area Width', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .nav-pos-left .wk-adv-tabs-nav, {{WRAPPER}} .nav-pos-right .wk-adv-tabs-nav' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .nav-pos-left .wk-tabs-content, {{WRAPPER}} .nav-pos-right .wk-tabs-content' => 'flex: 0 0 calc(100% - {{SIZE}}{{UNIT}});'
				],
			]
		);

		$this->add_responsive_control(
			'nav_margin_x',
			[
				'label' => __('Horizontal Margin (px)', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::NUMBER,
				'step' => 'any',
				'selectors' => [
					'{{WRAPPER}} .nav-pos-left .wk-adv-tabs-nav li' => 'margin-right: {{VALUE}}px;',
					'{{WRAPPER}} .nav-pos-left .wk-adv-tabs-nav .wk-adv-tab-heading' => 'margin-right: {{VALUE}}px;',
					'{{WRAPPER}} .nav-pos-right .wk-adv-tabs-nav li' => 'margin-left: {{VALUE}}px;',
					'{{WRAPPER}} .nav-pos-right .wk-adv-tabs-nav .wk-adv-tab-heading' => 'margin-left: {{VALUE}}px;',
					'{{WRAPPER}} .nav-pos-top .wk-adv-tabs-nav li:not(:last-child)' => 'margin-right: {{VALUE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'nav_margin_y',
			[
				'label' => __('Vertical Margin (px)', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::NUMBER,
				'step' => 'any',
				'selectors' => [
					'{{WRAPPER}} .nav-pos-left .wk-adv-tabs-nav li:not(:last-child)' => 'margin-bottom: {{VALUE}}px;',
					'{{WRAPPER}} .nav-pos-right .wk-adv-tabs-nav li:not(:last-child)' => 'margin-bottom: {{VALUE}}px;',
					'{{WRAPPER}} .nav-pos-left .wk-adv-tabs-nav li' => 'margin-bottom: {{VALUE}}px;',
					'{{WRAPPER}} .nav-pos-right .wk-adv-tabs-nav li' => 'margin-bottom: {{VALUE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'nav_margin',
			[
				'label' => __('Nav Area Margin', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tabs-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_margin',
			[
				'label' => __('Nav Item Margin', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tabs-nav li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_padding',
			[
				'label' => __('Padding', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [ '20px'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label' => __('Border Radius', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('_tab_nav_stats');
		$this->start_controls_tab(
			'_tab_nav_normal',
			[
				'label' => __('Normal', 'widgetkit-for-elementor'),
			]
		);

		$this->add_control(
			'nav_bg_bg',
			[
				'label' => esc_html__('Background Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f5f5f5',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'nav_border',
				'label' => __('Border', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_box_shadow',
				'label' => __('Box Shadow', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a'
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_nav_hover',
			[
				'label' => __('Hover', 'widgetkit-for-elementor'),
			]
		);

		$this->add_control(
			'nav_hover_bg_bg',
			[
				'label' => esc_html__('Background Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#444',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'nav_hover_border',
				'label' => __('Border', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a:hover'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_hover_box_shadow',
				'label' => __('Box Shadow', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav a:hover'
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_nav_active',
			[
				'label' => __('Active', 'widgetkit-for-elementor'),
			]
		);

		$this->add_control(
			'nav_active_bg_bg',
			[
				'label' => esc_html__('Background Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#444',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'nav_active_border',
				'label' => __('Border', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_active_box_shadow',
				'label' => __('Box Shadow', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active'
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'nav_title_heading',
			[
				'label' => __('Title', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_title_color',
			[
				'label' => __('Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'nav_title_hover_color',
			[
				'label' => __('Hover Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav li:hover .wk-adv-tab-title-text' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'nav_title_active_color',
			[
				'label' => __('Active Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active .wk-adv-tab-title-text' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nav_typography',
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text',
			]
		);

		$this->add_responsive_control(
			'nav_title_spacing',
			[
				'label' => __('Spacing', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text' => 'margin-bottom: {{SIZE}}px;'
				],
			]
		);

		$this->add_control(
			'nav_description',
			[
				'label' => __('Description', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_desc_color',
			[
				'label' => __('Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-desc' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'nav_desc_hover_color',
			[
				'label' => __('Hover Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav li:hover .wk-adv-tab-title-desc' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'nav_desc_active_color',
			[
				'label' => __('Active Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active .wk-adv-tab-title-desc' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nav_desc_typography',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-desc',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'_section_nav_icon',
			[
				'label' => __('Title Icon', 'widgetkit-for-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_icon_color',
			[
				'label' => __('Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-icon>svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-icon>i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text>svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text>i' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'nav_icon_hover_color',
			[
				'label' => __('Hover Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav li a:hover .wk-adv-tab-title-icon>svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav li a:hover .wk-adv-tab-title-icon>i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav li a:hover .wk-adv-tab-title-text>svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav li a:hover .wk-adv-tab-title-text>i' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'nav_icon_active_color',
			[
				'label' => __('Active Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active .wk-adv-tab-title-icon>svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active .wk-adv-tab-title-icon>i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active .wk-adv-tab-title-text>svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .active .wk-adv-tab-title-text>i' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'nav_icon_spacing',
			[
				'label' => __('Margin', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-icon>svg, {{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-icon>i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .nav-icon-pos-left .wk-adv-tab-title-icon>svg, {{WRAPPER}} .nav-icon-pos-left .wk-adv-tab-title-icon>i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text>svg, {{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text>i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .nav-icon-pos-left .wk-adv-tab-title-text>svg, {{WRAPPER}} .nav-icon-pos-left .wk-adv-tab-title-text>i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_icon_size',
			[
				'label' => __('Size', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-icon' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text>svg' => 'width: {{SIZE}}px;',
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-adv-tabs-nav .wk-adv-tab-title-text>i' => 'font-size: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_tab_content',
			[
				'label' => __('Content', 'widgetkit-for-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_content_global',
			[
				'label' => __('Global', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'tab_img_align',
			[
				'label' => esc_html__('Alignment', 'widgetkit-for-elementor'),
				'type'  => Controls_Manager::CHOOSE,
				'default'  => 'left',
				'options'  => [
					'left'    => [
						'title' => esc_html__('Left', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'widgetkit-for-elementor'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __('Padding', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tab_content_border',
				'label' => __('Border', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content'
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => __('Border Radius', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content',
			]
		);

		$this->add_control(
			'_heading_content_text',
			[
				'label' => __('Text', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_content_typography',
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content',
			]
		);

		$this->add_control(
			'tab_content_color',
			[
				'label' => __('Color', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'_heading_content_img',
			[
				'label' => __('Image', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'tab_img_width',
			[
				'label' => __('Width', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tab_img_margin',
			[
				'label' => __('Margin', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tab_img_border',
				'label' => __('Border', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content img',
			]
		);

		$this->add_control(
			'tab_img_border_radius',
			[
				'label' => __('Border Radius', 'widgetkit-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_img_box_shadow',
				'label' => __('Box Shadow', 'widgetkit-for-elementor'),
				'selector' => '{{WRAPPER}} .wk-adv-tab-wrapper .wk-tabs-content-wrap .wk-tabs-content img',
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		require WK_PATH . '/elements/advanced-tab/template/view.php';
	}
}