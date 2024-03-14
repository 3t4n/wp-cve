jQuery( document ).ready( function( $ )
{
    $( '.wpsa-browse' ).on( 'click', function ( event )
    {
        event.preventDefault();
        
        var self = $( this );

        // Create the media frame.
        var file_frame = wp.media.frames.file_frame = wp.media(
        {
            title: 'Upload Taxonomy Image',
            button:
            {
                text: 'Upload',
            },
            multiple: false
        } );

        file_frame.on( 'select', function ()
        {
            attachment = file_frame.state().get( 'selection' ).first().toJSON();
            
            self.prev('.wpsa-url').val( attachment.url ).change();
        } );

        // Finally, open the modal
        file_frame.open();
    } );
} );