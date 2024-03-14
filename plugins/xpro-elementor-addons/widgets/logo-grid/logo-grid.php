<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
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
class Logo_Grid extends Widget_Base {

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
		return 'xpro-logo-grid';
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
		return __( 'Logo Grid', 'xpro-elementor-addons' );
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
		return 'xi-logo-grid xpro-widget-label';
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
		return array( 'xpro', 'logo', 'grid' );
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
			'_section_logo',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Logo', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Website Url', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'       => __( 'Brand Name', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Brand Name', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'logo_list',
			array(
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array(
						'name' => 'Item #1',
					),
					array(
						'name' => 'Item #2',
					),
					array(
						'name' => 'Item #3',
					),
					array(
						'name' => 'Item #4',
					),
					array(
						'name' => 'Item #5',
					),
					array(
						'name' => 'Item #6',
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'large',
				'separator' => 'before',
				'exclude'   => array(
					'custom',
				),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'          => __( 'Grid Layout', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => array(
					'box'       => __( 'Box', 'xpro-elementor-addons' ),
					'border'    => __( 'Border', 'xpro-elementor-addons' ),
					'tictactoe' => __( 'Tic Tac Toe', 'xpro-elementor-addons' ),
				),
				'default'        => 'border',
				'prefix_class'   => 'xpro-logo-grid--',
				'style_transfer' => true,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'           => __( 'Columns', 'xpro-elementor-addons' ),
				'type'            => Controls_Manager::SELECT,
				'options'         => array(
					1 => __( '1', 'xpro-elementor-addons' ),
					2 => __( '2', 'xpro-elementor-addons' ),
					3 => __( '3', 'xpro-elementor-addons' ),
					4 => __( '4', 'xpro-elementor-addons' ),
					5 => __( '5', 'xpro-elementor-addons' ),
					6 => __( '6', 'xpro-elementor-addons' ),
				),
				'desktop_default' => 3,
				'tablet_default'  => 2,
				'mobile_default'  => 2,
				'prefix_class'    => 'xpro-logo-grid--col-%s',
				'style_transfer'  => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_grid',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 500,
						'min' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-logo-grid-item' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'grid_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}}.xpro-logo-grid--tictactoe .xpro-logo-grid-wrapper, {{WRAPPER}}.xpro-logo-grid--border .xpro-logo-grid-wrapper, {{WRAPPER}}.xpro-logo-grid--box .xpro-logo-grid-item',
			)
		);

		$this->add_control(
			'grid_border_type',
			array(
				'label'     => __( 'Border Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => __( 'None', 'xpro-elementor-addons' ),
					'solid'  => __( 'Solid', 'xpro-elementor-addons' ),
					'double' => __( 'Double', 'xpro-elementor-addons' ),
					'dotted' => __( 'Dotted', 'xpro-elementor-addons' ),
					'dashed' => __( 'Dashed', 'xpro-elementor-addons' ),
					'groove' => __( 'Groove', 'xpro-elementor-addons' ),
				),
				'default'   => 'solid',
				'selectors' => array(
					'{{WRAPPER}} .xpro-logo-grid-item' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_border_width',
			array(
				'label'      => __( 'Border Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border .xpro-logo-grid-item' => 'border-right-width: {{grid_border_width.SIZE}}{{UNIT}}; border-bottom-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border .xpro-logo-grid-item'   => 'border-right-width: {{grid_border_width_tablet.SIZE}}{{UNIT}}; border-bottom-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border .xpro-logo-grid-item'   => 'border-right-width: {{grid_border_width_mobile.SIZE}}{{UNIT}}; border-bottom-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',

					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-1 .xpro-logo-grid-item:nth-child(n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-2 .xpro-logo-grid-item:nth-child(2n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-3 .xpro-logo-grid-item:nth-child(3n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-4 .xpro-logo-grid-item:nth-child(4n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-5 .xpro-logo-grid-item:nth-child(5n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-6 .xpro-logo-grid-item:nth-child(6n+1)' => 'border-left-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-1 .xpro-logo-grid-item:nth-child(-n+1)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-2 .xpro-logo-grid-item:nth-child(-n+2)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-3 .xpro-logo-grid-item:nth-child(-n+3)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-4 .xpro-logo-grid-item:nth-child(-n+4)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-5 .xpro-logo-grid-item:nth-child(-n+5)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-6 .xpro-logo-grid-item:nth-child(-n+6)' => 'border-top-width: {{grid_border_width.SIZE}}{{UNIT}};',

					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet1 .xpro-logo-grid-item:nth-child(n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet2 .xpro-logo-grid-item:nth-child(2n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet3 .xpro-logo-grid-item:nth-child(3n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet4 .xpro-logo-grid-item:nth-child(4n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet5 .xpro-logo-grid-item:nth-child(5n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet6 .xpro-logo-grid-item:nth-child(6n+1)' => 'border-left-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet1 .xpro-logo-grid-item:nth-child(-n+1)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet2 .xpro-logo-grid-item:nth-child(-n+2)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet3 .xpro-logo-grid-item:nth-child(-n+3)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet4 .xpro-logo-grid-item:nth-child(-n+4)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet5 .xpro-logo-grid-item:nth-child(-n+5)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet6 .xpro-logo-grid-item:nth-child(-n+6)' => 'border-top-width: {{grid_border_width_tablet.SIZE}}{{UNIT}};',

					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile1 .xpro-logo-grid-item:nth-child(n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile2 .xpro-logo-grid-item:nth-child(2n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile3 .xpro-logo-grid-item:nth-child(3n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile4 .xpro-logo-grid-item:nth-child(4n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile5 .xpro-logo-grid-item:nth-child(5n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile6 .xpro-logo-grid-item:nth-child(6n+1)' => 'border-left-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile1 .xpro-logo-grid-item:nth-child(-n+1)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile2 .xpro-logo-grid-item:nth-child(-n+2)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile3 .xpro-logo-grid-item:nth-child(-n+3)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile4 .xpro-logo-grid-item:nth-child(-n+4)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile5 .xpro-logo-grid-item:nth-child(-n+5)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile6 .xpro-logo-grid-item:nth-child(-n+6)' => 'border-top-width: {{grid_border_width_mobile.SIZE}}{{UNIT}};',

					'{{WRAPPER}}.xpro-logo-grid--tictactoe .xpro-logo-grid-item' => 'border-top-width: {{SIZE}}{{UNIT}}; border-right-width: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.xpro-logo-grid--box .xpro-logo-grid-item' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'grid_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'grid_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-logo-grid-item' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'grid_border_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'grid_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-logo-grid-figure' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.xpro-logo-grid--border .xpro-logo-grid-wrapper, {{WRAPPER}}.xpro-logo-grid--box .xpro-logo-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.xpro-logo-grid--border .xpro-logo-grid-item:first-child'                                              => 'border-top-left-radius: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.xpro-logo-grid--border .xpro-logo-grid-item:last-child'                                               => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',

					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-2 .xpro-logo-grid-item:nth-child(2)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-2 .xpro-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-3 .xpro-logo-grid-item:nth-child(3)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-3 .xpro-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-4 .xpro-logo-grid-item:nth-child(4)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-4 .xpro-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-5 .xpro-logo-grid-item:nth-child(5)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-5 .xpro-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-6 .xpro-logo-grid-item:nth-child(6)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col-6 .xpro-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',

					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet2 .xpro-logo-grid-item:nth-child(2)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet2 .xpro-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet3 .xpro-logo-grid-item:nth-child(3)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet3 .xpro-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet4 .xpro-logo-grid-item:nth-child(4)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet4 .xpro-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet5 .xpro-logo-grid-item:nth-child(5)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet5 .xpro-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet6 .xpro-logo-grid-item:nth-child(6)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--tablet6 .xpro-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',

					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile2 .xpro-logo-grid-item:nth-child(2)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile2 .xpro-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile3 .xpro-logo-grid-item:nth-child(3)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile3 .xpro-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile4 .xpro-logo-grid-item:nth-child(4)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile4 .xpro-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile5 .xpro-logo-grid-item:nth-child(5)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile5 .xpro-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile6 .xpro-logo-grid-item:nth-child(6)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--border.xpro-logo-grid--col--mobile6 .xpro-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',

					// Tictactoe
					'{{WRAPPER}}.xpro-logo-grid--tictactoe .xpro-logo-grid-wrapper'                                                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.xpro-logo-grid--tictactoe .xpro-logo-grid-item:first-child'                                         => 'border-top-left-radius: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.xpro-logo-grid--tictactoe .xpro-logo-grid-item:last-child'                                          => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',

					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-2 .xpro-logo-grid-item:nth-child(2)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-2 .xpro-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-3 .xpro-logo-grid-item:nth-child(3)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-3 .xpro-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-4 .xpro-logo-grid-item:nth-child(4)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-4 .xpro-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-5 .xpro-logo-grid-item:nth-child(5)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-5 .xpro-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-6 .xpro-logo-grid-item:nth-child(6)'      => 'border-top-right-radius: {{grid_border_radius.RIGHT}}{{UNIT}};',
					'(desktop+){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col-6 .xpro-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius.LEFT}}{{UNIT}};',

					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet2 .xpro-logo-grid-item:nth-child(2)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet2 .xpro-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet3 .xpro-logo-grid-item:nth-child(3)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet3 .xpro-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet4 .xpro-logo-grid-item:nth-child(4)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet4 .xpro-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet5 .xpro-logo-grid-item:nth-child(5)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet5 .xpro-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet6 .xpro-logo-grid-item:nth-child(6)'      => 'border-top-right-radius: {{grid_border_radius_tablet.RIGHT}}{{UNIT}};',
					'(tablet){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--tablet6 .xpro-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_tablet.LEFT}}{{UNIT}};',

					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile2 .xpro-logo-grid-item:nth-child(2)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile2 .xpro-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile3 .xpro-logo-grid-item:nth-child(3)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile3 .xpro-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile4 .xpro-logo-grid-item:nth-child(4)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile4 .xpro-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile5 .xpro-logo-grid-item:nth-child(5)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile5 .xpro-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile6 .xpro-logo-grid-item:nth-child(6)'      => 'border-top-right-radius: {{grid_border_radius_mobile.RIGHT}}{{UNIT}};',
					'(mobile){{WRAPPER}}.xpro-logo-grid--tictactoe.xpro-logo-grid--col--mobile6 .xpro-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{grid_border_radius_mobile.LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-logo-grid-figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'_tabs_image_effects',
			array(
				'separator' => 'before',
			)
		);

		$this->start_controls_tab(
			'_tab_image_effects_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_css_filters',
				'selector' => '{{WRAPPER}} .xpro-logo-grid-figure img',
			)
		);

		$this->add_control(
			'image_opacity',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-logo-grid-figure img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .xpro-logo-grid-figure:hover img',
			)
		);

		$this->add_control(
			'image_opacity_hover',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-logo-grid-figure:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label'       => __( 'Hover Animation', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::HOVER_ANIMATION,
				'label_block' => true,
			)
		);

		$this->add_control(
			'image_bg_hover_transition',
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
					'{{WRAPPER}} .xpro-logo-grid-figure:hover img' => 'transition-duration: {{SIZE}}s;',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
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
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'logo-grid/layout/frontend.php';
	}
}
