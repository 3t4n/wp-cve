<?php
namespace Login_With_AJAX\Transports;
use LoginWithAjax;

class Transport {
	
	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public static function get_recipient_key() {
		throw new \Exception('Recipient key not set by ' . static::class);
	}
	
	/**
	 * Gets a second key used to confirm verification of the recipient that we can send them messages.
	 * @return string
	 */
	public static function get_recipient_verified_key() {
		return static::get_recipient_key() . '_verified';
	}
	
	/**
	 * Get username based on username key saved at user meta level
	 * @param \WP_User $user
	 *
	 * @return false|mixed
	 */
	public static function get_recipient( $user ) {
		$username = get_user_meta( $user->ID, static::get_recipient_key(), true );
		if( !empty($username) ) {
			return $username;
		}
	}
	
	/**
	 * Get username based on username key saved at user meta level
	 * @param \WP_User $user
	 *
	 * @return false|mixed
	 */
	public static function get_recipient_verified( $user ) {
		$username_verified = get_user_meta( $user->ID, static::get_recipient_verified_key(), true );
		if( !empty($username_verified) ) {
			return $username_verified;
		}
	}
	
	/**
	 * Gets user from table using the recipient value of used method (e.g. a phone number, username, email)
	 * @param $recipient
	 *
	 * @return \WP_User|null
	 */
	public static function get_recipient_user( $recipient ) {
		global $wpdb;
		$meta_key = static::get_recipient_key();
		$sql = $wpdb->prepare('SELECT user_id FROM '. $wpdb->usermeta . ' WHERE meta_key=%s AND meta_value=%s', $meta_key, $recipient);
		$user_id = $wpdb->get_var($sql);
		return ( $user_id ) ? get_user_by( 'id', $user_id ) : null;
	}
	
	/**
	 * Removes recipient data from the usermeta table.
	 * @param string $recipient
	 *
	 * @return bool
	 */
	public static function disconnect_account( $recipient ) {
		global $wpdb;
		$meta_key = static::get_recipient_key();
		$sql_part = $wpdb->prepare('FROM '. $wpdb->usermeta . ' WHERE (meta_key=%s AND meta_value=%s)', $meta_key, $recipient);
		$user_ids = $wpdb->get_col('SELECT DISTINCT user_id '.$sql_part);
		if( count($user_ids) ) {
			$result = $wpdb->query( 'DELETE ' . $sql_part );
			foreach ( $user_ids as $user_id ) {
				delete_user_meta( $user_id, static::get_recipient_verified_key() );
				$user = new \WP_User( $user_id );
				do_action( 'lwa_' . $meta_key . '_disconnected', $user );
			}
		}
		return !empty($result);
	}
	
	/**
	 * Gets credentials from settings
	 * @return array|null
	 */
	public static function get_credentials( $field = null ) {
		$data = static::get_data();
		if( $field ) {
			return !empty($data['credentials'][$field]) ? $data['credentials'][$field] : null;
		}
		if( !empty($data['credentials']) ) {
			return $data['credentials'];
		}
		return array();
	}
	
	/**
	 * @param $field
	 * @param $default
	 *
	 * @return mixed|null
	 */
	public static function get_data( $field = null, $default = null ){
		if( !empty(\LoginWithAjax::$data[static::$method]) ) {
			$data = \LoginWithAjax::$data[static::$method];
			if( $field && isset($data[$field]) ) {
				return $data[$field];
			}
			return $data;
		}
		return $default;
	}
	
	public static function get_default_templates( $user = null ) {
		$default_templates = array(
			'disconnect' => array(
				'success' => esc_html__( 'Your account has been successfully disconnected from our website. You can link you account again by registering on our website.', 'login-with-ajax-pro' ),
				'error' => esc_html__( 'We could not disconnect your account from our website, please contact a site administrator about this.', 'login-with-ajax-pro' ),
			),
			'verify' => array(
				'success' => sprintf( esc_html__( 'Your account has been linked to user account %s on %s.', 'login-with-ajax-pro' ), '*%s*', get_site_url() ) . "\n\n" . esc_html__( 'You can disconnect this account and repeat the registration process.', 'login-with-ajax-pro' ),
				'error' => esc_html__( 'We could not verify your account, please contact a site administrator about this.', 'login-with-ajax-pro' ),
				'declined' => sprintf( esc_html__( 'Your account will not be linked to the user account %s on %s.', 'login-with-ajax-pro' ), '*%s*', get_site_url()),
			),
			'verify-setup' => array(
				'code' => sprintf( esc_html__( 'Please confirm your number with code %s on %s.', 'login-with-ajax-pro' ), '%CODE%', get_site_url() ) . "\n\n" . esc_html__( 'You can disconnect this account and repeat the registration process.', 'login-with-ajax-pro' ),
				'authorize' => sprintf( esc_html__( 'Do you allow %s to send you messages?', 'login-with-ajax-pro' ), get_site_url() ),
				'changed' => sprintf( esc_html__( 'You have disconnected from %s and your account is now associated with %s', 'login-with-ajax-pro' ), get_site_url(), '%s' ),
			),
			'general' => array(
				'user' => sprintf( esc_html__( 'Hello! This is the authentication service for %s. You are linked to an account on our site.', 'login-with-ajax-pro' ), get_site_url() ),
				'guest' => sprintf( esc_html__( 'Hello! This is the authentication service for %s. We do not have an account linked to you, please log in and register it on our website.', 'login-with-ajax-pro' ), get_site_url() ),
			),
		);
		if ( $user instanceof \WP_User ) {
			// add any of the above array keys to the array if it requires a sprintf with a $user variable
			$default_templates['verify']['success'] = sprintf( $default_templates['verify']['success'],  $user->user_login );
			$default_templates['verify']['declined'] = sprintf( $default_templates['verify']['declined'],  $user->user_login );
			$default_templates['verify-setup']['changed'] = sprintf( $default_templates['verify-setup']['changed'], static::get_recipient($user) );
		}
		// go thorugh each default template above and see if it exists in the settings, if so overwrite the default
		foreach ( $default_templates as $templates_key => $templates ) {
			foreach( $templates as $template_key => $template ) {
				if( !empty(LoginWithAjax::$data[ static::$method ]['templates'][$templates_key][$template_key]) ) {
					$default_templates[$templates_key][$template_key] = LoginWithAjax::$data[ static::$method ]['templates'][$templates_key][$template_key];
				}
			}
		}
		return $default_templates;
	}
	
	public static function request_verify( $number, $user, $verification = 'authorize' ) {
		// send authorization request to new number
		$templates = static::get_default_templates( $user );
		if ( !empty($templates['verify-setup'][$verification]) ) {
			$template = $templates['verify-setup'][$verification];
			if ( $verification === 'code' ) {
				// first save verification code
				$code = rand(100000,999999);
				\LoginWithAjax::update_user_meta( $user->ID, static::$method . '[verify_code]', $code );
				$template = str_replace( '%CODE%', $code, $template);
			}
			return static::send( $number, $template );
		}
		return array(
			'result' => false,
			'error' => 'No defined verification method.',
		);
	}
}
