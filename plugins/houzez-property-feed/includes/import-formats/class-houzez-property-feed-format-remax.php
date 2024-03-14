<?php
/**
 * Class for managing the import process of a RE/MAX JSON file
 *
 * @package WordPress
 */

if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Remax extends Houzez_Property_Feed_Process {

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

	private function get_agents()
	{
		$this->log("Obtaining agents");

		$agent_ids_to_import = array();

		$import_settings = get_import_settings_from_id( $this->import_id );

		$host = 'ahcjbl9nbb.execute-api.eu-west-1.amazonaws.com';
		$url = 'https://' . $host;
		$url .= '/feeds_default/lists';
        $region = 'eu-west-1';

        $event = array( "token" => $import_settings['api_key'], "agents" => true );
        $json = json_encode($event);

        $aws = new AWSV4(
        	$import_settings['access_key'],
        	$import_settings['secret_key']
        );
        $aws->setRegionName( $region );
		$aws->setServiceName( 'execute-api' );
		$aws->setPath( '/feeds_default/lists' );
		$aws->setPayload( $json );
		$aws->setRequestMethod( 'POST' );
		$aws->addHeader( 'x-api-key', $import_settings['api_key'] );
		$aws->addHeader( 'host', $host );

        $headers = $aws->getHeaders();

        $response = wp_remote_request(
			$url,
			array(
				'method' => 'POST',
				'timeout' => 120,
				'headers' => $headers,
				'body' => $json
			)
		);

        if ( !is_wp_error($response) && is_array( $response ) ) 
		{
        	$body = $response['body'];
        	$json = json_decode( $body, TRUE );

		    if ( $json === FALSE )
		    {
		    	// Failed to parse JSON
				$this->log_error( 'Failed to parse JSON body: ' . print_r($body, true) );
				return false;
		    }

		    if ( !isset($json['data']) )
		    {
		    	// Failed to parse JSON
				$this->log_error( 'Data missing from JSON: ' . print_r($json, true) );
				return false;
		    }

		    $data_json = json_decode( $json['data'], TRUE );

		    if ( $data_json === FALSE )
		    {
		    	// Failed to parse JSON
				$this->log_error( 'Failed to parse JSON data: ' . print_r($json['data'], true) );
				return false;
		    }

		    if ( !isset($data_json['agent']) )
		    {
		    	// Failed to parse JSON
				$this->log_error( 'Agent data missing from JSON: ' . print_r($data_json, true) );
				return false;
		    }

		    if ( !is_array($data_json['agent']) || (is_array($data_json['agent']) && empty($data_json['agent'])) )
		    {
		    	// Failed to parse JSON
				$this->log_error( 'Agent data empty or not an array: ' . print_r($data_json['agent'], true) );
				return false;
		    }

		    foreach ( $data_json['agent'] as $agent )
		    {
		    	$agent_ids_to_import[] = $agent['agent_id'];
		    }
		}
		else
		{
			$this->log_error( 'Failed to obtain agents JSON. Dump of response as follows:' . print_r($response, TRUE) );
			return false;
		}

		return $agent_ids_to_import;
	}

	private function get_agents_from_office_id( $office_ids = array() )
	{
		$this->log("Obtaining agents for office IDs");

		$agent_ids_to_import = array();

		if ( !empty($office_ids) )
        {
        	$import_settings = get_import_settings_from_id( $this->import_id );

	        foreach ( $office_ids as $office_id )
	        {
		        $this->log("Obtaining agents for office ID " . $office_id);

		        $host = 'ahcjbl9nbb.execute-api.eu-west-1.amazonaws.com';
				$url = 'https://' . $host;
				$url .= '/feeds_default/office';
		        $region = 'eu-west-1';

		        $event = array( "token" => $import_settings['api_key'], "office_id" => $office_id );
		        $json = json_encode($event);

		        $aws = new AWSV4(
		        	$import_settings['access_key'],
		        	$import_settings['secret_key']
		        );
		        $aws->setRegionName( $region );
				$aws->setServiceName( 'execute-api' );
				$aws->setPath( '/feeds_default/office' );
				$aws->setPayload( $json );
				$aws->setRequestMethod( 'POST' );
				$aws->addHeader( 'x-api-key', $import_settings['api_key'] );
				$aws->addHeader( 'host', $host );

		        $headers = $aws->getHeaders();

		        $response = wp_remote_request(
					$url,
					array(
						'method' => 'POST',
						'timeout' => 120,
						'headers' => $headers,
						'body' => $json
					)
				);

		        if ( !is_wp_error($response) && is_array( $response ) ) 
				{
		        	$body = $response['body'];
		        	$json = json_decode( $body, TRUE );

				    if ( $json === FALSE )
				    {
				    	// Failed to parse JSON
						$this->log_error( 'Failed to parse JSON body: ' . print_r($body, true) );
						return array();
				    }

				    if ( !isset($json['data']) )
				    {
				    	// Failed to parse JSON
						$this->log_error( 'Data missing from JSON: ' . print_r($json, true) );
						return array();
				    }

				    $data_json = json_decode( $json['data'], TRUE );

				    if (!empty($data_json['agents']['agent']))
				    {
				    	$this->log( 'Found ' . count($data_json['agents']['agent']) . ' agents belonging to office ID ' . $office_id );
				    	foreach ( $data_json['agents']['agent'] as $agent )
				    	{
				    		$agent_ids_to_import[] = $agent['agent_id'];
				    	}
				    }
				}
				else
				{
					$this->log_error( 'Failed to obtain offices JSON. Dump of response as follows:' . print_r($response, TRUE) );
					return array();
				}
			}
		}

		$agent_ids_to_import = array_unique($agent_ids_to_import);
		$agent_ids_to_import = array_filter($agent_ids_to_import);

		return $agent_ids_to_import;
	}

	public function parse()
	{
		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$this->log("Parsing properties");

		$import_settings = get_import_settings_from_id( $this->import_id );

		$agent_ids_to_import = array();
		$office_ids_to_import = array();

        if ( 
        	(
	        	!isset($import_settings['agent_id']) || 
	        	( isset($import_settings['agent_id']) && empty($import_settings['agent_id']) ) 
	        )
	        &&
	    	(
	        	!isset($import_settings['office_id']) || 
	        	( isset($import_settings['office_id']) && empty($import_settings['office_id']) ) 
	        )
        )
        {
        	// no agents or offices specified, get all agents
        	$agent_ids_to_import = $this->get_agents();

        	if ( $agent_ids_to_import === false )
        	{
        		return false;
        	}
        }
        else
        {
        	if ( isset($import_settings['agent_id']) && !empty($import_settings['agent_id']) )
        	{
	        	$agent_ids_to_import = explode(",", $import_settings['agent_id']);
	        	$agent_ids_to_import = array_map('trim', $agent_ids_to_import);
	        	$agent_ids_to_import = array_filter($agent_ids_to_import);
	        	$agent_ids_to_import = array_unique($agent_ids_to_import);
	        }

	        if ( isset($import_settings['office_id']) && !empty($import_settings['office_id']) )
        	{
        		$office_ids_to_import = explode(",", $import_settings['office_id']);
	        	$office_ids_to_import = array_map('trim', $office_ids_to_import);
	        	$office_ids_to_import = array_filter($office_ids_to_import);
	        	$office_ids_to_import = array_unique($office_ids_to_import);
        	}
        }

        if ( empty($agent_ids_to_import) && empty($office_ids_to_import) )
        {
        	$this->log_error( 'No agents or offices to process' );
        	return false;
        }

        if ( empty($agent_ids_to_import) && !empty($office_ids_to_import) )
        {
        	$agent_ids_to_import = $this->get_agents_from_office_id($office_ids_to_import);
        }

        if ( !empty($agent_ids_to_import) )
        {
	        foreach ( $agent_ids_to_import as $agent_id )
	        {
		        $this->log("Obtaining properties for agent ID " . $agent_id);

		        $host = 'ahcjbl9nbb.execute-api.eu-west-1.amazonaws.com';
				$url = 'https://' . $host;
				$url .= '/feeds_default/agents-page';
		        $region = 'eu-west-1';

		        $more_properties = true;
		        $page = 0;

		        while ( $more_properties )
		        {
		        	$this->log("Page " . ( $page + 1 ));

			        $event = array( "token" => $import_settings['api_key'], "agent_id" => $agent_id, "page" => $page );
			        $json = json_encode($event);

			        $aws = new AWSV4(
			        	$import_settings['access_key'],
			        	$import_settings['secret_key']
			        );
			        $aws->setRegionName( $region );
					$aws->setServiceName( 'execute-api' );
					$aws->setPath( '/feeds_default/agents-page' );
					$aws->setPayload( $json );
					$aws->setRequestMethod( 'POST' );
					$aws->addHeader( 'x-api-key', $import_settings['api_key'] );
					$aws->addHeader( 'host', $host );

			        $headers = $aws->getHeaders();

			        $response = wp_remote_request(
						$url,
						array(
							'method' => 'POST',
							'timeout' => 120,
							'headers' => $headers,
							'body' => $json
						)
					);

			        if ( !is_wp_error($response) && is_array( $response ) ) 
					{
			        	$body = $response['body'];
			        	$json = json_decode( $body, TRUE );

					    if ( $json === FALSE )
					    {
					    	// Failed to parse JSON
							$this->log_error( 'Failed to parse JSON body: ' . print_r($body, true) );
							return false;
					    }

					    if ( !isset($json['data']) )
					    {
					    	// Failed to parse JSON
							$this->log_error( 'Data missing from JSON: ' . print_r($json, true) );
							return false;
					    }

					    $data_json = json_decode( $json['data'], TRUE );

					    if ( $data_json === FALSE )
					    {
					    	// Failed to parse JSON
							$this->log_error( 'Failed to parse JSON data: ' . print_r($json['data'], true) );
							return false;
					    }

					    if ( !isset($data_json['properties']) )
					    {
							$this->log_error( 'Properties data missing from JSON: ' . print_r($data_json, true) );
							//return false;
					    }
					    else
					    {
						    if ( !isset($data_json['properties']['property']) )
						    {
								$this->log_error( 'Property data missing from JSON: ' . print_r($data_json, true) );
								//return false;
						    }
						}

						if ( !isset($data_json['properties']['hasNextPage']) )
						{
							$this->log_error( 'Pagination hasNextPage element missing: ' . print_r($data_json, true) );
							return false;
						}

					    if ( isset($data_json['properties']['property']) && !empty($data_json['properties']['property']) )
					    {
						    foreach ( $data_json['properties']['property'] as $property )
						    {
						    	$property['agent_details'] = $data_json['agent_details'];
						    	$this->properties[] = $property;
						    }
						}

						if ( $data_json['properties']['hasNextPage'] == false )
						{
							$more_properties = false;
						}
					}
					else
					{
						$this->log_error( 'Failed to obtain properties JSON. Dump of response as follows:' . print_r($response, TRUE) );
						return false;
					}

					++$page;
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
        do_action( "houzez_property_feed_pre_import_properties_remax", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_remax", $this->properties, $this->import_id );

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
				if ( $property['property_id'] == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . $property['property_id'] );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, $property['property_id'], false );
			
			$this->log( 'Importing property ' . $property_row . ' with reference ' . $property['property_id'], $property['property_id'] );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => $property['property_id']
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = $property['heading']['_cdata'];

			$post_content = $property['description']['_cdata'];
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', $property['property_id'] );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => '',
				    	'post_content' 	 => $post_content,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), $property['property_id'] );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', $property['property_id'] );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => '',
					'post_content' 	 => $post_content,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), $property['property_id'] );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', $property['property_id'], $post_id );

				update_post_meta( $post_id, $imported_ref_key, $property['property_id'] );

				update_post_meta( $post_id, '_property_import_data', json_encode($property, JSON_PRETTY_PRINT) );

				$department = ( $property['listing_type'] == 'For Sale' ? 'residential-sales' : 'residential-lettings' );

				$poa = false;
				if ( isset($property['price']['poa']) && $property['price']['poa'] === true )
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
                	$price = '';
            		if ( isset($property['price']['amount']) && !empty($property['price']['amount']) )
            		{
                		$price = round(preg_replace("/[^0-9.]/", '', $property['price']['amount']));
                	}

                	if ( $department == 'residential-sales' )
                	{
	                    update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );
	                }
	                elseif ( $department == 'residential-lettings' )
	                {
	                	update_post_meta( $post_id, 'fave_property_price_prefix', '' );
	                    update_post_meta( $post_id, 'fave_property_price', $price );
	                    update_post_meta( $post_id, 'fave_property_price_postfix', $property['price']['periodicity'] );
	                }
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property['features']['bedrooms']) ) ? $property['features']['bedrooms'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property['features']['bathrooms']) ) ? $property['features']['bathrooms'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property['features']['lounges']) ) ? $property['features']['lounges'] : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' );
	            update_post_meta( $post_id, 'fave_property_id', $property['reference'] );

	            $address_number = '';
				$address_street = '';
				/*$address1_explode = explode(' ', $address_street);
				if ( !empty($address1_explode) && is_numeric($address1_explode[0]) )
				{
					$address_number = array_shift($address1_explode);
					$address_street = implode(' ', $address1_explode);
				}*/

	            $address_parts = array();
	            if ( isset($property['location']['suburb']['_cdata']) && $property['location']['suburb']['_cdata'] != '' )
	            {
	                $address_parts[] = $property['location']['suburb']['_cdata'];
	            }
	            if ( isset($property['location']['city']['_cdata']) && $property['location']['city']['_cdata'] != '' )
	            {
	                $address_parts[] = $property['location']['city']['_cdata'];
	            }
	            if ( isset($property['location']['province']['_cdata']) && $property['location']['province']['_cdata'] != '' )
	            {
	                $address_parts[] = $property['location']['province']['_cdata'];
	            }

	            update_post_meta( $post_id, 'fave_property_map', '1' );
	            update_post_meta( $post_id, 'fave_property_map_address', implode(", ", $address_parts) );
	            $lat = '';
	            $lng = '';
	            update_post_meta( $post_id, 'fave_property_location', $lat . "," . $lng . ",14" );
	            update_post_meta( $post_id, 'fave_property_country', $property['location']['country'] );
	            
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', '' );

	            $featured = '0';
	            add_post_meta( $post_id, 'fave_featured', $featured, true );
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
		            				case "agent_id":
		            				{
		            					$value_in_feed_to_check = $property['agent_details']['agent_id'];
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
		            				case "agent_id":
		            				{
		            					$value_in_feed_to_check = $property['agent_details']['agent_id'];
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
		            				case "agent_id":
		            				{
		            					$value_in_feed_to_check = $property['agent_details']['agent_id'];
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
	            /*$feature_term_ids = array();
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
				update_post_meta( $post_id, 'fave_epc_potential_rating', ( ( isset($property['epc']['energy_efficiency_potential']) ) ? $property['epc']['energy_efficiency_potential'] : '' ) );*/

				$mappings = ( isset($import_settings['mappings']) && is_array($import_settings['mappings']) && !empty($import_settings['mappings']) ) ? $import_settings['mappings'] : array();

				// status taxonomies
				$mapping_name = 'lettings_status';
				if ( $department == 'residential-sales' )
				{
					$mapping_name = 'sales_status';
				}

				$taxonomy_mappings = ( isset($mappings[$mapping_name]) && is_array($mappings[$mapping_name]) && !empty($mappings[$mapping_name]) ) ? $mappings[$mapping_name] : array();

				if ( isset($property['listing_state']) && !empty($property['listing_state']) )
				{
					if ( isset($taxonomy_mappings[$property['listing_state']]) && !empty($taxonomy_mappings[$property['listing_state']]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['listing_state']], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . $property['listing_state'] . ' that isn\'t mapped in the import settings', $property['property_id'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, $property['listing_state'], $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property['property_type']) && !empty($property['property_type']) )
				{
					if ( isset($taxonomy_mappings[$property['property_type']]) && !empty($taxonomy_mappings[$property['property_type']]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[$property['property_type']], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . $property['property_type'] . ' that isn\'t mapped in the import settings', $property['property_id'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', $property['property_type'], $this->import_id );
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
						if ( isset($property['location'][$address_field_to_use]['_cdata']) && !empty($property['location'][$address_field_to_use]['_cdata']) )
		            	{
		            		$term = term_exists( trim($property['location'][$address_field_to_use]['_cdata']), $location_taxonomy);
							if ( $term !== 0 && $term !== null && isset($term['term_id']) )
							{
								$location_term_ids[] = (int)$term['term_id'];
							}
							else
							{
								if ( $create_location_taxonomy_terms === true )
								{
									$term = wp_insert_term( trim($property['location'][$address_field_to_use]['_cdata']), $location_taxonomy );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', $property['property_id'], $post_id );
						}
					}
				}

				if ( isset($property['photos']['photo']) && !empty($property['photos']['photo']) )
				{
					usort($property['photos']['photo'], function($a, $b) {
					    return $a['order'] - $b['order'];
					});

					foreach ( $property['photos']['photo'] as $image )
					{
						$url = $image['url'];

						if ( 
							(isset($image['active']) && ($image['active'] === true || $image['active'] === "true")) &&
							(
								substr( strtolower($url), 0, 2 ) == '//' || 
								substr( strtolower($url), 0, 4 ) == 'http'
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
							$modified = ( (isset($image['date_last_updated'])) ? $image['date_last_updated'] : '' );
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
								if ( apply_filters( 'houzez_property_feed_import_media', true, $this->import_id, $post_id, $property['property_id'], $url, $url, $description, 'image', $image_i, $modified ) === true )
								{
									$tmp = download_url( $url );

								    $file_array = array(
								        'name' => $filename,
								        'tmp_name' => $tmp
								    );

								    // Check for download errors
								    if ( is_wp_error( $tmp ) ) 
								    {
								        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['property_id'], $post_id );
								    }
								    else
								    {
									    $id = media_handle_sideload( $file_array, $post_id, $description );

									    // Check for handle sideload errors.
									    if ( is_wp_error( $id ) ) 
									    {
									        @unlink( $file_array['tmp_name'] );
									        
									        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['property_id'], $post_id );
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['property_id'], $post_id );
				if ( $queued > 0 ) 
				{
					$this->log( $queued . ' photos added to download queue', $property['property_id'], $post_id );
				}

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				/*$floorplans = array();

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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', $property['property_id'], $post_id );

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
						        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['property_id'], $post_id );
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
							        
							        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['property_id'], $post_id );
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
							        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property['property_id'], $post_id );
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
								        
								        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property['property_id'], $post_id );
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

				$this->log( 'Imported ' . count($media_ids) . ' brochures (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property['property_id'], $post_id );
				
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
				}*/

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_remax", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, $property['property_id'], $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_remax", $this->import_id );

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
				$import_refs[] = $property['property_id'];
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}