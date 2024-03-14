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

class Divider extends \FireBox\Core\Blocks\Block
{
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name = 'divider';

	public function render_callback($atts, $content)
	{
		wp_enqueue_style('fb-block-divider');

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
			'fb-block-divider',
			FBOX_MEDIA_PUBLIC_URL . 'css/blocks/divider.css',
			[],
			FBOX_VERSION
		);
	}

	/**
	 * Registers assets both on front-end and back-end.
	 * 
	 * @return  void
	 */
	public function enqueue_block_assets()
	{
		wp_register_style(
			'fb-block-divider',
			FBOX_MEDIA_PUBLIC_URL . 'css/blocks/divider.css',
			[],
			FBOX_VERSION
		);
	}
}