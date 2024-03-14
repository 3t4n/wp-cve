<?php
namespace Thim_EL_Kit;

class Dashboard {
	use SingletonTrait;

	const MENU_SLUG = 'thim_elementor_kit';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'thim_ekit/rest_api/register_endpoints', array( $this, 'register_endpoints' ), 10, 1 );
	}

	public function admin_enqueue_scripts( $hook ) {
		// Only load in dashboard page.
		if ( $hook !== 'toplevel_page_' . self::MENU_SLUG ) {
			return;
		}

		$file_info = include THIM_EKIT_PLUGIN_PATH . 'build/dashboard.asset.php';

		wp_enqueue_script( 'thim-ekit-dashboard', THIM_EKIT_PLUGIN_URL . 'build/dashboard.js', $file_info['dependencies'], $file_info['version'], true );
		wp_enqueue_style( 'thim-ekit-dashboard', THIM_EKIT_PLUGIN_URL . 'build/dashboard.css', array( 'wp-components' ), $file_info['version'] );

		wp_localize_script(
			'thim-ekit-dashboard',
			'thim_ekit_dashboard',
			array(
				'banner' => THIM_EKIT_PLUGIN_URL . 'build/libraries/banner.png',
			)
		);
	}

	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Thim Elementor', 'thim-elementor-kit' ),
			esc_html__( 'Thim Elementor', 'thim-elementor-kit' ),
			'manage_options',
			self::MENU_SLUG,
			function() {
				echo '<div id="thim-ekit-dashboard-app"></div>';
			},
			'dashicons-art',
			'58.6'
		);
	}

	public function register_endpoints( $namespace ) {
		register_rest_route(
			$namespace,
			'/changelog',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'changelog' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	public function changelog() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$changelog = $wp_filesystem->get_contents( THIM_EKIT_PLUGIN_PATH . 'readme.txt' );
		$split     = explode( '== Changelog ==', $changelog );

		$readme = ! empty( $split[1] ) ? $split[1] : '';

		if ( ! empty( $readme ) ) {
			$readme = preg_replace( '|\n*===\s*([^=]+?)\s*=*\s*\n+|im', PHP_EOL . "\n# $1\n" . PHP_EOL, $readme );
			$readme = preg_replace( '|\n*==\s*([^=]+?)\s*=*\s*\n+|im', PHP_EOL . "\n## $1\n" . PHP_EOL, $readme );
			$readme = preg_replace( '|\n*=\s*([^=]+?)\s*=*\s*\n+|im', PHP_EOL . "\n### $1\n" . PHP_EOL, $readme );
		}

		return rest_ensure_response( $readme );
	}
}

Dashboard::instance();
