<?php
/**
 * Class for managing the export process of a Zoopla file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Zoopla extends Houzez_Property_Feed_Process {

    /**
     * @var array
     */
    private $get_branch_properties_responses = array();

	public function __construct()
	{
		$this->is_import = false;

		add_action( 'save_post', array( $this, 'send_realtime_feed_request' ), 99 );

        add_filter( 'houzez_before_submit_property', array( $this, 'remove_save_post_hook' ) );
        add_filter( 'houzez_before_update_property', array( $this, 'remove_save_post_hook' ) );

        add_action( 'houzez_after_property_submit', array( $this, 'send_realtime_feed_request' ), 99 );
        add_action( 'houzez_after_property_update', array( $this, 'send_realtime_feed_request' ), 99 );

        add_action( 'houzezpropertyfeedreconcilecronhook', array( $this, 'reconcile' ) );
	}

    public function remove_save_post_hook($new_property)
    {
        remove_action( 'save_post', array( $this, 'send_realtime_feed_request' ), 99 );
        return $new_property;
    }

    public function send_realtime_feed_request( $post_id ) 
    {
        global $wpdb;

        if ( $post_id == null )
            return;

        if ( get_post_type($post_id) != 'property' )  
            return; 

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;

        // If this is just a revision, don't make request.
        if ( wp_is_post_revision( $post_id ) )
            return;

        if ( get_post_status( $post_id ) == 'auto-draft' )
            return;

        $keep_logs_days = (string)apply_filters( 'houzez_property_feed_keep_logs_days', '1' );

        // Revert back to 1 days if anything other than numbers has been passed
        // This prevent SQL injection and errors
        if ( !preg_match("/^\d+$/", $keep_logs_days) )
        {
            $keep_logs_days = '1';
        }

        // Delete logs older than 1 days
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . "houzez_property_feed_export_logs_instance WHERE start_date < DATE_SUB(NOW(), INTERVAL " . $keep_logs_days . " DAY)" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log WHERE log_date < DATE_SUB(NOW(), INTERVAL " . $keep_logs_days . " DAY)" );

        global $post;  

        if ( empty( $post ) )
            $post = get_post($post_id);

        $options = get_option( 'houzez_property_feed', array() );
        $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

        if ( is_array($exports) && !empty($exports) )
        {
            // remove any non-cron formats
            foreach ( $exports as $export_id => $export_settings  )
            {
                $format = get_format_from_export_id( $export_id );
                if ( $export_settings['format'] != 'zoopla' )
                {
                    //remove non-Zoopla exports from being processed
                    unset($exports[$export_id]);
                }
            }
        }

        if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true )
        {
            
        }
        else
        {
            // ensure only one export if pro not active
            foreach ( $exports as $export_id => $export_settings )
            {
                if ( !isset($export_settings['running']) || ( isset($export_settings['running']) && $export_settings['running'] !== true ) )
                {
                    continue;
                }

                if ( isset($export_settings['deleted']) && $export_settings['deleted'] === true )
                {
                    continue;
                }

                $exports = array( $export_id => $export_settings );
                break;
            }
        }

        foreach ( $exports as $export_id => $export_settings )
        {
            $this->export_id = $export_id;

            if ( !isset($export_settings['running']) || ( isset($export_settings['running']) && $export_settings['running'] !== true ) )
            {
                continue;
            }

            if ( isset($export_settings['deleted']) && $export_settings['deleted'] === true )
            {
                continue;
            }

            // log instance start
            $current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
            $current_date = $current_date->format("Y-m-d H:i:s");

            $wpdb->insert( 
                $wpdb->prefix . "houzez_property_feed_export_logs_instance", 
                array(
                    'export_id' => $export_id,
                    'start_date' => $current_date
                )
            );
            $this->instance_id = $wpdb->insert_id;

            // decide if we need to do a SEND or REMOVE
            $property_send_request_send = false;

            // Get property
            $args = array(
                'post_type' => 'property',
                'nopaging' => true,
                'p' => $post_id,
                'post_status' => 'publish',
            );

            $meta_query = array();
            $tax_query = array();

            $args['meta_query'] = $meta_query;
            $args['tax_query'] = $tax_query;

            $args = apply_filters( 'houzez_property_feed_export_property_args', $args, $this->export_id );
            $args = apply_filters( 'houzez_property_feed_export_zoopla_property_args', $args, $this->export_id );

            $property_query = new WP_Query( $args );

            if ($property_query->have_posts())
            {
                while ($property_query->have_posts())
                {
                    $property_query->the_post();

                    $branch_code = $this->get_branch_code( $post->ID );
                    $department = $this->get_department( $post->ID );

                    $property_send_request_send = true;

                    if ( empty($branch_code) )
                    {
                        $this->log_error("No branch code found. Not including property. Ensure you have departments set under 'Export Properties > Settings > Departments' and branch codes entered accordingly in the export settings", '', $post->ID);
                    }
                    else
                    {
                        $ok_to_send = true;
                        $limit = apply_filters( "houzez_property_feed_property_limit", 25 );
                        if ( $limit !== false )
                        {
                            // check no more than 25 properties exist
                            if ( isset($this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department]) )
                            {
                                $response = $this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department];
                            }
                            else
                            {
                                // Should check branch properties before making remove request
                                $request_data = array();
                                $request_data['branch_reference'] = $branch_code;

                                $response = $this->do_curl_request( $request_data, $export_settings['get_branch_properties_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/list.json', $post->ID, false );

                                if ($response === FALSE) 
                                { 
                                    $this->log_error('Failed to get branch properties', '', $post->ID);
                                    $ok_to_send = false;
                                }

                                $this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department] = $response;
                            }

                            if (isset($response['listings']) && is_array($response['listings']) && !empty($response['listings']))
                            {
                                if ( count($response['listings']) >= $limit )
                                {
                                    $this->log_error($limit . ' or more properties already found to be active. You\'ll need to remove properties first before being able to send this one. <a href="https://houzezpropertyfeed.com/#pricing" target="_blank">Upgrade to PRO</a> to export more', '', $post->ID);
                                    $ok_to_send = false;
                                }
                            }
                        }

                        if ( $ok_to_send )
                        {
                            $success = $this->create_send_property_request( $post->ID );

                            /*if ($success === FALSE)
                            {
                                add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99, 2 );
                            }*/
                        }
                    }
                }
            }

            wp_reset_postdata();

            if ( !$property_send_request_send )
            {
                // send request not sent. Must need to remove it
                $success = $this->create_remove_property_request( $post_id );

                /*if ($success === FALSE)
                {
                    add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99, 2 );
                }*/
            }

            // log instance end
            $current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
            $current_date = $current_date->format("Y-m-d H:i:s");

            $wpdb->update( 
                $wpdb->prefix . "houzez_property_feed_export_logs_instance", 
                array( 
                    'end_date' => $current_date
                ),
                array( 'id' => $this->instance_id )
            );
        }
    }

    public function create_send_property_request( $post_id )
    {
        $export_settings = get_export_settings_from_id( $this->export_id );

        $department = $this->get_department( $post_id );

        $request_data = array();

        $request_data['bathrooms'] = (int)get_post_meta( $post_id, 'fave_property_bathrooms', TRUE );
        $branch_code = $this->get_branch_code( $post_id );
        $request_data['branch_reference'] = $branch_code;
        $request_data['category'] = 'residential';
        $request_data['detailed_description'] = array(
            array(
                'text' => trim( ( strip_tags(get_the_content($post_id)) != '' ) ? get_the_content($post_id) : get_the_excerpt($post_id) )
            )
        );
        $request_data['display_address'] = get_the_title($post_id);

        $features = array();
        $term_list = wp_get_post_terms($post_id, 'property_feature', array("fields" => "all"));
        if ( !is_wp_error($term_list) && is_array($term_list) && !empty($term_list) )
        {
            foreach ( $term_list as $term )
            {
                $features[] = $term->name;
            }
        }
        if ( !empty($features) ) { $request_data['feature_list'] = $features; }

        $request_data['life_cycle_status'] = $this->get_export_mapped_value($post_id, 'property_status');
        $request_data['listing_reference'] = $branch_code . '_' . $post_id;
        if ( !empty(get_post_meta( $post_id, 'fave_property_rooms', TRUE )) ) { $request_data['living_rooms'] = (int)get_post_meta( $post_id, 'fave_property_rooms', TRUE ); }
        $request_data['location'] = array(
            'country_code' => 'GB',
        );
        if ( get_post_meta( $post_id, 'fave_property_address', TRUE ) != '' ) 
        {
            $number = '';
            $street = '';

            $property_address = get_post_meta( $post_id, 'fave_property_address', TRUE );
            $property_address = str_replace(',', ' ', $property_address);

            $explode_address = explode(" ", $property_address);
            if ( preg_match('/\d/', $explode_address[0]) )
            {
                $number = $explode_address[0];
                $street = str_replace($number, '', get_post_meta( $post_id, 'fave_property_address', TRUE ));
                $street = trim($street);
                $street = trim($street, ',');
                $street = trim($street);
            }
            else
            {
                $street = get_post_meta( $post_id, 'fave_property_address', TRUE );
            }

            if ( !empty($number) ) { $request_data['location']['property_number_or_name'] = $number; }
            if ( !empty($street) ) { $request_data['location']['street_name'] = $street; }
        }
        
        $address_taxonomies = array( 'property_state', 'property_city', 'property_area' );
        $address_fields = array();
        foreach ( $address_taxonomies as $address_taxonomy )
        {
            $terms = get_the_terms( $post_id, $address_taxonomy );
            $term_ids_to_use = array();
            if ( !is_wp_error($terms) && !empty($terms) )
            {
                foreach ( $terms as $term )
                {
                    $address_fields[] = $term->name;
                    break;
                }
            }
        }

        if ( isset($address_fields[0]) ) { $request_data['location']['locality'] = $address_fields[0]; }
        if ( isset($address_fields[1]) ) 
        { 
            $request_data['location']['town_or_city'] = $address_fields[1]; 
        }
        elseif ( isset($address_fields[0]) ) 
        { 
            $request_data['location']['town_or_city'] = $address_fields[0]; 
        }
        elseif ( isset($address_fields[2]) ) 
        { 
            $request_data['location']['town_or_city'] = $address_fields[2]; 
        }
        if ( isset($address_fields[2]) ) { $request_data['location']['county'] = $address_fields[2]; }
        if ( get_post_meta($post_id, 'fave_property_zip', true) != '' ) { $request_data['location']['postal_code'] = strtoupper(get_post_meta($post_id, 'fave_property_zip', true)); }
        
        $fave_property_location = get_post_meta($post_id, 'fave_property_location', true);
        $explode_fave_property_location = explode(",", $fave_property_location);
        $lat = '';
        $lng = '';
        if ( count($explode_fave_property_location) >= 2 )
        {
            $lat = $explode_fave_property_location[0];
            $lng = $explode_fave_property_location[1];
        }
        if ( floatval($lat) != '' && floatval($lng) != '' )
        {
            $request_data['location']['coordinates'] = array();
            if ( floatval($lat) != '' ) { $request_data['location']['coordinates']['latitude'] = floatval($lat); }
            if ( floatval($lng) != '' ) { $request_data['location']['coordinates']['longitude'] = floatval($lng); }
        }

        $rent_frequency = '';
        if ( $department == 'lettings' )
        {
            if ( 
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'pm') !== FALSE ||
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'pcm') !== FALSE ||
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'month') !== FALSE
            )
            {
                $rent_frequency = 'per_month';
            }
            elseif ( 
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'person') !== FALSE ||
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'pppw') !== FALSE
            )
            {
                $rent_frequency = 'per_week';
            }
            elseif ( 
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'week') !== FALSE ||
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'pw') !== FALSE
            )
            {
                $rent_frequency = 'per_week';
            }
            elseif ( 
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'pq') !== FALSE ||
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'quarter') !== FALSE
            )
            {
                $rent_frequency = 'per_quarter';
            }
            elseif ( 
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'pa') !== FALSE ||
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'annu') !== FALSE ||
                strpos(strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )), 'year') !== FALSE
            )
            {
                $rent_frequency = 'per_year';
            }
        }

        $price_qualifier = '';
        $request_data['pricing'] = array(
            'transaction_type' => ( $department == "lettings" ? 'rent' : 'sale' ),
            'currency_code' => 'GBP',
            'price' => (int)get_post_meta( $post_id, 'fave_property_price', TRUE ),
        );
        if ( $rent_frequency != '' ) { $request_data['pricing']['rent_frequency'] = $rent_frequency; }
        if ( $price_qualifier != '' ) { $request_data['pricing']['price_qualifier'] = $price_qualifier; }

        $property_type = $this->get_export_mapped_value($post_id, 'property_type');
        if ( $property_type != '' ) { $request_data['property_type'] = $property_type; }
        $request_data['summary_description'] = trim(get_the_excerpt($post_id));

        $request_data['total_bedrooms'] = (int)get_post_meta( $post_id, 'fave_property_bedrooms', TRUE );

        $request_data['content'] = array();
         
        // IMAGES
        $attachment_ids = get_post_meta( $post_id, 'fave_property_images' );
        foreach ($attachment_ids as $attachment_id)
        {
            $url = wp_get_attachment_image_src( $attachment_id, 'large' );
            if ($url !== FALSE)
            {
                $attachment_data = wp_prepare_attachment_for_js( $attachment_id );

                $media = array(
                    'url' => $url[0],
                    'type' => 'image',
                );
                if ( isset( $attachment_data['alt'] ) && $attachment_data['alt'] != '' )
                {
                    $media['caption'] = $attachment_data['alt'];
                }

                $request_data['content'][] = $media;
            }
        }

        // FLOORPLANS
        $floorplans = get_post_meta( $post_id, 'floor_plans', true );
        if ( is_array($floorplans) && !empty($floorplans) )
        {
            foreach ( $floorplans as $floorplan )
            {
                $url = ( isset($floorplan['fave_plan_image']) ? $floorplan['fave_plan_image'] : '' );
                $text = ( isset($floorplan['fave_plan_title']) ? $floorplan['fave_plan_title'] : 'Floorplan' );
                if ( !empty($url) )
                {
                    $media = array(
                        'url' => $url,
                        'type' => 'floor_plan',
                    );
                    if ( $text != '' )
                    {
                        $media['caption'] = $text;
                    }

                    $request_data['content'][] = $media;
                }
            }
        }

        // BROCHURES
        $attachment_ids = get_post_meta( $post_id, 'fave_attachments' );
        foreach ($attachment_ids as $attachment_id)
        {
            $url = wp_get_attachment_url( $attachment_id );
            if ($url !== FALSE)
            {
                $attachment_data = wp_prepare_attachment_for_js( $attachment_id );

                $media = array(
                    'url' => $url,
                    'type' => 'brochure',
                );
                if ( isset( $attachment_data['alt'] ) && $attachment_data['alt'] != '' )
                {
                    $media['caption'] = $attachment_data['alt'];
                }

                $request_data['content'][] = $media;
            }
        }

        // VIRTUAL TOURS
        $virtual_tour_urls = array();
        if ( get_post_meta( $post_id, 'fave_video_url', true ) != '' )
        {
            $virtual_tour_urls[] = get_post_meta( $post_id, 'fave_video_url', true );
        }

        if ( !empty($virtual_tour_urls) )
        {
            foreach ($virtual_tour_urls as $url)
            {
                if ( trim($url) != '' )
                {
                    $media = array(
                        'url' => $url,
                        'type' => 'virtual_tour',
                        'caption' => 'Virtual Tour',
                    );

                    $request_data['content'][] = $media;
                }
            }
        }

        $request_data = apply_filters( 'houzez_property_feed_export_property_data', $request_data, $post_id, $this->export_id );
        $request_data = apply_filters( 'houzez_property_feed_export_zoopla_property_data', $request_data, $post_id, $this->export_id );

        array_walk_recursive( $request_data, array($this, 'replace_bad_characters' ) );

        $do_request = true;
        if ( isset($export_settings['only_send_if_different']) && $export_settings['only_send_if_different'] == 'yes' )
        {
            $previous_hash = get_post_meta( $post_id, '_zoopla_sha1_' . $this->export_id, TRUE );

            $request_data_to_check = $request_data;
            
            if ( $previous_hash == sha1(json_encode($request_data_to_check)) )
            {
                // Matches the data sent last time. Don't send again
                $do_request = false;
            }
        }

        if ( $do_request )
        {
            $request = $this->do_curl_request( $request_data, $export_settings['send_property_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/update.json', $post_id );

            if ( $request !== FALSE )
            {
                $request_data_to_check = $request_data;
                
                // Request was successful
                // Save the SHA-1 hash so we know for next time whether to push it again or not
                update_post_meta( $post_id, '_zoopla_sha1_' . $this->export_id, sha1(json_encode($request_data_to_check)) );
            }
        }
        else
        {
            $request = true;
        }
    }

    public function create_remove_property_request( $post_id )
    {
        $export_settings = get_export_settings_from_id( $this->export_id );

        $department = $this->get_department( $post_id );

        $response = true;

        $branch_code = $this->get_branch_code( $post_id );

        if ( empty($branch_code) )
        {
            // log error
            return false;
        }

        if ( isset($this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department]) )
        {
            $response = $this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department];
        }
        else
        {
            // Should check branch properties before making remove request
            $request_data = array();
            $request_data['branch_reference'] = $branch_code;

            $response = $this->do_curl_request( $request_data, $export_settings['get_branch_properties_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/list.json', $post_id, false );

            if ($response === FALSE) { return false; }

            $this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department] = $response;
        }

        $ok_to_remove = false;
        $agent_ref = $post_id;
        if (isset($response['listings']) && is_array($response['listings']) && !empty($response['listings']))
        {
            foreach ($response['listings'] as $property)
            {
                $agent_ref = str_replace($branch_code . '_', "", $property['listing_reference']);
                if ( $agent_ref == $post_id )
                {
                    // We found this property to be active on the site
                    $ok_to_remove = true;
                    break;
                }
            }
        }

        if (!$ok_to_remove) { return true; }

        $request_data = array();
        $request_data['listing_reference'] = $branch_code . '_' . $agent_ref;

        $request_data = apply_filters( 'houzez_property_feed_export_zoopla_remove_property_request_data', $request_data, $post_id, $this->export_id );

        $do_request = true;
        if ( isset($export_settings['only_send_if_different']) && $export_settings['only_send_if_different'] == 'yes' )
        {
            $previous_hash = get_post_meta( $post_id, '_zoopla_sha1_' . $this->export_id, TRUE );

            $request_data_to_check = $request_data;

            if ( $previous_hash == sha1(json_encode($request_data_to_check)) )
            {
                // Matches the data sent last time. Don't send again
                $do_request = false;
            }
        }

        if ( $do_request )
        {
            $response = $this->do_curl_request( $request_data, $export_settings['remove_property_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/delete.json', $post_id );

            if ( $response !== FALSE )
            {
                $request_data_to_check = $request_data;

                // Request was successful
                // Save the SHA-1 hash so we know for next time whether to push it again or not
                update_post_meta( $post_id, '_zoopla_sha1_' . $this->export_id, sha1(json_encode($request_data_to_check)) );
            }
        }
        else
        {
            $response = true;
        }

        return $response;
    }

    public function do_curl_request( $request_data, $api_url, $profile_url, $post_id = 0, $log_success = true ) 
    {
        $export_settings = get_export_settings_from_id( $this->export_id );

        $request_data = json_encode($request_data);

        if ( apply_filters( 'houzez_property_feed_export_zoopla_perform_request', true ) !== true )
        {
            $this->log_error("Disabling request due to houzez_property_feed_export_zoopla_perform_request filter", '', $post_id);
            return false;
        }

        $ch = curl_init();

        $this->log("Sending request: " . htmlentities($request_data), '', $post_id);

        $uploads_dir = wp_upload_dir();
                 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);

        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_SSLKEY, $uploads_dir['basedir'] . '/houzez_property_feed_export/'. $export_settings['private_key_file']);
        curl_setopt($ch, CURLOPT_SSLCERT, $uploads_dir['basedir'] . '/houzez_property_feed_export/'. $export_settings['certificate_file']);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; profile=' . $profile_url, // e.g. http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/update.json
            'ZPG-Listing-ETag: ' . sha1($request_data) . time(),
        ));

        $output = curl_exec($ch);

        if ( $output === FALSE )
        {
            $this->log_error("Error sending cURL request: " . curl_errno($ch) . " - " . curl_error($ch), '', $post_id);
        
            return false;
        }
        else
        {
            $response = json_decode($output, TRUE);

            if (isset($response['error_name']) && !empty($response['error_name']))
            {
                //$this->log_error("Error returned in response: " . $response['error_name'] . ( ( isset($response['error_advice']) ) ? " - " . $response['error_advice'] : '' ), '', $post_id);
                $this->log_error("Request failed. Response: " . $output, '', $post_id);
                
                return false;
            }
            else
            {
                if ( $log_success )
                {
                    $this->log("Request successful. Response: " . $output, '', $post_id);
                }
            }
        }

        return $response;
    }

    private function get_branch_code( $post_id )
    {
        $export_settings = get_export_settings_from_id( $this->export_id );

        $branch_code = '';
        $agent_display_option = get_post_meta( $post_id, 'fave_agent_display_option', true );

        $department = $this->get_department( $post_id );

        switch ( $agent_display_option )
        {
            case "author_info":
            {
                $thing_id = get_post_field( 'post_author', $post_id );
                $branch_code = isset($export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department]) ?
                    $export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department] :
                    '';
                break;
            }
            case "agent_info":
            {
                $thing_id = get_post_meta( $post_id, 'fave_agents', true );
                $branch_code = isset($export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department]) ?
                    $export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department] :
                    '';
                break;
            }
            case "agency_info":
            {
                $thing_id = get_post_meta( $post_id, 'fave_property_agency', true );
                $branch_code = isset($export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department]) ?
                    $export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department] :
                    '';
                break;
            }
        }

        return $branch_code;
    }

    private function get_department( $post_id )
    {
        $department = 'sales';

        $options = get_option( 'houzez_property_feed' , array() );
        $sales_statuses = ( isset($options['sales_statuses']) && is_array($options['sales_statuses']) && !empty($options['sales_statuses']) ) ? $options['sales_statuses'] : array();
        $lettings_statuses = ( isset($options['lettings_statuses']) && is_array($options['lettings_statuses']) && !empty($options['lettings_statuses']) ) ? $options['lettings_statuses'] : array();

        $status_terms = get_the_terms( $post_id, 'property_status' );
        if ( !is_wp_error($status_terms) && !empty($status_terms) )
        {
            foreach ( $status_terms as $term )
            {
                if ( in_array($term->term_id, $sales_statuses) )
                {
                    $department = 'sales';
                }
                elseif ( in_array($term->term_id, $lettings_statuses) )
                {
                    $department = 'lettings';
                }
            }
        }

        return $department;
    }

    public function replace_bad_characters( &$value, $key )
    {
        if ( is_string($value) )
        {
            // Replace bad dash and apostrophe character that breaks JSON
            $value = str_replace( "’", "'", str_replace( '–', '-', $value ));
        }
    }

    public function reconcile()
    {
        global $wpdb;

        $options = get_option( 'houzez_property_feed' , array() );
        $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

        $this->items = array();

        foreach ( $exports as $key => $export )
        {
            if ( isset($exports[$key]['deleted']) && $exports[$key]['deleted'] === true )
            {
                unset( $exports[$key] );
            }

            if ( isset($exports[$key]['format']) && $exports[$key]['format'] !== 'zoopla' )
            {
                unset( $exports[$key] );
            }

            if ( isset($exports[$key]['running']) && $exports[$key]['running'] !== true )
            {
                unset( $exports[$key] );
            }
        }

        // here we should be left with all active Zoopla exports
        foreach ( $exports as $export_id => $export )
        {
            $this->export_id = $export_id;

            // log instance start
            $current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
            $current_date = $current_date->format("Y-m-d H:i:s");

            $wpdb->insert( 
                $wpdb->prefix . "houzez_property_feed_export_logs_instance", 
                array(
                    'export_id' => $export_id,
                    'start_date' => $current_date
                )
            );

            $this->instance_id = $wpdb->insert_id;

            $this->log("Reconciling properties in " . $export['name'] . " export");

            // Get array of sales branch codes we need to check for reconcilliation
            $branch_codes = array();
            foreach ( $export as $export_key => $value ) 
            {
                // Check if the key starts with 'branch_code_' and ends with '_sales'
                if ( strpos($export_key, 'branch_code_') === 0 && substr($export_key, -6) === '_sales' ) 
                {
                    $branch_codes[] = $value;
                }
            }
            $branch_codes = array_filter($branch_codes);
            $branch_codes = array_unique($branch_codes);

            if ( !empty($branch_codes) )
            {
                foreach ( $branch_codes as $branch_code )
                {
                    // Make request to get sales branch properties
                    $request_data = array();

                    $request_data['branch_reference'] = $branch_code;

                    $response = $this->do_curl_request( $request_data, $export['get_branch_properties_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/list.json', '', false );

                    if ($response !== FALSE) 
                    {
                        if (isset($response['listings']) && is_array($response['listings']) && !empty($response['listings']))
                        {
                            // Loop through the listings and ensure they're published / should be sent in HPF
                            foreach ($response['listings'] as $property)
                            {
                                $agent_ref = str_replace($branch_code . '_', "", $property['listing_reference']);

                                $ok_to_remove = false;

                                // Check if this agent ref is active, on market and selected to be sent to the portal
                                $args = array(
                                    'post_type' => 'property',
                                    'nopaging' => true,
                                    'p' => $agent_ref,
                                    'post_status' => 'publish',
                                );

                                $meta_query = array();
                                $tax_query = array();

                                $args['meta_query'] = $meta_query;
                                $args['tax_query'] = $tax_query;

                                $args = apply_filters( 'houzez_property_feed_export_property_args', $args, $export_id );
                                $args = apply_filters( 'houzez_property_feed_export_zoopla_property_args', $args, $export_id );
                                
                                $property_query = new WP_Query( $args );
                                if ( $property_query->have_posts() )
                                {
                                    // Don't do anything, we found this property
                                }
                                else
                                {
                                    $ok_to_remove = true;
                                }

                                if ($ok_to_remove)
                                {
                                    // Hmm.. This property was on the portal but not an active in HPF
                                    // Let's remove it.
                                    $request_data = array();

                                    $request_data['listing_reference'] = $branch_code . '_' . $agent_ref;
                                    //$request_data['deletion_reason'] = '';

                                    $request_data = apply_filters( 'houzez_property_feed_export_zoopla_remove_property_request_data', $request_data, $post_id, $export_id );
                                    
                                    $this->log("Removing property " . $branch_code . '_' . $agent_ref . " as not found when reconciling");
                                    $this->do_curl_request( $request_data, $export['remove_property_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/delete.json', '' );
                                }
                                wp_reset_postdata();

                            } // end foreach property

                        } // end if properties set
                    }

                } // end foreach sales branch codes
            }

            // Get array of lettings branch codes we need to check for reconcilliation
            $branch_codes = array();
            foreach ( $export as $export_key => $value ) 
            {
                // Check if the key starts with 'branch_code_' and ends with '_lettings'
                if ( strpos($export_key, 'branch_code_') === 0 && substr($export_key, -9) === '_lettings' ) 
                {
                    $branch_codes[] = $value;
                }
            }
            $branch_codes = array_filter($branch_codes);
            $branch_codes = array_unique($branch_codes);

            if ( !empty($branch_codes) )
            {
                foreach ( $branch_codes as $branch_code )
                {
                    // Make request to get sales branch properties
                    $request_data = array();

                    $request_data['branch_reference'] = $branch_code;

                    $response = $this->do_curl_request( $request_data, $export['get_branch_properties_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/list.json', '', false );

                    if ($response !== FALSE) 
                    {
                        if (isset($response['listings']) && is_array($response['listings']) && !empty($response['listings']))
                        {
                            // Loop through the listings and ensure they're published / should be sent in HPF
                            foreach ($response['listings'] as $property)
                            {
                                $agent_ref = str_replace($branch_code . '_', "", $property['listing_reference']);

                                $ok_to_remove = false;

                                // Check if this agent ref is active, on market and selected to be sent to the portal
                                $args = array(
                                    'post_type' => 'property',
                                    'nopaging' => true,
                                    'p' => $agent_ref,
                                    'post_status' => 'publish',
                                );

                                $meta_query = array();
                                $tax_query = array();

                                $args['meta_query'] = $meta_query;
                                $args['tax_query'] = $tax_query;

                                $args = apply_filters( 'houzez_property_feed_export_property_args', $args, $export_id );
                                $args = apply_filters( 'houzez_property_feed_export_zoopla_property_args', $args, $export_id );
                                
                                $property_query = new WP_Query( $args );
                                if ( $property_query->have_posts() )
                                {
                                    // Don't do anything, we found this property
                                }
                                else
                                {
                                    $ok_to_remove = true;
                                }

                                if ($ok_to_remove)
                                {
                                    // Hmm.. This property was on the portal but not an active in HPF
                                    // Let's remove it.
                                    $request_data = array();

                                    $request_data['listing_reference'] = $branch_code . '_' . $agent_ref;
                                    //$request_data['deletion_reason'] = '';

                                    $request_data = apply_filters( 'houzez_property_feed_export_zoopla_remove_property_request_data', $request_data, $post_id, $export_id );
                                    
                                    $this->log("Removing property " . $branch_code . '_' . $agent_ref . " as not found when reconciling");
                                    $this->do_curl_request( $request_data, $export['remove_property_url'], 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.2/schemas/listing/delete.json', '' );
                                }
                                wp_reset_postdata();

                            } // end foreach property

                        } // end if properties set
                    }

                } // end foreach lettings branch codes
            }

            if ( !empty($this->instance_id) )
            {
                $this->log("Reconciling complete");

                // log instance end
                $current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
                $current_date = $current_date->format("Y-m-d H:i:s");

                $wpdb->update( 
                    $wpdb->prefix . "houzez_property_feed_export_logs_instance", 
                    array( 
                        'end_date' => $current_date
                    ),
                    array( 'id' => $this->instance_id )
                );

                do_action( 'houzez_property_feed_cron_end', $this->instance_id, $export_id );
                do_action( 'houzez_property_feed_export_cron_end', $this->instance_id, $export_id );
            }
        } // end foreach export
    }
}

}

new Houzez_Property_Feed_Format_Zoopla();