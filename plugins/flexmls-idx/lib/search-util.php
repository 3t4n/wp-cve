<?php

class flexmlsSearchUtil {
	public static function is_existing_saved_search( $search_criteria ) {
		global $fmc_api_portal;

		$is_existing_saved_search = false;

		if ( ! empty( $search_criteria['SavedSearch'] ) ) {
			$current_saved_search_id = $search_criteria['SavedSearch'];

			$info = $fmc_api_portal->get_info();
			$user_searches = $fmc_api_portal->GetMySavedSearches( $info[ 'Id' ] );
			$user_search_ids = [];
			if ( ! empty( $user_searches ) ) {
				foreach ( $user_searches as $user_search ) {
					$user_search_ids []= $user_search['Id'];
				}

				$is_existing_saved_search = in_array( $current_saved_search_id, $user_search_ids );
			}
		}

		return $is_existing_saved_search;
	}

	public static function remove_saved_search_from_searchable_fields( $searchable_fields ) {
		$searchable_fields = array_diff( $searchable_fields, [ "SavedSearch" ] );

		return $searchable_fields;
	}

	public static function parse_search_parameters_into_api_request( $input_source, $input_data ) {
			global $fmc_api;

			// pull StandardFields from the API to verify searchability prior to searching
			$result = $fmc_api->GetStandardFields();
			$standard_fields = $result[0];

			$sf_sqft_value = '';
			$sf_sqft_value = ($standard_fields['BuildingAreaTotal']) ? $standard_fields['BuildingAreaTotal'] : $standard_fields['LivingArea'];


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
			if( count( $standard_fields ) ){
					foreach( $standard_fields as $k => $v ){
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
							if( is_array( $standard_fields ) && array_key_exists( 'BathsTotal', $standard_fields ) ){
									if( array_key_exists( 'MlsVisible', $standard_fields[ 'BathsTotal' ] ) && empty( $standard_fields[ 'BathsTotal' ][ 'MlsVisible' ] ) ){
											$f[ 'field' ] = 'BathsFull';
									}
							}
					}

					$value = static::fetch_input_data( $f[ 'input' ], $input_source, $input_data );

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
					} elseif ( array_key_exists( $f['field'], $standard_fields ) ) {
							$type = $standard_fields[ $f['field'] ]['Type'];
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
					if( 'ListingId' == $f[ 'input' ] ){
							$search_criteria = array( $condition );
							break;
					} else {
							$search_criteria[] = $condition;
					}
			}

			// check for ListAgentId
			$list_agent_id = static::fetch_input_data( 'ListAgentId', $input_source, $input_data );
			if ($list_agent_id != null) {
					$cleaned_raw_criteria['ListAgentId'] = $list_agent_id;
					$search_criteria[] = "(ListAgentId Eq '{$list_agent_id}' Or CoListAgentId Eq '{$list_agent_id}')";
			}

			$pg = ( flexmlsConnect::wp_input_get_post('pg') && is_numeric( flexmlsConnect::wp_input_get_post('pg') ) ) ? intval( flexmlsConnect::wp_input_get_post('pg') ) : 1;
			$cleaned_raw_criteria['pg'] = $pg;

			$context = static::fetch_input_data( 'My', $input_source, $input_data );
			if (!empty($context)) {
					$cleaned_raw_criteria['My'] = $context;
			}

			$desired_orderby = flexmlsConnect::wp_input_get_post('OrderBy') ? flexmlsConnect::wp_input_get_post('OrderBy') : static::fetch_input_data( 'OrderBy', $input_source, $input_data );
			$orderby = ( !empty($desired_orderby) ) ? $desired_orderby : static::default_order_by_value();

			$desired_limit = static::fetch_input_data( 'Limit', $input_source, $input_data );
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

	$cleaned_raw_criteria['OrderBy'] = static::prepare_order_by_data($orderby);
	$params['_orderby'] = static::prepare_order_by_data($params['_orderby']);

			return array($params, $cleaned_raw_criteria, $context);
	}

	public static function default_order_by_value() {
		return '-ListPrice';
	}

	public static function order_by_values() {
		return [
	    '-ListPrice',
	    'ListPrice',
	    '-BedsTotal',
	    '-BathsTotal',
	    '-YearBuilt',
	    '-BuildingAreaTotal',
	    '-ModificationTimestamp'
	  ];
	}

	public static function prepare_order_by_data( $orderby ) {
		$orderby = in_array( $orderby, static::order_by_values() ) ? $orderby : static::default_order_by_value();

		return $orderby;
	}

	public static function fetch_input_data( $key, $input_source, $input_data ) {
		if ( $input_source == 'shortcode' ) {

			return ( array_key_exists($key, $input_data) ) ? $input_data[$key] : null;
		}
		else if (array_key_exists($key, $_GET) && is_array($_GET[$key])) {
			return implode(',', $_GET[$key]);
		} else if (array_key_exists($key, $_POST) && is_array($_POST[$key])) {
			return implode(',', $_POST[$key]);
		} else {
			return flexmlsConnect::wp_input_get_post($key);
		}
	}

	public static function saved_search_dialog_javascript( $filter_param ) {
		?>
		<script type="text/javascript">
			jQuery( function ( $ ) {
				var $confirmDialog = $( '.flexmls_connect__sr_save_search_save_confirm' );
				$( '.saved-search-button.save-search' ).on( 'click', function ( e ) {
					e.preventDefault();

					$confirmDialog.fadeIn();

					e.stopPropagation();
				} );


				$( '.flexmls_connect__sr_save_search_save_confirm' ).on( 'click', function ( e ) {
					e.stopPropagation();
				} );

				$( 'body' ).on( 'click', ':not(.flexmls_connect__sr_save_search_save_confirm)', function ( e ) {
					$confirmDialog.fadeOut();
				} );

				$( '#flexmls_connect_save_search_form' ).on( 'submit', function ( e ) {
					e.preventDefault();

					$.ajax({
						url: fmcAjax.ajaxurl,
						method: 'post',
						dataType: 'json',
						data: {
							action: 'flexmls_connect_save_search',
							name: $( '.flexmls_connect_search_name' ).val(),
							filter: <?php echo json_encode( $filter_param ); ?>
						},
						success: function ( data, status ) {
							if ( data && data['result'] ) {
								$( '.flexmls_connect_search_submit' ).val( "Saved!" );
								setTimeout( function () {
									$( '.flexmls_connect__sr_save_search_save_confirm' ).fadeOut();
									console.log( data );
									if ( data['saved_search_url'] ) {
										window.location = data['saved_search_url'];
									}
								}, 500 );
							} else {
								alert( 'There was a problem saving your search' );
							}
						},
						error: function () {
							alert( 'There was a problem saving your search' );
						}
					} );
				} );
			} );
		</script>
		<?php
	}

	public static function saved_search_dialog_html( $filter_param ) {
		?>
		<div class="flexmls_connect__sr_save_search_save_confirm" style="display: none">
			<form id="flexmls_connect_save_search_form">
				<input type="text" class="flexmls_connect_search_name" name="flexmls_connect_search_name" placeholder="Name your search">
				<input type="hidden" name="flexmls_connect_search_filter" value="<?php echo esc_attr( $filter_param ); ?>">
				<input type="submit" class="flexmls_connect_search_submit flexmls-btn flexmls-btn-primary flexmls-btn-sm" value="Save">
			</form>
		</div>
		<?php
	}

	public static function close_map_javascript() {
		?>
		<script type="text/javascript">
			jQuery( function ( $ ) {
				var $button = $( '.close-map-button' ),
				    $map = $( '.flexmls-map-wrapper' );

				if ( $map.is( ':visible' ) ) {
					$map.data( 'first-open', 1 );
				}

				$button.on( 'click', function () {
					if ( $map.is( ':visible' ) ) {
						$map.slideUp();
						$button.text( 'Open Map' );
					} else {
						$map.slideDown( 400, function () {
							if ( ! $map.data( 'first-open' ) ) {
								window.idxMap.fitBounds( window.idxBounds );
								$map.data( 'first-open', 1 );
							}
						} );
						$button.text( 'Close Map' );
					}
				} );
			} );
		</script>
		<?php
	}

	public static function one_line_without_zip_address( $data ) {
		$listing = $data['StandardFields'];
		$first_line_address = (flexmlsConnect::is_not_blank_or_restricted($listing['UnparsedFirstLineAddress'])) ? $listing['UnparsedFirstLineAddress'] : "";
		$second_line_address = "";

		if ( flexmlsConnect::is_not_blank_or_restricted($listing['City']) ) {
			$second_line_address .= "{$listing['City']}, ";
		}

		if ( flexmlsConnect::is_not_blank_or_restricted($listing['StateOrProvince']) ) {
			$second_line_address .= "{$listing['StateOrProvince']} ";
		}

		$second_line_address = str_replace("********", "", $second_line_address);
		$second_line_address = flexmlsConnect::clean_spaces_and_trim($second_line_address);

		$one_line_address = (!empty($first_line_address)) ? $first_line_address . ", " : "";
		$one_line_address .= "{$second_line_address}";
		$one_line_address = flexmlsConnect::clean_spaces_and_trim($one_line_address);

		return $one_line_address;
	}

	public static function mls_fields_to_suppress( $sf ) {
		$mls_fields_to_suppress = array(
      'ListingKey',
      'ListingId',
      'ListingPrefix',
      'ListingNumber',

      'Latitude',
      'Longitude',

      'MlsId',
      'StandardStatus',
      'PermitInternetYN',
      'UnparsedAddress',

      'ListAgentId',
      'ListAgentUserType',
      'ListOfficeUserType',
      'ListAgentFirstName',
      'ListAgentMiddleName',
      'ListAgentLastName',
      'ListAgentEmail',
      'ListAgentStateLicense',
      'ListAgentPreferredPhone',
      'ListAgentPreferredPhoneExt',
      'ListAgentOfficePhone',
      'ListAgentOfficePhoneExt',
      'ListAgentDesignation',
      'ListAgentTollFreePhone',
      'ListAgentCellPhone',
      'ListAgentDirectPhone',
      'ListAgentPager',
      'ListAgentVoiceMail',
      'ListAgentVoiceMailExt',
      'ListAgentFax',
      'ListAgentURL',

      'ListOfficeId',
      'ListCompanyId',
      'ListOfficeName',
      'ListCompanyName',
      'ListOfficeFax',
      'ListOfficeEmail',
      'ListOfficeURL',
      'ListOfficePhone',
      'ListOfficePhoneExt',

      'CoListAgentId',
      'CoListAgentUserType',
      'CoListOfficeUserType',
      'CoListAgentFirstName',
      'CoListAgentMiddleName',
      'CoListAgentLastName',
      'CoListAgentEmail',
      'CoListAgentStateLicense',
      'CoListAgentPreferredPhone',
      'CoListAgentPreferredPhoneExt',
      'CoListAgentOfficePhone',
      'CoListAgentOfficePhoneExt',
      'CoListAgentDesignation',
      'CoListAgentTollFreePhone',
      'CoListAgentCellPhone',
      'CoListAgentDirectPhone',
      'CoListAgentPager',
      'CoListAgentVoiceMail',
      'CoListAgentVoiceMailExt',
      'CoListAgentFax',
      'CoListAgentURL',

      'CoListOfficeId',
      'CoListCompanyId',
      'CoListOfficeName',
      'CoListCompanyName',
      'CoListOfficeFax',
      'CoListOfficeEmail',
      'CoListOfficeURL',
      'CoListOfficePhone',
      'CoListOfficePhoneExt',

      'BuyerAgentId',
      'CoBuyerAgentId',
      'BuyerOfficeId',
      'CoBuyerOfficeId',

      'StreetNumber',
      'StreetName',
      'StreetDirPrefix',
      'StreetDirSuffix',
      'StreetSuffix',
      'StreetAdditionalInfo',
      'PropertyClass',
      'StateOrProvince',
      'PostalCode',
      'City',

      'ApprovalStatus',
      'PublicRemarks',

      'VOWAddressDisplayYN',
      'VOWConsumerCommentYN',
      'VOWAutomatedValuationDisplayYN',
      'VOWEntireListingDisplayYN',

      'PriceChangeTimestamp',
      'MajorChangeTimestamp',
      'MajorChangeType',
      'ModificationTimestamp',
      'StatusChangeTimestamp'
    );

		//if RVA then add MLSStatus to list of fields to suppress
    if ($sf['MlsId'] === "20051230194116769413000000") {
      array_push($mls_fields_to_suppress, "MlsStatus");
    }

		return $mls_fields_to_suppress;
	}
}
