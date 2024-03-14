<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/public
 * @author     Your Name <email@example.com>
 */
class WP_FB_Reviews_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugintoken    The ID of this plugin.
	 */
	private $plugintoken;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugintoken       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	 
	private $_token;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	 
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		$this->version = $version;
		//$this->version = time();
		
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_FB_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_FB_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		 
		 //combining everything to one now
		 wp_register_style( 'wprev-fb-combine', plugin_dir_url( __FILE__ ) . 'css/wprev-fb-combine.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wprev-fb-combine' );
		
		
		//wp_register_style( 'wp-review-slider-pro-public_template1', plugin_dir_url( __FILE__ ) . 'css/wprev-public_template1.css', array(), $this->version, 'all' );
		//wp_enqueue_style( 'wp-review-slider-pro-public_template1' );

		//for rtl support
		// extra RTL stylesheet
		if ( is_rtl() )
		{
			wp_register_style( 'wp-review-slider-pro-public_template1_rtl', plugin_dir_url( __FILE__ ) . 'css/wprev-public_template1_rtl.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'wp-review-slider-pro-public_template1_rtl' );			
		}
		
		//wp_register_style( 'wprev_w3', plugin_dir_url( __FILE__ ) . 'css/wprev_w3.css', array(), $this->version, 'all' );
		
		
		//register slider stylesheet
		//wp_register_style( 'unslider', plugin_dir_url( __FILE__ ) . 'css/wprs_unslider.css', array(), $this->version, 'all' );
		//wp_register_style( 'unslider-dots', plugin_dir_url( __FILE__ ) . 'css/wprs_unslider-dots.css', array(), $this->version, 'all' );
		

		//wp_enqueue_style( 'wprev_w3' );
		//wp_enqueue_style( 'unslider' );
		//wp_enqueue_style( 'unslider-dots' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_FB_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_FB_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->_token."_unslider-swipe-min", plugin_dir_url( __FILE__ ) . 'js/wprs-unslider-swipe.js', array( 'jquery' ), $this->version, false );
		
		//wp_enqueue_script( $this->_token."_unslider-min", plugin_dir_url( __FILE__ ) . 'js/wprs-unslider-min.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script( $this->_token."_plublic", plugin_dir_url( __FILE__ ) . 'js/wprev-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->_token."_plublic", 'wprevpublicjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'wpfb_ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wprevpluginsurl' => wpfbrev_plugin_url
					)
				);

	}
	
	/**
	 * Register the Shortcode for the public-facing side of the site to display the template.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_wprev_usetemplate() {
	
				add_shortcode( 'wprevpro_usetemplate', array($this,'wprev_usetemplate_func') );
	}	 
	public function wprev_usetemplate_func( $atts, $content = null ) {
		//get attributes
		    $a = shortcode_atts( array(
				'tid' => '0',
				'bar' => 'something',
			), $atts );		//$a['tid'] to get id
	
				ob_start();
				include plugin_dir_path( __FILE__ ) . '/partials/wp-fb-reviews-public-display.php';
				return ob_get_clean();
	}
	
		/**
	 * Ajax, tries to update missing image src, facebook expires them.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_update_profile_pic_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$revid = sanitize_text_field($_POST['revid']);
		if($revid>0){
		//get review details, if FB then try to update it with call to fbapp.ljapps.com
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$reviewinfo = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$table_name." WHERE id=%d LIMIT 1", "$revid"), ARRAY_A);

			//check for type and continue if FB
			if($reviewinfo[0]['type']=="Facebook"){
				//set default image
				$newimagesrc['url'] = plugin_dir_url( __FILE__ )."/partials/imgs/fb_mystery_man_big.png";
				//now try to get from fb app.
				$option = get_option('wprevpro_options');
				$accesscode = $option['fb_app_code'];
				$tempurl = "https://fbapp.ljapps.com/ajaxgetprofilepic.php?q=getpic&acode=".$accesscode."&callback=cron&pid=".$reviewinfo[0]['pageid']."&rid=".$reviewinfo[0]['reviewer_id'];
				
				if (ini_get('allow_url_fopen') == true) {
					$data=file_get_contents($tempurl);
				} else if (function_exists('curl_init')) {
					$data=$this->file_get_contents_curl($tempurl);
				}
				
				$data = json_decode($data, true);
				$profileimgurl = $data['data'];
				
				//escape and add to db
				$escapedimgurl = esc_url( $profileimgurl);
				if($escapedimgurl!=''){
					$newimagesrc['url'] = $escapedimgurl;
					$temprevid = $reviewinfo[0]['id'];
					//update the database with this new image url
					$updatereviewsrc = $wpdb->query( $wpdb->prepare("UPDATE ".$table_name." SET userpic = %s WHERE id = %d AND reviewer_id = %s", $escapedimgurl, $temprevid, $reviewinfo[0]['reviewer_id'] ) );
					$temprevid ='';
				}
						
				
			}

		}
		exit();
	}
}
