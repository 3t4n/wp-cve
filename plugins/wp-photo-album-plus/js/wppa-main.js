// wppa-main.js
//
// contains common functions
//

wppaWppaVer = '8.6.04.006';

// jQuery(document).ready(function(){wppaConsoleLog('Document.ready')});

// Tabby click function
function wppaTabbyClick(){

	jQuery(window).trigger("resize");
	jQuery(document).trigger("tabbychange");
	jQuery(window).trigger("orientationchange");
	wppaAdjustAllFilmstrips(wppaEasingSlide);

};

// Init at dom ready
jQuery(document).ready(function() {
	wppaDoInit();
});

// Init nicescrollers
jQuery(document).ready(function(){
	if ( jQuery().niceScroll ) {
		jQuery(document).ready(function(){
			jQuery(".wppa-edit-area").niceScroll(".wppa-nicewrap",wppaNiceScrollOpts);
		});
	}
});

// QR code
function wppaQRUpdate(url) {

	wppaAjaxSetQrCodeSrc(url, "#wppa-qr-img");
	return;
}
jQuery(document).ready(function(){
	wppaQRUpdate(document.location.href);
});
window.onpopstate = function( event ) {
	wppaQRUpdate(document.location.href);
}

// General initialisation
function wppaDoInit() {

jQuery(document).ready(function(){
	// Misc. init
	_wppaTextDelay = wppaAnimationSpeed;
	if ( wppaFadeInAfterFadeOut ) {
		_wppaTextDelay *= 2;
	}
	if ( wppaIsMobile && wppaNoAnimateOnMobile ) {
		_wppaTextDelay = 10;
	}

	// Make sure ajax spinners dies
	jQuery( '.wppa-ajax-spin' ).hide();

	// Make sure ovl spinner dies
	jQuery( '.wppa-ovl-spin' ).hide();

	// Fade ubbs out
	setTimeout( function() {

		jQuery(".wppa-ubb").each(function(){

			var divId = jQuery(this).attr('id');
			var mocc = divId.substr(6);
			wppaUbb(mocc,'l','hide');
			wppaUbb(mocc,'r','hide');
		});

	}, 3000 );

	// Responsive handlers
	jQuery(window).on("DOMContentLoaded load resize wppascrollend orientationchange",wppaDoAllAutocols);

	// Size scrollable areas
	jQuery(window).on('DOMContentLoaded load resize scroll wheel orientationchange',wppaSizeArea);

	// Make Lazy load images visible
	jQuery(window).on('DOMContentLoaded load resize scroll wheelend orientationchange', function(){wppaMakeLazyVisible()});
	jQuery('.wppa-divnicewrap').on('DOMContentLoaded load resize scroll wheelend orientationchange', function(){wppaMakeLazyVisible()});

	// Init masonryplus
	jQuery(window).on('DOMContentLoaded load resize wppascrollend orientationchange', wppaInitMasonryPlus);

	// When the window size changes, filmstrip needs immediate adjustment
	jQuery(window).on('resize', function(){wppaAdjustAllFilmstrips(wppaEasingSlide)});

	// Resize nicescrollers, re-layout masonryplus
	jQuery(window).on("DOMContentLoaded load resize wppascrollend orientationchange", function(){

		setTimeout( function() {

			wppaResizeNice();

		}, 1000);
    });

	// Fake a resize
	jQuery(window).trigger('resize');

	// Protect rightclick
	wppaProtect();

	// For tabby plugin:
	setTimeout(function(){
		jQuery(".responsive-tabs__heading").on("click", wppaTabbyClick);
        jQuery(".responsive-tabs__list__item").on("click", wppaTabbyClick);
	},10);

	jQuery(document).on("tabbychange",function(){

		if ( typeof jQuery("div").getNiceScroll != "undefined" ) {
			setTimeout(function(){
				jQuery("div").getNiceScroll().resize();
				wppaDoAllAutocols();
			},500);
		}
		setTimeout(function(){
			wppaDoAllAutocols();
			jQuery(window).trigger("resize");
			jQuery("#wppa-ovl-spin").hide();
			wppaMakeLazyVisible();
		},1500);
	});

	// Lightbox global
	if ( wppaOvlGlobal ) {
		jQuery("a").each(function(){ 								// get all a tags
			var href = jQuery(this).attr("href"); 					// find href
			if ( href ) {
				var temp = href.split('.');
				var ext  = temp[temp.length-1]; 						// find extension
				if ( ext == 'jpg' || ext == 'jpeg' || ext == 'png' ) {	// If its an image
					if ( ! jQuery(this).attr("data-rel") ) { 			// should not already have attr data-rel
						jQuery(this).attr("data-rel",wppaOvlGlobal); 	// Add: 'data-rel="wppa"' or: 'data-rel="wppa[single]"'
						jQuery(this).css("cursor","wait"); 				// will be set to url wppaMagnifierCursor by lightbox js
					}
				}
			}
		});
	}

	// Lazy on mobile extra:
	jQuery("div").on("touchmove", wppaMakeLazyVisible);

	// Align ajax spinner
	jQuery(".wppa-ajax-spin").css({top:wppaWindowHeight()/2,left:wppaWindowWidth()/2});
});
}

// Resize all nicescrollers
var wppaResizeNiceTimer;
function wppaResizeNice() {

	clearTimeout(wppaResizeNiceTimer);

	wppaResizeNiceTimer = setTimeout(function(){_wppaResizeNice()}, 200);
}
function _wppaResizeNice() {

	if ( typeof jQuery("body").getNiceScroll == "function" ) {
		jQuery("body").getNiceScroll().resize();
	}
	jQuery("div").each(function(){
		if ( typeof jQuery(this).getNiceScroll == "function" ) {
			jQuery(this).getNiceScroll().resize();
		}
	});
}

// resize end listener
var wppaResizeEndTimer;
jQuery(document).ready(function(){
	jQuery(window).on( 'resize load', function () {
		clearTimeout( wppaResizeEndTimer );
		wppaResizeEndTimer = setTimeout( function () {
			jQuery(window).trigger('wpparesizeend');
			wppaConsoleLog('Resize end event');
		}, wppaResizeEndDelay );
	});
});

// scroll end listener
var wppaScrollEndTimer;
jQuery(document).ready(function(){
	jQuery(window).on( 'scroll wheel touchmove', function () {
		clearTimeout( wppaScrollEndTimer );
		wppaScrollEndTimer = setTimeout( function () {
			jQuery(window).trigger('wppascrollend');
		}, wppaScrollEndDelay );
	});
});

// Install auto div sizer
jQuery(document).ready(function(){
	jQuery(window).on("DOMContentLoaded load resize scroll wheel orientationchange", wppaSizeAutoDiv);
});

// Size auto wppa_div
function wppaSizeAutoDiv() {
	jQuery('.wppa-autodiv').each(function(index) {
		var totHeight = jQuery(window).height();
		var factor = jQuery(this).attr('data-max-height');
		jQuery(this).css({maxHeight:totHeight*factor});
	});
}

var wppaLastAllAutocols = 0;
var wppaLastAllAutocolsTimer = 0;
// Do the auto cols
function wppaDoAllAutocols(e) {

	// Too soon?
	if ( wppaTimNow() < ( wppaLastAllAutocols + 200 ) ) {
		if ( ! wppaLastAllAutocolsTimer ) {
			wppaLastAllAutocolsTimer = setTimeout( wppaDoAllAutocols, 200 );
		}
		return;
	}
	clearTimeout( wppaLastAllAutocolsTimer );
	wppaLastAllAutocols = wppaTimNow();

	_wppaDoAllAutocols(0);
}

function _wppaDoAllAutocols(i) {

	// Do occurrences that are responsive
	jQuery(".wppa-container").each(function(){
		var divId = jQuery(this).attr("id");
		var mocc = divId.substr(15);
		if ( wppaAutoColumnWidth[mocc] ) {
			_wppaDoAutocol( mocc, i);
		}
	});

	// Do retries if configured (-1 is infinite)
	if ( i < wppaExtendedResizeCount || wppaExtendedResizeCount == -1 ) {
		setTimeout(function(){_wppaDoAllAutocols(i+1)}, wppaExtendedResizeDelay);
	}

	return true;
}

// If disable right mouseclick
function wppaProtect() {
	if ( wppaHideRightClick ) {
		jQuery('img' ).bind('contextmenu', function(e) {
			return false;
		});
		jQuery('video' ).bind('contextmenu', function(e) {
			return false;
		});
		jQuery('canvas' ).bind('contextmenu', function(e) {
			return false;
		});
	}
}

// Initialize Ajax render partial page content and history update
jQuery(document).ready( function( e ) {

	// Are we allowed and capable to ajax?
	jQuery(document).ready(function(){
		if ( wppaAllowAjax && jQuery.ajax ) wppaCanAjaxRender = true;
	});

	// Can we do history.pushState ?
	if ( typeof( history.pushState ) != 'undefined') {
		wppaCanPushState = true;
	}
});

// On the fly init lightbox
function wppaUpdateLightboxes() {

	// Native wppa lightbox
	if ( typeof( wppaInitOverlay ) == 'function') {
		wppaInitOverlay();
	}

	// Lightbox-3
	if ( typeof( myLightbox ) != 'undefined') {
		if ( typeof( myLightbox.updateImageList ) == 'function') {
			myLightbox.updateImageList();
		}
	}

	// PrettyPhoto
	if ( jQuery().prettyPhoto ) {
		jQuery( "a[rel^='prettyPhoto']" ).prettyPhoto( {
			deeplinking: false,
		});
	}
}

// Stop video of a given occurrency
function wppaStopVideo( mocc ) {
	var id = [];
	var video;
	var i;

	id[1] = 'wppa-overlay-img';
	id[2] = 'theimg0-'+mocc;
	id[3] = 'theimg1-'+mocc;
	i = 0;

	while ( i < 3 ) {
		i++;
		if ( i != 1 || mocc == 0 ) {	// Stop video on lighbox only when mocc = 0, i.e. stop all video
			video = document.getElementById( id[i] );
			if ( video ) {
				if ( typeof( video.pause ) == 'function') {
					video.pause();
				}
			}
		}
	}
}

// Stop audio
function wppaStopAudio( mocc ) {

	var items = jQuery( '.wppa-audio-' + mocc );
	var i = 0;
	while ( i < items.length ) {
		items[i].pause();
//		console.log('Stop audio '+mocc+' '+jQuery(items[i]).attr('id'));
		i++;
	}
}

// Convert a thumbnail url to a fs url
function wppaMakeFullsizeUrl( url ) {
var temp;
var temp2;

	url = url.replace( '/thumbs/', '/' );	// Not a thumb

	// Remove sizespec for Cloudinary
	temp = url.split( '//' );
	if ( temp[1] ) {
		temp2 = temp[1].split( '/' );
		url = temp[0]+'//';
	}
	else {
		temp2 = temp[0].split( '/' );
		url = '';
	}
	var j = 0;
	while ( j < temp2.length ) {
		var chunk = temp2[j];
		var w = chunk.split('_' );
		if ( w[0] != 'w') {
			if ( j != 0 ) url += '/';
			url += chunk;
		}
		j++;
	}
	return url;
}

// Get the width of a container
function wppaGetContainerWidth( mocc ) {

	var elm = document.getElementById('wppa-container-'+mocc );
	if ( ! elm ) {
		return; // container vanishd (by pla)
	}

	var w = 0;

	if ( ! wppaAutoColumnWidth[mocc] ) {
		return elm.clientWidth;
	}

	while ( w == 0 ) {
		elm = elm.parentNode;
		w = jQuery( elm ).width();
	}
	return parseInt( w ); //* wppaAutoColumnFrac[mocc] );
}

// Do the responsive size adjustment
function _wppaDoAutocol( mocc, i ) {

	// Auto?
	if ( ! wppaAutoColumnWidth[mocc] ) {
		return true;
	}

	var w;
	var h;
	var old;
	var exists;

	// Container
	w = wppaGetContainerWidth(mocc);

	// Anything to do here?
	var container = document.getElementById( 'wppa-container-' + mocc );
	if ( ! container ) {
		return;
	}

	// Covers
	if ( ! wppaCoverImageResponsive[mocc] ) {
		exists = jQuery( ".wppa-asym-text-frame-"+mocc );
		if ( exists.length > 1 ) {
			old = jQuery( exists[0] ).width();

			if ( wppaResponseSpeed == 0 ) {
				jQuery( ".wppa-asym-text-frame-"+mocc ).css( {width:(w - wppaTextFrameDelta)} );
				jQuery( ".wppa-cover-box-"+mocc ).css( {width:w} );
			}
			else {
				wppaAnimate( ".wppa-asym-text-frame-"+mocc, {width:(w - wppaTextFrameDelta)}, wppaResponseSpeed, wppaEasingDefault );
				wppaAnimate( ".wppa-cover-box-"+mocc, {width:w}, wppaResponseSpeed, wppaEasingDefault );
			}
		}
	}

	// Multi Column Responsive covers
	exists = jQuery( ".wppa-cover-box-mcr-"+mocc );
	if ( exists.length > 1 ) {	// Yes there are

		var cw 			= document.getElementById('wppa-albumlist-' + mocc ).clientWidth;
		var nCovers 	= parseInt( ( cw + wppaCoverSpacing )/( wppaMaxCoverWidth+wppaCoverSpacing ) ) + 1;
		var coverMax1 	= nCovers - 1;
		var MCRWidth 	= parseInt( ( ( cw + wppaCoverSpacing )/nCovers ) - wppaCoverSpacing );

		if ( wppaColWidth[mocc] != cw || wppaMCRWidth[mocc] != MCRWidth ) {

			wppaColWidth[mocc] = cw;
			wppaMCRWidth[mocc] = MCRWidth;

			var idx = 0;
			while ( idx < exists.length ) {
				var col = idx % nCovers;
				switch ( col ) {
					case 0:	/* left */
						jQuery( exists[idx] ).css( {'marginLeft': '0px', 'clear': 'both', 'float': 'left'});
						break;
					case coverMax1:	/* right */
						jQuery( exists[idx] ).css( {'marginLeft': '0px', 'clear': 'none', 'float': 'right'});
						break;
					default:
						jQuery( exists[idx] ).css( {'marginLeft': wppaCoverSpacing, 'clear': 'none', 'float': 'left'});
				}
				idx++;
			}

			if ( wppaCoverImageResponsive[mocc] ) {
			}
			else {
				wppaAnimate( ".wppa-asym-text-frame-mcr-"+mocc, {width: (MCRWidth - wppaTextFrameDelta)}, wppaResponseSpeed, wppaEasingDefault );
			}
			old = jQuery( exists[0] ).width();
			wppaAnimate( ".wppa-cover-box-mcr-"+mocc, {width:MCRWidth}, wppaResponseSpeed, wppaEasingDefault );
		}
	}
	else if ( exists.length == 1 ) {	// One cover: full width, 0 covers don't care
		if ( wppaCoverImageResponsive[mocc] ) {
		}
		else {
			wppaAnimate( ".wppa-asym-text-frame-mcr-"+mocc, {width:(w - wppaTextFrameDelta)}, wppaResponseSpeed, wppaEasingDefault );
			var myCss = {
				'marginLeft': '0px',
				'float'		: 'left'
			}
			jQuery( ".wppa-cover-box-mcr-"+mocc ).css( myCss );
		}
	}

	// Grid covers. set container linheight to 0
	var isGrid = jQuery( '.wppa-album-cover-grid-'+mocc ).length;

	if ( isGrid > 0 ) {

		// Set container linheight to 0
		jQuery('#wppa-container-'+mocc).css( 'line-height', '0' );

		// Calculate width
		var nItems = parseInt( ( w / wppaMaxCoverWidth ) + 0.9999 );
		if ( nItems < 1 ) nItems = 1;

		// Set widths
		jQuery( '.wppa-album-cover-grid-'+mocc ).css( {width:(100/nItems)+'%'} );
	}

	// Thumbframes default
	if ( wppaThumbSpaceAuto ) {
		var tfw = parseInt( jQuery( ".thumbnail-frame-"+mocc ).css('width') );
		if ( tfw ) {
			var minspc = wppaMinThumbSpace;
			var weff = w - wppaThumbnailAreaDelta - 7;
			var nthumbs = Math.max( 1, parseInt( weff / ( tfw + minspc ) ) );
			var availsp = weff - nthumbs * tfw;
			var newspc = parseInt( availsp / ( nthumbs+1 ) );

			jQuery( ".thumbnail-frame-"+mocc ).css( {marginLeft:newspc});
		}
	}

	// Comalt thumbmails
	jQuery( ".thumbnail-frame-comalt-"+mocc ).css('width', w - wppaThumbnailAreaDelta );
	jQuery( ".wppa-com-alt-"+mocc ).css('width', w - wppaThumbnailAreaDelta - wppaComAltSize - 16 );

	// Masonry thumbnails horizontal
	var row = 1;
	var rowHeightPix;
	var rowHeightPerc = jQuery( '#wppa-mas-h-'+row+'-'+mocc ).attr('data-height-perc' );
	while ( rowHeightPerc ) {
		rowHeightPix = rowHeightPerc * ( w - wppaThumbnailAreaDelta ) / 100;
		jQuery( '#wppa-mas-h-'+row+'-'+mocc ).css('height', rowHeightPix );
		row++;
		rowHeightPerc = jQuery( '#wppa-mas-h-'+row+'-'+mocc ).attr('data-height-perc' );
	}

	// Fix bug in ie and chrome
	wppaSetMasHorFrameWidthsForIeAndChrome(mocc);

	// Slide
	if ( document.getElementById( 'slide_frame-'+mocc ) ) {
		wppaFormatSlide( mocc );
	}

	// Audio
	jQuery( "#audio-slide-"+mocc ).css('width', w - wppaBoxDelta - 6 );

	// Comments
	jQuery( ".wppa-comment-textarea-"+mocc ).css('width',w * 0.7 );

	// Filmstrip
	_wppaAdjustFilmstrip( mocc, wppaEasingSlide );	// reposition content

	// Texts in slideshow and browsebar
	if ( ! wppaIsMini[mocc] && typeof( _wppaSlides[mocc] ) != 'undefined') {	// Mini is properly initialized
		if ( wppaColWidth[mocc] < wppaMiniTreshold ) {
			jQuery( '#wppa-avg-rat-'+mocc ).html( __( 'Avg', 'wp-photo-album-plus' ) );
			jQuery( '#wppa-my-rat-'+mocc ).html( __( 'Mine', 'wp-photo-album-plus' ) );

			jQuery( '#counter-'+mocc ).html( ( _wppaCurIdx[mocc]+1 )+' / '+_wppaSlides[mocc].length );
		}
		else {
			jQuery( '#wppa-avg-rat-'+mocc ).html( __( 'Average&nbsp;rating', 'wp-photo-album-plus' ) );
			jQuery( '#wppa-my-rat-'+mocc ).html( __( 'My&nbsp;rating', 'wp-photo-album-plus' ) );

			jQuery( '#counter-'+mocc ).html( __( 'Photo', 'wp-photo-album-plus' )+' '+( _wppaCurIdx[mocc]+1 )+' '+__( 'of', 'wp-photo-album-plus' )+' '+_wppaSlides[mocc].length );
		}
	}

	// Single photo
	jQuery( ".wppa-sphoto-"+mocc ).css('width',w );
	jQuery( ".wppa-simg-"+mocc ).css('width',w - 2*wppaSlideBorderWidth );
	jQuery( ".wppa-simg-"+mocc ).css('height', '' );

	// Mphoto
	jQuery( ".wppa-mphoto-"+mocc ).css('width',w + 10 );
	jQuery( ".wppa-mimg-"+mocc ).css('width',w );
	jQuery( ".wppa-mimg-"+mocc ).css('height', '' );

	// smxpdf
	jQuery( ".smxpdf-"+mocc ).css('height', 0.8 * wppaWindowHeight() );

	// Search box
	if ( wppaSearchBoxSelItems[mocc] > 0 ) {
		if ( w / wppaSearchBoxSelItems[mocc] < 125 ) {
			jQuery( ".wppa-searchsel-item-"+mocc ).css('width', '100%' );
		}
		else {
			jQuery( ".wppa-searchsel-item-"+mocc ).css('width', ( 100 / wppaSearchBoxSelItems[mocc] ) + '%' );
		}
	}

	// Upload dialog album selectionbox
	jQuery( ".wppa-upload-album-"+mocc ).css('maxWidth', 0.6 * w );

	// Real calendar
	wppaSetRealCalendarHeights( mocc );

	return true;
}

// Set heights in real calendar
function wppaSetRealCalendarHeights( mocc ) {

	var w = jQuery('#wppa-real-calendar-'+mocc).width();

	if ( w > 0 ) {

		var ready = true;

		var h = w*wppaThumbAspect/7;
		jQuery('.wppa-real-calendar-day-'+mocc).css({height:h});

		var f = (w/50+2);
		jQuery('#wppa-real-calendar-'+mocc).css({fontSize:f});

		var m = f/4;
		jQuery('.wppa-real-calendar-head-td-'+mocc).css({marginTop:m,marginBottom:m});

		var b = h/2;
		jQuery('.wppa-realcalimg-'+mocc).each(function(){
			if ( this.height == 0 ) {
				ready = false;
			}
			else {
				var day = jQuery(this).attr('data-day');
				thisb = b - (h-this.height)/2;
				jQuery('.wppa-real-calendar-day-content-'+day+'-'+mocc).css({bottom:thisb});
			}
		});

		if ( ! ready ) {
			setTimeout(function(){wppaSetRealCalendarHeights( mocc );},100);
		}
	}
}

// Fix bug in IE and Chrome
function wppaSetMasHorFrameWidthsForIeAndChrome(mocc) {
	// For IE and Chrome there is the class .wppa-mas-h-{mocc}
	// Set widths of frames for IE and Chrome. These browsers interprete width:auto
	// sometimes not in relation to the specified height, but to the available space.
	var frames = jQuery( '.wppa-mas-h-'+mocc );
	var tnm = wppaMinThumbSpace;
	for ( var i=0;i<frames.length;i++ ) {

		var img = wppaGetChildI( frames[i] );
		if ( img ) {
			if ( img.nodeName == 'IMG') {
				if ( ! img.complete ) {
					setTimeout('wppaSetMasHorFrameWidthsForIeAndChrome( '+mocc+' )', 400 ); // Try again after 400 ms.
					return;
				}
			}
			var wd = ( ( img.naturalWidth ) / ( img.naturalHeight ) * ( img.height ) ) + tnm;
			jQuery( frames[i] ).css( {'width': wd } );
		}
	}
}
// get (grand)child of parent with id like i-...
function wppaGetChildI( parent ) {

	var children = parent.childNodes;
	var img = false;
	var i;

	for ( i=0; i<children.length; i++ ) {
		var child = children[i];
		if ( child.id ) {
			if ( child.id.substr( 0, 2 ) == 'i-' ) {
				return child;
			}
		}
		var grandChild = wppaGetChildI( child );
		if ( grandChild ) {
			return grandChild;
		}
	}
	return false;
}

// Fotomoto
var wppaFotomotoLoaded = false;
var wppaFotomotoToolbarIds = [];
function fotomoto_loaded() {
	wppaFotomotoLoaded = true;
}
function wppaFotomotoToolbar( mocc, url ) {
	if ( wppaColWidth[mocc] >= wppaFotomotoMinWidth ) {	// Space enough to show the toolbar
		jQuery( '#wppa-fotomoto-container-'+mocc ).css('display','inline' );
		jQuery( '#wppa-fotomoto-checkout-'+mocc ).css('display','inline' );
	}
	else {
		jQuery( '#wppa-fotomoto-container-'+mocc ).css('display','none' );
		jQuery( '#wppa-fotomoto-checkout-'+mocc ).css('display','none' );
		return;	// Too small
	}
	if ( wppaFotomoto && document.getElementById('wppa-fotomoto-container-'+mocc ) ) { // Configured and container present
		if ( wppaFotomotoLoaded ) {
			FOTOMOTO.API.checkinImage( url );
			wppaFotomotoToolbarIds[mocc] = FOTOMOTO.API.showToolbar('wppa-fotomoto-container-'+mocc, url );
		}
		else { // Not loaded yet, retry after 200 ms
			setTimeout('wppaFotomotoToolbar( '+mocc+',"'+url+'" )', 200 );
		}
	}
}
function wppaFotomotoHide( mocc ) {
	jQuery( '#wppa-fotomoto-container-'+mocc ).css('display','none' );
	jQuery( '#wppa-fotomoto-checkout-'+mocc ).css('display','none' );
}

// Sanitize utility
function wppaStringContainsForbiddenChars( str ) {
var forbidden = [ '?', '&', '#', '/', '"', "'" ];
var i=0;

	while ( i < forbidden.length ) {
		if ( str.indexOf( forbidden[i] ) != -1 ) {
			return true;
		}
		i++;
	}
	return false;
}

// Setup an event handler for popstate events
window.onpopstate = function( event ) {

	if ( wppaCanPushState ) {
		if ( event.state ) {
			if ( event.state.type ) {
				if ( event.state.type == 'slide' ) {
				//	console.log('slide popstate ');
					wppaNoStackPush = true;
					_wppaGoto( event.state.occur, event.state.slide );
					return;
				}
				if ( event.state.type == 'ajax' ) {
				//	console.log('ajax postate');
				//	alert('pres ok to continue');
					document.location.reload();
					return;
				}
			}
		}
	}

	// Do we have to reload?
	var url = document.location.href;
	if ( url.indexOf( 'wp-admin/admin.php' ) != -1 ) {
		return true; // is admin
	}
	else {
		document.location.reload();
	}
};

// Push history stack for a slideshow
function wppaPushStateSlide( mocc, slide, url ) {

	if ( wppaNoStackPush ) {
		wppaNoStackPush = false;
		console.log('stackpush skipped');
		return;
	}

	if ( ! wppaIsMini[mocc] ) {	// Not from a widget
		if ( wppaCanPushState && wppaUpdateAddressLine ) {
			if ( url != '' ) {
				jQuery(document).ready(function(){
					setTimeout(function(){
						try {
							history.pushState( {type: 'slide', slide: slide, occur: mocc}, "", url );
							wppaQRUpdate(url);
							console.log( 'slidehistory pushed mocc = '+mocc+', slide = '+slide );
						}
						catch( err ) {
							wppaConsoleLog( 'Slide history stack update failed' );
						}
					}, 1000);
				});
			}
		}
	}
}

// Filter enables the use of <script> tags inside a script
function wppaRepairScriptTags( text ) {
var temp;
var newtext;

	// Just to be sure we do not run into undefined error
	if ( typeof( text ) == 'undefined') return '';

	while ( text.indexOf( '[script') != -1 ) {
		text = text.replace( '[script', '<script' );
	}
	while ( text.indexOf( '[/script') != -1 ) {
		text = text.replace( '[/script', '</script' );
	}

	return text;

}

// Filter enables the use of a <br> tag while they are removed with strip_tags
// Also fixes [a ] and [/a], and [img  and /]
function wppaRepairBrTags( text ) {
var newtext;

	// Just to be sure we do not run into undefined error
	if ( typeof(text) == 'undefined') return '';

	newtext = text;
	newtext = newtext.replace( '[br /]', '<br />' );
	newtext = newtext.replace( '[a', '<a' );
	newtext = newtext.replace( /&quot;/g, '"' );
	newtext = newtext.replace( '"]', '">' );
	newtext = newtext.replace( '[/a]', '</a>' );
	newtext = newtext.replace( '[img', '<img' );
	newtext = newtext.replace( '/]', '/>' );

	return newtext;
}

// Replace text that is too long by ellipses
function wppaTrimAlt( text ) {
var newtext;

	// Just to be sure we do not run into undefined error
	if ( typeof(text) == 'undefined') return '';

	if ( text.length > 13 ) {
		newtext = text.substr( 0,10 ) + '...';
	}
	else newtext = text;
	return newtext;
}

// Initialize FaceBook sdk
var wppaFbInitBusy = false;
function wppaFbInit() {
	if ( ! wppaFbInitBusy ) {
		if ( typeof( FB ) != 'undefined') {
			wppaFbInitBusy = true;				// set busy
			setTimeout('_wppaFbInit()', 10 ); 	// do it async over 10 ms
		}
		else {
			setTimeout('wppaFbInit()', 200 );
		}
	}
}

var wppaFbInitDone = false;
function _wppaFbInit() {
	if ( wppaFbInitDone ) return;
	FB.init( {status : true, xfbml : true } );
	wppaFbInitBusy = false;
	wppaFbInitDone = true;
}

// Insert ( an emoticon ) in a comment text
function wppaInsertAtCursor( elm, value ) {

    //IE support
    if ( document.selection ) {
        elm.focus();
        sel = document.selection.createRange();
        sel.text = value;
    }

    //MOZILLA and others
    else if ( elm.selectionStart || elm.selectionStart == '0') {
        var startPos = elm.selectionStart;
        var endPos = elm.selectionEnd;
        elm.value = elm.value.substring( 0, startPos )
            + value
            + elm.value.substring( endPos, elm.value.length );
        elm.selectionStart = startPos + value.length;
        elm.selectionEnd = startPos + value.length;
    } else {
        elm.value += value;
    }
}

// Initialize Google Maps
function wppaGeoInit( mocc, lat, lon ) {
	var mapDiv = document.getElementById( "map-canvas-"+mocc );
	if ( ! mapDiv ) return;

	var myLatLng = new google.maps.LatLng( lat, lon );
	var mapOptions = {
		disableDefaultUI: false,
		panControl: false,
		zoomControl: true,
		mapTypeControl: true,
		scaleControl: true,
		streetViewControl: true,
		overviewMapControl: true,
		zoom: wppaGeoZoom,
		center: myLatLng,
//			mapTypeId: google.maps.MapTypeId.TERRAIN,
//			mapTypeControlOptions: {
//				mapTypeIds: [ google.maps.MapTypeId.TERRAIN, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID ],
//				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
//			},
	};
	var map = new google.maps.Map( document.getElementById( "map-canvas-"+mocc ), mapOptions );
	var marker = new google.maps.Marker( {
		position: myLatLng,
		map: map,
		title:""
	});

	google.maps.event.addListener( map, "center_changed", function() {
		// 1 second after the center of the map has changed, pan back to the
		// marker.
		window.setTimeout(function() {
		  map.panTo( marker.getPosition() );
		}, 1000 );
	});
}

// Encode funny chars
function wppaEncode( xtext ) {
	var text, result;

	if ( typeof( xtext )=='undefined') return;

	text = String(xtext);
	result = text.replace( /#/g, '%23' );
	text = result;
	result = text.replace( /&/g, '%26' );
	text = result;
//	result = text.replace( /+/g, '%2B' );
	var temp = text.split( '+' );
	var idx = 0;
	result = '';
	while ( idx < temp.length ) {
		result += temp[idx];
		idx++;
		if ( idx < temp.length ) result += '%2B';
	}

	return result;
}

// Compute photo id out of an url
function wppaUrlToId( url ) {
	var temp = url.split( '/wppa/' );		// if '/wppa/' found, a wppa image
	if ( temp.length == 1 ) {
		temp = url.split( '/upload/' );	// if '/upload/' found, a cloudinary image
	}
	if ( temp.length == 1 ) {
		return 0;	// Still nothing, not a wppa image or ahires image, return 0
	}
	// Find image id
	temp = temp[1];
	temp = temp.split( '.' );
	temp = temp[0].replace( '/', '' );
	temp = temp.replace( '/', '' );
	temp = temp.replace( '/', '' );
	temp = temp.replace( '/', '' );
	temp = temp.replace( '/', '' );

	return temp;
}

// Init supersearch
jQuery(document).ready(function () {
	jQuery('.wppa-ss-button').each(function(){
		mocc = jQuery(this).attr('data-mocc');
		wppaSuperSearchSelect(mocc);
	});
});


// Opens/closes selection boxes in supersearch html
function wppaSuperSearchSelect( mocc, go ) {

	// Init
	jQuery( '#wppa-ss-albumopt-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-albumcat-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-albumname-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-albumtext-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-photoopt-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-photoname-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-photoowner-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-phototag-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-phototext-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-photoexif-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-photoiptc-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-exifopts-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-iptcopts-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-spinner-'+mocc ).css('display', 'none');
	jQuery( '#wppa-ss-button-'+mocc ).css('display', 'none');

	var s1 = jQuery( '#wppa-ss-pa-'+mocc ).val();
	var s2 = '';
	var s3 = '';
	var data = '';
	switch ( s1 ) {
		case 'a':
			jQuery( '#wppa-ss-albumopt-'+mocc ).css('display', '');
			s2 = jQuery( '#wppa-ss-albumopt-'+mocc ).val();
			switch ( s2 ) {
				case 'c':
					jQuery( '#wppa-ss-albumcat-'+mocc ).css('display', '');
					var set = jQuery( '.wppa-ss-albumcat-'+mocc );
					data = '';
					var i;
					for ( i = 0; i < set.length; i++ ) {
						if ( jQuery( set[i] ).prop('selected')) {
							data += '.' + jQuery( set[i] ).val();
						}
					}
					data = data.substr( 1 );

					if ( data != '' ) {
						jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
					}
					break;
				case 'n':
					jQuery( '#wppa-ss-albumname-'+mocc ).css('display', '');
					data = jQuery( '#wppa-ss-albumname-'+mocc ).val();
					if ( data != null ) {
						jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
					}
					break;
				case 't':
					jQuery( '#wppa-ss-albumtext-'+mocc ).css('display', '');
					var set = jQuery( '.wppa-ss-albumtext-'+mocc );
					data = '';
					var i;
					for ( i = 0; i < set.length; i++ ) {
						if ( jQuery( set[i] ).prop('selected')) {
							data += '.' + jQuery( set[i] ).val();
						}
					}
					data = data.substr( 1 );

					if ( data != '' ) {
						jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
					}
					break;
			}
			break;

		case 'p':
			jQuery( '#wppa-ss-photoopt-'+mocc ).css('display', '');
			s2 = jQuery( '#wppa-ss-photoopt-'+mocc ).val();
			switch ( s2 ) {
				case 'n':
					jQuery( '#wppa-ss-photoname-'+mocc ).css('display', '');
					data = jQuery( '#wppa-ss-photoname-'+mocc ).val();
					if ( data != null ) {
						jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
					}
					break;
				case 'o':
					jQuery( '#wppa-ss-photoowner-'+mocc ).css('display', '');
					data = jQuery( '#wppa-ss-photoowner-'+mocc ).val();
					if ( data != null ) {
						jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
					}
					break;
				case 'g':
					jQuery( '#wppa-ss-phototag-'+mocc ).css('display', '');
					var set = jQuery( '.wppa-ss-phototag-'+mocc );
					data = '';
					var i;
					for ( i=0; i<set.length; i++ ) {
						if ( jQuery( set[i] ).prop('selected')) {
							data += '.' + jQuery( set[i] ).val();
						}
					}
					data = data.substr( 1 );

					if ( data != '' ) {
						jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
					}
					break;
				case 't':
					jQuery( '#wppa-ss-phototext-'+mocc ).css('display', '');
					var set = jQuery( '.wppa-ss-phototext-'+mocc );
					data = '';
					var i;
					for ( i=0; i<set.length; i++ ) {
						if ( jQuery( set[i] ).prop('selected')) {
							data += '.' + jQuery( set[i] ).val();
						}
					}
					data = data.substr( 1 );

					if ( data != '' ) {
						jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
					}
					break;
				case 'i':
					jQuery( '#wppa-ss-photoiptc-'+mocc ).css('display', '');
					s3 = jQuery( '#wppa-ss-photoiptc-'+mocc ).val();
					if ( s3 ) {
						if ( s3.length > 2 ) {
							s3 = s3.replace( '#', 'H' );	// Replace # by H
						}
						if ( s3 != '' ) {
							jQuery( '#wppa-ss-iptcopts-'+mocc ).css('display', '');
							if ( wppaLastIptc != s3 ) {
								wppaAjaxGetSsIptcList( mocc, s3, 'wppa-ss-iptcopts-'+mocc );
								wppaLastIptc = s3;
							}
							else {
								data = jQuery( '#wppa-ss-iptcopts-'+mocc ).val();
								if ( data != null && data != '' ) {
									jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
								}
							}
						}
					}
					break;
				case 'e':
					jQuery( '#wppa-ss-photoexif-'+mocc ).css('display', '');
					s3 = jQuery( '#wppa-ss-photoexif-'+mocc ).val();
					if ( s3 ) {
						if ( s3.length > 2 ) {
							s3 = s3.replace( '#', 'H' );	// Replace # by H
						}
						if ( s3 != '' ) {
							jQuery( '#wppa-ss-exifopts-'+mocc ).css('display', '');
							if ( wppaLastExif != s3 ) {
								wppaAjaxGetSsExifList( mocc, s3, 'wppa-ss-exifopts-'+mocc );
								wppaLastExif = s3;
							}
							else {
								data = jQuery( '#wppa-ss-exifopts-'+mocc ).val();
								if ( data != null && data != '' ) {
									jQuery( '#wppa-ss-button-'+mocc ).css('display', '');
								}
							}
						}
					}
					break;
			}
			break;
	}

	// Find results
	if ( go ) {
		var url = jQuery( '#wppa-ss-pageurl-'+mocc ).val();
		if ( url.indexOf( '?' ) == -1 ) {
			url += '?';
		}
		else {
			url += '&';
		}
		url += 'occur=1&wppa-supersearch='+s1+','+s2+','+s3+','+data;
		document.location.href = url;
	}
}

// Supersearch function set size of exif/iptc itemlist
function wppaSetIptcExifSize( clas, selid ) {
	var t = jQuery( clas );
	var n = t.length;
	if ( n > 6 ) n = 6;
	if ( n < 2 ) n = 2;
	jQuery( selid ).attr('size', n );
}

function wppaUpdateSearchRoot( text, root ) {
	var items = jQuery( ".wppa-search-root" );
	var i = 0;
	while ( i < items.length ) {
		jQuery( items[i] ).html( text );
		i++;
	}
	items = jQuery( ".wppa-rootbox" );
	i = 0;
	while ( i < items.length ) {
		if ( root ) {
			jQuery( items[i] ).prop('checked', false );
			jQuery( items[i] ).prop('disabled', false );
		}
		else {
			jQuery( items[i] ).prop('checked', true );
			jQuery( items[i] ).prop('disabled', true );
		}
		i++;
	}
	items = jQuery( ".wppa-search-root-id" );
	i = 0;
	while ( i < items.length ) {
		jQuery( items[i] ).val( root );
		i++;
	}
}

function wppaSubboxChange( elm ) {
	if ( jQuery( elm ).prop('checked') ) {
		jQuery( ".wppa-rootbox" ).each(function(index) {
			jQuery(this).prop('checked',true);
		});
	}
}

function wppaClearSubsearch() {
	var items = jQuery( ".wppa-display-searchstring" );
	var i = 0;
	while ( i < items.length ) {
		jQuery( items[i] ).html( '' );
		i++;
	}
	items = jQuery( ".wppa-search-sub-box" );
	i = 0;
	while ( i < items.length ) {
		jQuery( items[i] ).prop('disabled', true );
		i++;
	}
}

function wppaEnableSubsearch() {
	var items = jQuery( ".wppa-search-sub-box" );
	var i = 0;
	while ( i < items.length ) {
		jQuery( items[i] ).removeAttr('disabled' );
		i++;
	}
}

function wppaDisplaySelectedFiles(id,sep) {
	var theFiles = jQuery('#'+id);
	var i = 0;
	var result = '';
	if ( ! sep ) {
		sep = ' ';
	}

	while ( i<theFiles[0].files.length ) {
		result += theFiles[0].files[i].name+sep;
		i++;
	}

	jQuery('#'+id+'-display').val(result);
	jQuery('#'+id+'-display').html(result);
}

function wppaIsEmpty( str ) {
	if ( str == null ) return true;
	if ( typeof( str ) == 'undefined') return true;
	if ( str == '' ) return true;
	if ( str == false ) return true;
	if ( str == 0 ) return true;
}

function wppaGetUploadOptions( yalb, mocc, where, onComplete ) {

	var options = {
		beforeSend: function() {
			jQuery('#progress-'+yalb+'-'+mocc).show();
			jQuery('#bar-'+yalb+'-'+mocc).width('0%');
			jQuery('#message-'+yalb+'-'+mocc).html('');
			jQuery('#percent-'+yalb+'-'+mocc).html('');
		},
		uploadProgress: function(event, position, total, percentComplete) {
			jQuery('#bar-'+yalb+'-'+mocc).css('backgroundColor','#7F7');
			jQuery('#bar-'+yalb+'-'+mocc).width(percentComplete+'%');
			if ( percentComplete < 95 ) {
				jQuery('#percent-'+yalb+'-'+mocc).html(percentComplete+'%');
			}
			else {
				jQuery('#percent-'+yalb+'-'+mocc).html(__( 'Processing...', 'wp-photo-album-plus' ));
			}
		},
		success: function() {
			jQuery('#bar-'+yalb+'-'+mocc).width('100%');
			jQuery('#percent-'+yalb+'-'+mocc).html(__( 'Done!', 'wp-photo-album-plus' ));
			if (wppaUploadButtonText) jQuery('.wppa-upload-button').val(wppaUploadButtonText);
		},
		complete: function(response) {
			if (response.responseText.indexOf(__( 'Upload failed', 'wp-photo-album-plus' ))!=-1) {
				jQuery('#bar-'+yalb+'-'+mocc).css('backgroundColor','#F77');
				jQuery('#percent-'+yalb+'-'+mocc).html(__( 'Upload failed', 'wp-photo-album-plus' ));
				jQuery('#message-'+yalb+'-'+mocc).html( '<span style="font-size: 10px;" >'+response.responseText+'</span>' );
			}
			else {
				jQuery('#message-'+yalb+'-'+mocc).html( '<span style="font-size: 10px;" >'+response.responseText+'</span>' );
				if ( where == 'thumb' || where == 'cover') {
					eval(onComplete);
				}
			}
		},
		error: function( xhr, status, error ) {
			jQuery('#message-'+yalb+'-'+mocc).html( '<span style="color: red;" >'+__( 'Server error.', 'wp-photo-album-plus' )+'</span>' );
			jQuery('#bar-'+yalb+'-'+mocc).css('backgroundColor','#F77');
			jQuery('#percent-'+yalb+'-'+mocc).html(__( 'Upload failed', 'wp-photo-album-plus' ));
			wppaConsoleLog('Error = '+error+', status = '+status );
		}
	};

	return options;
}

// (re)-initialize masonryplus using jQuery().masonry()
function wppaInitMasonryPlus() {

	jQuery('.grid-masonryplus').each(function(){

		var divId 		= jQuery(this).attr('id');
		var mocc 		= divId.substr(5);
		var w 			= wppaGetContainerWidth(mocc) - wppaThumbnailAreaDelta;
		var cnt 		= parseInt( ( w + wppaTfMargin ) / ( wppaThumbSize * 0.75 + wppaTfMargin ) );
		if ( cnt == 0 ) cnt = 1;
		var colWidth 	= w / cnt;

		jQuery(".grid-item").css('visibility', 'visible');
		jQuery(".grid-item-" + mocc).css('width', colWidth + 'px');
		jQuery('#grid-' + mocc ).masonry({
				itemSelector: '.grid-item-' + mocc,
				columnWidth: colWidth,
				fitWidth: true
			});
	});
}

/* Start fullscreen functions */
// Initialize
jQuery(document).ready(function(){

	// Create global buttons if required
	jQuery(document).ready(function(){
		if ( wppaFsPolicy == 'global' ) wppaGlobalFS();
	});

	// Install handlers
	jQuery(window).on("DOMContentLoaded load", wppaFsShow);
	jQuery(document).on("fullscreenchange mozfullscreenchange webkitfullscreenchange msfullscreenchange", wppaFsChange);

});

// Handle fullscreen change event
function wppaFsChange() {

	// Show the right buttons
	wppaFsShow();

	// Re-display lightbox
	wppaOvlShowSame();
}

// Create global fullsize buttons
function wppaGlobalFS() {

	if ( wppaIsIpad ) return false;
	if ( wppaIsSafari ) return false;

	var top = parseInt( wppaGlobalFsIconSize / 4 );
	var rgt = top;
	if ( ! wppaIsMobile && jQuery( '#wpadminbar' ).length > 0 ) {
		top += jQuery( '#wpadminbar' ).height();
	}

	jQuery('body').append(
		'<div' +
			' id="wppa-fulls-btn-1"' +
			' class="wppa-fulls-btn"' +
			' style="position:fixed;top:' + top + 'px;right:' + rgt + 'px;display:none;"' +
			' title="Enter fullscreen"' +
			' onclick="wppaFsOn()"' +
			' >' +
			wppaSvgHtml( 'Full-Screen', wppaGlobalFsIconSize + 'px', true, false, '0', '0', '0', '0' ) +
		'</div>');

	jQuery('body').append(
		'<div' +
			' id="wppa-exit-fulls-btn-1"' +
			' class="wppa-exit-fulls-btn"' +
			' style="position:fixed;top:' + top + 'px;right:' + rgt + 'px;display:none;"' +
			' title="Leave fullscreen"' +
			' onclick="wppaFsOff()"' +
			' >' +
			wppaSvgHtml( 'Exit-Full-Screen', wppaGlobalFsIconSize + 'px', true, false, '0', '0', '0', '0' ) +
		'</div>');

	wppaFsShow();

}

// Switch fullscreen on
function wppaFsOn() {

	var docElm = document.documentElement;
	if (docElm.requestFullscreen) {
		docElm.requestFullscreen();
	}
	else if (docElm.mozRequestFullScreen) {
		docElm.mozRequestFullScreen();
	}
	else if (docElm.webkitRequestFullScreen) {
		docElm.webkitRequestFullScreen();
	}

}

// Switch fullscreen off
function wppaFsOff() {

	if (document.exitFullscreen) {
		document.exitFullscreen();
	}
	else if (document.mozCancelFullScreen) {
		document.mozCancelFullScreen();
	}
	else if (document.webkitCancelFullScreen) {
		document.webkitCancelFullScreen();
	}

}

// Is fullscreen on?
function wppaIsFs() {

	if ( wppaIsIpad ) return false;
	if ( wppaIsSafari ) return false;
	return ( document.fullscreenElement !== null );
}

// Show / hide the right buttons
function wppaFsShow() {

	if ( wppaIsFs() ) {
		jQuery( '.wppa-fulls-btn' ).hide();
		jQuery( '.wppa-exit-fulls-btn' ).show();
	}
	else {
		jQuery( '.wppa-fulls-btn' ).show();
		jQuery( '.wppa-exit-fulls-btn' ).hide();
	}

	if ( wppaOvlOpen && wppaFsPolicy == 'global' && wppaOvlBigBrowse ) {
		jQuery( '#wppa-fulls-btn-1' ).hide();
		jQuery( '#wppa-exit-fulls-btn-1' ).hide();
	}

}


var wppaAudioSeqno;
var wppaAudioOnlyRuns = [];
// Do the audio only
function wppaDoAudioOnly(tagid,urls,seqno,mocc) {

	wppaAudioSeqno = seqno;

	var body;
	var oldurl;
	var newurl = urls[0];

	body = '';
	urls.forEach( function(currentValue, index){
		var t = currentValue.split('.');
		var ext = t[t.length-1];
		body += '<source id="wppa-audio-source-'+index+'-'+mocc+'" src="'+currentValue+'" type="audio/'+ext+'">';
	});
	body += 'Your browser does not support the audio element.';

	// Get current url
	oldurl = jQuery("#wppa-audio-source-0-"+mocc).attr("src");

	// Not running
	if ( oldurl == "" ) {
		wppaAudioOnlyRuns[mocc] = false;
	}

	// Same: stop when playing
	if ( oldurl == newurl && wppaAudioOnlyRuns[mocc] ) {
		document.getElementById("wppa-audioonly-"+mocc).pause();
		wppaAudioOnlyRuns[mocc] = false;
	}

	// Same: resume when stopped
	else if ( oldurl == urls[0] ) {
		document.getElementById("wppa-audioonly-"+mocc).play();
		wppaAudioOnlyRuns[mocc] = seqno;
		wppaShowAudioDesc(seqno,mocc);
	}

	// Different
	else {
		jQuery("#"+tagid).html(body);
		document.getElementById("wppa-audioonly-"+mocc).load();
		document.getElementById("wppa-audioonly-"+mocc).play();
		jQuery(".wppa-audiolabel-"+mocc).css("font-weight","normal");
		jQuery("#audiolabel-"+mocc+"-"+seqno).css("font-weight","bold");
		jQuery("#audiolabel-"+mocc+"-"+(seqno-1)).blur();
		jQuery("#audiolabel-"+mocc+"-"+seqno).blur();

		// Hide previous
		wppaHideAudioDesc(wppaAudioOnlyRuns[mocc],mocc,true);

		wppaAudioOnlyRuns[mocc] = seqno;

		// Show current
		wppaShowAudioDesc(seqno,mocc);
	}
};
function wppaAudioOnlyNext(mocc) {
	jQuery("#audiolabel-"+mocc+"-"+(wppaAudioSeqno+1)).trigger("click");
};

function wppaShowAudioDesc(seqno,mocc) {
	var tagid = 'audiodesc-'+mocc+'-'+seqno;
	jQuery("#"+tagid).show();
};
function wppaHideAudioDesc(seqno,mocc,force) {

	if ( ! seqno ) return; // may be false

	var tagid = 'audiodesc-'+mocc+'-'+seqno;
	if ( force || wppaAudioOnlyRuns[mocc] != seqno ) {
		jQuery("#"+tagid).hide();
	}
};