<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_DateTime_Helper {
	private static $display_time_format = null;
	private static $display_full_date_time_format = null;
	private static $display_full_wday_date_time_format = null;

	public static function DateTime_Obj_to_DateTime_DB(DateTime $obj) {
		return $obj->format('Y-m-d H:i:sP');
	}

	public static function DateTime_Obj_to_Date_DB(DateTime $obj) {
		return $obj->format('Y-m-d');
	}

	public static function DateTime_Obj_to_unix(DateTime $obj) {
		return $obj->format('U');
	}

	public static function DateTime_Obj_to_ISO(DateTime $obj) {
		return $obj->format('c');
	}

	public static function DateTime_Obj_to_Date_DB_allow_exception($obj) {
		if ($obj instanceof DateTime) {
			return $obj->format('Y-m-d');
		} elseif ($obj === false) {
			return $obj;
		}

		return ''; //handle error later
	}

	public static function DateTime_Obj_to_UTC_and_ISO_Z(DateTime $obj) {
		$obj->setTimezone(new DateTimeZone('UTC'));
		$date_and_time = $obj->format('c');
		$date_and_time = str_replace('+00:00', 'Z', $date_and_time);
		return $date_and_time;
	}

	private static function set_time_formats() {
		if (self::$display_time_format == null) {
			$time_format = WPCal_General_Settings::get('time_format');
			self::$display_time_format = 'H:i';
			if ($time_format === '12hrs') {
				self::$display_time_format = 'h:i a';
			}
		}
		if (self::$display_full_date_time_format == null) {
			self::$display_full_date_time_format = self::$display_time_format . ', F jS, Y.';
		}
		if (self::$display_full_wday_date_time_format == null) {
			self::$display_full_wday_date_time_format = self::$display_time_format . ', l, F jS, Y.';
		}
		return true;
	}

	public static function DateTime_Obj_to_time_format(DateTime $obj) {
		self::set_time_formats();
		return $obj->format(self::$display_time_format);
	}

	public static function DateTime_Obj_to_full_date_time(DateTime $obj) {
		self::set_time_formats();
		$result = $obj->format(self::$display_full_date_time_format);
		$result = self::translate_date_time_strings($result);
		return $result;
	}

	public static function DateTime_Obj_to_from_and_to_full_date_time(DateTime $obj1, DateTime $obj2) {
		self::set_time_formats();
		$obj1_format = self::$display_time_format;
		$obj2_format = self::$display_full_wday_date_time_format;

		if ($obj1->format('e') != $obj2->format('e')) {
			//it shouldn't happend - don't want to throw error - lets convert - LOG this for debugging later
			$obj2->setTimezone(new DateTimeZone($obj1->format('e')));
		}

		if ($obj1->format('Y-m-d') != $obj2->format('Y-m-d')) {
			$obj1_format = self::$display_full_wday_date_time_format;
		}

		$result = $obj1->format($obj1_format) . ' - ' . $obj2->format($obj2_format);
		$result = self::translate_date_time_strings($result);
		return $result;
	}

	public static function DateTime_Obj_to_from_and_to_full_date_time_with_tz(DateTime $obj1, DateTime $obj2) {
		$str = self::DateTime_Obj_to_from_and_to_full_date_time($obj1, $obj2);
		$tz = $obj1->format('e');
		$tz_str = wpcal_get_timezone_name($tz);
		$str = rtrim($str, '.') . ' (' . $tz_str . ')';
		return $str;
	}

	private static function translate_date_time_strings($value) {
		static $trans = [];
		if (empty($trans)) {
			$trans = [
				'Monday' => /* translators: Weekday. */__("Monday", "wpcal"),
				'Tuesday' => /* translators: Weekday. */__("Tuesday", "wpcal"),
				'Wednesday' => /* translators: Weekday. */__("Wednesday", "wpcal"),
				'Thursday' => /* translators: Weekday. */__("Thursday", "wpcal"),
				'Friday' => /* translators: Weekday. */__("Friday", "wpcal"),
				'Saturday' => /* translators: Weekday. */__("Saturday", "wpcal"),
				'Sunday' => /* translators: Weekday. */__("Sunday", "wpcal"),
				'Mon' => /* translators: Three-letter abbreviation of the weekday. */__("Mon", "wpcal"),
				'Tue' => /* translators: Three-letter abbreviation of the weekday. */__("Tue", "wpcal"),
				'Wed' => /* translators: Three-letter abbreviation of the weekday. */__("Wed", "wpcal"),
				'Thu' => /* translators: Three-letter abbreviation of the weekday. */__("Thu", "wpcal"),
				'Fri' => /* translators: Three-letter abbreviation of the weekday. */__("Fri", "wpcal"),
				'Sat' => /* translators: Three-letter abbreviation of the weekday. */__("Sat", "wpcal"),
				'Sun' => /* translators: Three-letter abbreviation of the weekday. */__("Sun", "wpcal"),

				'January' => /* translators: Month name. */__("January", "wpcal"),
				'February' => /* translators: Month name. */__("February", "wpcal"),
				'March' => /* translators: Month name. */__("March", "wpcal"),
				'April' => /* translators: Month name. */__("April", "wpcal"),
				'May' => /* translators: Month name. */__("May", "wpcal"),
				'June' => /* translators: Month name. */__("June", "wpcal"),
				'July' => /* translators: Month name. */__("July", "wpcal"),
				'August' => /* translators: Month name. */__("August", "wpcal"),
				'September' => /* translators: Month name. */__("September", "wpcal"),
				'October' => /* translators: Month name. */__("October", "wpcal"),
				'November' => /* translators: Month name. */__("November", "wpcal"),
				'December' => /* translators: Month name. */__("December", "wpcal"),
				'Jan' => /* translators: Three-letter abbreviation of the month. */_x("Jan", "three-letter month abbreviation", "wpcal"),
				'Feb' => /* translators: Three-letter abbreviation of the month. */_x("Feb", "three-letter month abbreviation", "wpcal"),
				'Mar' => /* translators: Three-letter abbreviation of the month. */_x("Mar", "three-letter month abbreviation", "wpcal"),
				'Apr' => /* translators: Three-letter abbreviation of the month. */_x("Apr", "three-letter month abbreviation", "wpcal"),
				'May' => /* translators: Three-letter abbreviation of the month. */_x("May", "three-letter month abbreviation", "wpcal"),
				'Jun' => /* translators: Three-letter abbreviation of the month. */_x("Jun", "three-letter month abbreviation", "wpcal"),
				'Jul' => /* translators: Three-letter abbreviation of the month. */_x("Jul", "three-letter month abbreviation", "wpcal"),
				'Aug' => /* translators: Three-letter abbreviation of the month. */_x("Aug", "three-letter month abbreviation", "wpcal"),
				'Sep' => /* translators: Three-letter abbreviation of the month. */_x("Sep", "three-letter month abbreviation", "wpcal"),
				'Oct' => /* translators: Three-letter abbreviation of the month. */_x("Oct", "three-letter month abbreviation", "wpcal"),
				'Nov' => /* translators: Three-letter abbreviation of the month. */_x("Nov", "three-letter month abbreviation", "wpcal"),
				'Dec' => /* translators: Three-letter abbreviation of the month. */_x("Dec", "three-letter month abbreviation", "wpcal"),

				'1st' => __("1st", "wpcal"),
				'2nd' => __("2nd", "wpcal"),
				'3rd' => __("3rd", "wpcal"),
				'4th' => __("4th", "wpcal"),
				'5th' => __("5th", "wpcal"),
				'6th' => __("6th", "wpcal"),
				'7th' => __("7th", "wpcal"),
				'8th' => __("8th", "wpcal"),
				'9th' => __("9th", "wpcal"),
				'10th' => __("10th", "wpcal"),
				'11th' => __("11th", "wpcal"),
				'12th' => __("12th", "wpcal"),
				'13th' => __("13th", "wpcal"),
				'14th' => __("14th", "wpcal"),
				'15th' => __("15th", "wpcal"),
				'16th' => __("16th", "wpcal"),
				'17th' => __("17th", "wpcal"),
				'18th' => __("18th", "wpcal"),
				'19th' => __("19th", "wpcal"),
				'20th' => __("20th", "wpcal"),
				'21st' => __("21st", "wpcal"),
				'22nd' => __("22nd", "wpcal"),
				'23rd' => __("23rd", "wpcal"),
				'24th' => __("24th", "wpcal"),
				'25th' => __("25th", "wpcal"),
				'26th' => __("26th", "wpcal"),
				'27th' => __("27th", "wpcal"),
				'28th' => __("28th", "wpcal"),
				'29th' => __("29th", "wpcal"),
				'30th' => __("30th", "wpcal"),
				'31st' => __("31st", "wpcal"),
			];
		}
		$translated_value = strtr($value, $trans);
		return $translated_value;
	}

	// public static function DateTime_Obj_to_Time_DB(DateTime $obj){
	// 	return $obj->format('H:i:s');
	// }

	public static function DateTime_DB_to_DateTime_obj($v, DateTimeZone $tz) {
		if ($v instanceof DateTime) {
			return $v;
		}
		return new DateTime($v, $tz);
	}

	public static function now_DateTime_obj(DateTimeZone $tz) {
		return new DateTime('now', $tz);
	}

	public static function Date_DB_to_DateTime_obj($v, DateTimeZone $tz) {
		return new DateTime($v, $tz);
	}

	public static function unix_to_DateTime_obj($v, DateTimeZone $tz) {
		$obj = new DateTime('@' . $v);
		$obj->setTimezone($tz);
		return $obj;
	}

	public static function maybe_get_DateTime_obj($v, DateTimeZone $tz = null) {
		if ($v instanceof DateTime) {
			return $v;
		}
		if (!empty($v)) {
			return new DateTime($v, $tz);
		}
		return $v;
	}

	public static function maybe_get_date_from_DateTime_Obj($v) {
		if ($v instanceof DateTime) {
			return self::DateTime_Obj_to_Date_DB($v);
		}
		return $v;
	}

	public static function get_Time_obj($v) {
		if ($v instanceof WPCal_Time) {
			return $v;
		}
		return new WPCal_Time($v);
	}

	public static function maybe_get_time_from_Time_Obj($v) {
		if ($v instanceof WPCal_Time) {
			return $v->DB_format();
		}
		return $v;
	}

	public static function get_DateInterval_obj($mins) {
		if ($mins instanceof DateInterval) {
			return $mins;
		}
		return new DateInterval('PT' . $mins . 'M');
	}

	public static function get_mins_from_DateInterval_obj(DateInterval $interval) {
		$seconds = date_create('@0')->add($interval)->getTimestamp();
		return round($seconds / 60);
	}

	// public static function Time_DB_to_DateTime_Obj($v){
	// 	$obj = new DateTime('0000-1-1');
	// 	$obj->setTime($v);
	// 	return $obj;
	// }

	/**
	 * Combine a number of DateIntervals into 1
	 * @param DateInterval $...
	 * @return DateInterval
	 */
	public static function add_DateIntervals() {
		$reference = new DateTimeImmutable;
		$endTime = clone $reference;

		foreach (func_get_args() as $dateInterval) {
			$endTime = $endTime->add($dateInterval);
		}

		return $reference->diff($endTime);
	}

	public static function is_two_slots_collide($slot_1_from_time, $slot_1_to_time, $slot_2_from_time, $slot_2_to_time) {
		//var_export(array($slot_1_from_time->format('c'), $slot_1_to_time->format('c'), $slot_2_from_time->format('c'), $slot_2_to_time->format('c')));
		if (
			$slot_1_from_time >= $slot_2_from_time &&

			$slot_1_from_time < $slot_2_to_time
		) {
			return true;
		}
		if (
			$slot_2_from_time >= $slot_1_from_time &&

			$slot_2_from_time < $slot_1_to_time
		) {
			return true;
		}
		// if(
		// 	$slot_1_to_time > $slot_2_from_time &&

		// 	$slot_1_to_time <= $slot_2_to_time
		// ){
		// 	return true;
		// }
		return false;
	}
}

class WPCal_Time { //we can upgrade this class using php DateTime
	private $dateTime_obj;

	/**
	 * @$time HH:MM:SS format
	 */
	public function __construct($time) {
		$this->dateTime_obj = new DateTime('1000-01-01 ' . $time);
	}

	/**
	 * Use only time(HH:MM:SS) related formats
	 */
	public function format($v) {
		return $this->dateTime_obj->format($v);
	}

	public function DB_format() {
		return $this->dateTime_obj->format('H:i:s');
	}

	public function str_format() {
		return $this->dateTime_obj->format('H:i:s');
	}

	// public function sub(DateInterval $v){
	// 	return $this->dateTime_obj->sub($v);
	// }
}
