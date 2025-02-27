<?php

namespace SiteSEO\Helpers;

if ( ! defined('ABSPATH')) {
	exit;
}

abstract class OpeningHoursHelper {
	public static function getDays() {
		return [
			__('Monday', 'siteseo'),
			__('Tuesday', 'siteseo'),
			__('Wednesday', 'siteseo'),
			__('Thursday', 'siteseo'),
			__('Friday', 'siteseo'),
			__('Saturday', 'siteseo'),
			__('Sunday', 'siteseo'),
		];
	}

	public static function getHours() {
		return ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
	}

	public static function getMinutes() {
		return ['00', '15', '30', '45', '59'];
	}
}
