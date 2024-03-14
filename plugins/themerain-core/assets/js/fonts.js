( function( $ ) {

  'use strict';

  $( function() {

    var themerainFontsMediaFrame;

    $( document ).on( 'click', '.upload-font-container .upload-font-link', function( e ) {
      e.preventDefault();

      var parentContainer = $( this ).parents( '.upload-font-container' );

      themerainFontsMediaFrame = wp.media( {
        title: "Select or Upload Font",
        library: {
          type: "application/x-font-woff2"
        },
        multiple: false
      } );

      themerainFontsMediaFrame.on( 'select', function() {
        var attachment = themerainFontsMediaFrame.state().get( 'selection' ).first().toJSON();
        parentContainer.find( '.filename' ).val( attachment.url ).change();
        themerainFontsMediaFrame.close();
      } );

      themerainFontsMediaFrame.open();
    } );

    $( document ).on( 'click', '.themerain-fonts-delete', function() {
      return confirm( "Are you sure you want to delete this font?" ) ? true : false;
    } );

  } );

} )( jQuery );
