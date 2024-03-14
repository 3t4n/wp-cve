// Import library.
import { store } from '../library/StoreAPI';

/**
 * Check if there's a BOGO coupon.
 *
 * @since 4.5.8
 * @return List of BOGO coupons.
 * */
export const hasBOGOCoupon = () => {
  const BOGOCoupons = store.getCartData().coupons.filter((coupon: any) => coupon.discount_type === 'acfw_bogo');
  return !!BOGOCoupons;
};
