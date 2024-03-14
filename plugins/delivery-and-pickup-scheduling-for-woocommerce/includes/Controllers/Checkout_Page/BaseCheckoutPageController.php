<?php
/**
 * Base controller class.
 *
 * Author:          Uriahs Victor
 * Created on:      17/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Lpac_DPS\Controllers\Checkout_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Controllers\BaseController;
use Lpac_DPS\Models\BaseModel;

/**
 * Class BaseCheckoutPageController.
 *
 * @package Lpac_DPS\Controllers\Checkout_Page
 * @since 1.0.0
 */
class BaseCheckoutPageController extends BaseController {

	/**
	 * Instance of BaseModel class.
	 *
	 * @var BaseModel
	 */
	protected $base_model;

	/**
	 * Houses controller methods needed specifically for checkout page logic.
	 *
	 * @package Lpac_DPS\Controllers\Checkout_Page
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->base_model = new BaseModel();
	}
}
