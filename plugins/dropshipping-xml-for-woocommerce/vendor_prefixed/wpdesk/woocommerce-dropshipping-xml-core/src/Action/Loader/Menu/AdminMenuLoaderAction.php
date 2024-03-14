<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Menu;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MainMenuInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\SubMenuInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ViewAction;
/**
 * Class AdminMenuLoaderAction, admin menu loader.
 */
class AdminMenuLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var ViewAction
     */
    private $view;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ViewAction $view)
    {
        $this->config = $config;
        $this->view = $view;
    }
    public function isActive() : bool
    {
        return \is_admin();
    }
    public function hooks()
    {
        \add_action('admin_menu', array($this, 'adminMenu'), 10);
    }
    public function adminMenu()
    {
        $menus = $this->config->get_param('menu');
        if ($menus->isArray() && !$menus->isEmpty()) {
            foreach ($menus->get() as $menu) {
                if ($menu instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MainMenuInterface) {
                    $this->create_main_menu($menu);
                    foreach ($menu->get_submenus() as $sub) {
                        $this->create_sub_menu($sub);
                    }
                } elseif ($menu instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\SubMenuInterface) {
                    $this->create_sub_menu($menu);
                }
            }
        }
    }
    private function create_main_menu(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MainMenuInterface $menu)
    {
        global $submenu;
        \add_menu_page($menu->get_title(), $menu->get_title(), $menu->get_capability(), $menu->get_slug(), [$this->view, 'show'], $menu->get_icon(), $menu->get_position());
        if ($menu->is_hidden()) {
            $submenu[$menu->get_slug()] = array();
        }
    }
    private function create_sub_menu(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\SubMenuInterface $menu)
    {
        \add_submenu_page($menu->is_hidden() ? null : $menu->get_parent_slug(), $menu->get_title(), $menu->get_title(), $menu->get_capability(), $menu->get_slug(), [$this->view, 'show'], $menu->get_position());
    }
}
