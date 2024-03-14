<?php
/**
 * Class for managing the import process of a BLM file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Blm extends Houzez_Property_Feed_Process {

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
		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$import_settings = get_import_settings_from_id( $this->import_id );

		if ( $import_settings['format'] == 'blm_local' )
		{
			$local_directory = $import_settings['local_directory'];

			// Get all zip files in date order
			$zip_files = array();
			if ($handle = opendir($local_directory)) 
			{
			    while (false !== ($file = readdir($handle))) 
			    {
			        if (
			        	$file != "." && $file != ".." && 
			        	substr(strtolower($file), -3) == 'zip'
			        ) 
			        {
			           $zip_files[filemtime($local_directory . '/' . $file)] = $local_directory . '/' . $file;
			        }
			    }
			    closedir($handle);
			}
			else
			{
				$this->log_error( 'Failed to read from directory ' . $local_directory . '. Please ensure the local directory specified exists, is the full server path and is readable.' );
				return false;
			}

			if (!empty($zip_files))
			{
				$this->log('Found ' . count($zip_files) . ' ZIPs ready to extract'); 

				if ( !class_exists('ZipArchive') ) 
				{ 
					$this->log_error('The ZipArchive class does not exist but is needed to extract the zip files provided'); 
					return false; 
				}

				ksort($zip_files);

				foreach ($zip_files as $mtime => $zip_file)
				{
					$zip = new ZipArchive;
					if ($zip->open($zip_file) === TRUE) 
					{
					    $zip->extractTo($local_directory);
					    $zip->close();
					    sleep(1); // We sleep to ensure each BLM has a different modified time in the same order

					    $this->log('Extracted ZIP ' . $zip_file); 
					}
					else
					{
						$this->log_error('Failed to open the ZIP ' . $zip_file); 
						return false; 
					}
					unlink($zip_file);
				}
			}

			unset($zip_files);

			// Now they've all been extracted, get BLM files in date order
			$blm_files = array();
			if ($handle = opendir($local_directory)) 
			{
			    while (false !== ($file = readdir($handle))) 
			    {
			        if (
			        	$file != "." && $file != ".." && 
			        	substr(strtolower($file), -3) == 'blm'
			        ) 
			        {
			           $blm_files[filemtime($local_directory . '/' . $file)] = $local_directory . '/' . $file;
			        }
			    }
			    closedir($handle);
			}

			if (!empty($blm_files))
			{
				ksort($blm_files); // sort by date modified

				// We've got at least one BLM to process

                foreach ($blm_files as $mtime => $blm_file)
                {
                	$this->properties = array(); // Reset properties in the event we're importing multiple files

                	$this->log("Parsing properties");

                	$parsed = false;

                	// Get BLM contents into memory
					$handle = fopen($blm_file, "r");
			        $blm_contents = fread($handle, filesize($blm_file));
			        fclose($handle);

			        $parsed_header = $this->parse_header($blm_contents);

			        if ( !$parsed_header ) return false;

			        $parsed_definitions = $this->parse_definitions($blm_contents);

			        if ( !$parsed_definitions ) return false;

			        $parsed_data = $this->parse_data($blm_contents);

			        if ( !$parsed_data ) return false;

                	// Parsed it succesfully. Ok to continue
                	if ( empty($this->properties) )
					{
						$this->log_error( 'No properties found. We\'re not going to continue as this could likely be wrong and all properties will get removed if we continue.' );
					}
					else
					{
	                    $this->import();

	                    $this->remove_old_properties();
	                }

	                $this->archive( $blm_file );
                }
			}
			else
			{
				$this->log_error( 'No BLM\'s found to process' );
			}

			$this->clean_up_old_blms();
		}

		if ( $import_settings['format'] == 'blm_remote' )
		{
			$this->log( 'This is a BLM remote file' );
		}

		return true;
	}

	private function parse_header( $blm_contents )
	{
		if ( strpos($blm_contents, '#HEADER#') !== FALSE )
		{
			$header = trim(substr($blm_contents, strpos($blm_contents, '#HEADER#')+8, strpos($blm_contents, '#DEFINITION#')-8));
	        $header_data = explode("\n", $header);

	        foreach ( $header_data as $header_row ) 
	        {
	            // get end of field character
	            if ( strpos($header_row, "EOF") !== FALSE ) 
	            {
	                $replace_array = array("EOF", " ", ":", "'", "\n", "\r");
	                $this->eof = str_replace($replace_array, "", $header_row);
	            }

	            // get end of record character
	            if ( strpos($header_row, "EOR") !== FALSE ) 
	            {
	                $replace_array = array("EOR", " ", ":", "'", "\n", "\r");
	                $this->eor = str_replace($replace_array, "", $header_row);
	            }
	        }

	        if ( $this->eof == '' )
		    {
		    	$this->log_error( 'The #HEADER# section does not specify an EOF character' );
		    	return false;
		    }
		    if ( $this->eor == '' )
		    {
		    	$this->log_error( 'The #HEADER# section does not specify an EOR character' );
		    	return false;
		    }
	    }
	    else
	    {
	    	$this->log_error( 'The uploaded BLM file is missing a #HEADER# section' );
	    	return false;
	    }

	    return true;
	}

	private function parse_definitions( $blm_contents )
	{
		if ( strpos($blm_contents, '#DEFINITION#') !== FALSE )
		{
			$definition_length = strpos($blm_contents, $this->eor, strpos($blm_contents,'#DEFINITION#'))-strpos($blm_contents,'#DEFINITION#')-12;
	        $definition = trim( substr($blm_contents, strpos($blm_contents, '#DEFINITION#') + 12, $definition_length) );
	        $definitions = explode($this->eof, $definition);
	        
	        array_pop($definitions); // remove last blank definition field

	        $this->definitions = $definitions;
	    }
	    else
	    {
	    	$this->log_error( 'The uploaded BLM file is missing a #DEFINITION# section' );
	    	return false;
	    }

	    return true;
	}

	private function parse_data( $blm_contents )
	{
		if ( strpos($blm_contents, '#DATA#') !== FALSE && strpos($blm_contents, '#END#') !== FALSE )
		{
			$data_length = strpos($blm_contents, '#END#')-strpos($blm_contents, '#DATA#')-6;
	        $data = trim(substr($blm_contents, strpos($blm_contents, '#DATA#')+6, $data_length)); 
	        $data = explode($this->eor, $data);

	        // Loop through properties 
	        $i = 1;
	        foreach ($data as $property) 
	        {
	            $property = trim($property); // Remove any new lines from beginning of property row

	            if ( $property != '' )
	            {
		            $field_values = explode($this->eof, $property);
		                            
		            array_pop($field_values); // Remove last blank data field

		            if (count($this->definitions) == count($field_values)) 
		            {
		            	// If the correct number of fields expected
		                                
		                $property = array();
		            
		                // Loop through property fields
		                foreach ($field_values as $field_number=>$field) 
		                {
		                    // Standard fields
		                    $property[$this->definitions[$field_number]] = utf8_encode($field); // set by default to value in .blm
		                
		                } // Finish looping through property fields 

		                $this->properties[] = $property;
		            }
		            else
		            {
		            	// Invalid number of fields
		            	$this->log_error( 'Property on row ' . $i . ' contains an invalid number of fields. Received: ' . count($field_values) . ', Expected: ' . count($this->definitions) );
		            	return false;
		            }
		        }

	            ++$i;
	        }
	    }
	    else
	    {
	    	$this->log_error( 'The uploaded BLM file is missing a #DATA# and/or #END# section' );
	    	return false;
	    }

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
        do_action( "houzez_property_feed_pre_import_properties_blm", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_blm", $this->properties, $this->import_id );

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
				if ( $property['AGENT_REF'] == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . $property['AGENT_REF'] );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, $property['AGENT_REF'], false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . $property['AGENT_REF'], $property['AGENT_REF'] );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => $property['AGENT_REF']
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = $property['DISPLAY_ADDRESS'];
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', $property['AGENT_REF'] );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => $property['SUMMARY'],
				    	'post_content' 	 => $property['DESCRIPTION'],
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), $property['AGENT_REF'] );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', $property['AGENT_REF'] );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => $property['SUMMARY'],
					'post_content' 	 => $property['DESCRIPTION'],
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), $property['AGENT_REF'] );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', $property['AGENT_REF'], $post_id );

				update_post_meta( $post_id, $imported_ref_key, $property['AGENT_REF'] );

				update_post_meta( $post_id, '_property_import_data', print_r($property, true) );

				$department = $property['TRANS_TYPE_ID'] != '2' ? 'residential-sales' : 'residential-lettings';

				$poa = false;
				if (isset($property['PRICE_QUALIFIER']) && $property['PRICE_QUALIFIER'] == '1' )
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
                	$price = round(preg_replace("/[^0-9.]/", '', $property['PRICE']));

                    update_post_meta( $post_id, 'fave_property_price_prefix', '' );
                    update_post_meta( $post_id, 'fave_property_price', $price );
                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );

                    if ( $property['TRANS_TYPE_ID'] == '2' )
                    {
                    	$rent_frequency = 'pcm';
						switch ($property['LET_RENT_FREQUENCY'])
						{
							case "0": { $rent_frequency = 'pw'; break; }
							case "1": { $rent_frequency = 'pcm'; break; }
							case "2": { $rent_frequency = 'pq';  break; }
							case "3": { $rent_frequency = 'pa'; break; }
							case "5": { $rent_frequency = 'pppw'; break; }
						}

						update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
                    }
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property['BEDROOMS']) ) ? $property['BEDROOMS'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property['BATHROOMS']) ) ? $property['BATHROOMS'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property['RECEPTIONS']) ) ? $property['RECEPTIONS'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' ); // need to look at parking
	            update_post_meta( $post_id, 'fave_property_id', $property['AGENT_REF'] );

	            $address_parts = array();
	            $address_to_geocode_osm = array();

	            if ( isset($property['ADDRESS_2']) && $property['ADDRESS_2'] != '' )
	            {
	                $address_parts[] = $property['ADDRESS_2'];
	            }
	            if ( isset($property['ADDRESS_3']) && $property['ADDRESS_3'] != '' )
	            {
	                $address_parts[] = $property['ADDRESS_3'];
	            }
	            if ( isset($property['TOWN']) && $property['TOWN'] != '' )
	            {
	                $address_parts[] = $property['TOWN'];
	            }
	            if ( isset($property['ADDRESS_4']) && $property['ADDRESS_4'] != '' )
	            {
	                $address_parts[] = $property['ADDRESS_4'];
	            }
	            if ( isset($property['POSTCODE1']) && isset($property['POSTCODE2']) && ( $property['POSTCODE1'] != '' || $property['POSTCODE2'] != '' ) )
	            {
	                $address_parts[] = trim( ( ( isset($property['POSTCODE1']) ) ? $property['POSTCODE1'] : '' ) . ' ' . ( ( isset($property['POSTCODE2']) ) ? $property['POSTCODE2'] : '' ) );
	                $address_to_geocode_osm[] = trim( ( ( isset($property['POSTCODE1']) ) ? $property['POSTCODE1'] : '' ) . ' ' . ( ( isset($property['POSTCODE2']) ) ? $property['POSTCODE2'] : '' ) );
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' );
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $lat = '';
	            $lng = '';
	            if ( isset($property['LATITUDE']) && !empty($property['LATITUDE']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_lat', $property['LATITUDE'] );
	                $lat = $property['LATITUDE'];
	            }
	            if ( isset($property['EXACT_LATITUDE']) && !empty($property['EXACT_LATITUDE']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_lat', $property['EXACT_LATITUDE'] );
	                $lat = $property['EXACT_LATITUDE'];
	            }
	            if ( isset($property['LONGITUDE']) && !empty($property['LONGITUDE']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_long', $property['LONGITUDE'] );
	                $lng = $property['LONGITUDE'];
	            }
	            if ( isset($property['EXACT_LONGITUDE']) && !empty($property['EXACT_LONGITUDE']) )
	            {
	                update_post_meta( $post_id, 'houzez_geolocation_long', $property['EXACT_LONGITUDE'] );
	                $lng = $property['EXACT_LONGITUDE'];
	            }
	            if ( empty($lat) || empty($lng) )
	            {
	            	// use existing
	            	$lat = get_post_meta( $post_id, 'houzez_geolocation_lat', true );
	            	$lng = get_post_meta( $post_id, 'houzez_geolocation_long', true );

	            	if ( empty($lat) || empty($lng) )
	            	{
	            		// need to geocode
	            		$geocoding_return = $this->do_geocoding_lookup( $post_id, $property['AGENT_REF'], $address_parts, $address_to_geocode_osm, 'GB' );
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
	            if ( isset($property['ADDRESS_1']) && $property['ADDRESS_1'] != '' )
	            {
	                $address_parts[] = $property['ADDRESS_1'];
	            }
	            if ( isset($property['ADDRESS_2']) && $property['ADDRESS_2'] != '' )
	            {
	                $address_parts[] = $property['ADDRESS_2'];
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property['POSTCODE1']) && isset($property['POSTCODE2']) && ( $property['POSTCODE1'] != '' || $property['POSTCODE2'] != '' ) ) ? trim( ( ( isset($property['POSTCODE1']) ) ? $property['POSTCODE1'] : '' ) . ' ' . ( ( isset($property['POSTCODE2']) ) ? $property['POSTCODE2'] : '' ) ) : '' ) );

	            add_post_meta( $post_id, 'fave_featured', '0', true );
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
	        	
	            //turn bullets into property features
	            $feature_term_ids = array();
	            for ( $i = 1; $i <= 10; ++$i )
				{
					if ( isset($property['FEATURE' . $i]) && trim($property['FEATURE' . $i]) != '' )
					{
						$feature = $property['FEATURE' . $i];

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

				if ( isset($property['STATUS_ID']) && $property['STATUS_ID'] != '' )
				{
					if ( isset($taxonomy_mappings[$property['STATUS_ID']]) && !empty($taxonomy_mappings[$property['STATUS_ID']]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['STATUS_ID']], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . $property['STATUS_ID'] . ' that isn\'t mapped in the import settings', $property['AGENT_REF'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, $property['STATUS_ID'], $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property['PROP_SUB_ID']) && !empty($property['PROP_SUB_ID']) )
				{
					if ( isset($taxonomy_mappings[$property['PROP_SUB_ID']]) && !empty($taxonomy_mappings[$property['PROP_SUB_ID']]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['PROP_SUB_ID']], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . $property['PROP_SUB_ID'] . ' that isn\'t mapped in the import settings', $property['AGENT_REF'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $property['PROP_SUB_ID'], $this->import_id );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', $property['AGENT_REF'], $post_id );
						}
					}
				}

				$files_to_unlink = array();
				for ( $i = 0; $i <= 49; ++$i )
				{
					$j = str_pad( $i, 2, '0', STR_PAD_LEFT );

					if ( isset($property['MEDIA_IMAGE_' . $j]) && trim($property['MEDIA_IMAGE_' . $j]) != '' )
					{
						if ( 
							substr( strtolower($property['MEDIA_IMAGE_' . $j]), 0, 2 ) == '//' || 
							substr( strtolower($property['MEDIA_IMAGE_' . $j]), 0, 4 ) == 'http'
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
							$url = $property['MEDIA_IMAGE_' . $j];
							$description = ( ( isset($property['MEDIA_IMAGE_TEXT_' . $j]) && $property['MEDIA_IMAGE_TEXT_' . $j] != '' ) ? $property['MEDIA_IMAGE_TEXT_' . $j] : '' );
							$explode_url = explode('?', $url);

							$filename = basename( $url );

							// Check, based on the URL, whether we have previously imported this media
							$imported_previously = false;
							$imported_previously_id = '';
							if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
							{
								foreach ( $previous_media_ids as $previous_media_id )
								{
									if ( 
										get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $explode_url[0]
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
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['AGENT_REF'], $post_id );
							    }
							    else
							    {
								    $id = media_handle_sideload( $file_array, $post_id, $description );

								    // Check for handle sideload errors.
								    if ( is_wp_error( $id ) ) 
								    {
								        @unlink( $file_array['tmp_name'] );
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['AGENT_REF'], $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $explode_url[0]);

								    	if ( $image_i == 0 ) set_post_thumbnail( $post_id, $id );

								    	++$new;

								    	++$image_i;

								    	update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
								    }
								}
							}
						}
						else
						{
							// Not a URL. Must've been physically uploaded or already exists
							$media_file_name = $property['MEDIA_IMAGE_' . $j];
							$description = ( ( isset($property['MEDIA_IMAGE_TEXT_' . $j]) && $property['MEDIA_IMAGE_TEXT_' . $j] != '' ) ? $property['MEDIA_IMAGE_TEXT_' . $j] : '' );
							
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
                                if ( isset($previous_media_ids[$i]) ) 
                                {                                    
                                    // get this attachment
                                    $current_image_path = get_post_meta( $previous_media_ids[$i], '_imported_path', TRUE );
                                    $current_image_size = filesize( $current_image_path );
                                    
                                    if ($current_image_size > 0 && $current_image_size !== FALSE)
                                    {
                                        $replacing_attachment_id = $previous_media_ids[$i];
                                        
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
	                                    	$this->log_error( 'Failed to get filesize of new image file ' . $local_directory . '/' . $media_file_name, $property['AGENT_REF'] );
	                                    }
                                        
                                        unset($new_image_size);
                                    }
                                    else
                                    {
                                    	$this->log_error( 'Failed to get filesize of existing image file ' . $current_image_path, $property['AGENT_REF'] );
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
										$this->log_error( print_r($upload['error'], TRUE), $property['AGENT_REF'] );
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
											$this->log_error( 'Failed inserting image attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), $property['AGENT_REF'] );
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
	                            	if ( isset($previous_media_ids[$i]) ) 
                                	{
                                		$media_ids[] = $previous_media_ids[$i];

                                		if ( $description != '' )
										{
											$my_post = array(
										    	'ID'          	 => $previous_media_ids[$i],
										    	'post_title'     => $description,
										    );

										 	// Update the post into the database
										    wp_update_post( $my_post );
										}

										if ( $image_i == 0 ) set_post_thumbnail( $post_id, $previous_media_ids[$i] );

										++$existing;

										++$image_i;

										update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
                                	}

                                	$files_to_unlink[] =$local_directory . '/' . $media_file_name;
	                            }
							}
							else
							{
								if ( isset($previous_media_ids[$i]) ) 
		                    	{
		                    		$media_ids[] = $previous_media_ids[$i];

		                    		if ( $description != '' )
									{
										$my_post = array(
									    	'ID'          	 => $previous_media_ids[$i],
									    	'post_title'     => $description,
									    );

									 	// Update the post into the database
									    wp_update_post( $my_post );
									}

									if ( $image_i == 0 ) set_post_thumbnail( $post_id, $previous_media_ids[$i] );

									++$existing;

									++$image_i;

									update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, $post_id . '|' . implode(",", $media_ids), false );
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['AGENT_REF'], $post_id );

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
				for ( $i = 0; $i <= 10; ++$i )
				{
					$j = str_pad( $i, 2, '0', STR_PAD_LEFT );

					if ( isset($property['MEDIA_FLOOR_PLAN_' . $j]) && trim($property['MEDIA_FLOOR_PLAN_' . $j]) != '' )
					{
						if ( 
							substr( strtolower($property['MEDIA_FLOOR_PLAN_' . $j]), 0, 2 ) == '//' || 
							substr( strtolower($property['MEDIA_FLOOR_PLAN_' . $j]), 0, 4 ) == 'http'
						)
						{
							$floorplans[] = array( 
								"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
								"fave_plan_image" => $property['MEDIA_FLOOR_PLAN_' . $j]
							);
						}
						else
						{
							// Not a URL. Must've been physically uploaded or already exists
							$media_file_name = $property['MEDIA_FLOOR_PLAN_' . $j];
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
	                                    	$this->log_error( 'Failed to get filesize of new floorplan file ' . $local_directory . '/' . $media_file_name, $property['AGENT_REF'] );
	                                    }
                                        
                                        unset($new_image_size);
                                    }
                                    else
                                    {
                                    	$this->log_error( 'Failed to get filesize of existing floorplan file ' . $current_image_path, $property['AGENT_REF'] );
                                    }
                                    
                                    unset($current_image_size);
                                }

                                if ($upload)
                                {
									// We've physically received the file
									$upload = wp_upload_bits(trim($media_file_name, '_'), null, file_get_contents($local_directory . '/' . $media_file_name));  
									$this->log( print_r($upload, TRUE) );
									if( isset($upload['error']) && $upload['error'] !== FALSE )
									{
										$this->log_error( print_r($upload['error'], TRUE), $property['AGENT_REF'] );
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
											$this->log_error( 'Failed inserting floorplan attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), $property['AGENT_REF'] );
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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', $property['AGENT_REF'], $post_id );

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );
				$files_to_unlink = array();

				for ( $i = 0; $i <= 10; ++$i )
				{
					$j = str_pad( $i, 2, '0', STR_PAD_LEFT );

					if ( isset($property['MEDIA_DOCUMENT_' . $j]) && trim($property['MEDIA_DOCUMENT_' . $j]) != '' )
					{
						if ( 
							substr( strtolower($property['MEDIA_DOCUMENT_' . $j]), 0, 2 ) == '//' || 
							substr( strtolower($property['MEDIA_DOCUMENT_' . $j]), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = $property['MEDIA_DOCUMENT_' . $j];
							$description = ( ( isset($property['MEDIA_DOCUMENT_TEXT_' . $j]) && $property['MEDIA_DOCUMENT_TEXT_' . $j] != '' ) ? $property['MEDIA_DOCUMENT_TEXT_' . $j] : __( 'Brochure', 'houzezpropertyfeed' ) );
							$modified = ( isset($property['UPDATE_DATE']) && $property['UPDATE_DATE'] != '' ) ? $property['UPDATE_DATE'] : '';

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
										(
											$modified == '' // no UPDATE_DATE passed
											||
											(
												$modified != '' &&
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
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['AGENT_REF'], $post_id );
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
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['AGENT_REF'], $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $url);
								    	if ( $modified != '' )
										{
											update_post_meta( $id, '_modified', $modified);
										}

								    	++$new;
								    }
								}
							}
						}
						else
						{
							// Not a URL. Must've been physically uploaded or already exists
							$media_file_name = $property['MEDIA_DOCUMENT_' . $j];
							$description = ( ( isset($property['MEDIA_DOCUMENT_TEXT_' . $j]) && $property['MEDIA_DOCUMENT_TEXT_' . $j] != '' ) ? $property['MEDIA_DOCUMENT_TEXT_' . $j] : __( 'Brochure', 'houzezpropertyfeed' ) );
							
							if ( file_exists( $local_directory . '/' . $media_file_name ) )
							{
								$upload = true;
                                $replacing_attachment_id = '';
                                if ( isset($previous_media_ids[$i]) ) 
                                {                                    
                                    // get this attachment
                                    $current_image_path = get_post_meta( $previous_media_ids[$i], '_imported_path', TRUE );
                                    $current_image_size = filesize( $current_image_path );
                                    
                                    if ($current_image_size > 0 && $current_image_size !== FALSE)
                                    {
                                        $replacing_attachment_id = $previous_media_ids[$i];
                                        
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
	                                    	$this->log_error( 'Failed to get filesize of new brochure file ' . $local_directory . '/' . $media_file_name, $property['AGENT_REF'] );
	                                    }
                                        
                                        unset($new_image_size);
                                    }
                                    else
                                    {
                                    	$this->log_error( 'Failed to get filesize of existing brochure file ' . $current_image_path, $property['AGENT_REF'] );
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
										$this->log_error( print_r($upload['error'], TRUE), $property['AGENT_REF'] );
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
											$this->log_error( 'Failed inserting brochure attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), $property['AGENT_REF'] );
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
	                            	if ( isset($previous_media_ids[$i]) ) 
                                	{
                                		$media_ids[] = $previous_media_ids[$i];

                                		if ( $description != '' )
										{
											$my_post = array(
										    	'ID'          	 => $previous_media_ids[$i],
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
								if ( isset($previous_media_ids[$i]) ) 
		                    	{
		                    		$media_ids[] = $previous_media_ids[$i];

		                    		if ( $description != '' )
									{
										$my_post = array(
									    	'ID'          	 => $previous_media_ids[$i],
									    	'post_title'     => $description,
									    );

									 	// Update the post into the database
									    wp_update_post( $my_post );
									}

									++$existing;
		                    	}
							}
						}
					}
				}

				for ( $i = 60; $i <= 61; ++$i )
				{
					$j = str_pad( $i, 2, '0', STR_PAD_LEFT );

					if ( isset($property->{'MEDIA_IMAGE_' . $j}) && trim($property['MEDIA_IMAGE_' . $j]) != '' )
					{
						if ( 
							substr( strtolower($property['MEDIA_IMAGE_' . $j]), 0, 2 ) == '//' || 
							substr( strtolower($property['MEDIA_IMAGE_' . $j]), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = $property['MEDIA_IMAGE_' . $j];

							$description = ( ( isset($property['MEDIA_IMAGE_TEXT_' . $j]) && $property['MEDIA_IMAGE_TEXT_' . $j] != '' ) ? $property['MEDIA_IMAGE_TEXT_' . $j] : '' );
						    
							$filename = basename( $url );

							// Check, based on the URL, whether we have previously imported this media
							$imported_previously = false;
							$imported_previously_id = '';
							if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
							{
								foreach ( $previous_media_ids as $previous_media_id )
								{
									if ( get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $explode_url[0] )
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
							        'name' => $filename . '.jpg',
							        'tmp_name' => $tmp
							    );

							    // Check for download errors
							    if ( is_wp_error( $tmp ) ) 
							    {
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['AGENT_REF'], $post_id );
							    }
							    else
							    {
								    $id = media_handle_sideload( $file_array, $post_id, $description );

								    // Check for handle sideload errors.
								    if ( is_wp_error( $id ) ) 
								    {
								        @unlink( $file_array['tmp_name'] );
								        
								        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['AGENT_REF'], $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $explode_url[0]);

								    	++$new;
								    }
								}
							}
						}
						else
						{
							// Not a URL. Must've been physically uploaded or already exists
							$media_file_name = $property['MEDIA_IMAGE_' . $j];
							$description = ( ( isset($property['MEDIA_IMAGE_TEXT_' . $j]) && $property['MEDIA_IMAGE_TEXT_' . $j] != '' ) ? $property['MEDIA_IMAGE_TEXT_' . $j] : __( 'EPC', 'houzezpropertyfeed' ) );
							
							if ( file_exists( $local_directory . '/' . $media_file_name ) )
							{
								$upload = true;
                                $replacing_attachment_id = '';
                                if ( isset($previous_media_ids[$i]) ) 
                                {                                    
                                    // get this attachment
                                    $current_image_path = get_post_meta( $previous_media_ids[$i], '_imported_path', TRUE );
                                    $current_image_size = filesize( $current_image_path );
                                    
                                    if ($current_image_size > 0 && $current_image_size !== FALSE)
                                    {
                                        $replacing_attachment_id = $previous_media_ids[$i];
                                        
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
	                                    	$this->log_error( 'Failed to get filesize of new EPC file ' . $local_directory . '/' . $media_file_name, $property['AGENT_REF'] );
	                                    }
                                        
                                        unset($new_image_size);
                                    }
                                    else
                                    {
                                    	$this->log_error( 'Failed to get filesize of existing EPC file ' . $current_image_path, $property['AGENT_REF'] );
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
										$this->log_error( print_r($upload['error'], TRUE), $property['AGENT_REF'] );
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
											$this->log_error( 'Failed inserting EPC attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), $property['AGENT_REF'] );
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
	                            	if ( isset($previous_media_ids[$i]) ) 
                                	{
                                		$media_ids[] = $previous_media_ids[$i];

                                		if ( $description != '' )
										{
											$my_post = array(
										    	'ID'          	 => $previous_media_ids[$i],
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
								if ( isset($previous_media_ids[$i]) ) 
		                    	{
		                    		$media_ids[] = $previous_media_ids[$i];

		                    		if ( $description != '' )
									{
										$my_post = array(
									    	'ID'          	 => $previous_media_ids[$i],
									    	'post_title'     => $description,
									    );

									 	// Update the post into the database
									    wp_update_post( $my_post );
									}

									++$existing;
		                    	}
							}
						}
					}
				}

				for ( $i = 50; $i <= 55; ++$i )
				{
					$j = str_pad( $i, 2, '0', STR_PAD_LEFT );

					if ( isset($property['MEDIA_DOCUMENT_' . $j]) && trim($property['MEDIA_DOCUMENT_' . $j]) != '' )
					{
						if ( 
							substr( strtolower($property['MEDIA_DOCUMENT_' . $j]), 0, 2 ) == '//' || 
							substr( strtolower($property['MEDIA_DOCUMENT_' . $j]), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = $property['MEDIA_IMAGE_' . $j];
							$explode_url = explode('?', $url);
							$description = '';
						    
							$filename = basename( $url );

							// Check, based on the URL, whether we have previously imported this media
							$imported_previously = false;
							$imported_previously_id = '';
							if ( is_array($previous_media_ids) && !empty($previous_media_ids) )
							{
								foreach ( $previous_media_ids as $previous_media_id )
								{
									if ( 
										get_post_meta( $previous_media_id, '_imported_url', TRUE ) == $explode_url[0]
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
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['AGENT_REF'], $post_id );
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
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['AGENT_REF'], $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $explode_url[0]);

								    	++$new;
								    }
								}
							}
						}
						else
						{
							// Not a URL. Must've been physically uploaded or already exists
							$media_file_name = $property['MEDIA_DOCUMENT_' . $j];
							$description = ( ( isset($property['MEDIA_DOCUMENT_TEXT_' . $j]) && $property['MEDIA_DOCUMENT_TEXT_' . $j] != '' ) ? $property['MEDIA_DOCUMENT_TEXT_' . $j] : __( 'EPC', 'houzezpropertyfeed' ) );
							
							if ( file_exists( $local_directory . '/' . $media_file_name ) )
							{
								$upload = true;
                                $replacing_attachment_id = '';
                                if ( isset($previous_media_ids[$i]) ) 
                                {                                    
                                    // get this attachment
                                    $current_image_path = get_post_meta( $previous_media_ids[$i], '_imported_path', TRUE );
                                    $current_image_size = filesize( $current_image_path );
                                    
                                    if ($current_image_size > 0 && $current_image_size !== FALSE)
                                    {
                                        $replacing_attachment_id = $previous_media_ids[$i];
                                        
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
	                                    	$this->log_error( 'Failed to get filesize of new EPC file ' . $local_directory . '/' . $media_file_name, $property['AGENT_REF'] );
	                                    }
                                        
                                        unset($new_image_size);
                                    }
                                    else
                                    {
                                    	$this->log_error( 'Failed to get filesize of existing EPC file ' . $current_image_path, $property['AGENT_REF'] );
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
										$this->log_error( print_r($upload['error'], TRUE), $property['AGENT_REF'] );
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
											$this->log_error( 'Failed inserting EPC attachment ' . $upload['file'] . ' - ' . print_r($attachment, TRUE), $property['AGENT_REF'] );
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
	                            	if ( isset($previous_media_ids[$i]) ) 
                                	{
                                		$media_ids[] = $previous_media_ids[$i];

                                		if ( $description != '' )
										{
											$my_post = array(
										    	'ID'          	 => $previous_media_ids[$i],
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
								if ( isset($previous_media_ids[$i]) ) 
		                    	{
		                    		$media_ids[] = $previous_media_ids[$i];

		                    		if ( $description != '' )
									{
										$my_post = array(
									    	'ID'          	 => $previous_media_ids[$i],
									    	'post_title'     => $description,
									    );

									 	// Update the post into the database
									    wp_update_post( $my_post );
									}

									++$existing;
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

				if ( !empty($files_to_unlink) )
				{
					foreach ( $files_to_unlink as $file_to_unlink )
					{
						unlink($file_to_unlink);
					}
				}

				$this->log( 'Imported ' . count($media_ids) . ' brochures and EPCs (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['AGENT_REF'], $post_id );
				
				update_post_meta( $post_id, 'fave_video_url', '' );
				update_post_meta( $post_id, 'fave_virtual_tour', '' );

				for ( $i = 0; $i <= 5; ++$i )
				{
					$j = str_pad( $i, 2, '0', STR_PAD_LEFT );

					if ( isset($property['MEDIA_VIRTUAL_TOUR_' . $j]) && trim($property['MEDIA_VIRTUAL_TOUR_' . $j]) != '' )
					{
						if ( 
							substr( strtolower($property['MEDIA_VIRTUAL_TOUR_' . $j]), 0, 2 ) == '//' || 
							substr( strtolower($property['MEDIA_VIRTUAL_TOUR_' . $j]), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = trim($property['MEDIA_VIRTUAL_TOUR_' . $j]);

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
				do_action( "houzez_property_feed_property_imported_blm", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, $property['AGENT_REF'], $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_blm", $this->import_id );

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
				$import_refs[] = $property['AGENT_REF'];
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}

	private function clean_up_old_blms()
    {
    	$import_settings = get_import_settings_from_id( $this->import_id );

    	$local_directory = $import_settings['local_directory'];

    	// Clean up processed .BLMs and unused media older than 7 days old (7 days = 604800 seconds)
		if ($handle = opendir($local_directory)) 
		{
		    while (false !== ($file = readdir($handle))) 
		    {
		        if (
		        	$file != "." && $file != ".." && 
		        	(
		        		substr($file, -9) == 'processed' || 
		        		substr(strtolower($file), -4) == '.jpg' || 
		        		substr(strtolower($file), -4) == '.gif' || 
		        		substr(strtolower($file), -5) == '.jpeg' || 
		        		substr(strtolower($file), -4) == '.png' || 
		        		substr(strtolower($file), -4) == '.bmp' || 
		        		substr(strtolower($file), -4) == '.pdf'
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

	private function archive( $blm_file )
    {
    	// Rename to append the date and '.processed' as to not get picked up again. Will be cleaned up every 7 days
    	$new_target_file = $blm_file . '-' . time() .'.processed';
		rename( $blm_file, $new_target_file );
		
		$this->log( 'Archived BLM. Available for download for 7 days: <a href="' . str_replace("/includes/import-formats", "", plugin_dir_url( __FILE__ )) . "/download.php?import_id=" . $this->import_id . "&file=" . base64_encode(basename($new_target_file)) . '" target="_blank">Download</a>' );
	}
}

}