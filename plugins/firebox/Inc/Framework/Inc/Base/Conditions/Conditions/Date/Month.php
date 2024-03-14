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

class Month extends DateBase
{
    /**
     *  Returns the assignment's value
     * 
     *  @return string Name of the current month
     */
	public function value()
	{
		return [
            $this->date->format('F'),
            $this->date->format('M'),
            $this->date->format('n'),
            $this->date->format('m'),
        ];
	}
}