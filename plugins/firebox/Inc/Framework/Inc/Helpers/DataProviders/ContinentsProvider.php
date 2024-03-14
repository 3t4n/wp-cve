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

namespace FPFramework\Helpers\DataProviders;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Interfaces\GetSelectedItems;
use FPFramework\Base\Interfaces\GetSearchItems;

class ContinentsProvider implements GetSelectedItems, GetSearchItems
{
	/**
	 * Gets items from the Selected Items IDs
	 * 
	 * @param   array   $items
	 * 
	 * @return  array
	 */
    public function getSelectedItems($items)
    {
		$continents = fpframework()->helper->continents;
		
		$parsed = [];

		foreach ($continents::getContinents() as $key => $value)
		{
			if (in_array($key, $items))
			{
				$parsed[] = [
					'id' => $key,
					'title' => $value
				];
			}
		}
		
		return $parsed;
    }

	/**
	 * Searches and returns an array of items via the name
	 * 
	 * @param   string  $name
	 * @param   array  	$no_ids  List of already added items
	 * 
	 * @return  array
	 */
    public function getSearchItems($name, $no_ids = null)
    {
		$continents = fpframework()->helper->continents;

		$parsed = [];

		foreach ($continents::getContinents() as $key => $value)
		{
			if (stripos($value, trim($name)) !== false && !in_array($key, (array) $no_ids))
			{
				$parsed[] = [
					'id' => $key,
					'title' => $value
				];
			}
		}
		
		return $parsed;
	}
}