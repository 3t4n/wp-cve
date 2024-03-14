<?php
#[AllowDynamicProperties]
class BWFAN_Delay_Controller extends BWFAN_Base_Step_Controller {
	public static $DELAY_DURATION = 1;
	public static $DELAY_FIXED = 2;
	public static $DELAY_CUSTOM_FIELD = 3;
	public static $DELAY_VARIABLE = 4;

	private $type = 0;
	private $data = array();

	private $is_contact_timezone = null;

	public function populate_step_data( $db_step = array() ) {
		if ( ! parent::populate_step_data( $db_step ) || ! is_array( $this->step_data['sidebarData']['data'] ) || ! isset( $this->step_data['sidebarData']['type'] ) ) {
			return false;
		}

		$this->data = $this->step_data['sidebarData']['data'];
		$this->type = absint( $this->step_data['sidebarData']['type'] );

		$this->is_contact_timezone = false;
		if ( ! isset( $this->data['enable_time_delay'] ) || empty( $this->data['enable_time_delay'] ) ) {
			return true;
		}
		if ( ! isset( $this->data['time_delay'] ) || ! isset( $this->data['time_delay']['time'] ) || empty( $this->data['time_delay']['time'] ) ) {
			return true;
		}
		if ( ! isset( $this->data['time_delay']['in_contact_timezone'] ) || 1 !== absint( $this->data['time_delay']['in_contact_timezone'] ) ) {
			return true;
		}
		$this->is_contact_timezone = true;

		return true;
	}

	public function get_time( $last_time = '' ) {
		switch ( $this->type ) {
			case self::$DELAY_DURATION:
				return $this->get_delay_duration_time( $last_time );
			case self::$DELAY_FIXED:
				return $this->get_fixed_time();
			case self::$DELAY_CUSTOM_FIELD:
				return $this->get_custom_field_time();
			case self::$DELAY_VARIABLE:
				return $this->get_delay_variable_time();
		}

		return false;
	}

	public function get_delay_duration_time( $last_time = '' ) {
		if ( ! isset( $this->data['day_delay'] ) || ! isset( $this->data['day_delay']['text'] ) ) {
			return false;
		}

		$current_timestamp       = current_time( 'timestamp', 1 );
		$current_store_timestamp = current_time( 'timestamp' );

		/** If diff is negative then UTC is + & diff is positive then UTC is - */
		$diff              = $current_timestamp - $current_store_timestamp;
		$current_timestamp = ! empty( $last_time ) ? $last_time : $current_timestamp;

		/** Set general delay */
		switch ( $this->data['day_delay']['unit'] ) {
			case 'min':
				$current_timestamp += MINUTE_IN_SECONDS * (int) $this->data['day_delay']['text'];
				break;
			case 'hours':
				$current_timestamp += HOUR_IN_SECONDS * (int) $this->data['day_delay']['text'];
				break;
			case 'days':
				$current_timestamp += DAY_IN_SECONDS * (int) $this->data['day_delay']['text'];
				break;
			case 'weeks':
				$current_timestamp += WEEK_IN_SECONDS * (int) $this->data['day_delay']['text'];
				break;
			case 'months':
				$current_timestamp += MONTH_IN_SECONDS * (int) $this->data['day_delay']['text'];
				break;
		}

		if ( false === bwfan_is_autonami_pro_active() ) {
			return $current_timestamp;
		}

		/** If no time & day delay set */
		if ( empty( $this->data['enable_time_delay'] ) && empty( $this->data['enable_week_delay'] ) ) {
			return $current_timestamp;
		}
		if ( ( ! isset( $this->data['time_delay']['time'] ) || empty( $this->data['time_delay']['time'] ) ) && empty( $this->data['week_delay'] ) ) {
			return $current_timestamp;
		}

		$delay_time = isset( $this->data['time_delay']['time'] ) && ! empty( $this->data['time_delay']['time'] ) ? $this->data['time_delay']['time'] : 0;

		/** timestamp modified to local time */
		$current_timestamp = $current_timestamp - $diff;

		/** Get time according to contact's timezone */
		$datetime = $this->get_contact_time( $current_timestamp, $delay_time );

		/** No days delay found */
		if ( empty( $this->data['week_delay'] ) ) {
			/** If older time */
			if ( $datetime->getTimestamp() - current_time( 'timestamp', 1 ) < 0 ) {
				return $datetime->getTimestamp() + DAY_IN_SECONDS;
			}

			return $datetime->getTimestamp();
		}

		/** Days delay functioning */
		if ( is_array( $this->data['week_delay'] ) && count( $this->data['week_delay'] ) > 0 ) {
			for ( $h = 0; $h < 7; $h ++ ) {
				/** 1 - Sunday | 2 - Monday */
				$current_day = ( intval( $datetime->format( "N" ) ) === 7 ) ? 1 : ( intval( $datetime->format( "N" ) ) + 1 );
				if ( in_array( $current_day, $this->data['week_delay'] ) ) {
					/** If future time then return else +1 day */
					if ( current_time( 'timestamp', 1 ) < $datetime->getTimestamp() ) {
						return $datetime->getTimestamp();
					}
				}
				$datetime->modify( '+1 days' );
			}
		}

		return $datetime->getTimestamp();
	}

	public function get_contact_time( $current_timestamp, $delay_time ) {
		$timezone = new DateTimeZone( wp_timezone_string() );
		$datetime = new DateTime( date( 'Y-m-d H:i:s', $current_timestamp ), $timezone );

		if ( empty( $delay_time ) ) {
			return $datetime;
		}

		/** Time delay functioning */
		if ( $this->is_contact_timezone && ! empty( $this->contact_id ) ) {
			$contact = new WooFunnels_Contact( '', '', '', absint( $this->contact_id ) );
			if ( absint( $contact->get_id() ) > 0 && ! empty( $contact->get_timezone() ) ) {
				$timezone = new DateTimeZone( $contact->get_timezone() );
			}
			$datetime = new DateTime( date( 'Y-m-d H:i:s', $current_timestamp ), $timezone );
		}
		sscanf( $delay_time, '%d:%d', $hours, $minutes );
		$datetime->setTime( $hours, $minutes, 0 );

		return $datetime;
	}

	public function get_fixed_time() {
		if ( false === bwfan_is_autonami_pro_active() ) {
			return current_time( 'timestamp', 1 );
		}

		/** Store timezone */
		$timezone = new DateTimeZone( wp_timezone_string() );

		$enable_user_timezone = ( isset( $this->data['time_delay']['in_contact_timezone'] ) && 1 === intval( $this->data['time_delay']['in_contact_timezone'] ) );
		if ( true === $enable_user_timezone && ! empty( $this->contact_id ) ) {
			/** User timezone */
			$contact = new WooFunnels_Contact( '', '', '', absint( $this->contact_id ) );
			if ( absint( $contact->get_id() ) > 0 && ! empty( $contact->get_timezone() ) ) {
				try {
					$timezone = new DateTimeZone( $contact->get_timezone() );
				} catch ( Exception $e ) {
					BWFAN_Common::log_test_data( [ 'cid' => $contact->get_id(), 'timezone' => $contact->get_timezone(), 'msg' => $e->getMessage() ], 'contact-timezone-error' );
				}
			}
		}

		$date = new DateTime();
		$date->setTimezone( $timezone );

		sscanf( $this->data['date'], '%d/%d/%d', $months, $days, $years );
		$date->setDate( $years, $months, $days );

		sscanf( $this->data['time_delay']['time'], '%d:%d', $hours, $minutes );
		$date->setTime( $hours, $minutes, 0 );

		return $date->getTimestamp();
	}

	public function get_custom_field_time() {
		$current_timestamp = current_time( 'timestamp', 1 );

		if ( false === bwfan_is_autonami_pro_active() ) {
			return $current_timestamp;
		}

		if ( empty( $this->contact_id ) || ! class_exists( 'BWFCRM_Contact' ) ) {
			return false;
		}

		if ( ! isset( $this->data['custom_field'] ) || empty( $this->data['custom_field'] ) ) {
			return $current_timestamp;
		}

		$contact = new BWFCRM_Contact( $this->contact_id );
		if ( ! $contact->is_contact_exists() || empty( $contact->fields ) ) {
			return false;
		}

		$field_id  = isset( $this->data['custom_field']['field'] ) ? $this->data['custom_field']['field'] : 0;
		$field_val = isset( $contact->fields[ absint( $field_id ) ] ) ? $contact->fields[ absint( $field_id ) ] : 0;
		if ( empty( $field_val ) ) {
			return $current_timestamp;
		}

		if ( isset( $this->data['custom_field']['in_contact_timezone'] ) && ! empty( $this->data['custom_field']['in_contact_timezone'] ) ) {
			$this->is_contact_timezone = true;
		}

		$delay_time = empty( $this->data['custom_field']['time'] ) ? '10:00' : $this->data['custom_field']['time'];

		/** Get time according to contact's timezone */
		$datetime = $this->get_contact_time( strtotime( $field_val ), $delay_time );

		/** Get delay setting for certain days from field date */
		$enable_delay = isset( $this->data['enable_time_delay'] ) ? absint( $this->data['enable_time_delay'] ) : 0;
		if ( ! empty( $enable_delay ) && isset( $this->data['time_delay']['timing'] ) && isset( $this->data['time_delay']['type'] ) && isset( $this->data['time_delay']['duration'] ) ) {
			$operator = $this->data['time_delay']['timing'];
			$operator = ( 'before' === $operator ) ? '-' : '+';

			$type     = $this->data['time_delay']['type'];
			$duration = absint( $this->data['time_delay']['duration'] );
			if ( ! empty( $operator ) && ! empty( $duration ) && ! empty( $type ) ) {
				$datetime->modify( "$operator $duration $type" );
			}
		}

		/** checking for daymonth and then adding 1 year to date if time has passed */
		if ( isset( $this->data['occurrence'] ) && 'daymonth' === $this->data['occurrence'] ) {
			$datetime->setDate( date( 'Y', $current_timestamp ), $datetime->format( 'm' ), $datetime->format( 'd' ) );
			if ( $datetime->getTimestamp() < $current_timestamp ) {
				$datetime->modify( '+1 year' );
			}
		}

		return $datetime->getTimestamp();
	}

	public function get_delay_variable_time() {
		$current_timestamp = current_time( 'timestamp', 1 );

		if ( false === bwfan_is_autonami_pro_active() ) {
			return $current_timestamp;
		}

		if ( ! isset( $this->data['variable_field'] ) || empty( $this->data['variable_field'] ) ) {
			BWFAN_Common::log_test_data( 'Delay variable field is not set ' . $current_timestamp, 'delay-time', true );
			BWFAN_Common::log_test_data( $this->data, 'delay-time', true );

			return $current_timestamp;
		}

		if ( ! isset( $this->data['variable_field']['variable'] ) || empty( $this->data['variable_field']['variable'] ) ) {
			BWFAN_Common::log_test_data( 'Delay variable is empty ' . $current_timestamp, 'delay-time', true );
			BWFAN_Common::log_test_data( $this->data, 'delay-time', true );

			return $current_timestamp;
		}

		/** Reset the old merge tag data if any and set the current one and decoding accordingly */
		BWFAN_Merge_Tag_Loader::reset_data();
		BWFAN_Merge_Tag_Loader::set_data( $this->automation_data['global'] );
		/** Set type and output format(o_format) in mergetag */
		$delay_variable      = str_replace( '}}', " from='delay' type='date'}}", $this->data['variable_field']['variable'] );
		$delay_variable_date = BWFAN_Common::decode_merge_tags( $delay_variable );

		/** Return current time if date value is empty */
		if ( empty( $delay_variable_date ) || false === strtotime( $delay_variable_date ) ) {
			BWFAN_Common::log_test_data( 'Date value is empty ' . $current_timestamp, 'delay-time', true );
			BWFAN_Common::log_test_data( $this->data, 'delay-time', true );

			return $current_timestamp;
		}

		if ( isset( $this->data['variable_field']['in_contact_timezone'] ) && ! empty( $this->data['variable_field']['in_contact_timezone'] ) ) {
			$this->is_contact_timezone = true;
		}

		$delay_time = empty( $this->data['variable_field']['time'] ) ? '10:00' : $this->data['variable_field']['time'];

		/** Get time according to contact's timezone */
		$datetime = $this->get_contact_time( strtotime( $delay_variable_date ), $delay_time );

		/** Get delay setting for certain days from delay variable date */
		$enable_delay = isset( $this->data['enable_time_delay'] ) ? absint( $this->data['enable_time_delay'] ) : 0;
		if ( ! empty( $enable_delay ) && isset( $this->data['time_delay']['timing'] ) && isset( $this->data['time_delay']['type'] ) && isset( $this->data['time_delay']['duration'] ) ) {
			$operator = $this->data['time_delay']['timing'];
			$operator = ( 'before' === $operator ) ? '-' : '+';

			$type     = $this->data['time_delay']['type'];
			$duration = absint( $this->data['time_delay']['duration'] );
			if ( ! empty( $operator ) && ! empty( $duration ) && ! empty( $type ) ) {
				$datetime->modify( "$operator $duration $type" );
			}
		}

		/** checking for daymonth and then adding 1 year to date if time has passed */
		if ( isset( $this->data['occurrence'] ) && 'daymonth' === $this->data['occurrence'] ) {
			$datetime->setDate( date( 'Y', $current_timestamp ), $datetime->format( 'm' ), $datetime->format( 'd' ) );
			if ( $datetime->getTimestamp() < $current_timestamp ) {
				$datetime->modify( '+1 year' );
			}
		}

		return $datetime->getTimestamp();
	}
}
