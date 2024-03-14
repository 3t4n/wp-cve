(function( $ ) {
	'use strict';

	$(function() {


		/* For Input Switch */
		$( '.catchids-input-switch' ).on( 'click', function() {
			var nonce = $(this).prev('input').val();
			var loader = $( this ).parent().next();
			
			loader.show();
			
			var main_control = $( this );
			var data = {
				'action'      : 'catchwebtools_catchids_switch',
				'value'       : this.checked,
				'option_name' : main_control.attr( 'rel' ),
				'catch_ids_nonce': nonce
			};

			$.post( ajaxurl, data, function( response ) {
				response = $.trim( response );

				if ( '1' == response ) {
					main_control.parent().parent().addClass( 'active' );
					main_control.parent().parent().removeClass( 'inactive' );
					if( 'status' == main_control.attr( 'rel' ) ) {
						main_control.parent().siblings('.module-title').children('.active').show();
						main_control.parent().siblings('.module-title').children('.inactive').hide();
						$('.catch-ids-options').slideDown();
					}
				} else if( '0' == response ) {
					main_control.parent().parent().addClass( 'inactive' );
					main_control.parent().parent().removeClass( 'active' );
					if( 'status' == main_control.attr( 'rel' ) ) {
						main_control.parent().siblings('.module-title').children('.active').hide();
						main_control.parent().siblings('.module-title').children('.inactive').show();
						$('.catch-ids-options').slideUp();
					}
				} else {
					alert( response );
				}
				
				loader.hide();
			});
		});
		/* For Input Switch End */
	});



})( jQuery );
