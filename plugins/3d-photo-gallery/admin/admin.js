jQuery(document).ready(function($) {
	jQuery('#gallery-load').hide();
	jQuery('#gallery-saved').hide();
    

    jQuery( "#accordion" ).accordion();
	var la_photo_gallery;


     
    jQuery('.upload_image_button').live('click', function( event ){
     
        event.preventDefault();
     
     
        // Create the media frame.
        la_photo_gallery = wp.media.frames.la_photo_gallery = wp.media({
          title: 'Select Images for 3D Photo Gallery',
          button: {
            text: 'Add',
          },
          multiple: true  // Set to true to allow multiple files to be selected
        });
     
        // When an image is selected, run a callback.
        la_photo_gallery.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            var selection = la_photo_gallery.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                jQuery('.selected_images').append('<div><img src="'+attachment.url+'"><span class="dashicons dashicons-dismiss"></span><textarea placeholder="Description" name="" id="desc"></textarea><label>URL<input id="image-url" class="widefat" type="text" value=""></label></div>');

            });  
        });
     
        // Finally, open the modal 
        la_photo_gallery.open();
    });

    jQuery(".selected_images").sortable({
      placeholder: "ui-state-highlight"
    });

    jQuery('.la-photo-gallery').on('click', '.save_gallery', function(event) {
        event.preventDefault();

        jQuery('#gallery-load').show();
        var images = [];
        var des = [];
        var urls = [];

        jQuery('.selected_images div').each(function(index) {
            // console.log(index + '  '+ jQuery(this).find('img').attr('src'));
            images[index] = jQuery(this).find('img').attr('src');

        });

          jQuery('.selected_images div').each(function(index) {
            // console.log(index + '  '+ jQuery(this).find('img').attr('src'));
            des[index] = jQuery(this).find('textarea').val();

        });

         jQuery('.selected_images div').each(function(index) {
            // console.log(index + '  '+ jQuery(this).find('img').attr('src'));
            urls[index] = jQuery(this).find('#image-url').val();

        });
        var data = {
            action: 'la_save_photo_gallery_images',
            images: images,
            width: jQuery('#image-widht').val(),
            height: jQuery('#image-height').val(),
            des: des,
            url: urls
        }

        jQuery.post(laAjax.url, data, function(resp) { 

            if (images[0] == null) {
                location.reload();
                console.log(data);
            }
        jQuery('#gallery-load').hide();
        jQuery('#gallery-saved').show();
        $('#gallery-saved').delay(1000).fadeOut();
        });

    });
    



    jQuery('.la-photo-gallery').on('click', '.dashicons-dismiss', function() {
        jQuery(this).parent('div').remove();
    });
});