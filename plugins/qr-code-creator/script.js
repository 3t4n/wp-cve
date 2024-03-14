jQuery(document).ready(function($) {
	// Create QR code.
	$( '#create_qr_code' ).on( 'click', function( e ) {
		var content = $('#qr_code_content').val().trim();

		if ( content.length <= 0 ) {
			return;
		}

		content = encodeURIComponent( content );

		var imgUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' + content;

		$( '#qr_code' ).attr( 'src', imgUrl + '&size=150x150' );

		imgUrl += '&size=250x250';
		$( '#qr_code_download_link' ).html( '<a href="'+imgUrl+'&format=png" target="_blank">PNG</a> | <a href="'+imgUrl+'&format=jpg" target="_blank">JPG</a> | <a href="'+imgUrl+'&format=svg" target="_blank">SVG</a> | <a href="'+imgUrl+'&format=eps" target="_blank">EPS</a>' );

	} );

	// Reset text field.
	$( '#reset_qr_code' ).on( 'click', function( e ) {
		$('#qr_code_content').val('');
		$( '#qr_code' ).attr( 'src', '');
		$( '#qr_code_download_link' ).html('');
	});
});
