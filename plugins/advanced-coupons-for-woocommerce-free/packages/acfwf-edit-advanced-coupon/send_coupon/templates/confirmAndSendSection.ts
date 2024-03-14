import labels from "../labels";
import formState from "../state";

/**
 * Get the html markup for the confirm and send section of the modal.
 *
 * @since 4.5.3
 *
 * @param {boolean} isCurrentSection Flag to determine if the section is the current one being displayed or not.
 * @returns {string} Section html markup.
 */
export default function confirmAndSendSection(isCurrentSection = false) {
  const isDisabled = !formState.get("name") || !formState.get("email") ? "disabled" : "";

  return `
  <div class="acfw-send-coupon-form-section ${isCurrentSection ? "current" : ""}" data-section="confirm_and_send">
    <div class="section-number">
      <span>3</span>
    </div>
    <div class="section-inner">
      <h3>${labels.confirm_and_send}</h3>
      <div class="section-content">
        <p>
          <strong>${labels.customer}:</strong> ${formState.get("name") ?? ""}
          ${formState.get("email") ? `&lt;${formState.get("email")}>` : ""}
        </p>
        <p><a class="preview-email-link  ${isDisabled}" href="#">${labels.preview_email} ${externalIconSvg()}</a></p>
        <button type="button" class="button-primary acfw-send-email-btn" ${isDisabled}>${
    labels.send_email
  } ${envelopeIconSvg()}</button>
        <p class="request-message"></p>
      </div>
    </div>
  </div>
  `;
}

/**
 * External icon SVG markup.
 *
 * @returns {string} Icon markup.
 */
function externalIconSvg() {
  return `
  <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8.90625 8.59363V1.40613C8.90625 1.23328 8.7666 1.09363 8.59375 1.09363L1.40625 1.09363C1.2334 1.09363 1.09375 1.23328 1.09375 1.40613V4.92175C1.09375 4.96472 1.12891 4.99988 1.17188 4.99988H1.71875C1.76172 4.99988 1.79688 4.96472 1.79688 4.92175V1.79675L8.20313 1.79675V8.203H5.07813C5.03516 8.203 5 8.23816 5 8.28113V8.828C5 8.87097 5.03516 8.90613 5.07813 8.90613H8.59375C8.7666 8.90613 8.90625 8.76648 8.90625 8.59363ZM4.14648 6.37976L4.65625 6.88953C4.66663 6.89985 4.6797 6.90706 4.69397 6.91033C4.70824 6.91359 4.72314 6.91279 4.73697 6.90801C4.75081 6.90323 4.76303 6.89466 4.77224 6.88328C4.78145 6.8719 4.78728 6.85816 4.78906 6.84363L4.99414 5.09167C5 5.04187 4.95801 4.9989 4.90723 5.00476L3.15527 5.20984C3.09082 5.21765 3.06348 5.29675 3.10938 5.34265L3.62109 5.85437L1.11914 8.35632C1.08887 8.3866 1.08887 8.4364 1.11914 8.46667L1.5332 8.88074C1.56348 8.91101 1.61328 8.91101 1.64356 8.88074L4.14648 6.37976Z" fill="#3A6FAC"/>
  </svg>
  `;
}

/**
 * Envelope icon SVG markup.
 *
 * @returns {string} Icon markup.
 */
function envelopeIconSvg() {
  return `
  <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M10.875 1.87512H1.125C0.917578 1.87512 0.75 2.0427 0.75 2.25012V9.75012C0.75 9.95754 0.917578 10.1251 1.125 10.1251H10.875C11.0824 10.1251 11.25 9.95754 11.25 9.75012V2.25012C11.25 2.0427 11.0824 1.87512 10.875 1.87512ZM10.4062 3.17356V9.28137H1.59375V3.17356L1.27031 2.92161L1.73086 2.32981L2.23242 2.72004H9.76875L10.2703 2.32981L10.7309 2.92161L10.4062 3.17356ZM9.76875 2.71887L6 5.64856L2.23125 2.71887L1.72969 2.32864L1.26914 2.92043L1.59258 3.17239L5.5957 6.28489C5.71083 6.37433 5.85246 6.42288 5.99824 6.42288C6.14403 6.42288 6.28566 6.37433 6.40078 6.28489L10.4062 3.17356L10.7297 2.92161L10.2691 2.32981L9.76875 2.71887Z" fill="white"/>
  </svg>
  `;
}
