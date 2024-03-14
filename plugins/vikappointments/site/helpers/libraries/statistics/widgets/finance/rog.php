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
 * Widget used to fetch the Rate of Growth between 2 months.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetFinanceRog extends VAPStatisticsWidget
{
	/**
	 * Checks whether the specified group is supported by the widget.
	 *
	 * @param 	string 	 $group  The group to check.
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public function isSupported($group)
	{
		return empty($group) || $group == '*' || $group == 'dashboard' || $group == 'finance';
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
		return $user->authorise('core.access.analytics.finance', 'com_vikappointments');
	}

	/**
	 * Override this method to return a configuration form of the widget.
	 *
	 * @return 	array
	 */
	public function getForm()
	{
		return array(
			/**
			 * The first month to fetch.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var select
			 */
			'month1' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMANAGERESTRINTERVALMONTH') . ' #1',
				'default'  => JHtml::fetch('date', 'now', 'n'),
				'volatile' => true,
				'options'  => JHtml::fetch('vikappointments.months'),
			),

			/**
			 * The first year to which the selected month belongs.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var select
			 */
			'year1' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMANAGERESTRINTERVALYEAR') . ' #1',
				'default'  => JHtml::fetch('date', 'now', 'Y'),
				'volatile' => true,
				'options'  => JHtml::fetch('vikappointments.years', -10, 0),
			),

			/**
			 * The second month to fetch.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var select
			 */
			'month2' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMANAGERESTRINTERVALMONTH') . ' #2',
				'default'  => JHtml::fetch('date', '-1 month', 'n'),
				'volatile' => true,
				'options'  => JHtml::fetch('vikappointments.months'),
			),

			/**
			 * The first year to which the selected month belongs.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var select
			 */
			'year2' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAPMANAGERESTRINTERVALYEAR') . ' #2',
				'default'  => JHtml::fetch('date', '-1 month', 'Y'),
				'volatile' => true,
				'options'  => JHtml::fetch('vikappointments.years', -10, 0),
			),

			/**
			 * When enabled, the total earning of the month will be proportionally
			 * estimated depending on the money already earned and the remaining
			 * days (applies only for the current month).
			 *
			 * @var checkbox
			 */
			'prop' => array(
				'type'    => 'checkbox',
				'label'   => JText::translate('VAP_STATS_WIDGET_FINANCE_ROG_PROP_FIELD'),
				'help'    => JText::translate('VAP_STATS_WIDGET_FINANCE_ROG_PROP_FIELD_HELP'),
				'default' => true,
			),
		);
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
		// fetch selected ranges
		$a = $this->getOption('year1') . '-' . $this->getOption('month1');
		$b = $this->getOption('year2') . '-' . $this->getOption('month2');

		// import finance helper
		VAPLoader::import('libraries.statistics.helpers.finance');
		// fetch rog
		$data = VAPStatisticsHelperFinance::getRog($a, $b, $this->getOption('prop'));

		// replicate dates
		$data['month1'] = $a;
		$data['month2'] = $b;

		return $data;
	}
}
