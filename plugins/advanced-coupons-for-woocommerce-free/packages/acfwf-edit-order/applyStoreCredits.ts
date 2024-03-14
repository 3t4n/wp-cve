import { blockMetabox, unblockMetabox } from './utils';

declare var woocommerce_admin_meta_boxes: any;
declare var acfwEditOrder: any;

const $ = jQuery;

/**
 * Register apply store credits related events.
 */
export default function applyStoreCreditsEvents() {
  $('body').on('click', 'button.acfw-apply-store-credits', applyStoreCreditsToOrder);
  disallowDeleteStoreCreditCoupon();
}

/**
 * Process apply store credits to order.
 */
function applyStoreCreditsToOrder() {
  // @ts-ignore
  const $button = $(this);
  const value = window.prompt(acfwEditOrder.apply_store_credits_prompt, acfwEditOrder.order_store_credits_amount);

  if (null === value) {
    return;
  }

  blockMetabox();

  $.ajax({
    url: woocommerce_admin_meta_boxes.ajax_url,
    data: {
      action: 'acfwf_apply_store_credits_to_order',
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

/**
 * Disallow deleting the store credit coupon in the edit order page.
 */
function disallowDeleteStoreCreditCoupon() {
  $(`ul.wc_coupon_list li span span:contains('${acfwEditOrder.store_credit_coupon_code}')`)
    .closest('.code')
    .removeClass('editable')
    .find('a.remove-coupon')
    .remove();
}
