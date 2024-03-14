<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

/**
 * Get the existing menus in array format
 * @return array
 */
function better_navigation_menu_array() {
    $menus = wp_get_nav_menus();
    $menu_array = [];
    foreach ( $menus as $menu ) {
        $menu_array[$menu->slug] = $menu->name;
    }
    return $menu_array;
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-navigation-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Better Navigation Menu', 'elementor-hello-world' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'swiper','better-nav','wow','isotope','youtubepopup-js','bootstrap-js','splitting','parallaxie','simpleParallax','justifiedgallery','scrollit','counterup','jquery.twentytwenty','better-el-addons','elementor-hello-world' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		// start of the Content tab section
		$this->start_controls_section(
			'clients_content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
        );

		$this->add_control(
			'better_header_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
            'menu', [
                'label' => __( 'Menu', 'saasland-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => better_navigation_menu_array()
            ]
        );

		$this->end_controls_section();
		// end of the Content tab section

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'better_header_links_color',
			[
				'label' => esc_html__( 'links Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-navigation-menu.style-1 .navbar-nav .nav-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_header_links_typography',
				'label' => esc_html__( 'links Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-navigation-menu.style-1 .navbar-nav .nav-link:not(.dropdown-item)',
			]
		);
		
		$this->add_control(
			'better_sticky_header_links_color',
			[
				'label' => esc_html__( 'Scroll links Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-navigation-menu.style-1.nav-scroll .navbar-nav .nav-link' => 'color: {{VALUE}}',
				],
			]
        );

		$this->add_control(
			'better_header_dropdown_color',
			[
				'label' => esc_html__( 'Dropdown Menu Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-navigation-menu.style-1 .dropdown-menu' => 'background: {{VALUE}}',
				],
			]
        );

		$this->add_control(
			'better_header_dropdown_links_color',
			[
				'label' => esc_html__( 'Dropdown links Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-navigation-menu.style-1 .dropdown-menu' => 'color: {{VALUE}}',
				],
			]
        );

		$this->add_control(
			'better_header_dropdown_links_accent_color',
			[
				'label' => esc_html__( 'Dropdown links accent Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-navigation-menu.style-1 .dropdown-menu .dropdown-item:after' => 'background: {{VALUE}}',
				],
			]
        );

		$this->end_controls_section();

	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$style = $settings['better_header_style'];
       
		require( 'styles/style'.$style.'.php' );

	}
}