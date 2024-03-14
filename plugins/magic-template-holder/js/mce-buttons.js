( function() {

	var $ = jQuery;

	var mthCapturedText = '';

	tinymce.create( 'tinymce.plugins.mthButtons', {
		init : function( editor, url ) {

			// Popup Window Manage ( want to close all window at once )
				var popupWindowHolder = {
					popupWindows: [],
					set: function( popupWindow ) {
						this.popupWindows.push( popupWindow )
					},
					closeAll: function() {
						_( this.popupWindows ).each( function( popupWindow ) {
							popupWindow.close();
						})
					},
					getItemSettingsFormByItem: function( itemType, JSON ) {

					}
				};

			// Insert Button
				editor.addButton( 'mth-insert-template', {
					icon: 'dashicons-download',
					tooltip: mthLocalizedData.insertTemplate,
					onclick: function( e ) {

						editor.execCommand( 
							'mthItemPopup', 
							e, 
							{ // Params
								itemName : 'insert'
							}
						);

					}
				});

			// Insert Button
				editor.addButton( 'mth-make-template', {
					icon: 'dashicons-upload',
					tooltip: mthLocalizedData.makeTemplate,
					onclick: function( e ) {

						editor.execCommand( 
							'mthItemPopup', 
							e, 
							{ // Params
								itemName : 'make'
							}
						);

					}
				});

			// Command
				// Popup
					editor.addCommand( 'mthItemPopup', function( e, params ) {

						if( mthCapturedText == '' ) {
							mthCapturedText = $( '#content' ).val();
						}

						var template = '';
						var insertedHTML = '';

						if( _.isEmpty( params.itemName ) )
							params.itemName = 'none';

						// Setup Insert Form
							if( params.itemName === 'insert' ) {

								// Popup Title
									headerTitle = mthLocalizedData.insertTemplate;

								// Template
									template = _.template( $( '#mth-insert-popup-template' ).html() );
									insertedHTML = template({
										templateObjects: templatesData.templateObjects,
										templateGroups: templatesData.templateGroupObjects
									}) + $( '#mth-insert-popup-template-buttons' ).html();

							}

						// Setup Make Form
							else if( params.itemName === 'make' ) {

								// Content
									if( mthCapturedText == '' ) {
										mthCapturedText = editor.selection.getContent();
									}

								// Popup Title
									headerTitle = mthLocalizedData.makeTemplate;

								// Template
									template = _.template( $( '#mth-make-popup-template' ).html() );
									insertedHTML = template({
										"mthCapturedText": mthCapturedText
									}) + $( '#mth-make-popup-template-bottons' ).html();

							}

						// Popup
							editor.execCommand( 
								'mthOpenPopup', 
								e, 
								{ // Params
									popupHTML : insertedHTML
								}
							);

						// Visual Editor ?
						/*
							// ID Contents
								var editorId = "mth-insert-content-display";
								var editorContents = decodeURIComponent( mthCapturedText );

							// tinyMCE Settings
								tinyMCEGlobalSettings =  tinyMCEPreInit.mceInit.content;
								console.log( tinyMCEGlobalSettings );
								tinyMCEGlobalSettings.selector = "#" + editorId;
								tinyMCEGlobalSettings.height = "200px";
								//console.log( tinyMCEGlobalSettings );

							// tinyMCE Initialize
								tinymce.init( tinyMCEGlobalSettings ); 
								tinyMCE.execCommand( 'mceAddEditor', false, editorId ); 
								quicktags({ 
									id : editorId
								});

							// Editor Contents
								$editorBody = $( 'iframe#' + editorId + '_ifr' ).contents().find( 'body' );
								$editorBody.html( editorContents );
						*/

						// Events
							// For Insert Button
								if( params.itemName === 'insert' ) {

									// Change List
										$( 'input.mth-template-group-checkbox' ).click( function( e ) { //

											// Vars
												var mthTemplateGroups = [];
												var changedValue = $( context ).val();
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
										$( '#mth-templates-list' ).change( function( e ) {

											if( $( '#mth-templates-list' ).val() === 'none' ) {
												$( '#mth-insert-content-display textarea' ).text( '' );
												return;
											}

											$( '#mth-insert-content-display' ).val( $( this ).val() );

										} );

									// Insert
										$( '#mth-insert-button' ).on( 'click', function( e ) {

											editor.insertContent( $( '#mth-insert-content-display' ).val() );
											editor.execCommand( 
												'mthClosePopup', 
												e, 
												{ // params

												}
											);

										} );

									// Cancel
										$( '#mth-insert-cancel-button, #mth-popup-background' ).on( 'click', function( e ) {

											editor.execCommand( 
												'mthClosePopup', 
												e, 
												{ // params

												}
											);

										} );

								}

							// For Make Button
								else if( params.itemName === 'make' ) {

									// Make
										$( '#mth-make-button' ).on( 'click', function( e ) {

											// AJAX Call
											editor.execCommand( 
												'mthMakeTemplate', 
												e, 
												{ // params

												}
											);

											editor.execCommand( 
												'mthClosePopup', 
												e, 
												{ // params

												}
											);

										});

									// Cancel
										$( '#mth-make-cancel-button, #mth-popup-background' ).on( 'click', function( e ) {

											editor.execCommand( 
												'mthClosePopup', 
												e, 
												{ // params

												}
											);

										});

								}

					} );

						// Open
							editor.addCommand( 'mthOpenPopup', function( e, params ) {

								$( '#mth-popup-background' ).css({
									'display': 'block'
								});
								$( '#mth-popup-wrapper' ).html( params.popupHTML ).css({
									'display': 'block'
								});

							} );

						// Close
							editor.addCommand( 'mthClosePopup', function( e, params ) {

								$( '#mth-popup-wrapper' ).css({
									'display': 'none'
								}).empty();
								$( '#mth-popup-background' ).css({
									'display': 'none'
								});

							} );

				// Make Template
					editor.addCommand( 'mthMakeTemplate', function( e, params ) {

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

						// ノンスの値
							mth_template_nonce = $( '#mth-templates-nonce' ).val();

						//action: mth_make_template_from_content
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

									// For Check
										//console.log( data );

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
										editor.execCommand( 
											'mthClosePopup', 
											e, 
											{ // params

											}
										);

								}

							}).done( function( data ) {

								// Button
									$( '.button' ).removeClass( 'disabled' );

							});

					} );

			// Event
				// Selection Change
					editor.on( "selectionchange", function( e ) {

						mthCapturedText = editor.selection.getContent();

					} );

			// 
				window.mthEditor = editor;

		},
		createControl : function( n, cm ) {
			return null;
		},
    });
    tinymce.PluginManager.add( 'mth_buttons', tinymce.plugins.mthButtons );

} ) ();