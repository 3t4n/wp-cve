<?php
/**
 * Class for managing the import process of an Expert Agent XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Expertagent extends Houzez_Property_Feed_Process {

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

		$ftp_conn = $this->open_ftp_connection($import_settings['ftp_host'], $import_settings['ftp_user'], $import_settings['ftp_pass'], '', $import_settings['ftp_passive']);
		if ( $ftp_conn === null)
		{
			$this->log_error( 'Incorrect FTP details provided' );
			return false;
		}

		$wp_upload_dir = wp_upload_dir();
		if( $wp_upload_dir['error'] !== FALSE )
		{
			$this->log_error( 'Unable to create uploads folder. Please check permissions' );
			return false;
		}

		$xml_file = $wp_upload_dir['basedir'] . '/houzez_property_feed_import/' . $import_settings['xml_filename'];

		// Get file
		if ( ftp_get( $ftp_conn, $xml_file, $import_settings['xml_filename'], FTP_ASCII ) )
		{

		}
		else
		{
			$this->log_error( 'Failed to get file ' . $import_settings['xml_filename'] . ' into ' . $xml_file . ' from FTP directory. Maybe try changing the FTP Passive option' );
			return false;
		}
		ftp_close( $ftp_conn );

		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$xml = simplexml_load_file( $xml_file );

		$departments_to_import = array( 'sales', 'lettings' );
		$departments_to_import = apply_filters( 'houzez_property_feed_expertagent_departments_to_import', $departments_to_import );

		if ($xml !== FALSE)
		{
			foreach ($xml->branches as $branches)
			{
			    foreach ($branches->branch as $branch)
                {
                	$branch_attributes = $branch->attributes();

                	$branch_name = (string)$branch_attributes['name'];

                    foreach ($branch->properties as $properties)
                    {
                        foreach ($properties->property as $property)
                        {
                        	$property_attributes = $property->attributes();

                        	$department = (string)$property->department;

                        	$ok_to_import = false;
                        	foreach ( $departments_to_import as $department_to_import )
                        	{
                        		if ( strpos(strtolower($department), $department_to_import) !== FALSE )
                        		{
                        			$ok_to_import = true;
                        			break;
                        		}
                        	}

                        	if ( $ok_to_import )
                            { 
                            	// Add branch to the property object so we can access it later.
	                        	$property->addChild('branch', htmlentities($branch_name));

	                            $this->properties[] = $property;
	                        }

                        } // end foreach property
                    } // end foreach properties
                } // end foreach branch
            } // end foreach branches
        }
        else
        {
        	// Failed to parse XML
        	$this->log_error( 'Failed to parse XML file: ' . file_get_contents($xml_file) );

        	unlink($xml_file);

        	return false;
		}

		unlink($xml_file);

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
        do_action( "houzez_property_feed_pre_import_properties_expertagent", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_expertagent", $this->properties, $this->import_id );

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
				if ( (string)$property->property_reference == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . (string)$property->property_reference );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, (string)$property->property_reference, false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . (string)$property->property_reference, (string)$property->property_reference );

			$inserted_updated = false;

			$property_attributes = $property->attributes();

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => (string)$property->property_reference
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = (string)$property->advert_heading;

	        $post_content = '';
	        if ( isset($property->rooms) && !empty($property->rooms) )
			{
				foreach ($property->rooms as $rooms)
				{
					foreach ( $rooms->room as $room )
					{
						$room_attributes = $room->attributes();

			            $room_content = ( isset($room_attributes['name']) && !empty((string)$room_attributes['name']) ) ? '<strong>' . (string)$room_attributes['name'] . '</strong>' : '';
						$room_content .= ( isset($room->measurement_text) && !empty((string)$room->measurement_text) ) ? ' (' . (string)$room->measurement_text . ')' : '';
						if ( isset($room->description) && !empty((string)$room->description) ) 
						{
							if ( !empty($room_content) ) { $room_content .= '<br>'; }
							$room_content .= (string)$room->description;
						}
						
						if ( !empty($room_content) )
						{
							$post_content .= '<p>' . $room_content . '</p>';
						}
					}
				}
			}
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', (string)$property->property_reference );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => (string)$property->main_advert,
				    	'post_content' 	 => $post_content,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->property_reference );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', (string)$property->property_reference );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => (string)$property->main_advert,
					'post_content' 	 => $post_content,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->property_reference );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', (string)$property->property_reference, $post_id );

				update_post_meta( $post_id, $imported_ref_key, (string)$property->property_reference );

				update_post_meta( $post_id, '_property_import_data', $property->asXML() );

				$department = ( ( strpos(strtolower((string)$property->department), 'lettings') !== FALSE ) ? 'residential-lettings' : 'residential-sales' );

				$poa = false;
				if (
					strpos(strtolower((string)$property->price_text), 'poa') !== FALSE || 
					strpos(strtolower((string)$property->price_text), 'p.o.a') !== FALSE || 
					strpos(strtolower((string)$property->price_text), 'on application') !== FALSE
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
                	$price = round(preg_replace("/[^0-9.]/", '', (string)$property->numeric_price));

                	$price_prefix = '';
                	$explode_price_text = explode("£", (string)$property->price_text);

                    update_post_meta( $post_id, 'fave_property_price_prefix', $explode_price_text[0] );
                    update_post_meta( $post_id, 'fave_property_price', $price );
                    
                    $rent_frequency = '';
                    if ( $department == 'residential-lettings' )
                    {
	                    if (
							strpos(strtolower((string)$property->price_text), 'pcm') !== FALSE || 
							strpos(strtolower((string)$property->price_text), 'month') !== FALSE
						)
						{
							$rent_frequency = 'pcm';
						}

						if (
							strpos(strtolower((string)$property->price_text), 'pw') !== FALSE || 
							strpos(strtolower((string)$property->price_text), 'week') !== FALSE
						)
						{
							$rent_frequency = 'pw';
						}

						if (
							strpos(strtolower((string)$property->price_text), 'pq') !== FALSE || 
							strpos(strtolower((string)$property->price_text), 'quarter') !== FALSE
						)
						{
							$rent_frequency = 'pq';
						}

						if (
							strpos(strtolower((string)$property->price_text), 'pa') !== FALSE || 
							strpos(strtolower((string)$property->price_text), 'annum') !== FALSE || 
							strpos(strtolower((string)$property->price_text), 'annual') !== FALSE
						)
						{
							$rent_frequency = 'pa';
						}
					}
					update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property->bedrooms) ) ? (string)$property->bedrooms : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property->bathrooms) ) ? (string)$property->bathrooms : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property->receptions) ) ? (string)$property->receptions : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' ); // need to look at parking
	            update_post_meta( $post_id, 'fave_property_id', (string)$property_attributes['reference'] );

	            $address_parts = array();
	            if ( isset($property->street) && (string)$property->street != '' )
	            {
	                $address_parts[] = (string)$property->street;
	            }
	            if ( isset($property->district) && (string)$property->district != '' )
	            {
	                $address_parts[] = (string)$property->district;
	            }
	            if ( isset($property->town) && (string)$property->town != '' )
	            {
	                $address_parts[] = (string)$property->town;
	            }
	            if ( isset($property->county) && (string)$property->county != '' )
	            {
	                $address_parts[] = (string)$property->county;
	            }
	            if ( isset($property->postcode) && (string)$property->postcode != '' )
	            {
	                $address_parts[] = (string)$property->postcode;
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' );
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $lat = '';
	            $lng = '';
	            if ( isset($property->latitude) && !empty((string)$property->latitude) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_lat', (string)$property->latitude );
	                $lat = (string)$property->latitude;
	            }
	            if ( isset($property->longitude) && !empty((string)$property->longitude) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_long', (string)$property->longitude );
	                $lng = (string)$property->longitude;
	            }
	            update_post_meta( $post_id, 'fave_property_location', $lat . "," . $lng . ",14" );
	            update_post_meta( $post_id, 'fave_property_country', 'GB' );
	            
	            $address_parts = array();
	            if ( isset($property->street) && (string)$property->street != '' )
	            {
	                $address_parts[] = (string)$property->street;
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property->postcode) && (string)$property->postcode != '' ) ? (string)$property->postcode : '' ) );

	            $featured = '0';
	            if ( isset($property->featuredProperty) && strtolower((string)$property->featuredProperty) == 'yes' )
				{
					$featured = '1';
				}
				elseif ( isset($property->propertyofweek) && strtolower((string)$property->propertyofweek) == 'yes' )
				{
					$featured = '1';
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
	            for ( $i = 1; $i <= 20; ++$i )
				{
					if ( isset($property->{'bullet' . $i}) && trim((string)$property->{'bullet' . $i}) != '' )
					{
						$feature = (string)$property->{'bullet' . $i};

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

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				if ( isset($property->priority) && !empty((string)$property->priority) )
				{
					if ( isset($taxonomy_mappings[(string)$property->priority]) && !empty($taxonomy_mappings[(string)$property->priority]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->priority], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . (string)$property->priority . ' that isn\'t mapped in the import settings', (string)$property->property_reference, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, (string)$property->priority, $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property->property_type) && isset($property->property_style) )
				{
					$expert_agent_type = (string)$property->property_type . ' - ' . (string)$property->property_style;
					if ( isset($taxonomy_mappings[$expert_agent_type]) && !empty($taxonomy_mappings[$expert_agent_type]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$expert_agent_type], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . $expert_agent_type . ' that isn\'t mapped in the import settings', (string)$property->property_reference, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $expert_agent_type, $this->import_id );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', (string)$property->property_reference, $post_id );
						}
					}
				}

				if ( isset($property->pictures) && !empty($property->pictures) )
				{
					foreach ( $property->pictures as $pictures )
					{
						foreach ( $pictures->picture as $picture )
						{
							$picture_attributes = $picture->attributes();

							if ( isset($picture->filename) && trim((string)$picture->filename) != '' )
							{
								if ( 
									substr( strtolower((string)$picture->filename), 0, 2 ) == '//' || 
									substr( strtolower((string)$picture->filename), 0, 4 ) == 'http'
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
									$url = str_replace(" ", "%20", (string)$picture->filename);
									$description = ( ( isset($picture_attributes['name']) && (string)$picture_attributes['name'] != '' ) ? (string)$picture_attributes['name'] : '' );

									$media_attributes = $picture->attributes();
									$modified = date('Y-m-d H:i:s', strtotime ((string)$media_attributes['lastchanged']));

									$filename = basename( $url );

									// Check, based on the URL, whether we have previously imported this media
									$imported_previously = false;
									$imported_previously_id = '';
									if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
									{
										foreach ( $previous_media_ids as $previous_media_id )
										{
											$previous_url = get_post_meta( $previous_media_id, '_imported_url', TRUE );
											$new_url = $url;

											// Should contain 'expert' in URL but check first in case EA ever change their media hosting
											if ( strpos($previous_url, 'expert') !== FALSE && strpos($new_url, 'expert') !== FALSE )
											{
												// Need to remove first part of URLs before comparing as it seems to differ between http://med01. and http://www.
												$remove_from_previous_url = substr( strtolower($previous_url), 0, strpos( strtolower($previous_url), 'expert' ) );
												$previous_url = str_replace( $remove_from_previous_url, "", $previous_url);

												$remove_from_new_url = substr( strtolower($new_url), 0, strpos( strtolower($new_url), 'expert' ) );
												$new_url = str_replace( $remove_from_new_url, "", $new_url);
											}

											if (
												$previous_url == $new_url
												&&
												(
													get_post_meta( $previous_media_id, '_modified', TRUE ) == ''
													||
													(
														get_post_meta( $previous_media_id, '_modified', TRUE ) != '' &&
														get_post_meta( $previous_media_id, '_modified', TRUE ) == $modified
													)
												)
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
									        'name' => $filename . '.jpg',
									        'tmp_name' => $tmp
									    );

									    // Check for download errors
									    if ( is_wp_error( $tmp ) ) 
									    {
									        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->property_reference, $post_id );
									    }
									    else
									    {
										    $id = media_handle_sideload( $file_array, $post_id, $description );

										    // Check for handle sideload errors.
										    if ( is_wp_error( $id ) ) 
										    {
										        @unlink( $file_array['tmp_name'] );
										        
										        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->property_reference, $post_id );
										    }
										    else
										    {
										    	$media_ids[] = $id;

										    	update_post_meta( $id, '_imported_url', addslashes($url));
												update_post_meta( $id, '_modified', $modified);

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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->property_reference, $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if ( isset($property->floorplans) && !empty($property->floorplans) )
				{
					foreach ( $property->floorplans as $xml_floorplans )
					{
						foreach ( $xml_floorplans->floorplan as $floorplan )
						{
							$floorplan_attributes = $floorplan->attributes();

							if ( isset($floorplan->filename) && trim((string)$floorplan->filename) != '' )
							{
								if ( 
									substr( strtolower((string)$floorplan->filename), 0, 2 ) == '//' || 
									substr( strtolower((string)$floorplan->filename), 0, 4 ) == 'http'
								)
								{
									// This is a URL
									$floorplans[] = array( 
										"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
										"fave_plan_image" => str_replace(" ", "%20", (string)$floorplan->filename)
									);
								}
							}
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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', (string)$property->property_reference, $post_id );

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				if ( isset($property->brochure) && trim((string)$property->brochure) != '' )
				{
					if ( 
						substr( strtolower((string)$property->brochure), 0, 2 ) == '//' || 
						substr( strtolower((string)$property->brochure), 0, 4 ) == 'http'
					)
					{
						// This is a URL
						$url = str_replace(" ", "%20", (string)$property->brochure);
						$description = __( 'Brochure', 'houzezpropertyfeed' );
						
						$filename = basename( $url );

						// Check, based on the URL, whether we have previously imported this media
						$imported_previously = false;
						$imported_previously_id = '';
						if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
						{
							foreach ( $previous_media_ids as $previous_media_id )
							{
								$previous_url = get_post_meta( $previous_media_id, '_imported_url', TRUE );
								$new_url = $url;

								// Should contain 'expert' in URL but check first in case EA ever change their media hosting
								if ( strpos($previous_url, 'expert') !== FALSE && strpos($new_url, 'expert') !== FALSE )
								{
									// Need to remove first part of URLs before comparing as it seems to differ between http://med01. and http://www.
									$remove_from_previous_url = substr( strtolower($previous_url), 0, strpos( strtolower($previous_url), 'expert' ) );
									$previous_url = str_replace( $remove_from_previous_url, "", $previous_url);

									$remove_from_new_url = substr( strtolower($new_url), 0, strpos( strtolower($new_url), 'expert' ) );
									$new_url = str_replace( $remove_from_new_url, "", $new_url);
								}

								if ( $previous_url == $new_url )
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
						        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->property_reference, $post_id );
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
							        
							        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->property_reference, $post_id );
							    }
							    else
							    {
							    	$media_ids[] = $id;

							    	update_post_meta( $id, '_imported_url', v);

							    	++$new;
							    }
							}
						}
					}
				}

				if ( isset($property->epc) && trim((string)$property->epc) != '' )
				{
					if ( 
						substr( strtolower((string)$property->epc), 0, 2 ) == '//' || 
						substr( strtolower((string)$property->epc), 0, 4 ) == 'http'
					)
					{
						// This is a URL
						$url = str_replace(" ", "%20", (string)$property->epc);
						$description = __( 'EPC', 'houzezpropertyfeed' );
						    
						$filename = basename( $url );

						// Check, based on the URL, whether we have previously imported this media
						$imported_previously = false;
						$imported_previously_id = '';
						if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
						{
							foreach ( $previous_media_ids as $previous_media_id )
							{
								$previous_url = get_post_meta( $previous_media_id, '_imported_url', TRUE );
								$new_url = $url;

								// Should contain 'expert' in URL but check first in case EA ever change their media hosting
								if ( strpos($previous_url, 'expert') !== FALSE && strpos($new_url, 'expert') !== FALSE )
								{
									// Need to remove first part of URLs before comparing as it seems to differ between http://med01. and http://www.
									$remove_from_previous_url = substr( strtolower($previous_url), 0, strpos( strtolower($previous_url), 'expert' ) );
									$previous_url = str_replace( $remove_from_previous_url, "", $previous_url);

									$remove_from_new_url = substr( strtolower($new_url), 0, strpos( strtolower($new_url), 'expert' ) );
									$new_url = str_replace( $remove_from_new_url, "", $new_url);
								}
								
								if ( $previous_url == $new_url )
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
						        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->property_reference, $post_id );
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
							        
							        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->property_reference, $post_id );
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

				$this->log( 'Imported ' . count($media_ids) . ' brochures and EPCs (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->property_reference, $post_id );
				
				update_post_meta( $post_id, 'fave_video_url', '' );
				update_post_meta( $post_id, 'fave_virtual_tour', '' );

				if ( isset($property->virtual_tour_url) && trim((string)$property->virtual_tour_url) != '' )
				{
					if ( 
						substr( strtolower((string)$property->virtual_tour_url), 0, 2 ) == '//' || 
						substr( strtolower((string)$property->virtual_tour_url), 0, 4 ) == 'http'
					)
					{
						// This is a URL
						$url = (string)$property->virtual_tour_url;

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

				if ( isset($property_attributes['hash']) ) { update_post_meta( $post_id, '_expertagent_hash_' . $import_id, (string)$property_attributes['hash'] ); }

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_expertagent", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, (string)$property->property_reference, $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_expertagent", $this->import_id );

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
				$import_refs[] = (string)$property->property_reference;
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}