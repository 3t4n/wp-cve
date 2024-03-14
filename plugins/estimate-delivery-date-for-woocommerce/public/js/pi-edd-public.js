(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  jQuery(document).ready(function ($) {

    $(document).on('updated_shipping_method', function (e) {
      jQuery("[name='update_cart']")
        .removeAttr("disabled")
        .trigger("click")
        .attr("disabled");
    });

    /** shipping calculation on cart page */
    /* For WC less then 7.5 */
    $(document).ajaxComplete(function (event, jqxhr, settings) {

      if (typeof settings !== 'undefined' && typeof settings.data !== 'undefined' && typeof settings.data == 'string' && settings.data.includes('woocommerce-shipping-calculator-nonce')) {
        jQuery("[name='update_cart']")
          .removeAttr("disabled")
          .trigger("click")
          .attr("disabled");
      }
    });

    /** we need to move this to other file and load it only on cart page so it don't interfere with other fetch request on other pages */
    /* For WC 7.5 or above */
    var update_cart = false
    window.fetch = new Proxy(window.fetch, {
      apply(fetch, that, args) {
        // Forward function call to the original fetch
        const result = fetch.apply(that, args);

        if (args[1]?.body?.includes('woocommerce-shipping-calculator-nonce')) {
          // Do whatever you want with the resulting Promise
          update_cart = true;

        }

        if (update_cart && args[0]?.includes('wc-ajax=get_refreshed_fragments')) {
          update_cart = false;
          result.then((response) => {

            jQuery("[name='update_cart']")
              .removeAttr("disabled")
              .trigger("click")
              .attr("disabled");
          });
        }

        return result;
      }
    });




  });
})(jQuery);
