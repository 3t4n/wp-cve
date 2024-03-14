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

namespace FPFramework\Base\Conditions\Conditions\FireBox;

defined('ABSPATH') or die;

use FPFramework\Base\Conditions\Condition;

class Popup extends Condition
{
    /**
     * Checks if the user viewed any of the given boxes
     * 
     * @return  bool
     */
    public function pass()
    {
        if (!function_exists('firebox'))
        {
            return;
        }
        
        // Skip if the visitorID is not set
        if (!$visitorID = $this->factory->getVisitorID())
        {
            return true;
        }

        $box_ids  = $this->selection;
        if (!is_array($box_ids) || empty($box_ids))
        {
            return true;
        }

        $results = firebox()->tables->boxlog->getResults([
            'where' => [
                'box' => ' IN(' . implode(',', array_map('intval', $box_ids)) . ')',
                'visitorid' => " = '" . esc_sql($visitorID) . "'"
            ]
        ], false, true);

        $pass = (int) $results;

        return (bool) $pass;
	}
}