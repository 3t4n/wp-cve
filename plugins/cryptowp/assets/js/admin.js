/**
 * This file contains the main admin scripts that power
 * the various actions in CryptoWP.
 */

( function( window, document, $ ) {

	window.CryptoWP = {};

	/**
	 * Gather all CryptoWP admin scripts.
	 */

	CryptoWP.init = function() {
		CryptoWP.actions();
		CryptoWP.process();
		CryptoWP.sortable();
		CryptoWP.remove();
		CryptoWP.media();
	}

	/**
	 * Toggle settings panels.
	 */

	CryptoWP.actions = function() {
		$( '.cryptowp-action' ).on( 'click', function() {
			var action = $( this ).data( 'cryptowp-action' ),
				actionPanel = '#cryptowp_panel_' + action,
				activeClass = 'cryptowp-active';
			$( '.cryptowp-action' ).not( this ).removeClass( activeClass );
			$( this ).toggleClass( activeClass );
			$( '.cryptowp-panel' ).not( actionPanel ).removeClass( activeClass );
			$( actionPanel ).toggleClass( activeClass );
			if ( action == 'import' )
				$( '#cryptowp_import' ).focus();
		});
	}

	/**
	 * Run AJAX processing actions.
	 */

	CryptoWP.process = function() {
		$( document ).on( 'click', '.cryptowp-process', function( e ) {
			e.preventDefault();
			var process = $( this ).attr( 'data-cryptowp-process' ),
				coinCount = $( this ).attr( 'data-cryptowp-count' );
			if ( process == 'import' )
				$( '.cryptowp-importer .dashicons' ).addClass( 'cryptowp-spinner' );
			if ( process == 'refresh' )
				$( '#cryptowp_refresh .dashicons' ).addClass( 'cryptowp-spinner' );
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'process',
					form: $( '#cryptowp' ).serialize(),
					process: process,
					coins_import: $( '#cryptowp_import' ).val(),
					coin_count: coinCount
				},
				success: function( response ) {
					if ( process == 'import' ) {
						$( '.cryptowp-panel, .cryptowp-button' ).removeClass( 'cryptowp-active' );
						$( '#cryptowp_import' ).val( '' );
						$( '.cryptowp-importer .dashicons' ).removeClass( 'cryptowp-spinner' );
					}
					if ( process == 'refresh' )
						$( '#cryptowp_refresh .dashicons' ).removeClass( 'cryptowp-spinner' );
					$( '#cryptowp_coins' ).replaceWith( response );
					CryptoWP.sortable();
					CryptoWP.remove();
				}
			});

		});
	}

	/**
	 * Initialize Sortable script.
	 */

	CryptoWP.sortable = function() {
		new Sortable( document.getElementById( 'cryptowp_coins' ), {
			handle: '.cryptowp-coin-drag'
		});
	}

	/**
	 * Remove repeater item on click.
	 */

	CryptoWP.remove = function() {
		$( '.cryptowp-coin-remove' ).on( 'click', function( e ) {
			e.preventDefault();
			var current = $( this ).parent( 'div' );
			current.fadeOut( 'fast', function() {
				current.remove();
			});
		});
	}

	/**
	 * Load custom instance of WordPress Media Library.
	 */

	CryptoWP.media = function() {
		var CryptoWPMediaFrame;
		$( document.body ).on( 'click.CryptoWPMediaUploaderOpenMediaManager', '.cryptowp-coin-icon-image', function( e ) {
			e.preventDefault();
			$div = $( e.target ).closest( '.cryptowp-coin-icon' );
			if ( CryptoWPMediaFrame ) {
				CryptoWPMediaFrame.open();
				return;
			}
			CryptoWPMediaFrame = wp.media.frames.CryptoWPMediaFrame = wp.media({
				frame:    'select',
				multiple: false,
				title:    'Select Image',
				library:  { type: 'image' },
				button:   { text: 'Use Image' }
			});
			CryptoWPMediaFrame.on( 'select', function() {
				selection = CryptoWPMediaFrame.state().get('selection');
				if ( ! selection ) return;
				selection.each( function( attachment ) {
					$div.find( '.cryptowp-coin-icon-url' ).val( attachment.attributes.sizes.full.url );
					$div.find( '.cryptowp-coin-icon-image' ).attr( 'src', attachment.attributes.sizes.full.url );
				});
			});
			CryptoWPMediaFrame.open();
		});
	}

	/**
	 * Initialize scripts.
	 */

	CryptoWP.init();

})( window, document, jQuery );