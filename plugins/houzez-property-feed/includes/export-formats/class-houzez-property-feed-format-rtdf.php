<?php
/**
 * Class for managing the export process of an RTDF file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_RTDF extends Houzez_Property_Feed_Process {

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
                if ( $export_settings['format'] != 'rtdf' )
                {
                    //remove non-RTDF exports from being processed
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
            $args = apply_filters( 'houzez_property_feed_export_rtdf_property_args', $args, $this->export_id );

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

                                // Network
                                $request_data['network'] = array();
                                $request_data['network']['network_id'] = (int)$export_settings['network_id'];
                                
                                // Branch
                                $request_data['branch'] = array();
                                $request_data['branch']['branch_id'] = (int)$branch_code;
                                $request_data['branch']['channel'] = ( $department == 'sales' ? 1 : 2 ); // 1 for sales, 2 for lettings

                                $response = $this->do_curl_request( $request_data, $export_settings['get_branch_properties_url'], $post->ID, false );

                                if ($response === FALSE) { return false; }

                                $this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department] = $response;
                            }

                            if (isset($response['property']) && is_array($response['property']) && !empty($response['property']))
                            {
                                if ( count($response['property']) >= $limit )
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
                        
        // Network
        $request_data['network'] = array();
        $request_data['network']['network_id'] = (int)$export_settings['network_id'];
        
        // Branch
        $request_data['branch'] = array();
        $branch_code = $this->get_branch_code( $post_id );
        $request_data['branch']['branch_id'] = (int)$branch_code;
        $request_data['branch']['channel'] = ( $department == 'sales' ? 1 : 2 ); // 1 for sales, 2 for lettings
        $request_data['branch']['overseas'] = false;

        // Property
        $request_data['property'] = array();
        $request_data['property']['address'] = array();
        $request_data['property']['price_information'] = array();
        $request_data['property']['details'] = array();

        $request_data['property']['agent_ref'] = (string)$post_id;
        $request_data['property']['published'] = true;

        // Do property type lookup
        $property_type = $this->get_export_mapped_value($post_id, 'property_type');
        $request_data['property']['property_type'] = ( ( $property_type != '' ) ? (int)$property_type : 0 );

        //if ( !$overseas )
        //{
            $request_data['property']['status'] = (int)$this->get_export_mapped_value($post_id, 'property_status');
            $request_data['property']['student_property'] = FALSE;

            $address_taxonomies = array( 'property_state', 'property_city', 'property_area' );
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

            $request_data['property']['address']['house_name_number'] = '';
            $request_data['property']['address']['address_2'] = '';
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

                if ( !empty($number) ) { $request_data['property']['address']['house_name_number'] = $number; }
                if ( !empty($street) ) { $request_data['property']['address']['address_2'] = $street; }
            }

            $request_data['property']['address']['address_3'] = isset($address_fields[0]) ? $address_fields[0] : '';
            $request_data['property']['address']['address_4'] = isset($address_fields[1]) ? $address_fields[1] : '';

            $request_data['property']['address']['town'] = '';
            if ( isset($address_fields[1]) ) 
            { 
                $request_data['property']['address']['town'] = $address_fields[1]; 
            }
            elseif ( isset($address_fields[0]) ) 
            { 
                $request_data['property']['address']['town'] = $address_fields[0]; 
            }
            elseif ( isset($address_fields[2]) ) 
            { 
                $request_data['property']['address']['town'] = $address_fields[2]; 
            }

            $explode_postcode = explode(" ", trim(strtoupper(get_post_meta($post_id, 'fave_property_zip', true))));
            $request_data['property']['address']['postcode_1'] = strtoupper(trim($explode_postcode[0]));
            $request_data['property']['address']['postcode_2'] = ( (isset($explode_postcode[1])) ? strtoupper(trim($explode_postcode[1])) : '' );
            $request_data['property']['address']['display_address'] = get_the_title( $post_id );

            $price_qualifier = 0;
            $request_data['property']['price_information']['price_qualifier'] = (int)$price_qualifier;

            $request_data['property']['price_information']['deposit'] = null;
            $request_data['property']['price_information']['administration_fee'] = '';
            $rent_frequency = null;
            if ( $department == 'lettings' )
            {
                switch ( strtolower(get_post_meta( $post_id, 'fave_property_price_postfix', TRUE )) )
                {
                    case "pw":
                    case "per week":
                    case "weekly": { $rent_frequency = 52; break; }
                    case "pq":
                    case "per quarter":
                    case "quarterly": { $rent_frequency = 4; break; }
                    case "pa":
                    case "per annum":
                    case "per year":
                    case "yearly": { $rent_frequency = 1; break; }
                    case "pppw": { $rent_frequency = 52; break; }
                    default: { $rent_frequency = 12; }
                }
            }
            $request_data['property']['price_information']['rent_frequency'] = $rent_frequency;

            $request_data['property']['price_information']['auction'] = false;

            $request_data['property']['date_available'] = null;
            $request_data['property']['contract_months'] = null;
            $request_data['property']['minimum_term'] = null;
            $request_data['property']['let_type'] = null;
        /*}
        else
        {
            $request_data['property']['os_status'] = (int)$this->get_mapped_value($post_id, 'overseas_availability');

            $request_data['property']['address']['country_code'] = $country;
            $request_data['property']['address']['region'] = ( get_post_meta($post->ID, '_address_four', true) != '' ? get_post_meta($post->ID, '_address_four', true) : get_post_meta($post->ID, '_address_three', true) );
            $request_data['property']['address']['sub_region'] = get_post_meta($post->ID, '_address_three', true);
            $request_data['property']['address']['town_city'] = ( get_post_meta($post->ID, '_address_two', true) != '' ? get_post_meta($post->ID, '_address_two', true) : get_post_meta($post->ID, '_address_three', true) );

            $request_data['property']['price_information']['os_price_qualifier'] = (int)$this->get_mapped_value($post->ID, 'overseas_price_qualifier');
        }*/

        $request_data['property']['new_home'] = FALSE;
        $request_data['property']['create_date'] = get_the_time('d-m-Y H:i:s', $post_id);
        $request_data['property']['update_date'] = get_the_modified_time('d-m-Y H:i:s', $post_id);

        $fave_property_location = get_post_meta($post_id, 'fave_property_location', true);
        $explode_fave_property_location = explode(",", $fave_property_location);
        $lat = '';
        $lng = '';
        if ( count($explode_fave_property_location) >= 2 )
        {
            $lat = $explode_fave_property_location[0];
            $lng = $explode_fave_property_location[1];
        }
        $request_data['property']['address']['latitude'] = ( !empty($lat) ? (float)$lat : null );
        $request_data['property']['address']['longitude'] = ( !empty($lng) ? (float)$lng : null );

        $price = get_post_meta( $post_id, 'fave_property_price', TRUE );
        
        /*if ( 
            $overseas && 
            ( $department == 'sales' || ph_get_custom_department_based_on( $original_department ) == 'residential-sales' ) 
        )
        {
            // overseas. Make sure price is in right currency
            $gbp_countries = array( 'AE', 'AU', 'BG', 'BR', 'CZ', 'EG', 'HU', 'MA', 'NY', 'NZ', 'SG', 'TH', 'TR', 'ZA' );
            $gbp_countries = apply_filters( 'propertyhive_rtdf_gbp_countries' , $gbp_countries );

            if ( in_array($country, $gbp_countries) )
            {
                $price = get_post_meta( $post->ID, '_price_actual', TRUE );
            }
        }*/

        $request_data['property']['price_information']['price'] = (float)$price;
        
        $full_description = get_the_content( $post_id );
        $request_data['property']['details']['summary'] = ( get_the_excerpt( $post_id ) != '' ) ? substr(strip_tags(get_the_excerpt( $post_id )), 0, 999) : ( ( $full_description != '' ) ? substr(strip_tags($full_description), 0, 999) : '' );
        if (trim(strip_tags($full_description)) == '')
        {
            $full_description = get_the_excerpt( $post_id );
        }
        $request_data['property']['details']['description'] = $full_description;
        $request_data['property']['details']['bedrooms'] = ( (get_post_meta( $post_id, 'fave_property_bedrooms', TRUE ) != '') ? (int)get_post_meta( $post_id, 'fave_property_bedrooms', TRUE ) : 0 );
        $request_data['property']['details']['bathrooms'] = ( (get_post_meta( $post_id, 'fave_property_bathrooms', TRUE ) != '') ? (int)get_post_meta( $post_id, 'fave_property_bathrooms', TRUE ) : null );
        $request_data['property']['details']['reception_rooms'] = ( (get_post_meta( $post_id, 'fave_property_rooms', TRUE ) != '') ? (int)get_post_meta( $post_id, 'fave_property_rooms', TRUE ) : null );

        $features = array();
        $term_list = wp_get_post_terms($post_id, 'property_feature', array("fields" => "all"));
        if ( !is_wp_error($term_list) && is_array($term_list) && !empty($term_list) )
        {
            foreach ( $term_list as $term )
            {
                $features[] = $term->name;
            }
        }

        for ($i = 0; $i < 10; ++$i)
        {
            if ( isset($property_features[$i]) && trim($property_features[$i]) != '' )
            {
                $features[] = substr($property_features[$i], 0, 199);
            }
        }
        $request_data['property']['details']['features'] = $features;

        $request_data['property']['media'] = array();

        // IMAGES
        $i = 0;
        $attachment_ids = get_post_meta( $post_id, 'fave_property_images' );
        foreach ($attachment_ids as $attachment_id)
        {
            $image_size = 'large';
            $url = wp_get_attachment_image_src( $attachment_id, $image_size );
            if ($url !== FALSE)
            {
                $attachment_data = wp_prepare_attachment_for_js( $attachment_id );

                $media = array(
                    'media_type' => 1,
                    'media_url' => $url[0],
                    'caption' => ( ( isset($attachment_data['alt']) && is_string($attachment_data['alt']) && substr($attachment_data['alt'], 0, 50) !== FALSE ) ? substr($attachment_data['alt'], 0, 50) : '' ),
                    'sort_order' => $i,
                );

                $request_data['property']['media'][] = $media;

                ++$i;
            }
        }

        // FLOORPLANS
        $i = 0;
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
                        'media_type' => 2,
                        'media_url' => $url,
                        'caption' => substr($text, 0, 50),
                        'sort_order' => $i,
                    );

                    $request_data['property']['media'][] = $media;

                    ++$i;
                }
            }
        }

        // BROCHURES
        $i = 0;
        $attachment_ids = get_post_meta( $post_id, 'fave_attachments' );
        foreach ($attachment_ids as $attachment_id)
        {
            $url = wp_get_attachment_url( $attachment_id );
            if ($url !== FALSE)
            {
                $attachment_data = wp_prepare_attachment_for_js( $attachment_id );

                $media = array(
                    'media_type' => 3,
                    'media_url' => $url,
                    'caption' => ( ( isset($attachment_data['alt']) && is_string($attachment_data['alt']) && substr($attachment_data['alt'], 0, 50) !== FALSE ) ? substr($attachment_data['alt'], 0, 50) : '' ),
                    'sort_order' => $i,
                );

                $request_data['property']['media'][] = $media;

                ++$i;
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
            $i = 0;
            foreach ($virtual_tour_urls as $url)
            {
                if ( trim($url) != '' )
                {
                    $media = array(
                        'media_type' => 4,
                        'media_url' => $url,
                        'caption' => 'Virtual Tour',
                        'sort_order' => $i,
                    );

                    $request_data['property']['media'][] = $media;

                    ++$i;
                }
            }
        }

        $request_data = apply_filters( 'houzez_property_feed_export_property_data', $request_data, $post_id, $this->export_id );
        $request_data = apply_filters( 'houzez_property_feed_export_rtdf_property_data', $request_data, $post_id, $this->export_id );

        array_walk_recursive( $request_data, array($this, 'replace_bad_characters' ) );

        $do_request = true;
        if ( isset($export_settings['only_send_if_different']) && $export_settings['only_send_if_different'] == 'yes' )
        {
            $previous_hash = get_post_meta( $post_id, '_realtime_sha1_' . $this->export_id, TRUE );

            $request_data_to_check = $request_data;
            unset($request_data_to_check['property']['update_date']); // Remove update date as this is likely to differ each time and result in a different hash

            if ( $previous_hash == sha1(json_encode($request_data_to_check)) )
            {
                // Matches the data sent last time. Don't send again
                $do_request = false;
            }
        }

        if ( $do_request )
        {
            $request = $this->do_curl_request( $request_data, $export_settings['send_property_url'], $post_id );

            if ( $request !== FALSE )
            {
                $request_data_to_check = $request_data;
                unset($request_data_to_check['property']['update_date']); // Remove update date as this is likely to differ each time and result in a different hash

                // Request was successful
                // Save the SHA-1 hash so we know for next time whether to push it again or not
                update_post_meta( $post_id, '_realtime_sha1_' . $this->export_id, sha1(json_encode($request_data_to_check)) );
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

        $branch_codes = $export_settings['branch_codes'];

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

            // Network
            $request_data['network'] = array();
            $request_data['network']['network_id'] = (int)$export_settings['network_id'];
            
            // Branch
            $request_data['branch'] = array();
            $request_data['branch']['branch_id'] = (int)$branch_code;
            $request_data['branch']['channel'] = ( $department == 'sales' ? 1 : 2 ); // 1 for sales, 2 for lettings

            $response = $this->do_curl_request( $request_data, $export_settings['get_branch_properties_url'], $post_id, false );

            if ($response === FALSE) { return false; }

            $this->get_branch_properties_responses[$this->export_id . '_' . (int)$branch_code . '_' . $department] = $response;
        }

        $ok_to_remove = false;
        $agent_ref = $post_id;
        if (isset($response['property']) && is_array($response['property']) && !empty($response['property']))
        {
            foreach ($response['property'] as $property)
            {
                if ( $property['agent_ref'] == $post_id )
                {
                    // We found this property to be active on the site
                    $ok_to_remove = true;
                    break;
                }
            }
        }

        if (!$ok_to_remove) { return true; }

        $request_data = array();

        // Network
        $request_data['network'] = array();
        $request_data['network']['network_id'] = (int)$export_settings['network_id'];
        
        // Branch
        $request_data['branch'] = array();
        $request_data['branch']['branch_id'] = (int)$branch_code;
        $request_data['branch']['channel'] = ( $department == 'sales' ? 1 : 2 ); // 1 for sales, 2 for lettings
        
        // Property
        $request_data['property'] = array();
        $request_data['property']['agent_ref'] = (string)$agent_ref;
        $request_data['property']['removal_reason'] = 11; // Removed. Would be nice to set this to 'Sold' or 'Withdrawn' etc

        $request_data = apply_filters( 'houzez_property_feed_export_rtdf_remove_property_request_data', $request_data, $post_id, $this->export_id );

        $do_request = true;
        if ( isset($export_settings['only_send_if_different']) && $export_settings['only_send_if_different'] == 'yes' )
        {
            $previous_hash = get_post_meta( $post_id, '_realtime_sha1_' . $this->export_id, TRUE );

            $request_data_to_check = $request_data;

            if ( $previous_hash == sha1(json_encode($request_data_to_check)) )
            {
                // Matches the data sent last time. Don't send again
                $do_request = false;
            }
        }

        if ( $do_request )
        {
            $response = $this->do_curl_request( $request_data, $export_settings['remove_property_url'], $post_id );

            if ( $response !== FALSE )
            {
                $request_data_to_check = $request_data;

                // Request was successful
                // Save the SHA-1 hash so we know for next time whether to push it again or not
                update_post_meta( $post_id, '_realtime_sha1_' . $this->export_id, sha1(json_encode($request_data_to_check)) );
            }
        }
        else
        {
            $response = true;
        }

        return $response;
    }

    public function do_curl_request( $request_data, $api_url, $post_id, $log_success = true ) 
    {
        $export_settings = get_export_settings_from_id( $this->export_id );

        $request_data = json_encode($request_data);

        if ( apply_filters( 'houzez_property_feed_export_rtdf_perform_request', true ) !== true )
        {
            $this->log_error("Disabling request due to houzez_property_feed_export_rtdf_perform_request filter", '', $post_id);
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
        if ( $export_settings['certificate_file'] != '' )
        {
            curl_setopt($ch, CURLOPT_SSLCERT, $uploads_dir['basedir'] . '/houzez_property_feed_export/'. $export_settings['certificate_file']);
        }
        if ( $export_settings['certificate_password'] != '' )
        {
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $export_settings['certificate_password']);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
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

            if (isset($response['errors']) && !empty($response['errors']))
            {
                foreach ($response['errors'] as $error)
                {
                    $this->log_error("Error returned in response: " . $error['error_code'] . " - " . $error['error_description'], '', $post_id);
                }

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
}

}

new Houzez_Property_Feed_Format_RTDF();