	jQuery(document).ready(function($){
		alert("Hello! I am an alert box!!"); 
	  var w2aMediaUploader;
	
	  $('#w2a-upload-button').click(function(e) {
		e.preventDefault();
		// If the uploader object has already been created, reopen the dialog
		  if (w2aMediaUploader) {
		  w2aMediaUploader.open();
		  return;
		}
		// Extend the wp.media object
		w2aMediaUploader = wp.media.frames.file_frame = wp.media({
		  title: 'Choose Image',
		  button: {
		  text: 'Choose Image'
		}, multiple: false });
	
		// When a file is selected, grab the URL and set it as the text field's value
		w2aMediaUploader.on('select', function() {
		  attachment = w2aMediaUploader.state().get('selection').first().toJSON();
		  $('#image-url').val(attachment.url);
		});
		// Open the uploader dialog
		w2aMediaUploader.open();
	  });
	
	});