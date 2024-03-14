<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MainMenuInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MenuInterface;
use RuntimeException;
/**
 * Class PluginHelper, helps to navigate.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Helper
 */
class PluginHelper
{
    /**
     * @var Config
     */
    private $config;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config)
    {
        $this->config = $config;
    }
    public function is_plugin_page(string $page_id, string $action = '') : bool
    {
        $action = empty($action) ? \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig::ACTION_DEFAULT : $action;
        $menus = $this->config->get_param('menu');
        if ($menus->isArray() && !$menus->isEmpty()) {
            foreach ($menus->get() as $menu) {
                if ($this->check_if_view_exists($menu, $page_id, $action)) {
                    return \true;
                } elseif ($menu instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MainMenuInterface) {
                    foreach ($menu->get_submenus() as $submenu) {
                        if ($this->check_if_view_exists($submenu, $page_id, $action)) {
                            return \true;
                        }
                    }
                }
            }
        }
        return \false;
    }
    public function generate_url_by_view(string $view_class_name, array $parameters = []) : string
    {
        $menus = $this->config->get_param('menu');
        if ($menus->isArray() && !$menus->isEmpty()) {
            foreach ($menus->get() as $menu) {
                if ($menu->has_view_class_name($view_class_name)) {
                    return $this->generate_url($menu->get_slug(), $menu->get_action_by_class_name($view_class_name), $parameters);
                } elseif ($menu instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MainMenuInterface) {
                    foreach ($menu->get_submenus() as $submenu) {
                        if ($submenu->has_view_class_name($view_class_name)) {
                            return $this->generate_url($submenu->get_slug(), $submenu->get_action_by_class_name($view_class_name), $parameters);
                        }
                    }
                }
            }
        }
        throw new \RuntimeException('View is not defined in the menu config.');
    }
    public function generate_url(string $page, string $action = '', array $parameters = []) : string
    {
        $options = '' === $action ? ['page' => $page] : ['page' => $page, 'action' => $action];
        $url = \add_query_arg(\array_merge($options, $parameters), \admin_url('admin.php'));
        return $url;
    }
    public function get_view_by_page_action(string $page, string $action = '') : string
    {
        $action = empty($action) ? \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig::ACTION_DEFAULT : $action;
        $menus = $this->config->get_param('menu');
        if ($menus->isArray() && !$menus->isEmpty()) {
            foreach ($menus->get() as $menu) {
                if ($this->check_if_view_exists($menu, $page, $action)) {
                    return $menu->get_view_by_action($action);
                } elseif ($menu instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MainMenuInterface) {
                    foreach ($menu->get_submenus() as $submenu) {
                        if ($this->check_if_view_exists($submenu, $page, $action)) {
                            return $submenu->get_view_by_action($action);
                        }
                    }
                }
            }
        }
        throw new \RuntimeException('View is not defined in the menu config.');
    }
    public function get_page_from_view_id(string $view_id) : string
    {
        return $this->config->get_param('menu.slug_prefix') . $view_id;
    }
    public function get_url_to_plugin_file(string $uid) : string
    {
        return $this->config->get_param('files.tmp.dir_url') . $uid . '/' . $uid;
    }
    private function check_if_view_exists(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MenuInterface $menu, string $page_id, string $action) : bool
    {
        return $menu instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MenuInterface && $menu->get_slug() === $page_id && $menu->has_view_action($action);
    }
}
