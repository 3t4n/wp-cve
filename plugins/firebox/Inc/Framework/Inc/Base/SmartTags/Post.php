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

class Post extends SmartTag
{
	/**
	 * Returns the value of a post data as found in the $_POST superglobal array. For example, if you submit a form that consists of the “email” and “name” input fields, you can use {post.email} and {post.name} Smart Tags in the submitted URL to retrieve the value of any form input.
	 * 
	 * @param   string  $key
	 * 
	 * @return  string
	 */
	public function fetchValue($key)
	{
		$default_value = $this->parsedOptions->get('default', '');
		
		return isset($_POST[$key]) ? $_POST[$key] : $default_value;
	}
}