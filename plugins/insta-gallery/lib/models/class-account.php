<?php

namespace QuadLayers\IGG\Models;

use QuadLayers\IGG\Models\Base as Models_Base;
use QuadLayers\IGG\Api\Fetch\Business\Access_Token\Refresh as Api_Fetch_Business_Refresh_Access_Token;
use QuadLayers\IGG\Api\Fetch\Personal\Access_Token\Refresh as Api_Fetch_Personal_Refresh_Access_Token;

/**
 * Models_Account Class
 */
class Account extends Models_Base {

	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'insta_gallery_accounts';

	/**
	 * Max access token renew attemps
	 *
	 * @var integer
	 */
	protected static $access_token_max_renew_attemps = 3;

	/**
	 * Function to calculate expiration date based on current time and expires_in property
	 *
	 * @param int $expires_in Time lapse to expire.
	 * @return int
	 */
	public function calculate_expiration_date( $expires_in ) {
		return strtotime( current_time( 'mysql' ) ) + $expires_in - 1;
	}

	/**
	 * Function to clean token
	 *
	 * @param string $maybe_dirty
	 * @return string
	 */
	protected function clean_token( $maybe_dirty ) {
		if ( substr_count( $maybe_dirty, '.' ) < 3 ) {
			return str_replace( '634hgdf83hjdj2', '', $maybe_dirty );
		}

		$parts     = explode( '.', trim( $maybe_dirty ) );
		$last_part = $parts[2] . $parts[3];
		$cleaned   = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );

		return $cleaned;
	}

	/**
	 * Function to renew access token
	 *
	 * @param string  $access_token Account access_token.
	 * @param integer $access_token_renew_attempts Account access_token renew attemps.
	 * @return array
	 */
	public function renew_access_token( $access_token, $access_token_renew_attempts = 0 ) {

		$business_refresh_access_token = new Api_Fetch_Business_Refresh_Access_Token();
		$personal_refresh_access_token = new Api_Fetch_Personal_Refresh_Access_Token();

		if ( substr( $access_token, 0, 2 ) === 'IG' ) {
			return $personal_refresh_access_token->get_data( $access_token, $access_token_renew_attempts );
		}

		return $business_refresh_access_token->get_data( $access_token, $access_token_renew_attempts );
	}

	/**
	 * Function to increase access_token_renew_attempts
	 *
	 * @param array $account Account to increase access_token_renew_attempts property.
	 * @return void
	 */
	protected function access_token_renew_attemps_increase( $account ) {
		$account['access_token_renew_attempts'] = intval( $account['access_token_renew_attempts'] ) + 1;
		$this->update( $account );
	}

	/**
	 * Function to check if access_token_renew_attempts property exceded access_token_max_renew_attemps
	 *
	 * @param array $account Account to check if access_token_renew_attempts property is exceded.
	 * @return boolean
	 */
	protected function access_token_renew_attemps_exceded( $account ) {
		if ( intval( $account['access_token_renew_attempts'] ) > self::$access_token_max_renew_attemps ) {
			return true;
		}
		return false;
	}

	/**
	 * Function to validate account's access_token
	 *
	 * @param array $account Account to validate access_token.
	 * @return array|false
	 */
	protected function is_access_token_renewed( $account ) {

		$is_access_token_about_to_expire = $this->is_access_token_expired( $account );

		/**
		 * Check if account is about to expire
		 */
		if ( ! $is_access_token_about_to_expire ) {
			return true;
		}

		if ( $this->access_token_renew_attemps_exceded( $account ) ) {
			return false;
		}

		$response = $this->renew_access_token( $account['access_token'], $account['access_token_renew_attempts'] );

		/**
		 * Validate response
		 */
		if ( isset( $response['error'] ) || ! isset( $response['expires_in'] ) || ! isset( $response['access_token'] ) ) {
			$this->access_token_renew_attemps_increase( $account );
			return false;
		}

		if ( $account['access_token_expiration_date'] >= $this->calculate_expiration_date( $response['expires_in'] ) ) {
			return false;
		}

		$account['access_token_renew_attempts']  = 0;
		$account['access_token']                 = $response['access_token'];
		$account['access_token_expiration_date'] = $this->calculate_expiration_date( $response['expires_in'] );
		$account                                 = $this->update( $account );

		if ( $account ) {
			return $account;
		}

		return false;
	}

	/**
	 * Function to check if account access_token is expired
	 *
	 * @param array $account Account to check it's access_token expiration.
	 * @return boolean
	 */
	protected function is_access_token_expired( $account ) {

		if ( ( $account['access_token_expiration_date'] - strtotime( current_time( 'mysql' ) ) ) < 0 ) {
			return true;
		}

		return false;
	}

	/**
	 * Function to get account access_token type
	 *
	 * @param array $account
	 * @return string
	 */
	protected function get_token_type( $account ) {
		if ( substr( $account['access_token'], 0, 2 ) === 'IG' ) {
			return 'PERSONAL';
		}
		return 'BUSINESS';
	}

	/* CRUD */

	/**
	 * Function to get default args
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'id'                           => '',
			'username'                     => '',
			'profile_picture_url'          => '',
			'access_token'                 => '',
			'access_token_type'            => '',
			'access_token_expiration_date' => 0,
			'access_token_renew_attempts'  => 0,
		);
	}

	/**
	 * Function to get account by id
	 *
	 * @param int $id Account's id to look for.
	 * @return array|false
	 */
	public function get_account( $id ) {

		$accounts = $this->get();

		if ( ! isset( $accounts[ $id ] ) ) {
			return false;
		}

		if ( $this->is_access_token_renewed( $accounts[ $id ] ) ) {
			$accounts = $this->get();
		}

		return $accounts[ $id ];
	}

	/**
	 * Function to create new account
	 *
	 * @param array $account_data New account data.
	 * @return array|false
	 */
	public function create( $account_data ) {

		// if account_data not exist or not set, return error{ error: code, message: 'text'}.
		if ( empty( $account_data ) ) {
			return array(
				'error'   => 404,
				'message' => 'Account data is empty/null',
			);
		}

		// if all attributes exist, return save($account_data). (Case Add Personal Account button)
		if ( isset( $account_data['id'] ) &&
		isset( $account_data['access_token'] ) &&
		isset( $account_data['expires_in'] ) &&
		isset( $account_data['access_token_type'] )
		) {
			$account_data['access_token']                 = $this->clean_token( $account_data['access_token'] );
			$account_data['access_token_renew_atemps']    = 0;
			$account_data['access_token_expiration_date'] = $this->calculate_expiration_date( $account_data['expires_in'] );
			$account_data['access_token_expires_in']      = $account_data['expires_in'];
			$account_data['access_token_type']            = $this->get_token_type( $account_data );

			return $this->save( $account_data );
		}

		// if only exist($account_data['refresh_token']) return renew_access_token($account_data). (Case Button not working? button)
		$response = $this->renew_access_token( $account_data['access_token'] );

		if ( ! empty( $response['error'] ) && ! empty( $response['message'] ) ) {
			return array(
				'error'   => $response['error'],
				'message' => $response['message'],
			);
		}

		// if ( ! isset( $response['access_token'] ) && ! isset( $response['access_token_type'] ) && ! isset( $response['expires_in'] ) ) {
		if ( ! isset( $response['access_token'], $response['access_token_type'], $response['expires_in'] ) ) {
			return array(
				'error'   => $response['code'],
				'message' => $response['message'],
				// 'error'   => 404,
				// 'message' => 'Unknown error.',
			);
		}

		// TODO: uncomment when response brings account_id => change business api method. Add account_id to if ( isset( ... ) ) above
		// $account_data['id'] = $account_data['account_id'];
		$account_data['access_token']                 = $this->clean_token( $response['access_token'] );
		$account_data['access_token_renew_atemps']    = 0;
		$account_data['access_token_expiration_date'] = $this->calculate_expiration_date( $response['expires_in'] );
		$account_data['access_token_expires_in']      = $response['expires_in'];
		$account_data['access_token_type']            = $this->get_token_type( $response );

		return $this->save( $account_data );
	}

	/**
	 * Function to get all accounts
	 *
	 * @return array
	 */
	public function get() {
		$accounts = $this->get_all();
		/**
		 * Make sure each account has all values
		 */
		if ( count( $accounts ) ) {
			foreach ( $accounts as $id => $account ) {
				$accounts[ $id ] = array_replace_recursive( $this->get_args(), $accounts[ $id ] );
			}
		}
		return $accounts;
	}

	/**
	 * Function to update an account
	 *
	 * @param array $account_data Account data to be updated.
	 * @return array|false
	 */
	public function update( $account_data ) {
		return $this->save( $account_data );
	}

	/**
	 * Function to delete an account
	 *
	 * @param [int $id Account id to be deleted.
	 * @return boolean
	 */
	public function delete( $id = null ) {

		// Get all accounts.
		$accounts = $this->get_all();
		if ( ! $accounts ) {
			return false;
		}

		// Check if there is at least one account.
		if ( count( $accounts ) < 0 ) {
			return false;
		}

		$key = null;

		// Check if there is some account['id'] equal to $id.
		foreach ( $accounts as $account_id => $account ) {
			if ( (string) $id === (string) $account['id'] ) {
				// Save account_id.
				$key = $account_id;
			}
			continue;
		}

		// Check if $key is defined.
		if ( ! $key ) {
			return false;
		}

		// Unset account.
		unset( $accounts[ $key ] );
		$success = $this->save_all( $accounts );
		if ( ! $success ) {
			return false;
		}

		$args = array(
			$id,
		);
		wp_clear_scheduled_hook( 'qligg_cron_account', $args );
		return $success;
	}

	/**
	 * Function to save an account
	 *
	 * @param array $account_data Account data to be saved.
	 * @return array|false
	 */
	protected function save( $account_data = null ) {

		if ( $account_data['id'] ) {

			$account_data                    = array_intersect_key( $account_data, $this->get_args() );
			$accounts                        = $this->get();
			$accounts[ $account_data['id'] ] = array_replace_recursive( $this->get_args(), $account_data );

			$success = $this->save_all( $accounts );

			$args = array(
				$account_data['id'],
			);
			if ( ! wp_next_scheduled( 'qligg_cron_account', $args ) ) {
				wp_schedule_event(
					time(),
					'fifty_days',
					'qligg_cron_account',
					$args
				);
			}
			if ( $success ) {
				return $account_data;
			}

			return false;
		}
	}

	/**
	 * Function to delete table
	 *
	 * @return void
	 */
	public function delete_table() {
		$this->delete_all();
	}
}
