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

VAPLoader::import('libraries.order.wrapper');

/**
 * Appointments order class wrapper.
 *
 * @since 1.7
 */
class VAPOrderAppointment extends VAPOrderWrapper
{
	/**
	 * Class constructor.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 */
	public function __construct($id, $langtag = null, array $options = array())
	{
		// always force translation
		$options['translate'] = true;

		// get current language tag
		$default_lang = JFactory::getLanguage()->getTag();

		// construct with parent
		parent::__construct($id, $langtag, $options);

		// restore previous language according to the current cllient
		VikAppointments::loadLanguage($default_lang);
	}

	/**
	 * Helper method used to access all the user notes that belong to the
	 * specified appointment/order.
	 *
	 * @return 	array
	 */
	public function getUserNotes($id = null)
	{
		$notes = array();

		foreach ($this->notes as $note)
		{
			// take only public notes
			if ($note->status && (!$id || $id == $note->id_parent))
			{
				$notes[] = $note;
			}
		}

		return $notes;
	}

	/**
	 * Returns an array containing the requested information of the attendees.
	 *
	 * @param 	string 	$column  The information to access (name, mail, phone).
	 *
	 * @return 	array   An array of values.
	 */
	public function getAttendeesInfo($column = 'mail')
	{
		// fetch property name
		switch ($column)
		{
			case 'name':
			case 'nominative':
				$column = 'purchaser_nominative';
				break;

			case 'mail':
			case 'email':
				$column = 'purchaser_mail';
				break;

			case 'tel':
			case 'phone':
				$column = 'purchaser_phone';
				break;
		}

		$list = array();

		// include author info, if existing
		$list[] = $this->get($column, null);

		// iterate attendees
		foreach ($this->attendees as $attendee)
		{
			// make sure the requested property exists
			if (isset($attendee[$column]))
			{
				$list[] = $attendee[$column];
			}
		}

		// get rid of empty and duplicate values
		return array_values(array_unique(array_filter($list)));
	}

	/**
	 * @override
	 * Returns the appointments order object.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 *
	 * @return 	mixed    The array/object to load.
	 *
	 * @throws 	Exception
	 */
	protected function load($id, $langtag = null, array $options = array())
	{
		$dbo        = JFactory::getDbo();
		$config     = VAPFactory::getConfig();
		$dispatcher = VAPFactory::getEventDispatcher();

		// create query
		$q = $dbo->getQuery(true);

		// select all reservation columns
		$q->select('r.*');
		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

		// select employee details
		$q->select($dbo->qn('e.nickname', 'employee_name'));
		$q->select($dbo->qn('e.email', 'employee_email'));
		$q->select($dbo->qn('e.phone', 'employee_phone'));
		$q->select($dbo->qn('e.notify', 'employee_notify'));
		$q->select($dbo->qn('e.image', 'employee_image'));
		$q->select($dbo->qn('e.timezone'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'));

		// select employee group details
		$q->select($dbo->qn('ge.id', 'employee_group_id'));
		$q->select($dbo->qn('ge.name', 'employee_group_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee_group', 'ge') . ' ON ' . $dbo->qn('e.id_group') . ' = ' . $dbo->qn('ge.id'));

		// select service details
		$q->select($dbo->qn('s.name', 'service_name'));
		$q->select($dbo->qn('s.description', 'service_description'));
		$q->select($dbo->qn('s.image', 'service_image'));
		$q->select($dbo->qn('s.max_capacity', 'service_max_capacity'));
		$q->select($dbo->qn('s.choose_emp', 'service_choose_emp'));
		$q->select($dbo->qn('s.attachments', 'service_attachments'));
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'));

		// select service group details
		$q->select($dbo->qn('gs.id', 'service_group_id'));
		$q->select($dbo->qn('gs.name', 'service_group_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_group', 'gs') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('gs.id'));

		// select payment details
		$q->select($dbo->qn('gp.name', 'payment_name'));
		$q->select($dbo->qn('gp.file', 'payment_file'));
		$q->select($dbo->qn('gp.note', 'payment_note'));
		$q->select($dbo->qn('gp.prenote', 'payment_prenote'));
		$q->select($dbo->qn('gp.icontype', 'payment_icontype'));
		$q->select($dbo->qn('gp.icon', 'payment_icon'));
		$q->leftjoin($dbo->qn('#__vikappointments_gpayments', 'gp') . ' ON ' . $dbo->qn('r.id_payment') . ' = ' . $dbo->qn('gp.id'));

		// select purchased options
		$q->select($dbo->qn('oi.id', 'option_id'));
		$q->select($dbo->qn('oi.id_option', 'option_id_option'));
		$q->select($dbo->qn('oi.id_variation', 'option_id_variation'));
		$q->select($dbo->qn('oi.quantity', 'option_quantity'));
		$q->select($dbo->qn('oi.inc_price', 'option_price'));
		$q->select($dbo->qn('oi.net', 'option_net'));
		$q->select($dbo->qn('oi.tax', 'option_tax'));
		$q->select($dbo->qn('oi.gross', 'option_gross'));
		$q->select($dbo->qn('oi.discount', 'option_discount'));
		$q->select($dbo->qn('oi.tax_breakdown', 'option_tax_breakdown'));
		$q->select($dbo->qn('o.name', 'option_name'));
		$q->select($dbo->qn('o.description', 'option_description'));
		$q->select($dbo->qn('o.image', 'option_image'));
		$q->select($dbo->qn('o.single', 'option_single'));
		$q->select($dbo->qn('ov.name', 'option_var_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_res_opt_assoc', 'oi') . ' ON ' . $dbo->qn('oi.id_reservation') . ' = ' . $dbo->qn('r.id'));
		$q->leftjoin($dbo->qn('#__vikappointments_option', 'o') . ' ON ' . $dbo->qn('oi.id_option') . ' = ' . $dbo->qn('o.id'));
		$q->leftjoin($dbo->qn('#__vikappointments_option_value', 'ov') . ' ON ' . $dbo->qn('oi.id_variation') . ' = ' . $dbo->qn('ov.id'));

		// exclude closures
		$q->where($dbo->qn('r.closure') . ' = 0');

		// filter by order key, if specified
		if (isset($options['sid']))
		{
			$q->where($dbo->qn('r.sid') . ' = ' . $dbo->q($options['sid']));
		}

		// load appointments matching the specified ID/parent
		$q->andWhere(array(
			$dbo->qn('r.id') . ' = ' . (int) $id,
			$dbo->qn('r.id_parent') . ' = ' . (int) $id,
		), 'OR');

		// filter by confirmation key, if specified
		if (isset($options['conf_key']))
		{
			$q->where($dbo->qn('r.conf_key') . ' = ' . $dbo->q($options['conf_key']));
		}

		$q->order($dbo->qn('r.id') . ' ASC');
		$q->order($dbo->qn('oi.id') . ' ASC');

		/**
		 * External plugins can attach to this hook in order to manipulate
		 * the query at runtime, in example to alter the default ordering.
		 *
		 * @param 	mixed    &$query   A query builder instance.
		 * @param 	integer  $id       The ID of the order.
		 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
		 * @param 	array 	 $options  An array of options to be passed to the order instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onLoadAppointmentsOrderDetails', array(&$q, $id, $langtag, $options));

		$dbo->setQuery($q);
		$list = $dbo->loadObjectList();

		if (!$list)
		{
			// order not found raise error
			throw new Exception(sprintf('Order [%d] not found', $id), 404);
		}

		if (!$langtag)
		{
			// use order lang tag in case it was not specified
			$langtag = $list[0]->langtag;

			if (!$langtag)
			{
				// the order is not assigned to any lang tag, use the current one
				$langtag = JFactory::getLanguage()->getTag();
			}
		}

		
		// Load the language adjusted to the specified one, because this method
		// uses JText to translate some contents, such as the date formats.
		// Otherwise, when sending a notification from the back-end, in case the
		// language differs, the dates will be reported in a wrong locale.
		VikAppointments::loadLanguage($langtag, JPATH_SITE);

		// get current user timezone
		$tz = JFactory::getUser()->getTimezone()->getName();

		// get system timezone
		$system_tz = JFactory::getApplication()->get('offset', 'UTC');

		if ($list[0]->user_timezone)
		{
			// use the timezone saved within the appointment record
			$customer_tz = $list[0]->user_timezone;
		}
		else if ($list[0]->createdby > 0)
		{
			// Get customer timezone.
			// Notice that it relies on the used ID of the author. This means that
			// the timezone might not be correct in case the reservation has been
			// created from the back-end.
			$customer_tz = JFactory::getUser($list[0]->createdby)->getTimezone()->getName();
		}
		else
		{
			// use system timezone
			$customer_tz = $system_tz;
		}

		// get working time model
		$wdModel = JModelVAP::getInstance('worktime');
		// get location model
		$locModel = JModelVAP::getInstance('location');

		// create parent order details
		$order = $list[0];

		// register customer timezone
		$order->customerTimezone = $customer_tz;

		$order->appointments = array();
		$order->sameEmp = array();

		foreach ($list as $row)
		{
			if ($row->id_employee <= 0)
			{
				// we have a parent ID, skip it
				continue;
			}

			// check if we have already registered the appointment
			if (!isset($order->appointments[$row->id]))
			{
				// create appointment object
				$app = new stdClass;
				$app->id  = $row->id;
				$app->sid = $row->sid;

				// create employee object
				$app->employee = new stdClass;
				$app->employee->id     = $this->detach($row, 'id_employee');
				$app->employee->name   = $this->detach($row, 'employee_name');
				$app->employee->email  = $this->detach($row, 'employee_email');
				$app->employee->phone  = $this->detach($row, 'employee_phone');
				$app->employee->image  = $this->detach($row, 'employee_image');
				$app->employee->notify = $this->detach($row, 'employee_notify');

				$order->sameEmp[] = $app->employee->id;

				// create employee group object
				if ($row->employee_group_id)
				{
					$app->employee->group = new stdClass;
					$app->employee->group->id   = $this->detach($row, 'employee_group_id');
					$app->employee->group->name = $this->detach($row, 'employee_group_name');
				}
				else
				{
					$app->employee->group = null;
				}

				// create service object
				$app->service = new stdClass;
				$app->service->id             = $this->detach($row, 'id_service');
				$app->service->name           = $this->detach($row, 'service_name');
				$app->service->description    = $this->detach($row, 'service_description');
				$app->service->price          = $this->detach($row, 'service_price');
				$app->service->image          = $this->detach($row, 'service_image');
				$app->service->maxCapacity    = $this->detach($row, 'service_max_capacity');
				$app->service->chooseEmployee = $this->detach($row, 'service_choose_emp');
				$app->service->attachments    = $this->detach($row, 'service_attachments');
				// decode attachments
				$app->service->attachments = $app->service->attachments ? (array) json_decode($app->service->attachments, true) : array();

				// create service group object
				if ($row->service_group_id)
				{
					$app->service->group = new stdClass;
					$app->service->group->id   = $this->detach($row, 'service_group_id');
					$app->service->group->name = $this->detach($row, 'service_group_name');
				}
				else
				{
					$app->service->group = null;
				}

				// create check-in object relative to current user timezone
				$app->checkin = new stdClass;
				$app->checkin->lc   = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'));
				$app->checkin->lc2  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC2'));
				$app->checkin->lc3  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'));
				$app->checkin->date = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC5'));
				$app->checkin->utc  = $row->checkin_ts;
				// register ISO 8601 date format (Y-m-dTH:i:sZ)
				$app->checkin->iso8601 = JFactory::getDate($row->checkin_ts)->toISO8601();
				// register check-in UNIX timestamp (UTC)
				$app->checkin->timestamp = JDate::getInstance($row->checkin_ts)->getTimestamp();
				// register check-in timezone
				$app->checkin->timezone = $tz;

				$checkout = VikAppointments::getCheckout($row->checkin_ts, $row->duration);

				// create check-out object relative to current user timezone
				$app->checkout = new stdClass;
				$app->checkout->lc   = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'));
				$app->checkout->lc2  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC2'));
				$app->checkout->lc3  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'));
				$app->checkout->date = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC5'));
				$app->checkout->utc  = $checkout;
				// register ISO 8601 date format (Y-m-dTH:i:sZ)
				$app->checkout->iso8601 = JFactory::getDate($checkout)->toISO8601();
				// register check-out timezone
				$app->checkout->timezone = $tz;

				if (!$row->timezone)
				{
					// use system timezone for employees that do not specify it
					$row->timezone = $system_tz;
				}

				// create check-in object relative to employee timezone
				$app->employee->checkin = new stdClass;
				$app->employee->checkin->lc   = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), $row->timezone);
				$app->employee->checkin->lc2  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC2'), $row->timezone);
				$app->employee->checkin->lc3  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'), $row->timezone);
				$app->employee->checkin->date = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC5'), $row->timezone);
				// register check-in timezone
				$app->employee->checkin->timezone = $row->timezone;

				// create check-out object relative to employee timezone
				$app->employee->checkout = new stdClass;
				$app->employee->checkout->lc   = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), $row->timezone);
				$app->employee->checkout->lc2  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC2'), $row->timezone);
				$app->employee->checkout->lc3  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'), $row->timezone);
				$app->employee->checkout->date = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC5'), $row->timezone);
				// register check-out timezone
				$app->employee->checkout->timezone = $row->timezone;

				// create check-in object relative to customer timezone
				$app->customerCheckin = new stdClass;
				$app->customerCheckin->lc   = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), $customer_tz);
				$app->customerCheckin->lc2  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC2'), $customer_tz);
				$app->customerCheckin->lc3  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'), $customer_tz);
				$app->customerCheckin->date = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC5'), $customer_tz);
				// register check-in timezone
				$app->customerCheckin->timezone = $customer_tz;

				// create check-out object relative to customer timezone
				$app->customerCheckout = new stdClass;
				$app->customerCheckout->lc   = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), $customer_tz);
				$app->customerCheckout->lc2  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC2'), $customer_tz);
				$app->customerCheckout->lc3  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'), $customer_tz);
				$app->customerCheckout->date = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC5'), $customer_tz);
				// register check-out timezone
				$app->customerCheckout->timezone = $customer_tz;

				// create check-in object relative to system timezone
				$app->systemCheckin = new stdClass;
				$app->systemCheckin->lc   = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), $system_tz);
				$app->systemCheckin->lc2  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC2'), $system_tz);
				$app->systemCheckin->lc3  = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'), $system_tz);
				$app->systemCheckin->date = JHtml::fetch('date', $row->checkin_ts, JText::translate('DATE_FORMAT_LC5'), $system_tz);
				// register check-in timezone
				$app->systemCheckin->timezone = $system_tz;

				// create check-out object relative to system timezone
				$app->systemCheckout = new stdClass;
				$app->systemCheckout->lc   = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'), $system_tz);
				$app->systemCheckout->lc2  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC2'), $system_tz);
				$app->systemCheckout->lc3  = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat'), $system_tz);
				$app->systemCheckout->date = JHtml::fetch('date', $checkout, JText::translate('DATE_FORMAT_LC5'), $system_tz);
				// register check-out timezone
				$app->systemCheckout->timezone = $system_tz;

				// inject appointment details
				$app->duration = $this->detach($row, 'duration');
				$app->sleep    = $this->detach($row, 'sleep');
				$app->people   = $this->detach($row, 'people');
				$app->viewEmp  = $this->detach($row, 'view_emp');
				$app->status   = $row->status;

				$app->statusRole = null;

				// fetch status role
				if (JHtml::fetch('vaphtml.status.ispending', 'appointments', $app->status))
				{
					$app->statusRole = 'PENDING';
				}
				else if (JHtml::fetch('vaphtml.status.isapproved', 'appointments', $app->status))
				{
					$app->statusRole = 'APPROVED';
				}
				else if (JHtml::fetch('vaphtml.status.isremoved', 'appointments', $app->status))
				{
					$app->statusRole = 'EXPIRED';
				}
				else if (JHtml::fetch('vaphtml.status.iscancelled', 'appointments', $app->status))
				{
					$app->statusRole = 'CANCELLED';
				}

				// create appointment sub-totals
				$app->totals = new stdClass;
				$app->totals->net       = $this->detach($row, 'service_net');
				$app->totals->tax       = $this->detach($row, 'service_tax');
				$app->totals->gross     = $this->detach($row, 'service_gross');
				$app->totals->grossOpt  = $app->totals->gross;
				$app->totals->discount  = $this->detach($row, 'service_discount');
				$app->totals->breakdown = $row->tax_breakdown ? json_decode($this->detach($row, 'tax_breakdown')) : null;

				// find location of the appointment
				$id_location = $wdModel->getLocation($app->checkin->utc, $app->service->id, $app->employee->id);
				// get location details
				$app->location = $locModel->getInfo($id_location);

				// init options array
				$app->options = array();

				// register appointment
				$order->appointments[$row->id] = $app;
			}

			// check if we have an option to register
			if (!empty($row->option_id))
			{
				// create option
				$option = new stdClass;
				$option->id_assoc     = $this->detach($row, 'option_id');
				$option->id           = $this->detach($row, 'option_id_option');
				$option->id_variation = $this->detach($row, 'option_id_variation');
				$option->name         = $this->detach($row, 'option_name');
				$option->varName      = $this->detach($row, 'option_var_name');
				$option->fullName     = $option->name . ($option->varName ? ' - ' . $option->varName : '');
				$option->description  = $this->detach($row, 'option_description');
				$option->image        = $this->detach($row, 'option_image');
				$option->multiple     = $this->detach($row, 'option_single');
				$option->quantity     = $this->detach($row, 'option_quantity');
				$option->price        = $this->detach($row, 'option_price');

				// create option totals
				$option->totals = new stdClass;
				$option->totals->net       = $this->detach($row, 'option_net');
				$option->totals->tax       = $this->detach($row, 'option_tax');
				$option->totals->gross     = $this->detach($row, 'option_gross');
				$option->totals->discount  = $this->detach($row, 'option_discount');
				$option->totals->breakdown = $row->option_tax_breakdown ? json_decode($this->detach($row, 'option_tax_breakdown')) : null;;

				// register option within appointment record
				$order->appointments[$row->id]->options[] = $option;

				// increase appointment gross by the option cost
				$order->appointments[$row->id]->totals->grossOpt += $option->totals->gross;
			}
		}

		// reset array keys
		$order->appointments = array_values($order->appointments);

		// fetch coupon
		if ($order->coupon_str)
		{
			list($code, $type, $amount) = explode(';;', $this->detach($order, 'coupon_str'));

			$order->coupon = new stdClass;
			$order->coupon->code   = $code;
			$order->coupon->amount = $amount;
			$order->coupon->type   = $type;
		}
		else
		{
			$order->coupon = null;
			$this->detach($order, 'coupon_str');
		}

		// decode stored CF data
		$order->custom_f      = (array) json_decode($order->custom_f, true);
		$order->fields        = $order->custom_f;
		$order->displayFields = $order->fields;

		$vars = array_values($order->fields);
		$vars = array_filter($vars, function($elem)
		{
			if (is_array($elem))
			{
				return (bool) $elem;
			}

			return strlen($elem);
		});
		
		$order->hasFields = (bool) $vars;

		// decode uploads
		$order->uploads = json_decode($order->uploads);

		// decode attendees
		$order->attendees = $order->attendees ? (array) json_decode($order->attendees, true) : array();

		/**
		 * Get rid of records that do not specify any details for the attendee.
		 * 
		 * @since 1.7.1
		 */
		$order->attendees = array_filter($order->attendees, function($attendee)
		{
			if (!empty($attendee['fields']))
			{
				foreach ((array) $attendee['fields'] as $v)
				{
					if (strlen($v))
					{
						return true;
					}
				}
			}

			if (!empty($attendee['uploads']))
			{
				foreach ((array) $attendee['uploads'] as $v)
				{
					if (strlen($v))
					{
						return true;
					}
				}
			}

			return false;
		});

		// fetch payment data
		if ($order->payment_file)
		{
			$order->payment = new stdClass;
			$order->payment->id       = $this->detach($order, 'id_payment');
			$order->payment->name     = $this->detach($order, 'payment_name');
			$order->payment->driver   = $this->detach($order, 'payment_file');
			$order->payment->iconType = $this->detach($order, 'payment_icontype');
			$order->payment->icon     = $this->detach($order, 'payment_icon');

			if ($order->payment->iconType == 1)
			{
				// Font Icon
				$order->payment->fontIcon = $order->payment->icon;
			}
			else
			{
				// Image Icon
				$order->payment->iconURI = JUri::root() . $order->payment->icon;

				// fetch Font Icon based on payment driver
				switch ($order->payment->driver)
				{
					case 'bank_transfer.php':
						$order->payment->fontIcon = 'fas fa-money-bill';
						break;

					case 'paypal.php':
						$order->payment->fontIcon = 'fab fa-paypal';
						break;

					default:
						$order->payment->fontIcon = 'fas fa-credit-card';
				}
			}

			$order->payment->notes = new stdClass;
			$order->payment->notes->beforePurchase = $this->detach($order, 'payment_prenote');
			$order->payment->notes->afterPurchase  = $this->detach($order, 'payment_note');
		}
		else
		{
			$order->payment = null;
		}

		// setup totals
		$order->totals = new stdClass;
		$order->totals->net       = $this->detach($order, 'total_net');
		$order->totals->tax       = $this->detach($order, 'total_tax');
		$order->totals->gross     = $this->detach($order, 'total_cost');
		$order->totals->discount  = $this->detach($order, 'discount');
		$order->totals->paid      = $this->detach($order, 'tot_paid');
		$order->totals->payCharge = $this->detach($order, 'payment_charge');
		$order->totals->payTax    = $this->detach($order, 'payment_tax');
		$order->totals->due       = $order->totals->gross - $order->totals->paid;

		if (!$order->paid)
		{
			// fetch paid flag based on current order status
			$order->paid = JHtml::fetch('vaphtml.status.ispaid', 'appointments', $order->status);
		}

		if ($order->paid)
		{
			// amount paid, no remaining balance
			$order->totals->due = 0;
		}

		$order->statusRole = null;

		// fetch status role
		if (JHtml::fetch('vaphtml.status.ispending', 'appointments', $order->status))
		{
			$order->statusRole = 'PENDING';
		}
		else if (JHtml::fetch('vaphtml.status.isapproved', 'appointments', $order->status))
		{
			$order->statusRole = 'APPROVED';
		}
		else if (JHtml::fetch('vaphtml.status.isremoved', 'appointments', $order->status))
		{
			$order->statusRole = 'EXPIRED';
		}
		else if (JHtml::fetch('vaphtml.status.iscancelled', 'appointments', $order->status))
		{
			$order->statusRole = 'CANCELLED';
		}

		// flag used to check whether all the appointments have been
		// booked for the same employee
		$order->sameEmp = count(array_unique($order->sameEmp)) == 1;

		/**
		 * External plugins can use this event to manipulate the object holding
		 * the details of the order. Useful to inject all the additional data
		 * fetched with the manipulation of the query.
		 *
		 * @param 	mixed  $order  The order details object.
		 * @param 	array  $list   The query resulting array.
		 *
		 * @return 	void
		 */
		$dispatcher->trigger('onSetupAppointmentsOrderDetails', array($order, $list));

		$unsetList = array(
			'id_service',
			'id_employee',
			'checkin_ts',
			'duration',
			'sleep',
			'people',
			'tax_breakdown',
			'timezone',
			'view_emp',
			// unset notes because they are loaded through the getNotes() method
			'notes',
		);

		// get rid of not needed properties
		foreach (get_object_vars($order) as $k => $v)
		{
			if (preg_match("/^(employee|service|option|payment)_/", $k))
			{
				// the property is probably related to the appointment
				// or it results blank (so it was not parsed)
				unset($order->{$k});
			}
			else if (preg_match("/^__/", $k))
			{
				// remove deprecated (back-up) property
				unset($order->{$k});
			}
			else if (in_array($k, $unsetList))
			{
				// remove property if contained in the list
				unset($order->{$k});
			}
		}

		return $order;
	}

	/**
	 * @override
	 * Translates the internal properties.
	 *
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 *
	 * @return 	void
	 */
	protected function translate($langtag = null)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		if (!$langtag)
		{
			// use order lang tag in case it was not specified
			$langtag = $this->get('langtag', null);

			if (!$langtag)
			{
				// the order is not assigned to any lang tag, use the current one
				$langtag = JFactory::getLanguage()->getTag();
			}
		}

		// load front-end language
		VikAppointments::loadLanguage($langtag, JPATH_SITE);

		// get translator
		$translator = VAPFactory::getTranslator();

		$service_ids   = array();
		$ser_group_ids = array();
		$emp_group_ids = array();
		$employee_ids  = array();
		$option_ids    = array();
		$optvar_ids    = array();

		foreach ($this->appointments as $app)
		{
			$service_ids[]  = $app->service->id;
			$employee_ids[] = $app->employee->id;

			if ($app->service->group)
			{
				$ser_group_ids[] = $app->service->group->id;
			}

			if ($app->employee->group)
			{
				$emp_group_ids[] = $app->employee->group->id;
			}

			foreach ($app->options as $opt)
			{
				$option_ids[] = $opt->id;

				if ($opt->id_variation > 0)
				{
					$optvar_ids[] = $opt->id_variation;
				}
			}
		}

		// pre-load services translations
		$serLang = $translator->load('service', array_unique($service_ids), $langtag);
		// pre-load services groups translations
		$serGroupLang = $translator->load('group', array_unique($ser_group_ids), $langtag);
		// pre-load employees translations
		$empLang = $translator->load('employee', array_unique($employee_ids), $langtag);
		// pre-load employees groups translations
		$empGroupLang = $translator->load('empgroup', array_unique($emp_group_ids), $langtag);
		// pre-load options translations
		$optLang = $translator->load('option', array_unique($option_ids), $langtag);
		// pre-load options variations translations
		$varLang = $translator->load('optionvar', array_unique($optvar_ids), $langtag);

		// iterate appointments and apply translationss
		foreach ($this->appointments as $k => $app)
		{
			// translate service name for the given language
			$ser_tx = $serLang->getTranslation($app->service->id, $langtag);

			if ($ser_tx)
			{
				$app->service->name        = $ser_tx->name;
				$app->service->description = $ser_tx->description;
			}

			// translate employee name for the given language
			$emp_tx = $empLang->getTranslation($app->employee->id, $langtag);

			if ($emp_tx)
			{
				$app->employee->name = $emp_tx->nickname;
			}

			if ($app->service->group)
			{
				// translate service group for the given language
				$grp_tx = $serGroupLang->getTranslation($app->service->group->id, $langtag);

				if ($grp_tx)
				{
					$app->service->group->name = $grp_tx->name;
				}
			}

			if ($app->employee->group)
			{
				// translate employee group for the given language
				$grp_tx = $empGroupLang->getTranslation($app->employee->group->id, $langtag);

				if ($grp_tx)
				{
					$app->employee->group->name = $grp_tx->name;
				}
			}

			foreach ($app->options as $j => $opt)
			{
				// translate option name for the given language
				$opt_tx = $optLang->getTranslation($opt->id, $langtag);

				if ($opt_tx)
				{
					$opt->name = $opt_tx->name;
				}

				if ($opt->id_variation > 0)
				{
					// translate variation name for the given language
					$var_tx = $varLang->getTranslation($opt->id_variation, $langtag);

					if ($var_tx)
					{
						$opt->varName = $var_tx->name;
					}
				}

				$opt->fullName = $opt->name . ($opt->varName ? ' - ' . $opt->varName : '');

				// update option
				$app->options[$j] = $opt;
			}

			// update appointment
			$this->appointments[$k] = $app;
		}

		// translate payment if specified
		if ($this->payment)
		{
			// get payment translation
			$pay_tx = $translator->translate('payment', $this->payment->id, $langtag);

			if ($pay_tx)
			{
				// inject translation within order details
				$this->payment->name                  = $pay_tx->name;
				$this->payment->notes->beforePurchase = $pay_tx->prenote;
				$this->payment->notes->afterPurchase  = $pay_tx->note;
			}
		}

		// import custom fields loader
		VAPLoader::import('libraries.customfields.loader');

		// get relevant custom fields only
		$fields = VAPCustomFieldsLoader::getInstance()
			->translate($langtag)
			->noRequiredCheckbox()
			->noSeparator()
			->forService($service_ids);

		if (count(array_unique($employee_ids)) == 1)
		{
			$fields->ofEmployee($employee_ids[0]);
		}
			
		$cf = $fields->fetch();
		
		// translate CF data object
		$this->fields = VAPCustomFieldsLoader::translateObject($this->fields, $cf, $langtag);

		// reset display fields
		$this->displayFields = array();

		foreach ($cf as $field)
		{
			$k = $field['name'];

			if (!array_key_exists($k, $this->fields))
			{
				// field not found inside the given object, go to next one
				continue;
			}

			$v = $this->fields[$k];

			// take only if the value is not empty
			if ((is_scalar($v) && strlen($v)) || !empty($v))
			{
				// get a more readable label/text of the saved value
				$this->displayFields[$field['langname']] = $v;
			}
		}

		// translate custom fields of the attendees
		foreach ($this->attendees as $i => $attendee)
		{
			// translate CF data object
			$attFields = VAPCustomFieldsLoader::translateObject($attendee['fields'], $cf, $langtag);

			// reset display fields
			$attendee['display'] = array();

			foreach ($cf as $field)
			{
				$k = $field['name'];

				if (!array_key_exists($k, $attFields) || !$field['repeat'])
				{
					// field not found inside the given object, go to next one
					continue;
				}

				$v = $attFields[$k];

				// take only if the value is not empty
				if ((is_scalar($v) && strlen($v)) || !empty($v))
				{
					// get a more readable label/text of the saved value
					$attendee['display'][$field['langname']] = $v;
				}
			}

			$this->attendees[$i] = $attendee;
		}

		/**
		 * External plugins can use this event to apply the translations to
		 * additional details manually included within the order object.
		 *
		 * @param 	mixed   $order    The order details object.
		 * @param   string  $langtag  The requested language tag.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onTranslateAppointmentsOrderDetails', array($this, $langtag));
	}

	/**
	 * @override
	 * Returns the billing details of the user that made the order.
	 *
	 * @return 	object
	 */
	protected function getBilling()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' = ' . (int) $this->id_user)
			->orWhere(array(
				$dbo->qn('billing_mail') . ' <> ' . $dbo->q(''),
				$dbo->qn('billing_mail') . ' IS NOT NULL',
				$dbo->qn('billing_mail') . ' = ' . $dbo->q($this->purchaser_mail),
			), 'AND');

		$dbo->setQuery($q, 0, 1);
		return $dbo->loadObject() ?? false;
	}

	/**
	 * @override
	 * Returns the account details of the order author.
	 *
	 * @return 	object
	 */
	protected function getAuthor()
	{
		if ($this->createdby <= 0)
		{
			// no registered author, do not go ahead
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('name'))
			->select($dbo->qn('username'))
			->select($dbo->qn('email'))
			->from($dbo->qn('#__users'))
			->where($dbo->qn('id') . ' = ' . (int) $this->createdby);

		$dbo->setQuery($q, 0, 1);
		return $dbo->loadObject() ?? false;
	}

	/**
	 * @override
	 * Returns the invoice details of the order.
	 *
	 * @return 	mixed   The invoice object if exists, false otherwise.
	 */
	protected function getInvoice()
	{
		$id_order = $this->id;

		if ($this->id_parent != -1 && $this->id != $this->id_parent)
		{
			$id_order = $this->id_parent;
		}

		// load invoice details
		return JModelVAP::getInstance('invoice')->getInvoice($id_order, 'appointments');
	}

	/**
	 * @override
	 * Returns the history of the status codes set for the order.
	 *
	 * @return 	array
	 */
	protected function getHistory()
	{
		return VAPOrderStatus::getInstance()->getOrderTrack($this->id, $locale = true);
	}

	/**
	 * @override
	 * Returns a list of notes assigned to this order.
	 *
	 * @return 	array
	 */
	protected function getNotes()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('n.*')
			->select($dbo->qn('u.name', 'authorName'))
			->from($dbo->qn('#__vikappointments_user_notes', 'n'))
			->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('u.id') . ' = ' . $dbo->qn('n.author'))
			->where($dbo->qn('n.group') . ' = ' . $dbo->q('appointments'));
		
		if (count($this->appointments) > 1)
		{
			$ids = array((int) $this->id);

			foreach ($this->appointments as $app)
			{
				$ids[] = (int) $app->id;
			}

			// load all notes assigned to the parent order and its children
			$q->where($dbo->qn('n.id_parent') . ' IN (' . implode(',', $ids) . ')');
		}
		else
		{
			// load all notes assigned to this appointment
			$q->where($dbo->qn('n.id_parent') . ' = ' . (int) $this->id);
		}

		// sort by descending modify/create date
		$q->order(sprintf(
			'IFNULL(%s, %s) %s',
			$dbo->qn('n.modifiedon'),
			$dbo->qn('n.createdon'),
			'DESC'
		));

		$dbo->setQuery($q);
		
		// load all assigned notes
		$notes = $dbo->loadObjectList();

		if (!$notes)
		{
			// no assigned notes
			return [];
		}

		// get tags model
		$tagModel = JModelVAP::getInstance('tag');

		foreach ($notes as $note)
		{
			// JSON decode attachments
			$note->attachments = $note->attachments ? (array) json_decode($note->attachments, true) : array();
			
			// get file details of the attachments
			$note->attachments = array_map(function($attachment)
			{
				$file = new stdClass;
				$file->name = basename($attachment);
				$file->path = VAPCUSTOMERS_DOCUMENTS . DIRECTORY_SEPARATOR . $attachment;
				$file->uri  = VAPCUSTOMERS_DOCUMENTS_URI . str_replace(DIRECTORY_SEPARATOR, '/', $attachment);

				return is_file($file->path) ? $file : null;
			}, $note->attachments);

			// get rid of missing files
			$note->attachments = array_values(array_filter($note->attachments));

			// get details of the specified tags
			$note->tags = $tagModel->readTags($note->tags, '*');
		}

		return $notes;
	}
}
