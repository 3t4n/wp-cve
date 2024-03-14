// Import library.
import { dispatch } from '../../shared/library/StoreAPI';
import { WC_CHECKOUT, WC_CART } from '../../shared/library/Context';
import { CORE_NOTICE, ID_NOTICE } from '../../shared/wc-block/wc_block_notice';
import CartConditions from '../components/Notices/CartConditions';
import BOGODeals from '../components/Notices/BOGODeals';
import StoreCredit from '../components/Notices/StoreCredit';

const CONTEXTS = [WC_CART, WC_CHECKOUT];

// Global Variables.
declare var wp: any;

/**
 * Notices Plugin for handling custom notices on the frontend.
 *
 * @since 4.6.0
 */
const Notices = () => {
  // Since the removeNotices context param can only accept a single string parameter, we need to dispatch both WC_CART and WC_CHECKOUT.
  CONTEXTS.forEach((context) => {
    dispatch(CORE_NOTICE).removeNotices(
      Object.values(ID_NOTICE).filter((id) => id),
      context
    );
  });

  CartConditions();
  BOGODeals();
  StoreCredit();
};

export default function () {
  const { registerPlugin } = wp.plugins;

  registerPlugin('acfwf-notices', {
    render: Notices,
    scope: 'woocommerce-checkout',
  });
}
