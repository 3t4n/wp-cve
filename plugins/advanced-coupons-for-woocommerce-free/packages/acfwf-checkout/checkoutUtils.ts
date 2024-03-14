const $ = jQuery;

export default function checkoutGenericEvents() {
  $('body').on('acfw-blockCheckout', blockCheckout);
  $('body').on('acfw-unblockCheckout', unblockCheckout);
}

export function blockCheckout() {
  $('.woocommerce form.woocommerce-checkout')
    .addClass('processing')
    // @ts-ignore
    .block({
      message: null,
      overlayCSS: {
        background: '#fff',
        opacity: 0.6,
      },
    });
}

export function unblockCheckout() {
  $('.woocommerce-error, .woocommerce-message').remove();
  // @ts-ignore
  $('.woocommerce form.woocommerce-checkout').removeClass('processing').unblock();
}
