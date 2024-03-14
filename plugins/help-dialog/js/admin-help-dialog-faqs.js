jQuery(document).ready(function($) {

	/*************************************************************************************************
	 *
	 *          Misc
	 *
	 ************************************************************************************************/

	// Show message if no Questions were created or all questions have been assigned
	function ephd_check_no_questions_message() {

		let all_questions = $( '.ephd-all-questions-list-container .ephd-faq-question-container' ).length;
		let active_questions = $( '.ephd-all-questions-list-container .ephd-faq-question--active' ).length;

		// Hide all questions
		$( '#ephd-admin__no-question-message, #ephd-admin__assigned-question-message' ).hide();

		// Show No questions message
		if ( all_questions === 0 ) {
			$( '#ephd-admin__no-question-message' ).show();
		}
		// Show all questions have been assigned message
		if ( active_questions === 0 && all_questions > 0 ) {
			$( '#ephd-admin__assigned-question-message' ).show();
		}
	}

	// Disable Help Dialog search input field
	function ephd_disable_hd_search_input() {
		$( '#ephd-help-dialog #ephd-hd__search-terms' ).prop( 'disabled', true );
	};

	/*************************************************************************************************
	 *
	 *          FAQs: FAQs form
	 *
	 ************************************************************************************************/

	// Load form to edit/create FAQs
	$( document ).on( 'click', '.ephd-admin__item-preview .ephd_edit_item, .ephd-admin__item-preview .ephd-admin__item-preview__sub-items-btn, .ephd-fp__create-new-faqs-btn', function( e ) {
		e.preventDefault();

		let widget_id = $( this ).data( 'id' );
		let faqs_name = $( this ).closest( '.ephd-admin__item-preview' ).find( '#faqs_name' ).val();

		let postData = {
			action: 'ephd_load_faqs_form',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			widget_id: typeof widget_id !== 'undefined' ? widget_id : 0,
			faqs_name: faqs_name
		};

		ephd_send_ajax( postData, function( response ){
			if ( ! response.error && typeof response.faqs_form != 'undefined' ) {

				// Hide elements before show the form
				$( '.ephd-fp__create-new-faqs-btn, .ephd__welcome-message' ).hide();

				// Update FAQs form
				$( '.ephd-fp__faqs-form' ).replaceWith( response.faqs_form );

				// Update HD preview
				$( '.ephd-fp__widget-preview-content' ).html( response.preview );
				$( '.ephd-fp__widget-preview' ).show();
				$( '#ephd-hd-faq-tab' ).trigger('click');

				// Hide preview boxes
				$( '.ephd-admin__item-preview' ).hide();

				// Clear faqs_name input
				$( '.ephd-admin__add-new-item-preview' ).find( '#faqs_name' ).val('');

				// Update Designs inline styles
				$( '#ephd-public-styles-inline-css' ).html( response.demo_styles );

				// Add edit inputs to hd faqs list
				add_buttons_to_hd_faqs_items();

				// Make questions sortable
				ephd_enable_sortable_for_faqs();
				ephd_check_no_questions_message();
				ephd_check_questions_filter();
				ephd_disable_hd_search_input();
			}
		} );
	} );

	// Save FAQs
	$( document ).on( 'click', '.ephd-fp__faqs-form .ephd_save_faqs', function() {

		let form = $( this ).closest( '.ephd-fp__faqs-form' );
		let widget_id = form.find( '[name="widget_id"]' ).val();

		let faqs_sequence_input = $( this ).closest( '.ephd-fp__faqs-form' ).find( '[name="faqs_sequence"]' );
		let faqs_sequence = faqs_sequence_input.val().split( ',' );

		let postData = {
			action: 'ephd_save_faqs',
			_wpnonce_ephd_ajax_action : ephd_help_dialog_vars.nonce,
			widget_id: widget_id,
			faqs_name: form.find( '[name="faqs_name"]' ).val(),
			faqs_sequence: faqs_sequence
		};

		ephd_send_ajax( postData, function( response ){

			if ( ! response.error && typeof response.message != 'undefined' ) {
				ephd_show_success_notification( response.message );

				// Update FAQs form
				form.replaceWith( response.faqs_form );

				// Update FAQs preview
				if ( $( '.ephd-admin__item-preview--' + widget_id ).length ) {
					$( '.ephd-admin__item-preview--' + widget_id ).replaceWith( response.faqs_preview );

				// Add FAQs preview
				} else {
					$( response.faqs_preview ).insertBefore( '.ephd-fp__faqs-form' );
				}

				// Hide preview boxes
				$( '.ephd-admin__item-preview' ).hide();

				// Add edit inputs to hd faqs list
				add_buttons_to_hd_faqs_items();

				ephd_check_questions_filter();
			}
		} );
	} );

	// Cancel form to edit/create FAQs
	$( document ).on( 'click', '.ephd_cancel_faqs', function( e ) {
		e.preventDefault();
		$( '.ephd-fp__faqs-form' ).removeClass( 'ephd-fp__faqs-form--active' );
		$( '.ephd-admin__item-preview' ).show();
		$( '.ephd-fp__widget-preview' ).hide();
		$( '.ephd-fp__create-new-faqs-btn, .ephd__welcome-message' ).show();
		return false;
	} );


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
					// change question title in the list
					if ( $( '.ephd-faq-question--' + question.faq_id ).length ) {
						$( '.ephd-faq-question--' + question.faq_id + ' .ephd-faq-question__text' ).text( question.title );

						// add question to the list
					} else {
						$( question.html ).appendTo( '.ephd-all-questions-list-container' ).addClass( 'ui-sortable-handle' );
					}

				});

				$( '.ephd-fp__wp-editor' ).removeClass( 'active' );
				hide_ai_help_sidebar();

				ephd_sort_all_faqs();
				ephd_check_no_questions_message();
				ephd_check_questions_filter();
				update_faqs_preview();
			}
		} );

		return false;
	});

	// Update faqs item and preview
	function update_faqs_preview() {
		let faqs_sequence_input = $( '.ephd-fp__faqs-form--active' ).find( '[name="faqs_sequence"]' );
		update_preview( faqs_sequence_input );
	}

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
		let current_questions = $( '.ephd-fp__faqs-form--active' ).find( '[name="faqs_sequence"]' ).val().split( ',' );

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

	// trash button on all faqs list. Only call delete dialog
	$( document ).on( 'click', '.ephd-faq-question__delete', function() {
		let id = $( this ).closest( '.ephd-faq-question-container' ).data( 'id' );

		if ( typeof id == 'undefined' || ! id ) {
			ephd_show_error_notification( ephd_vars.reload_try_again );
			return;
		}

		$( '#ephd-fp_delete-question-confirmation-id' ).val( id );

		// Show assigned widgets
		let confirm_dialog = $( '#ephd-fp_delete-question-confirmation' );
		let widget_id = $( '.ephd-fp__faqs-form--active input[name="widget_id"]' ).val();

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

				// Update FAQs preview
				for ( let i in response.faqs_preview ) {
					let item_preview = $( '.ephd-admin__item-preview--' + i );
					if ( item_preview.length ) {
						item_preview.replaceWith( response.faqs_preview[i] );
						// Add FAQs preview
					} else {
						$( response.faqs_preview[i] ).insertBefore( '.ephd-fp__faqs-form' );
					}
				}

				// Hide preview boxes
				$( '.ephd-admin__item-preview' ).hide();

				// remove Question
				$( '.ephd-faq-question--' + id + ', .ephd-fp__faqs__question--' + id ).remove();
			}

			ephd_check_no_questions_message();

			// Hide dialog
			$( '#ephd-fp_delete-question-confirmation' ).removeClass( 'ephd-dialog-box-form--active' );
		} );

		return false;
	});




//	// Move question from the current questions list to the all questions list
//	$( document ).on( 'click', '.ephd-faq-question__move_right', function() {
//		let question = $( this ).closest( '.ephd-faq-question-container' );
//		question.removeClass( 'ephd-faq-question--active' );
//		$( '.ephd-all-questions-list-container' ).find( '.ephd-faq-question--' + question.data( 'id' ) ).addClass( 'ephd-faq-question--active' );
//		ephd_check_questions_filter();
//	});
//
//	// Move question from all questions list to the current questions list
//	$( document ).on( 'click', '.ephd-faq-question__add', function() {
//		let question = $( this ).closest( '.ephd-faq-question-container' );
//		question.removeClass( 'ephd-faq-question--active' );
//		$( '.ephd-fp__current-questions' ).find( '.ephd-faq-question--' + question.data( 'id' ) ).addClass( 'ephd-faq-question--active' );
//		ephd_check_questions_filter();
//	});


	// Move question from Help Dialog to the all questions list
	$( document ).on( 'click', '.ephd-faq-question__move_right', function() {

		let id = $( this ).closest( '.ephd-faq-question' ).data( 'id' );
		let faqs_sequence_input = $( '.ephd-fp__faqs-form--active' ).find( '[name="faqs_sequence"]' );
		let faqs_sequence = faqs_sequence_input.val().split( ',' );

		$( '.ephd-fp__faqs-form--active .ephd-faq-question--' + id ).addClass( 'ephd-faq-question--active' );

		faqs_sequence = faqs_sequence.filter( function(e) { return e != id } );

		faqs_sequence_input.val( faqs_sequence );

		update_preview( faqs_sequence_input );
		ephd_check_questions_filter();
	});

	// Move question from all questions to Help Dialog
	$( document ).on( 'click', '.ephd-faq-question__add', function() {

		let id = $( this ).closest( '.ephd-faq-question-container' ).data( 'id' );
		let faqs_sequence_input = $( '.ephd-fp__faqs-form--active' ).find( '[name="faqs_sequence"]' );
		let faqs_sequence = faqs_sequence_input.val().split( ',' );

		$( this ).closest( '.ephd-faq-question-container' ).removeClass( 'ephd-faq-question--active' );

		faqs_sequence.push( id );

		faqs_sequence_input.val( faqs_sequence );

		update_preview( faqs_sequence_input );
		ephd_check_questions_filter();
	});


	// Handle preview update on input changes
	function update_preview( element ) {

		ephd_check_no_questions_message();

		let form = $( element ).closest( '.ephd-fp__faqs-form--active' ),
			postData = get_widget_form_data( form, 'ephd_update_faqs_preview' );

		ephd_send_ajax( postData, function( response ){

			if ( ! response.error && typeof response.preview != 'undefined' ) {

				// Update preview
				$( '.ephd-fp__widget-preview-content' ).html( response.preview );

				// Update Styles
				$( '#ephd-public-styles-inline-css' ).html( response.demo_styles );

				// Adjust height of Widget preview - use delay to let CSS/HTML render
				setTimeout( function() {
					$( window ).trigger( 'resize' );
				}, 50);

				add_buttons_to_hd_faqs_items();

				// Update sorting
				ephd_enable_sortable_for_faqs();

				ephd_disable_hd_search_input();
			}
		}, false, false, undefined, $( '.ephd-fp__widget-preview-content' ) );
	}

	// Add FAQs list buttons to the Help Dialog
	function add_buttons_to_hd_faqs_items() {
		$( '.ephd-fp__widget-preview #ephd-help-dialog .ephd-hd-faq__list__item-container' ).each(function(){

			let id = $( this ).data( 'id' );

			let buttons_block = $( '.ephd-fp__faqs-form--active .ephd-faq-question__buttons_template' ).html();

			$( buttons_block ).appendTo( this ).attr( 'data-id', id );
		});
	}

	// Widget form data
	function get_widget_form_data( form, action ) {
		let widget_id = form.find( '[name="widget_id"]' ).val();

		return {
			action: action,
			_wpnonce_ephd_ajax_action: ephd_help_dialog_vars.nonce,
			widget_id: widget_id,

			// FAQs
			faqs_sequence: form.find( '[name="faqs_sequence"]' ).val().split(',')
		};
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

	function ephd_sort_all_faqs() {

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

	ephd_sort_all_faqs();
	ephd_check_questions_filter();

	// Cancel buttons (just reload the page)
	$( 'body' ).on( 'click', '.ephd__hdl__action__reload input', function(){
		location.reload();
	});

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
				$( '.ephd-fp__faqs-form--active' ).find( '[name="faqs_sequence"]' ).val( ordered_faqs.join( ',' ) );
			}
		});
	}


	/*************************************************************************************************
	 *
	 *          AI Help Sidebar - sync with Widgets admin page script 'admin-help-dialog-widgets.js'
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
		$( '.ephd-ai-help-sidebar__alternatives-input' ).html( 'Input: ' + input_text );
		$( '.ephd-ai-help-sidebar__alternatives-usage-tokens' ).html( 'Spent Tokens: ' + tokens_used );
		$( '.ephd-ai-help-sidebar__alternatives-title' ).html( action_title );
	}

	function ai_help_sidebar_sanitize_user_input( input_text ) {
		return input_text.trim().replace( /&/g, "&amp;" ).replace( /</g, "&lt;" ).replace( />/g, "&gt;" ).replace( /"/g, "&quot;" ).replace( /'/g, "&#039;" )
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
		refreshCallback = (typeof refreshCallback === 'undefined' ) ? 'ephd_callback_noop' : refreshCallback;

		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: postData,
			url: ajaxurl,
			beforeSend: function (xhr)
			{
				if ( typeof $loader == 'undefined' || $loader === false ) {
					ephd_loading_Dialog( 'show', '' );
				}

				if ( typeof $loader == 'object' ) {
					ephd_loading_Dialog( 'show', '', $loader);
				}
			}
		}).done(function (response)        {
			theResponse = ( response ? response : '' );
			if ( theResponse.error || typeof theResponse.message === 'undefined' ) {
				//noinspection JSUnresolvedVariable,JSUnusedAssignment
				errorMsg = theResponse.message ? theResponse.message : ephd_admin_notification( '', ephd_vars.reload_try_again, 'error' );
			}

		}).fail( function ( response, textStatus, error )        {
			//noinspection JSUnresolvedVariable
			errorMsg = ( error ? ' [' + error + ']' : ephd_vars.unknown_error );
			//noinspection JSUnresolvedVariable
			errorMsg = ephd_admin_notification(ephd_vars.error_occurred + '. ' + ephd_vars.msg_try_again, errorMsg, 'error' );
		}).always(function() {

			theResponse = (typeof theResponse === 'undefined' ) ? '' : theResponse;

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
				$( '.ephd-bottom-notice-message' ).remove();
				$( 'body #ephd-admin-page-wrap' ).append(errorMsg).removeClass( 'fadeOutDown' );

				setTimeout( function() {
					$( '.ephd-bottom-notice-message' ).addClass( 'fadeOutDown' );
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

		}else if( displayType === 'remove' ){

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
			($message ? '<p>' + $message + '</p>': '' ) +
			'</span>' +
			'</div>' +
			'<div class="ephd-close-notice ephdfa ephdfa-window-close"></div>' +
			'</div>';
	}

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

	// scrool to element with animation
	function ephd_scroll_to( $el ) {
		if ( ! $el.length ) {
			return;
		}

		$("html, body").animate({ scrollTop: $el.offset().top - 100 }, 300);
	}
});