<?php
/**
 * Class for managing the import process of an CSV file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Csv extends Houzez_Property_Feed_Process {

	public function __construct( $instance_id = '', $import_id = '' )
	{
		$this->instance_id = $instance_id;
		$this->import_id = $import_id;

		if ( $this->instance_id != '' && isset($_GET['custom_property_import_cron']) )
	    {
	    	$current_user = wp_get_current_user();

	    	$this->log("Executed manually by " . ( ( isset($current_user->display_name) ) ? $current_user->display_name : '' ) );
	    }

	    if ( !defined('ALLOW_UNFILTERED_UPLOADS') ) { define( 'ALLOW_UNFILTERED_UPLOADS', true ); }
	}

	public function parse()
	{
		$this->properties = array(); // Reset properties in the event we're importing multiple files

		$this->log("Parsing properties");

		$import_settings = get_import_settings_from_id( $this->import_id );

		if ( !isset($import_settings['property_id_field']) || ( isset($import_settings['property_id_field']) && empty($import_settings['property_id_field']) ) )
		{
			$this->log_error( 'Please ensure you have a field specified that we can use as the unique property identifer in the import setting under the \'Import Format\' tab and that is has a value set in the CSV' );
			return false;
		}

		$contents = '';

		$args = array( 'timeout' => 120, 'sslverify' => false );
        $args = apply_filters( 'houzez_property_feed_csv_request_args', $args, $import_settings['csv_url'] );
		$response = wp_remote_get( $import_settings['csv_url'], $args );
		if ( !is_wp_error($response) && is_array( $response ) ) 
		{
			$contents = $response['body'];
		}
		else
		{
			$this->log_error( "Failed to obtain CSV. Dump of response as follows: " . print_r($response, TRUE) );

        	return false;
		}

		$temp = tmpfile();
		fwrite($temp, $contents);
		fseek($temp, 0);

		$fields = array(); 
		$i = 0;

		while ( ($row = fgetcsv($temp, 10000, ( isset($import_settings['csv_delimiter']) ? $import_settings['csv_delimiter'] : ',' ))) !== false ) 
		{
	        if ( empty($fields) ) 
	        {
	            $fields = $row;
	            continue;
	        }
	        foreach ( $row as $k => $value ) 
	        {
	        	if ( !isset($this->properties[$i]) ) { $this->properties[$i] = array(); }
	            $this->properties[$i][$fields[$k]] = $value;
	        }
	        ++$i;
	    }
	    fclose($temp);

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
        do_action( "houzez_property_feed_pre_import_properties_csv", $this->properties, $this->import_id );

        $this->properties = apply_filters( "houzez_property_feed_properties_due_import", $this->properties, $this->import_id );
        $this->properties = apply_filters( "houzez_property_feed_properties_due_import_csv", $this->properties, $this->import_id );

        $limit = apply_filters( "houzez_property_feed_property_limit", 25 );
        $additional_message = '';
        if ( $limit !== false )
        {
        	$this->properties = array_slice( $this->properties, 0, $limit );
        	$additional_message = '. <a href="https://houzezpropertyfeed.com/#pricing" target="_blank">Upgrade to PRO</a> to import unlimited properties';
        }

		$this->log( 'Beginning to loop through ' . count($this->properties) . ' properties' . $additional_message );

		$property_id_field = $import_settings['property_id_field'];

		$start_at_property = get_option( 'houzez_property_feed_property_' . $this->import_id );

		$property_row = 1;
		foreach ( $this->properties as $property )
		{
			$property_id = '';

            if ( isset($property[$property_id_field]) && !empty($property[$property_id_field]) )
            {
                $property_id = $property[$property_id_field];
            }

			if ( empty($property_id) )
			{
				$this->log_error( 'Unique ID empty. Please ensure you have a field specified that we can use as the unique identifer in the import setting under the \'Import Format\' tab and that is has a value set in the CSV' );
				continue;
			}

			if ( !empty($start_at_property) )
			{
				// we need to start on a certain property
				if ( $property_id == $start_at_property )
				{
					// we found the property. We'll continue for this property onwards
					$this->log( 'Previous import failed to complete. Continuing from property ' . $property_row . ' with ID ' . $property_id );
					$start_at_property = false;
				}
				else
				{
					++$property_row;
					continue;
				}
			}

			update_option( 'houzez_property_feed_property_' . $this->import_id, $property_id, false );

			$this->log( 'Importing property ' . $property_row . ' with reference ' . $property_id, $property_id );

			$inserted_updated = false;

			$args = array(
	            'post_type' => 'property',
	            'posts_per_page' => 1,
	            'post_status' => 'any',
	            'meta_query' => array(
	            	array(
		            	'key' => $imported_ref_key,
		            	'value' => $property_id
		            )
	            )
	        );
	        $property_query = new WP_Query($args);

	        if ($property_query->have_posts())
	        {
	        	$this->log( 'This property has been imported before. Updating it', $property_id );

	        	// We've imported this property before
	            while ($property_query->have_posts())
	            {
	                $property_query->the_post();

	                $post_id = get_the_ID();

	                $my_post = array(
				    	'ID'          	 => $post_id,
				    	'post_title'     => wp_strip_all_tags( apply_filters( 'houzez_property_feed_csv_mapped_field_value', '', $property, 'post_title', $this->import_id ) ),
				    	'post_excerpt'   => apply_filters( 'houzez_property_feed_csv_mapped_field_value', '', $property, 'post_excerpt', $this->import_id ),
				    	'post_content' 	 => apply_filters( 'houzez_property_feed_csv_mapped_field_value', '', $property, 'post_content', $this->import_id ),
				    	'post_status'    => apply_filters( 'houzez_property_feed_csv_mapped_field_value', 'publish', $property, 'post_status', $this->import_id ),
				  	);

				 	// Update the post into the database
				    $post_id = wp_update_post( $my_post, true );

				    if ( is_wp_error( $post_id ) ) 
					{
						$this->log_error( 'Failed to update post. The error was as follows: ' . $post_id->get_error_message(), $property_id );
					}
					else
					{
						$inserted_updated = 'updated';
					}
	            }
	        }
	        else
	        {
	        	$this->log( 'This property hasn\'t been imported before. Inserting it', $property_id );

	        	// We've not imported this property before
				$postdata = array(
					'post_title'     => wp_strip_all_tags( apply_filters( 'houzez_property_feed_csv_mapped_field_value', '', $property, 'post_title', $this->import_id ) ),
				    'post_excerpt'   => apply_filters( 'houzez_property_feed_csv_mapped_field_value', '', $property, 'post_excerpt', $this->import_id ),
				    'post_content' 	 => apply_filters( 'houzez_property_feed_csv_mapped_field_value', '', $property, 'post_content', $this->import_id ),
					'post_status'    => apply_filters( 'houzez_property_feed_csv_mapped_field_value', 'publish', $property, 'post_status', $this->import_id ),
					'post_type'      => 'property',
					'comment_status' => 'closed',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) 
				{
					$this->log_error( 'Failed to insert post. The error was as follows: ' . $post_id->get_error_message(), $property_id );
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

				$this->log( 'Successfully ' . $inserted_updated . ' post', $property_id, $post_id );

				update_post_meta( $post_id, $imported_ref_key, $property_id );

				update_post_meta( $post_id, '_property_import_data', print_r($property, true) );

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

							$this->log( 'Imported ' . count($media_ids) . ' images before failing in the previous import. Continuing from here', $property_id, $post_id );
						}
					}
				}

				$image_mapping_specified = false;
				if ( !isset($import_settings['image_field_arrangement']) || ( isset($import_settings['image_field_arrangement']) && $import_settings['image_field_arrangement'] == '' ) )
				{
					if ( isset($import_settings['image_fields']) && !empty($import_settings['image_fields']) )
					{
						$image_mapping_specified = true;

						$explode_media = explode("\n", $import_settings['image_fields']);

						foreach ( $explode_media as $media_item )
						{
							$explode_media_item = explode("|", $media_item); // 0 => URL, 1 => Description

							$url = trim($explode_media_item[0]);
							$url = apply_filters( 'houzez_property_feed_csv_image_url', $url, $this->import_id );
							$description = isset($explode_media_item[1]) ? trim($explode_media_item[1]) : '';

							preg_match_all('/{[^}]*}/', $url, $matches);
			                if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
			                {
			                    foreach ( $matches[0] as $match )
			                    {
			                    	// foreach field in xpath
			                        $field_name = str_replace(array("{", "}"), "", $match);

			                        $value_to_check = check_array_for_matching_key( $property, $field_name );

		                            if ( $value_to_check === false )
		                            {
		                                $value_to_check = '';
		                            }

			                        $url = str_replace($match, $value_to_check, $url);
			                    }
			                }

							if ( !empty($description) )
							{
								preg_match_all('/{[^}]*}/', $description, $matches);
				                if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
				                {
				                    foreach ( $matches[0] as $match )
				                    {
				                    	// foreach field in xpath
				                        $field_name = str_replace(array("{", "}"), "", $match);

				                        $value_to_check = check_array_for_matching_key( $property, $field_name );

			                            if ( $value_to_check === false )
			                            {
			                                $value_to_check = '';
			                            }

				                        $description = str_replace($match, $value_to_check, $description);
				                    }
				                }
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

								$filename = basename( $url );

								// Check, based on the URL, whether we have previously imported this media
								$imported_previously = false;
								$imported_previously_id = '';

								if ( isset($import_settings['media_download_clause']) && $import_settings['media_download_clause'] == 'url_change' )
								{
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
								        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property_id, $post_id );
								    }
								    else
								    {
									    $id = media_handle_sideload( $file_array, $post_id, $description );

									    // Check for handle sideload errors.
									    if ( is_wp_error( $id ) ) 
									    {
									        @unlink( $file_array['tmp_name'] );
									        
									        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property_id, $post_id );
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
				elseif ( isset($import_settings['image_field_arrangement']) && $import_settings['image_field_arrangement'] == 'comma_delimited' )
				{
					if ( isset($import_settings['image_field']) && !empty($import_settings['image_field']) )
					{
						$image_mapping_specified = true;

						$value_to_check = check_array_for_matching_key( $property, $import_settings['image_field'] );

                        if ( $value_to_check !== false )
                        {
                            // we found this field
                        	$delimiter = ( isset($import_settings['image_field_delimiter']) && !empty($import_settings['image_field_delimiter']) ) ? $import_settings['image_field_delimiter'] : ',';

                            $explode_image_urls = explode($delimiter, $value_to_check);
                            $explode_image_urls = array_map('trim', $explode_image_urls);
                            $explode_image_urls = array_filter($explode_image_urls);

                            if ( !empty($explode_image_urls) )
                            {
                            	// we found image URLs
                            	foreach ( $explode_image_urls as $url )
                            	{
                            		$url = apply_filters( 'houzez_property_feed_csv_image_url', $url, $this->import_id );

                            		$description = '';

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

										$filename = basename( $url );

										$extension = pathinfo($filename, PATHINFO_EXTENSION);

									    // List of allowed image file extensions
									    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp');

									    // Check if the extension is empty or not in the allowed extensions list
									    if (empty($extension) || !in_array(strtolower($extension), $allowed_extensions)) {
									        // Append '.jpg' if the extension is missing or not valid
									        $filename .= '.jpg';
									    }

										// Check, based on the URL, whether we have previously imported this media
										$imported_previously = false;
										$imported_previously_id = '';

										if ( isset($import_settings['media_download_clause']) && $import_settings['media_download_clause'] == 'url_change' )
										{
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
										        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property_id, $post_id );
										    }
										    else
										    {
											    $id = media_handle_sideload( $file_array, $post_id, $description );

											    // Check for handle sideload errors.
											    if ( is_wp_error( $id ) ) 
											    {
											        @unlink( $file_array['tmp_name'] );
											        
											        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property_id, $post_id );
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
				}

				if ( $image_mapping_specified )
				{
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

					$this->log( 'Imported ' . count($media_ids) . ' photos (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property_id, $post_id );
				}
				else
				{
					$this->log( 'Not importing images due to no image fields specified in \'Media\' section of import settings', $property_id, $post_id );
				}

				update_option( 'houzez_property_feed_property_image_media_ids_' . $this->import_id, '', false );

				// Floorplans
				$floorplans = array();

				$floorplan_mapping_specified = false;
				if ( !isset($import_settings['floorplan_field_arrangement']) || ( isset($import_settings['floorplan_field_arrangement']) && $import_settings['floorplan_field_arrangement'] == '' ) )
				{
					if ( isset($import_settings['floorplan_fields']) && !empty($import_settings['floorplan_fields']) )
					{
						$floorplan_mapping_specified = true;

						$explode_media = explode("\n", $import_settings['floorplan_fields']);

						foreach ( $explode_media as $media_item )
						{
							$explode_media_item = explode("|", $media_item); // 0 => URL, 1 => Description

							$url = trim($explode_media_item[0]);
							$url = apply_filters( 'houzez_property_feed_csv_floorplan_url', $url, $this->import_id );
							$description = isset($explode_media_item[1]) ? trim($explode_media_item[1]) : '';

							preg_match_all('/{[^}]*}/', $url, $matches);
			                if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
			                {
			                    foreach ( $matches[0] as $match )
			                    {
			                    	// foreach field in xpath
			                        $field_name = str_replace(array("{", "}"), "", $match);

			                        $value_to_check = check_array_for_matching_key( $property, $field_name );

		                            if ( $value_to_check === false )
		                            {
		                                $value_to_check = '';
		                            }

			                        $url = str_replace($match, $value_to_check, $url);
			                    }
			                }

							if ( !empty($description) )
							{
								preg_match_all('/{[^}]*}/', $description, $matches);
				                if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
				                {
				                    foreach ( $matches[0] as $match )
				                    {
				                    	// foreach field in xpath
				                        $field_name = str_replace(array("{", "}"), "", $match);

				                        $value_to_check = check_array_for_matching_key( $property, $field_name );

			                            if ( $value_to_check === false )
			                            {
			                                $value_to_check = '';
			                            }

				                        $description = str_replace($match, $value_to_check, $description);
				                    }
				                }
							}

			            	if ( 
								substr( strtolower($url), 0, 2 ) == '//' || 
								substr( strtolower($url), 0, 4 ) == 'http'
							)
							{
								$floorplans[] = array( 
									"fave_plan_title" => ( !empty($description) ? $description : __( 'Floorplan', 'houzezpropertyfeed' ) ), 
									"fave_plan_image" => $url,
								);
							}					
						}
					}
				}
				elseif ( isset($import_settings['floorplan_field_arrangement']) && $import_settings['floorplan_field_arrangement'] == 'comma_delimited' )
				{
					if ( isset($import_settings['floorplan_field']) && !empty($import_settings['floorplan_field']) )
					{
						$floorplan_mapping_specified = true;

						$value_to_check = check_array_for_matching_key( $property, $import_settings['floorplan_field'] );

                        if ( $value_to_check !== false )
                        {
                            // we found this field
                            $delimiter = ( isset($import_settings['floorplan_field_delimiter']) && !empty($import_settings['floorplan_field_delimiter']) ) ? $import_settings['floorplan_field_delimiter'] : ',';

                            $explode_floorplan_urls = explode($delimiter, $value_to_check);
                            $explode_floorplan_urls = array_map('trim', $explode_floorplan_urls);
                            $explode_floorplan_urls = array_filter($explode_floorplan_urls);

                            if ( !empty($explode_floorplan_urls) )
                            {
                            	// we found floorplan URLs
                            	foreach ( $explode_floorplan_urls as $url )
                            	{
                            		$url = apply_filters( 'houzez_property_feed_csv_floorplan_url', $url, $this->import_id );

                            		if ( 
										substr( strtolower($url), 0, 2 ) == '//' || 
										substr( strtolower($url), 0, 4 ) == 'http'
									)
									{
										$floorplans[] = array( 
											"fave_plan_title" => ( !empty($description) ? $description : __( 'Floorplan', 'houzezpropertyfeed' ) ), 
											"fave_plan_image" => $url,
										);
									}
                            	}
                            }
                        }
                    }
				}

				if ( $floorplan_mapping_specified )
				{
					if ( !empty($floorplans) )
					{
		                update_post_meta( $post_id, 'floor_plans', $floorplans );
		                update_post_meta( $post_id, 'fave_floor_plans_enable', 'enable' );
		            }
		            else
		            {
		            	update_post_meta( $post_id, 'fave_floor_plans_enable', 'disable' );
		            }

					$this->log( 'Imported ' . count($floorplans) . ' floorplans', $property_id, $post_id );
				}
				else
				{
					$this->log( 'Not importing floorplans due to no floorplan fields specified in \'Media\' section of import settings', $property_id, $post_id );
				}

				// Documents
				$media_ids = array();
				$new = 0;
				$existing = 0;
				$deleted = 0;
				$previous_media_ids = get_post_meta( $post_id, 'fave_attachments' );

				$document_mapping_specified = false;
				if ( !isset($import_settings['document_field_arrangement']) || ( isset($import_settings['document_field_arrangement']) && $import_settings['document_field_arrangement'] == '' ) )
				{
					if ( isset($import_settings['document_fields']) && !empty($import_settings['document_fields']) )
					{
						$document_mapping_specified = true;

						$explode_media = explode("\n", $import_settings['document_fields']);

						foreach ( $explode_media as $media_item )
						{
							$explode_media_item = explode("|", $media_item); // 0 => URL, 1 => Description

							$url = trim($explode_media_item[0]);
							$url = apply_filters( 'houzez_property_feed_csv_document_url', $url, $this->import_id );
							$description = isset($explode_media_item[1]) ? trim($explode_media_item[1]) : '';

							preg_match_all('/{[^}]*}/', $url, $matches);
			                if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
			                {
			                    foreach ( $matches[0] as $match )
			                    {
			                    	// foreach field in xpath
			                        $field_name = str_replace(array("{", "}"), "", $match);

			                        $value_to_check = check_array_for_matching_key( $property, $field_name );

		                            if ( $value_to_check === false )
		                            {
		                                $value_to_check = '';
		                            }

			                        $url = str_replace($match, $value_to_check, $url);
			                    }
			                }

							if ( !empty($description) )
							{
								preg_match_all('/{[^}]*}/', $description, $matches);
				                if ( $matches !== FALSE && isset($matches[0]) && is_array($matches[0]) && !empty($matches[0]) )
				                {
				                    foreach ( $matches[0] as $match )
				                    {
				                    	// foreach field in xpath
				                        $field_name = str_replace(array("{", "}"), "", $match);

				                        $value_to_check = check_array_for_matching_key( $property, $field_name );

			                            if ( $value_to_check === false )
			                            {
			                                $value_to_check = '';
			                            }

				                        $description = str_replace($match, $value_to_check, $description);
				                    }
				                }
							}

			            	if ( 
								substr( strtolower($url), 0, 2 ) == '//' || 
								substr( strtolower($url), 0, 4 ) == 'http'
							)
							{
								// This is a URL
								$filename = basename( $url );

								// Check, based on the URL, whether we have previously imported this media
								$imported_previously = false;
								$imported_previously_id = '';

								if ( isset($import_settings['media_download_clause']) && $import_settings['media_download_clause'] == 'url_change' )
								{
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
								        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property_id, $post_id );
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
									        
									        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property_id, $post_id );
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
				elseif ( isset($import_settings['document_field_arrangement']) && $import_settings['document_field_arrangement'] == 'comma_delimited' )
				{
					if ( isset($import_settings['document_field']) && !empty($import_settings['document_field']) )
					{
						$document_mapping_specified = true;

						$value_to_check = check_array_for_matching_key( $property, $import_settings['document_field'] );

                        if ( $value_to_check !== false )
                        {
                            // we found this field
                            $delimiter = ( isset($import_settings['document_field_delimiter']) && !empty($import_settings['document_field_delimiter']) ) ? $import_settings['document_field_delimiter'] : ',';
                            
                            $explode_document_urls = explode($delimiter, $value_to_check);
                            $explode_document_urls = array_map('trim', $explode_document_urls);
                            $explode_document_urls = array_filter($explode_document_urls);

                            if ( !empty($explode_document_urls) )
                            {
                            	// we found image URLs
                            	foreach ( $explode_document_urls as $url )
                            	{
                            		$url = apply_filters( 'houzez_property_feed_csv_document_url', $url, $this->import_id );
                            		$description = '';

                            		if ( 
										substr( strtolower($url), 0, 2 ) == '//' || 
										substr( strtolower($url), 0, 4 ) == 'http'
									)
									{
										// This is a URL
										$filename = basename( $url );

										// Check, based on the URL, whether we have previously imported this media
										$imported_previously = false;
										$imported_previously_id = '';

										if ( isset($import_settings['media_download_clause']) && $import_settings['media_download_clause'] == 'url_change' )
										{
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
										        $this->log_error( 'An error occurred whilst importing ' . $url . '. The error was as follows: ' . $tmp->get_error_message(), $property_id, $post_id );
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
											        
											        $this->log_error( 'ERROR: An error occurred whilst importing ' . $url . '. The error was as follows: ' . $id->get_error_message(), $property_id, $post_id );
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
				}

				if ( $document_mapping_specified )
				{
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

					$this->log( 'Imported ' . count($media_ids) . ' documents (' . $new . ' new, ' . $existing . ' existing, ' . $deleted . ' deleted)', $property_id, $post_id );
				}
				else
				{
					$this->log( 'Not importing documents due to no document fields specified in \'Media\' section of import settings', $property_id, $post_id );
				}

				do_action( "houzez_property_feed_property_imported", $post_id, $property, $this->import_id );
				do_action( "houzez_property_feed_property_imported_csv", $post_id, $property, $this->import_id );

				$post = get_post( $post_id );
				do_action( "save_post_property", $post_id, $post, false );
				do_action( "save_post", $post_id, $post, false );

				if ( $inserted_updated == 'updated' )
				{
					$this->compare_meta_and_taxonomy_data( $post_id, $property_id, $metadata_before, $taxonomy_terms_before );
				}
			}

			++$property_row;

		} // end foreach property

		do_action( "houzez_property_feed_post_import_properties_csv", $this->import_id );

		$this->import_end();
	}

	public function remove_old_properties()
	{
		global $wpdb, $post;

		if ( !empty($this->properties) )
		{
			$import_settings = get_import_settings_from_id( $this->import_id );

			$property_id_field = $import_settings['property_id_field'];

			$import_refs = array();
			foreach ($this->properties as $property)
			{
				$property_id = '';

				if ( isset($property[$property_id_field]) && !empty($property[$property_id_field]) )
	            {
	                $import_refs[] = $property[$property_id_field];
	            }
			}

			$this->do_remove_old_properties( $import_refs );

			unset($import_refs);
		}
	}
}

}