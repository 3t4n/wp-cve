// Import Library.
import config from '../config.json';
import { getSetting } from '../../shared/library/BlockIntegration';
import StoreCreditDiscountRow from './store-credit-discount-row';
import StoreCreditForm from './store-credit-form';

// Global variables.
declare var wp: any;
declare var wc: any;

/**
 * Initiate Store Credit Form function.
 *
 * @since 4.5.9
 * */
export function initStoreCreditForm() {
  const { ExperimentalDiscountsMeta, ExperimentalOrderMeta } = wc.blocksCheckout;
  const { registerPlugin } = wp.plugins;

  // Get the settings from the integration interface.
  const { store_credits } = getSetting(config.integration);
  const { apply_type, display_store_credits_redeem_form, store_credits_module } = store_credits;

  if (store_credits_module && 'yes' === display_store_credits_redeem_form) {
    // Register slot and fill for the store credit form.
    registerPlugin('acfw-store-credit-discount-form', {
      render: () => {
        return (
          <ExperimentalOrderMeta>
            <StoreCreditForm />
          </ExperimentalOrderMeta>
        );
      },
      scope: 'woocommerce-checkout',
    });

    // Register slot and fill for displaying discount row.
    if ('coupon' !== apply_type) {
      registerPlugin('acfw-store-credit-discount-row', {
        render: () => {
          return (
            <ExperimentalDiscountsMeta>
              <StoreCreditDiscountRow />
            </ExperimentalDiscountsMeta>
          );
        },
        scope: 'woocommerce-checkout',
      });
    }
  }
}
