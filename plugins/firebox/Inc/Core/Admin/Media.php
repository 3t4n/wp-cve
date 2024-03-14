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

namespace FireBox\Core\Admin;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Media
{
	public function __construct()
	{
		if (!is_admin())
		{
			return;
		}

		add_action('enqueue_block_assets', [$this, 'enqueue_block_assets']);
	}

	/**
	 * Loads Gutenberg editor assets
	 * 
	 * @return  void
	 */
	public function enqueue_block_assets()
	{
		if (!is_admin())
		{
			return;
		}
		
		// Enqueue block editor script only in Gutenberg editor
		if (function_exists('get_current_screen'))
		{
			$screen = get_current_screen();
			if ($screen->is_block_editor)
			{
				// Add the block editor styling for our blocks
				wp_enqueue_style(
					'firebox-blocks-editor-styles',
					FBOX_MEDIA_ADMIN_URL . 'css/admin/blocks.css',
					[],
					FBOX_VERSION
				);

				// Add the FireBox block editor script only to FireBox post type
				if (get_post_type() === 'firebox')
				{
					wp_enqueue_script(
						'firebox-block-editor',
						FBOX_MEDIA_ADMIN_URL . 'js/block-editor.js',
						[],
						FBOX_VERSION,
						true
					);

					
				}

				$data = [
					'google_fonts' => \FPFramework\Libs\GoogleFonts::getFonts(),
					'google_fonts_names' => \FPFramework\Libs\GoogleFonts::getFontsNames(),
					'icons' => \FireBox\Core\Libs\Icons::getAll()
				];

				wp_register_script('firebox-block-editor-script', false);
				wp_enqueue_script('firebox-block-editor-script');
				wp_localize_script('firebox-block-editor-script', 'fbox_block_editor_object', $data);
			}
		}
	}
}