<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;

use function Crontrol\Event\add;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the implementation of features under the General settings.
 * Public Model.
 *
 * @since 4.5.3
 */
class General extends Base_Model implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that houses the \WC_Discounts object instance used in the cart or order.
     *
     * @since 4.5.3
     * @access private
     *
     * @var null|\WC_Discounts
     */
    private $_wc_discounts = null;

    /**
     * Property that houses the list of discounted item product ids that are valid to the applied coupons in the cart or order.
     *
     * @since 4.5.3
     * @access private
     *
     * @var int[]
     */
    private $_discounted_items = array();

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.5.3
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

    /*
    |--------------------------------------------------------------------------
    | Implement always use regular price feature.
    |--------------------------------------------------------------------------
     */

    /**
     * Set the \WC_Discounts object instance as a class property.
     *
     * @since 4.5.3
     * @access public
     *
     * @param array         $items List of cart or order items.
     * @param \WC_Discounts $wc_discounts WC Discounts object instance.
     */
    public function set_wc_discounts_object( $items, $wc_discounts ) {
        $this->_wc_discounts = $wc_discounts;
        return $items;
    }

    /**
     * Append the product ID to the discounted items list private property in this class.
     *
     * @since 4.5.3
     * @access public
     *
     * @param float                        $discount Discount amount.
     * @param float                        $price_to_discount Price to discount.
     * @param array|\WC_Order_Item_Product $item_object Cart item data or Product order item object.
     */
    public function append_product_to_discounted_items_list( $discount, $price_to_discount, $item_object ) {

        if ( is_array( $item_object ) && $item_object['data'] instanceof \WC_Product ) { // Handle cart item data.
            $this->_discounted_items[] = $item_object['data']->get_id();
        } elseif ( $item_object instanceof \WC_Order_Item_Product ) { // Handle order item object data.
            $this->_discounted_items[] = $item_object->get_product_id();
        }

        return $discount;
    }

    /**
     * Disable product sale when a coupon is applied and the "Always use regular price" setting is turned on.
     *
     * @since 4.5.3
     * @access public
     *
     * @param string      $price   Product price.
     * @param \WC_Product $product Product object.
     * @return bool Filtered product price.
     */
    public function always_use_regular_price_for_coupon_discounted_products( $price, $product ) {

        // Don't proceed when the \WC_Discounts object is not yet set or when the setting is not enabled.
        if ( 'all_valid' !== get_option( Plugin_Constants::ALWAYS_USE_REGULAR_PRICE ) ) {
            return $price;
        }

        // Don't proceed when cart has no applied coupons.
        if ( ! $this->_is_product_valid_for_coupons_in_cart( $product ) ) {
            return $price;
        }

        return $product->get_regular_price();
    }

    /**
     * Get the applied coupon codes from either the cart or order.
     *
     * @since 4.5.3
     * @access private
     *
     * @return string[] List of coupon codes.
     */
    private function _get_applied_coupon_codes_from_wc_discounts() {

        if ( $this->_wc_discounts instanceof \WC_Discounts ) {
            if ( $this->_wc_discounts->get_object() instanceof \WC_Cart ) {
                return $this->_wc_discounts->get_object()->get_applied_coupons();
            }

            if ( $this->_wc_discounts->get_object() instanceof \WC_Order ) {
                return $this->_wc_discounts->get_object()->get_coupon_codes();
            }
        } elseif ( ! is_admin() && \WC()->cart instanceof \WC_Cart ) {
            return \WC()->cart->get_applied_coupons();
        }

        return array();
    }

    /**
     * Get the applied coupon objects from either the cart or order.
     *
     * @since 4.5.3
     * @access private
     *
     * @return \WC_Coupon[] List of coupon objects.
     */
    private function _get_coupon_objects_from_wc_discounts() {

        if ( $this->_wc_discounts instanceof \WC_Discounts ) {
            if ( $this->_wc_discounts->get_object() instanceof \WC_Cart ) {
                return $this->_wc_discounts->get_object()->get_coupons();
            }

            if ( $this->_wc_discounts->get_object() instanceof \WC_Order ) {
                return $this->_helper_functions->get_coupon_objects_from_order( $this->_wc_discounts->get_object() );
            }
        } elseif ( ! is_admin() && \WC()->cart instanceof \WC_Cart ) {
            return \WC()->cart->get_coupons();
        }

        return array();
    }

    /**
     * Validate if the product is valid for at least one of the coupons applied in the cart.
     *
     * @since 4.5.3
     * @access private
     *
     * @param \WC_Product $product Product object.
     * @return bool True if valid, false otherwise.
     */
    private function _is_product_valid_for_coupons_in_cart( $product ) {

        $applied_coupons = $this->_get_applied_coupon_codes_from_wc_discounts();

        // Return as invalid when there are no coupons applied on the cart.
        if ( empty( $applied_coupons ) ) {
            return false;
        }

        $is_valid = false;

        // Set $is_valid as true when the product is valid for at least one of the coupon applied in the cart.
        foreach ( $this->_get_coupon_objects_from_wc_discounts() as $coupon ) {
            if ( $coupon->is_valid_for_product( $product ) ) {
                $is_valid = true;
                break;
            }
        }

        if ( $is_valid && ! empty( $this->_discounted_items ) ) {
            $is_valid = in_array( $product->get_id(), $this->_discounted_items, true );
        }

        return $is_valid;
    }

    /**
     * Automatically remove coupon for failed/cancelled orders.
     *
     * @since 4.5.6
     * @access public
     *
     * @param int       $order_id    Order ID.
     * @param string    $prev_status Previous status.
     * @param string    $new_status  New Status.
     * @param \WC_Order $order       Order object.
     */
    public function auto_remove_coupons_for_failed_orders( $order_id, $prev_status, $new_status, $order ) {

        if ( 'yes' !== get_option( Plugin_Constants::REMOVE_COUPONS_FOR_FAILED_ORDERS ) || // Skip when the setting is not enabled.
            ! in_array( $new_status, array( 'failed', 'cancelled' ), true ) || // Skip when the order is not failed or cancelled.
            empty( $order->get_coupons() ) // Skip when the order has no coupons applied.
        ) {
            return;
        }

        foreach ( $order->get_coupons() as $coupon_item ) {
            $order->remove_coupon( $coupon_item->get_code() );
        }

        // Prevent infinite loop.
        remove_action( 'woocommerce_order_status_changed', array( $this, 'auto_remove_coupons_for_failed_orders' ), 10, 4 );

        $order->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Advanced Features
    |--------------------------------------------------------------------------
     */

    /**
     * Enable integrity check for JS/CSS asset files loaded via Vite.
     *
     * @since 4.5.9
     * @access public
     *
     * @param bool $value Filter value.
     * @return bool Filtered value.
     */
    public function enable_integrity_check_for_vite_assets( $value ) {
        return 'yes' === get_option( $this->_constants->ENABLE_ASSET_INTEGRITY_CHECK, 'no' );
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute General class.
     *
     * @since 4.5.3
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        add_filter( 'woocommerce_coupon_get_items_to_validate', array( $this, 'set_wc_discounts_object' ), 10, 2 );
        add_filter( 'woocommerce_product_get_price', array( $this, 'always_use_regular_price_for_coupon_discounted_products' ), 10, 2 );
        add_filter( 'woocommerce_product_variation_get_price', array( $this, 'always_use_regular_price_for_coupon_discounted_products' ), 10, 2 );
        add_filter( 'woocommerce_coupon_get_discount_amount', array( $this, 'append_product_to_discounted_items_list' ), 10, 3 );
        add_action( 'woocommerce_order_status_changed', array( $this, 'auto_remove_coupons_for_failed_orders' ), 10, 4 );
        add_filter( 'acfw_enable_subresource_integrity_check', array( $this, 'enable_integrity_check_for_vite_assets' ), 1 );
    }
}
