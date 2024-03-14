<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Table extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-table';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Table', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-table xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'data', 'table', 'comparison', 'statistics' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_table_column',
			array(
				'label' => __( 'Table Head', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( '_tabs_column' );

		$repeater->start_controls_tab(
			'_tab_column_content',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'column_name',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Column Name', 'xpro-elementor-addons' ),
				'default'     => __( 'Column', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'column_span',
			array(
				'label' => __( 'Col Span', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 0,
				'max'   => 50,
				'step'  => 1,
			)
		);

		$repeater->add_control(
			'column_media',
			array(
				'label'       => __( 'Media', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'default'     => 'none',
				'options'     => array(
					'none'  => array(
						'title' => __( 'None', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ban',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-star-o',
					),
					'image' => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
				),
			)
		);

		$repeater->add_control(
			'column_icons',
			array(
				'label'       => __( 'Icon', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fab fa-wordpress',
					'library' => 'fa-brand',
				),
				'label_block' => true,
				'condition'   => array(
					'column_media' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'column_image',
			array(
				'label'     => __( 'Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'column_media' => 'image',
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'column_thumbnail',
				'default'   => 'thumbnail',
				'separator' => 'none',
				'condition' => array(
					'column_media' => 'image',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'_tabs_column_style',
			array(
				'label' => __( 'Style', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'head_custom_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-table-head-column-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'head_custom_icon_color',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'column_media' => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-table-head-column-cell-icon i' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'head_custom_bg_color',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.xpro-table-head-column-cell' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_responsive_control(
			'head_icon_size',
			array(
				'label'     => __( 'Media Size', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array(
					'column_media!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-table-head-column-cell-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-table-head-column-cell-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-table-head-column-cell-icon svg' => 'width: {{SIZE}}{{UNIT}}; height:auto;',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'columns_data',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ column_name }}}',
				'default'     => array(
					array(
						'column_name' => __( 'Sr.', 'xpro-elementor-addons' ),
					),
					array(
						'column_name' => __( 'First', 'xpro-elementor-addons' ),
					),
					array(
						'column_name' => __( 'Last', 'xpro-elementor-addons' ),
					),
					array(
						'column_name' => __( 'Handle', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->add_responsive_control(
			'head_align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-head-column-cell' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		//Table Body
		$this->start_controls_section(
			'section_table_row',
			array(
				'label' => __( 'Table Row', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'row_column_type',
			array(
				'label'   => __( 'Row/Column', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'row',
				'options' => array(
					'row'    => __( 'Row', 'xpro-elementor-addons' ),
					'column' => __( 'Column', 'xpro-elementor-addons' ),
				),
			)
		);

		$repeater->start_controls_tabs( 'tabs_row' );

		$repeater->start_controls_tab(
			'tab_row_content',
			array(
				'label'     => __( 'Content', 'xpro-elementor-addons' ),
				'condition' => array(
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->add_control(
			'cell_name',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Cell Name', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->add_control(
			'cell_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->add_control(
			'row_column_span',
			array(
				'label'     => __( 'Col Span', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 50,
				'step'      => 1,
				'condition' => array(
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->add_control(
			'row_span',
			array(
				'label'     => __( 'Row Span', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 50,
				'step'      => 1,
				'condition' => array(
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->add_control(
			'row_media',
			array(
				'label'       => __( 'Media', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'default'     => 'none',
				'condition'   => array(
					'row_column_type' => 'column',
				),
				'options'     => array(
					'none'  => array(
						'title' => __( 'None', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ban',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-star-o',
					),
					'image' => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
				),
			)
		);

		$repeater->add_control(
			'row_icons',
			array(
				'label'       => __( 'Icon', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => array(
					'value'   => 'fab fa-wordpress',
					'library' => 'fa-brand',
				),
				'condition'   => array(
					'row_media'       => 'icon',
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->add_control(
			'row_image',
			array(
				'label'     => __( 'Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'row_media'       => 'image',
					'row_column_type' => 'column',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'row_thumbnail',
				'default'   => 'thumbnail',
				'separator' => 'none',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					'row_media'       => 'image',
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tabs_row_style',
			array(
				'label'     => __( 'Style', 'xpro-elementor-addons' ),
				'condition' => array(
					'row_column_type' => 'column',
				),
			)
		);

		$repeater->add_control(
			'row_custom_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'row_column_type' => 'column',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row {{CURRENT_ITEM}}.xpro-table-body-row-cell .xpro-table-body-row-cell-content'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-table-body-row {{CURRENT_ITEM}}.xpro-table-body-row-cell .xpro-table-body-row-cell-content > a' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'row_custom_icon_color',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'row_column_type' => 'column',
					'row_media'       => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row {{CURRENT_ITEM}} .xpro-table-body-row-cell-icon i' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'row_custom_background_color',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'row_column_type' => 'column',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row {{CURRENT_ITEM}}.xpro-table-body-row-cell' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_responsive_control(
			'row_custom_icon_size',
			array(
				'label'     => __( 'Media Size', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array(
					'row_column_type' => 'column',
					'row_media!'      => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row {{CURRENT_ITEM}} .xpro-table-body-row-cell-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-body-row {{CURRENT_ITEM}} .xpro-table-body-row-cell-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-body-row {{CURRENT_ITEM}} .xpro-table-body-row-cell-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'row_starts',
			array(
				'label'     => false,
				'type'      => Controls_Manager::HIDDEN,
				'default'   => __( 'Row Starts', 'xpro-elementor-addons' ),
				'condition' => array(
					'row_column_type' => 'row',
				),
			)
		);

		$this->add_control(
			'rows_data',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '<# print( (row_column_type == "column" ) ? cell_name : ("Row Starts") ) #>',
				'default'     => array(
					array(
						'row_column_type' => 'row',
						'row_starts'      => __( 'Row Starts', 'xpro-elementor-addons' ),
					),
					array(
						'row_column_type' => 'column',
						'cell_name'       => __( '1', 'xpro-elementor-addons' ),
					),
					array(
						'row_column_type' => 'column',
						'cell_name'       => __( 'Mark', 'xpro-elementor-addons' ),
					),
					array(
						'row_column_type' => 'column',
						'cell_name'       => __( 'Otto', 'xpro-elementor-addons' ),
					),
					array(
						'row_column_type' => 'column',
						'cell_name'       => __( '@mdo', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->add_responsive_control(
			'row_align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row-cell' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
			'section_style_head',
			array(
				'label' => __( 'Table Head', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'head_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-table-head-column-cell-content',
			)
		);

		$this->add_control(
			'head_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-head-column-cell-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'head_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-table-head-column-cell',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'head_border',
				'selector' => '{{WRAPPER}} .xpro-table-head-column-cell',
			)
		);

		$this->add_responsive_control(
			'head_border_radius',
			array(
				'label'     => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-head-column-cell:first-child' => 'border-top-left-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-head-column-cell:last-child'  => 'border-top-right-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'head_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-table-head-column-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'head_media',
			array(
				'label'     => __( 'Media', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'head_icon_position',
			array(
				'label'        => __( 'Position', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
				),
				'default'      => 'right',
				'toggle'       => false,
				'prefix_class' => 'xpro-table-head-icon-',
			)
		);

		$this->add_control(
			'head_media_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-head-column-cell-icon > i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-table-head-column-cell-icon > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'head_media_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-table-head-column-cell-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-head-column-cell-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-head-column-cell-icon > img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'head_image_height',
			array(
				'label'          => __( 'Image Height', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-table-head-column-cell-icon img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'head_object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'condition' => array(
					'head_image_height[size]!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-head-column-cell-icon img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'head_media_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-table-head-column-cell-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Body Styling
		$this->start_controls_section(
			'section_style_row',
			array(
				'label' => __( 'Table Row', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'row_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-table-body-row-cell-content',
			)
		);

		$this->start_controls_tabs(
			'row_style_tabs'
		);

		$this->start_controls_tab(
			'row_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'row_color_odd',
			array(
				'label'     => __( 'Text Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(odd) .xpro-table-body-row-cell-content'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-table-body-row:nth-child(odd) .xpro-table-body-row-cell-content > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_color_even',
			array(
				'label'     => __( 'Text Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(even) .xpro-table-body-row-cell-content'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-table-body-row:nth-child(even) .xpro-table-body-row-cell-content > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_bg_odd',
			array(
				'label'     => __( 'Background Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(odd)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_bg_even',
			array(
				'label'     => __( 'Background Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(even)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'row_color_odd_hover',
			array(
				'label'     => __( 'Text Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(odd):hover .xpro-table-body-row-cell-content'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-table-body-row:nth-child(odd):hover .xpro-table-body-row-cell-content > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_color_even_hover',
			array(
				'label'     => __( 'Text Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(even):hover .xpro-table-body-row-cell-content'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-table-body-row:nth-child(even):hover .xpro-table-body-row-cell-content > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_bg_odd_hover',
			array(
				'label'     => __( 'Background Color(Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(odd):hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_bg_even_hover',
			array(
				'label'     => __( 'Background Color(Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:nth-child(even):hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'row_border',
				'selector' => '{{WRAPPER}} .xpro-table-body-row-cell,.xpro-table-responsive .xpro-table-body-row-cell-inner',
			)
		);

		$this->add_responsive_control(
			'row_border_radius',
			array(
				'label'     => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row:last-child .xpro-table-body-row-cell:first-child' => 'border-bottom-left-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-body-row:last-child .xpro-table-body-row-cell:last-child'  => 'border-bottom-right-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-table-body-row-cell'                              => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-responsive .xpro-table-body-row-cell-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'row_media',
			array(
				'label'     => __( 'Media', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'row_icon_position',
			array(
				'label'        => __( 'Position', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
				),
				'default'      => 'right',
				'toggle'       => false,
				'prefix_class' => 'xpro-table-row-icon-',
			)
		);

		$this->add_control(
			'row_media_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row-cell-icon > i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-table-body-row-cell-icon > svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_media_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-table-body-row-cell-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-body-row-cell-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-table-body-row-cell-icon > img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_image_height',
			array(
				'label'          => __( 'Image Height', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-table-body-row-cell-icon img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'condition' => array(
					'media_type'              => 'image',
					'row_image_height[size]!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-table-body-row-cell-icon img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_media_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-table-body-row-cell-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Responsive
		$this->start_controls_section(
			'section_style_responsive',
			array(
				'label' => __( 'Responsive', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'responsive_mobile',
			array(
				'label'        => __( 'Responsive On Mobile', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'responsive_space',
			array(
				'label'     => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array(
					'responsive_mobile' => 'yes',
				),
				'default'   => array(
					'size' => 10,
				),
				'selectors' => array(
					'(mobile) {{WRAPPER}} .xpro-table-responsive .xpro-table-body-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'responsive_item_boder',
				'selector' => '(mobile) {{WRAPPER}} .xpro-table-responsive .xpro-table-body-row',
			)
		);

		$this->add_control(
			'responsive_head',
			array(
				'label'     => __( 'Head', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'responsive_head_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(mobile) {{WRAPPER}} .xpro-table-responsive .xpro-table-body-row .xpro-table-head-column-cell-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_head_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(mobile) {{WRAPPER}} .xpro-table-responsive .xpro-table-body-row .xpro-table-head-column-cell' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_content',
			array(
				'label'     => __( 'Content', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'responsive_row_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(mobile) {{WRAPPER}} .xpro-table-responsive .xpro-table-body-row .xpro-table-body-row-cell-content'     => 'color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-table-responsive .xpro-table-body-row .xpro-table-body-row-cell-content > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_row_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(mobile) {{WRAPPER}} .xpro-table-responsive .xpro-table-body-row-cell-inner' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
	}


	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$table_row  = array();
		$table_cell = array();

		foreach ( $settings['rows_data'] as $row ) {
			$row_id = uniqid();

			if ( 'row' === $row['row_column_type'] ) {
				$table_row[] = array(
					'id'   => $row_id,
					'type' => $row['row_column_type'],
				);
			}

			if ( 'column' === $row['row_column_type'] ) {
				$table_row_keys = array_keys( $table_row );
				$cell_key       = end( $table_row_keys );

				$table_cell[] = array(
					'repeater_id'        => $row['_id'],
					'row_id'             => isset( $table_row[ $cell_key ]['id'] ) ? $table_row[ $cell_key ]['id'] : '',
					'title'              => $row['cell_name'],
					'row_span'           => $row['row_span'],
					'row_column_span'    => $row['row_column_span'],
					'row_icon'           => ! empty( $row['row_icon'] ) ? $row['row_icon'] : '',
					'row_icons'          => ! empty( $row['row_icons']['value'] ) ? $row['row_icons'] : '',
					'row_icon_show'      => ! empty( $row['row_icon_show'] ) ? $row['row_icon_show'] : '',
					'row_image'          => array_key_exists( 'row_image', $row ) ? $row['row_image'] : '',
					'row_thumbnail_size' => ! empty( $row['row_thumbnail_size'] ) ? $row['row_thumbnail_size'] : '',
					'cell_link'          => ! empty( $row['cell_link']['url'] ) ? $row['cell_link'] : '',
				);
			}
		}

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'table/layout/frontend.php';
	}
}
