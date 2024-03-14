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

class Image extends \FireBox\Core\Blocks\Block
{
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name = 'image';

	public function render_callback($atts, $content)
	{
		wp_enqueue_style('fb-block-image');

		return $content;
	}

	/**
	 * Registers block assets.
	 * 
	 * @return  void
	 */
	public function public_assets()
	{
		wp_register_style(
			'fb-block-image',
			FBOX_MEDIA_PUBLIC_URL . 'css/blocks/image.css',
			[],
			FBOX_VERSION
		);
	}
}