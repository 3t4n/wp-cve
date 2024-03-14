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

namespace FPFramework\Base\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Filter;

class QueryString extends SmartTag
{
	/**
	 * Fetch value of a specific query string
	 * 
	 * @param   string  $key
	 * 
	 * @return  string
	 */
	public function fetchValue($key)
	{
		if (!$key)
		{
			return '';
		}

		if (!is_string($key))
		{
			return '';
		}
		
        $query = $this->factory->getQueryStrings();

		if (empty($query))
		{
			return '';
		}
        
		// Sanitize all query parameters by removing HTML.
		$filter = Filter::getInstance();
		
		foreach ($query as $q_key => &$param)
		{
			if (strtolower($q_key) != strtolower($key))
			{
				continue;
			}
			
			return $filter->clean($param, 'HTML');
		}

		return '';
	}
}