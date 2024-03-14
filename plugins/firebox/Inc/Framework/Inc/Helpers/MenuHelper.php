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

class MenuHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Menu';

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

	/**
	 * Get the Menu ID by Post ID
	 * 
	 * @param   integer  $post_id
	 * 
	 * @return  mixed
	 */
	public static function getMenuIdByPostId($post_id)
	{
		if (!$post_id)
		{
			return false;
		}

		if (is_array($post_id) || is_object($post_id))
		{
			return false;
		}

		$post_id = (int) $post_id;
		
		global $wpdb;
		
		$sql = "SELECT
					post_id
				FROM
					{$wpdb->prefix}postmeta
				WHERE
					meta_key = '_menu_item_object_id' AND
					meta_value = '%d'";

		$results = $wpdb->get_row($wpdb->prepare($sql, $post_id));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if (!$results)
		{
			return false;
		}
		
		return $results->post_id;
	}
}