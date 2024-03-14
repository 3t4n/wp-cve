<?php

/**
 *
 *
 * @author Sergey Burkov, http://www.wp3dprinting.com
 * @copyright 2017
 */

function woo3dv_activate() {
	global $wpdb;

//	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
//		deactivate_plugins( plugin_basename( __FILE__ ) );
//		wp_die ('Woocommerce is not installed!');
//	}

	$current_version = get_option( 'woo3dv_version');


	woo3dv_check_install();

	add_option( 'woo3dv_do_activation_redirect', true );

	update_option( 'woo3dv_version', WOO3DV_VERSION );

	do_action( 'woo3dv_activate' );
}

function woo3dv_check_install() {
	global $wpdb;

	$current_version = get_option( 'woo3dv_version');
	$charset_collate = $wpdb->get_charset_collate();

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$default_image_url = str_replace('http:','',plugins_url()).'/woo-3d-viewer/images/';

	$current_settings = get_option( 'woo3dv_settings' );

	update_option( 'woo3dv_servers',  array(0=>'http://srv1.wp3dprinting.com', 1=>'http://srv2.wp3dprinting.com') );

	if (!isset($current_settings['display_mode'])) $display_mode = '3d_model';
	else $display_mode = $current_settings['display_mode'];
	if (!isset($current_settings['display_mode_mobile'])) $display_mode_mobile = $display_mode;
	else $display_mode_mobile = $current_settings['display_mode_mobile'];

	$settings=array(
		'file_extensions' => 'stl,obj,gltf,glb,zip',
		'display_mode' => $display_mode,
		'display_mode_mobile' => $display_mode_mobile,
		'canvas_width' => (isset($current_settings['canvas_width']) ? $current_settings['canvas_width'] : '1024'),
		'canvas_height' => (isset($current_settings['canvas_height']) ? $current_settings['canvas_height'] : '768'),
		'canvas_border' => (isset($current_settings['canvas_border']) ? $current_settings['canvas_border'] : 'on'),
		'shading' => (isset($current_settings['shading']) ? $current_settings['shading'] : 'flat'),
		'auto_rotation' => (isset($current_settings['auto_rotation']) ? $current_settings['auto_rotation'] : 'on'),
		'auto_rotation_speed' => (isset($current_settings['auto_rotation_speed']) ? $current_settings['auto_rotation_speed'] : '6'),
		'auto_rotation_direction' => (isset($current_settings['auto_rotation_direction']) ? $current_settings['auto_rotation_direction'] : 'ccw'),
		'default_rotation_x' => (isset($current_settings['default_rotation_x']) ? $current_settings['default_rotation_x'] : '-90'),
		'default_rotation_y' => (isset($current_settings['default_rotation_y']) ? $current_settings['default_rotation_y'] : '90'),
		'background1' => (isset($current_settings['background1']) ? $current_settings['background1'] : '#FFFFFF'),
		'background2' => (isset($current_settings['background2']) ? $current_settings['background2'] : '#1e73be'),
		'model_default_color' => (isset($current_settings['model_default_color']) ? $current_settings['model_default_color'] : '#ffffff'),
		'model_default_shininess' => (isset($current_settings['model_default_shininess']) ? $current_settings['model_default_shininess'] : 'plastic'),
		'model_default_transparency' => (isset($current_settings['model_default_transparency']) ? $current_settings['model_default_transparency'] : 'opaque'),
		'grid_color' => (isset($current_settings['grid_color']) ? $current_settings['grid_color'] : '#898989'),
		'fog_color' => (isset($current_settings['fog_color']) ? $current_settings['fog_color'] : '#FFFFFF'),
		'ground_color' => (isset($current_settings['ground_color']) ? $current_settings['ground_color'] : '#c1c1c1'),
		'ground_mirror' => (isset($current_settings['ground_mirror']) ? $current_settings['ground_mirror'] : ''),
		'show_light_source1' => (isset($current_settings['show_light_source1']) ? $current_settings['show_light_source1'] : ''),
		'show_light_source2' => (isset($current_settings['show_light_source2']) ? $current_settings['show_light_source2'] : 'on'),
		'show_light_source3' => (isset($current_settings['show_light_source3']) ? $current_settings['show_light_source3'] : ''),
		'show_light_source4' => (isset($current_settings['show_light_source4']) ? $current_settings['show_light_source4'] : ''),
		'show_light_source5' => (isset($current_settings['show_light_source5']) ? $current_settings['show_light_source5'] : ''),
		'show_light_source6' => (isset($current_settings['show_light_source6']) ? $current_settings['show_light_source6'] : 'on'),
		'show_light_source7' => (isset($current_settings['show_light_source7']) ? $current_settings['show_light_source7'] : ''),
		'show_light_source8' => (isset($current_settings['show_light_source8']) ? $current_settings['show_light_source8'] : ''),
		'show_light_source9' => (isset($current_settings['show_light_source9']) ? $current_settings['show_light_source9'] : ''),
		'show_shadow' => (isset($current_settings['show_shadow']) ? $current_settings['show_shadow'] : ''),
		'shadow_softness' => (isset($current_settings['show_softness']) ? $current_settings['show_softness'] : '1'),
		'show_ground' => (isset($current_settings['show_ground']) ? $current_settings['show_ground'] : 'on'),
		'ajax_loader' => (isset($current_settings['ajax_loader']) ? $current_settings['ajax_loader'] : $default_image_url.'ajax-loader.gif'),
		'view3d_button_image' => (isset($current_settings['view3d_button_image']) ? $current_settings['view3d_button_image'] : $default_image_url.'view3d.png'),
		'show_view3d_button' => (isset($current_settings['show_view3d_button']) ? $current_settings['show_view3d_button'] : ''),
		'show_grid' => (isset($current_settings['show_grid']) ? $current_settings['show_grid'] : 'on'),
		'show_fog' => (isset($current_settings['show_fog']) ? $current_settings['show_fog'] : ''),
		'show_controls' => (isset($current_settings['show_controls']) ? $current_settings['show_controls'] : 'on'),
		'zoom_distance_min' => (isset($current_settings['zoom_distance_min']) ? $current_settings['zoom_distance_min'] : ''),
		'zoom_distance_max' => (isset($current_settings['zoom_distance_min']) ? $current_settings['zoom_distance_max'] : ''),
		'enable_zoom' => (isset($current_settings['enable_zoom']) ? $current_settings['enable_zoom'] : 'on'),
		'enable_pan' => (isset($current_settings['enable_pan']) ? $current_settings['enable_pan'] : 'on'),
		'enable_manual_rotation' => (isset($current_settings['enable_manual_rotation']) ? $current_settings['enable_manual_rotation'] : 'on'),
		'api_login' => (isset($current_settings['api_login']) ? $current_settings['api_login'] : ''),
		'load_everywhere' => (isset($current_settings['load_everywhere']) ? $current_settings['load_everywhere'] : 'on'),
		'file_chunk_size' => (isset($current_settings['file_chunk_size']) ? $current_settings['file_chunk_size'] : '2'),
		'model_compression' => (isset($current_settings['model_compression']) ? $current_settings['model_compression'] : 'on'),
		'model_compression_threshold' => (isset($current_settings['model_compression_threshold']) ? $current_settings['model_compression_threshold'] : '1'),
		'proxy' => (isset($current_settings['proxy']) ? $current_settings['proxy'] : ''),
		'proxy_domains' => (isset($current_settings['proxy_domains']) ? $current_settings['proxy_domains'] : ''),
		'override_cart_thumbnail' => (isset($current_settings['override_cart_thumbnail']) ? $current_settings['override_cart_thumbnail'] : 'on'),
		'mobile_no_animation' => (isset($current_settings['mobile_no_animation']) ? $current_settings['mobile_no_animation'] : '')
	);


	update_option( 'woo3dv_settings', $settings );

	$upload_dir = wp_upload_dir();

	if ( !is_dir( $upload_dir['basedir'].'/woo3dv/' ) ) {
		mkdir( $upload_dir['basedir'].'/woo3dv/' );
	}

	if ( !file_exists( $upload_dir['basedir'].'/woo3dv/index.html' ) ) {
		$fp = fopen( $upload_dir['basedir'].'/woo3dv/index.html', "w" );
		fclose( $fp );
	}


	update_option( 'woo3dv_version', WOO3DV_VERSION );

}

function woo3dv_filter_update_checks($queryArgs) {
	$settings = get_option('woo3dv_settings');
	if ( !empty($settings['api_login']) ) {
		$queryArgs['login'] = $settings['api_login'];

	}
	return $queryArgs;
}

function woo3dv_get_option ($option) {
	return get_option($option);
}

function woo3dv_add_option ($option, $data) {

	add_option($data);
}

function woo3dv_update_option ($option, $data) {
	update_option($option, $data);
}

add_action( 'plugins_loaded', 'woo3dv_load_textdomain' );
function woo3dv_load_textdomain() {
	load_plugin_textdomain( 'woo3dv', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
}



function woo3dv_enqueue_scripts_backend() {
	global $wp_scripts;
	$woo3dv_current_version = get_option('woo3dv_version');
	$settings = get_option( 'woo3dv_settings' );
	$upload_dir = wp_upload_dir();
//var_dump($upload_dir);exit;
	wp_enqueue_script( 'js/woo3dv-backend.js', plugin_dir_url( __FILE__ ).'js/woo3dv-backend.js', array( 'jquery' ), $woo3dv_current_version );

	if (isset($_GET['page']) && $_GET['page']=='woo3dv' || (isset($_GET['post']) && is_numeric($_GET['post']) && $_GET['action']=='edit' && woo3dv_is_woo3dv($_GET['post']))) {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-dialog' );
#		wp_enqueue_script( 'jquery.sumoselect.min.js',  plugin_dir_url( __FILE__ ).'ext/sumoselect/jquery.sumoselect.min.js', array( 'jquery' ), $woo3dv_current_version );
#		wp_enqueue_style( 'sumoselect.css', plugin_dir_url( __FILE__ ).'ext/sumoselect/sumoselect.css', array(), $woo3dv_current_version );
		wp_enqueue_script( 'tooltipster.js',  plugin_dir_url( __FILE__ ).'ext/tooltipster/js/jquery.tooltipster.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_style( 'tooltipster.css', plugin_dir_url( __FILE__ ).'ext/tooltipster/css/tooltipster.css', array(), $woo3dv_current_version );

		wp_enqueue_script( 'woo3dv-threejs',  plugin_dir_url( __FILE__ ).'ext/threejs/three.min.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-detector',  plugin_dir_url( __FILE__ ).'ext/threejs/js/Detector.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-mirror',  plugin_dir_url( __FILE__ ).'ext/threejs/js/Mirror.js', array( 'jquery' ), $woo3dv_current_version );
//		wp_enqueue_script( 'woo3dv-threejs-reflector',  plugin_dir_url( __FILE__ ).'ext/threejs/js/objects/Reflector.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-controls',  plugin_dir_url( __FILE__ ).'ext/threejs/js/controls/OrbitControls.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-canvas-renderer',  plugin_dir_url( __FILE__ ).'ext/threejs/js/renderers/CanvasRenderer.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-projector-renderer',  plugin_dir_url( __FILE__ ).'ext/threejs/js/renderers/Projector.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-stl-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/STLLoader.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-obj-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/OBJLoader.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-vrml-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/VRMLLoader.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-draco-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/DRACOLoader.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-gltf-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/GLTFLoader.js', array( 'jquery' ), $woo3dv_current_version );
#		wp_enqueue_script( 'woo3dv-threejs-loader-support',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/LoaderSupport.js', array( 'jquery' ), $woo3dv_current_version );
#		wp_enqueue_script( 'woo3dv-threejs-obj-loader2',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/OBJLoader2.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-mtl-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/MTLLoader.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-backend-model.js',  plugin_dir_url( __FILE__ ).'js/woo3dv-backend-model.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_style( 'jquery-ui.min.css', plugin_dir_url( __FILE__ ).'ext/jquery-ui/jquery-ui.min.css', array(), $woo3dv_current_version );
		wp_enqueue_style( 'woo3dv-backend.css', plugin_dir_url( __FILE__ ).'css/woo3dv-backend.css', array(), $woo3dv_current_version );

		wp_localize_script( 'woo3dv-backend-model.js', 'woo3dv',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
				'plugin_url' => plugin_dir_url( dirname( __FILE__ ) ),
				'upload_dir' => $upload_dir['baseurl'].'/woo3dv/',
				'shading' => $settings['shading'],
				'display_mode' => isset($settings['display_mode']) ? $settings['display_mode'] : '3d_model',
				'display_mode_mobile' => isset($settings['display_mode_mobile']) ? $settings['display_mode_mobile'] : '3d_model',
				'show_shadow' => $settings['show_shadow'],
				'shadow_softness' => $settings['shadow_softness'],
				'show_light_source1' => $settings['show_light_source1'],
				'show_light_source2' => $settings['show_light_source2'],
				'show_light_source3' => $settings['show_light_source3'],
				'show_light_source4' => $settings['show_light_source4'],
				'show_light_source5' => $settings['show_light_source5'],
				'show_light_source6' => $settings['show_light_source6'],
				'show_light_source7' => $settings['show_light_source7'],
				'show_light_source8' => $settings['show_light_source8'],
				'show_light_source9' => $settings['show_light_source9'],
				'show_ground' => $settings['show_ground'],
				'show_fog' => $settings['show_fog'],
				'ground_mirror' => $settings['ground_mirror'],
				'model_default_color' => str_replace( '#', '0x', $settings['model_default_color'] ),
				'model_default_shininess' => $settings['model_default_shininess'],
				'model_default_transparency' => $settings['model_default_transparency'],
				'background1' => str_replace( '#', '0x', $settings['background1']),
				'grid_color' => str_replace( '#', '0x', $settings['grid_color'] ),
				'fog_color' => str_replace( '#', '0x', $settings['fog_color'] ),
				'ground_color' => str_replace( '#', '0x', $settings['ground_color'] ),
				'auto_rotation' => $settings['auto_rotation'],
				'auto_rotation_speed' => $settings['auto_rotation_speed'],
				'auto_rotation_direction' => $settings['auto_rotation_direction'],
				'default_rotation_x' => $settings['default_rotation_x'],
				'default_rotation_y' => $settings['default_rotation_y'],
				'show_grid' => $settings['show_grid'],
				'file_chunk_size' => $settings['file_chunk_size'],
				'post_max_size' => ini_get('post_max_size'),
				'text_not_available' => esc_html__('Not available in your browser', 'woo3dv'),
				'text_model_not_found' => esc_html__('Model not found!', 'woo3dv'),
				'text_enable_preview' => esc_html__('Please enable Preview Model in the settings of the plugin', 'woo3dv'),
				'text_upload_model' => esc_html__('Please upload the model first', 'woo3dv'),
				'text_webm_chrome' => esc_html__('WEBM rendering works only in Chrome browser', 'woo3dv'),
				'text_switch_tabs' => esc_html__("Please don't switch to other tabs while rendering", 'woo3dv'),
				'text_post_max_size' => esc_html__('The amount of data we are going to submit is larger than post_max_size in php.ini ('.ini_get('post_max_size').'). Either increase post_max_size value or decrease resolution or quality of gif/video', 'woo3dv'),
				'text_repairing_model' => esc_html__( "Repairing..", 'woo3dv' ),
				'text_model_repaired' => esc_html__( "Repairing.. done!", 'woo3dv' ),
				'text_model_repair_report' => esc_html__( 'Error report:', 'woo3dv' ),
				'text_model_repair_failed' => esc_html__( "Repairing.. fail!", 'woo3dv' ),
				'text_model_no_repair_needed' => esc_html__( 'No errors found.', 'woo3dv' ),
				'text_model_repair_degenerate_facets' => esc_html__( 'Degenerate facets', 'woo3dv' ),
				'text_model_repair_edges_fixed' => esc_html__( 'Edges fixed', 'woo3dv' ),
				'text_model_repair_facets_removed' => esc_html__( 'Facets removed', 'woo3dv' ),
				'text_model_repair_facets_added' => esc_html__( 'Facets added', 'woo3dv' ),
				'text_model_repair_facets_reversed' => esc_html__( 'Facets reversed', 'woo3dv' ),
				'text_model_repair_backwards_edges' => esc_html__( 'Backwards edges', 'woo3dv' ),
//				'text_upload_model' => esc_html__( "Please upload the model first!", 'woo3dv' ),
				'text_repairing_mtl' => esc_html__( 'Can not repair textured models yet!', 'woo3dv' ),
				'text_repairing_only' => esc_html__( 'Can repair only STL and OBJ models', 'woo3dv' ),
				'text_repairing_alert' => esc_html__( "The model will be sent to our server for repair.\nRepairing some models with very faulty geometries may result in broken models.\nClick OK if you agree.", 'woo3dv' ),
				'text_reducing_model' => esc_html__( "Reducing..", 'woo3dv' ),
				'text_model_reduced' => esc_html__( "Reducing.. done!", 'woo3dv' ),
				'text_model_no_reduction_needed' => esc_html__( "No reduction needed", 'woo3dv' ),
				'text_enter_polygon_cap' => esc_html__( "% of triangles to reduce", 'woo3dv' ),
				'text_reducing_mtl' => esc_html__( 'Can not reduce textured models yet!', 'woo3dv' ),
				'text_reducing_only' => esc_html__( 'Can reduce only STL and OBJ models', 'woo3dv' ),
				'text_reducing_alert' => esc_html__( "The model will be sent to our server for polygon reduction.\n Click OK if you agree.", 'woo3dv' ),
				'upload_file_nonce' => wp_create_nonce( 'woo3dv-file-upload' )
			)
		);
	}
}

function woo3dv_enqueue_scripts_frontend() {
	global $post, $woocommerce;
	if ( function_exists('is_shop') && is_shop() ) return false;

	$available_variations = array();

	$product_model = get_post_meta( get_the_ID(), '_product_model', true );
	$display_mode = get_post_meta( get_the_ID(), '_display_mode', true );
	$display_mode_mobile = get_post_meta( get_the_ID(), '_display_mode_mobile', true );

	$woo3dv_current_version = get_option('woo3dv_version');

	$settings = get_option( 'woo3dv_settings' );

	$page_object = get_page( get_the_ID() );
	$queried_object = get_page(get_queried_object_id());


	if (woo3dv_is_woo3dv(get_the_ID())) {
		if ($display_mode=='3d_model' && $settings['load_everywhere']!='on') $condition = (strlen( $product_model ) );
		else $condition = true;
	}
	else if ($settings['load_everywhere']=='shortcode' && (has_shortcode($page_object->post_content, 'woo3dviewer') || has_shortcode($queried_object->post_content, 'woo3dviewer'))) {
		$condition = true;
	}
	else if ($settings['load_everywhere']=='on') {
		$condition = true;
	}
	else {
		$condition = false;
	}

	//do not load on 3DPrint products
	$post_object = get_post(get_the_ID());
	if (is_object($post_object) && $post_object->post_type=='product' && class_exists('WC_Product_Variable')) {
		$product = new WC_Product_Variable( get_the_ID() );
		if (function_exists('p3d_is_p3d') && p3d_is_p3d($product->get_id())) {
			$condition = false;
		}

	}

	//echo get_the_ID();
	if ( $condition ) {
		wp_enqueue_style( 'woo3dv-frontend.css', plugin_dir_url( __FILE__ ).'css/woo3dv-frontend.css', array(), $woo3dv_current_version );

		wp_enqueue_style( 'tooltipster.bundle.min.css', plugin_dir_url( __FILE__ ).'ext/tooltipster/css/tooltipster.bundle.min.css', array(), $woo3dv_current_version );
		wp_enqueue_style( 'tooltipster-sideTip-light.min.css ', plugin_dir_url( __FILE__ ).'ext/tooltipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-light.min.css', array(), $woo3dv_current_version );

		if( $woocommerce && version_compare( $woocommerce->version, '3.0', ">=" ) ) {
			wp_enqueue_style( 'prettyPhoto.css', plugin_dir_url( __FILE__ ).'ext/prettyPhoto/css/prettyPhoto.css', array(), $woo3dv_current_version );
		}

//		wp_enqueue_script( 'woo3dv-threejs-checker',  plugin_dir_url( __FILE__ ).'js/woo3dv-threejs-checker.js', array( 'jquery' ), $woo3dv_current_version );

		wp_enqueue_script( 'woo3dv-es6-promise',  plugin_dir_url( __FILE__ ).'ext/es6-promise/es6-promise.auto.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs',  plugin_dir_url( __FILE__ ).'ext/threejs/three.min.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-detector',  plugin_dir_url( __FILE__ ).'ext/threejs/js/Detector.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-mirror',  plugin_dir_url( __FILE__ ).'ext/threejs/js/Mirror.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-controls',  plugin_dir_url( __FILE__ ).'ext/threejs/js/controls/OrbitControls.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-canvas-renderer',  plugin_dir_url( __FILE__ ).'ext/threejs/js/renderers/CanvasRenderer.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-projector-renderer',  plugin_dir_url( __FILE__ ).'ext/threejs/js/renderers/Projector.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-stl-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/STLLoader.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-obj-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/OBJLoader.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-vrml-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/VRMLLoader.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-draco-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/DRACOLoader.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-gltf-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/GLTFLoader.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
#		wp_enqueue_script( 'woo3dv-threejs-loader-support',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/LoaderSupport.js', array( 'jquery' ), $woo3dv_current_version );
#		wp_enqueue_script( 'woo3dv-threejs-obj-loader2',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/OBJLoader2.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-mtl-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/MTLLoader.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threex',  plugin_dir_url( __FILE__ ).'ext/threex/THREEx.FullScreen.js', array( 'jquery', 'woo3dv-threejs' ), $woo3dv_current_version );

/*		wp_enqueue_script( 'woo3dv-threejs-collada-loader-animation',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/collada/Animation.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-collada-loader-animationhandler',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/collada/AnimationHandler.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-collada-loader-animation-keyframeanimation',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/collada/KeyFrameAnimation.js', array( 'jquery' ), $woo3dv_current_version );
		wp_enqueue_script( 'woo3dv-threejs-collada-loader',  plugin_dir_url( __FILE__ ).'ext/threejs/js/loaders/ColladaLoader.js', array( 'jquery' ), $woo3dv_current_version );*/



		if( $woocommerce && version_compare( $woocommerce->version, '3.0', ">=" ) ) {
			wp_enqueue_script( 'jquery.prettyPhoto.min.js',  plugin_dir_url( __FILE__ ).'ext/prettyPhoto/js/jquery.prettyPhoto.min.js', array( 'jquery' ), $woo3dv_current_version );
			wp_enqueue_script( 'jquery.prettyPhoto.init.min.js',  plugin_dir_url( __FILE__ ).'ext/prettyPhoto/js/jquery.prettyPhoto.init.min.js', array( 'jquery' ), $woo3dv_current_version );
		}
		wp_enqueue_script( 'woo3dv-frontend.js',  plugin_dir_url( __FILE__ ).'js/woo3dv-frontend.js', array( 'jquery' ), $woo3dv_current_version );

		$settings=get_option( 'woo3dv_settings' );

		$woo3dv_file_url = get_post_meta(get_the_ID(), 'woo3dv_file_url', true); 


		wp_localize_script( 'woo3dv-frontend.js', 'woo3dv',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
				'plugin_url' => plugin_dir_url( dirname( __FILE__ ) ),
				'shading' => $settings['shading'],
				'display_mode' => isset($settings['display_mode']) ? $settings['display_mode'] : '3d_model',
				'display_mode_mobile' => isset($settings['display_mode_mobile']) ? $settings['display_mode_mobile'] : '3d_model',
				'show_shadow' => $settings['show_shadow'],
				'shadow_softness' => $settings['shadow_softness'],
				'show_light_source1' => $settings['show_light_source1'],
				'show_light_source2' => $settings['show_light_source2'],
				'show_light_source3' => $settings['show_light_source3'],
				'show_light_source4' => $settings['show_light_source4'],
				'show_light_source5' => $settings['show_light_source5'],
				'show_light_source6' => $settings['show_light_source6'],
				'show_light_source7' => $settings['show_light_source7'],
				'show_light_source9' => $settings['show_light_source9'],
				'show_fog' => $settings['show_fog'],
				'show_controls' => $settings['show_controls'],
				'zoom_distance_min' => $settings['zoom_distance_min'],
				'zoom_distance_max' => $settings['zoom_distance_max'],
				'enable_zoom' => $settings['enable_zoom'],
				'enable_pan' => $settings['enable_pan'],
				'enable_manual_rotation' => $settings['enable_manual_rotation'],
				'show_ground' => $settings['show_ground'],
				'ground_mirror' => $settings['ground_mirror'],
				'model_default_color' => str_replace( '#', '0x', $settings['model_default_color'] ),
				'model_default_transparency' => $settings['model_default_transparency'],
				'model_default_shininess' => $settings['model_default_shininess'],
				'background1' => str_replace( '#', '0x', $settings['background1']),
				'grid_color' => str_replace( '#', '0x', $settings['grid_color'] ),
				'ground_color' => str_replace( '#', '0x', $settings['ground_color'] ),
				'fog_color' => str_replace( '#', '0x', $settings['fog_color'] ),
				'auto_rotation' => $settings['auto_rotation'],
				'auto_rotation_speed' => $settings['auto_rotation_speed'],
				'auto_rotation_direction' => $settings['auto_rotation_direction'],
				'default_rotation_x' => $settings['default_rotation_x'],
				'default_rotation_y' => $settings['default_rotation_y'],
				'show_grid' => $settings['show_grid'],
				'mobile_no_animation' => $settings['mobile_no_animation'],
				'override_cart_thumbnail' => $settings['override_cart_thumbnail'],
				'text_not_available' => esc_html__('Not available in your browser', 'woo3dv'),
				'text_multiple' => esc_html__('Upgrade to Woo3DViewer PRO to have multiple viewers on one page!', 'woo3dv')
			)
		);

		if  ( woo3dv_is_woo3dv ( get_the_ID() ) || $condition ) {
			$fix_css = "
				.product.has-default-attributes.has-children > .images {
					opacity:1 !important;
				}
				@media screen and (max-width: 400px) {
				   .product.has-default-attributes.has-children > .images { 
				    float: none;
				    margin-right:0;
				    width:auto;
				    border:0;
				    border-bottom:2px solid #000;    
				  }
				}
				@media screen and (max-width:800px){
					.product.has-default-attributes.has-children > .images  {
						width: auto !important;
					}

				}
			";
			wp_add_inline_style( 'woo3dv-frontend.css', $fix_css );
		}
	}


	
}
/*
function woo3dv_handle_upload() {
		check_ajax_referer( 'woo3dv-file-upload', 'nonce' );

		$filename = sanitize_file_name($_POST['file']).($_POST['file_type']=='gif' ? ".gif" : ".webm");

		$wp_upload_dir = wp_upload_dir();

		$file_path     = $wp_upload_dir['basedir'].'/woo3dv/' . $filename;
		$file_data     = woo3dv_decode_chunk( $_POST['file_data'] );

		if ( false === $file_data ) {
			wp_send_json_error();
		}

		file_put_contents( $file_path, $file_data, FILE_APPEND );

		wp_send_json_success();

}

function woo3dv_decode_chunk( $data ) {
	$data = explode( ';base64,', $data );
		if ( ! is_array( $data ) || ! isset( $data[1] ) ) {
		return false;
	}
		$data = base64_decode( $data[1] );
	if ( ! $data ) {
		return false;
	}
		return $data;
}
*/

add_action( 'admin_init', 'woo3dv_plugin_redirect' );
function woo3dv_plugin_redirect() {
	if ( get_option( 'woo3dv_do_activation_redirect', false ) ) {
		delete_option( 'woo3dv_do_activation_redirect' );
		if ( !isset( $_GET['activate-multi'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=woo3dv' ) );exit;
		}
	}
}

function woo3dv_extension($file) {
	$array=explode('.',$file);
	$ext=array_pop($array);
	return $ext;
} 




function woo3dv_deactivate() {
	global $wpdb;

	do_action( 'woo3dv_deactivate' );
}

function woo3dv_delete_woo3dv( $post_id ) {

}

function woo3dv_is_woo3dv( $post_id ) {
	$product_model = get_post_meta( $post_id, '_product_model', true );
	if (strlen($product_model)) return true;

	$product_image_png = get_post_meta( $post_id, '_product_image_png', true );
	if (strlen($product_image_png)) return true;

	$product_image_gif = get_post_meta( $post_id, '_product_image_gif', true );
	if (strlen($product_image_gif)) return true;

	$product_video_webm = get_post_meta( $post_id, '_product_video_webm', true );
	if (strlen($product_video_webm)) return true;


	return false;
}

function woo3dv_get_products() {
        global $wpdb;
	$products = array();

        $woo3dv_variations = $wpdb->get_results("SELECT distinct(pm.post_id) FROM {$wpdb->prefix}postmeta pm left join wp_posts p on p.ID=pm.post_id WHERE pm.meta_key in ('_product_model', '_product_image_png', '_product_image_gif', '_product_video_webm') and pm.meta_value!='' and p.post_type='product'", ARRAY_A);


	foreach ($woo3dv_variations as $variation) {
		$product_id = $variation['post_id'];
	        $parent_id = wp_get_post_parent_id($product_id); 
		if ($parent_id) $product_id = $parent_id;
		$products[$product_id]=get_the_title($product_id);
	}
	asort ($products);
	return $products;
}


add_action( 'save_post', 'woo3dv_save_post' );
function woo3dv_save_post( $post_id ) {

	if ( wp_is_post_revision( $post_id ) )
		return;
	if ( isset( $_POST['post_ID'] ) && $_POST['post_ID']==$post_id ) {
//		var_dump($_POST);exit;

	}


}


add_action('woocommerce_variation_options', 'woo3dv_variation_options', 10, 3);
function woo3dv_variation_options($loop, $variation_data, $variation) {

	$product_id = $variation->post_parent;
	$variation_id = $variation->ID;

	if (woo3dv_is_woo3dv($product_id)) {
	$settings = get_option( 'woo3dv_settings' );
?>
<script>
jQuery(document).ready(function() {
	jQuery( "table.woo3dv-settings .woo3dv-color-picker" ).wpColorPicker();
})
</script>

		<p><?php esc_html_e('Woo3DViewer settings', 'woo3dv');?>:</p>
		<br>Unlock in <a href="http://woo3dviewer.wp3dprinting.com/">PRO version</a>
		<br>
		<table class="woo3dv-settings">
			<tr>
				<td><?php esc_html_e( 'Custom Model', 'woo3dv' ); ?>:</td>
				<td class="file_url">
					<input type="text" class="input_text" disabled placeholder="<?php esc_attr_e( 'STL, OBJ or ZIP file', 'woo3dv' ); ?>" value="" />
				</td>
				<td class="file_url_choose" width="1%"><a title="<?php esc_attr_e( 'Set model', 'woo3dv' ); ?>" class="button" href="javascript:;" onclick="return false;"><?php esc_html_e( 'Set model', 'woo3dv' ); ?></a></td>
				<td width="1%"><a href="javascript:void(0);" onclick="return false;"class="button"><?php esc_html_e( 'Delete', 'woo3dv' ); ?></a></td>
			</tr>
			<tr>
				<td><?php _e( 'Custom Image', 'woo3dv' ); ?>:</td>
				<td class="file_url">
					<input type="text" class="input_text" disabled placeholder="<?php esc_attr_e( 'JPG, PNG, GIF file', 'woo3dv' ); ?>" value="<?php echo esc_attr( str_replace(array('http:', 'https:'), '', $product_image) ); ?>" />
					<input type="hidden" />
					<small>
					<?php 
					_e('Product featured image should be set to make variation images work.', 'woo3dv');
					?>
					</small>
				</td>
				<td class="file_url_choose" width="1%">
					<a title="<?php _e( 'Set Image', 'woo3dv' ); ?>" class="button" href="javascript:;" onclick="woo3dvSetImage(event, <?php echo $variation_id;?>);" id="woo3dv-set-image-<?php echo $variation_id;?>"><?php _e( 'Set image', 'woo3dv' ); ?></a>

				</td>
				<td width="1%"><a href="javascript:void(0);" onclick="jQuery('#woo3dv_variation_image_url_<?php echo $variation_id;?>').val('')" class="button"><?php _e( 'Delete', 'woo3dv' ); ?></a></td>
			</tr>

			<tr>
				<td>
					<?php esc_html_e( 'Rotation', 'woo3dv' ); ?>: 
				</td>
				<td>
					<table>
					<tr>
					<td>X<input disabled size="3" style="width:20px;" type="text" value="" />&deg;</td>
					<td>Y<input disabled size="3" style="width:20px;" type="text" value="" />&deg;</td>
					<td>Z<input disabled size="3" style="width:20px;" type="text" value="" />&deg;</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<?php esc_html_e( 'Color', 'woo3dv' ); ?>: 
				</td>
				<td>
					<input disabled type="text" class="woo3dv-color-picker" value="<?php echo esc_attr( $settings['model_default_color'] );?>" />
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Shininess', 'woo3dv');?>:</td>
				<td>
					<select disabled >
						<option value="default"><?php esc_html_e('Default', 'woo3dv');?></option>
						<option value="plastic"><?php esc_html_e('Plastic', 'woo3dv');?></option>
						<option value="wood"><?php esc_html_e('Wood', 'woo3dv');?></option>
						<option value="metal"><?php esc_html_e('Metal', 'woo3dv');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Transparency', 'woo3dv');?>:</td>
				<td>
					<select disabled>
						<option value="default"><?php esc_html_e('Default', 'woo3dv');?></option>
						<option value="opaque"><?php esc_html_e('Opaque', 'woo3dv');?></option>
						<option value="resin"><?php esc_html_e('Resin', 'woo3dv');?></option>
						<option value="glass"><?php esc_html_e('Glass', 'woo3dv');?></option>
					</select>
				</td>
			</tr>


		</table>
<?php
	}
}


function woo3dv_save_thumbnail( $data, $filename ) {
	$link = '';
	if ( !empty($data) ) {
//		$new_filename=$filename.'.png';
		$new_filename=$filename;
		$upload_dir = wp_upload_dir();
		$file_path=$upload_dir['basedir'].'/woo3dv/'.$new_filename;
		file_put_contents( $file_path, base64_decode( $data ) );
		$link = $upload_dir['baseurl'].'/woo3dv/'.$new_filename;
	}
	return $link;
}

//do_action( 'woocommerce_save_product_variation', $variation_id, $i );

add_action( 'woocommerce_save_product_variation', 'woo3dv_save_product_variation', 20, 2 );  //save changes button is pressed
function woo3dv_save_product_variation($post_id, $i) {
		//save variations
		if ( isset($_POST['woo3dv_variation_file_url']) && count($_POST['woo3dv_variation_file_url'])>0 ) {
			$options = array();
			foreach ($_POST['woo3dv_variation_file_url'] as $variation_id => $file_url) {
				$options['product_model']=sanitize_text_field($file_url);
				$options['product_image_png']='';
				$options['product_image_gif']='';
				$options['product_video_webm']='';
				$options['display_mode']='3d_model';
				$options['display_mode_mobile']='3d_model';
				$options['product_color']=sanitize_text_field($_POST['woo3dv_variation_product_color'][$variation_id]);
				$options['product_shininess']=sanitize_text_field($_POST['woo3dv_variation_product_shininess'][$variation_id]);
				$options['product_transparency']=sanitize_text_field($_POST['woo3dv_variation_product_transparency'][$variation_id]);
				$options['rotation_x']=(int)$_POST['woo3dv_variation_product_rotation_x'][$variation_id];
				$options['rotation_y']=(int)$_POST['woo3dv_variation_product_rotation_y'][$variation_id];
				$options['rotation_z']=(int)$_POST['woo3dv_variation_product_rotation_z'][$variation_id];
				$options['attachment_id']=(int)$_POST['woo3dv_variation_attachment_id'][$variation_id];
				$options['product_image_data']='';
				$options['product_gif_data']='';
				$options['product_webm_data']='';
				woo3dv_save_model_meta($variation_id, $options);
			}
		}
}

add_action( 'woocommerce_process_product_meta', 'woo3dv_save_model', 20, 2 ); //update button is pressed
function woo3dv_save_model($post_id) {

		$options = $_POST;

		//save main post
		woo3dv_save_model_meta($post_id, $options);

		//save variations
		if ( isset($_POST['woo3dv_variation_file_url']) && count($_POST['woo3dv_variation_file_url'])>0 ) {
			$options = array();
			foreach ($_POST['woo3dv_variation_file_url'] as $variation_id => $file_url) {
				$options['product_model']=sanitize_text_field($file_url);
				$options['product_image_png']='';
				$options['product_image_gif']='';
				$options['product_video_webm']='';
				$options['display_mode']='3d_model';
				$options['display_mode_mobile']='3d_model';
				$options['product_color']=sanitize_text_field($_POST['woo3dv_variation_product_color'][$variation_id]);
				$options['product_shininess']=sanitize_text_field($_POST['woo3dv_variation_product_shininess'][$variation_id]);
				$options['product_transparency']=sanitize_text_field($_POST['woo3dv_variation_product_transparency'][$variation_id]);
				$options['rotation_x']=(int)$_POST['woo3dv_variation_product_rotation_x'][$variation_id];
				$options['rotation_y']=(int)$_POST['woo3dv_variation_product_rotation_y'][$variation_id];
				$options['rotation_z']=(int)$_POST['woo3dv_variation_product_rotation_z'][$variation_id];
				$options['attachment_id']=(int)$_POST['woo3dv_variation_attachment_id'][$variation_id];
				$options['product_image_data']='';
				$options['product_gif_data']='';
				$options['product_webm_data']='';
				woo3dv_save_model_meta($variation_id, $options);
			}
		}

}


function woo3dv_save_model_meta($post_id, $options) {

		$product_model = wc_clean($options['product_model']);
		$product_model = str_ireplace('http:', '', $product_model);
		$product_model = str_ireplace('https:', '', $product_model);
		$product_image_png = wc_clean($options['product_image_png']);
		$product_image_gif = wc_clean($options['product_image_gif']);
		$product_video_webm = wc_clean($options['product_video_webm']);
		$display_mode = wc_clean($options['display_mode']);
		$display_mode_mobile = wc_clean($options['display_mode_mobile']);
		$product_color = wc_clean($options['product_color']);
		$product_shininess = wc_clean($options['product_shininess']);
		$product_transparency = wc_clean($options['product_transparency']);
		$product_attachment_id = wc_clean($options['product_attachment_id']);
		$rotation_x = (int)$options['rotation_x'];
		$rotation_y = (int)$options['rotation_y'];
		$rotation_z = (int)$options['rotation_z'];
		$product_remember_camera_position=(int)$options['product_remember_camera_position'];
		$product_camera_position_x = (float)$options['product_camera_position_x'];
		$product_camera_position_y = (float)$options['product_camera_position_y'];
		$product_camera_position_z = (float)$options['product_camera_position_z'];
		$product_camera_lookat_x = (float)$options['product_camera_lookat_x'];
		$product_camera_lookat_y = (float)$options['product_camera_lookat_y'];
		$product_camera_lookat_z = (float)$options['product_camera_lookat_z'];
		$product_offset_z = (float)$options['product_offset_z'];
#var_dump($options['product_offset_z']);exit;
		$product_controls_target_x = (float)$options['product_controls_target_x'];
		$product_controls_target_y = (float)$options['product_controls_target_y'];
		$product_controls_target_z = (float)$options['product_controls_target_z'];
		$product_model_extracted_path = sanitize_text_field($options['product_model_extracted_path']);
		$product_show_light_source1 = sanitize_text_field($options['product_show_light_source1']);
		$product_show_light_source2 = sanitize_text_field($options['product_show_light_source2']);
		$product_show_light_source3 = sanitize_text_field($options['product_show_light_source3']);
		$product_show_light_source4 = sanitize_text_field($options['product_show_light_source4']);
		$product_show_light_source5 = sanitize_text_field($options['product_show_light_source5']);
		$product_show_light_source6 = sanitize_text_field($options['product_show_light_source6']);
		$product_show_light_source7 = sanitize_text_field($options['product_show_light_source7']);
		$product_show_light_source8 = sanitize_text_field($options['product_show_light_source8']);
		$product_show_light_source9 = sanitize_text_field($options['product_show_light_source9']);

		$product_show_fog = sanitize_text_field($options['product_show_fog']);
		$product_show_grid = sanitize_text_field($options['product_show_grid']);
		$product_show_ground = sanitize_text_field($options['product_show_ground']);
		$product_show_shadow = sanitize_text_field($options['product_show_shadow']);
		$product_background1 = sanitize_text_field($options['product_background1']);
		$product_background_transparency = sanitize_text_field($options['product_background_transparency']);
		$product_ground_mirror = sanitize_text_field($options['product_show_mirror']);
		$product_fog_color = sanitize_text_field($options['product_fog_color']);
		$product_grid_color = sanitize_text_field($options['product_grid_color']);
		$product_ground_color = sanitize_text_field($options['product_ground_color']);
		$product_auto_rotation = sanitize_text_field($options['product_auto_rotation']);
		$product_view3d_button = sanitize_text_field($options['product_view3d_button']);
		$product_main_image_data = sanitize_text_field($options['product_main_image_data']);



		

		$product_image_data = sanitize_text_field($options['product_image_data']);
		$product_gif_data = sanitize_text_field($options['product_gif_data']);
		$product_webm_data = sanitize_text_field($options['product_webm_data']);
		$upload_dir = wp_upload_dir();
		$targetDir = dirname($upload_dir['basedir']).'/'.dirname(substr($product_model, strpos($product_model, 'uploads/'))).'/';
		$targetDir = str_replace('/uploads/sites/uploads/sites/', '/uploads/sites/', $targetDir);

		if ($product_model=='' && $product_image_png=='' && $product_image_gif=='' && $product_video_webm=='') {
			return;
		}

		if (strtolower(woo3dv_extension($product_model))=='zip') {

//woo3dv_process_zip
			$output = woo3dv_process_zip($product_model, $targetDir);
			$wp_filename = $output['model_file'];
			$material_file = $output['material_file'];

			update_post_meta( $post_id, 'woo3dv_original_file', $targetDir.$wp_filename.'.zip' );
			update_post_meta( $post_id, 'woo3dv_extracted_file', $targetDir.$wp_filename );
			update_post_meta( $post_id, 'woo3dv_original_file_url', $product_model );

				
		}
		else {

		}

		if ($product_model=='DELETE') { //removed
			$old_product_model = get_post_meta( $post_id, '_product_model', true );
			if ($old_product_model) {
				$old_product_path = woo3dv_get_path_by_url($old_product_model);
				unlink($old_product_path);
			}
			update_post_meta( $post_id, '_product_model', '' );
			update_post_meta( $post_id, '_product_model_extracted_path', '' );

		}
		else {
			update_post_meta( $post_id, '_product_model', $product_model );
			update_post_meta( $post_id, '_product_model_extracted_path', $product_model_extracted_path );
		}
//		delete_post_meta( $post_id, '_product_model_extracted_path');



		if (strlen($wp_filename)>0) {
			update_post_meta( $post_id, '_product_model', dirname($product_model).'/'.$wp_filename );
			update_post_meta( $post_id, '_product_model_extracted_path', base64_encode($targetDir.'/'.$wp_filename) );
		}

		if (strlen($material_file)>0) {
			update_post_meta( $post_id, '_product_mtl', dirname($product_model).'/'.$material_file );
		}
		else if (woo3dv_extension($product_model) != 'obj' || (strlen(woo3dv_extension($wp_filename))>0 && woo3dv_extension($wp_filename) != 'obj')) {
			delete_post_meta( $post_id, '_product_mtl' );
		}


		update_post_meta( $post_id, '_display_mode', $display_mode );
		update_post_meta( $post_id, '_display_mode_mobile', $display_mode_mobile );
		update_post_meta( $post_id, '_product_color', $product_color );
		update_post_meta( $post_id, '_product_shininess', $product_shininess );
		update_post_meta( $post_id, '_product_transparency', $product_transparency );
		update_post_meta( $post_id, '_product_attachment_id', $product_attachment_id );


		update_post_meta( $post_id, '_rotation_x', $rotation_x );
		update_post_meta( $post_id, '_rotation_y', $rotation_y );
		update_post_meta( $post_id, '_rotation_z', $rotation_z );

		update_post_meta( $post_id, '_product_remember_camera_position', $product_remember_camera_position );

		update_post_meta( $post_id, '_product_camera_position_x', $product_camera_position_x );
		update_post_meta( $post_id, '_product_camera_position_y', $product_camera_position_y );
		update_post_meta( $post_id, '_product_camera_position_z', $product_camera_position_z );

		update_post_meta( $post_id, '_product_camera_lookat_x', $product_camera_lookat_x );
		update_post_meta( $post_id, '_product_camera_lookat_y', $product_camera_lookat_y );
		update_post_meta( $post_id, '_product_camera_lookat_z', $product_camera_lookat_z );

		update_post_meta( $post_id, '_product_offset_z', $product_offset_z );

		update_post_meta( $post_id, '_product_controls_target_x', $product_controls_target_x );
		update_post_meta( $post_id, '_product_controls_target_y', $product_controls_target_y );
		update_post_meta( $post_id, '_product_controls_target_z', $product_controls_target_z );

		update_post_meta( $post_id, '_product_show_light_source1', $product_show_light_source1 );
		update_post_meta( $post_id, '_product_show_light_source2', $product_show_light_source2 );
		update_post_meta( $post_id, '_product_show_light_source3', $product_show_light_source3 );
		update_post_meta( $post_id, '_product_show_light_source4', $product_show_light_source4 );
		update_post_meta( $post_id, '_product_show_light_source5', $product_show_light_source5 );
		update_post_meta( $post_id, '_product_show_light_source6', $product_show_light_source6 );
		update_post_meta( $post_id, '_product_show_light_source7', $product_show_light_source7 );
		update_post_meta( $post_id, '_product_show_light_source8', $product_show_light_source8 );
		update_post_meta( $post_id, '_product_show_light_source9', $product_show_light_source9 );


		update_post_meta( $post_id, '_product_show_fog', $product_show_fog );
		update_post_meta( $post_id, '_product_show_grid', $product_show_grid );
		update_post_meta( $post_id, '_product_show_ground', $product_show_ground );
		update_post_meta( $post_id, '_product_show_shadow', $product_show_shadow );
		update_post_meta( $post_id, '_product_background1', $product_background1 );
		update_post_meta( $post_id, '_product_background_transparency', $product_background_transparency );
		update_post_meta( $post_id, '_product_ground_mirror', $product_ground_mirror );
		update_post_meta( $post_id, '_product_fog_color', $product_fog_color );
		update_post_meta( $post_id, '_product_grid_color', $product_grid_color );
		update_post_meta( $post_id, '_product_ground_color', $product_ground_color );
		update_post_meta( $post_id, '_product_auto_rotation', $product_auto_rotation );
		update_post_meta( $post_id, '_product_view3d_button', $product_view3d_button );




		if ($product_image_png=='DELETE') { //removed
			$old_product_image_png = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( get_the_ID(), '_product_image_png', true );

			if ($old_product_image_png) {
				$old_png_path = woo3dv_get_path_by_url('/woo3dv/'.$old_product_image_png);
				unlink($old_png_path);
				update_post_meta( $post_id, '_product_image_png', '' );
			}
		}

		if ($product_image_gif=='DELETE') { //removed
			$old_product_image_gif = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( get_the_ID(), '_product_image_gif', true );

			if ($old_product_image_gif) {
				$old_gif_path = woo3dv_get_path_by_url('/woo3dv/'.$old_product_image_gif);
				unlink($old_gif_path);
				update_post_meta( $post_id, '_product_image_gif', '' );
			}
		}

		if ($product_video_webm=='DELETE') { //removed
			$old_product_video_webm = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( get_the_ID(), '_product_video_webm', true );

			if ($old_product_video_webm) {
				$old_webm_path = woo3dv_get_path_by_url('/woo3dv/'.$old_product_video_webm);
				unlink($old_webm_path);
				update_post_meta( $post_id, '_product_video_webm', '' );
			}
		}



		
		if (strlen($product_main_image_data)>0) {
			$uniqid=uniqid();
			woo3dv_save_thumbnail(wc_clean($product_main_image_data), $uniqid.'.png' );
			update_post_meta( $post_id, '_product_image_png', $uniqid.'.png' );
			copy($upload_dir['basedir'].'/woo3dv/'.$uniqid.'.png', $upload_dir['path'].'/'.$uniqid.'.png');
			$filename = $upload_dir['path']. '/'.$uniqid.'.png' ;
			$filetype = wp_check_filetype( basename( $filename ), null );
			$attachment = array(
				'guid'           => $upload_dir['url'] . '/'.basename( $filename ), 
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			set_post_thumbnail( $post_id, $attach_id );
		}
		if (strlen($product_image_data)>0) {
			$uniqid=uniqid();
			woo3dv_save_thumbnail(wc_clean($product_image_data), $uniqid.'.png' );
			update_post_meta( $post_id, '_product_image_png', $uniqid.'.png' );

			if ($_POST['use_png_as_thumnbail']=='on') {
				copy($upload_dir['basedir'].'/woo3dv/'.$uniqid.'.png', $upload_dir['path'].'/'.$uniqid.'.png');
				$filename = $upload_dir['path']. '/'.$uniqid.'.png' ;
				$filetype = wp_check_filetype( basename( $filename ), null );

				$attachment = array(
					'guid'           => $upload_dir['url'] . '/'.basename( $filename ), 
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );
	
				set_post_thumbnail( $post_id, $attach_id );
			}


		}

		if (strlen($product_gif_data)>0) {
			$uniqid=sanitize_file_name($product_gif_data);
			//woo3dv_save_thumbnail(wc_clean($product_gif_data), $uniqid.'.gif' );
			update_post_meta( $post_id, '_product_image_gif', $uniqid.'.gif' );
		}

		if (strlen($product_webm_data)>0) {
			$uniqid=sanitize_file_name($product_webm_data);
			//woo3dv_save_thumbnail(wc_clean($product_webm_data), $uniqid.'.webm' );
			update_post_meta( $post_id, '_product_video_webm', $uniqid.'.webm' );
		}

//var_dump($_POST);
#var_dump($product_gif_data);exit;
#var_dump(get_post_meta( (int)$post_id));
#var_dump(get_post_meta( (int)$post_id, '_display_mode', true ));
#exit;
}

function woo3dv_process_zip($product_model, $targetDir) {

	if (class_exists('ZipArchive')) {
		$allowed_extensions_inside_archive=woo3dv_get_allowed_extensions_inside_archive();

		$time=time();

		$filePath = $targetDir.woo3dv_basename($product_model);
		if (!file_exists("$targetDir/tmp")) mkdir ("$targetDir/tmp");


		$zip = new ZipArchive;
		$res = $zip->open( $filePath );

		if ( $res === TRUE ) {

			for( $i = 0; $i < $zip->numFiles; $i++ ) {
				$file_to_extract =  sanitize_file_name(woo3dv_basename( $zip->getNameIndex($i) ) );

				$f2e_path_parts = pathinfo($file_to_extract);

				$f2e_extension = mb_strtolower($f2e_path_parts['extension']);

				if (!in_array($f2e_extension, $allowed_extensions_inside_archive)) continue;
				$entry_stats = $zip->statIndex($i);


				if (strstr( $entry_stats['name'], "__MACOS")) continue;

				if ( in_array($f2e_extension, woo3dv_get_accepted_models()) && !in_array($f2e_extension, woo3dv_get_support_extensions_inside_archive())) {
					$file_found = true;

					$file_to_extract =  woo3dv_basename( $file_to_extract );
					$wp_filename =  $time.'_'.$file_to_extract ;
					$extension = woo3dv_extension($file_to_extract);
				}
				$zip->extractTo( "$targetDir/tmp", array( $zip->getNameIndex($i) ) );
				$files = woo3dv_find_all_files("$targetDir/tmp");
				if (count($files)) {
					foreach ($files as $filename) {	

						rename($filename, $targetDir.$time."_".$file_to_extract);
						if (strtolower(woo3dv_extension($filename))=='mtl') { 
							$material_file = $time."_".$file_to_extract;
							woo3dv_process_mtl($targetDir.$time."_".$file_to_extract, $time);
						}
						if (strtolower(woo3dv_extension($filename))=='wrl') { 
							$material_file = $time."_".$file_to_extract;
							woo3dv_process_wrl($targetDir.$time."_".$file_to_extract, $time);
						}
						if (strtolower(woo3dv_extension($filename))=='gltf') { 
							$material_file = $time."_".$file_to_extract;
							woo3dv_process_gltf($targetDir.$time."_".$file_to_extract, $time);
						}
					}
				}

			}

			$zip->close();

			return array('model_file'=>$wp_filename, 'material_file'=>$material_file);


		}
		else {
					//die( '{"jsonrpc" : "2.0", "error" : {"code": 105, "message": "'.esc_html__( 'Could not extract the file.', 'woo3dv' ).'"}, "id" : "id"}' );
		}
	}
}

function woo3dv_get_path_by_url ($url) {
	$wp_upload_dir=wp_upload_dir();
	$offset = strrpos($url, 'uploads')+7;
	$path = substr($url, $offset);
	return $wp_upload_dir['basedir'].$path;
}

add_filter('upload_mimes', 'woo3dv_enable_extended_upload');
function woo3dv_enable_extended_upload ( $mime_types =array() ) {
	$mime_types['stl']  = 'application/octet-stream';
	$mime_types['wrl']  = 'model/vrml';
	$mime_types['glb']  = 'model/gltf-binary';
	$mime_types['gltf']  = 'model/gltf-json';
	$mime_types['obj']  = 'text/plain';
	$mime_types['zip']  = 'application/zip';

	return $mime_types;
}

add_filter( 'wp_check_filetype_and_ext', 'woo3dv_secondary_mime', 10, 5 );
function woo3dv_secondary_mime($result, $file, $filename, $mimes, $real_mime) {
	if ( $result['ext'] == false ) {
		$wp_filetype = wp_check_filetype( $filename, $mimes );
		$ext = $wp_filetype['ext'];
		if ( $ext == 'stl' ) {
			$result['ext'] = $ext;
			$result['type'] = 'text/plain';
		}
		if ( $ext == 'gltf' ) {
			$result['ext'] = $ext;
			$result['type'] = 'text/plain';
		}
		if ( $ext == 'glb' ) {
			$result['ext'] = $ext;
			$result['type'] = 'application/octet-stream';
		}
		if ( $ext == 'wrl' ) {
			$result['ext'] = $ext;
			$result['type'] = 'text/plain';
	        }
	}
	return $result;
}
 


add_action( 'admin_enqueue_scripts', 'woo3dv_add_color_picker' );
function woo3dv_add_color_picker( $hook ) {
	if ( is_admin() ) {
		$woo3dv_current_version = get_option('woo3dv_version');
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'custom-script-handle', plugins_url( 'js/woo3dv-backend.js', __FILE__ ), array( 'wp-color-picker' ), $woo3dv_current_version, true );
	}
}

function woo3dv_upload_file($name, $index) {
	$uploadedfile = array(
		'name'     => $_FILES[$name]['name'][$index],
		'type'     => $_FILES[$name]['type'][$index],
		'tmp_name' => $_FILES[$name]['tmp_name'][$index],
		'error'    => $_FILES[$name]['error'][$index],
		'size'     => $_FILES[$name]['size'][$index]
	);

	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
	#if (isset( $movefile['error'])) echo $movefile['error'];
	return $movefile;
}

function woo3dv_get_real_path($model_url) {
	$upload_dir = wp_upload_dir();
	$targetDir  = dirname($upload_dir['basedir']).'/'.dirname(substr($model_url, strpos($model_url, 'uploads/'))).'/';
	$targetDir  = str_replace('/uploads/sites/uploads/sites/', '/uploads/sites/', $targetDir);
	$targetFile = $targetDir.basename($model_url);
	return $targetFile;
}


add_shortcode( 'woo3dviewer' , 'woo3dv_shortcode' );
function woo3dv_shortcode ($atts) {
	global $post;
	#include_once(dirname(__FILE__).'/ext/mobile_detect/Mobile_Detect.php');
	#$detect = new Mobile_Detect;
	//var_dump();
	$settings = get_option('woo3dv_settings');	
	//woocommerce product
	if (isset($atts['product_id']) && is_numeric($atts['product_id']) ) {
		$product_id=(int)$atts['product_id'];
		$product = new WC_Product( $product_id );
#var_dump($product);
		if (!isset($atts['compatibility_mode']) || (isset($atts['compatibility_mode']) && $atts['compatibility_mode']=='false')) {
			return do_shortcode('[product_page id="'.$product_id.'"]');
		}
		else if (isset($atts['compatibility_mode']) && $atts['compatibility_mode']=='true') {
			ob_start();
			include_once('woo3dv-product-single-compat.php');
			$content = ob_get_contents();
			ob_end_clean();

//			return $content;
			return apply_filters('p3d_single_compat_template', $content, $atts );
		}
	}

	$upload_dir = wp_upload_dir();

	$model_url = $atts['model_url'];
	$material_url = $atts['material_url'];
	if (isset($atts['thumbnail_url'])) $thumbnail_url = $atts['thumbnail_url'];
	else $thumbnail_url = '';
	$upload_url = dirname($model_url).'/';
	$image_gif = $video_webm = $atts['rendered_file_url'];
	$mtl = ''; //todo $atts['mtl'];
//	$mtl = $atts['mtl'];
	$display_mode = $atts['display_mode'];
	$display_mode_mobile = $atts['display_mode_mobile'];

	$canvas_width = $atts['canvas_width'];
	$canvas_height = $atts['canvas_height'];

	if (isset($atts['canvas_border'])) {
		$canvas_border = ($atts['canvas_border'] == 'true' ? 'on' : 'off');
	}
	else {
		$canvas_border = $settings['canvas_border'];
	}

	if ($canvas_border=='on') $border_class = 'woo3dv-canvas-border';
	else $border_class = 'woo3dv-canvas-borderless';


	$color = str_replace('#', '0x', $atts['model_color']);
	$transparency = $atts['model_transparency'];
	$shininess = $atts['model_shininess'];

	$rotation_x = $atts['rotation_x'];
	$rotation_y = $atts['rotation_y'];
	$rotation_z = $atts['rotation_z'];

	$offset_z = $atts['offset_z'];
	if (!$offset_z) $offset_z = '0';

	$show_light_source1 = ($atts['light_source1']=='true' ? 'on' : 'off');
	$show_light_source2 = ($atts['light_source2']=='true' ? 'on' : 'off');
	$show_light_source3 = ($atts['light_source3']=='true' ? 'on' : 'off');
	$show_light_source4 = ($atts['light_source4']=='true' ? 'on' : 'off');
	$show_light_source5 = ($atts['light_source5']=='true' ? 'on' : 'off');
	$show_light_source6 = ($atts['light_source6']=='true' ? 'on' : 'off');
	$show_light_source7 = ($atts['light_source7']=='true' ? 'on' : 'off');
	$show_light_source8 = ($atts['light_source8']=='true' ? 'on' : 'off');
	$show_light_source9 = ($atts['light_source9']=='true' ? 'on' : 'off');

	$camera_position_x = (isset($atts['camera_position_x']) ? $atts['camera_position_x'] : 0);
	$camera_position_y = (isset($atts['camera_position_y']) ? $atts['camera_position_y'] : 0);
	$camera_position_z = (isset($atts['camera_position_z']) ? $atts['camera_position_z'] : 0);

	$camera_lookat_x = (isset($atts['camera_lookat_x']) ? $atts['camera_lookat_x'] : 0);
	$camera_lookat_y = (isset($atts['camera_lookat_y']) ? $atts['camera_lookat_y'] : 0);
	$camera_lookat_z = (isset($atts['camera_lookat_z']) ? $atts['camera_lookat_z'] : 0);

	$controls_target_x = (isset($atts['controls_target_x']) ? $atts['controls_target_x'] : 0);
	$controls_target_y = (isset($atts['controls_target_y']) ? $atts['controls_target_y'] : 0);
	$controls_target_z = (isset($atts['controls_target_z']) ? $atts['controls_target_z'] : 0);


//	$show_fog = ($atts['show_fog']=='true' ? 'on' : '');
	$show_fog = $settings['show_fog'];
	$show_controls = $settings['show_controls'];

	$show_grid = ($atts['show_grid']=='true' ? 'on' : 'off');
	$show_ground = ($atts['show_ground']=='true' ? 'on' : 'off');
	$show_shadow = ($atts['show_shadow']=='true' ? 'on' : 'off');
	$background1 = str_replace('#', '0x', $atts['background_color']);
	if (isset($atts['background_transparency'])) {
		$background_transparency = ($atts['background_transparency']=='true' ? 'on' : 'off');
	}
	else {
		$background_transparency = 'off';
	}
	$ground_mirror = ($atts['show_mirror']=='true' ? 'on' : 'off');;
//	$fog_color = str_replace('#', '0x', $atts['fog_color']);
	$fog_color = str_replace('#', '0x', $settings['fog_color']);
	$grid_color = str_replace('#', '0x', $atts['grid_color']);
	$ground_color = str_replace('#', '0x', $atts['ground_color']);
	$auto_rotation = ($atts['auto_rotation']=='true' ? 'on' : 'off');
	if (isset($atts['show_controls'])) $show_controls = ($atts['show_controls']=='true' ? 'on' : 'off');

#	if ($detect->isMobile()) $display_mode = $display_mode_mobile;
//str_replace( '#', '0x'
//style="max-width:500px;max-height:500px;"

	ob_start(); 
	$woo3dv_viewer_height_style="max-height";
	$woo3dv_viewer_width_style="max-width";

?>
	<div id="woo3dv-viewer" style="<?php echo esc_attr($woo3dv_viewer_width_style);?>:<?php echo esc_attr($canvas_width);?>px;<?php echo esc_attr($woo3dv_viewer_height_style);?>:<?php echo esc_attr($canvas_height);?>px;">
		<div class="woo3dv-canvas-wrapper">
			<canvas id="woo3dv-cv" class="woo3dv-canvas <?php echo esc_attr($border_class);?>" width="<?php echo esc_attr($canvas_width);?>" height="<?php echo esc_attr($canvas_height);?>"></canvas>
			<div id="woo3dv-file-loading">
				<img alt="Loading file" src="<?php echo esc_url($settings['ajax_loader']); ?>">
			</div>
		</div>

	<?php if ($show_controls=='on' && ($display_mode=='3d_model' || $display_mode=='')) { ?>
	<div id="woo3dv-model-controls">
		<ul id="woo3dv-model-controls-list">
		<li><a href="javascript:void(0)" onclick="woo3dvToggleFullScreen();"><img alt="<?php esc_attr_e('Fullscreen', 'woo3dv');?>" title="<?php esc_html_e('Fullscreen', 'woo3dv');?>" id="woo3dv-controls-fullscreen" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/fullscreen.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvToggleWireframe();"><img alt="<?php esc_attr_e('Wireframe', 'woo3dv');?>" title="<?php esc_html_e('Wireframe', 'woo3dv');?>" id="woo3dv-controls-wireframe" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/wireframe.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvZoomIn();"><img alt="<?php esc_attr_e('Zoom In', 'woo3dv');?>" title="<?php esc_html_e('Zoom In', 'woo3dv');?>" id="woo3dv-controls-zoomin" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/zoom_in.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvZoomOut();"><img alt="<?php esc_attr_e('Zoom Out', 'woo3dv');?>" title="<?php esc_html_e('Zoom Out', 'woo3dv');?>" id="woo3dv-controls-zoomout" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/zoom_out.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvToggleRotation();"><img alt="<?php esc_attr_e('Rotation', 'woo3dv');?>" title="<?php esc_html_e('Rotation', 'woo3dv');?>" id="woo3dv-controls-rotation" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/rotation.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvScreenshot();" id="woo3dv-screenshot"><img alt="<?php esc_attr_e('Screenshot', 'woo3dv');?>" title="<?php esc_html_e('Screenshot', 'woo3dv');?>" id="woo3dv-controls-screenshot" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/screenshot.png')); ?>"></a>
		<li><a href="#woo3dv-popup1" class="woo3dv-button"><img alt="<?php esc_attr_e('Help', 'woo3dv');?>" title="<?php esc_html_e('Help', 'woo3dv');?>" id="woo3dv-controls-help" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/help.png')); ?>"></a>
		</ul>
	</div>
	<div id="woo3dv-popup1" class="woo3dv-overlay">
		<div class="woo3dv-popup">
			<h2><?php esc_html_e('Controls', 'woo3dv');?></h2>
			<a class="woo3dv-close" href="#">&times;</a>
			<div class="woo3dv-content">
				<ul id="woo3dv-controls-help">
					<li><?php esc_html_e('Rotate with the left mouse button. ', 'woo3dv');?>
					<li><?php esc_html_e('Zoom with the scroll button. ', 'woo3dv');?>
					<li><?php esc_html_e('Adjust camera position with the right mouse button.', 'woo3dv');?>
					<li><?php esc_html_e('Double-click to enter the fullscreen mode.', 'woo3dv');?>
					<li><?php esc_html_e('On mobile devices swipe to rotate.', 'woo3dv');?>
					<li><?php esc_html_e('On mobile devices pinch two fingers together or apart to adjust zoom.', 'woo3dv');?>
					<li><?php esc_html_e('On mobile devices 3 finger horizontal swipe performs panning.', 'woo3dv');?>
					<li><?php esc_html_e('On mobile devices 3 finger horizontal swipe performs panning.', 'woo3dv');?>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
	</div>

	<input type="hidden" id="woo3dv_model_url" value="<?php if ($display_mode=='3d_model' || $display_mode=='') echo esc_attr($model_url);?>">
	<input type="hidden" id="woo3dv_model_mtl" value="<?php echo esc_attr(basename($material_url));?>">
	<input type="hidden" id="woo3dv_model_gif" value="<?php echo esc_attr($image_gif);?>">


	<input type="hidden" id="woo3dv_upload_url" value="<?php echo esc_attr( $upload_url ); ?>" />
	<input type="hidden" id="woo3dv_model_color" value="<?php echo esc_attr($color);?>">
	<input type="hidden" id="woo3dv_model_shininess" value="<?php echo esc_attr($shininess);?>">
	<input type="hidden" id="woo3dv_model_transparency" value="<?php echo esc_attr($transparency);?>">

	<input type="hidden" id="woo3dv_model_rotation_x" value="<?php echo esc_attr($rotation_x);?>">
	<input type="hidden" id="woo3dv_model_rotation_y" value="<?php echo esc_attr($rotation_y);?>">
	<input type="hidden" id="woo3dv_model_rotation_z" value="<?php echo esc_attr($rotation_z);?>">

	<input type="hidden" id="woo3dv_show_light_source1" value="<?php echo esc_attr($show_light_source1);?>">
	<input type="hidden" id="woo3dv_show_light_source2" value="<?php echo esc_attr($show_light_source2);?>">
	<input type="hidden" id="woo3dv_show_light_source3" value="<?php echo esc_attr($show_light_source3);?>">
	<input type="hidden" id="woo3dv_show_light_source4" value="<?php echo esc_attr($show_light_source4);?>">
	<input type="hidden" id="woo3dv_show_light_source5" value="<?php echo esc_attr($show_light_source5);?>">
	<input type="hidden" id="woo3dv_show_light_source6" value="<?php echo esc_attr($show_light_source6);?>">
	<input type="hidden" id="woo3dv_show_light_source7" value="<?php echo esc_attr($show_light_source7);?>">
	<input type="hidden" id="woo3dv_show_light_source8" value="<?php echo esc_attr($show_light_source8);?>">
	<input type="hidden" id="woo3dv_show_light_source9" value="<?php echo esc_attr($show_light_source9);?>">



	<input type="hidden" id="woo3dv_camera_position_x" value="<?php echo esc_attr( $camera_position_x ); ?>" />
	<input type="hidden" id="woo3dv_camera_position_y"  value="<?php echo esc_attr( $camera_position_y ); ?>" />
	<input type="hidden" id="woo3dv_camera_position_z"  value="<?php echo esc_attr( $camera_position_z ); ?>" />
	<input type="hidden" id="woo3dv_camera_lookat_x" value="<?php echo esc_attr( $camera_lookat_x ); ?>" />
	<input type="hidden" id="woo3dv_camera_lookat_y"  value="<?php echo esc_attr( $camera_lookat_y ); ?>" />
	<input type="hidden" id="woo3dv_camera_lookat_z"  value="<?php echo esc_attr( $camera_lookat_z ); ?>" />
	<input type="hidden" id="woo3dv_controls_target_x" value="<?php echo esc_attr( $controls_target_x ); ?>" />
	<input type="hidden" id="woo3dv_controls_target_y" value="<?php echo esc_attr( $controls_target_y ); ?>" />
	<input type="hidden" id="woo3dv_controls_target_z" value="<?php echo esc_attr( $controls_target_z ); ?>" />
	<input type="hidden" id="woo3dv_offset_z"  value="<?php echo esc_attr( $offset_z ); ?>" />


	<input type="hidden" id="woo3dv_show_fog"  value="<?php echo esc_attr( $show_fog ); ?>" />
	<input type="hidden" id="woo3dv_show_grid"  value="<?php echo esc_attr( $show_grid ); ?>" />
	<input type="hidden" id="woo3dv_show_ground"  value="<?php echo esc_attr( $show_ground ); ?>" />
	<input type="hidden" id="woo3dv_show_shadow"  value="<?php echo esc_attr( $show_shadow ); ?>" />
	<input type="hidden" id="woo3dv_background1"  value="<?php echo esc_attr( $background1 ); ?>" />
	<input type="hidden" id="woo3dv_background_transparency"  value="<?php echo esc_attr( $background_transparency ); ?>" />
	<input type="hidden" id="woo3dv_ground_mirror"  value="<?php echo esc_attr( $ground_mirror ); ?>" />
	<input type="hidden" id="woo3dv_fog_color"  value="<?php echo esc_attr( $fog_color ); ?>" />
	<input type="hidden" id="woo3dv_grid_color"  value="<?php echo esc_attr( $grid_color ); ?>" />
	<input type="hidden" id="woo3dv_ground_color"  value="<?php echo esc_attr( $ground_color ); ?>" />
	<input type="hidden" id="woo3dv_auto_rotation"  value="<?php echo esc_attr( $auto_rotation ); ?>" />
<?php
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_filter( 'woocommerce_single_product_image_html', 'woo3dv_woocommerce_single_product_image_html', 10, 2 );
function woo3dv_woocommerce_single_product_image_html ($image_url, $post_id) {
	global $post, $woocommerce, $product;
	if (is_object($product) && method_exists($product, 'get_id')) {
		$post_id = $product->get_id();
	}
	$settings = get_option('woo3dv_settings');
#	include_once(dirname(__FILE__).'/ext/mobile_detect/Mobile_Detect.php');
#	$detect = new Mobile_Detect;

	$product_canvas_border = get_post_meta( $post_id, '_product_canvas_border', true );

	if ($product_canvas_border) {
		$canvas_border = $product_canvas_border;
	}
	else {
		$canvas_border = $settings['canvas_border'];
	}

	if ($canvas_border=='on') $border_class = 'woo3dv-canvas-border';
	else $border_class = 'woo3dv-canvas-borderless';

	$product_image_gif=$product_image_png=$product_image_png='';
	$master_models = $master_mtls =array();

	if (!woo3dv_is_woo3dv($post_id)) return $image_url;
	$upload_dir = wp_upload_dir();
	$product_model = get_post_meta( $post_id, '_product_model', true );
	$display_mode = get_post_meta( $post_id, '_display_mode', true );
	$display_mode_mobile = get_post_meta( $post_id, '_display_mode_mobile', true );
	if (!$display_mode_mobile) $display_mode_mobile = $display_mode;
	$product_mtl = get_post_meta( $post_id, '_product_mtl', true );

	$product_color = get_post_meta( $post_id, '_product_color', true );
	if (!$product_color) $product_color = $settings['model_default_color'];
	$product_transparency = get_post_meta( $post_id, '_product_transparency', true );
	if (!$product_transparency) $product_transparency = $settings['model_default_transparency'];
	$product_shininess = get_post_meta( $post_id, '_product_shininess', true );
	if (!$product_shininess) $product_shininess = $settings['model_default_shininess'];


	$rotation_x = get_post_meta( $post_id, '_rotation_x', true );
	$rotation_y = get_post_meta( $post_id, '_rotation_y', true );
	$rotation_z = get_post_meta( $post_id, '_rotation_z', true );

	$product_offset_z = get_post_meta( $post_id, '_product_offset_z', true );
	if (!$product_offset_z) $product_offset_z = '0';

	$product_show_light_source1 = get_post_meta( $post_id, '_product_show_light_source1', true );
	$product_show_light_source2 = get_post_meta( $post_id, '_product_show_light_source2', true );
	$product_show_light_source3 = get_post_meta( $post_id, '_product_show_light_source3', true );
	$product_show_light_source4 = get_post_meta( $post_id, '_product_show_light_source4', true );
	$product_show_light_source5 = get_post_meta( $post_id, '_product_show_light_source5', true );
	$product_show_light_source6 = get_post_meta( $post_id, '_product_show_light_source6', true );
	$product_show_light_source7 = get_post_meta( $post_id, '_product_show_light_source7', true );
	$product_show_light_source8 = get_post_meta( $post_id, '_product_show_light_source8', true );
	$product_show_light_source9 = get_post_meta( $post_id, '_product_show_light_source9', true );

	if (!$product_show_light_source1) $product_show_light_source1 = $settings['show_light_source1'];
	if (!$product_show_light_source2) $product_show_light_source2 = $settings['show_light_source2'];
	if (!$product_show_light_source3) $product_show_light_source3 = $settings['show_light_source3'];
	if (!$product_show_light_source4) $product_show_light_source4 = $settings['show_light_source4'];
	if (!$product_show_light_source5) $product_show_light_source5 = $settings['show_light_source5'];
	if (!$product_show_light_source6) $product_show_light_source6 = $settings['show_light_source6'];
	if (!$product_show_light_source7) $product_show_light_source7 = $settings['show_light_source7'];
	if (!$product_show_light_source8) $product_show_light_source8 = $settings['show_light_source8'];
	if (!$product_show_light_source9) $product_show_light_source9 = $settings['show_light_source9'];


	$product_show_fog = get_post_meta( $post_id, '_product_show_fog', true );
	$product_show_grid = get_post_meta( $post_id, '_product_show_grid', true );
	$product_show_ground = get_post_meta( $post_id, '_product_show_ground', true );
	$product_show_shadow = get_post_meta( $post_id, '_product_show_shadow', true );
	$product_background1 = get_post_meta( $post_id, '_product_background1', true );
	$product_background_transparency = get_post_meta( $post_id, '_product_background_transparency', true );
	$product_ground_mirror = get_post_meta( $post_id, '_product_ground_mirror', true );
	$product_fog_color = get_post_meta( $post_id, '_product_fog_color', true );
	$product_grid_color = get_post_meta( $post_id, '_product_grid_color', true );
	$product_ground_color = get_post_meta( $post_id, '_product_ground_color', true );
	$product_auto_rotation = get_post_meta( $post_id, '_product_auto_rotation', true );
	$product_view3d_button = get_post_meta( $post_id, '_product_view3d_button', true );

	$product_show_controls = $settings['show_controls'];
	if (!$product_show_fog) $product_show_fog = $settings['show_fog'];
	if (!$product_show_grid) $product_show_grid = $settings['show_grid'];
	if (!$product_show_ground) $product_show_ground = $settings['show_ground'];
	if (!$product_show_shadow) $product_show_shadow = $settings['show_shadow'];
	if (!$product_background1) $product_background1 = $settings['background1'];
	if (!$product_background_transparency) $product_background_transparency = 'off';
	if (!$product_fog_color) $product_fog_color = $settings['fog_color'];
	if (!$product_grid_color) $product_grid_color = $settings['grid_color'];
	if (!$product_ground_color) $product_ground_color = $settings['ground_color'];
	if (!$product_ground_mirror) $product_ground_mirror = $settings['ground_mirror'];
	if (!$product_auto_rotation) $product_auto_rotation = $settings['auto_rotation'];



	$product_remember_camera_position = get_post_meta( $post_id, '_product_remember_camera_position', true );
	if ($product_remember_camera_position=='1') {
		$product_camera_position_x = get_post_meta( $post_id, '_product_camera_position_x', true );
		$product_camera_position_y = get_post_meta( $post_id, '_product_camera_position_y', true );
		$product_camera_position_z = get_post_meta( $post_id, '_product_camera_position_z', true );
		$product_camera_lookat_x = get_post_meta( $post_id, '_product_camera_lookat_x', true );
		$product_camera_lookat_y = get_post_meta( $post_id, '_product_camera_lookat_y', true );
		$product_camera_lookat_z = get_post_meta( $post_id, '_product_camera_lookat_z', true );
		$product_controls_target_x = get_post_meta( $post_id, '_product_controls_target_x', true );
		$product_controls_target_y = get_post_meta( $post_id, '_product_controls_target_y', true );
		$product_controls_target_z = get_post_meta( $post_id, '_product_controls_target_z', true );
	}
	else {
		$product_camera_position_x = $product_camera_position_y = $product_camera_position_z = $product_camera_lookat_x = $product_camera_lookat_y = $product_camera_lookat_z = $product_controls_target_x = $product_controls_target_y = $product_controls_target_z = 0;
	}


	if (get_post_meta( $post_id, '_product_image_png', true )) $product_image_png = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( $post_id, '_product_image_png', true );
	if (get_post_meta( $post_id, '_product_image_gif', true )) $product_image_gif = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( $post_id, '_product_image_gif', true );
	if (get_post_meta( $post_id, '_product_video_webm', true )) $product_video_webm = $upload_dir['baseurl'].'/woo3dv/'.get_post_meta( $post_id, '_product_video_webm', true );


	$targetFile = woo3dv_get_real_path($product_model);

	if (is_file($targetFile)) {
		$targetFileMD5 = md5_file($targetFile);
		$master_models[$targetFileMD5] = $product_model;
	}

	$targetMTL = woo3dv_get_real_path($product_mtl);

	if (is_file($targetMTL)) {
		$targetMTLMD5 = md5_file($targetMTL);
		$master_mtls[$targetMTLMD5] = $product_mtl;
	}

	$product = wc_get_product($post_id);



	$targetFile = woo3dv_get_real_path($product_model);
	if (is_file($targetFile)) {
		$targetFileMD5 = md5_file($targetFile);
		if (isset($master_models[$targetFileMD5])) {
			$product_model = $master_models[$targetFileMD5];
		}
	}

	$targetMTL = woo3dv_get_real_path($product_mtl);

	if (is_file($targetMTL)) {
		$targetMTLMD5 = md5_file($targetMTL);
		if (isset($master_mtls[$targetMTLMD5])) {
			$product_mtl = $master_mtls[$targetMTLMD5];
		}
	}

	$upload_url = dirname($product_model).'/';


	$settings=get_option('woo3dv_settings');

#	if ($detect->isMobile()) $display_mode = $display_mode_mobile;
	ob_start(); 
	if ($product_view3d_button=='on') {
		$post_thumbnail_id = $product->get_image_id();
		$image_url = wp_get_attachment_image_url( $post_thumbnail_id, 'full' );
		echo '<div class="woo3dv-view3d-button-wrapper">';		
		echo '<a class="woo3dv-view3d-button" href="javascript:void(0);" onclick="woo3dvInit3D()"><img src="'.esc_attr($settings['view3d_button_image']).'"></a>';
		echo '<a class="zoom woo3dv-main-image" data-rel="prettyPhoto[product-gallery]" href="'.esc_attr($image_url).'"> <img src="'.esc_attr($image_url).'"></a>';
		echo '</div>';
	}
?>


	<div id="woo3dv-viewer" style="<?php if ($product_view3d_button=='on') echo 'display:none;';?>">
		<div class="woo3dv-canvas-wrapper">
			<canvas id="woo3dv-cv" class="woo3dv-canvas <?php echo esc_attr($border_class);?>" width="<?php echo esc_attr($settings['canvas_width']);?>" height="<?php echo esc_attr($settings['canvas_height']);?>"></canvas>
			<div id="woo3dv-file-loading">
				<img alt="Loading file" src="<?php echo esc_url($settings['ajax_loader']); ?>">
			</div>
		</div>


	<?php if ($product_show_controls=='on' && ($display_mode=='3d_model' || $display_mode=='')) { ?>
	<div id="woo3dv-model-controls">
		<ul id="woo3dv-model-controls-list">
		<li><a href="javascript:void(0)" onclick="woo3dvToggleFullScreen();"><img alt="<?php esc_attr_e('Fullscreen', 'woo3dv');?>" title="<?php esc_attr_e('Fullscreen', 'woo3dv');?>" id="woo3dv-controls-fullscreen" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/fullscreen.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvToggleWireframe();"><img alt="<?php esc_attr_e('Wireframe', 'woo3dv');?>" title="<?php esc_attr_e('Wireframe', 'woo3dv');?>" id="woo3dv-controls-wireframe" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/wireframe.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvZoomIn();"><img alt="<?php esc_attr_e('Zoom In', 'woo3dv');?>" title="<?php esc_attr_e('Zoom In', 'woo3dv');?>" id="woo3dv-controls-zoomin" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/zoom_in.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvZoomOut();"><img alt="<?php esc_attr_e('Zoom Out', 'woo3dv');?>" title="<?php esc_attr_e('Zoom Out', 'woo3dv');?>" id="woo3dv-controls-zoomout" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/zoom_out.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvToggleRotation();"><img alt="<?php esc_attr_e('Rotation', 'woo3dv');?>" title="<?php esc_attr_e('Rotation', 'woo3dv');?>" id="woo3dv-controls-rotation" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/rotation.png')); ?>"></a>
		<li><a href="javascript:void(0)" onclick="woo3dvScreenshot();" id="woo3dv-screenshot"><img alt="<?php esc_attr_e('Screenshot', 'woo3dv');?>" title="<?php esc_attr_e('Screenshot', 'woo3dv');?>" id="woo3dv-controls-screenshot" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/screenshot.png')); ?>"></a>
		<li><a href="#woo3dv-popup1" class="woo3dv-button"><img alt="<?php esc_attr_e('Help', 'woo3dv');?>" title="<?php esc_attr_e('Help', 'woo3dv');?>" id="woo3dv-controls-help" src="<?php echo esc_url(plugins_url( 'woo-3d-viewer/images/help.png')); ?>"></a>
		</ul>
	</div>
	<div id="woo3dv-popup1" class="woo3dv-overlay">
		<div class="woo3dv-popup">
			<h2><?php esc_html_e('Controls', 'woo3dv');?></h2>
			<a class="woo3dv-close" href="#">&times;</a>
			<div class="woo3dv-content">
				<ul id="woo3dv-controls-help">
					<li><?php esc_html_e('Rotate with the left mouse button. ', 'woo3dv');?>
					<li><?php esc_html_e('Zoom with the scroll button. ', 'woo3dv');?>
					<li><?php esc_html_e('Adjust camera position with the right mouse button.', 'woo3dv');?>
					<li><?php esc_html_e('Double-click to enter the fullscreen mode.', 'woo3dv');?>
					<li><?php esc_html_e('On mobile devices swipe to rotate.', 'woo3dv');?>
					<li><?php esc_html_e('On mobile devices pinch two fingers together or apart to adjust zoom.', 'woo3dv');?>
					<li><?php esc_html_e('On mobile devices 3 finger horizontal swipe performs panning.', 'woo3dv');?>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
	</div>

	<input type="hidden" id="woo3dv_model_url" value="<?php if ($display_mode=='3d_model' || $display_mode=='') echo esc_attr($product_model);?>">
	<input type="hidden" id="woo3dv_model_mtl" value="<?php echo esc_attr(basename($product_mtl));?>">
	<input type="hidden" id="woo3dv_model_gif" value="<?php echo esc_attr($product_image_gif);?>">
	<input type="hidden" id="woo3dv_model_png" value="<?php echo esc_attr($product_image_png);?>">

	<input type="hidden" id="woo3dv_upload_url" value="<?php echo esc_attr( $upload_url ); ?>" />
	<input type="hidden" id="woo3dv_model_color" value="<?php echo esc_attr($product_color);?>">
	<input type="hidden" id="woo3dv_model_shininess" value="<?php echo esc_attr($product_shininess);?>">
	<input type="hidden" id="woo3dv_model_transparency" value="<?php echo esc_attr($product_transparency);?>">

	<input type="hidden" id="woo3dv_model_rotation_x" value="<?php echo esc_attr($rotation_x);?>">
	<input type="hidden" id="woo3dv_model_rotation_y" value="<?php echo esc_attr($rotation_y);?>">
	<input type="hidden" id="woo3dv_model_rotation_z" value="<?php echo esc_attr($rotation_z);?>">

	<input type="hidden" id="woo3dv_show_light_source1" value="<?php echo esc_attr($product_show_light_source1);?>">
	<input type="hidden" id="woo3dv_show_light_source2" value="<?php echo esc_attr($product_show_light_source2);?>">
	<input type="hidden" id="woo3dv_show_light_source3" value="<?php echo esc_attr($product_show_light_source3);?>">
	<input type="hidden" id="woo3dv_show_light_source4" value="<?php echo esc_attr($product_show_light_source4);?>">
	<input type="hidden" id="woo3dv_show_light_source5" value="<?php echo esc_attr($product_show_light_source5);?>">
	<input type="hidden" id="woo3dv_show_light_source6" value="<?php echo esc_attr($product_show_light_source6);?>">
	<input type="hidden" id="woo3dv_show_light_source7" value="<?php echo esc_attr($product_show_light_source7);?>">
	<input type="hidden" id="woo3dv_show_light_source8" value="<?php echo esc_attr($product_show_light_source8);?>">
	<input type="hidden" id="woo3dv_show_light_source9" value="<?php echo esc_attr($product_show_light_source9);?>">



	<input type="hidden" id="woo3dv_camera_position_x" value="<?php echo esc_attr( $product_camera_position_x ); ?>" />
	<input type="hidden" id="woo3dv_camera_position_y"  value="<?php echo esc_attr( $product_camera_position_y ); ?>" />
	<input type="hidden" id="woo3dv_camera_position_z"  value="<?php echo esc_attr( $product_camera_position_z ); ?>" />
	<input type="hidden" id="woo3dv_camera_lookat_x" value="<?php echo esc_attr( $product_camera_lookat_x ); ?>" />
	<input type="hidden" id="woo3dv_camera_lookat_y"  value="<?php echo esc_attr( $product_camera_lookat_y ); ?>" />
	<input type="hidden" id="woo3dv_camera_lookat_z"  value="<?php echo esc_attr( $product_camera_lookat_z ); ?>" />
	<input type="hidden" id="woo3dv_controls_target_x" value="<?php echo esc_attr( $product_controls_target_x ); ?>" />
	<input type="hidden" id="woo3dv_controls_target_y" value="<?php echo esc_attr( $product_controls_target_y ); ?>" />
	<input type="hidden" id="woo3dv_controls_target_z" value="<?php echo esc_attr( $product_controls_target_z ); ?>" />
	<input type="hidden" id="woo3dv_offset_z"  value="<?php echo esc_attr( $product_offset_z ); ?>" />


	<input type="hidden" id="woo3dv_show_fog"  value="<?php echo esc_attr( $product_show_fog ); ?>" />
	<input type="hidden" id="woo3dv_show_grid"  value="<?php echo esc_attr( $product_show_grid ); ?>" />
	<input type="hidden" id="woo3dv_show_ground"  value="<?php echo esc_attr( $product_show_ground ); ?>" />
	<input type="hidden" id="woo3dv_show_shadow"  value="<?php echo esc_attr( $product_show_shadow ); ?>" />
	<input type="hidden" id="woo3dv_background1"  value="<?php echo esc_attr( $product_background1 ); ?>" />
	<input type="hidden" id="woo3dv_background_transparency"  value="<?php echo esc_attr( $product_background_transparency ); ?>" />
	<input type="hidden" id="woo3dv_ground_mirror"  value="<?php echo esc_attr( $product_ground_mirror ); ?>" />
	<input type="hidden" id="woo3dv_fog_color"  value="<?php echo esc_attr( $product_fog_color ); ?>" />
	<input type="hidden" id="woo3dv_grid_color"  value="<?php echo esc_attr( $product_grid_color ); ?>" />
	<input type="hidden" id="woo3dv_ground_color"  value="<?php echo esc_attr( $product_ground_color ); ?>" />
	<input type="hidden" id="woo3dv_auto_rotation"  value="<?php echo esc_attr( $product_auto_rotation ); ?>" />
	<input type="hidden" id="woo3dv_view3d_button" value="<?php echo esc_attr( $product_view3d_button ); ?>" />




<?php
#print_r($product->get_default_attributes());


	$image_url = ob_get_contents();
	ob_end_clean();

	return $image_url;	
}




add_filter( 'wc_get_template', 'woo3dv_get_template', 10, 2 );
function woo3dv_get_template( $located, $template_name ) {
	$woo3dv_templates=array( 'single-product/product-image.php' );

	if ( woo3dv_is_woo3dv( get_queried_object_id() ) || woo3dv_is_woo3dv( get_the_ID() ) ) {
		if ( in_array( $template_name, $woo3dv_templates ) ) {
			$woo3dv_dir = woo3dv_plugin_path();
			$located = $woo3dv_dir."/woocommerce/$template_name";
		}
	}
	return $located;
}
add_filter( 'woocommerce_locate_template', 'woo3dv_woocommerce_locate_template', 10, 3 );
function woo3dv_woocommerce_locate_template( $template, $template_name, $template_path ) {
	$_template = $template;

	if ( woo3dv_is_woo3dv( get_queried_object_id() ) || woo3dv_is_woo3dv( get_the_ID() ) ) {

		if ( ! $template_path ) $template_path = $woocommerce->template_url;
		$plugin_path  = woo3dv_plugin_path() . '/woocommerce/';

		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		if ( ! $template && file_exists( $plugin_path . $template_name ) )
			$template = $plugin_path . $template_name;
	}
	else {

	}

	if ( ! $template )
		$template = $_template;

	return $template;
}

function woo3dv_plugin_path() {
	return untrailingslashit( dirname( plugin_dir_path( __FILE__ ) ) );
}



function woo3dv_basename($file) {
	$array=explode('/',$file);
	$base=array_pop($array);
	return $base;
} 


function woo3dv_find_all_files($dir) {
	$root = scandir($dir);
	foreach($root as $value) {
	if($value === '.' || $value === '..') {continue;}
		if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;}
		foreach(woo3dv_find_all_files("$dir/$value") as $value) {
			$result[]=$value;
		}
	}
	return $result;
} 

function woo3dv_get_accepted_models () {
/*
	$settings = get_option('woo3dv_settings');
	$file_extensions = explode(',', $settings['file_extensions']);
	$models = array();
	foreach ($file_extensions as $extension) {
		if ($extension=='zip') continue;
		$models[]=$extension;
		
	}
	return $models;
*/
	return array('stl', 'obj', 'wrl', 'glb', 'gltf', '3mf');
}

function woo3dv_get_support_extensions_inside_archive() {
	return array('mtl', 'png', 'jpg', 'jpeg', 'gif', 'tga', 'bmp', 'bin');
}
function woo3dv_get_allowed_extensions_inside_archive() {
	return array_merge(woo3dv_get_accepted_models(), woo3dv_get_support_extensions_inside_archive());
}

add_action('woocommerce_before_add_to_cart_button', 'woo3dv_before_add_to_cart_button');
function woo3dv_before_add_to_cart_button() {
	echo '<input id="woo3dv_thumbnail" name="woo3dv_thumbnail" type="hidden">';
}

add_filter( 'woocommerce_add_cart_item_data', 'woo3dv_add_cart_item_data', 10, 2 );
function woo3dv_add_cart_item_data( $cart_item_meta, $product_id ) {
	global $woocommerce, $wpdb, $order;
	$settings = get_option('woo3dv_settings');

	$upload_dir = wp_upload_dir();

	if ( isset( $_POST['woo3dv_thumbnail'] ) && strlen($_POST['woo3dv_thumbnail'])) {
		$thumbnail_data=sanitize_text_field($_POST['woo3dv_thumbnail']);
		$thumbnail_url=woo3dv_save_thumbnail( $thumbnail_data, uniqid().'.png' );
		if ( $thumbnail_url ) {
			$cart_item_meta['woo3dv_options'] = array();
			$cart_item_meta['woo3dv_options']['thumbnail']=$thumbnail_url;
		}
	}
	if ( isset( $_POST['woo3dv_page_id'] ) && is_numeric($_POST['woo3dv_page_id'])) {
		if (!isset($cart_item_meta['woo3dv_options'])) {
			$cart_item_meta['woo3dv_options'] = array();
		}
		$cart_item_meta['woo3dv_options']['page_id']=(int)$_REQUEST['woo3dv_page_id'];
	}
	return $cart_item_meta;
}

add_filter( 'woocommerce_cart_item_permalink', 'woo3dv_cart_item_permalink', 100, 3 );
function woo3dv_cart_item_permalink($permalink, $cart_item, $cart_item_key) {
	
	if (isset($cart_item['product_id']) && woo3dv_is_woo3dv($cart_item['product_id'])) {

		if (!$permalink) {
			$permalink = get_permalink($cart_item['product_id']);
		}
		if (isset($cart_item['woo3dv_options']) && isset($cart_item['woo3dv_options']['page_id'])) {
			$permalink = get_permalink($cart_item['woo3dv_options']['page_id']);
		}
		if ($permalink) {
			if (isset($cart_item['variation']) && !strstr($permalink, 'attribute_pa_')) {
	
				$query = parse_url($permalink, PHP_URL_QUERY);
				if ($query) {
					$permalink .= '&';
				} else {
					$permalink .= '?';
				}
				foreach ($cart_item['variation'] as $key => $value) {
					$permalink.="$key=".strip_tags(str_replace(array('&times;', '%', '&'),'',$value)).'&';
				}
			}
		}
	}

	return $permalink;
}


//Show the screenshot of the product
add_filter( 'woocommerce_cart_item_thumbnail', 'woo3dv_cart_item_thumbnail', 11, 3 );
function woo3dv_cart_item_thumbnail( $img, $cart_item, $cart_item_key ) {

	if ( isset( $cart_item['woo3dv_options'] ) && is_array( $cart_item['woo3dv_options'] ) && !empty( $cart_item['woo3dv_options']['thumbnail'] ) ) {
		$img = '<img class="woo3dv-cart-thumbnail" src="'.esc_url($cart_item['woo3dv_options']['thumbnail']).'">';
	}

	return $img;
}

add_filter('woocommerce_admin_order_item_thumbnail', 'woo3dv_admin_order_item_thumbnail', 10, 3);
function woo3dv_admin_order_item_thumbnail ($image, $item_id, $item) {

	$item_meta = wc_get_order_item_meta( $item_id, false );

	if (isset($item_meta['_woo3dv_thumbnail'])) {
		$image = '<img src="'.$item_meta['_woo3dv_thumbnail'][0].'" class="attachment-thumbnail size-thumbnail">';
	}
	return $image;
}

function woo3dv_add_order_item_meta($item_id, $values) {
	if (isset($values['woo3dv_options']) && isset($values['woo3dv_options']['thumbnail'])) {
		wc_add_order_item_meta($item_id, '_woo3dv_thumbnail', $values['woo3dv_options']['thumbnail']);
	}
}
add_action('woocommerce_add_order_item_meta', 'woo3dv_add_order_item_meta', 10, 2);

add_filter('woocommerce_order_item_get_formatted_meta_data', 'woo3dv_order_item_get_formatted_meta_data', 20, 2);
function woo3dv_order_item_get_formatted_meta_data($formatted_meta, $object) {
	foreach ($formatted_meta as $key=>$obj) {
		switch ($obj->key) {
			case "_woo3dv_thumbnail":
				$formatted_meta[$key]->display_key=__('Thumbnail URL', '3dprint');
			break;
		}

	}
	return $formatted_meta;
}




function woo3dv_process_mtl($mtl_path, $timestamp) {
	if (file_exists($mtl_path)) {
		$new_content='';
		$handle = fopen($mtl_path, "r");  
		while (($line = fgets($handle)) !== false) {
			if (substr( trim(strtolower($line)), 0, 4 ) === "map_") {
				list ($map, $file) = explode(' ', $line, 2);
				$newline = "$map $timestamp"."_".sanitize_file_name(basename($file))."\n";
			} else {
				$newline = $line;
			}
			$new_content.=$newline;
		  }
		fclose($handle);
		file_put_contents($mtl_path, $new_content);
	}
}

function woo3dv_process_wrl($wrl_path, $timestamp) {
	ini_set( 'memory_limit', '-1' );
	if (file_exists($wrl_path)) {
		$new_content='';
		$handle = fopen($wrl_path, "r");  
		while (($line = fgets($handle)) !== false) {
			if (substr( trim(strtolower($line)), 0, 4 ) === "url ") {
				list ($map, $file) = explode(' ', $line, 2);
				$file = trim($file);
				$file = trim($file, '"');
				$file = trim($file, "'");
				if ( substr( $file, 0, 5 ) === 'http:') continue;
				if ( substr( $item, 0, 5 ) === 'data:') continue;
				if ( substr( $file, 0, 6 ) === 'https:') continue;
				$newline = "$map \"$timestamp"."_".sanitize_file_name(basename($file))."\"\n";
			} else {
				$newline = $line;
			}
			$new_content.=$newline;
		  }
		fclose($handle);
		file_put_contents($wrl_path, $new_content);
	}
}



function woo3dv_process_gltf($gltf_path, $timestamp) {
	ini_set( 'memory_limit', '-1' );
	if (file_exists($gltf_path)) {
		$new_content='';
		$gltf = json_decode(file_get_contents($gltf_path), true);
		array_walk_recursive($gltf, function(&$item, $key) use($timestamp){
			if (strtolower($key)=='uri') {
				if ( substr( $item, 0, 5 ) === 'http:') return;
				if ( substr( $item, 0, 5 ) === 'data:') return;
				if ( substr( $item, 0, 6 ) === 'https:') return;
				$item = $timestamp."_".sanitize_file_name(basename($item));
			}
		});
		$new_content = json_encode($gltf);
		file_put_contents($gltf_path, $new_content);
	}
}



function woo3dv_get_mtl($file_path) {
	if (file_exists($file_path)) {
		$handle = fopen($file_path, "r");  
		while (($line = fgets($handle)) !== false) {
			if (substr( trim(strtolower($line)), 0, 6 ) === "mtllib") {
				list ($mtllib, $file) = explode(' ', $line, 2);
				list ($time, $name) = explode('_', woo3dv_basename($file_path), 2);
				return $time."_".$file;
			}
		}
	}
	return '';
}
function woo3dv_unprotected_warning() {
	$class = 'notice notice-error is-dismissible';


#	if (!woo3dv_get_current_protection() && $_GET['page']=='woo3dv') {
#		$message = esc_html__( 'Your <a href="'.woo3dv_uploads_url().'">uploads folder</a> seems unprotected. Consider <a href="https://www.wpbeginner.com/wp-tutorials/disable-directory-browsing-wordpress/">disabling wordpress directory browsing</a>.', 'woo3dv' );
#		printf( '<div class="%1$s"><b>Woo3DViewer</b>: %2$s</div>', $class, $message ); 
#	}
}
add_action( 'admin_notices', 'woo3dv_unprotected_warning' );


function woo3dv_conflict_warning() {
	$class = 'notice notice-error is-dismissible';


	if (isset($_GET['page']) && $_GET['page']=='woo3dv') {
		$conflicting_plugins = array();
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			if (\Automattic\WooCommerce\Utilities\FeaturesUtil::feature_is_enabled( 'product_block_editor' )) {
				$conflicting_plugins[]='<a href="'.admin_url( 'admin.php?page=wc-settings&tab=advanced&section=features').'">WooCommerce new product editor</a>';
			}
		}
		if (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')) {
			$conflicting_plugins[]='All in One SEO';
		}
		if (count($conflicting_plugins)) {
			$message = esc_html__('Woo3DViewer detected other 3D viewers which may cause conflicts: '.implode(', ', $conflicting_plugins), 'woo3dv');
			printf( '<div class="%1$s"><b>Woo3DViewer</b>: %2$s</div>', esc_attr($class), esc_html($message) ); 
		}


	}
}
add_action( 'admin_notices', 'woo3dv_conflict_warning' );

/**
 * Author:            Alexis Blondin
*/
function woo3dv_uploads_url() {
	$uploads_dir = wp_upload_dir();
	return $uploads_dir['baseurl'];
}

function woo3dv_uploads_dir() {
	$uploads_dir = wp_upload_dir();
	return $uploads_dir['basedir'];
}



/**
 * Author:            Alexis Blondin
*/
/*function woo3dv_get_current_protection() {
	// check if header is 200 (ok)
	$uploads_headers = @get_headers( woo3dv_uploads_url() . '/' );
	if(!is_array($uploads_headers)) $uploads_headers[0] = '';
	if( preg_match('/200/i', $uploads_headers[0] )) {
		// because
		if( !file_exists( woo3dv_uploads_dir() .'/index.php' ) ) {
			return false;
		}
		else {
			return true;
		}
	}
	// check if header is 403 (forbidden)
	if( preg_match('/403/i', $uploads_headers[0] )) {
		return true;
	}
}*/



function woo3dv_handle_zip() {
	if ( !isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'woo3dv-file-upload' ) ) {
		wp_die( '{"jsonrpc" : "2.0", "error" : {"code": 99, "message": "Sorry, your nonce did not verify."}, "id" : "id"}' );	
	}

	set_time_limit( 5*60 );
	ini_set( 'memory_limit', '-1' );

	do_action('woo3dv_handle_zip');

	$settings = woo3dv_get_option( 'woo3dv_settings' );
	$post_id = (int)$_REQUEST['post_id'];

	$uploads = wp_upload_dir();

	$attached_file = get_post_meta( $post_id, '_wp_attached_file', true ); 
	$attached_file_dir = dirname($attached_file); 
	if ($attached_file) {
//$upload_dir['baseurl']
		$filepath = $uploads['basedir'].'/'.$attached_file;
		$targetDir = dirname($filepath).'/';
		$output = woo3dv_process_zip($filepath, $targetDir);
		$output['model_url'] = $uploads['baseurl'].'/'.$attached_file_dir.'/'.$output['model_file'];
		$output['material_url'] = $uploads['baseurl'].'/'.$attached_file_dir.'/'.$output['material_file'];

		$output['jsonrpc'] = "2.0";
		if ($output['model_file']) {
			$output['status'] = "1"; 
		}
		else {
			$output['model_'];
			$output['status'] = "0"; 
		}
		wp_die( json_encode( $output ) );
		
	}
	else wp_die( '{"jsonrpc" : "2.0", "error" : {"code": 111, "message": "'.esc_html__( 'Unknown error.', 'woo3dv' ).'"}, "id" : "id"}' );	

}
?>