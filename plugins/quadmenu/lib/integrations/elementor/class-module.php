<?php

namespace QuadLayers\QuadMenu\Integrations\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class Module extends \Elementor\Widget_Base {

	public function get_name() {
		return 'quadmenu';
	}

	public function get_title() {
		return __( 'QuadMenu', 'quadmenu' );
	}

	public function get_icon() {
		return 'eicon-menu-bar';
	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function _register_controls() {

	$this->start_controls_section(
		'general',
		array( 'label' => esc_html__( 'QuadMenu', 'quadmenu' ) )
	);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
		$this->add_control(
			'menu',
			array(
				'label'        => __( 'Menu', 'quadmenu' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'options'      => $menus,
				'default'      => array_keys( $menus )[0],
				'save_default' => true,
				'separator'    => 'after',
				'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'quadmenu' ), admin_url( 'nav-menus.php' ) ),
			)
		);
		} else {
		$this->add_control(
			'menu',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'There are no menus in your site.<br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'quadmenu' ), admin_url( 'nav-menus.php' ) ),
				'separator'       => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);
		}

	$this->add_control(
		'layout',
		array(
			'type'    => \Elementor\Controls_Manager::SELECT,
			'label'   => __( 'Layout', 'quadmenu' ),
			'default' => 'collapse',
			'options' => array(
				// 'embed' => esc_html__('Embed', 'quadmenu'),
				'collapse'  => esc_html__( 'Collapse', 'quadmenu' ),
				'offcanvas' => esc_html__( 'Offcanvas', 'quadmenu' ),
				// 'vertical' => esc_html__('Vertical', 'quadmenu'),
				'inherit'   => esc_html__( 'Inherit', 'quadmenu' ),
			),
		)
	);

	$this->add_control(
		'theme',
		array(
			'type'        => \Elementor\Controls_Manager::SELECT,
			'label'       => __( 'Theme', 'quadmenu' ),
			'default'     => 'default_theme',
			'options'     => $GLOBALS['quadmenu_themes'],
			'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">QuadMenu Options</a> to manage your menu themes.', 'quadmenu' ), admin_url( 'admin.php?page=' . QUADMENU_PANEL ) ),
			'separator'   => 'after',
		)
	);

	$this->add_control(
		'navbar_logo',
		array(
			'type'        => \Elementor\Controls_Manager::MEDIA,
			'label'       => __( 'Logo', 'quadmenu' ),
			'description' => esc_html__( 'Max logo height in px.', 'quadmenu' ),
			'default'     => array(
				'url' => QUADMENU_PLUGIN_URL . 'assets/frontend/images/logo.png',
			),
			'show_label'  => false,
		)
	);

	  /*
	 $this->add_control('navbar_logo_height', array(
		'type' => Controls_Manager::SLIDER,
		'label' => __('Height', 'quadmenu'),
		'default' => array(
		'unit' => 'px',
		'size' => 25,
		),
		'range' => array(
		'px' => array(
		'min' => 20,
		'max' => 160,
		),
		),
		)); */

	$this->add_control(
		'layout_align',
		array(
			'type'      => \Elementor\Controls_Manager::SELECT,
			'label'     => esc_html__( 'Align', 'quadmenu' ),
			'subtitle'  => esc_html__( 'Menu items alignment.', 'quadmenu' ),
			'options'   => array(
				'left'   => esc_html__( 'Left', 'quadmenu' ),
				'center' => esc_html__( 'Center', 'quadmenu' ),
				'right'  => esc_html__( 'Right', 'quadmenu' ),
			),
			'condition' => array(
				'layout' => array( 'embed', 'collapse', 'offcanvas' ),
			),
			'default'   => 'left',
		)
	);

	// Behaviour
	// ---------------------------------------------------------
	$this->add_control(
		'layout_breakpoint',
		array(
			'type'    => \Elementor\Controls_Manager::SLIDER,
			'label'   => esc_html__( 'Breakpoint', 'quadmenu' ),
			'default' => array(
				'unit' => 'px',
				'size' => 768,
			),
			'range'   => array(
				'px' => array(
					'min' => 10,
					'max' => 300,
				),
			),
		)
	);

	$this->add_control(
		'layout_width',
		array(
			'type'        => \Elementor\Controls_Manager::SELECT,
			'type'        => 'select',
			'label'       => esc_html__( 'Width', 'quadmenu' ),
			'description' => esc_html__( 'Try to force menu width to fit screen.', 'quadmenu' ),
			'options'     => array(
				'yes'   => esc_html__( 'Yes', 'quadmenu' ),
				'false' => esc_html__( 'No', 'quadmenu' ),
			),
			'condition'   => array(
				'layout' => array( 'collapse', 'offcanvas' ),
			),
			'default'     => 'false',
		)
	);
	$this->add_control(
		'layout_width_inner',
		array(
			'type'      => \Elementor\Controls_Manager::SELECT,
			'label'     => esc_html__( 'Inner', 'quadmenu' ),
			'options'   => array(
				'yes'   => esc_html__( 'Yes', 'quadmenu' ),
				'false' => esc_html__( 'No', 'quadmenu' ),
			),
			'condition' => array(
				'layout' => array( 'collapse', 'offcanvas' ),
			),
			'default'   => 'false',
		)
	);
	$this->add_control(
		'layout_width_inner_selector',
		array(
			'type'        => \Elementor\Controls_Manager::TEXT,
			'label'       => esc_html__( 'Selector', 'quadmenu' ),
			'description' => esc_html__( 'The menu container will take the width of this selector.', 'quadmenu' ),
			'default'     => '.container',
			'condition'   => array(
				'layout'             => array( 'collapse', 'offcanvas' ),
				'layout_width_inner' => 'yes',
			),
		)
	);
	$this->add_control(
		'layout_lazyload',
		array(
			'type'        => \Elementor\Controls_Manager::SELECT,
			'label'       => esc_html__( 'Lazyload', 'quadmenu' ),
			'options'     => array(
				'yes'   => esc_html__( 'Yes', 'quadmenu' ),
				'false' => esc_html__( 'No', 'quadmenu' ),
			),
			'default'     => 'false',
			'description' => esc_html__( 'This is a beta function, please test it carefully.', 'quadmenu' ),
		)
	);
	$this->add_control(
		'layout_current',
		array(
			'type'        => \Elementor\Controls_Manager::SELECT,
			'label'       => esc_html__( 'Open', 'quadmenu' ),
			'options'     => array(
				'yes'   => esc_html__( 'Yes', 'quadmenu' ),
				'false' => esc_html__( 'No', 'quadmenu' ),
			),
			'default'     => 'false',
			'description' => esc_html__( 'Open dropdown if is current page.', 'quadmenu' ),
		)
	);
	$this->add_control(
		'layout_divider',
		array(
			'type'        => \Elementor\Controls_Manager::SELECT,
			'label'       => esc_html__( 'Divider', 'quadmenu' ),
			'description' => esc_html__( 'Show a small divider bar between each menu item.', 'quadmenu' ),
			'options'     => array(
				'show' => esc_html__( 'Show', 'quadmenu' ),
				'hide' => esc_html__( 'Hide', 'quadmenu' ),
			),
			'condition'   => array(
				'layout' => array( 'embed', 'collapse', 'offcanvas' ),
			),
			'default'     => 'hide',
		)
	);
	$this->add_control(
		'layout_caret',
		array(
			'type'        => \Elementor\Controls_Manager::SELECT,
			'label'       => esc_html__( 'Caret', 'quadmenu' ),
			'description' => esc_html__( 'Show carets on items with dropdown menus.', 'quadmenu' ),
			'options'     => array(
				'show' => esc_html__( 'Show', 'quadmenu' ),
				'hide' => esc_html__( 'Hide', 'quadmenu' ),
			),
			'condition'   => array(
				'layout' => array( 'embed', 'collapse', 'offcanvas' ),
			),
			'default'     => 'hide',
		)
	);
	$this->add_control(
		'layout_classes',
		array(
			'type'    => \Elementor\Controls_Manager::TEXT,
			'label'   => esc_html__( 'Classes', 'quadmenu' ),
			'default' => '',
			// 'separator' => 'after',
		)
	);

	$this->add_control(
		'layout_trigger',
		array(
			'type'      => \Elementor\Controls_Manager::SELECT,
			'label'     => esc_html__( 'Trigger', 'quadmenu' ),
			'options'   => array(
				'hoverintent' => esc_html__( 'Hover', 'quadmenu' ),
				'click'       => esc_html__( 'Click', 'quadmenu' ),
			),
			'subtitle'  => esc_html__( 'Open dropdown menu on mouseover or click.', 'quadmenu' ),
			'default'   => 'hoverintent',
			'condition' => array(
				'layout' => array( 'embed', 'collapse', 'offcanvas' ),
			),
		)
	);

	$this->add_control(
		'layout_dropdown_maxheight',
		array(
			'type'      => \Elementor\Controls_Manager::SELECT,
			'type'      => 'select',
			'label'     => esc_html__( 'Max Height', 'quadmenu' ),
			'subtitle'  => esc_html__( 'Set the max height of dropdowns.', 'quadmenu' ),
			'options'   => array(
				'yes'   => esc_html__( 'Yes', 'quadmenu' ),
				'false' => esc_html__( 'No', 'quadmenu' ),
			),
			'default'   => 'false',
			'condition' => array(
				'layout' => array( 'embed', 'collapse', 'offcanvas' ),
			),
		)
	);

		$this->end_controls_section();
	}

	protected function render() {

		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_active_settings();

		if ( ! empty( $settings['menu'] ) ) {

			$args = array(
				'echo'                        => false,
				'menu'                        => $settings['menu'],
				'theme'                       => $settings['theme'],
				'layout'                      => $settings['layout'],
				'layout_align'                => $settings['layout_align'],
				'layout_divider'              => $settings['layout_divider'],
				'layout_caret'                => $settings['layout_caret'],
				'layout_classes'              => $settings['layout_classes'],
				'layout_width'                => wp_validate_boolean( $settings['layout_width'] ),
				'layout_width_inner'          => wp_validate_boolean( $settings['layout_width_inner'] ),
				'layout_width_inner_selector' => esc_html( $settings['layout_width_inner_selector'] ),
				'layout_lazyload'             => wp_validate_boolean( $settings['layout_lazyload'] ),
				'layout_current'              => wp_validate_boolean( $settings['layout_current'] ),
				// 'navbar_logo_height' => $settings['navbar_logo_height'],
			);

			if ( isset( $settings['navbar_logo']['url'] ) ) {
				$args['navbar_logo']['url'] = $settings['navbar_logo']['url'];
			}

			if ( wp_doing_ajax() ) {
				$args['layout_classes'] = 'js';
			}

			echo quadmenu( $args );
		}
	}

	protected function content_template() {

	}

	public function render_plain_content( $instance = array() ) {

	}

}
