<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

/**
 * Sets the filter to change authorization login cookie
 *
 * @since 1.4.0
**/ 
class CLP_Authorization_Expiration {

    private $settings;

    /**
     * Class Constructor
     * @since 1.4.0
    **/
    public function __construct( $settings ) {
        $this->settings = $settings;
        add_filter('auth_cookie_expiration', array($this, 'set_authorization_cookie'), 99, 3);
    }

    /**
     * filter the cookie exp time based on the settings
     * @since 1.4.0
     * @return int
    **/
	public function set_authorization_cookie( $seconds, $user_id, $remember ) {
        if ( !$remember ) {
            $expiration = $this->settings['basic']['auth-cookie'] ? (int)$this->settings['basic']['auth-cookie'] * CLP_Helper_Functions::time_unit_to_seconds( $this->settings['basic']['auth-cookie-unit'] ) : time() + (10 * 365 * 24 * 60 * 60);
        } else {
            $expiration = $this->settings['basic']['auth-cookie-remember'] ? (int)$this->settings['basic']['auth-cookie-remember'] * CLP_Helper_Functions::time_unit_to_seconds( $this->settings['basic']['auth-cookie-remember-unit'] ) : time() + (10 * 365 * 24 * 60 * 60); 
        }

        return $expiration;
    }

}