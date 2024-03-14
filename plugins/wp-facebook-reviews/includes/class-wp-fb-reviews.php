<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/includes
 * @author     Your Name <email@example.com>
 */
class WP_FB_Reviews {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_FB_Reviews_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugintoken    The string used to uniquely identify this plugin.
	 */
	protected $plugintoken;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	protected $_token;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	public function __construct() {

		$this->_token = 'wp-fb-reviews';
		$this->version = '13.2';
		//using this for development
		//$this->version = time();

		$this->load_dependencies();
		$this->set_locale();
			
		if (is_admin()) {
			$this->define_admin_hooks();
			
			//final check to see if uploads directory was created and is writable
			//if not then set to avatars and cache folders in plugin.
			$this->_check_upload_folder_creation();

			
		}
		$this->define_public_hooks();
		//save version number to db
		$this->_log_version_number();
		

	}
	
	/**
	 * change avatar and cache directory if we weren't able to create it.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function _check_upload_folder_creation () {

		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir_wprev = $upload_dir . '/wprevslider';

		if (is_dir($upload_dir_wprev)) {
			$fcreated = true;
			$dir_writable = substr(sprintf('%o', fileperms($upload_dir_wprev)), -4) == "0775" ? true : false;
		} else {
			$fcreated = false;
			$dir_writable = false;
		}
		
		if($dir_writable==true && $fcreated == true){
			$upload_dir_wprev_avatars = $upload_dir . '/wprevslider/avatars/';
			$upload_dir_wprev_cache = $upload_dir . '/wprevslider/cache/';
			$upload_url = $upload['baseurl'];
			$upload_url_wprev_avatars = $upload_url . '/wprevslider/avatars/';
			$upload_url_wprev_cache = $upload_url . '/wprevslider/cache/';
			//check for ssl
			if(is_ssl()) {
				$upload_url_wprev_avatars = str_replace( 'http://', 'https://', $upload_url_wprev_avatars );
				$upload_url_wprev_cache = str_replace( 'http://', 'https://', $upload_url_wprev_cache );
			}
		} else {
			//set the constants to plugin local folders
			$upload_dir_wprev_avatars = plugin_dir_path( __DIR__ ).'public/partials/avatars/';
			$upload_dir_wprev_cache = plugin_dir_path( __DIR__ ).'public/partials/cache/';
			$upload_url_wprev_avatars = plugins_url( 'public/partials/avatars/',  dirname(__FILE__)  );
			$upload_url_wprev_cache = plugins_url( 'public/partials/cache/',  dirname(__FILE__)  );
		}
		$img_locations['upload_dir_wprev_avatars']=$upload_dir_wprev_avatars;
		$img_locations['upload_url_wprev_avatars']=$upload_url_wprev_avatars;
		$img_locations['upload_dir_wprev_cache']=$upload_dir_wprev_cache;
		$img_locations['upload_url_wprev_cache']=$upload_url_wprev_cache;
		$img_locations = json_encode($img_locations);
		update_option( 'wprev_img_locations', $img_locations );
	}
	
	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		$current_version = get_option($this->_token . '_version', 0);
		update_option( $this->_token . '_version', $this->version );
		
		if($current_version!=$this->version){
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			
			$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				pageid varchar(150) DEFAULT '' NOT NULL,
				pagename tinytext NOT NULL,
				created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				created_time_stamp int(12) NOT NULL,
				reviewer_name tinytext NOT NULL,
				reviewer_email tinytext NOT NULL,
				company_name varchar(100) DEFAULT '' NOT NULL,
				company_title varchar(100) DEFAULT '' NOT NULL,
				company_url varchar(100) DEFAULT '' NOT NULL,
				reviewer_id varchar(50) DEFAULT '' NOT NULL,
				rating varchar(3) NOT NULL,
				recommendation_type varchar(12) DEFAULT '' NOT NULL,
				review_text text NOT NULL,
				hide varchar(3) DEFAULT '' NOT NULL,
				review_length int(5) NOT NULL,
				review_length_char int(5) NOT NULL,
				type varchar(20) DEFAULT '' NOT NULL,
				userpic varchar(500) DEFAULT '' NOT NULL,
				userpic_small varchar(500) DEFAULT '' NOT NULL,
				from_name varchar(20) DEFAULT '' NOT NULL,
				from_url varchar(800) DEFAULT '' NOT NULL,
				from_logo varchar(500) DEFAULT '' NOT NULL,
				from_url_review varchar(800) DEFAULT '' NOT NULL,
				review_title varchar(500) DEFAULT '' NOT NULL,
				categories text NOT NULL,
				posts text NOT NULL,
				consent varchar(3) DEFAULT '' NOT NULL,
				userpiclocal varchar(500) DEFAULT '' NOT NULL,
				hidestars varchar(3) DEFAULT '' NOT NULL,
				miscpic varchar(500) DEFAULT '' NOT NULL,
				location varchar(500) DEFAULT '' NOT NULL,
				verified_order varchar(10) DEFAULT '' NOT NULL,
				language_code varchar(10) DEFAULT '' NOT NULL,
				unique_id tinytext DEFAULT '' NOT NULL,
				meta_data text DEFAULT '' NOT NULL,
				custom_data text DEFAULT '' NOT NULL,
				custom_stars text DEFAULT '' NOT NULL,
				owner_response text NOT NULL,
				sort_weight int(5) NOT NULL,
				tags text NOT NULL,
				mediaurlsarrayjson text NOT NULL,
				mediathumburlsarrayjson text NOT NULL,
				reviewfunnel varchar(3) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			//create template posts table in dbDelta 
			$table_name = $wpdb->prefix . 'wpfb_post_templates';
			
			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(200) DEFAULT '' NOT NULL,
				template_type varchar(7) DEFAULT '' NOT NULL,
				style int(2) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				display_num int(2) NOT NULL,
				display_num_rows int(3) NOT NULL,
				load_more varchar(3) DEFAULT '' NOT NULL,
				load_more_text varchar(50) DEFAULT '' NOT NULL,
				display_order varchar(10) DEFAULT '' NOT NULL,
				display_order_second varchar(10) DEFAULT '' NOT NULL,
				hide_no_text varchar(3) DEFAULT '' NOT NULL,
				template_css text NOT NULL,
				min_rating int(2) NOT NULL,
				min_words int(4) NOT NULL,
				max_words int(4) NOT NULL,
				word_or_char varchar(5) DEFAULT '' NOT NULL,
				rtype varchar(200) DEFAULT '' NOT NULL,
				rpage varchar(1000) DEFAULT '' NOT NULL,
				createslider varchar(3) DEFAULT '' NOT NULL,
				numslides int(2) NOT NULL,
				sliderautoplay varchar(3) DEFAULT '' NOT NULL,
				sliderdirection varchar(12) DEFAULT '' NOT NULL,
				sliderarrows varchar(3) DEFAULT '' NOT NULL,
				sliderdots varchar(3) DEFAULT '' NOT NULL,
				sliderdelay int(2) NOT NULL,
				sliderspeed int(5) NOT NULL,
				sliderheight varchar(3) DEFAULT '' NOT NULL,
				slidermobileview varchar(5) DEFAULT '' NOT NULL,
				showreviewsbyid varchar(600) DEFAULT '' NOT NULL,
				template_misc text DEFAULT '' NOT NULL,
				read_more varchar(3) DEFAULT '' NOT NULL,
				read_more_num int(4) NOT NULL,
				read_more_text varchar(20) DEFAULT '' NOT NULL,
				facebook_icon varchar(3) DEFAULT '' NOT NULL,
				facebook_icon_link varchar(3) DEFAULT '' NOT NULL,
				google_snippet_add varchar(3) DEFAULT '' NOT NULL,
				google_snippet_type varchar(50) DEFAULT '' NOT NULL,
				google_snippet_name varchar(500) DEFAULT '' NOT NULL,
				google_snippet_desc varchar(1000) DEFAULT '' NOT NULL,
				google_snippet_business_image varchar(500) DEFAULT '' NOT NULL,
				google_snippet_more text DEFAULT '' NOT NULL,
				cache_settings varchar(5) DEFAULT '' NOT NULL,
				review_same_height varchar(3) DEFAULT '' NOT NULL,
				add_profile_link varchar(3) DEFAULT '' NOT NULL,
				display_order_limit varchar(3) DEFAULT '' NOT NULL,
				display_masonry varchar(3) DEFAULT '' NOT NULL,
				read_less_text varchar(20) DEFAULT '' NOT NULL,
				string_sel varchar(3) DEFAULT '' NOT NULL,
				string_selnot varchar(3) DEFAULT '' NOT NULL,
				string_text varchar(300) DEFAULT '' NOT NULL,
				string_textnot varchar(300) DEFAULT '' NOT NULL,
				showreviewsbyid_sel varchar(9) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			
			dbDelta( $sql );
			
			$table_name_getapps = $wpdb->prefix . 'wpfb_gettwitter_forms';
			$sql_reviewfunnel = "CREATE TABLE $table_name_getapps (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(200) DEFAULT '' NOT NULL,
				site_type varchar(20) DEFAULT '' NOT NULL,
				query text DEFAULT '' NOT NULL,
				endpoint varchar(3) DEFAULT '' NOT NULL,
				last_ran int(12) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				blocks varchar(4) DEFAULT '' NOT NULL,
				profile_img varchar(7) DEFAULT '' NOT NULL,
				categories text NOT NULL,
				posts text NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_reviewfunnel );
			
						//create directories in uploads folder for avatar and cache_settings
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir_wprev = $upload_dir . '/wprevslider';
			//check folder permissions, delete if false
			if (is_dir($upload_dir_wprev)) {
				$dir_writable = substr(sprintf('%o', fileperms($upload_dir_wprev)), -4) == "0775" ? true : false;
				if($dir_writable==false){
					//delete the directory and sub directories
					$this->wpprorev_rmrf_fb($upload_dir_wprev);
				}
			}
			if (! is_dir($upload_dir_wprev)) {
			   mkdir( $upload_dir_wprev, 0775 );
			   chmod($upload_dir_wprev, 0775);
			}
			$upload_dir_wprev_avatars = $upload_dir . '/wprevslider/avatars';
			if (! is_dir($upload_dir_wprev_avatars)) {
			   mkdir( $upload_dir_wprev_avatars, 0775 );
			   chmod($upload_dir_wprev_avatars, 0775);
			}
			$upload_dir_wprev_cache = $upload_dir . '/wprevslider/cache';
			if (! is_dir($upload_dir_wprev_cache)) {
			   mkdir( $upload_dir_wprev_cache, 0775 );
			   chmod($upload_dir_wprev_cache, 0775);
			}
			
		}
	} // End _log_version_number ()
	
		//used to remove directories
	public function wpprorev_rmrf_fb( $dir )
	{
		foreach ( glob( $dir ) as $file ) {
			
			if ( is_dir( $file ) ) {
				$this->wpprorev_rmrf_fb( "{$file}/*" );
				rmdir( $file );
			} else {
				unlink( $file );
			}
		
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_FB_Reviews_Loader. Orchestrates the hooks of the plugin.
	 * - WP_FB_Reviews_i18n. Defines internationalization functionality.
	 * - WP_FB_Reviews_Admin. Defines all hooks for the admin area.
	 * - WP_FB_Reviews_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-fb-reviews-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-fb-reviews-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-fb-reviews-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-fb-reviews-public.php';
		
		/**
		 * The class responsible for the widget admin and public
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-fb-reviews-widget.php';
		
		/**
		 * The class responsible for displaying review template via do_action in template file
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-fb-reviews-template_action.php';

		
		/**
		 * The class responsible for parsing yelp and tripadvisor pages
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wppro_simple_html_dom.php';
		
		/**
		 * The class responsible for making tritter calls
		 */
		 //autoload correct classes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/twitteroauth/autoload.php';
		
		//register the loader
		$this->loader = new WP_FB_Reviews_Loader();
		

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_FB_Reviews_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_FB_Reviews_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WP_FB_Reviews_Admin( $this->get_token(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// NEW-------
		//register our wpfbr_facebook_init to the admin_init action hook, add setting inputs
		$this->loader->add_action('admin_init', $plugin_admin, 'wpfbr_facebook_init');
		
		//End New ---------------
		
		// register our wpfbr_settings_init to the admin_init action hook, add setting inputs
		//$this->loader->add_action('admin_init', $plugin_admin, 'wpfbr_settings_init');
		
		//add menu page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_pages' );
		
		//add ajax for adding feedback to table
		$this->loader->add_action( 'wp_ajax_wpfb_get_results', $plugin_admin, 'wpfb_process_ajax' );
		
		//add ajax for downloading images to local
		$this->loader->add_action( 'wp_ajax_wpfb_avatar_tolocal', $plugin_admin, 'wprevpro_download_avatar_tolocal' ); 
		
		//add ajax for adding FB backup method feedback to table
		//$this->loader->add_action( 'wp_ajax_wpfb_fb_backup_reviews', $plugin_admin, 'wprevpro_ajax_download_fb_backup' );
		
		//add select shortcode list to post edit page
		//$this->loader->add_action( 'media_buttons', $plugin_admin, 'add_sc_select',11 ); 
		//$this->loader->add_action( 'admin_head', $plugin_admin, 'button_js' ); 
		
		//add ajax for searching for tweets
		$this->loader->add_action( 'wp_ajax_wprp_twitter_gettweets', $plugin_admin, 'wprp_twitter_gettweets_ajax' ); 
		
		//add ajax for saving tweet
		$this->loader->add_action( 'wp_ajax_wprp_twitter_savetweet', $plugin_admin, 'wprp_twitter_savetweet_ajax' ); 
		//add ajax for deleting tweet
		$this->loader->add_action( 'wp_ajax_wprp_twitter_deltweet', $plugin_admin, 'wprp_twitter_deltweet_ajax' ); 
		
		//for displaying leave review admin notice
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wprp_admin_notice__success' ); 
		//add_action( 'admin_notices', 'sample_admin_notice__success' );
		
		//dashboard widget to show newest reviews
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'wprevpro_dashboard_widget' );
		
		//add custom link to menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wprev_add_external_link_admin_submenu' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'wpse_66021_add_jquery' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WP_FB_Reviews_Public( $this->get_token(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//add shortcode shortcode_wprev_usetemplate
		$plugin_public->shortcode_wprev_usetemplate();
		
		//add ajax updating missing images
		if ( is_admin() ) {
			//add ajax updating missing images
			$this->loader->add_action( 'wp_ajax_wprp_update_profile_pic', $plugin_public, 'wppro_update_profile_pic_ajax' );
			//$this->loader->add_action( 'wp_ajax_nopriv_wprp_update_profile_pic', $plugin_public, 'wppro_update_profile_pic_ajax' );
		}
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
	
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_token() {
		return $this->_token;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WP_FB_Reviews_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
