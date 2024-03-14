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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments subscription model.
 *
 * @since 1.7
 */
class VikAppointmentsModelSubscription extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		if (!empty($data['trial']))
		{
			// we are going to set the TRIAL state for the
			// given record, so we need to turn it off from
			// any other subscription first
			$this->clearTrialState(@$data['group']);
		}

		// attempt to save subscription
		return parent::save($data);
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any assigned translation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_lang_subscr'))
			->where($dbo->qn('id_subscr') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langsubscr');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		return true;
	}

	/**
	 * Extends the specified date by the duration of the subscription.
	 *
	 * @param 	string  $date          The UTC date to extend.
	 * @param 	mixed   $subscription  Either a subscription object or its ID.
	 *
	 * @return 	mixed   The resulting date on success, -1 for lifetime subscription
	 *                  false in case the subscription doesn't exist.
	 */
	public function extend($date, $subscription)
	{
		// check if we have an ID
		if (is_numeric($subscription))
		{
			// get subscription table
			$table = $this->getTable();
			// attempt to load subscription details
			if (!$table->load((int) $subscription))
			{
				// subscription not found
				return false;
			}

			// get subscription details
			$subscription = $table->getProperties();
		}

		// always cast to object
		$subscription = (object) $subscription;

		// check if we have a lifetime subscription
		if ($subscription->type == 5)
		{
			// use lifetime constant
			return -1;
		}

		switch ($subscription->type)
		{
			case 2:
				// weekly subscription
				$add = 'weeks';
				break;
			
			case 3:
				// monthly subscription
				$add = 'months';
				break;
			
			case 4:
				// yearly subscription
				$add = 'years';
				break;
			
			default:
				// daily subscription
				$add = 'days';
		}

		if ($subscription->amount == 1)
		{
			// get rid of plural in case amount is 1
			$add = rtrim($add, 's');
		}

		// create date add string
		$add = '+' . $subscription->amount . ' ' . $add;

		// create date instance and extend it
		$date = new JDate($date);
		$date->modify($add);

		return $date->toSql();
	}

	/**
	 * Removes the TRIAL status from any existing subscription.
	 *
	 * @param 	mixed    $id     Either an array or an ID.
	 * @param 	boolean  $state  The new trial state.
	 * @param 	integer  $group  The subscription group. Use "1"
	 *                           for customers, "0" for employees.
	 *
	 * @return 	boolean
	 */
	public function setTrialState($id, $state = true, $group = null)
	{
		if (is_array($id))
		{
			// in case of array, take only the first element of the list
			$id = array_shift($id);
		}

		if ($state)
		{
			// group not specified, recover it from subscription details
			if (is_null($group))
			{
				$item = $this->getItem($id);

				if (!$item)
				{
					// item not found, abort
					return false;
				}

				// use the subscription group
				$group = $item->group;
			}

			// we are going to set the TRIAL state for the
			// given record, so we need to turn it off from
			// any other subscription first
			$this->clearTrialState($group);
		}

		// change state of selected records
		$this->publish($id, $state, 'trial');

		return true;
	}

	/**
	 * Removes the TRIAL status from any existing subscription.
	 *
	 * @param 	integer  $group  The subscription group. Use "1"
	 *                           for customers, "0" for employees.
	 *
	 * @return 	void
	 */
	protected function clearTrialState($group = 0)
	{
		$dbo = JFactory::getDbo();

		/**
		 * Mass update all the subscription without taking care
		 * of tracking events/hooks. Keep this process as smooth
		 * as possible.
		 *
		 * We need to clear all the records that belong to the
		 * specified group (@since 1.7).
		 */
		$q = $dbo->getQuery(true)
			->update($dbo->qn('#__vikappointments_subscription'))
			->set($dbo->qn('trial') . ' = 0')
			->where($dbo->qn('group') . ' = ' . (int) $group);

		$dbo->setQuery($q);
		$dbo->execute();
	}
}
