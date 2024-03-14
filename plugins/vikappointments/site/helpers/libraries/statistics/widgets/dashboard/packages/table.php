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
 * Widget used to fetch the latest purchased and redeemed packages.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetDashboardPackagesTable extends VAPStatisticsWidget
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
		return empty($group) || $group == '*' || $group == 'dashboard';
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
		return $user->authorise('core.access.dashboard', 'com_vikappointments');
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
			 * The maximum number of items to load.
			 *
			 * @var select
			 */
			'limit' => array(
				'type'    => 'select',
				'label'   => JText::translate('VAPMANAGECONFIG80'),
				'default' => 5,
				'options' => array(
					5,
					10,
					15,
					20,
				),
			),

			/**
			 * Flag used to check whether the "latest ordered" table
			 * should be displayed or not.
			 *
			 * @var checkbox
			 */
			'purchased' => array(
				'type'    => 'checkbox',
				'label'   => JText::translate('VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_PURCHASED_FIELD'),
				'help'    => JText::translate('VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_PURCHASED_FIELD_HELP'),
				'default' => true, 
			),

			/**
			 * Flag used to check whether the "latest used" table
			 * should be displayed or not.
			 *
			 * @var checkbox
			 */
			'redeemed' => array(
				'type'    => 'checkbox',
				'label'   => JText::translate('VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_REDEEMED_FIELD'),
				'help'    => JText::translate('VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_REDEEMED_FIELD_HELP'),
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
		$data = array();

		// get maximum number of items to display
		$limit = $this->getOption('limit', 5);

		// import dashboard helper
		VAPLoader::import('libraries.statistics.helpers.dashboard');

		// check whether the latest purchased should be displayed
		if ($this->getOption('purchased', true))
		{
			$data['purchased'] = VAPStatisticsHelperDashboard::getLatestPurchasedPackages($limit);
		}

		// check whether the latest redeemed packages should be displayed
		if ($this->getOption('redeemed', true))
		{
			$data['redeemed'] = VAPStatisticsHelperDashboard::getLatestUsedPackages($limit);
		}

		foreach ($data as $layoutId => $rows)
		{
			// define args for layout file
			$args = array(
				'packages' => $rows,
				'widget'   => $this,
			);

			// replace packages list with HTML layout
			$data[$layoutId] = JLayoutHelper::render('statistics.widgets.dashboard.packages.table.' . $layoutId, $args);
		}

		return $data;
	}
}
