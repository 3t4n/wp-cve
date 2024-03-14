// wppa-popup.js
//
// Contains popup modules
// Dependancies: wppa.js and default wp $ library
//
var wppaJsPopupVersion = '8.5.03.002';

// Popup of thumbnail images
function wppaPopUp( mocc, elm, id, name, desc, rating, ncom, videohtml, maxsizex, maxsizey ) {

	// Before we start, make sure old popups vanish
	wppaPopDown();

	// Find all we need
	var topDivBig, topDivSmall, leftDivBig, leftDivSmall;
	var heightImgBig, heightImgSmall, widthImgBig, widthImgSmall;
	var imghtml;
	var areaWidth 	= jQuery( '#wppa-thumb-area-'+mocc ).width();
	var namediv 	= name ? '<div id="wppa-name-'+mocc+'" style="display:none; padding:1px;" class="wppa_pu_info">'+name+'</div>' : '';
	var descdiv 	= desc ? '<div id="wppa-desc-'+mocc+'" style="clear:both; display:none; padding:1px;" class="wppa_pu_info">'+desc+'</div>' : '';
	var ratediv 	= rating ? '<div id="wppa-rat-'+mocc+'" style="clear:both; display:none; padding:1px;" class="wppa_pu_info">'+rating+'</div>' : '';
	var ncomdiv 	= ncom ? '<div id="wppa-ncom-'+mocc+'" style="clear:both; display:none; padding:1px;" class="wppa_pu_info">'+ncom+'</div>' : '';
	var popuptext 	= namediv+descdiv+ratediv+ncomdiv;
	var elmCursor 	= jQuery( elm ).css('cursor');
	var target 		= wppaThumbTargetBlank ? ' target="_blank"' : '';

	var href = jQuery(elm).parent().attr('href');

	imghtml = href ? '<a href="'+href+'" >' : '';
	imghtml += videohtml != '' ? videohtml : '<img id="wppa-img-'+mocc+'" src="'+elm.src+'" title="" style="border-width: 0px;" />';
	imghtml += href ? '</a>' : '';
	jQuery( '#wppa-popup-'+mocc ).html( '<div class="wppa-popup" style="background-color:'+wppaBackgroundColorImage+';box-sizing:content-box;text-align:center;">'+imghtml+popuptext+'</div>' );
	jQuery( '.wppa-popup' ).on( 'click', function(){jQuery(elm).trigger('click');return false;});
	jQuery( '.wppa-popup' ).css({cursor:elmCursor});

	// Compute ending sizes
	widthImgBig = parseInt(maxsizex);
	heightImgBig = parseInt(maxsizey);

	// Set width of text fields to width of image
	jQuery( ".wppa-popup" ).css({width:elm.clientWidth});

	// Compute starting coords
	leftDivSmall = parseInt( elm.offsetLeft ) - 7 - 5;
	topDivSmall = parseInt( elm.offsetTop ) - 7 - 2;

	// Is it masonry plus?
	if ( jQuery( '#grid-item-'+mocc+'-'+id ).length ) {
		leftDivSmall += parseInt( jQuery( '#grid-item-'+mocc+'-'+id ).css( 'left' ) ) + 6;
		topDivSmall += parseInt( jQuery( '#grid-item-'+mocc+'-'+id ).css( 'top' ) );
	}

	// Compute starting sizes
	widthImgSmall = parseInt( elm.clientWidth );
	heightImgSmall = parseInt( elm.clientHeight );

	// Compute ending coords
	leftDivBig = leftDivSmall - parseInt( ( widthImgBig - widthImgSmall ) / 2 );
	if ( leftDivBig < 0 ) {
		leftDivBig = 0;
	}
	if ( leftDivBig + widthImgBig + 16 > areaWidth ) {
		leftDivBig = areaWidth - widthImgBig - 16;
	}

	topDivBig = topDivSmall - parseInt( ( heightImgBig - heightImgSmall ) / 2 );
	if ( topDivBig < 0 ) {
		topDivBig = 0;
	}

	// To fix a Chrome bug where a theme class effect is: max-width:100% causing the width not being animated:
	jQuery( '#wppa-img-'+mocc ).css({maxWidth:widthImgBig});

	// Setup starting properties
	jQuery( '#wppa-popup-'+mocc ).css({marginLeft:leftDivSmall,marginTop:topDivSmall});
	jQuery( '#wppa-img-'+mocc ).css({marginLeft:0,marginRight:0,width:widthImgSmall,height:heightImgSmall});

	// Do the animation
	var duration = 500;
	wppaAnimate( '#wppa-popup-'+mocc, {marginLeft:leftDivBig,marginTop:topDivBig}, duration, wppaEasingPopup);
	wppaAnimate( '#wppa-img-'+mocc, {width:widthImgBig,height:heightImgBig}, duration, wppaEasingPopup,
		function(){
			jQuery(this).on('touchend',function(){wppaPopDown()});
			jQuery(this).on('click',function(e){e.stopPropagation})
		});
	wppaAnimate( '.wppa-popup', {width:widthImgBig}, duration, wppaEasingPopup);
	wppaAnimate( '.wppa_pu_info', {width:widthImgBig}, duration, wppaEasingPopup, function(){jQuery( '.wppa_pu_info' ).show()});

	// Hide rightclick optionally
	wppaProtect();
}

// Dismiss all popups
function wppaPopDown() {
	jQuery( '.wppa-popup-frame' ).html( "" );
	return;
}

// Popup of fullsize image
function wppaFullPopUp( mocc, id, url, xwidth, xheight, xname ) {

	var xFactor = screen.width / ( xwidth + 14 );
	var yFactor = screen.height / ( xheight + 80 );
	var factor 	= Math.min( xFactor, yFactor ) * 0.9;

	if ( factor < 1 ) {
		xwidth *= factor;
		xheight *= factor;
	}

	var height 	= xheight+80;
	var width  	= xwidth+14;
	var name 	= '';
	var desc 	= '';
	var elm 	= document.getElementById( 'i-'+id+'-'+mocc );
	if ( elm ) {
		name = elm.alt;
		desc = elm.title;
	}
	var isPdf = url.substr( url.length - 4, url.length ) == '.pdf';

	var d = new Date();
	var time = d.getTime();

	// Open new browser window
	var wnd = window.open( '', 'Print-'+time, 'width='+width+', height='+height+', location=no, resizable=no, menubar=yes ' );

	// Create the html
	var result =
	'<html>' +
		'<head>' +
			'<style>body{margin:0;padding:6px;background-color:'+wppaBackgroundColorImage+';text-align:center;}</style>' +
			'<title>'+name+'</title>' +
			'<script>function wppa_print() {' +
				'document.getElementById( "wppa_printer" ).style.visibility="hidden";' +
				'document.getElementById( "wppa_download" ).style.visibility="hidden";' +
				'window.print();' +
			'}</script>' +
		'</head>' +
		'<body>' +
			'<div style="width:'+xwidth+'px;" >';
				if ( isPdf ) {
					result += '<iframe src="'+url+'" style="padding-bottom:6px;width:100%;height:'+(height-60)+'px;" ></iframe><br />';
				}
				else {
					result += '<img src="'+url+'" style="width:'+xwidth+'px;height:'+xheight+'px;padding-bottom:6px;" /><br />';
				}
				result += '<div style="text-align:center">'+xname+'</div>';
				if ( ! isPdf ) { // Pdf has its own print and download buttons
					result +=
					'<a href="'+url+'" download="'+xname+'" ><img src="'+wppaImageDirectory+'download.png" id="wppa_download" title="Download" style="position:absolute; top:6px; left:'+(xwidth-66)+'px; background-color:'+wppaBackgroundColorImage+'; padding: 2px; cursor:pointer;" /></a>' +
					'<img src="'+wppaImageDirectory+'printer.png" id="wppa_printer" title="Print" style="position:absolute; top:6px; left:'+(xwidth-30)+'px; background-color:'+wppaBackgroundColorImage+'; padding: 2px; cursor:pointer;" onclick="wppa_print();" />';
				}
			result += '</div>' +
		'</body>' +
	'</html>';
	wnd.document.write( result );

	// Hide rightclick optionally
	wppaProtect();
}
