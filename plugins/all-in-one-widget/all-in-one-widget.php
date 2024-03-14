<?php
/*
    Plugin Name: All-in-one Widget
    Plugin URI: http://themeidol.com/product/all-in-one-widget/
    Description: Various fundamental and useful widgets that add some essential functionality to your WordPress site. There widgets are intended to let you add more engaging content in your sidebars, such as a Adv Section, Facebook like box, Instagram , Stylist Popular post,Twitter timeline, recent posts,WP Tabs,Flickr,Ajax Search, Site2Quotes,Rss Feed or social media links.
    Author: themeidol,rakeshshrestha
    Version: 1.1
    Author URI: http://www.themeidol.com
    Text Domain: themeidol-all-widget
    Text Domain Path: /languages


    Copyright 2017 Themeidol  (email : themeidol@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
if ( ! defined( 'ABSPATH' ) ) {
   die( 'Direct access not allowed!' );
    // directly acess not allowed
}

if ( ! class_exists( 'Themeidolwidgets' ) ) :
/**
 * Main Themeidolwidgets Class
 *
 * @class Themeidolwidgets
 * @version 1.0
 */
final class Themeidolwidgets {

    /**
     * @var string
     */
    public $version = '1.1';

    /**
     * @var Themeidolwidgets The single instance of the class
     * @since 1.0
     */
    private  static $_instance = null;

    /**
    * @ Themeidolwidget Array
    *
    */
    public $widgets=array();
    
    /**
     * Available sidebar widgets.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $sidebar_widgets = array();
    /**
     * Assigned Default sidebar widgets.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $default = array();

    
    /**
     * Main Themeidolwidgets Instance
     *
     * Ensures only one instance of Themeidolwidgets is loaded or can be loaded.
     *
     * @since 1.0
     * @static
     * @see TW()
     * @return Themeidolwidgets - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Cloning is forbidden.
     * @since 1.0
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'themeidol-all-widget' ), '1.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     * @since 1.0
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'themeidol-all-widget' ), '1.0' );
    }

    /**
     * Themeidolwidgets Constructor.
     */
    public function __construct() {
        $widgetsArray=array(
                            'advert'=>array('1',__( 'Advert Space','themeidol-all-widget'),__('Easily insert images without having to resort to writing HTML in a text widget. You can also link the image or add your own advertising code.','themeidol-all-widget')),
                            'flickr'=>array('1',__('Flickr','themeidol-all-widget'),__('Show your latest images from a Flickr account right into your sidebar, and linking to your Flickr gallery.','themeidol-all-widget')),
                            'recent'=>array('1',__('Recent Post','themeidol-all-widget'),__('A more powerful version of the default recent posts widget provided by WordPress. This widget allows you to display a thumbnail of each post and also to choose which post type to display.','themeidol-all-widget')),
                            'tweet'=>array('1',__('Twiteer Feed','themeidol-all-widget'),__('Add twitter feeds on your WordPress site by using the Twitter Feed Widget plugin.','themeidol-all-widget')),
                            'social'=>array('1',__('Social Icons','themeidol-all-widget'),__('Add links to your preferred social profiles, being able to choose from a long list of available social networks.','themeidol-all-widget')),
                            'author'=>array('1',__('Author Badge','themeidol-all-widget'),__('Display the profile of a specific user, showing the name, image, and profile description.','themeidol-all-widget')),
                            'stylist'=>array('1',__('Stylist Post','themeidol-all-widget'),__('Stylish Popular Posts creates a widget which displays popular posts based on the number of comments.','themeidol-all-widget')),
                            'facebook'=>array('1',__('Facebook Page Like','themeidol-all-widget'),__('This widget adds a Simple Facebook page Like Widget into your WordPress website Sidebar','themeidol-all-widget')),
                            'ajaxsearch'=>array('1',__('Ajax Search','themeidol-all-widget'),__('Displays instant search results directly beneath a search widget.','themeidol-all-widget')),
                            'tabs'=>array('1',__('Tabs Widget','themeidol-all-widget'),__('Tab Widget is the AJAXified plugin which loads content by demand, and thus it makes the plugin incredibly lightweight.','themeidol-all-widget')),
                            'instagram'=>array('1',__('Instagram Photos','themeidol-all-widget'),__('Instagram widget is a no fuss WordPress widget to showcase your latest Instagram pics.','themeidol-all-widget')),
                            'site2quotes'=>array('1',__('Site2quotes Widget','themeidol-all-widget'),__('This plugin lets you add a Quote of the Day widget to your WordPress page.','themeidol-all-widget')),
                            'rssfeed'=>array('1',__('Rss Feed','themeidol-all-widget'),__('RSS Feeds is a small & lightweight plugin. Fast and easy to use, it aggregates RSS feeds into your site with widgets.','themeidol-all-widget')),
                            'dataandtime'=>array('1',__('Date and Time','themeidol-all-widget'),__('Widget that displays the local date and/or time.','themeidol-all-widget')),
                            'tabslogin'=>array('0',__('Tabbed Login','themeidol-all-widget'),__('Easily add an beautiful tabbed login widget to your site\'s sidebar.">All in one widget Pro</a> for more details','themeidol-all-widget')),
                            'postslider'=>array('0',__('Post Slider','themeidol-all-widget'),__('Widget Post Slider to display posts image in a slider from category.Please <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">Upgrade to Pro</a>','themeidol-all-widget')),
                            'facebookTabs'=>array('0',__('Facebook Tabs','themeidol-all-widget'),__('Responsive Facebook Tabs for WordPress. Displays - Facebook Likebox, Facebook Streams, Facebook Activity.Please <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">Upgrade to Pro</a>','themeidol-all-widget')),
                            'splw'=>array('0',__('Smart Posts','themeidol-all-widget'),__('A widget that displays posts from a specific category/tag/author.Please <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">Upgrade to Pro</a>','themeidol-all-widget')),
                            'socialcount'=>array('0',__('Social Count','themeidol-all-widget'),__('A widget that displays social count and followers in sidebar.Please <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">Upgrade to Pro</a>','themeidol-all-widget')),
                            'verticalslider'=>array('0',__('Vertical Slider','themeidol-all-widget'),__('Display latest posts or posts of specific category, which will be used as the vertical slider.Please <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">Upgrade to Pro</a>','themeidol-all-widget')),
                            'featuredverticalposts'=>array('0',__('Features Posts','themeidol-all-widget'),__('Display latest posts or posts of specific category.Please <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">Upgrade to Pro</a>','themeidol-all-widget')),
                            'contactinfo'=>array('0',__('Contact Info','themeidol-all-widget'),__('This widget is used to display contact information with map. Only available in premium plugin.Please <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">Upgrade to Pro</a>','themeidol-all-widget')),

        );
        $this->widgets = apply_filters( 'themeidol_widgets_array', $widgetsArray );
        
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->add_ajax_events();
        
       
    }

    /**
     * Hook into actions and filters
     * @since  1.0
     */
    private function init_hooks() {
        register_activation_hook( __FILE__, array( &$this, 'setup_environment' ) );
       

        add_action('admin_notices', array( $this,'admin_notice'));
        add_action('admin_init', array( $this,'admin_notice_ignore'));
        add_action( 'init', array( $this, 'init' ), 0 );
        add_filter('the_content', array( $this, 'themeidol_view_count_js')); 
        add_action('mts_view_count_after_update', array( $this, 'themeidol_update_view_count')); 
        add_action('admin_menu',  array( $this, 'themeidol_add_options_page'));
        add_filter('plugin_action_links', array( $this, 'themeidol_action_links'), 10, 2);
        // Get and disable the sidebar widgets.
        add_action( 'widgets_init', array( $this, 'set_default_sidebar_widgets' ), 100 );
        add_action( 'widgets_init', array( $this, 'disable_sidebar_widgets' ), 100 );
        // aditional links in plugin description
        add_filter('plugin_row_meta', array( $this, 'themeidol_meta_links'), 10, 2);
        // enqueue admin scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 100);
        add_action('customize_controls_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    /**
     * Define WC Constants
     */
    private function define_constants() {
            $this->define( 'HEMEIDOL_WIDGET_FILE', __FILE__ );
            $this->define( 'THEMEIDOL_WIDGET_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'THEMEIDOL_WIDGET_JS_URL', plugins_url( '/assets/js/', __FILE__ ) );
            $this->define( 'THEMEIDOL_WIDGET_VERSION', $this->version );
            $this->define( 'THEMEIDOL_WIDGET_CSS_URL', plugins_url( '/assets/css/', __FILE__ ) );
            $this->define( 'THEMEIDOL_WIDGET_IMAGES_URL', plugins_url( '/assets/img/', __FILE__ ) );
            $this->define( 'THEMEIDOL_WIDGET_PATH', dirname( __FILE__ ) );
            $this->define( 'THEMEIDOL_WIDGET_FILE', __FILE__ );
            $this->define( 'THEMEIDOL_WIDGET_CORE', plugin_dir_path(__FILE__).'widgets/');
            $this->define( 'THEMEIDOL_WIDGET_ADMIN', plugin_dir_path(__FILE__).'admin/');
        
    }

    /**
     * Define constant if not already set
     * @param  string $name
     * @param  string|bool $value
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * What type of request is this?
     * string $type ajax, frontend or admin
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }
    /**
     * Set the default sidebar widgets.
     *
     * @return array Sidebar widgets.
     */
    public function set_default_sidebar_widgets() {
        global $wp_registered_widgets;
        $widgets = array();

        if ( ! empty( $GLOBALS['wp_widget_factory'] ) ) {
            $widgets = $GLOBALS['wp_widget_factory']->widgets;
        }

        /**
         * Filters the available sidebar widgets.
         *
         * @param array $widgets The globally available sidebar widgets.
         */
        $this->sidebar_widgets = apply_filters( 'themeidol_widgets_all_array', $widgets );
        
        $WPdefault=array_merge($GLOBALS['_wp_deprecated_widgets_callbacks'],array('wp_nav_menu_widget','wp_widget_recent_posts' ));
        
        foreach ( $this->sidebar_widgets as $id => $widget_object ) {
            // Now here we need to filter the WP default widgets only
            $id_base=esc_attr( $id );
            if(in_array( strtolower(esc_attr( $id )) , $WPdefault)){
                $default[esc_attr( $id ) ]=array('0','(WP)'.esc_html( $widget_object->name ),esc_html( $widget_object->widget_options['description'] ));
            }
        }

        $this->default=apply_filters( 'themeidol_widgets_default_array', $default );
        
    }
    /*
    * Init plugin options to white list our options
    *
    */
    function themeidol_options_init(){
        register_setting( 'themeidol_plugin_options', 'themeidol_options', '' );
        wp_register_style( 'themeidol_widgets_admincss', THEMEIDOL_WIDGET_CSS_URL.'options.css');
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        //$this->set_default_sidebar_widgets();
        $this->themeidol_load_widgets();
    }
    /*
    * Function to include widget Classes
    *
    */
    public function themeidol_load_widgets() {

        $widgets_include = array();
        $themeidol_options = get_option('themeidol_options');
        $themeidol_default_option = array(   
                "advert" =>         '1',
                "flickr" =>         '0',
                "recent" =>         '1',
                "tweet" =>          '0',
                "social" =>         '1',
                "author" =>         '0',
                "stylist" =>        '1',
                "facebook" =>       '0',
                "ajaxsearch" =>     '1',
                "tabs" =>           '0',
                "instagram" =>      '0',
                "site2quotes" =>    '1',
                "rssfeed" =>        '0',
                "dataandtime"=>     '1'

            );
          
            if (false === $themeidol_options) {
                $themeidol_options = $themeidol_default_option;
            }

            foreach ( $this->glob_php( dirname( __FILE__ ) . '/widgets' ) as $file ) {
                $widgets_include[] = $file;
            }
            
        /**
         * Modify which Themeidol Widgets to register.
         *
         * @module widgets
         *
         * @since 1.0
         *
         * @param array $widgets_include An array of widgets to be registered.
         */
        $widgets_include = apply_filters( 'themeidol_widgets_to_include', $widgets_include );
        $widgetname=array();
        foreach( $widgets_include as $include ) {
            //Explode file name
            if($include!=""):
                $file = basename($include, ".php"); // $file is set to "widget_aba"
                $widgetname=explode("-",$file);
                $wname=$widgetname[1];
               // echo "TEST:".$widgetname[1].'<br/>';
                if($wname!=""):
                    if (isset($themeidol_options[$wname]) && $themeidol_options[$wname] == 1):
                    include $include;
                    endif;
                endif;
            endif;
        }
       
    }

    /**
     * Disable Sidebar Widgets.
     *
     * Gets the list of disabled sidebar widgets and disables
     * them for you in WordPress.
     *
     * @since 1.0.0
     */
    public function disable_sidebar_widgets() {
         // Enable/Disable other WP default Widgets
         $themeidol_options = get_option('themeidol_options');
   
        foreach( $this->default as $key => $value ) {
  
        if (!isset($themeidol_options[$key]) && $themeidol_options[$key] != 1):
            unregister_widget( $key );
        endif;
        }
    }


    /**
     * Returns an array of all PHP files in the specified absolute path.
     * Equivalent to glob( "$absolute_path/*.php" ).
     *
     * @param string $absolute_path The absolute path of the directory to search.
     * @return array Array of absolute paths to the PHP files.
     */
    public function glob_php( $absolute_path ) {
        if ( function_exists( 'glob' ) ) {
            return glob( "$absolute_path/*.php" );
        }

        $absolute_path = untrailingslashit( $absolute_path );
        $files = array();
        if ( ! $dir = @opendir( $absolute_path ) ) {
            return $files;
        }

        while ( false !== $file = readdir( $dir ) ) {
            if ( '.' == substr( $file, 0, 1 ) || '.php' != substr( $file, -4 ) ) {
                continue;
            }

            $file = "$absolute_path/$file";

            if ( ! is_file( $file ) ) {
                continue;
            }

            $files[] = $file;
        }

        closedir( $dir );

        return $files;
    }

   


    /**
     * Init Themeidolwidgets when WordPress Initialises.
     */
    public function init() {
         // Set up localisation
         $this->load_plugin_textdomain();
         add_action('wp_enqueue_scripts',  array( $this, 'setup_environment_for_styling'));
  
    }
    /**
     * Init Themeidol widget admin notice Initialises.
     */
    public function admin_notice() {
        global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
        if ( ! get_user_meta($user_id, 'themeidol_tab_widget_ignore_notice') ) {
            echo '<div class="updated notice-info themeidol-widget-notice" style="position:relative;"><p>';
            printf(__('Like Themeidol all-in-one Widget? You will <strong>LOVE our All-in-one widget Pro</strong>!','themeidol-all-widget').'<a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">&nbsp;'.__('Click here for all the exciting features.','themeidol-all-widget').'</a><a href="%1$s" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>', '?themeidol_tab_widget_notice_ignore=0');
            echo "</p></div>";
        }
    } 
    /**
     * Init Themeidol widget admin notice close Initialises.
     */
    public function admin_notice_ignore() {
        $this->themeidol_options_init();
        global $current_user;
            $user_id = $current_user->ID;
            /* If user clicks to ignore the notice, add that to their user meta */
            if ( isset($_GET['themeidol_tab_widget_notice_ignore']) && '0' == $_GET['themeidol_tab_widget_notice_ignore'] ) {
                add_user_meta($user_id, 'themeidol_tab_widget_ignore_notice', 'true', true);
        }

    }

    /**
     * Load Localisation files.
     *
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain( 'themeidol-all-widget', false, dirname( THEMEIDOL_WIDGET_BASENAME ) . '/languages/' );
    }

    /**
     * Ensure theme and server variable compatibility and setup image sizes.
     */
    public function setup_environment() {
        $this->set_default_sidebar_widgets();
        $this->add_views_meta_for_posts();
        $this->add_thumbnail_support();
        $this->add_image_sizes();
        $this->themeidol_add_defaults_options();
        $this->reset_pointers();
    }
    /*
    * clear Environment
    */
    public function clear_environment()
    {
        delete_option('themeidol_options');
        delete_transient('themeidol_pointers');
        
    }
    /*
    * Define default option settings
    */
    function themeidol_add_defaults_options() {
            delete_option('themeidol_options');
            $arr = array(   
                "advert" =>         "1",
                "flickr" =>         "1",
                "recent" =>         "1",
                "tweet" =>          "1",
                "social" =>         "1",
                "author" =>         "1",
                "stylist" =>        "1",
                "facebook" =>       "1",
                "ajaxsearch" =>     "1",
                "tabs" =>           "1",
                "instagram" =>      "1",
                "site2quotes" =>    "1",
                "rssfeed" =>        "1",
                "dataandtime"=>     "1"
            );

            $result=array();
            foreach( $this->default as $key => $value ) {
                $result[$key]='1';
            }
            $allWidgets=array_merge($arr,$result);
             update_option('themeidol_options', $allWidgets);

            
  
    }

    /*
    * Add menu page
    */
    function themeidol_add_options_page() {
        /* Add our plugin submenu and administration screen */
        $hook = add_submenu_page( 'options-general.php', // The parent page of this submenu
                                  __( 'Themeidol Widget', 'themeidol-all-widget' ), // The submenu title
                                  __( 'Themeidol Widget', 'themeidol-all-widget' ), // The screen title
                  'manage_options', // The capability required for access to this submenu
                  'themeidol-widget-pack-options', // The slug to use in the URL of the screen
                                  array(&$this,'themeidol_render_form') // The function to call to display the screen
                               );

        add_action('admin_print_scripts-' . $hook, array($this,'themeidol_smart_widgets_css'));
    }

    /*
    * Add Settings link in plugin list page
    */
   
    public function themeidol_action_links($links, $file){
        if($file == 'all-in-one-widget/all-in-one-widget.php'){
            $new_links = '<a href="'.admin_url('options-general.php?page=themeidol-widget-pack-options').'">'.__('Settings', 'themeidol-all-widget').'</a>';
            array_unshift($links, $new_links);
        }
        return $links;
    }
    // enqueue CSS and JS scripts in admin
    static function admin_enqueue_scripts() {
        $pointers = get_transient('themeidol_pointers');
        $current_screen = get_current_screen();
        if ($pointers && !$this->is_plugin_admin_page('widgets')) {
          $pointers['_nonce_dismiss_pointer'] = wp_create_nonce('themeidol_dismiss_pointer');
          wp_enqueue_script('wp-pointer');
          wp_enqueue_script('themeidol-pointers', THEMEIDOL_WIDGET_JS_URL.'themeidol-admin-pointers.js', array('jquery'), $this->version, true);
          wp_enqueue_style('wp-pointer');
          wp_localize_script('wp-pointer', 'themeidol_pointers', $pointers);
        }
    }
    // add links to plugin's description in plugins table
    static function themeidol_meta_links($links, $file) {
        $documentation_link = '<a target="_blank" href="http://themeidol.com/product/all-in-one-widget/" title="' . __('View All-in-one Widget', 'themeidol-all-widget') . '">'. __('Details', 'themeidol-all-widget') . '</a>';
        $support_link = '<a target="_blank" href="http://themeidol.com/submit-ticket/" title="' . __('Problems? We are here to help!', 'themeidol-all-widget') . '">' . __('Support', 'themeidol-all-widget') . '</a>';
        $review_link = '<a target="_blank" href="https://wordpress.org/support/plugin/all-in-one-widget/reviews/?filter=5" title="' . __('If you like it, please review the plugin', 'themeidol-all-widget') . '">' . __('Review the plugin', 'themeidol-all-widget') . '</a>';
        $activate_link = '<a href="http://themeidol.com/product/all-in-one-widget-pro/">' . __('Upgrade to PRO', 'themeidol-all-widget') . '</a>';

        if ($file == plugin_basename(__FILE__)) {
          $links[] = $documentation_link;
          $links[] = $support_link;
          $links[] = $review_link;
          $links[] = $activate_link;  
         
    }

    return $links;
  } // themeidol_meta_links

    //register_activation_hook
      // reset all pointers to default state - visible
    public function reset_pointers() {
        $pointers = array();
        $pointers['welcome'] = array('target' => '#menu-appearance', 'edge' => 'left', 'align' => 'right', 'content' => 'Thank you for installing <b>All-in-one Widget</b>! Please open <a href="' . admin_url('options-general.php?page=themeidol-widget-pack-options'). '">Settings</a> to Enable/Disable Widgets.<br/> For more awesome widget please visit<br/> <a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">All-in-one widget Pro</a');
        
        set_transient('themeidol_pointers', $pointers, 60 * DAY_IN_SECONDS);
    } // reset_pointers

     // permanently dismiss a pointer
    function dismiss_pointer_ajax() {
        check_ajax_referer('themeidol_dismiss_pointer');
        
        $pointers = get_transient('themeidol_pointers');
        $pointer = trim($_POST['pointer']);

        if (empty($pointers) || empty($pointers[$pointer])) {
          wp_send_json_error();
        }

        unset($pointers[$pointer]);
        set_transient('themeidol_pointers', $pointers);
        
        wp_send_json_success();
    } // dismiss_pointer_ajax
    // check if plugin's admin page is shown
    function is_plugin_admin_page($page = 'widgets') {
        $current_screen = get_current_screen();

        if ($page == 'widgets' && $current_screen->id == 'widgets') {
          return true;
        }

        if ($page == 'settings' && $current_screen->id == 'settings_page_themeidol-widget-pack-options') {
          return true;
        }

        if ($page == 'plugins' && $current_screen->id == 'plugins') {
          return true;
        }

        return false;
    } // is_plugin_admin_page


    public function themeidol_smart_widgets_css() {
        /* Link already registered script to the settings page */
        wp_enqueue_style( 'themeidol_widgets_admincss' );
    }

    function themeidol_render_form()
    {
        include(THEMEIDOL_WIDGET_ADMIN.'admin-option.php');
    }

    /**
     * Ensure theme and server variable compatibility and setup image sizes.
     */

    public function setup_environment_for_styling() {
        wp_enqueue_style('themeidol-widgets', THEMEIDOL_WIDGET_CSS_URL.'style.css');    
       
    }
    /**
    * Add meta for all existing posts that don't have it
    * to make them show up in Popular tab
    */
    public function add_views_meta_for_posts() {
        $allposts = get_posts( 'numberposts=-1&post_type=post&post_status=any' );

        foreach( $allposts as $postinfo ) {
            add_post_meta( $postinfo->ID, '_themeidol_view_count', 0, true );
        }
    }

    public function add_ajax_events() {
        add_action('wp_ajax_wpt_view_count', array( $this, 'ajax_themeidol_view_count'));
        add_action('wp_ajax_nopriv_wpt_view_count',array( $this, 'ajax_themeidol_view_count'));
        add_action('wp_ajax_themeidol_dismiss_pointer', array($this,  'dismiss_pointer_ajax'));
        add_action('wp_ajax_nopriv_themeidol_dismiss_pointe',array( $this, 'dismiss_pointer_ajax'));

    }

    public function themeidol_view_count_js( $content ) {
            global $post;
            $id = $post->ID;
            $use_ajax = apply_filters( 'mts_view_count_cache_support', true );
            
            $exclude_admins = apply_filters( 'mts_view_count_exclude_admins', false ); // pass in true or a user capaibility
            if ($exclude_admins === true) $exclude_admins = 'edit_posts';
            if ($exclude_admins && current_user_can( $exclude_admins )) return $content; // do not count post views here

            if (is_single()) {
                if ( ! has_filter('the_content', 'mts_view_count_js') && $use_ajax) { // prevent additional ajax call if theme has view counter already
                    // enqueue jquery
                    wp_enqueue_script( 'jquery' );
                    
                    $url = admin_url( 'admin-ajax.php' );
                    $content .= "
                    <script type=\"text/javascript\">
                    jQuery(document).ready(function($) {
                    $.post('{$url}', {action: 'wpt_view_count', id: '{$id}'})
                    });
                    </script>";
                    
                }

                // if there's no general filter set and ajax is OFF
                if (! has_filter('the_content', 'mts_view_count_js') && ! $use_ajax) {
                    $this->themeidol_update_view_count($id);
                }
            } 

            return $content;
    }

    public function ajax_themeidol_view_count() {
        // do count
        
        if ( isset( $_POST['id'] ) ) {
            $post_id =  intval( $_POST['id']);
        // apply more sanitizations here if needed
        }
        $this->themeidol_update_view_count( $post_id );
    }

    public function themeidol_update_view_count( $post_id ) {
        $post_id =  intval( $post_id);
        $count = get_post_meta( $post_id, '_themeidol_view_count', true );
        update_post_meta( $post_id, '_themeidol_view_count', $count + 1 );
    }

    // Reset post count for specific post or all posts
    public function themeidol_reset_post_count($post_id = 0) {
        $post_id =  intval( $post_id);
        if ($post_id == 0) {
            $allposts = get_posts( 'numberposts=-1&post_type=post&post_status=any' );
            foreach( $allposts as $postinfo ) {
                update_post_meta( $postinfo->ID, '_themeidol_view_count', '0' );
            }
        } else {
            update_post_meta( $post_id, '_themeidol_view_count', '0' );
        }
    }

    /**
     * Ensure post thumbnail support is turned on
     */
    private function add_thumbnail_support() {
        if ( ! current_theme_supports( 'post-thumbnails' ) ) {
            add_theme_support( 'post-thumbnails' );
        }
    }

    /**
     * Add TW Image sizes to WP
     *
     * @since 1.0
     */
    private function add_image_sizes() {
        add_image_size( 'popular_posts_img', 600, 360, true );
        add_image_size( 'wp_review_small', 65, 65, true ); // small thumb
        add_image_size( 'wp_review_large', 320, 240, true ); // large thumb
        
    }

    /**
     * Get the plugin url.
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }


    /**
     * Get the plugin path.
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
    
    /**
     * Get Ajax URL.
     * @return string
     */
    public function ajax_url() {
        return admin_url( 'admin-ajax.php', 'relative' );
    }

}
endif;
/**
 * Returns the main instance of TW to prevent the need to use globals.
 *
 * @since  1.0
 * @return Themeidolwidgets
 */
    function TW() {
        return Themeidolwidgets::instance();
    }
// Global for backwards compatibility.
 $GLOBALS['themeidolwidgets'] = TW();
 register_uninstall_hook(__FILE__, array('Themeidolwidgets', 'clear_environment'));
 register_deactivation_hook(__FILE__, array('Themeidolwidgets', 'clear_environment'));