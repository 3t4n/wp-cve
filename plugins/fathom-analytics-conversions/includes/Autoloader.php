<?php
/**
 * Includes the composer Autoloader.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Autoloader class.
 *
 * @since 1.0.9
 */
class Autoloader {

    private function __construct() {}

    /**
     * Require the autoloader and return the result.
     *
     * If the autoloader is not present, let's log the failure and display a nice admin notice.
     *
     * @return boolean
     */
    public static function init() {
        $autoloader = dirname( __DIR__ ) . '/vendor/autoload.php';

        if ( ! is_readable( $autoloader ) ) {
            self::missing_autoloader();
            return false;
        }

        $autoloader_result = require $autoloader;
        if ( ! $autoloader_result ) {
            return false;
        }

        return $autoloader_result;
    }

    /**
     * If the autoloader is missing, add an admin notice.
     */
    protected static function missing_autoloader() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log(  // phpcs:ignore
                esc_html__( 'Your installation of Fathom Analytics Conversions is incomplete.', 'fathom-analytics-conversions' )
            );
        }
        add_action(
            'admin_notices',
            function() {
                ?>
                <div class="notice notice-error">
                    <p>
                        <?php
                        printf(
                        /* translators: 1: is a link to a support document. 2: closing link */
                            esc_html__( 'Your installation of Fathom Analytics Conversions is incomplete.', 'fathom-analytics-conversions' ),
                        );
                        ?>
                    </p>
                </div>
                <?php
            }
        );
    }


}
