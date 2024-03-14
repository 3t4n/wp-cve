<?php

namespace Baqend\WordPress;

use Baqend\SDK\Baqend;
use Baqend\SDK\Client\ClientInterface;
use Baqend\SDK\Model\User;
use Baqend\SDK\Resource\UserResource;
use Baqend\WordPress\Service\WidgetUpdateService;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

/**
 * The main plugin class.
 *
 * @property string slug
 * @property string version
 * @property Loader loader
 * @property Logger logger
 * @property Options options
 * @property Baqend baqend
 * @property \Symfony\Component\Serializer\Serializer serializer
 * @property Service\AnalyzerService analyzer_service
 * @property Service\PluginService plugin_service
 * @property Service\FormService form_service
 * @property Service\IOService io_service
 * @property Service\RevalidationService revalidation_service
 * @property Service\SpeedKitService speed_kit_service
 * @property Service\SpeedKitConfigBuilder speed_kit_config_builder
 * @property Service\StatsService stats_service
 * @property Service\WooCommerceService woo_commerce_service
 * @property Service\UploadService upload_service
 * @property Service\WidgetUpdateService widget_update_service
 * @property Controller\AdminController admin_controller
 * @property Controller\AjaxController ajax_controller
 * @property Controller\CronController cron_controller
 * @property Controller\FrontendController frontend_controller
 * @property Controller\TriggerController trigger_controller
 * @property Controller\DashboardController dashboard_controller
 * @property Env $env
 */
class Plugin {

    const CONTROLLER_TAG = 'baqend.controller';

    /**
     * @var string|null
     */
    public $app_name = null;

    /**
     * @var bool
     */
    private $is_root_writable;

    /**
     * @var string
     */
    private $css_dir_url;

    /**
     * @var string
     */
    private $icons_dir_url;

    /**
     * @var string
     */
    private $js_dir_url;

    /**
     * @var string
     */
    private $css_dir_path;

    /**
     * @var string
     */
    private $js_dir_path;

    public function __construct() {
        // Check if we are in debug mode
        $in_debug  = defined( 'WP_DEBUG' ) && WP_DEBUG === true;
        $log_level = $in_debug ? Logger::DEBUG : Logger::CRITICAL;

        $log_handler  = new ErrorLogHandler( ErrorLogHandler::OPERATING_SYSTEM, $log_level );
        $logger       = new Logger( 'baqend', [ $log_handler ] );
        $this->logger = $logger;

        $env       = new Env();
        $this->env = $env;

        // Set up directories and URLs
        $this->css_dir_url   = plugin_dir_url( __DIR__ ) . 'css/';
        $this->js_dir_url    = plugin_dir_url( __DIR__ ) . 'js/';
        $this->css_dir_path  = plugin_dir_path( __DIR__ ) . 'css/';
        $this->js_dir_path   = plugin_dir_path( __DIR__ ) . 'js/';
        $this->icons_dir_url = plugin_dir_url( __DIR__ ) . 'icons/';

        $this->slug    = Info::SLUG;
        $this->version = Info::VERSION;

        // Check whether the root directory is writable. This information is needed to
        // determine where to save the service worker file.
        $this->is_root_writable = @is_writable( $this->root_path( '' ) );

        $this->loader     = new Loader( $this );
        $this->serializer = Baqend::createSerializer();
        $this->baqend     = new Baqend( ClientInterface::WORD_PRESS_TRANSPORT, [], null, $this->serializer );

        // Services
        $this->io_service           = new Service\IOService();
        $this->upload_service       = new Service\UploadService( $this->baqend, $this->io_service );
        $this->woo_commerce_service = new Service\WooCommerceService();
        $this->form_service         = new Service\FormService( $this->slug );
        $this->speed_kit_service    = new Service\SpeedKitService( $this->io_service, $this->baqend->getSpeedKit(), $this );

        $this->options                  = new Options( $this );
        $this->speed_kit_config_builder = new Service\SpeedKitConfigBuilder( $this->options, $this->woo_commerce_service, $this->sw_scope(), $this->sw_url() );
        $this->revalidation_service     = new Service\RevalidationService( $this->baqend, $logger, $this->serializer, $this->options );
        $this->stats_service            = new Service\StatsService( $this->options, $this->baqend, $this->serializer, $this );
        $this->analyzer_service         = new Service\AnalyzerService( $this->baqend->getClient(), $this->serializer, $this->options );
        $this->widget_update_service    = new WidgetUpdateService( $this->serializer, $this->options, $this->revalidation_service );
        $this->plugin_service           = new Service\PluginService();

        // Controllers
        $this->admin_controller = new Controller\AdminController( $this, $logger );
        $this->loader->add_controller( $this->admin_controller );
        $this->ajax_controller = new Controller\AjaxController( $this, $logger );
        $this->loader->add_controller( $this->ajax_controller );
        $this->frontend_controller = new Controller\FrontendController( $this, $logger );
        $this->loader->add_controller( $this->frontend_controller );
        $this->trigger_controller = new Controller\TriggerController( $this, $logger );
        $this->loader->add_controller( $this->trigger_controller );
        $this->cron_controller = new Controller\CronController( $this, $logger );
        $this->loader->add_controller( $this->cron_controller );
        $this->dashboard_controller = new Controller\DashboardController( $this, $logger );
        $this->loader->add_controller( $this->dashboard_controller );

        // Load the text domain for i18n
        $this->loader->add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

        // Register Baqend login and logout handlers
        $this->loader->add_action( 'plugins_loaded', [ $this, 'login' ] );
        $this->loader->add_action( 'plugins_loaded', [ $this, 'logout' ] );
        $this->loader->add_action( 'activated_plugin', [ $this, 'activated_plugin' ], 10, 1 );
        $this->loader->add_filter( 'init',  [ $this, 'handle_service_worker_request' ] );
    }

    /**
     * Handles the service worker request.
     */
    public function handle_service_worker_request() {
        $request_uri = $_SERVER['REQUEST_URI'];
        if ( $request_uri === extract_path_from_URL( $this->sw_url() ) ) {
            header( 'Content-Type: application/javascript' );
            header( 'Service-Worker-Allowed: ' . $this->sw_scope() );

            require $this->sw_path();

            exit;
        }
    }

    /**
     * Builds a CSS URL for a given file.
     *
     * @param string $css_file
     *
     * @return string
     */
    public function css_url( $css_file ) {
        return $this->css_dir_url . $css_file;
    }

    /**
     * Builds an icon URL for a given file.
     *
     * @param string $icons_file
     *
     * @return string
     */
    public function icons_url( $icons_file ) {
        return $this->icons_dir_url . $icons_file;
    }

    /**
     * Builds a JavaScript URL for a given file.
     *
     * @param string $js_file
     *
     * @return string
     */
    public function js_url( $js_file ) {
        return $this->js_dir_url . $js_file;
    }

    /**
     * Builds a CSS path for a given file.
     *
     * @param string $css_file
     *
     * @return string
     */
    public function css_path( $css_file ) {
        return $this->css_dir_path . $css_file;
    }

    /**
     * Builds a JavaScript path for a given file.
     *
     * @param string $js_file
     *
     * @return string
     */
    public function js_path( $js_file ) {
        return $this->js_dir_path . $js_file;
    }

    /**
     * Builds a root path for a given file.
     *
     * @param string $file
     *
     * @return string
     */
    public function root_path( $file ) {
        return trailingslashit( ABSPATH ) . $file;
    }

    /**
     * @return string
     */
    public function get_basename() {
        return plugin_basename( dirname( __DIR__ ) . '/baqend.php' );
    }

    /**
     * @return string
     */
    public function get_description() {
        return __( 'This plugin connects your WordPress website to Baqend and makes it super-fast.', 'baqend' );
    }

    /**
     * Runs the plugin.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Sets the default options in the options table on activation.
     *
     * @throws \Exception
     */
    public function activate() {
        // Reset the options if they do not exist
        if ( $this->options->is_empty() ) {
            $this->options->reset();
        }

        // Checks that all necessary files exist
        $this->speed_kit_service->ensure_files_up_to_date();

        // Activate the HTML revalidation cron
        $this->update_cron_hooks($this->options->get( OptionEnums::SPEED_KIT_UPDATE_INTERVAL ) );

        // Set hook to update Speed Kit
        $recurrence = 'hourly';
        $this->logger->debug( 'Set the update hook', [ 'recurrence' => $recurrence ] );
        wp_schedule_event( time(), $recurrence, 'cron_update_speed_kit' );

        // $this->configureDomains( 'addDomains', $this->getAllowedOrigins() );
    }

    /**
     * Deactivates the plugin.
     */
    public function deactivate() {
        // Clear hook to update Speed Kit
        wp_clear_scheduled_hook( 'cron_update_speed_kit' );
        wp_clear_scheduled_hook( 'cron_revalidate_html' );

        // $this->configureDomains( 'removeDomains', $this->getAllowedOrigins() );
    }

    /**
     * Executed once a plugin is activated.
     *
     * @param string $plugin The plugin which was activated.
     */
    public function activated_plugin( $plugin ) {
        if ( $plugin === $this->get_basename() ) {
            // Redirect to settings
            $location = baqend_admin_url( 'baqend#activated' );
            exit( wp_redirect( $location ) );
        }
    }

    /**
     * Logs WordPress in to Baqend using a POST request or values from the settings.
     *
     * This method will be executed when the plugin is loaded.
     * It performs a request against Baqend to either login or validate the saved user token.
     */
    public function login() {
        $db       = $this->baqend;
        $app_name = null;

        if ( $this->options->has( OptionEnums::APP_NAME ) && $this->options->has( OptionEnums::AUTHORIZATION ) ) {
            // Login from options setting
            $app_name      = $this->options->get( OptionEnums::APP_NAME );
            $authorization = $this->options->get( OptionEnums::AUTHORIZATION );

            $db->connect( $app_name );
            $db->getRestClient()->setAuthorizationToken( $authorization );
        } elseif ( $this->options->has( OptionEnums::APP_NAME ) && $this->options->has( OptionEnums::API_TOKEN ) ) {
            // Login from API token setting
            $app_name  = $this->options->get( OptionEnums::APP_NAME );
            $api_token = $this->options->get( OptionEnums::API_TOKEN );

            $db->connect( $app_name );
            $db->getRestClient()->setAuthorizationToken( $api_token );
        }

        // Set invalid token handler
        if ( $db->isConnected() && $db->getRestClient()->isAuthorized() ) {
            $this->app_name = $app_name;
            if ( $this->options->has( OptionEnums::API_TOKEN ) ) {
                $db->getRestClient()->setInvalidTokenHandler( function () use ( $db ) {
                    // Set token again
                    $api_token = $this->options->get( OptionEnums::API_TOKEN );
                    $db->getRestClient()->setAuthorizationToken( $api_token );

                    // Update token in settings
                    $this->options->set( OptionEnums::AUTHORIZATION, $db->getRestClient()->getAuthorizationToken() );
                    $this->options->save();
                } );
            }
        }
    }

    /**
     * Logs a user into his app.
     *
     * @param string $app_name The app name to login to.
     * @param string $username The username to use on that app.
     * @param string $token The user's token on that app.
     * @param string $bbq_username The username used in the Baqend Dashboard.
     * @param string $bbq_password The password used in the Baqend Dashboard.
     *
     * @return null|string The logged in app's name or null, if login failed.
     */
    public function login_user( $app_name, $username, $token, $bbq_username = '', $bbq_password = '' ) {
        $this->baqend->connect( $app_name );
        $this->baqend->getRestClient()->setAuthorizationToken($token);

        $this->options->set( OptionEnums::APP_NAME, $app_name );
        $this->options->set( OptionEnums::USERNAME, $username );

        if ( $bbq_username ) {
            $this->options->set( OptionEnums::BBQ_USERNAME, $bbq_username );
        } else {
            $this->options->remove( OptionEnums::BBQ_USERNAME );
        }
        if ( $bbq_password ) {
            $this->options->set( OptionEnums::BBQ_PASSWORD, $bbq_password );
        } else {
            $this->options->remove( OptionEnums::BBQ_PASSWORD );
        }

        $is_ad_rotate_active = $this->plugin_service->is_plugin_active( 'AdRotate', false );
        $this->options->set( OptionEnums::USER_AGENT_DETECTION, $is_ad_rotate_active );
        $this->options->set( OptionEnums::DESTINATION_SCHEME, 'https' );
        $this->options->set( OptionEnums::DESTINATION_HOST, $app_name . '.app.baqend.com' );
        $this->options->set( OptionEnums::AUTHORIZATION, $token );
        $this->options->set( OptionEnums::SPEED_KIT_ENABLED, true );
        $this->options->save();

        return $app_name;
    }

    /**
     * Logs a user into his app using a token.
     *
     * @param string $app_name The app name to login to.
     * @param string $token The user's token on that app.
     *
     * @return null|string The logged in app's name or null, if login failed.
     */
    public function login_with_token( $app_name, $token ) {
        try {
            $password = wp_generate_password();
            $db2      = $this->baqend->createEntityManager( $app_name, $token );

            // Let the backend generate a valid username
            $username = $this->generate_username();

            // Find the WordPress user for this app
            $wordpress_user = $db2->user()->findByUsername( $username );
            // User does not exist
            if ( null === $wordpress_user ) {
                $wordpress_user = $db2->user()->register( $username, $password, UserResource::NO_LOGIN );
            }

            $db2->user()->newPassword( $wordpress_user, '', $password );
            $this->make_bucket_write_only_to_user( $db2, 'www', $wordpress_user );
            $this->make_bucket_queryable_for_user( $db2, 'baqend_assets', $wordpress_user );

            if ( $this->login_user( $app_name, $username, $token ) !== null ) {
                $this->app_name = $app_name;
            }
        } catch ( \Exception $e ) {
            return null;
        }
    }

    /**
     * Logs WordPress out from Baqend using a POST request.
     *
     * This method will be executed when the plugin is loaded.
     */
    public function logout() {
        $db = $this->baqend;
        if ( $db->isConnected() && array_key_exists( 'logout', $_POST ) && $_POST['logout'] === 'true' ) {
            // Execute logout request
            $this->did_logout();
            $this->app_name = null;
            $this->options->remove(OptionEnums::SPEED_KIT_ENABLED );
            $this->options->remove( OptionEnums::APP_NAME );
            $this->options->remove( 'authorization' );
            $this->options->remove( 'bbq_password' );
            $this->options->remove( 'bbq_username' );
            $this->options->save();
        }
    }

    /**
     * Loads the plugin language files.
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            Info::SLUG,
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );
    }

    /**
     * Returns the Service Worker's absolute pathname.
     * The wrapper is a PHP file that loads the actual Service Worker and
     * is used to set the Service-Worker-Allowed header.
     *
     * @return string
     */
    public function sw_wrapper_path() {
        return plugin_dir_path( __DIR__ ) . 'wrapper-sw.php';
    }

    /**
     * Returns the Service Worker's absolute pathname.
     *
     * @return string
     */
    public function sw_path() {
        $sw_file = 'speed-kit-sw.js';
        return $this->is_root_writable ? $this->root_path( $sw_file ) : $this->js_path( $sw_file );
    }

    /**
     * Returns the Service Worker's absolute URL.
     *
     * @return string
     */
    public function sw_url() {
        $sw_file = home_url( 'speed-kit-sw.js' );
        $sw_wrapper_file = home_url( 'speed-kit-sw' );
        return $this->is_root_writable ? $sw_file : $sw_wrapper_file;
    }

    /**
     * Returns the service worker's scope.
     *
     * @return string
     */
    public function sw_scope() {
        $https = is_ssl();
        $home  = home_url( '/', $https ? 'https' : 'http' );
        $site  = site_url( '/', $https ? 'https' : 'http' );

        // Return home url when the both host are not the same.
        if ( parse_url( $home )['host'] !== parse_url( $site )['host'] ) {
            return $home;
        }

        for ( $i = 0; $i < min( strlen( $home ), strlen( $site ) ); $i += 1 ) {
            if ( $home[ $i ] !== $site[ $i ] ) {
                break;
            }
        }

        return substr( $home, 0, $i );
    }

    /**
     * Returns the snippet's absolute pathname.
     *
     * @return string
     */
    public function snippet_path() {
        return $this->js_path( 'snippet.js' );
    }

    /**
     * Returns the Service Worker's absolute pathname in the JS dir.
     *
     * @return string
     */
    public function sw_js_path() {
        return $this->js_path( 'sw.js' );
    }

    /**
     * Returns the dynamic fetcher's absolute pathname.
     *
     * @return string
     */
    public function dynamic_fetcher_path() {
        return $this->js_path( 'dynamic-fetcher.js' );
    }

    /**
     * Returns the install script's absolute pathname.
     *
     * @return string
     */
    public function install_script_path() {
        // Use different install script if the user is logged in.
        // This avoids that the admin retries the install script of a non admin user.
        $admin_prefix = is_user_logged_in() ? 'admin-' : '';
        return $this->js_path( $admin_prefix . 'speed-kit-install.js' );
    }

    /**
     * Returns the install script's URL.
     *
     * @return string
     */
    public function install_script_url() {
        // Use different install script if the user is logged in.
        // This avoids that the admin retries the install script of a non admin user.
        $admin_prefix = is_user_logged_in() ? 'admin-' : '';
        return $this->js_dir_url . $admin_prefix . 'speed-kit-install.js';
    }

    /**
     * Updates the interval of the revalidation cron.
     *
     * @param string $speed_kit_update_interval The new update interval as string (e.g. 'daily').
     */
    public function update_cron_hooks( $speed_kit_update_interval ) {
        $time = time();
        wp_clear_scheduled_hook( 'cron_revalidate_html' );
        if ( $speed_kit_update_interval !== 'off' ) {
           wp_schedule_event( $time, $speed_kit_update_interval, 'cron_revalidate_html' );
        }
    }

    /**
     * @return bool
     */
    private function did_logout() {
        try {
            return $this->baqend->user()->logout();
        } catch ( \Exception $e ) {
            return true;
        }
    }

    /**
     * Generates a valid WordPress username for this WP installation.
     *
     * @return string
     */
    public function generate_username() {
        $site_url = site_url( '/' );
        preg_match( '#https?://(?:www\.)?([\.A-Za-z0-9-]+)#', $site_url, $matches );
        list( , $domain ) = $matches;
        $hash = substr( md5( $site_url ), 0, 10 );

        return 'wp-' . str_replace( '.', '-', $domain ) . '-' . $hash . '@baqend.com';
    }

    /**
     * Returns a POST value.
     *
     * @param string $field
     * @param string $default
     *
     * @return string
     */
    private function get_post( $field, $default = '' ) {
        if ( isset( $_POST[ $field ] ) ) {
            return $_POST[ $field ];
        }

        return $default;
    }

    /**
     * Make file bucket public accessible but restrict access to a given user.
     *
     * @param Baqend $db
     * @param string $bucket
     * @param User $user
     *
     * @return \Baqend\SDK\Model\FileBucket
     * @throws \Baqend\SDK\Exception\NeedsAuthorizationException
     */
    private function make_bucket_write_only_to_user( Baqend $db, $bucket, User $user ) {
        // Retrieve the bucket's metadata
        $metadata = $db->file()->get( $bucket );

        // Change write settings
        $metadata->getAcl()->getDelete()->allowAccess( $user );
        $metadata->getAcl()->getInsert()->allowAccess( $user );
        $metadata->getAcl()->getQuery()->allowAccess( $user );
        $metadata->getAcl()->getUpdate()->allowAccess( $user );

        return $db->file()->put( $bucket );
    }

    /**
     * Restrict file bucket to be queryable for a given user.
     *
     * @param Baqend $db
     * @param string $bucket
     * @param User $user
     *
     * @return \Baqend\SDK\Model\FileBucket
     * @throws \Baqend\SDK\Exception\NeedsAuthorizationException
     */
    private function make_bucket_queryable_for_user( Baqend $db, $bucket, User $user ) {
        // Retrieve the bucket's metadata
        $metadata = $db->file()->get( $bucket );

        // Change loading settings
        $metadata->getAcl()->getLoad()->allowAccess( $user );

        // Change query settings
        $metadata->getAcl()->getQuery()->allowAccess( $user );

        return $db->file()->put( $bucket );
    }

    /**
     * Returns all allowed origins.
     *
     * @return string[]
     */
    private function getAllowedOrigins() {
        return array_filter( get_allowed_http_origins(), function ( $el ) {
            return substr( $el, 0, 7 ) !== 'http://';
        });
    }

    /**
     * Configures the authorized domains of the app.
     *
     * @param string $action
     * @param string[] $domains
     */
    private function configureDomains( $action, $domains ) {
        $this->ensureConnection();
        $request = $this->baqend->getRestClient()->createRequest()
                                ->asPost()
                                ->withPath( '/code/wordpressAuthorizedDomains' )
                                ->withJsonBody( [ 'action' => $action, 'domains' => array_values($domains) ] )
                                ->build();
        try {
            $response = $this->baqend->getClient()->sendSyncRequest( $request );
            $this->logger->info($response->getBody());
        } catch ( \Exception $e ) {
            $this->logger->error($e);
        }
    }

    /**
     * Ensures that a connection to the app is established.
     */
    private function ensureConnection() {
        $app_name      = $this->options->get( OptionEnums::APP_NAME );
        $authorization = $this->options->get( OptionEnums::AUTHORIZATION );
        if ( $authorization === null ) {
            $authorization = $this->options->get( OptionEnums::API_TOKEN );
            if ( $authorization === null ) {
                return;
            }
        }

        if ( !$this->baqend->isConnected() ) {
            $this->baqend->connect( $app_name );
        }

        $this->baqend->getRestClient()->setAuthorizationToken( $authorization );
    }
}
