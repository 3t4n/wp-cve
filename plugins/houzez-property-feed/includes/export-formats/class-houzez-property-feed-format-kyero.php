<?php
/**
 * Class for managing the export process of a Kyero XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Kyero extends Houzez_Property_Feed_Process {

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
        $args = apply_filters( 'houzez_property_feed_export_kyero_property_args', $args, $this->export_id );

        $properties_query = new WP_Query( $args );
        $num_properties = $properties_query->found_posts;

        $xml = new SimpleXMLExtendedHpf("<?xml version=\"1.0\" encoding=\"utf-8\"?><root></root>");

        $kyero_xml = $xml->addChild('kyero');
        $kyero_xml->addChild('feed_version');
        $kyero_xml->feed_version = 3;

        if ( $properties_query->have_posts() )
        {
            $this->log( "Beginning to iterate through properties" );

            while ( $properties_query->have_posts() )
            {
                $properties_query->the_post();

                $this->log("Doing property", '', $post->ID);

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

                $property_xml = $xml->addChild('property');

                $property_xml->addChild('id', $post->ID);

                $property_xml->addChild('date', get_the_modified_date( 'Y-m-d H:i:s', $post->ID ));

                $property_xml->addChild('ref', get_post_meta( $post->ID, 'fave_property_id', true ));

                $property_xml->addChild('price', get_post_meta( $post->ID, 'fave_property_price', true ));

                $currency = 'EUR';
                // check if multi-currency enabled
                if ( houzez_option('multi_currency') == '1' )
                {
                    $default_multi_currency = houzez_option('default_multi_currency');
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
                    $symbol = houzez_option('currency_symbol');
                    switch ( $symbol )
                    {
                        case "£": { $currency = 'GBP'; break; }
                        case "$": { $currency = 'USD'; break; }
                    }
                }

                $property_xml->addChild('currency', $currency);

                $price_freq = 'sale';
                if ( $department == 'lettings' )
                {
                    $price_freq = 'month';
                    $price_postfix = strtolower(get_post_meta( $post->ID, 'fave_property_price_postfix', true ));
                    if ( strpos($price_postfix, 'pw') !== FALSE || strpos($price_postfix, 'week') !== FALSE )
                    {
                        $price_freq = 'week';
                    }
                }
                $property_xml->addChild('price_freq', $price_freq);

                $property_type = $this->get_export_mapped_value($post->ID, 'property_type');
                $property_xml->addChild('type', $property_type);

                $address_fields = array();

                $address_taxonomies = array( 'property_state', 'property_city', 'property_area' );
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

                $province = isset($address_fields[0]) ? $address_fields[0] : '';
                $town = isset($address_fields[1]) ? $address_fields[1] : '';

                $property_xml->addChild('town', $town);
                $property_xml->addChild('province', $province);

                $country = 'Spain';
                $terms = get_the_terms( $post->ID, 'property_country' );
                $term_ids_to_use = array();
                if ( !is_wp_error($terms) && !empty($terms) )
                {
                    foreach ( $terms as $term )
                    {
                        $country = $term->name;
                        break;
                    }
                }
                $property_xml->addChild('country', $country);

                $fave_property_location = get_post_meta($post->ID, 'fave_property_location', true);
                $explode_fave_property_location = explode(",", $fave_property_location);
                $lat = '';
                $lng = '';
                if ( count($explode_fave_property_location) >= 2 )
                {
                    $lat = $explode_fave_property_location[0];
                    $lng = $explode_fave_property_location[1];
                }
                if ( !empty($lat) && !empty($lng) )
                {
                    $location_xml = $property_xml->addChild('location');
                    $location_xml->addChild('latitude', $lat);
                    $location_xml->addChild('longitude', $lng);
                }

                $property_xml->addChild('beds', get_post_meta($post->ID, 'fave_property_bedrooms', true));

                if ( get_post_meta($post->ID, 'fave_property_bathrooms', true) != '' )
                {
                    $property_xml->addChild('baths', get_post_meta($post->ID, 'fave_property_bathrooms', true));
                }

                $surface_area_xml = $property_xml->addChild('surface_area');
                if ( !empty(get_post_meta($post->ID, 'fave_property_size', true)) )
                {
                    $size = get_post_meta($post->ID, 'fave_property_size', true);
                    if ( strpos(strtolower(get_post_meta($post->ID, 'fave_property_size_prefix', true)), 'ft') !== FALSE )
                    {
                        $size = get_post_meta($post->ID, 'fave_property_size', true) / 10.764; // convert from sqft to sqm
                    }
                    if ( strpos(strtolower(get_post_meta($post->ID, 'fave_property_size_prefix', true)), 'acre') !== FALSE )
                    {
                        $size = get_post_meta($post->ID, 'fave_property_size', true) * 4047; // convert from acre to sqm
                    }
                    if ( strpos(strtolower(get_post_meta($post->ID, 'fave_property_size_prefix', true)), 'hectare') !== FALSE )
                    {
                        $size = get_post_meta($post->ID, 'fave_property_size', true) * 10000; // convert from hectare to sqm
                    }
                    $surface_area_xml->addChild('built', $size);
                }
                if ( !empty(get_post_meta($post->ID, 'fave_property_land', true)) )
                {
                    $size = get_post_meta($post->ID, 'fave_property_land', true);
                    if ( strpos(strtolower(get_post_meta($post->ID, 'fave_property_land_postfix', true)), 'ft') !== FALSE )
                    {
                        $size = get_post_meta($post->ID, 'fave_property_land', true) / 10.764; // convert from sqft to sqm
                    }
                    if ( strpos(strtolower(get_post_meta($post->ID, 'fave_property_land_postfix', true)), 'acre') !== FALSE )
                    {
                        $size = get_post_meta($post->ID, 'fave_property_land', true) * 4047; // convert from acre to sqm
                    }
                    if ( strpos(strtolower(get_post_meta($post->ID, 'fave_property_land_postfix', true)), 'hectare') !== FALSE )
                    {
                        $size = get_post_meta($post->ID, 'fave_property_land', true) * 10000; // convert from hectare to sqm
                    }
                    $surface_area_xml->addChild('plot', $size);
                }

                $url_xml = $property_xml->addChild('url');
                $url_xml->addChild('en', get_permalink($post->ID));

                $videos = array();
                $virtual_tours = array();
                if ( 
                    get_post_meta( $post->ID, 'fave_video_url', true ) != '' &&
                    (
                        substr( strtolower(get_post_meta( $post->ID, 'fave_video_url', true )), 0, 2 ) == '//' || 
                        substr( strtolower(get_post_meta( $post->ID, 'fave_video_url', true )), 0, 4 ) == 'http'
                    )
                )
                {
                    if ( 
                        strpos( get_post_meta( $post->ID, 'fave_video_url', true ), 'youtu' ) !== false
                        ||
                        strpos( get_post_meta( $post->ID, 'fave_video_url', true ), 'vimeo' ) !== false
                    )
                    {
                        $videos[] = get_post_meta( $post->ID, 'fave_video_url', true );
                    }
                    else
                    {
                        $virtual_tours[] = get_post_meta( $post->ID, 'fave_video_url', true );
                    }
                }

                if ( get_post_meta( $post->ID, 'fave_virtual_tour', true ) != '' )
                {
                    preg_match('/src="([^"]+)"/', get_post_meta( $post->ID, 'fave_virtual_tour', true ), $match);
                    if ( isset($match[1]) )
                    {
                        $url = $match[1];

                        if ( 
                            substr( strtolower($url), 0, 2 ) == '//' || 
                            substr( strtolower($url), 0, 4 ) == 'http'
                        )
                        {
                            $virtual_tours[] = $url;
                        }
                    }
                }

                if ( !empty($videos) )
                {
                    foreach ($videos as $video)
                    {
                        $property_xml->addChild('video_url', $video);
                        break;
                    }
                }

                if ( !empty($virtual_tours) )
                {
                    foreach ($virtual_tours as $virtual_tour)
                    {
                        $property_xml->addChild('virtual_tour_url', $virtual_tour);
                        break;
                    }
                }

                $description = get_the_content();
                if ( trim(strip_tags($description)) == '' )
                {
                    $description = $post->post_excerpt;
                }
                $desc_xml = $property_xml->addChild('desc');
                $desc_xml->addChild('en', htmlspecialchars($description, ENT_QUOTES | ENT_XML1, 'UTF-8'));

                $term_list = wp_get_post_terms($post->ID, 'property_feature', array("fields" => "all"));
                if ( !is_wp_error($term_list) && is_array($term_list) && !empty($term_list) )
                {
                    $features_xml = $property_xml->addChild('features');
                    foreach ( $term_list as $term )
                    {
                        $features_xml->addChild('feature', htmlspecialchars($term->name, ENT_QUOTES | ENT_XML1, 'UTF-8'));
                    }
                }

                $attachment_ids = get_post_meta( $post->ID, 'fave_property_images' );

                if ( !empty($attachment_ids) )
                {
                    $attachment_ids = array_slice($attachment_ids, 0, 50);

                    $images_xml = $property_xml->addChild('images');

                    $j = 0;
                    foreach ( $attachment_ids as $attachment_id )
                    {
                        if ( !wp_attachment_is_image($attachment_id) )
                        {
                            continue;
                        }

                        $image_xml = $images_xml->addChild('image');
                        $image_xml->addAttribute('id', $j+1);
                        $image_xml->addChild('url', wp_get_attachment_url($attachment_id));

                        ++$j;
                    }
                }

                $property_xml = apply_filters( 'houzez_property_feed_export_property_data', $property_xml, $post->ID, $this->export_id );
                $property_xml = apply_filters( 'houzez_property_feed_export_kyero_property_data', $property_xml, $post->ID, $this->export_id );

                $this->log("Property written to Kyero XML file", '', $post->ID);
            }
        }

        $xml = $xml->asXML();

        // Write XML string to file
        $filename = $this->export_id . '.xml';
        $filename = apply_filters( 'houzez_property_feed_export_kyero_url_filename', $filename, $this->export_id );
        $handle = fopen($uploads_dir . $filename, 'w+');
        fwrite($handle, $xml);
        fclose($handle);

        $this->log('XML updated: <a href="' . $wp_upload_dir['baseurl'] . '/houzez_property_feed_export/' . $this->export_id . '.xml" target="_blank">View generated XML</a>', '', $post->ID);

        return true;
	}

}

}