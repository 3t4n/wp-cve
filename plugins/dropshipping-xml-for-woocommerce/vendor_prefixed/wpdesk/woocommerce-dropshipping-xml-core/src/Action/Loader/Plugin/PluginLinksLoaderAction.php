<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Plugin;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\MarketingViewAction;
/**
 * Class PluginLinksLoaderAction, plugin links loader.
 */
class PluginLinksLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $helper)
    {
        $this->config = $config;
        $this->request = $request;
        $this->plugin_helper = $helper;
    }
    public function hooks()
    {
        \add_filter('plugin_action_links_' . \plugin_basename($this->config->get_param('plugin.file')->get()), [$this, 'links_filter']);
    }
    public function links_filter(array $links) : array
    {
        $plugin_links = array('<a style="color:#007050; font-weight:bold" href="' . $this->plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\MarketingViewAction::class) . '">' . \__('Start here', 'dropshipping-xml-for-woocommerce') . '</a>', '<a href="' . $this->plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class) . '">' . \__('Settings', 'dropshipping-xml-for-woocommerce') . '</a>', '<a href="' . \__('https://www.wpdesk.net/docs/dropshipping-xml-woocommerce/', 'dropshipping-xml-for-woocommerce') . '">' . \__('Docs', 'dropshipping-xml-for-woocommerce') . '</a>');
        return \array_merge($plugin_links, $links);
    }
}
