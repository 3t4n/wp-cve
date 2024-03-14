<?php

global $directorypress_listings_widget_params;
$directorypress_listings_widget_params = array(
		array(
				'type' => 'checkbox',
				'param_name' => 'is_footer',
				'heading' => __("Check if its Footer Widget area", "DIRECTORYPRESS"),
				'value' => 0,
				'description' => __("Otherwise Listing style will be disturded", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'directorytypes',
				'param_name' => 'directorytypes',
				'heading' => __("Listings of these directorytypes", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'listing_post_style',
				'value' => apply_filters("directorypress_listing_widget_grid_styles", "directorypress_listing_widget_grid_styles_function"),
				'heading' => __('Listing Style', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'is_footer', 'value' => 0),
		),
		//apply_filters("directorypress_listing_widget_settings_filter", "directorypress_listing_widget_settings"),
		array(
				'type' => 'textfield',
				'param_name' => 'listings_grid_columns',
				'value' => 3,
				'heading' => __('Listing Grid Columns', 'DIRECTORYPRESS'),
				'description' => __('works only when widget is in footer.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'is_footer', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'number_of_listings',
				'value' => 6,
				'heading' => __('Number of listings', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'width',
				'value' => 370,
				'heading' => __('Listing Thumbnail Width', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'height',
				'value' => 250,
				'heading' => __('Listing Thumbnail Height', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'only_has_sticky_has_featured',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Show only sticky and featured listings?', 'DIRECTORYPRESS'),
				'std' => '0',
				'description' => __('Whether to show only sticky and featured listings. or show all', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'ordering',
				'param_name' => 'order_by',
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
				'param_name' => 'hide_content',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Hide content fields data', 'DIRECTORYPRESS'),
				'std' => '1',
		),
		array(
				'type' => 'textfield',
				'param_name' => 'address',
				'heading' => __('Address', 'DIRECTORYPRESS'),
				'description' => __('Display listings near this address, recommended to set default radius', 'DIRECTORYPRESS'),
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
				'type' => 'package',
				'param_name' => 'packages',
				'heading' => __('Listings packages', 'DIRECTORYPRESS'),
				'description' => __('Categories may be dependent from listings packages.', 'DIRECTORYPRESS'),
		),
		/* array(
				'type' => 'textfield',
				'param_name' => 'post__in',
				'heading' => __('Exact listings', 'DIRECTORYPRESS'),
				'description' => __('Comma separated string of listings IDs. Possible to display exact listings.', 'DIRECTORYPRESS'),
		), */
		array(
				'type' => 'dropdown',
				'param_name' => 'is_slider_view',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Turn On/Off Slider', 'DIRECTORYPRESS'),
				//'dependency' => array('element' => 'is_footer', 'value' => '0'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'autoplay',
				'heading' => __("Autoplay ", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => __("Turn autoplay on or off", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'loop',
				'heading' => __("Slider Loop ", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => __("Turn loop on or off", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'owl_nav',
				'heading' => __("Slider Navigation ", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => __("Turn Navigation on or off", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'delay',
				'heading' => __("Slider Animation Delay ", "DIRECTORYPRESS"),
				'value' => 1000,
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'autoplay_speed',
				'heading' => __("Slider autoplay speed ", "DIRECTORYPRESS"),
				'value' => 1000,
				'dependency' => array('element' => 'is_slider_view', 'value' => '1'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'visibility',
				'heading' => __("Show only on directorytype pages", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => __("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
		),
);

class directorypress_listings_widget extends directorypress_widget {

	public function __construct() {
		global $directorypress_object, $directorypress_listings_widget_params;

		parent::__construct(
				'directorypress_listings_widget', // name for backward compatibility
				__('DIRECTORYPRESS - Listings', 'DIRECTORYPRESS')
		);

		//foreach ($directorypress_object->search_fields->filter_fields_array AS $filter_field) {
			//if (method_exists($filter_field, 'gat_vc_params') && ($field_params = $filter_field->gat_vc_params())) {
				//$directorypress_listings_widget_params = array_merge($directorypress_listings_widget_params, $field_params);
			//}
		//}
		
		$this->convertParams($directorypress_listings_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS; 
		
		
			$instance['hide_paginator'] = 1;
			$instance['perpage'] = $instance['number_of_listings'];
			$instance['has_sticky_has_featured'] = $instance['only_has_sticky_has_featured'];
			$instance['hide_count'] = 1;
			$instance['hide_order'] = 1;
			$instance['show_views_switcher'] = 0;
			$instance['listings_view_type'] = 'grid';
			$instance['include_get_params'] = 0;
			$instance['listing_image_width'] = 	(isset($instance['width']) && !empty($instance['width']))? $instance['width']:'';
			$instance['listing_image_height'] = (isset($instance['height']) && !empty($instance['height']))? $instance['height']:'';
			$instance['desktop_items'] = 1;
			$instance['tab_landscape_items'] = 1;
			$instance['tab_items'] = 1;
			$instance['gutter'] = 0 ; //cz custom
			$instance['masonry_layout'] = 0;
			$instance['2col_responsive'] = 0;
			$instance['is_widget'] = 1;
			$in_footer = (isset($instance['is_footer']))? $instance['is_footer']: 0;
			if($in_footer){
				$instance['listing_post_style'] = 'footer_widget' ;
				$instance['listings_view_grid_columns'] = $instance['listings_grid_columns'] ;
				$instance['grid_padding'] = 3; //cz custom
				$instance['scroll'] = 0;
			}else{
				$instance['listing_post_style'] = (isset($instance['listing_post_style']) && !empty($instance['listing_post_style']))? $instance['listing_post_style']: 16;	
				$instance['listings_view_grid_columns'] = 1;
				$instance['scroll'] = (isset($instance['is_slider_view']) && !empty($instance['is_slider_view']))? $instance['is_slider_view']: 0;
				$instance['grid_padding'] = 0; //cz custom
			}
			
		// when visibility enabled - show only on directorytype pages
		if (empty($instance['visibility']) || !empty($directorypress_object->public_handlers)) {
			
			$title = apply_filters('widget_title', $instance['title']);
	
			echo wp_kses_post($args['before_widget']);
			if (!empty($title)) {
				echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
			}
			echo '<div class=" directorypress-widget directorypress_recent_listings_widget">';
					$directorypress_handler = new directorypress_listings_handler();
					$directorypress_handler->init($instance);
					echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			echo wp_kses_post($args['after_widget']);
		}
	}
}
?>