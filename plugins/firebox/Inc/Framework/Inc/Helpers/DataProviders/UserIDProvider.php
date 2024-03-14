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

class UserIDProvider implements GetSelectedItems, GetSearchItems, GetItems
{
	/**
	 * Returns items based on offset and limit
	 * 
	 * @param   integer  $offset
	 * @param   integer  $limit
	 * 
	 * @return  array
	 */
	public function getItems($offset = 0, $limit = SearchDropdownHelper::SELECTION_ITEMS)
	{
		$users = get_users([
			'offset' => $offset,
			'number' => $limit
		]);

		$data = [];

		foreach ($users as $user)
		{
			$data[] = [
				'id' => $user->ID,
				'title' => $user->user_nicename . ' (' . $user->user_email . ')'
			];
		}

		return $data;
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
		$data = [];

		$users = get_users([
			'include' => $items,
			'search_columns' => 'ID'
		]);

		foreach ($users as $user)
		{
			$data[] = [
				'id' => $user->ID,
				'title' => $user->user_nicename . ' (' . $user->user_email . ')'
			];
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
		$users = get_users([
			'search' => '*' . $search . '*',
			'exclude' => $no_ids,
		]);

		$data = [];
		
		foreach ($users as $user)
		{
			$data[] = [
				'id' => $user->ID,
				'title' => $user->user_nicename . ' (' . $user->user_email . ')'
			];
		}
		
		return $data;
	}
}