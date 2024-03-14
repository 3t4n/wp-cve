
/**
 * @package: Category Featured Images Extended
 * @Version: 0.99 BETA
 * @Author: CK MacLeod, Category Featured Images by Mattia Roccoberton
 * @Author: URI: http://ckmacleod.com
 * @License: GPL3
 */
 

jQuery(document).ready( function() {
    
	var cfix_media_upload;

	jQuery('#cfix-change-image').click(function(e) {
		e.preventDefault();

	// If the uploader object has already been created, reopen the dialog
		if( cfix_media_upload ) {
			cfix_media_upload.open();
			return;
		}

	// Extend the wp.media object
		cfix_media_upload = wp.media.frames.file_frame = wp.media({
			title: button_text.title,
			button: { text: button_text.button },
			multiple: false
		});
 
	//When a file is selected, grab the URL and set it as the text field's value
		cfix_media_upload.on( 'select', function() {
			attachment = cfix_media_upload.state().get( 'selection' ).first().toJSON();
			jQuery('#cfix-featured-image').val( attachment.id );
			jQuery('#cfix-thumbnail').empty();
			jQuery('#cfix-thumbnail').append( '<img src="' + attachment.url + '" class="attachment-thumbnail cfix-preview" />' );
		});

	//Open the uploader dialog
		cfix_media_upload.open();
	});

	jQuery('#cfix-remove-image').click(function(e) {
		jQuery('#cfix-featured-image').val('');
		jQuery('#cfix-thumbnail').empty();
                //fill emptied space with hidden no-image div
                jQuery('#cfix-thumbnail-no-image').toggle();        
	});
        
        jQuery('#cancel-removal').click(function() { 
                location.reload(); 
        });
        
});



