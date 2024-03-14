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

namespace FireBox\Core;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Blocks
{
    public function __construct()
    {
        if (!function_exists('register_block_type'))
		{
			return;
		}

		add_action('enqueue_block_assets', [$this, 'blocks_assets']);

		// Register categories
		global $wp_version;
		if (version_compare($wp_version, '5.8', '>='))
		{
			add_action('block_categories_all', [$this, 'register_categories'], 10, 2);
		}
		else
		{
			add_action('block_categories', [$this, 'register_categories'], 10, 2);
		}

		$this->register_blocks();
    }

	/**
	 * Adds the `FireBox` and `FirePlugins` custom Gutenberg block categories
     * to register all our blocks.
	 * 
	 * @param   array					 $categories
	 * @param   WP_Block_Editor_Context  $context
	 * 
	 * @return  array
	 */
    public function register_categories($categories, $context)
    {
        // Add FireBox category
		$categories = array_merge(
			$categories,
			array(
				array(
					'slug' => 'firebox',
					'title' => firebox()->_('FB_PLUGIN_NAME')
				),
			)
		);

        return $categories;
    }

    /**
     * Initialize all blocks
     * 
     * @return  void
     */
    public function register_blocks()
    {
		$ds = DIRECTORY_SEPARATOR;
		
		$base_dir = implode($ds, [__DIR__, 'Blocks']);
		
		$files = array_diff(scandir($base_dir), ['.', '..', '.DS_Store', 'index.php', 'FormBlock.php', 'Block.php']);
		
		$namespace = '\FireBox\Core\Blocks\\';

		foreach ($files as $item)
		{
            // Check if this is a single file and load it
            $file = implode($ds, [$base_dir, $item]);
            if (is_file($file))
            {
				$class_name = preg_replace('/.php$/', '', $item);
                $this->registerBlock($namespace . $class_name);
                continue;
            }
            
            // If that's not a file, we assume it is a directory of files
			$blocks = array_diff(scandir($file), ['.', '..', 'index.php']);

			foreach ($blocks as $key => $block_name)
			{
				$class_name = preg_replace('/.php$/', '', $block_name);
				$class = $namespace . $item . '\\' . $class_name;
                $this->registerBlock($class);
			}
		}
    }

    /**
     * Registers the block.
     * 
     * @param   string  $class
     * 
     * @return  void
     */
    private function registerBlock($class)
    {
        $block = new $class();
        $block->register();
    }

    /**
     * Blocks assets.
     * 
     * - Extends functionality of the button/image blocks by allowing to trigger a popup.
     *   It also allows us to set custom CSS Classes to these blocks without triggering a popup.
     */
    public function blocks_assets()
    {
		if (!is_admin())
		{
			return;
		}
		
		wp_enqueue_script(
			'fb-blocks-extend',
			FBOX_MEDIA_ADMIN_URL . 'js/fb_blocks_extend.js',
			['wp-i18n', 'wp-blocks', 'wp-editor', 'wp-components', 'wp-api-fetch', 'lodash'],
			FBOX_VERSION,
			true
		);
		
		// Enqueue block editor style only in Gutenberg editor
		if (function_exists('get_current_screen'))
		{
			$screen = get_current_screen();
			if ($screen->is_block_editor)
			{
				if (get_post_type() === 'firebox')
				{
					wp_enqueue_style(
						'firebox-block-editor',
						FBOX_MEDIA_ADMIN_URL . 'css/block-editor.css',
						[],
						FBOX_VERSION
					);
				}
			}
		}
    }
}