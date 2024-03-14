// Import library.
import { subscribe, useSelect } from '../library/StoreAPI';
import OrderSummarySubtotalBlock from '../components/OrderSummarySubtotalBlock';

// Global Variables.
declare var acfwfObj: any;
declare var wp: any;
declare var wc: any;

interface ICouponSummary {
  coupon_code: string;
  content: string;
}

const CouponSummary = () => {
  const couponSummaries: ICouponSummary[] = useSelect((select: any) => {
    const cartData = select('wc/store/cart').getCartData();
    const { acfwf_block } = cartData.extensions;

    // Wait until the data is loaded, this is due to Store API is asynchronous.
    if (!acfwf_block || !acfwf_block.couponSummaries) return [];

    return acfwf_block.couponSummaries ?? [];
  });

  const { ExperimentalDiscountsMeta } = wc.blocksCheckout;

  if (!couponSummaries.length) {
    return null;
  }

  return (
    <ExperimentalDiscountsMeta>
      {couponSummaries.map((row) => (
        <OrderSummarySubtotalBlock label={row.coupon_code}>
          <div
            className={`acfwf-bogo-discount-summary-block`}
            dangerouslySetInnerHTML={{
              __html: row.content,
            }}
          />
        </OrderSummarySubtotalBlock>
      ))}
    </ExperimentalDiscountsMeta>
  );
};

export default function () {
  const { registerPlugin } = wp.plugins;

  registerPlugin('acfw-coupons-summary', {
    render: CouponSummary,
    scope: 'woocommerce-checkout',
  });
}
