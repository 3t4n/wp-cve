var youzify_load_attachments = false;

( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Init Wall Posts Effects.
		 */
		$.youzify_google_map_init = function() {

			if ( ( typeof google === 'object' && typeof google.maps === 'object' ) ) {

				var map_div, map, placesService;

				$( '.youzify-activity-map-unloaded' ).each( function() {

					map_div = $( this );

 					// Init Map
		            map = new google.maps.Map( map_div[0], {
			            zoom: 16,
			            center: new google.maps.LatLng(50, 50),
			            panControl: false,
			            zoomControl: false,
			            mapTypeControl: false,
			            scaleControl: false,
			            streetViewControl: false,
			            overviewMapControl: false,
			            rotateControl: false,
			            fullscreenControl: false,
			            disableDefaultUI: false,
			            mapTypeId: google.maps.MapTypeId.ROADMAP
			        });

		            // Init Places Services
		            placesService = new google.maps.places.PlacesService(map);

		            placesService.getDetails( { placeId: map_div.attr( 'data-place_id' ) }, function( place, status ) {

		            	if ( status === google.maps.places.PlacesServiceStatus.OK ) {

			                var center = place.geometry.location,
			                    marker = new google.maps.Marker({
			                        position: center,
			                        map: map
			                    });

			                map.setCenter(center);

		                }
		            });

					map_div.removeClass( 'youzify-activity-map-unloaded' ).addClass( 'youzify-activity-map' ).fadeIn();

				});

			}
		}

		$.youzify_google_map_init();

		if ( jQuery().viewportChecker ) {

			/**
			 * Init Wall Posts Effects.
			 */
			$.youzify_init_wall_posts_effect = function() {

				if ( $( '.youzify_effect' )[0] ) {
					$( '.youzify_effect' ).viewportChecker( {
					    classToAdd: 'animated',
					    classToRemove: 'invisible',
					    removeClassAfterAnimation: true,
					    offset:'10%',
					    callbackFunction: function( elem, action ) {
							elem.addClass( elem.data( 'effect' ) );
					    }
					}, 500 );
				}
			}

			// Init Posts Effect.
			$.youzify_init_wall_posts_effect();

			// Init Effect On the appended elements also.
			if ( $( '#youzify div.activity' )[0] ) {

				// Init Effect On Activity Filters
				var youzify_observer = new MutationObserver(function( mutations ) {
					$.youzify_init_wall_posts_effect();
					$.youzify_sliders_init();
					$.youzify_google_map_init();
					if ( typeof youzify_autosize === "function" ) { 
						youzify_autosize( jQuery( '.youzify textarea' ) );
					}
					$.youzify_pause_other_media_when_user_play_another_media();
				});

				// Pass in the target node, as well as the observer options
				youzify_observer.observe( $( '#youzify div.activity' )[0] , { attributes: false, childList: true, subtree:true, characterData: false } );

			}

		}

		/**
		 * Modal.
		 */
		$( document ).on( 'click', '.youzify-trigger-who-modal' , function( e ) {

			e.preventDefault();

			// Init Var.
			var button = $( this );

			if ( button.hasClass( 'loading' ) ) {
				return;
			}

			// Show loader
			button.addClass( 'loading' );

			// Init Var.
			var reset_text = false;

			// Show Loading.
			if ( ! button.find( 'i' ).get( 0 ) ) {
				var old_nbr = button.text(), reset_text = true;
				button.html( '<i class="fas fa-spin fa-spinner"></i>' );
			}

			var li =  $( this ).closest( 'li.activity-item' ),
				data = {
				'action': $( this ).data( 'action' ),
				'post_id': li.attr( 'id' ).substr( 9, li.attr( 'id' ).length )
			};

			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post( Youzify.ajax_url, data, function( response ) {

				// Set Older Number.
				if ( reset_text ) {
					button.html( old_nbr );
				}

	    		// Shpow pop-up
	    		$.youzify_show_modal( $( response ) );

				// Hide Loader
				button.removeClass( 'loading' );

			});

		});

		function youzify_bp_get_cookies() {
			var allCookies = document.cookie.split(';'),  // get all cookies and split into an array
				bpCookies      = {},
				cookiePrefix   = 'bp-',
				i, cookie, delimiter, name, value;

			// loop through cookies
			for (i = 0; i < allCookies.length; i++) {
				cookie    = allCookies[i];
				delimiter = cookie.indexOf('=');
				name      = $.trim( unescape( cookie.slice(0, delimiter) ) );
				value     = unescape( cookie.slice(delimiter + 1) );

				// if BP cookie, store it
				if ( name.indexOf(cookiePrefix) === 0 ) {
					bpCookies[name] = value;
				}
			}

			// returns BP cookies as querystring
			return encodeURIComponent( $.param(bpCookies) );
		}

		/**
		 * Modal.
		 */
		$( document ).on( 'click', '.youzify-delete-post' , function( e ) {

			/* Delete activity stream items */
			var target = $( this ),
			li = target.parents( 'div.activity ul li' ),
			timestamp = li.prop( 'class' ).match( /date-recorded-([0-9]+)/ );

			target.addClass('loading');
			$.post( ajaxurl, {
				action: 'delete_activity',
				'cookie': youzify_bp_get_cookies(),
				'id': $( this ).parent().attr( 'data-activity-id' ),
				'_wpnonce': target.attr( 'data-nonce' )
			},
			function( response ) {

				if ( response[0] + response[1] === '-1' ) {
					li.prepend( response.substr( 2, response.length ) );
					li.children( '#message' ).hide().fadeIn( 300 );
				} else {
					li.slideUp( 300 );

					// reset vars to get newest activities
					if ( timestamp && activity_last_recorded === timestamp[1] ) {
						newest_activities = '';
						activity_last_recorded  = 0;
					}
				}

			});

		});


		/**
		 * Show Activity Tagged Users.
		 */
		$( document ).on( 'click', '.youzify-show-tagged-users' , function( e ) {

			e.preventDefault();

			$( '.youzify-wall-modal-overlay' ).fadeIn( 500, function() {
				$( this ).find( '.youzify-modal-loader' ).fadeIn( 400 );
			});

			// Init Vars.
			var li = $( this ).closest( 'li.activity-item' );
			var data = {
				'action': 'youzify_activity_tagged_users_modal',
				'post_id': li.attr( 'id' ).substr( 9, li.attr( 'id' ).length )
			};

			// Show Modal.
			jQuery.post( Youzify.ajax_url, data, function( response ) {

				var $new_modal = $( 'body' ).append( response );

			    // Display Modal
				$new_modal.find( '.youzify-wall-modal' ).addClass( 'youzify-wall-modal-show' );

				// Hide Loader
				$( '.youzify-wall-modal-overlay' ).find( '.youzify-modal-loader' ).hide();

			});

		});

		// Hide Modal If User Clicked Escape Button
		$( document ).keyup( function( e ) {
			if ( $( '.youzify-wall-modal-show' )[0] ) {
			    if ( e.keyCode === 27 ) {
				    $( '.youzify-wall-modal-close' ).trigger( 'click' );
			    }
			}
		});

		// # Hide Modal if User Clicked Outside
		$( document ).mouseup( function( e ) {
		    if ( $( '.youzify-wall-modal-overlay' ).is( e.target ) && $( '.youzify-wall-modal-show' )[0] ) {
				$( '.youzify-wall-modal-close' ).trigger( 'click' );
		    }
		});

		if ( Youzify.activity_autoloader == 'on' ) {

		   var $window = $( window );

			// Check the window scroll event.
			$window.scroll( function () {
				// Find the visible "load more" button.
				// since BP does not remove the "load more" button, we need to find the last one that is visible.
				var $load_more_btn = $( '#activity-stream .load-more:visible' );
				// If there is no visible "load more" button, we've reached the last page of the activity stream.
				// If data attribute is set, we already triggered request for ths specific button.
				if ( ! $load_more_btn.get( 0 ) || $load_more_btn.data( 'youzify-autoloaded' ) ) {
					return;
				}

				// Find the offset of the button.
				var pos = $load_more_btn.offset();
				var offset = pos.top - 3000;// 50 px before we reach the button.

				// If the window height+scrollTop is greater than the top offset of the "load more" button,
				// we have scrolled to the button's position. Let us load more activity.
				if ( $window.scrollTop() + $window.height() > offset ) {
					$load_more_btn.data( 'youzify-autoloaded', 1 );
					$load_more_btn.find( 'a' ).trigger( 'click' );
				}

			});
		}

		/* Add / Remove friendship buttons */
		$( '#activity-stream' ).on('click', '.friendship-button a', function() {
			$(this).parent().addClass('loading');
			var fid   = $(this).attr('id'),
				nonce   = $(this).attr('href'),
				thelink = $(this);

			fid = fid.split('-');
			fid = fid[1];

			nonce = nonce.split('?_wpnonce=');
			nonce = nonce[1].split('&');
			nonce = nonce[0];

			$.post( ajaxurl, {
				action: 'addremove_friend',
				'cookie': youzify_bp_get_cookies(),
				'fid': fid,
				'_wpnonce': nonce
			},
			function(response)
			{
				var action  = thelink.attr('rel');
				var parentdiv = thelink.parent();

				if ( action === 'add' ) {
					$(parentdiv).fadeOut(200,
						function() {
							parentdiv.removeClass('add_friend');
							parentdiv.removeClass('loading');
							parentdiv.addClass('pending_friend');
							parentdiv.fadeIn(200).html(response);
						}
						);

				} else if ( action === 'remove' ) {
					$(parentdiv).fadeOut(200,
						function() {
							parentdiv.removeClass('remove_friend');
							parentdiv.removeClass('loading');
							parentdiv.addClass('add');
							parentdiv.fadeIn(200).html(response);
						}
						);
				}
			});
			return false;
		} );

		$('#activity-stream').on('click', '.group-button a', function( e ) {

			if( ! $( this ).hasClass( 'membership-requested') ) {
				$( this ).addClass( 'youzify-btn-loading' );
			}

			var gid   = $(this).parent().attr('id'),
				nonce   = $(this).attr('href'),
				thelink = $(this);

			gid = gid.split('-');
			gid = gid[1];

			nonce = nonce.split('?_wpnonce=');
			nonce = nonce[1].split('&');
			nonce = nonce[0];

			// Leave Group confirmation within directories - must intercept
			// AJAX request
			if ( thelink.hasClass( 'leave-group' ) && false === confirm( BP_DTheme.leave_group_confirm ) ) {
				return false;
			}

			$.post( ajaxurl, {
				action: 'joinleave_group',
				'cookie': youzify_bp_get_cookies(),
				'gid': gid,
				'_wpnonce': nonce
			},
			function(response) {
				var parentdiv = thelink.parent();

				$(parentdiv).fadeOut(200,
					function() {
						parentdiv.fadeIn(200).html(response);

						var mygroups = $('#groups-personal span'),
							add        = 1;

						if( thelink.hasClass( 'leave-group' ) ) {
							// hidden groups slide up
							if ( parentdiv.hasClass( 'hidden' ) ) {
								parentdiv.closest('li').slideUp( 200 );
							}

							add = 0;
						} else if ( thelink.hasClass( 'request-membership' ) ) {
							add = false;
						}

						// change the "My Groups" value
						if ( mygroups.length && add !== false ) {
							if ( add ) {
								mygroups.text( ( mygroups.text() >> 0 ) + 1 );
							} else {
								mygroups.text( ( mygroups.text() >> 0 ) - 1 );
							}
						}

					}
				);
			});
			return false;
		} );

		/**
		 * Pause Other Media when user play another Media.
		 */
		$.youzify_pause_other_media_when_user_play_another_media = function() {

			$( 'audio,video' ).on( 'play', function( e ) {

				// Stopping other audios and videos
		        $( 'audio,video' ).not( this ).each( function( index, element ) {
		            element.pause();
		        });

		        // Stoping iframes.
		        $( 'iframe' ).each( function( index, element ) {
		            $( element ).attr( 'src', $( element ).attr( 'src' ) );
		        });

		    });

		}

		$.youzify_pause_other_media_when_user_play_another_media();

		/**
		 * Nice Select - Add Attribute value to current.
		 */
		$( document ).on( 'click', '.nice-select .option', function( e ) {
			$( this ).parent().prev( '.current' ).attr( 'data-value', $( this ).attr( 'data-value' ) );
		});

		/**
		 * Shortcodes Pagination.
		 */
		$( '.activity-list' ).on( 'click', 'li.load-more', function( e ) {

			if ( $( this ).closest('.youzify-activity-shortcode')[0] ) {

				// Stop Propagation.
				e.stopImmediatePropagation();

				// Get Current Load More Button.
			    var load_more_button = $( this );

			    // Add Loading Icon.
			    load_more_button.addClass( 'loading' );

			    // Get Shortcode Container.
				var container = $( this ).parents( '.youzify-activity-shortcode' );

				// Increase Page Number
			    container.attr( 'data-page', parseInt( container.attr( 'data-page' ) ) + 1 );

			    var data = container.data();

			    data.page = container.attr( 'data-page' );

			    $.post( ajaxurl, { data: data, action : 'youzify_activity_load_activities' }, function( response ) {

			        if ( response.success ) {
			        	load_more_button.hide();
			            // Add New Posts.
			            var new_posts = $(  response.data );
			            load_more_button.parents( 'ul.activity-list' ).append( new_posts );
						bp_legacy_theme_hide_comments( new_posts );
			        }

			    }, 'json' );

			    return false;

			}

		});

		/**
		 * Display Activity tools.
		 */
		$( document ).on( 'click', '.activity-item .youzify-show-item-tools', function ( e ) {

			var button = $( this ), li = button.closest( 'li.activity-item' ), default_icon = button.find( 'i' ).attr( 'class' );

			if ( button.hasClass( 'loaded' ) ) {
				li.find( '.youzify-activity-tools' ).fadeToggle();
				return;
			}

			if ( button.hasClass( 'loading' ) ) {
				return;
			}

			button.addClass( 'loading' );

			button.find( 'i' ).attr( 'class', 'fas fa-spin fa-spinner' );

			// Get Activity Tools.
	        $.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            dataType: 'json',
	            data: { 'activity_id' : li.attr( 'id' ).substr( 9, li.attr( 'id' ).length ), 'action': 'youzify_get_activity_tools' },
	            success: function( response ) {

	            	button.find( 'i' ).attr( 'class', default_icon );
	            	button.addClass( 'loaded' );
	            	button.removeClass( 'loading' );

	            	if ( response.success ) {
	            		$( response.data ).prependTo( li ).fadeIn();
	            	}

	            	// Include Sticky Scripts.
	            	if ( $( response.data ).find( '.youzify-pin-tool' ).get( 0 ) ) {
						$( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-sticky-posts.min.js' } ).appendTo( 'head' );
	            	}

	            	// Include Bookmark Scripts.
	            	if ( $( response.data ).find( '.youzify-bookmark-tool' ).get( 0 ) ) {
						$( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-bookmark-posts.min.js' } ).appendTo( 'head' );
	            	}

	            }
	        });

		});

		// Display Search Box.
    	$( '.youzify-activity-show-search-form' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var button = $( this ), parent = button.closest( 'ul' );
    		parent.find( '#activity-filter-select .youzify-dropdown-area' ).fadeOut( 1, function() {
    			button.closest( 'li' ).find( '.youzify-dropdown-area' ).fadeToggle();
    			button.closest( 'li' ).find( 'input' ).focus();
    		});
		});

		// Display Search Box.
    	$( '.youzify-activity-show-filter' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var button = $( this ), parent = button.closest( 'ul' );
    		parent.find( '.youzify-activity-show-search .youzify-dropdown-area' ).fadeOut( 1, function() {
    			button.closest( 'li' ).find( '.youzify-dropdown-area' ).fadeToggle();
    		} );
		});


		// Display Search Box.
    	$( '.youzify-show-activity-search' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var parent = $( this ).parents( '#youzify' ),
    		element = parent.find( '.youzify-activity-show-search .youzify-dropdown-area' );
    		parent.find( '#activity-filter-select .youzify-dropdown-area, .activity-type-tabs' ).fadeOut();
    		element.fadeToggle();
    		element.find( 'input' ).focus();
		});

		// Display Filter Box.
    	$( '.youzify-show-activity-filter' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var parent = $( this ).parents( '#youzify' );
    		parent.find( '.youzify-activity-show-search .youzify-dropdown-area, .activity-type-tabs' ).fadeOut();
    		parent.find( '#activity-filter-select .youzify-dropdown-area' ).fadeToggle();
		});

		// Display Menu Box.
    	$( '.youzify-show-activity-menu' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var parent = $( this ).parents( '#youzify' );
    		parent.find( '#subnav .youzify-dropdown-area' ).fadeOut();
    		parent.find( '.activity-type-tabs' ).fadeToggle();
		});

    	/**
    	 * Shared Posts - Load More Button
    	 */
	    $( '.activity_share' ).on( 'click', '.activity-read-more a', function(event) {
	        var target = $( this );
	        target.addClass( 'loading' );
	        $.post( ajaxurl, {
	            action: 'get_single_activity_content',
	            'activity_id': target.parent().attr('id').split('-')[3]
	        },
	        function( response ) {
	            target.closest( '.activity-inner' ).slideUp( 300 ).html( response ).slideDown(300);
	        });
	        return false;
	    });

		/**
		 * Hide Modal if user clicked Close Button or Icon
		 */
		$( document ).on( 'click', '.youzify-wall-modal-close' , function( e ) {

			e.preventDefault();

			// Hide Form.
			$( '.youzify-wall-modal' ).removeClass( 'youzify-wall-modal-show' );
	        $( '.youzify-wall-modal-overlay' ).fadeOut( 600 );

			setTimeout(function(){
			   // wait for card1 flip to finish and then flip 2
			   $( '.youzify-wall-modal' ).remove();
			}, 500);

		});

        /**
         * Show Poll Result.
         */
        $( document ).on( 'click' , '.youzify-see-poll-results', function () {

            // Get Parent.
           var parent = $( this ).closest( '.youzify-poll-content' );

			// Show Loader.
			$( this ).find( 'i' ).attr( 'class', 'fas fa-spinner fa-spin' );

			$.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'youzify_activity_result_content',
                    activity_id: parent.attr( 'data-activity-id' )
                },
                success: function ( response ) {
                    // Show Voting Results
                    parent.find( '.youzify-poll-holder' ).html( $( response.data ) );
                }

            });

        });

        /**
         *  Show Poll Options.
         */
        $( document ).on( 'click' , '.youzify-see-standard-poll-options', function() {

            // Get Parent.
            var parent = $( this ).closest( '.youzify-poll-content' );

            // Show Loader.
            $( this ).find( 'i' ).attr( 'class', 'fas fa-spinner fa-spin' );

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'youzify_activity_revote_content',
                    activity_id: parent.attr( 'data-activity-id' )
                },
                success: function ( response ) {

                    // Show Poll Options.
                    parent.find( '.youzify-poll-holder' ).html( $( response.data ).find( '.youzify-poll-options-holder' ) );

                }
            })

        });

        /**
         *  Submit Votes.
         */
        $( document ).on( 'click', '.youzify-submit-vote', function () {

            // Get Parent
            var button = $( this ), parent = $( this ).closest( '.youzify-poll-content' );

            // If Steal Loading Return.
            if ( button.hasClass( 'yz-loading' ) ) {
                return;
            }

            // Check If Disabled.
            if ( $( this ).hasClass( 'youzify-disable-vote' ) ) {

                // Show Error Message.
                $.youzify_DialogMsg( 'error', Youzify.poll_option_empty );

                return;

            }

            var valuesStatus = [], emptyBox = 0;

            parent.find( 'input[name="youzify_poll_option[]"]:checked' ).each( function ( i ) {
                // Set Option Value.
                valuesStatus[ i ] = $( this ).val();
            });

            // Get Old Title.
            var old_title = $( this ).html();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'youzify_activity_poll_new_vote',
                    voting_options: valuesStatus,
                    activity_id: parent.attr( 'data-activity-id' )
                },
                beforeSend: function () {
                    button.addClass( 'yz-loading' );
                    button.html( '<i class="fas fa-spin fa-spinner"></i>' );
                },
                success: function ( response ) {

                    // Remove Loading Class.
                    button.removeClass( 'yz-loading' );

                    // Show Old Title.
                    button.html( old_title );

                    // If Response Success False
                    if ( ! response.success  ) {
                        $.youzify_DialogMsg( 'error', response.data );
                        return;
                    }

                    // Check If User Can Revote.
                    if ( button.attr( 'data-revote' ) == 'off' ) {

                        // Disable Options
                        parent.find( '.youzify-poll-item-input' ).prop( "disabled", true );
                        // Remove Button.
                        button.remove();
                    }

                    // Show Message.
                    if ( response.data['result'] ) {
                        $.youzify_DialogMsg( 'success', response.data['msg'] );
                    } else {
                        // Show Voting Results
                        parent.find( '.youzify-poll-holder' ).html( $( response.data ) );
                    }

                }
            });
        });


        /**
         * Disable Poll Button Submit.
         */
        $( document ).on( 'click' , '.youzify-poll-item-input', function() {

            // Get Parent
            var parent = $( this ).closest( '.youzify-poll-options-holder' );

            if ( parent.find( '.youzify-poll-item-input:checked' ).length > 0 ) {

                // Enable Button
                parent.find( '.youzify-disable-vote' ).removeClass( 'youzify-disable-vote' );

            } else if ( parent.find( '.youzify-poll-item-input:checked' ).length == 0 ) {

                // Disable button.
                parent.find( '.youzify-submit-vote' ).addClass( 'youzify-disable-vote' );

            }

        });


        /**
         *  Show All Voters.
         */
        $( document ).on( 'click' , '.youzify-show-voters' , function () {

            //Get Parent.
            var parent = $( this ).closest( '.youzify-poll-content' );

            // Get Icon
            var button = $( this );

            // Get Old Icon.
            var old_button = button.html();

            // Show Loader.
            button.html( '<i class="fas fa-spinner fa-spin"></i>' );

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'youzify_activity_all_voters',
                    activity_id: parent.attr( 'data-activity-id' ),
                    option_id: $( this ).attr( 'data-option-id' )
                },
                success: function ( response ) {

            		// Reset Content
            		button.html( old_button );

                    // Append Data.
                    parent.find( '.youzify-poll-holder' ).append( $( response.data ) );

                }
            })

        });


	});

})( jQuery );