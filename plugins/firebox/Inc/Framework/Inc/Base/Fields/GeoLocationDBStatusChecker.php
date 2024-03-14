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

class GeoLocationDBStatusChecker extends Field
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
			'plugin_name' => $options->get('plugin_name', ''),
			'target' => $options->get('target', ''),
			'show_update_button' => $options->get('show_update_button', false),
			'link' => $options->get('link', false)
		];
	}

	/**
	 * Runs before field renders
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		if (!\FPFramework\Helpers\Geolocation::geoNeedsUpdate())
		{
			$this->setOptionsValue('skip_render', true);
		}
	}

	/**
	 * Loads media tied to this Field
	 * 
	 * @return  void
	 */
	protected function addMedia()
	{
		// load geoip js
		wp_register_script(
			'fpf-geoip',
			FPF_MEDIA_URL . 'admin/js/fpf_geoip.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script('fpf-geoip');
	}
}