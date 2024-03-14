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

class UserRoleProvider implements GetSelectedItems, GetSearchItems
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
		$groups = \FPFramework\Helpers\UserHelper::getUserRoles();

		$data = [];

		foreach ($groups as $name => $group)
		{
			if (in_array($name, $items))
			{
				$data[] = [
					'id' => $name,
					'title' => $group['name']
				];
			}
		}
		
		return $data;
    }

	/**
	 * Searches and returns an array of items via the name
	 * 
	 * @param   string  $search
	 * @param   array  	$no_ids  List of already added items
	 * 
	 * @return  array
	 */
    public function getSearchItems($search, $no_ids = null)
    {
		$groups = \FPFramework\Helpers\UserHelper::getUserRoles();

		$data = [];
		
		foreach ($groups as $name => $group)
		{
			if (stripos($group['name'], $search) !== false && !in_array($name, (array) $no_ids))
			{
				$data[] = [
					'id' => $name,
					'title' => $group['name']
				];
			}
		}
		
		return $data;
	}
}