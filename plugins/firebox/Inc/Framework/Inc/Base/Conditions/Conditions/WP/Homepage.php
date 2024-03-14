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

namespace FPFramework\Base\Conditions\Conditions\WP;

defined('ABSPATH') or die;

use FPFramework\Base\Conditions\Condition;

class Homepage extends Condition
{
    /**
	 * Pass method.
	 *
	 * @return  bool
	 */
	public function pass()
	{
		$is_front_page = is_front_page();
		
		return $this->options->get('params.operator', 'is') === 'is' ? $is_front_page : !$is_front_page;
    }
}