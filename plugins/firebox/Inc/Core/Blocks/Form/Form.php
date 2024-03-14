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

namespace FireBox\Core\Blocks\Form;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Form extends \FireBox\Core\Blocks\FormBlock
{
	/**
	 * Block identifier.
	 * 
	 * @var  string
	 */
	protected $name = 'form';
	
	public function render_callback($atts, $content)
	{
		wp_enqueue_style('fb-block-form');
		wp_enqueue_script('fb-block-form');

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
			'fb-block-form',
			FBOX_MEDIA_PUBLIC_URL . 'css/blocks/form.css',
			[],
			FBOX_VERSION
		);

		wp_register_script(
			'fb-block-form',
			FBOX_MEDIA_PUBLIC_URL . 'js/blocks/form.js',
			[],
			FBOX_VERSION,
			true
		);
	}
	
	/**
	 * Registers assets both on front-end and back-end.
	 * 
	 * @return  void
	 */
	public function assets()
	{
		wp_register_style(
			'fb-block-form',
			FBOX_MEDIA_PUBLIC_URL . 'css/blocks/form.css',
			[],
			FBOX_VERSION
		);
	}
}