<?php
/**
 * Class for managing the export process of a Thribee XML file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Thribee extends Houzez_Property_Feed_Process {

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
        $args = apply_filters( 'houzez_property_feed_export_thribee_property_args', $args, $this->export_id );

        $properties_query = new WP_Query( $args );
        $num_properties = $properties_query->found_posts;

        $xml = new SimpleXMLExtendedHpf("<?xml version=\"1.0\" encoding=\"utf-8\"?><trovit></trovit>");

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

                $property_xml = $xml->addChild('ad');

                $property_xml->addChild('id', $post->ID);

                $property_xml->addCData('url', get_permalink());

                $property_xml->addCData('title', get_the_title());

                $property_xml->addChild('type', ( $department == 'sales' ? 'For Sale' : 'For Rent' ));

                $property_xml->addCData('agency', get_bloginfo('name'));

                $content = get_the_content();
                if ( trim(strip_tags($content)) == '' )
                {
                    $content = $post->post_excerpt;
                }
                $property_xml->addCData('content', $content);

                $currency = '€';
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
                    $currency = houzez_option('currency_symbol');
                } 
                $property_xml->addCData('price', $currency . get_post_meta( $post->ID, 'fave_property_price', true ));

                $property_xml->addCData('property_type', $this->get_export_mapped_value($post->ID, 'property_type'));

                $floor_area = get_post_meta( $post->ID, 'fave_property_size', true );
                $floor_area_units = 'feet';
                if ( get_post_meta( $post->ID, 'fave_property_size_prefix', true ) != '' )
                {
                    if ( strpos(strtolower(get_post_meta( $post->ID, 'fave_property_size_prefix', true )), 'm') !== false )
                    {
                        $floor_area_units = 'meters';
                    }
                    if ( strpos(strtolower(get_post_meta( $post->ID, 'fave_property_size_prefix', true )), 'acre') !== false )
                    {
                        $floor_area_units = 'acres';
                    }
                    if ( strpos(strtolower(get_post_meta( $post->ID, 'fave_property_size_prefix', true )), 'hectare') !== false )
                    {
                        $floor_area_units = 'hectares';
                    }
                }
                $floor_area_xml = $property_xml->addCData('floor_area', $floor_area);
                $floor_area_xml->addAttribute('unit', $floor_area_units);

                $property_xml->addCData('rooms', get_post_meta($post->ID, 'fave_property_bedrooms', true));

                $property_xml->addCData('bathrooms', get_post_meta($post->ID, 'fave_property_bathrooms', true));

                $property_xml->addCData('address', get_post_meta($post->ID, 'fave_property_address', true));

                $address_taxonomies = array( 'property_area', 'property_city', 'property_state',  );
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
                if ( isset($address_fields[1]) )
                {
                    $city = $address_fields[1];
                }
                elseif ( isset($address_fields[0]) )
                {
                    $city = $address_fields[0];
                }
                $property_xml->addCData('city', $city);

                $city_area = '';
                if ( isset($address_fields[0]) )
                {
                    $city_area = $address_fields[0];
                }
                $property_xml->addCData('city_area', $city_area);

                $property_xml->addCData('postcode', get_post_meta($post->ID, 'fave_property_zip', true));

                $region = '';
                if ( isset($address_fields[2]) )
                {
                    $region = $address_fields[2];
                }
                elseif ( isset($address_fields[1]) )
                {
                    $region = $address_fields[1];
                }
                elseif ( isset($address_fields[0]) )
                {
                    $region = $address_fields[0];
                }
                $property_xml->addCData('region', $region);

                $fave_property_location = get_post_meta($post->ID, 'fave_property_location', true);
                $explode_fave_property_location = explode(",", $fave_property_location);
                $lat = '';
                $lng = '';
                if ( count($explode_fave_property_location) >= 2 )
                {
                    $lat = $explode_fave_property_location[0];
                    $lng = $explode_fave_property_location[1];
                }
                $property_xml->addCData('latitude', $lat);
                $property_xml->addCData('longitude', $lng);

                $attachment_ids = get_post_meta( $post->ID, 'fave_property_images' );

                if ( !empty($attachment_ids) )
                {
                    $pictures_xml = $property_xml->addChild('pictures');

                    foreach ( $attachment_ids as $attachment_id )
                    {
                        if ( !wp_attachment_is_image($attachment_id) )
                        {
                            continue;
                        }

                        $picture_xml = $pictures_xml->addChild('picture');
                        $picture_xml->addCData('picture_url', wp_get_attachment_url($attachment_id));
                        $picture_xml->addCData('picture_title', get_the_title($attachment_id));
                    }
                }

                $virtual_tour = '';
                if ( 
                    get_post_meta( $post->ID, 'fave_video_url', true ) != '' &&
                    (
                        substr( strtolower(get_post_meta( $post->ID, 'fave_video_url', true )), 0, 2 ) == '//' || 
                        substr( strtolower(get_post_meta( $post->ID, 'fave_video_url', true )), 0, 4 ) == 'http'
                    )
                )
                {
                    $virtual_tour = get_post_meta( $post->ID, 'fave_video_url', true );
                }
                elseif ( get_post_meta( $post->ID, 'fave_virtual_tour', true ) != '' )
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
                            $virtual_tour = $url;
                        }
                    }
                }

                $property_xml->addCData('virtual_tour', $virtual_tour);

                $property_xml->addCData('date', get_the_date('d/m/Y'));
                $property_xml->addCData('time', get_the_date('H:i'));

                $property_xml->addCData('plot_area', get_post_meta( $post->ID, 'fave_property_land', true ));
                $property_xml->addCData('year', get_post_meta( $post->ID, 'fave_property_year', true ));

                $property_xml = apply_filters( 'houzez_property_feed_export_property_data', $property_xml, $post->ID, $this->export_id );
                $property_xml = apply_filters( 'houzez_property_feed_export_thribee_property_data', $property_xml, $post->ID, $this->export_id );

                $this->log("Property written to Thribee XML file", '', $post->ID);
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