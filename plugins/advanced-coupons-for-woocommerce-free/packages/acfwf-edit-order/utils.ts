/**
 * Block order metabox.
 */
export function blockMetabox() {
  // @ts-ignore
  jQuery('#woocommerce-order-items').block({
    message: null,
    overlayCSS: {
      background: '#fff',
      opacity: 0.6,
    },
  });
}

/**
 * Unblock order metabox.
 */
export function unblockMetabox() {
  // @ts-ignore
  jQuery('#woocommerce-order-items').unblock();
}
