<?php

global $directorypress_search_widget_params;
$directorypress_search_widget_params = array(
		array(
				'type' => 'dropdown',
				'param_name' => 'custom_home',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Is it on custom home page?', 'DIRECTORYPRESS'),
				//'description' => __('When set to Yes - the widget will follow some parameters from Directory Settings and not those listed here.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'directorytype',
				'param_name' => 'directorytype',
				'heading' => __("Search by directorytype", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'uid',
				'heading' => __("uID", "DIRECTORYPRESS"),
				'description' => __("Enter unique string to connect search form with another elements on the page.", "DIRECTORYPRESS"),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'columns',
				'value' => array('2', '1'),
				'std' => '2',
				'heading' => __('Number of columns to arrange search fields', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'advanced_open',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Advanced search panel always open', 'DIRECTORYPRESS'),
		),
		array(
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
				'type' => 'colorpicker',
				'param_name' => 'search_bg_color',
				'heading' => __("Background color", "DIRECTORYPRESS"),
				'value' => get_option('directorypress_search_bg_color'),
		),
		array(
				'type' => 'colorpicker',
				'param_name' => 'search_text_color',
				'heading' => __("Text color", "DIRECTORYPRESS"),
				'value' => get_option('directorypress_search_text_color'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'search_bg_opacity',
				'heading' => __("Opacity of search form background, in %", "DIRECTORYPRESS"),
				'value' => 100,
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'search_overlay',
				'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
				'heading' => __('Show background overlay', 'DIRECTORYPRESS'),
				'std' => get_option('directorypress_search_overlay')
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
);

class directorypress_search_widget extends directorypress_widget {

	public function __construct() {
		global $directorypress_object, $directorypress_search_widget_params;

		parent::__construct(
				'directorypress_search_widget',
				__('Directory - Search', 'DIRECTORYPRESS'),
				__( 'Search Form', 'DIRECTORYPRESS')
		);

		foreach ($directorypress_object->search_fields->filter_fields_array AS $filter_field) {
			if (method_exists($filter_field, 'gat_vc_params') && ($field_params = $filter_field->gat_vc_params())) {
				$directorypress_search_widget_params = array_merge($directorypress_search_widget_params, $field_params);
			}
		}

		$this->convertParams($directorypress_search_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $directorypress_object;
		
		// when visibility enabled - show only on directorytype pages
		if (empty($instance['visibility']) || !empty($directorypress_object->public_handlers)) {
			// when search_visibility enabled - show only when main search form wasn't displayed
			if (!empty($instance['search_visibility']) && !empty($directorypress_object->public_handlers)) {
				foreach ($directorypress_object->public_handlers AS $shortcode_handlers) {
					foreach ($shortcode_handlers AS $directorypress_handler) {
						if (is_object($directorypress_handler) && $directorypress_handler->search_form) {
							return false;
						}
					}
				}
			}
				
			$title = apply_filters('widget_title', $instance['title']);
				
			// it is auto selection - take current directorytype
			if ($instance['directorytype'] == 0) {
				// probably we are on single listing page - it could be found only after frontend handlers were loaded, so we have to repeat setting
				$directorypress_object->setup_current_page_directorytype();
		
				$instance['directorytype'] = $directorypress_object->current_directorytype->id;
			}
			$instance['form_layout'] = 'vertical';
			$instance['gap_in_fields'] = 0;
			echo wp_kses_post($args['before_widget']);
			if (!empty($title)) {
				echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
			}
			echo '<div class="directorypress-content-wrap directorypress-widget directorypress-search-widget">';
			$directorypress_handler = new directorypress_search_handler();
			$directorypress_handler->init($instance);
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			echo wp_kses_post($args['after_widget']);
		}
	}
}
?>