<?php

require_once( FMC_PLUGIN_DIR . 'components/search-results.php' );

class fmcSearchResults extends fmcSearchResults_v1 {
	const WIDGET_VERSION = 2;

	protected $search_criteria;
	protected $order_by;
	protected $default_page_size;
	protected $page_size;
	protected $current_page;
	protected $total_pages;
	protected $search_data;
	protected $total_rows;
	protected $params;

	function __construct() {
		parent::__construct();

		$this->widget_version = static::WIDGET_VERSION;
	}

	function load_search_results( $input_source, $input_data ) {
		if ( ! is_null( $this->search_data ) ) {
			return; // Only load search data once
		}

		global $fmc_api;

		list($params, $cleaned_raw_criteria, $context) = flexmlsSearchUtil::parse_search_parameters_into_api_request( $input_source, $input_data );

		$this->search_criteria = $cleaned_raw_criteria;

		$this->order_by = $this->search_criteria['OrderBy'];

		//This unset was added to pull all information
		unset($params['_select']);
		//Set page size to cookie value

		if ( ! empty( $this->search_criteria['Limit'] ) ) {
			$this->default_page_size = intval( $this->search_criteria['Limit'] );
		}

		$this->page_size= empty($_COOKIE['flexmlswordpressplugin']) ? $this->default_page_size : intval($_COOKIE['flexmlswordpressplugin']) ;

		if ($this->page_size > 0 and $this->page_size <= 25){
			//Good, don't need to to anything
		}
		elseif ($this->page_size>25){
			$this->page_size=25;
		}
		else {
			$this->page_size=10;
		}

		$params['_limit'] = $this->page_size;
		if ($context == "listings") {
			$results = $fmc_api->GetMyListings($params);
		}
		elseif ($context == "office") {
			$results = $fmc_api->GetOfficeListings($params);
		}
		elseif ($context == "company") {
			$results = $fmc_api->GetCompanyListings($params);
		}
		else {
			$cache_time = (strpos($params['_filter'],'ListingCart')!==false) ? 0 : '10m';
			$results = $fmc_api->GetListings($params, $cache_time);
		}

		$this->search_data = $results;
		$this->total_pages =  $fmc_api->total_pages;
		$this->current_page =  $fmc_api->current_page;
		$this->total_rows =  $fmc_api->last_count;
		$this->page_size =  $fmc_api->page_size;
		$this->params =  $params;
	}

	function settings_form( $instance ) {
		if ( ! $this->is_new_version_widget( $instance ) ) {
			return parent::settings_form( $instance );
		}

		global $fmc_plugin_dir;
		$this->instance = $instance;
		$this->admin_view_vars = $this->admin_view_vars();

		return $this->render( $fmc_plugin_dir . "/views/admin/v2/settings.php", $this->admin_view_vars );
	}

	function render_map($search_results = "") {
		$options = get_option( 'fmc_settings' );

		if ( isset ( $options['google_maps_api_key'] ) && $options['google_maps_api_key'] ) :
			/**
			 * Grab the proper data for the Google Map and render it.
			 */
				$markers      = array();
				$result_count = 0;
				global $wp;
				$current_url = home_url( add_query_arg( $_GET, $wp->request ) );
				if($search_results){ 
					$this->search_data = $search_results;
				}
				foreach ( $this->search_data as $record ) {
					$result_count ++;
					$fields = $record['StandardFields'];

					if ( ! flexmlsConnect::is_not_blank_or_restricted( $fields['Latitude'] ) || ! flexmlsConnect::is_not_blank_or_restricted( $fields['Longitude'] ) ) {
							continue;
					}

					$listing_address          = flexmlsConnect::format_listing_street_address( $record );
					$first_line_address       = htmlspecialchars( $listing_address[0] );
					$second_line_address      = htmlspecialchars( $listing_address[1] );
					$link_to_details_criteria = $this->search_criteria;

					$list_price = flexmlsConnect::is_not_blank_or_restricted( $fields['ListPrice'] ) ? '$' . flexmlsConnect::gentle_price_rounding( $fields['ListPrice'] ) : '';

					$this_result_overall_index = ( $this->page_size * ( $this->current_page - 1 ) ) + $result_count;

					// figure out if there's a previous listing
					$link_to_details_criteria['p'] = ( $this_result_overall_index != 1 ) ? 'y' : 'n';

					// figure out if there's a next listing possible
					$link_to_details_criteria['n'] = ( $this_result_overall_index < $this->total_rows ) ? 'y' : 'n';

					$link_to_details = flexmlsConnect::make_nice_address_url( $record, $link_to_details_criteria );
					$link_to_details = add_query_arg( 'search_referral_url', urlencode( $current_url ), $link_to_details );

					// Image
					$image_thumb = '';
					$image_alt   = '';
					if ( count( $fields['Photos'] ) >= 1 ) {
						//Find primary photo and assign it to thumbnail
						foreach ( $fields['Photos'] as $key => $photo ) {
							if ( true !== $photo['Primary'] ) {
								continue;
							}
							$image_thumb = $photo['Uri300'];
							$image_alt   = $photo['Name'];
						}
					}

					$markers[] = array(
						'latitude'  => esc_html( $fields['Latitude'] ),
						'longitude' => esc_html( $fields['Longitude'] ),
						'listprice' => esc_html( $list_price ),
						'rawprice'  => esc_html( $fields['ListPrice'] ),
						'address1'  => esc_html( $first_line_address ),
						'address2'  => esc_html( $second_line_address ),
						'link'      => esc_url( $link_to_details ),
						'image'     => esc_url( $image_thumb ),
						'imagealt'  => esc_html( $image_alt ),
						'bedrooms'  => esc_html( $fields['BedsTotal'] ),
						'bathrooms' => esc_html( $fields['BathsTotal'] ),
					);
				}

				$map = new flexmlsListingMap( $markers );
				$map->render_map();
		endif;
	}

	function jelly( $args, $settings, $type ) {
		if ( ! $this->is_new_version_widget( $settings ) ) {
			return parent::jelly( $args, $settings, $type );
		}

		global $fmc_api;
		global $fmc_plugin_url;
		$options = get_option( 'fmc_settings' );


		// sets up $before_title, $after_title, $before_widget, $after_widget
		extract($args);

		if ($type == "widget" && empty($settings['title']) && flexmlsConnect::use_default_titles()) {
			$settings['title'] = "Listings";
		}

		$title              = isset($settings['title']) ? ($settings['title']) : '';

		$pure_conditions = $this->get_pure_conditions( $settings );
		$this->load_search_results( 'shortcode', $pure_conditions );

		$search_criteria = $this->search_criteria;
		$params = $this->params;

		$custom_page = new flexmlsConnectPageSearchResults($fmc_api);
		$is_widget = true;
		ob_start();
			global $fmc_plugin_dir;
			require( $fmc_plugin_dir . "/views/v2/fmcSearchResults.php" );
			$custom_page->render_template_styles();
			$return = ob_get_contents();
		ob_end_clean();
		return $return;
	}

	function get_pure_conditions( $settings ) {
		global $fmc_api;

		$source             = isset($settings['source']) ? trim($settings['source']) : 'location';
		$display            = isset($settings['display']) ? trim($settings['display']) : '';
		$days               = isset($settings['days']) ? trim($settings['days']) :  '';
		$property_type      = isset($settings['property_type']) ? trim($settings['property_type']): '';
		$property_sub_type  = isset($settings['property_sub_type']) ? trim($settings['property_sub_type']): '';
		$link               = isset($settings['link']) ? trim($settings['link']) : '';
		$sort               = isset($settings['sort']) ? trim($settings['sort']) : '';
		$listings_per_page  = isset($settings['listings_per_page']) ? trim($settings['listings_per_page']) : '20';
		$agent              = isset($settings['agent']) ? trim($settings['agent']) : '';
		$status             = isset($settings['status']) ? trim($settings['status']) : '';

		$locations = '';
		if ( isset($settings['location_fields']) ) {
			$locations = urldecode(html_entity_decode( flexmlsConnect::clean_comma_list( stripslashes( $settings['location_fields'] )  ) ));
		}

		if ($link == "default") {
			$link = flexmlsConnect::get_default_idx_link();
		}

		$source = (empty($source)) ? "my" : $source;

		$pure_conditions = array();

		if (isset($settings['days'])){
			$days = $settings['days'];
		}
		elseif ($display == "open_houses"){
			//For backward compatibility. Set # of days for open house default to 10
			$days = 10;
		}
		else{
			$days = 1;
			if (date("l") == "Monday")
				$days = 3;
		}

		$flexmls_temp_date = date_default_timezone_get();
		date_default_timezone_set('America/Chicago');
		$specific_time = date("Y-m-d\TH:i:s.u",strtotime("-".$days." days"));
		date_default_timezone_set($flexmls_temp_date);
		if ($display == "new") {
			$pure_conditions["OriginalOnMarketTimestamp"] = $specific_time;
		}
		elseif ($display == "open_houses") {
			$pure_conditions['OpenHouses'] = $days;
		}
		elseif ($display == "price_changes") {
			$pure_conditions["PriceChangeTimestamp"] = $specific_time;
		}
		elseif ($display == "recent_sales") {
			$pure_conditions["StatusChangeTimestamp"] = $specific_time;
		}

		if ($sort == "recently_changed") {
			$pure_conditions['OrderBy'] = "-ModificationTimestamp"; // special tag caught later
		}
		elseif ($sort == "price_low_high") {
			$pure_conditions['OrderBy'] = "ListPrice";
		}
		elseif ($sort == "price_high_low") {
			$pure_conditions['OrderBy'] = "-ListPrice";
		}
	 elseif ($sort == "open_house"){
			$pure_conditions['OrderBy'] = "+OpenHouses";
		}
		elseif ($sort == "sqft_low_high") {
			$pure_conditions['OrderBy'] = "+BuildingAreaTotal";
		}
		elseif ($sort == "sqft_high_low") {
			$pure_conditions['OrderBy'] = "-BuildingAreaTotal";
		}
		elseif ($sort == "year_built_high_low") {
			$pure_conditions['OrderBy'] = "-YearBuilt";
		}
		elseif ($sort == "year_built_low_high") {
			$pure_conditions['OrderBy'] = "+YearBuilt";
		}

		$apply_property_type = ($source == 'location') ? true : false;

		if ($source == 'agent') {
			$pure_conditions['ListAgentId'] = $agent;
		}

		// parse location search settings
		$locations = flexmlsConnect::parse_location_search_string($locations);

		foreach ($locations as $loc) {
			if(array_key_exists($loc['f'], $pure_conditions)) {
				$pure_conditions[$loc['f']] .=  ',' . $loc['v'];
			} else {
				$pure_conditions[$loc['f']] = $loc['v'];
			}
		}

		if ($apply_property_type and !empty($property_type)) {
			$pure_conditions['PropertyType'] = $property_type;
			$pure_conditions['PropertySubType'] = $property_sub_type;
		}

		if ($link) {
			$link_details = $fmc_api->GetIDXLinkFromTinyId($link);
			if ($link_details['LinkType'] == "SavedSearch") {
					$pure_conditions['SavedSearch'] = $link_details['SearchId'];
				}
		}

		if ($source == "my") {
			// make a simple request to /my/listings with no _filter's
			$pure_conditions['My'] = 'listings';
		}
		elseif ($source == "office") {
			$pure_conditions['My'] = 'office';
		}
		elseif ($source == "company") {
			$pure_conditions['My'] = 'company';
		}

		if ($status) {
			$pure_conditions["StandardStatus"] = $status;
		}
		if(isset($settings['default_view']))
				$pure_conditions['default_view'] = $settings['default_view'];

		return $pure_conditions;
	}

	function settings_fields_v2() {
		global $fmc_api;

		$property_type_options = $this->get_view_property_types();
		array_unshift( $property_type_options, ['value' => '', 'display_text' => 'All' ] );

		$property_sub_types_from_api = $fmc_api->GetPropertySubTypes();

		if ( ! is_array( $property_sub_types_from_api ) ) {
			$property_sub_types_from_api = [];
		}

		$type_to_subtype_map = [];

		// First, we build an array mapping type => subtypes
		foreach ( $property_type_options as $property_type ) {
			$code = $property_type['value'];
			foreach ( $property_sub_types_from_api as $sub_type_from_api ) {
				if ( in_array( $code, $sub_type_from_api['AppliesTo'] ) ) {
					if ( empty( $type_to_subtype_map[$code] ) ) {
						$type_to_subtype_map[$code] = [ [
							'display_text' => "All Sub Types",
							'value' => ''
						] ];
					}

					$type_to_subtype_map[$code][] = [
						'display_text' => $sub_type_from_api['Name'],
						'value' => $sub_type_from_api['Value']
					];
				}
			}
		}

		// Next, we build the array used for the toggled_inputs field
		$toggled_subtype_inputs = [];
		foreach ( $type_to_subtype_map as $code => $subtype_options ) {
			if ( ! empty( $subtype_options ) ) {
				$toggled_subtype_inputs []= [
					'type' => 'select',
					'parent_input_value' => $code,
					'collection' => $subtype_options
				];
			}
		}

		$saved_search_options = $this->idx_links();
		array_unshift( $saved_search_options, ['value' => '', 'display_text' => "(None)"] );

		$settings_fields = [
			'general_title' => [
				'type' => 'section-title',
				'text' => 'General',
				'skip_prev_section_close' => true
			],
			'title' => [
				'label' => 'Title',
				'type' => 'text',
				'description' => 'Will appear above the search results on the page'
			],
			'default_view' => [
				'label' => 'Default View',
				'type' => 'select',
				'collection' => $this->view_options(),
			],
			'sort' => [
				'label' => 'Sort By',
				'type' => 'select',
				'collection' => $this->sort_by_options(),
			],
			'preset_filters_title' => [
				'type' => 'section-title',
				'text' => 'Preset Filters',
				'description' => 'Use only if you want to create a summary of listings that meet a certain criteria or otherwise want to limit the results displayed to your visitors'
			],
			'link' => [
				'type' => 'select',
				'label' => 'Saved Search',
				'description' => 'Use any Saved Search from Flexmls to apply to this search',
				'collection' => $saved_search_options
			],
			'source' => [
				'type' => 'select',
				'label' => 'Filter By',
				'collection' => $this->source_options()
			],
			'agent' => [
				'type' => 'toggled_inputs',
				'label' => 'Agent',
				'parent_input_name' => 'source',
				'inputs' => [
					[
						'type' => 'select',
						'parent_input_value' => 'agent',
						'collection' => $this->agent_options()
					]
				]
			],
			'location_fields' => [
				'type' => 'location',
				'label' => 'Location',
			],
			'property_type' => [
				'type' => 'select',
				'label' => 'Property Type',
				'collection' => $property_type_options
			],
			'property_sub_type' => [
				'type' => 'toggled_inputs',
				'label' => 'Property Sub Type',
				'parent_input_name' => 'property_type',
				'inputs' => $toggled_subtype_inputs
			],
			'display' => [
				'type' => 'select',
				'label' => 'Display',
				'collection' => [
					[ 'value' => "all", 'display_text' => "All Listings"],
					[ 'value' => "new", 'display_text' => "New Listings"],
					[ 'value' => "open_houses", 'display_text' => "Open Houses"],
					[ 'value' => "price_changes", 'display_text' => "Recent Price Changes"],
					[ 'value' => "recent_sales", 'display_text' => "Recent Sales"]
				]
			],
			'days' => [
				'type' => 'toggled_inputs',
				'label' => 'Number of days',
				'parent_input_name' => 'display',
				'inputs' => [
					[
						'type' => 'select',
						'parent_input_value' => [ 'new', 'open_houses', 'price_changes', 'recent_sales' ],
						'collection' => [
							[ 'value' => null, 'display_text' => "1 (3 on Monday)" ],
							[ 'value' => 1, 'display_text' => 1 ],
							[ 'value' => 2, 'display_text' => 2 ],
							[ 'value' => 3, 'display_text' => 3 ],
							[ 'value' => 4, 'display_text' => 4 ],
							[ 'value' => 5, 'display_text' => 5 ],
							[ 'value' => 6, 'display_text' => 6 ],
							[ 'value' => 7, 'display_text' => 7 ],
							[ 'value' => 8, 'display_text' => 8 ],
							[ 'value' => 9, 'display_text' => 9 ],
							[ 'value' => 10, 'display_text' => 10 ],
							[ 'value' => 11, 'display_text' => 11 ],
							[ 'value' => 12, 'display_text' => 12 ],
							[ 'value' => 13, 'display_text' => 13 ],
							[ 'value' => 14, 'display_text' => 14 ],
							[ 'value' => 15, 'display_text' => 15 ]
						]
					]
				]
			],
			'widget_version' => [
				'type' => 'hidden',
				'default' => static::WIDGET_VERSION
			],
		];

		return $settings_fields;

	}

	protected function view_options() {
		return [
			[ 'value' => 'list', 'display_text' => __( 'List', 'fmcdomain' ) ],
			[ 'value' => 'map', 'display_text' => __( 'Map', 'fmcdomain' ) ]
		];
	}

	protected function sort_by_options() {
		return [
			[ 'value' => "recently_changed", 'display_text' => "Recently changed first" ],
			[ 'value' => "price_low_high", 'display_text' => "Price, low to high" ],
			[ 'value' => "price_high_low", 'display_text' => "Price, high to low" ],
			[ 'value' => "year_built_low_high", 'display_text' => "Year Built, low to high" ],
			[ 'value' => "year_built_high_low", 'display_text' => "Year Built, high to low" ],
			[ 'value' => "sqft_low_high", 'display_text' => "SqFt, low to high" ],
			[ 'value' => "sqft_high_low", 'display_text' => "SqFt, high to low" ],
			[ 'value' => "open_house", 'display_text' => "Open House" ]
		];
	}

	function admin_view_vars( $integration = false ) {
		if ( ! $this->is_new_version_widget() || $integration ) {
			return parent::admin_view_vars();
		}

		global $fmc_api;
		$standard_status = new fmcStandardStatus($fmc_api->GetStandardField("StandardStatus"));

		$vars = array();
		$vars["settings_fields"] = $this->settings_fields_v2();
		$vars["class_name"] = "fmcSearchResults";
		$vars["portal_slug"] = \flexmlsConnect::get_portal_slug();

		return $vars;
	}

	static function compliance_label( $record ) {
		$compList = flexmlsConnect::mls_required_fields_and_values( "Summary", $record );
		foreach ( $compList as $reqs ) :
			if ( flexmlsConnect::is_not_blank_or_restricted($reqs[1]) ) :
				if ( $reqs[0]=='LOGO' ) :
					if ( $reqs[1]=='IDX' ) :
						?>
							<span class="flexmls-idx-compliance-label">IDX</span>
						<?php
					else:
						?>
							<img class="flexmls-idx-compliance-badge" alt="IDX" src="<?php echo esc_attr( $reqs[1] ); ?>">
						<?php
					endif;
				endif;
			endif;
		endforeach;
	}

	function pagination($current_page, $total_pages) {

		$jump_after_first = false;
		$jump_before_last = false;

		$tolerance = 5;

		$return = " <div class='flexmls_connect__sr_pagination'>";

		if ($current_page != 1) {
			$return .= "    <a href='". $this->make_pagination_link($current_page - 1) ."'>Previous</a>";
		}

		if ( ($current_page - $tolerance - 1) > 1 ) {
			$jump_after_first = true;
		}

		if ( $total_pages > ($current_page + $tolerance + 1) ) {
			$jump_before_last = true;
		}


		for ($i = 1; $i <= $total_pages; $i++) {

			if ($i == $total_pages and $jump_before_last) {
				$return .= "     ... ";
			}

			$is_current = ($i == $current_page) ? true : false;
			if ($i != 1 and $i != $total_pages) {
				if ( $i < ($current_page - $tolerance) or $i > ($current_page + $tolerance) ) {
					continue;
				}
			}

			if ($is_current) {
				$return .= "    <span>{$i}</span> ";
			}
			else {
				$return .= "    <a href='". $this->make_pagination_link($i) ."'>{$i}</a> ";
			}

			if ($i == 1 and $jump_after_first) {
				$return .= "     ... ";
			}

		}

		if ($current_page != $total_pages) {
			$return .= "     <a href='". $this->make_pagination_link($current_page + 1) ."'>Next</a>";
		}
		$return .= "  </div><!-- pagination -->";

		return $return;

	}

	function make_pagination_link( $page ) {
		global $wp;
		$current_url = home_url( add_query_arg( array( $_GET ), $wp->request ) );
		$current_url = str_replace("\\", "", $current_url);
		$paginated_url = add_query_arg( 'pg', $page, $current_url );

		return $paginated_url;
	}

	function source_options() {
		global $fmc_api;
		$source_options = [];
		$my_company_id = flexmlsConnect::get_company_id();

		if ( ! $fmc_api->HasBasicRole() ) {
			$source_options []= [
				'value' => 'location',
				'display_text' => 'Location'
			];
    }

		if ( flexmlsConnect::is_agent() ) {
			$source_options []= [
				'value' => 'my',
				'display_text' => 'My Listings'
			];
		}

		if ( flexmlsConnect::is_agent() || flexmlsConnect::is_office() ) {
			$source_options []= [
				'value' => 'office',
				'display_text' => "My Office's Listings"
			];

			if ( ! empty( $my_company_id ) ) {
				$source_options []= [
					'value' => 'company',
					'display_text' => "My Company's Listings"
				];
			}
		}

		if ( flexmlsConnect::is_office() ) {
			$source_options []= [
				'value' => 'agent',
				'display_text' => "Specific agent"
			];
		}

		if ( flexmlsConnect::is_company() ) {
			$source_options []= [
				'value' => 'company',
				'display_text' => "My Company's Listings"
			];
		}

		return $source_options;
	}

	function agent_options() {
		global $fmc_api;
		$agent_options = [];
		$api_my_account = $fmc_api->GetMyAccount();
		$office_roster = $fmc_api->GetAccountsByOffice( $api_my_account['OfficeId'] );

		if ( ! empty( $office_roster ) ) {
			foreach ( $office_roster as $agent ) {
				$agent_options []= [
					'value' => $agent['Id'],
					'display_text' => $agent['Name']
				];
			}
		}

		return $agent_options;
	}

	public static function main_photo_from_collection( $photos ) {
		global $fmc_plugin_url;
		$count_photos = count( $photos );

		foreach ( $photos as $photo ) {
			if ( $photo['Primary'] === TRUE ) {
				return [
					'Uri300' => $photo['Uri300'],
					'Uri640' => $photo['Uri640'],
					'UriLarge' => $photo['UriLarge'],
					'caption' => htmlspecialchars( $photo['Caption'], ENT_QUOTES )
				];
			}
		}

		return [
			'Uri300' => "{$fmc_plugin_url}/assets/images/nophoto.gif",
			'Uri640' => "{$fmc_plugin_url}/assets/images/nophoto.gif",
			'UriLarge' => "{$fmc_plugin_url}/assets/images/nophoto.gif",
			'caption' => ''
		];
	}
}
