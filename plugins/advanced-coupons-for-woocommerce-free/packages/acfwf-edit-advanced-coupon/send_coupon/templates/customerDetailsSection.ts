import labels from "../labels";

/**
 * Get the html markup for the customer details section of the modal.
 *
 * @since 4.5.3
 *
 * @param {boolean} isCurrentSection Flag to determine if the section is the current one being displayed or not.
 * @returns {string} Section html markup.
 */
export default function customerDetailsSectionMarkup(isCurrentSection = false) {
  return `
  <div class="acfw-send-coupon-form-section ${isCurrentSection ? "current" : ""}" data-section="customer_details">
    <div class="section-number">
      <span>2</span>
    </div>
    <div class="section-inner">
      <h3>${labels.customer_details}</h3>
      <div class="section-content">
        <div class="customer-details-form user-form show">
          <select data-placeholder="${
            labels.search
          }" name="acfw_send_coupon[user]" data-key="user" class="wc-customer-search" style="width:100%"></select>
        </div>
        <div class="customer-details-form guest-form">
          <input type="text" placeholder="${labels.name}" name="acfw_send_coupon[name]" data-key="name" />
          <input type="email" placeholder="${labels.email}" name="acfw_send_coupon[email]" data-key="email" />
          <label>
            <input type="checkbox" data-key="create_account" value="yes" /> 
            <span>${labels.create_new_user_account}</span>
        </div>
        <button type="button" class="button-primary acfw-next-section-btn" data-next_section="confirm_and_send">${
          labels.next
        }</button>
      </div>
    </div>
  </div>
  `;
}
