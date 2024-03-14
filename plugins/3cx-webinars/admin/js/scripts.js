( function( $ ) {

	'use strict';

	if ( typeof wp3cxw === 'undefined' || wp3cxw === null ) {
		return;
	}

	$( function() {
		
		$(document).ready(function() {
			$("#wp3cxw-config-country").msDropdown();
		});

		$( '#webinar-form-editor' ).tabs( {
			active: wp3cxw.activeTab,
			activate: function( event, ui ) {
				$( '#active-tab' ).val( ui.newTab.index() );
			}
		} );

		$( '#webinar-form-editor-tabs' ).focusin( function( event ) {
			$( '#webinar-form-editor .keyboard-interaction' ).css(
				'visibility', 'visible' );
		} ).focusout( function( event ) {
			$( '#webinar-form-editor .keyboard-interaction' ).css(
				'visibility', 'hidden' );
		} );

		if ( '' === $( '#title' ).val() ) {
			$( '#title' ).focus();
		}

		wp3cxw.titleHint();

		$( '.webinar-form-editor-box-mail span.mailtag' ).click( function( event ) {
			var range = document.createRange();
			range.selectNodeContents( this );
			window.getSelection().addRange( range );
		} );

		wp3cxw.updateConfigErrors();

		$( '[data-config-field]' ).change( function() {
			var postId = $( '#post_ID' ).val();

			if ( ! postId || -1 == postId ) {
				return;
			}

			var data = [];

			$( this ).closest( 'form' ).find( '[data-config-field]' ).each( function() {
				data.push( {
					'name': $( this ).attr( 'name' ).replace( /^wp3cxw-/, '' ).replace( /-/g, '_' ),
					'value': $( this ).val()
				} );
			} );

			data.push( { 'name': 'context', 'value': 'dry-run' } );

			$.ajax( {
				method: 'POST',
				url: wp3cxw.apiSettings.getRoute( '/webinar-forms/' + postId ),
				beforeSend: function( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', wp3cxw.apiSettings.nonce );
				},
				data: data
			} ).done( function( response ) {
				wp3cxw.configValidator.errors = response.config_errors;
				wp3cxw.updateConfigErrors();
			} );
		} );

		$( window ).on( 'beforeunload', function( event ) {
			var changed = false;

			$( '#wp3cxw-admin-form-element :input[type!="hidden"]' ).each( function() {
				if ( $( this ).is( ':checkbox, :radio' ) ) {
					if ( this.defaultChecked != $( this ).is( ':checked' ) ) {
						changed = true;
					}
				} else if ( $( this ).is( 'select' ) ) {
					$( this ).find( 'option' ).each( function() {
						if ( this.defaultSelected != $( this ).is( ':selected' ) ) {
							changed = true;
						}
					} );
				} else {
					if ( this.defaultValue != $( this ).val() ) {
						changed = true;
					}
				}
			} );

			if ( changed ) {
				event.returnValue = wp3cxw.saveAlert;
				return wp3cxw.saveAlert;
			}
		} );

		$( '#wp3cxw-admin-form-element' ).submit( function() {
			if ( 'copy' != this.action.value ) {
				$( window ).off( 'beforeunload' );
			}

			if ( 'save' == this.action.value ) {
				$( '#publishing-action .spinner' ).addClass( 'is-active' );
			}
		} );
	} );

	wp3cxw.updateConfigErrors = function() {
		var errors = wp3cxw.configValidator.errors;
		var errorCount = { total: 0 };

		$( '[data-config-field]' ).each( function() {
			$( this ).removeAttr( 'aria-invalid' );
			$( this ).next( 'ul.config-error' ).remove();

			var section = $( this ).attr( 'data-config-field' );

			if ( errors[ section ] ) {
				var $list = $( '<ul></ul>' ).attr( {
					'role': 'alert',
					'class': 'config-error'
				} );

				$.each( errors[ section ], function( i, val ) {
					var $li = $( '<li></li>' ).append(
						$( '<span class="dashicons dashicons-warning" aria-hidden="true"></span>' )
					).append(
						$( '<span class="screen-reader-text"></span>' ).text( wp3cxw.configValidator.iconAlt )
					).append( ' ' );

					if ( val.link ) {
						$li.append(
							$( '<a></a>' ).attr( 'href', val.link ).text( val.message )
						);
					} else {
						$li.text( val.message );
					}

					$li.appendTo( $list );

					var tab = section
						.replace( /^mail_\d+\./, 'mail.' ).replace( /\..*$/, '' );

					if ( ! errorCount[ tab ] ) {
						errorCount[ tab ] = 0;
					}

					errorCount[ tab ] += 1;

					errorCount.total += 1;
				} );

				$( this ).after( $list ).attr( { 'aria-invalid': 'true' } );
			}
		} );

		$( '#webinar-form-editor-tabs > li' ).each( function() {
			var $item = $( this );
			$item.find( 'span.dashicons' ).remove();
			var tab = $item.attr( 'id' ).replace( /-panel-tab$/, '' );

			$.each( errors, function( key, val ) {
				key = key.replace( /^mail_\d+\./, 'mail.' );

				if ( key.replace( /\..*$/, '' ) == tab.replace( '-', '_' ) ) {
					var $mark = $( '<span class="dashicons dashicons-warning" aria-hidden="true"></span>' );
					$item.find( 'a.ui-tabs-anchor' ).first().append( $mark );
					return false;
				}
			} );

			var $tabPanelError = $( '#' + tab + '-panel > div.config-error:first' );
			$tabPanelError.empty();

			if ( errorCount[ tab.replace( '-', '_' ) ] ) {
				$tabPanelError
					.append( '<span class="dashicons dashicons-warning" aria-hidden="true"></span> ' );

				if ( 1 < errorCount[ tab.replace( '-', '_' ) ] ) {
					var manyErrorsInTab = wp3cxw.configValidator.manyErrorsInTab
						.replace( '%d', errorCount[ tab.replace( '-', '_' ) ] );
					$tabPanelError.append( manyErrorsInTab );
				} else {
					$tabPanelError.append( wp3cxw.configValidator.oneErrorInTab );
				}
			}
		} );

		$( '#misc-publishing-actions .misc-pub-section.config-error' ).remove();

		if ( errorCount.total ) {
			var $warning = $( '<div></div>' )
				.addClass( 'misc-pub-section config-error' )
				.append( '<span class="dashicons dashicons-warning" aria-hidden="true"></span> ' );

			if ( 1 < errorCount.total ) {
				$warning.append(
					wp3cxw.configValidator.manyErrors.replace( '%d', errorCount.total )
				);
			} else {
				$warning.append( wp3cxw.configValidator.oneError );
			}

			$warning.append( '<br />' ).append(
				$( '<a></a>' )
					.attr( 'href', wp3cxw.configValidator.docUrl )
					.text( wp3cxw.configValidator.howToCorrect )
			);

			$( '#misc-publishing-actions' ).append( $warning );
		}
	};

	/**
	 * Copied from wptitlehint() in wp-admin/js/post.js
	 */
	wp3cxw.titleHint = function() {
		var $title = $( '#title' );
		var $titleprompt = $( '#title-prompt-text' );

		if ( '' === $title.val() ) {
			$titleprompt.removeClass( 'screen-reader-text' );
		}

		$titleprompt.click( function() {
			$( this ).addClass( 'screen-reader-text' );
			$title.focus();
		} );

		$title.blur( function() {
			if ( '' === $(this).val() ) {
				$titleprompt.removeClass( 'screen-reader-text' );
			}
		} ).focus( function() {
			$titleprompt.addClass( 'screen-reader-text' );
		} ).keydown( function( e ) {
			$titleprompt.addClass( 'screen-reader-text' );
			$( this ).unbind( e );
		} );
	};

	wp3cxw.apiSettings.getRoute = function( path ) {
		var url = wp3cxw.apiSettings.root;

		url = url.replace(
			wp3cxw.apiSettings.namespace,
			wp3cxw.apiSettings.namespace + path );

		return url;
	};

} )( jQuery );
