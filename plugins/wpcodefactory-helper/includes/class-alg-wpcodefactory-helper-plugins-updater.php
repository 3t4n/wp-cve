<?php
/**
 * WPFactory Helper - Plugins Updater Class
 *
 * @version 1.5.4
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WPCodeFactory_Helper_Plugins_Updater' ) ) :

class Alg_WPCodeFactory_Helper_Plugins_Updater {

	/**
	 * plugins_to_update.
	 *
	 * @since 1.5.4
	 */
	public $plugins_to_update;

	/**
	 * themes_to_update.
	 *
	 * @since 1.5.4
	 */
	public $themes_to_update;

	/**
	 * items_to_update.
	 *
	 * @since 1.5.4
	 */
	public $items_to_update;

	/**
	 * update_checkers.
	 *
	 * @since 1.5.4
	 */
	public $update_checkers;

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @see     https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @todo    (dev) move `lib` folder up (or to the `assets` folder)?
	 * @todo    (dev) update library
	 */
	function __construct() {

		require_once( 'lib/plugin-update-checker/plugin-update-checker.php' );

		$is_admin = is_admin();

		// Handle plugins update
		$this->plugins_to_update = array();
		$all_plugins = get_option( 'alg_wpcodefactory_helper_plugins', array() );
		if ( '' == $all_plugins ) {
			$all_plugins = array();
		}
		foreach ( $all_plugins as $plugin_slug ) {
			$plugin_file_path = $this->get_plugin_file_path_from_slug( $plugin_slug );
			if ( ! file_exists( $plugin_file_path ) ) {
				continue;
			}
			$this->plugins_to_update[] = $plugin_slug;
			$this->add_updater( $plugin_slug, $plugin_file_path );
			// "Site key" action links etc.
			$plugin_file = $this->get_plugin_file_from_slug( $plugin_slug );
			if ( $is_admin ) {
				add_filter( 'plugin_action_links_' . $plugin_file, array( $this, 'add_plugin_manage_key_action_link' ), 10, 4 );
				add_action( 'after_plugin_row_'    . $plugin_file, array( $this, 'maybe_add_after_plugin_row_key_error_message' ), 1, 3 );
			}
		}

		// Handle themes update
		$this->themes_to_update = array();
		$all_themes = get_option( 'alg_wpcodefactory_helper_themes', array() );
		if ( '' == $all_themes ) {
			$all_themes = array();
		}
		foreach ( $all_themes as $theme_slug ) {
			$theme_file_path = get_theme_root() . '/' . $theme_slug . '/style.css';
			if ( ! file_exists( $theme_file_path ) ) {
				continue;
			}
			$this->themes_to_update[] = $theme_slug;
			$this->add_updater( $theme_slug, $theme_file_path, false );
		}
		if ( $is_admin ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'add_theme_manage_key_links' ) );
		}

		// Items to update
		$this->items_to_update = array_merge( $this->plugins_to_update, $this->themes_to_update );

	}

	/**
	 * add_theme_manage_key_links.
	 *
	 * @version 1.3.0
	 * @since   1.1.0
	 *
	 * @todo    (dev) minimize JS
	 */
	function add_theme_manage_key_links() {
		if ( empty( $this->themes_to_update ) ) {
			return;
		}
		$status_messages = array();
		foreach ( $this->themes_to_update as $theme_slug ) {
			$status_messages[ $theme_slug ] = ( ! alg_wpcfh_is_site_key_valid( $theme_slug ) ? strip_tags( alg_wpcfh_get_site_key_status_message( $theme_slug ) ) :
				__( 'License is valid.', 'wpcodefactory-helper' ) );
		}
		wp_enqueue_script(  'alg-wpfh-theme-manage-key-links',
			alg_wpcodefactory_helper()->plugin_url() . '/includes/js/alg-wpfh-theme-manage-key-links.js', array( 'jquery' ), alg_wpcodefactory_helper()->version, true );
		wp_localize_script( 'alg-wpfh-theme-manage-key-links', 'alg_wpfh_object', array(
			'themes_to_update' => $this->themes_to_update,
			'manage_key_text'  => __( 'Manage site key', 'wpcodefactory-helper' ),
			'admin_url'        => admin_url(),
			'status_messages'  => $status_messages,
		) );
	}

	/**
	 * add_updater.
	 *
	 * @version 1.5.0
	 * @since   1.1.0
	 *
	 * @todo    (dev) `Puc_v4_Factory` version
	 */
	function add_updater( $item_slug, $item_file_path, $is_plugin = true ) {

		// Build update checker
		$updater_url = ALG_WPCODEFACTORY_HELPER_UPDATE_SERVER . '/?alg_update_action=get_metadata&alg_update_slug=' . $item_slug;
		$this->update_checkers[ $item_slug ] = Puc_v4_Factory::buildUpdateChecker( $updater_url, $item_file_path, $item_slug );

		// Query args
		$updater_query_args_function = ( $is_plugin ? 'add_updater_query_args' : 'add_updater_query_args_theme' );
		$this->update_checkers[ $item_slug ]->addQueryArgFilter( array( $this, $updater_query_args_function ) );

		// Remove (some) scheduler actions
		if ( apply_filters( 'wpfactory_helper_remove_actions', true ) && isset( $this->update_checkers[ $item_slug ]->scheduler ) ) {
			$scheduler = $this->update_checkers[ $item_slug ]->scheduler;
			remove_action( 'admin_init', array( $scheduler, 'maybeCheckForUpdates' ) );
			remove_action( 'load-update-core.php', array( $scheduler, 'maybeCheckForUpdates' ) );
			remove_action( 'load-update.php', array( $scheduler, 'maybeCheckForUpdates' ) );
			remove_action( ( $is_plugin ? 'load-plugins.php' : 'load-themes.php' ), array( $scheduler, 'maybeCheckForUpdates' ) );
			remove_action( 'upgrader_process_complete', array( $scheduler, 'upgraderProcessComplete' ), 11 );
		}

	}

	/**
	 * get_plugin_file_from_slug.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_plugin_file_from_slug( $plugin_slug ) {
		return $plugin_slug . '/' . $plugin_slug . '.php';
	}

	/**
	 * get_plugin_file_path_from_slug.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_plugin_file_path_from_slug( $plugin_slug ) {
		return WP_PLUGIN_DIR . '/' . $this->get_plugin_file_from_slug( $plugin_slug );
	}

	/**
	 * add_updater_query_args_theme.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function add_updater_query_args_theme( $query ) {
		$plugin_slug = str_replace( 'puc_request_update_query_args_theme-', '', current_filter() );
		$query['alg_site_key'] = alg_wpcfh_get_site_key( $plugin_slug );
		$query['alg_site_url'] = alg_wpcodefactory_helper()->site_url;
		return $query;
	}

	/**
	 * add_updater_query_args.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_updater_query_args( $query ) {
		$plugin_slug = str_replace( 'puc_request_info_query_args-', '', current_filter() );
		$query['alg_site_key'] = alg_wpcfh_get_site_key( $plugin_slug );
		$query['alg_site_url'] = alg_wpcodefactory_helper()->site_url;
		return $query;
	}

	/**
	 * get_plugin_slug_from_file.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_plugin_slug_from_file( $plugin_file ) {
		$plugin_slug = explode( '/', $plugin_file );
		$plugin_slug = $plugin_slug[1];
		$plugin_slug = substr( $plugin_slug, 0, -4 );
		return $plugin_slug;
	}

	/**
	 * add_plugin_manage_key_action_link.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_plugin_manage_key_action_link( $actions, $plugin_file, $plugin_data, $context ) {
		$plugin_slug    = $this->get_plugin_slug_from_file( $plugin_file );
		$url            = admin_url( 'options-general.php?page=wpcodefactory-helper&item_slug=' . $plugin_slug );
		$custom_actions = array( '<a href="' . $url . '">' . __( 'Manage site key', 'wpcodefactory-helper' ) . '</a>' );
		return array_merge( $actions, $custom_actions );
	}

	/**
	 * maybe_add_after_plugin_row_key_error_message.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 */
	function maybe_add_after_plugin_row_key_error_message( $plugin_file, $plugin_data, $status ) {
		$plugin_slug = $this->get_plugin_slug_from_file( $plugin_file );
		if ( ! alg_wpcfh_is_site_key_valid( $plugin_slug ) ) {
			echo '<tr class="plugin-update-tr" id="' . $plugin_slug . '-update-site-key" data-slug="' . $plugin_slug . '" data-plugin="' . $plugin_file . '">' .
				'<td colspan="4" class="plugin-update colspanchange">' .
					'<div class="update-message notice inline notice-warning notice-alt">' .
						'<p>' . alg_wpcfh_get_site_key_status_message( $plugin_slug ) . '</p>' .
					'</div>' .
				'</td>' .
			'</tr>';
		}
	}

}

endif;

return new Alg_WPCodeFactory_Helper_Plugins_Updater();
