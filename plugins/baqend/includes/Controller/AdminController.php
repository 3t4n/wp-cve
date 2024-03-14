<?php

namespace Baqend\WordPress\Controller;

use Baqend\WordPress\Admin\MenuBuilder;
use Baqend\WordPress\Admin\View;
use Baqend\WordPress\DisableReasonEnums;
use Baqend\WordPress\Loader;
use Baqend\WordPress\OptionEnums;
use Baqend\WordPress\Plugin;
use Psr\Log\LoggerInterface;

/**
 * Class AdminController created on 16.06.17.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Controller
 */
class AdminController extends Controller {

    /**
     * @var View
     */
    private $view;

    /**
     * @var string
     */
    private $settings_group;

    /**
     * Admin constructor.
     *
     * @param Plugin $plugin
     */
    public function __construct( Plugin $plugin, LoggerInterface $logger ) {
        parent::__construct( $plugin, $logger );
        $this->view           = new View();
        $this->settings_group = $plugin->slug . '_group';
    }

    public function register( Loader $loader ) {
        $loader->add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
        $loader->add_action( 'admin_init', [ $this, 'register_settings' ] );
        $loader->add_action( 'admin_menu', [ $this, 'add_menus' ] );
        $loader->add_action( 'admin_notices', [ $this, 'add_notices' ] );
        $loader->add_filter( 'plugin_action_links_' . $this->plugin->get_basename(), [ $this, 'add_settings_link' ] );
    }

    public function assets() {
        $version = $this->plugin->version;

        wp_enqueue_style( $this->plugin->slug . '-iqons', $this->plugin->icons_url( 'baqend.css' ), [], $version );
        wp_enqueue_style( $this->plugin->slug, $this->plugin->css_url( 'baqend-admin.css' ), [ 'wp-codemirror' ], $version );

        // enqueue the wp pointer to the plugins site to show the alert when deactivating speed kit
        if ( is_admin() && strpos( $_SERVER['REQUEST_URI'], '/wp-admin/plugins.php' ) !== false ) {
            $this->render_wp_pointer( $version );
        }
        // Only add script if the admin's location is within the baqend plugin.
        // This avoids overriding of JavaScript from other plugins or pages.
        if ( ! $this->is_on_baqend_admin() ) {
            return;
        }

        wp_enqueue_script( 'baqend-vendor', $this->plugin->js_url( 'baqend-vendor.js' ), [ 'jquery' ], $version, true );
        wp_enqueue_script( 'baqend-admin', $this->plugin->js_url( 'baqend-admin.js' ), [
            'jquery',
            'wp-codemirror',
        ], $version, true );

        wp_localize_script( 'baqend-admin', 'SPEED_KIT_MESSAGES', [
            'Access to Baqend Cloud'                           => __( 'Access to Baqend Cloud', 'baqend' ),
            'App Name'                                         => __( 'App Name', 'baqend' ),
            'Auto Progressive JPEG'                            => __( 'Auto Progressive JPEG', 'baqend' ),
            'Auto WebP Improvement'                            => __( 'Auto WebP Improvement', 'baqend' ),
            'Baqend Token'                                     => __( 'Baqend Token', 'baqend' ),
            'Black/White'                                      => __( 'Black/White', 'baqend' ),
            'Choose an app'                                    => __( 'Choose an app', 'baqend' ),
            'City'                                             => __( 'City', 'baqend' ),
            'Continue'                                         => __( 'Continue', 'baqend' ),
            'Disable Image Optimization'                       => __( 'Disable Image Optimization', 'baqend' ),
            'dismiss'                                          => __( 'dismiss' ),
            'E-Mail'                                           => __( 'E-Mail', 'baqend' ),
            'enter-valid-email-address'                        => __( 'Enter a valid e-mail address you will use to acess Baqend Cloud.', 'baqend' ),
            'first-create-access'                              => __( 'First, create an access for the Baqend Cloud with your e-mail. You need it to use Baqend’s performance features.', 'baqend' ),
            'I already have an access to Baqend Cloud'         => __( 'I already have an access to Baqend Cloud', 'baqend' ),
            'I don’t have an access to Baqend Cloud yet'       => __( 'I don’t have an access to Baqend Cloud yet', 'baqend' ),
            'I have a Baqend Token'                            => __( 'I have a Baqend Token', 'baqend' ),
            'Landscape'                                        => __( 'Landscape', 'baqend' ),
            'Loading image ...'                                => __( 'Loading image ...', 'baqend' ),
            'Log in to Baqend'                                 => __( 'Log in to Baqend', 'baqend' ),
            'Log In'                                           => __( 'Log In', 'baqend' ),
            'Login with credentials'                           => __( 'Login with credentials', 'baqend' ),
            'Login with token'                                 => __( 'Login with token', 'baqend' ),
            'login-with-credentials-of-account'                => __( 'Log in to Baqend with the credentials of an existing Baqend account.', 'baqend' ),
            'login-with-token-of-account'                      => __( 'Alternatively, log in to Baqend with the token of an existing Baqend account.', 'baqend' ),
            'logout'                                           => __( 'Not {{username}}? Log Out', 'baqend' ),
            'Macro'                                            => __( 'Macro', 'baqend' ),
            'Off'                                              => __( 'Off', 'baqend' ),
            'On'                                               => __( 'On', 'baqend' ),
            'Optimized'                                        => __( 'Optimized', 'baqend' ),
            'Original'                                         => __( 'Original', 'baqend' ),
            'Password'                                         => __( 'Password', 'baqend' ),
            'Photo'                                            => __( 'Photo', 'baqend' ),
            'Please provide a valid e-mail address.'           => __( 'Please provide a valid e-mail address.', 'baqend' ),
            'Portrait'                                         => __( 'Portrait', 'baqend' ),
            'Quality'                                          => __( 'Quality', 'baqend' ),
            'Reduced size by %d %'                             => __( 'Reduced size by %d %', 'baqend' ),
            'Reduced size'                                     => __( 'Reduced size', 'baqend' ),
            'Sea'                                              => __( 'Sea', 'baqend' ),
            'Select an app…'                                   => __( 'Select an app…', 'baqend' ),
            'Select App'                                       => __( 'Select App', 'baqend' ),
            'select-app-below'                                 => __( 'Please select one of your apps below. Your WordPress blog will then be hosted on the selected app.', 'baqend' ),
            'terms'                                            => __( 'By getting access to Baqend Cloud, you agree to Baqend’s <a href="%terms%" target="_blank">Terms of Service</a> and <a href="%privacy%" target="_blank">Privacy Policy</a>.', 'baqend' ),
            'The e-mail you use to login to Baqend.'           => __( 'The e-mail you use to login to Baqend.', 'baqend' ),
            'The password you use to login to Baqend.'         => __( 'The password you use to login to Baqend.', 'baqend' ),
            'The token you can find in your Baqend Dashboard.' => __( 'The token you can find in your Baqend Dashboard.', 'baqend' ),
            'wrong-credentials'                                => __( 'Your credentials were not correct. Please try again.', 'baqend' ),
            'Your token was not correct. Please try again.'    => __( 'Your token was not correct. Please try again.', 'baqend' ),
        ] );
    }

    public function register_settings() {
        if ( ! $this->plugin->app_name && $this->is_on_baqend_admin( [ 'baqend', 'baqend_help' ] ) ) {
            exit( wp_redirect( baqend_admin_url( 'baqend' ) ) );
        }

        register_setting( $this->settings_group, $this->plugin->slug, [ $this, 'save_settings' ] );
    }

    /**
     * Adds links to the plugin page.
     *
     * @param string[] $links
     *
     * @return string[]
     */
    public function add_settings_link( array $links ) {
        $url           = baqend_admin_url( 'baqend' );
        $settings_link = '<a href="' . $url . '">' . __( 'Settings', 'baqend' ) . '</a>';
        array_push( $links, $settings_link );

        return $links;
    }

    /**
     * Processes options aver saving them.
     *
     * FIXME: This is also called when saving the options. What should we do?
     *
     * @param string[] $options Options to process.
     *
     * @return mixed[] The processed options.
     */
    public function save_settings( array $options = null ) {
        $fields  = $this->create_fields();
        $changed = [];

        foreach ( $fields as $field ) {
            $key = $field['slug'];
            if ( ! array_key_exists( $key, $options ) ) {
                continue;
            }

            $oldValue = $this->plugin->options->get( $key );
            $value    = $options[ $key ];

            if ( is_string( $value ) ) {
                $value = $this->normalize_setting( $field, $value );
            }

            if ( $oldValue !== $value ) {
                $this->plugin->options->set( $key, $value );
                $changed[ $key ] = $value;
            }

            // clears cache of fastest cache
            if ( isset( $GLOBALS['wp_fastest_cache'] ) &&
                 method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ) {
                $GLOBALS['wp_fastest_cache']->deleteCache();
            }

            // clears cache of simple cache
            if ( has_action( 'sc_purge_cache' ) ) {
                do_action( 'sc_purge_cache' );
            }
        }

        if ( isset( $changed[ OptionEnums::SPEED_KIT_UPDATE_INTERVAL ] ) ) {
            $this->plugin->update_cron_hooks( $changed[ OptionEnums::SPEED_KIT_UPDATE_INTERVAL ] );
        }

        if ( isset( $changed[ OptionEnums::SPEED_KIT_ENABLED ] ) ) {
            $disable_reason = $changed[ OptionEnums::SPEED_KIT_ENABLED ] ? DisableReasonEnums::NONE : DisableReasonEnums::MANUAL;
            $this->plugin->options->set( OptionEnums::SPEED_KIT_DISABLE_REASON, $disable_reason );
        }

        return $this->plugin->options->all();
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @return array
     */
    public function add_menus() {
        $stats    = $this->stats();
        $is_plesk = $stats ? $stats->is_plesk_user() : false;

        $menu = new MenuBuilder();

        $menu
            ->title( __( 'Speed Kit', 'baqend' ) )
            ->slug( 'baqend' )
            ->icon( 'dashicons-speed-kit' );

        if ( ! $is_plesk ) {
            // Add settings submenu item
            $menu->entry( __( 'Overview', 'baqend' ), 'baqend', [ $this, 'render_overview' ] );

            // Add deploy submenu item
            $menu->entry( __( 'Settings', 'baqend' ), 'baqend_speed_kit', [ $this, 'render_speed_kit' ] );
        } else {
            $menu->entry( __( 'Settings', 'baqend' ), 'baqend', [ $this, 'render_speed_kit' ] );
        }

        $menu->entry( __( 'Advanced', 'baqend' ), 'baqend_advanced', [ $this, 'render_advanced' ] );

        $menu->main( 'baqend' );

        if ( ! $is_plesk ) {
            // Add account submenu item
            $menu->entry( __( 'Account', 'baqend' ), 'baqend_account', [ $this, 'render_account' ] );
        }

        // Add help submenu item
        $menu->entry( __( 'Help', 'baqend' ), 'baqend_help', [ $this, 'render_help' ] );

        $this->view->set_tabs( $menu->build() );
    }

    /**
     * Displays notices in the admin if applicable.
     */
    public function add_notices() {
        if ( $this->is_login_info_broken() ) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <strong><?php _e( 'We updated the Baqend WordPress plugin. Please logout and login again to fix a login vulnerability.', 'baqend' ) ?></strong>
                </p>
                <p>
                    <a href="<?php echo baqend_admin_url( 'baqend_account' ); ?>"><?php _e( 'Click here to go to the logout', 'baqend' ) ?></a>
                </p>
            </div>
            <?php
        }

        if ( ! is_ssl() && strpos( site_url(), 'http://localhost' ) === false ) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong><?php _e( 'Baqend Speed Kit needs HTTPS to work correctly.', 'baqend' ) ?></strong>
                </p>
                <p>
                    <?php _e( 'Please ensure your WordPress is running on HTTPS.', 'baqend' ) ?>
                    <?php _e( 'Therefore, install an SSL certificate on your Apache or Nginx web server, or ask your website provider to install it for you.', 'baqend' ) ?>
                    <?php _e( 'Then, go to the general settings and ensure your WordPress address is beginning with “https://”.', 'baqend' ) ?>
                </p>
                <p>
                    <a href="<?php echo admin_url( 'options-general.php' ); ?>"><?php _e( 'Click here to go to the general settings', 'baqend' ) ?></a>
                </p>
            </div>
            <?php
        }

        if ( $this->plugin->options->has( OptionEnums::CRON_ERROR ) ) {
            $cron_error = $this->plugin->options->get( OptionEnums::CRON_ERROR );
            ?>
            <div class="notice notice-error is-dismissible" data-dismiss-ajax="clear_cron_error">
                <p><strong><?php echo $cron_error; ?></strong></p>
            </div>
            <?php
        }

        $stats             = $this->stats();
        $is_plesk          = $stats ? $stats->is_plesk_user() : false;
        $is_exceeded       = $stats ? $stats->is_exceeded() : false;
        $speed_kit_enabled = $this->plugin->options->get( OptionEnums::SPEED_KIT_ENABLED );
        $remaining_days    = $stats && $stats->remaining_days ? $stats->remaining_days : false;

        if ( ! $is_plesk && $speed_kit_enabled && $remaining_days > 0 && $remaining_days <= 7 && ! $stats->has_next_plan() ) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong>
                        <?php echo printf( __( 'Your free trial will expire in %s days. Speed Kit is then no longer active.', 'baqend' ), $stats->remaining_days ); ?>
                    </strong>
                </p>
                <p>
                    <?php _e( 'Therefore, Speed Kit will not be activated for your website.', 'baqend' ) ?>
                </p>
            </div>
            <?php
        }

        if ( ! $is_plesk && $is_exceeded && $speed_kit_enabled ) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong><?php _e( 'Your free trial period has expired. Speed Kit is no longer active.', 'baqend' ) ?></strong>
                </p>
                <p>
                    <?php _e( 'Your current plan has been exceeded.', 'baqend' ) ?>
                    <?php _e( 'Therefore, Speed Kit is not activated for your website.', 'baqend' ) ?>
                </p>
            </div>
            <?php
        }

        if ( $this->plugin->revalidation_service->has_critical_revalidation_attempts() && ! $speed_kit_enabled ) {
            ?>
            <div class="notice notice-error">
                <p>
                    <strong><?php _e( 'Refreshing the Speed Kit caches failed multiple times. Speed Kit has been disabled automatically!', 'baqend' ) ?></strong>
                </p>
                <p>
                    <?php _e( 'Please ensure that our IP range ', 'baqend' ) ?>
                    <strong><?php _e( '45.140.152.0/22', 'baqend' ) ?></strong>
                    <?php _e( ' is whitelisted to prevent our backend from being blocked.', 'baqend' ) ?>
                </p>
                <p>
                    <?php _e( 'After fixing this issue, you can enable Speed Kit within your ', 'baqend' ) ?>
                    <?php if ( $is_plesk ): ?>
                        <?php _e( 'Plesk dashboard.', 'baqend' ) ?>
                    <?php else: ?>
                        <a href="<?php echo baqend_admin_url( 'baqend_speed_kit' ); ?>"><?php _e( 'Speed Kit settings', 'baqend' ) ?></a>.
                    <?php endif; ?>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Render the overview view.
     */
    public function render_overview() {
        // Logs a user in by the "bq_login_token" query parameter
        if ( isset( $_GET['bq_login_token'] ) ) {
            $json = base64_decode( $_GET['bq_login_token'] );
            if ( $json !== false ) {
                $login_info = json_decode( $json, true );
                if ( isset( $login_info['app_name'] ) && isset( $login_info['token'] ) ) {
                    $app_name = $login_info['app_name'];
                    $token    = $login_info['token'];

                    $this->plugin->login_with_token( $app_name, $token );
                }
            }
        }

        $stats             = $this->stats();
        $is_exceeded       = $stats ? $stats->is_exceeded() : false;
        $speed_kit_enabled = $this->plugin->options->get( OptionEnums::SPEED_KIT_ENABLED );
        $comparison        = $this->plugin->analyzer_service->load_latest_comparison( $speed_kit_enabled, $is_exceeded );

        if ( $comparison === null ) {
            $this->plugin->analyzer_service->start_comparison( $speed_kit_enabled );
        }

//        $stats = new Stats(['appName' => '', 'fixPrice' => 0, 'requestsNow' => 114e3, 'requestsMax' => 150e3, 'priceMax' => 10000, 'priceNow' => 400, 'trafficMax' => 4*1024*1024*1024, 'trafficNow' => 4*1024*1024*1024]);

        $this->view
            ->set_template( 'overview.php' )
            ->assign( 'logged_in', $this->plugin->app_name !== null )
            ->assign( 'app_name', $this->plugin->app_name )
            ->assign( 'username', $this->plugin->options->get( OptionEnums::USERNAME ) )
            ->assign( 'speed_kit', $speed_kit_enabled )
            ->assign( 'exceeded', $is_exceeded )
            ->assign( 'stats', $stats )
            ->assign( 'comparison', $comparison )
            ->assign( 'bbq_username', $this->plugin->options->get( OptionEnums::BBQ_USERNAME ) )
            ->assign( 'bbq_password', $this->plugin->options->get( OptionEnums::BBQ_PASSWORD ) )
            ->render();
    }

    /**
     * Render the account view.
     */
    public function render_account() {
        $settings  = $this->plugin->options->all();
        $api_token = $this->plugin->options->get( OptionEnums::API_TOKEN );

        $this->view
            ->set_template( 'account.php' )
            ->assign( 'app_name', $this->plugin->app_name )
            ->assign( 'stats', $this->stats() )
            ->assign( 'api_token', $api_token )
            ->assign( 'settings', $settings )
            ->assign( 'settings_group', $this->settings_group )
            ->assign( 'bbq_username', $this->plugin->options->get( OptionEnums::BBQ_USERNAME ) )
            ->assign( 'bbq_password', $this->plugin->options->get( OptionEnums::BBQ_PASSWORD ) )
            ->render();
    }

    /**
     * Render the Speed Kit view.
     */
    public function render_speed_kit() {
        $template = 'speedKit.php';
        $slugs    = [
            'enabled_pages',
            'enabled_paths',
            'speed_kit_whitelist',
            'speed_kit_blacklist',
            'image_optimization',
            'speed_kit_cookies',
            'speed_kit_content_type',
            'speed_kit_update_interval',
            'fetch_origin_interval',
            'update_attempts',
        ];

        if ( ! $this->stats()->is_plesk_user() ) {
            $slugs[] = 'speed_kit_enabled';
        }

        $this->render_settings( $slugs, $template );
    }

    public function render_advanced() {
        $template = 'advanced.php';
        $slugs    = [
            'speed_kit_max_staleness',
            'speed_kit_app_domain',
            'custom_config',
            'dynamic_block_config',
            'strip_query_params',
            'update_attempts',
            'metrics_enabled',
            'install_resource_url',
            'user_agent_detection',
        ];

        $this->render_settings( $slugs, $template );
    }

    /**
     * Render the help view.
     */
    public function render_help() {
        // Generate the help fields
        $this->view
            ->set_template( 'help.php' )
            ->assign( 'logged_in', $this->plugin->app_name !== null )
            ->assign( 'stats', $this->stats() )
            ->render();
    }

    private function render_settings( array $slugs, $template ) {
        $settings = $this->plugin->options->all();

        // Get updates on Speed Kit
        $sw_path      = $this->plugin->sw_path();
        $snippet_path = $this->plugin->snippet_path();
        $df_path      = $this->plugin->dynamic_fetcher_path();
        $info         = $this->plugin->baqend->getSpeedKit()->createInfo( $sw_path, $snippet_path, $df_path, '', 'latest', true );

        $field_args = $this->create_fields( $slugs );
        $fields     = $this->plugin->form_service->render_fields( $field_args, $settings );

        $this->view
            ->set_template( $template )
            ->assign( 'stats', $this->stats() )
            ->assign( 'fields', $fields )
            ->assign( 'settings', $settings )
            ->assign( 'settings_group', $this->settings_group )
            ->assign( 'speed_kit', $info )
            ->render();
    }

    /**
     * Checks whether the user is on one of the given Baqend admin pages.
     * If no pages are specified, all baqend pages are checked.
     *
     * @param string[] $pages The optional pages which should be checked.
     *
     * @return bool True, if user is on one of those pages.
     */
    private function is_on_baqend_admin( array $pages = [] ) {
        global $pagenow;
        if ( $pagenow !== 'admin.php' || ! isset( $_GET['page'] ) ) {
            return false;
        }

        $page = $_GET['page'];

        $isBaqendPage = strpos( $page, 'baqend' ) !== false;
        if ( ! $isBaqendPage ) {
            return false;
        }

        return count( $pages ) > 0 ? ! in_array( $page, $pages, true ) : $isBaqendPage;
    }

    /**
     * @param string[]|null $fields_to_filter Slugs of the fields to display.
     *
     * @return array The fields.
     */
    private function create_fields( array $fields_to_filter = null ) {
        $fields = include __DIR__ . '/../../config/fields.php';
        if ( null === $fields_to_filter ) {
            return $fields;
        }

        // Filters only the field to display
        return array_filter( $fields, function ( $field ) use ( $fields_to_filter ) {
            return in_array( $field['slug'], $fields_to_filter, true );
        } );
    }

    /**
     * Normalizes a setting based on its field information.
     *
     * @param array $field The field information.
     * @param string $value The value to normalize.
     *
     * @return mixed
     */
    private function normalize_setting( array $field, $value ) {
        $type = $field['type'];

        if ( $type == 'list' ) {
            $value = preg_split( '/\r\n|[\r\n]/', $value );

            return array_filter( $value, function ( $item ) {
                return ! empty( $item );
            } );
        }

        if ( $type == 'checkbox' ) {
            return $value === 'true' ? true : ( $value === 'false' ? false : null );
        }

        return $value;
    }

    /**
     * Checks whether the login info is broken.
     *
     * @return bool
     */
    private function is_login_info_broken() {
        $options = $this->plugin->options;

        if ( ! $options->has( OptionEnums::APP_NAME ) ) {
            return false;
        }

        if ( $options->has( OptionEnums::API_TOKEN ) ) {
            return false;
        }

        if ( substr( $options->get( OptionEnums::AUTHORIZATION ), 0, 8 ) !== '7fffffff' ) {
            return true;
        }

        return ! $options->has( OptionEnums::USERNAME );
    }

    private function stats() {
        return $this->plugin->stats_service->load_stats();
    }

    private function render_wp_pointer( $version ) {
        wp_enqueue_script( 'baqend-deinstall-alert', $this->plugin->js_url( 'baqend-deinstall-warning.js' ), [ 'wp-pointer' ], $version, true );
        wp_localize_script( 'baqend-deinstall-alert', 'BAQEND_LOCAL', [
            'title'       => __( 'Warning when deactivating Speed Kit!', 'baqend' ),
            'text'        => __( 'Speed Kit may not be removed successfully <strong>from reoccurring visitors</strong>. It is recommended to disable Speed Kit first in the Speed Kit settings and then remove it after two to three weeks.', 'baqend' ),
            'sk_text'     => __( 'Speed Kit is currently', 'baqend' ),
            'sk_limit'    => __( 'Speed Kit free limit is', 'baqend' ),
            'pending'     => __( 'Speed Kit is going to be', 'baqend' ),
            'exceeded'    => __( 'exceeded', 'baqend' ),
            'enabled'     => __( 'enabled', 'baqend' ),
            'disabled'    => __( 'disabled', 'baqend' ),
            'activated'   => __( 'activated', 'baqend' ),
            'deactivated' => __( 'deactivated', 'baqend' ),
        ] );

        $stats             = $this->stats();
        $is_exceeded       = $stats ? $stats->is_exceeded() : false;
        $speed_kit_enabled = $this->plugin->options->get( OptionEnums::SPEED_KIT_ENABLED );
        wp_localize_script( 'baqend-deinstall-alert', 'BAQEND_VAR', [
            'logged_in' => json_encode( $this->plugin->app_name !== null ),
            'exceeded'  => json_encode( $is_exceeded ),
            'enabled'   => json_encode( $speed_kit_enabled ),
        ] );

        wp_enqueue_style( 'wp-pointer' );
    }
}
