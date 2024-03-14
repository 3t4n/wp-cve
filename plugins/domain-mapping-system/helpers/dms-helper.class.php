<?php

/**
 * Helper class designed to include all kind of general use methods
 *
 * @since 1.7
 */
class DMS_Helper {
	//TODO move all the general helper methods to this class by little little steps ))

	/**
	 * Detect platform running WordPress
	 *
	 * @param  DMS  $DMS
	 *
	 * @return DMS_Wpcs|null
	 */
	public static function detectPlatform( $DMS ) {
		/**
		 * Later we will add different other cases like cpanel, whsm, etc ...
		 * Now we will consider only wpcs.
		 * Load all possible classes
		 */
		if ( DMS_Wpcs::isActive() ) {
			return new DMS_Wpcs( $DMS );
		} else {
			return null;
		}
	}

	/**
	 * Check if mapping save button needs to be disabled after save
	 *
	 * @param  DMS_Platform  $platform
	 *
	 * @return string
	 */
	public static function disableSaveButton( $platform ) {
		if ( ! empty( $platform ) ) {
			$save_delay = get_option( 'dms_' . strtolower( $platform::NAME ) . '_last_save_delay' );
			if ( ! empty( $save_delay ) ) {
				$diff = $save_delay - time();
				if ( $diff > 0 ) {
					return $diff;
				} else {
					delete_option( 'dms_' . strtolower( $platform::NAME ) . '_last_save_delay' );

					return false;
				}
			}
		}

		return false;
	}

	/**
	 * Get mapping domain by map id
	 *
	 * @param  int  $map_id
	 * @param  wpdb  $wpdb
	 *
	 * @return string|null
	 */
	public static function getDomainByMapId( $map_id, $wpdb ) {
		return $wpdb->get_var( $wpdb->prepare( "SELECT `host` FROM `" . $wpdb->prefix . "dms_mappings` WHERE `id`=%d", $map_id ) );
	}

	/**
	 * Get mapping id by domain
	 *
	 * @param  string  $domain
	 * @param  wpdb  $wpdb
	 *
	 * @return int|null
	 */
	public static function getMapIdByDomain( $domain, $wpdb ) {
		return $wpdb->get_var( $wpdb->prepare( "SELECT `id` FROM `" . $wpdb->prefix . "dms_mappings` WHERE `host`=%s", $domain ) );
	}

	/**
	 * Remove domain from mapping list
	 *
	 * @param  int  $map_id
	 * @param  wpdb  $wpdb
	 *
	 * @return mixed
	 */
	public static function removeDomainByMapId( $map_id, $wpdb ) {

		return $wpdb->delete( $wpdb->prefix . 'dms_mapping_values', array( 'host_id' => $map_id ), array( '%d' ) ) !== false
		       && $wpdb->delete( $wpdb->prefix . 'dms_mappings', array( 'id' => $map_id ), array( '%d' ) ) !== false;
	}

	/**
	 * Get base host
	 *
	 * @return string
	 */
	public static function getBaseHost() {
		return trim( wp_parse_url( get_site_url(), PHP_URL_HOST ) );
	}

	/**
	 * Get current Scheme
	 *
	 * @return string
	 */
	public static function getScheme() {
		return trim( wp_parse_url( get_site_url(), PHP_URL_SCHEME ) );
	}

	/**
	 * Get base Path
	 *
	 * @return string
	 */
	public static function getBasePath() {
		$path = wp_parse_url( get_site_url(), PHP_URL_PATH );

		return ! empty( $path ) ? preg_replace( '/\//', '', trim( $path ), 1 ) : '';
	}

	/**
	 * Get actual base host
	 *
	 * @return string
	 */
	public static function getActualBaseHost() {
		if ( is_admin() ) {
			return trim( $_SERVER["HTTP_HOST"], '/' );
		}

		return self::getBaseHost();
	}

	/**
	 * Get woo shop page ID
	 *
	 * @return int|null
	 */
	public static function getShopPageAssociation() {
		return function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : null;
	}

	/**
	 * Get all subpages, which parent page ID, is $id
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public static function getChildPages( $id ) {
		$children = get_children( array(
			'post_parent' => $id,
			'post_type'   => 'page'
		) );

		return $children;
	}

	/**
	 * Check the if the $value is page
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public static function isPagePostType( $value ) {
		return is_numeric( $value ) && get_post_type( $value ) === 'page';
	}

	/**
	 * Get matching domain by value
	 *
	 * @param  wpdb  $wpdb
	 * @param $value
	 *
	 * @return array|object|void|null
	 */
	public static function getMatchingHostByValue( $wpdb, $value ) {

		return $wpdb->get_row( $wpdb->prepare( "SELECT m.id, `host`, `path`, `attachment_id`, mv.host_id FROM `" . $wpdb->prefix . "dms_mapping_values` mv
	    INNER JOIN `" . $wpdb->prefix . "dms_mappings` m on mv.host_id=m.id
	    WHERE mv.value=%s", $value ) );
	}

	/**
	 * Get matching domain by value permalink path
	 *
	 * @param  wpdb  $wpdb
	 * @param  string  $value_permalink_path
	 *
	 * @return array|object|void|null
	 * @since 1.6
	 */
	public static function getMatchingHostByValuePermalinkPath( $wpdb, $value_permalink_path ) {

		return $wpdb->get_row( $wpdb->prepare( "SELECT `host`, `path`, `attachment_id` FROM `" . $wpdb->prefix . "dms_mapping_values` mv
	    INNER JOIN `" . $wpdb->prefix . "dms_mappings` m on mv.host_id=m.id
	    WHERE mv.value_permalink_path=%s", $value_permalink_path ) );
	}

	/**
	 * Get most matching host by params
	 *
	 * @param  wpdb  $wpdb
	 * @param  mixed  $value
	 * @param  string  $dms_path
	 * @param  string  $host
	 *
	 * @return mixed
	 */
	public static function getMatchingHostByAllParams( $wpdb, $value, $dms_path, $host ) {
		return $wpdb->get_row( $wpdb->prepare( "SELECT m.id, `host`, `path`, m.attachment_id, mv.host_id FROM `" . $wpdb->prefix . "dms_mapping_values` mv
	    INNER JOIN `" . $wpdb->prefix . "dms_mappings` m on mv.host_id=m.id
	    WHERE mv.value=%s AND m.host = %s AND POSITION(m.path IN %s) =1", $value, $host, $dms_path ) );
	}

	/**
	 * Get mappings by domain and path
	 *
	 * @param  wpdb  $wpdb
	 * @param  string  $host
	 * @param  string  $path
	 *
	 * @return mixed
	 */
	public static function getMappingByHostAndPath( $wpdb, $host, $path = '' ) {
		$where_args = [ $host ];
		if ( ! empty( $path ) ) {
			$path_where   = " AND m.path=%s";
			$where_args[] = $path;
		} else {
			$path_where = " AND ( m.path='' OR m.path IS NULL )";
		}

		return $wpdb->get_row( $wpdb->prepare( "SELECT `host`, `path`, `id` FROM `" . $wpdb->prefix . "dms_mappings` m
	    WHERE m.host=%s $path_where", $where_args ) );
	}

	/**
	 * Check if Multiple Domain Mapping plugin active
	 *
	 * @return bool
	 */
	public static function checkMdmPluginPresence() {
		return is_plugin_active( 'multiple-domain-mapping-on-single-site/multidomainmapping.php' );
	}

	/**
	 * Remove known permalink filters to get clear ones
	 */
	public static function removeKnownPermalinkFilters() {
		// MDM
		if ( self::checkMdmPluginPresence() ) {
			DMS_Mdm_Import::removeMdmFilters();
		}
	}

	/**
	 * Check whether installed in subdirectory
	 *
	 * @return bool
	 */
	public static function isSubDirectoryInstall() {
		return ! empty( self::getBasePath() );
	}

	/*
	 * Suppose to move sunrise.php to the wp-content folder and define Sunrise true
	 */
	public static function showSunriseNotices() {
		if ( ! is_multisite() || ! is_admin() ) {
			return;
		}
		$DMS          = DMS::getInstance();
		$notification = '';
		if ( ! file_exists( WP_CONTENT_DIR . '/sunrise.php' )
		     || ! self::compareFiles( $DMS->plugin_dir . 'sunrise.php', WP_CONTENT_DIR . '/sunrise.php' )
		     || ! defined( 'SUNRISE' ) ) {

			$notification .= __( 'It looks like you are using a Multisite Network. 
				To enable Domain Mapping System for multisite, add the following string 
				to your <code><b>wp-config.php</b></code> file in <code><b>' . ABSPATH . '</b></code>
				above the line reading <code><b>/* That\'s all, stop editing! Happy publishing. */</b></code>.
				<br> <br> <code>define( \'SUNRISE\', true);</code><br><br> You must also copy and paste the 
				sunrise.php file from <code><b>' . $DMS->plugin_dir . '</b></code> to the directory <code><b>' . WP_CONTENT_DIR . '</b></code><br><br>
				For detailed configuration instructions, please see our
				<a href="https://docs.domainmappingsystem.com/features/multisite-integration" target="_blank"><b>Documentation</b></а>' );
			session_start();
			$_SESSION['dms_admin_error'][] = $notification;
		}
	}

	/**
	 * @param $file_a
	 * @param $file_b
	 *
	 * @return bool
	 */
	public static function compareFiles( $file_a, $file_b ) {
		if ( filesize( $file_a ) != filesize( $file_b ) ) {
			return false;
		}
		$chunksize = 4096;
		$fp_a      = fopen( $file_a, 'rb' );
		$fp_b      = fopen( $file_b, 'rb' );
		while ( ! feof( $fp_a ) && ! feof( $fp_b ) ) {
			$d_a = fread( $fp_a, $chunksize );
			$d_b = fread( $fp_b, $chunksize );
			if ( $d_a === false || $d_b === false || $d_a !== $d_b ) {
				fclose( $fp_a );
				fclose( $fp_b );

				return false;
			}
		}
		fclose( $fp_a );
		fclose( $fp_b );

		return true;
	}

	/**
	 * Modifies the htaccess when activated
	 *
	 * @param $base_host
	 */
	public static function changeHtaccess( $base_host ) {
		$file = ABSPATH . '.htaccess';
		if ( file_exists( $file ) ) {
			$data       = '# BEGIN DMS plugin
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{HTTP_HOST} !^' . $base_host . '
RewriteRule . /index.php [L]
</IfModule>
# END DMS plugin
';
			$check_data = '# BEGIN DMS plugin
<IfModule mod_rewrite\.c>
RewriteEngine On
RewriteRule \.\* - \[E=HTTP_AUTHORIZATION:%\{HTTP:Authorization\}\]
RewriteBase \/
RewriteRule \^index\\\.php\$ - \[L\]
RewriteCond %\{REQUEST_FILENAME\} !-f
RewriteCond %\{REQUEST_FILENAME\} !-d
RewriteCond %\{HTTP_HOST\} !\^' . $base_host . '
RewriteRule . \/index.php \[L\]
<\/IfModule>
# END DMS plugin';
			$contents   = file_get_contents( $file );
			preg_match( '/' . $check_data . '/', $contents, $matches, PREG_OFFSET_CAPTURE );
			if ( empty( $matches ) ) {
				file_put_contents( $file, $data . $contents );
			}
		}
	}

	/**
	 * Revert changes to htaccess when deactivated
	 *
	 * @param $base_host
	 */
	public static function revertHtaccess( $base_host ) {
		$file = ABSPATH . '.htaccess';
		if ( file_exists( $file ) ) {
			$data     = '# BEGIN DMS plugin
<IfModule mod_rewrite\.c>
RewriteEngine On
RewriteRule \.\* - \[E=HTTP_AUTHORIZATION:%\{HTTP:Authorization\}\]
RewriteBase \/
RewriteRule \^index\\\.php\$ - \[L\]
RewriteCond %\{REQUEST_FILENAME\} !-f
RewriteCond %\{REQUEST_FILENAME\} !-d
RewriteCond %\{HTTP_HOST\} !\^' . $base_host . '
RewriteRule . \/index.php \[L\]
<\/IfModule>
# END DMS plugin';
			$contents = file_get_contents( $file );
			preg_match( '/' . $data . '/', $contents, $matches, PREG_OFFSET_CAPTURE );
			if ( ! empty( $matches ) ) {
				$contents = preg_replace( '/' . $data . '/', '', $contents, 1 );
				if ( ! empty( $contents ) ) {
					file_put_contents( $file, $contents );
				}
			}
		}
	}

	/**
	 * Encoded base64 format to base64url format
	 *
	 * @param $header
	 * @param $payload
	 * @param $secret
	 *
	 * @return string
	 */
	public static function base64UrlEncode( $header, $payload, $secret ) {
		$base64UrlHeader    = str_replace( [ '+', '/', '=' ], [ '-', '_', '' ], base64_encode( $header ) );
		$base64UrlPayload   = str_replace( [ '+', '/', '=' ], [ '-', '_', '' ], base64_encode( $payload ) );
		$signature          = hash_hmac( 'sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true );
		$base64UrlSignature = str_replace( [ '+', '/', '=' ], [ '-', '_', '' ], base64_encode( $signature ) );

		return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
	}

	/**
	 * This function for including all files from $directory,
	 * with '.php' file extension.
	 *
	 * @param $directory
	 *
	 * @return void
	 *
	 * @since 1.9.4
	 */
	public static function includeFiles( $directory ) {
		if ( is_dir( $directory ) ) {
			$platforms = scandir( $directory );
			$platforms = array_diff( $platforms, array( '.', '..' ) );
			/**
			 * Check if directory is contained folder.
			 * For correct working child classes should
			 * locate one level deeper than abstract/interface/parent class.
			 * Or should have abstract/interface/parent class name
			 * with some prefix in the end, for properly working.
			 */
			$has_folder = false;
			foreach ( $platforms as $item ) {
				if ( ! isset ( pathinfo( $item )['extension'] ) ) {
					$has_folder = true;
				}
			}
			if ( ! $has_folder ) {
				$platforms = array_reverse( $platforms );
			}
			foreach ( $platforms as $item ) {
				if ( isset ( pathinfo( $item )['extension'] ) && pathinfo( $item )['extension'] == 'php' ) {
					require_once $directory . pathinfo( $item )['basename'];
				} else {
					self::includeFiles( $directory . pathinfo( $item )['basename'] . '/' );
				}
			}
		}
	}

	/**
	 * Get mappings by value
	 *
	 * @param  wpdb  $wpdb
	 * @param  int|string  $value
	 * @param  string  $output
	 * @param  array  $columns
	 *
	 * @return array
	 */
	public static function getMappingsByValue( $wpdb, $value, $output = ARRAY_A, $columns = [ '`host`', '`path`' ] ) {
		return $wpdb->get_results( $wpdb->prepare( "SELECT " . implode( ', ', $columns ) . " FROM `" . $wpdb->prefix . "dms_mappings` WHERE `id`
													 IN ( SELECT `host_id` FROM `" . $wpdb->prefix . "dms_mapping_values` WHERE `value` = %s)", $value ), $output );
	}

	/**
	 * Get host plus the path as an url part
	 *
	 * @param  array  $mapping  of mapping row host, path, etc ... (only if mapping is numeric array)
	 *
	 * @return string
	 */
	public static function getHostPlusPath( $mapping ) {
		if ( is_array( $mapping ) ) {
			// Check if associative
			if ( isset( $mapping['host'] ) && isset( $mapping['path'] ) ) {
				$mapping = [ $mapping['host'], $mapping['path'] ];
			} elseif ( isset( $mapping['host'] ) ) {
				$mapping = [ $mapping['host'] ];
			}
		} elseif ( is_object( $mapping ) ) {
			$mapping = [ $mapping->host, $mapping->path ];
		} else {
			$mapping = [];
		}

		return trim( implode( '/', $mapping ), '/' );
	}

	/**
	 * Checking if WordPress file system is "bedrock" or not.
	 *
	 * @return bool
	 */
	public static function checkIfBedrock() {
		$separators = explode( '/', WP_CONTENT_DIR );
		if ( $separators[ count( $separators ) - 1 ] == 'app' && $separators[ count( $separators ) - 2 ] == 'web' ) {
			return true;
		}

		return false;
	}

	/**
	 * Check weather $string ends up by $needle
	 *
	 * @param  string  $haystack
	 * @param  string  $needle
	 *
	 * @return bool
	 */
	public static function endsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		if ( $length == 0 ) {
			return true;
		}

		return ( substr( $haystack, - $length ) === $needle );
	}

	/**
	 * Get main domain mapping
	 *
	 * @param  wpdb  $wpdb
	 *
	 * @return array|object|stdClass|null
	 * @since 1.9.4
	 */
	public static function getMainMappingDomain( $wpdb ) {
		return $wpdb->get_row( "SELECT `id`, `host`, `path`, `attachment_id` FROM `" . $wpdb->prefix . "dms_mappings` AS m ORDER BY m.main DESC, m.id ASC LIMIT 1" );
	}

}