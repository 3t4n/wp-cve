<?php
/**
 * Base view class.
 *
 * Author:          Uriahs Victor
 * Created on:      22/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Lpac_DPS\Views;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Models\BaseModel;

/**
 * Class BaseView.
 *
 * @package Lpac_DPS\Views
 */
class BaseView {

	/**
	 * delivery text string, used throughout views to perform various logic.
	 *
	 * @var string
	 * @since 1.0.6
	 */
	protected $delivery = 'delivery';

	/**
	 * pickup text string, used throughout views to perform various logic.
	 *
	 * @var string
	 * @since 1.0.6
	 */
	protected $pickup = 'pickup';
}
