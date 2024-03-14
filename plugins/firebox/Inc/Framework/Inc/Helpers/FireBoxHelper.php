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

use FPFramework\Libs\Registry;
use FPFramework\Helpers\SearchDropdownHelper;

class FireBoxHelper
{
	/**
	 * FireBox Provider
	 * 
	 * @var  FireBoxProvider
	 */
	private $provider;

	public function __construct($provider = null)
	{
		// add current box ID we are manipulating to the noids list
		add_filter('fpframework/fields/searchdropdown/filter_get_search_items_ids', [$this, 'addBoxOnNoIDsList'], 1, 2);

		if (!$provider)
		{
			$provider = new \FPFramework\Helpers\DataProviders\FireBoxProvider();
		}
		
		$this->provider = $provider;
	}
	
	/**
	 * Parses and returns boxes data
	 * 
	 * @param   array  $boxes
	 * 
	 * @return  array
	 */
	public static function _parseData($boxes)
	{
		$boxes = (array) $boxes;

		if (empty($boxes))
		{
			return [];
		}

		$boxes_parsed = [];

		foreach ($boxes as $key => $p)
		{
			$p = (object) $p;
			
			$boxes_parsed[] = [
				'id' => $p->ID,
				'title' => $p->post_title
			];
		}

		return $boxes_parsed;
	}

	/**
	 * Adds the current box we are manipulating to the noids list
	 * 
	 * @param   mixed  $noids
	 * @param   int    $post_id
	 * 
	 * @return  array
	 */
	public function addBoxOnNoIDsList($noids, $post_id)
	{
		// search boxes except a given ID
		if (is_null($post_id))
		{
			return $noids;
		}

		if (empty($noids))
		{
			$noids = [ $post_id ];
		}
		else
		{
			$noids[] = $post_id;
		}

		return $noids;
	}

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
		if (!is_int($offset))
		{
			return [];
		}

		if (!is_int($limit))
		{
			return [];
		}

		return $this->provider->getItems($offset, $limit);
	}

	/**
	 * Gets Boxes from the Selected Items
	 * 
	 * @param   array   $items
	 * 
	 * @return  array
	 */
	public function getSelectedItems($items)
	{
		if (is_object($items))
		{
			$items = (array) $items;
		}

		if (empty($items) || !is_array($items))
		{
			return [];
		}
		
		return $this->provider->getSelectedItems($items);
	}

	/**
	 * Searches the boxes and returns an array of items
	 * 
	 * @param   string  $name
	 * @param   array  	$no_ids  List of already added items
	 * 
	 * @return  array
	 */
	public function getSearchItems($name, $no_ids = null)
	{
        if (!is_string($name))
		{
			return [];
		}
		
		$name = trim($name);

		if (empty($name))
		{
			return [];
		}

		return $this->provider->getSearchItems($name, $no_ids);
	}
}