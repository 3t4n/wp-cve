// wppa-slideshow.js
//
// Contains slideshow modules
// Dependancies: wppa.js and default wp $ library
//
var wppaJsSlideshowVersion = '8.6.04.008';
var wppaHasControlbar = false;

// This is an entrypoint to load the slide data
function wppaStoreSlideInfo(
							mocc, 		// The occurrance of a wppa invocation
										// ( php: $wppa['master_occur'] )
							id, 		// The index in the slide array
							url, 		// The url to the fs image file
							size,
							width,
							height,
							fullname,
							name,
							desc,
							photoid, 		// The photo id (encrypted)
							realid, 		// The photo id (unencrypted)
							avgrat, 		// Average rating
							discount, 		// Dislike count
							myrat, 			// My rating
							rateurl, 		// The rating url
							linkurl,
							linktitle,
							linktarget,
							timeout,
							commenthtml, 	// The html code for the comment box
							iptchtml,
							exifhtml,
							lbtitle, 		// Lightbox subtext
							shareurl,
							smhtml,
							ogdsc,
							hiresurl, 		// The url to the hi res ( source ) image file
							videohtml, 		// The html for the video, or ''
							audiohtml,
							waittext, 		// The time you have to wait before you can vote again on the photo
							imagealt,
							posterurl,
							filename,
							panoramahtml,
							pancontrolheight,
							ratio,
							) {

	var cursor;

	desc = wppaRepairScriptTags( desc );

	if ( ! _wppaSlides[mocc] || '0' == id ) {	// First or next page
		_wppaSlides[mocc] = [];
		_wppaNames[mocc] = [];
		_wppaFilmThumbTitles[mocc] = [];
		_wppaFullNames[mocc] = [];
		_wppaDsc[mocc] = [];
		_wppaOgDsc[mocc] = [];
		_wppaCurIdx[mocc] = -1;
		_wppaNxtIdx[mocc] = 0;

		if ( wppaSavedSlideshowTimeout[mocc] ) {
			_wppaTimeOut[mocc] = wppaSavedSlideshowTimeout[mocc];
		}
		else {
			_wppaTimeOut[mocc] = timeout;
			wppaSavedSlideshowTimeout[mocc] = _wppaTimeOut[mocc];
		}
		_wppaSSRuns[mocc] = false;
		_wppaTP[mocc] = -2;	// -2 means NO, index for _wppaStartStop otherwise
		_wppaFg[mocc] = 0;
		_wppaIsBusy[mocc] = false;
		_wppaFirst[mocc] = true;
		_wppaId[mocc] = [];
		_wppaRealId[mocc] = [];
		_wppaAvg[mocc] = [];
		_wppaDisc[mocc] = [];
		_wppaMyr[mocc] = [];
		_wppaVRU[mocc] = [];
		_wppaLinkUrl[mocc] = []; // linkurl;
		_wppaLinkTitle[mocc] = []; // linktitle;
		_wppaLinkTarget[mocc] = [];
		_wppaCommentHtml[mocc] = [];
		_wppaIptcHtml[mocc] = [];
		_wppaExifHtml[mocc] = [];
		_wppaUrl[mocc] = [];
		_wppaSkipRated[mocc] = false;
		_wppaLbTitle[mocc] = [];
		_wppaDidGoto[mocc] = false;
		wppaSlidePause[mocc] = false;
		_wppaShareUrl[mocc] = [];
		_wppaShareHtml[mocc] = [];
		_wppaFilmNoMove[mocc] = false;
		_wppaHiresUrl[mocc] = [];
		_wppaIsVideo[mocc] = [];
		_wppaIsAudio[mocc] = [];
		_wppaVideoHtml[mocc] = [];
		_wppaAudioHtml[mocc] = [];
		_wppaVideoNatWidth[mocc] = [];
		_wppaVideoNatHeight[mocc] = [];
		wppaVideoPlaying[mocc] = false;
		wppaAudioPlaying[mocc] = false;
		_wppaWaitTexts[mocc] = [];
		_wppaImageAlt[mocc] = [];
		_wppaFilename[mocc] = [];
		_wppaPanoramaHtml[mocc] = [];
		_wppaPanControlHeight[mocc] = [];
		_wppaRatio[mocc] = [];

//		wppaFullSize[mocc] = wppaGetContainerWidth(mocc);
	}

	// Cursor
	cursor = 'default';
	if ( linkurl != '' ) {
		cursor = 'pointer';
	}
	else if ( wppaLightBox[mocc] != '' ) {
		cursor =  'url( '+wppaImageDirectory+wppaMagnifierCursor+' ),pointer';
	}

	// Is it a video?
	_wppaIsVideo[mocc][id] = ( '' != videohtml );

	// Is it an audio?
	_wppaIsAudio[mocc][id] = ( '' != audiohtml );

	// Fill _wppaSlides[mocc][id]
	if ( _wppaIsVideo[mocc][id] ) {
		_wppaSlides[mocc][id] = ' alt="' + imagealt + '" class="theimg theimg-'+mocc+' big" ';
		if ( wppaSlideVideoStart && wppaLightBox[mocc] == '' ) {
			_wppaSlides[mocc][id] += ' autoplay ';
		}
		if ( posterurl.length > 0 ) {
			_wppaSlides[mocc][id] += ' poster="' + posterurl + '" ';
		}
	}
	else if ( _wppaIsAudio[mocc][id] ) {
		_wppaSlides[mocc][id] = ' alt="' + imagealt + '" class="theimg theimg-'+mocc+' big" ';
		if ( wppaSlideAudioStart && wppaLightBox[mocc] == '' ) {
			_wppaSlides[mocc][id] += ' autoplay ';
		}
		_wppaSlides[mocc][id] = ' src="' + url + '" alt="' + imagealt + '" class="theimg theimg-'+mocc+' big stereo" ';
	}
	else {
		_wppaSlides[mocc][id] = ' src="' + url + '" alt="' + imagealt + '" class="theimg theimg-'+mocc+' big stereo" ';
	}

	// Add swipe
	if ( wppaSlideSwipe ) {
		_wppaSlides[mocc][id] += 	' ontouchstart="wppaTouchStart( event, this.id, '+mocc+' );"' +
									' ontouchend="wppaTouchEnd( event );"' +
									' ontouchmove="wppaTouchMove( event );"' +
									' ontouchcancel="wppaTouchCancel( event );" ';
	}

	// Add 'old' width and height only for non-auto
	if ( ! wppaAutoColumnWidth[mocc] ) _wppaSlides[mocc][id] += 'width="' + width + '" height="' + height + '" ';
	if ( _wppaIsVideo[mocc][id] ) {
		var controls;
		controls = 'wppa' == wppaLightBox[mocc] ? '' : 'controls';
		_wppaSlides[mocc][id] += 'style="' + size + '; cursor:'+cursor+'; display:none;" '+controls+'>'+videohtml+'</video>';
	}
	else {
		_wppaSlides[mocc][id] += 'style="' + size + '; cursor:'+cursor+'; display:none; vertical-align:middle;">';
	}

    _wppaFullNames[mocc][id] = '<span class="sdf-'+mocc+'" >'+wppaRepairBrTags(fullname)+'</span>';
    _wppaNames[mocc][id] = '<span class="sdn-'+mocc+'" >'+name+'</span>';
	_wppaFilmThumbTitles[mocc][id] = name;
    _wppaDsc[mocc][id] = desc;// '<span class="sdd-'+mocc+'" >'+desc+'</span>';
	_wppaOgDsc[mocc][id] = ogdsc;
	_wppaId[mocc][id] = photoid;		// reqd for rating and comment and monkey and registering views
	_wppaRealId[mocc][id] = realid;
	_wppaAvg[mocc][id] = avgrat;		// avg ratig value
	_wppaDisc[mocc][id] = discount;		// Dislike count
	_wppaMyr[mocc][id] = myrat;			// my rating
	_wppaVRU[mocc][id] = rateurl;		// url that performs the vote and returns to the page
	_wppaLinkUrl[mocc][id] = linkurl;
	_wppaLinkTitle[mocc][id] = linktitle;

	if ( linktarget != '' ) {
		_wppaLinkTarget[mocc][id] = linktarget;
	}
	else if ( wppaSlideBlank[mocc] ) {
		_wppaLinkTarget[mocc][id] = '_blank';
	}
	else {
		_wppaLinkTarget[mocc][id] = '_self';
	}

	_wppaCommentHtml[mocc][id] = commenthtml;
	_wppaIptcHtml[mocc][id] = iptchtml;
	_wppaExifHtml[mocc][id] = exifhtml;
	_wppaUrl[mocc][id] = url;		// Image url
	_wppaLbTitle[mocc][id] = wppaRepairScriptTags( lbtitle );
	_wppaShareUrl[mocc][id] = shareurl;
	_wppaShareHtml[mocc][id] = wppaRepairScriptTags( smhtml );
	_wppaHiresUrl[mocc][id] = hiresurl;
	_wppaVideoHtml[mocc][id] = videohtml;
	_wppaAudioHtml[mocc][id] = audiohtml;
	_wppaVideoNatWidth[mocc][id] = width;
	_wppaVideoNatHeight[mocc][id] = height;
	_wppaWaitTexts[mocc][id] = waittext;
	_wppaImageAlt[mocc][id] = imagealt;
	_wppaFilename[mocc][id] = filename;
	_wppaPanoramaHtml[mocc][id] = panoramahtml;
	_wppaPanControlHeight[mocc][id] = pancontrolheight;
	_wppaRatio[mocc][id] = ratio;
}

// These functions check the validity and store the users request to be executed later if busy and if applicable.
function wppaSpeed( mocc, faster ) {
	if ( _wppaSSRuns[mocc] ) {
		_wppaSpeed( mocc, faster );
	}
}

function wppaStopShow( mocc ) {
	if ( _wppaSSRuns[mocc] ) {
		_wppaStop( mocc );
	}
}

function wppaStopMedia( mocc ) {
	wppaStopAudio( mocc );
	wppaStopVideo( mocc );
	wppaStopShow( mocc );
}

// The application contains various togglers for start/stop
// The busy flag will be reset at the end of the NextSlide procedure
function wppaStartStop( mocc, index ) {

	// If prev or next page due to running or prev/next button, overrule default, only once!!
	if ( wppaSlideInitRunning[mocc] ) {
		if ( wppaSlideInitRunning[mocc] == 'start' ) {
			index = -1;
		}
		else if ( wppaSlideInitRunning[mocc] == 'stopprev' ) {
			index = _wppaSlides[mocc].length -1;
		}
		else if ( wppaSlideInitRunning[mocc] == 'stopnext' ) {
			index = 0;
		}
		wppaSlideInitRunning[mocc] = '';
	}

	if ( _wppaIsBusy[mocc] ) {

		// Remember there is a toggle pending
		_wppaTP[mocc] = index;
	}

	// Actually do it
	if ( _wppaSSRuns[mocc] ) {

		// Stop it
		_wppaStop( mocc );
	}
	else {

		// Start it
		_wppaStart( mocc, index );
	}

	if ( wppaIsMobile ) {
		jQuery( '#wppa-startstop-icon-' + mocc ).stop().fadeTo( 10, 1 ).fadeTo( 3000, 0 );
		jQuery( '.ubb-'+mocc ).stop().fadeTo( 10, 1 ).fadeTo( 3000, 0 );
	}
}

function wppaBbb( mocc, where, act ) {
	if ( ! _wppaSSRuns[mocc] ) {

		// Big Browsing Buttons only work when stopped
		_wppaBbb( mocc, where, act );
	}
}

function wppaUbb( mocc, where, act ) {
	_wppaUbb( mocc, where, act );
}

function wppaRateIt( mocc, value ) {
	_wppaRateIt( mocc, value );
}

function wppaOvlRateIt( id, value, mocc, reloadAfter ) {
	_wppaOvlRateIt( id, value, mocc, reloadAfter );
}

function wppaPrev( mocc ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaPrev( mocc );
	}
}

function wppaPrevN( mocc, n ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaPrevN( mocc, n );
	}
}

function wppaFirst( mocc ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaGoto( mocc, 0 );
	}
}

function wppaNext( mocc ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaNext( mocc );
	}
}

function wppaNextN( mocc, n ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaNextN( mocc, n );
	}
}

function wppaLast( mocc ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaGoto( mocc, _wppaSlides[mocc].length - 1 );
	}
}

function wppaFollowMe( mocc, idx ) {
wppaStopMedia( mocc );
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaFollowMe( mocc, idx );
	}
}

function wppaLeaveMe( mocc, idx ) {
wppaStopMedia( mocc );
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaLeaveMe( mocc, idx );
	}
}

function wppaGoto( mocc, idx ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaGoto( mocc, idx );
	}
}

function wppaGotoFilmNoMove( mocc, idx ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSSRuns[mocc] ) {
		_wppaFilmNoMove[mocc] = true;
		_wppaGoto( mocc, idx );
	}
}

function wppaGotoKeepState( mocc, idx ) {
wppaStopMedia( mocc );
	if ( _wppaNxtIdx[mocc] == idx ) return;
	_wppaDidGoto[mocc] = true;
	_wppaGotoKeepState( mocc, idx );
}

function _wppaGotoKeepState( mocc, idx ) {
	if ( _wppaSSRuns[mocc] ) {
		_wppaGotoRunning( mocc,idx );
	}
	else {
		_wppaGoto( mocc,idx );
	}
}

function wppaGotoRunning( mocc, idx ) {
wppaStopMedia( mocc );
	_wppaDidGoto[mocc] = true;
	_wppaGotoRunning( mocc, idx );
}

function wppaValidateComment( mocc ) {
	return _wppaValidateComment( mocc );
}

function _wppaNextSlide( mocc, mode ) {


	var isFilmOnly = ! document.getElementById( 'slide_frame-'+mocc );

	// If not in viewport, try again later
	/*
	if ( ! isFilmOnly && ! wppaIsSlidshowVisible( mocc ) ) {

		// Fake not inited to cause jump to loc the first time
		wppaFilmInit[mocc] = false;
		setTimeout(function(){_wppaNextSlide( mocc, mode )},400);
		return;
	}
	*/

	// Remember current
	_wppaLastIdx[mocc] = _wppaCurIdx[mocc];

	// Filmonly continuously?
	if ( ! document.getElementById( 'slide_frame-'+mocc ) && document.getElementById( 'filmwindow-'+mocc ) && wppaFilmonlyContinuous ) {

		if ( ! _wppaSSRuns[mocc] ) {
			_wppaCurIdx[mocc] = _wppaNxtIdx[mocc];
			wppaFilmInit[mocc] = false;
			_wppaAdjustFilmstrip( mocc, 'linear' );
			return;
		}

		// Find index of next slide if in auto mode and not stop in progress
		_wppaCurIdx[mocc] ++;
		if ( _wppaCurIdx[mocc] == _wppaSlides[mocc].length ) _wppaCurIdx[mocc] = 0;

		// Adjust filmstrip
		_wppaAdjustFilmstrip( mocc, 'linear' );
		_wppaNxtIdx[mocc] = _wppaCurIdx[mocc];

		// Go for the next
		setTimeout( '_wppaNextSlide( '+mocc+', \''+mode+'\' )', wppaAnimationSpeed );
		return;
	}

	// Not slide and not filmonly normal (non contin) ?
	if ( ! document.getElementById( 'slide_frame-'+mocc ) && ! document.getElementById( 'filmwindow-'+mocc ) ) {
		return; 	// Nothing to do here
	}

	var fg = _wppaFg[mocc];
	var bg = 1 - fg;

	// If audio video is playing, delay a running slideshow
	if ( ( wppaVideoPlaying[mocc] || wppaAudioPlaying[mocc] ) && _wppaSSRuns[mocc] ) {
		setTimeout( '_wppaNextSlide( '+mocc+', \''+mode+'\' )', 500 ); 	// Retry after 500 ms
		return;
	}

	// Stop any playing video
//	wppaStopVideo( mocc );

	// Stop playing audio
//	wppaStopAudio( mocc );

	// Paused??
	if ( 'auto' == mode ) {
		if ( wppaSlidePause[mocc] ) {
			jQuery( '#theimg'+fg+'-'+mocc ).attr( "title", wppaSlidePause[mocc] );
			jQuery( '#slide_frame-'+mocc ).attr( "title", wppaSlidePause[mocc] );
			setTimeout( '_wppaNextSlide( '+mocc+', "auto" )', 250 );	// Retry after 250 ms.
			return;
		}
	}
	else {
		jQuery( '#slide_frame-'+mocc ).removeAttr( "title" );
	}

	// Kill an old timed request, while stopped
	if ( ! _wppaSSRuns[mocc] && 'auto' == mode ) return;

	// Empty slideshow?
	if ( ! _wppaSlides[mocc] ) return;

	// Do not animate single image
	if ( _wppaSlides[mocc].length < 2 && ! _wppaFirst[mocc] ) return;

	// Reset request?
	if ( ! _wppaSSRuns[mocc] && 'reset' == mode ) {
		_wppaSSRuns[mocc] = true;
		__wppaOverruleRun = false;
	}

	// No longer busy voting
	_wppaVoteInProgress = false;

	// Set the busy flag
	_wppaIsBusy[mocc] = true;

	// Hide metadata while changing image
	if ( _wppaSSRuns[mocc] ) _wppaShowMetaData( mocc, 'hide' );

	// Find index of next slide if in auto mode and not stop in progress
	if ( _wppaSSRuns[mocc] ) {
		_wppaNxtIdx[mocc] = _wppaCurIdx[mocc] + 1;
		if ( _wppaNxtIdx[mocc] == _wppaSlides[mocc].length ) _wppaNxtIdx[mocc] = 0;
	}

	// Update geo if any
	// GPX Plugin
	jQuery( '#geodiv-'+mocc+'-'+_wppaId[mocc][_wppaCurIdx[mocc]] ).css( { display: 'none' });
	jQuery( '#geodiv-'+mocc+'-'+_wppaId[mocc][_wppaNxtIdx[mocc]] ).css( { display: '' });

	// WPPA+ Native
	if ( typeof( _wppaLat ) != 'undefined' ) {
		if ( _wppaLat[mocc] ) {
			var id = _wppaRealId[mocc];
			if ( _wppaLat[mocc][id[_wppaNxtIdx[mocc]]] ) {
				jQuery( '#map-canvas-'+mocc ).css( 'display', '' );
				wppaGeoInit( mocc, _wppaLat[mocc][id[_wppaNxtIdx[mocc]]], _wppaLon[mocc][id[_wppaNxtIdx[mocc]]] );
			}
			else jQuery( '#map-canvas-'+mocc ).css( 'display', 'none' );
		}
		else jQuery( '#map-canvas-'+mocc ).css( 'display', 'none' );
	}
	else jQuery( '#map-canvas-'+mocc ).css( 'display', 'none' );

	// Set numbar backgrounds and fonts
	jQuery( '[id^=wppa-numbar-' + mocc + '-]' ).css( {	backgroundColor: wppaBGcolorNumbar,
													borderColor: wppaBcolorNumbar,
													fontFamily: wppaFontFamilyNumbar,
													fontSize: wppaFontSizeNumbar,
													color: wppaFontColorNumbar,
													fontWeight: wppaFontWeightNumbar
													});

	jQuery( "#wppa-numbar-" + mocc + "-" + _wppaNxtIdx[mocc] ).css( {	backgroundColor: wppaBGcolorNumbarActive,
																	borderColor: wppaBcolorNumbarActive,
																	fontFamily: wppaFontFamilyNumbarActive,
																	fontSize: wppaFontSizeNumbarActive,
																	color: wppaFontColorNumbarActive,
																	fontWeight: wppaFontWeightNumbarActive
																	});

	// too many?
	if ( _wppaSlides[mocc].length > wppaNumbarMax ) {

		var lo, hi, mx = _wppaSlides[mocc].length - 1, is = _wppaNxtIdx[mocc], arm = ( wppaNumbarMax - 1 ) / 2;

		// Near left
		if ( is < arm ) {
			lo = 0;
			hi = wppaNumbarMax - 1 - 1;
			jQuery( "#wppa-nbar-"+ mocc + "-lodots" ).css({display:'none'});
			jQuery( "#wppa-nbar-"+ mocc + "-hidots" ).css({display:'block'});
		}
		// Near right
		else if ( is > ( mx - arm ) ) {
			hi = mx;
			lo = mx - wppaNumbarMax + 1 + 1;
			jQuery( "#wppa-nbar-"+ mocc + "-lodots" ).css({display:'block'});
			jQuery( "#wppa-nbar-"+ mocc + "-hidots" ).css({display:'none'});
		}
		// near center
		else {
			lo = is - arm + 1;
			hi = is + arm + 0.5 - 1;
			if ( lo < 2 ) {
				jQuery( "#wppa-nbar-"+ mocc + "-lodots" ).css({display:'none'});
				jQuery( "#wppa-nbar-"+ mocc + "-hidots" ).css({display:'block'});
			}
			else if ( hi > mx - 1 ) {
				jQuery( "#wppa-nbar-"+ mocc + "-lodots" ).css({display:'block'});
				jQuery( "#wppa-nbar-"+ mocc + "-hidots" ).css({display:'none'});

			}
			else {
				jQuery( "#wppa-nbar-"+ mocc + "-lodots" ).css({display:'block'});
				jQuery( "#wppa-nbar-"+ mocc + "-hidots" ).css({display:'block'});
			}
		}
		var i = 0;
		while ( i < _wppaSlides[mocc].length ) {
			if ( i != 0 && i != mx && ( i < lo || i > hi ) ) {
				jQuery( "#wppa-numbar-" + mocc + "-" + i ).css({display:'none'});
			}
			else {
				jQuery( "#wppa-numbar-" + mocc + "-" + i ).css({display:'block'});
			}
			i++;
		}
	}

    // first:
    if ( _wppaFirst[mocc] ) {
	    if ( _wppaCurIdx[mocc] != -1 ) {
			wppaMakeTheSlideHtml( mocc, '0', _wppaCurIdx[mocc] );
		}
		wppaMakeTheSlideHtml( mocc, '1', _wppaNxtIdx[mocc] );

		// Display name, description and comments
		jQuery( "#imagedesc-"+mocc ).html( _wppaDsc[mocc][_wppaCurIdx[mocc]] );
		jQuery( "#imagetitle-"+mocc ).html( wppaMakeNameHtml( mocc ) );

		// Comments displayable?
		if ( _wppaCommentHtml[mocc][_wppaCurIdx[mocc]] == 'void' ) {
			jQuery( "#wppa-comments-"+mocc ).hide();
			jQuery( "#wppa-comments-"+mocc ).html('');
		}
		else {
			jQuery( "#wppa-comments-"+mocc ).show();
			jQuery( "#wppa-comments-"+mocc ).html( _wppaCommentHtml[mocc][_wppaCurIdx[mocc]] );
		}
		jQuery( "#iptc-"+mocc ).html( _wppaIptcHtml[mocc][_wppaCurIdx[mocc]] );
		jQuery( "#exif-"+mocc ).html( _wppaExifHtml[mocc][_wppaCurIdx[mocc]] );

		// Display prev/next
		if ( wppaSlideshowNavigationType == 'icons' ) {
			var iconsize = wppaIconSize( mocc, '1.5em', false );
			jQuery( '#prev-arrow-'+mocc ).html( wppaSvgHtml( 'Prev-Button', iconsize, false, true ) );
			jQuery( '#next-arrow-'+mocc ).html( wppaSvgHtml( 'Next-Button', iconsize, false, true ) );
		}
		else {
			if ( wppaIsMini[mocc] || wppaGetContainerWidth( mocc ) < wppaMiniTreshold ) {
				jQuery( '#prev-arrow-'+mocc ).html( '&laquo;&nbsp;'+__( 'Previous', 'wp-photo-album-plus' ) );
				jQuery( '#next-arrow-'+mocc ).html( __( 'Next', 'wp-photo-album-plus' )+'&nbsp;&raquo;' );
			}
			else {
				jQuery( '#prev-arrow-'+mocc ).html( '&laquo;&nbsp;'+__( 'Previous photo', 'wp-photo-album-plus' ) );
				jQuery( '#next-arrow-'+mocc ).html( __( 'Next photo', 'wp-photo-album-plus' )+'&nbsp;&raquo;' );
			}
		}

		// Display Rating
		if ( wppaIsMini[mocc] || wppaGetContainerWidth( mocc ) < wppaMiniTreshold ) {
			jQuery( '#wppa-avg-rat-'+mocc ).html( __( 'Avg', 'wp-photo-album-plus' ) );
			jQuery( '#wppa-my-rat-'+mocc ).html( __( 'Mine', 'wp-photo-album-plus' ) );
		}
		else {
			jQuery( '#wppa-avg-rat-'+mocc ).html( __( 'Average&nbsp;rating', 'wp-photo-album-plus' ) );
			jQuery( '#wppa-my-rat-'+mocc ).html( __( 'My&nbsp;rating', 'wp-photo-album-plus' ) );
		}
	}

    // load next img ( backg )
    else {
		wppaMakeTheSlideHtml( mocc, bg, _wppaNxtIdx[mocc] );
    }

	_wppaFirst[mocc] = false;

	// Give free for a while to enable rendering of what we have done so far
	setTimeout( '_wppaNextSlide_2( ' + mocc + ' )', 10 );	// to be continued after 10 ms
}

function _wppaNextSlide_2( mocc ) {

	var fg = _wppaFg[mocc];
	var bg = 1 - fg;

	// Wait for load complete
	var elm = document.getElementById( 'theimg' + bg + "-" + mocc );
	if ( elm ) { // Exists
		if ( 1 == elm.nodeType ) {											// Is html
			if ( 'IMG' == elm.nodeName ) {									// Is an image
				if ( ! elm.complete ) {										// Is not complete yet
					setTimeout( '_wppaNextSlide_2( ' + mocc + ' )', 200 );	// Try again after 200 ms
					return;
				}
			}
		}
	}

	// Update lightbox
	wppaUpdateLightboxes();

	jQuery( '#wppa-slide-spin-' + mocc ).hide();

	// Hide subtitles
	if ( _wppaSSRuns[mocc] != -1 ) {	// not stop in progress
		if ( ! _wppaToTheSame ) {
			_wppaShowMetaData( mocc, 'hide' );
		}
	}

	// change foreground
	_wppaFg[mocc] = 1 - _wppaFg[mocc];
	fg = _wppaFg[mocc];
	bg = 1 - fg;
	setTimeout( '_wppaNextSlide_3( ' + mocc + ' )', 10 );
}

function _wppaNextSlide_3( mocc ) {

	var nw 		= _wppaFg[mocc];
	var ol 		= 1 - nw;

	var olIdx 	= _wppaCurIdx[mocc];
	var nwIdx 	= _wppaNxtIdx[mocc];

	var olSli	= "#theslide" + ol + "-" + mocc;
	var nwSli 	= "#theslide" + nw + "-" + mocc;

	var olImg	= "#theimg" + ol + "-" + mocc;
	var nwImg	= "#theimg" + nw + "-" + mocc;

	var w 		= parseInt( jQuery( olSli ).css( 'width' ) );
	var dir 	= 'nil';

	if ( olIdx == nwIdx ) dir = 'none';
	if ( olIdx == nwIdx - 1 ) dir = 'left';
	if ( olIdx == nwIdx + 1 ) dir = 'right';
	if ( olIdx == _wppaSlides[mocc].length - 1 && 0 == nwIdx && wppaSlideWrap[mocc] ) dir = 'left';
	if ( 0 == olIdx && nwIdx == _wppaSlides[mocc].length - 1 && wppaSlideWrap[mocc] ) dir = 'right';

	// Not known yet?
	if ( 'nil' == dir ) {
		if ( olIdx < nwIdx ) dir = 'left';
		else dir = 'right';
	}

	// Repair standard css
	jQuery( olSli ).css( { marginLeft:0, width:w });
	jQuery( nwSli ).css( { marginLeft:0, width:w });

	wppaFormatSlide( mocc );

	switch ( wppaAnimationType ) {

		case 'fadeafter':
			wppaFadeOut( olImg, wppaAnimationSpeed );

			// For panos and videos:
			jQuery('#theslide'+ol+'-'+mocc).find('canvas').fadeOut(wppaAnimationSpeed);
			jQuery('#theslide'+ol+'-'+mocc).find('video').fadeOut(wppaAnimationSpeed);

			setTimeout( wppaFadeIn( nwImg, wppaAnimationSpeed, _wppaNextSlide_4( mocc ) ), wppaAnimationSpeed );
//			jQuery( nwImg ).delay( wppaAnimationSpeed ).fadeIn( wppaAnimationSpeed, _wppaNextSlide_4( mocc ) );
			break;

		case 'swipe':
			switch ( dir ) {
				case 'left':
					wppaAnimate( olSli, { marginLeft: - w }, wppaAnimationSpeed, wppaEasingSlide );
					jQuery( nwSli ).css( { marginLeft: w });
					wppaFadeIn( nwImg, 10 );
					wppaAnimate( nwSli, { marginLeft: 0 }, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					break;
				case 'right':
					wppaAnimate( olSli, { marginLeft: w }, wppaAnimationSpeed, wppaEasingSlide );
					jQuery( nwSli ).css( { marginLeft: - w });
					wppaFadeIn( nwImg, 10 );
					wppaAnimate( nwSli, { marginLeft: 0 }, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					break;
				case 'none':
					wppaFadeIn( nwImg, 10 );
					setTimeout( '_wppaNextSlide_4( ' + mocc + ' )', 10 );
					break;
			}
			break;
/*
		case 'stackon':
			switch ( dir ) {
				case 'left':
					jQuery( olSli ).css( {zIndex:80});
					jQuery( nwSli ).css( {marginLeft:w+"px", zIndex:81});
					wppaFadeIn( nwImg, 10 );
					setTimeout( wppaFadeOut( olImg, 10 ), wppaAnimationSpeed );
//					setTimeout( wppaFadeOut( olImg, 10 ), wppaAnimationSpeed );
					wppaAnimate( nwSli, {marginLeft:0+"px"}, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					break;
				case 'right':
					jQuery( olSli ).css( {zIndex:80});
					jQuery( nwSli ).css( {marginLeft:-w+"px", zIndex:81});
					wppaFadeIn( nwImg, 10 );
					setTimeout( wppaFadeOut( olImg, 10 ), wppaAnimationSpeed );
					wppaAnimate( nwSli, {marginLeft:0+"px"}, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					break;
				case 'none':
					wppaFadeIn( nwImg, 10 );
					setTimeout( '_wppaNextSlide_4( '+mocc+' )', 10 );
					break;
			}
			break;

		case 'stackoff':
			switch ( dir ) {
				case 'left':
					jQuery( olSli ).css( {marginLeft:0, zIndex:81});
					wppaAnimate( olSli, {marginLeft:-w+"px"}, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					jQuery( nwSli ).css( {marginLeft:0, zIndex:80});
					wppaFadeIn( nwImg, 10 );
					setTimeout( wppaFadeOut( olImg, 10 ), wppaAnimationSpeed );
					break;
				case 'right':
					jQuery( olSli ).css( {marginLeft:0, zIndex:81});
					wppaAnimate( olSli, {marginLeft:w+"px"}, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					jQuery( nwSli ).css( {marginLeft:0, zIndex:80});
					wppaFadeIn( nwImg, 10 );
					setTimeout( wppaFadeOut( olImg, 10 ), wppaAnimationSpeed );
					break;
				case 'none':
					wppaFadeIn( nwImg, 10 );
					setTimeout( '_wppaNextSlide_4( '+mocc+' )', 10 );
					break;
			}
			break;

		case 'turnover':
			switch ( dir ) {
				case 'left':
/*	there is a z-order problem here, if you can fix it, i would be glad
					jQuery( olSli ).css( {zIndex:81});
					jQuery( olSli ).wppaAnimate( {width:0}, wppaAnimationSpeed, wppaEasingSlide );
					jQuery( olImg ).wppaAnimate( {marginLeft:0, width:0, paddingLeft:0, paddingRight:0}, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					jQuery( nwSli ).css( {width:w, zIndex:80});
					wppaFadeIn( nwImg, 10 );
					jQuery( olImg ).fadeOut( 10 );
					break;
*//*
				case 'right':
					var nwImgWid = parseInt( jQuery( nwSli ).css( 'width' ) );
					var nwMarLft = parseInt( jQuery( nwImg ).css( 'marginLeft' ) );
					jQuery( olSli ).css( {zIndex:80});
					jQuery( nwSli ).css( {zIndex:81, width:0});
					jQuery( nwImg ).css( {maxWidth:0, marginLeft:0});
					wppaFadeIn( nwImg, 10 );
					wppaAnimate( nwsli, {width:w}, wppaAnimationSpeed, wppaEasingSlide );
					wppaAnimate( nwImg, {maxWidth:nwImgWid, marginLeft:nwMarLft}, wppaAnimationSpeed, wppaEasingSlide, _wppaNextSlide_4( mocc ) );
					setTimeout( wppaFadeOut( olImg, 10 ), wppaAnimationSpeed );
					break;

				case 'none':
					wppaFadeIn( nwImg, 10 );
					setTimeout( '_wppaNextSlide_4( '+mocc+' )', 10 );
					break;
				}
			break;
*/
		default:
//		case 'fadeover':
			wppaFadeOut( olImg, wppaAnimationSpeed );
			jQuery('#theslide'+ol+'-'+mocc).find('canvas').fadeOut(wppaAnimationSpeed);
			jQuery('#theslide'+ol+'-'+mocc).find('video').fadeOut(wppaAnimationSpeed);
			wppaFadeIn( nwImg, wppaAnimationSpeed, _wppaNextSlide_4( mocc ) );
			break;
	}
}

function _wppaNextSlide_4( mocc ) {


	var nw 		= _wppaFg[mocc];
	var ol 		= 1 - nw;
	var olSli	= "#theslide" + ol + "-" + mocc;
	var nwSli 	= "#theslide" + nw + "-" + mocc;

	// Make sure title and onclick of the new image ( slide ) are in sight
	jQuery( olSli ).css({ zIndex:80 });
	jQuery( nwSli ).css({ zIndex:81 });

    // Next is now current // put here for swipe
	_wppaCurIdx[mocc] = _wppaNxtIdx[mocc];

	wppaFormatSlide( mocc );

	// Display counter and arrow texts
	if ( wppaIsMini[mocc] || wppaGetContainerWidth( mocc ) < wppaMiniTreshold ) {
		jQuery( '#counter-'+mocc ).html( ( _wppaCurIdx[mocc]+1 )+' / '+_wppaSlides[mocc].length );
	}
	else {
		jQuery( '#counter-'+mocc ).html( __( 'Photo', 'wp-photo-album-plus' )+' '+( _wppaCurIdx[mocc]+1 )+' '+__( 'of', 'wp-photo-album-plus' )+' '+_wppaSlides[mocc].length );
	}

	// Update breadcrumb
	jQuery( '#bc-pname-modal-' + mocc ).html( _wppaNames[mocc][_wppaCurIdx[mocc]] );
	jQuery( '#bc-pname-' + mocc ).html( _wppaNames[mocc][_wppaCurIdx[mocc]] );

	// Adjust filmstrip
	_wppaAdjustFilmstrip( mocc, wppaEasingSlide );

	// Set rating mechanism
	_wppaSetRatingDisplay( mocc );

	// Wait for almost next slide
	setTimeout( '_wppaNextSlide_5( ' + mocc + ' )', _wppaTextDelay );
}

function _wppaNextSlide_5( mocc ) {

	// If we are going to the same slide, there is no need to hide and restore the subtitles and commentframe
	if ( ! _wppaToTheSame ) {

		// Restore subtitles
		var imageDescHtml = _wppaDsc[mocc][_wppaCurIdx[mocc]];

		jQuery( '#imagedesc-'+mocc ).html( imageDescHtml );

		if ( wppaHideWhenEmpty ) {
			var desc = _wppaDsc[mocc][_wppaCurIdx[mocc]];
			if ( '' == desc || '&nbsp;' == desc ) {
				jQuery( '#descbox-'+mocc ).css( 'display', 'none' );
			}
			else {
				jQuery( '#descbox-'+mocc ).css( 'display', '' );
			}
		}
		jQuery( "#imagetitle-"+mocc ).html( wppaMakeNameHtml( mocc ) );

		// Restore comments html
		if ( _wppaCommentHtml[mocc][_wppaCurIdx[mocc]] == 'void' ) {
			jQuery( "#wppa-comments-"+mocc ).hide();
			jQuery( "#wppa-comments-"+mocc ).html('');
		}
		else {
			jQuery( "#wppa-comments-"+mocc ).show();
			jQuery( "#wppa-comments-"+mocc ).html( _wppaCommentHtml[mocc][_wppaCurIdx[mocc]] );
		}

		// Restor IPTC
		jQuery( "#iptc-"+mocc ).html( _wppaIptcHtml[mocc][_wppaCurIdx[mocc]] );
		jQuery( "#exif-"+mocc ).html( _wppaExifHtml[mocc][_wppaCurIdx[mocc]] );

		// Restore share html
		jQuery( "#wppa-share-"+mocc ).html( _wppaShareHtml[mocc][_wppaCurIdx[mocc]] );
	}
	_wppaToTheSame = false;					// This has now been worked out

	// Find page for returning to thumbnails
	if ( wppaThumbPageSize ) {
		wppaThumbPage[mocc] = parseInt( ( ( wppaSlideOffset[mocc] | 0 ) + _wppaCurIdx[mocc] ) / wppaThumbPageSize ) + 1;
	}
	else {
		wppaThumbPage[mocc] = 1;
	}

	// End of non wrapped show?
	if ( _wppaSSRuns[mocc] &&
		! wppaSlideWrap[mocc] &&
		( ( _wppaCurIdx[mocc] + 1 ) == _wppaSlides[mocc].length ) ) {
			_wppaIsBusy[mocc] = false;
			_wppaStop( mocc );	// stop
			return;
	}

	// Re-display the metadata
	_wppaShowMetaData( mocc, 'show' );

	// Almost done, finalize
	if ( _wppaTP[mocc] != -2 ) {								// A Toggle pending?
		var index = _wppaTP[mocc];								// Remember the pending startstop request argument
		_wppaTP[mocc] = -2;										// Reset the pending toggle
		_wppaDidGoto[mocc] = false;								// Is worked out now
		_wppaIsBusy[mocc] = false;								// No longer busy
		if ( ! wppaIsMini[mocc] ) { 							// Not in a widget
			_bumpViewCount( _wppaId[mocc][_wppaCurIdx[mocc]] );	// Register a view
		}
		_wppaDoAutocol(mocc, 'next_5');
		wppaStartStop( mocc, index );							// Do as if the toggle request happens now
		return;
	}
	else {														// No toggle pending
		wppaUpdateLightboxes(); 								// Refresh lightbox

		// Update url and title if ( ( this is non-mini ) AND
		// ( this is the only running non-mini OR there are no running non-minis ) )
		if ( ! wppaIsMini[mocc] ) {		// This is NOT a widget

			// Prepare visual url ( for addressline )
			var visurl = _wppaShareUrl[mocc][_wppaCurIdx[mocc]];

			// Update possible QR Widget
			if ( typeof( wppaQRUpdate ) != 'undefined' ) {
				wppaQRUpdate( _wppaShareUrl[mocc][_wppaCurIdx[mocc]] );
			}

			// Push state if not slphoto
			if ( _wppaSlides[mocc].length > 1 ) {
				wppaPushStateSlide( mocc, _wppaCurIdx[mocc], visurl );
			}
		}

		// If running: Wait for next slide
		if ( _wppaSSRuns[mocc] ) {

			// Are we at the end of a page?
			if ( ( _wppaCurIdx[mocc] + 1 ) == _wppaSlides[mocc].length ) {

				var npb = jQuery( '#wppa-next-pagelink-' + mocc );
				var fpb = jQuery( '#wppa-first-pagelink-' + mocc );

				// If there is a non-hidden next page button, trigger next page after timeout
				if ( npb.length > 0 && jQuery( npb ).css('visibility') != 'hidden' ) {
					setTimeout( function() {
	//					wppaSavedSlideshowTimeout[mocc] = _wppaTimeOut[mocc];
						wppaSlideInitRunning[mocc] = 'start';
						jQuery( '#wppa-next-pagelink-' + mocc ).trigger( 'click' );
					}, wppaGetSlideshowTimeout( mocc, 'next page trigger' ) );
				}

				// If there is a (hidden) first page button, trigger it after timeout
				else if ( fpb.length > 0 ) {
					setTimeout( function() {
	//					wppaSavedSlideshowTimeout[mocc] = _wppaTimeOut[mocc];
						jQuery( fpb ).trigger( 'click' );
					}, wppaGetSlideshowTimeout( mocc, 'first page trigger' ) );
				}

				// Just wait for next slide in current page
				else {
					setTimeout( '_wppaNextSlide( '+mocc+', "auto" )', wppaGetSlideshowTimeout( mocc, 'just wait for next in current(1)' ) );
				}
			}

			// Just wait for next slide in current page
			else {
				setTimeout( '_wppaNextSlide( '+mocc+', "auto" )', wppaGetSlideshowTimeout( mocc, 'just wait for next in current(2)' ) );
			}
		}
//		else {
//			_wppaStopping[mocc] = false;
//		}
	}

	// If glossery tooltip on board...
	jQuery(document).trigger( 'glossaryTooltipReady' );

	_wppaDidGoto[mocc] = false;								// Is worked out now
	_wppaIsBusy[mocc] = false;								// No longer busy
	if ( ! wppaIsMini[mocc] ) { 							// Not in a widget
		_bumpViewCount( _wppaId[mocc][_wppaCurIdx[mocc]] );	// Register a view
	}

//	wppaStopAudio( mocc );

/*
	if ( wppaSlideAudioStart ) {
		var elms = jQuery( '.wppa-audio-'+_wppaId[mocc][_wppaCurIdx[mocc]]+'-'+mocc );
		if ( elms.length > 0 ) {
			var audio = elms[elms.length-1];
			if ( audio ) {
				if ( ! wppaAudioPlaying[mocc] ) {
					audio.play();
				}
			}
		}
	}
*/
	// Hide rightclick optionally
	wppaProtect();

	// Trigger resize for modal containers. This will diturb lightbox, so we do this only when lightbox is not open
	if ( jQuery('#wppa-overlay-bg').css('display') == 'none' ) {
		jQuery(window).trigger('resize');
	}

	// Hide possible zoom cotrolbar
	if ( ! wppaHasControlbar ) {
		jQuery( '#wppa-pctl-div-'+mocc ).hide();
	}
}

// Format a slide
function wppaFormatSlide( mocc ) {

	// Slide present in this occ?
	var imgid    = 'theimg'+_wppaFg[mocc]+'-'+mocc;
	var elm      = document.getElementById( imgid );
	if ( ! elm ) {
		return;	// No slide present
	}

	// vars we have
	var slideid  = 'theslide'+_wppaFg[mocc]+'-'+mocc;
	var frameid  = 'slide_frame-'+mocc;
	var contw    = wppaGetContainerWidth(mocc); //jQuery('#wppa-container-'+mocc).width();
		wppaColWidth[mocc] = contw;
	var audios 	 = jQuery( '.wppa-audio-'+mocc );
	var natwidth  = elm.naturalWidth;
		if ( typeof( natwidth )=='undefined' ) natwidth = parseInt( elm.style.maxWidth );
	var natheight = elm.naturalHeight;
		if ( typeof( natheight )=='undefined' ) natheight = parseInt( elm.style.maxHeight );
	var aspect    = wppaAspectRatio[mocc];
	var fullsize  = wppaFullSize[mocc];
	var delta     = wppaFullFrameDelta[mocc];

	// Switches we have
	var ponly   = wppaPortraitOnly[mocc];
	var valign  = wppaFullValign[mocc]; if ( typeof( valign )=='undefined' ) valign = 'none';
	var halign  = wppaFullHalign[mocc]; if ( typeof( halign )=='undefined' ) halign = 'none';
	var stretch = wppaStretch;

	// vars to be calculated:
	var imgw, imgh;		// image width and height
	var margl, margt;	// image margins
	var slidew, slideh;	// slide width and height
	var framew, frameh;	// frame

	// Calculate
	if ( ponly ) {
		imgw = contw - delta;
		imgh = parseInt( imgw * natheight / natwidth );
		margl = 0;
		margt = 0;
		slidew = contw;
		slideh = imgh + delta;
		framew = contw;
		frameh = slideh;
		// Size
		jQuery( '#'+frameid ).css( {width:framew, height:frameh});
		jQuery( '#'+slideid ).css( {width:slidew, height:slideh});
		jQuery( '#'+imgid ).css( {width:imgw, height:imgh});
	}
	else {

		// not 'ponly' so we have a fixed display ratio. First assume the container is the hor limit
		framew = contw;

		// If the fullsize ( Slideshow -> I -> Item 1 ) is smaller than the container width The frame is scaled down to fit the fullsize
		if ( fullsize < contw ) {
			framew = fullsize;				// The fullsize appears to be the hor limit
		}

		// Now calc the height using the width and the ratio
		frameh = parseInt( framew * aspect );	// Always obey the occurences aspect ratio
		slidew = framew;
		slideh = frameh;
		if ( stretch || natwidth >= ( framew-delta ) || natheight >= ( frameh-delta ) ) {	// Image big enough
			if ( ( ( natheight+delta ) / ( natwidth+delta ) ) > aspect ) {	// vertical limit
				imgh = frameh - delta;
				imgw = parseInt( imgh * natwidth / natheight );
			}
			else {	// horizontal limit
				imgw = framew - delta;
				imgh = parseInt( imgw * natheight / natwidth );
			}
		}
		else {															// Image too small
			imgw = natwidth;
			imgh = natheight;
		}

		// Align vertical
		if ( valign != 'default' && valign != 'none' ) {
			switch ( valign ) {
				case 'top':
					margt = 0;
					break;
				case 'center':
					margt = parseInt( ( frameh - ( imgh+delta ) ) / 2 );
					break;
				case 'bottom':
					margt = frameh - ( imgh+delta );
					break;
				case 'fit':
					margt = 0;
					frameh = imgh + delta;
					slideh = imgh + delta;
					break;
				default:
				//	alert( 'Unknown v align:'+valign+' occ='+mocc );
			}
			jQuery( '#'+imgid ).css( {marginTop:margt, marginBottom:0});
		}

		// Size ( after v align because 'fit' changes the frameh and slidh )
		jQuery( '#'+frameid ).css( {width:framew, height:frameh});
		jQuery( '#'+slideid ).css( {width:slidew, height:slideh});
		jQuery( '#'+imgid ).css( {width:imgw, height:imgh});

		// Align horizontal
		if ( valign != 'default' && valign != 'none' && halign != 'none' && halign != 'default' ) {
			switch ( halign ) {
				case 'left':
					jQuery( '#'+imgid ).css( {marginLeft:0, marginRight:'auto'});
					break;
				case 'center':
					jQuery( '#'+imgid ).css( {marginLeft:'auto', marginRight:'auto'});
					break;
				case 'right':
					jQuery( '#'+imgid ).css( {marginLeft:'auto', marginRight:0});
					break;
				default:
					jQuery( '#'+imgid ).css( {marginLeft:'auto', marginRight:'auto'});
			}
		}

		// Size audio
		var h = jQuery( audios ).height();
		var l = ( framew - imgw ) / 2;
		if ( h && h > 0 ) {
			wppaAudioHeight = h;
			jQuery( audios ).css({height:wppaAudioHeight,width:imgw,left:l});
		}
	}

	var hrUrl = _wppaHiresUrl[mocc][_wppaCurIdx[mocc]] || '';
	var isPdf = hrUrl.substr( hrUrl.length - 4, hrUrl.length ) == '.pdf';
	if ( isPdf ) {
		jQuery( '#'+slideid ).css( {width:'100%', height:'100%'});
	}

	// Size Big Browse Buttons.
	var bbbwidth 	= parseInt( framew / 3 );
//	var leftmarg 	= bbbwidth*2;
//	var bbbheight;

	// Make sure they do not cover the audio controlbar.
//	if ( audios.length > 0 ) {
//		bbbheight 	= frameh - wppaAudioHeight - wppaSlideBorderWidth - margt;
//	}
//	else {
//		bbbheight 	= frameh;
//	}

	jQuery( '#bbb-'+mocc+'-l' ).css( {width:bbbwidth});
	jQuery( '#bbb-'+mocc+'-r' ).css( {width:bbbwidth});
}

// Make name
function wppaMakeNameHtml( mocc ) {

	if ( _wppaCurIdx[mocc] < 0 ) return '';

	if ( _wppaFullNames[mocc][_wppaCurIdx[mocc]] == 'void' ) {
		jQuery('#namebox-'+mocc).hide();
		return '';
	}
	else {
		jQuery('#namebox-'+mocc).show();
	}

	return wppaRepairBrTags( _wppaFullNames[mocc][_wppaCurIdx[mocc]] );
}

function wppaMakeTheSlideHtml( mocc, bgfg, idx ) {

	var imgVideo = ( _wppaIsVideo[mocc][idx] ) ? 'video' : 'img';
	var theHtml;
	var url;
	var theTitle = 'title';
	if ( wppaLightBox[mocc] == 'wppa') theTitle = 'data-lbtitle';
	var mmEvents = wppaLightBox[mocc] == '' ? ' onpause="wppaVideoPlaying['+mocc+'] = false;" onplay="wppaVideoPlaying['+mocc+'] = true;"' : '';
	var hrUrl;
	var isPdf;
	var isPanorama = ( _wppaPanoramaHtml[mocc][idx].length > 0 );
	wppaHasControlbar = isPanorama;

	// Stop possible zooom/pano image
	jQuery("body").trigger("quitimage",[mocc]);

	// No links on panos, so quickly done
	if ( isPanorama ) {
		theHtml = _wppaPanoramaHtml[mocc][idx].replace( /title=""/g, '' );

		jQuery( "#theslide"+(1-bgfg)+"-"+mocc ).html( '' );
		jQuery( "#theslide"+bgfg+"-"+mocc ).html( theHtml );

		var h = wppaGetContainerWidth(mocc) * wppaAspectRatio[mocc];
//		if ( parseInt(_wppaPanControlHeight[mocc][idx] ) > 0 ) {
//			h += parseInt(_wppaPanControlHeight[mocc][idx]) + 5;
//		}
		jQuery( "#slide_frame-"+mocc ).css({height:h});
// console.log('sl cw='+wppaGetContainerWidth(mocc)+', ratio='+wppaAspectRatio[mocc]);
// console.log('sl h = '+h);
		return;
	}

	hrUrl = _wppaHiresUrl[mocc][idx] || '';
	isPdf = hrUrl.substr( hrUrl.length - 4, hrUrl.length ) == '.pdf';

	// Link url explicitly given ?
	if ( _wppaLinkUrl[mocc][idx] != '' ) {
		if ( wppaSlideToFullpopup ) {
			theHtml = 	'<a onclick="wppaStopAudio();wppaStopShow('+mocc+');'+_wppaLinkUrl[mocc][idx]+'" target="'+_wppaLinkTarget[mocc][idx]+'" title="'+_wppaLinkTitle[mocc][idx]+'">'+
							( isPdf ? '<iframe '+mmEvents+' src="'+hrUrl+'" title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" style="width:100%;height:100%;" ></iframe>' :
							'<'+imgVideo+mmEvents+' title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" '+_wppaSlides[mocc][idx] ) +
						'</a>';
		}
		else {
			theHtml = 	'<a onclick="_bumpClickCount(\''+_wppaId[mocc][idx]+'\');wppaStopAudio();wppaStopShow('+mocc+');window.open(\''+_wppaLinkUrl[mocc][idx]+'\', \''+_wppaLinkTarget[mocc][idx]+'\');" title="'+_wppaLinkTitle[mocc][idx]+'">'+
							( isPdf ? '<iframe '+mmEvents+' src="'+hrUrl+'" title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" style="width:100%;height:100%;" ></iframe>' :
							'<'+imgVideo+mmEvents+' title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" '+_wppaSlides[mocc][idx] ) +
						'</a>';
		}
	}

	// No url, maybe lightbox?
	else {

		// No Lightbox
		if ( wppaLightBox[mocc] == '' ) {

			if ( isPdf ) {
				theHtml = '<iframe '+mmEvents+' src="'+hrUrl+'" title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" style="width:100%;height:100%;" ></iframe>';
			}
			else {
				theHtml = '<'+imgVideo+mmEvents+' title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" '+_wppaSlides[mocc][idx];
			}
		}

		// Lightbox
		else {
			var html = '';
			var i = 0;
			var set = wppaLightboxSingle[mocc] ? '' : '[slide-'+mocc+'-'+bgfg+']';

			// Before current slide	// This does NOT work on lightbox 3 !
			while ( i<idx ) {

				// Hi res?
				if ( wppaOvlHires || ( wppaLightBox[mocc] != 'wppa' ) ) {
					url = _wppaHiresUrl[mocc][i];
				}
				else {
					url = wppaMakeFullsizeUrl( _wppaUrl[mocc][i] );
				}

				hrUrl 	= _wppaHiresUrl[mocc][i] || '';
				isPdf 	= hrUrl.substr( hrUrl.length - 4, hrUrl.length ) == '.pdf';

				html += '<a href="'+url+'"' +
						( ( _wppaIsVideo[mocc][i] ) ?
							' data-videonatwidth="'+_wppaVideoNatWidth[mocc][i]+'"' +
							' data-videonatheight="'+_wppaVideoNatHeight[mocc][i]+'"' +
							' data-videohtml="'+encodeURI( _wppaVideoHtml[mocc][i] )+'"' : '' ) +
						( isPdf ? ' data-pdfhtml="src=\''+hrUrl+'\'"' : '' ) +
						( ( _wppaAudioHtml[mocc][i] != '' ) ?
							' data-audiohtml="'+encodeURI( _wppaAudioHtml[mocc][i] )+'"' : '' ) +
						' '+theTitle+'="'+_wppaLbTitle[mocc][i]+'"' +
						' '+wppaRel+'="'+wppaLightBox[mocc]+set+'"></a>';
				i++;
			}

			// Current slide
			// Hi res?
			if ( wppaOvlHires || ( wppaLightBox[mocc] != 'wppa' ) ) {
				url = _wppaHiresUrl[mocc][idx];
			}
			else {
				url = wppaMakeFullsizeUrl( _wppaUrl[mocc][idx] );
			}

			hrUrl 	= _wppaHiresUrl[mocc][idx];
			isPdf 	= hrUrl.substr( hrUrl.length - 4, hrUrl.length ) == '.pdf';

			var onClick = ' onclick="wppaStopAudio();wppaStopShow('+mocc+');"';

			html += '<a href="'+url+'"' +
					onClick +
					' style="cursor:pointer;"' +
					' target="'+_wppaLinkTarget[mocc][idx]+'"' +
					( ( _wppaIsVideo[mocc][i] ) ?
						' data-videonatwidth="'+_wppaVideoNatWidth[mocc][idx]+'"' +
						' data-videonatheight="'+_wppaVideoNatHeight[mocc][idx]+'"' +
						' data-videohtml="'+encodeURI( _wppaVideoHtml[mocc][idx] )+'"' : '' ) +
					( isPdf ? ' data-pdfhtml="src=\''+hrUrl+'\'"' : '' ) +
					( ( _wppaAudioHtml[mocc][i] != '' ) ?
						' data-audiohtml="'+encodeURI( _wppaAudioHtml[mocc][idx] )+'"' : '' ) +
					' '+theTitle+'="'+_wppaLbTitle[mocc][idx]+'"' +
					' '+wppaRel+'="'+wppaLightBox[mocc]+set+'"' + '>' +
						( isPdf ?
							'<iframe '+mmEvents+' src="'+hrUrl+'" title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" style="width:100%;height:100%;" ></iframe>' :
							'<'+imgVideo+mmEvents+' title="'+_wppaLinkTitle[mocc][idx]+'" id="theimg'+bgfg+'-'+mocc+'" '+_wppaSlides[mocc][idx]
						)+
					'</a>';

			// After current slide // This does NOT work on lightbox 3 !
			i = idx + 1;
			while ( i<_wppaUrl[mocc].length ) {

				// H ires?
				if ( wppaOvlHires || ( wppaLightBox[mocc] != 'wppa' ) ) {
					url = _wppaHiresUrl[mocc][i];
				}
				else {
					url = wppaMakeFullsizeUrl( _wppaUrl[mocc][i] );
				}

				hrUrl 	= _wppaHiresUrl[mocc][i];
				isPdf 	= hrUrl.substr( hrUrl.length - 4, hrUrl.length ) == '.pdf';

				html += '<a href="'+url+'"' +
						( ( _wppaIsVideo[mocc][i] ) ?
							' data-videonatwidth="'+_wppaVideoNatWidth[mocc][i]+'"' +
							' data-videonatheight="'+_wppaVideoNatHeight[mocc][i]+'"' +
							' data-videohtml="'+encodeURI( _wppaVideoHtml[mocc][i] )+'"' : '' ) +
							( isPdf ? ' data-pdfhtml="src=\''+hrUrl+'\'"' : '' ) +
						( ( _wppaAudioHtml[mocc][i] != '' ) ?
							' data-audiohtml="'+encodeURI( _wppaAudioHtml[mocc][i] )+'"' : '' ) +
						' '+theTitle+'="'+_wppaLbTitle[mocc][i]+'"' +
						' '+wppaRel+'="'+wppaLightBox[mocc]+set+'"' + '></a>';
				i++;
			}

			theHtml = html;
		}
	}

	// Audio?
	if ( _wppaAudioHtml[mocc][idx] != '' ) {
		theHtml += 	'<audio' +
						' controls' +
						' id="wppa-audio-'+_wppaId[mocc][idx]+'-'+mocc+'"' +
						' class="wppa-audio-'+mocc+' wppa-audio-'+_wppaId[mocc][idx]+'-'+mocc+'"' +
						' data-from="wppa"' +
						( wppaSlideAudioStart && wppaLightBox[mocc] == '' ? ' autoplay' : '' ) +
						' onplay="wppaAudioPlaying['+mocc+'] = true;"' +
						' onpause="wppaAudioPlaying['+mocc+'] = false"' +
						' style="' +
							'position:relative;' +
							'top:-'+( wppaAudioHeight + wppaSlideBorderWidth )+'px;' +
							'z-index:10;' +
							'width:'+_wppaVideoNatWidth[mocc][idx]+'px;' +

							'padding:0;' +
							'box-sizing:border-box;' +
							'"' +
						' >' +
							_wppaAudioHtml[mocc][idx] +
					'</audio>';
	}

	// Remove empty titles for browsers that display empty tooltip boxes
	theHtml = theHtml.replace( /title=""/g, '' );

	jQuery( "#theslide"+bgfg+"-"+mocc ).html( theHtml );	// nieuw

}

// Adjust all filmstrips
function wppaAdjustAllFilmstrips( easing ) {

	jQuery(".wppa-filmstrip").each(function(){
		var divId = jQuery(this).attr('id');
		var mocc = divId.substr(15);
		_wppaAdjustFilmstrip(mocc, easing);
	});
}

// Adjust the filmstrip
var wppaLastAnimFilmLoc = [];
function _wppaAdjustFilmstrip( mocc, easing ) {

	// Maybe filmonly continuous, in this case easing is always linear
	if ( easing != 'linear' ) easing = wppaEasingSlide;

	if ( ! document.getElementById( 'wppa-filmstrip-'+mocc ) ) return;	// No filmstrip this mocc
	if ( ! _wppaSlides[mocc] ) return;

	var isFilmOnly = ! document.getElementById( 'slide_frame-'+mocc );
	if ( ! wppaLastAnimFilmLoc[mocc] ) wppaLastAnimFilmLoc[mocc] = 0;

	// If not visible filmonly, quit
	if ( isFilmOnly ) {
		var elm = jQuery('#wppa-filmstrip-'+mocc);
		if ( ! wppaIsElementInViewport( elm ) ) return;
	}

	// Remove class from active thumb
	if ( ! isFilmOnly ) {
		jQuery( '.wppa-film-'+mocc ).find('img').removeClass( 'wppa-filmthumb-active' );
		jQuery( '.wppa-film-'+mocc ).find('canvas').removeClass( 'wppa-filmthumb-active' );
	}

	if ( ! _wppaFilmNoMove[mocc] || ! wppaFilmInit[mocc] ) {
		wppaFilmWindowLen = wppaGetContainerWidth(mocc) - wppaFilmStripAreaDelta[mocc];
		jQuery( '#filmwindow-'+mocc ).width(wppaFilmWindowLen);

		var loc = wppaFilmWindowLen / 2 - ( _wppaCurIdx[mocc] + 0.5 + wppaPreambule[mocc] ) * wppaThumbnailPitch[mocc] - wppaFilmStripMargin[mocc];

		if ( wppaFilmShowGlue ) {
			loc -= ( wppaFilmStripMargin[mocc] * 2 + 2 );	// Glue
		}
		var loc_1 	= loc + wppaThumbnailPitch[mocc];		// loc -1
		var loc_n1 	= loc - wppaThumbnailPitch[mocc]; 	// loc +1
		var speed 	= wppaAnimationSpeed;
		if ( ! wppaFilmInit[mocc] ) {
			speed = 1;
		}

		// If going from last to first, jump to -1 and animate to 0
		if ( _wppaCurIdx[mocc] == 0 && _wppaLastIdx[mocc] == ( _wppaSlides[mocc].length -1 ) ) {
			jQuery( '#wppa-filmstrip-'+mocc ).css( {marginLeft: loc_1+'px'} );
			if ( wppaLastAnimFilmLoc[mocc] != loc ) {
				wppaAnimate( '#wppa-filmstrip-'+mocc, {marginLeft:loc}, speed, easing );
				wppaLastAnimFilmLoc[mocc] = loc;
			}
		}

		// If going from first to last, jump to n+1 and animate to n
		else if ( _wppaLastIdx[mocc] == 0 && _wppaCurIdx[mocc] == ( _wppaSlides[mocc].length -1 ) ) {
			jQuery( '#wppa-filmstrip-'+mocc ).css( {marginLeft:loc_n1} );
			if ( wppaLastAnimFilmLoc[mocc] != loc ) {
				wppaAnimate( '#wppa-filmstrip-'+mocc, {marginLeft:loc}, speed, easing );
				wppaLastAnimFilmLoc[mocc] = loc;
			}
		}

		// The normal situation, jump to n-1 if required and animate to n
		else {
			var current = parseInt( jQuery( '#wppa-filmstrip-'+mocc ).css( 'margin-left' ) );
			var target = parseInt(loc);
			var pitch = wppaThumbnailPitch[mocc];

			if ( _wppaSSRuns[mocc] ) {
				var r = 0;
				if ( target < ( current - pitch * 2 ) ) r = 1;
				if ( target > current ) r = 2;
				if ( r ) {
					jQuery( '#wppa-filmstrip-'+mocc ).css( {marginLeft: ( target + pitch ) } );
				}
			}
			else {
				if ( target < ( current - pitch * 1.5 ) || target > ( current + pitch * 1.5 ) ) {
					jQuery( '#wppa-filmstrip-'+mocc ).css( {marginLeft:target} );
				}
			}

			// Animate to n
			wppaAnimate( '#wppa-filmstrip-'+mocc, {marginLeft:loc}, speed, easing );
			wppaLastAnimFilmLoc[mocc] = loc;
			wppaFilmInit[mocc] = true;
		}

		_wppaLastIdx[mocc] = _wppaCurIdx[mocc];
	}

	else {
		_wppaFilmNoMove[mocc] = false; // reset
	}

	// Make lazy filmthumbs visible
	wppaMakeLazyVisible('filmstripmocc='+mocc);

	// Fix titles
	if ( ! isFilmOnly && _wppaCurIdx[mocc] != -1 ) {
		var from = _wppaCurIdx[mocc] - 10; if ( from < 0 ) from = 0;
		var to = _wppaCurIdx[mocc] + 10; if ( to > _wppaSlides[mocc].length ) to = _wppaSlides[mocc].length;
		var index = 0;
		while ( index < _wppaSlides[mocc].length ) {

			var html = jQuery( '#film_wppatnf_'+_wppaId[mocc][index]+'_'+mocc ).html();

			if ( html ) {

				// Fit title in
				if ( jQuery( '#wppa-film-'+index+'-'+mocc ).attr( 'data-title' ) != '' ) {
					jQuery( '#wppa-film-'+index+'-'+mocc ).attr( 'title', jQuery( '#wppa-film-'+index+'-'+mocc ).attr( 'data-title' ) );
					jQuery( '#wppa-pre-'+index+'-'+mocc ).attr( 'title', jQuery( '#wppa-film-'+index+'-'+mocc ).attr( 'data-title' ) );
				}
				else if ( wppaFilmThumbTitle != '' && _wppaCurIdx[mocc] == index ) {
					jQuery( '#wppa-film-'+index+'-'+mocc ).attr( 'title', wppaFilmThumbTitle );
					jQuery( '#wppa-pre-'+index+'-'+mocc ).attr( 'title', wppaFilmThumbTitle );
				}
				else {
					jQuery( '#wppa-film-'+index+'-'+mocc ).attr( 'title', wppaClickToView + ' ' + _wppaFilmThumbTitles[mocc][index] );
					jQuery( '#wppa-pre-'+index+'-'+mocc ).attr( 'title', wppaClickToView + ' ' + _wppaFilmThumbTitles[mocc][index] );
				}
			}
			index++;
		}
	}

	// Apply class to active filmthumb
	if ( ! isFilmOnly ) {
		jQuery( '#film_wppatnf_'+_wppaId[mocc][_wppaCurIdx[mocc]]+'_'+mocc ).find('img').addClass( 'wppa-filmthumb-active' );
		jQuery( '#film_wppatnf_'+_wppaId[mocc][_wppaCurIdx[mocc]]+'_'+mocc ).find('canvas').addClass( 'wppa-filmthumb-active' );
	}
}

function _wppaNext( mocc ) {

	// Check for end of non wrapped show
	if ( ! wppaSlideWrap[mocc] && _wppaCurIdx[mocc] == ( _wppaSlides[mocc].length -1 ) ) return;

	// Find next index
	_wppaNxtIdx[mocc] = _wppaCurIdx[mocc] + 1;

	// End of page? go to start of next page
	if ( _wppaNxtIdx[mocc] == _wppaSlides[mocc].length ) {

		var npb = jQuery( '#wppa-next-pagelink-' + mocc );
		var fpb = jQuery( '#wppa-first-pagelink-' + mocc );

		// If there is a non-hidden next page button, trigger next page after timeout
		if ( npb.length > 0 && jQuery( npb ).css('visibility') != 'hidden' ) {
			wppaSlideInitRunning[mocc] = ( _wppaSSRuns[mocc] ? 'start' : 'stopnext' );
			jQuery( '#wppa-next-pagelink-' + mocc ).trigger( 'click' );
			return;
		}

		// If there is a (hidden) first page button, trigger it after timeout
		else if ( fpb.length > 0 ) {
			wppaSlideInitRunning[mocc] = ( _wppaSSRuns[mocc] ? 'start' : 'stopnext' );
			jQuery( fpb ).trigger( 'click' );
			return;
		}

		// Same page
		else {
			_wppaNxtIdx[mocc] = 0;
			_wppaNextSlide( mocc, 0 );
		}
	}

	// The normal case
	else {
		_wppaNextSlide( mocc, 0 );
	}
}

function _wppaNextN( mocc, n ) {

	// Check for end of non wrapped show
	if ( ! wppaSlideWrap[mocc] && _wppaCurIdx[mocc] >= ( _wppaSlides[mocc].length - n ) ) return;
	// Find next index
	_wppaNxtIdx[mocc] = _wppaCurIdx[mocc] + n;
	while ( _wppaNxtIdx[mocc] >= _wppaSlides[mocc].length ) _wppaNxtIdx[mocc] -= _wppaSlides[mocc].length;
	// And go!
	_wppaNextSlide( mocc, 0 );
}

function _wppaNextOnCallback( mocc ) {

	// Check for end of non wrapped show
	if ( ! wppaSlideWrap[mocc] && _wppaCurIdx[mocc] == ( _wppaSlides[mocc].length -1 ) ) return;
	// Check for skip rated after rating
	if ( _wppaSkipRated[mocc] ) {
		var now = _wppaCurIdx[mocc];
		var idx = now + 1;
		if ( idx == _wppaSlides[mocc].length ) idx = 0;	// wrap?
		var next = idx; // assume simple next
		if ( _wppaMyr[mocc][next] != 0 ) {		// Already rated, skip
			idx++;	// try next
			if ( idx == _wppaSlides[mocc].length ) idx = 0;	// wrap?
			while ( idx != next && _wppaMyr[mocc][idx] != 0 ) {	// still rated, skip
				idx ++;	// try next
				if ( idx == _wppaSlides[mocc].length ) idx = 0;	// wrap?
			}	// either idx == next or not rated
			next = idx;
		}
		_wppaNxtIdx[mocc] = next;
	}
	else {	// Normal situation
		_wppaNxtIdx[mocc] = _wppaCurIdx[mocc] + 1;
		if ( _wppaNxtIdx[mocc] == _wppaSlides[mocc].length ) _wppaNxtIdx[mocc] = 0;
	}
	_wppaNextSlide( mocc, 0 );
}

function _wppaPrev( mocc ) {

	// Check for begin of non wrapped show
	if ( ! wppaSlideWrap[mocc] && _wppaCurIdx[mocc] == 0 ) return;

	// Find previous index
	_wppaNxtIdx[mocc] = _wppaCurIdx[mocc] - 1;

	// If beyond begin, wrap to last item prev page
	if ( _wppaNxtIdx[mocc] < 0 ) {

//		wppaSavedSlideshowTimeout[mocc] = _wppaTimeOut[mocc];

		// if prev page link exists go to last item on prev page-last-item
		if ( jQuery( '#wppa-prev-page-last-item-' + mocc ).length ) {
			wppaSlideInitRunning[mocc] = ( _wppaSSRuns[mocc] ? 'start' : 'stopprev' );
			jQuery( '#wppa-prev-page-last-item-' + mocc ).trigger( 'click' );
			return;
		}

		// Same page
		else {
			_wppaNxtIdx[mocc] += _wppaSlides[mocc].length;
			_wppaNextSlide( mocc, 0 );
		}
	}

	// The normal case
	else {
		_wppaNextSlide( mocc, 0 );
	}
}

function _wppaPrevN( mocc, n ) {

	// Check for begin of non wrapped show
	if ( ! wppaSlideWrap[mocc] && _wppaCurIdx[mocc] < n ) return;
	// Find previous index
	_wppaNxtIdx[mocc] = _wppaCurIdx[mocc] - n;
	while ( _wppaNxtIdx[mocc] < 0 ) _wppaNxtIdx[mocc] += _wppaSlides[mocc].length;
	// And go!
	_wppaNextSlide( mocc, 0 );
}

function _wppaGoto( mocc, idx ) {

	_wppaToTheSame = ( _wppaNxtIdx[mocc] == idx );
	_wppaNxtIdx[mocc] = idx;
	_wppaNextSlide( mocc, 0 );
}

function _wppaGotoRunning( mocc, idx ) {

	//wait until not bussy
	if ( _wppaIsBusy[mocc] ) {
		setTimeout( '_wppaGotoRunning( '+mocc+',' + idx + ' )', 10 );	// Try again after 10 ms
		return;
	}

	_wppaSSRuns[mocc] = false; // we don't want timed loop to occur during our work

	_wppaToTheSame = ( _wppaNxtIdx[mocc] == idx );
	_wppaNxtIdx[mocc] = idx;
__wppaOverruleRun = true;
	_wppaNextSlide( mocc, "manual" ); // enqueue new transition

	_wppaGotoContinue( mocc );
}

function _wppaGotoContinue( mocc ) {
	if ( _wppaIsBusy[mocc] ) {
		setTimeout( '_wppaGotoContinue( '+mocc+' )', 10 );	// Try again after 10 ms
		return;
	}
	setTimeout( '_wppaNextSlide( '+mocc+', "reset" )', wppaGetSlideshowTimeout( mocc, '_wppaGotoContinue' ) + 10 ); //restart slideshow after new timeout
}

function _wppaStart( mocc, idx ) {

	// If stop in progress, try later
//	if ( _wppaStopping[mocc] ) {
//		_wppaStopping[mocc]--;
//		if ( ! wppaSSRuns[1] ) {
//			setTimeout( function(){_wppaStart( mocc, idx )}, (wppaAnimationSpeed+wppaGetSlideshowTimeout( mocc, '_wppaStart' ))/10 );
//			return;
//		}
//	}
//	_wppaStopping[mocc] = 0;

	// If already running, ignore
	if ( _wppaSSRuns[mocc] ) {
		return;
	}

	if ( wppaSlideshowNavigationType == 'icons' ) {
		_wppaStartIcons( mocc, idx );
	}
	else {
		_wppaStartText( mocc, idx );
	}
	jQuery( '#wppa-startstop-icon-' + mocc ).html( wppaSvgHtml( 'Pause-Button', wppaIconSize( mocc, '48px', true ), false, true, '0', '10', '50', '50' ) );

	// If failed, try again later
	if ( idx == -1 )
	setTimeout( function(){_wppaStart( mocc, idx )}, 200 );
}

var wppaVeryFirst = true;
function _wppaStartIcons( mocc, idx ) {


	// A filmonly does not start when it is the very first occ, so we set wppaVeryFirst to false in case its a filmonly
	if ( jQuery('#filmwindow-'+mocc).length > 0 && jQuery('#slide_frame-'+mocc).length == 0 ) wppaVeryFirst = false;

	if ( idx == -2 ) {	// Init at first without my rating
		var i = 0;
		idx = 0;
		_wppaSkipRated[mocc] = true;
		if ( _wppaMyr[mocc][i] != 0 ) {
			while ( i < _wppaSlides[mocc].length ) {
				if ( idx == 0 && _wppaMyr[mocc][i] == 0 ) idx = i;
				i++;
			}
		}
	}

	var iconsize = wppaIconSize( mocc, '1.5em', false );
	if ( idx > -1 ) {	// Init still at index idx
		jQuery( '#startstop-'+mocc ).html( wppaSvgHtml( 'Play-Button', iconsize, false, true, '0', '10', '20', '50' ) );
		jQuery( '#speed0-'+mocc ).hide();
		jQuery( '#speed1-'+mocc ).hide();
		_wppaNxtIdx[mocc] = idx;
		_wppaCurIdx[mocc] = idx;
		_wppaNextSlide( mocc, 0 );
		_wppaShowMetaData( mocc, 'show' );
	}
	else {	// idx == -1, start from where you are
		if ( ! wppaVeryFirst) _wppaSSRuns[mocc] = true;
		_wppaNextSlide( mocc, 0 );
		if ( wppaVeryFirst ) _wppaSSRuns[mocc] = true;
		jQuery( '#startstop-'+mocc ).html( wppaSvgHtml( 'Pause-Button', iconsize, false, true, '0', '10', '20', '50' ) );
		jQuery( '#speed0-'+mocc ).show();
		jQuery( '#speed1-'+mocc ).show();
		_wppaShowMetaData( mocc, 'hide' );
		if ( jQuery( '#bc-pname-modal-'+mocc ) ) {
			jQuery( '#bc-pname-modal-'+mocc ).html( __( 'Slideshow', 'wp-photo-album-plus' ) );
		}
		else {
			jQuery( '#bc-pname-'+mocc ).html( __( 'Slideshow', 'wp-photo-album-plus' ) );
		}
	}
	wppaVeryFirst = false;

	// Both cases:
	_wppaSetRatingDisplay( mocc );
}

function _wppaStartText( mocc, idx ) {



	// A filmonly does not start when it is the very first occ, so we set wppaVeryFirst to false in case its a filmonly
	if ( jQuery('#filmwindow-'+mocc).length > 0 && jQuery('#slide_frame-'+mocc).length == 0 ) wppaVeryFirst = false;

	if ( idx == -2 ) {	// Init at first without my rating
		var i = 0;
		idx = 0;
		_wppaSkipRated[mocc] = true;
		if ( _wppaMyr[mocc][i] != 0 ) {
			while ( i < _wppaSlides[mocc].length ) {
				if ( idx == 0 && _wppaMyr[mocc][i] == 0 ) idx = i;
				i++;
			}
		}
	}

	if ( idx > -1 ) {	// Init still at index idx
		jQuery( '#startstop-'+mocc ).html( __( 'Start', 'wp-photo-album-plus' )+' '+__( 'Slideshow', 'wp-photo-album-plus' ) );
		jQuery( '#speed0-'+mocc ).css( 'display', 'none' );
		jQuery( '#speed1-'+mocc ).css( 'display', 'none' );
		_wppaNxtIdx[mocc] = idx;
		_wppaCurIdx[mocc] = idx;
		_wppaNextSlide( mocc, 0 );
		_wppaShowMetaData( mocc, 'show' );
	}
	else {	// idx == -1, start from where you are
		if ( ! wppaVeryFirst) _wppaSSRuns[mocc] = true;
		_wppaNextSlide( mocc, 0 );
		if ( wppaVeryFirst ) _wppaSSRuns[mocc] = true;
		jQuery( '#startstop-'+mocc ).html( __( 'Stop', 'wp-photo-album-plus' ) );
		jQuery( '#speed0-'+mocc ).css( 'display', 'inline' );
		jQuery( '#speed1-'+mocc ).css( 'display', 'inline' );
		_wppaShowMetaData( mocc, 'hide' );
		if ( jQuery( '#bc-pname-modal-'+mocc ) ) {
			jQuery( '#bc-pname-modal-'+mocc ).html( __( 'Slideshow', 'wp-photo-album-plus' ) );
		}
		else {
			jQuery( '#bc-pname-'+mocc ).html( __( 'Slideshow', 'wp-photo-album-plus' ) );
		}
	}
	wppaVeryFirst = false;

	// Both cases:
	_wppaSetRatingDisplay( mocc );
}

function _wppaStop( mocc ) {
//	_wppaStopping[mocc] = 1;//12;
	if ( wppaSlideshowNavigationType == 'icons' ) {
		_wppaStopIcons( mocc );
	}
	else {
		_wppaStopText( mocc );
	}
	jQuery( '#wppa-startstop-icon-' + mocc ).html( wppaSvgHtml( 'Play-Button', wppaIconSize( mocc, '48px', true ), false, true, '0', '10', '50', '50' ) );
}

function _wppaStopIcons( mocc ) {

    _wppaSSRuns[mocc] = false;
	jQuery( '#startstop-'+mocc ).html( wppaSvgHtml( 'Play-Button', wppaIconSize( mocc, '1.5em', false ), false, true ) );
	jQuery( '#speed0-'+mocc ).hide();
	jQuery( '#speed1-'+mocc ).hide();
	_wppaShowMetaData( mocc, 'show' );
	if ( jQuery( '#bc-pname-modal-'+mocc ) ) {
		jQuery( '#bc-pname-modal-'+mocc ).html( _wppaNames[mocc][_wppaCurIdx[mocc]] );
	}
	else {
		jQuery( '#bc-pname-'+mocc ).html( _wppaNames[mocc][_wppaCurIdx[mocc]] );
	}
}
function _wppaStopText( mocc ) {


    _wppaSSRuns[mocc] = false;
    jQuery( '#startstop-'+mocc ).html( __( 'Start', 'wp-photo-album-plus' )+' '+__( 'Slideshow', 'wp-photo-album-plus' ) );
	jQuery( '#speed0-'+mocc ).css( 'display', 'none' );
	jQuery( '#speed1-'+mocc ).css( 'display', 'none' );
	_wppaShowMetaData( mocc, 'show' );
	if ( jQuery( '#bc-pname-modal-'+mocc ) ) {
		jQuery( '#bc-pname-modal-'+mocc ).html( _wppaNames[mocc][_wppaCurIdx[mocc]] );
	}
	else {
		jQuery( '#bc-pname-'+mocc ).html( _wppaNames[mocc][_wppaCurIdx[mocc]] );
	}
}

function _wppaSpeed( mocc, faster ) {

	if ( _wppaTimeOut[mocc] == 'random' ) {
		return;
	}
    if ( faster ) {
        if ( _wppaTimeOut[mocc] > 500 ) _wppaTimeOut[mocc] /= 1.5;
    }
    else {
        if ( _wppaTimeOut[mocc] < 60000 ) _wppaTimeOut[mocc] *= 1.5;
    }
	wppaSavedSlideshowTimeout[mocc] = _wppaTimeOut[mocc];
}

function _wppaUnloadSpinner( mocc ) {

	jQuery( '#wppa-slide-spin-' + mocc ).hide();
	setTimeout( function() { jQuery( '#wppa-slide-spin-' + mocc ).stop().fadeOut(); }, 1000 );

}

function _wppaSetRatingDisplay( mocc ) {

	// Rating bar present?
	if ( ! document.getElementById( 'wppa-rating-'+mocc ) ) {
		return;
	}

	var idx, avg, tmp, cnt, dsc, myr, dsctxt;

	avg = _wppaAvg[mocc][_wppaCurIdx[mocc]];
	if ( typeof( avg ) == 'undefined' ) return;

	// Likes system
	if ( wppaRatingDisplayType == 'likes' ) {

		myr = _wppaMyr[mocc][_wppaCurIdx[mocc]];
		if ( myr == 'void' ) {
			jQuery( '#wppa-dislike-imgdiv-'+mocc ).hide();
			jQuery( '#wppa-like-imgdiv-'+mocc ).hide();
		}
		else {
			jQuery( '#wppa-dislike-imgdiv-'+mocc ).show();
			jQuery( '#wppa-like-imgdiv-'+mocc ).show();
		}

		var likeText = avg.split( "|" );

		jQuery( '#wppa-like-'+mocc ).attr( 'title', likeText[0] );
		jQuery( '#wppa-liketext-'+mocc ).html( likeText[1] );
		if ( _wppaMyr[mocc][_wppaCurIdx[mocc]] == '1' ) {
			jQuery( '#wppa-like-'+mocc ).attr( 'src', wppaImageDirectory+'thumbdown.png' );
		}
		else { // == '0'
			jQuery( '#wppa-like-'+mocc ).attr( 'src', wppaImageDirectory+'thumbup.png' );
		}
		return;
	}

	// Not likes system
	tmp = avg.split( '|' );
	avg = tmp[0];
	cnt = tmp[1];

	dsc = _wppaDisc[mocc][_wppaCurIdx[mocc]];
	myr = _wppaMyr[mocc][_wppaCurIdx[mocc]];

	if ( dsc == 'void' ) {
		jQuery('#wppa-rating-'+mocc).hide();
	}
	else {
		jQuery('#wppa-rating-'+mocc).show();

		// Graphic display ?
		if ( wppaRatingDisplayType == 'graphic' ) {

			// Set Avg rating
			_wppaSetRd( mocc, avg, '#wppa-avg-' );

			// Set My rating
			_wppaSetRd( mocc, myr, '#wppa-rate-' );

			// Display dislike
			if ( myr == 0 ) {

				// If i did not vote yet, enable the thumb down
				jQuery( '#wppa-dislike-'+mocc ).css( 'display', 'inline' );
				jQuery( '#wppa-dislike-imgdiv-'+mocc ).css( 'display', 'inline' );

				// Hide the filler only when there is a thumbdown
				if ( document.getElementById( 'wppa-dislike-'+mocc ) ) jQuery( '#wppa-filler-'+mocc ).css( 'display', 'none' );
				jQuery( '#wppa-dislike-'+mocc ).stop().fadeTo( 100, wppaStarOpacity );
			}
			else {

				// If i voted, disable thumb down
				jQuery( '#wppa-dislike-'+mocc ).css( 'display', 'none' );
				jQuery( '#wppa-dislike-imgdiv-'+mocc ).css( 'display', 'none' );
				jQuery( '#wppa-filler-'+mocc ).css( 'display', 'inline' );
				jQuery( '#wppa-filler-'+mocc ).stop().fadeTo( 100, wppaStarOpacity );

				// Show filler with dislike count
				jQuery( '#wppa-filler-'+mocc ).attr( 'title', dsc );
			}
		}

		// Numeric display
		else {
			// Set avg rating
			jQuery( '#wppa-numrate-avg-'+mocc ).html( avg+' ( '+cnt+' ) ' );

			// Set My rating
			jQuery( '.wppa-my-rat-'+mocc ).show();
			if ( myr ==  'void' ) {
				jQuery( '#wppa-numrate-mine-'+mocc ).html( '' );
				jQuery( '.wppa-my-rat-'+mocc ).hide();
			}
			else if ( wppaRatingOnce && myr > 0 ) {	// I did a rating and one allowed
				jQuery( '#wppa-numrate-mine-'+mocc ).html( myr );
			}
			else if ( myr < 0 ) {					// I did a dislike
				jQuery( '#wppa-numrate-mine-'+mocc ).html( ' dislike' );
			}
			else {								// Multiple allowed or change allowed or not rated yet
				var htm = '';
				for ( i=1;i<=wppaRatingMax;i++ ) {
					if ( myr == i ) {
						htm += '<span class="wppa-rating-numeric-mine" style="cursor:pointer; font-weight:bold;" onclick="_wppaRateIt( '+mocc+', '+i+' )">&nbsp;'+i+'&nbsp;</span>';
					}
					else {
						if ( myr > ( i-1 ) && myr < i ) htm += '&nbsp;( '+myr+' )&nbsp;';
						htm += '<span class="wppa-rating-numeric" style="cursor:pointer;" onclick="_wppaRateIt( '+mocc+', '+i+' )" onmouseover="this.style.fontWeight=\'bold\'" onmouseout="this.style.fontWeight=\'normal\'" >&nbsp;'+i+'&nbsp;</span>';
					}
				}
				jQuery( '#wppa-numrate-mine-'+mocc ).html( htm );
			}

			// Display dislike
			if ( myr == 0 ) {

				// If i did not vote yet, enable the thumb down
				jQuery( '#wppa-dislike-'+mocc ).css( 'display', 'inline' );
				jQuery( '#wppa-dislike-imgdiv-'+mocc ).css( 'display', 'inline' );
				jQuery( '#wppa-filler-'+mocc ).css( 'display', 'none' );
				jQuery( '#wppa-dislike-'+mocc ).stop().fadeTo( 100, wppaStarOpacity );
			}
			else {

				// If i voted, disable thumb down
				jQuery( '#wppa-dislike-'+mocc ).css( 'display', 'none' );
				jQuery( '#wppa-dislike-imgdiv-'+mocc ).css( 'display', 'none' );
				jQuery( '#wppa-filler-'+mocc ).css( 'display', 'inline' );
			}

			// Disply text
			jQuery( '#wppa-discount-'+mocc ).html( dsc + '&bull; ' );	// Show count
			jQuery( '#wppa-filler-'+mocc ).css( 'display', 'none' );

		}
		// One Button Vote only?
		if ( myr == 0 ) {
			jQuery( '#wppa-vote-button-'+mocc ).val( wppaVoteForMe );
		}
		else {
			jQuery( '#wppa-vote-button-'+mocc ).val( wppaVotedForMe );
		}
		jQuery( '#wppa-vote-count-'+mocc ).html( cnt );
	}
}

function wppaGetDislikeText( dsc,myr,incmine ) {

	return dsc;
/*
	if ( dsc == 0 && myr != 0 ) dsctxt = ' '+wppaNoDislikes+' ';
	else if ( dsc == 1 ) dsctxt = ' '+wppa1Dislike+' ';
	else dsctxt = ' '+dsc+' '+wppaDislikes+' ';
	if ( incmine && myr < 0 ) dsctxt+=wppaIncludingMine;
	return dsctxt;
*/
}

function _wppaSetRd( mocc, avg, where ) {

	var idx1 = parseInt( avg );
	var idx2 = idx1 + 1;
	var frac = avg - idx1;
	var opac = wppaStarOpacity + frac * ( 1.0 - wppaStarOpacity );
	var ilow = 1;
	var ihigh = wppaRatingMax;

	if ( avg == 'void' ) {
		jQuery( '#wppa-my-rat-'+mocc ).hide();
		jQuery( '.wppa-my-rat-'+mocc ).hide();
		jQuery( '.wppa-rate-'+mocc ).hide();
		jQuery( '.wppa-ratingthumb' ).hide();
		jQuery( '#wppa-numrate-mine-'+mocc ).hide();
	}
	else {
		jQuery( '#wppa-my-rat-'+mocc ).show();
		jQuery( '.wppa-my-rat-'+mocc ).show();
		jQuery( '.wppa-rate-'+mocc ).show();
		jQuery( '.wppa-ratingthumb' ).show();
		jQuery( '#wppa-numrate-mine-'+mocc ).show();

		for ( idx=ilow;idx<=ihigh;idx++ ) {
			if ( where == '#wppa-rate-' || where == '.wppa-rate-' ) {

				// Replace V by *
				if ( jQuery( where+mocc+'-'+idx ).attr( 'src' ) != wppaImageDirectory+'star.ico' ) {
					jQuery( where+mocc+'-'+idx ).attr( 'src', wppaImageDirectory+'star.ico' );
				}
			}
			if ( idx <= idx1 ) {
				jQuery( where+mocc+'-'+idx ).stop().fadeTo( 100, 1.0 );
			}
			else if ( idx == idx2 ) {
				jQuery( where+mocc+'-'+idx ).stop().fadeTo( 100, opac );
			}
			else {
				jQuery( where+mocc+'-'+idx ).stop().fadeTo( 100, wppaStarOpacity );
			}
		}
	}
}

function _wppaFollowMe( mocc, idx ) {

	if ( _wppaSSRuns[mocc] ) return;				// Do not rate on a running show, what only works properly in Firefox

	if ( _wppaMyr[mocc][_wppaCurIdx[mocc]] != 0 && wppaRatingOnce ) return;	// Already rated
	if ( _wppaMyr[mocc][_wppaCurIdx[mocc]] < 0 ) return; 	// Disliked aleady
	if ( _wppaVoteInProgress ) return;
	_wppaSetRd( mocc, idx, '#wppa-rate-' );
}

function wppaOvlFollowMe( mocc, idx, val ) {
	if ( val ) return; // Already rated
	_wppaSetRd( mocc, idx, '.wppa-rate-' );
}

function _wppaLeaveMe( mocc, idx ) {

	if ( _wppaSSRuns[mocc] ) return;				// Do not rate on a running show, what only works properly in Firefox

	if ( _wppaMyr[mocc][_wppaCurIdx[mocc]] != 0 && wppaRatingOnce ) return;	// Already rated
	if ( _wppaMyr[mocc][_wppaCurIdx[mocc]] < 0 ) return; 	// Disliked aleady
	if ( _wppaVoteInProgress ) return;
	_wppaSetRd( mocc, _wppaMyr[mocc][_wppaCurIdx[mocc]], '#wppa-rate-' );
}

function wppaOvlLeaveMe( mocc, idx, val ) {
	_wppaSetRd( mocc, val, '.wppa-rate-' );
}

function _wppaValidateComment( mocc, photoid ) {


	if ( ! photoid ) photoid = _wppaId[mocc][_wppaCurIdx[mocc]];

	// Process name
	var name = jQuery( '#wppa-comname-'+mocc ).val();
	if ( name.length<1 ) {
		alert( __( 'Please enter your name', 'wp-photo-album-plus' ) );
		return false;
	}

	// Process email address
	if ( wppaEmailRequired == 'required' || wppaEmailRequired == 'optional' ) {
		var email = jQuery( '#wppa-comemail-'+mocc ).val();
		if ( wppaEmailRequired == 'optional' && email.length == 0 ) {
			return true;
		}
		var atpos=email.indexOf( "@" );
		var dotpos=email.lastIndexOf( "." );
		if ( atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length ) {
			alert( __( 'Please enter a valid email address', 'wp-photo-album-plus' ) );
			return false;
		}
	}

	// Process comment
	var text = jQuery( '#wppa-comment-'+mocc ).val( );
	if ( text.length<1 ) {
		alert( __( 'Please enter a comment', 'wp-photo-album-plus' ) );
		return false;
	}

	return true;
}

function _wppaGo( url ) {

	document.location = url;	// Go!
}

function _wppaBbb( mocc,where,act ) {


	if ( _wppaSSRuns[mocc] ) return;

	var elm = '#bbb-'+mocc+'-'+where;
	switch ( act ) {
		case 'show':
			if ( where == 'l' ) jQuery( elm ).attr( 'title', __( 'Previous photo', 'wp-photo-album-plus' ) );
			if ( where == 'r' ) jQuery( elm ).attr( 'title', __( 'Next photo', 'wp-photo-album-plus' ) );
			jQuery( '.wppa-bbb-'+mocc ).css( 'cursor', 'pointer' );
			break;
		case 'hide':
			jQuery( '.wppa-bbb-'+mocc ).removeAttr( 'title' );
			jQuery( '.wppa-bbb-'+mocc ).css( 'cursor', 'default' );
			break;
		case 'click':
			if ( where == 'l' ) wppaPrev( mocc );
			if ( where == 'r' ) wppaNext( mocc );
			break;
		default:
			alert( 'Unimplemented instruction: '+act+' on: '+elm );
	}
}

function _wppaUbb( mocc,where,act ) {


	var elm = '#ubb-'+mocc+'-'+where;

	switch ( act ) {
		case 'show':
			if ( where == 'l' ) jQuery( elm ).attr( 'title', __( 'Previous photo', 'wp-photo-album-plus' ) );
			if ( where == 'r' ) jQuery( elm ).attr( 'title', __( 'Next photo', 'wp-photo-album-plus' ) );
			jQuery( '.ubb-'+mocc ).css( 'cursor', 'pointer' );
			jQuery( '.ubb-'+mocc ).stop().fadeTo( 200, 0.8 );
			jQuery( '#wppa-startstop-icon-' + mocc ).stop().fadeTo( 200, 0.8 );
			break;
		case 'hide':
			jQuery( '.ubb-'+mocc ).removeAttr( 'title' );
			jQuery( '.ubb-'+mocc ).css( 'cursor', 'default' );
			if ( wppaIsMobile ) {
				jQuery( '.ubb-'+mocc ).stop().fadeTo( 200, 0.1 );
			}
			else {
				jQuery( '.ubb-'+mocc ).stop().fadeTo( 200, 0 );
			}
			jQuery( '#wppa-startstop-icon-' + mocc ).stop().fadeTo( 200, 0 );
			break;
		case 'click':
			if ( wppaIsMobile ) {
				jQuery( '.ubb-'+mocc ).stop().fadeTo( 200, 1 ).fadeTo( 1000, 0 );
				jQuery( '#wppa-startstop-icon-' + mocc ).stop().fadeTo( 200, 1 ).fadeTo( 1000, 0 );
			}
			if ( where == 'l' ) {
				wppaPrev( mocc );
			}
			if ( where == 'r' ) {
				wppaNext( mocc );
			}
			break;
		default:
			alert( 'Unimplemented instruction: '+act+' on: '+elm );
	}
}

function wppaOpenComments( mocc ) {

	if ( _wppaSSRuns[mocc] ) _wppaStop( mocc );

	// Show existing comments
	jQuery( '#wppa-comtable-wrap-'+mocc ).css( 'display', 'block' );

	// Show the input form table
	jQuery( '#wppa-comform-wrap-'+mocc ).css( 'display', 'block' );

	// Hide the comment footer
	jQuery( '#wppa-comfooter-wrap-'+mocc ).css( 'display', 'none' );

	// Do autocol to fix a layout problem
	wppaColWidth[mocc] = 0;
	setTimeout( '_wppaDoAutocol( '+mocc+' )', 100 );
}

function _wppaShowMetaData( mocc, key ) {

	if ( ! _wppaSlides[mocc] ) return;

	// What to do when the slideshow is NOT running
	if ( ! _wppaSSRuns[mocc] && ! __wppaOverruleRun ) {
		if ( key == 'show' ) {			// Show
			if ( wppaAutoOpenComments ) {
				// Show existing comments
				jQuery( '#wppa-comtable-wrap-'+mocc ).css( 'display', 'block' );
				// Show the input form table
				jQuery( '#wppa-comform-wrap-'+mocc ).css( 'display', 'block' );
				// Hide the comment footer
				jQuery( '#wppa-comfooter-wrap-'+mocc ).css( 'display', 'none' );
			}
			// Fade the browse arrows in
			if ( _wppaCurIdx[mocc] != 0 )
				jQuery( '.wppa-first-'+mocc ).show(); // css( 'visibility', 'visible' ); // fadeIn( 300 );
			if ( _wppaCurIdx[mocc] != ( _wppaSlides[mocc].length - 1 ) )
				jQuery( '.wppa-last-'+mocc ).show(); //css( 'visibility', 'visible' ); // fadeIn( 300 );
			// SM box
			if ( wppaShareHideWhenRunning ) {
				jQuery( '#wppa-share-'+mocc ).css( 'display', '' );
			}

			// Fotomoto
			wppaFotomotoToolbar( mocc, _wppaHiresUrl[mocc][_wppaCurIdx[mocc]] );

		}
		else {							// Hide
			// Hide existing comments
			jQuery( '#wppa-comtable-wrap-'+mocc ).css( 'display', 'none' );
			// Hide the input form table
			jQuery( '#wppa-comform-wrap-'+mocc ).css( 'display', 'none' );
			// Show the comment footer
			jQuery( '#wppa-comfooter-wrap-'+mocc ).css( 'display', 'block' );
			// Fade the browse arrows out
//			jQuery( '.wppa-first-'+mocc ).fadeOut( 300 );
//			jQuery( '.wppa-last-'+mocc ).fadeOut( 300 );
			wppaFotomotoHide( mocc );
		}
	}
	// What to do when the slideshow is running
	else {	// Slideshow is running
		if ( key == 'show' ) {
			// Fotomoto
			if ( ! wppaFotomotoHideWhenRunning ) wppaFotomotoToolbar( mocc, _wppaHiresUrl[mocc][_wppaCurIdx[mocc]] );
		}
		else {
			// SM box
			if ( wppaShareHideWhenRunning ) {
				jQuery( '#wppa-share-'+mocc ).css( 'display', 'none' );
			}
			// Fotomoto
		//	if ( wppaFotomotoHideWhenRunning )
		//	wppaFotomotoHide( mocc );
		}
	}

	// What to do always, independant of slideshow is running
	if ( key == 'show' ) {
		// Show title and description
		jQuery( "#imagedesc-"+mocc ).css( 'visibility', 'visible' );
		jQuery( "#imagetitle-"+mocc ).css( 'visibility', 'visible' );
		// Display counter
		jQuery( "#counter-"+mocc ).css( 'visibility', 'visible' );
		// Display iptc
		jQuery( "#iptccontent-"+mocc ).css( 'visibility', 'visible' );
		jQuery( "#exifcontent-"+mocc ).css( 'visibility', 'visible' );
	}
	else {
		// Hide title and description
//		jQuery( "#imagedesc-"+mocc ).css( 'visibility', 'hidden' );
//		jQuery( "#imagetitle-"+mocc ).css( 'visibility', 'hidden' );
		// Hide counter
		jQuery( "#counter-"+mocc ).css( 'visibility', 'hidden' );
		// Fade the browse arrows out
		jQuery( '.wppa-first-'+mocc ).hide(); // css( 'visibility', 'hidden' ); // fadeOut( 300 );
		jQuery( '.wppa-last-'+mocc ).hide(); // css( 'visibility', 'hidden' ); // fadeOut( 300 );
		// Hide iptc
		jQuery( "#iptccontent-"+mocc ).css( 'visibility', 'hidden' );
		jQuery( "#exifcontent-"+mocc ).css( 'visibility', 'hidden' );

	}
}

function wppaGetSlideshowTimeout( mocc, from ) {

	var time;
	if ( _wppaTimeOut[mocc] == 'random' ) {
		var min = 2 * wppaAnimationSpeed;
		var max = 7 * wppaAnimationSpeed;
		time = Math.floor(Math.random() * (max - min + 1)) + min;
	}
	else {
		time = _wppaTimeOut[mocc];
	}
	return time;
}

function wppaIsSlidshowVisible( mocc ) {

	if ( ! wppaLazyLoad ) {
		return true;
	}

	var ids, len, i, elm, rect;

	ids = ['slide_frame-'+mocc, 'filmwindow-'+mocc];
	len = ids.length;
	for ( i = 0; i < len; i++ ) {
		elm = document.getElementById( ids[i] );
		if ( elm ) {
			rect = elm.getBoundingClientRect();
			if ( wppaIsElementInViewport( elm ) ) {
				return true;
			}
		}
	}
	wppaFilmInit[mocc] = false;
	return false;
}

function wppaFilmThumbToCanvas(imgId) {

	var canvasId 		= imgId+'-canvas';
	var imgElm 			= document.getElementById(imgId);
	var canvasElm 		= document.getElementById(canvasId);
	if ( typeof( canvasElm ) == 'undefined' ) {
//		console.log( 'Error. No canvas elm for item '+imgId );
		return;
	}
	var ctx 			= canvasElm.getContext("2d");
	var	sourceWidth 	= imgElm.naturalWidth | imgElm.videoWidth;
	var sourceHeight 	= imgElm.naturalHeight | imgElm.videoHeight;
	var canvasWidth 	= canvasElm.width;
	var canvasHeight 	= canvasElm.height;

	// Image too landscape or too portrait?
	if ( ( sourceWidth / sourceHeight ) > ( canvasWidth / canvasHeight ) ) {

		// Too landscape
		fromWidth 	= sourceHeight * canvasWidth / canvasHeight;
		fromX 		= ( sourceWidth - fromWidth ) / 2;
		ctx.drawImage( imgElm, fromX, 0, fromWidth, sourceHeight, 0, 0, canvasWidth, canvasHeight );
	}
	else {

		// Too portrait
		fromHeight 	= sourceWidth * canvasHeight / canvasWidth;
		fromY 		= ( sourceHeight - fromHeight ) /2;
		ctx.drawImage( imgElm, 0, fromY, sourceWidth, fromHeight, 0, 0, canvasWidth, canvasHeight );
	}

	if ( imgElm.pause ) imgElm.pause();
	return;
}
