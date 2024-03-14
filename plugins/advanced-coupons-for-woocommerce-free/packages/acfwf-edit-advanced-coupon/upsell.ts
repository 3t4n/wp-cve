declare var jQuery: any;
declare var acfw_edit_coupon: any;
declare var vex: any;

const $: any = jQuery;
let exludeCouponShown = false;

/**
 * Add upsell events script.
 *
 * @since 1.0.0
 */
export default function upsell_events() {
  $('#usage_limit_coupon_data').on('change', '#reset_usage_limit_period', upsell_advance_usage_limits);
  $(
    '#usage_restriction_coupon_data .acfw_exclude_coupons_field,#usage_restriction_coupon_data .acfw_allowed_customers_field'
  ).on('click change focus', 'input,select', upsell_exclude_coupons_restriction);
  $('#acfw-auto-apply-coupon').on('change', '#acfw_auto_apply_coupon_field', upsell_auto_apply);
  $('#acfw-virtual-coupon').on('change', '#acfw_enable_virtual_coupons', upsell_virtual_coupons);
  $('#acfw_cart_conditions').on('change', '.condition-types', cart_condition_select_notice);
  $('#woocommerce-coupon-data').on('change acfw_load', '#discount_type', hideGeneralUpsellOnBogo);
  $('#woocommerce-coupon-data').on('change acfw_load', '#discount_type', displayCashbackUpsellModal);
  $('#woocommerce-coupon-data').on('focusin', '#discount_type', cacheCurrentDiscountType);

  $('#acfw_bogo_deals').on('change acfw_load', 'select#bogo-deals-type', toggle_bogo_auto_add_products_field);

  $('#acfw_bogo_deals').on('change', "input[name='acfw_bogo_auto_add_products']", upsell_bogo_auto_add_get_products);

  $('#acfw_scheduler').on(
    'change',
    ".acfw-daytime-schedules-section .acfw-section-toggle input[type='checkbox']",
    upsell_day_time_schedules
  );

  $('#woocommerce-coupon-data #discount_type').trigger('acfw_load');

  initExcludeCouponField();
}

function initExcludeCouponField() {
  const $excludeField = $('p.acfw_exclude_coupons_field');

  $excludeField.insertAfter('p.form-field.individual_use_field');
}

/**
 * Usage limits upsell vex dialog.
 *
 * @since 1.1
 */
function upsell_advance_usage_limits() {
  const { usage_limits } = acfw_edit_coupon.upsell;

  vex.dialog.alert({
    unsafeMessage: `<div class="upsell-alert usage-limits">${usage_limits}</div>`,
  });
  // @ts-ignore
  $(this).val('none');
}

/**
 * Usage restriction for exclude coupons upsell vex dialog.
 *
 * @since 1.1
 */
function upsell_exclude_coupons_restriction() {
  // prevent duplicate dialogs showing up.
  if (exludeCouponShown) return;
  exludeCouponShown = true;

  const { usage_restriction } = acfw_edit_coupon.upsell;

  vex.dialog.alert({
    unsafeMessage: `<div class="upsell-alert exclude-coupon">${usage_restriction}</div>`,
    afterClose: () => (exludeCouponShown = false),
  });
  // @ts-ignore
  $(this).val('').blur();
}

/**
 * Auto apply upsell vex dialog.
 *
 * @since 1.1
 */
function upsell_auto_apply() {
  const { auto_apply } = acfw_edit_coupon.upsell;

  vex.dialog.alert({
    unsafeMessage: `<div class="upsell-alert auto-apply">${auto_apply}</div>`,
  });
  // @ts-ignore
  $(this).prop('checked', false);
}

/**
 * Virtual coupons upsell vex dialog.
 *
 * @since 4.3
 */
function upsell_virtual_coupons() {
  const { virtual_coupons } = acfw_edit_coupon.upsell;

  vex.dialog.alert({
    unsafeMessage: `<div class="upsell-alert auto-apply">${virtual_coupons}</div>`,
  });
  // @ts-ignore
  $(this).prop('checked', false);
}

/**
 * Display did you know notice below cart condition selector when premium option is selected.
 *
 * @since 1.6
 */
function cart_condition_select_notice() {
  // @ts-ignore
  const $select = $(this);
  const $moduleBlock = $select.closest('#acfw_cart_conditions');
  const $formBlock = $select.closest('.add-condition-form');
  const premiumConditions: string[] = $moduleBlock.data('premium-conditions');
  const $noticeHolder = $moduleBlock.find('.acfw-dyk-notice-holder');

  let $noticeBlock = $formBlock.find('.acfw-dyk-notice');

  if (!$noticeBlock.length) {
    $formBlock.append($noticeHolder.html());
    $noticeBlock = $formBlock.find('.acfw-dyk-notice');
  }

  if ($.inArray($select.val(), premiumConditions) >= 0) {
    $noticeBlock.show();
  } else {
    $noticeBlock.hide();
  }
}

/**
 * Hide did you know notice upsell under general tab when BOGO discount type is selected.
 *
 * @since 3.0
 */
function hideGeneralUpsellOnBogo() {
  // @ts-ignore
  const type = $(this).val();

  if ('acfw_bogo' === type) {
    $('p.acfw-dyk-notice-general').hide();
  } else {
    $('p.acfw-dyk-notice-general').show();
  }
}

/**
 * Cache current discount type.
 *
 * @since 4.5.6
 */
function cacheCurrentDiscountType() {
  // @ts-ignore
  $(this).data('cachedval', $(this).val());
}

/**
 * Display cashback upsell modal.
 *
 * @since 4.5.6
 */
function displayCashbackUpsellModal() {
  // @ts-ignore
  const type = $(this).val();
  const { cashback_coupon } = acfw_edit_coupon.upsell;

  if ('acfw_percentage_cashback' === type || 'acfw_fixed_cashback' === type) {
    vex.dialog.alert({
      unsafeMessage: `<div class="upsell-alert usage-limits">${cashback_coupon}</div>`,
    });
    // @ts-ignore
    $(this).val($(this).data('cachedval') ?? 'percent');
  }
}

/**
 * Toggle BOGO auto add products field.
 *
 * @since 4.1
 */
function toggle_bogo_auto_add_products_field() {
  // @ts-ignore
  const $this = $(this);
  const $module = $this.closest('#acfw_bogo_deals');
  const $field = $module.find('.bogo-auto-add-products-field');
  const $input = $field.find("input[type='checkbox']");
  const applyType = $this.val();

  if (applyType === 'specific-products') {
    $input.prop('disabled', false);
    $field.addClass('show');
  } else {
    $input.prop('disabled', false);
    $field.removeClass('show');
  }
}

/**
 * Upsell BOGO auto add get products feature.
 *
 * @since 4.1
 */
function upsell_bogo_auto_add_get_products() {
  // @ts-ignore
  const $this = $(this);

  $this.prop('checked', false);

  const { bogo_auto_add_get_products } = acfw_edit_coupon.upsell;

  vex.dialog.alert({
    unsafeMessage: `<div class="upsell-alert usage-limits">${bogo_auto_add_get_products}</div>`,
  });
  // @ts-ignore
  $(this).val('none');
}

/**
 * Display upsell for day time schedules when section checkbox is checked.
 *
 * @since 4.5
 */
function upsell_day_time_schedules() {
  // @ts-ignore
  const $checkbox = $(this);

  const { day_time_schedules } = acfw_edit_coupon.upsell;

  vex.dialog.alert({
    unsafeMessage: `<div class="upsell-alert day-time-schedules">${day_time_schedules}</div>`,
  });

  $checkbox.prop('checked', false).trigger('acfw_load');
}
