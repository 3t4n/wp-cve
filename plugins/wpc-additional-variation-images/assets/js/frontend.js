(function($) {
  'use strict';

  var wpcvi_clicked = 0;
  var wpcvi_timeout = null;

  $(document).on('click touch mouseover', '.variations_form', function() {
    wpcvi_clicked = 1;
  });

  const wpcvi = () => {
    const endpoint = wpcvi_vars.ajax_url.toString().
        replace('%%endpoint%%', 'wpcvi_get_images');
    const gallery_status = {};

    const jq_func_exists = function(name) {
      return typeof $.fn[name] === 'function';
    };

    const jq_trigger = function(name, params = []) {
      $('.variations_form').trigger(name, params);
    };

    const get_images = function(variation_id) {
      return $.ajax({
        type: 'POST', data: {
          security: wpcvi_vars.nonce, variation_id: variation_id,
        }, url: endpoint,
      });
    };

    const init_lightbox = function() {
      if (jq_func_exists('prettyPhoto')) {
        $(wpcvi_vars.lightbox_class).prettyPhoto({
          hook: 'data-rel',
          social_tools: false,
          theme: 'pp_woocommerce',
          horizontal_padding: 20,
          opacity: 0.8,
          deeplinking: false,
        });
      }

      jq_trigger('wpcvi_lightbox');
    };

    const block_ui_params = {
      message: null, overlayCSS: {
        background: '#fff', opacity: 0.6,
      },
    };

    const load_gallery = function(variation_id = 0, callback) {
      const loading_gallery = $.Deferred(callback);

      if (gallery_status[variation_id] &&
          gallery_status[variation_id].promise.state() === 'resolved') {
        return loading_gallery.resolve(variation_id,
            gallery_status[variation_id].result);
      }

      if (!gallery_status[variation_id] ||
          gallery_status[variation_id].promise.state() === 'rejected') {
        const promise = get_images(variation_id);

        gallery_status[variation_id] = {
          promise: promise, result: '',
        };
      }

      $.when(gallery_status[variation_id].promise).then(function(response) {
        gallery_status[variation_id].result = response.images || '';
        loading_gallery.resolve(variation_id,
            gallery_status[variation_id].result);
      }, function() {
        loading_gallery.reject();
      });

      jq_trigger('wpcvi_load_gallery');

      return loading_gallery.promise();
    };

    const show_gallery = function(variation_id = 0, variation_form) {
      const $container = $(variation_form).closest('.product');

      if ($container.length === 0) {
        return;
      }

      const $all_galleries = $container.find(wpcvi_vars.images_class);
      const $ori_gallery = $container.find(wpcvi_vars.images_class +
          ':not(.woocommerce-product-gallery--wpcvi)');

      const show_selected_gallery = function($gallery_to_show) {
        const $visible = $container.find(wpcvi_vars.images_class + ':visible');

        if ($gallery_to_show.is(':hidden') || $visible.length > 1) {
          $visible.hide();
          $gallery_to_show.show();
        }

        jq_trigger('wpcvi_show_selected_gallery');
      };

      const get_gallery = function(variation_id) {
        return $container.find(
            '.woocommerce-product-gallery--variation-' + variation_id);
      };

      const gallery_exists = function(variation_id) {
        return get_gallery(variation_id).length > 0;
      };

      const init_gallery = function(gallery_variation_id) {
        const gallery = get_gallery(gallery_variation_id);

        if (gallery.length) {
          gallery.wc_product_gallery();
          init_lightbox();
        }

        jq_trigger('init_gallery');
      };

      const create_gallery = function(gallery_variation_id, gallery_html) {
        $all_galleries.first().after(gallery_html);
        init_gallery(gallery_variation_id);
      };

      $container.data('current_variation_id', variation_id);

      if (variation_id === 0) {
        return show_selected_gallery($ori_gallery);
      }

      load_gallery(variation_id, function() {
        $all_galleries.block(block_ui_params);
      }).then(function(gallery_variation_id, gallery_html) {
        if (!gallery_exists(gallery_variation_id)) {
          create_gallery(gallery_variation_id, gallery_html);
        }

        if (gallery_variation_id === $container.data('current_variation_id')) {
          show_selected_gallery(get_gallery(gallery_variation_id));
        }
      }).always(function() {
        $all_galleries.unblock();
      });

      jq_trigger('wpcvi_show_gallery');
    };

    $('.variations_form').on('reset_data', function(event, variation) {
      show_gallery(0, event.target);
      jq_trigger('wpcvi_reset_data');
    }).on('show_variation', function(event, variation) {
      show_gallery(parseInt(variation.variation_id, 10), event.target);
      jq_trigger('wpcvi_show_variation');
    }).on('found_variation', function(event, variation) {
      // for grouped, bundles, frequently bought together, force sells
      if ($(event.target).closest('.woosg-wrap').length ||
          $(event.target).closest('.woosb-wrap').length ||
          $(event.target).closest('.woobt-wrap').length ||
          $(event.target).closest('.woofs-wrap').length) {
        if (wpcvi_clicked) {
          show_gallery(parseInt(variation.variation_id, 10), event.target);
        } else {
          // on loaded
          clearTimeout(wpcvi_timeout);
          wpcvi_timeout = setTimeout(function() {
            show_gallery(parseInt(variation.variation_id, 10), event.target);
          }, 300);
        }
      }

      jq_trigger('wpcvi_found_variation');
    });

    jq_trigger('wpcvi_init');
  };

  if (document.readyState === 'complete' ||
      (document.readyState !== 'loading' &&
          !document.documentElement.doScroll)) {
    wpcvi();
  } else {
    document.addEventListener('DOMContentLoaded', wpcvi);
  }
})(jQuery);
