'use strict';

(function($) {
  $(document).on('click touch', '.wpcsc-btn', function(e) {
    e.preventDefault();

    var $this = $(this);
    var pid = $this.attr('data-pid');
    var cid = $this.attr('data-cid');

    if (pid != undefined) {
      // get combined charts

      var data = {
        action: 'wpcsc_get_charts',
        id: pid,
        nonce: wpcsc_vars.nonce,
      };
    } else if (cid != undefined) {
      // get singular chart

      var data = {
        action: 'wpcsc_get_chart',
        id: cid,
        nonce: wpcsc_vars.nonce,
      };
    } else {
      return false;
    }

    $('.wpcsc-popup').html('');

    $this.addClass('wpcsc-size-charts-list-item-loading');

    $.post(wpcsc_vars.ajax_url, data, function(response) {
      $('.wpcsc-popup').html(response);
      $this.removeClass('wpcsc-size-charts-list-item-loading');

      // popup
      if (wpcsc_vars.library === 'magnific') {
        $.magnificPopup.open({
          items: {
            src: $('.wpcsc-popup'), type: 'inline',
          }, mainClass: 'mfp-wpcsc', callbacks: {
            beforeOpen: function() {
              this.st.mainClass = 'mfp-wpcsc ' + wpcsc_vars.effect;
            }, open: function() {
              $('body').addClass('wpcsc-magnific');
            }, afterClose: function() {
              $('body').removeClass('wpcsc-magnific');
            },
          },
        });
      } else {
        $('.wpcsc-popup-btn').trigger('click');
      }
    });
  });
})(jQuery);