<?php 

class directorypress_search_handler extends directorypress_public {

	public function init($args = array()) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;

		parent::init($args);

		$this->args = array_merge(array(
				'custom_home' => 0,
				'directorytype' => 0,
				'columns' => 2,
				'gap_in_fields' => 10,
				'advanced_open' => false,
				'uid' => null,
				'show_keywords_category_combo' => 1,
				'show_categories_search' =>  1,
				'categories_search_depth' =>  2,
				'category' => 0,
				'exact_categories' => array(),
				'show_default_filed_label' => '',
				'show_keywords_search' =>  1,
				'keywords_ajax_search' =>  1,
				'keywords_search_examples' => '',
				'what_search' => '',
				'show_radius_search' =>  1,
				'radius' =>  (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_radius_search_default'],
				'show_locations_search' =>  1,
				'locations_search_depth' =>  1,
				'show_address_search' =>  1,
				'address' => '',
				'location' => 0,
				'exact_locations' => array(),
				'search_fields' => '',
				'search_fields_advanced' => '',
				'hide_search_button' => 0,
				'on_row_search_button' => 0,
				'has_sticky_scroll' => 0,
				'has_sticky_scroll_toppadding' => 0,
				'scroll_to' => 'listings', // '', 'listings', 'map'
				'keyword_field_width' => 25,
				'category_field_width' => 25,
				'location_field_width' => 25,
				'address_field_width' => 25,
				'radius_field_width' => 25,
				'button_field_width' => 25,
				'search_button_margin_top' => 0,
				'search_custom_style' => 0,
				'main_searchbar_bg_color' => '',
				'main_search_border_color' => '',
				'search_box_padding_top' => '',
				'search_box_padding_bottom' => '',
				'search_box_padding_left' => '',
				'search_box_padding_right' => '',
				'input_field_bg' => '',
				'input_field_border_color' => '',
				'input_field_placeholder_color' => '',
				'input_field_text_color' => '',
				'input_field_label_color' => '',
				'input_field_border_width' => '',
				'input_field_border_radius' => '',
				'search_button_border_radius' => '',
				'search_button_bg' => '',
				'search_button_bg_hover' => '',
				'search_button_border_color' => '',
				'search_button_border_color_hover' => '',
				'search_button_border_width' => '',
				'search_button_text_color' => '',
				'search_button_text_color_hover' => '',
				'search_button_icon' => '',
				'search_button_type' => 1,
				'search_form_type' => 1,
				'form_layout' => 'horizontal'
		), $args);
		
		$hash = false;
		if (!$this->args['custom_home'] && $this->args['uid']) {
			$hash = md5($this->args['uid']);
		}

		$this->search_form = new directorypress_search_form($hash, 'directorypress_listings_handler', $this->args);
		//$this->scripts();
		//add_action('wp_enqueue_scripts', array($this, 'scripts'));
		//add_action('init', $this->search_form, 'scripts');
		apply_filters('directorypress_search_handler_construct', $this);
	}
	public function scripts() {
		
		wp_enqueue_style('directorypress-search');
	}
	public function display() {
		ob_start();
		$this->search_form->display();
		$output = ob_get_clean();

		return $output;
	}
	
}

?>