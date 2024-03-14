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

class Units extends Field
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
			'type' => 'Units',
			'render_top' => $options->get('render_top', false),
			'device' => $options->get('device', '')
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
			'fpframework-units-field',
			FPF_MEDIA_URL . 'admin/css/fpf_units.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-units-field' );

		// JS
		wp_register_script(
			'fpframework-units-field',
			FPF_MEDIA_URL . 'admin/js/fpf_units.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-units-field' );
	}
}