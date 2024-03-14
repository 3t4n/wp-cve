<?php
/**
 * Base Controller class.
 *
 * Author:          Uriahs Victor
 * Created on:      27/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Lpac_DPS\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BaseController.
 *
 * @package Lpac_DPS\Controllers
 * @since 1.0.0
 */
class BaseController {

	/**
	 * Days of the week.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $days_of_the_week = array(
		'sunday', // Counting of days of the week starts on Sunday with an index of 0.
		'monday',
		'tuesday',
		'wednesday',
		'thursday',
		'friday',
		'saturday',
	);
}
