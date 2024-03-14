<?php

namespace InspireLabs\WoocommerceInpost;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;
use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;

use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\StoreApi;


defined( 'ABSPATH' ) || exit;
define( 'WOOCOMMERCE_INPOST_VERSION', '1.2.0' );
/**
 * Class for integrating with WooCommerce Blocks
 */
class EasyPackWooBlocks implements IntegrationInterface
{

    /**
     * The name of the integration.
     *
     * @return string
     */
    public function get_name() {
        return 'woocommerce-inpost';
    }

    /**
     * When called invokes any initialization/setup for the integration.
     */
    public function initialize() {

        $plugin_data = new EasyPack();
        $script_url = $plugin_data->getPluginJs() . 'blocks/woo-blocks-integration.js';


        //$style_url = $plugin_data->getPluginCss() . 'woo-blocks-integration.css';

        $dep = array('dependencies' => array('wp-blocks', 'wp-components', 'wp-data', 'wp-element'), 'version' => '1.2.0');

        $script_asset = $dep;

        wp_register_script(
            'wc-blocks-integration',
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );

    }

    /**
     * Returns an array of script handles to enqueue in the frontend context.
     *
     * @return string[]
     */
    public function get_script_handles() {
        return array( 'wc-blocks-integration' );
    }

    /**
     * Returns an array of script handles to enqueue in the editor context.
     *
     * @return string[]
     */
    public function get_editor_script_handles() {
        return array( 'wc-blocks-integration' );
    }

    /**
     * An array of key, value pairs of data made available to the block on the client side.
     *
     * @return array
     */
    public function get_script_data() {
        //$woocommerce_example_plugin_data = some_expensive_serverside_function();
        return [
            'expensive_data_calculation' => '' //$woocommerce_example_plugin_data
        ];
    }

    /**
     * Get the file modified time as a cache buster if we're in dev mode.
     *
     * @param string $file Local path to the file.
     * @return string The cache buster value to use for the given file.
     */
    protected function get_file_version( $file ) {
        if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
            return filemtime( $file );
        }

        return '1.2.0';
    }


}