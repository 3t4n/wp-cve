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

class Tags extends Condition
{
    /**
	 * Check if we are browsing certain tags
	 *
	 * @return bool
	 */
	public function pass()
	{
        if (!$tags = $this->value())
        {
            return false;
        }

        if (empty($this->getSelection()))
        {
            return false;
        }

        foreach ($tags as $key => $value)
        {
            if (in_array($value->term_id, $this->getSelection()))
            {
                return true;
            }
        }

		return false;
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return mixed Tags IDs
     */
	public function value()
	{
        if (!$page_id = \FPFramework\Helpers\WPHelper::getPageID())
        {
            return;
        }

        return wp_get_post_tags($page_id);
	}
}