<?php
namespace SG_Email_Marketing\Rest\Controllers\v1\Pages;

use WP_REST_Posts_Controller;
use SG_Email_Marketing\Traits\Rest_Trait;

/**
 * Class responsible for the Forms plugin page.
 */
class Forms extends WP_REST_Posts_Controller {
	use Rest_Trait;

	/**
	 * Post Type
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'sg_form';

	/**
	 * The Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct( $this->post_type );
		$this->namespace = $this->rest_namespace;
		$this->rest_base = 'forms';
	}

	/**
	 * Prepare the item for creation.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return stdClass|WP_Error Post object or WP_Error.
	 */
	protected function prepare_item_for_database( $request ) {
		$params = $request->get_params();
		$body   = $params['body'];

		if ( ! isset( $body ) ) {
			return new \WP_Error(
				'message',
				__( 'Missing body', 'siteground-email-marketing' ),
				array( 'status' => 400 )
			);
		}

		$id = isset( $params['id'] ) ? $params['id'] : 0;

		// Bail if form with the same name exists.
		if ( $this->form_title_exists( $body['settings']['form_title'], $id ) ) {
			return new \WP_Error(
				'error',
				__( 'Name already exists.', 'siteground-email-marketing' ),
				array( 'status' => 403 )
			);
		}

		$request['content'] = wp_json_encode( $body );
		$request['status']  = 'publish';
		$request['title']   = $body['settings']['form_title'];

		unset( $request['body'] );

		return parent::prepare_item_for_database( $request );
	}

	/**
	 * Check if we have a form with this title.
	 *
	 * @since 1.0.0
	 *
	 * @param  string  $title The user specified title.
	 * @param  integer $id    The form id if is edit.
	 *
	 * @return boolean true/false Whether the title exists.
	 */
	public function form_title_exists( $title, $id ) {
		global $wpdb;

		$posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'sg_form' AND post_status = 'publish' LIMIT 1",
				$title,
			),
			ARRAY_A
		);

		// No form with that name exists.
		if ( ! $posts ) {
			return false;
		}

		// Allow changes if it is edit bail otherwise.
		return intval( $posts[0]['ID'] ) === $id ? false : true;
	}

	/**
	 * Prepare the item for response.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post         $item    Post object.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array  The modified response.
	 */
	public function prepare_item_for_response( $item, $request ) {
		$body = json_decode( $request->get_body(), true );

		return array(
			'ID'           => $item->ID,
			'body'         => json_decode( $item->post_content ),
			'date_created' => strtotime( $item->post_date ),
			'meta'         => $body['meta'],
		);
	}
}
