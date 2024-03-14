// Global variables.
declare var wp: any;
declare var wc: any;

// WooCommerce Blocks Data Store.
export const { subscribe, select, dispatch, useSelect, useDispatch } = wp.data;
// WooCommerce Blocks Data Validation.
export const { VALIDATION_STORE_KEY } = wc.wcBlocksData;
export const WC_STORE_CART = 'wc/store/cart';

/**
 * Get the store.
 * - To learn more you can go to : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/data-store/cart.md
 *
 * @since 1.8.6
 * */
export const store = select('wc/store/cart');
