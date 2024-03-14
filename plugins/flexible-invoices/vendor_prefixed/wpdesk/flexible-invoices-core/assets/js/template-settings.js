( function ( $ ) {

	jQuery( document ).ready( function ( $ ) {
		$('.color-picker').wpColorPicker();
		
		jQuery(document).find('.color-picker.disabled').each(function (index, item) {
			let container = jQuery(this).closest('.wp-picker-container');
			container.find('.wp-color-result').attr('disabled', 'disabled');
		});
	} );

	jQuery('.select-images label').click(function (e) {
		if (jQuery(this).hasClass('disabled')) {
			e.preventDefault();
			return false;
		}
		
		jQuery( '.select-images' ).find( 'label' ).removeClass( 'checked-image' );
		jQuery( this ).addClass( 'checked-image' );
	} );

	jQuery( '.select-images a.zoom' ).click( function ( e ) {
		jQuery( this ).closest( '.select-images' ).find( '.large-view' ).hide();
		jQuery( this ).parent().find( '.large-view' ).show();
		return false;
	} );

	jQuery( '.large-view' ).click( function ( e ) {
		jQuery( this ).parent().find( '.large-view' ).hide();
		return false;
	} );

	jQuery( document ).on( 'click', '.reset-pdf-template', function ( e ) {
		let fields = jQuery( this ).closest( 'form' ).find( '[data-default_value]' );
		fields.each( function ( i, n ) {
			let value = jQuery( this ).attr( 'data-default_value' );
			jQuery( this ).val( value ).change();
		} );
		return false;
	} );

} )( jQuery );
