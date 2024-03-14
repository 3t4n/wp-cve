<?php
/**
 * Class for managing the import process of an Alto XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Alto extends Houzez_Property_Feed_Process {

	/**
	 * @var array
	 */
	private $database_ids;

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

	// Function to authenticate self to API and return/store the Token
	private function get_token($url, $filename) 
	{
		$import_settings = get_import_settings_from_id( $this->import_id );

		$wp_upload_dir = wp_upload_dir();
		$uploads_dir = $wp_upload_dir['basedir'] . '/houzez_property_feed_import/';

		// Overwriting the response headers from each attempt in this file (for information only)
		$file = $uploads_dir . "headers-" . $this->import_id . ".txt";

		if ( file_exists($file) )
		{
			unlink($file);
		}

		$fh = fopen($file, "w");
		
		// Start curl session
		$ch = curl_init($url);
		// Define Basic HTTP Authentication method
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// Provide Username and Password Details
		curl_setopt($ch, CURLOPT_USERPWD, $import_settings['username'] . ":" . $import_settings['password']);
		// Show headers in returned data but not body as we are only using this curl session to aquire and store the token
		curl_setopt($ch, CURLOPT_HEADER, 1); 
		curl_setopt($ch, CURLOPT_NOBODY, 1); 
		// write the output (returned headers) to file
		curl_setopt($ch, CURLOPT_FILE, $fh);
		// execute curl session
		curl_exec($ch);
		// Log curl request info in case of error
		$token_request_info = curl_getinfo($ch);
		// close curl session
		curl_close($ch); 

		// close headers.txt file
		fclose($fh);

		// read each line of the returned headers back into an array
		$headers = file($uploads_dir . 'headers-' . $this->import_id . '.txt', FILE_SKIP_EMPTY_LINES);
		
		$token = '';

		// For each line of the array explode the line by ':' (Seperating the header name from its value)
		foreach ( $headers as $headerLine )
		{
			$line = explode(':', $headerLine);
			$header = $line[0];
			
			// If the request is successful and we are returned a token
			if ( strtolower($header) == "token" ) 
			{
				$value = trim($line[1]);

				// Save token start and expire time (roughly)
				$tokenStart = time(); 
				$tokenExpire = $tokenStart + (60 * 60);

				$token = base64_encode($value);
				$this->log("Got new token: " . $token);

				// For now write this new token, its start and expiry datetime into a .txt (appending not overwriting - this is for reference in case you lose your session data)
				$file = $uploads_dir . "tokens-" . $this->import_id . ".txt";
				$fh = fopen($file, "a+");
				// Write the line in
				$newLine = "" . $token . "," . date('Y-m-d H:i:s', $tokenStart) . "," . date('Y-m-d H:i:s', $tokenExpire) . "" . "\n";
				fwrite($fh, $newLine);
				// Close file
				fclose($fh);
			}
		}

		// If we have been given a token request XML from the API authenticating using the token
		if ( !empty($token) ) 
		{
			$this->connect($url, $filename);

			unlink($uploads_dir . 'headers-' . $this->import_id . '.txt');
		}
		else
		{
			// If we have not been given a new token its because:
			// a) we already have a live token which has not expired yet (check the tokens.txt file)
			// or
			// b) there was an error.
			// Write this to logs for reference
			//log_error("There is still an active Token, you must wait for this token to expire before a new one can be requested!");
			$this->log("Response when requesting token: " . file_get_contents($uploads_dir . 'headers-' . $this->import_id . '.txt'));

			if ( isset($token_request_info['http_code']) && $token_request_info['http_code'] === 401 )
			{
				$this->log_error("Error encountered when requesting token. The most common causes for this are incorrect credentials or the credentials are already in use on another site. Please confirm credentials or request a new set from Vebra.");
			}
		}
	}

	// Function to connect to the API authenticating ourself with the token we have been given
	private function connect($url, $filename) 
	{
		$token = '';

		if ( isset($_GET['token']) && !empty($_GET['token']) )
		{
			$token = sanitize_text_field($_GET['token']);
		}
		else
		{
			$wp_upload_dir = wp_upload_dir();
			$uploads_dir = $wp_upload_dir['basedir'] . '/houzez_property_feed_import/';

			// get latest token
			$file = $uploads_dir . "tokens-" . $this->import_id . ".txt";
			if ( file_exists($file) )
			{
				$tokenRows = file($file, FILE_SKIP_EMPTY_LINES);
				$numTokens = count($tokenRows);

				$timeNowSecs = time();

				foreach ($tokenRows as $tokenRow) 
				{
					$tokenRow = explode(",", $tokenRow);
					$tokenValue = $tokenRow[0];
					$tokenStart = $tokenRow[1];
					$tokenStartSecs = strtotime($tokenStart);
					$tokenExpiry = $tokenRow[2];
					$tokenExpirySecs = strtotime($tokenExpiry);
					//echo "Checking " . $timeNowSecs . " against start " . $tokenStartSecs . " and end " . $tokenExpirySecs . "\n";
					if ( $timeNowSecs >= $tokenStartSecs && $timeNowSecs <= $tokenExpirySecs )
					{
						// We have a token that is currently valid
						$token = $tokenValue;
					}
				}
			}
		}

		// If token is not set skip to else condition to request a new token 
		if ( !empty($token) ) 
		{
			// Set a new file name and create a new file handle for our returned XML
			$file = $filename;

			if ( file_exists($file) )
			{
				unlink($file);
			}

			$fh = fopen($file, "w");
			
			// Initiate a new curl session
			$ch = curl_init($url);
			// Don't require header this time as curl_getinfo will tell us if we get HTTP 200 or 401
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			// Provide Token in header
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $token));
			// Write returned XML to file
			curl_setopt($ch, CURLOPT_FILE, $fh);
			// Execute the curl session
			curl_exec($ch);
			
			// Store the curl session info/returned headers into the $info array
			$info = curl_getinfo($ch);

			// Check if we have been authorised or not
			if ( $info['http_code'] == '401' ) 
			{
				$this->get_token($url, $filename);
			}
			elseif ( $info['http_code'] == '200' )
			{
				
			}
			else
			{
				$this->log("Got HTTP code: " . $info['http_code'] . " when making request to " . $url);
			}
			
			// Close the curl session
			curl_close($ch);
			// Close the open file handle
			fclose($fh);
			
		}
		else
		{
			// Run the getToken function above if we are not authenticated
			$this->get_token($url, $filename);
		}
	}

	public function parse()
	{
		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$this->log("Parsing properties");

		$import_settings = get_import_settings_from_id( $this->import_id );

		$wp_upload_dir = wp_upload_dir();
		$uploads_dir = $wp_upload_dir['basedir'] . '/houzez_property_feed_import/';

		$request = "http://webservices.vebra.com/export/" . $import_settings['datafeed_id'] . "/v12/branch";

		$branches_file = $uploads_dir . "branches-" . $this->import_id . ".xml";

		$this->connect($request, $branches_file);

		if ( file_exists($branches_file) )
		{
			$branches_xml = @simplexml_load_file($branches_file);

			if ( $branches_xml !== FALSE )
			{
				foreach ( $branches_xml->branch as $branch )
				{
					$branch_xml_url = (string)$branch->url;

					// We have the branch. Now get all properties for this branch
					$request = $branch_xml_url . "/property";

					$properties_file = $uploads_dir . "properties-" . $this->import_id . ".xml";

					$this->connect($request, $properties_file);

					if ( file_exists($properties_file) )
					{
						$properties_xml = @simplexml_load_file($properties_file);

						if ( $properties_xml !== FALSE )
						{
							foreach ( $properties_xml->property as $property )
							{
								$property_xml_url = (string)$property->url;

								$request = $property_xml_url;

								$property_file = $uploads_dir . "property-" . $this->import_id . ".xml";

								$this->connect($request, $property_file);

								if ( file_exists($property_file) )
								{
									$property_xml = @simplexml_load_file($property_file);

									if ( $property_xml !== FALSE )
									{
										$property_xml->addChild('action', 'updated');

										$this->properties[] = $property_xml;
									}
									else
									{
										//echo 'Failed to parse property XML';
									}

									unlink($property_file);
								}
								else
								{
									//echo 'File ' . $property_file . ' doesnt exist';
								}
							}
						}
						else
						{
							//echo 'Failed to parse properties XML';
						}

						unlink($properties_file);
					}
					else
					{
						//echo 'File ' . $properties_file . ' doesnt exist';
					}
				}
			}
			else
			{
				//echo 'Failed to parse branches XML';
			}

			unlink($branches_file);
		}
		else
		{
			//echo 'File ' . $branches_file . ' doesnt exist';
		}

		if ( !empty($this->properties) )
		{
			$this->log("Parsing properties");

            $properties = array();

            $database_id_mappings = array(
                '1' => 'residential-sales',
                '2' => 'residential-lettings',
                '5' => 'commercial',
                '15' => 'residential-sales',
            );
            $this->database_ids = apply_filters( 'houzez_property_feed_alto_include_database_ids', $database_id_mappings, $this->import_id );
            
			foreach ( $this->properties as $property )
			{
            	$property_attributes = $property->attributes();

                // Only import UK residential sales (1), UK residential lettings (2), UK new homes (15)
                if ( 
                	isset($property_attributes['database']) 
                	&&
                	in_array((string)$property_attributes['database'], array_keys($this->database_ids))
                )
                {
                    $properties[] = $property;
                }

            } // end foreach property

            $this->properties = $properties;
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
        do_action( "houzez_property_feed_pre_import_properties_alto", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_alto", $this->properties, $this->import_id );

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
			$property_attributes = $property->attributes();

			$price_attributes = $property->price->attributes();

			if ( !empty($start_at_property) )
			{
				// we need to start on a certain property
				if ( (string)$property_attributes['id'] == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . (string)$property_attributes['id'] );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, (string)$property_attributes['id'], false );

			$this->log( 'Importing property ' . $property_row . ' with reference ' . (string)$property_attributes['id'], (string)$property_attributes['id'] );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => (string)$property_attributes['id']
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        $display_address = (string)$property->address->display;

	        $post_content = (string)$property->description;

	        if ( isset($property->paragraphs) )
			{
				foreach ( $property->paragraphs as $paragraphs )
				{
					if ( isset($paragraphs->paragraph) )
					{
						foreach ( $paragraphs->paragraph as $paragraph )
						{
							$room_content = ( isset($paragraph->name) && !empty((string)$paragraph->name) ) ? '<strong>' . (string)$paragraph->name . '</strong>' : '';
							$room_content .= ( isset($paragraph->dimensions->mixed) && !empty((string)$paragraph->dimensions->mixed) ) ? ' (' . (string)$paragraph->dimensions->mixed . ')' : '';
							if ( isset($paragraph->text) && !empty((string)$paragraph->text) ) 
							{
								if ( !empty($room_content) ) { $room_content .= '<br>'; }
								$room_content .= (string)$paragraph->text;
							}
							
							if ( !empty($room_content) )
							{
								$post_content .= '<p>' . $room_content . '</p>';
							}
						}
					}
				}
			}
	        
	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', (string)$property_attributes['id'] );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( $display_address ),
				    	'post_excerpt'   => (string)$property->description,
				    	'post_content' 	 => $post_content,
				    	'post_status'    => 'publish',
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), (string)$property_attributes['id'] );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', (string)$property_attributes['id'] );

	        	// We've not imported this property before
				$postdata = array(
					'post_excerpt'   => (string)$property->description,
					'post_content' 	 => $post_content,
					'post_title'     => wp_strip_all_tags( $display_address ),
					'post_status'    => 'publish',
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), (string)$property_attributes['id'] );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', (string)$property_attributes['id'], $post_id );

				update_post_meta( $post_id, $imported_ref_key, (string)$property_attributes['id'] );

				update_post_meta( $post_id, '_property_import_data', $property->asXML() );

				$department = 'residential-sales';
				if ( isset($this->database_ids[(string)$property_attributes['database']]) )
				{
					$department = $this->database_ids[(string)$property_attributes['database']];
					if ( $department == 'commercial' )
					{
						if ( (string)$property->commercial->transaction == 'sale' )
						{
							$department = 'residential-sales';
						}
						if ( (string)$property->commercial->transaction == 'rental' )
						{
							$department = 'residential-lettings';
						}
					}
				}

				$poa = false;
				if ( isset($price_attributes['display']) && (string)$price_attributes['display'] == 'no' )
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

                    update_post_meta( $post_id, 'fave_property_price_prefix', ( isset($price_attributes['qualifier']) ? (string)$price_attributes['qualifier'] : '' ) );
                    update_post_meta( $post_id, 'fave_property_price', $price );
                    update_post_meta( $post_id, 'fave_property_price_postfix', '' );

                    if ( $department == 'residential-lettings' )
                    {
                    	$rent_frequency = (string)$price_attributes['rent'];

						update_post_meta( $post_id, 'fave_property_price_postfix', $rent_frequency );
                    }
                }

                update_post_meta( $post_id, 'fave_property_bedrooms', ( ( isset($property->bedrooms) ) ? (string)$property->bedrooms : '' ) );
	            update_post_meta( $post_id, 'fave_property_bathrooms', ( ( isset($property->bathrooms) ) ? (string)$property->bathrooms : '' ) );
	            update_post_meta( $post_id, 'fave_property_rooms', ( ( isset($property->receptions) ) ? (string)$property->receptions : '' ) );
	            update_post_meta( $post_id, 'fave_property_garage', '' ); // need to look at parking
	            update_post_meta( $post_id, 'fave_property_id', (string)$property->reference->agents );

	            $address_parts = array();
	            if ( isset($property->address->street) && (string)$property->address->street != '' )
	            {
	                $address_parts[] = (string)$property->address->street;
	            }
	            if ( isset($property->address->locality) && (string)$property->address->locality != '' )
	            {
	                $address_parts[] = (string)$property->address->locality;
	            }
	            if ( isset($property->address->town) && (string)$property->address->town != '' )
	            {
	                $address_parts[] = (string)$property->address->town;
	            }
	            if ( isset($property->address->county) && (string)$property->address->county != '' )
	            {
	                $address_parts[] = (string)$property->address->county;
	            }
	            if ( isset($property->address->postcode) && (string)$property->address->postcode != '' )
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
	            if ( isset($property->address->street) && (string)$property->address->street != '' )
	            {
	                $address_parts[] = (string)$property->address->street;
	            }
	            update_post_meta( $post_id, 'fave_property_address', implode(", ", $address_parts) );
	            update_post_meta( $post_id, 'fave_property_zip', ( ( isset($property->address->postcode) ) ? (string)$property->address->postcode : '' ) );

	            update_post_meta( $post_id, 'fave_featured', ( ( isset($property_attributes['featured']) && (string)$property_attributes['featured'] == '1' ) ? '1' : '0' ) );
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
		            					$value_in_feed_to_check = isset($property_attributes[$rule['field']]) ? (string)$property_attributes[$rule['field']] : '';
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
		            					$value_in_feed_to_check = isset($property_attributes[$rule['field']]) ? (string)$property_attributes[$rule['field']] : '';
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
		            					$value_in_feed_to_check = isset($property_attributes[$rule['field']]) ? (string)$property_attributes[$rule['field']] : '';
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
	            if ( isset($property->bullets) )
				{
					foreach ( $property->bullets as $bullets )
					{
						if ( isset($bullets->bullet) )
						{
							foreach ( $bullets->bullet as $bullet )
							{
								$feature = (string)$bullet;

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

				if ( isset($property->web_status) && (string)$property->web_status != '' )
				{
					if ( isset($taxonomy_mappings[(string)$property->web_status]) && !empty($taxonomy_mappings[(string)$property->web_status]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$property->web_status], "property_status" );
					}
					else
					{
						$this->log( 'Received status of ' . (string)$property->web_status . ' that isn\'t mapped in the import settings', (string)$property_attributes['id'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, $mapping_name, (string)$property->web_status, $this->import_id );
					}
				}

				// property type taxonomies
				$taxonomy_mappings = ( isset($mappings['property_type']) && is_array($mappings['property_type']) && !empty($mappings['property_type']) ) ? $mappings['property_type'] : array();

				if ( isset($property->type) )
				{
					$type = $property->type;
					if ( is_array($type) )
					{
						$type = $type[0];
					}

					if ( isset($taxonomy_mappings[(string)$type]) && !empty($taxonomy_mappings[(string)$type]) )
					{
						wp_set_object_terms( $post_id, (int)$taxonomy_mappings[(string)$type], "property_type" );
					}
					else
					{
						$this->log( 'Received property type of ' . (string)$type . ' that isn\'t mapped in the import settings', (string)$property_attributes['id'], $post_id );

						$import_settings = $this->add_missing_mapping( $mappings, 'property_type', (string)$type, $this->import_id );
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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', (string)$property_attributes['id'], $post_id );
						}
					}
				}

				if (isset($property->files) && !empty($property->files))
                {
                    foreach ($property->files as $files)
                    {
                        if (!empty($files->file))
                        {
                            foreach ($files->file as $file)
                            {
                            	$file_attributes = $file->attributes();

								if ( 
									(string)$file_attributes['type'] == '0' &&
									(
										substr( strtolower((string)$file->url), 0, 2 ) == '//' || 
										substr( strtolower((string)$file->url), 0, 4 ) == 'http'
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
									$url = (string)$file->url;
									$explode_url = explode("?", $url);
									$url = $explode_url[0];
									$description = (string)$file->name;

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
									        'name' => $filename . '.jpg',
									        'tmp_name' => $tmp
									    );

									    // Check for download errors
									    if ( is_wp_error( $tmp ) ) 
									    {
									        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property_attributes['id'], $post_id );
									    }
									    else
									    {
										    $id = media_handle_sideload( $file_array, $post_id, $description );

										    // Check for handle sideload errors.
										    if ( is_wp_error( $id ) ) 
										    {
										        @unlink( $file_array['tmp_name'] );
										        
										        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property_attributes['id'], $post_id );
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

				$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property_attributes['id'], $post_id );

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				if (isset($property->files) && !empty($property->files))
                {
                    foreach ($property->files as $files)
                    {
                        if (!empty($files->file))
                        {
                            foreach ($files->file as $file)
                            {
                            	$file_attributes = $file->attributes();

								if ( 
									(string)$file_attributes['type'] == '2' &&
									(
										substr( strtolower((string)$file->url), 0, 2 ) == '//' || 
										substr( strtolower((string)$file->url), 0, 4 ) == 'http'
									)
								)
								{
									// This is a URL
									$floorplans[] = array( 
										"fave_plan_title" => __( 'Floorplan', 'houzezpropertyfeed' ), 
										"fave_plan_image" => (string)$file->url
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

				$this->log( 'Imported ' . count($floorplans) . ' floorplans', (string)$property_attributes['id'], $post_id );

				// Brochures and EPCs
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				if (isset($property->files) && !empty($property->files))
                {
                    foreach ($property->files as $files)
                    {
                        if (!empty($files->file))
                        {
                            foreach ($files->file as $file)
                            {
                            	$file_attributes = $file->attributes();

								if ( 
									(string)$file_attributes['type'] == '7' &&
									(
										substr( strtolower((string)$file->url), 0, 2 ) == '//' || 
										substr( strtolower((string)$file->url), 0, 4 ) == 'http'
									)
								)
								{
									// This is a URL
									$url = (string)$file->url;
									$explode_url = explode("?", $url);
									$url = $explode_url[0];
									$description = (string)$file->name;
									
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
									        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property_attributes['id'], $post_id );
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
										        
										        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property_attributes['id'], $post_id );
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
					}
				}

				if (isset($property->files) && !empty($property->files))
                {
                    foreach ($property->files as $files)
                    {
                        if (!empty($files->file))
                        {
                            foreach ($files->file as $file)
                            {
                            	$file_attributes = $file->attributes();

								if ( 
									(string)$file_attributes['type'] == '9' &&
									(
										substr( strtolower((string)$file->url), 0, 2 ) == '//' || 
										substr( strtolower((string)$file->url), 0, 4 ) == 'http'
									)
								)
								{
									// This is a URL
									$url = (string)$file->url;
									$explode_url = explode("?", $url);
									$url = $explode_url[0];
									$description = (string)$file->name;
						    
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
									        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), (string)$property_attributes['id'], $post_id );
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
										        
										        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), (string)$property_attributes['id'], $post_id );
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

				$this->log( 'Imported ' . count($media_ids) . ' brochures and EPCs (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', (string)$property_attributes['id'], $post_id );
				
				update_post_meta( $post_id, 'fave_video_url', '' );
				update_post_meta( $post_id, 'fave_virtual_tour', '' );

				if (isset($property->files) && !empty($property->files))
                {
                    foreach ($property->files as $files)
                    {
                        if (!empty($files->file))
                        {
                            foreach ($files->file as $file)
                            {
                            	$file_attributes = $file->attributes();

								if ( 
									(string)$file_attributes['type'] == '11' &&
									(
										substr( strtolower((string)$file->url), 0, 2 ) == '//' || 
										substr( strtolower((string)$file->url), 0, 4 ) == 'http'
									)
								)
								{
									// This is a URL
									$url = trim((string)$file->url);

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
					}
				}

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_alto", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, (string)$property_attributes['id'], $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_alto", $this->import_id );

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
				$property_attributes = $property->attributes();
				$import_refs[] = (string)$property_attributes['id'];
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}