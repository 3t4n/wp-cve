<?php

add_action('vc_before_init', 'directorypress_vc_init');

function directorypress_vc_init() {
	global $directorypress_object, $directorypress_fsubmit_instance, $directorypress_google_maps_styles;
	if (directorypress_has_map()){
		$map_styles = array('default' => '');
		foreach ($directorypress_google_maps_styles AS $name=>$style){
			$map_styles[$name] = $name;
		}
	}
	$packages = array(__('All', 'DIRECTORYPRESS') => 0);
	foreach ($directorypress_object->packages->packages_array AS $package) {
		$packages[$package->name] = $package->id;
	}
	$ordering = array_flip(directorypress_sorting_options());
	
	if (!isset($directorypress_object->fields)) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		return ;
	}

	if (!function_exists('directorypress_ordering_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('ordering', 'directorypress_ordering_param');
		function directorypress_ordering_param($settings, $value) {
			$ordering = array_flip(directorypress_sorting_options());

			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			foreach ($ordering AS $ordering_item) {
				$out .= '<option value="' . $ordering_item['value'] . '" ' . selected($value, $ordering_item['value'], false) . '>' . $ordering_item['label'] . '</option>';
			}
			$out .= '</select>';
	
			return $out;
		}
	}
	if (directorypress_has_map()){
		if (!function_exists('directorypress_mapstyle_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
			vc_add_shortcode_param('mapstyle', 'directorypress_mapstyle_param');
			function directorypress_mapstyle_param($settings, $value) {
				$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
				$out .= '<option value="0" ' . ((!$value) ? 'selected' : 0) . '>' . __('Default', 'DIRECTORYPRESS') . '</option>';
				$map_styles = array('default' => '');
				foreach (directorypress_map_styles() AS $name=>$style) {
					$out .= '<option value="' . $name . '" ' . selected($value, $name, false) . '>' . $name . '</option>';
				}
				$out .= '</select>';
		
				return $out;
			}
		}
	}

	if (!function_exists('directorypress_directorytypes_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('directorytypes', 'directorypress_directorytypes_param');
		function directorypress_directorytypes_param($settings, $value) {
			global $directorypress_object;

			$out = "<script>
				jQuery(function() {
					jQuery('body').on('change', '#" . $settings['param_name'] . "_select', function($){
						var el = jQuery(this);
						var input_el = el.next();
						input_el.val(el.val());
						
					});
				});
			</script>";

			$out .= '<select id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" multiple="multiple">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : 0) . '>' . __('- Auto -', 'DIRECTORYPRESS') . '</option>';
			foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype) {
				$out .= '<option value="' . $directorytype->id . '" ' . selected($value, $directorytype->id, false) . '>' . $directorytype->name . '</option>';
			}
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="' . $value . '" />';
			return $out;
		}
	}
	
	if (!function_exists('directorypress_directory_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('directorytype', 'directorypress_directory_param');
		function directorypress_directory_param($settings, $value) {
			global $directorypress_object;

			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : 0) . '>' . __('- Auto -', 'DIRECTORYPRESS') . '</option>';
			foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype) {
				$out .= '<option value="' . $directorytype->id . '" ' . selected($value, $directorytype->id, false) . '>' . $directorytype->name . '</option>';
			}
			$out .= '</select>';
	
			return $out;
		}
	}
	
	if (!function_exists('directorypress_packages_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('packages', 'directorypress_packages_param');
		function directorypress_packages_param($settings, $value) {
			global $directorypress_object;
	
			$out = "<script>
				jQuery(function() {
					jQuery('body').on('change', '#" . $settings['param_name'] . "_select', function($){
						var el = jQuery(this);
						var input_el = el.next();
						input_el.val(el.val());
						
					});
				});
			</script>";
	
			$out .= '<select id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" multiple="multiple">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- Auto -', 'DIRECTORYPRESS') . '</option>';
			foreach ($directorypress_object->packages->packages_array AS $package) {
				$out .= '<option value="' . $package->id . '" ' . selected($value, $package->id, false) . '>' . $package->name . '</option>';
			}
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="' . $value . '" />';
			return $out;
		}
	}

	if (!function_exists('directorypress_package_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('package', 'directorypress_package_param');
		function directorypress_package_param($settings, $value) {
			global $directorypress_object;

			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : 0) . '>' . __('- Auto -', 'DIRECTORYPRESS') . '</option>';
			foreach ($directorypress_object->packages->packages_array AS $package) {
				$out .= '<option value="' . $package->id . '" ' . selected($value, $package->id, false) . '>' . $package->name . '</option>';
			}
			$out .= '</select>';
	
			return $out;
		}
	}

	if (!function_exists('directorypress_categories_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('categoriesfield', 'directorypress_categories_param');
		function directorypress_categories_param($settings, $value) {
			$out = "<script>
				jQuery(function() {
					jQuery('body').on('change', '#" . $settings['param_name'] . "_select', function($){
						var el = jQuery(this);
						var input_el = el.next();
						input_el.val(el.val());
						
					});
				});
			</script>";
		
			$out .= '<select multiple="multiple" id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" style="height: 300px">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- Select All -', 'DIRECTORYPRESS') . '</option>';
			ob_start();
			directorypress_renderOptionsTerms(DIRECTORYPRESS_CATEGORIES_TAX, 0, explode(',', $value));
			$out .= ob_get_clean();
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="' . $value . '" />';
		
			return $out;
		}
	}

	if (!function_exists('directorypress_category_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('categoryfield', 'directorypress_category_param');
		function directorypress_category_param($settings, $value) {
			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- No category selected -', 'DIRECTORYPRESS') . '</option>';
			ob_start();
			directorypress_renderOptionsTerms(DIRECTORYPRESS_CATEGORIES_TAX, 0, array($value));
			$out .= ob_get_clean();
			$out .= '</select>';
		
			return $out;
		}
	}

	if (!function_exists('directorypress_locations_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('locationsfield', 'directorypress_locations_param');
		function directorypress_locations_param($settings, $value) {
			$out = "<script>
				jQuery(function() {
					jQuery('body').on('change', '#" . $settings['param_name'] . "_select', function($){
						var el = jQuery(this);
						var input_el = el.next();
						input_el.val(el.val());
						
					});
				});
			</script>";
		
			$out .= '<select multiple="multiple" id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" style="height: 300px">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- Select All -', 'DIRECTORYPRESS') . '</option>';
			ob_start();
			directorypress_renderOptionsTerms(DIRECTORYPRESS_LOCATIONS_TAX, 0, explode(',', $value));
			$out .= ob_get_clean();
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="' . $value . '" />';
		
			return $out;
		}
	}

	if (!function_exists('directorypress_location_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('locationfield', 'directorypress_location_param');
		function directorypress_location_param($settings, $value) {
			$out = '<select id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- No location selected -', 'DIRECTORYPRESS') . '</option>';
			ob_start();
			directorypress_renderOptionsTerms(DIRECTORYPRESS_LOCATIONS_TAX, 0, array($value));
			$out .= ob_get_clean();
			$out .= '</select>';
		
			return $out;
		}
	}

	if (!function_exists('directorypress_fields_param')) { // some "unique" themes/plugins call vc_before_init more than ones - this is such protection
		vc_add_shortcode_param('contentfields', 'directorypress_fields_param');
		function directorypress_fields_param($settings, $value) {
			global $directorypress_object;
			$out = "<script>
				jQuery(function() {
					jQuery('body').on('change', '#" . $settings['param_name'] . "_select', function($){
						var el = jQuery(this);
						var input_el = el.next();
						input_el.val(el.val());
						
					});
				});
			</script>";

			$fields_ids = explode(',', $value);
			$out .= '<select multiple="multiple" id="' . $settings['param_name'] . '_select" name="' . $settings['param_name'] . '_select" style="height: 300px">';
			$out .= '<option value="" ' . ((!$value) ? 'selected' : '') . '>' . __('- All content fields -', 'DIRECTORYPRESS') . '</option>';
			$out .= '<option value="-1" ' . (($value == -1) ? 'selected' : '') . '>' . __('- No content fields -', 'DIRECTORYPRESS') . '</option>';
			foreach ($directorypress_object->search_fields->search_fields_array AS $search_field)
				$out .= '<option value="' . $search_field->field->id . '" ' . (in_array($search_field->field->id, $fields_ids) ? 'selected' : '') . '>' . $search_field->field->name . '</option>';
			$out .= '</select>';
			$out .= '<input type="hidden" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] . '" class="wpb_vc_param_value" value="'.$value.'" />';
		
			return $out;
		}
	}
	
	vc_map( array(
		'name'                    => __('Directory', 'DIRECTORYPRESS'),
		'description'             => __('Main shortcode', 'DIRECTORYPRESS'),
		'base'                    => 'directorypress-main',
		'icon'                    => DIRECTORYPRESS_RESOURCES_URL . 'images/directorypress.png',
		'show_settings_on_create' => true,
		'category'                => __('Listing Content', 'DIRECTORYPRESS'),
		'params'                  => array(
			array(
					'type' => 'dropdown',
					'param_name' => 'custom_home',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Is it on custom home page?', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'directorytype',
					'param_name' => 'id',
					'heading' => __('Select Directory', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textarea_raw_html',
					'param_name' => 'archive_top_banner',
					'heading' => __('Add Custom Banner or adsense ads below header ', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textarea_raw_html',
					'param_name' => 'archive_below_search_banner',
					'heading' => __('Add Custom Banner or adsense ads below Search ', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textarea_raw_html',
					'param_name' => 'archive_below_category_banner',
					'heading' => __('Add Custom Banner or adsense ads below Categories ', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textarea_raw_html',
					'param_name' => 'archive_below_locations_banner',
					'heading' => __('Add Custom Banner or adsense ads below Locations', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textarea_raw_html',
					'param_name' => 'archive_below_listings_banner',
					'heading' => __('Add Custom Banner or adsense ads below Listings ', 'DIRECTORYPRESS'),
			),
			array(
						'type' => 'contentfields',
						'param_name' => 'search_fields',
						'heading' => __('Select certain content fields', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'contentfields',
						'param_name' => 'search_fields_advanced',
						'heading' => __('Select certain content fields in advanced section', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'hide_search_button',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Hide search button', 'DIRECTORYPRESS'),
				),
		),
	));
	$vc_listings_args = array(
		'name'                    => __('Listings', 'DIRECTORYPRESS'),
		'description'             => __('Listings filtered by params', 'DIRECTORYPRESS'),
		'base'                    => 'directorypress-listings',
		'icon'                    => DIRECTORYPRESS_RESOURCES_URL . 'images/directorypress.png',
		'show_settings_on_create' => true,
		'category'                => __('Listing Content', 'DIRECTORYPRESS'),
		'params'                  => array(
			array(
					'type' => 'directorytypes',
					'param_name' => 'directorytypes',
					'heading' => __("Listings of these directorytypes", "DIRECTORYPRESS"),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'uid',
					'value' => '',
					'heading' => __('Enter unique string to connect this shortcode with another shortcode.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'onepage',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Show all possible listings on one page?', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'listing_post_style',
					'value' => apply_filters("directorypress_listing_grid_styles_vc" , "directorypress_listing_grid_styles_vc_function"),
					'heading' => __('Listing Style.', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'listing_has_featured_tag_style',
				'value' => apply_filters("directorypress_listing_grid_styles_featured_tags_vc" , "directorypress_listing_grid_styles_featured_tags_vc_function"),
				'heading' => __('Listing Feature Tag Style.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => '2col_responsive',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Show 2 column listing on Mobile devices.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'masonry_layout',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Turn on Masonry Layout.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'perpage',
					'value' => 10,
					'heading' => __('Number of listing per page', 'DIRECTORYPRESS'),
					'description' => __('Number of listings to display per page. Set -1 to display all listings without paginator.', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'onepage', 'value' => '0'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'hide_paginator',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Hide paginator', 'DIRECTORYPRESS'),
					'description' => __('When paginator is hidden - it will display only exact number of listings.', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'onepage', 'value' => '0'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'scrolling_paginator',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Load next set of listing on scroll', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'onepage', 'value' => '0'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'has_sticky_has_featured',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Show only has_sticky or/and has_featured listings?', 'DIRECTORYPRESS'),
					'description' => __('Whether to show only has_sticky or/and has_featured listings.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'order_by',
					'value' => $ordering,
					'heading' => __('Order by', 'DIRECTORYPRESS'),
					'description' => __('Order listings by any of these parameter.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'order',
					'value' => array(__('Ascending', 'DIRECTORYPRESS') => 'ASC', __('Descending', 'DIRECTORYPRESS') => 'DESC'),
					'description' => __('Direction of sorting.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'hide_order',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Hide ordering links?', 'DIRECTORYPRESS'),
					'description' => __('Whether to hide ordering navigation links.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'listing_order_by_txt',
					'heading' => __('Order By Text', 'DIRECTORYPRESS'),
					'description' => __('Option will work if Ordering links are On', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'hide_order', 'value' => '0'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'hide_count',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Hide number of listings?', 'DIRECTORYPRESS'),
					'description' => __('Whether to hide number of found listings.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'show_views_switcher',
					'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
					'heading' => __('Show listings views switcher?', 'DIRECTORYPRESS'),
					'description' => __('Whether to show listings views switcher.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'listings_view_type',
					'value' => array(__('List', 'DIRECTORYPRESS') => 'list', __('Grid', 'DIRECTORYPRESS') => 'grid'),
					'heading' => __('Listings view by default', 'DIRECTORYPRESS'),
					'description' => __('Do not forget that selected view will be stored in cookies.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'listings_view_grid_columns',
					'value' => array('1', '2', '3', '4', '5', '6'),
					'heading' => __('Number of columns for listings Grid View', 'DIRECTORYPRESS'),
					//'std' => 2,
			),
			array(
					'type' => 'textfield',
					'param_name' => 'listing_thumb_width',
					'heading' => __('Listing thumbnail logo width in List View', 'DIRECTORYPRESS'),
					'description' => __('in pixels', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'wrap_logo_list_view',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Wrap logo image by text content in List View', 'DIRECTORYPRESS'),
			),
			
			
			array(
					'type' => 'textfield',
					'param_name' => 'grid_padding',
					'value' => '15',
					'heading' => __('Grid padding ', 'DIRECTORYPRESS'),
					'description' => __('padding between columns', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'range',
					'param_name' => 'listing_image_width',
					'value' => 370,
					'min' => 0,
					'max' => 770,
					'heading' => __('Grid image width', 'DIRECTORYPRESS'),
					'step' => 1,
					'unit' => 'px',
					//'std' => 2,
			),
			array(
					'type' => 'range',
					'param_name' => 'listing_image_height',
					'value' => 270,
					'min' => 0,
					'max' => 770,
					'heading' => __('Grid image Height', 'DIRECTORYPRESS'),
					'step' => 1,
					'unit' => 'px',
					//'std' => 2,
			),
			array(
					'type' => 'textfield',
					'param_name' => 'address',
					'heading' => __('Address', 'DIRECTORYPRESS'),
					'description' => __('Display listings near this address, recommended to set "radius" attribute.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'radius',
					'heading' => __('Radius', 'DIRECTORYPRESS'),
					'description' => __('Display listings near provided address within this radius in miles or kilometers.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'author',
					'heading' => __('Author', 'DIRECTORYPRESS'),
					'description' => __('Enter exact ID of author or word "related" to get assigned listings of current author (works only on listing page or author page)', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'related_categories',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Use related categories.', 'DIRECTORYPRESS'),
					'description' => __('Parameter works only on listings and categories pages.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'categoriesfield',
					'param_name' => 'categories',
					//'value' => 0,
					'heading' => __('Select certain categories', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'related_locations',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Use related locations.', 'DIRECTORYPRESS'),
					'description' => __('Parameter works only on listings and locations pages.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'locationsfield',
					'param_name' => 'locations',
					//'value' => 0,
					'heading' => __('Select certain locations', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'related_tags',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Use related tags.', 'DIRECTORYPRESS'),
					'description' => __('Parameter works only on listings and tags pages.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'include_categories_children',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Include children of selected categories and locations', 'DIRECTORYPRESS'),
					'description' => __('When enabled - any subcategories or sublocations will be included as well. Related categories and locations also affected.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'checkbox',
					'param_name' => 'packages',
					'value' => $packages,
					'heading' => __('Listings packages', 'DIRECTORYPRESS'),
					'description' => __('Categories may be dependent from listings packages.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'post__in',
					'heading' => __('Exact listings', 'DIRECTORYPRESS'),
					'description' => __('Comma separated string of listings IDs. Possible to display exact listings.', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'scroll',
					'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
					'heading' => __('Scroll', 'DIRECTORYPRESS'),
					'description' => __('listing carousel', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'autoplay',
					'value' => array(__('No', 'DIRECTORYPRESS') => 'false', __('Yes', 'DIRECTORYPRESS') => 'true'),
					'heading' => __('Autoplay', 'DIRECTORYPRESS'),
					'description' => __('Autoplay', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'loop',
					'value' => array(__('No', 'DIRECTORYPRESS') => 'false', __('Yes', 'DIRECTORYPRESS') => 'true'),
					'heading' => __('Loop', 'DIRECTORYPRESS'),
					'description' => __('Loop', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'owl_nav',
					'value' => array(__('No', 'DIRECTORYPRESS') => 'false', __('Yes', 'DIRECTORYPRESS') => 'true'),
					'heading' => __('Scroller Nav', 'DIRECTORYPRESS'),
					'description' => __('Scroller Nav', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'scroller_nav_style',
					'value' => array(__('Style 1', 'DIRECTORYPRESS') => '1', __('Style 2', 'DIRECTORYPRESS') => '2'),
					'heading' => __('Scroller Navigation style.', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'gutter',
					'value' => '30',
					'heading' => __('margin ', 'DIRECTORYPRESS'),
					'description' => __('margin between columns', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'desktop_items',
					'value' => '3',
					'heading' => __('desktop items ', 'DIRECTORYPRESS'),
					'description' => __('items to display above 1025px', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'tab_landscape_items',
					'value' => '3',
					'heading' => __('tab landscape items ', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'tab_items',
					'value' => '2',
					'heading' => __('Tab items', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'delay',
					'value' => '1000',
					'heading' => __('Scroll Delay  ', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'autoplay_speed',
					'value' => '1000',
					'heading' => __('scrolling speed', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'custom_category_link',
					'value' => '',
					'heading' => __('Custom Category Link', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'custom_category_link_text',
					'value' => '',
					'heading' => __('Custom Category Link Text', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'checkbox',
					'param_name' => 'visibility',
					'heading' => __("Show only on directorytype pages", "DIRECTORYPRESS"),
					'value' => 1,
					'description' => __("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
			),
		),
	);
	foreach ($directorypress_object->search_fields->search_fields_array AS $search_field) {
		if (method_exists($search_field, 'gat_vc_params') && ($field_params = $search_field->gat_vc_params()))
			$vc_listings_args['params'] = array_merge($vc_listings_args['params'], $field_params);
	}
	vc_map($vc_listings_args);
	
	vc_map(array(
			'name'                    => __('Single Listing', 'DIRECTORYPRESS'),
			'description'             => __('The page with single listing', 'DIRECTORYPRESS'),
			'base'                    => 'directorypress-listing',
			'icon'                    => DIRECTORYPRESS_RESOURCES_URL . 'images/directorypress.png',
			'show_settings_on_create' => true,
			'category'                => __('Listing Content', 'DIRECTORYPRESS'),
			'params'                  => array(
					array(
							'type' => 'textfield',
							'param_name' => 'listing_id',
							'heading' => __('ID of listing', 'DIRECTORYPRESS'),
							'description' => __('Enter exact ID of listing or leave empty to build custom page for any single listing.', 'DIRECTORYPRESS'),
					),
			),
		)
	);
	if (directorypress_has_map()){
	$vc_maps_args = array(
			'name'                    => __('Listing Map', 'DIRECTORYPRESS'),
			'description'             => __('Listing map and markers', 'DIRECTORYPRESS'),
			'base'                    => 'directorypress-map',
			'icon'                    => DIRECTORYPRESS_RESOURCES_URL . 'images/directorypress.png',
			'show_settings_on_create' => true,
			'category'                => __('Listing Content', 'DIRECTORYPRESS'),
			'params'                  => array(
				array(
						'type' => 'dropdown',
						'param_name' => 'custom_home',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Is it on custom home page?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'directorytypes',
						'param_name' => 'directorytypes',
						'heading' => __("Listings of these directorytypes", "DIRECTORYPRESS"),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'map_markers_is_limit',
						'value' => array(__('Display all map markers', 'DIRECTORYPRESS') => '0', __('The only map markers of visible listings will be displayed (when listings shortcode is connected with map by unique string)', 'DIRECTORYPRESS') => '1'),
						'heading' => __('How many map markers to display on the map', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'uid',
						'value' => '',
						'heading' => __('uID. Enter unique string to connect this shortcode with another shortcode.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'draw_panel',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Enable Draw Panel', 'DIRECTORYPRESS'),
						'description' => __('Very important: MySQL version must be 5.6.1 and higher or MySQL server variable "thread stack" must be 256K and higher. Ask your host about it if "Draw Area" does not work.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'num',
						'value' => -1,
						'heading' => __('Number of markers', 'DIRECTORYPRESS'),
						'description' => __('Number of markers to display on map (-1 gives all markers).', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'width',
						'heading' => __('Width', 'DIRECTORYPRESS'),
						'description' => __('Set map width in pixels. With empty field the map will take all possible width.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'height',
						'value' => 400,
						'heading' => __('Height', 'DIRECTORYPRESS'),
						'description' => __('Set map height in pixels, also possible to set 100% value.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'mapstyle',
						'param_name' => 'map_style',
						'heading' => __('Maps style', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'has_sticky_scroll',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Make map to be has_sticky on scroll', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'has_sticky_scroll_toppadding',
						'value' => 0,
						'heading' => __('Sticky scroll top padding', 'DIRECTORYPRESS'),
						'description' => __('Top padding in pixels.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'has_sticky_scroll', 'value' => '1'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_summary_button',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Show summary button?', 'DIRECTORYPRESS'),
						'description' => __('Show summary button in InfoWindow?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_readmore_button',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Show read more button?', 'DIRECTORYPRESS'),
						'description' => __('Show read more button in InfoWindow?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'geolocation',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('GeoLocation', 'DIRECTORYPRESS'),
						'description' => __('Geolocate user and center map.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'ajax_loading',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('AJAX loading', 'DIRECTORYPRESS'),
						'description' => __('When map contains lots of markers - this may slow down map markers loading. Select AJAX to speed up loading. Requires Starting Address or Starting Point coordinates Latitude and Longitude.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'ajax_markers_loading',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Maps info window AJAX loading', 'DIRECTORYPRESS'),
						'description' => __('This may additionally speed up loading.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'start_address',
						'heading' => __('Starting Address', 'DIRECTORYPRESS'),
						'description' => __('When map markers load by AJAX - it should have starting point and starting zoom. Enter start address or select latitude and longitude (recommended). Example: 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'start_latitude',
						'heading' => __('Starting Point Latitude', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'start_longitude',
						'heading' => __('Starting Point Longitude', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'start_zoom',
						'heading' => __('Default zoom', 'DIRECTORYPRESS'),
						'value' => array(__("Auto", "DIRECTORYPRESS") => '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19'),
						'std' => '0',
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'has_sticky_has_featured',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Show markers only of has_sticky or/and has_featured listings?', 'DIRECTORYPRESS'),
						'description' => __('Whether to show markers only of has_sticky or/and has_featured listings.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'radius_circle',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Show radius circle?', 'DIRECTORYPRESS'),
						'description' => __('Display radius circle on map when radius filter provided.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'clusters',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Group map markers in clusters?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'enable_full_screen',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Enable full screen button', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'enable_wheel_zoom',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Enable zoom by mouse wheel', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'enable_dragging_touchscreens',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Enable map dragging on touch screen devices', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'center_map_onclick',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Center map on marker click', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'author',
						'heading' => __('Author', 'DIRECTORYPRESS'),
						'description' => __('Enter exact ID of author or word "related" to get assigned listings of current author (works only on listing page or author page)', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'related_categories',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Use related categories.', 'DIRECTORYPRESS'),
						'description' => __('Parameter works only on listings and categories pages.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'categoriesfield',
						'param_name' => 'categories',
						'heading' => __('Select listings categories to display on map', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'related_locations',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Use related locations.', 'DIRECTORYPRESS'),
						'description' => __('Parameter works only on listings and locations pages.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'locationsfield',
						'param_name' => 'locations',
						'heading' => __('Select listings locations to display on map', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'related_tags',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Use related tags.', 'DIRECTORYPRESS'),
						'description' => __('Parameter works only on listings and tags pages.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'include_categories_children',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Include children of selected categories and locations', 'DIRECTORYPRESS'),
						'description' => __('When enabled - any subcategories or sublocations will be included as well. Related categories and locations also affected.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'package',
						'param_name' => 'packages',
						'heading' => __('Listings packages', 'DIRECTORYPRESS'),
						'description' => __('Categories may be dependent from listings packages.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'post__in',
						'heading' => __('Exact listings', 'DIRECTORYPRESS'),
						'description' => __('Comma separated string of listings IDs. Possible to display exact listings.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'checkbox',
						'param_name' => 'visibility',
						'heading' => __("Show only on Listing pages", "DIRECTORYPRESS"),
						'value' => 1,
						'description' => __("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
				),
			),
	);
	
	foreach ($directorypress_object->search_fields->search_fields_array AS $search_field) {
		if (method_exists($search_field, 'gat_vc_params') && ($field_params = $search_field->gat_vc_params()))
			$vc_maps_args['params'] = array_merge($vc_maps_args['params'], $field_params);
	}
	
	vc_map($vc_maps_args);
	}
	vc_map( array(
		'name'                    => __('Categories List', 'DIRECTORYPRESS'),
		'description'             => __('Listing categories list', 'DIRECTORYPRESS'),
		'base'                    => 'directorypress-categories',
		'icon'                    => DIRECTORYPRESS_RESOURCES_URL . 'images/directorypress.png',
		'show_settings_on_create' => true,
		'category'                => __('Listing Content', 'DIRECTORYPRESS'),
		'params'                  => array(
			array(
					'type' => 'directorytype',
					'param_name' => 'directorytype',
					'heading' => __("Categories links will redirect to selected directorytype", "DIRECTORYPRESS"),
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'cat_style',
				'value' => apply_filters("directorypress_categories_styles_vc" , "directorypress_categories_styles_vc_function"),
				'heading' => __('category styles', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'textfield',
				'param_name' => 'parent',
				//'value' => 0,
				'heading' => __('Parent category', 'DIRECTORYPRESS'),
				'description' => __('ID of parent category (default 0 – this will build whole categories tree starting from the root).', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'depth',
				'value' => array('1', '2'),
				'heading' => __('Categories nesting level', 'DIRECTORYPRESS'),
				'description' => __('The max depth of categories tree. When set to 1 – only root categories will be listed.', 'DIRECTORYPRESS'),
				"dependency" => array(
                'element' => "cat_style",
                'value' => apply_filters("directorypress_categories_depth_conditions", "directorypress_categories_depth_conditions_function"),
				)
			),
			array(
				'type' => 'textfield',
				'param_name' => 'subcats',
				//'value' => 0,
				'heading' => __('Show subcategories items number', 'DIRECTORYPRESS'),
				'description' => __('This is the number of subcategories those will be displayed in the table, when category item includes more than this number "View all" link appears at the bottom.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'depth', 'value' => '2'),
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'columns',
				'value' => array('1', '2', '3', '4', '6', 'inline'),
				'heading' => __('Categories columns number', 'DIRECTORYPRESS'),
				'description' => __('Categories list is divided by columns.', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'cat_icon_type',
				'value' => array(__('Font Icons', 'DIRECTORYPRESS') => '1', __('Image Icons', 'DIRECTORYPRESS') => '2'),
				'heading' => __('Select Categories icon type', 'DIRECTORYPRESS'),
				'description' => '',
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'count',
				'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
				'heading' => __('Show category listings count?', 'DIRECTORYPRESS'),
				'description' => __('Whether to show number of listings assigned with current category in brackets.', 'DIRECTORYPRESS'),
			),
			array(
				"type" => "range",
				"heading" => esc_html__("Parent category font size", "DIRECTORYPRESS"),
				"param_name" => "cat_font_size",
				"value" => '',
				"min" => "0",
				"max" => "36",
				"step" => "1",
				"unit" => 'px',
			),
			array(
				"type" => "range",
				"heading" => esc_html__("Child category font size", "DIRECTORYPRESS"),
				"param_name" => "child_cat_font_size",
				"value" => '',
				"min" => "0",
				"max" => "24",
				"step" => "1",
				"unit" => 'px',
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'cat_font_weight',
					'value' => array(__('300', 'DIRECTORYPRESS') => '300', __('400', 'DIRECTORYPRESS') => '400', __('700', 'DIRECTORYPRESS') => '700',  __('900', 'DIRECTORYPRESS') => '900'),
					'heading' => __('Parent category font weight', 'DIRECTORYPRESS'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'child_cat_font_weight',
					'value' => array(__('300', 'DIRECTORYPRESS') => '300', __('400', 'DIRECTORYPRESS') => '400', __('700', 'DIRECTORYPRESS') => '700',  __('900', 'DIRECTORYPRESS') => '900'),
					'heading' => __('Child category font weight', 'DIRECTORYPRESS'),
					'description' => '',
					//'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'cat_font_line_height',
					'value' => '',
					'heading' => __('Parent category line-height ', 'DIRECTORYPRESS'),
					'description' => '',
					//'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'child_cat_font_line_height',
					'value' => '',
					'heading' => __('Child category line-height ', 'DIRECTORYPRESS'),
					'description' => '',
					//'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'cat_font_transform',
					'value' => array(__('Lowercase', 'DIRECTORYPRESS') => 'lowercase', __('Capitalize', 'DIRECTORYPRESS') => 'capitalize', __('Uppercase', 'DIRECTORYPRESS') => 'uppercase'),
					'heading' => __('Parent category text transform', 'DIRECTORYPRESS'),
					'description' => '',
					//'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'child_cat_font_transform',
					'value' => array(__('Lowercase', 'DIRECTORYPRESS') => 'lowercase', __('Capitalize', 'DIRECTORYPRESS') => 'capitalize', __('Uppercase', 'DIRECTORYPRESS') => 'uppercase'),
					'heading' => __('Child category text transform', 'DIRECTORYPRESS'),
					'description' => '',
					//'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category background color", "DIRECTORYPRESS"),
				"param_name" => "cat_bg",
				"value" => "",
				"description" => esc_html__("depended on category styles, this color will effect category icon box for style 1 and style 2, and will effect category wrapper for all other styles", "DIRECTORYPRESS")
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category background color on hover", "DIRECTORYPRESS"),
				"param_name" => "cat_bg_hover",
				"value" => "",
				"description" => esc_html__("depended on category styles, this color will effect category icon box for style 1 and style 2, and will effect category wrapper for all other styles", "DIRECTORYPRESS")
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category parent title color", "DIRECTORYPRESS"),
				"param_name" => "parent_cat_title_color",
				"value" => "",
				//"description" => esc_html__("depended on category styles, this color will effect category icon box for style 1 and style 2, and will effect category wrapper for all other styles", "DIRECTORYPRESS")
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category Child title color", "DIRECTORYPRESS"),
				"param_name" => "subcategory_title_color",
				"value" => "",
				//"description" => esc_html__("depended on category styles, this color will effect category icon box for style 1 and style 2, and will effect category wrapper for all other styles", "DIRECTORYPRESS")
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category parent title color on hover", "DIRECTORYPRESS"),
				"param_name" => "parent_cat_title_color_hover",
				"value" => "",
				//"description" => esc_html__("depended on category styles, this color will effect category icon box for style 1 and style 2, and will effect category wrapper for all other styles", "DIRECTORYPRESS")
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category Child title color on hover", "DIRECTORYPRESS"),
				"param_name" => "subcategory_title_color_hover",
				"value" => "",
				//"description" => esc_html__("depended on category styles, this color will effect category icon box for style 1 and style 2, and will effect category wrapper for all other styles", "DIRECTORYPRESS")
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category Border color", "DIRECTORYPRESS"),
				"param_name" => "cat_border_color",
				"value" => "",
				"description" => esc_html__("will effect style 6", "DIRECTORYPRESS")
			),
			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Category Border color on hover", "DIRECTORYPRESS"),
				"param_name" => "cat_border_color_hover",
				"value" => "",
				"description" => esc_html__("will effect style 6", "DIRECTORYPRESS")
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'scroll',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('scroll', 'DIRECTORYPRESS'),	
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'autoplay',
					'value' => array(__('No', 'DIRECTORYPRESS') => 'false', __('Yes', 'DIRECTORYPRESS') => 'true'),
					'heading' => __('Autoplay', 'DIRECTORYPRESS'),
					'description' => __('Autoplay', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'loop',
					'value' => array(__('No', 'DIRECTORYPRESS') => 'false', __('Yes', 'DIRECTORYPRESS') => 'true'),
					'heading' => __('Loop', 'DIRECTORYPRESS'),
					'description' => __('Loop', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'dropdown',
					'param_name' => 'owl_nav',
					'value' => array(__('No', 'DIRECTORYPRESS') => 'false', __('Yes', 'DIRECTORYPRESS') => 'true'),
					'heading' => __('Scroller Nav', 'DIRECTORYPRESS'),
					'description' => __('Scroller Nav', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'gutter',
					'value' => '30',
					'heading' => __('margin ', 'DIRECTORYPRESS'),
					'description' => __('margin between columns', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'desktop_items',
					'value' => '3',
					'heading' => __('desktop items ', 'DIRECTORYPRESS'),
					'description' => __('items to display above 1025px', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'tab_landscape_items',
					'value' => '3',
					'heading' => __('tab landscape items ', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'tab_items',
					'value' => '2',
					'heading' => __('Tab items', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'delay',
					'value' => '1000',
					'heading' => __('Scroll Delay  ', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
					'type' => 'textfield',
					'param_name' => 'autoplay_speed',
					'value' => '1000',
					'heading' => __('scrolling speed', 'DIRECTORYPRESS'),
					'dependency' => array('element' => 'scroll', 'value' => '1'),
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'packages',
				'value' => $packages,
				'heading' => __('Listings packages', 'DIRECTORYPRESS'),
				'description' => __('Categories may be dependent from listings packages.', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'categoriesfield',
				'param_name' => 'categories',
				//'value' => 0,
				'heading' => __('Categories', 'DIRECTORYPRESS'),
				'description' => __('Comma separated string of categories slugs or IDs. Possible to display exact categories.', 'DIRECTORYPRESS'),
			),
		),
	));

	vc_map( array(
		'name'                    => __('Locations List', 'DIRECTORYPRESS'),
		'class'                    => 'location-element',
		'description'             => __('Listing locations list', 'DIRECTORYPRESS'),
		'base'                    => 'directorypress-locations',
		'icon'                    => DIRECTORYPRESS_RESOURCES_URL . 'images/directorypress.png',
		'show_settings_on_create' => true,
		'category'                => __('Listing Content', 'DIRECTORYPRESS'),
		'params'                  => array(
			
			array(
				'type' => 'dropdown',
				'param_name' => 'location_style',
				'value' => apply_filters("directorypress_locations_styles_vc" , "directorypress_locations_styles_vc_function"),
				'heading' => __('Location styles', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'textfield',
				'param_name' => 'parent',
				//'value' => 0,
				'heading' => __('Parent location', 'DIRECTORYPRESS'),
				'description' => __('ID of parent location (default 0 – this will build whole locations tree starting from the root).', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
			),
			array(
            "type" => "colorpicker",
            "heading" => esc_html__("background Color", "DIRECTORYPRESS"),
            "param_name" => "location_bg",
            "value" => "",
			),
		array(
            "type" => "upload",
            "heading" => esc_html__("Background Image", "DIRECTORYPRESS"),
            "param_name" => "location_bg_image",
            "value" => ""
        ),
		array(
            "type" => "colorpicker",
            "heading" => esc_html__("Gradient Color 1", "DIRECTORYPRESS"),
            "param_name" => "gradientbg1",
            "value" => ""
        ),
		array(
            "type" => "colorpicker",
            "heading" => esc_html__("Gradient Color 2", "DIRECTORYPRESS"),
            "param_name" => "gradientbg2",
            "value" => ""
        ),
		array(
            "type" => "range",
            "heading" => esc_html__("Opacity Color 1", "DIRECTORYPRESS"),
            "param_name" => "opacity1",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%'
        ),
		array(
            "type" => "range",
            "heading" => esc_html__("Opacity Color 2", "DIRECTORYPRESS"),
            "param_name" => "opacity2",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => '%'
        ),
		array(
            "type" => "range",
            "heading" => esc_html__("Gradient Angle", "DIRECTORYPRESS"),
            "param_name" => "gradient_angle",
            "value" => "0",
            "min" => "0",
            "max" => "360",
            "step" => "1",
            "unit" => 'deg'
        ),
		array(
            "type" => "range",
            "heading" => esc_html__("Column width", "DIRECTORYPRESS"),
            "param_name" => "location_width",
            "value" => "30",
            "min" => "0",
            "max" => "200",
            "step" => "1",
            "unit" => '%'
        ),
		array(
            "type" => "range",
            "heading" => esc_html__("Column Height ", "DIRECTORYPRESS"),
            "param_name" => "location_height",
            "value" => "480",
            "min" => "0",
            "max" => "800",
            "step" => "1",
            "unit" => 'px'
        ),
		array(
            "type" => "range",
            "heading" => esc_html__("Padding", "DIRECTORYPRESS"),
            "param_name" => "location_padding",
            "value" => "15",
            "min" => "0",
            "max" => "200",
            "step" => "1",
            "unit" => 'px'
        ),
			array(
				'type' => 'dropdown',
				'param_name' => 'depth',
				'value' => array('1', '2'),
				'heading' => __('Locations nesting level', 'DIRECTORYPRESS'),
				'description' => __('The max depth of locations tree. When set to 1 – only root locations will be listed.', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'textfield',
				'param_name' => 'sublocations',
				//'value' => 0,
				'heading' => __('Show sub-locations items number', 'DIRECTORYPRESS'),
				'description' => __('This is the number of sublocations those will be displayed in the table, when location item includes more than this number "View all sublocations ->" link appears at the bottom.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'depth', 'value' => '2'),
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'columns',
				'value' => array('1', '2', '3', '4'),
				'heading' => __('Locations columns number', 'DIRECTORYPRESS'),
				'description' => __('Locations list is divided by columns.', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'dropdown',
				'param_name' => 'count',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Show location listings count?', 'DIRECTORYPRESS'),
				'description' => __('Whether to show number of listings assigned with current location in brackets.', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'locationsfield',
				'param_name' => 'locations',
				//'value' => 0,
				'heading' => __('Locations', 'DIRECTORYPRESS'),
				'description' => __('Comma separated string of locations slugs or IDs. Possible to display exact locations.', 'DIRECTORYPRESS'),
			),
		),
	));

	vc_map( array(
		'name'                    => __('Search form', 'DIRECTORYPRESS'),
		'description'             => __('Listing listings search form', 'DIRECTORYPRESS'),
		'base'                    => 'directorypress-search',
		'icon'                    => DIRECTORYPRESS_RESOURCES_URL . 'images/directorypress.png',
		'show_settings_on_create' => false,
		'category'                => __('Listing Content', 'DIRECTORYPRESS'),
		'params'                  => array(
				array(
						'type' => 'dropdown',
						'param_name' => 'custom_home',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Is it on custom home page?', 'DIRECTORYPRESS'),
						//'description' => __('When set to Yes - the widget will follow some parameters from Directory Settings and not those listed here.', 'DIRECTORYPRESS'),
				),
				/* array(
						'type' => 'dropdown',
						'param_name' => 'columns',
						'value' => array('2', '1'),
						'std' => '2',
						'heading' => __('Number of columns to arrange search fields', 'DIRECTORYPRESS'),
				), */
				array(
						'type' => 'directorytype',
						'param_name' => 'directorytype',
						'heading' => __("Search by directorytype", "DIRECTORYPRESS"),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'uid',
						'value' => '',
						'heading' => __('Enter unique string to connect this shortcode with another shortcode.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'advanced_open',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Advanced search panel always open', 'DIRECTORYPRESS'),
				),array(
						'type' => 'dropdown',
						'param_name' => 'has_sticky_scroll',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Make search form to be has_sticky on scroll', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'has_sticky_scroll_toppadding',
						'value' => 0,
						'heading' => __('Sticky scroll top padding', 'DIRECTORYPRESS'),
						'description' => __('Sticky scroll top padding in pixels.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'has_sticky_scroll', 'value' => '1'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_keywords_search',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Show keywords search?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'keywords_ajax_search',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Enable listings autosuggestions by keywords', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'keywords_search_examples',
						'heading' => __('Keywords examples', 'DIRECTORYPRESS'),
						'description' => __('Comma-separated list of suggestions to try to search.', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'what_search',
						'heading' => __('Default keywords', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_categories_search',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Show categories search?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'categories_search_depth',
						'value' => array('1', '2', '3'),
						'std' => '2',
						'heading' => __('Categories search depth package', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'categoryfield',
						'param_name' => 'category',
						'heading' => __('Select certain category', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'categoriesfield',
						'param_name' => 'exact_categories',
						'heading' => __('List of categories', 'DIRECTORYPRESS'),
						'description' => __('Comma separated string of categories slugs or IDs. Possible to display exact categories.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_locations_search',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Show locations search?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'locations_search_depth',
						'value' => array('1', '2', '3'),
						'std' => '2',
						'heading' => __('Locations search depth package', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'locationfield',
						'param_name' => 'location',
						'heading' => __('Select certain location', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'locationsfield',
						'param_name' => 'exact_locations',
						'heading' => __('List of locations', 'DIRECTORYPRESS'),
						'description' => __('Comma separated string of locations slugs or IDs. Possible to display exact locations.', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'custom_home', 'value' => '0'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_address_search',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Show address search?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'address',
						'heading' => __('Default address', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_radius_search',
						'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
						'heading' => __('Show locations radius search?', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'textfield',
						'param_name' => 'radius',
						'heading' => __('Default radius search', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'contentfields',
						'param_name' => 'search_fields',
						'heading' => __('Select certain content fields', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'contentfields',
						'param_name' => 'search_fields_advanced',
						'heading' => __('Select certain content fields in advanced section', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'hide_search_button',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Hide search button', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'on_row_search_button',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Search button on one line with fields', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'scroll_to',
						'value' => array(__('No scroll', 'DIRECTORYPRESS') => '', __('Listings', 'DIRECTORYPRESS') => 'listings', __('Map', 'DIRECTORYPRESS') => 'map'),
						'heading' => __('Scroll to listings, map or do not scroll after search button was pressed', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'checkbox',
						'param_name' => 'search_visibility',
						'heading' => __("Show only when there is no any other search form on page", "DIRECTORYPRESS"),
				),
				array(
						'type' => 'checkbox',
						'param_name' => 'visibility',
						'heading' => __("Show only on directorytype pages", "DIRECTORYPRESS"),
						'value' => 1,
						'description' => __("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
				),
				array(
						'type' => 'range',
						'param_name' => 'keyword_field_width',
						'value' => 25,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'unit' => '%',
						'heading' => __('Set Width for Keyword Field In Search Form', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'range',
						'param_name' => 'location_field_width',
						'value' => 25,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'unit' => '%',
						'heading' => __('Set Width for Location Field In Search Form', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'range',
						'param_name' => 'radius_field_width',
						'value' => 25,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'unit' => '%',
						'heading' => __('Set Width for Radius Field In Search Form', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'range',
						'param_name' => 'button_field_width',
						'value' => 25,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'unit' => '%',
						'heading' => __('Set Width for Search Button Field In Search Form', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'range',
						'param_name' => 'search_button_margin_top',
						'value' => 0,
						'min' => 0,
						'max' => 50,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Set Margin top for Search Button Field In Search Form', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'range',
						'param_name' => 'gap_in_fields',
						'value' => 10,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'unit' => '%',
						'heading' => __('Set gap between Field In Search Form', 'DIRECTORYPRESS'),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'show_default_filed_label',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Show Field Label for default fields', 'DIRECTORYPRESS'),
						'save_always' => true,
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'search_custom_style',
						'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Custom Styling', 'DIRECTORYPRESS'),
						'save_always' => true,
				),
				array(
						'type' => 'range',
						'param_name' => 'search_box_padding_top',
						'value' => '',
						'min' => 0,
						'max' => 200,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Search Box Padding Top', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
						'type' => 'range',
						'param_name' => 'search_box_padding_bottom',
						'value' => '',
						'min' => 0,
						'max' => 200,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Search Box Padding Bottom', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
						'type' => 'range',
						'param_name' => 'search_box_padding_left',
						'value' => '',
						'min' => 0,
						'max' => 200,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Search Box Padding Left', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
						'type' => 'range',
						'param_name' => 'search_box_padding_right',
						'value' => '',
						'min' => 0,
						'max' => 200,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Search Box Padding Right', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Main Search Box background Color", "DIRECTORYPRESS"),
					"param_name" => "main_searchbar_bg_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Main Search Box Border Color", "DIRECTORYPRESS"),
					"param_name" => "main_search_border_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
						'type' => 'range',
						'param_name' => 'input_field_border_width',
						'value' => '',
						'min' => 0,
						'max' => 10,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Input Field Border Width', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
						'type' => 'range',
						'param_name' => 'input_field_border_radius',
						'value' => '',
						'min' => 0,
						'max' => 10,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Input Field Border Radius', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Input Field Border Color", "DIRECTORYPRESS"),
					"param_name" => "input_field_border_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Input Field Label Color", "DIRECTORYPRESS"),
					"param_name" => "input_field_label_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Input Field Placeholder Color", "DIRECTORYPRESS"),
					"param_name" => "input_field_placeholder_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Input Field Text Color", "DIRECTORYPRESS"),
					"param_name" => "input_field_text_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
						'type' => 'range',
						'param_name' => 'search_button_border_width',
						'value' => '',
						'min' => 0,
						'max' => 10,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Submit Button Border Width', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
						'type' => 'range',
						'param_name' => 'search_button_border_radius',
						'value' => '',
						'min' => 0,
						'max' => 10,
						'step' => 1,
						'unit' => 'px',
						'heading' => __('Submit Button Border Radius', 'DIRECTORYPRESS'),
						'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Submit Button Text Color", "DIRECTORYPRESS"),
					"param_name" => "search_button_text_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Submit Button Text Hover Color", "DIRECTORYPRESS"),
					"param_name" => "search_button_text_color_hover",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Submit Button Background Color", "DIRECTORYPRESS"),
					"param_name" => "search_button_bg",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Submit Button Background Hover Color", "DIRECTORYPRESS"),
					"param_name" => "search_button_bg_hover",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Submit Button Border Color", "DIRECTORYPRESS"),
					"param_name" => "search_button_border_color",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					"type" => "colorpicker",
					"heading" => esc_html__("Submit Button Border Hover Color", "DIRECTORYPRESS"),
					"param_name" => "search_button_border_color_hover",
					"value" => "",
					"description" => '',
					'dependency' => array('element' => 'search_custom_style', 'value' => '1'),
				),
				array(
					'type' => 'textfield',
					'param_name' => 'search_button_icon',
					'value' => '',
					'heading' => esc_html__("Submit Button Icon Class", "DIRECTORYPRESS"),
				),
				array(
						'type' => 'dropdown',
						'param_name' => 'search_form_type',
						'value' => array(__('Custom', 'DIRECTORYPRESS') => '1'),
						'heading' => __('Form Type', 'DIRECTORYPRESS'),
						'save_always' => true,
				),
			),
	));

}