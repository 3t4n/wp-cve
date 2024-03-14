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

namespace FireBox\Core\Admin\Menu;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Admin\Menu\Menu as FrameworkMenu;

class PluginMenu extends FrameworkMenu
{
	/**
	 * Returns all Plugin menu items
	 * 
	 * @return  array
	 */
	public function getMenuItems()
	{
		return [
			[
				'page_title' => esc_html(firebox()->_('FB_PLUGIN_NAME')),
				'menu_title' => esc_html(firebox()->_('FB_PLUGIN_NAME')),
				'menu_slug'	 => 'firebox',
				'icon_url'	 => FBOX_MEDIA_ADMIN_URL . 'images/logo_white.svg',
				'position'	 => 80,
				'controller' => 'FireBox\\Core\\Controllers\\Dashboard',
				'is_parent'  => true
			],
			[
				'page_title' => esc_html(fpframework()->_('FPF_OVERVIEW')),
				'menu_title' => esc_html(fpframework()->_('FPF_OVERVIEW')),
				'menu_slug'	 => 'firebox'
			],
			[
				'render_callback' => false,
				'page_title' => esc_html(firebox()->_('FB_NEW_CAMPAIGN')),
				'menu_title' => esc_html(firebox()->_('FB_NEW_CAMPAIGN')),
				'menu_slug'	 => 'post-new.php?post_type=firebox'
			],
			[
				'page_title' => esc_html(firebox()->_('FB_FIREBOX_CAMPAIGNS')),
				'menu_title' => esc_html(firebox()->_('FB_CAMPAIGNS')),
				'menu_slug'	 => 'firebox-campaigns',
				'controller' => 'FireBox\\Core\\Controllers\\Campaigns'
			],
			[
				'page_title' => esc_html(firebox()->_('FB_ANALYTICS_PAGE_TITLE')),
				'menu_title' => esc_html(fpframework()->_('FPF_ANALYTICS')),
				'menu_slug'	 => 'firebox-analytics',
				'controller' => 'FireBox\\Core\\Controllers\\Analytics'
			],
			[
				'page_title' => esc_html(firebox()->_('FB_SUBMISSIONS_PAGE_TITLE')),
				'menu_title' => esc_html(fpframework()->_('FPF_SUBMISSIONS')),
				'menu_slug'	 => 'firebox-submissions',
				'controller' => 'FireBox\\Core\\Controllers\\Submissions'
			],
			[
				'page_title' => esc_html(firebox()->_('FB_IMPORT_CAMPAIGNS')),
				'menu_title' => esc_html(fpframework()->_('FPF_IMPORT')),
				'menu_slug'	 => 'firebox-import',
				'controller' => 'FireBox\\Core\\Controllers\\BoxImport'
			],
			[
				'page_title' => esc_html(firebox()->_('FB_SETTINGS_PAGE_TITLE')),
				'menu_title' => esc_html(fpframework()->_('FPF_SETTINGS')),
				'menu_slug'	 => 'firebox-settings',
				'controller' => 'FireBox\\Core\\Controllers\\BoxSettings'
			],
			[
				'menu_title' => esc_html(fpframework()->_('FPF_DOCUMENTATION')),
				'custom_url'  => FBOX_DOC_URL
			],
			
			[
				'menu_title' => esc_html(fpframework()->_('FPF_UPGRADE_TO_PRO')),
				'custom_url'  => FBOX_GO_PRO_URL
			]
			
		];
	}

}