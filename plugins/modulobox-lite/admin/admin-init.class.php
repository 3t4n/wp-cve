<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ModuloBox Admin class
 *
 * @class ModuloBox_Admin_Init
 * @version	1.0.0
 * @since 1.0.0
 */
class ModuloBox_Admin_Init {

	/**
	 * Initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Build admin menu/pages
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Add admin menu in Dashboard menu.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_menu() {

		// add ModuloBox menu page
		add_menu_page(
			'ModuloBox',
			'ModuloBox',
			'manage_options',
			MOBX_NAME,
			array( $this, 'display_page' ),
			$this->menu_icon()
		);

	}

	/**
	 * Add menu icon as data uri to prevent additional HTTP request in admin dashboard
	 * Base64-encoded SVG using a data URI, allow to match the color scheme
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function menu_icon() {

		return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2Ij48cGF0aCBmaWxsPSJub25lIiBkPSJNNC43IDE0LjhjLTAuMSAwLTAuMiAwLTAuMi0wLjFMMS4zIDEyLjhDMS4xIDEyLjcgMSAxMi42IDEgMTIuNFYzLjZjMCAwIDAgMCAwIDBWMy42QzEgMy42IDEgMy42IDEgMy42YzAgMCAwIDAgMCAwbDAgMGMwIDAgMCAwIDAgMGwwIDBjMC0wLjIgMC4xLTAuMyAwLjItMC40IDAgMCAwIDAgMCAwbDAgMGMwIDAgMCAwIDAgMHMwIDAgMCAwYzAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwbDAgMGMwIDAgMCAwIDAgMGgwQzEuMiAzLjIgMS4yIDMuMiAxLjMgMy4yYzAgMCAwIDAgMCAwTDQuNSAxLjNjMC4yLTAuMSAwLjMtMC4xIDAuNSAwbDMgMS43IDMtMS43YzAuMi0wLjEgMC4zLTAuMSAwLjUgMGwzLjIgMS45YzAgMCAwIDAgMCAwQzE0LjkgMy4zIDE1IDMuNCAxNSAzLjZ2OC44YzAgMC4yLTAuMSAwLjMtMC4yIDAuNGwtMy4yIDEuOWMtMC4yIDAuMS0wLjMgMC4xLTAuNSAwLTAuMi0wLjEtMC4yLTAuMy0wLjItMC40bDAtNC4xTDguMyAxMS42Yy0wLjIgMC4xLTAuMyAwLjEtMC41IDAgMCAwIDAgMCAwIDBsLTIuNS0xLjR2NC4xYzAgMC4yLTAuMSAwLjMtMC4yIDAuNEM0LjkgMTQuNyA0LjggMTQuOCA0LjcgMTQuOHpNMiAxMi4xbDIuMiAxLjNWOS4zYzAtMC4yIDAuMS0wLjMgMC4zLTAuNCAwLjItMC4xIDAuMy0wLjEgMC41IDBMNy41IDEwLjN2LTIuNkwyIDQuNVYxMi4xek0xMS4zIDguOGMwLjEgMCAwLjIgMCAwLjMgMC4xIDAuMiAwLjEgMC4zIDAuMyAwLjMgMC40bDAgNC4xTDE0IDEyLjFWNC41TDguNSA3Ljd2Mi42bDIuNS0xLjRDMTEuMSA4LjggMTEuMiA4LjggMTEuMyA4Ljh6TTIuNSAzLjZMOCA2LjhsNS41LTMuMi0yLjItMS4zLTMgMS43Yy0wLjIgMC4xLTAuMyAwLjEtMC41IDBsLTMtMS43TDIuNSAzLjZ6Ii8+PC9zdmc+';

	}

	/**
	 * Display admin page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function display_page() {

		include_once( MOBX_ADMIN_PATH . 'views/header.php' );
		include_once( MOBX_ADMIN_PATH . 'views/tabs.php' );
		include_once( MOBX_ADMIN_PATH . 'views/form-start.php' );
		include_once( MOBX_ADMIN_PATH . 'views/general.php' );
		include_once( MOBX_ADMIN_PATH . 'views/accessibility.php' );
		include_once( MOBX_ADMIN_PATH . 'views/navigation.php' );
		include_once( MOBX_ADMIN_PATH . 'views/controls.php' );
		include_once( MOBX_ADMIN_PATH . 'views/caption.php' );
		include_once( MOBX_ADMIN_PATH . 'views/thumbnails.php' );
		include_once( MOBX_ADMIN_PATH . 'views/social-sharing.php' );
		include_once( MOBX_ADMIN_PATH . 'views/slideshow.php' );
		include_once( MOBX_ADMIN_PATH . 'views/zoom-video.php' );
		include_once( MOBX_ADMIN_PATH . 'views/gallery.php' );
		include_once( MOBX_ADMIN_PATH . 'views/customization.php' );
		include_once( MOBX_ADMIN_PATH . 'views/import-export.php' );
		include_once( MOBX_ADMIN_PATH . 'views/system-status.php' );
		include_once( MOBX_ADMIN_PATH . 'views/premium-version.php' );
		include_once( MOBX_ADMIN_PATH . 'views/form-end.php' );
		include_once( MOBX_ADMIN_PATH . 'views/footer.php' );
		include_once( MOBX_ADMIN_PATH . 'views/modal.php' );

	}

	public function localize() {

		return array(
			// WP nonce
			'nonce'           => current_user_can( 'manage_options' ) ? wp_create_nonce( MOBX_NAME . '_admin_nonce' ) : 666,
			// Ajax loading messages
			'saving_msg'      => esc_html__( 'Please wait, saving settings...' , 'modulobox' ),
			'previewing_msg'  => esc_html__( 'Please wait, fetching settings...' , 'modulobox' ),
			'reset_msg'       => esc_html__( 'Are you sure you want to reset your current settings?' , 'modulobox' ),
			'import_msg'      => esc_html__( 'Are you sure you want to import settings? All your current settings will be overridden.' , 'modulobox' ),
			'error_msg'       => esc_html__( 'Sorry, an unknown error occurred.' , 'modulobox' ),
			// SVG icons url for asynchronous use
			'svg_icons'       => esc_url( MOBX_ADMIN_URL . 'assets/icons/modulobox.svg' ),
			// Media gallery for lightbox preview mode
			'lightbox_media'  => array(
				array(
					'src'    => esc_url( MOBX_ADMIN_URL . 'assets/images/image-1.jpg' ),
					'thumb'  => esc_url( MOBX_ADMIN_URL . 'assets/images/thumb-1.jpg' ),
					'title'  => esc_html__( 'Anemone Flower Path', 'modulobox' ),
					'desc'   => esc_html__( 'Photo by: Annie Spratt', 'modulobox' ),
				),
				array(
					'src'    => esc_url( MOBX_ADMIN_URL . 'assets/images/image-2.jpg' ),
					'thumb'  => esc_url( MOBX_ADMIN_URL . 'assets/images/thumb-2.jpg' ),
					'title'  => esc_html__( 'Maori Tattoo, Body Art', 'modulobox' ),
					'desc'   => esc_html__( 'Photo by: Felix Russell-Saw', 'modulobox' ),
				),
				array(
					'src'    => esc_url( MOBX_ADMIN_URL . 'assets/images/image-3.jpg' ),
					'thumb'  => esc_url( MOBX_ADMIN_URL . 'assets/images/thumb-3.jpg' ),
					'title'  => esc_html__( 'Antelope Canyon', 'modulobox' ),
					'desc'   => esc_html__( 'Photo by: Blake Richard Verdoorn', 'modulobox' ),
				),
				array(
					'src'    => esc_url( MOBX_ADMIN_URL . 'assets/images/image-4.jpg' ),
					'thumb'  => esc_url( MOBX_ADMIN_URL . 'assets/images/thumb-4.jpg' ),
					'title'  => esc_html__( 'View From A Blue Moon', 'modulobox' ),
					'desc'   => esc_html__( 'Photo by: Austin Schmid', 'modulobox' ),
				),
			),
		);

	}

	/**
	 * Enqueue admin scripts
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {

		// get current admin screen
		$screen = get_current_screen();

		if ( strpos( $screen->id, MOBX_NAME ) !== false ) {

			// enqueue lightbox script and style
			wp_enqueue_script( MOBX_NAME, MOBX_PUBLIC_URL . 'assets/js/modulobox.min.js', array(), MOBX_VERSION, true );
			wp_enqueue_style( MOBX_NAME, MOBX_PUBLIC_URL . 'assets/css/modulobox.min.css', array(), MOBX_VERSION );

			// enqueue main script
			wp_enqueue_script( MOBX_NAME . '-options', MOBX_ADMIN_URL . 'assets/js/options.js', array( 'jquery', 'wp-color-picker' ), MOBX_VERSION, true );
			// localize strings
			wp_localize_script( MOBX_NAME . '-options', MOBX_SLUG . '_localize', $this->localize() );

			// enqueue main styleSheet
			wp_enqueue_style( MOBX_NAME . '-options-style', MOBX_ADMIN_URL . 'assets/css/options.css', array(), MOBX_VERSION );

		}

	}
}

new ModuloBox_Admin_Init;
