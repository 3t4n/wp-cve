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
 * VikAppointments packages controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerPackages extends VAPControllerAdmin
{
	/**
	 * Method used to add an item into the cart via AJAX.
	 *
	 * @return 	void
	 */
	public function addcart()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id']    = $input->getUint('id_package', 0);
		$args['units'] = $input->getUint('units', 1);

		// get cart model
		$model = $this->getModel('packagescart');
		
		// push item into the cart
		$item = $model->addItem($args);

		if (!$item)
		{
			// get error from model
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error);
		}

		$result = new stdClass;
		$result->item      = $item->toArray();
		$result->totalCost = $model->getCart()->getTotalCost();
		
		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * Method used to remove an item from the cart via AJAX.
	 *
	 * @return 	void
	 */
	public function removecart()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id']    = $input->getUint('id_package', 0);
		$args['units'] = $input->getUint('units', 1);

		// get cart model
		$model = $this->getModel('packagescart');
		
		// remove item from the cart
		$res = $model->removeItem($args);

		if (!$res)
		{
			// get error from model
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error);
		}

		// get cart handler
		$cart = $model->getCart();

		$result = new stdClass;
		$result->idPackage = $args['id'];
		$result->isEmpty   = $cart->isEmpty();
		$result->totalCost = $cart->getTotalCost();

		// check whether the quantity of the package is still higher than 0
		$index = $cart->indexOf($args['id']);

		if ($index != -1)
		{
			$result->item = $cart->getPackageAt($index)->toArray();
		}
		else
		{
			$result->item = false;
		}
		
		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * AJAX task to empty the appointments cart.
	 *
	 * @return 	void
	 */
	public function emptycart()
	{	
		// flush the cart
		$this->getModel('packagescart')->emptyCart();

		// send response to caller
		$this->sendJSON(1);
	}
}
