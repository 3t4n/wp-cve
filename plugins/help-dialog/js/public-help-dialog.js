jQuery(document).ready(function($) {

	let parent_container = $( '#ephd-help-dialog' );

	// Initialize triggers
	function initialize_widget_trigger() {

		if ( parent_container.length === 0 ) {
			return;
		}

		let widget_id = parent_container.data( 'ephd-widget-id' );

		// Check current widget visibility defined by triggers - Cookies: https://github.com/js-cookie/js-cookie
		let triggers_visibility = Cookies.get( 'ephd_triggers_visibility_' + widget_id );
		if ( triggers_visibility === 'visible' ) {
			return;
		}

		let triggers_disabled = true;

		// Trigger: Delay seconds
		let trigger_delay_seconds = parent_container.data( 'ephd-trigger-delay-seconds' );
		if ( trigger_delay_seconds > 0 ) {

			triggers_disabled = false;

			parent_container.data( 'ephd-triggers-visibility', 'hidden' );
			Cookies.set( 'ephd_triggers_visibility_' + widget_id, 'hidden', { expires: 365 } );    // Cookies: https://github.com/js-cookie/js-cookie

			setTimeout(function () {

				// Do not execute if the Widget is already shown by another trigger
				if ( parent_container.data( 'ephd-triggers-visibility' ) === 'visible' ) {
					return;
				}

				parent_container.data( 'ephd-triggers-visibility', 'visible' )
				Cookies.set( 'ephd_triggers_visibility_' + widget_id, 'visible', { expires: 365 } );    // Cookies: https://github.com/js-cookie/js-cookie
				jQuery('.ephd-hd-toggle').show();
				analytics_count_impression_view();
			}, trigger_delay_seconds * 1000 );
		}

		// Trigger: Scroll percent
		let trigger_scroll_percent = parent_container.data( 'ephd-trigger-scroll-percent' );
		if ( trigger_scroll_percent > 0 ) {

			triggers_disabled = false;

			parent_container.data( 'ephd-triggers-visibility', 'hidden' );
			Cookies.set( 'ephd_triggers_visibility_' + widget_id, 'hidden', { expires: 365 } );    // Cookies: https://github.com/js-cookie/js-cookie

			$( window ).on( 'scroll', function () {

				// Do not execute if the Widget is already shown by another trigger
				if ( parent_container.data( 'ephd-triggers-visibility' ) === 'visible' ) {
					return;
				}

				let scroll_height = $( document ).height() - $( window ).height();
				let scroll_position = $( window ).scrollTop();

				if ( scroll_height > 0 ) {
					let scroll_percent = parseInt(( scroll_position / scroll_height ) * 100 );
					if ( scroll_percent >= trigger_scroll_percent ) {
						parent_container.data( 'ephd-triggers-visibility', 'visible' )
						Cookies.set( 'ephd_triggers_visibility_' + widget_id, 'visible', { expires: 365 } );    // Cookies: https://github.com/js-cookie/js-cookie
						jQuery('.ephd-hd-toggle').show();
						analytics_count_impression_view();
					}
				} else {
					parent_container.data( 'ephd-triggers-visibility', 'visible' )
					Cookies.set( 'ephd_triggers_visibility_' + widget_id, 'visible', { expires: 365 } );    // Cookies: https://github.com/js-cookie/js-cookie
					jQuery('.ephd-hd-toggle').show();
					analytics_count_impression_view();
				}
			});
		}

		// Deactivate previously set triggers cookie if now all triggers are disabled
		if ( triggers_disabled ) {
			Cookies.set( 'ephd_triggers_visibility_' + widget_id, 'visible', { expires: 365 } );    // Cookies: https://github.com/js-cookie/js-cookie
		}
	}
	initialize_widget_trigger();

	if( $( '.ephd-hd-toggle' ).length ) {

		// Initialize constant delay feature only if widget is not hidden by triggers
		if ( parent_container.data( 'ephd-triggers-visibility' ) === 'visible' ) {

			let start_delay = jQuery('.ephd-hd-toggle').data('ephd-start-wait');

			setTimeout(function () {
				jQuery('.ephd-hd-toggle').show();
			}, start_delay * 1000);
		}
	}

	// Analytics: count impression after 5 seconds delay. Check if launcher exist and prevent counting from iframe script
	function analytics_count_impression_view() {

		// Count impression only if widget is not hidden by triggers
		if ( parent_container.data( 'ephd-triggers-visibility' ) === 'hidden' ) {
			return;
		}

		if ( $( '.ephd-hd-toggle' ).length && $( 'html.ephd-preview-iframe' ).length <= 0 ) {

			let analytics_delay = $('.ephd-hd-toggle').data('analytics-delay');
			// set default if not exists
			if ( 'undefined' == typeof analytics_delay ) {
				analytics_delay = 5;
			}

			setTimeout(function () {
				count_analytics( 'impressions', 'view' );
			}, analytics_delay * 1000 );
		}
	}
	analytics_count_impression_view();

	/*************************************************************************************************
	 *
	 *          FRONTEND: FAQ box
	 *
	 ************************************************************************************************/
	function adjust_help_dialog_height(){

		let windowHeight = window.innerHeight;
		let minWindowHeight = 720; // This is the height at which the HD will be cut

		// Calculate the height difference and minus it from our fixed value of 477
		let diff =  477 - ( minWindowHeight - windowHeight );

		$( '.ephd-help-dialog-container' ).each( function() {

			// If the logo is active, then add extra space.
			let logoSpace = '';
			if( $( this ).find( '.ephd-hd-header__logo' ).length ) {
				logoSpace = 30;
			}

			// Start making the body container smaller if the diff is less.
			if( diff > 477 ){
				$( this ).find( '#ephd-hd-body-container' ).css( 'height', ( 477 - logoSpace ) );
				$( this ).find( '.ephd-hd-header__logo' ).show();

			} else {

				// Very small screen
				$( this ).find( '#ephd-hd-body-container' ).css( 'height', ( diff )  );

				// Hide Logo
				$( this ).find( '.ephd-hd-header__logo' ).hide();
			}
		} );
	}

	if( $( '.ephd-help-dialog-container' ).length ) {

		// On page load set height
		adjust_help_dialog_height();

		/**
		 * If window re-sizes vertically, adjust the Help Dialog box height.
		 * This will prevent the HD from being cut off from the top of the browser window.
		 */
		$( window ).on( 'resize', adjust_help_dialog_height );
	}

	// This function shows specific divs in the HD based on the arg. Used by all click functions that trigger content to be displayed.
	function show_content( $content_name ){

		switch( $content_name ) {

			case 'show_home':
				// This is the default page, when the HD is initially loaded.

				// Show Default FAQs list ( The one that gets loaded initially )
				$( '.ephd-hd-faq__faqs-container' ).css( 'display', 'flex' );

				// Hide Article that was open.
				$( '#ephd-hd__search_results-cat-article-details' ).hide();

				// Show Search Results
				$( '#ephd-hd-search-results__tab-container' ).hide();
				$( '.ephd-hd-kb__search-results-container' ).hide();

				// Show FAQs
				$( '.ephd-hd-faq-container' ).css( 'display', 'flex' );

				// Remove top Classes
				parent_container.removeClass( 'ephd-hd-search--active' );
				parent_container.removeClass( 'ephd-hd-article-details-active' );
				parent_container.removeClass( 'ephd-hd-search-error--active' );
				break;

			case 'show_search_results':

				// Show Results Tabs & Content
				$( '#ephd-hd-search-results__tab-container' ).css( 'display', 'flex' );
				$( '.ephd-hd-search-results__tab-content-container' ).show();

				// Hide Default FAQs list ( The one that gets loaded initially )
				$( '.ephd-hd-faq__faqs-container' ).hide();

				// Hide Article that was open.
				$( '#ephd-hd__search_results-cat-article-details' ).hide();

				// Show Search Results
				$( '.ephd-hd-kb__search-results-container' ).show();

				// Show FAQs
				$( '.ephd-hd-faq-container' ).show();

				// Add Top Class
				parent_container.addClass( 'ephd-hd-search--active' );

				// Remove top Classes
				parent_container.removeClass( 'ephd-hd-search-error--active' );
				parent_container.removeClass( 'ephd-hd-article-details-active' );
				break;

			case 'hide_content':

				// Hide Results Tabs & Content
				$( '#ephd-hd-search-results__tab-container' ).hide();
				$( '.ephd-hd-search-results__tab-content-container' ).hide();

				// Show Loader
				$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'flex' );

				// Remove top Classes
				parent_container.removeClass( 'ephd-hd-search-error--active' );
				parent_container.removeClass( 'ephd-hd-article-details-active' );
				break;

			case 'show_article_details':

				// Hide Results Tabs & Content
				$( '#ephd-hd-search-results__tab-container' ).hide();
				$( '.ephd-hd-search-results__tab-content-container' ).hide();

				// Show Loader
				$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'flex' );

				// Show Article Details
				$( '#ephd-hd__search_results-cat-article-details' ).show();

				// Hide Loader
				$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'none' );

				// Add top Article Details class
				parent_container.addClass( 'ephd-hd-article-details-active' );

				// Remove top Classes
				parent_container.removeClass( 'ephd-hd-search--active' );
				parent_container.removeClass( 'ephd-hd-search-error--active' );
				break;

			case 'show_article_load_iframe_details':

				// Hide Results Tabs & Content
				$( '#ephd-hd-search-results__tab-container' ).hide();
				$( '.ephd-hd-search-results__tab-content-container' ).hide();

				// Show Loader
				$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'flex' );
				break;

			case 'show_article_show_iframe_details':

				// Show Article Details
				$( '#ephd-hd__search_results-cat-article-details' ).show();

				// Hide Loader
				$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'none' );

				// Add top Article Details class
				parent_container.addClass( 'ephd-hd-article-details-active' );

				// Remove top Classes
				parent_container.removeClass( 'ephd-hd-search--active' );
				parent_container.removeClass( 'ephd-hd-search-error--active' );
				break;

			case 'show_error_message':
				// Hide Results Tabs & Content
				$( '#ephd-hd-search-results__tab-container' ).hide();
				$( '.ephd-hd-search-results__tab-content-container' ).hide();

				// Add top Error class
				parent_container.addClass( 'ephd-hd-search-error--active' );

				// Remove top Classes
				parent_container.removeClass( 'ephd-hd-article-details-active' );

				break;
		}
	}


	/********************************************************************
	 *                      Article Box
	 ********************************************************************/

	// resize iframe if exists
	function resize_iframe() {

		if ( $('#ephd-hd_article-desc_iframe') == 0 ) {
			return;
		}

		let article_desc = $('#ephd-hd_article-desc_iframe');
		let iframe_height = article_desc.contents().find('html').outerHeight( true );

		// If the logo is active, then remove from height.
		let logo_space = 0;
		if ( $( '.ephd-hd-header__logo' ).length ) {
			logo_space = 50;
		}

		if ( iframe_height < 300 ) {
			// Set the size if there isn't much text
			article_desc.height( 300 - logo_space );
		} else {
			article_desc.height( iframe_height );
		}
	}

	// Article Click
	$( 'body' ).on( 'click', '.ephd-hd_article-item', function( e ) {
		
		e.preventDefault();

		// Clear Values -----------------------------------------------------------------------/

		// Clear Height Attribute
		$('.ephd-hd_article-desc').css('height', '');

		// Clear old preview
		$('#ephd-hd_article-desc_iframe').remove();
		$('#ephd-hd_article-desc_excerpt').html('');

		// Set Values -------------------------------------------------------------------------/

		// Get Article URL
		let url = $(this).data('ephd-url');

		// If no URL detected , stop code.
		if ( typeof url == 'undefined' || ! url ) {
			return;
		}

		// Set title
		let article_title = $(this).find('.ephd-hd_article-item__text').text();

		// Set read more link
		$('.ephd-hd_article-link').prop( 'href', url );

		// Get the Preview mode type
		let preview_mode = $(this).data('ephd-target');

		// Set Default Error Message
		let article_excerpt = ephd_help_dialog_vars.article_preview_not_available;

		switch ( preview_mode ) {

			case 'direct':
				window.open(url, '_blank').focus();
				return;

			case 'iframe':
				preview_mode_iframe( url );
				return;

			case 'excerpt':
			default:
				preview_mode_excerpt( article_title, article_excerpt, $(this) );
				return;
		}
	});

	// iframe preview mode for articles
	function preview_mode_iframe( url ){

		let iframe = document.createElement('iframe');
		iframe.src = url;
		iframe.id = 'ephd-hd_article-desc_iframe';
		iframe.className = 'ephd-hd_article-desc';

		show_content( 'show_article_load_iframe_details' );

		$('.ephd-hd_article-item-details').get(0).appendChild(iframe);

		$('#ephd-hd_article-desc_iframe').on( 'load', function(){

			show_content( 'show_article_show_iframe_details' );

			let iframe = $('#ephd-hd_article-desc_iframe').contents();

			// insert user defined styles to hide Article elements
			let user_styles = $('#ephd-user-defined-values-inline-css');
			if ( user_styles.length ) {
				iframe.find('body').prepend(user_styles.clone());
			}

			// add hidden class for elements which can be shown with js
			iframe.find('body').append(`<style>.ephd-hd_article_preview-hidden { display: none!important;}</style>`);
			iframe.find('body').append(`<link rel='stylesheet' href='${ephd_help_dialog_vars.iframe_styles_url}' media='all' />`);
			iframe.find('html').addClass('ephd-preview-iframe');
			iframe.find('body').addClass( ephd_help_dialog_vars.active_theme_class );
			iframe.find('body').addClass('ephd-hd_article-desc__body');

			// remove not needed tags
			let excludedClasses = [
				'eckb-article-back-navigation-container',
				'eckb-article-content-breadcrumb-container',
				'eckb-article-content-toolbar-container',
				'eckb-article-content-created-date-container',
				'eckb-article-content-last-updated-date-container',
				'eckb-article-content-author-container',
				'eckb-article-content-footer',
				'eckb-article-toc'
			];
			iframe.find('body *').each(function(){
				let $el = $(this);

				// special kb tags
				if ( ~excludedClasses.indexOf( $el.prop('id').toLowerCase() ) ) {
					$el.hide();
				}

				// special kb classes
				excludedClasses.forEach( function( className ) {
					if ( $el.hasClass( className ) ) {
						$el.hide();
					}
				});

				if ( $el.closest('#eckb-article-content').length || $el.find('#eckb-article-content').length ) {
					return true;
				}

				let tag = $el.prop('tagName').toLowerCase();
				if ( tag == 'link' || tag == 'script' ) {
					return true;
				}

				$el.hide();
				$el.addClass('ephd-hd_article_preview-hidden');
			});

			iframe.find('#eckb-article-body').parents().each(function(){
				let el = $(this).get();

				el[0].style.setProperty('margin-top', '0px', 'important');
				el[0].style.setProperty('margin-bottom', '0px', 'important');
				el[0].style.setProperty('padding-top', '0px', 'important');
				el[0].style.setProperty('padding-bottom', '0px', 'important');
			});

			resize_iframe();
			// delay for animation for iframe width
			setTimeout(resize_iframe, 700);
		} );
	}

	// Excerpt preview mode for articles
	function preview_mode_excerpt( article_title, article_excerpt, article ){

		// load excerpt
		let $input = $('#ephd-hd__search-terms');
		let $widget_id = $input.data( 'ephd-widget-id' );
		let post_id = article.data( 'ephd-post-id' );
		let excerpt_type = '';
		let post_type = '';
		let post_status = '';
		let postData = {
			action: 'ephd_get_post_content',
			post_id: post_id,
			_wpnonce: ephd_help_dialog_vars.nonce,
			widget_id: $widget_id,
		};
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ephd_help_dialog_vars.ajaxurl,
			data: postData,
			beforeSend: function (xhr) {
				show_content( 'hide_content' );
			}
		}).done(function (response) {

			show_content( 'show_article_details' );

			if ( response.post_content.length === 0 ) {
				return true;
			}

			article_excerpt  = response.post_content;

			/**
			 * There are two types: ( Classes applied for custom CSS )
			 *  - custom-excerpt:  If user enters the value in the metabox for that specific article/post
			 *  - trimmed-content: If nothing is entered, WP will get the Post Content ( trimmed )
			 */
			excerpt_type = response.excerpt_type;

			/**
			 * There are two types: ( Classes applied for custom CSS )
			 *  - post: This is a post preview
			 *  - eckb: This is a article preview
			 */
			post_type = response.post_type;

			/**
			 *  post_status
			 *  Can be protected with password
			 */
			post_status = response.post_status;


			return true;

		}).always(function () {

			let excerpt_type_class 	= ' ephd-article-excerpt-type--' + excerpt_type;
			let post_type_class 	= ' ephd-article-post-type--' + post_type.substring(0, 4);
			let post_status_class 	= ' ephd-article-status-type--' + post_status;


			$( '#ephd-hd_article-desc_excerpt' ).html('' +
				'<div class="ephd-hd-article__excerpt-container ' + excerpt_type_class + ' ' + post_type_class + ' ' + post_status_class + ' "> ' +
					'<div class="ephd-hd-excerpt__header"><h1 class="ephd-hd_article-title">' + article_title + '</h1></div> ' +
					'<div class="ephd-hd-excerpt__body">' + article_excerpt + '</div> ' +
				'</div>' );
		});
	}

	// Back to FAQs button click
	$( document.body ).on( 'click', '.ephd-hd__faq__back-btn', function( e ) {
		show_content( 'show_search_results' );
	});

	// Internal link inside article preview
	$('#ephd-hd_article-desc').contents().on('click', 'a', function(){
		let url = $(this).prop('href');

		if ( typeof url == 'undefined' || ! url || url == '#') {
			return false;
		}

		window.open( url, '_blank').focus();
		return false;
	});


	/********************************************************************
	 *                      Search Box
	 ********************************************************************/

	// Trigger User Input: auto search with delay and check input, show tooltip
	$( document.body ).on( 'input', '#ephd-hd__search-terms', function( e ) {

		let $term = $( this ).val();
		let $term_array = $term.split( ' ' );

		$('.ephd-hd__search-tooltip').removeClass( 'ephd-hd__search-tooltip--active' );

		let i = 0;

		// filter duplicated spaces
		$term_array.forEach(function( str ){
			if ( str ) {
				i++;
			}

			if ( i > 3 ) {
				$('.ephd-hd__search-tooltip').addClass( 'ephd-hd__search-tooltip--active' );
				return false;
			}
		});

		if ( $term.length >= 3 ) {  // will cause search to be invoked by this
			help_dialog_live_search( $( this ), 500 );
			$( '.ephd-hd-faq__list__item-container' ).removeClass( 'ephd-hd-faq__list__item--active' );
		}
	});

	// Trigger Search: press Enter
	$( document.body ).on( 'keydown', '#ephd-hd__search-terms', function( e ) {
		let $term = $( this ).val();

		if ( e.keyCode == 13 && $term.length >= 3 ) {
			help_dialog_live_search( $( this ), 0 );
			$( '.ephd-hd-faq__list__item-container' ).removeClass( 'ephd-hd-faq__list__item--active' );
		}

		// Esc button
		if ( e.keyCode == 27 ) {
			$('.ephd-hd__search-tooltip').removeClass( 'ephd-hd__search-tooltip--active' );
		}
	});

	// Trigger Search: press search icon
	$( document.body ).on( 'click', '.ephd-hd__search-terms__icon', function() {
		let $term_input = $( this ).closest( '#ephd-hd__search-form' ).find( '#ephd-hd__search-terms' );
		let $term = $term_input.val();
		if ( $term.length >= 3 ) {
			help_dialog_live_search( $term_input, 0 );
			$( '.ephd-hd-faq__list__item-container' ).removeClass( 'ephd-hd-faq__list__item--active' );
		}
	});


	// cleanup search if search keywords deleted or length < 3
	$( document.body ).on( 'keyup', "#ephd-hd__search-terms", function ( event ) {

		// article search with ajax 
		if ( ! $( this ).val() || $( this ).val().length < 3 ) {
			show_content( 'show_home' );
		}
		
		// js search in the faq 
		if ( ! $( this ).val() ) {
			show_content( 'show_home' );
		}
	});

	// to store setTimeout in live search
	let live_search_timeout;

	// to store searched values in the current session to avoid duplicates in DB
	let session_searched_values = [];

	function help_dialog_live_search( $input, $delay ) {

		let $this_input = $input,
			$search_value = $this_input.val(),
			$widget_id = $this_input.data( 'ephd-widget-id' ),
			$page_id = $this_input.closest('#ephd-help-dialog').data( 'ephd-page-id' );

		// clear existing timeout to prevent double request (auto search + Enter press)
		clearTimeout( live_search_timeout );

		live_search_timeout = setTimeout( function(){
			if ( $search_value === $this_input.val() ) {

				// Record search in DB flag for controller
				// 0 - (do not record) if value already searched in the current session
				// 1 - (record) if new value
				let record_search = session_searched_values.includes( $search_value ) ? 0 : 1;

				// add search value to session array
				session_searched_values.push( $search_value );

				let postData = {
					action: 'ephd_search',
					search_terms: $search_value,
					widget_id: $widget_id,
					page_id: $page_id,
					lang: ephd_help_dialog_vars.lang,
					record_search: record_search,
				}, faq_results = '',
					article_results = '',
					post_results = '',
					no_results = '';
				
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: ephd_help_dialog_vars.ajaxurl,
					data: postData,
					beforeSend: function (data) {

						// Show Loading Icon
						$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'flex' );

						// Clear all results.
						$( '.ephd-hd-faq-container' ).hide();

					}

				}).done(function (response) {
					response = ( response ? response : '' );

					$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'none' );

					if ( response.error || response.status !== 'success') {
						//noinspection JSUnresolvedVariable
						no_results = ephd_help_dialog_vars.msg_try_again;
					} else {
						
						if ( response.no_results ) {
							no_results = response.no_results;
						}
						
						if ( response.faq_results ) {
							faq_results = response.faq_results;
						}
						
						if ( response.article_results ) {
							article_results = '<ul>' + response.article_results + '</ul>';
						}

						if ( response.post_results ) {
							post_results = '<ul>' + response.post_results + '</ul>';
						}
					}

				}).fail(function (response, textStatus, error) {
					//noinspection JSUnresolvedVariable
					no_results = ephd_help_dialog_vars.msg_try_again + '. [' + ( error ? error : ephd_help_dialog_vars.unknown_error ) + ']';

				}).always(function ( response ) {

					show_content( 'show_search_results' );

					// Show Breadcrumbs
					parent_container.addClass( 'ephd-hd-search--active' );
					
					$( '.ephd-hd__contact-us__link' ).unbind( 'click' ).on( 'click', ephd_initialize_contact_us_link );

					if ( no_results.length > 0 ) {

						$( '#ephd-hd__search_results__errors' ).html(no_results);
						show_content( 'show_error_message' );

						$( '#ephd-help-dialog .ephd-hd-no-results__keywords' ).text(  $search_value );

						// Found results
					} else {
						// Clear all Values.
						$( '#ephd-hd__search-results-faqs-tab' ).hide();
						$( '#ephd-hd__search-results-articles-tab' ).hide();
						$( '#ephd-hd__search-results-post-tab' ).hide();
						$( '#ephd-hd__search_results__errors' ).html(' ');
						$( '.ephd-hd-results__tab' ).removeClass( 'ephd-hd-results__tab--active' );
						$( '.ephd-hd-results__tab-content ' ).removeClass( 'ephd-hd-results__tab-content--active' );
						$( '#ephd-hd__search_results-cat-article-details' ).hide();
						$( '#ephd-hd-search-results__tab-container' ).removeClass( 'ephd-hd-search-results__tab-count--1 ephd-hd-search-results__tab-count--2 ephd-hd-search-results__tab-count--3 ephd-hd-search-results__tab-count--long-text' );

						let $firstTab = '';

						// Count visible tabs to control icon indicator and tab width / sizes
						let search_tabs_count = 0;

						// Count Text length
						let faq_text_length = $( '#ephd-hd__search-results-faqs-tab .ephd-hd-results__tab__text' ).text().length;
						let article_text_length = $( '#ephd-hd__search-results-articles-tab .ephd-hd-results__tab__text' ).text().length;
						let post_text_length = $( '#ephd-hd__search-results-post-tab .ephd-hd-results__tab__text' ).text().length;

						// Populate divs with content and show the tabs based on found results.
						// Show FAQs
						if ( faq_results.length > 0 ) {
							$( '#ephd-hd__search-results-faqs-tab' ).css( 'display', 'flex' );
							$( '#ephd-hd__search_results__faqs' ).html( faq_results );
							$firstTab = 'faq';
							search_tabs_count++;
						}
						// Show Articles
						if ( article_results.length > 0 ) {
							$( '#ephd-hd__search-results-articles-tab' ).css( 'display', 'flex' );
							$( '#ephd-hd__search_results__articles' ).html( article_results );
							$firstTab = ( $firstTab === '' ) ? 'articles' : $firstTab;
							search_tabs_count++;
						}
						// Show Posts
						if ( post_results.length > 0 ) {
							$( '#ephd-hd__search-results-post-tab' ).css( 'display', 'flex' );
							$( '#ephd-hd__search_results__posts' ).html( post_results );
							$firstTab = ( $firstTab === '' ) ? 'posts' : $firstTab;
							search_tabs_count++;
						}

						// If the text is too long, we apply a top class and adjust font size and remove the tab active indicator.
						if ( faq_text_length > 10 || article_text_length > 10 || post_text_length > 10 ) {
							$( '#ephd-hd-search-results__tab-container' ).addClass( 'ephd-hd-search-results__tab-count--long-text' );
						}

						// Add top Class for Tab Container
						$( '#ephd-hd-search-results__tab-container' ).addClass( 'ephd-hd-search-results__tab-count--'+search_tabs_count );

						// Set the First Tab and it's content to active and displayed. Focus on the active tab for arrow key navigation
						switch($firstTab) {
							case 'faq':
								$( '#ephd-hd__search-results-faqs-tab' ).addClass( 'ephd-hd-results__tab--active' ).trigger( 'click' ).focus();
								$( '#ephd-hd__search_results__faqs' ).addClass( 'ephd-hd-results__tab-content--active' );
								break;
							case 'articles':
								$( '#ephd-hd__search-results-articles-tab' ).addClass( 'ephd-hd-results__tab--active' ).trigger( 'click' ).focus();
								$( '#ephd-hd__search_results__articles' ).addClass( 'ephd-hd-results__tab-content--active' );
								break;
							case 'posts':
								$( '#ephd-hd__search-results-post-tab' ).addClass( 'ephd-hd-results__tab--active' ).trigger( 'click' ).focus();
								$( '#ephd-hd__search_results__posts' ).addClass( 'ephd-hd-results__tab-content--active' );
								break;
						}
					}

					// hide search hint
					$('.ephd-hd__search-tooltip').removeClass( 'ephd-hd__search-tooltip--active' );
				});
			}
		}, $delay );
	}

	// Back Search Button
	$( document.body ).on( 'click', '.ephd-hd__search-back-btn', function () {
		show_content( 'show_home' );
	});

	// Breadcrumb clicks
	$( document.body ).on( 'click', '.ephd-hd__breadcrumb_text', function () {

		let $breadcrumb_link = $( this ).attr( 'data-ephd-breadcrumb' );

		switch( $breadcrumb_link ) {
			case 'home':
				show_content( 'show_home' );
				break;
			case 'search_results':
				show_content( 'show_search_results' );
				break;
			case 'article':
				show_content( 'show_article_details' );
				break;
		}

	});

	// Switch search results between FAQs and Articles
	$( document.body ).on( 'click', '.ephd-hd-results__tab', function () {

		// Clear all results.
		$( '.ephd-hd-results__tab' ).removeClass( 'ephd-hd-results__tab--active' );
		$( '.ephd-hd-results__tab-content ' ).removeClass( 'ephd-hd-results__tab-content--active' );

		// Add Active Class to Tab
		$( this ).addClass( 'ephd-hd-results__tab--active' );
		let $TabType = $( this ).attr( 'data-ephd-tab' );

		// Show Tab Content, Add Active Class
		$('.ephd-hd-results__tab-content[data-ephd-tab-content=' + $TabType + ']').addClass( 'ephd-hd-results__tab-content--active' );

	});


	/********************************************************************
	*                      Help dialog Toggle
	********************************************************************/
	$( document ).on( 'click', '.ephd-hd-toggle', function() {

		let help_dialog_box = $( '#ephd-help-dialog' );

		// Change the Toggle Icon
		let toggle_icon = $( this ).find( '.ephd-hd-toggle__icon' );
		toggle_icon.toggleClass( toggle_icon.data( 'ephd-toggle-icons' ) );

		// Show / Hide Dialog Box
		$( '.ephd-hd-search-container' ).hide();
		help_dialog_box.slideToggle( 400, function() {
			$( '.ephd-hd-search-container' ).show();
		});

		// Set Toggle Status
		$( this ).toggleClass( 'ephd-hd-toggle--off ephd-hd-toggle--on' );

		// Toggle attributes
		$( this ).attr( 'aria-pressed', function( i, attr ) { return attr === 'true' ? 'false' : 'true'; } );
		help_dialog_box.attr( 'tabindex', function( i, attr ) { return attr === '0' ? '-1' : '0'; } );

		// Do not continue for admin preview
		if ( $( this ).closest( '.ephd-wp__widget-form' ).length ) {
			return;
		}

		// Add class if the Help Dialog box was actually opened by user
		help_dialog_box.addClass( 'ephd-hd-toggle--check' );

		// Analytics: count launcher open
		if ( $( this ).hasClass( 'ephd-hd-toggle--on' ) ) {
			count_analytics( 'impressions', 'view' ); // Count impressions until delay if Dialog Opened
			count_analytics( 'launcher-open', 'click_1' );
		}

		// once HD is open, add focus to the active tab to activate Keyboard Arrow Keys
		if ( $( '.ephd-hd-tab--active' ).length ) {
			$( '.ephd-hd-tab--active' ).focus();
		// add focus to container if tabs are hidden
		} else {
			$( '#ephd-hd-body-container div[role="tabpanel"]:first' ).focus();
		}
	});

	// Trigger toggle when user press 'enter' key and the toggle is in focus
	$( document ).on( 'keydown', '.ephd-hd-toggle', function( e ) {
		switch ( e.which ) {
			case 13: $( this ).trigger( 'click' ); break;
			default: break;
		}
	} );

	// If user uses the Tab key on tabs
	$( document ).on( 'keydown', '.ephd-hd-tab', function( e ) {
		if ( e.which === 9 ) {
			e.preventDefault();
			// FAQs Tab
			if ( $( this ).attr( 'id' ) === 'ephd-hd-faq-tab' ) {
				$( '#ephd-hd-body__content-container .ephd-hd-faq__list__item-container:first' ).focus();
			}
			// Contact Tab
			if ( $( this ).attr( 'id' ) === 'ephd-hd-contact-us-tab' ) {
				$( '#ephd-hd-body__contact-container .ephd-hd__contact-form-field:first input' ).focus();
			}
		}
	} );

	// If user uses the Tab key on search field OR search result tabs
	$( document ).on( 'keydown', '#ephd-hd__search-terms, .ephd-hd-results__tab', function( e ) {
		if ( e.which === 9 ) {
			e.preventDefault();

			let $active_tab = $( '.ephd-hd-results__tab-content--active' );

			// Articles and Posts results
			if ( $active_tab.find( '.ephd-hd_article-item:first' ).length ) {
				$active_tab.find( '.ephd-hd_article-item:first' ).focus();
			// FAQ Articles results
			} else if ( $active_tab.find( '.ephd-hd-faq__list__item-container:first' ).length ) {
				$active_tab.find( '.ephd-hd-faq__list__item-container:first' ).focus();
			// initial FAQs list
			} else {
				$( '#ephd-hd-body__content-container .ephd-hd-faq__list__item-container:first' ).focus();
			}
		}
	} );

	// Open FAQs If user uses the Enter Key on focused FAQ
	$( document ).on( 'keydown', '.ephd-hd-faq__list__item-container', function( e ) {
		if ( e.which === 13 ) {
			e.preventDefault();
			$( this ).find( '.ephd-hd__item__question' ).click();
		}
	} );

	// Open FAQs If user uses the Enter Key on focused search result Articles
	$( document ).on( 'keydown', '.ephd-hd_article-item', function( e ) {
		if ( e.which === 13 ) {
			e.preventDefault();
			$( this ).click();
		}
	} );

	// FAQ item Click
	$( document.body ).on( 'click', '.ephd-hd__item__question', function(e) {

		let current_container = $( this ).closest( '.ephd-hd-faq__list__item-container' );

		// If clicked again on already opened Question
		if ( current_container.hasClass( 'ephd-hd__element--active' ) ) {
			current_container.removeClass( 'ephd-hd__element--active' );
			return;
		}

		// Close currently opened Questions
		current_container.parent().find( '.ephd-hd-faq__list__item-container' ).removeClass( 'ephd-hd__element--active' );

		// Add Active class to clicked on Question.
		current_container.toggleClass( 'ephd-hd__element--active' );

		//Scroll to div top
		let el = document.querySelector( '.ephd-hd-faq__list__item-container.ephd-hd__element--active' );
		el.scrollIntoView({
			block: "start",
			behavior: "smooth"
		});
	});

	// Contact Us link when no Questions at Home step
	function ephd_initialize_contact_us_link() {
		$( '.ephd-hd-tab[data-ephd-target-tab=' + $( this ).attr( 'data-ephd-target-tab' ) + ']' ).trigger( 'click' );
	}
	$( document.body ).on( 'click', '.ephd-hd__contact-us__link', ephd_initialize_contact_us_link );


	/********************************************************************
	 *                      Help dialog Header Events
	 ********************************************************************/
	$( document.body ).on( 'click', '.ephd-hd-tab', function (){

		if ( $(this).hasClass('ephd-hd-tab--disabled') ) {
			return;
		}

		// faq admin page fix
		if ( parent_container.length == 0 ) {
			parent_container = $( '#ephd-help-dialog' );
		}

		// Get or define values.
		let target_tab = $( this ).attr( 'data-ephd-target-tab' );

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
				show_content( 'show_home' );
				break;
			case 'contact':
				// Set the top Container Class for the active Tab.
				parent_container.addClass( 'ephd-hd-contact-tab--active' );
				parent_container.removeClass( 'ephd-hd-faqs-tab--active' );
				parent_container.removeClass( 'ephd-hd-search--active' );
				parent_container.removeClass( 'ephd-hd-chat-tab--active' );

				// Analytics: count contact tab was opened
				let object_id = parseInt( $( '#ephd-hd__contact-form input[name="contact_form_id"]' ).val() );
				if ( object_id ) {
					count_analytics( 'contact-tab-opened', 'click_1', object_id );
				}
				break;
			default: break;
		}

		//Remove Top Tab Active Classes
		parent_container.find( '.ephd-hd-tab' ).removeClass( 'ephd-hd-tab--active' );

		// Add Top Tab Active Class
		$( this ).addClass( 'ephd-hd-tab--active' );

		// change tabindex
		parent_container.find( '.ephd-hd-tab' ).attr( 'tabindex', '-1' ).attr( 'aria-selected', false );
		$( this ).attr( 'tabindex', '0' ).attr( 'aria-selected', true );
	});

	// Switch tabs with arrow keys
	$( document ).on( 'keydown', '#ephd-hd-top-tab-container', function( e ) {
		let all_tabs = $( '.ephd-hd-tab' );
		let active_tab = $( this ).find( '.ephd-hd-tab--active' );
		let target_tab = null;

		switch ( e.which ) {

			// Left arrow
			case 37:
				target_tab = active_tab.prev( '.ephd-hd-tab' );
				if ( ! target_tab.length ) {
					target_tab = all_tabs[all_tabs.length - 1];
				}
				break;

			// Right arrow
			case 39:
				target_tab = active_tab.next( '.ephd-hd-tab' );
				if ( ! target_tab.length ) {
					target_tab = all_tabs[0];
				}
				break;

			default: break;
		}

		if ( target_tab ) {
			$( target_tab ).trigger( 'click' ).focus();
		}
	} );

	// Switch search result tabs with arrow keys
	$( document ).on( 'keydown', '#ephd-hd-search-results__tab-container', function( e ) {
		let all_tabs = $( '.ephd-hd-results__tab:visible' );
		let active_tab = $( this ).find( '.ephd-hd-results__tab--active' );
		let target_tab = null;

		switch ( e.which ) {

			// Left arrow
			case 37:
				target_tab = active_tab.prev( '.ephd-hd-results__tab:visible' );
				if ( ! target_tab.length ) {
					target_tab = all_tabs[all_tabs.length - 1];
				}
				break;

			// Right arrow
			case 39:
				target_tab = active_tab.next( '.ephd-hd-results__tab:visible' );
				if ( ! target_tab.length ) {
					target_tab = all_tabs[0];
				}
				break;

			default: break;
		}

		if ( target_tab ) {
			$( target_tab ).trigger( 'click' ).focus();
		}
	} );

	/*************************************************************************************************
	 *
	 *          FRONTEND: Contact Us box
	 *
	 ************************************************************************************************/

	$( document ).on( 'submit', '#ephd-hd__contact-form', function( event ){
		event.preventDefault();

		if( ! $("#ephd-help-dialog").is(":visible") ){
			return;
		}

		let form_data = $( this ).serialize();

		// add additional parameter to verify the form is submitted by our JS
		form_data += '&jsnonce=' + ephd_help_dialog_vars.nonce;

		// check if the Help Dialog box was actually opened by user (has class in this case), otherwise stop execution
		if ( ! $( '#ephd-help-dialog' ).hasClass( 'ephd-hd-toggle--check' ) ) {
			return;
		}

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ephd_help_dialog_vars.ajaxurl,
			data: form_data,
			beforeSend: function (xhr) {
				$( '.ephd-hd__contact-form-error' ).html( '' );
				// Show Loader
				$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'flex' );
			}

		}).done(function (response) {

			// Show Loader
			$( '.ephd-hd__loading-spinner__container' ).css( 'display', 'none' );
			
			// success message
			if ( ( typeof response.success !== 'undefined' && response.success !== false ) || ( typeof response.success !== 'undefined' && response.success === true ) ) {
				$( '#ephd-hd__contact-form-body' ).hide();
				$( '.ephd-hd__contact-form-response' ).html( '<div class="ephd-hd__contact-form-response__message"><span>' + response.data + '</span></div>' ).css( "display", "flex" );

			} else {

				// something went wrong
				if ( typeof response.data !== 'undefined' && response.data.length > 5 ) {
					$( '.ephd-hd__contact-form-error' ).html( '<div class="ephd-hd__contact-form-error__message">' + response.data + '</div>' );
				} else {
					$( '.ephd-hd__contact-form-error' ).html( '<div class="ephd-hd__contact-form-error__message">' + ephd_help_dialog_vars.msg_try_again + '</div>' );
				}
			}

		}).fail(function (response, textStatus, error) {
			// something went wrong
			$( '.ephd-hd__contact-form-error' ).html( '<div class="ephd-hd__contact-form-error__message">' + ephd_help_dialog_vars.msg_try_again + '</div>' );

		}).always(function () {

		});
	});

	/********************************************************************
	 *                      Generic Count Analytics function
	 ********************************************************************/
	// Please duplicate any changes in the PRO version

	// to store counted events in the current session
	let session_counted_events = {};

	/**
	 * Generic function to count analytics events.
	 *
	 * @param event_name - from PHP events meta constant: EPHD_Analytics_DB::EVENTS_META
	 * @param column_name - event column name
	 * @param object_id (optional) - article id, post id or contact form id
	 */
	function count_analytics( event_name, column_name, object_id=0 ) {

		// Help dialog selector to retrieve data attribute values (page_id, widget_id)
		let dialog = $( '#ephd-help-dialog' );

		// Should we count analytics? Exclude admin pages, drafts etc. This value is set to "on" for specific types ( Published, Private )
		// See PHP function is_count_analytics
		if ( 'on' !== dialog.data( 'ephd-count-analytics' ) ) {
			return;
		}

		// Unique event session key to count event once per session. Format: event_name + column_name(optional) + object_id(optional).
		// Examples: launcher-open_count_0, contact-tab-opened_count_1, faq-item-click_faq_count_2577, article-item-click_search_count_2569
		// Exclude object_id to count only one FAQ/Article per session
		let session_event_key = event_name + '_' + column_name + '_' + object_id;

		// prepare ajax post data
		let postData = {
			action: 'ephd_count_invocations_action',
			_wpnonce: ephd_help_dialog_vars.nonce,
			page_id: dialog.data( 'ephd-page-id' ),
			widget_id: dialog.data( 'ephd-widget-id' ),
			event_name: event_name,
			column_name: column_name,
			object_id: object_id
		}

		// Count if event is counted for the first time in the current session
		if ( typeof session_counted_events[session_event_key] === 'undefined' ) {

			// Mark an event as counted in the current session
			session_counted_events[session_event_key] = true;

			// Count event
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: ephd_help_dialog_vars.ajaxurl,
				data: postData
			});
		}
	}

	// Prevent actions for Chat links on admin preview
	$( document ).on( 'click', '.ephd-hd-chat-channel a', function( e ) {
		if ( $( '#ephd-help-dialog' ).hasClass( 'ephd-hd-admin-preview' ) ) {
			e.preventDefault();
			return false;
		}
	});
});