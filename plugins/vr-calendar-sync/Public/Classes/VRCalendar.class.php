<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCalendar_Class
 * @package   VRCalendar_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCalendar Class Doc Comment
  * 
  * VRCalendar Class
  * 
  * @category  VRCalendar_Class
  * @package   VRCalendar_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */

class VRCalendar extends VRCSingleton
{

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since 1.0.0
     *
     * @var string
     */

    const VERSION = '1.0.0';
    /**
     * Define template file
     **/
    protected function __construct()
    {
        /**
         * Short description: Booking calendar created by Innate Images, LLC
         * PHP Version 8.0
         
        * @category  Booking,ical,ics
        * @package   VR_Calendar
        * @author    Innate Images, LLC <info@innateimagesllc.com>
        * @copyright 2015 Innate Images, LLC
        * @license   GPL-2.0+ http://www.vrcalendarsync.com
        * @link      http://www.vrcalendarsync.com
        */

        // Load plugin text domain
        add_action('init', array( $this, 'loadPluginTextdomain' ));
        add_action('init', array( $this, 'initShortcodes' ));
        add_action('init', array($this, 'handleCommands'));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array( $this, 'enqueueStyles' ));
        add_action('wp_enqueue_scripts', array( $this, 'enqueueScripts' ));

        add_action('vrc_cal_sync_hook', array($this, 'syncAllCalendars'));

        add_action('wp_ajax_getSingleCalendarCustome', array($this, 'getSingleCalendarCustome'));
        add_action('wp_ajax_nopriv_getSingleCalendarCustome', array($this, 'getSingleCalendarCustome'));

    }

    /**
     * Sync all calendars based on web instance
     * 
     * @return String
     */
    function syncAllCalendars()
    {
        $VRCalendarAdmin = VRCalendarAdmin::getInstance();
        $VRCalendarAdmin->syncAllCalendars();
    }

    /**
     * Handle commands
     * 
     * @return String
     */
    function handleCommands()
    {
        if (isset($_POST['vrc_pcmd'])) {
            $post = sanitize_text_field($_POST["vrc_pcmd"]);
            switch($post) {
            default:
                break;
            }
        }
    }

    /**
     * Initialized based on web instance
     * 
     * @return String
     */
    function initShortcodes()
    {
        VRCalendarShortcode::getInstance();
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @return String
     */
    function loadPluginTextdomain()
    {
        $domain = VRCALENDAR_PLUGIN_TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(plugin_dir_path(dirname(dirname(__FILE__)))) . '/Languages/');
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @return String
     */
    public function enqueueStyles()
    {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        wp_enqueue_style(VRCALENDAR_PLUGIN_SLUG . '-bootstrap-styles', VRCALENDAR_PLUGIN_URL.'assets/css/bootstrap.css', array(), self::VERSION);
        wp_enqueue_style(VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-main', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.carousel.css', array(), self::VERSION);
        wp_enqueue_style(VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-theme', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.theme.css', array(), self::VERSION);
        wp_enqueue_style(VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-transitions', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.transitions.css', array(), self::VERSION);
        wp_enqueue_style(VRCALENDAR_PLUGIN_SLUG . '-calendar-styles', VRCALENDAR_PLUGIN_URL.'assets/css/calendar.css', array(), self::VERSION);
        if ($VRCalendarSettings->getSettings('load_jquery_ui_css', 'yes') == 'yes') {
            wp_enqueue_style('jquery-style', VRCALENDAR_PLUGIN_URL.'assets/css/jquery-ui.css');
        }
        wp_enqueue_style(VRCALENDAR_PLUGIN_SLUG . '-plugin-styles', VRCALENDAR_PLUGIN_URL.'assets/css/public.css', array(), self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @return String
     */
    public function enqueueScripts()
    {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        wp_enqueue_script(VRCALENDAR_PLUGIN_SLUG . '-bootstrap-script', VRCALENDAR_PLUGIN_URL.'assets/js/bootstrap.js', array( 'jquery' ), self::VERSION);
        wp_enqueue_script(VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-script', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.carousel.js', array( 'jquery' ), self::VERSION);
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script(VRCALENDAR_PLUGIN_SLUG . '-plugin-script', VRCALENDAR_PLUGIN_URL.'assets/js/public.js', array( 'jquery', 'jquery-ui-datepicker' ), self::VERSION);

    }

    /**
     * Activate plugin instance.
     *
     * @return String
     */
    static function activate()
    {
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $VRCalendarSettings = VRCalendarSettings::getInstance();

        $VRCalendarEntity->createTable();
        $VRCalendarBooking->createTable();

        $VRCalendarSettings = VRCalendarSettings::getInstance();
        /* Setup Cal Sync Task */
        wp_schedule_event(time(), $VRCalendarSettings->getSettings('auto_sync', 'daily'), 'vrc_cal_sync_hook');
    }

    /**
     * De-activate plugin instance.
     *
     * @return String
     */
    static function deactivate()
    {
        wp_clear_scheduled_hook('vrc_cal_sync_hook');
    }

    /**
     * Get custom single calendar.
     *
     * @return String
     */
    function getSingleCalendarCustome()
    { 

        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $postid = sanitize_text_field($_POST['id']);
        $cal_data = $VRCalendarEntity->getCalendar($postid);
        $VRCalendarShortcode = VRCalendarShortcode::getInstance();
        $calendar_display_num_months = '';
        if ($cal_data->calendar_display_num_months != '') {
            $calendar_display_num_months = $cal_data->calendar_display_num_months;
            $navigation_block = '';
            if ($cal_data->calendar_display_num_months <= 12) {
                $navigation_block = 'display:none;';
            } else {
                $navigation_block = 'display:block;';
            }
        } else {
            $calendar_display_num_months = 36 ;
        }
        $next_page = sanitize_text_field($_POST['next_page']);
        if ($next_page < 1) {
            $next_page = 1;
        } else {
            $next_page = $next_page;
        }
        $calendar_display_num_months;
        $calendar_html = $VRCalendarShortcode->getCalendar($cal_data, $calendar_display_num_months, $next_page);

        echo wp_kses_post($calendar_html);
        exit;
    }

}
