<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Frontend;

use QuadLayers\IGG\Api\Rest\Endpoints\Base as Base;
use QuadLayers\IGG\Models\Account as Models_Account;
use QuadLayers\IGG\Api\Fetch\Personal\User_Profile\Get as Api_Fetch_Personal_User_Profile;
use QuadLayers\IGG\Api\Fetch\Business\User_Profile\Get as Api_Fetch_Business_User_Profile;
use QuadLayers\IGG\Utils\Cache as Cache;

class User_Profile extends Base {

	protected static $route_path = 'frontend/user-profile';

	protected $profile_cache_engine;
	protected $profile_cache_key = 'profile';

	public function callback( \WP_REST_Request $request ) {

		$body = json_decode( $request->get_body(), true );

		if ( ! isset( $body['feedSettings'] ) ) {
			$message = array(
				'message' => esc_html__( 'Bad Request, feed settings not found.', 'insta-gallery' ),
				'code'    => '400',
			);
			return $this->handle_response( $message );
		}
		$feed = $body['feedSettings'];

		if ( ! isset( $feed['account_id'] ) ) {
			$message = array(
				'message' => esc_html__( 'Bad Request, feed account id not found.', 'insta-gallery' ),
				'code'    => '400',
			);
			return $this->handle_response( $message );
		}

		$account_id = trim( $feed['account_id'] );

		// Get cache data and return it if exists.
		// Set prefix to cache.
		$profile_complete_prefix = "{$this->profile_cache_key}_{$account_id}";

		$this->profile_cache_engine = new Cache( 6, true, $profile_complete_prefix );

		// Get cached user profile data.
		$response = $this->profile_cache_engine->get( $profile_complete_prefix );

		// Check if $response has data, if it have return it.
		if ( ! QLIGG_DEVELOPER && ! empty( $response['response'] ) ) {
			return $response['response'];
		}

		$models_account = new Models_Account();
		$account        = $models_account->get_account( $account_id );

		// Check if exist an access_token and access_token_type related to id setted by param, if it is not return error.
		if ( ! isset( $account['access_token'], $account['access_token_type'] ) ) {
			return $this->handle_response(
				array(
					'code'    => 412,
					'message' => sprintf( esc_html__( 'Account id %s not found to fetch user profile.', 'insta-gallery' ), $account_id ),
				)
			);
		}

		$access_token = $account['access_token'];

		// Query to Api_Fetch_Personal_User_Profile if access_token_type is 'PERSONAL'.
		if ( $account['access_token_type'] == 'PERSONAL' ) {
			$personal_user_profile = new Api_Fetch_Personal_User_Profile();

			// Get user profile data.
			$response = $personal_user_profile->get_data( $access_token );

			// Check if response is an error and return it.
			if ( isset( $response['message'] ) && isset( $response['code'] ) ) {
				return $this->handle_response( $response );
			}

			// Check if response is not an error but neither a valid one.
			if ( empty( $response['id'] ) ) {

				$message = array(
					'code'    => 500,
					'message' => 'Ups something went wrong. Please try again.',
				);
				return $this->handle_response( $message );
			}

			// Update user profile data cache and return it.
			if ( ! QLIGG_DEVELOPER ) {
				$this->profile_cache_engine->update( $profile_complete_prefix, $response );
			}

			return $this->handle_response( $response );
		}
		// Query to Api_Fetch_Business_User_Profile.

		$business_user_profile = new Api_Fetch_Business_User_Profile();

		// Get user profile data.
		$response = $business_user_profile->get_data( $access_token, $account_id );

		// Check if response is an error and return it.
		if ( isset( $response['message'], $response['code'] ) ) {
			return $this->handle_response( $response );
		}

		// Update user profile data cache and return it.
		if ( ! QLIGG_DEVELOPER ) {
			$this->profile_cache_engine->update( $profile_complete_prefix, $response );
		}

		return $this->handle_response( $response );
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}

	public function get_rest_permission() {
		return true;
	}

}
