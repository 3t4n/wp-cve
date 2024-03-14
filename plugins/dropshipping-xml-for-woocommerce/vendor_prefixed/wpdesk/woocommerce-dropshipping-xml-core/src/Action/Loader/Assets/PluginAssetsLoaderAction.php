<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\Assets;
/**
 * Class PluginAssetsLoaderAction, loads plugin assets.
 */
class PluginAssetsLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional
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
    public function isActive() : bool
    {
        return $this->plugin_helper->is_plugin_page($this->request->get_param('get.page')->getAsString(), $this->request->get_param('get.action')->getAsString());
    }
    public function hooks()
    {
        \add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts'], 90);
    }
    public function enqueue_scripts()
    {
        $suffix = \true === $this->config->get_param('plugin.development')->get() ? '' : '.min';
        $version = $this->config->get_param('plugin.version')->get();
        \wp_register_style('dropshipping_admin', $this->config->get_param('assets.css.core_dir_url')->get() . 'admin' . $suffix . '.css', [], $version);
        \wp_enqueue_style('dropshipping_admin');
        \wp_register_style('dropshipping_simple_xml', $this->config->get_param('assets.js.core_dir_url')->get() . 'simpleXML/css/simpleXML.css', [], $version);
        \wp_enqueue_style('dropshipping_simple_xml');
        \wp_enqueue_script('select2');
        \wp_enqueue_script('jquery-ui-draggable');
        \wp_enqueue_script('jquery-ui-droppable');
        \wp_register_script('dropshipping_admin', $this->config->get_param('assets.js.core_dir_url')->get() . 'admin' . $suffix . '.js', ['jquery'], $version, \true);
        \wp_enqueue_script('dropshipping_admin');
        \wp_register_script('dropshipping_simple_xml', $this->config->get_param('assets.js.core_dir_url')->get() . 'simpleXML/js/simpleXML.js', ['jquery', 'jquery-ui-draggable', 'jquery-ui-droppable'], $version, \true);
        \wp_enqueue_script('dropshipping_simple_xml');
        \wp_enqueue_style('dropshipping_marketing_modal_css', $this->config->get_param('assets.css.core_dir_url')->get() . 'marketing/modal.css', [], $version);
        \wp_enqueue_style('dropshipping_marketing_css', $this->config->get_param('assets.css.core_dir_url')->get() . 'marketing/marketing.css', [], $version);
        \DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\Assets::enqueue_assets();
        \DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\Assets::enqueue_owl_assets();
    }
}
