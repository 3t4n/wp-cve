import { blockMetabox, unblockMetabox } from './utils';

declare var woocommerce_admin_meta_boxes: any;
declare var woocommerce_admin: any;
declare var accounting: any;
declare var wcTracks: any;
declare var acfwEditOrder: any;

const $ = jQuery;

/**
 * Register store credit refund related events.
 */
export default function refundOrderAsStoreCreditsEvents() {
  $('#woocommerce-order-items').on('click', 'button.refund-items', appendStoreCreditRefundButton);
  $('#woocommerce-order-items').on('change keyup', '.wc-order-refund-items #refund_amount', refundAmountChanged);
  $('body').on('click', 'button#acfw-refund-as-store-credits', doRefund);
  $('body').on('order-totals-recalculate-complete', movePaymentSummaryBelowOrderTotals);

  movePaymentSummaryBelowOrderTotals();
}

/**
 * Append store credit refund button when "refund" button is clicked for the first time.
 */
function appendStoreCreditRefundButton() {
  if ($('#acfw-refund-as-store-credits').length) {
    return;
  }

  $('#post-body .refund-actions').prepend(
    `<button type="button" id="acfw-refund-as-store-credits" class="button button-primary">${acfwEditOrder.button_text}</button>`
  );

  $('#post-body button.refund-items').on('click', appendStoreCreditRefundButton);
}

/**
 * Adjust button text when refund amount is changed.
 */
function refundAmountChanged() {
  // @ts-ignore
  const value = $(this).val();
  const total = accounting.unformat(value, woocommerce_admin.mon_decimal_point);

  $('button#acfw-refund-as-store-credits .amount').text(
    accounting.formatMoney(total, {
      symbol: woocommerce_admin_meta_boxes.currency_format_symbol,
      decimal: woocommerce_admin_meta_boxes.currency_format_decimal_sep,
      thousand: woocommerce_admin_meta_boxes.currency_format_thousand_sep,
      precision: woocommerce_admin_meta_boxes.currency_format_num_decimals,
      format: woocommerce_admin_meta_boxes.currency_format,
    })
  );
}

/**
 * Process manual refund via store credits.
 */
function doRefund() {
  blockMetabox();

  if (!window.confirm(woocommerce_admin_meta_boxes.i18n_do_refund)) {
    unblockMetabox();
  }

  const refund_amount = $('input#refund_amount').val();
  const refund_reason = $('input#refund_reason').val();
  const refunded_amount = $('input#refunded_amount').val();

  // Get line item refunds
  const line_item_qtys: any = {};
  const line_item_totals: any = {};
  const line_item_tax_totals: any = {};

  $('.refund input.refund_order_item_qty').each(function (index, item) {
    if ($(item).closest('tr').data('order_item_id')) {
      if ($(item).val()) {
        line_item_qtys[$(item).closest('tr').data('order_item_id')] = $(item).val();
      }
    }
  });

  $('.refund input.refund_line_tax').each(function (index, item) {
    if ($(item).closest('tr').data('order_item_id')) {
      var tax_id = $(item).data('tax_id');

      if (!line_item_tax_totals[$(item).closest('tr').data('order_item_id')]) {
        line_item_tax_totals[$(item).closest('tr').data('order_item_id')] = {};
      }

      line_item_tax_totals[$(item).closest('tr').data('order_item_id')][tax_id] = accounting.unformat(
        $(item).val(),
        woocommerce_admin.mon_decimal_point
      );
    }
  });

  const data = {
    action: 'woocommerce_refund_line_items',
    order_id: woocommerce_admin_meta_boxes.post_id,
    refund_amount: refund_amount,
    refunded_amount: refunded_amount,
    refund_reason: refund_reason,
    line_item_qtys: JSON.stringify(line_item_qtys, null, ''),
    line_item_totals: JSON.stringify(line_item_totals, null, ''),
    line_item_tax_totals: JSON.stringify(line_item_tax_totals, null, ''),
    // @ts-ignore
    api_refund: $(this).is('.do-api-refund'),
    restock_refunded_items: $('#restock_refunded_items:checked').length ? 'true' : 'false',
    security: woocommerce_admin_meta_boxes.order_item_nonce,
    acfw_store_credits: true,
  };

  $.ajax({
    url: woocommerce_admin_meta_boxes.ajax_url,
    data: data,
    type: 'POST',
    success: function (response) {
      if (true === response.success) {
        // Redirect to same page for show the refunded status
        window.location.reload();
      } else {
        window.alert(response.data.error);
        $('#woocommerce-order-items').trigger('wc_order_items_reload');
        unblockMetabox();
      }
    },
    complete: function () {
      wcTracks.recordEvent('order_edit_refunded', {
        order_id: data.order_id,
        status: $('#order_status').val(),
        api_refund: data.api_refund,
        has_reason: !!data.refund_reason,
        restock: 'true' === data.restock_refunded_items,
      });
    },
  });
}

/**
 * Move the payment summary rows below the order totals when the page is loaded.
 */
function movePaymentSummaryBelowOrderTotals() {
  // skip if order was not paid with store credits
  if (!$('.wc-order-totals-items .wc-order-totals tr.acfw-payment-row').length) {
    return;
  }

  $('.wc-order-totals-items .wc-order-totals tr.acfw-payment-row').appendTo(
    '.wc-order-totals-items .wc-order-totals:first-child tbody'
  );
  $('.wc-order-totals-items .wc-order-totals tr.acfw-payment-row').removeClass('acfw-payment-row');

  if ($('.wc-order-totals-items table.wc-order-totals').length > 2) {
    $($('.wc-order-totals-items table.wc-order-totals')[1]).remove();
  }
}
