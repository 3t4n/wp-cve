<?php

function rss_retriever_ajax_request() { 
    if ( isset($_REQUEST) ) {
        // Check for nonce security      
         if (isset($_REQUEST['settings']['nonce'])) {
            if (!wp_verify_nonce($_REQUEST['settings']['nonce'], 'rss-retriever-ajax-nonce')) {
                echo json_encode('Error: Ajax nonce security check failed.');
                die();
            }
         } else {
            echo json_encode('Error: Ajax nonce security check failed. Nonce undefined.');
            die();
         }

        $settings = array();

        // get variables
        if (isset($_REQUEST['settings'])) {
            foreach($_REQUEST['settings'] as $key => $value) {
                $settings[$key] = sanitize_text_field($value);
            }
        }
        $settings['ajax'] = 'false';

        try {
            $feed = new RSS_Retriever_Feed($settings);
            $output = $feed->display_feed();
        } catch (Exception $e) {
            $output = $e->getMessage() . "\n";
        }

        // encode and return the array
        echo json_encode($output);
    }
    die();
}
 
add_action( 'wp_ajax_rss_retriever_ajax_request', 'rss_retriever_ajax_request' );
add_action( 'wp_ajax_nopriv_rss_retriever_ajax_request', 'rss_retriever_ajax_request' );