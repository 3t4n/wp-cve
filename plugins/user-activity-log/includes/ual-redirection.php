<?php
/**
 * Redirection Support.
 *
 * @package User Activity Log
 */

/**
 * Logger for the Redirection plugin
 * https://wordpress.org/plugins/redirection/
 */

add_filter( 'rest_request_before_callbacks', 'ual_rest_request_before_callbacks', 10, 3 );

if ( ! function_exists( 'ual_rest_request_before_callbacks' ) ) {
	/**
	 * Fired when WP REST API call is done.
	 *
	 * @param WP_HTTP_Response $response Result to send to the client. Usually a WP_REST_Response.
	 * @param WP_REST_Server   $handler  ResponseHandler instance (usually WP_REST_Server).
	 * @param WP_REST_Request  $request  Request used to generate the response.
	 *
	 * @return WP_HTTP_Response $response
	 */
	function ual_rest_request_before_callbacks( $response, $handler, $request ) {

		if ( ! isset( $handler['callback'] ) ) {
			return $response;
		}
		$recallback = $handler['callback'];

		// get redirection callable name.
		if ( is_string( $recallback ) ) {
			$red_callable_name = trim( $recallback );
		} elseif ( is_array( $recallback ) ) {
			if ( is_object( $recallback[0] ) ) {
				$red_callable_name = sprintf( '%s::%s', get_class( $recallback[0] ), trim( $recallback[1] ) );
			} else {
				$red_callable_name = sprintf( '%s::%s', trim( $recallback[0] ), trim( $recallback[1] ) );
			}
		} elseif ( $recallback instanceof Closure ) {
			$red_callable_name = 'closure';
		} else {
			$red_callable_name = 'unknown';
		}

		$redirection_api_callable_names = array(
			'Redirection_Api_Redirect::route_bulk',
			'Redirection_Api_Redirect::route_create',
			'Redirection_Api_Redirect::route_update',
			'Redirection_Api_Group::route_create',
			'Redirection_Api_Group::route_bulk',
			'Redirection_Api_Settings::route_save_settings',
		);
		if ( ! in_array( $red_callable_name, $redirection_api_callable_names ) ) {
			return $response;
		}
		if ( 'Redirection_Api_Redirect::route_create' === $red_callable_name ) {
			ual_log_redirection_add( $request );
		} elseif ( 'Redirection_Api_Redirect::route_update' === $red_callable_name ) {
			ual_log_redirection_edit( $request );
		} elseif ( 'Redirection_Api_Redirect::route_bulk' === $red_callable_name ) {
			$bulk_action = $request->get_param( 'bulk' );
			$bulk_items  = $request->get_param( 'items' );

			if ( ! is_array( $bulk_items ) ) {
				$bulk_items = explode( ',', $bulk_items );
			}

			if ( is_array( $bulk_items ) ) {
				$bulk_items = array_map( 'intval', $bulk_items );
			}

			if ( empty( $bulk_items ) ) {
				return $response;
			}

			if ( 'enable' === $bulk_action ) {
				$action     = 'redirection_enabled';
				$obj_type   = 'Redirection';
				$post_id    = '';
				$post_title = 'Enabled redirection for ' . count( $bulk_items ) . ' URL(s) ';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			} elseif ( 'disable' === $bulk_action ) {
				$action     = 'redirection_disabled';
				$obj_type   = 'Redirection';
				$post_id    = '';
				$post_title = 'Disabled redirection for ' . count( $bulk_items ) . ' URL(s) ';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			} elseif ( 'delete' === $bulk_action ) {
				$action     = 'redirection_deleted';
				$obj_type   = 'Redirection';
				$post_id    = '';
				$post_title = 'Deleted redirection for ' . count( $bulk_items ) . ' URL(s) ';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
		} elseif ( 'Redirection_Api_Group::route_create' === $red_callable_name ) {
			ual_log_group_add( $request );
		} elseif ( 'Redirection_Api_Group::route_bulk' === $red_callable_name ) {
			$bulk_action = $request->get_param( 'bulk' );
			$bulk_items  = $request->get_param( 'items' );
			if ( is_array( $bulk_items ) ) {
				$bulk_items = array_map( 'intval', $bulk_items );
			}
			if ( empty( $bulk_items ) ) {
				return $response;
			}
			if ( 'enable' === $bulk_action ) {
				$action     = 'redirection_group_enabled';
				$obj_type   = 'Redirection';
				$post_id    = '';
				$post_title = 'Enabled ' . count( $bulk_items ) . ' redirection group(s)';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			} elseif ( 'disable' === $bulk_action ) {
				$action     = 'redirection_group_disabled';
				$obj_type   = 'Redirection';
				$post_id    = '';
				$post_title = 'Disabled ' . count( $bulk_items ) . ' redirection group(s)';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			} elseif ( 'delete' === $bulk_action ) {
				$action     = 'redirection_group_deleted';
				$obj_type   = 'Redirection';
				$post_id    = '';
				$post_title = 'Deleted ' . count( $bulk_items ) . ' redirection group(s)';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
		} elseif ( 'Redirection_Api_Settings::route_save_settings' == $red_callable_name ) {
			$action     = 'route_save_settings';
			$obj_type   = 'Redirection';
			$post_id    = '';
			$post_title = 'Updated redirection options';
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}


if ( ! function_exists( 'ual_log_redirection_add' ) ) {
	/**
	 * Log when a Redirection is added.
	 *
	 * @param WP_REST_Request $req Request.
	 */
	function ual_log_redirection_add( $req ) {
		$action_data = $req->get_param( 'action_data' );

		if ( ! $action_data || ! is_array( $action_data ) ) {
			return false;
		}
		$action     = 'redirection_added';
		$obj_type   = 'Redirection';
		$post_id    = '';
		$post_title = 'Added a redirection for URL ' . $req->get_param( 'url' );
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}

if ( ! function_exists( 'ual_log_redirection_edit' ) ) {
	/**
	 * Log when a Redirection is edit.
	 *
	 * @param WP_REST_Request $req Request.
	 */
	function ual_log_redirection_edit( $req ) {
		$action_data = $req->get_param( 'action_data' );

		if ( ! $action_data || ! is_array( $action_data ) ) {
			return false;
		}
		$redirection_id   = $req->get_param( 'id' );
		$redirection_item = Red_Item::get_by_id( $redirection_id );
		if ( false !== $redirection_item ) {
			$action     = 'redirection_edited';
			$obj_type   = 'Redirection';
			$post_id    = '';
			$post_title = 'Edited redirection for Source URL ' . $redirection_item->get_url() . ' New Source URL' . $req->get_param( 'url' );
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}

if ( ! function_exists( 'ual_log_group_add' ) ) {
	/**
	 * Log when a group is added.
	 *
	 * @param WP_REST_Request $req Request.
	 */
	function ual_log_group_add( $req ) {
		$group_name = $req->get_param( 'name' );

		if ( ! $group_name ) {
			return;
		}
		$action     = 'redirection_group_added';
		$obj_type   = 'Redirection';
		$post_id    = '';
		$post_title = 'Added redirection group ' . $group_name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
