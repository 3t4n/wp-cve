/**
 * AJAX Load More.
 *
 * Contains functions for AJAX Load More.
 */

import {
	$,
	$window,
	$doc,
	$body,
	sight
} from './utility';

/**
 * Insert Load More
 */
function sight_portfolio_load_more_insert( container, settings ) {
	if ( 'none' === settings.pagination_type ) {
		return;
	}

	if ( $( container ).find( '.sight-portfolio-area__pagination' ).length ) {
		return;
	}

	$( container ).append( '<div class="sight-portfolio-area__pagination"><button class="sight-portfolio-load-more">' + settings.translation.load_more + '</button></div>' );
}

/**
 * Get next posts
 */
function sight_portfolio_ajax_get_posts( container, reload ) {
	var pagination = $( container ).find( '.sight-portfolio-area__pagination' );
	var loadMore = $( container ).find( '.sight-portfolio-load-more' );
	var settings = $( container ).data( 'settings' );
	var page = $( container ).data( 'page' );

	// Filter terms.
	var terms = [];

	$( container ).find( '.sight-portfolio-area-filter__list li' ).each( function( index, elem ) {
		if ( $( this ).is( '.sight-filter-active:not(.sight-filter-all)' ) ) {
			terms.push( $( this ).find( 'a' ).data( 'filter' ) );
		}
	} );

	// Set area loading.
	$( container ).find( '.sight-portfolio-area__main' ).addClass( 'sight-portfolio-loading' );

	// Set ajax settings.
	if ( reload ) {
		page = 1;
	}

	var data = {
		action: 'sight_portfolio_ajax_load_more',
		terms: terms,
		page: page,
		posts_per_page: settings.posts_per_page,
		query_data: settings.query_data,
		attributes: settings.attributes,
		options: settings.options,
		_ajax_nonce: settings.nonce,
	};

	// Set the loading state.
	$( container ).data( 'loading', true );

	// Set button text to Load More.
	$( loadMore ).text( settings.translation.loading );

	// Request Url.
	var sight_pagination_url;

	if ( 'ajax_restapi' === settings.type ) {
		sight_pagination_url = settings.rest_url;
	} else {
		sight_pagination_url = settings.url;
	}

	// Send Request.
	$.post( sight_pagination_url, data, function( res ) {
		if ( res.success ) {

			// Get the posts.
			var data = $( res.data.content );

			data.imagesLoaded( function() {

				// Append new posts to list, standard and grid archives.
				if ( reload ) {
					$( container ).find( '.sight-portfolio-area__main' ).html( data );
				} else {
					$( container ).find( '.sight-portfolio-area__main' ).append( data );
				}

				// Entry animations.
				$( container ).find( '.sight-portfolio-entry-request' ).each( function() {
					if ( !$( this ).is( 'sight-portfolio-entry-animation' ) ) {
						$( this ).addClass( 'sight-portfolio-entry-animation' ).animate( {opacity: 1, top: 0}, 600);
					}
				} );

				// WP Post Load trigger.
				$( window ).trigger( 'post-load' );

				// Reinit Facebook widgets.
				if ( $( '#fb-root' ).length && 'object' === typeof FB ) {
					FB.XFBML.parse();
				}

				// Set button text to Load More.
				$( loadMore ).text( settings.translation.load_more );

				// Increment a page.
				page = page + 1;

				$( container ).data( 'page', page );

				// Set the loading state.
				$( container ).data( 'loading', false );
			} );

			// Remove Button on Posts End.
			if ( res.data.posts_end || !data.length ) {

				// Remove Load More button.
				$( pagination ).remove();
			} else {

				// Add Load More button.
				sight_portfolio_load_more_insert( container, settings );
			}

			$( container ).find( '.sight-portfolio-area__main' ).removeClass( 'sight-portfolio-loading' );
		} else {
			$( container ).find( '.sight-portfolio-area__main' ).removeClass( 'sight-portfolio-loading' );
		}


	} ).fail( function( xhr, textStatus, e ) {
		// console.log(xhr.responseText);
	} );
}

/**
 * Initialization Load More
 */
function sight_portfolio_load_more_init( infinite, blockId ) {

	$( '.sight-portfolio-area' ).each( function() {

		var sight_ajax_settings;

		if ( blockId ) {
			let itemId = $( this ).closest( `[data-block]` ).attr( 'id' );

			if ( itemId !== blockId ) {
				return;
			}
		} else if ( $( this ).data( 'init' ) ) {
			return;
		}

		var archive_data = $( this ).data( 'items-area' );

		if ( archive_data ) {
			sight_ajax_settings = JSON.parse( window.atob( archive_data ) );
		}

		if ( sight_ajax_settings ) {
			// Set load more settings.
			$( this ).data( 'settings', sight_ajax_settings );
			$( this ).data( 'page', 2 );
			$( this ).data( 'loading', false );
			$( this ).data( 'scrollHandling', {
				allow: $.parseJSON( 'infinite' === sight_ajax_settings.pagination_type ? true : false ),
				delay: 400
			} );

			if ( !infinite && ( 'infinite' === sight_ajax_settings.pagination_type ? true : false ) ) {
				return;
			}

			// Add load more button.
			if ( sight_ajax_settings.max_num_pages > 1 ) {
				sight_portfolio_load_more_insert( this, sight_ajax_settings );
			}
		}

		$( this ).data( 'init', true );
	} );
}

sight_portfolio_load_more_init( true, false );

sight.addAction( 'sight.components.serverSideRender.onChange', 'sight/portfolio/loadmore', function( props ) {
	if ( 'sight/portfolio' === props.block ) {

		let blockId = $( `[id=block-${props.blockProps.clientId}]` ).attr( 'id' );

		sight_portfolio_load_more_init( false, blockId );
	}
} );

// On Scroll Event.
$( window ).scroll( function() {

	$( '.sight-portfolio-area .sight-portfolio-load-more' ).each( function() {

		var container = $( this ).closest( '.sight-portfolio-area' );

		// Vars loading.
		var loading = $( container ).data( 'loading' );
		var scrollHandling = $( container ).data( 'scrollHandling' );

		if ( 'undefined' === typeof scrollHandling ) {
			return;
		}

		if ( $( this ).length && !loading && scrollHandling.allow ) {

			scrollHandling.allow = false;

			$( container ).data( 'scrollHandling', scrollHandling );

			setTimeout( function() {
				var scrollHandling = $( container ).data( 'scrollHandling' );

				if ( 'undefined' === typeof scrollHandling ) {
					return;
				}

				scrollHandling.allow = true;

				$( container ).data( 'scrollHandling', scrollHandling );
			}, scrollHandling.delay );

			var offset = $( this ).offset().top - $( window ).scrollTop();
			if ( 4000 > offset ) {
				sight_portfolio_ajax_get_posts( container );
			}
		}
	} );
} );

// On Click Event.
$( 'body' ).on( 'click', '.sight-portfolio-load-more', function() {
	var container = $( this ).closest( '.sight-portfolio-area' );
	var loading = $( this ).data( 'loading' );

	if ( !loading ) {
		sight_portfolio_ajax_get_posts( container, false );
	}
} );

// On Click Categories.
$( 'body' ).on( 'click', '.sight-portfolio-area-filter__list a', function( e ) {
	var container = $( this ).closest( '.sight-portfolio-area' );

	// Remove active item.
	$( this ).parent().siblings().removeClass( 'sight-filter-active' );

	// Active item.
	$( this ).parent().addClass( 'sight-filter-active' );

	// Ajax.
	sight_portfolio_ajax_get_posts( container, true );

	e.preventDefault();
} );
