<?php
/**
 * Hester Core Admin class. Hester related pages in WP Dashboard.
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
 * Hester Core Admin Class.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Core_Admin {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Main Hester Core Admin Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Core_Admin
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Core_Admin ) ) {
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

		if ( ! is_admin() ) {
			return;
		}

		// Init Hester Core admin.
		add_action( 'after_setup_theme', array( $this, 'init_admin' ), 99 );

		// Fetch recommended plugins remotely.
		add_filter( 'hester_recommended_plugins', array( $this, 'recommended_plugins' ) );

		// Hester Core Admin loaded.
		do_action( 'hester_core_admin_loaded' );
	}

	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 */
	private function includes() {

		// Demo Library.
		require_once HESTER_CORE_PLUGIN_DIR . 'core/admin/demo-library/class-hester-demo-library.php';
	}

	/**
	 * Admin init.
	 *
	 * @since 1.0.0
	 */
	public function init_admin() {

		if ( ! defined( 'HESTER_THEME_VERSION' ) && ! defined( 'BLOGUN_THEME_VERSION' ) && ! defined( 'BLOGLO_THEME_VERSION' ) ) {
			add_action( 'admin_notices', array( $this, 'theme_required_notice' ) );
			return;
		}

		$theme_name =  hester_core()->theme_name;

		// Add Hester admin page.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 100 );
		add_action( 'admin_menu', array( $this, 'add_changelog_menu' ), 999 );

		// Change about page navigation.
		add_filter( $theme_name . '_dashboard_navigation_items', array( $this, 'update_navigation_items' ) );

		// Add changelog section.
		add_action( $theme_name . '_after_changelog', array( $this, 'changelog' ) );

		// Enqueue scripts & styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		$this->includes();
	}

	/**
	 * Add main menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {

		$theme_name =  hester_core()->theme_name;
		$hester_dashboard = $theme_name . '_dashboard';
		// Remove from Appearance.
		remove_submenu_page( 'themes.php', $theme_name . '-dashboard' );
		remove_submenu_page( null, $theme_name . '-plugins' );

		// Add a new menu item.
		add_menu_page(
			esc_html__( 'Hester', 'hester-core' ),
			'Hester', // This menu cannot be translated because it's used for the $hook prefix.
			apply_filters( 'hester_manage_cap', 'edit_theme_options' ),
			$theme_name . '-dashboard',
			array( $hester_dashboard(), 'render_dashboard' ),
			'dashicons-hester-brand',
			apply_filters( 'hester_menu_position', '999.2' )
		);

		// About page.
		add_submenu_page(
			$theme_name . '-dashboard',
			esc_html__( 'About', 'hester-core' ),
			'About',
			apply_filters( 'hester_manage_cap', 'edit_theme_options' ),
			$theme_name . '-dashboard',
			array( $hester_dashboard(), 'render_dashboard' )
		);

		// Install Plugins page.
		add_submenu_page(
			$theme_name . '-dashboard',
			esc_html__( 'Plugins', 'hester-core' ),
			'Plugins',
			apply_filters( 'hester_manage_cap', 'edit_theme_options' ),
			$theme_name . '-plugins',
			array( $hester_dashboard(), 'render_plugins' )
		);
	}

	/**
	 * Add changelog menu.
	 *
	 * @since 1.0.0
	 */
	public function add_changelog_menu() {

		$theme_name =  hester_core()->theme_name;
		$hester_dashboard = $theme_name . '_dashboard';

		remove_submenu_page( null, $theme_name . '-changelog' );

		// Changelog page.
		add_submenu_page(
			$theme_name . '-dashboard',
			esc_html__( 'Changelog', 'hester-core' ),
			'Changelog',
			apply_filters( 'hester_manage_cap', 'edit_theme_options' ),
			$theme_name . '-changelog',
			array( $hester_dashboard(), 'render_changelog' )
		);
	}

	/**
	 * Add menu items to Hester Dashboard navigation.
	 *
	 * @param array $items Array of navigation items.
	 * @since 1.0.0
	 */
	public function update_navigation_items( $items ) {
		$theme_name =  hester_core()->theme_name;
		$items['dashboard']['url'] = admin_url( 'admin.php?page='. $theme_name . '-dashboard' );
		$items['plugins']['url']   = admin_url( 'admin.php?page='. $theme_name . '-plugins' );
		$items['changelog']['url'] = admin_url( 'admin.php?page='. $theme_name . '-changelog' );

		return $items;
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'hester-core-dashicon',
			HESTER_CORE_PLUGIN_URL . 'assets/css/admin-dashicon' . $suffix . '.css',
			null,
			HESTER_CORE_VERSION
		);
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function changelog() {

		$changelog = HESTER_CORE_PLUGIN_DIR . '/changelog.txt';

		if ( ! file_exists( $changelog ) ) {
			$changelog = esc_html__( 'Changelog file not found.', 'hester-core' );
		} elseif ( ! is_readable( $changelog ) ) {
			$changelog = esc_html__( 'Changelog file not readable.', 'hester-core' );
		} else {
			global $wp_filesystem;

			// Check if the the global filesystem isn't setup yet.
			if ( is_null( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			$changelog = $wp_filesystem->get_contents( $changelog );
		}

		?>
		<div class="hester-section-title hester-core-changelog">
			<h2 class="hester-section-title">
				<span><?php esc_html_e( 'Hester Core Plugin Changelog', 'hester-core' ); ?></span>
				<span class="changelog-version"><?php echo esc_html( sprintf( 'v%1$s', HESTER_CORE_VERSION ) ); ?></span>
			</h2>

		</div><!-- END .hester-section-title -->

		<div class="hester-section hester-columns">

			<div class="hester-column column-12">
				<div class="hester-box hester-changelog">
					<pre><?php echo esc_html( $changelog ); ?></pre>
				</div>
			</div>
		</div><!-- END .hester-columns -->
		<?php
	}

	/**
	 * Display notice.
	 *
	 * @since 1.0.0
	 */
	public function theme_required_notice() {

		echo '<div class="notice notice-warning"><p>' . esc_html__( 'One of Peregrine Themes needs to be installed and activated in order to use Hester Core plugin.', 'hester-core' ) . ' <a href="' . esc_url( admin_url( 'themes.php' ) ) . '"><strong>' . esc_html__( 'Install & Activate', 'hester-core' ) . '</strong></a>.</p></div>';
	}

	/**
	 * Fetch plugins config array from remote server.
	 *
	 * @since 1.0.0
	 * @param array $plugins Array of recommended plugins.
	 */
	public function recommended_plugins( $plugins ) {

		$remote = get_site_transient( 'hester_check_plugin_update' );

		if ( false === $remote ) {

			$response = wp_remote_get(
				'https://peregrine-themes.com/wp-json/api/v1/plugins',
				array(
					// 'user-agent' => 'Hester/' . HESTER_THEME_VERSION . ';',
					'timeout'    => 60,
				)
			);

			if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
				set_site_transient( 'hester_check_plugin_update', 'error', 60 * 60 * 6 );
				return;
			}

			$body    = wp_remote_retrieve_body( $response );
			$plugins = json_decode( $body, true );

			set_site_transient( 'hester_check_plugin_update', $plugins, 60 * 60 * 24 * 3 );
		} elseif ( 'error' === $remote ) {
			return $plugins;
		} else {
			$plugins = $remote;
		}

		return $plugins;
	}
}

/**
 * The function which returns the one Hester_Core_Admin instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $hester_core_admin = hester_core_admin(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function hester_core_admin() {
	return Hester_Core_Admin::instance();
}

hester_core_admin();
