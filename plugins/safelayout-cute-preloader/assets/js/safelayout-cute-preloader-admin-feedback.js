jQuery( document ).ready( function( $ ) {
	$( "#deactivate-safelayout-cute-preloader" ).click( function ( e ) {
		e.preventDefault();
		$( "#sl-pl-feedback-modal" ).css( 'display', 'block' );
	});
	$( "#sl-pl-feedback-modal" ).click( function ( e ) {
		if ( e.target === this ) {
			e.preventDefault();
			hideModal();
		}
	});
	$( document ).on( 'keydown', function ( e ) {
		if ( e.keyCode === 27 && $( "#sl-pl-feedback-modal" ).css( 'display' ) != 'none' ) { // ESC
			hideModal();
		}
	});
	function hideModal() {
		$( "#sl-pl-feedback-loader" ).css( 'display', 'none' );
		$( "#sl-pl-feedback-modal" ).css( 'display', 'none' );
	}
	$( "#sl-pl-feedback-submit" ).click( function ( e ) {
		e.preventDefault();
		$( "#sl-pl-feedback-loader" ).css( 'display', 'block' );
		var id = $( "[name='sl-pl-feedback-radio']:checked" ).attr( "id" );
		var type = $( "[name='sl-pl-feedback-radio']:checked" ).val() || '';
		var text = '';
		if ( id != 'sl-pl-feedback-item1' ) {
			text = $( "#" + id + "-text" ).val() || '';
		}
		$.post( slplPreloaderAjax.ajax_url, {
			_ajax_nonce: slplPreloaderAjax.nonce,
			action: "slpl_preloader_feedback",
			type: type,
			text: text
		}, function() {
			$( "#sl-pl-feedback-loader-msg" ).html( $( "#sl-pl-feedback-loader-msg-tr" ).html() );
			setTimeout( function(){$( '#sl-pl-feedback-modal' ).fadeTo( 1000, 0, function () {hideModal()} )}, 500 );
			window.location = $( "#deactivate-safelayout-cute-preloader" ).attr( "href" );
		});
	});
	$( "#sl-pl-feedback-skip" ).click( function ( e ) {
		e.preventDefault();
		$( "#sl-pl-feedback-modal" ).css( 'display', 'none' );
		window.location = $( "#deactivate-safelayout-cute-preloader" ).attr( "href" );
	});
	$( "[name='sl-pl-feedback-radio']" ).change( function() {
		$( "#sl-pl-feedback-item2-text,#sl-pl-feedback-item5-text,#sl-pl-feedback-item6-text" ).css( 'display', 'none' );
		if ( this.id != 'sl-pl-feedback-item1' ) {
			$( "#" + this.id + "-text" ).css( 'display', 'initial' );
		}
	});
	$( "#sl-pl-rate-later,#sl-pl-rate-already" ).click( function ( e ) {
		e.preventDefault();
		$.post( slplPreloaderAjax.ajax_url, {
			_ajax_nonce: slplPreloaderAjax.nonce,
			action: "slpl_preloader_rate_reminder",
			type: this.id
		});
		var el = $( "#sl-pl-rate-reminder" );
		el.fadeTo(100, 0, function () {
			el.slideUp(100, function () {
				el.remove();
			});
		});
	});
	$( "#sl-pl-upgrade-later" ).click( function ( e ) {
		e.preventDefault();
		$.post( slplPreloaderAjax.ajax_url, {
			_ajax_nonce: slplPreloaderAjax.nonce,
			action: "slpl_preloader_upgrade",
		});
		var el = $( "#sl-pl-upgrade-reminder" );
		el.fadeTo(100, 0, function () {
			el.slideUp(100, function () {
				el.remove();
			});
		});
	});
});