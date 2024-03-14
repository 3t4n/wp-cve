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

class FireBoxProvider implements GetSelectedItems, GetSearchItems, GetItems
{
	/**
	 * The post status
	 * 
	 * @var  string
	 */
	private $post_status = 'publish';

	/**
	 * Post Status Comparator
	 * 
	 * @var  string
	 */
	private $post_status_comparator = '=';
	
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
		if (!function_exists('firebox'))
		{
			return [];
		}
		
		global $post;
		
		$wpdb = fpframework()->helper->wpdb;

		$where = [
			'post_type' => " = 'firebox'",
		];

		// set post status if not empty
		if (!empty($this->post_status))
		{
			$where['post_status'] = " {$this->post_status_comparator} '{$this->post_status}'";
		}

		// exclude ID of post we are currently manipulating
		if ($post && isset($post->ID))
		{
			$where['ID'] = ' NOT IN(' . esc_sql($post->ID) . ')';
		}
		
		$payload = [
			'where' => $where,
			'limit' => $limit,
			'offset' => $offset
		];

		$boxes = firebox()->tables->box->getResults($payload);

		return \FPFramework\Helpers\FireBoxHelper::_parseData($boxes);
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
		if (!function_exists('firebox'))
		{
			return [];
		}

		$where = [
			'post_type' => " = 'firebox'",
		];

		if (!empty($this->post_status))
		{
			$where['post_status'] = " {$this->post_status_comparator} '{$this->post_status}'";
		}

		$payload = [
			'where' => $where
		];

		if ($items && count($items))
		{
			$payload['where']['ID'] = ' IN(' . implode(',', array_map('intval', $items)) . ')';
		}
		
		$boxes = firebox()->tables->box->getResults($payload);

		return \FPFramework\Helpers\FireBoxHelper::_parseData($boxes);
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
		if (!function_exists('firebox'))
		{
			return [];
		}

		$where = [
			'post_title' => " LIKE '%{$name}%'",
			'post_type' => " = 'firebox'",
		];

		if (!empty($this->post_status))
		{
			$where['post_status'] = " {$this->post_status_comparator} '{$this->post_status}'";
		}

		$payload = [
			'where' => $where
		];

		if ($no_ids && count($no_ids))
		{
			$payload['where']['ID'] = ' NOT IN(' . implode(',', esc_sql($no_ids)) . ')';
		}

		$boxes = firebox()->tables->box->getResults($payload);

		return \FPFramework\Helpers\FireBoxHelper::_parseData($boxes);
	}

	/**
	 * Sets the post status
	 * 
	 * @param   string  $status
	 * 
	 * @return  void
	 */
	public function setPostStatus($status)
	{
		$this->post_status = $status;
	}

	public function getPostStatus()
	{
		return $this->post_status;
	}

	/**
	 * Sets the post status comparator
	 * 
	 * @param   string  $comparator
	 * 
	 * @return  void
	 */
	public function setPostStatusComparator($comparator)
	{
		$this->post_status_comparator = $comparator;
	}

	public function getPostStatusComparator()
	{
		return $this->post_status_comparator;
	}
}