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

namespace FireBox\Core\Blocks\Generic;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Map extends \FireBox\Core\Blocks\Block
{
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name = 'map';

	/**
	 * Callback
	 * 
	 * @param   array   $attributes
	 * @param   string  $content
	 * 
	 * @return  mixed
	 */
	public function render_callback($atts, $content)
	{
		$map = new \FPFramework\Base\Widgets\Map();
		$map->enqueue_assets();

		return $content;
	}
	
	/**
	 * Registers assets on back-end editor.
	 * 
	 * @return  void
	 */
	public function assets()
	{
		\FPFramework\Base\Widgets\Map::register_assets(false);
	}
}