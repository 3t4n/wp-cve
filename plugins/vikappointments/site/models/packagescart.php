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
// load cart framework
VikAppointments::loadCartPackagesLibrary();

/**
 * VikAppointments packages cart model.
 *
 * @since 1.7
 */
class VikAppointmentsModelPackagescart extends JModelVAP
{
	/**
	 * Registers a new package within the cart.
	 *
	 * @param 	mixed  $data  Either an array or an object holding
	 *                        the package details.
	 *
	 * @return 	mixed  The added item on success, false otherwise.
	 */
	public function addItem($data)
	{
		$data = (array) $data;

		$dispatcher = VAPFactory::getEventDispatcher();

		// load package details
		$package = JModelVAP::getInstance('package')->getItem($data['id']);

		if (!$package)
		{
			// the specified package doesn't exist
			$this->setError(JText::translate('VAPPACKNOTFOUNDERR'));
			return false;
		}

		// get cart instance
		$cart = $this->getCart();

		// create cart item instance
		$item = new VAPCartPackagesItem($package->id, $package->name, $package->price, $package->num_app);

		// junk variable used by plugins to set custom errors
		$err = '';

		/**
		 * Trigger event before adding an item into the cart.
		 *
		 * @param 	mixed 	 $cart 	The cart instance.
		 * @param 	mixed 	 $item  The cart item object.
		 * @param 	string 	 &$err 	String used to raise custom errors.
		 *
		 * @return 	boolean  False to avoid adding the item.
		 *
		 * @since 	1.7
		 */
		if ($dispatcher->not('onAddPackageItemCart', array($cart, $item, &$err)))
		{
			// Avoid pushing the item into the cart in case at least a plugin
			// returns a negative value. If no plugin is attached to this event,
			// the item will be added correctly.
			$this->setError($err ? $err : JText::translate('ERROR'));
			return false;
		}
		
		// try to add the item into the cart
		$res = $cart->addPackage($item);
		
		// check package integrity on cart
		if (!$res)
		{
			// we probably reached the maximum cart limit
			$this->setError(JText::translate('VAPCARTPACKADDERR'));
			return false;
		}
		
		// save cart data
		$cart->store();

		// search the item inside the cart because the element might have been
		// updated an existing record
		return $cart->getPackageAt($cart->indexOf($item->getID()));
	}

	/**
	 * Decreases the units of the specified package.
	 *
	 * @param 	mixed    $data  Either an array or an object holding
	 *                          the package details.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function removeItem($data)
	{
		$data = (array) $data;

		$dispatcher = VAPFactory::getEventDispatcher();

		// get cart handler
		$cart = $this->getCart();

		/**
		 * Trigger event before detaching a package from the cart.
		 *
		 * @param 	mixed    $cart        The cart instance.
		 * @param 	integer  $id_package  The package ID.
		 *
		 * @return 	boolean  False to avoid detaching the option.
		 *
		 * @since 	1.7
		 */
		if ($dispatcher->not('onRemovePackageItemCart', array($cart, $data['id'])))
		{
			// Avoid detaching the option from the item in case at least a plugin
			// returns a negative value. If no plugin is attached to this event,
			// the option will be detached correctly.

			$this->setError(JText::translate('VAPCARTOPTDELERR'));
			return false;
		}

		// validate units to decrease
		$units = isset($data['units']) ? $data['units'] : 1;
		// try to delete the option
		$res = $cart->removePackage($data['id'], $units);

		if (!$res)
		{
			// unable to delete the package
			$this->setError(JText::translate('VAPPACKNOTFOUNDERR'));
			return false;
		}

		// revalidate coupon code
		$this->revalidateCoupon();

		// save changes
		$cart->store();

		return true;
	}

	/**
	 * Removes all the packages from the cart.
	 *
	 * @return 	void
	 */
	public function emptyCart()
	{
		$dispatcher = VAPFactory::getEventDispatcher();
		
		// get cart handler
		$cart = $this->getCart();

		/**
		 * Trigger event before flushing the cart.
		 *
		 * @param 	mixed  $cart The cart instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->not('onEmptyPackagesCart', array($cart));
		
		// flush the cart
		$cart->emptyCart();

		// revalidate coupon code
		$this->revalidateCoupon();
		
		// apply the changes
		$cart->store();
	}

	/**
	 * Helper method used to redeem the specified coupon code.
	 *
	 * @param 	mixed 	 $coupon  Either the coupon details or its code.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function redeemCoupon($coupon)
	{
		if (empty($coupon))
		{
			// coupon code not specified
			$this->setError(JText::translate('VAPCOUPONNOTVALID'));
			return false;
		}

		if (is_string($coupon))
		{
			// get model to load coupon details
			$couponModel = JModelVAP::getInstance('coupon');
			$coupon = $couponModel->getCoupon($coupon);

			if (!$coupon)
			{
				// coupon not found in database
				$this->setError(JText::translate('VAPCOUPONNOTVALID'));
				return false;
			}
		}

		// get cart instance
		$cart = $this->getCart();

		// validate the coupon code
		if (!VikAppointments::validatePackagesCoupon($coupon, $cart))
		{
			// cannot apply the coupon code
			$this->setError(JText::translate('VAPCOUPONNOTVALID'));
			return false;
		}

		// coupon valid, create discount object
		$discount = new VAPCartDiscount('coupon', $coupon->value, $coupon->percentot == 1);
		// register coupon data for later use
		$discount->set('couponData', $coupon);

		// apply discount, by replacing any other coupon discount previously set
		$cart->setDiscount($discount);
		// commit cart changes
		$cart->store();

		return true;
	}

	/**
	 * Revalidates the internal coupon code, since the cart might be no more
	 * compliant with the coupon restrictions after some changes.
	 *
	 * @param 	boolean  $store  True to commit the changes.
	 *
	 * @return 	boolean  True in case of valid coupon, false otherwise.
	 */
	public function revalidateCoupon($store = false)
	{
		// get cart instance
		$cart = $this->getCart();
		// get coupon discount, if any
		$discount = $cart->getDiscount('coupon');

		if (!$discount)
		{
			// coupon discount not set
			return false;
		}

		// extract coupon data
		$coupon = $discount->get('couponData');

		// try to redeem the coupon code
		$res = $this->redeemCoupon($coupon);

		if (!$res)
		{
			// coupon no more valid, unset it
			$cart->removeDiscount($discount);

			if ($store)
			{
				// commit changes
				$cart->store();
			}
			
			return false;
		}

		// coupon still valid
		return true;
	}

	/**
	 * Helper method used to obtain an instance of the cart.
	 *
	 * @return 	VAPCartPackages
	 */
	public function getCart()
	{
		static $cart = null;

		if (!$cart)
		{
			// load cart instance
			$cart = VAPCartPackages::getInstance();

			$config = VAPFactory::getConfig();

			// set cart configuration
			$cart->setParams(array( 
				VAPCartPackages::MAX_SIZE => $config->getInt('maxpackscart'),
			));
		}

		return $cart;
	}
}
