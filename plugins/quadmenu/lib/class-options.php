<?php

namespace QuadLayers\QuadMenu;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Plugin as Plugin;

/**
 * Options Class ex QuadMenu_Options
 */
class Options {

	private $defaults = array();

	private static $instance;

	public function __construct() {

		// include options that the user can't change
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/options', array( $this, 'developer' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/global_variable', array( $this, 'developer' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/global_variable', array( $this, 'compatibility' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/ajax_save/response', array( $this, 'developer_ajax' ) );

		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/sections', array( $this, 'configuration' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/sections', array( $this, 'locations' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/sections', array( $this, 'responsive' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/sections', array( $this, 'themes' ) );
		add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/sections', array( $this, 'css' ) );

		if ( ! is_admin() && ! is_customize_preview() ) {
			return;
		}

		add_filter( 'redux/page/' . QUADMENU_DB_OPTIONS . '/form/before', array( $this, 'remove' ) );
	}

	// Comaptibility with old versions
	// -------------------------------------------------------------------------
	function compatibility( $options ) {

		foreach ( $options as $key => $value ) {

			if ( is_array( $value ) && isset( $value['color'] ) && isset( $value['alpha'] ) ) {
				$options[ $key ] = \Redux_Helpers::hex2rgba( $value['color'], $value['alpha'] );
			}
		}

		return $options;
	}

	function developer( $options = array() ) {

		$developer = apply_filters( 'quadmenu_developer_options', array() );

		if ( ! empty( $developer ) && is_array( $developer ) ) {

			$options = array_merge( $options, $developer );

			foreach ( $developer as $id => $value ) {

				add_filter( 'redux/options/' . QUADMENU_DB_OPTIONS . '/field/' . $id, '__return_null' );
			}
		}

		return $options;
	}

	function developer_ajax( $return_array ) {

		$return_array['options'] = apply_filters( 'quadmenu_developer_options', $return_array['options'] );

		return $return_array;
	}

	function remove( $redux ) {

		$class = '';

		foreach ( $redux->parent->sections as $key => $section ) {

			if ( empty( $section['fields'] ) ) {

				if ( isset( $redux->parent->sections[ $key ]['class'] ) ) {

					$class = ' ' . $redux->parent->sections[ $key ]['class'];
				}

				$redux->parent->sections[ $key ]['class'] = 'empty_section' . $class;
			}
		}

		return $redux->parent->sections;
	}

	function configuration( $sections ) {

		$this->defaults = apply_filters( 'quadmenu_default_options', array() );

		$sections[] = array(
			// 'customizer' => true,
			'id'          => 'quadmenu_configuration',
			'title'       => esc_html__( 'Configuration', 'quadmenu' ),
			'heading'     => false,
			'icon'        => 'dashicons dashicons-admin-generic',
			'permissions' => 'manage_options',
			'fields'      => array(
				array(
					'customizer' => false,
					'compiler'   => false,
					'id'         => 'viewport',
					'type'       => 'switch',
					'title'      => esc_html__( 'Viewport', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Include the viewport meta tag in site head.', 'quadmenu' ),
					'default'    => (bool) $this->defaults['viewport'],
				),
				array(
					'customizer' => false,
					'compiler'   => false,
					'id'         => 'styles',
					'type'       => 'switch',
					'title'      => esc_html__( 'Stylesheet', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Load styles in your theme.', 'quadmenu' ),
					'compiler'   => false,
					'default'    => (bool) $this->defaults['styles'],
				),
				array(
					'customizer' => true,
					'transport'  => 'refresh',
					'compiler'   => false,
					'id'         => 'styles_normalize',
					'type'       => 'switch',
					'title'      => esc_html__( 'Normalize', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Try to clean residual styling of the theme.', 'quadmenu' ),
					'required'   => array(
						'styles',
						'=',
						1,
					),
					'default'    => (bool) $this->defaults['styles_normalize'],
				),
				array(
					'customizer' => true,
					'transport'  => 'refresh',
					'compiler'   => false,
					'id'         => 'styles_widgets',
					'type'       => 'switch',
					'title'      => esc_html__( 'Widgets', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Load default widgets stylesheets.', 'quadmenu' ),
					'required'   => array(
						'styles',
						'=',
						1,
					),
					'default'    => (bool) $this->defaults['styles_widgets'],
				),
				array(
					'customizer' => true,
					'transport'  => 'refresh',
					'compiler'   => false,
					'id'         => 'styles_pscrollbar',
					'type'       => 'switch',
					'title'      => esc_html__( 'Perfect ScrollBar', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Load Perfect scroll bar files.', 'quadmenu' ),
					'default'    => (bool) $this->defaults['styles_pscrollbar'],
				),
				array(
					'customizer' => false,
					'compiler'   => false,
					'id'         => 'styles_owlcarousel',
					'type'       => 'switch',
					'title'      => esc_html__( 'OWL Carousel', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Load OWL Carousel files.', 'quadmenu' ),
					'default'    => (bool) $this->defaults['styles_owlcarousel'],
				),
				array(
					'customizer' => false,
					'compiler'   => false,
					'id'         => 'styles_icons',
					'type'       => 'select',
					'options'    => Plugin::instance()->registered_icons_names(),
					'title'      => esc_html__( 'Icons', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Select the fonticon for your menus.', 'quadmenu' ),
					'select2'    => array(
						'allowClear' => false,
					),
					'required'   => array(
						'styles',
						'=',
						1,
					),
					'default'    => $this->defaults['styles_icons'],
					'validate'   => 'no_special_chars',
				),
			),
		);

		return $sections;
	}

	function css( $sections ) {

		$this->defaults = apply_filters( 'quadmenu_default_options', array() );

		$sections[] = array(
			'heading'     => false,
			'id'          => 'quadmenu_css',
			'title'       => esc_html__( 'Customize', 'quadmenu' ),
			'icon'        => 'dashicons dashicons-editor-code',
			'permissions' => 'edit_theme_options',
			'fields'      => array(
				array(
					'customizer' => true,
					'transport'  => 'refresh',
					'compiler'   => false,
					'id'         => 'css',
					'type'       => 'ace_editor',
					'mode'       => 'css',
					'title'      => esc_html__( 'CSS', 'quadmenu' ),
					'subtitle'   => esc_html__( 'Quickly add some CSS to your theme by adding it to this block.', 'quadmenu' ),
					'theme'      => 'chrome',
					'default'    => $this->defaults['css'],
				),
			),
		);

		return $sections;
	}

	function locations( $sections ) {

		global $quadmenu_locations;

		$theme = get_stylesheet();

		$all_locations_defaults = apply_filters( 'quadmenu_default_options_locations', array() );

		foreach ( $quadmenu_locations as $id => $location ) {

			$current_location_defaults = apply_filters( 'quadmenu_default_options_location_' . $id, $all_locations_defaults );

			foreach ( $current_location_defaults as $key => $value ) {

				$this->defaults[ "{$id}_{$key}" ] = $value;
			}
		}

		$this->defaults = apply_filters( 'quadmenu_default_options', $this->defaults );

		foreach ( $quadmenu_locations as $id => $location ) {

			extract( $location );

			$sections[] = array(
				// 'customizer' => true,
				'subsection'  => true,
				'heading'     => false,
				'icon'        => 'dashicons dashicons-editor-insertmore',
				'id'          => 'quadmenu_location_' . $id,
				'title'       => $name,
				'permissions' => 'edit_theme_options',
				'fields'      => array(
					array(
						'customizer' => true,
						'transport'  => 'refresh',
						'id'         => $id . '_integration',
						'type'       => 'switch',
						'title'      => esc_html__( 'Integration', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Integrate QuadMenu in this theme location.', 'quadmenu' ),
						'default'    => $this->defaults[ $id . '_integration' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'refresh',
						'id'         => $id . '_unwrap',
						'type'       => 'switch',
						'title'      => esc_html__( 'Conflicts', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Try to solve conflicts of residual theme style.', 'quadmenu' ),
						'default'    => $this->defaults[ $id . '_unwrap' ],
						'required'   => array(
							$id . '_integration',
							'=',
							1,
						),
					),
					array(
						'customizer' => true,
						'transport'  => 'refresh',
						'type'       => 'select',
						'id'         => $id . '_theme',
						'title'      => __( 'Theme', 'quadmenu' ),
						'subtitle'   => __( 'Select a theme for this theme location', 'quadmenu' ),
						'class'      => 'current_theme',
						'options'    => (array) $GLOBALS['quadmenu_themes'],
						'default'    => $this->defaults[ $id . '_theme' ],
					),
					array(
						'customizer' => false,
						'id'         => $id . '_information',
						'type'       => 'info',
						'title'      => esc_html__( 'Integration', 'quadmenu' ),
						'style'      => 'success',
						'desc'       => sprintf( '<p>%s</p>', esc_html__( 'If your menu doesn\'t seem to be working properly after using Automatic Integration, the most common scenario is that you have residual styling from your theme and would need to use Manual Integration instead.', 'quadmenu' ) ),
						'required'   => array(
							$id . '_integration',
							'=',
							1,
						),
					),
					array(
						'customizer' => false,
						'id'         => $id . '_manual',
						'type'       => 'info',
						'title'      => esc_html__( 'Manual Integration', 'quadmenu' ),
						'style'      => 'info',
						'class'      => 'info_manual',
						'desc'       => sprintf( '<p>%s</p><p><code>&lt;?php quadmenu(array(&quot;theme_location&quot; => &quot;%s&quot;, &quot;theme&quot; => &quot;<span class="current_theme">%s</span>&quot;)); ?&gt;</code></p>', esc_html__( 'Copy the generated PHP function code and paste it into the appropriate template in your theme.', 'quadmenu' ), $id, 'default_theme' ),
					),
				),
			);
		}

		return $sections;
	}

	function responsive( $sections ) {

		$this->defaults = apply_filters( 'quadmenu_default_options', array() );

		$sections[] = array(
			'customizer'  => true,
			'heading'     => false,
			'id'          => 'quadmenu_responsive',
			'title'       => esc_html__( 'Responsive', 'quadmenu' ),
			'icon'        => 'dashicons dashicons-desktop',
			'permissions' => 'edit_theme_options',
			'fields'      => array(
				array(
					'compiler' => true,
					'id'       => 'gutter',
					'type'     => 'slider',
					'title'    => esc_html__( 'Gutter', 'quadmenu' ),
					'subtitle' => esc_html__( 'Padding between columns.', 'quadmenu' ),
					'min'      => '0',
					'step'     => '2',
					'max'      => '60',
					'default'  => (int) $this->defaults['gutter'],
					'validate' => 'numeric',
				),
				array(
					'compiler' => true,
					'id'       => 'screen_sm_width',
					'type'     => 'slider',
					'title'    => esc_html__( 'Screen Small', 'quadmenu' ),
					'subtitle' => esc_html__( 'Small screens breakpoint.', 'quadmenu' ),
					'min'      => '480',
					'step'     => '1',
					'max'      => '1000',
					'default'  => (int) $this->defaults['screen_sm_width'],
					'validate' => 'numeric',
				),
				array(
					'compiler' => true,
					'id'       => 'screen_md_width',
					'type'     => 'slider',
					'title'    => esc_html__( 'Screen Medium', 'quadmenu' ),
					'subtitle' => esc_html__( 'Medium screens breakpoint.', 'quadmenu' ),
					'min'      => '481',
					'step'     => '1',
					'max'      => '1200',
					'default'  => (int) $this->defaults['screen_md_width'],
					'validate' => 'numeric',
				),
				array(
					'compiler' => true,
					'id'       => 'screen_lg_width',
					'type'     => 'slider',
					'title'    => esc_html__( 'Screen Large', 'quadmenu' ),
					'subtitle' => esc_html__( 'Large screens breakpoint.', 'quadmenu' ),
					'min'      => '801',
					'step'     => '1',
					'max'      => '1600',
					'default'  => (int) $this->defaults['screen_lg_width'],
					'validate' => 'numeric',
				),
			),
		);

		return $sections;
	}

	function themes( $sections ) {

		global $quadmenu_themes;

		$all_themes_defaults = apply_filters( 'quadmenu_default_options_themes', array() );

		foreach ( $quadmenu_themes as $theme => $name ) {

			$current_theme_defaults = apply_filters( 'quadmenu_default_options_theme_' . $theme, $all_themes_defaults );

			foreach ( $current_theme_defaults as $key => $value ) {

				$this->defaults[ "{$theme}_{$key}" ] = $value;
			}
		}

		$this->defaults = apply_filters( 'quadmenu_default_options', $this->defaults );

		foreach ( $quadmenu_themes as $key => $theme ) {

			$sections[] = array(
				// 'customizer' => true,
				'customizer_title' => esc_html( 'Layout', 'quadmenu' ),
				'subsection'       => false,
				'heading'          => false,
				'id'               => 'quadmenu_layout_' . $key,
				'title'            => $theme,
				'icon'             => 'dashicons dashicons-menu',
				'permissions'      => 'edit_theme_options',
				'class'            => 'quadmenu_theme_' . $key,
				'fields'           => array(
					array(
						'customizer' => false,
						'id'         => $key . '_theme_title',
						'type'       => 'text',
						'title'      => esc_html__( 'Theme', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Change theme name.', 'quadmenu' ),
						'default'    => $theme,
						'validate'   => 'no_special_chars',
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'class'      => $key,
						'id'         => $key . '_layout',
						'type'       => 'image_select',
						'title'      => esc_html__( 'Menu', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Change menu layout.', 'quadmenu' ),
						'options'    => array(
							'embed'     => array(
								'alt' => esc_html__( 'Embed', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/embed.png',
							),
							'collapse'  => array(
								'alt' => esc_html__( 'Collapse', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/collapse.png',
							),
							'offcanvas' => array(
								'alt' => esc_html__( 'Offcanvas', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/offcanvas.png',
							),
							'vertical'  => array(
								'alt' => esc_html__( 'Vertical', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/vertical.png',
							),
							'inherit'   => array(
								'alt' => esc_html__( 'Inherit', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/inherit.png',
							),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_layout' ], array( 'embed', 'collapse', 'offcanvas', 'vertical', 'inherit' ) ),
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_offcanvas_float',
						'type'       => 'image_select',
						'title'      => esc_html__( 'Float', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Vertical menu position.', 'quadmenu' ),
						'options'    => array(
							'left'  => array(
								'alt' => esc_html__( 'Left', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/fleft.png',
							),
							'right' => array(
								'alt' => esc_html__( 'Right', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/fright.png',
							),
						),
						'required'   => array(
							array( $key . '_layout', '=', array( 'offcanvas', 'vertical' ) ),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_layout_offcanvas_float' ], array( 'left', 'right' ) ),
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_align',
						'type'       => 'image_select',
						'title'      => esc_html__( 'Align', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Menu items alignment.', 'quadmenu' ),
						'options'    => array(
							'left'   => array(
								'alt' => esc_html__( 'Left', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/aleft.png',
							),
							'center' => array(
								'alt' => esc_html__( 'Center', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/acenter.png',
							),
							'right'  => array(
								'alt' => esc_html__( 'Right', 'quadmenu' ),
								'img' => QUADMENU_PLUGIN_URL . 'assets/backend/img/layouts/aright.png',
							),
						),
						'required'   => array(
							array( $key . '_layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_layout_align' ], array( 'left', 'right' ) ),
					),
					// Behaviour
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_menu_divider',
						'type'       => 'section',
						'title'      => esc_html__( 'Menu', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_breakpoint',
						'type'       => 'slider',
						'title'      => esc_html__( 'Breakpoint', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Point at which the navbar becomes uncollapsed.', 'quadmenu' ),
						'min'        => '0',
						'step'       => '1',
						'max'        => '1600',
						'required'   => array(
							array( $key . '_layout', '=', array( 'embed', 'collapse', 'offcanvas', 'vertical' ) ),
						),
						'default'    => (int) $this->defaults[ $key . '_layout_breakpoint' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_width',
						'type'       => 'switch',
						'title'      => esc_html__( 'Width', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Try to force menu width to fit screen.', 'quadmenu' ),
						'required'   => array(
							array( $key . '_layout', '=', array( 'collapse', 'offcanvas' ) ),
						),
						'default'    => (int) $this->defaults[ $key . '_layout_width' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_width_inner',
						'type'       => 'switch',
						'title'      => esc_html__( 'Inner', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Try to force menu inner width to fit selector.', 'quadmenu' ),
						'required'   => array(
							array( $key . '_layout', '=', array( 'collapse', 'offcanvas' ) ),
						),
						'default'    => (int) $this->defaults[ $key . '_layout_width_inner' ],
					),
					array(
						'customizer'  => true,
						'transport'   => 'selective',
						'id'          => $key . '_layout_width_inner_selector',
						'type'        => 'text',
						'title'       => esc_html__( 'Selector', 'quadmenu' ),
						'subtitle'    => esc_html__( 'The menu container will take the width of this selector.', 'quadmenu' ),
						'placeholder' => '.container',
						'default'     => $this->defaults[ $key . '_layout_width_inner_selector' ],
						'required'    => array(
							array( $key . '_layout', '=', array( 'collapse', 'offcanvas' ) ),
						),
						'required'    => array(
							array( $key . '_layout_width_inner', '=', 1 ),
						),
					),
					array(
						'customizer'  => true,
						'transport'   => 'selective',
						'id'          => $key . '_layout_lazyload',
						'type'        => 'switch',
						'title'       => esc_html__( 'Lazyload', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Defer images load until they are required.', 'quadmenu' ),
						'default'     => (bool) $this->defaults[ $key . '_layout_lazyload' ],
						'description' => esc_html__( 'This is a beta function, please test it carefully.', 'quadmenu' ),
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_current',
						'type'       => 'switch',
						'title'      => esc_html__( 'Open', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Open dropdown if is current page.', 'quadmenu' ),
						'compiler'   => false,
						'default'    => (bool) $this->defaults[ $key . '_layout_current' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_divider',
						'type'       => 'button_set',
						'title'      => esc_html__( 'Divider', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Show a small divider bar between each menu item.', 'quadmenu' ),
						'options'    => array(
							'show' => esc_html__( 'Show', 'quadmenu' ),
							'hide' => esc_html__( 'Hide', 'quadmenu' ),
						),
						'required'   => array(
							array( $key . '_layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_layout_divider' ], array( 'show', 'hide' ) ),
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_caret',
						'type'       => 'button_set',
						'title'      => esc_html__( 'Caret', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Show carets on items with dropdown menus.', 'quadmenu' ),
						'options'    => array(
							'show' => esc_html__( 'Show', 'quadmenu' ),
							'hide' => esc_html__( 'Hide', 'quadmenu' ),
						),
						'required'   => array(
							array( $key . '_layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_layout_caret' ], array( 'show', 'hide' ) ),
					),
					array(
						'customizer' => false,
						'id'         => $key . '_layout_classes',
						'type'       => 'text',
						'title'      => esc_html__( 'Classes', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Include your own classes in the menu.', 'quadmenu' ),
						'default'    => $this->defaults[ $key . '_layout_classes' ],
						'validate'   => 'no_special_chars',
					),
					// Dropdown
					// ---------------------------------------------------------
					array(
						'id'     => $key . '_dropdown_divider',
						'type'   => 'section',
						'title'  => esc_html__( 'Dropdown', 'quadmenu' ),
						'indent' => false,
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_trigger',
						'type'       => 'button_set',
						'title'      => esc_html__( 'Trigger', 'quadmenu' ),
						'options'    => array(
							'hoverintent' => esc_html__( 'Hover', 'quadmenu' ),
							'click'       => esc_html__( 'Click', 'quadmenu' ),
						),
						'subtitle'   => esc_html__( 'Open dropdown menu on mouseover or click.', 'quadmenu' ),
						'default'    => $this->validate( $this->defaults[ $key . '_layout_trigger' ], array( 'hoverintent', 'click' ) ),
						'required'   => array(
							array( $key . '_layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
						),
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_dropdown_maxheight',
						'type'       => 'switch',
						'title'      => esc_html__( 'Max Height', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Set the max height of dropdowns.', 'quadmenu' ),
						'compiler'   => false,
						'default'    => (bool) $this->defaults[ $key . '_layout_dropdown_maxheight' ],
						'required'   => array(
							array( $key . '_layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
						),
					),
				),
			);

			$sections[] = array(
				// 'customizer' => true,
				'subsection'  => true,
				'heading'     => false,
				'icon'        => 'dashicons dashicons-desktop',
				'id'          => 'quadmenu_navbar_' . $key,
				'title'       => esc_html__( 'Menu', 'quadmenu' ),
				'permissions' => 'edit_theme_options',
				'fields'      => array(
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_navbar_background',
						'type'       => 'button_set',
						'title'      => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the navbar background.', 'quadmenu' ),
						'options'    => array(
							'color'    => esc_html__( 'Color', 'quadmenu' ),
							'gradient' => esc_html__( 'Gradient', 'quadmenu' ),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_navbar_background' ], array( 'color', 'gradient' ) ),
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'id'          => $key . '_navbar_background_color',
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a background color for the navbar.', 'quadmenu' ),
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_navbar_background_color' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'id'          => $key . '_navbar_background_to',
						'title'       => esc_html__( 'Gradient', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a background color for the navbar.', 'quadmenu' ),
						'type'        => 'rgba',
						'transparent' => false,
						'required'    => array(
							$key . '_navbar_background',
							'=',
							'gradient',
						),
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_navbar_background_to' ],
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_navbar_background_deg',
						'title'      => esc_html__( 'Degrees', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Pick a degrees angle for background gradient.', 'quadmenu' ),
						'type'       => 'spinner',
						'min'        => '0',
						'step'       => '1',
						'max'        => '360',
						'validate'   => 'numeric',
						'required'   => array(
							$key . '_navbar_background',
							'=',
							'gradient',
						),
						'default'    => (int) $this->defaults[ $key . '_navbar_background_deg' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Text', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar text.', 'quadmenu' ),
						'id'          => $key . '_navbar_text',
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_text' ],
					),
					// Navbar
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_navbar',
						'type'       => 'section',
						'title'      => esc_html__( 'Menu', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_navbar_height',
						'type'       => 'slider',
						'title'      => esc_html__( 'Height', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Change the items height.', 'quadmenu' ),
						'min'        => '30',
						'step'       => '2',
						'max'        => '160',
						'default'    => (int) $this->defaults[ $key . '_navbar_height' ],
						'validate'   => 'numeric',
					),
					array(
						'compiler' => true,
						'id'       => $key . '_navbar_width',
						'type'     => 'slider',
						'title'    => esc_html__( 'Width', 'quadmenu' ),
						'subtitle' => esc_html__( 'Change the items width.', 'quadmenu' ),
						'min'      => '60',
						'step'     => '1',
						'max'      => '500',
						'default'  => (int) $this->defaults[ $key . '_navbar_width' ],
						'validate' => 'numeric',
					),
					// Logo
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_logo',
						'type'       => 'section',
						'title'      => esc_html__( 'Logo', 'quadmenu' ),
						'indent'     => false,
						// 'required' => array(
						// array($key . '_layout', '=', array('collapse', 'offcanvas', 'vertical', 'inherit')),
						// ),
					),
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_navbar_logo_link',
						'type'       => 'text',
						'title'      => esc_html__( 'Link', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Set navbar logo url.', 'quadmenu' ),
						'default'    => $this->defaults[ $key . '_navbar_logo_link' ],
						'validate'   => 'url',
						// 'required' => array(
						// array($key . '_layout', '=', array('collapse', 'offcanvas', 'vertical', 'inherit')),
						// ),
					),
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_navbar_logo',
						'type'       => 'media',
						'title'      => esc_html__( 'Image', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Upload the navbar logo image.', 'quadmenu' ),
						'default'    => $this->defaults[ $key . '_navbar_logo' ],
						// 'required' => array(
						// array($key . '_layout', '=', array('collapse', 'offcanvas', 'vertical', 'inherit')),
						// ),
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_navbar_logo_height',
						'type'       => 'slider',
						'title'      => esc_html__( 'Height', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Max logo height in px.', 'quadmenu' ),
						'min'        => '20',
						'step'       => '1',
						'max'        => '160',
						'default'    => (int) $this->defaults[ $key . '_navbar_logo_height' ],
						'validate'   => 'numeric',
						// 'required' => array(
						// array($key . '_layout', '=', array('collapse', 'offcanvas', 'vertical', 'inherit')),
						// ),
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'id'          => $key . '_navbar_logo_bg',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a background color for the navbar logo.', 'quadmenu' ),
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_navbar_logo_bg' ],
						// 'required' => array(
						// array($key . '_layout', '=', array('collapse', 'offcanvas', 'vertical', 'inherit')),
						// ),
					),
					// Layout
					// ---------------------------------------------------------
					array(
						'customizer' => false,
						'transport'  => 'postMessage',
						'id'         => $key . '_navbar_layout',
						'type'       => 'section',
						'title'      => esc_html__( 'Layout', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'   => true,
						'customizer' => false,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Margin', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Set the margin for the navbar links.', 'quadmenu' ),
						'id'         => $key . '_navbar_link_margin',
						'type'       => 'border',
						'all'        => false,
						'style'      => false,
						'color'      => false,
						'default'    => $this->validate_border( $this->defaults[ $key . '_navbar_link_margin' ] ),
					),
					array(
						'compiler'   => true,
						'customizer' => false,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Radius', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Set the radius for the navbar links.', 'quadmenu' ),
						'id'         => $key . '_navbar_link_radius',
						'type'       => 'border',
						'all'        => false,
						'style'      => false,
						'color'      => false,
						'default'    => $this->defaults[ $key . '_navbar_link_radius' ],
					),
					// Link
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_link',
						'type'       => 'section',
						'title'      => esc_html__( 'Links', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar links.', 'quadmenu' ),
						'id'          => $key . '_navbar_link',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_link' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar background.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_bg',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_navbar_link_bg' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar links on mousehover.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_link_hover' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar background on mousehover.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_bg_hover',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_navbar_link_bg_hover' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Divider', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar links divider.', 'quadmenu' ),
						'id'          => $key . '_navbar_divider',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_navbar_divider' ],
					),
					array(
						'compiler'    => true,
						'title'       => esc_html__( 'Effect', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar link effect on mousehover.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_hover_effect',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_navbar_link_hover_effect' ],
					),
					// Icon
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_icon',
						'type'       => 'section',
						'title'      => esc_html__( 'Icon', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Icon', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar links icon.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_icon',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_link_icon' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar links icon on hover.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_icon_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_link_icon_hover' ],
					),
					// Subtitle
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_subtitle',
						'type'       => 'section',
						'title'      => esc_html__( 'Subtitle', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar links subtitle.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_subtitle',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_link_subtitle' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar links subtitle on hover.', 'quadmenu' ),
						'id'          => $key . '_navbar_link_subtitle_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_link_subtitle_hover' ],
					),
					// Badge
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_badge',
						'type'       => 'section',
						'title'      => esc_html__( 'Badge', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the badge.', 'quadmenu' ),
						'id'          => $key . '_navbar_badge_color',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_badge_color' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a background color for the badge.', 'quadmenu' ),
						'id'          => $key . '_navbar_badge',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_badge' ],
					),
					// Button
					// ---------------------------------------------------------
					array(
						'id'     => $key . '_button',
						'type'   => 'section',
						'title'  => esc_html__( 'Button', 'quadmenu' ),
						'indent' => false,
					),
					array(
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button.', 'quadmenu' ),
						'id'          => $key . '_navbar_button',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'compiler'    => true,
						'default'     => $this->defaults[ $key . '_navbar_button' ],
					),
					array(
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button.', 'quadmenu' ),
						'id'          => $key . '_navbar_button_background',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'compiler'    => true,
						'default'     => $this->defaults[ $key . '_navbar_button_background' ],
					),
					array(
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button on mousehover.', 'quadmenu' ),
						'id'          => $key . '_navbar_button_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'compiler'    => true,
						'default'     => $this->defaults[ $key . '_navbar_button_hover' ],
					),
					array(
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button on mousehover.', 'quadmenu' ),
						'id'          => $key . '_navbar_button_hover_background',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'compiler'    => true,
						'default'     => $this->defaults[ $key . '_navbar_button_hover_background' ],
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Radius', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Button border radius.', 'quadmenu' ),
						'id'         => $key . '_navbar_button_radius',
						'type'       => 'border',
						'all'        => false,
						'style'      => false,
						'color'      => false,
						'default'    => $this->validate_border( $this->defaults[ $key . '_navbar_button_radius' ] ),
					),
					// Scrollbar
					// ---------------------------------------------------------
					array(
						'customizer' => false,
						'id'         => $key . '_scrollbar',
						'type'       => 'section',
						'title'      => esc_html__( 'ScrollBar', 'quadmenu' ),
						'indent'     => false,
						'required'   => array(
							'styles_pscrollbar',
							'=',
							true,
						),
					),
					array(
						'compiler'    => true,
						'customizer'  => false,
						'title'       => esc_html__( 'Scroll', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the scrollbar.', 'quadmenu' ),
						'id'          => $key . '_navbar_scrollbar',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						'default'     => $this->defaults[ $key . '_navbar_scrollbar' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => false,
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the scrollbar rail.', 'quadmenu' ),
						'id'          => $key . '_navbar_scrollbar_rail',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						'default'     => $this->defaults[ $key . '_navbar_scrollbar_rail' ],
					),
				),
			);

			$sections[] = array(
				// 'customizer' => true,
				'subsection'  => true,
				'heading'     => false,
				'icon'        => 'dashicons dashicons-sticky',
				'id'          => 'quadmenu_sticky_' . $key,
				'title'       => esc_html__( 'Sticky', 'quadmenu' ),
				'permissions' => 'edit_theme_options',
				'fields'      => array(
					// Sticky
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_sticky',
						'type'       => 'switch',
						'title'      => esc_html__( 'Sticky', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Make the menu sticky on scroll.', 'quadmenu' ),
						'default'    => (int) $this->defaults[ $key . '_layout_sticky' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_sticky_offset',
						'type'       => 'slider',
						'title'      => esc_html__( 'Offset', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Set the length of the scroll for each user to pass before the menu will stick to the top of the window.', 'quadmenu' ),
						'min'        => '0',
						'step'       => '1',
						'max'        => '340',
						'required'   => array(
							$key . '_layout_sticky',
							'=',
							1,
						),
						'default'    => (int) $this->defaults[ $key . '_layout_sticky_offset' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'id'          => $key . '_sticky_background',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Change the navbar sticky background.', 'quadmenu' ),
						'type'        => 'rgba',
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'transparent' => false,
						'default'     => $this->defaults[ $key . '_sticky_background' ],
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_sticky_height',
						'type'       => 'slider',
						'title'      => esc_html__( 'Height', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Change the navbar sticky height.', 'quadmenu' ),
						'min'        => '30',
						'step'       => '2',
						'max'        => '160',
						'default'    => $this->defaults[ $key . '_sticky_height' ],
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_sticky_logo_height',
						'type'       => 'slider',
						'title'      => esc_html__( 'Logo', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Max logo height in px.', 'quadmenu' ),
						'min'        => '20',
						'step'       => '1',
						'max'        => '160',
						'default'    => $this->defaults[ $key . '_sticky_logo_height' ],
					),
				),
			);

			$sections[] = array(
				// 'customizer' => true,
				'subsection'  => true,
				'heading'     => false,
				'icon'        => 'dashicons dashicons-tablet',
				'id'          => 'quadmenu_mobile_' . $key,
				'title'       => esc_html__( 'Mobile', 'quadmenu' ),
				'permissions' => 'edit_theme_options',
				'fields'      => array(
					// Mobile
					// ---------------------------------------------------------
					/*
					 array(
						'id' => $key . '_mobile',
						'type' => 'section',
						'title' => esc_html__('Mobile', 'quadmenu'),
						'indent' => true
					  ), */
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_mobile_shadow',
						'type'       => 'button_set',
						'title'      => esc_html__( 'Shadow', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Display shadow on mobile navbar.', 'quadmenu' ),
						'options'    => array(
							'show' => esc_html__( 'Show', 'quadmenu' ),
							'hide' => esc_html__( 'Hide', 'quadmenu' ),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_mobile_shadow' ], array( 'show', 'hide' ) ),
					),
					array(
						'compiler'    => true,
						'title'       => esc_html__( 'Border', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the border separator.', 'quadmenu' ),
						'id'          => $key . '_navbar_mobile_border',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						'default'     => $this->defaults[ $key . '_navbar_mobile_border' ],
					),
					// Toggle
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_toggle',
						'type'       => 'section',
						'title'      => esc_html__( 'Toggle', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Open', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the toggle icon.', 'quadmenu' ),
						'id'          => $key . '_navbar_toggle_open',
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_toggle_open' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Close', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the toggle button background on hover.', 'quadmenu' ),
						'id'          => $key . '_navbar_toggle_close',
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_navbar_toggle_close' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_mobile_link',
						'type'       => 'section',
						'title'      => esc_html__( 'Link', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'   => true,
						'customizer' => false,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Margin', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Set the margin for the mobile links.', 'quadmenu' ),
						'id'         => $key . '_mobile_link_padding',
						'type'       => 'border',
						'all'        => false,
						'style'      => false,
						'color'      => false,
						'default'    => $this->defaults[ $key . '_mobile_link_padding' ],
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Border', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Pick a border color for the mobile menu links border.', 'quadmenu' ),
						'id'         => $key . '_mobile_link_border',
						'type'       => 'border',
						'default'    => $this->defaults[ $key . '_mobile_link_border' ],
					),
				),
			);

			$sections[] = array(
				// 'customizer' => true,
				'subsection'  => true,
				'heading'     => false,
				'icon'        => 'dashicons dashicons-editor-break',
				'id'          => 'quadmenu_dropdown_' . $key,
				'title'       => esc_html__( 'Dropdown', 'quadmenu' ),
				'permissions' => 'edit_theme_options',
				'fields'      => array(
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_dropdown_shadow',
						'type'       => 'button_set',
						'title'      => esc_html__( 'Shadow', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Display shadow on dropdown menus.', 'quadmenu' ),
						'options'    => array(
							'show' => esc_html__( 'Show', 'quadmenu' ),
							'hide' => esc_html__( 'Hide', 'quadmenu' ),
						),
						'default'    => $this->validate( $this->defaults[ $key . '_dropdown_shadow' ], array( 'show', 'hide' ) ),
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_dropdown_margin',
						'type'       => 'spinner',
						'title'      => esc_html__( 'Margin', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Dropdown margin top.', 'quadmenu' ),
						'min'        => '0',
						'step'       => '1',
						'max'        => '45',
						'default'    => (int) $this->defaults[ $key . '_dropdown_margin' ],
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Radius', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Dropdown border radius.', 'quadmenu' ),
						'id'         => $key . '_dropdown_radius',
						'type'       => 'border',
						'all'        => false,
						'style'      => false,
						'color'      => false,
						'default'    => $this->validate_border( $this->defaults[ $key . '_dropdown_radius' ] ),
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Border', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Pick a border color for the dropdown.', 'quadmenu' ),
						'id'         => $key . '_dropdown_border',
						'type'       => 'border',
						'all'        => false,
						'style'      => false,
						'default'    => $this->validate_border( $this->defaults[ $key . '_dropdown_border' ] ),
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a background color for the dropdown menu.', 'quadmenu' ),
						'id'          => $key . '_dropdown_background',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_dropdown_background' ],
					),
					// Link
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_dropdown_link_section',
						'type'       => 'section',
						'title'      => esc_html__( 'Link', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the dropdown menu links.', 'quadmenu' ),
						'id'          => $key . '_dropdown_link',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_link' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the dropdown menu links on mousehover.', 'quadmenu' ),
						'id'          => $key . '_dropdown_link_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_link_hover' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a background color for the links on mouseover.', 'quadmenu' ),
						'id'          => $key . '_dropdown_link_bg_hover',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						// 'options' => array(
						// 'allow_empty' => false,
						// ),
						'default'     => $this->defaults[ $key . '_dropdown_link_bg_hover' ],
					),
					/*
					 array(
						'compiler' => true,
						'customizer' => false,
						'transport' => 'postMessage',
						'title' => esc_html__('Margin', 'quadmenu'),
						'subtitle' => esc_html__('Set the margin for the navbar links.', 'quadmenu'),
						'id' => $key . '_dropdown_link_padding',
						'type' => 'border',
						'all' => false,
						'style' => false,
						'color' => false,
						'default' => $this->defaults[$key . '_dropdown_link_padding']
					  ), */
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Border', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Pick a border color for the dropdown menu links border.', 'quadmenu' ),
						'id'         => $key . '_dropdown_link_border',
						'type'       => 'border',
						'default'    => $this->defaults[ $key . '_dropdown_link_border' ],
					),
					// Title
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_title',
						'type'       => 'section',
						'title'      => esc_html__( 'Title', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Title', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the dropdown menu title.', 'quadmenu' ),
						'id'          => $key . '_dropdown_title',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_title' ],
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Border', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Pick a color for the dropdown menu title border.', 'quadmenu' ),
						'id'         => $key . '_dropdown_title_border',
						'type'       => 'border',
						'default'    => $this->defaults[ $key . '_dropdown_title_border' ],
					),
					// Icon
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_icon',
						'type'       => 'section',
						'title'      => esc_html__( 'Icon', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Icon', 'quadmenu' ),
						'icon'        => esc_html__( 'Pick a color for the dropdown links icon.', 'quadmenu' ),
						'id'          => $key . '_dropdown_link_icon',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_link_icon' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'icon'        => esc_html__( 'Pick a color for the dropdown links icon on hover.', 'quadmenu' ),
						'id'          => $key . '_dropdown_link_icon_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_link_icon_hover' ],
					),
					// Subtitle
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_subtitle',
						'type'       => 'section',
						'title'      => esc_html__( 'Subtitle', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the dropdown links subtitle.', 'quadmenu' ),
						'id'          => $key . '_dropdown_link_subtitle',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_link_subtitle' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the dropdown links subtitle on hover.', 'quadmenu' ),
						'id'          => $key . '_dropdown_link_subtitle_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_link_subtitle_hover' ],
					),
					// Button
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_button',
						'type'       => 'section',
						'title'      => esc_html__( 'Button', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Color', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button.', 'quadmenu' ),
						'id'          => $key . '_dropdown_button',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_button' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button.', 'quadmenu' ),
						'id'          => $key . '_dropdown_button_bg',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_button_bg' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button on hover.', 'quadmenu' ),
						'id'          => $key . '_dropdown_button_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_button_hover' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar button on hover.', 'quadmenu' ),
						'id'          => $key . '_dropdown_button_bg_hover',
						'type'        => 'color',
						'transparent' => false,
						// 'validate' => 'color',
						'validate'    => 'not_empty',
						'default'     => $this->defaults[ $key . '_dropdown_button_bg_hover' ],
					),
					/*
					 array(
						'compiler' => true,
						'customizer' => true,
						'transport' => 'postMessage',
						'title' => esc_html__('Radius', 'quadmenu'),
						'subtitle' => esc_html__('Dropdown button border radius.', 'quadmenu'),
						'id' => $key . '_dropdown_button_radius',
						'type' => 'border',
						'all' => false,
						'style' => false,
						'color' => false,
						'default' => $this->validate_border($this->defaults[$key . '_dropdown_button_radius'])
					  ),
					  */
					// Tab
					// ---------------------------------------------------------
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_tab',
						'type'       => 'section',
						'title'      => esc_html__( 'Tab', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar tab.', 'quadmenu' ),
						'id'          => $key . '_dropdown_tab_bg',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						'transparent' => false,
						'default'     => $this->defaults[ $key . '_dropdown_tab_bg' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => true,
						'transport'   => 'postMessage',
						'title'       => esc_html__( 'Hover', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the navbar tab on hover.', 'quadmenu' ),
						'id'          => $key . '_dropdown_tab_bg_hover',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						'transparent' => false,
						'default'     => $this->defaults[ $key . '_dropdown_tab_bg_hover' ],
					),
					// Scrollbar
					// ---------------------------------------------------------
					array(
						'customizer' => false,
						'id'         => $key . '_dropdown_scrollbar_section',
						'type'       => 'section',
						'title'      => esc_html__( 'ScrollBar', 'quadmenu' ),
						'indent'     => false,
						'required'   => array(
							'styles_pscrollbar',
							'=',
							true,
						),
					),
					array(
						'compiler'    => true,
						'customizer'  => false,
						'title'       => esc_html__( 'Scroll', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the scrollbar.', 'quadmenu' ),
						'id'          => $key . '_dropdown_scrollbar',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						'default'     => $this->defaults[ $key . '_dropdown_scrollbar' ],
					),
					array(
						'compiler'    => true,
						'customizer'  => false,
						'title'       => esc_html__( 'Background', 'quadmenu' ),
						'subtitle'    => esc_html__( 'Pick a color for the scrollbar rail.', 'quadmenu' ),
						'id'          => $key . '_dropdown_scrollbar_rail',
						'type'        => 'rgba',
						'transparent' => false,
						'validate'    => 'colorrgba',
						'default'     => $this->defaults[ $key . '_dropdown_scrollbar_rail' ],
					),
				),
			);

			$sections[] = array(
				'subsection'  => true,
				'heading'     => false,
				'icon'        => 'dashicons dashicons-editor-paste-text',
				'id'          => 'quadmenu_typography_' . $key,
				'title'       => esc_html__( 'Typography', 'quadmenu' ),
				'permissions' => 'edit_theme_options',
				'fields'      => array(
					array(
						'compiler'       => true,
						'id'             => $key . '_font',
						'type'           => 'typography',
						'title'          => esc_html__( 'General', 'quadmenu' ),
						'subtitle'       => esc_html__( 'Specify the font properties.', 'quadmenu' ),
						'font-weight'    => true,
						'font-size'      => true,
						'font-style'     => true,
						'letter-spacing' => true,
						'line-height'    => false,
						'google'         => true,
						'text-align'     => false,
						'color'          => false,
						'subsets'        => true,
						'default'        => $this->validate_font( $this->defaults[ $key . '_font' ] ),
					),
					array(
						'compiler'       => true,
						'id'             => $key . '_navbar_font',
						'type'           => 'typography',
						'title'          => esc_html__( 'Menu', 'quadmenu' ),
						'subtitle'       => esc_html__( 'Specify the font properties.', 'quadmenu' ),
						'font-weight'    => true,
						'font-size'      => true,
						'font-style'     => true,
						'line-height'    => false,
						'letter-spacing' => true,
						'google'         => true,
						'text-align'     => false,
						'color'          => false,
						'subsets'        => true,
						'default'        => $this->validate_font( $this->defaults[ $key . '_navbar_font' ] ),
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Transform', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Pick a text transform for the link.', 'quadmenu' ),
						'id'         => $key . '_navbar_link_transform',
						'type'       => 'select',
						'options'    => array(
							'none'       => esc_html__( 'None', 'quadmenu' ),
							'capitalize' => esc_html__( 'Capitalize', 'quadmenu' ),
							'uppercase'  => esc_html__( 'Uppercase', 'quadmenu' ),
							'lowercase'  => esc_html__( 'Lowercase', 'quadmenu' ),
						),
						'default'    => $this->defaults[ $key . '_navbar_link_transform' ],
					),
					array(
						'compiler'       => true,
						'id'             => $key . '_dropdown_font',
						'type'           => 'typography',
						'title'          => esc_html__( 'Submenu', 'quadmenu' ),
						'subtitle'       => esc_html__( 'Specify the font properties.', 'quadmenu' ),
						'font-weight'    => true,
						'font-size'      => true,
						'font-style'     => true,
						'line-height'    => false,
						'letter-spacing' => true,
						'google'         => true,
						'text-align'     => false,
						'color'          => false,
						'subsets'        => true,
						'default'        => $this->validate_font( $this->defaults[ $key . '_dropdown_font' ] ),
					),
					array(
						'compiler'   => true,
						'customizer' => true,
						'transport'  => 'postMessage',
						'title'      => esc_html__( 'Transform', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Pick a text transform for the link.', 'quadmenu' ),
						'id'         => $key . '_dropdown_link_transform',
						'type'       => 'select',
						'options'    => array(
							'none'       => esc_html__( 'None', 'quadmenu' ),
							'capitalize' => esc_html__( 'Capitalize', 'quadmenu' ),
							'uppercase'  => esc_html__( 'Uppercase', 'quadmenu' ),
							'lowercase'  => esc_html__( 'Lowercase', 'quadmenu' ),
						),
						'default'    => $this->defaults[ $key . '_dropdown_link_transform' ],
					),
				),
			);

			$sections[] = array(
				'subsection'  => true,
				'heading'     => false,
				'icon'        => 'dashicons dashicons-format-video',
				'id'          => 'quadmenu_animations_' . $key,
				'title'       => esc_html__( 'Animations', 'quadmenu' ),
				'permissions' => 'edit_theme_options',
				'fields'      => array(
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_hover_effect',
						'type'       => 'select',
						'title'      => esc_html__( 'Effect', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Add an amazing effect for the hover event.', 'quadmenu' ),
						'options'    => array(
							'quadmenu-hover-ripple' => esc_html__( 'Ripple', 'quadmenu' ),
							// 'quadmenu-hover-text-top' => esc_html__('Text Top', 'quadmenu'),
							'quadmenu-hover-slidebar sl-top' => esc_html__( 'SlideBar Top (Horizontal)', 'quadmenu' ),
							'quadmenu-hover-slidebar sl-middle' => esc_html__( 'SlideBar Middle (Horizontal)', 'quadmenu' ),
							'quadmenu-hover-slidebar sl-bottom' => esc_html__( 'SlideBar Bottom (Horizontal)', 'quadmenu' ),
						),
						'required'   => array(
							array( $key . '_layout', '=', array( 'collapse', 'offcanvas', 'embed' ) ),
						),
						'default'    => $this->defaults[ $key . '_layout_hover_effect' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_navbar_animation_text',
						'type'       => 'animation',
						'title'      => esc_html__( 'Text', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the link text.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_navbar_animation_text' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_navbar_animation_subtitle',
						'type'       => 'animation',
						'title'      => esc_html__( 'Subtitle', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the link subtitle.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_navbar_animation_subtitle' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_navbar_animation_icon',
						'type'       => 'animation',
						'title'      => esc_html__( 'Icon', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the links icons.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_navbar_animation_icon' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_navbar_animation_badge',
						'type'       => 'animation',
						'title'      => esc_html__( 'Badge', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the links badges.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_navbar_animation_badge' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_navbar_animation_cart',
						'type'       => 'animation',
						'title'      => esc_html__( 'Cart', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the cart bubble.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'speed'      => array(
							't_200' => 200,
							't_300' => 300,
							't_500' => 500,
							't_700' => 700,
						),
						'default'    => $this->defaults[ $key . '_navbar_animation_cart' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'postMessage',
						'id'         => $key . '_dropdown',
						'type'       => 'section',
						'title'      => esc_html__( 'Dropdown', 'quadmenu' ),
						'indent'     => false,
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_layout_animation',
						'type'       => 'animation',
						'title'      => esc_html__( 'Dropdown', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the dropdowns.', 'quadmenu' ),
						'options'    => array(
							'quadmenu_fadeIn' => esc_html__( 'FadeIn', 'quadmenu' ),
							'quadmenu_btt'    => esc_html__( 'Bottom to top', 'quadmenu' ),
							'quadmenu_rtl'    => esc_html__( 'Right to left', 'quadmenu' ),
							'quadmenu_ltr'    => esc_html__( 'Left to right', 'quadmenu' ),
							'quadmenu_hinge'  => esc_html__( 'Hinge', 'quadmenu' ),
							'quadmenu_flip'   => esc_html__( 'Flip', 'quadmenu' ),
						),
						'speed'      => array(
							't_100'  => 100,
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
						),
						'default'    => $this->defaults[ $key . '_layout_animation' ],
						'required'   => array(
							array( $key . '_layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
						),
						'validate'   => 'no_special_chars',
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_dropdown_animation_text',
						'type'       => 'animation',
						'title'      => esc_html__( 'Text', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the menu links.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_dropdown_animation_text' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_dropdown_animation_subtitle',
						'type'       => 'animation',
						'title'      => esc_html__( 'Subtitle', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the menu links.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_dropdown_animation_subtitle' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_dropdown_animation_icon',
						'type'       => 'animation',
						'title'      => esc_html__( 'Icon', 'quadmenu' ),
						'subtitle'   => esc_html__( 'Select the animation for the menu links.', 'quadmenu' ),
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_dropdown_animation_icon' ],
					),
					array(
						'customizer' => true,
						'transport'  => 'selective',
						'id'         => $key . '_dropdown_animation_badge',
						'subtitle'   => esc_html__( 'Select the animation for the menu links.', 'quadmenu' ),
						'title'      => esc_html__( 'Badge', 'quadmenu' ),
						'type'       => 'animation',
						'options'    => array(
							''                    => esc_html__( 'None', 'quadmenu' ),
							'quadmenu_bounce'     => esc_html__( 'Bounce', 'quadmenu' ),
							'quadmenu_pulse'      => esc_html__( 'Pulse', 'quadmenu' ),
							'quadmenu_rubberBand' => esc_html__( 'Rubber', 'quadmenu' ),
							'quadmenu_swing'      => esc_html__( 'Swing', 'quadmenu' ),
							'quadmenu_tada'       => esc_html__( 'Tada', 'quadmenu' ),
							'quadmenu_wobble'     => esc_html__( 'Wobble', 'quadmenu' ),
						),
						'action'     => array(
							'load'  => esc_html__( 'Load', 'quadmenu' ),
							'hover' => esc_html__( 'Hover', 'quadmenu' ),
							'loop'  => esc_html__( 'Loop', 'quadmenu' ),
						),
						'speed'      => array(
							't_200'  => 200,
							't_300'  => 300,
							't_500'  => 500,
							't_700'  => 700,
							't_1000' => 1000,
							't_1500' => 1500,
							't_2000' => 2000,
						),
						'default'    => $this->defaults[ $key . '_dropdown_animation_badge' ],
					),
				),
			);
		}

		return $sections;
	}

	function validate( $option, $defaults ) {

		if ( ! $option || ! in_array( sanitize_key( $option ), $defaults ) ) {
			return reset( $defaults );
		}

		return $option;
	}

	function validate_font( $options ) {

		$defaults = array(
			'font-family'    => 'Verdana, Geneva, sans-serif',
			'font-size'      => '11',
			'font-style'     => 'normal',
			'font-weight'    => '400',
			'letter-spacing' => 'inherit',
		);

		return wp_parse_args( (array) $options, $defaults );
	}

	function validate_border( $options ) {

		if ( ! is_array( $options ) ) {
			$options = array(
				'border-all'    => $options,
				'border-top'    => $options,
				'border-right'  => $options,
				'border-left'   => $options,
				'border-bottom' => $options,
			);
		}

		$defaults = array(
			'border-all'    => '0',
			'border-top'    => '0',
			'border-right'  => '0',
			'border-left'   => '0',
			'border-bottom' => '0',
			'border-color'  => '#000000',
		);

		return wp_parse_args( (array) $options, $defaults );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
