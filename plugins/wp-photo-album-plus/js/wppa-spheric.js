// wppa-spheric.js
//
// contains wppa functions for simple zoomable photos
//
var wppaJsSphericVersion = '8.5.03.007';

// The main proccedure
function wppaDoSphericPan(mocc, xdata) {

	if ( xdata ) wppaSphericData[mocc] = xdata;
	var data 	= update(mocc, data);

	// Been here before with same parms?
	if ( data.initialized ) return;
	if ( data.abort ) return;

	// If in lightbox but lightbox not open, quitImage
	if ( data.isLightbox && ! wppaOvlOpen ) {
		return;
	}

	// Clear previous instance
	if ( data.wppaRenderer ) {
		data.wppaRenderer.state.reset();
		data.wppaSphere.dispose();
		data.wppaSphereMaterial.dispose();
		data.texture.dispose();
	}

	// If lightbox, show spinner
	if ( data.isLightbox && wppaOvlOpen ) {
		jQuery("#wppa-ovl-spin").show();

		// Clear normal image
		jQuery("#wppa-overlay-ic").html("");
	}

	// Find additional data
	data.manCtrl 	= false;
	data.butDown 	= false;
	data.lon 		= 180;
	data.lat 		= 0;
	data.dFov 		= 0;
	data.abort 		= false;
	data.aspect 	= 2;
	data.div 		= jQuery("#wppa-pan-div-"+mocc );
	data.left 		= jQuery("#wppa-pctl-left-"+mocc );
	data.right 		= jQuery("#wppa-pctl-right-"+mocc );
	data.up 		= jQuery("#wppa-pctl-up-"+mocc );
	data.down 		= jQuery("#wppa-pctl-down-"+mocc );
	data.zoomin 	= jQuery("#wppa-pctl-zoomin-"+mocc );
	data.zoomout 	= jQuery("#wppa-pctl-zoomout-"+mocc );
	data.prev 		= jQuery("#wppa-pctl-prev-"+mocc );
	data.next 		= jQuery("#wppa-pctl-next-"+mocc );
	data.pause 		= false;
	data.mFakt 		= ( wppaIsMobile ? '2' : '1' );
	data.time 		= 0;

	if ( data.isLightbox ) {
		wppaGlobalOvlPanoramaId++;
		data.uId = wppaGlobalOvlPanoramaId;
	}

	jQuery(data.div).html("");

	if ( ! data.wppaRenderer ) data.wppaRenderer = new THREE.WebGLRenderer();
	data.wppaRenderer.setSize(data.width, data.height);
	jQuery(data.div).append(data.wppaRenderer.domElement);

	data.wppaScene = new THREE.Scene();

	data.wppaSphere = new THREE.SphereGeometry(100, 100, 40);
	data.wppaSphere.applyMatrix4(new THREE.Matrix4().makeScale(-1, 1, 1));

	data.texture = new THREE.TextureLoader().load(data.url);
	data.wppaSphereMaterial = new THREE.MeshBasicMaterial({map:data.texture});

	data.wppaSphereMesh = new THREE.Mesh(data.wppaSphere, data.wppaSphereMaterial);
	data.wppaScene.add(data.wppaSphereMesh);

		data.right.on("touchstart", function(e){rDown(mocc,e)});
		data.right.on("touchend", function(e){bUp(mocc,e)});
		data.left.on("touchstart", function(e){lDown(mocc,e)});
		data.left.on("touchend", function(e){bUp(mocc,e)});
		data.up.on("touchstart", function(e){uDown(mocc,e)});
		data.up.on("touchend", function(e){bUp(mocc,e)});
		data.down.on("touchstart", function(e){dDown(mocc,e)});
		data.down.on("touchend", function(e){bUp(mocc,e)});
		data.zoomin.on("touchstart", function(e){zInDown(mocc,e)});
		data.zoomin.on("touchend", function(e){bUp(mocc,e)});
		data.zoomout.on("touchstart", function(e){zOutDown(mocc,e)});
		data.zoomout.on("touchend", function(e){bUp(mocc,e)});

		if ( data.enableManual && jQuery("#wppa-pan-div-"+mocc+" canvas").length > 0 ) {

			jQuery("#wppa-pan-div-"+mocc+" canvas")[0].addEventListener("touchstart", function(e){onMs(mocc,e)});
			jQuery("#wppa-pan-div-"+mocc+" canvas")[0].addEventListener("touchmove", function(e){e.stopPropagation();onMm(mocc,e)});
			jQuery("#wppa-pan-div-"+mocc+" canvas")[0].addEventListener("touchend", function(e){onMe(mocc,e)});
		}

		data.right.on("mousedown", function(e){rDown(mocc,e)});
		data.right.on("mouseup", function(e){bUp(mocc,e)});
		data.left.on("mousedown", function(e){lDown(mocc,e)});
		data.left.on("mouseup", function(e){bUp(mocc,e)});
		data.up.on("mousedown", function(e){uDown(mocc,e)});
		data.up.on("mouseup", function(e){bUp(mocc,e)});
		data.down.on("mousedown", function(e){dDown(mocc,e)});
		data.down.on("mouseup", function(e){bUp(mocc,e)});
		data.zoomin.off("mousedown");
		data.zoomin.on("mousedown", function(e){zInDown(mocc,e)});
		data.zoomin.off("mouseup");
		data.zoomin.on("mouseup", function(e){bUp(mocc,e)});
		data.zoomout.on("mousedown", function(e){zOutDown(mocc,e)});
		data.zoomout.on("mouseup", function(e){bUp(mocc,e)});

		if ( data.enableManual && jQuery("#wppa-pan-div-"+mocc+" canvas").length > 0 ) {

			jQuery("#wppa-pan-div-"+mocc+" canvas").on("mousedown", function(e){onMs(mocc,e)});
			jQuery("#wppa-pan-div-"+mocc+" canvas").on("mousemove", function(e){onMm(mocc,e)});
			jQuery("#wppa-pan-div-"+mocc+" canvas").on("mouseup", function(e){onMe(mocc,e)});
			jQuery("#wppa-pan-div-"+mocc+" canvas")[0].addEventListener("wheel", function(e){onDivWheel(mocc,e)});
		}

	// Common event handlers
	jQuery("body").on("quitimage", function(e,arg){
		if ( typeof(arg) == 'undefined' || arg == mocc ) quitImage(mocc,e);
	});

	if ( data.isLightbox ) {
		jQuery("#wppa-pctl-div-"+mocc).on("click", function(e){wppaKillEvent(e)});
	}

	update(mocc,data);

	// Install Resize handler
	jQuery(window).on("wpparesizeend",function(e){resize(mocc,e)});

	// Optionally disable rightclick
	wppaProtect();

	// Redo when visible again due to tabby change tab
	jQuery(document).on("tabbychange",function(e){tabbyChange(mocc,e)});

	resize(mocc,'force');

	function render(mocc){

		var data = update(mocc);

		// To prevent multiple invocations - caused by e.g. resize events - see if we were here less than the repeat time ago
		if ( ! data.manCtrl && timnow() < data.time + 25 ) {
			return;
		}

		update(mocc,{time:timnow()});

		if ( data.isLightbox ) {
			if ( ! wppaOvlOpen || wppaOvlActivePanorama != data.id || wppaGlobalOvlPanoramaId > data.uId ) abort=true;
		}

		if ( data.abort ) return;
		if ( ( data.butDown || data.manCtrl ) && data.autorun ) {
			data.autorun = false;
			data.dX = 0;
		}
		if ( data.dX == 0 && data.dY == 0 && data.dFov == 0 ) data.pause = true;
		if ( data.butDown ) data.pause = false;

		data.fov += data.dFov;
		data.fov = Math.max(20, Math.min(120, data.fov));
		data.wppaCamera = new THREE.PerspectiveCamera(data.fov, data.aspect, 1, 1000);
		data.wppaCamera.target = new THREE.Vector3(0, 0, 0);
		data.lon += data.dX;
		data.lat += data.dY;
		data.lat = Math.max(-85, Math.min(85, data.lat));
		data.wppaCamera.target.x = 500 * Math.sin(THREE.Math.degToRad(90 - data.lat)) * Math.cos(THREE.Math.degToRad(data.lon));
		data.wppaCamera.target.y = 500 * Math.cos(THREE.Math.degToRad(90 - data.lat));
		data.wppaCamera.target.z = 500 * Math.sin(THREE.Math.degToRad(90 - data.lat)) * Math.sin(THREE.Math.degToRad(data.lon));
		data.wppaCamera.lookAt(data.wppaCamera.target);
		if ( data.wppaRenderer ) {
			data.wppaRenderer.render(data.wppaScene, data.wppaCamera);
		}

		wppaHasControlbar = true;
		wppaAdjustControlbar(mocc);
		jQuery("#wppa-ovl-spin").hide();

		var t = 25;
		if ( data.manCtrl ) t = 5;

		if ( data.autorun || ( ! data.pause && ( data.manCtrl || data.butDown ) ) ) {
			if ( data.wppaRenderer ) {
				data.timer = setTimeout(function(){render(mocc)},25);
			}
		}
		update(mocc,data);
	}

	function resize(mocc,e) {

		var modeIsNormal = ! wppaIsFs();
		var newWidth;
		var newHeight;

		data = update(mocc);
		if ( ! data ) return;

		if ( data.isLightbox ) {

			if ( ! wppaOvlOpen ) return;

			jQuery("#wppa-overlay-pc").show();
			jQuery("#wppa-overlay-pc").css("width", "");

			var widthIsLim, modeIsNormal = ! wppaIsFs();

			var contWidth,	contHeight;

			if ( modeIsNormal ) {
				contWidth = window.innerWidth ? window.innerWidth : screen.width;
				contHeight = window.innerHeight ? window.innerHeight : screen.height;
			}
			else {
				contWidth = screen.width;
				contHeight = screen.height;
			}

			// Initialize new display sizes
			var
				topMarg,
				leftMarg,
				extraX = 8,
				extraY = 8 + ( data.controls ? data.icsize + 10 : 0 ) + 30;

			// Add borderwidth in case of mode == normal
			if ( modeIsNormal ) {
				extraX += 2 * data.borderWidth;
				extraY += 2 * data.borderWidth;
			}

			// Find out if the width is the limitng dimension
			widthIsLim = ( contHeight > ( ( ( contWidth  - extraX ) / 2 ) + extraY ) );

			// Compute new sizes and margins
			if ( modeIsNormal ) {
				if ( widthIsLim ) {
					newWidth = contWidth - extraX;
					newHeight = newWidth / 2;
					topMarg = ( contHeight - newHeight - extraY ) / 2 + 20;
				}
				else {
					newHeight = contHeight - extraY;
					newWidth = newHeight * 2;
					topMarg = 20;
				}
			}
			else {
				newWidth = screen.width;
				newHeight = screen.height;
				topMarg = 0;
			}
			data.aspect = newWidth / newHeight;

			// Set css common for all 4 situations
			jQuery("#wppa-ovl-sphericpan-container").css({top:topMarg});
			jQuery("#wppa-overlay-pc").css({top:0});

			// Now set css for all 4 situations: Mode is normal
			if ( modeIsNormal ) {

				// Common for mode normal
				jQuery("#wppa-ovl-sphericpan-container").css({
					backgroundColor:data.backgroundColor,
					padding:data.padding+"px",
					borderRadius:data.borderRadius+"px",
					width:newWidth
				});

				// Limit specific
				if ( widthIsLim ) {
					jQuery("#wppa-overlay-pc").css({left:4});
				}
				else {
					jQuery("#wppa-overlay-pc").css({left:(contWidth-newWidth)/2});
				}
			}

			// Mode is fullscreen
			else {

				// Common for mode fullscreen
				jQuery("#wppa-ovl-sphericpan-container").css({
					backgroundColor:"transparent",
					padding:0,
					borderRadius:"0px",
					width:newWidth,
					left:(contWidth-newWidth)/2
				});
			}

			data.wppaRenderer.setSize(newWidth, newHeight);
		}

		// Not lightbox
		else {
			var cw = wppaGetContainerWidth(mocc);
			var pch = data.pancontrolheight;
			newWidth = cw;
			newHeight = parseInt( newWidth / 2 );
			if (e != 'force') {
				if (data.width > 0 && data.width == newWidth && data.height == newHeight) {
					return;
				}
			}

			// Slideshow
			if ( data.slide ) {
				var pch = data.pancontrolheight;
				if ( pch > 0 ) pch += 5;
				var frameRatio 	= wppaAspectRatio[mocc]; 					// aspect ratio of slideframe h / w
				newHeight = newWidth * frameRatio - pch;
				jQuery('#wppa-pctl-div-'+mocc).css({width:cw});
				jQuery('#slide_frame-'+mocc).css({height:(newHeight + pch)});
// console.log('sp heigt = '+newHeight);
			}

			if ( ! data.wppaRenderer ) return; // vanished ( by pla )
			data.wppaRenderer.setSize(newWidth, newHeight);
		}

		// Store new sizes and render
		data.width = newWidth;
		data.height = newHeight;
		update(mocc,data);

		data.timer = setTimeout(function(){render(mocc)},250);
	}

	// Start event handlers

	// Mouse wheel
	function onDivWheel(mocc,e) {
		e.preventDefault();
		e.stopPropagation();
		data = update(mocc);
		update(mocc, {
			manCtrl :false,
			butDown :false,
			autorun :false,
			dX 		:0,
			dFov 	:-e.deltaY * data.zoomsensitivity / 60
		});
		render(mocc);
		setTimeout(function(){update(mocc,{dFov:0})}, 25);
	}

	// Manual movement on the canvas: (s(tart), m(ove), e(nd))
	function onMs(mocc,e) {
		e.preventDefault();
		e.stopPropagation();
		data = update(mocc,{
			manCtrl :true,
			butDown :false,
			autorun :false,
			dX 		:0
		});
		if ( e.touches ) {
			update(mocc,{
				sX 	:e.touches[0].clientX,
				sY 	:e.touches[0].clientY
			});
		}
		else {
			update(mocc,{
				sX 	:e.clientX,
				sY 	:e.clientY
			});
		}
		update(mocc, {
			sLon 	:data.lon,
			sLat 	:data.lat
		});
		render(mocc);
	}
	function onMm(mocc,e) {
		e.preventDefault();
		e.stopPropagation();
		data = update(mocc);
		if ( data.manCtrl ) {
			if ( e.touches ) {
				update(mocc,{
					lon 	:(data.sX - e.touches[0].clientX) * 0.1 + data.sLon,
					lat 	:(e.touches[0].clientY - data.sY) * 0.1 + data.sLat,
					pause 	:false
				});
			}
			else {
				update(mocc,{
					lon 	:(data.sX - e.clientX) * 0.1 + data.sLon,
					lat 	:(e.clientY - data.sY) * 0.1 + data.sLat,
					pause 	:false
				});
			}
			render(mocc);
		}
	}
	function onMe(mocc,e) {
		update(mocc,{manCtrl:false});
	}

	// Movement by buttons
	function zInDown(mocc,e) {
		data = update(mocc);
		update(mocc,{
			dFov 	:-0.4*data.mFakt,
			butDown :true
		});
		render(mocc);
	}
	function zOutDown(mocc,e) {
		data = update(mocc);
		update(mocc,{
			dFov 	:0.4*data.mFakt,
			butDown :true
		});
		render(mocc);
	}
	function rDown(mocc,e) {
		data = update(mocc);
		update(mocc,{
			dX 		:0.2*data.mFakt,
			butDown :true
		});
		render(mocc);
	}
	function lDown(mocc,e) {
		data = update(mocc);
		update(mocc,{
			dX 		:-0.2*data.mFakt,
			butDown :true
		});
		render(mocc);
	}
	function uDown(mocc,e) {
		data = update(mocc);
		update(mocc,{
			dY 		:0.2*data.mFakt,
			butDown :true
		});
		render(mocc);
	}
	function dDown(mocc,e) {
		data = update(mocc);
		update(mocc,{
			dY 		:-0.2*data.mFakt,
			butDown :true
		});
		render(mocc);
	}
	function bUp(mocc,e) {
		data = update(mocc);
		update(mocc,{
			dX 		:0,
			dY 		:0,
			dFov 	:0,
			butDown :false,
			manCtrl	:false
		});
	}

	// Update data
	function update(mocc, data) {

		if ( ! wppaSphericData[mocc] ) return false;

		if (data) {
			for (var property in data) {
				wppaSphericData[mocc][property] = data[property];
			}
		}

		return wppaSphericData[mocc];
	}

	// Re-initialize this
	function tabbyChange(mocc,e) {

		data.wppaRenderer.state.reset();
		data = update(mocc,{
			initialized:false,
			abort:true,
		});

		setTimeout(function(){
			update(mocc,{abort:false});
			wppaDoSphericPan(mocc);
		},200);
	}

	// Get current time in ms
	function timnow() {
		d = new Date();
		return d.getTime();
	}

	// Clean up
	function quitImage(mocc,e) {

		// console.log('Quit spheric '+mocc);

		// Kill timer
		clearTimeout(data.timer);

		// Clear data
		wppaSphericData[mocc] = [];

		// Make sure procs do not run
		data = update(mocc, {
			abort 	:true,
			autorun :false
		});

		// Clear html
		jQuery("#wppa-pan-div-"+mocc+" canvas").html('');
		jQuery("#wppa-pctl-div-"+mocc).html('');
		jQuery("#wppa-overlay-pc").html('');
	}
}
