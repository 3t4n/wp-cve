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
 * Driver class used to export the orders/appointments in CSV format.
 *
 * @since 1.7
 */
class VAPOrderExportDriverCsv extends VAPOrderExportDriver
{
	/**
	 * A list of custom fields.
	 *
	 * @var array
	 */
	private $customFields;

	/**
	 * The timezone to use according to the fetched entity.
	 *
	 * @var DateTimeZone
	 */
	private $timezone;

	/**
	 * @override
	 * Builds the form parameters required to the CSV driver.
	 *
	 * @return 	array
	 */
	protected function buildForm()
	{
		return array(
			/**
			 * Choose whether only the confirmed orders will be retrieved.
			 *
			 * @var checkbox
			 */
			'confirmed' => array(
				'type'    => 'checkbox',
				'label'   => JText::translate('VAP_EXPORT_DRIVER_CSV_CONFIRMED_STATUS_FIELD'),
				'help'    => JText::translate('VAP_EXPORT_DRIVER_CSV_CONFIRMED_STATUS_FIELD_HELP'),
				'default' => 1,
			),

			/**
			 * Choose whether the order items should be retrieved and included
			 * within the CSV.
			 *
			 * @var checkbox
			 */
			'useitems' => array(
				'type'    => 'checkbox',
				'label'   => JText::translate('VAP_EXPORT_DRIVER_CSV_USE_ITEMS_FIELD'),
				'help'    => JText::translate('VAP_EXPORT_DRIVER_CSV_USE_ITEMS_FIELD_HELP'),
				'default' => 0,
			),

			/**
			 * The separator character that will be used to separate the value
			 * of the columns.
			 *
			 * @var select
			 */
			'delimiter' => array(
				'type'    => 'select',
				'label'   => JText::translate('VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD'),
				'help'    => JText::translate('VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD_HELP'),
				'default' => ',',
				'options' => array(
					',' => JText::translate('VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD_OPT_COMMA'),
					';' => JText::translate('VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD_OPT_SEMICOLON'),
				),
			),

			/**
			 * The enclosure character that will be used to wrap, and escape,
			 * the value of the columns.
			 *
			 * @var select
			 */
			'enclosure' => array(
				'type'    => 'select',
				'label'   => JText::translate('VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD'),
				'help'    => JText::translate('VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD_HELP'),
				'default' => '"',
				'options' => array(
					'"'  => JText::translate('VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD_OPT_DOUBLE_QUOTE'),
					'\'' => JText::translate('VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD_OPT_SINGLE_QUOTE'),
				),
			),
		);
	}

	/**
	 * @override
	 * Exports the reservations in the given format.
	 *
	 * @return 	string 	The resulting export string.
	 */
	public function export()
	{
		// start catching output buffer
		ob_start();

		// open file resource pointing to PHP OUTPUT
		$handle = fopen('php://output', 'w');
		
		// output CSV to the given resource
		$this->output($handle);

		// catch buffer
		$buffer = ob_get_contents();
		
		// close resource
		fclose($handle);

		// close output buffer
		ob_end_clean();

		// strip trailing new line and return CSV string
		return trim($buffer, "\n");
	}

	/**
	 * @override
	 * Downloads the reservations in a file compatible with the given format.
	 *
	 * @param 	string 	$filename 	The name of the file that will be downloaded.
	 *
	 * @return 	void
	 *
	 * @uses 	export()
	 */
	public function download($filename = null)
	{
		if ($filename)
		{
			// strip file extension
			$filename = preg_replace("/\.csv$/i", '', $filename);
		}
		else
		{
			// use current date time as name
			$filename = JHtml::fetch('date', 'now', 'Y-m-d H_i_s');
		}

		$app = JFactory::getApplication();

		// prepare headers
		$this->prepareDownload($app, $filename);

		// send headers
		$app->sendHeaders();

		// open file resource pointing to PHP OUTPUT
		$handle = fopen('php://output', 'w');
		
		// output CSV to the given resource
		$this->output($handle);
		
		// close resource
		fclose($handle);
	}

	/**
	 * Prepares the application headers to start the download.
	 *
	 * @param 	mixed   $app       The client application.
	 * @param 	string 	$filename  The name of the file that will be downloaded.
	 *
	 * @return 	void 
	 */
	protected function prepareDownload($app, $filename)
	{
		// prepare headers
		$app->setHeader('Cache-Control', 'no-store, no-cache');
		$app->setHeader('Content-Type', 'text/csv; charset=UTF-8');
		$app->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
	}

	/**
	 * Generates the CSV structure by putting the fetched
	 * bytes into the specified resource.
	 *
	 * @param 	mixed 	$handle  The resource pointer created with fopen().
	 *
	 * @return 	void
	 */
	protected function output($handle)
	{
		if (!$handle)
		{
			throw new RuntimeException('Invalid resource for CSV generation');
		}

		$dispatcher = VAPFactory::getEventDispatcher();

		// fetch timezone
		if ($this->getOption('admin'))
		{
			$id_emp = (int) $this->getOption('id_employee');

			if ($id_emp > 0)
			{
				// use employee timezone
				$this->timezone = JModelVAP::getInstance('employee')->getTimezone($id_emp);
			}
			else
			{
				// use system timezone for admin
				$this->timezone = JFactory::getApplication()->get('offset', 'UTC');
			}

			$this->timezone = new DateTimeZone($this->timezone);
		}
		else
		{
			// use timezone of currently logged-in user
			$this->timezone = JFactory::getUser()->getTimezone();
		}

		// retrieve settings
		$delimiter = $this->getOption('delimiter', ',');
		$enclosure = $this->getOption('enclosure', '"');

		$records = $this->getRecords();

		/**
		 * Take all the exported services to make sure we are properly
		 * obtaining all the custom fields.
		 * 
		 * @since 1.7.4
		 */
		$all_services = array_map(function($record)
		{
			return (int) $record->id_service;
		}, $records);

		// load custom fields
		VAPLoader::import('libraries.customfields.loader');
		$this->customFields = VAPCustomFieldsLoader::getInstance()
			->noRequiredCheckbox()
			->noInputFile()
			->noSeparator()
			->forService($all_services)
			->translate()
			->fetch();

		// creates the CSV header
		$head = $this->createHead();

		// put head within the CSV
		$this->putRow($handle, $head, $delimiter, $enclosure);

		// iterate records and create arrays CSV-compatible
		foreach ($records as $data)
		{
			// create CSV row
			$row = $this->createRow($data);

			// put records within the CSV
			$this->putRow($handle, $row, $delimiter, $enclosure);
		}

		/**
		 * Trigger event to allow the plugins to append additional rows
		 * within the CSV file.
		 *
		 * @param 	array   $records  An array of database records.
		 * @param 	mixed   $handler  The current handler instance.
		 *
		 * @return 	array   The rows to include. Must be an array of arrays.
		 *
		 * @since   1.7
		 */
		$results = $dispatcher->trigger('onAfterBuildRowsCSV', array($records, $this));

		// iterate plugin results
		foreach ($results as $res)
		{
			// iterate result rows
			foreach ($res as $row)
			{
				if (is_array($row))
				{
					// put record within the CSV
					$this->putRow($handle, $row, $delimiter, $enclosure);
				}
			}
		}
	}

	/**
	 * Inserts the row within the CSV file.
	 *
	 * @param 	mixed 	$handle     The resource pointer created with fopen().
	 * @param 	array   $row        The row to include.
	 * @param 	mixed   $delimiter  The delimiter used to separate the columns
	 * @param 	mixed   $enclosure  The enclosure used to wrap the values.
	 * 
	 * @return 	void
	 */
	protected function putRow($handle, $row, $delimiter = null, $enclosure = null)
	{
		fputcsv($handle, $row, $delimiter, $enclosure);
	}

	/**
	 * Creates the CSV table header.
	 *
	 * @return 	array
	 */
	protected function createHead()
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$head = array();

		// order number
		$head['id'] = JText::translate('VAPMANAGERESERVATION0');
		// order key
		$head['sid'] = JText::translate('VAPMANAGERESERVATION2');
		// created on
		$head['date'] = JText::translate('VAPMANAGEMEDIA14');

		if ($this->isGroup('appointment'))
		{
			// service
			$head['service'] = JText::translate('VAPMANAGERESERVATION4');
			// employee
			$head['employee'] = JText::translate('VAPMANAGERESERVATION3');
			// check-in
			$head['checkin'] = JText::translate('VAPMANAGERESERVATION26');
			// duration
			$head['duration'] = JText::translate('VAPMANAGERESERVATION10');
			// people
			$head['people'] = JText::translate('VAPMANAGERESERVATION25');
		}

		// total net
		$head['net'] = JText::translate('VAPINVTOTAL');
		// total tax
		$head['tax'] = JText::translate('VAPINVTAXES');
		// total cost
		$head['gross'] = JText::translate('VAPMANAGERESERVATION9');
		// discount
		$head['discount'] = JText::translate('VAPMANAGEPACKAGE13');
		// payment
		$head['payment'] = JText::translate('VAPMANAGERESERVATION13');
		// coupon
		$head['coupon'] = JText::translate('VAPMANAGERESERVATION21');
		// status
		$head['status'] = JText::translate('VAPMANAGERESERVATION19');
		// purchaser nominative
		$head['customer'] = JText::translate('VAPMANAGERESERVATION32');
		// purchaser e-mail
		$head['email'] = JText::translate('VAPMANAGERESERVATION8');
		// purchaser phone
		$head['phone'] = JText::translate('VAPMANAGERESERVATION27');

		// iterate fields and push them within the head
		foreach ($this->customFields as $field)
		{
			// exclude custom fields that are already displayed by
			// using the purchaser information
			if (!in_array($field['rule'], array('nominative', 'email', 'phone')))
			{
				$head['cf' . $field['id']] = $field['langname'];
			}
		}

		// check if the items should be included
		if ($this->getOption('useitems'))
		{
			if ($this->isGroup('appointment'))
			{
				// extra options
				$head['items'] = JText::translate('VAPMANAGERESERVATION14');
			}
		}

		/**
		 * Trigger event to allow the plugins to manipulate the heading
		 * row of the CSV file. Here it is possible to attach new columns,
		 * detach existing columns and reorder them. Notice that the same
		 * changes must be applied to the body of the CSV, otherwise the
		 * columns might result shifted.
		 *
		 * @param 	array   &$head    The CSV head array.
		 * @param 	mixed   $handler  The current handler instance.
		 *
		 * @return 	void
		 *
		 * @since   1.7
		 */
		$dispatcher->trigger('onBuildHeadCSV', array(&$head, $this));

		// reset keys
		return array_values($head);
	}

	/**
	 * Creates a CSV table row.
	 *
	 * @param 	object  $data  The database record.
	 *
	 * @return 	array   The resulting row.
	 */
	protected function createRow($data)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$currency = VAPFactory::getCurrency();

		$row = array();

		if (VAPFactory::getConfig()->getBool('multitimezone'))
		{
			// include timezone name next to dates
			$tz_str = ' (' . $this->timezone->getName() . ')';
		}
		else
		{
			$tz_str = '';
		}

		// order number
		$row['id'] = $data->id;
		// order key
		$row['sid'] = $data->sid;
		// creation date
		$row['date'] = JHtml::fetch('date', $data->createdon, JText::translate('DATE_FORMAT_LC6'), $this->timezone->getName()) . $tz_str;

		if ($this->isGroup('appointment'))
		{
			// service
			$row['service'] = $data->service_name;
			// employee
			$row['employee'] = $data->employee_name;
			// check-in
			$row['checkin'] = JHtml::fetch('date', $data->checkin_ts, JText::translate('DATE_FORMAT_LC6'), $this->timezone->getName()) . $tz_str;
			// duration
			$row['duration'] = VikAppointments::formatMinutesToTime($data->duration);
			// people
			$row['people'] = $data->people;
		}

		// total net
		$row['net'] = $currency->format($data->total_net);
		// total tax
		$row['tax'] = $currency->format($data->total_tax);
		// total cost
		$row['gross'] = $currency->format($data->total_cost);
		// discount
		$row['discount'] = $currency->format($data->discount);

		$coupon = '';

		if ($this->isGroup('appointment'))
		{
			if ($data->coupon_str)
			{
				list($coupon_code, $coupon_type, $coupon_amount) = explode(';;', $data->coupon_str);

				$coupon = $coupon_code . ' : ' . ($coupon_type == 1 ? $coupon_amount . '%' : $currency->format($coupon_amount)); 
			}
		}

		// payments
		$row['payment'] = $data->payment_name;
		// coupon
		$row['coupon'] = $coupon;
		// status
		$row['status'] = JHtml::fetch('vaphtml.status.display', $data->status, 'plain');
		// purchaser nominative
		$row['customer'] = $data->purchaser_nominative;
		// purchaser e-mail
		$row['email'] = $data->purchaser_mail;
		// purchaser phone
		$row['phone'] = $data->purchaser_phone;

		// decode custom fields and translate values
		$cf = $data->custom_f ? (array) json_decode($data->custom_f, true) : [];
		$cf = VAPCustomFieldsLoader::translateObject($cf, $this->customFields);

		// iterate fields and push them within the head
		foreach ($this->customFields as $field)
		{
			// exclude custom fields that are already displayed by
			// using the purchaser information
			if (!in_array($field['rule'], array('nominative', 'email', 'phone')))
			{
				$row['cf' . $field['id']] = isset($cf[$field['name']]) ? $cf[$field['name']] : '';
			}
		}

		// check if the items should be included
		if ($this->getOption('useitems'))
		{
			// items
			$row['items'] = implode("\r\n", $data->items);
		}

		/**
		 * Trigger event to allow the plugins to manipulate the row that
		 * is going to be added into the CSV body. Here it is possible to
		 * attach new columns, detach existing columns and reorder them.
		 * Notice that the same changes must be applied to the head of the
		 * CSV, otherwise the columns might result shifted.
		 *
		 * @param 	array   &$row     The CSV body row.
		 * @param   object  $data     The row fetched from the database.
		 * @param 	mixed   $handler  The current handler instance.
		 *
		 * @return 	void
		 *
		 * @since   1.7
		 */
		$dispatcher->trigger('onBuildRowCSV', array(&$row, $data, $this));

		// reset keys
		return array_values($row);
	}

	/**
	 * Returns the list of records to export.
	 *
	 * @return 	array 	A list of records.
	 */
	protected function getRecords()
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo = JFactory::getDbo();

		$currency = VAPFactory::getCurrency();

		$q = $dbo->getQuery(true);

		if ($this->isGroup('appointment'))
		{
			// select all reservation columns
			$q->select('r.*');
			$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

			// get employee details
			$q->select($dbo->qn('e.nickname', 'employee_name'));
			$q->select($dbo->qn('e.timezone', 'employee_tz'));
			$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'));

			// get service details
			$q->select($dbo->qn('s.name', 'service_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'));

			// get selected payment method
			$q->select($dbo->qn('gp.name', 'payment_name'));
			$q->leftjoin($dbo->qn('#__vikappointments_gpayments', 'gp') . ' ON ' . $dbo->qn('r.id_payment') . ' = ' . $dbo->qn('gp.id'));

			// check if the items should be loaded
			if ($this->getOption('useitems'))
			{
				// get item details
				$q->select(sprintf(
					'IF(%2$s IS NOT NULL, CONCAT_WS(\' - \', %1$s, %2$s), %1$s) AS %3$s',
					$dbo->qn('o.name'),
					$dbo->qn('v.name'),
					$dbo->qn('item_name')
				));
				$q->select($dbo->qn('i.quantity', 'item_quantity'));
				$q->select($dbo->qn('i.gross', 'item_total'));
				$q->leftjoin($dbo->qn('#__vikappointments_res_opt_assoc', 'i') . ' ON ' . $dbo->qn('i.id_reservation') . ' = ' . $dbo->qn('r.id'));
				$q->leftjoin($dbo->qn('#__vikappointments_option', 'o') . ' ON ' . $dbo->qn('i.id_option') . ' = ' . $dbo->qn('o.id'));
				$q->leftjoin($dbo->qn('#__vikappointments_option_value', 'v') . ' ON ' . $dbo->qn('i.id_variation') . ' = ' . $dbo->qn('v.id'));
			}

			// DO NOT take closures
			$q->where($dbo->qn('r.closure') . ' = 0');

			// DO NOT take parent orders
			$q->where($dbo->qn('r.id_parent') . ' > 0');

			if ($this->getOption('confirmed'))
			{
				// get approved statuses
				$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1));

				if ($approved)
				{
					// filter by approved status
					$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
				}
			}

			// include records with check-in equals or higher than 
			// the specified starting date
			$from = $this->getOption('fromdate');

			if (!VAPDateHelper::isNull($from))
			{
				$q->where($dbo->qn('r.checkin_ts') . ' >= ' . $dbo->q($from));
			}

			// include records with check-in equals or lower than 
			// the specified ending date
			$to = $this->getOption('todate');

			if (!VAPDateHelper::isNull($to))
			{
				$q->where($dbo->qn('r.checkin_ts') . ' <= ' . $dbo->q($to));
			}

			// retrieve only the selected records, if any
			$ids = $this->getOption('cid');

			if ($ids)
			{
				/**
				 * The export system is now able to fetch also the appointments assigned to a parent order.
				 * 
				 * @since 1.7.4
				 */
				$q->andWhere([
					$dbo->qn('r.id') . ' IN (' . implode(',', array_map('intval', $ids)) . ')',
					$dbo->qn('r.id_parent') . ' IN (' . implode(',', array_map('intval', $ids)) . ')',
				], 'OR');
			}

			// retrieve employee filter, if any
			$id_emp = $this->getOption('id_employee');

			if ($id_emp)
			{
				$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $id_emp);
			}

			// order by ascending checkin
			$q->order($dbo->qn('r.checkin_ts') . ' ASC');
		}

		/**
		 * Trigger event to allow the plugins to manipulate the query used to retrieve
		 * a standard list of records.
		 *
		 * @param 	mixed  &$query 	 The query string or a query builder object.
		 * @param 	mixed  $options  A configuration registry.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBeforeListQueryExportCSV', array(&$q, $this->options));

		$dbo->setQuery($q);
		$list = $dbo->loadObjectList();

		if (!$list)
		{
			// no rows found
			return array();
		}

		$rows = array();

		foreach ($list as $obj)
		{
			if (!isset($rows[$obj->id]))
			{
				$rows[$obj->id] = $obj;

				$rows[$obj->id]->items = array();
			}

			// group reservation items
			if (!empty($obj->item_name))
			{
				$rows[$obj->id]->items[] = sprintf(
					"%dx %s\t(%s)",
					$obj->item_quantity,
					$obj->item_name,
					$currency->format($obj->item_total)
				);
			}
		}

		/**
		 * Trigger event to allow the plugins to manipulate response fetched by
		 * the query used to retrieve a standard list of records.
		 *
		 * @param 	mixed  &$rows 	 An array of results (objects).
		 * @param 	mixed  $options  A configuration registry.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onAfterListQueryExportCSV', array(&$rows, $this->options));

		return array_values($rows);
	}
}
