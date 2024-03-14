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

class ResponsiveControls extends Field
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
			'type' => 'ResponsiveControls',
			'render_top' => $options->get('render_top', false),
			'devices' => $options->get('devices', ['desktop', 'tablet', 'mobile'])
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
			'fpframework-responsivecontrols-field',
			FPF_MEDIA_URL . 'admin/css/fpf_responsivecontrols.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-responsivecontrols-field' );

		// JS
		wp_register_script(
			'fpframework-responsive-controls-field',
			FPF_MEDIA_URL . 'admin/js/fpf_responsive_controls.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-responsive-controls-field' );
	}
}