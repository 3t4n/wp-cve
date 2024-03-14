<?php
/**
 * Class for managing the import process of a Dezrez Rezi JSON file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Dezrez_Rezi extends Houzez_Property_Feed_Process {

	public function __construct( $instance_id = '', $import_id = '' )
	{
		$this->instance_id = $instance_id;
		$this->import_id = $import_id;

		if ( $this->instance_id != '' && isset($_GET['custom_property_import_cron']) )
	    {
	    	$current_user = wp_get_current_user();

	    	$this->log("Executed manually by " . ( ( isset($current_user->display_name) ) ? $current_user->display_name : '' ) );
	    }

	    if ( !defined('ALLOW_UNFILTERED_UPLOADS') ) { define( 'ALLOW_UNFILTERED_UPLOADS', true ); }
	}

	public function parse()
	{
		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$this->log("Parsing properties");

		$import_settings = get_import_settings_from_id( $this->import_id );

		$api_calls = array(
			'sales' => array(
				'PageSize' => 999,
				'IncludeStc' => 'true',
				'RoleTypes' => array('Selling'),
				'MarketingFlags' => array('ApprovedForMarketingWebsite')
			),
			'lettings' => array(
				'PageSize' => 999,
				'IncludeStc' => 'true',
				'RoleTypes' => array('Letting'),
				'MarketingFlags' => array('ApprovedForMarketingWebsite')
			)
		);
		
		$api_calls = apply_filters( 'propertyhive_dezrez_json_api_calls', $api_calls, $import_id );

		$limit = apply_filters( "houzez_property_feed_property_limit", 25 );

		$property_i = 1;

		foreach ( $api_calls as $department => $params )
		{
			$search_url = 'https://api.dezrez.com/api/simplepropertyrole/search';
			$fields = array(
				'APIKey' => urlencode($import_settings['api_key']),
			);

			$fields_string = '';
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			$fields_string = rtrim($fields_string, '&');

			$search_url = $search_url . '?' . $fields_string;
			$contents = '';

			$post_fields = $params;
			if ( isset($import_settings['branch_ids']) && trim($import_settings['branch_ids']) != '' )
			{
				$post_fields['BranchIdList'] = array();
				$branch_ids = explode(",", $import_settings['branch_ids']);
				foreach ( $branch_ids as $branch_id )
				{
					$post_fields['BranchIdList'][] = trim($branch_id);
				}
			}
			if ( isset($import_settings['tags']) && trim($import_settings['tags']) != '' )
			{
				$post_fields['Tags'] = array();
				$tags = explode(",", $import_settings['tags']);
				foreach ( $tags as $tag )
				{
					$post_fields['Tags'][] = trim($tag);
				}
			}

			$contents = '';

			$response = wp_remote_post( 
				$search_url, 
				array(
					'method' => 'POST',
					'timeout' => 120,
					'headers' => array(
						'Rezi-Api-Version' => '1.0',
						'Content-Type' => 'application/json'
					),
					'body' => json_encode( $post_fields ),
			    )
			);
			
			if ( !is_wp_error( $response ) && is_array( $response ) ) 
			{
				$contents = $response['body'];

				$json = json_decode( $contents, TRUE );

				if ($json !== FALSE && isset($json['Collection']) && !empty($json['Collection']))
				{
					
		            $properties_imported = 0;

		            $properties_array = $json['Collection'];

					$imported_ref_key = ( ( $import_id != '' ) ? '_imported_ref_' . $import_id : '_imported_ref' );
					$imported_ref_key = apply_filters( 'houzez_property_feed_property_imported_ref_key', $imported_ref_key, $this->import_id );

					$this->log("Found " . count($properties_array) . " " . $department . " properties in JSON ready for parsing");

					foreach ($properties_array as $property)
					{
						if ( $property_i <= $limit )
	                	{
							$property_id = $property['RoleId'];

							$agent_ref = $property_id;

							$property_url = 'https://api.dezrez.com/api/simplepropertyrole/' . $property_id;
							$fields = array(
								'APIKey' => urlencode($import_settings['api_key']),
							);

							//url-ify the data for the POST
							$fields_string = '';
							foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_url = $property_url . '?' . $fields_string;
							
							$response = wp_remote_get( 
								$property_url, 
								array(
									'timeout' => 120,
									'headers' => array(
										'Rezi-Api-Version' => '1.0',
										'Content-Type' => 'application/json'
									),
							    )
							);
							
							if ( !is_wp_error( $response ) && is_array( $response ) ) 
							{
								$contents = $response['body'];

								$property_json = json_decode($contents, TRUE);
								if ($property_json !== FALSE)
								{
									$property_json['RoleId'] = $property_id;
									$property_json['SummaryTextDescription'] = ( ( isset($property['SummaryTextDescription']) && !empty($property['SummaryTextDescription']) ) ? $property['SummaryTextDescription'] : '' );
									$this->properties[] = $property_json;
								}
							}
							else
							{
								$this->log_error( 'Failed to obtain property JSON. Dump of response as follows: ' . print_r($response, TRUE) );
								return false;
							}
						}

						++$property_i;
					}
		        }
		        else
		        {
		        	// Failed to parse JSON
		        	$this->log_error( 'Failed to parse JSON file: ' . print_r($json, TRUE) );
		        	return false;
		        }
		    }
	        else
	        {
	        	$this->log_error( 'Failed to obtain JSON. Dump of response as follows: ' . print_r($response, TRUE) );
	        	return false;
	        }
	    }

		if ( empty($this->properties) )
		{
			$this->log_error( 'No properties found. We\'re not going to continue as this could likely be wrong and all properties will get removed if we continue.' );

			return false;
		}

		return true;
	}

	public function import()
	{
		global $wpdb;

		$imported_ref_key = ( ( $this->import_id != '' ) ? '_imported_ref_' . $this->import_id : '_imported_ref' );
		$imported_ref_key = apply_filters( 'houzez_property_feed_property_imported_ref_key', $imported_ref_key, $this->import_id );

		$import_settings = get_import_settings_from_id( $this->import_id );

		$this->import_start();

		do_action( "houzez_property_feed_pre_import_properties", $this->properties, $this->import_id );
        do_action( "houzez_property_feed_pre_import_properties_dezrez_rezi", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_dezrez_rezi", $this->properties, $this->import_id );

        $limit = apply_filters( "houzez_property_feed_property_limit", 25 );
        $additional_message = '';
        if ( $limit !== false )
        {
        	$this->properties = array_slice( $this->properties, 0, $limit );
        	$additional_message = '. <a href="https://houzezpropertyfeed.com/#pricing" target="_blank">Upgrade to PRO</a> to import unlimited properties';
        }

		$this->log( 'Beginning to loop through ' . count($this->properties) . ' properties' . $additional_message );

		$start_at_property = get_option( 'houzez_property_feed_property_' . $this->import_id );

		$property_row = 1;
		foreach ( $this->properties as $property )
		{
			if ( !empty($start_at_property) )
			{
				// we need to start on a certain property
				if ( $property['RoleId'] == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . $property['RoleId'] );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, $property['RoleId'], false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . $property['RoleId'], $property['RoleId'] );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => $property['RoleId']
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = array();
	        if ( isset($property['Address']['Street']) && trim($property['Address']['Street']) != '' )
	        {
	        	$display_address[] = trim($property['Address']['Street']);
	        }
	        if ( isset($property['Address']['Locality']) && trim($property['Address']['Locality']) != '' )
	        {
	        	$display_address[] = trim($property['Address']['Locality']);
	        }
	        elseif ( isset($property['Address']['Locality']) && trim($property['Address']['Locality']) != '' )
	        {
	        	$display_address[] = trim($property['Address']['Locality']);
	        }
	        elseif ( isset($property['Address']['County']) && trim($property['Address']['County']) != '' )
	        {
	        	$display_address[] = trim($property['Address']['County']);
	        }
	        $display_address = implode(", ", $display_address);

			$post_content = '';

			if ( isset($property['Descriptions']) && is_array($property['Descriptions']) && !empty($property['Descriptions']) )
		    {
		        foreach ( $property['Descriptions'] as $description )
		        {
		            if ( isset($description['Name']) && strtolower($description['Name']) == 'main marketing' && isset($description['Text']) && $description['Text'] != '' )
		            {
		                $post_content .= '<p>' . $description['Text'] . '</p>';
		            }
				}
				foreach ( $property['Descriptions'] as $description )
		        {
					if ( isset($description['Rooms']) && is_array($description['Rooms']) && !empty($description['Rooms']) )
					{
						foreach ($description['Rooms'] as $room)
						{
							$room_content = ( isset($room['Name']) && !empty($room['Name']) ) ? '<strong>' . $room['Name'] . '</strong>' : '';
							if ( isset($room['Text']) && !empty($room['Text']) ) 
							{
								if ( !empty($room_content) ) { $room_content .= '<br>'; }
								$room_content .= $room['Text'];
							}
							
							if ( !empty($room_content) )
							{
								$post_content .= '<p>' . $room_content . '</p>';
							}
						}
					}
				}
			}
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', $property['RoleId'] );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => $property['SummaryTextDescription'],
				    	'post_content' 	 => $post_content,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), $property['RoleId'] );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', $property['RoleId'] );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => $property['SummaryTextDescription'],
					'post_content' 	 => $post_content,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), $property['RoleId'] );
				}
				else
				{
					$inserted_updated = 'inserted';
				}
			}
			$property_query->reset_postdata();

			if ( $inserted_updated !== false )
			{
				// Inserted property ok. Continue

				if ( $inserted_updated == 'updated' )
				{
					// Get all meta data so we can compare before and after to see what's changed
					$metadata_before = get_metadata('post', $post_id, '', true);

					// Get all taxonomy/term data
					$taxonomy_terms_before = array();
					$taxonomy_names = get_post_taxonomies( $post_id );
					foreach ( $taxonomy_names as $taxonomy_name )
					{
						$taxonomy_terms_before[$taxonomy_name] = wp_get_post_terms( $post_id, $taxonomy_name, array('fields' => 'ids') );
					}
				}

				$this->log( 'Successfully ' . $inserted_updated . ' post', $property['RoleId'], $post_id );

				update_post_meta( $post_id, $imported_ref_key, $property['RoleId'] );

				update_post_meta( $post_id, '_property_import_data', json_encode($property, JSON_PRETTY_PRINT) );

				$department = ( (strtolower($property['RoleType']['SystemName']) != 'selling') ? 'residential-lettings' : 'residential-sales' );

				$poa = false;
				if ( isset($property['Flags']) && is_array($property['Flags']) && !empty($property['Flags']) )
				{
					foreach ( $property['Flags'] as $flag )
					{
						if ( isset($flag['SystemName']) && $flag['SystemName'] == 'PriceOnApplication' )
					    {
							$poa = true;
						}
					}
				}

				if ( $poa === false )
				{
					if (
						isset($property['Price']['PriceQualifierType']['SystemName']) && 
						( strtolower($property['Price']['PriceQualifierType']['SystemName']) == 'priceonapplication' || strtolower($property['Price']['PriceQualifierType']['SystemName']) == 'poa' )
					)
					{
						$poa = true;
					}
				}

				if ( $poa === true ) 
                {
                    update_post_meta( $post_id, 'fave_property_price', 'POA');
                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
                }
                else
                {
                	$price = '';
            		if ( isset($property['Price']['PriceValue']) && !empty($property['Price']['PriceValue']) )
            		{
                		$price = preg_replace("/[^0-9.]/", '', $property['Price']['PriceValue']);
                	}

                	if ( $department == 'residential-sales' )
                	{
	                    update_post_meta( $post_id, 'fave_property_price_prefix', ( isset($property['Price']['PriceQualifierType']['DisplayName']) ? $property['Price']['PriceQualifierType']['DisplayName'] : '' ) );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
	                }
	                elseif ( $department == 'residential-lettings' )
	                {
	                	$rent_frequency = 'pcm';

						if ( isset($property['Price']['PriceType']['SystemName']) )
						{
							switch ($property['Price']['PriceType']['SystemName'])
							{
								case "Daily": { $rent_frequency = 'pd'; break; }
								case "Weekly": { $rent_frequency = 'pw'; break; }
								case "Fortnightly": { $rent_frequency = 'per fortnight'; break; }
								case "FourWeekly": { $rent_frequency = 'per four weeks';break; }
								case "Quarterly": { $rent_frequency = 'pq'; break; }
								case "SixMonthly": { $rent_frequency = 'per six months'; break; }
								case "Yearly": { $rent_frequency = 'pa'; break; }
							}
						}

	                	update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
	                }
                }

                if ( isset($property['Descriptions']) && is_array($property['Descriptions']) && !empty($property['Descriptions']) )
				{
					foreach ( $property['Descriptions'] as $description )
					{
						// Room Counts
						if ( 
							$description['Name'] == 'Room Counts' ||  
							( isset($description['DescriptionType']['SystemName']) && $description['DescriptionType']['SystemName'] == 'RoomCount' )
						)
						{
			                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($description['Bedrooms']) ) ? $description['Bedrooms'] : '' ) );
				            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($description['Bathrooms']) ) ? $description['Bathrooms'] : '' ) );
				            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($description['Receptions']) ) ? $description['Receptions'] : '' ) );
				        }
				    }
				}
	            update_post_meta( $post_id, 'fave_property_garage', '' );
	            update_post_meta( $post_id, 'fave_property_id', $property['RoleId'] );

	            $address_parts = array();
	            if ( isset($property['Address']['Street']) && $property['Address']['Street'] != '' )
	            {
	                $address_parts[] = $property['Address']['Street'];
	            }
	            if ( isset($property['Address']['Locality']) && $property['Address']['Locality'] != '' )
	            {
	                $address_parts[] = $property['Address']['Locality'];
	            }
	            if ( isset($property['Address']['Town']) && $property['Address']['Town'] != '' )
	            {
	                $address_parts[] = $property['Address']['Town'];
	            }
	            if ( isset($property['Address']['County']) && $property['Address']['County'] != '' )
	            {
	                $address_parts[] = $property['Address']['County'];
	            }
	            if ( isset($property['Address']['Postcode']) && $property['Address']['Postcode'] != '' )
	            {
	                $address_parts[] = $property['Address']['Postcode'];
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' );
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $lat = '';
	            $lng = '';
	            if ( isset($property['Address']['Location']['Latitude']) && !empty($property['Address']['Location']['Latitude']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_lat', $property['Address']['Location']['Latitude'] );
	                $lat = $property['Address']['Location']['Latitude'];
	            }
	            if ( isset($property['Address']['Location']['Longitude']) && !empty($property['Address']['Location']['Longitude']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_long', $property['Address']['Location']['Longitude'] );
	                $lng = $property['Address']['Location']['Longitude'];
	            }
	            update_post_meta( $post_id, 'fave_property_location', $lat . "," . $lng . ",14" );
	            update_post_meta( $post_id, 'fave_property_country', 'GB' );
	            
	            $address_parts = array();
	            if ( isset($property['Address']['Street']) && $property['Address']['Street'] != '' )
	            {
	                $address_parts[] = $property['Address']['Street'];
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property['Address']['Postcode']) ) ? $property['Address']['Postcode'] : '' ) );

	            $featured = '0';
	            if ( isset($property['Flags']) && is_array($property['Flags']) && !empty($property['Flags']) )
				{
					foreach ( $property['Flags'] as $flag )
					{
						if ( isset($flag['SystemName']) && $flag['SystemName'] == 'Featured' )
			            {
			            	$featured = '1';
			            }
					}
				}
	            update_post_meta( $post_id, 'fave_featured', $featured );
	            update_post_meta( $post_id, 'fave_agent_display_option', ( isset($import_settings['agent_display_option']) ? $import_settings['agent_display_option'] : 'none' ) );

	            if ( 
	            	isset($import_settings['agent_display_option']) && 
	            	isset($import_settings['agent_display_option_rules']) && 
	            	is_array($import_settings['agent_display_option_rules']) && 
	            	!empty($import_settings['agent_display_option_rules']) 
	            )
	            {
		            switch ( $import_settings['agent_display_option'] )
		            {
		            	case "author_info":
		            	{
		            		foreach ( $import_settings['agent_display_option_rules'] as $rule )
		            		{
		            			$value_in_feed_to_check = '';
		            			switch ( $rule['field'] )
		            			{
		            				case "Branch ID":
		            				{
		            					$value_in_feed_to_check = $property['BranchDetails']['Id'];
		            					break;
		            				}
		            				case "Branch Name":
		            				{
		            					$value_in_feed_to_check = $property['BranchDetails']['Name'];
		            					break;
		            				}
		            			}

		            			if ( $value_in_feed_to_check == $rule['equal'] || $rule['equal'] == '*' )
		            			{
		            				// set post author
		            				$my_post = array(
								    	'ID'          	 => $post_id,
								    	'post_author'    => $rule['reult'],
								  	);

								 	// Update the post into the database
								    wp_update_post( $my_post, true );

		            				break; // Rule matched. Lets not do anymore
		            			}
		            		}
		            		break;
		            	}
		            	case "agent_info":
		            	{
		            		foreach ( $import_settings['agent_display_option_rules'] as $rule )
		            		{
		            			$value_in_feed_to_check = '';
		            			switch ( $rule['field'] )
		            			{
		            				case "Branch ID":
		            				{
		            					$value_in_feed_to_check = $property['BranchDetails']['Id'];
		            					break;
		            				}
		            				case "Branch Name":
		            				{
		            					$value_in_feed_to_check = $property['BranchDetails']['Name'];
		            					break;
		            				}
		            			}

		            			if ( $value_in_feed_to_check == $rule['equal'] || $rule['equal'] == '*' )
		            			{
		            				update_post_meta( $post_id, 'fave_agents', $rule['result'] );
		            				break; // Rule matched. Lets not do anymore
		            			}
		            		}
		            		break;
		            	}
		            	case "agency_info":
		            	{
		            		foreach ( $import_settings['agent_display_option_rules'] as $rule )
		            		{
		            			$value_in_feed_to_check = '';
		            			switch ( $rule['field'] )
		            			{
		            				case "Branch ID":
		            				{
		            					$value_in_feed_to_check = $property['BranchDetails']['Id'];
		            					break;
		            				}
		            				case "Branch Name":
		            				{
		            					$value_in_feed_to_check = $property['BranchDetails']['Name'];
		            					break;
		            				}
		            			}

		            			if ( $value_in_feed_to_check == $rule['equal'] || $rule['equal'] == '*' )
		            			{
		            				update_post_meta( $post_id, 'fave_property_agency', $rule['result'] );
		            				break; // Rule matched. Lets not do anymore
		            			}
		            		}
		            		break;
		            	}
		            }
	        	}
	        	
	            // Turn bullets into property features
	            $feature_term_ids = array();
	            if ( isset($property['Descriptions']) && is_array($property['Descriptions']) && !empty($property['Descriptions']) )
				{
					foreach ( $property['Descriptions'] as $description )
					{
						if ( $description['Name'] == 'Feature Description' ||  $description['DescriptionType']['SystemName'] == 'Feature' )
						{
							if ( isset($description['Features']) && is_array($description['Features']) && !empty($description['Features']) )
							{
								foreach ( $description['Features'] as $feature )
								{
									if ( isset($feature['Feature']) && !empty($feature['Feature']) )
									{
										$term = term_exists( trim($feature['Feature']), 'property_feature');
										if ( $term !== 0 && $term !== null && isset($term['term_id']) )
										{
											$feature_term_ids[] = (int)$term['term_id'];
										}
										else
										{
											$term = wp_insert_term( trim($feature['Feature']), 'property_feature' );
											if ( is_array($term) && isset($term['term_id']) )
											{
												$feature_term_ids[] = (int)$term['term_id'];
											}
										}
									}
								}
							}
						}
					}
					if ( !empty($feature_term_ids) )
					{
						wp_set_object_terms( $post_id, $feature_term_ids, "property_feature" );
					}
					else
					{
						wp_delete_object_term_relationships( $post_id, "property_feature" );
					}
				}

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				if ( isset($property['Flags']) && is_array($property['Flags']) && !empty($property['Flags']) )
				{
					foreach ( $property['Flags'] as $flag )
					{
						if ( isset($flag['SystemName']) && !empty($flag['SystemName']) )
						{
							if ( isset($taxonomy_mappings[$flag['SystemName']]) && !empty($taxonomy_mappings[$flag['SystemName']]) )
							{
				                wp_set_post_terms( $post_id, (int)$taxonomy_mappings[$flag['SystemName']], 'availability' );
				            }
						}
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property['Descriptions']) && is_array($property['Descriptions']) && !empty($property['Descriptions']) )
				{
					foreach ( $property['Descriptions'] as $description )
					{
						if ( 
							$description['Name'] == 'StyleAge' ||
							( isset($description['DescriptionType']['SystemName']) && $description['DescriptionType']['SystemName'] == 'StyleAge' )
						)
						{
							if ( isset($description['PropertyType']['SystemName']) && $description['PropertyType']['SystemName'] != '' )
							{
								if ( isset($description['PropertyType']['SystemName']) && !empty($description['PropertyType']['SystemName']) )
								{
									if ( isset($taxonomy_mappings[$description['PropertyType']['SystemName']]) && !empty($taxonomy_mappings[$description['PropertyType']['SystemName']]) )
									{
										wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$description['PropertyType']['SystemName']], "property_type" );
									}
									else
									{
										$this->log( 'Received property type of ' . $description['PropertyType']['SystemName'] . ' that isn\'t mapped in the import settings', $property['RoleId'], $post_id );

										$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $description['PropertyType']['SystemName'], $this->import_id );
									}
								}
							}
						}
					}
				}

				// Location taxonomies
				$create_location_taxonomy_terms = isset( $import_settings['create_location_taxonomy_terms'] ) ? $import_settings['create_location_taxonomy_terms'] : false;

				$houzez_tax_settings = get_option('houzez_tax_settings', array() );
				
				$location_taxonomies = array();
				if ( !isset($houzez_tax_settings['property_city']) || ( isset($houzez_tax_settings['property_city']) && $houzez_tax_settings['property_city'] != 'disabled' ) )
				{
					$location_taxonomies[] = 'property_city';
				}
				if ( !isset($houzez_tax_settings['property_area']) || ( isset($houzez_tax_settings['property_area']) && $houzez_tax_settings['property_area'] != 'disabled' ) )
				{
					$location_taxonomies[] = 'property_area';
				}
				if ( !isset($houzez_tax_settings['property_state']) || ( isset($houzez_tax_settings['property_state']) && $houzez_tax_settings['property_state'] != 'disabled' ) )
				{
					$location_taxonomies[] = 'property_state';
				}

				foreach ( $location_taxonomies as $location_taxonomy )
				{
					$address_field_to_use = isset( $import_settings[$location_taxonomy . '_address_field'] ) ? $import_settings[$location_taxonomy . '_address_field'] : '';
					if ( !empty($address_field_to_use) )
					{
						$location_term_ids = array();
						if ( isset($property['Address'][$address_field_to_use]) && !empty($property['Address'][$address_field_to_use]) )
		            	{
		            		$term = term_exists( trim($property['Address'][$address_field_to_use]), $location_taxonomy);
							if ( $term !== 0 && $term !== null && isset($term['term_id']) )
							{
								$location_term_ids[] = (int)$term['term_id'];
							}
							else
							{
								if ( $create_location_taxonomy_terms === true )
								{
									$term = wp_insert_term( trim($property['Address'][$address_field_to_use]), $location_taxonomy );
									if ( is_array($term) && isset($term['term_id']) )
									{
										$location_term_ids[] = (int)$term['term_id'];
									}
								}
							}
		            	}
		            	if ( !empty($location_term_ids) )
						{
							wp_set_object_terms( $post_id, $location_term_ids, $location_taxonomy );
						}
						else
						{
							wp_delete_object_term_relationships( $post_id, $location_taxonomy );
						}
					}
				}

				// Images
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$image_i = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_property_images' );

				$start_at_image_i = false;
				$previous_import_media_ids = get_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id );

				if ( !empty($previous_import_media_ids) )
				{
					// an import stopped previously whilst doing images. Check if it was this post
					$explode_previous_import_media_ids = explode("|", $previous_import_media_ids);
					if ( $explode_previous_import_media_ids[0] == $post_id )
					{
						// yes it was this property. now loop through the media already imported to ensure it's not imported again
						if ( isset($explode_previous_import_media_ids[1]) && !empty($explode_previous_import_media_ids[1]) )
						{
							$media_ids = explode(",", $explode_previous_import_media_ids[1]);
							$start_at_image_i = count($media_ids);

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', $property['RoleId'], $post_id );
						}
					}
				}

				if ( isset($property['Images']) && !empty($property['Images']) )
				{
					foreach ( $property['Images'] as $image )
					{
						if ( 
							isset($image['Url']) && $image['Url'] != ''
							&&
							(
								substr( strtolower($image['Url']), 0, 2 ) == '//' || 
								substr( strtolower($image['Url']), 0, 4 ) == 'http'
							)
							&&
							isset($image['DocumentType']['SystemName']) && $image['DocumentType']['SystemName'] == 'Image'
							&&
							isset($image['DocumentSubType']['SystemName']) && $image['DocumentSubType']['SystemName'] == 'Photo'
						)
						{
							if ( $start_at_image_i !== false )
							{
								// we need to start at a specific image
								if ( $image_i < $start_at_image_i )
								{
									++$existing;
									++$image_i;
									continue;
								}
							}

							// This is a URL
							$url = $image['Url'];
							if ( strpos(strtolower($url), 'width=') === FALSE )
							{
								// If no width passed then set to 2048
								$url .= ( ( strpos($url, '?') === FALSE ) ? '?' : '&' ) . 'width=';
								$url .= apply_filters( 'houzez_property_feed_dezrez_rezi_image_width', '2048' );
							}
							$description = '';
						    
							$filename = basename( $url );

							$exploded_filename = explode(".", $filename);
						    $ext = 'jpg';
						    if (strlen($exploded_filename[count($exploded_filename)-1]) == 3)
						    {
						    	$ext = $exploded_filename[count($exploded_filename)-1];
						    }
						    $name = $property['RoleId'] . '_' . $image['Id'] . '.' . $ext;

							// Check, based on the URL, whether we have previously imported this media
							$imported_previously = false;
							$imported_previously_id = '';
							if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
							{
								foreach ( $previous_media_ids as $previous_media_id )
								{
									if ( 
										get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $url
									)
									{
										$imported_previously = true;
										$imported_previously_id = $previous_media_id;
										break;
									}
								}
							}

							if ($imported_previously)
							{
								$media_ids[] = $imported_previously_id;

								if ( $description != '' )
								{
									$my_post = array(
								    	'ID'          	 => $imported_previously_id,
								    	'post_title'     => $description,
								    );

								 	// Update the post into the database
								    wp_update_post( $my_post );
								}

								if ( $image_i == 0 ) set_post_thumbnail( $post_id, $imported_previously_id );

								++$existing;

								++$image_i;

								update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
							}
							else
							{
								$tmp = download_url( $url );

							    $file_array = array(
							        'name' => $name,
							        'tmp_name' => $tmp
							    );

							    // Check for download errors
							    if ( is_wp_error( $tmp ) ) 
							    {
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['RoleId'], $post_id );
							    }
							    else
							    {
								    $id = media_handle_sideload( $file_array, $post_id, $description );

								    // Check for handle sideload errors.
								    if ( is_wp_error( $id ) ) 
								    {
								        @unlink( $file_array['tmp_name'] );
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['RoleId'], $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $url);

								    	if ( $image_i == 0 ) set_post_thumbnail( $post_id, $id );

								    	++$new;

								    	++$image_i;

								    	update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
								    }
								}
							}
						}
					}
				}
				if ( $media_ids != $previous_media_ids )
				{
					delete_post_meta( $post_id, 'fave_property_images' );
					foreach ( $media_ids as $media_id )
					{
						add_post_meta( $post_id, 'fave_property_images', $media_id );
					}
				}

				// Loop through $previous_media_ids, check each one exists in $media_ids, and if it doesn't then delete
				if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
				{
					foreach ( $previous_media_ids as $previous_media_id )
					{
						if ( !in_array($previous_media_id, $media_ids) )
						{
							if ( wp_delete_attachment( $previous_media_id, TRUE ) !== FALSE )
							{
								++$deleted;
							}
						}
					}
				}

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['RoleId'], $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if ( isset($property['Documents']) && !empty($property['Documents']) )
				{
					foreach ( $property['Documents'] as $document )
					{
						if ( 
							isset($document['Url']) && $document['Url'] != ''
							&&
							(
								substr( strtolower($document['Url']), 0, 2 ) == '//' || 
								substr( strtolower($document['Url']), 0, 4 ) == 'http'
							)
							&&
							isset($document['DocumentType']['SystemName']) && $document['DocumentType']['SystemName'] == 'Image'
							&&
							isset($document['DocumentSubType']['SystemName']) && $document['DocumentSubType']['SystemName'] == 'Floorplan'
						)
						{
							$floorplans[] = array( 
								"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
								"fave_plan_image" => $document['Url']
							);
						}
					}
				}

				if ( !empty($floorplans) )
				{
	                update_post_meta( $post_id, 'floor_plans', $floorplans );
	                update_post_meta( $post_id, 'fave_floor_plans_enable', 'enable' );
	            }
	            else
	            {
	            	update_post_meta( $post_id, 'fave_floor_plans_enable', 'disable' );
	            }

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', $property['RoleId'], $post_id );

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				if ( isset($property['Documents']) && !empty($property['Documents']) )
				{
					foreach ( $property['Documents'] as $document )
					{
						if ( 
							isset($document['Url']) && $document['Url'] != ''
							&&
							(
								substr( strtolower($document['Url']), 0, 2 ) == '//' || 
								substr( strtolower($document['Url']), 0, 4 ) == 'http'
							)
							&&
							isset($document['DocumentType']['SystemName']) && $document['DocumentType']['SystemName'] == 'Document'
	                        &&
	                        isset($document['DocumentSubType']['SystemName']) && $document['DocumentSubType']['SystemName'] == 'Brochure'
						)
						{
							// This is a URL
							$url = $document['Url'];
							$description = '';
						    
						    $filename = basename( $url );

							$exploded_filename = explode(".", $filename);
						    $ext = 'pdf';
						    if (strlen($exploded_filename[count($exploded_filename)-1]) == 3)
						    {
						    	$ext = $exploded_filename[count($exploded_filename)-1];
						    }
						    $name = $property['RoleId'] . '_' . $document['Id'] . '.' . $ext;

							// Check, based on the URL, whether we have previously imported this media
							$imported_previously = false;
							$imported_previously_id = '';
							if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
							{
								foreach ( $previous_media_ids as $previous_media_id )
								{
									if ( 
										get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $url
									)
									{
										$imported_previously = true;
										$imported_previously_id = $previous_media_id;
										break;
									}
								}
							}

							if ($imported_previously)
							{
								$media_ids[] = $imported_previously_id;

								if ( $description != '' )
								{
									$my_post = array(
								    	'ID'          	 => $imported_previously_id,
								    	'post_title'     => $description,
								    );

								 	// Update the post into the database
								    wp_update_post( $my_post );
								}

								++$existing;
							}
							else
							{
								$tmp = download_url( $url );

							    $file_array = array(
							        'name' => $name,
							        'tmp_name' => $tmp
							    );

							    // Check for download errors
							    if ( is_wp_error( $tmp ) ) 
							    {
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['RoleId'], $post_id );
							    }
							    else
							    {
								    $id = media_handle_sideload( $file_array, $post_id, $description, array(
	                                    'post_title' => __( 'Brochure', 'houzezpropertyfeed' ),
	                                    'post_excerpt' => $description
	                                ) );

								    // Check for handle sideload errors.
								    if ( is_wp_error( $id ) ) 
								    {
								        @unlink( $file_array['tmp_name'] );
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['RoleId'], $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $url);

								    	++$new;
								    }
								}
							}
						}
					}
				}

				if ( 
					isset($property['EPC']['Image']['Url']) && 
					!empty($property['EPC']['Image']['Url'])  && 
					(
						substr( strtolower($property['EPC']['Image']['Url']), 0, 2 ) == '//' || 
						substr( strtolower($property['EPC']['Image']['Url']), 0, 4 ) == 'http'
					)
				)
        		{
					// This is a URL
					$url = $property['EPC']['Image']['Url'];
					// This is a URL
					$description = __( 'EPC', 'houzezpropertyfeed' );
				   
					// Check, based on the URL, whether we have previously imported this media
					$imported_previously = false;
					$imported_previously_id = '';
					if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
					{
						foreach ( $previous_media_ids as $previous_media_id )
						{
							if ( 
								get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $url
							)
							{
								$imported_previously = true;
								$imported_previously_id = $previous_media_id;
								break;
							}
						}
					}

					if ($imported_previously)
					{
						$media_ids[] = $imported_previously_id;

						if ( $description != '' )
						{
							$my_post = array(
						    	'ID'          	 => $imported_previously_id,
						    	'post_title'     => $description,
						    );

						 	// Update the post into the database
						    wp_update_post( $my_post );
						}

						++$existing;
					}
					else
					{
						$tmp = download_url( $url );

					    $file_array = array(
					        'name' => $filename,
					        'tmp_name' => $tmp
					    );

					    // Check for download errors
					    if ( is_wp_error( $tmp ) ) 
					    {
					        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['RoleId'], $post_id );
					    }
					    else
					    {
						    $id = media_handle_sideload( $file_array, $post_id, $description, array(
                                'post_title' => $description,
                                'post_excerpt' => $description
                            ) );

						    // Check for handle sideload errors.
						    if ( is_wp_error( $id ) ) 
						    {
						        @unlink( $file_array['tmp_name'] );
						        
						        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['RoleId'], $post_id );
						    }
						    else
						    {
						    	$media_ids[] = $id;

						    	update_post_meta( $id, '_imported_url', $url);

						    	++$new;
						    }
						}
					}
				}

				if ( $media_ids != $previous_media_ids )
				{
					delete_post_meta( $post_id, 'fave_attachments' );
					foreach ( $media_ids as $media_id )
					{
						add_post_meta( $post_id, 'fave_attachments', $media_id );
					}
				}

				// Loop through $previous_media_ids, check each one exists in $media_ids, and if it doesn't then delete
				if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
				{
					foreach ( $previous_media_ids as $previous_media_id )
					{
						if ( !in_array($previous_media_id, $media_ids) )
						{
							if ( wp_delete_attachment( $previous_media_id, TRUE ) !== FALSE )
							{
								++$deleted;
							}
						}
					}
				}

				$this->log( 'Imported ' . count($media_ids) . ' brochures and EPCs (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['RoleId'], $post_id );
				
				$virtual_tours = array();
				if ( isset($property['Documents']) && !empty($property['Documents']) )
	            {
	                foreach ( $property['Documents'] as $document )
	                {
	                    if ( 
	                        isset($document['Url']) && $document['Url'] != ''
	                        &&
	                        (
	                            substr( strtolower($document['Url']), 0, 2 ) == '//' || 
	                            substr( strtolower($document['Url']), 0, 4 ) == 'http'
	                        )
	                        &&
	                        ( isset($document['DocumentType']['SystemName']) && ( $document['DocumentType']['SystemName'] == 'Link' || $document['DocumentType']['SystemName'] == 'Video' ) )
	                        &&
	                        isset($document['DocumentSubType']['SystemName']) && $document['DocumentSubType']['SystemName'] == 'VirtualTour'
	                    )
	                    {
	                        $virtual_tours[] = $document['Url'];
	                    }
	                }
                }

				update_post_meta( $post_id, 'fave_video_url', '' );
				update_post_meta( $post_id, 'fave_virtual_tour', '' );

				if ( !empty($virtual_tours) )
				{
					foreach ( $virtual_tours as $virtual_tour )
					{
						if ( 
							$virtual_tour != ''
							&&
							(
								substr( strtolower($virtual_tour), 0, 2 ) == '//' || 
								substr( strtolower($virtual_tour), 0, 4 ) == 'http'
							)
						)
						{
							// This is a URL
							$url = $virtual_tour;

							if ( strpos(strtolower($url), 'youtu') !== false || strpos(strtolower($url), 'vimeo') !== false )
							{
								update_post_meta( $post_id, 'fave_video_url', $url );
							}
							else
							{
								$iframe = '<iframe src="' . $url . '" style="border:0; height:360px; width:640px; max-width:100%" allowFullScreen="true"></iframe>';
								update_post_meta( $post_id, 'fave_virtual_tour', $iframe );
							}
						}
					}
				}

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_dezrez_rezi", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, $property['RoleId'], $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_dezrez_rezi", $this->import_id );

		$this->import_end();
	}

	public function remove_old_properties()
	{
		global $wpdb, $post;

		if ( !empty($this->properties) )
		{
			$import_refs = array();
			foreach ($this->properties as $property)
			{
				$import_refs[] = $property['RoleId'];
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}