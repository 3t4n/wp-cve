<?php
/**
 * @package Social Share With Floating Bar
 * @version 1.0.3
 */
/*
 * Plugin Name: Social Share With Floating Bar
 * Version: 1.0.3
 * Plugin URI: https://www.themeinwp.com/social-share-with-floating-bar/
 * Description: Grow Your Audience, Enhance your website traffic and engage your visitors with Free to use and Mobile optimized Social Share With Floating Bar WordPress Plugin. You can add/select share icons for  Facebook, Twitter, VK, Google+, Pinterest and Email.
 * Author: ThemeInWP
 * Author URI: https://www.themeinwp.com/
 * Requires at least: 4.5
 * Tested up to: 5.7
 * Requires PHP: 5.5
 * Text Domain: social-share-with-floating-bar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'inc/class-social-share-with-floating-bar-settings.php' );

class Social_Share_With_Floating_Bar {

	/**
	 * The single instance of Social_Share_With_Floating_Bar.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Url protocol
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $url_protocol;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {

		$this->_version = $version;
		$this->_token = 'social_share_with_floating_bar';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if (isset($_SERVER['HTTPS']) &&
		    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
		    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
			$this->url_protocol = 'https';
		} else {
			$this->url_protocol = 'http';
		}

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Social_Share_With_Floating_Bar_Admin_API();
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );

		//Add post content filters
		add_filter( 'the_content', array( $this, 'add_share_buttons_to_post' ));
		add_filter( 'the_content', array( $this, 'add_share_buttons_to_media' ));

		//Create shortcode
		add_shortcode( 'sswfb_post_share', array($this, 'share_post_shortcode') );

		add_action( 'add_meta_boxes', array( $this, 'social_share_metabox' ) );
		add_action( 'save_post', array( $this, 'social_share_save_post_meta' ) );

		if( isset( $_GET['page'] ) && $_GET['page'] == 'social_share_with_floating_bar_settings' ){

            add_action('in_admin_header', array( $this,'social_share_hide_all_admin_notice' ),1000 );

        }
        
        
	} // End __construct ()


	public function social_share_hide_all_admin_notice(){

        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
        
    }

    function  social_share_metabox() {
        
        add_meta_box(
            'social-share-with-floating-bar-custom-metabox',
            esc_html__( 'Social Share With Floating Bar Settings', 'social-share-with-floating-bar' ),
            array( $this,'social_share_post_metafield_callback'),
            'post', 
            'normal', 
            'high'
        );
    }

	/**
	 * Callback function for post option.
	*/
	    
	function social_share_post_metafield_callback() {

		global $post;
        $post_type = get_post_type($post->ID);
		wp_nonce_field( basename( __FILE__ ), 'social_share_with_floating_bar_post_meta_nonce' );
		$sswfb_ed_post_social_share = get_post_meta( $post->ID, 'sswfb_ed_post_social_share', true ); ?>
        
        <div class="metabox-main-block">

            <div class="metabox-navbar">
                <ul>

                    <li>
                        <a id="metabox-navbar-general" class="metabox-navbar-active" href="javascript:void(0)">

                            <?php esc_html_e('General Settings', 'social-share-with-floating-bar'); ?>

                        </a>
                    </li>


                </ul>
            </div>

            <div class="twp-tab-content">

                <div id="metabox-navbar-general-content" class="metabox-content-wrap metabox-content-wrap-active">

                    <div class="metabox-opt-panel">

                       	<h3 class="meta-opt-title"><?php esc_html_e('Social Share Floating Bar Setting','social-share-with-floating-bar'); ?></h3>

                        <div class="metabox-opt-wrap twp-checkbox-wrap">

                            <input type="checkbox" id="social-share-with-floating-bar-ed-post-social-share" name="sswfb_ed_post_social_share" value="1" 

                            <?php if( $sswfb_ed_post_social_share ){ echo "checked='checked'";} ?>/>

                            <label for="social-share-with-floating-bar-ed-post-social-share"><?php esc_html_e( 'Disable Social Share On This Post','social-share-with-floating-bar' ); ?></label>

                        </div>

                    </div>

                </div>

            </div>

        </div>  
            
    <?php }

    // Save metabox value.
    function social_share_save_post_meta( $post_id ) {

        global $post;

        if( !isset( $_POST[ 'social_share_with_floating_bar_post_meta_nonce' ] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['social_share_with_floating_bar_post_meta_nonce'] ) ), basename( __FILE__ ) ) ){

            return;

        }

        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){

            return;

        }
            
        if( isset(  $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {  

            if ( !current_user_can( 'edit_page', $post_id ) ){  

                return $post_id;

            }

        }elseif( !current_user_can( 'edit_post', $post_id ) ) {

            return $post_id;

        }

        $sswfb_ed_post_social_share_old = absint( get_post_meta( $post_id, 'sswfb_ed_post_social_share', true ) );
        $sswfb_ed_post_social_share_new = '';

        if( isset( $_POST['sswfb_ed_post_social_share'] ) ){

	        $sswfb_ed_post_social_share_new = absint( wp_unslash( $_POST['sswfb_ed_post_social_share'] ) );

	    }
        if( $sswfb_ed_post_social_share_new && $sswfb_ed_post_social_share_new != $sswfb_ed_post_social_share_old ){

            update_post_meta ( $post_id, 'sswfb_ed_post_social_share', $sswfb_ed_post_social_share_new );

        }elseif( '' == $sswfb_ed_post_social_share_new && $sswfb_ed_post_social_share_old ) {

            delete_post_meta( $post_id,'sswfb_ed_post_social_share', $sswfb_ed_post_social_share_old );

        }


    } 
	    
	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {

		if ( !get_option( 'sswfb_load_css' ) ) {

			wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend' . $this->script_suffix . '.css', array(), $this->_version );

			wp_enqueue_style( $this->_token . '-frontend' );

		}

	} // End enqueue_styles ()

	/**
	 * Load Backend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_admin_styles () {

		if ( !get_option( 'sswfb_load_admin_css' ) ) {

			wp_register_style( $this->_token . '-sswfb-admin-style', esc_url( $this->assets_url ) . 'css/sswfb-admin-style' . $this->script_suffix . '.css', array(), $this->_version );
			wp_enqueue_style( $this->_token . '-sswfb-admin-style' );

		}

	} // End enqueue_styles ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {

		load_plugin_textdomain( 'social-share-with-floating-bar', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );

	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {

	    $domain = 'social-share-with-floating-bar';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );

	} // End load_plugin_textdomain ()


	/**
	 * Main Social_Share_With_Floating_Bar Instance
	 *
	 * Ensures only one instance of Social_Share_With_Floating_Bar is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Social_Share_With_Floating_Bar()
	 * @return Main Social_Share_With_Floating_Bar instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;

	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );

	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );

	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {

		$this->_log_version_number();

	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {

		update_option( $this->_token . '_version', $this->_version );

	} // End _log_version_number ()

	/**
	 * Return the url of the current page.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function get_current_url () {

		global $wp;
		return home_url( add_query_arg( array(), $wp->request ) ) . '/';

	} // End get_current_url()

	/**
	 * Create and return a post excerpt from a post ID outside of the loop.
	 * A similar function in Wordpress was deprecated.
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function get_excerpt_by_id ( $post_id ) {

	    $the_post = get_post( $post_id ); //Gets post ID
	    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
	    $excerpt_length = 35; //Sets excerpt length by word count
	    $the_excerpt = strip_tags( strip_shortcodes( $the_excerpt ) ); //Strips tags and images
	    $words = explode( ' ', $the_excerpt, $excerpt_length + 1 );

	    if ( count( $words ) > $excerpt_length ) {
	        array_pop( $words );
	        array_push( $words, '…' );
	        $the_excerpt = implode( ' ', $words );
	    }

	    return $the_excerpt;

	} //End get_excerpt_by_id()

	/**
	 * Generate buttons
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function get_button_html ( $service, $post_id, $show_count, $image_url = null, $image_id = null ) {

		$title = apply_filters( 'update_social_share_with_floating_bar_title', esc_html( get_the_title( $post_id ) ) );
		$description = apply_filters( 'update_social_share_with_floating_bar_description', esc_html( $this->get_excerpt_by_id( $post_id ) ) );
		$link = esc_url( get_permalink( $post_id ) );

		// Use direct link to image
		if ( $image_id ) {
			if ( get_option( 'permalink_structure' ) ) {
				$slash = '';	
			} else {
				// if pretty permalinks are not enabled add slash
				$slash = '/';
			}

			$link = $link . $slash . '#' . $image_id;
		}
		
		$link = apply_filters( 'update_social_share_with_floating_bar_url', esc_url( $link ) );

		switch ( $service ) {

			case 'facebook':

			    $query = array(
			    	'app_id' => esc_html( get_option( 'sswfb_facebook_app_id' ) ),
			    	'display' => 'popup',
			    	'caption' => apply_filters( 'update_social_share_with_floating_bar_title_facebook', $title ),
			    	'link' => apply_filters( 'update_social_share_with_floating_bar_url_facebook', $link ),
			    	'description' => apply_filters( 'update_social_share_with_floating_bar_description_facebook', $description )
			    );

			    if ( !empty( $image_url ) ) {
			    	$query['picture'] = $image_url;
			    }

			    $popup_url = 'https://www.facebook.com/dialog/feed?' . http_build_query($query, null, '&amp;', PHP_QUERY_RFC3986);
			    $svg_viewbox = '0 0 264 448';
			    $svg = '<path d="M239.75 3v66h-39.25q-21.5 0-29 9t-7.5 27v47.25h73.25l-9.75 74h-63.5v189.75h-76.5v-189.75h-63.75v-74h63.75v-54.5q0-46.5 26-72.125t69.25-25.625q36.75 0 57 3z"></path>';
			    $onclick_action = 'window.open(this.href, \'facebookwindow\',\'left=20,top=20,width=600,height=700,toolbar=0,resizable=1\'); return false;';
			    $link_title =  esc_html__('Share on Facebook','social-share-with-floating-bar');
			    $action =  esc_html__('Share on Facebook','social-share-with-floating-bar');

			break;

			case 'twitter':

				$query = array(
					'text' => apply_filters( 'update_social_share_with_floating_bar_twitter_text', $title . ' ' . $link )
				);

				$popup_url = 'https://twitter.com/intent/tweet?' . http_build_query($query, null, '&amp;', PHP_QUERY_RFC3986);
				$popup_url = str_replace( '%23038%3B', '', $popup_url);

				$svg_viewbox = '0 0 1024 1024';
				$svg = '<path d="M1024 194.418c-37.676 16.708-78.164 28.002-120.66 33.080 43.372-26 76.686-67.17 92.372-116.23-40.596 24.078-85.556 41.56-133.41 50.98-38.32-40.83-92.922-66.34-153.346-66.34-116.022 0-210.088 94.058-210.088 210.078 0 16.466 1.858 32.5 5.44 47.878-174.6-8.764-329.402-92.4-433.018-219.506-18.084 31.028-28.446 67.116-28.446 105.618 0 72.888 37.088 137.192 93.46 174.866-34.438-1.092-66.832-10.542-95.154-26.278-0.020 0.876-0.020 1.756-0.020 2.642 0 101.788 72.418 186.696 168.522 206-17.626 4.8-36.188 7.372-55.348 7.372-13.538 0-26.698-1.32-39.528-3.772 26.736 83.46 104.32 144.206 196.252 145.896-71.9 56.35-162.486 89.934-260.916 89.934-16.958 0-33.68-0.994-50.116-2.94 92.972 59.61 203.402 94.394 322.042 94.394 386.422 0 597.736-320.124 597.736-597.744 0-9.108-0.206-18.168-0.61-27.18 41.056-29.62 76.672-66.62 104.836-108.748z"></path>';
			    $onclick_action = 'window.open(this.href, \'twitterwindow\',\'left=20,top=20,width=600,height=300,toolbar=0,resizable=1\'); return false;';
			    $link_title =  esc_html__('Tweet','social-share-with-floating-bar');
			    $action =  esc_html__('Tweet it','social-share-with-floating-bar');

			break;
			
			case 'reddit':

				$popup_url = 'https://www.reddit.com/submit?url=' . apply_filters( 'update_social_share_with_floating_bar_url_reddit', $link );
				$svg_viewbox = '0 0 491.858 491.858';
                $svg = '<path fill="currentColor" d="M440.3 203.5c-15 0-28.2 6.2-37.9 15.9-35.7-24.7-83.8-40.6-137.1-42.3L293 52.3l88.2 19.8c0 21.6 17.6 39.2 39.2 39.2 22 0 39.7-18.1 39.7-39.7s-17.6-39.7-39.7-39.7c-15.4 0-28.7 9.3-35.3 22l-97.4-21.6c-4.9-1.3-9.7 2.2-11 7.1L246.3 177c-52.9 2.2-100.5 18.1-136.3 42.8-9.7-10.1-23.4-16.3-38.4-16.3-55.6 0-73.8 74.6-22.9 100.1-1.8 7.9-2.6 16.3-2.6 24.7 0 83.8 94.4 151.7 210.3 151.7 116.4 0 210.8-67.9 210.8-151.7 0-8.4-.9-17.2-3.1-25.1 49.9-25.6 31.5-99.7-23.8-99.7zM129.4 308.9c0-22 17.6-39.7 39.7-39.7 21.6 0 39.2 17.6 39.2 39.7 0 21.6-17.6 39.2-39.2 39.2-22 .1-39.7-17.6-39.7-39.2zm214.3 93.5c-36.4 36.4-139.1 36.4-175.5 0-4-3.5-4-9.7 0-13.7 3.5-3.5 9.7-3.5 13.2 0 27.8 28.5 120 29 149 0 3.5-3.5 9.7-3.5 13.2 0 4.1 4 4.1 10.2.1 13.7zm-.8-54.2c-21.6 0-39.2-17.6-39.2-39.2 0-22 17.6-39.7 39.2-39.7 22 0 39.7 17.6 39.7 39.7-.1 21.5-17.7 39.2-39.7 39.2z" class=""></path>';
			    $onclick_action = 'window.open(this.href, \'redditwindow\',\'left=20,top=20,width=600,height=300,toolbar=0,resizable=1\'); return false;';
			    $link_title =  esc_html__('Share on Reddit','social-share-with-floating-bar');
			    $action =  esc_html__('Share on Reddit','social-share-with-floating-bar');

			break;

			case 'pinterest':

				$query = array(
					'media' => $image_url,
					'url' => apply_filters( 'update_social_share_with_floating_bar_url_pinterest', $link ),
					'is_video' => 'false',
					'description' => apply_filters( 'update_social_share_with_floating_bar_description_pinterest', $title . ' - ' . $description )
				);

				$popup_url = 'https://pinterest.com/pin/create/bookmarklet/?' . http_build_query($query, null, '&amp;', PHP_QUERY_RFC3986);
				$svg_viewbox = '0 0 320 448';
				$svg = '<path d="M0 149.25q0-27 9.375-50.875t25.875-41.625 38-30.75 46.25-19.5 50.5-6.5q39.5 0 73.5 16.625t55.25 48.375 21.25 71.75q0 24-4.75 47t-15 44.25-25 37.375-36.25 25.75-47.25 9.625q-17 0-33.75-8t-24-22q-2.5 9.75-7 28.125t-5.875 23.75-5.125 17.75-6.5 17.75-8 15.625-11.5 19.375-15.5 21.625l-3.5 1.25-2.25-2.5q-3.75-39.25-3.75-47 0-23 5.375-51.625t16.625-71.875 13-50.75q-8-16.25-8-42.25 0-20.75 13-39t33-18.25q15.25 0 23.75 10.125t8.5 25.625q0 16.5-11 47.75t-11 46.75q0 15.75 11.25 26.125t27.25 10.375q13.75 0 25.5-6.25t19.625-17 14-23.75 9.5-27.625 5-27.75 1.625-24.875q0-43.25-27.375-67.375t-71.375-24.125q-50 0-83.5 32.375t-33.5 82.125q0 11 3.125 21.25t6.75 16.25 6.75 11.375 3.125 7.625q0 7-3.75 18.25t-9.25 11.25q-0.5 0-4.25-0.75-12.75-3.75-22.625-14t-15.25-23.625-8.125-27-2.75-26.625z"></path>';
			    $onclick_action = 'window.open(this.href, \'pinterestwindow\',\'left=20,top=20,width=750,height=750,toolbar=0,resizable=1\');return false;';
			    $link_title =  esc_html__('Pin','social-share-with-floating-bar');
			    $action =  esc_html__('Pin it','social-share-with-floating-bar');

			break;

			case 'vk' :

				$query = array(
					'url' => apply_filters( 'update_social_share_with_floating_bar_url_vk', $link ),
					//'is_video' => 'false',
					'description' => apply_filters( 'update_social_share_with_floating_bar_description_vk', $title . ' - ' . $description ),
					'media' => $image_url,
				);

				$popup_url = 'http://vk.com/share.php?'. http_build_query($query, null, '&amp;', PHP_QUERY_RFC3986);
				$svg_viewbox = '0 0 548.358 548.358';
				$svg = '<path d="M545.451,400.298c-0.664-1.431-1.283-2.618-1.858-3.569c-9.514-17.135-27.695-38.167-54.532-63.102l-0.567-0.571   l-0.284-0.28l-0.287-0.287h-0.288c-12.18-11.611-19.893-19.418-23.123-23.415c-5.91-7.614-7.234-15.321-4.004-23.13   c2.282-5.9,10.854-18.36,25.696-37.397c7.807-10.089,13.99-18.175,18.556-24.267c32.931-43.78,47.208-71.756,42.828-83.939   l-1.701-2.847c-1.143-1.714-4.093-3.282-8.846-4.712c-4.764-1.427-10.853-1.663-18.278-0.712l-82.224,0.568   c-1.332-0.472-3.234-0.428-5.712,0.144c-2.475,0.572-3.713,0.859-3.713,0.859l-1.431,0.715l-1.136,0.859   c-0.952,0.568-1.999,1.567-3.142,2.995c-1.137,1.423-2.088,3.093-2.848,4.996c-8.952,23.031-19.13,44.444-30.553,64.238   c-7.043,11.803-13.511,22.032-19.418,30.693c-5.899,8.658-10.848,15.037-14.842,19.126c-4,4.093-7.61,7.372-10.852,9.849   c-3.237,2.478-5.708,3.525-7.419,3.142c-1.715-0.383-3.33-0.763-4.859-1.143c-2.663-1.714-4.805-4.045-6.42-6.995   c-1.622-2.95-2.714-6.663-3.285-11.136c-0.568-4.476-0.904-8.326-1-11.563c-0.089-3.233-0.048-7.806,0.145-13.706   c0.198-5.903,0.287-9.897,0.287-11.991c0-7.234,0.141-15.085,0.424-23.555c0.288-8.47,0.521-15.181,0.716-20.125   c0.194-4.949,0.284-10.185,0.284-15.705s-0.336-9.849-1-12.991c-0.656-3.138-1.663-6.184-2.99-9.137   c-1.335-2.95-3.289-5.232-5.853-6.852c-2.569-1.618-5.763-2.902-9.564-3.856c-10.089-2.283-22.936-3.518-38.547-3.71   c-35.401-0.38-58.148,1.906-68.236,6.855c-3.997,2.091-7.614,4.948-10.848,8.562c-3.427,4.189-3.905,6.475-1.431,6.851   c11.422,1.711,19.508,5.804,24.267,12.275l1.715,3.429c1.334,2.474,2.666,6.854,3.999,13.134c1.331,6.28,2.19,13.227,2.568,20.837   c0.95,13.897,0.95,25.793,0,35.689c-0.953,9.9-1.853,17.607-2.712,23.127c-0.859,5.52-2.143,9.993-3.855,13.418   c-1.715,3.426-2.856,5.52-3.428,6.28c-0.571,0.76-1.047,1.239-1.425,1.427c-2.474,0.948-5.047,1.431-7.71,1.431   c-2.667,0-5.901-1.334-9.707-4c-3.805-2.666-7.754-6.328-11.847-10.992c-4.093-4.665-8.709-11.184-13.85-19.558   c-5.137-8.374-10.467-18.271-15.987-29.691l-4.567-8.282c-2.855-5.328-6.755-13.086-11.704-23.267   c-4.952-10.185-9.329-20.037-13.134-29.554c-1.521-3.997-3.806-7.04-6.851-9.134l-1.429-0.859c-0.95-0.76-2.475-1.567-4.567-2.427   c-2.095-0.859-4.281-1.475-6.567-1.854l-78.229,0.568c-7.994,0-13.418,1.811-16.274,5.428l-1.143,1.711   C0.288,140.146,0,141.668,0,143.763c0,2.094,0.571,4.664,1.714,7.707c11.42,26.84,23.839,52.725,37.257,77.659   c13.418,24.934,25.078,45.019,34.973,60.237c9.897,15.229,19.985,29.602,30.264,43.112c10.279,13.515,17.083,22.176,20.412,25.981   c3.333,3.812,5.951,6.662,7.854,8.565l7.139,6.851c4.568,4.569,11.276,10.041,20.127,16.416   c8.853,6.379,18.654,12.659,29.408,18.85c10.756,6.181,23.269,11.225,37.546,15.126c14.275,3.905,28.169,5.472,41.684,4.716h32.834   c6.659-0.575,11.704-2.669,15.133-6.283l1.136-1.431c0.764-1.136,1.479-2.901,2.139-5.276c0.668-2.379,1-5,1-7.851   c-0.195-8.183,0.428-15.558,1.852-22.124c1.423-6.564,3.045-11.513,4.859-14.846c1.813-3.33,3.859-6.14,6.136-8.418   c2.282-2.283,3.908-3.666,4.862-4.142c0.948-0.479,1.705-0.804,2.276-0.999c4.568-1.522,9.944-0.048,16.136,4.429   c6.187,4.473,11.99,9.996,17.418,16.56c5.425,6.57,11.943,13.941,19.555,22.124c7.617,8.186,14.277,14.271,19.985,18.274   l5.708,3.426c3.812,2.286,8.761,4.38,14.853,6.283c6.081,1.902,11.409,2.378,15.984,1.427l73.087-1.14   c7.229,0,12.854-1.197,16.844-3.572c3.998-2.379,6.373-5,7.139-7.851c0.764-2.854,0.805-6.092,0.145-9.712   C546.782,404.25,546.115,401.725,545.451,400.298z"></path>';
				$onclick_action = 'window.open(this.href, \'vkwindow\',\'left=20,top=20,width=750,height=750,toolbar=0,resizable=1\');return false;';
				$link_title =  esc_html__('Share it','social-share-with-floating-bar');
				$action =  esc_html__('Share it','social-share-with-floating-bar');
			break;

			case 'email':

				$email_url = 'mailto:';
				$email_url .= '?subject=' . $title;
				$email_url .= '&amp;body=' . $description . '%20%20' . rawurlencode($link);

				$query = array(
					'subject' => apply_filters( 'update_social_share_with_floating_bar_email_subject', $title ),
					'body' => apply_filters( 'update_social_share_with_floating_bar_email_body', $description . ' ' . $link )
				);

				$popup_url = 'mailto:?' . http_build_query($query, null, '&amp;', PHP_QUERY_RFC3986);
				$svg_viewbox = '0 0 32 32';
				$svg = '<path d="M0 26.857v-19.429q0-1.179 0.839-2.018t2.018-0.839h26.286q1.179 0 2.018 0.839t0.839 2.018v19.429q0 1.179-0.839 2.018t-2.018 0.839h-26.286q-1.179 0-2.018-0.839t-0.839-2.018zM2.286 26.857q0 0.232 0.17 0.402t0.402 0.17h26.286q0.232 0 0.402-0.17t0.17-0.402v-13.714q-0.571 0.643-1.232 1.179-4.786 3.679-7.607 6.036-0.911 0.768-1.482 1.196t-1.545 0.866-1.83 0.438h-0.036q-0.857 0-1.83-0.438t-1.545-0.866-1.482-1.196q-2.821-2.357-7.607-6.036-0.661-0.536-1.232-1.179v13.714zM2.286 7.429q0 3 2.625 5.071 3.446 2.714 7.161 5.661 0.107 0.089 0.625 0.527t0.821 0.67 0.795 0.563 0.902 0.491 0.768 0.161h0.036q0.357 0 0.768-0.161t0.902-0.491 0.795-0.563 0.821-0.67 0.625-0.527q3.714-2.946 7.161-5.661 0.964-0.768 1.795-2.063t0.83-2.348v-0.438t-0.009-0.232-0.054-0.223-0.098-0.161-0.161-0.134-0.25-0.045h-26.286q-0.232 0-0.402 0.17t-0.17 0.402z"></path>';
			    $onclick_action = '';
			    $link_title =  esc_html__('Email','social-share-with-floating-bar');
			    $action =  esc_html__('Email','social-share-with-floating-bar');

			break;

			case 'link':

				$popup_url = $link;
				$svg_viewbox = '0 0 1024 1024';
				$svg = '<g><path class="path1" d="M440.236 635.766c-13.31 0-26.616-5.076-36.77-15.23-95.134-95.136-95.134-249.934 0-345.070l192-192c46.088-46.086 107.36-71.466 172.534-71.466s126.448 25.38 172.536 71.464c95.132 95.136 95.132 249.934 0 345.070l-87.766 87.766c-20.308 20.308-53.23 20.308-73.54 0-20.306-20.306-20.306-53.232 0-73.54l87.766-87.766c54.584-54.586 54.584-143.404 0-197.99-26.442-26.442-61.6-41.004-98.996-41.004s-72.552 14.562-98.996 41.006l-192 191.998c-54.586 54.586-54.586 143.406 0 197.992 20.308 20.306 20.306 53.232 0 73.54-10.15 10.152-23.462 15.23-36.768 15.23z"></path><path class="path2" d="M256 1012c-65.176 0-126.45-25.38-172.534-71.464-95.134-95.136-95.134-249.934 0-345.070l87.764-87.764c20.308-20.306 53.234-20.306 73.54 0 20.308 20.306 20.308 53.232 0 73.54l-87.764 87.764c-54.586 54.586-54.586 143.406 0 197.992 26.44 26.44 61.598 41.002 98.994 41.002s72.552-14.562 98.998-41.006l192-191.998c54.584-54.586 54.584-143.406 0-197.992-20.308-20.308-20.306-53.232 0-73.54 20.306-20.306 53.232-20.306 73.54 0.002 95.132 95.134 95.132 249.932 0.002 345.068l-192.002 192c-46.090 46.088-107.364 71.466-172.538 71.466z"></path></g>';
			    $onclick_action = '';
			    $link_title = esc_html__('Share Direct Link','social-share-with-floating-bar');
			    $action = esc_url( $link );

			break;

			default:

			break;
		}

		ob_start();
		if( isset( $popup_url ) && isset( $onclick_action ) && isset( $link_title ) && $svg_viewbox ){
			?>
			<a class="sswfb-button sswfb-button-<?php echo esc_attr( $service ); ?>"
				href="<?php echo esc_url( $popup_url ); ?>"
				onclick="<?php echo $onclick_action; ?>"
				title="<?php echo $link_title; ?>"
				target="_blank">
				<div class="sswfb-button-inner">
					<svg class="sswfb-icon"
						version="1.1"
						xmlns="http://www.w3.org/2000/svg"
						xmlns:xlink="http://www.w3.org/1999/xlink"
						viewBox="<?php echo $svg_viewbox; ?>">
						<?php if( isset( $svg ) ){ echo $svg; } ?>
					</svg>
					<span class="sswfb-share-text"><?php if( isset( $action ) ){ echo $action; } ?></span>
				</div>

				<?php if ( $service == 'link' ) : ?>
				<div class="sswfb-share-link-wrap">
					<input class="sswfb-share-link" type="text" value="<?php echo esc_url( $popup_url ); ?>" onclick="javascript:this.setSelectionRange(0, this.value.length); return false;"/>
				</div>
				<?php endif; ?>

			</a>
			<?php
		}
		return ob_get_clean();
	}// End get_button_html()

	/**
	 * Build the html the share button component
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function build_share_buttons ( $button_type) {
		global $post;

		$html = '';

		$sites = get_option( 'sswfb_social_sites' );

		if ( is_array( $sites ) ) {

			$extra_classes = '';
			$show_count = false;

			switch ( $button_type ) {

				case 'text':
				$extra_classes = 'sswfb-buttons-text';
				break;

				default:

				break;
			}

			$html = '<ul class="sswfb-buttons ' . $extra_classes . '">' . "\n";

			// Get post thumbnail if it has one
			$thumbnail_url = '';
			$thumbnail_id = get_post_thumbnail_id( $post->ID );

			if ( !empty( $thumbnail_id ) ) {
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id,'large', true );

				if ( $thumbnail ) {
					$thumbnail_url = $thumbnail[0];
				}
			}
			
			// Build the share button for every site selected in plugin's options
			foreach ( $sites as $site ) {

				$button = $this->get_button_html( $site, $post->ID, $show_count, $thumbnail_url );

				$html .= '<li>' . $button . '</li>' . "\n";
			}

			$html .= '</ul>' . "\n";
		}

		return $html;
	} // End build_share_buttons()

	/**
	 * Add the social sharing buttons either before or after the post content.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function add_share_buttons_to_post ( $content ) {

		global $post;
		$post_types = apply_filters( 'update_social_share_with_floating_bar_post_types', array('post') );

		$sswfb_ed_post_social_share = '';
		if( isset( $post->ID ) ){
			$sswfb_ed_post_social_share = get_post_meta( $post->ID, 'sswfb_ed_post_social_share', true );
		}
		if ( !is_singular( $post_types ) || $sswfb_ed_post_social_share ) {
			return $content;
		}

		$location = get_option( 'sswfb_share_location' );

		$button_type = get_option( 'sswfb_share_type' );

		if ( is_array( $location ) ) {
			foreach ($location as $position ) {
				if ( $position == 'before' ) {
					$content = $this->build_share_buttons( $button_type ) . $content;
				}
				if ( $position == 'after' ) {
					$content = $content . $this->build_share_buttons( $button_type );
				}
				if ( $position == 'floating-left' ) {
					$content = "<div class='social-bar-floating social-bar-floating-left'>" . $this->build_share_buttons( 'basic' )  . "</div>" . $content;
				}
				if ( $position == 'floating-right' ) {
					$content =  $content . "<div class='social-bar-floating social-bar-floating-right'>" .$this->build_share_buttons( 'basic' ). "</div>";
				}
			}
		}

		return $content;
	} // End add_share_buttons_to_post()

	

	/**
	 * Shortcode for adding the sharing buttons to content or templates
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function share_post_shortcode( $atts, $content = null ) {
		$options = shortcode_atts( array(
			'share_type' => 'text'
		), $atts );

		$button_type = $options['share_type'];

		return $this->build_share_buttons( $button_type );
	} // End share_post_shortcode()

	/**
	 * Add the social sharing buttons to post media.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function add_share_buttons_to_media ( $content ) {
		global $post;

		if ( !is_singular( 'post' ) ) {
			return $content;
		}

		// If show media buttons option turned off, don't do anything
		if ( !get_option( 'sswfb_show_media_buttons' ) ) {
			return $content;
		}
		
		// Apply shortcodes
		$content = do_shortcode( $content );
		
		// If content is empty don't do anything
		if ( trim( $content ) == '' ) {
			return $content;
		}

		$internalErrors = libxml_use_internal_errors(true);

		if ( ! class_exists( 'DOMDocument' ) ) {
		    $error_msg = __( 'Your webhost needs to install the DOM extension for PHP: http://www.php.net/en/dom.', 'social-share-with-floating-bar' );
		    trigger_error( $error_msg );
		} else {

			$dom = new DOMDocument();
		
			$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
			
			// Create wrapping div
			$wrapper_div = $dom->createElement( 'div' );
			$wrapper_div->setAttribute( 'class', 'sswfb-image-wrap' );

			// Find all images in post
			$ImageList = $dom->getElementsByTagName( 'img' );

			foreach ( $ImageList as $key => $Image ) {

				// Create share button list container
				$share_list = $dom->createElement( 'ul' );
				$share_list->setAttribute( 'class', 'sswfb-buttons' );

				$sites = get_option( 'sswfb_media_social_sites' );

				$random_id = substr( base64_encode( basename( $Image->getAttribute('src') ) ), 0, 15 );

				// Add a button for every site selected
				foreach ( $sites as $site ) {

					$button = $this->get_button_html( $site, $post->ID, false, $Image->getAttribute('src'), $random_id );

					$share_item = $dom->createDocumentFragment();
					$share_item->appendXML( mb_convert_encoding( $button, 'HTML-ENTITIES', 'UTF-8' ) );
					$share_list->appendChild( $share_item );

				}//End social sites foreach

				// If image link append wrapper to link, otherwise wrap image
				if ( $Image->parentNode->nodeName == 'a' ) {

					$link_parent = $Image->parentNode;

					$wrap_clone = $wrapper_div->cloneNode();

					$wrap_clone->setAttribute( 'id', $random_id );

					$link_parent->parentNode->replaceChild( $wrap_clone, $link_parent );
					$wrap_clone->appendChild( $link_parent );

					$wrap_clone->appendChild( $share_list );
					
				} else {
					
					$wrap_clone = $wrapper_div->cloneNode();

					$wrap_clone->setAttribute( 'id', $random_id );

					$Image->parentNode->replaceChild( $wrap_clone, $Image );
					$wrap_clone->appendChild( $Image );

					$wrap_clone->appendChild( $share_list );
					
				}

			}//End Images foreach

			libxml_clear_errors();
			libxml_use_internal_errors($internalErrors);
				
			//Fixed the issue with additional html tags loading
			$content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
		}

	   // return the processed content
	   return $content;
	
	} //End add_share_buttons_to_media()

}//End class Social_Share_With_Floating_Bar


// Admin API 
class Social_Share_With_Floating_Bar_Admin_API {

	/**
	 * Generate HTML for displaying fields
	 * @param  array   $field Field data
	 * @param  boolean $echo  Whether to echo the field HTML or return it
	 * @return void
	 */
	public function display_field ( $data = array(), $post = false, $echo = true ) {

		// Get field info
		if ( isset( $data['field'] ) ) {
			$field = $data['field'];
		} else {
			$field = $data;
		}

		// Check for prefix on option name
		$option_name = '';
		if ( isset( $data['prefix'] ) ) {
			$option_name = $data['prefix'];
		}

		// Get saved data
		$data = '';
		
		// Get saved option
		$option_name .= $field['id'];
		$option = get_option( $option_name );

		// Get data to display in field
		if ( isset( $option ) ) {
			$data = $option;
		}

		// Show default data if no option saved and default is supplied
		if ( $data === false && isset( $field['default'] ) ) {
			$data = $field['default'];
		} elseif ( $data === false ) {
			$data = '';
		}

		if (is_array($field['default']) && $data == '') {
			$data = array();
		}

		$html = '';

		switch( $field['type'] ) {

			case 'text':
			case 'url':
			case 'email':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '" />' . "\n";
			break;

			case 'password':
			case 'number':
			case 'hidden':
				$min = '';
				if ( isset( $field['min'] ) ) {
					$min = ' min="' . esc_attr( $field['min'] ) . '"';
				}

				$max = '';
				if ( isset( $field['max'] ) ) {
					$max = ' max="' . esc_attr( $field['max'] ) . '"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $min . '' . $max . '/>' . "\n";
			break;

			case 'text_secret':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="" />' . "\n";
			break;

			case 'textarea':
				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";
			break;

			case 'checkbox':
				$checked = '';
				if ( $data && 'on' == $data ) {
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
			break;

			case 'checkbox_multi':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( in_array( $k, $data ) ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '" class="checkbox_multi"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
			break;

			case 'radio':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( $k == $data ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
			break;

			case 'select':
				$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
				foreach ( $field['options'] as $k => $v ) {
					$selected = false;
					if ( $k == $data ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
				}
				$html .= '</select> ';
			break;

			case 'select_multi':
				$html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';
				foreach ( $field['options'] as $k => $v ) {
					$selected = false;
					if ( in_array( $k, $data ) ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
				}
				$html .= '</select> ';
			break;

			case 'image':
				$image_thumb = '';
				if ( $data ) {
					$image_thumb = wp_get_attachment_thumb_url( $data );
				}
				$html .= '<img id="' . esc_attr( $option_name ) . '_preview" class="image_preview" src="' . esc_url( $image_thumb ) . '" /><br/>' . "\n";
				$html .= '<input id="' . esc_attr( $option_name ) . '_button" type="button" data-uploader_title="' . __( 'Upload an image' , 'social-share-with-floating-bar' ) . '" data-uploader_button_text="' . __( 'Use image' , 'social-share-with-floating-bar' ) . '" class="image_upload_button button" value="'. __( 'Upload new image' , 'social-share-with-floating-bar' ) . '" />' . "\n";
				$html .= '<input id="' . esc_attr( $option_name ) . '_delete" type="button" class="image_delete_button button" value="'. __( 'Remove image' , 'social-share-with-floating-bar' ) . '" />' . "\n";
				$html .= '<input id="' . esc_attr( $option_name ) . '" class="image_data_field" type="hidden" name="' . esc_attr( $option_name ) . '" value="' . $data . '"/><br/>' . "\n";
			break;

			case 'color':
				?><div class="color-picker" style="position:relative;">
			        <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />
			        <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>
			    </div>
			    <?php
			break;

			default:

			break;

		}

		switch( $field['type'] ) {

			case 'checkbox_multi':
			case 'radio':
			case 'select_multi':
				$html .= '<br/><span class="description">' . esc_html( $field['description'] ) . '</span>';
			break;

			default:
				if ( ! $post ) {
					$html .= '<label for="' . esc_attr( $field['id'] ) . '">' . "\n";
				}

				$html .= '<span class="description">' . esc_html( $field['description'] ) . '</span>' . "\n";

				if ( ! $post ) {
					$html .= '</label>' . "\n";
				}
			break;
		}

		if ( ! $echo ) {
			return $html;
		}

		echo $html;

	}


}
/**
 * Returns the main instance of Social_Share_With_Floating_Bar to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Social_Share_With_Floating_Bar
 */
function Social_Share_With_Floating_Bar () {
	$instance = Social_Share_With_Floating_Bar::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Social_Share_With_Floating_Bar_Settings::instance( $instance );
	}

	return $instance;
}

$social_share_with_floating_bar = Social_Share_With_Floating_Bar();
