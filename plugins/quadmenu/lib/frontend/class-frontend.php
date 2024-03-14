<?php
namespace QuadLayers\QuadMenu\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Plugin;

/**
 * Frontend Class ex QuadMenu_Frontend
 */
class Frontend {

	public static $instance;

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'register' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'wp_head', array( $this, 'meta' ) );

		add_action( 'wp_head', array( $this, 'css' ) );
	}

	public function register() {

		wp_register_style( 'owlcarousel', QUADMENU_PLUGIN_URL . 'assets/frontend/owlcarousel/owl.carousel.min.css', array(), QUADMENU_PLUGIN_VERSION, 'all' );
		wp_register_script( 'owlcarousel', QUADMENU_PLUGIN_URL . 'assets/frontend/owlcarousel/owl.carousel.min.js', array( 'jquery' ), QUADMENU_PLUGIN_VERSION, true );

		wp_register_style( 'pscrollbar', QUADMENU_PLUGIN_URL . 'assets/frontend/pscrollbar/perfect-scrollbar.min.css', array(), QUADMENU_PLUGIN_VERSION, 'all' );
		wp_register_script( 'pscrollbar', QUADMENU_PLUGIN_URL . 'assets/frontend/pscrollbar/perfect-scrollbar.jquery.min.js', array( 'jquery' ), QUADMENU_PLUGIN_VERSION, true );

		wp_register_style( 'quadmenu-normalize', QUADMENU_PLUGIN_URL . 'assets/frontend/css/quadmenu-normalize' . Plugin::isMin() . '.css', array(), QUADMENU_PLUGIN_VERSION, 'all' );

		$frontend = include QUADMENU_PLUGIN_DIR . 'build/frontend/index.asset.php';

		wp_register_script( 'quadmenu', QUADMENU_PLUGIN_URL . 'build/frontend/index.js', $frontend['dependencies'], $frontend['version'], true );

		wp_register_style( 'quadmenu', QUADMENU_PLUGIN_URL . 'build/frontend/style.css', array(), QUADMENU_PLUGIN_VERSION, 'all' );

		if ( is_file( QUADMENU_UPLOAD_DIR . 'quadmenu-locations.css' ) ) {
			wp_register_style( 'quadmenu-locations', QUADMENU_UPLOAD_URL . 'quadmenu-locations.css', array(), filemtime( QUADMENU_UPLOAD_DIR . 'quadmenu-locations.css' ), 'all' );
		} else {
			wp_register_style( 'quadmenu-locations', QUADMENU_PLUGIN_URL . 'assets/frontend/css/quadmenu-locations.css', array(), QUADMENU_PLUGIN_VERSION, 'all' );
		}

		if ( is_file( QUADMENU_UPLOAD_DIR . 'quadmenu-widgets.css' ) ) {
			wp_register_style( 'quadmenu-widgets', QUADMENU_UPLOAD_URL . 'quadmenu-widgets.css', array(), filemtime( QUADMENU_UPLOAD_DIR . 'quadmenu-widgets.css' ), 'all' );
		} else {
			wp_register_style( 'quadmenu-widgets', QUADMENU_PLUGIN_URL . 'assets/frontend/css/quadmenu-widgets.css', array(), QUADMENU_PLUGIN_VERSION, 'all' );
		}
	}

	public function enqueue() {

		global $quadmenu;

		if ( empty( $quadmenu['styles'] ) ) {
			return;
		}

		if ( $quadmenu['styles_pscrollbar'] ) {
			wp_enqueue_script( 'pscrollbar' );
			wp_enqueue_style( 'pscrollbar' );
		}

		if ( $quadmenu['styles_owlcarousel'] ) {
			wp_enqueue_script( 'owlcarousel' );
			wp_enqueue_style( 'owlcarousel' );
		}

		if ( ! empty( $quadmenu['styles_normalize'] ) ) {
			wp_enqueue_style( 'quadmenu-normalize' );
		}

		if ( ! empty( $quadmenu['styles_widgets'] ) ) {
			wp_enqueue_style( 'quadmenu-widgets' );
		}

		wp_enqueue_style( 'quadmenu' );

		wp_enqueue_style( 'quadmenu-locations' );

		wp_enqueue_style( _QuadMenu()->selected_icons()->ID );

		wp_enqueue_script( 'quadmenu' );

		/*
		wp_localize_script('quadmenu', 'quadmenu', apply_filters('quadmenu_global_js_data', array(
		'login-nonce' => wp_create_nonce('quadmenu-login'),
		'gutter' => $quadmenu['gutter'],
		)));
		*/
	wp_localize_script(
		'quadmenu',
		'quadmenu',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'gutter'  => $quadmenu['gutter'],
		)
	);
	}

	public function meta() {
		global $quadmenu;

		if ( empty( $quadmenu['viewport'] ) ) {
			return;
		}
		?>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php
	}

	public function css() {

		global $quadmenu;

		if ( empty( $quadmenu['css'] ) ) {
			return;
		}
		?>
	<style>
		<?php echo $quadmenu['css']; ?>   
	</style>
	<?php
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

