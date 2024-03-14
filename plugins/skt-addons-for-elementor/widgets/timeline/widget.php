<?php
/**
 * Timeline
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Repeater;

defined('ABSPATH') || die();

class Timeline extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Timeline', 'skt-addons-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-timeline';
	}

	public function get_keywords() {
		return ['timeline', 'time', 'schedule'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__timeline_content_controls();
		$this->__settings_content_controls();
	}

	protected function __timeline_content_controls() {

		$this->start_controls_section(
			'_section_timeline',
			[
				'label' => __('Timeline', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs('tabs_timeline_item');
		$repeater->start_controls_tab(
			'tab_timeline_content_item',
			[
				'label' => __('Content', 'skt-addons-elementor'),
			]
		);
		$repeater->add_control(
			'icon_type',
			[
				'label' => __('Icon Type', 'skt-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon' => __('Icon', 'skt-addons-elementor'),
					'image' => __('Image', 'skt-addons-elementor'),
				],
				'default' => 'icon',
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => __('Icon', 'skt-addons-elementor'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-calendar-alt',
					'library' => 'solid',
				],
				'condition' => [
					'icon_type' => 'icon'
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __('Image Icon', 'skt-addons-elementor'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => 'image'
				],
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$repeater->add_control(
			'time_style',
			[
				'label' => __('Time', 'skt-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'calender' => __('Calender', 'skt-addons-elementor'),
					'text' => __('Text', 'skt-addons-elementor'),
				],
				'default' => 'calender',
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'time',
			[
				'label' => __('Calender Time', 'skt-addons-elementor'),
				'show_label' => false,
				'type' => Controls_Manager::DATE_TIME,
				'default' => date('M d Y g:i a'),
				'condition' => [
					'time_style' => 'calender'
				],
			]
		);

		$repeater->add_control(
			'time_text',
			[
				'label' => __('Text Time', 'skt-addons-elementor'),
				'show_label' => false,
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __('2016 - 2018', 'skt-addons-elementor'),
				'placeholder' => __('Text Time', 'skt-addons-elementor'),
				'condition' => [
					'time_style' => 'text'
				],
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __('Title', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('This Is SKT Title', 'skt-addons-elementor'),
				'placeholder' => __('Title', 'skt-addons-elementor'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'gallery',
			[
				'type' => Controls_Manager::GALLERY
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => ['custom'],
				'default' => 'thumbnail',
			]
		);

		$repeater->add_control(
			'image_position',
			[
				'label' => __('Image Position', 'skt-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'before' => __('Before Title', 'skt-addons-elementor'),
					'after' => __('After Content', 'skt-addons-elementor'),
				],
				'default' => 'before',
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => __('Content', 'skt-addons-elementor'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut',
				'placeholder' => __('Type your content here', 'skt-addons-elementor'),
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __('Button Text', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Read More', 'skt-addons-elementor'),
				'placeholder' => __('Button Text', 'skt-addons-elementor'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => __('Button Link', 'skt-addons-elementor'),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://example.com/',
				'default' => [
					'url' => '#',
					'is_external' => true,
					'nofollow' => true,
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'button_text!' => '',
				]
			]
		);
		$repeater->end_controls_tab();//Timeline Content Tab END

		//Timeline Style Tab
		$repeater->start_controls_tab(
			'tab_timeline_style_item',
			[
				'label' => __('Style', 'skt-addons-elementor'),
			]
		);

		$repeater->add_control(
			'single_icon_color',
			[
				'label' => __('Icon Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-timeline-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-timeline-icon svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'icon_type' => 'icon'
				],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'single_icon_box_bg',
			[
				'label' => __('Icon box Background', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-timeline-icon' => 'background: {{VALUE}}',
				],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'single_icon_box_border_bg',
			[
				'label' => __('Icon box border color', 'skt-addons-elementor'),
				'description' => __('Color will apply after set the border from style.', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-timeline-icon' => 'border-color: {{VALUE}}',
				],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'single_icon_box_tree_color',
			[
				'label' => __('Icon box tree color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-timeline-tree' => 'background: {{VALUE}}',
				],
				'style_transfer' => true,
			]
		);

		$repeater->add_responsive_control(
			'single_content_align',
			[
				'label' => __('Alignment', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __('Justify', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-wrap > {{CURRENT_ITEM}} .skt-timeline-content' => 'text-align: {{VALUE}}'
				],
				'style_transfer' => true,
			]
		);
		$repeater->end_controls_tab();//Timeline Style Tab END
		$repeater->end_controls_tabs();

		$this->add_control(
			'timeline_item',
			[
				'label' => __('Content List', 'skt-addons-elementor'),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'icon_type' => 'icon',
						'icon' => [
							'value' => 'fas fa-calendar-alt',
							'library' => 'solid',
						],
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'time' => date('M d Y g:i a'),
						'title' => __('This Is SKT Title', 'skt-addons-elementor'),
						'image_position' => 'before',
						'content' => '<p>' . __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut', 'skt-addons-elementor') . '</p>',
						'button_text' => __('Button Text', 'skt-addons-elementor'),
						'button_link' => [
							'url' => '#',
							'is_external' => true,
							'nofollow' => true,
						],
					],
					[
						'icon_type' => 'icon',
						'icon' => [
							'value' => 'fas fa-calendar-alt',
							'library' => 'solid',
						],
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'time' => date('M d Y g:i a'),
						'title' => __('This Is SKT Title', 'skt-addons-elementor'),
						'image_position' => 'before',
						'content' => '<p>' . __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut', 'skt-addons-elementor') . '</p>',
						'button_text' => __('Button Text', 'skt-addons-elementor'),
						'button_link' => [
							'url' => '#',
							'is_external' => true,
							'nofollow' => true,
						],
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

		$this->start_controls_section(
			'_section_timeline_settings',
			[
				'label' => __('Settings', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'show_date',
			[
				'label' => __('Show Date?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'skt-addons-elementor'),
				'label_off' => __('Hide', 'skt-addons-elementor'),
				'return_value' => 'yes',
				'default' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_time',
			[
				'label' => __('Show Time?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'skt-addons-elementor'),
				'label_off' => __('Hide', 'skt-addons-elementor'),
				'return_value' => 'yes',
				'default' => '',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_content_arrow',
			[
				'label' => __('Show Content Arrow?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'skt-addons-elementor'),
				'label_off' => __('Hide', 'skt-addons-elementor'),
				'return_value' => 'yes',
				'default' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __('Title Tag', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'h2',
				'options' => [
					'h1' => [
						'title' => __('H1', 'skt-addons-elementor'),
						'icon' => 'eicon-editor-h1'
					],
					'h2' => [
						'title' => __('H2', 'skt-addons-elementor'),
						'icon' => 'eicon-editor-h2'
					],
					'h3' => [
						'title' => __('H3', 'skt-addons-elementor'),
						'icon' => 'eicon-editor-h3'
					],
					'h4' => [
						'title' => __('H4', 'skt-addons-elementor'),
						'icon' => 'eicon-editor-h4'
					],
					'h5' => [
						'title' => __('H5', 'skt-addons-elementor'),
						'icon' => 'eicon-editor-h5'
					],
					'h6' => [
						'title' => __('H6', 'skt-addons-elementor'),
						'icon' => 'eicon-editor-h6'
					]
				],
				'toggle' => false,
			]
		);

		$this->add_control(
			'icon_box_align',
			[
				'label' => __('Icon Box Alignment', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __('Top', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __('Center', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'toggle' => false,
				'default' => 'top',
				'prefix_class' => 'skt-timeline-icon-box-vertical-align-',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'tree_align',
			[
				'label' => __('Tree Alignment', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => false,
				'prefix_class' => 'skt-timeline-align-',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_scroll_tree',
			[
				'label' => __('Show Scroll Tree?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'skt-addons-elementor'),
				'label_off' => __('Hide', 'skt-addons-elementor'),
				'return_value' => 'yes',
				'default' => '',
				'style_transfer' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'scroll_tree_background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-timeline-scroll-tree .skt-timeline-icon, {{WRAPPER}} .skt-timeline-tree-inner',
				'exclude' => [
					'image'
				],
				'condition' => [
					'show_scroll_tree' => 'yes'
				],
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__content_box_style_controls();
		$this->__icon_box_style_controls();
		$this->__title_style_controls();
		$this->__time_date_style_controls();
		$this->__button_style_controls();
	}

	protected function __content_box_style_controls() {

		$this->start_controls_section(
			'_section_timeline_content_box_style',
			[
				'label' => __('Content Box', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_box_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-timeline-content',
			]
		);

		$this->add_control(
			'content_box_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_box_arrow_color',
			[
				'label' => __('Arrow Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					//Align center
					'(desktop){{WRAPPER}}.skt-timeline-align-center .skt-timeline-content.arrow::before' => 'border-left-color: {{VALUE}}',
					'(desktop){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block:nth-child(even) .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					'(tablet){{WRAPPER}}.skt-timeline-align-center .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}};border-left-color: transparent',
					'(tablet){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block:nth-child(even) .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					'(mobile){{WRAPPER}}.skt-timeline-align-center .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}};border-left-color: transparent',
					'(mobile){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block:nth-child(even) .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					//Align Left
					'{{WRAPPER}}.skt-timeline-align-left .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}}',
					//Align Right
					'(desktop){{WRAPPER}}.skt-timeline-align-right .skt-timeline-content.arrow::before' => 'border-left-color: {{VALUE}};border-right-color: transparent',
					'(tablet){{WRAPPER}}.skt-timeline-align-right .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}};border-left-color: transparent',
					'(mobile){{WRAPPER}}.skt-timeline-align-right .skt-timeline-content.arrow::before' => 'border-right-color: {{VALUE}};border-left-color: transparent',
				],
				'condition' => [
					'show_content_arrow' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '{{WRAPPER}} .skt-timeline-content',
			]
		);

		$this->add_control(
			'content_bg_after', ['type' => Controls_Manager::DIVIDER,]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_box_border',
				'label' => __('Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-timeline-content',
			]
		);

		$this->add_control(
			'content_border_after', ['type' => Controls_Manager::DIVIDER,]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => __('Box Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-timeline-content',
			]
		);

		$this->add_responsive_control(
			'content_box_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_box_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_box_margin_bottom',
			[
				'label' => __('Margin Bottom', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-timeline-block:last-child .skt-timeline-content' => 'margin-bottom: 0;',
					'{{WRAPPER}}.skt-timeline-icon-box-vertical-align-center .skt-timeline-icon' => 'margin-top: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}}.skt-timeline-icon-box-vertical-align-center .skt-timeline-block:last-child .skt-timeline-icon' => 'margin-top: 0;',
					'{{WRAPPER}}.skt-timeline-icon-box-vertical-align-bottom .skt-timeline-icon' => 'margin-top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-timeline-icon-box-vertical-align-bottom .skt-timeline-block:last-child .skt-timeline-icon' => 'margin-top: 0;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __icon_box_style_controls() {

		$this->start_controls_section(
			'_section_timeline_icon_box_style',
			[
				'label' => __('Icon Box', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_box_width',
			[
				'label' => __('Width', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-icon' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-timeline-block:nth-child(even) .skt-timeline-icon' => 'width: {{SIZE}}{{UNIT}};',
					//timeline align center -> content box width
					'(desktop){{WRAPPER}}.skt-timeline-align-center .skt-timeline-content' => 'width: calc(50% - (({{icon_box_width.SIZE || 48}}{{UNIT}}/2) + {{icon_box_space.SIZE || 30}}{{UNIT}}));',
					'(tablet){{WRAPPER}}.skt-timeline-align-center .skt-timeline-content' => 'width: calc(100% - (({{icon_box_width_tablet.SIZE || 40}}{{UNIT}}/2) + {{icon_box_space_tablet.SIZE || 35}}{{UNIT}}));',
					'(mobile){{WRAPPER}}.skt-timeline-align-center .skt-timeline-content' => 'width: calc(100% - (({{icon_box_width_mobile.SIZE || 40}}{{UNIT}}/2) + {{icon_box_space_mobile.SIZE || 35}}{{UNIT}}));',

					//timeline align left -> content box width
					'(desktop){{WRAPPER}}.skt-timeline-align-left .skt-timeline-content' => 'width: calc(100% - ({{icon_box_width.SIZE || 48}}{{UNIT}} + {{icon_box_space.SIZE || 30}}{{UNIT}} + {{icon_box_tree_space.SIZE || 110}}{{UNIT}}));',
					'(tablet){{WRAPPER}}.skt-timeline-align-left .skt-timeline-content' => 'width: calc(100% - ({{icon_box_width_tablet.SIZE || 40}}{{UNIT}} + {{icon_box_space_tablet.SIZE || 30}}{{UNIT}} + {{icon_box_tree_space_tablet.SIZE || 0}}{{UNIT}}));',
					'(mobile){{WRAPPER}}.skt-timeline-align-left .skt-timeline-content' => 'width: calc(100% - ({{icon_box_width_mobile.SIZE || 40}}{{UNIT}} + {{icon_box_space_mobile.SIZE || 30}}{{UNIT}} + {{icon_box_tree_space_mobile.SIZE || 0}}{{UNIT}}));',

					//timeline align right -> content box width
					'(desktop){{WRAPPER}}.skt-timeline-align-right .skt-timeline-content' => 'width: calc(100% - ({{icon_box_width.SIZE || 48}}{{UNIT}} + {{icon_box_space.SIZE || 30}}{{UNIT}} + {{icon_box_tree_space.SIZE || 110}}{{UNIT}}));',
					'(tablet){{WRAPPER}}.skt-timeline-align-right .skt-timeline-content' => 'width: calc(100% - ({{icon_box_width_tablet.SIZE || 40}}{{UNIT}} + {{icon_box_space_tablet.SIZE || 30}}{{UNIT}} + {{icon_box_tree_space_tablet.SIZE || 0}}{{UNIT}}));',
					'(mobile){{WRAPPER}}.skt-timeline-align-right .skt-timeline-content' => 'width: calc(100% - ({{icon_box_width_mobile.SIZE || 40}}{{UNIT}} + {{icon_box_space_mobile.SIZE || 30}}{{UNIT}} + {{icon_box_tree_space_mobile.SIZE || 0}}{{UNIT}}));',

				],
			]
		);

		$this->add_responsive_control(
			'icon_box_height',
			[
				'label' => __('Height', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-icon' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-timeline-block:nth-child(even) .skt-timeline-icon' => 'height: {{SIZE}}{{UNIT}};',
					//timeline content box arrow position
					'(desktop){{WRAPPER}}.skt-timeline-icon-box-vertical-align-top .skt-timeline-content.arrow::before' => 'top: calc(({{icon_box_height.SIZE}}{{UNIT}}/2) - 8px)',
					'(desktop){{WRAPPER}}.skt-timeline-icon-box-vertical-align-bottom .skt-timeline-content.arrow::before' => 'bottom: calc(({{icon_box_height.SIZE}}{{UNIT}}/2) - 8px)',
				],
			]
		);
		//Box Space
		$this->add_responsive_control(
			'icon_box_space',
			[
				'label' => __('Box Space', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					//timeline align center -> box margin
					'(desktop){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block .skt-timeline-icon-box' => 'margin-left: {{icon_box_space.SIZE || 30}}{{UNIT}};margin-right: 0;',
					'(desktop){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block:nth-child(even) .skt-timeline-icon-box' => 'margin-left: 0;margin-right: {{icon_box_space.SIZE || 30}}{{UNIT}};',

					'(tablet){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block .skt-timeline-icon-box' => 'margin-right: {{icon_box_space_tablet.SIZE || 35}}{{UNIT}};margin-left: 0;',
					'(tablet){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block:nth-child(even) .skt-timeline-icon-box' => 'margin-left: 0;margin-right: {{icon_box_space_tablet.SIZE || 35}}{{UNIT}};',

					'(mobile){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block .skt-timeline-icon-box' => 'margin-right: {{icon_box_space_mobile.SIZE || 35}}{{UNIT}};margin-left: 0;',
					'(mobile){{WRAPPER}}.skt-timeline-align-center .skt-timeline-block:nth-child(even) .skt-timeline-icon-box' => 'margin-left: 0;margin-right: {{icon_box_space_mobile.SIZE || 35}}{{UNIT}};',
				],
			]
		);
		//Box Tree Space
		$this->add_responsive_control(
			'icon_box_tree_space',
			[
				'label' => __('Tree Space', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'condition' => ['tree_align!' => 'center'],
				'selectors' => [
					//timeline align left -> box margin
					'(desktop){{WRAPPER}}.skt-timeline-align-left .skt-timeline-block .skt-timeline-icon-box' => 'margin-right: {{icon_box_space.SIZE || 30}}{{UNIT}};margin-left: {{icon_box_tree_space.SIZE || 110}}{{UNIT}};',
					'(tablet){{WRAPPER}}.skt-timeline-align-left .skt-timeline-block .skt-timeline-icon-box' => 'margin-right: {{icon_box_space_tablet.SIZE || 30}}{{UNIT}};margin-left: {{icon_box_tree_space_tablet.SIZE || 0}}{{UNIT}};',
					'(mobile){{WRAPPER}}.skt-timeline-align-left .skt-timeline-block .skt-timeline-icon-box' => 'margin-right: {{icon_box_space_mobile.SIZE || 30}}{{UNIT}};margin-left: {{icon_box_tree_space_mobile.SIZE || 0}}{{UNIT}};',

					//timeline align right -> box margin
					'(desktop){{WRAPPER}}.skt-timeline-align-right .skt-timeline-block .skt-timeline-icon-box' => 'margin-left: {{icon_box_space.SIZE || 30}}{{UNIT}};margin-right: {{icon_box_tree_space.SIZE || 110}}{{UNIT}};',
					'(tablet){{WRAPPER}}.skt-timeline-align-right .skt-timeline-block .skt-timeline-icon-box' => 'margin-right: {{icon_box_space_tablet.SIZE || 30}}{{UNIT}};margin-left: {{icon_box_tree_space_tablet.SIZE || 0}}{{UNIT}};',
					'(mobile){{WRAPPER}}.skt-timeline-align-right .skt-timeline-block .skt-timeline-icon-box' => 'margin-right: {{icon_box_space_mobile.SIZE || 30}}{{UNIT}};margin-left: {{icon_box_tree_space_mobile.SIZE || 0}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'icon_box_bg',
			[
				'label' => __('Background', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-icon' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_box_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_box_border',
				'label' => __('Icon box border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-timeline-icon',
			]
		);


		$this->add_control(
			'icon_color',
			[
				'label' => __('Icon Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-timeline-icon svg' => 'fill: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_box_tree_width',
			[
				'label' => __('Tree Width', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-tree' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-timeline-tree-inner' => 'width: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'icon_box_tree_color',
			[
				'label' => __('Tree color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-tree' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'_section_timeline_title_style',
			[
				'label' => __('Title', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
			],
				'selector' => '{{WRAPPER}} .skt-timeline-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __time_date_style_controls() {

		$this->start_controls_section(
			'_section_timeline_time_date_style',
			[
				'label' => __('Time & Date', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'terms' => [
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'show_date',
									'operator' => '==',
									'value' => 'yes',
								],
								[
									'name' => 'show_time',
									'operator' => '==',
									'value' => 'yes',
								]
							],
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => __('Date Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-timeline-date .date',
				'condition' => [
					'show_date' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'date_color',
			[
				'label' => __('Date Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'devices' => ['desktop', 'tablet', 'mobile'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-date .date' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_date' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => __('Date Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-date .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_date' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'time_typography',
				'label' => __('Time Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-timeline-date .time',
				'condition' => [
					'show_time' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'time_color',
			[
				'label' => __('Time Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'devices' => ['desktop', 'tablet', 'mobile'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-date .time' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_time' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'time_margin',
			[
				'label' => __('Time Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-date .time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_time' => 'yes'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __button_style_controls() {

		$this->start_controls_section(
			'_section_timeline_button_style',
			[
				'label' => __('Button', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
				'selector' => '{{WRAPPER}} .skt-timeline-button',
			]
		);

		$this->start_controls_tabs(
			'button_tabs'
		);
		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => __('Normal', 'skt-addons-elementor'),
			]
		);
		$this->add_control(
			'button_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-button' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-timeline-button',
			]
		);
		$this->end_controls_tab();//Button Normal Tab END

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => __('Hover', 'skt-addons-elementor'),
			]
		);
		$this->add_control(
			'button_hover_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-button:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-timeline-button:hover',
			]
		);
		$this->end_controls_tab(); //Button Hover Tab END
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-timeline-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute('timeline-wrap', 'class', 'skt-timeline-wrap');
		if( 'yes' === $settings['show_scroll_tree'] ){
			$this->add_render_attribute('timeline-wrap', 'data-scroll', $settings['show_scroll_tree']);
		}
		?>
		<div <?php echo wp_kses_post($this->get_render_attribute_string('timeline-wrap')); ?>>
		<?php if ($settings['timeline_item']):
			foreach ($settings['timeline_item'] as $key => $item):?>
				<?php
				//Block
				$this->set_render_attribute('timeline-block', 'class', ['skt-timeline-block', 'elementor-repeater-item-' . esc_attr($item['_id'])]);
				//Date
				$date = date("d M Y", strtotime($item['time']));
				if('text' == $item['time_style']){
					$date = $item['time_text'];
				}
				$time = 'calender' == $item['time_style'] ? date("g:i a", strtotime($item['time'])) : '';
				//Icon Image
				if ('image' == $item['icon_type'] && $item['image']) {
					$this->add_render_attribute('image', 'src', $item['image']['url']);
					$this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($item['image']));
					$this->add_render_attribute('image', 'title', Control_Media::get_image_title($item['image']));
				}

				//Title
				$title_key = $this->get_repeater_setting_key('title', 'timeline_item', $key);
				$this->add_inline_editing_attributes($title_key, 'none');
				$this->add_render_attribute($title_key, 'class', 'skt-timeline-title');

				//Content box
				$this->add_render_attribute('content-box', 'class', 'skt-timeline-content');
				if ($settings['show_content_arrow']) {
					$this->add_render_attribute('content-box', 'class', 'arrow');
				}

				//Content Text
				$content_key = $this->get_repeater_setting_key('content', 'timeline_item', $key);
				$this->add_inline_editing_attributes($content_key, 'advanced');
				$this->add_render_attribute($content_key, 'class', 'skt-timeline-content-text');

				//Button
				if ($item['button_text']) {
					$button_key = $this->get_repeater_setting_key('button_text', 'timeline_item', $key);
					$this->add_inline_editing_attributes($button_key, 'none');
					$this->add_render_attribute($button_key, 'class', 'skt-timeline-button');

					$this->add_link_attributes( $button_key, $item['button_link'] );
				}
				?>
				<div <?php $this->print_render_attribute_string('timeline-block'); ?>>
					<div class="skt-timeline-icon-box align-center">
						<div class="skt-timeline-icon">
							<?php
							if ('icon' == $item['icon_type'] && $item['icon']) {
								Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']);
							} elseif ('image' == $item['icon_type'] && $item['image']) {
								echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'image'));
							}
							?>
							<?php if (($date || $time) && ($settings['show_date'] || $settings['show_time'])): ?>
								<span class="skt-timeline-date skt-timeline-date-desktop">
									<?php
									if ($date && $settings['show_date']) {
										printf('<span class="date">%s</span>', esc_html($date));
									}
									if ($time && $settings['show_time']) {
										printf('<span class="time">%s</span>', esc_html($time));
									}
									?>
								</span>
							<?php endif; ?>
						</div>
						<div class="skt-timeline-tree">
							<?php if( 'yes' === $settings['show_scroll_tree'] ):?>
							<div class="skt-timeline-tree-inner"></div>
							<?php endif;?>
						</div>
					</div>
					<div <?php $this->print_render_attribute_string('content-box'); ?>">
					<?php if (($date || $time) && ($settings['show_date'] || $settings['show_time'])): ?>
						<span class="skt-timeline-date skt-timeline-date-tablet">
							<?php
							if ($date && $settings['show_date']) {
								printf('<span class="date">%s</span>', esc_html($date));
							}
							if ($time && $settings['show_time']) {
								printf('<span class="time">%s</span>', esc_html($time));
							}
							?>
						</span>
					<?php endif; ?>
					<?php
					if (!empty($item['gallery']) && 'before' == $item['image_position']) {
						echo wp_kses_post('<figure class="skt-timeline-images before">');
						foreach ($item['gallery'] as $id => $single) {
							echo wp_kses_post(wp_get_attachment_image(
								$single['id'],
								$item['thumbnail_size'],
								false,
								[
									'alt' => wp_get_attachment_caption($single['id'])
								]
							));
						}
						echo wp_kses_post('</figure>');
					}
					?>
					<?php
					if ($item['title']) {
						printf('<%1$s %2$s>%3$s</%1$s>', skt_addons_elementor_escape_tags($settings['title_tag']), $this->get_render_attribute_string($title_key), esc_html($item['title']));
					}

					if ($item['content']) {
						printf('<div %s>%s</div>', $this->get_render_attribute_string($content_key), $this->parse_text_editor($item['content']));
					}
					if (!empty($item['gallery']) && 'after' == $item['image_position']) {
						echo wp_kses_post('<figure class="skt-timeline-images after">');
						foreach ($item['gallery'] as $id => $single) {
							echo wp_kses_post(wp_get_attachment_image(
								$single['id'],
								$item['thumbnail_size'],
								false,
								[
									'alt' => wp_get_attachment_caption($single['id'])
								]
							));
						}
						echo wp_kses_post('</figure>');
					}
					if ($item['button_text']) {
						printf('<a %s>%s</a>', $this->get_render_attribute_string($button_key), esc_html($item['button_text']));
					}
					?>
				</div>
				</div>
			<?php endforeach;
		endif; ?>
		</div>
		<?php
	}
}