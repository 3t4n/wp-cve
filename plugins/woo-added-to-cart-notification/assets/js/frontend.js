'use strict';

(function($) {
  $(function() {
    if (wooac_vars.style === 'notiny') {
      $.notiny.addTheme('wooac', {
        notification_class: 'notiny-theme-wooac',
      });
    }
  });

  $(document.body).
      on('click touch', wooac_vars.add_to_cart_button, function(e) {
        if (wooac_vars.show_adding === 'yes') {
          var $btn = $(this);
          var product_name = $btn.closest(wooac_vars.archive_product).
              find(wooac_vars.archive_product_name).
              eq(0).
              text();
          var product_image = $btn.closest(wooac_vars.archive_product).
              find(wooac_vars.archive_product_image).
              eq(0).
              attr('src');

          if ($btn.hasClass('single_add_to_cart_button') ||
              $btn.is(wooac_vars.single_add_to_cart_button)) {
            product_name = $btn.closest(wooac_vars.single_product).
                find(wooac_vars.single_product_name).
                eq(0).
                text();
            product_image = $btn.closest(wooac_vars.single_product).
                find(wooac_vars.single_product_image).
                eq(0).
                attr('src');
          }

          $('.wooac-product-image').attr('src', product_image);
          $('.wooac-product-name').html(product_name);

          wooac_show_adding();
        }
      });

  $(document.body).on('added_to_cart', function(e) {
    if (wooac_vars.style === 'notiny') {
      $('.notiny-theme-wooac').remove();
    } else {
      wooac_hide();
    }

    if (wooac_vars.show_ajax === 'yes') {
      setTimeout(function() {
        wooac_show();
      }, parseInt(wooac_vars.delay));
    }
  });

  $(document.body).on('wc_fragments_refreshed', function() {
    wooac_suggested_slick();

    if ((wooac_vars.added_to_cart === 'yes') &&
        (wooac_vars.show_normal === 'yes')) {
      setTimeout(function() {
        wooac_show();
      }, parseInt(wooac_vars.delay));
    }
  });

  $(document).on('click touch', '#wooac-continue', function(e) {
    e.preventDefault();

    wooac_hide();

    var url = $(this).attr('data-url');

    if (url !== '') {
      window.location.href = url;
    }
  });

  $(document).on('click touch', '.wooac-popup .woosq-btn', function(e) {
    e.preventDefault();

    wooac_hide();
  });
})(jQuery);

function wooac_show() {
  if (jQuery.trim(jQuery('.wooac-wrapper').html()).length) {
    jQuery('body').removeClass('wooac-show-adding');
    jQuery('body').addClass('wooac-show');

    if (wooac_vars.style === 'notiny') {
      let notiny_image = jQuery('.wooac-notiny-added img').attr('src');
      let notiny_text = jQuery('.wooac-notiny-added').text();

      jQuery.notiny({
        theme: 'wooac',
        position: wooac_vars.notiny_position,
        image: notiny_image,
        text: notiny_text,
      });
    } else {
      wooac_suggested_unslick();

      jQuery.magnificPopup.open({
        items: {
          src: jQuery('.wooac-popup-added'), type: 'inline',
        }, mainClass: 'mfp-wooac', callbacks: {
          beforeOpen: function() {
            this.st.mainClass = 'mfp-wooac ' + wooac_vars.effect;
          }, open: function() {
            if (parseInt(wooac_vars.close) > 0) {
              setTimeout(function() {
                jQuery('.wooac-popup-added').
                    magnificPopup('close');
              }, parseInt(wooac_vars.close));
            }

            wooac_suggested_slick();
          }, afterClose: function() {
            jQuery('body').removeClass('wooac-show');
          },
        },
      });
    }

    jQuery(document.body).trigger('wooac_show');
  }
}

function wooac_hide() {
  jQuery('body').removeClass('wooac-show-adding');
  jQuery('body').removeClass('wooac-show');
  jQuery.magnificPopup.close();
  jQuery(document.body).trigger('wooac_hide');
}

function wooac_show_adding() {
  jQuery('body').addClass('wooac-show-adding');

  if (wooac_vars.style === 'notiny') {
    let notiny_image = jQuery('.wooac-notiny-adding img').attr('src');
    let notiny_text = jQuery('.wooac-notiny-adding').text();

    jQuery.notiny({
      theme: 'wooac',
      position: wooac_vars.notiny_position,
      image: notiny_image,
      text: notiny_text,
      autohide: false,
    });
  } else {
    jQuery.magnificPopup.open({
      items: {
        src: '.wooac-popup-adding', type: 'inline',
      }, mainClass: 'mfp-wooac', callbacks: {
        beforeOpen: function() {
          this.st.mainClass = 'mfp-wooac ' + wooac_vars.effect;
        },
      },
    });
  }

  jQuery(document.body).trigger('wooac_show_adding');
}

function wooac_suggested_unslick() {
  if (wooac_vars.carousel &&
      (jQuery('.wooac-popup .wooac-suggested-product').length > 1) &&
      jQuery('.wooac-popup .wooac-suggested-products').
          hasClass('slick-initialized')) {
    jQuery('.wooac-popup .wooac-suggested-products').slick('unslick');
  }
}

function wooac_suggested_slick() {
  if (wooac_vars.carousel &&
      (jQuery('.wooac-popup .wooac-suggested-product').length > 1)) {
    jQuery('.wooac-popup .wooac-suggested-products').
        slick(JSON.parse(wooac_vars.slick_params));
  }
}