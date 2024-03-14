<?php

class flexmlsConnectPageSearchResults extends flexmlsConnectPageCore {

	protected $search_criteria;
	protected $field_value_count;
	protected $search_data;
	protected $standard_fields;
	protected $total_pages;
	protected $current_page;
	protected $total_rows;
	public $api;
	protected $page_size;
	protected $type;
	protected $order_by;
	public $title;
  public $default_page_size;

	function __construct( $api, $type = 'fmc_tag' ){
		parent::__construct($api);
		$this->type = $type;

		add_filter( 'wpseo_canonical', array( $this, 'wpseo_canonical' ) );

	}

	function wp_head(){

		// WP-716: Add prerender next and previous tags for better performance
		if( 1 < $this->total_pages ){
				$previous_page = max( 1, $this->current_page - 1 );
				$next_page = min( $this->total_pages, $this->current_page + 1 );

				if( $previous_page != $this->current_page ){
						echo '<link rel="prerender" href="' . $this->make_pagination_link($previous_page) . '">';
				}

				if( $this->current_page != $this->total_pages ){
						echo '<link rel="prerender" href="' . $this->make_pagination_link($next_page) . '">';
				}
		}
		
		
    		if ( $this->uses_v2_template() ) {
      			$this->render_template_styles();
    		   }

	}

	public function pre_tasks($tag) {
		global $fmc_special_page_caught;
		global $fmc_api;
		global $fmc_plugin_url;

		// WP-716: Add preload tags
		add_action( 'wp_head', array( $this, 'wp_head' ) );

		list($params, $cleaned_raw_criteria, $context) = $this->parse_search_parameters_into_api_request();

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
			$results = $this->api->GetMyListings($params);
		}
		elseif ($context == "office") {
			$results = $this->api->GetOfficeListings($params);
		}
		elseif ($context == "company") {
			$results = $this->api->GetCompanyListings($params);
		}
		else {
			$cache_time = (strpos($params['_filter'],'ListingCart')!==false) ? 0 : '10m';
			$results = $this->api->GetListings($params, $cache_time);
		}

		$this->title = !empty($this->title) ? $this->title : "";
		$this->search_data = $results;
		$this->total_pages =  $this->api->total_pages;
		$this->current_page =  $this->api->current_page;
		$this->total_rows =  $this->api->last_count;
		$this->page_size =  $this->api->page_size;
		$fmc_special_page_caught['type'] = "search-results";
		$fmc_special_page_caught['page-title'] = "Property Search";
		$fmc_special_page_caught['post-title'] = "Property Search";
		$fmc_special_page_caught['page-url'] = flexmlsConnect::make_nice_tag_url('search') .'?'. $_SERVER['QUERY_STRING'];

	}

	public static function save_user_search() {
		$api = new flexmlsConnectPortalUser(null, null);
		$result = $api->CreateSavedSearch( json_encode( [ "D" => [
			"Name" => stripslashes( $_POST['name'] ),
			 "Filter" => stripslashes( $_POST['filter'] )
		] ] ) );

		self::purge_saved_searches_cache();

		echo json_encode( array(
			'result' => $result,
			'saved_search_url' => flexmlsConnect::make_nice_tag_url( 'search', array( 'SavedSearch' => $result[0][ 'Id' ] )
		) ) );

		wp_die();
	}

	public static function purge_saved_searches_cache() {
		$transient_name = sha1( build_query( array( '_select' => 'Name', 'GET' => 'savedsearches' ) ) );
		delete_transient( 'flexmls_query_' . $transient_name );
	}


	function generate_page($from_shortcode = false) {
		global $fmc_api;
		global $fmc_api_portal;
		global $fmc_special_page_caught;
		global $fmc_plugin_url;
		global $fmc_search_results_loaded;

		if ($this->type == 'fmc_vow_tag' && !$fmc_api_portal->is_logged_in()){
			return "Sorry, but you must <a href={$fmc_api_portal->get_portal_page()}>log in</a> to see this page.<br />";
		}
		if ($fmc_search_results_loaded and !flexmlsConnect::allowMultipleLists()) {
			return '<!-- flexmls-idx blocked duplicate search results widget on page -->';
		}
		$fmc_search_results_loaded = true;

		$options = get_option( 'fmc_settings' );

		if ( $this->uses_v2_template() ) {
			global $fmc_plugin_dir;
			ob_start();

			add_filter( 'flexmls_searchable_fields', [ 'flexmlsSearchUtil', 'remove_saved_search_from_searchable_fields' ] );

			list($params, $cleaned_raw_criteria, $context) = $this->parse_search_parameters_into_api_request();

			remove_filter( 'flexmls_searchable_fields', [ 'flexmlsSearchUtil', 'remove_saved_search_from_searchable_fields' ] );

			$search_criteria = $this->search_criteria;

			require( $fmc_plugin_dir . "/views/v2/fmcSearchResults.php" );
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}

		ob_start();
		flexmlsPortalPopup::popup_portal('search_page');

		$primary_details = array_merge(array('MlsStatus' =>'Status'), $options['search_results_fields']);

		$exclude_property_type = false;
		$exclude_county = false;
		$exclude_area = false;


    echo ( $this->title ) ? "<h1>$this->title</h1>" : "";

		if ( array_key_exists('PropertyType', $this->field_value_count) && $this->field_value_count['PropertyType'] == 1) {
			$exclude_property_type = true;
		}

		if ( array_key_exists('MLSAreaMajor', $this->field_value_count) && $this->field_value_count['MLSAreaMajor'] == 1) {
			$exclude_area = true;
		}

		if ( array_key_exists('CountyOrParish', $this->field_value_count) && $this->field_value_count['CountyOrParish'] == 1) {
			$exclude_county = true;
		}

		if(isset($this->input_data['default_view']) && $this->input_data['default_view'] == "map")
				$_GET['view'] = "map";
	$has_map = isset( $_GET['view'] ) && 'map' == $_GET['view'] ? ' has-map' : '';
		?>

		<div class='flexmls_connect__page_content<?php echo esc_attr( $has_map ); ?>'>

		<?php
		/**
		 * Map/List View Toggle
		 */

		if ( isset( $options['google_maps_api_key'] ) && $options['google_maps_api_key'] ) :
			if ( isset( $_GET['view'] ) && 'map' == $_GET['view'] ) {
				$map_class = ' active';
				$list_class = '';
			} else {
				$map_class = '';
				$list_class = ' active';
			}
			$link = flexmlsConnect::make_nice_tag_url( 'search', $this->search_criteria );
			?>
			<div class="flexmls_toggle-view">
			<a href="<?php echo esc_url( $link ); ?>" alt="Toggle List View" class="list-view<?php echo esc_attr( $list_class ); ?>">List View</a>
				<a href="<?php echo esc_url( add_query_arg( 'view', 'map', $link ) ); ?>" alt="Toggle Map View" class="map-view<?php echo esc_attr( $map_class ); ?>">Map View</a>
			</div>
		<?php endif; ?>

			<div class='flexmls_connect__sr_matches'>
				<span class='flexmls_connect__sr_matches_count'>
					<?php
						echo number_format($this->total_rows, 0, '.', ',');
						?>
				</span>
				matches found
				<?php if ( ! empty( $options['portal_saving_searches'] ) && $options['portal_saving_searches'] ) : ?>
					<?php if ( $fmc_api_portal->is_logged_in() ) : ?>
						<?php
							$is_existing_saved_search = flexmlsSearchUtil::is_existing_saved_search( $this->search_criteria );

							add_filter( 'flexmls_searchable_fields', [ 'flexmlsSearchUtil', 'remove_saved_search_from_searchable_fields' ] );

							list($params, $cleaned_raw_criteria, $context) = flexmlsSearchUtil::parse_search_parameters_into_api_request( $this->input_source, $this->input_data );

							remove_filter( 'flexmls_searchable_fields', [ 'flexmlsSearchUtil', 'remove_saved_search_from_searchable_fields' ] );
						?>
						<div class="flexmls_connect__sr_save_search">
							<?php if ( ! $is_existing_saved_search ) : ?>
								<a href="#" class="flexmls_connect__button saved-search-button save-search">Save this search</a>
							<?php endif; ?>
						</div>

						<?php flexmlsSearchUtil::saved_search_dialog_html( $params['_filter'] ); ?>
						<?php flexmlsSearchUtil::saved_search_dialog_javascript( $params['_filter'] ); ?>
					<?php else : ?>
						<div class="flexmls_connect__sr_save_search">
							<a href="<?php echo esc_url( $fmc_api_portal->get_portal_page() ); ?>" class="flexmls_connect__button saved-search-button save-search">Log in to save searches</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<div class='flexmls_connect__sr_view_options'>
				<div>
					<label for="listings_per_page">Listings Per Page</label>
					<select name="listings_per_page" class="flexmls_connect_select listingsperpage flexmls_connect_hasJavaScript">
						<option value=''></option>
						<?php
							$values = [5, 10, 15, 20, 25];
							foreach ($values as $value) {
								$selected = ($this->page_size == $value) ? 'selected="selected"' : '';
								echo "<option value=\"{$value}\" {$selected}>{$value}</option>";
							}
						?>
					</select>
				</div>

				<div>
					<label for="OrderBy">Sort By</label>
					<select name='OrderBy' class='flexmls_connect_select flex_orderby  flexmls_connect_hasJavaScript'>
						<option value=''></option>
						<?php
							$sort_options = array(
								"-ListPrice"              => "List price (High to Low)",
								"ListPrice"               => "List price (Low to High)",
								"-BedsTotal"              =>"# Bedrooms",
								"-BathsTotal"             =>"# Bathrooms",
								"-YearBuilt"              => "Year Built",
								"-BuildingAreaTotal"      => "Square Footage",
								"-ModificationTimestamp"  => "Recently Updated"
							);
							$current_value = flexmlsConnect::wp_input_get_post('OrderBy');

							foreach ($sort_options as $value => $display_text) {
			$selected = ($this->order_by == $value || $current_value == $value) ? 'selected="selected"' : '';
								echo "<option value=\"{$value}\" {$selected}>{$display_text}</option>";
							}
						?>
					</select>
				</div>
			</div>

			<hr class='flexmls_connect__sr_divider'>

		<?php
		if ( isset ( $options['google_maps_api_key'] ) && $options['google_maps_api_key'] ) :
			/**
			 * Grab the proper data for the Google Map and render it.
			 */
			if ( isset( $_GET['view'] ) && 'map' === $_GET['view'] ) {
				$markers      = array();
				$result_count = 0;
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

					$link_to_details = flexmlsConnect::make_nice_address_url( $record, $link_to_details_criteria, $this->type );

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
			}
		endif;

		/**
		 * Display the Listings.
		 */
		$result_count = 0;

		foreach ($this->search_data as $record) {
			$result_count++;
			// Establish some variables
			$listing_address = flexmlsConnect::format_listing_street_address($record);
			$first_line_address = htmlspecialchars($listing_address[0]);
			$second_line_address = htmlspecialchars($listing_address[1]);
			$one_line_address = htmlspecialchars($listing_address[2]);
			$one_line_address_add_slashes = addslashes($listing_address[2]);
			$link_to_details_criteria = $this->search_criteria;

			$this_result_overall_index = ($this->page_size * ($this->current_page - 1)) + $result_count;

			$sf =& $record['StandardFields'];

			// figure out if there's a previous listing
			$link_to_details_criteria['p'] = ($this_result_overall_index != 1) ? 'y' : 'n';

			$link_to_details_criteria['m'] = $sf['MlsId'];

			// figure out if there's a next listing possible
			$link_to_details_criteria['n'] = ( $this_result_overall_index < $this->total_rows ) ? 'y' : 'n';

			$link_to_details = flexmlsConnect::make_nice_address_url($record, $link_to_details_criteria, $this->type);

			$rand = mt_rand();


			// Container
			echo "<div class='flexmls_connect__sr_result' title='{$one_line_address} - MLS# {$sf['ListingId']}'
				link='{$link_to_details}'>";

			// Price

			// price changes

//      $historical_result = $this->api->GetListingHistorical($record['Id']);
//      $price_changes = array();
//        if($historical_result != null)
//        {
//            foreach($historical_result as $history){
//              if($history['Event'] == 'FieldChange' && $history['Field'] == 'ListPrice' && count($price_changes) == 0)
//              {
//                  $price_changes['PreviousValue'] = $history['PreviousValue'];
//                  $price_changes['NewValue'] = $history['NewValue'];
//              }
//
//            }
//        }

			echo "<div class='flexmls_connect__sr_price'>";
				if(flexmlsConnect::is_not_blank_or_restricted($sf['ListPrice'])) echo '$' . flexmlsConnect::gentle_price_rounding($sf['ListPrice']);
				// == price changes ==
//        if(isset($price_changes) && count($price_changes) > 0){
//          $price_change = abs($price_changes['PreviousValue'] - $price_changes['NewValue']);
//          if($price_changes['PreviousValue'] > $price_changes['NewValue'])
//              echo "<div class='flexmls_connect__price_changes flexmls_connect__price_changes_down'><span>&#9660;</span> $".flexmlsConnect::gentle_price_rounding($price_change)."</div>";
//          else echo "<div class='flexmls_connect__price_changes flexmls_connect__price_changes_up'><span>&#9650;</span> $".flexmlsConnect::gentle_price_rounding($price_change)."</div>";
//      }
			echo "</div>";

			// Address
			echo "<div class='flexmls_connect__sr_address'>";
				echo "<a href='{$link_to_details}' title='Click for more details'>";
					if ($first_line_address) {
						echo $first_line_address;
						if ($second_line_address)
							echo "<br />";
					}
					echo $second_line_address;
				echo "</a>";
			echo "</div>";

			// begin left column

			echo "<div class='flexmls_connect__sr_left_column'>";

			// Image
			if ( count($sf['Photos']) >= 1 ) {

				//Find primary photo and assign it to $main_photo_url variable.
				 $count_photos = count($sf['Photos']);

				$i = 0;
				while($i < $count_photos){
					if($sf['Photos'][$i]['Primary'] === TRUE){
						$main_photo_url =     $sf['Photos'][$i]['Uri300'];
						$main_photo_urilarge = $sf['Photos'][$i]['UriLarge'];
						$caption = htmlspecialchars($sf['Photos'][$i]['Caption'], ENT_QUOTES);
						break;
					}
					$i++;
				}

			} else {
				$main_photo_url = "{$fmc_plugin_url}/assets/images/nophoto.gif";
				$main_photo_urilarge = "{$fmc_plugin_url}/assets/images/nophoto.gif";
				$caption = "";
			}

		//set alt value
		if(!empty($caption)){
			$img_alt_attr = $caption;
		}
		elseif(!empty($one_line_address)){
			$img_alt_attr = $one_line_address;
		}
		else{
			$img_alt_attr = "Photo for listing #" . $sf['ListingId'];
		}

		//set title value
		$img_title_attr = "Photo for ";
		if(!empty($one_line_address)){
			$img_title_attr .= $one_line_address . " - ";
		}

		$img_title_attr .= "listing #" . $sf['ListingId'];

			echo "<a class='photo' href='{$main_photo_urilarge}' rel='{$rand}-{$sf['ListingKey']}' title='{$caption}'>";
			echo "<img class='flexmls_connect__sr_main_photo' src='{$main_photo_url}' onerror='this.src=\"{$fmc_plugin_url}/assets/images/nophoto.gif\"' alt='{$img_alt_attr}' title='{$img_title_attr}' />";
			echo "</a>";
			echo "<div class='flexmls_connect__hidden'></div>";
			echo "<div class='flexmls_connect__hidden2'></div>";
			echo "<div class='flexmls_connect__hidden3'></div>";


			// Detail Links
			$count_photos = count($sf['Photos']);
			$count_videos = count($sf['Videos']);
			$count_tours = count($sf['VirtualTours']);

			echo "<div class='flexmls_connect__sr_details'>";

			fmcAccount::write_carts($record);

			echo "<div style='display:none;color:green;font-weight:bold;text-align:right;padding:5px'
				id='flexmls_connect__success_message{$sf['ListingId']}'></div>";

			echo "<div class='flexmls_connect__sr_details_buttons'>";
				echo "<button href='{$link_to_details}'>View Details</button>";
				?>
				<button onclick="flexmls_connect.contactForm({
					'title': 'Ask a Question',
					'subject': '<?php echo addslashes($one_line_address_add_slashes); ?> - MLS# <?php echo addslashes($sf['ListingId'])?> ',
					'agentEmail': '<?php echo $this->contact_form_agent_email($sf); ?>',
					'officeEmail': '<?php echo $this->contact_form_office_email($sf); ?>',
					'listingId': '<?php echo addslashes($sf['ListingId']); ?>'
					});">
					Ask Question
				</button>
				<?php
			echo "</div>";

			if ($count_photos > 0)
				echo "<a class='photo_click flexmls_connect__sr_asset_link'>View Photos ({$count_photos})</a>";
			if ($count_videos > 0)
				echo "<a class='video_click flexmls_connect__sr_asset_link' rel='v{$rand}-{$sf['ListingKey']}'>Videos ({$count_videos})</a>";
			if ($count_tours > 0)
				echo "<a class='tour_click flexmls_connect__sr_asset_link' rel='t{$rand}-{$sf['ListingKey']}'>Virtual Tours ({$count_tours})</a>";

			echo "</div>";

			// end flexmls_connect__sr_left_column
			echo "</div>";

			// Details table
			echo "<div class='flexmls_connect__sr_listing_facts_container'>";

			// Open House
			if ( count($sf['OpenHouses']) >= 1) {
				echo "<div class='flexmls_connect__sr_openhouse'>";
					echo "<em>Open House</em> ({$sf['OpenHouses'][0]['Date']} - {$sf['OpenHouses'][0]['StartTime']})";
				echo "</div>";
			}

			echo "<div class='flexmls_connect__sr_listing_facts'>";

			//display listing status if it is not active
			$detail_count = 0;
			foreach ($primary_details as $field_id => $display_name) {
				if ($field_id == 'PropertyType' and $exclude_property_type) {
					continue;
				}
				if ($field_id == 'MLSAreaMajor' and $exclude_area) {
					continue;
				}
				if ($field_id == 'CountyOrParish' and $exclude_county) {
					continue;
				}
				if ($field_id == 'MlsStatus' and $sf[$field_id] == 'Active'){
					continue;
				}

				$zebra = (flexmlsConnect::is_odd($detail_count)) ? 'on' : 'off';

				if ( flexmlsConnect::is_not_blank_or_restricted( $sf[$field_id] ) ) {
					$this_val = $sf[$field_id];

					if ($field_id == "PropertyType") {
						$this_val = flexmlsConnect::nice_property_type_label($this_val);
					}

					if ($field_id == "PublicRemarks") {
						$this_val = substr($this_val, 0, 75) . "...";
					}
					if ($field_id == 'MlsStatus' and $sf[$field_id] == 'Closed'){
						$this_val = "<span style='color:Blue;font-weight:bold'>$this_val</span>";
					}
					elseif ($field_id == 'MlsStatus') {
							$this_val = "<span style='color:Orange;font-weight:bold'>$this_val</span>";
					}

					$detail_count++;

					echo "<div class='flexmls_connect__zebra'><span class='flexmls_connect__field_label'>{$display_name}: </span>";

		 if(is_array($this_val)){
							$this_val = implode(", ", array_keys($this_val));
						}

					echo "<span class='flexmls_connect__field_value'>{$this_val}</span></div>";

				}

			}


			$compList = flexmlsConnect::mls_required_fields_and_values("Summary",$record);
			foreach ($compList as $reqs){
				$zebra = (flexmlsConnect::is_odd($detail_count)) ? 'on' : 'off';
				if (flexmlsConnect::is_not_blank_or_restricted($reqs[1])){
					if ($reqs[0]=='LOGO'){
						echo "<div class='flexmls_connect__zebra'>";
						echo "<span class='flexmls_connect__sr_idx'>";
						if ($reqs[1]=='IDX'){
							echo "<span style='float: right' class='flexmls_connect__badge' title='{$sf['ListOfficeName']}'>IDX</span>";
						}
						else {
							echo "<img class='flexmls_connect__badge' style='background:none; border:none; float: right' src='{$reqs[1]}' alt='IDX' />";
						}
						echo '</span>';
						echo '</div>';
						$detail_count++;
						continue;
					}
          echo  "<div class='flexmls_connect__zebra'><span class='flexmls_connect__field_label'>{$reqs[0]} </span><span class='flexmls_connect__field_value'>{$reqs[1]}</span></div>";
					$detail_count++;
				}
			}


			// end table
			echo "</div></div>";
			// end flexmls_connect__sr_listing_facts_container
			echo "</div>";
		}

		echo "<hr class='flexmls_connect__sr_divider'>";

		if ($this->total_pages != 1) {
			echo $this->pagination($this->current_page, $this->total_pages);
		}

		echo "  <div class='flexmls_connect__idx_disclosure_text flexmls_connect__disclaimer_text'>";
		echo flexmlsConnect::get_big_idx_disclosure_text();
		echo "</div>";
?>
		<div class="fbs-branding" style="text-align: center;">
				<?php echo flexmlsConnect::fbs_products_branding_link(); ?>
		</div>
<?php
		echo "</div>";

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	function pagination($current_page, $total_pages) {

		$jump_after_first = false;
		$jump_before_last = false;

		$tolerance = 5;

		$return = " <div class='flexmls_connect__sr_pagination'>";

		if ($current_page != 1) {
			$return .= "    <button href='". $this->make_pagination_link($current_page - 1) ."'>Previous</button>";
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
			$return .= "     <button href='". $this->make_pagination_link($current_page + 1) ."'>Next</button>";
		}
		$return .= "  </div><!-- pagination -->";

		return $return;

	}

	function make_pagination_link($page) {
			$page_conditions = $this->search_criteria;
			$page_conditions['pg'] = $page;
      $page_conditions['Limit'] = $this->page_size;
		$link = flexmlsConnect::make_nice_tag_url('search', $page_conditions);
		if ( isset( $_GET['view'] ) && 'map' === $_GET['view'] ) {
			$link = add_query_arg( 'view', 'map', flexmlsConnect::make_nice_tag_url( 'search', $page_conditions ) );
		}
			return $link;
	}

}
