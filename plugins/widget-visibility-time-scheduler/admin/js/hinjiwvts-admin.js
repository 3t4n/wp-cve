jQuery( document ).ready( function( $ ){
	/*
	 * toggle visibility of scheduler in widget
	 */
	$( document ).on( 'click', '.wvts-link', function( e ) {
		// remove default behaviour
		e.preventDefault();
		// get grandparent element of clicked link
		var origin_parent = $( this ).parent().parent();
		// if scheduler is closed: open it, else: close it
		// (i.e. change the css class name to let the 'display' property change from 'block' to 'none' and vice versa)
		// and change the text of the clicked link
		if ( origin_parent.hasClass( 'wvts-collapsed' ) ) {
			origin_parent.removeClass( 'wvts-collapsed' ).addClass( 'wvts-expanded' );
			$( this ).text( wvts_i18n.close_scheduler )
		} else {
			origin_parent.removeClass( 'wvts-expanded' ).addClass( 'wvts-collapsed' );
			$( this ).text( wvts_i18n.open_scheduler )
		}
	} );
} );
