<?php

class flexmlsConnectPageCore {

    public $input_data = array();
    public $input_source = 'page';
    public $api;
    private $account;

  private $default_order_by_value = '-ListPrice';

  private $order_by_values = [
    '-ListPrice',
    'ListPrice',
    '-BedsTotal',
    '-BathsTotal',
    '-YearBuilt',
    '-BuildingAreaTotal',
    '-ModificationTimestamp'
  ];

    function __construct($api) {
        $this->api = $api;
        $this->account = new FMC_Account($this->api->GetMyAccount());
    }

    public function parse_search_parameters_into_api_request() {
        global $fmc_api;

        // pull StandardFields from the API to verify searchability prior to searching
        $result = $fmc_api->GetStandardFields();
        $this->standard_fields = $result[0] ?? '';

        //WP-1020 - ORE
        $sf_sqft_value = ($this->account->MlsId == "20191104230040909159000000") ? 'LivingArea' : 'BuildingAreaTotal';


        $catch_fields = array(
            'OpenHouses' => array(
                'input' => 'OpenHouses',
                'field' => 'OpenHouses',
                'condition' => 'OpenHouses Bt days(0),days(#)',
                'type' => 'Integer',
            ),
            'ListingCart' => array(
                'input' => 'ListingCart',
                'operator' => 'Eq',
                'field' => 'ListingCart'
            ),
            'SavedSearch' => array(
                'input' => 'SavedSearch',
                'operator' => 'Eq',
                'field' => 'SavedSearch'
            ),
            'ListingId' => array(
                'input' => 'ListingId',
                'operator' => 'Eq',
                'field' => 'ListingId',
                'allow_or' => true
            ),
            'PropertyType' => array(
                'input' => 'PropertyType',
                'operator' => 'Eq',
                'field' => 'PropertyType',
                'allow_or' => true
            ),
            'AddressCommunity' => array(
                'input' => '"Address"_"Community2"',
                'operator' => 'Eq',
                'field' => '"Address"."Community2"',
                'allow_or' => true
            ),
            'PropertySubType' => array(
                'input' => 'PropertySubType',
                'operator' => 'Eq',
                'field' => 'PropertySubType',
                'allow_or' => true
            ),
            'MapOverlay' => array(
                'input' => 'MapOverlay',
                'operator' => 'Eq',
                'field' => 'MapOverlay'
            ),
            'City' => array(
                'input' => 'City',
                'operator' => 'Eq',
                'field' => 'City',
                'allow_or' => true
            ),
            'StateOrProvince' => array(
                'input' => 'StateOrProvince',
                'operator' => 'Eq',
                'field' => 'StateOrProvince',
                'allow_or' => true
            ),
            'CountyOrParish' => array(
                'input' => 'CountyOrParish',
                'operator' => 'Eq',
                'field' => 'CountyOrParish',
                'allow_or' => true
            ),
            'StreetAddress' => array(
                'input' => 'StreetAddress',
                'operator' => 'Eq',
                'field' => 'StreetAddress',
                'allow_or' => true
            ),
            'PostalCode' => array(
                'input' => 'PostalCode',
                'operator' => 'Eq',
                'field' => 'PostalCode',
                'allow_or' => true
            ),
            'SubdivisionName' => array(
                'input' => 'SubdivisionName',
                'operator' => 'Eq',
                'field' => 'SubdivisionName',
                'allow_or' => true
            ),
            'MinBeds' => array(
                'input' => 'MinBeds',
                'operator' => 'Ge',
                'field' => 'BedsTotal'
            ),
            'MaxBeds' => array(
                'input' => 'MaxBeds',
                'operator' => 'Le',
                'field' => 'BedsTotal'
            ),
            'MinBaths' => array(
                'input' => 'MinBaths',
                'operator' => 'Ge',
                'field' => 'BathsTotal'
            ),
            'MaxBaths' => array(
                'input' => 'MaxBaths',
                'operator' => 'Le',
                'field' => 'BathsTotal'
            ),
            'MinPrice' => array(
                'input' => 'MinPrice',
                'operator' => 'Ge',
                'field' => 'ListPrice'
            ),
            'MaxPrice' => array(
                'input' => 'MaxPrice',
                'operator' => 'Le',
                'field' => 'ListPrice'
            ),
            'MinSqFt' => array(
                'input' => 'MinSqFt',
                'operator' => 'Ge',
                'field' => $sf_sqft_value
            ),
            'MaxSqFt' => array(
                'input' => 'MaxSqFt',
                'operator' => 'Le',
                'field' => $sf_sqft_value
            ),
            'MinYear' => array(
                'input' => 'MinYear',
                'operator' => 'Ge',
                'field' => 'YearBuilt'
            ),
            'MaxYear' => array(
                'input' => 'MaxYear',
                'operator' => 'Le',
                'field' => 'YearBuilt'
            ),
            'MLSAreaMinor' => array(
                'input' => 'MLSAreaMinor',
                'operator' => 'Eq',
                'field' => 'MLSAreaMinor',
                'allow_or' => true
            ),
            'MLSAreaMajor' => array(
                'input' => 'MLSAreaMajor',
                'operator' => 'Eq',
                'field' => 'MLSAreaMajor',
                'allow_or' => true
            ),
            'StatusChangeTimestamp' => array(
                'input' => 'StatusChangeTimestamp',
                'operator' => 'Gt',
                'field' => 'StatusChangeTimestamp'
            ),
            'OnMarketDate' => array(
                'input' => 'OnMarketDate',
                'operator' => 'Gt',
                'field' => 'OnMarketDate'
            ),
            'OriginalOnMarketTimestamp' => array(
                'input' => 'OriginalOnMarketTimestamp',
                'operator' => 'Gt',
                'field' => 'OriginalOnMarketTimestamp'
            ),
            'PriceChangeTimestamp' => array(
                'input' => 'PriceChangeTimestamp',
                'operator' => 'Gt',
                'field' => 'PriceChangeTimestamp'
            ),
            'StandardStatus' => array(
                'input' => 'StandardStatus',
                'operator' => 'Eq',
                'field' => 'StandardStatus',
                'allow_or' => true
            ),
            'SchoolDistrict' => array(
                'input' => 'SchoolDistrict',
                'operator' => 'Eq',
                'field' => 'SchoolDistrict',
                'allow_or' => true
            ),
            'WaterBodyName' => array(
                'input' => 'WaterBodyName',
                'operator' => 'Eq',
                'field' => 'WaterBodyName',
                'allow_or' => true
            ),
            'default_view'  => array(
                'input' => 'default_view',
                'operator' => 'Eq',
                'field' => 'default_view',
                'allow_or' => true
            ),
        );

        $searchable_fields = array();

        if( ! empty( $this->standard_fields ) && is_array( $this->standard_fields ) ){
            foreach( $this->standard_fields as $k => $v ){
                if( $v[ 'Searchable' ] ){
                    $searchable_fields[] = $k;
                    if (!array_key_exists($k, $catch_fields)) {
                        $catch_fields[] = [
                            'input' => $k,
                            'operator' => 'Eq',
                            'field' => $k,
                            'allow_or' => true
                        ];
                    }
                }
            }
        }

        // add in special fields
        $searchable_fields[] = 'SavedSearch';
        $searchable_fields[] = 'StreetAddress';
        $searchable_fields[] = 'MapOverlay';
        $searchable_fields[] = 'ListingCart';
        $searchable_fields[] = 'OpenHouses';
        $searchable_fields[] = '"Address"."Community2"';

        $searchable_fields = apply_filters( 'flexmls_searchable_fields', $searchable_fields );

        // start catching and building API search criteria
        $search_criteria = array();
        $cleaned_raw_criteria = array();

        // used to track how many field values are provided for each field
        $field_value_count = array();

        // pluck out values from GET or POST
        foreach( $catch_fields as $f ){

            if( 'BathsTotal' == $f[ 'field' ] ){
                if( is_array( $this->standard_fields ) && array_key_exists( 'BathsTotal', $this->standard_fields ) ){
                    if( array_key_exists( 'MlsVisible', $this->standard_fields[ 'BathsTotal' ] ) && empty( $this->standard_fields[ 'BathsTotal' ][ 'MlsVisible' ] ) ){
                        $f[ 'field' ] = 'BathsFull';
                    }
                }
            }

            $value = $this->fetch_input_data( $f[ 'input' ] );

            if( null === $value || '' == $value ){
                // not provided
                continue;
            }

            if( !in_array( $f[ 'field' ], $searchable_fields ) ){
                // field would usually be OK but it's not searchable for this user
                continue;
            }

            $field_value_count[ $f[ 'field' ] ] = 0;

            $cleaned_raw_criteria[ $f[ 'input' ] ] = $value;


            if ( array_key_exists( 'type', $f ) ) {
                $type = $f['type'];
            } elseif ( array_key_exists( $f['field'], $this->standard_fields ) ) {
                $type = $this->standard_fields[ $f['field'] ]['Type'];
            } else {
                $type = 'Character';
            }

            if( array_key_exists( 'allow_or', $f ) && $f[ 'allow_or' ] ){
                $this_field = array();

                $condition = '(';
                $f_values = explode( ',', $value );
                foreach( $f_values as $fv ){
                    $field_value_count[ $f[ 'field' ] ]++;

                    $decoded_value = $fv;

                    $formatted_value = flexmlsConnect::make_api_formatted_value( $decoded_value, $type );
                    if( null === $formatted_value ){
                        continue;
                    }
                    $this_field[] = $f[ 'field' ] . ' ' . $f[ 'operator' ] . ' ' . $formatted_value;
                }
                $condition .= implode(" Or ", $this_field);
                $condition .= ')';
            } else {
                $field_value_count[ $f['field'] ] ++;
                $formatted_value = flexmlsConnect::make_api_formatted_value( $value, $type );
                if ( null === $formatted_value ) {
                    continue;
                }

                if ( array_key_exists( 'condition', $f ) ) {
                    $condition = $f['condition'];
                    $condition = str_replace( '#', $formatted_value, $condition );
                } else {
                    $condition = $f['field'] . ' ' . $f['operator'] . ' ' . $formatted_value;
                }
            }

            // If the listing id is included in the search criteria, ignore all the
            // criteria and stop here.
            if( 'ListingId' == $f[ 'input' ] && 'SavedSearch' == $f[ 'input' ] ){
                $search_criteria = array( $condition );
                break;
            } else {
                $search_criteria[] = $condition;
            }
        }

        // check for ListAgentId
        $list_agent_id = $this->fetch_input_data( 'ListAgentId' );
        if ($list_agent_id != null) {
            $cleaned_raw_criteria['ListAgentId'] = $list_agent_id;
            $search_criteria[] = "(ListAgentId Eq '{$list_agent_id}' Or CoListAgentId Eq '{$list_agent_id}')";
        }

        $this->field_value_count = $field_value_count;

        $pg = ( flexmlsConnect::wp_input_get_post('pg') && is_numeric( flexmlsConnect::wp_input_get_post('pg') ) ) ? intval(flexmlsConnect::wp_input_get_post('pg')) : 1;
        
        $cleaned_raw_criteria['pg'] = $pg;

        $context = $this->fetch_input_data('My');
        if (!empty($context)) {
            $cleaned_raw_criteria['My'] = $context;
        }

        $desired_orderby = flexmlsConnect::wp_input_get_post('OrderBy') ? flexmlsConnect::wp_input_get_post('OrderBy') : $this->fetch_input_data('OrderBy');
    $orderby = ( !empty($desired_orderby) ) ? $desired_orderby : $this->default_order_by_value;

        $desired_limit = $this->fetch_input_data('Limit');
        $limit = ($desired_limit) ? $desired_limit : 10;
        if ($limit != 10) {
            $cleaned_raw_criteria['Limit'] = $limit;
        }

        $params = array(
            '_filter' => implode(" And ", $search_criteria),
            '_select' => 'MlsId,ListingId,ListPrice,Photos,ListingKey,OpenHouses,ListOfficeId,ListOfficeName,ListAgentFirstName,ListAgentLastName,Videos,VirtualTours,PropertyType,BedsTotal,BathsTotal,BuildingAreaTotal,LivingArea,YearBuilt,MLSAreaMinor,MLSAreaMajor,SubdivisionName,PublicRemarks,StreetNumber,StreetDirPrefix,StreetName,StreetSuffix,StreetDirSuffix,StreetAdditionalInfo,City,StateOrProvince,PostalCode,MapOverlay,SavedSearch,CountyOrParish,StreetAddress,UnparsedFirstLineAddress,SchoolDistrict,AddressCommunity',
            '_pagination' => 1,
            '_limit' => $limit,
            '_page' => $pg,
            '_expand' => 'Photos,Videos,VirtualTours,OpenHouses'
        );


        if ($orderby !== null and $orderby != 'natural') {
            $params['_orderby'] = $orderby;
        }

    $cleaned_raw_criteria['OrderBy'] = $this->prepare_order_by_data($orderby);
    $params['_orderby'] = $this->prepare_order_by_data($params['_orderby']);

        return array($params, $cleaned_raw_criteria, $context);
  }

    function get_flo_plans_for_listing_as_photos( $listing_id ) {
      $floplans = $this->api->GetListingFloPlans( $listing_id );

      $floplans_as_photos = $this->convert_floplans_array_into_photos( $floplans );

      return $floplans_as_photos;
    }

    function convert_floplans_array_into_photos( $floplan_array ) {
      $floplans_as_photos = array();

      foreach ( $floplan_array as $floplan ) {
        $thumb_image_index = array_search( 'all_in_one_thumbnail_png', array_column( $floplan['Images'], 'Type' ) );
        $full_image_index = array_search( 'all_in_one_png', array_column( $floplan['Images'], 'Type' ) );

        $floplans_as_photos []= array(
          'UriThumb' => $floplan['Images'][$thumb_image_index]['Uri'],
          'UriLarge' => $floplan['Images'][$full_image_index]['Uri'],
          'Name' => $floplan['Name'],
          'FloPlan' => 1
        );
      }

      return $floplans_as_photos;
    }

    function prepare_order_by_data($orderby) {

        $orderby = in_array($orderby, $this->order_by_values) ? $orderby : $this->default_order_by_value;

        return $orderby;

    }

    function fetch_input_data($key) {

        if ($this->input_source == 'shortcode') {
            // pull values from $this->input_data rather than $_REQUEST
            return ( array_key_exists($key, $this->input_data) ) ? $this->input_data[$key] : null;
        }
        else if (array_key_exists($key, $_GET) && is_array($_GET[$key])) {
            return implode(',', $_GET[$key]);
        } else if (array_key_exists($key, $_POST) && is_array($_POST[$key])) {
            return implode(',', $_POST[$key]);
        } else {
            return flexmlsConnect::wp_input_get_post($key);
        }

    }



    function get_browse_redirects() {
        global $fmc_api;

        $previous_listing_url = '';
        $next_listing_url = '';

        $last_page = flexmlsConnect::wp_input_get('pg');

        if ($last_page > 1) {
            $this->build_browse_list( $last_page - 1 );
        }

        $this->build_browse_list( flexmlsConnect::wp_input_get('pg') );

        if ($no_more == false) {
            $this->build_browse_list( flexmlsConnect::wp_input_get('pg') + 1 );
        }


        $last_listing = flexmlsConnect::wp_input_get('id');
        $this_listings_index = null;

        foreach ($this->browse_list as $bl) {
            if ($bl['ListingId'] == $last_listing) {
                $this_listings_index = $bl['Index'];
            }
        }

        if ( array_key_exists((string) $this_listings_index-1, $this->browse_list) ) {
            $previous_listing_url = $this->browse_list[(string) $this_listings_index-1]['Uri'];
        }
        if ( array_key_exists((string) $this_listings_index+1, $this->browse_list) ) {
            $next_listing_url = $this->browse_list[(string) $this_listings_index+1]['Uri'];
        }

        if (flexmlsConnect::wp_input_get('m')) {
            $previous_listing_url .= "&m=".flexmlsConnect::wp_input_get('m');
            $next_listing_url .= "&m=".flexmlsConnect::wp_input_get('m');
        }

        return array($previous_listing_url, $next_listing_url);

    }


    function build_browse_list($pg) {
        global $fmc_api;

        if (!$this->api){
            $this->api = $fmc_api;
        }


        // parse passed parameters for browsing capability
        list($params, $cleaned_raw_criteria, $context) = $this->parse_search_parameters_into_api_request();

        // cut out pieces we don't want
        $modified_params = $params;
        $modified_raw_criteria = $cleaned_raw_criteria;

        unset($modified_params['_expand']);
        $modified_params['_page'] = $pg;
        $modified_raw_criteria['pg'] = $pg;
        $modified_params['_limit'] = empty($_COOKIE['flexmlswordpressplugin']) ? 10 : intval($_COOKIE['flexmlswordpressplugin']) ;
        if ($context == "listings") {
            $results = $this->api->GetMyListings($modified_params);
        }
        elseif ($context == "office") {
            $results = $this->api->GetOfficeListings($modified_params);
        }
        elseif ($context == "company") {
            $results = $this->api->GetCompanyListings($modified_params);
        }
        else {
            $results = $this->api->GetListings($modified_params);
        }

        $result_count = 0;
        foreach ($results as $record) {
            $result_count++;

            $this_result_overall_index = ($this->api->page_size * ($this->api->current_page - 1)) + $result_count;

            $link_to_details_criteria = $modified_raw_criteria;
            // figure out if there's a previous listing
            $link_to_details_criteria['p'] = ($this_result_overall_index != 1) ? 'y' : 'n';

            // figure out if there's a next listing possible
            $link_to_details_criteria['n'] = ( $this_result_overall_index < $this->api->last_count ) ? 'y' : 'n';

            if ($link_to_details_criteria['n'] == 'n') {
                $this->no_more = true;
            }

            $this->browse_list[(string) $this_result_overall_index] = array(
                'Index' => $this_result_overall_index,
                'Id' => $record['Id'],
                'ListingId' => $record['StandardFields']['ListingId'],
                'Uri' => flexmlsConnect::make_nice_address_url($record, $link_to_details_criteria, $this->type)
            );

        }

    }

    function contact_form_agent_email($standard_fields) {
        $email = ($this->account->UserType == "Mls") ? $standard_fields['ListAgentEmail'] : $this->account->primary_email();
        return addslashes($email);
    }

    function contact_form_office_email($standard_fields) {

        if($this->account->UserType == "Member") {
            $office_id = $this->account->OfficeId;
            $office_account = new FMC_Account($this->api->GetAccount($office_id));
            $email = $office_account->primary_email();
        }
        elseif ($this->account->UserType == "Office") {
            $email = $this->account->primary_email();
        } else {
            $email = $standard_fields['ListOfficeEmail'];
        }
        return addslashes($email);
    }

    function wpseo_canonical() {
        // Disable the Yoast canonical tag. We add our own in flexmlsConnectPage::rel_canonical()
        return false;
    }

		function uses_v2_template() {
			$options = get_option( 'fmc_settings' );

			return ! empty( $options['search_listing_template_version'] ) && ( $options['search_listing_template_version'] == 'v2' );
		}

		function render_template_styles() {
			$options = get_option( 'fmc_settings' );
			$has_primary_color = ! empty( $options['search_listing_template_primary_color'] );
			$has_heading_font = ! empty( $options['search_listing_template_heading_font'] ) && $options['search_listing_template_heading_font'] != 'default';
			$has_body_font = ! empty( $options['search_listing_template_body_font'] ) && $options['search_listing_template_body_font'] != 'default';
			$has_customized_settings = $has_primary_color || $has_heading_font || $has_body_font;
			?>
      <?php if ( $has_customized_settings ) : ?>
        <style type="text/css">
					<?php if ( $has_primary_color ) : ?>
	          .flexmls-primary-color-font {
	            color: <?php echo esc_html( $options['search_listing_template_primary_color'] ); ?> !important;
	          }
	          .flexmls-primary-color-background {
	            background-color: <?php echo esc_html( $options['search_listing_template_primary_color'] ); ?> !important;
	          }
					<?php endif; ?>
					<?php if ( $has_body_font ) : ?>
						.flexmls-body-font {
							font-family: '<?php echo esc_attr( $options['search_listing_template_body_font'] ); ?>';
						}
					<?php endif; ?>
					<?php if ( $has_heading_font ) : ?>
						.flexmls-heading-font {
							font-family: '<?php echo esc_attr( $options['search_listing_template_heading_font'] ); ?>';
						}
					<?php endif; ?>
        </style>
      <?php endif; ?>
      <?php
		}
}
