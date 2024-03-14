// wppa-zoom.js
//
// contains wppa functions for simple zoomable photos
//
var wppaJsZoomVersion = '8.5.03.009';

// The main proccedure
function wppaDoZoomPan(mocc, xdata) {

	// Store the data
	wppaZoomData[mocc] = xdata;

	// Update data with image
	var data 	= update(mocc,{image:document.getElementById( "wppa-" + xdata.itemId )});

	// Find additional data
	data.manual 			= false;
	data.deltaX 			= 0;
	data.deltaY 			= 0;
	data.deltaFactor 		= 1.0;
	data.busy 				= false;
	data.div 				= jQuery( "#wppa-pan-div-" + data.itemId );
	data.canvas 			= document.getElementById( "wppa-pan-canvas-" + data.itemId );
	data.pctldiv 			= jQuery( "#wppa-pctl-div-" + data.itemId );
	data.left 				= jQuery( "#wppa-pctl-left-" + data.itemId );
	data.right 				= jQuery( "#wppa-pctl-right-" + data.itemId );
	data.up 				= jQuery( "#wppa-pctl-up-" + data.itemId );
	data.down 				= jQuery( "#wppa-pctl-down-" + data.itemId );
	data.zoomin 			= jQuery( "#wppa-pctl-zoomin-" + data.itemId );
	data.zoomout 			= jQuery( "#wppa-pctl-zoomout-" + data.itemId );
	data.prev 				= jQuery( "#wppa-pctl-prev-" + data.itemId );
	data.next 				= jQuery( "#wppa-pctl-next-" + data.itemId );
	data.exit 				= jQuery( "#wppa-exit-btn-2" );
	data.canvasWidth 		= data.width;
	data.canvasHeight 		= data.height;
	data.savedCanvasX 		= 0;
	data.savedCanvasY 		= 0;
	data.fromHeight 		= data.image.height;
	data.fromWidth 			= data.image.width;
	data.imageRatio 		= data.image.width / data.image.height;
	data.fromX 				= 0;
	data.fromY 				= 0;
	data.centerX 			= data.fromX + data.fromWidth / 2;
	data.centerY 			= data.fromY + data.fromHeight / 2;
	data.ctx 				= null;
	data.buttondown 		= false;
	data.enableManual 		= true;
	data.ctrlbarUpdate 		= true;
	data.pause 				= false;
	data.time 				= 0;
	data.oldcw 				= 0;

	// Install event listeners
	data.right.on("touchstart", function(e){rightDown(mocc, e)});
	data.right.on("touchend", function(e){buttonUp(mocc, e)});
	data.left.on("touchstart", function(e){leftDown(mocc, e)});
	data.left.on("touchend", function(e){buttonUp(mocc, e)});
	data.up.on("touchstart", function(e){upDown(mocc, e)});
	data.up.on("touchend", function(e){buttonUp(mocc, e)});
	data.down.on("touchstart", function(e){downDown(mocc, e)});
	data.down.on("touchend", function(e){buttonUp(mocc, e)});
	data.zoomin.on("touchstart", function(e){plusDown(mocc, e)});
	data.zoomin.on("touchend", function(e){buttonUp(mocc, e)});
	data.zoomout.on("touchstart", function(e){minDown(mocc, e)});
	data.zoomout.on("touchend", function(e){buttonUp(mocc, e)});

	data.canvas.addEventListener("touchstart", function(e){canvasDown(mocc, e)});
	data.canvas.addEventListener("touchmove", function(e){canvasMove(mocc, e)});
	data.canvas.addEventListener("touchend", function(e){canvasUp(mocc,e)});
	data.canvas.addEventListener("mousedown", function(e){canvasDown(mocc, e)});
	data.canvas.addEventListener("mousemove", function(e){canvasMove(mocc, e)});
	data.canvas.addEventListener("mouseup", function(e){canvasUp(mocc, e)});
	data.canvas.addEventListener("mouseleave", function(e){canvasLeave(mocc, e)});
	data.canvas.addEventListener("mouseenter", function(e){canvasEnter(mocc, e)});

	document.getElementById("wppa-pan-canvas-" + data.itemId ).addEventListener("wheel", function(e){onDivWheel(mocc, e)});

	data.right.on("mousedown", function(e){rightDown(mocc, e)});
	data.right.on("mouseup", function(e){buttonUp(mocc, e)});
	data.left.on("mousedown", function(e){leftDown(mocc, e)});
	data.left.on("mouseup", function(e){buttonUp(mocc, e)});
	data.up.on("mousedown", function(e){upDown(mocc, e)});
	data.up.on("mouseup", function(e){buttonUp(mocc, e)});
	data.down.on("mousedown", function(e){downDown(mocc, e)});
	data.down.on("mouseup", function(e){buttonUp(mocc, e)});
	data.zoomin.off("mousedown");
	data.zoomin.on("mousedown", function(e){plusDown(mocc, e)});
	data.zoomin.off("mouseup");
	data.zoomin.on("mouseup", function(e){buttonUp(mocc, e)});
	data.zoomout.on("mousedown", function(e){minDown(mocc, e)});
	data.zoomout.on("mouseup", function(e){buttonUp(mocc, e)});

	// Common event handlers
	jQuery("body").on("quitimage", function(e,arg){
		if ( typeof(arg) == 'undefined' || arg == mocc ) quitImage(mocc,e);
	});

	if ( data.isLightbox ) {
		jQuery("#wppa-pctl-div-"+data.itemId).on("click", function(e){wppaKillEvent(e)});
	}

	// Install Resize handler
	if ( data.isLightbox ) {
		jQuery(window).off("resize",wppaOvlShowSame);
		jQuery(window).on("resize",wppaOvlShowSame);
	}
	else {
		jQuery(window).on("wpparesizeend",function(e){resize(mocc,e)});
	}

	// Resize
	resize(mocc,'force');

	// We did initialize this occ
	data.initialized = true;

	// Store data
	update(mocc,data);

	// Remove spinner
	jQuery("#wppa-ovl-spin").hide();

	// Optionally disable rightclick
	wppaProtect();

	// The render function
	function render(mocc){

		var data = update(mocc);
		if ( ! data.canvas ) return;

		// To prevent multiple invocations - caused by e.g. resize events - see if we were here less than the repeat time ago
		if ( timnow() < data.time + 10 ) {
			return;
		}
		update(mocc,{time:timnow()});

		if ( ( data.isLightbox && ! wppaOvlOpen ) || ( ! data.isLightbox && wppaOvlOpen ) ) {

			data = update(0,{abort:true});
		}

		if ( data.abort ) {
			update(mocc,{
				manual		:false,
				buttondown	:false,
				pause 		:false,
				ctx			:null
			});
			return;
		}

		if (data.busy) {
			return;
		}

		// Finally decides to do the rendering

		data = update(mocc,{busy:true});

		// Save curent x,y for pan range check
		var oldX = data.fromX;
		var oldY = data.fromY;

		// manual is true when a drag on the canvas is being performed
		if ( ! data.manual ) {

			// Panning
			data.fromX += data.deltaX;
			data.fromY += data.deltaY;

			// Zooming
			var newHeight = data.fromHeight / data.deltaFactor;
			var newWidth = data.fromWidth / data.deltaFactor;

			// Keep zooming in range
			if ( newHeight > 50 && ( wppaIsFs() || newHeight <= data.image.height ) ) {
				data.fromX -= ( newWidth - data.fromWidth ) / 2;
				data.fromY -= ( newHeight - data.fromHeight ) / 2;
				data.fromWidth = newWidth;
				data.fromHeight = newHeight;
			}
			// Stop zooming
			else {
				data.buttondown = false;
			}
		}

		// Keep viewport within image boundaries
		data.fromX = Math.max(0, Math.min(data.image.width-data.fromWidth, data.fromX));
		data.fromY = Math.max(0, Math.min(data.image.height-data.fromHeight, data.fromY));

		// Not zooming and x and y stay the same, stop panning
		if ( oldX == data.fromX && oldY == data.fromY && data.deltaFactor == 1 ) {
			data.buttondown = false;
		}

		var fact, toX, toY;
		var screenRatio = screen.width / screen.height;

		fact = data.image.width / data.fromWidth;
		toX = ( 1 - fact ) * data.canvas.width / 2;
		fact = data.image.height / data.fromHeight;
		toY = ( 1 - fact ) * data.canvas.height / 2;

		// Draw the image
		data.ctx = data.canvas.getContext("2d");

		if ( screenRatio > data.imageRatio && newHeight > data.image.height && wppaIsFs() ) {
			data.enableManual = false;
			data.ctx.clearRect(0, 0, data.canvas.width, data.canvas.height);
			data.ctx.drawImage(data.image,data.fromX,data.fromY,data.fromWidth,data.fromHeight,toX,toY,data.canvas.width,data.canvas.height);
			jQuery( data.canvas ).css({cursor:'default'});
		}
		else if ( screenRatio <= data.imageRatio && newWidth > data.image.width && wppaIsFs() ) {
			data.enableManual = false;
			data.ctx.clearRect(0, 0, data.canvas.width, data.canvas.height);
			data.ctx.drawImage(data.image,data.fromX,data.fromY,data.fromWidth,data.fromHeight,toX,toY,data.canvas.width,data.canvas.height);
			jQuery( data.canvas ).css({cursor:'default'});
		}
		else {
			data.enableManual = true;
			data.ctx.drawImage(data.image,data.fromX,data.fromY,data.fromWidth,data.fromHeight,0,0,data.canvas.width,data.canvas.height);
			jQuery( data.canvas ).css({cursor:'grab'});
		}

		// Calculate image loc on screen
		if ( wppaIsFs() ) {
			data.fsMaskTop = toY - ( data.canvas.height - screen.height ) / 2;
			data.fsMaskLeft = toX - ( data.canvas.width - screen.width ) / 2;
			data.fsMaskBottom = screen.height - data.fsMaskTop;
			data.fsMaskRight = screen.width - data.fsMaskLeft;
		}

		// Not fullscreen
		else {
			data.fsMaskTop = 0;
			data.fsMaskLeft = 0;
			data.fsMaskBottom = 0;
			data.fsMaskRight = 0;
		}

		if ( data.ctrlbarUpdate ) {
			setTimeout(function(){wppaAdjustControlbar(mocc)},100);
			data.ctrlbarUpdate = false;
		}

		// No longer busy
		data.busy = false;

		// No longer in viewport?
		if ( ! wppaIsElementInViewport(data.canvas) ) {
			data.buttondown = false;
		}

		// Restore data
		update(mocc, data);

		// Re-render if needed
		if ( ! data.pause && ( data.manual || data.buttondown ) ) {
			setTimeout(function(){render(mocc)},10);
		}

		// Reset pause
		if (data.pause) {
			update(mocc,{pause:false});
		}

		// Make controlbar visible
		wppaHasControlbar = true;
		wppaAdjustControlbar(mocc);
	}

	// When a (responsive) resize is required, we resize the wppaScene
	function resize(mocc, e){

		var data = update(mocc);
		var oldData = data; // make copy for later
		if ( ! data ) return;

		if ( data.isLightbox ) {

			// Show image container
			jQuery("#wppa-overlay-zpc").css("display", "");

			// There are 4 possiblilities: all combi of 'Width is the limit or not' and 'Mode is normal or fullscreen'
			var widthIsLim;
			var modeIsNormal = ! wppaIsFs();
			var contWidth,	contHeight;

			// First find container dimensions dependant of mode
			if ( modeIsNormal ) {
				contWidth = window.innerWidth ? window.innerWidth : screen.width;
				contHeight = window.innerHeight ? window.innerHeight : screen.height;
			}
			else {
				contWidth = screen.width;
				contHeight = screen.height;
			}

			// Initialize new display sizes
			var newWidth, newHeight,
				topMarg = 0,
				extraX = 8,
				extraY = 8 + ( data.controls ? data.icsize + 10 : 0 ) + 10;

			// Add borderwidth in case of mode == normal
			if ( modeIsNormal ) {
				extraX += 2 * data.borderWidth;
				extraY += 2 * data.borderWidth;
			}
			else {
				extraX = 40;
			}

			// Compute new sizes and margins
			if ( modeIsNormal ) {
				widthIsLim = ( contHeight - extraY ) > ( contWidth - extraX ) / data.imageRatio;
			}
			else {
				screenRatio = screen.width / screen.height;
				widthIsLim = screenRatio < data.imageRatio;
			}

			// Case #1: mode is normal, width is lim
			if ( modeIsNormal && widthIsLim ) {
				newWidth = contWidth - extraX;
				newHeight = newWidth / data.imageRatio;
				topMarg = ( contHeight - newHeight - extraY ) / 2 + 20;
			}

			// Case #2: mode is normal, height is lim
			if ( modeIsNormal && ! widthIsLim ) {
				newWidth = data.imageRatio * ( contHeight - ( data.controls ? data.icsize : 0 ) - 48 );
				newHeight = newWidth / data.imageRatio;
				topMarg = 20;
			}

			// Case #3: mode is fs, width is lim
			if ( ! modeIsNormal && widthIsLim ) {
				newHeight = screen.height;
				newWidth = newHeight * data.imageRatio;
				topMarg = 0;
			}

			// Case #4: mode is fs, height is lim
			if ( ! modeIsNormal && ! widthIsLim ) {
				newWidth = screen.width;
				newHeight = newWidth / data.imageRatio;
				topMarg = ( screen.height - newHeight ) / 2;
			}

			// Set css common for all 4 situations
			jQuery("#wppa-ovl-zoom-container").css({top:topMarg});//,width:newWidth});
			jQuery("#wppa-overlay-zpc").css({top:0,left:0});

			data.canvasWidth = newWidth;
			data.canvasHeight = newHeight;
			data.canvas.width = data.canvasWidth;
			data.canvas.height = data.canvasHeight;


			// Now set css for all mode is normal cases
			if ( modeIsNormal ) {

				// Common for mode normal
				jQuery("#wppa-ovl-zoom-container").css({
					backgroundColor:data.backgroundColor,
					padding:data.padding,
					borderRadius:data.borderRadius+"px",
					borderWidth:data.borderWidth+"px",
					width:newWidth,
					marginLeft:0
				});

				// Limit specific
				if ( widthIsLim ) {
					jQuery("#wppa-overlay-zpc").css({left:4});
				}
				else {
					jQuery("#wppa-overlay-zpc").css({left:(contWidth-newWidth)/2});
				}
			}

			// Mode is fullscreen
			else {

				// Common for mode fullscreen
				jQuery("#wppa-overlay-zpc").css({marginLeft:0});
				jQuery("#wppa-ovl-zoom-container").css({
					backgroundColor:"transparent",
					padding:0,
					borderRadius:"0px",
					borderWidth:"0px",
					width:newWidth,
					left:(contWidth-newWidth)/2,
					position:"fixed"
				});
			}

			data.ctrlbarUpdate = true;
		}

		// Not lightbox
		else {
			if ( data.slide ) {
				var cw = wppaGetContainerWidth(mocc);
				if (e != 'force' && data.oldcw == cw) {
					return; // no change in container width
				}

				var frameRatio 	= wppaAspectRatio[mocc]; 					// aspect ratio of slideframe h / w
				var imgRatio 	= 1 / data.imageRatio;						// aspect ratio of image h / w
				var ponly   	= wppaPortraitOnly[mocc]; 					// true if portrait only i.e. always fill width
				var frameHeight, frameWidth;
				var panCtrlHeight = data.pancontrolheight;
				if ( panCtrlHeight > 0 ) panCtrlHeight += 5;

				// To see if the height is the limit, we scale the image to fit in the container width and calculate its height;
				var temph = cw * imgRatio;
				// add the control height
				temph += panCtrlHeight;
				// if the resukt is higher than the frame height, height is lim

				// height is limit
				if ( temph > frameRatio * cw && ! ponly ) {
					data.canvasHeight 	= cw * frameRatio - panCtrlHeight;
					data.canvasWidth 	= data.canvasHeight / imgRatio;
					data.oldcw 			= cw;
					data.ctrlbarUpdate 	= false;
					frameHeight 		= cw * frameRatio; // + panCtrlHeight;
					frameWidth 			= cw;
					jQuery('#slide_frame-'+mocc).css({height:frameHeight,width:frameWidth});
					jQuery(data.pctldiv).css({width:frameWidth});

					// Align horizontal
					var halign  = wppaFullHalign[mocc]; if ( typeof( halign )=='undefined' ) halign = 'none';
					switch ( halign ) {
						case 'left':
							break;
						case 'right':
							jQuery( "#wppa-pan-canvas-" + data.itemId ).css( {marginLeft:cw - data.canvasWidth});
							break;
						case 'center':
						default:
							jQuery( "#wppa-pan-canvas-" + data.itemId ).css( {marginLeft:( cw - data.canvasWidth ) / 2});
					}
// console.log('zm height is lim, h = '+jQuery( "#slide_frame-" + mocc ).height());
				}

				// width is limit
				else {
					data.canvasWidth 	= cw;
					data.oldcw 			= cw;
					data.ctrlbarUpdate 	= false;
					frameHeight 		= cw * frameRatio; //  + panCtrlHeight;
					frameWidth 			= cw;
					jQuery('#slide_frame-'+mocc).css({height:frameHeight,width:frameWidth});

					// Image height plus controlbar
					var imghplus = cw * imgRatio + panCtrlHeight;

					// Align vertical
					var valign  = wppaFullValign[mocc]; if ( typeof( valign )=='undefined' ) valign = 'none';
					switch ( valign ) {
						case 'center':
							jQuery( "#wppa-pan-canvas-" + data.itemId ).css({marginTop:( frameHeight - imghplus ) / 2 });
							jQuery( "#slide_frame-" + mocc ).css({height:cw * frameRatio });
							break;
						case 'bottom':
							jQuery( "#wppa-pan-canvas-" + data.itemId ).css({marginTop: frameHeight - imghplus });
							jQuery( "#slide_frame-" + mocc ).css({height:cw * frameRatio });
							break;
						case 'top':
							jQuery( "#slide_frame-" + mocc ).css({height:cw * frameRatio });
							break;
						case 'fit':
							jQuery( "#slide_frame-" + mocc ).css({height:imghplus });
						default:
					}
				}
			}
			else {
				if (e != 'force' && data.canvasWidth == jQuery(data.div).parent().width()) {
					return;
				}
				data.canvasWidth = jQuery(data.div).parent().width();
				data.ctrlbarUpdate = true;
			}
			data.canvasHeight = data.canvasWidth / data.imageRatio;
			data.canvas.width = data.canvasWidth;
			data.canvas.height = data.canvasHeight;
			jQuery( "#wppa-pctl-div-" + mocc ).css({width:cw});
		}

		// Save data and render
		update(mocc, data);
		setTimeout(function(){render(mocc)},1);

		jQuery("#wppa-ovl-zoom-container").show();
	}

	// Horizontal movement right by button
	function rightDown(mocc, e){

		var data = update(mocc);
		var delta = data.image.naturalWidth / data.canvas.width;
		if ( delta < 1.5 ) {
			delta = 1.5;
		}
		delta *= 1.5;

		update(mocc, {
			deltaX 			:delta,
			buttondown 		:true,
			manual 			:false
		});
		render(mocc);
	}

	// Horizontal movement left by button
	function leftDown(mocc, e){

		var data = update(mocc);
		var delta = data.image.naturalWidth / data.canvas.width;
		if ( delta < 1.5 ) {
			delta = 1.5;
		}
		delta *= 1.5;

		update(mocc, {
			deltaX 			:-delta,
			buttondown 		:true,
			manual 			:false
		});
		render(mocc);
	}

	// Vertical movement up by button
	function upDown(mocc, e){

		var data = update(mocc);
		var delta = data.image.naturalHeight / data.canvas.height;
		if ( delta < 1.5 ) {
			delta = 1.5;
		}
		delta *= 1.5;

		update(mocc, {
			deltaY			:-delta,
			buttondown 		:true,
			manual 			:false
		});
		render(mocc);
	}

	// Vertical movement down by button
	function downDown(mocc, e){

		var data = update(mocc);
		var delta = data.image.naturalHeight / data.canvas.height;
		if ( delta < 1.5 ) {
			delta = 1.5;
		}
		delta *= 1.5;

		update(mocc, {
			deltaY			:delta,
			buttondown 		:true,
			manual 			:false
		});
		render(mocc);
	}

	// Zoom in by mousedown
	function plusDown(mocc, e){

		update(mocc, {
			deltaFactor 	:1.005,
			buttondown 		:true,
			manual 			:false
		});
		render(mocc);
	}

	// Zoom out by mousedown
	function minDown(mocc, e){

		update(mocc, {
			deltaFactor 	:0.995,
			buttondown 		:true,
			manual 			:false
		});
		render(mocc);
	}

	// Mouse wheel
	function onDivWheel(mocc, e) {

		e.preventDefault();
		e.stopPropagation();

		data = update(mocc);
		update(mocc, {
			deltaFactor 	: 1 + e.deltaY * data.zoomsensitivity / 10000,
			manual 			: false,
			burrondown 		: false
		});
		render(mocc);
		setTimeout(function(){update(mocc, {deltaFactor:1})}, 25);
	}

	// When a navigation button is released, stop and reset all deltas
	function buttonUp(mocc, e) {

		update(mocc, {
			deltaX 		:0,
			deltaY 		:0,
			deltaFactor :1,
			buttondown 	:false
		});
	}

	// when the mouse is pressed on the canvas, we switch to manual control and save current coordinates
	function canvasDown(mocc, e){

		var data = update(mocc);

		// Find screen x and y
		var X = findscreenxy(e).X;
		var Y = findscreenxy(e).Y;

		// If fs and outside image, transfer to wppa-overlay-bg
		if ( wppaIsFs() && data.isLightbox ) {
			if ( X < data.fsMaskLeft || X > data.fsMaskRight || Y < data.fsMaskTop || Y > data.fsMaskBottom ) {
				update(0,{abort:true})
				jQuery( '#wppa-overlay-bg' ).trigger( 'click' );
				return;
			}
		}

		// If manual get location on image
		if ( data.enableManual ) {

			X = findoffsetxy(mocc,e).X;
			Y = findoffsetxy(mocc,e).Y;
			update(mocc, {
				manual 			:true,
				savedCanvasX 	:X,
				savedCanvasY 	:Y,
				buttondown 		:false
			});
			render(mocc);
		}
	}

	// When the mouse is down (manual control) and moved adjust the coorinates
	function canvasMove(mocc, e){

		e.preventDefault();
		e.stopPropagation();

		var data = update(mocc);
		var X = findoffsetxy(mocc,e).X;
		var Y = findoffsetxy(mocc,e).Y;

		if ( data.manual ){
			var factor = data.canvas.width / data.fromWidth;
			var x = ( data.savedCanvasX - X ) / factor + data.fromX;
			var y = ( data.savedCanvasY - Y ) / factor + data.fromY;

			if ( x > 0 && y > 0 && ( x + data.fromWidth ) < data.image.width && ( y + data.fromHeight ) < data.image.height ) {
				update(mocc, {
					fromX 			:x,
					fromY 			:y,
					savedCanvasX 	:X,
					savedCanvasY 	:Y
				});
			}
		}
	}

	// When the mouse is released, reset manual control
	function canvasUp(mocc, e){

		update(mocc, {manual:false});
	}

	// When the mouse leaves the canvas
	function canvasLeave(mcc, e) {

		data = update(mocc);
		if ( data.manual ) {
			update(mocc,{pause:true});
		}
	}

	// when the mouse enters te canvas
	function canvasEnter(mocc, e) {

		var data = update(mocc,{pause:false});

		if ( data.manual ) {
			render(mocc);
		}
	}

	// Update data
	function update(mocc, data) {

		if ( ! wppaZoomData[mocc] ) return false;

		if (data) {
			for (var property in data) {
				wppaZoomData[mocc][property] = data[property];
			}
		}

		return wppaZoomData[mocc];
	}

	// Find screen x and y
	function findscreenxy(e) {

		if ( e.touches ) {
			var X = e.touches[0].screenX;
			var Y = e.touches[0].screenY;
		}
		else {
			var X = e.screenX;
			var Y = e.screenY;
		}
		return {X:X,Y:Y};
	}

	// Find offset x and y
	function findoffsetxy(mocc,e) {

		if ( e.touches ) {
			var data = update(mocc);
			var X = e.touches[0].screenX - data.fsMaskLeft;
			var Y = e.touches[0].screenY - data.fsMaskTop;
		}
		else {
			var X = e.offsetX;
			var Y = e.offsetY;
		}
		return {X:X,Y:Y};
	}

	// Get current time in ms
	function timnow() {
		d = new Date();
		return d.getTime();
	}

	// Clean up
	function quitImage(mocc,e) {

//		console.log('Quit zoom '+mocc);

		// Kill timer
		clearTimeout(data.timer);

		// Clear data
		wppaZoomData[mocc] = [];

		// Make sure procs do not run
		data = update(mocc, {
			abort 	:true,
			autorun :false
		});

		// Clear html
		jQuery("#wppa-pan-canvas-"+data.itemId).html('');
		jQuery("#wppa-pctl-div-"+data.itemId).html('');
		jQuery("#wppa-ovl-zoom-container").html('');
	}
}
