<?php
/**
 * File responsible for defining Emails model methods.
 *
 * Author:          Uriahs Victor
 * Created on:      21/08/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Models
 */
namespace Lpac_DPS\Models\Plugin_Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Models\BaseModel;

/**
 * Class responsible for defining E-mail Model methods.
 *
 * @package Lpac_DPS\Models\Plugin_Settings
 * @since 1.1.0
 */
class Emails extends BaseModel {

	/**
	 * Check if E-mail Reminders feature is turned on.
	 *
	 * As long as any of the options are enabled then this should return true.
	 *
	 * @return bool
	 * @since 1.1.0
	 */
	public static function emailRemindersEnabled(): bool {
		return ( self::deliveryReminderEnabled() || self::pickupReminderEnabled() );
	}

	/**
	 * Check if email delivery reminder enabled.
	 *
	 * @return bool
	 * @since 1.1.0
	 */
	public static function deliveryReminderEnabled(): bool {
		$value = self::get_setting( 'emails__delivery_reminders' )['enable_delivery_reminder_feature'] ?? false;
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * How long before the delivery should the email reminder be sent.
	 *
	 * @return int
	 * @since 1.1.0
	 */
	public static function deliveryReminderTimeBefore(): int {
		return (int) ( self::get_setting( 'emails__delivery_reminders' )['time_before'] ?? 0 );
	}

	/**
	 * Should the order details be included in the reminder email.
	 *
	 * @return int
	 * @since 1.1.0
	 */
	public static function deliveryReminderIncludeOrderDetails(): bool {
		$value = self::get_setting( 'emails__delivery_reminders' )['include_order_details'] ?? false;
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the email subject.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function deliveryReminderEmailSubject(): string {
		return self::get_setting( 'emails__delivery_reminders' )['email_subject'] ?? '';
	}

	/**
	 * Get email heading.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function deliveryReminderEmailHeading(): string {
		return self::get_setting( 'emails__delivery_reminders' )['email_heading'] ?? '';
	}

	/**
	 * Get email body.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function deliveryReminderEmailBody(): string {
		return self::get_setting( 'emails__delivery_reminders' )['email_body'] ?? '';
	}

	/**
	 * Check if email pickup reminder enabled.
	 *
	 * @return bool
	 * @since 1.1.0
	 */
	public static function pickupReminderEnabled(): bool {
		$value = self::get_setting( 'emails__pickup_reminders' )['enable_pickup_reminder_feature'] ?? false;
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * How long before the delivery should the email reminder be sent.
	 *
	 * @return int
	 * @since 1.1.0
	 */
	public static function pickupReminderTimeBefore(): int {
		return (int) ( self::get_setting( 'emails__pickup_reminders' )['time_before'] ?? 0 );
	}

	/**
	 * Should the order details be included in the reminder email.
	 *
	 * @return int
	 * @since 1.1.0
	 */
	public static function pickupReminderIncludeOrderDetails(): bool {
		$value = self::get_setting( 'emails__pickup_reminders' )['include_order_details'] ?? false;
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the email subject.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function pickupReminderEmailSubject(): string {
		return self::get_setting( 'emails__pickup_reminders' )['email_subject'] ?? '';
	}

	/**
	 * Get email heading.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function pickupReminderEmailHeading(): string {
		return self::get_setting( 'emails__pickup_reminders' )['email_heading'] ?? '';
	}

	/**
	 * Get email body.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function pickupReminderEmailBody(): string {
		return self::get_setting( 'emails__pickup_reminders' )['email_body'] ?? '';
	}
}
