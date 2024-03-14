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
 * VikAppointments customer class handler.
 *
 * @since 1.7
 */
class VAPCustomer extends JObject
{
	/**
	 * A list of cached customers.
	 *
	 * @var VAPCustomer[]
	 */
	protected static $instances = array();

	/**
	 * Returns the details of the given customer.
	 *
	 * @param 	mixed  $id  The customer ID. If not specified, the customer assigned
	 *                      to the current user will be retrieved, if any.
	 *
	 * @return 	self   A customer instance.
	 *
	 * @throws  Exception
	 */
	public static function getInstance($id = null)
	{
		$key = !$id ? 'auto' : (int) $id;

		// check whether the customer has been already fetched
		if (!isset(static::$instances[$key]))
		{
			// nope, create a new instance
			static::$instances[$key] = new static($id);
		}

		return static::$instances[$key];
	}

	/**
	 * Class constructor.
	 *
	 * @param 	mixed  $id  The customer ID. If not specified, the customer assigned
	 *                      to the current user will be retrieved, if any.
	 *
	 * @throws  Exception
	 */
	public function __construct($id)
	{
		// initialize object with loaded details
		parent::__construct($this->load($id));
	}

	/**
	 * Checks whether the current user owns an active subscription.
	 *
	 * @param 	mixed    $id       Either an array of services or a service ID.
	 *                             Leave empty to bypass the service validation.
	 * @param 	mixed    $checkin  An optional check-in date to make sure the
	 *                             user is still subscribed on that date.
	 *
	 * @return 	boolean
	 */
	public function isSubscribed($id = null, $checkin = null)
	{
		// always treat the id as an array
		$id = (array) $id;

		$dispatcher = VAPFactory::getEventDispatcher();

		/**
		 * This event can be used to apply additional conditions to the subscription validation.
		 * It can be used to skip the default system validations.
		 *
		 * @param 	VAPCustomer  $customer  The customer details.
		 * @param 	array        $services  A list of services to check.
		 * @param 	mixed        $checkin   An optional check-in date (UTC).     
		 *
		 * @return 	boolean      True to flag the customer as subscribed; false to flag it as expired
		 *                       or not yet subscribed, any other value to rely on the default system.
		 *
		 * @since 	1.7
		 */
		$results = $dispatcher->trigger('onBeforeValidateCustomerSubscription', array($this, $id, $checkin));

		if (in_array(false, $results, true))
		{
			// customer not subscribed
			return false;
		}

		if (in_array(true, $results, true))
		{
			// customer correctly subscribed
			return false;
		}

		// Plugins did not manipulate the response, fallback to the default system.
		// First of all, make sure the user is subscribed.
		if (!$this->subscribed || !$this->subscription)
		{
			// user not subscribed or removed subscription plan
			return false;
		}

		// Then make sure the specified services are covered by the subscription plan.
		// Check only in case the list of assigned services is not empty, otherwise
		// go ahead because the plan supports any created service.
		if ($this->subscription['services'] && array_diff($id, $this->subscription['services']))
		{
			// at least one of the selected service is not covered...
			return false;
		}

		// check whether the check-in date was specified
		if (!VAPDateHelper::isNull($checkin))
		{
			// validate the selected check-in 
			if (VAPFactory::getConfig()->getBool('subscrthreshold') === true)
			{
				// validate the specified check-in against the expiration date
				$threshold = $checkin;
			}
			else
			{
				// use the current day in place of the check-in date
				$threshold = JFactory::getDate()->format('Y-m-d H:i:s');
			}

			// make sure the subscription plan is still active for the specified check-in (equals or higher)
			if (!$this->lifetime && $this->active_to_date < $threshold)
			{
				// the subscription is not lifetime and the expiration date is
				// lower than the given check-in date time
				return false;
			}
		}

		/**
		 * This event can be used to apply additional conditions to the subscription validation.
		 * When this hook triggers, the system already validated all the default conditions.
		 *
		 * @param 	VAPCustomer  $customer  The customer details.
		 * @param 	array        $services  A list of services to check.
		 * @param 	mixed        $checkin   An optional check-in date (UTC).      
		 *
		 * @return 	boolean      False to flag the customer subscription as expired.
		 *
		 * @since 	1.7
		 */
		if ($dispatcher->false('onAfterValidateCustomerSubscription', array($this, $id, $checkin)))
		{
			// a plugin extended the validation of the subscription and decided that this
			// customer is not compliant with the searched query
			return false;
		}

		// subscription active
		return true;
	}

	/**
	 * Helper method used to load the customer details.
	 *
	 * @param 	mixed  $id  The customer ID. If not specified, the customer assigned
	 *                      to the current user will be retrieved, if any.
	 *
	 * @throws  Exception
	 */
	protected function load($id)
	{
		$jid = null;

		if (is_null($id))
		{
			// get current CMS user
			$user = JFactory::getUser();

			// make sure the user is not a guest
			if ($user->guest)
			{
				// user not logged in, throw an exception
				throw new RangeException('User not logged in');
			}

			// get CMS user ID
			$jid = $user->id;
		}
		else
		{
			// use given ID
			$id = (int) $id;
		}

		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		
		// get customer details
		$q->select('c.*');
		$q->from($dbo->qn('#__vikappointments_users', 'c'));

		// get billing country name
		$q->select($dbo->qn('ctr.country_name', 'country'));
		$q->leftjoin($dbo->qn('#__vikappointments_countries', 'ctr') . ' ON ' . $dbo->qn('ctr.country_2_code') . ' = ' . $dbo->qn('c.country_code'));

		// get CMS user details
		$q->select($dbo->qn('u.name', 'user_name'));
		$q->select($dbo->qn('u.username', 'user_username'));
		$q->select($dbo->qn('u.email', 'user_email'));
		$q->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('u.id') . ' = ' . $dbo->qn('c.jid'));

		if (is_null($id))
		{
			// get customer by CMS user
			$q->where($dbo->qn('u.id') . ' = ' . $jid);
		}
		else
		{
			// get customer by ID
			$q->where($dbo->qn('c.id') . ' = ' . $id);
		}

		/**
		 * External plugins can attach to this hook in order to manipulate
		 * the query at runtime, in example to include additional properties.
		 *
		 * Notice that the query is always limited to 1 element, so it is not
		 * suggested to join here tables that may fetch several rows. Use a 
		 * different hook for this purpose (@see onSetupCustomerDetails).
		 *
		 * @param 	mixed    &$query  A query builder instance.
		 * @param 	integer  $id      The ID of the customer.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onLoadCustomerDetails', array(&$q, $id));

		$dbo->setQuery($q, 0, 1);
		$app = $dbo->loadObject();

		if (!$app)
		{
			// no matching customers
			throw new RuntimeException('User not found', 404);
		}

		$customer = new stdClass;

		foreach ($app as $k => $v)
		{
			// exclude CMS user fields, which will be added
			// into a different property
			if (!preg_match("/^user_/", $k))
			{
				$customer->{$k} = $v;
			}
		}

		// decode customer fields
		$customer->fields = (array) json_decode($customer->fields, true);

		// create CMS user object
		$customer->user = new stdClass;
		$customer->user->id       = $app->jid;
		$customer->user->name     = $app->user_name;
		$customer->user->username = $app->user_username;
		$customer->user->email    = $app->user_email;

		$now = JFactory::getDate();

		// check whether the customer owns an active subscription
		$customer->subscribed = $customer->lifetime || (!VAPDateHelper::isNull($customer->active_to_date) && $customer->active_to_date >= $now->toSql());

		if ($customer->subscribed && !$customer->lifetime)
		{
			// calculate remaining days
			$customer->daysLeft = VAPDateHelper::diff($customer->active_to_date, $now, 'days');
		}
		else
		{
			$customer->daysLeft = 0;
		}

		$customer->subscription = null;

		VAPLoader::import('libraries.models.subscriptions');

		// check whether the system is selling the subscriptions for the customers
		if (!VAPDateHelper::isNull($customer->active_since) && VAPSubscriptions::has())
		{
			// fetch latest subscription purchased by this customer
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id_subscr'))
				->from($dbo->qn('#__vikappointments_subscr_order'))
				->where($dbo->qn('id_user') . ' = ' . $customer->id)
				->order($dbo->qn('id') . ' DESC');

			// get any approved codes
			$approved = JHtml::fetch('vaphtml.status.find', 'code', array('subscriptions' => 1, 'approved' => 1));

			if ($approved)
			{
				// filter by approved status
				$q->where($dbo->qn('status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
			}

			$dbo->setQuery($q, 0, 1);

			if ($subscrId = $dbo->loadResult())
			{
				// load subscription details
				$customer->subscription = VAPSubscriptions::get($subscrId, $group = 0, $strict = false);
			}
		}

		/**
		 * External plugins can use this event to manipulate the object holding
		 * the details of the customer. Useful to inject all the additional data
		 * fetched with the manipulation of the query.
		 *
		 * @param 	mixed   $customer  The customer details object.
		 * @param 	object  $data      The query resulting object.
		 *
		 * @return 	void
		 */
		$dispatcher->trigger('onSetupCustomerDetails', array($customer, $app));

		return $customer;
	}
}
