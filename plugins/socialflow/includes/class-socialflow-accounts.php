<?php
/**
 * SocialFlow Accounts class
 *
 * @package SocialFlow
 */

/**
 * SocialFlow_Accounts
 */
class SocialFlow_Accounts {

	/**
	 * Active accounts ids
	 *
	 * @since 2.0
	 * @access protected
	 * @var array
	 */
	protected $active;

	/**
	 * Account ids from last query
	 *
	 * @since 2.0
	 * @access protected
	 * @var array
	 */
	protected $last;

	/**
	 * Default order for available account types
	 *
	 * @var array
	 */
	public static $type_order = array( 'twitter', 'facebook', 'google_plus', 'linkedin', 'pinterest' );

	/**
	 * Init construct
	 *
	 * @since 0.2
	 * @access public
	 */
	public function __construct() {}

	/**
	 * Get account by id
	 *
	 * @since 3.0
	 * @param  int    $account_id is int.
	 * @param  string $post_type is string.
	 *
	 * @return null|int
	 */
	public function get_by_id( $account_id, $post_type = 'post' ) {
		$accounts = $this->get_2( array(), $post_type );

		$account_id = absint( $account_id );

		if ( empty( $account_id ) ) {
			return false;
		}

		if ( ! array_key_exists( $account_id, $accounts ) ) {
			return false;
		}

		return $accounts[ $account_id ];
	}

	/**
	 * Retrieve array of accounts
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param array  $query filter.
	 * @param string $post_type post type.
	 * @return mixed ( array | bool ) Return array of accounts or false if none matched
	 * can also return single account if client_account_id is passed instead of query array
	 */
	public function get_2( $query = array(), $post_type = 'post' ) {
		global $socialflow;

		$accounts = $socialflow->options->get( 'accounts', array() );

		foreach ( $accounts as $account_id => $account ) {
			$account = new SocialFlow_Account( $account );

			$accounts[ $account_id ] = $account;

			// For attachments return accounts with specific types only.
			if ( 'attachment' !== $post_type ) {
				continue;
			}

			if ( in_array( $account->get_type(), array( 'twitter', 'facebook_page', 'google_plus_page', 'facebook', 'google_plus' ), true ) ) {
				continue;
			}

			unset( $accounts[ $account_id ] );
		}

		// return all acconts if empty query passed.
		if ( empty( $query ) ) {
			return $this->disable_pinterest( $accounts );
		}

		// return single account if $query is int - client_account_id.
		if ( is_int( $query ) ) {
			$account_id = $query;

			if ( array_key_exists( $account_id, $accounts ) ) {
				return $accounts[ $account_id ];
			}

			return false;
		}

		// Check if array of account ids was passed.
		if ( isset( $query[0] ) && is_int( $query[0] ) ) {
			$intersect = array_intersect( array_keys( $accounts ), array_values( $query ) );
			if ( $intersect ) {
				foreach ( $accounts as $key => $value ) {
					if ( ! in_array( $key, $intersect, true ) ) {
						unset( $accounts[ $key ] );
					}
				}
				return $this->disable_pinterest( $accounts );
			}
			return false;
		}

		// loop through query attributes and unset not matching accounts.
		foreach ( $accounts as $key => $account ) {
			// check current account to match all qeuries.
			foreach ( $query as $check ) {
				// To-Do add different comparison operators.
				// break loop if query doesn't match.
				if ( ! isset( $check['key'] )
					|| ! is_string( $check['key'] )
					|| ! $account->get( $check['key'] )
					|| (
						! is_array( $check['value'] )
						&& ! is_array( $account->get( $check['key'] ) )
						&& $account->get( $check['key'] ) !== $check['value']
					)
					|| (
						is_array( $check['value'] )
						&& ! is_array( $account->get( $check['key'] ) )
						&& ! in_array( $account->get( $check['key'] ), $check['value'], true )
					)
				) {
					unset( $accounts[ $key ] );
					break;
				}
			}
		}

		if ( empty( $accounts ) ) {
			return false;
		}

		return $this->disable_pinterest( $accounts );
	}

	/**
	 * Hard disable pinterest, because pinterest api doesn't supported.
	 *
	 * @param array $accounts account user socialflow.
	 *
	 * @return array
	 */
	public function disable_pinterest( $accounts ) {
		if ( empty( $accounts ) ) {
			return $accounts;
		}

		foreach ( $accounts as $key => $account ) {
			$type = is_a( $account, 'SocialFlow_Account' ) ? $account->get_type() : $account['account_type'];

			if ( 'pinterest' === $type ) {
				unset( $accounts[ $key ] );
			}
		}

		return $accounts;
	}

	/**
	 * Get enabled accounts list
	 *
	 * @since 3.0
	 *
	 * @param  string $post_type post type.
	 * @return array
	 */
	public function get_enabled_accounts( $post_type = 'post' ) {
		global $socialflow;

		$enabled_ids = $socialflow->options->get( 'show' );

		if ( empty( $enabled_ids ) ) {
			return array();
		}

		return $this->get_2( $enabled_ids, $post_type );
	}

	/**
	 * Retrieve array of accounts
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param array  $query  query filter.
	 * @param string $post_type  post type.
	 * @return mixed ( array | bool ) Return array of accounts or false if none matched
	 * can also return single account if client_account_id is passed instead of query array
	 */
	public function get( $query = array(), $post_type = 'post' ) {
		global $socialflow;

		$accounts = $socialflow->options->get( 'accounts', array() );

		// For attachments return accounts with specific types only.
		if ( 'attachment' === $post_type ) {
			foreach ( $accounts as $key => $account ) {
				if ( ! in_array( $account['account_type'], array( 'twitter', 'facebook_page', 'google_plus_page' ), true ) ) {
					unset( $accounts[ $key ] );
				}
			}
		}

		// return all acconts if empty query passed.
		if ( empty( $query ) ) {
			return $accounts;
		}

		// return single account if $query is int - client_account_id.
		if ( is_int( $query ) ) {
			if ( array_key_exists( $query, $accounts ) ) {
				return $accounts[ $query ];
			} else {
				return false;
			}
		}

		// Check if array of account ids was passed.
		if ( isset( $query[0] ) && is_int( $query[0] ) ) {
			$intersect = array_intersect( array_keys( $accounts ), array_values( $query ) );
			if ( $intersect ) {
				foreach ( $accounts as $key => $value ) {
					if ( ! in_array( $key, $intersect, true ) ) {
						unset( $accounts[ $key ] );
					}
				}
				return $this->disable_pinterest( $accounts );
			}
			return false;
		}

		// loop through query attributes and unset not matching accounts.
		foreach ( $accounts as $key => $account ) {
			// check current account to match all qeuries.
			if ( ! is_array( $query ) ) {
				return false;
			}
			foreach ( $query as $check ) {

				// To-Do add different comparison operators.
				// break loop if query doesn't match.
				if ( ! isset( $check['key'] ) || ! is_string( $check['key'] ) ||
					! isset( $account[ $check['key'] ] ) ||
					( ! is_array( $check['value'] ) && ! is_array( $account[ $check['key'] ] ) && $account[ $check['key'] ] !== $check['value'] ) ||
					( is_array( $check['value'] ) && ! is_array( $account[ $check['key'] ] ) && ! in_array( $account[ $check['key'] ], $check['value'], true ) )
				) {
					unset( $accounts[ $key ] );
					break;
				}
			}
		}

		if ( empty( $accounts ) ) {
			return false;
		}

		return $this->disable_pinterest( $accounts );
	}

	/**
	 * Get active accounts
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param string $fields accounts or only ids.
	 * @return mixed ( array | bool ) array of accounts is returned if active attribute isset
	 */
	public function get_active( $fields = 'all' ) {

		if ( ! $this->isset_active() ) {
			return false;
		}

		if ( 'ids' === $fields ) {
			return $this->active;
		}

		return $this->get(
			array(
				'client_account_id' => $this->active,
			)
		);
	}

	/**
	 * Set active accounts
	 *
	 * @since 2.1
	 * @access public
	 * @deprecated not used in plugin anymore
	 *
	 * @param array $query arguments passed to get() method.
	 * @return bool accounts were found and active ids were set
	 */
	public function set_active( $query = array() ) {

		// Get accounts by query.
		$accounts = $this->get( $query );

		// $active atribute needs only ids.
		if ( false !== $accounts ) {
			$accounts = array_keys( $accounts );
		}

		return $accounts;
	}

	/**
	 * Check if active attribute isset
	 *
	 * @since 2.1
	 *
	 * @return bool
	 */
	protected function isset_active() {
		return isset( $this->active );
	}

	/**
	 * Retrieve single account display name
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param mixed ( array | int ) $account single account or account_id.
	 * @param bool                  $add_prefix add_type_prefix or not.
	 * @return string account display name
	 */
	public function get_display_name( $account = array(), $add_prefix = true ) {
		// Get account if account id was passed.
		$account = is_int( $account ) ? $this->get( $account ) : $account;

		$account = new SocialFlow_Account( $account );

		return $account->get_display_name( $add_prefix );
	}

	/**
	 * Group accounts by type
	 *
	 * @since 2.0   - group_by()
	 * @since 3.0 - updated - group_by_type()
	 * @access public
	 *
	 * @param array   $accounts accounts to group.
	 * @param boolean $order order to account.
	 * @return array grouped accounts
	 */
	public function group_by_type( $accounts = array(), $order = false ) {
		if ( empty( $accounts ) ) {
			return $accounts;
		}

		$new = array();
		foreach ( $accounts as  $account ) {
			// Define.
			$type = $account->get_type();

			if ( ! isset( $new[ $type ] ) ) {
				$new[ $type ] = array();
			}

			$new[ $type ][] = $account;
		}
		$accounts = $new;

		if ( false === $order ) {
			return $accounts;
		}

		$types = array_intersect( self::$type_order, array_keys( $accounts ) );

		// see http://stackoverflow.com/questions/348410/sort-an-array-by-keys-based-on-another-array.
		return array_replace( array_flip( $types ), $accounts );
	}


	/**
	 * User Friendly type title
	 *
	 * @param  string $type Account type.
	 * @return string       Account type title
	 */
	public function get_type_title( $type ) {
		switch ( $type ) {
			case 'google_plus':
				return 'Google+';

			case 'linkedin':
				return 'LinkedIn';

			default:
				return ucfirst( $type );
		}
	}

	/**
	 * Get global account type
	 *
	 * @param mixed $account accoun user.
	 * @return string account type
	 *
	 * @since 1.0
	 * @access public
	 */
	public function get_global_type( $account ) {
		if ( ! is_array( $account ) ) {
			return '';
		}

		$account = new SocialFlow_Account( $account );

		return $account->get_type();
	}

	/**
	 * Get all accounts names for post stats table
	 *
	 * @since 2.7.4
	 */
	public function get_all_accounts_names() {
		global $socialflow;

		$accounts_all = $socialflow->accounts->get_2();

		$names = array();

		foreach ( $accounts_all as $account_id => $account ) {
			$type = $account->get_type();

			if ( empty( $type ) ) {
				continue;
			}

			$name = $account->get_display_name();

			if ( empty( $name ) ) {
				continue;
			}

			$names[ $account_id ] = $name;
		}

		return $names;
	}
}
