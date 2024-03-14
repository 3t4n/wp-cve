<?php

namespace QuadLayers\QuadMenu;

use QuadLayers\QuadMenu\Configuration;

use QuadLayers\QuadMenu\Integrations\WooCommerce;
use QuadLayers\QuadMenu\Integrations\Polylang;
use QuadLayers\QuadMenu\Integrations\Elementor;
use QuadLayers\QuadMenu\Integrations\Beaver;
use QuadLayers\QuadMenu\Integrations\Divi;

use QuadLayers\QuadMenu\Activation;
use QuadLayers\QuadMenu\Admin;
use QuadLayers\QuadMenu\Panel;
use QuadLayers\QuadMenu\Panel\Welcome;
use QuadLayers\QuadMenu\Panel\System;
use QuadLayers\QuadMenu\Panel\Options as PanelOptions;
use QuadLayers\QuadMenu\Panel\Premium;

use QuadLayers\QuadMenu\Locations;

use QuadLayers\QuadMenu\Themes;
use QuadLayers\QuadMenu\Options;
use QuadLayers\QuadMenu\Redux;
use QuadLayers\QuadMenu\Icons;

use QuadLayers\QuadMenu\Compiler;

use QuadLayers\QuadMenu\Backend\Settings;
use QuadLayers\QuadMenu\Backend\Walker\Nav_Menu_Widgets;
use QuadLayers\QuadMenu\Backend\Walker\Nav_Menu_Columns;
use QuadLayers\QuadMenu\Backend\Walker\Nav_Menu_Mega;
use QuadLayers\QuadMenu\Backend\Walker\Nav_Menu_Defaults;
use QuadLayers\QuadMenu\Backend\Ajax;

use QuadLayers\QuadMenu\Frontend\Frontend;
use QuadLayers\QuadMenu\Frontend\Items;

final class Plugin {

	private $prefix = '#quadmenu-';
	private static $instance;
	private $registered_icons;
	private $registered_icons_names;
	public $selected_icons;

	public function __construct() {
		$this->constants();
		$this->config();
		$this->includes();
		$this->compatibility();
		$this->hooks();
		// $this->errors();
	}

	private function config() {
		Configuration::instance();

	}

	private function compatibility() {

		WooCommerce::instance();
		Polylang::instance();
		require_once QUADMENU_PLUGIN_DIR . 'lib/integrations/class-vc.php';
		Elementor::instance();
		Beaver::instance();
		Divi::instance();
		add_action(
			'widgets_init',
			function() {
				register_widget( '\\QuadLayers\\QuadMenu\\Widget' );
			}
		);
	}

	private function hooks() {

		add_filter( 'wp_get_nav_menu_items', array( $this, 'remove_nav_menu_item' ), 20, 3 );
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_nav_menu_item_options' ), 90 );
		add_filter( 'quadmenu_setup_nav_menu_item', array( $this, 'setup_nav_menu_item_parents' ), 5 );
		add_filter( 'quadmenu_setup_nav_menu_item', array( $this, 'setup_nav_menu_item_validation' ), 10 );

		add_action( 'init', array( $this, 'register_sidebar' ) );
		add_action( 'init', array( $this, 'register_icons' ), -35 );
		add_action( 'init', array( $this, 'admin' ), -25 );
		add_action( 'init', array( $this, 'compiler' ), -20 );
		add_action( 'init', array( $this, 'locations' ), -15 );
		add_action( 'init', array( $this, 'init' ), -10 );
		add_action( 'init', array( $this, 'frontend' ), -5 );
		add_action( 'admin_init', array( $this, 'navmenu' ) );

		add_action( 'plugins_loaded', array( $this, 'i18n' ) );
	}

	public function register_sidebar() {

		register_sidebar(
			array(
				'id'          => 'quadmenu-widgets',
				'name'        => esc_html__( 'QuadMenu Widgets', 'quadmenu' ),
				'description' => esc_html__( 'Do not manually edit this sidebar.', 'quadmenu' ),
			)
		);
	}

	function register_icons() {

		foreach ( apply_filters( 'quadmenu_register_icons', array() ) as $id => $settings ) {

			// if (!wp_style_is($id, $list = 'registered')) {
			// wp_register_style($id, $settings['url']);
			// }

			$settings['ID'] = $id;

			$this->registered_icons[ $id ] = (object) $settings;

			$this->registered_icons_names[ $id ] = $settings['name'];
		}
	}

	function registered_icons() {
		return (object) $this->registered_icons;
	}

	function registered_icons_names() {
		return $this->registered_icons_names;
	}

	function selected_icons() {

		global $quadmenu;

		$this->selected_icons = $this->registered_icons()->dashicons;

		if ( ! empty( $quadmenu['styles_icons'] ) && isset( $this->registered_icons()->{$quadmenu['styles_icons']} ) ) {
			$this->selected_icons = $this->registered_icons()->{$quadmenu['styles_icons']};
		}

		if ( ! wp_style_is( $this->selected_icons->ID, $list = 'registered' ) ) {
			wp_register_style( $this->selected_icons->ID, $this->selected_icons->url );
		}

		return $this->selected_icons;
	}


	/*
	   function selected_icons() {

	global $quadmenu;

	if (empty($quadmenu['styles_icons'])) {
	self::$selected_icons = $this->registered_icons()->dashicons;
	}

	if (empty($this->registered_icons()->{$quadmenu['styles_icons']})) {
	self::$selected_icons = $this->registered_icons()->dashicons;
	}

	self::$selected_icons = $this->registered_icons()->{$quadmenu['styles_icons']};

	if (!wp_style_is(self::$selected_icons->ID, $list = 'registered')) {
	wp_register_style(self::$selected_icons->ID, self::$selected_icons->url);
	}

	return self::$selected_icons;
	} */

	private function theme() {

		$theme = get_stylesheet();

		$theme = preg_replace( '/[^a-zA-Z0-9_\-]/', '', $theme );

		return $theme;
	}

	private function constants() {

		$upload_dir = wp_upload_dir();

		define( 'QUADMENU_DB_OPTIONS', "quadmenu_{$this->theme()}" );

		define( 'QUADMENU_DB_THEMES', "quadmenu_{$this->theme()}_themes" );

		define( 'QUADMENU_DB_LOCATIONS', "quadmenu_{$this->theme()}_locations" );

		define( 'QUADMENU_UPLOAD_DIR', trailingslashit( "{$upload_dir['basedir']}/{$this->theme()}" ) );

		define( 'QUADMENU_UPLOAD_URL', set_url_scheme( trailingslashit( "{$upload_dir['baseurl']}/{$this->theme()}" ) ) );

		define( 'QUADMENU_PANEL', apply_filters( 'quadmenu_hook_menu_panel', 'quadmenu_options' ) );

		// Compatibility
		define( 'QUADMENU_PATH_CSS', QUADMENU_UPLOAD_DIR );
		define( 'QUADMENU_URL_CSS', QUADMENU_UPLOAD_URL );
		define( 'QUADMENU_OPTIONS', QUADMENU_DB_OPTIONS );
		define( 'QUADMENU_THEMES', QUADMENU_DB_THEMES );
		define( 'QUADMENU_LOCATIONS', QUADMENU_DB_LOCATIONS );
	}

	private function includes() {

		require_once QUADMENU_PLUGIN_DIR . 'lib/class-functions.php';

		require_once QUADMENU_PLUGIN_DIR . 'lib/class-import.php';

		Activation::instance();

		Panel::instance();
	}

	public function admin() {
		Admin::instance();
		Welcome::instance();
		System::instance();
		PanelOptions::instance();
		Premium::instance();
	}

	public function locations() {
		Locations::instance();
	}

	public function init() {
		Themes::instance();
		Options::instance();
		Redux::instance();
		Icons::instance();
	}

	public function compiler() {

		if ( ! is_admin() && ! is_customize_preview() ) {
			return;
		}

		Compiler::instance();
	}

	function nav_menu_selected_id() {

		if ( wp_doing_ajax() && isset( $_REQUEST['menu_id'] ) ) {
			return (int) $_REQUEST['menu_id'];
		}

		$nav_menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );

		$menu_count = count( $nav_menus );

		// Get recently edited nav menu
		$recently_edited = (int) get_user_option( 'nav_menu_recently_edited' );

		$nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;

		// Are we on the add new screen?
		$add_new_screen = ( isset( $_REQUEST['menu'] ) && 0 == $_REQUEST['menu'] ) ? true : false;

		$page_count = wp_count_posts( 'page' );

		$one_theme_location_no_menus = ( 1 == count( get_registered_nav_menus() ) && ! $add_new_screen && empty( $nav_menus ) && ! empty( $page_count->publish ) ) ? true : false;

		if ( empty( $recently_edited ) && is_nav_menu( $nav_menu_selected_id ) ) {
			$recently_edited = $nav_menu_selected_id;
		}

		// Use $recently_edited if none are selected.
		if ( empty( $nav_menu_selected_id ) && ! isset( $_REQUEST['menu'] ) && is_nav_menu( $recently_edited ) ) {
			$nav_menu_selected_id = $recently_edited;
		}

		// On deletion of menu, if another menu exists, show it.
		if ( ! $add_new_screen && 0 < $menu_count && isset( $_REQUEST['action'] ) && 'delete' == $_REQUEST['action'] ) {
			$nav_menu_selected_id = $nav_menus[0]->term_id;
		}

		// Set $nav_menu_selected_id to 0 if no menus.
		if ( $one_theme_location_no_menus ) {
			$nav_menu_selected_id = 0;
		} elseif ( empty( $nav_menu_selected_id ) && ! empty( $nav_menus ) && ! $add_new_screen ) {
			// if we have no selection yet, and we have menus, set to the first one in the list.
			$nav_menu_selected_id = $nav_menus[0]->term_id;
		}

		return $nav_menu_selected_id;
	}

	function is_quadmenu( $nav_menu_selected_id = false ) {

		global $quadmenu_active_locations;

		if ( ! $menu_locations = isset( $_REQUEST['menu-locations'] ) && is_array( $_REQUEST['menu-locations'] ) ? $_REQUEST['menu-locations'] : get_nav_menu_locations() ) {
			return false;
		}

		if ( ! $nav_menu_selected_id = $this->nav_menu_selected_id() ) {
			return false;
		}

		// chek if this menu id is in the theme locations
		if ( ! in_array( sanitize_key( $nav_menu_selected_id ), $menu_locations ) ) {
			return false;
		}

		if ( count( array_intersect( array_keys( $menu_locations, $nav_menu_selected_id ), array_keys( (array) $quadmenu_active_locations ) ) ) > 0 ) {
			return true;
		}

		return false;
	}

	function is_quadmenu_location( $location = false ) {

		global $quadmenu_active_locations;

		if ( ! empty( $quadmenu_active_locations[ $location ] ) ) {
			return true;
		}

		return false;
	}

	public function navmenu() {

		if ( $this->is_quadmenu() ) {

			Settings::instance();
			Nav_Menu_Widgets::instance();
			Nav_Menu_Columns::instance();
			Nav_Menu_Mega::instance();
			Nav_Menu_Defaults::instance();
			Ajax::instance();
		}
	}

	public function frontend() {

		// if (is_admin())
		// return;

		Frontend::instance();
		require_once QUADMENU_PLUGIN_DIR . 'lib/frontend/class-integration.php';
		Items::instance();

	}

	public function setup_nav_menu_item_options( $item ) {

		if ( isset( $item->ID ) ) {

			$saved_settings = (array) get_post_meta( $item->ID, QUADMENU_DB_ITEM, true );

			foreach ( $saved_settings as $key => $value ) {
				$item->{$key} = $value;
			}

			return apply_filters( 'quadmenu_setup_nav_menu_item', $item );
		}

		return $item;
	}

	public function setup_nav_menu_item_parents( $item ) {

		global $wp_registered_widgets;

		$items = Configuration::custom_nav_menu_items();

		// Quadmenu
		// -----------------------------------------------------------------

		if ( strpos( $item->url, $this->prefix ) !== false ) {
			$item->quadmenu = str_replace( $this->prefix, '', $item->url );
		}

		if ( isset( $item->quadmenu ) ) {
			if ( ! empty( $items->{$item->quadmenu}->label ) ) {
				$item->type_label = '[' . $items->{$item->quadmenu}->label . ']';
			}
		}

		if ( isset( $item->quadmenu ) && $item->object == 'custom' ) {
			$item->object = $item->quadmenu;
		}

		if ( ! isset( $item->quadmenu ) ) {
			$item->quadmenu = $item->type;
		}

		// Replace quadmenu with object if defined
		// -----------------------------------------------------------------

		if ( isset( $items->{$item->object} ) ) {
			$item->quadmenu = $item->object;
		}

		// Replace quadmenu with object post_archive
		// -----------------------------------------------------------------
		if ( $item->type === 'post_type_archive' && isset( $items->{$item->object . '_archive'} ) ) {
			$item->quadmenu = $item->object . '_archive';
		}

		// Parent
		// -----------------------------------------------------------------
		if ( empty( $item->quadmenu_menu_item_parent ) ) {
			if ( ! empty( $item->menu_item_parent ) ) {

				$parent_obj = self::wp_setup_nav_menu_item( $item->menu_item_parent );

				if ( isset( $parent_obj->type ) ) {
					// brokes the subitems
					// if (!empty($parent_obj->quadmenu)) {
					if ( isset( $parent_obj->quadmenu ) && $parent_obj->type === 'custom' ) {
						$item->quadmenu_menu_item_parent = $parent_obj->quadmenu;
					} else {
						// post_type taxonomy post_type_archive parents
						$item->quadmenu_menu_item_parent = $parent_obj->type;
					}
				}
			} else {
				$item->quadmenu_menu_item_parent = 'main';
			}
		}

		// Validation
		// -----------------------------------------------------------------

		if ( ! empty( $items->{$item->quadmenu}->parent ) ) {

			$item->quadmenu_allowed_parents = $items->{$item->quadmenu}->parent;

			// Main
			// -----------------------------------------------------------------
			if ( ! is_array( $item->quadmenu_allowed_parents ) && $item->quadmenu_allowed_parents === 'main' ) {
				$item->menu_item_parent          = 0;
				$item->quadmenu_menu_item_parent = 'main';
			}

			// Invalid
			// -----------------------------------------------------------------
			if ( is_array( $item->quadmenu_allowed_parents ) && ! in_array( $item->quadmenu_menu_item_parent, $item->quadmenu_allowed_parents ) ) {
				$item->_invalid = true;
			}

			// Invalid
			// -----------------------------------------------------------------
			if ( ! is_array( $item->quadmenu_allowed_parents ) && $item->quadmenu_allowed_parents != $item->quadmenu_menu_item_parent ) {
				$item->_invalid = true;
			}
		} else {
			$item->quadmenu_allowed_parents = 'all';
		}

		if ( $item->quadmenu == 'widget' && ( empty( $item->widget_id ) || ! isset( $wp_registered_widgets[ $item->widget_id ] ) ) ) {
			$item->_invalid = true;
		}

		return $item;
	}

	static function wp_setup_nav_menu_item( $ID ) {

		$item_obj = wp_cache_get( "wp_setup_nav_menu_item_{$ID}", 'quadmenu' );

		if ( $item_obj === false ) {

			$item_obj = get_post( $ID );

			if ( ! empty( $item_obj->ID ) ) {
				$item_obj = wp_setup_nav_menu_item( $item_obj );
			}

			wp_cache_set( "wp_setup_nav_menu_item_{$ID}", $item_obj, 'quadmenu' );
		}

		return $item_obj;
	}

	function setup_nav_menu_item_validation( $item ) {

		if ( isset( $item->target ) && $item->target === 'on' ) {
			$item->target = '_blank';
		}

		if ( isset( $item->target ) && $item->target === 'off' ) {
			$item->target = '';
		}

		if ( isset( $item->columns ) ) {

			// var_dump($item->columns);

			$item->columns = array_diff( array_filter( (array) $item->columns ), array( 'off' ) );
		}

		return $item;
	}

	public function remove_nav_menu_item( $items, $menu, $args ) {

		if ( is_quadmenu() ) {

			foreach ( $items as $key => $item ) {

				if ( ! wp_doing_ajax() ) {

					// Remove invalid items in frontend
					if ( ! is_admin() && $item->_invalid ) {
						unset( $items[ $key ] );
					}

					// Remove valid quadmenu items
					if ( is_admin() && ! $item->_invalid && in_array( sanitize_key( $item->quadmenu_menu_item_parent ), apply_filters( 'quadmenu_remove_nav_menu_item', array( 'column', 'mega', 'login' ) ) ) ) {
						unset( $items[ $key ] );
					}

					// Remove invalid items without parent
					if ( is_admin() && $item->_invalid && ! $item->quadmenu_menu_item_parent ) {
						unset( $items[ $key ] );
					}
				}
			}
		}

		return $items;
	}

	public function edit_nav_menu_walker( $menu_id ) {
		return 'QuadMenu_Walker_Nav_Menu_Edit';
	}

	function i18n() {
		load_plugin_textdomain( 'quadmenu', false, QUADMENU_PLUGIN_DIR . '/languages' );
	}

	public static function taburl( $id = 0 ) {
		return admin_url( 'admin.php?page=' . QUADMENU_PANEL . '&tab=' . $id );
	}

	public static function isMin() {
		$min = '';

		if ( false == QUADMENU_DEV ) {
			$min = '.min';
		}

		return $min;
	}

	public static function send_json_success( $json ) {
		if ( ob_get_contents() ) {
			ob_clean();
		}

		wp_send_json_success( $json );
	}

	public static function send_json_error( $json ) {
		if ( ob_get_contents() ) {
			ob_clean();
		}

		wp_send_json_error( $json );
	}

	// private function errors() {

	// if ( ! QUADMENU_DEV ) {
	// return;
	// }

	// ini_set( 'error_reporting', E_ALL );
	// ini_set( 'display_errors', 1 );
	// ini_set( 'display_startup_errors', 1 );
	// }

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
