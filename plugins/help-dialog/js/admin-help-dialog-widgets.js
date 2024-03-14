jQuery(document).ready(function($) {

	/*************************************************************************************************
	 *
	 *          Misc
	 *
	 ************************************************************************************************/

	$( '.ephd-admin__reload-link' ).on( 'click', function() {
		$( '.ephd-admin__top-panel__item[data-target="' + $( this ).attr( 'href' ).replace( '#', '' ) + '"]' ).trigger( 'click' );
	});

	// Adjust css of Help Dialog preview - bind with delay to have it initialized after frontend events
	/*
	setTimeout( function() {
		$( window ).on( 'resize', function() {
			let preview_content = $( '#ephd-help-dialog' );
			$( '.ephd-wp__widget-preview' ).css( {
				'height': preview_content.css( 'height' ),
				'width': preview_content.css( 'width' ),
			} );
		} );
	}, 50 );
	 */

	// Toggle tooltip for Help Dialog preview
	$( document ).on( 'click', '.ephd-hd-toggle', function() {
		if ( $( '.ephd-hd-toggle' ).hasClass( 'ephd-hd-toggle--on' ) ) {
			$( '.ephd-wp__widget-preview-tooltip' ).hide();
		} else {
			setTimeout( function() {
				$( '.ephd-wp__widget-preview-tooltip' ).show();
			}, 400);
		}
	} );

	// Show message if no Questions were created or all questions have been assigned
	function ephd_check_no_questions_message() {

		let all_questions = $( '.ephd-all-questions-list-container .ephd-faq-question-container' ).length;
		let active_questions = $( '.ephd-all-questions-list-container .ephd-faq-question--active' ).length;

		// Hide all questions
		$( '#ephd-admin__no-question-message, #ephd-admin__assigned-question-message' ).hide();

		// Show No questions message
		if ( all_questions === 0 ) {
			$('#ephd-admin__no-question-message').show();
		}
		// Show all questions have been assigned message
		if ( active_questions === 0 && all_questions > 0 ) {
			$( '#ephd-admin__assigned-question-message' ).show();
		}
	}

	// Powered By notification box
	$( document ).on( 'change', '.ephd-wp__widget-form .ephd-input-group input[name="launcher_powered_by"]', function() {
		ephd_set_power_by_notification();
	});

	function ephd_set_power_by_notification() {
		let notification = $( '.ephd-wp__widget-form #ephd-admin__notification-launcher-powered-by' );
		let input = $( '.ephd-wp__widget-form .ephd-input-group input[name="launcher_powered_by"]:checked' );
		if ( input.val() == 'hide' ) {
			notification.show();
		} else {
			notification.hide();
		}
	}

	// order FAQs
	function ephd_enable_sortable_for_faqs() {
		$( '.ephd-hd-faq__faqs-container' ).sortable({
			axis: 'y',
			forceHelperSize: true,
			forcePlaceholderSize: true,
			placeholder: 'ephd-sortable-placeholder',
			update: function( event, ui ) {
				let ordered_faqs = new Array();
				$( this ).find( '.ephd-hd-faq__list__item-container' ).each(function(e){
					ordered_faqs.push( $( this ).data('id') );
				});
				$( '.ephd-wp__widget-form--active' ).find( '[name="faqs_sequence"]' ).val( ordered_faqs.join( ',' ) );
			}
		});
	}

	// Open popup box from admin widget (ephd-ap-widget)
	$( document.body ).on( 'click', '.ephd-admin__widget-preview .ephd-admin__widget-preview__sub-items-btn', function() {
		$( this ).closest( '.ephd-admin__widget-preview__content-list' ).find( '.ephd-admin__widget-details-popup' ).addClass( 'ephd-admin__widget-details-popup--active' );
		return false;
	} );

	// Open locations popup box
	$( document.body ).on( 'click', '.ephd-wp__selected-locations-popup .ephd-wp__popup-show-btn', function() {
		$( this ).closest( '.ephd-wp__selected-locations-popup' ).find( '.ephd-admin__widget-details-popup' ).addClass( 'ephd-admin__widget-details-popup--active' );
		return false;
	} );

	// Popup accept / close button
	$( document.body ).on( 'click', '.ephd-admin__widget-details-popup__overlay, .ephd-admin__widget-details-popup__accept-btn', function() {
		$( '.ephd-admin__widget-details-popup' ).removeClass( 'ephd-admin__widget-details-popup--active' );
	});

	// Open widget page by hash parameters
	setTimeout( function() {
		let hash_parameters = ephd_get_hash_parameters();
		if ( typeof hash_parameters['widget'] !== 'undefined' ) {
			$( '.ephd-admin__widget-preview--' + hash_parameters['widget'] + ' input.ephd_edit_widget' ).trigger( 'click' );
		}
	}, 50);

	// Get hash parameters (format: #a=1&b=2)
	function ephd_get_hash_parameters() {
		let hash = window.location.hash.substr(1);

		let hash_parameters = hash.split( '&' ).reduce( function ( res, item ) {
			let parts = item.split( '=' );
			res[parts[0]] = parts[1];
			return res;
		}, {} );

		return hash_parameters;
	}

	// Disable Help Dialog search input field
	function ephd_disable_hd_search_input() {
		$( '#ephd-help-dialog #ephd-hd__search-terms' ).prop( 'disabled', true );
	};

	/*************************************************************************************************
	 *
	 *          Widget Form - Pages Tab
	 *
	 ************************************************************************************************/

	function show_widget_search_loader( el ) {
		let $wrap = el.closest( '.ephd-wp__locations-list-search-body' );

		if ( ! $wrap.length ) {
			return;
		}

		$wrap.find( '.ephd-wp__locations-list-input-wrap' ).addClass( 'ephd-wp__locations-list-input-wrap--loader' );
	}

	function hide_search_loader( el ) {
		let $wrap = el.closest( '.ephd-wp__locations-list-search-body' );

		if ( ! $wrap.length ) {
			return;
		}

		$wrap.find( '.ephd-wp__locations-list-input-wrap' ).removeClass( 'ephd-wp__locations-list-input-wrap--loader' );
	}

	// Search input
	$( document.body ).on( 'input click', '.ephd-wp__widget-form .ephd-wp__locations-list-input', function( e ){

		let form = $( this ).closest( '.ephd-wp__widget-form' ),
			search_input = $( this ),
			search_value = search_input.val(),
			locations_type = search_input.data( 'post-type' );

		setTimeout( function() {
			if ( search_value !== search_input.val() ) {
				return;
			}

			let postData = {
				action: 'ephd_search_locations',
				_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
				locations_type: locations_type,
				search_value: search_value,
				excluded_ids: get_excluded_widget_locations( form ),
				location_page_filtering: $('[name="location_page_filtering"]:checked').val(),
				widget_id: form.find( '[name="widget_id"]' ).val(),
				location_language_filtering: $( '[name="location_language_filtering"]' ).val(),
			};

			show_widget_search_loader( search_input );

			ephd_send_ajax( postData, function( response ) {
				hide_search_loader( search_input );
				if ( ! response.error && typeof response.locations != 'undefined' ) {
					form.find( '.ephd-wp__locations-list-select--' + locations_type + ' .ephd-wp__found-locations-list' ).html( response.locations ).show();
				}
			}, false, false, undefined, 'no-loader' );
		}, 500 );
	} );

	// Add Location to the selected Locations list when click on any Location inside found Locations list
	$( document ).on( 'click', '.ephd-wp__widget-form .ephd-wp__found-locations-list li', function( e ) {
		e.stopPropagation();
		// Do not add Location if it is already added to another widget
		if ( $( this ).data('assigned_widget_id') && $( this ).data('assigned_widget_id') != $( this ).closest( '.ephd-wp__widget-form' ).find( '[name="widget_id"]' ).val() ) {
			return false;
		}
		$( this ).appendTo( $( this ).parent().parent().find( '.ephd-wp__selected-locations-list' ) );

		ephd_update_selected_locations_visibility();
	} );

	// Hide list of found Locations when click outside the list
	$( document ).on( 'click', function() {
		$( '.ephd-wp__found-locations-list' ).html( '' ).hide();
	});

	// Remove Location from selected list if clicked on it
	$( document ).on( 'click' , '.ephd-wp__widget-form .ephd-wp__selected-locations-list li', function() {

		let location_id = $( this ).data( 'id' );

		// Remove element from widget page and from popup dialog
		$( '.ephd-wp__widget-form .ephd-wp__selected-locations-list li[data-id="' + location_id  + '"]' ).remove();

		ephd_update_selected_locations_visibility();
	} );

	/**
	 * Update selected locations visibility
	 */
	function ephd_update_selected_locations_visibility() {

		// Max visible locations
		let limit = 15;

		$( '.ephd-wp__widget-form .ephd-wp__selected-locations-list' ).each( function(){

			let count = 0;
			let $list_items = $( this ).find( 'li' );

			// Update list item visibility
			$list_items.each( function(){
				if ( ++count <= limit ) {
					$( this ).removeClass( 'ephd-wp__location--hidden' )
				} else {
					$( this ).addClass( 'ephd-wp__location--hidden' )
				}
			} );

			let $view_all_button = $( this ).closest( '.ephd-wp__selected-locations-popup' ).find( 'a' );

			// Update 'View All' button visibility
			if ( $list_items.length <= limit ) {
				$view_all_button.hide();
			} else {
				$view_all_button.show();
			}
		} );
	}

	// Trigger target tab if clicked on button inside notification message
	$( document ).on( 'click', '.ephd-notification-box-basic__body__desc .ephd-hd__wizard-link', function() {
		$( '.ephd-admin__top-panel__item[data-target="' + $( this ).data( 'target' ) + '"]' ).trigger( 'click' );
	});

	/*************************************************************************************************
	 *
	 *          Widgets page
	 *
	 ************************************************************************************************/

	// Load form to edit Widget
	$( document ).on ( 'click', '.ephd-admin__widget-preview .ephd_edit_widget', function( e ) {
		e.preventDefault();

		let widget_id = $( this ).data( 'id' );
		let widget_count = $( this ).closest( '.ephd-admin__section-wrap' ).find( '.ephd-admin__widget-preview' ).length;
		widget_count++;

		let widget_name = 'Widget #' + widget_count;

		let postData = {
			action: 'ephd_load_widget_form',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			widget_id: typeof widget_id !== 'undefined' ? widget_id : 0,
			widget_name: widget_name
		};

		ephd_send_ajax( postData, function( response ){

			if ( ! response.error && typeof response.message != 'undefined' ) {

				// Hide elements before show the form
				$( '.ephd-admin__boxes-list__box-btn-wrap' ).hide();

				// Insert new Widget form
				$( '.ephd-wp__widget-form' ).replaceWith( response.widget_form );

				// Update HD preview
				$( '.ephd-wp__widget-preview-content' ).html( response.preview );

				// Hide preview boxes
				$( '.ephd-admin__widget-preview' ).hide();

				// Update Designs inline styles
				$( '#ephd-public-styles-inline-css' ).html( response.demo_styles );

				// Add disabled attribute
				add_input_disabled_attribute();

				// Add edit inputs to hd faqs list
				add_buttons_to_hd_faqs_items();

				// Check search options and add disable attribute
				widget_form_search_fields_activity_toggle();

				ephd_check_no_questions_message();

				ephd_check_questions_filter();

				ephd_set_power_by_notification();

				ephd_enable_sortable_for_faqs();

				ephd_disable_hd_search_input();

				ephd_init_wp_color_picker();

				ephd_add_events_to_hd_preview_tabs();

				check_pages_tab_labels();

				// Open widget tab by hash parameters
				setTimeout( function() {
					let hash_parameters = ephd_get_hash_parameters();
					if ( typeof hash_parameters['tab'] !== 'undefined' ) {
						$( '.ephd-admin__form-tab[data-target="' + hash_parameters['tab'] + '"]' ).trigger( 'click' );
					}
				}, 50);

				// Adjust height of Widget preview - use delay to let CSS/HTML render
				setTimeout( function() {
					$( window ).trigger( 'resize' );
				}, 60);
			}
		} );
	});

	// copy Widget
	$( document ).on ( 'click', '.ephd-admin__widget-preview .ephd_copy_widget', function( e ) {
		e.preventDefault();

		let widget_id = $( this ).data( 'id' );
		let widget_count = $( this ).closest( '.ephd-admin__section-wrap' ).find( '.ephd-admin__widget-preview' ).length;
		widget_count++;

		let widget_name = 'Widget #' + widget_count;

		let postData = {
			action: 'ephd_load_widget_form',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			parent_widget_id: widget_id,
			widget_id: 0,
			widget_name: widget_name,
		};

		ephd_send_ajax( postData, function( response ){

			if ( ! response.error && typeof response.message != 'undefined' ) {

				// Hide elements before show the form
				$( '.ephd-admin__boxes-list__box-btn-wrap' ).hide();

				// Insert new Widget form
				$( '.ephd-wp__widget-form' ).replaceWith( response.widget_form );

				// Hide preview boxes
				$( '.ephd-admin__widget-preview' ).hide();

				// Open widget tab by hash parameters
				setTimeout( function() {
					let hash_parameters = ephd_get_hash_parameters();
					if ( typeof hash_parameters['tab'] !== 'undefined' ) {
						$( '.ephd-admin__form-tab[data-target="' + hash_parameters['tab'] + '"]' ).trigger( 'click' );
					}
				}, 50);

				// Auto save new added widget as draft - preview and styles are controlled by its click handler
				setTimeout( function() {
					$( '.ephd-wp__widget-form .ephd-wp__widget-action__draft-btn' ).trigger( 'click' );
				}, 50);
			}
		} );
	});

	// Create or Update Widget
	$( document ).on( 'click', '.ephd-wp__widget-form .ephd-wp__widget-action__publish-btn, .ephd-wp__widget-form .ephd-wp__widget-action__draft-btn', function( e ) {
		e.preventDefault();

		let form = $( this ).closest( '.ephd-wp__widget-form' );

		// Set widget status for widget
		if ( $( this ).hasClass( 'ephd-wp__widget-action__publish-btn' ) ) {
			form.find( '[name="widget_status"]' ).val( 'published' );
		}
		if ( $( this ).hasClass( 'ephd-wp__widget-action__draft-btn' ) ) {
			form.find( '[name="widget_status"]' ).val( 'draft' );
		}

		let postData = get_widget_form_data( form, 'ephd_update_widget' );
		let active_tab = form.find( '.ephd-admin__form-tab--active' ).data( 'target' );

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );

				// Update Widget form
				form.replaceWith( response.widget_form );

				// Update preview of front-end Widget
				$( '.ephd-wp__widget-preview-content' ).html( response.preview );

				// Update Designs inline styles
				$( '#ephd-public-styles-inline-css' ).html( response.demo_styles );

				if ( $( '.ephd-admin__widget-preview--' + response.widget_id ).length ) {
					// Update preview box for Widget
					$( '.ephd-admin__widget-preview--' + response.widget_id ).replaceWith( response.widget_preview );
				} else {
					// Render preview box for new Widget
					$( '.ephd-admin__section-wrap__widgets' ).append( response.widget_preview );
				}

				// Hide preview boxes
				$( '.ephd-admin__widget-preview' ).hide();

				// Set active tab
				$( '.ephd-admin__form-tab[data-target="' + active_tab + '"]' ).trigger( 'click' );

				// Add disabled attribute
				add_input_disabled_attribute();

				// Add edit inputs to hd faqs list
				add_buttons_to_hd_faqs_items();

				// Check search options and add disable attribute
				widget_form_search_fields_activity_toggle();

				ephd_check_no_questions_message();

				ephd_check_questions_filter();

				ephd_set_power_by_notification();

				ephd_enable_sortable_for_faqs();

				ephd_disable_hd_search_input();

				ephd_init_wp_color_picker();

				ephd_add_events_to_hd_preview_tabs();

				check_pages_tab_labels();

				// Adjust height of Widget preview - use delay to let CSS/HTML render
				setTimeout( function() {
					$( window ).trigger( 'resize' );
				}, 50);
			}
		} );

		return false;
	});

	// Delete Widget - only call delete dialog
	$( document ).on( 'click', '.ephd-wp__delete-widget-wrap input.ephd_delete_widget', function( e ) {
		$( '#ephd-wp__delete-widget-confirmation' ).addClass( 'ephd-dialog-box-form--active' );
	});

	// Delete Widget by press on confirmation button
	$( document ).on( 'submit', '#ephd-wp__delete-widget-confirmation', function( e ) {
		e.preventDefault();

		let confirmation_form = $( this ),
			form = $( '.ephd-wp__widget-form' ),
			widget_id = form.find( '[name="widget_id"]' ).val(),
			postData = {
			action: 'ephd_delete_widget',
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			widget_id: widget_id
		};

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' ) {

				// Show success message
				ephd_show_success_notification( response.message );

				// Hide forms
				confirmation_form.removeClass( 'ephd-dialog-box-form--active' );
				form.removeClass( 'ephd-wp__widget-form--active' );

				// Show preview boxes
				$( '.ephd-admin__widget-preview' ).show();

				// Remove preview box for the deleted Widget
				$( '.ephd-admin__widget-preview--' + widget_id ).fadeOut( function() {
					$( this ).remove();
				});

				// Show elements when hide the form
				$( '.ephd-admin__boxes-list__box-btn-wrap' ).show();
			}
		} );

		return false;
	});

	// Cancel form to edit/create Widget
	$( document ).on( 'click', '.ephd_cancel_widget', function( e ) {
		e.preventDefault();

		$( '.ephd-wp__widget-form' ).removeClass( 'ephd-wp__widget-form--active' );
		$( '.ephd-wp__widget-preview' ).hide();
		$( '.ephd-admin__widget-preview' ).show();
		$( '.ephd-admin__boxes-list__box-btn-wrap' ).show();
		return false;
	});

	// Options for Save Widget button
	$( document ).on( 'click', '.ephd-wp__widget-action__save-options-toggle', function() {
		$( this ).closest( '.ephd-wp__widget-form' ).find( '.ephd-wp__widget-action__save-options-list' ).toggle();
	});

	// Switch tabs inside Widget form
	$( document ).on( 'click', '.ephd-wp__widget-form .ephd-admin__form-tab', function() {

		let target_key = $( this ).data( 'target' ),
			parent_wrap = $( this ).closest( '.ephd-wp__widget-form' );

		parent_wrap.find( '.ephd-admin__form-tab' ).removeClass( 'ephd-admin__form-tab--active' );
		parent_wrap.find( '.ephd-admin__form-tab-wrap' ).removeClass( 'ephd-admin__form-tab-wrap--active' );

		$( this ).addClass( 'ephd-admin__form-tab--active' );
		parent_wrap.find( '.ephd-admin__form-tab-wrap--' + target_key ).addClass( 'ephd-admin__form-tab-wrap--active' );

		// Show Help Dialog preview only for tabs which use it
		if ( $( this ).data( 'preview' ) ) {

			let $wrap = $('.ephd-wp__widget-preview');
			let $toggler = $wrap.find('.ephd-hd-toggle');
			$wrap.show();

			// Show opened widget without toggle
			if ( $( this ).data( 'preview' ) == 1 ) {
				$wrap.find( '.ephd-wp__widget-preview-page' ).removeClass( 'ephd-wp__widget-preview-page--active' );
				$wrap.removeClass('ephd-wp__widget-preview--closed');

				// Show needed tab in preview depends on target key of the tab
				open_corresponding_widget_preview_tab( target_key );

				if ( !$toggler.hasClass('ephd-hd-toggle--on') ) {
					$toggler.trigger('click');
					setTimeout( function() {
						$( '.ephd-wp__widget-preview-tooltip' ).show();
					}, 100 );
				}
			}

			// Show toggle without with closed widget
			if ( $( this ).data( 'preview' ) == 2 ) {
				$wrap.find( '.ephd-wp__widget-preview-page' ).addClass( 'ephd-wp__widget-preview-page--active' );
				$wrap.addClass('ephd-wp__widget-preview--closed');
				if ( $toggler.hasClass('ephd-hd-toggle--on') ) {
					$toggler.trigger('click');
				}
			}

			let active_tab = $wrap.find('.ephd-hd-tab--active').data('ephd-target-tab');
			$wrap.find('.ephd-wp__widget-preview-tooltip>div').hide();
			$wrap.find('.ephd-wp__widget-preview-tooltip--' + active_tab ).show();

			// Adjust height of Widget preview - use delay to let CSS/HTML render
			setTimeout( function() {
				$( window ).trigger( 'resize' );
			}, 50);

		} else {
			$( '.ephd-wp__widget-preview' ).hide();
		}

		check_message_mode();
	});

	// Switch sub-tabs inside Widget form
	$( document ).on( 'click', '.ephd-wp__widget-form .ephd-admin__form-sub-tab', function() {

		let target_key = $( this ).data( 'target' ),
			parent_wrap = $( this ).closest( '.ephd-wp__widget-form' );

		parent_wrap.find( '.ephd-admin__form-sub-tab' ).removeClass( 'ephd-admin__form-sub-tab--active' );
		parent_wrap.find( '.ephd-admin__form-sub-tab-content' ).removeClass( 'ephd-admin__form-sub-tab-content--active' );

		$( this ).addClass( 'ephd-admin__form-sub-tab--active' );
		parent_wrap.find( '.ephd-admin__form-sub-tab-content--' + target_key ).addClass( 'ephd-admin__form-sub-tab-content--active' );
	});

	// Update demo preview when any checkbox was clicked on Search tab
	$( document ).on( 'click', '.ephd-wp__widget-form .ephd-admin__form-tab-wrap--search input[type="checkbox"]', function() {
		if ( $( this ).attr( 'name' ) === 'search_option' ) {
			widget_form_search_fields_activity_toggle();
		}
	} );

	// Update demo preview when any color was changed on Colors tab
	let colorPickerUpdateTimeout;
	$( document ).on( 'change', '.ephd-wp__widget-form .ephd-admin__form-tab-wrap--colors .ekb-color-picker input[type="text"]', function() {
		// prevent multiple triggering when moving the cursor in the color palette
		clearTimeout( colorPickerUpdateTimeout );
		colorPickerUpdateTimeout = setTimeout( function( obj ) {
			update_preview( obj );
		}, 300, this );
	} );

	// Toggle search fields activity
	function widget_form_search_fields_activity_toggle() {

		let search_option = $( '.ephd-wp__widget-form .ephd-admin__form-tab-wrap--search input[name="search_option"]' );

		let search_posts = $( '.ephd-wp__widget-form .ephd-admin__form-tab-wrap--search input[name="search_posts"]' );
		let search_kb = $( '.ephd-wp__widget-form .ephd-admin__form-tab-wrap--search select[name="search_kb"]' );

		if ( $( search_option ).prop( 'checked' ) === true ) {
			search_posts.parent().removeClass( 'ephd-admin__input-disabled' );
			search_kb.parent().removeClass( 'ephd-admin__input-disabled' );

			search_posts.prop( 'disabled', false );
			search_kb.prop( 'disabled', false );
		} else {
			search_posts.parent().addClass( 'ephd-admin__input-disabled' );
			search_kb.parent().addClass( 'ephd-admin__input-disabled' );

			search_posts.prop( 'disabled', true );
			search_kb.prop( 'disabled', true );
		}
	}

	// Pre-made Designs buttons check icon
	$( document ).on( 'click', '.ephd-wp__widget-form .ephd-wp__options-container--premade-designs input[type="radio"]', function() {
		$( this ).closest( '.ephd-wp__widget-form' ).find( '.ephd-btn-checked-icon' ).removeClass( 'ephd-btn-checked-icon--active' );
		$( this ).parent().find( '.ephd-btn-checked-icon' ).addClass( 'ephd-btn-checked-icon--active' );
	} );

	// Update demo preview when any select option was changed on Appearance tab -> Copy -> Copy Design From
	$( document ).on( 'change', '.ephd-wp__widget-form .ephd-wp__options-container--copy-design-from select', function() {
		let form = $( this ).closest( '.ephd-wp__widget-form' );
		form.find( '[name="colors_set"]' ).prop( 'checked', false );  // unselect radio button on Predefined Colors option to apply the selected Design
		form.find( '[name="dialog_width"]' ).prop( 'checked', false );  // unselect radio button on Pre-made Designs option to apply the selected Design
		update_preview( this );
	} );

	// Update demo preview when any select option was changed on Appearance tab -> Copy -> Copy Design To
	$( document ).on( 'change', '.ephd-wp__widget-form .ephd-wp__options-container--copy-design-to select', function() {

		let widgetsList = $( this ),
			form = widgetsList.closest( '.ephd-wp__widget-form' ),
			postData = {
				action: 'ephd_copy_design_to',
				_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
				current_design_id: form.find( '[name="design_id"]' ).val(),
				target_design_id: $( this ).val()
			};

		ephd_send_ajax( postData, function( response ) {
			if ( ! response.error && typeof response.message != 'undefined' ) {

				// Show success message
				ephd_show_success_notification( response.message );

				// Clear selection in the dropdown
				widgetsList.val( widgetsList.data( 'value' ) );
			}
		} );
	} );

	// add disabled attribute
	function add_input_disabled_attribute(){
		$( '.ephd-wp__widget-form .ephd-admin__input-disabled' ).each( function(){
			$( this ).find( 'input, select, textarea' ).prop( 'disabled', true );
		});
	}

	// Callback function to execute when mutations are observed to update previews after editor was closed
	let body_mutation_callback = function(mutationsList) {
		for(let mutation of mutationsList) {
			if ( mutation.removedNodes.length && typeof mutation.removedNodes[0].id !== 'undefined' && mutation.removedNodes[0].id == 'ephd-editor-popup' ) {
				update_preview( $('.ephd-wp__widget-form--active') );
			}
		}
	};

	// Create an observer instance linked to the callback function
	let body_observer = new MutationObserver(body_mutation_callback);

	// Start observing the target node for configured mutations
	body_observer.observe( document.getElementsByTagName('body')[0], { childList: true });

	// Handle preview update on input changes
	$(document).on('change', '.ephd-admin__input-update_preview input, .ephd-admin__input-update_preview select, .ephd-admin__input-update_preview textarea', function() {
		update_preview( this );
	});

	function update_preview( element ) {

		ephd_check_no_questions_message();

		// if disabled field
		if ( $( element ).closest( '.ephd-admin__input-disabled' ).length > 0 ) {
			e.preventDefault();
			return false;
		}

		let form = $( element ).closest( '.ephd-wp__widget-form--active' ),
			postData = get_widget_form_data( form, 'ephd_update_preview' ),
			active_tab = $('.ephd-wp__widget-preview').find('.ephd-hd-tab--active').data('ephd-target-tab');

		ephd_send_ajax( postData, function( response ) {
			if ( ! response.error && typeof response.preview != 'undefined' ) {

				// Update preview
				$( '.ephd-wp__widget-preview-content' ).html( response.preview );

				// Define preview size
				$( '.ephd-wp__widget-preview' ).attr( 'data-preview-size', postData.dialog_width );

				// Update Styles
				$( '#ephd-public-styles-inline-css' ).html( response.demo_styles );

				ephd_preview_tab_click( $('[data-ephd-target-tab="' + active_tab + '"]') );

				// Adjust height of Widget preview - use delay to let CSS/HTML render
				setTimeout( function() {
					$( window ).trigger( 'resize' );
				}, 50);

				add_buttons_to_hd_faqs_items();

				// Update sorting
				ephd_enable_sortable_for_faqs();

				ephd_disable_hd_search_input();

				ephd_add_events_to_hd_preview_tabs();

				// Open corresponding tab on Widget preview if required
				if ( $( '.ephd-admin__form-tab--active' ).data( 'preview' ) == 1 ) {
					open_corresponding_widget_preview_tab( $( '.ephd-admin__form-tab--active' ).data( 'target' ) );
				}
			}
		}, false, false, undefined, $( '.ephd-wp__widget-preview-content' ) );
	}

	// Widget form data
	function get_widget_form_data( form, action ) {

		return {
			action: action,
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,

			/** Internal Settings */
			widget_id: form.find( '[name="widget_id"]' ).val(),
			widget_status: form.find( '[name="widget_status"]' ).val(),
			initial_message_id: form.find( '[name="initial_message_id"]' ).val(),
			colors_set: form.find( '[name="colors_set"]:checked' ).val(),

			// Define whether need to return the Widget preview opened or closed
			is_opened: $( '.ephd-hd-toggle' ).hasClass( 'ephd-hd-toggle--on' ) ? 1 : 0,

			/** Locations/Triggers: Pages */
			location_page_filtering: form.find( '[name="location_page_filtering"]:checked' ).val(),
			location_pages_list: get_widget_locations_data( form, 'page' ),
			location_posts_list: get_widget_locations_data( form, 'post' ),
			location_cpts_list: get_widget_locations_data( form, 'cpt' ),
			location_language_filtering: form.find( '[name="location_language_filtering"]' ).val(),

			/** Locations/Triggers: Triggers */
			trigger_delay_toggle: form.find( '[name="trigger_delay_toggle"]:checked' ).val(),
			trigger_delay_seconds: form.find( '[name="trigger_delay_seconds"]' ).val(),
			trigger_scroll_toggle: form.find( '[name="trigger_scroll_toggle"]:checked' ).val(),
			trigger_scroll_percent: form.find( '[name="trigger_scroll_percent"]' ).val(),
			/*trigger_days_and_hours_toggle: form.find( '[name="trigger_days_and_hours_toggle"]:checked' ).val(),
			trigger_days: form.find( '[name="trigger_days"]' ).val(),
			trigger_hours_from: form.find( '[name="trigger_hours_from"]' ).val(),
			trigger_hours_to: form.find( '[name="trigger_hours_to"]' ).val(),*/

			/** Structure: Launcher */
			launcher_mode: form.find( '[name="launcher_mode"]:checked' ).val(),
			launcher_icon: form.find( '[name="launcher_icon"]:checked' ).val(),
			launcher_location: form.find( '[name="launcher_location"]:checked' ).val(),
			launcher_bottom_distance: form.find( '[name="launcher_bottom_distance"]' ).val(),
			launcher_start_wait: form.find( '[name="launcher_start_wait"]' ).val(),
			initial_message_toggle: form.find( '[name="initial_message_toggle"]:checked' ).val(),
			initial_message_text: form.find( '[name="initial_message_text"]' ).val(),
			initial_message_mode: form.find( '[name="initial_message_mode"]:checked' ).val(),
			initial_message_image_url: form.find( '[name="initial_message_image_url"]' ).val(),

			/** Structure: Dialog */

			/**  Structure: Search */
			search_option: form.find( '[name="search_option"]:checked' ).length ? 'show_search' : 'hide_search',
			search_posts: form.find( '[name="search_posts"]:checked' ).length ? 'on' : 'off',
			search_kb: form.find( '[name="search_kb"]' ).val(),

			/**  Structure: General */
			widget_name: form.find( '#widget_name' ).val(),

			/** Main Features: Chat */
			display_channels_tab: form.find( '[name="display_channels_tab"]:checked' ).val(),

			channel_phone_toggle: form.find( '[name="channel_phone_toggle"]:checked' ).val(),
			channel_phone_country_code: form.find( '[name="channel_phone_country_code"]' ).val(),
			channel_phone_number: form.find( '[name="channel_phone_number"]' ).val(),
			channel_phone_number_image_url: form.find( '[name="channel_phone_number_image_url"]' ).val(),

			channel_whatsapp_toggle: form.find( '[name="channel_whatsapp_toggle"]:checked' ).val(),
			channel_whatsapp_phone_country_code: form.find( '[name="channel_whatsapp_phone_country_code"]' ).val(),
			channel_whatsapp_phone_number: form.find( '[name="channel_whatsapp_phone_number"]' ).val(),
			channel_whatsapp_web_on_desktop: form.find( '[name="channel_whatsapp_web_on_desktop"]:checked' ).val(),
			channel_whatsapp_number_image_url: form.find( '[name="channel_whatsapp_number_image_url"]' ).val(),

			channel_custom_link_toggle: form.find( '[name="channel_custom_link_toggle"]:checked' ).val(),
			channel_custom_link_url: form.find( '[name="channel_custom_link_url"]' ).val(),
			channel_custom_link_image_url: form.find( '[name="channel_custom_link_image_url"]' ).val(),

			/** Main Features: FAQs */
			display_faqs_tab: form.find( '[name="display_faqs_tab"]:checked' ).val(),
			faqs_sequence: form.find( '[name="faqs_sequence"]' ).val().split(','),

			/** Main Features: Contact Form */
			display_contact_tab: form.find( '[name="display_contact_tab"]:checked' ).val(),
			contact_name_toggle: form.find( '[name="contact_name_toggle"]:checked' ).val(),
			contact_subject_toggle: form.find( '[name="contact_subject_toggle"]:checked' ).val(),
			contact_acceptance_checkbox: form.find( '[name="contact_acceptance_checkbox"]:checked' ).val(),
			contact_acceptance_title_toggle: form.find( '[name="contact_acceptance_title_toggle"]:checked' ).val(),

			/** Design: Colors */
			back_text_color: form.find( '[name="back_text_color"]' ).val(),
			back_text_color_hover_color: form.find( '[name="back_text_color_hover_color"]' ).val(),
			back_background_color: form.find( '[name="back_background_color"]' ).val(),
			back_background_color_hover_color: form.find( '[name="back_background_color_hover_color"]' ).val(),
			launcher_background_color: form.find( '[name="launcher_background_color"]' ).val(),
			launcher_background_hover_color: form.find( '[name="launcher_background_hover_color"]' ).val(),
			launcher_icon_color: form.find( '[name="launcher_icon_color"]' ).val(),
			launcher_icon_hover_color: form.find( '[name="launcher_icon_hover_color"]' ).val(),
			background_color: form.find( '[name="background_color"]' ).val(),

			not_active_tab_color: form.find( '[name="not_active_tab_color"]' ).val(),
			tab_text_color: form.find( '[name="tab_text_color"]' ).val(),
			main_title_text_color: form.find( '[name="main_title_text_color"]' ).val(),
			welcome_title_color: form.find( '[name="welcome_title_color"]' ).val(),
			welcome_title_link_color: form.find( '[name="welcome_title_link_color"]' ).val(),

			breadcrumb_color: form.find( '[name="breadcrumb_color"]' ).val(),
			breadcrumb_background_color: form.find( '[name="breadcrumb_background_color"]' ).val(),
			breadcrumb_arrow_color: form.find( '[name="breadcrumb_arrow_color"]' ).val(),
			faqs_qa_border_color: form.find( '[name="faqs_qa_border_color"]' ).val(),
			faqs_question_text_color: form.find( '[name="faqs_question_text_color"]' ).val(),
			faqs_question_background_color: form.find( '[name="faqs_question_background_color"]' ).val(),
			faqs_question_active_text_color: form.find( '[name="faqs_question_active_text_color"]' ).val(),
			faqs_question_active_background_color: form.find( '[name="faqs_question_active_background_color"]' ).val(),
			faqs_answer_text_color: form.find( '[name="faqs_answer_text_color"]' ).val(),
			faqs_answer_background_color: form.find( '[name="faqs_answer_background_color"]' ).val(),
			found_faqs_article_active_tab_color: form.find( '[name="found_faqs_article_active_tab_color"]' ).val(),
			found_faqs_article_tab_color: form.find( '[name="found_faqs_article_tab_color"]' ).val(),
			article_post_list_title_color: form.find( '[name="article_post_list_title_color"]' ).val(),
			article_post_list_icon_color: form.find( '[name="article_post_list_icon_color"]' ).val(),
			single_article_read_more_text_color: form.find( '[name="single_article_read_more_text_color"]' ).val(),
			single_article_read_more_text_hover_color: form.find( '[name="single_article_read_more_text_hover_color"]' ).val(),

			contact_submit_button_color: form.find( '[name="contact_submit_button_color"]' ).val(),
			contact_submit_button_hover_color: form.find( '[name="contact_submit_button_hover_color"]' ).val(),
			contact_submit_button_text_color: form.find( '[name="contact_submit_button_text_color"]' ).val(),
			contact_submit_button_text_hover_color: form.find( '[name="contact_submit_button_text_hover_color"]' ).val(),
			contact_acceptance_background_color: form.find( '[name="contact_acceptance_background_color"]' ).val(),
			channel_phone_color: form.find( '[name="channel_phone_color"]' ).val(),
			channel_phone_hover_color: form.find( '[name="channel_phone_hover_color"]' ).val(),
			channel_label_color: form.find( '[name="channel_label_color"]' ).val(),
			channel_whatsapp_color: form.find( '[name="channel_whatsapp_color"]' ).val(),
			channel_whatsapp_hover_color: form.find( '[name="channel_whatsapp_hover_color"]' ).val(),
			channel_link_color: form.find( '[name="channel_link_color"]' ).val(),
			channel_link_hover_color: form.find( '[name="channel_link_hover_color"]' ).val(),

			/** Design: Labels */
			welcome_title: form.find( '[name="welcome_title"]' ).val(),
			contact_us_top_tab: form.find( '[name="contact_us_top_tab"]' ).val(),
			channel_header_top_tab: form.find( '[name="channel_header_top_tab"]' ).val(),
			channel_header_title: form.find( '[name="channel_header_title"]' ).val(),
			channel_header_sub_title: form.find( '[name="channel_header_sub_title"]' ).val(),
			chat_welcome_text: form.find( '[name="chat_welcome_text"]' ).val(),
			faqs_top_tab: form.find( '[name="faqs_top_tab"]' ).val(),
			welcome_text: form.find( '[name="welcome_text"]' ).val(),
			search_input_label: form.find( '[name="search_input_label"]' ).val(),
			search_input_placeholder: form.find( '[name="search_input_placeholder"]' ).val(),
			article_read_more_text: form.find( '[name="article_read_more_text"]' ).val(),
			search_results_title: form.find( '[name="search_results_title"]' ).val(),
			breadcrumb_home_text: form.find( '[name="breadcrumb_home_text"]' ).val(),
			breadcrumb_search_result_text: form.find( '[name="breadcrumb_search_result_text"]' ).val(),
			breadcrumb_article_text: form.find( '[name="breadcrumb_article_text"]' ).val(),
			found_faqs_tab_text: form.find( '[name="found_faqs_tab_text"]' ).val(),
			found_articles_tab_text: form.find( '[name="found_articles_tab_text"]' ).val(),
			found_posts_tab_text: form.find( '[name="found_posts_tab_text"]' ).val(),
			no_results_found_title_text: form.find( '[name="no_results_found_title_text"]' ).val(),
			protected_article_placeholder_text: form.find( '[name="protected_article_placeholder_text"]' ).val(),
			article_back_button_text: form.find( '[name="article_back_button_text"]' ).val(),
			search_instruction_text: form.find( '[name="search_instruction_text"]' ).val(),
			no_result_contact_us_text: form.find( '[name="no_result_contact_us_text"]' ).val(),
			contact_user_email_text: form.find( '[name="contact_user_email_text"]' ).val(),
			contact_welcome_title: form.find( '[name="contact_welcome_title"]' ).val(),
			contact_welcome_text: form.find( '[name="contact_welcome_text"]' ).val(),
			contact_name_text: form.find( '[name="contact_name_text"]' ).val(),
			contact_subject_text: form.find( '[name="contact_subject_text"]' ).val(),
			contact_comment_text: form.find( '[name="contact_comment_text"]' ).val(),
			contact_acceptance_text: form.find( '[name="contact_acceptance_text"]' ).val(),
			contact_acceptance_title: form.find( '[name="contact_acceptance_title"]' ).val(),
			contact_button_title: form.find( '[name="contact_button_title"]' ).val(),
			contact_success_message: form.find( '[name="contact_success_message"]' ).val(),

			channel_phone_label: form.find( '[name="channel_phone_label"]' ).val(),
			channel_whatsapp_label: form.find( '[name="channel_whatsapp_label"]' ).val(),
			channel_whatsapp_welcome_message: form.find( '[name="channel_whatsapp_welcome_message"]' ).val(),
			channel_custom_link_label: form.find( '[name="channel_custom_link_label"]' ).val(),

			/** Global Settings */
			logo_image_url: form.find( '[name="logo_image_url"]' ).val(),
			//container_desktop_width: form.find( '[name="container_desktop_width"]' ).val(),
			//container_tablet_width: form.find( '[name="container_tablet_width"]' ).val(),
			//tablet_break_point: form.find( '[name="tablet_break_point"]' ).val(),
			mobile_break_point: form.find( '[name="mobile_break_point"]' ).val(),
			tabs_sequence: form.find( '[name="tabs_sequence"]:checked' ).val(),
			preview_post_mode: form.find( '[name="preview_post_mode"]:checked' ).val(),
			preview_kb_mode: form.find( '[name="preview_kb_mode"]' ).length ? form.find( '[name="preview_kb_mode"]:checked' ).val() : '',
			launcher_text: form.find( '[name="launcher_text"]' ).val(),
			launcher_powered_by: form.find( '[name="launcher_powered_by"]:checked' ).val(),
			//kb_article_hidden_classes: form.find( '[name="kb_article_hidden_classes"]' ).val(),
			dialog_width: form.find( '[name="dialog_width"]:checked' ).val(),
			saved_preset_id: form.find( '[name="saved_preset_id"]' ).val(),
			wpml_toggle: form.find( '[name="wpml_toggle"]:checked' ).val(),
		};
	}

	function get_widget_locations_data( form, location_type ) {
		let locations_list = [];
		form.find( '.ephd-wp__locations-list-select--' + location_type + ' .ephd-wp__locations-list-wrap > .ephd-wp__selected-locations-list li' ).each( function() {
			locations_list.push( $( this ).data( 'id' ) );
		} );
		return locations_list;
	}

	function get_excluded_widget_locations( form ) {
		let excluded_pages = get_widget_locations_data( form, 'page' ),
			excluded_posts = get_widget_locations_data( form, 'post' ),
			excluded_cpts = get_widget_locations_data( form, 'cpt' );
		return excluded_pages.concat( excluded_posts ).concat( excluded_cpts );
	}

	// Save 'No Results Content' option on Save button click
	$( document ).on( 'click', '.ephd-wp__tiny-mce-input--no-results-found-content-html .ephd_tiny_mce_input_save', function() {

		let form = $( this ).closest( '.ephd-wp__tiny-mce-input' );

		let postData = {
				action: 'ephd_tiny_mce_input_save',
				_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
				widget_id: form.find( '[name="widget_id"]' ).val(),
				option_name: 'no_results_found_content_html',
				option_value: $( '#wp-no_results_found_content_wpeditor-wrap' ).hasClass( 'tmce-active' ) ? tinymce.activeEditor.getContent() : $( '#no_results_found_content_wpeditor' ).val()
			};

		ephd_send_ajax( postData, function( response ) {
			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );
			}
		} );
	} );

	// Show confirmation pop-up when user clicks 'Launch the visual Editor' in new Widget form
	$( document ).on( 'click', '.ephd-wp__new-widget-form .ephd-wp__editor-link .ephd-wp__widget-form__cta-link', function( e ) {
		e.preventDefault();
		e.stopPropagation();
		$( '#ephd-wp__save-widget-confirmation' ).addClass( 'ephd-dialog-box-form--active' );
		return false;
	});

	// Create Widget by press on confirmation button
	$( document ).on( 'submit', '#ephd-wp__save-widget-confirmation', function( e ) {
		e.preventDefault();
		$( this ).removeClass( 'ephd-dialog-box-form--active' );
		$( this ).closest( '.ephd-wp__new-widget-form' ).find( '.ephd-wp__widget-action__save-btn' ).trigger( 'click' );
		return false;
	});

	// Change fields labels for Pages tab
	function check_pages_tab_labels() {
		let is_include = $('[name=location_page_filtering]:checked').val() == 'include';

		$('.ephd-wp__locations-list-select--page .ephd-wp__locations-list-search-title>span').text( is_include ? ephd_vars.include_pages : ephd_vars.exclude_pages );
		$('.ephd-wp__locations-list-select--post .ephd-wp__locations-list-search-title>span').text( is_include ? ephd_vars.include_posts : ephd_vars.exclude_posts );
		$('.ephd-wp__locations-list-select--cpt .ephd-wp__locations-list-search-title>span').text( is_include ? ephd_vars.include_cpts : ephd_vars.exclude_cpts );

		// not click on the element
		if ( this == window ) {
			return;
		}

		// save selected pages
		$(this).closest('.ephd-admin__form-tab-content-body').find('.ephd-wp__locations-list-wrap').each(function(){
			// create backup blocks
			if ( $(this).find('.ephd-wp__selected-locations-list--backup-include').length == 0 ) {
				$(this).append(`<div class="ephd-wp__selected-locations-list--backup-include"></div>`)
			}

			if ( $(this).find('.ephd-wp__selected-locations-list--backup-exclude').length == 0 ) {
				$(this).append(`<div class="ephd-wp__selected-locations-list--backup-exclude"></div>`)
			}

			// backup
			$(this).find('.ephd-wp__selected-locations-list--backup-' + ( is_include ? 'exclude' : 'include' ) ).html( $(this).find( '.ephd-wp__selected-locations-list' ).html() );

			// restore
			$(this).find( '.ephd-wp__selected-locations-list' ).html( $(this).find('.ephd-wp__selected-locations-list--backup-' + ( is_include ? 'include' : 'exclude' ) ).html() );
		});
	}

	$('body').on('change', '[name=location_page_filtering]', check_pages_tab_labels);

	// Open corresponding tab on Widget preview depending to settings target key
	function open_corresponding_widget_preview_tab( target_key ) {
		if ( ! target_key ) {
			return;
		}
		if ( target_key == 'global-settings' || target_key == 'search' || target_key == 'faqs' ) {
			$('#ephd-hd-faq-tab').trigger('click');
		}
		if ( target_key == 'chat' ) {
			$('#ephd-hd-chat-tab').trigger('click');
		}
		if ( target_key == 'contact-form' ) {
			$('#ephd-hd-contact-tab').trigger('click');
		}
	}
	
	/*************************************************************************************************
	 *
	 *          Question edit functions
	 *
	 ************************************************************************************************/

	let ephd_editor_update_timer = false;

	// Form data for all languages. Each language like object en : { title: '', content: '', id: 0}
	let question_form = {};

	// Last edited language. Need only to show needed tab
	let current_language = '';

	// fill initial data
	if ( $( '.ephd-fp__wp-editor__languages' ).length ) {
		$( '.ephd-fp__wp-editor__language' ).each(function(){
			question_form[$( this ).data( 'slug' )] = {
				title: '',
				content: '',
				faq_id: 0,
				id: 0
			};
		});

		current_language = $( '.ephd-fp__wp-editor__language' ).first().data( 'slug' );

	} else {
		question_form[ephd_vars.default_language] = {
			title: '',
			content: '',
			faq_id: 0,
			id: 0
		};

		current_language = ephd_vars.lang;
	}

	// fill editor with question_form data
	function ephd_update_question_form() {

		// only for openned popup
		if ( ! $( '.ephd-fp__wp-editor' ).hasClass( 'active' ) ) {
			return;
		}

		let editor = tinymce.get( 'ephd-fp__wp-editor' );

		// turn needed tab based on current_language
		if ( $( '.ephd-fp__wp-editor__language-' + current_language ).length ) {
			$( '.ephd-fp__wp-editor__language' ).removeClass( 'ephd-fp__wp-editor__language--active' );
			$( '.ephd-fp__wp-editor__language-' + current_language ).addClass( 'ephd-fp__wp-editor__language--active' );
		}

		// check if language exist (if no - add question)
		if ( typeof question_form[current_language] == 'undefined' ) {
			question_form[current_language] = {
				title: '',
				content: '',
				faq_id: 0,
				id: 0
			};
		}

		// fill the title
		$( '#ephd-fp__wp-editor__question-title' ).val( question_form[current_language].title );

		// fill the editor or text editor tab
		if ( editor && $( '.wp-editor-wrap' ).hasClass( 'tmce-active' ) ) {
			editor.setContent( question_form[current_language].content );
		} else {
			$( '#ephd-fp__wp-editor' ).val( question_form[current_language].content );
		}

		$( '.ephd-characters_left-counter' ).text( question_form[current_language].content.length + '' );
	}

	// get data about the question and fill the form
	function ephd_show_question_form( question_id ) {

		ephd_editor_update_timer = setInterval( ephd_calculate_characters_counter, 1000 );

		// clear question data
		for ( let question_lang in question_form ) {
			question_form[question_lang] = {
				title: '',
				content: '',
				faq_id: 0,
				id: 0
			};
		}

		// new question
		if ( typeof question_id == 'undefined' || ! question_id ) {

			// show the popup
			$( '.ephd-fp__wp-editor' ).addClass( 'active' );
			ephd_update_question_form();

			return;
		}

		// get existing question data to fill wp editor
		let postData = {
			action: 'ephd_get_question_data',
			question_id: question_id,
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce
		};

		ephd_send_ajax( postData, function( response ){

			if ( ! response.error && typeof response.data != 'undefined' ) {

				// fill question_form first
				for ( let slug in response.data ) {
					question_form[slug] = response.data[slug];
				}

				if ( typeof response.language != 'undefined' && response.language ) {
					current_language = response.language;
				}

				$( '.ephd-fp__wp-editor' ).addClass( 'active' );
				ephd_update_question_form();
			}
		} );
	}

	function ephd_calculate_characters_counter() {

		let question_title = $( '#ephd-fp__wp-editor__question-title' );
		if ( question_title.length ) {

			let question_length = question_title.val().length;

			if ( question_length > 200 ) {
				$( '.ephd-fp__wp-editor__question .ephd-characters_left-counter' ).text( 200 );
				question_title.val( question_title.val().substring( 0, 200 ) );
			} else {
				$( '.ephd-fp__wp-editor__question .ephd-characters_left-counter' ).text( question_length );
			}
		}

		if ( $( '#ephd-fp__wp-editor' ).length ) {

			let editor = tinymce.get( 'ephd-fp__wp-editor' );
			let answer = '';

			if ( editor && $( '.wp-editor-wrap' ).hasClass( 'tmce-active' ) ) {
				answer = editor.getContent();
			} else {
				answer = $( '#ephd-fp__wp-editor' ).val();
			}

			if ( answer.length > 1500 ) {
				answer = answer.substring( 0, 1500 );

				if ( editor ) {
					editor.setContent( answer );
				}

				$( '#ephd-fp__wp-editor' ).val( answer );
			}

			$( '.ephd-fp__wp-editor__answer .ephd-characters_left-counter' ).text( answer.length );

		}
	}

	// hide editor
	$( '.ephd__help_editor__action__cancel, .ephd-fp__wp-editor__overlay' ).on( 'click', function(){
		hide_ai_help_sidebar();
		clearInterval( ephd_editor_update_timer );
		return false;
	});

	// save question from the popup with wp editor
	$( '#ephd-fp__article-form' ).on( 'submit', function( e ){

		e.preventDefault();
		ephd_calculate_characters_counter();
		save_question_form_to_object();

		// check if default tab filled
		if ( typeof question_form[ephd_vars.default_language] == 'undefined' ) {
			// something went wrong and default language not exist
			ephd_show_error_notification( ephd_vars.reload_try_again );
			return false;
		}

		if ( ! question_form[ephd_vars.default_language].title ) {
			ephd_show_error_notification( ephd_vars.empty_default_language );
			$( '.ephd-fp__wp-editor__language-' + ephd_vars.default_language ).trigger( 'click' );
			return false;
		}

		let postData = {
			action: 'ephd_save_question_data',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			faqs_id: $( '#faqs_id' ).val(),
			direction: 'right',
			question_languages: []
		};

		// set questions data in the way that allows to sanitize fields separately
		for ( let lang in question_form ) {
			postData.question_languages.push( lang );
			postData['question_id_' + lang] = question_form[lang].id;
			if ( ! postData['question_faq_id'] ) {
				postData['question_faq_id'] = question_form[lang].faq_id;
			}
			postData['question_title_' + lang] = question_form[lang].title;
			postData['question_content_' + lang] = question_form[lang].content;
		}

		ephd_send_ajax( postData, function( response ){

			if ( ! response.error && typeof response.message != 'undefined' ) {

				ephd_show_success_notification( response.message );

				response.data.forEach ( function( question ) {
					// change article title in the list
					if ( $( '.ephd-faq-question--' + question.faq_id ).length ) {
						$( '.ephd-faq-question--' + question.faq_id + ' .ephd-faq-question__text' ).text( question.title );

						// add article to the list
					} else {
						$( question.html ).appendTo( '.ephd-all-questions-list-container' ).addClass( 'ui-sortable-handle' );
					}
				});

				$( '.ephd-fp__wp-editor' ).removeClass( 'active' );
				hide_ai_help_sidebar();

				ephd_sort_all_articles();
				ephd_check_no_questions_message();
				ephd_check_questions_filter();
				update_faqs_preview();
			}
		} );

		return false;
	});

	// save form data to object
	function save_question_form_to_object() {
		// save from tinymce to textarea
		tinyMCE.triggerSave();

		// save title
		question_form[current_language].title = $( '#ephd-fp__wp-editor__question-title' ).val();

		// content
		question_form[current_language].content = $( '#ephd-fp__wp-editor' ).val();
	}

	$( '.ephd-fp__wp-editor__language' ).on( 'click', function(){
		save_question_form_to_object();
		current_language = $( this ).data( 'slug' );
		ephd_update_question_form();
	});

	// add new question button
	$( document ).on( 'click', '#ephd-fp__add_new_question', function( e ) {
		e.preventDefault();
		ephd_show_question_form();
		return false;
	});

	// edit question button
	$( document ).on( 'click', '.ephd-faq-question__edit', function() {
		ephd_show_question_form( $( this ).closest( '.ephd-faq-question' ).data( 'id' ) );
	});

	// search input on the  top of the all questions list
	$( document ).on( 'change keyup', '#ephd_all_articles_filter', function() {

		let val = $( this ).val().toLowerCase().trim();
		let current_questions = $( '.ephd-wp__widget-form--active' ).find( '[name="faqs_sequence"]' ).val().split( ',' );

		$( '.ephd-all-questions-list-container .ephd-faq-question-container' ).each( function() {

			if ( current_questions.includes( $( this ).data( 'id' ).toString() ) ) {
				return;
			}

			let title = $( this ).find( '.ephd-faq-question__text' ).text();

			if ( ! val.length || ~ title.toLowerCase().indexOf( val ) ) {
				$( this ).addClass( 'ephd-faq-question--active' );
			} else {
				$( this ).removeClass( 'ephd-faq-question--active' );
			}
		});

		ephd_check_questions_filter();
	});

	// show/hide filter and no results text for all questions block
	function ephd_check_questions_filter() {

		// have visible items in the list
		if ( $( '.ephd-all-questions-list-container .ephd-faq-question-container.ephd-faq-question--active' ).length ) {
			$( '.ephd__top-section__filter label, #ephd_all_articles_filter' ).removeClass( 'ephd__top-section__filter--disabled' );

		// have no visible items but user type something in the search
		} else if( $( '#ephd_all_articles_filter' ).val() ) {
			$( '.ephd__top-section__filter label, #ephd_all_articles_filter' ).removeClass( 'ephd__top-section__filter--disabled' );

		// no visible items and nothing in the search
		} else {
			$( '.ephd__top-section__filter label, #ephd_all_articles_filter' ).addClass( 'ephd__top-section__filter--disabled' );
		}
	}

	// trash button on all articles list. Only call delete dialog
	$( document ).on( 'click', '.ephd-faq-question__delete', function() {
		let id = $( this ).closest( '.ephd-faq-question-container' ).data( 'id' );

		if ( typeof id == 'undefined' || ! id ) {
			ephd_show_error_notification( ephd_vars.reload_try_again );
			return;
		}

		$( '#ephd-fp_delete-question-confirmation-id' ).val( id );

		// Show assigned widgets
		let confirm_dialog = $( '#ephd-fp_delete-question-confirmation' );
		let widget_id = $( '.ephd-wp__widget-form--active input[name="widget_id"]' ).val();

		$( '.ephd-admin__confirm-dialog-assigned-widgets' ).hide();

		confirm_dialog.find( '.ephd-admin__confirm-dialog-assigned-widget' ).each( function() {
			let faq_sequence = $( this ).data( 'faq-sequence' ).toString().split( ',' );
			if ( faq_sequence.includes( id.toString() ) && $( this ).data( 'widget-id' ).toString() !== widget_id.toString() ) {
				$( '.ephd-admin__confirm-dialog-assigned-widgets' ).show();
				$( this ).show();
			} else {
				$( this ).hide();
			}
		} );

		confirm_dialog.addClass( 'ephd-dialog-box-form--active' );
	});

	// Remove question by press on confirmation button
	$( '#ephd-fp_delete-question-confirmation form' ).on( 'submit', function() {

		// check that we have filled input
		let id = $( '#ephd-fp_delete-question-confirmation-id' ).val();

		if ( typeof id == 'undefined' || ! id ) {
			ephd_show_error_notification( ephd_vars.reload_try_again );
			return;
		}

		let postData = {
			action: 'ephd_delete_question',
			faq_id: id,
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce
		};

		ephd_send_ajax( postData, function( response ){

			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );

				// remove Question
				$( '.ephd-faq-question--' + id + ', .ephd-fp__faqs__question--' + id ).remove();
			}

			ephd_check_no_questions_message();

			// Hide dialog
			$( '#ephd-fp_delete-question-confirmation' ).removeClass( 'ephd-dialog-box-form--active' );
		} );

		return false;
	});

	// Move question from Help Dialog to the all questions list
	$( document ).on( 'click', '.ephd-faq-question__move_right', function() {

		let id = $( this ).closest( '.ephd-faq-question' ).data( 'id' );
		let faqs_sequence_input = $( '.ephd-wp__widget-form--active' ).find( '[name="faqs_sequence"]' );
		let faqs_sequence = faqs_sequence_input.val().split( ',' );

		$( '.ephd-wp__widget-form--active .ephd-faq-question--' + id ).addClass( 'ephd-faq-question--active' );

		faqs_sequence = faqs_sequence.filter( function(e) { return e != id } );

		faqs_sequence_input.val( faqs_sequence );

		update_preview( faqs_sequence_input );
		ephd_check_questions_filter();
	});

	// Move question from all questions to Help Dialog
	$( document ).on( 'click', '.ephd-faq-question__add', function() {

		let id = $( this ).closest( '.ephd-faq-question-container' ).data( 'id' );
		let faqs_sequence_input = $( this ).closest( '.ephd-wp__widget-form--active' ).find( '[name="faqs_sequence"]' );
		let faqs_sequence = faqs_sequence_input.val().split( ',' );

		$( this ).closest( '.ephd-faq-question-container' ).removeClass( 'ephd-faq-question--active' );

		faqs_sequence.push( id );

		faqs_sequence_input.val( faqs_sequence );

		update_preview( faqs_sequence_input );
		ephd_check_questions_filter();
	});

	// Add FAQs list buttons to the Help Dialog
	function add_buttons_to_hd_faqs_items() {
		$( '.ephd-wp__widget-preview #ephd-help-dialog .ephd-hd-faq__list__item-container' ).each(function(){

			let id = $( this ).data( 'id' );

			let buttons_block = $( '.ephd-wp__widget-form--active .ephd-faq-question__buttons_template' ).html();

			$( buttons_block ).appendTo( this ).attr( 'data-id', id );
		});
	}

	// Update faqs item and preview
	function update_faqs_preview() {
		let faqs_sequence_input = $( '.ephd-wp__widget-form--active' ).find( '[name="faqs_sequence"]' );
		update_preview( faqs_sequence_input );
	}

	// add background to element when hover trash button
	$( document ).on( 'mouseenter', '.ephd-faq-question__delete', function(){
		$( this ).closest( '.ephd-faq-question-container' ).addClass( 'ephd-faq-question--delete-highlight' );
	});

	$( document ).on( 'mouseleave', '.ephd-faq-question__delete', function(){
		$( this ).closest( '.ephd-faq-question-container' ).removeClass( 'ephd-faq-question--delete-highlight' );
	});

	$( document ).on( 'mouseenter', '.ephd-faq-question__add', function(){
		$( this ).closest( '.ephd-faq-question-container' ).addClass( 'ephd-faq-question--move_left-highlight' );
	});

	$( document ).on( 'mouseleave', '.ephd-faq-question__add', function(){
		$( this ).closest( '.ephd-faq-question-container' ).removeClass( 'ephd-faq-question--move_left-highlight' );
	});

	$( document ).on( 'mouseenter', '.ephd-faq-question__move_right', function(){
		$( this ).closest( '.ephd-faq-question-container' ).addClass( 'ephd-faq-question--move_right-highlight' );
	});

	$( document ).on( 'mouseleave', '.ephd-faq-question__move_right', function(){
		$( this ).closest( '.ephd-faq-question-container' ).removeClass( 'ephd-faq-question--move_right-highlight' );
	});

	$( document ).on( 'mouseenter', '.ephd-faq-question__edit', function(){
		$( this ).closest( '.ephd-faq-question-container' ).addClass( 'ephd-faq-question--edit-highlight' );
	});

	$( document ).on( 'mouseleave', '.ephd-faq-question__edit', function(){
		$( this ).closest( '.ephd-faq-question-container' ).removeClass( 'ephd-faq-question--edit-highlight' );
	});

	function ephd_sort_all_articles() {

		let questions = $( '.ephd-all-questions-list-container' ),
			cont = questions.children( '.ephd-faq-question-container' );

		cont.detach().sort(function (a, b) {

			// stripping the id to get the position number
			let modifiedA = $(a).data( 'modified' );
			let modifiedB = $(b).data( 'modified' );

			// checking for the greater position and order accordingly
			if (parseInt(modifiedA) <= parseInt(modifiedB)) {
				return 0;
			} else {
				return -1;
			}
		})

		questions.append(cont);
	}

	ephd_sort_all_articles();
	ephd_check_questions_filter();

	// Cancel buttons (just reload the page)
	$( 'body' ).on( 'click', '.ephd__hdl__action__reload input', function(){
		location.reload();
	});

	// init wpColorPicker
	function ephd_init_wp_color_picker() {
		$('.ephd-admin__color-field input[type="text"]').wpColorPicker({
			change: function( colorEvent, ui) {
				setTimeout( function() {
					$( colorEvent.target ).trigger( 'change' );
				}, 50);
			},
		});
	}


	/*************************************************************************************************
	 *
	 *          Help dialog Header Events
	 *
	 ************************************************************************************************/

	function ephd_preview_tab_click( $tab ) {
		if ( $tab.hasClass( 'ephd-hd-tab--disabled' ) ) {
			return;
		}

        // Get or define values.
        let target_tab = $tab.attr( 'data-ephd-target-tab' );
        let parent_container = $( '#ephd-help-dialog' );

        // Set certain values or do actions based on the tab that was clicked on.
        switch( target_tab ) {
        	case 'chat':
        		// Set the top Container Class for the active Tab.
        		parent_container.addClass( 'ephd-hd-chat-tab--active' );
        		parent_container.removeClass( 'ephd-hd-faqs-tab--active' );
        		parent_container.removeClass( 'ephd-hd-search--active' );
        		parent_container.removeClass( 'ephd-hd-contact-tab--active' );
        		break;
        	case 'faqs':
        		// Set the top Container Class for the active Tab.
        		parent_container.addClass( 'ephd-hd-faqs-tab--active' );
        		parent_container.removeClass( 'ephd-hd-contact-tab--active' );
        		parent_container.removeClass( 'ephd-hd-chat-tab--active' );
        		break;
        	case 'contact':
        		// Set the top Container Class for the active Tab.
        		parent_container.addClass( 'ephd-hd-contact-tab--active' );
        		parent_container.removeClass( 'ephd-hd-faqs-tab--active' );
        		parent_container.removeClass( 'ephd-hd-search--active' );
        		parent_container.removeClass( 'ephd-hd-chat-tab--active' );
        		break;
        	default: break;
        }

        //Remove Top Tab Active Classes
        parent_container.find( '.ephd-hd-tab' ).removeClass( 'ephd-hd-tab--active' );

        // Add Top Tab Active Class
        $tab.addClass( 'ephd-hd-tab--active' );

        parent_container.closest('.ephd-wp__widget-preview').find('.ephd-wp__widget-preview-tooltip>div').hide();
		parent_container.closest('.ephd-wp__widget-preview').find('.ephd-wp__widget-preview-tooltip--' + target_tab ).show();
	}

	function ephd_add_events_to_hd_preview_tabs() {

		let parent_container = $( '#ephd-help-dialog' );

		// first remove event to avoid duplication
		$( document.body ).off( 'click', '.ephd-hd-tab' );

		// add event
		$( document.body ).on( 'click', '.ephd-hd-tab', function () {
			ephd_preview_tab_click( $( this ) );
		});

		// Trigger events for currently active tab
		let active_tab = $( '.ephd-hd-tab--active' );
		if ( active_tab.length > 0 ){
			ephd_preview_tab_click( active_tab );

		// Handle tooltip for single tab correctly
		} else {
			let target_tab = parent_container.data( 'ephd-tab' );
			parent_container.closest('.ephd-wp__widget-preview').find('.ephd-wp__widget-preview-tooltip>div').hide();
			parent_container.closest('.ephd-wp__widget-preview').find('.ephd-wp__widget-preview-tooltip--' + target_tab ).show();
		}
	}


	/*************************************************************************************************
	 *
	 *          AI Help Sidebar - sync with Widgets admin page script 'admin-help-dialog-faqs.js'
	 *
	 ************************************************************************************************/
	// Prevent the triggering click handler for WP Editor inside the AI Help Sidebar
	$( document ).on( 'click', '.ephd__wp_editor__ai-help-sidebar', function( e ) {
		e.stopPropagation();
	} );

	// Open AI Help Sidebar
	$( document ).on( 'click', '.ephd__wp_editor__ai-help-sidebar-btn-open', function( e ) {
		e.preventDefault();
		$( '.ephd-fp__wp-editor' ).addClass( 'ephd-ai-help-sidebar--active' );
		return false;
	} );

	// Close AI Help Sidebar
	$( document ).on( 'click', '.ephd-ai-help-sidebar-btn-close', function( e ) {
		$( this ).closest( '.ephd-fp__wp-editor' ).removeClass( 'ephd-ai-help-sidebar--active' );
		$( '.ephd-ai-help-sidebar__alternatives-back-btn' ).trigger( 'click' );
	} );

	// QUESTION action: Fix Spelling and Grammar
	$( document ).on( 'click', '.ephd-ai__question-fix-spelling-and-grammar-btn', function( e ) {
		e.preventDefault();

		// Do nothing if the prompt is empty or too short
		let question = ai_help_sidebar_sanitize_user_input( $( '#ephd-fp__wp-editor__question-title' ).val() );
		if ( question.length < 3 ) {
			return false;
		}

		let postData = {
			action: 'ephd_fix_question_spelling_and_grammar',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			input_text: question,
		};

		let action_title = $( this ).val();

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' && typeof response.fixed_input_text != 'undefined' && response.fixed_input_text.length > 0 ) {
				ephd_show_success_notification( response.message );
				ai_help_sidebar_open_alternatives_screen( question, response.tokens_used, action_title );
				$( '.ephd-ai-help-sidebar__alternatives-list' ).html( '<div class="ephd-ai-help-sidebar__alternative-question">' + response.fixed_input_text + '</div>' );
			}
		} );

		return false;
	} );

	// QUESTION action: Create 5 Alternatives
	$( document ).on( 'click', '.ephd-ai__question-create-five-alternatives-btn', function( e ) {
		e.preventDefault();

		// Do nothing if the prompt is empty or too short
		let question = ai_help_sidebar_sanitize_user_input( $( '#ephd-fp__wp-editor__question-title' ).val() );
		if ( question.length < 3 ) {
			return false;
		}

		let postData = {
			action: 'ephd_create_five_question_alternatives',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			input_text: question
		};

		let action_title = $( this ).val();

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' && typeof response.alternatives != 'undefined' && response.alternatives.length > 0 ) {
				ephd_show_success_notification( response.message );
				ai_help_sidebar_open_alternatives_screen( question, response.tokens_used, action_title );
				let alternatives_list = '';
				for ( let i = 0; i < response.alternatives.length; i++ ) {
					alternatives_list += '<div class="ephd-ai-help-sidebar__alternative-question">' + response.alternatives[i] + '</div>';
				}
				$( '.ephd-ai-help-sidebar__alternatives-list' ).html( alternatives_list );
			}
		} );

		return false;
	} );

	// ANSWER action: Fix Spelling and Grammar
	$( document ).on( 'click', '.ephd-ai__answer-fix-spelling-and-grammar-btn', function( e ) {
		e.preventDefault();

		let editor = tinymce.get( 'ephd-fp__wp-editor' );
		let answer = '';

		if ( editor && $( '.wp-editor-wrap' ).hasClass( 'tmce-active' ) ) {
			answer = editor.getContent();
		} else {
			answer = $( '#ephd-fp__wp-editor' ).val();
		}

		answer = ai_help_sidebar_sanitize_user_input( answer );

		// Do nothing if the input is empty or too short
		if ( answer.length < 3 ) {
			return false;
		}

		let postData = {
			action: 'ephd_fix_answer_spelling_and_grammar',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			input_text: answer,
		};

		let action_title = $( this ).val();

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' && typeof response.fixed_input_text != 'undefined' && response.fixed_input_text.length > 0 ) {
				ephd_show_success_notification( response.message );
				ai_help_sidebar_open_alternatives_screen( answer, response.tokens_used, action_title );
				$( '.ephd-ai-help-sidebar__alternatives-list' ).html( '<div class="ephd-ai-help-sidebar__alternative-answer">' + response.fixed_input_text + '</div>' );
			}
		} );

		return false;
	} );

	// ANSWER action: Create 5 Alternatives
	$( document ).on( 'click', '.ephd-ai__answer-create-five-alternatives-btn', function( e ) {
		e.preventDefault();

		let editor = tinymce.get( 'ephd-fp__wp-editor' );
		let answer = '';

		if ( editor && $( '.wp-editor-wrap' ).hasClass( 'tmce-active' ) ) {
			answer = editor.getContent();
		} else {
			answer = $( '#ephd-fp__wp-editor' ).val();
		}

		answer = ai_help_sidebar_sanitize_user_input( answer );

		// Do nothing if the input is empty or too short
		if ( answer.length < 3 ) {
			return false;
		}

		let postData = {
			action: 'ephd_create_five_answer_alternatives',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			input_text: answer,
		};

		let action_title = $( this ).val();

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' && typeof response.alternatives != 'undefined' && response.alternatives.length > 0 ) {
				ephd_show_success_notification( response.message );
				ai_help_sidebar_open_alternatives_screen( answer, response.tokens_used, action_title );
				let alternatives_list = '';
				for ( let i = 0; i < response.alternatives.length; i++ ) {
					alternatives_list += '<div class="ephd-ai-help-sidebar__alternative-answer">' + response.alternatives[i] + '</div>';
				}
				$( '.ephd-ai-help-sidebar__alternatives-list' ).html( alternatives_list );
			}
		} );

		return false;
	} );

	// ANSWER action: Create Answer Based on Your Question
	$( document ).on( 'click', '.ephd-ai__create-answer-based-on-question-btn', function( e ) {
		e.preventDefault();

		// Do nothing if the prompt is empty or too short
		let question = ai_help_sidebar_sanitize_user_input( $( '#ephd-fp__wp-editor__question-title' ).val() );
		if ( question.length < 3 ) {
			return false;
		}

		let postData = {
			action: 'ephd_create_answer_based_on_question',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			question_text: question,
			max_tokens: 200
		};

		let action_title = $( this ).val();

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.message != 'undefined' && typeof response.generated_answer != 'undefined' && response.generated_answer.length > 0 ) {
				ephd_show_success_notification( response.message );
				ai_help_sidebar_open_alternatives_screen( question, response.tokens_used, action_title );
				$( '.ephd-ai-help-sidebar__alternatives-list' ).html( '<div class="ephd-ai-help-sidebar__alternative-answer">' + response.generated_answer + '</div>' );
			}
		} );

		return false;
	} );

	// Return to main AI Help Sidebar screen
	$( document ).on( 'click', '.ephd-ai-help-sidebar__alternatives-back-btn', function() {
		$( '.ephd-ai-help-sidebar__alternatives' ).hide();
		$( '.ephd-ai-help-sidebar__main' ).show();
	} );

	// Fill Question input with selected alternative question
	$( document ).on( 'click', '.ephd-ai-help-sidebar__alternative-question', function() {
		$( '#ephd-fp__wp-editor__question-title' ).val( $( this ).html() );
	} );

	// Fill Answer input with selected alternative answer
	$( document ).on( 'click', '.ephd-ai-help-sidebar__alternative-answer', function() {
		let editor = tinymce.get( 'ephd-fp__wp-editor' );
		if ( editor && $( '.wp-editor-wrap' ).hasClass( 'tmce-active' ) ) {
			editor.setContent( $( this ).html() );
		} else {
			$( '#ephd-fp__wp-editor' ).val( $( this ).html() );
		}
	} );

	// Hide AI Help Sidebar
	function hide_ai_help_sidebar() {
		$( '.ephd-fp__wp-editor' ).removeClass( 'active ephd-ai-help-sidebar--active' );
		$( '.ephd-ai-help-sidebar__alternatives' ).hide();
		$( '.ephd-ai-help-sidebar__main' ).show();
	}

	function ai_help_sidebar_open_alternatives_screen( input_text, tokens_used, action_title ) {
		$( '.ephd-ai-help-sidebar__main' ).hide();
		$( '.ephd-ai-help-sidebar__alternatives' ).show();
		$( '.ephd-ai-help-sidebar__alternatives-input__value' ).html( input_text );
		$( '.ephd-ai-help-sidebar__alternatives-usage-tokens__value' ).html( tokens_used );
		$( '.ephd-ai-help-sidebar__alternatives-title' ).html( action_title );
	}

	function ai_help_sidebar_sanitize_user_input( input_text ) {
		// convert all HTML to HTML entities
		// return input_text.trim().replace( /&/g, "&amp;" ).replace( /</g, "&lt;" ).replace( />/g, "&gt;" ).replace( /"/g, "&quot;" ).replace( /'/g, "&#039;" );
		// remove script tags and all between them
		return input_text.trim().replace( /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "" );
	}


	/*************************************************************************************************
	 *
	 *          AJAX calls
	 *
	 ************************************************************************************************/
	
	// generic AJAX call handler
	function ephd_send_ajax( postData, refreshCallback, callbackParam, reload, alwaysCallback, $loader ) {

		let errorMsg;
		let theResponse;
		refreshCallback = (typeof refreshCallback === 'undefined') ? 'ephd_callback_noop' : refreshCallback;

		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: postData,
			url: ajaxurl,
			beforeSend: function (xhr)
			{
				if ( typeof $loader == 'undefined' || $loader === false ) {
					ephd_loading_Dialog('show', '');
				} 
				
				if ( typeof $loader == 'object' ) {
					ephd_loading_Dialog('show', '', $loader);
				} 
			}
		}).done(function (response)        {
			theResponse = ( response ? response : '' );
			if ( theResponse.error || typeof theResponse.message === 'undefined' ) {
				//noinspection JSUnresolvedVariable,JSUnusedAssignment
				errorMsg = theResponse.message ? theResponse.message : ephd_admin_notification('', ephd_vars.reload_try_again, 'error');
			}

		}).fail( function ( response, textStatus, error )        {
			//noinspection JSUnresolvedVariable
			errorMsg = ( error ? ' [' + error + ']' : ephd_vars.unknown_error );
			//noinspection JSUnresolvedVariable
			errorMsg = ephd_admin_notification(ephd_vars.error_occurred + '. ' + ephd_vars.msg_try_again, errorMsg, 'error');
		}).always(function() {
			
			theResponse = (typeof theResponse === 'undefined') ? '' : theResponse;
			
			if ( typeof alwaysCallback == 'function' ) {
				alwaysCallback( theResponse );
			} 

			if ( typeof $loader == 'undefined' || $loader === false ) {
				ephd_loading_Dialog( 'remove', '' );
			}

			if ( typeof $loader == 'object' ) {
				ephd_loading_Dialog( 'remove', '', $loader );
			}

			if ( errorMsg ) {
				$('.ephd-bottom-notice-message').remove();
				$('body #ephd-admin-page-wrap').append(errorMsg).removeClass('fadeOutDown');
				
				setTimeout( function() {
					$('.ephd-bottom-notice-message').addClass( 'fadeOutDown' );
				}, 10000 );
				return;
			}

			if ( typeof refreshCallback === "function" ) {
				
				if ( callbackParam === 'undefined' ) {
					refreshCallback(theResponse);
				} else {
					refreshCallback(theResponse, callbackParam);
				}
			} else {
				if ( reload ) {
					location.reload();
				}
			}
		});
	}

	/**
	 * Displays a Center Dialog box with a loading icon and text.
	 *
	 * This should only be used for indicating users that loading or saving or processing is in progress, nothing else.
	 * This code is used in these files, any changes here must be done to the following files.
	 *   - admin-plugin-pages.js
	 *   - admin-kb-config-scripts.js
	 *
	 * @param  {string}    displayType     Show or hide Dialog initially. ( show, remove )
	 * @param  {string}    message         Optional    Message output from database or settings.
	 *
	 * @return {html}                      Removes old dialogs and adds the HTML to the end body tag with optional message.
	 *
	 */
	function ephd_loading_Dialog( displayType, message, $el ){

		if( displayType === 'show' ){
			
			let loadingClass = ( typeof $el == 'undefined' ) ? '' : 'ephd-admin-dialog-box-loading--relative';
			
			let output =
				'<div class="ephd-admin-dialog-box-loading ' + loadingClass + '">' +

				//<-- Header -->
				'<div class="ephd-admin-dbl__header">' +
				'<div class="ephd-admin-dbl-icon ephdfa ephdfa-hourglass-half"></div>'+
				(message ? '<div class="ephd-admin-text">' + message + '</div>' : '' ) +
				'</div>'+

				'</div>' +
				'<div class="ephd-admin-dialog-box-overlay ' + loadingClass + '"></div>';

			//Add message output at the end of Body Tag
			if ( typeof $el == 'undefined' ) {
				$( 'body' ).append( output );
			} else { 
				$el.append( output ).addClass( 'ephd-loading-white-opacity' );
			}
			
		} else if( displayType === 'remove' ){

			// Remove loading dialogs.
			$( '.ephd-admin-dialog-box-loading' ).remove();
			$( '.ephd-admin-dialog-box-overlay' ).remove();

			if ( typeof $el != 'undefined' ) {
				$el.removeClass( 'ephd-loading-white-opacity' );
			}
		}

	}

	/* Dialogs --------------------------------------------------------------------*/
	// SHOW INFO MESSAGES
	function ephd_admin_notification( $title, $message , $type ) {
		return '<div class="ephd-bottom-notice-message">' +
			'<div class="contents">' +
			'<span class="' + $type + '">' +
			($title ? '<h4>'+$title+'</h4>' : '' ) +
			($message ? '<p>' + $message + '</p>': '') +
			'</span>' +
			'</div>' +
			'<div class="ephd-close-notice ephdfa ephdfa-window-close"></div>' +
			'</div>';
	}

	// Toggle the PRO Setting Tooltip
	$( document ).on( 'click', '.ephd-admin__input-disabled, .ephd__option-pro-tag', function () {
		let $tooltip = $( this ).closest( '.ephd-input-group' ).find( '.ephd__option-pro-tooltip' );
		let is_visible = $tooltip.is(':visible');

		// hide all pro tooltip
		$( '.ephd__option-pro-tooltip' ).hide();

		// toggle current pro tooltip
		if ( is_visible ) {
			$tooltip.hide();
		} else {
			$tooltip.show();
		}
	});

	// Hide PRO Setting Tooltip if click outside the tooltip
	$( document ).on( 'click', function (e) {
		let target = $( e.target );
		if ( ! target.closest( '.ephd__option-pro-tooltip' ).length && ! target.closest( '.ephd-admin__input-disabled' ).length && ! target.closest( '.ephd__option-pro-tag' ).length  ) {
			$( '.ephd__option-pro-tooltip' ).hide();
		}
	});

	let ephd_notification_timeout;

	function ephd_show_error_notification( $message, $title = '' ) {
		$( '.ephd-bottom-notice-message' ).remove();
		$( 'body #ephd-admin-page-wrap' ).append( ephd_admin_notification( $title, $message, 'error' ) );

		clearTimeout( ephd_notification_timeout );
		ephd_notification_timeout = setTimeout( function() {
			$('.ephd-bottom-notice-message').addClass( 'fadeOutDown' );
		}, 10000 );
	}

	function ephd_show_success_notification( $message, $title = '' ) {
		$( '.ephd-bottom-notice-message' ).remove();
		$( 'body #ephd-admin-page-wrap' ).append( ephd_admin_notification( $title, $message, 'success' ) );

		clearTimeout( ephd_notification_timeout );
		ephd_notification_timeout = setTimeout( function() {
			$( '.ephd-bottom-notice-message' ).addClass( 'fadeOutDown' );
		}, 10000 );
	}
	
	// scroll to element with animation
	function ephd_scroll_to( $el ) {
		if ( ! $el.length ) {
			return;
		}
		
		$("html, body").animate({ scrollTop: $el.offset().top - 100 }, 300);
	}

	function check_message_mode() {
		if ( $('#initial_message_mode').length == 0 ) {
			return;
		}

		if ( $('#initial_message_mode').find('input:checked').length == 0 ) {
			return;
		}

		let val = $('#initial_message_mode').find('input:checked').val();

		if ( val == 'icon_text' ) {
			$('#initial_message_image_url_group').show();
		} else {
			$('#initial_message_image_url_group').hide();
		}
	}

	$('body').on('change', '#initial_message_mode input', check_message_mode );
});