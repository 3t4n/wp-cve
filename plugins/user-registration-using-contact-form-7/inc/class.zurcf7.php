<?php
/**
 * ZURCF7 Class
 *
 * Handles the plugin functionality.
 *
 * @package WordPress
 * @package Plugin name
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'ZURCF7' ) ) {

	/**
	 * The main ZURCF7 class
	 */
	class ZURCF7 {

		private static $_instance = null;

		var $admin = null,
		    $front = null,
		    $lib   = null;

		public static function instance() {

			if ( is_null( self::$_instance ) )
				self::$_instance = new self();

			return self::$_instance;
		}

		function __construct() {
			
			add_action( 'plugins_loaded', array( $this, 'action__plugins_loaded' ), 1 );
			add_filter( 'plugin_action_links',array( $this,'action__zurcf7_plugin_action_links'), 10, 2 );

			#get Contact form data in admin
			add_action("wp_ajax_get_cf7_form_data", array($this,"fn_get_cf7_form_data"));
			add_action("wp_ajax_nopriv_get_cf7_form_data", array($this,"fn_get_cf7_form_data"));

		}

		
		/**
		 * Action: plugins_loaded
		 *
		 * - Plugin load function
		 *
		 * @method action__plugins_loaded
		 *
		 * @return [type] [description]
		*/
		function action__plugins_loaded() {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
				add_action( 'admin_notices', array( $this, 'action__zurcf7_admin_notices_deactive' ) );
				deactivate_plugins( ZURCF7_PLUGIN_BASENAME );
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}

			# Action to load custom post type
			add_action( 'init', array( $this, 'action__init' ) );

			global $wp_version;

			# Set filter for plugin's languages directory
			$ZURCF7_lang_dir = dirname( ZURCF7_PLUGIN_BASENAME ) . '/languages/';
			$ZURCF7_lang_dir = apply_filters( 'ZURCF7_languages_directory', $ZURCF7_lang_dir );

			# Traditional WordPress plugin locale filter.
			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			# Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale',  $get_locale, 'plugin-text-domain' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'plugin-text-domain', $locale );

			# Setup paths to current locale file
			$mofile_global = WP_LANG_DIR . '/plugins/' . basename( ZURCF7_DIR ) . '/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				# Look in global /wp-content/languages/plugin-name folder
				load_textdomain( 'plugin-text-domain', $mofile_global );
			} else {
				# Load the default language files
				load_plugin_textdomain( 'plugin-text-domain', false, $ZURCF7_lang_dir );
			}
		}

		/**
		 * Action: admin_init
		 *
		 * Register custom post type
		 *
		 * @method action__init
		 *
		 */
		function action__init() {
			

			flush_rewrite_rules();

			# Post Type: Here you add your post type
			$labels = array(
				'name' => __( 'CF7 User Registration ', 'zeal-user-reg-cf7' ),
				'singular_name' => __( 'Registered User', 'zeal-user-reg-cf7' ),
				'all_items' => __( 'All Registered Users', 'zeal-user-reg-cf7' ),
				'edit_item' => __( 'Registered User Detail', 'zeal-user-reg-cf7' ),
				'search_items' => __( 'Search Registered User', 'zeal-user-reg-cf7' ),
				'view_item' => __( 'View Registered User', 'zeal-user-reg-cf7' ),
				'not_found' => __( 'No Registered User found', 'zeal-user-reg-cf7' ),
				'not_found_in_trash' => __( 'No Registered User found in Trash', 'zeal-user-reg-cf7' ),
			);

			$args = array(
				'label' => __( 'CF7 User Registration', 'zeal-user-reg-cf7' ),
				'labels' => $labels,
				'description' => '',
				'public' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'delete_with_user' => false,
				'show_in_rest' => false,
				'rest_base' => '',
				'menu_position' =>40,
				'has_archive' => false,
				'show_in_nav_menus' => false,
				'exclude_from_search' => true,
				'capability_type' => 'post',
				'capabilities' => array(
					'read' => true,
					'create_posts'  => false,
					'publish_posts' => true,
				),
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => false,
				'query_var' => false,
				'supports' => array( 'title' ),
			);

			register_post_type( ZURCF7_POST_TYPE, $args );
		}

		
		/**
		 *
		 * Action: admin_notices
		 *
		 * Admin notice of activate pugin.
		 */
		function action__zurcf7_admin_notices_deactive() {
			echo '<div class="error">' .
					sprintf(
						__( '<p><strong><a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a></strong> is required to use <strong>%s</strong>.</p>', 'zeal-user-reg-cf7' ),
						'User Registration using Contact Form 7'
					) .
				'</div>';
		}

		/**
		 * Action: wp_ajax_get_cf7_form_data
		 *
		 * Get the selected Contact form 7 data
		 *
		 * @method action__wpcf7forms_abandoned
		 *
		 */
		function fn_get_cf7_form_data(){

			//Get current saved CF7 ID
			$zurcf7_formid = (get_option( 'zurcf7_formid')) ? get_option( 'zurcf7_formid') : "";

			$html .= '<option value="">Select field</option>';
			if(!empty(sanitize_text_field($_POST['zurcf7_formid']))){

				//get tag for specific tag
				$cf7 = WPCF7_ContactForm::get_instance(sanitize_text_field($_POST['zurcf7_formid']));
				$tags = $cf7->collect_mail_tags();

				foreach($tags as $tag){
					$html .= '<option value="'.$tag.'">['.$tag.']</option>';
				}

				//if already saved CF7 ID
				if( $zurcf7_formid == sanitize_text_field($_POST['zurcf7_formid']) ){

					$zurcf7_email_field = (get_option( 'zurcf7_email_field')) ? get_option( 'zurcf7_email_field') : "";
					$zurcf7_username_field = (get_option( 'zurcf7_username_field')) ? get_option( 'zurcf7_username_field') : "";
					$zurcf7_userrole_field = (get_option( 'zurcf7_userrole_field')) ? get_option( 'zurcf7_userrole_field') : "";

					//Start Save ACF Fields
					if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
						$returnfieldarr = zurcf7_ACF_filter_array_function();
						if(!empty($returnfieldarr)){
							$count = 0;
							$zurcf7_acf_field = array();
							foreach ($returnfieldarr['response'] as $value) { 
								$field_name = $value['field_name'];
								if($count != 3) {
									// Perform blank check before updating option
									if (!empty($field_name)) {
										//$zurcf7_acf_field[] = get_option($field_name);
										$zurcf7_ACF_field[] = (get_option($field_name)) ? get_option($field_name) : "";
									}
								}
							$count++;
							}
						}
					}
					//End Save ACF Fields
					$zurcf7_fb_signup_app_id = (get_option( 'zurcf7_fb_signup_app_id')) ? get_option( 'zurcf7_fb_signup_app_id') : "";
					$zurcf7_fb_app_secret = (get_option( 'zurcf7_fb_app_secret')) ? get_option( 'zurcf7_fb_app_secret') : "";
					// Start FB Fields

					// End FB Fields

					$res = array(
						'response' =>'success',
						'is_exists' => 'yes',
						'formtag' => $html,
						'zurcf7_email_field' => $zurcf7_email_field,
						'zurcf7_username_field' => $zurcf7_username_field,
						'zurcf7_userrole_field' => $zurcf7_userrole_field,
						'zurcf7_ACF_field' => $zurcf7_ACF_field,
						'zurcf7_fb_signup_app_id' => $zurcf7_fb_signup_app_id,
						'zurcf7_fb_app_secret' => $zurcf7_fb_app_secret,
					);

				}else{
					//all the form tags without selected value
					$res = array('response' =>'success', 'is_exists' => 'no', 'formtag' => __( $html, 'zeal-user-reg-cf7' ) );
				}
			}else{
				//No tag found
				$html = '<option value="">No tag found</option>';
				$res = array('response' =>'error', 'formtag' => __( $html, 'zeal-user-reg-cf7' ));
			}
			
			wp_send_json($res);
		}


		/**
		 * Action: plugin_action_links
		 *
		 * Add all plugin related links after active links.
		 *
		 * @method action__zurcf7_plugin_action_links
		 *
		 * @param  array  $links
		 * @param  path	  $file
		 *
		 * @return $links
		 */
		function action__zurcf7_plugin_action_links( $links, $file ) {
			if ( $file != ZURCF7_PLUGIN_BASENAME ) {
				return $links;
			}
			if ( is_plugin_active( 'user-registration-cf7/user-registration-cf7.php' ) )
			{
				
				$settings_link =  '<a href="'.admin_url('edit.php?post_type='.ZURCF7_POST_TYPE.'&page=zurcf7_settings', $scheme = 'admin' ).'">'.__( 'Settings', 'zeal-user-reg-cf7' ).'</a>';
				$support_link = '<a href="#" target="_blank">' .__( 'Support', 'zeal-user-reg-cf7' ). '</a>';
				$document_link = '<a href="#" target="_blank">' .__( 'Document', 'zeal-user-reg-cf7' ). '</a>';

				array_unshift( $links, $settings_link );
				array_unshift( $links, $support_link );
				array_unshift( $links, $document_link );
			}
			return $links;
		}

	}
}

function ZURCF7() {
	return ZURCF7::instance();
}

ZURCF7();
