<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  dashboard
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.statistics.factory');
VAPLoader::import('libraries.statistics.helpers.finance');
VAPLoader::import('libraries.statistics.helpers.appointments');

/**
 * Widget used to display a financial overview within the
 * Dashboard of WordPress.
 *
 * @since 1.2
 */
class JDashboardWidgetVikAppointmentsOverview extends JDashboardWidget
{
	/**
	 * Keep a reference of the user that needs to access
	 * this kind of widget.
	 *
	 * @var JUser
	 */
	private $user;

	/**
	 * Returns the name of the widget.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return __('VikAppointments - Overview', 'vikappointments');
	}

	/**
	 * Checks whether the specified user is able to access
	 * this widget. Allow only super users.
	 *
	 * @param 	mixed 	 $user  The user to check.
	 *
	 * @return 	boolean
	 */
	public function canAccess($user = null)
	{
		if (!$user instanceof JUser)
		{
			// get user
			$user = JFactory::getUser($user);
		}

		// keep a reference
		$this->user = $user;

		// allow administrators and users that can access the appointments or financial data
		return $user->authorise('core.admin', 'com_vikappointments')
			|| $user->authorise('core.access.reservations', 'com_vikappointments')
			|| $user->authorise('core.access.analytics.finance', 'com_vikappointments');
	}

	/**
	 * Renders the HTML to display within the contents of the widget.
	 * 
	 * @param 	mixed 	$args  A registry of settings.
	 *
	 * @return 	string  The HTML to display.
	 */
	protected function renderWidget($args)
	{
		// prepare display data
		$data = array(
			'config' => $args,
			'widget' => $this,
			'user'   => $this->user ? $this->user : JFactory::getUser(),
		);

		// fetch analytics
		$this->fetchMonthTotal($data);
		$this->fetchRog($data);
		$this->fetchWeeklyChart($data);
		$this->fetchPendingCount($data);
		$this->fetchPaymentCount($data);

		// create layout file
		$layout = new JLayoutFile('html.wpdash.overview.widget', null, array('component' => 'com_vikappointments'));

		// render widget content
		return $layout->render($data);
	}

	/**
	 * Fetches the total earning of the current month.
	 *
	 * @param 	array  &$data  The display data array to fill.
	 *
	 * @return 	void
	 */
	private function fetchMonthTotal(&$data)
	{
		// back to first day of the current month
		$from = JFactory::getDate();
		$from->modify($from->format('Y-m-01 00:00:00'));

		// use the current date as delimiter, since there cannot be
		// orders in the future
		$to = JFactory::getDate();

		// define the columns to load
		$columns = ['total', 'tax', 'net'];

		// fetch revenue
		$data['monthtotal'] = VAPStatisticsHelperFinance::getTotalRevenue($from, $to, $columns);
	}

	/**
	 * Fetches the rate of growth between the current month and
	 * the previous one.
	 *
	 * @param 	array  &$data  The display data array to fill.
	 *
	 * @return 	void
	 */
	private function fetchRog(&$data)
	{
		// use the current month
		$m1 = JFactory::getDate();
		$m1 = $m1->format('Y-m');

		// back to previous month
		$m2 = JFactory::getDate();
		$m2->modify('-1 month');
		$m2 = $m2->format('Y-m');

		// fetch rog
		$data['rog'] = VAPStatisticsHelperFinance::getRog($m1, $m2, $proportional = true);
	}

	/**
	 * Instantiates the analytics widget responsible to generating a
	 * financial revenue chart.
	 *
	 * @param 	array  &$data  The display data array to fill.
	 *
	 * @return 	void
	 */
	private function fetchWeeklyChart(&$data)
	{
		// force "option" in request to allow the plugin to use the correct folder
		// from which the layout files should be loaded
		JFactory::getApplication()->input->set('option', 'com_vikappointments');

		// load employee-services revenue chart instance
		$data['chart'] = VAPStatisticsFactory::getInstance('finance_revenue_chart', [
			'range' => '-1 week',
		]);
	}

	/**
	 * Fetches total count of pending appointments.
	 *
	 * @param 	array  &$data  The display data array to fill.
	 *
	 * @return 	void
	 */
	private function fetchPendingCount(&$data)
	{
		// fetch rog
		$lookup = VAPStatisticsHelperAppointments::getStatusesCount();

		$data['pending'] = 0;

		// find all pending status codes
		$pending = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 0, 'reserved' => 1));

		foreach($pending as $code)
		{
			if (isset($lookup[$code]))
			{
				// increase pending count
				$data['pending'] += (int) $lookup[$code];
			}
		}
	}

	/**
	 * Fetches total count of appointments that require a payment.
	 *
	 * @param 	array  &$data  The display data array to fill.
	 *
	 * @return 	void
	 */
	private function fetchPaymentCount(&$data)
	{
		$dbo = JFactory::getDbo();

		$data['needpay'] = 0;

		// get any approved codes with no payment
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1, 'paid' => 0)); 

		$q = $dbo->getQuery(true);

		// count total number of appointments
		$q->select('COUNT(1)');
		$q->from($dbo->qn('#__vikappointments_reservation'));

		// take only the appointments that still have to be paid
		$q->where(sprintf('(%s - %s) > 0', $dbo->qn('total_cost'), $dbo->qn('tot_paid')));
		// take only the appointments with check-in in the past
		$q->where($dbo->qn('checkin_ts') . ' < ' . $dbo->q(JFactory::getDate()->toSql()));

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			$data['needpay'] = (int) $dbo->loadResult();
		}
	}
}
