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
 * VikAppointments service details (calendar) view.
 *
 * @since 1.0
 */
class VikAppointmentsViewservicesearch extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{	
		$app   = JFactory::getApplication();
		$input = $app->input;

		$itemid = $input->getInt('Itemid', 0);
		
		// request args
		$id_employee = $input->getUint('id_employee', 0);
		$id_service  = $input->getUint('id_service', 0);
		$date 	     = $input->getString('date', null);
		$month 		 = $input->getString('month', null);
		$hour 		 = $input->getUint('hour', null);
		$min 		 = $input->getUint('min', null);

		if (!$id_service)
		{
			// fallback to XML notation
			$id_service = $input->getUint('id_ser', 0);
		}

		// get view model
		$model = JModelVAP::getInstance('servicesearch');

		// load details of requested employee
		$service = $model->getService($id_service, array('id_employee' => $id_employee));

		if (!$service)
		{
			// throw exception to safely break the process
			throw new Exception($model->getError($index = null, $string = true));
		}

		if (VAPFactory::getConfig()->getUint('loginreq') == 3 && !VikAppointments::isUserLogged())
		{
			// login is required
			$tpl = 'login';
		}
		else
		{
			// get reviews
			$base_uri = "index.php?option=com_vikappointments&view=servicesearch&id_service={$id_service}";

			if ($id_employee)
			{
				$base_uri .= '&id_employee=' . $id_employee;
			}

			if ($itemid)
			{
				$base_uri .= '&Itemid=' . $itemid;
			}

			$rev_ord_by   = $input->getString('revordby', '');
			$rev_ord_mode = $input->getString('revordmode', '');
			
			$this->userCanLeaveReview   = VikAppointments::userCanLeaveServiceReview($id_service);
			$this->reviewsOrderingLinks = VikAppointments::getReviewsOrderingLinks($base_uri, $rev_ord_by, $rev_ord_mode);

			/**
			 * Use the first available employee if not specified.
			 *
			 * Make sure the random selection is disabled, otherwise we need to avoid
			 * picking the employees, because the availability must be calculated for
			 * all the supported employees (@since 1.7).
			 */
			if ($id_employee <= 0 && count($service->employees) && $service->choose_emp && !$service->random_emp)
			{
				$id_employee = $service->employees[0]->id;
			}

			if ($id_employee > 0)
			{
				/**
				 * Get the selected employee to obtain here the details to use.
				 *
				 * @since 1.6
				 */
				$employee = $this->getSelectedEmployee($id_employee, $service->employees);
			}
			else
			{
				$employee = null;
			}

			// get locations from request
			$locations = $input->getUint('locations', array());

			// get all IDs of the supported locations
			$loc_ids = array_map(function($l)
			{
				return $l->id;
			}, $service->locations);

			/**
			 * It is needed to unset all the locations that don't belong
			 * to the current employee, as they may have been submitted
			 * after selecting a different employee.
			 *
			 * @since 1.6
			 */
			$locations = array_values(array_intersect($locations, $loc_ids));
			
			// use all the locations if not provided
			if (count($locations) == 0 && count($service->locations) > 1)
			{
				$locations = $loc_ids;
			}
			
			// remove all the expired reservations (switch status from PENDING to REMOVED)
			VikAppointments::removeAllServicesReservationsOutOfTime($service->id);

			// prepare calendar availability search options
			$options = array(
				'id_ser'    => $id_service,
				'id_emp'    => $id_employee,
				'locations' => $locations,
			);

			if ($month)
			{
				// use the specified month date
				$options['start'] = $month;
			}

			if (!VAPDateHelper::isNull($date) || $month)
			{
				if ($date)
				{
					// re-format date
					$date = VAPDateHelper::getDate($date)->format('Y-m-d');
				}

				/**
				 * @todo Add helper method to check whether the date is contained within
				 *       the calendar range. If it isn't, we should use the first available
				 *       date, or the current date in case the selected one is in the past.
				 */

				$options['date'] = $date;
			}

			// fetch calendar data
			$this->calendar = $model->getCalendar($options);

			// load cart instance
			$cart = JModelVAP::getInstance('cart')->getCart();

			$this->isCartEmpty = $cart->isEmpty();

			if ($service && $service->checkout_selection)
			{
				/**
				 * In case the checkout selection is allowed, we need to include
				 * the script used to handle the checkin/checkout events.
				 *
				 * @since 1.6
				 */
				$js = JLayoutHelper::render('javascript.timeline.dropdown');
				$this->document->addScriptDeclaration($js);
			}

			// fetch employee details
			$this->employee = $employee;

			// define search options
			$this->options = $options;
		}

		// register service instance
		$this->service = $service;

		$this->idService  = $id_service;
		$this->idEmployee = $id_employee;
		$this->date 	  = $date;
		$this->month 	  = $month;
		$this->hour 	  = $hour;
		$this->min 		  = $min;
		$this->itemid 	  = $itemid;

		// prepare view contents and microdata
		VikAppointments::prepareContent($this);

		// extend pathway for breadcrumbs module
		$this->extendPathway($app);
		
		// display the template
		parent::display($tpl);
	}

	/**
	 * Finds the specified employee located in the given array.
	 *
	 * @param 	integer  $id_employee  The employee to search.
	 * @param 	array 	 $employees    The haystack.
	 *
	 * @return 	mixed    The employee array on success, otherwise false.
	 *
	 * @since 	1.6
	 */
	protected function getSelectedEmployee($id_employee, array $employees)
	{
		foreach ($employees as $e)
		{
			if ($e->id == $id_employee)
			{
				return $e;
			}
		}

		return false;
	}

	/**
	 * Extends the pathway for breadcrumbs module.
	 *
	 * @param 	mixed 	$app  The application instance.
	 *
	 * @return 	void
	 *
	 * @since 	1.6
	 */
	protected function extendPathway($app)
	{
		$pathway = $app->getPathway();
		$items   = $pathway->getPathway();
		$last 	 = end($items);

		$name = $this->service->name;
		$id   = $this->service->id;

		// Make sure this service is not a menu item, otherwise
		// the pathway will display something like:
		// Home > Menu > Item > Item
		if ($last && strpos($last->link, '&id_service=' . $id) === false)
		{
			// register link into the Breadcrumb
			$link = 'index.php?option=com_vikappointments&view=servicesearch&id_service=' . $id;
			$pathway->addItem($name, $link);
		}
	}
}
