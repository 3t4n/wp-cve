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

use FPFramework\Libs\Registry;
use FireBox\Core\Helpers\BoxHelper;

class AdminBarMenu
{
	public function __construct()
	{
		$cParam = BoxHelper::getParams();
		$cParam = new Registry($cParam);

		// Ensure we have enabled the showing of the admin bar menu item
		if ($cParam->get('show_admin_bar_menu_item', '1') !== '1')
		{
			return;
		}
		
		// add media to both front and back end
		add_action('wp_enqueue_scripts', [ $this, 'addMedia' ]);
		add_action('admin_enqueue_scripts', [ $this, 'addMedia' ]);

		// add FireBox menu item on top admin bar
		add_action('admin_bar_menu', [ $this, 'register' ], 999);
	}

	/**
	 * Enqueue styles.
	 * 
	 * @return  void
	 */
	public function addMedia()
	{
		if (!$this->canRun())
		{
			return;
		}

		wp_enqueue_style(
			'firebox-admin-bar',
			FBOX_MEDIA_ADMIN_URL . 'css/admin-bar.css',
			[],
			FBOX_VERSION
		);
	}

	/**
	 * Check if current user has access to see admin bar menu.
	 *
	 * @return  boolean
	 */
	public function canRun()
	{
		if (is_user_logged_in() &&
			current_user_can('manage_options') &&
			!get_option('hide-admin-bar', false))
		{
			return true;
		}

		return false;
	}

	/**
	 * Register and render admin menu bar items.
	 *
	 * @param   $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function register($wp_admin_bar)
	{
		if (!$this->canRun())
		{
			return;
		}

		$items = [
			'main_menu',
			'add_new_menu',
			'all_popups_menu',
			'analytics_menu',
			'submissions_menu',
			'support_menu',
			
			'upgrade_menu',
			
		];

		foreach ($items as $item)
		{
			$this->{$item}($wp_admin_bar);
		}
	}

	/**
	 * Render FireBox plugin main page
	 *
	 * @param   object  $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function main_menu($wp_admin_bar)
	{
		$logo = '<img src="' . FBOX_MEDIA_ADMIN_URL . 'images/logo_white.svg' . '" alt="firebox logo" />';
		
		$wp_admin_bar->add_menu(
			[
				'id'    => 'firebox-menu',
				'title' => $logo . firebox()->_('FB_PLUGIN_NAME'),
				'href'  => admin_url('admin.php?page=firebox')
			]
		);
	}

	/**
	 * Render FireBox Popups page
	 *
	 * @param   object  $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function all_popups_menu($wp_admin_bar)
	{
		$wp_admin_bar->add_menu(
			[
				'parent' => 'firebox-menu',
				'id'    => 'firebox-menu-all-boxes',
				'title' => firebox()->_('FB_CAMPAIGNS'),
				'href'  => admin_url('admin.php?page=firebox-campaigns')
			]
		);
	}

	/**
	 * Render FireBox New Item page
	 *
	 * @param   object  $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function add_new_menu($wp_admin_bar)
	{
		$wp_admin_bar->add_menu(
			[
				'parent' => 'firebox-menu',
				'id'    => 'firebox-menu-new-box',
				'title' => firebox()->_('FB_NEW_CAMPAIGN'),
				'href'  => admin_url('post-new.php?post_type=firebox')
			]
		);
	}

	/**
	 * Render FireBox Support Page
	 *
	 * @param   object  $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function support_menu($wp_admin_bar)
	{
		$wp_admin_bar->add_menu(
			[
				'parent' => 'firebox-menu',
				'id'    => 'firebox-menu-support',
				'title' => fpframework()->_('FPF_SUPPORT'),
				'href'  => FPF_SUPPORT_URL
			]
		);
	}

	/**
	 * Render FireBox Analytics Page
	 *
	 * @param   object  $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function analytics_menu($wp_admin_bar)
	{
		$wp_admin_bar->add_menu(
			[
				'parent' => 'firebox-menu',
				'id'    => 'firebox-menu-analytics',
				'title' => fpframework()->_('FPF_ANALYTICS'),
				'href'  => admin_url('admin.php?page=firebox-analytics')
			]
		);
	}

	/**
	 * Render FireBox Submissions Page
	 *
	 * @param   object  $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function submissions_menu($wp_admin_bar)
	{
		$wp_admin_bar->add_menu(
			[
				'parent' => 'firebox-menu',
				'id'    => 'firebox-menu-submissions',
				'title' => fpframework()->_('FPF_SUBMISSIONS'),
				'href'  => admin_url('admin.php?page=firebox-submissions')
			]
		);
	}

	
	/**
	 * Render FireBox Upgrade Page
	 *
	 * @param   object  $wp_admin_bar  WordPress Admin Bar object.
	 * 
	 * @return  void
	 */
	public function upgrade_menu($wp_admin_bar)
	{
		$wp_admin_bar->add_menu(
			[
				'parent' => 'firebox-menu',
				'id'    => 'firebox-menu-upgrade',
				'title' => fpframework()->_('FPF_UPGRADE_TO_PRO'),
				'href'  => FBOX_GO_PRO_URL,
				'meta'  => [
					'class' => 'firebox-go-pro-yellow-link'
				]
			]
		);
	}
	
}