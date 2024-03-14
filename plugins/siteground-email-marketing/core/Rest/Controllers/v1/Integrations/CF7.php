<?php
namespace SG_Email_Marketing\Rest\Controllers\v1\Integrations;

use SG_Email_Marketing\Traits\Rest_Trait;
use SG_Email_Marketing\Loader\Loader;
use SG_Email_Marketing\Integrations\ThirdParty\CF7 as CF7_Integration;

/**
 * Class responsible for the CF7 REST endpoints.
 */
class CF7 {
	use Rest_Trait;

	/**
	 * List of all of the forms' ids.
	 *
	 * @var array
	 */
	public $forms;

	/**
	 * List of all of the possible post meta keys
	 *
	 * @var array
	 */
	public $post_meta = array(
		CF7_Integration::CF7_TOGGLE_META,
		CF7_Integration::CF7_SELECTED_LABELS_META,
		CF7_Integration::CF7_CHECKBOX_META,
		CF7_Integration::CF7_CHECKBOX_LABEL_META
	);

	public function __construct() {
		$this->forms = $this->get_all_forms();
	}
	/**
	 * Registers CF7 Integration REST routes.
	 *
	 * @since 1.1.0
	 */
	public function register_rest_routes() {
		// Add the GET request.
		register_rest_route(
			$this->rest_namespace,
			'/cf7/(?P<id>[\w]+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_form_settings' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'id' => array(
							'validate_callback' => function ( $id, $request, $key ) {
								return in_array( (int) $id, array_values( $this->forms ) );
							},
						),
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_form_settings' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'id' => array(
						'validate_callback' => function ( $id, $request, $key ) {
							return in_array( $id, $this->forms );
						},
					),
				),
			),
		);
	}

	/**
	 * Retrieves form meta data
	 *
	 * @since 1.1.0
	 *
	 * @param  \WP_REST_Request $request The incoming request
	 *
	 * @return array
	 */
	public function get_form_settings( $request ) {
		$id = (int) $request->get_param('id');

		if ( empty( $id ) ) {
			return array();
		}

		$response = array();

		foreach( $this->post_meta as $key ) {
			if ( CF7_Integration::CF7_SELECTED_LABELS_META === $key ) {
				$labels_response = Loader::get_instance()->mailer_api->get_labels();
				$response['labels'] = array(
					'selected' => get_post_meta( $id, $key, array() ),
					'default'  => $labels_response['data'],
				);
				continue;
			}
			$response[ $key ] = get_post_meta( $id, $key, '' );
		}

		return $response;
	}

	public function get_all_forms() {
		$cf7_forms = get_posts( array( 'post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1 ) );
		$post_ids = wp_list_pluck( $cf7_forms , 'ID' );
		return array_values( $post_ids );
	}

	/**
	 * Updates form meta data
	 *
	 * @since 1.1.0
	 *
	 * @param  \WP_REST_Request $request The incoming request
	 *
	 * @return array
	 */
	public function update_form_settings( $request ) {
		$body = $request->get_json_params();
		$id = $request->get_param('id');

		foreach ( $body as $key => $value ) {
			if ( 'labels' === $key ) {
				$labels_response = Loader::get_instance()->mailer_api->get_labels();
				foreach ( $value['selected'] as $label_key => $label ) {
					if( ! in_array( $label, $labels_repsonse['data'] ) ) {
						unset( $value['selected'][$label_key] );
					}
				}

				update_post_meta( $id, CF7_Integration::CF7_SELECTED_LABELS_META, $value['selected'] );
				continue;
			}

			if ( in_array( $key, $this->post_meta ) ) {
				update_post_meta( $id, $key, $value );
			}
		}

		CF7_Integration::maybe_update_post_content( (int) $id );
		return $this->get_form_settings( $request );
	}
}