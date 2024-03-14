<?php
/*
 Version: 1.0
 Author: Alex Polonski
 Author URI: http://smartcalc.es
 License: GPL2
 */
defined( 'ABSPATH' ) or die();

// We push 3 plural forms - suitable for most languages.
wp_localize_script( 'smartcountdown-counter-script', 'smartcountdownstrings', array (
		'seconds' => _n( 'Second', 'Seconds', 5, 'smart-countdown' ),
		'seconds_1' => _n( 'Second', 'Seconds', 1, 'smart-countdown' ),
		'seconds_2' => _n( 'Second', 'Seconds', 2, 'smart-countdown' ),
		'minutes' => _n( 'Minute', 'Minutes', 5, 'smart-countdown' ),
		'minutes_1' => _n( 'Minute', 'Minutes', 1, 'smart-countdown' ),
		'minutes_2' => _n( 'Minute', 'Minutes', 2, 'smart-countdown' ),
		'hours' => _n( 'Hour', 'Hours', 5, 'smart-countdown' ),
		'hours_1' => _n( 'Hour', 'Hours', 1, 'smart-countdown' ),
		'hours_2' => _n( 'Hour', 'Hours', 2, 'smart-countdown' ),
		'days' => _n( 'Day', 'Days', 5, 'smart-countdown' ),
		'days_1' => _n( 'Day', 'Days', 1, 'smart-countdown' ),
		'days_2' => _n( 'Day', 'Days', 2, 'smart-countdown' ),
		'weeks' => _n( 'Week', 'Weeks', 5, 'smart-countdown' ),
		'weeks_1' => _n( 'Week', 'Weeks', 1, 'smart-countdown' ),
		'weeks_2' => _n( 'Week', 'Weeks', 2, 'smart-countdown' ),
		'months' => _n( 'Month', 'Months', 5, 'smart-countdown' ),
		'months_1' => _n( 'Month', 'Months', 1, 'smart-countdown' ),
		'months_2' => _n( 'Month', 'Months', 2, 'smart-countdown' ),
		'years' => _n( 'Year', 'Years', 5, 'smart-countdown' ),
		'years_1' => _n( 'Year', 'Years', 1, 'smart-countdown' ),
		'years_2' => _n( 'Year', 'Years', 2, 'smart-countdown' ) 
) );