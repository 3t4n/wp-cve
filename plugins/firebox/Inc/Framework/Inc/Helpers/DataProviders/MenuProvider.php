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

class MenuProvider implements GetSelectedItems, GetSearchItems, GetItems
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
		$wpdb = fpframework()->helper->wpdb;

		$select = '';
		$join = '';

		// set the language data
		if ($wpmlData = \FPFramework\Helpers\WPHelper::getWPMLQueryData())
		{
			$select = $wpmlData['select'];
			$join = $wpmlData['join'];
		}

		$sql = "SELECT DISTINCT
					pm.post_id as id, p.post_title as title $select
				FROM
					{$wpdb->prefix}posts as p
					LEFT JOIN {$wpdb->prefix}postmeta as pm
						ON pm.meta_value = p.ID
					$join
				WHERE
					pm.meta_key = '_menu_item_object_id'
				LIMIT %d
				OFFSET %d";

		$args = [$limit, $offset];
		return $wpdb->get_results($wpdb->prepare($sql, $args));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
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
		$wpdb = fpframework()->helper->wpdb;
		
		$select = '';
		$join = '';

		// set the language data
		if ($wpmlData = \FPFramework\Helpers\WPHelper::getWPMLQueryData())
		{
			$select = $wpmlData['select'];
			$join = $wpmlData['join'];
		}

		$sql = "SELECT DISTINCT
					pm.post_id as id, p.post_title as title $select
				FROM
					{$wpdb->prefix}posts as p
					LEFT JOIN {$wpdb->prefix}postmeta as pm
						ON pm.meta_value = p.ID
					$join
				WHERE
					pm.meta_key = '_menu_item_object_id' AND
					pm.post_id IN (%s)";

		return $wpdb->get_results(sprintf($sql, implode(',', array_map('intval', $items))));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
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
		$wpdb = fpframework()->helper->wpdb;

		$select = '';
		$where_end = '';
		$join = '';
		
		$args = ['%' . $wpdb->esc_like($name) . '%'];

		// filter search results by removing given IDs from results
		if ($no_ids)
		{
			$where_end = ' AND pm.post_id NOT IN (' . implode(',', array_map('intval', $no_ids)) . ')';
		}

		// set the language data
		if ($wpmlData = \FPFramework\Helpers\WPHelper::getWPMLQueryData())
		{
			$select = $wpmlData['select'];
			$join = $wpmlData['join'];
		}

		$sql = "SELECT DISTINCT
					pm.post_id as id, p.post_title as title $select
				FROM
					{$wpdb->prefix}posts as p
					LEFT JOIN {$wpdb->prefix}postmeta as pm
						ON pm.meta_value = p.ID
					$join
				WHERE
					p.post_title LIKE %s AND
					pm.meta_key = '_menu_item_object_id'" . $where_end;

		return $wpdb->get_results($wpdb->prepare($sql, $args));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    }
}