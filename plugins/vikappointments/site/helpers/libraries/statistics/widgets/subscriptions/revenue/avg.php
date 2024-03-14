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
 * Widget class used to calculate the daily/monthly average of received subscriptions.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetSubscriptionsRevenueAvg extends VAPStatisticsWidget
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
	 * Use the layout provided by "Finance - Average Revenue" widget.
	 *
	 * @var string
	 */
	protected $layoutId = 'finance.revenue.avg';

	/**
	 * Checks whether the specified group is supported by the widget.
	 *
	 * @param 	string 	 $group  The group to check.
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public function isSupported($group)
	{
		return empty($group) || $group == '*' || $group == 'dashboard' || $group == 'subscriptions';
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

		return $user->authorise('core.access.analytics.subscriptions', 'com_vikappointments');
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
			 * How to group the records (month/day).
			 *
			 * @var select
			 */
			'groupby' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAP_CHART_GROUPBY_FIELD'),
				'default'  => 'month',
				'options'  => array(
					'day'   => JText::translate('VAPMANAGERESTRINTERVALDAY'),
					'month' => JText::translate('VAPMANAGERESTRINTERVALMONTH'),
				),
			),

			/**
			 * The entity of the chart (orders count or total earning).
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
					'count' => JText::translate('VAPREPORTSVALUETYPEOPT4'),
				),
			),
		);

		// check whether the user owns enough permissions to see financial data
		if (!$this->hasFinanceAccess)
		{
			// change type to hidden to avoid its selection
			$form['valuetype']['type'] = 'hidden';
			// display by default the total number of subscriptions
			$form['valuetype']['default'] = 'count';
		}

		return $form;
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
		$filters['groupby']   = $this->getOption('groupby', 'month');
		$filters['valuetype'] = $this->getOption('valuetype', 'total');
		$filters['datefrom']  = $this->getOption('datefrom');
		$filters['dateto']    = $this->getOption('dateto');

		if (!$this->hasFinanceAccess)
		{
			// always display the total number of subscriptions in case
			// the user cannot access financial data
			$filters['valuetype'] = 'count';
		}

		$tz = JFactory::getUser()->getTimezone();

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

		$data = array();

		// import subscriptions helper
		VAPLoader::import('libraries.statistics.helpers.subscriptions');
		// fetch subscriptions average
		$data['avg'] = VAPStatisticsHelperSubscriptions::getAvg($filters['groupby'], $filters['datefrom'], $filters['dateto'], $filters['valuetype']);

		// include formatted values
		if ($filters['valuetype'] == 'total')
		{
			$currency = VAPFactory::getCurrency();

			// format as currency per day/month
			$data['formatted'] = $currency->format(round($data['avg'], $currency->getDecimalDigits()));
			$data['formatted'] = JText::sprintf('VAP_CURRENCY_PER_' . strtoupper($filters['groupby']), $data['formatted']);
		}
		else
		{
			// format as count per day/month
			$data['formatted'] = JText::plural('VAP_N_ORDERS_PER_' . strtoupper($filters['groupby']), round($data['avg'], 0));
		}

		// include range dates
		$data['from'] = VAPDateHelper::isNull($filters['datefrom']) ? null : JHtml::fetch('date', $filters['datefrom']->format('Y-m-d', true), JText::translate('DATE_FORMAT_LC4'));
		$data['to']   = VAPDateHelper::isNull($filters['dateto'])   ? null : JHtml::fetch('date', $filters['dateto']->format('Y-m-d', true), JText::translate('DATE_FORMAT_LC4'));

		return $data;
	}
}
