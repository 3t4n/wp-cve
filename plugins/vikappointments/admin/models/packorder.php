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
 * VikAppointments package order model.
 *
 * @since 1.7
 */
class VikAppointmentsModelPackorder extends JModelVAP
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

		// get order-package model
		$model = JModelVAP::getInstance('packorderitem');

		if (!empty($data['deletedItems']) && !isset($data['discount']))
		{
			// get total discount of items to remove
			$discount = $model->getTotalDiscount($data['deletedItems']);

			if ($discount > 0)
			{
				// load order details
				$table = $this->getTable();
				$table->load($data['id']);

				// subtract discount of items to remove from the total one
				$data['discount'] = max(array(0, $table->discount - $discount));
			}
		}

		// get order statuses handler
		$orderStatus = VAPOrderStatus::getInstance('package_order');

		$prev_status = null;

		if (!empty($data['status']) && !empty($data['id']))
		{
			// register previous order status
			$prev_status = $orderStatus->getStatus($data['id']);
		}

		// attempt to save the order
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		// always clear order from cache after saving
		VAPLoader::import('libraries.order.factory');
		VAPOrderFactory::changed('package', $id);

		if (!empty($data['deletedItems']))
		{
			// delete specified items, needed to properly apply
			// discount calculation (if requested)
			$model->delete($data['deletedItems']);
		}

		if (!empty($data['items']))
		{
			foreach ((array) $data['items'] as $item)
			{
				// check if we are dealing with a JSON object
				$item = is_string($item) ? json_decode($item, true) : (array) $item;

				if (!empty($item['validthru']))
				{
					// convert valid through date into UTC
					$item['validthru'] = VAPDateHelper::getSqlDateLocale($item['validthru']);
				}

				// make relation with saved order
				$item['id_order'] = $id;

				// save item
				$model->save($item);
			}
		}

		// Check whether the status has changed.
		// Create a new status record also for new reservations
		if (!empty($data['status']) && $data['status'] != $prev_status)
		{
			if (empty($data['status_comment']))
			{
				// use default status comment
				$data['status_comment'] = 'VAP_STATUS_CHANGED_ON_MANAGE';
			}

			// track status change
			$orderStatus->keepTrack($data['status'], $id, $data['status_comment']);
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

		if (!empty($data['notify']))
		{
			// send e-mail notification to customer
			$this->sendEmailNotification($id);
		}
		
		return $id;
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any children
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_package_order_item'))
			->where($dbo->qn('id_order') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($child_id = $dbo->loadColumn())
		{
			// get item model
			$model = JModelVAP::getInstance('packorderitem');
			// delete children
			$model->delete($child_id);
		}

		// load any assigned order statuses
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_order_status'))
			->where($dbo->qn('type') . ' = ' . $dbo->q('package_order'))
			->where($dbo->qn('id_order') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get order status model
			$model = JModelVAP::getInstance('orderstatus');
			// delete relations
			$model->delete($assoc_ids);
		}

		return true;
	}

	/**
	 * Adds a discount to the specified package order.
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

		$dbo = JFactory::getDbo();

		// load any children
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'id_package', 'price', 'quantity')))
			->from($dbo->qn('#__vikappointments_package_order_item'))
			->where($dbo->qn('id_order') . ' = ' . (int) $id)
			->where($dbo->qn('price') . ' > 0');

		$dbo->setQuery($q);
		$items = $dbo->loadObjectList();

		if (!$items)
		{
			// no assigned items
			$this->setError(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));

			return false;
		}

		// load package order details
		$table = $this->getTable();
		$table->load((int) $id);

		// define options for tax calculation
		$options = array(
			'subject' => 'package',
			'lang'    => $table->langtag,
			'id_user' => $table->id_user,
		);

		$total_q = 0;

		// calculate total number of quantity
		foreach ($items as $item)
		{
			$total_q += (int) $item->quantity;
		}

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
			'coupon'     => '',
			'items'      => array(),
		);

		VAPLoader::import('libraries.tax.factory');

		foreach ($items as $i => $item)
		{
			$cost_with_disc = $item->price * $item->quantity;

			if (empty($coupon->percentot) || $coupon->percentot == 1)
			{
				// percentage discount
				$disc_val = round($cost_with_disc * $coupon->value / 100, 2);
			}
			else
			{
				if ($i < count($items) - 1)
				{
					// fixed discount, apply proportionally according to
					// the total number of quantities
					$disc_val = round($coupon->value * $item->quantity / $total_q, 2);
				}
				else
				{
					// We are fetching the last element of the list, instead of calculating the
					// proportional discount, we should subtract the total discount from the coupon
					// value, in order to avoid rounding issues. Let's take as example a coupon of
					// EUR 10 applied on 3 packages. The final result would be 3.33 + 3.33 + 3.33,
					// which won't match the initial discount value of the coupon. With this
					// alternative way, the result would be: 10 - 3.33 - 3.33 = 3.34.
					$disc_val = $coupon->value - $orderData['discount'];
				}
			}

			// increase total discount
			$orderData['discount'] += $disc_val;

			// subtract discount from item cost
			$cost_with_disc -= $disc_val;

			// recalculate totals
			$totals = VAPTaxFactory::calculate($item->id_package, $cost_with_disc, $options);

			// prepare item to save
			$itemData = array(
				'id'            => $item->id,
				'net'           => $totals->net,
				'tax'           => $totals->tax,
				'gross'         => $totals->gross,
				'discount'      => $disc_val,
				'tax_breakdown' => $totals->breakdown,
			);

			// update order totals
			$orderData['total_net']  += $itemData['net'];
			$orderData['total_tax']  += $itemData['tax'];
			$orderData['total_cost'] += $itemData['gross'];

			// append to items list
			$orderData['items'][] = $itemData;
		}

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
	 * Removes discount from the specified package order.
	 *
	 * @param 	integer  $id  The order ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function removeDiscount($id)
	{
		$dbo = JFactory::getDbo();

		// load any children
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'id_package', 'price', 'quantity')))
			->from($dbo->qn('#__vikappointments_package_order_item'))
			->where($dbo->qn('id_order') . ' = ' . (int) $id)
			->where($dbo->qn('price') . ' > 0');

		$dbo->setQuery($q);
		$items = $dbo->loadObjectList();

		if (!$items)
		{
			// no assigned items
			$this->setError(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));

			return false;
		}

		// load package order details
		$table = $this->getTable();
		$table->load((int) $id);

		if ($table->coupon)
		{
			// decode coupon string
			$coupon = explode(';;', $table->coupon);

			// unredeem coupon usage
			JModelVAP::getInstance('coupon')->unredeem($coupon[0]);
		}

		// define options for tax calculation
		$options = array(
			'subject' => 'package',
			'lang'    => $table->langtag,
			'id_user' => $table->id_user,
		);

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
			'coupon'     => '',
			'items'      => array(),
		);

		VAPLoader::import('libraries.tax.factory');

		foreach ($items as $i => $item)
		{
			$cost_no_disc = $item->price * $item->quantity;

			// recalculate totals
			$totals = VAPTaxFactory::calculate($item->id_package, $cost_no_disc, $options);

			// prepare item to save
			$itemData = array(
				'id'            => $item->id,
				'net'           => $totals->net,
				'tax'           => $totals->tax,
				'gross'         => $totals->gross,
				'discount'      => 0,
				'tax_breakdown' => $totals->breakdown,
			);

			// update order totals
			$orderData['total_net']  += $itemData['net'];
			$orderData['total_tax']  += $itemData['tax'];
			$orderData['total_cost'] += $itemData['gross'];

			// append to items list
			$orderData['items'][] = $itemData;
		}

		// update order details
		return $this->save($orderData);
	}

	/**
	 * Sends an e-mail notification to the customer of the
	 * specified order.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	array 	 $options  An array of options.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function sendEmailNotification($id, array $options = array())
	{
		VAPLoader::import('libraries.mail.factory');

		// fetch receiver alias
		$client = isset($options['client']) ? $options['client'] : 'package';

		try
		{
			// instantiate mail
			$mail = VAPMailFactory::getInstance($client, $id, $options);
		}
		catch (Exception $e)
		{
			// probably order not found, register error message
			$this->setError($e->getMessage());

			return false;
		}

		// in case the "check" attribute is set, we need to make
		// sure whether the specified client should receive the
		// e-mail according to the configuration rules
		if (!empty($options['check']) && !$mail->shouldSend())
		{
			// configured to avoid receiving this kind of e-mails
			return false;
		}

		// send notification
		return $mail->send();
	}

	/**
	 * Counts the number of services within the purchased packages that the
	 * specified customer/user is still able to use.
	 *
	 * @param 	integer  $id_service  The service ID.
	 * @param 	integer  $id_user     The user ID. If not provided, 
	 * 								  the current user will be retrieved.
	 *
	 * @return 	integer  The remaining number of services.
	 */
	public function countRemaining($id_service = null, $id_user = null)
	{
		$dbo = JFactory::getDbo();

		if (!$id_user || $id_user == -1)
		{
			$user = JFactory::getUser();

			if ($user->guest)
			{
				return 0;
			}

			$id_user = $user->id;

			// make relation with Joomla ID
			$user_column = 'u.jid';
		}
		else
		{
			// make relation using customer ID
			$user_column = 'o.id_user';
		}

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1)); 

		$q = $dbo->getQuery(true)
			->select(sprintf('SUM(%s - %s) AS %s', $dbo->qn('i.num_app'), $dbo->qn('i.used_app'), $dbo->qn('count')))
			->from($dbo->qn('#__vikappointments_package_order', 'o'))
			->innerjoin($dbo->qn('#__vikappointments_package_order_item', 'i') . ' ON ' . $dbo->qn('i.id_order') . ' = ' . $dbo->qn('o.id'))
			->leftjoin($dbo->qn('#__vikappointments_users', 'u') . ' ON ' . $dbo->qn('o.id_user') . ' = ' . $dbo->qn('u.id'))
			->where($dbo->qn($user_column) . ' = ' . $id_user);

		if ($id_service)
		{
			// filter by service
			$q->leftjoin($dbo->qn('#__vikappointments_package_service', 'a') . ' ON ' . $dbo->qn('a.id_package') . ' = ' . $dbo->qn('i.id_package'));
			$q->where($dbo->qn('a.id_service') . ' = ' . (int) $id_service);
		}

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		/**
		 * Ignore the expired packages.
		 * 
		 * @since 1.7.4
		 */
		$q->andWhere([
			$dbo->qn('i.validthru') . ' IS NULL',
			$dbo->qn('i.validthru') . ' = ' . $dbo->q($dbo->getNullDate()),
			$dbo->qn('i.validthru') . ' >= ' . $dbo->q(JFactory::getDate()->toSql()),
		], 'OR');

		/**
		 * Applies additional restrictions while counting the remaining number of packages
		 * that can be redeemed for the specified service.
		 *
		 * @param   string  &$query      The database query used to count the remaining packages.
		 * @param   int     $id_service  The ID of the service that should be redeemed.
		 *
		 * @return 	void
		 * 
		 * @since 	1.7.4
		 */
		VAPFactory::getEventDispatcher()->trigger('onCountRemainingPackages', [&$q, $id_service]);

		$dbo->setQuery($q);

		// return the remaining number of packages (cannot be lower than 0)
		return max(array(0, (int) $dbo->loadResult()));
	}

	/**
	 * Registers all the packages that have been used to purchase a service.
	 * 
	 * @param 	mixed    $order     Either the order details instance or an ID.
	 * @param 	boolean  $increase 	True to increase the number of used packages,
	 * 								false to free them.
	 *
	 * @return 	integer  The number of packages redeemed/unreedemed.
	 */
	public function usePackages($order, $increase = true)
	{
		$dbo = JFactory::getDbo();

		if (is_numeric($order))
		{
			try
			{
				// get order details
				VAPLoader::import('libraries.order.factory');
				$order = VAPOrderFactory::getAppointments($order);
			}
			catch (Exception $e)
			{
				// order not found
				return 0;
			}
		}

		if ($order->id_user <= 0)
		{
			// the owner of the order is not registered
			return 0;
		}

		// count the total number of guests for each service in the list
		$count_map = array();

		foreach ($order->appointments as $app)
		{
			$id_ser = $app->service->id;

			if (!array_key_exists($id_ser, $count_map))
			{
				$count_map[$id_ser] = 0;
			}

			$count_map[$id_ser] += $app->people;
		}

		$reedemed = 0;

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1));

		// get package order item model
		$itemModel = JModelVAP::getInstance('packorderitem');

		$dispatcher = VAPFactory::getEventDispatcher();

		// iterate the map
		foreach ($count_map as $id_ser => $count)
		{
			// get all the packages that can be redeemed for the service/user pair
			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('i.id', 'i.num_app', 'i.used_app')))
				->from($dbo->qn('#__vikappointments_package_order', 'o'))
				->innerjoin($dbo->qn('#__vikappointments_package_order_item', 'i') . ' ON ' . $dbo->qn('i.id_order') . ' = ' . $dbo->qn('o.id'))
				->leftjoin($dbo->qn('#__vikappointments_package_service', 'a') . ' ON ' . $dbo->qn('a.id_package') . ' = ' . $dbo->qn('i.id_package'))
				->where(array(
					$dbo->qn('o.id_user') . ' = ' . (int) $order->id_user,
					$dbo->qn('a.id_service') . ' = ' . (int) $id_ser,
				));

			if ($approved)
			{
				// filter by approved status
				$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
			}

			if ($increase)
			{
				// restrict number of used apps only if we are increasing
				$q->where($dbo->qn('i.used_app') . ' < ' . $dbo->qn('i.num_app'));
			}

			/**
			 * Ignore the expired packages.
			 * 
			 * @since 1.7.4
			 */
			$q->andWhere([
				$dbo->qn('i.validthru') . ' IS NULL',
				$dbo->qn('i.validthru') . ' = ' . $dbo->q($dbo->getNullDate()),
				$dbo->qn('i.validthru') . ' >= ' . $dbo->q(JFactory::getDate()->toSql()),
			], 'OR');

			/**
			 * Applies additional restrictions while counting the remaining number of packages
			 * that can be redeemed for the specified service.
			 *
			 * @param   string  &$query      The database query used to count the remaining packages.
			 * @param   int     $id_service  The ID of the service that should be redeemed.
			 *
			 * @return 	void
			 * 
			 * @since 	1.7.4
			 */
			$dispatcher->trigger('onCountRemainingPackages', [&$q, $id_ser]);

			$dbo->setQuery($q);
			
			$rows = $dbo->loadObjectList();

			$i = 0;
			// iterate until the total number of services is redeemed,
			// or at least until we reach the end of the array
			while ($count > 0 && $i < count($rows))
			{
				$r = $rows[$i];

				/**
				 * Evaluates if we have to increase or decrease the number
				 * of used packages.
				 *
				 * @since 1.6.3
				 */
				if ($increase)
				{
					// Get the number of packages to redeem.
					// Obtain the minimum value between the total services and the remaining packages.
					$used = min(array($count, $r->num_app - $r->used_app));

					// increase used packages
					$r->used_app += $used;
				}
				else
				{
					// Get the number of packages to redeem.
					// Obtain the minimum value between the total services and the number of used packages.
					$used = min(array($count, $r->used_app));

					// decrease used packages
					$r->used_app -= $used;
				}

				// update the record by changing the total number of units used
				$itemModel->save($r);

				// decrease the services count by the number of used packages
				$count -= $used;
				$i++;

				// increase the total number of redeemed packages
				$reedemed += $used;
			}
		}

		return $reedemed;
	}
}
