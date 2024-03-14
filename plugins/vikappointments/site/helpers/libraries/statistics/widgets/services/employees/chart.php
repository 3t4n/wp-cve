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
 * Widget used to draw a trend chart of the employees assigned to a specific service.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetServicesEmployeesChart extends VAPStatisticsWidget
{
	/**
	 * Internal flag used to check whether the current user
	 * owns enough permissions to access the financial data.
	 *
	 * Use true by default in case the widget is displayed
	 * without checking the permissions.
	 *
	 * @var boolean
	 */
	protected $hasFinanceAccess = true;

	/**
	 * Checks whether the specified group is supported by the widget.
	 *
	 * @param 	string 	 $group  The group to check.
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public function isSupported($group)
	{
		return empty($group) || $group == '*' || $group == 'dashboard' || $group == 'services';
	}

	/**
	 * Checks whether the specified user is capable to access this widget.
	 *
	 * @param 	JUser    $user  The user instance.
	 *
	 * @return 	boolean  True if capable, false otherwise.
	 */
	public function checkPermissions($user)
	{
		// detected finance visibility
		$this->hasFinanceAccess = $user->authorise('core.access.analytics.finance', 'com_vikappointments');

		return $user->authorise('core.access.analytics.services', 'com_vikappointments');
	}

	/**
	 * Returns to the caller the financial rights of the user.
	 *
	 * @return 	boolean  True if capable, false otherwise.
	 */
	public function hasFinanceAccess()
	{
		return $this->hasFinanceAccess;
	}

	/**
	 * Override this method to return a configuration form of the widget.
	 *
	 * @return 	array
	 */
	public function getForm()
	{
		$form = array(
			/**
			 * The initial date to take when a new session starts.
			 *
			 * @var select
			 */
			'range' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAP_CHART_INITIAL_RANGE_FIELD'),
				'help'     => JText::translate('VAP_CHART_INITIAL_RANGE_FIELD_HELP'),
				'default'  => '-6 months',
				'options'  => array(
					'-1 week'    => JText::plural('VAP_LAST_N_WEEKS', 1),
					'-2 weeks'   => JText::plural('VAP_LAST_N_WEEKS', 2),
					'-1 month'   => JText::plural('VAP_LAST_N_MONTHS', 1),
					'-3 months'  => JText::plural('VAP_LAST_N_MONTHS', 3),
					'-6 months'  => JText::plural('VAP_LAST_N_MONTHS', 6),
					'-9 months'  => JText::plural('VAP_LAST_N_MONTHS', 9),
					'-12 months' => JText::plural('VAP_LAST_N_MONTHS', 12),
				),
			),
				
			/**
			 * The initial date of the range.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var date
			 */
			'datefrom' => array(
				'type'     => 'date',
				'label'    => JText::translate('VAPEXPORTRES3'),
				'volatile' => true,
			),

			/**
			 * The ending date of the range.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var date
			 */
			'dateto' => array(
				'type'     => 'date',
				'label'    => JText::translate('VAPEXPORTRES4'),
				'volatile' => true,
			),

			/**
			 * The type of chart to display (line or bar).
			 *
			 * @var select
			 */
			'chart' => array(
				'type'    => 'select',
				'label'   => JText::translate('VAP_CHART_TYPE'),
				'default' => 'line',
				'options' => array(
					'line' => JText::translate('VAP_CHART_TYPE_LINE'),
					'bar'  => JText::translate('VAP_CHART_TYPE_BAR'),
				),
			),

			/**
			 * The entity of the chart (reservations count or total earning).
			 *
			 * @var select
			 */
			'valuetype' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAP_CHART_VALUE_TYPE_FIELD'),
				'help'     => JText::translate('VAP_CHART_VALUE_TYPE_FIELD_HELP'),
				'default'  => 'total',
				'options'  => array(
					'total' => JText::translate('VAPREPORTSVALUETYPEOPT1'),
					'count' => JText::translate('VAPMENUTITLEHEADER2'),
				),
			),

			/**
			 * The service to track.
			 *
			 * @var select
			 */
			'service' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMANAGERESERVATION4'),
				'default'  => 0,
				'options'  => JHtml::fetch('vaphtml.admin.services', $strict = false, $blank = false, $group = true),
			),

			/**
			 * Flag used to fetch the appointments by check-in or creation.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var hidden
			 */
			'checkin' => array(
				'type'     => 'hidden',
				'default'  => true,
				'volatile' => true,
			),

			/**
			 * Flag used to filter the appointments by employee.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var hidden
			 */
			'employees' => array(
				'type'     => 'hidden',
				'default'  => null,
				'volatile' => true,
			),
		);

		// check whether the user owns enough permissions to see financial data
		if (!$this->hasFinanceAccess)
		{
			// change type to hidden to avoid its selection
			$form['valuetype']['type'] = 'hidden';
			// display by default the total number of appointments
			$form['valuetype']['default'] = 'count';
		}

		$services = array();

		// ungroup services and merge them all at the same level
		foreach ($form['service']['options'] as $group)
		{
			$services = array_merge($services, $group);
		}

		// update options attribute
		$form['service']['options'] = $services;

		if ($services)
		{
			// update default value with first selected service
			$form['service']['default'] = $services[0]->value;
		}

		return $form;
	}

	/**
	 * Checks whether the widget is able to export the fetched data.
	 *
	 * @return 	array  A list of supported exportable functions.
	 */
	public function isExportable()
	{
		return ['export', 'print'];
	}

	/**
	 * Loads the dataset(s) that will be recovered asynchronously
	 * for being displayed within the widget.
	 *
	 * It is possible to return an array of records to be passed
	 * to a chart or directly the HTML to replace.
	 *
	 * @return 	mixed
	 */
	public function getData()
	{
		$filters = array();
		$filters['service']   = $this->getOption('service');
		$filters['valuetype'] = $this->getOption('valuetype', 'total');
		$filters['datefrom']  = $this->getOption('datefrom');
		$filters['dateto']    = $this->getOption('dateto');
		$filters['range']     = $this->getOption('range', '-6 months');
		$filters['checkin']   = $this->getOption('checkin', true);
		$filters['employees'] = $this->getOption('employees', null);

		if (!$this->hasFinanceAccess)
		{
			// always display the total number of appointments in case
			// the user cannot access financial data
			$filters['valuetype'] = 'count';
		}

		$tz = JFactory::getUser()->getTimezone();

		// use default range in case one of the specified dates is empty
		if (VAPDateHelper::isNull($filters['datefrom']) || VAPDateHelper::isNull($filters['dateto']))
		{
			// create current date by using the timezone of the logged-in user
			$filters['dateto'] = JFactory::getDate('now', $tz);
			// set ending date to current one, 1 second before tomorrow
			$filters['dateto']->modify('23:59:59');

			if (strpos($filters['range'], 'months') !== false)
			{
				// set ending date to the end of the month
				$filters['dateto']->modify($filters['dateto']->format('Y-m-t'));
			}

			// set starting date to current one at midnight
			$filters['datefrom'] = clone $filters['dateto'];
			// go to next day to properly calculate the starting date
			$filters['datefrom']->modify('tomorrow 00:00:00');
			// subtract given range
			$filters['datefrom']->modify($filters['range']);
		}
		else
		{
			// convert specified dates to SQL format
			$filters['datefrom'] = new JDate(VAPDateHelper::getDate($filters['datefrom'], 0, 0, 0), $tz);
			$filters['dateto']   = new JDate(VAPDateHelper::getDate($filters['dateto'], 23, 59, 59), $tz);
		}

		// temporary option that can be used to extend the date format at runtime
		$extended = $this->getOption('extended', false);

		// import services helper
		VAPLoader::import('libraries.statistics.helpers.services');
		// fetch trend of the employees assigned to the selected service
		$data = VAPStatisticsHelperServices::getEmployeesTrend($filters['datefrom'], $filters['dateto'], $filters['valuetype'], $filters['service'], $filters['checkin'], $filters['employees'], $extended);

		return $data;
	}

	/**
	 * Create adapter for export method.
	 * This widget only supports "print" export function.
	 *
	 * @param 	mixed  $rule  The requested export type.
	 *
	 * @return 	mixed  The file path in case of "file" export rule.
	 */
	public function export($rule = null)
	{
		if ($rule == 'print')
		{
			// auto-print the document
			JHtml::fetch('vaphtml.sitescripts.winprint', 256);

			// display widget with fetched data
			echo $this->display([
				// auto-fetch the widget data
				'data' => $this->getData(),
				// always display the chart legend
				'legend' => true,
			]);
		}
		else
		{
			// temporarily obtain all the columns and take an extended date format
			$this->setOption('valuetype', ['total', 'tax', 'net', 'count']);
			$this->setOption('extended', true);

			// load service details
			$service = JModelVAP::getInstance('service')->getItem((int) $this->getOption('service'));

			if (!$service)
			{
				throw new Exception('Service not found', 404);
			}

			// auto-fetch the widget data
			$data = $this->getData();

			$app = JFactory::getApplication();

			$path = null;

			// create file name
			$filename = JFilterOutput::stringURLSafe($service->name);

			if ($rule != 'file')
			{
				// send headers for CSV download
				$app->setHeader('Cache-Control', 'no-store, no-cache');
				$app->setHeader('Content-Type', 'text/csv; charset=UTF-8');
				$app->setHeader('Content-Disposition', 'attachment; filename="' . htmlspecialchars($filename) . '.csv"');
				$app->sendHeaders();

				// send bytes through PHP output
				$handle = fopen('php://output', 'w');
			}
			else
			{
				// create file path
				$path = rtrim($app->get('tmp_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

				$count = 1;

				// append a counter next to the file name as long as the file already exists
				while (is_file($path . $filename . ($count > 1 ? '-' . $count : '') . '.csv'))
				{
					$count++;
				}

				// create path
				$path = $path . $filename . ($count > 1 ? '-' . $count : '') . '.csv';

				// send bytes through file
				$handle = fopen($path, 'w');
			}

			// create table heading
			$head = array(
				// date
				JText::translate('VAPMANAGEREVIEW4'),
				// employee
				JText::translate('VAPMANAGERESERVATION3'),
				// total gross
				JText::translate('VAPTOTALGROSS'),
				// total tax
				JText::translate('VAPTOTALTAX'),
				// total net
				JText::translate('VAPTOTALNET'),
				// count
				JText::translate('VAPCALSTATTHEAD3'),
			);

			// include table heading
			fputcsv($handle, $head, ',', '"');

			$currency = VAPFactory::getCurrency();

			$sumTotal = $sumTax = $sumNet = $sumCount = 0;

			// iterate all records
			foreach ($data as $date => $employees)
			{
				$row = array(
					// date
					$date,
					// employee
					'',
					// total gross
					'',
					// total tax
					'',
					// total net
					'',
					// count
					'',
				);

				// include table row
				fputcsv($handle, $row, ',', '"');

				$subTotal = $subTax = $subNet = $subCount = 0;

				foreach ($employees as $employee)
				{
					$row = array(
						// date
						'',
						// employee
						$employee['name'],
						// total gross
						$currency->format($employee['total']),
						// total tax
						$currency->format($employee['tax']),
						// net
						$currency->format($employee['net']),
						// count
						$employee['count'],
					);

					// sum totals
					$subTotal += $employee['total'];
					$subTax   += $employee['tax'];
					$subNet   += $employee['net'];
					$subCount += $employee['count'];

					// include table row
					fputcsv($handle, $row, ',', '"');
				}

				// sum grand totals
				$sumTotal += $subTotal;
				$sumTax   += $subTax;
				$sumNet   += $subNet;
				$sumCount += $subCount;

				$row = array(
					// date
					'',
					// employee
					'',
					// total gross
					$currency->format($subTotal),
					// total tax
					$currency->format($subTax),
					// total net
					$currency->format($subNet),
					// count
					$subCount,
				);

				// include table row
				fputcsv($handle, $row, ',', '"');
			}

			$row = array(
				// date
				'',
				// employee
				'',
				// total gross
				$currency->format($sumTotal),
				// total tax
				$currency->format($sumTax),
				// total net
				$currency->format($sumNet),
				// count
				$sumCount,
			);

			// include table row
			fputcsv($handle, $row, ',', '"');

			fclose($handle);

			if ($rule != 'file')
			{
				// terminate session in case of download
				$app->close();
			}

			// return file path otherwise
			return $path;
		}
	}
}
