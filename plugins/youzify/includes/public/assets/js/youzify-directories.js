( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		var youzify_directory_per_page = false;

		// Sync Search Forms.
		$( '.youzify-directory-search-form' ).bind( 'keyup paste', function() {
		    $( this ).closest( '.youzify' ).find( '.dir-search input[type="text"]' ).val( $( this ).val() );
		});

		// Submit Search if user hit Enter.
		$( document ).on( 'keypress', '.youzify-directory-search-form', function( e ) {
			if ( e.which == 13 ) {
				e.preventDefault();
				$( this ).closest( '.youzify' ).find( '.dir-search input[type="submit"]' ).click();
			}
		});

		/**
		 * Display Activity tools.
		 */
		$( '#youzify' ).on( 'click', 'a.page-numbers', function ( e ) {

			// Init Var.
			var button_clone = $( this ).clone().html( '<i class="fas fa-spinner fa-spin"></i>' );

			$( this ).hide( 0, function(){
				button_clone.insertAfter( $( this ) );
			});

		});

		// Add Loading Button
        $( '#youzify-groups-list,#youzify-members-list' ).on( 'click', 'a.group-button:not(.membership-requested),.friendship-button:not(.awaiting_response_friend) a', function(e) {
        	e.preventDefault();
    		$( this ).addClass( 'youzify-btn-loading' );
		});

		// Display Search Box.
    	$( '#directory-show-search' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '.youzify-directory-filter #members-order-select,.youzify-directory-filter #groups-order-select,.youzify-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeOut( 1 );
    		$( '#youzify-directory-search-box' ).fadeToggle();
		});

		// Display Search Box.
    	$( '#directory-show-filter' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#youzify-directory-search-box,.youzify-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeOut( 1 );
    		$( '.youzify-directory-filter #members-order-select, .youzify-directory-filter #groups-order-select, .youzify-directory-filter #blogs-order-select' ).fadeToggle();
		});

		// Display Search Box.
    	$( '#directory-show-menu' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#youzify-directory-search-box,.youzify-directory-filter #members-order-select,.youzify-directory-filter #groups-order-select,.youzify-directory-filter #blogs-order-select' ).fadeOut( 1 );
    		$( '.youzify-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeToggle();
		});

		// Activate Members Masonry Layout.
		if ( $( '#youzify-members-list' )[0] ) {

			// Set the container that Masonry will be inside of in a var
		    var members_container = document.querySelector( '#youzify-members-list' );

		    // Create empty var msnry
		    var members_msnry;

		    // Initialize Masonry after all images have loaded
		    // imagesLoaded( members_container, function() {
		    // });
		        members_msnry = new Masonry( members_container, {
		            itemSelector: '#youzify-members-list > li'
		        });

		}

		// Activate Groups Masonry Layout.
		if ( $( '#youzify-groups-list' )[0] ) {

			// Set the container that Masonry will be inside of in a var
		    var groups_container = document.querySelector( '#youzify-groups-list');

		    // Create empty var msnry
		    var groups_msnry;

		    // Initialize Masonry after all images have loaded
		    imagesLoaded( groups_container, function() {
		        groups_msnry = new Masonry( groups_container, {
		            itemSelector: '#youzify-groups-list li'
		        });
		    });

		}


		// Display Search Box.
    	$( '#directory-show-search a' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#youzify-directory-search-box' ).fadeToggle();
		});

		// Display Search Box.
    	$( '#directory-show-filter a' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '.youzify-directory-filter #members-order-select, .youzify-directory-filter #groups-order-select, .youzify-directory-filter #blogs-order-select' ).fadeToggle();
		});

		$( '#members_search, #groups_search' ).on( 'click', function(){
		    $( window ).off( 'resize' );
		});

    	/**
    	 * Store Shortcode Per Page Value.
    	 */
		$( '.youzify-directory' ).on( 'click', 'a.page-numbers', function() {

			// Init Var.
			var $shortcode_container = $( this ).closest( '.youzify-directory-shortcode' );

			// Get Shortcode Pagination Number.
			if (  $shortcode_container.get( 0 ) ) {
				youzify_directory_per_page = $shortcode_container.data();
			}

		});

		/**
		 * Append Attachments.
		 */
		$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {

			if ( youzify_directory_per_page != false && originalOptions.hasOwnProperty( 'data' ) && originalOptions.data.hasOwnProperty( 'action' ) ) {

				var action = originalOptions.data.action;

				if ( action == 'members_filter' || action == 'groups_filter' ) {
			        options.data += '&custom_args=' + JSON.stringify( youzify_directory_per_page );
			        youzify_directory_per_page = false;
				}

			}

		});

	});

})( jQuery );