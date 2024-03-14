( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Init Vars.
		var selected_giphy = $( '.youzify-selected-giphy-item' ), is_giphy_selected = false, timer = null;

		/**
		 * Select GIF Process
		 */
		$( document ).on( 'click', '.youzify-giphy-item img', function ( e ) {

			if ( is_giphy_selected === true ) {
				return;
			}

			is_giphy_selected = true;

			// Init Var.
			var img_url = $( this ).attr( 'data-original' );

			// Get Closest Form.
			var form = $( this ).closest( 'form' );

			// Get Selected Giphy.
			var selected_giphy = form.find( '.youzify-selected-giphy-item' );

			// Hide Search Form.
			form.find( '.youzify-wall-giphy-form .youzify-wall-cf-item' ).fadeOut( 400, function(){

				// Display Loader.
				form.find( '.youzify-giphy-loading-preview' ).fadeIn();

				// Add Selected Image.
				selected_giphy.find( 'input' ).val( img_url );

				// Get Selected Image.
				var selected_image = $( '<img>' ).attr( { src: img_url } );

				// Add Image3
				selected_giphy.prepend( selected_image );

				// Wait Untill Image Is loaded.
				$( selected_image ).on('load', function( e ){

					// Hide Loader.
					form.find( '.youzify-giphy-loading-preview' ).fadeOut( 100, function() {
						// Display Image.
						selected_giphy.fadeIn().css( 'display', 'inline-block' );
						// Reset Giphy Selection.
						is_giphy_selected = false;
					});
				});

			});

		});

		/**
		 * Load More GIFs Process
		 */
		$( document ).on( 'click', '.youzify-load-more-giphys', function ( e ) {

			// Init Vars
			var form = $( this ).closest( 'form' );

			// Add Loading Class.
			form.find( '.youzify-load-more-giphys' ).addClass( 'loading' );

			// Get Page Number.
			var page_number = parseInt( $( this ).attr( 'data-page' ) );

			// Ars.
			var args = {
			    q: $( this ).attr( 'data-query' ),
			    offset: ( page_number * Youzify_Wall.giphy_limit ) - 2
		  	};

		  	// Get Giphy Items Through An Ajax Call.
			$.youzify_LoadGiphyItems( form, args );

			// Increase Page.
			$( this ).attr( 'data-page', page_number + 1  );

		});

	    /**
	     * Select Activity GIF.
	     */
	    $( document ).on( 'click', '.youzify-comment-giphy-form .youzify-giphy-item', function( e ) {

	    	// Get Form.
	    	var form = $( this ).closest( 'form' );

	    	// Set Image.
	    	form.find( 'textarea' ).val( '<img src="' + $( this ).find( 'img' ).attr( 'src' ) + '" class="youzify-comment-gif" alt="">' );

	    	// Post Image.
	    	form.find( '.youzify-send-comment' ).trigger( 'click' );

	    	// Clear Textarea.
	    	form.find( 'textarea' ).val( '' );

	    	// Hide Form.
	    	$( '.youzify-comment-giphy-form' ).fadeOut();

	    });

		/**
		 * Open Comments GIF Form.
		 */
		$( document ).on( 'click', '.youzify-wall-add-activity_giphy', function( e ) {

			var form = $( this ).closest( 'form' );

			if ( 'activity_giphy' == form.find( 'input:radio[name="post_type"]:checked' ).val() ) {
	    		return true;
	    	}

			form.find( '.youzify-giphy-item' ).remove();

		  	// Get Giphy Items Through An Ajax Call.
			$.youzify_LoadGiphyItems( form, { url: 'https://api.giphy.com/v1/gifs/trending?' } );
		});

		/**
		 * Show Comments GIFs Form.
		 */
		$( document ).on( 'click', '.youzify-wall-add-gif > i', function( e ) {

			e.stopImmediatePropagation();

			// Get Form
			var form = $( this ).parent().find( '.youzify-comment-giphy-form' );

			if ( form.css( 'display' ) == 'block' ) {
				form.fadeOut();
				return;
			}

			// Remove Old Giphys
			form.find( '.youzify-giphy-item' ).remove();

			// Show GIF Popup.
			form.fadeIn().find( '.youzify-giphy-search-input' ).focus();

		  	// Get Giphy Items Through An Ajax Call.
			$.youzify_LoadGiphyItems( form, { url: 'https://api.giphy.com/v1/gifs/trending?' } );

		});

		/**
		 * Search GIF.
		 */
		$( document ).on( 'keydown', '.youzify-giphy-search-input', function( e ) {

			if ( e.keyCode == 13 ) {
				e.preventDefault();
				return;
			}

			var self = $( this );

			// Check if User Stopped Typing.
			clearTimeout( timer );
       		timer = setTimeout(function() {

				// Init Vars
				var form = self.closest( '.youzify-wall-giphy-form' ), search_query = self.val();

				// Hide & Change Load More Query & Page.
				form.find( '.youzify-load-more-giphys' ).fadeOut( 1 ).attr( { 'data-query': search_query, 'data-page': 2 } );

			    // Reset Giphy Items.
				form.find( '.youzify-giphy-item' ).remove();

			  	// Get Giphy Items Through An Ajax Call.
				if ( search_query != '' ) {
					$.youzify_LoadGiphyItems( form, { q : search_query } );
				} else {
					$.youzify_LoadGiphyItems( form, { url: 'https://api.giphy.com/v1/gifs/trending?' } );
				}

       		}, 1000 );

			});

		/**
		 * Delete GIF Process.
		 */
		$( document ).on( 'click', '.youzify-delete-giphy-item', function() {

			// Get Closest Form.
			var form = $( this ).closest( 'form' );

			form.find( '.youzify-selected-giphy-item' ).fadeOut( 400, function() {

				// Clear Selected Gif Data
				$( this ).find( 'img' ).remove();
				$( this ).find( 'input' ).val( '' );

				// Display Search Form.
				form.find( '.youzify-wall-giphy-form .youzify-wall-cf-item' ).fadeIn();

			});

		});

		// Hide Modal if User Clicked Outside
		$( document ).mouseup( function( e ) {
			var container = $( '.youzify-comment-giphy-form' );
		    if ( ! container.is( e.target ) && container.has( e.target ).length === 0 ) {
				container.fadeOut();
		    }
		});

		// Append More Elements On Scroll.
		function youzify_allow_giphy_scroll() {

			$( '.youzify-giphy-items-content' ).on( 'scroll', function( e ) {

				if ( $( this ).parent().hasClass( 'youzify-wall-cf-item' ) ) {

					if ( $( this ).scrollLeft() + $( this ).innerWidth() >= $( this )[0].scrollWidth) {
						var load_more = $ ( this ).find( '.youzify-load-more-giphys' );
						if ( ! load_more.hasClass( 'loading' ) ) {
							load_more.trigger( 'click' );
						}
				    }

				} else {

					if ( $( this ).scrollTop() + $( this ).innerHeight() >= $( this )[0].scrollHeight) {
						var load_more = $ ( this ).find( '.youzify-load-more-giphys' );
						if ( ! load_more.hasClass( 'loading' ) ) {
							load_more.trigger( 'click' );
						}
				    }

				}

			});
		}


		/**
		 * Get Giphy Items
		 */
		$.youzify_LoadGiphyItems = function( form, options ) {

			// Hide No Gifs Message.
			form.find( '.youzify-no-gifs-found' ).fadeOut();

			// Get Giphy API Args.
			var args = $.extend( {
				url : 'https://api.giphy.com/v1/gifs/search?',
			    api_key: "aFFKTuSMjd6j0wwjpFCPXZipQbcnw3vB",
			    limit: Youzify_Wall.giphy_limit,
			    fmt: 'json',
			    rating: 'g'
        	}, options ), i, items = '', display_load_more;

			$.ajax({
				url: args.url + $.param( args ),
				method: 'GET',
				success: function( r ) {

					if ( r.data.length === 0 ) {

						if ( args.q && args.q != '' ) {
							form.find( '.youzify-no-gifs-found' ).fadeIn();
						} else {
							form.find( '.youzify-load-more-giphys' ).fadeOut();
						}

						return;
					}

					for ( i = 0; i < args.limit; i++ ) {
						// Get Image Data.
						var gif = r.data[i].images;
						// Get Image Item.
						items += '<div class="youzify-giphy-item"><img src="' + gif.fixed_height.url + '" data-original="' + gif.original.url + '"></div>';
					}

					// Display Load More.
					display_load_more = r.pagination.total_count > args.limit ? true : false;

					// Insert Items.
					$( $( items ) ).insertBefore( form.find( '.youzify-load-more-giphys' ) );

					// Remove loading class.
					form.find( '.youzify-load-more-giphys' ).removeClass( 'loading' );

					// Show more button.
					form.find( '.youzify-load-more-giphys' ).fadeIn().css( 'display', 'inline-block' );

					// Allow Giphy Scroll.
					youzify_allow_giphy_scroll();

				}

			});
		}


	});

})( jQuery );