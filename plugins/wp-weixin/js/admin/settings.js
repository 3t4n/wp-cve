/* global WpWeixin */
jQuery( document ).ready( function( $ ) {
	$( '.toplevel_page_wp-weixin .stuffbox form h2' ).each( function( idx, value ) {
		var h2      = $( value ),
			h2Class = $( h2 ).next( '.section-class-holder' ).data( 'section_class' );

		h2.addClass( h2Class );
	} );
		
	var sections = {
			responder: {
				handle: $( '.wp_weixin-responder-section.wp_weixin-responder-field input' ),
				content: $( '.wp_weixin-responder-section:not( .wp_weixin-responder-field )' )
			},
			proxy: {
				handle: $( '.wp_weixin-proxy-section.wp_weixin-proxy-field input' ),
				content: $( '.wp_weixin-proxy-section:not( .wp_weixin-proxy-field )' )
			}, 
			auth: {
				handle: $( '.wp_weixin-enable_auth-field input' ),
				content: $( '.requires-auth' )
			}
		},
		forceResponderHandles = [
			$( '.wp_weixin-follow_welcome-field input' )
		];

	$.each( sections, function( idx, section ) {
		
		if ( section.handle.prop( 'checked' ) ) {
			section.content.show();
		} else {
			section.content.hide();
		}

		section.handle.on( 'change', function() {
			var handle = $( this );

			if ( handle.prop( 'checked' ) ) {
				section.content.show();
			} else {
				section.content.hide();
			}
		} );
	} );

	$.each( forceResponderHandles, function( idx, handle ) {
		
		if ( handle.prop( 'checked' ) && ! sections.responder.handle.prop( 'checked' ) ) {
			sections.responder.handle.prop( 'checked', true );
			sections.responder.handle.trigger( 'change' );
		}

		handle.on( 'change', forceFollow );
	} );

	sections.responder.handle.on( 'change', forceFollow );

	function forceFollow() {

		$.each( forceResponderHandles, function( idx, handle ) {

			if ( handle.prop( 'checked' ) ) {
				sections.responder.handle.prop( 'checked', true );
				sections.responder.content.show();
			}
		} );
	}

	if ( $( '.wp_weixin-force_auth-field input' ).prop( 'checked' ) ) {
		$( '.wp_weixin-enable_auth-field input' ).prop( 'checked', true );
	}

	$( '.wp_weixin-enable_auth-field input, .wp_weixin-force_auth-field input' ).on( 'change', function() {

		if ( $( '.wp_weixin-force_auth-field input' ).prop( 'checked' ) ) {
			$( '.wp_weixin-enable_auth-field input' ).prop( 'checked', true );
		}
	} );

	$( 'input[type="password"].toggle' ).on( 'focus', function() {
		$( this ).attr( 'type', 'text' );
	} );

	$( 'input[type="password"].toggle' ).on( 'blur', function() {
		$( this ).attr( 'type', 'password' );
	} );

	$( '.qr-custom-button' ).on( 'click', function( e ) {
		e.preventDefault();

		var button = $( this ),
			img    = $( '#' + button.data( 'img' ) ),
			url    = $( '#qr_url' ).val(),
			data   = {
				url : url
			};

		data.action = 'wp_weixin_get_settings_qr';

		$.ajax( {
			url: WpWeixin.ajax_url,
			type: 'POST',
			data: data
		} ).done( function( response ) {

			if ( response.success ) {
				img.attr( 'src', img.data( 'base_url' ) + response.data );
				img.css( 'visibility', 'visible' );
				img.parent().children( 'span' ).hide();
			} else {
				img.css( 'visibility', 'hidden' );
				img.parent().children( 'span' ).show();
			}
		} ).fail( function( qXHR, textStatus ) {
			WpWeixin.debug && window.console.log( textStatus );
		} );
	} );

} );