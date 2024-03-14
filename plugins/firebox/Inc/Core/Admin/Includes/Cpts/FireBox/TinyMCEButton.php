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

namespace FireBox\Core\Admin\Includes\Cpts\FireBox;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class TinyMCEButton
{
	public function __construct()
	{
		if (!$this->canRun())
		{
			return;
		}

		// Load wp.apiFetch function
		wp_enqueue_script('wp-api-fetch');

		// add button media
		add_filter('mce_external_plugins', [$this, 'register_button_media']);

		// register button
		add_filter('mce_buttons', [$this, 'register_button']);
	}

	/**
	 * Registers Firebox to TinyMCE editor
	 * 
	 * @param   array  $buttons
	 * 
	 * @return  array
	 */
	public function register_button($buttons)
	{
		array_push($buttons, "|", "fireplugins_firebox");
		return $buttons;
	}
	
	/**
	 * Adds button media files
	 * 
	 * @param   array  $buttons
	 * 
	 * @return  array 
	 */
	public function register_button_media($buttons)
	{
		$buttons['fireplugins_firebox'] = FBOX_MEDIA_ADMIN_URL . 'js/fb_tinymce_button.js';
		return $buttons;
	}

	/**
	 * Whether we can run
	 * 
	 * @return  boolean
	 */
	private function canRun()
	{
		if (!current_user_can('manage_options') || !current_user_can('edit_posts') || !current_user_can('edit_pages'))
		{
			return false;
		}

		global $pagenow;
		if (!in_array($pagenow, ['post.php', 'post-new.php']))
		{
			return false;
		}

		return true;
	}
}