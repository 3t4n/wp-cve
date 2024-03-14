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
 * VikAppointments employee details (calendar) view.
 *
 * @since 1.0
 */
class VikAppointmentsViewemployeesearch extends JViewVAP
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

		// get view model
		$model = JModelVAP::getInstance('employeesearch');

		// load details of requested employee
		$employee = $model->getEmployee($id_employee, array('id_service' => $id_service));

		if (!$employee)
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
			$base_uri = "index.php?option=com_vikappointments&view=employeesearch&id_employee={$id_employee}";

			if ($id_service)
			{
				$base_uri .= '&id_service=' . $id_service;
			}

			if ($itemid)
			{
				$base_uri .= '&Itemid=' . $itemid;
			}

			$rev_ord_by   = $input->getString('revordby', '');
			$rev_ord_mode = $input->getString('revordmode', '');
			
			$this->userCanLeaveReview   = VikAppointments::userCanLeaveEmployeeReview($id_employee);
			$this->reviewsOrderingLinks = VikAppointments::getReviewsOrderingLinks($base_uri, $rev_ord_by, $rev_ord_mode);

			// use the first available service if not specified
			if ($id_service <= 0 && count($employee->services))
			{
				$id_service = $employee->services[0]->id;
			}

			if ($id_service > 0)
			{
				/**
				 * Get the selected service to obtain here the details to use.
				 *
				 * @since 1.6
				 */
				$service = $this->getSelectedService($id_service, $employee->services);
			}
			else
			{
				$service = null;
			}

			// get locations from request
			$locations = $input->getUint('locations', array());
			
			// use all the locations if not provided
			if (count($locations) == 0 && count($employee->locations) > 1)
			{
				foreach ($employee->locations as $loc)
				{
					$locations[] = $loc->id;
				}
			}
			
			// remove all the expired reservations (switch status from PENDING to REMOVED)
			VikAppointments::removeAllReservationsOutOfTime($employee->id);

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
		
			// register service instance
			$this->service = $service;

			// define search options
			$this->options = $options;
		}

		// fetch employee details
		$this->employee = $employee;

		$this->idEmployee = $id_employee;
		$this->idService  = $id_service;
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
	 * Finds the specified service located in the given array.
	 *
	 * @param 	integer  $id_service  The service to search.
	 * @param 	array    $services 	  The haystack.
	 *
	 * @return 	mixed    The service array on success, otherwise false.
	 *
	 * @since 	1.6
	 */
	protected function getSelectedService($id_service, array $services)
	{
		foreach ($services as $s)
		{
			if ($s->id == $id_service)
			{
				return $s;
			}
		}

		return false;
	}

	/**
	 * Groups the services in categories.
	 *
	 * @since 1.7
	 */
	protected function groupServices()
	{
		$groups = array();

		foreach ($this->employee->services as $s)
		{
			$id_group = $s->id_group > 0 ? (int) $s->id_group : 0;

			if (!isset($groups[$s->id_group]))
			{
				$g = new stdClass;
				$g->id   = $s->id_group;
				$g->name = $s->groupName;

				$g->services = array();

				$groups[$g->id] = $g; 
			}

			$groups[$s->id_group]->services[] = $s;
		}

		if (count($groups) > 1 && isset($groups[0]))
		{
			// always move services without group at the end of the list
			$tmp = $groups[0];
			unset($groups[0]);
			$groups[0] = $tmp;
		}

		return array_values($groups);
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

		$name = $this->employee->nickname;
		$id   = $this->employee->id;

		// Make sure this employee is not a menu item, otherwise
		// the pathway will display something like:
		// Home > Menu > Item > Item
		if ($last && strpos($last->link, '&id_employee=' . $id) === false)
		{
			// register link into the Breadcrumb
			$link = 'index.php?option=com_vikappointments&view=employeesearch&id_employee=' . $id;
			$pathway->addItem($name, $link);
		}
	}
}
