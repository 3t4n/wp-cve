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

namespace FPFramework\Base\Conditions\Conditions\Date;

defined('ABSPATH') or die;

class Day extends DateBase
{
    /**
     * Cover special cases where the user checks whether the current day is a Weekday or Weekend.
     *
     * @param  mixed $selection     The current selection
     * 
     * @return array
     */
    public function prepareSelection()
    {
        $selection = (array) $this->getSelection();

        foreach ($selection as $str)
        {
            $str = \strtolower($str);

            if (strpos($str, 'weekday') !== false)
            {
                $selection = array_merge($selection, range(1, 5));
                continue;
            }

            if (strpos($str, 'weekend') !== false)
            {
                $selection = array_merge($selection, [6, 7]);
            }
        }

        return $selection;
    }
    
    /**
     *  Return a list with all different formats of the current day.
     * 
     *  @return array
     */
	public function value()
	{
		return [
            $this->date->format('l'), // 'Friday'
            $this->date->format('D'), // 'Fri'
            $this->date->format('N'), // '1' (Monday) to '7' (Sunday)
        ];
	}
}