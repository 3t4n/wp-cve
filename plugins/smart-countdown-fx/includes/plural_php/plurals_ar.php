<?php
/*
 Version: 1.0
 Author: Alex Polonski
 Author URI: http://smartcalc.es
 License: GPL2
 */
defined( 'ABSPATH' ) or die();

// Custom localization for Arabic - we need more plural forms here
wp_localize_script( 'smartcountdown-counter-script', 'smartcountdownstrings', array (
		'seconds' => _n( 'Second', 'Seconds', 99, 'smart-countdown' ),
		'seconds_0' => _n( 'Second', 'Seconds', 0, 'smart-countdown' ),
		'seconds_1' => _n( 'Second', 'Seconds', 1, 'smart-countdown' ),
		'seconds_2' => _n( 'Second', 'Seconds', 2, 'smart-countdown' ),
		'seconds_3' => _n( 'Second', 'Seconds', 3, 'smart-countdown' ),
		'seconds_4' => _n( 'Second', 'Seconds', 100, 'smart-countdown' ),
		'minutes' => _n( 'Minute', 'Minutes', 99, 'smart-countdown' ),
		'minutes_0' => _n( 'Minute', 'Minutes', 0, 'smart-countdown' ),
		'minutes_1' => _n( 'Minute', 'Minutes', 1, 'smart-countdown' ),
		'minutes_2' => _n( 'Minute', 'Minutes', 2, 'smart-countdown' ),
		'minutes_3' => _n( 'Minute', 'Minutes', 3, 'smart-countdown' ),
		'minutes_4' => _n( 'Minute', 'Minutes', 100, 'smart-countdown' ),
		'hours' => _n( 'Hour', 'Hours', 99, 'smart-countdown' ),
		'hours_0' => _n( 'Hour', 'Hours', 0, 'smart-countdown' ),
		'hours_1' => _n( 'Hour', 'Hours', 1, 'smart-countdown' ),
		'hours_2' => _n( 'Hour', 'Hours', 2, 'smart-countdown' ),
		'hours_3' => _n( 'Hour', 'Hours', 3, 'smart-countdown' ),
		'hours_4' => _n( 'Hour', 'Hours', 100, 'smart-countdown' ),
		'days' => _n( 'Day', 'Days', 99, 'smart-countdown' ),
		'days_0' => _n( 'Day', 'Days', 0, 'smart-countdown' ),
		'days_1' => _n( 'Day', 'Days', 1, 'smart-countdown' ),
		'days_2' => _n( 'Day', 'Days', 2, 'smart-countdown' ),
		'days_3' => _n( 'Day', 'Days', 3, 'smart-countdown' ),
		'days_4' => _n( 'Day', 'Days', 100, 'smart-countdown' ),
		'weeks' => _n( 'Week', 'Weeks', 99, 'smart-countdown' ),
		'weeks_0' => _n( 'Week', 'Weeks', 0, 'smart-countdown' ),
		'weeks_1' => _n( 'Week', 'Weeks', 1, 'smart-countdown' ),
		'weeks_2' => _n( 'Week', 'Weeks', 2, 'smart-countdown' ),
		'weeks_3' => _n( 'Week', 'Weeks', 3, 'smart-countdown' ),
		'weeks_4' => _n( 'Week', 'Weeks', 100, 'smart-countdown' ),
		'months' => _n( 'Month', 'Months', 99, 'smart-countdown' ),
		'months_0' => _n( 'Month', 'Months', 0, 'smart-countdown' ),
		'months_1' => _n( 'Month', 'Months', 1, 'smart-countdown' ),
		'months_2' => _n( 'Month', 'Months', 2, 'smart-countdown' ),
		'months_3' => _n( 'Month', 'Months', 3, 'smart-countdown' ),
		'months_4' => _n( 'Month', 'Months', 100, 'smart-countdown' ),
		'years' => _n( 'Year', 'Years', 99, 'smart-countdown' ),
		'years_0' => _n( 'Year', 'Years', 0, 'smart-countdown' ),
		'years_1' => _n( 'Year', 'Years', 1, 'smart-countdown' ),
		'years_2' => _n( 'Year', 'Years', 2, 'smart-countdown' ),
		'years_3' => _n( 'Year', 'Years', 3, 'smart-countdown' ),
		'years_4' => _n( 'Year', 'Years', 100, 'smart-countdown' )
) );