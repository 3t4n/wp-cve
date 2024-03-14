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

VAPLoader::import('models.subscrcart', VAPBASE);

/**
 * VikAppointments subscription cart model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpsubscrcart extends VikAppointmentsModelSubscrcart
{
	/**
	 * An optional suffix to be included within the session keys.
	 *
	 * @var string
	 */
	protected $suffix = 'emp';

	/**
	 * Loads all the subscriptions available for the current user.
	 *
	 * @return 	array
	 */
	public function getAllSubscriptions()
	{
		static $subscriptions = null;

		// load subscriptions only once
		if (is_null($subscriptions))
		{
			// load all the active subscriptions (ignore TRIAL, if any)
			$subscriptions = VAPSubscriptions::getList($group = 1);
		}

		return $subscriptions;
	}

	/**
	 * Loads all the subscriptions available for the current user.
	 *
	 * @return 	array
	 */
	public function getTrial()
	{
		$auth = VAPEmployeeAuth::getInstance();

		// get TRIAL subscription, if any
		$trial = VAPSubscriptions::getTrial($group = 1);

		// make sure the TRIAL exists and the employee never activated its account
		if ($auth->isEmployee() && $trial && $auth->active_to == 0)
		{
			return $trial;
		}

		return null;
	}

	/**
	 * Helper method used to fetch the subscription data.
	 * Override to load the subscription of the employees.
	 *
	 * @param 	integer  $id_subscr  The subscription ID.
	 *
	 * @return 	array
	 */
	protected function fetchSubscription($id_subscr)
	{
		return VAPSubscriptions::get($id_subscr, $group = 1);
	}
}
