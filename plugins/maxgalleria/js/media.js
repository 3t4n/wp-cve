jQuery(document).ready(function() {
    // This holds our custom media manager
    var maxgalleria_media_frame;

	// Bind to our click event
    jQuery(document.body).on("click.maxgalleria-media", ".maxgalleria-open-media", function(e) {
        e.preventDefault();
		
        // If the frame already exists, re-open it
        if (maxgalleria_media_frame) {
            maxgalleria_media_frame.open();
            return;
        }

		// Create our media frame with some options
        maxgalleria_media_frame = wp.media.frames.maxgalleria_media_frame = wp.media({			
			// Use our own custom class name to avoid CSS conflicts
            className: "media-frame maxgalleria-media-frame",

			// The other option here is setting the frame to "select"
            frame: "post"
        });
		
        // With everything set, open the frame
        maxgalleria_media_frame.open();
		
		// Hide the duplicate "Media Library" menu items on the left side of the
		// media manager, not sure why they are there or where they are coming from.
		// However, the "Media Library" tab in the main part of the frame should
		// still be visible. Note that we're using the mediaLibraryTitle property
		// from the global _wpMediaViewsL10n javascript object provided by WP.
		jQuery(".media-frame-menu .media-menu-item:contains('" + _wpMediaViewsL10n.mediaLibraryTitle + "')").hide();
		
		// Remove the "All media items" option from the attachment filters dropdown
		// list and pre-select the "Images" option (whose value is "image").
		jQuery(".attachment-filters option[value='all']").remove();
		jQuery(".attachment-filters").val("image");
		
		// Bind the insert event to grab the chosen images to add to the gallery
        maxgalleria_media_frame.on("insert", function() {
			// If many images are selected from the media library, it could take a
			// few minutes, so we show the user a message asking them to be patient
			jQuery(".maxgalleria-meta .adding-media-library-images-note").show();
			
			// Grab the selected attachments and turn them into JSON objects
			var media_attachments = maxgalleria_media_frame.state().get("selection").toJSON();

			// Start building the form data with the post ID
			var form_data = "gallery_id=" + jQuery("#post_ID").val();
			
			// Go through each selected media item and grab its data
			jQuery(media_attachments).each(function() {
				form_data += "&url[]=" + this.url;
				form_data += "&title[]=" + this.title;
				form_data += "&caption[]=" + this.caption;
				form_data += "&description[]=" + this.description;
				form_data += "&alt_text[]=" + this.alt;
			});
			
			// Append the ajax action to the end of the form data
			form_data += "&action=add_media_library_images_to_gallery";
      
      form_data += "&nonce=" + mg_media.nonce;

			// Post the form data in an ajax call
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: form_data,
				success: function(result) {
					if (result == "0") {
						alert("There was a problem adding the images to your gallery.");
						jQuery(".maxgalleria-meta .adding-media-library-images-note").hide();
					}
					else {
						window.top.location = window.top.location.href;
					}
				}
			});
        });
    });
});
