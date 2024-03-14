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

namespace FPFramework\Base\Conditions\Conditions\EDD;

defined('ABSPATH') or die;

class CartContainsXProducts extends EDDBase
{
    /**
	 * Passes the condition.
	 * 
	 * @return  bool
	 */
	public function pass()
	{
		// Get cart products
		if (!$cartProducts = $this->getCartProducts())
		{
			return false;
		}
		
        return $this->passByOperator(count($cartProducts), $this->selection);
    }
}