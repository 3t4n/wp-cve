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

class PostsBaseHelper extends SearchDropdownProviderHelper
{
	/**
	 * JOIN clause used by WPML to fetch related items only
	 * 
	 * @var  String
	 */
	const join_clause = " AND (iclt.element_type = 'post_page' || iclt.element_type = 'post_post' || iclt.element_type = 'post_nav_menu_item')";

	public function __construct($provider = null)
	{
		$this->class_name = 'PostsPages';

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