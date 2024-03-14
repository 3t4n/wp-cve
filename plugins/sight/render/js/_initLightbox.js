/**
 * Lightbox
 */

import {
	$,
	$window,
	$doc,
	$body,
	sight
} from './utility';

( function( $ ) {

	$.fn.SightLightbox = function( options ) {

		var settings = $.extend( {
			gallery : false,
		}, options);

		var containerSelector       = this;
		var imageSelector     = null;

		$( containerSelector ).each( function() {

			if ( $( this ).is( 'img' ) ) {
				imageSelector = this;
			} else {
				imageSelector = $( this ).find( 'img' );
			}

			$( imageSelector ).each( function() {

				var link = $( this ).closest( '.sight-portfolio-entry' ).find( '.sight-portfolio-overlay-link' );

				if ( ! $( link ).is( 'a' ) ) {
					return;
				}

				var imagehref = $( link ).attr( 'href' );

				if ( ! imagehref.match( /\.(gif|jpeg|jpg|png)/ ) ) {
					return;
				}

				$( link ).addClass( 'sight-image-popup' );
			});

			if ( $( this ).closest( '.elementor-element' ).length > 0 ) {
				return;
			}

			$( containerSelector ).magnificPopup( {
				delegate: '.sight-image-popup',
				type: 'image',
				tClose: sight_lightbox_localize.text_close + '(Esc)',
				tLoading: sight_lightbox_localize.text_loading,
				gallery: {
					enabled: settings.gallery,
					tPrev: sight_lightbox_localize.text_previous,
					tNext: sight_lightbox_localize.text_next,
					tCounter: '<span class="mfp-counter">%curr% ' + sight_lightbox_localize.text_counter + ' %total%</span>'
				},
				image: {
					titleSrc: function( item ) {
						let entry = item.el.closest( '.sight-portfolio-entry' );

						if ( $( entry ).hasClass( 'sight-portfolio-entry-custom' ) ) {

							return $( entry ).find( '.sight-portfolio-entry__caption' ).text();

						} else if ( $( entry ).hasClass( 'sight-portfolio-entry-post' ) ) {

							return $( entry ).find( '.sight-portfolio-entry__caption' ).text();

						} else {
							return $( entry ).find( '.sight-portfolio-entry__heading' ).text();
						}
					}
				},
			} );
		} );

	};

	function initSightLightbox() {
		$( '.sight-portfolio-area-lightbox' ).imagesLoaded( function() {
			$( '.sight-portfolio-area-lightbox' ).SightLightbox( { gallery: true } );
		} );
	}

	$( document ).ready( function() {
		initSightLightbox();
		$( document.body ).on( 'post-load image-load', function() {
			initSightLightbox();
		} );
	} );

} )( jQuery );
