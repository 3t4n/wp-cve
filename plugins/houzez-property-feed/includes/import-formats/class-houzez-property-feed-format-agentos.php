<?php
/**
 * Class for managing the import process of a agentOS JSON file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Agentos extends Houzez_Property_Feed_Process {

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

		$requests = 0;
		$requests_per_chunk = apply_filters( 'houzez_property_feed_agentos_requests_per_chunk', 10 );
		$sleep_seconds = apply_filters( 'houzez_property_feed_agentos_sleep_seconds', 5 );

		$branches_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/company/branches/0/1000';
		$fields = array(
			'api_key' => urlencode($import_settings['api_key']),
		);

		$fields_string = '';
		foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
		$fields_string = rtrim($fields_string, '&');

		$branches_url = $branches_url . '?' . $fields_string;

		$response = wp_remote_get( $branches_url, array( 'timeout' => 120 ) );

		++$requests;
		if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

		if ( is_array($response) && isset($response['body']) ) 
		{
			$branches_json = json_decode($response['body'], TRUE);

			if ( $branches_json === FALSE || is_null($branches_json) )
			{
				$this->log_error("Failed to parse branches JSON: " . $response['body']);
				return false;
			}

			$branches = $branches_json['Data'];

			$this->log("Found " . count($branches) . " branches");

			foreach ( $branches as $branch )
			{
				$this->log("Obtaining properties for branch " . $branch['Name'] . " (" . $branch['OID'] . ")");
				
				// Sales Properties
				$sales_instructions = array();
				$sales_instructions_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/sales/advertised/0/1000';
				$fields = array(
					'api_key' => urlencode($import_settings['api_key']),
					'branchID' => $branch['OID'],
					'onlyDevelopement' => 'false',
					'onlyInvestements' => 'false',
				);

				$fields_string = '';
				foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
				$fields_string = rtrim($fields_string, '&');

				$sales_instructions_url = $sales_instructions_url . '?' . $fields_string;

				$response = wp_remote_get( $sales_instructions_url, array( 'timeout' => 120 ) );

				++$requests;
				if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

				if ( is_array($response) && isset($response['body']) ) 
				{
					$sales_instructions_json = json_decode($response['body'], TRUE);

					if ( $sales_instructions_json === FALSE || is_null($sales_instructions_json) )
					{
						$this->log_error("Failed to parse sales properties summary JSON: " . $response['body']);
						return false;
					}
					else
					{
						$sales_instructions = $sales_instructions_json['Data'];

						$this->log("Found " . count($sales_instructions) . " sales instructions");

						foreach ( $sales_instructions as $property )
						{
							// Get sales instruction data
							$property_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/sales/salesinstructions/' . $property['OID'];
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_url = $property_url . '?' . $fields_string;

							$response = wp_remote_get( $property_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_json = json_decode($response['body'], TRUE);

								if ( $property_json === FALSE || is_null($property_json) )
								{
									$this->log_error("Failed to parse full sales data JSON: " . $response['body'], $property['OID']);
									return false;
								}
								else
								{
									$property = array_merge($property, $property_json);
									//$property['State'] = $property_json['State'];

									$property['department'] = 'residential-sales';
								}
							}
							else
							{
								$this->log_error("Failed to obtain full sales JSON: " . print_r($response, TRUE), $property['OID']);
								return false;
							}

							// Get features
							$features = array();

							$property_features_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/sales/salesinstructions/' . $property['OID'] . '/features/0/1000';
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_features_url = $property_features_url . '?' . $fields_string;

							$response = wp_remote_get( $property_features_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_features_json = json_decode($response['body'], TRUE);

								if ( $property_features_json === FALSE || is_null($property_features_json) )
								{
									$this->log_error("Failed to parse property features JSON: " . $response['body'], $property['OID']);
									return false;
								}
								else
								{
									$property_features = $property_features_json['Data'];

									foreach ( $property_features as $property_feature )
									{
										$features[] = $property_feature['Name'];
									}
								}
							}
							else
							{
								$this->log_error("Failed to obtain property features JSON: " . print_r($response, TRUE), $property['OID']);
								return false;
							}

							// Get floorplans
							$floorplans = array();

							$property_floorplans_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/sales/salesinstructions/' . $property['OID'] . '/floorplans/0/1000';
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_floorplans_url = $property_floorplans_url . '?' . $fields_string;

							$response = wp_remote_get( $property_floorplans_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_floorplans_json = json_decode($response['body'], TRUE);

								if ( $property_floorplans_json === FALSE || is_null($property_floorplans_json) )
								{
									$this->log_error("Failed to parse property floorplans JSON: " . $response['body'], $property['OID']);
									return false;
								}
								else
								{
									$property_floorplans = $property_floorplans_json['Data'];

									foreach ( $property_floorplans as $property_floorplan )
									{
										$floorplans[] = $property_floorplan;
									}
								}
							}
							else
							{
								$this->log_error("Failed to obtain property floorplans JSON: " . print_r($response, TRUE), $property['OID']);
								return false;
							}

							// Get photos
							$photos = array();

							$property_photos_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/sales/salesinstructions/' . $property['OID'] . '/photos/0/1000';
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_photos_url = $property_photos_url . '?' . $fields_string;

							$response = wp_remote_get( $property_photos_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_photos_json = json_decode($response['body'], TRUE);

								if ( $property_photos_json === FALSE || is_null($property_photos_json) )
								{
									$this->log_error("Failed to parse property photos JSON: " . $response['body'], $property['OID']);
									return false;
								}
								else
								{
									$property_photos = $property_photos_json['Data'];

									foreach ( $property_photos as $property_photo )
									{
										$photos[] = $property_photo;
									}
								}
							}
							else
							{
								$this->log_error("Failed to obtain property photos JSON: " . print_r($response, TRUE), $property['OID']);
								return false;
							}

							// Get rooms
							$rooms = array();

							$property_rooms_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/sales/salesinstructions/' . $property['OID'] . '/rooms/0/1000';
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_rooms_url = $property_rooms_url . '?' . $fields_string;

							$response = wp_remote_get( $property_rooms_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_rooms_json = json_decode($response['body'], TRUE);

								if ( $property_rooms_json === FALSE || is_null($property_rooms_json) )
								{
									$this->log_error("Failed to parse property rooms JSON: " . $response['body'], $property['OID']);
									return false;
								}
								else
								{
									$property_rooms = $property_rooms_json['Data'];

									foreach ( $property_rooms as $property_room )
									{
										$rooms[] = $property_room;
									}
								}
							}
							else
							{
								$this->log_error("Failed to obtain property rooms JSON: " . print_r($response, TRUE), $property['OID']);
								return false;
							}

							$property['features'] = $features;
							$property['floorplans'] = $floorplans;
							$property['photos'] = $photos;
							$property['rooms'] = $rooms;

							if (!isset($property['BranchOID'])) { $property['BranchOID'] = $branch['OID']; }

							$this->properties[] = $property;
						}
					}
				}
				else
				{
					$this->log_error("Failed to obtain sales properties summary JSON: " . print_r($response, TRUE));
					return false;
				}

				// Lettings Properties
				$lettings_instructions_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/lettings/advertised/0/1000';
				$fields = array(
					'api_key' => urlencode($import_settings['api_key']),
					'branchID' => $branch['OID'],
				);

				$fields_string = '';
				foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
				$fields_string = rtrim($fields_string, '&');

				$lettings_instructions_url = $lettings_instructions_url . '?' . $fields_string;

				$response = wp_remote_get( $lettings_instructions_url, array( 'timeout' => 120 ) );

				++$requests;
				if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

				if ( is_array($response) && isset($response['body']) ) 
				{
					$lettings_instructions_json = json_decode($response['body'], TRUE);

					if ( $lettings_instructions_json === FALSE || is_null($lettings_instructions_json) )
					{
						$this->log_error("Failed to parse lettings properties summary JSON: " . $response['body']);
						return false;
					}
					else
					{
						$lettings_instructions = $lettings_instructions_json['Data'];

						$this->log("Found " . count($lettings_instructions) . " lettings properties");

						foreach ( $lettings_instructions as $property )
						{
							// Get full lettings data
							$property_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/lettings/advertised/' . $property['OID'];
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_url = $property_url . '?' . $fields_string;

							$response = wp_remote_get( $property_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_json = json_decode($response['body'], TRUE);

								if ( $property_json === FALSE || is_null($property_json) )
								{
									$this->log_error("Failed to parse full lettings data JSON: " . $response['body'], $property['PropertyID']);
									return false;
								}
								else
								{
									$property = array_merge($property, $property_json);

									$property['department'] = 'residential-lettings';
								}
							}
							else
							{
								$this->log_error("Failed to obtain full lettings JSON: " . print_r($response, TRUE), $property['PropertyID']);
								return false;
							}

							// Get full property data
							$property_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/lettings/properties/' . $property['PropertyID'];
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_url = $property_url . '?' . $fields_string;

							$response = wp_remote_get( $property_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_json = json_decode($response['body'], TRUE);

								if ( $property_json === FALSE || is_null($property_json) )
								{
									$this->log_error("Failed to parse full property JSON: " . $response['body'], $property['PropertyID']);
									return false;
								}
								else
								{
									$property = array_merge($property, $property_json);
								}
							}
							else
							{
								$this->log_error("Failed to obtain full property JSON: " . print_r($response, TRUE), $property['PropertyID']);
								return false;
							}

							// Get features
							$features = array();

							$property_features_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/lettings/properties/' . $property['PropertyID'] . '/facilities/0/1000';
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_features_url = $property_features_url . '?' . $fields_string;

							$response = wp_remote_get( $property_features_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_features_json = json_decode($response['body'], TRUE);

								if ( $property_features_json === FALSE || is_null($property_features_json) )
								{
									$this->log_error("Failed to parse property features JSON: " . $response['body'], $property['PropertyID']);
									return false;
								}
								else
								{
									$property_features = $property_features_json['Data'];

									foreach ( $property_features as $property_feature )
									{
										$features[] = $property_feature['Name'];
									}
								}
							}
							else
							{
								$this->log_error("Failed to obtain property features JSON: " . print_r($response, TRUE), $property['PropertyID']);
								return false;
							}

							// Get photos
							$photos = array();

							$property_photos_url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/lettings/properties/' . $property['PropertyID'] . '/photos/0/1000';
							$fields = array(
								'api_key' => urlencode($import_settings['api_key']),
							);

							$fields_string = '';
							foreach ($fields as $key => $value) { $fields_string .= $key . '=' . $value . '&'; }
							$fields_string = rtrim($fields_string, '&');

							$property_photos_url = $property_photos_url . '?' . $fields_string;

							$response = wp_remote_get( $property_photos_url, array( 'timeout' => 120 ) );

							++$requests;
							if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }

							if ( is_array($response) && isset($response['body']) ) 
							{
								$property_photos_json = json_decode($response['body'], TRUE);

								if ( $property_photos_json === FALSE || is_null($property_photos_json) )
								{
									$this->log_error("Failed to parse property photos JSON: " . $response['body'], $property['PropertyID']);
									return false;
								}
								else
								{
									$property_photos = $property_photos_json['Data'];

									foreach ( $property_photos as $property_photo )
									{
										$photos[] = $property_photo;
									}
								}
							}
							else
							{
								$this->log_error("Failed to obtain property photos JSON: " . print_r($response, TRUE), $property['PropertyID']);
								return false;
							}

							$property['features'] = $features;
							$property['photos'] = $photos;

							if (!isset($property['BranchOID'])) { $property['BranchOID'] = $branch['OID']; }

							$this->properties[] = $property;
						}
					}
				}
				else
				{
					$this->log_error("Failed to obtain lettings properties summary JSON: " . print_r($response, TRUE));
					return false;
				}
			}
		}
		else
		{
			$this->log_error("Failed to obtain branches JSON: " . print_r($response, TRUE));
			return false;
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
        do_action( "houzez_property_feed_pre_import_properties_agentos", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_agentos", $this->properties, $this->import_id );

        $limit = apply_filters( "houzez_property_feed_property_limit", 25 );
        $additional_message = '';
        if ( $limit !== false )
        {
        	$this->properties = array_slice( $this->properties, 0, $limit );
        	$additional_message = '. <a href="https://houzezpropertyfeed.com/#pricing" target="_blank">Upgrade to PRO</a> to import unlimited properties';
        }

        $requests = 0;
		$requests_per_chunk = apply_filters( 'houzez_property_feed_agentos_requests_per_chunk', 10 );
		$sleep_seconds = apply_filters( 'houzez_property_feed_agentos_sleep_seconds', 5 );

		$this->log( 'Beginning to loop through ' . count($this->properties) . ' properties' . $additional_message );

		$start_at_property = get_option( 'houzez_property_feed_property_' . $this->import_id );

		$property_row = 1;
		foreach ( $this->properties as $property )
		{
			if ( !empty($start_at_property) )
			{
				// we need to start on a certain property
				if ( $property['OID'] == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . $property['OID'] );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, $property['OID'], false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . $property['OID'], $property['OID'] );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => $property['OID']
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = array();
	        if ( isset($property['Address1']) && trim($property['Address1']) != '' )
	        {
	        	$display_address[] = trim($property['Address1']);
	        }
	        if ( isset($property['Address2']) && trim($property['Address2']) != '' )
	        {
	        	$display_address[] = trim($property['Address2']);
	        }
	        if ( isset($property['Address3']) && trim($property['Address3']) != '' )
	        {
	        	$display_address[] = trim($property['Address3']);
	        }
	        $display_address = implode(", ", $display_address);
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', $property['OID'] );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => $property['Description'],
				    	'post_content' 	 => $property['Description'],
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), $property['OID'] );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', $property['OID'] );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => $property['Description'],
					'post_content' 	 => $property['Description'],
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), $property['OID'] );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', $property['OID'], $post_id );

				update_post_meta( $post_id, $imported_ref_key, $property['OID'] );

				update_post_meta( $post_id, '_property_import_data', json_encode($property, JSON_PRETTY_PRINT) );

				$department = $property['department'];

				$poa = false;
				if ( 
					( ( isset($property['POA']) && $property['POA'] === true ) ? 'yes' : '' )
					||
					( ( isset($property['PoA']) && $property['PoA'] === true ) ? 'yes' : '' )
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
                		if ( isset($property['Price']) && !empty($property['Price']) )
                		{
	                		$price = round(preg_replace("/[^0-9.]/", '', $property['Price']));
	                	}
	                    update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
	                }
	                elseif ( $department == 'residential-lettings' )
	                {
	                	$price = '';
	                	if ( isset($property['RentAdvertised']) && !empty($property['RentAdvertised']) )
						{
							$price = round(preg_replace("/[^0-9.]/", '', $property['RentAdvertised']));
						}
	                	update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );

	                    $rent_frequency = 'pcm';
						switch ($property['RentSchedule'])
						{
							case "Weekly": { $rent_frequency = 'pw'; break; }
							case "Monthly": { $rent_frequency = 'pcm'; break; }
							case "Quarterly": { $rent_frequency = 'pq'; break; }
							case "Yearly": { $rent_frequency = 'pa'; break; }
						}
	                    update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
	                }
                }

                $bedrooms = isset($property['Bedrooms']) ? $property['Bedrooms'] : ( isset($property['BedroomCount']) ? $property['BedroomCount'] : '' );
                update_post_meta( $post_id, 'fave_property_bedrooms', $bedrooms );

                $bathrooms = isset($property['Bathrooms']) ? $property['Bathrooms'] : ( isset($property['BathroomCount']) ? $property['BathroomCount'] : '' );
	            update_post_meta( $post_id, 'fave_property_bathrooms', $bathrooms );
	            
	            $reception_rooms = isset($property['ReceptionRooms']) ? $property['ReceptionRooms'] : ( isset($property['ReceptionCount']) ? $property['ReceptionCount'] : '' );
	            update_post_meta( $post_id, 'fave_property_rooms', $reception_rooms );
	            
	            $parking = array();
	            if ( isset($property['ParkingType']) && !empty($property['ParkingType']) )
				{
					foreach ( $property['ParkingType'] as $parking_type )
					{
						$parking[] = $parking_type;
					}
				}
	            update_post_meta( $post_id, 'fave_property_garage', implode(", ", $parking) );
	            update_post_meta( $post_id, 'fave_property_id', $property['GlobalReference'] );

	            $address_parts = array();
	            $address_to_geocode_osm = array();
	            if ( isset($property['Address1']) && $property['Address1'] != '' )
	            {
	                $address_parts[] = $property['Address1'];
	            }
	            if ( isset($property['Address2']) && $property['Address2'] != '' )
	            {
	                $address_parts[] = $property['Address2'];
	            }
	            if ( isset($property['Address3']) && $property['Address3'] != '' )
	            {
	                $address_parts[] = $property['Address3'];
	            }
	            if ( isset($property['Address4']) && $property['Address4'] != '' )
	            {
	                $address_parts[] = $property['Address4'];
	            }
	            if ( isset($property['Postcode']) && $property['Postcode'] != '' )
	            {
	                $address_parts[] = $property['Postcode'];
	                $address_to_geocode_osm[] = $property['Postcode'];
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' ); // set to 0 as we don't get lat/lng through in the feee
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $lat = '';
	            $lng = '';
	            if ( empty($lat) || empty($lng) )
	            {
	            	// use existing
	            	$lat = get_post_meta( $post_id, 'houzez_geolocation_lat', true );
	            	$lng = get_post_meta( $post_id, 'houzez_geolocation_long', true );

	            	if ( empty($lat) || empty($lng) )
	            	{
	            		// need to geocode
	            		$geocoding_return = $this->do_geocoding_lookup( $post_id, $property['OID'], $address_parts, $address_to_geocode_osm, 'GB' );
						if ( is_array($geocoding_return) && !empty($geocoding_return) && count($geocoding_return) == 2 )
						{
							$lat = $geocoding_return[0];
	            			$lng = $geocoding_return[1];
						}
	            	}
	            }
	            update_post_meta( $post_id, 'fave_property_location', $lat . "," . $lng . ",14" );
	            update_post_meta( $post_id, 'fave_property_country', 'GB' );
	            
	            $address_parts = array();
	            if ( isset($property['Address1']) && $property['Address1'] != '' )
	            {
	                $address_parts[] = $property['Address1'];
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property['Postcode']) ) ? $property['Postcode'] : '' ) );

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
		            				default:
		            				{
		            					$value_in_feed_to_check = isset($property[$rule['field']]) ? $property[$rule['field']] : '';
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
		            				default:
		            				{
		            					$value_in_feed_to_check = isset($property[$rule['field']]) ? $property[$rule['field']] : '';
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
		            				default:
		            				{
		            					$value_in_feed_to_check = isset($property[$rule['field']]) ? $property[$rule['field']] : '';
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
	            if ( isset($property['features']) && is_array($property['features']) )
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

				update_post_meta( $post_id, 'fave_epc_current_rating', ( ( isset($property['EPCCurrentEER']) ) ? $property['EPCCurrentEER'] : '' ) );
				update_post_meta( $post_id, 'fave_epc_potential_rating', ( ( isset($property['EPCPotentialEER']) ) ? $property['EPCPotentialEER'] : '' ) );

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				$availability = '';
				if ( $property['department'] == 'residential-sales' )
				{
					$availability = 'For Sale';
					if ( isset($property['State']) && $property['State'] == 'UnderOffer' )
					{
						$availability = 'Under Offer';
					}
				}
				elseif ( $property['department'] == 'residential-lettings' )
				{
					$availability = 'To Let';
					if ( isset($property['IsTenancyProposed']) && $property['IsTenancyProposed'] === TRUE )
					{
						$availability = 'Let Agreed';
					}
				}

				if ( $availability != '' )
				{
					if ( isset($taxonomy_mappings[$availability]) && !empty($taxonomy_mappings[$availability]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$availability], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . $availability . ' that isn\'t mapped in the import settings', $property['OID'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, $availability, $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property['PropertyType']) && !empty($property['PropertyType']) )
				{
					if ( isset($taxonomy_mappings[$property['PropertyType']]) && !empty($taxonomy_mappings[$property['PropertyType']]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['PropertyType']], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . $property['PropertyType'] . ' that isn\'t mapped in the import settings', $property['OID'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $property['PropertyType'], $this->import_id );
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
						if ( isset($property[$address_field_to_use]) && !empty($property[$address_field_to_use]) )
		            	{
		            		$term = term_exists( trim($property[$address_field_to_use]), $location_taxonomy);
							if ( $term !== 0 && $term !== null && isset($term['term_id']) )
							{
								$location_term_ids[] = (int)$term['term_id'];
							}
							else
							{
								if ( $create_location_taxonomy_terms === true )
								{
									$term = wp_insert_term( trim($property[$address_field_to_use]), $location_taxonomy );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', $property['OID'], $post_id );
						}
					}
				}

				if ( isset($property['photos']) && !empty($property['photos']) )
				{
					foreach ( $property['photos'] as $image )
					{
						if ( isset($image['PhotoType']) && strtolower($image['PhotoType']) == 'photo' )
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

							$url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/download/' . $image['OID'] . '?api_key=' . urlencode($import_settings['api_key']);
							$description = $image['Name'];
							$etag = $image['ETag'];
						    
							$filename = $image['OID'] . '.jpg';;

							// Check, based on the URL, whether we have previously imported this media
							$imported_previously = false;
							$imported_previously_id = '';
							if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
							{
								foreach ( $previous_media_ids as $previous_media_id )
								{
									if ( 
										get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $url . $etag
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
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['OID'], $post_id );
							    }
							    else
							    {
								    $id = media_handle_sideload( $file_array, $post_id, $description );

								    // Check for handle sideload errors.
								    if ( is_wp_error( $id ) ) 
								    {
								        @unlink( $file_array['tmp_name'] );
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['OID'], $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $url . $etag );

								    	if ( $image_i == 0 ) set_post_thumbnail( $post_id, $id );

								    	++$new;

								    	++$image_i;

								    	update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
								    }
								}

								++$requests;
								if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['OID'], $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if ( isset($property['photos']) && !empty($property['photos']) )
				{
					foreach ( $property['photos'] as $image )
					{
						if ( isset($image['PhotoType']) && strtolower($image['PhotoType']) == 'floorplan' )
						{
							$url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/download/' . $image['OID'] . '?api_key=' . urlencode($import_settings['api_key']);
							$description = ( ( isset($image['Name']) && !empty($image['Name']) ) ? $image['Name'] : __( 'Floorplan', 'houzezpropertyfeed' ) );

							$floorplans[] = array( 
								"fave_plan_title" => $description, 
								"fave_plan_image" => $url
							);
						}
					}
				}

				if ( isset($property['floorplans']) && !empty($property['floorplans']) )
				{
					foreach ( $property['floorplans'] as $image )
					{
						if ( 
							isset($image['PhotoType']) && strtolower($image['PhotoType']) == 'floorplan'
						)
						{
							$url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/download/' . $image['OID'] . '?api_key=' . urlencode($import_settings['api_key']);
							$description = ( ( isset($image['Name']) && !empty($image['Name']) ) ? $image['Name'] : __( 'Floorplan', 'houzezpropertyfeed' ) );

							$floorplans[] = array( 
								"fave_plan_title" => $description, 
								"fave_plan_image" => $url
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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', $property['OID'], $post_id );

				$unique_id_to_use_for_epcs = $property['OID'];
				if ( $property['department'] == 'residential-lettings' )
				{
					$unique_id_to_use_for_epcs = $property['PropertyID'];
				}

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				$url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/download/' . $unique_id_to_use_for_epcs . '/brochure?api_key=' . urlencode($import_settings['api_key']);
				$description = __( 'Brochure', 'houzezpropertyfeed' );

				$filename = $property['OID'] . '-brochure.pdf';

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
				        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['OID'], $post_id );
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
					        
					        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['OID'], $post_id );
					    }
					    else
					    {
					    	$media_ids[] = $id;

					    	update_post_meta( $id, '_imported_url', $url);

					    	++$new;
					    }
					}

					++$requests;
					if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }
				}

				

				if ( 
					isset($property['EPCCurrentEER']) && !empty($property['EPCCurrentEER']) &&
					isset($property['EPCPotentialEER']) && !empty($property['EPCPotentialEER'])
				)
				{
					$url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/download/' . $unique_id_to_use_for_epcs . '/epc/EnergyEfficiency?api_key=' . urlencode($import_settings['api_key']);
					$description = 'EnergyEfficiency';
				    
					$filename = $property['OID'] . '-eer.jpg';

					// Check, based on the URL, whether we have previously imported this media
					$imported_previously = false;
					$imported_previously_id = '';
					if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
					{
						foreach ( $previous_media_ids as $previous_media_id )
						{
							if ( 
								get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $url . $property['EPCCurrentEER'] . $property['EPCPotentialEER']
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
					        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['OID'], $post_id );
					    }
					    else
					    {
						    $id = media_handle_sideload( $file_array, $post_id, $description );

						    // Check for handle sideload errors.
						    if ( is_wp_error( $id ) ) 
						    {
						        @unlink( $file_array['tmp_name'] );
						        
						        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['OID'], $post_id );
						    }
						    else
						    {
						    	$media_ids[] = $id;

						    	update_post_meta( $id, '_imported_url', $url . $property['EPCCurrentEER'] . $property['EPCPotentialEER'] );

						    	++$new;
						    }
						}

						++$requests;
						if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }
					}
				}

				if ( 
					isset($property['EPCCurrentEI']) && !empty($property['EPCCurrentEI']) &&
					isset($property['EPCPotentialEI']) && !empty($property['EPCPotentialEI'])
				)
				{
					$url = 'https://live-api.letmc.com/v4/advertising/' . urlencode($import_settings['short_name']) . '/download/' . $unique_id_to_use_for_epcs . '/epc/EnvironmentalImpact?api_key=' . urlencode($import_settings['api_key']);
					$description = 'EnvironmentalImpact';
				    
					$filename = $property['OID'] . '-eir.jpg';

					// Check, based on the URL, whether we have previously imported this media
					$imported_previously = false;
					$imported_previously_id = '';
					if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
					{
						foreach ( $previous_media_ids as $previous_media_id )
						{
							if ( 
								get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $url . $property['EPCCurrentEI'] . $property['EPCPotentialEI']
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
					        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['OID'], $post_id );
					    }
					    else
					    {
						    $id = media_handle_sideload( $file_array, $post_id, $description );

						    // Check for handle sideload errors.
						    if ( is_wp_error( $id ) ) 
						    {
						        @unlink( $file_array['tmp_name'] );
						        
						        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['OID'], $post_id );
						    }
						    else
						    {
						    	$media_ids[] = $id;

						    	update_post_meta( $id, '_imported_url', $url . $property['EPCCurrentEI'] . $property['EPCPotentialEI'] );

						    	++$new;
						    }
						}

						++$requests;
						if ( $requests >= $requests_per_chunk ) { sleep($sleep_seconds); $requests = 0; }
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

				$this->log( 'Imported ' . count($media_ids) . ' brochures and EPCs (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['OID'], $post_id );
				
				/*$virtual_tours = array();
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
				}*/

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_agentos", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, $property['OID'], $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_agentos", $this->import_id );

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
				$import_refs[] = $property['OID'];
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}