import labels from "../labels";
import sendCouponToSectionMarkup from "./sendCouponToSection";
import customerDetailsSectionMarkup from "./customerDetailsSection";
import confirmAndSendSection from "./confirmAndSendSection";

declare var jQuery: any;

const $ = jQuery;

/**
 * Get the html markup of the send coupon modal UI.
 *
 * @since 4.5.3
 *
 * @param {string} currentSection The current section to display.
 * @returns {string} Modal html markup.
 */
export default function modalMarkup(currentSection: string) {
  return `
    <div id="acfw-send-coupon">
      <h2>${labels.title}</h2>
      <div class="description">
        <p>${labels.description}</p>
      </div>

      <div class="acfw-send-coupon-form-sections">
        ${getSectionRenderer("send_coupon_to")("send_coupon_to" === currentSection)}
        ${getSectionRenderer("customer_details")("customer_details" === currentSection)}
        ${getSectionRenderer("confirm_and_send")("confirm_and_send" === currentSection)}
      </div>
    </div>
  `;
}

/**
 * Rerender a given section in the modal.
 *
 * @since 4.5.3
 *
 * @param {string} section The section to rerender.
 * @param {boolean} isCurrent Flag to determine if the section is the current one displayed or not.
 */
export function reRenderSection(section: string, isCurrent = false) {
  const $modal = $(".acfw-send-coupon-modal");

  $modal.find(`[data-section='${section}']`).replaceWith(getSectionRenderer(section)(isCurrent));
}

/**
 * Returns the renderer callback for a given section.
 *
 * @since 4.5.3
 *
 * @param {string} section Section id.
 * @returns {function} Section renderer callback.
 */
function getSectionRenderer(section: string) {
  switch (section) {
    case "send_coupon_to":
      return sendCouponToSectionMarkup;
    case "customer_details":
      return customerDetailsSectionMarkup;
    case "confirm_and_send":
      return confirmAndSendSection;
  }

  return () => {};
}
