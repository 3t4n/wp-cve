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

class Time extends DateBase
{
	/**
	 * If set to True, dates will be constructed with modified offset based on the passed timezone
	 *
	 * @var Boolean
	 */
	protected $modify_offset = false;

    /**
	 * Checks if current time passes the given time range
	 *
	 * @return bool
	 */
	public function pass()
	{
        $up   = $this->date->format('Y-m-d') . ' ' . $this->params->get('publish_up');
        $down = $this->date->format('Y-m-d') . ' ' . $this->params->get('publish_down');

        $up   = $this->factory->getDate((string) $up, $this->tz);
        $down = $this->factory->getDate((string) $down, $this->tz);

        return $this->checkRange($up, $down);
    }
    
    /**
     *  Returns the assignment's value
     * 
     *  @return \Date Current date
     */
	public function value()
	{
		return $this->date;
	}

	/**
	 * A one-line text that describes the current value detected by the rule. Eg: The current time is %s.
	 *
	 * @return string
	 */
	public function getValueHint()
	{
		return sprintf(fpframework()->_('FPF_DISPLAY_CONDITIONS_HINT_' . strtoupper($this->getName())), $this->date->format('H:i'));
	}
}