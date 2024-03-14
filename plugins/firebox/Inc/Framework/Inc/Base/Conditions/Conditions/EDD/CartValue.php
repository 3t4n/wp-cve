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

class CartValue extends EDDBase
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
	protected function getCartTotal()
	{
		return edd_get_cart_total();
	}

	/**
	 * Returns the cart subtotal.
	 * 
	 * @return  float
	 */
	protected function getCartSubtotal()
	{
		return edd_get_cart_subtotal();
	}
	
	/**
	 * Returns the shipping total.
	 * 
	 * @return  float
	 */
	protected function getShippingTotal()
	{
		if (!$cart = $this->getCart())
		{
			return 0;
		}

		$fees = $cart->fees ? $cart->fees : [];
		$total_fees = 0;
		if (is_array($fees) && count($fees))
		{
			foreach ($fees as $fee)
			{
				$total_fees += (float) $fee['amount'];
			}
		}

		return $total_fees;
	}
}