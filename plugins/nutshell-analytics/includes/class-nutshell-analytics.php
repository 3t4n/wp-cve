<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

/**
 * Nutshell WP
 *  - Contains primary functionality - Nutshell script integrations
 */

// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
// - Scripts are intentionally not output with enqueue functions
// - We don't want any WP or plugin functionality messing with these scripts - moving, combining, minifying, etc.

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Nutshell_Analytics {

	/**
	* Singleton pattern
	*  - This makes working with WP Hooks fairly nice
	*/
	private static $instance = null;
	public static function instance() {
		return is_null( self::$instance ) ? new self() : self::$instance;
	}
	private function __construct() {

		// Already instantiated, and new called on the class directly
		if ( ! is_null( self::$instance ) ) {
			$class = get_called_class();
			throw new Exception( $class . ' uses forced singleton pattern - call ' . $class . '::instance() instead to get existing instance.' );
		}

		self::$instance = $this;

		$this->init_hooks();
	}

	/**
	 * Get Nutshell ID from option table
	 */
	private $nutshell_instance_id = null;
	public function get_nutshell_instance_id() {
		if ( is_null( $this->nutshell_instance_id ) ) {
			// will return false if option doesn't exist
			$this->nutshell_instance_id = get_option( 'mcfx_id' );
			if ( is_string( $this->nutshell_instance_id ) && stripos( $this->nutshell_instance_id, 'ns-' ) === false ) {
				$this->nutshell_instance_id = 'ns-' . $this->nutshell_instance_id;
			}
		}
		return $this->nutshell_instance_id;
	}

	/**
	 * Set nutshell ID to new value
	 */
	private function set_nutshell_instance_id( $nutshell_instance_id ) {
		update_option( 'nutshell_instance_id', $nutshell_instance_id );
		$this->nutshell_instance_id = $nutshell_instance_id;
	}

	/**
	 * Get extra nutshell integrations list and whether they are enabled
	 */
	private $nutshell_integrations = null;
	private function get_nutshell_integrations() {
		if ( is_null( $this->nutshell_integrations ) ) {
			$integrations            = [];
			$available_integrations  = scandir( NUTSHELL_ANALYTICS_INTEGRATIONS_DIR );
			$configured_integrations = get_option( 'mcfx_integrations' );
			if ( empty( $configured_integrations ) || ! is_array( $configured_integrations ) ) {
				$configured_integrations = [];
			}

			// Parse integration files
			foreach ( $available_integrations as $filename ) {

				$integration_file = NUTSHELL_ANALYTICS_INTEGRATIONS_DIR . DIRECTORY_SEPARATOR . $filename;
				$filename         = basename( $integration_file );
				$file_parts       = explode( '.', $filename );
				$ext              = array_pop( $file_parts );

				// Only allow PHP files given that we will include them directly
				if ( 'php' !== $ext ) {
					continue;
				}

				$integration_data = get_file_data(
					$integration_file,
					[
						'name'        => 'Name',
						'description' => 'Description',
						'link'        => 'Link',
						'slug'        => 'Slug',
					]
				);

				// Automatic values and fallbacks
				$integration_data['enabled']  = 0;
				$integration_data['filepath'] = $integration_file;
				if ( empty( $integration_data['slug'] ) ) {
					$integration_data['slug'] = array_shift( $file_parts );
				}
				$file_slug = $integration_data['slug'];
				if ( empty( $integration_data['name'] ) ) {
					$integration_data['name'] = ucwords( preg_replace( '/[^0-9a-z]+/', ' ', $file_slug ) );
				}
				if ( empty( $integration_data['description'] ) ) {
					$integration_data['description'] = $filename;
				}

				$integrations[ $file_slug ] = $integration_data;
			}

			foreach ( $configured_integrations as $config_slug => $config ) {
				$integrations[ $config_slug ] = array_merge( $integrations[ $config_slug ], $config );
			}
			$this->nutshell_integrations = $integrations;
		}

		return $this->nutshell_integrations;
	}

	/**
	 * Check if this looks like a live site
	 * - based on database name
	 */
	public function is_live() {
		return ( false === stripos( DB_NAME, 'fxstage' ) && 'project' !== DB_NAME );
	}

	public function init_hooks() {

		// Admin notices
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );

		// Admin settings page
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'add_menu' ] );

		// Output scripts - only if nutshell tracking is enabled
		$nutshell_script_active = get_option( 'mcfx_script_active' );
		if ( $nutshell_script_active ) {
			add_action( 'wp_head', [ $this, 'header_scripts' ] );
			add_action( 'wp_footer', [ $this, 'footer_scripts' ] );
		}

		// Exclude JS from WP Rocket Optimizations
		add_filter( 'get_rocket_option_exclude_js', [ $this, 'wp_rocket_exclude_js' ] );
		add_filter( 'get_rocket_option_exclude_defer_js', [ $this, 'wp_rocket_exclude_js' ] );
		add_filter( 'get_rocket_option_exclude_inline_js', [ $this, 'wp_rocket_exclude_js' ] );
		add_filter( 'get_rocket_option_delay_js_exclusions', [ $this, 'wp_rocket_exclude_js' ] );
	}

	public function admin_notices() {

		$nutshell_instance_id   = $this->get_nutshell_instance_id();
		$nutshell_script_active = get_option( 'mcfx_script_active' );
		$nutshell_incomplete    = ( $nutshell_script_active && empty( $nutshell_instance_id ) );

		// Show an alert if:
		if (
			// Missing either Nutshell ID
			( $nutshell_incomplete )
			// and this is a live site
			&& $this->is_live()
			// and we're not on the settings page
			&& ! $this->is_nutshell_settings_page()
		) {
			?>
				<br/>
				<div class="notice notice-warning update-nag">
					<?php echo esc_html_e( 'Nutshell Analytics Plugin is not configured.', 'nutshell' ); ?>
					<a href='<?php echo esc_html( admin_url( 'options-general.php?page=nutshell-analytics-settings' ) ); ?>'>
						<?php esc_html_e( 'Click here to configure.', 'nutshell' ); ?>
					</a>
				</div>
			<?php
		}
	}

	/**
	 * Check if current admin screen is nutshell settings page
	 */
	public function is_nutshell_settings_page() {
		$admin_screen = get_current_screen();
		return ( 'settings_page_nutshell-analytics-settings' === $admin_screen->base );
	}

	/**
	 * Register settings fields
	 */
	public function register_settings() {
		register_setting( 'mcfx_wp_settings', 'custom_mcfx_config' );
		register_setting( 'mcfx_wp_settings', 'mcfx_id' );
		register_setting(
			'mcfx_wp_settings',
			'mcfx_integrations',
			[
				'type'    => 'array',
				'default' => [],
			]
		);
		register_setting( 'mcfx_wp_settings', 'mcfx_script_active', [ 'default' => 1 ] );
	}

	/**
	 * Nutshell Settings Page
	 */
	public function add_menu() {
		add_submenu_page(
			'options-general.php',
			'Nutshell Settings',
			'Nutshell Settings',
			'manage_options',
			'nutshell-analytics-settings',
			[ $this, 'settings_page' ]
		);
	}
	public function settings_page() {
		require NUTSHELL_ANALYTICS_ADMIN_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'nutshell-analytics-settings.php';
	}

	/**
	 * Output scripts in header
	 */
	public function header_scripts() {
		// Output main nutshell script
		$nutshell_instance_id = $this->get_nutshell_instance_id();
		if ( ! empty( $nutshell_instance_id ) ) {
			ob_start();
			require NUTSHELL_ANALYTICS_FRONTEND_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'scripts-head.php';
			$output = ob_get_clean();
			// Remove blank lines
			$output = preg_replace( "/(^|\n)\s*($|\n)+/", '$1', $output );

			echo wp_kses(
				$output,
				[
					'script' => [
						'type'            => [],
						'data-registered' => [],
					],
				]
			);
		}
	}

	/**
	 * Output scripts in footer
	 */
	public function footer_scripts() {

		ob_start();
		$integrations = $this->get_nutshell_integrations();
		if ( ! empty( $integrations ) ) {
			require NUTSHELL_ANALYTICS_FRONTEND_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'scripts-integrations.php';
		}

		$output = ob_get_clean();
		// Remove blank lines
		$output = preg_replace( "/(^|\n)\s*($|\n)+/", '$1', $output );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		// This is the contents of included files that are escaped within each file
		echo $output;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Exclude JS from WP Rocket optimizations
	 */
	public function wp_rocket_exclude_js( $exclude ) {
		if ( empty( $exclude ) ) {
			$exclude = [];
		}
		$exclude[] = 'marketingcloudfx';
		$exclude[] = 'mcfx';
		$exclude[] = 'nutshell';
		$exclude[] = '(.*)mcfx.js';
		return array_unique( $exclude );
	}

}

/**
 * Returns the main instance of Nutshell_Analytics to prevent the need to use globals.
 */
function nutshell_analytics() {
	// Only check for the version in the plugins folder. If the older mu-plugin version exists, we still want to run
    // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
	// - we are not calling our own custom action, but rather an existing WP action
	if ( in_array( 'marketingcloudfx-wp/marketingcloudfx-wp.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
        // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals
		// Inform the user that the two plugins can't run simultaneously
		add_action(
			'admin_notices',
			function() {
				?>
				<div class="notice notice-warning update-nag">
					<?php echo esc_html_e( '"Nutshell Analytics" is superseded by "WebFX Core Services & MCFX - MarketingCloudFX" and will not run while it\'s active. You can safely deactivate the "Nutshell Analytics" plugin without impacting tracking to remove this message.', 'nutshell' ); ?>
				</div>
				<?php
			},
			3
		);
		// If requesting the settings page from the link in the plugin description, redirect to the superseding plugin's settings page
		add_action(
			'admin_init',
			function() {
				global $pagenow;
                // phpcs:disable WordPress.Security.NonceVerification.Recommended
				//  - This is from clicking a menu item, no nonce required
				if ( 'options-general.php' === $pagenow && 'nutshell-analytics-settings' === $_GET['page'] ) {
                    // phpcs:enable WordPress.Security.NonceVerification.Recommended
					header( 'Location: ' . admin_url( 'options-general.php?page=mcfx-wp-settings' ) );
					die();
				}
			}
		);
		// Add the settings page to avoid an error about the page not existing
		add_action(
			'admin_menu',
			function() {
				add_submenu_page(
					'options-general.php',
					'Nutshell Settings',
					'Nutshell Settings',
					'manage_options',
					'nutshell-analytics-settings',
					function() {}
				);
			}
		);
		// Hide the settings page from the menu to avoid clutter/confusion
		add_filter(
			'submenu_file',
			function( $submenu_file ) {
				remove_submenu_page( 'options-general.php', 'nutshell-analytics-settings' );
				return $submenu_file;
			}
		);
		return;
	}
	return Nutshell_Analytics::instance();
}
add_action( 'plugins_loaded', 'nutshell_analytics' );

// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN
