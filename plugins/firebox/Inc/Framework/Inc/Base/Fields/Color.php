<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Field;

class Color extends Field
{
	/**
	 * Runs before field renders
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		// color picker
		wp_enqueue_style( 'wp-color-picker' );

		// CSS
		wp_register_style(
			'fpframework-colorpicker-field',
			FPF_MEDIA_URL . 'admin/css/fpf_colorpicker.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-colorpicker-field' );

		// used by WordPress color picker  ( wpColorPicker() )
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n',
			[
				'clear'            => fpframework()->_('FPF_RESET'),
				'clearAriaLabel'   => fpframework()->_('FPF_RESET_COLOR'),
				'defaultString'    => fpframework()->_('FPF_DEFAULT'),
				'defaultAriaLabel' => fpframework()->_('FPF_SELECT_DEFAULT_COLOR'),
				'pick'             => fpframework()->_('FPF_SELECT_COLOR'),
				'defaultLabel'     => fpframework()->_('FPF_COLOR_VALUE'),
			]
		);

		// load color picker from wordpress
		// as well as our own for transparency support
		wp_register_script(
			'fpframework-colorpicker-admin',
			FPF_MEDIA_URL . 'admin/js/wp-color-picker-alpha.min.js',
			['wp-color-picker', 'jquery-ui-datepicker'],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-colorpicker-admin' );

		wp_register_script(
			'fpframework-colorpicker-field',
			FPF_MEDIA_URL . 'admin/js/fpf_colorpicker.js',
			['wp-color-picker'],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-colorpicker-field' );
	}
}