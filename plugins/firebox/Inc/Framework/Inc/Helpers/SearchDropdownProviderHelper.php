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

class SearchDropdownProviderHelper
{
	/**
	 * Data Provider
	 * 
	 * @var  The Data Provider
	 */
	private $provider;

	/**
	 * The helper class name
	 * 
	 * @var  String
	 */
	protected $class_name = null;

	protected $provider_prefix = null;

	public function __construct($provider = null)
	{
		if (!$provider)
		{
			$prefix = !$this->provider_prefix ? '\FPFramework\Helpers\DataProviders\\' : $this->provider_prefix;
            $class_name = $prefix . $this->class_name . 'Provider';
			$provider = new $class_name();
		}
		
		$this->provider = $provider;
    }

	/**
	 * Returns items based on offset and limit
	 * 
	 * @param   integer  $offset
	 * @param   integer  $limit
	 * @param   string   $post_type
	 * 
	 * @return  array
	 */
	public function getItems($offset = 0, $limit = SearchDropdownHelper::SELECTION_ITEMS, $post_type = '')
	{
		if (!is_int($offset))
		{
			return [];
		}

		if (!is_int($limit))
		{
			return [];
		}

		return $this->provider->getItems($offset, $limit, $post_type);
	}

	/**
	 * Gets items from the Selected Items
	 * 
	 * @param   array   $items
	 * @param   string  $post_type
	 * 
	 * @return  array
	 */
	public function getSelectedItems($items, $post_type = '')
	{
		if (is_object($items))
		{
			$items = (array) $items;
		}

		if (!is_array($items))
		{
			$items = [$items];
		}

		if (empty($items) || !is_array($items))
		{
			return [];
		}

		return $this->provider->getSelectedItems($items, $post_type);
	}

	/**
	 * Searches the items and returns an array of items
	 * 
	 * @param   string  $name
	 * @param   array  	$no_ids  List of already added items
	 * @param   string  $post_type
	 * 
	 * @return  array
	 */
	public function getSearchItems($name, $no_ids = null, $post_type = '')
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

		return $this->provider->getSearchItems($name, $no_ids, $post_type);
	}
}