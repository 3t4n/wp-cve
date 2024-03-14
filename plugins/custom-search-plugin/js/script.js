( function( $ ) {
	$( document ).ready( function() {
		$('#main>.cstmsrch-submit-type').detach().prependTo('#content');
		$( '#cstmsrch_div_select_all' ).show( 0, function() {
			var select_all			= $( '.cstmsrch-form-table input#cstmsrch_select_all' ),
				checkboxes			= $( '.cstmsrch-form-table input[name="cstmsrch_fields_array[]"]:enabled' ),
				checkboxes_total	= checkboxes.size(),
				checkboxes_selected	= checkboxes.filter(':checked').size();
			if ( checkboxes_total == checkboxes_selected ) {
				select_all.prop( 'checked', true );
			}
		});
		$( '.cstmsrch-form-table input' ).bind( 'change click select', function() {
			var	select_all					= $( '.cstmsrch-form-table input#cstmsrch_select_all' ),
				checkboxes					= $( '.cstmsrch-form-table input[name="cstmsrch_fields_array[]"]:enabled' ),
				checkboxes_size				= checkboxes.size(),
				checkboxes_selected_size	= checkboxes.filter( ':checked' ).size();
			if ( $( this ).attr( 'id' ) == select_all.attr( 'id' ) ) {
				if ( select_all.is( ':checked' ) ) {
					checkboxes.prop( 'checked', true );
				} else {
					checkboxes.prop( 'checked', false );
				}
			} else {
				if ( checkboxes_size == checkboxes_selected_size ) {
					select_all.prop( 'checked', true );
				} else {
					select_all.prop( 'checked', false );
				}
			}
		} );
	} );
} )( jQuery );