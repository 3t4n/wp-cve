import labels from "../labels";

/**
 * Get the html markup for the "send coupon to" section of the modal.
 *
 * @since 4.5.3
 *
 * @param {boolean} isCurrentSection Flag to determine if the section is the current one being displayed or not.
 * @returns {string} Section html markup.
 */
export default function sendCouponToSectionMarkup(isCurrentSection = false) {
  return `
  <div class="acfw-send-coupon-form-section ${isCurrentSection ? "current" : ""}" data-section="send_coupon_to">
    <div class="section-number">
      <span>1</span>
    </div>
    <div class="section-inner">
      <h3>${labels.send_coupon_to}</h3>
      <div class="section-content">
        <label>
          <input type="radio" name="acfw_send_coupon[to]" value="user" data-key="send_to" checked />
           <span>${labels.existing_customer_account}<span>
        </label>
        <label>
          <input type="radio" name="acfw_send_coupon[to]" value="email" data-key="send_to" />
           <span>${labels.new_customer}<span>
        </label>
        <button type="button" class="button-primary acfw-next-section-btn" data-next_section="customer_details">${
          labels.next
        }</button>
      </div>
    </div>
  </div>
  `;
}
