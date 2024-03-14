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

class Dimensions extends Field
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

		$labels = [
			'top' => fpframework()->_('FPF_TOP'),
			'right' => fpframework()->_('FPF_RIGHT'),
			'bottom' => fpframework()->_('FPF_BOTTOM'),
			'left' => fpframework()->_('FPF_LEFT')
		];

		$this->field_options = [
			'labels' => $options->get('labels', $labels),
			'showLink' => $options->get('showLink', true),
			'isLinked' => $options->get('isLinked', false),
			'max' => $options->get('max', ''),
		];
	}

	/**
	 * Prepares the field data after field default settings and any field specifc settings have been combined 
	 * 
	 * @return  void
	 */
	protected function prePrepareData()
	{
		parent::prePrepareData();
		
		$number_atts = [];
		if (isset($this->options['min']) && is_numeric($this->options['min']))
		{
			$number_atts['min'] = $this->options['min'];
		}
		if (isset($this->options['max']) && is_numeric($this->options['max']))
		{
			$number_atts['max'] = $this->options['max'];
		}
		if (isset($this->options['step']) && is_numeric($this->options['step']))
		{
			$number_atts['step'] = $this->options['step'];
		}
		$this->options['number_atts'] = \FPFramework\Helpers\FieldsHelper::getHTMLAttributes($number_atts);
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
			'fpframework-dimension-field',
			FPF_MEDIA_URL . 'admin/css/fpf_dimension.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-dimension-field' );

		// JS
		wp_register_script(
			'fpframework-dimensions-control-field',
			FPF_MEDIA_URL . 'admin/js/fpf_dimensions_control.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-dimensions-control-field' );
	}
}