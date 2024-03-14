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
 * Widget used to fetch the overall revenue coming from the selected customers.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetCustomersOverall extends VAPStatisticsWidget
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
		return empty($group) || $group == '*' || $group == 'customers';
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

		return $user->authorise('core.access.analytics.customers', 'com_vikappointments');
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
				'default'  => 'year.curr',
				'options'  => array(
					'day.curr'   => JText::translate('VAPTODAY'),
					'day.prev'   => JText::plural('VAP_LAST_N_DAYS', 1),
					'month.curr' => JText::translate('VAP_CURR_MONTH'),
					'month.prev' => JText::plural('VAP_LAST_N_MONTHS', 1),
					'year.curr'  => JText::translate('VAP_CURR_YEAR'),
					'year.prev'  => JText::plural('VAP_LAST_N_YEARS', 1),
					'all'        => JText::translate('VAPMANAGECONFIGCRON4'),
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
			 * Flag used to filter the appointments by customer.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var hidden
			 */
			'customers' => array(
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

		return $form;
	}

	/**
	 * Checks whether the widget is able to export the fetched data.
	 *
	 * @return 	array  A list of supported exportable functions.
	 */
	public function isExportable()
	{
		return ['print'];
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
		$filters['valuetype'] = $this->getOption('valuetype', 'total');
		$filters['datefrom']  = $this->getOption('datefrom');
		$filters['dateto']    = $this->getOption('dateto');
		$filters['range']     = $this->getOption('range', '-6 months');
		$filters['customers'] = $this->getOption('customers', null);

		if (!$this->hasFinanceAccess)
		{
			// always display the total number of appointments in case
			// the user cannot access financial data
			$filters['valuetype'] = 'count';
		}


		$tz = JFactory::getUser()->getTimezone();

		$summary = null;

		// use default range in case both the specified dates are empty
		if (VAPDateHelper::isNull($filters['datefrom']) && VAPDateHelper::isNull($filters['dateto']))
		{
			// init start date
			$filters['datefrom'] = JFactory::getDate('today 00:00:00', $tz);

			switch ($filters['range'])
			{
				case 'day.curr':
					// go to the end of the current day
					$filters['dateto'] = clone $filters['datefrom'];
					$filters['dateto']->modify('23:59:59');
					break;

				case 'day.prev':
					// back to the beginning of the previous day
					$filters['datefrom']->modify('-1 day');
					// go to the end of the previous day
					$filters['dateto'] = clone $filters['datefrom'];
					$filters['dateto']->modify('23:59:59');
					break;

				case 'month.curr':
					// back to the first day of the current month
					$filters['datefrom']->modify($filters['datefrom']->format('Y-m-01', true));
					// go to the last day of the current month
					$filters['dateto'] = clone $filters['datefrom'];
					$filters['dateto']->modify($filters['dateto']->format('Y-m-t', true));

					// get rid of month day
					$format = preg_replace("/[^a-z]?d[^a-z]?/", '', JText::translate('DATE_FORMAT_LC3'));
					$summary = JHtml::fetch('date', $filters['datefrom']->format('Y-m-d', true), $format);
					break;

				case 'month.prev':
					// back to the first day of the previous month
					$filters['datefrom']->modify($filters['datefrom']->format('Y-m-01', true));
					$filters['datefrom']->modify('-1 month');
					// go to the last day of the previous month
					$filters['dateto'] = clone $filters['datefrom'];
					$filters['dateto']->modify($filters['datefrom']->format('Y-m-t', true) . ' 23:59:59');

					// get rid of month day
					$format = preg_replace("/[^a-z]?d[^a-z]?/", '', JText::translate('DATE_FORMAT_LC3'));
					$summary = JHtml::fetch('date', $filters['datefrom']->format('Y-m-d', true), $format);
					break;

				case 'year.curr':
					// back to the first day of the current year
					$filters['datefrom']->modify($filters['datefrom']->format('Y-01-01', true));
					// go to the last day of the current year
					$filters['dateto'] = clone $filters['datefrom'];
					$filters['dateto']->modify($filters['dateto']->format('Y-12-31', true));

					$summary = $filters['datefrom']->format('Y', true);
					break;

				case 'year.prev':
					// back to the first day of the previous year
					$filters['datefrom']->modify($filters['datefrom']->format('Y-01-01', true));
					$filters['datefrom']->modify('-1 year');
					// go to the last day of the previous year
					$filters['dateto'] = clone $filters['datefrom'];
					$filters['dateto']->modify($filters['datefrom']->format('Y-12-31', true) . ' 23:59:59');

					$summary = $filters['datefrom']->format('Y', true);
					break;

				default:
					// do not use date filters
					$filters['datefrom'] = $filters['dateto'] = null;
			}
		}
		else
		{
			if (!VAPDateHelper::isNull($filters['datefrom']))
			{
				// convert specified date to SQL format
				$filters['datefrom'] = new JDate(VAPDateHelper::getDate($filters['datefrom'], 0, 0, 0), $tz);	
			}

			if (!VAPDateHelper::isNull($filters['dateto']))
			{
				// convert specified date to SQL format
				$filters['dateto'] = new JDate(VAPDateHelper::getDate($filters['dateto'], 23, 59, 59), $tz);
			}
		}

		// import customers helper
		VAPLoader::import('libraries.statistics.helpers.customers');
		// fetch revenue
		$data = VAPStatisticsHelperCustomers::getTotalRevenue($filters['customers'], $filters['datefrom'], $filters['dateto'], $filters['valuetype']);

		return $data;
	}

	/**
	 * Create adapter for export method.
	 * This widget only supports "print" export function.
	 *
	 * @param 	mixed  $rule  The requested export type.
	 *
	 * @return 	void
	 */
	public function export($rule = null)
	{
		// auto-print the document
		JHtml::fetch('vaphtml.sitescripts.winprint', 256);

		// auto-fetch the widget data
		$data = array(
			'data'   => $this->getData(),
			'legend' => true,
		);

		// display widget with fetched data
		echo $this->display($data);
	}
}
