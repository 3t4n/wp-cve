jQuery(document).ready(function($){






  
  var mediaUploader;
  $('#upload_image_button').click(function(e) {
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
      $('#header_banner_0').val(attachment.url);

       $('#pv_d').attr("src", attachment.url)
    });
    mediaUploader.open();
  });


$('#pv_d').attr("src", $('#header_banner_0').val());

$( "#remove-banner" ).click(function() {
   $('#header_banner_0').val('');
$('#pv_d').attr("src", $('#header_banner_0').val());

});


$('#editor_information_1').trumbowyg({
    btns: [
        ['strong', 'em'],
        ['justifyLeft', 'justifyCenter'],
        // ['insertImage', 'link']
    ]
});
      

});


 