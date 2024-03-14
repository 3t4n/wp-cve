<?php
class CMEB_WhiteList {

	const MENU_OPTION				= 'cmeb_user_whitelist_option';
	const TABLE_NAME				= 'cmeb_userlist';
	const OPTION_DB_VERSION			= 'cmeb_user_list_ver';
	const CURRENT_VERSION			= '1.0';

	public static function isValid( $domain ) {
		global $wpdb;
		$sql	 = "SELECT COUNT(*) FROM " . $wpdb->prefix . self::TABLE_NAME . " WHERE whitelist=1 AND '" . esc_sql( $domain ) . "' LIKE REPLACE(domain, '*', '%')";
		$found	 = $wpdb->get_var( $sql );
		return ($found > 0);
	}

	public static function install() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$table_name1 = $wpdb->prefix . self::TABLE_NAME;
			if (get_option(self::OPTION_DB_VERSION) != self::CURRENT_VERSION)
			{
				$sql = "CREATE TABLE `" . $table_name1 . "` ( id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, domain VARCHAR(100) NOT NULL, whitelist TINYINT(1) DEFAULT 0, UNIQUE KEY id (id) )". $charset_collate . ";";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
				update_option(self::OPTION_DB_VERSION, self::CURRENT_VERSION);
			}
	}

	public static function uninstall() {
		//covered already by UserList
	}

	public static function getUserWhitelist() {
		global $wpdb;
		$sql = "SELECT * FROM " . $wpdb->prefix . self::TABLE_NAME . ' WHERE whitelist=1 ORDER BY domain ASC';
		return $wpdb->get_results( $sql );
	}

	public static function _processAdminRequest() {
		isset( $_POST[ 'cmeb_white' ] ) ? $var = sanitize_text_field($_POST[ 'cmeb_white' ]) : $var = sanitize_text_field($_GET[ 'cmeb_white' ]);
		switch ( $var ) {
			case 'add':
				self::addDomain( sanitize_text_field($_POST[ 'white_domain' ]) );
				break;
			case 'edit':
				$ids	 = sanitize_text_field($_POST[ 'white_id' ]);
				$domains = sanitize_text_field($_POST[ 'white_domain' ]);
				foreach ( $ids as $key => $id ) {
					self::editDomain( $id, $domains[ $key ] );
				}
				break;
			case 'delete':
				self::deleteDomain( sanitize_text_field($_GET[ 'white_id' ]) );
				break;
		}
		// $url = admin_url().'?page=cmeb_menu#tab-whitelist';
		// wp_redirect($url);
	}

	public static function sanitizeDomainName( $name ) {
		$regex = '/(\*{2,})/';
		return strtolower( preg_replace( $regex, '*', $name ) );
	}

	public static function isValidDomainName( $name ) {
		$regex		 = '/^(\*|[a-z0-9])([\*\.a-z0-9\-]+)(\*|[a-z])$/';
		$isAsterisk	 = (strpos( $name, '*' ) !== false);
		$isDot		 = (strpos( $name, '.' ) !== false);
		return (preg_match( $regex, $name ) && ($isAsterisk || $isDot) && strlen( $name ) <= 63);
	}

	public static function domainExists( $name, $id = null ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT COUNT(*) FROM " . $wpdb->prefix . self::TABLE_NAME . ' WHERE whitelist=1 AND domain=%s', $name );
		if ( !empty( $id ) && is_numeric( $id ) ) {
			$sql.=' AND id=' . $id;
		}
		return ($wpdb->get_var( $sql ) > 0);
	}

	public static function addDomain( $name ) {
		global $wpdb;
		$name = self::sanitizeDomainName( $name );
		if ( !self::isValidDomainName( $name ) ) {
			throw new Exception( 'Domain name (' . $name . ') is not valid' );
		} elseif ( self::domainExists( $name ) ) {
			throw new Exception( 'Domain (' . $name . ') already exists in the system' );
		} else {
			$wpdb->insert( $wpdb->prefix . self::TABLE_NAME, array( 'domain' => $name, 'whitelist' => 1 ) );
			$id = $wpdb->insert_id; //last insert
		}
	}

	public static function editDomain( $id, $name ) {
		global $wpdb;
		$name = self::sanitizeDomainName( $name );
		if ( !self::isValidDomainName( $name ) ) {
			throw new Exception( 'Domain name (' . $name . ') is not valid' );
		} elseif ( self::domainExists( $name, $id ) ) {
			throw new Exception( 'Domain (' . $name . ') already exists in the system' );
		} else {
			$wpdb->update( $wpdb->prefix . self::TABLE_NAME, array( 'domain' => $name, 'whitelist' => 1 ), array( 'id' => $id ) );
		}
	}

	public static function deleteDomain( $id ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . self::TABLE_NAME, array( 'id' => $id ) );
	}

}
?>