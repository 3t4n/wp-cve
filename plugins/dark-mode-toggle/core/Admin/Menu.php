<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Admin\Menu service class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle\Admin;

use DarkModeToggle\Base;
use DarkModeToggle\Service;

defined( 'ABSPATH' ) || die();

/**
 * Add plugin menu.
 */
class Menu extends Base implements Service {
	/**
	 * Initialize service.
	 */
	public function init() {
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_filter( 'script_loader_tag', array( $this, 'defer_script' ), 10, 2 );

		add_filter( 'plugin_action_links_' . $this->plugin_base, array( $this, 'add_action_links' ) );

		add_action( 'admin_notices', array( $this, 'welcome' ) );
	}

	/**
	 * Register styles and scripts.
	 */
	public function register() {
		// Admin stylesheet.
		wp_register_style( 'darkmodetg-admin', ( $this->plugin_url . 'assets/css/admin.min.css' ), array(), $this->plugin_ver, 'all' );

		// Alpine script.
		wp_register_script( 'alpine', ( $this->plugin_url . 'assets/js/alpine.min.js' ), array(), '3.12.3', true );

		// Admin script.
		wp_register_script( 'darkmodetg-admin', ( $this->plugin_url . 'assets/js/admin.js' ), array( 'alpine', 'jquery', 'wp-color-picker' ), $this->plugin_ver, true );
	}

	/**
	 * Add menu pages.
	 */
	public function add_menu() {
		$settings = add_theme_page(
			esc_html_x( 'Dark Mode Toggle', 'Page title', 'dark-mode-toggle' ),
			esc_html_x( 'Dark Mode', 'Menu title', 'dark-mode-toggle' ),
			darkmodetg( 'options' )->cap_main(),
			'darkmodetg-settings',
			array( $this, 'settings_page' )
		);

		add_action( ( 'admin_print_styles-' . $settings ), array( $this, 'settings_assets' ) );
	}

	/**
	 * Settings page HTML.
	 */
	public function settings_page() {
		require $this->plugin_path . 'inc/settings/index.php';
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function settings_assets() {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style( 'darkmodetg-admin' );
		wp_style_add_data( 'darkmodetg-admin', 'rtl', 'replace' );

		wp_enqueue_script( 'alpine' );

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script( 'darkmodetg-admin' );
	}

	/**
	 * Defer Alpine script.
	 *
	 * @param string $tag The <script> tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 *
	 * @return string
	 */
	public function defer_script( $tag, $handle ) {
		if ( 'alpine' === $handle ) {
			return str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Add link to the settings page.
	 *
	 * @param array $actions An array of plugin action links.
	 *
	 * @return array
	 */
	public function add_action_links( $actions ) {
		$settings_link = ( '<a href="' . esc_url( menu_page_url( 'darkmodetg-settings', false ) ) . '">' . esc_html__( 'Settings', 'dark-mode-toggle' ) . '</a>' );
		array_unshift( $actions, $settings_link );

		$premium_link = ( '<a target="_blank" style="font-weight: bold;" href="' . esc_url( darkmodetg( 'utility' )->pro_buy() ) . '">' . esc_html__( 'Get Premium', 'dark-mode-toggle' ) . '</a>' );
		array_unshift( $actions, $premium_link );

		return $actions;
	}

	/**
	 * Welcome notice.
	 */
	public function welcome() {
		if ( get_option( 'darkmodetg_welcome', false ) ) {
			delete_option( 'darkmodetg_welcome' );
			?>
			<div class="updated notice notice-success is-dismissible">
				<p>
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %s: Settings page link. */
							__( 'To get started with Dark Mode Toggle, visit our <a href="%s" target="_blank">settings page</a>.', 'dark-mode-toggle' ),
							esc_url( menu_page_url( 'darkmodetg-settings', false ) )
						),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					);
					?>
				</p>
				<p>
					<a href="<?php menu_page_url( 'darkmodetg-settings', true ); ?>" target="_blank" class="button">
						<?php esc_html_e( 'Dark Mode Toggle Settings', 'dark-mode-toggle' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}
}
