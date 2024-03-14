<?php

class FLQuadMenuModule extends FLBuilderModule {

	public static $fl_builder_page_id;

	public function __construct() {

        parent::__construct(
            array(
                'name'            => __( 'QuadMenu', 'quadmenu' ),
                'description'     => __( 'Renders a WordPress menu.', 'quadmenu' ),
                'category'        => __( 'Actions', 'quadmenu' ),
                'partial_refresh' => true,
                'editor_export'   => false,
                'icon'            => 'hamburger-menu.svg',
            )
        );

	  add_action( 'pre_get_posts', __CLASS__ . '::set_pre_get_posts_query', 10, 2 );
	}

	public static function get_attachment_data( $id ) {
	  $data = wp_prepare_attachment_for_js( $id );

		if ( gettype( $data ) == 'array' ) {
		  return json_decode( json_encode( $data ) );
		}

	  return $data;
	}

	public static function _get_menus() {
	$get_menus = get_terms(
		'nav_menu',
		array(
			'hide_empty' => true,
		)
	);
	  $fields  = array(
		  'type'   => 'select',
		  'label'  => __( 'Menu', 'quadmenu' ),
		  'helper' => __( 'Select a WordPress menu that you created in the admin under Appearance > Menus.', 'quadmenu' ),
	  );

		if ( $get_menus ) {

			foreach ( $get_menus as $key => $menu ) {

				if ( 0 == $key ) {
					$fields['default'] = $menu->name;
				}

			  $menus[ $menu->slug ] = $menu->name;
			}

		  $fields['options'] = $menus;
		} else {
		  $fields['options'] = array(
			  '' => __( 'No Menus Found', 'quadmenu' ),
		  );
		}

	  return $fields;
	}

	public static function set_pre_get_posts_query( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {

			if ( $query->queried_object_id ) {

			  self::$fl_builder_page_id = $query->queried_object_id;

			  // Fix when menu module is rendered via hook
			} elseif ( isset( $query->query_vars['page_id'] ) && 0 != $query->query_vars['page_id'] ) {

			  self::$fl_builder_page_id = $query->query_vars['page_id'];
			}
		}
	}

}

FLBuilder::register_module(
	'FLQuadMenuModule',
	array(
		'general'  => array(// Tab
			'title'    => __( 'General', 'quadmenu' ), // Tab title
			'sections' => array(// Tab Sections
				'general' => array(// Section
					'title'  => '', // Section Title
					'fields' => array(// Section Fields
						'menu'   => FLQuadMenuModule::_get_menus(),
						'layout' => array(
							'type'    => 'select',
							'label'   => __( 'Layout', 'quadmenu' ),
							'default' => 'collapse',
							'options' => array(
								// 'embed' => esc_html__('Embed', 'quadmenu'),
								'collapse'  => esc_html__( 'Collapse', 'quadmenu' ),
								'offcanvas' => esc_html__( 'Offcanvas', 'quadmenu' ),
								// 'vertical' => esc_html__('Vertical', 'quadmenu'),
								'inherit'   => esc_html__( 'Inherit', 'quadmenu' ),
							),
						),
						'theme'  => array(
							'type'    => 'select',
							'label'   => __( 'Theme', 'quadmenu' ),
							'default' => 'default_theme',
							'options' => $GLOBALS['quadmenu_themes'],
						),
					),
				),
				'layout'  => array(
					'title'  => __( 'Layout', 'quadmenu' ),
					'fields' => array(
						'navbar_logo'                 => array(
							'type'        => 'photo',
							'label'       => esc_html__( 'Logo', 'quadmenu' ),
							'connections' => array( 'photo' ),
							'show_remove' => true,
							'default'     => QUADMENU_PLUGIN_URL . 'assets/frontend/images/logo.png',
						),
						/*
						'navbar_logo_height' => array(
							'type' => 'number',
							'label' => esc_html__('Height', 'quadmenu'),
							'description' => esc_html__('Max logo height in px.', 'quadmenu'),
							'required' => array(
								array('layout', '=', array('collapse', 'offcanvas', 'vertical', 'inherit')),
							),
							'default' => QUADMENU_PLUGIN_URL . 'assets/frontend/images/logo.png'
						),*/
						'layout_align'                => array(
							'customizer' => true,
							'transport'  => 'selective',
							'id'         => 'layout_align',
							'type'       => 'select',
							'label'      => esc_html__( 'Align', 'quadmenu' ),
							'subtitle'   => esc_html__( 'Menu items alignment.', 'quadmenu' ),
							'options'    => array(
								'left'   => esc_html__( 'Left', 'quadmenu' ),
								'center' => esc_html__( 'Center', 'quadmenu' ),
								'right'  => esc_html__( 'Right', 'quadmenu' ),
							),
							'required'   => array(
								array( 'layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
							),
							'default'    => 'left',
						),
						// Behaviour
						// ---------------------------------------------------------
						'layout_breakpoint'           => array(
							'type'        => 'text',
							'label'       => esc_html__( 'Breakpoint', 'quadmenu' ),
							'default'     => '768',
							'maxlength'   => '4',
							'size'        => '4',
							'description' => 'px',
							'preview'     => array(
								// 'type' => 'css',
								// 'selector' => '.menu',
								// 'property' => 'font-size',
								'unit' => 'px',
							),
						),
						'layout_width'                => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Width', 'quadmenu' ),
							'description' => esc_html__( 'Try to force menu width to fit screen.', 'quadmenu' ),
							'options'     => array(
								'yes'   => esc_html__( 'Yes', 'quadmenu' ),
								'false' => esc_html__( 'No', 'quadmenu' ),
							),
							'required'    => array(
								array( 'layout', '=', array( 'collapse', 'offcanvas' ) ),
							),
							'default'     => 'false',
						),
						'layout_width_inner'          => array(
							'type'     => 'select',
							'label'    => esc_html__( 'Inner', 'quadmenu' ),
							'options'  => array(
								'yes'   => esc_html__( 'Yes', 'quadmenu' ),
								'false' => esc_html__( 'No', 'quadmenu' ),
							),
							'required' => array(
								array( 'layout', '=', array( 'collapse', 'offcanvas' ) ),
							),
							'default'  => 'false',
						),
						'layout_width_inner_selector' => array(
							'type'        => 'text',
							'label'       => esc_html__( 'Selector', 'quadmenu' ),
							'description' => esc_html__( 'The menu container will take the width of this selector.', 'quadmenu' ),
							'default'     => '.container',
							'required'    => array(
								array( 'layout', '=', array( 'collapse', 'offcanvas' ) ),
							),
							'required'    => array(
								array( 'layout_width_inner', '=', 1 ),
							),
						),
						'layout_lazyload'             => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Lazyload', 'quadmenu' ),
							'options'     => array(
								'yes'   => esc_html__( 'Yes', 'quadmenu' ),
								'false' => esc_html__( 'No', 'quadmenu' ),
							),
							'default'     => 'false',
							'description' => esc_html__( 'This is a beta function, please test it carefully.', 'quadmenu' ),
						),
						'layout_current'              => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Open', 'quadmenu' ),
							'options'     => array(
								'yes'   => esc_html__( 'Yes', 'quadmenu' ),
								'false' => esc_html__( 'No', 'quadmenu' ),
							),
							'default'     => 'false',
							'description' => esc_html__( 'Open dropdown if is current page.', 'quadmenu' ),
						),
						'layout_divider'              => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Divider', 'quadmenu' ),
							'description' => esc_html__( 'Show a small divider bar between each menu item.', 'quadmenu' ),
							'options'     => array(
								'show' => esc_html__( 'Show', 'quadmenu' ),
								'hide' => esc_html__( 'Hide', 'quadmenu' ),
							),
							'required'    => array(
								array( 'layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
							),
							'default'     => 'hide',
						),
						'layout_caret'                => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Caret', 'quadmenu' ),
							'description' => esc_html__( 'Show carets on items with dropdown menus.', 'quadmenu' ),
							'options'     => array(
								'show' => esc_html__( 'Show', 'quadmenu' ),
								'hide' => esc_html__( 'Hide', 'quadmenu' ),
							),
							'required'    => array(
								array( 'layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
							),
							'default'     => 'hide',
						),
						'layout_classes'              => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Classes', 'quadmenu' ),
							'default' => '',
						),
					),
				),
			),
		),
		// Dropdown
		// ---------------------------------------------------------
		'dropdown' => array(// Tab
			'title'    => __( 'Dropdown', 'quadmenu' ), // Tab title
			'sections' => array(// Tab Sections
				'general_style' => array(
					'title'  => '',
					'fields' => array(
						'layout_trigger'            => array(
							'type'     => 'select',
							'label'    => esc_html__( 'Trigger', 'quadmenu' ),
							'options'  => array(
								'hoverintent' => esc_html__( 'Hover', 'quadmenu' ),
								'click'       => esc_html__( 'Click', 'quadmenu' ),
							),
							'subtitle' => esc_html__( 'Open dropdown menu on mouseover or click.', 'quadmenu' ),
							'default'  => 'hoverintent',
							'required' => array(
								array( 'layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
							),
						),
						'layout_dropdown_maxheight' => array(
							'type'     => 'select',
							'label'    => esc_html__( 'Max Height', 'quadmenu' ),
							'subtitle' => esc_html__( 'Set the max height of dropdowns.', 'quadmenu' ),
							'options'  => array(
								'yes'   => esc_html__( 'Yes', 'quadmenu' ),
								'false' => esc_html__( 'No', 'quadmenu' ),
							),
							'default'  => 'false',
							'required' => array(
								array( 'layout', '=', array( 'embed', 'collapse', 'offcanvas' ) ),
							),
						),
					),
				),
			),
		),
	)
);
