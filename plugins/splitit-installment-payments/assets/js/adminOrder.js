jQuery( document ).ready(
	function(){
		jQuery( 'body' ).prepend( '<div class="loading" style="display: none"></div>' );

		jQuery( 'html' )
		.on(
			'click',
			'#start_installment_button',
			function (e) {
				e.preventDefault();
				jQuery( '.loading' ).show();
				jQuery.ajax(
					{
						type: 'POST',
						url: WC_SPLITIT.ajaxurl_admin,
						data: {
							action: 'start_installment_method',
							order_id: jQuery( this ).data( 'order_id' ),
						},
						success: function (response) {
							jQuery( '.loading' ).hide();
							if (response.data) {
								alert( response.data );
							} else {
								alert( response );
							}

							location.reload();
						},
						error: function (error) {
							jQuery( '.loading' ).hide();
							alert( error );
						}
					}
				);
			}
		);
	}
);
