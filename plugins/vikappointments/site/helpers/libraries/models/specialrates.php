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

defined('SPECIAL_RATES_DEBUG') or define('SPECIAL_RATES_DEBUG', 0);

/**
 * VikAppointments special rates class handler.
 *
 * @since  	1.6
 */
abstract class VAPSpecialRates
{
	/**
	 * List used to cache the base price of the services.
	 *
	 * @var array
	 */
	protected static $baseCosts = array();

	/**
	 * List used to cache the special rates found for the given service
	 * and employee relationships.
	 *
	 * @var array
	 */
	protected static $rates = array();

	/**
	 * Calculates the final rate depending on the selected service, employee,
	 * checkin timestamp and number of people.
	 *
	 * @param 	integer  $id_service 	The service ID.
	 * @param 	integer  $id_employee 	The employee ID.
	 * @param 	mixed    $checkin 		Either a check-in timestamp or a date.
	 * @param 	integer  $people 		The number of guests.
	 * @param 	array 	 &$trace 		An array to keep track of the rates applied.
	 *
	 * @return 	float  	 The final base cost.
	 *
	 * @uses 	getBaseCost()
	 * @uses 	getRatesList()
	 * @uses 	isCompatible()
	 */
	public static function getRate($id_service, $id_employee = 0, $checkin = 0, $people = 1, array &$trace = null)
	{
		// obtain the base cost to use
		$cost = static::getBaseCost($id_service, $id_employee);

		if ($cost === false)
		{
			// the service doesn't exist, return a null cost
			return 0;
		}

		$cost = (float) $cost;

		if (!is_null($trace))
		{
			// add basecost to trace
			$trace['basecost'] = $cost;
		}

		// get the full rates list compatible with the given service
		$rates = static::getRatesList($id_service);

		// iterate the rates found
		foreach ($rates as $rate)
		{
			// check if the rate is compatible with the given parameters
			if (static::isCompatible($rate, $checkin, $people, $trace))
			{
				// Increase the base cost with the rate charge.
				// If the charge is negative, the base cost will be discounted.
				$cost += $rate->charge;

				if (!is_null($trace))
				{
					if (!isset($trace['rates']))
					{
						$trace['rates'] = array();
					}

					// add rate details to trace
					$trace['rates'][] = $rate;
				}
			}
		}

		// avoid to return a negative value due to a bad configuration
		return (float) max(array(0, $cost));
	}

	/**
	 * Returns the base cost of the given service-employee relationship.
	 *
	 * @param 	integer  $id_service 	The service ID.
	 * @param 	integer  $id_employee 	The employee ID.
	 *
	 * @return 	float 	 The base cost if the service exists, otherwise false.
	 */
	public static function getBaseCost($id_service, $id_employee = 0)
	{
		$sign = serialize(array($id_service, $id_employee));

		// check if the base cost has been already cached
		if (!isset(static::$baseCosts[$sign]))
		{
			$dbo = JFactory::getDbo();

			// retrieve the base cost of the service (must be published)
			$q = $dbo->getQuery(true)
				->select($dbo->qn('s.price'))
				->from($dbo->qn('#__vikappointments_service', 's'))
				->where($dbo->qn('s.published') . ' = 1')
				->where($dbo->qn('s.id') . ' = ' . (int) $id_service);

			// if the employee is specified, we should check for a price override
			if ($id_employee > 0)
			{
				$q->select($dbo->qn('a.rate'))
					->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('a.id_service') . ' = ' . $dbo->qn('s.id'))
					->where($dbo->qn('a.id_employee') . ' = ' . (int) $id_employee);
			}

			$dbo->setQuery($q, 0, 1);
			$service = $dbo->loadObject();

			if (!$service)
			{
				// service not found, return false
				return false;
			}

			// check for an override
			if (isset($service->rate))
			{
				static::$baseCosts[$sign] = (float) $service->rate;
			}
			// otherwise use the base cost of the service
			else
			{
				static::$baseCosts[$sign] = (float) $service->price;
			}
		}

		return static::$baseCosts[$sign];
	}

	/**
	 * Returns the list of special rates compatible with the given service.
	 *
	 * @param 	integer  $id_service 	The service ID.
	 *
	 * @return 	array 	 The rates list.
	 */
	public static function getRatesList($id_service)
	{
		// check if the rates list has been already cached
		if (!isset(static::$rates[$id_service]))
		{
			$dbo = JFactory::getDbo();

			/**
			 * This SELECT is able to retrieve the special rates in these 2 cases:
			 * - the given service is assigned to the rate
			 * - the rate doesn't own any service (in other words can be applied to any service)
			 * 		 
			 *
			 * SELECT r.*
			 * FROM `#__vikappointments_special_rates` AS `r`
			 * LEFT JOIN `#__vikappointments_ser_rates_assoc` AS `a` ON `a`.`id_special_rate` = `r`.`id`
			 * WHERE 
			 * (
			 * 		`a`.`id_service`=21 OR
			 * 		(
			 *			SELECT COUNT(1)
    		 *			FROM `#__vikappointments_ser_rates_assoc` AS `ai`
    		 *			WHERE `a`.`id_special_rate` = `r`.`id`
			 *		) = 0
			 * ) AND `r`.published = 1
			 */

			// inner query to search for rates with no service assigned
			$no_service_query = $dbo->getQuery(true)
				->select('COUNT(1)')
				->from($dbo->qn('#__vikappointments_ser_rates_assoc', 'ai'))
				->where($dbo->qn('a.id_special_rate') . ' = ' . $dbo->qn('r.id'));

			// main query
			$q = $dbo->getQuery(true)
				->select('`r`.*')
				->from($dbo->qn('#__vikappointments_special_rates', 'r'))
				->leftjoin($dbo->qn('#__vikappointments_ser_rates_assoc', 'a') . ' ON ' . $dbo->qn('a.id_special_rate') . ' = ' . $dbo->qn('r.id'))
				->where($dbo->qn('a.id_service') . ' = ' . (int) $id_service)
				->orWhere("(" . $no_service_query . ") = 0")
				->andWhere($dbo->qn('r.published') . ' = 1');

			$dbo->setQuery($q);
			
			$rates = array();

			foreach ($dbo->loadObjectList() as $rate)
			{
				// convert the week days (separated by a comma) into an array
				$rate->weekdays = strlen($rate->weekdays) ? explode(',', $rate->weekdays) : array();
				// convert the usergroups (separated by a comma) into an array
				$rate->usergroups = strlen($rate->usergroups) ? explode(',', $rate->usergroups) : array();

				/**
				 * Decode rate params.
				 *
				 * @since 1.6.2
				 */
				$rate->params = $rate->params ? json_decode($rate->params) : new stdClass;

				// push the rate in the list
				$rates[] = $rate;
			}

			static::$rates[$id_service] = $rates;
		}

		return static::$rates[$id_service];
	}

	/**
	 * Checks if the parameters are compatible with the given rate.
	 *
	 * @param 	object   $rate 		The rate object.
	 * @param 	mixed    $checkin 	Either a check-in timestamp or a date.
	 * @param 	integer  $people 	The number of guests.
	 * @param 	array 	 &$trace 	An array to keep track of the rates applied.
	 *
	 * @return 	boolean  True if compatible, otherwise false.
	 *
	 * @uses 	debugTrace()
	 */
	public static function isCompatible($rate, $checkin, $people, array &$trace = null)
	{
		// make sure the checkin has been provided
		if (!$checkin)
		{
			// keep failure in debug trace
			static::debugTrace($trace, 'nocheckin', $rate, $checkin);

			// no checkin provided
			return false;
		}

		// compare the number of guests
		if ($rate->people && $rate->people != $people)
		{
			// keep failure in debug trace
			static::debugTrace($trace, 'people', $rate, $people);

			// the people is defined in the rate but 
			// it is not equal to the given value
			return false;
		}

		// handle check-in with JDate
		$checkinDate = new JDate($checkin);
		$checkin = $checkinDate->toSql();

		// make sure the checkin is valid
		if (!VAPDateHelper::isNull($rate->fromdate) && $rate->fromdate > $checkin)
		{
			// keep failure in debug trace
			static::debugTrace($trace, 'fromdate', $rate, $checkin);

			// the start publishing is higher than the specified checkin
			return false;
		}

		// make sure the checkin is valid
		if (!VAPDateHelper::isNull($rate->todate) && $rate->todate < $checkin)
		{
			// keep failure in debug trace
			static::debugTrace($trace, 'todate', $rate, $checkin);

			// the end publishing is lower than the specified checkin
			return false;
		}

		// get checkin day of the week
		$wd = $checkinDate->format('w');

		// check the weekday
		if ($rate->weekdays && !in_array($wd, $rate->weekdays))
		{
			// keep failure in debug trace
			static::debugTrace($trace, 'weekdays', $rate, $wd);

			// the weekdays list is not empty and doesn't
			// contain the given day of the week
			return false;
		}

		// make sure the checkin time is contained 
		// in the time range of the rate
		if ($rate->fromtime < $rate->totime)
		{
			// Format time locally, because from time and to time
			// provided by the special rate are fixed.
			// Use UTC timezone to avoid altering the check-in time,
			// since that is the offset assumed by the "date" helper.
			$checkinTime = JHtml::fetch('date', $checkin, 'H:i', 'UTC');
			// convert time to integer for easier comparison
			$checkinTime = JHtml::fetch('vikappointments.time2min', $checkinTime);

			// make sure the check-in stays between from time and to time
			if ($checkinTime < $rate->fromtime || $rate->totime < $checkinTime)
			{
				// keep failure in debug trace
				static::debugTrace($trace, 'time', $rate, $checkin);

				// the checkin time is outside the bounds
				return false;
			}
		}

		// make sure the usergroup is supported by the rate
		if (count($rate->usergroups))
		{
			// check if the usergroup was specified in the trace array
			if (isset($trace['usergroup']))
			{
				$usergroup = array($trace['usergroup']);
			}
			// check if the user ID was specified in the trace
			else if (isset($trace['id_user']))
			{
				$user = JUser::getInstance((int) $trace['id_user']);

				$usergroup = array_values($user->groups);
			}
			// get usergroup from the current user
			else
			{
				$user = JFactory::getUser();

				$usergroup = array_values($user->groups);
			}

			// check if the intersection between the arrays returns
			// at least an element (in common)
			if (!array_intersect($usergroup, $rate->usergroups))
			{
				// keep failure in debug trace
				static::debugTrace($trace, 'usergroup', $rate, $usergroup);

				// the usergroup is not supported
				return false;
			}
		}

		// the rate is compatible
		return true;
	}

	/**
	 * Returns a list of rates available for the specified service and checkin day.
	 *
	 * @param 	integer  $id_service  The service ID.
	 * @param 	mixed    $checkin     Either a check-in timestamp or a date.
	 *
	 * @return 	array  	 The available rates.
	 *
	 * @uses 	getRatesList()
	 */
	public static function getRatesOnDay($id_service, $checkin = 0)
	{
		$list = array();

		// get the full rates list compatible with the given service
		$rates = static::getRatesList($id_service);

		// handle check-in with JDate
		$checkinDate = new JDate($checkin);
		$checkin = $checkinDate->toSql();

		// get check-in day of the week
		$wd = $checkinDate->format('w');

		// iterate the rates found
		foreach ($rates as $rate)
		{
			if (
				// validate start publishing
				(VAPDateHelper::isNull($rate->fromdate) || $rate->fromdate <= $checkin)
				// validate end publishing
				&& (VAPDateHelper::isNull($rate->todate) || $rate->todate >= $checkin)
				// validate day of the week
				&& (!$rate->weekdays || in_array($wd, $rate->weekdays))
			) {
				// rate found, push it within the list
				$list[] = $rate;
			}

		}

		return $list;
	}

	/**
	 * Extracts the CSS class to be used from a list of special rates.
	 *
	 * @param 	array 	$rates 	 A list of rates.
	 * @param 	string  $caller  The caller that requested the method.
	 *
	 * @return 	string 	The fetched class.
	 *
	 * @since 	1.6.2
	 */
	public static function extractClass(array $rates = array(), $caller = '')
	{
		$class = array();

		// fetch caller
		if (preg_match("/cal|calendar/i", $caller))
		{
			$caller = 'cal_';
		}
		else
		{
			$caller = '';
		}

		// iterate all rates
		foreach ($rates as $trace)
		{
			if (!empty($trace->params->{$caller . 'class_sfx'}))
			{
				// include class suffix within the list
				$class = array_merge($class, explode(' ', $trace->params->{$caller . 'class_sfx'}));
			}

			if (!empty($trace->params->{$caller . 'style_class'}))
			{
				// include style preset
				$class[] = $trace->params->{$caller . 'style_class'};
			}
		}

		// make array unique and join all classes
		return $class ? ' ' . implode(' ', array_unique($class)) : '';
	}

	/**
	 * Tries to push the (failure) log within the debug attribute of the trace.
	 *
	 * @param 	array 	 &$trace 	An array to keep track of the rates applied.
	 * @param 	string 	 $id 		An identifier to build the log.	
	 * @param 	object 	 $rate 		The rate that is not compatible.
	 * @param 	mixed 	 $target 	The value of the element that caused the failure.
	 *
	 * @return 	boolean  True if added, otherwise false.
	 */
	protected static function debugTrace(array &$trace = null, $id = null, $rate = null, $target = null)
	{
		if (is_null($trace) || (!isset($trace['debug']) && !SPECIAL_RATES_DEBUG))
		{
			// we are here for 2 possible reasons:
			// - trace var not provided
			// - the trace doesn't need to register debug data
			return false;
		}

		if (!isset($trace['debug']))
		{
			// the debug is forced by the global constant
			$trace['debug'] = array();
		}

		$config = VAPFactory::getConfig();

		switch ($id)
		{
			case 'nocheckin':
				$log = sprintf(
					'The checkin date and time was not provided: %d given.',
					$target
				);
				break;

			case 'people':
				$log = sprintf(
					'The number of people does not match: %d expected, %d given.',
					$rate->people,
					$target
				);
				break;

			case 'fromdate':
				$log = sprintf(
					'The given date is previous than the start publishing: starts on %s, %s given.',
					JHtml::fetch('date', $rate->fromdate, $config->get('dateformat') . ' ' . $config->get('timeformat')),
					JHtml::fetch('date', $target, $config->get('dateformat') . ' ' . $config->get('timeformat'), 'UTC')
				);
				break;

			case 'todate':
				$log = sprintf(
					'The given date is after the end publishing: ends on %s, %s given.',
					JHtml::fetch('date', $rate->todate, $config->get('dateformat') . ' ' . $config->get('timeformat')),
					JHtml::fetch('date', $target, $config->get('dateformat') . ' ' . $config->get('timeformat'), 'UTC')
				);
				break;

			case 'weekdays':
				$lookup = array(
					JText::translate('SUN'),
					JText::translate('MON'),
					JText::translate('TUE'),
					JText::translate('WED'),
					JText::translate('THU'),
					JText::translate('FRI'),
					JText::translate('SAT'),
				);

				$allowed_days = array_map(function($day) use ($lookup)
				{
					return $lookup[$day];
				}, $rate->weekdays);

				$log = sprintf(
					'The day of the week is not included: %s expected, %s given.',
					implode(', ', $allowed_days),
					$lookup[(int) $target]
				);
				break;

			case 'time':
				$flabel = JHtml::fetch('vikappointments.min2time', $rate->fromtime);
				$tlabel = JHtml::fetch('vikappointments.min2time', $rate->totime);

				$log = sprintf(
					'The given time is not included between the range of the rate: %s expected, %s given.',
					$flabel . '-' . $tlabel,
					JHtml::fetch('date', $target, $config->get('timeformat'), 'UTC')
				);
				break;

			case 'usergroup':
				$lookup = JHelperUsergroups::getInstance()->getAll();

				$allowed_groups = array_map(function($group) use ($lookup)
				{
					if (!isset($lookup[$group]))
					{
						return $group;
					}

					return $lookup[$group]->title;
				}, $rate->usergroups);

				$given_groups = array_map(function($group) use ($lookup)
				{
					if (!isset($lookup[$group]))
					{
						return $group;
					}

					return $lookup[$group]->title;
				}, $target);

				$log = sprintf(
					'The specified usergroup is not included: %s expected, %s given.',
					implode(', ', $allowed_groups),
					implode(', ', $given_groups)
				);
				break;

			default:
				$log = 'Unknown reason.';
		}

		// push the log within the list
		$trace['debug'][] = array(
			'id' 			=> $rate->id,
			'name' 			=> $rate->name,
			'description' 	=> $rate->description,
			'error' 		=> $log,
		);

		return true;
	}
}
