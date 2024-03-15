<?php
/**
 * Plugin Name:       Our Team Members
 * Plugin URI:        https://wpbean.com/product/our-team-members-pro/
 * Description:       Highly customizable team members showcase plugin. 
 * Version:           2.1
 * Author:            wpbean
 * Author URI:        https://wpbean.com
 * Text Domain:       our-team-members
 * Domain Path:       /languages
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Our Team Members
 */


// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;


/* Set constant path to the plugin directory. */
define( 'WPB_OTM_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Our Team Members Class
 */

class WPB_Our_Team_Members {

    /**
     * The plugin path
     *
     * @var string
     */
    public $plugin_path;


    /**
     * The theme directory path
     *
     * @var string
     */
    public $theme_dir_path;


    /**
     * Initializes the WPB_Our_Team_Members() class
     *
     * Checks for an existing WPB_Our_Team_Members() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPB_Our_Team_Members();

            $instance->plugin_init();
        }

        return $instance;
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    function plugin_init() {
    	$this->theme_dir_path = apply_filters( 'wpb_our_team_members_theme_dir_path', 'our-team-members/' );

    	$this->file_includes();

        add_action( 'init', array( $this, 'localization_setup' ) );

        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

        add_action('wpsf_framework_loaded', array( $this, 'wpsf_framework_config' ));

        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_actions_links' ));
    }


    /**
     * Load the required files
     *
     * @return void
     */
    function file_includes() {
        require_once dirname( __FILE__ ) . '/inc/wpb_functions.php';
        require_once dirname( __FILE__ ) . '/inc/wpb_shortcode.php';
        require_once dirname( __FILE__ ) . '/inc/wpb_scripts.php';
        require_once dirname( __FILE__ ) . '/inc/wpb_cpt.php';
        require_once dirname( __FILE__ ) . '/inc/map.php';
    }


    /**
     * Plugin loaded
     */
    
    function plugins_loaded() {
        if (! function_exists ( 'wpsf_framework_init' ) && ! class_exists ( 'WPSFramework' )) {
            require_once dirname( __FILE__ ) . '/admin/framework/wpsf-framework.php';
        }
    }


    /**
     * Plugin action links
     */
    
    function plugin_actions_links( $links ) {
        if( is_admin() ){
            $links[] = '<a href="http://wpbean.com/support/" target="_blank">'. esc_html__( 'Support', 'our-team-members' ) .'</a>';
            $links[] = '<a href="http://docs.wpbean.com/docs/wpb-our-team-member-free-version/installing/" target="_blank">'. esc_html__( 'Documentation', 'our-team-members' ) .'</a>';
        }
        return $links;
    }


    /**
     * Framework Config
     */

    function wpsf_framework_config(){
    
        global $wpb_otm_metabox;
        
        wpsf_locate_template ( 'config/framework.config.php' );
        wpsf_locate_template ( 'config/metabox.config.php' );

        $framework_options = array(
            'metabox' => $wpb_otm_metabox,
        );
        
        new WPSFramework($framework_options);
    }
    


    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'our-team-members', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }


    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return $this->plugin_path() . '/templates/';
    }

}

/**
 * Initialize the plugin
 */
function wpb_our_team_members() {
    return WPB_Our_Team_Members::init();
}

// kick it off
wpb_our_team_members();