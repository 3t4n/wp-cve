<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Apwpultimate_Admin {

	function __construct() {

		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'apwpultimate_post_sett_metabox' ));

		// Action to save metabox
		add_action( 'save_post', array( $this, 'apwpultimate_save_metabox_value' ));

		// Action to register admin menu
		add_action( 'admin_menu', array( $this, 'apwpultimate_ultimate_register_menu' ), 9 );

		// Action to register plugin settings
		add_action ( 'admin_init', array( $this, 'apwpultimate_ultimate_register_settings' ));

		// Filter to add row action in category table
		add_filter( APWPULTIMATE_CAT.'_row_actions', array( $this, 'apwpultimate_ultimate_add_tax_row_data' ), 10, 2 );

		// Filter to add row action in category table
		add_filter( 'post_row_actions', array( $this, 'apwpultimate_ultimate_add_post_row_data' ), 10, 2 );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package Audio Player with Playlist Ultimate 
	 * @since 1.0.0
	 */
	function apwpultimate_post_sett_metabox() {
		add_meta_box( 'apwpultimate-post-sett', __( 'Audio Player - Settings', 'banner-anything-on-click' ), array($this, 'apwpultimate_post_sett_mb_content'), APWPULTIMATE_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_post_sett_mb_content() {
		include_once( APWPULTIMATE_DIR .'/includes/admin/metabox/apwpultimate-post-sett-metabox.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_save_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )	// Check Revision
		|| ( $post_type !=  APWPULTIMATE_POST_TYPE ) )							// Check if current post type is supported.
		{
			return $post_id;
		}

		$prefix = APWPULTIMATE_META_PREFIX; // Taking metabox prefix

		// Taking variables	
		$artist_name	= isset( $_POST[$prefix.'artist_name'] )	? apwpultimate_ultimate_clean( $_POST[$prefix.'artist_name'] )		: '';
		$audio_file		= isset( $_POST[$prefix.'audio_file'] )		? apwpultimate_ultimate_clean_url( $_POST[$prefix.'audio_file'] )	: '';
		$duration		= isset( $_POST[$prefix.'duration'] )		? apwpultimate_ultimate_clean( $_POST[$prefix.'duration'] )			: '';

		update_post_meta( $post_id, $prefix.'artist_name', $artist_name );	
		update_post_meta( $post_id, $prefix.'audio_file', $audio_file );
		update_post_meta( $post_id, $prefix.'duration', $duration );
	}

	/**
	 * Function to register admin menus
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_ultimate_register_menu() {
		add_submenu_page( 'edit.php?post_type='.APWPULTIMATE_POST_TYPE, __('Settings', 'audio-player-with-playlist-ultimate'), __('Settings', 'audio-player-with-playlist-ultimate'), 'manage_options', 'apwpultimate-ultimate-settings', array( $this, 'apwpultimate_ultimate_settings_page' ));

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.APWPULTIMATE_POST_TYPE, __('Upgrade to PRO - Audio Player with Playlist Ultimate', 'audio-player-with-playlist-ultimate'), '<span style="color:#ff2700">'.__('Upgrade to PRO', 'audio-player-with-playlist-ultimate').'</span>', 'manage_options', 'wpapwpu-premium', array( $this, 'apwpultimate_ultimate_premium_page' ));
	}

	/**
	 * Function to handle the setting page html
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_ultimate_settings_page() {
		include_once( APWPULTIMATE_DIR . '/includes/admin/settings/apwpultimate-settings.php' );
	}

	/**
	 * Function to handle the upgrade to pro page html
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_ultimate_premium_page() {
		include_once( APWPULTIMATE_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Function register setings
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_ultimate_register_settings() {

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && 'apwpultimate-ultimate-plugin-notice' == $_GET['message'] ) {
			set_transient( 'apwpultimate_ultimate_install_notice', true, 604800 );
		}

		// Reset default settings
		if( ! empty( $_POST['apwpultimate_reset_settings'] ) && check_admin_referer( 'apwpultimate_reset_settings', 'apwpultimate_reset_sett_nonce' ) ) {

			// Default Settings
			apwpultimate_ultimate_set_default_settings();
		}

		// Register Setting
		register_setting( 'apwpultimate_ultimate_plugin_options', 'apwpultimate_ultimate_options', array( $this, 'apwpultimate_ultimate_validate_options' ) );
	}

	/**
	 * Validate Settings Options
	 * 
	 * @package Audio Player with Playlist Ultimate
	 * @since 1.0.0
	 */
	function apwpultimate_ultimate_validate_options( $input ) {
		$input['custom_css']	= isset($input['custom_css'])	? sanitize_textarea_field( $input['custom_css'] ) : '';
		return $input;
	}


	/**
	 * Function to add category row action
	 * 
	 * @since 1.0
	 */
	function apwpultimate_ultimate_add_tax_row_data( $actions, $tag ) {
		return array_merge( array( 'wpos_id' => esc_html__('ID:', 'audio-player-with-playlist-ultimate').' ' .esc_html( $tag->term_id ) ), $actions );
	}

	/**
	 * Function to add post row action
	 * 
	 * @since 1.2.6
	 */
	function apwpultimate_ultimate_add_post_row_data( $actions, $post ) {
		return array_merge( array( 'wpos_id' => esc_html__('ID:', 'audio-player-with-playlist-ultimate') .' '. esc_html( $post->ID ) ), $actions );
	}
}

$apwpultimate_admin = new Apwpultimate_Admin();
