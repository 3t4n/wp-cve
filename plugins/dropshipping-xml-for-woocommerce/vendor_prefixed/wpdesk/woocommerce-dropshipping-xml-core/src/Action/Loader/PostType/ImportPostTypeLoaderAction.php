<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\PostType;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
/**
 * Class ImportPostTypeLoaderAction, import post type loader.
 */
class ImportPostTypeLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional
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
        \add_action('init', [$this, 'register_post_type'], 10);
    }
    /**
     * Register post type for single imported file.
     *
     * @return void
     */
    public function register_post_type()
    {
        $args = array('description' => \__('Manage Dropshipping Xml.', 'dropshipping-xml-for-woocommerce'), 'public' => \false, 'publicly_queryable' => \false, 'show_ui' => \false, 'show_in_menu' => \false, 'query_var' => \true, 'has_archive' => \false, 'hierarchical' => \false, 'show_in_rest' => \false);
        \register_post_type(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::POST_TYPE_SLUG, $args);
    }
}
