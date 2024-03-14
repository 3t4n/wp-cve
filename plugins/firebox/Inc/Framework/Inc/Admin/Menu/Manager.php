<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Admin\Menu;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class Manager
{
	/**
	 * The Top Level Menu Item Slug
	 * 
	 * @var  string
	 */
	private $parent_slug = '';

	/**
	 * The capability required for this menu to be displayed to the user
	 * 
	 * @var  string
	 */
	protected $capability = 'manage_options';

	/**
	 * Adds a Menu Page
	 * 
	 * @param  array  $args
	 * 
	 * @return  void
	 */
	public function addMenuPage($args)
	{
		$args = new Registry($args);
		
		$this->parent_slug = $args->get('menu_slug');

		add_menu_page(
			$args->get('page_title'),
			$args->get('menu_title'),
			apply_filters('fpframework/manage_capability', $this->capability),
			$args->get('menu_slug'),
			[$this, 'admin_page'],
			$args->get('icon_url'),
			apply_filters('fpframework/menu_position', $args->get('position', 80))
		);

		$this->handleMenuController($args);
	}

	/**
	 * Adds a Submenu Page
	 * 
	 * @param  array  $args
	 * 
	 * @return  void
	 */
	public function addSubmenuPage($args)
	{
		$args = new Registry($args);

		/**
		 * Handle Custom URL Submenu Item
		 */
		if (!empty($args->get('custom_url')) && is_user_logged_in() && current_user_can('manage_options'))
		{
			global $submenu;
			$submenu[$this->parent_slug][] = array( $args->get('menu_title'), $this->capability, $args->get('custom_url') );
			return;
		}

		$itemParent = !empty($args->get('parent')) ? $args->get('parent') : $this->parent_slug;

		$render_admin_page = ($args->get('render_callback', true)) ? [$this, 'admin_page'] : null;

		add_submenu_page(
			$itemParent,
			$args->get('page_title'),
			$args->get('menu_title'),
			$this->capability,
			$args->get('menu_slug'),
			$render_admin_page
		);

		$this->handleMenuController($args);
	}

	/**
	 * Detects if we should initialize the Controller of current plugin's page
	 * 
	 * @return  void
	 */
	public function handleMenuController($args)
	{
		$controller = $args->get('controller');
		if (!empty($controller))
		{
			// ensure we display the controller of the current page only
			if ($this->canRunController($args->get('menu_slug')))
			{
				if (!class_exists($controller))
				{
					return;
				}

				$controller = new $controller();
				$controller->renderPage();
			}
		}
	}

	/**
	 * Check that we can run the view
	 * 
	 * @return  boolean
	 */
	public function canRunController($menu_slug)
	{
		if ($menu_slug == fpframework()->getPluginPage()) {
			return true;
		}

		return false;
	}

	/**
	 * Sets the capability
	 * 
	 * @param   string  $capability
	 * 
	 * @return  void
	 */
	public function setCapability($capability)
	{
		$this->capability = $capability;
	}

	/**
	 * Wrapper for the hook to render our admin pages
	 *
	 * @return  void
	 */
	public function admin_page() {
		do_action('fpframework_' . fpframework()->getPluginPage() . '/admin_page');
	}
}