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

class CategoriesHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Categories';

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

		foreach ($items as $key => $item)
		{
			if (!isset($item->id))
			{
				continue;
			}
			
			$data[] = [
				'id' => $item->id,
				'title' => $item->title,
				'lang' => isset($item->lang) ? $item->lang : ''
			];
		}
		
		return $data;
	}
}