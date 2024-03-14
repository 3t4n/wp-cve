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

namespace FireBox\Core\Controllers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class BaseController
{
	/**
	 * Render page
	 * 
	 * @return  void
	 */
	public function renderPage()
	{
		add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
		
		// render base view of controller
		add_action('fpframework_' . fpframework()->getPluginPage() . '/admin_page', [$this, 'renderBaseView']);
		
		// render each controller view from the child controllers
		add_action('firebox/admin/content', [$this, 'render']);
	}

	public function admin_enqueue_scripts()
	{
		// load media for current page
		if (method_exists($this, 'addMedia'))
		{
			$this->addMedia();
		}
	}

	/**
	 * Renders the Basic View of a Controller
	 * 
	 * @return  void
	 */
	public function renderBaseView()
	{
		firebox()->renderer->admin->render('static/template', [
			'settings' => get_option('firebox_settings'),
			'current_page' => $this->getCurrentPage(),
			'navigation' => $this->getNavigation(),
			'call_to_action_label' => firebox()->_('FB_NEW_CAMPAIGN'),
			'plugin_version' => FBOX_VERSION,
			'plugin_slug' => 'firebox',
			'plugin_name' => 'FireBox',
		]);
	}

	private function getCurrentPage()
	{
		return isset($_GET['page']) ? $_GET['page'] : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Returns the sidebar navigation.
	 * 
	 * @return  void
	 */
	private function getNavigation()
	{
		return [
			[
				'label' => fpframework()->_('FPF_OVERVIEW'),
				'url'	=> admin_url('admin.php?page=firebox'),
				'slug'  => 'firebox'
			],
			[
				'label' => firebox()->_('FB_CAMPAIGNS'),
				'url'	=> admin_url('admin.php?page=firebox-campaigns'),
				'slug'  => 'firebox-campaigns'
			],
			[
				'label' => fpframework()->_('FPF_ANALYTICS'),
				'url'	=> admin_url('admin.php?page=firebox-analytics'),
				'slug'  => 'firebox-analytics'
			],
			[
				'label' => fpframework()->_('FPF_SUBMISSIONS'),
				'url'	=> admin_url('admin.php?page=firebox-submissions'),
				'slug'  => 'firebox-submissions'
			],
			[
				'label' => fpframework()->_('FPF_SETTINGS'),
				'url'	=> admin_url('admin.php?page=firebox-settings'),
				'slug'  => 'firebox-settings'
			]
		];
	}
}