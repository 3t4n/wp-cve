<?php

defined( 'ABSPATH' ) || die;

/**
 * Handle requirements.
 */
class Woo_MP_Requirement_Checks {

    /**
     * Minimum required version of PHP.
     *
     * @var string
     */
    const PHP_MIN_REQUIRED = '5.6';

    /**
     * Minimum required version of WordPress.
     *
     * @var string
     */
    const WORDPRESS_MIN_REQUIRED = '4.7';

    /**
     * Minimum required version of WooCommerce.
     *
     * @var string
     */
    const WOOCOMMERCE_MIN_REQUIRED = '3.3';

    /**
     * Requirement checks.
     *
     * @var array
     */
    private static $checks = array(
        'check_php_version',
        'check_wordpress_version',
        'check_woocommerce_active',
        'check_woocommerce_version',
        'check_woo_mp_data_version',
    );

    /**
     * A message explaining which requirement was not met.
     *
     * @var string
     */
    private static $message = '';

    /**
     * Run requirement verification routines.
     *
     * @return bool true if all requirements are met, false otherwise.
     */
    public static function run() {
        foreach ( self::$checks as $check ) {
            self::$check();

            if ( self::$message ) {
                add_action( 'admin_notices', array( __CLASS__, 'output_notice' ) );

                return false;
            }
        }

        return true;
    }

    /**
     * Display notice.
     *
     * @return void
     */
    public static function output_notice() {
        echo '<div class="error"><p>' . wp_kses_post( self::$message ) . '</p></div>';
    }

    /**
     * Check whether the site is using a compatible version of PHP.
     *
     * @return void
     */
    private static function check_php_version() {
        if ( version_compare( PHP_VERSION, self::PHP_MIN_REQUIRED, '<' ) ) {
            self::$message = sprintf(
                'WooCommerce Manual Payment requires PHP version %s or above. You have version %s.' .
                ' Please contact your website administrator, web developer,' .
                ' or hosting provider to get your server updated.',
                self::PHP_MIN_REQUIRED,
                PHP_VERSION
            );
        }
    }

    /**
     * Check whether the site is using a compatible version of WordPress.
     *
     * @return void
     */
    private static function check_wordpress_version() {
        if ( version_compare( $GLOBALS['wp_version'], self::WORDPRESS_MIN_REQUIRED, '<' ) ) {
            self::$message = sprintf(
                'WooCommerce Manual Payment requires WordPress version %s or above. You have version %s.' .
                ' Please contact your website administrator, web developer,' .
                ' or hosting provider to get your website updated.',
                self::WORDPRESS_MIN_REQUIRED,
                $GLOBALS['wp_version']
            );
        }
    }

    /**
     * Check whether WooCommerce is active.
     *
     * @return void
     */
    private static function check_woocommerce_active() {
        $active_plugins = array_merge(
            get_option( 'active_plugins', array() ),
            array_keys( get_site_option( 'active_sitewide_plugins', array() ) )
        );

        if ( ! in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
            self::$message = 'WooCommerce Manual Payment requires WooCommerce to be installed and active.';
        }
    }

    /**
     * Check whether the site is using a compatible version of WooCommerce.
     *
     * @return void
     */
    private static function check_woocommerce_version() {
        if ( version_compare( get_option( 'woocommerce_version' ), self::WOOCOMMERCE_MIN_REQUIRED, '<' ) ) {
            self::$message = sprintf(
                'WooCommerce Manual Payment requires WooCommerce version %s or above. You have version %s.',
                self::WOOCOMMERCE_MIN_REQUIRED,
                get_option( 'woocommerce_version' )
            );
        }
    }

    /**
     * Check whether the site is on a compatible data version.
     *
     * If the user has downgraded the plugin after update routines have been run on a newer version,
     * then the current version won't be compatible with the data that has changed.
     *
     * @return void
     */
    private static function check_woo_mp_data_version() {
        require WOO_MP_PATH . '/includes/update-routines.php';

        $update_routines_class = 'Woo_MP\Update_Routines';
        $update_routines       = new $update_routines_class();

        $current_data_version           = get_option( 'woo_mp_data_version', '0' );
        $latest_compatible_data_version = $update_routines->get_latest_data_version();

        // In versions 2.2.0 and below, the data version is actually the code version.
        // Therefore, if you upgrade from version 2.2.0 or below to a version above 2.2.0,
        // your "data version" will be above the "latest compatible data version"
        // but will actually still be OK.
        $latest_excepted_data_version = '2.2.0';

        if ( version_compare( $current_data_version, $latest_excepted_data_version, '<=' ) ) {
            return;
        }

        if ( version_compare( $current_data_version, $latest_compatible_data_version, '>' ) ) {
            self::$message = sprintf(
                'You have downgraded to an incompatible version of WooCommerce Manual Payment.' .
                ' Version %s or above is required. You have version %s.',
                $current_data_version,
                WOO_MP_VERSION
            );
        }
    }

}
