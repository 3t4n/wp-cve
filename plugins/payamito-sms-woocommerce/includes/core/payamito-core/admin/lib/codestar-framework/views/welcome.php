<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Setup Framework Class
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Welcome' ) ) {
	class KIANFR_Welcome
	{

		private static $instance = null;

		public function __construct()
		{
			if ( KIANFR::$premium && ( ! KIANFR::is_active_plugin( 'codestar-framework/codestar-framework.php' ) || apply_filters( 'kianfr_welcome_page', true ) === false ) ) {
				return;
			}

			add_action( 'admin_menu', [ $this, 'add_about_menu' ], 0 );
			add_filter( 'plugin_action_links', [ $this, 'add_plugin_action_links' ], 10, 5 );
			add_filter( 'plugin_row_meta', [ $this, 'add_plugin_row_meta' ], 10, 2 );

			$this->set_demo_mode();
		}

		// instance
		public static function instance()
		{
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function add_about_menu()
		{
			add_management_page( 'Codestar Framework', 'Codestar Framework', 'manage_options', 'kianfr-welcome', [
				$this,
				'add_page_welcome',
			] );
		}

		public function add_page_welcome()
		{
			$section = ( ! empty( $_GET['section'] ) ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';

			KIANFR::include_plugin_file( 'views/header.php' );

			// safely include pages
			switch ( $section ) {
				case 'quickstart':
					KIANFR::include_plugin_file( 'views/quickstart.php' );
					break;

				case 'documentation':
					KIANFR::include_plugin_file( 'views/documentation.php' );
					break;

				case 'relnotes':
					KIANFR::include_plugin_file( 'views/relnotes.php' );
					break;

				case 'support':
					KIANFR::include_plugin_file( 'views/support.php' );
					break;

				case 'free-vs-premium':
					KIANFR::include_plugin_file( 'views/free-vs-premium.php' );
					break;

				default:
					KIANFR::include_plugin_file( 'views/about.php' );
					break;
			}

			KIANFR::include_plugin_file( 'views/footer.php' );
		}

		public static function add_plugin_action_links( $links, $plugin_file )
		{
			if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
				$links['kianfr--welcome'] = '<a href="' . esc_url( admin_url( 'tools.php?page=kianfr-welcome' ) ) . '">Settings</a>';
				if ( ! KIANFR::$premium ) {
					$links['kianfr--upgrade'] = '<a href="http://codestarframework.com/">Upgrade</a>';
				}
			}

			return $links;
		}

		public static function add_plugin_row_meta( $links, $plugin_file )
		{
			if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
				$links['kianfr--docs'] = '<a href="http://codestarframework.com/documentation/" target="_blank">Documentation</a>';
			}

			return $links;
		}

		public function set_demo_mode()
		{
			$demo_mode = get_option( 'kianfr_demo_mode', false );

			$demo_activate = ( ! empty( $_GET['kianfr-demo'] ) ) ? sanitize_text_field( wp_unslash( $_GET['kianfr-demo'] ) ) : '';

			if ( ! empty( $demo_activate ) ) {
				$demo_mode = ( $demo_activate === 'activate' ) ? true : false;

				update_option( 'kianfr_demo_mode', $demo_mode );
			}

			if ( ! empty( $demo_mode ) ) {
				KIANFR::include_plugin_file( 'samples/admin-options.php' );

				if ( KIANFR::$premium ) {
					KIANFR::include_plugin_file( 'samples/customize-options.php' );
					KIANFR::include_plugin_file( 'samples/metabox-options.php' );
					KIANFR::include_plugin_file( 'samples/nav-menu-options.php' );
					KIANFR::include_plugin_file( 'samples/profile-options.php' );
					KIANFR::include_plugin_file( 'samples/shortcode-options.php' );
					KIANFR::include_plugin_file( 'samples/taxonomy-options.php' );
					KIANFR::include_plugin_file( 'samples/widget-options.php' );
					KIANFR::include_plugin_file( 'samples/comment-options.php' );
				}
			}
		}

	}

	KIANFR_Welcome::instance();
}
