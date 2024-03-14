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

class Form extends Condition
{
    /**
     * Checks if the user has submitted a FireBox Form.
     * 
     * @return  bool
     */
    public function pass()
    {
        if (!function_exists('firebox'))
        {
            return false;
        }
        
        // Skip if the visitorID is not set
        if (!$visitorID = $this->factory->getVisitorID())
        {
            return true;
        }

        $form_ids  = $this->selection;
        if (!is_array($form_ids) || empty($form_ids))
        {
            return true;
        }

        $results = firebox()->tables->submission->getResults([
            'where' => [
                'form_id' => ' IN(' . sprintf('"%s"', implode('","', array_map('strval', $form_ids))) . ')',
                'visitor_id' => " = '" . esc_sql($visitorID) . "'"
            ]
        ], false, true);

        $pass = (int) $results;

        return (bool) $pass;
	}
}