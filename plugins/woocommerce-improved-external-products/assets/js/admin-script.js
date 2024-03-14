jQuery( function( $ ) {
	$( '.wpo-iepp-pro-notice' ).on( 'click', '.notice-dismiss', function( event ) {
		event.preventDefault();
  		window.location.href = $( '.wpo-iepp-dismiss' ).attr('href');
	});
});