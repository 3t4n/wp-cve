jQuery(document).ready(function($){
  // Upload Image on Setting Page
  let mediaUploader;
  $('#atpcn_upload_button').click(function(e) {
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
      attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#atpcn_image_url').val(attachment.url);
    });
    mediaUploader.open();
  });
  $('.color-picker').wpColorPicker();
});