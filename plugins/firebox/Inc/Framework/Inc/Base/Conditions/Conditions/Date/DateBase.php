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

use FPFramework\Base\Conditions\Condition;

class DateBase extends Condition
{
	/**
	 * Server's Timezone
	 *
	 * @var DateTimeZone
	 */
	protected $tz;

	/**
	 * If set to True, dates will be constructed with modified offset based on the passed timezone
	 *
	 * @var Boolean
	 */
	protected $modify_offset = true;

	/**
	 *  Class constructor
	 *
	 *  @param  object  $assignment
	 */
	public function __construct($assignment = null, $factory = null)
	{
		parent::__construct($assignment, $factory);

		// Set timezone
		if ($timezone = $this->params->get('timezone'))
		{
			$this->tz = new \DateTimeZone($timezone);
		}
		else
		{
			$this->tz = wp_timezone();
		}

		// Set modify offset switch
		$this->modify_offset = $this->params->get('modify_offset', true);

		// Set now date
		$now = $this->params->get('now', 'now');
		$this->date = $this->getDate($now);
	}

	/**
	 * Checks if the current datetime is between the specified range
	 *
	 * @param JDate &$up_date
	 * @param JDate &$down_date
	 * 
	 * @return bool
	 */
	protected function checkRange(&$up_date, &$down_date)
	{
        if (!$up_date && !$down_date)
        {
            return false;
		}
 
		// Set down date's hours to 23:59:59
		if ($down_date && $this->params->get('publish_down_end_date'))
		{
			$down_date->setTime(23, 59, 59);
		}

		$now = $this->date->getTimestamp();

		if (((bool)$up_date   && $up_date->getTimestamp() > $now) ||
			((bool)$down_date && $down_date->getTimestamp() < $now))
		{
			return false;
		}

		return true;
	}

	/**
	 * Create a date object based on the given string and apply timezone.
	 *
	 * @param  String $date
	 *
	 * @return void
	 */
	protected function getDate($date = 'now')
	{
		// Fix the date string
		\FPFramework\Base\Functions::fixDate($date);

		if ($this->modify_offset)
		{
			// Create date, set timezone and modify offset
			$date = $this->factory->getDate($date)->setTimeZone($this->tz);
		} else 
		{
			// Create date and set timezone without modifyig offset
			$date = $this->factory->getDate($date, $this->tz);
		}

		return $date;
	}
}