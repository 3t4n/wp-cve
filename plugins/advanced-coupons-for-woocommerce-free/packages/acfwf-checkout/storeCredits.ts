import { blockCheckout, unblockCheckout } from './checkoutUtils';

declare var acfwfCheckout: any;
declare var wc_checkout_params: any;

const $ = jQuery;

export default function storeCreditEvents() {
  $(document.body).on('click', '.acfw-redeem-store-credit-form-field .button', applyStoreCredits);
}

export function applyStoreCredits() {
  // @ts-ignore
  const $button = $(this);
  const $form = $button.closest('.acfw-redeem-store-credit-form-field');
  const $input = $form.find('input.input-text');
  const amount = parsePrice($input.val() as string);

  $form.find('p.invalid-message').remove();
  $('.woocommerce-NoticeGroup-updateOrderReview').remove();

  if (!validatePrice(amount)) {
    $form.append(`<p class='invalid-message'>${acfwfCheckout.enter_valid_price}</p>`);
    return;
  }

  blockCheckout();

  $.post(
    wc_checkout_params.ajax_url,
    {
      action: 'acfwf_redeem_store_credits',
      amount: amount,
      wpnonce: acfwfCheckout.redeem_nonce,
    },
    function (response) {
      unblockCheckout();
      $input.val('');
      $(document.body).trigger('update_checkout', {
        update_shipping_method: false,
      });
    }
  );
}

function parsePrice(value: string) {
  return value
    ? parseFloat(value.replace(acfwfCheckout.thousand_separator, '').replace(acfwfCheckout.decimal_separator, '.'))
    : 0;
}

function validatePrice(value: number | string) {
  value = value.toString();
  const regex = new RegExp(`[^-0-9%\\.]+`, 'gi');
  const decimalRegex = new RegExp(`[^\\.}"]`, 'gi');
  let newvalue = value.replace(regex, '');

  // Check if newvalue have more than one decimal point.
  if (1 < newvalue.replace(decimalRegex, '').length) {
    newvalue = newvalue.replace(decimalRegex, '');
  }

  return parseFloat(value) === parseFloat(newvalue);
}
