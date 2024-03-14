/*
* My WP Translate
* https://mythemeshop.com/plugins/my-wp-translate/
*/
/* global confirm,mtswpt,alert */
(function( $ ) {
	'use strict';

	var fnDelay = (function() {
		var timer = 0;
		return function( callback, ms ) {
			clearTimeout( timer );
			timer = setTimeout( callback, ms );
		};
	})();

	$(function() {

		function getUrlParameter( sParam ) {

			var sPageURL = decodeURIComponent( window.location.search.substring( 1 ) ),
				sURLVariables = sPageURL.split( '&' ),
				sParameterName,
				i;

			for ( i = 0; i < sURLVariables.length; i++ ) {

				sParameterName = sURLVariables[i].split( '=' );

				if ( sParameterName[0] === sParam ) {
					return undefined === sParameterName[1] ? true : sParameterName[1];
				}
			}
		}

		function removeURLParameter( url, parameter ) {

			// Prefer to use l.search if you have a location/link object.
			var urlparts = url.split( '?' );
			if ( urlparts.length >= 2 ) {

				var prefix = encodeURIComponent( parameter ) + '=';
				var pars = urlparts[1].split( /[&;]/g );

				// Reverse iteration as may be destructive.
				for ( var i = pars.length; i-- > 0; ) {
					// Idiom for string.startsWith
					if ( pars[i].lastIndexOf( prefix, 0 ) !== -1 ) {
						pars.splice( i, 1 );
					}
				}

				url = urlparts[0] + '?' + pars.join( '&' );
				return url;

			} else {

				return url;
			}
		}

		// Theme translation
		var $translationsContainer = $( '.translate-strings' );
		var $translationsToggle = $( '#nhp-opts-translate' );
		var translationIsLoaded = false;

		function mtswptLoadTranslations( page, search ) {

			page = page || 1;
			var tab = getUrlParameter( 'tab' ),
				perPage = $( '#translate_strings_per_page' ).val();

			$translationsContainer.empty();

			$.ajax({
				url: ajaxurl,
				method: 'post',
				data: {
					'action':   'mtswpt_translation_panel',
					'page':     page,
					'search':   search,
					'tab':      tab,
					'per_page': perPage,
					'security': mtswpt.nonce
				},
				beforeSend: function() {

					$translationsContainer.addClass( 'loading' );
				},
				success: function( data ) {

					if ( 'no_file' === data ) {

						$( '#mtswpt_translate_wrap' ).addClass( 'hidden' );
						$( '#mtswpt_path_wrap' ).removeClass( 'hidden' );
						$translationsContainer.removeClass( 'loading' );
						$( '#translate_search_wrapper' ).hide();
						$( '#translate_strings_per_page_wrapper' ).hide();

					} else {

						$( '#mtswpt_translate_wrap' ).removeClass( 'hidden' );
						translationIsLoaded = true;
						$( '#translate_search_wrapper' ).show();
						$( '#translate_strings_per_page_wrapper' ).show();
						$translationsContainer.removeClass( 'loading' ).html( data );
					}
				}
			});
		}

		function mtswptSaveTranslation( originalString, newString, elem ) {

			var wrapper = elem.closest( '.translate-string-wrapper' ),
				tab = getUrlParameter( 'tab' ),
				loadingTimeout = void 0,
				loading = wrapper.find('.mts_translate_loading');

			$.ajax({
				url: ajaxurl,
				method: 'post',
				data: {
					'action':   'mtswpt_save_translation',
					'id':       originalString,
					'val':      newString,
					'tab':      tab,
					'security': mtswpt.nonce
				},
				beforeSend: function() {

					elem.prop( 'disabled', true );
					wrapper.addClass( 'loading' );
					loading.removeClass('active')
					clearTimeout(loadingTimeout)
				},
				success: function( data ) {

					elem.prop( 'disabled', false );
					wrapper.removeClass( 'loading' );

					if ( '1' === data ) {

						wrapper.addClass( 'success' );
						setTimeout(function() {
							wrapper.addClass( 'animate' ).removeClass( 'success' );
						}, 10 );

						loading.addClass('active')
						loadingTimeout = setTimeout(function() {
							wrapper.removeClass( 'animate' );
							loading.removeClass('active');
						}, 3000 );


						// Update number and %
						var $info = $( '.translation_info' ),
							total = parseInt( $info.find( '.total' ).text() ),
							translated = parseInt( $info.find( '.translated' ).text() );

						if ( '' === newString && elem.data( 'translated' ) ) {

							translated--;
							$info.find( '.translated' ).text( translated );

						} else if ( '' !== newString && ! elem.data( 'translated' ) ) {

							translated++;
							$info.find( '.translated' ).text( translated );
						}

						var percent = translated / total * 100;
						$info.find( '.percent' ).text( '(' + percent.toFixed( 2 ) + '%)' );

						$( document ).trigger( 'mtswptStringUpdated' );

					} else {

						wrapper.addClass( 'fail' );

						setTimeout(function() {
							wrapper.addClass( 'animate' ).removeClass( 'fail' );
						}, 10 );

						setTimeout(function() {
							wrapper.removeClass( 'animate' );
						}, 3000 );
					}
				}
			});
		}

		// Load on document.ready if needed
		if ( ! $translationsToggle.is( ':checked' ) ) {
			$( '#nhp-opts-reset-translations' ).prop( 'disabled', true );
		}

		if ( $translationsToggle.is( ':checked' ) ) {
			mtswptLoadTranslations();
		}

		$translationsToggle.change(function() {

			var tab = getUrlParameter( 'tab' );

			$.ajax({
				url: ajaxurl,
				method: 'post',
				data: {
					'action':   'mtswpt_save_state',
					'tab':      tab,
					'security': mtswpt.nonce
				},
				beforeSend: function() {
					$translationsToggle.prop( 'disabled', true );
				},
				success: function() {
					$translationsToggle.prop( 'disabled', false );
				}
			});

			if ( $( this ).is( ':checked' ) ) {

				if ( ! translationIsLoaded ) {
					mtswptLoadTranslations();
				} else {
					$translationsContainer.show();
					$( '#translate_search_wrapper' ).show();
					$( '#translate_strings_per_page_wrapper' ).show();
				}

				$( '#nhp-opts-reset-translations' ).prop( 'disabled', false );

			} else {

				$translationsContainer.hide();
				$( '#translate_search_wrapper' ).hide();
				$( '#translate_strings_per_page_wrapper' ).hide();
				$( '#nhp-opts-reset-translations' ).prop( 'disabled', true );
			}
		});

		// Translate panel v2: instant saving & pagination
		$translationsContainer.on( 'change', '.mts_translate_textarea', function( e ) {

			var $this = $( e.target );
			mtswptSaveTranslation( $this.data( 'id' ), $this.val(), $this );

		}).on( 'focus', '.mts_translate_textarea', function( e ) {

			var $this = $( e.target );
			if ( '' === $this.val() ) {
				$this.data( 'translated', 0 );
			} else {
				$this.data( 'translated', 1 );
			}

		}).on( 'click', '.mts_translation_pagination a', function( e ) {

			e.preventDefault();
			var $this = $( e.target );
			if ( ! $this.hasClass( 'current' ) ) {
				mtswptLoadTranslations( $this.text(), $( '#translate_search' ).val() );
			}
		});

		$( '#translate_search' ).on( 'input propertychange', function() {
			var query = $( this ).val();
			fnDelay(function() {
				mtswptLoadTranslations( 1, query );
			}, 600 );
		});

		var translateStringsPerPage = $( '#translate_strings_per_page' ).val();
		$( '#translate_strings_per_page' ).on( 'blur', function() {
			var query = $( '#translate_search' ).val();
			var translateStringsPerPageChanged = translateStringsPerPage !== $( '#translate_strings_per_page' ).val();

			if ( translateStringsPerPageChanged ) {

				mtswptLoadTranslations( 1, query );
				translateStringsPerPage = $( '#translate_strings_per_page' ).val();
			}
		});

		$( document ).on( 'click', '#add_plugin_button', function( e ) {

			e.preventDefault();

			var $this = $( this ),
				pluginDomain = $( '#mtswpt_plugin' ).val(),
				$spinner = $this.parent().find( '#mtswpt-select-loader' );

			if ( '' !== pluginDomain ) {

				$.ajax({
					url: ajaxurl,
					method: 'post',
					data: {
						'action':        'mtswpt_add_plugin',
						'plugin_domain': pluginDomain,
						'security':      mtswpt.nonce
					},
					beforeSend: function() {

						$this.prop( 'disabled', true );
						$spinner.addClass( 'is-active' );
					},
					success: function() {
						location.reload();
					}
				});

			} else {

				$( '#mtswpt_plugin' ).focus();
			}
		});

		$( document ).on( 'click', '.mtswpt-remove-translation', function( e ) {

			e.preventDefault();

			var result = confirm( mtswpt.confirm_remove );

			if ( result ) {

				var $this = $( this ),
					pluginTab = $this.attr( 'data-tab' );

				if ( '' !== pluginTab ) {

					$.ajax({
						url: ajaxurl,
						method: 'post',
						data: {
							'action':     'mtswpt_remove_plugin',
							'plugin_tab': pluginTab,
							'security':   mtswpt.nonce
						},
						success: function() {
							var a = removeURLParameter( window.location.href, 'tab' );
							window.location.href = a;
						}
					});
				}
			}
		});

		$( document ).on( 'click', '.show-import-export', function( e ) {

			e.preventDefault();

			$( this ).next().toggleClass( 'active' );
		});

		$( document ).on( 'click', '.import-export-tab', function( e ) {

			e.preventDefault();

			var $this = $( this ),
				tab = $this.attr( 'href' );

			$this.closest( 'ul' ).find( '.active' ).removeClass( 'active' );
			$( '.translate-import-export-content.active' ).removeClass( 'active' ).find( 'textarea' ).blur();
			$this.addClass( 'active' );
			$( tab ).addClass( 'active' ).find( 'textarea' ).click().focus();
		});

		$( document ).on( 'click', '#import-button', function( e ) {

			e.preventDefault();

			var $this = $( this ),
				tab = getUrlParameter( 'tab' ),
				$textarea = $( '#mtswpt-import' ),
				importCode = $textarea.val(),
				stringsOptName = $textarea.attr( 'data-opt-name' );

			if ( '' !== tab ) {

				if ( '' !== importCode ) {

					var result = confirm( mtswpt.confirm_import );

					if ( result ) {

						$.ajax({
							url: ajaxurl,
							method: 'post',
							data: {
								'action':           'mtswpt_import_strings',
								'tab':              tab,
								'import_code':      importCode,
								'strings_opt_name': stringsOptName,
								'security':         mtswpt.nonce
							},
							beforeSend: function() {
								$( '.mtswpt-import-error' ).remove();
								$this.prop( 'disabled', true );
							},
							success: function( data ) {

								if ( '1' === data ) {
									location.reload();
								} else {
									$this.prop( 'disabled', false );
									$textarea.after( data );
								}
							}
						});
					}

				} else {

					alert( mtswpt.no_import_data );
					$textarea.focus();
				}
			}
		});

		$( document ).on( 'mtswptStringUpdated', function() {

			var $exportField = $( '#mtswpt-export' ),
				tab = getUrlParameter( 'tab' ),
				stringsOptName = $exportField.attr( 'data-opt-name' );

			$.ajax({
				url: ajaxurl,
				method: 'post',
				data: {
					'action':           'mtswpt_update_export_code',
					'tab':              tab,
					'strings_opt_name': stringsOptName,
					'security':         mtswpt.nonce
				},
				beforeSend: function() {

					$exportField.prop( 'disabled', true );
					$exportField.val( mtswpt.updating_export );
				},
				success: function( data ) {

					$exportField.prop( 'disabled', false );
					$exportField.val( data );
				}
			});
		});
	});

})( jQuery );
