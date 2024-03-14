<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * The closing periods were stored within the database with this format:
 * [TIMESTAMP_START]-[TIMESTAMP_END]-[SERVICES_LIST]
 *
 * After the update, the timestamps will be replaced by UTC dates and, since the date uses dashes as separator,
 * we are forced to change the character that separates the chunks of a closing period, in example by using a colon:
 * [DATE_START]:[DATE_END]:[SERVICES_LIST]
 *
 * @since 1.7
 */
class VAPUpdateRuleClosingPeriodsFixer1_7 extends VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		$this->fix();

		return true;
	}

	/**
	 * Fixes the closing periods.
	 *
	 * @return 	void
	 */
	private function fix()
	{
		$config = VAPFactory::getConfig();

		// load registered closing periods
		$str = $config->get('closingperiods', '');

		if (!$str)
		{
			// no closing periods
			return;
		}

		// get all closing periods
		$list = explode(';;', $str);

		// iterate all closing periods
		foreach ($list as &$cp)
		{
			// get closing period data
			$tmp = explode('-', $cp);

			// convert start timestamp to date
			$tmp[0] = date('Y-m-d', $tmp[0]);
			// convert end timestamp to date
			$tmp[1] = date('Y-m-d', $tmp[1]);

			// merge chunks together by using a new separator
			$cp = implode(':', $tmp);
		}

		// update new structure
		$config->set('closingperiods', implode(';;', $list));

		return true;
	}
}
