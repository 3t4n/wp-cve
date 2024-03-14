( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Check if Div is Empty
		function isEmpty( el ) {
		    return ! $.trim( el.html() );
		}

    	$( '.youzify-msg-show-search' ).on( 'click', function( e ) {
    		$( '.item-list-tabs #search-message-form' ).fadeToggle();
		});

    	// Load Widget With Effects
		function youzify_profile_load_widget() {
			if ( $( '.youzify_effect' )[0] ) {
				$( '.youzify_effect' ).viewportChecker( {
				    classToAdd: 'animated',
				    classToRemove: 'invisible',
					removeClassAfterAnimation: true,
				    offset:'10%',
				    callbackFunction: function( elem, action ) {
						elem.addClass( elem.data( 'effect' ) );
				    }
				});
			}
		}

		youzify_profile_load_widget();

		// Profile View More Menu
	    $( '.youzify-navbar-view-more' ).click( function( e ) {
	    	if ( $( e.target ).closest('.youzify-nav-view-more-menu' ).length === 0 ) {
		    	e.preventDefault();
		    	$( this ).find( '.youzify-nav-view-more-menu' ).fadeToggle();
	    	}
	    });

		// Show/Hide Message
		$( '.youzify-nav-settings' ).click( function( e ) {
	        e.preventDefault();
			// Hide Account Settings Menu to avoid any Conflect.
			if (  $( '.youzify-responsive-menu' ).hasClass( 'is-active' ) ) {
				$( '.youzify-responsive-menu' ).removeClass( 'is-active'  );
				$( '.youzify-profile-navmenu' ).fadeOut();
			}
	        // Get Parent Box.
			var settings_box = $( this ).closest( '.youzify-settings-area' );
			// Toggle Menu.
			settings_box.toggleClass( 'open-settings-menu' );
			// Display or Hide Box.
	        settings_box.find( '.youzify-settings-menu' ).fadeToggle( 400 );
		});

		var original_sidebar = $( '.youzify-author' ).closest( '.youzify-sidebar-column' );

    	// Init Profile Settings Menu
		function youzify_init_account_settings_menu() {

			if ( $( '.youzify-profile-login' )[0] ) {
				var account_menu = $( '.youzify-profile-login' );
			    if ( $( window ).width() < 769 ) {
	        		account_menu.prependTo( '.youzify-main-column .youzify-column-content' );
			    } else {
	        		account_menu.prependTo( '.youzify-sidebar-column .youzify-column-content' );
			    }
	        }

	        if ( $( '.youzify-author' )[0] ) {
				var header = $( '.youzify-author' );
			    if ( $( window ).width() < 769 ) {
	        		header.prependTo( $( original_sidebar ).parent() );
			    } else {
	        		header.prependTo( $( original_sidebar ) );
			    }
	        }
		}


		// Init Account Menu
		youzify_init_account_settings_menu();

		// Skill Bar Script
		if ( $( '.youzify-skillbar' )[0] ) {
			/**
			 * Load Skills On Scroll
			 */
			$.youzify_initSkills = function() {
				if ( $( window ).scrollTop() + $( window ).height() >= $( '.youzify-skillbar' ).offset().top ) {
		            if ( ! $( '.youzify-skillbar' ).attr( 'loaded' ) ) {
		                $( '.youzify-skillbar' ).attr( 'loaded', true );
						$( '.youzify-skillbar' ).each( function() {
							$( this ).find( '.youzify-skillbar-bar' ).animate( {
								width: $( this ).attr( 'data-percent' )
							}, 2000 );
						});
		            }
		        }
			}
			// Init Skills.
			$.youzify_initSkills();
			$( window ).scroll( function() {
		    	$.youzify_initSkills();
			});
		}

		var resizeTimer;

		$( window ).on( 'resize', function ( e ) {
		    clearTimeout( resizeTimer );
		    resizeTimer = setTimeout( function () {
		        if ( $( window ).width() > 768 ) {
		        	$( '.youzify-profile-navmenu' ).fadeIn( 1000 );
		        } else {
		        	if ( $( '.youzify-responsive-menu' ).hasClass( 'is-active' ) ) {
		        		$( '.youzify-profile-navmenu' ).fadeIn( 600 );
		        	} else {
		        		$( '.youzify-profile-navmenu' ).fadeOut( 600 );
		        		$( '.youzify-responsive-menu' ).removeClass( 'is-active' );
		        	}
		        }
		        // Init Account Menu
				youzify_init_account_settings_menu();
		    }, 1 );
		});

		// Zoom Flick Photo
	    $( '.youzify-pf-zoom, .youzify-flickr-zoom' ).on( 'click' , function() {
	    	$( this ).next( '.youzify-lightbox-img' ).click();
	    });

		// Hide Settings Menu if User Clicked Outside.
		$( document ).mouseup( function( e ) {

		    if ( ! $( '.youzify-settings-area' ).hasClass( 'open-settings-menu' ) ) {
		    	return;
		    }

		    // Set Up Variables.
		    var settings_button = $( '.youzify-nav-settings' ),
		    	settings_menu   = $( '.youzify-settings-menu' );

	        // Hide Menu.
	        if (
	        	! settings_menu.is( e.target ) &&
	        	! settings_button.is( e.target ) &&
	        	settings_menu.has( e.target ).length === 0 &&
	        	settings_button.has( e.target ).length === 0
	        ) {
				// Toggle Menu.
	        	$( '.youzify-settings-area' ).removeClass( 'open-settings-menu' );
				// Hide Box.
	            settings_menu.slideToggle( 250 );
	        }

		});

		$( 'body' ).on( 'click', '.youzify-bp-close-uploader', function( e ) {
	        e.preventDefault();
			$( 'body' ).removeClass( 'youzify-modal-overlay-active' );
			let parent = $( this ).closest( '.youzify-bp-uploader-popup' );
	        parent.fadeOut();
	        if ( parent.attr('data-type') == 'avatar' ) {
	        	parent.find( '.avatar-nav-items li:first' ).trigger('click' );
	        }
	    });

	});

})( jQuery );

/**
 *	Add Js to HTML body
 */
( function( e, t, n ) {

	'use strict';

    var r = e.querySelectorAll( 'html' )[0];
    r.className = r.className.replace( /(^|\s)no-js(\s|$)/, "$1js$2" );

})( document, window, 0 );