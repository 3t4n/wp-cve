function tmip_select_all(id) {
	document.getElementById(id).focus();
	document.getElementById(id).select();
}
( function( $ ) {
    $( document ).ready( function() {
        $( '.rate-tmip-stars' ).find('a').hover(
            function() {
                $(this).nextAll('a').children( 'span' ).removeClass('dashicons-star-filled').addClass( 'dashicons-star-empty' );
                $(this).prevAll('a').children( 'span' ).removeClass( 'dashicons-star-empty' ).addClass('dashicons-star-filled');
                $(this).children( 'span' ).removeClass( 'dashicons-star-empty' ).addClass('dashicons-star-filled');
            }, function() {
                var rating = $( 'input#rating' ).val();
                if ( rating ) {
                    var list = $( '.rate-tmip-stars a' );
                    list.children( 'span' ).removeClass('dashicons-star-filled').addClass( 'dashicons-star-empty' );
                    list.slice( 0, rating ).children( 'span' ).removeClass( 'dashicons-star-empty' ).addClass('dashicons-star-filled');
                }
            }
        );
    });

})( jQuery );

