// Import library.
import { dispatch, useSelect, VALIDATION_STORE_KEY, WC_STORE_CART } from '../../library/StoreAPI';
import { CORE_NOTICE, ID_NOTICE } from '../../../shared/wc-block/wc_block_notice';
import { decodeHTML, isHTML } from '../../library/html';
import { WC_CART } from '../../../shared/library/Context';

/**
 * Get cart error related to the REST API coupon with HTML-encoded message.
 *
 * @since 4.6.0
 *
 * @param {Array} cartErrors - An array of cart errors.
 * @returns {Object|null} - Returns cart error related to the REST API coupon with HTML-encoded message, or null if not found.
 */
const getRestCartCouponError = (cartErrors: Array<any>): object | null => {
  return cartErrors.find(
    (error: { code: string; message: string }) =>
      error.code === 'woocommerce_rest_cart_coupon_error' && isHTML(error.message)
  );
};

/**
 * Cart Conditions Notice function.
 *
 * @since 4.6.0
 */
export default function () {
  const couponError = useSelect((select: any) => {
    return select(WC_STORE_CART).getCartErrors();
  });

  const validationError = useSelect((select: any) => {
    return select(VALIDATION_STORE_KEY).getValidationError('coupon');
  });

  const restCartCouponError = getRestCartCouponError(couponError);

  if (restCartCouponError && validationError) {
    // Override message error.
    dispatch(VALIDATION_STORE_KEY).setValidationErrors({
      coupon: {
        message: ' ',
        hidden: false,
      },
    });

    // Show error on notice banner.
    const errorMessage = (restCartCouponError as { code: string; message: string }).message || '';
    dispatch(CORE_NOTICE).createNotice('error', decodeHTML(errorMessage), {
      context: WC_CART,
      id: ID_NOTICE.ACFWF_NOTICE_CART_CONDITIONS,
    });
  }
}
