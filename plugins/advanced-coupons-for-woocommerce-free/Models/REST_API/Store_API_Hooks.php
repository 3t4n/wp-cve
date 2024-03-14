<?php
namespace ACFWF\Models\REST_API;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of store api.
 *
 * @since 4.5.8
 */
class Store_API_Hooks extends Base_Model implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Class constructor.
     *
     * @since 4.5.8
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        parent::__construct( $main_plugin, $constants, $helper_functions );
        $main_plugin->add_to_all_plugin_models( $this );
    }

    /**
     * Extend Store API Coupon Endpoint.
     *
     * @since 4.5.8
     * @access public
     */
    public function extend_store_api_coupon_endpoint() {
        \ACFWF\Models\REST_API\Store_API_Extend_Endpoint::init();
    }

    /**
     * Extend Store API Dummy Update.
     *
     * This function is required to update block data store. Some use cases are:
     * - Adding BOGO coupon, where the new item will be added to the block data store.
     *
     * @since 4.5.8
     * @access public
     */
    public function extend_store_api_dummy_update() {
        woocommerce_store_api_register_update_callback(
            array(
                'namespace' => 'acfwf_dummy_update',
                'callback'  => function(){}, // Dummy callback.
            )
        );
    }

    /**
     * Execute Hooks.
     *
     * @since 4.5.8
     * @access public
     */
    public function run() {
        add_action( 'woocommerce_blocks_loaded', array( $this, 'extend_store_api_coupon_endpoint' ) );
        add_action( 'woocommerce_blocks_loaded', array( $this, 'extend_store_api_dummy_update' ) );
    }
}
