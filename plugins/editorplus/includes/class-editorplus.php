<?php
/**
 * Main file for editorplus
 *
 * @package EditorPlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDPL_EDITORPLUS_PLUGIN_DIR', plugin_dir_path( __DIR__ ) );
define( 'EDPL_EDITORPLUS_PLUGIN_URL', plugins_url( '/', __DIR__ ) );

/**
 * Main Editor Plus Class
 *
 * @since 2.6.0
 */
final class EditorPlus {


	/**
	 * This plugin's instance
	 *
	 * @var EditorPlus
	 * @since 2.6.0
	 */

	private static $instance;

	/**
	 * This plugin's current version
	 *
	 * @var $plugin_version
	 */

	public static $plugin_version = '2.5.0';

	/**
	 * This plugin's slug
	 *
	 * @var $plugin_slug
	 */

	public static $plugin_slug = 'editor_plus';

	/**
	 * Used For Disabling Caching/Other features to make development easy
	 *
	 * @var $env
	 */

	private static $env = 'DEVELOPMENT';

	/**
	 * Main EditorPlus Instance.
	 *
	 * Insures that only one instance of EditorPlus exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 2.6.0
	 * @static
	 * @return object|EditorPlus The one true edpl__EditorPlus
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EditorPlus ) ) {

			self::$instance = new self();
			self::$instance->includes();
			self::$instance->init();

			return self::$instance;
		}
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 2.6.0
	 * @return void
	 */
	private function includes() {
		require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'assets/index.php';
		require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'blocks/index.php';
		require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'includes/class-editorplus-extensions-manager.php';
		require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'includes/class-editorplus-styles-manager.php';
	}

	/**
	 * Register all plugin settings
	 *
	 * @return void
	 */
	public function register_settings() {
		$extensions = new EditorPlus_Extensions_Manager();
		$extensions->register_settings();
	}

	/**
	 * Enqueue Assets
	 *
	 * @param string $suffix - Current Page Suffix.
	 * @return void
	 */
	public function enqueue_assets( $suffix ) {

		require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'includes/utils.php';

		// using dynamic version to disable wp cache in the DEVELOPMENT version.
		$dynamic_version = 'PRODUCTION' === self::$env ? self::$plugin_version : uniqid();

		// for gutenberg editor.
		if ( editorplus_is_gutenberg_page() ) {

			wp_enqueue_style( 'editor_plus-plugin-style', EDPL_EDITORPLUS_PLUGIN_URL . 'dist/gutenberg-editor-style.css', array( 'wp-components' ), $dynamic_version );
			wp_enqueue_script( 'editor_plus-plugin-script', EDPL_EDITORPLUS_PLUGIN_URL . 'dist/gutenberg-editor.js', array( 'lodash', 'wp-api', 'wp-i18n', 'wp-components', 'wp-element', 'wp-editor' ), $dynamic_version, true );
			wp_enqueue_script( 'editor_plus-lodash-conflict-script', EDPL_EDITORPLUS_PLUGIN_URL . 'assets/scripts/lodash-conflict.js', array( 'lodash', 'wp-api', 'wp-i18n', 'wp-components', 'wp-element', 'wp-editor' ), $dynamic_version, true );

			// LOTTIE SCRIPT.
			wp_enqueue_script( 'editor-plus-lottie-script', EDPL_EDITORPLUS_PLUGIN_URL . '/assets/scripts/lottie-player.js', array(), 'latest', true );

			$extra_css = apply_filters( 'editor_plus_plugin_css', '' );
			wp_add_inline_style( 'editor_plus-plugin-style', $extra_css );

			// loading localized variables.
			$extensions = new EditorPlus_Extensions_Manager();
			$settings   = $extensions->get_settings();

			wp_localize_script(
				'editor_plus-plugin-script',
				'editor_plus_extension',
				$settings->extensions
			);

			wp_localize_script(
				'editor_plus-plugin-script',
				'eplus_data',
				array(
					'rest_url'      => get_rest_url(),
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'plugin_assets' => plugins_url( 'assets', __FILE__ ),
				)
			);

		}

		// for wp frontend.
		if ( ! is_admin() ) {

			wp_enqueue_style( 'editor_plus-plugin-frontend-style', EDPL_EDITORPLUS_PLUGIN_URL . 'dist/style-gutenberg-frontend-style.css', array(), $dynamic_version, false );

			if ( editorplus_is_extension_enabled( 'animation_builder' ) ) {
				wp_enqueue_script( 'editor_plus-plugin-frontend-script', EDPL_EDITORPLUS_PLUGIN_URL . 'assets/scripts/frontend.js', array(), $dynamic_version, true );
			}

			$extra_css = apply_filters( 'editor_plus_plugin_css', '' );
			wp_add_inline_style( 'editor_plus-plugin-frontend-style', $extra_css );

		}

		// for admin settings.
		if ( in_array( $suffix, array( 'settings_page_editor_plus', 'toplevel_page_gutenberg-edit-site' ), true ) ) {

			wp_enqueue_style( 'editor-plus-admin-style', EDPL_EDITORPLUS_PLUGIN_URL . 'dist/style-admin.css', array( 'wp-components' ), $dynamic_version );
			wp_enqueue_script( 'editor-plus-admin-script', EDPL_EDITORPLUS_PLUGIN_URL . 'dist/admin.js', array( 'lodash', 'wp-api', 'wp-i18n', 'wp-components', 'wp-element', 'wp-editor' ), $dynamic_version, true );

			// loading localized variables.
			$extension_manager = new EditorPlus_Extensions_Manager();
			$settings          = $extension_manager->get_settings();

			wp_localize_script(
				'editor-plus-admin-script',
				'editor_plus_extension',
				$settings->extensions
			);

			wp_localize_script(
				'editor-plus-admin-script',
				'eplus_data',
				array(
					'rest_url'      => get_rest_url(),
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'plugin_assets' => plugins_url( 'assets', __FILE__ ),
				)
			);

		}
	}


	/**
	 * All registerations should be done here
	 *
	 * @return void
	 */
	public function register() {
		add_options_page(
			__( 'Editor Plus', 'editor_plus' ),
			__( 'Editor Plus', 'editor_plus' ),
			'manage_options',
			'editor_plus',
			function() {
				?>
					<div id="editor-plus-root"></div>
				<?php
			}
		);
	}

	/**
	 * Load actions/filters
	 *
	 * @return void
	 */
	private function init() {

		$styles_manager = new EditorPlus_Styles_Manager();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'register' ) );
		add_action( 'admin_print_scripts-{settings_page_editor_plus}', array( $this, 'enqueue_assets' ) );
		add_filter(
			'upload_mimes',
			function ( $ep_mime_types ) {
				$ep_mime_types['json'] = 'text/plain'; // Adding .json extension.
				return $ep_mime_types;
			},
			1,
			1
		);
	}
}


/**
 * Function works with the EditorPlus class instance
 *
 * @return object EditorPlus
 */
function edpl_editorplus() {
	return EditorPlus::instance();
}

add_action( 'plugins_loaded', 'edpl_editorplus' );
