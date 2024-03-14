(function ($) {
	const CTCCore = {

		/**
		 * Init
		 */
		init: function () {
			this._bind();
		},

		/**
		 * Binds events
		 */
		_bind: function () {
			$( document ).on( 'click', '.ctc-block-copy', this.doCopy );
		},

		/**
		 * Do Copy to Clipboard
		 */
		doCopy: function (event) {
			event.preventDefault();

			let btn        = $( this ),
				btnText    = btn.find( '.ctc-button-text' ),
				oldText    = btnText.text(),
				copiedText = btn.attr( 'data-copied' ) || 'Copied',
				copyAsRaw  = btn.attr( 'copy-as-raw' ) || '',
				block      = btn.parents( '.ctc-block' ),
				textarea    = block.find( '.ctc-copy-content' ),
				content    = textarea.val(),
				selectionTarget = textarea.attr( 'selection-target' ) || ''

			// Copy as selection.
			if ( selectionTarget ) {
				const source = $( selectionTarget )
				if ( ! source.length ) {
					return
				}

				CTC.copySelection( source )
			} else {
				if ( ! copyAsRaw ) {
					// Convert the <br/> tags into new line.
					content = content.replace( /<br\s*[\/]?>/gi, "\n" );

					// Convert the <div> tags into new line.
					content = content.replace( /<div\s*[\/]?>/gi, "\n" );

					// Convert the <p> tags into new line.
					content = content.replace( /<p\s*[\/]?>/gi, "\n\n" );

					// Convert the <li> tags into new line.
					content = content.replace( /<li\s*[\/]?>/gi, "\n" );

					// Remove all tags.
					content = content.replace( /(<([^>]+)>)/ig, '' );

					// Remove white spaces.
					content = content.replace( new RegExp( "/^\s+$/" ), "" );
				}

				// Remove first and last new line.
				content = $.trim( content );

				// Support for IOS devices too.
				CTC.copy( content );
			}

			if ( btn.hasClass( 'ctc-block-copy-icon' ) ) {
				// Copied!
				btn.addClass( 'copied' );
				setTimeout(
					function () {
						btn.removeClass( 'copied' )
					},
					1000
				);
			} else {
				// Copied!
				btnText.text( copiedText );
				block.addClass( 'copied' )
				setTimeout(
					function () {
						btnText.text( oldText )
						block.removeClass( 'copied' )
					},
					1000
				);
			}
		}
	};

	/**
	 * Initialization
	 */
	$(
		function () {
			CTCCore.init();
		}
	);

})( jQuery );
