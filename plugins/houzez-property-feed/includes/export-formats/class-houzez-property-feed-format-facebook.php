<?php
/**
 * Class for managing the export process of a Facebook XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Facebook extends Houzez_Property_Feed_Process {

	public function __construct( $instance_id = '', $export_id = '' )
	{
		$this->instance_id = $instance_id;
		$this->export_id = $export_id;
		$this->is_import = false;

		if ( $this->instance_id != '' && isset($_GET['custom_property_export_cron']) )
	    {
	    	$current_user = wp_get_current_user();

	    	$this->log("Executed manually by " . ( ( isset($current_user->display_name) ) ? $current_user->display_name : '' ) );
	    }
	}

	public function export()
	{
		global $wpdb, $post;

        $this->log("Starting export");

		$export_settings = get_export_settings_from_id( $this->export_id );

		$options = get_option( 'houzez_property_feed' , array() );
		$sales_statuses = ( isset($options['sales_statuses']) && is_array($options['sales_statuses']) && !empty($options['sales_statuses']) ) ? $options['sales_statuses'] : array();
		$lettings_statuses = ( isset($options['lettings_statuses']) && is_array($options['lettings_statuses']) && !empty($options['lettings_statuses']) ) ? $options['lettings_statuses'] : array();

        $wp_upload_dir = wp_upload_dir();
        if( $wp_upload_dir['error'] !== FALSE )
        {
            $this->log_error("Unable to create uploads folder. Please check permissions");
            return false;
        }
        else
        {
            $uploads_dir = $wp_upload_dir['basedir'] . '/houzez_property_feed_export/';

            if ( ! @file_exists($uploads_dir) )
            {
                if ( ! @mkdir($uploads_dir) )
                {
                    $this->log_error("Unable to create directory " . $uploads_dir);
                    return false;
                }
            }
            else
            {
                if ( ! @is_writeable($uploads_dir) )
                {
                    $this->log_error("Directory " . $uploads_dir . " isn't writeable");
                    return false;
                }
            }
        }

        // Get properties
        // Don't send if no properties
        $args = array(
            'post_type' => 'property',
            'post_status' => 'publish',
        );

        $limit = apply_filters( "houzez_property_feed_property_limit", 25 );
        $additional_message = '';
        if ( $limit !== false )
        {
            $additional_message = '. <a href="https://houzezpropertyfeed.com/#pricing" target="_blank">Upgrade to PRO</a> to import unlimited properties';
            $this->log( 'Exporting up to ' . $limit . ' properties' . $additional_message );
            $args['posts_per_page'] = $limit;
        }
        else
        {
            $args['nopaging'] = true;
        }

        $args = apply_filters( 'houzez_property_feed_export_property_args', $args, $this->export_id );
        $args = apply_filters( 'houzez_property_feed_export_facebook_property_args', $args, $this->export_id );

        $properties_query = new WP_Query( $args );
        $num_properties = $properties_query->found_posts;

        $xml = new SimpleXMLExtendedHpf("<?xml version=\"1.0\" encoding=\"utf-8\"?><listings></listings>");

        $xml->addChild('title');
        $xml->title = get_bloginfo('name') . ' Feed';

        if ( $properties_query->have_posts() )
        {
            $this->log( "Beginning to iterate through properties" );

            $i = 0;

            while ( $properties_query->have_posts() )
            {
                $properties_query->the_post();

                $this->log("Doing property", '', $post->ID);

                $listing_xml = $xml->addChild('listing');

                $listing_xml->addChild('home_listing_id', $post->ID);

                $listing_xml->addChild('name', get_the_title());

                $status = $this->get_export_mapped_value($post->ID, 'property_status');
                $listing_xml->addChild('availability', $status);

                $listing_xml->addCData('description', substr(strip_tags(get_the_excerpt()), 0, 5000));

                $address_xml = $listing_xml->addChild('address');
                $address_xml->addAttribute('format', 'simple');

                $component_i = 0;

                $component_xml = $address_xml->addCData('component', get_post_meta( $post->ID, 'fave_property_address', TRUE ));
                $component_xml->addAttribute('name', 'addr1');
                //++$component_i;

                $address_taxonomies = array( 'property_city', 'property_area', 'property_state' );
                $address_fields = array();
                foreach ( $address_taxonomies as $address_taxonomy )
                {
                    $terms = get_the_terms( $post->ID, $address_taxonomy );
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

                $city = '';
                if ( isset($address_fields[0]) && !empty($address_fields[0]) )
                {
                    $city = $address_fields[0];
                }
                elseif ( isset($address_fields[1]) && !empty($address_fields[1]) )
                {
                    $city = $address_fields[1];
                }
                elseif ( isset($address_fields[2]) && !empty($address_fields[2]) )
                {
                    $city = $address_fields[2];
                }
                $component_xml = $address_xml->addCData('component', $city);
                $component_xml->addAttribute('name', 'city');
                //$xml->listing[$i]->address->component[$component_i]->addCData($city);
                //++$component_i;

                $region = '';
                if ( isset($address_fields[2]) && !empty($address_fields[2]) )
                {
                    $region = $address_fields[2];
                }
                elseif ( isset($address_fields[0]) && !empty($address_fields[0]) )
                {
                    $region = $address_fields[0];
                }
                elseif ( isset($address_fields[1]) && !empty($address_fields[1]) )
                {
                    $region = $address_fields[1];
                }
                $component_xml = $address_xml->addCData('component', $region);
                $component_xml->addAttribute('name', 'region');
                //$xml->listing[$i]->address->component[$component_i]->addCData();
                //++$component_i;

                $country = 'United Kingdom';
                $component_xml = $address_xml->addCData('component', $country);
                $component_xml->addAttribute('name', 'country');
                ++$component_i;

                $component_xml = $address_xml->addCData('component', get_post_meta($post->ID, 'fave_property_zip', true));
                $component_xml->addAttribute('name', 'postal_code');
                //$xml->listing[$i]->address->component[$component_i]->addCData();
                //++$component_i;

                $fave_property_location = get_post_meta($post->ID, 'fave_property_location', true);
                $explode_fave_property_location = explode(",", $fave_property_location);
                $lat = '';
                $lng = '';
                if ( count($explode_fave_property_location) >= 2 )
                {
                    $lat = $explode_fave_property_location[0];
                    $lng = $explode_fave_property_location[1];
                }
                $listing_xml->addChild('latitude', $lat);
                $listing_xml->addChild('longitude', $lng);

                $currency = 'EUR';
                // check if multi-currency enabled
                if ( fave_option('multi_currency') == 1 )
                {
                    $default_multi_currency = fave_option('default_multi_currency');
                    if ( !empty( $default_multi_currency ) && strlen($default_multi_currency) == 3 )
                    {
                        $currency = strtoupper($default_multi_currency);
                    }

                    $property_currency = get_post_meta( $post->ID, 'fave_currency', true );
                    if ( !empty( $property_currency ) && strlen($property_currency) == 3 )
                    {
                        $currency = strtoupper($property_currency);
                    }
                }
                else
                {
                    // look at symbol set in settings
                    $symbol = fave_option('currency_symbol', '£');
                    switch ( $symbol )
                    {
                        case "£": { $currency = 'GBP'; break; }
                        case "$": { $currency = 'USD'; break; }
                    }
                }
                $price = get_post_meta( $post->ID, 'fave_property_price', true ) . ' ' . $currency;
                $listing_xml->addChild('price', $price);

                $attachment_ids = get_post_meta( $post->ID, 'fave_property_images' );

                if ( !empty($attachment_ids) )
                {
                    $attachment_ids = array_slice($attachment_ids, 0, 20);

                    foreach ( $attachment_ids as $attachment_id )
                    {
                        $image_xml = $listing_xml->addChild('image');
                        $image_url_xml = $image_xml->addChild('url', wp_get_attachment_url($attachment_id));
                    }
                }

                $department = 'sales';
                $status_terms = get_the_terms( $post->ID, 'property_status' );
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

                $listing_xml->addChild('listing_type', ( $department == 'lettings' ? 'for_rent_by_agent' : 'for_sale_by_agent' ) );

                $listing_xml->addChild('area_size', get_post_meta($post->ID, 'fave_property_size', true));
                $area_unit = 'sq_ft';
                if ( strpos( strtolower(get_post_meta($post->ID, 'fave_property_size_prefix', true)), 'm' ) !== false )
                {
                    $area_unit = 'sq_m';
                }
                $listing_xml->addChild('area_unit', $area_unit);

                $listing_xml->addChild('url', htmlentities(get_permalink()));

                $listing_xml->addChild('num_beds', get_post_meta($post->ID, 'fave_property_bedrooms', true));
                $listing_xml->addChild('num_baths', get_post_meta($post->ID, 'fave_property_bathrooms', true));
                $listing_xml->addChild('num_rooms', get_post_meta($post->ID, 'fave_property_rooms', true));

                $property_type = $this->get_export_mapped_value($post->ID, 'property_type');
                $listing_xml->addChild('property_type', $property_type);

                //$furnished = $this->get_mapped_value($post->ID, 'furnished');
                //$listing_xml->addChild('furnish_type', $furnished);

                //$parking = $this->get_mapped_value($post->ID, 'parking');
                //$listing_xml->addChild('parking_type', $parking);

                $property_xml = apply_filters( 'houzez_property_feed_export_property_data', $property_xml, $post->ID, $this->export_id );
                $property_xml = apply_filters( 'houzez_property_feed_export_facebook_property_data', $property_xml, $post->ID, $this->export_id );
                $this->log("Property written to Facebook XML file", '', $post->ID);

                ++$i;
            }
        }

        $xml = $xml->asXML();

        // Write XML string to file
        $handle = fopen($uploads_dir . $this->export_id . '.xml', 'w+');
        fwrite($handle, $xml);
        fclose($handle);

        $this->log('XML updated: <a href="' . $wp_upload_dir['baseurl'] . '/houzez_property_feed_export/' . $this->export_id . '.xml" target="_blank">View generated XML</a>', '', $post->ID);

        return true;
	}

}

}