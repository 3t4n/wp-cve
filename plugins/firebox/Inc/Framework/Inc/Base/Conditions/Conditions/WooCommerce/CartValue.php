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

namespace FPFramework\Base\Conditions\Conditions\WooCommerce;

defined('ABSPATH') or die;

class CartValue extends WooCommerceBase
{
    /**
	 * Passes the condition.
	 * 
	 * @return  bool
	 */
	public function pass()
	{
		return $this->passAmountInCart();
    }

    /**
	 * Returns the cart total.
	 * 
	 * @return  float
	 */
	public function getCartTotal()
	{
		if (!$cart = $this->getCart())
		{
			return 0;
		}

		return (float) $cart->total;
	}

    /**
	 * Returns the cart subtotal.
	 * 
	 * @return  float
	 */
	public function getCartSubtotal()
	{
		if (!$cart = $this->getCart())
		{
			return 0;
		}

		return (float) $cart->subtotal;
	}
}