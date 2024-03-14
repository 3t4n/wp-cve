<?php
/**
 * Class for managing the export process of an Idealista file
 *
 * @package WordPress
 */
if ( class_exists( 'Houzez_Property_Feed_Process' ) ) {

class Houzez_Property_Feed_Format_Idealista extends Houzez_Property_Feed_Process {

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

        $args = apply_filters( 'houzez_property_feed_export_property_args', $args, $this->export_id );
        $args = apply_filters( 'houzez_property_feed_export_idealista_property_args', $args, $this->export_id );

        $properties_query = new WP_Query( $args );
        $num_properties = $properties_query->found_posts;

        $files_to_zip = array();
        $files_to_ftp = array();

        $local_json_file = $this->export_id . '_' . date("YmdHis") . '.json';

        $remote_json_file = date("YmdHis") . '.json';

        $json_filename = $wp_upload_dir['basedir'] . '/houzez_property_feed_export/' . $local_json_file;
        $handle = fopen($json_filename, 'w+');

        $files_to_ftp[] = array(
            'local' => $json_filename,
            'remote' => $remote_json_file,
            'mode' => FTP_BINARY
        );

        $properties_added = 0;

        $properties_data = array();

        if ( $properties_query->have_posts() )
        {
            $this->log( "Beginning to iterate through properties" );

            while ( $properties_query->have_posts() )
            {
                $properties_query->the_post();

                $this->log("Doing property", '', $post->ID);

                $property_data = array();

                $property_data['propertyCode'] = $post->ID;
                $property_data['propertyReference'] = get_post_meta( $post_id, 'fave_property_id', true );
                $property_data['propertyVisibility'] = 'idealista';
                $property_data['propertyOperation'] = array();
                $property_data['propertyOperation']['operationType'] = 'sale'; // "rent","sale","rentToOwn"
                $property_data['propertyOperation']['operationPrice'] = get_post_meta( $post_id, 'fave_property_price', true );
                $property_data['propertyOperation']['operationPriceCommunity'] = '';
                $property_data['propertyOperation']['operationPriceParking'] = '';
                $property_data['propertyContact'] = array();
                $property_data['propertyContact']['contactName'] = $export_settings['contact_name'];
                $property_data['propertyContact']['contactEmail'] = $export_settings['contact_email'];
                $property_data['propertyContact']['contactPrimaryPhonePrefix'] = $export_settings['primary_telephone_number_prefix'];
                $property_data['propertyContact']['contactPrimaryPhoneNumber'] = $export_settings['primary_telephone_number'];
                $property_data['propertyAddress'] = array();
                $property_data['propertyAddress']['addressVisibility'] = 'street';
                $property_data['propertyAddress']['addressStreetName'] = '';
                $property_data['propertyAddress']['addressStreetNumber'] = '';
                $property_data['propertyAddress']['addressBlock'] = '';
                $property_data['propertyAddress']['addressFloor'] = '';
                $property_data['propertyAddress']['addressStair'] = '';
                $property_data['propertyAddress']['addressDoor'] = '';
                $property_data['propertyAddress']['addressUrbanization'] = '';
                $property_data['propertyAddress']['addressPostalCode'] = get_post_meta($post->ID, 'fave_property_zip', true);
                $property_data['propertyAddress']['addressNsiCode'] = '';
                $property_data['propertyAddress']['addressTown'] = '';
                $property_data['propertyAddress']['addressCountry'] = ( get_post_meta($post->ID, '_address_country', true) != '' ? get_post_meta($post->ID, '_address_country', true) : $export_settings['country'] );
                $property_data['propertyAddress']['addressCoordinatesPrecision'] = 'exact';
                $fave_property_location = get_post_meta($post->ID, 'fave_property_location', true);
                $explode_fave_property_location = explode(",", $fave_property_location);
                $lat = '';
                $lng = '';
                if ( count($explode_fave_property_location) >= 2 )
                {
                    $lat = $explode_fave_property_location[0];
                    $lng = $explode_fave_property_location[1];
                }
                $property_data['propertyAddress']['addressCoordinatesLatitude'] = $lat;
                $property_data['propertyAddress']['addressCoordinatesLongitude'] = $lng;
                $property_data['propertyFeatures'] = array();
                $property_data['propertyFeatures']['featuresType'] = $this->get_export_mapped_value($post->ID, 'property_type');
                $property_data['propertyFeatures']['featuresBathroomNumber'] = get_post_meta( $post->ID, 'fave_property_bedrooms', TRUE );
                $property_data['propertyFeatures']['featuresBedroomNumber'] = get_post_meta( $post->ID, 'fave_property_bathrooms', TRUE );
                $property_data['propertyDescriptions'] = array(
                    'descriptionLanguage' => 'english',
                    'descriptionText' => substr(strip_tags(get_the_content()), 0, 4000),
                );

                // Images
                $property_data['propertyImages'] = array();

                $attachment_ids = get_post_meta( $post->ID, 'fave_property_images' );
                $i = 1;
                if ( is_array($attachment_ids) && !empty($attachment_ids) )
                {
                    foreach ( $attachment_ids as $attachment_id )
                    {
                        if ( !wp_attachment_is_image($attachment_id) )
                        {
                            continue;
                        }

                        $property_data['propertyImages'][] = array(
                            'imageOrder' => $i,
                            'imageLabel' => '',
                            'imageUrl' => wp_get_attachment_image_src( $attachment_id, 'full' ),
                        );

                        ++$i;
                    }
                }

                // Virtual tours
                $property_data['propertyVirtualTours'] = array();
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
                        $property_data['propertyVirtualTours'][] = array(
                            'videoOrder' => $i,
                            'videoUrl' => $virtual_tour,
                        );

                        ++$i;
                    }
                }

                $property_data['propertyUrl'] = get_permalink($post->ID);

                $property_data = apply_filters( 'houzez_property_feed_export_property_data', $property_data, $post->ID, $this->export_id );
                $property_data = apply_filters( 'houzez_property_feed_export_idealista_property_data', $property_data, $post->ID, $this->export_id );

                $properties_data[] = $property_data;

                $this->log("Property written to JSON", '', $post->ID);

                ++$properties_added;
            }
        }

        $file_data = array();

        $file_data['customerCountry'] = $export_settings['country'];
        $file_data['customerCode'] = $export_settings['customer_code'];
        $file_data['customerReference'] = '';
        $file_data['customerSendDate'] = date("Y/m/d H:i:s");
        $file_data['customerContact'] = array(
            'contactName' => $export_settings['contact_name'],
            'contactEmail' => $export_settings['contact_email'],
            'contactPrimaryPhonePrefix' => $export_settings['primary_telephone_number_prefix'],
            'contactPrimaryPhoneNumber' => $export_settings['primary_telephone_number'],
        );

        $file_data['customerProperties'] = $properties_data;

        fwrite($handle, json_encode($file_data));

        fclose($handle);

        if ( isset($_GET['preview']) )
        {
            $quoted = sprintf('"%s"', addcslashes(basename($json_filename), '"\\'));
            $size   = filesize($json_filename);

            header('Content-Description: File Transfer');
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename=' . $quoted); 
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . $size);

            readfile($json_filename);

            die();
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

            $this->log("Successfully uploaded " . $files_successfully_uploaded . " / " . count($files_to_ftp) . " files");
        }
        else
        {
            $this->log_error("No files to upload via FTP");
        }

        // Delete JSON files older than 7 days
        $path = $wp_upload_dir['basedir'] . '/houzez_property_feed_export/'; 
        if ( $handle = opendir($path) )  
        {  
            // Loop through the directory  
            while ( false !== ($file = readdir($handle)) )  
            {  
                // Check the file we're doing is actually a JSON file  
                if ( is_file($path.$file) && strpos(strtolower($file), '.json') !== FALSE )  
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