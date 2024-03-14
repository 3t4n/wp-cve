
/*
* This file enhances the plugin to be used with Customizer smoothly
*/

( function( $ ) {
		wp.customize('related_posts_title', function(value) {
		value.bind(function(to){
			$('related_posts_title').text(to);
		});
	});
	//Related Posts Number
	wp.customize('related-number', function(value){
		value.bind(function(to) {
			$('related_posts_number').text(to);
		} );
	} );
})( jQuery );