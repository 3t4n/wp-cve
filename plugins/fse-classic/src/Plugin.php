<?php
/**
 * Main plugin class.
 * php version 5.6
 *
 * @package WP_Syntex\FSE_Classic
 */

namespace WP_Syntex\FSE_Classic;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since 1.0
 */
final class Plugin {

	/**
	 * Suffix for minified assets or not. Depending on the debug environment.
	 *
	 * @var string
	 */
	private $assets_suffix;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->assets_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * Plugin init.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function init() {
		/**
		 * Fires before the plugin init.
		 *
		 * @since 1.0
		 *
		 * @param Plugin $plugin Main class instance.
		 */
		do_action_ref_array( 'fse_classic_before_init', [ &$this ] );

		if ( wp_is_block_theme() ) {
			// Loads the plugin only if the theme is a block theme.
			$this->addHooks();
		}

		/**
		 * Fires after the plugin init.
		 *
		 * @since 1.0
		 *
		 * @param Plugin $plugin Main class instance.
		 */
		do_action_ref_array( 'fse_classic_init', [ &$this ] );
	}

	/**
	 * Adds hooks.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private function addHooks() {
		add_action( 'admin_print_styles', [ $this, 'adminStyles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'classicMenuStyles' ] );
		add_action( 'admin_print_scripts', [ $this, 'adminScripts' ] );
		add_action( 'admin_print_footer_scripts', [ $this, 'adminFooterScripts' ] );
		add_action( 'admin_footer', [ $this, 'adminFooterWidgets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'editorAssets' ] );
		add_action( 'after_setup_theme', [ $this, 'supportMenusAndWidgets' ] );
	}

	/**
	 * Loads the widget scripts and registers the Legacy Widget block via JS.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function editorAssets() {
		wp_enqueue_script( 'wp-widgets' );
		wp_add_inline_script( 'wp-widgets', 'wp.widgets.registerLegacyWidgetBlock()' );
		wp_enqueue_style( 'wp-widgets' );
	}

	/**
	 * Loads the classic menu style.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function classicMenuStyles() {
		wp_enqueue_style(
			'fse-classic-menu',
			plugins_url( '/public/css/menu' . $this->assets_suffix . '.css', FSE_CLASSIC_FILE ),
			[],
			FSE_CLASSIC_VERSION
		);
	}

	/**
	 * Runs the widget styles action in the block editor.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function adminStyles() {
		if ( $this->isBlockEditor() ) {
			do_action( 'admin_print_styles-widgets.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	}

	/**
	 * Runs various widget actions in the block editor.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function adminScripts() {
		if ( $this->isBlockEditor() ) {
			do_action( 'load-widgets.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			do_action( 'widgets.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			do_action( 'sidebar_admin_setup' );
			do_action( 'admin_print_scripts-widgets.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	}

	/**
	 * Runs the footer widget scripts action in the block editor.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function adminFooterScripts() {
		if ( $this->isBlockEditor() ) {
			do_action( 'admin_print_footer_scripts-widgets.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	}

	/**
	 * Runs the widgets footer action in the block editor.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function adminFooterWidgets() {
		if ( $this->isBlockEditor() ) {
			do_action( 'admin_footer-widgets.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	}

	/**
	 * Allows to display the menus admin page.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function supportMenusAndWidgets() {
		add_theme_support( 'menus' );
	}

	/**
	 * Check if the current screen runs the block editor.
	 *
	 * @since 1.0
	 *
	 * @return boolean True if the current screen runs the block editor, false otherwise.
	 */
	private function isBlockEditor() {
		$current_screen = get_current_screen();

		if ( ! empty( $current_screen ) ) {
			return $current_screen->is_block_editor();
		}

		return false;
	}
}
