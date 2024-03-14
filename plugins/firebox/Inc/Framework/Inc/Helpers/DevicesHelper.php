<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class DevicesHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Devices';

		parent::__construct($provider);
	}

	/**
	 * Returns the device name
	 * 
	 * @param   string  $device
	 * 
	 * @return  boolean
	 */
	public static function getDevice($device)
	{
		if (!is_string($device))
		{
			return null;
		}
		
		$devices = self::getDevices();

		if (!isset($devices[$device]))
		{
			return null;
		}

		return $devices[$device];
	}

	/**
	 * Checks whether the device exists
	 * 
	 * @param   string  $device
	 * 
	 * @return  boolean
	 */
	public static function deviceExist($device)
	{
		if (!is_string($device))
		{
			return false;
		}
		
		$devices = self::getDevices();

		if (!isset($devices[$device]))
		{
			return false;
		}

		return true;
	}

	/**
	 * Returns all devices
	 * 
	 * @return  array
	 */
	public static function getDevices()
	{
		return [
			'desktop' => fpframework()->_('FPF_DESKTOP'),
			'mobile' => fpframework()->_('FPF_MOBILE'),
			'tablet' => fpframework()->_('FPF_TABLET')
		];
	}
}