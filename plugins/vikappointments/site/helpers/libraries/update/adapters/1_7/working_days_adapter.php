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
 * The working times now refer to the server timezone, while they were in UTC.
 * In order to avoid timezone problems, we introduced a new column called tsdate, which represents
 * the Y-m-d format of the date timestamp in UTC.
 *
 * @since 1.7
 */
class VAPUpdateRuleWorkingDaysAdapter1_7 extends VAPUpdateRule
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
		$this->adapt();

		return true;
	}

	/**
	 * Adapts the dates of the working days.
	 *
	 * @return 	void
	 */
	private function adapt()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'ts')))
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where($dbo->qn('ts') . ' <> -1');

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $wd)
			{
				// convert UTC timestamp into a military date string
				$wd->tsdate = JDate::getInstance($wd->ts)->format('Y-m-d');

				// commit changes
				$dbo->updateObject('#__vikappointments_emp_worktime', $wd, 'id');
			}
		}
	}
}
