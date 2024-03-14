<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig;
/**
 * Class PluginConfig, configuration class for plugin.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Config
 */
class PluginConfig extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig
{
    const ID = 'plugin';
    /**
     * @var string
     */
    private $plugin_name;
    /**
     *
     * @var string
     */
    private $text_domain;
    /**
     *
     * @var string
     */
    private $version;
    /**
     *
     * @var string
     */
    private $plugin_file;
    /**
     *
     * @var string
     */
    private $plugin_dir;
    /**
     *
     * @var string
     */
    private $plugin_slug;
    /**
     * 
     * @var string
     */
    private $marketing_slug;
    public function __construct(string $plugin_name, string $text_domain, string $version, string $plugin_file, string $plugin_dir, string $plugin_slug, string $marketing_slug = 'woocommerce-dropshipping-xml')
    {
        $this->plugin_name = $plugin_name;
        $this->text_domain = $text_domain;
        $this->version = $version;
        $this->plugin_file = $plugin_file;
        $this->plugin_dir = \trailingslashit($plugin_dir);
        $this->plugin_slug = $plugin_slug;
        $this->marketing_slug = $marketing_slug;
    }
    public function get() : array
    {
        $dir_url = \plugin_dir_url($this->plugin_file);
        $core_dir = $this->plugin_dir . 'vendor_prefixed/wpdesk/woocommerce-dropshipping-xml-core/';
        $core_dir_url = $dir_url . \str_replace($this->plugin_dir, '', $core_dir);
        return ['name' => $this->plugin_name, 'version' => $this->version, 'text_domain' => $this->text_domain, 'file' => $this->plugin_file, 'dir' => $this->plugin_dir, 'dir_url' => $dir_url, 'core_dir' => $core_dir, 'core_dir_url' => $core_dir_url, 'persistance_prefix' => 'dropshipping_xml', 'development' => \true, 'marketing_slug' => $this->marketing_slug, 'slug' => $this->plugin_slug];
    }
    public function get_id() : string
    {
        return self::ID;
    }
}
