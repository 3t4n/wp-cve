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
 * With the implementation of the status codes, it is now possible to choose for which statuses, the system
 * should send a notification e-mail. For this reason, during the update to the new 1.7 version, after
 * installing the default statuses, we should update the configuration to support the new statuses.
 *
 * Similar changes have to be applied also to the following tables:
 * - `#__vikappointments_reservation`
 * - `#__vikappointments_package_order`
 * - `#__vikappointments_subscr_order`
 * - `#__vikappointments_conversion`
 * - `#__vikappointments_order_status`
 * - `#__vikappointments_cust_mail`
 * - `#__vikappointments_configuration` (default status)
 *
 * @since 1.7
 */
class VAPUpdateRuleStatusCodesMapper1_7 extends VAPUpdateRule
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
		$config = VAPFactory::getConfig();

		$this->adaptConfiguration();
		$this->adaptReservations();
		$this->adaptPackages();
		$this->adaptSubscriptions();
		$this->adaptConversions();
		$this->adaptOrderHistory();
		$this->adaptCustomMailTexts();

		return true;
	}

	/**
	 * Adjusts the status codes from the configuration.
	 *
	 * @return 	void
	 */
	private function adaptConfiguration()
	{
		$config = VAPFactory::getConfig();

		// update notification statuses
		foreach (array('mailcustwhen', 'mailempwhen', 'mailadminwhen') as $key)
		{
			switch ($config->getUint($key))
			{
				case 1:
					// only confirmed
					$config->set($key, array('C', 'P'));
					break;

				case 2:
					// confirmed and pending
					$config->set($key, array('C', 'P', 'W'));
					break;

				default:
					// never
					$config->set($key, array());
			}
		}

		// update default status
		if ($config->get('defstatus') == 'CONFIRMED')
		{
			$config->set('defstatus', 'C');
		}
		else
		{
			$config->set('defstatus', 'W');
		}
	}

	/**
	 * Adjusts the status codes for the appointments.
	 *
	 * @return 	void
	 */
	private function adaptReservations()
	{
		$dbo = JFactory::getDbo();

		// set PAID status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_reservation'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('P'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'))
			->where($dbo->qn('paid') . ' = 1');

		$dbo->setQuery($q);
		$dbo->execute();

		// set CONFIRMED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_reservation'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('C'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set PENDING status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_reservation'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('W'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('PENDING'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set REMOVED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_reservation'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('E'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('REMOVED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set CANCELLED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_reservation'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('X'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CANCELED'));

		$dbo->setQuery($q);
		$dbo->execute();
	}

	/**
	 * Adjusts the status codes for the packages.
	 *
	 * @return 	void
	 */
	private function adaptPackages()
	{
		$dbo = JFactory::getDbo();

		// set PAID status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_package_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('P'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'))
			->where($dbo->qn('tot_paid') . ' > 1');

		$dbo->setQuery($q);
		$dbo->execute();

		// set CONFIRMED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_package_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('C'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set PENDING status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_package_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('W'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('PENDING'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set REFUNDED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_package_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('R'))
			->where($dbo->qn('status') . ' IN (' . $dbo->q('CANCELED') . ', ' . $dbo->q('REMOVED') . ')')
			->where($dbo->qn('tot_paid') . ' > 0');

		$dbo->setQuery($q);
		$dbo->execute();

		// set CANCELLED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_package_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('X'))
			->where($dbo->qn('status') . ' IN (' . $dbo->q('CANCELED') . ', ' . $dbo->q('REMOVED') . ')');

		$dbo->setQuery($q);
		$dbo->execute();
	}

	/**
	 * Adjusts the status codes for the subscriptions.
	 *
	 * @return 	void
	 */
	private function adaptSubscriptions()
	{
		$dbo = JFactory::getDbo();

		// set PAID status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_subscr_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('P'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'))
			->where($dbo->qn('tot_paid') . ' > 1');

		$dbo->setQuery($q);
		$dbo->execute();

		// set CONFIRMED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_subscr_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('C'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set PENDING status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_subscr_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('W'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('PENDING'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set REFUNDED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_subscr_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('R'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('REMOVED'))
			->where($dbo->qn('tot_paid') . ' > 0');

		$dbo->setQuery($q);
		$dbo->execute();

		// set CANCELLED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_subscr_order'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('X'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('REMOVED'));

		$dbo->setQuery($q);
		$dbo->execute();
	}

	/**
	 * Adjusts the status codes for the conversions.
	 *
	 * @return 	void
	 */
	private function adaptConversions()
	{
		// define statuses lookup
		$lookup = array(
			'CONFIRMED' => 'C',
			'PENDING'   => 'W',
			'REMOVED'   => 'E',
			'CANCELED'  => 'X',
		);

		$dbo = JFactory::getDbo();

		// load all the conversion codes
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'statuses')))
			->from($dbo->qn('#__vikappointments_conversion'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $c)
			{
				// get list of statuses
				$statuses = (array) json_decode($c->statuses);
				// convert old statuses
				$statuses = array_map(function($s) use ($lookup)
				{
					if (!isset($lookup[$s]))
					{
						// return default status
						return $s;
					}

					return $lookup[$s];
				}, $statuses);

				if (in_array('C', $statuses))
				{
					// include PAID status too
					$statuses[] = 'P';
				}

				// commit changes
				$c->statuses = json_encode($statuses);
				$dbo->updateObject('#__vikappointments_conversion', $c, 'id');
			}
		}
	}

	/**
	 * Adjusts the status codes for the orders history.
	 *
	 * @return 	void
	 */
	private function adaptOrderHistory()
	{
		$dbo = JFactory::getDbo();

		// set CONFIRMED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_order_status'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('C'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set PENDING status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_order_status'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('W'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('PENDING'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set REMOVED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_order_status'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('E'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('REMOVED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set CANCELLED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_order_status'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('X'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CANCELED'));

		$dbo->setQuery($q);
		$dbo->execute();
	}

	/**
	 * Adjusts the status codes for the orders history.
	 *
	 * @return 	void
	 */
	private function adaptCustomMailTexts()
	{
		$dbo = JFactory::getDbo();

		// set CONFIRMED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_cust_mail'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('C'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set PENDING status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_cust_mail'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('W'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('PENDING'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set REMOVED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_cust_mail'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('E'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('REMOVED'));

		$dbo->setQuery($q);
		$dbo->execute();

		// set CANCELLED status
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_cust_mail'))
			->set($dbo->qn('status') . ' = ' . $dbo->q('X'))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CANCELED'));

		$dbo->setQuery($q);
		$dbo->execute();
	}
}
