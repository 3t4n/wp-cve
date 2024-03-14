jQuery(document).ready(function($)
{	var mediaUploader;

	$('#upload-button').click(function(e)
	 { e.preventDefault();

	   //--- Reopen uploader is already created:
		 if (mediaUploader)
		    { mediaUploader.open();
			    return;
		    }

	   mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose watermark image',
		  	library: {
        orderby: 'date',
        query: true, 
        post_mime_type: ['image/gif','image/jpeg','image/png']
        },
				button: {
				text: 'Use as watermark'
		    }, 
			  multiple: false });

	   mediaUploader.on('select', function()
	   { attachment = mediaUploader.state().get('selection').first().toJSON();
	     $('#optImageWM').val(attachment.url);
       $('#divImageWM').text(attachment.url);
		 });

	   mediaUploader.open();
	});
});
