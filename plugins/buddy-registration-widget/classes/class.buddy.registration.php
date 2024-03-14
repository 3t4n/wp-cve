<?php
require('class.buddy.profile.php');

//Main Plugin Class

class Buddy_Registration extends WP_Widget
{

    /**
     * Instance of the class
     * @var type 
     */
    static $instance;

    /**
     * Constructor of the class
     */
    function __construct()
    {

        self::$instance = $this;
        parent::__construct(false, $name = __('BuddyPress Registration Form', 'wp_widget_plugin'));

        add_action('bp_core_screen_signup', array(&$this, 'buddyRedirectSignup'));
        add_action('bp_init', array(&$this, 'buddySignupErrors'));
        add_shortcode('buddyRegisterFormCode', array(&$this, 'shortCodeRegistrationForm'));
        add_action("wp_enqueue_scripts", array(&$this, "addCustomBuddyScripts"));

        // register widget
        add_action('widgets_init', function() {
            register_widget("Buddy_Registration");
        });

        register_activation_hook('buddyregistration/classes/class.buddy.registration.php', array(
            &$this,
            'hookActivate'
        ));

        register_deactivation_hook('buddyregistration/classes/class.buddy.registration.php', array(
            &$this,
            'hookDeactivate'
        ));
    }

    /**
     * Function to Add Settings Menu in backend from where user would modify settings and options
     * Author : Yogesh Pawar
     * Date : 5th Feb 2019
     */
    function customBPMenu()
    {

        add_submenu_page('options-general.php', 'BuddyPress Registration Options', 'BuddyPress Registration Options', "manage_options", 'buddy-register-widget-options', array($this, 'loadBuddyCustomOptions'));
    }

    /**
     * Plugin Activation Hook
     * @return type
     * Author: Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function hookActivate()
    {

        if (!current_user_can('activate_plugins')) {
            return;
        }

        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("activate-plugin_{$plugin}");
    }

    /**
     * Plugin De-activation Hook
     * @return type
     * Author: Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function hookDeactivate()
    {

        if (!current_user_can('activate_plugins')) {
            return;
        }

        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");
    }

    /**
     * Function to add Registration widget in backend
     * @param type $args
     * @param type $instance
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function widget($args, $instance)
    {
        if (!is_user_logged_in()) {

            if (!empty($args)) {
                extract($args);
            }

            echo '<div class="widget-text wp_widget_plugin_box">';
            if (esc_attr(get_option('buddy_custom_widget_template')) == "yes") {
                if (file_exists(plugin_dir_path(__DIR__) . '/templates/custom/form-template.php')) {
                    require plugin_dir_path(__DIR__) . '/templates/custom/form-template.php';
                } else {
                    die("<br /><h3>Cannot locate template.</h3>");
                }
            } else {
                require_once(BUDDY_FILE_DIRECTORY . "/templates/form-template.php");
            }

            echo '</div>';
        }
    }

    /**
     * Function to render shortcode registration form
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function shortCodeRegistrationForm()
    {
        if (esc_attr(get_option('buddy_custom_shortcode_template')) == "yes") {
            if (file_exists(plugin_dir_path(__DIR__) . '/templates/custom/shortcode-form-template.php')) {
                load_template(BUDDY_FILE_DIRECTORY . "/templates/custom/shortcode-form-template.php");
            } else {
                die("<br /><h3>Cannot locate template.</h3>");
            }
        } else {
            load_template(BUDDY_FILE_DIRECTORY . "/templates/shortcode-form-template.php");
        }
    }

    /**
     * If the signup form is being processed, Redirect to the page where the signup form is
     * @return type
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function buddyRedirectSignup()
    {

        if ('POST' !== strtoupper($_SERVER['REQUEST_METHOD'])) {
            return;
        }

        $bp = buddypress();

        //only if bp signup object is set
        if (!empty($bp->signup)) {
            //save the signup object and submitted post data
            $_SESSION['buddydev_signup'] = $bp->signup;
            $_SESSION['buddydev_signup_fields'] = $_POST;
        }

        bp_core_redirect(wp_get_referer());
    }

    /**
     * Function to address signup errors
     * @return type
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function buddySignupErrors()
    {

        //we don't need to process if the user is logged in
        if (is_user_logged_in())
            return;

        //if session was not started by another code, let us begin the session
        if (!session_id())
            session_start();

        //check if the current request
        if (!empty($_SESSION['buddydev_signup'])) {

            $bp = buddypress();
            //restore the old signup object
            $bp->signup = $_SESSION['buddydev_signup'];

            //we are sure that it is our redirect from the buddydev_redirect_on_signup function, so we can safely replace the $_POST array
            if (isset($bp->signup->errors) && !empty($bp->signup->errors))
                $_POST = $_SESSION['buddydev_signup_fields']; //we need to restore so that the signup form can show the old data

            $errors = array();

            if (isset($bp->signup->errors))
                $errors = $bp->signup->errors;

            foreach ((array) $errors as $fieldname => $error_message) {

                add_action('bp_' . $fieldname . '_errors', function() {
                    echo apply_filters('bp_members_signup_error_message', "<div class=error>" . $error_message . "</div>");
                });
            }
            //remove from session
            $_SESSION['buddydev_signup'] = null;
            $_SESSION['buddydev_signup_fields'] = null;
        }
    }

    /**
     * Function to load custom buddypress css and js
     * Author : Yogesh Pawar
     * Date: 5th Sept 2019
     */
    function addCustomBuddyScripts()
    {
        if (!is_user_logged_in()) {

            wp_enqueue_script('jquery');
            wp_register_style("buddy-custom-style", plugins_url('/assets/css/buddypress-override.css', __DIR__));
            wp_register_script('buddy-custom-script', plugins_url('/assets/js/buddypress-override.js', __DIR__));

            wp_enqueue_style('buddy-custom-style');
            wp_enqueue_script('buddy-custom-script');
            wp_enqueue_script('password-strength-meter');
        }
    }

    /**
     * Function to load buddypress custom options
     * Author : Yogesh Pawar
     * Date : 5th Sept 2019
     */
    function loadBuddyCustomOptions()
    {
        if (current_user_can('manage_options')) {
            if (file_exists(plugin_dir_path(__DIR__) . '/templates/settings/buddy-setting-options.php')) {
                require plugin_dir_path(__DIR__) . '/templates/settings/buddy-setting-options.php';
            } else {
                die('<br /><h3>Plugin Installation is Incomplete. Please install the plugin again or make sure you have copied all the plugin files.</h3>');
            }
        } else {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
    }
}

new Buddy_Registration();
Buddy_Profile::get_instance();

?>