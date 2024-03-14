(function ($) {
  "use strict";
  /**
   * This enables you to define handlers, for when the DOM is ready:
   * $(function() { });
   * When the window is loaded:
   * $( window ).load(function() { });
   */
})(jQuery);
class TVC_GTM_Enhanced {
  constructor(options = {}) {
    this.options = {
      tracking_option: "UA",
    };
    if (options) {
      Object.assign(this.options, options);
    } 
    //console.log(this.options);
    //this.addEventBindings();  
  }
  /*
   * check remarketing option
   */
  is_add_remarketing_tags() {
    if (
      this.options.is_admin == false &&
      this.options.ads_tracking_id != "" &&
      (this.options.remarketing_tags == 1 ||
        this.options.dynamic_remarketing_tags == 1)
    ) {
      return true;
    } else {
      return false;
    }
  }
  
}
