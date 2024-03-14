( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		var tmWooLoadingClass = 'loading',
			tmWooAddedClass   = 'added in_compare',
			btnSelector       = 'button.tm-woocompare-button';

		$( document ).on( 'tx_compare_update_fragments', updateFragments );

		function productButtonsInit() {

			$( document ).on( 'click', btnSelector, function ( event ) {

				var button = $( this );

				event.preventDefault();

				var url  = tmWoocompare.ajaxurl,
					data = {
						action: 'tx_woocompare_add_to_list',
						pid:    button.data( 'id' ),
						nonce:  button.data( 'nonce' ),
						single: button.hasClass( 'tm-woocompare-button-single' )
					};

				button
					.removeClass( tmWooAddedClass )
					.addClass( tmWooLoadingClass );

				$.post(
					url,
					data,
					function( response ) {

						button.removeClass( tmWooLoadingClass );

						if( response.success ) {

							switch ( response.data.action ) {

								case 'add':

									$( btnSelector + '[data-id=' + data.pid + ']' )
										.addClass( tmWooAddedClass )
										.find( '.text' )
										.text( tmWoocompare.removeText );

									if( response.data.comparePageBtn ) {

										button.after( response.data.comparePageBtn );
									}
									break;

								case 'remove':

									$( btnSelector + '[data-id=' + data.pid + ']' )
										.removeClass( tmWooAddedClass )
										.find( '.text' )
										.text( tmWoocompare.compareText );

									$( '.tm-woocompare-page-button' ).remove();

									break;

								default:

									break;
							}
							data = {
								action: 'tx_woocompare_update'
							};
							tmWoocompareAjax( null, data );
						}

						if ( undefined !== response.data.counts ) {
							$( document ).trigger( 'tx_compare_update_fragments', { response: response.data.counts } );
						}

					}
				);
			} );

		}

		function tmWoocompareAjax( event, data ) {

			if( event ) {

				event.preventDefault();
			}

			var url           = tmWoocompare.ajaxurl,
				widgetWrapper = $( 'div.tm-woocompare-widget-wrapper' ),
				compareList   = $( 'div.tm-woocompare-list' );

			data.isComparePage = !!compareList.length;
			data.isWidget      = !!widgetWrapper.length;

			if ( 'tx_woocompare_update' === data.action && !data.isComparePage && !data.isWidget ) {

				return;
			}
			compareList.addClass( tmWooLoadingClass );
			widgetWrapper.addClass( tmWooLoadingClass );

			$.post(
				url,
				data,
				function( response ) {

					compareList.removeClass( tmWooLoadingClass );
					widgetWrapper.removeClass( tmWooLoadingClass );

					if( response.success ) {

						if( data.isComparePage ) {

							$( 'div.tm-woocompare-wrapper' ).html( response.data.compareList );
							$( document ).trigger( 'enhance.tablesaw' );
						}
						if( data.isWidget ) {

							widgetWrapper.html( response.data.widget );
						}
						if ( 'tx_woocompare_empty' === data.action ) {

							$( btnSelector )
								.removeClass( tmWooAddedClass )
								.find( '.text' )
								.text( tmWoocompare.compareText );

							$( '.tm-woocompare-page-button' ).remove();
						}
						if ( 'tx_woocompare_remove' === data.action ) {

							$( btnSelector + '[data-id=' + data.pid + ']' )
								.removeClass( tmWooAddedClass )
								.find( '.text' )
								.text( tmWoocompare.compareText );

							$( '.tm-woocompare-page-button' ).remove();
						}
					}

					if ( undefined !== response.data.counts ) {
						$( document ).trigger( 'tx_compare_update_fragments', { response: response.data.counts } );
					}

					widgetButtonsInit();
				}
			);
		}

		function tmWoocompareRemove( event ) {

			var button = $( event.currentTarget ),
				data   = {
					action: 'tx_woocompare_remove',
					pid:    button.data( 'id' ),
					nonce:  button.data( 'nonce' )
				};

			tmWoocompareAjax( event, data );
		}

		function tmWoocompareEmpty( event ) {

			var data = {
				action: 'tx_woocompare_empty'
			};

			tmWoocompareAjax( event, data );
		}

		function widgetButtonsInit() {

			$( '.tm-woocompare-remove' )
				.off( 'click' )
				.on( 'click', function ( event ) {
					tmWoocompareRemove( event );
				} );

			$( '.tm-woocompare-empty' )
				.off( 'click' )
				.on( 'click', function( event ) {
					tmWoocompareEmpty( event );
				} );
		}

		function getRefreshedFragments() {

			$.ajax({
				url: tmWoocompare.ajaxurl,
				type: 'get',
				dataType: 'json',
				data: {
					action: 'tx_compare_get_fragments'
				}
			}).done( function( response ) {

				$( document ).trigger( 'tx_compare_update_fragments', { response: response.data } );

			});

		}

		function updateFragments( event, data ) {

			if ( ! $.isEmptyObject( data.response.defaultFragments ) ) {
				$.each( data.response.defaultFragments, function( key, val ) {
					var $item  = $( key ),
						$count = $( '.compare-count', $item );
					if ( 0 === $count.length ) {
						$item.append( tmWoocompare.countFormat.replace( '%count%', val ) );
					} else {
						$item.find( '.compare-count' ).html( val );
					}
				} );
			}

			if ( ! $.isEmptyObject( data.response.customFragments ) ) {
				$.each( data.response.customFragments, function( key, val ) {
					var $item = $( key );
					if ( $item.length ) {
						$item.html( val );
					}
				} );
			}

		}

		widgetButtonsInit();
		productButtonsInit();
		getRefreshedFragments();

	} );
}( jQuery) );
