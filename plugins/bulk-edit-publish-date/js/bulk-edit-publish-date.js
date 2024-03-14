(function ($) {

  var BEPD = {

    // Current date.
    now: new Date(),

    // Reference to bulk actions select element.
    bulk_action_selector: null,

    /**
     * Bind change event to the bulk actions select
     */
    init: function () {
      $("#bulk-action-selector-top, #bulk-action-selector-bottom").on('change', BEPD.bulk_action_change);
    },

    /**
     * Callback for when the bulk action select changes.
     */
    bulk_action_change: function () {
      BEPD.bulk_action_selector = $(this);

      if (BEPD.bulk_action_selector.val() === 'set_publish_date') {

        // Value is set_publish_date, create date time form elements
        BEPD.create_date_time_elements();
      } else {

        // Value is something else so remove the date time inputs.
        BEPD.remove_date_time_elements();
      }
    },

    /**
     * Create data and time form elements to allow setting the publish date.
     */
    create_date_time_elements: function () {
      var time_options = {
        type: 'time',
        name: 'publish_time',
        step: 60,
        value: BEPD.the_time(),
        required: 'required'
      };

      var date_options = {
        type: 'date',
        name: 'publish_date',
        value: BEPD.the_date(),
        required: 'required'
      };

      BEPD.bulk_action_selector
        .after($('<input>', time_options).addClass('publish_time'))
        .after($('<input>', date_options).addClass('publish_date'));
    },

    /**
     * Remove the date and time form elements.
     */
    remove_date_time_elements: function () {
      $('.publish_date, .publish_time').remove();
    },

    /**
     * Return date in yyyy-mm-dd format
     * @returns {string}
     */
    the_date: function () {
      var y = this.now.getFullYear();
      var m = this.zero_pad(this.now.getMonth() + 1);
      var d = this.zero_pad(this.now.getDate());
      return y + '-' + m + '-' + d;
    },

    /**
     * Return the time in hh-mm format.
     * @returns {string}
     */
    the_time: function () {
      var h = this.zero_pad(this.now.getHours());
      var m = this.zero_pad(this.now.getMinutes());
      return h + ':' + m;
    },

    /**
     * Zero pad to length of 2.
     * @param n
     * @returns {string}
     */
    zero_pad: function (n) {
      return (n < 10 ? '0' : '') + n;
    }
  };

  $(document).ready(function () {
    BEPD.init();
  });

})(jQuery);
