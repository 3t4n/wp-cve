( function( $ ) {
	'use strict';

	var $doc = $( document );

	$doc.ready( function( $ ) {

		// Add items.
		$( 'body' ).on( 'click', '.csw-form .csw-add', function( event ) {
			event.preventDefault();
			var widgetForm = $( this ).closest( '.csw-form' ),
				cloneEl    = widgetForm.find( '.csw-clone' );
			widgetForm.find( '.csw-sortable' ).append( '<li>' + cloneEl.html() + '</li>' );
		} );

		// Delete items.
		$( 'body' ).on( 'click', '.csw-form .csw-remove', function( event ) {
			event.preventDefault();
			var $parent = $(this).parents( '.csw-form' );
			if ( confirm( candySocialWidget.confirm ) ) {
				$( this ).closest( 'li' ).remove()
				$parent.find( 'input[type="text"]' ).eq(0).trigger( 'change' );
			}
		} );

		// Sort items.
		function sortServices() {
			$( '.csw-form .csw-sortable' ).each( function() {
				var id = $( this ).attr( 'id' ),
					$el = $( '#' + id );
				$el.sortable( {
					cursor: 'move',
					placeholder: '.csw-placeholder',
					opacity: 0.6,
					update: function( event, ui ) {
						$el.find( 'input[type="text"]' ).trigger( 'change' );
					}
				} );
			} );
		}

		sortServices();

		// Re-run sorting as needed.
		$doc.on( 'widget-updated', sortServices );
		$doc.on( 'widget-added', sortServices );
	} );

} ) ( jQuery );