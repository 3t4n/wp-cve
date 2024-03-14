<?php
/**
 *
 * Plugin Name: ThemeZee Widget Bundle
 * Plugin URI: https://themezee.com/plugins/widget-bundle/
 * Description: A collection of our most popular widgets, neatly bundled into a single plugin. The Plugin includes advanced widgets for Recent Posts, Recent Comments, Tabbed Content, Social Icons and more.
 * Author: ThemeZee
 * Author URI: https://themezee.com/
 * Version: 1.6
 * Text Domain: themezee-widget-bundle
 * Domain Path: /languages/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package ThemeZee Widget Bundle
 * Copyright(C) 2022, ThemeZee.com - support@themezee.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main ThemeZee_Widget_Bundle Class
 *
 * @package ThemeZee Widget Bundle
 */
class ThemeZee_Widget_Bundle {

	/**
	 * Call all Functions to setup the Plugin
	 *
	 * @uses ThemeZee_Widget_Bundle::constants() Setup the constants needed
	 * @uses ThemeZee_Widget_Bundle::includes() Include the required files
	 * @uses ThemeZee_Widget_Bundle::setup_actions() Setup the hooks and actions
	 * @return void
	 */
	static function setup() {

		// Setup Constants.
		self::constants();

		// Setup Translation.
		add_action( 'plugins_loaded', array( __CLASS__, 'translation' ) );

		// Include Files.
		self::includes();

		// Setup Action Hooks.
		self::setup_actions();

		// Disables the block editor from managing widgets in the Gutenberg plugin.
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

		// Disables the block editor from managing widgets.
		add_filter( 'use_widgets_block_editor', '__return_false' );

		// Add Admin Notice on widgets screen.
		add_action( 'admin_notices', array( __CLASS__, 'widgets_admin_notice' ) );

		// Dismiss Notice.
		add_action( 'init', array( __CLASS__, 'dismiss_notice' ) );
	}

	/**
	 * Setup plugin constants
	 *
	 * @return void
	 */
	static function constants() {

		// Define Plugin Name.
		define( 'TZWB_NAME', 'ThemeZee Widget Bundle' );

		// Define Version Number.
		define( 'TZWB_VERSION', '1.5.2' );

		// Plugin Folder Path.
		define( 'TZWB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

		// Plugin Folder URL.
		define( 'TZWB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		// Plugin Root File.
		define( 'TZWB_PLUGIN_FILE', __FILE__ );
	}

	/**
	 * Load Translation File
	 *
	 * @return void
	 */
	static function translation() {

		load_plugin_textdomain( 'themezee-widget-bundle', false, dirname( plugin_basename( TZWB_PLUGIN_FILE ) ) . '/languages/' );

	}

	/**
	 * Include required files
	 *
	 * @return void
	 */
	static function includes() {

		// Include Admin Classes.
		require_once TZWB_PLUGIN_DIR . '/includes/admin/class-themezee-plugins-page.php';

		// Include Settings Classes.
		require_once TZWB_PLUGIN_DIR . '/includes/settings/class-tzwb-settings.php';
		require_once TZWB_PLUGIN_DIR . '/includes/settings/class-tzwb-settings-page.php';

		// Include Widget Classes.
		require_once TZWB_PLUGIN_DIR . '/includes/widgets/widget-recent-comments.php';
		require_once TZWB_PLUGIN_DIR . '/includes/widgets/widget-recent-posts.php';
		require_once TZWB_PLUGIN_DIR . '/includes/widgets/widget-social-icons.php';
		require_once TZWB_PLUGIN_DIR . '/includes/widgets/widget-tabbed-content.php';
	}

	/**
	 * Setup Action Hooks
	 *
	 * @see https://codex.wordpress.org/Function_Reference/add_action WordPress Codex
	 * @return void
	 */
	static function setup_actions() {

		// Include active modules
		add_action( 'init', array( __CLASS__, 'modules' ), 12 );

		// Register all widgets.
		add_action( 'widgets_init', array( __CLASS__, 'register_widgets' ) );

		// Enqueue Frontend Widget Styles.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );

		// Enqueue Scripts and Styles on widgets admin screen.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_scripts' ) );

		// Register Image Sizes.
		add_action( 'init', array( __CLASS__, 'add_image_size' ) );

		// Add Settings link to Plugin actions.
		add_filter( 'plugin_action_links_' . plugin_basename( TZWB_PLUGIN_FILE ), array( __CLASS__, 'plugin_action_links' ) );

		// Add Widget Bundle Box to Plugin Overview Page.
		add_action( 'themezee_plugins_overview_page', array( __CLASS__, 'plugin_overview_page' ) );
	}

	/**
	 * Include active Modules
	 *
	 * @return void
	 */
	static function modules() {

		// Get Plugin Options
		$options = TZWB_Settings::instance();

		// Include Widget Visibility class unless it is already activated with Jetpack or Toolkit plugin.
		if ( true === $options->get( 'widget_visibility' ) and ! class_exists( 'Jetpack_Widget_Conditions' ) and ! class_exists( 'TZTK_Widget_Visibility' ) ) :

			require TZWB_PLUGIN_DIR . '/includes/modules/class-tzwb-widget-visibility.php';

		endif;
	}

	/**
	 * Register Widgets
	 *
	 * @return void
	 */
	static function register_widgets() {

		// Get Settings.
		$instance = TZWB_Settings::instance();
		$options = $instance->get_all();

		// Register Widgets if enabled.
		if ( true == $options['recent_comments'] ) :
			register_widget( 'TZWB_Recent_Comments_Widget' );
		endif;

		if ( true == $options['recent_posts'] ) :
			register_widget( 'TZWB_Recent_Posts_Widget' );
		endif;

		if ( true == $options['social_icons'] ) :
			register_widget( 'TZWB_Social_Icons_Widget' );
		endif;

		if ( true == $options['tabbed_content'] ) :
			register_widget( 'TZWB_Tabbed_Content_Widget' );
		endif;
	}

	/**
	 * Enqueue Widget Styles
	 *
	 * @return void
	 */
	static function enqueue_styles() {

		// Return early if theme handles styling.
		if ( current_theme_supports( 'themezee-widget-bundle' ) ) :
			return;
		endif;

		// Load stylesheet only if widgets are active.
		if ( is_active_widget( 'TZWB_Recent_Comments_Widget', false, 'tzwb-recent-comments' )
		or is_active_widget( 'TZWB_Recent_Posts_Widget', false, 'tzwb-recent-posts' )
		or is_active_widget( 'TZWB_Social_Icons_Widget', false, 'tzwb-social-icons' )
		or is_active_widget( 'TZWB_Tabbed_Content_Widget', false, 'tzwb-tabbed-content' )
		) :

			// Enqueue Plugin Stylesheet.
			wp_enqueue_style( 'themezee-widget-bundle', TZWB_PLUGIN_URL . 'assets/css/themezee-widget-bundle.css', array(), TZWB_VERSION );

		endif;
	}

	/**
	 * Enqueue Admin Styles
	 *
	 * @param string $hook Hook suffix for the current admin page.
	 * @return void
	 */
	static function enqueue_admin_scripts( $hook ) {

		// Embed Widget Highlight only on widget page.
		if ( 'widgets.php' != $hook ) :
			return;
		endif;

		wp_enqueue_style( 'tzwb-widget-bgcolor', TZWB_PLUGIN_URL . 'assets/css/tzwb-widget-bgcolor.css', array(), TZWB_VERSION );
	}

	/**
	 * Add custom image size for post thumbnails in widgets
	 *
	 * @return void
	 */
	static function add_image_size() {

		// Check if theme defines custom image size.
		if ( current_theme_supports( 'themezee-widget-bundle' ) ) :

			$theme_support = get_theme_support( 'themezee-widget-bundle' );

			// Set custom image size.
			if ( isset( $theme_support[0]['thumbnail_size'] ) && is_array( $theme_support[0]['thumbnail_size'] ) ) :

				$thumbnail_size = $theme_support[0]['thumbnail_size'];
				add_image_size( 'tzwb-thumbnail', $thumbnail_size[0], $thumbnail_size[1], true );

			endif;

		else :

			// Set default image size.
			add_image_size( 'tzwb-thumbnail', 80, 80, true );

		endif;
	}

	/**
	 * Add Settings link to the plugin actions
	 *
	 * @param array $actions Array of all plugin action links.
	 * @return array $actions Plugin action links
	 */
	static function plugin_action_links( $actions ) {

		$settings_link = array( 'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=themezee-plugins&tab=widgets' ), esc_html__( 'Settings', 'themezee-widget-bundle' ) ) );

		return array_merge( $settings_link, $actions );
	}

	/**
	 * Add widget bundle box to plugin overview admin page
	 *
	 * @return void
	 */
	static function plugin_overview_page() {

		$plugin_data = get_plugin_data( __FILE__ );
		?>

		<dl>
			<dt>
				<h4><?php echo esc_html( $plugin_data['Name'] ); ?></h4>
				<span><?php printf( esc_html__( 'Version %s', 'themezee-widget-bundle' ),  esc_html( $plugin_data['Version'] ) ); ?></span>
			</dt>
			<dd>
				<p><?php echo wp_kses_post( $plugin_data['Description'] ); ?><br/></p>
				<a href="<?php echo admin_url( 'options-general.php?page=themezee-plugins&tab=widgets' ); ?>" class="button button-primary"><?php esc_html_e( 'Plugin Settings', 'themezee-widget-bundle' ); ?></a>&nbsp;
				<a href="<?php echo esc_url( 'https://themezee.com/docs/widget-bundle-documentation/?utm_source=plugin-overview&utm_medium=button&utm_campaign=widget-bundle&utm_content=documentation' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'View Documentation', 'themezee-widget-bundle' ); ?></a>
			</dd>
		</dl>

		<?php
	}

	/**
	 * Display admin notice on widgets screen.
	 *
	 * @return void
	 */
	static function widgets_admin_notice() {
		global $pagenow;

		if ( in_array( $pagenow, array( 'widgets.php' ) ) && ! isset( $_GET['page'] ) && ! get_transient( 'tzwb_admin_notice_dismissed' ) && current_user_can( 'manage_options' ) ) : ?>

			<div class="notice notice-info">
				<p>
					<?php esc_html_e( 'ThemeZee Widget Bundle is not compatible with Blocks and therefore shows the Classic Widgets Editor screen.', 'themezee-widget-bundle' ); ?>
					<a href="<?php echo wp_nonce_url( add_query_arg( array( 'tzwb_admin_notice_action' => 'dismiss_notice', 'tzwb_admin_notice_transient' => 'dismissed' ) ), 'tzwb_admin_notice_dismiss', 'tzwb_admin_notice_dismiss_nonce' ); ?>"><?php _e( 'Dismiss Notice', 'themezee-widget-bundle' ); ?></a>
				</p>
			</div>

		<?php
		endif;
	}

	/**
	 * Dismiss admin notices when Dismiss links are clicked
	 *
	 * @return void
	 */
	static function dismiss_notice() {

		// Return early if tzwb_admin_notice_action was not fired.
		if ( ! isset( $_REQUEST['tzwb_admin_notice_action'] ) ) {
			return;
		}

		if ( ! isset( $_GET['tzwb_admin_notice_dismiss_nonce'] ) || ! wp_verify_nonce( $_GET['tzwb_admin_notice_dismiss_nonce'], 'tzwb_admin_notice_dismiss' ) ) {
			wp_die( __( 'Security check failed', 'themezee-widget-bundle' ), __( 'Error', 'themezee-widget-bundle' ), array( 'response' => 403 ) );
		}

		if ( isset( $_GET['tzwb_admin_notice_transient'] ) ) {
			set_transient( 'tzwb_admin_notice_' . $_GET['tzwb_admin_notice_transient'], 1, DAY_IN_SECONDS * 90 );
			wp_redirect( remove_query_arg( array( 'tzwb_admin_notice_action', 'tzwb_admin_notice_transient', 'tzwb_admin_notice_dismiss_nonce' ) ) );
			exit;
		}
	}
}

// Run Plugin.
ThemeZee_Widget_Bundle::setup();
