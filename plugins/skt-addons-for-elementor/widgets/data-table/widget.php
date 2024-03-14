<?php
/**
 * Data Table
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Control_Media;

defined('ABSPATH') || die();

class Data_Table extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Data Table', 'skt-addons-elementor' );
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
		return 'skti skti-data-table';
	}

	public function get_keywords() {
		return ['data', 'table', 'statistics'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__table_head_content_controls();
		$this->__table_row_content_controls();
	}

	protected function __table_head_content_controls() {

		$this->start_controls_section(
			'_section_table_column',
			[
				'label' => __( 'Table Head', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( '_tabs_column' );

		$repeater->start_controls_tab(
			'_tab_column_content',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'column_name',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Column Name', 'skt-addons-elementor' ),
				'default' => __( 'Column One', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'column_span',
			[
				'label' => __( 'Col Span', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1
			]
		);

		$repeater->add_responsive_control(
			'column_media',
			[
				'label' => __( 'Media', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle' => false,
				'default' => 'none',
				'options' => [
					'none' => [
						'title' => __( 'None', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-close',
					],
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon' => 'eicon-info-circle',
					],
					'image' => [
						'title' => __( 'Image', 'skt-addons-elementor' ),
						'icon' => 'eicon-image-bold',
					],
				]
			]
		);

		$repeater->add_control(
			'column_icons',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'column_icon',
				'label_block' => true,
				'condition' => [
					'column_media' => 'icon'
				],
			]
		);

		$repeater->add_control(
            'column_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'column_media' => 'image'
                ]
            ]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'column_thumbnail',
				'default' => 'thumbnail',
				'separator' => 'none',
				'condition' => [
                    'column_media' => 'image'
                ]
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( '_tabs_column_style',
			[
				'label' => __( 'Style', 'skt-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'head_custom_color',
			[
				'label' => __( 'Icon Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'column_media' => 'icon'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-table__head-column-cell-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'columns_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ column_name }}}',
				'default' => [
					[
						'column_name' => __( 'WordPress', 'skt-addons-elementor' )
					],
					[
						'column_name' => __( 'Elementor', 'skt-addons-elementor' )
					],
					[
						'column_name' => __( 'SKT Addons', 'skt-addons-elementor' )
					],
				]
			]
		);

		$this->add_responsive_control(
			'head_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'center',
				'toggle' => false,
				'prefix_class' => 'skt-column-alignment-',
				'selectors' => [
					'{{WRAPPER}} .skt-table__head-column-cell' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'icon_position',
			[
				'label' => __( 'Icon Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'right',
				'toggle' => false,
				'prefix_class' => 'skt-column-icon-'
			]
		);

		$this->end_controls_section();
	}

	protected function __table_row_content_controls() {

		$this->start_controls_section(
			'_section_table_row',
			[
				'label' => __( 'Table Row', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
					'row' => __( 'Row', 'skt-addons-elementor' ),
					'column' => __( 'Column', 'skt-addons-elementor' ),
				],
			]
		);

		$repeater->start_controls_tabs( '_tabs_row' );

		$repeater->start_controls_tab(
			'_tab_row_content',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'cell_name',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Cell Name', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'cell_link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'row_column_span',
			[
				'label' => __( 'Col Span', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'row_span',
			[
				'label' => __( 'Row Span', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'row_media',
			[
				'label' => __( 'Media', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle' => false,
				'default' => 'none',
				'condition' => [
					'row_column_type' => 'column'
				],
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon' => 'eicon-info-circle',
					],
					'image' => [
						'title' => __( 'Image', 'skt-addons-elementor' ),
						'icon' => 'eicon-image-bold',
					],
					'none' => [
						'title' => __( 'None', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-close',
					],
				]
			]
		);

		$repeater->add_control(
			'row_icons',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'row_icon',
				'label_block' => true,
				'condition' => [
					'row_media' => 'icon',
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
            'row_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
					'row_media' => 'image',
					'row_column_type' => 'column'
				],
                'dynamic' => [
                    'active' => true,
                ]
            ]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'row_thumbnail',
				'default' => 'thumbnail',
				'separator' => 'none',
				'exclude' => [ 'custom' ],
				'condition' => [
					'row_media' => 'image',
					'row_column_type' => 'column'
                ],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( '_tabs_row_style',
			[
				'label' => __( 'Style', 'skt-addons-elementor' ),
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'row_custom_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'row_column_type' => 'column'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.skt-table__body-row-cell' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'row_custom_text_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'row_column_type' => 'column'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-table__body-row-cell-text' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'row_custom_icon_color',
			[
				'label' => __( 'Icon Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'row_column_type' => 'column',
					'row_media' => 'icon'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-table__body-row-cell-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->add_responsive_control(
			'row_custom_icon_size',
			[
				'label' => __( 'Icon/Image Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'condition' => [
					'row_column_type' => 'column'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-table__body-row-cell-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-table__body-row-cell-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .skt-table__body-row-cell-icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'row_starts',
			[
				'label' => false,
				'type' => Controls_Manager::HIDDEN,
				'default' => __( 'Row Starts', 'skt-addons-elementor' ),
				'condition' => [
					'row_column_type' => 'row'
				],
			]
		);

		$this->add_control(
			'rows_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '<# print( (row_column_type == "column" ) ? cell_name : ("Row Starts") ) #>',
				'default' => [
					[
						'row_column_type' => 'row',
						'row_starts' => __( 'Row Starts', 'skt-addons-elementor' ),
					],
					[
						'row_column_type' => 'column',
						'cell_name' => __( 'Stay Happy', 'skt-addons-elementor' )
					],
					[
						'row_column_type' => 'column',
						'cell_name' => __( 'Stay Safe', 'skt-addons-elementor' )
					],
					[
						'row_column_type' => 'column',
						'cell_name' => __( 'Spread Happiness', 'skt-addons-elementor' )
					],
				]
			]
		);

		$this->add_responsive_control(
			'row_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'left',
				'toggle' => false,
				'prefix_class' => 'skt-row-alignment-',
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body-row-cell' => 'text-align: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body-row-cell' => 'text-align: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body-row-cell' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'row_icon_position',
			[
				'label' => __( 'Icon Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'right',
				'toggle' => false,
				'prefix_class' => 'skt-row-icon-'
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
	}

	protected function __table_head_style_controls() {

		$this->start_controls_section(
			'_section_table_head',
			[
				'label' => __( 'Table Head', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_head_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-table .skt-table__head-column-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'head_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table .skt-table__head-column-cell:first-child' => 'border-top-left-radius: {{SIZE}}{{UNIT}};',
					'(desktop){{WRAPPER}} .skt-table .skt-table__head-column-cell:last-child' => 'border-top-right-radius: {{SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}} .skt-table .skt-table__head-column-cell:first-child' => 'border-top-left-radius: {{SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}} .skt-table .skt-table__head-column-cell:last-child' => 'border-top-right-radius: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .skt-table .skt-table__head-column-cell' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'head_border',
				'selector' => '{{WRAPPER}} .skt-table .skt-table__head-column-cell',
			]
		);

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'head_background_color',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-table .skt-table__head-column-cell',
            ]
        );

		$this->add_control(
			'_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'head_typography',
				'selector' => '{{WRAPPER}} .skt-table .skt-table__head-column-cell-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'head_text_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-table .skt-table__head-column-cell-wrap' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'_heading_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon/Image', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-table .skt-table__head-column-cell-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'head_icon',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-table .skt-table__head-column-cell-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-table .skt-table__head-column-cell-icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'column_image_border_radius',
			[
				'label' => __( 'Image Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-table .skt-table__head-column-cell-icon img' => 'border-radius: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'head_icon_color',
			[
				'label' => __( 'Icon Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-table__head-column-cell-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'column_color_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 'If you\'ve added <strong>Custom Style</strong> then Icon Color will be over written for that cell.',
			]
		);

		$this->end_controls_section();
	}

	protected function __table_row_style_controls() {

		$this->start_controls_section(
			'_section_table_row_style',
			[
				'label' => __( 'Table Row', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_row_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'row_border',
				'selector' => '{{WRAPPER}} .skt-table__body .skt-table__body-row-cell',
			]
		);

		$this->start_controls_tabs( '_tabs_rows' );
		$this->start_controls_tab(
			'_tab_head_row',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' )
			]
		);

		$this->add_responsive_control(
			'row_background_color_even',
			[
				'label' => __( 'Background Color (Even)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even)' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even)' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(even) .skt-table__body-row-cell-wrap' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even)' => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_background_color_odd',
			[
				'label' => __( 'Background Color (Odd)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd)' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd)' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(odd) .skt-table__body-row-cell-wrap' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd)' => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_color_even',
			[
				'label' => __( 'Color (Even)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even) .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even) .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(even) .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_responsive_control(
			'row_color_odd',
			[
				'label' => __( 'Color (Odd)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd) .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd) .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(odd) .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'row_text_link_color',
			[
				'label' => __( 'Link Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-table__body .skt-table__body-row-cell-text a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_row',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' )
			]
		);

		$this->add_responsive_control(
			'row_hover_background_color_even',
			[
				'label' => __( 'Background Color (Even)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even):hover' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even):hover' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(even) .skt-table__body-row-cell-wrap:hover' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even):hover' => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_hover_background_color_odd',
			[
				'label' => __( 'Background Color (Odd)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd):hover' => 'background-color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd):hover' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(odd) .skt-table__body-row-cell-wrap:hover' => 'background-color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd):hover' => 'background-color: transparent',
				],
			]
		);

		$this->add_responsive_control(
			'row_hover_color_even',
			[
				'label' => __( 'Color (Even)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even):hover .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(even):hover .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(even):hover .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_responsive_control(
			'row_hover_color_odd',
			[
				'label' => __( 'Color (Odd)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd):hover .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(tablet){{WRAPPER}} .skt-table__body .skt-table__body-row:nth-child(odd):hover .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}',
					'(mobile){{WRAPPER}} .skt-table__body .skt-table__body-row-cell:nth-child(odd):hover .skt-table__body-row-cell-wrap' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'row_text_link_hover_color',
			[
				'label' => __( 'Link Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-table__body .skt-table__body-row-cell-text a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'_row_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'row_text_typography',
				'selector' => '{{WRAPPER}} .skt-table__body .skt-table__body-row-cell-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'_row_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon/Image', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'row_icon_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-table__body .skt-table__body-row-cell-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_icon_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-table__body-row-cell-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-table__body-row-cell-icon img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-table__body-row-cell-icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'row_image_border_radius',
			[
				'label' => __( 'Image Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-table__body .skt-table__body-row-cell-icon img' => 'border-radius: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'row_icon_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-table__body-row-cell-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_style_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'raw' => 'If you\'ve added <strong>Custom Style</strong> then Background Color, Color, Icon Size, Icon Color will be over written for that cell.',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$table_row  = [];
		$table_cell = [];

		foreach ( $settings['rows_data'] as $row ) {
			$row_id = uniqid();

			if ( $row['row_column_type'] == 'row' ) {
				$table_row[] = [
					'id' => $row_id,
					'type' => $row['row_column_type'],
				];
			}

			if ( $row['row_column_type'] == 'column' ) {
				$table_row_keys = array_keys( $table_row );
				$cell_key = end($table_row_keys );

				$table_cell[] = [
					'repeater_id'        => $row['_id'],
					'row_id'             => isset($table_row[$cell_key]['id'])? $table_row[$cell_key]['id']: '',
					'title'              => $row['cell_name'],
					'row_span'           => $row['row_span'],
					'row_column_span'    => $row['row_column_span'],
					'row_icon'           => ! empty( $row['row_icon'] ) ? $row['row_icon'] : '',
					'row_icons'          => ! empty( $row['row_icons']['value'] ) ? $row['row_icons'] : '',
					'row_icon_show'      => ! empty( $row['row_icon_show'] ) ? $row['row_icon_show'] : '',
					'row_image'          => array_key_exists( 'row_image', $row ) ? $row['row_image'] : '',
					'row_thumbnail_size' => !empty( $row['row_thumbnail_size'] ) ? $row['row_thumbnail_size'] : '',
					'cell_link'          => !empty( $row['cell_link']['url'] ) ? $row['cell_link'] : '',
				];
			}
		}
		?>

		<table class="skt-table">
			<thead class="skt-table__head">
				<tr class="skt-table__head-column">
					<?php foreach ( $settings['columns_data'] as $index => $column_cell ) :
						$column_repeater_key = $this->get_repeater_setting_key( 'column_span', 'columns_data', $index );

						$this->add_render_attribute( $column_repeater_key, 'class', 'skt-table__head-column-cell' );
						$this->add_render_attribute( $column_repeater_key, 'class', 'elementor-repeater-item-' . $column_cell['_id'] );

						if ( $column_cell['column_span'] ) {
							$this->add_render_attribute( $column_repeater_key, 'colspan', $column_cell['column_span'] );
						}
						?>
						<th <?php echo wp_kses_post($this->get_render_attribute_string( $column_repeater_key )); ?>>
							<div class="skt-table__head-column-cell-wrap">
								<div class="skt-table__head-column-cell-text"><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $column_cell['column_name'] )); ?></div>
									<?php if ( $column_cell['column_media'] == 'icon' && ! empty( $column_cell['column_icons'] ) ) : ?>
										<div class="skt-table__head-column-cell-icon">
											<?php Icons_Manager::render_icon( $column_cell['column_icons'] ); ?>
										</div>
									<?php endif; ?>

									<?php
									if ( !empty($column_cell['column_image']['url']) || !empty($column_cell['column_image']['id']) ) :
										$this->add_render_attribute( 'column_image', 'src', $column_cell['column_image']['url'] );
										$this->add_render_attribute( 'column_image', 'alt', Control_Media::get_image_alt( $column_cell['column_image'] ) );
										$this->add_render_attribute( 'column_image', 'title', Control_Media::get_image_title( $column_cell['column_image'] ) );
										?>
										<div class="skt-table__head-column-cell-icon">
											<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $column_cell, 'column_thumbnail', 'column_image' )); ?>
										</div>
									<?php endif; ?>
							</div>
						</th>
					<?php endforeach; ?>
				</tr>
			</thead>

			<tbody class="skt-table__body">
				<?php for ( $i = 0; $i < count( $table_row ); $i++ ) : ?>
					<tr class="skt-table__body-row">
						<?php
						for ( $j = 0; $j < count( $table_cell ); $j++ ) :
							if( $table_row[$i]['id'] == $table_cell[$j]['row_id'] ) :
								$row_span_repeater_key = $this->get_repeater_setting_key( 'row_span', 'rows_data', $table_cell[$j]['row_id'].$i.$j );
								$this->add_render_attribute( $row_span_repeater_key, 'class', 'skt-table__body-row-cell' );
								$this->add_render_attribute( $row_span_repeater_key, 'class', 'elementor-repeater-item-' . $table_cell[$j]['repeater_id'] );
								if ( ! empty( $table_cell[$j]['row_column_span'] ) ) {
									$this->add_render_attribute( $row_span_repeater_key, 'colspan', $table_cell[$j]['row_column_span'] );
								}
								if ( ! empty( $table_cell[$j]['row_span'] ) ) {
									$this->add_render_attribute( $row_span_repeater_key, 'rowspan', $table_cell[$j]['row_span'] );
								}

								 // link
								 if ( ! empty( $table_cell[$j]['cell_link']['url'] ) ) {
									$row_link_key = $this->get_repeater_setting_key( 'cell_link', 'rows_data', $table_cell[$j]['row_id'].$i.$j );
									$this->add_link_attributes( $row_link_key, $table_cell[$j]['cell_link'] );
								}
							?>
								<td <?php echo wp_kses_post($this->get_render_attribute_string( $row_span_repeater_key )); ?>>
									<div class="skt-table__body-row-cell-wrap">
										<div class="skt-table__body-row-cell-text">
											<?php if ( ! empty( $table_cell[$j]['cell_link']['url'] ) ) : ?>
												<a <?php $this->print_render_attribute_string( $row_link_key ); ?>>
												 	<?php echo wp_kses_post(skt_addons_elementor_kses_basic( $table_cell[$j]['title'] ));  ?>
												</a>
											<?php else:
												echo wp_kses_post(skt_addons_elementor_kses_basic( $table_cell[$j]['title'] ));
											endif;
											?>
										</div>

										<?php if ( ! empty( $table_cell[$j]['row_icons'] ) ) : ?>
											<div class="skt-table__body-row-cell-icon">
												<?php Icons_Manager::render_icon( $table_cell[$j]['row_icons'] ); ?>
											</div>
										<?php endif; ?>

										<?php
										if ( !empty($table_cell[$j]['row_image']['url']) || !empty($table_cell[$j]['row_image']['id']) ) :
											$image = wp_get_attachment_image_url( $table_cell[$j]['row_image']['id'], $table_cell[$j]['row_thumbnail_size'] );
											if ( ! $image ) {
												$image = $table_cell[$j]['row_image']['url'];
											}
											?>
											<div class="skt-table__body-row-cell-icon">
												<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $table_cell[$j]['title'] ); ?>">
											</div>
										<?php endif; ?>
									</div>
								</td>
							<?php
							endif;
						endfor;
						?>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>

		<?php
	}
}