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

class UserProvider implements GetSelectedItems, GetSearchItems, GetItems
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
		$data = [];

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
		$data = [];
		
		return $data;
	}
}