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

VAPLoader::import('libraries.availability.timeline.parser');
VAPLoader::import('libraries.availability.timeline.renderer');

/**
 * Class responsible to dispatch the requested timeline parser.
 *
 * @since 1.7
 */
final class VAPAvailabilityTimelineFactory
{
	/**
	 * Creates a new instance of the timeline parser.
	 *
	 * @param 	VAPAvailabilitySearch  $search  The search handler.
	 * @param 	string                 $parser  The parser name.
	 *
	 * @return 	VAPAvailabilityTimelineParser   The parser handler.
	 */
	public static function getParser(VAPAvailabilitySearch $search, $parser = null)
	{
		if (!$parser)
		{
			// no specified parser, retrieve the best one according to
			// the details wrapped by the searcher
			$parser = static::findParser($search);
		}

		/**
		 * This hook can be used to safely change the class instance
		 * responsible of generating the availability timeline.
		 * In addition to returning the new class name, plugins must
		 * include all the needed resources. The returned object must
		 * also inherit VAPAvailabilityTimelineParser class.
		 *
	 	 * @param 	VAPAvailabilitySearch  $search  The search handler.
	 	 * @param 	string                 $parser  The parser name.
		 *
		 * @return 	string  The parser class name.
		 *
		 * @since 	1.7
		 */
		$classname = VAPFactory::getEventDispatcher()->triggerOnce('onCreateTimelineParser', array($search, $parser));

		if (!$classname)
		{
			// load default one
			VAPLoader::import('libraries.availability.timeline.parser.' . $parser);
			// create class name
			$classname = 'VAPAvailabilityTimelineParser' . ucfirst($parser);
		}

		// make sure the object is loaded and exists
		if (!class_exists($classname))
		{
			// class not found, throw an exception
			throw new Exception(sprintf('Timeline parser [%s] not found', $classname), 404);
		}

		// create new instance
		$handler = new $classname($search);

		// make sure the object is a valid instance
		if (!$handler instanceof VAPAvailabilityTimelineParser)
		{
			// Not a valid instance, unexpected behavior might occur...
			// Prevent them by throwing an exception.
			throw new Exception(sprintf('The class [%s] is not a valid instance', $classname), 500);
		}

		return $handler;
	}

	/**
	 * Creates a new instance of the timeline renderer.
	 *
	 * @param 	mixed   $timeline  Either a timeline instance or an array of timelines.
	 * @param 	string  $renderer  The renderer name.
	 *
	 * @return 	VAPAvailabilityTimelineRenderer   The renderer handler.
	 */
	public static function getRenderer($timeline, $renderer = null)
	{
		if (!$renderer)
		{
			// no specified renderer, retrieve the best one according to
			// the details wrapped by the searcher
			$renderer = static::findRenderer($timeline);
		}

		/**
		 * This hook can be used to safely change the class instance
		 * responsible of rendering the availability timeline.
		 * In addition to returning the new class name, plugins must
		 * include all the needed resources. The returned object must
		 * also inherit VAPAvailabilityTimelineRenderer class.
		 *
	 	 * @param 	VAPAvailabilityTimeline  $timeline  The search handler.
	 	 * @param 	string                   $renderer  The renderer name.
		 *
		 * @return 	string  The renderer class name.
		 *
		 * @since 	1.7
		 */
		$classname = VAPFactory::getEventDispatcher()->triggerOnce('onCreateTimelineRenderer', array($timeline, $renderer));

		if (!$classname)
		{
			// load default one
			VAPLoader::import('libraries.availability.timeline.renderer.' . $renderer);
			// create class name
			$classname = 'VAPAvailabilityTimelineRenderer' . ucfirst($renderer);
		}

		// make sure the object is loaded and exists
		if (!class_exists($classname))
		{
			// class not found, throw an exception
			throw new Exception(sprintf('Timeline renderer [%s] not found', $classname), 404);
		}

		// create new instance
		$handler = new $classname($timeline);

		// make sure the object is a valid instance
		if (!$handler instanceof VAPAvailabilityTimelineRenderer)
		{
			// Not a valid instance, unexpected behavior might occur...
			// Prevent them by throwing an exception.
			throw new Exception(sprintf('The class [%s] is not a valid instance', $classname), 500);
		}

		return $handler;
	}

	/**
	 * Automatically finds the parser that fits at best the given search.
	 *
	 * @param 	VAPAvailabilitySearch  $search  The search handler.
	 *
	 * @return 	string  The parser name.
	 */
	protected static function findParser(VAPAvailabilitySearch $search)
	{
		$id_service  = (int) $search->get('id_service');
		$id_employee = (int) $search->get('id_employee');

		if ($id_employee <= 0)
		{
			// get service model
			$model = JModelVAP::getInstance('service');
			// load assigned employees
			$employees = $model->getEmployees($id_service);

			if (count($employees) == 1)
			{
				// only one employee assigned to this service, use it
				$id_employee = (int) $employees[0]->id;
				// update search instance
				$search->set('id_employee', $id_employee);
			}
		}

		if ($id_employee <= 0)
		{
			// use service parser
			return 'service';
		}

		// get service-employee association model
		$model = JModelVAP::getInstance('serempassoc');
		// get service details
		$service = $model->getOverrides($id_service, $id_employee);

		if ($service && $service->max_capacity > 1 && $service->app_per_slot)
		{
			// use group parser
			return 'group';
		}

		// fallback to employee parser
		return 'employee';		
	}

	/**
	 * Automatically finds the renderer that fits at best the given timeline.
	 *
	 * @param 	mixed   $timeline  Either a timeline instance or an array of timelines.
	 *
	 * @return 	string  The renderer name.
	 */
	protected static function findRenderer($timeline)
	{
		if (is_array($timeline))
		{
			// an array of timelines was returned
			return 'multiple';
		}

		$id_service  = (int) $timeline->getSearch()->get('id_service');
		$id_employee = (int) $timeline->getSearch()->get('id_employee');

		// get service-employee association model
		$model = JModelVAP::getInstance('serempassoc');
		// get service details
		$service = $model->getOverrides($id_service, $id_employee);

		// switch timeline layout depending on the configuration of the service
		
		if ($service->checkout_selection && JFactory::getApplication()->isClient('site'))
		{
			// check-out selection available only from the front-end
			$count = 1;

			if ($id_employee <= 0)
			{
				// count number of employees assigned to the given service
				$count = count(JModelVAP::getInstance('service')->getEmployees($id_service));
			}

			// in order to use this layout, the seach must specify the employee or
			// the service must be assigned at most to a single employee
			if ($id_employee > 0 || $count == 1)
			{
				// we are here as the service allows the selection of the checkout
				return 'dropdown';
			}
		}
		
		if ($service->display_seats)
		{
			// we are here as the service needs to display the remaining seats
			return 'seats';
		}

		// get currently logged-in customer
		$customer = VikAppointments::getCustomer();

		// iterate all timeline levels
		foreach ($timeline as $times)
		{
			// iterate all times
			foreach ($times as $time)
			{
				// check whether the customer owns an active subscription for the booked service and
				// check-in date time (adjusted to UTC)
				$is_subscribed = ($customer && $customer->isSubscribed($id_service, $time->checkin('Y-m-d H:i:s', 'UTC')));

				// check whether the time owns a trace and the customer doesn't own an active subscription
				if ($time->ratesTrace && !$is_subscribed)
				{
					// display a timeline that includes the price per slot
					return 'ratesgrid';
				}
			}
		}

		// fallback to default timeline
		return 'default';
	}

	/**
	 * Class constructor.
	 * Declared with protected visibility to avoid its instantiation.
	 */
	protected function __construct()
	{
		// cannot be publicly instantiated
	}
}
