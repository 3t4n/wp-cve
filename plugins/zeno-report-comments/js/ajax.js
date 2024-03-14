
/*
 * JavaScript for Zeno Report Comments.
 */


jQuery(document).ready(function($) {
	jQuery( 'span.zeno-comments-report-link span a' ).on( 'click', function( a_element ) {

		var comment_id = jQuery( this ).attr('data-zeno-comment-id');
		var timeout  = zenocommentsajax.timeout;
		var timeout2 = zenocommentsajax.timeout2;
		var timer  = new Number( jQuery( 'input.' + timeout ).val() );
		var timer2 = new Number( jQuery( 'input.' + timeout2 ).val() );

		var zeno_comments_ajax_data = {
			comment_id: comment_id,
			sc_nonce:   zenocommentsajax.nonce,
			action:     'zeno_report_comments_flag_comment',
			xhrFields:  {
				withCredentials: true
			}
		};
		zeno_comments_ajax_data[timeout] = timer;
		zeno_comments_ajax_data[timeout2] = timer2;

		jQuery.post(
			zenocommentsajax.ajaxurl, zeno_comments_ajax_data,
			function( response ) {
				var span_id = 'zeno-comments-result-' + comment_id;
				jQuery( 'span#' + span_id ).html( response );
			}
		);

		return false;

	});
});

jQuery( document ).ready( function() {
	jQuery( '.hide-if-js' ).hide();
	jQuery( '.hide-if-no-js' ).show();
});


/*
* Mangle data for the form timeout.
*/
jQuery(document).ready(function($) {

	var timeout  = zenocommentsajax.timeout;
	var timeout2 = zenocommentsajax.timeout2;

	var timer  = new Number( jQuery( 'input.' + timeout ).val() );
	var timer2 = new Number( jQuery( 'input.' + timeout2 ).val() );

	var timer  = ( timer - 1 );
	var timer2 = ( timer2 + 1 );

	jQuery( 'input.' + timeout ).val( timer );
	jQuery( 'input.' + timeout2 ).val( timer2 );

});
