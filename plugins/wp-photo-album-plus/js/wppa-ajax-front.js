// wppa-ajax-front.js
//
// Contains frontend ajax modules
// Dependancies: wppa.js and default wp $ library
//
var wppaJsAjaxVersion = '8.6.04.003';

// The new AJAX rendering routine Async
function wppaDoAjaxRender( mocc, ajaxurl, newurl, addHilite ) {

	// Fix the url
	if ( wppaLang != '' ) ajaxurl += '&lang='+wppaLang;
	if ( wppaAutoColumnWidth[mocc] ) ajaxurl += '&resp=1';
	ajaxurl += '&wppa-cw='+wppaGetContainerWidth(mocc);
	if ( addHilite && _wppaCurIdx[mocc] && _wppaId[mocc][_wppaCurIdx[mocc]] ) ajaxurl += '&wppa-hilite=' + _wppaId[mocc][_wppaCurIdx[mocc]];

	// Ajax possible, or no newurl defined ?
	if ( wppaCanAjaxRender || ! newurl ) {

		jQuery.ajax( { 	url: 		ajaxurl,
						async: 		true,
						type: 		'GET',
						timeout: 	60000,
						beforeSend: function( xhr ) {

										// If it is a slideshow: Stop slideshow before pushing it on the stack
										if ( _wppaSSRuns[mocc] ) _wppaStop( mocc );

										// Display the spinner
										jQuery( '#wppa-ajax-spin-'+mocc ).fadeIn();
									},
						success: 	function( xresult, status, xhr ) {

										if ( xresult.substr( 0, 1 ) != '{' ) {
											wppaConsoleLog( 'Ajax render result starts with ' + xresult.substr(0,100), 'force');
											if ( xresult.substr( 0, 8 ) == '<!DOCTYPE' || xresult.substr( 0, 5 ) == '<html' ) {
												if ( newurl.length > 0 ) {
													document.location.href = newurl;
												}
												else {
													alert( __( 'Frontend Ajax request failed. Try a different setting in Advanced settings -> System -> I -> Item 5', 'wp-photo-album-plus' ) );
												}
											}
											else {
												alert( __( 'Unexpected output', 'wp-photo-album-plus' )+': '+xresult.substr(0,100)+'...' );
											}
										}
										else {
											var theResult = JSON.parse(xresult);
											var result = theResult.html + '<script>' + theResult.js + '</script>';

											// Do not render modal if behind button. When behind button, there is no newurl,
											// so we test on the existence of newurl to see if it is behind button
											if ( wppaRenderModal && newurl ) {

												// Init dialog options
												var opt = {
													modal:		true,
													resizable: 	true,
													width:		wppaGetContainerWidth( mocc ),
													show: 		{
																	effect: 	"fadeIn",
																	duration: 	400
																},
													closeText: 	"",
													classes: 	"wppa-dialog",
												};

												// Open modal dialog
												wppaPrepareModal(mocc);
												jQuery( '#wppa-modal-container-'+mocc ).html( result );
												jQuery( '#wppa-modal-container-'+mocc ).dialog( opt );

												var isSlide = result.indexOf('slide-frame') != -1;
												var theTop;
												if (isSlide) {
													if (wppaAreaMaxFracSlide == 1) {
														theTop = wppaWindowHeight() / 20;
													}
													else {
														theTop = ((wppaWindowHeight() - wppaWindowHeight() * wppaAreaMaxFracSlide)/2);
													}
												}
												else {
													if (wppaAreaMaxFrac == 1) {
														theTop = wppaWindowHeight() / 20;
													}
													else {
														theTop = ((wppaWindowHeight() - wppaWindowHeight() * wppaAreaMaxFrac)/2);
													}
												}

												// Adjust styles
												jQuery( '.ui-dialog' ).css( {
																				boxShadow: 			'0px 0px 5px 5px #aaaaaa',
																				borderRadius: 		wppaBoxRadius+'px',
																				padding: 			'8px',
																				backgroundColor: 	wppaModalBgColor,
																				boxSizing: 			'content-box',
																				zIndex: 			100000,
																				margin: 			'auto',
																				overflow: 			'hidden',
																				position: 			'fixed',
																				left: 				((wppaWindowWidth() - wppaGetContainerWidth(mocc))/2)+'px',
																				top: 				theTop+'px',
																			});
												jQuery( '.ui-dialog-titlebar' ).css(
																						{
																							lineHeight: '0px',
																							height: 	'32px',
																						}
																					);
												jQuery( '.ui-button' ).css(
																			{
																				backgroundImage: 	wppaModalQuitImg,
																				padding:			0,
																				position: 			'absolute',
																				right: 				'8px',
																				top: 				'8px',
																				width: 				'16px',
																				height: 			'16px',
																			});
												jQuery( '.ui-button' ).attr( 'title', 'Close' );

												// Stop a possible slideshow
												jQuery( '.ui-button' ).on( 'click', function() { _wppaStop( mocc ); } );

												// Remove spinner
												jQuery( '.wppa-ajax-spin' ).stop().fadeOut();

												// Adjust modal container max height
									//			jQuery("#wppa-modal-container-"+mocc).css({maxHeight:wppaWindowHeight()});

												// Add nicescroller optionally
												setTimeout(function() {
													if (jQuery().niceScroll) {
														jQuery(".wppa-albumlist-"+mocc).niceScroll(".wppa-nicewrap",{});
														jQuery(".wppa-slidelist-"+mocc).niceScroll(".wppa-nicewrap",{});
													};
												}, 1000);
												jQuery(document).trigger('resize');

											}

											// Not modal or behind button
											else {
												jQuery( '#wppa-container-'+mocc ).html( result );

												// If behind button: show hide buttton
												jQuery( '#wppa-button-hide-'+mocc ).show();
											}


											// Push the stack
											if ( wppaCanPushState && wppaUpdateAddressLine && newurl ) {

												newurl = newurl.split('&amp;').join('&');
												history.pushState( {type: 'ajax'}, "", newurl ); //wppaCurrentUrl );
										//		console.log( 'ajax history pushed ' + newurl ); //wppaCurrentUrl );
											}

											// If lightbox is on board, refresh the imagelist. It has just changed, you know!
											wppaUpdateLightboxes();

											// Update qrcode
											if ( typeof( wppaQRUpdate ) != 'undefined' ) {
												wppaQRUpdate( newurl );
											}

											// Run Autocol?
											wppaColWidth[mocc] = 0;
											_wppaDoAutocol( mocc, 'ajax' );
										}
									},
						error: 		function( xhr, status, error ) {
										wppaConsoleLog( 'wppaDoAjaxRender failed. Error = ' + error + ', status = ' + status );

										// Do it by reload
										if ( newurl ) {
											document.location.href = newurl;
										}
										else {
											document.location.reload(true);
										}
									},
						complete: 	function( xhr, status, newurl ) {

										if ( ! wppaRenderModal && wppaAjaxScroll ) {
											jQuery('html, body').animate({ scrollTop: jQuery("#wppa-container-"+mocc).offset().top - 32 - wppaStickyHeaderHeight }, 1000);
										}

										// Remove spinner
										jQuery( '.wppa-ajax-spin' ).stop().fadeOut();

										// Fake resize
										window.dispatchEvent(new Event('resize'));

										// Hide rightclick optionally
										wppaProtect();
									}
					} );
	}

	// Ajax NOT possible
	else {
		document.location.href = newurl;

		// Run Autocol?
		wppaColWidth[mocc] = 0;	// force a recalc and triggers autocol if needed
		_wppaDoAutocol( mocc, 'nonajax' );
	}
}

function wppaDoFetchShortcodeRendered( mocc, shortcode, page ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl+'?action=wppa&wppa-action=getshortcodedrenderedfenodelay&wppa-shortcode='+shortcode+'&wppa-nonce='+jQuery('#wppa-nonce').val()+'&wppa-occur='+mocc+'&wppa-fromp='+page,
				async: 		true,
				type: 		'GET',
				timeout: 	60000,
				beforeSend: function(xhr) {
								wppaConsoleLog('Fetching delayed shortcode content for mocc = '+mocc);
								jQuery('#wppa-ajax-spin-'+mocc).show();
							},
				success: 	function(xresult, status, xhr) {
								try {
									var theResult = JSON.parse(xresult);
									var result = theResult.html + '<script>' + theResult.js + '</script>';
									wppaConsoleLog('Recieved delayed shortcode content for mocc = '+mocc);
									jQuery('#wppa-container-'+mocc).html(result);
									wppaConsoleLog('Applied delayed shortcode content for mocc = '+mocc);
								}
								catch(e) {
									wppaConsoleLog('wppaDoFetchShortcodeRendered failed. Invalid json data');
								}
							},
				error: 		function(xhr,status,error) {
								wppaConsoleLog('wppaDoFetchShortcodeRendered failed. Error = '+error+', status = '+status);
							},
				complete: 	function(xhr, status) {
								jQuery(document).trigger('resize');
								wppaProtect();
								jQuery('#wppa-ajax-spin-'+mocc).hide();
								wppaInitOverlay();
							}
	});
}

// Set photo status to 'publish'
function wppaAjaxApprovePhoto( photo ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=approve' +
								'&photo-id=' + photo,
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {
									if ( result == 'OK' ) {
										jQuery( '.wppa-approve-' + photo ).css( 'display', 'none' );
									}
									else {
										alert( result );
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxApprovePhoto failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

// Remove photo
function wppaAjaxRemovePhoto( mocc, photo, isslide ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=remove' +
								'&photo-id=' + photo,
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {

									// Remove succeeded?
									rtxt = result.split( '||' );
									if ( rtxt[0] == 'OK' ) {

										// Slide?
										if ( isslide ) {
											jQuery( '#wppa-film-'+_wppaCurIdx[mocc]+'-'+mocc ).attr( 'src', '' );
											jQuery( '#wppa-pre-'+_wppaCurIdx[mocc]+'-'+mocc ).attr( 'src', '' );
											jQuery( '#wppa-film-'+_wppaCurIdx[mocc]+'-'+mocc ).attr( 'alt', 'removed' );
											jQuery( '#wppa-pre-'+_wppaCurIdx[mocc]+'-'+mocc ).attr( 'alt', 'removed' );
											wppaNext( mocc );
										}

										// Thumbnail
										else {
											jQuery( '.wppa-approve-'+photo ).css( 'display', 'none' );
											jQuery( '.thumbnail-frame-photo-'+photo ).css( 'display', 'none' );
										}
									}

									// Remove failed
									else {
										if ( rtxt[3] ) {
											alert( rtxt[3] );
											jQuery( '#wppa-delete-'+photo ).css('text-decoration', 'line-through' );
										}
										else {
											alert( result );
										}
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxRemovePhoto failed. Error = ' + error + ', status = ' + status );
								}
				} );
}

// Set comment status to 'pblish'
function wppaAjaxApproveComment( comment ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=approve' +
								'&comment-id=' + comment,
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {

									// Approve succeeded?
									if ( result == 'OK' ) {
										jQuery( '.wppa-approve-'+comment ).css( 'display', 'none' );
									}

									// Approve failed
									else {
										alert( result );
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxApproveComment failed. Error = ' + error + ', status = ' + status );
								}
				} );

}

// Remove comment
function wppaAjaxRemoveComment( comment ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=remove' +
								'&comment-id=' + comment,
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {

									// Remove succeeded?
									var rtxt = result.split( '||' );
									if ( rtxt[0] == 'OK' ) {
										jQuery( '.wppa-approve-'+comment ).css( 'display', 'none' );
										jQuery( '.wppa-comment-'+comment ).css( 'display', 'none' );
									}
									else {
										alert( result );
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxRemoveComment failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

// Add photo to zip
function wppaAjaxAddPhotoToZip( mocc, id, reload ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
				data: 		'action=wppa' +
							'&wppa-action=addtozip' +
							'&photo-id=' + id,
				async: 		true,
				type: 		'GET',
				timeout: 	60000,
				success: 	function( result, status, xhr ) {

								// Adding succeeded?
								var rtxt = result.split( '||' );
								if ( rtxt[0] == 'OK' ) {

									// For the thumbnails
									jQuery('#admin-choice-'+id+'-'+mocc).html(rtxt[2]);

									// For the slideshow
									jQuery('#admin-choice-'+id+'-'+mocc).val(rtxt[2]);
									jQuery('#admin-choice-'+id+'-'+mocc).prop('disabled', true);
								}
								else {
								}
								alert( rtxt[1] );

								// Reload
								if ( reload ) {
									document.location.reload( true );
								}
							},
				error: 		function( xhr, status, error ) {
								wppaConsoleLog( 'wppaAjaxAddPhotoToZip failed. Error = ' + error + ', status = ' + status );
							},
			} );
}

// Remove photo from zip
function wppaAjaxRemovePhotoFromZip( mocc, id, reload ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
				data: 		'action=wppa' +
							'&wppa-action=removefromzip' +
							'&photo-id=' + id,
				async: 		true,
				type: 		'GET',
				timeout: 	60000,
				success: 	function( result, status, xhr ) {

								// Adding succeeded?
								var rtxt = result.split( '||' );
								if ( rtxt[0] == 'OK' ) {

									// For the thumbnails
								//	jQuery('#admin-choice-'+id+'-'+mocc).html(rtxt[1]);

									// For the slideshow
									jQuery('#admin-choice-rem-'+id+'-'+mocc).val(rtxt[1]);
									jQuery('#admin-choice-rem-'+id+'-'+mocc).prop('disabled', true);
									jQuery('#admin-choice-rem-'+id+'-'+mocc).css('text-decoration', '');
								}
								else {
									alert( result );
								}

								// Reload
								if ( reload ) {
									document.location.reload( true );
								}
							},
				error: 		function( xhr, status, error ) {
								wppaConsoleLog( 'wppaAjaxRemovePhotoFromZip failed. Error = ' + error + ', status = ' + status, 'force' );
							},
			} );
}

// Remove admins choice zipfile
function wppaAjaxDeleteMyZip() {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
				data: 		'action=wppa' +
							'&wppa-action=delmyzip',
				async: 		true,
				type: 		'GET',
				timeout: 	60000,
				success: 	function( result, status, xhr ) {

								// Reload
								document.location.reload( true );

							},
				error: 		function( xhr, status, error ) {
								wppaConsoleLog( 'wppaAjaxDeleteMyZip failed. Error = ' + error + ', status = ' + status );
							},
			} );
}

// Request Info
function wppaAjaxRequestInfo( mocc, id, reload ) {


	dialogHtml =
	'<div class="wppa-modal">'+
	'<h3>' + __( 'Please specify your question', 'wp-photo-album-plus' ) + '</h3>' +
	'<textarea id="wppa-request-info-text-'+mocc+'" style="width:98%;" ></textarea>' +
	'<div style="clear:both;" ></div>' +
	'<input type="button" style="float:left;margin-top:8px;margin-right:8px;" value="' + __( 'Send', 'wp-photo-album-plus' ) + '" onclick="wppaAjaxRequestInfoSend( '+mocc+', \''+id+'\', '+reload+' )" />' +
	'<input type="button" style="float:left;margin-top:8px;margin-right:8px;" value="' + __( 'Cancel', 'wp-photo-album-plus' ) + '" onclick="jQuery( \'#wppa-modal-container-'+mocc+'\' ).dialog( \'close\' );" />'+
	'<div style="clear:both;" ></div></div>';

	// Show dialog first
	var opt = {
				modal:		true,
				resizable: 	true,
				width:		wppaGetContainerWidth( mocc ),
				show: 		{
								effect: 	"fadeIn",
								duration: 	400
							},
				closeText: 	"",
				classes: 	"wppa-dialog",
			};
			wppaPrepareModal(mocc);
			jQuery( '#wppa-modal-container-'+mocc ).html( dialogHtml );
			jQuery( '#wppa-modal-container-'+mocc ).dialog( opt );

			jQuery( '.ui-dialog' ).css( {
											boxShadow: 			'0px 0px 5px 5px #aaaaaa',
											borderRadius: 		wppaBoxRadius+'px',
											padding: 			'8px',
											backgroundColor: 	wppaModalBgColor,
											boxSizing: 			'content-box',
											zIndex: 			100000,
											position: 			'fixed',
											top: 				(wppaWindowHeight()/10)+'px',
										});
			jQuery( '.ui-dialog-titlebar' ).css(
													{
														lineHeight: '0px',
														height: 	'24px',
													}
												)
			jQuery( '.ui-button' ).css(
										{
											backgroundImage: 	wppaModalQuitImg,
											padding:			0,
											position: 			'absolute',
											right: 				'8px',
											top: 				'8px',
											width: 				'16px',
											height: 			'16px',
										});
			jQuery( '.ui-button' ).attr( 'title', 'Close' );
			jQuery(document).trigger('resize');
}

function wppaAjaxRequestInfoSend( mocc, id, reload ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
				data: 		'action=wppa' +
							'&wppa-action=requestinfo' +
							'&photo-id=' + id +
							'&emailtext=' + jQuery('#wppa-request-info-text-'+mocc).val(),
				async: 		true,
				type: 		'GET',
				timeout: 	60000,
				success: 	function( result, status, xhr ) {

								// Request succeeded?
								var rtxt = result.split( '||' );
								if ( rtxt[0] == 'OK' ) {

									// For the slideshow
									jQuery('#request-info-'+id+'-'+mocc).val(rtxt[1]);
									jQuery('#request-info-'+id+'-'+mocc).prop('disabled', true);
//									jQuery('#wppa-modal-container-'+mocc).dialog('close');
								}
								else {
									alert( result );
								}

								// Reload
								if ( reload ) {
									document.location.reload( true );
								}
								else {
									jQuery( '#wppa-modal-container-'+mocc ).dialog( 'close' );
									jQuery( '#wppa-modal-container-'+mocc ).html('');
								}
							},
				error: 		function( xhr, status, error ) {
								wppaConsoleLog( 'wppaAjaxRequestInfoSend failed. Error = ' + error + ', status = ' + status );
							},
			} );
}

// Frontend Edit Photo
function wppaEditPhoto( mocc, id ) {

//	var id 		= String(xid);
//	var name 	= 'Edit Photo '+id;
//	var desc 	= '';
//	var width 	= 500;
//	var height 	= 512;

//	if ( screen.availWidth < width ) width = screen.availWidth;

//	var wnd;

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=front-edit' +
								'&photo-id=' + id +
								'&occur=' + mocc,
					async: 		true,
					type: 		'POST',
					timeout: 	60000,
					beforeSend: function( xhr ) {

								},
					success: 	function( result, status, xhr ) {

									var opt = {
										modal:		true,
										resizable: 	true,
										width:		wppaGetContainerWidth( mocc ),
										show: 		{
														effect: 	"fadeIn",
														duration: 	400
													},
										closeText: 	"",
										classes: 	"wppa-dialog",
									};

									wppaPrepareModal(mocc);
									jQuery( '#wppa-modal-container-'+mocc ).html( result );
									jQuery( '#wppa-modal-container-'+mocc ).dialog( opt );
									jQuery( '.ui-dialog' ).css( {
																	boxShadow: 			'0px 0px 5px 5px #aaaaaa',
																	borderRadius: 		wppaBoxRadius+'px',
																	padding: 			'8px',
																	backgroundColor: 	wppaModalBgColor,
																	boxSizing: 			'content-box',
																	zIndex: 			100000,
																	position: 			'fixed',
																	top: 				(wppaWindowHeight()/10)+'px',
																});
									jQuery( '.ui-dialog-titlebar' ).css(
																			{
																				lineHeight: '0px',
																				height: 	'24px',
																			}
																		)
									jQuery( '.ui-button' ).css(
																{
																	backgroundImage: 	wppaModalQuitImg,
																	padding:			0,
																	position: 			'absolute',
																	right: 				'8px',
																	top: 				'8px',
																	width: 				'16px',
																	height: 			'16px',
																});
									jQuery( '.ui-button' ).attr( 'title', 'Close' );
									jQuery(document).trigger('resize');

									jQuery('.ui-dialog').on('scroll wheel', function(event){event.stopPropagation();});
								},
					error: 		function( xhr, status, error ) {

									wppaConsoleLog( 'wppaEditPhoto failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, newurl ) {

								}
				} );
}

// Preview tags in frontend upload dialog
function wppaPrevTags( tagsSel, tagsEdit, tagsAlbum, tagsPrev ) {

	var sel 		= jQuery( '.'+tagsSel );
	var selArr 		= [];
	var editTag		= '';
	var album 		= jQuery( '#'+tagsAlbum ).val();
	var i 			= 0;
	var j 			= 0;
	var tags 		= '';

	// Get the selected tags
	while ( i < sel.length ) {
		if ( sel[i].selected ) {
			selArr[j] = sel[i].value;
			j++;
		}
		i++;
	}

	// Add edit field if not empty
	editTag = jQuery( '#'+tagsEdit ).val();
	if ( editTag != '' ) {
		selArr[j] = editTag;
	}

	// Prelim result
	tags = selArr.join();

	// Sanitize if edit field is not empty or album known and put result in preview field
	if ( editTag != '' || tagsAlbum != '' ) {

		jQuery.ajax( { 	url: 		wppaAjaxUrl,
						data: 		'action=wppa' +
									'&wppa-action=sanitizetags' +
									'&tags=' + tags +
									'&album=' + album,
						async: 		true,
						type: 		'GET',
						timeout: 	60000,
						beforeSend: function( xhr ) {
										jQuery( '#'+tagsPrev ).html( 'Working...' );
									},
						success: 	function( result, status, xhr ) {
										jQuery( '#'+tagsPrev ).html( wppaTrim( result, ',' ) );
									},
						error: 		function( xhr, status, error ) {
										jQuery( '#'+tagsPrev ).html( '<span style="color:red" >' + error + '</span>' );
										wppaConsoleLog( 'wppaPrevTags failed. Error = ' + error + ', status = ' + status );
									},
					} );
	}
}

// Delete album
function wppaAjaxDestroyAlbum( album, nonce ) {

	// Are you sure?
	if ( confirm('Are you sure you want to delete this album?') ) {

		jQuery.ajax( { 	url: 		wppaAjaxUrl,
						data: 		'action=wppa' +
									'&wppa-action=destroyalbum' +
									'&album=' + album +
									'&nonce=' + nonce,
						async: 		true,
						type: 		'GET',
						timeout: 	60000,
						success: 	function( result, status, xhr ) {
										alert( result+'\n'+__( 'Page will be reloaded', 'wp-photo-album-plus' ) );
										document.location.reload( true );
									},
						error: 		function( xhr, status, error ) {
										wppaConsoleLog( 'wppaAjaxDestroyAlbum failed. Error = ' + error + ', status = ' + status );
									},
					} );
	}
	return false;
}

// Bump click counter
function _bumpClickCount( photo ) {

	// Feature enabled?
	if ( ! wppaBumpClickCount ) return;

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=bumpclickcount' +
								'&wppa-photo=' + photo +
								'&wppa-nonce=' + jQuery( '#wppa-nonce' ).val(),
					async: 		false,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( '_bumpClickCount failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

// Bump view counter
function _bumpViewCount( photo ) {

	// Feature enabled?
	if ( ! wppaBumpViewCount ) return;

	// Already bumped?
	if ( wppaPhotoView[photo] ) {
		return;
	}

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=bumpviewcount' +
								'&wppa-photo=' + photo +
								'&wppa-nonce=' + jQuery( '#wppa-nonce' ).val(),
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {
									wppaPhotoView[photo] = true;
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( '_bumpViewCount failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

// Vote a thumbnail
function wppaVoteThumb( mocc, photo ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=rate' +
								'&wppa-rating=1' +
								'&wppa-rating-id=' + photo +
								'&wppa-occur=' + mocc +
								'&wppa-index=0' +
								'&wppa-nonce=' + jQuery( '#wppa-nonce' ).val(),
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {
									jQuery( '#wppa-vote-button-'+mocc+'-'+photo ).val( wppaVotedForMe );
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaVoteThumb failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

// Rate a photo
function _wppaRateIt( mocc, value ) {

	// No value, no vote
	if ( value == 0 ) return;

	// Do not rate a running show
	if ( _wppaSSRuns[mocc] ) return;

	// Init vars
	var photo 		= _wppaId[mocc][_wppaCurIdx[mocc]];
	var oldval  	= _wppaMyr[mocc][_wppaCurIdx[mocc]];
	var waittext  	= _wppaWaitTexts[mocc][_wppaCurIdx[mocc]];

	// If wait text, alert and exit
	if ( waittext.length > 0 ) {
		alert( waittext );
		return;
	}

	// Already rated, and once allowed only?
	if ( oldval != 0 && wppaRatingOnce ) {
//		alert('exit 2');
		return;
	}

	// Disliked aleady?
	if ( oldval < 0 ) return;

	// Set Vote in progress flag
	_wppaVoteInProgress = true;

	// Do the voting
	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=rate' +
								'&wppa-rating=' + value +
								'&wppa-rating-id=' + photo +
								'&wppa-occur=' + mocc +
								'&wppa-index=' + _wppaCurIdx[mocc] +
								'&wppa-nonce=' + jQuery( '#wppa-nonce' ).val(),
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					beforeSend: function( xhr ) {

									// Set icon
									jQuery( '#wppa-rate-'+mocc+'-'+value ).attr( 'src', wppaImageDirectory+'tick.png' );

									// Fade in fully
									jQuery( '#wppa-rate-'+mocc+'-'+value ).stop().fadeTo( 100, 1.0 );

									// Likes
									jQuery( '#wppa-like-'+mocc ).attr( 'src', wppaImageDirectory+'spinner.gif' );
								},
					success: 	function( result, status, xhr ) {

									var ArrValues = result.split( "||" );

									// Error from rating algorithm?
									if ( ArrValues[0] == 0 ) {
										if ( ArrValues[1] == 900 ) {		// Recoverable error
											alert( ArrValues[2] );
											_wppaSetRatingDisplay( mocc );	// Restore display
										}
										else {
											alert( __( 'Error Code', 'wp-photo-album-plus' ) + ' = '+ArrValues[1]+'\n\n'+ArrValues[2] );
										}
									}

									// No rating error
									else {

										// Is it likes sytem?
										if ( ArrValues[7] && ArrValues[7] == 'likes' ) {
											var likeText = ArrValues[4].split( "|" );

											// Slide
											jQuery( '#wppa-like-'+mocc ).attr( 'title', likeText[0] );
											jQuery( '#wppa-liketext-'+mocc ).html( likeText[1] );
											if ( ArrValues[3] == '1' ) {
												jQuery( '#wppa-like-'+mocc ).attr( 'src', wppaImageDirectory+'thumbdown.png' );
											}
											else { // == '0'
												jQuery( '#wppa-like-'+mocc ).attr( 'src', wppaImageDirectory+'thumbup.png' );
											}
											_wppaMyr[ArrValues[0]][ArrValues[2]] = ArrValues[3];
											_wppaAvg[ArrValues[0]][ArrValues[2]] = ArrValues[4];
										}

										// Not likes system
										else {
											// Store new values
											_wppaMyr[ArrValues[0]][ArrValues[2]] = ArrValues[3];
											_wppaAvg[ArrValues[0]][ArrValues[2]] = ArrValues[4];
											_wppaDisc[ArrValues[0]][ArrValues[2]] = ArrValues[5];

											// Update display
											_wppaSetRatingDisplay( mocc );

											// If commenting required and not done so far...
											if ( wppaCommentRequiredAfterVote ) {
												if ( ArrValues[6] == 0 ) {
													alert( ArrValues[7] );
												}
											}
										}

										// Shift to next slide?
										if ( wppaNextOnCallback ) _wppaNextOnCallback( mocc );
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( '_wppaRateIt failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

// Rate from lightbox
function _wppaOvlRateIt( id, value, mocc, reloadAfter ) {

	// No value, no vote
	if ( value == 0 ) return;

	// Do the voting
	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=rate' +
								'&wppa-rating=' + value +
								'&wppa-rating-id=' + id +
								'&wppa-occur=1' + // Must be <> 0 to indicate no error
								'&wppa-nonce=' + jQuery( '#wppa-nonce' ).val(),
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					beforeSend: function( xhr ) {

									// Set icon
									jQuery( '.wppa-rate-'+mocc+'-'+value ).attr( 'src', wppaImageDirectory+'tick.png' );

									// Fade in fully
									jQuery( '.wppa-rate-'+mocc+'-'+value ).stop().fadeTo( 100, 1.0 );

									// Likes
									jQuery( '#wppa-like-'+id+'-'+mocc ).attr( 'src', wppaImageDirectory+'spinner.gif' );
									jQuery( '#wppa-like-0' ).attr( 'src', wppaImageDirectory+'spinner.gif' );
								},
					success: 	function( result, status, xhr ) {

									var ArrValues = result.split( "||" );

									// Error from rating algorithm?
									if ( ArrValues[0] == 0 ) {
										if ( ArrValues[1] == 900 ) {		// Recoverable error
											alert( ArrValues[2] );
										}
										else {
											alert( __( 'Error Code', 'wp-photo-album-plus' )+' = '+ArrValues[1]+'\n\n'+ArrValues[2] );
										}

										// Set icon
										jQuery( '.wppa-rate-'+mocc+'-'+value ).attr( 'src', wppaImageDirectory+'cross.png' );
									}

									// No rating error
									else {

										// Is it likes sytem?
										if ( ArrValues[7] && ArrValues[7] == 'likes' ) {
											var likeText = ArrValues[4].split( "|" );

											// Lightbox
											jQuery( '#wppa-like-0' ).attr( 'title', likeText[0] );
											jQuery( '#wppa-liketext-0' ).html( likeText[1] );
											if ( ArrValues[3] == '1' ) {
												jQuery( '#wppa-like-0' ).attr( 'src', wppaImageDirectory+'thumbdown.png' );
											}
											else { // == '0'
												jQuery( '#wppa-like-0' ).attr( 'src', wppaImageDirectory+'thumbup.png' );
											}

											// Thumbnail
											jQuery( '#wppa-like-'+id+'-'+mocc ).attr( 'title', likeText[0] );
											jQuery( '#wppa-liketext-'+id+'-'+mocc ).html( likeText[1] );
											if ( ArrValues[3] == '1' ) {
												jQuery( '#wppa-like-'+id+'-'+mocc ).attr( 'src', wppaImageDirectory+'thumbdown.png' );
											}
											else { // == '0'
												jQuery( '#wppa-like-'+id+'-'+mocc ).attr( 'src', wppaImageDirectory+'thumbup.png' );
											}

											return;
										}

// result = $occur.'||'.$photo.'||'.$index.'||'.$myavgrat.'||'.$allavgratcombi.'||'.$distext.'||'.$hascommented.'||'.$message;
// ArrValues[3] = my avg rating
// ArrValues[4] = all avg rating
//
// All avg stars have class 	.wppa-avg-'+mocc+'-'+value
// My stars have class 			.wppa-rate-'+mocc+'-'+value
										_wppaSetRd( mocc, ArrValues[4], '.wppa-avg-' );
										_wppaSetRd( mocc, ArrValues[3], '.wppa-rate-' );

										// Reload?
										if ( reloadAfter ) {
			//								document.location.reload(true);
											return;
										}

										// Shift to next slide?
										if ( wppaNextOnCallback ) wppaOvlShowNext();
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( '_wppaOvlRateIt failed. Error = ' + error + ', status = ' + status );
								},
				} );

}

// Download a photo having its original name as filename
function wppaAjaxMakeOrigName( mocc, photo ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=makeorigname' +
								'&photo-id=' + photo +
								'&from=fsname',
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					beforeSend: function( xhr ) {

								},
					success: 	function( result, status, xhr ) {

									var ArrValues = result.split( "||" );
									if ( ArrValues[1] == '0' ) {	// Ok, no error

										// Publish result
										if ( wppaIsSafari ) {
											if ( wppaArtMonkyLink == 'file' ) wppaWindowReference.location = ArrValues[2];
											if ( wppaArtMonkyLink == 'zip' ) document.location = ArrValues[2];
										}
										else {
											if ( wppaArtMonkyLink == 'file' ) window.open( ArrValues[2] );
											if ( wppaArtMonkyLink == 'zip' ) document.location = ArrValues[2];
										}

									}
									else {

										// Close pre-opened window
										if ( wppaIsSafari && wppaArtMonkyLink == 'file' ) wppaWindowReference.close();

										// Show error
										alert( __( 'Error', 'wp-photo-album-plus' )+' = '+ArrValues[1]+'\n\n'+ArrValues[2] );
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxMakeOrigName failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, newurl ) {

								}
				} );
}

// Download an album
function wppaAjaxDownloadAlbum( mocc, id ) {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=downloadalbum' +
								'&album-id=' + id,
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					beforeSend: function( xhr ) {

									// Show spinner
									jQuery( '#dwnspin-'+mocc+'-'+id ).css( 'display', '' );
								},
					success: 	function( result, status, xhr ) {

									// Analyze the result
									var ArrValues = result.split( "||" );
									var url 	= ArrValues[0];
									var erok 	= ArrValues[1];
									var text 	= ArrValues[2];

									if ( ArrValues.length == 3 && text != '' ) {
										alert( __( 'Attention', 'wp-photo-album-plus' )+':\n\n'+text );
									}

									if ( erok == 'OK' ) {
										document.location = url;
									}

									else {	// See if a ( partial ) zipfile has been created
										alert( __( 'The server could not complete the request. Please try again.', 'wp-photo-album-plus' ) );
									}
								},
					error: 		function( xhr, status, error ) {
									alert( 'An error occurred:\n'+error+'\nPlease try again' );
								},
					complete: 	function( xhr, status, newurl ) {

									// Hide spinner
									jQuery( '#dwnspin-'+mocc+'-'+id ).css( 'display', 'none' );
								}
				} );
}

// Enter a comment to a photo
function wppaAjaxComment( mocc, id ) {

	// Validate comment else return
	if ( ! _wppaValidateComment( mocc, id ) ) return;

	// Make the Ajax send data
	var theComment = jQuery( "#wppa-comment-"+mocc ).val();
	theComment = theComment.replace(/&/g,'%26');
	var data = { photoid: id,
				 comname: jQuery( "#wppa-comname-"+mocc ).val(),
				 comment: theComment,
				 captcha: jQuery( "#wppa-captcha-"+mocc ).val(),
				 nonce: jQuery( "#wppa-nonce" ).val(),
				 occur: mocc,
				 comemail: jQuery( "#wppa-comemail-"+mocc ).val(),
				 comid: jQuery( "#wppa-comment-edit-"+mocc ).val(),
				 returnurl: encodeURIComponent(jQuery( "#wppa-returnurl-"+mocc ).val()),
				 dbagree: jQuery( "#db-agree-" + mocc ).prop( 'checked' ) ? 'yes' : 'no'
	};
	var theData = JSON.stringify( data );
//	console.log(theData);

	// Do the ajax commit
	jQuery.ajax( { 	url: 		wppaAjaxUrl + '?action=wppa&wppa-action=do-comment',
					data: 		'data='+theData,
					async: 		true,
					type: 		'POST',
					timeout: 	60000,
					beforeSend: function( xhr ) {

									// Show spinner
									jQuery( "#wppa-comment-spin-"+mocc ).css( 'display', 'inline' );
								},
					success: 	function( result, status, xhr ) {

									// sanitize
									result = result.replace( /\\/g, '' );

									// Show result
									jQuery( "#wppa-comments-"+mocc ).html( result );

									// if from slideshow, update memory data array
									if ( _wppaCurIdx[mocc] ) {
										_wppaCommentHtml[mocc][_wppaCurIdx[mocc]] = result;
									}

									// Make sure comments are visible
									wppaOpenComments( mocc );
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxComment failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, newurl ) {

									// Hide spinner
									jQuery( "#wppa-comment-spin-"+mocc ).css( 'display', 'none' );
								}
				} );
}

// New style front-end edit photo
function wppaUpdatePhotoNew(id,mocc) {

	var myItems = [ 'upn-name',
					'upn-description',
					'upn-tags',
					'custom_0',
					'custom_1',
					'custom_2',
					'custom_3',
					'custom_4',
					'custom_5',
					'custom_6',
					'custom_7',
					'custom_8',
					'custom_9',
					];

	var myData = 	'action=wppa' +
					'&wppa-action=update-photo-new' +
					'&photo-id=' + id +
					'&wppa-nonce=' + jQuery('#wppa-nonce-'+id).val();

	var i = 0;
	var tMceContent = '';
	while ( i < myItems.length ) {
		if ( typeof(jQuery('#'+myItems[i] ).val() ) != 'undefined' ) {
			myData += '&' + myItems[i] + '=' + jQuery('#'+myItems[i]).val();
		}

		tMceContent = wppaGetTinyMceContent(myItems[i]);
		if ( typeof(tMceContent) != 'undefined' ) {
			myData += '&' + myItems[i] + '=' + tMceContent;
		}

		i++;
	}
	if ( jQuery('#upn-reload').prop('checked') ) {
		myData += '&upn-reload=on';
	}

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		myData,
					async: 		false,
					type: 		'POST',
					timeout: 	10000,
					beforeSend: function( xhr ) {

								},
					success: 	function( result, status, xhr ) {
									var ArrValues = result.split('||');
									try {
										var updates = JSON.parse( ArrValues[2] );
										jQuery('.sdd-'+mocc).html(updates['desc']);
										jQuery('.sdn-'+mocc).html(updates['name']);
										jQuery('.sdf-'+mocc).html(updates['fullname']);

										if ( _wppaNames[mocc] ) { // For slideshow only
											_wppaNames[mocc][_wppaCurIdx[mocc]] 	= wppaFixHtml(updates['name']);
											_wppaFullNames[mocc][_wppaCurIdx[mocc]] = wppaFixHtml(updates['fullname']);
											_wppaDsc[mocc][_wppaCurIdx[mocc]] 		= wppaFixHtml(updates['desc']);

											jQuery( "#imagetitle-"+mocc ).html( wppaMakeNameHtml( mocc ) );
										}
									}
									catch(e) {
										wppaConsoleLog('Failed to report updates', 'force' );
									}
								},
					error: 		function( xhr, status, error ) {

									wppaConsoleLog( 'wppaUpdatePhotoNew failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, newurl ) {

									if ( jQuery('#upn-reload').prop('checked') ) {
										document.location.reload(true);
									}
									else {
										jQuery('#wppa-modal-container-'+mocc).dialog('close');
										jQuery('#wppa-modal-container-'+mocc).html('');
									}
								}
				} );
}

function wppaFixHtml( txt ) {

	txt = txt.replace(/\[/g, '<'); // [ to <
	txt = txt.replace(/\]/g, '>'); // ] to >
	txt = txt.replace(/&quot;/g, '"'); //
	txt = txt.replace(/\\n/g, ' '); // \n to space

	return txt;
}

var wppaLastQrcodeUrl = '';
// Get qrcode and put it as src in elm
function wppaAjaxSetQrCodeSrc( url, elm ) {

	// Does target element exist?
	if ( jQuery(elm).length == 0 ) {
		return;
	}

	// Been here before with this url?
	if ( wppaLastQrcodeUrl == url ) {
		return;
	}

	// Remember this
	wppaLastQrcodeUrl = url;

	var myData = 	'action=wppa' +
					'&wppa-action=getqrcode' +
					'&wppa-qr-nonce=' + jQuery( '#wppa-qr-nonce' ).val() +
					'&url=' + encodeURIComponent( url );

	jQuery.ajax( {	url: 		wppaAjaxUrl,
					data: 		myData,
					async: 		true,
					type: 		'POST',
					timeout: 	10000,
					success: 	function( result, status, xhr ) {
									var temp = result.split('|');
									jQuery( elm ).attr( 'src', temp[0] ); //document.getElementById( elm ).src = temp[0];
									jQuery( elm ).attr( 'title', temp[1] );
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxSetQrCodeSrc failed. Error = ' + error + ', status = ' + status );
								}
				} );
}

// Add/remove a user to a mailinglist
function wppaAjaxNotify( elm, list, user ) {

	var onoff  = 	jQuery( elm ).prop( 'checked' ) ? 'on' : 'off';
	var myData = 	'action=wppa' +
					'&wppa-action=mailinglist' +
					'&wppa-ntfy-nonce=' + jQuery( '#wppa-ntfy-nonce' ).val() +
					'&list=' + list +
					'&onoff=' + onoff;
					if ( user ) {
						myData += '&wppa-user='+user;
					}

	jQuery.ajax( {	url: 		wppaAjaxUrl,
					data: 		myData,
					async: 		true,
					type: 		'POST',
					timeout: 	10000,
					beforeSend: function() {
									if ( user ) {
										jQuery("#img_"+list+"-"+user).attr('src',wppaImageDirectory+'spinner.gif');
									}
								},
					success: 	function( result, status, xhr ) {
									if ( user ) {
										wppaConsoleLog( result, 'force' );
										jQuery("#img_"+list+"-"+user).attr('src',wppaImageDirectory+'tick.png');
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxNotify failed. Error = ' + error + ', status = ' + status );
									jQuery("#img_"+list+"-"+user).attr('src',wppaImageDirectory+'cross.png');
								}
				} );
}

// Supersearch function get iptc list
function wppaAjaxGetSsIptcList( mocc, s3, selid, retry ) {

	wppaAjaxGetSsIptcExifList( mocc, s3, selid, retry, 'iptc' );
}

// Supersearch function get exif list
function wppaAjaxGetSsExifList( mocc, s3, selid, retry ) {

	wppaAjaxGetSsIptcExifList( mocc, s3, selid, retry, 'exif' );
}

// Common function that does the job
function wppaAjaxGetSsIptcExifList( mocc, s3, selid, retry, tagtype ) {

	ajaxurl = wppaAjaxUrl;

	ajaxurl += '?action=wppa&wppa-action=getss' + tagtype + 'list&' + tagtype + 'tag=' + s3 + '&occur=' + mocc;
	jQuery.ajax( { 	url: 		ajaxurl,
					async: 		true,
					type: 		'GET',
					timeout: 	10000,
					beforeSend: function( xhr ) {
									jQuery( '#wppa-ss-spinner-'+mocc ).css('display', '');
								},
					success: 	function( result, status, xhr ) {
									jQuery( '#'+selid ).html( result );
									jQuery( '#wppa-ss-'+tagtype+'opts-'+mocc ).css('display', '');
									wppaSuperSearchSelect( mocc );
									setTimeout('wppaSetIptcExifSize( ".wppa-'+tagtype+'list-'+mocc+'", "#'+selid+'" )', 10 );
									if (retry) {
										wppaConsoleLog('wppaAjaxGetSs'+tagtype+'List success after retry.');
									}
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog('wppaAjaxGetSs'+tagtype+'List failed. Error = ' + error + ', status = ' + status );
									if (!retry) wppaAjaxGetSsIptcExifList( mocc, s3, selid, true, tagtype );
								},
					complete: 	function( xhr, status, newurl ) {
									jQuery( '#wppa-ss-spinner-'+mocc ).css('display', 'none');
								}
				} );
}

// If a dialog is already open, close it first
function wppaPrepareModal(mocc) {

	try {
		jQuery('#wppa-modal-container-'+mocc).dialog('destroy');
		jQuery('#wppa-modal-container-'+mocc).html('');
	}
	catch(e) {
		jQuery('#wppa-modal-container-'+mocc).html('');
	}
}