'use strict';

(function($) {
  let woovr_media = {
    frame: null,
    button: null,
    upload_id: null,
  };

  $(function() {
    woovr_image_selector();

    if ($('input[name="_woovr_active"]:checked').val() == 'yes') {
      $('.woovr_show_if_active').css('display', 'flex');
    }

    woovr_custom_clear_image();
  });

  $(document).
      on('woocommerce_variations_added woocommerce_variations_loaded',
          function() {
            woovr_image_selector();
          });

  $(document).on('click touch', 'a.woovr_image_remove', function(e) {
    e.preventDefault();
    $(this).
        closest('.woovr_image_selector').
        find('.woovr_image_id').val('').trigger('change');
    $(this).
        closest('.woovr_image_selector').
        find('.woovr_image_preview').html('');
  });

  $(document).on('change', 'input[name="_woovr_active"]', function() {
    if ($(this).val() == 'yes') {
      $('.woovr_show_if_active').css('display', 'flex');
    } else {
      $('.woovr_show_if_active').css('display', 'none');
    }
  });

  $(document).on('change', '.woovr_clear_image', function() {
    woovr_custom_clear_image();
  });

  function woovr_custom_clear_image() {
    if ($('.woovr_clear_image').val() === 'custom') {
      $('.woovr_clear_image_custom').show();
    } else {
      $('.woovr_clear_image_custom').hide();
    }
  }

  function woovr_image_selector() {
    $('a.woovr_image_add').on('click touch', function(e) {
      e.preventDefault();

      var $button = $(this);
      var upload_id = parseInt($button.attr('rel'));

      woovr_media.button = $button;

      if (upload_id) {
        woovr_media.upload_id = upload_id;
      } else if (typeof woocommerce_admin_meta_boxes_variations !==
          'undefined') {
        woovr_media.upload_id = woocommerce_admin_meta_boxes_variations.post_id;
      }

      if (woovr_media.frame) {
        woovr_media.frame.uploader.uploader.param('post_id',
            woovr_media.upload_id);
        woovr_media.frame.open();
        return;
      } else {
        wp.media.model.settings.post.id = woovr_media.upload_id;
      }

      woovr_media.frame = wp.media.frames.woovr_media = wp.media({
        title: woovr_vars.media_title, button: {
          text: woovr_vars.media_add_text,
        }, library: {
          type: 'image',
        }, multiple: true,
      });

      woovr_media.frame = wp.media.frames.woovr_media = wp.media({
        title: woovr_vars.media_title, button: {
          text: woovr_vars.media_add_text,
        }, library: {
          type: 'image',
        }, multiple: true,
      });

      woovr_media.frame.on('select', function() {
        var selection = woovr_media.frame.state().get('selection');
        var $preview = woovr_media.button.
            closest('.woovr_image_selector').
            find('.woovr_image_preview');
        var $image_id = woovr_media.button.
            closest('.woovr_image_selector').
            find('.woovr_image_id');

        selection.map(function(attachment) {
          attachment = attachment.toJSON();

          if (attachment.id) {
            var url = attachment.sizes.thumbnail ?
                attachment.sizes.thumbnail.url :
                attachment.url;
            $preview.html('<img src="' + url +
                '" /><a class="woovr_image_remove button" href="#">' +
                woovr_vars.media_remove + '</a>');
            $image_id.val(attachment.id).trigger('change');
          }
        });

        if (typeof woocommerce_admin_meta_boxes_variations !== 'undefined') {
          wp.media.model.settings.post.id = woocommerce_admin_meta_boxes_variations.post_id;
        }
      });

      woovr_media.frame.open();
    });
  }
})(jQuery);