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

class PurchasedProduct extends WooCommerceBase
{
	/**
	 *  Pass check
	 *
	 *  @return  bool
	 */
	public function pass()
	{
		if (!is_user_logged_in())
		{
			return;
		}

		if (!is_array($this->selection) || empty($this->selection))
		{
			return;
		}

		if (!class_exists('woocommerce'))
		{
			return;
		}

		// Get user
		$user = get_user_by('id', get_current_user_id());

		// Check if user has purchased a product from selection
		foreach ($this->selection as $product_id)
		{
			if (!wc_customer_bought_product($user->user_email, $user->ID, $product_id))
			{
				continue;
			}

			return true;
		}

		return false;
	}
}