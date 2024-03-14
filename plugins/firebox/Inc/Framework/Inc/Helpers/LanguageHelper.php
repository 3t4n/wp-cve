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

class LanguageHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Language';

		parent::__construct($provider);
	}

	/**
	 * Retrieves all languages from WPML.
	 * Requires plugin to be installed.
	 * 
	 * @return  return
	 */
	public static function getWPMLLanguages()
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

		$data = [];

		foreach($languages as $l)
		{
			$data[$l['language_code']] = $l['translated_name'];
		}

		return $data;
	}
}