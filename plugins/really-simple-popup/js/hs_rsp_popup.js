//Origonal image values
var hsRspOrigImgWidth = null; //will contain pixel value
var hsRspOrigImgHeight = null; //will contain pixel value
var hsRspDefaultAnimation = 'fade'; //'fade' || 'slide-down'

//Image absolute min pixel values
var hsRspMinImgWidth = 150; //pixels
var hsRspMinImgHeight = 150; //pixels

//Iframe default width and height
var hsRspIframeWidth = -1; //pixels
var hsRspIframeHeight = -1; //pixels

//Hidden div
var hsRspDivWidth = -1; //pixels
var hsRspDivHeight = -1; //pixels

//Modal settings
var hsRspModalMarginTop = 90; //pixels
var hsRspMaxModalWidth = 90; //percentage
var hsRspMaxModalHeight = 90; //percentage

var hsRspSetWidth = null;
var hsRspSetHeight = null;

//Other settings
var hsRspResizeTimer;
var hsRspCurrentType = null; // current content type: ['image' | 'iframe' | 'html']
var hsRspImgGallery = []; //To hold all the popup images in the page
var hsRspIsPopupOpen = false; //Is the popup currently open?
var hsRspDisableResizeOnWindowChange = false; //When the window is resized, should popup scale?




jQuery(document).ready(function(){
	/* Dont run the script if its' been loaded as part of an ajax request */
	if(jQuery('#hs-rsp-popup-bg').length == 0){
		_hsRspSetupThumbnails();
		_hsRspSetupGallery();
		_hsRspSetupEventHandlers();
	}
});

//Adds the load spinner to the dom, centers it and hides the image being loaded
function hsRspShowLoader(){
	_hsRspSetupPopup();
	//Hide the popup while we show the loader
	jQuery('#hs-rsp-image-wrap').css('visibility', 'hidden');

	//Remove image title.
	if(jQuery('#hs-rsp-image-wrap p').length == 1) jQuery('#hs-rsp-image-wrap p').remove();

	//Add loader if it doesn't exist on the page
	if(jQuery('#hs-rsp-image-loader').length == 0) jQuery('#hs-rsp-popup-bg').append('<div id="hs-rsp-image-loader"></div>');

	//Center the loader
	jQuery('#hs-rsp-image-loader').css({
		left: _hsRspCalcLeftMargin(32),
		top: _hsRspCalcTopMargin(32)
	});
}

//Removes the spiner from the dom
function hsRspCloseLoader(){
	if(jQuery('#hs-rsp-image-loader').length == 1) jQuery('#hs-rsp-image-loader').remove();
}

//Function will load an image into the popup div
function hsRspLoadImage(imgurl, title){
    hsRspDisableResizeOnWindowChange = false;

	//If image exists, remove before loading in the new one
	if(jQuery('#hs-rs-big-img').length == 1){
		jQuery('#hs-rs-big-img').remove();
	}

	if(title !== undefined && title !== '' && jQuery('#hs-rsp-image-wrap .popup-title').length == 0){
		jQuery('#hs-rsp-image-wrap').append('<img id="hs-rs-big-img" src="' + imgurl + '" alt="' + imgurl + '"/>');
		jQuery('#hs-rsp-image-wrap img').load(function(){
			jQuery(this).after('<p class="popup-title pimage">' + title + '</p>');
		});
	}else{
		jQuery('#hs-rsp-image-wrap').append('<img id="hs-rs-big-img" src="' + imgurl + '" alt="' + imgurl + '"/>');
	}

	/* image load event*/
	jQuery('#hs-rs-big-img').load(function(){

		//Record orig image sizes
		hsRspOrigImgWidth = jQuery('#hs-rs-big-img').width();
		hsRspOrigImgHeight = jQuery('#hs-rs-big-img').height();


		var img = {};
		img = _hsRspScalePopup(jQuery('#hs-rs-big-img'), hsRspMinImgWidth, hsRspMinImgHeight, hsRspOrigImgWidth, hsRspOrigImgHeight);

		//If we've got a gallery of images, include the left and right arrows
		if(hsRspImgGallery.length > 1){
			if(jQuery('#hsrsp-leftarrow').length == 0){
				jQuery('#hs-rsp-image-wrap').append('<a id="hsrsp-leftarrow" href="#hsrsp-left">Previous</a>');

				//Setup click events for the newly created buttons
				jQuery('#hsrsp-leftarrow').click(function(event){
					event.preventDefault();
					_hsRspPreviousImg();
				});
			}

			if(jQuery('#hsrsp-rightarrow').length == 0){
				jQuery('#hs-rsp-image-wrap').append('<a id="hsrsp-rightarrow" href="#hsrsp-right">Next</a>');

				jQuery('#hsrsp-rightarrow').click(function(event){
					event.preventDefault();
					_hsRspNextImg();
				});
			}

			//Position arrows
			jQuery('#hsrsp-leftarrow').css({
				top: parseInt((img.iheight/2) - ((64 - 10)/2)), //32px is arrow height so to center use half - 10px img padding
				left: 10 //10px padding round the img
			});

			jQuery('#hsrsp-rightarrow').css({
				top: parseInt((img.iheight/2) - ((64 - 10)/2)), //32px is arrow height so to center use half - 10px img padding
				left: parseInt( (img.iwidth) - 54 ) //32px is the width of the arrow - 5px img padding (22px)
			});
		}

		_hsRspShowPopup(img.iwidth, img.iheight);
	});
}

//Function will load an image into the popup div
function hsRspLoadFixedImage(imgurl, title, height, width){
    hsRspDisableResizeOnWindowChange = true;

    hsRspSetWidth = width;
    hsRspSetHeight = height;

	//If image exists, remove before loading in the new one
	if(jQuery('#hs-rs-big-img').length == 1){
		jQuery('#hs-rs-big-img').remove();
	}


	//Add title after image
	if(title !== undefined && title !== '' && jQuery('#hs-rsp-image-wrap .popup-title').length == 0){
		jQuery('#hs-rsp-image-wrap').append('<img id="hs-rs-big-img" src="' + imgurl + '" alt="' + imgurl + '"/>');
		jQuery('#hs-rsp-image-wrap img').load(function(){
			jQuery(this).after('<p class="popup-title pimagef">' + title + '</p>');
		});

	}else{
		jQuery('#hs-rsp-image-wrap').append('<img id="hs-rs-big-img" src="' + imgurl + '" alt="' + imgurl + '"/>');
	}

	/* image load event*/
	jQuery('#hs-rs-big-img').load(function(){

		//Record orig image sizes
		hsRspOrigImgWidth = jQuery('#hs-rs-big-img').width();
		hsRspOrigImgHeight = jQuery('#hs-rs-big-img').height();


		var img = {};


		//If we've got a gallery of images, include the left and right arrows
		if(hsRspImgGallery.length > 1){
			if(jQuery('#hsrsp-leftarrow').length == 0){
				jQuery('#hs-rsp-image-wrap').append('<a id="hsrsp-leftarrow" href="#hsrsp-left">Previous</a>');

				//Setup click events for the newly created buttons
				jQuery('#hsrsp-leftarrow').click(function(event){
					event.preventDefault();
					_hsRspPreviousImg();
				});
			}

			if(jQuery('#hsrsp-rightarrow').length == 0){
				jQuery('#hs-rsp-image-wrap').append('<a id="hsrsp-rightarrow" href="#hsrsp-right">Next</a>');

				jQuery('#hsrsp-rightarrow').click(function(event){
					event.preventDefault();
					_hsRspNextImg();
				});
			}

			//Position arrows
			jQuery('#hsrsp-leftarrow').css({
				top: parseInt((height/2) - ((64 - 10)/2)), //32px is arrow height so to center use half - 10px img padding
				left: 10 //10px padding round the img
			});

			jQuery('#hsrsp-rightarrow').css({
				top: parseInt((height/2) - ((64 - 10)/2)), //32px is arrow height so to center use half - 10px img padding
				left: parseInt( (width) - 54 ) //32px is the width of the arrow - 10px img padding (22px)
			});
		}

		_hsRspShowPopup(width, height);
	});
}

function hsRspLoadIframe(iframurl, title){
    hsRspDisableResizeOnWindowChange = false;
	jQuery('#hs-rsp-image-wrap').append('<iframe frameborder="0" id="hs-rsp-iframe" src="' + iframurl + '" name="hs-rsp-iframe" vspace="0" hspace="0" allowtransparency="true"></iframe>');
	if(title !== undefined && title !== '' && jQuery('#hs-rsp-image-wrap .popup-title').length == 0) jQuery('#hs-rsp-image-wrap').append('<p class="popup-title">' + title + '</p>');

	var ifr = {};
	ifr = _hsRspScalePopup(jQuery('#hs-rsp-iframe'), hsRspMinImgWidth, hsRspMinImgHeight, hsRspIframeWidth, hsRspIframeHeight);

	//Set iframe width and height
	jQuery('#hs-rsp-iframe').attr({
		width: ifr.iwidth,
		height: ifr.iheight
	});

	/*frame load*/
	jQuery('#hs-rsp-iframe').load(iframurl, function(){
			_hsRspShowPopup(ifr.iwidth, ifr.iheight);
	});
}

function hsRspLoadIframeFixedWidth(iframurl, title, height, width){
    hsRspDisableResizeOnWindowChange = true;

    hsRspSetWidth = width;
    hsRspSetHeight = height;

	jQuery('#hs-rsp-image-wrap').append('<iframe frameborder="0" id="hs-rsp-iframe" src="' + iframurl + '" name="hs-rsp-iframe" vspace="0" hspace="0" allowtransparency="true"></iframe>');
	if(title !== undefined && title !== '' && jQuery('#hs-rsp-image-wrap .popup-title').length == 0) jQuery('#hs-rsp-image-wrap').append('<p class="popup-title">' + title + '</p>');

	//Set iframe width and height
	jQuery('#hs-rsp-iframe').attr({
		width: width,
		height: height
	});

	/*frame load*/
	jQuery('#hs-rsp-iframe').load(iframurl, function(){
			_hsRspShowPopup(width, height);
	});
}

function hsRspLoadDiv(linktarget, title){
    hsRspDisableResizeOnWindowChange = false;

	//If link target starts with a #hash assume its an id to another element
	if(/\#.*/.test(linktarget)){
		jQuery('#hs-rsp-image-wrap').append('<div id="hs-rsp-div">' +  jQuery(linktarget).html() + '</div>');
		if(title !== undefined && title !== '' && jQuery('#hs-rsp-image-wrap .popup-title').length == 0) jQuery('#hs-rsp-image-wrap').append('<p class="popup-title">' + title + '</p>');

		var div = {};
		div = _hsRspScalePopup(jQuery('#hs-rsp-div'), hsRspMinImgWidth, hsRspMinImgHeight, hsRspDivWidth, hsRspDivHeight);

		//Set iframe width and height
		jQuery('#hs-rsp-div').css({
			width: div.iwidth,
			height: div.iheight
		});

		_hsRspShowPopup(div.iwidth, div.iheight);
	}else{
		//Load in the remote content in an iframe
		this.hsRspCurrentType = 'iframe';
		hsRspLoadIframe(linktarget, title);
	}
}


//hsRspLoadFixedWidthDiv(jQuery(this).attr('href'), divTitle, jQuery(this).attr('popupheight'), jQuery(this).attr('popupheight'));
function hsRspLoadFixedWidthDiv(linktarget, title, height, width){
    hsRspDisableResizeOnWindowChange = true;

    hsRspSetWidth = width;
    hsRspSetHeight = height;

	//If link target starts with a #hash assume its an id to another element
	if(/\#.*/.test(linktarget)){
		jQuery('#hs-rsp-image-wrap').append('<div id="hs-rsp-div">' +  jQuery(linktarget).html() + '</div>');
		if(title !== undefined && title !== '' && jQuery('#hs-rsp-image-wrap .popup-title').length == 0) jQuery('#hs-rsp-image-wrap').append('<p class="popup-title">' + title + '</p>');

		_hsRspShowPopup(width, height);
	}else{
		//Load in the remote content in an iframe
		this.hsRspCurrentType = 'iframe';
		hsRspLoadIframeFixedWidth(linktarget, title, height, width);
	}
}

//If the modal exists, close it.
function hsRspClosePopup(){
	if(jQuery('#hs-rsp-popup-bg').length == 1){
		jQuery('#hs-rsp-popup-bg').fadeOut('fast', function(){
			jQuery(this).remove();
		});

		//Clear current type
		hsRspCurrentType = null;

		//clear out image details
		hsRspOrigImgWidth = null;
		hsRspOrigImgHeight = null;

		hsRspIsPopupOpen = false;
	}
}

/*************************************
		  Private functions
*************************************/

//Sets up the overlay background, main popup div and close button
function _hsRspSetupPopup(){
	//add overlay
	if(jQuery('#hs-rsp-popup-bg').length == 0) jQuery('body').append('<div id="hs-rsp-popup-bg"></div>');

	//add image in div (use the the full size image)
	if(jQuery('#hs-rsp-image-wrap').length == 0) jQuery('#hs-rsp-popup-bg').append('<div id="hs-rsp-image-wrap"></div>');

	//Add close button
	if(jQuery('#hs-rsp-close').length == 0) jQuery('#hs-rsp-image-wrap').append('<a id="hs-rsp-close" href="#hs-rsp-close" title="click to close">Close</a>');
}

//Display the popup content
function _hsRspShowPopup(imgWidth, imgHeight){
	if(jQuery('#hs-rsp-image-wrap').length == 1){
		jQuery('#hs-rsp-image-wrap').css({
			left: _hsRspCalcLeftMargin(imgWidth) + 'px'
		});

		jQuery('#hs-rsp-image-wrap img').prop({
			width: imgWidth,
			height: imgHeight
		});

		//If we're already showing the popup we dont want to do the fancy 'drop-down' animation
		if(hsRspIsPopupOpen){
			jQuery('#hs-rsp-image-wrap').css({
				marginTop:  hsRspModalMarginTop
			});
		}else{ //no popup, drop down
			jQuery('#hs-rsp-image-wrap').css({
				marginTop:  ((imgHeight + 60) * -1)
			});
		}

		hsRspCloseLoader();

		if(hsRspDefaultAnimation == 'slide-down'){
			jQuery('#hs-rsp-image-wrap').fadeIn(100, function(){
				jQuery('#hs-rsp-image-wrap').css('visibility', 'visible')

				if(!hsRspIsPopupOpen) jQuery('#hs-rsp-image-wrap').animate({marginTop: hsRspModalMarginTop}, 200);
				hsRspIsPopupOpen = true;
			});
		}else{
			jQuery('#hs-rsp-image-wrap')
				.css({'display': 'none',
					  'visibility': 'visible',
					  'marginTop': hsRspModalMarginTop})
				.fadeIn(200, function(){
					//jQuery('#hs-rsp-image-wrap').css('visibility', 'visible');

					//jQuery('#hs-rsp-image-wrap').animate({marginTop: hsRspModalMarginTop}, 200);
					hsRspIsPopupOpen = true;
				});
		}
	}
}

//Scan the page for images and add the .hs-rsp-popup to all supported ones
function _hsRspSetupThumbnails(){
	var thumbnails = jQuery("a:has(img)").not(".no-hsrsp-popup").filter( function() {
        if(jQuery(this).find('img.no-hsrsp-popup').length == 1){
            return false;
        }else{
		  return /\.(jpe?g|png|gif|bmp)$/i.test(jQuery(this).attr('href'));
        }
	});

	thumbnails.addClass('hs-rsp-popup');
}

//Grabs all normal popup images and puts them in a gallery
function _hsRspSetupGallery(){
	hsRspImgGallery = jQuery('.hs-rsp-popup').not('.iframe, .hiddendiv, .hs-rsp-nogallery');

    //IF we have a no-gallery, disable it completly
    
    if(jQuery('.hs-rsp-nogallery').length >= 1){
        hsRspImgGallery = [];
    }
}

//Registers all the relevent event handlers
function _hsRspSetupEventHandlers(){
	//If the image is being shown, handle a resize
	jQuery(window).resize(function(){
		if(jQuery('#hs-rsp-image-wrap').length == 1){
			//Set a timeout to give the user time to resize
			clearTimeout(hsRspResizeTimer);
			hsRspResizeTimer = setTimeout(_hsRspAnimateResize, 100);
		}
	});

	//Individual image click events
	jQuery('.hs-rsp-popup').click(function(event){
		event.preventDefault();

		//Load the popup, note: we need to do different things before showing the popup - depending on the type.
		if(jQuery(this).hasClass('iframe')){
			hsRspCurrentType = 'iframe';
			hsRspShowLoader();
			var iframeTitle = jQuery(this).attr('title');

			if(jQuery(this).attr('data-popupheight') !== undefined && jQuery(this).attr('data-popupheight') !== undefined){
				hsRspLoadIframeFixedWidth(jQuery(this).attr('href'), iframeTitle, jQuery(this).attr('data-popupheight'), jQuery(this).attr('data-popupwidth'));
			}else{
				hsRspLoadIframe(jQuery(this).attr('href'), iframeTitle);
			}


		}else if(jQuery(this).hasClass('hiddendiv')){
			hsRspCurrentType = 'div';
			hsRspShowLoader();
			var divTitle = jQuery(this).attr('title');

			if(jQuery(this).attr('data-popupheight') !== undefined && jQuery(this).attr('data-popupheight') !== undefined){
				hsRspLoadFixedWidthDiv(jQuery(this).attr('href'), divTitle, jQuery(this).attr('data-popupheight'), jQuery(this).attr('data-popupwidth'));
			}else{
				hsRspLoadDiv(jQuery(this).attr('href'), divTitle);
			}


		}else{
			hsRspCurrentType = 'image';
			hsRspShowLoader();
			var imgTitle = jQuery(this).find('img').attr('alt');
			hsRspLoadImage(jQuery(this).attr('href'), imgTitle);

			if(jQuery(this).attr('data-popupheight') !== undefined && jQuery(this).attr('data-popupheight') !== undefined){
				hsRspLoadFixedImage(jQuery(this).attr('href'), imgTitle, jQuery(this).attr('data-popupheight'), jQuery(this).attr('data-popupwidth'));
			}else{
				hsRspLoadImage(jQuery(this).attr('href'), imgTitle);
			}
		}
	});

	//Close the popup when the close link or the overlay is clicked
	jQuery('body').on('click', '#hs-rsp-popup-bg, #hs-rsp-image-wrap', function(event){
		if(event.srcElement == event.currentTarget){
			event.preventDefault();
			hsRspClosePopup();
		}
	});


	jQuery('body').on('click', '#hs-rsp-close', function(event){
		event.preventDefault();
		hsRspClosePopup();
	});


    //Setup event handler for the shortcut keys
	jQuery(document).keyup(function(e){
		//If left or right arrow is pressed.
		if(e.which == 37 || e.which == 39){
			var img = jQuery('#hs-rs-big-img');

			/* If we have mutiple images and the left arrow is pushed while the popup is open
			   show the 'previous' image */
	        if(hsRspImgGallery.length > 1 && e.which == 37 && img.length == 1){ //Left arrow
	        	_hsRspPreviousImg();
	        }

	        /* If we have mutiple images and the right arrow is pushed while the popup is open
			   show the 'next' image */
	        if(hsRspImgGallery.length > 1 && e.which == 39 && img.length == 1){ //Right arrow
	        	_hsRspNextImg();
	        }
	    }

        //Escape key - close popup
        if(e.which == 27){
            hsRspClosePopup();
        }
    });
}


function _hsRspPreviousImg(){
	var img = jQuery('#hs-rs-big-img');
	var curIndex = -1;
	var newImgIndex = -1;
	var newImg = null;
	var prevImageLink = null;

	//find out where we are in the gallery array
	for(var i = 0; i < hsRspImgGallery.length; i++){
		if(jQuery(hsRspImgGallery[i]).attr('href') == img.attr('src')){
			curIndex = i;
		}
	}

	/* We're moving left so we want the previous image unless we're on the first
	   in which case we want the last */
	if(curIndex == 0){
		newImgIndex = hsRspImgGallery.length -1;
	}else if(curIndex > 0){ //Move back one
		newImgIndex = --curIndex;
	}

	newImg = jQuery(hsRspImgGallery[newImgIndex]).attr('href');
	jQuery('#hs-rsp-image-wrap').fadeOut('fast', function(){
		//done
		jQuery(this).css({
			visibility: 'hidden',
			display: 'block'
		});
		hsRspShowLoader();

		prevImageLink = jQuery('a[href="' + newImg + '"]');
		if(jQuery(prevImageLink).attr('data-popupheight') !== undefined && jQuery(prevImageLink).attr('data-popupheight') !== undefined){
			hsRspLoadFixedImage(newImg, jQuery(prevImageLink).find('img').attr('alt'), jQuery(prevImageLink).attr('data-popupheight'), jQuery(prevImageLink).attr('data-popupwidth'));
		}else{
			hsRspLoadImage(newImg, jQuery(prevImageLink).find('img').attr('alt'));
		}
	});
}

function _hsRspNextImg(){
	var img = jQuery('#hs-rs-big-img');
	var curIndex = -1;
	var newImgIndex = -1;
	var newImg = null;
	var nextImgLink = null;

	//find out where we are in the gallery array
	for(var i = 0; i < hsRspImgGallery.length; i++){
		if(jQuery(hsRspImgGallery[i]).attr('href') == img.attr('src')){
			curIndex = i;
		}
	}

	/* We're moving right so we want the next image unless we're on the last image
	   in which case we want the first */
	if(curIndex == hsRspImgGallery.length -1){
		newImgIndex = 0;
	}else if(curIndex < hsRspImgGallery.length -1){ //Move forward one
		newImgIndex = ++curIndex;
	}

	newImg = jQuery(hsRspImgGallery[newImgIndex]).attr('href');
	jQuery('#hs-rsp-image-wrap').fadeOut('fast', function(){
		//done
		jQuery(this).css({
			visibility: 'hidden',
			display: 'block'
		});
		hsRspShowLoader();

		nextImgLink = jQuery('a[href="' + newImg + '"]');
		if(jQuery(nextImgLink).attr('data-popupheight') !== undefined && jQuery(nextImgLink).attr('data-popupheight') !== undefined){
			hsRspLoadFixedImage(newImg, jQuery(nextImgLink).find('img').attr('alt'), jQuery(nextImgLink).attr('data-popupheight'), jQuery(nextImgLink).attr('data-popupwidth'));
		}else{
			hsRspLoadImage(newImg, jQuery(nextImgLink).find('img').attr('alt'));
		}
	});
}

//This function is called on a window resize
function _hsRspAnimateResize(){
	switch(hsRspCurrentType){
		case 'image':



            if(hsRspDisableResizeOnWindowChange){
                var img = {"iwidth": hsRspSetWidth, "iheight": hsRspSetHeight};
            }else{
                var img = _hsRspScalePopup(jQuery('#hs-rs-big-img'), hsRspMinImgWidth, hsRspMinImgHeight, hsRspOrigImgWidth, hsRspOrigImgHeight);
            }

			//Resize and reposition image
			jQuery('#hs-rs-big-img').animate({width: img.iwidth, height: img.iheight}, 100, function(){
				//Put the image into the right place
				jQuery('#hs-rsp-image-wrap').animate({left: _hsRspCalcLeftMargin(jQuery(this).width())}, 50, function(){
					//Position arrows - if they exist
					if(jQuery('#hsrsp-leftarrow').length == 1){
						jQuery('#hsrsp-leftarrow').css({
							top: parseInt((img.iheight/2) - ((64 - 10)/2)), //32px is arrow height so to center use half - 10px img padding
							left: 10 //10px padding round the img
						});
					}

					if(jQuery('#hsrsp-rightarrow').length == 1){
						jQuery('#hsrsp-rightarrow').css({
							top: parseInt((img.iheight/2) - ((64 - 10)/2)), //32px is arrow height so to center use half - 10px img padding
							left: parseInt( (img.iwidth) - 54 ) //32px is the width of the arrow - 10px img padding
						});
					}
				});
			});
			break;
		case 'iframe':
            if(hsRspDisableResizeOnWindowChange){
                var img = {"iwidth": hsRspSetWidth, "iheight": hsRspSetHeight};
            }else{
                var img = _hsRspScalePopup(jQuery('#hs-rsp-iframe'), hsRspMinImgWidth, hsRspMinImgHeight, hsRspIframeWidth, hsRspIframeHeight);
            }

			//Resize and reposition iframe
			jQuery('#hs-rsp-iframe').animate({width: img.iwidth, height: img.iheight}, 100, function(){
				//Put the iframe into the right place
				jQuery('#hs-rsp-image-wrap').animate({left: _hsRspCalcLeftMargin(jQuery(this).width())}, 50, function(){
					//done
				});
			});
			break;
		case 'div':
            if(hsRspDisableResizeOnWindowChange){
                var div = {"iwidth": hsRspSetWidth, "iheight": hsRspSetHeight};
            }else{
                var div = _hsRspScalePopup(jQuery('#hs-rsp-div'), hsRspMinImgWidth, hsRspMinImgHeight, hsRspDivWidth, hsRspDivHeight);
            }

			//Resize and reposition div
			jQuery('#hs-rsp-div').animate({width: div.iwidth, height: div.iheight}, 100, function(){
				//Put the div into the right place
				jQuery('#hs-rsp-image-wrap').animate({left: _hsRspCalcLeftMargin(jQuery(this).width())}, 50, function(){
					//done
				});
			});
			break;
	}
}

//Calculates the starting point for the image on the left to center it
function _hsRspCalcLeftMargin(imgWidth){
	//Half of the screen minux half of the width of the imgage
	var halfScreenPoint = parseInt(parseInt(jQuery(window).width()) / 2);
	var leftStartPoint = parseInt(halfScreenPoint - (imgWidth / 2));

	return leftStartPoint;
}

//Calculates the starting point for the image on top to centre it
function _hsRspCalcTopMargin(imgHeight){
	//Half of the screen minux half of the height of the imgage
	var halfScreenPoint = parseInt(parseInt(jQuery(window).height()) / 2);
	var topStartPoint = parseInt(halfScreenPoint - (imgHeight / 2));

	return topStartPoint;
}

//Calculates the correct sizes for the popup
function _hsRspScalePopup(elem, minWidth, minHeight, defaultWidth, defaultHeight){
	var popupWidth, popupWidth = 0; //Current image size
	var maxPopupWidth, maxPopupHeight = 0; //Maxium size image can be
	var pageWidth, pageHeight = 0; //The current body width and height
	var newWidth, newHeight = 0; //the rescaled size
	var resizeFactor = 0; //the amount we're reducing by

	//Page width and height
	pageWidth = jQuery(window).width();
	pageHeight = jQuery(window).height();

	//extract image width and height
	popupWidth = elem.width();
	popupHeight = elem.height();

	//Calculate max and min height and width
	maxPopupWidth = parseInt(pageWidth * (hsRspMaxModalWidth / 100));
	maxPopupHeight = parseInt((pageHeight - hsRspModalMarginTop) * (hsRspMaxModalHeight / 100));

	//If defaults are at -1, keep at max width and height
	if(defaultWidth == -1 && defaultHeight == -1){
		newHeight = maxPopupHeight;
		newWidth = maxPopupWidth;
	}else if(popupWidth > maxPopupWidth || popupHeight > maxPopupHeight){ //Scale down iframe if required.
		if(popupHeight > popupWidth || popupHeight > maxPopupHeight){
			newHeight = maxPopupHeight;

			if(newHeight < hsRspMinImgHeight){
				newHeight = hsRspMinImgHeight;
			}

			resizeFactor = parseFloat(newHeight / popupHeight);
			newWidth = parseFloat(resizeFactor * popupWidth);
			newWidth = parseInt(Math.ceil(newWidth));
		}else{
			newWidth = maxPopupWidth;

			//Don't go below the origonal iframe width
			if(newHeight < hsRspMinImgWidth){
				newHeight = hsRspMinImgWidth;
			}

			resizeFactor = parseFloat(newWidth/popupWidth);
			newHeight = parseFloat(resizeFactor * popupHeight);
			newHeight = parseInt(Math.ceil(newHeight));
		}
	}else{
		//scale up if were lower than the origonal dimensions
		if(defaultHeight != null || defaultWidth != null){

			//If height is greater than the width, scale the width
			if(popupHeight > popupWidth && popupHeight < maxPopupHeight){
				newHeight = maxPopupHeight;

				//Don't go over the origonal height
				if(newHeight > defaultHeight){
					newHeight = defaultHeight;
				}

				resizeFactor = parseFloat(newHeight / popupHeight);
				newWidth = parseFloat(resizeFactor * popupWidth);
				newWidth = parseInt(Math.ceil(newWidth));
			}else{ //scale the height
				newWidth = maxPopupWidth;

				//Don't go over the origonal width
				if(newWidth > defaultWidth){
					newWidth = defaultWidth;
				}

				resizeFactor = parseFloat(newWidth / popupWidth);
				newHeight = parseFloat(resizeFactor * popupHeight);
				newHeight = parseInt(Math.ceil(newHeight));
			}
		}else{ //stick with the current sizw
			newWidth = popupWidth;
			newHeight = popupHeight;
		}
	}

	//if hidden div with inline content, auto...
	if(jQuery(elem).attr('id') == "hs-rsp-div"){
		newWidth = elem.width();
		newHeight = elem.height();

		if(popupWidth > maxPopupWidth || popupHeight > maxPopupHeight){ //Scale down iframe if required.
			if(popupHeight > popupWidth || popupHeight > maxPopupHeight){
				newHeight = maxPopupHeight;

				if(newHeight < maxPopupHeight){
					newHeight = maxPopupHeight;
				}

				resizeFactor = parseFloat(newHeight / popupHeight);
				newWidth = parseFloat(resizeFactor * popupWidth);
				newWidth = parseInt(Math.ceil(newWidth));
			}else{
				newWidth = maxPopupWidth;

				//Don't go below the origonal iframe width
				if(elem.height() < maxPopupHeight ){
					newHeight = maxPopupHeight;
				}else{
					resizeFactor = parseFloat(newWidth/popupWidth);
					newHeight = parseFloat(resizeFactor * popupHeight);
					newHeight = parseInt(Math.ceil(newHeight));
				}
				elem.css('overflow', 'scroll');
			}
		}
	}

	var popup = {};
	popup.iwidth = newWidth;
	popup.iheight = newHeight;

	return popup;
}