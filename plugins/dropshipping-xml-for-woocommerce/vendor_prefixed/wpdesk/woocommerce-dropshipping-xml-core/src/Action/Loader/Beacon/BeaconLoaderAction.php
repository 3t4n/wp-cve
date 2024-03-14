<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Beacon;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Beacon\BeaconPro;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Beacon\BeaconShowStrategy;
/**
 * Class BeaconLoaderAction, Beacon loader.
 */
class BeaconLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional
{
    const PL_LANG_CODE = 'pl_PL';
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
        \add_action('init', [$this, 'init_beacon'], 10);
    }
    public function init_beacon()
    {
        $beacon_id = \get_locale() === self::PL_LANG_CODE ? '2ccdd1a3-4363-4396-bc8e-c3f74d8c0b27' : '567d6202-287a-4485-93ad-ef43fb84048c';
        $beacon = new \DropshippingXmlFreeVendor\WPDesk\Beacon\BeaconPro($beacon_id, new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Beacon\BeaconShowStrategy(), $this->config->get_param('plugin.dir_url')->getAsString() . 'vendor_prefixed/wpdesk/wp-helpscout-beacon/assets/');
        $beacon->hooks();
    }
}
