(function($) {
  'use strict';

  let wpcvi_media = {
    frame: null,
    button: null,
    upload_id: null,
    post_id: wp.media.model.settings.post.id,
  };

  $(document).ready(function() {
    function init() {
      $('.wpcvi-images-form:not(.wpcvi-images-form-before-options)').
          each(function() {
            var $options = $(this).parent().find('.form-row.options');

            $(this).
                addClass('wpcvi-images-form-before-options').
                insertBefore($options);
          });

      init_media();
      init_sortable();
      init_remove();
    }

    function init_media() {
      $('a.wpcvi-add-images').on('click touch', function(e) {
        e.preventDefault();

        var $button = $(this);
        var upload_id = parseInt($button.attr('rel'));

        wpcvi_media.button = $button;

        if (upload_id) {
          wpcvi_media.upload_id = upload_id;
        } else {
          wpcvi_media.upload_id = wpcvi_media.post_id;
        }

        if (wpcvi_media.frame) {
          wpcvi_media.frame.uploader.uploader.param('post_id',
              wpcvi_media.upload_id);
          wpcvi_media.frame.open();
          return;
        } else {
          wp.media.model.settings.post.id = wpcvi_media.upload_id;
        }

        wpcvi_media.frame = wp.media.frames.wpcvi_media = wp.media({
          title: wpcvi_vars.media_title, button: {
            text: wpcvi_vars.media_add_text,
          }, library: {
            type: 'image',
          }, multiple: true,
        });

        wpcvi_media.frame.on('select', function() {
          var selection = wpcvi_media.frame.state().get('selection');
          var id = wpcvi_media.button.closest('.wpcvi-images-form').data('id');
          var images = wpcvi_media.button.closest('.wpcvi-images-form').
              find('ul.wpcvi-images');

          selection.map(function(attachment) {
            attachment = attachment.toJSON();

            if (attachment.id) {
              var url = attachment.sizes.thumbnail ?
                  attachment.sizes.thumbnail.url :
                  attachment.url;
              images.append('<li data-id="' + attachment.id +
                  '"><span href="#" class="wpcvi-image-thumb"><a class="wpcvi-image-remove" href="#"></a><img src="' +
                  url + '" /></span></li>');
            }
          });

          wp.media.model.settings.post.id = wpcvi_media.post_id;

          init_sortable();
          init_image(id);
        });

        wpcvi_media.frame.open();
      });
    }

    function init_sortable() {
      $.each($('.wpcvi-images-form'), function() {
        var id = $(this).data('id');

        $(this).find('ul.wpcvi-images').sortable({
          update: function() {
            init_image(id);
          }, placeholder: 'sortable-placeholder', cursor: 'move',
        });
      });
    }

    function init_remove() {
      $(document).on('click touch', 'a.wpcvi-image-remove', function(e) {
        e.preventDefault();

        var id = $(this).closest('.wpcvi-images-form').data('id');

        $(this).closest('li').remove();
        init_image(id);
      });
    }

    function init_image(id) {
      if (parseInt(id) <= 0) {
        return;
      }

      var order = [];
      var $form = $('.wpcvi-images-form[data-id="' + id + '"]');

      if ($form.find('ul.wpcvi-images li').length) {
        $.each($form.find('ul.wpcvi-images li'), function() {
          order.push($(this).data('id'));
        });

        $form.find('input.wpcvi-images-ids').val(order);
      } else {
        $form.find('input.wpcvi-images-ids').val('');
      }

      order.join(',');

      $('#variable_product_options').find('input').eq(0).change();

      $form.closest('.woocommerce_variation').
          eq(0).
          addClass('variation-needs-update');
    }

    init();

    $(document).
        on('woocommerce_variations_added woocommerce_variations_loaded',
            function() {
              init();
            });
  });
})(jQuery);
