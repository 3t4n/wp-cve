<?php

namespace blocks;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use WC_Gateway_Mypos;
use WC_Mypos_Payments;

/**
 * Back-end block init
 */
final class WC_Gateway_Mypos_Blocks_Support extends AbstractPaymentMethodType
{

    /**
     * The gateway instance.
     *
     * @var WC_Gateway_Mypos
     */
    private $gateway;

    protected $name = 'mypos_virtual';

    public function initialize()
    {
        $this->settings = get_option('woocommerce_mypos_virtual_settings', []);
        $this->gateway = new WC_Gateway_Mypos();
    }

    public function is_active()
    {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles()
    {
        $script_path        = '/assets/js/frontend/blocks.js';
        $script_asset_path  = WC_Mypos_Payments::plugin_abspath() . 'assets/js/frontend/blocks.asset.php';
        $script_asset       = file_exists( $script_asset_path)
            ? require($script_asset_path)
            : array('dependencies' => array(), 'version' => '1.3.30');
        $script_url         = WC_Mypos_Payments::plugin_url() . $script_path;

        wp_register_script(
            'mypos_virtual',
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );

        if (function_exists('wp_set_script_translations')){
            wp_set_script_translations('mypos_virtual', 'woocommerce-gateway-mypos', WC_Mypos_Payments::plugin_abspath() . 'languages/');
        }

        return ['mypos_virtual'];
    }

    public function get_payment_method_data()
    {
        return [
            'title'         => $this->get_setting('title'),
            'description'   => $this->get_setting('description'),
			'path' 			=> plugins_url(),
            'supports'      => array_filter($this->gateway->supports, [$this->gateway, 'supports'])
        ];
    }
}
