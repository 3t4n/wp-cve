<?php

namespace Baqend\WordPress;

use Baqend\SDK\Model\AssetFilter;
use Baqend\SDK\Value\Version;

/**
 * A class representing options of the plugin.
 *
 * Created on 2017-06-20.
 *
 * @author Konstantin Simon Maria Möllers
 * @author Kevin Twesten
 */
class Options {

    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @var array
     */
    private $options;

    /**
     * Options constructor.
     *
     * @param Plugin $plugin
     */
    public function __construct( Plugin $plugin ) {
        $this->plugin  = $plugin;
        $this->options = $this->load_options();
        $this->init();
        $this->upgrade();
    }

    public function init() {
        $host   = $this->plugin->env->host();
        $scheme = $this->plugin->env->scheme();

        $this->set_default( OptionEnums::ADDITIONAL_FILES, '' )
             ->set_default( OptionEnums::ADDITIONAL_URLS, includes_url( 'js/wp-emoji-release.min.js' ) )
             ->set_default( OptionEnums::APP_NAME, null )
             ->set_default( OptionEnums::ARCHIVE_END_TIME, null )
             ->set_default( OptionEnums::ARCHIVE_NAME, null )
             ->set_default( OptionEnums::ARCHIVE_START_TIME, null )
             ->set_default( OptionEnums::ARCHIVE_STATUS_MESSAGES, [] )
             ->set_default( OptionEnums::DEBUGGING_MODE, '0' )
             ->set_default( OptionEnums::DELETE_TEMP_FILES, '1' )
             ->set_default( OptionEnums::DELIVERY_METHOD, 'zip' )
             ->set_default( OptionEnums::DESTINATION_HOST, $host )
             ->set_default( OptionEnums::DESTINATION_SCHEME, $scheme )
             ->set_default( OptionEnums::DESTINATION_URL_TYPE, 'relative' )
             ->set_default( OptionEnums::HTTP_BASIC_AUTH_DIGEST, null )
             ->set_default( OptionEnums::LOCAL_DIR, '' )
             ->set_default( OptionEnums::PASSWORD, null )
             ->set_default( OptionEnums::RELATIVE_PATH, '' )
             ->set_default( OptionEnums::ENABLED_PAGES, [] )
             ->set_default( OptionEnums::ENABLED_PATHS, [] )
             ->set_default( OptionEnums::SPEED_KIT_DISABLE_REASON, DisableReasonEnums::INITIAL )
             ->set_default( OptionEnums::SPEED_KIT_ENABLED, false )
             ->set_default( OptionEnums::SPEED_KIT_WHITELIST, [
                 'fonts.googleapis.com',
                 'fonts.gstatic.com',
                 'maxcdn.bootstrapcdn.com',
                 '',
                 ';;;;;;;;;;;;;;;;;;;;',
                 '; Example:',
                 ';;;;;;;;;;;;;;;;;;;;',
                 '; Use a string which is treated as a prefix:',
                 ';img.assets',
             ] )
             ->set_default( OptionEnums::SPEED_KIT_BLACKLIST, [
                 ';;;;;;;;;;;;;;;;;;;;',
                 '; Example:',
                 ';;;;;;;;;;;;;;;;;;;;',
                 '; Use a string which is treated as a prefix:',
                 ';ads.example',
             ] )
             ->set_default( OptionEnums::IMAGE_OPTIMIZATION, [
                 'quality' => '85',
                 'webp'    => '1',
                 'pjpeg'   => '1',
             ] )
             ->set_default( OptionEnums::SPEED_KIT_COOKIES, [
                 'wordpress_logged_in',
                 'twostep_auth',
                 'comment_',
                 'woocommerce_cart',
                 'woocommerce_items',
             ] )
             ->set_default( OptionEnums::SPEED_KIT_CONTENT_TYPE, [
                 AssetFilter::DOCUMENT,
                 AssetFilter::STYLE,
                 AssetFilter::IMAGE,
                 AssetFilter::SCRIPT,
                 AssetFilter::TRACK,
                 AssetFilter::FONT,
             ] )
             ->set_default( OptionEnums::STRIP_QUERY_PARAMS, [
                 ';;;;;;;;;;;;;;;;;;;;',
                 '; Example:',
                 ';;;;;;;;;;;;;;;;;;;;',
                 '; Use a string to specify the query parameter:',
                 ';gclid',
                 '; Speed Kit will remove "gclid" query parameter before issuing and caching the request.',
             ] )
             ->set_default( OptionEnums::FETCH_ORIGIN_INTERVAL, - 2 )
             ->set_default( OptionEnums::SPEED_KIT_UPDATE_INTERVAL, 'daily' )
             ->set_default( OptionEnums::SPEED_KIT_MAX_STALENESS, 60000 )
             ->set_default( OptionEnums::SPEED_KIT_APP_DOMAIN, '' )
             ->set_default( OptionEnums::TEMP_FILES_DIR, trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) . 'static-files' ) )
             ->set_default( OptionEnums::URLS_TO_EXCLUDE, [] )
             ->set_default( OptionEnums::CUSTOM_CONFIG, '{}' )
             ->set_default( OptionEnums::DYNAMIC_BLOCK_CONFIG, '' )
             ->set_default( OptionEnums::USERNAME, null )
             ->set_default( OptionEnums::VERSION, $this->plugin->version )
             ->set_default( OptionEnums::UPDATE_ATTEMPTS, 0 )
             ->set_default( OptionEnums::METRICS_ENABLED, false )
             ->set_default( OptionEnums::INSTALL_RESOURCE_URL, null )
             ->set_default( OptionEnums::USER_AGENT_DETECTION, false )
             ->set_default( OptionEnums::REVALIDATION_ATTEMPTS, 0)
             ->save();
    }

    /**
     * Upgrades the plugin's options to the newer version.
     */
    public function upgrade() {
        $version        = Version::parse( $this->get( OptionEnums::VERSION ) );
        $plugin_version = Version::parse( $this->plugin->version );

        // Is the installed version up to date? Do not upgrade
        if ( $version->greaterThanOrEqualTo( $plugin_version ) ) {
            return;
        }

        // Upgrade to 1.6.0
        if ( $version->lessThan( Version::fromValues( 1, 6 ) ) ) {
            $this->upgrade_to_1_6_0();
        }

        // Upgrade to 1.7.1
        if ( $version->lessThan( Version::fromValues( 1, 7, 1 ) ) ) {
            $this->upgrade_to_1_7_1();
        }

        // Upgrade to 1.12.5
        if ( $version->lessThan( Version::fromValues( 1, 12, 5 ) ) ) {
            $this->plugin->speed_kit_service->ensure_files_up_to_date();
        }

        $this->set( OptionEnums::VERSION, $plugin_version->__toString() )->save();
        $this->plugin->logger->info( 'Upgraded to ' . $plugin_version->__toString() . ' successfully.' );
    }

    /**
     * Returns whether the value exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has( $name ) {
        return array_key_exists( $name, $this->options ) && null !== $this->options[ $name ];
    }

    /**
     * Returns an option value.
     *
     * @param string $name Name of the option.
     * @param mixed $default The default value to return in case it is missing.
     *
     * @return mixed
     */
    public function get( $name, $default = null ) {
        return isset( $this->options[ $name ] ) ? $this->options[ $name ] : $default;
    }

    /**
     * Returns an option value.
     *
     * @param string $name Name of the option.
     * @param mixed $value The new option value.
     *
     * @return Options
     */
    public function set( $name, $value ) {
        $this->options[ $name ] = $value;

        return $this;
    }

    /**
     * Sets the default option value.
     *
     * @param string $name Name of the option.
     * @param mixed $value The default option value.
     *
     * @return Options
     */
    public function set_default( $name, $value ) {
        if ( ! $this->has( $name ) ) {
            $this->options[ $name ] = $value;
        }

        return $this;
    }

    /**
     * Removes a value.
     *
     * @param string $name
     *
     * @return Options
     */
    public function remove( $name ) {
        if ( array_key_exists( $name, $this->options ) ) {
            unset( $this->options[ $name ] );
        }

        return $this;
    }

    /**
     * Saves options.
     *
     * @return bool
     */
    public function save() {
        return $this->update_options();
    }

    /**
     * @return bool
     */
    public function is_empty() {
        return empty( $this->options );
    }

    /**
     * @return array
     */
    public function all() {
        return $this->options;
    }

    /**
     * Resets the options.
     *
     * @return $this
     */
    public function reset() {
        $this->options = [];

        return $this;
    }

    /**
     * Sets all given values.
     *
     * @param array $options
     */
    public function set_all( array $options ) {
        foreach ( $options as $k => $v ) {
            $this->set( $k, $v );
        }
    }

    /**
     * Get the current path to the temp static archive directory
     * @return string The path to the temp static archive directory
     */
    public function get_archive_dir() {
        return $this->get( OptionEnums::TEMP_FILES_DIR ) . $this->get( OptionEnums::ARCHIVE_NAME );
    }

    /**
     * Get the destination URL (scheme + host)
     * @return string The destination URL
     */
    public function get_destination_origin() {
        return $this->get( OptionEnums::DESTINATION_SCHEME ) . '://' . $this->get( OptionEnums::DESTINATION_HOST );
    }

    /**
     * @return mixed
     */
    private function load_options() {
        return get_option( $this->plugin->slug, [] );
    }

    /**
     * @return bool
     */
    private function update_options() {
        return update_option( $this->plugin->slug, $this->options );
    }

    /**
     * Upgrade to 1.6.0 where cookies will be replaced and the whitelist will be extended.
     */
    private function upgrade_to_1_6_0() {
        // Extend the cookies and delete wp-
        $cookies = $this->get( OptionEnums::SPEED_KIT_COOKIES );

        $cleaned_cookies = array_diff( $cookies, [ 'wp-' ] );
        $added_cookies   = $this->add_to_array( $cleaned_cookies, [
            'wordpress_logged_in',
            'twostep_auth',
        ] );

        $this->set( OptionEnums::SPEED_KIT_COOKIES, $added_cookies );

        // Extend the whitelist
        $whitelist = $this->get( OptionEnums::SPEED_KIT_WHITELIST );

        $extended_whitelist = $this->add_to_array( $whitelist, [
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'maxcdn.bootstrapcdn.com',
        ] );

        $this->set( OptionEnums::SPEED_KIT_WHITELIST, $extended_whitelist );
    }

    /**
     * Upgrade to 1.7.1 where whitelist will be shortened and blacklist extended.
     */
    private function upgrade_to_1_7_1() {
        // Shrink the whitelist
        $whitelist     = $this->get( OptionEnums::SPEED_KIT_WHITELIST );
        $new_whitelist = $this->remove_from_array( $whitelist, [
            untrailingslashit( strip_protocol( site_url() ) ),
        ] );

        $this->set( OptionEnums::SPEED_KIT_WHITELIST, $new_whitelist );

        // Extend the blacklist
        $blacklist     = $this->get( OptionEnums::SPEED_KIT_BLACKLIST );
        $new_blacklist = $this->remove_from_array( $blacklist, [
            untrailingslashit( strip_protocol( site_url( 'wp-json' ) ) ),
            untrailingslashit( strip_protocol( site_url( 'wp-login' ) ) ),
            untrailingslashit( strip_protocol( site_url( 'wp-admin' ) ) ),
            untrailingslashit( strip_protocol( site_url( 'wp-content/plugins/baqend' ) ) ),
        ] );

        $this->set( OptionEnums::SPEED_KIT_BLACKLIST, $new_blacklist );
    }

    /**
     * Add elements to an array if they are not included yet.
     *
     * @param string[] $array The array to add elements to.
     * @param string[] $to_add The elements to add.
     *
     * @return string[] The manipulated array.
     */
    private function add_to_array( array $array, array $to_add ) {
        foreach ( $to_add as $item ) {
            if ( ! in_array( $item, $array, true ) ) {
                $array[] = $item;
            }
        }

        return $array;
    }

    /**
     * Removes elements of an array.
     *
     * @param string[] $array The array to remove elements from.
     * @param string[] $to_remove The elements to remove.
     *
     * @return string[] The manipulated array.
     */
    private function remove_from_array( array $array, array $to_remove ) {
        return array_values( array_diff( $array, $to_remove ) );
    }
}
