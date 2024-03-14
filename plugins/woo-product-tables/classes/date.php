<?php
class DateWtbp {
	public static function _( $time = null ) {
		if (is_null($time)) {
			$time = time();
		}
		return gmdate(WTBP_DATE_FORMAT_HIS, $time);
	}
}
