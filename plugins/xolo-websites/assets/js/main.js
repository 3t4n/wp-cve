jQuery( function ( $ ) {
	'use strict';

	/**
	 * ---------------------------------------
	 * ------------- Events ------------------
	 * ---------------------------------------
	 */

	/**
	 * No or Single predefined demo import button click.
	 */
	$( '.xl-import-data' ).on( 'click', function () {

		// Reset response div content.
		$( '.js-xolo_web-ajax-response' ).empty();

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append( 'action', 'XOLO_WEB_import_demo_data' );
		data.append( 'security', XOLO_WEBS.ajax_nonce );
		data.append( 'selected', $( '#XOLO_WEB__demo-import-files' ).val() );
		if ( $('#XOLO_WEB__content-file-upload').length ) {
			data.append( 'content_file', $('#XOLO_WEB__content-file-upload')[0].files[0] );
		}
		if ( $('#XOLO_WEB__widget-file-upload').length ) {
			data.append( 'widget_file', $('#XOLO_WEB__widget-file-upload')[0].files[0] );
		}
		if ( $('#XOLO_WEB__customizer-file-upload').length ) {
			data.append( 'customizer_file', $('#XOLO_WEB__customizer-file-upload')[0].files[0] );
		}
		if ( $('#xolo_web__redux-file-upload').length ) {
			data.append( 'redux_file', $('#xolo_web__redux-file-upload')[0].files[0] );
			data.append( 'redux_option_name', $('#xolo_web__redux-option-name').val() );
		}

		// AJAX call to import everything (content, widgets, before/after setup)
		ajaxCall( data );

	});


	/**
	 * Grid Layout import button click.
	 */
	$( '.xl-demo-import-data' ).on( 'click', function () {
		var selectedImportID = $( this ).val();
		var $itemContainer   = $( this ).closest( '.xl-sites-items' );

		// If the import confirmation is enabled, then do that, else import straight away.
		if ( XOLO_WEBS.import_popup ) {
			displayConfirmationPopup( selectedImportID, $itemContainer );
		}
		else {
			gridLayoutImport( selectedImportID, $itemContainer );
		}
	});


	/**
	 * Grid Layout categories navigation.
	 */
	(function () {
		// Cache selector to all items
		var $items = $( '.xl-sites-wrapper' ).find( '.xl-sites-items' ),
			fadeoutClass = 'xolo_web-is-fadeout',
			fadeinClass = 'xolo_web-is-fadein',
			animationDuration = 200;

		// Hide all items.
		var fadeOut = function () {
			var dfd = jQuery.Deferred();

			$items
				.addClass( fadeoutClass );

			setTimeout( function() {
				$items
					.removeClass( fadeoutClass )
					.hide();

				dfd.resolve();
			}, animationDuration );

			return dfd.promise();
		};

		var fadeIn = function ( category, dfd ) {
			var filter = category ? '[data-categories*="' + category + '"]' : 'div';

			if ( 'all' === category ) {
				filter = 'div';
			}

			$items
				.filter( filter )
				.show()
				.addClass( 'xolo_web-is-fadein' );

			setTimeout( function() {
				$items
					.removeClass( fadeinClass );

				dfd.resolve();
			}, animationDuration );
		};

		var animate = function ( category ) {
			var dfd = jQuery.Deferred();

			var promise = fadeOut();

			promise.done( function () {
				fadeIn( category, dfd );
			} );

			return dfd;
		};

		$( '.xl-tabs-link' ).on( 'click', function( event ) {
			event.preventDefault();

			// Remove 'active' class from the previous nav list items.
			//$( this ).parent().siblings().removeClass( 'active' );

			// Add the 'active' class to this nav list item.
			//$( this ).parent().addClass( 'active' );

			var category = this.hash.slice(1);

			// show/hide the right items, based on category selected
			var $container = $( '.xl-sites-wrapper' );
			$container.css( 'min-width', $container.outerHeight() );

			var promise = animate( category );

			promise.done( function () {
				$container.removeAttr( 'style' );
			} );
		} );

		var ScrollSetting = {
		    navBarTravelling: false,
		    navBarTravelDirection: "",
			 navBarTravelDistance: 150
		}

		document.documentElement.classList.remove("no-js");
		document.documentElement.classList.add("js");

		// Out advancer buttons
		var scrollerLeft = document.getElementById("scrollerLeft");
		var scrollerRight = document.getElementById("scrollerRight");
		// the indicator
		var tabActive = document.getElementById("tabActive");

		var xlTabScroll = document.getElementById("xlTabScroll");
		var xlTabScrollMenu = document.getElementById("xlTabScrollMenu");

		xlTabScroll.setAttribute("data-overflowing", determineOverflow(xlTabScrollMenu, xlTabScroll));

		// Set the indicator
		moveIndicator(xlTabScroll.querySelector("[aria-selected=\"true\"]"));

		// Handle the scroll of the horizontal container
		var last_known_scroll_position = 0;
		var ticking = false;

		function doSomething(scroll_pos) {
		    xlTabScroll.setAttribute("data-overflowing", determineOverflow(xlTabScrollMenu, xlTabScroll));
		}

		xlTabScroll.addEventListener("scroll", function() {
		    last_known_scroll_position = window.scrollY;
		    if (!ticking) {
		        window.requestAnimationFrame(function() {
		            doSomething(last_known_scroll_position);
		            ticking = false;
		        });
		    }
		    ticking = true;
		});

		scrollerLeft.addEventListener("click", function() {
			// If in the middle of a move return
		    if (ScrollSetting.navBarTravelling === true) {
		        return;
		    }
		    // If we have content overflowing both sides or on the left
		    if (determineOverflow(xlTabScrollMenu, xlTabScroll) === "left" || determineOverflow(xlTabScrollMenu, xlTabScroll) === "both") {
		        // Find how far this panel has been scrolled
		        var availableScrollLeft = xlTabScroll.scrollLeft;
		        // If the space available is less than two lots of our desired distance, just move the whole amount
		        // otherwise, move by the amount in the ScrollSetting
		        if (availableScrollLeft < ScrollSetting.navBarTravelDistance * 2) {
		            xlTabScrollMenu.style.transform = "translateX(" + availableScrollLeft + "px)";
		        } else {
		            xlTabScrollMenu.style.transform = "translateX(" + ScrollSetting.navBarTravelDistance + "px)";
		        }
		        // We do want a transition (this is set in CSS) when moving so remove the class that would prevent that
		        xlTabScrollMenu.classList.remove("xl-tabs-menu-no-transition");
		        // Update our ScrollSetting
		        ScrollSetting.navBarTravelDirection = "left";
		        ScrollSetting.navBarTravelling = true;
		    }
		    // Now update the attribute in the DOM
		    xlTabScroll.setAttribute("data-overflowing", determineOverflow(xlTabScrollMenu, xlTabScroll));
		});

		scrollerRight.addEventListener("click", function() {
		    // If in the middle of a move return
		    if (ScrollSetting.navBarTravelling === true) {
		        return;
		    }
		    // If we have content overflowing both sides or on the right
		    if (determineOverflow(xlTabScrollMenu, xlTabScroll) === "right" || determineOverflow(xlTabScrollMenu, xlTabScroll) === "both") {
		        // Get the right edge of the container and content
		        var navBarRightEdge = xlTabScrollMenu.getBoundingClientRect().right;
		        var navBarScrollerRightEdge = xlTabScroll.getBoundingClientRect().right;
		        // Now we know how much space we have available to scroll
		        var availableScrollRight = Math.floor(navBarRightEdge - navBarScrollerRightEdge);
		        // If the space available is less than two lots of our desired distance, just move the whole amount
		        // otherwise, move by the amount in the ScrollSetting
		        if (availableScrollRight < ScrollSetting.navBarTravelDistance * 2) {
		            xlTabScrollMenu.style.transform = "translateX(-" + availableScrollRight + "px)";
		        } else {
		            xlTabScrollMenu.style.transform = "translateX(-" + ScrollSetting.navBarTravelDistance + "px)";
		        }
		        // We do want a transition (this is set in CSS) when moving so remove the class that would prevent that
		        xlTabScrollMenu.classList.remove("xl-tabs-menu-no-transition");
		        // Update our ScrollSetting
		        ScrollSetting.navBarTravelDirection = "right";
		        ScrollSetting.navBarTravelling = true;
		    }
		    // Now update the attribute in the DOM
		    xlTabScroll.setAttribute("data-overflowing", determineOverflow(xlTabScrollMenu, xlTabScroll));
		});

		xlTabScrollMenu.addEventListener(
		    "transitionend",
		    function() {
		        // get the value of the transform, apply that to the current scroll position (so get the scroll pos first) and then remove the transform
		        var styleOfTransform = window.getComputedStyle(xlTabScrollMenu, null);
		        var tr = styleOfTransform.getPropertyValue("-webkit-transform") || styleOfTransform.getPropertyValue("transform");
		        // If there is no transition we want to default to 0 and not null
		        var amount = Math.abs(parseInt(tr.split(",")[4]) || 0);
		        xlTabScrollMenu.style.transform = "none";
		        xlTabScrollMenu.classList.add("xl-tabs-menu-no-transition");
		        // Now lets set the scroll position
		        if (ScrollSetting.navBarTravelDirection === "left") {
		            xlTabScroll.scrollLeft = xlTabScroll.scrollLeft - amount;
		        } else {
		            xlTabScroll.scrollLeft = xlTabScroll.scrollLeft + amount;
		        }
		        ScrollSetting.navBarTravelling = false;
		    },
		    false
		);

		// Handle setting the currently active link
		xlTabScrollMenu.addEventListener("click", function(e) {
			var links = [].slice.call(document.querySelectorAll(".xl-tabs-link"));
			links.forEach(function(item) {
				item.setAttribute("aria-selected", "false");
			})
			e.target.setAttribute("aria-selected", "true");
			moveIndicator(e.target);
		});

		function moveIndicator(item, color) {
		    var textPosition = item.getBoundingClientRect();
		    var container = xlTabScrollMenu.getBoundingClientRect().left;
		    var distance = textPosition.left - container;
		    tabActive.style.transform = "translateX(" + (distance + xlTabScrollMenu.scrollLeft) + "px) scaleX(" + textPosition.width * 0.01 + ")";
		    if (color) {
		        tabActive.style.backgroundColor = colour;
		    }
		}

		function determineOverflow(content, container) {
		    var containerMetrics = container.getBoundingClientRect();
		    var containerMetricsRight = Math.floor(containerMetrics.right);
		    var containerMetricsLeft = Math.floor(containerMetrics.left);
		    var contentMetrics = content.getBoundingClientRect();
		    var contentMetricsRight = Math.floor(contentMetrics.right);
		    var contentMetricsLeft = Math.floor(contentMetrics.left);
			 if (containerMetricsLeft > contentMetricsLeft && containerMetricsRight < contentMetricsRight) {
		        return "both";
		    } else if (contentMetricsLeft < containerMetricsLeft) {
		        return "left";
		    } else if (contentMetricsRight > containerMetricsRight) {
		        return "right";
		    } else {
		        return "none";
		    }
		}
	}());

	$('.xl-demo-disabled').on('click mousedown', function(e) {
	  e.preventDefault();
	  //alert($(this).is(':disabled'));
	  if (!$(this).attr('disabled') == 'disabled') {
	  	$(".xl-btn-active").removeClass('shake');
	    return false;
	  } else {
	  		$(".xl-btn-active").addClass('shake');			
		}
	});

	/**
	 * Grid Layout search functionality.
	 */
	function delay(callback, ms) {
	  var timer = 0;
	  return function() {
	    var context = this, args = arguments;
	    clearTimeout(timer);
	    timer = setTimeout(function () {
	      callback.apply(context, args);
	    }, ms || 0);
	  };
	}
	$( '.xl-quick-search' ).keyup(delay(function (e) {
		var searchVal 	= $(this).val().toLowerCase();
		if (0 < $(this).val().length && $('.xl-sites-items').data('name') !== '') {
			// Hide all items.
			$( '.xl-sites-wrapper' ).find( '.xl-sites-items' ).hide();
			$( '.xl-sites-empty' ).show();

			// Show just the ones that have a match on the import name.
			$( '.xl-sites-wrapper' ).find( '.xl-sites-items[data-name*="' + searchVal + '"]' ).show();
		}
		else {			
			$( '.xl-sites-wrapper' ).find( '.xl-sites-items:not(.xl-sites-empty)' ).show();
			$( '.xl-sites-empty' ).hide();
		}
	}, 250));

	/**
	 * ---------------------------------------
	 * --------Helper functions --------------
	 * ---------------------------------------
	 */

	/**
	 * Prepare grid layout import data and execute the AJAX call.
	 *
	 * @param int selectedImportID The selected import ID.
	 * @param obj $itemContainer The jQuery selected item container object.
	 */
	function gridLayoutImport( selectedImportID, $itemContainer ) {
		// Reset response div content.
		$( '.js-xolo_web-ajax-response' ).empty();

		// Hide all other import items.
		$itemContainer.siblings( '.xl-sites-items' ).fadeOut( 500 );

		$itemContainer.animate({
			opacity: 0
		}, 500, 'swing', function () {
			$itemContainer.animate({
				opacity: 1
			}, 500 )
		});

		// Hide the header with category navigation and search box.
		$itemContainer.closest( '.xl-page-content' ).find( '.xl-tab-panel' ).fadeOut( 500 );

		// Append a title for the selected demo import.
		$itemContainer.prepend( '<h3 class="xl-selected-demo">' + XOLO_WEBS.texts.selected_import_title + '</h3>' );

		// Remove the import button of the selected item.
		$itemContainer.find( '.xl-demo-import-data' ).remove();

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append( 'action', 'XOLO_WEB_import_demo_data' );
		data.append( 'security', XOLO_WEBS.ajax_nonce );
		data.append( 'selected', selectedImportID );

		var info = [];
			info[0] = 'elementor';
			info[1] = 'contact-form-7';

		var plugin_data = {
			action: 		'xolo_web_install_act_plugin',
			plugin_slug:	info
		};

		$( '.js-XOLO_WEB-install-plugin' ).addClass( 'is-plugin-install' );

		$.ajax({
			method:      'POST',
			url:         XOLO_WEBS.ajax_url,
			data:        plugin_data,
			success: function( response ) {
				$( '.js-XOLO_WEB-install-plugin' ).removeClass( 'is-plugin-install' );
				// AJAX call to import everything (content, widgets, before/after setup)
				ajaxCall( data );
			}
		})
	}

	/**
	 * Display the confirmation popup.
	 *
	 * @param int selectedImportID The selected import ID.
	 * @param obj $itemContainer The jQuery selected item container object.
	 */
	function displayConfirmationPopup( selectedImportID, $itemContainer ) {
		var $dialogContiner         = $( '#xl-modal-content' );
		var currentFilePreviewImage = XOLO_WEBS.import_files[ selectedImportID ]['import_preview_image_url'] || XOLO_WEBS.theme_screenshot;
		var previewImageContent     = '';
		var importNotice            = XOLO_WEBS.import_files[ selectedImportID ]['import_notice'] || '';
		var importNoticeContent     = '';
		var dialogOptions           = $.extend(
			{
				'dialogClass': 'wp-dialog',
				'resizable':   false,
				'height':      'auto',
				'modal':       true
			},
			XOLO_WEBS.dialog_options,
			{
				'buttons':
				[
					{
						text: XOLO_WEBS.texts.dialog_no,
						click: function() {
							$(this).dialog('close');
						}
					},
					{
						text: XOLO_WEBS.texts.dialog_yes,
						class: 'button  button-primary',
						click: function() {
							$(this).dialog('close');
							gridLayoutImport( selectedImportID, $itemContainer );
						}
					}
				]
			});

		if ( '' === currentFilePreviewImage ) {
			previewImageContent = '<p>' + XOLO_WEBS.texts.missing_preview_image + '</p>';
		}
		else {
			previewImageContent = '<div class="XOLO_WEB__modal-image-container"><img src="' + currentFilePreviewImage + '" alt="' + XOLO_WEBS.import_files[ selectedImportID ]['import_file_name'] + '"></div>'
		}

		// Prepare notice output.
		if( '' !== importNotice ) {
			importNoticeContent = '<div class="XOLO_WEB__modal-notice  XOLO_WEB__demo-import-notice">' + importNotice + '</div>';
		}

		// Populate the dialog content.
		$dialogContiner.prop( 'title', XOLO_WEBS.texts.dialog_title );
		$dialogContiner.html(
			'<p class="XOLO_WEB__modal-item-title">' + XOLO_WEBS.import_files[ selectedImportID ]['import_file_name'] + '</p>' +
			previewImageContent +
			importNoticeContent
		);

		// Display the confirmation popup.
		$dialogContiner.dialog( dialogOptions );
	}

	/**
	 * The main AJAX call, which executes the import process.
	 *
	 * @param FormData data The data to be passed to the AJAX call.
	 */
	function ajaxCall( data ) {
		$.ajax({
			method:      'POST',
			url:         XOLO_WEBS.ajax_url,
			data:        data,
			contentType: false,
			processData: false,
			beforeSend:  function() {
				$( '.js-XOLO_WEB-ajax-loader' ).addClass('is-import-success');
			}
		})
		.done( function( response ) {
			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				ajaxCall( data );
			}
			else if ( 'undefined' !== typeof response.status && 'customizerAJAX' === response.status ) {
				// Fix for data.set and data.delete, which they are not supported in some browsers.
				var newData = new FormData();
				newData.append( 'action', 'XOLO_WEB_import_customizer_data' );
				newData.append( 'security', XOLO_WEBS.ajax_nonce );

				// Set the wp_customize=on only if the plugin filter is set to true.
				if ( true === XOLO_WEBS.wp_customize_on ) {
					newData.append( 'wp_customize', 'on' );
				}

				ajaxCall( newData );
			}
			else if ( 'undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status ) {
				// Fix for data.set and data.delete, which they are not supported in some browsers.
				var newData = new FormData();
				newData.append( 'action', 'XOLO_WEB_after_import_data' );
				newData.append( 'security', XOLO_WEBS.ajax_nonce );
				ajaxCall( newData );
			}
			else if ( 'undefined' !== typeof response.message ) {
				$( '.js-xolo_web-ajax-response' ).append( '<p>' + response.message + '</p>' );
				$( '.js-XOLO_WEB-ajax-loader' ).removeClass('is-import-success');

				// Trigger custom event, when XOLO_WEB import is complete.
				$( document ).trigger( 'XOLOWEBImportComplete' );
			}
			else {
				$( '.js-xolo_web-ajax-response' ).append( '<div class="notice  notice-error  is-dismissible"><p>' + response + '</p></div>' );
				$( '.js-XOLO_WEB-ajax-loader' ).removeClass('is-import-success');
			}
		})
		.fail( function( error ) {
			$( '.js-xolo_web-ajax-response' ).append( '<div class="notice  notice-error  is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>' );
			$( '.js-XOLO_WEB-ajax-loader' ).removeClass('is-import-success');
		});
	}
} );
