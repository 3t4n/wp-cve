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
 * Widget used to draw a trend chart of the services.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetServicesRevenueChart extends VAPStatisticsWidget
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
			 * Select a group to automatically track all the assigned services.
			 *
			 * @var select
			 */
			'group' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMANAGESERVICE10'),
				'help'     => JText::translate('VAP_STATS_WIDGET_SERVICES_REVENUE_CHART_GROUP_FIELD_HELP'),
				'options'  => JHtml::fetch('vaphtml.admin.groups', $services = 1, $blank = true),
			),

			/**
			 * A list of services to track.
			 *
			 * @var select
			 */
			'services' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMENUSERVICES'),
				'multiple' => true,
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
		foreach ($form['services']['options'] as $group)
		{
			$services = array_merge($services, $group);
		}

		// update options attribute
		$form['services']['options'] = $services;

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
		$filters['services']  = array_values(array_filter($this->getOption('services', [])));
		$filters['valuetype'] = $this->getOption('valuetype', 'total');
		$filters['datefrom']  = $this->getOption('datefrom');
		$filters['dateto']    = $this->getOption('dateto');
		$filters['range']     = $this->getOption('range', '-6 months');

		if ($filters['group'])
		{
			$dbo = JFactory::getDbo();

			// filter specified, take all services under this group
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id'))
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id_group') . ' = ' . (int) $filters['group']);

			$dbo->setQuery($q);

			if ($services = $dbo->loadColumn())
			{
				// merge with selected services
				$filters['services'] = array_merge($filters['services'], $services);
				// get rid of duplicates
				$filters['services'] = array_values(array_unique($filters['services']));
			}
		}

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

		// import services helper
		VAPLoader::import('libraries.statistics.helpers.services');
		// fetch services trend
		$data = VAPStatisticsHelperServices::getTrend($filters['datefrom'], $filters['dateto'], $filters['valuetype'], $filters['services']);

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
