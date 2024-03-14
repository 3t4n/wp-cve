import sendCouponButtonMarkup from './templates/sendCouponButton';
import modalMarkup from './templates/modalMarkup';
import formState, { initFormState, updateStateFromInput } from './state';

declare var jQuery: any;
declare var acfw_edit_coupon: any;
declare var vex: any;
declare var wp: any;
declare var ajaxurl: string;

const $ = jQuery;

/**
 * Register events related to the send coupon feature.
 *
 * @since 4.5.3
 */
export default function sendCouponEvents() {
  appendSendCouponButton();
  initFormState();

  $('body').on('click', 'button.acfw-send-coupon-btn', showSendCouponModal);
  $('body').on(
    'change clearForm',
    ".acfw-send-coupon-form-section input[type='radio']:checked",
    toggleCustomerDetailsForm
  );
  $('body').on('click', '.acfw-send-coupon-form-section .acfw-next-section-btn', toggleFormNextSection);
  $('body').on(
    'click',
    '.acfw-send-coupon-form-section .section-number,.acfw-send-coupon-form-section h3',
    navigateToSection
  );
  $('body').on(
    'change',
    '.acfw-send-coupon-form-section input, .acfw-send-coupon-form-section select',
    updateStateFromInput
  );
  $('body').on('click', '.acfw-send-coupon-form-section .preview-email-link', previewCouponEmail);
  $('body').on('click', '.acfw-send-coupon-form-section .acfw-send-email-btn', sendCouponEmail);
}

/**
 * Append the send coupon button next to the "Add coupon" button.
 *
 * @since 4.5.3
 */
function appendSendCouponButton() {
  $('.page-title-action').after(sendCouponButtonMarkup());
}

/**
 * Show the send coupon modal UI.
 *
 * @since 4.5.3
 */
function showSendCouponModal() {
  // display vex dialog.
  vex.dialog.open({
    unsafeMessage: modalMarkup(formState.get('section')),
    className: 'vex-theme-plain acfw-send-coupon-modal',
    showCloseButton: true,
    buttons: {},
    afterOpen: () => $(document.body).trigger('wc-enhanced-select-init'),
  });
}

/**
 * Toggle the customer details form depending on the "Send to" option value selected.
 *
 * @since 4.5.3
 */
function toggleCustomerDetailsForm() {
  // @ts-ignore
  const $radio = $(this);
  const sendTo = $radio.val().toString();
  const formClassName = 'user' === sendTo ? '.user-form' : '.guest-form';

  $('.acfw-send-coupon-form-section .customer-details-form')
    .removeClass('show')
    .find('input,select')
    .prop('disabled', true);
  $(`.acfw-send-coupon-form-section ${formClassName}`).addClass('show').find('input,select').prop('disabled', false);
}

/**
 * Navigate to the next section when the "Next" button is clicked.
 *
 * @since 4.5.3
 */
function toggleFormNextSection() {
  // @ts-ignore
  const $button = $(this);
  const sectionName = $button.data('next_section');

  $('.acfw-send-coupon-form-section').removeClass('current');
  $(`.acfw-send-coupon-form-section[data-section='${sectionName}']`).addClass('current');
}

/**
 * Navigate to a section when the section number or header text is clicked.
 *
 * @since 4.5.3
 */
function navigateToSection() {
  // @ts-ignore
  const $section = $(this).closest('.acfw-send-coupon-form-section');

  if (!$section.hasClass('current')) {
    $('.acfw-send-coupon-form-section').removeClass('current');
    $section.addClass('current');
  }
}

/**
 * Preview the coupon email content in a new tab.
 *
 * @since 4.5.3
 */
function previewCouponEmail() {
  // @ts-ignore
  const $button = $(this);

  if ($button.hasClass('disabled')) {
    return;
  }

  const query = new URLSearchParams();

  query.append('action', 'acfw_advanced_coupon_preview_email');
  query.append('email_id', 'acfw_coupon_email');
  query.append('args[coupon_id]', $('#post_ID').val());
  query.append('args[name]', formState.get('name'));
  query.append('args[email]', formState.get('email'));
  query.append('args[user_id]', formState.get('user_id'));
  query.append('_wpnonce', acfw_edit_coupon.send_coupon.form_nonce);

  window.open(`${ajaxurl}?${query.toString()}`, '_blank');
}

/**
 * Send the coupon email API request.
 *
 * @since 4.5.3
 */
function sendCouponEmail() {
  // @ts-ignore
  const $button = $(this);

  $button.prop('disabled', true);
  $('#acfw-send-coupon .request-message').html('').removeClass('success error');

  wp.apiFetch({
    path: '/coupons/v1/sendcoupon',
    method: 'POST',
    data: {
      coupon_id: $('#post_ID').val(),
      customer: parseInt(formState.get('user_id')) ? formState.get('user_id') : formState.get('email'),
      name: formState.get('name'),
      create_account: formState.get('create_account'),
    },
  })
    .then((res: any) => {
      clearForm();
      displayResponseMessage('success', res.message);
    })
    .catch((error: any) => displayResponseMessage('error', error.message));
}

/**
 * Clear all form fields.
 *
 * @since 4.5.3
 */
function clearForm() {
  initFormState('confirm_and_send');
  $('#acfw-send-coupon').find("select,input[type='text'],input[type='email']").val('').trigger('change');
  $('#acfw-send-coupon').find("input[type='radio']").prop('checked', false);
  $('#acfw-send-coupon').find("label:first-child input[type='radio']").prop('checked', true).trigger('clearForm');
  $('#acfw-send-coupon').find("input[type='checkbox']").prop('checked', false);
}

/**
 * Display form response message notice.
 *
 * @since 4.5.3
 *
 * @param {string} type Notice type.
 * @param {string} message Notice message.
 */
function displayResponseMessage(type: string, message: string) {
  $('#acfw-send-coupon .request-message').html(message).addClass(type);
}
