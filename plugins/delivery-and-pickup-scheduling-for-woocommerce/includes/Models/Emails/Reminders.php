<?php
/**
 * File responsible for creating Model methods for email reminders.
 *
 * Author:          Uriahs Victor
 * Created on:      17/08/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Models
 */

namespace Lpac_DPS\Models\Emails;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Helpers\Logger;
use Lpac_DPS\Helpers\Utilities;
use Lpac_DPS\Models\Plugin_Settings\Emails as EmailsSettingsModel;
use DateTime;
use Lpac_DPS\Helpers\Functions;

/**
 * Class responsible for creating Model methods to deal with Email reminders.
 *
 * @package Lpac_DPS\Models\Emails
 * @since 1.1.0
 */
class Reminders {

	/**
	 * Schedule an Email Reminder for a customer.
	 *
	 * @param array $reminder_data
	 * @return void
	 * @since 1.1.0
	 */
	public function scheduleEmailReminders( array $reminder_data ) {

		$order_type = $reminder_data['order_type'];
		$order_id   = $reminder_data['order_id'];
		$order_date = $reminder_data['date'];
		// In case no order date is given, assume that the order is taking place on the same day.
		if ( empty( $order_date ) ) {
			$order_date = Functions::getCurrentStoreDate();
		}

		// If order time is taken from a timeslot then use the starting time.
		$order_time     = Utilities::getStartTimeFromTimeSlot( $reminder_data['time'] );
		$order_datetime = trim( $order_date . ' ' . $order_time );

		if ( Functions::using24hrTime() ) {
			$formatted_date = DateTime::createFromFormat( 'Y-m-d H:i', $order_datetime, Functions::getTimezone() );
		} else {
			$formatted_date = DateTime::createFromFormat( 'Y-m-d h:i A', $order_datetime, Functions::getTimezone() );
		}

		if ( empty( $formatted_date ) ) { // We need this so that we can get the timestamp.
			return;
		}

		$timestamp = $formatted_date->getTimestamp();

		if ( empty( $timestamp ) ) {
			return;
		}

		// How many minutes before the order is due should the email be sent.
		$minutes_before = EmailsSettingsModel::{$order_type . 'ReminderTimeBefore'}();
		$timestamp      = Utilities::subtractMinutesFromTimestamp( $timestamp, $minutes_before );

		if ( ! class_exists( 'WC_Action_Queue' ) ) {
			require_once WP_PLUGIN_DIR . '/woocommerce/includes/interfaces/class-wc-queue-interface.php';
			require_once WP_PLUGIN_DIR . '/woocommerce/includes/queue/class-wc-action-queue.php';
		}

		try {
			$action_scheduler = new \WC_Action_Queue();
		} catch ( \Throwable $th ) {
			( new Logger() )->logError( 'Error scheduling email reminder: ' . $th->getMessage() );
			return;
		}

		$reminder_data['customer_email'] = wc_get_order( $order_id )->get_billing_email();

		$action_scheduler->schedule_single(
			$timestamp,
			'dps_for_wc_email_reminder',
			array(
				'reminder_data' => $reminder_data,
			),
			'dps-for-wc'
		);
	}
}
