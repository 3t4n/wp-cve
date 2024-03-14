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

class GeoLookup extends Field
{
	/**
	 * Runs before field renders
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		// load geoip lookup js
		wp_register_script(
			'fpf-geoip-lookup',
			FPF_MEDIA_URL . 'admin/js/fpf_geoip_lookup.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpf-geoip-lookup' );
	}
}