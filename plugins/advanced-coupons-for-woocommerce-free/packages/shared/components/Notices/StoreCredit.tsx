// Import library.
import config from '../../../acfwf-checkout-block/config.json';
import { getSetting } from '../../../shared/library/BlockIntegration';
import { store, dispatch, useSelect, WC_STORE_CART } from '../../library/StoreAPI';
import { CORE_NOTICE, ID_NOTICE } from '../../../shared/wc-block/wc_block_notice';
import { WC_CHECKOUT } from '../../../shared/library/Context';

const $ = jQuery;

/**
 * Show store credit notice.
 *
 * @since 4.6.0
 * @param {string} notice_store_credits_text - Notice store credits text.
 */
const showStoreCreditNotice = (notice_store_credits_text: string) => {
  // Debounce event
  setTimeout(function () {
    dispatch(CORE_NOTICE).createNotice('error', notice_store_credits_text, {
      context: WC_CHECKOUT,
      id: ID_NOTICE.ACFWF_NOTICE_STORE_CREDIT,
    });

    $('.acfw-reapply-sc-discount').on('click', reapplyStoreCreditsLink);
  }, 400);
};

/**
 * Reapply store credits link and perform auto-scroll and focus.
 *
 * @since 4.6.0
 */
const reapplyStoreCreditsLink = () => {
  const storeCreditsAccordion = $('.acfw-checkout-ui-block');
  const storeCreditsBlock = storeCreditsAccordion.find('.acfw-store-credits-checkout-ui');
  const accordion = storeCreditsAccordion.find('h3');

  if (!storeCreditsBlock.hasClass('show')) {
    accordion.trigger('click');
  }

  // Debounce event
  setTimeout(function () {
    window.scroll({
      top: storeCreditsAccordion?.offset()?.top,
      behavior: 'smooth',
    });

    const inputToFocus = storeCreditsAccordion.find('input');
    inputToFocus.trigger('focus');
  }, 400);
};

/**
 * Store Credit Notice function.
 *
 * @since 4.6.0
 */
export default function () {
  const getHasCalculatedShipping = useSelect((select: any) => {
    return select(WC_STORE_CART).getHasCalculatedShipping();
  });

  // Get the settings from the integration interface.
  const { store_credits } = getSetting(config.integration);

  if (!store_credits) return null;

  const { apply_type, notice_store_credits_text } = store_credits;

  // Get data from Store API.
  const { acfwf_block } = store.getCartData().extensions;

  // If there's a store credit applied and have notice error
  if ('coupon' !== apply_type && getHasCalculatedShipping && acfwf_block.store_credits.notice) {
    showStoreCreditNotice(notice_store_credits_text);
  }
}
