<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/public
 */

use WPVR\Builder\DIVI\WPVR_Divi_modules;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpvr
 * @subpackage Wpvr/public
 * @author     Rextheme <support@rextheme.com>
 */
class Wpvr_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    8.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    8.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


    /**
     * Instance of WPVR_Shortcode class
     * 
     * @var object
     * @since 8.0.0
     */
    private $shortcode;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    8.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->shortcode = new WPVR_Shortcode($this->plugin_name);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    8.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpvr_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpvr_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $wp;
        $wpvr_script_control = get_option('wpvr_script_control');
        $wpvr_script_list = get_option('wpvr_script_list');
        $allowed_pages_modified = array();
        $allowed_pages = explode(",", $wpvr_script_list);
        foreach ($allowed_pages as $value) {
            $allowed_pages_modified[] = untrailingslashit($value);
        }
        $current_url = home_url(add_query_arg(array($_GET), $wp->request));
        if ($wpvr_script_control == 'true') {
            foreach ($allowed_pages_modified as $value) {
                if ($value) {
                    if (strpos($current_url, $value) !== false) {
                        $fontawesome_disable = get_option('wpvr_fontawesome_disable');
                        if ($fontawesome_disable == 'true') {
                        } else {
                            wp_enqueue_style($this->plugin_name . 'fontawesome', 'https://use.fontawesome.com/releases/v6.5.1/css/all.css', array(), $this->version, 'all');
                        }
                        wp_enqueue_style('panellium-css', plugin_dir_url(__FILE__) . 'lib/pannellum/src/css/pannellum.css', array(), true);
                        wp_enqueue_style('videojs-css', plugin_dir_url(__FILE__) . 'lib/pannellum/src/css/video-js.css', array(), true);
                        wp_enqueue_style('owl-css', plugin_dir_url(__FILE__) . 'css/owl.carousel.css', array(), $this->version, 'all');
                        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpvr-public.css', array(), $this->version, 'all');
                    }
                }
            }
        } else {
            $fontawesome_disable = get_option('wpvr_fontawesome_disable');
            if ($fontawesome_disable == 'true') {
            } else {
                wp_enqueue_style($this->plugin_name . 'fontawesome', 'https://use.fontawesome.com/releases/v6.5.1/css/all.css', array(), $this->version, 'all');
            }
            wp_enqueue_style('panellium-css', plugin_dir_url(__FILE__) . 'lib/pannellum/src/css/pannellum.css', array(), true);
            wp_enqueue_style('videojs-css', plugin_dir_url(__FILE__) . 'lib/pannellum/src/css/video-js.css', array(), true); // commented for video js vr
            // wp_enqueue_style('videojs-css', 'https://vjs.zencdn.net/7.18.1/video-js.css', array(), true); // commented for video js vr
            wp_enqueue_style('videojs-vr-css', plugin_dir_url(__FILE__) . 'lib/videojs-vr/videojs-vr.css', array(), true); //video js VR
            wp_enqueue_style('owl-css', plugin_dir_url(__FILE__) . 'css/owl.carousel.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpvr-public.css', array(), $this->version, 'all');
        }

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    8.0.0
	 */
	public function enqueue_scripts() {

		$notice = '';
        $wpvr_frontend_notice = get_option('wpvr_frontend_notice');
        if ($wpvr_frontend_notice) {
            $notice = get_option('wpvr_frontend_notice_area');
        }
        global $wp;
        $wpvr_script_control = get_option('wpvr_script_control');
        $wpvr_script_list = get_option('wpvr_script_list');
        $allowed_pages_modified = array();
        $allowed_pages = explode(",", $wpvr_script_list);
        foreach ($allowed_pages as $value) {
            $allowed_pages_modified[] = untrailingslashit($value);
        }

        $wpvr_video_script_control = get_option('wpvr_video_script_control');
        $wpvr_video_script_list = get_option('wpvr_video_script_list');
        $allowed_video_pages_modified = array();
        $allowed_video_pages = explode(",", $wpvr_video_script_list);
        foreach ($allowed_video_pages as $value) {
            $allowed_video_pages_modified[] = untrailingslashit($value);
        }

        $current_url = home_url(add_query_arg(array($_GET), $wp->request));

        if ($wpvr_script_control == 'true') {
            foreach ($allowed_pages_modified as $value) {
                if (strpos($current_url, $value) !== false) {
                    wp_enqueue_script('panellium-js', plugin_dir_url(__FILE__) . 'lib/pannellum/src/js/pannellum.js', array(), true);
                    wp_enqueue_script('panelliumlib-js', plugin_dir_url(__FILE__) . 'lib/pannellum/src/js/libpannellum.js', array(), true);
                    wp_enqueue_script('videojs-js', plugin_dir_url(__FILE__) . 'js/video.js', array(), true); //commented for video js vr
                    // wp_enqueue_script('videojs-js', 'https://vjs.zencdn.net/7.18.1/video.min.js', array(), true);
                    wp_enqueue_script('videojsvr-js', plugin_dir_url(__FILE__) . 'lib/videojs-vr/videojs-vr.js', array(), true); //video js vr
                    wp_enqueue_script('panelliumvid-js', plugin_dir_url(__FILE__) . 'lib/pannellum/src/js/videojs-pannellum-plugin.js', array(), true);
                    wp_enqueue_script('owl-js', plugin_dir_url(__FILE__) . 'js/owl.carousel.js', array('jquery'), false);
                    wp_enqueue_script('jquery_cookie', plugin_dir_url(__FILE__) . 'js/jquery.cookie.js', array('jquery'), true);
                    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpvr-public.js', array('jquery', 'jquery_cookie'), $this->version, false);
                    wp_localize_script('wpvr', 'wpvr_public', array(
                        'notice_active' => $wpvr_frontend_notice,
                        'notice' => $notice,
                    ));
                }
            }
        } else {
            wp_enqueue_script('panellium-js', plugin_dir_url(__FILE__) . 'lib/pannellum/src/js/pannellum.js', array(), true);
            wp_enqueue_script('panelliumlib-js', plugin_dir_url(__FILE__) . 'lib/pannellum/src/js/libpannellum.js', array(), true);
            wp_enqueue_script('videojs-js', plugin_dir_url(__FILE__) . 'js/video.js', array(), true); // commented for video js vr
            wp_enqueue_script('videojsvr-js', plugin_dir_url(__FILE__) . 'lib/videojs-vr/videojs-vr.js', array(), true); //video js vr
            wp_enqueue_script('panelliumvid-js', plugin_dir_url(__FILE__) . 'lib/pannellum/src/js/videojs-pannellum-plugin.js', array(), true);
            wp_enqueue_script('owl-js', plugin_dir_url(__FILE__) . 'js/owl.carousel.js', array('jquery'), false);
            wp_enqueue_script('jquery_cookie', plugin_dir_url(__FILE__) . 'js/jquery.cookie.js', array('jquery'), true);
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpvr-public.js', array('jquery', 'jquery_cookie'), $this->version, true);
            wp_localize_script('wpvr', 'wpvr_public', array(
                'notice_active' => $wpvr_frontend_notice,
                'notice' => $notice,
            ));
        }

        $match_found = false;
        if ($wpvr_video_script_control == 'true') {
            foreach ($allowed_video_pages_modified as $value) {
                if (strpos($current_url, $value) !== false) {
                    $match_found = true;
                    wp_enqueue_script('videojs-js', plugin_dir_url(__FILE__) . 'js/video.js', array(), true); // commented for video js vr
                    wp_enqueue_script('videojsvr-js', plugin_dir_url(__FILE__) . 'lib/videojs-vr/videojs-vr.js', array(), true); //video js vr
                    wp_enqueue_script('panelliumvid-js', plugin_dir_url(__FILE__) . 'lib/pannellum/src/js/videojs-pannellum-plugin.js', array(), true);
                }
            }
            if (!$match_found) {
                wp_dequeue_script('videojs-js');
                wp_dequeue_script('videojsvr-js');
                wp_dequeue_script('panelliumvid-js');
            }
        }

	}

	/**
     * Init the edit screen of the plugin post type item
     *
     * @since 8.0.0
     */
    public function public_init()
    {
        add_shortcode($this->plugin_name, array( $this->shortcode , 'wpvr_shortcode'));

    }

}
