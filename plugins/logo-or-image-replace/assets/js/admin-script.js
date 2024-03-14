jQuery(document).ready(function($){

  'use strict';

  // Instantiates the variable that holds the media library frame.
  var metaImageFrame;

  // Runs when the media button is clicked.
  $( 'body' ).click(function(e) {

    // Get the btn
    var btn = e.target;

    // Check if it's the upload button
    if ( !btn || !$( btn ).attr( 'data-media-uploader-target' ) ) return;

    // Get the field target
    var field = $( btn ).data( 'media-uploader-target' );

    // Prevents the default action from occuring.
    e.preventDefault();

    // Sets up the media library frame
    metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
      title: meta_image.title,
      button: { text:  'Use this Image' },
      multiple: false,
      frame:    'post',    // <-- this is the important part
      state:    'insert',
    });

    // Runs when an image is selected.
    metaImageFrame.on('select', function() {

      // Grabs the attachment selection and creates a JSON representation of the model.
      var media_attachment = metaImageFrame.state().get('selection').first().toJSON();

      // Sends the attachment URL to our custom image input field.
      $( field ).val(media_attachment.url);

    });

    // When an image is inserted, run a callback.
    metaImageFrame.on( 'insert', function(selection) {
        var state = metaImageFrame.state();
        selection = selection || state.get('selection');
        if (! selection) return;
        // We set multiple to false so only get one image from the uploader
        var attachment = selection.first();
        var display = state.display(attachment).toJSON();  // <-- additional properties
        attachment = attachment.toJSON();
        // Do something with attachment.id and/or attachment.url here
        var imgurl = attachment.sizes[display.size].url;
        jQuery( field ).val( imgurl );
    });

    // Opens the media library frame.
    metaImageFrame.open();

  });

});