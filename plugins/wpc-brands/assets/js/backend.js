'use strict';

(function($) {
  var media_uploader = null;

  $(document).
      on('click touch', '#wpcbr_logo_select, #wpcbr_banner_select',
          function(event) {
            image_uploader(event, $(this));
          });

  $(document).on('click touch', '.wpcbr_remove_image', function(e) {
    var $wrap = $(this).closest('.wpcbr_image_uploader');

    $wrap.find('.wpcbr_image_val').val('');
    $wrap.find('.wpcbr_selected_image_img').html('');
    $wrap.find('.wpcbr_selected_image').hide();
  });

  // clear custom fields when brand is added
  if ($('body').hasClass('edit-tags-php') &&
      $('body').hasClass('taxonomy-wpc-brand')) {
    $(document).ajaxSuccess(function(event, xhr, settings) {
      // check ajax action of request that succeeded
      if (typeof settings != 'undefined' && settings.data &&
          ~settings.data.indexOf('action=add-tag') &&
          ~settings.data.indexOf('taxonomy=wpc-brand')) {
        $('.wpcbr_image_val').val('');
        $('.wpcbr_selected_image_img').html('');
        $('.wpcbr_selected_image').hide();
        $('.wpcbr_description').val('').trigger('change');
      }
    });
  }

  function image_uploader(event, btn) {
    var $wrap = btn.closest('.wpcbr_image_uploader');

    media_uploader = wp.media({
      frame: 'post', state: 'insert', multiple: false,
    });

    media_uploader.on('insert', function() {
      var json = media_uploader.state().get('selection').first().toJSON();
      var image_id = json.id;
      var image_url = json.url;
      var image_html = '<img src="' + image_url + '"/>';

      $wrap.find('.wpcbr_image_val').val(image_id);
      $wrap.find('.wpcbr_selected_image').show();
      $wrap.find('.wpcbr_selected_image_img').html(image_html);
    });

    media_uploader.open();
  }
})(jQuery);
