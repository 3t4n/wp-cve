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

class ConditionsHelper
{
	/**
	 * Gets items from the Selected Items IDs
	 * 
	 * @param   array   $needle
	 * @param   array   $haystack
	 * 
	 * @return  array
	 */
    public function getSelectedItems($needle, $haystack)
    {
		foreach ($haystack as $key => $value)
		{
			foreach ($value['title'] as $_key => $_value)
			{
				if (!in_array($_key, [$needle, stripslashes($needle)]))
				{
					continue;
				}
			
				return [
					[
						'id' => $_key,
						'title' => $_value
					]
				];
			}
		}
		
		return;
    }
}