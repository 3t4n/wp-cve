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

namespace FPFramework\Base\Conditions\Conditions;

defined('ABSPATH') or die;

use FPFramework\Base\Conditions\Condition;

class Browser extends Condition
{
    /**
	 *  Returns the assignment's value
	 * 
	 *  @return string Browser name
	 */
	public function value()
	{
		$browser = $this->factory->getBrowser();

		if (!is_array($browser) || !array_key_exists('name', $browser))
		{
			return;
		}

		return $browser['name'];
    }
}