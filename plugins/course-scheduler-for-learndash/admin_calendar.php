<?php
/**
 * Plugin Name: Course Scheduler for LearnDash
 * Plugin URI: http://wooninjas.com/
 * Description: Enables scheduling of LearnDash Courses on Calendar
 * Version: 1.5.1
 * Requires at least: 5.1
 * Requires PHP: 7.2
 * Author: WooNinjas
 * Author URI: http://wooninjas.com/
 * Text Domain: cs_ld_addon
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Abort if this file is accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if LD is enabled
 */
function CS_LD_require_dependency( ) {
    if ( !class_exists( 'SFWD_LMS' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
	    unset($_GET['activate']);
	    $class = 'notice notice-error is-dismissible';
	    $message = __( 'Course Scheduler for LearnDash requires <a href="https://www.learndash.com" target="_blank">LearnDash</a> plugin to be activated.', 'cs_ld_addon' );
        printf( '<div class="%s"> <p>%s</p></div>', $class, $message );
    }
}

/**
 * Check if LD free is disabled
 */
function cS_LD_free_require_dependency( ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
	    unset($_GET['activate']);
	    $class = 'notice notice-error is-dismissible';
	    $message = __( 'Learndash Course Planner pro needs to be deactivated to enable course scheduler for learndash.', 'cs_ld_addon' );
        printf( '<div class="%s"> <p>%s</p></div>', $class, $message );
}



// add_action( 'admin_notices','CS_LD_require_dependency' );


/**
 * LearnDash Version Constant
 */
define( 'CS_LD_VERSION', '1.5.1' );
define( 'CS_LD_TEXT_DOMAIN', 'cs_ld_addon' );
define( 'CS_LD_PLUGIN_NAME', plugin_basename( __FILE__ ) );

/**
 * Learndash Directory Constants
 */
define( 'LD_CS_DIR', plugin_dir_path(__FILE__));
define( 'LD_CS_DIR_FILE', LD_CS_DIR . basename(__FILE__));
define( 'CS_LD_INCLUDES_DIR', trailingslashit(LD_CS_DIR . 'includes' ));

/**
 * Learndash URL Contants
 */
define( 'CS_LD_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'CS_LD_ASSETS_URL', trailingslashit( CS_LD_PLUGIN_URL.DIRECTORY_SEPARATOR.'assets' ) );


/**
 * Class CS_LD_Main for plugin initiation
 *
 * @since 1.0
 */
final class CS_LD_Main {
    public static $version = CS_LD_VERSION;

    // CS_LD_Main instance
    protected static $_instance = null;

    protected function __construct(  ) {
        register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
        register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );

        // Upgrade
        add_action( 'plugins_loaded', array( $this, 'upgrade' ) );

        // Adding settings tab
        add_filter( 'plugin_action_links_'.plugin_basename( LD_CS_DIR_FILE ), function( $links ) {
            return array_merge( $links, array(
                sprintf(
                    '<a href="%s">Settings</a>',
                    admin_url( 'admin.php?page=wooninjas-dashboard-setting' )
                ),
            ) );
        } );
        $this->includes();
    }

    public static function includes() {

        if( ! class_exists( 'Wn_Plugin_Settings_API' ) ){
            require_once CS_LD_INCLUDES_DIR . 'settings/class.settings-api.php';
        }

        if( ! class_exists( 'WN_DASHBOARD_SETTINGS' ) ){
            require_once CS_LD_INCLUDES_DIR . 'settings/settings.php';
        }

        if( ! class_exists( 'WN_DASHBOARD_Page' ) )
            require_once CS_LD_INCLUDES_DIR . 'wn-dashboard.php';
            
        if ( file_exists( CS_LD_INCLUDES_DIR . 'CS_LD_HELPER.php' ) ) {
			require_once( CS_LD_INCLUDES_DIR . 'CS_LD_HELPER.php' );
        }

        if( file_exists( CS_LD_INCLUDES_DIR . 'CS_LD_Widget.php' ) ) {
            require_once ( CS_LD_INCLUDES_DIR . 'CS_LD_Widget.php' );
        }
    }

    /**
     * @return $this
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Activation function hook
     *
     * @return void
     */
    public static function activation() {
        if (!current_user_can('activate_plugins'))
            return;

        update_option( 'csld_version', self::$version );
        update_option( 'wn_course_schedular_general_settings', [ 'show_courses' => 1 ] );
        update_option( 'wn_course_schedular_course_settings', [ 
                        'show_message' => __("This " . LearnDash_Custom_Label::get_label( 'course' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                        'hide_message' => __("This " . LearnDash_Custom_Label::get_label( 'course' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                    ] );

        update_option( 'wn_course_schedular_lesson_settings', [ 
                        'show_message' => __("This " . LearnDash_Custom_Label::get_label( 'lesson' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                        'hide_message' => __("This " . LearnDash_Custom_Label::get_label( 'lesson' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                    ] );          
        update_option( 'wn_course_schedular_quiz_settings', [ 
                        'show_message' => __("This " . LearnDash_Custom_Label::get_label( 'quiz' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                        'hide_message' => __("This " . LearnDash_Custom_Label::get_label( 'quiz' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                    ] ); 
        update_option( 'wn_course_schedular_topic_settings', [ 
                        'show_message' => __("This " . LearnDash_Custom_Label::get_label( 'topic' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                        'hide_message' => __("This " . LearnDash_Custom_Label::get_label( 'topic' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon"),
                    ] );            
    }

    /**
     * Deactivation function hook
     *
     * @return void
     */
    public static function uninstall() {
        delete_option( 'csld_version' );
        delete_option( 'wn_course_schedular_general_settings' );
        delete_option( 'wn_course_schedular_course_settings' );
        delete_option( 'wn_course_schedular_lesson_settings' );
        delete_option( 'wn_course_schedular_quiz_settings' );
        delete_option( 'wn_course_schedular_topic_settings' );
    }

    /**
     * Upgrade function hook
     */
    public static function upgrade() {
        if ( get_option( 'csld_version' ) != self::$version ) {
            
        }
    }
}

/**
 * CS_LD_Main instance
 *
 * @return CS_LD_Main
 */
function CourseSchLD() {
    return CS_LD_Main::instance();
}



add_action('plugins_loaded', function() {

    if ( current_user_can( 'activate_plugins' ) && !class_exists( 'SFWD_LMS' ) ) {

        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        add_action( 'admin_notices', __NAMESPACE__ . '\\CS_LD_require_dependency' );
    } else  if ( current_user_can( 'activate_plugins' ) && class_exists( 'LCMS_Main' ) ) {

        
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		deactivate_plugins ( plugin_basename ( __FILE__ ), true );

		add_action( "admin_notices", 'cS_LD_free_require_dependency' );
    } else {
        CourseSchLD();
    }

});
