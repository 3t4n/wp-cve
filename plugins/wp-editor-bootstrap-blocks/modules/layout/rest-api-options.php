<?php 
/**
 * Bootstrap Blocks for WP Editor Layout.
 *
 * @version 1.4.0
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2021-05-10
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'GUTENBERGBOOTSTRAP_VERSION' ) ) {
	exit;
}


/**
 * This function is where we register our routes for the gtb endpoint.
 */
add_action( 'rest_api_init', function () {

	// Register endpoint: domain.com/wp-json/powerapi/v1/thingstoget
	register_rest_route( 'gtbbootstrap/v1', 'options', array(
		'methods'   => 'GET, POST',
		'callback'  => 'rest_api_gtbbootstrap_options',
		'args'		=> array(
			'width' 	=> array( 'validate_callback' => function( $param, $request, $key ) {
				return is_numeric( $param );
			}),
		),
		'permission_callback' => function(){ return current_user_can('edit_posts');},
	));
});

/**
 * Retrieve information for a custom endpoint
 *
 * @return WP_Rest_Response
 */
function rest_api_gtbbootstrap_options($data)
{
	$user_id = get_current_user_id();
	if (!$user_id) die;
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if (is_numeric($data['width']))
		{
			update_user_meta($user_id, 'editor_width', $data['width'] );
		}
	}
	
	return new WP_Rest_Response( array(
		'editor_width' => get_user_meta($user_id, 'editor_width' ,true)
	) );
}
