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

class ConditionBuilder extends Field
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
			'plugin' => $options->get('plugin', ''),
			'include_rules' => $options->get('include_rules', []),
			'exclude_rules' => $options->get('exclude_rules', []),
			'exclude_rules_pro' => $options->get('exclude_rules_pro', false)
		];
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
			'fpframework-conditionbuilder-field',
			FPF_MEDIA_URL . 'admin/css/fpf_conditionbuilder.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style('fpframework-conditionbuilder-field');

		// JS
		wp_register_script(
			'fpframework-conditionbuilder-field',
			FPF_MEDIA_URL . 'admin/js/fpf_conditionbuilder.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script('fpframework-conditionbuilder-field');
	}
}