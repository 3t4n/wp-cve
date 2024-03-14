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
 * VikAppointments appointments confirmation view.
 *
 * @since 1.0
 */
class VikAppointmentsViewconfirmapp extends JViewVAP
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

		// get cart model
		$model = JModelVAP::getInstance('cart');

		// get cart instance
		$this->cart = $model->getCart();
		
		$this->itemid = $input->getUint('Itemid', 0);
		
		$items = $this->cart->getItemsList();

		// check if all the services booked owns the same employee
		$same_emp_id = VAPCartUtils::isSameEmployee($items);
		// obtain the list of all the services booked
		$services_id = VAPCartUtils::getServices($items);

		if ($same_emp_id)
		{
			// we can obtain the custom payments of the booked employee (just take the first one)
			$id_employee = $items[0]->getEmployeeID();
		}
		else
		{
			// the cart owns different employees (or maybe a single one hidden), we
			// need to get the global payments (use null to exclude the employee filter).
			$id_employee = null;
		}

		// load payments and translate them
		$this->payments = VikAppointments::getAllEmployeePayments($id_employee);
		VikAppointments::translatePayments($this->payments);

		// import custom fields renderer and loader (as dependency)
		VAPLoader::import('libraries.customfields.renderer');

		// get all custom fields for the selected services
		$cf = VAPCustomFieldsLoader::getInstance()
			->translate()
			->setLanguageFilter()
			->forService($services_id);

		if ($same_emp_id)
		{
			// obtain custom fields of the specified employee
			$cf->ofEmployee($id_employee);
		}

		// fetch custom fields
		$this->customFields = $cf->fetch();

		// get customer details of currently logged-in user
		$this->user = VikAppointments::getCustomer();

		// always refresh remaining user credit
		$this->cart->removeDiscount('credit');

		if ($this->user && $this->user->credit > 0)
		{
			// register user credit as discount
			$this->cart->addDiscount(new VAPCartDiscount('credit', $this->user->credit, $percent = false));
		}

		$this->cart->store();

		// check whether the form used to redeem the coupons should be displayed or not
		$this->anyCoupon = VikAppointments::hasCoupon('appointments');
		
		// fetch the field that should be used to validate the ZIP
		$this->zipFieldID = VikAppointments::getZipCodeValidationFieldId($id_employee, $services_id);

		// get order total
		$total = $this->cart->getTotalGross();

		// skip payment in case there are no gateways or the total is equals to 0
		$this->skipPayments = !$this->payments || $total <= 0 ? 1 : 0;
		
		// print conversion code if needed
		VAPLoader::import('libraries.models.conversion');
		VAPConversion::getInstance(array('page' => 'confirmapp'))->trackCode(array('total_cost' => $total));

		/**
		 * Prepare view contents and microdata.
		 *
		 * @since 1.7
		 */
		VikAppointments::prepareContent($this);

		// extend pathway for breadcrumbs module
		$this->extendPathway($app);

		// display the template
		parent::display($tpl);
	}

	/**
	 * Groups the items by service.
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	protected function groupItems()
	{
		// sort items by service and date
		$items = VAPCartUtils::sortItemsByServiceDate($this->cart->getItemsList());

		$arr = array();

		foreach ($this->cart->getItemsList() as $i)
		{
			$id_service = $i->getServiceID();

			if (!isset($arr[$id_service]))
			{
				$service = new stdClass;
				$service->id   = $id_service;
				$service->name = $i->getServiceName();
				$service->list = array();

				$arr[$id_service] = $service;
			}

			$arr[$id_service]->list[] = $i;
		}

		return array_values($arr);
	}

	/**
	 * Extends the pathway for breadcrumbs module.
	 *
	 * @param 	mixed 	$app  The application instance.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	protected function extendPathway($app)
	{
		$pathway = $app->getPathway();
		$items   = $pathway->getPathway();
		$last 	 = end($items);

		// Make sure the confirmation page is not a menu item, otherwise
		// the pathway will display something like:
		// Home > Menu > Confirmation > Confirm Order
		if ($last && strpos($last->link, '&view=confirmapp') === false)
		{
			// register link into the Breadcrumb
			$link = 'index.php?option=com_vikappointments&view=confirmapp' . ($this->itemid ? '&Itemid=' . $this->itemid : '');
			$pathway->addItem(JText::translate('VAPCONFIRMRESBUTTON'), $link);
		}
	}
}
