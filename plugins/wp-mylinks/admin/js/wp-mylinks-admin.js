(function( $ ) {
	'use strict';

	/**
	 * All of the code for admin-facing JavaScript source.
	 */

})( jQuery );

jQuery(document).ready(function($){
  var mediaUploader;
  $('#upload_image_button').click(function(e) {
    e.preventDefault();
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Favicon',
      button: {
      text: 'Choose Favicon'
    }, multiple: false });
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#mylinks_upload_favicon').val(attachment.url);
    });
    mediaUploader.open();
  });
});