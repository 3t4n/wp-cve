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

class FPToggle extends Field
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
			'checked' => $options->get('checked', false)
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

		$checked = $this->options['checked'];
		$this->options['empty_value'] = $this->options['value'] == '0';
		$this->options['value'] = is_null($this->options['value']) ? $this->options['default'] : $this->options['value'];
		$this->options['checked'] = $this->options['value'] == '1' || ($this->options['value'] == '' && $checked);
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
			'fpframework-fptoggle-field',
			FPF_MEDIA_URL . 'admin/css/fpf_fptoggle.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-fptoggle-field' );

		// JS
		wp_register_script(
			'fpframework-fptoggle-field',
			FPF_MEDIA_URL . 'admin/js/fpf_fptoggle.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-fptoggle-field' );
	}
}