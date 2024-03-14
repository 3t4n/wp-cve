// wppa-flatpan.js
//
// contains wppa functions for flat panorama images
//
var wppaJsFlatPanVersion = '8.5.03.007';

	// The main proccedure
	function wppaDoFlatPanorama(mocc, xdata) {

	// Store the data
	wppaFlatpanData[mocc] = xdata;

	// Update data with image
	var data 	= update(mocc,{image:document.getElementById( "wppa-"+xdata.itemId )});

	// Find additional data
	data.ratio 			= data.slide ? ( 1 / wppaAspectRatio[mocc] ) : 2;
	data.manualControl 	= false;
	data.deltaY 		= 0;
	data.deltaFactor 	= 1.0;
	data.run 			= data.deltaX ? 5 : 4;
	data.abort 			= false;
	data.div 			= jQuery("#wppa-pan-div-"+mocc);
	data.canvas 		= document.getElementById("wppa-pan-canvas-"+mocc);
	data.prevCanvas 	= document.getElementById("wppa-pan-prev-canvas-"+mocc);
	data.pctldiv 		= jQuery("#wppa-pctl-div-"+mocc);
	data.left 			= jQuery("#wppa-pctl-left-"+mocc);
	data.right 			= jQuery("#wppa-pctl-right-"+mocc);
	data.up 			= jQuery("#wppa-pctl-up-"+mocc);
	data.down 			= jQuery("#wppa-pctl-down-"+mocc);
	data.zoomin 		= jQuery("#wppa-pctl-zoomin-"+mocc);
	data.zoomout 		= jQuery("#wppa-pctl-zoomout-"+mocc);
	data.prev 			= jQuery("#wppa-pctl-prev-"+mocc);
	data.next 			= jQuery("#wppa-pctl-next-"+mocc);
	data.canvasWidth 	= jQuery(data.div).parent().width();
	data.canvasHeight 	= data.canvasWidth / data.ratio;
	data.savedCanvasX 	= 0;
	data.savedCanvasY 	= 0;
	data.fromHeight 	= data.image.height / data.ratio;
	data.fromWidth 		= data.fromHeight * data.ratio;
	data.fromX 			= ( data.image.width - data.fromWidth ) / data.ratio;
	data.fromY 			= ( data.image.height - data.fromHeight ) / data.ratio;
	data.centerX 		= data.fromX + data.fromWidth / data.ratio;
	data.centerY 		= data.fromY + data.fromHeight / data.ratio;
	data.wppaFlatLastRenderTime = 0;

	// Install listeners
	data.right.on("touchstart", function(e){rightDown(mocc, e)});
	data.right.on("touchend", function(e){buttonUp(mocc, e)});
	data.left.on("touchstart", function(e){leftDown(mocc, e)});
	data.left.on("touchend", function(e){buttonUp(mocc, e)});
	data.up.on("touchstart", function(e){upDown(mocc, e)});
	data.up.on("touchend", function(e){buttonUp(mocc, e)});
	data.down.on("touchstart", function(e){downDown(mocc, e)});
	data.down.on("touchend", function(e){buttonUp(mocc, e)});
	data.zoomin.on("touchstart", function(e){zoomInDown(mocc, e)});
	data.zoomin.on("touchend", function(e){buttonUp(mocc, e)});
	data.zoomout.on("touchstart", function(e){zoomOutDown(mocc, e)});
	data.zoomout.on("touchend", function(e){buttonUp(mocc, e)});

	if ( data.manual ) {
		data.canvas.addEventListener("mousedown", function(e){onCanvasMouseDown(mocc, e)});
		data.canvas.addEventListener("mousemove", function(e){onCanvasMouseMove(mocc, e)});
		data.canvas.addEventListener("mouseup", function(e){onCanvasMouseUp(mocc, e)});
		data.canvas.addEventListener("mouseout", function(e){onCanvasMouseUp(mocc, e)});
		document.getElementById("wppa-pan-canvas-"+mocc).addEventListener("wheel", function(e){onDivWheel(mocc, e)});
		if ( data.prevCanvas ) {
			data.prevCanvas.addEventListener("mousedown", function(e){onCanvasMouseDown(mocc, e)});
			data.prevCanvas.addEventListener("mousemove", function(e){onPrevCanvasMouseMove(mocc, e)});
			data.prevCanvas.addEventListener("mouseup", function(e){onCanvasMouseUp(mocc, e)});
			data.prevCanvas.addEventListener("mouseout", function(e){onCanvasMouseUp(mocc, e)});
		}
	}

	data.right.on("mousedown", function(e){rightDown(mocc, e)});
	data.right.on("mouseup", function(e){buttonUp(mocc, e)});
	data.left.on("mousedown", function(e){leftDown(mocc, e)});
	data.left.on("mouseup", function(e){buttonUp(mocc, e)});
	data.up.on("mousedown", function(e){upDown(mocc, e)});
	data.up.on("mouseup", function(e){buttonUp(mocc, e)});
	data.down.on("mousedown", function(e){downDown(mocc, e)});
	data.down.on("mouseup", function(e){buttonUp(mocc, e)});
	data.zoomin.on("mousedown", function(e){zoomInDown(mocc, e)});
	data.zoomin.on("mouseup", function(e){buttonUp(mocc, e)});
	data.zoomout.on("mousedown", function(e){zoomOutDown(mocc, e)});
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
		jQuery(window).on("resize",function(e){resize(mocc,e)});
	}

	// Store data
	update(mocc,data);

	// Resize
	resize(mocc);

	// Remove spinner
	jQuery("#wppa-ovl-spin").hide();

	// Optionally disable rightclick
	wppaProtect();

	// The render function
	function render(mocc) {

		var data = update(mocc);

		if ( ! data.canvas ) {
			return;
		}

		if ( data.isLightbox ) {
			if (!wppaOvlOpen) {
				data.abort=true;
			}
		}
		else {
			if (!wppaIsElementInViewport(data.canvas)) {

				setTimeout(function(){render(mocc)},200);
				return;
			}
		}

		if ( data.abort ) {
			update(mocc,{
				ctx			:null,
				prevctx 	:null
			});
			return;
		}

		if (data.run==0) {
			return;
		}

		// Duplicate loops?
		var curTime = timnow();
		if ( data.autorun && curTime < data.wppaFlatLastRenderTime + 25 ) {
			return;
		}
		data.wppaFlatLastRenderTime = curTime;

		// manualControl is true when a drag on the canvas is being performed
		if(!data.manualControl){

			// Panning
			data.fromX += data.deltaX;
			data.fromY += data.deltaY;

			// Zooming
			var newHeight = data.fromHeight / data.deltaFactor;
			var newWidth = data.fromWidth / data.deltaFactor;

			// Keep zooming in range
			if ( data.deltaFactor != 1 && newHeight <= data.image.height && newHeight > 50 ) {
				data.fromX -= ( newWidth - data.fromWidth ) / data.ratio;
				data.fromY -= ( newHeight - data.fromHeight ) / data.ratio;
				data.fromWidth = newWidth;
				data.fromHeight = newHeight;
			}
		}

		// Keep viewport within image boundaries
		data.fromX = Math.max(0, Math.min(data.image.width - data.fromWidth, data.fromX));
		data.fromY = Math.max(0, Math.min(data.image.height - data.fromHeight, data.fromY));

		// Check for turningpoint in case autrun
		if ( data.autorun ) {
			if ( data.fromX == 0 || data.fromX == ( data.image.width - data.fromWidth ) ) {
				data.deltaX *= -1;
			}
		}

		// Draw the image
		var ctx = data.canvas.getContext("2d");
		ctx.drawImage(data.image, data.fromX, data.fromY, data.fromWidth, data.fromHeight, 0, 0, data.canvas.width, data.canvas.height);

		// Draw the preview image
		if ( data.prevCanvas ) {
			var prevctx = data.prevCanvas.getContext("2d");
			prevctx.clearRect(0, 0, data.prevCanvas.width, data.prevCanvas.height);
			prevctx.drawImage(data.image, 0, 0, data.image.width, data.image.height, 0, 0, data.prevCanvas.width, data.prevCanvas.height);

			// Draw viewport rect on preview image
			var factor = data.prevCanvas.width / data.image.width;
			prevctx.strokeRect(factor * data.fromX, factor * data.fromY, factor * data.fromWidth, factor * data.fromHeight);
		}

		// Re-render if needed
		if (data.run>0) {
			timer = setTimeout(function(){render(mocc)},25);
		}
		if (data.run<5)data.run--;

		// Make controlbar visible
		wppaHasControlbar = true;
		wppaAdjustControlbar(mocc);
//		setTimeout(function(){jQuery("#wppa-pctl-div-"+mocc).css({visibility:"visible"});}, 100);

		update(mocc,data);
	}

	// When a (responsive) resize is required, we resize the wppaScene
	function resize(mocc) {

		var data = update(mocc);

		if (data.abort) return;

		// console.log('resizing flatpan '+mocc);

		if ( data.isLightbox) {

			// Show image container
			jQuery("#wppa-overlay-fpc").css("display", "");

			// There are 4 possiblilities: all combi of 'Width is the limit or not' and 'Mode is normal or fullscreen'
			var widthIsLim,
			modeIsNormal = ! wppaIsFs();

			// First find container dimensions dependant of mode
			var contWidth,	contHeight;

			if ( modeIsNormal ) {
				contWidth = window.innerWidth ? window.innerWidth : screen.width;
				contHeight = window.innerHeight ? window.innerHeight : screen.height;
			}
			else {
				contWidth = screen.width;
				contHeight = screen.height;
			}
		//	newWidth = parseInt(newWidth);
		//	newHeight = parseInt(newHeight);

			// Initialize new display sizes
			var topMarg,
			leftMarg,
			extraX = 8,
			extraY = 24 + ( data.controls ? data.icsize : 0 ) + contWidth * data.height / data.width + 40;

			// Add borderwidth in case of mode == normal
			if ( modeIsNormal ) {
				extraX += 2 * data.padding;
				extraY += 2 * data.padding;
			}

			// Find out if the width is the limitng dimension
			widthIsLim = ( contHeight > ( ( contWidth / data.ratio ) + extraY ) );

			// Compute new sizes and margins
			if ( widthIsLim ) {
				newWidth = contWidth - extraX;
				newHeight = newWidth / data.ratio;
				topMarg = ( contHeight - newHeight - extraY ) / 2 + 20;
			}
			else {
				newWidth = data.ratio * ( contHeight - ( data.controls ? data.icsize : 0 ) - 24 - 40 ) / ( 1 + 2 * data.height / data.width );
				newHeight = newWidth / data.ratio;
				topMarg = 20;
			}

			// Set css common for all 4 situations
			jQuery("#wppa-ovl-flatpan-container").css({top:topMarg,marginTop:0});

			data.canvas.width = newWidth;
			data.canvas.height = newHeight;
			if ( data.prevCanvas ) {
				data.prevCanvas.width = newWidth;
				data.prevCanvas.height = newWidth * data.height / data.width;
			}

			// Now set css for all 4 situations: Mode is normal
			if ( modeIsNormal ) {

				// Common for mode normal
				jQuery("#wppa-ovl-flatpan-container").css({
					backgroundColor:data.backgroundColor,
					padding:data.padding,
					borderRadius:data.borderRadius,
					width:newWidth,
					marginLeft:0
				});

				// Limit specific
				if ( widthIsLim ) {
					jQuery("#wppa-overlay-fpc").css({left:4});
				}
				else {
					jQuery("#wppa-overlay-fpc").css({left:(contWidth-newWidth)/2});
				}
				wppaAdjustControlbar(mocc);
			}

			// Mode is fullscreen
			else {

				// Common for mode fullscreen
				jQuery("#wppa-overlay-fpc").css({marginLeft:0});
				jQuery("#wppa-ovl-flatpan-container").css({
					backgroundColor:"transparent",
					padding:0,
					borderRadius:0,
					width:newWidth,
					left:(contWidth-newWidth)/2
				});
				wppaAdjustControlbar(mocc);
			}

			data.run=(data.autorun?5:4);
		}

		// Not lightbox
		else {
			if ( data.slide ) {
				var cw = wppaGetContainerWidth(mocc);
				var pch = data.pancontrolheight;
				if ( pch > 0 ) pch += 5;
				var frameRatio 	= wppaAspectRatio[mocc]; 					// aspect ratio of slideframe h / w

				data.canvasWidth = cw;
				data.canvasHeight = cw * frameRatio - pch;
				jQuery(data.pctldiv).css({width:cw});
				jQuery('#slide_frame-'+mocc).css({height:(data.canvasHeight+pch)});
			}
			else {
				data.canvasWidth = jQuery(data.div).parent().width();
				data.canvasHeight = data.canvasWidth / data.ratio;
			}

			data.canvas.width = data.canvasWidth;
			data.canvas.height = data.canvasHeight;
			if ( data.prevCanvas ) {
				data.prevCanvas.width = data.canvasWidth;
				data.prevCanvas.height = data.canvasWidth * data.height / data.width;
			}
			data.run=(data.autorun?5:4);
		}

		update(mocc,data);
		render(mocc);
	}

	// Horizontal movement by button
	function rightDown(mocc,e) {

		update(mocc, {
			autorun	:false,
			run 	:5,
			deltaX 	:3,
			deltaY 	:0
		});
		render(mocc);
	}

	function leftDown(mocc,e) {

		update(mocc, {
			autorun :false,
			run		:5,
			deltaX	:-3,
			deltaY	:0
		});
		render(mocc);
	}

	// Vertical movement by button
	function upDown(mocc,e) {

		update(mocc, {
			autorun :false,
			run 	:5,
			deltaX 	:0,
			deltaY 	:-3
		});
		render(mocc);
	}

	function downDown(mocc,e) {

		update(mocc, {
			autorun :false,
			run		:5,
			deltaX 	:0,
			deltaY 	:3
		});
		render(mocc);
	}

	// Zooming
	function zoomInDown(mocc,e) {

		update(mocc, {
			deltaX 		:0,
			deltaY 		:0,
			autorun 	:false,
			run 		:5,
			deltaFactor :1.005
		});
		render(mocc);
	}

	function zoomOutDown(mocc,e) {

		update(mocc, {
			deltaX 		:0,
			deltaY 		:0,
			autorun 	:false,
			run			:5,
			deltaFactor :0.995
		});
		render(mocc);
	}

	// Mouse wheel
	function onDivWheel(mocc,e) {

		e.preventDefault();
		e.stopPropagation();

		var data = update(mocc);

		update(mocc, {
			deltaX 		:0,
			deltaY 		:0,
			autorun 	:false,
			run 		:4,
			deltaFactor :1 + e.deltaY * data.zoomsensitivity / 10000
		});
		render(mocc);

		setTimeout(function(){update(mocc, {deltaFactor:1});}, 25);
	}

	// When a navigation button is released, stop and reset all deltas
	function buttonUp(mocc,e) {

		e.preventDefault();
		update(mocc, {
			autorun 	:false,
			deltaX 		:0,
			deltaY 		:0,
			deltaFactor :1,
			run 		:4
		});
	}

	// when the mouse is pressed on the canvas, we switch to manual control and save current coordinates
	function onCanvasMouseDown(mocc,e) {

		e.preventDefault();

		update(mocc, {
			manualControl	:true,
			autorun 		:false,
			deltaX 			:0,
			savedCanvasX 	:e.offsetX,
			savedCanvasY 	:e.offsetY,
			run 			:5
		});
		render(mocc);
	}

	function onCanvasMouseMove(mocc,e) {

		var data = update(mocc);

		if ( data.manualControl ) {
			update(mocc, {
				autorun 	:false,
				deltaX 		:0
			});

			var factor = data.canvas.width / data.fromWidth;
			var x = ( data.savedCanvasX - e.offsetX ) / factor + data.fromX;
			var y = ( data.savedCanvasY - e.offsetY ) / factor + data.fromY;

			if ( x > 0 && y > 0 && ( x + data.fromWidth ) < data.image.width && ( y + data.fromHeight ) < data.image.height ) {
				update(mocc, {
					fromX 			:x,
					fromY 			:y,
					savedCanvasX 	:e.offsetX,
					savedCanvasY 	:e.offsetY
				});
			}
		}
	}

	function onPrevCanvasMouseMove(mocc,e) {

		var data = update(mocc);

		var factor = data.prevCanvas.width / data.image.width;

		if (e.offsetX > factor * data.fromX &&
			e.offsetX < factor * ( data.fromX + data.fromWidth ) &&
			e.offsetY > factor * data.fromY &&
			e.offsetY < factor * ( data.fromY + data.fromHeight ) ) {
				jQuery(data.prevCanvas).css("cursor","grab");
		}
		else {
			jQuery(data.prevCanvas).css("cursor","default");
		}

		if ( data.manualControl ){
			data.autorun=false;
			data.deltaX=0;
			if (e.offsetX > factor * data.fromX &&
			e.offsetX < factor * ( data.fromX + data.fromWidth ) &&
			e.offsetY > factor * data.fromY &&
			e.offsetY < factor * ( data.fromY + data.fromHeight ) ) {
				data.fromX = ( e.offsetX - data.savedCanvasX ) / factor + data.fromX;
				data.fromY = ( e.offsetY - data.savedCanvasY ) / factor + data.fromY;
				data.savedCanvasX = e.offsetX;
				data.savedCanvasY = e.offsetY;
			}
		}
		update(mocc,data);
	}

	function onCanvasMouseUp(mocc,e) {

		var data = update(mocc);

		if ( data.manualControl ) {
			update(mocc, {
				run: 			4,
				manualControl: 	false
			});
		}
	}

	// Update data
	function update(mocc, data) {

		if ( ! wppaFlatpanData[mocc] ) return false;

		if (data) {
			for (var property in data) {
				wppaFlatpanData[mocc][property] = data[property];
			}
		}

		return wppaFlatpanData[mocc];
	}

	function timnow() {
		d = new Date();
		return d.getTime();
	}

	// Clean up
	function quitImage(mocc,e) {

		// console.log('Quit flatpan '+mocc);

		// Kill timer
		clearTimeout(data.timer);

		// Clear data
		wppaFlatpanData[mocc] = [];

		// Make sure procs do not run
		data = update(mocc, {
			abort 	:true,
			run 	:0,
			autorun :false
		});

		// Clear html
		jQuery(data.canvas).html('');
		jQuery(data.prevCanvas).html('');
		jQuery("#wppa-pctl-div-"+mocc).html('');
	}
}
