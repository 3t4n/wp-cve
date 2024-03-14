<?php
/**
 * File responsible for defining Misc model methods.
 *
 * Author:          Uriahs Victor
 * Created on:      26/08/2023 (d/m/y)
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
 * Miscellenous Model Class.
 *
 * Responsible for getting Misc settings.
 *
 * @package Lpac_DPS\Models\Plugin_Settings
 */
class Misc extends BaseModel {


	/**
	 * Get option on whether to show current time.
	 *
	 * @return bool
	 * @since 1.1.0
	 */
	public static function showCurrentTime(): bool {
		$value = self::get_setting( 'misc__show_current_time', false );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * String for "Current time".
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function currentTimeText(): string {
		return self::get_setting( 'misc__show_current_time_text', esc_html__( 'Current time:', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}
}
