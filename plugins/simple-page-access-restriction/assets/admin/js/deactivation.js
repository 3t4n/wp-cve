( function( $ ) {
	
	$( function() {
		
		var pluginSlug = 'simple-page-access-restriction';
		
		// Code to fire when the DOM is ready.
		$( document ).on( 'click', 'tr[data-slug="' + pluginSlug + '"] .deactivate', function( e ) {
			e.preventDefault();
			$( '.simple-par-popup-overlay' ).addClass( 'simple-par-active' );
			$( 'body' ).addClass( 'simple-par-hidden' );
		} );
		
		$( document ).on( 'click', '.simple-par-popup-button-close', function() {
			close_popup();
		} );

		$( document ).on( 'click', '.simple-par-serveypanel,tr[data-slug="' + pluginSlug + '"] .deactivate', function( e ) {
			e.stopPropagation();
		} );
		
		$( document ).click(function() {
			close_popup();
		} );

		$( '.simple-par-reason label' ).on( 'click', function() {
			if ( $( this ).find( 'input[type="radio"]' ).is( ':checked' ) ) {
				$( this )
					.next()
					.next( '.simple-par-reason-input' )
					.show()
					.end()
					.end()
					.parent()
					.siblings()
					.find( '.simple-par-reason-input' )
					.hide();
			}
		} );

		$( 'input[type="radio"][name="simple-par-selected-reason"]' ).on( 'click', function( event ) {
			$( '.simple-par-popup-allow-deactivate' ).removeAttr( 'disabled' );
			$( '.simple-par-popup-skip-feedback' ).removeAttr( 'disabled' );
			$( '.message.error-message' ).hide();
			$( '.simple-par-pro-message' ).hide();
		} );

		$( '.simple-par-reason-pro label' ).on( 'click', function() {
			if ( $( this ).find( 'input[type="radio"]' ).is( ':checked' ) ) {
				$( this ).next( '.simple-par-pro-message' )
					.show()
					.end()
					.end()
					.parent()
					.siblings()
					.find( '.simple-par-reason-input' )
					.hide();
				
				$( this ).next( '.simple-par-pro-message' ).show()
				$( '.simple-par-popup-allow-deactivate' ).attr( 'disabled', 'disabled' );
				$( '.simple-par-popup-skip-feedback' ).attr( 'disabled', 'disabled' );
			}
		} );

		$( document ).on( 'submit', '#simple-par-deactivate-form', function( event ) {
			event.preventDefault();
			
			var _reason = $( 'input[type="radio"][name="simple-par-selected-reason"]:checked' ).val();
			var _reason_details = '';
			var deactivate_nonce = $( '.ps_simple_par_deactivation_nonce' ).val();
			
			if ( _reason == 2 ) {
				_reason_details = $( this ).find( 'input[type="text"][name="better_plugin"]' ).val();
			} else if ( _reason == 7 ) {
				_reason_details = $( this ).find( 'input[type="text"][name="other_reason"]' ).val();
			}

			if ( ( _reason == 7 || _reason == 2 ) && _reason_details == '') {
				$( '.message.error-message' ).show();
				return;
			}

			$.ajax( {
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'ps_simple_par_deactivation',
					reason: _reason,
					reason_details: _reason_details,
					ps_simple_par_deactivation_nonce: deactivate_nonce
				},
				beforeSend: function() {
					$( '.simple-par-spinner' ).show();
					$( '.simple-par-popup-allow-deactivate' ).attr( 'disabled', 'disabled' );
				}
			} )
			.done(function() {
				$( '.simple-par-spinner' ).hide();
				$( '.simple-par-popup-allow-deactivate' ).removeAttr( 'disabled' );
				window.location.href = $( 'tr[data-slug="' + pluginSlug + '"] .deactivate a' ).attr( 'href' );
			} );
		} );

		$( '.simple-par-popup-skip-feedback' ).on( 'click', function(e) {
			// e.preventDefault();
			window.location.href = $( 'tr[data-slug="' + pluginSlug + '"] .deactivate a' ).attr( 'href' );
		} );

		function close_popup() {
			$( '.simple-par-popup-overlay' ).removeClass( 'simple-par-active' );
			$( '#simple-par-deactivate-form' ).trigger( "reset" );
			$( '.simple-par-popup-allow-deactivate' ).attr( 'disabled', 'disabled' );
			$( '.simple-par-reason-input' ).hide();
			$( 'body' ).removeClass( 'simple-par-hidden' );
			$( '.message.error-message' ).hide();
			$( '.simple-par-pro-message' ).hide();
		}
	} );

} )( jQuery );