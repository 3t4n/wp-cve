<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Export Enquiries Functions
 */
class Houzez_Property_Feed_Export_Enquiries {

	public function __construct() 
    {
        add_action( 'houzez_record_activities', array( $this, 'export_enquiry' ) );
	}

    public function export_enquiry( $activity_args = array() )
    {
        $property_id = isset($_POST['listing_id']) ? sanitize_text_field( $_POST['listing_id'] ) : '';
        $activity_args['property_id'] = $property_id;

        // get all active imports where export enquiries is enabled
        $options = get_option( 'houzez_property_feed' , array() );
        $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();

        foreach ( $imports as $key => $import )
        {
            if ( isset($imports[$key]['deleted']) && $imports[$key]['deleted'] === true )
            {
                unset( $imports[$key] );
                continue;
            }

            if ( !isset($import['running']) || ( isset($import['running']) && $import['running'] !== true ) )
            {
                unset( $imports[$key] );
                continue;
            }

            $format = get_houzez_property_feed_import_format( $import['format'] );

            if ( !isset($format['export_enquiries']) || ( isset($format['export_enquiries']) && $format['export_enquiries'] !== true ) )
            {
                unset( $imports[$key] );
                continue;
            }

            if ( !isset($imports[$key]['export_enquiries_enabled']) || ( isset($imports[$key]['export_enquiries_enabled']) && $imports[$key]['export_enquiries_enabled'] !== 'yes' ) )
            {
                unset( $imports[$key] );
                continue;
            }
        }

        // By now we should only have imports where exporting enquiries is enabled
        foreach ( $imports as $key => $import )
        {
            switch ( $import['format'] )
            {
                case "street": { $this->export_street_enquiries( $key, $activity_args ); break; }
            }
        }
    }

    public function export_street_enquiries( $import_id, $activity_args = array() )
    {
        if (
            ( !isset($activity_args['email']) || ( isset($activity_args['email']) && empty($activity_args['email']) ) )
            ||
            ( !isset($activity_args['message']) || ( isset($activity_args['message']) && empty($activity_args['message']) ) )
        )
        {
            // Failed validation
            return false;
        }

        $import_settings = get_import_settings_from_id( $import_id );

        // Get imported ref for Property ID
        $street_property_id = null;
        if ( isset($activity_args['property_id']) && !empty($activity_args['property_id']) )
        {
            $metadata = get_metadata('post', $activity_args['property_id'], '', true);

            foreach ( $metadata as $key => $value )
            {
                if ( $key == '_imported_ref_' . $import_id )
                {
                    $street_property_id = is_array($value) ? $value[0] : $value;
                }
            }
        }

        $explode_name = isset($activity_args['name']) ? explode(" ", $activity_args['name'], 2) : array();

        $data = array( 
            'data' => array(
                'type' => 'enquiry',
                'attributes' => array(
                    'first_name' => ( ( isset($explode_name[0]) ) ? $explode_name[0] : '' ),
                    'last_name' => ( ( isset($explode_name[1]) ) ? $explode_name[1] : '' ),
                    'email_address' => ( ( isset($activity_args['email']) ) ? $activity_args['email'] : '' ),
                    'telephone_number' => ( ( isset($activity_args['phone']) ) ? $activity_args['phone'] : '' ),
                    'message' => ( ( isset($activity_args['message']) ) ? $activity_args['message'] : '' ),
                    'property_uuid' => $street_property_id,
                    'custom_source' => 'website'
                )
            ),
        );

        $data = apply_filters( 'houzez_property_feed_export_enquiries_data_street', $data, $import_id, $activity_args );

        $args = array(
            'body' => json_encode($data),
            'headers' => array(
                'Accept' => 'application/vnd.api+json',
                'Authorization' => 'Bearer ' . ( ( isset($import_settings['api_key']) ) ? $import_settings['api_key'] : '' ),
                'Content-Type' => 'application/vnd.api+json',
            )
        );

        $response = wp_remote_post( ( ( isset($import_settings['base_url']) && !empty($import_settings['base_url']) ) ? trim($import_settings['base_url'], '/') : 'https://street.co.uk' ) . '/open-api/enquiries', $args );

        if ( is_wp_error( $response ) ) 
        {
            $error_message = $response->get_error_message();
            // Failed. Should probably do something here
        }
        else
        {
            // Success. Check response
            $body = $response['body'];

            $json = json_decode($body, TRUE);

            if ( $json !== false )
            {
                if ( isset($json['errors']) && is_array($json['errors']) && !empty($json['errors']) )
                {
                    // Failed. Should probably do something here
                }
            }
            else
            {
                // Failed. Should probably do something here
            }
        }
    }
}

new Houzez_Property_Feed_Export_Enquiries();