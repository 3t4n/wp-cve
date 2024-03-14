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

namespace FPFramework\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class AllowedCSSTags
{
	public function __construct()
	{
		add_filter('safe_style_css', [$this, 'allowed_css'], 20);
	}

	/**
	 * Extra allowed CSS styles.
	 * 
	 * @param   array  $atts
	 * 
	 * @return  array
	 */
	public function allowed_css($atts)
	{
		$new_atts = [
			'display'
		];

		foreach ($new_atts as $key => $value)
		{
			if (in_array($value, $atts))
			{
				continue;
			}

			$atts[] = $value;
		}

		return $atts;
	}
}