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

/**
 * VikAppointments subscription order model.
 *
 * @since 1.7
 */
class VikAppointmentsModelSubscrorder extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		$new_is_approved = $old_is_approved = false;

		if (!empty($data['status']))
		{
			// check whether the specified status is an approval
			$new_is_approved = JHtml::fetch('vaphtml.status.isapproved', 'subscriptions', $data['status']);

			if (!empty($data['id']))
			{
				// we are doing an update, retrieve previous order status
				$table = $this->getTable();
				$table->load($data['id']);

				// check whether the previous status was an approval too
				$old_is_approved = JHtml::fetch('vaphtml.status.isapproved', 'subscriptions', $table->status);

				if (!isset($data['id_employee']))
				{
					// register previous employee ID, needed to extend a license
					$data['id_employee'] = $table->id_employee;
				}

				if (!isset($data['id_user']))
				{
					// register previous user ID, needed to extend a license
					$data['id_user'] = $table->id_user;
				}

				if (!isset($data['id_subscr']))
				{
					// register previous subscription ID, needed to extend a license
					$data['id_subscr'] = $table->id_subscr;
				}
			}
		}

		if (!isset($data['payment_charge']) && isset($data['id_payment']))
		{
			if ($data['id_payment'] > 0)
			{
				// missing payment charge, get payment table
				$payTable = JModelVAP::getInstance('payment')->getTable();

				// attempt to load the payment details
				if ($payTable->load($data['id_payment']))
				{
					$data['payment_charge'] = (float) $payTable->charge;
				}
			}
			else
			{
				// empty payment, unset charge
				$data['payment_charge'] = 0;
			}
		}

		// attempt to save the record
		$id = parent::save($data);

		if (!$id)
		{
			// something went wrong
			return false;
		}

		// always clear order from cache after saving
		VAPLoader::import('libraries.order.factory');
		VAPOrderFactory::changed('subscr', $id);
		VAPOrderFactory::changed('empsubscr', $id);

		// extend subscription only in case the new status is an approval
		// and the previous one wasn't
		if ($new_is_approved && !$old_is_approved && !empty($data['id_subscr']))
		{
			if (!empty($data['id_user']))
			{
				// extend customer license by the subscription
				$this->extendCustomer($data['id_user'], $data['id_subscr']);
			}
			else if (!empty($data['id_employee']))
			{
				// extend employee license by the subscription
				$this->extendEmployee($data['id_employee'], $data['id_subscr']);
			}
		}

		// check whether we should apply or delete a discount
		if (!empty($data['add_discount']))
		{
			$this->addDiscount($id, $data['add_discount']);
		}
		else if (!empty($data['remove_discount']))
		{
			$this->removeDiscount($id);
		}

		return $id;
	}

	/**
	 * Extends the expiration date of the specified customer by
	 * the duration of the given subscription.
	 *
	 * @param 	mixed    $customer      Either a customer object or its ID.
	 * @param 	mixed    $subscription  Either a subscription object or its ID.
	 * @param 	boolean  $message       True to enqueue a message, false to ignore it.
	 *
	 * @return 	mixed    The resulting date on success, false otherwise.
	 */
	public function extendCustomer($customer, $subscription, $message = true)
	{
		// get customer model for later use
		$customerModel = JModelVAP::getInstance('customer');

		// check if we have an ID
		if (is_numeric($customer))
		{
			// get customer details
			$customer = $customerModel->getItem((int) $customer);
			
			if (!$customer)
			{
				// customer not found
				return false;
			}
		}

		// always cast to object
		$customer = (object) $customer;

		if ($customer->lifetime)
		{
			// the customer owns a lifetime subscription,
			// we don't need to update its expiration
			return false;
		}

		$to  = 0;
		$str = "";

		// get current time
		$now = JDate::getInstance()->toSql();

		if (VAPDateHelper::isNull($customer->active_to_date) || $customer->active_to_date < $now)
		{
			// the customer was pending or expired, renew from now on
			$customer->active_to_date = $now;
		}

		// get subscription model
		$subscrModel = JModelVAP::getInstance('subscription');

		// extend date by the duration of the given subscription
		$customer->active_to_date = $subscrModel->extend($customer->active_to_date, $subscription);

		if ($customer->active_to_date === false)
		{
			// something went wrong...
			return false;
		}

		// prepare data to save
		$data = array(
			'id'             => $customer->id,
			'lifetime'       => $customer->active_to_date == -1 ? 1 : 0,
			'active_to_date' => $customer->active_to_date == -1 ? null : $customer->active_to_date,
		);

		if (VAPDateHelper::isNull($customer->active_since))
		{
			// register first activation date
			$data['active_since'] = $now;
		}

		// update customer record
		$customerModel->save($data);

		// check if we should enqueue a successful message
		if ($message)
		{
			$app = JFactory::getApplication();

			if ($data['lifetime'])
			{
				/**
				 * Get LIFETIME text based on the current application client.
				 *
				 * @since 1.6.1
				 */
				if ($app->isClient('site'))
				{
					$str = JText::translate('VAPACCOUNTVALIDTHRU1');
				}
				else
				{
					$str = JText::translate('VAPSUBSCRTYPE5');
				}
			}
			else
			{
				$config = VAPFactory::getConfig();

				// format expiration date
				$str = JHtml::fetch('date', $customer->active_to_date, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'));
			}

			/**
			 * Display the message based on the current application client.
			 *
			 * @since 1.6.1
			 */
			if ($app->isClient('site'))
			{
				$str = JText::sprintf('VAPSUBSCRIPTIONEXTENDED_SITE', $str);
			}
			else
			{
				$str = JText::sprintf('VAPSUBSCRIPTIONEXTENDED', $customer->billing_name, $str);
			}

			// enqueue message
			$app->enqueueMessage($str);
		}

		return $customer->active_to_date;
	}

	/**
	 * Extends the expiration date of the specified employee by
	 * the duration of the given subscription.
	 *
	 * @param 	mixed    $employee      Either an employee object or its ID.
	 * @param 	mixed    $subscription  Either a subscription object or its ID.
	 * @param 	boolean  $message       True to enqueue a message, false to ignore it.
	 *
	 * @return 	mixed    The resulting date on success, false otherwise.
	 */
	public function extendEmployee($employee, $subscription, $message = true)
	{
		// get employee model for later use
		$employeeModel = JModelVAP::getInstance('employee');

		// check if we have an ID
		if (is_numeric($employee))
		{
			// get employee details
			$employee = $employeeModel->getItem($employee);
			
			if (!$employee)
			{
				// employee not found
				return false;
			}
		}

		// always cast to object
		$employee = (object) $employee;

		if ($employee->active_to == -1)
		{
			// the employee owns a lifetime subscription,
			// we don't need to update its expiration
			return false;
		}

		$to  = 0;
		$str = "";

		// get current time
		$now = JDate::getInstance()->toSql();

		if ($employee->active_to == 0 || $employee->active_to_date < $now)
		{
			// the employee was pending or expired, renew from now on
			$employee->active_to_date = $now;
		}

		// get subscription model
		$subscrModel = JModelVAP::getInstance('subscription');

		// extend date by the duration of the given subscription
		$employee->active_to_date = $subscrModel->extend($employee->active_to_date, $subscription);

		if ($employee->active_to_date === false)
		{
			// something went wrong...
			return false;
		}

		// prepare data to save
		$data = array(
			'id'             => $employee->id,
			'active_to'      => $employee->active_to_date == -1 ? -1 : 1,
			'active_to_date' => $employee->active_to_date,
			'listable'       => 1,
		);

		if (VAPDateHelper::isNull($employee->active_since))
		{
			// register first activation date
			$data['active_since'] = $now;
		}

		// update employee record
		$employeeModel->save($data);

		// check if we should enqueue a successful message
		if ($message)
		{
			$app = JFactory::getApplication();

			if ($employee->active_to_date == -1)
			{
				/**
				 * Get LIFETIME text based on the current application client.
				 *
				 * @since 1.6.1
				 */
				if ($app->isClient('site'))
				{
					$str = JText::translate('VAPACCOUNTVALIDTHRU1');
				}
				else
				{
					$str = JText::translate('VAPSUBSCRTYPE5');
				}
			}
			else
			{
				$config = VAPFactory::getConfig();

				// format expiration date
				$str = JHtml::fetch('date', $employee->active_to_date, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'));
			}

			/**
			 * Display the message based on the current application client.
			 *
			 * @since 1.6.1
			 */
			if ($app->isClient('site'))
			{
				$str = JText::sprintf('VAPSUBSCRIPTIONEXTENDED_SITE', $str);
			}
			else
			{
				$str = JText::sprintf('VAPSUBSCRIPTIONEXTENDED', $employee->nickname, $str);
			}

			// enqueue message
			$app->enqueueMessage($str);
		}

		return $employee->active_to_date;
	}

	/**
	 * Adds a discount to the specified subscription order.
	 *
	 * @param 	integer  $id      The order ID.
	 * @param 	mixed    $coupon  Either a coupon code or an array/object
	 *                            containing its details.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function addDiscount($id, $coupon)
	{
		// get coupon model
		$couponModel = JModelVAP::getInstance('coupon');

		if (is_string($coupon))
		{
			// get coupon code details
			$coupon = $couponModel->getCoupon($coupon);
		}
		else
		{
			// treat as object
			$coupon = (object) $coupon;
		}

		// make sure we have a valid coupon code
		if (!$coupon || empty($coupon->value))
		{
			// invalid/missing coupon
			$this->setError('Missing coupon code');

			return false;
		}

		// load subscription order details
		$table = $this->getTable();
		$table->load((int) $id);

		// get subscription cost
		$subTable = JModelVAP::getInstance('subscription')->getTable();
		$subTable->load($table->id_subscr);

		// define options for tax calculation
		$options = array(
			'subject'     => 'subscription',
			'id_employee' => $table->id_employee,
		);

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
			'coupon'     => '',
		);

		VAPLoader::import('libraries.tax.factory');

		if (empty($coupon->percentot) || $coupon->percentot == 1)
		{
			// percentage discount
			$orderData['discount'] = round($subTable->price * $coupon->value / 100, 2);
		}
		else
		{
			// fixed discount
			$orderData['discount'] = $coupon->value;
		}

		// recalculate totals
		$totals = VAPTaxFactory::calculate($table->id_subscr, $subTable->price - $orderData['discount'], $options);

		// update order totals
		$orderData['total_net']  += $totals->net;
		$orderData['total_tax']  += $totals->tax;
		$orderData['total_cost'] += $totals->gross;

		if (!empty($coupon->code))
		{
			// save coupon data
			$orderData['coupon'] = $coupon;

			// redeem coupon usage
			$couponModel->redeem($coupon);
		}

		// update order details
		return $this->save($orderData);
	}

	/**
	 * Removes discount from the specified subscription order.
	 *
	 * @param 	integer  $id  The order ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function removeDiscount($id)
	{
		// load subscription order details
		$table = $this->getTable();
		$table->load((int) $id);

		if ($table->coupon)
		{
			// decode coupon string
			$coupon = explode(';;', $table->coupon);

			// unredeem coupon usage
			JModelVAP::getInstance('coupon')->unredeem($coupon[0]);
		}

		// get subscription cost
		$subTable = JModelVAP::getInstance('subscription')->getTable();
		$subTable->load($table->id_subscr);

		// define options for tax calculation
		$options = array(
			'subject'     => 'subscription',
			'id_employee' => $table->id_employee,
		);

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
			'coupon'     => '',
		);

		VAPLoader::import('libraries.tax.factory');

		// recalculate totals
		$totals = VAPTaxFactory::calculate($table->id_subscr, $subTable->price, $options);

		// update order totals
		$orderData['total_net']  += $totals->net;
		$orderData['total_tax']  += $totals->tax;
		$orderData['total_cost'] += $totals->gross;

		// update order details
		return $this->save($orderData);
	}
}
