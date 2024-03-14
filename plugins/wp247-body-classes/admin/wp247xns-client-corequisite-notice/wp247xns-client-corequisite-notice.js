/*
 * wp247 Extension Notification System Client Javascript
*/
jQuery(document).ready( function($)
{
	// Handle dismiss
	$(window).load( function()
	{
		$( '.wp247xns-client-corequisite-notice button.notice-dismiss' ).click( function () {
			var notice = $(this).closest( '.wp247xns-client-corequisite-notice' );
			var xid = $(notice).attr( 'data-xid' );
			if ( undefined != xid )
			{
				$.ajax( {
					type: 'POST',
					url: ajaxurl,
					data: {
						action: 'wp247xns_client_corequisite_notice_dismiss',
						security: wp247xns_client_corequisite_notice_dismiss_ajax_nonce,
						xid: xid
					},
					dataType: 'html',
					success: function ( response ) {
// alert('Success - resoponse: '+JSON.stringify(response));
					},
					error: function( response ) {
// alert('Error - resoponse: '+JSON.stringify(response));
					},
					async: true
				});
			}
			return false;
		});
	});
} );