<?php

namespace QuadLayers\QuadMenu\Integrations\Divi;

/**
 * Module Class Ex QuadmenuDiviModule
 */

class Module extends \DiviExtension {
	public static $instance;

	public $gettext_domain = 'quadmenu-quadmenu-divi-module';
	public $name           = 'quadmenu-divi-module';
	public $version        = '1.0.0';

	function ajax_quadmenu_divi_module() {

		if ( ! empty( $_REQUEST['menu_id'] ) && ! empty( $_REQUEST['menu_theme'] ) ) {
		$menu = quadmenu(
			array(
				'echo'           => false,
				'menu'           => $_REQUEST['menu_id'],
				'theme'          => $_REQUEST['menu_theme'],
				'layout_classes' => 'js',
			)
		);
			wp_send_json_success( $menu );
		} else {
			wp_send_json_error( 'Unknow error', 'quadmenu' );
		}
	}

	function _dequeue_bundles() {
		wp_dequeue_style( "{$this->name}-styles" );
	}

	function fullwidth_menu( $args ) {

		if ( class_exists( 'ET_Builder_Module' ) ) {
			if ( isset( $args['menu_class'] ) && strpos( $args['menu_class'], 'fullwidth-menu' ) !== false ) {
				$args['theme_location'] = false;
			}
		}

		return $args;
	}

	public function __construct( $name = 'quadmenu-divi-module', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir . 'divi/' );
		add_action( 'wp_ajax_ajax_quadmenu_divi_module', array( $this, 'ajax_quadmenu_divi_module' ) );
		add_action( 'wp_ajax_nopriv_ajax_quadmenu_divi_module', array( $this, 'ajax_quadmenu_divi_module' ) );
		add_action( 'wp_enqueue_scripts', array( $this, '_dequeue_bundles' ), 100 );
		add_filter( 'wp_nav_menu_args', array( $this, 'fullwidth_menu' ), 100000, 1 );
		parent::__construct( $name, $args );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

