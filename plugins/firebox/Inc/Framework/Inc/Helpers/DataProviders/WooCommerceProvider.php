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
use FPFramework\Helpers\SearchDropdownHelper;

class WooCommerceProvider implements GetSelectedItems, GetSearchItems, GetItems
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
		if (!class_exists('woocommerce'))
		{
			return [];
		}

		$products = wc_get_products([
			'offset' => $offset,
			'limit' => $limit,
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		$data = [];
		
		foreach ($products as $product)
		{
			$data[] = [
				'id' => $product->get_id(),
				'title' => $product->get_name()
			];
		}

		return $data;
	}

	/**
	 * Gets boxes from the Selected Items
	 * 
	 * @param   array   $items
	 * 
	 * @return  array
	 */
    public function getSelectedItems($items = [])
    {
		if (!class_exists('woocommerce'))
		{
			return [];
		}

		$products = wc_get_products([
			'include' => $items,
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		$data = [];
		
		foreach ($products as $product)
		{
			$data[] = [
				'id' => $product->get_id(),
				'title' => $product->get_name()
			];
		}

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
    public function getSearchItems($name, $no_ids = [])
    {
		if (!class_exists('woocommerce'))
		{
			return [];
		}

		add_filter('woocommerce_product_data_store_cpt_get_products_query', [$this, 'add_like_name'], 10, 2);

		$products = wc_get_products([
			'firebox_woo_product_like_name' => $name,
			'exclude' => $no_ids,
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		$data = [];
		
		foreach ($products as $product)
		{
			$data[] = [
				'id' => $product->get_id(),
				'title' => $product->get_name()
			];
		}

		return $data;
	}

	/**
	 * Allow to search by woo product name.
	 * 
	 * @param   array  $query
	 * @param   array  $query_vars
	 * 
	 * @return  array
	 */
	public function add_like_name($query, $query_vars)
	{
		if (isset($query_vars['firebox_woo_product_like_name']) && ! empty($query_vars['firebox_woo_product_like_name']))
		{
			$query['s'] = esc_attr($query_vars['firebox_woo_product_like_name']);
		}

		return $query;
	}
}