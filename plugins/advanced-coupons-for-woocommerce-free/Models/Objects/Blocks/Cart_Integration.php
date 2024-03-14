<?php
namespace ACFWF\Models\Objects\Blocks;

use ACFWF\Abstracts\Base_Model;
use ACFWF\Models\Objects\Vite_App;
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Class for integrating with WooCommerce Blocks
 *
 * @since 4.5.8
 */
class Cart_Integration extends Base_Model implements IntegrationInterface {
    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * The name of the integration.
     * This is used internally to identify the integration and should be unique.
     *
     * @since 4.5.8
     * @access public
     *
     * @return string
     */
    public function get_name() {
        return 'acfwf-wc-cart-block';
    }

    /**
     * When called invokes any initialization/setup for the integration.
     *
     * @since 4.5.8
     * @access public
     */
    public function initialize() {
        $this->register_scripts();
    }

    /**
     * Register Scripts
     * - This is where you will register custom scripts for your block
     *
     * @since 4.5.8
     * @access private
     */
    private function register_scripts() {
        // Register Script.
        $vite_app = new Vite_App(
            'acfwf-wc-cart-block-integration', // Don't forget to register this handle in the get_script_handles() or get_editor_script_handles() method.
            'packages/acfwf-cart-block/index.tsx',
        );
        $vite_app->register();
    }

    /**
     * Returns an array of script handles to enqueue in the frontend context.
     *
     * @since 4.5.8
     * @access public
     *
     * @return string[]
     */
    public function get_script_handles() {
        return array( 'acfwf-wc-cart-block-integration' );
    }

    /**
     * Returns an array of script handles to enqueue in the editor context.
     *
     * @since 4.5.8
     * @access public
     *
     * @return string[]
     */
    public function get_editor_script_handles() {
        return array();
    }

    /**
     * An array of key, value pairs of data made available to the block on the client side.
     * - To access this data in the block see code sample here : https://github.com/agungsundoro/woocommerce-blocks-test/blob/0a520112707e7e09af67e3fbbbb8876a846e6c56/packages/acfw-wc-blocks/cart/data.tsx#L19-L26
     *
     * @since 4.5.8
     * @access public
     *
     * @return array
     */
    public function get_script_data() {
        return array();
    }
}
