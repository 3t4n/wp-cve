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

class CategoriesProvider implements GetSelectedItems, GetSearchItems, GetItems
{
	/**
	 * JOIN clause used by WPML to fetch related items only
	 * 
	 * @var  String
	 */
	const join_clause = " AND iclt.element_type LIKE '%_category'";

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
		$where = '';

		// set the language data
		if ($wpmlData = \FPFramework\Helpers\WPHelper::getWPMLQueryData('t.term_id', self::join_clause))
		{
			$select = $wpmlData['select'];
			$join = $wpmlData['join'];
			$where = "iclt.element_type = 'tax_category' AND";
		}

		$sql = "SELECT DISTINCT
					t.term_id as id, t.name as title $select
				FROM
					{$wpdb->prefix}terms as t
					LEFT JOIN {$wpdb->prefix}term_taxonomy as tt
						ON tt.term_id = t.term_id
					$join
				WHERE
					$where
					tt.taxonomy = 'category'
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
		$where = '';

		// set the language data
		if ($wpmlData = \FPFramework\Helpers\WPHelper::getWPMLQueryData('t.term_id', self::join_clause))
		{
			$select = $wpmlData['select'];
			$join = $wpmlData['join'];
			$where = "iclt.element_type = 'tax_category' AND";
		}

		$sql = "SELECT DISTINCT
					t.term_id as id, t.name as title $select
				FROM
					{$wpdb->prefix}terms as t
					LEFT JOIN {$wpdb->prefix}term_taxonomy as tt
						ON tt.term_id = t.term_id
					$join
				WHERE
					t.term_id IN (%s) AND
					$where
					tt.taxonomy = 'category'";

		$data = $wpdb->get_results(sprintf($sql, implode(',', array_map('intval', $items))));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return $data;
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
			$where_end = ' AND t.term_id NOT IN (' . implode(',', array_map('intval', $no_ids)) . ')';
		}

		// set the language data
		if ($wpmlData = \FPFramework\Helpers\WPHelper::getWPMLQueryData('tt.term_id', self::join_clause))
		{
			$select = $wpmlData['select'];
			$join = $wpmlData['join'];
		}

		$sql = "SELECT DISTINCT
					t.term_id as id, t.name as title $select
				FROM
					{$wpdb->prefix}terms as t
					LEFT JOIN {$wpdb->prefix}term_taxonomy as tt
						ON tt.term_id = t.term_id
					$join
				WHERE
					t.name LIKE %s AND
					tt.taxonomy = 'category'" . $where_end;

		return $wpdb->get_results($wpdb->prepare($sql, $args));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    }
}