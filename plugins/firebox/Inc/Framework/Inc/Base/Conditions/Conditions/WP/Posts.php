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

class Posts extends Condition
{
    /**
     *  Returns the assignment's value
     * 
     *  @return mixed Post ID
     */
	public function value()
	{
		$post_id = \FPFramework\Helpers\WPHelper::getPageID();
        
        return $post_id && is_single($post_id) ? $post_id : false;
	}
}