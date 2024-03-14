<?php
/**
 * Class for managing the import process of a Property Finder myCRM XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Property_Finder extends Houzez_Property_Feed_Process {

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

		$urls = explode( ",", trim($import_settings['xml_url']) );

		if ( empty( $urls ) )
		{
			$this->log_error( 'No URLs to process' );
		    return false;
		}

		foreach ($urls as $url)
		{
			$response = wp_remote_get( trim($url), array( 'timeout' => 120 ) );
			if ( !is_wp_error($response) && is_array( $response ) ) 
			{
				$contents = $response['body'];
			}
			else
			{
				$this->log_error( "Failed to obtain XML at " . $url . ". Dump of response as follows: " . print_r($response, TRUE) );

	        	return false;
			}

			$xml = simplexml_load_string($contents);

			if ( $xml !== FALSE )
			{
				foreach ($xml->property as $property)
    			{
		            $this->properties[] = $property;
	            }
		    }
		    else
	        {
	        	// Failed to parse XML
	        	$this->log_error( 'Failed to parse XML file at ' . $url . '. Possibly invalid XML' );
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
        do_action( "houzez_property_feed_pre_import_properties_property_finder", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_property_finder", $this->properties, $this->import_id );

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
				if ( (string)$property->reference_number == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . (string)$property->reference_number );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, (string)$property->reference_number, false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . (string)$property->reference_number, (string)$property->reference_number );

			$inserted_updated = false;

			$property_attributes = $property->attributes();

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => (string)$property->reference_number
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = (string)$property->title_en;
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', (string)$property->reference_number );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => (string)$property->description_en,
				    	'post_content' 	 => (string)$property->description_en,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->reference_number );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', (string)$property->reference_number );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => (string)$property->description_en,
				    'post_content' 	 => (string)$property->description_en,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->reference_number );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', (string)$property->reference_number, $post_id );

				update_post_meta( $post_id, $imported_ref_key, (string)$property->reference_number );

				update_post_meta( $post_id, '_property_import_data', $property->asXML() );

				$department = 'residential-sales';
				if ( (string)$property->offering_type == 'RR' || (string)$property->offering_type == 'CR' )
				{
					$department = 'residential-lettings';
				}

				$poa = false;
				if (
					isset($property->price_on_application) && 
					strtolower((string)$property->price_on_application) == 'yes'
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
                	$price = round(preg_replace("/[^0-9.]/", '', (string)$property->price));

                    update_post_meta( $post_id, 'fave_property_price_prefix', '' );
                    update_post_meta( $post_id, 'fave_property_price', $price );
                    
                    $rent_frequency = '';
                    if ( $department == 'residential-lettings' )
                    {
	                    $rent_frequency = 'pcm';
						if ( isset($property->rental_period) )
						{
							switch ((string)$property->rental_period)
							{
								case "Y": { $rent_frequency = 'pa'; break; }
								case "W": { $rent_frequency = 'pw'; break; }
								case "D": { $rent_frequency = 'pd'; break; }
							}
						}
					}
					update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property->bedroom) ) ? (string)$property->bedroom : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property->bathroom) ) ? (string)$property->bathroom : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', '' );
	            update_post_meta( $post_id, 'fave_property_garage', '' );
	            update_post_meta( $post_id, 'fave_property_id', (string)$property->reference_number );

	            $address_parts = array();
	            if ( isset($property->sub_community) && (string)$property->sub_community != '' )
	            {
	                $address_parts[] = (string)$property->sub_community;
	            }
	            if ( isset($property->community) && (string)$property->community != '' )
	            {
	                $address_parts[] = (string)$property->community;
	            }
	            if ( isset($property->city) && (string)$property->city != '' )
	            {
	                $address_parts[] = (string)$property->city;
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' );
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $geopoints = isset($property->geopoints) ? explode(",", (string)$property->geopoints) : '';
				$lat = '';
				$lng = '';
				if ( count($geopoints) == 2 )
				{
					$lat = $geopoints[1];
					$lng = $geopoints[0];
				}
	            update_post_meta( $post_id, 'fave_property_location', $lat . "," . $lng . ",14" );
	            update_post_meta( $post_id, 'fave_property_country', 'AE' );
	            
	            $address_parts = array();
	            if ( isset($property->property_name) && (string)$property->property_name != '' )
	            {
	                $address_parts[] = (string)$property->property_name;
	            }
	            if ( isset($property->street) && (string)$property->street != '' )
	            {
	                $address_parts[] = (string)$property->street;
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', '' );

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
		            				case "agent_name":
		            				{
		            					$value_in_feed_to_check = isset($property->agent->name) ? (string)$property->agent->name : '';
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
		            				case "agent_name":
		            				{
		            					$value_in_feed_to_check = isset($property->agent->name) ? (string)$property->agent->name : '';
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
		            				case "agent_name":
		            				{
		            					$value_in_feed_to_check = isset($property->agent->name) ? (string)$property->agent->name : '';
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

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$availability = 'For Sale';
				if ( $department == 'residential-lettings' )
				{
					$availability = 'To Let';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				if ( isset($property->priority) && !empty($availability) )
				{
					if ( isset($taxonomy_mappings[$availability]) && !empty($taxonomy_mappings[$availability]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$availability], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . $availability . ' that isn\'t mapped in the import settings', (string)$property->reference_number, $post_id );

						//$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, $availability, $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property->property_type) )
				{
					if ( isset($taxonomy_mappings[(string)$property->property_type]) && !empty($taxonomy_mappings[(string)$property->property_type]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->property_type], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . (string)$property->property_type . ' that isn\'t mapped in the import settings', (string)$property->reference_number, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', (string)$property->property_type, $this->import_id );
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
						if ( isset($property->{$address_field_to_use}) && !empty((string)$property->{$address_field_to_use}) )
		            	{
		            		$term = term_exists( trim((string)$property->{$address_field_to_use}), $location_taxonomy);
							if ( $term !== 0 && $term !== null && isset($term['term_id']) )
							{
								$location_term_ids[] = (int)$term['term_id'];
							}
							else
							{
								if ( $create_location_taxonomy_terms === true )
								{
									$term = wp_insert_term( trim((string)$property->{$address_field_to_use}), $location_taxonomy );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', (string)$property->reference_number, $post_id );
						}
					}
				}

				if ( isset($property->photo->url) && !empty($property->photo->url) )
	            {
    				foreach ( $property->photo->url as $url )
					{
						if ( 
							substr( strtolower((string)$url), 0, 2 ) == '//' || 
							substr( strtolower((string)$url), 0, 4 ) == 'http'
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

							$media_attributes = $url->attributes();

							// This is a URL
							$url = (string)$url;
							$description = '';

							$modified = (string)$media_attributes['last_update'];

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
										&&
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
								if ( apply_filters( 'houzez_property_feed_import_media', true, $this->import_id, $post_id, (string)$property->reference_number, $url, $url, $description, 'image', $image_i, '' ) === true )
								{
									$tmp = download_url( $url );

								    $file_array = array(
								        'name' => $filename,
								        'tmp_name' => $tmp
								    );

								    // Check for download errors
								    if ( is_wp_error( $tmp ) ) 
								    {
								        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->reference_number, $post_id );
								    }
								    else
								    {
									    $id = media_handle_sideload( $file_array, $post_id, $description );

									    // Check for handle sideload errors.
									    if ( is_wp_error( $id ) ) 
									    {
									        @unlink( $file_array['tmp_name'] );
									        
									        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->reference_number, $post_id );
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->reference_number, $post_id );
				if ( $queued > 0 ) 
				{
					$this->log( $queued . ' photos added to download queue', (string)$property->reference_number, $post_id );
				}

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				/*$floorplans = array();

				if ( isset($property->floor_plan->url) && !empty($property->floor_plan->url) )
	            {
    				foreach ( $property->floor_plan->url as $url )
					{
						if ( 
							substr( strtolower((string)$url), 0, 2 ) == '//' || 
							substr( strtolower((string)$url), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$floorplans[] = array( 
								"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
								"fave_plan_image" => str_replace(" ", "%20", (string)$url)
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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', (string)$property->reference_number, $post_id );

				update_post_meta( $post_id, 'fave_video_url', '' );
				update_post_meta( $post_id, 'fave_virtual_tour', '' );

				if (isset($property->view360) && (string)$property->view360 != '')
                {
					// This is a URL
					$url = (string)$property->view360;

					if ( strpos(strtolower($url), 'youtu') !== false || strpos(strtolower($url), 'vimeo') !== false )
					{
						update_post_meta( $post_id, 'fave_video_url', $url );
					}
					else
					{
						$iframe = '<iframe src="' . $url . '" style="border:0; height:360px; width:640px; max-width:100%" allowFullScreen="true"></iframe>';
						update_post_meta( $post_id, 'fave_virtual_tour', $iframe );
					}
				}*/

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_property_finder", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, (string)$property->reference_number, $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_property_finder", $this->import_id );

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
				$import_refs[] = (string)$property->reference_number;
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}