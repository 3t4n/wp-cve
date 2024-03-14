(function( $ ) {
	'use strict';
	/**
	 * Admin-facing JavaScript source
	 */

	/**
	 * Set the user's clipboard contents.
	 *
	 * @param string data: Text to copy to clipboard.
	 * @param object $el: jQuery element to trigger copy events on. (Default: document)
	 */

	function coSetClipboard( data, $el ) {
		if ( 'undefined' === typeof $el ) {
			$el = jQuery( document );
		}
		var $temp_input = jQuery( '<textarea style="opacity:0">' );
		jQuery( 'body' ).append( $temp_input );
		$temp_input.val( data ).select();

		$el.trigger( 'beforecopy' );
		try {
			document.execCommand( 'copy' );
			$el.trigger( 'aftercopy' );
		} catch ( err ) {
			console.log( err );
			$el.trigger( 'aftercopyfailure' );
		}

		$temp_input.remove();
	}

	/**
	 * Clear the user's clipboard.
	 */
	function coClearClipboard() {
		coSetClipboard( '' );
	}
	
	var coStatus = {
		// Initialize events.
		init: function() {
			$( document )
				.on( "click", "#copy-action-btn", this.copyShortcode )
				.on( 'click', 'a.help_tip, a.card-oracle-help-tip', this.preventTipTipClick )
				.on( 'click', 'a.debug-report', this.generateReport )
				.on( 'click', '#copy-for-support', this.copyReport )
				.on( 'click', '#demo_data_button', this.disableButton)
				.on( 'aftercopy', '#copy-for-support', this.copySuccess )
				.on( 'aftercopyfailure', '#copy-for-support', this.copyFail )
				.on( 'change', '#_co_question_layout', this.updateLayout)
				.on( 'change', '#error_rows', this.submitErrorRows );
		},

		// Copy Shortcodes to user clipboard.
		copyShortcode: function() {
			let $tempElement = $( "<input>" );
			$( "body" ).append( $tempElement );
			let copyText = this.value;
			$tempElement.val( copyText ).select();
			document.execCommand( "Copy" );
			$tempElement.remove();
		},
	
		/**
		 * Disable the the Demo Data button to prevent mulitple clicks.
		 */
		disableButton: function() {
			log.console( 'Disable Button' );
			setTimeout( function() {
				$( this ).prop( "disabled", true );
			}, 500);
		},

		/**
		 * Prevent anchor behavior when click on TipTip.
		 *
		 * @return {Bool}
		 */
		preventTipTipClick: function() {
			return false;
		},
		
		/**
		 * Update the Layout demo on the admin page to use the new display before having to save.
		 * 
		 */
		updateLayout: function() {
			$( '#card-oracle-display-layout' ).attr( 'class', this.value );
		},

		/**
		 * Generate system status report.
		 *
		 * @return {Bool}
		 */
		generateReport: function() {
			var report = '';

			$( '.card_oracle_status_table thead, .card_oracle_status_table tbody' ).each( function() {
				if ( $( this ).is( 'thead' ) ) {
					var label = $( this ).find( 'th:eq(0)' ).data( 'export-label' ) || $( this ).text();
					report = report + '\n### ' + $.trim( label ) + ' ###\n\n';
				} else {
					$( 'tr', $( this ) ).each( function() {
						var label       = $( this ).find( 'td:eq(0)' ).data( 'export-label' ) || $( this ).find( 'td:eq(0)' ).text();
						var the_name    = $.trim( label ).replace( /(<([^>]+)>)/ig, '' ); // Remove HTML.

						// Find value
						var $value_html = $( this ).find( 'td:eq(2)' ).clone();
						$value_html.find( '.private' ).remove();
						$value_html.find( '.dashicons-yes' ).replaceWith( '&#10004;' );
						$value_html.find( '.dashicons-no-alt, .dashicons-warning' ).replaceWith( '&#10060;' );

						// Format value
						var the_value   = $.trim( $value_html.text() );
						var value_array = the_value.split( ', ' );

						if ( value_array.length > 1 ) {
							// If value have a list of plugins ','.
							// Split to add new line.
							var temp_line ='';
							$.each( value_array, function( key, line ) {
								temp_line = temp_line + line + '\n';
							});

							the_value = temp_line;
						}

						report = report + '' + the_name + ': ' + the_value + '\n';
					});
				}
			});

			try {
				$( '#debug-report' ).slideDown();
				$( '#debug-report' ).find( 'textarea' ).val( '`' + report + '`' ).focus().select();
				$( this ).fadeOut();
				return false;
			} catch ( e ) {
				/* jshint devel: true */
				console.log( e );
			}

			return false;
		},

		/**
		 * Copy for report.
		 *
		 * @param {Object} evt Copy event.
		 */
		copyReport: function( evt ) {
			coClearClipboard();
			coSetClipboard( $( '#debug-report' ).find( 'textarea' ).val(), $( this ) );
			evt.preventDefault();
		},

		/**
		 * Display a "Copied!" tip when success copying
		 */
		copySuccess: function() {
			$( '.copy-success' ).removeClass( 'hidden' );
			$( '.copy-success' ).addClass( 'card-oracle-copy-visible' );
			$( '#debug-report' ).find( 'textarea' ).focus().select();
		},

		/**
		 * Displays the copy error message when failure copying.
		 */
		copyFail: function() {
			$( '.copy-error' ).removeClass( 'hidden' );
			$( '#debug-report' ).find( 'textarea' ).focus().select();
		},

		submitErrorRows: function() {
			$( "#error_rows_form" ).submit();
		}
	};
	
	coStatus.init();

})( jQuery );
