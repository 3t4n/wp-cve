import labels from "../labels";

/**
 * Get the html markup for the send coupon button.
 *
 * @since 4.5.3
 *
 * @returns {string} Send coupon button markup.
 */
export default function sendCouponButtonMarkup() {
  return `<button type="button" class="button acfw-send-coupon-btn">${labels.title}</button>`;
}
