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
 * Widget used to display a daily calendar with the booked appointments.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetDashboardAppointmentsCalendar extends VAPStatisticsWidget
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
		$form = array(
			/**
			 * The calendar date.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var date
			 */
			'date' => array(
				'type'     => 'date',
				'label'    => JText::translate('VAPMANAGESUBSCRORD3'),
				'volatile' => true,
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

		$services = array();

		// ungroup services and merge them all at the same level
		foreach ($form['services']['options'] as $group)
		{
			$services = array_merge($services, $group);
		}

		// update options attribute
		$form['services']['options'] = $services;

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
		$filters['begin']     = $this->getOption('date');
		$filters['services']  = array_values(array_filter($this->getOption('services', [])));
		$filters['employees'] = array_values(array_filter($this->getOption('employees', [])));

		$config = VAPFactory::getConfig();

		$tz = JFactory::getUser()->getTimezone();

		$today = JFactory::getDate('today 00:00:00', $tz);

		$data = array();

		if (VAPDateHelper::isNull($filters['begin']))
		{
			// use current date at midnight
			$date = $today;
		}
		else
		{
			// convert specified dates to SQL format
			$date = new JDate(VAPDateHelper::getDate($filters['begin'], 0, 0, 0), $tz);
		}

		// get military format of the selected date
		$ymd = $date->format('Y-m-d', $local = true);

		$filters['begin'] = clone $date;

		// extend date to midnight
		$filters['end'] = clone $filters['begin'];
		$filters['end']->modify('23:59:59');

		$filters['begin'] = $filters['begin']->toSql(true);
		$filters['end']   = $filters['end']->toSql(true);

		// get model used to handle the daily calendar data
		$model = JModelVAP::getInstance('caldays');

		// load matching appointments
		$data['appointments'] = $model->getAppointments($filters);

		$data['min'] = 23;
		$data['max'] = 0;

		// map appointments
		foreach ($data['appointments'] as $app)
		{
			$date = new JDate($app->checkin_ts);
			$date->setTimezone($tz);

			$checkin = new stdClass;
			$checkin->date = $date->toISO8601($local = true);
			$checkin->ymd  = $date->format('Y-m-d', $local = true);
			$checkin->hour = (int) $date->format('G', $local = true);
			$checkin->min  = (int) $date->format('i', $local = true);
			$checkin->time = $date->format($config->get('timeformat'), $local = true);

			$app->checkin = $checkin;

			$date->modify('+' . $app->duration . ' minutes');

			$checkout = new stdClass;
			$checkout->date = $date->toISO8601($local = true);
			$checkout->ymd  = $date->format('Y-m-d', $local = true);
			$checkout->hour = (int) $date->format('G', $local = true);
			$checkout->min  = (int) $date->format('i', $local = true);
			$checkout->time = $date->format($config->get('timeformat'), $local = true);

			$app->checkout = $checkout;

			// extend bounds if needed
			if ($checkin->ymd == $checkout->ymd)
			{
				// check-in and check-out are in the same day, compare as usual
				$data['min'] = min(array($data['min'], $checkin->hour));
				$data['max'] = max(array($data['max'], $checkout->hour));

				// allow event drag
				$app->draggable = true;
			}
			else if ($checkin->ymd < $ymd)
			{
				// we have an appointment that started on the previous day
				$data['min'] = 0;
				$data['max'] = max(array($data['max'], $checkout->hour));

				// set check-time to 00:00 for a correct rendering
				$app->checkin->hour = $app->checkin->min = 0;

				// recalculate new duration with the appointments starting at midnight
				$app->duration = VAPDateHelper::diff(
					// use check-out date, since we moved the beginning at midnight of the next day
					"{$app->checkout->ymd} {$app->checkin->hour}:{$app->checkin->min}:00",
					"{$app->checkout->ymd} {$app->checkout->hour}:{$app->checkout->min}:00",
					'minutes'
				);

				// do not allow event drag
				$app->draggable = false;
			}
			else
			{
				// we have an appointment that ends on the next day
				$data['min'] = min(array($data['min'], $checkin->hour));
				$data['max'] = 23;

				// do not allow event drag
				$app->draggable = false;
			}
		}

		if ($data['min'] > $data['max'])
		{
			// no appointments found, use default opening times
			$opening = VikAppointments::getOpeningTime();
			$closing = VikAppointments::getClosingTime();

			$data['min'] = $opening['hour'];
			$data['max'] = $closing['hour'];
		}
		else
		{
			do {
				// extend bounds by an hour, if possible
				$data['min'] = max(array( 0, $data['min'] - 1));
				$data['max'] = min(array(23, $data['max'] + 1));
				// repeat until we have at least a shift of 6 hours
			} while ($data['max'] - $data['min'] < 6);
		}

		// check if we are seeing the current date
		$data['today'] = $ymd == $today->format('Y-m-d', $local = true);
		// get current date time
		$data['now'] = JFactory::getDate('now', $tz)->toISO8601($local = true);

		// get previous day (adjusted to our internal format)
		$data['prev'] = JFactory::getDate($filters['begin']);
		$data['prev']->modify('-1 day');
		$data['prev'] = $data['prev']->format($config->get('dateformat'));

		// get next day (adjusted to our internal format)
		$data['next'] = JFactory::getDate($filters['begin']);
		$data['next']->modify('+1 day');
		$data['next'] = $data['next']->format($config->get('dateformat'));

		return $data;
	}
}
