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

class Toggle extends Field
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

		$default_choices = [
			'0' => 'FPF_NO',
			'1' => 'FPF_YES'
		];
		
		$this->field_options = [
			'type' => 'Toggle',
			'choices' => $options->get('choices', $default_choices),
			'filter' => $options->get('filter', 'sanitize_key')
		];
	}

	/**
	 * Prepares the field data after value has been set
	 * 
	 * @return  void
	 */
	protected function postPrepareData()
	{
		parent::postPrepareData();

		$checked = $this->options['value'] || $this->options['value'] == '0' ? $this->options['value'] : $this->options['default'];
		$choices_keys = is_array($this->options['choices']) && count($this->options['choices']) ? array_keys($this->options['choices']) : [];
		$this->options['checked'] = !$checked && $checked != '0' && $choices_keys && is_array($choices_keys) && count($choices_keys) ? $choices_keys[0] : $checked;
	}

	/**
	 * Runs before field renders.
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		// CSS
		wp_register_style(
			'fpframework-toggle-field',
			FPF_MEDIA_URL . 'admin/css/fpf_toggle.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-toggle-field' );

		// JS
		wp_register_script(
			'fpframework-toggles-field',
			FPF_MEDIA_URL . 'admin/js/fpf_toggles.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-toggles-field' );
	}
}