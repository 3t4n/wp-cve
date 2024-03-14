function is_scrolled_into_view( elem )
{
	var docViewTop 		= $( window ).scrollTop();
	var docViewBottom 	= docViewTop + $( window ).height();
	var elemTop 		= $( elem ).offset().top;
	var elemBottom 		= elemTop + $( elem ).height();
	var elemHeight 		= $( elem ).height();

	var sensitivity = 1;

	var sensitivity_factor = $( window ).height() * sensitivity;

	var bounding = elem[0].getBoundingClientRect();
	
	return bounding.top <= $( window ).height() + ( $( window ).height() * 0.2 );
}

function check_resources()
{
	let items = [];

	$( '.areoi-lazy:not(.areoi-lazy-loaded)' ).each( function( key, item ) {
		var item = $( this );
		if ( is_scrolled_into_view( item ) ) {
			var src = item.data( 'src' );
			item.addClass( 'areoi-lazy-loaded' );
			item.attr( 'src', src );
			item.css( 'opacity', 1 );
		}
	});
}

$( window ).on( 'scroll', function() {
	check_resources();
});
check_resources();