<?php

namespace FedExVendor\Octolize\ShippingExtensions;

use FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FedExVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use FedExVendor\WPDesk_Plugin_Info;
/**
 * .
 */
class Assets implements \FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    use AdminPage;
    public const HANDLE = 'octolize-shipping-extensions';
    /**
     * @var string
     */
    private $assets_url;
    /**
     * @var int
     */
    private $version;
    /**
     * @param string $assets_url .
     * @param int $version .
     */
    public function __construct(string $assets_url, int $version)
    {
        $this->assets_url = $assets_url;
        $this->version = $version;
    }
    /**
     * @return void
     */
    public function hooks() : void
    {
        \add_action('admin_enqueue_scripts', [$this, 'register_scripts']);
    }
    /**
     * @return void
     */
    public function register_scripts() : void
    {
        if (!$this->is_shipping_extensions_page()) {
            return;
        }
        \wp_enqueue_style(self::HANDLE, $this->assets_url . 'dist/css/shipping-extensions.css', [], $this->version);
        \wp_enqueue_script(self::HANDLE, $this->assets_url . 'dist/js/shipping-extensions.js', ['jquery'], $this->version, \true);
    }
}
