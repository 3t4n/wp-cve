<?php

namespace Wicked_Folders\REST_API\v1;

use Exception;
use WP_Error;
use WP_REST_Server;
use Wicked_Folders\Screen_State;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

class Screen_State_API extends REST_API {

    public function __construct() {
        $this->register_routes();
    }

	public function register_routes() {
		register_rest_route( $this->base, '/screen-state', array(		
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_item' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
			),
		) );
	}

	public function update_item_permissions_check( $request ) {
		return current_user_can( 'edit_posts' );
	}

	public function update_item( $request ) {
		try {
			$json 		= $request->get_json_params();
			$screen_id 	= $json['screenId'];
			$user_id 	= $json['userId'];
			$state 		= new Screen_State( $screen_id, $user_id );

			$state->from_json( $json );
			$state->save();

			return $state;
		} catch ( Exception $exception ) {
			return new WP_Error(
				'wf_error',
				$exception->getMessage(),
				array( 'status' => 500 )
			);
		}
	}
}
