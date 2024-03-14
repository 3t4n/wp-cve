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
use FPFramework\Base\Interfaces\GetItems;

class CptsProvider implements GetSelectedItems, GetSearchItems, GetItems
{
	/**
	 * Returns items based on offset and limit
	 * 
	 * @param   integer  $offset
	 * @param   integer  $limit
	 * @param   string   $post_type
	 * 
	 * @return  array
	 */	
	public function getItems($offset, $limit, $post_type = null)
	{
		$cpts = \FPFramework\Helpers\CptsHelper::getCpts();
		$cpts = array_slice($cpts, $offset, $limit);

		$parsed = [];

		foreach ($cpts as $cpt => $data)
		{
			$parsed[] = [
				'id' => $cpt,
				'title' => $data->label
			];
		}

		return $parsed;
	}

	/**
	 * Gets items from the Selected Items IDs
	 * 
	 * @param   array   $items
	 * 
	 * @return  array
	 */
    public function getSelectedItems($items)
    {
		$cpts = \FPFramework\Helpers\CptsHelper::getCpts();

		$parsed = [];

		foreach ($cpts as $cpt => $data)
		{
			if (in_array($cpt, $items)) {
				$parsed[] = [
					'id' => $cpt,
					'title' => $data->label
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
		$cpts = \FPFramework\Helpers\CptsHelper::getCpts();

		$parsed = [];

		foreach ($cpts as $cpt => $data)
		{
			if (stripos($data->label, $name) !== false && !in_array($cpt, (array) $no_ids))
			{
				$parsed[] = [
					'id' => $cpt,
					'title' => $data->label
				];
			}
		}
		
		return $parsed;
	}
}