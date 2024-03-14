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

use FPFramework\Admin\Menu\Manager;
use FPFramework\Libs\Registry;

abstract class Menu
{
	/**
	 * The Menu Manager
	 * 
	 * @var  Manager
	 */
	public $manager;

	/**
	 * Initializes the menus
	 * 
	 * @param   Manager  $manager
	 * 
	 * @return  void
	 */
	public function init($manager = null)
	{
		if (empty($manager))
		{
			$manager = new Manager();
		}
		
		$this->manager = $manager;

		// Register Menus
		$this->registerMenus();
	}

	/**
	 * Returns all plugin menu items
	 * 
	 * @return  array
	 */
	public function getPluginMenuItems()
	{
		if (!method_exists($this, 'getMenuItems'))
		{
			return [];
		}

		if (!$menu_items = $this->getMenuItems())
		{
			return [];
		}

		$data = [];

		foreach ($menu_items as $menu)
		{
			if (!isset($menu['menu_slug']))
			{
				continue;
			}

			$data[] = $menu['menu_slug'];
		}
		
		return array_unique($data);
	}

	/**
	 * Register plugin menus
	 * 
	 * @return  void
	 */
	public function registerMenus()
	{
		if (!method_exists($this, 'getMenuItems'))
		{
			return;
		}
		
		if (!$menu_items = apply_filters('fpframework/filter_menu_items', $this->getMenuItems()))
		{
			return;
		}
		
		foreach ($menu_items as $menu)
		{
			$menu = new Registry($menu);
			
			// Top level menu item
			if ($menu->get('is_parent')) {
				$this->manager->addMenuPage([
					'page_title' => $menu->get('page_title'),
					'menu_title' => $menu->get('menu_title'),
					'menu_slug'  => $menu->get('menu_slug'),
					'icon_url'	 => $menu->get('icon_url'),
					'position'	 => $menu->get('position'),
					'controller' => $menu->get('controller')
				]);
			}
			else //second level menu items
			{
				$args = [
					'page_title' => $menu->get('page_title')
				];

				$args['render_callback'] = $menu->get('render_callback', true);

				if (!empty($menu->get('parent')))
				{
					$args['parent'] = $menu->get('parent');
				}

				if (!empty($menu->get('menu_title')))
				{
					$args['menu_title'] = $menu->get('menu_title');
				}

				if (!empty($menu->get('menu_slug')))
				{
					$args['menu_slug'] = $menu->get('menu_slug');
				}
				
				if (!empty($menu->get('custom_url')))
				{
					$args['custom_url'] = $menu->get('custom_url');
				}
				
				if (!empty($menu->get('controller')))
				{
					$args['controller'] = $menu->get('controller');
				}

				$this->manager->addSubmenuPage($args);
			}
		}
	}

	abstract function getMenuItems();
}