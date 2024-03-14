// Global Variables.
declare var wc: any;

/**
 * This is update callback for WooCommerce Blocks.
 *  - To learn more you can go to : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/rest-api/extend-rest-api-update-cart.md
 *
 *  @since 4.5.9
 * */
const { extensionCartUpdate } = wc.blocksCheckout;

/**
 * Update the cart by invoking the extensionCartUpdate action.
 * - To learn more you can go to : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/rest-api/extend-rest-api-update-cart.md
 *
 * @since 4.5.9
 * @return void
 * */
export const dummyUpdateCart = () => {
  return extensionCartUpdate({
    namespace: 'acfwf_dummy_update',
    data: {}, // Dummy data.
  });
};
