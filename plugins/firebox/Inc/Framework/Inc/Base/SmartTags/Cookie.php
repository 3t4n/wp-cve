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

class Cookie extends SmartTag
{
	/**
	 * Returns the value of a cookie as stored in the visitor’s browser. 
	 * 
	 * @param   string  $key
	 * 
	 * @return  string
	 */
	public function fetchValue($key)
	{
		return $this->factory->getCookie($key);
	}
}