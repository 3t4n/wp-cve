<?php

class ET_Builder_Module_QuadMenu extends \ET_Builder_Module {

	public $slug              = 'et_pb_quadmenu';
	public $vb_support        = 'on';
	public $fullwidth         = true;
	public $main_css_element  = '%%order_class%%.et_pb_quadmenu';
	protected $module_credits = array(
		'module_uri' => 'https://quadmenu.com',
		'author'     => 'QuadLayers',
		'author_uri' => 'https://www.quadlayers.com',
	);

	function init() {
		$this->name = esc_html__( 'QuadMenu', 'quadmenu' );

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Settings', 'quadmenu' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'width' => array(
						'title'    => esc_html__( 'Width', 'quadmenu' ),
						'priority' => 65,
					),
				),
			),
		);

		$this->advanced_fields = array(
			// 'background' => array(
			// 'background_image' => false,
			// ),
			'link'           => false,
			'spacing'        => false,
			'filters'        => false,
			'animation'      => false,
			'text'           => false,
			'borders'        => array(
				'default' => false,
			),
			'margin_padding' => array(
				'css' => array(
					'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling
				),
			),
			'text_shadow'    => array(
				// Don't add text-shadow fields since they already are via font-options
				'default' => false,
			),
			'box_shadow'     => array(
				'default' => false,
			),
			'fonts'          => false,
			'button'         => false,
		);
	}

	function get_fields() {

		$menus = et_builder_get_nav_menus_options();

		$menu_id = array_keys( $menus )[1];

		$fields = array(
			'menu_id'    => array(
				'label'           => esc_html__( 'Menu', 'quadmenu' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => $menus,
				'description'     => sprintf( '%s <a href="%s" target="_blank">%s</a>', esc_html__( 'Select a menu that should be used in the module', 'quadmenu' ), esc_url( admin_url( 'nav-menus.php' ) ), esc_html__( 'Click here to create new menu', 'quadmenu' ) ),
				'toggle_slug'     => 'main_content',
				'default'         => $menu_id,
			),
			'menu_theme' => array(
				'label'            => esc_html__( 'Theme', 'quadmenu' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array_flip( quadmenu_vc_themes() ),
				'description'      => sprintf( '%s. <a href="%s" target="_blank">%s</a>', esc_html__( 'Select a the theme that should be used in the menu', 'quadmenu' ), esc_url( admin_url( 'admin.php?page=' . QUADMENU_PANEL ) ), esc_html__( 'Click here to create new theme', 'quadmenu' ) ),
				'toggle_slug'      => 'main_content',
				'default'          => 'default_theme',
				'default_on_front' => 'default_theme',
			),
		);

		return $fields;
	}

	function render( $attrs, $content, $render_slug ) {
	$this->props['content'] = quadmenu(
		array(
			'echo'           => false,
			'layout_classes' => 'js',
			'menu'           => $this->props['menu_id'],
			'theme'          => $this->props['menu_theme'],
		)
	);
		return sprintf( '<div class="et_pb_row et_pb_fullwidth_menu clearfix">%1$s</div>', $this->props['content'] );
	}

}

new ET_Builder_Module_QuadMenu();
