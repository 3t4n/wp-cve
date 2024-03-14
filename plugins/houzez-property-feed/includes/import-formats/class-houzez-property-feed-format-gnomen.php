<?php
/**
 * Class for managing the import process of a Gnomen XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Gnomen extends Houzez_Property_Feed_Process {

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

		$xml = simplexml_load_file( $import_settings['xml_url'] );
		if ($xml !== FALSE)
		{
			foreach ($xml->property as $property)
			{
				if ( strpos($import_settings['xml_url'], '?key=') !== FALSE )
				{
					// This feed has an API key which I think means it uses a format whereby we don't need to go off and get the full details

					$property->area = isset($property->town) ? $property->town : '';
					$property->description = isset($property->short_description) ? $property->short_description : '';
					$property->beds = isset($property->bedrooms) ? $property->bedrooms : '';
					$property->baths = isset($property->bathrooms) ? $property->bathrooms : '';
					$property->base_price = isset($property->price) ? $property->price : '';
					$property->videotour = isset($property->video_tour) ? $property->video_tour : '';

					$property->branch_name = '';
					$property->tenure = isset($property->tenure) ? $property->tenure : '';

					if ( !isset($property->status) || ( isset($property->status) && strtolower((string)$property->status) != 'withdrawn' ) )
					{
	                	$this->properties[] = $property;
	                }
				}
				else
				{
					$explode_url = explode("/", $import_settings['xml_url']);
					if ( $explode_url[count($explode_url)-1] == 'xml-feed' )
					{
						$url = trim($import_settings['xml_url'], '/') . '-details~action=detail,pid=' . (string)$property->id;
					}
					else
					{
						$url = trim($import_settings['xml_url'], '/') . '/xml-feed-details~action=detail,pid=' . (string)$property->id;
					}

					$response = wp_remote_get( $url );

					if ( is_array( $response ) ) 
					{
						$body = $response['body']; 
					
						$xml_string = $body;
						if ( substr($xml_string, 0, 5) != '<?xml' )
						{
							$xml_string = '<?xml version="1.0" encoding="utf-8"?>' . $xml_string;
						}
						
						$property_xml = simplexml_load_string( $xml_string );

						if ($property_xml !== FALSE)
						{
							$property_xml->addChild( 'latitude' );
							$property_xml->latitude = ( isset($property->latitude) ? (string)$property->latitude : '' );
							$property_xml->addChild( 'longitude' );
							$property_xml->longitude = ( isset($property->longitude) ? (string)$property->longitude : '' );
							$property_xml->addChild( 'videotour' );
							$property_xml->videotour = ( isset($property->videotour) ? (string)$property->videotour : '' );
							$property_xml->addChild( 'virtualtour' );
							$property_xml->virtualtour = ( isset($property->virtualtour) ? (string)$property->virtualtour : '' );
							$property_xml->addChild( 'epc' );
							$property_xml->epc = ( isset($property->epc) ? (string)$property->epc : '' );
							$property_xml->addChild( 'short_description' );
							$property_xml->short_description = ( isset($property->description) ? (string)$property->description : '' );
							$property_xml->addChild( 'full_details' );
							$property_xml->full_details = ( isset($property->description) ? (string)$property->description : '' );

							if ( !isset($property_xml->status) || ( isset($property_xml->status) && strtolower((string)$property_xml->status) != 'withdrawn' ) )
							{
			                	$this->properties[] = $property_xml;
			                }
		                }
		                else
				        {
				        	// Failed to parse XML
				        	$this->log_error( 'Failed to parse property (id: ' . (string)$property->id . ', url: ' . $url . ') XML file. Possibly invalid XML' );
				        	return false;
				        }
			        }
			        else
			        {
			        	$this->log_error( 'Failed to obtain property (id: ' . (string)$property->id . ', url: ' . $url . ') XML file. Response: ' . print_r($response, TRUE) );
				        return false;
			        }
			    }
            } // end foreach property
        }
        else
        {
        	// Failed to parse XML
        	$this->log_error( 'Failed to parse properties XML file ' . $import_settings['url'] . '. Possibly invalid XML' );
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
        do_action( "houzez_property_feed_pre_import_properties_gnomen", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_gnomen", $this->properties, $this->import_id );

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

	        $display_address = isset($property->address1) ? trim((string)$property->address1) : '';
			if ( isset($property->address2) && trim((string)$property->address2) != '' )
			{
				if ( trim($display_address) != '' )
				{
					$display_address .= ', ';
				}
				$display_address .= (string)$property->address2;
			}
			elseif ( isset($property->area) && trim((string)$property->area) != '' )
			{
				if ( trim($display_address) != '' )
				{
					$display_address .= ', ';
				}
				$display_address .= (string)$property->area;
			}
			elseif ( isset($property->property_area) && trim((string)$property->property_area) != '' )
			{
				if ( trim($display_address) != '' )
				{
					$display_address .= ', ';
				}
				$display_address .= (string)$property->property_area;
			}
			$display_address = trim($display_address);
	        
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
				    	'post_excerpt'   => (string)$property->short_description,
				    	'post_content' 	 => (string)$property->full_details,
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
					'post_excerpt'   => (string)$property->short_description,
				    'post_content' 	 => (string)$property->full_details,
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

				$department = ( ( isset($property->transaction) && (string)$property->transaction == 'Sale' ) ? 'residential-sales' : 'residential-lettings' );

				$poa = false;

				// no way that we can see to determine a property as POA

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
                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );

                    if ( $department == 'residential-lettings' )
                    {
                    	$rent_frequency = isset($property->frequency) ? strtolower((string)$property->frequency) : 'pcm';
						update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
                    }
                    else
                    {
                    	$price_qualifier = isset($property->price_qualifier) ? (string)$property->price_qualifier : '';
						update_post_meta( $post_id, 'fave_property_price_prefix', $price_qualifier );
                    }
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property->beds) ) ? round((int)$property->beds) : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property->baths) ) ? round((int)$property->baths) : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property->receptions) ) ? round((int)$property->receptions) : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' ); // need to look at parking
	            update_post_meta( $post_id, 'fave_property_size', ( ( isset($property->living_space) && !empty((int)$property->living_space) ) ? round((int)$property->living_space) : '' ) );
	            update_post_meta( $post_id, 'fave_property_size_prefix', 'sq m' );
	            update_post_meta( $post_id, 'fave_property_land', ( ( isset($property->land_size) && !empty((int)$property->land_size) ) ? round((int)$property->land_size) : '' ) );
	            update_post_meta( $post_id, 'fave_property_land_postfix', 'sq m' );
	            update_post_meta( $post_id, 'fave_property_id', ( isset($property->reference) && (string)$property->reference != '' ) ? (string)$property->reference : (string)$property->id );

	            $address_parts = array();
	            if ( isset($property->address1) && (string)$property->address1 != '' )
	            {
	                $address_parts[] = (string)$property->address1;
	            }
	            if ( isset($property->address2) && (string)$property->address2 != '' )
	            {
	                $address_parts[] = (string)$property->address2;
	            }
	            elseif ( isset($property->area) && (string)$property->area != '' )
	            {
	                $address_parts[] = (string)$property->area;
	            }
	            elseif ( isset($property->property_area) && (string)$property->property_area != '' )
	            {
	                $address_parts[] = (string)$property->property_area;
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
	            if ( isset($property->address1) && (string)$property->address1 != '' )
	            {
	                $address_parts[] = (string)$property->address1;
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property->postcode) && (string)$property->postcode != '' ) ? trim( (string)$property->addressPostcode ) : '' ) );

	            update_post_meta( $post_id, 'fave_featured', ( ( isset($property->featured) && strtolower((string)$property->featured) == 'yes' ) ? '1' : '0' ) );
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
	            $features = array();
				if ( isset($property->features->feature) && !empty($property->features->feature) )
				{
					foreach ( $property->features->feature as $feature )
					{
						$features[] = (string)$feature;
					}
				}
				elseif ( isset($property->features) && !empty($property->features) )
				{
					$features = explode( ",", (string)$property->features );
				}
				$features = array_filter($features);

	            $feature_term_ids = array();
	            if ( !empty($features) )
		        {
		            foreach ( $features as $feature )
					{
						if ( trim($feature) != '' )
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
				}

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				if ( isset($property->status) && !empty((string)$property->status) )
				{
					if ( isset($taxonomy_mappings[(string)$property->status]) && !empty($taxonomy_mappings[(string)$property->status]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->status], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . (string)$property->status . ' that isn\'t mapped in the import settings', (string)$property->id, $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, (string)$property->status, $this->import_id );
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
						$this->log( 'Received property type of ' . (string)$property->property_type . ' that isn\'t mapped in the import settings', (string)$property->id, $post_id );

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

				$media_urls = array();
				if (isset($property->images->image) && !empty($property->images->image))
                {
                    foreach ($property->images->image as $image)
                    {
                    	if ( 
							substr( strtolower((string)$image), 0, 2 ) == '//' || 
							substr( strtolower((string)$image), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = (string)$image;

							$media_urls[] = $url;
						}
					}
				}
				if (isset($property->property_images->image) && !empty($property->property_images->image))
                {
                    foreach ($property->property_images->image as $image)
                    {
                    	if ( 
							substr( strtolower((string)$image), 0, 2 ) == '//' || 
							substr( strtolower((string)$image), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = (string)$image;

							$media_urls[] = $url;
						}
					}
				}

                if (!empty($media_urls))
                {
                    foreach ($media_urls as $url)
                    {
						if ( 
							substr( strtolower((string)$image), 0, 2 ) == '//' || 
							substr( strtolower((string)$image), 0, 4 ) == 'http'
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
							$url = apply_filters('houzez_property_feed_gnomen_image_url', $url);
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property->id, $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if (isset($property->floorplans->floorplan) && !empty($property->floorplans->floorplan))
                {
                    foreach ($property->floorplans->floorplan as $floorplan)
                    {
						if ( 
							substr( strtolower((string)$floorplan), 0, 2 ) == '//' || 
							substr( strtolower((string)$floorplan), 0, 4 ) == 'http'
						)
						{
							$floorplans[] = array( 
								"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
								"fave_plan_image" => apply_filters('houzez_property_feed_gnomen_floorplan_url', (string)$floorplan)
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

				if (isset($property->brochure) && !empty($property->brochure))
                {
                    foreach ($property->brochure as $brochure)
                    {
						if ( 
							substr( strtolower((string)$brochure), 0, 2 ) == '//' || 
							substr( strtolower((string)$brochure), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = (string)$brochure;
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

								    	++$new;
								    }
								}
							}
						}
					}
				}

				if (isset($property->property_brochure) && !empty($property->property_brochure))
                {
                    foreach ($property->property_brochure as $brochure)
                    {
						if ( 
							substr( strtolower((string)$brochure), 0, 2 ) == '//' || 
							substr( strtolower((string)$brochure), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = (string)$brochure;
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

								    	++$new;
								    }
								}
							}
						}
					}
				}

				if (isset($property->epc) && !empty($property->epc))
                {
                    foreach ($property->epc as $epc)
                    {
						if ( 
							substr( strtolower((string)$epc), 0, 2 ) == '//' || 
							substr( strtolower((string)$epc), 0, 4 ) == 'http'
						)
						{
							// This is a URL
							$url = (string)$epc;
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
                                        'post_title' => __( 'EPC', 'houzezpropertyfeed' ),
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

				$virtual_tours = array();
				if ( isset($property->videotour) && (string)$property->videotour != '' )
                {
                    $virtual_tours[] = (string)$property->videotour;
                }
                if ( isset($property->virtualtour) && (string)$property->virtualtour != '' )
                {
                    $virtual_tours[] = (string)$property->virtualtour;
                }
                if ( isset($property->vtour) && (string)$property->vtour != '' )
                {
                    $virtual_tours[] = (string)$property->vtour;
                }
                if ( isset($property->virtual_tour) && trim((string)$property->virtual_tour) != '' )
                {
                    $virtual_tours[] = (string)$property->virtual_tour;
                }
                if ( isset($property->external_vtour) && trim((string)$property->external_vtour) != '' )
                {
                    $virtual_tours[] = (string)$property->external_vtour;
                }
                if ( isset($property->external_vtour2) && trim((string)$property->external_vtour2) != '' )
                {
                    $virtual_tours[] = (string)$property->external_vtour2;
                }

				if ( !empty($virtual_tours) )
                {
                    foreach ($virtual_tours as $virtualTour)
                    {
						// This is a URL
						$url = trim($virtualTour);

						if ( !empty($url) )
						{
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
				do_action( "houzez_property_feed_property_imported_gnomen", $post_id, $property, $this->import_id );

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

		do_action( "houzez_property_feed_post_import_properties_gnomen", $this->import_id );

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