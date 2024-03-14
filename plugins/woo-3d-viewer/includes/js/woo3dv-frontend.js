/**
 * @author Sergey Burkov, http://www.wp3dprinting.com
 * @copyright 2017
 */

woo3dv.aabb = new Array();
woo3dv.resize_scale = 1;
woo3dv.default_scale = 100;
woo3dv.cookie_expire = parseInt(woo3dv.cookie_expire);
woo3dv.boundingBox=[];
woo3dv.initial_rotation_x = 0;
woo3dv.initial_rotation_y = 0;
woo3dv.initial_rotation_z = 0;
woo3dv.current_model = '';
woo3dv.current_mtl = '';
woo3dv.font_size = 25;
woo3dv.wireframe = false;
woo3dv.vec = new THREEW.Vector3();
woo3dv.product_offset_z = false;
woo3dv.product_fullscreen = 0;

jQuery(document).ready(function(){

	if (!document.getElementById('woo3dv-cv')) return;
	if (jQuery('.woo3dv-canvas').length>1) {
		alert(woo3dv.text_multiple);
	}
	if (jQuery('#woo3dv_view3d_button').val()=='on') return;

	//shortcode page
	if (jQuery('#woo3dv_form_page_id').length==0 && jQuery('#woo3dv_page_id').length>0) {
		jQuery( "form.cart" ).prop('action', '');
		jQuery( "form.cart" ).append('<input type="hidden" id="woo3dv_form_page_id" name="woo3dv_page_id" value="'+jQuery('#woo3dv_page_id').val()+'" /> ');
	}

	woo3dvInit3D();

})

function woo3dvInit3D() {

	jQuery('.woo3dv-view3d-button-wrapper').hide();
	jQuery('.woo3dv-main-image').hide();
	jQuery('.woo3dv-thumbnail').hide();
	jQuery('#woo3dv-viewer').show();

	//workaround for shortcode pages
	woo3dv.model_url = jQuery('#woo3dv_model_url').val();
	woo3dv.model_mtl = jQuery('#woo3dv_model_mtl').val();
	woo3dv.model_color = jQuery('#woo3dv_model_color').val().replace('#', '0x');
	woo3dv.model_transparency = jQuery('#woo3dv_model_transparency').val();
	woo3dv.model_shininess = jQuery('#woo3dv_model_shininess').val();
	woo3dv.model_rotation_x = jQuery('#woo3dv_model_rotation_x').val();
	woo3dv.model_rotation_y = jQuery('#woo3dv_model_rotation_y').val();
	woo3dv.model_rotation_z = jQuery('#woo3dv_model_rotation_z').val();
	woo3dv.stored_position_x = parseFloat(jQuery('#woo3dv_camera_position_x').val());
	woo3dv.stored_position_y = parseFloat(jQuery('#woo3dv_camera_position_y').val());
	woo3dv.stored_position_z = parseFloat(jQuery('#woo3dv_camera_position_z').val());
	woo3dv.stored_lookat_x = parseFloat(jQuery('#woo3dv_camera_lookat_x').val());
	woo3dv.stored_lookat_y = parseFloat(jQuery('#woo3dv_camera_lookat_y').val());
	woo3dv.stored_lookat_z = parseFloat(jQuery('#woo3dv_camera_lookat_z').val());
	woo3dv.stored_controls_target_x = parseFloat(jQuery('#woo3dv_controls_target_x').val());
	woo3dv.stored_controls_target_y = parseFloat(jQuery('#woo3dv_controls_target_y').val());
	woo3dv.stored_controls_target_z = parseFloat(jQuery('#woo3dv_controls_target_z').val());
	woo3dv.offset_z = parseFloat(jQuery('#woo3dv_offset_z').val());
	if (jQuery('#woo3dv_background_transparency').val().length>1) {
		woo3dv.background_transparency = jQuery('#woo3dv_background_transparency').val();
	}
	else {
		woo3dv.background_transparency = 'off';
	}
	if (jQuery('#woo3dv_show_light_source1').val().length>1) woo3dv.show_light_source1 = jQuery('#woo3dv_show_light_source1').val();
	if (jQuery('#woo3dv_show_light_source2').val().length>1) woo3dv.show_light_source2 = jQuery('#woo3dv_show_light_source2').val();
	if (jQuery('#woo3dv_show_light_source3').val().length>1) woo3dv.show_light_source3 = jQuery('#woo3dv_show_light_source3').val();
	if (jQuery('#woo3dv_show_light_source4').val().length>1) woo3dv.show_light_source4 = jQuery('#woo3dv_show_light_source4').val();
	if (jQuery('#woo3dv_show_light_source5').val().length>1) woo3dv.show_light_source5 = jQuery('#woo3dv_show_light_source5').val();
	if (jQuery('#woo3dv_show_light_source6').val().length>1) woo3dv.show_light_source6 = jQuery('#woo3dv_show_light_source6').val();
	if (jQuery('#woo3dv_show_light_source7').val().length>1) woo3dv.show_light_source7 = jQuery('#woo3dv_show_light_source7').val();
	if (jQuery('#woo3dv_show_light_source8').val().length>1) woo3dv.show_light_source8 = jQuery('#woo3dv_show_light_source8').val();
	if (jQuery('#woo3dv_show_light_source9').val().length>1) woo3dv.show_light_source9 = jQuery('#woo3dv_show_light_source9').val();
	

	if (jQuery('#woo3dv_show_grid').val().length>1) woo3dv.show_grid = jQuery('#woo3dv_show_grid').val();
	if (jQuery('#woo3dv_grid_color').val().length>1) woo3dv.grid_color = jQuery('#woo3dv_grid_color').val().replace('#', '0x');
	if (jQuery('#woo3dv_show_ground').val().length>1) woo3dv.show_ground = jQuery('#woo3dv_show_ground').val();
	if (jQuery('#woo3dv_ground_color').val().length>1) woo3dv.ground_color = jQuery('#woo3dv_ground_color').val().replace('#', '0x');
	if (jQuery('#woo3dv_show_shadow').val().length>1) woo3dv.show_shadow = jQuery('#woo3dv_show_shadow').val();
	if (jQuery('#woo3dv_ground_mirror').val().length>1) woo3dv.ground_mirror = jQuery('#woo3dv_ground_mirror').val();
	if (jQuery('#woo3dv_auto_rotation').val().length>1) woo3dv.auto_rotation = jQuery('#woo3dv_auto_rotation').val();



	woo3dv.upload_url = jQuery('#woo3dv_upload_url').val();

	window.woo3dv_canvas = document.getElementById('woo3dv-cv');

	window.woo3dv_canvas.addEventListener('dblclick', function(){ 
		woo3dvToggleFullScreen();
	});

	woo3dvCanvasDetails();

	var logoTimerID = 0;

	woo3dv.targetRotation = 0;

	var model_type=woo3dv.model_url.split('.').pop().toLowerCase();
	woo3dvViewerInit(woo3dv.model_url, woo3dv.model_mtl, model_type);

	woo3dvAnimate();

	woo3dvBindSubmit();
}





function woo3dvViewerInit(model, mtl, ext) {
	var woo3dv_canvas = document.getElementById('woo3dv-cv');
	var woo3dv_canvas_width = jQuery('#woo3dv-cv').width()
	var woo3dv_canvas_height = jQuery('#woo3dv-cv').height()

	if (jQuery('div.product').length>0) {
		woo3dv.product_width=woo3dv_canvas_width;
		woo3dv.product_height=woo3dv_canvas_height;
	}

	woo3dv.current_model = model;
	woo3dv.current_mtl = mtl;

	woo3dv.mtl=mtl;

	//3D Renderer
	woo3dv.renderer = Detector.webgl? new THREEW.WebGLRenderer({ antialias: true, alpha: (woo3dv.background_transparency=='on' ? true : false), canvas: woo3dv_canvas, preserveDrawingBuffer: true }): new THREEW.CanvasRenderer({canvas: woo3dv_canvas});
//	if (this.woo3dv.background_transparency=='off') {
//		woo3dv.renderer.setClearColor( parseInt(woo3dv.background1, 16) );
//	}
//	woo3dv.renderer.setClearColor( parseInt(woo3dv.background1, 16) );
	woo3dv.renderer.setPixelRatio( window.devicePixelRatio );
	woo3dv.renderer.setSize( woo3dv_canvas_width, woo3dv_canvas_height );


	if (Detector.webgl) {

		woo3dv.renderer.gammaInput = true;
		woo3dv.renderer.gammaOutput = true;
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

	if (jQuery('#woo3dv_background1').val().length>0 && woo3dv.background_transparency=='off') {
		woo3dv.scene.background = new THREEW.Color(parseInt(jQuery('#woo3dv_background1').val().replace('#', '0x'), 16));
	}


	woo3dv.clock = new THREEW.Clock();
	//woo3dv.scene.fog = new THREEW.Fog( 0x72645b, 1, 300 );

	//Group
	if (woo3dv.group) woo3dv.scene.remove(woo3dv.group);
	woo3dv.group = new THREEW.Group();
	woo3dv.group.position.set( 0, 0, 0 )
	woo3dv.group.name = "group";
	woo3dv.scene.add( woo3dv.group );


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
	if (woo3dv.auto_rotation=='on' && !(woo3dv.mobile_no_animation=='on' && woo3dvMobileCheck())) {
		woo3dv.controls.autoRotate = true; 
		woo3dv.controls.autoRotateSpeed = (woo3dv.auto_rotation_direction == 'ccw' ? -parseInt(woo3dv.auto_rotation_speed) : parseInt(woo3dv.auto_rotation_speed));
	}

	woo3dv.controls.addEventListener( 'start', function() {
		woo3dv.controls.autoRotate = false;
	});


	if (woo3dv.enable_zoom!='on') {
		woo3dv.controls.enableZoom = false; 
	}

	if (woo3dv.enable_pan!='on') {
		woo3dv.controls.enablePan = false; 
	}

	if (woo3dv.enable_manual_rotation!='on') {
		woo3dv.controls.enableRotate = false; 
	}


	if (ext=='stl') {
		woo3dv.loader = new THREEW.STLLoader();
	}
/*	else if (ext=='dae') {
		woo3dv.loader = new THREEW.ColladaLoader();
	}*/
	else if (ext=='obj') {
		woo3dv.loader = new THREEW.OBJLoader();
	}
	else if (ext=='wrl') {
		woo3dv.loader = new THREEW.VRMLLoader();
	}
	else if (ext=='gltf' || ext=='glb') {
		THREEW.DRACOLoader.setDecoderPath( woo3dv.plugin_url+'includes/ext/threejs/js/libs/draco/gltf/' );
		woo3dv.loader = new THREEW.GLTFLoader();
		woo3dv.loader.setDRACOLoader( new THREEW.DRACOLoader() );
	}

	if (model.length>0) {
		woo3dvDisplayUserDefinedProgressBar(true);
		var mtlLoader = new THREEW.MTLLoader();
		mtlLoader.setPath( woo3dv.upload_url );

		if (ext=='obj' && mtl && mtl.length>0) {

			mtlLoader.load( mtl, function( materials ) {
				materials.preload();
				//var objLoader = new THREEW.OBJLoader();
				woo3dv.loader.setMaterials( materials );

				woo3dv.loader.load( model, function ( geometry ) {
		        	    woo3dvModelOnLoad(geometry);
				},
				function( e ){},
				function ( error ) {
					woo3dvDisplayUserDefinedProgressBar(false);
					if (typeof(error)=='object') {
						if (typeof(error.currentTarget)=='object') {
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
					if (typeof(error.currentTarget)=='object') {
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
					if (typeof(error.currentTarget)=='object') {
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
			},
			function( e ){},
			function ( error ) {
				woo3dvDisplayUserDefinedProgressBar(false);
				if (typeof(error)=='object') {
					if (typeof(error.currentTarget)=='object') {
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
//	}




	window.addEventListener( 'resize', woo3dvOnWindowResize, false );

}

function woo3dvMobileCheck () {
	var check = false;
	(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
};
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
            if (woo3dv.zoom_distance_max!='0') {
            	controls.maxDistance = cameraToFarEdge * 2;
            }
	    if (typeof(controls.saveState)!='undefined')
	            controls.saveState();
    
        } else {
            camera.lookAt( center )
        }

    
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


	//Model
	woo3dvCreateModel(object, geometry, material, woo3dv.shading);
	woo3dvChangeModelColor(woo3dv.model_color);


	if ((object.type=='Group'&& object.children.length>1) || object.type=='Scene') {
		new THREEW.Box3().setFromObject( woo3dv.object ).getCenter( woo3dv.object.position ).multiplyScalar( - 1 );
	    	woo3dv.boundingBox = new THREEW.Box3().setFromObject(object);
	}
	else {
		geometry.center();
	}


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
//mesh_diagonal
	var canvas_diagonal = Math.sqrt(canvas_width * canvas_width + canvas_height * canvas_height);
	var model_dim = new Array();
	model_dim.x = woo3dv.boundingBox.max.x - woo3dv.boundingBox.min.x;
	model_dim.y = woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y;
	model_dim.z = woo3dv.boundingBox.max.z - woo3dv.boundingBox.min.z;

	var max_side = Math.max(mesh_width, mesh_length, mesh_height)
//	var max_side_xy = Math.max(mesh_width, mesh_length)

//	var axis_length = Math.max(mesh_width, mesh_length);
///	var axis_width = Math.min(mesh_width, mesh_length);

//	console.log(Math.max(canvas_width , canvas_height));
//	var font_size = (Math.max(canvas_width , canvas_height)/30);	
//console.log(font_size);

	var plane_width = max_side * 100;
	var grid_step = plane_width/100;


	if (woo3dv.show_fog=='on' && woo3dv.background_transparency=='off') {
		woo3dv.scene.background = new THREEW.Color( parseInt(woo3dv.fog_color, 16) );
		woo3dv.scene.fog = new THREEW.Fog( parseInt(woo3dv.fog_color, 16), max_side*3, plane_width/2 ); 
	}
//0xa0a0a0


	//Zoom Distance
	var zoom_distance_min = parseFloat(woo3dv.zoom_distance_min);
	var zoom_distance_max = parseFloat(woo3dv.zoom_distance_max);

	if (zoom_distance_min>0) {
		woo3dv.controls.minDistance = (max_side / 100) * zoom_distance_min;
	}

	if (zoom_distance_max>0) {
		woo3dv.controls.maxDistance = (max_side / 100) * zoom_distance_max;
	}


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
//console.log(plane_width);
		woo3dv.camera.far=plane_width*5;
		woo3dv.camera.updateProjectionMatrix();
	}
	else {//auto set
		woo3dvFitCameraToObject(woo3dv.camera, woo3dv.object, 1.2, woo3dv.controls);
	}

//woo3dvFitCameraToObject(woo3dv.camera, woo3dv.object, 1.35, woo3dv.controls);

//	mesh_width, mesh_height

	//Ground
	if (Detector.webgl && woo3dv.background_transparency=='off') {
		if (woo3dv.ground_mirror=='on') {
			var plane_shininess = 2500;
			var plane_transparent = true;
			var plane_opacity = 0.6;
		}
		else {
			var plane_shininess = 30;
			var plane_transparent = false;
			var plane_opacity = 1;
		}
		if (woo3dvMobileCheck()) {
			var plane_material = new THREEW.MeshLambertMaterial( { color: parseInt(woo3dv.ground_color, 16), wireframe: false, flatShading:true, precision: 'mediump' } );
		}
		else {
			var plane_material = new THREEW.MeshPhongMaterial ( { color: parseInt(woo3dv.ground_color, 16), transparent:plane_transparent, opacity:plane_opacity, shininess: plane_shininess, precision: 'mediump' } ) 
		}
		plane = new THREEW.Mesh(
			new THREEW.PlaneBufferGeometry( plane_width, plane_width ),
			new THREEW.MeshPhongMaterial ( { color: parseInt(woo3dv.ground_color, 16), transparent:plane_transparent, opacity:plane_opacity, shininess: plane_shininess } ) 
		);
		if (woo3dv.show_ground!='on') {
			plane.visible = false;
		}

		plane.rotation.x = -Math.PI/2;
		plane.position.y = woo3dv.boundingBox.min.z;

		plane.receiveShadow = true;
		plane.castShadow = true;
		plane.name = 'ground';
		woo3dv.scene.add( plane );
		if (woo3dv.ground_mirror=='on') {
			var planeGeo = new THREEW.PlaneBufferGeometry( plane_width, plane_width );
			woo3dv.groundMirror = new THREEW.Mirror( woo3dv.renderer, woo3dv.camera, { clipBias: 0.003, textureWidth: canvas_width, textureHeight: canvas_height, color: 0xaaaaaa } );
			var mirrorMesh = new THREEW.Mesh( planeGeo, woo3dv.groundMirror.material );
			mirrorMesh.position.y = woo3dv.boundingBox.min.z-camera_near;
			mirrorMesh.add( woo3dv.groundMirror );
			mirrorMesh.rotateX( - Math.PI / 2 );
			mirrorMesh.name = 'mirror';
			woo3dv.scene.add( mirrorMesh );
		}

	}

	if (woo3dv.object.type=='Scene') {
		//calculate new dimensions
		var bbox = new THREEW.Box3().setFromObject(woo3dv.object);
		var mesh_height = bbox.max.y - bbox.min.y;
		var mesh_width = bbox.max.x - bbox.min.x;
		var mesh_length = bbox.max.z - bbox.min.z;
		if (typeof(woo3dv.scene.getObjectByName('ground'))!='undefined') {
			woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y
		}
	}
//	else {
//		woo3dv.model_mesh.position.y = woo3dv.scene.getObjectByName('ground').position.y ;
//	}

	//Grid
	if (woo3dv.show_grid=='on' && woo3dv.grid_color.length>0 && woo3dv.background_transparency=='off') {
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

	if (jQuery("#woo3dv_model_rotation_x").val()!=0)
		woo3dvRotateModel('x', jQuery("#woo3dv_model_rotation_x").val());
	if (jQuery("#woo3dv_model_rotation_y").val()!=0)
		woo3dvRotateModel('y', jQuery("#woo3dv_model_rotation_y").val());
	if (jQuery("#woo3dv_model_rotation_z").val()!=0)
		woo3dvRotateModel('z', jQuery("#woo3dv_model_rotation_z").val());

	var variation_id = jQuery('input[name=variation_id]').val();

	if (variation_id) {
		//woo3dvLoadVariationOptions(variation_id);
	}

	if (!isNaN(woo3dv.offset_z) && parseFloat(woo3dv.offset_z)!=0) {
		if (woo3dv.object.type=='Scene' || woo3dv.object.type=='Group') {
			woo3dv.object.position.y = woo3dv.offset_z;
		}
		else {
			//woo3dv.model_mesh.position.y = woo3dv.offset_z;
		}
	}


}
function woo3dvCreateMaterial(model_shading) {

	var color = new THREEW.Color( parseInt(woo3dv.model_color, 16) );
	var shininess = woo3dvGetCurrentShininess(null);
	var transparency = woo3dvGetCurrentTransparency(null);

//	color.offsetHSL(0, 0, -0.1);
//console.log(woo3dvMobileCheck());
	if (Detector.webgl && !woo3dvMobileCheck()) {
//	if (Detector.webgl) {
		if (model_shading=='smooth') {
			var flat_shading = false;
		}
		else {
			var flat_shading = true;
		}


		var material = new THREEW.MeshPhongMaterial( { color: color, specular: shininess.specular, shininess: shininess.shininess, transparent:true, opacity:transparency, wireframe:false, flatShading:flat_shading, precision: 'mediump' } );
	}
	else {

		var material = new THREEW.MeshLambertMaterial( { color: color, transparent:true, opacity:transparency, wireframe: false, flatShading:true, precision: 'mediump'} );
	}
	return material;
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
	if (Detector.webgl && woo3dv.show_shadow=='on') {
		directionalLight.castShadow = true;
		woo3dvMakeShadow(directionalLight);
	}
	woo3dv.scene.add( directionalLight );
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

			woo3dv.object.traverse( function ( child ) {
				if ( child.isMesh ) {
					child.castShadow = true;
					child.receiveShadow = true;
				}
			} );
		woo3dv.scene.add( woo3dv.object );
		woo3dv.group.add( woo3dv.object );
	}
	else if (woo3dv.object.type=='Scene') { //wrl, gltf

		woo3dv.object.position.set( 0, 0, 0 );

		woo3dv.object.rotation.z = parseFloat(woo3dv.default_rotation_y) * Math.PI/180;
		woo3dv.object.rotation.x = parseFloat(woo3dv.default_rotation_x) * Math.PI/180;
		woo3dv.object.name = "object";

		woo3dv.initial_rotation_x = woo3dv.object.rotation.x;
		woo3dv.initial_rotation_y = woo3dv.object.rotation.y;
		woo3dv.initial_rotation_z = woo3dv.object.rotation.z;

		if (Detector.webgl) {
			woo3dv.object.traverse( function ( child ) {
//console.log(child.type);
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
//		woo3dv.model_mesh.position.set( 0, (woo3dv.boundingBox.max.y - woo3dv.boundingBox.min.y)/2, 0 );
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




}


function woo3dvMakeLight(idx) {
	var intensity = woo3dvGetLightIntensity();
	var directionalLight = new THREEW.DirectionalLight( 0xffffff, intensity );
	directionalLight.name = "light"+idx;
	return directionalLight;
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



function woo3dvOnWindowResize() {

	if (THREEx.FullScreen.activated()) {
		woo3dv.product_fullscreen = 2;
	}


	if (jQuery('div.product').length>0) { //product page
		var woo3dv_canvas_width = jQuery('#woo3dv-viewer').parent().width()
		var woo3dv_canvas_height = jQuery('#woo3dv-viewer').width()


		if (woo3dv.product_fullscreen && !THREEx.FullScreen.activated() && typeof(woo3dv.product_width)!='undefined' && typeof(woo3dv.product_height)!='undefined') { //cancelling fullscreen
			woo3dv.product_fullscreen--;
			var woo3dv_canvas_width = parseFloat(woo3dv.product_width)
			var woo3dv_canvas_height = parseFloat(woo3dv.product_height)
		}
	}
	else {
		var woo3dv_canvas_width = jQuery('#woo3dv-viewer').width()
		var woo3dv_canvas_height = jQuery('#woo3dv-viewer').height()
	}

	if (THREEx.FullScreen.activated()) {
		woo3dv_canvas_width = window.innerWidth;
		woo3dv_canvas_height = window.innerHeight;
	}
	woo3dv.camera.aspect = woo3dv_canvas_width / woo3dv_canvas_height;
	woo3dv.camera.updateProjectionMatrix();
	woo3dv.renderer.setSize( woo3dv_canvas_width, woo3dv_canvas_height );


	woo3dvCanvasDetails();
}

function woo3dvCanvasDetails() {
/*	jQuery("#woo3dv-file-loading").css({
		top: jQuery("#woo3dv-cv").position().top+jQuery("#woo3dv-cv").height()/2-jQuery("#woo3dv-file-loading").height()/2,
		left: jQuery("#woo3dv-cv").position().left + jQuery("#woo3dv-cv").width()/2-jQuery("#woo3dv-file-loading").width()/2
	}) ;
*/
}




function woo3dvAnimate() {
	window.requestAnimationFrame( woo3dvAnimate );
	woo3dv.group.rotation.y += ( woo3dv.targetRotation - woo3dv.group.rotation.y ) * 0.05;
	woo3dv.controls.update();

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
	model_color = model_color.replace('#', '0x');
	woo3dv.model_mesh.material.color.set(parseInt(model_color, 16));
//	woo3dv.model_mesh.material.color.offsetHSL(0, 0, -0.1);
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

};


function woo3dvGetCurrentShininess(shininess) {
	if (!shininess) shininess = woo3dv.model_shininess;
	switch(shininess) {
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

function woo3dvGetCurrentTransparency(transparency) {
	if (!transparency) transparency = woo3dv.model_transparency;
	switch(transparency) {
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


function woo3dvRotateModel(axis, degree) {
//console.log(axis, degree);
	if (isNaN(degree)) degree=0;

	if (axis=='x') {
		jQuery("#rotation_x").focus();
		woo3dv.model_mesh.rotation.x=woo3dv.initial_rotation_x+woo3dvAngleToRadians(degree);

		if (woo3dv.object.type=='Group' || woo3dv.object.type=='Scene') {
			woo3dv.object.rotation.x=woo3dv.initial_rotation_x+woo3dvAngleToRadians(degree);
		}
	}
	if (axis=='y') {
		jQuery("#rotation_y").focus();
		woo3dv.model_mesh.rotation.y=woo3dv.initial_rotation_y+woo3dvAngleToRadians(degree);
		if (woo3dv.object.type=='Group' || woo3dv.object.type=='Scene') {
			woo3dv.object.rotation.y=woo3dv.initial_rotation_y+woo3dvAngleToRadians(degree);
		}
	}
	if (axis=='z') {
		jQuery("#rotation_z").focus();
		woo3dv.model_mesh.rotation.z=woo3dv.initial_rotation_z+woo3dvAngleToRadians(degree);
		if (woo3dv.object.type=='Group' || woo3dv.object.type=='Scene') {
			woo3dv.object.rotation.z=woo3dv.initial_rotation_z+woo3dvAngleToRadians(degree);
		}
	}

	if (typeof(woo3dv.scene.getObjectByName('ground'))!=='undefined' || woo3dv.object.type=='Scene') {
		if (woo3dv.object.type=='Group') {
			//calculate new dimensions
			var bbox = new THREEW.Box3().setFromObject(woo3dv.object);
			var mesh_height = bbox.max.y - bbox.min.y;
			var mesh_width = bbox.max.x - bbox.min.x;
			var mesh_length = bbox.max.z - bbox.min.z;
			woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2)
		}
		else if (woo3dv.object.type=='Scene') {
			//calculate new dimensions
			var bbox = new THREEW.Box3().setFromObject(woo3dv.object);
			var mesh_height = bbox.max.y - bbox.min.y;
			var mesh_width = bbox.max.x - bbox.min.x;
			var mesh_length = bbox.max.z - bbox.min.z;
//			woo3dv.object.position.y = woo3dv.scene.getObjectByName('ground').position.y
		}
		else {
			//calculate new dimensions
			var bbox = new THREEW.Box3().setFromObject(woo3dv.model_mesh);
			var mesh_height = bbox.max.y - bbox.min.y;
			var mesh_width = bbox.max.x - bbox.min.x;
			var mesh_length = bbox.max.z - bbox.min.z;
			woo3dv.model_mesh.position.y = woo3dv.scene.getObjectByName('ground').position.y+(mesh_height/2)
		}
	}

	if (!isNaN(woo3dv.offset_z) && parseFloat(woo3dv.offset_z)!=0) {
		if (woo3dv.object.type=='Scene' || woo3dv.object.type=='Group') {
			woo3dv.object.position.y = woo3dv.offset_z;
		}
		else {
			//woo3dv.model_mesh.position.y = woo3dv.offset_z;
		}
	}

}

function woo3dvAngleToRadians (angle) {
	return angle * (Math.PI / 180);
}

function woo3dvGoScreen() {
	if (!THREEx.FullScreen.available()) {
		alert(woo3dv.text_not_available);
	}
	THREEx.FullScreen.request(document.getElementById('woo3dv-cv'));
}

function woo3dvToggleFullScreen() {
	if (THREEx.FullScreen.activated()) {
		THREEx.FullScreen.cancel();
	}
	else {
		if (!THREEx.FullScreen.available()) {
			alert(woo3dv.text_not_available);
			return;
		}
//		THREEx.FullScreen.request(jQuery(this).closest('.woo3dv-viewer').get(0));
		THREEx.FullScreen.request(jQuery('#woo3dv-viewer').get(0));
	}
}

function woo3dvToggleWireframe() {
	if (Detector.webgl) {

		if (woo3dv.model_mesh && typeof(woo3dv.model_mesh.material)!=='undefined') {
			woo3dv.model_mesh.material.wireframe = !woo3dv.model_mesh.material.wireframe;
		}

		if (woo3dv.object && woo3dv.object.type=='Group') {
			for (var i=0;i<woo3dv.object.children.length;i++) {
				woo3dv.object.children[i].material.wireframe = !woo3dv.object.children[i].material.wireframe;
			}
		}
		else if (woo3dv.object && woo3dv.object.type=='Scene') { 
			woo3dv.object.traverse( function ( child ) {
				if ( child.isMesh || child.isSkinnedMesh ) {
					child.material.wireframe = !woo3dv.wireframe;
//					child.material.wireframe = !child.material.wireframe;
				}
			} );
		}
		woo3dv.wireframe = !woo3dv.wireframe;
	}
}

function woo3dvZoomIn() {
	var offset = 0.8;
	woo3dv.camera.position.set(woo3dv.camera.position.x*offset, woo3dv.camera.position.y*offset, woo3dv.camera.position.z*offset);
}

function woo3dvZoomOut() {
	var offset = 1.2;
	woo3dv.camera.position.set(woo3dv.camera.position.x*offset, woo3dv.camera.position.y*offset, woo3dv.camera.position.z*offset);
}

function woo3dvToggleRotation() {
	woo3dv.controls.autoRotate = !woo3dv.controls.autoRotate;
	woo3dv.controls.autoRotateSpeed = (woo3dv.auto_rotation_direction == 'ccw' ? -parseInt(woo3dv.auto_rotation_speed) : parseInt(woo3dv.auto_rotation_speed));
}

function woo3dvScreenshot() {
	var file_name = woo3dv.model_url.split('/').reverse()[0]+'.png';
	document.getElementById("woo3dv-screenshot").download = file_name;
	document.getElementById("woo3dv-screenshot").href = document.getElementById("woo3dv-cv").toDataURL("image/png").replace(/^data:image\/[^;]/, 'data:application/octet-stream');
}

function woo3dvBindSubmit() {
	jQuery( "form.cart" ).on( "submit", function(e) {
		if (woo3dv.override_cart_thumbnail=='on') {
			woo3dvSetThumbnail();
		}
	})
}

function woo3dvSetThumbnail () {
	var thumbnail_data = jQuery('#woo3dv-cv').first().get(0).toDataURL().replace('data:image/png;base64,','');
	jQuery('#woo3dv_thumbnail').val(thumbnail_data);
}