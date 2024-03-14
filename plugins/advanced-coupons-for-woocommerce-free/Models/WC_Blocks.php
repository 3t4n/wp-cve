<?php
namespace ACFWF\Models;

// Exit if accessed directly.
use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Blocks\Cart_Integration;
use ACFWF\Models\Objects\Blocks\Checkout_Integration;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic for WooCommerce cart block.
 *
 * @since 4.5.8
 */
class WC_Blocks extends Base_Model implements Model_Interface {
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
     * Integration Interface
     * - This is used to enqueue css and js also for localizing data.
     * - To learn more please go to : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/checkout-block/integration-interface.md
     *
     * @since 4.5.8
     * @access public
     *
     * @param \Automattic\WooCommerce\Blocks\Integrations\IntegrationRegistry $integration_registry The integration registry.
     */
    public function cart_block_integration( $integration_registry ) {
        $integration_registry->register(
            new Cart_Integration(
                ACFWF(),
                $this->_constants,
                $this->_helper_functions
            )
        );
    }

    /**
     * Integration Interface
     * - This is used to enqueue css and js also for localizing data.
     * - To learn more please go to : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/checkout-block/integration-interface.md
     *
     * @since 4.5.8
     * @access public
     *
     * @param \Automattic\WooCommerce\Blocks\Integrations\IntegrationRegistry $integration_registry The integration registry.
     */
    public function checkout_block_integration( $integration_registry ) {
        $integration_registry->register(
            new Checkout_Integration(
                ACFWF(),
                $this->_constants,
                $this->_helper_functions
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Register hooks.
     *
     * @since 4.5.8
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        add_action( 'woocommerce_blocks_cart_block_registration', array( $this, 'cart_block_integration' ), 10, 1 );
        add_action( 'woocommerce_blocks_checkout_block_registration', array( $this, 'checkout_block_integration' ), 10, 1 );
    }
}
