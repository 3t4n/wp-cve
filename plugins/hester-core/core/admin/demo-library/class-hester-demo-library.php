<?php
/**
 * Hester Demo Library. Install a copy of a Hester demo to your website.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Demo Library Class.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Demo_Library {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Demo templates.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $templates = false;

	/**
	 * Is pro
	 * @since 1.0.6
	 * @var boolean
	 */
	public $is_pro = false;

	/**
	 * Main Hester Demo Library Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Demo_Library
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Demo_Library ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		$this->version = defined( HESTER_CORE_VERSION ) ? HESTER_CORE_VERSION : $this->version;

		$this->includes();
		$this->hooks();

		do_action( 'hester_demo_library_loaded' );
	}

	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 */
	private function includes() {

		require_once plugin_dir_path( __FILE__ ) . 'class-hester-demo-library-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-hester-demo-importer.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-hester-demo-exporter.php';
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'admin_init', array( $this, 'refresh_templates' ) );
		add_action( 'wp_ajax_hester-core-filter-demos', array( $this, 'filter_templates' ) );
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $hook Current hook name.
	 * @return void
	 */
	public function admin_enqueue( $hook = '' ) {

		$theme_name =  hester_core()->theme_name;

		if ( 'hester_page_'.$theme_name.'-demo-library' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'hester-demo-library',
			plugin_dir_url( __FILE__ ) . 'assets/js/demo-library.min.js',
			array( 'jquery', 'wp-util', 'updates' ),
			$this->version,
			true
		);
		
		$theme  = wp_get_theme(); // gets the current theme
		if ( stripos( strtolower( $theme->name ), 'pro' ) ) {
			$this->is_pro = true;
		}
				
		$localized = array(
			'strings'            => array(
				'closeWindowWarning'  => __( 'Warning! Demo import process is not complete. Don\'t close the window until import process is complete. Do you still want to leave the window?', 'hester-core' ),
				'importDemoWarning'   => __( 'Demo import process will start now. Please do not close the window until import process is complete.', 'hester-core' ),
				'importing'           => __( 'Importing...', 'hester-core' ),
				'installingPlugin'    => __( 'Installing plugin', 'hester-core' ) . ' ',
				'installed'           => __( 'Plugin installed!', 'hester-core' ),
				'activatingPlugin'    => __( 'Activating plugin', 'hester-core' ) . ' ',
				'activated'           => __( 'Plugin activated! ', 'hester-core' ),
				'importCompleted'     => __( 'All Done! Visit Site', 'hester-core' ),
				'importingCustomizer' => __( 'Importing Customizer...', 'hester-core' ),
				'importingContent'    => __( 'Importing Content...', 'hester-core' ),
				'importingWPForms'    => __( 'Importing WPForms...', 'hester-core' ),
				'importingOptions'    => __( 'Importing Options...', 'hester-core' ),
				'importingWidgets'    => __( 'Importing Widgets...', 'hester-core' ),
				'preview'             => __( 'Preview', 'hester-core' ),
				'preparing'           => __( 'Preparing Data...', 'hester-core' ),
				'noResultsFound'      => __( 'No results found', 'hester-core' ),
			),
			'homeurl'            => home_url( '/' ),
			'templates'          => $this->get_templates(),
			'is_pro'             => $this->is_pro,
			'upgrade_to_pro_url' => sprintf('https://peregrine-themes.com/%s/?utm_medium=dashboard&utm_source=demos&utm_campaign=upgradeToPro', $theme_name),
		);

		$localized = apply_filters( 'hester_core_demo_library_localized', $localized );

		wp_localize_script(
			'hester-demo-library',
			'hesterCoreDemoLibrary',
			$localized
		);

		wp_enqueue_style(
			'hester-core-admin',
			plugin_dir_url( __FILE__ ) . 'assets/css/demo-library.min.css',
			$this->version,
			true
		);
	}

	/**
	 * Get templates.
	 *
	 * @since  1.0.0
	 *
	 * @return array Array of demo templates.
	 */
	public function get_templates() {

		// Check if we have stored templates.
		if ( false === $this->templates ) {
			$this->templates = get_transient( 'hester_core_demo_templates' );
		}
		
		// No stored templates, get from remote.
		if ( ! $this->templates ) {
			$response = wp_remote_get(
				'https://peregrine-themes.com/wp-json/api/v1/demos',
				array(
					// 'user-agent' => 'Hester/' . HESTER_THEME_VERSION . ';',
					'timeout'    => 60,
				)
			);
			
			if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
				$this->templates = (array) json_decode( stripcslashes( wp_remote_retrieve_body( $response ) ), true );
			}
			$theme  = wp_get_theme();
			if ( is_array( $this->templates ) && ! empty( $this->templates ) ) {
				foreach ( $this->templates as $id => $template ) {
					
					// Skip demos that require a newer version of Hester Core.
					if ( defined( 'HESTER_CORE_VERSION' ) && isset( $template['hester-core-version'] ) && version_compare( HESTER_CORE_VERSION, $template['hester-core-version'] ) < 0 ) {
						unset( $this->templates[ $id ] );
						continue;
					}

					// Skip demos that require a newer version of Hester Theme.
					if ( defined( 'HESTER_THEME_VERSION' ) && isset( $template['hester-theme-version'] ) && version_compare( HESTER_THEME_VERSION, $template['hester-theme-version'] ) < 0 ) {
						unset( $this->templates[ $id ] );
						continue;
					}

					// Skip demos that require a newer version of Bloglo Theme.
					if ( defined( 'BLOGLO_THEME_VERSION' ) && isset( $template['bloglo-theme-version'] ) && version_compare( BLOGLO_THEME_VERSION, $template['bloglo-theme-version'] ) < 0 ) {
						unset( $this->templates[ $id ] );
						continue;
					}

					if( isset( $template['for_themes'] ) && !in_array( strtolower( $theme->name ), $template['for_themes'] ) ){
						unset( $this->templates[ $id ] );
						continue;
					}					
				}
			}

			set_transient( 'hester_core_demo_templates', $this->templates, 60 * 60 * 24 );
		}

		if ( is_array( $this->templates ) && ! empty( $this->templates ) ) {
			foreach ( $this->templates as $id => $template ) {
				$this->templates[ $id ]['plugins'] = $this->required_plugins( $template );
			}
		}

		return $this->templates;
	}

	/**
	 * Refresh demo templates.
	 *
	 * @since 1.0.0
	 */
	public function refresh_templates() {

		// Security check.
		if ( ! isset( $_GET['hester_core_nonce'] ) || ! wp_verify_nonce( $_GET['hester_core_nonce'], 'refresh_templates' ) ) {
			return;
		}

		delete_transient( 'hester_core_demo_templates' );

		$theme_name =  hester_core()->theme_name;

		wp_safe_redirect( admin_url( 'admin.php?page='.$theme_name.'-demo-library' ) );
		die;
	}

	/**
	 * Filter demo templates.
	 *
	 * @since 1.0.0
	 */
	public function filter_templates() {

		$hester_nonce =  hester_core()->theme_name . '_nonce';

		// Nonce check.
		check_ajax_referer( $hester_nonce );

		// Permission check.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to import a demo.', 'hester-core' ), 'import_error' );
		}

		$templates = $this->get_templates();

		if ( ! isset( $_POST['filters'] ) ) {
			wp_send_json_success( $templates );
		}

		$filters = array(
			'category' => isset( $_POST['filters']['category'] ) ? sanitize_text_field( wp_unslash( $_POST['filters']['category'] ) ) : '',
			'builder'  => isset( $_POST['filters']['builder'] ) ? sanitize_text_field( wp_unslash( $_POST['filters']['builder'] ) ) : '',
			's'        => isset( $_POST['filters']['s'] ) ? sanitize_text_field( wp_unslash( $_POST['filters']['s'] ) ) : '',
		);

		if ( ! empty( $templates ) && is_array( $templates ) ) {
			foreach ( $templates as $id => $template ) {

				// Check template category.
				if ( ! empty( $filters['category'] ) && ! array_key_exists( $filters['category'], $template['categories'] ) ) {
					unset( $templates[ $id ] );
					continue;
				}

				// Check template builder.
				if ( ! empty( $filters['builder'] ) && $filters['builder'] !== $template['page-builder'] ) {
					unset( $templates[ $id ] );
					continue;
				}

				// Check search filter.
				if ( ! empty( $filters['s'] ) && false === strpos( strtolower( $template['name'] ), strtolower( $filters['s'] ) ) ) {
					unset( $templates[ $id ] );
					continue;
				}
			}
		}

		wp_send_json_success( $templates );
	}

	/**
	 * Get required plugins.
	 *
	 * @since  1.0.0
	 * @param  array $template Template details.
	 * @return array Array of demo templates.
	 */
	public function required_plugins( $template ) {

		if ( ! isset( $template['plugins'] ) ) {
			return;
		}

		$theme_name =  hester_core()->theme_name;
		$hester_plugin_utilities =  $theme_name . '_plugin_utilities';

		if ( ! function_exists( $hester_plugin_utilities ) ) {
			return $template['plugins'];
		}

		$plugins = array();

		foreach ( $template['plugins'] as $plugin ) {

			if ( $hester_plugin_utilities()->is_activated( $plugin['slug'] ) ) {
				$plugin['status'] = 'active';
			} elseif ( $hester_plugin_utilities()->is_installed( $plugin['slug'] ) ) {
				$plugin['status'] = 'installed';
			} else {
				$plugin['status'] = 'not_installed';
			}

			$plugins[] = $plugin;
		}

		return $plugins;
	}
}

/**
 * The function which returns the one Hester_Demo_Library instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $hester_demo_library = hester_demo_library(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function hester_demo_library() {
	return Hester_Demo_Library::instance();
}

hester_demo_library();
