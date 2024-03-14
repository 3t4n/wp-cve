
/* global dotdigital_form_data */
( function( $ ) {
	'use strict';
	$(
		function() {
			$( 'form.dotdigital-signup-form' ).on(
				'submit',
				function( event ) {
					const form = $( this );
					const is_ajax = form.find( "input[name='is_ajax']" );
					if ( ! is_ajax[ 0 ].value ) {
						return;
					}
					const email = form.find( "input[name='email']" );
					let responseMessage = '';
					event.preventDefault();
					$.ajax(
						{
							beforeSend( xhr ) {
								xhr.setRequestHeader( 'X-WP-Nonce', dotdigital_form_data.nonce );
							},
							url: dotdigital_form_data.ajax_url + 'dotdigital/v1/signup-widget',
							type: 'POST',
							dataType: 'json',
							data: {
								redirection: form.find( "input[name='redirection']" )[ 0 ].value,
								is_ajax: true,
								email: $( email ).val(),
								lists: form.find( "input[name='lists[]']" ).map(
									function() {
										if ( $( this ).is( ':checked' ) || $( this ).is( ':hidden' ) ) {
											return $( this ).val();
										}
										return undefined;
									} ).get(),
								datafields: form.find( '.datafield' ).map(
									function( i, element ) {
										if ( $( element ).parent().attr( 'class' ) === 'ddg-form-group' ) {
											return { key: $( element ).data( 'datafield-name' ), value: element.value, required: $( element ).data( 'required' ) };
										}
										if ( $( element )[ 0 ].checked ) {
											return { key: $( element ).data( 'datafield-name' ), value: element.value, required: $( element ).data( 'required' ) };
										}
										return undefined;
									} ).get(),
							}, success( response ) {
								if ( response.success ) {
									$( form )[ 0 ].reset();
									if ( response.redirection ) {
										window.location.href = response.redirection;
									}
									responseMessage = $( '<p/>', {
										class: 'dd-wordpress-success-msg',
										html: response.message,
									} );
								} else {
									responseMessage = $( '<p/>', {
										class: 'dd-wordpress-error-msg',
										html: response.message,
									} );
								}
								form.next( '.form_messages' ).empty().append( responseMessage ).fadeIn( 'fast' ).delay( 9000 ).fadeOut( 'slow' );
							}, error() {
								responseMessage = $( '<p/>', {
									class: 'dd-wordpress-error-msg',
									html: dotdigital_form_data.generic_failure_message,
								} );
								form.next( '.form_messages' ).empty().append( responseMessage );
							},
						}
					);
				}
			);
		}
	);
}( jQuery ) );
