<?php

namespace UpsFreeVendor\Octolize\Blocks\PickupPoint;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class Registrator implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    private string $integration_name;
    /**
     * @var string
     */
    private string $meta_data_name;
    /**
     * @var string
     */
    private string $plugin_dir;
    /**
     * @var string
     */
    private string $plugin_file;
    private \UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData $integration_data;
    public function __construct(\UpsFreeVendor\Octolize\Blocks\PickupPoint\IntegrationData $integration_data, string $plugin_dir, string $plugin_file)
    {
        $this->integration_data = $integration_data;
        $this->integration_name = $integration_data->get_integration_name();
        $this->meta_data_name = $integration_data->get_meta_data_name();
        $this->plugin_dir = $plugin_dir;
        $this->plugin_file = $plugin_file;
    }
    public function hooks()
    {
        \add_action('woocommerce_blocks_checkout_block_registration', function ($integration_registry) {
            $integration_registry->register(new \UpsFreeVendor\Octolize\Blocks\PickupPoint\CheckoutIntegration($this->integration_data, $this->plugin_dir, $this->plugin_file));
        });
        (new \UpsFreeVendor\Octolize\Blocks\PickupPoint\StoreEndpoint($this->integration_name, $this->meta_data_name))->hooks();
    }
}
