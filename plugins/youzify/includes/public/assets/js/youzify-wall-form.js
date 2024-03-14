( function( $ ) {

	'use strict';


	$( document ).ready( function() {

		var load_wall_form_js = false,
			load_wall_polls_js = false,
			load_wall_checkin_js = false;

		/**
		 * Show All Form Buttons
		 */
    	$( '.youzify-wall-form-show-all' ).on( 'click', function( e ) {
    		$( '.youzify-wall-form-show-all' ).fadeOut( function() {
    			$( '.youzify-wall-opts-item' ).fadeIn();
    			$( this ).remove();
    		});
		});

		/**
		 * Load Wall Assets.
		 */
    	$( document ).on( 'input click', '.youzify-wall-textarea, .ac-input', function() {
    		$( this ).next( '.youzify-load-emojis' ).attr( 'data-cursor', $( this ).prop( 'selectionStart' ) );
    	});

    	$( document ).on( 'focus', '.youzify-wall-textarea', function() {
    		if ( load_wall_form_js == false ) {
	    		// Load Live Preview Scripts.
    			if ( $( this ).closest( 'form' ).find( '.youzify-lp-prepost' ).get( 0 ) ) {
    				youzify_call_live_url_preview_scripts();
			    }
	    		load_wall_form_js = true;
    		}
	    });

    	$( document ).on( 'click', '.youzify-edit-tool', function() {
			if ( load_wall_form_js == false ) {
	    		// Load Live Preview Scripts.
    			if ( $( '#youzify-wall-form' ).find( '.youzify-lp-prepost' ).get( 0 ) || ! $( '#youzify-wall-form' ).get( 0 )  ) {
    				youzify_call_live_url_preview_scripts();
			    }
	    		load_wall_form_js = true;
    		}
    	});

	    function youzify_call_live_url_preview_scripts() {
    		$( '<link/>', { rel: 'stylesheet', href: Youzify.assets + 'css/youzify-url-preview.min.css' } ).appendTo( 'head' );
	        $( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-url-preview.min.js' } ).appendTo( 'head' );
	    }

		/**
		 * Load Emojis JS.
		 */
    	$( document ).on( 'click', '.youzify-load-emojis', function() {
        	var form = $( this ).closest( 'form' );
	        $( this ).find( 'i' ).attr(  'class', 'fas fa-spin fa-spinner' );
        	$( this ).addClass( 'loading' );
	        $( '<link/>', { rel: 'stylesheet', href: Youzify.assets + 'css/youzify-emojionearea.min.css' } ).appendTo( 'head' );
	        $( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-emojionearea.min.js' } ).appendTo( 'head' );
	    });

		/**
		 * Submit Wall Posts.
		 */
		$( document ).on( 'click', '.youzify-wall-post', function() {

			// Init Vars.
			var last_date_recorded = 0,
				button = $( this ),
				button_title = $( this ).text(),
				form   = button.closest( 'form#youzify-wall-form' ),
				inputs = {}, post_data, object;

			// Get all inputs and organize them into an object {name: value}
			$.each( form.serializeArray(), function( key, input ) {
				// Only include public extra data
				if ( '_' !== input.name.substr( 0, 1 ) && 'whats-new' !== input.name.substr( 0, 9 ) ) {
					if ( ! inputs[ input.name ] ) {
						inputs[ input.name ] = input.value;
					} else {
						// Checkboxes/dropdown list can have multiple selected value
						if ( ! $.isArray( inputs[ input.name ] ) ) {
							inputs[ input.name ] = new Array( inputs[ input.name ], input.value );
						} else {
							inputs[ input.name ].push( input.value );
						}
					}
				}
			} );

			form.find( '*' ).each( function() {
				if ( $.nodeName( this, 'textarea' ) || $.nodeName( this, 'input' ) ) {
					$( this ).prop( 'disabled', true );
				}
			} );

			// Disable Emojionearea Editor.
	        if ( form.find( '.youzify-wall-textarea' ).get(0).Youzify_emojioneArea ) {
	        	form.find( '.youzify-wall-textarea' ).data( 'Youzify_emojioneArea' ).disable();
	        }

			/* Disable Button & Display Loader. */
			button.addClass( 'loading' );
			button.prop('disabled', true );
			form.addClass( 'submitted' );
			button.css( 'min-width', button.css( 'width' ) );
			button.html( '<i class="fas fa-spinner fa-spin"></i>' );

			/* Default POST values */
			var object = '';
			var item_id = form.find( '#whats-new-post-in' ).val();
			var content = form.find( '#whats-new' ).val();
			var firstrow = $( '#youzify ul.activity-list li' ).first();
			var activity_row = firstrow;
			var timestamp = null;

			// Checks if at least one activity exists
			if ( firstrow.length ) {

				if ( activity_row.hasClass( 'load-newest' ) ) {
					activity_row = firstrow.next();
				}

				timestamp = activity_row.prop( 'class' ).match( /date-recorded-([0-9]+)/ );
			}

			if ( timestamp ) {
				last_date_recorded = timestamp[1];
			}

			/* Set object for non-profile posts */
			if ( item_id > 0 ) {
				object = form.find( '#whats-new-post-object' ).val();
			}

			post_data = $.extend( {
				action: 'youzify_post_update',
				'cookie': youzify_buddypress_get_cookies(),
				'_youzify_wpnonce_post_update': $( '#_youzify_wpnonce_post_update' ).val(),
				'content': content,
				'object': object,
				'item_id': item_id,
				'since': last_date_recorded,
				'_bp_as_nonce': $( '#_bp_as_nonce' ).val() || ''
			}, inputs );

			$.post( ajaxurl, post_data, function( response ) {

				form.find( '*' ).each( function() {
					if ( $.nodeName( this, 'textarea' ) || $.nodeName( this, 'input' ) ) {
						$( this ).prop( 'disabled', false );
					}
				});

	            if ( form.find( '.youzify-wall-textarea' ).get( 0 ).Youzify_emojioneArea ) {
	            	form.find( '.youzify-wall-textarea' ).data( 'Youzify_emojioneArea' ).enable();
	            }

            	// Get Response Data.
            	if ( response.success == false ) {
					$.youzify_DialogMsg( 'error', response.data.error );
				} else {

					// Show Check .
					button.html( '<i class="fas fa-check"></i>' ).hide().fadeIn( 'slow' );

					form.find( '.youzify-delete-attachment' ).trigger( 'click' );

					if ( 0 === $('ul.activity-list').length ) {
						$( 'div.error' ).slideUp( 100 ).remove();
						$( '#message' ).slideUp( 100 ).remove();
						$( 'div.activity').append( '<ul id="activity-stream" class="activity-list item-list">' );
					}

					if ( firstrow.hasClass( 'load-newest' ) ) {
						firstrow.remove();
					}

					$( '#activity-stream' ).prepend( response );

					if ( ! last_date_recorded ) {
						$( '#activity-stream li:first' ).addClass( 'new-update just-posted' );
					}

					// Scroll To Added Post.
					if ( $( response ).get( 0 ) ) {
						$( 'body,html' ).animate({
						    scrollTop: $( '#' + $( response ).attr( 'id') ).offset().top - 65 + 'px'
						}, 1000 );
					}

					if ( 0 !== $( '#latest-update' ).length ) {
						var l   = $( '#activity-stream li.new-update .activity-content .activity-inner p' ).html(),
							v     = $( '#activity-stream li.new-update .activity-content .activity-header p a.view' ).attr('href'),
							ltext = $( '#activity-stream li.new-update .activity-content .activity-inner p' ).text(),
							u     = '';

						if ( ltext !== '' ) {
							u = l + ' ';
						}

						u += '<a href="' + v + '" rel="nofollow">' + BP_DTheme.view + '</a>';

						$( '#latest-update' ).slideUp( 300,function() {
							$( '#latest-update' ).html( u );
							$( '#latest-update' ).slideDown( 300 );
						});
					}

					$( 'li.new-update' ).hide().slideDown( 300 );
					$( 'li.new-update' ).removeClass( 'new-update' );
					$( '#whats-new' ).val( '' );

					// Init Slider.
					if ( inputs.post_type == 'activity_slideshow' )  {
						$.youzify_sliders_init();
					}

					// Reset Form.
					form.get( 0 ).reset();

					// Reset Text Form.
		            if ( form.find( '.youzify-wall-textarea' ).get( 0 ).Youzify_emojioneArea ) {
		            	form.find( '.youzify-wall-textarea' ).get( 0 ).Youzify_emojioneArea.setText( '' );
		            }

		            // Clear Location Plugin Fields
		            form.find( '.bp-checkins input[type="hidden"]' ).val( '' );

		            // Clear Checkin
		            form.find( '.youzify-checkin-close-icon,.youzify-remove-map' ).trigger( 'click' );

		            // Reset Tagged Users Form.
		            form.find( '.youzify-tagged-user .youzify-tagusers-delete-user, .youzify-list-delete-item, .youzify-list-close-icon' ).trigger( 'click' );

		            // Select First Element.
		            form.find( 'div.youzify-activity-privacy' ).find( '.list div' ).first().trigger( 'click' );
		            form.find( '#whats-new-post-in' ).find( 'option' ).first().trigger( 'click' );

		            // Update Nice Select.
		            if ( ! window.hasOwnProperty( 'youzify_disable_niceselect' ) ) {
		            	form.find( '#whats-new-post-in' ).niceSelect( 'update' );
		            }

		            // Update Post in field.
		            $( '#whats-new-post-in' ).trigger( 'change' );

		            form.find( '.youzify-wall-options input:radio[name="post_type"]' ).first().trigger( 'change' );
		            form.find( '.youzify-lp-prepost .lp-button-cancel' ).trigger( 'click' );

		            if ( inputs.post_type == 'activity_giphy' ) {
		            	form.find( '.youzify-delete-giphy-item' ).trigger( 'click' );
		            	form.find( '.youzify-giphy-submit-search' ).val( '' ).trigger( 'click' );
		            }

		            // Get Poll Activities
		            let poll_activities = [ 'activity_poll', 'activity_versus_poll', 'activity_hotornot_poll', 'activity_classic_poll' ];
		            
		            // Reset Custom Fields Content.
		            if ( jQuery.inArray( inputs.post_type, poll_activities ) !== -1 ) {
			            form.find( '.youzify-wall-cf-input' ).val( '' );
			            form.find( '.youzify-wall-custom-form .youzify-wall-cf-item:not(:nth-child(1)):not(:nth-child(2)) .youzify-wall-form-remove-item' ).trigger( 'click' );
			        }

					// reset vars to get newest activities
					newest_activities = '';
					activity_last_recorded  = 0;

				}

				setTimeout( function() {
					// Change Submit Button Text.
					button.html( button_title ).fadeIn( 'slow' );
				}, 1000 );

				// Enable Submit Button.
				form.find( '.youzify-wall-post,.youzify-update-post' ).prop( 'disabled', false ).removeClass( 'loading' );

				// Hide Share Form
				if( form.parent().attr( 'id' ) == 'youzify-share-activity-wrapper' ) {
					$( '.youzify-modal-close-icon' ).trigger( 'click' );
				}

			});

			return false;
		});

		/*
		 * Show/Hide Link Form.
		 **/
		$( document ).on( 'change', 'input:radio[name="post_type"]', function( e ) {

			// Get Post type.
			var form = $( this ).closest( 'form' );
			var post_type = $( this ).val();
			var uploader = $( this ).data( 'uploader' );
			var inputs_lenght = $( 'input:radio[name="post_type"]' ).length;

			if ( inputs_lenght > 1 ) {

				if (  form.find( '.youzify-wall-custom-form[data-post-type!="' + post_type + '"]' ).length == 0 ) {
	            	form.find( '.youzify-wall-custom-form[data-post-type="' + post_type + '"]' ).fadeIn();
				} else {
		            form.find( '.youzify-wall-custom-form[data-post-type!="' + post_type + '"]' ).fadeOut( 1, function() {
		            	form.find( '.youzify-wall-custom-form[data-post-type="' + post_type + '"]' ).fadeIn();
		            });
				}

			} else {
	            form.find( '.youzify-wall-custom-form[data-post-type="' + post_type + '"]' ).fadeIn();
			}

	        // Set Input Supported Elements.
	        switch( post_type ) {

	        	case 'activity_video':
	        		form.find( '.youzify-upload-attachments' ).attr( 'accept', 'video/*' );
	        	break;

	        	case 'activity_audio':
	        		form.find( '.youzify-upload-attachments' ).attr( 'accept', 'audio/*' );
	        	break;

	        	case 'activity_file':
	        		form.find( '.youzify-upload-attachments' ).removeAttr( 'accept' );
	        	break;

	        	case 'activity_poll':

		        	// Call Poll JS
		        	if ( ! load_wall_polls_js ) {

		        		// if ( ! jQuery.isFunction( $.fn.sortable ) ) {
		        			// console.log('hhhh');
	       					// $( '<script/>', { rel: 'text/javascript', src: Youzify_Wall.wp_url + '/wp-includes/js/jquery/ui/sortable.min.js' } ).appendTo( 'html' );
		        		// }

	       				$( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-polls.min.js' } ).appendTo( 'head' );

		        		load_wall_polls_js = true;

		        	}

	        	break;

	        	default:
	        		form.find( '.youzify-upload-attachments' ).attr( 'accept', 'image/*' );
		        	break;

	        }

	        // Show/Hide Upload Button
	        if ( uploader == 'on' ) {
	            if ( e.originalEvent !== undefined && post_type != 'activity_quote' && post_type != 'activity_link' && form.parent().attr( 'id' ) != 'youzify-edit-activity-wrapper' ) {
	            	form.find( '.youzify-wall-actions .youzify-wall-upload-btn' ).trigger( 'click' );
	            }
	            form.find( '.youzify-wall-actions .youzify-wall-upload-btn' ).fadeIn();
	        } else {
	            form.find( '.youzify-wall-actions .youzify-wall-upload-btn' ).fadeOut();
	        }

	        // Set Cursor on First Field.
	        if ( form.find( '.youzify-wall-custom-form[data-post-type="' + post_type + '"]' ).find( 'input[type="text"]' )[0]  ) {
	        	form.find( '.youzify-wall-custom-form[data-post-type="' + post_type + '"]' ).find( 'input[type="text"]' )[0].focus();
	        }

	        // Delete Temporary Uploaded Files.
	       	form.find( '.youzify-delete-attachment' ).trigger( 'click' );

	        // Remove Old Attachments
	       	form.find( '.youzify-attachment-item' ).remove();

	    });

		// Display Form Fields After Page Load.
		$( 'input:radio[name="post_type"]' ).first().trigger( 'change' );

		/**
		 * Open Files Uploader
		 */
		$( document ).on( 'click', '.youzify-wall-upload-btn', function( e ) {

			if ( ! youzify_load_attachments ) {
				$( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-attachments.min.js' } ).appendTo( 'head' );
				$( '<link/>', { rel: 'stylesheet', href: Youzify.assets + 'css/youzify-attachments.min.css' } ).appendTo( 'head' );
				youzify_load_attachments = true;
			}

			$( this ).closest( 'form' ).find( '.youzify-wall-attachments .youzify-upload-attachments' ).click();

		    e.preventDefault();

		});

		/**
		 * Display Tag Users Search Box & Friends List.
		 */
		$( document ).on( 'click', '.youzify-tag-users-tool', function() {

			var form = $( this ).closest( 'form' );

        	// Hide All Form Opened Lists.
        	form.find( '.youzify-feeling-form,.youzify-checkin-form' ).fadeOut();

			if ( $( this ).hasClass( 'loaded' ) ) {
				form.find( '.youzify-tagusers-form' ).fadeToggle();
				form.find( '.youzify-tagusers-search-input' ).focus();
				return;
			}

			var button = $( this ),
				icon = button.find( 'i' ),
				old_icon = icon.attr( 'class' );

			// Display Loader.
			icon.attr( 'class', 'fas fa-spinner fa-spin' );

			// Get User Friends.
	        $.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            dataType: 'json',
	            data: { 'action': 'youzify_tag_users_get_user_friends' },
	            success: function( response ) {

	            	button.addClass( 'loaded' );
		        	var	list = $( '<div></div>' ).append( response.data );


		        	// Hide Selected Items.
		        	if ( form.parent().attr( 'id' ) == 'youzify-edit-activity-wrapper' ) {

		        		var tagged_users_ids = [];

		        		form.find( 'input[name="tagged_users[]"]' ).each(function() {
		        			list.find( '.youzify-list-item[data-user-id="100"] ').fadeOut();
						    tagged_users_ids.push($(this).val());
						});

		        		list.find( '.youzify-list-item' ).each(function( i, data ) {

						   if ( jQuery.inArray( $( this ).attr( 'data-user-id' ), tagged_users_ids ) !== -1 ) {
						   	$ ( this ).find( '.youzify-wall-tag-user' ).hide();
						   }

						});

		        	}
	            	form.find( '.youzify-tagusers-form' ).fadeIn( 200, function() {
	            		icon.attr( 'class', old_icon );
	            		form.find( '.youzify-tagusers-search-input' ).focus();
	            		form.find( '.youzify-wall-tagusers-list' ).html( list );
	            	});
	            }
	        });

		});

		/**
		 * Display Tag Users Search Box & Friends List.
		 */
		$( document ).on( 'click', '.youzify-checkin-tool', function() {

			var form = $( this ).closest( 'form' );

        	// // Hide All Form Opened Lists.
        	form.find( '.youzify-feeling-form,.youzify-tagusers-form' ).fadeOut();
			form.find( '.youzify-checkin-form' ).fadeToggle();
			form.find( '.youzify-checkin-search-input' ).focus();
			if ( ! load_wall_checkin_js  && ( typeof google === 'object' && typeof google.maps === 'object' ) ) {
   				$( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-checkin.min.js' } ).appendTo( 'head' );
        		load_wall_checkin_js = true;
        	}

		});

		/**
		 * Tag Users Search.
		 */
		$( document ).on( 'keyup', '.youzify-list-search-input', function() {
			var form = $( this ).closest( '.youzify-wall-list' );
			var value = this.value.toLowerCase().trim();
			form.find( '.youzify-wall-list-items .youzify-list-item' ).show().filter(function() {
				return $( this ).find( '.youzify-item-title, .youzify-item-description' ).text().toLowerCase().trim().indexOf( value ) == -1;
			}).hide();
		});

		/**
		 * Display Feeling/Activity Search Box & Categories..
		 */
		$( document ).on( 'click', '.youzify-user-mood-tool', function() {

			var form = $( this ).closest( 'form' );

	        form.find( '.youzify-tagusers-form,.youzify-checkin-form' ).fadeOut();

			if ( $( this ).hasClass( 'loaded' ) ) {
				form.find( '.youzify-feeling-form' ).fadeToggle();
				form.find( '.youzify-feeling-search-input' ).focus();
				return;
			}

			// Set Place Holder Attribute.
			form.find( '.youzify-feeling-form' ).attr( 'data-placeholder', form.find( '.youzify-feeling-search-input' ).attr( 'placeholder' ) );

			var button = $( this ),
				icon = button.find( 'i' ),
				old_icon = icon.attr( 'class' );

			// Display Loader.
			icon.attr( 'class', 'fas fa-spinner fa-spin' );

			// Get User Friends.
	        $.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            dataType: 'json',
	            data: { 'action': 'youzify_feeling_activity_get_categories' },
	            success: function( response ) {
	            	// Hide All Form Opened Lists.
	            	button.addClass( 'loaded' );
	            	form.find( '.youzify-feeling-form' ).fadeIn( 200, function() {
	            		icon.attr( 'class', old_icon );
	            		form.find( '.youzify-feeling-search-input' ).focus();
	            		form.find( '.youzify-wall-feeling-list' ).html( response.data );
	            	});
	            }
	        });

		});

		/**
		 * Select Feeling/Activity Category.
		 **/
		$( document ).on( 'click', '.youzify-feeling-item', function( e ) {

			e.preventDefault();

			// Init Vars.
			var el = $( this ), parent = el.closest( '.youzify-wall-feeling' );

			if ( $( this ).parent().hasClass( 'youzify-list-category-items' ) ) {

				// Select Emoji.
				$.youzify_select_mood_emojis( parent, el.attr( 'data-emoji' ), el.attr( 'data-category-title' ), el.find( '.youzify-item-img' ).css( 'background-image' ) );

				return;
			}

			// Add Category Title.
			parent.find( '.youzify-list-items-title' ).text( el.attr( 'data-category-title' ) );
			parent.find( 'input[name="mood_type"]' ).val( el.attr( 'data-category' ) );

			// Hide Categories.
			parent.find( '.youzify-list-categories' ).fadeOut();
			parent.find( '.youzify-feeling-close-icon' ).addClass( 'youzify-feeling-go-back' );
			parent.find( '.youzify-feeling-close-icon i' ).attr( 'class', 'far fa-arrow-alt-circle-left' );

			// Change Search Form Place Holder.
			parent.find( 'input[name="mood_search"]' ).val( '' ).attr( 'placeholder', el.find( '.youzify-item-description' ).text() );
			parent.find( '.youzify-list-search-icon i' ).attr( 'class', el.find( '.youzify-item-icon i' ).attr( 'class' ) );

			// Show Category.
			var current_category = parent.find( '.youzify-list-category-items[data-category="' + $( this ).attr( 'data-category' ) + '"]' );
			if ( current_category.length == 1 ) {
				current_category.fadeIn();
			} else {
				parent.find( '.youzify-list-submit-button' ).fadeIn();
			}

		});

		/**
		 * Hide Feeling/Activity Box.
		 **/
		$( document ).on( 'click', '.youzify-feeling-close-icon', function() {

			// Init Vars.
			var el = $( this ), parent = el.closest( '.youzify-wall-feeling' );

			// Reset Form.
			parent.find( '.youzify-feeling-submit-button' ).fadeOut();
			parent.find( '.youzify-feeling-search-input' ).val( '' );
			parent.find( '.youzify-feeling-search-input' ).attr( 'placeholder', parent.find( '.youzify-feeling-form' ).attr( 'data-placeholder' ) );
			parent.find( '.youzify-feeling-search-icon i' ).attr( 'class', 'fas fa-search' );

			if ( el.hasClass( 'youzify-feeling-go-back' ) ) {
				el.find( 'i' ).attr( 'class', 'fas fa-times' );
				parent.find( '.youzify-list-categories, .youzify-list-item' ).fadeIn();
				parent.find( '.youzify-list-category-items' ).fadeOut();
				el.removeClass( 'youzify-feeling-go-back' );
				return;
			}

			// Hide Box.
			el.closest( '.youzify-feeling-form' ).fadeOut();

		});

		/**
		 * Edit Feeling / Activity Item.
		 */
		$( document ).on( 'click', '.youzify-wall-feeling .youzify-list-edit-item', function() {

			// Init Vars.
			var el = $( this ), parent = el.closest( '.youzify-wall-feeling' ), selected_items = parent.find( '.youzify-list-selected-items' );

			selected_items.fadeOut( 200, function() {

				parent.find( '.youzify-feeling-search-input' ).val( selected_items.find( '.youzify-item-title' ).text() );

				// Delete Item.
				el.closest( '.youzify-selected-item' ).fadeOut( 200, function() { $( this ).remove() });

				// Display Search Box.
				parent.find( '.youzify-feeling-form' ).fadeIn();

				// Remove Class
				parent.removeClass( 'youzify-activity-feeling-selected' );

			});

		});

		/**
		 * Delete Feeling / Activity Item.
		 */
		$( document ).on( 'click', '.youzify-wall-feeling .youzify-list-delete-item', function() {

			// Init Vars.
			var el = $( this ), parent = el.closest( '.youzify-wall-feeling' ), selected_items = parent.find( '.youzify-list-selected-items' );


			selected_items.fadeOut( 200, function() {

				// Reset Selected Item Text.
				selected_items.find( '.youzify-item-title' ).text( '' );

				// Delete Item.
				el.closest( '.youzify-selected-item' ).fadeOut( 200, function() { $( this ).remove() });

				parent.find( '.youzify-feeling-close-icon' ).trigger( 'click' );

				// Remove Class
				parent.removeClass( 'youzify-activity-feeling-selected' );

			});

		});

		/**
		 * Enter User Activity Value.
		 **/
		$( document ).on( 'click', '.youzify-feeling-submit-button', function() {

			// Init Vars.
			var parent = $( this ).closest( '.youzify-wall-feeling' ), value = parent.find( '.youzify-feeling-search-input' ).val();

			if ( value.trim() == '' ) {
				return;
			}

			// Select Emoji.
			$.youzify_select_mood_emojis( parent, value, value );

		});

		// Set Enter On Submit Search Button.
		$( document ).on( 'keypress', '.youzify-feeling-search-input', function( e ) {
			if( e.keyCode == 13 ) {
				e.preventDefault();
				var submit = $( this ).next( '.youzify-feeling-submit-button' );
				if ( submit.css( 'display' ) != 'none' ) {
					submit.click();
				}
			}
		});

		/**
		 * Display Selected Item
		 */
		$.youzify_select_mood_emojis = function( parent, value, title, image ) {

			if ( parent.hasClass( 'youzify-activity-feeling-selected' ) ) {
				// alert( 'hhaha');
				// return;
				parent.find( '.youzify-selected-item' ).remove();
			}

			// Add Class.
			parent.addClass( 'youzify-activity-feeling-selected' );

			// Display Selected Element.
			parent.find( '.youzify-feeling-form' ).fadeOut( 0, function() {
				parent.find( '.youzify-feeling-selected-items' ).fadeIn();
			});

			var image_div = ( typeof image === "undefined" || image === null ) ? '' : "<div class='youzify-item-img' style='background-image: " + image + ";'></div>";
			var div_class = ( typeof image === "undefined" || image === null ) ? ' youzify-selected-item-no-image' : '';

			// Parent.
			parent.find( '.youzify-list-selected-items' ).append(
				'<div class="youzify-selected-item youzify-feeling-selected-item' + div_class + '">' + image_div + '<div class="youzify-item-title">' + title + '</div>' +
				'<i class="fas fa-pencil-alt youzify-selected-item-tool youzify-list-edit-item"></i>' +
				'<i class="fas fa-trash-alt youzify-selected-item-tool youzify-list-delete-item"></i>' +
				'<input type="hidden" name="mood_value" value="' + value + '">' +
				'</div>' ).fadeIn();

			// Clear Search Input.
			parent.find( '.youzify-feeling-search-input' ).val( '' );
		}

		/**
		 * Tag Users.
		 **/
		$( document ).on( 'click', '.youzify-wall-tag-user', function() {

			// Init Vars.
			var button = $( this );

			if ( button.hasClass( 'youzify-selected' ) ) {
				return;
			}

			// Add Class.
			button.addClass( 'youzify-selected' );

			// Init Vars
			var parent = button.closest( '.youzify-list-item' );

			$( this ).fadeOut( 200, function(){

				var item = '<div class="youzify-selected-item youzify-tagged-user">' +
						"<div class='youzify-item-img' style='background-image: " + parent.find( '.youzify-item-img' ).css( 'background-image' ) + ";'></div>" +
						'<div class="youzify-item-title">' + parent.find( '.youzify-item-title' ).text() + '</div><i class="fas fa-trash-alt youzify-selected-item-tool youzify-list-delete-item youzify-tagusers-delete-user" data-user-id="' + parent.attr( 'data-user-id' ) + '"></i>' +
						'<input type="hidden" name="tagged_users[]" value="' + parent.attr( 'data-user-id' ) + '">' +
					'</div>';

				parent.closest( '.youzify-wall-tagusers' ).find( '.youzify-tagged-users' ).append( item );

				var users_count = button.closest( '.youzify-wall-tagusers' ).find( '.youzify-tagged-user' ).length;

				var form = button.closest( '.youzify-wall-tagusers' );

				if ( form.find( '.youzify-tagged-user' ).length == 1 ) {
					form.find( '.youzify-tagged-users' ).fadeIn();
				}

			});
		});

		/**
		 * Delete Tagged Users.
		 **/
		$( document ).on( 'click', '.youzify-list-close-icon.youzify-tagusers-close-icon', function() {
			$( this ).closest( '.youzify-tagusers-form' ).fadeOut();
		});

		/**
		 * Delete Tagged Users.
		 **/
		$( document ).on( 'click', '.youzify-tagusers-delete-user', function() {

			// Init Vars.
			var parent = $( this ).parent();
			var form = $( this ).closest( '.youzify-wall-tagusers' );
			var tagged_users = $( this ).closest( '.youzify-tagged-users' );

			// Display select button again.
			form.find( '.youzify-list-item[data-user-id=' + $( this ).attr( 'data-user-id' ) + '] .youzify-wall-tag-user' ).removeClass( 'youzify-selected' ).fadeIn();

			$( this ).parent().fadeOut( 200, function() {
				$( this ).remove();
				var users_count = tagged_users.find( '.youzify-tagged-user' ).length;
				if ( users_count == 0 ) {
					tagged_users.fadeOut();
				}
			});

		});

		/**
		 * Hide Privacy if the post will be added to groups.
		 */
		$( document ).on( 'change', '#whats-new-post-in', function( e ) {

			if ( $( this ).val() != 0 ) {
				$( this ).closest( '.youzify-wall-actions' ).find( 'div.youzify-activity-privacy' ).fadeOut();
			} else {
				$( this ).closest( '.youzify-wall-actions' ).find( 'div.youzify-activity-privacy' ).fadeIn();
			}

		});

		/**
		 * Hide Privacy if the post will be added to groups.
		 */
		$( document ).on( 'click', '.youzify-send-comment', function( e ) {

			e.preventDefault();

			if ( $( this ).hasClass( 'loading' ) ) {
				return;
			}

			var target = $( this ), form, content, form_parent, comment_id, form_id, tmp_id, ajaxdata, ak_nonce,
			new_count,show_all_a, old_icon,activity;
			activity = target.closest('.activity');
			target.addClass( 'loading' );
			old_icon = target.find( 'i' ).attr( 'class' );
			target.find( 'i' ).attr( 'class', 'fas fa-spinner fa-spin' );

			/* Activity comment posting */
			// if ( target.attr('name') === 'ac_form_submit' ) {
				form = target.parents( 'form' );
				form_parent = form.parent();
				form_id = form.attr('id').split('-');

				if ( !form_parent.hasClass('activity-comments') ) {
					tmp_id = form_parent.attr('id').split('-');
					comment_id = tmp_id[1];
				} else {
					comment_id = form_id[2];
				}

				content = activity.find( '#' + form.attr('id') + ' textarea' );

				/* Hide any error messages */
				activity.find( '#' + form.attr('id') + ' div.error').hide();
				target.addClass('loading').prop('disabled', true);
				content.addClass('loading').prop('disabled', true);

				ajaxdata = {
					action: 'new_activity_comment',
					'cookie': youzify_buddypress_get_cookies(),
					'_wpnonce_new_activity_comment': $('#_wpnonce_new_activity_comment').val(),
					'comment_id': comment_id,
					'form_id': form_id[2],
					'post_type' : 'activity_comment',
					'content': content.val()
				};

				// Akismet
				ak_nonce = $('#_bp_as_nonce_' + comment_id).val();
				if ( ak_nonce ) {
					ajaxdata['_bp_as_nonce_' + comment_id] = ak_nonce;
				}

				$.post( ajaxurl, ajaxdata, function(response) {
					target.removeClass('loading');
					content.removeClass('loading');
					target.find( 'i' ).attr( 'class', old_icon );
					/* Check for errors and append if found. */
					if ( response[0] + response[1] === '-1' ) {
						form.append( $( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
					} else {
						var activity_comments = form.parent();
						form.fadeOut( 200, function() {
							if ( 0 === activity_comments.children('ul').length ) {
								if ( activity_comments.hasClass('activity-comments') ) {
									activity_comments.prepend('<ul></ul>');
								} else {
									activity_comments.append('<ul></ul>');
								}
							}

							/* Preceding whitespace breaks output with jQuery 1.9.0 */
							var the_comment = $.trim( response );

							activity_comments.children('ul').append( $( the_comment ).hide().fadeIn( 200 ) );
							form.children('textarea').val('');
							activity_comments.parent().addClass('has-comments');
						} );
						activity.find( '#' + form.attr('id') + ' textarea').val('');

						/* Increase the "Reply (X)" button count */
						new_count = Number( $('#activity-' + form_id[2] + ' a.acomment-reply span').html() ) + 1;
						$('#activity-' + form_id[2] + ' a.acomment-reply span').html( new_count );

						// Increment the 'Show all x comments' string, if present
						show_all_a = activity_comments.closest('.activity-comments').find('.show-all a');

						if ( show_all_a ) {
							var show_count = show_all_a.text().match(/\((.*)\)/);
							if ( show_count ) {
								show_all_a.html( BP_DTheme.show_x_comments.replace( '%d', Number( show_count[1] )  + 1 ) );
							}
						}
					}

					$(target).prop('disabled', false);
					$(content).prop('disabled', false);
				});

				return false;
			// }
		});

		// Submit Comment Form if user hits enter.
		$( document ).on( 'keypress', '.ac-form textarea', function( e ) {
			if ( e.which == 13 && !e.shiftKey ) {
                e.preventDefault();
                $( this ).closest( 'form' ).find( '.youzify-send-comment' ).click();
            }
		});

		function youzify_buddypress_get_cookies() {
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
		 * Check Post Type.
		 **/
	    $.youzify_isPostType = function( form, post_type ) {
	    	if ( post_type == form.find( 'input:radio[name="post_type"]:checked' ).val() ) {
	    		return true;
	    	}
	    	return false;
	    }


		var current_share_button;

		/**
		 * Get Share Activity.
		 **/
		$( document ).on( 'click', '.share-activity', function( e ) {

			e.preventDefault();

			// Check if there's no other share button is clicked on the same time.
			if ( $( '.share-activity.loading' )[0] ) {
				return;
			}

			// Init Vars.
			var $link = $( this ), $form, $form_wrapper,
			activity_id = $link.parents( 'li' ).attr('id').split('-')[1],
			activity_type = $link.attr( 'data-activity-type' ), show_form = true;

			if ( $link.hasClass( 'loading' ) ) {
				return;
			}

			// Add Link Loading Icon.
			$link.addClass( 'loading' );

			// Set Current Buttonn.
			current_share_button = $link;

			// Get Form Wrapper.
			$form_wrapper = $( '#youzify-edit-activity-wrapper');

			// Get Form Data.
			var data = {
				'action': 'youzify_get_share_activity_form',
				'youzify_share_activity_nonce': Youzify.security_nonce,
				'activity_id': activity_id
			};

			// Process Verification.
			$.post( Youzify.ajax_url, data, function( response ) {

	    		// Remove Share Button.
				if ( response.success == false ) {
	    			current_share_button.remove();
					$.youzify_DialogMsg( 'error', response.error );
	    			return;
				}

				// Get Form
	    		var $form = $( response.data.form );

	    		// Append form but Hide it.
	    		$( 'body' ).append( $form.hide() );

	    		var $preview = $( response.data.preview );

	    		$preview.insertAfter( $form.find( 'textarea#whats-new' ) );

	    		// Init Nice Select.
				if ( jQuery.isFunction( $.fn.niceSelect ) ) {
	    			$form.find( 'select:not([multiple="multiple"])' ).niceSelect();
	    		}

				if ( $preview.height() > 150 ) {
					$preview.find( '.youzify-shared-wrapper-container' ).addClass( 'youzify-collapsed-preview' );
	    			$form.find( '.youzify-shared-wrapper' ).append( response.data.show_all );
				}

				$form.find( 'form' ).append( '<input type="hidden" value="activity_share" name="post_type" />');
				$form.find( 'form' ).append( '<input type="hidden" value="' + response.data.activity_id + '" name="secondary_item_id" />');

				// Hide Form Options Tab
				$form.find( '.youzify-wall-options' ).remove();

				if ( response.posts_emojis == 'on' ) {
					show_form = false;
					if ( ! jQuery().Youzify_emojioneArea ) {
				        $( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-emojionearea.min.js' } ).appendTo( 'head' );
				        $( '<link/>', { rel: 'stylesheet', href: Youzify.assets + 'css/youzify-emojionearea.min.css' } ).appendTo( 'head' );
					} else {
						$form.find( '.youzify-wall-textarea' ).Youzify_emojioneArea( {
			                pickerPosition: 'bottom',
			                autocomplete: true,
			                saveEmojisAs : 'image',
			                events: {
			                ready: function () {
			                  this.editor.textcomplete([{
			                      id: 'youzify_mentions',
			                      match: /\B@([\-\d\w]*)$/,
			                      search: function ( term, callback ) {
			                          var mentions = bp.mentions.users;
			                          callback( $.map(mentions, function ( mention ) {
			                          return mention.ID.indexOf( term ) === 0 || mention.name.indexOf( term ) === 0 ? mention : null;
			                      }));
			                      },
			                      template: function ( mention ) {
			                          return '<img src="' + mention.image + '" /><span class="username">@' + mention.ID + '</span><small>' +mention.name+ '</small>';
			                      },
			                      replace: function ( mention ) {
			                          return '@' + mention.ID + '&nbsp;';
			                      },
			                      cache: true,
			                      index: 1
			                   }]);
			                }
			              }
	        			} );
					}

				}

				// Remove Attachment.
				$form.find( '.youzify-wall-upload-btn' ).remove();

				var setIntervalID = setInterval( function() {
			      	if ( show_form == true || ( show_form == false && $form.find( '.youzify-emojionearea' ).get( 0 ) ) ) {
						clearInterval( setIntervalID );

						// Mark Button As laoded.
						$link.attr( 'data-loaded', 'true' );

			    		// Remove Loading Class.
			    		$link.removeClass( 'loading' );

						// Show Form.
						$.youzify_show_modal( $form );
				    };
			    }, 100 );

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		$link.removeClass( 'loading' );

            	// Show Error Message
            	$.youzify_DialogMsg( 'error', Youzify.unknown_error );

				return false;

    		});


		});

		$( document ).on( 'click', '.youzify-show-all-less div', function(){

			var parent = $( this ).parent();

			if ( ! parent.hasClass( 'active' ) ) {
				parent.addClass( 'active' );
				parent.prev( '.youzify-shared-wrapper-container' ).removeClass( 'youzify-collapsed-preview' );
			} else {
				parent.removeClass( 'active' );
				parent.prev( '.youzify-shared-wrapper-container' ).addClass( 'youzify-collapsed-preview' );
			}

		});

        /**
         * Polls Attachments - Open Files Uploader.
         */
        $( document ).on( 'click', '.youzify-wall-item-upload', function( e ) {

            // Load Attachments JS.
            if ( ! youzify_load_attachments ) {
                $( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-attachments.min.js' } ).appendTo( 'head' );
                $( '<link/>', { rel: 'stylesheet', href: Youzify.assets + 'css/youzify-attachments.min.css' } ).appendTo( 'head' );
                youzify_load_attachments = true;
            }

            // Get Attachment.
            var $form = $( this ).closest( '.youzify-attachments' );

            // If Empty.
            if ( $form.find( '.youzify-attachment-item' )[0] ) {
                return false;
            }

            // Triger Click.
            $form.find( '.youzify-upload-attachments' ).click();

            e.preventDefault();

        });

	});

})( jQuery );