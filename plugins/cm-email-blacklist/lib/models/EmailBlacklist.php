<?php
class CMEB_EmailBlacklist
{
	const MENU_OPTION 			= 'cmeb_email_blacklist_option';
	const TABLE_NAME 			= 'cmeb_email_list';
	const OPTION_DB_VERSION 	= 'cmeb_email_list_ver';
	const CURRENT_VERSION 		= '1.0';

	public static function isValid($email)
	{
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . self::TABLE_NAME . " WHERE whitelist=0 AND '" . esc_sql($email) . "' LIKE REPLACE(email, '*', '%')";
		$found = $wpdb->get_var($sql);
		return ($found == 0);
	}

	public static function install()
	{
		if (get_option(self::OPTION_DB_VERSION) != self::CURRENT_VERSION)
		{
			global $wpdb;
			$table_name 	 = $wpdb->prefix . self::TABLE_NAME;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE ".$table_name." (
				id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				email VARCHAR(100) NOT NULL,
				whitelist TINYINT(1) DEFAULT 0,
				UNIQUE KEY id (id) )".$charset_collate.";";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	public static function uninstall()
	{
		//code...
	}

	public static function getEmailsBlacklist()
	{
		global $wpdb;
		$sql = "SELECT * FROM " . $wpdb->prefix . self::TABLE_NAME . ' WHERE whitelist=0 ORDER BY email ASC';
		return $wpdb->get_results($sql);
	}

	public static function _processAdminRequest()
	{
		isset($_POST['cmeb_email_black']) ? $var = sanitize_text_field($_POST['cmeb_email_black']) : $var = sanitize_text_field($_GET['cmeb_email_black']);

		switch ($var)
		{
			case 'add':
				self::addEmail(sanitize_text_field($_POST['black_email']));
				break;
			case 'edit':
				$ids = sanitize_text_field($_POST['black_id']);
				$emails = sanitize_text_field($_POST['black_email']);
				foreach ($ids as $key => $id) {
					self::editEmail($id, $emails[$key]);
				}
				break;
			case 'delete':
				self::deleteEmail(sanitize_text_field($_GET['black_id']));
				break;
		}
		// $url = admin_url().'?page=cmeb_menu#tab-user-blacklist';
		//wp_redirect($url);
	}

	public static function sanitizeEmailName($name) {
        // $regex = '/(\*{2,})/';
        // return strtolower(preg_replace($regex, '*', $name));
        return strtolower($name);
    }

   public static function isValidEmailName($name)
    {
        $regex = "/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/";
        $isDot = (strpos($name, '.') !== false);
        return (preg_match($regex, $name) && strlen($name) <= 63);
    }

	public static function emailExists($name, $id = null)
	{
		global $wpdb;
		$sql = $wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . self::TABLE_NAME . ' WHERE whitelist=0 AND email=%s', $name);
		if (!empty($id) && is_numeric($id)) {
			$sql.=' AND id=' . $id;
		}
		return ($wpdb->get_var($sql) > 0);
	}

	public static function addEmail($name)
	{
		global $wpdb;
		$name = self::sanitizeEmailName($name);
		if (!self::isValidEmailName($name)) {
			throw new Exception('Email name (' . $name . ') is not valid');
		} elseif (self::emailExists($name)) {
			throw new Exception('Email (' . $name . ') already exists in the system');
		} else {
			$wpdb->insert($wpdb->prefix . self::TABLE_NAME, array('email' => $name));
			$id = $wpdb->insert_id; //last insert
		}
	}

	public static function editEmail($id, $name)
	{
		global $wpdb;
		$name = self::sanitizeEmailName($name);
		if (!self::isValidEmailName($name)) {
			throw new Exception('Email address (' . $name . ') is not valid');
		} elseif (self::emailExists($name, $id)) {
			throw new Exception('Email address (' . $name . ') already exists in the system');
		} else {
			$wpdb->update($wpdb->prefix . self::TABLE_NAME, array('email' => $name), array('id' => $id));
		}
	}

	public static function deleteEmail($id)
	{
		global $wpdb;
		$wpdb->delete($wpdb->prefix . self::TABLE_NAME, array('id' => $id));
	}
}
