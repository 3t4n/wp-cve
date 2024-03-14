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

namespace FireBox\Core\Admin\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Library extends \FPFramework\Admin\Library\Library
{
	public function __construct()
	{
		parent::__construct($this->getLibrarySettings());

		add_action('current_screen', [$this, 'validate']);

		// set default post title
		add_filter('default_title', [$this, 'presetPostTitle'], 100, 2);

		// set default post content
		add_filter('default_content', [$this, 'presetPostContent'], 200, 2);
	}

	/**
	 * Returns the library settings.
	 * 
	 * @return  array
	 */
	private function getLibrarySettings()
	{
		return [
			'id' => 'fbSelectTemplate',
			'title' => firebox()->_('FB_CAMPAIGN_LIBRARY'),
			'create_new_template_link' => admin_url('post-new.php?post_type=firebox'),
			'main_category_label' => firebox()->_('FB_CAMPAIGN_TYPE'),
			'plugin_license_settings_url' => admin_url('admin.php?page=firebox-settings#license_key'),
			'plugin_dir' => FBOX_PLUGIN_DIR,
			'plugin' => 'firebox',
			'plugin_version' => FBOX_VERSION,
			'plugin_license_type' => FBOX_LICENSE_TYPE,
			'plugin_name' => firebox()->_('FB_PLUGIN_NAME'),
			
			'license_key' => false,
			'license_key_status' => false,
			
			
			'blank_template_label' => fpframework()->_('FPF_BLANK_TEMPLATE'),
			'template_use_url' => 'post-new.php?post_type=firebox&fpf_use_template=true&template='
		];
	}

	/**
	 * Old function used to find templates.
	 * 
	 * @param   string  $template
	 * 
	 * @deprecated  1.1.0
	 * 
	 * @return  null
	 */
	public function find($template = '')
	{
		return;
	}

	/**
	 * Runs only on specific FireBox pages
	 * 
	 * @return  void
	 */
	public function validate()
	{
		$current_screen = get_current_screen();

		$allowed_pages = [
			'toplevel_page_firebox',
			'firebox_page_firebox-analytics',
			'firebox_page_firebox-campaigns',
			'firebox_page_firebox-submissions',
			'firebox_page_firebox-settings',
			'edit-firebox'
		];

		if (!in_array($current_screen->id, $allowed_pages))
		{
			return false;
		}

		$this->init();
	}

	/**
	 * Set the default post title if we have selected a template via the Library
	 * 
	 * @param   string  $post_title
	 * @param   object  $post
	 * 
	 * @return  string
	 */
	public function presetPostTitle($post_title, $post)
	{
		if (!$template = \FireBox\Core\Helpers\BoxHelper::getBoxFromTemplateURL())
		{
			return $post_title;
		}

		return $template['template']['post_title'];
	}

	/**
	 * Sets the default post content if we have selected a template via the Library
	 * 
	 * @param   string
	 * 
	 * @return  string
	 */
	public function presetPostContent($content)
	{
		if (!$template = \FireBox\Core\Helpers\BoxHelper::getBoxFromTemplateURL())
		{
			return $content;
		}

		return $template['template']['post_content'];
	}
}