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

class FireBoxFormHelper
{
	/**
	 * FireBoxForm Provider
	 * 
	 * @var  FireBoxFormProvider
	 */
	private $provider;

	public function __construct($provider = null)
	{
		if (!$provider)
		{
			$provider = new \FPFramework\Helpers\DataProviders\FireBoxFormProvider();
		}
		
		$this->provider = $provider;
	}

	/**
	 * Parses and returns forms data
	 * 
	 * @param   array  $forms
	 * 
	 * @return  array
	 */
	public static function _parseData($forms)
	{
		if (empty($forms))
		{
			return [];
		}

		$forms_parsed = [];

		foreach ($forms as $id => $label)
		{
			$forms_parsed[] = [
				'id' => $id,
				'title' => $label
			];
		}

		return $forms_parsed;
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

		$limit = 9999999;

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