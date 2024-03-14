<?php

class directorypress_search_form {
	public $uid;
	public $directorypress_handler;
	public $args = array();
	public $search_fields_array = array();
	public $search_fields_array_advanced = array();
	public $search_fields_array_all = array();
	public $is_advanced_search_panel = false;
	public $form_id;
	public $advanced_open = false;
	public $directorytypes = array(); // native search form needs only one directorytype, search form on the map can use multiple
	public $form_layout = 'horizontal';
	
	public function __construct($uid = null, $directorypress_handler = 'directorypress_listings_handler', $args = array()) {
		global $directorypress_object;
		
		$this->uid = $uid;
		$directorypress_object->search_fields->load_search_fields();
		$this->directorypress_handler = $directorypress_handler;
		$this->scripts();
		$this->args = array_merge(array(
				'custom_home' => 0,
				'directorytype' => 0,
				'columns' => 1,
				'gap_in_fields' => 10,
				'show_categories_search' => 1,
				'categories_search_depth' => 1,
				'category' => 0,
				'exact_categories' => array(),
				'show_default_filed_label' =>'',
				'show_keywords_category_combo' => 1,
				'show_keywords_search' => 1,
				'keywords_ajax_search' => 1,
				'keywords_search_examples' => '',
				'what_search' => '',
				'show_radius_search' => 1,
				'radius' => 0,
				'show_locations_search' => 1,
				'locations_search_depth' => 1,
				'show_address_search' => 1,
				'address' => '',
				'location' => 0,
				'exact_locations' => array(),
				'search_fields' => '',
				'search_fields_advanced' => '',
				'search_bg_color' => '',
				'search_bg_opacity' => 100,
				'search_text_color' => '',
				'hide_search_button' => 0,
				'on_row_search_button' => 0,
				'has_sticky_scroll' => 0,
				'has_sticky_scroll_toppadding' => 0,
				'scroll_to' => 'listings',
				'search_custom_style' => 0,
				'main_searchbar_bg_color' => '',
				'main_search_border_color' => '',
				'search_box_padding_top' => '20',
				'search_box_padding_bottom' => '90',
				'search_box_padding_left' => '60',
				'search_box_padding_right' => '40',
				'input_field_height' => '70',
				'input_field_bg' => '444',
				'input_field_border_color' => 'red',
				'input_field_placeholder_color' => '888',
				'input_field_text_color' => '#aaa',
				'input_field_label_color' => '#ddd',
				'input_field_border_width' => '3',
				'input_field_border_radius' => '6',
				'search_button_border_radius' => '',
				'search_button_bg' => '',
				'search_button_bg_hover' => '',
				'search_button_border_color' => '',
				'search_button_border_color_hover' => '',
				'search_button_border_width' => '',
				'search_button_text_color' => '',
				'search_button_text_color_hover' => '',
				'search_button_icon' => '',
				'keyword_field_icon' => 'glyphicon glyphicon-search',
				//'keyword_field_icon' => DIRECTORYPRESS_RESOURCES_URL .'images/search.png',
				'category_field_icon' => 'glyphicon glyphicon-search',
				'address_field_icon' => 'dicode-material-icons dicode-material-icons-map-marker-outline',
				'location_field_icon' => 'dicode-material-icons dicode-material-icons-map-marker-outline',
				'default_fields_icon_type' => 'font',
				'search_button_type' => 1,
				'search_form_type' => 1,
				//'form_layout' => 'horizontal'
				
		), $args);
		$this->form_layout = $this->args['form_layout'];
		if ($this->args['custom_home']) {
			if ($directorypress_object->current_directorytype->categories) {
				$this->args['exact_categories'] = $directorypress_object->current_directorytype->categories;
			}
			if ($directorypress_object->current_directorytype->locations) {
				$this->args['exact_locations'] = $directorypress_object->current_directorytype->locations;
			}
			$this->directorytypes = array($directorypress_object->current_directorytype->id);
		} elseif ($this->args['directorytype'] && ($directorytype = $directorypress_object->directorytypes->directory_by_id($this->args['directorytype']))) {
			if ($directorytype->categories) {
				$this->args['exact_categories'] = $directorytype->categories;
			}
			if ($directorytype->locations) {
				$this->args['exact_locations'] = $directorytype->locations;
			}
			$this->directorytypes = array($this->args['directorytype']);
		}
		if (isset($this->args['exact_categories']) && !is_array($this->args['exact_categories'])) {
			if ($categories = array_filter(explode(',', $this->args['exact_categories']), 'trim')) {
				$this->args['exact_categories'] = $categories;
			}
		}
		if (isset($this->args['exact_locations']) && !is_array($this->args['exact_locations'])) {
			if ($locations = array_filter(explode(',', $this->args['exact_locations']), 'trim')) {
				$this->args['exact_locations'] = $locations;
			}
		}

		if ((isset($this->args['search_fields']) && $this->args['search_fields'] && $this->args['search_fields'] != -1) || (isset($this->args['search_fields_advanced']) && $this->args['search_fields_advanced'] && $this->args['search_fields_advanced'] != -1)) {
			$search_fields_ids = explode(',', $this->args['search_fields']);
			$search_fields_ids_advanced = explode(',', $this->args['search_fields_advanced']);
			$search_fields_ids_all = array_filter(array_merge($search_fields_ids, $search_fields_ids_advanced));
			
			foreach ($search_fields_ids_all AS $id) {
				if ($search_field = $directorypress_object->search_fields->get_search_field_by_id($id)) {
					if (in_array($id, $search_fields_ids))
						$this->search_fields_array[$id] = $search_field;
					elseif (in_array($id, $search_fields_ids_advanced))
						$this->search_fields_array_advanced[$id] = $search_field;
				}
			}
		} else {
			foreach ($directorypress_object->search_fields->search_fields_array AS $id=>$search_field)
				if ($search_field->field->advanced_search_form && (!isset($this->args['search_fields_advanced']) || $this->args['search_fields_advanced'] != -1)) {
					$this->search_fields_array_advanced[$id] = $search_field;
				} elseif (!isset($this->args['search_fields']) || $this->args['search_fields'] != -1) {
					$this->search_fields_array[$id] = $search_field;
				}
		}

		$search_fields_array_all = $this->search_fields_array + $this->search_fields_array_advanced;
		
		// safely copy all fields into $this->search_fields_array_all, this array needs to manage hidden fields_in_categories[] = []
		foreach ($search_fields_array_all AS $key=>$search_field) {
			$this->search_fields_array_all[$key] = clone $search_field;
			$this->search_fields_array_all[$key]->reset_field_value();
		}
		
		if ($this->search_fields_array_advanced)
			$this->is_advanced_search_panel = true;

		if ((isset($_REQUEST['use_advanced']) && ($_REQUEST['use_advanced'] == 1)) || !empty($this->args['advanced_open']))
			$this->advanced_open = true;
	}
	public function scripts() {
		
		wp_enqueue_style('directorypress-search');
	}
	public function display_hidden_fields() {
		global $directorypress_object, $wp_rewrite;

		$hidden_fields = array();

		if (!$wp_rewrite->using_permalinks() && $directorypress_object->directorypress_archive_page_id && (get_option('show_on_front') != 'page' || get_option('page_on_front') != $directorypress_object->directorypress_archive_page_id))
			$hidden_fields['page_id'] = $directorypress_object->directorypress_archive_page_id;
		if ($directorypress_object->directorypress_archive_page_id)
			$hidden_fields['directorypress_action'] = "search";
		else
			$hidden_fields['s'] = "search";
		if ($this->uid)
			$hidden_fields['hash'] = $this->uid;
		if ($this->directorypress_handler)
			$hidden_fields['handler'] = $this->directorypress_handler;
		
		$hidden_fields['include_categories_children'] = 1;

		if ($this->directorytypes) {
			$hidden_fields['directorytypes'] = implode(',', $this->directorytypes);
		}

		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress)
			if ($sitepress->get_option('language_negotiation_type') == 3)
				$hidden_fields['lang'] =  $sitepress->get_current_language();

		if (!$this->args['show_categories_search'] && !empty($this->args['category'])) {
			$hidden_fields['categories'] = $this->args['category'];
		}
		if (!$this->args['show_keywords_search'] && !empty($this->args['what_search'])) {
			$hidden_fields['what_search'] = $this->args['what_search'];
		}
		if (!$this->args['show_locations_search'] && !empty($this->args['location'])) {
			$hidden_fields['location_id'] = $this->args['location'];
		}
		if (!$this->args['show_address_search'] && !empty($this->args['address'])) {
			$hidden_fields['address'] = $this->args['address'];
		}
		if (!$this->args['show_radius_search'] && !empty($this->args['radius'])) {
			$hidden_fields['radius'] = $this->args['radius'];
		}
		if (!empty($this->args['exact_categories'])) {
			$hidden_fields['exact_categories'] = implode(",", $this->args['exact_categories']);
		}
		if (!empty($this->args['exact_locations'])) {
			$hidden_fields['exact_locations'] = implode(",", $this->args['exact_locations']);
		}

		
		foreach ($this->args AS $arg_name=>$arg_value) {
			if (strpos($arg_name, 'field_') === 0) {
				$is_visible_field = false;
				foreach ($this->search_fields_array_all AS $search_field) {
					if ($search_field->is_this_field_param($arg_name)) {
						$is_visible_field = true;
						break;
					}
				}

				if (!$is_visible_field)
					$hidden_fields[$arg_name] = $arg_value;
			}
		}
		
		foreach ($hidden_fields AS $name=>$value) {
			if (is_array($value)) {
				foreach ($value AS $val) {
					echo '<input type="hidden" name="' . esc_attr($name) . '[]" value="' . esc_attr($val) . '" />';
				}
			} else {
				echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" />';
			}
		}
	}
	
	public function is_default_search_fields() {
		if (
			((!empty($this->args['show_categories_search']) && directorypress_is_anyone_in_taxonomy(DIRECTORYPRESS_CATEGORIES_TAX)) || !empty($this->args['show_keywords_search'])) ||
			((!empty($this->args['show_locations_search']) && directorypress_is_anyone_in_taxonomy(DIRECTORYPRESS_LOCATIONS_TAX)) || !empty($this->args['show_address_search']))
		) {
			return true;
		}
	}
	public function is_default_fields_label() {
		if (!empty($this->args['show_default_filed_label']) && $this->args['show_default_filed_label'] == 1)  {
			return true;
		}
	}
	public function is_categories() {
		if (!empty($this->args['show_categories_search']) && directorypress_is_anyone_in_taxonomy(DIRECTORYPRESS_CATEGORIES_TAX)) {
			return true;
		}
	}
	
	public function is_keywords_field() {
		if (!empty($this->args['show_keywords_search'])) {
			return true;
		}
	}

	public function is_keywords_field_with_ajax() {
		if (!empty($this->args['keywords_ajax_search'])) {
			return true;
		}
	}

	public function is_categories_or_Keywords_field() {
		if ($this->is_categories() || $this->is_keywords_field()) {
			return true;
		}
	}
	
	public function is_locations() {
		if (!empty($this->args['show_locations_search']) && directorypress_is_anyone_in_taxonomy(DIRECTORYPRESS_LOCATIONS_TAX)) {
			return true;
		}
	}

	public function is_address() {
		if (!empty($this->args['show_address_search'])) {
			return true;
		}
	}

	public function is_locations_or_address_field() {
		if ($this->is_locations() || $this->is_address()) {
			return true;
		}
	}

	public function is_radius() {
		if (!empty($this->args['show_radius_search'])) {
			return true;
		}
	}

	public function get_keyword_value() {
		return stripslashes(directorypress_get_input_value($_GET, 'what_search', directorypress_get_input_value($this->args, 'what_search')));
	}

	public function is_keyword_field_examples() {
		if (!empty($this->args['keywords_search_examples'])) {
			return true;
		}
	}
	
	public function wrap_keywords_examples($example) {
		$example = trim($example);
		return "<a href=\"javascript:void(0);\">{$example}</a>";
	}

	public function get_keywords_examples() {
		$examples = explode(',', $this->args['keywords_search_examples']);
		$wrapped = array_map(
				array($this, "wrap_keywords_examples"),
				$examples
		);
		return implode(', ', $wrapped);
	}

	public function get_address_value() {
		return stripslashes(directorypress_get_input_value($_GET, 'address', directorypress_get_input_value($this->args, 'address')));
	}

	public function get_radius_value() {
		if (!($radius = directorypress_get_input_value($_GET, 'radius', directorypress_get_input_value($this->args, 'radius')))) {
			$radius = 0;
		} else {
			$radius = directorypress_get_input_value($_GET, 'radius', directorypress_get_input_value($this->args, 'radius'));
		}
		return $radius;
	}
	
	public function get_categories_dropmenu_params($placeholder_category, $placeholder_category_keywords) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$term_id = directorypress_get_search_term_id('category-directorypress', 'categories', directorypress_get_input_value($this->args, 'category'));
			
		$params = array(
				'tax' => DIRECTORYPRESS_CATEGORIES_TAX,
				'field_name' => 'categories',
				'depth' => $this->args['categories_search_depth'],
				'term_id' => $term_id,
				'count' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_category_count_in_search'],
				'uID' => null,
				'exact_terms' => $this->args['exact_categories'],
				'hide_empty' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_hide_empty_categories'],
				'field_icon' => $this->args['category_field_icon'],
				'default_fields_icon_type' => $this->args['default_fields_icon_type'],
				'placeholder' => $placeholder_category,
		);
		if ($this->is_keywords_field() && $this->args['show_keywords_category_combo']) {
			$params['placeholder'] = $placeholder_category_keywords;
			$params['autocomplete_field'] = 'what_search';
			$params['autocomplete_field_value'] = $this->get_keyword_value();
			$params['autocomplete_ajax'] = $this->is_keywords_field_with_ajax();
		}
		
		return $params;
	}

	public function get_locations_dropmenu_params($placeholder_location, $placeholder_locations_address) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$term_id = directorypress_get_search_term_id('location-directorypress', 'location_id', directorypress_get_input_value($this->args, 'location'));

		$params = array(
				'tax' => DIRECTORYPRESS_LOCATIONS_TAX,
				'field_name' => 'location_id',
				'depth' => $this->args['locations_search_depth'],
				'term_id' => $term_id,
				'count' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_location_count_in_search'],
				'uID' => null,
				'exact_terms' => $this->args['exact_locations'],
				'hide_empty' => false,
				'field_icon' => $this->args['location_field_icon'],
				'default_fields_icon_type' => $this->args['default_fields_icon_type'],
				'placeholder' => $placeholder_location,
		);
		if ($this->is_address()) {
			$params['placeholder'] = $placeholder_locations_address;
			$params['autocomplete_field'] = 'address';
			$params['autocomplete_field_value'] = $this->get_address_value();
		}

		return $params;
	}
	
	public function get_search_form_dynamic_css() {
		if ($this->args['search_custom_style']) {
			$id = uniqid();
			global $directorypress_dynamic_styles, $DIRECTORYPRESS_ADIMN_SETTINGS;
			$directorypress_styles = '';
			 /* styles */
			$main_search_border_color = $this->args['main_search_border_color'];
			$main_searchbar_bg_color = $this->args['main_searchbar_bg_color'];
			$search_box_padding_top = $this->args['search_box_padding_top'];
			$search_box_padding_bottom = $this->args['search_box_padding_bottom'];
			$search_box_padding_left = $this->args['search_box_padding_left'];
			$search_box_padding_right = $this->args['search_box_padding_right'];
			
			$input_field_bg = $this->args['input_field_bg'];
			$input_field_border_color = $this->args['input_field_border_color'];
			$input_field_placeholder_color = $this->args['input_field_placeholder_color'];
			$input_field_text_color = $this->args['input_field_text_color'];
			$input_field_label_color = $this->args['input_field_label_color'];
			$input_field_border_width = $this->args['input_field_border_width'];
			$input_field_border_radius = $this->args['input_field_border_radius'];
			
			$search_button_border_radius = $this->args['search_button_border_radius'];
			$search_button_bg = $this->args['search_button_bg'];
			$search_button_bg_hover = $this->args['search_button_bg_hover'];
			$search_button_border_color = $this->args['search_button_border_color'];
			$search_button_border_color_hover = $this->args['search_button_border_color_hover'];
			$search_button_border_width = $this->args['search_button_border_width'];
			$search_button_text_color = $this->args['search_button_text_color'];
			$search_button_text_color_hover = $this->args['search_button_text_color_hover'];
			$search_button_icon = $this->args['search_button_icon'];
						
			$form_id = "#directorypress-search-form-" . $this->form_id;
			$directorypress_styles .='
				'.$form_id.' .directorypress-search-holder{
					padding-top:'.$search_box_padding_top.'px;
					padding-bottom:'.$search_box_padding_bottom.'px;
					padding-left:'.$search_box_padding_left.'px;
					padding-right:'.$search_box_padding_right.'px;
				}
				'.$form_id.'.directorypress-search-form,
				.search-form-style1 .advanced-search-button{
					background:'.$main_searchbar_bg_color.';
					border-color:'.$main_search_border_color.';
				}

				'.$form_id.' .directorypress-search-holder .cz-submit-btn.btn.btn-primary{
					background-color:'.$search_button_bg.' !important;
					border-color:'.$search_button_border_color.'!important;
					border-width:'.$search_button_border_width.'px;
					border-radius: '.$search_button_border_radius.'px;
					color:'.$search_button_text_color.'!important;
				}
				'.$form_id.' .directorypress-search-holder .cz-submit-btn.btn.btn-primary:hover{
					background-color:'.$search_button_bg_hover.'!important;
					border-color:'.$search_button_border_color_hover.'!important;
					color:'.$search_button_text_color_hover.'!important;
				}
				'.$form_id.' .directorypress-search-holder .form-control{
					background-color:'.$input_field_bg.';
					border-color:'.$input_field_border_color.';
					border-width:'.$input_field_border_width.'px;
					border-radius: '.$input_field_border_radius.'px;
					color:'.$input_field_text_color.';
				}

				'.$form_id.' .directorypress-search-holder .form-control:focus{
					border-color:'.$input_field_border_color.';
					border-width:'.$input_field_border_width.'px;
					border-radius: '.$input_field_border_radius.'px;
					color:'.$input_field_text_color.';
				}
				'.$form_id.' .directorypress-search-radius-label{
					color:'.$input_field_label_color.';
				}
				'.$form_id.' .directorypress-search-holder .form-control::-moz-placeholder,
				'.$form_id.' .directorypress-search-holder .form-control::placeholder{
					color:'.$input_field_placeholder_color.' !important;
				}
			
			';
			
			// Hidden styles node for head injection after page load through ajax
			echo '<div id="ajax-'. esc_attr($id) .'" class="directorypress-dynamic-styles">';
			echo '</div>';


			// Export styles to json for faster page load
			$directorypress_dynamic_styles[] = array(
			  'id' => 'ajax-'.$id ,
			  'inject' => $directorypress_styles
			);
		}
	}
	
	public function display_search_button($on_row_search_button = false) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$classes = ($on_row_search_button)? ('directorypress-on-row-button'): ''; 
		$has_icon = (!empty($this->args['search_button_icon']))? $this->args['search_button_icon']: $DIRECTORYPRESS_ADIMN_SETTINGS['search_button_icon'];
		$icon = (!empty($has_icon))? ('<i class="'.$has_icon.'"></i>'): '';
		$hide_button_class = ($this->args['hide_search_button'])? ' directorypress-submit-button-hidden': '';
		
		echo '<div class="directorypress-search-form-button ' . esc_attr($classes) . '">';
			if ($this->args['search_button_type'] == 1 && !empty($icon)) {
				echo '<button type="submit" name="submit" class="btn btn-primary icon-left' . esc_attr($hide_button_class) . '" data-handler-hash="'. esc_attr($this->uid) .'">' . wp_kses_post($icon) . __('Search', 'DIRECTORYPRESS') . '</button>';
			} elseif ($this->args['search_button_type'] == 2  && !empty($icon)) {
				echo '<button type="submit" name="submit" class="btn btn-primary icon-right' . esc_attr($hide_button_class) . '" data-handler-hash="'. esc_attr($this->uid) .'">' . __('Search', 'DIRECTORYPRESS'). wp_kses_post($icon) . '</button>';
			}elseif ($this->args['search_button_type'] == 3) {
				echo '<button type="submit" name="submit" class="btn btn-primary' . esc_attr($hide_button_class) . '" data-handler-hash="'. esc_attr($this->uid) .'">' . __('Search', 'DIRECTORYPRESS') . '</button>';
			}elseif ($this->args['search_button_type'] == 4  && !empty($icon)) {
				echo '<button type="submit" name="submit" class="btn btn-primary' . esc_attr($hide_button_class) . '" data-handler-hash="'. esc_attr($this->uid) .'">' . wp_kses_post($icon) . '</button>';
			}else{
				echo '<button type="submit" name="submit" class="btn btn-primary' . esc_attr($hide_button_class) . '" data-handler-hash="'. esc_attr($this->uid) .'">' . __('Search', 'DIRECTORYPRESS') . '</button>';
			}
		echo '</div>';
	}
	public function display_search_button_header($on_row_search_button = false) {
			$classes = "directorypress-on-row-button";
		
		echo '<div class="directorypress-search-form-button ' . esc_attr($classes) . '">
				<button type="submit" name="submit" class="btn btn-primary ' . (($this->args['hide_search_button']) ? 'directorypress-submit-button-hidden' : '') . '"><i class="fas fa-search-plus"></i></button>
			</div>';
	}
	
	public function display() {
		global $directorypress_object;

		// random ID needed because there may be more than 1 search form on one page
		$this->form_id = directorypress_create_random_value();

		if ($this->directorytypes && ($directory_id = $this->directorytypes[0]) &&  ($directorytype = $directorypress_object->directorytypes->directory_by_id($directory_id))) {
			$search_url = $directorytype->url;
		} else {
			$search_url = ($directorypress_object->directorypress_archive_page_url) ? directorypress_directorytype_url() : home_url('/');
		}
		
		$search_url = apply_filters('directorypress_search_url', $search_url, $this);

		directorypress_display_template('partials/search/form-'. $this->form_layout .'.php',
			array(
				'form_id' => $this->form_id,
				'is_advanced_search_panel' => $this->is_advanced_search_panel,
				'advanced_open' => $this->advanced_open,
				'search_url' => $search_url,
				'args' => $this->args,
				'search_form' => $this
			)
		);
	}
}
?>