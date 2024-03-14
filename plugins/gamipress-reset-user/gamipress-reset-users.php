<?php
/**
 * Plugin Name:         GamiPress - Reset User
 * Plugin URI:          https://wordpress.org/plugins/gamipress-reset-user/
 * Description:         Reset all user earnings and logs from a single button.
 * Version:             1.0.0
 * Author:              GamiPress
 * Author URI:          https://gamipress.com/
 * Text Domain:         gamipress-reset-user
 * Domain Path: 		/languages/
 * Requires at least: 	4.4
 * Tested up to: 		5.4
 * License:             GNU AGPL v3.0 (http://www.gnu.org/licenses/agpl.txt)
 *
 * @package             GamiPress\Reset_User
 * @author              GamiPress <contact@gamipress.com>
 * @copyright           Copyright (c) GamiPress
 */

final class GamiPress_Reset_User {

    /**
     * @var         GamiPress_Reset_User $instance The one true GamiPress_Reset_User
     * @since       1.0.0
     */
    private static $instance;

    /**
     * Get active instance
     *
     * @access      public
     * @since       1.0.0
     * @return      GamiPress_Reset_User self::$instance The one true GamiPress_Reset_User
     */
    public static function instance() {
        if( !self::$instance ) {
            self::$instance = new GamiPress_Reset_User();
            self::$instance->constants();
            self::$instance->includes();
            self::$instance->hooks();
            self::$instance->load_textdomain();
        }

        return self::$instance;
    }

    /**
     * Setup plugin constants
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function constants() {
        // Plugin version
        define( 'GAMIPRESS_RESET_USER_VER', '1.0.0' );

        // Plugin path
        define( 'GAMIPRESS_RESET_USER_DIR', plugin_dir_path( __FILE__ ) );

        // Plugin URL
        define( 'GAMIPRESS_RESET_USER_URL', plugin_dir_url( __FILE__ ) );
    }

    /**
     * Include plugin files
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function includes() {

        if( $this->meets_requirements() ) {

            require_once GAMIPRESS_RESET_USER_DIR . 'includes/admin.php';

        }
    }

    /**
     * Setup plugin hooks
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function hooks() {
        // Setup our activation and deactivation hooks
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    /**
     * Activation hook for the plugin.
     */
    function activate() {

        if( $this->meets_requirements() ) {

        }

    }

    /**
     * Deactivation hook for the plugin.
     */
    function deactivate() {

    }

    /**
     * Plugin admin notices.
     *
     * @since  1.0.0
     */
    public function admin_notices() {

        if ( ! $this->meets_requirements() && ! defined( 'GAMIPRESS_ADMIN_NOTICES' ) ) : ?>

            <div id="message" class="notice notice-error is-dismissible">
                <p>
                    <?php printf(
                        __( 'GamiPress - Reset User requires %s in order to work. Please install and activate them.', 'gamipress-reset-user' ),
                        '<a href="https://wordpress.org/plugins/gamipress/" target="_blank">GamiPress</a>'
                    ); ?>
                </p>
            </div>

            <?php define( 'GAMIPRESS_ADMIN_NOTICES', true ); ?>

        <?php endif;

    }

    /**
     * Check if there are all plugin requirements
     *
     * @since  1.0.0
     *
     * @return bool True if installation meets all requirements
     */
    private function meets_requirements() {

        if ( class_exists( 'GamiPress' ) ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Internationalization
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function load_textdomain() {
        // Set filter for language directory
        $lang_dir = GAMIPRESS_RESET_USER_DIR . '/languages/';
        $lang_dir = apply_filters( 'gamipress_reset_user_languages_directory', $lang_dir );

        // Traditional WordPress plugin locale filter
        $locale = apply_filters( 'plugin_locale', get_locale(), 'gamipress-reset-user' );
        $mofile = sprintf( '%1$s-%2$s.mo', 'gamipress-reset-user-integration', $locale );

        // Setup paths to current locale file
        $mofile_local   = $lang_dir . $mofile;
        $mofile_global  = WP_LANG_DIR . '/gamipress-reset-user-integration/' . $mofile;

        if( file_exists( $mofile_global ) ) {
            // Look in global /wp-content/languages/gamipress-reset-user-integration/ folder
            load_textdomain( 'gamipress-reset-user', $mofile_global );
        } elseif( file_exists( $mofile_local ) ) {
            // Look in local /wp-content/plugins/gamipress-reset-user-integration/languages/ folder
            load_textdomain( 'gamipress-reset-user', $mofile_local );
        } else {
            // Load the default language files
            load_plugin_textdomain( 'gamipress-reset-user', false, $lang_dir );
        }
    }

}

/**
 * The main function responsible for returning the one true GamiPress_Reset_User instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \GamiPress_Reset_User The one true GamiPress_Reset_User
 */
function GamiPress_Reset_User() {
    return GamiPress_Reset_User::instance();
}
add_action( 'plugins_loaded', 'GamiPress_Reset_User' );
