<?php
/**
 * Class for managing the import process of a Street JSON file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Street extends Houzez_Property_Feed_Process {

	public function __construct( $instance_id = '', $import_id = '' )
	{
		$this->instance_id = $instance_id;
		$this->import_id = $import_id;

		if ( $this->instance_id != '' && isset($_GET['custom_property_import_cron']) )
	    {
	    	$current_user = wp_get_current_user();

	    	$this->log("Executed manually by " . ( ( isset($current_user->display_name) ) ? $current_user->display_name : '' ) );
	    }
	}

	public function parse()
	{
		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$this->log("Parsing properties");

		$import_settings = get_import_settings_from_id( $this->import_id );

		$departments = array(
			'sales' => apply_filters( 'houzez_property_feed_street_sales_statuses', array( 'for_sale', 'under_offer', 'sold_stc', 'for_sale_and_to_let' ) ),
			'lettings' => apply_filters( 'houzez_property_feed_street_lettings_statuses', array( 'to_let', 'let_agreed', 'for_sale_and_to_let' ) )
		);
		foreach ( $departments as $department => $statuses )
		{
			$this->log("Obtaining " . $department . " properties");

			$current_page = 1;
			$more_properties = true;

			while ( $more_properties )
			{
				$url = ( isset($import_settings['base_url']) && !empty($import_settings['base_url']) ) ? trim($import_settings['base_url'], '/') : 'https://street.co.uk';
				$url .= '/api/property-feed/' . $department . '/search?include=featuresForPortals%2Crooms%2Cimages%2Cfloorplans%2Cepc%2Cbrochure%2CadditionalMedia%2Ctags%2CparkingSpaces%2CoutsideSpaces&page%5Bnumber%5D=' . $current_page;
				$url .= '&filter%5Binclude_land%5D=true';
				if ( is_array($statuses) && !empty($statuses) )
				{
					$url .= '&filter%5Bstatus%5D=' . implode(',', $statuses);
				}

				$headers = array(
					'Authorization' => 'Bearer ' . $import_settings['api_key'],
				);

				$response = wp_remote_request(
					$url,
					array(
						'method' => 'GET',
						'timeout' => 60,
						'headers' => $headers
					)
				);

				if ( is_wp_error( $response ) )
				{
					$this->log_error( 'Response: ' . $response->get_error_message() );

					return false;
				}

				$json = json_decode( $response['body'], TRUE );

				if ($json !== FALSE)
				{
					$this->log("Parsing " . $department . " properties on page " . $current_page);

					if ( isset($json['errors']) && !empty($json['errors']) )
					{
						foreach ( $json['errors'] as $error )
						{
							$this->log_error( 'Error returned by Street: ' . print_r($error, TRUE) );
						}
					}

					if ( isset($json['meta']['pagination']['total_pages']) )
					{
						if ( $current_page == $json['meta']['pagination']['total_pages'] )
						{
							$more_properties = false;
						}
					}
					else
					{
						$more_properties = false;
					}

					if ( isset($json['data']) )
					{
						foreach ($json['data'] as $property)
						{
							$property['department'] = 'residential-' . $department;

							if ( isset($property['attributes']['dual_listing']) && $property['attributes']['dual_listing'] === true )
							{
								$property['id'] = $property['id'] . '-' . $department;
							}

							$relationships = array( 
								array(
									'relationship_name' => 'address',
									'included_name' => 'address',
								),
								array(
									'relationship_name' => 'details',
									'included_name' => 'details',
								),
								array(
									'relationship_name' => 'salesListing',
									'included_name' => 'sales_listing',
								),
								array(
									'relationship_name' => 'lettingsListing',
									'included_name' => 'lettings_listing',
								),
								array(
									'relationship_name' => 'featuresForPortals',
									'included_name' => 'feature',
									'multiple' => true
								),
								array(
									'relationship_name' => 'rooms',
									'included_name' => 'room',
									'multiple' => true
								),
								array(
									'relationship_name' => 'images',
									'included_name' => 'media',
									'multiple' => true
								),
								array(
									'relationship_name' => 'floorplans',
									'included_name' => 'floorplan',
									'multiple' => true
								),
								array(
									'relationship_name' => 'epc',
									'included_name' => 'epc',
								),
								array(
									'relationship_name' => 'brochure',
									'included_name' => 'brochure',
								),
								array(
									'relationship_name' => 'additionalMedia',
									'included_name' => 'additionalMedia',
									'multiple' => true
								),
								array(
									'relationship_name' => 'tags',
									'included_name' => 'tags',
									'multiple' => true
								),
								array(
									'relationship_name' => 'parkingSpaces',
									'included_name' => 'parkingSpaces',
									'multiple' => true
								),
								array(
									'relationship_name' => 'outsideSpaces',
									'included_name' => 'outsideSpaces',
									'multiple' => true
								),
							);
							$relationships = apply_filters( 'houzez_property_feed_street_relationships', $relationships );
							foreach ( $relationships as $relationship )
							{
								if ( isset($property['relationships'][$relationship['relationship_name']]['data']) )
								{
									if ( isset($relationship['multiple']) && $relationship['multiple'] === true )
									{
										if ( !empty($property['relationships'][$relationship['relationship_name']]['data']) )
										{
											foreach ( $property['relationships'][$relationship['relationship_name']]['data'] as $relationship_data )
											{
												if ( isset($relationship_data['id']) )
												{
													foreach ( $json['included'] as $included )
													{
														if ( /*$included['type'] == $relationship['included_name'] &&*/ $included['id'] == $relationship_data['id'] )
														{
															if ( !isset($property[$relationship['relationship_name']]) )
															{
																$property[$relationship['relationship_name']] = array();
															}
															$property[$relationship['relationship_name']][] = $included['attributes'];
														}
													}
												}
											}
										}
									}
									else
									{
										if ( isset($property['relationships'][$relationship['relationship_name']]['data']['id']) )
										{
											foreach ( $json['included'] as $included )
											{
												if ( $included['type'] == $relationship['included_name'] && $included['id'] == $property['relationships'][$relationship['relationship_name']]['data']['id'] )
												{
													$property[$relationship['relationship_name']] = $included['attributes'];
												}
											}
										}
									}
								}
							}

							$this->properties[] = $property;
						}
					}

					++$current_page;
				}
				else
				{
					// Failed to parse XML
					$this->log_error( 'Failed to parse JSON.' );

					return false;
				}
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
        do_action( "houzez_property_feed_pre_import_properties_street", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_street", $this->properties, $this->import_id );

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
				if ( $property['id'] == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . $property['id'] );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, $property['id'], false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . $property['id'], $property['id'] );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => $property['id']
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = $property['address']['anon_address'];
			if ( isset($property['attributes']['public_address']) && !empty($property['attributes']['public_address']) )
			{
				$display_address = $property['attributes']['public_address'];
			}

			$post_content = $property['details']['full_description'];
			if ( isset($property['rooms']) && !empty($property['rooms']) )
			{
				foreach ( $property['rooms'] as $room )
				{	
					$room_content = ( isset($room['name']) && !empty($room['name']) ) ? '<strong>' . $room['name'] . '</strong>' : '';
					$room_content .= ( isset($room['formatted_dimensions']) && !empty($room['formatted_dimensions']) ) ? ' (' . $room['formatted_dimensions'] . ')' : '';
					if ( isset($room['description']) && !empty($room['description']) ) 
					{
						if ( !empty($room_content) ) { $room_content .= '<br>'; }
						$room_content .= $room['description'];
					}
					
					if ( !empty($room_content) )
					{
						$post_content .= '<p>' . $room_content . '</p>';
					}
				}
			}
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', $property['id'] );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => $property['details']['short_description'],
				    	'post_content' 	 => $post_content,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), $property['id'] );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', $property['id'] );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => $property['details']['short_description'],
					'post_content' 	 => $post_content,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), $property['id'] );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', $property['id'], $post_id );

				update_post_meta( $post_id, $imported_ref_key, $property['id'] );

				update_post_meta( $post_id, '_property_import_data', json_encode($property, JSON_PRETTY_PRINT) );

				$department = $property['department'];

				$poa = false;
				if ( 
					$department == 'residential-sales' &&
					( isset($property['salesListing']['display_price']) && $property['salesListing']['display_price'] === false )
				)
				{
					$poa = true;
				}
				if ( 
					$department == 'residential-lettings' &&
					( isset($property['lettingsListing']['display_price']) && $property['lettingsListing']['display_price'] === false )
				)
				{
					$poa = true;
				}

				if ( $poa === true ) 
                {
                    update_post_meta( $post_id, 'fave_property_price', 'POA');
                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
                }
                else
                {
                	if ( $department == 'residential-sales' )
                	{
                		$price = '';
                		if ( isset($property['salesListing']['price']) && !empty($property['salesListing']['price']) )
                		{
	                		$price = round(preg_replace("/[^0-9.]/", '', $property['salesListing']['price']));
	                	}
	                    update_post_meta( $post_id, 'fave_property_price_prefix', ( isset($property['salesListing']['price_qualifier']) ? $property['salesListing']['price_qualifier'] : '' ) );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
	                }
	                elseif ( $department == 'residential-lettings' )
	                {
	                	$price = '';
	                	if ( isset($property['lettingsListing']['price_pcm']) )
						{
							$price = preg_replace("/[^0-9.]/", '', $property['lettingsListing']['price_pcm']);
						}
						elseif ( isset($property['lettingsListing']['price']) )
						{
							$price = preg_replace("/[^0-9.]/", '', $property['lettingsListing']['price']);
						}
	                	update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', 'pcm' );
	                }
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property['attributes']['bedrooms']) ) ? $property['attributes']['bedrooms'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property['attributes']['bathrooms']) ) ? $property['attributes']['bathrooms'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property['attributes']['receptions']) ) ? $property['attributes']['receptions'] : '' ) );
	            $parking = array();
	            if ( isset($property['parkingSpaces']) && !empty($property['parkingSpaces']) )
				{
					foreach ( $property['parkingSpaces'] as $parking_space )
					{
						if ( isset($parking_space['parking_space_type']) )
						{
							$parking[] = $parking_space['parking_space_type'];
						}
					}
				}
	            update_post_meta( $post_id, 'fave_property_garage', implode(", ", $parking) );
	            update_post_meta( $post_id, 'fave_property_id', $property['id'] );

	            $address_number = '';
				$address_street = isset($property['address']['line_1']) ? $property['address']['line_1'] : '';
				$address1_explode = explode(' ', $address_street);
				if ( !empty($address1_explode) && is_numeric($address1_explode[0]) )
				{
					$address_number = array_shift($address1_explode);
					$address_street = implode(' ', $address1_explode);
				}

	            $address_parts = array();
	            if ( $address_street != '' )
	            {
	                $address_parts[] = $address_street;
	            }
	            if ( isset($property['address']['line_2']) && $property['address']['line_2'] != '' )
	            {
	                $address_parts[] = $property['address']['line_2'];
	            }
	            if ( isset($property['address']['town']) && $property['address']['town'] != '' )
	            {
	                $address_parts[] = $property['address']['town'];
	            }
	            if ( isset($property['address']['line_3']) && $property['address']['line_3'] != '' )
	            {
	                $address_parts[] = $property['address']['line_3'];
	            }
	            if ( isset($property['address']['postcode']) && $property['address']['postcode'] != '' )
	            {
	                $address_parts[] = $property['address']['postcode'];
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' );
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $lat = '';
	            $lng = '';
	            if ( isset($property['address']['latitude']) && !empty($property['address']['latitude']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_lat', $property['address']['latitude'] );
	                $lat = $property['address']['latitude'];
	            }
	            if ( isset($property['address']['longitude']) && !empty($property['address']['longitude']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_long', $property['address']['longitude'] );
	                $lng = $property['address']['longitude'];
	            }
	            update_post_meta( $post_id, 'fave_property_location', $lat . "," . $lng . ",14" );
	            update_post_meta( $post_id, 'fave_property_country', 'GB' );
	            
	            $address_parts = array();
	            if ( $address_street != '' )
	            {
	                $address_parts[] = $address_street;
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property['address']['postcode']) ) ? $property['address']['postcode'] : '' ) );

	            $featured = '0';
	            if ( isset($property['tags']) && is_array($property['tags']) && !empty($property['tags']) )
				{
					foreach ( $property['tags'] as $tag )
					{
						if ( isset($tag['tag']) && strtolower($tag['tag']) == 'featured' )
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
		            				case "branch_uuid":
		            				{
		            					$value_in_feed_to_check = $property['attributes']['branch_uuid'];
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
		            				case "branch_uuid":
		            				{
		            					$value_in_feed_to_check = $property['attributes']['branch_uuid'];
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
		            				case "branch_uuid":
		            				{
		            					$value_in_feed_to_check = $property['attributes']['branch_uuid'];
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
	            if ( isset($property['featuresForPortals']) && is_array($property['featuresForPortals']) )
				{
					foreach ( $property['featuresForPortals'] as $feature )
					{
						$term = term_exists( trim($feature['name']), 'property_feature');
						if ( $term !== 0 && $term !== null && isset($term['term_id']) )
						{
							$feature_term_ids[] = (int)$term['term_id'];
						}
						else
						{
							$term = wp_insert_term( trim($feature['name']), 'property_feature' );
							if ( is_array($term) && isset($term['term_id']) )
							{
								$feature_term_ids[] = (int)$term['term_id'];
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

				update_post_meta( $post_id, 'fave_energy_class', ( ( isset($property['epc']['rating']) ) ? $property['epc']['rating'] : '' ) );
				update_post_meta( $post_id, 'fave_epc_current_rating', ( ( isset($property['epc']['energy_efficiency_current']) ) ? $property['epc']['energy_efficiency_current'] : '' ) );
				update_post_meta( $post_id, 'fave_epc_potential_rating', ( ( isset($property['epc']['energy_efficiency_potential']) ) ? $property['epc']['energy_efficiency_potential'] : '' ) );

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				$status_field = str_replace('residential-', '', str_replace('sales', 'sale', $department));

				if ( isset($property['attributes'][$status_field . '_status']) && !empty($property['attributes'][$status_field . '_status']) )
				{
					if ( isset($taxonomy_mappings[$property['attributes'][$status_field . '_status']]) && !empty($taxonomy_mappings[$property['attributes'][$status_field . '_status']]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['attributes'][$status_field . '_status']], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . $property['attributes'][$status_field . '_status'] . ' that isn\'t mapped in the import settings', $property['id'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, $property['attributes'][$status_field . '_status'], $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property['attributes']['property_type']) && !empty($property['attributes']['property_type']) )
				{
					$type_mapped = false;

					if ( 
						isset($property['attributes']['property_type']) && 
						$property['attributes']['property_type'] != '' &&
						isset($property['attributes']['property_style']) && 
						$property['attributes']['property_style'] != ''
					)
					{
						if ( 
							isset($taxonomy_mappings[$property['attributes']['property_type'] . ' - ' . $property['attributes']['property_style']]) && 
							!empty($taxonomy_mappings[$property['attributes']['property_type'] . ' - ' . $property['attributes']['property_style']]) 
						)
						{
							wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['attributes']['property_type'] . ' - ' . $property['attributes']['property_style']], "property_type" );
							$type_mapped = true;
						}
						else
						{
							$this->log( 'Received property type of ' . $property['attributes']['property_type'] . ' - ' . $property['attributes']['property_style'] . ' that isn\'t mapped in the import settings', $property['id'], $post_id );

							$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $property['attributes']['property_type'] . ' - ' . $property['attributes']['property_style'], $this->import_id );
						}
					}

					if ( !$type_mapped )
					{
						if ( isset($taxonomy_mappings[$property['attributes']['property_type']]) && !empty($taxonomy_mappings[$property['attributes']['property_type']]) )
						{
							wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['attributes']['property_type']], "property_type" );
						}
						else
						{
							$this->log( 'Received property type of ' . $property['attributes']['property_type'] . ' that isn\'t mapped in the import settings', $property['id'], $post_id );

							$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $property['attributes']['property_type'], $this->import_id );
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
						if ( isset($property['address'][$address_field_to_use]) && !empty($property['address'][$address_field_to_use]) )
		            	{
		            		$term = term_exists( trim($property['address'][$address_field_to_use]), $location_taxonomy);
							if ( $term !== 0 && $term !== null && isset($term['term_id']) )
							{
								$location_term_ids[] = (int)$term['term_id'];
							}
							else
							{
								if ( $create_location_taxonomy_terms === true )
								{
									$term = wp_insert_term( trim($property['address'][$address_field_to_use]), $location_taxonomy );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', $property['id'], $post_id );
						}
					}
				}

				if (isset($property['images']) && !empty($property['images']))
				{
					foreach ($property['images'] as $image)
					{
						$size = 'large'; // thumbnail, small, medium, large, hero, full
						$url = isset($image['urls'][$size]) ? $image['urls'][$size] : $image['url'];

						if ( 
							substr( strtolower($url), 0, 2 ) == '//' || 
							substr( strtolower($url), 0, 4 ) == 'http'
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
							$description = ( (isset($image['title'])) ? $image['title'] : '' );
						    
							$filename = basename( $url );

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
							        'name' => $filename,
							        'tmp_name' => $tmp
							    );

							    // Check for download errors
							    if ( is_wp_error( $tmp ) ) 
							    {
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['id'], $post_id );
							    }
							    else
							    {
								    $id = media_handle_sideload( $file_array, $post_id, $description );

								    // Check for handle sideload errors.
								    if ( is_wp_error( $id ) ) 
								    {
								        @unlink( $file_array['tmp_name'] );
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['id'], $post_id );
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['id'], $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if (isset($property['floorplans']) && !empty($property['floorplans']))
				{
					foreach ($property['floorplans'] as $floorplan)
					{
						if ( 
							isset($floorplan['url']) && $floorplan['url'] != ''
							&&
							(
								substr( strtolower($floorplan['url']), 0, 2 ) == '//' || 
								substr( strtolower($floorplan['url']), 0, 4 ) == 'http'
							)
						)
						{
							$description = ( ( isset($floorplan['title']) && !empty($floorplan['title']) ) ? $floorplan['title'] : __( 'Floorplan', 'houzezpropertyfeed' ) );

							$floorplans[] = array( 
								"fave_plan_title" => $description, 
								"fave_plan_image" => $floorplan['url']
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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', $property['id'], $post_id );

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				if (isset($property['brochure']) && !empty($property['brochure']))
				{
					if ( 
						substr( strtolower($property['brochure']['url']), 0, 2 ) == '//' || 
						substr( strtolower($property['brochure']['url']), 0, 4 ) == 'http'
					)
					{
						// This is a URL
						$url = $property['brochure']['url'];
						$description = '';
					    
						$explode_url = explode("?", $url);
						$filename = basename( $explode_url[0] );

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
						        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['id'], $post_id );
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
							        
							        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['id'], $post_id );
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

				if (isset($property['additionalMedia']) && !empty($property['additionalMedia']))
				{
					foreach ($property['additionalMedia'] as $brochure)
					{	
						if ( 
							substr( strtolower($brochure['url']), 0, 2 ) == '//' || 
							substr( strtolower($brochure['url']), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = $property['brochure']['url'];
							$description = ( (isset($brochure['title'])) ? $brochure['title'] : '' );
						    
							$explode_url = explode("?", $url);
							$filename = basename( $explode_url[0] );

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
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['id'], $post_id );
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
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['id'], $post_id );
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

				// No EPCs as I believe this gets sent as a URL to a webpage
				// They do provide EPC ratings that we could look to use in future

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

				$this->log( 'Imported ' . count($media_ids) . ' brochures (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['id'], $post_id );
				
				$virtual_tours = array();
				if ( isset($property['details']['virtual_tour']) && !empty($property['details']['virtual_tour']) )
				{
					$virtual_tours[] = $property['details']['virtual_tour'];
				}
				if ( isset($property['attributes']['property_urls']) && !empty($property['attributes']['property_urls']) && is_array($property['attributes']['property_urls']) )
				{
					foreach ( $property['attributes']['property_urls'] as $property_url )
					{
						if ( 
							isset($property_url['media_type']) && 
							(
								strpos(strtolower($property_url['media_type']), 'virtual') !== FALSE ||
								strpos(strtolower($property_url['media_type']), 'video') !== FALSE ||
								strpos(strtolower($property_url['media_type']), 'tour') !== FALSE
							) &&
							isset($property_url['media_url']) && 
							!empty($property_url['media_url']) &&
							!in_array($property_url['media_url'], $virtual_tours)
						)
						{
							$virtual_tours[] = $property_url['media_url'];
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
				do_action( "houzez_property_feed_property_imported_street", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, $property['id'], $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_street", $this->import_id );

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
				$import_refs[] = $property['id'];
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}