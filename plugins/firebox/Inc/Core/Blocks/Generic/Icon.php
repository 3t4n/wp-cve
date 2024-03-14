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

class Icon extends \FireBox\Core\Blocks\Block
{
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name = 'icon';

	public function render_callback($atts, $content)
	{
		wp_enqueue_style('fb-block-icon');

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
			'fb-block-icon',
			FBOX_MEDIA_PUBLIC_URL . 'css/blocks/icon.css',
			[],
			FBOX_VERSION
		);
	}
}