<?php
/**
 * Utility functions
 *
 * @package WooCommerce Utils
 * @subpackage Utils
 */

use Automattic\WooCommerce\Blocks\BlockTypesController;
use Automattic\WooCommerce\Packages;

/**
 * Get the Attribute Taxonomy Data Store.
 *
 * @return Attribute_Taxonomy_Data_Store
 */
function wc_atds() {
    return WC_Data_Store::load( 'attribute_taxonomy' );
}

if ( ! function_exists( 'wc_deregister_all_blocks' ) ) :
    /**
     * Deregisters all WooCommerce blocks.
     */
	function wc_deregister_all_blocks() {
        //phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( is_admin() && str_starts_with( wc_clean( wp_unslash( $_GET['page'] ?? '' ) ), 'wc-' ) ) {
            return;
        }

        if ( did_action( 'plugins_loaded' ) && ! doing_action( 'plugins_loaded' ) ) {
            wc_doing_it_wrong(
                __FUNCTION__,
                'This function needs to be called during the plugins_loaded action.',
                '1.0.0',
            );
            return;
        }

        wc_override_packages_class();
        wc_override_packages_class();
        add_action(
            'init',
            static fn() => remove_filter_by_class_method(
                'init',
                BlockTypesController::class,
                'register_blocks',
                10,
            ),
            9,
        );
	}
endif;

if ( ! function_exists( 'wc_override_packages_class' ) ) :
    /**
     * Overrides the packages class to remove the blocks.
     */
    function wc_override_packages_class() {
        if ( ! class_exists( Packages::class ) ) {
            return;
        }

        $override = new class() extends Packages {
            /**
             * Constructor
             */
            public function __construct() {
                // Empty constructor.
            }

            /**
             * Removes the blocks
             */
            public function remove_woocommerce_blocks(): void {
                unset( self::$packages['woocommerce-blocks'] );
            }
        };
        $override->remove_woocommerce_blocks();
    }
endif;
