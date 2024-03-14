<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
/**
 * Class MenuPluginAssetsLoaderAction, loads plugin assets.
 */
class MenuPluginAssetsLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    /**
     * @var Config
     */
    private $config;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config)
    {
        $this->config = $config;
    }
    public function hooks()
    {
        \add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts'], 90);
    }
    public function enqueue_scripts()
    {
        $version = $this->config->get_param('plugin.version')->get();
        \wp_enqueue_style('dropshipping_menu_css', $this->config->get_param('assets.css.core_dir_url')->get() . 'menu.css', [], $version);
    }
}
