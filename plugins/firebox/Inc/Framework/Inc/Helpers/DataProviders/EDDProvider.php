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

class EDDProvider implements GetSelectedItems, GetSearchItems, GetItems
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
		if (!\function_exists('EDD'))
		{
			return [];
		}

		$products = get_posts([
			'post_type' => 'download',
			'offset' => $offset,
			'numberposts' => $limit,
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		$data = [];
		
		foreach ($products as $product)
		{
			$data[] = [
				'id' => $product->ID,
				'title' => $product->post_title
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
		if (!\function_exists('EDD'))
		{
			return [];
		}

		$products = get_posts([
			'post_type' => 'download',
			'include' => $items,
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		$data = [];
		
		foreach ($products as $product)
		{
			$data[] = [
				'id' => $product->ID,
				'title' => $product->post_title
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
		if (!\function_exists('EDD'))
		{
			return [];
		}

		$products = get_posts([
			'post_type' => 'download',
			's' => $name,
			'exclude' => $no_ids,
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		$data = [];
		
		foreach ($products as $product)
		{
			$data[] = [
				'id' => $product->ID,
				'title' => $product->post_title
			];
		}

		return $data;
	}
}