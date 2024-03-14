<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\AbstractMenu;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\MainMenu;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\SubMenu;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportOptionsViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportFileViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportMapperViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportCsvSelectorViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportXmlSelectorViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportStatusViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\SettingsViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\MarketingViewAction;
/**
 * Class MenuConfig, configuration class for wordpress admin menu.
 * @package WPDesk\Library\DropshippingXmlCore\Config
 */
class MenuConfig extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig
{
    const ID = 'menu';
    const MENU_PREFIX = 'dropshipping_xml_';
    const SLUG_MANAGER = self::MENU_PREFIX . 'manager';
    const SLUG_IMPORT = self::MENU_PREFIX . 'import';
    const SLUG_SETTINGS = self::MENU_PREFIX . 'settings';
    const SLUG_MARKETING = self::MENU_PREFIX . 'how_to_use';
    const ACTION_DEFAULT = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\AbstractMenu::DEFAULT_ACTION;
    const ACTION_CSV_SELECTOR = 'csv_selector';
    const ACTION_XML_SELECTOR = 'xml_selector';
    const ACTION_MAPPER = 'mapper';
    const ACTION_OPTIONS = 'options';
    const ACTION_IMPORTING = 'importing';
    public function get() : array
    {
        $menu_img = $this->get_config()->get_param('assets.img.core_dir_url')->get() . 'logo.svg';
        return [(new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\MainMenu())->set_position(26)->set_title('Dropshipping import')->set_capability('manage_options')->set_icon($menu_img)->set_hidden(\true)->set_slug(self::SLUG_MANAGER)->set_default_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class)->add_submenus([(new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\SubMenu())->set_position(0)->set_title(\__('Import Manager', 'dropshipping-xml-for-woocommerce'))->set_capability('manage_options')->set_slug(self::SLUG_MANAGER)->set_default_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\SubMenu())->set_position(1)->set_title(\__('Import', 'dropshipping-xml-for-woocommerce'))->set_capability('manage_options')->set_slug(self::SLUG_IMPORT)->set_default_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportFileViewAction::class)->add_view_actions([self::ACTION_CSV_SELECTOR => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportCsvSelectorViewAction::class, self::ACTION_XML_SELECTOR => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportXmlSelectorViewAction::class, self::ACTION_MAPPER => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportMapperViewAction::class, self::ACTION_OPTIONS => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportOptionsViewAction::class, self::ACTION_IMPORTING => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportStatusViewAction::class]), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\SubMenu())->set_position(2)->set_title(\__('Settings', 'dropshipping-xml-for-woocommerce'))->set_capability('manage_options')->set_slug(self::SLUG_SETTINGS)->set_default_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\SettingsViewAction::class), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\SubMenu())->set_position(3)->set_title(\__('Start Here', 'dropshipping-xml-for-woocommerce'))->set_capability('manage_options')->set_slug(self::SLUG_MARKETING)->set_default_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\MarketingViewAction::class)])];
    }
    public function get_id() : string
    {
        return self::ID;
    }
}
