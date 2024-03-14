<?php
/**
 * Plugin Name: Disable Toolbar
 * Description: Adds an option in Settings > General that controls who sees the WP Toolbar when viewing your site.
 * Version: 1.0
 * Author: Michael Dance
 * Author URI: http://mikedance.com
 * License: GPL2
 */


class MD_Disable_Toolbar {


	static $option;


	/**
	 * Add hooks.
	 */
	static function init() {

		self::$option = 'md_disable_toolbar';

		add_action( 'init', array( __CLASS__, 'run' ) );
		add_action( 'admin_init', array( __CLASS__, 'add_setting' ) );

		add_action( 'admin_print_styles-user-edit.php', array( __CLASS__, 'hide_per_user_option' ) );
		add_action( 'admin_print_styles-profile.php', array( __CLASS__, 'hide_per_user_option' ) );

	}


	/**
	 * Disable the toolbar if necessary.
	 */
	static function run() {

		if ( !is_user_logged_in() )
			return;

		$user = wp_get_current_user();

		if ( !self::user_can_view_toolbar( $user ) )
			add_filter( 'show_admin_bar', '__return_false' );

	}


	/**
	 * Register our setting.
	 */
	static function add_setting() {
		add_settings_field( self::$option, 'Toolbar', array( __CLASS__, 'setting_html' ), 'general' );
		register_setting( 'general', self::$option );
	}


	/**
	 * Output the setting html.
	 */
	static function setting_html() {

		?><p>Hide the Toolbar for the following user roles when viewing the site:</p>

		<p><?php
			global $wp_roles;
			foreach( $wp_roles->role_names as $role => $label ) {
				?><label for="<?php echo self::$option; ?>_<?php echo esc_attr( $role ); ?>">
					<input
						name="<?php echo self::$option; ?>[]"
						id="<?php echo self::$option; ?>_<?php echo esc_attr( $role ); ?>"
						type="checkbox"
						value="<?php echo esc_attr( $role ); ?>"
						<?php checked( in_array( $role, (array) get_option( self::$option, array() ) ) ); ?>
					/>
					<?php echo esc_html( $label ); ?>
				</label><br /><?php
			}
		?></p><?php

	}


	/**
	 * Don't let the current user see the Toolbar option in their profile
	 * if the toolbar is hidden for their role already.
	 */
	static function hide_per_user_option() {

		// get user
		$user = isset( $_GET['user_id'] ) ? (int) $_GET['user_id'] : 0;
		if ( !$user )
			$user = wp_get_current_user();
		else
			$user = get_user_by( 'id', $user );

		// maybe hide option
		if ( !self::user_can_view_toolbar( $user ) ) {
			?><style type="text/css">.show-admin-bar{display:none;}</style><?php
		}

	}


	/**
	 * Conditional check to see if the current user should see the Toolbar.
	 */
	static function user_can_view_toolbar( $user ) {

		$roles = (array) get_option( self::$option, array() );
		if ( array_intersect( $roles, $user->roles ) )
			return false;

		return true;

	}


}
MD_Disable_Toolbar::init();