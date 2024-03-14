<?php
/**
 * Class for managing the export process of a BLM file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Blm extends Houzez_Property_Feed_Process {

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

		$num_images = apply_filters( 'houzez_property_feed_blm_export_images_count', 50 );
	    $num_floorplans = apply_filters( 'houzez_property_feed_blm_export_floorplans_count', 6 );
	    $num_brochures = apply_filters( 'houzez_property_feed_blm_export_brochures_count', 6 );
	    $num_virtual_tours = apply_filters( 'houzez_property_feed_blm_export_virtual_tours_count', 1 );

	    // Test FTP
        // Don't continue it we can't connect
        if ( !isset($_GET['preview']) )
        {
            $this->log("Testing FTP details are valid");

            if ( function_exists('ftp_connect') )
            {
                $ftp_conn = ftp_connect( $export_settings['ftp_host'] );
                if ($ftp_conn !== FALSE)
                {
                    $ftp_login = ftp_login($ftp_conn, $export_settings['ftp_user'], $export_settings['ftp_pass']);
                    if ($ftp_login !== FALSE)
                    {
                        if ( isset($export_settings['ftp_dir']) && !empty($export_settings['ftp_dir']) )
                        {
                            if ( ftp_chdir($ftp_conn, $export_settings['ftp_dir']) )
                            {

                            }
                            else
                            {
                                $this->log_error("Error encountered whilst trying to change to FTP directory: " . $export_settings['ftp_dir']);
                                ftp_close($ftp_conn);
                                return false;
                            }
                        }
                    }
                    else
                    {
                        $this->log_error("Can't login to FTP host using login details " . $export_settings['ftp_user'] . ' and ' . $export_settings['ftp_pass']);
                        ftp_close($ftp_conn);
                        return false;
                    }
                    ftp_close($ftp_conn);
                }
                else
                {
                    $this->log_error("Can't connect to FTP host provided: " . $export_settings['ftp_host']);
                    return false;
                }
            }
            else
            {
                // FTP functionality not enabled
                $this->log_error("FTP functionality not enabled in PHP");
                return false;
            }

            $this->log("FTP details valid");
        }
    
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

        $meta_query = array();
        $tax_query = array();

        $default_non_overseas_countries = array('', 'UK', 'United Kingdom', 'England', 'Scotland', 'Wales', 'Ireland', 'GB', 'Britain', 'Great Britain');
        $countries_array = apply_filters( 'houzez_property_feed_export_blm_non_overseas_countries_array', $default_non_overseas_countries, $this->export_id );

        if ( !is_array($countries_array) || empty($countries_array) )
        {
            $countries_array = $default_non_overseas_countries;
        }

        $houzez_tax_settings = get_option('houzez_tax_settings', array() );

        if ( !isset($houzez_tax_settings['property_country']) || ( isset($houzez_tax_settings['property_country']) && $houzez_tax_settings['property_country'] != 'disabled' ) )
        {
            // Country taxonomy enabled. Look for overseas country or not
            if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
            {
                $tax_query[] = array(
                    'taxonomy' => 'property_country',
                    'field' => 'name',
                    'terms' => $countries_array,
                    'operator' => 'NOT IN'
                );
            }
            else
            {
                $tax_query[] = array(
                    'taxonomy' => 'property_country',
                    'field' => 'name',
                    'terms' => $countries_array,
                    'operator' => 'IN'
                );
            }
        }

        $args['meta_query'] = $meta_query;
        $args['tax_query'] = $tax_query;

        $args = apply_filters( 'houzez_property_feed_export_property_args', $args, $this->export_id );
        $args = apply_filters( 'houzez_property_feed_export_blm_property_args', $args, $this->export_id );

        $properties_query = new WP_Query( $args );
        $num_properties = $properties_query->found_posts;

        $files_to_zip = array();
        $files_to_ftp = array();

        $local_blm_file = $this->export_id . '_' . date("YmdHis") . '.blm';

        $remote_blm_file = date("YmdHis") . '.blm';

        $local_zip_file = $this->export_id . '_' . date("YmdHis") . '.zip';

        $remote_zip_file = date("YmdHis") . '.zip';

        $blm_filename = $wp_upload_dir['basedir'] . '/houzez_property_feed_export/' . $local_blm_file;
        $handle = fopen($blm_filename, 'w+');

        $zip_filename = $wp_upload_dir['basedir'] . '/houzez_property_feed_export/' . $local_zip_file;

        if ( isset($export_settings['compressed']) && $export_settings['compressed'] == 'yes' )
        {
            $files_to_zip[] = array(
                'local' => $blm_filename,
                'remote' => $remote_blm_file
            );
            $files_to_ftp[] = array(
                'local' => $zip_filename,
                'remote' => $remote_zip_file,
                'mode' => FTP_BINARY
            );
        }
        else
        {
            $files_to_ftp[] = array(
                'local' => $blm_filename,
                'remote' => $remote_blm_file,
                'mode' => FTP_ASCII
            );
        }

        $this->log("Writing header to BLM");
        $header_row = "#HEADER#\n";
        $header_row .= "Version : 3" . ( ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' ) ? 'i' : '' ) . "\n";
        $header_row .= "EOF : '^'\n";
        $header_row .= "EOR : '~'\n";

        $num_properties = 0;
        if ( $properties_query->have_posts() )
        {
            while ( $properties_query->have_posts() )
            {
                $properties_query->the_post();

                ++$num_properties;
            }
        }

        $header_row .= "Property Count : " . $num_properties . "\n";
        $header_row .= "Generated Date : " . date("d") . "-" . strtoupper(date("M")) . "-" . date("Y") . " " . date("H:i") . "\n\n";

        $this->log("Writing definition row to BLM");
        $header_row .= "#DEFINITION#\n";
        $header_row_values = array('AGENT_REF');
        if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
        {
            $header_row_values = array_merge(
                $header_row_values,
                array('HOUSE_NAME_NUMBER', 'STREET_NAME', 'OS_TOWN_CITY', 'OS_REGION', 'ZIPCODE', 'COUNTRY_CODE', 'EXACT_LATITUDE', 'EXACT_LONGITUDE')
            );
        }
        else
        {
            $header_row_values = array_merge(
                $header_row_values,
                array('ADDRESS_1', 'ADDRESS_2', 'ADDRESS_3', 'TOWN', 'POSTCODE1', 'POSTCODE2')
            );
        }

        $header_row_values = array_merge(
            $header_row_values,
            array(
                'FEATURE1',
                'FEATURE2',
                'FEATURE3',
                'FEATURE4',
                'FEATURE5',
                'FEATURE6',
                'FEATURE7',
                'FEATURE8',
                'FEATURE9',
                'FEATURE10',
                'SUMMARY',
                'DESCRIPTION',
                'BRANCH_ID',
                'STATUS_ID',
                'BEDROOMS'
            )
        );

        if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
        {

        }
        else
        {
            $header_row_values[] = 'BATHROOMS';
            $header_row_values[] = 'LIVING_ROOMS';
        }
        $header_row_values = array_merge(
            $header_row_values,
            array('PRICE', 'PRICE_QUALIFIER', 'PROP_SUB_ID', 'CREATE_DATE', 'UPDATE_DATE', 'DISPLAY_ADDRESS', 'PUBLISHED_FLAG')
        );
        if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
        {

        }
        else
        {
            $header_row_values = array_merge(
                $header_row_values,
                array('LET_DATE_AVAILABLE', 'LET_BOND', 'ADMINISTRATION_FEE', 'LET_TYPE_ID', 'LET_FURN_ID', 'LET_RENT_FREQUENCY', 'TENURE_TYPE_ID', 'COUNCIL_TAX_BAND', 'SHARED_OWNERSHIP', 'SHARED_OWNERSHIP_PERCENTAGE', 'ANNUAL_GROUND_RENT', 'GROUND_RENT_REVIEW_PERIOD_YEARS', 'ANNUAL_SERVICE_CHARGE', 'TENURE_UNEXPIRED_YEARS')
            );
        }
        $header_row_values[] = 'TRANS_TYPE_ID';
        for ($i = 0; $i < $num_images; ++$i)
        {
            $j = str_pad($i, 2, '0', STR_PAD_LEFT);
            $header_row_values[] = 'MEDIA_IMAGE_' . $j;
            $header_row_values[] = 'MEDIA_IMAGE_TEXT_' . $j;
        }
        $header_row_values[] = 'MEDIA_IMAGE_60';
        $header_row_values[] = 'MEDIA_IMAGE_TEXT_60';
        for ($i = 0; $i < $num_floorplans; ++$i)
        {
            $j = str_pad($i, 2, '0', STR_PAD_LEFT);
            $header_row_values[] = 'MEDIA_FLOOR_PLAN_' . $j;
            $header_row_values[] = 'MEDIA_FLOOR_PLAN_TEXT_' . $j;
        }
        for ($i = 0; $i < $num_brochures; ++$i)
        {
            $j = str_pad($i, 2, '0', STR_PAD_LEFT);
            $header_row_values[] = 'MEDIA_DOCUMENT_' . $j;
            $header_row_values[] = 'MEDIA_DOCUMENT_TEXT_' . $j;
        }
        for ($i = 0; $i < $num_virtual_tours; ++$i)
        {
            $j = str_pad($i, 2, '0', STR_PAD_LEFT);
            $header_row_values[] = 'MEDIA_VIRTUAL_TOUR_' . $j;
            $header_row_values[] = 'MEDIA_VIRTUAL_TOUR_TEXT_' . $j;
        }

        $header_row_values = apply_filters( 'houzez_property_feed_export_blm_header_values', $header_row_values, $this->export_id );

        $header_row .= implode('^', $header_row_values) . "^~\n\n";

        $header_row .= "#DATA#\n";

        fwrite($handle, $header_row);

        $properties_added = 0;

        if ( $properties_query->have_posts() )
        {
            $this->log( "Beginning to iterate through properties" );

            while ( $properties_query->have_posts() )
            {
                $properties_query->the_post();

                $this->log("Doing property", '', $post->ID);

                // Get date when this property was last sent to this portal
                $property_last_sent = '2000-01-01 00:00:00';
                $row = $wpdb->get_row( "
                    SELECT 
                        end_date
                    FROM " . $wpdb->prefix . "houzez_property_feed_export_logs_instance
                    INNER JOIN 
                        " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log ON  " . $wpdb->prefix . "houzez_property_feed_export_logs_instance.id = " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log.instance_id
                    WHERE 
                        export_id = '" . $this->export_id . "'
                        AND
                        post_id = '" . $post->ID . "'
                        AND
                        end_date != '0000-00-00 00:00:00'
                    ORDER BY log_date DESC LIMIT 1
                ", ARRAY_A);
                if ( null !== $row )
                {
                    $property_last_sent = $row['end_date'];
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

                $branch_code = '';
                $agent_display_option = get_post_meta( $post->ID, 'fave_agent_display_option', true );

                switch ( $agent_display_option )
                {
                	case "author_info":
                	{
                		$thing_id = $post->post_author;
                		$branch_code = isset($export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department]) ?
                			$export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department] :
                			'';
                		break;
                	}
                	case "agent_info":
                	{
                		$thing_id = get_post_meta( $post->ID, 'fave_agents', true );
                        $branch_code = isset($export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department]) ?
                			$export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department] :
                			'';
                		break;
                	}
                	case "agency_info":
                	{
                		$thing_id = get_post_meta( $post->ID, 'fave_property_agency', true );
                		$branch_code = isset($export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department]) ?
                			$export_settings['branch_code_' . $agent_display_option . '_' . $thing_id . '_' . $department] :
                			'';
                		break;
                	}
                }

                if ( empty($branch_code) )
                {
                	$this->log_error("No branch code found. Not including property. Ensure you have departments set under 'Export Properties > Settings > Departments' and branch codes entered accordingly in the export settings", '', $post->ID);
                	continue;
                }

                $agent_ref = $branch_code. "_" . $post->ID;

                $property_row_values = array();

                $property_row_values['AGENT_REF'] = $agent_ref;

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

                if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
                {
                    $number = '';
                    $street = '';

                    if ( get_post_meta( $post->ID, 'fave_property_address', TRUE ) != '' ) 
                    {
                        $property_address = get_post_meta( $post->ID, 'fave_property_address', TRUE );
                        $property_address = str_replace(',', ' ', $property_address);

                        $explode_address = explode(" ", $property_address);
                        if ( preg_match('/\d/', $explode_address[0]) )
                        {
                            $number = $explode_address[0];
                            $street = str_replace($number, '', get_post_meta( $post->ID, 'fave_property_address', TRUE ));
                            $street = trim($street);
                            $street = trim($street, ',');
                            $street = trim($street);
                        }
                        else
                        {
                            $street = get_post_meta( $post->ID, 'fave_property_address', TRUE );
                        }
                    }

                    $property_row_values['HOUSE_NAME_NUMBER'] = $number;
                    $property_row_values['STREET_NAME'] = $street;

                    $town_city = '';
                    if ( isset($address_fields[1]) ) 
                    { 
                        $town_city = $address_fields[1]; 
                    }
                    elseif ( isset($address_fields[0]) ) 
                    { 
                        $town_city = $address_fields[0]; 
                    }
                    elseif ( isset($address_fields[2]) ) 
                    { 
                        $town_city = $address_fields[2]; 
                    }
                    $property_row_values['OS_TOWN_CITY'] = $town_city;

                    $region = '';
                    if ( isset($address_fields[0]) ) 
                    { 
                        $region = $address_fields[0]; 
                    }
                    elseif ( isset($address_fields[1]) ) 
                    { 
                        $region = $address_fields[1]; 
                    }
                    elseif ( isset($address_fields[2]) ) 
                    { 
                        $region = $address_fields[2]; 
                    }
                    $property_row_values['OS_REGION'] = $region;
                    $property_row_values['ZIPCODE'] = get_post_meta($post->ID, 'fave_property_zip', true);

                    $country_code = '';
                    $terms = get_the_terms( $post->ID, 'property_country' );
                    $term_ids_to_use = array();
                    if ( !is_wp_error($terms) && !empty($terms) )
                    {
                        foreach ( $terms as $term )
                        {
                            $temp_country_code = get_houzez_property_feed_country_by_name($term->name);
                            if ( $temp_country_code !== FALSE )
                            {
                                $country_code = $temp_country_code;
                                break;
                            }
                        }
                    }
                    $property_row_values['COUNTRY_CODE'] = $country_code;

                    $fave_property_location = get_post_meta($post->ID, 'fave_property_location', true);
                    $explode_fave_property_location = explode(",", $fave_property_location);
                    $lat = '';
                    $lng = '';
                    if ( count($explode_fave_property_location) >= 2 )
                    {
                        $lat = $explode_fave_property_location[0];
                        $lng = $explode_fave_property_location[1];
                    }
                    $property_row_values['EXACT_LATITUDE'] = $lat;
                    $property_row_values['EXACT_LONGITUDE'] = $lng;
                }
                else
                {
                    $property_row_values['ADDRESS_1'] = get_post_meta( $post->ID, 'fave_property_address', TRUE );
                    $property_row_values['ADDRESS_2'] = '';
                    $property_row_values['ADDRESS_3'] = isset($address_fields[0]) ? $address_fields[0] : '';
                    $property_row_values['TOWN'] = isset($address_fields[1]) ? $address_fields[1] : '';
                    $explode_postcode = explode(" ", get_post_meta($post->ID, 'fave_property_zip', true));
                    $property_row_values['POSTCODE1'] = strtoupper(trim($explode_postcode[0]));
                    $property_row_values['POSTCODE2'] = ( (isset($explode_postcode[1])) ? strtoupper(trim($explode_postcode[1])) : '' );
                }

                // Features
                $features = array();
                $term_list = wp_get_post_terms($post->ID, 'property_feature', array("fields" => "all"));
                if ( !is_wp_error($term_list) && is_array($term_list) && !empty($term_list) )
                {
                    foreach ( $term_list as $term )
                    {
                        $features[] = $term->name;
                    }
                }

                for ($i = 0; $i < 10; ++$i)
                {   
                    $feature = '';

                    if ( isset($features[$i]) )
                    {
                        $feature = $features[$i];
                    }

                    $property_row_values['FEATURE' . ( $i + 1 )] = $feature;
                }

                // Descriptions
                $property_row_values['SUMMARY'] = strip_tags(get_the_excerpt());

                $full_description = get_the_content();
                if ( trim(strip_tags($full_description)) == '' )
                {
                    $full_description = nl2br(get_the_excerpt());
                }
                $property_row_values['DESCRIPTION'] = $full_description;

                // Miscellaneous
                $property_row_values['BRANCH_ID'] = $branch_code;

                $availability = $this->get_export_mapped_value($post->ID, 'property_status');
                /*if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
                {
                    $availability = $this->get_mapped_value($post->ID, 'overseas_availability');
                }
                else
                {
                    $availability = $this->get_mapped_value($post->ID, 'availability');
                }*/
                $property_row_values['STATUS_ID'] = ( ( $availability != '' ) ? $availability : '0' );

                $property_row_values['BEDROOMS'] = ( ( get_post_meta( $post->ID, 'fave_property_bedrooms', TRUE ) != '' ) ? get_post_meta( $post->ID, 'fave_property_bedrooms', TRUE ) : '0' );

                if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
                {

                }
                else
                {
                    $property_row_values['BATHROOMS'] = get_post_meta( $post->ID, 'fave_property_bathrooms', TRUE );
                    $property_row_values['LIVING_ROOMS'] = get_post_meta( $post->ID, 'fave_property_rooms', TRUE );
                }

                $property_row_values['PRICE'] = get_post_meta( $post->ID, 'fave_property_price', TRUE );

                if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
                {
                    $gbp_countries = array( 'AE', 'AU', 'BG', 'BR', 'CZ', 'EG', 'HU', 'MA', 'NY', 'NZ', 'SG', 'TH', 'TR', 'ZA' );
                    $gbp_countries = apply_filters( 'propertyhive_blm_gbp_countries' , $gbp_countries );

                    $country = get_post_meta($post->ID, '_address_country', true);

                    if ( in_array($country, $gbp_countries) )
                    {
                        $property_row_values['PRICE'] = round(get_post_meta( $post->ID, '_price_actual', TRUE ));
                    }
                }

                $price_qualifier = '';
                $price_qualifier_field_name = apply_filters( 'houzez_property_feed_price_qualifier_field', 'fave_property_price_prefix' );
                $price_qualifier = $this->get_export_mapped_value($post->ID, '', 'price_qualifier', $price_qualifier_field_name);
                $property_row_values['PRICE_QUALIFIER'] = $price_qualifier;
                $property_type = $this->get_export_mapped_value($post->ID, 'property_type');
                $property_row_values['PROP_SUB_ID'] = ( ( $property_type != '' ) ? $property_type : '0' );
                $property_row_values['CREATE_DATE'] = get_the_time('Y-m-d H:i:s');
                $property_row_values['UPDATE_DATE'] = get_the_modified_time('Y-m-d H:i:s');
                $property_row_values['DISPLAY_ADDRESS'] = get_the_title();
                $property_row_values['PUBLISHED_FLAG'] = '1';

                if ( isset($export_settings['overseas']) && $export_settings['overseas'] == 'yes' )
                {

                }
                else
                {
                    $let_date_available = '';
                    $property_row_values['LET_DATE_AVAILABLE'] = $let_date_available;
                    $property_row_values['LET_BOND'] = '';
                    $fees = '';
                    $property_row_values['ADMINISTRATION_FEE'] = $fees;
                    $property_row_values['LET_TYPE_ID'] = '0';
                    $property_row_values['LET_FURN_ID'] = '';
                    $rent_frequency = '';
                    if ( $department == 'lettings' )
                    {
                        switch ( strtolower(get_post_meta( $post->ID, 'fave_property_price_postfix', TRUE )) )
                        {
                            case "pw":
                            case "per week":
                            case "weekly": { $rent_frequency = '0'; break; }
                            case "pq":
                            case "per quarter":
                            case "quarterly": { $rent_frequency = '2'; break; }
                            case "pa":
                            case "per annum":
                            case "per year":
                            case "yearly": { $rent_frequency = '3'; break; }
                            case "pppw": { $rent_frequency = '5'; break; }
                            default: { $rent_frequency = '1'; }
                        }
                    }
                    $property_row_values['LET_RENT_FREQUENCY'] = $rent_frequency;
                    $property_row_values['TENURE_TYPE_ID'] = '';

                    $property_row_values['COUNCIL_TAX_BAND'] = '';
                    $property_row_values['SHARED_OWNERSHIP'] = '';
                    $property_row_values['SHARED_OWNERSHIP_PERCENTAGE'] = '';
                    $property_row_values['ANNUAL_GROUND_RENT'] = '';
                    $property_row_values['GROUND_RENT_REVIEW_PERIOD_YEARS'] = '';
                    $property_row_values['ANNUAL_SERVICE_CHARGE'] = '';
                    $property_row_values['TENURE_UNEXPIRED_YEARS'] = '';
                }
                $property_row_values['TRANS_TYPE_ID'] = ( ($department == 'lettings') ? '2' : '1' );

                // IMAGES
                $attachment_ids = get_post_meta( $post->ID, 'fave_property_images' );
                $i = 0;
                if ( is_array($attachment_ids) && !empty($attachment_ids) )
                {
                    foreach ( $attachment_ids as $attachment_id )
                    {
                        if ( !wp_attachment_is_image($attachment_id) )
                        {
                            continue;
                        }

                        if ( $i >= $num_images )
                        {
                            break;
                        }

                        $j = str_pad($i, 2, '0', STR_PAD_LEFT);

                        if ( $export_settings['media_sent_as'] == 'urls' )
                        {
                            // Sent as full URLs
                            $url = wp_get_attachment_image_src( $attachment_id, 'full' );
                            if ( $url === FALSE )
                            {
                                $property_row_values['MEDIA_IMAGE_' . $j] = '';
                            }
                            else
                            {
                                $property_row_values['MEDIA_IMAGE_' . $j] = $url[0];
                            }
                        }
                        else
                        {
                            // Sent as physical files
                            $filepath = get_attached_file( $attachment_id );

                            // Get extension
                            $explode_file_name = explode(".", trim( basename( $filepath ) ));
                            $extension = $explode_file_name[ count($explode_file_name) - 1 ];

                            // Construct filename
                            $filename = $agent_ref . "_IMG_" . $j . "." . $extension;

                            // Only add file reference to BLM file if it exists
                            // Adding a file that doesn't exist can cause the ZIP creation to fail
                            if ( file_exists( $filepath ) )
                            {
                                $property_row_values['MEDIA_IMAGE_' . $j] = $filename;

                                $include = false;
                                if ( isset($export_settings['incremental']) && $export_settings['incremental'] == 'yes' )
                                {
                                    if (strtotime($property_last_sent) <= strtotime(get_the_modified_time('Y-m-d H:i:s', true)))
                                    {
                                        $include = true;
                                    }
                                }
                                else
                                {
                                    $include = true;
                                }

                                if ($include)
                                {
                                    if ( isset($export_settings['compressed']) && $export_settings['compressed'] == 'yes' )
                                    {
                                        // parse the local file name
                                        // @see http://us.php.net/manual/en/ziparchive.addfile.php#89813
                                        // @see http://stackoverflow.com/questions/4620205/php-ziparchive-corrupt-in-windows
                                        $filename = str_replace('\\', '/', ltrim($filename, '\\/'));

                                        $files_to_zip[] = array(
                                            'local' => $filepath,
                                            'remote' => $filename
                                        );
                                    }
                                    else
                                    {
                                        $files_to_ftp[] = array(
                                            'local' => $filepath,
                                            'remote' => $filename,
                                            'mode' => FTP_BINARY
                                        );
                                    }
                                }
                            }
                            else
                            {
                                $property_row_values['MEDIA_IMAGE_' . $j] = '';
                            }
                        }
                        $attachment_data = wp_prepare_attachment_for_js( $attachment_id );
                        $property_row_values['MEDIA_IMAGE_TEXT_' . $j] = ( ( isset( $attachment_data['alt'] ) ) ? $attachment_data['alt'] : '' );

                        ++$i;
                    }
                }
                for ($k = $i; $k < $num_images; ++$k)
                {
                    $j = str_pad($k, 2, '0', STR_PAD_LEFT);

                    $property_row_values['MEDIA_IMAGE_' . $j] = '';
                    $property_row_values['MEDIA_IMAGE_TEXT_' . $j] = '';
                }

                $property_row_values['MEDIA_IMAGE_60'] = '';
                $property_row_values['MEDIA_IMAGE_TEXT_60'] = '';

                // FLOORPLANS
                $i = 0;
                $floorplans = get_post_meta( $post_id, 'floor_plans', true );
                if ( is_array($floorplans) && !empty($floorplans) )
                {
                    foreach ($floorplans as $floorplan)
                    {
                        if ( $i >= $num_floorplans )
                        {
                            break;
                        }

                        $j = str_pad($i, 2, '0', STR_PAD_LEFT);

                        // Sent as full URLs
                        $url = ( isset($floorplan['fave_plan_image']) ? $floorplan['fave_plan_image'] : '' );
                        $text = ( isset($floorplan['fave_plan_title']) ? $floorplan['fave_plan_title'] : 'Floorplan' );

                        $property_row_values['MEDIA_FLOOR_PLAN_' . $j] = $url;

                        $property_row_values['MEDIA_FLOOR_PLAN_TEXT_' . $j] = $text;

                        ++$i;
                    }
                }
                for ($k = $i; $k < $num_floorplans; ++$k)
                {
                    $j = str_pad($k, 2, '0', STR_PAD_LEFT);

                    $property_row_values['MEDIA_FLOOR_PLAN_' . $j] = '';
                    $property_row_values['MEDIA_FLOOR_PLAN_TEXT_' . $j] = '';
                }
                
                // BROCHURES
                $attachment_ids = get_post_meta( $post->ID, 'fave_attachments' );
                $i = 0;
                if ( is_array($attachment_ids) && !empty($attachment_ids) )
                {
                    foreach ( $attachment_ids as $attachment_id )
                    {
                        if ( $i >= $num_brochures )
                        {
                            break;
                        }

                        $j = str_pad($i, 2, '0', STR_PAD_LEFT);

                        // Sent as full URLs
                        $url = wp_get_attachment_url( $attachment_id );
                        if ($url === FALSE)
                        {
                            $property_row_values['MEDIA_DOCUMENT_' . $j] = '';
                            $property_row_values['MEDIA_DOCUMENT_TEXT_' . $j] = '';
                        }
                        else
                        {
                            $property_row_values['MEDIA_DOCUMENT_' . $j] = $url;
                            $caption = wp_get_attachment_caption( $attachment_id );
                            $property_row_values['MEDIA_DOCUMENT_TEXT_' . $j] = ( $caption != '' ? $caption : 'Brochure' );
                        }
                        
                        ++$i;
                    }
                }
                for ($k = $i; $k < $num_brochures; ++$k)
                {
                    $j = str_pad($k, 2, '0', STR_PAD_LEFT);

                    $property_row_values['MEDIA_DOCUMENT_' . $j] = '';
                    $property_row_values['MEDIA_DOCUMENT_TEXT_' . $j] = '';
                }

                // VIRTUAL TOURS
                $virtual_tours = array();
                if ( get_post_meta( $post->ID, 'fave_video_url', true ) != '' )
                {
                    $virtual_tours[] = array(
                        'url' => get_post_meta( $post->ID, 'fave_video_url', true ),
                        'label' => 'Video'
                    );
                }
                $i = 0;
                if ( !empty($virtual_tours) )
                {
                    foreach ($virtual_tours as $virtual_tour)
                    {
                        if ( $i >= $num_virtual_tours )
                        {
                            break;
                        }

                        $j = str_pad($i, 2, '0', STR_PAD_LEFT);

                        // Sent as full URLs
                        $property_row_values['MEDIA_VIRTUAL_TOUR_' . $j] = $virtual_tour['url'];
                        $property_row_values['MEDIA_VIRTUAL_TOUR_TEXT_' . $j] = $virtual_tour['label'];

                        ++$i;
                    }
                }
                for ($k = $i; $k < $num_virtual_tours; ++$k)
                {
                    $j = str_pad($k, 2, '0', STR_PAD_LEFT);

                    $property_row_values['MEDIA_VIRTUAL_TOUR_' . $j] = '';
                    $property_row_values['MEDIA_VIRTUAL_TOUR_TEXT_' . $j] = '';
                }
            
                $new_property_row_values = array();
                foreach ($property_row_values as $key => $property_row_value)
                {
                    $property_row_value = str_replace( '^', "", $property_row_value );
                    $property_row_value = str_replace( '~', "", $property_row_value );
                    $property_row_value = str_replace( "\r\n", "", $property_row_value );
                    $property_row_value = str_replace( "\n", "", $property_row_value );

                    $new_property_row_values[$key] = $property_row_value;
                }

                $property_row_values = apply_filters( 'houzez_property_feed_export_property_data', $new_property_row_values, $post->ID, $this->export_id );
                $property_row_values = apply_filters( 'houzez_property_feed_export_blm_property_data', $property_row_values, $post->ID, $this->export_id );

                $property_row = implode("^", $property_row_values) . "^~\n";

                fwrite($handle, $property_row);

                $this->log("Property written to BLM", '', $post->ID);

                ++$properties_added;
            }
        }

        fwrite($handle, '#END#');

        fclose($handle);

        if ( isset($_GET['preview']) )
        {
            $quoted = sprintf('"%s"', addcslashes(basename($blm_filename), '"\\'));
            $size   = filesize($blm_filename);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $quoted); 
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . $size);

            readfile($blm_filename);

            die();
        }

        // do compression if necessary
        if ( isset($export_settings['compressed']) && $export_settings['compressed'] == 'yes' )
        {
            $this->log("Compressing " . count($files_to_zip) . " files into " . $zip_filename);

            $zip = new ZipArchive;
                                        
            $res = $zip->open( $zip_filename, ZipArchive::CREATE );
            if( $res === TRUE )
            {
                // Loop through all the files and add them to the zip
                foreach ($files_to_zip as $file_to_zip)
                {
                    $zip->addFile($file_to_zip['local'], $file_to_zip['remote']);
                }
                
                $zip->close(); // Close the zip
            }
            else
            {
                // Error creating zip file
                $this->log_error("Failed to create zip file");
            }

            $this->log("Successfully compressed files");
        }

        // Do FTP'ing
        if (!empty($files_to_ftp))
        {
            $this->log("Uploading " . count($files_to_ftp) . " files via FTP");

            $files_successfully_uploaded = 0;

            // Connect to FTP
            $ftp_conn = ftp_connect( $export_settings['ftp_host'] );
            if ( $ftp_conn !== FALSE )
            {
                $ftp_login = ftp_login($ftp_conn, $export_settings['ftp_user'], $export_settings['ftp_pass']);
                if ( $ftp_login !== FALSE )
                {
                    if ( isset($export_settings['ftp_passive']) && $export_settings['ftp_passive'] == 'yes' )
                    {
                        ftp_pasv($ftp_conn, true);
                    }
                    if ( 
                        ( isset($export_settings['ftp_dir']) && !empty($export_settings['ftp_dir']) && ftp_chdir($ftp_conn, $export_settings['ftp_dir']) ) ||
                        ( isset($export_settings['ftp_dir']) && empty($export_settings['ftp_dir']) ) ||
                        !isset($export_settings['ftp_dir'])
                    )
                    {
                        foreach ( $files_to_ftp as $file_to_ftp )
                        {
                            // Do FTP upload
                            if ( ftp_put($ftp_conn, $file_to_ftp['remote'], $file_to_ftp['local'], $file_to_ftp['mode']) )
                            {
                                ++$files_successfully_uploaded;
                            }
                            else
                            {
                                $this->log_error("File " . $file_to_ftp['local'] . " could not be uploaded via FTP");
                            }
                        }
                    }
                }
                ftp_close($ftp_conn);
            }

            if ( isset($export_settings['compressed']) && $export_settings['compressed'] == 'yes' )
            {
                unlink($zip_filename);
            }

            $this->log("Successfully uploaded " . $files_successfully_uploaded . " / " . count($files_to_ftp) . " files");
        }
        else
        {
            $this->log_error("No files to upload via FTP");
        }

        // Delete BLM files older than 7 days
        $path = $wp_upload_dir['basedir'] . '/houzez_property_feed_export/'; 
        if ( $handle = opendir($path) )  
        {  
            // Loop through the directory  
            while ( false !== ($file = readdir($handle)) )  
            {  
                // Check the file we're doing is actually a BLM file  
                if ( is_file($path.$file) && strpos(strtolower($file), '.blm') !== FALSE )  
                {  
                    // Check if the file is older than X days old  
                    if ( filemtime($path.$file) < ( time() - ( 7 * 24 * 60 * 60 ) ) )  
                    {  
                        // Do the deletion  
                        unlink($path . $file);  
                    }  
                }  
            }  
        } 

        return true;
	}

}

}