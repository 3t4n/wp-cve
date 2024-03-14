<?php

require_once( FMC_PLUGIN_DIR . 'components/search.php' );

class fmcSearch extends fmcSearch_v1 {
	const WIDGET_VERSION = 2;

	function __construct() {
		parent::__construct();

		$this->widget_version = static::WIDGET_VERSION;
	}

	function settings_form( $instance ) {
		if ( ! $this->is_new_version_widget( $instance ) || ! in_array( '_is_gutenberg_new', $instance ) ) {
			return parent::settings_form( $instance );
		}

		global $fmc_plugin_dir;
		$this->instance = $instance;
		$this->admin_view_vars = $this->admin_view_vars();

		return $this->render( $fmc_plugin_dir . "/views/admin/v2/settings.php", $this->admin_view_vars );
	}

	function get_font_with_default( $key, $default = "default" ) {
		$value = $this->widget_settings[$key];
		if ( empty( $value ) || $value == "default" ) {
			return false;
		}

		return $value;
	}

	function jelly( $args, $settings, $type ) {
		if ( ! $this->is_new_version_widget( $settings ) ) {
			return parent::jelly( $args, $settings, $type );
		}

		global $fmc_api;

		extract( $args );

		$this->widget_settings = $settings;

		if ($type == "widget" && empty($settings['title']) && flexmlsConnect::use_default_titles()) {
			$settings['title'] = "IDX Search";
		}

		$rand = mt_rand();

		// presentation variables from settings
		$title = isset($settings['title']) ? trim($settings['title']) : null;
		$my_link = isset($settings['link']) ? ($settings['link']): null;
		$buttontext = (array_key_exists('buttontext', $settings) and !empty($settings['buttontext'])) ? htmlspecialchars(trim($settings['buttontext']), ENT_QUOTES) : "Search";
		$detailed_search = trim($settings['detailed_search']);
		$detailed_search_text = (array_key_exists('detailed_search_text', $settings) and !empty($settings['detailed_search_text'])) ? trim($settings['detailed_search_text']) : "More Search Options" ;
		// destination="local"
		$location_search = trim($settings['location_search']);

		$all_location_fields = array('City', 'StateOrProvince', 'PostalCode', 'CountyOrParish', 'MLSAreaMajor',
			'MLSAreaMinor', 'StreetAddress', 'ListingId', 'Location');

		$location_fields = array();

		foreach ($all_location_fields as $location_field) {
			if(array_key_exists($location_field, $_GET)){
				$location_fields[$location_field] = $_GET[$location_field];
			}
		}

		$user_sorting = trim($settings['user_sorting']);
		$property_type_enabled = (array_key_exists('property_type_enabled', $settings)) ? trim($settings['property_type_enabled']) : "on" ;
		$property_type = isset($settings['property_type'])? trim($settings['property_type']) : null;
		$property_types_selected = explode(",", $property_type);
		$std_fields = isset($settings['std_fields'])? trim($settings['std_fields']) : null;
		$std_fields_selected = explode(",", $std_fields);
		$allow_sold_searching = isset($settings['allow_sold_searching']) ? $settings['allow_sold_searching'] : null;
		$allow_pending_searching = array_key_exists('allow_pending_searching',$settings) ? $settings['allow_pending_searching'] : null;
		// theme="vert_round_dark"
		$orientation = (array_key_exists('orientation', $settings)) ? trim($settings['orientation']) : "horizontal" ;

		$width = ($orientation == "horizontal") ? 760 : 360;
		if( array_key_exists( 'width', $settings ) ){
			if( is_numeric( $settings[ 'width' ] ) ){
				$width = trim($settings['width']) - 40;
			 }
		}

		$border_style = (array_key_exists('border_style', $settings)) ? trim($settings['border_style']) : "squared" ;

		$background_color = fmcSearch::get_setting_color('background_color');
		$title_text_color = fmcSearch::get_setting_color('title_text_color');
		$field_text_color = fmcSearch::get_setting_color('field_text_color');
		$detailed_search_text_color = fmcSearch::get_setting_color('detailed_search_text_color');

		$submit_button_shine = (array_key_exists('submit_button_shine', $settings)) ? trim($settings['submit_button_shine']) : "shine" ;

		$submit_button_background = fmcSearch::get_setting_color('submit_button_background');
		$submit_button_text_color = fmcSearch::get_setting_color('submit_button_text_color');

		$title_font = ($this->get_font_with_default( 'title_font' )) ? $this->get_font_with_default( 'title_font' ) : '';
		$field_font = ($this->get_font_with_default( 'field_font' )) ? $this->get_font_with_default( 'field_font' ) : '';
		$destination = (array_key_exists('destination', $settings)) ? trim($settings['destination']) : "local" ;
		$default_view = (array_key_exists('default_view', $settings)) ? trim($settings['default_view']) : "list";
		$listings_per_page = (array_key_exists('listings_per_page', $settings)) ? trim($settings['listings_per_page']) : "20";
		// API variables
		$api_prop_types = $fmc_api->GetPropertyTypes();
		$api_property_sub_types = $fmc_api->GetPropertySubTypes();
		$api_system_info = $fmc_api->GetSystemInfo();

		$IDXLinks = new \SparkAPI\IDXLinks();
		$api_links = $IDXLinks->get_all_idx_links();

		if ($api_prop_types === false || $api_system_info === false || $api_links === false) {
			return flexmlsConnect::widget_not_available($fmc_api, false, $args, $settings);
		}

		if ($my_link == "default") {
			$my_link = flexmlsConnect::get_default_idx_link();
		}

		$good_link = false;
		foreach ($api_links as $link) {
			if ($link['LinkId'] == $my_link) {
				$good_link = true;
			}
		}

		if (!$good_link) {
			return flexmlsConnect::widget_not_available($fmc_api, false, $args, $settings);
		}

		$search_fields = array();

		$this_target = "";
		if (flexmlsConnect::get_destination_window_pref() == "new") {
			$this_target = " target='_blank'";
		}

		$idx_link_details = flexmlsConnect::get_idx_link_details($my_link);
		$detailed_search_url = flexmlsConnect::make_destination_link($idx_link_details['Uri']);


		// set border radius code
		$border_radius = "";
		if ($border_style == "rounded")
			$border_radius = "border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;";

		$wrapper_class = "flexmls_connect__search_v2";

		if ( 'vertical' == $orientation ) {
			$wrapper_class .= " flexmls_connect__search_v2_vertical ";
		}

		// submit button CSS
		$submit_button_css = "background:{$submit_button_background} !important; color: {$submit_button_text_color} !important;";
		$is_rgba = strpos($submit_button_background,"rgba") > - 1 ? true : false;

		// Submit Return
		$submit_return  = "";

		// only include remote link information if necessary
		if ($destination == "remote") {
			$submit_return .= "<input type='hidden' name='fmc_do' value='fmc_search' />";
			$submit_return .= "<input type='hidden' name='link' class='flexmls_connect__link' value='{$my_link}' />";
			$submit_return .= "<input type='hidden' name='destlink' value='".flexmlsConnect::get_destination_link()."' />";
			$submit_return .= "<input type='hidden' name='destination' value='{$destination}' />";
			$submit_return .= "<input type='hidden' name='query' value='' />";
		} else {
			// include the link if it's a Saved Search - added 1-29-2013 by Brandon Medenwald (WP-137)
			if ($idx_link_details['LinkType'] == "SavedSearch") {
				$submit_return .= "<input type='hidden' name='SavedSearch' class='flexmls_connect__link
					flexmls_connect__search_v2_submit' value='{$idx_link_details['SearchId']}' />";
			}
		}

		if ( ! empty( $listings_per_page ) ) {
			$submit_return .= "<input type='hidden' name='Limit' value='" . esc_attr( $listings_per_page ) . "'>";
		}

		$submit_return .= "<div style='visibility:hidden;' class='query' ></div>";

		$submit_return .= "<div class='flexmls_connect__search_v2_links'>";
		$submit_return .= "<input class='flexmls_connect__search_v2_submit' type='submit' value='{$buttontext}' style='{$submit_button_css}' />";
		if ($detailed_search == "on") {
			$submit_return .= "<a href='{$detailed_search_url}' style='color:{$detailed_search_text_color};'{$this_target}>{$detailed_search_text}</a>";
		}
		$submit_return .= "</div>";



		// Property Types
		$good_prop_types = array();
		foreach ($api_prop_types as $k => $v) {
			if (in_array($k, $property_types_selected)) {
				$good_prop_types[] = $k;
			}
		}

		$user_selected_property_types = $this->requestVariableArray('PropertyType');
		$user_selected_property_sub_types = $this->requestVariableArray('PropertySubType');

		$search_fields[] = "PropertyType";

		// set up prop sub types in a way that will be easy to output in the view
		$property_sub_types = array();
		if ($api_property_sub_types) {
			foreach ($api_property_sub_types as $sub_type) {
				if ($sub_type['Name'] != "Select One") {
					foreach($sub_type['AppliesTo'] as $property_code) {
						if (array_key_exists($property_code, $property_sub_types)) {
							$property_sub_types[$property_code][] = $sub_type;
						} else  {
							$property_sub_types[$property_code] = array($sub_type);
						}
					}
				}
			}
		}


		$portal_slug = flexmlsConnect::get_portal_slug();

		// output html from the template
		ob_start();
			global $fmc_plugin_dir;
			require( $fmc_plugin_dir . "/views/v2/fmcSearch.php" );
			$return = ob_get_contents();
		ob_end_clean();

		return $return;

	}


	function settings_fields_v2() {
		$idx_links = $this->idx_links();

		$possible_desinations = [];
		$possible_desinations = $this->destination_options();

		$listings_per_page_options = [];
		foreach ( $this->listings_per_page_options() as $id => $option ) {
			$listings_per_page_options []= [
				'value' => $id,
				'display_text' => $option
			];
		}

		global $fmc_api;
		$standard_status = new fmcStandardStatus( $fmc_api->GetStandardField("StandardStatus") );
		$allow_sold_searching = $standard_status->allow_sold_searching();

		$settings_fields = [
			'sorting_title' => [
				'type' => 'section-title',
				'text' => 'Sorting',
				'skip_prev_section_close' => true
			],
			'user_sorting' => [
				'label' => 'User Sorting',
				'type' => 'enabler',
			],
			'filter_title' => [
				'type' => 'section-title',
				'text' => 'Filters',
				'description' => 'These choices will be shown to your website visitors in the widget to help them narrow down their search.'
			],
			'location_search' => [
				'label' => 'Location Search',
				'type' => 'enabler',
			],
			'detailed_search' => [
				'label' => 'More Search Options',
				'type' => 'enabler',
				'description' => 'This will add the option for More Search Options beneath the search button and will open a SmartFrame link where website visitors can use more criteria for a search'
			],
			'allow_sold_searching' => [
				'label' => 'Allow Sold Searching',
				'type' => 'enabler',
				'disabled' => ( ! $allow_sold_searching ),
			],
			'allow_pending_searching' => [
				'label' => 'Allow Pending Searching',
				'type' => 'enabler',
				'disabled' => ( ! $allow_sold_searching ),
			],
			'property_type_enabled' => [
				'label' => 'Property Type',
				'type' => 'enabler'
			],
			'property_type' => [
				'label' => 'Select Property Types to Show',
				'type' => 'list',
				'collection' => $this->get_view_property_types(),
				'selected' => $this->get_selected_property_types(),
				'field_grouping' => 'property_type_enabled'
			],
			'std_fields' => [
				'label' => 'Other Fields to Show',
				'type' => 'list',
				'collection' => $this->get_available_fields(),
				'selected' => $this->get_selected_std_fields(),
			],
			'behavior_title' => [
				'type' => 'section-title',
				'text' => 'Behavior'
			],
			'link' => [
				'label' => 'IDX Link',
				'type' => 'select',
				'collection' => $this->idx_links(),
				'description' => 'This is the IDX Link generated from within the Flexmls IDX Manager that you wish to use when this search is executed. We recommend choosing a link with broad criteria unless you would like to limit the search results for your website visitors.',
			],
			'destination' => [
				'label' => 'Send users to',
				'type' => 'select',
				'collection' => $possible_desinations,
			],
			'layout_title' => [
				'type' => 'section-title',
				'text' => 'Layout and Style'
			],
			'listings_per_page' => [
				'label' => 'Listings per page',
				'type' => 'select',
				'collection' => $listings_per_page_options,
				'default' => 20
			],
			'title' => [
				'label' => 'Title',
				'type' => 'text',
				'description' => 'This will appear at the top of the search widget. If you would prefer not to have a title, you can leave this blank.'
			],
			'buttontext' => [
				'label' => 'Submit Button Text:',
				'type' => 'text',
				'description' => '(ex. "Search for Homes")'
			],
			'default_view' => [
				'label' => 'Default View',
				'type' => 'select',
				'collection' => [
					[ 'value' => 'list', 'display_text' => 'List view'],
					[ 'value' => 'map', 'display_text' => 'Map view' ]
				],
				'description' => 'What view of the listing results would you like search results to default to for your visitors?'
			],
			'orientation' => [
				'label' => 'Orientation',
				'type' => 'select',
				'collection' => [
					[ 'value' => 'horizontal', 'display_text' => 'Horizontal' ],
					[ 'value' => 'vertical', 'display_text' => 'Vertical' ],
				]
			],
			'width' => [
				'label' => 'Widget Width',
				'type' => 'text',
				'input_width' => 5,
				'after_input' => ' px'
			],
			'title_font' => [
				'label' => 'Title Font',
				'type' => 'font',
				'collection' => fmcWidget::available_fonts(),
				'default' => 'default'
			],
			'title_text_color' => [
				'label' => 'Title Text Color',
				'type' => 'color',
				'default' => '333333'
			],
			'field_font' => [
				'label' => 'Field Font',
				'type' => 'font',
				'collection' => fmcWidget::available_fonts(),
				'default' => 'default'
			],
			'field_text_color' => [
				'label' => 'Field Text Color',
				'type' => 'color',
				'default' => '333333'
			],
			'submit_button_background' => [
				'label' => 'Submit Button Background',
				'type' => 'color',
				'default' => '0577d9'
			],
			'submit_button_text_color' => [
				'label' => 'Submit Button Text Color',
				'type' => 'color',
				'default' => 'ffffff'
			],
			'detailed_search_text_color' => [
				'label' => 'Field Text Color',
				'type' => 'color',
				'default' => '333333'
			],
			'widget_version' => [
				'type' => 'hidden',
				'default' => static::WIDGET_VERSION
			],
		];

		return $settings_fields;

	}

	protected function get_view_property_types() {
		global $fmc_api;
		$output = array();
		$types = $fmc_api->GetPropertyTypes();
		if ( is_array($types) ) {
			foreach ($types as $id => $name) {
				$output []= [
					'value' => $id,
					'display_text' => flexmlsConnect::nice_property_type_label($id)
				];
			}
		}
		return $output;
	}


	protected function get_selected_property_types() {
		$output = array();
		$property_type = $this->get_field_value("property_type");
		if ($property_type) {
			$ids = explode(",", $property_type);
			foreach ($ids as $id) {
				$output []= [
					'value' => $id,
					'display_text' => flexmlsConnect::nice_property_type_label($id)
				];
			}
			return $output;
		} else {
			return false;
		}
	}

	protected function get_selected_std_fields() {
		$output = array();
		$std_fields = $this->get_field_value("std_fields");
		if ($std_fields) {
			$ids = explode(",", $std_fields);

			foreach ($ids as $id) {
				$output []= [
					'value' => $id,
					'display_text' => $this->available_field_name_for($id)
				];
			}
			return $output;
		} else {
			return false;
		}
	}

	function admin_view_vars() {
		if ( ! $this->is_new_version_widget() ) {
			return parent::admin_view_vars();
		}

		global $fmc_api;
		$standard_status = new fmcStandardStatus($fmc_api->GetStandardField("StandardStatus"));

		$vars = array();
		$vars["settings_fields"] = $this->settings_fields_v2();
		$vars["idx_links"] = flexmlsConnect::get_all_idx_links();
		$vars["idx_links_default"] = $this->options->default_link();
		$vars["property_types"] = $this->get_view_property_types();
		$vars["selected_property_types"] = $this->get_selected_property_types();
		$vars["on_off_options"] = $this->on_off_options();
		$vars['destination_options'] = $this->destination_options();
		$vars['available_fields'] = $this->get_available_fields();
		$vars['selected_std_fields'] = $this->get_selected_std_fields();
		$vars['theme_options'] = $this->theme_options();
		$vars["orientation_options"] = $this->orientation_options();
		$vars["default_view_options"] = $this->default_view_options();
		$vars["listings_per_page_options"] = $this->listings_per_page_options();
		$vars["border_style_options"] = $this->border_style_options();
		$vars["submit_button_options"] = $this->submit_button_options();
		$vars["mls_allows_sold_searching"] = $standard_status->allow_sold_searching();
		$vars["allow_sold_searching_default"] = $this->allow_sold_searching_default();
		$vars["class_name"] = "fmcSearch";

		return $vars;
	}
}
