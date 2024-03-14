<?php

add_action( 'plugins_loaded', 'AddRestAPIMetaSupport' );

/******************************************************************************************
 * Plugin Name: REST API Meta Support
 * Description: Updates a page/post meta data from the meta field of a REST POST request
 * Author: Pericror
 * Author URI: https://github.com/pericror
 * Version: 1.0.0
 * Plugin URI: https://github.com/pericror/WPRESTAPIMetaSupport
 *****************************************************************************************/
class RestAPIMetaSupport {

	function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_rest_meta_hook' ) );
	}

	function register_rest_meta_hook() {
        // https://developer.wordpress.org/reference/functions/register_rest_field/
		register_rest_field( ['post','page'],
			'meta',
			array(
				'get_callback'    => null,
				'update_callback' => array( $this, 'update_post_meta_from_rest' ),
				'schema'          => null,
			)
		);
	}

	function update_post_meta_from_rest( $value, $data, $field_name ) {
        // Update the WP post meta with all of the key value fields present in the 'meta' field of the POST
        // We write both 'key' and '_key' values to account for variations in plugins
		foreach ( $value as $k => $v ) {
				if(!empty( $k ))
                {
                    update_post_meta( $data->ID, '_' . $k, $v );
                    update_post_meta( $data->ID, $k, $v );
                }
            }
	}
}

function AddRestAPIMetaSupport() {
    $rest_api_meta_support = new RestAPIMetaSupport();
}