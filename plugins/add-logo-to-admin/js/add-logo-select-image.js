( function($) {
	var file_frame;
	$( '#add-logo-table' )
		.on( 'click', '.select-image', function(e) {
		    e.preventDefault();

			// Let's start over to make sure everything works
		    if ( file_frame )
		        file_frame.remove();

		    file_frame = wp.media.frames.file_frame = wp.media( {
		        title: $(this).data( 'uploader_title' ),
		        button: {
		            text: $(this).data( 'uploader_button_text' )
		        },
		        multiple: false
		    } );

		    file_frame.on( 'select', function() {
		        var attachment = file_frame.state().get( 'selection' ).first().toJSON();
				$( '#add-logo-image' ).val( attachment.url );
				$( '#add-logo-image-container' ).html( '<img src="' + attachment.url + '" alt="" style="max-width:100%;" />' );
		    } );

		    file_frame.open();
		    $( '.delete-image' ).show();
		} )
		.on( 'click', '.delete-image', function(e) {
		    e.preventDefault();
		    $(this).hide();
			$( '#add-logo-image' ).val( '' );
			$( '#add-logo-image-container' ).html( '' );
		} );

} )(jQuery);