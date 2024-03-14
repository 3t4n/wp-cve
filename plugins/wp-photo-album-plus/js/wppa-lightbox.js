// wppa-lightbox.js
//
// Conatins lightbox modules
// Dependancies: wppa.js and default wp $ library
//
//
var wppaJsLightboxVersion = '8.5.03.002';
var wppaOvlActivePanorama = 0;

// Initial initialization
jQuery(document).ready( function( e ) {
	wppaInitOverlay();
});

// Window resize handler
jQuery( window ).on('resize',function() {
	wppaOvlResize();
});

// Keyboard handler
function wppaOvlKeyboardHandler( e ) {

	var keycode;
	var escapeKey;

	if ( e == null ) { // ie
		keycode = event.keyCode;
		escapeKey = 27;
	} else { // mozilla
		keycode = e.keyCode;
		escapeKey = 27; //e.DOM_VK_ESCAPE;
	}

	var key = String.fromCharCode( keycode ).toLowerCase();

	switch ( keycode ) {
		case escapeKey:
			wppaStopVideo( 0 );
			wppaOvlHide();
			break;
		case 37:
			wppaOvlShowPrev();
			break;
		case 39:
			wppaOvlShowNext();
			break;
	}

	switch ( key ) {
		case 'p':
			wppaOvlShowPrev();
			break;
		case 'n':
			wppaOvlShowNext();
			break;
		case 's':
			wppaOvlStartStop();
			break;
		case 'f':
			wppaFsOn();
			break;
		case 'q':
		case 'x':
			wppaStopVideo( 0 );
			wppaOvlHide();
			break;
	}

	return false;
}

// Prepare the display of the lightbox overlay.
// arg is either numeric ( index to current lightbox set ) or
// 'this' for a single image or for the first of a set
function wppaOvlShow( arg ) {

	var panData;
	var dotPos;


	// Panorama requires image container top=0 left=0
	// Non panorama: 50%
	if ( wppaOvlActivePanorama > 0 ) {
//		wppaSavedContainerWidth = 0; // reset
//		wppaSavedContainerHeight = 0;
	}

	// Make sure background is present
	jQuery( '#wppa-overlay-bg' ).css({display:'inline'}); // stop().fadeTo( 3, wppaOvlOpacity );

	// Do the setup right after the invocation of the lightbox
	if ( wppaOvlFirst ) {

		// Prevent Weaver ii from hiding us
		jQuery( '#weaver-final' ).removeClass( 'wvr-hide-bang' );

		// Install keyboard handler
		if ( ! wppaKbHandlerInstalled ) {
			jQuery(document).on( 'keydown', wppaOvlKeyboardHandler );
			wppaKbHandlerInstalled = true;
		}

		// Trigger lightbox open event
		jQuery(window).trigger( 'wppalightboxstart' );

	}

	// If fs policy == global and big browse, hide global fs buttons
//	if ( wppaFsPolicy == 'global' && wppaOvlBigBrowse ) {
//		setTimeout( function() {
//			jQuery('#wppa-fulls-btn-1').hide();
//			jQuery('#wppa-exit-fulls-btn-1').hide();
//		}, 500 );
//	}

	// If arg = 'this', setup the array of data
	if ( typeof( arg ) == 'object' ) {

		// Init the set
		wppaOvlIds 					= [];
		wppaOvlUrls 				= [];
		wppaOvlTitles 				= [];
		wppaOvlAlts 				= [];
		wppaOvlTypes 				= [];
		wppaOvlVideoHtmls 			= [];
		wppaOvlAudioHtmls 			= [];
		wppaOvlPdfHtmls 			= [];
		wppaOvlVideoNaturalWidths 	= [];
		wppaOvlVideoNaturalHeights 	= [];
		wppaOvlImgs					= [];
		wppaOvlIdx 					= 0;
		wppaOvlPanoramaHtml 		= [];
		wppaOvlPanoramaIds 			= [];
		wppaOvlHasPanoramas 		= false;

		// Do we use rel or data-rel?
		var rel;
		if ( arg.rel ) {
			rel = arg.rel;
		}
		else if ( jQuery( arg ).attr( 'data-rel' ) ) {
			rel = jQuery( arg ).attr( 'data-rel' );
		}
		else {
			rel = false;
		}

		// Are we in a set?
		var temp = rel.split( '[' );

		// We are in a set if temp[1] is defined
		if ( temp[1] ) {
			var setname = temp[1];
			var anchors = jQuery( 'a' );
			var anchor;
			var i, j = 0;

			// Save the set
			for ( i = 0; i < anchors.length; i++ ) {
				anchor = anchors[i];
				if ( jQuery( anchor ).attr( 'data-rel' ) ) {
					temp = jQuery( anchor ).attr( 'data-rel' ).split( "[" );
				}
				else {
					temp = false;
				}

				if ( temp.length > 1 ) {
					if ( temp[0] == 'wppa' && temp[1] == setname ) {	// Same set
						wppaOvlUrls[j] = anchor.href;
						if ( jQuery( anchor ).attr( 'data-lbtitle' ) ) {
							wppaOvlTitles[j] = wppaRepairScriptTags( jQuery( anchor ).attr( 'data-lbtitle' ) );
						}
						else {
							wppaOvlTitles[j] = wppaRepairScriptTags( anchor.title );
						}
						wppaOvlIds[j] 					= jQuery( anchor ).attr( 'data-id' ) ? jQuery( anchor ).attr( 'data-id' ) : '0';
						wppaOvlAlts[j] 					= jQuery( anchor ).attr( 'data-alt' ) ? jQuery( anchor ).attr( 'data-alt' ) : '';
						wppaOvlVideoHtmls[j] 			= jQuery( anchor ).attr( 'data-videohtml' ) ? decodeURI( jQuery( anchor ).attr( 'data-videohtml' ) ) : '';
						wppaOvlPdfHtmls[j] 				= jQuery( anchor ).attr( 'data-pdfhtml' ) ? decodeURI( jQuery( anchor ).attr( 'data-pdfhtml' ) ) : '';
						wppaOvlAudioHtmls[j] 			= jQuery( anchor ).attr( 'data-audiohtml' ) ? decodeURI( jQuery( anchor ).attr( 'data-audiohtml' ) ) : '';
						wppaOvlVideoNaturalWidths[j] 	= jQuery( anchor ).attr( 'data-videonatwidth' ) ? jQuery( anchor ).attr( 'data-videonatwidth' ) : '';
						wppaOvlVideoNaturalHeights[j] 	= jQuery( anchor ).attr( 'data-videonatheight' ) ? jQuery( anchor ).attr( 'data-videonatheight' ) : '';
						panData 						= jQuery( anchor ).attr( 'data-panorama' ) ? jQuery( anchor ).attr( 'data-panorama' ) : '';
						panType 						= jQuery( anchor ).attr( 'data-pantype' ) ? jQuery( anchor ).attr( 'data-pantype' ) : '';

						if ( panData.length > 0 ) {
							wppaOvlHasPanoramas = true;
							dotPos = panData.indexOf( '.' );
							wppaOvlPanoramaHtml[j] 		= panData.substr(dotPos+1);
							wppaOvlPanoramaIds[j] 		= panData.substr(0,dotPos);
							wppaOvlTypes[j] 			= panType;
						}
						else {
							wppaOvlPanoramaHtml[j] 		= '';
							wppaOvlPanoramaIds[j] 		= 0;
							wppaOvlTypes[j] 			= '';
						}
						if ( wppaOvlPdfHtmls[j].length > 0 ) {
							wppaOvlTypes[j] 			= 'document';
						}

						// Is this the one we asked for?
						// If its not a stub file, one can look at the arg.href (old method).
						// So, now we look at data-pdfhtml, data-videohtml, data-audiohtml as well as arg.href
						if ( decodeURI( jQuery( anchor ).attr( 'data-pdfhtml' ) ) == decodeURI( jQuery( arg ).attr( 'data-pdfhtml' ) ) &&
							 decodeURI( jQuery( anchor ).attr( 'data-videohtml' ) ) == decodeURI( jQuery( arg ).attr( 'data-videohtml' ) ) &&
							 decodeURI( jQuery( anchor ).attr( 'data-audiohtml' ) ) == decodeURI( jQuery( arg ).attr( 'data-audiohtml' ) ) &&
							 anchor.href == arg.href ) {
							wppaOvlIdx = j;									// Current index
						}
						j++;
					}
				}
			}
		}

		// Single image, treat as set with one element
		else {
			wppaOvlUrls[0] = arg.href;
			if ( jQuery( arg ).attr( 'data-lbtitle' ) ) {
				wppaOvlTitles[0] = wppaRepairScriptTags( jQuery( arg ).attr( 'data-lbtitle' ) );
			}
			else {
				wppaOvlTitles[0] = wppaRepairScriptTags( arg.title );
			}
			wppaOvlIds[0] 					= jQuery( arg ).attr( 'data-id' ) ? jQuery( arg ).attr( 'data-id' ) : '0';
			wppaOvlAlts[0] 					= jQuery( arg ).attr( 'data-alt' ) ? jQuery( arg ).attr( 'data-alt' ) : '';
			wppaOvlVideoHtmls[0] 			= jQuery( arg ).attr( 'data-videohtml' ) ? decodeURI( jQuery( arg ).attr( 'data-videohtml' ) ) : '';
			wppaOvlAudioHtmls[0] 			= jQuery( arg ).attr( 'data-audiohtml' ) ? decodeURI( jQuery( arg ).attr( 'data-audiohtml' ) ) : '';
			wppaOvlPdfHtmls[0] 				= jQuery( arg ).attr( 'data-pdfhtml' ) ? decodeURI( jQuery( arg ).attr( 'data-pdfhtml' ) ) : '';
			wppaOvlVideoNaturalWidths[0] 	= jQuery( arg ).attr( 'data-videonatwidth' ) ? jQuery( arg ).attr( 'data-videonatwidth' ) : '';
			wppaOvlVideoNaturalHeights[0] 	= jQuery( arg ).attr( 'data-videonatheight' ) ? jQuery( arg ).attr( 'data-videonatheight' ) : '';
			panData 						= jQuery( arg ).attr( 'data-panorama' ) ? jQuery( arg ).attr( 'data-panorama' ) : '';
			panType 						= jQuery( arg ).attr( 'data-pantype' ) ? jQuery( arg ).attr( 'data-pantype' ) : '';

			if ( panData.length > 0 ) {
				wppaOvlHasPanoramas = true;
				dotPos = panData.indexOf( '.' );
				wppaOvlPanoramaHtml[0] 		= panData.substr(dotPos+1);
				wppaOvlPanoramaIds[0] 		= panData.substr(0,dotPos);
				wppaOvlTypes[0] 			= panType;
			}
			else {
				wppaOvlPanoramaHtml[0] 		= '';
				wppaOvlPanoramaIds[0] 		= 0;
				wppaOvlTypes[0] 			= '';
			}
			if ( wppaOvlPdfHtmls[0].length > 0 ) {
				wppaOvlTypes[0] 			= 'document';
			}
			wppaOvlIdx = 0;
		}
	}

	// Arg is numeric
	else {
		wppaOvlIdx = arg;
	}

	wppaOvlOpen = true;

	// Make sure a possible previous panorama dies
	jQuery('body').trigger('quitimage');

	// Now start the actual function
	setTimeout( function(){ _wppaOvlShow( wppaOvlIdx )}, 100 );

}

// Show the lightbox overlay.
// idx is the numeric index to current lightbox set
function _wppaOvlShow( idx ) {



	// Clear image containers
	jQuery( '#wppa-overlay-ic' ).html( '' ).hide();
	jQuery( '#wppa-overlay-pc' ).html( '' ).hide();
	jQuery( '#wppa-overlay-fpc' ).html( '' ).hide();
	jQuery( '#wppa-overlay-zpc' ).html( '' ).hide();

	// Globalize index
	wppaOvlCurIdx = idx;

	// Show spinner
	jQuery( "#wppa-ovl-spin" ).show();

	// Find handy switches
	wppaIsVideo 	= wppaOvlVideoHtmls[idx] != '';
	wppaHasAudio 	= wppaOvlAudioHtmls[idx] != '';
	wppaOvlIsPdf 	= wppaOvlPdfHtmls[idx] != '';

	// Preload current image
	// Not an empty url, and do not wait infinitely for a possibly non-existend posterimage
	if ( false && wppaOvlUrls[idx].length > 0 && ! wppaIsVideo ) {
		if ( ! wppaOvlImgs[idx] ) {
			wppaOvlImgs[idx] 			= new Image();
		}
		wppaOvlImgs[idx].src 		= wppaOvlUrls[idx];	// Preload

		if ( ! wppaIsIe && ! wppaOvlImgs[idx].complete && wppaOvlOpen && ! wppaOvlIsPdf ) {
			setTimeout( function(){_wppaOvlShow(idx)}, 500 );
			return;
		}
	}

	// Calc prev and next indexes
	var next = ( wppaOvlIdx == ( wppaOvlUrls.length - 1 ) ? 0 : wppaOvlIdx + 1 );
	var prev = ( wppaOvlIdx == 0 ? ( wppaOvlUrls.length - 1 ) : wppaOvlIdx - 1 );

	// Preload next, only if not video and not panorama
	if ( wppaOvlTypes[next] == '' && wppaOvlVideoHtmls[next] == '' && wppaOvlOpen ) {
		document.getElementById( 'wppa-pre-next' ).src = wppaOvlUrls[next]; // Preload
	}

	// Preload previous, only if not video
	if ( wppaOvlTypes[prev] == '' && wppaOvlVideoHtmls[prev] == '' && wppaOvlOpen ) {
		document.getElementById( 'wppa-pre-prev' ).src = wppaOvlUrls[prev]; // Preload
	}

	// Find photo id and bump its viewcount
	_bumpViewCount( wppaOvlIds[idx] );

	// A single image?
	wppaOvlIsSingle = ( wppaOvlUrls.length == 1  );

	// Panorama requires image container top=0 left=0
	// Non panorama: 50%
	wppaOvlActivePanorama = wppaOvlPanoramaIds[idx];
	if ( wppaOvlActivePanorama ) {
	}

	// Fullsize?
	if ( wppaIsFs() || wppaOvlActivePanorama ) {
		var html;

		// Fullsize panorama?
		if ( wppaOvlActivePanorama ) {
			html = wppaOvlPanoramaHtml[idx];
		}

		// Fullsize Video
		else if ( wppaIsVideo ) {

			html =
			'<div id="wppa-ovl-full-bg" style="position:fixed; width:'+screen.width+'px; height:'+screen.height+'px; left:0px; top:0px; text-align:center;" >'+
				'<video id="wppa-overlay-img" controls preload="metadata"' +
					( wppaOvlVideoStart ? ' autoplay' : '' ) +
					' ontouchstart="wppaTouchStart( event, \'wppa-overlay-img\', -1 );"' +
					' ontouchend="wppaTouchEnd( event );"' +
					' ontouchmove="wppaTouchMove( event );"' +
					' ontouchcancel="wppaTouchCancel( event );"' +
					' onclick="wppaOvlImgClick( event );"' +
					' onpause="wppaOvlVideoPlaying = false;"' +
					' onplay="wppaOvlVideoPlaying = true;"' +
					' style="border:none; width:'+screen.width+'px; box-shadow:none; position:absolute;"' +
					' alt="'+wppaOvlAlts[idx]+'"' +
					' >'+
						wppaOvlVideoHtmls[idx]+
				'</video>'+
			'</div>';
		}

		// Fullsize pdf
		else if ( wppaOvlIsPdf ) {

			html =
			'<div id="wppa-ovl-full-bg" style="position:fixed; width:'+screen.width+'px; height:'+screen.height+'px; left:0px; top:0px; text-align:center;" >'+
				'<iframe'+
					' id="wppa-overlay-img"' +
					' ' + wppaOvlPdfHtmls[idx] +
					' ontouchstart="wppaTouchStart( event, \'wppa-overlay-img\', -1 );"' +
					' ontouchend="wppaTouchEnd( event );"' +
					' ontouchmove="wppaTouchMove( event );"' +
					' ontouchcancel="wppaTouchCancel( event );"' +
					' onclick="wppaOvlImgClick( event );"' +
					' style="border:none; width:'+screen.width+'px; box-shadow:none; position:absolute;"' +
					' alt="'+wppaOvlAlts[idx]+'"' +
					' >'+
				'</iframe>'+
			'</div>';
		}

		// Fullsize Photo
		else {
			html =
			'<div id="wppa-ovl-full-bg" style="position:fixed; width:'+screen.width+'px; height:'+screen.height+'px; left:0px; top:0px; text-align:center;" >'+
				'<img id="wppa-overlay-img"'+
					' ontouchstart="wppaTouchStart( event, \'wppa-overlay-img\', -1 );"'+
					' ontouchend="wppaTouchEnd( event );"'+
					' ontouchmove="wppaTouchMove( event );"'+
					' ontouchcancel="wppaTouchCancel( event );"'+
					' onclick="wppaOvlImgClick( event );"' +
					' src="'+wppaOvlUrls[idx]+'"'+
					' style="border:none; width:'+screen.width+'px; visibility:hidden; box-shadow:none; position:absolute;"'+
					' alt="'+wppaOvlAlts[idx]+'"'+
				' />';

				if ( wppaHasAudio ) {
					html +=
					'<audio' +
						' id="wppa-overlay-audio"' +
						' class="wppa-overlay-audio"' +
						' data-from="wppa"' +
						' preload="metadata"' +
						( ( wppaOvlAudioStart ) ? ' autoplay' : '' ) +
						' onpause="wppaOvlAudioPlaying = false;"' +
						' onplay="wppaOvlAudioPlaying = true;"' +
						' style="' +
							'width:100%;' +
							'position:absolute;' +
							'left:0px;' +
							'bottom:0px;' +
							'padding:0;' +
							'"' +
						' controls' +
						' >' +
						wppaOvlAudioHtmls[idx] +
					'</audio>';
				}

			html +=
			'</div>';
		}

		// Replacing the html stops a running video,
		// so we only replace html on a new id, or a photo without audio
		if ( ( ! wppaIsVideo && ! wppaHasAudio ) || wppaOvlFsPhotoId != wppaPhotoId || wppaPhotoId == 0 ) {
			wppaStopVideo( 0 );
			wppaStopAudio();

			// Fill the right image container
			if ( wppaOvlActivePanorama > 0) {
				switch( wppaOvlTypes[idx] ) {
					case 'spheric':
						jQuery( '#wppa-overlay-pc' ).html( html ).show();
						break;
					case 'flat':
						jQuery( '#wppa-overlay-fpc' ).html( html ).show();
						break;
					case 'zoom':
						jQuery( '#wppa-overlay-zpc' ).html( html ).show();
						break;
				}
			}
			else {
				jQuery( '#wppa-overlay-ic' ).html( html ).show();
			}
		}

		// If panorama and single, hide panorama browse buttons
		if ( wppaOvlPanoramaIds[idx] > 0 && wppaOvlIsSingle ) {
			jQuery( '.wppa-pan-prevnext' ).hide();
		}

		// Disable right mouse button optionally
		wppaProtect();

		wppaOvlIsVideo = wppaIsVideo;
		setTimeout( wppaOvlFormatFull, 10 );
		if ( wppaIsVideo || wppaHasAudio ) {
			setTimeout( function(){wppaOvlFsPhotoId = wppaPhotoId;}, 20 );
		}
		else {
			wppaOvlFsPhotoId = 0;
		}
		wppaOvlFirst = false;

		// Show
		if ( wppaOvlTypes[idx] == '' ) {
			jQuery("#wppa-overlay-ic").show();
		}

		// Update buttons
		wppaFsShow();

		// Optionally disable rightclick
		wppaProtect();

		return false;
	}

	// NOT fullsize
	else {

		// Initialize
		wppaOvlFsPhotoId = 0; // Reset ovl fullscreen photo id
		wppaPhotoId = 0;
		wppaStopVideo( 0 );
		var txtcol = wppaOvlTheme == 'black' ? '#a7a7a7' : '#272727';	// Normal font
		if ( wppaOvlFontColor ) {
			txtcol = wppaOvlFontColor;
		}
		var showNav = wppaOvlUrls.length > 1;

		// Initial sizing of image container ( contains image, borders and subtext )
		if ( wppaOvlActivePanorama == 0 ) {
			jQuery( '#wppa-overlay-ic' ).css( {
											width:wppaSavedContainerWidth,
											marginLeft:wppaSavedMarginLeft,
											marginTop:wppaSavedMarginTop,
										});
		}

		// Make the html
		var html = '';

		// The img sub image container
		html += '<div id="img-sb-img-cont" style="position:relative;line-height:0;" >';

			// Not Fullsize Video
			if ( wppaIsVideo ) {

				html += '<video' +
							' id="wppa-overlay-img"' +
							' onmouseover="jQuery(\'.wppa-ovl-nav-btn\').stop().fadeTo(200,0.8);"' +
							' onmouseout="jQuery(\'.wppa-ovl-nav-btn\').stop().fadeTo(200,0);"' +
							' preload="metadata"' +
							( wppaOvlVideoStart ? ' autoplay' : '' ) +
							' onpause="wppaOvlVideoPlaying = false;"' +
							' onplay="wppaOvlVideoPlaying = true;"' +
							' ontouchstart="wppaTouchStart( event, \'wppa-overlay-img\', -1 );"' +
							' ontouchend="wppaTouchEnd( event );"' +
							' ontouchmove="wppaTouchMove( event );"' +
							' ontouchcancel="wppaTouchCancel( event );" ' +
							' onclick="wppaOvlImgClick( event );"' +
							' controls' +
							' style="' +
								'border-width:' + wppaOvlBorderWidth + 'px ' + wppaOvlBorderWidth + 'px 0;' +
								'border-style:solid;' +
								'border-color:'+wppaOvlTheme+';' +
								'width:' + wppaSavedImageWidth + 'px;' +
								'height:' + wppaSavedImageHeight + 'px;' +
								'box-shadow:none;' +
								'box-sizing:content-box;' +
								'position:relative;' +
								'border-top-left-radius:'+wppaOvlRadius+'px;' +
								'border-top-right-radius:'+wppaOvlRadius+'px;' +
								'margin:0;' +
								'padding:0;' +
							'"' +
							' alt="'+wppaOvlAlts[idx]+'"' +
							' >' +
							wppaOvlVideoHtmls[idx] +
						'</video>';

				wppaOvlIsVideo = true;
			}

			// Not fullsize pdf
			else if ( wppaOvlIsPdf ) {

				html += '<iframe' +
							' ' + wppaOvlPdfHtmls[idx] +
							' id="wppa-overlay-img"' +
							' onmouseover="jQuery(\'.wppa-ovl-nav-btn\').stop().fadeTo(200,0.8);"' +
							' onmouseout="jQuery(\'.wppa-ovl-nav-btn\').stop().fadeTo(200,0);"' +
							' ontouchstart="wppaTouchStart( event, \'wppa-overlay-img\', -1 );"' +
							' ontouchend="wppaTouchEnd( event );"' +
							' ontouchmove="wppaTouchMove( event );"' +
							' ontouchcancel="wppaTouchCancel( event );" ' +
							' onclick="wppaOvlImgClick( event );"' +
							' style="' +
								'border-width:' + wppaOvlBorderWidth + 'px ' + wppaOvlBorderWidth + 'px 0;' +
								'border-style:solid;' +
								'border-color:'+wppaOvlTheme+';' +
			//					'width:' + wppaSavedImageWidth + 'px;' +
			//					'height:' + wppaSavedImageHeight + 'px;' +
								'box-shadow:none;' +
								'box-sizing:content-box;' +
								'position:relative;' +
								'border-top-left-radius:'+wppaOvlRadius+'px;' +
								'border-top-right-radius:'+wppaOvlRadius+'px;' +
								'margin:0;' +
								'padding:0;' +
							'"' +
							' alt="'+wppaOvlAlts[idx]+'"' +
							' >' +
						'</iframe>';
			}

			// Not fullsize photo
			else {
				html += '<img' +
							' id="wppa-overlay-img"'+
							' onmouseover="jQuery(\'.wppa-ovl-nav-btn\').stop().fadeTo(200,0.8);"' +
							' onmouseout="jQuery(\'.wppa-ovl-nav-btn\').stop().fadeTo(200,0);"' +
							' ontouchstart="wppaTouchStart( event, \'wppa-overlay-img\', -1 );"' +
							' ontouchend="wppaTouchEnd( event );"' +
							' ontouchmove="wppaTouchMove( event );"' +
							' ontouchcancel="wppaTouchCancel( event );"' +
							' onclick="wppaOvlImgClick( event );"' +
							' src="'+wppaOvlUrls[idx]+'"' +
							' style="' +
								'border-width:' + wppaOvlBorderWidth + 'px ' + wppaOvlBorderWidth + 'px 0;' +
								'border-style:solid;' +
								'border-color:'+wppaOvlTheme+';' +
								'width:' + wppaSavedImageWidth + 'px;' +
								'height:' + wppaSavedImageHeight + 'px;' +
								'box-shadow:none;' +
								'box-sizing:content-box;' +
								'position:relative;' +
								'border-top-left-radius:'+wppaOvlRadius+'px;' +
								'border-top-right-radius:'+wppaOvlRadius+'px;' +
								'margin:0;' +
								'padding:0;' +
								'"' +
							' alt="'+wppaOvlAlts[idx]+'"' +
						' />';

				// Audio on not fullsize
				if ( wppaHasAudio ) {
					html += '<audio' +
								' id="wppa-overlay-audio"' +
								' class="wppa-overlay-audio"' +
								' data-from="wppa"' +
								' preload="metadata"' +
								' onpause="wppaOvlAudioPlaying = false;"' +
								' onplay="wppaOvlAudioPlaying = true;"' +
								' style="' +
									'width:100%;' +
									'position:absolute;' +
									'box-shadow:none;' +
									'left:0;' +
									'bottom:0;' +
									'padding:0 ' + wppaOvlBorderWidth + 'px;' +
									'margin:0;' +
									'background-color:transparent;' +
									'box-sizing:border-box;' +
									'"' +
								' controls' +
								' >' +
								wppaOvlAudioHtmls[idx] +
							'</audio>';
				}
				wppaOvlIsVideo = false;
			}

		// Close the #img-sb-img-cont
		html += '</div>';

		// The subtext container
		var showCounter = ! wppaOvlIsSingle && wppaOvlShowCounter;
		html +=
		'<div id="wppa-overlay-txt-container"' +
			' style="' +
				'position:relative;' +
				'padding:10px;' +
				'background-color:' + wppaOvlTheme + ';' +
				'color:' + txtcol + ';' +
				'text-align:center;' +
				'font-family:' + wppaOvlFontFamily + ';' +
				'font-size:' + wppaOvlFontSize + 'px;' +
				'font-weight:' + wppaOvlFontWeight + ';' +
				'line-height:' + wppaOvlLineHeight + 'px;' +
				'box-shadow:none;' +
				'border-bottom-left-radius:'+wppaOvlRadius+'px;' +
				'border-bottom-right-radius:'+wppaOvlRadius+'px;' +
				'"' +
			' >' +
			'<div' +
				' id="wppa-overlay-txt"' +
				' style="' +
					'text-align:center;' +
					'min-height:36px;' +
					'width:100%;' +
					( wppaOvlTxtHeight == 'auto' ? 'max-height:200px;' : 'max-height:' + wppaOvlTxtHeight + 'px;' ) +
					'overflow:auto;' +
					'box-shadow:none;' +
					'"' +
			' >';

				// The nav bar
				html += wppaOvlNavBar();

				// The actual text
				html +=
				( showCounter ? ( wppaOvlIdx + 1 ) + '/' + wppaOvlUrls.length + '<br />' : '' ) +
				wppaOvlTitles[idx];

			// Close wppa-overlay-txt
			html +=
			'</div>';

		// Close wppa-overlay-txt-container
		html +=
		'</div>';

		// Insert the html
		if ( wppaOvlActivePanorama == 0 ) {
			jQuery( '#wppa-overlay-ic' ).html( html );
//			jQuery( '#wppa-overlay-pc' ).html( '' );
		}
		else {
			jQuery( '#wppa-overlay-pc' ).html( html );
//			jQuery( '#wppa-overlay-ic' ).html( '' );
		}

		// Show
		if ( wppaOvlTypes[idx] == '' || wppaOvlTypes[idx] == 'document' ) {
			jQuery("#wppa-overlay-ic").show();
		}

		// Restore opacity of fs and exit buttons
		wppaFsShow();

		// Disable right mouse button
		if ( wppaHideRightClick ) {
			jQuery( '#wppa-overlay-img' ).bind( 'contextmenu', function(e) {
				return false;
			});
			jQuery( 'canvas' ).bind( 'contextmenu', function(e) {
				return false;
			});
		}

		// Size if not panorama
		if ( wppaOvlPanoramaIds[idx] == 0 ) {
			wppaOvlResize();
		}

		// Show fs and exit buttons
		wppaFsShow();

		wppaAdjustControlbar();

		// Done!
		return false;
	}
}

// Adjust display sizes
function wppaOvlSize( speed ) {



	// Panoramas do their own formatting
	if ( wppaOvlActivePanorama ) return;

	var img = document.getElementById( 'wppa-overlay-img' );	// Do NOT jquerify this:
	var txt = document.getElementById( 'wppa-overlay-txt' ); 	// $ does not support .naturalHeight etc.

	// Are we still visible?
	if ( ! img || ! txt || jQuery('#wppa-overlay-bg').css('display') == 'none' ) {
		return;
	}

	// Full screen?
	if ( wppaIsFs() ) {
		wppaOvlFormatFull();
		return;
	}

	var iw = wppaWindowWidth();
	var ih = wppaWindowHeight();
	var cw, nw, nh;

	if ( wppaOvlIsVideo ) {
		cw = img.clientWidth;//640;
		nw = wppaOvlVideoNaturalWidths[wppaOvlCurIdx];//640;
		nh = wppaOvlVideoNaturalHeights[wppaOvlCurIdx];//480;
	}
	else if ( wppaOvlIsPdf ) {
		cw = wppaWindowWidth() * 0.9;
		nw = wppaWindowWidth() * 0.9;
		nh = wppaWindowHeight() * 0.9;
	}
	else {
		cw = img.clientWidth;
		nw = img.naturalWidth;
		nh = img.naturalHeight;
	}

	var fakt1;
	var fakt2;
	var fakt;

	// If the width is the limiting factor, adjust the height
	if ( typeof( nw ) == 'undefined' ) {	// ver 4 browser
		nw = img.clientWidth;
		nh = img.clientHeight;
	}
	fakt1 = ( iw - 3 * wppaOvlBorderWidth ) / nw;
	fakt2 = ih / nh;
	if ( fakt1 < fakt2 ) fakt = fakt1;	// very landscape, width is the limit
	else fakt = fakt2;				// Height is the limit
	if ( fakt < 1.0 ) {					// Up or downsize
		nw = parseInt( nw * fakt );
		nh = parseInt( nh * fakt );
	}

	var mh;	// max image height
	var tch = jQuery( '#wppa-overlay-txt' ).height();

	if ( wppaOvlTxtHeight == 'auto' ) {
		if ( tch == 0 ) tch = 20 + 2 * wppaOvlBorderWidth;
		mh = ih - tch - 20 - 2 * wppaOvlBorderWidth;
	}
	else {
		mh = ih - wppaOvlTxtHeight - 20 - 2 * wppaOvlBorderWidth;
	}

	var mw = parseInt( mh * nw / nh );
	var pt = wppaOvlPadTop;
	var lft = parseInt( ( iw-mw )/2 );
	var wid = mw;

	// Image too small?	( never for ver 4 browsers, we do not know the natural dimensions
	if ( nh < mh ) {
		pt = wppaOvlPadTop + ( mh - nh )/2;
		lft = parseInt( ( iw-nw )/2 );
		wid = nw;
	}

	// Save new image width and height
	var done = ( wppaSavedImageWidth - wid < 3 && wid - wppaSavedImageWidth < 3 );

	if ( wid <= 10 ) {
		wid = 240;
		nh = 180;
		nw = 240;
		done = false;
	}

	wid = parseInt(wid);

	wppaSavedImageWidth 		= parseInt( wid );
	wppaSavedImageHeight 		= parseInt( wid * nh / nw );
	wppaSavedMarginLeft 		= - parseInt( ( wid / 2 + wppaOvlBorderWidth ) );
	wppaSavedContainerWidth 	= parseInt( wid + 2 * wppaOvlBorderWidth );
	wppaSavedContainerHeight 	= parseInt( wppaSavedImageHeight + wppaOvlBorderWidth + jQuery( '#wppa-overlay-txt-container' ).height() + 20 ); // padding = 10
	wppaSavedMarginTop 			= - parseInt( wppaSavedContainerHeight / 2 );

	// Go to final size
	wppaAnimate( '#wppa-overlay-img', {width:wppaSavedImageWidth,height:wppaSavedImageHeight}, speed, wppaEasingLightbox );
	wppaAnimate( '#wppa-overlay-ic', {width:wppaSavedContainerWidth,marginLeft:wppaSavedMarginLeft,marginTop:wppaSavedMarginTop}, speed, wppaEasingLightbox );

	// Done?
	if ( ! done ) {
		setTimeout( function(){ wppaOvlSize(wppaOvlAnimSpeed) }, speed + 10 );
	}
	else {

		// Remove spinner
		jQuery( '#wppa-ovl-spin' ).hide();
		wppaOvlFirst = false;
	}
	return true;
}

// Show fullscreen lightbox image
function wppaOvlFormatFull() {



	// Are we still in?
	if ( ! wppaOvlOpen ) {
		return;
	}

	// Panoramas do their own resize
	if ( wppaOvlActivePanorama > 0 ) {
		return;
	}

	var img;
	var natWidth;
	var natHeight;

	// Find the natural image sizes
	if ( wppaOvlIsVideo ) {
		img 		= document.getElementById( 'wppa-overlay-img' );
		natWidth 	= wppaOvlVideoNaturalWidths[wppaOvlIdx];
		natHeight 	= wppaOvlVideoNaturalHeights[wppaOvlIdx];
	}
	else if ( wppaOvlIsPdf  ) {
		img 		= document.getElementById( 'wppa-overlay-img' );
		natWidth 	= screen.width;
		natHeight 	= screen.height;
	}
	else {
		img 		= document.getElementById( 'wppa-overlay-img' );
		if ( ! wppaIsIe && ( ! img || ! img.complete ) ) {

			// Wait for load complete
			setTimeout( wppaOvlFormatFull, 200 );
			return;
		}
		natWidth 	= img.naturalWidth;
	 	natHeight 	= img.naturalHeight;
	}

	var screenRatio = screen.width / screen.height;
	var imageRatio 	= natWidth / natHeight;
	var margLeft 	= 0;
	var margTop 	= 0;
	var imgHeight 	= 0;
	var imgWidth 	= 0;
	var scrollTop 	= 0;
	var scrollLeft 	= 0;
	var Overflow 	= 'hidden';

	if ( screenRatio > imageRatio ) {	// Picture is more portrait
		margLeft 	= ( screen.width - screen.height * imageRatio ) / 2;
		margTop 	= 0;
		imgHeight 	= screen.height;
		imgWidth 	= screen.height * imageRatio;
	}
	else {
		margLeft 	= 0;
		margTop 	= ( screen.height - screen.width / imageRatio ) / 2;
		imgHeight 	= screen.width / imageRatio;
		imgWidth 	= screen.width;
	}

	margLeft 	= parseInt( margLeft );
	margTop 	= parseInt( margTop );
	imgHeight 	= parseInt( imgHeight );
	imgWidth 	= parseInt( imgWidth );
	jQuery(img).css({height:imgHeight,width:imgWidth,marginLeft:margLeft,marginTop:margTop,left:0,top:0,maxWidth:10000});
	jQuery(img).css({visibility:'visible'});
	jQuery( '#wppa-ovl-full-bg' ).css({overflow:Overflow});
	jQuery( '#wppa-ovl-full-bg' ).scrollTop( scrollTop );
	jQuery( '#wppa-ovl-full-bg' ).scrollLeft( scrollLeft );
	jQuery( '#wppa-ovl-spin' ).hide();
jQuery('#wppa-ovl-full-bg').css({visibility:'hidden'});

	// Add navbar
	html = jQuery( '#wppa-overlay-ic' ).html();
	html += '<div style="position:fixed;bottom:0;left:0;right:0;" >' + wppaOvlNavBar() + '</div>';
	jQuery( '#wppa-overlay-ic' ).html( html );

	wppaFsShow();

	return true;	// Done!
}

// Start audio on the lightbox view
function wppaOvlStartAudio() {

	// Due to a bug in $ ( $.play() does not exist ), must do myself:
	var elm = document.getElementById( 'wppa-overlay-audio' );
	if ( elm ) {
		if ( typeof( elm.play ) == 'function' ) {
			elm.play();
		}
	}
}

// Start / stop lightbox slideshow
function wppaOvlStartStop() {



	// Running?
	if ( wppaOvlRunning ) {

		// Stop it
		wppaOvlRunning = false;

		// If in a set: Determine visibility of browse buttons and make visible if appliccable
		if ( wppaOvlIdx != -1 ) {

			// NOT first, show prev button
			if ( wppaOvlIdx != 0 ) {
				jQuery( '.wppa-ovl-prev-btn' ).css('visibility', 'visible');
			}

			// NOT last, show next button
			if ( wppaOvlIdx != ( wppaOvlUrls.length-1 ) ) {
				jQuery( '.wppa-ovl-next-btn' ).css('visibility', 'visible');
			}
		}
			// Hide stop, show start buttn
			jQuery( '#wppa-ovl-stop-btn' ).hide();
			jQuery( '#wppa-ovl-start-btn' ).show();

//		}
	}

	// Not running
	else {

		// Start it
		wppaOvlRunning = true;
		wppaOvlRun();

			// Hide stop, show start buttn
			jQuery( '#wppa-ovl-stop-btn' ).show();
			jQuery( '#wppa-ovl-start-btn' ).hide();

	}

}

// Start lb slideshow
function wppaOvlRun() {

	// Already running?
	if ( ! wppaOvlRunning ) return;

	// Wait until playing audio or video ends
	if ( wppaOvlVideoPlaying || wppaOvlAudioPlaying ) {
		setTimeout( wppaOvlRun, 50 );
		return;
	}

	// If the current image is not yet complete, try again after 200 ms
	if ( ! wppaIsVideo && ! wppaOvlIsPdf ) {
		var elm = document.getElementById( 'wppa-overlay-img' );
		if ( elm ) {
			if ( ! wppaIsIe && ! elm.complete ) {
				setTimeout( wppaOvlRun, 200 );
				return;
			}
		}
	}

	wppaOvlShowNext();

	wppaOvlTimer = setTimeout( wppaOvlRun, wppaOvlSlideSpeed );
}

// One back in the set
function wppaOvlShowPrev() {

	// Not on a single image
	if ( wppaOvlIsSingle ) return false;

	jQuery("#wppa-ovl-spin").show();

	wppaOvlFsPhotoId = 0;
	wppaPhotoId = 0;

	var idx = wppaOvlCurIdx - 1;
	if ( idx < 0 ) {
		idx = wppaOvlUrls.length - 1;
	}

	// Preload previous, only if not video
	var img = document.getElementById( 'wppa-pre-prev' );
	if ( wppaOvlVideoHtmls[idx] == '' && ! wppaIsIe && ! img.complete && wppaOvlOpen ) {

		setTimeout( wppaOvlShowPrev, 200 );
		return false;
	}

	wppaOvlShow( idx );
	return false;
}

// One further in the set
function wppaOvlShowNext() {

	// Not on a single image
	if ( wppaOvlIsSingle ) return false;

	jQuery("#wppa-ovl-spin").show();

	wppaOvlFsPhotoId = 0;
	wppaPhotoId = 0;

	var idx = wppaOvlCurIdx + 1;
	if ( idx > ( wppaOvlUrls.length-1 ) ) {
		idx = 0;
	}

	// Preload next, only if not video
	var img = document.getElementById( 'wppa-pre-next' );
	if ( wppaOvlVideoHtmls[idx] == '' && ! wppaIsIe && ! img.complete && wppaOvlOpen ) {

		setTimeout( wppaOvlShowNext, 200 );
		return false;
	}

	wppaOvlShow( idx );
	return false;
}

// Show the same after orientatiochange or fullscreenchange
function wppaOvlShowSame() {

	// Are we in?
	if ( ! wppaOvlOpen ) return;

	// Find current index
	var now = wppaOvlCurIdx;

	// Are we running?
	var wasRunning = wppaOvlRunning;

	// Remove existing, but leave the background
	wppaOvlHide(true);

	// Show spinner
	jQuery( "#wppa-ovl-spin" ).show();

	// Redisplay
	setTimeout( function(){
		wppaOvlShow(now);
		if ( wasRunning ) {
			setTimeout( wppaOvlStartStop(), wppaOvlSlideSpeed );
		}
	}, 1000 );

}

// Quit lightbox mode
function wppaOvlHide(keepState) {


//	if ( !keepState ) keepState = false;

	// Record we are out
	wppaOvlOpen = false;
	wppaOvlClosing = ! wppaOvlClosing;

	// Stop audio
	wppaStopAudio();

	// Give up fullscreen mode optionally
	if ( ! keepState && wppaFsPolicy == 'lightbox' ) {
		if ( wppaIsFs() ) {
			wppaFsOff();
		}
	}

	// Clear image container
	jQuery( '#wppa-overlay-ic' ).html( '' ).hide();
	jQuery( '#wppa-overlay-pc' ).html( '' ).hide();
	jQuery( '#wppa-overlay-fpc' ).html( '' ).hide();
	jQuery( '#wppa-overlay-zpc' ).html( '' ).hide();

	// Remove kb handler
	jQuery(document).off( 'keydown', wppaOvlKeyboardHandler );
	wppaKbHandlerInstalled = false;

	// Reset switches
	wppaOvlFirst = true;
	wppaOvlRunning = false;
	clearTimeout( wppaOvlTimer );

	// Remove spinner
	jQuery( '#wppa-ovl-spin' ).hide();

	// Stop any panorama from running the wppaRenderer
	wppaOvlActivePanorama = 0;
//	if ( wppaRenderer ) wppaRenderer.resetGLState();
	jQuery('body').trigger('quitimage');

	// Record we are out
//	wppaOvlOpen = false;

	// Reatart slideshow if requested
	if ( document.onLbquitMocc ) {
		wppaStartStop( document.onLbquitMocc );
		document.onLbquitMocc = null;
		document.onLbquitIdx = null;
	}

	// Trigger lightbox end event
	jQuery(window).trigger( 'wppalightboxend' );

	// Fake a window reize
	jQuery(window).trigger('resize');

	// Do again if closing
	if ( wppaOvlClosing && wppaIsMobile ) {
		setTimeout(function(){wppaOvlHide(keepState)},250);
		return;
	}
	else {
		wppaOvlClosing = false;
	}

	// Remove background optionally
	if ( ! keepState ) {
		setTimeout(function(){
			jQuery( '#wppa-overlay-bg' ).hide();
			jQuery( '#wppa-ovl-spin' ).hide();

			// If fs policy == global and big browse, show the right button
			if ( wppaFsPolicy == 'global' && wppaOvlBigBrowse ) {
				wppaFsShow();
			}
		},500);
	}
}

// Perform onclick action
function wppaOvlOnclick( event ) {

	switch ( wppaOvlOnclickType ) {
		case 'none':
			break;
		case 'close':
			wppaOvlHide();
			break;
		case 'browse':
			var x = event.screenX - window.screenX;
			var y = event.clientY;
			if ( y > 48 ) {
				if ( x < screen.width / 2 ) wppaOvlShowPrev();
				else wppaOvlShowNext();
			}
			break;
		default:
			break;
	}
	return true;
}

// Initialize <a> tags with onclick and ontouchstart events to lightbox
function wppaInitOverlay() {



	// First find subtitles for non-wppa images
	jQuery( '.wp-caption' ).each( function() {
		var div 		= jQuery( this );
		var title 		= div.find( 'IMG[alt]' ).attr( 'alt' ) || '';
		var description = div.find( '.wp-caption-text' ).html() || '';
		var a 			= div.find( 'a' );
		var lbtitle 	= title + '<br>' + description;
		if ( ! a.attr( 'data-lbtitle' ) ) {
			a.attr( 'data-lbtitle', lbtitle );
		}
	});

	var anchors = jQuery( 'a' );
	var anchor;
	var i;
	var temp = [];

	wppaOvlFsPhotoId = 0; // Reset ovl fullscreen photo id
	wppaPhotoId = 0;
	wppaOvlActivePanorama = 0;

	// First time ?
	if ( wppaSavedContainerWidth == 0 ) {
		wppaSavedContainerWidth = 240 + 2 * wppaOvlBorderWidth;
		wppaSavedContainerHeight = 180 + 3 * wppaOvlBorderWidth + 20 + ( wppaOvlTxtHeight == 'auto' ? 50 : wppaOvlTxtHeight );
		wppaSavedMarginLeft = - ( 120 + wppaOvlBorderWidth );
		wppaSavedMarginTop = - ( 90 + wppaOvlBorderWidth + 10 + ( wppaOvlTxtHeight == 'auto' ? 25 : wppaOvlTxtHeight / 2 ) );
		wppaSavedImageWidth = 240;
		wppaSavedImageHeight = 180 + wppaOvlBorderWidth;
	}

	for ( i = 0; i < anchors.length; i++ ) {

		anchor = anchors[i];
		if ( jQuery( anchor ).attr( 'data-rel' ) ) {
			temp = jQuery( anchor ).attr( 'data-rel' ).split( "[" );
		}
		else if ( anchor.rel ) {
			temp = anchor.rel.split( "[" );
		}
		else {
			temp[0] = '';
		}

		if ( temp[0] == 'wppa' ) {

			// found one
			wppaWppaOverlayActivated = true;

				// Install onclick handler
				jQuery( anchor ).on( 'click', function( event ) {
					wppaOvlShow( this );
					event.preventDefault();
				});

			// Set cursor to magnifier
			switch ( wppaMagnifierCursor ) {
				case 'pointer':
					jQuery( anchor ).css( 'cursor', 'pointer' );
					break;
				case '':
					jQuery( anchor ).css( 'cursor', 'default' );
					break;
				default:
					jQuery( anchor ).css( 'cursor', 'url( ' + wppaImageDirectory + wppaMagnifierCursor + ' ),auto' );
			}

		}
	}

	// Install orientationchange handler
//	window.addEventListener( 'orientationchange', wppaOvlShowSame);

	// Install fullscreen navigation bar positioning
	jQuery(window).on('DOMContentLoaded load resize wppascrollend orientationchange', wppaAdjustControlbar );
}

// This module is intented to be used in any onclick definition that opens or closes a part of the photo description.
// this will automaticly adjust the picturesize so that the full description will be visible.
// Example: <a onclick="myproc()" >Show Details</a>
// Change to: <a onclick="myproc(); wppaOvlResize()" >Show Details</a>
// Isn't it simple?
function wppaOvlResize() {

	// Panoramas do their own resize
	if ( wppaOvlActivePanorama > 0 ) {
		return;
	}

	// After resizing, the number of lines may have changed
	setTimeout( 'wppaOvlSize( '+wppaOvlAnimSpeed+' )', 10 );

	if ( wppaOvlAudioStart && ! wppaOvlAudioPlaying ) {
		setTimeout( wppaOvlStartAudio, 100 );
	}
}

// Click on image
function wppaOvlImgClick( event ) {

	if ( wppaOvlBrowseOnClick && ! wppaOvlIsSingle ) {
		if ( event.screenX < ( screen.width / 2 ) ) {
			wppaOvlShowPrev();
		}
		else {
			wppaOvlShowNext();
		}
	}
}

// Make the navbar html
function wppaOvlNavBar() {

	// Init
	var html = '';

	// Prev / next buttons
	if ( ! wppaOvlIsSingle ) {

		// Previous
		if ( wppaOvlBigBrowse ) {
			html +=
			'<span' +
				' id="wppa-ovl-prev-btn"' +
				' class="wppa-ovl-prev-btn"' +
				' style="position:fixed;top:50%;left:0;width:60px;height:60px;margin-top:-30px;"' +
				' onclick="wppaOvlShowPrev()"' +
				' >' +
				wppaSvgHtml( 'Prev-Button-Big', '60px', true, false, 0, 0, 0, 0 ) +
			'</span>';
		}
		if ( wppaOvlSmallBrowse ) {
			html +=
			'<span' +
				' id="wppa-ovl-prev-btn"' +
				' class="wppa-ovl-prev-btn"' +
				' style="margin:0 2px 0 0;float:left;display:block;"' +
				' onclick="wppaOvlShowPrev()"' +
				' >' +
				wppaSvgHtml( 'Prev-Button', wppaOvlIconSize, true, true ) +
			'</span>';
		}

		html +=
		'<span' +
			' id="wppa-ovl-start-btn"' +
			' style="margin:0 2px;float:left;display:' + ( wppaOvlRunning ? 'none' : 'block' ) + ';"' +
			' title="Start"' +
			' onclick="wppaOvlStartStop()"' +
			' >' +
			wppaSvgHtml( 'Play-Button', wppaOvlIconSize, true, true ) +
		'</span>' +
		'<span' +
			' id="wppa-ovl-stop-btn"' +
			' style="margin:0 2px;float:left;display:' + ( wppaOvlRunning ? 'block' : 'none' ) + ';"' +
			' title="Stop"' +
			' onclick="wppaOvlStartStop()"' +
			' >' +
			wppaSvgHtml( 'Pause-Button', wppaOvlIconSize, true, true ) +
		'</span>';

		// Next
		if ( wppaOvlBigBrowse ) {
			html +=
			'<span' +
				' id="wppa-ovl-next-btn"' +
				' class="wppa-ovl-next-btn"' +
				' style="position:fixed;top:50%;right:0;width:60px;height:60px;margin-top:-30px;"' +
				' onclick="wppaOvlShowNext()"' +
				' >' +
				wppaSvgHtml( 'Next-Button-Big', '60px', true, false, 0, 0, 0, 0 ) +
			'</span>';
		}
		if ( wppaOvlSmallBrowse ) {
			html +=
			'<span' +
				' id="wppa-ovl-next-btn"' +
				' class="wppa-ovl-next-btn"' +
				' style="margin:0 2px;float:right;display:block;"' +
				' onclick="wppaOvlShowNext()"' +
				' >' +
				wppaSvgHtml( 'Next-Button', wppaOvlIconSize, true, true ) +
			'</span>';
		}

	}

	// The exit button
	if ( wppaOvlBigBrowse ) {
		html +=
		'<span' +
			' id="wppa-exit-btn-2"' +
			' class="sixty"' +
			' style="position:fixed;top:0;right:0;width:60px;height:60px;"' +
			' title="Exit"' +
			' onclick="wppaOvlHide()"' +
			' >' +
			wppaSvgHtml( 'Exit-Big', '60px', true, false, 0, 0, 0, 0 ) +
		'</span>';
	}
	if ( wppaOvlSmallBrowse ) {
		html +=
		'<span' +
			' id="wppa-exit-btn-2"' +
			' style="margin:0 2px;float:right;display:block;"' +
			' title="Exit"' +
			' onclick="wppaOvlHide()"' +
			' >' +
			wppaSvgHtml( 'Exit-2', wppaOvlIconSize, true, true ) +
		'</span>';
	}

	// The fs buttons
	if ( ( wppaFsPolicy == 'lightbox' || ( wppaFsPolicy == 'global' && wppaOvlBigBrowse ) ) && ! wppaIsSafari && ! wppaIsIpad ) {
		html +=
		'<span' +
			' id="wppa-fulls-btn-2"' +
			' class="wppa-fulls-btn"' +
			' style="margin:0 2px;float:right;display:none;"' +
			' title="Enter fullscreen"' +
			' onclick="wppaFsOn()"' +
			' >' +
			wppaSvgHtml( 'Full-Screen-2', wppaOvlIconSize, true, true ) +
		'</span>' +
		'<span' +
			' id="wppa-exit-fulls-btn-2"' +
			' class="wppa-exit-fulls-btn"' +
			' style="margin:0 2px;float:right;display:none;"' +
			' title="Leave fullscreen"' +
			' onclick="wppaFsOff()"' +
			' >' +
			wppaSvgHtml( 'Exit-Full-Screen-2', wppaOvlIconSize, true, true ) +
		'</span>';
	}

	return html;
}
