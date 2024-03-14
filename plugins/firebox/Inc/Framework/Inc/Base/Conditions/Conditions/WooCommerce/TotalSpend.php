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

class TotalSpend extends WooCommerceBase
{
	/**
	 *  Returns the condtion value.
	 * 
	 *  @return  float
	 */
	public function value()
	{
		if (!is_user_logged_in())
		{
			return;
		}

		if (!class_exists('woocommerce'))
		{
			return;
		}

		return (float) wc_get_customer_total_spent(get_current_user_id());
	}
}