import cart_conditions_module_events from "./cart_conditions/index";
import bogo_deals_module_events from "./bogo_deals/index";
import toggle_fields_events from "./toggle_fields";
import url_coupon_events from "./url_coupons";
import edit_link_scheduler_fields from "./scheduler";
import upsell_events from "./upsell";
import helpLinkRegisterEvents, { generateHelpLinks } from "./help_modal/index";
import sendCouponEvents from "./send_coupon/index";

import "./assets/styles/index.scss";

declare var jQuery: any;
declare var acfw_edit_coupon: any;

const { modules, upsell } = acfw_edit_coupon;

jQuery(document).ready(($: any) => {
  if (modules.indexOf("acfw_cart_conditions_module") > -1) cart_conditions_module_events();

  if (modules.indexOf("acfw_bogo_deals_module") > -1) bogo_deals_module_events();

  if (modules.indexOf("acfw_url_coupons_module") > -1) url_coupon_events();

  if (modules.indexOf("acfw_scheduler_module") > -1) edit_link_scheduler_fields();

  if (upsell) upsell_events();

  toggle_fields_events();

  generateHelpLinks();
  helpLinkRegisterEvents();
  preSelectCouponTypeFromUrl();
  sendCouponEvents();
});

/**
 * Pre select the coupon type when the parameter is available on the URL during new coupon creation.
 *
 * @since 4.3
 */
function preSelectCouponTypeFromUrl() {
  const queryString = new URLSearchParams(window.location.search);
  const type = queryString.get("type");

  // only do this when creating a new coupon.
  if (type && window.location.pathname.includes("post-new.php")) {
    jQuery("select#discount_type").val(type).trigger("change");
  }
}
