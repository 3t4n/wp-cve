( function() {

	// Vars
		var $ = jQuery;
		var mthCapturedString = '';

		window.mthEditorMethods = {
			// Insert Button
				insert: function() {

					// Vars
						// Template
							template = _.template( $( '#mth-insert-popup-template' ).html() );
							popupHTML = template({
								templateObjects: templatesData.templateObjects,
								templateGroups: templatesData.templateGroupObjects
							});
							popupHTML += $( '#mth-insert-popup-template-buttons' ).html();

					// Popup
						mthEditorMethods.openPopup( popupHTML );

					// Events
						// Change List
							$( 'input.mth-template-group-checkbox' ).on( 'click', function( e ) { //

								// Vars
									var mthTemplateGroups = [];
									var changedValue = $( this ).val();
									var optionClass = '';

								// Checked
									if( e.target.checked ) { 

										mthTemplateGroups[ changedValue ] = changedValue;
											$( '.mth-template-group' ).css({ 'display': 'none' });

									}
								// Check Gone
									else { 

										delete mthTemplateGroups[ changedValue ];
										
										// With Checked Filters
											if( $( '.mth-template-group-checkbox:checked' ).exists() ) { 
													$( '#mth-templates-list > .group-' + changedValue ).css({ 'display': 'none' });
										// Without Checked Filters
											} else {
												$( '.mth-template-group' ).css({ 'display': 'initial' });
											}

									}

								// Group Classes
									if( mthTemplateGroups.length > 0 ) { 
										for ( var prop in mthTemplateGroups ) {
											optionClass += '.group-' + prop;
										} 
										$( optionClass ).css({ 'display': 'initial' });
									}

								// Message
									$( '#mth-template-list-popup-notification' ).html( '&nbsp;&nbsp;List Changed.' );

								// Message Fade
									setTimeout( 
										function( $ ) { $( '#mth-template-list-popup-notification' ).empty(); },
										1000,
										jQuery
									);

							} );

						// Select Template
							$( '#mth-templates-list' ).on( 'change', function( e ) {

								if( $( '#mth-templates-list' ).val() === 'none' ) {
									$( '#mth-insert-content-display textarea' ).text( '' );
									return;
								}

								$( '#mth-insert-content-display' ).val( $( this ).val() );

							} );

						// Insert Click
							$( '#mth-insert-button' ).on( 'click', function() {
								QTags.insertContent( $( '#mth-insert-content-display' ).val() );
								mthEditorMethods.closePopup();
							} );
						// Cancel Click
							$( '#mth-insert-cancel-button, #mth-popup-background' ).on( 'click', function() {
								mthEditorMethods.closePopup();						
							} );

				},

			// Save Button
				save: function() {

					// Vars
						if( mthCapturedString == '' ) {
							mthCapturedString = $( '#content' ).val();
						}

					// Template
						template = _.template( $( '#mth-make-popup-template' ).html() );
						popupHTML = template({
							"mthCapturedText": mthCapturedString
						}) + $( '#mth-make-popup-template-bottons' ).html();

					// Popup
						mthEditorMethods.openPopup( popupHTML );

					// Event
						// Make Button
							$( '#mth-make-button' ).on( 'click', function( e ) {

								$( '.button' ).addClass( 'disabled' );
								mthEditorMethods.saveTemplate();

							} );

						// Cancel Button
							$( '#mth-make-cancel-button, #mth-popup-background' ).on( 'click', function( e ) {

								mthEditorMethods.closePopup();

							} );

				},

					saveTemplate: function() {

						// Vars
							templateTitle = $( '#mth-make-template-title' ).val();
							templateGroup = $( '#mth-make-template-group' ).val();
							templateText = $( '#mth-make-tepmlate-display' ).val();

						// Text Title if is not empty
							if( templateTitle == '' ) {
								$( '#mth-popup-notification' ).text( 'Fill the Required' );
								return;
							}

						// Text Check if is not empty
							if( templateText == '' ) {
								$( '#mth-popup-notification' ).text( 'Fill the Required' );
								return;
							}

						// Nonce
							mth_template_nonce = $( '#mth-templates-nonce' ).val();

						// AJAX Call 
						// action: mth_make_template_from_content
							$.ajax({
								type: "POST",
								url: ajaxurl,
								dataType: "json",
								data: {
									"templateTitle": templateTitle,
									"templateGroup": templateGroup,
									"templateText": templateText,
									"mth_template_nonce": mth_template_nonce,
									action: "mth_make_template_from_content"
								},
								error: function( jqHXR, textStatus, errorThrown ) {
									console.log( textStatus );
								},
								success: function( data, textStatus, jqHXR ) {

									//console.log( data ); // チェック用

									// Append to Global Object
										// Template
											templatesData.templateObjects.push( data.template_object );

										// Group
											templateGroupLoop: 
											for( var key in data.template_groups ) {
												for( var key2 in templatesData.templateGroupObjects ) {
													if( templatesData.templateGroupObjects[ key2 ].term_id != data.template_groups[ key ].term_id ) {
														templatesData.templateGroupObjects.push( data.template_groups[ key ] );
														continue templateGroupLoop;
													}
												}
											}

									// Close
										mthEditorMethods.closePopup();
								}
							}).done( function( data ) {

								// Button
									$( '.button' ).removeClass( 'disabled' );
									mthEditorMethods.closePopup();

							});

					},

			// Popup
				// Open
					openPopup: function( popupHTML ) {
						$( '#mth-popup-background' ).css({
							'display': 'block'
						});
						$( '#mth-popup-wrapper' ).html( popupHTML ).css({
							'display': 'block'
						});
					},
				// Close
					closePopup: function() {
						$( '#mth-popup-wrapper' ).css({
							'display': 'none'
						}).empty();
						$( '#mth-popup-background' ).css({
							'display': 'none'
						});
					},

		}

	$( document ).ready( function() {

		// Insert
			$( ".mth-tempalte-media-button.insert-mth-template" ).on( "click", function( e ) {

				e.preventDefault();

				$wpEditorWrap = $( this ).parentsUntil( '.wp-editor-wrap' ).parent();

				if( $wpEditorWrap.hasClass( 'html-active' ) ) {

					mthEditorMethods.insert();

				} else if( $wpEditorWrap.hasClass( 'tmce-active' ) ) {

					mthEditor.execCommand( 
						'mthItemPopup', 
						e, 
						{ // Params
							itemName : 'insert'
						}
					);

				}

			});

		// Make
			$( ".mth-tempalte-media-button.make-mth-template" ).on( "click", function( e ) {

				e.preventDefault();

				$wpEditorWrap = $( this ).parentsUntil( '.wp-editor-wrap' ).parent();

				if( $wpEditorWrap.hasClass( 'html-active' ) ) {

					mthEditorMethods.save();

				} else if( $wpEditorWrap.hasClass( 'tmce-active' ) ) {

					mthEditor.execCommand( 
						'mthItemPopup', 
						e, 
						{ // Params
							itemName : 'make'
						}
					);

				}

			});

		// Events
			$( window ).on( 'select', function( e ) {
				mthCapturedString = window.getSelection().toString();
			});

	});

}) ();