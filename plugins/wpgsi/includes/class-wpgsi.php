<?php
/**
 * The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 * Also maintains the unique identifier of this plugin as well as the current version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpgsi
 * @subpackage Wpgsi/includes
 * @author     javmah <jaedmah@gmail.com>
 */
class Wpgsi {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpgsi_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		if(defined('WPGSI_VERSION')){
			$this->version = WPGSI_VERSION;
		} else {
			$this->version = '3.7.9';
		}

		$this->plugin_name = 'wpgsi';

		$this->load_dependencies();

		$this->set_locale();

		$this->define_admin_hooks();

		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 * Include the following files that make up the plugin:
	 *
	 * - Wpgsi_Loader. Orchestrates the hooks of the plugin.
	 * - Wpgsi_i18n. Defines internationalization functionality.
	 * - Wpgsi_Admin. Defines all hooks for the admin area.
	 * - Wpgsi_Public. Defines all hooks for the public side of the site.
	 *
	 * @since    1.0.0
	 * @access   private
	*/
	private function load_dependencies(){
		/**
		 * The class for common methods that are used in many different Classes.
		*/ 
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-wpgsi-common.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin.
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-wpgsi-loader.php';

		/**
		 * The class responsible for defining internationalization functionality of the plugin.
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-wpgsi-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'admin/class-wpgsi-events.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'admin/class-wpgsi-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'admin/class-wpgsi-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'admin/class-wpgsi-update.php';

		/**
		 * This Class is Responsible for Displaying the All the Google Sheet to User selected PAGE or POST 
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'admin/class-wpgsi-show.php';

		/**
		 * The class responsible for defining all actions that occur in the Inclued Google .
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-wpgsi-google-sheet.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		*/
		require_once plugin_dir_path(dirname( __FILE__ )) . 'public/class-wpgsi-public.php';
		
		$this->loader = new Wpgsi_Loader();
	}
	

	/**
	 * Define the locale for this plugin for internationalization.
	 * Uses the Wpgsi_i18n class in order to set the domain and to register the hook with WordPress.
	 * @since    1.0.0
	 * @access   private
	*/
	private function set_locale() {
		$plugin_i18n = new Wpgsi_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 * @since    1.0.0
	 * @access   private
	*/
	private function define_admin_hooks(){
		$common 	  	= new Wpgsi_common($this->get_plugin_name(), $this->get_version());																    # Common class object 
		$googleSheet    = new Wpgsi_Google_Sheet($this->get_plugin_name(), $this->get_version(), $common); 													# GOOGLE Service Account and Sheet API 
		
		$wpgsi_events 	= new Wpgsi_Events($this->get_plugin_name(), $this->get_version(), $common);														# Event class object 												    
		$this->loader->add_action('user_register', 	 	 		  				$wpgsi_events, 'wpgsi_wordpress_newUser', 300, 1);							# New User Event [user_register]
		$this->loader->add_action('profile_update',		 		  				$wpgsi_events, 'wpgsi_wordpress_profileUpdate', 300, 2);					# Update User Event [profile_update]
		$this->loader->add_action('delete_user', 			 		  			$wpgsi_events, 'wpgsi_wordpress_deleteUser', 300, 1);  						# Delete User Event [delete_user]
		$this->loader->add_action('wp_login', 			   	 		  			$wpgsi_events, 'wpgsi_wordpress_userLogin', 300, 2);						# User Logged In  [wp_login]
		$this->loader->add_action('clear_auth_cookie', 	 		  				$wpgsi_events, 'wpgsi_wordpress_userLogout', 300, 1);						# User Logged Out [wp_logout] 
		$this->loader->add_action('save_post', 			 		  				$wpgsi_events, 'wpgsi_wordpress_post', 300, 3);								# Wordpress Post  || Fires once a post has been saved. || 3 param 1.post_id 2.post 3.updates
		$this->loader->add_action('comment_post', 			 		  			$wpgsi_events, 'wpgsi_wordpress_comment', 300, 3);							# Wordpress comment_post  || Fires once a comment_post has been saved TO DB.
		$this->loader->add_action('edit_comment', 			 		  			$wpgsi_events, 'wpgsi_wordpress_edit_comment', 300, 2);						# Wordpress comment_post  || Fires once a comment_post has been saved TO DB.
		$this->loader->add_action('transition_post_status', 		  			$wpgsi_events, 'wpgsi_woocommerce_product', 300, 3);						# WooCommerce  Product save_post_product
		$this->loader->add_action('woocommerce_order_status_changed',			$wpgsi_events, 'wpgsi_woocommerce_order_status_changed', 300, 3);			# Woocommerce Order Status Changed
		$this->loader->add_action('woocommerce_new_order', 	 	  				$wpgsi_events, 'wpgsi_woocommerce_new_order_admin', 300, 1);				# WooCommerce New Order
		$this->loader->add_action('woocommerce_thankyou', 	 	  				$wpgsi_events, 'wpgsi_woocommerce_new_order_checkout', 300, 1);				# WooCommerce New Order
		$this->loader->add_action('wpcf7_before_send_mail', 		  			$wpgsi_events, 'wpgsi_cf7_submission');										# CF7 Submission a New Form 
		$this->loader->add_action('ninja_forms_after_submission',    			$wpgsi_events, 'wpgsi_ninja_forms_after_submission', 300, 1);				# Ninja form Submission a New Form 
		$this->loader->add_action('frm_after_create_entry', 		  			$wpgsi_events, 'wpgsi_formidable_after_save', 30, 2);						# formidable after create form data entry to DB
		$this->loader->add_action('wpforms_process', 		  		  			$wpgsi_events, 'wpgsi_wpforms_process', 30, 3);								# formidable after create form data entry to DB
		$this->loader->add_action('weforms_entry_submission', 		    		$wpgsi_events, 'wpgsi_weforms_entry_submission', 100, 4);					# weforms after create form data entry to DB				
		$this->loader->add_action('gform_after_submission', 		    		$wpgsi_events, 'wpgsi_gravityForms_after_submission', 100, 2);				# gravityForms after form submission			
		$this->loader->add_action('forminator_custom_form_submit_field_data', 	$wpgsi_events, 'wpgsi_forminator_custom_form_submit_field_data', 10, 2);	# forminator custom form submit field data		
		$this->loader->add_action('fluentform_before_submission_confirmation', 	$wpgsi_events, 'wpgsi_fluentform_before_submission_confirmation', 20, 3);	# fluent form submit field data	
		$this->loader->add_action('admin_notices',  							$wpgsi_events, 'wpgsi_event_notices');	
		# Fire the Database 
		$this->loader->add_action('shutdown',  									$wpgsi_events, 'wpgsi_database_data_update', 300);	
		
		$plugin_admin = new Wpgsi_Admin($this->get_plugin_name(), $this->get_version(), $googleSheet, $common);
		$this->loader->add_action('init', 										$plugin_admin, 'wpgsi_customPostType');										# creating custom post type on wpgsiIntegration
		$this->loader->add_action('admin_enqueue_scripts', 						$plugin_admin, 'wpgsi_enqueue_styles',	50);								# enqueue style sheet 
		$this->loader->add_action('admin_enqueue_scripts', 						$plugin_admin, 'wpgsi_enqueue_scripts',	50);								# enqueue_scripts Javascript 
		$this->loader->add_action('admin_menu', 								$plugin_admin, 'wpgsi_admin_menu');											# Menu Page
		$this->loader->add_action('admin_post_wpgsi_Integration', 				$plugin_admin, 'wpgsi_save_integration');									# save integration
		$this->loader->add_action('wp_ajax_wpgsi_WorksheetColumnsTitle',		$plugin_admin, 'wpgsi_WorksheetColumnsTitle');								# AJAX  || function name is [ wpgsi_ajax ] this Will Handle 2nd Part of Connection Form 
		$this->loader->add_action('wp_ajax_wpgsi_changeIntegrationStatus',		$plugin_admin, 'wpgsi_changeIntegrationStatus');							# AJAX  || change Integration Status >> 
		$this->loader->add_action('wp_ajax_wpgsi_changeRemoteUpdateStatus',		$plugin_admin, 'wpgsi_changeRemoteUpdateStatus');							# AJAX  || change Remote Update Status >> 
		$this->loader->add_action('wp_ajax_wpgsi_createSheetColumnTitles',		$plugin_admin, 'wpgsi_createSheetColumnTitles');							# AJAX  || change Remote Update Status >> 
		$this->loader->add_filter('plugin_action_links',						$plugin_admin, 'wpgsi_action_link', 10, 2);									# plugin action links	

		$this->loader->add_action('wpgsi_khatas',  								$plugin_admin, 'wpgsi_SendToGS', 10, 4);									# Core event Function 
		$this->loader->add_action('admin_notices',  							$plugin_admin, 'wpgsi_admin_notices');										# Admin notice For test And Debug 
		
		$wpgsi_update = new Wpgsi_Update($this->get_plugin_name(), $this->get_version(), $googleSheet, $plugin_admin, $common);							    # POST type update
		$this->loader->add_action('rest_api_init',  							$wpgsi_update, 'wpgsi_register_rest_route');								# Registering REST END Point, This Hook Will register Two Hooks, Two End point.  -accept -updates
		$this->loader->add_action('admin_notices',  							$wpgsi_update, 'wpgsi_update_notices');										# Admin notice  For test And Debug 	
		
		$wpgsi_show = new Wpgsi_Show($this->get_plugin_name(), $this->get_version(), $googleSheet, $plugin_admin, $common);							        # POST type update
		$this->loader->add_action('admin_enqueue_scripts', 						$wpgsi_show, 'wpgsi_show_enqueue_scripts',50);								# enqueue_scripts Javascript 
		$this->loader->add_action('admin_notices',	 							$wpgsi_show, 'wpgsi_show_notices');											# Admin notice  For test And Debug 	
		$this->loader->add_action('admin_menu', 					 			$wpgsi_show, 'wpgsi_show_menu');											# Sub-Menu Page
		$this->loader->add_action('admin_post_save_google_show',	 			$wpgsi_show, 'wpgsi_save_google_show');									
		$this->loader->add_action('wp_ajax_wpgsi_ajaxWorksheetData',	 		$wpgsi_show, 'wpgsi_ajaxWorksheetData');																	
		$this->loader->add_action('init',	 									$wpgsi_show, 'wpgsi_wpShortCode');		
		#wpgsi cron events 
		$this->loader->add_action('cron_schedules',  							$wpgsi_show, 'wpgsi_add_cron_schedule');									
		$this->loader->add_action('init',	 									$wpgsi_show, 'wpgsi_wp_next_scheduled');	
		$this->loader->add_action('wpgsi_every_5_minutes',	 					$wpgsi_show, 'wpgsi_every_5_minutes_cron');																
		$this->loader->add_action('wpgsi_every_10_minutes',	 					$wpgsi_show, 'wpgsi_every_10_minutes_cron');																
		$this->loader->add_action('wpgsi_every_15_minutes',	 					$wpgsi_show, 'wpgsi_every_15_minutes_cron');																
		$this->loader->add_action('wpgsi_every_30_minutes',	 					$wpgsi_show, 'wpgsi_every_30_minutes_cron');																
		$this->loader->add_action('wpgsi_every_hour',	 						$wpgsi_show, 'wpgsi_every_hour_cron');																
		$this->loader->add_action('wpgsi_every_two_hours',	 					$wpgsi_show, 'wpgsi_every_two_hours_cron');																
		$this->loader->add_action('wpgsi_every_three_hours',	 				$wpgsi_show, 'wpgsi_every_three_hours_cron');																
		$this->loader->add_action('wpgsi_every_five_hours',	 					$wpgsi_show, 'wpgsi_every_five_hours_cron');																
		$this->loader->add_action('wpgsi_every_seven_hours',	 				$wpgsi_show, 'wpgsi_every_seven_hours_cron');																
		$this->loader->add_action('wpgsi_every_twelve_hours',	 				$wpgsi_show, 'wpgsi_every_twelve_hours_cron');																
		$this->loader->add_action('wpgsi_every_day',	 						$wpgsi_show, 'wpgsi_every_day_cron');																
		$this->loader->add_action('wpgsi_every_two_day',	 					$wpgsi_show, 'wpgsi_every_two_day_cron');																
		$this->loader->add_action('wpgsi_every_three_day',	 					$wpgsi_show, 'wpgsi_every_three_day_cron');																
		$this->loader->add_action('wpgsi_every_five_day',	 					$wpgsi_show, 'wpgsi_every_five_day_cron');																
		$this->loader->add_action('wpgsi_every_week',	 						$wpgsi_show, 'wpgsi_every_week_cron');																

		$wpgsi_settings = new Wpgsi_Settings($this->get_plugin_name(), $this->get_version(), $wpgsi_events, $googleSheet, $common);
		$this->loader->add_action('admin_menu', 					 			$wpgsi_settings, 'wpgsi_settings_menu');									# Sub-Menu Page
		$this->loader->add_action('admin_post_google_settings',	 				$wpgsi_settings, 'wpgsi_google_settings');									# Settings Form Submission || Save google Forms  ||
		$this->loader->add_action('admin_footer',  				 				$wpgsi_settings, 'wpgsi_remove_log');										# Removing Loge From Database || After 100 
		$this->loader->add_action('admin_notices',  							$wpgsi_settings, 'wpgsi_settings_notices');									# Admin notice  For test And Debug 
	}
	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 * @since    1.0.0
	 * @access   private
	*/
	private function define_public_hooks(){
		$plugin_public = new Wpgsi_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 * @since    1.0.0
	*/
	public function run(){
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	*/
	public function get_plugin_name(){
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 * @since     1.0.0
	 * @return    Wpgsi_Loader   Orchestrates the hooks of the plugin.
	*/
	public function get_loader(){
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	*/
	public function get_version(){
		return $this->version;
	}
}