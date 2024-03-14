<?php
/**
 * Plugin Name: WPPP Persistent DB Connection
 * Description: Data base Drop-In to overwrite default WordPress data base to use persistent mysql connections.
 * Version: 2.0.0
 * Author: Bj&ouml;rn Ahrens
 * Author URI: http://www.bjoernahrens.de
 * Plugin URI: http://wordpress.org/plugins/wp-performance-pack
 */


/**
 * Simply an extension of wpdb overriding db_connect to use persistent connections
 * So not to break your site in case WordPress core gets changed, WordPress version is
 * tested.
 */

class persistent_wpdb extends wpdb {

	/**
	 * Connect to and select database.
	 *
	 * If $allow_bail is false, the lack of database connection will need
	 * to be handled manually.
	 * 
	 * This method replaces wpdb's native method and sets persistent database connections.
	 *
	 * @param bool $allow_bail Optional. Allows the function to bail. Default true.
	 * @return null|bool True with a successful connection, false on failure.
	 */
	public function db_connect( $allow_bail = true ) {

		$this->is_mysql = true;

		/*
		 * Deprecated in 3.9+ when using MySQLi. No equivalent
		 * $new_link parameter exists for mysqli_* functions.
		 */
		$new_link = defined( 'MYSQL_NEW_LINK' ) ? MYSQL_NEW_LINK : true;
		$client_flags = defined( 'MYSQL_CLIENT_FLAGS' ) ? MYSQL_CLIENT_FLAGS : 0;

		if ( $this->use_mysqli ) {
			$this->dbh = mysqli_init();

			// mysqli_real_connect doesn't support the host param including a port or socket
			// like mysql_connect does. This duplicates how mysql_connect detects a port and/or socket file.
			$port = null;
			$socket = null;
			$host = $this->dbhost;
			$port_or_socket = strstr( $host, ':' );
			if ( ! empty( $port_or_socket ) ) {
				$host = substr( $host, 0, strpos( $host, ':' ) );
				$port_or_socket = substr( $port_or_socket, 1 );
				if ( 0 !== strpos( $port_or_socket, '/' ) ) {
					$port = intval( $port_or_socket );
					$maybe_socket = strstr( $port_or_socket, ':' );
					if ( ! empty( $maybe_socket ) ) {
						$socket = substr( $maybe_socket, 1 );
					}
				} else {
					$socket = $port_or_socket;
				}
			}

			if ( WP_DEBUG ) {
				mysqli_real_connect( $this->dbh, 'p:' . $host, $this->dbuser, $this->dbpassword, null, $port, $socket, $client_flags );
			} else {
				@mysqli_real_connect( $this->dbh, 'p:' . $host, $this->dbuser, $this->dbpassword, null, $port, $socket, $client_flags );
			}

			if ( $this->dbh->connect_errno ) {
				$this->dbh = null;

				/* It's possible ext/mysqli is misconfigured. Fall back to ext/mysql if:
		 		 *  - We haven't previously connected, and
		 		 *  - WP_USE_EXT_MYSQL isn't set to false, and
		 		 *  - ext/mysql is loaded.
		 		 */
				$attempt_fallback = true;

				if ( $this->has_connected ) {
					$attempt_fallback = false;
				} elseif ( defined( 'WP_USE_EXT_MYSQL' ) && ! WP_USE_EXT_MYSQL ) {
					$attempt_fallback = false;
				} elseif ( ! function_exists( 'mysql_pconnect' ) ) {
					$attempt_fallback = false;
				}

				if ( $attempt_fallback ) {
					$this->use_mysqli = false;
					$this->db_connect();
				}
			}
		} else {
			if ( WP_DEBUG ) {
				$this->dbh = mysql_pconnect( $this->dbhost, $this->dbuser, $this->dbpassword, $new_link, $client_flags );
			} else {
				$this->dbh = @mysql_pconnect( $this->dbhost, $this->dbuser, $this->dbpassword, $new_link, $client_flags );
			}
		}

		if ( ! $this->dbh && $allow_bail ) {
			wp_load_translations_early();

			// Load custom DB error template, if present.
			if ( file_exists( WP_CONTENT_DIR . '/db-error.php' ) ) {
				require_once( WP_CONTENT_DIR . '/db-error.php' );
				die();
			}

			$this->bail( __( 'Error establishing a database connection.', 'wp-performance-pack' ) );

			return false;
		} elseif ( $this->dbh ) {
			$this->has_connected = true;
			$this->set_charset( $this->dbh );
			$this->ready = true;
			$this->set_sql_mode();
			$this->select( $this->dbname, $this->dbh );

			return true;
		}

		return false;
	}

}

global $wp_version;
if ( version_compare( $wp_version, '3.9.0', '>=' ) && version_compare( $wp_version, '4.7.4', '>=' ) ) {
	$GLOBALS['wpdb'] = new persistent_wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
	define( 'WPPP_PERSISTENT_DB', true );
}