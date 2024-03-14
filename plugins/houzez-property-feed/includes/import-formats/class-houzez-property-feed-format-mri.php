<?php
/**
 * Class for managing the import process of a Mri XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Mri extends Houzez_Property_Feed_Process {

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

		$import_settings = get_import_settings_from_id( $this->import_id );

		$departments = array( 'RS', 'RL' );

		$departments = apply_filters( 'houzez_property_feed_mri_departments', $departments );

		foreach ( $departments as $department )
		{
			$this->log("Parsing " . $department . " properties");

			$data = array(
		        'upw' => $import_settings['password'],
		        'de' => $department,
		        'pp' => 1000,
		    );

	  		$postvars = http_build_query($data);

			$contents = '';

			$response = wp_remote_post(
				$import_settings['xml_url'],
				array(
					'method' => 'POST',
					'headers' => array(),
					'body' => $postvars,
					'timeout' => 300
			    )
			);
			if ( !is_wp_error($response) && is_array( $response ) ) 
			{
				$contents = $response['body'];
			}
			else
			{
				$this->log_error( "Failed to obtain XML. Dump of response as follows: " . print_r($response, TRUE) );

	        	return false;
			}

			$xml = simplexml_load_string($contents);

			if ($xml !== FALSE)
			{
				if ( isset($xml->houses->property) && !empty($xml->houses->property) )
				{
					foreach ( $xml->houses->property as $property ) 
					{
						// Get full details XML so we can obtain features and full description
						$response = wp_remote_get(
							str_replace("aspasia_search.xml", "xml_export.xml?prn=N&preg=N&pid=" . (string)$property->id, $import_settings['xml_url']),
							array()
						);

						if ( is_wp_error( $response ) ) 
						{
							$this->log_error( 'Failed to request property ' . (string)$property->id . ': ' . $response->get_error_message() );
							return false;
						}

						$single_property_contents = simplexml_load_string($response['body']);

						if ( $single_property_contents === false )
						{
							$this->log_error( 'Failed to decode property ' . (string)$property->id . ' request body: ' . $response['body'] );
							return false;
						}

						// Some feeds contain an outer containing node. Catch this to get the data correctly
						if ( property_exists($single_property_contents, 'PROPERTY') )
						{
							$single_property_contents = $single_property_contents->PROPERTY;
						}

						$property->latitude = $single_property_contents->ADDRESS->LATITUDE;
						$property->longitude = $single_property_contents->ADDRESS->LONGITUDE;

						$features_xml = $property->addChild('features');
						if ( isset($single_property_contents->SELLPOINTS) )
						{
							$feature_i = 0;
							foreach ( $single_property_contents->SELLPOINTS as $sellpoints )
							{
								foreach ( $sellpoints->PARA as $para )
								{
									//$features_xml->addChild('feature');
									$property->features[$feature_i] = (string)$para;
									++$feature_i;
								}
							}
						}

						$rooms_xml = $property->addChild('rooms');
						if ( isset($single_property_contents->ACCOMMODATION) )
						{
							$room_i = 0;
							foreach ( $single_property_contents->ACCOMMODATION as $accommodation )
							{
								foreach ( $accommodation->FLOOR as $floor )
								{
									foreach ( $floor->ROOM as $room )
									{
										$room_xml = $rooms_xml->addChild('room');
										$room_xml->addChild('name');
										$room_xml->name = (string)$room->TITLE;
										$room_xml->dimensions = ( property_exists($room, 'DIMENSIONS') ) ? (string)$room->DIMENSIONS : '';
										$description = array();
										$room_xml->addChild('description');
										foreach ( $room->PARA as $para )
										{
											$description[] = html_entity_decode((string)$para, ENT_QUOTES | ENT_HTML5);
										}
										$room_xml->description = implode("\n\n", $description);
										++$room_i;
									}
								}
							}
						}

						$extras_xml = $property->addChild('extras');
						if ( isset($single_property_contents->EXTRAS) )
						{
							$extra_i = 0;
							foreach ( $single_property_contents->EXTRAS as $extras )
							{
								foreach ( $extras->ITEM as $item )
								{
									$extra_xml = $extras_xml->addChild('extra');
									$extra_xml->addChild('name');
									$extra_xml->name = (string)$item->TITLE;

									$description = array();
									$extra_xml->addChild('description');
									foreach ( $item->PARA as $para )
									{
										$description[] = html_entity_decode((string)$para, ENT_QUOTES | ENT_HTML5);
									}
									$extra_xml->description = implode("\n\n", $description);

									++$extra_i;
								}
							}
						}
						
						$this->properties[] = $property;
					}
				}
	        }
	        else
	        {
	        	// Failed to parse XML
	        	$this->log_error( 'Failed to parse XML file. Possibly invalid XML' );

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
        do_action( "houzez_property_feed_pre_import_properties_mri", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_mri", $this->properties, $this->import_id );

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
				if ( (string)$property->id == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . (string)$property->id );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, (string)$property->id, false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . (string)$property->id, (string)$property->id );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => (string)$property->id
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = (string)$property->address->display_address;

	        $summary_description = '';
			if ( isset($property->property_summary->short_description->para) && !empty($property->property_summary->short_description->para) )
			{
				foreach ( $property->property_summary->short_description->para as $para )
				{
					if ( $summary_description != '' )
					{
						$summary_description .= "\n\n";
					}
					$summary_description .= (string)$para;
				}
			}

			$post_content = '';
			if ( isset($property->property_summary->long_description->para) && !empty($property->property_summary->long_description->para) )
			{
				foreach ( $property->property_summary->long_description->para as $para )
				{
					$post_content .= '<p>' . (string)$para . '</p>';
				}
			}
		    if ( isset($property->rooms->room) && !empty($property->rooms->room) )
			{
				foreach ( $property->rooms->room as $room )
				{
		            $room_content = ( isset($room->name) && !empty((string)$room->name) ) ? '<strong>' . (string)$room->name . '</strong>' : '';
					$room_content .= ( isset($room->dimensions) && !empty((string)$room->dimensions) ) ? ' (' . (string)$room->dimensions . ')' : '';
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
		    if ( isset($property->extras) && !empty($property->extras) )
			{
				foreach ( $property->extras as $extras )
				{
					foreach ( $extras->extra as $extra )
					{
			            $room_content = ( isset($extra->name) && !empty((string)$extra->name) ) ? '<strong>' . (string)$extra->name . '</strong>' : '';
						if ( isset($extra->description) && !empty((string)$extra->description) ) 
						{
							if ( !empty($room_content) ) { $room_content .= '<br>'; }
							$room_content .= (string)$extra->description;
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
	        	$this->log( 'This property has been imported before. Updating it', (string)$property->id );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => $summary_description,
				    	'post_content' 	 => $post_content,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->id );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', (string)$property->id );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => $summary_description,
					'post_content' 	 => $post_content,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), (string)$property->id );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', (string)$property->id, $post_id );

				update_post_meta( $post_id, $imported_ref_key, (string)$property->id );

				update_post_meta( $post_id, '_property_import_data', $property->asXML() );

				$department = 'residential-sales';
				if ( (string)$property->department == 'RL' )
				{
					$department = 'residential-lettings';
				}

				$price_text = (string)$property->property_summary->price_text;

				$poa = false;
				if ( strpos(strtolower($price_text), 'poa') !== FALSE || strpos(strtolower($price_text), 'price on application') !== FALSE || strpos(strtolower($price_text), 'rent on application') !== FALSE )
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
                	$price = round(preg_replace("/[^0-9.]/", '', (string)$property->property_summary->price));

                	$prefix = '';
                	if ( $department == 'residential-sales' )
                	{
	                	$price_text = (string)$property->property_summary->price_text;
	                	$explode_price_bits = explode(" ", $price_text);

	                	$price_text_array = array();
	                	foreach ( $explode_price_bits as $bit )
	                	{
	                		if ( preg_match('/\d/', $bit) )
	                		{
	                			// contains number
	                			break;
	                		}
	                		else
	                		{
	                			$price_text_array[] = $bit;
	                		}
	                	}

	                	if ( !empty($price_text_array) )
	                	{
	                		$prefix = trim(implode( ' ', $price_text_array ));
	                	}
	                }

                    update_post_meta( $post_id, 'fave_property_price_prefix', $prefix );
                    update_post_meta( $post_id, 'fave_property_price', $price );
                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );

                    if ( $department == 'residential-lettings' )
                    {
                    	$rent_frequency = 'pcm';
						if ( strpos( strtolower((string)$property->property_summary->price_text), 'week') !== FALSE )
						{
							$rent_frequency = 'pw';
						}

						update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
                    }
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property->property_summary->beds) ) ? (string)$property->property_summary->beds : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property->property_summary->baths) ) ? (string)$property->property_summary->baths : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property->property_summary->receptions) ) ? (string)$property->property_summary->receptions : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' ); // need to look at parking
	            update_post_meta( $post_id, 'fave_property_id', (string)$property->id );

	            $address_parts = array();
	            if ( isset($property->address->address2) && (string)$property->address->address2 != '' )
	            {
	                $address_parts[] = (string)$property->address->address2;
	            }
	            if ( isset($property->address->address3) && (string)$property->address->address3 != '' )
	            {
	                $address_parts[] = (string)$property->address->address3;
	            }
	            if ( isset($property->address->town) && (string)$property->address->town != '' )
	            {
	                $address_parts[] = (string)$property->address->town;
	            }
	            if ( isset($property->address->county) && (string)$property->address->county != '' )
	            {
	                $address_parts[] = (string)$property->address->county;
	            }
	            if ( isset($property->address->postcode) && ( (string)$property->address->postcode != '' ) )
	            {
	                $address_parts[] = (string)$property->address->postcode;
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
	            if ( isset($property->address->address2) && (string)$property->address->address2 != '' )
	            {
	                $address_parts[] = (string)$property->address->address2;
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property->address->postcode) && (string)$property->address->postcode != '' ) ? (string)$property->address->postcode : '' ) );

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
	            foreach ( $property->features as $feature )
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

				if ( isset($property->property_summary->status) && !empty((string)$property->property_summary->status) )
				{
					if ( isset($taxonomy_mappings[(string)$property->property_summary->status]) && !empty($taxonomy_mappings[(string)$property->property_summary->status]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->property_summary->status], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . (string)$property->property_summary->status . ' that isn\'t mapped in the import settings', (string)$property->id, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, (string)$property->property_summary->status, $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property->extra_info->prty_code) && isset($property->extra_info->prst_code) && !empty((string)$property->extra_info->prty_code) && !empty((string)$property->extra_info->prst_code) )
				{
					if ( isset($taxonomy_mappings[(string)$property->extra_info->prty_code . '-' . (string)$property->extra_info->prst_code]) && !empty($taxonomy_mappings[(string)$property->extra_info->prty_code . '-' . (string)$property->extra_info->prst_code]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->extra_info->prty_code . '-' . (string)$property->extra_info->prst_code], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . (string)$property->extra_info->prty_code . '-' . (string)$property->extra_info->prst_code . ' that isn\'t mapped in the import settings', (string)$property->id, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', (string)$property->extra_info->prty_code . '-' . (string)$property->extra_info->prst_code, $this->import_id );
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
						if ( isset($property->address->{$address_field_to_use}) && !empty((string)$property->address->{$address_field_to_use}) )
		            	{
		            		$term = term_exists( trim((string)$property->address->{$address_field_to_use}), $location_taxonomy);
							if ( $term !== 0 && $term !== null && isset($term['term_id']) )
							{
								$location_term_ids[] = (int)$term['term_id'];
							}
							else
							{
								if ( $create_location_taxonomy_terms === true )
								{
									$term = wp_insert_term( trim((string)$property->address->{$address_field_to_use}), $location_taxonomy );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', (string)$property->id, $post_id );
						}
					}
				}

				if (isset($property->images->pictures) && !empty($property->images->pictures))
                {
                    foreach ($property->images->pictures as $images)
                    {
                        if (!empty($images->picture))
                        {
                            foreach ($images->picture as $image)
                            {
                            	$media_attributes = $image->attributes();

								if ( 
									isset($media_attributes['type']) &&
									$media_attributes['type'] == 'image' &&
									(
										substr( strtolower((string)$image), 0, 2 ) == '//' || 
										substr( strtolower((string)$image), 0, 4 ) == 'http'
									)
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
									$url = (string)$image;
									$description = (string)$media_attributes['description'];

									$modified = (string)$media_attributes['updated_date'];

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
									        'name' => $filename,
									        'tmp_name' => $tmp
									    );

									    // Check for download errors
									    if ( is_wp_error( $tmp ) ) 
									    {
									        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->id, $post_id );
									    }
									    else
									    {
										    $id = media_handle_sideload( $file_array, $post_id, $description );

										    // Check for handle sideload errors.
										    if ( is_wp_error( $id ) ) 
										    {
										        @unlink( $file_array['tmp_name'] );
										        
										        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->id, $post_id );
										    }
										    else
										    {
										    	$media_ids[] = $id;

										    	update_post_meta( $id, '_imported_url', $url );
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->id, $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if (isset($property->images->floorplans) && !empty($property->images->floorplans))
                {
                    foreach ($property->images->floorplans as $images)
                    {
                        if (!empty($images->floorplan))
                        {
                            foreach ($images->floorplan as $image)
                            {
                            	$media_attributes = $image->attributes();

								if ( 
									substr( strtolower((string)$image), 0, 2 ) == '//' || 
									substr( strtolower((string)$image), 0, 4 ) == 'http'
								)
								{
									// This is a URL
									$url = (string)$image;

									$floorplans[] = array( 
										"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
										"fave_plan_image" => $url
									);
								}
							}
						}
					}
				}

				for ( $i = 1; $i <= 30; ++$i )
				{
					if (isset($property->images->{'floorplan' . $i}) && !empty($property->images->{'floorplan' . $i}))
                	{
                    	$media_attributes = $property->images->{'floorplan' . $i}->attributes();

						if ( 
							substr( strtolower((string)$property->images->{'floorplan' . $i}), 0, 2 ) == '//' || 
							substr( strtolower((string)$property->images->{'floorplan' . $i}), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = (string)$property->images->{'floorplan' . $i};

							$floorplans[] = array( 
								"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', (string)$property->id, $post_id );

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				if ( isset($property->links->brochure) && (string)$property->links->brochure != '' )
                {
                	$media_attributes = $property->links->brochure->attributes();

					if ( 
						substr( strtolower((string)$property->links->brochure), 0, 2 ) == '//' || 
						substr( strtolower((string)$property->links->brochure), 0, 4 ) == 'http'
					)
					{
						// This is a URL
						$url = (string)$property->links->brochure;
						$description = __( 'Brochure', 'houzezpropertyfeed' );
						$modified = (string)$media_attributes['updated_date'];
						
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
						        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->id, $post_id );
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
							        
							        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->id, $post_id );
							    }
							    else
							    {
							    	$media_ids[] = $id;

							    	update_post_meta( $id, '_imported_url', $url);
							    	update_post_meta( $id, '_modified', $modified);

							    	++$new;
							    }
							}
						}
					}
				}

				if (isset($property->images->epcs) && !empty($property->images->epcs))
                {
                    foreach ($property->images->epcs as $images)
                    {
                        if (!empty($images->epc))
                        {
                            foreach ($images->epc as $image)
                            {
                            	$media_attributes = $image->attributes();

								if ( 
									substr( strtolower((string)$image), 0, 2 ) == '//' || 
									substr( strtolower((string)$image), 0, 4 ) == 'http'
								)
								{
									// This is a URL
									$url = (string)$image;

									$description = isset($media_attributes['description']) ? (string)$media_attributes['description'] : __( 'EPC', 'houzezpropertyfeed' );

									$modified = (string)$media_attributes['updated_date'];

									$explode_url = explode("?", $url);
									$filename = basename( $explode_url[0] );

									if ( strpos($filename, '.') === FALSE )
									{
										$filename .= '.jpg';
									}

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
									        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->id, $post_id );
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
										        
										        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->id, $post_id );
										    }
										    else
										    {
										    	$media_ids[] = $id;

										    	update_post_meta( $id, '_imported_url', $url);
										    	update_post_meta( $id, '_modified', $modified);

										    	++$new;
										    }
										}
									}
								}
							}
						}
					}
				}

				for ( $i = 1; $i <= 30; ++$i )
				{
					if (isset($property->images->{'epc' . $i}) && !empty($property->images->{'epc' . $i}))
                	{
                    	$media_attributes = $property->images->{'epc' . $i}->attributes();

						if ( 
							substr( strtolower((string)$property->images->{'epc' . $i}), 0, 2 ) == '//' || 
							substr( strtolower((string)$property->images->{'epc' . $i}), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = (string)$property->images->{'epc' . $i};

							$description = isset($media_attributes['description']) ? (string)$media_attributes['description'] : __( 'EPC', 'houzezpropertyfeed' );

							$modified = (string)$media_attributes['updated_date'];

							$explode_url = explode("?", $url);
							$filename = basename( $explode_url[0] );

							if ( strpos($filename, '.') === FALSE )
							{
								$filename .= '.jpg';
							}

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
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property->id, $post_id );
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
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property->id, $post_id );
								    }
								    else
								    {
								    	$media_ids[] = $id;

								    	update_post_meta( $id, '_imported_url', $url);
								    	update_post_meta( $id, '_modified', $modified);

								    	++$new;
								    }
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

				$this->log( 'Imported ' . count($media_ids) . ' brochures and EPCs (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->id, $post_id );
				
				update_post_meta( $post_id, 'fave_video_url', '' );
				update_post_meta( $post_id, 'fave_virtual_tour', '' );

				$virtual_tour_urls = array();
				if (isset($property->links->virtual_tour) && (string)$property->links->virtual_tour)
                {
                    $virtual_tour_urls[] = (string)$property->links->virtual_tour;
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
				do_action( "houzez_property_feed_property_imported_mri", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, (string)$property->id, $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_mri", $this->import_id );

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
				$import_refs[] = (string)$property->id;
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}