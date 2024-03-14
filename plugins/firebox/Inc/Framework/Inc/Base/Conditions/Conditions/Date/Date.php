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

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Date extends DateBase
{
    /**
	 *  Checks if current date passes the given date range. 
	 *  Dates must be always passed in format: Y-m-d H:i:s
	 *
	 *  @return  bool
	 */
	public function pass()
	{
		$publish_up   = $this->params->get('publish_up');
		$publish_down = $this->params->get('publish_down');

        // No valid dates
		if (!$publish_up && !$publish_up)
		{
			return false;
		}
		
		$up   = $publish_up   ? $this->getDate($publish_up)   : null;
		$down = $publish_down ? $this->getDate($publish_down) : null;

        return $this->checkRange($up, $down);
    }
    
    /**
     *  Returns the assignment's value
     * 
     *  @return \Date Current date
     */
	public function value()
	{
		return $this->date->format('Y-m-d H:i:s');
	}

	/**
	 * This method is called before the value of the condition is stored into the database.
	 * 
	 * Dates should be always stored in the database in GMT. Thus, we remove the timezone offset from the date.
	 *
	 * @param  array $rule	The condition object.
	 * 
	 * @return void
	 */
	public function onBeforeSave(&$rule)
	{
		\FPFramework\Base\Functions::fixDateOffset($rule['params']['publish_up']);
		\FPFramework\Base\Functions::fixDateOffset($rule['params']['publish_down']);
	}
}