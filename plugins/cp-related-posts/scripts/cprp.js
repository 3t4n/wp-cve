/**
 * cp-related-posts.js
 */
jQuery( window ).on( 'load', function(){
	var $ = jQuery;
	$('.cprp_items').show();
	$('.cprp_percentage').each(
		function()
        {
			var me = $(this),
				n  = Math.ceil( (me.html() * 1) / 20 ),
				s  = '';

			n = Math.min( 5, Math.max( 1, n ) );

			for( var i = 0; i < n; i++ ){
				s += '<img src="'+cprp[ 'star_on' ]+'" />';
			}

			for( var i = 0; i < ( 5 - n ); i++ ){
				s += '<img src="'+cprp[ 'star_off' ]+'" />';
			}

			me.html( s );
		}
	);
	$('.cprp_excerpt_content br').remove();
	$('.cprp_thumbnail').removeAttr( 'width' ).removeAttr( 'height' );
});