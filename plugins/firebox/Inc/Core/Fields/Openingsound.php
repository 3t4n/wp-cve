<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Fields\Dropdown;
use FPFramework\Libs\Registry;

class Openingsound extends Dropdown
{
	/**
	 * Override the layout of the field.
	 * 
	 * @var  string
	 */
	protected $layout_override = 'FPFramework\Base\Fields\Dropdown';
	
	/**
	 * Runs before field renders.
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		wp_register_script(
			'fb-admin-opening-sound',
			FBOX_MEDIA_ADMIN_URL . 'js/fb_opening_sound.js',
			[],
			FBOX_VERSION,
			true
		);
		wp_enqueue_script( 'fb-admin-opening-sound' );
	}
}