<?php
/**
 * SocialFlow Account class
 *
 * @package SocialFlow
 */

/**
 * SocialFlow_Account
 */
class SocialFlow_Account {

	/**
	 * Data Account
	 *
	 * @since 1.0
	 * @var  array
	 */
	protected $data = array();

	/**
	 * Load Options for requested db key
	 *
	 * @since 0.2
	 * @access public
	 * @param string $account account value.
	 */
	public function __construct( $account ) {
		$this->data = $account;
	}

	/**
	 * Get id
	 *
	 * @since 3.0
	 *
	 * @return null|int
	 */
	public function get_id() {
		return $this->get( 'client_service_id' );
	}

	/**
	 * Get account data
	 *
	 * @since 3.0
	 *
	 * @param  string $key is string.
	 *
	 * @return null|string
	 */
	public function get( $key = '' ) {
		if ( empty( $key ) ) {
			return $this->data;
		}

		if ( ! isset( $this->data[ $key ] ) ) {
			return;
		}

		return $this->data[ $key ];
	}

	/**
	 * Get account data
	 *
	 * @since 3.0
	 *
	 * @return int
	 */
	public function is_valid() {
		return ! ! $this->get( 'is_valid' );
	}

	/**
	 * Retrieve single account display name
	 *
	 * @since 3.0
	 * @access public
	 *
	 * mixed ( array | int ) single account or account_id.
	 * @param bool $add_prefix add type prefix.
	 * @return string account display name.
	 */
	public function get_display_name( $add_prefix = true ) {
		$name   = $this->get( 'name' );
		$prefix = $this->get_display_type();

		$type = $this->get_type();

		if ( empty( $type ) ) {
			return __( 'Missing account', 'socialflow' );
		}

		if ( 'twitter' === $type ) {
			if ( $this->get( 'screen_name' ) ) {
				$name = $this->get( 'screen_name' );
			} elseif ( $this->get( 'user' ) ) {
				$user = $this->get( 'user' );

				if ( isset( $user['name'] ) ) {
					$name = $user['name'];
				}
			}

			$name = "@{$name}";
		}

		if ( empty( $name ) ) {
			$name = $this->get_id();
		}

		return $add_prefix ? "$prefix $name" : $name;
	}

	/**
	 * User Friendly type title
	 *
	 * @since 3.0
	 *
	 * @return string       Account type title.
	 */
	public function get_type_title() {
		$type = $this->get_type();

		if ( 'google_plus' === $type ) {
			return 'Google+';
		}

		if ( 'linkedin' === $type ) {
			return 'LinkedIn';
		}

		return ucfirst( $type );
	}

	/**
	 * Get global account type
	 *
	 * @return string account type
	 *
	 * @since 3.0
	 * @access public
	 */
	public function get_type() {
		$type = $this->get( 'account_type' );

		if ( strpos( $type, 'twitter' ) !== false ) {
			$type = 'twitter';
		} elseif ( strpos( $type, 'facebook' ) !== false ) {
			$type = 'facebook';
		} elseif ( strpos( $type, 'google_plus' ) !== false ) {
			$type = 'google_plus';
		} elseif ( strpos( $type, 'linked_in' ) !== false ) {
			$type = 'linkedin';
		}

		return $type;
	}

	/**
	 * Get client id
	 *
	 * @return int type service_user_id
	 *
	 * @since 3.0
	 * @access public
	 */
	public function get_client_id() {
		return $this->get( 'service_user_id' );
	}

	/**
	 * Get display type
	 *
	 * @return string  display type
	 *
	 * @since 3.0
	 * @access public
	 */
	public function get_display_type() {
		$type = $this->get_type();

		switch ( $type ) {
			case 'facebook':
				$type = __( 'Facebook Wall', 'socialflow' );
				break;
			case 'google_plus':
				$type = __( 'Google+', 'socialflow' );
				break;

			case 'linkedin':
				$type = __( 'LinkedIn', 'socialflow' );
				break;

			default:
				$type = ucfirst( $type );
				break;
		}

		return $type;
	}

	/**
	 * Get native type
	 *
	 * @return string  native type
	 *
	 * @since 3.0
	 * @access public
	 */
	public function get_native_type() {
		return $this->get( 'account_type' );
	}
}
