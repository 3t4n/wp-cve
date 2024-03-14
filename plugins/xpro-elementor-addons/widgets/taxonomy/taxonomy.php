<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
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
class Taxonomy extends Widget_Base {

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
		return 'xpro-taxonomy';
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
		return __( 'Taxonomy', 'xpro-elementor-addons' );
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
		return 'xi-info-list xpro-widget-label';
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
		return array( 'taxonomy', 'category', 'icon', 'list' );
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
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'          => __( 'Layout', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'vertical',
				'options'        => array(
					'vertical'   => array(
						'title' => __( 'Vertical', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'horizontal' => array(
						'title' => __( 'Horizontal', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-apps',
					),
					'inline'     => array(
						'title' => __( 'Inline', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'style_transfer' => true,
			)
		);

		$this->add_control(
			'taxonomy_type',
			array(
				'label'       => __( 'Source', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $this->get_taxonomies(),
				'default'     => key( $this->get_taxonomies() ),
			)
		);

		$this->add_control(
			'exclude',
			array(
				'label'       => __( 'Exclude', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->taxonomies_exclude(),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => xpro_elementor_get_post_orderby_options(),
				'default' => 'date',

			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'asc'  => 'Ascending',
					'desc' => 'Descending',
				),
				'default' => 'desc',

			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => __( 'Hide Empty', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => __( 'Show Count', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'far fa-circle',
					'library' => 'regular',
				),
			)
		);

		$this->add_control(
			'show_custom',
			array(
				'label'        => __( 'Custom Field', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'custom_text',
			array(
				'label'       => __( 'Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'All', 'xpro-elementor-addons' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_custom' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_count',
			array(
				'label'       => __( 'Count', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'input_type'  => 'number',
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_count'  => 'yes',
					'show_custom' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_link',
			array(
				'label'     => __( 'Link', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'show_custom' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'list_align',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'       => false,
				'default'      => 'left',
				'prefix_class' => 'elementor%s-align-',
				'selectors'    => array(
					'{{WRAPPER}} .xpro-taxonomy-layout-inline .xpro-taxonomy-list' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-taxonomy-list .xpro-taxonomy-list-content',
			)
		);

		$this->add_responsive_control(
			'list_item_per_row',
			array(
				'label'          => __( 'Item Per Row', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min'  => 2,
						'max'  => 6,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 3,
				),
				'tablet_default' => array(
					'size' => 2,
				),
				'mobile_default' => array(
					'size' => 1,
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-taxonomy-layout-horizontal .xpro-taxonomy-list' => 'grid-template-columns: repeat({{SIZE}},1fr);',
				),
				'condition'      => array(
					'layout' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_space',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 15,
				),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-wrapper .xpro-taxonomy-list' => 'grid-gap:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-sub-taxonomy-list > li:first-child > a' => 'margin-top:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'list_item_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-taxonomy-list > li > a',
			)
		);

		$this->start_controls_tabs( 'list_item_style_tabs' );

		$this->start_controls_tab(
			'list_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'list_item_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list .xpro-taxonomy-list-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_item_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li > a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'list_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'list_item_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li:hover .xpro-taxonomy-list-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_item_bg_hover_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li:hover > a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_item_border_hcolor',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li:hover > a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'list_active_tab_style',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'list_item_active_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy .xpro-taxonomy-list-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_item_bg_active_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy > a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_item_border_active_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy > a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'list_item_border',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-taxonomy-list > li > a',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'list_item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_sub_item_padding',
			array(
				'label'      => __( 'Sub Category Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-list.xpro-sub-taxonomy-list > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'hierarchical' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'        => __( 'Icon', 'xpro-elementor-addons' ),
				'tab'          => Controls_Manager::TAB_STYLE,
				'icon[value]!' => '',
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-media' => 'font-size: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-taxonomy-media > svg' => 'width: {{SIZE}}{{UNIT}}; height:auto;',
				),
				'default'    => array(
					'size' => 12,
				),
			)
		);

		$this->add_responsive_control(
			'icon_bgsize',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-media' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'size' => 25,
				),
			)
		);

		$this->add_responsive_control(
			'icon_space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li > a' => 'grid-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-align-center .xpro-taxonomy-list-content' => 'grid-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'icon_border',
				'selector' => '{{WRAPPER}} .xpro-taxonomy-media',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-taxonomy-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'icon_tab' );

		$this->start_controls_tab(
			'icon_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-media i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-taxonomy-media svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_background_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-media' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li:hover .xpro-taxonomy-media i, {{WRAPPER}} .xpro-elementor-button-primary:focus .xpro-elementor-button-media i'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-taxonomy-list > li:hover .xpro-taxonomy-media svg, {{WRAPPER}} .xpro-elementor-button-primary:focus .xpro-elementor-button-media svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li:hover .xpro-taxonomy-media' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_border_hover_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li:hover .xpro-taxonomy-media, {{WRAPPER}} .xpro-taxonomy-list > li:hover .xpro-taxonomy-media' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li .xpro-taxonomy-media' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_active',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_active_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy .xpro-taxonomy-media i, {{WRAPPER}} .xpro-elementor-button-primary:focus .xpro-elementor-button-media i'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy .xpro-taxonomy-media svg, {{WRAPPER}} .xpro-elementor-button-primary:focus .xpro-elementor-button-media svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_active_background_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy .xpro-taxonomy-media' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_border_active_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy .xpro-taxonomy-media, {{WRAPPER}} .xpro-taxonomy-list > li.current-taxonomy .xpro-taxonomy-media' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Get a list of Taxonomy
	 *
	 * @return array
	 */
	public static function get_taxonomies( $taxonomy_type = '' ) {
		$list = array();
		if ( $taxonomy_type ) {
			$tax                    = xpro_elementor_get_taxonomies(
				array(
					'public'      => true,
					'object_type' => array( $taxonomy_type ),
				),
				'object',
				true
			);
			$list[ $taxonomy_type ] = count( $tax ) !== 0 ? $tax : '';
		} else {
			$list = xpro_elementor_get_taxonomies( array( 'public' => true ), 'object', true );
		}

		return $list;
	}

	public static function taxonomies_exclude() {

		$list = array();

		$terms = get_terms(
			array(
				'order'      => 'asc',
				'hide_empty' => false,
			)
		);

		foreach ( $terms as $value ) {
			$list[ $value->term_id ] = $value->name;
		}

		return $list;
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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'taxonomy/layout/frontend.php';
	}
}
