<?php
/**
 * Book a Place.
 *
 * @package   Book a Place
 * @author    ArtkanMedia
 * @license   GPL-2.0+
 * @copyright 2018 ArtkanMedia
 */

/**
 * Book a Place class.
 *
 * @package Book_A_Place
 * @author  ArtkanMedia
 */
class Book_A_Place
{

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   0.1.0
     *
     * @var     string
     */
    protected $version = '0.7.1';

    /**
     * Unique identifier for plugin.
     *
     * @since    0.1.0
     *
     * @var      string
     */
    protected $plugin_slug = 'book-a-place';

    /**
     * Instance of this class.
     *
     * @since    0.1.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    0.1.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    protected $schemes_page_screen_hook_suffix = null;
    protected $orders_page_screen_hook_suffix = null;
    protected $settings_page_screen_hook_suffix = null;
    protected $events_page_screen_hook_suffix = null;

    protected $page_url;

    protected $place_types = array('1' => 'seat',);
    protected $place_statuses = array(
        '1' => 'available',
        '2' => 'booked',
        '3' => 'in-cart',
        '4' => 'in-others-cart',
        '5' => 'unavailable'
    );
    protected $place_statuses_labels = array();
    protected $order_statuses = array();

    protected $session_id;

    protected $event_booking_open = null;

    protected $options;

    /**
     * @since    0.3.0
     * @var array
     */
    private $currency_symbols = array(
        '1' => '&#164;',
        '2' => '&#36;',
        '3' => '&#162;',
        '4' => '&#163;',
        '5' => '&#165;',
        '6' => '&#8355;',
        '7' => '&#8356;',
        '8' => '&#8359;',
        '9' => '&#128;',
        '10' => '&#8361;',
        '11' => '&#8372;',
        '12' => '&#8367;',
        '13' => '&#8366;',
        '14' => '&#8368;',
        '15' => '&#8370;',
        '16' => '&#8369;',
        '17' => '&#8371;',
        '18' => '&#8373;',
        '19' => '&#8365;',
        '20' => '&#8362;',
        '21' => '&#8363;',
        '22' => '&#37;',
        '23' => '&#137;',
    );

    private $success_user_messages = array();
    private $error_user_messages = array();

    /**
     * Initialize the plugin by setting localization, filters, and administration functions.
     *
     * @since     0.1.0
     */
    private function __construct()
    {
        self::add_options();
        $this->options = get_option(BAP_OPTIONS);

        // run version dependent functions
        if ($this->options['plugin_version'] != $this->version) {
            self::create_tables();
            $this->options['plugin_version'] = $this->version;
            update_option(BAP_OPTIONS, $this->options);
        }

        session_start();
        $this->session_id = session_id();

        $this->page_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Load plugin text domain
        add_action('init', array(
            $this,
            'load_plugin_textdomain'
        ));

        // Add the options page and menu item.
        add_action('admin_menu', array(
            $this,
            'add_plugin_admin_menu'
        ));

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array(
            $this,
            'enqueue_admin_styles'
        ));
        add_action('admin_enqueue_scripts', array(
            $this,
            'enqueue_admin_scripts'
        ));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array(
            $this,
            'enqueue_styles'
        ));
        add_action('wp_enqueue_scripts', array(
            $this,
            'enqueue_scripts'
        ), 999);

        add_action('init', array(
            'Book_A_Place',
            'register_tables'
        ), 1);
        add_action('switch_blog', array(
            'Book_A_Place',
            'register_tables'
        ));

        // Custom functionality.
        add_action('wp_ajax_set_place', array(
            $this,
            'set_place'
        ));
        add_action('wp_ajax_refresh_scheme', array(
            $this,
            'refresh_scheme_front'
        ));
        add_action('wp_ajax_nopriv_refresh_scheme', array(
            $this,
            'refresh_scheme_front'
        ));
        add_action('wp_ajax_unset_place', array(
            $this,
            'unset_place'
        ));
        add_action('wp_ajax_update_place', array(
            $this,
            'update_place'
        ));
        add_action('wp_ajax_edit_place', array(
            $this,
            'edit_place'
        ));
        add_action('wp_ajax_get_place_statuses', array(
            $this,
            'get_place_statuses'
        ));
        add_action('wp_ajax_update_place_status', array(
            $this,
            'ajax_update_place_status'
        ));
        add_action('wp_ajax_add_to_cart', array(
            $this,
            'add_to_cart'
        ));
        add_action('wp_ajax_nopriv_add_to_cart', array(
            $this,
            'add_to_cart'
        ));
        add_action('wp_ajax_refresh_cart', array(
            $this,
            'refresh_cart'
        ));
        add_action('wp_ajax_nopriv_refresh_cart', array(
            $this,
            'refresh_cart'
        ));
        add_shortcode('book_a_place', array(
            $this,
            'book_a_place_shortcode'
        ));
        add_action('wp_ajax_delete_from_cart', array(
            $this,
            'delete_from_cart'
        ));
        add_action('wp_ajax_nopriv_delete_from_cart', array(
            $this,
            'delete_from_cart'
        ));
        add_action('wp_ajax_checkout', array(
            $this,
            'ajax_checkout'
        ));
        add_action('wp_ajax_nopriv_checkout', array(
            $this,
            'ajax_checkout'
        ));
        add_action('wp_ajax_add_event', array(
            $this,
            'add_event_ajax'
        ));
        add_action('wp_ajax_get_schemes_list', array(
            $this,
            'get_schemes_list_ajax'
        ));
        add_action('wp_ajax_get_events_json', array(
            $this,
            'get_events_json_ajax'
        ));
        add_action('wp_ajax_move_event', array(
            $this,
            'move_event_ajax'
        ));
        add_action('wp_ajax_resize_event', array(
            $this,
            'resize_event_ajax'
        ));
        add_action('wp_ajax_get_event', array(
            $this,
            'get_event_ajax'
        ));
        add_action('wp_ajax_edit_event', array(
            $this,
            'edit_event_ajax'
        ));
        add_action('wp_ajax_delete_event', array(
            $this,
            'delete_event_ajax'
        ));
        add_shortcode('book_a_place_event', array(
            $this,
            'book_a_place_event_shortcode'
        ));

    }

    /**
     * Return an instance of this class.
     *
     * @since     0.1.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    0.1.0
     *
     * @param    boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public static function activate($network_wide)
    {
        if (self::book_a_place_plugin_verification()) {
            set_error_handler ('self::bap_error_handler', E_USER_ERROR);
            trigger_error('Another version of the plugin is activated. Please deactivate it first.', E_USER_ERROR);
        }

        // activation functionality
        self::create_tables();
        self::add_options();
    }

    public static function bap_error_handler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        echo '<strong>' . $errstr . '</strong>';
        die();
    }

    /**
     * Verifies whether the other version of the plugin is activated
     *
     * @return bool
     * @since 0.2.1
     */
    public static function book_a_place_plugin_verification()
    {
        $installedplugins = get_option('active_plugins');
        $found = false;
        foreach ($installedplugins as $key => $value) {
            $pos = strpos($value, 'book-a-place.php');
            if ($pos === false) {
            } else {
                $found = true;
            }
        }
        return $found;
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    0.1.0
     *
     * @param    boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public static function deactivate($network_wide)
    {
        // TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.1.0
     */
    public function load_plugin_textdomain()
    {
        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)) . '/languages/');

        $this->set_texts();
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     0.1.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles()
    {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();

        if ($screen->id == $this->plugin_screen_hook_suffix || $screen->id == $this->schemes_page_screen_hook_suffix || $screen->id == $this->settings_page_screen_hook_suffix || $screen->id == $this->events_page_screen_hook_suffix || $screen->id == $this->orders_page_screen_hook_suffix) {
            wp_enqueue_style($this->plugin_slug . '-jquery-ui-theme', plugins_url('css/jquery-ui-themes/smoothness/jquery-ui-1.10.3.custom.min.css', __FILE__), array(), $this->version);
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('css/admin.css', __FILE__), array(), $this->version);
            wp_enqueue_style('wp-color-picker');
        }

        if ($screen->id == $this->events_page_screen_hook_suffix) {
            wp_enqueue_style($this->plugin_slug . '-fullcalendar', plugins_url('lib/fullcalendar/fullcalendar/fullcalendar.css', __FILE__), array(), $this->version);
            wp_enqueue_style($this->plugin_slug . '-fullcalendar-print', plugins_url('lib/fullcalendar/fullcalendar/fullcalendar.print.css', __FILE__), array(), $this->version, 'print');
            wp_enqueue_style('wp-color-picker');
        }

    }


    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     0.1.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts()
    {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix || $screen->id == $this->schemes_page_screen_hook_suffix || $screen->id == $this->settings_page_screen_hook_suffix || $screen->id == $this->orders_page_screen_hook_suffix) {
            wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('js/admin.js', __FILE__), array(
                    'jquery',
                    'jquery-ui-core',
                    'jquery-ui-button',
                    'jquery-ui-dialog',
                    'wp-color-picker',
                    'jquery-ui-widget',
                    'jquery-ui-position',
                    'jquery-ui-tooltip',
                    'jquery-ui-tabs',
                    'jquery-ui-datepicker',
                ), $this->version);

            wp_localize_script($this->plugin_slug . '-admin-script', 'bap_object', array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'loc_strings' => $this->get_loc_strings(),
                ));
        }

        if ($screen->id == $this->events_page_screen_hook_suffix) {
            wp_enqueue_script($this->plugin_slug . '-fullcalendar', plugins_url('lib/fullcalendar/fullcalendar/fullcalendar.min.js', __FILE__), array(
                    'jquery',
                    'jquery-ui-core',
                    'jquery-ui-draggable',
                    'jquery-ui-resizable',
                    'jquery-ui-dialog',
                    'wp-color-picker',
                ), $this->version);
        }

    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    0.1.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-jquery-ui-theme', plugins_url('css/jquery-ui-themes/smoothness/jquery-ui-1.10.3.custom.min.css', __FILE__), array(), $this->version);
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('css/public.css', __FILE__), array(), $this->version);
    }

    /**
     * Register and enqueue public-facing JavaScript files.
     *
     * @since    0.1.0
     */
    public function enqueue_scripts()
    {
        wp_register_script($this->plugin_slug . '-kkcountdown', plugins_url('js/kkcountdown.js', __FILE__), array('jquery',), $this->version);
        wp_register_script($this->plugin_slug . '-jquery-block-ui', plugins_url('js/jquery.blockUI.js', __FILE__), array('jquery',), $this->version);
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('js/public.js', __FILE__), array(
                $this->plugin_slug . '-kkcountdown',
                $this->plugin_slug . '-jquery-block-ui',
                'jquery-ui-core',
                'jquery-ui-widget',
                'jquery-ui-position',
                'jquery-ui-tooltip',
                'jquery-ui-button',
                'jquery-ui-dialog'
            ), $this->version);
        wp_localize_script($this->plugin_slug . '-plugin-script', 'bap_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'loc_strings' => $this->get_loc_strings(),
            ));
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    0.1.0
     */
    public function add_plugin_admin_menu()
    {
        $this->plugin_screen_hook_suffix = add_menu_page(__('Book a Place', $this->plugin_slug), __('Book a Place', $this->plugin_slug), 'manage_options', $this->plugin_slug, array(
            $this,
            'display_plugin_admin_page'
        ));

        $this->schemes_page_screen_hook_suffix = add_submenu_page($this->plugin_slug, __('Schemes', $this->plugin_slug), __('Schemes', $this->plugin_slug), 'manage_options', $this->plugin_slug . '-schemes', array(
                $this,
                'display_plugin_admin_schemes_page'
            ));
        $this->events_page_screen_hook_suffix = add_submenu_page($this->plugin_slug, __('Events', $this->plugin_slug), __('Events', $this->plugin_slug), 'manage_options', $this->plugin_slug . '-events', array(
                $this,
                'display_plugin_admin_events_page'
            ));
        $this->orders_page_screen_hook_suffix = add_submenu_page($this->plugin_slug, __('Orders', $this->plugin_slug), __('Orders', $this->plugin_slug), 'manage_options', $this->plugin_slug . '-orders', array(
                $this,
                'display_plugin_admin_orders_page'
            ));
        add_action('load-' . $this->orders_page_screen_hook_suffix, array(
                $this,
                'custom_ob_start'
            ));
        $this->settings_page_screen_hook_suffix = add_submenu_page($this->plugin_slug, __('Settings', $this->plugin_slug), __('Settings', $this->plugin_slug), 'manage_options', $this->plugin_slug . '-settings', array(
                $this,
                'display_plugin_admin_settings_page'
            ));

        add_action('load-' . $this->schemes_page_screen_hook_suffix, array(
                $this,
                'custom_ob_start'
            ));
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    0.1.0
     */
    public function display_plugin_admin_page()
    {
        include_once('views/admin.php');
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    0.1.0
     */
    public function display_plugin_admin_schemes_page()
    {
        include_once('views/admin-schemes.php');
    }

    public function display_plugin_admin_orders_page()
    {
        include_once('views/admin-orders.php');
    }

    public function display_plugin_admin_settings_page()
    {
        include_once('views/admin-settings.php');
    }

    public function display_plugin_admin_events_page()
    {
        include_once('views/admin-events.php');
    }

    public static function register_tables()
    {
        global $wpdb;
        $wpdb->bap_schemes = "{$wpdb->prefix}bap_schemes";
        $wpdb->bap_places = "{$wpdb->prefix}bap_places";
        $wpdb->bap_carts = "{$wpdb->prefix}bap_carts";
        $wpdb->bap_orders = "{$wpdb->prefix}bap_orders";
        $wpdb->bap_events = "{$wpdb->prefix}bap_events";
    }

    public static function create_tables()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;

        $wpdb->hide_errors();

        global $charset_collate;
        // Call this manually as we may have missed the init hook
        self::register_tables();

        // bap_schemes
        $sql_create_table = "CREATE TABLE `{$wpdb->bap_schemes}` (
                                  `scheme_id` int(11) NOT NULL AUTO_INCREMENT,
                                  `name` varchar(255) NOT NULL,
                                  `width` int(11) NOT NULL,
                                  `height` int(11) NOT NULL,
                                  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
                                  `description` text NOT NULL,
                                  `purchase_limit` int(11) NOT NULL,                                  
                                  PRIMARY  KEY (`scheme_id`)
                                ) $charset_collate; ";
        dbDelta($sql_create_table);

        // bap_places
        $sql_create_table = "CREATE TABLE `{$wpdb->bap_places}` (
                                  `place_id` int(11) NOT NULL AUTO_INCREMENT,
                                  `type` int(11) NOT NULL DEFAULT '0',
                                  `cells` text NOT NULL,
                                  `name` varchar(255) NOT NULL,
                                  `description` varchar(255) DEFAULT NULL,
                                  `price` float NOT NULL DEFAULT '0',
                                  `scheme_id` int(11) NOT NULL,
                                  `status_id` int(11) NOT NULL,
                                  `color` varchar(255) DEFAULT NULL,
                                  PRIMARY  KEY (`place_id`),
                                  KEY `scheme_id` (`scheme_id`)
                                ) $charset_collate;";
        dbDelta($sql_create_table);
        $sql_add_constraint = "ALTER TABLE `{$wpdb->bap_places}`
                                    ADD CONSTRAINT `{$wpdb->bap_places}_ibfk_1` FOREIGN KEY (`scheme_id`) REFERENCES `{$wpdb->bap_schemes}` (`scheme_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
        dbDelta($sql_add_constraint);

        // bap_carts
        $sql_create_table = "CREATE TABLE `{$wpdb->bap_carts}` (
                                  `session_id` varchar(255) NOT NULL,
                                  `place_id` int(11) NOT NULL,
                                  `date` datetime NOT NULL,
                                  KEY `place_id` (`place_id`)
                                ) $charset_collate;";
        dbDelta($sql_create_table);
        $sql_add_constraint = "ALTER TABLE `{$wpdb->bap_carts}`
                                    ADD CONSTRAINT `{$wpdb->bap_carts}_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `{$wpdb->bap_places}` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
        dbDelta($sql_add_constraint);

        // bap_orders
        $sql_create_table = "CREATE TABLE `{$wpdb->bap_orders}` (
                                  `order_id` int(11) NOT NULL AUTO_INCREMENT,
                                  `first_name` varchar(255) DEFAULT NULL,
                                  `last_name` varchar(255) DEFAULT NULL,
                                  `email` varchar(255) DEFAULT NULL,
                                  `phone` varchar(255) DEFAULT NULL,
                                  `notes` text,
                                  `date` datetime DEFAULT NULL,
                                  `code` varchar(255) DEFAULT NULL,
                                  `places` text,
                                  `total_price` float NOT NULL DEFAULT '0',
                                  `status_id` int(11) NOT NULL DEFAULT '0',
                                  `admin_notes` text,
                                  `scheme_id` int(11) NOT NULL DEFAULT '0',
                                  `event_id` int(11) DEFAULT NULL,
                                  `event_name` varchar(255) DEFAULT NULL,
                                  `event` text,
                                  PRIMARY  KEY (`order_id`)
                                ) $charset_collate; ";
        dbDelta($sql_create_table);

        // bap_events
        $sql_create_table = "CREATE TABLE `{$wpdb->bap_events}` (
                                  `event_id` int(11) NOT NULL AUTO_INCREMENT,
                                  `scheme_id` int(11) DEFAULT NULL,
                                  `name` varchar(255) NOT NULL,
                                  `description` varchar(255) DEFAULT NULL,
                                  `url` varchar(255) DEFAULT NULL,
                                  `hours` float NOT NULL DEFAULT '0',
                                  `background_color` varchar(255) DEFAULT NULL,
                                  `border_color` varchar(255) DEFAULT NULL,
                                  `text_color` varchar(255) DEFAULT NULL,
                                  `start` datetime NOT NULL,
                                  `end` datetime NOT NULL,
                                  `timezone_offset` float NOT NULL DEFAULT '0',
                                  `all_day` int(11) NOT NULL DEFAULT '1',
                                  `status_id` int(11) NOT NULL,
                                  PRIMARY  KEY (`event_id`),
                                  KEY `scheme_id` (`scheme_id`)
                                ) $charset_collate;";
        dbDelta($sql_create_table);
        $sql_add_constraint = "ALTER TABLE `{$wpdb->bap_events}`
                                    ADD CONSTRAINT `{$wpdb->bap_events}_ibfk_1` FOREIGN KEY (`scheme_id`) REFERENCES `{$wpdb->bap_schemes}` (`scheme_id`) ON DELETE SET NULL ON UPDATE CASCADE;";
        dbDelta($sql_add_constraint);

        $wpdb->show_errors();
    }

    public static function add_options()
    {
        $default_options = array(
            'plugin_version' => 0,
            'email' => '',
            'cart-expiration-time' => 5,
            'currency-symbol' => 2,
        );
        $options = get_option(BAP_OPTIONS);
        if (!$options) {
            $add_options = add_option(BAP_OPTIONS, $default_options);
        } else {
            $options = wp_parse_args($options, $default_options);
            $update_options = update_option(BAP_OPTIONS, $options);
        }

        $default_subject_admin = 'New booking <total_price>';
        $default_message_admin = '<first_name> <last_name> has booked:<br/>
<places>

<br/><br/>
Order details:<br/>
Email - <email><br/>
Phone - <phone><br/>
Unique order ID - <code><br/>
Status - <status>';
        $default_options_admin = array(
            'subject' => $default_subject_admin,
            'message' => $default_message_admin,
        );
        $options = get_option(BAP_EMAIL_NEW_ORDER_ADMIN);
        if (!$options) {
            $add_options = add_option(BAP_EMAIL_NEW_ORDER_ADMIN, $default_options_admin);
        }

        $default_subject_user = 'You\'ve just booked places';
        $default_message_user = '<strong>Your order (#<code>):</strong><br/>
Name - <first_name> <last_name><br/>
Email - <email><br/>
Phone - <phone><br/>

<br/><br/>
Regards';
        $default_options_user = array(
            'subject' => $default_subject_user,
            'message' => $default_message_user,
        );
        $options = get_option(BAP_EMAIL_NEW_ORDER_USER);
        if (!$options) {
            $add_options = add_option(BAP_EMAIL_NEW_ORDER_USER, $default_options_user);
        }

        return true;
    }

    public function add_scheme($data)
    {
        global $wpdb;

        return $wpdb->insert($wpdb->bap_schemes, array(
            'name' => stripcslashes($data['scheme-name']),
            'width' => $data['scheme-width'],
            'height' => $data['scheme-height'],
            'is_hidden' => (!empty($data['scheme-hidden'])) ? $data['scheme-hidden'] : 0,
            'description' => stripcslashes($data['scheme-description']),
            'purchase_limit' => (int)$data['scheme-purchase-limit'],
        ), array(
            '%s',
            '%d',
            '%d',
            '%d',
            '%s',
            '%d'
        ));
    }

    public function get_schemes($include_scheme = 0)
    {
        global $wpdb;

        $schemes = $wpdb->get_results("
            SELECT `s`.*, `e`.`name` as `event`, `e`.`start`
            FROM $wpdb->bap_schemes as `s`
            LEFT JOIN $wpdb->bap_events as `e`
            ON `s`.`scheme_id` = `e`.`scheme_id`
            WHERE `e`.`event_id` IS NULL OR `e`.`event_id` IS NOT NULL OR `s`.`scheme_id` = $include_scheme
	    ", OBJECT);

        return $schemes;
    }

    public function get_scheme_by_id($id, $output_type = OBJECT)
    {
        global $wpdb;

        $scheme = $wpdb->get_row("SELECT * FROM $wpdb->bap_schemes WHERE scheme_id = " . (int)$id, $output_type);

        return $scheme;
    }

    public function get_scheme_by_place_id($id, $output_type = OBJECT)
    {
        global $wpdb;

        $place = $this->get_place_by_id($id);

        $scheme = $wpdb->get_row("SELECT * FROM $wpdb->bap_schemes WHERE scheme_id = " . (int)$place['scheme_id'], $output_type);

        return $scheme;
    }

    public function delete_scheme($id)
    {
        global $wpdb;

        return $wpdb->delete($wpdb->bap_schemes, array('scheme_id' => (int)$id), array('%d'));
    }

    public function update_scheme($data)
    {
        global $wpdb;

        return $wpdb->update($wpdb->bap_schemes, array(
            'name' => $data['scheme-name'],
            'width' => $data['scheme-width'],
            'height' => $data['scheme-height'],
            'is_hidden' => $data['scheme-hidden'],
            'description' => stripcslashes($data['scheme-description']),
            'purchase_limit' => (int)$data['scheme-purchase-limit'],
        ), array('scheme_id' => (int)$data['scheme-id']), array(
            '%s',
            '%d',
            '%d',
            '%d',
            '%s',
            '%d'
        ), array('%d'));

    }

    public function display_scheme($id)
    {
        $this->delete_expired_carts();

        $id = (int)trim($id);
        $scheme = $this->get_scheme_by_id($id);

        $cells_by_number = $this->get_cells($id);

        $cell_number = 1;
        $width = $scheme->width * 30;

        $tooltip_content = '';
        $html = '';

        $html .= '<ul id="scheme" data-scheme-id="' . $id . '" style="width: ' . $width . 'px;">';
        $places = array();

        for ($i = 1; $i <= $scheme->height; ++$i) {
            $html .= '<li>';
            $html .= '<ul class="scheme-row clearfix">';
            for ($j = 1; $j <= $scheme->width; ++$j) {

                $cell_type_class = '';
                $style = '';
                $data_place_id = '';
                $place_id_class = '';
                $place_status_class = '';
                if (isset($cells_by_number[$cell_number]) && !empty($cells_by_number[$cell_number])) {

                    $place = $cells_by_number[$cell_number];

                    $style = 'style="';
                    $style .= 'background-color: ' . $place['color'] . ';';
                    $style .= '"';

                    if (isset($place['type']) && $place['type'] != 0) {
                        $cell_type_class = 'scheme-cell-setted scheme-cell-' . $this->place_types[$place['type']];
                    }

                    $data_place_id = 'data-place-id=' . $place['place_id'];
                    $place_id_class = ' scheme-place-' . $place['place_id'];

                    // if the place is in the cart, check in whose cart
                    $place_status_id = ($place['status_id'] == 3 && $place['session_id'] != $this->session_id) ? 4 : $place['status_id'];
                    $place_status_class = ' scheme-place-' . $this->place_statuses[$place_status_id] . ' ';

                    if (!in_array($place['place_id'], $places)) {
                        $tooltip_content .= '<div id="tooltip-scheme-place-' . $place['place_id'] . '">' . __('Status', $this->plugin_slug) . ': ' . $this->place_statuses_labels[$place_status_id];
                        $tooltip_content .= '<br>' . $place['name'];
                        if ($place['status_id'] != 5) {
                            $tooltip_content .= ': ' . $this->places_money_format($place['price']);
                        }
                        $tooltip_content .= '<br>' . $place['description'] . '</div>';
                    }


                    $places[$place['place_id']] = $place['place_id'];
                }

                $html .= '<li ' . $style . ' ' . $data_place_id . ' data-x="' . $j . '" data-y="' . $i . '" data-cell="' . $cell_number . '" class="scheme-cell-selectee scheme-cell ' . $cell_type_class . $place_id_class . $place_status_class . '"><span class="number">' . ($cell_number++) . '</span></li>';
            }
            $html .= '</ul>';
            $html .= '</li>';
        }

        $html .= '</ul>';

        $html .= '<div id="scheme-tooltips">' . $tooltip_content . '</div>';

        return $html;
    }

    public function display_scheme_front($id, $event_id = false)
    {
        $this->delete_expired_carts();

        $html = '';

        if (is_null($this->event_booking_open) && $event_id) {
            $event = $this->get_event_by_id($event_id);
            $this->is_event_booking_open($event);
        }

        if (!is_null($this->event_booking_open)) {
            $html .= '
            <script type="text/javascript">
                var bookAPLaceEventBookingOpen = ' . ($this->event_booking_open ? 1 : 0) . ';
            </script>';
        }

        if (!$event_id) {
            $html .= '
            <script type="text/javascript">
                var bookAPLaceEventBookingOpen = 1;
            </script>';
        }

        if ($this->event_booking_open === false) {
            $html .= '<p>Booking is closed.</p>';
            return $html;
        }

        $id = (int)trim($id);
        $scheme = $this->get_scheme_by_id($id);

        $cells_by_number = $this->get_cells($id);

        $cell_number = 1;
        $width = $scheme->width * 30;

        $tooltip_content = '';
        $html .= '<ul id="scheme" data-event-id="' . $event_id . '" data-scheme-id="' . $id . '" style="width: ' . $width . 'px;">';
        $places = array();

        for ($i = 1; $i <= $scheme->height; ++$i) {
            $html .= '<li>';
            $html .= '<ul class="scheme-row clearfix">';
            for ($j = 1; $j <= $scheme->width; ++$j) {

                $cell_type_class = '';
                $style = '';
                $data_place_id = '';
                $place_id_class = '';
                $place_status_class = '';
                if (isset($cells_by_number[$cell_number]) && !empty($cells_by_number[$cell_number])) {

                    $place = $cells_by_number[$cell_number];

                    $style = 'style="';
                    $style .= 'background-color: ' . $place['color'] . ';';
                    $style .= '"';

                    if (isset($place['type']) && $place['type'] != 0) {
                        $cell_type_class = 'scheme-cell-setted scheme-cell-' . $this->place_types[$place['type']];
                    }

                    $data_place_id = 'data-place-id=' . $place['place_id'];
                    $place_id_class = ' scheme-place-' . $place['place_id'];

                    // if the place is in the cart, check in whose cart
                    $place_status_id = ($place['status_id'] == 3 && $place['session_id'] != $this->session_id) ? 4 : $place['status_id'];
                    $place_status_class = ' scheme-place-' . $this->place_statuses[$place_status_id] . ' ';

                    if (!in_array($place['place_id'], $places)) {
                        $tooltip_content .= '<div id="tooltip-scheme-place-' . $place['place_id'] . '">' . __('Status', $this->plugin_slug) . ': ' . $this->place_statuses_labels[$place_status_id];
                        $tooltip_content .= '<br>' . $place['name'];
                        if ($place['status_id'] != 5) {
                            $tooltip_content .= ': ' . $this->places_money_format($place['price']);
                        }
                        $tooltip_content .= '<br>' . $place['description'] . '</div>';
                    }


                    $places[$place['place_id']] = $place['place_id'];
                }

                $html .= '<li ' . $style . ' ' . $data_place_id . ' data-x="' . $j . '" data-y="' . $i . '" data-cell="' . $cell_number . '" class="scheme-cell-selectee scheme-cell ' . $cell_type_class . $place_id_class . $place_status_class . '"><span class="number"></span></li>';
                $cell_number++;
            }
            $html .= '</ul>';
            $html .= '</li>';
        }

        $html .= '</ul>';

        $html .= '<div id="scheme-tooltips">' . $tooltip_content . '</div>';

        return $html;
    }

    public function set_place()
    {
        global $wpdb;

        $save = $wpdb->insert($wpdb->bap_places, array(
            'type' => $_POST['type'],
            'cells' => serialize($_POST['cells']),
            'name' => stripslashes($_POST['name']),
            'description' => stripslashes($_POST['description']),
            'price' => $_POST['price'],
            'scheme_id' => $_POST['scheme_id'],
            'status_id' => 1,
            'color' => $_POST['color'],
        ), array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%f',
            '%d',
            '%d',
            '%s',
        ));

        $this->refresh_scheme();
        die();
    }

    public function unset_place()
    {
        global $wpdb;

        $delete = $wpdb->delete($wpdb->bap_places, array('place_id' => $_POST['place_id']), array('%d'));

        $this->refresh_scheme();
        die();
    }

    public function update_place()
    {
        global $wpdb;

        $update = $wpdb->update($wpdb->bap_places, array(
            'name' => stripslashes($_POST['name']),
            'description' => stripslashes($_POST['description']),
            'price' => $_POST['price'],
            'color' => $_POST['color'],
        ), array('place_id' => (int)$_POST['place_id']), array(
            '%s',
            '%s',
            '%f',
            '%s',
        ), array('%d'));

        $this->refresh_scheme();
        die();
    }

    public function edit_place()
    {
        $place = $this->get_place_by_id($_POST['place_id']);
        echo json_encode($place);
        die();
    }

    public function get_places_with_carts($scheme_id, $output_type = OBJECT)
    {
        global $wpdb;

        $places = $wpdb->get_results("SELECT p.*, c.session_id FROM $wpdb->bap_places AS p LEFT JOIN $wpdb->bap_carts AS c ON c.place_id=p.place_id WHERE scheme_id = " . (int)$scheme_id, $output_type);

        return $places;
    }

    public function get_places($scheme_id, $output_type = OBJECT)
    {
        global $wpdb;

        $places = $wpdb->get_results("SELECT * FROM $wpdb->bap_places WHERE scheme_id = " . (int)$scheme_id, $output_type);

        return $places;
    }

    public function get_place_by_id($place_id)
    {
        global $wpdb;

        $place = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->bap_places WHERE place_id = %d", (int)$place_id), ARRAY_A);

        return $place;
    }

    public function get_cells($scheme_id)
    {
        $places = $this->get_places_with_carts($scheme_id);

        $cells_by_id = array();

        if (!empty($places) && is_array($places)) {
            foreach ($places as $place) {
                $cells = unserialize($place->cells);
                if (!empty($cells) && is_array($cells)) {
                    foreach ($cells as $cell) {
                        foreach ($place as $key => $value) {
                            $cells_by_id[$cell][$key] = $value;
                        }
                    }

                }
            }

        }

        return $cells_by_id;
    }

    public function refresh_scheme_front()
    {
        $id = $_POST['scheme_id'];
        $event_id = $_POST['event_id'];

        $scheme = $this->display_scheme_front($id, $event_id);

        echo $scheme;
        die();
    }

    public function refresh_scheme()
    {
        $id = $_POST['scheme_id'];

        $scheme = $this->display_scheme($id);

        echo $scheme;
        die();
    }

    public function book_a_place_shortcode($atts)
    {
        //extract(shortcode_atts(array('scheme' => 1), $atts));
        $scheme = (!empty($atts['scheme'])) ? $atts['scheme'] : 1;

        $scheme_details = $this->get_scheme_by_id($scheme);

        if (!$scheme_details) {
            $html = '<div id="book-a-place-scheme">';
            $html .= '<h2>There is no such scheme</h2>';
            $html .= '</div>';

            return $html;
        }

        $html = '<div id="book-a-place-scheme">';

        // feedback after offline booking
        if (isset($_GET['s_msg']) && !empty($_GET['s_msg'])) {
            $msg_id = (int) $_GET['s_msg'];
            $html .= '<div id="payment-success">' . $this->success_user_messages[$msg_id] . '</div>';
        }
        if (isset($_GET['e_msg']) && !empty($_GET['e_msg'])) {
            $msg_id = (int) $_GET['e_msg'];
            $html .= '<div id="payment-error">' . $this->error_user_messages[$msg_id] . '</div>';
        }

        $html .= '<h2>' . $scheme_details->name . '</h2>';

        $html .= '<p>' . $scheme_details->description . '</p>';

        if ($scheme_details->is_hidden) {
            $scheme_container_style = ' style="display: none;"';
            $scheme_show_text = __('Show Scheme', $this->plugin_slug);
            $html .= '<a id="scheme-container-visibility" data-visible="0" href="#">' . $scheme_show_text . '</a>';
        } else {
            $scheme_container_style = '';
        }

        $html .= '<div id="scheme-container"' . $scheme_container_style . '>';
        $html .= $this->display_scheme_front($scheme);
        $html .= '</div>';

        $html .= '<div id="shopping-cart-container">';
        $html .= $this->display_cart($scheme);
        $html .= '</div>';

        $html .= '<div id="shopping-cart-controls-container">';
        $html .= $this->display_cart_controls();
        $html .= '</div>';

        $html .= '<div id="scheme-warning-message" title="' . __("Warning!", $this->plugin_slug) . '"></div>';

        $html .= '</div>';

        return $html;
    }

    public function places_money_format($number)
    {
        $currency_symbols = $this->get_currency_symbols();
        return $currency_symbols[$this->options['currency-symbol']] . number_format($number, 2, '.', ',');
    }

    public function get_place_statuses()
    {
        $place = $this->get_place_by_id($_POST['place_id']);

        $html = '';

        if (is_array($this->place_statuses_labels)) {
            foreach ($this->place_statuses_labels as $status_id => $status_label) {
                $selected = ($status_id == $place['status_id']) ? 'selected="selected"' : '';
                $html .= '<option ' . $selected . ' value="' . $status_id . '">' . $status_label . '</option>';
            }

        }

        echo $html;
        die();
    }

    public function update_place_status($place_id, $status_id)
    {
        if (empty($place_id) || empty($status_id)) return false;

        global $wpdb;

        if (is_array($place_id)) {

            $sql = "
            UPDATE `{$wpdb->bap_places}`
            SET `status_id` = '$status_id'
            WHERE 1 AND (
            ";

            end($place_id);
            $last_key = key($place_id);
            reset($place_id);

            foreach ($place_id as $pid => $place) {
                $sql .= " `place_id` = '$pid' ";
                if ($pid != $last_key) $sql .= " || ";
            }

            $sql .= ")";

            $update = $wpdb->query($sql);

        } else {

            $update = $wpdb->update($wpdb->bap_places, array('status_id' => (int)$status_id,), array('place_id' => (int)$place_id), array('%d',), array('%d'));

        }

        return $update;
    }

    public function ajax_update_place_status()
    {
        $update = $this->update_place_status($_POST['place_id'], $_POST['status_id']);

        $this->refresh_scheme();
        die();
    }

    public function display_cart($scheme_id)
    {
        $scheme = $this->get_scheme_by_id($scheme_id);

        $places_in_cart = $this->get_places_in_cart($scheme_id);

        $time_left = $this->calculate_time_left($places_in_cart);

        $html = '<h3>' . __("Cart", $this->plugin_slug) . '</h3>
    <p id="bap-countdown-container">
        ' . __("The cart will be cleared in", $this->plugin_slug) . ': <span id="cart-expiration-time" class="kkcount-down" data-time-left="' . $time_left . '" data-time="1"></span>
    </p>';

        if ($scheme->purchase_limit > 0) {
            $places_in_cart_remainder = $scheme->purchase_limit - count($places_in_cart);
            $html .= '
                <p>
                    ' . __("The number of places you are allowed to add", $this->plugin_slug) . ': ' . $places_in_cart_remainder . '
                </p>
            ';
        }

        $html .= '
    <table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>' . __("Name", $this->plugin_slug) . '</th>
        <th>' . __("Price", $this->plugin_slug) . '</th>
        <th></th>
    </tr>
    </thead>
    <tbody>';

        $total_price = 0;
        if ($places_in_cart && is_array($places_in_cart)) {
            foreach ($places_in_cart as $key => $place) {
                ++$key;
                $total_price += $place['place_price'];
                $html .= "<tr class='bap-place-in-cart'>
        <td>$key</td>
        <td>{$place['place_name']}</td>
        <td>{$this->places_money_format($place['place_price'])}</td>
        <td><a class='delete_from_cart' data-place-id='{$place['place_id']}' href='#'>" . __('Delete', $this->plugin_slug) . "</a></td>
    </tr>";
            }

        } else {
            $html .= '<tr>
        <td colspan="4">' . __("There are no places in the cart.", $this->plugin_slug) . '</td>
    </tr>';
        }

        $html .= "</tbody>
    <tfoot>
    <tr>
        <th colspan=\"2\">" . __('Total Price', $this->plugin_slug) . "</th>
        <th colspan=\"2\">{$this->places_money_format($total_price)}</th>
    </tr>
    </tfoot>
</table>";

        return $html;
    }

    public function display_cart_controls()
    {
        $html = '<div id="cart-controls">
    <input type="submit" id="cart-checkout" value="' . __("Checkout", $this->plugin_slug) . '" />
</div>';

        $html .= '<div id="bap-cart-form-dialog" title="' . __("Checkout", $this->plugin_slug) . '">
    <p class="validateTips">' . sprintf(__('Fields with %s are required.', $this->plugin_slug), '<span class="required">*</span>') . '</p>

    <form>
        <fieldset>
            <input type="hidden" name="action" value=""/>
            <div class="field">
            <label for="checkout-first-name">' . __("First Name", $this->plugin_slug) . ' <span class="required">*</span></label>
            <input type="text" name="checkout-first-name" id="checkout-first-name" class="text"/>
            </div>
            <div class="field">
            <label for="checkout-last-name">' . __("Last Name", $this->plugin_slug) . ' <span class="required">*</span></label>
            <input type="text" name="checkout-last-name" id="checkout-last-name" value="" class="text"/>
            </div>
            <div class="field">
            <label for="checkout-email">' . __("Email", $this->plugin_slug) . ' <span class="required">*</span></label>
            <input type="text" name="checkout-email" id="checkout-email" value="" class="text"/>
            </div>
            <div class="field">
            <label for="checkout-phone">' . __("Phone", $this->plugin_slug) . ' <span class="required">*</span></label>
            <input type="text" name="checkout-phone" id="checkout-phone" value="" class="text"/>
            <p class="input-notice">Only digits, e.g. 15417543010</p>
            </div>
            <label for="checkout-notes">' . __("Notes", $this->plugin_slug) . '</label>
            <textarea name="checkout-notes" id="checkout-notes" class="text"></textarea>

        </fieldset>
    </form>
</div>';

        $html .= '<div id="bap-empty-cart-dialog" title="' . __("Checkout", $this->plugin_slug) . '">
    <p class="validateTips">' . __('Please add to your cart at least one place.', $this->plugin_slug) . '</p>
    </div>';

        return $html;
    }

    public function refresh_cart()
    {
        $scheme_id = (int) $_POST['scheme_id'];
        $cart = $this->display_cart($scheme_id);

        echo $cart;
        die();
    }

    public function add_to_cart()
    {
        global $wpdb;

        $scheme_id = (int) $_POST['scheme_id'];

        $session_id = $this->session_id;

        $scheme = $this->get_scheme_by_id($scheme_id);

        $places_ids = $this->get_places_ids_in_cart_by_scheme_id($scheme_id);

        if ($scheme->purchase_limit > 0 && count($places_ids) >= $scheme->purchase_limit) {
            echo "limit";
            die();
        }

        $place_in_cart = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->bap_carts WHERE place_id = %d", (int)$_POST['place_id']), ARRAY_A);

        if (!empty($place_in_cart)) {
            echo '0';
            die();
        }

        $save = $wpdb->insert($wpdb->bap_carts, array(
            'session_id' => $session_id,
            'place_id' => (int)$_POST['place_id'],
            'date' => date('Y-m-d H:i:s'),
        ), array(
            '%s',
            '%d',
            '%s'
        ));

        $this->update_cart_time($session_id, $places_ids);

        $update = $this->update_place_status($_POST['place_id'], 3);

        $this->refresh_shortcode_content();
        die();
    }

    public function refresh_shortcode_content($return = false)
    {
        $id = $_POST['scheme_id'];
        $event_id = $_POST['event_id'];

        if ($event_id == 0) {
            $content = $this->book_a_place_shortcode(array('scheme' => $id));
        } else {
            $content = $this->book_a_place_event_shortcode(array('id' => $event_id));
        }

        if ($return) {
            return $content;
        } else {
            echo $content;
            die();
        }
    }

    public function delete_from_cart()
    {
        global $wpdb;

        $session_id = $this->session_id;

        $delete = $wpdb->delete($wpdb->bap_carts, array(
            'session_id' => $session_id,
            'place_id' => (int)$_POST['place_id'],
        ), array(
            '%s',
            '%d'
        ));

        $scheme_id = (int) $_POST['scheme_id'];

        $places_ids = $this->get_places_ids_in_cart_by_scheme_id($scheme_id);

        $this->update_cart_time($session_id, $places_ids);

        $update = $this->update_place_status($_POST['place_id'], 1);

        $this->refresh_shortcode_content();
        die();
    }

    public function checkout()
    {
        global $wpdb;

        // event verifications
        if (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
            $event = $this->get_event_by_id($_POST['event_id'], ARRAY_A);
            if (!$this->is_event_booking_open($event)) return false;
        }

        $scheme_id = (int) $_POST['scheme_id'];

        $places_in_cart = $this->get_places_in_cart($scheme_id);
        $places_list = array();
        $total_price = 0;

        if ($places_in_cart && is_array($places_in_cart)) {
            foreach ($places_in_cart as $place) {
                $places_list[$place['place_id']] = array(
                    'place_name' => $place['place_name'],
                    'place_price' => $place['place_price'],
                );
                $total_price += $place['place_price'];
            }
        } else {
            return false;
        }

        $event_id = '';
        $event_name = '';
        if ($event) {
            $event_id = $event['event_id'];
            $event_name = $event['name'];
        }

        $save_order = $wpdb->insert($wpdb->bap_orders, array(
            'first_name' => stripslashes($_POST['first_name']),
            'last_name' => stripslashes($_POST['last_name']),
            'email' => stripslashes($_POST['email']),
            'phone' => stripslashes($_POST['phone']),
            'notes' => stripslashes($_POST['notes']),
            'date' => current_time('mysql'),
            'places' => serialize($places_list),
            'total_price' => $total_price,
            'status_id' => 1,
            'scheme_id' => $scheme_id,
            'event_id' => $event_id,
            'event_name' => $event_name,
            'event' => serialize($event),
        ), array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%f',
            '%d',
            '%d',
            '%d',
            '%s',
            '%s'
        ));

        if ($save_order) {
            $order_id = $wpdb->insert_id;
            $this->generate_order_code($order_id);
            $update_place_status = $this->update_place_status($places_list, 2);
            $clear_cart = $this->clear_cart(array_keys($places_list));
            $order = $this->get_order_by_id($order_id);
            $this->send_mail(BAP_EMAIL_NEW_ORDER_ADMIN, $order);
            $this->send_mail(BAP_EMAIL_NEW_ORDER_USER, $order, $order->email);
        }

        return $save_order && $update_place_status && $clear_cart;
    }

    public function ajax_checkout()
    {
        $isCheckoutValid = $this->checkout_validation();
        if ($isCheckoutValid === false) {
            echo json_encode(array(
                'error'         => 'limit',
                'error_message' => __('You\'ve already reached the purchasing limit for this event', $this->plugin_slug)
            ));
            die;
        }

        $checkout = $this->checkout();

        $out = $this->refresh_shortcode_content(true);

        if (!$checkout) {
            $out['msg'] = 'e_msg=1';
        } else {
            $out['msg'] = 's_msg=1';
        }

        echo json_encode($out);
        die();
    }

    /**
     * @return bool
     * @since 0.7.0
     */
    public function checkout_validation()
    {
        $scheme = $this->get_scheme_by_id($_POST['scheme_id']);

        if ($scheme->purchase_limit > 0) {
            $user_booked_places_count = 0;
            $orders = $this->get_orders_by_email_and_scheme_id($_POST['email'], $_POST['scheme_id']);
            if (is_array($orders) && !empty($orders)) {
                foreach ($orders as $order) {
                    $order_places = unserialize($order->places);
                    $user_booked_places_count += count($order_places);
                }
            }

            if ($user_booked_places_count >= $scheme->purchase_limit) {
                return false;
            }
        }

        return true;
    }

    public function get_places_in_cart($scheme_id)
    {
        global $wpdb;
        $places_in_carts = $wpdb->get_results($wpdb->prepare("SELECT c.place_id, p.name AS place_name, p.scheme_id AS place_scheme_id, p.price AS place_price, c.date FROM $wpdb->bap_carts AS c LEFT JOIN $wpdb->bap_places AS p ON c.place_id = p.place_id WHERE session_id = %s", $this->session_id), ARRAY_A);

        $places_in_cart = $this->filter_places_by_scheme_id($places_in_carts, $scheme_id);

        return $places_in_cart;
    }

    public function clear_all_carts($session_id = false)
    {
        if (!$session_id) {
            $session_id = $this->session_id;
        }

        global $wpdb;
        $delete = $wpdb->get_results($wpdb->prepare("DELETE FROM $wpdb->bap_carts WHERE session_id = %s", $session_id), ARRAY_A);

        return $delete;
    }

    public function clear_cart($places_ids, $session_id = false)
    {
        if (!$session_id) {
            $session_id = $this->session_id;
        }

        $in = implode(",", $places_ids);

        global $wpdb;
        $delete = $wpdb->get_results($wpdb->prepare("DELETE FROM $wpdb->bap_carts WHERE session_id = %s AND place_id IN ($in)", $session_id), ARRAY_A);

        return $delete;
    }

    public function generate_order_code($order_id)
    {
        $order_code = substr($order_id . md5($order_id), 0, 8);
        global $wpdb;
        return $wpdb->update($wpdb->bap_orders, array('code' => $order_code,), array('order_id' => $order_id), array('%s',), array('%d'));
    }

    public function get_orders()
    {
        global $wpdb;

        $orders = $wpdb->get_results("
        SELECT *
        FROM `{$wpdb->bap_orders}`
        WHERE 1
        ORDER BY `order_id` DESC
	    ");

        return $orders;
    }

    public function get_order_by_id($order_id)
    {
        global $wpdb;

        $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->bap_orders WHERE order_id = %d", (int)$order_id));

        return $order;
    }

    /**
     * @param $email
     * @param $scheme_id
     *
     * @return array|null|object
     *
     * @since 0.7.0
     */
    public function get_orders_by_email_and_scheme_id($email, $scheme_id)
    {
        global $wpdb;

        $orders = $wpdb->get_results($wpdb->prepare("
        SELECT *
        FROM `{$wpdb->bap_orders}`
        WHERE `email` = %s AND `scheme_id` = %d
        ORDER BY `order_id` DESC
	    ", array($email, $scheme_id)));

        return $orders;
    }

    public function update_order($order_id)
    {
        global $wpdb;
        $update = $wpdb->update($wpdb->bap_orders, array(
            'admin_notes' => strip_tags(stripslashes($_POST['order-admin-notes'])),
            'status_id' => strip_tags(stripslashes($_POST['order-status'])),
        ), array('order_id' => $order_id), array('%s',), array('%d'));
        return $update;
    }

    public function get_order_statuses_options($order)
    {
        $html = '';

        if (!empty($this->order_statuses) && is_array($this->order_statuses)) {
            foreach ($this->order_statuses as $order_status_id => $order_status) {
                $html .= '
                <option value="' . $order_status_id . '" ' . selected($order_status_id, $order->status_id, false) . '>' . $order_status . '</option>
                ';
            }
        }

        return $html;
    }

    private function send_mail($template_name, $data, $email_to = false)
    {
        $options = get_option(BAP_OPTIONS);

        if ($template_name == BAP_EMAIL_NEW_ORDER_ADMIN || $template_name == BAP_EMAIL_NEW_ORDER_USER) {
            $compiled_template = $this->compile_template_new_order($template_name, $data);
        }

        if (!$email_to) {
            $email_to = (isset($options['email']) && !empty($options['email'])) ? $options['email'] : get_option('admin_email');
        }

        $headers = 'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>' . "\r\n";

        add_filter('wp_mail_content_type', array(
            $this,
            'set_html_content_type'
        ));

        $send_mail = wp_mail($email_to, $compiled_template['subject'], $compiled_template['message'], $headers);

        // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
        remove_filter('wp_mail_content_type', array(
            $this,
            'set_html_content_type'
        ));

        return $send_mail;
    }

    public function set_html_content_type()
    {
        return 'text/html';
    }

    private function compile_template_new_order($template_name, $data)
    {
        $template = get_option($template_name);

        $places = unserialize($data->places);

        $places_html = '';
        if (is_array($places)) {
            foreach ($places as $place_id => $place) {
                $places_html .= '#' . $place_id . ': ' . $place['place_name'] . ' (' . $this->places_money_format($place['place_price']) . ') <br />';
            }
        }

        $place_ids = array_keys($places);
        $scheme = $this->get_scheme_by_place_id($place_ids[0]);

        if ($data->event_id) {
            $event = $this->get_event_by_id($data->event_id);
        }

        $order_url = str_replace('admin-ajax.php', 'admin.php', $this->page_url) . '?page=' . $this->plugin_slug . '-orders&order=' . $data->order_id . '&action=view';

        $search = array(
            '<first_name>',
            '<last_name>',
            '<email>',
            '<phone>',
            '<notes>',
            '<date>',
            '<code>',
            '<places>',
            '<total_price>',
            '<status>',
            '<event_name>',
            '<event_start>',
            '<event_end>',
            '<scheme_name>',
            '<order_url>',
        );

        $replace = array(
            $data->first_name,
            $data->last_name,
            $data->email,
            $data->phone,
            $data->notes,
            $data->date,
            $data->code,
            $places_html,
            $this->places_money_format($data->total_price),
            $this->order_statuses[$data->status_id],
            $data->event_name,
            isset($event) ? $event->start : '',
            isset($event) ? $event->end : '',
            $scheme->name,
            $order_url,
        );

        $text = str_replace($search, $replace, $template);

        return $text;
    }

    private function update_cart_time($session_id, $places_ids)
    {
        global $wpdb;
        $in = implode(',', $places_ids);
        $update = $wpdb->query($wpdb->prepare("UPDATE `$wpdb->bap_carts` SET `date`=%s WHERE `session_id`=%s AND `place_id` IN ($in)", date('Y-m-d H:i:s'), $session_id));
        return $update;
    }

    private function calculate_time_left($places_in_cart)
    {
        $options = get_option(BAP_OPTIONS);
        $cart_expiration_time = $options['cart-expiration-time'] * 60;
        $time_left = 0;
        if (!empty($places_in_cart) && is_array($places_in_cart)) {
            $p = current($places_in_cart);
            $time = strtotime($p['date']);
            $time_left = $cart_expiration_time - (time() - $time);
        }

        return $time_left;
    }

    public function get_carts()
    {
        global $wpdb;

        $orders = $wpdb->get_results("
        SELECT *
        FROM $wpdb->bap_carts
        WHERE 1
	    ");

        return $orders;
    }

    private function delete_expired_carts()
    {
        $places_in_carts = $this->get_carts();

        $options = get_option(BAP_OPTIONS);
        $cart_expiration_time = $options['cart-expiration-time'] * 60;

        $carts_to_clear = array();
        if (!empty($places_in_carts) && is_array($places_in_carts)) {
            foreach ($places_in_carts as $place) {
                if (strtotime($place->date) + $cart_expiration_time < time()) {
                    $carts_to_clear[$place->session_id]['cart_date'] = $place->date;
                    $carts_to_clear[$place->session_id]['cart_places'][$place->place_id] = $place->place_id;
                }
            }

        }

        if (!empty($carts_to_clear) && is_array($carts_to_clear)) {
            foreach ($carts_to_clear as $session_id => $cart) {
                $this->clear_cart($cart['cart_places'], $session_id);
                $this->update_place_status($cart['cart_places'], 1);
            }

        }

        return true;
    }

    public function custom_ob_start()
    {
        ob_start();
    }

    private function redirect_to_schemes_list($get_url = false)
    {
        $redirect_to_page = explode('_page_', $this->schemes_page_screen_hook_suffix);
        $url = admin_url('admin.php?page=' . $redirect_to_page[1]);
        if ($get_url) {
            return $url;
        } else {
            wp_redirect($url);
        }
        exit;
    }

    private function duplicate_scheme($scheme_id)
    {
        $scheme = $this->get_scheme_by_id($scheme_id, ARRAY_A);

        $copy_scheme_id = $this->copy_scheme($scheme);

        if ($copy_scheme_id) {
            $this->copy_places($scheme_id, $copy_scheme_id);
        }

        return true;
    }

    private function copy_scheme($scheme)
    {
        global $wpdb;

        if (!empty($scheme) && is_array($scheme)) {
            $scheme['scheme_id'] = '';
            $scheme['name'] .= ' - copy';
            $insert = $wpdb->insert($wpdb->bap_schemes, $scheme);
            return $wpdb->insert_id;
        } else {
            return false;
        }
    }

    private function copy_places($from_scheme_id, $to_scheme_id = false)
    {
        $places = $this->get_places($from_scheme_id, ARRAY_A);

        if (!empty($places) && is_array($places)) {
            foreach ($places as $place) {
                $this->copy_place($place, $to_scheme_id);
            }
        }

        return true;
    }

    private function copy_place($place, $to_scheme_id = false)
    {
        global $wpdb;

        if (!empty($place) && is_array($place)) {
            $place['place_id'] = '';
            if ($to_scheme_id) {
                $place['scheme_id'] = $to_scheme_id;
            }
            $insert = $wpdb->insert($wpdb->bap_places, $place);
            return $wpdb->insert_id;
        } else {
            return false;
        }
    }

    public function add_event_ajax()
    {
        $event_id = $this->add_event(array(
            'scheme_id' => $_POST['scheme_id'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'url' => $_POST['url'],
            'start' => $_POST['start'],
            'end' => $_POST['end'],
            'timezone_offset' => $_POST['timezone_offset'],
            'all_day' => $_POST['all_day'],
            'hours' => $_POST['hours'],
            'background_color' => $_POST['background_color'],
            'border_color' => $_POST['border_color'],
            'text_color' => $_POST['text_color']
        ));

        if ($event_id) {
            $error = false;
        } else {
            $error = true;
        }

        echo json_encode(array(
            'error' => $error,
            'event' => array(
                'name' => stripcslashes($_POST['name']),
                'url' => $_POST['url'],
                'id' => $event_id,
                'background_color' => $_POST['background_color'],
                'border_color' => $_POST['border_color'],
                'text_color' => $_POST['text_color']
            ),
        ));

        die();
    }

    private function add_event($data)
    {
        global $wpdb;

        $scheme = $wpdb->get_row("SELECT * FROM $wpdb->bap_schemes WHERE scheme_id = '" . (int)$data['scheme_id'] . "'");
        if (!$scheme) return false;

        $save = $wpdb->insert($wpdb->bap_events, array(
            'scheme_id' => (int)$data['scheme_id'],
            'name' => stripslashes($data['name']),
            'description' => stripslashes($data['description']),
            'url' => $data['url'],
            'start' => $this->convert_calendar_date($data['start'], $data['timezone_offset']),
            'end' => $this->convert_calendar_date($data['end'], $data['timezone_offset']),
            'timezone_offset' => $data['timezone_offset'],
            'all_day' => (int)$data['all_day'],
            'status_id' => isset($data['status_id']) ? $data['status_id'] : 1,
            'hours' => $data['hours'],
            'background_color' => $data['background_color'],
            'border_color' => $data['border_color'],
            'text_color' => $data['text_color']
        ), array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
            '%f',
            '%s',
            '%s',
            '%s',
        ));

        return $wpdb->insert_id;
    }

    public function get_events_json_ajax()
    {
        $start = $this->convert_calendar_date($_POST['start'], $_POST['timezone_offset']);
        $end = $this->convert_calendar_date($_POST['end'], $_POST['timezone_offset']);

        echo $this->get_events_json($start, $end);

        die();
    }

    private function get_events_json($start = null, $end = null)
    {
        $events = $this->get_events($start, $end);
        $events_arr = array();
        if (!empty($events) && is_array($events)) {
            foreach ($events as $event) {
                $events_arr[] = array(
                    'id' => $event->event_id,
                    'title' => $event->name,
                    'start' => $event->start,
                    'end' => $event->end,
                    //'url' => $event->url,
                    'allDay' => $event->all_day == 1 ? true : false,
                    'backgroundColor' => $event->background_color,
                    'borderColor' => $event->border_color,
                    'textColor' => $event->text_color,
                );
            }

        }

        return json_encode($events_arr);
    }

    private function get_events($start = null, $end = null)
    {
        global $wpdb;

        if ($start && $end) {
            $where = "WHERE `start` <= '$end' AND `end` >= '$start'";
        } else {
            $where = "WHERE 1";
        }

        $events = $wpdb->get_results("
        SELECT *
        FROM $wpdb->bap_events
        {$where}
	    ");

        return $events;
    }

    private function convert_calendar_date($ts, $timezone_offset)
    {
        return date("Y-m-d H:i:s", $ts - $timezone_offset * 3600);
    }

    protected function get_schemes_list($include_scheme = 0)
    {
        $schemes = $this->get_schemes($include_scheme);

        $html = '<option value="0">' . __("Select Scheme", $this->plugin_slug) . '</option>';

        if (!empty($schemes) && is_array($schemes)) {
            foreach ($schemes as $scheme) {
                $html .= '<option value="' . $scheme->scheme_id . '">' . $scheme->name . '</option>';
            }
        }

        return $html;
    }

    public function get_schemes_list_ajax()
    {
        $scheme_id = (int) $_POST['scheme_id'];
        echo $this->get_schemes_list($scheme_id);
        die();
    }

    public function move_event_ajax()
    {
        $move = $this->move_event($_POST['event_id'], $_POST['day_delta'], $_POST['minute_delta'], $_POST['all_day']);
        if ($move) {
            $error = false;
        } else {
            $error = true;
        }
        echo json_encode(array('error' => $error));
        die();
    }

    private function move_event($id, $day_delta, $minute_delta, $all_day)
    {
        $event = $this->get_event_by_id($id);

        $delta = $day_delta * 86400 + $minute_delta * 60;
        $start_ts = strtotime($event->start) + $delta;
        $end_ts = strtotime($event->end) + $delta;

        $update = array(
            'start' => date("Y-m-d H:i:s", $start_ts),
            'end' => date("Y-m-d H:i:s", $end_ts),
            'all_day' => (int)$all_day,
        );

        $format = array(
            '%s',
            '%s',
            '%d',
        );

        $move = $this->update_event($id, $update, $format);

        return $move;
    }

    protected function update_event($id, $update, $format)
    {
        global $wpdb;

        return $wpdb->update($wpdb->bap_events, $update, array('event_id' => (int)$id), $format, array('%d'));
    }

    protected function delete_event($id)
    {
        global $wpdb;

        return $wpdb->delete($wpdb->bap_events, array('event_id' => $id), array('%d'));;
    }

    public function get_event_by_id($id, $output_type = OBJECT)
    {
        global $wpdb;
        $event = $wpdb->get_row("SELECT * FROM $wpdb->bap_events WHERE event_id = " . (int)$id, $output_type);
        return $event;
    }

    public function resize_event_ajax()
    {
        $resize = $this->resize_event($_POST['event_id'], $_POST['day_delta'], $_POST['minute_delta']);
        if ($resize) {
            $error = false;
        } else {
            $error = true;
        }
        echo json_encode(array('error' => $error));
        die();
    }

    private function resize_event($id, $day_delta, $minute_delta)
    {
        $event = $this->get_event_by_id($id);

        $delta = $day_delta * 86400 + $minute_delta * 60;
        $end_ts = strtotime($event->end) + $delta;

        $update = array(
            'end' => date("Y-m-d H:i:s", $end_ts),
        );

        $format = array(
            '%s',
        );

        $resize = $this->update_event($id, $update, $format);

        return $resize;
    }

    public function get_event_ajax()
    {
        $event = $this->get_event_by_id($_POST['event_id']);
        $event->shortcode = '[book_a_place_event id="' . $event->event_id . '"]';
        if ($event) {
            $error = false;
        } else {
            $error = true;
        }
        echo json_encode(array(
            'error' => $error,
            'event' => $event
        ));
        die();
    }

    public function edit_event_ajax()
    {
        $id = (int)$_POST['event_id'];

        $update = array(
            'name' => stripslashes($_POST['name']),
            'description' => stripslashes($_POST['description']),
            'url' => $_POST['url'],
            'hours' => $_POST['hours'],
            'scheme_id' => (int)$_POST['scheme_id'],
            'background_color' => $_POST['background_color'],
            'border_color' => $_POST['border_color'],
            'text_color' => $_POST['text_color'],
        );

        $format = array(
            '%s',
            '%s',
            '%s',
            '%f',
            '%s',
            '%s',
            '%s',
            '%s',
        );

        $edit = $this->update_event($id, $update, $format);

        if ($edit) {
            $error = false;
        } else {
            $error = true;
        }

        echo json_encode(array(
            'error' => $error,
            'event' => array(
                'name' => $_POST['name'],
                'background_color' => $_POST['background_color'],
                'border_color' => $_POST['border_color'],
                'text_color' => $_POST['text_color'],
            )
        ));
        die();
    }

    public function delete_event_ajax()
    {
        $id = (int)$_POST['event_id'];

        $delete = $this->delete_event($id);

        if ($delete) {
            $error = false;
        } else {
            $error = true;
        }

        echo json_encode(array('error' => $error));
        die();
    }

    public function book_a_place_event_shortcode($atts)
    {
        //extract(shortcode_atts(array('id' => 1), $atts));
        $id = ($atts['id']) ? $atts['id'] : 1;

        $event = $this->get_event_by_id($id);

        $this->is_event_booking_open($event);

        $scheme = $event->scheme_id;

        $scheme_details = $this->get_scheme_by_id($scheme);

        if (!$scheme_details) {
            $html = '<div id="book-a-place-scheme">';
            $html .= '<h2>There is no such scheme</h2>';
            $html .= '</div>';

            return $html;
        }

        $html = '<div id="book-a-place-scheme">';

        // feedback after offline booking
        if (isset($_GET['s_msg']) && !empty($_GET['s_msg'])) {
            $msg_id = (int) $_GET['s_msg'];
            $html .= '<div id="payment-success">' . $this->success_user_messages[$msg_id] . '</div>';
        }
        if (isset($_GET['e_msg']) && !empty($_GET['e_msg'])) {
            $msg_id = (int) $_GET['e_msg'];
            $html .= '<div id="payment-error">' . $this->error_user_messages[$msg_id] . '</div>';
        }

        $html .= '<h2>' . $event->name . '</h2>';

        $html .= '<p>' . __("Start", $this->plugin_slug) . ': ' . $event->start . '<br>' . __("End", $this->plugin_slug) . ': ' . $event->end . '</p>';

        $html .= '<p>' . $event->description . '</p>';

        $html .= '<p><a href="' . $event->url . '">' . $event->url . '</a></p>';

        $html .= '<p><strong>' . $scheme_details->name . '</strong></p>';

        $html .= '<p>' . $scheme_details->description . '</p>';

        if ($scheme_details->is_hidden) {
            $scheme_container_style = ' style="display: none;"';
            $scheme_show_text = __('Show Scheme', $this->plugin_slug);
            $html .= '<a id="scheme-container-visibility" data-visible="0" href="#">' . $scheme_show_text . '</a>';
        } else {
            $scheme_container_style = '';
        }

        $html .= '<div id="scheme-container"' . $scheme_container_style . '>';

        $html .= $this->display_scheme_front($scheme, $id);

        $html .= '</div>';

        $html .= '<div id="shopping-cart-container">';
        if ($this->event_booking_open) {
            $html .= $this->display_cart($scheme);
        }
        $html .= '</div>';

        $html .= '<div id="shopping-cart-controls-container">';
        if ($this->event_booking_open) {
            $html .= $this->display_cart_controls();
        }
        $html .= '</div>';

        $html .= '<div id="scheme-warning-message" title="' . __("Warning!", $this->plugin_slug) . '"></div>';

        $html .= '</div>';

        return $html;
    }

    private function is_event_booking_open($event)
    {
        if (is_array($event)) {
            $event = json_decode(json_encode($event), FALSE);
        }

        $ts_close_booking = strtotime($event->start) - $event->hours * 3600;
        $ts_now = time() - $event->timezone_offset * 3600;

        if ($ts_now < $ts_close_booking) {
            $this->event_booking_open = true;
            return true;
        } else {
            $this->event_booking_open = false;
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @since 0.3.0
     */
    public function delete_order($id)
    {
        global $wpdb;

        return $wpdb->delete($wpdb->bap_orders, array('order_id' => (int)$id), array('%d'));
    }

    /**
     * @param bool $get_url
     * @return string|void
     * @since 0.3.0
     */
    private function redirect_to_orders_list($get_url = false)
    {
        $redirect_to_page = explode('_page_', $this->orders_page_screen_hook_suffix);
        $url = admin_url('admin.php?page=' . $redirect_to_page[1]);
        if ($get_url) {
            return $url;
        } else {
            wp_redirect($url);
        }
        exit;
    }


    /**
     * @return mixed
     * @since 0.3.0
     */
    public function clear_orders()
    {
        global $wpdb;
        $delete = $wpdb->get_results("DELETE FROM $wpdb->bap_orders WHERE 1");
        return $delete;
    }

    /**
     * @return array
     * @since 0.3.0
     */
    private function get_loc_strings()
    {
        return array(
            'please_wait' => __('Please wait...', $this->plugin_slug),
            'checkout' => __('Checkout', $this->plugin_slug),
            'cancel' => __('Cancel', $this->plugin_slug),
            'ok' => __('Ok', $this->plugin_slug),
            'set_a_place' => __('Set a place', $this->plugin_slug),
            'change_place_status' => __('Change place status', $this->plugin_slug),
            'change_place_price' => __('Change place price', $this->plugin_slug),
            'update_a_place' => __('Update a place', $this->plugin_slug),
            'unset_this_place' => __('Are you sure you want to unset this place?', $this->plugin_slug),
            'delete_this_item' => __('Are you sure you want to delete this item?', $this->plugin_slug),
            'scheme_show_text' => __('Show Scheme', $this->plugin_slug),
            'scheme_hide_text' => __('Hide Scheme', $this->plugin_slug),
            'places_in_cart_cant_add' => __('Sorry, you can not add this place to your cart!', $this->plugin_slug),
            'places_in_cart_limit' => __('Sorry, you can not add more places to your cart!', $this->plugin_slug),
        );
    }

    /**
     * @return array
     * @since 0.3.0
     */
    public function get_currency_symbols()
    {
        return $this->currency_symbols;
    }

    /**
     * @param $fields
     * @return mixed
     * @since 0.3.2
     */
    public function search_orders($fields)
    {
        global $wpdb;

        $where = 'WHERE 1';
        $params = array();

        if ($fields && is_array($fields)) {
            foreach ($fields as $field => $value) {
                if ($field == 'email' && !empty($value)) {
                    $where .= " AND email = %s";
                    $params[] = $value;
                }
                if ($field == 'phone' && !empty($value)) {
                    $where .= " AND phone = %s";
                    $params[] = $value;
                }
                if ($field == 'code' && !empty($value)) {
                    $where .= " AND code = %s";
                    $params[] = $value;
                }
                if ($field == 'status_id' && $value != '') {
                    $where .= " AND status_id = %d";
                    $params[] = $value;
                }
                if ($field == 'price_from' && !empty($value)) {
                    $where .= " AND total_price >= %f";
                    $params[] = $value;
                }
                if ($field == 'price_to' && !empty($value)) {
                    $where .= " AND total_price <= %f";
                    $params[] = $value;
                }
                if ($field == 'date_from' && !empty($value)) {
                    $ts = strtotime($value);
                    if ($ts) {
                        $date = date('Y-m-d H:i:s', $ts);
                        $where .= " AND date >= %s";
                        $params[] = $date;
                    }
                }
                if ($field == 'date_to' && !empty($value)) {
                    $ts = strtotime($value);
                    if ($ts) {
                        $date = date('Y-m-d H:i:s', $ts);
                        $where .= " AND date <= %s";
                        $params[] = $date;
                    }
                }
                if ($field == 'first_name' && !empty($value)) {
                    $where .= " AND first_name LIKE %s";
                    $params[] = $value . '%';
                }
                if ($field == 'last_name' && !empty($value)) {
                    $where .= " AND last_name LIKE %s";
                    $params[] = $value . '%';
                }

            }
        }

        $sql = $wpdb->prepare("SELECT *
        FROM $wpdb->bap_orders
        {$where}", $params);

        $orders = $wpdb->get_results($sql);

        return $orders;
    }

    /*
     * Generates csv file with order details
     */
    public function orders_to_csv($orders)
    {
        ob_clean();

        $csvout = 'N,First Name,Last Name,Email,Phone,Notes,Date,Code,Places,Price,Status,Admin Notes,Event Name' . "\n";

        foreach ($orders as $key => $order) {

            $places_array = unserialize($order->places);
            $places = '';
            if (is_array($places_array)) {
                $place_key = 0;
                foreach ($places_array as $place) {
                    $places .= ($place_key + 1) . '. ';
                    $places .= $place['place_name'];
                    $places .= ' - ' . number_format($place['place_price'], 2, '.', ' ') . '. ';

                    $place_key++;
                }

            }

            $csvout .= ($key + 1) . ',' . $order->first_name . ',' . $order->last_name . ',' . $order->email . ',' . $order->phone . ',' . $order->notes . ',' . $order->date . ',' . $order->code . ',' . trim($places) . ',' . number_format($order->total_price, 2, '.', ' ') . ',' . $this->order_statuses[$order->status_id] . ',' . $order->admin_notes . ',' . $order->event_name . "\n";

        }

        $filename = "Orders_" . date("d-m-Y_H-i", time());

        header("Content-type: application/vnd.ms-excel");

        header("Content-disposition: csv" . date("Y-m-d") . ".csv");

        header("Content-disposition: filename=" . $filename . ".csv");

        print $csvout;

        exit;
    }

    private function set_texts()
    {
        $this->place_statuses_labels = array(
            '1' => __('Available', $this->plugin_slug),
            '2' => __('Booked', $this->plugin_slug),
            '3' => __('In cart', $this->plugin_slug),
            '4' => __('In other\'s cart', $this->plugin_slug),
            '5' => __('Unavailable', $this->plugin_slug),
        );

        $this->order_statuses = array(
            0 => __('Not defined', $this->plugin_slug),
            1 => __('Set', $this->plugin_slug),
            2 => __('Paid', $this->plugin_slug),
            3 => __('Cancelled', $this->plugin_slug),
        );

        $this->success_user_messages = array(
            1 => __('Places has been successfully booked.', $this->plugin_slug),
        );

        $this->error_user_messages = array(
            1 => __('Error. Places has not been booked.', $this->plugin_slug),
        );

    }

    private function filter_places_by_scheme_id($places_in_carts, $scheme_id)
    {
        if (!empty($places_in_carts) && is_array($places_in_carts)) {
            $places = array();
            foreach ($places_in_carts as $place) {
                if ($place['place_scheme_id'] == $scheme_id) {
                    $places[] = $place;
                }
            }

            return $places;
        }


        return $places_in_carts;
    }

    private function get_places_ids_in_cart_by_scheme_id($scheme_id)
    {
        $places_in_cart = $this->get_places_in_cart($scheme_id);
        $places_ids = array();
        if (is_array($places_in_cart)) {
            foreach ($places_in_cart as $place) {
                $places_ids[] = $place['place_id'];
            }

            return $places_ids;
        }

        return false;
    }


    /**
     * Prepare proper page slug.
     *
     * @since     0.5.7
     *
     * @return    string $page_slug    Return proper page slug.
     */
    private function get_proper_page_slug()
    {
        $screen = get_current_screen();
        $position = strripos($screen->id, $this->plugin_slug);
        $page_slug = substr($screen->id, $position);
        return $page_slug;
    }


    /**
     * @param array $params

     * @since 0.6.1

     * @return string|void
     */
    private function create_url($params = array())
    {
        $url = admin_url('admin.php');

        if (empty($params)) {
            return $url;
        }

        $params_str = array();
        foreach ($params as $key => $param) {
            $params_str[] = $key . '=' . $param;
        }
        $params_str = implode('&', $params_str);

        $url .= '?' . $params_str;

        return $url;

    }
}