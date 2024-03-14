<?php
/**
 * Main file for initializing admin.
 *
 * @package Omnipress\Admin
 */

namespace Omnipress\Admin;

use Omnipress\Helpers;
use Omnipress\Transient;

/**
 * Main admin class to initialize our admin functionalities.
 *
 * @since 1.1.0
 */
class Init {

	/**
	 * Current object instance.
	 *
	 * @var Init
	 */
	protected static $instance;

	/**
	 * Current object instance.
	 *
	 * @return Init
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class construct.
	 */
	public function __construct() {

		if ( ( ! Helpers::is_localhost() ) && ( ! Helpers::is_test_site() ) ) {
			add_action( 'init', array( $this, 'handle_usage_stats' ) );
			add_action( 'admin_notices', array( $this, 'consent_notice' ) );
		}
		add_action( 'admin_init', array( $this, 'force_cold_boot' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer', array( $this, 'add_app_root' ) );
		add_action( 'after_setup_theme', array( $this, 'update_demo_styles' ) );

		if ( file_exists( ABSPATH . 'wp-content/css/theme-woo.css' ) ) {
			add_action(
				'wp_enqueue_scripts',
				function () {
					wp_enqueue_style( 'omnipress-woo-css', '/wp-content/css/theme-woo.css', array(), OMNIPRESS_VERSION );
				}
			);
		}

		add_action( 'save_post', array( $this, 'save_selected_fonts' ) );
	}

	/**
	 * Get current demo's global styles.
	 *
	 * @param array $theme_json previous theme gobal styles.
	 * @return mixed
	 */
	public function get_demo_global_styles( $theme_json ) {
		$global_style_url    = \get_option( 'demo_styles_link' );
		$styles_request      = wp_remote_get( $global_style_url );
		$styles_request_body = wp_remote_retrieve_body( $styles_request );

		return $theme_json->update_with( json_decode( $styles_request_body, true ) );
	}

	/**
	 * Update Demo styles.
	 *
	 * @return void
	 */
	public function update_demo_styles() {
		if ( wp_theme_has_theme_json() ) {
			add_filter(
				'wp_theme_json_data_theme',
				function ( $theme_data ) {
					return $this->get_demo_global_styles( $theme_data );
				}
			);
		}
	}

	public function save_selected_fonts( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		Helpers::get_blocks_attributes( parse_blocks( get_the_content( null, false, $post_id ) ), $attrs ); // Here, $attrs is passed as reference.

		$fonts = Helpers::extract_fonts_from_attrs( $attrs );

		$post_type = get_post_type( $post_id );

		switch ( $post_type ) {
			case 'wp_template':
			case 'wp_template_part':
				return update_option( "omnipress_global_{$post_type}_fonts", $fonts );

			default:
				return update_post_meta( $post_id, 'omnipress_post_type_fonts', $fonts );
		}
	}

	/**
	 * Handle the "force_cold_boot" request.
	 *
	 * @return void
	 */
	public function force_cold_boot() {

		if ( empty( $_GET['force_cold_boot'] ) ) {
			return;
		}

		if ( empty( $_GET['op_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['op_nonce'] ) ), '_omnipress_nonce' ) ) {
			return;
		}

		do_action( 'omnipress_doing_force_cold_boot' );

		$transient = new Transient();
		$transient->delete_all();

		wp_safe_redirect( remove_query_arg( array( 'force_cold_boot', 'op_nonce' ) ) );
		exit;
	}

	/**
	 * Adds the required html element for library app modal in post/page editor.
	 *
	 * @return void
	 */
	public function add_app_root() {
		?>
		<div id="omnipress"></div>
		<?php
	}

	/**
	 * Register our admin menus.
	 *
	 * @return void
	 */
	public function register_admin_menus() {
		add_menu_page(
			__( 'Omnipress', 'omnipress' ),
			__( 'Omnipress', 'omnipress' ),
			'manage_options',
			'omnipress',
			function () {},
			'data:image/svg+xml;base64,' . base64_encode( @file_get_contents( OMNIPRESS_PATH . 'assets/images/omnipress-dashboard-menu-icon.svg' ) )
		);
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $hook_prefix ) {

		if (
			'post-new.php' !== $hook_prefix &&
			'post.php' !== $hook_prefix &&
			'site-editor.php' !== $hook_prefix &&
			'toplevel_page_omnipress' !== $hook_prefix
		) {
			return;
		}

		if ( 'post.php' === $hook_prefix || 'post-new.php' === $hook_prefix ) {
			switch ( get_post_type() ) {
				case 'post':
				case 'page':
					break;

				default:
					return;
			}
		}

		do_action( 'omnipress_before_admin_scripts', $hook_prefix );

		$assets = include OMNIPRESS_PATH . 'assets/admin/index.asset.php';

		wp_enqueue_style( 'omnipress-admin-style', OMNIPRESS_URL . 'assets/admin/admin.css', array( 'wp-components' ), $assets['version'], 'all' );

		wp_enqueue_script( 'omnipress-admin-script', OMNIPRESS_URL . 'assets/admin/index.js', $assets['dependencies'], $assets['version'], true );

		$current_user = get_userdata( get_current_user_id() );

		$localize = array(
			'nonce'            => wp_create_nonce( '_omnipress_nonce' ),
			'omnipressVersion' => OMNIPRESS_VERSION,
			'isOmnipressPage'  => 'toplevel_page_omnipress' === $hook_prefix,
			'urls'             => array(
				'home'        => home_url(),
				'wpDashboard' => admin_url(),
			),
		);

		if ( $current_user ) {
			$localize['user'] = array(
				'firstName' => $current_user ? $current_user->first_name : '',
				'username'  => $current_user ? $current_user->user_login : '',
				'avatarURL' => $current_user ? get_avatar_url( $current_user->ID ) : '',
			);
		}

		wp_localize_script( 'omnipress-admin-script', '_omnipress', apply_filters( 'omnipress_localize_admin_script', $localize ) );

		do_action( 'omnipress_after_admin_scripts', $hook_prefix );
	}

	/**
	 * Returns usage stats class object.
	 */
	private function get_stats_object() {
		if ( ! class_exists( 'EverestThemes_Stats' ) ) {
			require_once OMNIPRESS_PATH . 'includes/Libraries/stats/class-stats.php';
		}

		return \EverestThemes_Stats::get_instance( OMNIPRESS_FILE, 'https://ps.w.org/omnipress/assets/icon-128X128.png' );
	}

	public function handle_usage_stats() {

		$stats = $this->get_stats_object();

		if ( ! empty( $_POST['omnipress_consent_optin'] ) ) {
			if ( wp_verify_nonce( $_POST['omnipress_consent_optin'], 'omnipress_consent_optin' ) ) {
				update_option( 'omnipress_consent_optin', 'yes' );
			}
		}

		if ( ! empty( $_POST['omnipress_consent_skip'] ) ) {
			if ( wp_verify_nonce( $_POST['omnipress_consent_skip'], 'omnipress_consent_skip' ) ) {
				set_transient( 'omnipress_consent_skip', 'yes', MONTH_IN_SECONDS );
			}
		}

		if ( 'yes' === get_option( 'omnipress_consent_optin' ) ) {
			$stats->init();
		}
	}

	/**
	 * Show consent notice.
	 *
	 * @return void
	 */
	public function consent_notice() {

		if ( 'yes' === get_option( 'omnipress_consent_optin' ) || 'yes' === get_transient( 'omnipress_consent_skip' ) ) {
			return;
		}

		?>
		<style>
			#omnipress-consent-notice {
				border: none;
				padding-top: 10px;
			}

			#omnipress-consent-notice .consent-header {
				padding: 5px 0;
				background: #a8bc17;
				width: 100%;
			}

			#omnipress-consent-notice .consent-header h2 {
				color: #ffffff;
				font-size: 23px;
				padding-left: 5px;
			}

			#omnipress-consent-notice form .button-primary {
				background: #175fff;
				border: #175fff;
			}

			#omnipress-consent-notice .consent-footer {
				margin: 10px 0;
			}

			#omnipress-consent-notice details {
				cursor: pointer;
			}
		</style>
		<div id="omnipress-consent-notice" class="notice">
			<div class="consent-header">
				<h2><?php esc_html_e( '👋 Welcome to Omnipress! Count me in for important updates.', 'omnipress' ); ?></h2>
			</div>

			<div class="consent-body">
				<p><?php esc_html_e( 'Stay informed about important security updates, new features, exclusive deals, and allow non sensitive diagnostic tracking.', 'omnipress' ); ?></p>

				<form method="post">
					<button class="button button-primary" type="submit"><?php esc_html_e( 'Allow and Continue', 'omnipress' ); ?></button>
					<?php wp_nonce_field( 'omnipress_consent_optin', 'omnipress_consent_optin' ); ?>
				</form>
			</div>

			<div class="consent-footer">
				<details>
					<summary><?php esc_html_e( 'Learn more', 'omnipress' ); ?></summary>
					<h4><?php esc_html_e( 'You are granting these permissions.', 'omnipress' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Your Profile Information', 'omnipress' ); ?></li>
						<li><?php esc_html_e( 'Your site Information ( URL, WP Version, PHP info, Plugins & Themes )', 'omnipress' ); ?></li>
						<li><?php esc_html_e( 'Plugin notices ( updates, announcements, marketing, no spam )', 'omnipress' ); ?></li>
						<li><?php esc_html_e( 'Plugin events ( activation, deactivation, and uninstall )', 'omnipress' ); ?></li>
					</ul>

					<form method="post">
						<button class="button button-link" type="submit"><?php esc_html_e( 'Skip Now', 'omnipress' ); ?></button>
						<?php wp_nonce_field( 'omnipress_consent_skip', 'omnipress_consent_skip' ); ?>
					</form>
				</details>
			</div>
		</div>
		<?php
	}
}
