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

class UserIDHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'UserID';

		parent::__construct($provider);
	}

	/**
	 * Parses given data to a key,value array
	 * 
	 * @param   array  $items
	 * 
	 * @return  array
	 */
	public static function parseData($items)
	{
		$items = (array) $items;

		if (empty($items))
		{
			return [];
		}

		$data = [];

		foreach ($items as $key => $value)
		{
			$data[$key] = $value;
		}
		
		return $data;
	}
}