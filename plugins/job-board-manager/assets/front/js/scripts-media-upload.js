jQuery(document).ready(function($) {

	$(document).on('click','.job-bm-media-upload .media-upload',function(e){
		var side_uploader;
		this_ = $(this);
		//alert(target_input);
		e.preventDefault();
		//If the uploader object has already been created, reopen the dialog
		if (side_uploader) {
			side_uploader.open();
			return;
		}
		//Extend the wp.media object
		side_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose file',
			button: {
				text: 'Choose file'
			},
			multiple: false
		});
		//When a file is selected, grab the URL and set it as the text field's value
		side_uploader.on('select', function() {
			attachment = side_uploader.state().get('selection').first().toJSON();

			console.log(attachment);

			attachmentId = attachment.id;
			src_url = attachment.url;
			subtype = attachment.subtype;
			mime = attachment.mime;
			filename = attachment.filename;



			$(this_).prev().val(src_url);

			if(mime == "image/jpeg" || mime == "image/png"){
				$(this_).parent().children('.media-preview-wrap').html('<img class="media-preview" src="'+src_url+'" />');
			}else{
				$(this_).parent().children('.media-preview-wrap').html(filename);
			}







		});

		//Open the uploader dialog
		side_uploader.open();

	})


});







