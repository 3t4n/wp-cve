<?php
/**
 * Watchful Client settings.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful;

use Watchful\Helpers\BackupPlugins\AkeebaBackupPlugin;

/**
 * WP REST API Menu routes
 *
 * @package WP_API_Menus
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Watchful Settings class.
 */
class Settings {
    /**
     * Holds the values to be used in the fields callbacks
     *
     * @var array
     */
    private $options;

    /**
     * Start up.
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'addPluginPage' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page.
     */
    public function addPluginPage() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
        // This page will be under "Settings".
        add_options_page(
            'Watchful settings',
            'Watchful',
            'manage_options',
            'watchful-setting',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property.
        $this->options = get_option( 'watchfulSettings' );

        $activation_message = isset( $_GET['activate'] ) ? htmlspecialchars( $_GET['activate'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
        ?>
        <div class="wrap">

            <?php
            if ( $activation_message ) {
                $this->print_activation_sucessful();
            }
            ?>

            <h2><?php esc_html_e( 'Watchful', 'watchful' ); ?></h2>

            <?php $this->print_watchful_form(); ?>

            <br/><br/>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'watchfulSettingsGroup' );

                do_settings_sections( 'watchful-setting' );

                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {
        register_setting(
            'watchfulSettingsGroup',
            'watchfulSettings', // Option name.
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'watchfulSection', // ID.
            'Watchful settings', // Title.
            array( $this, 'print_section_info' ), // Callback.
            'watchful-setting' // Page.
        );

        add_settings_field(
            'watchfulSecretKey', // ID.
            'Watchful Secret key', // Title.
            array( $this, 'watchful_secret_key_callback' ), // Callback.
            'watchful-setting', // Page.
            'watchfulSection' // Section.
        );

        add_settings_field(
            'watchful_disable_timestamp',
            'Disable Timestamp',
            array( $this, 'watchful_disable_timestamp_callback' ),
            'watchful-setting',
            'watchfulSection'
        );

        add_settings_field(
            'watchful_maintenance',
            'Maintenance mode',
            array( $this, 'watchful_maintenance_callback' ),
            'watchful-setting',
            'watchfulSection'
        );

        add_settings_field(
            'watchful_sso_authentication',
            'SSO authentication',
            array( $this, 'watchful_sso_callback' ),
            'watchful-setting',
            'watchfulSection'
        );

        add_settings_field(
            'watchful_sso_authentication_adminonly',
            'SSO authentication admin only',
            array( $this, 'watchful_sso_adminonly_callback' ),
            'watchful-setting',
            'watchfulSection'
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys.
     *
     * @return array
     */
    public function sanitize( $input ) {
        $new_input = array();
        if ( isset( $input['watchfulSecretKey'] ) ) {
            $new_input['watchfulSecretKey'] = sanitize_text_field( $input['watchfulSecretKey'] );
        }

        $new_input['watchful_disable_timestamp']            = isset( $input['watchful_disable_timestamp'] ) ? 1 : 0;
        $new_input['watchful_maintenance']                  = isset( $input['watchful_maintenance'] ) ? 1 : 0;
        $new_input['watchful_sso_authentication']           = isset( $input['watchful_sso_authentication'] ) ? 1 : 0;
        $new_input['watchful_sso_authentication_adminonly'] = isset( $input['watchful_sso_authentication_adminonly'] ) ? 1 : 0;

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info() {
        print esc_html__( 'Enter your settings below:', 'watchful' );
    }

    /**
     * Callback for Watchful secret key setting.
     */
    public function watchful_secret_key_callback() {
        printf(
            '<input type="text" id="watchfulSecretKey" name="watchfulSettings[watchfulSecretKey]" class="regular-text" value="%s" />',
            isset( $this->options['watchfulSecretKey'] ) ? esc_attr( $this->options['watchfulSecretKey'] ) : ''
        );
    }

    /**
     * Callback for disable timestamp setting.
     */
    public function watchful_disable_timestamp_callback() {
        printf(
            '<input type="checkbox" id="watchful_disable_timestamp" name="watchfulSettings[watchful_disable_timestamp]" %s />',
            isset( $this->options['watchful_disable_timestamp'] ) && 1 === (int) $this->options['watchful_disable_timestamp'] ? 'checked' : ''
        );
    }

    /**
     * Callback for maintenance mode setting.
     */
    public function watchful_maintenance_callback() {
        printf(
            '<input type="checkbox" id="watchful_maintenance" name="watchfulSettings[watchful_maintenance]" %s />',
            isset( $this->options['watchful_maintenance'] ) && 1 === (int) $this->options['watchful_maintenance'] ? 'checked' : ''
        );
    }

    /**
     * Callback for SSO setting.
     */
    public function watchful_sso_callback() {
        printf(
            '<input type="checkbox" id="watchful_sso_authentication" name="watchfulSettings[watchful_sso_authentication]" %s />',
            isset( $this->options['watchful_sso_authentication'] ) && 1 === (int) $this->options['watchful_sso_authentication'] ? 'checked' : ''
        );
    }

    /**
     * Callback for SSO "admin only" setting.
     */
    public function watchful_sso_adminonly_callback() {
        printf(
            '<input type="checkbox" id="watchful_sso_authentication_adminonly" name="watchfulSettings[watchful_sso_authentication_adminonly]" %s />',
            isset( $this->options['watchful_sso_authentication_adminonly'] ) && 1 === (int) $this->options['watchful_sso_authentication_adminonly'] ? 'checked' : ''
        );
    }

    /**
     * Output the Watchful form.
     */
    public function print_watchful_form() {
        $settings = get_option( 'watchfulSettings', '000' );
        ?>
        <h3><?php esc_html_e( 'Add site to Watchful', 'watchful' ); ?></h3>
        <p>
            <strong><?php esc_html_e( 'With this button you can add your website to the Watchful dashboard.', 'watchful' ); ?></strong>
        </p>

        <form action="https://app.watchful.net/app/#/dashboard/" method="get" target="_blank">
            <input type="hidden" name="name" value="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
            <input type="hidden" name="access_url" value="<?php echo esc_attr( get_bloginfo( 'wpurl' ) ); ?>">
            <input type="hidden" name="secret_word" value="<?php echo esc_attr( $settings['watchfulSecretKey'] ); ?>">
            <input type="hidden" name="word_akeeba" value="<?php echo  AkeebaBackupPlugin::get_akeeba_secret_key() ?>">
            <input type="hidden" name="option" value="com_jmonitoring">
            <input type="hidden" name="task" value="save">
            <input type="hidden" name="controller" value="editsite">
            <input type="hidden" name="view" value="editsite">
            <input type="hidden" name="source" value="client">
            <input type="hidden" name="cms" value="wordpress">
            <input type="submit" value="Add site to Watchful" class="button button-primary">
        </form>
        <?php
    }

    /**
     * Print a message indicating the activation was successful.
     */
    protected function print_activation_sucessful() {
        ?>
        <div class="updated notice">
            <p><?php esc_html_e( 'The plugin has been activated', 'watchful' ); ?></p>
        </div>
        <?php
    }
}
