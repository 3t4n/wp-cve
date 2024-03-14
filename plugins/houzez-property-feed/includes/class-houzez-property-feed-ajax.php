<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Ajax Functions
 */
class Houzez_Property_Feed_Ajax {

	public function __construct() {

        add_action( "wp_ajax_houzez_property_feed_fetch_xml_nodes", array( $this, "fetch_xml_nodes" ) );

        add_action( "wp_ajax_houzez_property_feed_fetch_csv_fields", array( $this, "fetch_csv_fields" ) );

	}

    public function fetch_xml_nodes()
    {
        header( 'Content-Type: application/json; charset=utf-8' );

        if ( !wp_verify_nonce( $_GET['ajax_nonce'], "hpf_ajax_nonce" ) ) 
        {
            $return = array(
                'success' => false,
                'error' => __( 'Invalid nonce provided', 'houzezpropertyfeed' )
            );
            echo json_encode($return);
            die();
        } 

        // nonce ok. Let's get the XML

        $contents = '';

        $args = array( 'timeout' => 120, 'sslverify' => false );
        $args = apply_filters( 'houzez_property_feed_xml_request_args', $args, $_GET['url'] );
        $response = wp_remote_get( $_GET['url'], $args );
        if ( !is_wp_error($response) && is_array( $response ) ) 
        {
            $contents = $response['body'];
        }
        else
        {
            $error = __( 'Failed to obtain XML. Dump of response as follows', 'houzezpropertyfeed' ) . ': ' . print_r($response, TRUE);
            if ( is_wp_error($response) )
            {
                $error = $response->get_error_message();
            }
            $return = array(
                'success' => false,
                'error' => $error
            );
            echo json_encode($return);
            die();
        }

        $xml = simplexml_load_string($contents);

        if ($xml !== FALSE)
        {
            $node_names = get_all_node_names($xml, array_merge(array(''), $xml->getNamespaces(true)));
            $node_names = array_unique($node_names);

            $return = array(
                'success' => true,
                'nodes' => $node_names
            );
            echo json_encode($return);
            die();
        }
        else
        {
            // Failed to parse XML
            $return = array(
                'success' => false,
                'error' => __( 'Failed to parse XML file', 'houzezpropertyfeed' ) . ': ' . print_r($contents, TRUE)
            );
            echo json_encode($return);
            die();
        }

        wp_die();
    }

    public function fetch_csv_fields()
    {
        header( 'Content-Type: application/json; charset=utf-8' );

        if ( !wp_verify_nonce( $_GET['ajax_nonce'], "hpf_ajax_nonce" ) ) 
        {
            $return = array(
                'success' => false,
                'error' => __( 'Invalid nonce provided', 'houzezpropertyfeed' )
            );
            echo json_encode($return);
            die();
        } 

        // nonce ok. Let's get the XML

        $contents = '';

        $args = array( 'timeout' => 120, 'sslverify' => false );
        $args = apply_filters( 'houzez_property_feed_csv_request_args', $args, $_GET['url'] );
        $response = wp_remote_get( $_GET['url'], $args );
        if ( !is_wp_error($response) && is_array( $response ) ) 
        {
            $contents = $response['body'];
        }
        else
        {
            $error = __( 'Failed to obtain CSV. Dump of response as follows', 'houzezpropertyfeed' ) . ': ' . print_r($response, TRUE);
            if ( is_wp_error($response) )
            {
                $error = $response->get_error_message();
            }
            $return = array(
                'success' => false,
                'error' => $error
            );
            echo json_encode($return);
            die();
        }

        $lines = explode( "\n", $contents );
        $headers = str_getcsv( array_shift( $lines ), ( isset($_GET['delimiter']) ? sanitize_text_field($_GET['delimiter']) : ',' ) );

        $return = array(
            'success' => true,
            'fields' => $headers
        );
        echo json_encode($return);

        wp_die();
    }
}

new Houzez_Property_Feed_Ajax();