/**
 * @author Sergey Burkov, http://www.wp3dprinting.com
 * @copyright 2017
 */

woo3dv.aabb = new Array();
woo3dv.resize_scale = 1.2;
woo3dv.image_render_required = 0;
woo3dv.default_scale = 100;
woo3dv.cookie_expire = parseInt(woo3dv.cookie_expire);
woo3dv.boundingBox=[];
woo3dv.max_frames = 600;
woo3dv.initial_rotation_x = 0;
woo3dv.initial_rotation_y = 0;
woo3dv.initial_rotation_z = 0;
woo3dv.font_size = 25;
woo3dv.vec = new THREEW.Vector3();



jQuery(document).ready(function(){

	if (!document.getElementById('woo3dv-cv')) return;

	jQuery('.woo3dv-option-container input, .woo3dv-option-container select').on('change', function(){
		jQuery('#woo3dv_shortcode').val('');
	})


//	jQuery('#woo3dv_dialog input').blur()
	
	jQuery("#rotation_x").bind('keyup mouseup', function () {
		woo3dvRotateModel('x', this.value);
		jQuery("#woo3dv_rotation_x").val(this.value);
	});
	jQuery("#rotation_y").bind('keyup mouseup', function () {
		woo3dvRotateModel('y', this.value);
		jQuery("#woo3dv_rotation_y").val(this.value);
	});
	jQuery("#rotation_z").bind('keyup mouseup', function () {
		woo3dvRotateModel('z', this.value);
		jQuery("#woo3dv_rotation_z").val(this.value);
	});

	jQuery("#z_offset").bind('keyup mouseup', function () {
		var newval = parseFloat(this.value);
		if (newval == 0) newval = 0.00001;//workaround
		woo3dvOffsetZ(newval);
		jQuery("#z_offset").val(newval);
	});

	if (jQuery('#product_grid_color').val().length>1) woo3dv.grid_color = jQuery('#product_grid_color').val().replace('#', '0x');//woo3dv.ground_color

	jQuery("form[name=post]").submit(function(){


		if (jQuery('#woo3dv-show-shadow').prop('checked')) {
			jQuery('input[name=product_show_shadow]').val('on');
		}
		else {
			jQuery('input[name=product_show_shadow]').val('off');
		}
		if (jQuery('#woo3dv-show-mirror').prop('checked')) {
			jQuery('input[name=product_show_mirror]').val('on');
		}
		else {
			jQuery('input[name=product_show_mirror]').val('off');
		}

/*		if (jQuery('#woo3dv-show-fog').prop('checked')) {
			jQuery('input[name=product_show_fog]').val('on');
		}
		else {
			jQuery('input[name=product_show_fog]').val('off');
		}
*/

		if (jQuery('#woo3dv-show-grid').prop('checked')) {
			jQuery('input[name=product_show_grid]').val('on');
		}
		else {
			jQuery('input[name=product_show_grid]').val('off');
		}

		if (jQuery('#woo3dv-show-ground').prop('checked')) {
			jQuery('input[name=product_show_ground]').val('on');
		}
		else {
			jQuery('input[name=product_show_ground]').val('off');
		}
		if (jQuery('#woo3dv-auto-rotation').prop('checked')) {
			jQuery('input[name=product_auto_rotation]').val('on');
		}
		else {
			jQuery('input[name=product_auto_rotation]').val('off');
		}
		if (jQuery('#woo3dv-view3d-button').prop('checked')) {
			jQuery('input[name=product_view3d_button]').val('on');
		}
		else {
			jQuery('input[name=product_view3d_button]').val('off');
		}


		if (jQuery('#woo3dv-background-color').val().length>0) {
			jQuery('input[name=product_background1]').val(jQuery('#woo3dv-background-color').val());
		}
		else {
			jQuery('input[name=product_background1]').val('#ffffff');
		}
		if (jQuery('#woo3dv-background-transparency').prop('checked')) {
			jQuery('input[name=product_background_transparency]').val('on');
		}
		else {
			jQuery('input[name=product_background_transparency]').val('off');
		}
/*		i
/*		if (jQuery('#woo3dv-fog-color').val().length>0) {
			jQuery('input[name=product_fog_color]').val(jQuery('#woo3dv-fog-color').val());
		}
		else {
			jQuery('input[name=product_fog_color]').val('#ffffff');
		}
*/
		if (jQuery('#woo3dv-grid-color').val().length>0) {
			jQuery('input[name=product_grid_color]').val(jQuery('#woo3dv-grid-color').val());
		}
		else {
			jQuery('input[name=product_grid_color]').val('#ffffff');
		}
		if (jQuery('#woo3dv-ground-color').val().length>0) {
			jQuery('input[name=product_ground_color]').val(jQuery('#woo3dv-ground-color').val());
		}
		else {
			jQuery('input[name=product_ground_color]').val('#ffffff');
		}





		if (jQuery('#woo3dv-show-light-source1').prop('checked')) {
			jQuery('input[name=product_show_light_source1]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source1]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source2').prop('checked')) {
			jQuery('input[name=product_show_light_source2]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source2]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source3').prop('checked')) {
			jQuery('input[name=product_show_light_source3]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source3]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source4').prop('checked')) {
			jQuery('input[name=product_show_light_source4]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source4]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source5').prop('checked')) {
			jQuery('input[name=product_show_light_source5]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source5]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source6').prop('checked')) {
			jQuery('input[name=product_show_light_source6]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source6]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source7').prop('checked')) {
			jQuery('input[name=product_show_light_source7]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source7]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source8').prop('checked')) {
			jQuery('input[name=product_show_light_source8]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source8]').val('off');
		}
		if (jQuery('#woo3dv-show-light-source9').prop('checked')) {
			jQuery('input[name=product_show_light_source9]').val('on');
		}
		else {
			jQuery('input[name=product_show_light_source9]').val('off');
		}
		if (jQuery('#z_offset').val().length>0) {
			jQuery('#product_offset_z').val(jQuery('#z_offset').val());
		}
		
		if (jQuery('#woo3dv-remember-camera-position').prop('checked')) {
			jQuery('input[name=product_remember_camera_position]').val('1');
			if (typeof(woo3dv.camera)!=='undefined') {
				jQuery('#product_camera_position_x').val(woo3dv.camera.position.x);
				jQuery('#product_camera_position_y').val(woo3dv.camera.position.y);
				jQuery('#product_camera_position_z').val(woo3dv.camera.position.z);

				jQuery('#product_controls_target_x').val(woo3dv.controls.target.x);
				jQuery('#product_controls_target_y').val(woo3dv.controls.target.y);
				jQuery('#product_controls_target_z').val(woo3dv.controls.target.z);

				var vec = woo3dv.camera.getWorldDirection( woo3dv.vec );
				jQuery('#product_camera_lookat_x').val(vec.x);
				jQuery('#product_camera_lookat_y').val(vec.y);
				jQuery('#product_camera_lookat_z').val(vec.z);
			}
		}
		else {
			woo3dv_remove_camera_params();
		}
	})
});



function woo3dvPreview() {

	//workaround for shortcode pages
	woo3dv.model_url = jQuery('#woo3dv_model_url').val();
	woo3dv.model_mtl = jQuery('#woo3dv_model_mtl').val();
	woo3dv.model_color = jQuery('#woo3dv_model_color').val();

	woo3dv.model_transparency = jQuery('#woo3dv_model_transparency').val();
	woo3dv.model_shininess = jQuery('#woo3dv_model_shininess').val();
	woo3dv.upload_url = jQuery('#woo3dv_upload_url').val();
	woo3dv.stored_position_x = parseFloat(jQuery('#product_camera_position_x').val()) || 0;
	woo3dv.stored_position_y = parseFloat(jQuery('#product_camera_position_y').val()) || 0;
	woo3dv.stored_position_z = parseFloat(jQuery('#product_camera_position_z').val()) || 0;
	woo3dv.stored_lookat_x = parseFloat(jQuery('#product_camera_lookat_x').val()) || 0;
	woo3dv.stored_lookat_y = parseFloat(jQuery('#product_camera_lookat_y').val()) || 0;
	woo3dv.stored_lookat_z = parseFloat(jQuery('#product_camera_lookat_z').val()) || 0;
	woo3dv.stored_controls_target_x = parseFloat(jQuery('#product_controls_target_x').val()) || 0;
	woo3dv.stored_controls_target_y = parseFloat(jQuery('#product_controls_target_y').val()) || 0;
	woo3dv.stored_controls_target_z = parseFloat(jQuery('#product_controls_target_z').val()) || 0;
	woo3dv.offset_z = parseFloat(jQuery('#product_offset_z').val()) || 0;
	if (jQuery('input[name=product_show_light_source1]').val().length>1) woo3dv.show_light_source1 = jQuery('input[name=product_show_light_source1]').val();
	if (jQuery('input[name=product_show_light_source2]').val().length>1) woo3dv.show_light_source2 = jQuery('input[name=product_show_light_source2]').val();
	if (jQuery('input[name=product_show_light_source3]').val().length>1) woo3dv.show_light_source3 = jQuery('input[name=product_show_light_source3]').val();
	if (jQuery('input[name=product_show_light_source4]').val().length>1) woo3dv.show_light_source4 = jQuery('input[name=product_show_light_source4]').val();
	if (jQuery('input[name=product_show_light_source5]').val().length>1) woo3dv.show_light_source5 = jQuery('input[name=product_show_light_source5]').val();
	if (jQuery('input[name=product_show_light_source6]').val().length>1) woo3dv.show_light_source6 = jQuery('input[name=product_show_light_source6]').val();
	if (jQuery('input[name=product_show_light_source7]').val().length>1) woo3dv.show_light_source7 = jQuery('input[name=product_show_light_source7]').val();
	if (jQuery('input[name=product_show_light_source8]').val().length>1) woo3dv.show_light_source8 = jQuery('input[name=product_show_light_source8]').val();
	if (jQuery('input[name=product_show_light_source9]').val().length>1) woo3dv.show_light_source9 = jQuery('input[name=product_show_light_source9]').val();


//	if ()
//jQuery('#woo3dv-show-light-source1]').val('');


	jQuery( function() {
		jQuery( "#woo3dv_dialog" ).dialog({
			modal: true,
			width: "auto",
			open: function(event, ui) {
				woo3dvOnWindowResize();
			}
		});
	} );


	window.woo3dv_canvas = document.getElementById('woo3dv-cv');
	woo3dvCanvasDetails();

	var logoTimerID = 0;

	woo3dv.targetRotation = 0;

	var model_type=woo3dv.model_url.split('.').pop().toLowerCase();
	woo3dvViewerInit(woo3dv.model_url, woo3dv.model_mtl, model_type, false);

	woo3dvAnimate();
}



function woo3dvViewerInit(model, mtl, ext) {
	var woo3dv_canvas = document.getElementById('woo3dv-cv');
	var woo3dv_canvas_width = jQuery('#woo3dv-cv').width()
	var woo3dv_canvas_height = jQuery('#woo3dv-cv').height()
//console.log(woo3dv.model_color)
	jQuery('#woo3dv_shortcode').val('');

	woo3dv.mtl=mtl;

	//3D Renderer
	woo3dv.renderer = Detector.webgl? new THREEW.WebGLRenderer({ antialias: true, canvas: woo3dv_canvas, preserveDrawingBuffer: true }): new THREEW.CanvasRenderer({canvas: woo3dv_canvas});
	woo3dv.renderer.setClearColor( parseInt(woo3dv.background1, 16) );
	woo3dv.renderer.setPixelRatio( window.devicePixelRatio );
	woo3dv.renderer.setSize( woo3dv_canvas_width, woo3dv_canvas_height );


	if (Detector.webgl) {

		woo3dv.renderer.gammaInput = true;
		woo3dv.renderer.gammaOutput = true;
//		if (!jQuery('#woo3dv-show-shadow').prop('checked')) woo3dv.renderer.shadowMap.enabled = false;
//		else woo3dv.renderer.shadowMap.enabled = true;
		woo3dv.renderer.shadowMap.enabled = true;


//		woo3dv.renderer.shadowMap.renderReverseSided = false;
		woo3dv.renderer.shadowMap.Type = THREEW.PCFSoftShadowMap;
	}

	woo3dv.camera = new THREEW.PerspectiveCamera( 35, woo3dv_canvas_width / woo3dv_canvas_height, 0.01, 1000 );
//	woo3dv.camera.position.set( 0, 0, 6 );

	if (woo3dv.stored_position_x!=0 || woo3dv.stored_position_y!=0 || woo3dv.stored_position_z!=0) {
		woo3dv.camera.position.set(woo3dv.stored_position_x, woo3dv.stored_position_y, woo3dv.stored_position_z);
	}
	else {
		woo3dv.camera.position.set( 0, 0, 0 );
	}

//	woo3dv.cameraTarget = new THREEW.Vector3( 0, 0, 0 );




	woo3dv.scene = new THREEW.Scene();
	if (jQuery('#woo3dv-background-color').val().length>0) {
		woo3dv.scene.background = new THREEW.Color(parseInt(jQuery('#woo3dv-background-color').val().replace('#', '0x'), 16));
	}


	woo3dv.clock = new THREEW.Clock();

	//Group
	if (woo3dv.group) woo3dv.scene.remove(woo3dv.group);
	woo3dv.group = new THREEW.Group();
	woo3dv.group.position.set( 0, 0, 0 )
	woo3dv.group.name = "group";
	woo3dv.scene.add( woo3dv.group );

	//Axis
	woo3dv.axis = new THREEW.AxesHelper( 300 )
	woo3dv.scene.add( woo3dv.axis );





	//Light
	ambientLight = new THREEW.AmbientLight(0x191919);
	woo3dv.scene.add(ambientLight);
	ambientLight.name = "light";


	if (woo3dv.show_light_source1=='on') woo3dv.directionalLight1 = woo3dvMakeLight(1);
	if (woo3dv.show_light_source2=='on') woo3dv.directionalLight2 = woo3dvMakeLight(2);
	if (woo3dv.show_light_source3=='on') woo3dv.directionalLight3 = woo3dvMakeLight(3);
	if (woo3dv.show_light_source4=='on') woo3dv.directionalLight4 = woo3dvMakeLight(4);
	if (woo3dv.show_light_source5=='on') woo3dv.directionalLight5 = woo3dvMakeLight(5);
	if (woo3dv.show_light_source6=='on') woo3dv.directionalLight6 = woo3dvMakeLight(6);
	if (woo3dv.show_light_source7=='on') woo3dv.directionalLight7 = woo3dvMakeLight(7);
	if (woo3dv.show_light_source8=='on') woo3dv.directionalLight8 = woo3dvMakeLight(8);
	if (woo3dv.show_light_source9=='on') woo3dv.directionalLight9 = woo3dvMakeLight(9);



	woo3dv.controls = new THREEW.OrbitControls( woo3dv.camera, woo3dv.renderer.domElement );
	if (woo3dv.auto_rotation=='on' || jQuery('#woo3dv-auto-rotation').prop('checked')) {
		woo3dv.controls.autoRotate = true; 
		woo3dv.controls.autoRotateSpeed = (woo3dv.auto_rotation_direction == 'ccw' ? -parseInt(woo3dv.auto_rotation_speed) : parseInt(woo3dv.auto_rotation_speed));
	}
	if (!jQuery('#woo3dv-auto-rotation').prop('checked')) {
		woo3dv.controls.autoRotate = false;
	}

	woo3dv.controls.addEventListener( 'start', function() {
		woo3dv.controls.autoRotate = false; 
	});
	jQuery('#woo3dv-convert1').hide();
	jQuery('#woo3dv-convert2').hide();
	if (ext=='stl') {
		jQuery('#woo3dv-convert2').show();
		woo3dv.loader = new THREEW.STLLoader();
	}
/*	else if (ext=='dae') {
		woo3dv.loader = new THREEW.ColladaLoader();
	}*/
	else if (ext=='obj') {
		jQuery('#woo3dv-convert1').show();
		woo3dv.loader = new THREEW.OBJLoader();
	}
	else if (ext=='wrl') {
		jQuery('#woo3dv-convert1').show();
		woo3dv.loader = new THREEW.VRMLLoader();
	}
	else if (ext=='gltf' || ext=='glb') {
		jQuery('#woo3dv-convert1').hide();
		THREEW.DRACOLoader.setDecoderPath( woo3dv.plugin_url+'includes/ext/threejs/js/libs/draco/gltf/' );
		woo3dv.loader = new THREEW.GLTFLoader();
		woo3dv.loader.setDRACOLoader( new THREEW.DRACOLoader() );
	}
	else if (typeof(ext)!=='undefined') {

		if (ext == 'zip') {
			alert('ZIP is not supported by the viewer yet.');
		}
		else {
			alert('Supported file types: STL, OBJ, WRL, GLTF, GLB, ZIP');
		}
		woo3dvDisplayUserDefinedProgressBar(false);
		return;
	}

	if (model.length>0) {

		var mtlLoader = new THREEW.MTLLoader();
		mtlLoader.setPath( woo3dv.upload_url );

		if (ext=='obj' && mtl && mtl.length>0) {
			mtlLoader.load( mtl, function( materials ) {
				materials.preload();
				var objLoader = new THREEW.OBJLoader();
				woo3dv.loader.setMaterials( materials );

				woo3dv.loader.load( model, function ( geometry ) {
		        	    woo3dvModelOnLoad(geometry);
				},
				function( e ){},
				function ( error ) {
					woo3dvDisplayUserDefinedProgressBar(false);
					if (typeof(error)=='object') {
						if (typeof(error.currentTarget)=='object' && error.currentTarget.responseURL) {
							var error_msg = '';
							error_msg+=error.currentTarget.responseURL.split('/').reverse()[0]+': ';
							error_msg+=error.currentTarget.status+' '+error.currentTarget.statusText;
							alert(error_msg);
						}
					}
					else {
						alert('Model not found');
					}
				}
				);
			},
			function( e ){},
			function ( error ) {
				woo3dvDisplayUserDefinedProgressBar(false);
				if (typeof(error)=='object') {
					if (typeof(error.currentTarget)=='object' && error.currentTarget.responseURL) {
						var error_msg = '';
						error_msg+=error.currentTarget.responseURL.split('/').reverse()[0]+': ';
						error_msg+=error.currentTarget.status+' '+error.currentTarget.statusText;
						alert(error_msg);
					}
				}
				else {
					alert('Material not found');
				}
			}
			);
		}
		else if (ext=='gltf' || ext=='glb') {
			woo3dv.loader.load( model, function ( gltf ) {
				woo3dvModelOnLoad(gltf.scene);
/*
					gltf.scene.traverse( function ( child ) {

						if ( child.isSkinnedMesh ) child.castShadow = true;

					} );
*/
				if (gltf.animations.length>0) {
					woo3dv.mixer = new THREEW.AnimationMixer( gltf.scene );
					woo3dv.mixer.clipAction( gltf.animations[ 0 ] ).play();
				}
			},
			function( e ){},
			function ( error ) {
				woo3dvDisplayUserDefinedProgressBar(false);
				if (typeof(error)=='object') {
					if (typeof(error.currentTarget)=='object' && error.currentTarget.responseURL) {

						var error_msg = '';
						error_msg+=error.currentTarget.responseURL.split('/').reverse()[0]+': ';
						error_msg+=error.currentTarget.status+' '+error.currentTarget.statusText;
						alert(error_msg);
					}
				}
				else {
					alert('Model not found');
				}
			}
			);
		}
		else {
			woo3dv.loader.load( model, function ( geometry ) {
				woo3dvModelOnLoad(geometry)
				if (ext=='wrl' && jQuery('#woo3dv_shortcode').length>0) { //strange bug fix
					jQuery('#z_offset').val('1');
					woo3dvOffsetZ(1);
					woo3dvFitCameraToObject(woo3dv.camera, woo3dv.object, 1.2, woo3dv.controls);
				}

			},
			function( e ){},
			function ( error ) {
				woo3dvDisplayUserDefinedProgressBar(false);
				if (typeof(error)=='object') {
					if (typeof(error.currentTarget)=='object' && error.currentTarget.responseURL) {
						var error_msg = '';
						error_msg+=error.currentTarget.responseURL.split('/').reverse()[0]+': ';
						error_msg+=error.currentTarget.status+' '+error.currentTarget.statusText;
						alert(error_msg);
					}
				}
				else {
					alert('Model not found');
				}
			}
			);
		}
	}

//	if (woo3dv.display_mode=='fullscreen') {
//		woo3dvGoFullScreen();
//
//	}


	window.addEventListener( 'resize', woo3dvOnWindowResize, false );

}


function woo3dvModelOnLoad(object) {

	woo3dv.object = object;
	geometry = object;

	if (object.type=='Group') {
		geometry = object.children[0].geometry;
		//todo: merge multiple geometries?
	}

	//Material
	var material = woo3dvCreateMaterial(woo3dv.shading);
	if (typeof(geometry.computeBoundingBox)!=='undefined') {
		geometry.computeBoundingBox();
		woo3dv.boundingBox=geometry.boundingBox;
	}
	else {
	    	woo3dv.boundingBox = new THREEW.Box3().setFromObject(object);
	}
/*
	if (object.type=='Group' && object.children.length>1) {
		var min_coords=[];
		var max_coords=[];
		for(var i=0;i<object.children.length;i++) {
			object.children[i].geometry.computeBoundingBox();
			if (i==0) {
				min_coords.x=object.children[i].geometry.boundingBox.min.x;
				min_coords.y=object.children[i].geometry.boundingBox.min.y;
				min_coords.z=object.children[i].geometry.boundingBox.min.z;
				max_coords.x=object.children[i].geometry.boundingBox.max.x;
				max_coords.y=object.children[i].geometry.boundingBox.max.y;
				max_coords.z=object.children[i].geometry.boundingBox.max.z;
			}
			else {
				if (object.children[i].geometry.boundingBox.min.x < min_coords.x) min_coords.x = object.children[i].geometry.boundingBox.min.x;
				if (object.children[i].geometry.boundingBox.min.y < min_coords.y) min_coords.y = object.children[i].geometry.boundingBox.min.y;
				if (object.children[i].geometry.boundingBox.min.z < min_coords.z) min_coords.z = object.children[i].geometry.boundingBox.min.z;

				if (object.children[i].geometry.boundingBox.max.x > max_coords.x) max_coords.x = object.children[i].geometry.boundingBox.max.x;
				if (object.children[i].geometry.boundingBox.max.y > max_coords.y) max_coords.y = object.children[i].geometry.boundingBox.max.y;
				if (object.children[i].geometry.boundingBox.max.z > max_coords.z) max_coords.z = object.children[i].geometry.boundingBox.max.z;
			}
		}
		woo3dv.boundingBox.min=min_coords;
		woo3dv.boundingBox.max=max_coords;
	}
*/

	//Model


	woo3dvCreateModel(object, geometry, material, woo3dv.shading);

	woo3dvChangeModelColor(woo3dv.model_color);


//	if ((object.type=='Group'&& object.children.length>1) || object.type=='Scene') {
	if ((object.type=='Group'&& object.children.length>1) || object.type=='Scene') {
		new THREEW.Box3().setFromObject( woo3dv.object ).getCenter( woo3dv.object.position ).multiplyScalar( - 1 );
	    	woo3dv.boundingBox = new THREEW.Box3().setFromObject(object);
	}
	else {
		geometry.center();
	}



//	var model_dim = new Array();
//	model_dim.x = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
//	model_dim.y = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
//	model_dim.z = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

	var mesh_width = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
	var mesh_length = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
	var mesh_height = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

	var mesh_diagonal = Math.sqrt(mesh_width * mesh_width + mesh_length * mesh_length + mesh_height * mesh_height);



	if (Detector.webgl) {
		var canvas_width=woo3dv.renderer.getSize().width;
		var canvas_height=woo3dv.renderer.getSize().height;
	}
	else {
		var canvas_width=jQuery('#woo3dv-cv').width();
		var canvas_height=jQuery('#woo3dv-cv').height();
	}

	var canvas_diagonal = Math.sqrt(canvas_width * canvas_width + canvas_height * canvas_height);

	var max_side = Math.max(mesh_width, mesh_length, mesh_height)
//	var max_side_xy = Math.max(mesh_width, mesh_length)

//	var axis_length = Math.max(mesh_width, mesh_length);
///	var axis_width = Math.min(mesh_width, mesh_length);

//	console.log(Math.max(canvas_width , canvas_height));
//	var font_size = (Math.max(canvas_width , canvas_height)/30);	
//console.log(font_size);

	var plane_width = max_side * 100;
	var grid_step = plane_width/100;

	if (woo3dv.axis.geometry.boundingSphere.radius < max_side) {
		woo3dv.axis.scale.set(max_side,max_side,max_side);
	}

	if (woo3dv.show_fog=='on') {
		woo3dv.scene.background = new THREEW.Color( parseInt(woo3dv.fog_color, 16) );
		woo3dv.scene.fog = new THREEW.Fog( parseInt(woo3dv.fog_color, 16), max_side*3, plane_width/2 ); 
		woo3dv.scene.fog.oldfar = woo3dv.scene.fog.far;
	}
//	if (!jQuery('#woo3dv-show-fog').prop('checked')) {
//		woo3dv.scene.fog.far=Infinity;
//	}

//0xa0a0a0


//	console.log(dist);
//	font_size = 20;

	woo3dv.spritey_x = woo3dvMakeTextSprite( " X ", 
		{ fontsize: woo3dv.font_size, borderColor: {r:255, g:102, b:0, a:1.0}, backgroundColor: {r:255, g:255, b:255, a:0.8} } );
	woo3dv.spritey_x.position.set(((woo3dvGetFraction(woo3dv.font_size)*(mesh_width/2))+10),0,0);
	woo3dv.spritey_x.rotation.z = parseFloat(woo3dv.default_rotation_y) * Math.PI/180;
	woo3dv.spritey_x.rotation.x = parseFloat(woo3dv.default_rotation_x) * Math.PI/180;
	woo3dv.scene.add( woo3dv.spritey_x );

	woo3dv.spritey_y = woo3dvMakeTextSprite( " Y ", 
		{ fontsize: woo3dv.font_size, borderColor: {r:51, g:51, b:255, a:1.0}, backgroundColor: {r:255, g:255, b:255, a:0.8} } );
	woo3dv.spritey_y.position.set(0,0,((woo3dvGetFraction(woo3dv.font_size)*(mesh_length/2))+10));
	woo3dv.spritey_y.rotation.z = parseFloat(woo3dv.default_rotation_y) * Math.PI/180;
	woo3dv.spritey_y.rotation.x = parseFloat(woo3dv.default_rotation_x) * Math.PI/180;
	woo3dv.scene.add( woo3dv.spritey_y );

	woo3dv.spritey_z = woo3dvMakeTextSprite( " Z ", 
		{ fontsize: woo3dv.font_size, borderColor: {r:51, g:204, b:51, a:1.0}, backgroundColor: {r:255, g:255, b:255, a:0.8} } );
	woo3dv.spritey_z.position.set(0,((woo3dvGetFraction(woo3dv.font_size)*(mesh_height/2))+10),0);
	woo3dv.spritey_z.rotation.z = parseFloat(woo3dv.default_rotation_y) * Math.PI/180;
	woo3dv.spritey_z.rotation.x = parseFloat(woo3dv.default_rotation_x) * Math.PI/180;
	woo3dv.scene.add( woo3dv.spritey_z );





	//Camera
	var camera_near = 0.01;
	var min_side = Math.min(mesh_width, mesh_length, mesh_height);
	if (min_side>10) camera_near = 1;
	if (min_side<10) camera_near = 0.1;
	if (min_side<1) camera_near = 0.01;
	woo3dv.camera.near=camera_near;

	//todo if remember camera pos
	if (woo3dv.stored_position_x!=0 || woo3dv.stored_position_y!=0 || woo3dv.stored_position_z!=0) {//manually set 
	        var objectWorldPosition = new THREEW.Vector3(woo3dv.stored_lookat_x, woo3dv.stored_lookat_y, woo3dv.stored_lookat_z); //params?
	        woo3dv.camera.lookAt(objectWorldPosition); 

		woo3dv.camera.position.set(woo3dv.stored_position_x, woo3dv.stored_position_y, woo3dv.stored_position_z);

		woo3dv.controls.target = new THREEW.Vector3(woo3dv.stored_controls_target_x, woo3dv.stored_controls_target_y, woo3dv.stored_controls_target_z); 

		woo3dv.camera.far=plane_width*5;
		woo3dv.camera.updateProjectionMatrix();
	}
	else {//auto set
		woo3dvFitCameraToObject(woo3dv.camera, woo3dv.object, 1.2, woo3dv.controls);
	}

//woo3dvFitCameraToObject(woo3dv.camera, woo3dv.object, 1.35, woo3dv.controls);

//	mesh_width, mesh_height

	//Ground
	if (Detector.webgl) {
//		if (woo3dv.ground_mirror=='on' || jQuery('#woo3dv-show-mirror').prop('checked')) {
		if (true) {
			var plane_shininess = 2500;
			var plane_transparent = true;
			var plane_opacity = 0.6;
		}
		else {
			var plane_shininess = 30;
			var plane_transparent = false;
			var plane_opacity = 1;
		}
		
		plane = new THREEW.Mesh(
			new THREEW.PlaneBufferGeometry( plane_width, plane_width ),
			new THREEW.MeshPhongMaterial ( { color: parseInt(woo3dv.ground_color, 16), transparent:plane_transparent, opacity:plane_opacity, shininess: plane_shininess } ) 
		);
		if (!jQuery('#woo3dv-show-ground').prop('checked')) {
			plane.visible = false;
		}
		if (jQuery('#woo3dv-ground-color').val().length>0) {
			plane.material.color = new THREEW.Color(parseInt(jQuery('#woo3dv-ground-color').val().replace('#', '0x'), 16));
		}

		plane.rotation.x = -Math.PI/2;
		plane.position.y = woo3dv.boundingBox.min.z;
		plane.receiveShadow = true;
		plane.castShadow = true;
		plane.name = 'ground';
		woo3dv.scene.add( plane );
//		if (woo3dv.ground_mirror=='on' || jQuery('#woo3dv-show-mirror').prop('checked')) {
		if (true) {
			var planeGeo = new THREEW.PlaneBufferGeometry( plane_width, plane_width );
			woo3dv.groundMirror = new THREEW.Mirror( woo3dv.renderer, woo3dv.camera, { clipBias: 0.003, textureWidth: canvas_width, textureHeight: canvas_height, color: 0xaaaaaa } );
			var mirrorMesh = new THREEW.Mesh( planeGeo, woo3dv.groundMirror.material );
			mirrorMesh.position.y = woo3dv.boundingBox.min.z-camera_near;
			mirrorMesh.add( woo3dv.groundMirror );
			mirrorMesh.rotateX( - Math.PI / 2 );
			mirrorMesh.name = 'mirror';
			//if (!jQuery('#woo3dv-show-mirror').prop('checked')) mirrorMesh.visible = false;

			woo3dv.scene.add( mirrorMesh );
			if (!jQuery('#woo3dv-show-mirror').prop('checked')) {
				mirrorMesh.visible = false;
				woo3dv.scene.getObjectByName('ground').material.transparent = false;
				woo3dv.scene.getObjectByName('ground').material.opacity = 1;
				woo3dv.scene.getObjectByName('ground').material.shininess = 30;

			}

		}

	}

	if (woo3dv.object.type=='Scene') {
		//calculate new dimensions
		var bbox = new THREEW.Box3().setFromObject(woo3dv.object);
		var mesh_height = bbox.max.y - bbox.min.y;
		var mesh_width = bbox.max.x - bbox.min.x;
		var mesh_length = bbox.max.z - bbox.min.z;
		woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y
//		woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y
	}



	//Grid
//	if (woo3dv.show_grid=='on' && woo3dv.grid_color.length>0) {
	if (true) {

		if (woo3dv.grid_color.length==0) woo3dv.grid_color = '0xffffff';
//		var size = 1000, step = 50;
		var size = plane_width/2, step = grid_step;
		var grid_geometry = new THREEW.Geometry();
		for ( var i = - size; i <= size; i += step ) {
			grid_geometry.vertices.push( new THREEW.Vector3( - size, woo3dv.boundingBox.min.z, i ) );
			grid_geometry.vertices.push( new THREEW.Vector3(   size, woo3dv.boundingBox.min.z, i ) );
			grid_geometry.vertices.push( new THREEW.Vector3( i, woo3dv.boundingBox.min.z, - size ) );
			grid_geometry.vertices.push( new THREEW.Vector3( i, woo3dv.boundingBox.min.z,   size ) );
		
		}


		var grid_material = new THREEW.LineBasicMaterial( { color: parseInt(woo3dv.grid_color, 16), opacity: 0.2 } );
		var line = new THREEW.LineSegments( grid_geometry, grid_material );
		line.name = "grid";
		woo3dv.scene.add( line );
		woo3dv.group.add( line );
	}

	if (!jQuery('#woo3dv-show-grid').prop('checked')) { 
		woo3dv.scene.getObjectByName('grid').visible=false;
	}
/*	
	directionalLight.position.set( max_side*2, max_side*2, max_side*2 );
	directionalLight2.position.set( -max_side*2, max_side*2, -max_side*2 );
	if (Detector.webgl && woo3dv.show_shadow=='on') {
		directionalLight.castShadow = true;
		directionalLight2.castShadow = true;
		woo3dvMakeShadow();
	}
	woo3dv.scene.add( directionalLight );
	woo3dv.scene.add( directionalLight2 );
*/

	if (woo3dv.show_light_source1=='on') woo3dvSetupLight(woo3dv.directionalLight1, 1);
	if (woo3dv.show_light_source2=='on') woo3dvSetupLight(woo3dv.directionalLight2, 2);
	if (woo3dv.show_light_source3=='on') woo3dvSetupLight(woo3dv.directionalLight3, 3);
	if (woo3dv.show_light_source4=='on') woo3dvSetupLight(woo3dv.directionalLight4, 4);
	if (woo3dv.show_light_source5=='on') woo3dvSetupLight(woo3dv.directionalLight5, 5);
	if (woo3dv.show_light_source6=='on') woo3dvSetupLight(woo3dv.directionalLight6, 6);
	if (woo3dv.show_light_source7=='on') woo3dvSetupLight(woo3dv.directionalLight7, 7);
	if (woo3dv.show_light_source8=='on') woo3dvSetupLight(woo3dv.directionalLight8, 8);
	if (woo3dv.show_light_source9=='on') woo3dvSetupLight(woo3dv.directionalLight9, 9);



	woo3dvDisplayUserDefinedProgressBar(false);
	woo3dv.original_width=jQuery('#woo3dv-cv').width();
	woo3dv.original_height=jQuery('#woo3dv-cv').height();

	//woo3dvRotateModel();
	if (jQuery("#woo3dv_rotation_x").val()!=0)
		woo3dvRotateModel('x', jQuery("#rotation_x").val());
	if (jQuery("#woo3dv_rotation_y").val()!=0)
		woo3dvRotateModel('y', jQuery("#rotation_y").val());
	if (jQuery("#woo3dv_rotation_z").val()!=0)
		woo3dvRotateModel('z', jQuery("#rotation_z").val());

	woo3dv.spritey_x.position.set(((woo3dvGetFraction(woo3dv.font_size)*(mesh_width/2))+10),0,0);
	woo3dv.spritey_y.position.set(0,0,((woo3dvGetFraction(woo3dv.font_size)*(mesh_length/2))+10));
	woo3dv.spritey_z.position.set(0,((woo3dvGetFraction(woo3dv.font_size)*(mesh_height/2))+10),0);

	if (!isNaN(woo3dv.offset_z) && woo3dv.offset_z!=0) {
		if (woo3dv.object.type=='Scene' || woo3dv.object.type=='Group') {
			woo3dv.object.position.y = woo3dv.offset_z;
		}
		else {
			//woo3dv.model_mesh.position.y = woo3dv.offset_z;
		}
	}

	if (woo3dv.object.type=='Scene' || woo3dv.object.type=='Group') {
		jQuery('#z_offset').val(woo3dv.object.position.y);
	}
	else {
		jQuery('#z_offset').val(woo3dv.model_mesh.position.y);
	}


}
function woo3dvUnhideLightSource(directionalLight) {
	var number_of_sources=0;

	for(var i=0;i<woo3dv.scene.children.length;i++) {
		if (woo3dv.scene.children[i].type=='DirectionalLight' && woo3dv.scene.children[i].visible) {
			number_of_sources++
		}
	}
	directionalLight.visible = true;
}

function woo3dvToggleLightSource(idx) {
	switch(idx) {
		case 1:
			if (typeof(woo3dv.directionalLight1)!=='undefined') {
				if (woo3dv.directionalLight1.visible) {
					woo3dv.directionalLight1.visible = false;
				}
				else {
					//woo3dv.directionalLight1.visible = true;
					woo3dvUnhideLightSource(woo3dv.directionalLight1);
				}
			}
			else {
				woo3dv.directionalLight1 = woo3dvMakeLight(1);
				woo3dvSetupLight(woo3dv.directionalLight1, 1);
			}
	        break;
		case 2:
			if (typeof(woo3dv.directionalLight2)!=='undefined') {
				if (woo3dv.directionalLight2.visible) {
					woo3dv.directionalLight2.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight2);
				}
			}
			else {
				woo3dv.directionalLight2 = woo3dvMakeLight(2);
				woo3dvSetupLight(woo3dv.directionalLight2, 2);
			}
	        break;
		case 3:
			if (typeof(woo3dv.directionalLight3)!=='undefined') {
				if (woo3dv.directionalLight3.visible) {
					woo3dv.directionalLight3.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight3);
				}
			}
			else {
				woo3dv.directionalLight3 = woo3dvMakeLight(3);
				woo3dvSetupLight(woo3dv.directionalLight3, 3);
			}
	        break;
		case 4:
			if (typeof(woo3dv.directionalLight4)!=='undefined') {
				if (woo3dv.directionalLight4.visible) {
					woo3dv.directionalLight4.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight4);
				}
			}
			else {
				woo3dv.directionalLight4 = woo3dvMakeLight(4);
				woo3dvSetupLight(woo3dv.directionalLight4, 4);
			}
	        break;
		case 5:
			if (typeof(woo3dv.directionalLight5)!=='undefined') {
				if (woo3dv.directionalLight5.visible) {
					woo3dv.directionalLight5.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight5);
				}
			}
			else {
				woo3dv.directionalLight5 = woo3dvMakeLight(5);
				woo3dvSetupLight(woo3dv.directionalLight5, 5);
			}
	        break;
		case 6:
			if (typeof(woo3dv.directionalLight6)!=='undefined') {
				if (woo3dv.directionalLight6.visible) {
					woo3dv.directionalLight6.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight6);
				}
			}
			else {
				woo3dv.directionalLight6 = woo3dvMakeLight(6);
				woo3dvSetupLight(woo3dv.directionalLight6, 6);
			}
	        break;
		case 7:
			if (typeof(woo3dv.directionalLight7)!=='undefined') {
				if (woo3dv.directionalLight7.visible) {
					woo3dv.directionalLight7.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight7);
				}
			}
			else {
				woo3dv.directionalLight7 = woo3dvMakeLight(7);
				woo3dvSetupLight(woo3dv.directionalLight7, 7);
			}
	        break;
		case 8:
			if (typeof(woo3dv.directionalLight8)!=='undefined') {
				if (woo3dv.directionalLight8.visible) {
					woo3dv.directionalLight8.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight8);
				}
			}
			else {
				woo3dv.directionalLight8 = woo3dvMakeLight(8);
				woo3dvSetupLight(woo3dv.directionalLight8, 8);
			}
	        break;
		case 9:
			if (typeof(woo3dv.directionalLight9)!=='undefined') {
				if (woo3dv.directionalLight9.visible) {
					woo3dv.directionalLight9.visible = false;
				}
				else {
					woo3dvUnhideLightSource(woo3dv.directionalLight9);
				}
			}
			else {
				woo3dv.directionalLight9 = woo3dvMakeLight(9);
				woo3dvSetupLight(woo3dv.directionalLight9, 9);
			}
	        break;
	}

	//recalculate intensity
	var number_of_sources=0;
	var intensity=0;
	for(var i=0;i<woo3dv.scene.children.length;i++) {
		if (woo3dv.scene.children[i].type=='DirectionalLight' && woo3dv.scene.children[i].visible) {
			number_of_sources++
		}
	}
	intensity = Math.min((1/number_of_sources)*1.5, 1);
	for(var i=0;i<woo3dv.scene.children.length;i++) {
		if (woo3dv.scene.children[i].type=='DirectionalLight' && woo3dv.scene.children[i].visible) {
			woo3dv.scene.children[i].intensity = intensity;
		}
	}
}

function woo3dvGetLightIntensity() {

	var number_of_sources=0;
	if (woo3dv.show_light_source1=='on') number_of_sources++;
	if (woo3dv.show_light_source2=='on') number_of_sources++;
	if (woo3dv.show_light_source3=='on') number_of_sources++;
	if (woo3dv.show_light_source4=='on') number_of_sources++;
	if (woo3dv.show_light_source5=='on') number_of_sources++;
	if (woo3dv.show_light_source6=='on') number_of_sources++;
	if (woo3dv.show_light_source7=='on') number_of_sources++;
	if (woo3dv.show_light_source8=='on') number_of_sources++;
	if (woo3dv.show_light_source9=='on') number_of_sources++;

	return Math.min((1/number_of_sources)*1.5, 1);

}

function woo3dvMakeLight(idx) {
	var intensity = woo3dvGetLightIntensity();
	var directionalLight = new THREEW.DirectionalLight( 0xffffff, intensity );
	directionalLight.name = "light"+idx;
	return directionalLight;
}


function woo3dvSetupLight(directionalLight, idx) {
	var model_dim = new Array();
	model_dim.x = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
	model_dim.y = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
	model_dim.z = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

	var max_side = Math.max(model_dim.x, model_dim.y, model_dim.z)

	switch(idx) {
		case 1:
			directionalLight.position.set( max_side*2, max_side*2, 0 );
	        break;
		case 2:
			directionalLight.position.set( max_side*2, max_side*2, max_side*2 );
	        break;
		case 3:
			directionalLight.position.set( 0, max_side*2, max_side*2 );
	        break;
		case 4:
			directionalLight.position.set( -max_side*2, max_side*2, max_side*2 );
	        break;
		case 5:
			directionalLight.position.set( -max_side*2, max_side*2, 0 );
	        break;
		case 6:
			directionalLight.position.set( -max_side*2, max_side*2, -max_side*2 );
	        break;
		case 7:
			directionalLight.position.set( 0, max_side*2, - max_side*2 );
	        break;
		case 8:
			directionalLight.position.set( max_side*2, max_side*2, -max_side*2 );
	        break;
		case 9:
			directionalLight.position.set( 0, max_side*2, 0 );
	        break;
	}
	if (Detector.webgl) {
		if (jQuery('#woo3dv-show-shadow').prop('checked')) {
			directionalLight.castShadow = true;
		}
		else {
			directionalLight.castShadow = false;
		}
		woo3dvMakeShadow(directionalLight);
	}
	woo3dv.scene.add( directionalLight );

}

function woo3dvMakeShadow(directionalLight) {
	var model_dim = new Array();
	model_dim.x = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
	model_dim.y = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
	model_dim.z = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

	var max_side = Math.max(model_dim.x, model_dim.y, model_dim.z)
//  	var bias = -0.001;
	var d = max_side;
//	if (d<30) bias = -0.0001;

  	var bias = -0.00001;
	directionalLight.shadow.camera.left = -d;
	directionalLight.shadow.camera.right = d;
	directionalLight.shadow.camera.top = d;
	directionalLight.shadow.camera.bottom = -d;
	directionalLight.shadow.camera.near = 0.01;
	directionalLight.shadow.camera.far = woo3dv.camera.far;
	directionalLight.shadow.mapSize.width = 2048;
	directionalLight.shadow.mapSize.height = 2048;
	directionalLight.shadow.bias = bias;
	directionalLight.shadow.radius = woo3dv.shadow_softness;

	if (directionalLight.shadow.map) {
		directionalLight.shadow.map.dispose(); 
		directionalLight.shadow.map = null;
	}
}


function woo3dvGetFraction(size) {

	model_dim=[];
	model_dim.x = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
	model_dim.y = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
	model_dim.z = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

	var max_side = Math.max(model_dim.x, model_dim.y, model_dim.z)
	var zero = []; zero.x=zero.y=zero.y=0;
	var dist = woo3dvLineLength(woo3dv.camera.position, zero);
	var vFOV = woo3dv.camera.fov * Math.PI / 180;        // convert vertical fov to radians
	var height = 2 * Math.tan( vFOV / 2 ) * dist; // visible height

//	var fraction = height/max_side;

	var fraction = height/size;

	if (fraction<1) fraction = 1;

	return fraction;
}

function woo3dvMakeTextSprite( message, parameters )  {
        if ( parameters === undefined ) parameters = {};
        var fontface = parameters.hasOwnProperty("fontface") ? parameters["fontface"] : "Arial";
        var fontsize = parameters.hasOwnProperty("fontsize") ? parameters["fontsize"] : 18;
        var borderThickness = parameters.hasOwnProperty("borderThickness") ? parameters["borderThickness"] : 4;
        var borderColor = parameters.hasOwnProperty("borderColor") ?parameters["borderColor"] : { r:0, g:0, b:0, a:1.0 };
        var backgroundColor = parameters.hasOwnProperty("backgroundColor") ?parameters["backgroundColor"] : { r:255, g:255, b:255, a:0 };
        var textColor = parameters.hasOwnProperty("textColor") ?parameters["textColor"] : { r:0, g:0, b:0, a:1.0 };

        var canvas = document.createElement('canvas');
//	canvas.width=256;
//	canvas.height=128;
        var context = canvas.getContext('2d');


//	console.log(context.canvas.width, context.canvas.height);
        context.font = "Bold " + fontsize + "px " + fontface;
        var metrics = context.measureText( message );
//console.log(metrics);
        var textWidth = metrics.width;

        context.fillStyle   = "rgba(" + backgroundColor.r + "," + backgroundColor.g + "," + backgroundColor.b + "," + backgroundColor.a + ")";
        context.strokeStyle = "rgba(" + borderColor.r + "," + borderColor.g + "," + borderColor.b + "," + borderColor.a + ")";

        context.lineWidth = borderThickness;
        woo3dvRoundRect(context, borderThickness/2, borderThickness/2, (textWidth + borderThickness) * 1.1, fontsize * 1.4 + borderThickness, 8);

        context.fillStyle = "rgba("+textColor.r+", "+textColor.g+", "+textColor.b+", 1.0)";
        context.fillText( message, borderThickness, fontsize + borderThickness);
//	context.globalAlpha = 0;
//console.log(context);

        var texture = new THREEW.Texture(canvas) 
        texture.needsUpdate = true;

        var spriteMaterial = new THREEW.SpriteMaterial( { map: texture } );
        var sprite = new THREEW.Sprite( spriteMaterial );
//	var scale_x = 0.5 * fontsize * woo3dvGetFraction(fontsize);
//	var scale_y = 0.25 * fontsize * woo3dvGetFraction(fontsize);
//	var scale_z = 0.75 * fontsize * woo3dvGetFraction(fontsize);

        sprite.scale.set(0.5 * fontsize * woo3dvGetFraction(fontsize), 0.25 * fontsize * woo3dvGetFraction(fontsize), 0.75 * fontsize * woo3dvGetFraction(fontsize));
        return sprite;  
}

function woo3dvRoundRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x+r, y);
    ctx.lineTo(x+w-r, y);
    ctx.quadraticCurveTo(x+w, y, x+w, y+r);
    ctx.lineTo(x+w, y+h-r);
    ctx.quadraticCurveTo(x+w, y+h, x+w-r, y+h);
    ctx.lineTo(x+r, y+h);
    ctx.quadraticCurveTo(x, y+h, x, y+h-r);
    ctx.lineTo(x, y+r);
    ctx.quadraticCurveTo(x, y, x+r, y);
    ctx.closePath();
    ctx.fill();
    ctx.stroke();   
}



function woo3dvCreateMaterial(model_shading) {

	var color = new THREEW.Color( parseInt(woo3dv.model_color, 16) );
	var shininess = woo3dvGetCurrentShininess();
	var transparency = woo3dvGetCurrentTransparency();

	//color.offsetHSL(0, 0, -0.1);

	if (Detector.webgl) {
		if (model_shading=='smooth') {
			var flat_shading = false;
		}
		else {
			var flat_shading = true;
		}


		var material = new THREEW.MeshPhongMaterial( { color: color, specular: shininess.specular, shininess: shininess.shininess, transparent:true, opacity:transparency, wireframe:false, flatShading:flat_shading, precision: 'mediump' } );
	}
	else {

		var material = new THREEW.MeshLambertMaterial( { color: color, vertexColors: THREEW.FaceColors, wireframe: false, overdraw:1, flatShading:true } );
	}

	return material;
}

function woo3dvCreateModel(object, geometry, material, shading) {

	woo3dv.model_mesh = new THREEW.Mesh(geometry, material);
	if (typeof(geometry.getAttribute)!=='undefined') {
		var attrib = geometry.getAttribute('position');
		if(attrib === undefined) {
			throw new Error('a given BufferGeometry object must have a position attribute.');
		}
		var positions = attrib.array;
		var vertices = [];
		for(var i = 0, n = positions.length; i < n; i += 3) {
			var x = positions[i];
			var y = positions[i + 1];
			var z = positions[i + 2];
			vertices.push(new THREEW.Vector3(x, y, z));
		}
		var faces = [];
		for(var i = 0, n = vertices.length; i < n; i += 3) {
			faces.push(new THREEW.Face3(i, i + 1, i + 2));
		}

		var new_geometry = new THREEW.Geometry();
		new_geometry.vertices = vertices;
		new_geometry.faces = faces;
		new_geometry.computeFaceNormals();              
		new_geometry.computeVertexNormals();
		new_geometry.computeBoundingBox();

		geometry = new_geometry;
		geometry.center();


		if (shading=='smooth' && Detector.webgl) {
	                var smooth_geometry = new THREEW.Geometry();
	                smooth_geometry.vertices = vertices;
	                smooth_geometry.faces = faces;
	                smooth_geometry.computeFaceNormals();              
	                smooth_geometry.mergeVertices();
	                smooth_geometry.computeVertexNormals();
			smooth_geometry.computeBoundingBox();
			geometry = smooth_geometry;
	                woo3dv.model_mesh = new THREEW.Mesh(geometry, material);
		}
		else {
			woo3dv.model_mesh = new THREEW.Mesh( geometry, material );
		}
	}
	else {
		woo3dv.model_mesh = new THREEW.Mesh(geometry, material);
	}

	if (woo3dv.object.type=='Group') { //obj
		if (!woo3dv.mtl || woo3dv.mtl.length==0) {
			//woo3dv.object.children[0].material=woo3dv.model_mesh.material;
			for (var i=0;i<woo3dv.object.children.length;i++) {
				woo3dv.object.children[i].material=woo3dv.model_mesh.material;
			}
		}

		woo3dv.object.position.set( 0, 0, 0 );
		woo3dv.object.rotation.z = parseFloat(woo3dv.default_rotation_y) * Math.PI/180;
		woo3dv.object.rotation.x = parseFloat(woo3dv.default_rotation_x) * Math.PI/180;
		woo3dv.object.name = "object";

		woo3dv.initial_rotation_x = woo3dv.object.rotation.x;
		woo3dv.initial_rotation_y = woo3dv.object.rotation.y;
		woo3dv.initial_rotation_z = woo3dv.object.rotation.z;


		if (Detector.webgl) {
			for (var i=0;i<woo3dv.object.children.length;i++) {
				woo3dv.object.children[i].castShadow = true;
				woo3dv.object.children[i].receiveShadow = true;
			}
		}
		woo3dv.scene.add( woo3dv.object );
		woo3dv.group.add( woo3dv.object );
	}
	else if (woo3dv.object.type=='Scene') { //wrl

		woo3dv.object.position.set( 0, 0, 0 );
		woo3dv.object.rotation.z = parseFloat(woo3dv.default_rotation_y) * Math.PI/180;
		woo3dv.object.rotation.x = parseFloat(woo3dv.default_rotation_x) * Math.PI/180;
		woo3dv.object.name = "object";

		woo3dv.initial_rotation_x = woo3dv.object.rotation.x;
		woo3dv.initial_rotation_y = woo3dv.object.rotation.y;
		woo3dv.initial_rotation_z = woo3dv.object.rotation.z;


		if (Detector.webgl) {
			woo3dv.object.traverse( function ( child ) {
				if ( child.isMesh ) {
					child.castShadow = true;
					child.receiveShadow = true;
				}
			} );
/*			woo3dv.object.traverse( function( object ) { 
				if ( object.isMesh ) {
					object.castShadow = true;
				}
			} );*/

		}
		woo3dv.scene.add( woo3dv.object );
		woo3dv.group.add( woo3dv.object );
	}
	else {
//
//		var mesh_height = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

//		woo3dv.model_mesh.computeBoundingBox();
//		woo3dv.boundingBox=woo3dv.model_mesh.boundingBox;
//		var mesh_height = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
		woo3dv.model_mesh.position.set( 0, 0, 0 );




		woo3dv.model_mesh.rotation.z = parseFloat(woo3dv.default_rotation_y) * Math.PI/180;
		woo3dv.model_mesh.rotation.x = parseFloat(woo3dv.default_rotation_x) * Math.PI/180;
		woo3dv.model_mesh.name = "model";

		woo3dv.initial_rotation_x = woo3dv.model_mesh.rotation.x;
		woo3dv.initial_rotation_y = woo3dv.model_mesh.rotation.y;
		woo3dv.initial_rotation_z = woo3dv.model_mesh.rotation.z;

		if (Detector.webgl) {
			woo3dv.model_mesh.castShadow = true;
			woo3dv.model_mesh.receiveShadow = true;
		}
		woo3dv.scene.add( woo3dv.model_mesh );
		woo3dv.group.add( woo3dv.model_mesh );

	}


	if (typeof(woo3dv.loader.byteLength)!=='undefined') {
		var precision = 2;
		if (parseFloat((woo3dv.loader.byteLength/1048576).toFixed(2))==0) precision = 3;
		jQuery('#woo3dv-file-stats-size').html((woo3dv.loader.byteLength/1048576).toFixed(precision));
	}

	if (typeof(woo3dv.model_mesh)!=='undefined') {
		if (woo3dv.object.type=='Scene') {
/*			woo3dv.object.traverse( function( object ) { //todo count faces (expensive)
				if ( object.isMesh ) {
				}
			} );
*/
		}
		else {
			jQuery('#woo3dv-file-stats-polygons').html(woo3dv.model_mesh.geometry.faces.length);
		}
	}



}


/*
function woo3dvMakeShadow() {
	var model_dim = new Array();
	model_dim.x = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
	model_dim.y = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
	model_dim.z = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

	var max_side = Math.max(model_dim.x, model_dim.y, model_dim.z)
//  	var bias = -0.001;
	var d = max_side;
//	if (d<30) bias = -0.0001;

  	var bias = -0.00001;
	directionalLight2.shadow.camera.left = directionalLight.shadow.camera.left = -d;
	directionalLight2.shadow.camera.right = directionalLight.shadow.camera.right = d;
	directionalLight2.shadow.camera.top = directionalLight.shadow.camera.top = d;
	directionalLight2.shadow.camera.bottom = directionalLight.shadow.camera.bottom = -d;
	directionalLight2.shadow.camera.near = directionalLight.shadow.camera.near = 1;
	directionalLight2.shadow.camera.far = directionalLight.shadow.camera.far = woo3dv.camera.far;
	directionalLight2.shadow.mapSize.width = directionalLight.shadow.mapSize.width = 2048;
	directionalLight2.shadow.mapSize.height = directionalLight.shadow.mapSize.height = 2048;
	directionalLight2.shadow.bias = directionalLight.shadow.bias = bias;

	if (directionalLight.shadow.map) {
		directionalLight.shadow.map.dispose(); 
		directionalLight.shadow.map = null;
		directionalLight2.shadow.map.dispose(); 
		directionalLight2.shadow.map = null;
	}
}
*/


function woo3dvOnWindowResize() {

	var woo3dv_canvas_width = jQuery('#woo3dv-cv').width()
	var woo3dv_canvas_height = jQuery('#woo3dv-cv').height()
	woo3dv.camera.aspect = woo3dv_canvas_width / woo3dv_canvas_height;
	woo3dv.camera.updateProjectionMatrix();
	woo3dv.renderer.setSize( woo3dv_canvas_width, woo3dv_canvas_height );


	woo3dvCanvasDetails();
}

function woo3dvCanvasDetails() {
	jQuery("#woo3dv-file-loading").css({
		top: jQuery("#woo3dv-cv").position().top+jQuery("#woo3dv-cv").height()/2-jQuery("#woo3dv-file-loading").height()/2,
		left: jQuery("#woo3dv-cv").position().left + jQuery("#woo3dv-cv").width()/2-jQuery("#woo3dv-file-loading").width()/2
	}) ;
}


woo3dv.frame_count=0;
function woo3dvAnimate() {
	window.requestAnimationFrame( woo3dvAnimate );
	woo3dv.group.rotation.y += ( woo3dv.targetRotation - woo3dv.group.rotation.y ) * 0.05;
	woo3dv.controls.update();

	if (typeof(woo3dv.boundingBox.max)!=='undefined' && typeof(woo3dv.spritey_x)!=='undefined') {
		var mesh_width = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
		var mesh_length = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
		var mesh_height = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;
		
		var fraction = woo3dvGetFraction(woo3dv.font_size);
		woo3dv.spritey_x.position.set(((fraction*(mesh_width/2))+10),0,0);
		woo3dv.spritey_y.position.set(0,0,((fraction*(mesh_length/2))+10));
		woo3dv.spritey_z.position.set(0,((fraction*(mesh_height/2))+10),0);
		var spritey_scale_x = 0.5 * woo3dv.font_size * fraction;
		var spritey_scale_y = 0.25 * woo3dv.font_size * fraction;
		var spritey_scale_z = 0.75 * woo3dv.font_size * fraction;

	        woo3dv.spritey_x.scale.set(spritey_scale_x, spritey_scale_y, spritey_scale_z);
	        woo3dv.spritey_y.scale.set(spritey_scale_x, spritey_scale_y, spritey_scale_z);
	        woo3dv.spritey_z.scale.set(spritey_scale_x, spritey_scale_y, spritey_scale_z);
	}

	if ( woo3dv.mixer ) woo3dv.mixer.update( woo3dv.clock.getDelta() );
	woo3dvRender();

}

function woo3dvRender() {

	if (Detector.webgl && woo3dv.ground_mirror=='on' && typeof(woo3dv.groundMirror)!=='undefined')
		woo3dv.groundMirror.render();
	woo3dv.renderer.render( woo3dv.scene, woo3dv.camera );

}







function woo3dvSkinlessMeshesNumber(object) {
	var num=0;
	object.traverse( function ( child ) {
		if ( child.isMesh && !child.isSkinnedMesh) {
			if (woo3dv.object.children.length == 1) {
				num++;
			}
		}
	})
//	if (num>1) todo warning?
	return num;
}

function woo3dvChangeModelColor(model_color) {
	if (!woo3dv.model_mesh) return;

	woo3dv.model_mesh.material.color.set(model_color);
//	woo3dv.model_mesh.material.color.set(parseInt(model_color, 16));
//"0x81d742"
	//woo3dv.model_mesh.material.color.offsetHSL(0, 0, -0.1);
	if (Detector.webgl) {
		var model_shininess = woo3dvGetCurrentShininess();
		woo3dv.model_mesh.material.shininess = model_shininess.shininess;
		if (typeof(woo3dv.model_mesh.material.specular)!=='undefined') {
			woo3dv.model_mesh.material.specular.set(model_shininess.specular);
		}

		var model_transparency = woo3dvGetCurrentTransparency();
		woo3dv.model_mesh.material.opacity = model_transparency;


		if (woo3dv.object && woo3dv.object.type=='Group' && !(woo3dv.mtl && woo3dv.mtl.length>0)) {
			for (var i=0;i<woo3dv.object.children.length;i++) {
				woo3dv.object.children[i].material=woo3dv.model_mesh.material;

			}

		}
		else if (woo3dv.object.type=='Scene') {

			woo3dv.object.traverse( function ( child ) {
				if ( child.isMesh && !child.isSkinnedMesh && !child.material.map && !child.material.envMap) {
					if (woo3dvSkinlessMeshesNumber(woo3dv.object)==1) {
						child.material = woo3dv.model_mesh.material;
					}
					else {
						//todo option to set a color for each mesh
					}
				}
				else if (child.isSkinnedMesh) {
//					child.material.color.set(model_color); //todo make configurable
				}
//				else console.log(child);
			} );
		}
	}
	jQuery('#woo3dv_model_color').val(model_color)

};


function woo3dvGetCurrentShininess() {

	switch(woo3dv.model_shininess) {
		case 'plastic':
			var shininess = 150;
			var specular = 0x111111;
	        break;
		case 'wood':
			var shininess = 15;
			var specular = 0x111111;
	        break;
		case 'metal':
			var shininess = 500;
			var specular = 0xc9c9c9;
	        break;
		default:
			var shininess = 150;
			var specular = 0x111111;

	}
	return {shininess: shininess, specular: specular};
}

function woo3dvSetCurrentShininess(model_shininess) {
	switch(model_shininess) {
		case 'plastic':
			var shininess = 150;
	        break;
		case 'wood':
			var shininess = 15;
	        break;
		case 'metal':
			var shininess = 500;
	        break;
		default:
			var shininess = 150;
	}
	jQuery('#woo3dv_model_shininess').val(model_shininess)
	woo3dv.model_mesh.material.shininess=shininess;
}

function woo3dvGetCurrentTransparency() {

	switch(woo3dv.model_transparency) {
		case 'opaque':
			var transparency = 1;
	        break;
		case 'resin':
			var transparency = 0.8;
	        break;
		case 'glass':
			var transparency = 0.6;
	        break;
		default:
			var transparency = 1;

	}
	return transparency;
}

function woo3dvSetCurrentTransparency(model_transparency) {

	switch(model_transparency) {
		case 'opaque':
			var transparency = 1;
	        break;
		case 'resin':
			var transparency = 0.8;
	        break;
		case 'glass':
			var transparency = 0.6;
	        break;
		default:
			var transparency = 1;

	}
	jQuery('#woo3dv_model_transparency').val(model_transparency)
	woo3dv.model_mesh.material.opacity=transparency;
}




function woo3dvDisplayUserDefinedProgressBar(show) {
	if(show) {
		jQuery('#woo3dv-file-loading').show();
	}
	else {
		if (!woo3dv.repairing) {
			jQuery('#woo3dv-file-loading').hide();
		}
	}
}



function woo3dvNoEnter(e) {
    if (e.which == 13) {
        return false;
    }
}

THREEW.STLLoader.prototype.parseASCII = function ( data ) {
		var geometry, length, normal, patternFace, patternNormal, patternVertex, result, text;
		geometry = new THREEW.BufferGeometry();
		patternFace = /facet([\s\S]*?)endfacet/g;



		var vertices = new Array();
		var normals = new Array();

		while ( ( result = patternFace.exec( data ) ) !== null ) {

			text = result[ 0 ];
			patternNormal = /normal[\s]+([\-+]?[0-9]+\.?[0-9]*([eE][\-+]?[0-9]+)?)+[\s]+([\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?)+[\s]+([\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?)+/g;

			while ( ( result = patternNormal.exec( text ) ) !== null ) {

				normal = new THREEW.Vector3( parseFloat( result[ 1 ] ), parseFloat( result[ 3 ] ), parseFloat( result[ 5 ] ) );

				normals.push(result[ 1 ]);
				normals.push(result[ 3 ]);
				normals.push(result[ 5 ]);
			}


			patternVertex = /vertex[\s]+([\-+]?[0-9]+\.?[0-9]*([eE][\-+]?[0-9]+)?)+[\s]+([\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?)+[\s]+([\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?)+/g;
			tetrahedron = new Array();
			var i = 1;

			while ( ( result = patternVertex.exec( text ) ) !== null ) {

				tetrahedron[i] = new Array();
				tetrahedron[i].push(parseFloat( result[ 1 ] ));
				tetrahedron[i].push(parseFloat( result[ 3 ] ));
				tetrahedron[i].push(parseFloat( result[ 5 ] ));

				vertices.push(parseFloat(result[ 1 ]));
				vertices.push(parseFloat(result[ 3 ]));
				vertices.push(parseFloat(result[ 5 ]));

				i++;
			}



		}

		var vertices32 = new Float32Array(vertices);
		var normals32 = new Float32Array(normals);
		geometry.addAttribute( 'position', new THREEW.BufferAttribute( vertices32, 3 ) );
		geometry.addAttribute( 'normal', new THREEW.BufferAttribute( normals32, 3 ) );

		geometry.computeBoundingBox();
		geometry.computeBoundingSphere();

		return geometry;
}
function woo3dvUniqid() {
    var ts=String(new Date().getTime()), i = 0, out = '';
    for(i=0;i<ts.length;i+=2) {        
       out+=Number(ts.substr(i, 2)).toString(36);    
    }
    return ('d'+out);
}


function woo3dvRotateModel(axis, degree) {

//console.log(axis, degree);
	if (isNaN(degree)) degree=0;

	if (axis=='x') {
		jQuery("#rotation_x").focus();
		if (woo3dv.object.type=='Group' || woo3dv.object.type=='Scene') {
			woo3dv.object.rotation.x=woo3dv.initial_rotation_x+woo3dvAngleToRadians(degree);
		}
		else woo3dv.model_mesh.rotation.x=woo3dv.initial_rotation_x+woo3dvAngleToRadians(degree);
	}
	if (axis=='y') {
		jQuery("#rotation_y").focus();

		if (woo3dv.object.type=='Group' || woo3dv.object.type=='Scene') {
			woo3dv.object.rotation.y=woo3dv.initial_rotation_y+woo3dvAngleToRadians(degree);
		}
		else woo3dv.model_mesh.rotation.y=woo3dv.initial_rotation_y+woo3dvAngleToRadians(degree);

	}
	if (axis=='z') {
		jQuery("#rotation_z").focus();
		if (woo3dv.object.type=='Group' || woo3dv.object.type=='Scene') {
			woo3dv.object.rotation.z=woo3dv.initial_rotation_z+woo3dvAngleToRadians(degree);
		}
		else woo3dv.model_mesh.rotation.z=woo3dv.initial_rotation_z+woo3dvAngleToRadians(degree);

	}


/*
	if (woo3dv.object.type=='Scene') {
		//calculate new dimensions
		var bbox = new THREEW.Box3().setFromObject(woo3dv.object);
		var mesh_height = bbox.max.y - bbox.min.y;
		var mesh_width = bbox.max.x - bbox.min.x;
		var mesh_length = bbox.max.z - bbox.min.z;
		woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2)
//		woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y
	}
*/
	        	
	if (woo3dv.object.type=='Group' ) {
		//calculate new dimensions
		var bbox = new THREEW.Box3().setFromObject(woo3dv.object);
		var mesh_height = bbox.max.y - bbox.min.y;
		var mesh_width = bbox.max.x - bbox.min.x;
		var mesh_length = bbox.max.z - bbox.min.z;
		woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2)
	}
	else if (woo3dv.object.type=='Scene') {
		//calculate new dimensions
		var bbox = new THREEW.Box3().setFromObject(woo3dv.scene);
		var mesh_height = bbox.max.y - bbox.min.y;
		var mesh_width = bbox.max.x - bbox.min.x;
		var mesh_length = bbox.max.z - bbox.min.z;
		woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y
	}
	else {
		//calculate new dimensions
		var bbox = new THREEW.Box3().setFromObject(woo3dv.model_mesh);
		var mesh_height = bbox.max.y - bbox.min.y;
		var mesh_width = bbox.max.x - bbox.min.x;
		var mesh_length = bbox.max.z - bbox.min.z;
		woo3dv.model_mesh.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2)
	}

	woo3dv.axis.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2);

	woo3dv.spritey_z.position.y = woo3dv.scene.getObjectByName('ground').position.y+mesh_height+10;

	woo3dv.spritey_x.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2);
	woo3dv.spritey_x.position.x = woo3dv.scene.getObjectByName('ground').position.x+(mesh_width/2)+10;

	woo3dv.spritey_y.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2);
	woo3dv.spritey_y.position.z = woo3dv.scene.getObjectByName('ground').position.z+(mesh_length/2)+10;

	if (!isNaN(woo3dv.offset_z) && woo3dv.offset_z!=0) {
		if (woo3dv.object.type=='Scene' || woo3dv.object.type=='Group') {
			woo3dv.object.position.y = woo3dv.offset_z;
			jQuery('#z_offset').val(woo3dv.object.position.y);
		}
		else {
			//woo3dv.model_mesh.position.y = woo3dv.offset_z;
//			jQuery('#z_offset').val(woo3dv.model_mesh.position.y);
		}
	}

}

function woo3dvAngleToRadians (angle) {
	return angle * (Math.PI / 180);
}
function woo3dvFitCameraToObject( camera, object, offset, controls ) {


	if (woo3dv.object.type!='Scene') {
		var mesh_width = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
		var mesh_length = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
		var mesh_height = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;
		var max_side = Math.max(mesh_width, mesh_length, mesh_height)

	    	const plane_width = max_side * 100;
		var aspect = Math.max(mesh_width, mesh_length)/mesh_height;

		woo3dv.camera.position.set(max_side*woo3dv.resize_scale, max_side*woo3dv.resize_scale, max_side*woo3dv.resize_scale);
/*
		if (aspect>1) {
			woo3dv.controls.target = new THREEW.Vector3(0, 0, 0);
		}
		else {
			woo3dv.controls.target = new THREEW.Vector3(0, mesh_height/2, 0);
		}
*/
		woo3dv.controls.target = new THREEW.Vector3(0, 0, 0);

		woo3dv.camera.far=plane_width*5;
		woo3dv.camera.updateProjectionMatrix();
		return;
	}


	offset =  offset || 1.35;


	const boundingBox = new THREEW.Box3().setFromObject(object);
	const center = boundingBox.getCenter(new THREEW.Vector3());
	const size = boundingBox.getSize(new THREEW.Vector3());


	const maxDim = Math.max( size.x, size.y, size.z );
	const fov = camera.fov * ( Math.PI / 180 );
    	const plane_width = maxDim * 100;


	cameraZ = Math.abs( maxDim / 2 * Math.tan( fov * 2 ) ); 
	r=camera.position.z*offset;
	cameraZ *= offset; 

	woo3dv.scene.updateMatrixWorld(); 

        var objectWorldPosition = new THREEW.Vector3(); 
        objectWorldPosition.setFromMatrixPosition( object.matrixWorld );
	const directionVector = camera.position.sub(objectWorldPosition);   
        const unitDirectionVector = directionVector.normalize(); 
        camera.position = unitDirectionVector.multiplyScalar(cameraZ); 
        camera.lookAt(objectWorldPosition); 


        const minZ = boundingBox.min.z;
        const cameraToFarEdge = ( minZ < 0 ) ? -minZ + cameraZ : cameraZ - minZ;
    


        camera.far = plane_width * 5;

        camera.updateProjectionMatrix();
    
        if ( controls ) {
    
            controls.target = center;
        
            //controls.maxDistance = cameraToFarEdge * 2;
	    if (typeof(controls.saveState)!='undefined')
	            controls.saveState();
    
        } else {
            camera.lookAt( center )
        }

    
}


function woo3dvLineLength(point1, point2) {

	return Math.sqrt( Math.pow((point1.x-point2.x),2) + Math.pow((point1.y-point2.y),2));

}

function woo3dvOffsetZ(z_offset) {

	if (woo3dv.object.type=='Scene' || woo3dv.object.type=='Group') {
		woo3dv.object.position.y = z_offset;
	}
	else {
		woo3dv.model_mesh.position.y = z_offset;
	}
}

function woo3dvChangeBackgroundColor(color) {
	if (typeof(woo3dv.scene)=='undefined') return;
	color = color.replace('#', '0x');
	woo3dv.scene.background = new THREEW.Color(parseInt(color, 16));
}


function woo3dvToggleGround(color) {
	if (typeof(woo3dv.scene)=='undefined') return;
	if (!woo3dv.scene.getObjectByName('ground').visible) {
		woo3dv.scene.getObjectByName('ground').visible = true;
	}
	else {
		woo3dv.scene.getObjectByName('ground').visible = false;
	}
}

function woo3dvChangeGroundColor(color) {
	if (typeof(woo3dv.scene)=='undefined') return;
	color = color.replace('#', '0x');
	woo3dv.scene.getObjectByName('ground').material.color = new THREEW.Color(parseInt(color, 16));
	jQuery('#woo3dv_shortcode').val('');
}

function woo3dvChangeFogColor(color) {
	if (typeof(woo3dv.scene)=='undefined') return;
	color = color.replace('#', '0x');
	woo3dv.scene.fog.color = new THREEW.Color(parseInt(color, 16));
}

function woo3dvToggleGrid() {
	if (typeof(woo3dv.scene)=='undefined') return;
	if (!woo3dv.scene.getObjectByName('grid').visible) {
		woo3dv.scene.getObjectByName('grid').visible = true;
	}
	else {
		woo3dv.scene.getObjectByName('grid').visible = false;
	}
}

function woo3dvToggleMirror() {
	if (typeof(woo3dv.scene)=='undefined') return;
	if (!woo3dv.scene.getObjectByName('mirror').visible) {
		woo3dv.scene.getObjectByName('ground').material.transparent = true;
		woo3dv.scene.getObjectByName('ground').material.opacity = 0.6;
		woo3dv.scene.getObjectByName('ground').material.shininess = 2500;
		woo3dv.scene.getObjectByName('mirror').visible = true;
	}
	else {
		woo3dv.scene.getObjectByName('ground').material.transparent = false;
		woo3dv.scene.getObjectByName('ground').material.opacity = 1;
		woo3dv.scene.getObjectByName('ground').material.shininess = 30;
		woo3dv.scene.getObjectByName('mirror').visible = false;

	}
}
function woo3dvToggleShadow(checkbox) {
	if (typeof(woo3dv.scene)=='undefined') return;
	if (checkbox.checked) {
		if (jQuery('#woo3dv-show-light-source1').prop('checked')) woo3dv.directionalLight1.castShadow=true;
		if (jQuery('#woo3dv-show-light-source2').prop('checked')) woo3dv.directionalLight2.castShadow=true;
		if (jQuery('#woo3dv-show-light-source3').prop('checked')) woo3dv.directionalLight3.castShadow=true;
		if (jQuery('#woo3dv-show-light-source4').prop('checked')) woo3dv.directionalLight4.castShadow=true;
		if (jQuery('#woo3dv-show-light-source5').prop('checked')) woo3dv.directionalLight5.castShadow=true;
		if (jQuery('#woo3dv-show-light-source6').prop('checked')) woo3dv.directionalLight6.castShadow=true;
		if (jQuery('#woo3dv-show-light-source7').prop('checked')) woo3dv.directionalLight7.castShadow=true;
		if (jQuery('#woo3dv-show-light-source8').prop('checked')) woo3dv.directionalLight8.castShadow=true;
		if (jQuery('#woo3dv-show-light-source9').prop('checked')) woo3dv.directionalLight9.castShadow=true;

		if (jQuery('#woo3dv-show-light-source10').prop('checked')) woo3dv.directionalLight10.castShadow=true;
		if (jQuery('#woo3dv-show-light-source20').prop('checked')) woo3dv.directionalLight20.castShadow=true;
		if (jQuery('#woo3dv-show-light-source30').prop('checked')) woo3dv.directionalLight30.castShadow=true;
		if (jQuery('#woo3dv-show-light-source40').prop('checked')) woo3dv.directionalLight40.castShadow=true;
		if (jQuery('#woo3dv-show-light-source50').prop('checked')) woo3dv.directionalLight50.castShadow=true;
		if (jQuery('#woo3dv-show-light-source60').prop('checked')) woo3dv.directionalLight60.castShadow=true;
		if (jQuery('#woo3dv-show-light-source70').prop('checked')) woo3dv.directionalLight70.castShadow=true;
		if (jQuery('#woo3dv-show-light-source80').prop('checked')) woo3dv.directionalLight80.castShadow=true;
		if (jQuery('#woo3dv-show-light-source90').prop('checked')) woo3dv.directionalLight90.castShadow=true;
	}
	else {
		if (typeof(woo3dv.directionalLight1)!='undefined') woo3dv.directionalLight1.castShadow=false;
		if (typeof(woo3dv.directionalLight2)!='undefined') woo3dv.directionalLight2.castShadow=false;
		if (typeof(woo3dv.directionalLight3)!='undefined') woo3dv.directionalLight3.castShadow=false;
		if (typeof(woo3dv.directionalLight4)!='undefined') woo3dv.directionalLight4.castShadow=false;
		if (typeof(woo3dv.directionalLight5)!='undefined') woo3dv.directionalLight5.castShadow=false;
		if (typeof(woo3dv.directionalLight6)!='undefined') woo3dv.directionalLight6.castShadow=false;
		if (typeof(woo3dv.directionalLight7)!='undefined') woo3dv.directionalLight7.castShadow=false;
		if (typeof(woo3dv.directionalLight8)!='undefined') woo3dv.directionalLight8.castShadow=false;
		if (typeof(woo3dv.directionalLight9)!='undefined') woo3dv.directionalLight9.castShadow=false;


	}
}

function woo3dvToggleAlpha(checkbox) {
	if (typeof(woo3dv.scene)=='undefined') return;
	if (checkbox.checked) {
		woo3dv.scene.getObjectByName('ground').visible = false;
		woo3dv.scene.getObjectByName('grid').visible = false;
		woo3dv.scene.getObjectByName('mirror').visible = false;
		jQuery('#woo3dv-show-grid').prop('checked', false);
		jQuery('#woo3dv-show-ground').prop('checked', false);
		jQuery('#woo3dv-show-mirror').prop('checked', false);
		jQuery('#woo3dv-show-grid').prop('disabled', true);
		jQuery('#woo3dv-show-ground').prop('disabled', true);
		jQuery('#woo3dv-show-mirror').prop('disabled', true);

		woo3dvChangeBackgroundColor('#FFFFFF');
	}
	else {

		jQuery('#woo3dv-show-grid').prop('disabled', false);
		jQuery('#woo3dv-show-ground').prop('disabled', false);
		jQuery('#woo3dv-show-mirror').prop('disabled', false);
		woo3dvChangeBackgroundColor(jQuery('#woo3dv-background-color').val());
	}

	
}
function woo3dvToggleRotation(checked) {
//console.log(checked);
	if (typeof(woo3dv.scene)=='undefined') return;
	if (!woo3dv.controls.autoRotate && checked) {
		woo3dv.controls.autoRotate = true;
		woo3dv.controls.autoRotateSpeed = (woo3dv.auto_rotation_direction == 'ccw' ? -parseInt(woo3dv.auto_rotation_speed) : parseInt(woo3dv.auto_rotation_speed));
	}
	else if (woo3dv.controls.autoRotate && !checked) {
		woo3dv.controls.autoRotate = false;
	}

}
//woo3dvChangeGridColor
function woo3dvChangeGridColor(color) {
	if (typeof(woo3dv.scene)=='undefined') return;
	color = color.replace('#', '0x');
	woo3dv.scene.getObjectByName('grid').material.color = new THREEW.Color(parseInt(color, 16));
	jQuery('#woo3dv_shortcode').val('');
}

function woo3dvToggleFog() {
	if (typeof(woo3dv.scene)=='undefined') return;
	if (woo3dv.scene.fog.far==Infinity) {
		woo3dv.scene.fog.far=woo3dv.scene.fog.oldfar
	}
	else {
		woo3dv.scene.fog.far=Infinity
	}
}

function woo3dvSaveProductImage() {
	if (confirm('This will replace your existing product featured image. Do you wish to continue?')) {

//		var thumbnail_data = window.woo3dv_canvas.toDataURL().replace('data:image/png;base64,','');
//window.woo3dv_canvas.toDataURL().replace('data:image/png;base64,','')
		woo3dv.axis.visible=false;
		woo3dv.spritey_x.visible=false;
		woo3dv.spritey_y.visible=false;
		woo3dv.spritey_z.visible=false;
		woo3dv.renderer.render( woo3dv.scene, woo3dv.camera );
		var thumbnail_data = jQuery('#woo3dv-cv').get(0).toDataURL().replace('data:image/png;base64,','');
		jQuery('#product_main_image_data').val(thumbnail_data);
		woo3dv.axis.visible=true;
		woo3dv.spritey_x.visible=true;
		woo3dv.spritey_y.visible=true;
		woo3dv.spritey_z.visible=true;
		alert("Done! Don't forget to save the product!");
	}
}


woo3dv.tab_warning_shown = false;
