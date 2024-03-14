<?php
/**
 * Class for managing the import process of a Rex JSON file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Rex extends Houzez_Property_Feed_Process {

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

	private function get_token()
	{
		$import_settings = get_import_settings_from_id( $this->import_id );

		$endpoint = '/v1/rex/Authentication/login';

		$data = array(
			'email' => $import_settings['username'],
		    'password' => $import_settings['password'],
		    //'application' => 'rex' // getting error when using this even though it's in the docs
		);

		$data = apply_filters( 'houzez_property_feed_rex_authentication_request_body', $data );

		$data = json_encode($data);

		if ( !$data )
		{
			$this->log_error( 'Failed to encode authentication request data' );
			return false;
		}

		$response = wp_remote_post(
			$import_settings['base_url'] . $endpoint,
			array(
				'body' => $data,
				'headers' => array(
					'Content-Type' => 'application/json'
				),
			)
		);

		if ( is_wp_error($response) )
		{
			$this->log_error( 'WP Error returned in response from authentication' );
			return false;
		}

		$json = json_decode( $response['body'], TRUE );

		if ( !$json )
		{
			$this->log_error( 'Failed to decode authentication response data' );
			return false;
		}

		if ( isset($json['error']) && !empty($json['error']) )
		{
			$this->log_error( 'Error returned in response from authentication: ' . print_r( $json['error'], TRUE ) );
        	return false;
		}

		if ( !isset($json['result']) )
		{
			$this->log_error( 'No result in response from authentication: ' . print_r( $json, TRUE ) );
        	return false;
		}

		// get token from result
		$token = $json['result'];

		return $token;
	}

	public function parse()
	{
		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$token = $this->get_token();

		if ( !$token )
		{
			return false;
		}

		$this->log("Parsing properties");

		$import_settings = get_import_settings_from_id( $this->import_id );

		$limit = 100;

		$endpoint = '/v1/rex/published-listings/search';

		$data = array(
			'result_format' => 'website_overrides_applied',
			'extra_options' => array(
				'extra_fields' => array( 'documents', 'highlights', 'links', 'rooms', 'images', 'floorplans', 'epc', 'tags', 'features', 'advert_internet', 'advert_brochure', 'advert_stocklist', 'subcategories' ),
			),
			'criteria' => array(
				array(
					"name" => "listing.system_listing_state", 
					"type" => "notin",
					"value" => array("withdrawn")
				),
				array(
					"name" => "listing.publish_to_external", 
					"type" => "=",
					"value" => true
				)
			),
			'limit' => $limit,
			'order_by' => array('system_publication_time' => 'desc')
		);

		$data = apply_filters( 'houzez_property_feed_rex_property_request_body', $data );

		$page = 1;
		$found_results = true;

		while ( $found_results && $page < 99 )
		{
			$offset = ( $page - 1 ) * $limit;
			$data['offset'] = $offset;

			$body = json_encode($data);

			if ( !$body )
			{
				$this->log_error( 'Failed to encode property request data' );
				return false;
			}

			$response = wp_remote_post(
				$import_settings['base_url'] . $endpoint,
				array(
					'body' => $body,
					'headers' => array(
						'Content-Type' => 'application/json',
						'Authorization' => 'Bearer ' . $token
					),
					'timeout' => 120,
				)
			);

			if ( is_wp_error($response) )
			{
				$this->log_error( 'WP Error returned in response from properties:' . $response->get_error_message() );
				return false;
			}

			$json = json_decode( $response['body'], TRUE );

			if ( !$json )
			{
				$this->log_error( 'Failed to decode property response data' );
				return false;
			}

			if ( isset($json['error']) && !empty($json['error']) )
			{
				$this->log_error( 'Error returned in response from properties: ' . print_r( $json['error'], TRUE ) );
	        	return false;
			}

			if ( !isset($json['result']) )
			{
				$this->log_error( 'No result in response from properties: ' . print_r( $json, TRUE ) );
	        	return false;
			}

			if ( is_array($json['result']['rows']) )
			{
				if ( !empty($json['result']['rows']) )
				{
					$this->log("Found " . count($json['result']['rows']) . " properties in JSON on page " . $page . " ready for parsing");

					foreach ($json['result']['rows'] as $property)
					{
						$this->properties[] = $property;
					}
				}
				else
				{
					$found_results = false;
				}
	        }
	        else
	        {
	        	// Failed to parse JSON
	        	$this->log_error( 'Rows missing or empty from property response' );
	        	return false;
	        }

	        ++$page;
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
        do_action( "houzez_property_feed_pre_import_properties_rex", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_rex", $this->properties, $this->import_id );

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

	        $display_address = array();
	        if ( isset($property['address']['formats']['display_address']) && trim($property['address']['formats']['display_address']) != '' )
	        {
	        	$display_address = $property['address']['formats']['display_address'];
	        }
	        else
	        {
		        if ( isset($property['address']['street_name']) && trim($property['address']['street_name']) != '' )
		        {
		        	$display_address[] = trim($property['address']['street_name']);
		        }
		        if ( isset($property['address']['locality']) && trim($property['address']['locality']) != '' )
		        {
		        	$display_address[] = trim($property['address']['locality']);
		        }
		        elseif ( isset($property['address']['suburb_or_town']) && trim($property['address']['suburb_or_town']) != '' )
		        {
		        	$display_address[] = trim($property['address']['suburb_or_town']);
		        }
		        $display_address = implode(", ", $display_address);
		    }

			$post_content = '';
			if ( isset($property['advert_internet']) && isset($property['advert_internet']['body']) && $property['advert_internet']['body'] != '' )
	        {
	        	$post_content .= '<p>' . $property['advert_internet']['body'] . '</p>';
	        }
	        elseif ( isset($property['advert_brochure']) && isset($property['advert_brochure']['body']) && $property['advert_brochure']['body'] != '' )
	        {
	        	$post_content .= '<p>' . $property['advert_brochure']['body'] . '</p>';
	        }

	        if ( isset($property['rooms']) && is_array($property['rooms']) && !empty($property['rooms']) )
	        {
	        	foreach ( $property['rooms'] as $room )
	        	{
	        		$room_content = ( isset($room['room_type']) && !empty($room['room_type']) ) ? '<strong>' . $room['room_type'] . '</strong>' : '';
					$room_content .= ( isset($room['dimensions']) && !empty($room['dimensions']) ) ? ' (' . $room['dimensions'] . ')' : '';
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

	        if ( isset($property['disclaimer_text']) && $property['disclaimer_text'] != '' )
	        {
	        	$post_content .= '<p>' . $property['disclaimer_text'] . '</p>';
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
				    	'post_excerpt'   => $property['advert_internet']['heading'],
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
					'post_excerpt'   => $property['advert_internet']['heading'],
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

				$department = 'residential-sales';
				if ( isset($property['listing_category_id']) )
				{
					switch ( $property['listing_category_id'] )
					{
						case "residential_letting":
						case "residential_rental":
						case "commercial_rental":
						{
							$department = 'residential-lettings';
							break;
						}
					}
				}

				$poa = false;
				if ( 
					( isset($property['state_hide_price']) && $property['state_hide_price'] == '1' ) || 
					( isset($property['price_advertise_as']) && strtolower($property['price_advertise_as']) == 'price on application' ) 
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
                		$price = round(preg_replace("/[^0-9.]/", '', $property['price_match']));

                		$price_qualifier = '';
                		$explode_price = explode("£", trim($property['price_advertise_as']));
						if ( count($explode_price) == 2 )
						{
							$price_qualifier = trim($explode_price[0]);
						}

	                    update_post_meta( $post_id, 'fave_property_price_prefix', $price_qualifier );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
	                }
	                elseif ( $department == 'residential-lettings' )
	                {
	                	$price = round(preg_replace("/[^0-9.]/", '', $property['price_rent']));

	                	$rent_frequency = 'pcm';

						if ( isset($property['price_rent_period']) )
						{
							if ( strpos(strtolower($property['price_rent_period']), 'week') !== FALSE || strpos(strtolower($property['price_rent_period']), 'pw') !== FALSE )
							{
								$rent_frequency = 'pw'; 
							}
							elseif ( strpos(strtolower($property['price_rent_period']), 'ann') !== FALSE || strpos(strtolower($property['price_rent_period']), 'pa') !== FALSE )
							{
								$rent_frequency = 'pa';
							}
						}

	                	update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
	                }
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property['attributes']['bedrooms']) ) ? $property['attributes']['bedrooms'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property['attributes']['bathrooms']) ) ? $property['attributes']['bathrooms'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property['attributes']['living_areas']) ) ? $property['attributes']['living_areas'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' );
	            update_post_meta( $post_id, 'fave_property_id', $property['id'] );

	            $address_parts = array();
	            if ( isset($property['address']['street_name']) && $property['address']['street_name'] != '' )
	            {
	                $address_parts[] = $property['address']['street_name'];
	            }
	            if ( isset($property['address']['locality']) && $property['address']['locality'] != '' )
	            {
	                $address_parts[] = $property['address']['locality'];
	            }
	            if ( isset($property['address']['suburb_or_town']) && $property['address']['suburb_or_town'] != '' )
	            {
	                $address_parts[] = $property['address']['suburb_or_town'];
	            }
	            if ( isset($property['address']['state_or_region']) && $property['address']['state_or_region'] != '' )
	            {
	                $address_parts[] = $property['address']['state_or_region'];
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
	            if ( isset($property['address']['street_name']) && $property['address']['street_name'] != '' )
	            {
	                $address_parts[] = $property['address']['street_name'];
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property['address']['postcode']) ) ? $property['address']['postcode'] : '' ) );

	            $featured = '0';
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
		            				case "listing_agent_name":
		            				{
		            					$value_in_feed_to_check = $property['listing_agent_1']['name'];
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
		            				case "listing_agent_name":
		            				{
		            					$value_in_feed_to_check = $property['listing_agent_1']['name'];
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
		            				case "listing_agent_name":
		            				{
		            					$value_in_feed_to_check = $property['listing_agent_1']['name'];
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
	            if ( isset($property['features']) && is_array($property['features']) && !empty($property['features']) )
				{
					foreach ( $property['features'] as $feature )
					{
						$term = term_exists( trim($feature), 'property_feature');
						if ( $term !== 0 && $term !== null && isset($term['term_id']) )
						{
							$feature_term_ids[] = (int)$term['term_id'];
						}
						else
						{
							$term = wp_insert_term( trim($feature), 'property_feature' );
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

				update_post_meta( $post_id, 'fave_epc_current_rating', ( ( isset($property['epc']['current_eer']) ) ? $property['epc']['current_eer'] : '' ) );
				update_post_meta( $post_id, 'fave_epc_potential_rating', ( ( isset($property['epc']['potential_eer']) ) ? $property['epc']['potential_eer'] : '' ) );

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				$availability = isset($property['project_listing_status']) ? $property['project_listing_status'] : '';
				if ( $availability == '' || $availability === null )
				{
					$availability = 'Available';
					if ( $department == 'residential-lettings' && isset($property['let_agreed']) && $property['let_agreed'] == 1 )
					{
						$availability = 'Let Agreed';
					}
				}
				if ( isset($property['system_listing_status']) && strtolower($property['system_listing_status']) == 'sold' )
				{
					$availability = 'Sold';
				}

				if ( !empty($availability) )
				{
					if ( isset($taxonomy_mappings[$availability]) && !empty($taxonomy_mappings[$availability]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$availability], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . $availability . ' that isn\'t mapped in the import settings', $property['id'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, $availability, $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				$term_ids = array();

				if ( isset($property['subcategories']) && is_array($property['subcategories']) && !empty($property['subcategories']) )
				{
					foreach ( $property['subcategories'] as $subcategory )
					{
						if ( !empty($taxonomy_mappings) && isset($taxonomy_mappings[$subcategory]) )
						{
							$term_ids[] = (int)$taxonomy_mappings[$subcategory];
						}
						else
						{
							$this->log( 'Received property type of ' . $subcategory . ' that isn\'t mapped in the import settings', $property['id'], $post_id );

							$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $subcategory, $this->import_id );
						}
					}
				}

				if ( !empty($term_ids) )
				{
					wp_set_post_terms( $post_id, $term_ids, 'property_type' );
				}
				else
				{
					wp_delete_object_term_relationships( $post_id, 'property_type' );
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
				$queued = 0;
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

				if ( isset($property['images']) && is_array($property['images']) && !empty($property['images']) )
				{
					foreach ( $property['images'] as $image )
					{
						$url = $image['url'];
						if ( substr($url, 0, 2) == '//' )
						{
							$url = 'https:' . $url;
						}

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
							$description = '';
							$modified = $image['modtime'];
						    
						    $explode_url = explode('?', $url);

						    // does it have an extension
						    $filename = basename( $explode_url[0] );
							if ( !preg_match('/\.(jpg|jpeg|png|gif|bmp|svg)$/i', $explode_url[0]) ) 
							{
							    $filename .= '.jpg';
							}

							$max_length = 100; // Define a safe limit considering file system and other constraints
						    
						    if ( strlen($filename) > $max_length ) 
						    {
						    	$extension = pathinfo($filename, PATHINFO_EXTENSION);
						    	$name_without_extension = pathinfo($filename, PATHINFO_FILENAME);

						        $name_without_extension = substr($name_without_extension, 0, $max_length - strlen($extension) - 1);
						        $filename = $name_without_extension . '.' . $extension;
						    }
							
							// Check, based on the URL, whether we have previously imported this media
							$imported_previously = false;
							$imported_previously_id = '';
							if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
							{
								foreach ( $previous_media_ids as $previous_media_id )
								{
									if ( 
										get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $url &&
										get_post_meta( $previous_media_id, '_modified', TRUE ) == $modified
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
								if ( apply_filters( 'houzez_property_feed_import_media', true, $this->import_id, $post_id, $property['id'], $explode_url[0], $url, $description, 'image', $image_i, $modified ) === true )
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
									    	update_post_meta( $id, '_modified', $modified);

									    	if ( $image_i == 0 ) set_post_thumbnail( $post_id, $id );

									    	++$new;

									    	++$image_i;

									    	update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
									    }
									}
								}
								else
								{
									++$queued;
									++$image_i;
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
				if ( $queued > 0 ) 
				{
					$this->log( $queued . ' photos added to download queue', $property['id'], $post_id );
				}

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if ( isset($property['floorplans']) && is_array($property['floorplans']) && !empty($property['floorplans']) )
				{
					foreach ( $property['floorplans'] as $floorplan )
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
							$description = __( 'Floorplan', 'houzezpropertyfeed' );

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
				$attachment_i = 0;
				$queued = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				if ( isset($property['documents']) && is_array($property['documents']) && !empty($property['documents']) )
				{
					foreach ( $property['documents'] as $document )
					{
						if ( 
							substr( strtolower($document['url']), 0, 2 ) == '//' || 
							substr( strtolower($document['url']), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = $document['url'];
							if ( substr($url, 0, 2) == '//' )
							{
								$url = 'https:' . $url;
							}
							$description = __( 'Brochure', 'houzezpropertyfeed' );
						    
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

								++$existing;

								++$attachment_i;
							}
							else
							{
								if ( apply_filters( 'houzez_property_feed_import_media', true, $this->import_id, $post_id, $property['id'], $url, $url, $description, 'brochure', $attachment_i, '' ) === true )
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

									    	++$attachment_i;
									    }
									}
								}
								else
								{
									++$queued;
									++$attachment_i;
								}
							}
						}
					}
				}

				if ( 
					isset($property['epc']['combined_chart_url']) && $property['epc']['combined_chart_url'] != ''
					&&
					(
						substr( strtolower($property['epc']['combined_chart_url']), 0, 2 ) == '//' || 
						substr( strtolower($property['epc']['combined_chart_url']), 0, 4 ) == 'http'
					)
				)
				{
					// This is a URL
					$url = $property['epc']['combined_chart_url'];
					if ( substr($url, 0, 2) == '//' )
					{
						$url = 'https:' . $url;
					}
					$description = __( 'EPC', 'houzezpropertyfeed' );
				    
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

						++$existing;

						++$attachment_i;
					}
					else
					{
						if ( apply_filters( 'houzez_property_feed_import_media', true, $this->import_id, $post_id, $property['id'], $url, $url, $description, 'epc', $attachment_i, '' ) === true )
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
                                    'post_title' => __( 'EPC', 'houzezpropertyfeed' ),
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

							    	++$attachment_i;
							    }
							}
						}
						else
						{
							++$queued;
							++$attachment_i;
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

				$this->log( 'Imported ' . count($media_ids) . ' brochures (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['id'], $post_id );
				if ( $queued > 0 ) 
				{
					$this->log( $queued . ' brochures and EPCs added to download queue', $property['id'], $post_id );
				}

				$virtual_tours = array();
				if ( isset($property['links']) && is_array($property['links']) && !empty($property['links']) )
				{
					foreach ( $property['links'] as $link )
					{
						if ( 
							isset($link['link_url']) && $link['link_url'] != ''
							&&
							(
								substr( strtolower($link['link_url']), 0, 2 ) == '//' || 
								substr( strtolower($link['link_url']), 0, 4 ) == 'http'
							)
							&&
							isset($link['link_type']) && ( $link['link_type'] == 'virtual_tour' || $link['link_type'] == 'video_link' )
						)
						{
							$virtual_tours[] = $link['link_url'];
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
				do_action( "houzez_property_feed_property_imported_rex", $post_id, $property, $this->import_id );

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

		do_action( "houzez_property_feed_post_import_properties_rex", $this->import_id );

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