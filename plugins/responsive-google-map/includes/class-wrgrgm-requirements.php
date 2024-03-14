<?php
defined( 'ABSPATH' ) || exit;

/**
 * WRGRGM Requirements Check
 * 
 * @since 3.0.0
 */
class WRGRGM_Requirements {

    /**
     * Required WordPress Version
     *
     * @var string
     */
    protected $wp_version;

    /**
     * Required PHP Version
     *
     * @var string
     */
    protected $php_version;

    /**
     * Initialize class
     *
     * @param array $args
     */
    public function __construct( $args ) {

        foreach( [] as $option ) {
            if ( isset( $args[$option] ) ) {
                $this->setting = $args[$option];
            }
        }
    }

    /**
     * Check for compatibility
     *
     * @return boolean
     */
    public function check() {

        if ( ! $this->php_check() ) {
            add_action( 'admin_notices', array( $this, 'php_not_compatible_error' ) );
            return false;
        }

        if ( ! $this->wp_check() ) {
            add_action( 'admin_notices', array( $this, 'wp_not_compatible_error' ) );
            return false;
        }

        return true;
    }

    /**
     * WordPress Compatible Error
     *
     * @return void
     */
    public function wp_not_compatible_error() {

        if ( ! current_user_can( 'manage_options' ) ) {
			return;
        }

        WRGRGM_Notice::push(array(
            'id' => 'wrgrgm-wp-not-compatible',
            'type' => 'error',
            'title' => 'Responsive Google Map',
            'message' => sprintf( __( 'WordPress %1$s. To use this Responsive Google Map version, please upgrade WordPress to version %1$s or higher.', 'wrg_rgm' ), $this->wp_version )
        ));
    }

    /**
     * PHP Compatible Error
     *
     * @return void
     */
    public function php_not_compatible_error() {

        if ( ! current_user_can( 'manage_options' ) ) {
			return;
        }

        WRGRGM_Notice::push(array(
            'id' => 'wrgrgm-php-not-compatible',
            'type' => 'error',
            'title' => 'Responsive Google Map',
            'message' => sprintf( __( 'PHP %1$s. To use this Responsive Google Map version, please ask your web host how to upgrade your server to PHP %1$s or higher.', 'wrg_rgm' ), $this->php_version )
        ));
    }

    /**
     * Compare PHP Version
     *
     * @return boolean
     */
    private function php_check() {
        return version_compare( PHP_VERSION, $this->php_version ) >= 0;
    }

    /**
     * Compare WordPress Version
     *
     * @return boolean
     */
    private function wp_check() {
        global $wp_version;

		return version_compare( $wp_version, $this->wp_version ) >= 0;
    }
}