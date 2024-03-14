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

class PostsPagesProvider implements GetSelectedItems, GetSearchItems, GetItems
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
					p.ID as id, p.post_title as title $select
				FROM
					{$wpdb->prefix}posts as p
					$join
				WHERE
					p.post_type = '$post_type'
					AND p.post_status = 'publish'
				LIMIT %d
				OFFSET %d";

		$args = [$limit, $offset];
		return $wpdb->get_results($wpdb->prepare($sql, $args));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Gets items from the Selected Items IDs
	 * 
	 * @param   array   $items
	 * @param   string  $post_type
	 * 
	 * @return  array
	 */
    public function getSelectedItems($items, $post_type = null)
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
					p.ID as id, p.post_title as title $select
				FROM
					{$wpdb->prefix}posts as p
					$join
				WHERE
					p.post_status = 'publish' AND
					p.post_type = '$post_type' AND
                    p.ID IN (%s)";
        
		return $wpdb->get_results(sprintf($sql, implode(',', array_map('intval', $items))));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    }

	/**
	 * Searches and returns an array of items via the name
	 * 
	 * @param   string  $name
	 * @param   array  	$no_ids  List of already added items
	 * @param   string  $post_type
	 * 
	 * @return  array
	 */
    public function getSearchItems($name, $no_ids = null, $post_type = null)
    {
		$wpdb = fpframework()->helper->wpdb;
		
		$select = '';
		$join = '';
		$where = '';

		$args = ['%' . $wpdb->esc_like($name) . '%'];

		// set the language data
		if ($wpmlData = \FPFramework\Helpers\WPHelper::getWPMLQueryData())
		{
			$select = $wpmlData['select'];
			$join = $wpmlData['join'];
		}

		if ($no_ids)
		{
			$where = 'AND p.ID NOT IN(' . implode(',', array_map('intval', $no_ids)) . ')';
		}

		$sql = "SELECT DISTINCT
					p.ID as id, p.post_title as title $select
				FROM
					{$wpdb->prefix}posts as p
					$join
				WHERE
					p.post_status = 'publish' AND
					p.post_type = '{$post_type}' AND
					p.post_title LIKE %s
					$where";

		return $wpdb->get_results($wpdb->prepare($sql, $args));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}
}