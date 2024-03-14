jQuery(document).ready(function($){
  var mediaUploader;
  $('#upload_image_button_one').click(function(e) {
    e.preventDefault();
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
      text: 'Choose Image'
    }, multiple: false });
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#fb_overlay_img').val(attachment.url);
      $('#pv_overlay_img').attr("src", attachment.url);


    });
    mediaUploader.open();
  });

  var mediaUploader;
  $('#upload_image_button_d').click(function(e) {
    e.preventDefault();
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
      text: 'Choose Image'
    }, multiple: false });
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#fb_default').val(attachment.url);
      $('#pv_d').attr("src", attachment.url);

    });
    mediaUploader.open();
  });

});
