var kickflipActivateRedirect = false;
function receivedAddToCartResponse(event) {
  if (!(event.data.eventName === "mczrAddToCart"
    && MCZR_BASE_HOST === event.origin)
  ) {
    return;
  }

  doKickflipAddToCart(event, 0);
};

function doKickflipAddToCart(event, retry = 0) {
  if (retry > 2) return;
  var productId = jQuery("#mczrMainIframe").data("product-id");
  var kickflipEventData = event.data.detail;

  if (!kickflipEventData
    || !kickflipEventData.store
    || !kickflipEventData.id
    || !kickflipEventData.designImage
    || !kickflipEventData.designImage.url
    || !kickflipEventData.designId) {
    console.warn("Kickflip Plugin error: missing required data.");
  }
  var props = {
    shopProductId: productId,
    shopDomain: MCZR_SHOP_DOMAIN,
    brand: MCZR_BRAND_NAME,
    mczrStoreId: kickflipEventData.store,
    designId: kickflipEventData.id,
    image: kickflipEventData.designImage.url,
    title: "Customization #" + kickflipEventData.designId,
    customizationNumber: kickflipEventData.designId,
    summary_v2: kickflipEventData.summary ? kickflipEventData.summary : [],
  };
  try {
    jQuery(document.body).trigger("adding_to_cart", [null, {}]);
  } catch (error) {
  }
  jQuery.ajax(
    {
      url: ajaxFrontObj.ajax_url,
      method: "POST",
      crossDomain: true,
      dataType: "json",
      data: {
        action: "addToCart",
        productId: productId,
        props: props,
      },
      success: (json) => {
        if (!json.success) {
          doKickflipAddToCart(event, retry + 1);
          return;
        }
        kickflipActivateRedirect = json.data.redirect;
        if (json.data.fragments) {
          jQuery.each(json.data.fragments, function (key, value) {
            jQuery(key).replaceWith(value);
          });
          if (kickflipEventData && kickflipEventData.doNotTriggerAddToCart) {
            return;
          }
          try {
            jQuery(document.body).trigger('added_to_cart', [json.data.fragments, json.data.cart_hash]);
          } catch (error) {
          }
        }
      },
    }
  );
};

jQuery(document).ready(
  function () {
    window.addEventListener("message", receivedAddToCartResponse, false);
    jQuery(document.body).on("added_to_cart", function () {
      if (kickflipActivateRedirect) {
        window.setTimeout(function () { window.location = kickflipActivateRedirect; }, 1000);
      }
    });
  }
);