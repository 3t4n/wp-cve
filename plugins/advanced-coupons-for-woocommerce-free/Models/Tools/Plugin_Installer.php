<?php
namespace ACFWF\Models\Tools;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Interfaces\Initializable_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the Plugin_Installer module.
 *
 * @since 4.5.5
 */
class Plugin_Installer implements Model_Interface, Initializable_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 4.5.5
     * @access private
     * @var Plugin_Installer
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 4.5.5
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 4.5.5
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.5.5
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 4.5.5
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Plugin_Installer
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $main_plugin, $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /**
     * Download and activate a given plugin.
     *
     * @since 4.5.5
     * @access public
     *
     * @param string $plugin_slug Plugin slug.
     * @return bool|\WP_Error True if successful, WP_Error otherwise.
     */
    public function download_and_activate_plugin( $plugin_slug ) {

        // Check if the current user has the required permissions.
        if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
            return new \WP_Error( 'permission_denied', __( 'You do not have sufficient permissions to install and activate plugins.', 'advanced-coupons-for-woocommerce-free' ) );
        }

        // Check if the plugin is valid.
        if ( ! $this->_is_plugin_allowed_for_install( $plugin_slug ) ) {
            return new \WP_Error( 'acfw_plugin_not_allowed', __( 'The plugin is not valid.', 'advanced-coupons-for-woocommerce-free' ) );
        }

        // Get required files since we're calling this outside of context.
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        // Get the plugin info from WordPress.org's plugin repository.
        $api = plugins_api( 'plugin_information', array( 'slug' => $plugin_slug ) );
        if ( is_wp_error( $api ) ) {
            return $api;
        }

        $plugin_basename = $this->get_plugin_basename_by_slug( $plugin_slug );

        // Check if the plugin is already active.
        if ( is_plugin_active( $plugin_basename ) ) {
            return new \WP_Error( 'acfw_plugin_already_active', __( 'The plugin is already installed.', 'advanced-coupons-for-woocommerce-free' ) );
        }

        // Check if the plugin is already installed but inactive, just activate it and return true.
        if ( $this->_helper_functions->is_plugin_installed( $plugin_basename ) ) {
            return $this->_activate_plugin( $plugin_basename, $plugin_slug );
        }

        // Download the plugin.
        $upgrader = new \Plugin_Upgrader(
            new \Plugin_Installer_Skin(
                array(
                    'type'  => 'web',
                    'title' => sprintf( 'Installing Plugin: %s', $api->name ),
                )
            )
        );

        $result = $upgrader->install( $api->download_link );

        // Check if the plugin was installed successfully.
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Activate the plugin.
        return $this->_activate_plugin( $plugin_basename, $plugin_slug );
    }

    /**
     * Activate a plugin.
     *
     * @since 4.5.6
     * @access private
     *
     * @param string $plugin_basename Plugin basename.
     * @param string $plugin_slug     Plugin slug.
     * @return bool|\WP_Error True if successful, WP_Error otherwise.
     */
    private function _activate_plugin( $plugin_basename, $plugin_slug ) {
        $result = activate_plugin( $plugin_basename );

        // Update uncanny automator source option.
        if ( 'uncanny-automator' === $plugin_slug ) {
            update_option( 'uncannyautomator_source', 'acoupons' );
        }

        return is_wp_error( $result ) ? $result : true;
    }

    /**
     * Get the list of allowed plugins for install.
     *
     * @since 4.5.5
     * @access public
     *
     * @return array List of allowed plugins.
     */
    public function get_allowed_plugins() {

        $allowed_plugins = array(
            'woocommerce'       => 'woocommmerce/woocommerce.php',
            'uncanny-automator' => Plugin_Constants::UNCANNY_AUTOMATOR_PLUGIN,
            'funnel-builder'    => Plugin_Constants::FUNNEL_BUILDER_PLUGIN,
        );

        // Allow other plugins to be installed but not let them overwrite the ones listed above.
        $extra_allowed_plugins = apply_filters( 'acfw_allowed_install_plugins', array() );

        return array_merge( $allowed_plugins, $extra_allowed_plugins );
    }

    /**
     * Validate if the given plugin is allowed for install.
     *
     * @since 4.5.5
     * @access private
     *
     * @param string $plugin_slug Plugin slug.
     * @return bool True if valid, false otherwise.
     */
    private function _is_plugin_allowed_for_install( $plugin_slug ) {
        return in_array( $plugin_slug, array_keys( $this->get_allowed_plugins() ), true );
    }

    /**
     * Get the plugin basename by slug.
     *
     * @since 4.5.5
     * @access public
     *
     * @param string $plugin_slug Plugin slug.
     * @return string Plugin basename.
     */
    public function get_plugin_basename_by_slug( $plugin_slug ) {
        $allowed_plugins = $this->get_allowed_plugins();

        return $allowed_plugins[ $plugin_slug ] ?? '';
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX Functions
    |--------------------------------------------------------------------------
     */

    /**
     * AJAX install and activate a plugin.
     *
     * @since 4.5.5
     * @access public
     */
    public function ajax_install_activate_plugin() {

        // Check nonce.
        check_ajax_referer( 'acfw_install_plugin', 'nonce' );

        // Retrieve the plugin slug from the front-end.
        $plugin_slug = isset( $_POST['plugin_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_slug'] ) ) : '';

        $result = $this->download_and_activate_plugin( $plugin_slug );

        // Check if the result is a WP_Error.
        if ( is_wp_error( $result ) ) {
            // If it is, return a JSON response indicating failure.
            wp_send_json_error( $result->get_error_message() );
        } else {
            // If not, return a JSON response indicating success.
            wp_send_json_success();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute codes that needs to run plugin init.
     *
     * @since 4.5.5
     * @access public
     * @inherit ACFWF\Interfaces\Initializable_Interface
     */
    public function initialize() {
        add_action( 'wp_ajax_acfw_install_activate_plugin', array( $this, 'ajax_install_activate_plugin' ) );
    }

    /**
     * Execute Plugin_Installer class.
     *
     * @since 4.5.5
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
    }
}
