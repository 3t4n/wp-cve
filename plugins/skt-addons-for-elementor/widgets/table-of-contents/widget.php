<?php
/**
 * Table of Contents
 *
 * @package Skt_Addons
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Control_Media;

defined( 'ABSPATH' ) || die();

class Table_Of_Contents extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Table of Contents', 'skt-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-list-2';
	}

	public function get_keywords() {
		return ['table of content', 'table', 'toc'];
	}

	/**
	 * Register widget content controls
	 */
	protected function register_content_controls() {
		$this->__content_controls();
		$this->__settings_content_controls();
		$this->toc_sticky_controls();
	}

	protected function __content_controls() {

		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Table of Contents', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'widget_title',
			[
				'label'              => __( 'Title', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'Table of Contents', 'skt-addons-elementor' ),
				'placeholder'        => __( 'Type your title here', 'skt-addons-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label'              => esc_html__( 'HTML Tag', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				],
				'default'            => 'h4',
				'frontend_available' => true,
			]
		);

		$this->start_controls_tabs( 'include_exclude_tags', [ 'separator' => 'before' ] );

		$this->start_controls_tab(
			'include',
			[
				'label' => esc_html__( 'Include', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'headings_by_tags',
			[
				'label'              => esc_html__( 'Anchors By Tags', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'default'            => [ 'h2', 'h3', 'h4', 'h5', 'h6' ],
				'options'            => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'label_block'        => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'container',
			[
				'label'              => esc_html__( 'Container', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'label_block'        => true,
				'description'        => __( 'With this control you can use only a specific container’s heading element with Table of Content </br> Example: .toc, .toc-extra', 'skt-addons-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // include

		$this->start_controls_tab(
			'exclude',
			[
				'label' => esc_html__( 'Exclude', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'exclude_headings_by_selector',
			[
				'label'              => esc_html__( 'Anchors By Selector', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'description'        => esc_html__( 'CSS selectors, in a comma-separated list', 'skt-addons-elementor' ),
				'default'            => [],
				'label_block'        => true,
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // exclude

		$this->end_controls_tabs(); // include_exclude_tags

		$this->add_control(
			'custom_style',
			[
				'label'              => esc_html__( 'Custom Style', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'frontend_available' => true,
				'render_type'        => 'template',
				'separator'          => 'before',
			]
		);

		$this->add_control(
			'custom_style_list',
			[
				'label'              => esc_html__( 'Select Style', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'skt-toc-slide-style',
				'options'            => [
					'skt-toc-slide-style'    => __( 'Slide', 'skt-addons-elementor' ),
					'skt-toc-timeline-style' => __( 'Timeline', 'skt-addons-elementor' ),
					'skt-toc-list-style'     => __( 'List', 'skt-addons-elementor' ),
				],
				'condition'          => [
					'custom_style' => 'yes',
				],
				'frontend_available' => true,
				'render_type'        => 'template',
			]
		);

		$this->add_control(
			'marker_view',
			[
				'label'              => esc_html__( 'Marker View', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'numbers',
				'options'            => [
					'numbers' => esc_html__( 'Numbers', 'skt-addons-elementor' ),
					'bullets' => esc_html__( 'Bullets', 'skt-addons-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'custom_style' => '',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'                  => esc_html__( 'Icon', 'skt-addons-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'default'                => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'recommended'            => [
					'fa-solid'   => [
						'circle',
						'dot-circle',
						'square-full',
					],
					'fa-regular' => [
						'circle',
						'dot-circle',
						'square-full',
					],
				],
				'condition'              => [
					'marker_view'  => 'bullets',
					'custom_style' => '',
				],
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'frontend_available'     => true,
			]
		);

		$this->add_control(
			'hierarchical_view',
			[
				'label'              => esc_html__( 'Hierarchical View', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'condition'          => [
					'custom_style' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'collapse_subitems',
			[
				'label'              => __( 'Collapse Subitems', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'description'        => __( 'The "Collapse" option will not work unless you make the Table of Contents sticky.', 'skt-addons-elementor' ),
				'condition'          => [
					'hierarchical_view' => 'yes',
					'custom_style'      => '',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

		$this->start_controls_section(
			'_section_toc_settings',
			[
				'label' => __( 'Settings', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'word_wrap',
			[
				'label'              => __( 'Word Wrap', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'ellipsis',
				'prefix_class'       => 'skt-toc--content-',
			]
		);

		$this->add_control(
			'minimize_box',
			[
				'label'              => __( 'Minimize Box', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'condition'          => [
					'custom_style!' => 'yes',
				],
			]
		);

		$this->add_control(
			'expand_icon',
			[
				'label'       => esc_html__( 'Expand Icon', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid'   => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'label_block' => false,
				'skin'        => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'condition'   => [
					'minimize_box'  => 'yes',
					'custom_style!' => 'yes',
				],
			]
		);

		$this->add_control(
			'collapse_icon',
			[
				'label'       => esc_html__( 'Collapse Icon', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid'   => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin'        => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'label_block' => false,
				'condition'   => [
					'minimize_box'  => 'yes',
					'custom_style!' => 'yes',
				],
			]
		);

		// TODO: For Pro 3.6.0, convert this to the breakpoints utility method introduced in core 3.5.0.
		$breakpoints = skt_addons_elementor()->breakpoints->get_active_breakpoints();

		$minimized_on_options = [];

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			// This feature is meant for mobile screens.
			if ( 'widescreen' === $breakpoint_key ) {
				continue;
			}

			$minimized_on_options[ $breakpoint_key ] = sprintf(
				/* translators: 1: `<` character, 2: Breakpoint value. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'skt-addons-elementor' ),
				$breakpoint->get_label(),
				'<',
				$breakpoint->get_value()
			);
		}

		$minimized_on_options['desktop'] = esc_html__( 'Desktop (or smaller)', 'skt-addons-elementor' );

		$this->add_control(
			'minimized_on',
			[
				'label'              => esc_html__( 'Minimized On', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'tablet',
				'options'            => $minimized_on_options,
				'prefix_class'       => 'skt-toc--minimized-on-',
				'condition'          => [
					'minimize_box!' => '',
					'custom_style!' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'scroll_offset',
			[
				'label'              => __( 'Scroll Offset', 'skt-addons-elementor' ),
				'description'        => __( 'Minimum Value 0 is required for it to function.', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'            => [
					'unit' => 'px',
					'size' => 0,
				],
				'frontend_available' => true,
				'responsive'         => true,
			]
		);

		$this->end_controls_section();
	}

	protected function toc_sticky_controls() {
		$this->start_controls_section(
			'_section_toc_sticky',
			[
				'label' => __( 'Sticky', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'sticky_toc_toggle',
			[
				'label'              => __( 'Sticky', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'sticky_toc_disable_on',
			[
				'label'              => esc_html__( 'Sticky On', 'skt-addons-elementor' ),
				'label_block'        => true,
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'options'            => [
					'desktop' => esc_html__( 'Desktop', 'skt-addons-elementor' ),
					'tablet'  => esc_html__( 'Table', 'skt-addons-elementor' ),
					'mobile'  => esc_html__( 'Mobile', 'skt-addons-elementor' ),
				],
				'default'            => [ 'desktop', 'tablet', 'mobile' ],
				'frontend_available' => true,
				'condition'          => [
					'sticky_toc_toggle' => 'yes',
				],
			]
		);

		$this->add_control(
			'sticky_toc_type',
			[
				'label'              => __( 'Sticky Type', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'in-place',
				'options'            => [
					'in-place'        => __( 'Sticky In Place', 'skt-addons-elementor' ),
					'custom-position' => __( 'Custom Position', 'skt-addons-elementor' ),
				],
				'prefix_class'       => 'sticky-',
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => [
					'sticky_toc_toggle' => 'yes',
				],
			]
		);

		$this->add_control(
			'toc_horizontal_align',
			[
				'label'              => __( 'Horizontal Align', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'skt-toc-left'  => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'skt-toc-right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'            => 'skt-toc-right',
				'toggle'             => false,
				'frontend_available' => true,
				'condition'          => [
					'sticky_toc_type'   => 'custom-position',
					'sticky_toc_toggle' => 'yes',
				],
			]
		);

		$this->add_control(
			'toc_vertical_align',
			[
				'label'              => __( 'Vertical Align', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'skt-toc-position-top'    => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'skt-toc-position-middle' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'skt-toc-position-bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'            => 'skt-toc-position-middle',
				'toggle'             => false,
				'frontend_available' => true,
				'condition'          => [
					'sticky_toc_type'   => 'custom-position',
					'sticky_toc_toggle' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_toc_position_left',
			[
				'label'      => __( 'Left', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-left' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toc_horizontal_align' => 'skt-toc-left',
					'sticky_toc_type'      => 'custom-position',
					'sticky_toc_toggle'    => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_toc_position_left_top',
			[
				'label'      => __( 'Top', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-left.skt-toc-position-top' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toc_horizontal_align' => 'skt-toc-left',
					'toc_vertical_align'   => 'skt-toc-position-top',
					'sticky_toc_type'      => 'custom-position',
					'sticky_toc_toggle'    => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'sticky_toc_position_left_bottom',
			[
				'label'      => __( 'Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-left.skt-toc-position-bottom' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toc_horizontal_align' => 'skt-toc-left',
					'toc_vertical_align'   => 'skt-toc-position-bottom',
					'sticky_toc_type'      => 'custom-position',
					'sticky_toc_toggle'    => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'sticky_toc_position_right',
			[
				'label'      => __( 'Right', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-right' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'sticky_toc_type'      => 'custom-position',
					'toc_horizontal_align' => 'skt-toc-right',
					'sticky_toc_toggle'    => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_toc_position_right_top',
			[
				'label'      => __( 'Top', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-right.skt-toc-position-top' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toc_horizontal_align' => 'skt-toc-right',
					'sticky_toc_type'      => 'custom-position',
					'toc_vertical_align'   => 'skt-toc-position-top',
					'sticky_toc_toggle'    => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_toc_position_right_bottom',
			[
				'label'      => __( 'Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-right.skt-toc-position-bottom' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'toc_horizontal_align' => 'skt-toc-right',
					'sticky_toc_type'      => 'custom-position',
					'toc_vertical_align'   => 'skt-toc-position-bottom',
					'sticky_toc_toggle'    => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_toc_top_offset',
			[
				'label'      => __( 'Offset', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'condition'  => [
					'sticky_toc_type'   => 'in-place',
					'sticky_toc_toggle' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}}.sticky-in-place .skt-toc-wrapper.floating-toc' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_toc_z_index',
			[
				'label'      => __( 'Z-Index', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 999,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-wrapper.floating-toc' => 'z-index: {{SIZE}}',
				],
				'condition'  => [
					'sticky_toc_toggle' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register widget style controls
	 */
	protected function register_style_controls() {
		$this->toc_box_style_controls();
		$this->toc_box_style_header_controls();
		$this->toc_box_style_list_item();
		$this->toc_slide_item();
		$this->toc_timeline_item();
		$this->toc_list_item();
	}

	protected function toc_box_style_controls() {
		$this->start_controls_section(
			'section_box_style',
			[
				'label'     => esc_html__( 'Box', 'skt-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'wrapper_bg',
				'label'    => esc_html__( 'Background Color', 'skt-addons-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'video', 'image'],
				'selector' => '{{WRAPPER}} .skt-toc-wrapper',
			]
		);

		$this->add_control(
			'loader_color',
			[
				'label'     => esc_html__( 'Loader Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					// Not using CSS var for BC, when not configured: the loader should get the color from the body tag.
					'{{WRAPPER}} .skt-toc__spinner' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     => esc_html__( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-wrapper' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label'     => esc_html__( 'Border Width', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-wrapper' => 'border-width: {{SIZE}}{{UNIT}}; border-style:solid',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-wrapper' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label'              => esc_html__( 'Min Height', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', 'vh' ],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .skt-toc-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
				'condition' => [
					'custom_style!' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .skt-toc-wrapper',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'     => esc_html__( 'Padding', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .skt-toc__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-slide-style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-timeline-style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-toc-wrapper.skt-toc-list-style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // box_style
	}

	protected function toc_box_style_header_controls() {
		$this->start_controls_section(
			'section_box_style_header',
			[
				'label'     => esc_html__( 'Header', 'skt-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_style!' => 'yes',
				],
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--header-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--header-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .skt-toc__header, {{WRAPPER}} .skt-toc__header-title',
			]
		);

		$this->add_control(
			'toggle_button_color',
			[
				'label'     => esc_html__( 'Icon Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'minimize_box' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_separator_width',
			[
				'label'     => esc_html__( 'Separator Width', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--separator-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'header_sepa_color',
			[
				'label'     => esc_html__( 'Separator Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--separator-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function toc_box_style_list_item() {
		$this->start_controls_section(
			'section_box_style_list_item_style',
			[
				'label'     => esc_html__( 'List Item', 'skt-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_style!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'list_padding',
			[
				'label'     => esc_html__( 'Padding', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'   => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--list-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'max_height',
			[
				'label'      => esc_html__( 'Max Height', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--toc-body-max-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_typography',
				'selector' => '{{WRAPPER}} .skt-toc__list-item',
			]
		);

		$this->add_control(
			'list_indent',
			[
				'label'      => esc_html__( 'Indent', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--nested-list-indent: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'hierarchical_view' => 'yes',
					'custom_style'      => '',
				],
			]
		);

		$this->start_controls_tabs( 'item_text_style' );

		$this->start_controls_tab(
			'normal',
			[
				'label' => esc_html__( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'item_text_color_normal',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_normal',
			[
				'label'     => esc_html__( 'Underline', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // normal

		$this->start_controls_tab(
			'hover',
			[
				'label' => esc_html__( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'item_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'    => '#E04D8B',
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_hover',
			[
				'label'     => esc_html__( 'Underline', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // hover
		$this->end_controls_tabs(); // item_text_style

		$this->add_control(
			'heading_marker',
			[
				'label'     => esc_html__( 'Marker', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label'     => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--marker-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'marker_size',
			[
				'label'      => esc_html__( 'Size', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--marker-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function toc_slide_item() {
		$this->start_controls_section(
			'section_toc_slide_item',
			[
				'label'     => esc_html__( 'Slide', 'skt-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_style'      => 'yes',
					'custom_style_list' => 'skt-toc-slide-style',
				],
			]
		);

		$this->add_control(
			'toc_slide_title',
			[
				'label' => __( 'Heading', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'toc_slide_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toc_slide_title_bar_color',
			[
				'label'     => esc_html__( 'Bar Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc .skt-toc-title:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toc_slide_title_typography',
				'selector' => '{{WRAPPER}} .skt-toc-title',
			]
		);
		$this->add_responsive_control(
			'toc_slide_space_bottom',
			[
				'label'      => __( 'Space Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'toc_slide_item',
			[
				'label'     => __( 'List Item', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toc_slide_item_typography',
				'selector' => '{{WRAPPER}} .skt-toc .skt-toc-entry a',
			]
		);

		$this->add_responsive_control(
			'toc_slide_lsit_space_bottom',
			[
				'label'      => __( 'List Space Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-entry' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'toc_slide_bar_style' );

		$this->start_controls_tab(
			'toc_slide_bar_normal',
			[
				'label' => esc_html__( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'toc_slide_text_normal_color',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc .skt-toc-entry a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc .skt-toc-entry a:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toc_slide_bar_normal_color',
			[
				'label'     => esc_html__( 'Bar Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc .skt-toc-entry a:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toc_slide_bar_hover',
			[
				'label' => esc_html__( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'toc_slide_text_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'    => '#E04D8B',
				'selectors' => [
					'{{WRAPPER}} .skt-toc .skt-toc-entry a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc .skt-toc-entry a.skt-toc-item-active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toc_slide_bar_hover_color',
			[
				'label'     => esc_html__( 'Bar Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc .skt-toc-entry a.skt-toc-item-active:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function toc_timeline_item() {
		$this->start_controls_section(
			'section_toc_timeline_item',
			[
				'label'     => __( 'Timeline', 'skt-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_style'      => 'yes',
					'custom_style_list' => 'skt-toc-timeline-style',
				],
			]
		);

		$this->add_control(
			'tml_toc_title',
			[
				'label' => __( 'Heading', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'tml_toc_title_color',
			[
				'label'     => __( 'Title Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tml_toc_title_typography',
				'selector' => '{{WRAPPER}} .skt-toc-timeline-style .skt-toc-title',
			]
		);
		$this->add_responsive_control(
			'tml_space_bottom',
			[
				'label'      => __( 'Space Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'toc_tml_items',
			[
				'label'     => __( 'List Item', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toc_tml_item_typography',
				'selector' => '{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a',
			]
		);

		$this->add_responsive_control(
			'toc_tml_item_space_bottom',
			[
				'label'      => __( 'List Space Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc-entry' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'toc_tml_item_tabs' );

		$this->start_controls_tab(
			'toc_tml_item_normal',
			[
				'label' => esc_html__( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'toc_tml_item_normal_color',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a::before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toc_tml_item_hover',
			[
				'label' => esc_html__( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'toc_tml_item_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'    => '#E04D8B',
				'selectors' => [
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a.skt-toc-item-active' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a:hover::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a.skt-toc-item-active::before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toc_tml_dots',
			[
				'label'     => __( 'Dots', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toc_tml_dots_size',
			[
				'label'     => esc_html__( 'Size', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-timeline-style' => '--skt-toc-timeline-dot-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toc_tml_dots_box_shadow',
				'selector' => '{{WRAPPER}} .skt-toc-timeline-style .skt-toc .skt-toc-entry a::before',
			]
		);

		$this->add_control(
			'toc_tml_tree_color',
			[
				'label'     => esc_html__( 'Tree Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-timeline-style .skt-toc-items-inner:before' => 'border-left-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function toc_list_item() {
		$this->start_controls_section(
			'section_toc_list_item',
			[
				'label'     => __( 'List', 'skt-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_style'      => 'yes',
					'custom_style_list' => 'skt-toc-list-style',
				],
			]
		);

		$this->add_control(
			'toc_list_title',
			[
				'label' => __( 'Heading', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'toc_list_title_color',
			[
				'label'     => __( 'Title Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-list-style .skt-toc-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toc_list_title_typography',
				'selector' => '{{WRAPPER}} .skt-toc-list-style .skt-toc-title',
			]
		);
		$this->add_responsive_control(
			'toc_list_title_space_bottom',
			[
				'label'      => __( 'Space Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-list-style .skt-toc-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'toc_list_items',
			[
				'label'     => __( 'List Item', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toc_list_item_typography',
				'selector' => '{{WRAPPER}} .skt-toc-list-style .skt-toc .skt-toc-entry a',
			]
		);

		$this->add_responsive_control(
			'toc_list_item_space_bottom',
			[
				'label'      => __( 'List Space Bottom', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toc-list-style .skt-toc-entry' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'toc_list_item_tabs' );

		$this->start_controls_tab(
			'toc_list_item_normal',
			[
				'label' => esc_html__( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'toc_list_item_normal_color',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-list-style .skt-toc .skt-toc-entry a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toc_list_item_bar_normal_color',
			[
				'label'     => esc_html__( 'Bar Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toc-list-style .skt-toc .skt-toc-entry a::before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toc_list_item_hover',
			[
				'label' => esc_html__( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'toc_list_item_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'    => '#E04D8B',
				'selectors' => [
					'{{WRAPPER}} .skt-toc-list-style .skt-toc .skt-toc-entry a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc-list-style .skt-toc .skt-toc-entry a.skt-toc-item-active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toc_list_item_bar_hover_color',
			[
				'label'     => esc_html__( 'Bar Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'    => '#E04D8B',
				'selectors' => [
					'{{WRAPPER}} .skt-toc-list-style .skt-toc .skt-toc-entry a:hover::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .skt-toc-list-style .skt-toc .skt-toc-entry a.skt-toc-item-active::before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$html_tag = Utils::validate_html_tag( $settings['html_tag'] );

		$this->add_render_attribute( 'body', 'class', 'skt-toc__body' );
		$this->add_render_attribute( 'wrapper', 'class', 'skt-toc-wrapper' );

		if ( 'yes' == $settings['custom_style'] ) {
			$this->add_render_attribute( 'wrapper', 'class', [$settings['custom_style_list']] );
		} else {
			$this->add_render_attribute( 'wrapper', 'class', ['skt-toc-default-style'] );
		}

		if ( $settings['collapse_subitems'] ) {
			$this->add_render_attribute( 'body', 'class', 'skt-toc__list-items--collapsible' );
		}

		if ( 'yes' === $settings['sticky_toc_toggle'] && 'custom-position' === $settings['sticky_toc_type'] ) {
			$this->add_render_attribute(
				'wrapper',
				'class',
				[
					'sticky_position_fixed',
					$settings['toc_horizontal_align'],
					$settings['toc_vertical_align'],
				]
			);
		}
		if ( skt_addons_elementor()->editor->is_edit_mode() && 'yes' === $settings['sticky_toc_toggle'] && 'custom-position' === $settings['sticky_toc_type'] ) :
			?>
			<div class="skt-toc-editor-placeholder">
				<div class="skt-toc-editor-placeholder-content">
					<?php esc_html_e( 'This is a placeholder text which won\'t be displayed in the preview panel or front end.', 'skt-addons-elementor' ); ?>
				</div>
			</div>
			<?php
		endif;
		?>
		<div id="<?php echo 'skt-toc-' . esc_attr( $this->get_id() ); ?>" <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
		<?php if ( 'yes' != $settings['custom_style'] ) : ?>
			<div class="skt-toc__header">
				<<?php Utils::print_validated_html_tag( $html_tag ); ?> class="skt-toc__header-title">
					<?php echo esc_html( $settings['widget_title'] ); ?>
				</<?php Utils::print_validated_html_tag( $html_tag ); ?>>

				<?php if ( 'yes' === $settings['minimize_box'] ) : ?>
				<div class="skt-toc__toggle-button skt-toc__toggle-button--expand">
					<?php Icons_Manager::render_icon( $settings['expand_icon'] ); ?>
				</div>
				<div class="skt-toc__toggle-button skt-toc__toggle-button--collapse">
					<?php Icons_Manager::render_icon( $settings['collapse_icon'] ); ?>
				</div>
				<?php endif; ?>
			</div>
			<div <?php $this->print_render_attribute_string( 'body' ); ?>>
				<div class="skt-toc__spinner-container">
					<i class="skt-toc__spinner eicon-loading eicon-animation-spin" aria-hidden="true"></i>
				</div>
				<span class="line"></span>
			</div>
			<?php endif; ?>
		</div>
		<?php

	}
}
