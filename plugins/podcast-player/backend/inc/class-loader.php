<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 */

namespace Podcast_Player\Backend\Inc;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Core\Singleton;

/**
 * The admin-specific functionality of the plugin.
 *
 * Register custom widget and custom shortcode functionality. Enqueue admin area
 * scripts and styles.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 * @author     vedathemes <contact@vedathemes.com>
 */
class Loader extends Singleton {
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'wp-color-picker' );

		/**
		 * Enqueue admin stylesheet.
		 */
		wp_enqueue_style(
			'ppadmin',
			PODCAST_PLAYER_URL . 'backend/css/podcast-player-admin.css',
			array(),
			PODCAST_PLAYER_VERSION,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_media();

		wp_enqueue_script(
			'ppadmin',
			PODCAST_PLAYER_URL . 'backend/js/admin.build.js',
			array( 'jquery', 'wp-color-picker' ),
			PODCAST_PLAYER_VERSION,
			true
		);

		// Theme localize scripts data.
		wp_localize_script(
			'ppadmin',
			'podcastplayerImageUploadText',
			array(
				'title'    => esc_html__( 'Set Image', 'podcast-player' ),
				'btn_text' => esc_html__( 'Select', 'podcast-player' ),
				'img_text' => esc_html__( 'Set Image', 'podcast-player' ),
			)
		);

		// Theme localize scripts data.
		wp_localize_script(
			'ppadmin',
			'ppjsAdmin',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'security'  => wp_create_nonce( 'podcast-player-admin-ajax-nonce' ),
				'ispremium' => apply_filters( 'podcast_player_is_premium', false ),
			)
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_editor_scripts() {
		$menus    = wp_get_nav_menus();
		$menus    = wp_list_pluck( $menus, 'name', 'term_id' );
		$menu_arr = array();
		foreach ( $menus as $key => $val ) {
			$menu_arr[] = array(
				'value' => $key,
				'label' => $val,
			);
		}

		$style_arr = array();
		$styles    = Get_Fn::get_styles();
		foreach ( $styles as $key => $val ) {
			$style_arr[] = array(
				'value' => $key,
				'label' => $val,
			);
		}

		// Scripts data.
		$cdata          = array();
		$cdata['menu']  = $menu_arr;
		$cdata['style'] = $style_arr;
		$cdata['stSup'] = Get_Fn::get_style_supported();
		$ppjs_settings  = apply_filters( 'podcast_player_mediaelement_settings', array() );

		wp_enqueue_script(
			'podcast-player-block-js',
			PODCAST_PLAYER_URL . 'backend/js/blocks.build.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-api-fetch', 'wp-block-editor', 'wp-server-side-render', 'jquery' ),
			PODCAST_PLAYER_VERSION,
			true
		);

		wp_enqueue_style(
			'podcast-player-block-css',
			PODCAST_PLAYER_URL . 'frontend/css/podcast-player-editor.css',
			array(),
			PODCAST_PLAYER_VERSION
		);

		wp_enqueue_script(
			'ppeditor',
			PODCAST_PLAYER_URL . 'frontend/js/ppeditor.build.js',
			array( 'jquery', 'mediaelement-core' ),
			PODCAST_PLAYER_VERSION,
			true
		);

		wp_localize_script( 'ppeditor', 'podcastPlayerData', $cdata );
		wp_localize_script( 'ppeditor', 'ppmejsSettings', $ppjs_settings );
	}

	/**
	 * Register the script to fix mediaelement migrate error.
	 *
	 * Mediaelement migrate WP script forces to use 'mejs-' class prefix for all
	 * mediaelements. Podcast player only work with 'ppjs__' class prefix. Hence,
	 * fixing this issue.
	 *
	 * @since    1.0.0
	 */
	public function mediaelement_migrate_error_fix() {
		/*
		 * This file must be loaded before mediaelement-migrate script.
		 * Mediaelement-migrate script loads in header in various admin windows.
		 * Therefore, loading in header.
		 */
		$in_footer = false;

		/**
		 * Register public facing stylesheets.
		 */
		wp_enqueue_script(
			'podcast-player-mmerrorfix',
			PODCAST_PLAYER_URL . 'frontend/js/mmerrorfix.js',
			array( 'jquery', 'mediaelement-core' ),
			PODCAST_PLAYER_VERSION,
			$in_footer
		);
	}

	/**
	 * Add SVG definitions to the site footer.
	 *
	 * @since 1.0.0
	 */
	public function svg_icons() {

		/**
		 * This files defines all svg icons used by the plugin.
		 */
		require_once PODCAST_PLAYER_DIR . 'frontend/images/icons.svg';
	}

	/**
	 * Display message on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function admin_notices() {
		// Check what admin page we are on.
		$current_screen = get_current_screen();

		// Screens on which notice is to be displayed.
		$enabled_screens = array( 'dashboard', 'themes', 'plugins', 'update-core.php' );

		if ( ! ( in_array( $current_screen->id, $enabled_screens, true ) || in_array( $current_screen->parent_file, $enabled_screens, true ) ) ) {
			return;
		}

		// Podcast Player Admin Notice.
		if ( PODCAST_PLAYER_VERSION !== get_option( 'podcast-player-admin-notice' ) ) {
			include_once PODCAST_PLAYER_DIR . '/backend/partials/pp-notifications.php';

			?>
			<style type="text/css" media="screen">

				.pp-welcome-notice p {
					margin: 0.25em !important;
				}

				.common-links {
					padding: 5px 0;
				}

				.pp-link {
					display: inline-block;
					line-height: 1;
				}

				.pp-link a {
					padding: 0;
				}

				.pp-link + .pp-link {
					margin-left: 10px;
					padding: 0 0 0 10px !important;
					border-left: 2px solid #999;
				}

			</style>

			<?php
		}

		if ( defined( 'PP_PRO_VERSION' ) && version_compare( PP_PRO_VERSION, '5.2.0', '<' ) ) {
			?>
			<div class="notice-warning notice is-dismissible pp-welcome-notice">
				<p><?php esc_html_e( 'There is an update available to Podcast Player Pro. Please update to Podcast Player Pro v5.2.0. If you have not received an automated update notice, please login to our website and download latest version.', 'podcast-player' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Display message on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function dismiss_notices() {
		if ( isset( $_GET['pp-dismiss'] ) && check_admin_referer( 'pp-dismiss-' . get_current_user_id() ) ) {
			update_option( 'podcast-player-admin-notice', PODCAST_PLAYER_VERSION );
		}
	}

	/**
	 * Load script for compatibility with PP Pro 4.8.1.
	 *
	 * @since    6.6.0
	 */
	public function compat_admin_scripts() {
		if ( ! defined( 'PP_PRO_VERSION' ) || ! defined( 'PP_PRO_URL' ) ) {
			return;
		}

		$current_screen = get_current_screen();
		$load_on = array(
			'toplevel_page_pp-options',
			'podcast-player_page_pp-settings',
			'podcast-player_page_pp-toolkit',
			'podcast-player_page_pp-help',
		);
		if ( $current_screen && in_array( $current_screen->id, $load_on, true ) ) {

			/**
			 * Enqueue admin scripts.
			 */
			wp_enqueue_script(
				'ppproadminoptions',
				PP_PRO_URL . 'admin/js/admin-options.build.js',
				array( 'ppadminoptions' ),
				PP_PRO_VERSION,
				true
			);

			/**
			 * Enqueue admin stylesheet.
			 */
			wp_enqueue_style(
				'ppproadminoptions',
				PP_PRO_URL . 'admin/css/admin-options.css',
				array(),
				PP_PRO_VERSION,
				'all'
			);
		}
	}
}
