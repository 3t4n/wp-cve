<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Blocks\Generic;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Countdown extends \FireBox\Core\Blocks\Block
{
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name = 'countdown';

	/**
	 * Callback
	 * 
	 * @param   array   $attributes
	 * @param   string  $content
	 * 
	 * @return  mixed
	 */
	public function render_callback($attributes, $content)
	{
		if (!class_exists('\FPFramework\Base\Widgets\Helper'))
		{
			return;
		}

		if (!isset($attributes['value']))
		{
			return;
		}

		$payload = [
			'countdown_type' => $attributes['countdownType'],
			'value' => $attributes['value'],
			'timezone' => $attributes['timezone'],
			'dynamic_days' => $attributes['dynamicDays'],
			'dynamic_hours' => $attributes['dynamicHours'],
			'dynamic_minutes' => $attributes['dynamicMinutes'],
			'dynamic_seconds' => $attributes['dynamicSeconds'],
			'digits_wrapper_padding' => $attributes['digitsWrapperPadding'],
			'digits_wrapper_background_color' => $attributes['digitsWrapperBackgroundColor'],
			'digits_wrapper_border_radius' => $attributes['digitsWrapperBorderRadius'],
			'finish_text' => $attributes['finishText'],
			'redirect_url' => $attributes['redirectURL'],
			'countdown_action' => $attributes['countdownAction'],
			'theme' => $attributes['theme'],
			'format' => $attributes['format'],
			'days' => $attributes['days'],
			'days_label' => $attributes['daysLabel'],
			'hours' => $attributes['hours'],
			'hours_label' => $attributes['hoursLabel'],
			'minutes' => $attributes['minutes'],
			'minutes_label' => $attributes['minutesLabel'],
			'seconds' => $attributes['seconds'],
			'seconds_label' => $attributes['secondsLabel'],
			'background_color' => $attributes['backgroundColor'],
			'item_background_color' => $attributes['itemBackgroundColor'],
			'unit_label_text_color' => $attributes['unitLabelTextColor'],
			'digit_background_color' => $attributes['digitBackgroundColor'],
			'digit_text_color' => $attributes['digitTextColor'],
			'unit_label_margin_top' => $attributes['unitLabelMarginTop'],
			'separator' => $attributes['separator'],
			'double_zeroes_format' => $attributes['doubleZeroesFormat'],
			'align' => $attributes['align'],
			'margin' => $attributes['margin'],
			'padding' => $attributes['padding'],
			'gap' => $attributes['gap'],
			'digits_gap' => $attributes['digitsGap'],
			'item_size' => $attributes['itemSize'],
			'item_border_color' => $attributes['itemBorderColor'],
			'item_border_width' => $attributes['itemBorderWidth'],
			'item_border_style' => $attributes['itemBorderStyle'],
			'item_border_radius' => $attributes['itemBorderRadius'],
			'digits_padding' => $attributes['digitsPadding'],
			'digits_wrapper_min_width' => $attributes['digitsWrapperMinWidth'],
			'digit_min_width' => $attributes['digitMinWidth'],
			'digit_border_radius' => $attributes['digitBorderRadius'],
			'digits_font_size' => $attributes['digitsFontSize'],
			'label_font_size' => $attributes['labelFontSize'],
			'digits_font_weight' => $attributes['digitsFontWeight'],
			'label_font_weight' => $attributes['labelFontWeight'],
		];
		
		return \FPFramework\Base\Widgets\Helper::render('Countdown', $payload);
	}
	
	/**
	 * Registers assets both on front-end and back-end.
	 * 
	 * @return  void
	 */
	public function assets()
	{
		\FPFramework\Base\Widgets\Countdown::register_assets();
		\FPFramework\Base\Widgets\Countdown::localize_script();

		wp_enqueue_script('fpframework-countdown-widget');
	}
}