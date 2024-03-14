<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  update
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Class used to handle the software license.
 *
 * @since 1.0
 */
class VikAppointmentsLicense
{
	/**
	 * Gets the current License Key.
	 *
	 * @return 	string
	 */
	public static function getKey()
	{
		return trim(get_option('vikappointments_license_key', ''));
	}

	/**
	 * Updates the current License Key.
	 *
	 * @param 	string 	$key
	 *
	 * @return 	void
	 */
	public static function setKey($key)
	{
		/**
		 * In case of multi-site, update the option on all the network sites.
		 *
		 * @since 1.1.9
		 */
		JFactory::getApplication()->set('vikappointments_license_key', (string) $key, $network = true);
	}

	/**
	 * Gets the current License Expiration date (Y-m-d H:i:s).
	 *
	 * @return 	string
	 */
	public static function getExpirationDate()
	{
		return trim(get_option('vikappointments_license_expdate', ''));
	}

	/**
	 * Updates the current License Expiration date.
	 *
	 * @param 	string  $time
	 *
	 * @return 	void
	 */
	public static function setExpirationDate($time)
	{
		// format time
		$time = JDate::getInstance($time)->toSql();

		/**
		 * In case of multi-site, update the option on all the network sites.
		 *
		 * @since 1.1.9
		 */
		JFactory::getApplication()->set('vikappointments_license_expdate', $time, $network = true);
	}

	/**
	 * Checks whether the software version is Pro.
	 *
	 * @return 	boolean
	 */
	public static function isPro()
	{
		$key  = self::getKey();
		$date = self::getExpirationDate();
		
		return $key && $date && $date > JDate::getInstance()->toSql();
	}

	/**
	 * Checks whether the License Key is expired.
	 *
	 * @return 	boolean
	 */
	public static function isExpired()
	{
		return !self::isPro();
	}

	/**
	 * Gets the current License Hash.
	 *
	 * @return 	string
	 */
	public static function getHash()
	{
		$hash = trim(get_option('vikappointments_license_hash', ''));
		
		if (empty($hash))
		{
			$hash = self::setHash();
		}

		return $hash;
	}

	/**
	 * Sets and returns the License Hash.
	 *
	 * @return 	string
	 */
	public static function setHash()
	{
		$hash = md5(JUri::root() . uniqid());
		update_option('vikappointments_license_hash', $hash);

		return $hash;
	}

	/**
	 * Registers some options upon installation of the plugin.
	 *
	 * @return 	void
	 */
	public static function install()
	{
		update_option('vikappointments_license_key', '');
		update_option('vikappointments_license_expdate', null);
		update_option('vikappointments_license_hash', '');
	}

	/**
	 * Deletes all the options upon uninstallation of the plugin.
	 *
	 * @return 	void
	 */
	public static function uninstall()
	{
		delete_option('vikappointments_license_key');
		delete_option('vikappointments_license_expdate');
		delete_option('vikappointments_license_hash');
	}
}
