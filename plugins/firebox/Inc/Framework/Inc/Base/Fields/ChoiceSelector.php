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

class ChoiceSelector extends Field
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
			/**
			 * Available modes:
			 * - text
			 * - icon
			 * - svg
			 * - image
			 */
			'mode' => $options->get('mode', 'text'),
			'plugin' => $options->get('plugin'),
			'choices' => $options->get('choices', []),
			'choice_item_class' => $options->get('choice_item_class', [])
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

		$this->options['choice_item_class'] = (isset($this->options['choice_item_class']) && is_array($this->options['choice_item_class']) && count($this->options['choice_item_class']))
												? ' ' . implode(' ', $this->options['choice_item_class'])
												: ' medium-auto';
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
			'fpframework-choice-selector-field',
			FPF_MEDIA_URL . 'admin/css/fpf_choice_selector.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-choice-selector-field' );
	}
}