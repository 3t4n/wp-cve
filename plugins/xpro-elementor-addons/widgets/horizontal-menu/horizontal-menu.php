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
class Horizontal_Menu extends Widget_Base {


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
		return 'xpro-horizontal-menu';
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
		return __( 'Horizontal Menu', 'xpro-elementor-addons' );
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
		return 'xi-horizontal-menu xpro-widget-label';
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
		return array( 'xpro-themer' );
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
		return array( 'menu', 'horizontal' );
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
			)
		);

		$menus = $this->get_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'nav_menu',
				array(
					'label'        => __( 'Menu', 'xpro-elementor-addons' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'description'  => sprintf(
						/* translators: %s: Title */
						__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'xpro-elementor-addons' ),
						admin_url( 'nav-menus.php' )
					),
				)
			);
		} else {
			$this->add_control(
				'nav_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . __( 'There are no menus in your site.', 'xpro-elementor-addons' ) . '</strong><br>' . sprintf(
						/* translators: %s: Title */
						__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'xpro-elementor-addons' ),
						admin_url( 'nav-menus.php?action=edit&menu=0' )
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_control(
			'one_page_navigation',
			array(
				'label'              => __( 'One Page Navigation', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'scroll_offset',
			array(
				'label'              => __( 'Scroll Offset', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 5,
					),
				),
				'default'            => array(
					'size' => 100,
				),
				'condition'          => array(
					'one_page_navigation' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'          => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'        => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'         => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
					'space-between' => array(
						'title' => __( 'Justified', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'prefix_class' => 'elementor-align-',
				'default'      => 'right',
				'selectors'    => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_show',
			array(
				'label'              => __( 'Responsive Breakpoint', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'tablet',
				'separator'          => 'before',
				'frontend_available' => true,
				'options'            => array(
					'tablet' => __( 'Tablet & Mobile', 'xpro-elementor-addons' ),
					'mobile' => __( 'Mobile Only', 'xpro-elementor-addons' ),
					'none'   => __( 'None', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'hamburger_icon',
			array(
				'label'     => __( 'Toggle Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-bars',
					'library' => 'solid',
				),
				'condition' => array(
					'responsive_show!' => array( 'none' ),
				),
			)
		);

		$this->add_control(
			'hamburger_close_icon',
			array(
				'label'     => __( 'Close Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-times',
					'library' => 'solid',
				),
				'condition' => array(
					'responsive_show!' => array( 'none' ),
				),
			)
		);

		$this->add_control(
			'hamburger_entrance_animation',
			array(
				'label'     => __( 'Entrance', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'    => false,
				'default'   => 'right',
				'condition' => array(
					'responsive_show!' => array( 'none' ),
				),
			)
		);

		$this->end_controls_section();

		//Style Menu
		$this->start_controls_section(
			'section_menu_style',
			array(
				'label' => __( 'Menu', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'menu_style',
			array(
				'label'   => __( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => array(
					'fade'                => __( 'FadeIn', 'xpro-elementor-addons' ),
					'crossOver'           => __( 'Cross Over', 'xpro-elementor-addons' ),
					'pushRight'           => __( 'Push Right', 'xpro-elementor-addons' ),
					'focusLens'           => __( 'Focus Lens', 'xpro-elementor-addons' ),
					'lineTopBottom'       => __( 'Line Top Bottom', 'xpro-elementor-addons' ),
					'underlineFromLeft'   => __( 'Underline Left', 'xpro-elementor-addons' ),
					'underlineFromRight'  => __( 'Underline Right', 'xpro-elementor-addons' ),
					'underlineFromCenter' => __( 'Underline Center', 'xpro-elementor-addons' ),
					'underlineCrossOver'  => __( 'Underline Cross', 'xpro-elementor-addons' ),
					'sweepToLeft'         => __( 'Sweep To Left', 'xpro-elementor-addons' ),
					'sweepToRight'        => __( 'Sweep To Right', 'xpro-elementor-addons' ),
					'sweepToTop'          => __( 'Sweep To Top', 'xpro-elementor-addons' ),
					'sweepToBottom'       => __( 'Sweep To Bottom', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typography',
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > a',
			)
		);

		$this->add_control(
			'line_width',
			array(
				'label'      => __( 'Line Stroke', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-menu-style-crossOver .xpro-elementor-horizontal-navbar-nav > li > a:before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-pushRight .xpro-elementor-horizontal-navbar-nav > li > a:before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li > a::before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li > a::after,
					 {{WRAPPER}} [class*=xpro-elementor-horizontal-menu-style-underline] .xpro-elementor-horizontal-navbar-nav > li > a:before' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'menu_style' => array(
						'underlineFromLeft',
						'underlineFromRight',
						'underlineFromCenter',
						'underlineCrossOver',
						'crossOver',
						'lineTopBottom',
						'pushRight',
					),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_menu_style' );

		$this->start_controls_tab(
			'tab_menu_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'menu_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > a',
				'condition' => array(
					'menu_style!' => array( 'sweepToLeft', 'sweepToRight', 'sweepToTop', 'sweepToBottom' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'hover_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li:hover > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'menu_background_hover',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-menu-style-fade .xpro-elementor-horizontal-navbar-nav > li:hover > a,
								{{WRAPPER}} [class*=xpro-elementor-horizontal-menu-style-underline] .xpro-elementor-horizontal-navbar-nav > li:hover > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-crossOver .xpro-elementor-horizontal-navbar-nav > li:hover > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-pushRight .xpro-elementor-horizontal-navbar-nav > li:hover > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-focusLens .xpro-elementor-horizontal-navbar-nav > li:hover > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li:hover > a,
								{{WRAPPER}} [class*=xpro-elementor-horizontal-menu-style-sweepTo] .xpro-elementor-horizontal-navbar-nav > li > a:before',
			)
		);

		$this->add_control(
			'menu_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li:hover > a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'menu_hover_underline',
			array(
				'label'     => __( 'Line Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-menu-style-crossOver .xpro-elementor-horizontal-navbar-nav > li > a:before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-pushRight .xpro-elementor-horizontal-navbar-nav > li > a:before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li > a::before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li > a::after,
					 {{WRAPPER}} [class*=xpro-elementor-horizontal-menu-style-underline] .xpro-elementor-horizontal-navbar-nav > li > a:before'             => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-elementor-horizontal-menu-style-focusLens .xpro-elementor-horizontal-navbar-nav > li > a::before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-focusLens .xpro-elementor-horizontal-navbar-nav > li > a::after' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'menu_style' => array(
						'underlineFromLeft',
						'underlineFromRight',
						'underlineFromCenter',
						'underlineCrossOver',
						'crossOver',
						'focusLens',
						'lineTopBottom',
						'pushRight',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_active',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'active_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'menu_background_active',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-menu-style-fade .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a,
								{{WRAPPER}} [class*=xpro-elementor-horizontal-menu-style-underline] .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-crossOver .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-pushRight .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-focusLens .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a,
								{{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a,
								{{WRAPPER}} [class*=xpro-elementor-horizontal-menu-style-sweepTo] .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a:before',
			)
		);

		$this->add_control(
			'menu_active_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'menu_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'menu_active_underline',
			array(
				'label'     => __( 'Line Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-menu-style-crossOver .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a:before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-pushRight .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a:before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a::before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-lineTopBottom .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a::after,
					 {{WRAPPER}} [class*=xpro-elementor-horizontal-menu-style-underline] .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a:before'             => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-elementor-horizontal-menu-style-focusLens .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a::before,
					 {{WRAPPER}} .xpro-elementor-horizontal-menu-style-focusLens .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a::after' => 'border-color: {{VALUE}};',
				),
				'default'   => '#c92a61',
				'condition' => array(
					'menu_style' => array(
						'underlineFromLeft',
						'underlineFromRight',
						'underlineFromCenter',
						'underlineCrossOver',
						'crossOver',
						'focusLens',
						'lineTopBottom',
						'pushRight',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'menu_border',
				'selector'  => '{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > a',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
				'condition'  => array(
					'menu_style' => array( 'fade' ),
				),
			)
		);

		$this->add_responsive_control(
			'menu_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'menu_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Style Sub Menu
		$this->start_controls_section(
			'section_submenu_style',
			array(
				'label' => __( 'Sub Menu', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'submenu_typography',
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li > a',
			)
		);

		$this->add_control(
			'submenu_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_submenu_style' );

		$this->start_controls_tab(
			'tab_submenu_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'submenu_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'submenu_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li > a,{{WRAPPER}} .xpro-elementor-horizontal-navbar-nav > li > .xpro-elementor-dropdown-menu:after',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'submenu_hover_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li:hover > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'submenu_background_hover',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li:hover > a',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_active',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'submenu_active_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li.current-menu-item > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'submenu_background_active',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li.current-menu-item > a',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'submenu_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li:not(:nth-last-child(1)) > a' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'submenu_separator_size',
			array(
				'label'      => __( 'Separator Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li:not(:nth-last-child(1)) > a' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'submenu_shadow',
				'selector' => '{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu',
			)
		);

		$this->add_responsive_control(
			'submenu_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Toggle Button
		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label' => __( 'Toggle Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'toggle_align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-horizontal-menu-toggler-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler'       => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler > svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toggle_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 40,
				),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toggle_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'toggle_border',
				'selector' => '{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler',
			)
		);

		$this->add_control(
			'toggle_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toggle_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-toggler' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Style Responsive Menu
		$this->start_controls_section(
			'section_responsive_menu_style',
			array(
				'label'     => __( 'Responsive Menu', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'responsive_show!' => array( 'none' ),
				),
			)
		);

		$this->add_control(
			'responsive_menu_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 400,
				),
				'selectors'  => array(
					'(tablet){{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet' => 'width: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'responsive_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f5f5f5',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet' => 'background-color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00000069',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-overlay' => 'background-color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'responsive_close_heading',
			array(
				'label'     => __( 'Close', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'close_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-close'       => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-close > svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'close_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 40,
				),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-close' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'close_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-close' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'close_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-close' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'close_border',
				'selector' => '{{WRAPPER}} button.xpro-elementor-horizontal-menu-close',
			)
		);

		$this->add_control(
			'close_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'close_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} button.xpro-elementor-horizontal-menu-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'responsive_menu_heading',
			array(
				'label'     => __( 'Menu', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_responsive_menu_style' );

		$this->start_controls_tab(
			'responsive_menu_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'responsive_menu_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#b5b5b5',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar-nav > li > a' => 'color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar-nav > li > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_menu_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f5f5f5',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar-nav > li > a' => 'background: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar-nav > li > a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_menu_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e6e6e6',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar-nav > li > a' => 'border-bottom-color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar-nav > li > a' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'responsive_menu_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'responsive_menu_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar-nav > li:hover > a' => 'color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar-nav > li:hover > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_menu_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar-nav > li:hover > a' => 'background: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar-nav > li:hover > a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'responsive_menu_active',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'responsive_menu_active_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2b2b2b',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a' => 'color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_menu_active_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f5f5f5',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a' => 'background: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar-nav > li.current-menu-item > a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'responsive_submenu_heading',
			array(
				'label'     => __( 'Sub Menu', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_responsive_submenu_style' );

		$this->start_controls_tab(
			'responsive_submenu_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'responsive_submenu_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#a2a2a2',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-dropdown-menu > li > a' => 'color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-dropdown-menu > li > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_submenu_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#efefef',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-dropdown-menu > li > a' => 'background: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-dropdown-menu > li > a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_submenu_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff69',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li > a' => 'border-bottom-width:1px; border-bottom-color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-horizontal-navbar .xpro-elementor-dropdown-menu > li > a' => 'border-bottom-width:1px; border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'responsive_submenu_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'responsive_submenu_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-dropdown-menu > li:hover > a' => 'color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-dropdown-menu > li:hover > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_submenu_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-dropdown-menu > li:hover > a' => 'background: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-dropdown-menu > li:hover > a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'responsive_submenu_active',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'responsive_submenu_active_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2b2b2b',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-dropdown-menu > li.current-menu-item > a' => 'color: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-dropdown-menu > li.current-menu-item > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'responsive_submenu_active_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#efefef',
				'selectors' => array(
					'(tablet) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-tablet .xpro-elementor-dropdown-menu > li.current-menu-item > a' => 'background: {{VALUE}};',
					'(mobile) {{WRAPPER}} .xpro-elementor-horizontal-menu-responsive-mobile .xpro-elementor-dropdown-menu > li.current-menu-item > a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function get_menus() {
		$list  = array();
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			$list[ $menu->slug ] = $menu->name;
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
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'horizontal-menu/layout/frontend.php';
	}
}
