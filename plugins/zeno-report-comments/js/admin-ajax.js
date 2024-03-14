
/*
 * Admin JavaScript for Zeno Report Comments.
 */


jQuery(document).ready(function($) {
	jQuery( 'span.zeno-comments-report-moderate a' ).on( 'click', function( a_element ) {

		var comment_id = jQuery( this ).attr('data-zeno-comment-id');

		jQuery.post(
			zenocommentsajax.ajaxurl, {
				comment_id: comment_id,
				sc_nonce:   zenocommentsajax.nonce,
				action:     'zeno_report_comments_moderate_comment',
				xhrFields:  {
					withCredentials: true
				}
			},
			function( response ) {
				var span_id = 'zeno-comments-result-' + comment_id;
				jQuery( 'span#' + span_id ).html( response );
			}
		);

		return false;
	});
});
