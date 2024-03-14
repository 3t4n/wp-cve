<?php

namespace SocialLinkPages;

class Api extends Singleton {
	const NAMESPACE = 'slp/v1';

	protected function setup() {
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );

	}

	public function register_endpoints() {

		register_rest_route(
			self::NAMESPACE,
			'/page/click',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'page_click' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'page_id'   => [
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
					'button_id' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					]
				],
			] );

//		register_rest_route(
//			self::NAMESPACE,
//			'/pages',
//			[
//				'methods'             => 'GET',
//				'callback'            => [ $this, 'get_pages' ],
//				'permission_callback' => [ $this, 'validate_nonce' ],
//			] );

		register_rest_route(
			self::NAMESPACE,
			'/pages',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'create_page' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'slug'        => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'label'       => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'displayName' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/pages/(?P<page_id>\d+)',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_page' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'page_id' => [
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
					'page'    => [
						'required' => true,
//						'sanitize_callback' => 'sanitize_text_field',
					]
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/user',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_user_app_settings' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'key'   => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'value' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/pages/(?P<page_id>\d+)',
			[
				'methods'             => 'DELETE',
				'callback'            => [ $this, 'delete_page' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'page_id' => [
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/convertkit/lists',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'convertkit_get_lists' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'api' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/mailchimp/lists',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'mailchimp_get_lists' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'api' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			] );

//		register_rest_route(
//			self::NAMESPACE,
//			'/mailchimp/lists/fields',
//			[
//				'methods'             => 'GET',
//				'callback'            => [ $this, 'mailchimp_get_list_merge_fields' ],
//				'permission_callback' => [ $this, 'validate_nonce' ],
//				'args'                => [
//					'api'    => [
//						'required'          => true,
//						'sanitize_callback' => 'sanitize_text_field',
//					],
//					'listId' => [
//						'required'          => true,
//						'sanitize_callback' => 'sanitize_text_field',
//					],
//				],
//			] );

		register_rest_route(
			self::NAMESPACE,
			'/mailchimp/subscribe',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'mailchimp_subscribe' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'page_id'   => [
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
					'button_id' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'email'     => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_email',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/convertkit/subscribe',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'convertkit_subscribe' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'page_id'   => [
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
					'button_id' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'email'     => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_email',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/slugs',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'check_slug' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'slug' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/plugin/contact',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'contact_support' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'message' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_textarea_field',
					],
					'email'   => [
						'sanitize_callback' => 'sanitize_email',
					],
					'subject' => [
						'sanitize_callback' => 'sanitize_text_field',
					]
				],
			] );


		register_rest_route(
			self::NAMESPACE,
			'/contact',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'contact_page' ],
				'permission_callback' => '__return_true',
				'args'                => [
					'page_id' => [
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
					'message' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_textarea_field',
					],
					'email'   => [
						'sanitize_callback' => 'sanitize_email',
					],
				],
			] );

		register_rest_route(
			self::NAMESPACE,
			'/contact/deactivate',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'deactivate_feedback' ],
				'permission_callback' => [ $this, 'validate_nonce' ],
				'args'                => [
					'reason'   => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_textarea_field',
					],
					'comments' => [
						'sanitize_callback' => 'sanitize_textarea_field',
					]
				],
			] );
	}

	public function page_click( $request ) {
		if ( is_user_logged_in() ) {
			wp_send_json_error();
		}


		$page_id = $request->get_param( 'page_id' );

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			return new \WP_Error( '404', __( 'Page not found.', 'social-link-pages' ) );
		}

		// find button
		$button = null;
		foreach ( $page_data->buttons as &$button ) {
			if ( $request->get_param( 'button_id' ) === $button->id ) {
				add_filter(
					Social_Link_Pages()->plugin_name_friendly
					. '_update_page_data_permission_check',
					'__return_true',
					PHP_INT_MAX
				);

				// Update count.
				$button->buttonClicks = empty( $button->buttonClicks ) ? 1
					: $button->buttonClicks + 1;
				Db::instance()->update_page_data( $page_id, $page_data );

				remove_filter(
					Social_Link_Pages()->plugin_name_friendly
					. '_update_page_data_permission_check',
					'__return_true',
					PHP_INT_MAX
				);

				return rest_ensure_response( [ 'success' => true ] );
			}
		}

		return new \WP_Error( '400', __( 'Unknown error.', 'social-link-pages' ) );
	}

	public function mailchimp_subscribe( $request ) {

		$page_id = $request->get_param( 'page_id' );

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			return new \WP_Error( '404', __( 'Page not found.', 'social-link-pages' ) );
		}

		// find button
		$button    = null;
		$button_id = $request->get_param( 'button_id' );

		foreach ( $page_data->buttons as $b ) {
			if ( $button_id === $b->id ) {
				$button = $b;
				break;
			}
		}

		if ( ! $button ) {
			return new \WP_Error( '400', __( 'Button not found.', 'social-link-pages' ) );
		}

		// check api key
		if ( empty( $button->APIKey ) ) {
			return new \WP_Error( '400', __( 'Button error.', 'social-link-pages' ) );
		}

		// get domain from api key
		$APIKeyArr = explode( '-', $button->APIKey );
		$domain    = end( $APIKeyArr );

		if ( empty( $domain ) ) {
			return new \WP_Error( '400', __( 'Invalid API key.', 'social-link-pages' ) );
		}

		$email = $request->get_param( 'email' );

		$body = array(
			'email_address' => $email,
			'status'        => 'subscribed'
		);

		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'a:'
				                                             . $button->APIKey )

			),
			'body'    => json_encode( $body ),
		);

		$response = wp_remote_post(
			sprintf(
				'https://%s.api.mailchimp.com/3.0/lists/%s/members',
				$domain,
				$button->listId
			),
			$args
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();

			return new \WP_Error( '400', "Something went wrong: $error_message" );
		}

		if ( 200 === intval( wp_remote_retrieve_response_code( $response ) ) ) {
			return rest_ensure_response( [ 'success' => true ] );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		return new \WP_Error(
			! empty( $response_body['response']['code'] ) ? $response_body['response']['code'] : 400,
			! empty( $response_body['detail'] ) ? $response_body['detail'] : __( 'Whoops! You could not be subscribed.', 'social-link-pages' )
		);
	}

	public function mailchimp_get_lists( $request ) {

		// get domain from api key
		$APIKey    = $request->get_param( 'api' );
		$APIKeyArr = explode( '-', $APIKey );
		$domain    = end( $APIKeyArr );

		if ( empty( $domain ) ) {
			return new \WP_Error( '400', __( 'Invalid API key.', 'social-link-pages' ) );
		}

		$response = wp_remote_get(
			sprintf( 'https://%s.api.mailchimp.com/3.0/lists', $domain ),
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( 'a:'
					                                             . $APIKey )
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();

			return new \WP_Error( '400', "Something went wrong: $error_message" );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== intval( wp_remote_retrieve_response_code( $response ) ) ) {
			return new \WP_Error(
				! empty( $response_body['response']['code'] ) ? $response_body['response']['code'] : 400,
				! empty( $response_body['detail'] ) ? $response_body['detail'] : __( 'Whoops! You could not be subscribed.', 'social-link-pages' )
			);
		}

		if ( empty( $response_body['lists'] ) ) {
			$response_body['lists'] = [];
		}

		return rest_ensure_response( [
			'success' => true,
			'data'    => $response_body['lists']
		] );
	}

	public function mailchimp_get_list_merge_fields( $request ) {

		// get domain from api key
		$APIKey    = $request->get_param( 'api' );
		$APIKeyArr = explode( '-', $APIKey );
		$domain    = end( $APIKeyArr );

		if ( empty( $domain ) ) {
			return new \WP_Error( '400', __( 'Invalid API key.', 'social-link-pages' ) );
		}

		$listId = $request->get_param( 'listId' );

		$response = wp_remote_get(
			sprintf( 'https://%s.api.mailchimp.com/3.0/lists/%s/merge-fields', $domain, $listId ),
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( 'a:'
					                                             . $APIKey )
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();

			return new \WP_Error( '400', "Something went wrong: $error_message" );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== intval( wp_remote_retrieve_response_code( $response ) ) ) {
			return new \WP_Error(
				! empty( $response_body['response']['code'] ) ? $response_body['response']['code'] : 400,
				! empty( $response_body['detail'] ) ? $response_body['detail'] : __( 'Whoops! You could not be subscribed.', 'social-link-pages' )
			);
		}

		if ( empty( $response_body['merge_fields'] ) ) {
			$response_body['merge_fields'] = [];
		}

		return rest_ensure_response( [
			'success' => true,
			'data'    => $response_body['merge_fields']
		] );
	}


	public function convertkit_subscribe( $request ) {

		$page_id = $request->get_param( 'page_id' );

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			return new \WP_Error( '404', __( 'Page not found.', 'social-link-pages' ) );
		}

		// find button
		$button    = null;
		$button_id = $request->get_param( 'button_id' );

		foreach ( $page_data->buttons as $b ) {
			if ( $button_id === $b->id ) {
				$button = $b;
				break;
			}
		}

		if ( ! $button ) {
			return new \WP_Error( '400', __( 'Button not found.', 'social-link-pages' ) );
		}

		// check api key
		if ( empty( $button->APIKey ) ) {
			return new \WP_Error( '400', __( 'Button error.', 'social-link-pages' ) );
		}

		$args = array(
			'method' => 'POST',
			'body'   => array(
				'email'   => $request->get_param( 'email' ),
				'api_key' => $button->APIKey
			)
		);

		$response = wp_remote_post(
			sprintf(
				'https://api.convertkit.com/v3/forms/%s/subscribe',
				$button->listId
			),
			$args
		);

		return rest_ensure_response( [ 'success' => true ] );
	}

	public function convertkit_get_lists( $request ) {

		// get domain from api key
		$APIKey = $request->get_param( 'api' );

		$response = wp_remote_get(
			sprintf( 'https://api.convertkit.com/v3/forms?api_key=%s', $APIKey )
		);

		if ( is_wp_error( $response ) ) {
			return new \WP_Error( '400', __( 'Sorry, could not connect to ConvertKit.', 'social-link-pages' ) );
		}

		$body = json_decode( $response['body'] );

		if ( ! empty( $body->error ) ) {
			return new \WP_Error(
				$response['response']['code'],
				$body->message ? $body->message : $body->error
			);
		}

		if ( empty( $body->forms ) ) {
			$body->forms = [];
		}

		return rest_ensure_response( [
			'success' => true,
			'data'    => $body->forms
		] );
	}

	public function deactivate_feedback( $request ) {
		$reason = $request->get_param( 'reason' );

		try {
			$subject = stripcslashes( sprintf( '[slp] %s', $reason ) );
			$message = stripcslashes( sprintf(
				"Reason: %s\n\n%s\n\n%s",
				$subject,
				stripcslashes( implode( $request->has_param( 'comments' ) ? $request->get_param( 'comments' ) : '' ) ),
				site_url()
			) );

			wp_mail(
				'support@sociallinkpages.com',
				$subject,
				$message,
				sprintf(
					'From: "%s" <%s>',
					get_option( 'blogname' ),
					get_option( 'admin_email' )
				)
			);

			return rest_ensure_response( [ 'success' => true ] );
		} catch ( Exception $e ) {
			return rest_ensure_response( [ 'success' => false ] );
		}
	}

	public function contact_support( $request ) {
		$message = $request->get_param( 'message' );

		$from_blog = sprintf(
			'From: "%s" <%s>',
			get_option( 'blogname' ),
			get_option( 'admin_email' )
		);

		$from = sprintf(
			'From: %s',
			$request->has_param( 'email' ) ? $request->get_param( 'email' ) : $from_blog
		);

		$subject = $request->has_param( 'subject' ) ? $request->get_param( 'subject' ) : 'Social Link Pages support';

		try {
			wp_mail(
				'support@sociallinkpages.com',
				$subject,
				stripcslashes( sprintf(
					"%s\n\n%s",
					stripcslashes( $message ),
					site_url()
				) ),
				$from
			);

			return rest_ensure_response( [ 'success' => true ] );
		} catch ( Exception $e ) {
			return rest_ensure_response( [ 'success' => false ] );
		}
	}


	public function contact_page( $request ) {
		$page_id = $request->get_param( 'page_id' );

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			return new \WP_Error( '404', __( 'Page not found.', 'social-link-pages' ) );
		}

		if ( ! $page_data->skipEmailNonce ) {
			if ( ! $this->validate_nonce( $request ) ) {
				return new \WP_Error( '400', __( 'Invalid nonce.', 'social-link-pages' ) );
			}
		}

		$button    = null;
		$button_id = $request->get_param( 'button_id' );

		foreach ( $page_data->buttons as $b ) {
			if ( $button_id === $b->id ) {
				$button = $b;
				break;
			}
		}

		if ( ! $button ) {
			return new \WP_Error( '400', __( 'Button not found.', 'social-link-pages' ) );
		}

		wp_mail(
			$button->value,
			/* translators: %s: Email address */
			sprintf( __( 'Email from %s', 'social-link-pages' ), site_url( $page_data->slug ) ),
			$request->get_param( 'message' ),
			array(
				sprintf( 'From: <%s>', $request->get_param( 'email' ) )
			)
		);

		return rest_ensure_response( [ 'success' => true ] );
	}

	public function check_slug( $request ) {
		$slug = $request->get_param( 'slug' );

		return rest_ensure_response( [
			'data' => [
				'success'   => true,
				'slug'      => $slug,
				'is_unique' => Db::instance()
				                 ->is_slug_unique( sanitize_title( $slug ) )
			]
		] );
	}

	public function get_pages( $request ) {
		return rest_ensure_response( [
			'success' => true,
			'data'    => Admin::instance()->get_all_pages()
		] );
	}

	public function create_page( $request ) {

		$post_id = Db::instance()->create_page( $_POST ); // Sanitized in Db->sanitize_page_data.

		if ( false === $post_id ) {
			return new \WP_Error( '400', __( 'Page could not be created.', 'social-link-pages' ) );
		}

		$page_data = Db::instance()->page_data_from_post( $post_id );

		return rest_ensure_response( [ 'success' => true, 'data' => $page_data ] );
	}

	public function update_page( $request ) {
		$post_id = $request->get_param( 'page_id' );

		$success = Db::instance()->update_page_data(
			$post_id,
			$request->get_param( 'page' )
		);

		return rest_ensure_response( [ 'success' => $success ] );
	}

	public function delete_page( $request ) {


		$post_id = $request->get_param( 'page_id' );
		$post    = get_post( $post_id );

		if ( ! $post ) {
			return new \WP_Error( '400', __( 'Page not found.', 'social-link-pages' ) );
		}

		$permission_check = apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_delete_page_permission_check',
			true,
			$post
		);

		if ( ! $permission_check ) {
			return new \WP_Error( '400', __( 'You do not have permission to do that.', 'social-link-pages' ) );
		}

		wp_trash_post( $post_id );

		return rest_ensure_response( [ 'success' => true ] );
	}

	public function update_user_app_settings( $request ) {

		User::instance()->update_current_user_settings( [
			$request->get_param( 'key' ) => $request->get_param( 'value' )
		] );

		return rest_ensure_response( [ 'success' => true ] );
	}

//	public function validate_user() {
//		// Add your custom logic to validate the current user
//		// For example, check if the user is logged in and their capabilities
//		return is_user_logged_in();
//	}

	public function validate_nonce( $request ) {
		if ( empty( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
			return false;
		}

		return wp_verify_nonce( $_SERVER['HTTP_X_WP_NONCE'], 'wp_rest' );
	}
}