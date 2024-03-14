<?php //phpcs:ignore

namespace Enable\Cors;

/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
*/
if ( ! defined( 'Enable\Cors\NAME' ) ) {
	exit;
}

use Enable\Cors\Traits\Singleton;

final class AdminPage {
	use Singleton;

	/**
	 * Initialize admin page
	 * @return void
	 */
	private function __construct() {
		add_filter( 'script_loader_tag', array( $this, 'add_module' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'render' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}
	/**
	 * Render admin page
	 * @return void
	 */
	public function render() {
		add_menu_page(
			__( 'Enable CORS', 'enable-cors' ),
			__( 'Enable CORS', 'enable-cors' ),
			'manage_options',
			NAME,
			function () {
				add_filter( 'admin_footer_text', array( $this, 'credit' ) );
				add_filter( 'update_footer', array( $this, 'version' ), 11 );
				wp_enqueue_style( NAME );
				wp_enqueue_style( NAME . '-notify', 'https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css', array(), VERSION );
				wp_enqueue_script( NAME );
				echo wp_kses( sprintf( '<div id="%s">Loading...</div>', NAME ), array( 'div' => array( 'id' => array() ) ) );
			},
			'dashicons-admin-generic'
		);
	}
	/**
	 * Register scripts
	 * @return void
	 */
	public function scripts(): void {
		wp_register_style( NAME, plugins_url( 'assets/dist/style.css', FILE ), array(), VERSION );
		wp_register_script( NAME, plugins_url( 'assets/dist/script.js', FILE ), array(), VERSION, true );
		wp_add_inline_style( NAME, '#wpcontent .notice, #wpcontent #message{display: none} input[type=checkbox]:checked::before{content:unset}' );
		wp_localize_script(
			NAME,
			'enableCors',
			$this->js_object()
		);
	}
	/**
	 * Get object for js
	 * @return array of js
	 */
	private function js_object(): array {
		return array(
			'nonce'    => wp_create_nonce( 'wp_rest' ),
			'endpoint' => sprintf( '%senable-cors/v%s/settings', get_rest_url(), VERSION ),
			'strings'  => include plugin_dir_path( FILE ) . '/strings.php',
		);
	}

	/**
	 * Add type="module" to script tags
	 * @param string $tag of script
	 * @param string $handle of plugin script
	 * @return string with type="module"
	 */
	public function add_module( string $tag, string $id ): string {
		if ( NAME === $id ) {
			$tag = str_replace( '<script ', '<script type="module" ', $tag );
		}

		return $tag;
	}

	/**
	 * Version string for plugin footer
	 * @return string
	 */
	public function version(): string {
		return sprintf( 'You are using <strong>%s</strong> version', VERSION );
	}

	/**
	 * Credit string for plugin footer
	 * @return string
	 */
	public function credit(): string {
		return sprintf(
			"<strong>%s <span style='color: mediumvioletred'>❤</span> %s <a style='text-decoration: none' href='%s' target='_blank'><strong>%s</strong></a><strong/>",
			__( 'Created with', 'enable-cors' ),
			__( 'by', 'enable-cors' ),
			esc_url_raw( 'https://phprtsan.com?utm_source=wordpress&utm_medium=plugin&utm_campaign=enable-cors' ),
			__( 'PHP Artisan', 'enable-cors' )
		);
	}
}
