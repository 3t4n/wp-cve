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

use FPFramework\Base\Conditions\Condition;
use FPFramework\Base\Functions;

class TimeOnSite extends Condition
{
	/**
	 *  Returns the condition's value
	 * 
	 *  @return int Time on site in seconds
	 */
	public function value()
	{
		return $this->getTimeOnSite();
	}

	/**
	 *  Returns the user's time on site in seconds
	 * 
	 *  @return int
	 */
	public function getTimeOnSite()
	{
		if (!$sessionStartTime = strtotime($this->getSessionStartTime()))
		{
			return;
		}

		$dateTimeNow = strtotime(Functions::dateTimeNow());
		return $dateTimeNow - $sessionStartTime;
	}

	/**
	 *  Returns the sessions start time
	 * 
	 *  @return string
	 */
	private function getSessionStartTime()
	{
		$session = $this->factory->getSession();

		$var = 'fpf.session.starttime';
		$sessionStartTime = $session->get($var, '');

		if (!$sessionStartTime)
		{
			$date = Functions::dateTimeNow();
			$session->set($var, $date);
		}

		return $sessionStartTime;
	}
}