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
use FPFramework\Libs\Registry;

class Datepicker extends Field
{
	/**
	 * Set specific field options
	 * 
	 * @param   array  $options
	 * 
	 * @return  void
	 */
	protected function setFieldOptions($options)
	{
		$options = new Registry($options);

		$this->field_options = [
			'apply_timezone' => $options->get('apply_timezone', false),
			'show_open_button' => $options->get('show_open_button', true),
			'show_clear_button' => $options->get('show_clear_button', true),
		];
	}

	/**
	 * Gets the field value
	 * 
	 * @return  mixed
	 */
	public function getValue()
	{
		$value = parent::getValue();

		if (isset($this->options['apply_timezone']) && $this->options['apply_timezone'] && $value)
		{
			$tz = new \DateTimeZone(wp_timezone()->getName());
			$value = (new \DateTime($value))->setTimezone($tz)->format('Y-m-d H:i');
		}
		
		return $value;
	}
	
	/**
	 * Runs before field renders
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		// CSS
		wp_register_style(
			'fpframework-datepicker',
			FPF_MEDIA_URL . 'public/css/flatpickr.min.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style('fpframework-datepicker');

		wp_register_style(
			'fpframework-datetimepicker-field',
			FPF_MEDIA_URL . 'admin/css/fpf_datetimepicker.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style('fpframework-datetimepicker-field');

		// JS
		wp_register_script(
			'fpframework-datepicker-lib',
			FPF_MEDIA_URL . 'public/js/flatpickr.min.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script('fpframework-datepicker-lib');

		wp_register_script(
			'fpframework-datepicker-field',
			FPF_MEDIA_URL . 'admin/js/fpf_datepicker.js',
			['fpframework-datepicker-lib'],
			FPF_VERSION,
			false
		);
		wp_enqueue_script('fpframework-datepicker-field');
	}
}