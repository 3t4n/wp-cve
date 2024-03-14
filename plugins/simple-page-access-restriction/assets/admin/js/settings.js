;( function ( $, window, undefined ) {

	$( function() {

		$( '#simple-par-settings-tabs-header a' ).on( 'click', function( e ) {
			e.preventDefault();
			if ( $( this ).hasClass( 'simple-par-tab-active' ) ) {
				return;
			}

			$( this )
				.addClass( 'simple-par-tab-active' )
				.siblings( 'a' )
					.removeClass( 'simple-par-tab-active' );

			$( $( this ).attr( 'href' ) )
				.addClass( 'simple-par-tab-active' )
				.siblings( '.simple-par-tab-content' )
					.removeClass( 'simple-par-tab-active' );
		} );

		$( '.simple-par-redirect-type-choices input' ).on( 'change', function() {
			if ( 'page' === $( '.simple-par-redirect-type-choices input:checked' ).attr( 'value' ) ) {
				$( 'tr[data-simple-par-redirect-type="page"]' ).show();
				$( 'tr[data-simple-par-redirect-type="url"]' ).hide();
			} else {
				$( 'tr[data-simple-par-redirect-type="url"]' ).show();
				$( 'tr[data-simple-par-redirect-type="page"]' ).hide();
			}
		} );

		if ( $( '.simple-par-subscription-callout-wrapper' ).length > 0 ) {
			// Show the popup after 5 seconds
			window.setTimeout( function() {
				$( '.simple-par-subscription-callout-wrapper' ).addClass( 'open' );
			}, 5000 );

			// Overtake the form submission request
			$( '.simple-par-subscription-form' ).on( 'submit', function( e ) {
				e.preventDefault();
				
				if ( $( '.simple-par-subscription-callout' ).hasClass( 'ajaxing' ) ) {
					return; // request is already in progress
				}

				$( '.simple-par-subscription-callout' ).addClass( 'ajaxing' );

				$.ajax( {
					url: ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data: {
						action: 'ps_simple_par_handle_subscription_request',
						email: $( '.simple-par-subscription-form input' ).val(),
						from_callout: 1,
					},
					success: function( data ) {
						$( '.simple-par-subscription-callout-main' ).hide();
						$( '.simple-par-subscription-callout-thanks' ).show();
					}
				} )
				.fail( function() {
					$( '.simple-par-subscription-error' ).show();
				} )
				.always( function() {
					$( '.simple-par-subscription-callout' ).removeClass( 'ajaxing' );
				} );
			} );

			function store_popup_shown_status() {
				$.ajax( {
					url: ajaxurl,
					type: 'POST',
					dataType: 'JSON',
					data: {
						action: 'ps_simple_par_subscription_popup_shown'
					},
				} );
				
			}

			$( '.simple-par-subscription-skip' ).on( 'click', function( e ) {
				e.preventDefault();
				$( '.simple-par-subscription-callout-wrapper' ).removeClass( 'open' );
				store_popup_shown_status();
			} );
		}
	} );

}( jQuery, window ) );