<?php
/**
 * Class for managing the import process of a Rentman XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Rentman extends Houzez_Property_Feed_Process {

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

	public function parse_and_import()
	{
		$import_settings = get_import_settings_from_id( $this->import_id );

		$local_directory = $import_settings['local_directory'];

		// Now they've all been extracted, get XML files in date order
		$xml_files = array();
		if ($handle = opendir($local_directory)) 
		{
		    while (false !== ($file = readdir($handle))) 
		    {
		        if (
		        	$file != "." && $file != ".." && 
		        	substr(strtolower($file), -3) == 'xml'
		        ) 
		        {
		           $xml_files[filemtime($local_directory . '/' . $file)] = $local_directory . '/' . $file;
		        }
		    }
		    closedir($handle);
		}

		if (!empty($xml_files))
		{
			ksort($xml_files); // sort by date modified

			// We've got at least one XML to process

            foreach ($xml_files as $mtime => $xml_file)
            {
            	$this->properties = array(); // Reset properties in the event we're importing multiple files

            	$this->log("Parsing properties");

            	$parsed = false;

            	// Get XML contents into memory
				$handle = fopen($xml_file, "r");
		        $xml_contents = fread($handle, filesize($xml_file));
		        fclose($handle);

		        $xml = simplexml_load_string($xml_contents);

		        if ( $xml === false )
		        {
		        	$this->log_error( 'Failed to parse XML' );
		        	$this->archive( $xml_file );
					return false;
		        }

		        foreach ($xml->Properties as $properties)
				{
					foreach ($properties->Property as $property)
					{
		                if ((string)$property->Rentorbuy == 1 || (string)$property->Rentorbuy == 2)
		                {
		                    $this->properties[] = $property;
		                }
		            } // end foreach property
	            } // end foreach properties

            	// Parsed it succesfully. Ok to continue
            	if ( empty($this->properties) )
				{
					$this->log_error( 'No properties found. We\'re not going to continue as this could likely be wrong and all properties will get removed if we continue.' );
					$this->archive( $xml_file );
					return false;
				}
				else
				{
                    $this->import();

                    $this->remove_old_properties();
                }

                $this->archive( $xml_file );
            }
		}
		else
		{
			$this->log_error( 'No XML\'s found to process' );
		}

		$this->clean_up_old_xmls();

		return true;
	}

	private function import()
	{
		global $wpdb;

		$imported_ref_key = ( ( $this->import_id != '' ) ? '_imported_ref_' . $this->import_id : '_imported_ref' );
		$imported_ref_key = apply_filters( 'houzez_property_feed_property_imported_ref_key', $imported_ref_key, $this->import_id );

		$import_settings = get_import_settings_from_id( $this->import_id );

		$local_directory = $import_settings['local_directory'];

		$this->import_start();

		do_action( "houzez_property_feed_pre_import_properties", $this->properties, $this->import_id );
        do_action( "houzez_property_feed_pre_import_properties_rentman", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_rentman", $this->properties, $this->import_id );

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
				if ( (string)$property->Refnumber == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . (string)$property->Refnumber );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, (string)$property->Refnumber, false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . (string)$property->Refnumber, (string)$property->Refnumber );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => (string)$property->Refnumber
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = '';
			if ( (string)$property->Street != '' )
			{
				$display_address .= (string)$property->Street;
			}
			if ( (string)$property->Address3 != '' )
			{
				if ( $display_address != '' ) { $display_address .= ', '; }
				$display_address .= (string)$property->Address3;
			}

			$post_content = '';
			if ( (string)$property->Comments != '' )
            {
				$post_content .= '<p>' . (string)$property->Comments . '</p>';
            }

            if ( isset($property->Rooms->Room) && !empty($property->Rooms->Room) )
			{
            	foreach ( $property->Rooms->Room as $room )
            	{
            		$room_content = ( isset($room->Title) && !empty((string)$room->Title) ) ? '<strong>' . (string)$room->Title . '</strong>' : '';
					if ( isset($room->Description) && !empty((string)$room->Description) ) 
					{
						if ( !empty($room_content) ) { $room_content .= '<br>'; }
						$room_content .= (string)$room->Description;
					}
					
					if ( !empty($room_content) )
					{
						$post_content .= '<p>' . $room_content . '</p>';
					}
            	}
            }

	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', (string)$property->Refnumber );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => (string)$property->Description,
				    	'post_content' 	 => $post_content,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->Refnumber );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', (string)$property->Refnumber );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => (string)$property->Description,
					'post_content' 	 => $post_content,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->Refnumber );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', (string)$property->Refnumber, $post_id );

				update_post_meta( $post_id, $imported_ref_key, (string)$property->Refnumber );

				update_post_meta( $post_id, '_property_import_data', $property->asXML() );

				$department = 'residential-sales';
				if ( (string)$property->Rentorbuy == 1 )
				{
					$department = 'residential-lettings';
				}

				$poa = false;
				if ( (string)$property->Rentorbuy == 2 )
				{
					$price_attributes = $property->Saleprice->attributes();

					if ( isset($price_attributes['Qualifier']) && $price_attributes['Qualifier'] == '2' )
					{
						$poa = true;
					}
				}
				if ( (string)$property->Rentorbuy == 1 )
				{
					$price_attributes = $property->Rent->attributes();

					if ( isset($price_attributes['Qualifier']) && $price_attributes['Qualifier'] == '2' )
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
                	// sales
                	if ( (string)$property->Rentorbuy == 2 )
					{
						$price_attributes = $property->Saleprice->attributes();

						$price = round(preg_replace("/[^0-9.]/", '', (string)$property->Saleprice));

						$prefix = '';
						if ( isset($price_attributes['Qualifier']) )
						{
							switch ($price_attributes['Qualifier'])
							{
								case "1": { $prefix = 'Asking Price'; break; }
								case "3": { $prefix = 'Guide Price'; break; }
								case "4": { $prefix = 'Offers in excess of'; break; }
								case "5": { $prefix = 'Offers in region of'; break; }
								case "6": { $prefix = 'Fixed'; break; }
							}
						}

                		update_post_meta( $post_id, 'fave_property_price_prefix', $prefix );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
					}

					//lettings
					if ( (string)$property->Rentorbuy == 1 )
					{
						$price_attributes = $property->Rent->attributes();

						$price = round(preg_replace("/[^0-9.]/", '', (string)$property->Rent));

						$postfix = '';
						if ( isset($price_attributes['Period']) && (string)$price_attributes['Period'] != '' )
						{
							$postfix = 'per ' . strtolower((string)$price_attributes['Period']);
						}

						update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', $postfix );
					}
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property->Beds) ) ? round((string)$property->Beds) : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property->Baths) ) ? round((string)$property->Baths) : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property->Receps) ) ? round((string)$property->Receps) : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' ); // need to look at parking
	            update_post_meta( $post_id, 'fave_property_id', (string)$property->Refnumber );

	            $address_parts = array();
	            if ( isset($property->Street) && (string)$property->Street != '' )
	            {
	                $address_parts[] = (string)$property->Street;
	            }
	            if ( isset($property->Address3) && (string)$property->Address3 != '' )
	            {
	                $address_parts[] = (string)$property->Address3;
	            }
	            if ( isset($property->Address4) && (string)$property->Address4 != '' )
	            {
	                $address_parts[] = (string)$property->Address4;
	            }
	            if ( isset($property->Postcode) && ( (string)$property->Postcode != '' ) )
	            {
	                $address_parts[] = (string)$property->Postcode;
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' );
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $lat = '';
	            $lng = '';
	            if ( isset($property->Gloc) && (string)$property->Gloc != '' && count( explode(",", (string)$property->Gloc) ) == 2 )
				{
					$exploded_gloc = explode(",", (string)$property->Gloc);

					update_post_meta( $post_id, 'houzez_geolocation_lat', trim($exploded_gloc[0]) );
	                $lat = trim($exploded_gloc[0]);

	                update_post_meta( $post_id, 'houzez_geolocation_long', trim($exploded_gloc[1]) );
	                $lng = trim($exploded_gloc[1]);
				}
	            update_post_meta( $post_id, 'fave_property_location', $lat . "," . $lng . ",14" );
	            update_post_meta( $post_id, 'fave_property_country', 'GB' );
	            
	            $address_parts = array();
	            if ( isset($property->Street) && (string)$property->Street != '' )
	            {
	                $address_parts[] = (string)$property->Street;
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property->Postcode) && (string)$property->Postcode != '' ) ? (string)$property->Postcode : '' ) );

	            add_post_meta( $post_id, 'fave_featured', '0', TRUE );
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
		            					$value_in_feed_to_check = isset($property->{$rule['field']}) ? (string)$property->{$rule['field']} : '';
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
		            					$value_in_feed_to_check = isset($property->{$rule['field']}) ? (string)$property->{$rule['field']} : '';
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
		            					$value_in_feed_to_check = isset($property->{$rule['field']}) ? (string)$property->{$rule['field']} : '';
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
	        	
	            //turn bullets into property features
	            $feature_term_ids = array();
	            if ( isset($property->Bulletpoints->BulletPoint) && !empty($property->Bulletpoints->BulletPoint) )
				{
					foreach ( $property->Bulletpoints->BulletPoint as $feature )
					{
						$feature = (string)$feature;

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
				}
				if ( !empty($feature_term_ids) )
				{
					wp_set_object_terms( $post_id, $feature_term_ids, "property_feature" );
				}
				else
				{
					wp_delete_object_term_relationships( $post_id, "property_feature" );
				}

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				if ( isset($property->Status) && !empty((string)$property->Status) )
				{
					if ( isset($taxonomy_mappings[(string)$property->Status]) && !empty($taxonomy_mappings[(string)$property->Status]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->Status], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . (string)$property->Status . ' that isn\'t mapped in the import settings', (string)$property->Refnumber, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, (string)$property->Status, $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property->Type) && !empty((string)$property->Type) )
				{
					if ( isset($taxonomy_mappings[(string)$property->Type]) && !empty($taxonomy_mappings[(string)$property->Type]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->Type], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . (string)$property->Type . ' that isn\'t mapped in the import settings', (string)$property->Refnumber, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', (string)$property->Type, $this->import_id );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', (string)$property->Refnumber, $post_id );
						}
					}
				}

				$files_to_unlink = array();
				if ( isset($property->Media->Item) && !empty($property->Media->Item) )
				{
	            	foreach ( $property->Media->Item as $image )
	            	{
						$media_file_name = (string)$image;
						$description = '';
						
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

						if ( file_exists( $local_directory . '/' . $media_file_name ) )
						{
							$upload = true;
                            $replacing_attachment_id = '';
                            if ( isset($previous_media_ids[$image_i]) ) 
                            {                                    
                                // get this attachment
                                $current_image_path = get_post_meta( $previous_media_ids[$image_i], '_imported_path', TRUE );
                                $current_image_size = filesize( $current_image_path );
                                
                                if ($current_image_size > 0 && $current_image_size !== FALSE)
                                {
                                    $replacing_attachment_id = $previous_media_ids[$image_i];
                                    
                                    $new_image_size = filesize( $local_directory . '/' . $media_file_name );
                                    
                                    if ($new_image_size > 0 && $new_image_size !== FALSE)
                                    {
                                        if ($current_image_size == $new_image_size)
                                        {
                                            $upload = false;
                                        }
                                        else
                                        {
                                            
                                        }
                                    }
                                    else
                                    {
                                    	$this->log_error( 'Failed to get filesize of new image file ' . $local_directory . '/' . $media_file_name, (string)$property->Refnumber );
                                    }
                                    
                                    unset($new_image_size);
                                }
                                else
                                {
                                	$this->log_error( 'Failed to get filesize of existing image file ' . $current_image_path, (string)$property->Refnumber );
                                }
                                
                                unset($current_image_size);
                            }

                            if ($upload)
                            {
                            	$description = ( $description != '' ) ? $description : preg_replace('/\.[^.]+$/', '', trim($media_file_name, '_'));

								// We've physically received the file
								$upload = wp_upload_bits(trim($media_file_name, '_'), null, file_get_contents($local_directory . '/' . $media_file_name));  
								
								if( isset($upload['error']) && $upload['error'] !== FALSE )
								{
									$this->log_error( print_r($upload['error'], TRUE), (string)$property->Refnumber );
								}
								else
								{
									// We don't already have a thumbnail and we're presented with an image
									$wp_filetype = wp_check_filetype( $upload['file'], null );
								
									$attachment = array(
										//'guid' => $wp_upload_dir['url'] . '/' . trim($media_file_name, '_'), 
										'post_mime_type' => $wp_filetype['type'],
										'post_title' => $description,
										'post_content' => '',
										'post_status' => 'inherit'
									);
									$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
									
									if ( $attach_id === FALSE || $attach_id == 0 )
									{    
										$this->log_error( 'Failed inserting image attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), (string)$property->Refnumber );
									}
									else
									{  
										$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
										wp_update_attachment_metadata( $attach_id,  $attach_data );

										update_post_meta( $attach_id, '_imported_path', $upload['file']);

										$media_ids[] = $attach_id;

										if ( $image_i == 0 ) set_post_thumbnail( $post_id, $attach_id );

										++$new;

										++$image_i;

										update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
									}
								}

								$files_to_unlink[] = $local_directory . '/' . $media_file_name;
                            }
                            else
                            {
                            	if ( isset($previous_media_ids[$image_i]) ) 
                            	{
                            		$media_ids[] = $previous_media_ids[$image_i];

                            		if ( $description != '' )
									{
										$my_post = array(
									    	'ID'          	 => $previous_media_ids[$image_i],
									    	'post_title'     => $description,
									    );

									 	// Update the post into the database
									    wp_update_post( $my_post );
									}

									if ( $image_i == 0 ) set_post_thumbnail( $post_id, $previous_media_ids[$image_i] );

									++$existing;

									++$image_i;

									update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
                            	}

                            	$files_to_unlink[] =$local_directory . '/' . $media_file_name;
                            }
						}
						else
						{
							if ( isset($previous_media_ids[$image_i]) ) 
	                    	{
	                    		$media_ids[] = $previous_media_ids[$image_i];

	                    		if ( $description != '' )
								{
									$my_post = array(
								    	'ID'          	 => $previous_media_ids[$image_i],
								    	'post_title'     => $description,
								    );

								 	// Update the post into the database
								    wp_update_post( $my_post );
								}

								if ( $image_i == 0 ) set_post_thumbnail( $post_id, $previous_media_ids[$image_i] );

								++$existing;

								++$image_i;

								update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->Refnumber, $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				if ( !empty($files_to_unlink) )
				{
					foreach ( $files_to_unlink as $file_to_unlink )
					{
						unlink($file_to_unlink);
					}
				}

				// Floorplans
				$floorplans = array();
				$media_ids = array();
				$previous_media_ids = get_post_meta( $post_id, '_floorplan_attachment_ids', TRUE );
				$media_i = 0;
				$files_to_unlink = array();
				if ( isset($property->Floorplan) && (string)$property->Floorplan != '' )
	            {
					if ( 
						substr( strtolower((string)$property->Floorplan), 0, 2 ) == '//' || 
						substr( strtolower((string)$property->Floorplan), 0, 4 ) == 'http'
					)
					{
						$floorplans[] = array( 
							"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
							"fave_plan_image" => (string)$property->Floorplan
						);
					}
					else
					{
						// Not a URL. Must've been physically uploaded or already exists
						$media_file_name = (string)$property->Floorplan;
						$description = '';

						if ( file_exists( $local_directory . '/' . $media_file_name ) )
						{
							$upload = true;
                            $replacing_attachment_id = '';
                            if ( isset($previous_media_ids[$media_i]) ) 
                            {                                    
                                // get this attachment
                                $current_image_path = get_post_meta( $previous_media_ids[$media_i], '_imported_path', TRUE );
                                $current_image_size = filesize( $current_image_path );
                                
                                if ($current_image_size > 0 && $current_image_size !== FALSE)
                                {
                                    $replacing_attachment_id = $previous_media_ids[$media_i];
                                    
                                    $new_image_size = filesize( $local_directory . '/' . $media_file_name );
                                    
                                    if ($new_image_size > 0 && $new_image_size !== FALSE)
                                    {
                                        if ($current_image_size == $new_image_size)
                                        {
                                            $upload = false;
                                        }
                                        else
                                        {
                                            
                                        }
                                    }
                                    else
                                    {
                                    	$this->log_error( 'Failed to get filesize of new floorplan file ' . $local_directory . '/' . $media_file_name, (string)$property->Refnumber );
                                    }
                                    
                                    unset($new_image_size);
                                }
                                else
                                {
                                	$this->log_error( 'Failed to get filesize of existing floorplan file ' . $current_image_path, (string)$property->Refnumber );
                                }
                                
                                unset($current_image_size);
                            }

                            if ($upload)
                            {
								// We've physically received the file
								$upload = wp_upload_bits(trim($media_file_name, '_'), null, file_get_contents($local_directory . '/' . $media_file_name));  
								if( isset($upload['error']) && $upload['error'] !== FALSE )
								{
									$this->log_error( print_r($upload['error'], TRUE), (string)$property->Refnumber );
								}
								else
								{
									// We don't already have a thumbnail and we're presented with an image
									$wp_filetype = wp_check_filetype( $upload['file'], null );
								
									$attachment = array(
										//'guid' => $wp_upload_dir['url'] . '/' . trim($media_file_name, '_'), 
										'post_mime_type' => $wp_filetype['type'],
										'post_title' => $description,
										'post_content' => '',
										'post_status' => 'inherit'
									);
									$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
									
									if ( $attach_id === FALSE || $attach_id == 0 )
									{    
										$this->log_error( 'Failed inserting floorplan attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), (string)$property->Refnumber );
									}
									else
									{  
										$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
										wp_update_attachment_metadata( $attach_id,  $attach_data );

										update_post_meta( $attach_id, '_imported_path', $upload['file']);

										$media_ids[] = $attach_id;

										$floorplans[] = array( 
											"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
											"fave_plan_image" => wp_get_attachment_url($attach_id),
										);
									}
								}

								$files_to_unlink[] = $local_directory . '/' . $media_file_name;
                            }
                            else
                            {
                            	if ( isset($previous_media_ids[$media_i]) ) 
                            	{
                            		$media_ids[] = $previous_media_ids[$media_i];

                            		if ( $description != '' )
									{
										$my_post = array(
									    	'ID'          	 => $previous_media_ids[$media_i],
									    	'post_title'     => $description,
									    );

									 	// Update the post into the database
									    wp_update_post( $my_post );
									}

									$floorplans[] = array( 
										"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
										"fave_plan_image" => wp_get_attachment_url($previous_media_ids[$media_i]),
									);
                            	}

                            	$files_to_unlink[] = $local_directory . '/' . $media_file_name;
                            }
						}
						else
						{
							if ( isset($previous_media_ids[$media_i]) ) 
	                    	{
	                    		$media_ids[] = $previous_media_ids[$media_i];

	                    		if ( $description != '' )
								{
									$my_post = array(
								    	'ID'          	 => $previous_media_ids[$media_i],
								    	'post_title'     => $description,
								    );

								 	// Update the post into the database
								    wp_update_post( $my_post );
								}

								$floorplans[] = array( 
									"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
									"fave_plan_image" => wp_get_attachment_url($previous_media_ids[$media_i]),
								);
	                    	}
						}
						++$media_i;
					}
				}

				update_post_meta( $post_id, '_floorplan_attachment_ids', $media_ids );

				if ( !empty($floorplans) )
				{
	                update_post_meta( $post_id, 'floor_plans', $floorplans );
	                update_post_meta( $post_id, 'fave_floor_plans_enable', 'enable' );
	            }
	            else
	            {
	            	update_post_meta( $post_id, 'fave_floor_plans_enable', 'disable' );
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

	            if ( !empty($files_to_unlink) )
				{
					foreach ( $files_to_unlink as $file_to_unlink )
					{
						unlink($file_to_unlink);
					}
				}

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', (string)$property->Refnumber, $post_id );

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );
				$files_to_unlink = array();

				if (isset($property->Brochure) && (string)$property->Brochure != '')
	            {
					$media_file_name = (string)$property->Brochure;
					$description = __( 'Brochure', 'houzezpropertyfeed' );
					
					if ( file_exists( $local_directory . '/' . $media_file_name ) )
					{
						$upload = true;
                        $replacing_attachment_id = '';
                        if ( isset($previous_media_ids[0]) ) 
                        {                                    
                            // get this attachment
                            $current_image_path = get_post_meta( $previous_media_ids[0], '_imported_path', TRUE );
                            $current_image_size = filesize( $current_image_path );
                            
                            if ($current_image_size > 0 && $current_image_size !== FALSE)
                            {
                                $replacing_attachment_id = $previous_media_ids[0];
                                
                                $new_image_size = filesize( $local_directory . '/' . $media_file_name );
                                
                                if ($new_image_size > 0 && $new_image_size !== FALSE)
                                {
                                    if ($current_image_size == $new_image_size)
                                    {
                                        $upload = false;
                                    }
                                    else
                                    {
                                        
                                    }
                                }
                                else
                                {
                                	$this->log_error( 'Failed to get filesize of new brochure file ' . $local_directory . '/' . $media_file_name, (string)$property->Refnumber );
                                }
                                
                                unset($new_image_size);
                            }
                            else
                            {
                            	$this->log_error( 'Failed to get filesize of existing brochure file ' . $current_image_path, (string)$property->Refnumber );
                            }
                            
                            unset($current_image_size);
                        }

                        if ($upload)
                        {
                        	$description = ( $description != '' ) ? $description : preg_replace('/\.[^.]+$/', '', trim($media_file_name, '_'));

							// We've physically received the file
							$upload = wp_upload_bits(trim($media_file_name, '_'), null, file_get_contents($local_directory . '/' . $media_file_name));  
							
							if( isset($upload['error']) && $upload['error'] !== FALSE )
							{
								$this->log_error( print_r($upload['error'], TRUE), (string)$property->Refnumber );
							}
							else
							{
								// We don't already have a thumbnail and we're presented with an image
								$wp_filetype = wp_check_filetype( $upload['file'], null );
							
								$attachment = array(
									//'guid' => $wp_upload_dir['url'] . '/' . trim($media_file_name, '_'), 
									'post_mime_type' => $wp_filetype['type'],
									'post_title' => $description,
									'post_content' => '',
									'post_status' => 'inherit'
								);
								$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );

								if ( $attach_id === FALSE || $attach_id == 0 )
								{    
									$this->log_error( 'Failed inserting brochure attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), (string)$property->Refnumber );
								}
								else
								{                                    
									$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
									wp_update_attachment_metadata( $attach_id,  $attach_data );

									update_post_meta( $attach_id, '_imported_path', $upload['file']);

									$media_ids[] = $attach_id;

									++$new;
								}
							}

							$files_to_unlink[] = $local_directory . '/' . $media_file_name;
                        }
                        else
                        {
                        	if ( isset($previous_media_ids[0]) ) 
                        	{
                        		$media_ids[] = $previous_media_ids[0];

                        		if ( $description != '' )
								{
									$my_post = array(
								    	'ID'          	 => $previous_media_ids[0],
								    	'post_title'     => $description,
								    );

								 	// Update the post into the database
								    wp_update_post( $my_post );
								}

								++$existing;
                        	}

                        	$files_to_unlink[] = $local_directory . '/' . $media_file_name;
                        }
					}
					else
					{
						if ( isset($previous_media_ids[0]) ) 
                    	{
                    		$media_ids[] = $previous_media_ids[0];

                    		if ( $description != '' )
							{
								$my_post = array(
							    	'ID'          	 => $previous_media_ids[0],
							    	'post_title'     => $description,
							    );

							 	// Update the post into the database
							    wp_update_post( $my_post );
							}

							++$existing;
                    	}
					}
				}

				if (isset($property->Epc) && (string)$property->Epc != '')
	            {
					$media_file_name = (string)$property->Epc;
					$description = __( 'EPC', 'houzezpropertyfeed' );
					
					if ( file_exists( $local_directory . '/' . $media_file_name ) )
					{
						$upload = true;
                        $replacing_attachment_id = '';
                        if ( isset($previous_media_ids[1]) ) 
                        {                                    
                            // get this attachment
                            $current_image_path = get_post_meta( $previous_media_ids[1], '_imported_path', TRUE );
                            $current_image_size = filesize( $current_image_path );
                            
                            if ($current_image_size > 0 && $current_image_size !== FALSE)
                            {
                                $replacing_attachment_id = $previous_media_ids[1];
                                
                                $new_image_size = filesize( $local_directory . '/' . $media_file_name );
                                
                                if ($new_image_size > 0 && $new_image_size !== FALSE)
                                {
                                    if ($current_image_size == $new_image_size)
                                    {
                                        $upload = false;
                                    }
                                    else
                                    {
                                        
                                    }
                                }
                                else
                                {
                                	$this->log_error( 'Failed to get filesize of new EPC file ' . $local_directory . '/' . $media_file_name, (string)$property->Refnumber );
                                }
                                
                                unset($new_image_size);
                            }
                            else
                            {
                            	$this->log_error( 'Failed to get filesize of existing EPC file ' . $current_image_path, (string)$property->Refnumber );
                            }
                            
                            unset($current_image_size);
                        }

                        if ($upload)
                        {
                        	$description = ( $description != '' ) ? $description : preg_replace('/\.[^.]+$/', '', trim($media_file_name, '_'));

							// We've physically received the file
							$upload = wp_upload_bits(trim($media_file_name, '_'), null, file_get_contents($local_directory . '/' . $media_file_name));  
							
							if( isset($upload['error']) && $upload['error'] !== FALSE )
							{
								$this->log_error( print_r($upload['error'], TRUE), (string)$property->Refnumber );
							}
							else
							{
								// We don't already have a thumbnail and we're presented with an image
								$wp_filetype = wp_check_filetype( $upload['file'], null );
							
								$attachment = array(
									//'guid' => $wp_upload_dir['url'] . '/' . trim($media_file_name, '_'), 
									'post_mime_type' => $wp_filetype['type'],
									'post_title' => $description,
									'post_content' => '',
									'post_status' => 'inherit'
								);
								$attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );

								if ( $attach_id === FALSE || $attach_id == 0 )
								{    
									$this->log_error( 'Failed inserting EPC attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), (string)$property->Refnumber );
								}
								else
								{                                    
									$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
									wp_update_attachment_metadata( $attach_id,  $attach_data );

									update_post_meta( $attach_id, '_imported_path', $upload['file']);

									$media_ids[] = $attach_id;

									++$new;
								}
							}

							$files_to_unlink[] = $local_directory . '/' . $media_file_name;
                        }
                        else
                        {
                        	if ( isset($previous_media_ids[1]) ) 
                        	{
                        		$media_ids[] = $previous_media_ids[1];

                        		if ( $description != '' )
								{
									$my_post = array(
								    	'ID'          	 => $previous_media_ids[1],
								    	'post_title'     => $description,
								    );

								 	// Update the post into the database
								    wp_update_post( $my_post );
								}

								++$existing;
                        	}

                        	$files_to_unlink[] = $local_directory . '/' . $media_file_name;
                        }
					}
					else
					{
						if ( isset($previous_media_ids[1]) ) 
                    	{
                    		$media_ids[] = $previous_media_ids[1];

                    		if ( $description != '' )
							{
								$my_post = array(
							    	'ID'          	 => $previous_media_ids[1],
							    	'post_title'     => $description,
							    );

							 	// Update the post into the database
							    wp_update_post( $my_post );
							}

							++$existing;
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

				if ( !empty($files_to_unlink) )
				{
					foreach ( $files_to_unlink as $file_to_unlink )
					{
						unlink($file_to_unlink);
					}
				}

				$this->log( 'Imported ' . count($media_ids) . ' brochures and EPCs (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->Refnumber, $post_id );
				
				update_post_meta( $post_id, 'fave_video_url', '' );
				update_post_meta( $post_id, 'fave_virtual_tour', '' );

				$virtual_tour_urls = array();
				if (isset($property->Evt) && (string)$property->Evt)
                {
                    $virtual_tour_urls[] = (string)$property->Evt;
                }

				foreach ( $virtual_tour_urls as $virtual_tour_url )
				{
					if ( 
						substr( strtolower($virtual_tour_url), 0, 2 ) == '//' || 
						substr( strtolower($virtual_tour_url), 0, 4 ) == 'http'
					)
					{
						// This is a URL
						$url = trim($virtual_tour_url);

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

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_rentman", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, (string)$property->Refnumber, $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_rentman", $this->import_id );

		$this->import_end();
	}

	private function remove_old_properties()
	{
		global $wpdb, $post;

		if ( !empty($this->properties) )
		{
			$import_refs = array();
			foreach ($this->properties as $property)
			{
				$import_refs[] = (string)$property->Refnumber;
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}

	private function clean_up_old_xmls()
    {
    	$import_settings = get_import_settings_from_id( $this->import_id );

    	$local_directory = $import_settings['local_directory'];

    	// Clean up processed .XMLs and unused media older than 7 days old (7 days = 604800 seconds)
		if ($handle = opendir($local_directory)) 
		{
		    while (false !== ($file = readdir($handle))) 
		    {
		        if (
		        	$file != "." && $file != ".." && 
		        	(
		        		substr($file, -9) == 'processed'
		        	)
		        ) 
		        {
		        	if ( filemtime($local_directory . '/' . $file) !== FALSE && filemtime($local_directory . '/' . $file) < (time() - 604800) )
		        	{
		        		unlink($local_directory . '/' . $file);
		        	}
		        }
		    }
		    closedir($handle);
		}
		else
		{
			$this->log_error( 'Failed to read from directory ' . $local_directory . '. Please ensure the local directory specified exists, is the full server path and is readable.' );
			return false;
		}
	}

	private function archive( $xml_file )
    {
    	// Rename to append the date and '.processed' as to not get picked up again. Will be cleaned up every 7 days
    	$new_target_file = $xml_file . '-' . time() .'.processed';
		rename( $xml_file, $new_target_file );
		
		$this->log( 'Archived XML. Available for download for 7 days: <a href="' . str_replace("/includes/import-formats", "", plugin_dir_url( __FILE__ )) . "/download.php?import_id=" . $this->import_id . "&file=" . base64_encode(basename($new_target_file)) . '" target="_blank">Download</a>' );
	}
}

}