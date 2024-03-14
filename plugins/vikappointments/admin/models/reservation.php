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
 * VikAppointments appointment model.
 *
 * @since 1.7
 */
class VikAppointmentsModelReservation extends JModelVAP
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
		$dbo   = JFactory::getDbo();
		$app   = JFactory::getApplication();
		$table = $this->getTable();

		$data = (array) $data;

		if (empty($data['id']) && !empty($data['icaluid']))
		{
			// search reservation by iCal UID
			$tmp = $this->getItem(array('icaluid' => $data['icaluid']));

			if ($tmp)
			{
				// reservation found, do update
				$data['id'] = $tmp->id;
			}
		}

		if (!empty($data['validate_availability']))
		{
			// validate reservation availability
			if (!$this->isAvailable($data))
			{
				// The selected slot doesn't seem to be available...
				// Register data within the user state before aborting.
				$app->setUserState('vap.reservation.data', $data);

				return false;
			}
		}

		if (!empty($data['id']))
		{
			// register current datetime as modified date, if not specified
			if (!isset($data['modifiedon']))
			{
				$data['modifiedon'] = JFactory::getDate()->toSql();
			}

			// load order details
			$table->load($data['id']);
		}

		/**
		 * When fetching the statistics, we may have to convert the check-in time to
		 * the offset of the assigned employee. Since SQL engines do not provide default
		 * tools, we need to always keep up-to-date the offset of the check-in in order
		 * to adjust the dates at runtime without having to care of DST issues.
		 *
		 * For this reason, every time the check-in and the employee are provided, we 
		 * have to refresh the timezone offset of the check-in.
		 */
		if (!empty($data['id_employee']) && !empty($data['checkin_ts']) && !VAPDateHelper::isNull($data['checkin_ts']))
		{
			// get employee timezone
			$tz = JModelVAP::getInstance('employee')->getTimezone($data['id_employee']);
			// create check-in date
			$checkin = new JDate($data['checkin_ts'], $tz);
			// get timezone offset for the selected check-in date time
			$data['tz_offset'] = $checkin->format('P', $local = true);
		}

		// get reservation-option model
		$model = JModelVAP::getInstance('resoptassoc');

		if (!empty($data['deletedOptions']) && !isset($data['discount']))
		{
			// get total discount of items to remove
			$discount = $model->getTotalDiscount($data['deletedOptions']);

			if ($discount > 0)
			{
				// subtract discount of items to remove from the total one
				$data['discount'] = max(array(0, $table->discount - $discount));
			}
		}

		if (empty($data['id']) && !isset($data['view_emp']) && !empty($data['id_service']))
		{
			// use only positive values, so that we can avoid a query in case of ID equals to -1
			$id_service = $data['id_service'] > 0 ? $data['id_service'] : 0;

			// while creating a new record check whether we should display
			// the assigned employee to the customer
			$service = JModelVAP::getInstance('service')->getItem($id_service);

			if ($service)
			{
				// display employee in case the service allows its selection
				$data['view_emp'] = (int) $service->choose_emp;
			}
		}

		// always recover the service sleep time (if not specified) while creating a new appointment
		if (!isset($data['sleep']) && empty($data['id']) && !empty($data['id_service']) && !empty($data['id_employee']))
		{
			// load employee overrides
			$service = JModelVAP::getInstance('serempassoc')->getOverrides($data['id_service'], $data['id_employee']);

			if ($service)
			{
				// use default service sleep time
				$data['sleep'] = $service->sleep;
			}
		}

		if (empty($data['id']) && empty($data['status']))
		{
			// status not specified, use the default confirmed one
			$data['status'] = JHtml::fetch('vaphtml.status.confirmed', 'appointments', 'code');
		}

		// get order statuses handler
		$orderStatus = VAPOrderStatus::getInstance();

		$prev_status = null;

		if (!empty($data['status']) && !empty($data['id']))
		{
			// register previous order status
			$prev_status = $orderStatus->getStatus($data['id']);
		}

		// attempt to save the reservation
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		// always clear order from cache after saving
		VAPLoader::import('libraries.order.factory');
		VAPOrderFactory::changed('appointment', $id);

		if (empty($data['id']) && !isset($data['id_parent']))
		{
			// we are creating a new single reservation, so we need
			// to update the record to link the parent id with the PK
			$tmp = new stdClass;
			$tmp->id = $id;
			$tmp->id_parent = $id;

			$dbo->updateObject('#__vikappointments_reservation', $tmp, 'id');
		}

		if (!empty($data['deletedOptions']))
		{
			// delete specified options, needed to properly
			// apply discount calculation (if requested)
			$model->delete($data['deletedOptions']);
		}

		if (!empty($data['options']))
		{
			foreach ((array) $data['options'] as $item)
			{
				// check if we are dealing with a JSON object
				$item = is_string($item) ? json_decode($item, true) : (array) $item;
				// make relation with saved order
				$item['id_reservation'] = $id;

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

			// check if we have an existing parent order
			if ($table->id && $table->id_parent <= 0)
			{
				// get multi-order model
				$multiOrderModel = JModelVAP::getInstance('multiorder');

				// iterate orders found
				foreach ($multiOrderModel->getChildren($table->id, 'id') as $order_id)
				{
					// prepare data to save for child record
					$childData = array(
						'id'             => (int) $order_id,
						'status'         => $data['status'],
						'status_comment' => $data['status_comment'],
					);

					// save on cascade
					$this->save($childData);
				}
			}
			else
			{
				// check whether the order was previously approved
				$was_approved = JHtml::fetch('vaphtml.status.isapproved', 'appointments', $prev_status);
				// check whether the order has been cancelled
				$is_now_cancelled = JHtml::fetch('vaphtml.status.iscancelled', 'appointments', $data['status']);

				if ($is_now_cancelled && $was_approved)
				{
					/**
					 * Try to unredeem a package if the order has been cancelled.
					 * The service cost must be zero too in order to prove that a package was redeemed.
					 *
					 * @since 1.6.3
					 */
					if ($table->service_price == 0)
					{
						// get package model
						$package = JModelVAP::getInstance('packorder');

						// unredeem packages
						$unredeemed = $package->usePackages($data['id'], $increase = false);

						if ($unredeemed)
						{
							if ($app->isClient('administrator'))
							{
								// message for back-end
								$app->enqueueMessage(JText::sprintf('VAPORDERUNREDEEMEDPACKS', $unredeemed));
							}
							else
							{
								// message for front-end
								$app->enqueueMessage(JText::translate('VAPRESTOREPACKSONCANCEL'), 'notice');
							}
						}
					}

					// Check whether the appointment was paid and it is now cancelled.
					// In case of a multi-order, the total amount will be summed recursively by each
					// appointment assigned to the order, since the prices totals are proportional.
					if ($table->id_user > 0 && $table->total_cost && $is_now_cancelled && JHtml::fetch('vaphtml.status.ispaid', 'appointments', $prev_status))
					{
						// Remove the payment charge from the total paid.
						// Ignore the charge if it is a discount.
						$credit = $table->total_cost - max(array($table->payment_charge + $table->payment_tax, 0));

						// increase user credit by the amount paid, if any
						JModelVAP::getInstance('customer')->addCredit($table->id_user, $credit);
					}
				}
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

		if (!empty($data['notifycust']))
		{
			// define options
			$options = array(
				'id'      => isset($data['mail_custom_text']) ? $data['mail_custom_text'] : null,
				'default' => isset($data['exclude_default_mail_texts']) ? !$data['exclude_default_mail_texts'] : null,
			);

			// send e-mail notification to customer
			$this->sendEmailNotification($id, $options);
		}

		if (!empty($data['notifyemp']))
		{
			// send e-mail notification to employee
			$this->sendEmailNotification($id, array('client' => 'employee'));
		}

		if (!empty($data['notifywl']) && !empty($data['status']))
		{
			// check whether we have a cancelled status
			if (JHtml::fetch('vaphtml.status.iscancelled', 'appointments', $data['status']))
			{
				// process waiting list queue
				JModelVAP::getInstance('waitinglist')->notify($id);
			}
		}

		if (!empty($data['notes']))
		{
			// create new user notes for this appointment
			JModelVAP::getInstance('usernote')->save(array(
				'group'     => 'appointments',
				'id_parent' => $id,
				'content'   => $data['notes'],
				'id'        => isset($data['id_notes']) ? (int) $data['id_notes'] : 0,
			));
		}

		// check if we have an existing child appointment
		if ($table->id && $this->isChildAppointment($table))
		{
			// check whether the total cost has changed
			if (isset($data['total_cost']) && $data['total_cost'] != $table->total_cost)
			{
				// get multi-order model
				$multiOrderModel = JModelVAP::getInstance('multiorder');
				// recalculate totals of parent order
				$multiOrderModel->recalculateTotals($table->id_parent);

				// This may not the best solution because it creates a structure
				// similar to the circular dependency pattern by creating a link
				// between the reservation model and the multi-order model.
				// However, I have to say that this seems to be extremely effective.
				// The workflow will be observed carefully.
			}
		}

		// prepare event data
		$is_new     = empty($data['id']);
		$data['id'] = $id;

		/**
		 * Trigger event to allow the plugins to make something after saving
		 * an appointment into the database. Fires once all the details of
		 * the appointment has been saved.
		 *
		 * @param 	array 	 $args    The saved record.
		 * @param 	boolean  $is_new  True if the record was inserted.
		 * @param 	JModel   $model   The model instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onAfterSaveReservationLate', array($data, $is_new, $this));
		
		return $id;
	}

	/**
	 * Extend duplicate implementation to clone any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids     Either the record ID or a list of records.
	 * @param 	mixed    $src     Specifies some values to be used while duplicating.
	 * @param 	array    $ignore  A list of columns to skip.
	 *
	 * @return 	mixed    The ID of the records on success, false otherwise.
	 */
	public function duplicate($ids, $src = array(), $ignore = array())
	{
		$new_ids = array();

		// defined default columns that should never be copied
		$ignore[] = 'sid';
		$ignore[] = 'conf_key';
		$ignore[] = 'id_parent';
		$ignore[] = 'createdon';
		$ignore[] = 'createdby';
		$ignore[] = 'log';
		$ignore[] = 'cc_data';
		$ignore[] = 'payment_attempt';
		$ignore[] = 'conversion';

		$dbo = JFactory::getDbo();

		// get reservation options model
		$optModel = JModelVAP::getInstance('resoptassoc');

		foreach ($ids as $id_reservation)
		{
			// start by duplicating the whole record
			$new_id = parent::duplicate($id_reservation, $src, $ignore);

			if ($new_id)
			{
				$new_id = array_shift($new_id);

				// register copied
				$new_ids[] = $new_id;
			
				// load any assigned option
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_res_opt_assoc'))
					->where($dbo->qn('id_reservation') . ' = ' . (int) $id_reservation);

				$dbo->setQuery($q);

				if ($duplicate = $dbo->loadColumn())
				{
					$opt_data = array();
					$opt_data['id_reservation'] = $new_id;

					// duplicate options by using the new reservation ID
					$optModel->duplicate($duplicate, $opt_data);
				}
			}
		}

		return $new_ids;
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
		
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_reservation'))
			->where(array(
				$dbo->qn('id_parent') . ' IN (' . implode(',', $ids) . ')',
				$dbo->qn('id') . ' <> ' . $dbo->qn('id_parent'),
			), 'AND');

		$dbo->setQuery($q);

		// merge children with specified IDS list
		$ids = array_merge($ids, array_map('intval', $dbo->loadColumn()));

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		// load any reservation-option relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_res_opt_assoc'))
			->where($dbo->qn('id_reservation') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get reservation-option model
			$model = JModelVAP::getInstance('resoptassoc');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any assigned order statuses
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_order_status'))
			->where($dbo->qn('type') . ' = ' . $dbo->q('reservation'))
			->where($dbo->qn('id_order') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get order status model
			$model = JModelVAP::getInstance('orderstatus');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any assigned notes
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_user_notes'))
			->where($dbo->qn('group') . ' = ' . $dbo->q('appointments'))
			->where($dbo->qn('id_parent') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($note_ids = $dbo->loadColumn())
		{
			// get user notes model
			$model = JModelVAP::getInstance('usernote');
			// delete records
			$model->delete($note_ids);
		}

		return true;
	}

	/**
	 * Returns a list of appointments that intersects the specified date time.
	 *
	 * @param 	string   $datetime  The date time to look for.
	 * @param 	integer  $id_emp    An optional employee ID.
	 *
	 * @return 	array    A list of appointments.
	 */
	public function getAppointmentsAt($datetime, $id_emp = 0)
	{
		$dbo = JFactory::getDbo();

		// get employee timezone
		$employee_tz = JModelVAP::getInstance('employee')->getTimezone($id_emp);

		// create date instance and assume it refers to the
		// timezone of the selected employee (or global one)
		$date = new JDate($datetime, $employee_tz);

		$q = $dbo->getQuery(true);

		// select all reservation columns
		$q->select('r.*');
		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

		// select service name
		$q->select($dbo->qn('s.name', 'service_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('r.id_service'));

		if ($id_emp)
		{
			// filter by employee
			$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $id_emp);
		}
		else
		{
			$q->select($dbo->qn('e.nickname', 'employee_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('r.id_employee'));
		}

		// get any reserved codes
		$reserved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1));

		if ($reserved)
		{
			// filter by reserved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $reserved)) . ')');
		}

		// make sure the specified date stays between the reservation check-in and check-out
		$q->where($dbo->qn('r.checkin_ts') . ' <= ' . $dbo->q($date->toSql()));
		$q->where(sprintf(
			'DATE_ADD(%s, INTERVAL (%s + %s) MINUTE) > %s',
			$dbo->qn('r.checkin_ts'),
			$dbo->qn('r.duration'),
			$dbo->qn('r.sleep'),
			$dbo->q($date->toSql())
		));
	
		$dbo->setQuery($q);
		return $dbo->loadObjectList();
	}

	/**
	 * Returns a list of appointments with check-in on the specified date.
	 *
	 * @param 	string   $date    The date to look for.
	 * @param 	integer  $id_emp  An optional employee ID.
	 *
	 * @return 	array    A list of appointments.
	 */
	public function getAppointmentsOn($date, $id_emp = 0)
	{
		$dbo = JFactory::getDbo();

		// get employee timezone
		$employee_tz = JModelVAP::getInstance('employee')->getTimezone($id_emp);

		// create dates range and assume they refer to the
		// timezone of the selected employee (or global one)
		$start = new JDate($date, $employee_tz);
		$start->modify('00:00:00');

		$end = new JDate($date, $employee_tz);
		$end->modify('23:59:59');

		$q = $dbo->getQuery(true);

		// select all reservation columns
		$q->select('r.*');
		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

		// select service name
		$q->select($dbo->qn('s.name', 'service_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('r.id_service'));

		if ($id_emp)
		{
			// filter by employee
			$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $id_emp);
		}
		else
		{
			$q->select($dbo->qn('e.nickname', 'employee_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('r.id_employee'));
		}

		// get any reserved codes
		$reserved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1));

		if ($reserved)
		{
			// filter by reserved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $reserved)) . ')');
		}

		// make sure the specified date stays between the reservation check-in and check-out
		$q->where(sprintf('%s BETWEEN %s AND %s',
			$dbo->qn('r.checkin_ts'),
			$dbo->q($start->toSql()),
			$dbo->q($end->toSql())
		));

		// sort by check-in
		$q->order($dbo->qn('r.checkin_ts') . ' ASC');
	
		$dbo->setQuery($q);
		return $dbo->loadObjectList();
	}

	/**
	 * Checks whether the specified appointment is a child.
	 *
	 * @param 	mixed 	 $reservation  Either a reservation ID or a table object.
	 *
	 * @return 	boolean  True if a child, false otherwise.
	 */
	public function isChildAppointment($reservation)
	{
		if (is_numeric($reservation))
		{
			$table = $this->getTable();
			$table->load($reservation);
			$reservation = $table;
		}

		// check if we have a child appointment assigned to a parent order
		return $reservation->id_parent > 0 && $reservation->id_parent != $reservation->id;
	}

	/**
	 * Recalculates the totals of the specified reservation.
	 *
	 * @param 	object  &$reservation  The reservation details.
	 * @param 	mixed   $service       The service details. If not specified, it will
	 *                                 be automatically loaded. It is also possible to
	 *                                 pass a number to force the service price.
	 *
	 * @return 	void
	 */
	public function recalculateTotals(&$reservation, $service = null)
	{
		$wasArray = false;

		if (is_array($reservation))
		{
			// cast array to object and register reminder
			$reservation = (object) $reservation;
			$wasArray = true;
		}

		if (is_null($service))
		{
			// get service details
			$service = JModelVAP::getInstance('serempassoc')->getOverrides($reservation->id_service, $reservation->id_employee);

			if (!$service)
			{
				if ($wasArray)
				{
					// back to array
					$reservation = (array) $reservation;
				}

				throw new Exception('Employee/service relation not found.', 404);
			}
		}

		if (!isset($reservation->jid))
		{
			// use guest user group if not specified
			$reservation->jid = 0;
		}

		// in case of a service, calculate the resulting price
		if (is_object($service))
		{
			$checkin = new JDate($reservation->checkin_ts);

			if (!empty($reservation->timezone))
			{
				// adjust to the specified timezone
				$checkin->setTimezone(new DateTimeZone($reservation->timezone));
			}
			else
			{
				// adjust to the system timezone
				$checkin->setTimezone(new DateTimeZone(JFactory::getApplication()->get('offset', 'UTC')));
			}

			/**
			 * Calculate the reservation cost by using the special rates.
			 *
			 * @since 1.6
			 */
			$trace = array('id_user' => (int) $reservation->jid);

			$service_price = $price = VAPSpecialRates::getRate($reservation->id_service, $reservation->id_employee, $checkin, $reservation->people, $trace);

			if ($service->priceperpeople)
			{
				// multiply by the number of participants
				$price *= $reservation->people;
			}
		}
		else
		{
			// use the specified price
			$price = (float) $service;
		}

		if (!empty($reservation->id_user))
		{
			// get details of the customer assigned to this reservation
			$customer = VikAppointments::getCustomer($reservation->id_user);
			// fetch check-in date time
			$checkin = isset($reservation->checkin_ts) ? $reservation->checkin_ts : null;

			if ($customer && $customer->isSubscribed($service->id, $checkin))
			{
				// subscribed customer, unset price
				$price = 0;
			}
		}

		// define default values
		$reservation->service_gross = isset($reservation->service_gross) ? (float) $reservation->service_gross : 0;
		$reservation->service_net   = isset($reservation->service_net) ? (float) $reservation->service_net : 0;
		$reservation->service_tax   = isset($reservation->service_tax) ? (float) $reservation->service_tax : 0;
		$reservation->total_cost    = isset($reservation->total_cost) ? (float) $reservation->total_cost : 0;
		$reservation->total_net     = isset($reservation->total_net) ? (float) $reservation->total_net : 0;
		$reservation->total_tax     = isset($reservation->total_tax) ? (float) $reservation->total_tax : 0;

		// subtract existing service totals (subtract at most a self unit to prevent negative values)
		$reservation->total_cost -= min(array($reservation->service_gross, $reservation->total_cost));
		$reservation->total_net  -= min(array($reservation->service_net, $reservation->total_net));
		$reservation->total_tax  -= min(array($reservation->service_tax, $reservation->total_tax));

		VAPLoader::import('libraries.tax.factory');

		// prepare options for tax
		$options = array();
		$options['lang']    = isset($reservation->langtag) ? $reservation->langtag : null;
		$options['subject'] = 'service';
		$options['id_user'] = isset($reservation->id_user) ? (int) $reservation->id_user : 0;

		// calculate taxes
		$result = VAPTaxFactory::calculate($reservation->id_service, $price, $options);

		// update totals with new calculated price
		$reservation->service_price = $service_price;
		$reservation->service_net   = $result->net;
		$reservation->service_tax   = $result->tax;
		$reservation->service_gross = $result->gross;
		$reservation->tax_breakdown = json_encode($result->breakdown);

		// sum new sub-totals to order totals
		$reservation->total_cost += $reservation->service_gross;
		$reservation->total_net  += $reservation->service_net;
		$reservation->total_tax  += $reservation->service_tax;

		if ($wasArray)
		{
			// back to array
			$reservation = (array) $reservation;
		}
	}

	/**
	 * Adds a discount to the specified reservation.
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
		if (!$coupon || !isset($coupon->value))
		{
			// invalid/missing coupon
			$this->setError('Missing coupon code');

			return false;
		}

		$dbo = JFactory::getDbo();

		// load any children (options)
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'id_option', 'inc_price')))
			->from($dbo->qn('#__vikappointments_res_opt_assoc'))
			->where($dbo->qn('id_reservation') . ' = ' . (int) $id)
			->where($dbo->qn('inc_price') . ' > 0');

		$dbo->setQuery($q);
		$items = $dbo->loadObjectList();

		// load reservation details
		$table = $this->getTable();
		$table->load((int) $id);

		// define options for tax calculation
		$options = array(
			'subject' => 'service',
			'lang'    => $table->langtag,
			'id_user' => $table->id_user,
		);

		$total_c = 0;

		// calculate total cost
		foreach ($items as $item)
		{
			$total_c += (float) $item->inc_price;
		}

		// prepare order data
		$orderData = array(
			'id'         => $table->id,
			'total_cost' => $table->payment_charge + $table->payment_tax,
			'total_net'  => 0,
			'total_tax'  => $table->payment_tax,
			'discount'   => 0,
			'coupon'     => '',
			'options'    => array(),
		);

		VAPLoader::import('libraries.tax.factory');

		if ($table->service_price > 0)
		{
			/**
			 * Multiply the service price by the number of selected attendees.
			 * 
			 * @since 1.7.4  Do not multiply in case the service has the "Price per Person"
			 *               setting turned off.
			 */
			if ((bool) JModelVAP::getInstance('service')->getItem($table->id_service, $blank = true)->priceperpeople)
			{
				$table->service_price *= $table->people;
			}

			// include service within the total number
			// of items that can be discounted
			$total_c += $table->service_price;

			// recalculate service
			$cost_with_disc = $table->service_price;

			if (empty($coupon->percentot) || $coupon->percentot == 1)
			{
				// percentage discount
				$disc_val = round($cost_with_disc * $coupon->value / 100, 2);
			}
			else
			{
				// fixed discount, apply proportionally according to
				// the total cost of all the items
				$percentage = $cost_with_disc * 100 / $total_c;
				$disc_val = round($coupon->value * $percentage / 100, 2);

				// the discount cannot exceed the total price
				$disc_val = min(array($table->service_price, $disc_val));
			}

			// save service discount
			$orderData['service_discount'] = $disc_val;
			// increase total discount
			$orderData['discount'] += $orderData['service_discount'];

			// subtract discount from service cost
			$cost_with_disc -= $disc_val;

			// recalculate totals
			$totals = VAPTaxFactory::calculate($table->id_service, $cost_with_disc, $options);

			// update service totals
			$orderData['service_net']   = $totals->net;
			$orderData['service_tax']   = $totals->tax;
			$orderData['service_gross'] = $totals->gross;
			$orderData['tax_breakdown'] = $totals->breakdown;

			// update order totals
			$orderData['total_net']  += $orderData['service_net'];
			$orderData['total_tax']  += $orderData['service_tax'];
			$orderData['total_cost'] += $orderData['service_gross'];
		}

		$options['subject'] = 'option';

		// recalculate options
		foreach ($items as $i => $item)
		{
			$cost_with_disc = $item->inc_price;

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
					// the total cost of all the items
					$percentage = $cost_with_disc * 100 / $total_c;
					$disc_val = round($coupon->value * $percentage / 100, 2);
				}
				else
				{
					// We are fetching the last element of the list, instead of calculating the
					// proportional discount, we should subtract the total discount from the coupon
					// value, in order to avoid rounding issues. Let's take as example a coupon of
					// EUR 10 applied on 3 options. The final result would be 3.33 + 3.33 + 3.33,
					// which won't match the initial discount value of the coupon. With this
					// alternative way, the result would be: 10 - 3.33 - 3.33 = 3.34.
					$disc_val = $coupon->value - $orderData['discount'];
				}

				// the discount cannot exceed the total price
				$disc_val = min(array($item->inc_price, $disc_val));
			}

			// increase total discount
			$orderData['discount'] += $disc_val;

			// subtract discount from item cost
			$cost_with_disc -= $disc_val;

			// recalculate totals
			$totals = VAPTaxFactory::calculate($item->id_option, $cost_with_disc, $options);

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

			// append to options list
			$orderData['options'][] = $itemData;
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
	 * Removes discount from the specified reservation.
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
			->select($dbo->qn(array('id', 'id_option', 'inc_price')))
			->from($dbo->qn('#__vikappointments_res_opt_assoc'))
			->where($dbo->qn('id_reservation') . ' = ' . (int) $id)
			->where($dbo->qn('inc_price') . ' > 0');

		$dbo->setQuery($q);
		$items = $dbo->loadObjectList();

		// load reservation details
		$table = $this->getTable();
		$table->load((int) $id);

		if ($table->coupon_str)
		{
			// decode coupon string
			$coupon = explode(';;', $table->coupon_str);

			// unredeem coupon usage
			JModelVAP::getInstance('coupon')->unredeem($coupon[0]);
		}

		// define options for tax calculation
		$options = array(
			'subject' => 'service',
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
			'coupon_str' => '',
			'options'    => array(),
		);

		VAPLoader::import('libraries.tax.factory');

		if ($table->service_price > 0)
		{
			// multiply the service price by the number of selected attendees
			$table->service_price *= $table->people;
			
			$cost_no_disc = $table->service_price;

			// recalculate totals
			$totals = VAPTaxFactory::calculate($table->id_service, $cost_no_disc, $options);

			// update service totals
			$orderData['service_net']      = $totals->net;
			$orderData['service_tax']      = $totals->tax;
			$orderData['service_gross']    = $totals->gross;
			$orderData['service_discount'] = 0;
			$orderData['tax_breakdown']    = $totals->breakdown;

			// update order totals
			$orderData['total_net']  += $orderData['service_net'];
			$orderData['total_tax']  += $orderData['service_tax'];
			$orderData['total_cost'] += $orderData['service_gross'];
		}

		$options['subject'] = 'option';

		foreach ($items as $i => $item)
		{
			$cost_no_disc = $item->inc_price;

			// recalculate totals
			$totals = VAPTaxFactory::calculate($item->id_option, $cost_no_disc, $options);

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
			$orderData['options'][] = $itemData;
		}

		// update order details
		return $this->save($orderData);
	}

	/**
	 * Sends an e-mail notification to the customer of the
	 * specified reservation.
	 *
	 * @param 	integer  $id       The reservation ID.
	 * @param 	array 	 $options  An array of options.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function sendEmailNotification($id, array $options = array())
	{
		VAPLoader::import('libraries.mail.factory');

		// fetch receiver alias
		$client = isset($options['client']) ? $options['client'] : 'customer';

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
	 * Sends a SMS notification to the customer of the
	 * specified reservation.
	 *
	 * @param 	integer  $id  The reservation ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function sendSmsNotification($id)
	{
		try
		{
			// get current SMS instance
			$smsapi = VAPApplication::getInstance()->getSmsInstance();
		}
		catch (Exception $e)
		{
			// SMS API not configured
			$this->setError(JText::translate('VAPSMSESTIMATEERR1'));

			return false;
		}

		VAPLoader::import('libraries.order.factory');

		try
		{
			// load appointment details
			$order = VAPOrderFactory::getAppointments($id);
		}
		catch (Exception $e)
		{
			// order not found
			$this->setError($e->getMessage());

			return false;
		}

		// make sure we have a phone number
		if (!$order->purchaser_phone)
		{
			// register error
			$this->setError('Missing phone number.');

			return false;
		}

		// make sure the phone number reports a dial code
		if ($order->purchaser_prefix && !preg_match("/^\+/", $order->purchaser_phone))
		{
			// nope, add the specified one (backward compatibility)
			$order->purchaser_phone = $order->purchaser_prefix . $order->purchaser_phone;
		}

		// fetch sms message
		$text = VikAppointments::getSmsCustomerTextMessage($order);

		// send message
		$response = $smsapi->sendMessage($order->purchaser_phone, $text);

		// validate response
		if (!$smsapi->validateResponse($response))
		{
			// unable to send the notification, register error message
			$log = $smsapi->getLog();

			if ($log)
			{
				$this->setError($log);
			}

			return false;
		}

		return true;
	}

	/**
	 * Returns a list of available times for the specified data.
	 *
	 * @param 	array  $data  An array of search data.
	 *
	 * @return 	mixed  An array of available times. False in case of error.
	 */
	public function getAvailableTimes($data)
	{
		// prepare search options
		$options = array();
		$options['people'] = !empty($data['people']) ? (int) $data['people'] : 1;
		$options['id_res'] = !empty($data['id'])     ? (int) $data['id']     : 0;

		// number of people cannot be lower than 1
		$options['people'] = max(array(1, (int) $options['people']));

		if (JFactory::getApplication()->isClient('administrator') || (!empty($data['validate_availability']) && $data['validate_availability'] == 'admin'))
		{
			// grant administrator access
			$options['admin'] = true;
		}

		VAPLoader::import('libraries.availability.manager');
		// create availability search instance
		$search = VAPAvailabilityManager::getInstance($data['id_service'], $data['id_employee'], $options);

		try
		{
			// create timeline parser instance
			VAPLoader::import('libraries.availability.timeline.factory');
			$parser = VAPAvailabilityTimelineFactory::getParser($search);
		}
		catch (Exception $e)
		{
			// register exception as error
			$this->setError($e);

			return false;
		}

		// get employee timezone
		$tz = JModelVAP::getInstance('employee')->getTimezone($search->get('id_employee'));

		// create check-in date and adjust it to the employee timezone
		$checkin = JDate::getInstance($data['checkin_ts']);
		$checkin->setTimezone(new DateTimeZone($tz));

		// elaborate timeline
		$timeline = $parser->getTimeline($checkin->format('Y-m-d', true), $options['people'], $options['id_res']);

		if (!$timeline)
		{
			// propagate error message
			$this->setError($parser->getError());

			return false;
		}

		return $timeline;
	}

	/**
	 * Checks the availability within the system according to the specified
	 * search details.
	 *
	 * @param 	array  $data  An array of search data.
	 *
	 * @return 	mixed  True if available, false otherwise. In case the employee
	 *                 was not specified, the ID of the available employee will
	 *                 be returned instead.
	 */
	public function isAvailable($data)
	{
		// get availability timeline
		$timeline = $this->getAvailableTimes($data);

		if (!$timeline)
		{
			// not available for the current day
			return false;
		}

		// convert times into an array
		$times = $timeline->toArray($flatten = true);

		// get employee timezone
		$tz = JModelVAP::getInstance('employee')->getTimezone($timeline->getSearch()->get('id_employee'));

		// create check-in date and adjust it to the employee timezone
		$checkin = JDate::getInstance($data['checkin_ts']);
		$checkin->setTimezone(new DateTimeZone($tz));

		// extract time
		$hm = $checkin->format('H:i', $local = true);
		// convert time to minutes
		$hm = JHtml::fetch('vikappointments.time2min', $hm);

		// make sure the time is available
		if (!isset($times[$hm]) || $times[$hm] != 1)
		{
			// the time is not available/supported
			$this->setError(JText::translate('VAPRESDATETIMENOTAVERR'));

			return false;
		}

		$options = array();

		if (isset($data['exclude_employees']))
		{
			// check if we should exclude certain employees from the availability check
			$options['exclude_employees'] = $data['exclude_employees'];
		}

		// create availability search instance
		$search = VAPAvailabilityManager::getInstance($data['id_service'], $data['id_employee'], $options);

		$duration = 0;

		if (!empty($data['duration']))
		{
			$duration += $data['duration'];

			if (!empty($data['sleep']))
			{
				$duration += $data['sleep'];
			}
		}

		// fetch number of participants
		$people = !empty($data['people']) ? max(array(1, (int) $data['people'])) : 1;
		// check if we are editing a reservation
		$id = !empty($data['id']) ? (int) $data['id'] : 0;

		// exmployee was specified, validate its availability
		if ($data['id_employee'] > 0)
		{
			// check if the employee is able to host the appointment
			$is = $search->isEmployeeAvailable($data['checkin_ts'], $duration, $people, $id);
		}
		else
		{
			// Employee not specified, we need to load all the employees assigned
			// to this service and take the first available one. In case of available
			// employee, its ID will be returned here.
			$is = $search->isServiceAvailable($data['checkin_ts'], $duration, $people, $id);
		}

		if (!$is)
		{
			// the time is not available/supported
			$this->setError(JText::translate('VAPRESDATETIMENOTAVERR'));

			return false;
		}

		// employee available (true or its ID)
		return $is;
	}

	/**
	 * Updates the status of all the appointments out of time to REMOVED.
	 * This method is used to free the slots occupied by pending orders
	 * that haven't been confirmed within the specified range of time.
	 *
	 * Affects only the reservations that match the specified employee ID.
	 *
	 * @param 	array  $options  An array of options to filter the records.
	 *
	 * @return 	void
	 */
	public function checkExpired(array $options = array())
	{
		$dbo = JFactory::getDbo();

		// get any pending codes
		$pending = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1, 'approved' => 0)); 

		// select all the expired appointments
		$q = $dbo->getQuery(true);
		$q->select($dbo->qn('id'));
		$q->from($dbo->qn('#__vikappointments_reservation'));

		// take the expired appointments
		$q->where($dbo->qn('locked_until') . ' < ' . time());

		if ($pending)
		{
			// filter by pending status
			$q->where($dbo->qn('status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $pending)) . ')');
		}

		if (!empty($options['id_service']))
		{
			// get service model
			$serviceModel = JModelVAP::getInstance('service');

			// check if we have a service with private calendar
			if ($serviceModel->hasOwnCalendar($options['id_service']))
			{
				// we can directly filter the reservations by service, because the
				// availability is related to the appointments assigned to the latter
				$q->where($dbo->qn('id_service') . ' = ' . (int) $options['id_service']);
				// unset employees filter
				$options['id_employee'] = 0;
			}
			else
			{
				/**
				 * Service set, recover all the employees assigned to this service
				 * in order to properly refresh the availability. It is not enough
				 * to remove only the appointments assigned to the specified service,
				 * because a time-slot of the employees might have been locked by
				 * an appointment assigned to a different service.
				 *
				 * @since 1.7
				 */
				$employees = $dbo->getQuery(true)
					->select($dbo->qn('id_employee'))
					->from($dbo->qn('#__vikappointments_ser_emp_assoc'))
					->where($dbo->qn('id_service') . ' = ' . (int) $options['id_service']);

				$dbo->setQuery($employees);
				
				// filter by the specified employees
				$options['employees'] = $dbo->loadColumn();
			}
		}

		if (!empty($options['id_employee']))
		{
			// affects only the reservations that match the specified employee ID
			if (is_array($options['id_employee']))
			{
				// sanitize employees array
				$ids = implode(',', array_map('intval', $options['id_employee']));
				$q->where($dbo->qn('id_employee') . ' IN (' . $ids . ')');
			}
			else
			{
				$q->where($dbo->qn('id_employee') . ' = ' . (int) $options['id_employee']);
			}
		}

		if (!empty($options['id']))
		{
			// take only the specified reservation
			$q->andWhere(array(
				$dbo->qn('id') . ' = ' . (int) $options['id'],
				$dbo->qn('id_parent') . ' = ' . (int) $options['id'],
			), 'OR');
		}
		
		$dbo->setQuery($q);
		$rows = $dbo->loadColumn();

		$handler = VAPOrderStatus::getInstance();

		foreach ($rows as $id)
		{
			// Remove and track the status change (REMOVED).
			// Do not use the model to save the status change
			// to speed up the whole process. It is still possible
			// to track the status change by using the hook
			// provided by VAPOrderStatus class.
			$handler->remove($id, 'VAP_STATUS_ORDER_REMOVED');
		}
	}
}
