
jQuery(document).ready(function () {

  function initColorPicker( widget ) {
    widget.find( '.ya_color-picker' ).not('[id*="__i__"]').wpColorPicker( {
      change: _.throttle( function() {
        jQuery(this).trigger( 'change' );
      }, 3000 )
    });
  }

  function onFormUpdate( event, widget ) {
    initColorPicker( widget );
  }

  jQuery( document ).on( 'widget-added widget-updated', onFormUpdate );

  jQuery( document ).ready( function() {
    jQuery( '.widget-inside:has(.ya_color-picker)' ).each( function () {
      initColorPicker( jQuery( this ) );
    } );
  } );




});



