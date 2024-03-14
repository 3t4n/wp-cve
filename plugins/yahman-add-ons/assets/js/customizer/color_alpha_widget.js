jQuery( function( $ ) {
	jQuery(document).ready(function () {
		var params = {
			change: function(e, ui) {
				jQuery( e.target ).val( ui.color.toString() );
                jQuery( e.target ).trigger('change'); // enable widget "Save" button
            },
            clear: function(e, ui) {
            	jQuery( e.target ).val();
                jQuery( e.target ).trigger('change'); // enable widget "Save" button
            },
        };

        jQuery('#widgets-right .ya_color-picker, .inactive-sidebar .ya_color-picker').wpColorPicker(params);
        // Executes wpColorPicker function after AJAX is fired on saving the widget
        jQuery(document).ajaxComplete(function() {
        	jQuery('#widgets-right .ya_color-picker, .inactive-sidebar .ya_color-picker').wpColorPicker(params);
        });


        jQuery(document).on( 'ready widget-added widget-updated', function(event, widget) {
        	jQuery('.ya_color-picker').not('[id*="__i__"]').wpColorPicker();
        });

    });
} );


( function( $ ){
	function initColorPicker( widget ) {
		widget.find( '.ya_color-picker' ).wpColorPicker( {
						change: _.throttle( function() { // For Customizer
							$(this).trigger( 'change' );
						}, 3000 )
					});
	}

	function onFormUpdate( event, widget ) {
		initColorPicker( widget );
	}

	$( document ).on( 'widget-added widget-updated', onFormUpdate );

	$( document ).ready( function() {
		$( '#widgets-right .widget:has(.ya_color-picker)' ).each( function () {
			initColorPicker( $( this ) );
		} );
	} );
}( jQuery ) );