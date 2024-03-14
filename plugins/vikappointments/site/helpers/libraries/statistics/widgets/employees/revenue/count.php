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
 * Widget used to draw a doughnut chart containing the total revenue of the employees.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetEmployeesRevenueCount extends VAPStatisticsWidget
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
	 * Use the layout provided by "Services - Revenue Overall" widget.
	 *
	 * @var string
	 */
	protected $layoutId = 'services.revenue.count';

	/**
	 * Checks whether the specified group is supported by the widget.
	 *
	 * @param 	string 	 $group  The group to check.
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public function isSupported($group)
	{
		return empty($group) || $group == '*' || $group == 'dashboard' || $group == 'employees';
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

		return $user->authorise('core.access.analytics.employees', 'com_vikappointments');
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
					'all'        => JText::translate('VAPMANAGECONFIGCRON4'),
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
			 * Select a group to automatically track all the assigned employees.
			 *
			 * @var select
			 */
			'group' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMANAGESERVICE10'),
				'help'     => JText::translate('VAP_STATS_WIDGET_EMPLOYEES_REVENUE_CHART_GROUP_FIELD_HELP'),
				'options'  => JHtml::fetch('vaphtml.admin.groups', $employees = 2, $blank = true),
			),

			/**
			 * A list of employees to track.
			 *
			 * @var select
			 */
			'employees' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMENUEMPLOYEES'),
				'multiple' => true,
				'options'  => JHtml::fetch('vaphtml.admin.employees', $strict = false, $blank = false),
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

		// make sure we have a list of groups
		if (count($form['group']['options']) == 1)
		{
			// nope, the list contains only the placeholder, unset parameter
			unset($form['group']);
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
		$filters['group']     = $this->getOption('group');
		$filters['employees'] = array_values(array_filter($this->getOption('employees', [])));
		$filters['valuetype'] = $this->getOption('valuetype', 'total');
		$filters['datefrom']  = $this->getOption('datefrom');
		$filters['dateto']    = $this->getOption('dateto');
		$filters['range']     = $this->getOption('range', '-6 months');

		if ($filters['group'])
		{
			$dbo = JFactory::getDbo();

			// filter specified, take all employees under this group
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id'))
				->from($dbo->qn('#__vikappointments_employee'))
				->where($dbo->qn('id_group') . ' = ' . (int) $filters['group']);

			$dbo->setQuery($q);

			if ($employees = $dbo->loadColumn())
			{
				// merge with selected employees
				$filters['employees'] = array_merge($filters['employees'], $employees);
				// get rid of duplicates
				$filters['employees'] = array_values(array_unique($filters['employees']));
			}
		}

		if (!$this->hasFinanceAccess)
		{
			// always display the total number of appointments in case
			// the user cannot access financial data
			$filters['valuetype'] = 'count';
		}

		$tz = JFactory::getUser()->getTimezone();

		// use default range in case both the specified dates are empty
		if (VAPDateHelper::isNull($filters['datefrom']) && VAPDateHelper::isNull($filters['dateto']))
		{
			if ($filters['range'] != 'all')
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
		}
		else
		{
			if (!VAPDateHelper::isNull($filters['datefrom']))
			{
				// convert specified dates to SQL format
				$filters['datefrom'] = new JDate(VAPDateHelper::getDate($filters['datefrom'], 0, 0, 0), $tz);
			}

			if (!VAPDateHelper::isNull($filters['dateto']))
			{
				// convert specified dates to SQL format
				$filters['dateto']   = new JDate(VAPDateHelper::getDate($filters['dateto'], 23, 59, 59), $tz);
			}
		}

		// import employees helper
		VAPLoader::import('libraries.statistics.helpers.employees');
		// fetch employees count
		$data = VAPStatisticsHelperEmployees::getCount($filters['datefrom'], $filters['dateto'], $filters['valuetype'], $filters['employees']);

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
			'data' => $this->getData(),
		);

		// display widget with fetched data
		echo $this->display($data);
	}
}
