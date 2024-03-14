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
 * Started the conversion from timestamp integers to DATE columns for higher consistency. Since integers
 * cannot be directly converted into dates, we need to drop the column and then re-add it with the same
 * name and a different type.
 *
 * In order to avoid losing all our stored data, instead of dropping the column, we should simply rename them.
 * This way, after the update, we are able to compile all the new columns with a mass SQL update.
 *
 * Here's the list of tables and columns that implemented a similar change:
 * `#__vikappointments_service`
 * `#__vikappointments_employee`
 * `#__vikappointments_reservation`
 * `#__vikappointments_subscr_order`
 * `#__vikappointments_package`
 * `#__vikappointments_package_order`
 * `#__vikappointments_package_order_item`
 * `#__vikappointments_reviews`
 * `#__vikappointments_coupon`
 * `#__vikappointments_config`
 *
 * In addition, while converting the dates of the appointments, we need to auto-populate the `tz_offset` column,
 * which strictly depends on the check-in and the employee of the appointments.
 *
 * @since 1.7
 */
class VAPUpdateRuleTimestampDatetimeConverter1_7 extends VAPUpdateRule
{
	/**
	 * The timezone used for the timestamps.
	 *
	 * @var string
	 */
	private $timezone;

	/**
	 * The string used to save NULL dates within the database.
	 *
	 * @var string
	 */
	private $nullDate;

	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		// init the currently set timezone
		$this->timezone = date_default_timezone_get();

		if ($this->timezone === 'UTC')
		{
			// get system timezone
			$sys_tz = JFactory::getApplication()->get('offset', 'UTC');

			if ($sys_tz !== 'UTC')
			{
				// auto-adjust the times to the timezone set from the CMS configuration
				$this->timezone = $sys_tz;
			}
		}

		// register a NULL date
		$this->nullDate = JFactory::getDbo()->getNullDate();

		$this->convertServices();
		$this->convertEmployees();
		$this->convertReservations();
		$this->convertSubscriptionsOrders();
		$this->convertPackages();
		$this->convertPackagesOrders();
		$this->convertPackagesOrdersItems();
		$this->convertReviews();
		$this->convertCoupons();
		$this->convertConfiguration();

		return true;
	}

	/**
	 * Helper function used to convert a timestamp into a datetime string.
	 *
	 * @param 	integer  $timestamp  The UNIX timestamp to convert.
	 * @param 	mixed    $tz         The timezone of the timestamp.
	 *
	 * @return 	string
	 */
	private function ts2date($timestamp, $tz = null)
	{
		if (VAPDateHelper::isNull($timestamp))
		{
			// return a NULL date
			return $this->nullDate;
		}

		// convert the timestamp by using the native date function so
		// that it will be able to properly use the current timezone
		$date = date('Y-m-d H:i:s', $timestamp);

		// return the SQL date time string
		return JDate::getInstance($date, $tz)->toSql();
	}

	/**
	 * Converts the services timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `start_publishing`
	 * - `end_publishing`
	 *
	 * @return 	void
	 */
	private function convertServices()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', '__start_publishing', '__end_publishing')))
			->from($dbo->qn('#__vikappointments_service'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $s)
		{
			// do conversion
			$s->start_publishing = $this->ts2date($s->__start_publishing, $this->timezone);
			$s->end_publishing   = $this->ts2date($s->__end_publishing  , $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_service', $s, 'id');
		}
	}

	/**
	 * Converts the employees timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `active_to_date`
	 * - `active_since`
	 *
	 * @return 	void
	 */
	private function convertEmployees()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'active_to', '__active_since')))
			->from($dbo->qn('#__vikappointments_employee'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $e)
		{
			// do conversion
			$e->active_to_date = $this->ts2date($e->active_to     , $this->timezone);
			$e->active_since   = $this->ts2date($e->__active_since, $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_employee', $e, 'id');
		}
	}

	/**
	 * Converts the reservations timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `checkin_ts`
	 * - `createdon`
	 *
	 * @return 	void
	 */
	private function convertReservations()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_reservation'));

		$dbo->setQuery($q);
		$total = (int) $dbo->loadResult();

		// take at most 1000 records per time in order to avoid exceeding the memory limit
		for ($offset = 0, $limit = 1000; $offset < $total; $offset += $limit)
		{
			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'id_employee', '__createdon', '__checkin_ts')))
				->from($dbo->qn('#__vikappointments_reservation'));

			$dbo->setQuery($q, $offset, $limit);
			foreach ($dbo->loadObjectList() as $r)
			{
				$tz = null;

				// make sure we don't have a parent order
				if ($r->id_employee > 0)
				{
					// fetch employee timezone
					$tz = JModelVAP::getInstance('employee')->getTimezone($r->id_employee);
				}

				// do conversion
				$r->createdon  = $this->ts2date($r->__createdon , $this->timezone);

				// the check-in was always referring to the employee timezone
				$r->checkin_ts = $this->ts2date($r->__checkin_ts, $tz);

				if ($r->id_employee > 0)
				{
					// register timezone offset
					$r->tz_offset = JDate::getInstance($r->checkin_ts, $tz)->format('P', true);
				}

				// commit changes
				$dbo->updateObject('#__vikappointments_reservation', $r, 'id');
			}
		}
	}

	/**
	 * Converts the subscriptions orders timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `createdon`
	 *
	 * @return 	void
	 */
	private function convertSubscriptionsOrders()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', '__createdon')))
			->from($dbo->qn('#__vikappointments_subscr_order'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $o)
		{
			// do conversion
			$o->createdon = $this->ts2date($o->__createdon, $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_subscr_order', $o, 'id');
		}
	}

	/**
	 * Converts the packages timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `start_ts`
	 * - `end_ts`
	 *
	 * @return 	void
	 */
	private function convertPackages()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', '__start_ts', '__end_ts')))
			->from($dbo->qn('#__vikappointments_package'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $p)
		{
			// do conversion
			$p->start_ts = $this->ts2date($p->__start_ts, $this->timezone);
			$p->end_ts   = $this->ts2date($p->__end_ts  , $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_package', $p, 'id');
		}
	}

	/**
	 * Converts the packages orders timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `createdon`
	 *
	 * @return 	void
	 */
	private function convertPackagesOrders()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', '__createdon')))
			->from($dbo->qn('#__vikappointments_package_order'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $o)
		{
			// do conversion
			$o->createdon = $this->ts2date($o->__createdon, $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_package_order', $o, 'id');
		}
	}

	/**
	 * Converts the packages order items timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `modifiedon`
	 *
	 * @return 	void
	 */
	private function convertPackagesOrdersItems()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', '__modifiedon')))
			->from($dbo->qn('#__vikappointments_package_order_item'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $i)
		{
			// do conversion
			$i->modifiedon = $this->ts2date($i->__modifiedon, $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_package_order_item', $i, 'id');
		}
	}

	/**
	 * Converts the reviews timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `timestamp`
	 *
	 * @return 	void
	 */
	private function convertReviews()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', '__timestamp')))
			->from($dbo->qn('#__vikappointments_reviews'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $r)
		{
			// do conversion
			$r->timestamp = $this->ts2date($r->__timestamp, $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_reviews', $r, 'id');
		}
	}

	/**
	 * Converts the coupons timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `dstart`
	 * - `dend`
	 *
	 * @return 	void
	 */
	private function convertCoupons()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', '__dstart', '__dend')))
			->from($dbo->qn('#__vikappointments_coupon'));

		$dbo->setQuery($q);
		foreach ($dbo->loadObjectList() as $c)
		{
			// do conversion
			$c->dstart = $this->ts2date($c->__dstart, $this->timezone);
			$c->dend   = $this->ts2date($c->__dend  , $this->timezone);

			// commit changes
			$dbo->updateObject('#__vikappointments_coupon', $c, 'id');
		}
	}

	/**
	 * Converts the configuration timestamps into UTC datetimes.
	 * Here's a list of columns that need to be adjusted:
	 * - `closingdays`
	 *
	 * @return 	void
	 */
	private function convertConfiguration()
	{
		$config = VAPFactory::getConfig();

		$str = $config->get('closingdays', '');

		if (!$str)
		{
			// no closing days
			return;
		}

		// get all closing days
		$list = explode(';;', $str);

		// iterate all closing days
		foreach ($list as &$cd)
		{
			// get closing day data
			$tmp = explode(':', $cd);

			// convert timestamp to date
			$tmp[0] = date('Y-m-d', $tmp[0]);

			// merge chunks together
			$cd = implode(':', $tmp);
		}

		// update new structure
		$config->set('closingdays', implode(';;', $list));

		return true;
	}
}
