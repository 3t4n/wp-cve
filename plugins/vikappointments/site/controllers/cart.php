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

VAPLoader::import('libraries.mvc.controllers.admin');

/**
 * VikAppointments cart controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerCart extends VAPControllerAdmin
{
	/**
	 * Method used to add an item into the cart via AJAX.
	 *
	 * @return 	void
	 */
	public function additem()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id_service']  = $input->getUint('id_ser', 0);
		$args['id_employee'] = $input->getInt('id_emp', 0);
		$args['date']        = $input->getString('date', 0);
		$args['hour']        = $input->getUint('hour', 0);
		$args['min']         = $input->getUint('min', 0);
		$args['people']      = $input->getUint('people', 1);
		$args['factor']      = $input->getUint('duration_factor', 1);
		$args['options']     = $input->get('options', array(), 'array');

		// get cart model
		$model = $this->getModel();
		
		// push item into the cart
		$item = $model->addItem($args);

		if (!$item)
		{
			// get error from model
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error);
		}

		// get cart handler
		$cart = $model->getCart();
			
		// get the sum of total cost for each service (of this kind) in the cart 
		$group_cost = VAPCartUtils::getServiceTotalCost($cart->getItemsList(), $args['id_service']);

		$result = new stdClass;
		$result->item       = $item->toArray();
		$result->total      = $cart->getTotalCost();
		$result->totalNet   = $cart->getTotalNet();
		$result->totalTax   = $cart->getTotalTax();
		$result->totalGross = $cart->getTotalGross();
		$result->groupCost  = $group_cost;
		$result->date       = $item->getCheckinDate(JText::translate('DATE_FORMAT_LC2'), 'customer');
		$result->totalsHtml = $this->getCartTotalsHtml($cart);
		
		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * Method used to add an item (with recurrence) into the cart via AJAX.
	 *
	 * @return 	void
	 */
	public function addrecuritem()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id_service']  = $input->getUint('id_ser', 0);
		$args['id_employee'] = $input->getInt('id_emp', 0);
		$args['date']        = $input->getString('date', 0);
		$args['hour']        = $input->getUint('hour', 0);
		$args['min']         = $input->getUint('min', 0);
		$args['people']      = $input->getUint('people', 1);
		$args['factor']      = $input->getUint('duration_factor', 1);
		$args['options']     = $input->get('options', array(), 'array');

		// get recurrence roles
		$rules = $input->getString('recurrence', '');
		list($recurrence['by'], $recurrence['for'], $recurrence['amount']) = explode(',', $rules);

		// get cart model
		$model = $this->getModel();
		
		// push item into the cart
		$items = $model->addRecurringItem($args, $recurrence);

		if (!$items)
		{
			// get error from model
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error);
		}

		// get cart handler
		$cart = $model->getCart();
			
		// get the sum of total cost for each service (of this kind) in the cart 
		$group_cost = VAPCartUtils::getServiceTotalCost($cart->getItemsList(), $args['id_service']);

		$count = 0;

		// count the number of added items
		foreach ($items as $item)
		{
			if ($item['status'])
			{
				$count++;
			}
		}

		$result = new stdClass;
		$result->total      = $cart->getTotalCost();
		$result->totalNet   = $cart->getTotalNet();
		$result->totalTax   = $cart->getTotalTax();
		$result->totalGross = $cart->getTotalGross();
		$result->groupCost  = $group_cost;
		$result->items      = $items;
		$result->count      = $count;
		$result->totalsHtml = $this->getCartTotalsHtml($cart);
		
		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * Method used to remove an item from the cart via AJAX.
	 *
	 * @return 	void
	 */
	public function removeitem()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id_service']  = $input->getUint('id_ser', 0);
		$args['id_employee'] = $input->getInt('id_emp', 0);
		$args['checkin']     = $input->getString('checkin', '');

		// get cart model
		$model = $this->getModel();
		
		// remove item from the cart
		$item = $model->removeItem($args);

		if (!$item)
		{
			// get error from model
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error);
		}

		// get cart handler
		$cart = $model->getCart();
			
		// get the sum of total cost for each service (of this kind) in the cart 
		$group_cost = VAPCartUtils::getServiceTotalCost($cart->getItemsList(), $args['id_service']);

		$result = new stdClass;
		$result->total      = $cart->getTotalCost();
		$result->totalNet   = $cart->getTotalNet();
		$result->totalTax   = $cart->getTotalTax();
		$result->totalGross = $cart->getTotalGross();
		$result->groupCost  = $group_cost;
		$result->totalsHtml = $this->getCartTotalsHtml($cart);

		if ($cart->isEmpty())
		{
			$url = 'index.php?option=com_vikappointments&view=servicesearch&id_service=' . $args['id_service'];

			if ($args['id_employee'] > 0)
			{
				$url .= '&id_employee=' . $args['id_employee'];
			}

			$url .= '&date=' . JDate::getInstance($args['checkin'])->format('Y-m-d');

			$itemid = $input->getUint('Itemid');

			if ($itemid)
			{
				$url .= '&Itemid=' . $itemid;
			}

			// register URL to let javascript redirect the user to the
			// details page of the last deleted service, since the cart
			// is now empty and there's no need to stay in confirmapp
			$result->redirect = JRoute::rewrite($url, false);
		}
		
		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * Method used to add an item option into the cart via AJAX.
	 *
	 * @return 	void
	 */
	public function addoption()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id_option']   = $input->getUint('id_opt', 0);
		$args['id_service']  = $input->getUint('id_ser', 0);
		$args['id_employee'] = $input->getInt('id_emp', 0);
		$args['checkin']     = $input->getString('checkin', '');
		$args['units']       = $input->getString('units', 1);

		// get cart model
		$model = $this->getModel();
		
		// add option into the cart
		$res = $model->addOption($args);

		if (!$res)
		{
			// get error from model
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error);
		}

		// get cart handler
		$cart = $model->getCart();
			
		// get the sum of total cost for each service (of this kind) in the cart 
		$group_cost = VAPCartUtils::getServiceTotalCost($cart->getItemsList(), $args['id_service']);

		// get item details from cart
		$index = $cart->indexOf($args['id_service'], $args['id_employee'], $args['checkin']);
		$item  = $cart->getItemAt($index);

		// get option
		$index  = $item->indexOf($args['id_option']);
		$option = $item->getOptionAt($index);

		$result = new stdClass;
		$result->itemTotal  = $item->getTotalCost();
		$result->total      = $cart->getTotalCost();
		$result->totalNet   = $cart->getTotalNet();
		$result->totalTax   = $cart->getTotalTax();
		$result->totalGross = $cart->getTotalGross();
		$result->groupCost  = $group_cost;
		$result->quantity   = $option->getQuantity();
		$result->totalsHtml = $this->getCartTotalsHtml($cart);
		
		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * Method used to remove an item option from the cart via AJAX.
	 *
	 * @return 	void
	 */
	public function removeoption()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id_option']   = $input->getUint('id_opt', 0);
		$args['id_service']  = $input->getUint('id_ser', 0);
		$args['id_employee'] = $input->getInt('id_emp', 0);
		$args['checkin']     = $input->getString('checkin', '');
		$args['units']       = $input->getString('units', 1);

		// get cart model
		$model = $this->getModel();
		
		// remove option from the cart
		$res = $model->removeOption($args);

		if (!$res)
		{
			// get error from model
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error);
		}

		// get cart handler
		$cart = $model->getCart();
			
		// get the sum of total cost for each service (of this kind) in the cart 
		$group_cost = VAPCartUtils::getServiceTotalCost($cart->getItemsList(), $args['id_service']);

		// get item details from cart
		$index = $cart->indexOf($args['id_service'], $args['id_employee'], $args['checkin']);
		$item  = $cart->getItemAt($index);

		if (($index = $item->indexOf($args['id_option'])) == -1)
		{
			// option not found
			$qty = 0;
		}
		else
		{
			// get remaining quantity
			$qty = $item->getOptionAt($index)->getQuantity();
		}

		$result = new stdClass;
		$result->itemTotal  = $item->getTotalCost();
		$result->total      = $cart->getTotalCost();
		$result->totalNet   = $cart->getTotalNet();
		$result->totalTax   = $cart->getTotalTax();
		$result->totalGross = $cart->getTotalGross();
		$result->groupCost  = $group_cost;
		$result->quantity   = $qty;
		$result->totalsHtml = $this->getCartTotalsHtml($cart);
		
		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * AJAX task to empty the appointments cart.
	 *
	 * @return 	void
	 */
	public function flush()
	{	
		// flush the cart
		$this->getModel()->emptyCart();

		// send response to caller
		$this->sendJSON(1);
	}

	/**
	 * End-point used to redeem the coupon code.
	 *
	 * @return 	void
	 * 
	 * @since 	1.7.3
	 */
	public function redeemcoupon()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// validate form token to prevent brute force attacks
		if (!JSession::checkToken())
		{
			// direct access attempt
			UIErrorFactory::raiseError(500, JText::translate('JINVALID_TOKEN'));
		}
		
		// get coupon key from POST
		$coupon = $input->post->getString('coupon', '');

		// get cart model
		$model = $this->getModel();

		// try to redeem the coupon code
		$res = $model->redeemCoupon($coupon);

		if (!$res)
		{
			// get last error registered by the model
			$error = $model->getError($index = null, $string = true);
			// propagate error or use the default one
			UIErrorFactory::raiseError(500, $error ? $error : JText::translate('VAPCOUPONNOTVALID'));
		}

		$cart = $model->getCart();

		$result = new stdClass;
		$result->cart       = $cart;
		$result->totalsHtml = $this->getCartTotalsHtml($cart);

		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * Fetches the HTML block displaying the cart totals.
	 *
	 * @param 	VAPCart  $cart
	 *
	 * @return 	string
	 */
	protected function getCartTotalsHtml($cart)
	{
		return JLayoutHelper::render('blocks.carttotals', array('cart' => $cart));
	}
}
