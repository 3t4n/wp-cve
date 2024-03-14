jQuery( document ).ready( function( $ ) {

	$( document ).on( 'change', '.ypop-popup-toggle-enabled input', function () {
		var enabled   = $( this ).val() === 'yes' ? 'enable' : 'disable',
			container = $( this ).closest( '.ypop-popup-toggle-enabled' ),
			popupID   = container.data( 'id' ),
			security  = container.data( 'security' );

		$.ajax( {
					type: 'POST',
					data: {
						action  : 'ypop_change_status',
						post_id : popupID,
						status  : enabled,
						security: security
					},
					url : ajaxurl
				} );
	} );
} );