jQuery( document ).ready(function() {
	
	jQuery("#ns-gallery-btn").on('click', function() {
		open_media_uploader_image();
	});
	
	jQuery('#ns-set-to-default').on('click', function(){
		jQuery('#ns-placeholder-image-from-list').val(jQuery('#ns-replace-default-place-val').val());
	});

		
var media_uploader = null;

function open_media_uploader_image()
{
    media_uploader = wp.media({
        frame:    "select",
        multiple: false,
		title: 'Select Placeholder Image',
		library: { 
		type: 'image' // limits the frame to show only images
	   },
    });

    media_uploader.on("select", function(){
        var json = media_uploader.state().get("selection").first().toJSON();

        var image_url = json.url;
        var image_caption = json.caption;
        var image_title = json.title;
		
		jQuery('#ns-placeholder-image-from-list').val('');
		jQuery('#ns-placeholder-image-from-list').val(image_url);
		
    });

    media_uploader.open();
}

	
});