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

class SearchDropdown extends Field
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
			'input_class' => $options->get('input_class', ['xxlarge']),
			'path' => $options->get('path', ''),
			'items' => $options->get('items', []),
			'multiple' => $options->get('multiple', true),
			'lazyload' => $options->get('lazyload', false),
			'hide_ids' => $options->get('hide_ids', true),
			'hide_flags' => $options->get('hide_flags', true),
			'can_clear' => $options->get('can_clear', false),
			'local_search' => $options->get('local_search', false),
			'exclude_rules' => $options->get('exclude_rules', []),
			'exclude_rules_pro' => $options->get('exclude_rules_pro', false),
			'control_inner_class' => $options->get('control_inner_class', ['fpf-max-width-350']),
			'popup_class' => $options->get('popup_class', []),
			'selected_items' => $options->get('selected_items', []),
			'search_query_placeholder' => $options->get('search_query_placeholder', '')
		];
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
			'fpframework-searchdropdown-field',
			FPF_MEDIA_URL . 'admin/css/fpf_searchdropdown.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style('fpframework-searchdropdown-field');

		// JS
		wp_register_script(
			'fpframework-searchdropdown-field',
			FPF_MEDIA_URL . 'admin/js/fpf_searchdropdown.js',
			[],
			FPF_VERSION,
			true
		);
		wp_enqueue_script('fpframework-searchdropdown-field');
	}
}