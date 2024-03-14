<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCalendarAdmin_Class
 * @package   VRCalendarAdmin_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCalendarAdmin Class Doc Comment
  * 
  * VRCalendarAdmin Class
  * 
  * @category  VRCalendarAdmin_Class
  * @package   VRCalendarAdmin_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
class VRCalendarAdmin extends VRCSingleton
{

    /**
     * Define template file
     **/
    protected function __construct()
    {

        add_action('init', array($this, 'handleCommands'));
        add_action('admin_notices', array( $this,'adminNotice'));

        add_action('admin_menu', array($this,'registerAdminPages'));


        add_action('admin_enqueue_scripts', array( $this, 'enqueueStyles' ));
        add_action('admin_enqueue_scripts', array( $this, 'enqueueScripts' ));
    }

    /**
     * Admin notice based on web instance
     * 
     * @return String
     */
    function adminNotice()
    {
        $type = 'updated';

        if ( isset($_GET['vrc_msg']) ) {
            $msg = sanitize_text_field($_GET['vrc_msg']);
            if ( !empty($msg) ) {
                if ( isset($_GET['vrc_msg_type'] ) ) {
                    $type = sanitize_text_field($_GET['vrc_msg_type']);
                }
                ?>
                <div class="<?php echo esc_html($type); ?>">
                    <p><?php echo esc_html($msg); ?></p>
                </div>
                <?php
            }
        }
    }

    /**
     * Get handle commands based on web instance
     * 
     * @return String
     */
    function handleCommands()
    {
        ob_start();
        
        if (isset($_REQUEST['vrc_cmd'])) {
            $cmd = sanitize_text_field($_REQUEST['vrc_cmd']);
            if ($cmd == "calendarRemove") {
                $this->deleteCalendar();
            } elseif ($cmd == "calendarSync") {
                $this->syncCalendar();
            } elseif ($cmd == "updateSettings") {
                $this->saveSettings();
            } elseif ($cmd == "saveCalendar") {
                $this->saveCalendar();
            }
        }
    }

    /**
     * Sync calendar based on web instance
     * 
     * @return String
     */
    function syncCalendar()
    {
        $msg = 'Something went wrong!';
        $type = 'error';
        if (isset($_GET['cal_id'])) {
            $calid = sanitize_text_field($_GET['cal_id']);
            $VRCalendarEntity = VRCalendarEntity::getInstance();
            $VRCalendarEntity->synchronizeCalendar($calid);
            $msg = __('Calendar Synchronized successfully', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            $type = 'updated';
        }
        $msg = rawurlencode($msg);
        $redirect_url = sanitize_url( admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-dashboard&vrc_msg={$msg}&vrc_msg_type={$type}") );
        wp_redirect($redirect_url);
    }

    /**
     * Get sync all calendars based on web instance
     * 
     * @return String
     */
    function syncAllCalendars()
    {
        $VRCalendarEntity = VRCalendarEntity::getInstance();

        /* Fetch all calendars */
        $cals = $VRCalendarEntity->getAllCalendar();
        foreach ($cals as $cal) {
            $VRCalendarEntity->synchronizeCalendar($cal->calendar_id);
        }
    }

    /**
     * Remove calendar based on web instance
     * 
     * @return String
     */
    function deleteCalendar()
    {
        $msg = __('Deleting calendar error occured.', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        if(isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'vr-calendar-sync-nonce' )){
            $VRCalendarEntity = VRCalendarEntity::getInstance();
            $calid = sanitize_text_field($_GET['cal_id']);
            $VRCalendarEntity->deleteCalendar($calid);
            $msg = __('Calendar deleted successfully', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            $msg = rawurlencode($msg);
        }
        $redirect_url = admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-dashboard&vrc_msg={$msg}");
        wp_redirect($redirect_url);
    }

    /**
     * Save calendar based on web instance
     * 
     * @return String
     */
    function saveCalendar()
    {
        $data = $_POST;
        $msg = __('Calendar saving error occured.', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

        if(isset( $_POST['vr-calendar-sync-nonce'] ) && wp_verify_nonce( $_POST['vr-calendar-sync-nonce'], 'vr-calendar-sync-nonce' )){
            $msg = __('Calendar updated successfully', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            if ($data['calendar_id']<=0) {
                $data['calendar_created_on'] = date('Y-m-d H:i:s');
                $data['calendar_author_id'] = get_current_user_id();
                $msg = 'Calendar created successfully';
            }
            $data['calendar_modified_on'] = date('Y-m-d H:i:s');
            //$data['calendar_links'] = array_filter( $data['calendar_links'] );
            /* remove last element from link entries */
            array_pop($data['calendar_links']['name']);
            array_pop($data['calendar_links']['url']);
            $calendar_links= array();
            /* convert this to required format */
            foreach ($data['calendar_links']['name'] as $k=>$v) {
                $tmp = array();
                $tmp['name'] = $data['calendar_links']['name'][$k];
                $tmp['url'] = $data['calendar_links']['url'][$k];
                $calendar_links[] = $tmp;
            }
            $data['calendar_links'] = $calendar_links;

            $VRCalendarEntity = VRCalendarEntity::getInstance();
            $VRCalendarEntity->saveCalendar($data);
        }
        
        $msg = rawurlencode($msg);
        $redirect_url = admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-dashboard&vrc_msg={$msg}");

        wp_redirect($redirect_url);
        exit();
    }

    /**
     * Save settings based on web instance
     * 
     * @return String
     */
    function saveSettings()
    {
        
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $msg = __('Settings error occured', VRCALENDAR_PLUGIN_TEXT_DOMAIN);;

        if(isset( $_POST['vr-calendar-sync-nonce'] ) && wp_verify_nonce( $_POST['vr-calendar-sync-nonce'], 'vr-calendar-sync-nonce' )){
            $VRCalendarSettings->setSettings('auto_sync', sanitize_text_field($_POST['auto_sync']));
            $VRCalendarSettings->setSettings('attribution', sanitize_text_field($_POST['attribution']));
            $VRCalendarSettings->setSettings('load_jquery_ui_css', sanitize_text_field($_POST['load_jquery_ui_css']));

            /* Updated sync hook */
            wp_clear_scheduled_hook('vrc_cal_sync_hook');
            wp_schedule_event(time(), $VRCalendarSettings->getSettings('auto_sync', 'daily'), 'vrc_cal_sync_hook');

            $msg = __('Settings saved successfully', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

            $msg = rawurlencode($msg);
        }

        $redirect_url = admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-settings&vrc_msg={$msg}");
        wp_redirect($redirect_url);

        exit();
    }

    /**
     * Register pages based on web instance
     * 
     * @return String
     */
    function registerAdminPages()
    {
        add_menu_page(VRCALENDAR_PLUGIN_NAME, VRCALENDAR_PLUGIN_NAME, 'manage_options', VRCALENDAR_PLUGIN_SLUG.'-dashboard', array($this,'dashboard'));
        add_submenu_page(VRCALENDAR_PLUGIN_SLUG.'-dashboard', 'Dashboard', 'Dashboard', 'manage_options', VRCALENDAR_PLUGIN_SLUG.'-dashboard', array($this,'dashboard'));
        add_submenu_page(VRCALENDAR_PLUGIN_SLUG.'-dashboard', 'Add Calendar', 'Add Calendar', 'manage_options', VRCALENDAR_PLUGIN_SLUG.'-add-calendar', array($this,'addCalendar'));
        //add_submenu_page( VRCALENDAR_PLUGIN_SLUG.'-dashboard', 'Bookings', 'Bookings', 'manage_options', VRCALENDAR_PLUGIN_SLUG.'-calendar-bookings', array($this,'calendarBookings') );
        add_submenu_page(VRCALENDAR_PLUGIN_SLUG.'-dashboard', 'Settings', 'Settings', 'manage_options', VRCALENDAR_PLUGIN_SLUG.'-settings', array($this,'settings'));
        add_submenu_page(VRCALENDAR_PLUGIN_SLUG.'-dashboard', 'Information', 'Information', 'manage_options', VRCALENDAR_PLUGIN_SLUG.'-information', array($this,'information'));
    }

    /**
     * Info Template
     * 
     * @return String
     */
    function information()
    {
        include VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Information.php';
    }

    /**
     * Settings Template
     * 
     * @return String
     */
    function settings()
    {
        include VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Settings.php';
    }

    /**
     * Add calendar Template
     * 
     * @return String
     */
    function addCalendar()
    {
        /* check if we have more then one calender in system */
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        if (isset($_GET['cal_id'])) {
            $calid = sanitize_text_field($_GET['cal_id']);
            $cal = $VRCalendarEntity->getCalendar($calid);
            if (!isset($cal->calendar_id)) {
                $msg = __('Invalid calendar!', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $msg = rawurlencode($msg);
                $redirect_url = admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-dashboard&vrc_msg={$msg}");

                wp_safe_redirect($redirect_url);
                exit;
            }
        } else {
            $cals = $VRCalendarEntity->getAllCalendar();
            if (count($cals) >= 1 ) {
                $msg = __('Only one calendar is allowed in free version<br/>Upgrade to the <strong>PRO</strong> or <strong>ENTERPRISE</strong> version to add more calendars', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $msg = rawurlencode($msg);
                $redirect_url = admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-dashboard&vrc_msg={$msg}");

                wp_safe_redirect($redirect_url);
                exit;
            }
        }

        include VRCALENDAR_PLUGIN_DIR.'/Admin/Views/AddCalendar.php';
    }

    /**
     * Dashboard Template
     * 
     * @return String
     */
    function dashboard()
    {
        $view = 'Dashboard';
        if (isset($_GET['view'])) {
            $view = sanitize_text_field(ucfirst($_GET['view']));
        }

        include VRCALENDAR_PLUGIN_DIR.'/Admin/Views/'.$view.'.php';
    }

    /**
     * Register and enqueue admin-facing style sheet.
     *
     * @since 1.0.0
     * 
     * @return String
     */
    public function enqueueStyles()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('jquery-style', VRCALENDAR_PLUGIN_URL.'/assets/css/jquery-ui.css');
        wp_enqueue_style(VRCALENDAR_PLUGIN_SLUG . '-plugin-styles', VRCALENDAR_PLUGIN_URL.'/assets/css/admin.css', array(), VRCalendar::VERSION);
    }

    /**
     * Register and enqueues admin-facing JavaScript files.
     *
     * @since 1.0.0
     * 
     * @return String
     */
    public function enqueueScripts()
    {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script(VRCALENDAR_PLUGIN_SLUG . '-plugin-script', VRCALENDAR_PLUGIN_URL.'/assets/js/admin.js', array( 'jquery' ), VRCalendar::VERSION);
    }

}
