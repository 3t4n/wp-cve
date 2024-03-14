<?php

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public
 * @author     Designinvento <developers@designinvento.net>
 */
class DirectoryPress_Public_Handler {
	private $plugin_name;
	private $version;
	
	public function __construct( $plugin_name, $version ) {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/single-listing-functions.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shortcode-functions.php';
	}
	
	public function enqueue_styles() {
		wp_register_style('bootstrap', DIRECTORYPRESS_RESOURCES_URL . 'lib/bootstrap/css/bootstrap.min.css');
		wp_register_style('select2', DIRECTORYPRESS_RESOURCES_URL . 'lib/select2/css/select2.css');
			wp_register_style('fontawesome', DIRECTORYPRESS_RESOURCES_URL . 'lib/fontawesome/css/all.min.css');
			wp_register_style('material-icons', DIRECTORYPRESS_RESOURCES_URL . 'lib/material-icons/material-icons.min.css');
			wp_register_style('slick-carousel', DIRECTORYPRESS_RESOURCES_URL . 'lib/slick-carousel/css/slick.css');
			wp_register_style('slick-carousel-theme', DIRECTORYPRESS_RESOURCES_URL . 'lib/slick-carousel/css/slick-theme.css');
			
			// locations
			//wp_register_style('directorypress_locations', DIRECTORYPRESS_RESOURCES_URL . 'css/locations.css');
			// categories
			//wp_register_style('directorypress_category', DIRECTORYPRESS_RESOURCES_URL . 'css/directorypress-categories.css');
			
			//wp_register_style('directorypress-archive', DIRECTORYPRESS_RESOURCES_URL . 'css/archive.css');
			
			wp_register_style('directorypress-single', DIRECTORYPRESS_RESOURCES_URL . 'css/single-listing.css');
			
			wp_register_style('lightbox', DIRECTORYPRESS_RESOURCES_URL . 'lib/lightbox/css/lightbox.min.css');
			
			wp_register_style('jquery-ui-style', DIRECTORYPRESS_RESOURCES_URL .'css/jqueryui/themes/smoothness/jquery-ui.min.css');
			
			wp_register_style('directorypress_style', DIRECTORYPRESS_RESOURCES_URL . 'css/style.css');
			wp_register_style('directorypress_rtl', DIRECTORYPRESS_RESOURCES_URL . 'css/rtl.css');
			
			// listings
			wp_register_style('directorypress_listings', DIRECTORYPRESS_RESOURCES_URL . 'css/directorypress-listings.css');
			wp_register_style('directorypress_listing_style_default', DIRECTORYPRESS_RESOURCES_URL . 'css/listing/listing-style-default.css');
			
			wp_register_style('directorypress-search', DIRECTORYPRESS_RESOURCES_URL . 'css/directorypress-search.css');
			
			wp_enqueue_style('bootstrap');
			wp_enqueue_style('select2');
			wp_enqueue_style('jquery-ui-style');
			wp_enqueue_style('fontawesome');
			wp_enqueue_style('material-icons');
			wp_enqueue_style('slick-carousel');
			wp_enqueue_style('slick-carousel-theme');
			wp_enqueue_style('directorypress-search');
			if(directorypress_is_archive_page()){
				
				global $DIRECTORYPRESS_ADIMN_SETTINGS;
				$style = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style']))? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style']: 'default';
				wp_enqueue_style('directorypress_category');
				wp_enqueue_style('directorypress_listings');
				wp_enqueue_style('directorypress_listing_style_'.apply_filters('directorypress_archive_page_grid_style', $style));
				wp_enqueue_style('directorypress-archive');
			}
			if(directorypress_is_listing_page() || is_author()){
				wp_enqueue_style('directorypress_listings');
				wp_enqueue_style('directorypress-single');
				wp_enqueue_style('lightbox');
			}
			
			wp_enqueue_style('directorypress_style');
			
			if (function_exists('is_rtl') && is_rtl()){
				wp_enqueue_style('directorypress_rtl');
			}
	}
	
	public function enqueue_scripts() {
			global $DIRECTORYPRESS_ADIMN_SETTINGS;
			wp_register_script('bootstrap', DIRECTORYPRESS_RESOURCES_URL . 'lib/bootstrap/js/bootstrap.min.js', array('jquery'), false, true);
			wp_register_script('masonry', DIRECTORYPRESS_RESOURCES_URL . 'lib/masonry/js/masonry.pkgd.min.js', array('jquery'), false, true);
			wp_register_script('lightbox', DIRECTORYPRESS_RESOURCES_URL . 'lib/lightbox/js/lightbox.min.js', array('jquery'), false, true);
			wp_register_script('jquery-lity', DIRECTORYPRESS_RESOURCES_URL . 'lib/lity/js/lity.min.js', array('jquery'), false, true);
			wp_register_script('select2', DIRECTORYPRESS_RESOURCES_URL . 'lib/select2/js/select2.min.js', array('jquery'), false, true);
			wp_register_script('directorypress-select2-triger', DIRECTORYPRESS_RESOURCES_URL . 'lib/select2/js/select2-triger.js', array('jquery'), false, true);
			wp_register_script('slick-js', DIRECTORYPRESS_RESOURCES_URL . 'lib/slick-carousel/js/slick.min.js', array('jquery'), false, true);
			wp_register_script('slick-carousel-triger', DIRECTORYPRESS_RESOURCES_URL . 'lib/slick-carousel/js/slick-triger.min.js', array('jquery'), false, true);
			wp_register_script('directorypress-public', DIRECTORYPRESS_RESOURCES_URL . 'js/directorypress-public.js', array('jquery'), false, true);
			wp_register_script('jquery-cookie', DIRECTORYPRESS_RESOURCES_URL . 'js/jquery.cookie.js', array('jquery'), false, true);
			wp_register_script('directorypress-terms', DIRECTORYPRESS_RESOURCES_URL . 'js/directorypress-terms.js', array('jquery'), false, true);
			wp_register_script('directorypress-contact', DIRECTORYPRESS_RESOURCES_URL . 'js/contact-form.js', array('jquery'), false, true);
			//wp_register_script('directorypress-compare', DIRECTORYPRESS_RESOURCES_URL . 'js/compare.js', array('jquery'), false, true);
			
			add_action('wp_head', array($this, 'enqueue_global_vars'));
			wp_enqueue_script('jquery');
			wp_enqueue_script('bootstrap');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-selectmenu');
			wp_enqueue_script('jquery-ui-autocomplete');
			wp_enqueue_script('select2');
			wp_enqueue_script('directorypress-select2-triger');
			wp_enqueue_script('masonry');
			wp_enqueue_script('lightbox');
			wp_enqueue_script('jquery-lity');
			wp_enqueue_script('slick');
			wp_enqueue_script('slick-carousel-triger');
			wp_enqueue_script('jquery-cookie');
			wp_enqueue_script('directorypress-terms');
			//wp_enqueue_script('directorypress-compare-js');
			wp_enqueue_script('directorypress-contact');
			if(is_author() && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon'])){
				global $direviews_plugin;
				$direviews_plugin->enqueue_scripts();
			}
			
			
			wp_enqueue_script('directorypress-public');
			
			wp_localize_script(
				'directorypress-public',
				'directorypress_maps_function_call',
				array(
						'callback' => 'directorypress_load_maps_api'
				)
			);
			
			/* wp_localize_script(
				'directorypress-public',
				'directorypress_maps_function_call',
				array(
						'callback' => 'directorypress_init_backend_map_api'
				)
			); */
			
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_recaptcha'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_public_key'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_private_key']) {
				wp_register_script('directorypress_has_recaptcha', '//google.com/recaptcha/api.js');
				wp_enqueue_script('directorypress_has_recaptcha');
			}

	}
	
	public function enqueue_global_vars() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$ajaxurl = admin_url('admin-ajax.php?lang=' .  $sitepress->get_current_language());
		} else
			$ajaxurl = admin_url('admin-ajax.php');

		echo '<script>';
		
		echo 'var directorypress_handler_args_array = {};';
		// move to maps addon
		if (directorypress_has_map()){
			echo 'var directorypress_map_markers_attrs_array = [];';
			
			echo 'var directorypress_map_markers_attrs = (function(map_id, markers_array, enable_radius_circle, enable_clusters, show_summary_button, show_readmore_button, draw_panel, map_style, enable_full_screen, enable_wheel_zoom, enable_dragging_touchscreens, center_map_onclick, show_directions, map_attrs) {
			this.map_id = map_id;
			this.markers_array = markers_array;
			this.enable_radius_circle = enable_radius_circle;
			this.enable_clusters = enable_clusters;
			this.show_summary_button = show_summary_button;
			this.show_readmore_button = show_readmore_button;
			this.draw_panel = draw_panel;
			this.map_style = map_style;
			this.enable_full_screen = enable_full_screen;
			this.enable_wheel_zoom = enable_wheel_zoom;
			this.enable_dragging_touchscreens = enable_dragging_touchscreens;
			this.center_map_onclick = center_map_onclick;
			this.show_directions = show_directions;
			this.map_attrs = map_attrs;
			});';
		}
		global $directorypress_google_maps_styles;
		$in_favourites_icon = 'checked fas fa-heart';
		$in_favourites_icon2 = 'checked fas fa-heart';
		$in_favourites_icon3 = 'checked directorypress-icon-bookmark';
		$in_favourites_icon4 = 'checked fas fa-bookmark';
		$not_in_favourites_icon = 'unchecked fas fa-heart';
		$not_in_favourites_icon2 = 'unchecked fas fa-heart';
		$not_in_favourites_icon3 = 'unchecked directorypress-icon-bookmark-o';
		$not_in_favourites_icon4 = 'unchecked far fa-bookmark';
		
		echo 'var directorypress_js_instance = ' . json_encode(
				array(
						'ajaxurl' => $ajaxurl,
						'in_favourites_msg' => __('Bookmarked', 'DIRECTORYPRESS'),
						'not_in_favourites_msg' => __('Bookmark', 'DIRECTORYPRESS'),
						'ajax_load' => true,
						'is_rtl' => is_rtl(),
						'is_ratting' => (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon'])? 1: 0,
						'is_admin' => (int)is_admin(),
						'send_button_text' => __('Send message', 'DIRECTORYPRESS'),
						'send_button_sending' => __('Sending...', 'DIRECTORYPRESS'),
						'recaptcha_public_key' => (($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_recaptcha'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_public_key'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_private_key']) ? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_public_key'] : ''),
						'lang' => (($sitepress && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_language_from_wpml']) ? ICL_LANGUAGE_CODE : ''),
						'has_map' => directorypress_has_map(),
						'directorypress_show_radius_tooltip' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_radius_tooltip'],
						'directorypress_miles_kilometers_in_search' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_miles_kilometers_in_search'],
						'max_attchment_size' => (isset($DIRECTORYPRESS_ADIMN_SETTINGS['max_attchment_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['max_attchment_size']))? $DIRECTORYPRESS_ADIMN_SETTINGS['max_attchment_size'] : 500, // value in kilobytes
						'max_attchment_size_unit' => __('Kilobytes', 'DIRECTORYPRESS'),
						'max_attchment_size_error' =>  __('skipped, File size should not exceed', 'DIRECTORYPRESS'),
				)
		) . ';';
		// move to maps addon
	if (directorypress_has_map()){	
		$map_fields = $directorypress_object->fields->get_map_fields();
		$map_fields_icons = array('fa-info-circle');
		foreach ($map_fields AS $field){
			if (is_a($field, 'directorypress_field') && $field->icon_image)
				$map_fields_icons[] = $field->icon_image;
			else
				$map_fields_icons[] = '';
		}
		echo 'var directorypress_maps_instance = ' . json_encode(
				array(
						'notinclude_maps_api' => ((defined('DIRECTORYPRESS_NOTINCLUDE_MAPS_API') && DIRECTORYPRESS_NOTINCLUDE_MAPS_API) ? 1 : 0),
						'google_api_key' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_google_api_key'],
						'mapbox_api_key' => (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_mapbox_api_key']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_mapbox_api_key']))? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_mapbox_api_key'] : '',
						'map_markers_type' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_markers_type'],
						'default_marker_color' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_color'],
						'default_marker_icon' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_marker_icon'],
						'global_map_icons_path' => DIRECTORYPRESS_MAP_ICONS_URL,
						'directorypress_map_info_window_button_readmore' => __('Read more »', 'DIRECTORYPRESS'),
						'directorypress_map_info_window_button_summary' => __('« Summary', 'DIRECTORYPRESS'),
						'draw_area_button' => '',
						'edit_area_button' => '',
						'apply_area_button' => '',
						'reload_map_button' => '',
						'enable_my_location_button' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_geocode'],
						'my_location_button' => __('My Location', 'DIRECTORYPRESS'),
						'my_location_button_error' => __('GeoLocation service does not work on your device!', 'DIRECTORYPRESS'),
						'directorypress_map_fields_icons' => $map_fields_icons,
						'map_style' => directorypress_map_style_selected(),
						'lang' => (($sitepress && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_language_from_wpml']) ? ICL_LANGUAGE_CODE : ''),
						'address_autocomplete' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_autocomplete'],
						'address_autocomplete_code' => (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_autocomplete_code']))? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_autocomplete_code']: '',
				)
		) . ';';
	}
		echo '</script>';
	}
	
	public function dequeue_maps_googleapis() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$dequeue = false;
		if ((directorypress_has_map() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_google_api_key'] && !(defined('DIRECTORYPRESS_NOTINCLUDE_MAPS_API') && DIRECTORYPRESS_NOTINCLUDE_MAPS_API)) && !(defined('DIRECTORYPRESS_NOT_DEQUEUE_MAPS_API') && DIRECTORYPRESS_NOT_DEQUEUE_MAPS_API)) {
			$dequeue = true;
		}
		
		$dequeue = apply_filters('directorypress_dequeue_maps_googleapis', $dequeue);
		
		if ($dequeue) {
			// dequeue only at the frontend or at admin directory pages
			if (!is_admin() || (is_admin() && directorypress_is_admin_directory_page())) {
				global $wp_scripts;
				foreach ($wp_scripts->registered AS $key=>$script) {
					if (strpos($script->src, 'maps.googleapis.com') !== false || strpos($script->src, 'maps.google.com/maps/api') !== false) {
						unset($wp_scripts->registered[$key]);
					}
				}
			}
		}
	}
}
