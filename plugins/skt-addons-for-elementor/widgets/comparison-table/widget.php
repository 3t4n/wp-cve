<?php

/**
 * Comparison_Table widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Comparison_Table extends Base {
	/**
	 * Get widget title.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Comparison Table', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		// return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-scale';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the widget keywords.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return ['comparison table', 'table', 'comparison'];
	}

	/**
	 * Register widget content controls
	 */
	protected function register_content_controls() {
		$this->__table_head_content_controls();
		$this->__table_row_content_controls();
		$this->__table_btn_content_controls();
		$this->__settings_content_controls();
	}

	protected function __table_head_content_controls() {

		$this->start_controls_section(
			'_section_table_column',
			[
				'label' => __( 'Table Head', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'column_name',
			[
				'label'       => __( 'Title', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Column Name', 'skt-addons-elementor' ),
				'default'     => __( 'Column', 'skt-addons-elementor' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_responsive_control(
			'column_width',
			[
				'label'      => __( 'Column Width', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min'  => 0,
						'max'  => 2000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}-sub' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$repeater->add_responsive_control(
			'column_media',
			[
				'label'       => __( 'Media', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'default'     => 'none',
				'options'     => [
					'none' => [
						'title' => __( 'None', 'skt-addons-elementor' ),
						'icon'  => 'eicon-editor-close',
					],
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon'  => 'eicon-info-circle',
					],
				],
			]
		);

		$repeater->add_control(
			'column_icons',
			[
				'label'            => __( 'Icon', 'skt-addons-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'column_icon',
				'label_block'      => true,
				'condition'        => [
					'column_media' => 'icon',
				],
			]
		);

		$repeater->add_control(
			'head_custom_color',
			[
				'label'     => __( 'Icon Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'column_media' => 'icon',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-comparison-table__head-column-cell-icon i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-comparison-table__head-column-cell-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'columns_data',
			[
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ column_name }}}',
				'item_actions'  => [
					'sort' => false,
				],
				'default'       => [
					[
						'column_name' => __( 'Features', 'skt-addons-elementor' ),
						'column_media' => 'icon',
						'column_icons' => [
							'value'   => 'fa fa-rocket',
							'library' => 'fa-solid',
						],
						'column_width' => [
							'size' => 60,
							'unit' => '%',
						],
						'column_width_tablet' => [
							'size' => 40,
							'unit' => '%',
						],
						'column_width_mobile' => [
							'size' => 40,
							'unit' => '%',
						],
					],
					[
						'column_name' => __( 'Free', 'skt-addons-elementor' ),
						'column_media' => 'icon',
						'column_icons' => [
							'value'   => 'far fa-hand-pointer',
							'library' => 'fa-regular',
						],
						'column_width' => [
							'size' => 20,
							'unit' => '%',
						],
						'column_width_tablet' => [
							'size' => 30,
							'unit' => '%',
						],
						'column_width_mobile' => [
							'size' => 30,
							'unit' => '%',
						],
					],
					[
						'column_name' => __( 'Pro', 'skt-addons-elementor' ),
						'column_media' => 'icon',
						'column_icons' => [
							'value'   => 'far fa-hand-spock',
							'library' => 'fa-regular',
						],
						'column_width' => [
							'size' => 20,
							'unit' => '%',
						],
						'column_width_tablet' => [
							'size' => 30,
							'unit' => '%',
						],
						'column_width_mobile' => [
							'size' => 30,
							'unit' => '%',
						],
					],
				],
				'prevent_empty' => false,
			]
		);

		$this->add_responsive_control(
			'head_align',
			[
				'label'        => __( 'Alignment', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'separator'    => 'before',
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => 'center',
				'toggle'       => false,
				'prefix_class' => 'skt-comparison-alignment-',
				'selectors'    => [
					'{{WRAPPER}} .skt-comparison-table__head-item:not(:first-child)' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_position',
			[
				'label'        => __( 'Icon Position', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right'  => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'      => 'left',
				'toggle'       => false,
			]
		);

		$this->add_control(
			'sticky_table_header',
			[
				'label'        => __( 'Sticky Header?', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function __table_row_content_controls() {

		$this->start_controls_section(
			'_section_table_row',
			[
				'label' => __( 'Table Row', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'row_column_type',
			[
				'label'   => __( 'Row/Column', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row'    => __( 'Row', 'skt-addons-elementor' ),
					'column' => __( 'Column', 'skt-addons-elementor' ),
				],
			]
		);

		// $repeater->add_control(
		// 	'column_content_type',
		// 	[
		// 		'label'     => __( 'Column Content Type', 'skt-addons-elementor' ),
		// 		'type'      => Controls_Manager::SELECT,
		// 		'default'   => 'text',
		// 		'options'   => [
		// 			'text' => __( 'Text', 'skt-addons-elementor' ),
		// 			'icon' => __( 'Icon', 'skt-addons-elementor' ),
		// 			'blank' => __( 'Blank', 'skt-addons-elementor' ),
		// 		],
		// 		'condition' => [
		// 			'row_column_type!' => 'row',
		// 		],
		// 	]
		// );

		$repeater->add_control(
			'column_content_type',
			[
				'label'       => __( 'Content Type', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'default'     => 'blank',
				'options'     => [
					'blank' => [
						'title' => __( 'Blank', 'skt-addons-elementor' ),
						'icon'  => 'eicon-editor-close',
					],
					'text' => [
						'title' => __( 'Text', 'skt-addons-elementor' ),
						'icon'  => 'eicon-heading',
					],
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon'  => 'eicon-info-circle',
					],
					'image' => [
						'title' => __( 'Image', 'skt-addons-elementor' ),
						'icon'  => 'eicon-image',
					],
				],
			]
		);

		$repeater->add_control(
			'column_text',
			[
				'label'       => __( 'Title', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Title', 'skt-addons-elementor' ),
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'column_content_type' => 'text',
					'row_column_type!'    => 'row',
				],
			]
		);

		$repeater->add_control(
			'column_image',
			[
				'label'       => __( 'Image', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'   => [
					'column_content_type' => 'image',
					'row_column_type!'    => 'row',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'thumbnail',
			]
		);

		$repeater->add_control(
			'column_icon',
			[
				'label'       => __( 'Icon', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => [
					'value'   => 'fas fa-check-circle',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'column_content_type' => 'icon',
					'row_column_type'     => 'column',
				],
			]
		);

		$repeater->add_control(
			'row_indv_icon_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition'   => [
					'column_content_type' => 'icon',
					'row_column_type'     => 'column',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-comparison-table__row-cell-icon i'   => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-comparison-table__row-cell-icon svg' => 'fill: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'rows_data',
			[
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $repeater->get_controls(),
				'item_actions' => [
					'sort' => true,
				],
				'default'      => [
					[
						'row_column_type' => 'row',
					],
					[
						'row_column_type'     => 'column',
						'column_content_type' => 'text',
						'column_text'         => __( 'Ready Blocks', 'skt-addons-elementor' ),
					],
					[
						'row_column_type'     => 'column',
						'column_content_type' => 'icon',
						'column_icon'         => [
							'value'   => 'fas fa-times-circle',
							'library' => 'fa-solid',
						],
						'row_indv_icon_color' => '#F86363',
					],
					[
						'row_column_type'     => 'column',
						'column_content_type' => 'icon',
						'column_icon'         => [
							'value'   => 'fas fa-check-circle',
							'library' => 'fa-solid',
						],
						'row_indv_icon_color' => '#12B34C',
					],
					[
						'row_column_type' => 'row',
					],
					[
						'row_column_type'     => 'column',
						'column_content_type' => 'text',
						'column_text'         => __( 'Ready Pages', 'skt-addons-elementor' ),
					],
					[
						'row_column_type'     => 'column',
						'column_content_type' => 'text',
						'column_text'         => __( '150', 'skt-addons-elementor' ),
					],
					[
						'row_column_type'     => 'column',
						'column_content_type' => 'text',
						'column_text'         => __( '250', 'skt-addons-elementor' ),
					],
				],
				'title_field'  => '{{{ row_column_type == "row" ? "Row Start" : (column_content_type == "text"  ? column_text : "" || column_content_type == "icon" ? elementor.helpers.renderIcon( this, column_icon, {}, "i", "panel" ) +" Icon" : "" || column_content_type == "blank" ? "Blank" : ""|| column_content_type == "image" ? "Image" : "") }}}',

			]
		);

		$this->add_responsive_control(
			'row_align',
			[
				'label'        => __( 'Alignment', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'separator'    => 'before',
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => 'center',
				'toggle'       => false,
				'selectors'    => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item-cell:not(:first-child)' => 'text-align: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item-cell:not(:first-child)'  => 'text-align: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item-cell:not(:first-child)'  => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function __table_btn_content_controls() {

		$this->start_controls_section(
			'_section_table_btn',
			[
				'label' => __( 'Table Button', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'btn_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Buy Now', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type your title here', 'skt-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'skt-addons-elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'table_btns',
			[
				'label' => __( 'Buttons', 'skt-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'btn_title'     => __( 'Download', 'skt-addons-elementor' ),
						'link' => [
							'url' => '#',
							'is_external' => true,
							'nofollow' => true,
						],
					],
					[
						'btn_title'     => __( 'Buy Now', 'skt-addons-elementor' ),
						'link' => [
							'url' => '#',
							'is_external' => true,
							'nofollow' => true,
						],
					],
				],
				'title_field' => '{{{ btn_title }}}',
				'prevent_empty' => false,
			]
		);

		$this->add_control(
			'more_options',
			[
				'label' => __( 'Button Settings', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'flex-end',
				'toggle' => false,
				'selectors' => ['{{WRAPPER}} .skt-comparison-table__btns' => 'justify-content: {{VALUE}}'],
			]
		);

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {
		$this->start_controls_section(
			'__settings_content',
			[
				'label' => __( 'Table Settings', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'table_width',
			[
				'label' => __( 'Table Width', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'mobile_default'    => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register widget style controls
	 */
	protected function register_style_controls() {
		$this->__table_head_style_controls();
		$this->__table_row_style_controls();
		$this->__table_btn_style_controls();
	}

	protected function __table_head_style_controls() {

		$this->start_controls_section(
			'_section_table_head_style',
			[
				'label' => __( 'Table Head', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'table_head_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .skt-comparison-table-wrapper .skt-comparison-table__head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'head_border_radius',
			[
				'label'     => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table-wrapper .skt-comparison-table__head' => 'border-top-left-radius: {{SIZE}}{{UNIT}};border-top-right-radius: {{SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}} .skt-comparison-table-wrapper .skt-comparison-table__head'  => 'border-top-left-radius: {{SIZE}}{{UNIT}};border-top-right-radius: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .skt-comparison-table-wrapper .skt-comparison-table__head'  => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'head_border',
				'selector' => '{{WRAPPER}} .skt-comparison-table-wrapper .skt-comparison-table__head',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'head_background_color',
				'types'    => ['classic', 'gradient'],
				'exclude'  => ['image'],
				'selector' => '{{WRAPPER}} .skt-comparison-table-wrapper .skt-comparison-table__head',
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Title', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'head_typography',
				'selector' => '{{WRAPPER}} .skt-comparison-table-wrapper .skt-comparison-table__head-item',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'head_text_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table-wrapper  .skt-comparison-table__head-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'_heading_icon',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Icon', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'      => __( 'Spacing', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .skt-comparison-table__head-column-cell-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'head_icon_size',
			[
				'label'     => __( 'Icon Size', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__head-column-cell-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-comparison-table__head-column-cell-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'head_icon_color',
			[
				'label'     => __( 'Icon Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__head-column-cell-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'column_color_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __( 'If you\'ve added <strong>Custom Style</strong> then Icon Color will be over written for that cell.', 'skt-addons-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'render_type' => 'ui',
			]
		);

		$this->end_controls_section();
	}

	protected function __table_row_style_controls() {
		$this->start_controls_section(
			'_section_table_row_style',
			[
				'label' => __( 'Table Row', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'table_row_padding',
			[
				'label'      => __( 'Row Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}} .skt-comparison-table__row'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'row_border',
				'selector' => '{{WRAPPER}} .skt-comparison-table__row-item',
			]
		);

		$this->start_controls_tabs( '_tabs_rows' );
		$this->start_controls_tab(
			'_tab_head_row',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'row_background_color_even',
			[
				'label'     => __( 'Background Color (Even)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even)' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even)'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even)'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even)'  => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_background_color_odd',
			[
				'label'     => __( 'Background Color (Odd)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd)' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd)'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd)'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd)'  => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_color_even',
			[
				'label'     => __( 'Color (Even)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even)' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even)'  => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even)'  => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_color_odd',
			[
				'label'     => __( 'Color (Odd)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd)' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd)'  => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd)'  => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_row',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'row_hover_background_color_even',
			[
				'label'     => __( 'Background Color (Even)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even):hover' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even):hover'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even):hover'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even):hover'  => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_hover_background_color_odd',
			[
				'label'     => __( 'Background Color (Odd)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd):hover' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd):hover'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd):hover'  => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd):hover'  => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_hover_color_even',
			[
				'label'     => __( 'Color (Even)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even):hover' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even):hover'  => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(even):hover'  => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_hover_color_odd',
			[
				'label'     => __( 'Color (Odd)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd):hover' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd):hover'  => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-comparison-table__row-item:nth-of-type(odd):hover'  => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'_row_title',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Title', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'row_text_typography',
				'selector' => '{{WRAPPER}} .skt-comparison-table__row-item-cell-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'_row_image',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Image', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'row_image_width',
			[
				'label'     => __( 'Width', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__row-cell-image img'     => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_image_height',
			[
				'label'     => __( 'Height', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__row-cell-image img'     => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'_row_icon',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Icon', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'row_icon_spacing',
			[
				'label'      => __( 'Spacing', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .skt-comparison-table__row-cell-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_icon_size',
			[
				'label'     => __( 'Size', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__row-cell-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-comparison-table__row-cell-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-comparison-table__row-cell-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row_icon_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__row-cell-icon i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-comparison-table__row-cell-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_style_notice',
			[
				'type'      => Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'raw'       => __( 'If you\'ve added <strong>Custom Style</strong> then Background Color, Color, Icon Size, Icon Color will be over written for that cell.', 'skt-addons-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'render_type' => 'ui',
			]
		);

		$this->end_controls_section();
	}

	protected function __table_btn_style_controls() {

		$this->start_controls_section(
			'_section_table_btn_style',
			[
				'label' => __( 'Table Button', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_text_typography',
				'label'      => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-comparison-table__btns-item--btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label'     => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__btns-item--btn'  => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'btn_padding',
			[
				'label'     => __( 'Padding', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__btns-item--btn'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'btn_border',
				'selector' => '{{WRAPPER}} .skt-comparison-table__btns-item--btn',
			]
		);

		$this->start_controls_tabs( '_tabs_button' );
		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__btns-item--btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__btns-item--btn' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-comparison-table__btns-item--btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tabs_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__btns-item--btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-comparison-table__btns-item--btn:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .skt-comparison-table__btns-item--btn:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings     = $this->get_settings_for_display();
		$columns_data = is_array( $settings['columns_data'] ) ? $settings['columns_data'] : [];
		$rows_data    = is_array( $settings['rows_data'] ) ? $settings['rows_data'] : [];
		$sticky       = ! empty( $settings['sticky_table_header'] ) ? $settings['sticky_table_header'] : 'no';

		$table_row  = [];
		$table_cell = [];

		foreach ( $rows_data as $index => $row ) {
			$row_id = uniqid();

			if ( 'row' == $row['row_column_type'] ) {
				$table_row[] = [
					'id'   => $row_id,
					'type' => $row['row_column_type'],
				];
			}

			if ( 'column' == $row['row_column_type'] ) {
				$table_row_keys = array_keys( $table_row );
				$cell_key       = end( $table_row_keys );

				$table_cell[] = [
					'repeater_id' => $row['_id'],
					'row_id'      => isset( $table_row[ $cell_key ]['id'] ) ? $table_row[ $cell_key ]['id'] : '',
					'title'       => $row['column_text'],
					'row_icons'   => ! empty( $row['column_icon']['value'] ) ? $row['column_icon'] : '',
					'row_image'   => ! empty( $row['column_image'] ) ? $row['column_image'] : '',
					'image_size'   => ! empty( $row['thumbnail_size'] ) ? $row['thumbnail_size'] : '',
				];
			}
		}

		$column_width = [];
		$sub_id = [];
		?>

		<div class="skt-comparison-table-wrapper">
			<div class="skt-comparison-table__head" data-sticky-header="<?php echo esc_attr( $sticky ); ?>">
				<?php if ( $columns_data ) :
					foreach ( $columns_data as $index => $head ) :

						$column_width[]      = $head['column_width'];
						$sub_id[]            = $head['_id'] . '-sub';
						$column_repeater_key = $this->get_repeater_setting_key( 'column_span', 'columns_data', $index );
						$this->add_render_attribute( $column_repeater_key, [
							'class' => ['skt-comparison-table__head-item',
							'elementor-repeater-item-' . $head['_id'],
							'icon-' . $settings['icon_position'],
							],
						] );

						?>
					<div <?php $this->print_render_attribute_string( $column_repeater_key ); ?>>
						<?php if ( ! empty( $head['column_icons'] ) ) : ?>
						<div class="skt-comparison-table__head-column-cell-icon">
							<?php Icons_Manager::render_icon( $head['column_icons'] ); ?>
						</div>
						<?php endif; ?>

						<?php if ( ! empty( $head['column_name'] ) ) : ?>
						<div class="skt-comparison-table__head-column-cell-title">
							<?php echo wp_kses_post(skt_addons_elementor_kses_basic( $head['column_name'] )); ?>
						</div>
						<?php endif; ?>
					</div>
					<?php endforeach;
endif; ?>
			</div>
			<div class="skt-comparison-table__row">
			<?php for ( $i = 0; $i < count( $table_row ); $i++ ) :
				$index = 0; ?>
				<div class="skt-comparison-table__row-item">
					<?php for ( $j = 0; $j < count( $table_cell ); $j++ ) :

						if ( $table_row[ $i ]['id'] == $table_cell[ $j ]['row_id'] ) :
							$row_repeater_key = $this->get_repeater_setting_key( 'column_span', 'rows_data', $index );
							$this->add_render_attribute( 'row_repeater_key', 'class', [ 'skt-comparison-table__row-item-cell', 'elementor-repeater-item-' . $table_cell[ $j ]['repeater_id'], 'elementor-repeater-item-' . $sub_id[ $index ] ] );
							?>
					<div <?php $this->print_render_attribute_string( 'row_repeater_key' ); ?>>
							<?php if ( ! empty( $table_cell[ $j ]['title'] ) ) : ?>
							<div class="skt-comparison-table__row-item-cell-title">
								<?php echo wp_kses_post(skt_addons_elementor_kses_basic( $table_cell[ $j ]['title'] )); ?>
							</div>
						<?php endif; ?>
							<?php if ( ! empty( $table_cell[ $j ]['row_icons'] ) ) : ?>
							<div class="skt-comparison-table__row-cell-icon">
								<?php Icons_Manager::render_icon( $table_cell[ $j ]['row_icons'] ); ?>
							</div>
						<?php endif; ?>
							<?php if ( ! empty( $table_cell[ $j ]['row_image'] ) ) : ?>
							<div class="skt-comparison-table__row-cell-image">	
								<?php
									echo wp_kses_post(wp_get_attachment_image( $table_cell[ $j ]['row_image']['id'], $table_cell[ $j ]['image_size'] ));
								?>
							</div>
						<?php endif; ?>
					</div>

							<?php
								$this->remove_render_attribute( 'row_repeater_key' );
								$index++;
							endif;
						endfor;?>
				</div>
				<?php endfor; ?>
			</div>
			<div class="skt-comparison-table__btns">
				<?php
					$btns = $settings['table_btns'];

				if ( is_array( $btns ) ) {
					foreach ( $btns as $index => $btn ) {
						$column_repeater_key = $this->get_repeater_setting_key( '', 'table_btns', $index );
						$this->add_render_attribute( $column_repeater_key, 'class', ['skt-comparison-table__btns-item', 'elementor-repeater-item-' . $sub_id[ $index + 1 ]] );
						$this->add_render_attribute( 'button', 'class', ['skt-comparison-table__btns-item--btn'] );
						if ( $btn['link']['url'] ) {
							$this->add_link_attributes( 'button', $btn['link'] );
						}

						?>
							<div <?php $this->print_render_attribute_string( $column_repeater_key ); ?>>
								<a <?php $this->print_render_attribute_string( 'button' ); ?>>	<?php echo esc_html($btn['btn_title']); ?></a>
							</div>
						<?php
						$this->remove_render_attribute( $column_repeater_key );
					}
				}
				?>
			</div>
		</div>

		<?php
	}
}