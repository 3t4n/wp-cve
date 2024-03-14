<?php
/**
 * Notifies Clariti based on a variety of WordPress events.
 *
 * @package Clariti
 */

namespace Clariti;

/**
 * Notifies Clariti based on a variety of WordPress events.
 */
class Notifier {

	/**
	 * String used for the create action.
	 *
	 * @var string
	 */
	const CREATE_ACTION = 'create';

	/**
	 * String used for the update action.
	 *
	 * @var string
	 */
	const UPDATE_ACTION = 'update';

	/**
	 * String used for the delete action.
	 *
	 * @var string
	 */
	const DELETE_ACTION = 'delete';

	/**
	 * String use for the draft action
	 *
	 * @var string
	 */
	const DRAFT_ACTION = 'draft';

	/**
	 * Default host for Clariti Calls
	 *
	 * @var string
	 */
	const API_HOST_DEFAULT = 'app.clariti.com';

	/**
	 * Hook API endpoint
	 *
	 * @var string
	 */
	const API_HOOK_ENDPOINT = '/wp-hook-v1';

	/**
	 * Inform Clariti when the API key option is added.
	 *
	 * When an API key is first added, a secret is generated and sent with
	 * the API key to Clariti to establish a connection.
	 *
	 * @param string $option    Name of the option.
	 * @param string $new_value New option value.
	 */
	public static function action_added_option( $option, $new_value ) {
		if ( Admin::API_KEY_OPTION !== $option ) {
			return;
		}

		try {
			$secret   = Admin::set_secret();
			$response = self::send_clariti_admin_post( $new_value, true, array( 'secret' => $secret ) );

			if ( $response['ok'] ) {
				Admin::send_admin_notification( 'clariti-added-option', 'clariti-added-option-success', 'Successfully connected to Clariti', 'success' );
			}
		} catch ( \Exception $exception ) {
			$error = explode( ' - ', $exception->getMessage() );

			if ( 601 === (int) $error[0] || 403 === (int) $error[0] ) {
				Admin::send_admin_notification( 'clariti-updated-option', 'clariti-updated-option-error', 'Connection not established. Please check your site settings at Clariti to reconnect the plugin.', 'error' );
			} else {
				Admin::send_admin_notification( 'clariti-updated-option', 'clariti-updated-option-error', 'Unable to connect to Clariti: ' . $exception->getMessage(), 'error' );
			}

			error_log( 'CLARITI:ERROR - action_added_option - ' . $exception->getMessage() );
		}
	}

	/**
	 * Inform Clariti when the API key option is updated.
	 *
	 * When an API key is changed, the secret is refreshed and sent with the
	 * API key to Clariti to re-establish a connection. If the API key is an
	 * empty string, the secret is removed as well.
	 *
	 * @param string $option    Name of the option.
	 * @param string $old_value Old option value.
	 * @param string $new_value New option value.
	 */
	public static function action_updated_option( $option, $old_value, $new_value ) {
		if ( Admin::API_KEY_OPTION !== $option ) {
			return;
		}

		// If the new API key is an empty string, remove the secret entirely.
		if ( '' === $new_value ) {
			Admin::clear_secret();
			return;
		}

		// This is a new API key, so set a new secret and notify Clariti.
		Admin::set_secret();
		self::send_secret_to_clariti( $new_value );
	}

	/**
	 * Establish a connection with Clarity by sending the generated secret.
	 *
	 * @param string $key API Key from Clariti.
	 *
	 * @return void
	 */
	public static function send_secret_to_clariti( $key ) {
		try {
			$response = self::send_clariti_admin_post( $key, true, array( 'secret' => Admin::get_secret() ) );
			if ( $response['ok'] ) {
				Admin::send_admin_notification( 'clariti-updated-option', 'clariti-updated-option-success', 'Successfully connected to Clariti', 'success' );
			}
		} catch ( \Exception $exception ) {
			$error = explode( ' - ', $exception->getMessage() );

			if ( 601 === (int) $error[0] || 403 === (int) $error[0] ) {
				Admin::send_admin_notification( 'clariti-updated-option', 'clariti-updated-option-error', 'Connection not established. Please check your site settings at Clariti to reconnect the plugin.', 'error' );
			} else {
				Admin::send_admin_notification( 'clariti-updated-option', 'clariti-updated-option-error', 'Unable to connect to Clariti: ' . $exception->getMessage(), 'error' );
			}

			error_log( 'CLARITI:ERROR - action_updated_option - ' . $exception->getMessage() );
		}
	}

	/**
	 * Inform Clariti when a post is updated.
	 *
	 * @param string $new_status New post status.
	 * @param string $old_status Old post status.
	 * @param object $post       Post object.
	 */
	public static function action_transition_post_status( $new_status, $old_status, $post ) {
		if ( ! in_array( $post->post_type, clariti_get_supported_post_types(), true ) ) {
			return;
		}

		if ( 'publish' === $new_status && 'publish' !== $old_status ) {
			$action = self::CREATE_ACTION;
		} elseif ( 'draft' === $new_status && 'publish' === $old_status ) {
			// note it's important to have this before the delete action
			// as the !== publish will catch draft, but we want to have draft
			// specifically.
			$action = self::DRAFT_ACTION;
		} elseif ( 'publish' !== $new_status && 'publish' === $old_status ) {
			$action = self::DELETE_ACTION;
		} elseif ( 'publish' === $new_status && 'publish' === $old_status ) {
			$action = self::UPDATE_ACTION;
		} else {
			return;
		}

		self::send_event_to_clariti( $post->post_type, $action, $post->ID );
	}

	/**
	 * Inform Clariti when a post is trashed.
	 *
	 * @param integer $post_id ID for the trashed post.
	 */
	public static function action_wp_trash_post( $post_id ) {
		$post = get_post( $post_id );
		if ( ! in_array( $post->post_type, clariti_get_supported_post_types(), true ) ) {
			return;
		}
		self::send_event_to_clariti( $post->post_type, self::DELETE_ACTION, $post->ID );
	}

	/**
	 * Inform Clariti when a post is deleted.
	 *
	 * @param integer $post_id ID for the deleted post.
	 */
	public static function action_before_delete_post( $post_id ) {
		$post = get_post( $post_id );
		if ( ! in_array( $post->post_type, clariti_get_supported_post_types(), true ) ) {
			return;
		}
		self::send_event_to_clariti( $post->post_type, self::DELETE_ACTION, $post->ID );
	}

	/**
	 * Inform Clariti when a term is created.
	 *
	 * @param integer $term_id  ID for the created term.
	 * @param integer $tt_id    Term taxonomy ID.
	 * @param string  $taxonomy Taxonomy slug.
	 */
	public static function action_created_term( $term_id, $tt_id, $taxonomy ) {
		if ( ! in_array( $taxonomy, array( 'category', 'post_tag' ), true ) ) {
			return;
		}
		$resource = 'post_tag' === $taxonomy ? 'tag' : $taxonomy;
		self::send_event_to_clariti( $resource, self::CREATE_ACTION, $term_id );
	}

	/**
	 * Inform Clariti when a term is updated.
	 *
	 * @param integer $term_id  ID for the created term.
	 * @param integer $tt_id    Term taxonomy ID.
	 * @param string  $taxonomy Taxonomy slug.
	 */
	public static function action_edited_term( $term_id, $tt_id, $taxonomy ) {
		if ( ! in_array( $taxonomy, array( 'category', 'post_tag' ), true ) ) {
			return;
		}
		$resource = 'post_tag' === $taxonomy ? 'tag' : $taxonomy;
		self::send_event_to_clariti( $resource, self::UPDATE_ACTION, $term_id );
	}

	/**
	 * Inform Clariti when a term is deleted.
	 *
	 * @param integer $term_id  ID for the created term.
	 * @param integer $tt_id    Term taxonomy ID.
	 * @param string  $taxonomy Taxonomy slug.
	 */
	public static function action_delete_term( $term_id, $tt_id, $taxonomy ) {
		if ( ! in_array( $taxonomy, array( 'category', 'post_tag' ), true ) ) {
			return;
		}
		$resource = 'post_tag' === $taxonomy ? 'tag' : $taxonomy;
		self::send_event_to_clariti( $resource, self::DELETE_ACTION, $term_id );
	}

	/**
	 * Inform Clariti when an approved comment is updated.
	 *
	 * @param integer    $id      The comment ID.
	 * @param WP_Comment $comment Comment object.
	 */
	public static function action_wp_insert_comment( $id, $comment ) {
		if ( 1 !== (int) $comment->comment_approved ) {
			return;
		}
		self::send_event_to_clariti( 'comment', self::CREATE_ACTION, $comment->comment_post_ID );
	}

	/**
	 * Inform Clariti when a comment is approved or unapproved.
	 *
	 * @param int|string $new_status The new comment status.
	 * @param int|string $old_status The old comment status.
	 * @param object     $comment    The comment data.
	 */
	public static function action_transition_comment_status( $new_status, $old_status, $comment ) {

		if ( 'approved' === $new_status && 'approved' !== $old_status ) {
			$action = self::CREATE_ACTION;
		} elseif ( 'approved' !== $new_status && 'approved' === $old_status ) {
			$action = self::DELETE_ACTION;
		} elseif ( 'approved' === $new_status && 'approved' === $old_status ) {
			$action = self::UPDATE_ACTION;
		} else {
			return;
		}

		self::send_event_to_clariti( 'comment', $action, $comment->comment_post_ID );
	}

	/**
	 * Inform Clariti when specific post meta is updated.
	 *
	 * @param int    $meta_id   The meta ID.
	 * @param int    $object_id The object (post) ID.
	 * @param string $meta_key  The meta key.
	 */
	public static function action_updated_postmeta( $meta_id, $object_id, $meta_key ) {
		if ( in_array(
			$meta_key,
			array(
				'average_rating', // Tasty Recipes, average.
				'total_reviews', // Tasty Recipes, total.
				'wprm_rating_average', // WP Recipe Maker, average.
				'wprm_rating_count', // WP Recipe Maker, total.
			),
			true
		) ) {
			// If this PHP process has already sent an update event for recipe ratings, don't send another.
			if ( true === apply_filters( 'clariti_sent_ratings_update_event_' . (int) $object_id, false ) ) {
				return;
			}

			$post = get_post( $object_id );

			// Handle WP Recipe Maker updates.
			if ( 'wprm_recipe' === $post->post_type ) {
				$parent_post_id = (int) get_post_meta( $object_id, 'wprm_parent_post_id', true );

				// We only have the recipe, not where it is used, so avoid sending
				// an ambigous event.
				if ( ! $parent_post_id ) {
					return;
				}

				$post = get_post( $parent_post_id );

				if ( ! $post || 'publish' !== $post->post_status ) {
					return;
				}

				self::send_event_to_clariti( $post->post_type, self::UPDATE_ACTION, $object_id );

				// Set a flag to avoid duplicate events.
				add_filter( 'clariti_sent_ratings_update_event_' . (int) $object_id, '__return_true' );
			}

			/**
			 * Handle Tasty Recipe updates.
			 *
			 * Tasty Recipes provides this hook, but it fires on every recipe update, whether or
			 * not the total or average ratings actually changes. We hook in here so that the
			 * event is only sent when one of the meta values is modified.
			 */
			if ( 'tasty_recipe' === $post->post_type ) {
				add_action( 'tasty_recipes_updated_recipe_rating', array( 'Clariti\Notifier', 'action_tasty_recipes_updated_recipe_rating' ), 10, 2 );
				return;
			}
		}
	}

	/**
	 * Inform Clariti when a Tasty Recipes recipe rating is updated.
	 *
	 * @param object  $recipe  Recipe object.
	 * @param integer $post_id ID for the recipe's post.
	 */
	public static function action_tasty_recipes_updated_recipe_rating( $recipe, $post_id ) {
		// Remove self to avoid multiple events.
		remove_action( 'tasty_recipes_updated_recipe_rating', array( 'Clariti\Notifier', 'action_tasty_recipes_updated_recipe_rating' ) );

		$post = get_post( $post_id );

		if ( ! $post || 'publish' !== $post->post_status ) {
			return;
		}

		self::send_event_to_clariti( $post->post_type, self::UPDATE_ACTION, $post->ID );

		// Set a flag to avoid duplicate events.
		add_filter( 'clariti_sent_ratings_update_event_' . (int) $post->ID, '__return_true' );
	}

	/**
	 * Notifies Clariti of some event.
	 *
	 * For an event to send, the API key and secret must have previously been
	 * set and sent to Clariti to establish a connection.
	 *
	 * @param string  $updated_resource Resource being updated.
	 * @param string  $action           Action happening to the resource.
	 * @param integer $id               ID for the resource.
	 * @throws \Exception Thrown if we get a WP Error as a response.
	 */
	public static function send_event_to_clariti( $updated_resource, $action, $id ) {

		$key = Admin::get_api_key();

		// A verified secret and API key must be available to send events to Clariti.
		if ( ! Admin::get_secret() || ! $key ) {
			return;
		}

		try {
			$payload = wp_json_encode(
				array(
					'key'      => $key,
					'action'   => $action,
					'id'       => $id,
					'resource' => $updated_resource,
				)
			);

			self::send_clariti_payload( $payload );
		} catch ( \Exception $exception ) {
			error_log( 'CLARITI:ERROR - action_updated_option - ' . $exception->getMessage() );
		}
	}

	/**
	 * Gets the URL to use for Clariti requests.
	 *
	 * @return string
	 */
	protected static function get_clariti_url() {
		if ( defined( 'CLARITI_API_HOST' ) ) {
			$host = CLARITI_API_HOST;
		} elseif ( get_option( Admin::API_HOST_OPTION, '' ) ) {
			$host = get_option( Admin::API_HOST_OPTION, '' );
		} elseif ( ! empty( $_POST[ Admin::API_HOST_OPTION ] ) && ! Admin::is_valid_api_host( $_POST[ Admin::API_HOST_OPTION ] ) ) {
			$host = $_POST[ Admin::API_HOST_OPTION ];
		} else {
			$host = self::API_HOST_DEFAULT;
		}
		return 'https://' . trailingslashit( $host . self::API_HOOK_ENDPOINT );
	}

	/**
	 * Send our post events to the API for settings updates
	 *
	 * @param string $key         API Key.
	 * @param string $enabled     API status 'true|false'.
	 * @param array  $extra_params Key value pares of items to be passed into the body of the post.
	 * @return array
	 * @throws \Exception Throw exceptions for problem cases.
	 */
	public static function send_clariti_admin_post( $key, $enabled, $extra_params = array() ) {
		// Create Payload.
		$default_params = array(
			'key'    => $key,
			'enable' => $enabled,
		);
		$params         = array_merge( $default_params, $extra_params );
		$payload        = wp_json_encode( $params );

		$data = self::send_clariti_payload( $payload );

		return $data;
	}

	/**
	 * Send a JSON encoded payload to Clariti and handle the response.
	 *
	 * @param string $payload JSON encoded payload.
	 * @return array Response data.
	 * @throws \Exception Throw exceptions for problem cases.
	 */
	public static function send_clariti_payload( $payload ) {
		$response = wp_remote_post(
			self::get_clariti_url(),
			array(
				'body'    => self::create_jwt( $payload ),
				'headers' => array( 'Content-Type' => 'text/plain' ),
				'timeout' => 45,
			)
		);

		if ( $response instanceof \WP_Error ) {
			throw new \Exception( $response->get_error_message() );
		}

		$data = json_decode( $response['body'], true );

		if ( ! $data || is_wp_error( $data ) ) {
			throw new \Exception( 'Could not read response from Clariti' );
		}

		if ( false === ( (bool) $data['ok'] ?? false ) ) {
			// If Clariti replies with a 601 error code, clear the secret and
			// prevent further requests until a new API key is added.
			if ( 601 === ( (int) $data['error']['code'] ?? null ) ) {
				Admin::clear_secret();
			}

			throw new \Exception( "{$data['error']['code']} - {$data['error']['message']}" );
		}

		return $data;
	}

	/**
	 * Helper function to create our JWTs.
	 *
	 * @param string $payload       Payload as a json string.
	 * @param array  $header         Header values for our JWT, empty will set a normal default.
	 * @param bool   $only_signature  A short circuit for getting our base 64 encoded signature DRY.
	 * @return string               Our JWT.
	 * @throws \Exception           Could be thrown via Admin::get_secret().
	 */
	public static function create_jwt( $payload, $header = array(), $only_signature = false ) {
		$secret = Admin::get_secret();

		if ( empty( $header ) ) {
			// Create Header.
			$header = wp_json_encode(
				array(
					'typ' => 'JWT',
					'alg' => 'HS256',
				)
			);
		}

		// Encode Header to Base64Url String.
		$base_64_url_header = str_replace( array( '+', '/', '=' ), array( '-', '_', '' ), base64_encode( $header ) );

		// Encode Payload to Base64Url String.
		$base_64_url_payload = str_replace( array( '+', '/', '=' ), array( '-', '_', '' ), base64_encode( $payload ) );

		// Create Signature Hash.
		$signature = hash_hmac( 'sha256', $base_64_url_header . '.' . $base_64_url_payload, $secret, true );

		// Encode Signature to Base64Url String.
		$base_64_url_signature = str_replace( array( '+', '/', '=' ), array( '-', '_', '' ), base64_encode( $signature ) );

		if ( $only_signature ) {
			return $base_64_url_signature;
		}

		// Create JWT.
		return $base_64_url_header . '.' . $base_64_url_payload . '.' . $base_64_url_signature;
	}

	/**
	 * Clear the secret and message the user when the secret is manually
	 * cleared in the advanced admin screen.
	 *
	 * @return void
	 */
	public static function clear_secret_option() {
		Admin::clear_secret();
		Admin::send_admin_notification( 'clariti-updated-option', 'clariti-updated-option-success', 'Clariti Secret cleared!', 'success' );
		$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
		wp_safe_redirect( $goback );
		exit;
	}
}
