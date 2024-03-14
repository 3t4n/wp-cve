// Import scripts.
import initACFWFShared from '../shared';
import registerCouponSummaryPlugin from '../shared/plugins/CouponSummaryPlugin';
import registeNoticesPlugin from '../shared/plugins/NoticesPlugin';

// Import CSS.
import '../shared/wc-block/index.scss';

// Global Variables.
declare var acfwfObj: any;
declare var wp: any;

// WooCommerce Blocks Hooks.
const { addAction } = wp.hooks;

// Initiate scripts.
initACFWFShared(); // Shared Components.
registeNoticesPlugin();
registerCouponSummaryPlugin();

/**
 * BOGO Coupon - Dummy Update Cart
 * - This is a dummy update cart to trigger the update cart action.
 * - This requires timeout because the cart is not yet updated when this action is fired.
 *
 * @since 4.5.8
 * */
const BOGODummyUpdateCart = () => {
  const { dummyUpdateCart } = acfwfObj.wc;
  const { hasBOGOCoupon } = acfwfObj.utils;
  if (hasBOGOCoupon()) {
    setTimeout(() => {
      dummyUpdateCart();
    }, 750);
  }
};

/**
 * Handle if item quantity is changed.
 * Code sample can be found here :
 * - https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/internal-developers/blocks/feature-flags-and-experimental-interfaces.md
 *
 * The hook is still experimental and might change in the future.
 *
 * @since 4.5.8
 * */
addAction('experimental__woocommerce_blocks-cart-set-item-quantity', 'acfwf', ({ product = {}, quantity = 1 }) =>
  BOGODummyUpdateCart()
);

/**
 * Handle if item is removed from cart.
 * Code sample can be found here :
 * - https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/internal-developers/blocks/feature-flags-and-experimental-interfaces.md
 * - https://github.com/woocommerce/woocommerce-blocks/issues/9885
 *
 * The hook is still experimental and might change in the future.
 *
 * @since 4.5.8
 * */
addAction('experimental__woocommerce_blocks-cart-remove-item', 'acfwf', ({ product = {}, quantity = 1 }) =>
  BOGODummyUpdateCart()
);
