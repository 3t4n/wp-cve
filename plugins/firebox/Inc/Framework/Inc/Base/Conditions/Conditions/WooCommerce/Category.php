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

class Category extends WooCommerceBase
{
    /**
	 *  Pass check
	 *
	 *  @return  bool
	 */
	public function pass()
	{
        return $this->passCategories();
    }
}