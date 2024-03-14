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

use FPFramework\Base\Interfaces\GetItems;
use FPFramework\Base\Interfaces\GetSelectedItems;
use FPFramework\Base\Interfaces\GetSearchItems;

class LanguageProvider implements GetItems, GetSelectedItems, GetSearchItems
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
		if (!class_exists('SitePress'))
		{
			return [];
		}

		$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
		
		if (empty($languages))
		{
			return [];
		}

		$languages = array_slice($languages, $offset, $limit);

		$data = [];

		foreach($languages as $l)
		{
			$data[] = [
				'id' => $l['language_code'],
				'title' => $l['translated_name'],
				'lang' => $l['language_code']
			];
		}

		return $data;
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
		$langs = fpframework()->helper->language;
		
		$parsed = [];

		foreach ($langs::getWPMLLanguages() as $key => $value)
		{
			if (in_array($key, $items))
			{
				$parsed[] = [
					'id' => $key,
					'title' => $value,
					'lang' => $key
				];
			}
		}
		
		return $parsed;
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
		$langs = fpframework()->helper->language;

		$parsed = [];

		foreach ($langs::getWPMLLanguages() as $key => $value)
		{
			if (stripos($value, trim($name)) !== false && !in_array($key, (array) $no_ids))
			{
				$parsed[] = [
					'id' => $key,
					'title' => $value,
					'lang' => $key
				];
			}
		}
		
		return $parsed;
	}
}