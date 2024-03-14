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

class PurchasedProduct extends EDDBase
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
		
		if (!function_exists('EDD'))
		{
			return;
		}
		
		return edd_has_user_purchased(get_current_user_id(), $this->selection);
	}
}