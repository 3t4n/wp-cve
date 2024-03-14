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
 * Widget used to draw a doughnut chart containing the total revenue of the employees
 * assigned to a specific service.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetServicesEmployeesCount extends VAPStatisticsWidget
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
		$filters['service']   = $this->getOption('service');
		$filters['valuetype'] = $this->getOption('valuetype', 'total');
		$filters['datefrom']  = $this->getOption('datefrom');
		$filters['dateto']    = $this->getOption('dateto');
		$filters['range']     = $this->getOption('range', '-6 months');

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

		// import services helper
		VAPLoader::import('libraries.statistics.helpers.services');
		// fetch count of employees assigned to the selected service
		$data = VAPStatisticsHelperServices::getEmployeesCount($filters['datefrom'], $filters['dateto'], $filters['valuetype'], $filters['service']);

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

		$data = array(
			// auto-fetch the widget data
			'data' => $this->getData(),
			// always display chart legend
			'legend' => true,
		);

		// display widget with fetched data
		echo $this->display($data);
	}
}
