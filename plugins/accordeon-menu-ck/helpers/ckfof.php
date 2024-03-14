<?php
/**
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */

Namespace Accordeonmenuck;

defined('CK_LOADED') or die;

/**
 * CK Development Framework layer
 */
class CKFof {

	static $keepMessages = false;

	public static function loadHelper($name) {
		require_once(ACCORDEONMENUCK_PATH . '/helpers/ck' . $name . '.php');
	}

	public static function userCan($task) {
		switch ($task) {
			case 'edit' :
			default :
				return current_user_can('edit_plugins');
			break;
			case 'manage' :
				return current_user_can('manage_options');
			break;
		}
	}

	public static function _die() {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	public static function redirect($url, $msg = '', $type = '') {
		if ($msg) {
			self::enqueueMessage($msg, $type);
		}
		// If the headers have been sent, then we cannot send an additional location header
		// so we will output a javascript redirect statement.
		if (headers_sent())
		{
			self::$keepMessages = true;
			echo "<script>document.location.href='" . str_replace("'", '&apos;', $url) . "';</script>\n";
		}
		else
		{
			self::$keepMessages = true;
			// All other browsers, use the more efficient HTTP header method
			header('HTTP/1.1 303 See other');
			header('Location: ' . $url);
			header('Content-Type: text/html; charset=UTF-8');
		}
	}

	public static function enqueueMessage($msg, $type = 'message') {
		// add the information message
		$transient[] = Array("text" => CKText::_($msg), "type" => $type);
		set_transient( 'accordeonmenuck_message', $transient, 60 );
	}

	public static function displayMessages() {
		// manage the information messages
		if ($messages = get_transient( 'accordeonmenuck_message' )) {
			if (! empty($messages)) {
				foreach ($messages as $message) {
					if (is_array($message)) {
						$type = $message["type"] == 'error' ? 'danger': ($message["type"] == 'success' ? 'success' : 'info');
						echo '<div class="ckalert ckalert-' . $type . '">' . $message["text"] . '<div class="ckclose" onclick="jQuery(this).parent().remove()">×</div></div>';
					} else {
						echo '<div class="ckalert ckalert-warning">' . $message . '<div class="ckclose" onclick="jQuery(this).parent().remove()">×</div></div>';
					}
				}
			}
			if (self::$keepMessages == false) delete_transient( 'accordeonmenuck_message' );
		}
	}

	public static function getToken($name = 'accordeonmenuck') {
		return wp_create_nonce($name);
	}

	public static function renderToken($name = 'accordeonmenuck') {
		?>
		<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce($name); ?>" />
		<?php
	}

	public static function checkToken($token = 'accordeonmenuck_save') {
		if (! wp_verify_nonce($_REQUEST['_wpnonce'], $token)) {
			$msg = CKText::_('Invalid token');
			exit($msg);
		}
	}

	public static function checkAjaxToken() {
		if (! isset($_REQUEST['CKTOKEN']) || ! wp_verify_nonce($_REQUEST['CKTOKEN'], 'accordeonmenuck')) {
			$msg = CKText::_('Invalid Token');
			echo '{"status": "0", "message": "' . $msg . '"}';
			exit();
		}
		return true;
	}

	public static function dbLoadObjectList($query) {
		global $wpdb;
		$query = str_replace('#__', $wpdb->prefix, $query);
		$results = $wpdb->get_results($query, OBJECT);

		return $results;
	}

	public static function dbLoadTable($tableName) {
		global $wpdb;
		$tableName = self::getTableName($tableName);
		$query = "DESCRIBE  " . $tableName;
		$columns = $wpdb->get_results($query);

		$table = new \stdClass();
		foreach ($columns as $col) {
			$table->{$col->Field} = '';
		}

		return $table;
	}

	public static function dbLoad($tableName, $id) {
		// if no existing row, then load empty table
		if ($id == 0) return self::dbLoadTable($tableName);

		global $wpdb;
		$tableName = self::getTableName($tableName);
		$query = "SELECT * FROM " . $tableName . " WHERE id = " . (int)$id;
		$result = $wpdb->get_row($query, OBJECT);

		if (! $result) return self::dbLoadTable($tableName);

		return $result;
	}

	public static function getTableName($tableName) {
		global $wpdb;
		return $wpdb->prefix . str_replace('#__', '', $tableName);
	}

	public static function dbStore($tableName, $data, $format) {
		global $wpdb;
		if (is_object($data)) $data = self::convertObjectToArray($data);

		// $wpdb->show_errors();
		if ($data['id'] === 0) {
			$result = $wpdb->insert( self::getTableName($tableName), $data, $format );
			$id = $wpdb->insert_id;
		} else {
			$where = array( 'id' => $data['id']);
			$result = $wpdb->update( self::getTableName($tableName), $data, $where, $format );
			$id = $data['id'];
		}
		// $wpdb->print_error();

		return $id;
	}

	public static function dbDelete($tableName, $id) {
		global $wpdb;

		$where = array( 'id' => (int)$id );
		// $wpdb->show_errors();
		$result = $wpdb->delete( self::getTableName($tableName), $where, $where_format = null );
		// $wpdb->print_error();

		return $result;
	}

	public static function convertObjectToArray($data) {
		return (array) $data;
	}

	/**
	 * Utility function to map an array to a stdClass object.
	 *
	 * @param   array    $array      The array to map.
	 * @param   string   $class      Name of the class to create
	 * @param   boolean  $recursive  Convert also any array inside the main array
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public static function toObject(array $array, $class = 'stdClass', $recursive = true)
	{
		$obj = new $class;

		foreach ($array as $k => $v)
		{
			if ($recursive && is_array($v))
			{
				$obj->$k = static::toObject($v, $class);
			}
			else
			{
				$obj->$k = $v;
			}
		}

		return $obj;
	}

	public static function dump($anything){
		add_action('shutdown', function () use ($anything) {
			echo "<div style='position: absolute; z-index: 100; left: 30px; top: 30px; right: 30px; background-color: white;'>";
				var_dump($anything);
			echo "</div>";
		});
	}

	public static function print_r($anything){
		add_action('shutdown', function () use ($anything) {
			echo "<div style='position: absolute; z-index: 100; left: 30px; top: 30px; right: 30px; background-color: white;'>";
				print_r($anything);
			echo "</div>";
		});
	}

	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	public static function hex2RGB($hexStr, $opacity) {
		if ($opacity > 1) $opacity = $opacity/100;
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . $opacity . ")";

		return $rgbacolor;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	public static function testUnit($value, $defaultunit = "px") {

		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%')) OR $value == 'auto')
			return $value;

		return $value . $defaultunit;
	}
}
