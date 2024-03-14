import { blockMetabox, unblockMetabox } from './utils';

declare var woocommerce_admin_meta_boxes: any;

const $ = jQuery;

export default function registerRefundStoreCreditsDiscountEvents() {
  $('body').on('click', 'button.acfw-refund-store-credits', refundStoreCreditsDiscount);
}

function refundStoreCreditsDiscount() {
  // @ts-ignore
  const $button = $(this);

  const value = window.prompt($button.data('prompt'));

  if (null === value) {
    return;
  }

  blockMetabox();

  $.ajax({
    url: woocommerce_admin_meta_boxes.ajax_url,
    data: {
      action: 'acfwf_refund_store_credits_discount_from_order',
      order_id: woocommerce_admin_meta_boxes.post_id,
      amount: value,
      security: woocommerce_admin_meta_boxes.order_item_nonce,
    },
    type: 'POST',
    success: function (response) {
      if (true === response.success) {
        // reload window
        window.location.reload();
      } else {
        window.alert(response.data.error);
      }

      unblockMetabox();
    },
  });
}
