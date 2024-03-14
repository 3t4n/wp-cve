<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/*------------------------------------------------------------------------------------------------*/
/* !ACTIVATION ================================================================================== */
/*------------------------------------------------------------------------------------------------*/

register_activation_hook( SFML_FILE, 'sfml_activate' );
/**
 * Trigger `wp_die()` on plugin activation if the server configuration does not fit. Or, set a transient for admin notices.
 */
function sfml_activate() {
	$dies = array();

	// The plugin needs the request URI.
	if ( empty( $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'] ) && empty( $_SERVER['REQUEST_URI'] ) ) {
		$dies[] = 'error_no_request_uri';
	}

	// Apache.
	if ( sfml_is_apache() ) {
		require_once( ABSPATH . WPINC . '/functions.php' );

		if ( ! got_mod_rewrite() ) {
			$dies[] = 'error_no_mod_rewrite';
		}
	}
	// IIS7.
	elseif ( sfml_is_iis7() ) {
		require_once( ABSPATH . 'wp-admin/includes/misc.php' );

		if ( ! iis7_supports_permalinks() ) {
			$dies[] = 'error_no_mod_rewrite';
		}
	}
	// None.
	elseif ( ! sfml_is_iis7() && ! sfml_is_apache() && ! sfml_is_nginx() ) {
		$dies[] = 'error_unknown_server_conf';
	}

	// `die()`s: don't activate the plugin.
	if ( $dies ) {
		// I18n early loading.
		sfml_lang_init();

		$dies = array_filter( array_map( 'sfml_notice_message', $dies ) );
		/* translators: 1 is the plugin name. */
		$dies = sprintf( __( '%s has not been activated.', 'sf-move-login' ), '<strong>Move Login</strong>' ) . '<br/>' . implode( '<br/>', $dies );

		wp_die( $dies, __( 'Error', 'sf-move-login' ), array( 'back_link' => true ) );
	}

	/**
	 * Perhaps we'll need to display some notices. Add the rewrite rules to the .htaccess/web.config file.
	 * 1 means "Update the file".
	 */
	set_transient( 'sfml_activation-' . get_current_user_id(), '1' );

	/**
	 * Triggered when Move Login is activated.
	 *
	 * @since 2.4
	 */
	do_action( 'sfml_activate' );
}


/*------------------------------------------------------------------------------------------------*/
/* !DEACTIVATION ================================================================================ */
/*------------------------------------------------------------------------------------------------*/

register_deactivation_hook( SFML_FILE, 'sfml_deactivate' );
/**
 * Remove rewrite rules from the `.htaccess`/`web.config` file on deactivation.
 */
function sfml_deactivate() {
	sfml_include_rewrite_file();

	sfml_write_rules( array() );

	/**
	 * Triggered when Move Login is deactivated.
	 *
	 * @since 2.4
	 */
	do_action( 'sfml_deactivate' );
}


/*------------------------------------------------------------------------------------------------*/
/* !UTILITIES =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Messages used for notices and `die()`s.
 *
 * @param (string) $message_id A message ID.
 *
 * @return (string|array) A message if `$message_id` is a valid ID. An array otherwize.
 */
function sfml_notice_message( $message_id ) {
	static $messages;

	if ( isset( $messages ) ) {
		return isset( $messages[ $message_id ] ) ? $messages[ $message_id ] : '';
	}

	$file = sfml_is_iis7() ? '<code>web.config</code>' : '<code>.htaccess</code>';
	$link = '<a href="' . esc_url( is_multisite() ? network_admin_url( 'settings.php?page=move-login' ) : admin_url( 'options-general.php?page=move-login' ) ) . '">Move Login</a>';

	$messages = array(
		/* translators: 1 is the plugin name. */
		'error_no_request_uri'      => sprintf( __( 'It seems your server configuration prevents the plugin to work properly. %s won\'t work.', 'sf-move-login' ), '<strong>Move Login</strong>' ),
		/* translators: 1 is the plugin name. */
		'error_no_mod_rewrite'      => sprintf( __( 'It seems the url rewrite module is not activated on your server. %s won\'t work.', 'sf-move-login' ), '<strong>Move Login</strong>' ),
		/* translators: 1, 2, and 3 are server technologies (Apache, Nginx, IIS7), 4 is the plugin name. */
		'error_unknown_server_conf' => sprintf( __( 'It seems your server does not use %1$s, %2$s, nor %3$s. %4$s won\'t work.', 'sf-move-login' ), '<i>Apache</i>', '<i>Nginx</i>', '<i>IIS7</i>', '<strong>Move Login</strong>' ),
		/* translators: 1 is the plugin name, 2 is a file name, 3 is a "Move Login" link. */
		'error_file_not_writable'   => sprintf( __( '%1$s needs access to the %2$s file. Please visit the %3$s settings page and copy/paste the given code into the %2$s file.', 'sf-move-login' ), '<strong>Move Login</strong>', $file, $link ),
		/* translators: 1 is a server technology (Nginx), 2 is a "Move Login" link, 3 is the plugin name. */
		'updated_is_nginx'          => sprintf( __( 'It seems your server uses a %1$ system. You have to edit the rewrite rules by yourself in the configuration file. Please visit the %2$s settings page and take a look at the rewrite rules. %3$s is running but won\'t work correctly until you deal with those rewrite rules.', 'sf-move-login' ), '<i>Nginx</i>', $link, '<strong>Move Login</strong>' ),
	);

	return isset( $messages[ $message_id ] ) ? $messages[ $message_id ] : '';
}
