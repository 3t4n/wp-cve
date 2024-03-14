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

namespace FPFramework\Base\Conditions\Conditions;

defined('ABSPATH') or die;

use FPFramework\Base\Functions;
use FPFramework\Base\Conditions\Condition;

class OS extends Condition
{
    /**
     *  Check the client's operating system
     *
     *  @return bool
     */
    public function prepareSelection()
    {
        $selection = Functions::makeArray($this->getSelection());

        // backwards compatibility check
        // replace 'iphone' and 'ipad' selection values with 'ios'
        return array_map(function($os_selection)
        {
            if ($os_selection === 'iphone' || $os_selection === 'ipad')
            {
                return 'ios';
            }
            return $os_selection;
        }, $selection);
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return string OS name
     */
	public function value()
	{
		return $this->factory->getOS();
	}
}