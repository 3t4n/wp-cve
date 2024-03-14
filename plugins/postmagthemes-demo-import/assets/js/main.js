jQuery(
	function ( $ ) {
		'use strict';

		/**
		 * ---------------------------------------
		 * ------------- Events ------------------
		 * ---------------------------------------
		 */

		/**
		 * No or Single predefined demo import button click.
		 */
		$( '.js-pmdi-import-data' ).on(
			'click',
			function () {
				// Reset response div content.
				$( '.js-pmdi-ajax-response' ).empty();

				// Prepare data for the AJAX call
				var data = new FormData();
				data.append( 'action', 'pmdi_import_demo_data' );
				data.append( 'security', pmdi.ajax_nonce );
				data.append( 'selected', $( '#pmdi__demo-import-files' ).val() );
				if ( $( '#pmdi__content-file-upload' ).length && $('#pmdi__content-file-upload').get(0).files.length ) {
					var contentFile = $('#pmdi__content-file-upload')[0].files[0];
					var contentFileExt = contentFile.name.split('.').pop();

					if ( -1 === [ 'xml' ].indexOf( contentFileExt.toLowerCase() ) ) {
						alert( pmdi.texts.content_filetype_warn );

						return false;
					} 
					data.append( 'content_file', $( '#pmdi__content-file-upload' )[0].files[0] );
				}
				if ( $( '#pmdi__widget-file-upload' ).length && $('#pmdi__widget-file-upload').get(0).files.length) {
					var widgetsFile = $('#pmdi__widget-file-upload')[0].files[0];
					var widgetsFileExt = widgetsFile.name.split('.').pop();

					if ( -1 === [ 'json', 'wie' ].indexOf( widgetsFileExt.toLowerCase() ) ) {
						alert( pmdi.texts.widgets_filetype_warn );

						return false;
					}
						 data.append( 'widget_file', $( '#pmdi__widget-file-upload' )[0].files[0] );
				}
				if ( $( '#pmdi__customizer-file-upload' ).length && $('#pmdi__customizer-file-upload').get(0).files.length ) {
					var customizerFile = $('#pmdi__customizer-file-upload')[0].files[0];
					var customizerFileExt = customizerFile.name.split('.').pop();

					if ( -1 === [ 'dat' ].indexOf( customizerFileExt.toLowerCase() ) ) {
						alert( pmdi.texts.customizer_filetype_warn );

						return false;
					}   
					data.append( 'customizer_file', $( '#pmdi__customizer-file-upload' )[0].files[0] );
				}

				// AJAX call to import everything (content, widgets, before/after setup)
				ajaxCall( data );

			}
		);

		/**
		 * Grid Layout import button click.
		 */
		$( '.js-pmdi-gl-import-data' ).on(
			'click',
			function () {
				var selectedImportID = $( this ).val();
				var $itemContainer   = $( this ).closest( '.js-pmdi-gl-item' );

				// If the import confirmation is enabled, then do that, else import straight away.
				if ( pmdi.import_popup ) {
					  displayConfirmationPopup( selectedImportID, $itemContainer );
				} else {
					 gridLayoutImport( selectedImportID, $itemContainer );
				}
			}
		);

		/**
		 * Grid Layout categories navigation.
		 */
		(function () {
			// Cache selector to all items
			var $items            = $( '.js-pmdi-gl-item-container' ).find( '.js-pmdi-gl-item' ),
				fadeoutClass      = 'pmdi-is-fadeout',
				fadeinClass       = 'pmdi-is-fadein',
				animationDuration = 200;

			// Hide all items.
			var fadeOut = function () {
				var dfd = jQuery.Deferred();

				$items
					.addClass( fadeoutClass );

				setTimeout(
					function() {
						$items
						.removeClass( fadeoutClass )
						.hide();

						dfd.resolve();
					},
					animationDuration
				);

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
				.addClass( 'pmdi-is-fadein' );

				setTimeout(
					function() {
						$items
						.removeClass( fadeinClass );

						dfd.resolve();
					},
					animationDuration
				);
			};

			var animate = function ( category ) {
				var dfd = jQuery.Deferred();

				var promise = fadeOut();

				promise.done(
					function () {
						fadeIn( category, dfd );
					}
				);

				return dfd;
			};

			$( '.js-pmdi-nav-link' ).on(
				'click',
				function( event ) {
					event.preventDefault();

					// Remove 'active' class from the previous nav list items.
					$( this ).parent().siblings().removeClass( 'active' );

					// Add the 'active' class to this nav list item.
					$( this ).parent().addClass( 'active' );

					var category = this.hash.slice( 1 );

					// show/hide the right items, based on category selected
					var $container = $( '.js-pmdi-gl-item-container' );
					$container.css( 'min-width', $container.outerHeight() );

					var promise = animate( category );

					promise.done(
						function () {
							$container.removeAttr( 'style' );
						}
					);
				}
			);
		}());

		/**
		 * Grid Layout search functionality.
		 */
		$( '.js-pmdi-gl-search' ).on(
			'keyup',
			function( event ) {
				if ( 0 < $( this ).val().length ) {
					  // Hide all items.
					  $( '.js-pmdi-gl-item-container' ).find( '.js-pmdi-gl-item' ).hide();

					  // Show just the ones that have a match on the import name.
					  $( '.js-pmdi-gl-item-container' ).find( '.js-pmdi-gl-item[data-name*="' + $( this ).val().toLowerCase() + '"]' ).show();
				} else {
					 $( '.js-pmdi-gl-item-container' ).find( '.js-pmdi-gl-item' ).show();
				}
			}
		);

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
			$( '.js-pmdi-ajax-response' ).empty();

			// Hide all other import items.
			$itemContainer.siblings( '.js-pmdi-gl-item' ).fadeOut( 500 );

			$itemContainer.animate(
				{
					opacity: 0
					 },
				500,
				'swing',
				function () {
					$itemContainer.animate(
						{
							opacity: 1
						},
						500
					)
				}
			);

				 // Hide the header with category navigation and search box.
				 $itemContainer.closest( '.js-pmdi-gl' ).find( '.js-pmdi-gl-header' ).fadeOut( 500 );

				 // Append a title for the selected demo import.
				 $itemContainer.parent().prepend( '<h3>' + pmdi.texts.selected_import_title + '</h3>' );

				 // Remove the import button of the selected item.
				 $itemContainer.find( '.js-pmdi-gl-import-data' ).remove();

				 // Prepare data for the AJAX call
				 var data = new FormData();
				 data.append( 'action', 'pmdi_import_demo_data' );
				 data.append( 'security', pmdi.ajax_nonce );
				 data.append( 'selected', selectedImportID );

				 // AJAX call to import everything (content, widgets, before/after setup)
				 ajaxCall( data );
		}

		/**
		 * Display the confirmation popup.
		 *
		 * @param int selectedImportID The selected import ID.
		 * @param obj $itemContainer The jQuery selected item container object.
		 */
		function displayConfirmationPopup( selectedImportID, $itemContainer ) {

			  // $( 'select[name=author]' ).on( 'click', function () {
			 // alert('tset');
			  // });

			  var $dialogContiner         = $( '#js-pmdi-modal-content' );
			  var currentFilePreviewImage = pmdi.import_files[ selectedImportID ]['import_preview_image_url'] || pmdi.theme_screenshot;
			  // var user_list 				= pmdi.import_files[ selectedImportID ]['user_list'];
			  var previewImageContent = '';
			  var importNotice        = pmdi.import_files[ selectedImportID ]['import_notice'] || '';
			  var importNoticeContent = '';
			var dialogOptions         = $.extend(
				{
					'dialogClass': 'wp-dialog',
					'resizable':   false,
					'height':      'auto',
					'modal':       true
				  },
				pmdi.dialog_options,
				{
					'buttons':
					[
					{
						text: pmdi.texts.dialog_no,
						click: function() {
							$( this ).dialog( 'close' );
						}
					},
					{
						text: pmdi.texts.dialog_yes,
						class: 'button  button-primary',
						click: function() {
							$( this ).dialog( 'close' );
							gridLayoutImport( selectedImportID, $itemContainer );
						}
					}
					]
				}
			);

			if ( '' === currentFilePreviewImage ) {
				 previewImageContent = '<p>' + pmdi.texts.missing_preview_image + '</p>';
			} else {
				previewImageContent = '<div class="pmdi__modal-image-container"><img src="' + currentFilePreviewImage + '" alt="' + pmdi.import_files[ selectedImportID ]['import_file_name'] + '"></div>'
			}
			// var previewUserList = user_list;
			// Prepare notice output.
			if ( '' !== importNotice ) {
				importNoticeContent = '<div class="pmdi__modal-notice  pmdi__demo-import-notice">' + importNotice + '</div>';
			}

			// Populate the dialog content.
			$dialogContiner.prop( 'title', pmdi.texts.dialog_title );
			$dialogContiner.html(
				'<p class="pmdi__modal-item-title">' + pmdi.import_files[ selectedImportID ]['import_file_name'] + '</p>' +
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

			// console.log(data);
			$.ajax(
				{
					method:      'POST',
					url:         pmdi.ajax_url,
					data:        data,
					contentType: false,
					processData: false,
					beforeSend:  function() {
						$( '.js-pmdi-ajax-loader' ).show();
					}
				}
			)
			.done(
				function( response ) {

					if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
							ajaxCall( data );
					} else if ( 'undefined' !== typeof response.status && 'customizerAJAX' === response.status ) {
						   // Fix for data.set and data.delete, which they are not supported in some browsers.
						   var newData = new FormData();
						   newData.append( 'action', 'pmdi_import_customizer_data' );
						   newData.append( 'security', pmdi.ajax_nonce );

						   // Set the wp_customize=on only if the plugin filter is set to true.
						if ( true === pmdi.wp_customize_on ) {
							newData.append( 'wp_customize', 'on' );
						}

						ajaxCall( newData );
					} else if ( 'undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status ) {
						  // Fix for data.set and data.delete, which they are not supported in some browsers.
						  var newData = new FormData();
						  newData.append( 'action', 'pmdi_after_import_data' );
						  newData.append( 'security', pmdi.ajax_nonce );
						  ajaxCall( newData );
					} else if ( 'undefined' !== typeof response.message ) {
						 $( '.js-pmdi-ajax-response' ).append( '<p>' + response.message + '</p>' );
						 $( '.js-pmdi-ajax-loader' ).hide();

						 // Trigger custom event, when PMDI import is complete.
						 $( document ).trigger( 'pmdiImportComplete' );
					} else {
						$( '.js-pmdi-ajax-response' ).append( '<div class="notice  notice-error  is-dismissible"><p>' + response + '</p></div>' );
						$( '.js-pmdi-ajax-loader' ).hide();
					}
				}
			)
			.fail(
				function( error ) {
					$( '.js-pmdi-ajax-response' ).append( '<div class="notice  notice-error  is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>' );
					$( '.js-pmdi-ajax-loader' ).hide();
				}
			);
		}
	}
);
