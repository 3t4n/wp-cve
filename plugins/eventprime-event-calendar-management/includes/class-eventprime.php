<?php
defined( 'ABSPATH' ) || exit;

/**
 * EventPrime Plugin
 *
 * The main plugin handler class is responsiable for initializing the EventPrime.
 *
 * @since 3.0.0
 */

final class EventPrime {

    /**
     * Version
     *
     * @var Version
     */
    public $version = '3.4.4';
    /**
     * Factory
     *
     * @var Factory
     */
    public $factory;
    /**
     * Errors
     *
     * @var Errors
     */
    public $errors = array();
    /**
     * Extensions
     *
     * @var Extensions
     */
    public $extensions = array();
    /**
     * Instance
     *
     * @var Instance
     */
    protected static $instance = null;

    /**
     *
     * Ensures only one instance of Event_Magic is loaded or can be loaded.
     *
     * @static
     * @return EventPrime - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->define_constants();
            self::$instance->load_textdomain();
            self::$instance->includes();
            self::$instance->init_hooks();
            add_action( 'plugins_loaded', array( self::$instance, 'plugin_loaded' ) );
        }
        return self::$instance;
    }

    /**
     * Cloning is forbidden.
     *
     * @since 3.0
     */
    public function __clone() {
        _doing_it_wrong(__FUNCTION__, __('Cloning is forbidden.', 'eventprime-event-calendar-management'), $this->version);
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 3.0
     */
    public function __wakeup() {
        _doing_it_wrong(__FUNCTION__, __('Unserializing instances of this class is forbidden.', 'eventprime-event-calendar-management'), $this->version);
    }

    /**
     * EventPrime constructor
     */
    public function __construct() {
        
    }

    /**
     * Define constant if not already set
     *
     * @param string        $name Constant name.
     * @param string|bool   $value Constant value
     */
    public function define( $name, $value ) {
        if( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Define EventPrime constants
     */
    public function define_constants() {
        $this->define( 'EVENTPRIME_VERSION', $this->version );
        $this->define( 'EM_EVENT_POST_TYPE', 'em_event' );
        $this->define( 'EM_PERFORMER_POST_TYPE', 'em_performer' );
        $this->define( 'EM_BOOKING_POST_TYPE', 'em_booking' );
        $this->define( 'EM_EVENT_TYPE_TAX', 'em_event_type' );
        $this->define( 'EM_VENUE_TYPE_TAX', 'em_venue' );
        $this->define( 'EM_EVENT_VENUE_TAX', 'em_venue' );
        $this->define( 'EM_EVENT_ORGANIZER_TAX', 'em_event_organizer');
        $this->define( 'EM_GLOBAL_SETTINGS', 'em_global_settings' );
        $this->define( 'EM_DB_VERSION', 'emagic_db_version' );
        $this->define( 'EP_PAGINATION_LIMIT', 10 );
        $this->define( 'EP_DEFAULT_CURRENCY', 'USD' );
        $this->define( 'EP_BASE_URL', plugin_dir_url( EP_PLUGIN_FILE ) );
        //$this->define( 'EP_BASE_FRONT_IMG_URL', plugin_dir_url( EP_PLUGIN_FILE ) . 'includes/templates/images/' );
        $this->define( 'EP_BASE_DIR', plugin_dir_path( EP_PLUGIN_FILE ) );
        $this->define( 'EP_PLUGIN_BASE', plugin_basename( EP_PLUGIN_FILE ) );
        $this->define( 'EP_INCLUDES_DIR', plugin_dir_path( EP_PLUGIN_FILE ) . 'includes' );
        $this->define( 'EP_REQ_EXT_MCRYPT', 1 );
        $this->define( 'EP_REQ_EXT_CURL', 2 );
    }

    /**
     * Load EventPrime localization files
     */
    public function load_textdomain() {
        $local = determine_locale();

        $locale = apply_filters( 'plugin_locale', $local, 'eventprime-event-calendar-management' );

        // WordPress language directory /wp-content/languages/eventprime-event-calendar-management-en_US.mo
        $language_filepath = WP_LANG_DIR . '/eventprime-event-calendar-management-'.$locale.'.mo';

        // If language file exists on WordPress language directory use it
        if( file_exists( $language_filepath ) ) {
            load_textdomain('eventprime-event-calendar-management', $language_filepath );
        } else{ // Otherwise use EventPrime plugin directory /path/to/plugin/languages/eventprime-event-calendar-management-en_US.mo
            load_plugin_textdomain('eventprime-event-calendar-management', false, plugin_basename( dirname( EP_PLUGIN_FILE ) ) . '/languages/');
        }
    }

    /**
     * Include the required files
     */
    public function includes() {
        // core classes
        $this->core_includes();
		// module classes
	    $this->module_includes();
    }

	/**
	 * Include core files
	 */
    public function core_includes() {
        include_once EP_BASE_DIR . 'includes/core/class-ep-autoloader.php';
        include_once EP_BASE_DIR . 'includes/core/class-ep-post-types.php';
        include_once EP_BASE_DIR . 'includes/core/class-ep-install.php';
        include_once EP_BASE_DIR . 'includes/core/ep-utility-functions.php';
        include_once EP_BASE_DIR . 'includes/core/class-ep-constants.php';
        include_once EP_BASE_DIR . 'includes/core/class-ep-shortcodes.php';
        include_once EP_BASE_DIR . 'includes/service/class-ep-factory.php';
        include_once EP_BASE_DIR . 'includes/service/class-ep-ajax.php';
        include_once EP_BASE_DIR . 'includes/service/class-ep-form-handler.php';
        include_once EP_BASE_DIR .'includes/core/admin/class-ep-widgets.php';
        // include notification service
        include_once EP_BASE_DIR . 'includes/service/class-ep-notification-service.php';
        // include hook service
        include_once EP_BASE_DIR . 'includes/service/class-ep-action-service.php';
        
        // include admin files
        if( EventM_Factory_Service::ep_is_request( 'admin' ) ) {
            include_once EP_BASE_DIR . 'includes/core/admin/class-ep-admin.php';
            include_once EP_BASE_DIR . 'includes/bookings/admin/class-ep-booking-admin.php';
            include_once EP_BASE_DIR . 'includes/reports/admin/class-ep-report-admin.php';
        }
        // include specific frontend files
        if( EventM_Factory_Service::ep_is_request( 'frontend' ) ) {
            include_once EP_BASE_DIR . 'includes/service/class-ep-front-notices.php';
            // include paypal service
            include_once EP_BASE_DIR . 'includes/service/class-ep-paypal-service.php';
            
        }
        // include ep blocks
        include_once  EP_BASE_DIR . 'includes/core/admin/blocks/class-ep-magic-blocks.php';
    }

	/**
	 * Include module files
	 */

	public function module_includes() {
		include_once EP_BASE_DIR . 'includes/events/class-events-init.php';
		include_once EP_BASE_DIR . 'includes/event_types/class-event-types-init.php';
        include_once EP_BASE_DIR . 'includes/venues/class-venues-init.php';
        include_once EP_BASE_DIR . 'includes/organizers/class-organizers-init.php';
        include_once EP_BASE_DIR . 'includes/performers/class-performers-init.php';
	}

    /**
     * register hooks
     */
	public function init_hooks() {
		register_activation_hook( EP_PLUGIN_FILE, array( 'EventM_Install', 'install' ) );
        
        add_action( 'wp_enqueue_scripts', array( $this, 'ep_enqueues' ) );

        add_filter( 'generate_rewrite_rules', array( 'EventM_Factory_Service', 'ep_load_rewrites' ) );

        // iCal download
        add_action( 'init', array( 'EventM_Factory_Service', 'get_ical_file' ), 9999 ); // iCal file download.

        add_action( 'init', array( 'EventM_Install', 'ep_check_updated_data' ) );
        
        $this->define_gutenberg_block_hooks();
	}

    /**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function ep_plugin_url() {
		return untrailingslashit( plugins_url( '/', EP_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function ep_plugin_path() {
		return untrailingslashit( plugin_dir_path( EP_PLUGIN_FILE ) );
	}

    /**
     * Enqueue front scripts and styles
     */
    public function ep_enqueues() {
        wp_enqueue_style(
            'ep-public-css',
            EP_BASE_URL . '/includes/assets/css/em-front-common-utility.css',
            false, EVENTPRIME_VERSION
        );

        //wp_enqueue_style( 'ep-material-fonts', 'https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp', array(), EVENTPRIME_VERSION );
        wp_enqueue_style( 'ep-material-fonts', EP_BASE_URL . '/includes/assets/css/ep-material-fonts-icon.css', array(), EVENTPRIME_VERSION );

        // register toast
        wp_register_style(
            'ep-toast-css',
            EP_BASE_URL . '/includes/assets/css/jquery.toast.min.css',
            false, EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-js',
            EP_BASE_URL . '/includes/assets/js/jquery.toast.min.js',
            array('jquery'), EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-message-js',
            EP_BASE_URL . '/includes/assets/js/toast-message.js',
            array('jquery'), EVENTPRIME_VERSION
        );

        wp_enqueue_style( 'ep-toast-css' );
        wp_enqueue_script( 'ep-toast-js' );
        wp_enqueue_script( 'ep-toast-message-js' );

        // common js for admin and front both
        wp_enqueue_script(
            'ep-common-script',
            EP_BASE_URL . '/includes/assets/js/ep-common-script.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        // localized global settings
        $global_settings = ep_get_global_settings();
        $currency_symbol = ep_currency_symbol();
        $datepicker_format = ep_get_datepicker_format( 2 );
        wp_localize_script(
            'ep-common-script', 
            'eventprime', 
            array(
                'global_settings'      => $global_settings,
                'currency_symbol'      => $currency_symbol,
                'ajaxurl'              => ep_get_ajax_url(),
                'trans_obj'            => EventM_Factory_Service::ep_define_common_field_errors(),
                'event_wishlist_nonce' => wp_create_nonce( 'event-wishlist-action-nonce' ),
                'security_nonce_failed'=> esc_html__( 'Security check failed. Please refresh the page and try again later.', 'eventprime-event-calendar-management' ),
                'datepicker_format'    => $datepicker_format
            )
        );

        wp_register_style(
            'ep-responsive-slides-css',
            EP_BASE_URL . '/includes/assets/css/responsiveslides.css',
            false, EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-responsive-slides-js',
            EP_BASE_URL . '/includes/assets/js/responsiveslides.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
    }

    public function define_gutenberg_block_hooks() {
		$plugin_block = new EventM_Magic_Blocks();
		add_action( 'init',  array( $plugin_block, 'eventprime_block_register' ) );
        add_action( 'rest_api_init',  array( $plugin_block, 'ep_register_rest_route' ) );
	}
    
    /**
     * Action after plugin loaded
     */
    public function plugin_loaded() {
        do_action( 'event_magic_loaded' );
    }

}