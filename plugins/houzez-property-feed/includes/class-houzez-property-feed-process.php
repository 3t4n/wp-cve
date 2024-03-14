<?php

class Houzez_Property_Feed_Process {

	/**
	 * @var int
	 */
	public $instance_id;

	/**
	 * @var bool
	 */
	public $is_import = true;

	/**
	 * @var int
	 */
	public $import_id;

	/**
	 * @var int
	 */
	public $export_id;

	/**
	 * @var array
	 */
	public $properties = array();

    public function __construct() 
    {

    }

	public function import_start()
	{
		$this->log( 'Starting import' );
		
		wp_suspend_cache_invalidation( true );

		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );

		if ( !function_exists('media_handle_upload') ) 
		{
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
		}
	}

	public function import_end()
	{
		update_option( 'houzez_property_feed_property_' . $this->import_id, '', false );

		do_action( "houzez_property_feed_post_import_properties", $this->import_id );

		$this->log( 'Finished import' );

		wp_cache_flush();

		wp_suspend_cache_invalidation( false );

		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );
	}

	public function do_remove_old_properties( $import_refs = array() )
	{
		global $wpdb, $post;

		if ( !empty($import_refs) )
		{
			$imported_ref_key = ( ( $this->import_id != '' ) ? '_imported_ref_' . $this->import_id : '_imported_ref' );
			$imported_ref_key = apply_filters( 'houzez_property_feed_property_imported_ref_key', $imported_ref_key, $this->import_id );

			// Get all properties that don't have an _imported_ref matching the properties in $this->properties

			$args = array(
				'post_type' => 'property',
				'post_status' => 'publish',
				'nopaging' => true,
				'fields' => 'ids',
				'meta_query' => array(
					array(
						'key'     => $imported_ref_key,
						'value'   => $import_refs,
						'compare' => 'NOT IN',
					),
				),
			);
			$property_query = new WP_Query( $args );

			if ( $property_query->have_posts() )
			{
				while ( $property_query->have_posts() )
				{
					$property_query->the_post();

					$property_post_id = get_the_ID();

					wp_update_post(
			            array(
			                'ID' => get_the_ID(), 
			                'post_status' => 'draft'
			            )
			        );

			        $this->log( 'Property removed', get_post_meta($property_post_id, $imported_ref_key, TRUE), $property_post_id );

					do_action( "save_post_property", $property_post_id, get_post($property_post_id), false );
					do_action( "save_post", $property_post_id, get_post($property_post_id), false );

					do_action( "houzez_property_feed_property_removed", $property_post_id, $this->import_id );

					$wpdb->query("
						DELETE FROM
							" . $wpdb->prefix . "houzez_property_feed_media_queue
						WHERE
							`post_id` = '" . get_the_ID() . "'
					");
				}
			}
			wp_reset_postdata();
		}
	}

	public function delete_media( $post_id, $meta_key, $except_first = false )
	{
		$media_ids = get_post_meta( $post_id, $meta_key, TRUE );
		if ( !empty( $media_ids ) )
		{
			$i = 0;
			foreach ( $media_ids as $media_id )
			{
				if ( !$except_first || ( $except_first && $i > 0 ) )
				{
					if ( wp_delete_attachment( $media_id, TRUE ) !== FALSE )
					{
						// Deleted succesfully. Now remove from array
						if( ($key = array_search($media_id, $media_ids)) !== false)
						{
						    unset($media_ids[$key]);
						}
					}
					else
					{
						$this->log_error( 'Failed to delete ' . $meta_key . ' with attachment ID ' . $media_id, get_post_meta($post_id, $imported_ref_key, TRUE) );
					}
				}
				++$i;
			}
		}
		update_post_meta( $post_id, $meta_key, $media_ids );
	}

	public function add_missing_mapping( $mappings, $custom_field, $value, $import_id )
	{
		$options = get_option( 'houzez_property_feed', array() );

		if ( $value != '' && !isset($mappings[$custom_field][$value]) )
		{
			$mappings[$custom_field][$value] = '';

			if ( $import_id != '' && isset($options['imports'][$import_id]) )
			{
				$options['imports'][$import_id]['mappings'][$custom_field][$value] = '';

				update_option( 'houzez_property_feed', $options );

				//$this->log( 'Added new option (' . $value . ') to ' . $custom_field . ' mappings that you will need to assign' );
			}
		}

		if ( $import_id != '' && isset($options['imports'][$import_id]) )
		{
			return $options['imports'][$import_id];
		}

		return array();
	}

	public function log_error( $message, $agent_ref = '', $post_id = 0, $received_data = '' )
	{
		$current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
		$current_date = $current_date->format("Y-m-d H:i:s");

		if ( $this->instance_id != '' )
		{
			global $wpdb;

			$data = array(
                'instance_id' => $this->instance_id,
                'severity' => 1,
                'post_id' => $post_id,
                'entry' => $message,
                'log_date' => $current_date
            );

            if ( $this->is_import )
            {
            	$data['crm_id'] = $agent_ref;
            }

            if ( $received_data != '' )
            {
            	$data['received_data'] = $received_data;
            }
        
	        $wpdb->insert( 
	            $wpdb->prefix . "houzez_property_feed" . ( $this->is_import ? '' : '_export' ) . "_logs_instance_log", 
	            $data
	        );
		}
	}

	public function log( $message, $agent_ref = '', $post_id = 0, $received_data = '' )
	{
		$current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
		$current_date = $current_date->format("Y-m-d H:i:s");

		if ( $this->instance_id != '' )
		{
			global $wpdb;

			$data = array(
                'instance_id' => $this->instance_id,
                'severity' => 0,
                'post_id' => $post_id,
                'entry' => $message,
                'log_date' => $current_date
            );

            if ( $this->is_import )
            {
            	$data['crm_id'] = $agent_ref;
            }

            if ( $received_data != '' )
            {
            	$data['received_data'] = $received_data;
            }
        
	        $wpdb->insert( 
	            $wpdb->prefix . "houzez_property_feed" . ( $this->is_import ? '' : '_export' ) . "_logs_instance_log", 
	            $data
	        );
		}
	}

	public function open_ftp_connection( $host, $username, $password, $directory, $passive = '' )
	{
		// Connect to FTP directory and get file
		$ftp_connected = false;
		$ftp_conn = ftp_connect( $host );
		if ( $ftp_conn !== FALSE )
		{
			$ftp_login = ftp_login( $ftp_conn, $username, $password );
			if ( $ftp_login !== FALSE )
			{
				if ( $passive == 'yes' )
				{
					ftp_pasv( $ftp_conn, true );
				}

				if ( empty($directory) || ( !empty($directory) && ftp_chdir( $ftp_conn, $directory ) ) )
				{
					$ftp_connected = true;
				}
			}
		}
		return $ftp_connected ? $ftp_conn : null;
	}

	public function get_export_mapped_value( $post_id, $taxonomy = '', $field_type = '', $field_name = '' )
	{
		$return = '';

		if ( !empty($taxonomy) )
		{
			$terms = get_the_terms( $post_id, $taxonomy );
			$term_ids_to_use = array();
	        if ( !is_wp_error($terms) && !empty($terms) )
	        {
	        	foreach ( $terms as $term )
	        	{
	        		if ( !empty($term->parent) )
	        		{
	        			array_unshift($term_ids_to_use, $term->term_id); // push to front of array to give it priority
	        		}
	        		else
	        		{
		        		$term_ids_to_use[] = $term->term_id;
		        	}
	        	}
	        }
	        
	        if ( !empty($term_ids_to_use) )
	        {
	        	$export_settings = get_export_settings_from_id( $this->export_id );

	        	$mappings = ( isset($export_settings['mappings'][$taxonomy]) && !empty($export_settings['mappings'][$taxonomy]) ) ? $export_settings['mappings'][$taxonomy] : array();

	        	if ( !empty($mappings) )
	        	{
		        	foreach ( $term_ids_to_use as $term_id )
		        	{
		        		if ( isset($mappings[$term_id]) )
		        		{
		        			return $mappings[$term_id];
		        		}
		        	}
		        }
	        }
	    }

	    if ( !empty($field_type) && !empty($field_name) )
		{
			$field_value = get_post_meta( $post_id, $field_name, TRUE );

			if ( !empty($field_value) )
			{
				$export_settings = get_export_settings_from_id( $this->export_id );

	        	$mappings = ( isset($export_settings['mappings'][$field_type]) && !empty($export_settings['mappings'][$field_type]) ) ? $export_settings['mappings'][$field_type] : array();

	        	if ( !empty($mappings) )
	        	{
	        		if ( isset($mappings[sanitize_title($field_value)]) )
	        		{
	        			return $mappings[sanitize_title($field_value)];
	        		}
		        }
			}
		}

		return $return;
	}

	public function compare_meta_and_taxonomy_data( $post_id, $crm_id, $metadata_before = array(), $taxonomy_terms_before = array() )
	{
		$metadata_after = get_metadata('post', $post_id, '', true);

		foreach ( $metadata_after as $key => $value)
		{
			if ( in_array($key, array('fave_property_images', 'floor_plans', 'fave_attachments', 'fave_floor_plans_enable', '_property_import_data')) )
			{
				continue;
			}

			if ( !isset($metadata_before[$key]) )
			{
				$this->log( 'New meta data for ' . trim($key, '_') . ': ' . ( ( is_array($value) ) ? implode(", ", $value) : $value ), $crm_id, $post_id );
			}
			elseif ( $metadata_before[$key] != $metadata_after[$key] )
			{
				$this->log( 'Updated ' . trim($key, '_') . '. Before: ' . ( ( is_array($metadata_before[$key]) ) ? implode(", ", $metadata_before[$key]) : $metadata_before[$key] ) . ', After: ' . ( ( is_array($value) ) ? implode(", ", $value) : $value ), $crm_id, $post_id );
			}
		}

		$taxonomy_terms_after = array();
		$taxonomy_names = get_post_taxonomies( $post_id );
		foreach ( $taxonomy_names as $taxonomy_name )
		{
			$taxonomy_terms_after[$taxonomy_name] = wp_get_post_terms( $post_id, $taxonomy_name, array('fields' => 'ids') );
		}

		foreach ( $taxonomy_terms_after as $taxonomy_name => $ids)
		{
			if ( !isset($taxonomy_terms_before[$taxonomy_name]) )
			{
				$this->log( 'New taxonomy data for ' . $taxonomy_name . ': ' . ( ( is_array($ids) ) ? implode(", ", $ids) : $ids ), $crm_id, $post_id );
			}
			elseif ( $taxonomy_terms_before[$taxonomy_name] != $taxonomy_terms_after[$taxonomy_name] )
			{
				$this->log( 'Updated ' . $taxonomy_name . '. Before: ' . ( ( is_array($taxonomy_terms_before[$taxonomy_name]) ) ? implode(", ", $taxonomy_terms_before[$taxonomy_name]) : $taxonomy_terms_before[$taxonomy_name] ) . ', After: ' . ( ( is_array($ids) ) ? implode(", ", $ids) : $ids ), $crm_id, $post_id );
			}
		}
	}

	public function do_geocoding_lookup( $post_id, $agent_ref, $address, $address_osm, $country = '' )
	{
		if ( empty($country) )
		{
			$country = 'GB';
		}

		//if ( get_option('propertyhive_geocoding_provider') == 'osm' )
		//{
			if ( empty($address_osm) )
			{
				$address_osm = $address;
			}

			$request_url = "https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=" . strtolower($country) . "&addressdetails=1&q=" . urlencode(implode(", ", $address_osm));

			$response = wp_remote_get($request_url);

			if ( !is_wp_error( $response ))
			{
				if ( is_array( $response ) )
				{
					$body = wp_remote_retrieve_body( $response );
					$json = json_decode($body, true);

					if ( !empty($json) && isset($json[0]['lat']) && isset($json[0]['lon']) )
					{
						$lat = $json[0]['lat'];
						$lng = $json[0]['lon'];

						if ($lat != '' && $lng != '')
						{
							update_post_meta( $post_id, 'houzez_geolocation_lat', $lat );
							update_post_meta( $post_id, 'houzez_geolocation_long', $lng );

							return array( $lat, $lng );
						}
					}
					else
					{
						$this->log_error( 'No co-ordinates returned for the address provided: ' . implode( ", ", $address_osm ), $agent_ref );
					}
				}
				else
				{
					$this->log_error( 'Failed to parse JSON response from OSM Geocoding service.', $agent_ref );
				}
			}
			else
			{
				$this->log_error( 'Error returned from geocoding service: ' . $response->get_error_message(), $agent_ref );
			}

			sleep(1); // Sleep due to nominatim throttling limits
		/*}
		else
		{
			$api_key = get_option('propertyhive_google_maps_geocoding_api_key', '');
			if ( $api_key == '' )
			{
				$api_key = get_option('propertyhive_google_maps_api_key', '');
			}
			if ( $api_key != '' )
			{
				if ( ini_get('allow_url_fopen') )
				{
					$request_url = "https://maps.googleapis.com/maps/api/geocode/xml?address=" . urlencode( implode( ", ", $address ) ) . "&sensor=false&region=" . strtolower($country); // the request URL you'll send to google to get back your XML feed
					
					if ( $api_key != '' ) { $request_url .= "&key=" . $api_key; }

					$response = wp_remote_get($request_url);

					if ( is_array( $response ) && !is_wp_error( $response ) ) 
					{
						$header = $response['headers']; // array of http header lines
						$body = $response['body']; // use the content

						$xml = simplexml_load_string($body);

						if ( $xml !== FALSE )
						{
							$status = $xml->status; // Get the request status as google's api can return several responses

							if ($status == "OK") 
							{
								//request returned completed time to get lat / lng for storage
								$lat = (string)$xml->result->geometry->location->lat;
								$lng = (string)$xml->result->geometry->location->lng;
								
								if ($lat != '' && $lng != '')
								{
									update_post_meta( $post_id, 'houzez_geolocation_lat', $lat );
									update_post_meta( $post_id, 'houzez_geolocation_long', $lng );

									return true;
								}
							}
							else
							{
								$this->log_error( 'Google Geocoding service returned status ' . $status, $agent_ref );
								sleep(3);

								if ( $status == "REQUEST_DENIED" )
								{
									return 'denied';
								}
							}
						}
						else
						{
							$this->log_error( 'Failed to parse XML response from Google Geocoding service', $agent_ref );
						}
					}
					else
					{
						$this->log_error( 'Invalid response when trying to obtain co-ordinates', $agent_ref );
					}
				}
				else
				{
					$this->log_error( 'Failed to obtain co-ordinates as allow_url_fopen setting is disabled', $agent_ref );
				}
			}
			else
			{
				$this->log( 'Not performing Google Geocoding request as no API key present in settings', $agent_ref );
			}
		}*/

		return false;
	}
}

class SimpleXMLExtendedHpf extends SimpleXMLElement {

    public function addCData($name, $value) {
        $new = parent::addChild($name);
        $base = dom_import_simplexml($new);
        $docOwner = $base->ownerDocument;
        $base->appendChild($docOwner->createCDATASection($value));
        return $new;
    } 

}